<?php defined('BASEPATH') OR exit('No direct script access allowed');

function filter_by_level($accounts, $max_level)
{
    $filtered = [];
    
    foreach ($accounts as $account) {
        if ($account['account_level'] <= $max_level) {
            $filtered[] = $account;
        }
    }
    
    return $filtered;
}

function aggregate_coa_totals($records)
{
    $accounts = [];
    $account_map = [];
    $grand_totals = ['debit' => 0, 'credit' => 0, 'balance' => 0];

    // First pass: organize accounts by ID and build parent-child relationships
    foreach ($records as $row) {
        $account_id = $row->chart_of_account_id;
        $accounts[$account_id] = [
            'id' => $account_id,
            'account_title' => $row->account_title,
            'sort_order' => $row->sort_order,
            'account_level' => $row->account_level,
            'debit_total' => $row->debit_total,
            'credit_total' => $row->credit_total,
            'balance' => $row->debit_total - $row->credit_total,
            'parent_id' => $row->parent_id,
            'image' => $row->image ?? '',
            'children' => [],
            'original_debit' => $row->debit_total,
            'original_credit' => $row->credit_total
        ];
        $account_map[$account_id] = &$accounts[$account_id];
        
        // Sum only leaf nodes (accounts with no children) for grand totals
        // We'll adjust this after building the hierarchy
    }

    // Second pass: build hierarchy
    $root_accounts = [];
    foreach ($accounts as $id => &$account) {
        $parent_id = $account['parent_id'];
        if ($parent_id && isset($account_map[$parent_id])) {
            $account_map[$parent_id]['children'][] = &$account;
        } else {
            $root_accounts[] = &$account;
        }
    }

    // Third pass: calculate totals recursively and identify leaf nodes
    calculate_child_totals($root_accounts, $grand_totals);

    // Flatten the hierarchy for display
    $flattened = [];
    flatten_hierarchy($root_accounts, $flattened);
    
    return [
        'records' => $flattened,
        'totals' => $grand_totals
    ];
}

function calculate_child_totals(&$accounts, &$grand_totals)
{
    foreach ($accounts as &$account) {
        if (!empty($account['children'])) {
            calculate_child_totals($account['children'], $grand_totals);
            
            // Sum up child totals
            foreach ($account['children'] as $child) {
                $account['debit_total'] += $child['debit_total'];
                $account['credit_total'] += $child['credit_total'];
                $account['balance'] += $child['balance'];
            }
        } else {
            // This is a leaf node - add to grand totals
            $grand_totals['debit'] += $account['original_debit'];
            $grand_totals['credit'] += $account['original_credit'];
            $grand_totals['balance'] += ($account['original_debit'] - $account['original_credit']);
        }
    }
}

function calculate_filtered_totals($filtered_records, $level)
{
    $totals = ['debit' => 0, 'credit' => 0, 'balance' => 0];
    
    foreach ($filtered_records as $account) {
        // For level filtering, we want to sum only accounts at the selected level
        if ($account['account_level'] == $level) {
            $totals['debit'] += $account['debit_total'];
            $totals['credit'] += $account['credit_total'];
            $totals['balance'] += $account['balance'];
        }
    }
    
    return $totals;
}

function flatten_hierarchy($accounts, &$result, $level = 0)
{
    foreach ($accounts as $account) {
        $result[] = $account;
        if (!empty($account['children'])) {
            flatten_hierarchy($account['children'], $result, $level + 1);
        }
    }
}

