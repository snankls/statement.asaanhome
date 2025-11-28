<div class="d-flex justify-content-center align-items-center">
    <div class="justify-content-center w-100 m-3">
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <div class="card overflow-hidden h-100 p-xxl-4 p-3 mb-0">
                                <div class="text-center mb-2">
                                    <img src="<?php echo site_url('assets/images/logo.png'); ?>" alt="Logo" height="40">
                                    <h4 class="mt-2">Asaan Home (Pvt) Ltd.</h4>
                                </div>

                                <?php if (!empty($statement_rows)): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <a href="<?php echo base_url('client'); ?>" class="btn btn-primary">
                                        <i class="fas fa-home"></i>&nbsp; Back to Statement Form
                                    </a>

                                    <button type="buton" class="btn btn-dark btn-small"onclick="account_statement('<?php echo $booking_id; ?>', '<?php echo $booking_inventory_id; ?>');">
                                        <i class="fa fa-file-text-o"></i>&nbsp; Download Statement
                                    </button>
                                </div>

                                <!-- Account Statement Section -->
                                <table id="installment-table" class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Month</th>
                                            <th>Due Date</th>
                                            <th>Due Amount</th>
                                            <th>Paid Date</th>
                                            <th>Paid Amount</th>
                                            <th>Serial</th>
                                            <th>Balance</th>
                                            <th>Payment Mode</th>
                                            <th>Reference</th>
                                            <th>Surcharge</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $currentDate = date('Y-m-d');
                                        $total_dueSurcharge = 0;
                                        $total_duesurcharge_amount = 0; 

                                        foreach ($statement_rows as $row): ?>
                                        <tr>
                                            <td><?= $row['month'] ?></td>
                                            <td><?= $row['due_date'] ?></td>
                                            <td>
                                                <?= is_numeric($row['due_amount']) ? number_format($row['due_amount']) : $row['due_amount'] ?>
                                            </td>
                                            <td><?= $row['paid_date'] ?></td>
                                            <td>
                                                <?= is_numeric($row['paid_amount']) ? number_format($row['paid_amount']) : $row['paid_amount'] ?>
                                            </td>
                                            <td><?= $row['serial'] ?></td>
                                            <td>
                                                <?= is_numeric($row['balance']) ? number_format($row['balance']) : $row['balance'] ?>
                                            </td>
                                            <td><?= $row['payment_mode'] ?></td>
                                            <td><?= $row['reference'] ?></td>
                                            <td>
                                                <?= is_numeric($row['surcharge']) ? number_format($row['surcharge']) : $row['surcharge'] ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <?php 
                                    $total_paid_amount = 0;
                                    $total_dueSurcharge = 0;
                                    $total_duesurcharge_amount = 0;
                                    $currentDate = date('Y-m-d');
                                    // 1) Sum actual surcharges from statement rows
                                    if (!empty($statement_rows)) {
                                        foreach ($statement_rows as $row) {
                                            if (is_numeric($row['surcharge'])) {
                                                $total_dueSurcharge += (float) $row['surcharge'];
                                            }
                                            if (is_numeric($row['paid_amount'])) {
                                                $total_paid_amount += (float) $row['paid_amount'];
                                            }
                                        }
                                    }

                                    // 2) Sum waived surcharge from DB list if applicable
                                    if (!empty($duesurcharge_list)) {
                                        foreach ($duesurcharge_list as $data) {
                                            $total_duesurcharge_amount += (float) $data->amount;
                                        }
                                    }
                                    ?>
                                    <tfoot> 
                                        <tr>
                                            <td colspan="2"><strong>Total Amount</strong></td>
                                            <td><strong><?= number_format($total_due) ?></strong></td>
                                            <td>-</td>
                                            <td><strong><?= number_format($total_paid) ?></strong></td>
                                            <td>-</td>
                                            <td><strong><?= number_format($total_balance) ?></strong></td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td><strong><?= number_format($total_dueSurcharge) ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="9"><strong>Total Due Surcharge Waive off</strong></td>
                                            <td><strong><?= number_format($total_duesurcharge_amount) ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="9"><strong>Remaining Due Surcharge</strong></td>
                                            <td><strong><?= number_format($total_dueSurcharge - $total_duesurcharge_amount) ?></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                
                                <?php else: ?>
                                <div class="alert alert-danger text-center mb-3"><?= $error_message ?></div>

                                <div class="text-center">
                                    <a href="<?php echo base_url('client'); ?>" class="btn btn-primary">
                                        <i class="fas fa-home"></i>&nbsp; Back to Statement Form
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
//Account Statement
function account_statement(update_id, booking_inventory_id) {
    const url = site_url + "client/account_statement?booking_id=" + update_id + "&inventory_id=" + booking_inventory_id;
    window.open(url, '_blank');
}
</script>