function generate_statement_data($installments, $payments, $total_unit_price)
{
    $statement_rows = [];
    $total_due = 0;
    $total_paid = 0;
    $total_balance = 0;
    $total_surcharge = 0;

    $payment_index = 0;
    $payment_balance = isset($payments[$payment_index]) ? $payments[$payment_index]->challan_amount : 0;

    $today = date('Y-m-d');

    foreach ($installments as $i => $installment) {
        $due_date = $installment->date;
        $due_amount = $installment->amount;
        $paid_amount_so_far = 0;
        $first_row_done = false;

        // Handle zero-amount installment rows
        if ($due_amount == 0) {
            $statement_rows[] = [
                'month' => ($i + 1),
                'due_date' => get_date_string_sql($due_date),
                'due_amount' => 0,
                'paid_date' => '-',
                'paid_amount' => '-',
                'serial' => '-',
                'balance' => '-',
                'payment_mode' => '-',
                'reference' => '-',
                'surcharge' => 0
            ];
            continue;
        }

        // Process payments for this installment
        while ($paid_amount_so_far < $due_amount && isset($payments[$payment_index])) {
            $remaining_due = $due_amount - $paid_amount_so_far;
            $pay_this_round = min($remaining_due, $payment_balance);
            $current_payment = $payments[$payment_index];

            $payment_date = !empty($current_payment->challan_date) ? $current_payment->challan_date : $today;
            $daysDifference = max(0, floor((strtotime($payment_date) - strtotime($due_date)) / 86400));
            $surcharge_amount = ($daysDifference > 0 && $remaining_due > 0)
                ? round($remaining_due * 0.001 * $daysDifference)
                : 0;

            $row = [
                'month' => !$first_row_done ? ($i + 1) : '',
                'due_date' => !$first_row_done ? get_date_string_sql($due_date) : '',
                'due_amount' => !$first_row_done ? $due_amount : $remaining_due,
                'paid_date' => get_date_string_sql($payment_date),
                'paid_amount' => $pay_this_round,
                'serial' => '&nbsp;' . str_pad($current_payment->serial, 6, '0', STR_PAD_LEFT),
                'balance' => $remaining_due - $pay_this_round,
                'payment_mode' => payment_method($current_payment->challan_payment_method),
                'reference' => $current_payment->reference,
                'surcharge' => $surcharge_amount
            ];
            $statement_rows[] = $row;

            $total_paid += $pay_this_round;
            $total_surcharge += $surcharge_amount;

            $paid_amount_so_far += $pay_this_round;
            $payment_balance -= $pay_this_round;
            $first_row_done = true;

            if ($payment_balance <= 0) {
                $payment_index++;
                $payment_balance = isset($payments[$payment_index]) ? $payments[$payment_index]->challan_amount : 0;
            }
        }

        // If not fully paid → show pending row (only for past/current installments)
        if ($paid_amount_so_far < $due_amount && strtotime($due_date) <= strtotime($today)) {
            $remaining = $due_amount - $paid_amount_so_far;
            $days_unpaid = max(0, floor((strtotime($today) - strtotime($due_date)) / 86400));
            $surcharge_unpaid = ($days_unpaid > 0 && $remaining > 0)
                ? round($remaining * 0.001 * $days_unpaid)
                : 0;

            $statement_rows[] = [
                'month' => !$first_row_done ? ($i + 1) : '',
                'due_date' => !$first_row_done ? get_date_string_sql($due_date) : '',
                'due_amount' => !$first_row_done ? $due_amount : $remaining,
                'paid_date' => '-',
                'paid_amount' => '-',
                'serial' => '-',
                'balance' => $remaining,
                'payment_mode' => '-',
                'reference' => '-',
                'surcharge' => $surcharge_unpaid
            ];
            $total_surcharge += $surcharge_unpaid;
            $total_balance += $remaining;
        }
        // For future installments that aren't fully paid, only show if no payments have been made
        elseif (!$first_row_done && $paid_amount_so_far < $due_amount) {
            $statement_rows[] = [
                'month' => ($i + 1),
                'due_date' => get_date_string_sql($due_date),
                'due_amount' => $due_amount,
                'paid_date' => '-',
                'paid_amount' => '-',
                'serial' => '-',
                'balance' => '-',
                'payment_mode' => '-',
                'reference' => '-',
                'surcharge' => 0
            ];
        }

        $total_due += $due_amount;
    }

    return [
        'statement_rows' => $statement_rows,
        'total_due' => $total_due,
        'total_paid' => $total_paid,
        'total_balance' => $total_balance,
        'total_surcharge'=> $total_surcharge,
    ];
}

function generate_milestone_statement_data($milestones, $payments, $total_unit_price)
{
    $statement_rows = [];
    $total_due = 0;
    $total_paid = 0;
    $total_balance = 0;
    $total_surcharge = 0;

    $payment_index = 0;
    $payment_count = count($payments);
    
    $today = date('Y-m-d');

    foreach ($milestones as $i => $m) {
        $due_date = $m->achievement ? $m->achievement_date : '-';
        $due_amount = $m->amount;
        $paid_amount_so_far = 0;
        $first_row_done = false;

        // Milestone NOT achieved (no due date)
        if ($m->achievement != 1) {
            $payment_exists = false;
            
            // Check if any payments exist for current payment index
            if ($payment_index < $payment_count) {
                $payment_exists = true;
            }

            if (!$payment_exists) {
                $statement_rows[] = [
                    'month'        => $m->milestone_name,
                    'due_date'     => '-',
                    'due_amount'   => $due_amount,
                    'paid_date'    => '-',
                    'paid_amount'  => '-',
                    'serial'       => '-',
                    'balance'      => '-',
                    'payment_mode' => '-',
                    'reference'    => '-',
                    'surcharge'    => 0,
                ];
                continue;
            }
            $due_date = null;
        }

        // PROCESS PAYMENTS FOR THIS MILESTONE
        while ($paid_amount_so_far < $due_amount && $payment_index < $payment_count) {
            $current_payment = $payments[$payment_index];
            $payment_amount_available = $current_payment->challan_amount;
            
            $remaining_due = $due_amount - $paid_amount_so_far;
            $pay_this_round = min($remaining_due, $payment_amount_available);

            $payment_date = $current_payment->challan_date;
            $daysDifference = ($due_date && strtotime($payment_date) > strtotime($due_date)) 
                ? max(0, floor((strtotime($payment_date) - strtotime($due_date)) / 86400))
                : 0;

            $surcharge_amount = 0; // Your surcharge logic commented out

            $statement_rows[] = [
                'month'        => !$first_row_done ? $m->milestone_name : '',
                'due_date'     => !$first_row_done && $due_date ? get_date_string_sql($due_date) : '',
                'due_amount'   => !$first_row_done ? $due_amount : $remaining_due,
                'paid_date'    => get_date_string_sql($payment_date),
                'paid_amount'  => $pay_this_round,
                'serial'       => '&nbsp;' . str_pad($current_payment->serial, 6, '0', STR_PAD_LEFT),
                'balance'      => $remaining_due - $pay_this_round,
                'payment_mode' => payment_method($current_payment->challan_payment_method),
                'reference'    => $current_payment->reference,
                'surcharge'    => $surcharge_amount
            ];

            $total_paid += $pay_this_round;
            $total_surcharge += $surcharge_amount;

            $paid_amount_so_far += $pay_this_round;

            // Reduce the current payment's available amount
            $payments[$payment_index]->challan_amount -= $pay_this_round;

            // If current payment is fully used, move to next payment
            if ($payments[$payment_index]->challan_amount <= 0) {
                $payment_index++;
            }

            $first_row_done = true;
        }

        // PENDING ROW FOR REMAINING AMOUNT
        if ($paid_amount_so_far < $due_amount) {
            $remaining = $due_amount - $paid_amount_so_far;
            
            $days_unpaid = 0;
            if ($due_date && strtotime($today) > strtotime($due_date)) {
                $days_unpaid = max(0, floor((strtotime($today) - strtotime($due_date)) / 86400));
            }

            $surcharge_unpaid = 0; // Your surcharge logic commented out

            $statement_rows[] = [
                'month'        => !$first_row_done ? $m->milestone_name : '',
                'due_date'     => !$first_row_done && $due_date ? get_date_string_sql($due_date) : '',
                'due_amount'   => !$first_row_done ? $due_amount : $remaining,
                'paid_date'    => '-',
                'paid_amount'  => '-',
                'serial'       => '-',
                'balance'      => $remaining,
                'payment_mode' => '-',
                'reference'    => '-',
                'surcharge'    => $surcharge_unpaid
            ];

            $total_surcharge += $surcharge_unpaid;
            $total_balance += $remaining;
        }
        // Future milestone with no payments
        elseif (!$first_row_done) {
            $statement_rows[] = [
                'month'        => $m->milestone_name,
                'due_date'     => $due_date ? get_date_string_sql($due_date) : '-',
                'due_amount'   => $due_amount,
                'paid_date'    => '-',
                'paid_amount'  => '-',
                'serial'       => '-',
                'balance'      => '-',
                'payment_mode' => '-',
                'reference'    => '-',
                'surcharge'    => 0
            ];
        }

        $total_due += $due_amount;
    }

    return [
        'statement_rows' => $statement_rows,
        'total_due'      => $total_due,
        'total_paid'     => $total_paid,
        'total_balance'  => $total_balance,
        'total_surcharge'=> $total_surcharge,
    ];
}

?>
