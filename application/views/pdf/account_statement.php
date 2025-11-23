<?php $total_duesurcharge_amount = 0;
foreach ($duesurcharge_list as $data) {
    $total_duesurcharge_amount += $data->amount;
} ?>
<div id="search-export">
	<figure style="text-align:center; margin:0;"><img src="<?php echo site_url('uploads/projects/'.$record_list->image); ?>" alt="Logo" width="100" /></figure>
    
    <table cellpadding="0" cellspacing="0" style="width:100%; font-size:10px; background-color:#CCC; margin:20px 0;">
    	<thead>
        	<tr>
            	<td width="200"></td>
            	<td align="center"; style="text-align:center; padding:5px 20px; font-size:15px; font-weight:800;">SCHEDULE OF INSTALMENTS</td>
            	<td align="right" width="200" style="font-size:10px; float:right; padding:5px 20px;">Print Date: <?php echo date('d-F-Y'); ?></td>
            </tr>
        </thead>
    </table>

	<?php if (!empty($record_list)){ ?>
	<table cellpadding="0" cellspacing="0" style="width:100%; font-size:10px;">
    	<thead>
        	<tr>
            	<th style="letter-spacing:1; text-align:left; font-size:12px; font-weight:800; margin-bottom:5px; width:33.33%;">PROPERTY DETAILS:</th>
            	<th style="letter-spacing:1; text-align:left; font-size:12px; font-weight:800; margin-bottom:5px; width:33.33%;">PRICING (PKR):</th>
            	<th style="letter-spacing:1; text-align:left; font-size:12px; font-weight:800; margin-bottom:5px; width:33.33%;">MEMBER DETAILS:</th>
            </tr>
        </thead>
    	<tbody>
        	<tr>
            	<td style="padding:4px 0;"><strong>Property Type:</strong> <?php echo $record_list->unit_category; ?></td>
            	<td style="padding:4px 0;"><strong>Unit Price:</strong> <?php echo number_format($record_list->total_price); ?></td>
            	<td style="padding:4px 0;"><strong>Name:</strong> <?php echo $record_list->customer_name; ?></td>
            </tr>
        	<tr>
            	<td style="padding:4px 0;"><strong>Unit #</strong> <?php echo $record_list->unit_number; ?></td>
            	<td style="padding:4px 0;"><strong>Payment Terms:</strong> <?php echo $record_list->payment_plan.' Months'; ?></td>
            	<td style="padding:4px 0;"><strong>CNIC:</strong> <?php echo $record_list->cnic; ?></td>
            </tr>
        	<tr>
            	<td style="padding:4px 0;"><strong>Type:</strong> <?php echo property_types($record_list->property_type); ?></td>
            	<td style="padding:4px 0;"><strong>Payment Plan:</strong> <?php echo $record_list->payment_plan.' Months'; ?></td>
            	<td style="padding:4px 0;"><strong>Serial #:</strong> <?php echo $record_list->registration; ?></td>
            </tr>
        	<tr>
            	<td style="padding:4px 0;"><strong>Unit Size:</strong> <?php echo $record_list->unit_size; ?></td>
            	<td style="padding:4px 0;"><strong>Booking:</strong> <?php echo number_format($first_installment_amount); ?></td>
            	<td style="padding:4px 0;"><strong>City:</strong> <?php echo $record_list->customer_city; ?></td>
            </tr>
        	<tr>
            	<td style="padding:4px 0;"><strong>Community:</strong> <?php echo $record_list->project_name; ?></td>
                <td>&nbsp;</td>
            	<td style="padding:4px 0;"><strong>Address:</strong> <?php echo $record_list->mailing_address; ?></td>
            </tr>
        	<tr>
            	<td style="padding:4px 0;"><strong>City:</strong> <?php echo $record_list->project_city; ?></td>
                <td>&nbsp;</td>
            	<td>&nbsp;</td>
            </tr>
        </tbody>
    </table>
    <?php } ?>
    
    <table cellpadding="0" cellspacing="0" style="width:100%; margin-top:30px; font-size:12px;">
        <thead class="thead-dark">
            <tr>
                <th style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">Month</th>
                <th style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">Due Date</th>
                <th style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">Due Amount</th>
                <th style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">Paid Date</th>
                <th style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">Paid Amount</th>
                <th style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">Serial</th>
                <th style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">Balance</th>
                <th style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">Payment Mode</th>
                <th style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">Reference</th>
                <th style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">Surcharge</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($statement_rows)): ?>
            <?php 
            $currentDate = date('Y-m-d');
            $total_dueSurcharge = 0;
            $total_duesurcharge_amount = 0; 

            foreach ($statement_rows as $row): ?>
            <tr>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;"><?= $row['month'] ?></td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;"><?= $row['due_date'] ?></td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">
                    <?= is_numeric($row['due_amount']) ? number_format($row['due_amount']) : $row['due_amount'] ?>
                </td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;"><?= $row['paid_date'] ?></td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">
                    <?= is_numeric($row['paid_amount']) ? number_format($row['paid_amount']) : $row['paid_amount'] ?>
                </td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;"><?= $row['serial'] ?></td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">
                    <?= is_numeric($row['balance']) ? number_format($row['balance']) : $row['balance'] ?>
                </td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;"><?= $row['payment_mode'] ?></td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;"><?= $row['reference'] ?></td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">
                    <?= is_numeric($row['surcharge']) ? number_format($row['surcharge']) : $row['surcharge'] ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="10" class="text-center">No statement data available</td>
            </tr>
            <?php endif; ?>
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
                <td style="font-size:10px; border-bottom:1px solid #000; padding: 8px;" colspan="2"><strong>Total Amount</strong></td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;"><strong><?= number_format($total_due) ?></strong></td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">-</td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;"><strong><?= number_format($total_paid) ?></strong></td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">-</td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;"><strong><?= number_format($total_balance) ?></strong></td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">-</td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">-</td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;"><strong><?= number_format($total_dueSurcharge) ?></strong></td>
            </tr>
            <tr>
                <td style="font-size:10px; border-bottom:1px solid #000; padding: 8px;" colspan="9"><strong>Total Due Surcharge Waive off</strong></td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;"><strong><?= number_format($total_duesurcharge_amount) ?></strong></td>
            </tr>
            <tr>
                <td style="font-size:10px; border-bottom:1px solid #000; padding: 8px;" colspan="9"><strong>Remaining Due Surcharge</strong></td>
                <td style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;"><strong><?= number_format($total_dueSurcharge - $total_duesurcharge_amount) ?></strong></td>
            </tr>
        </tfoot>
    </table>
    <div style="font-size:9px; font-style:italic; text-align:center; display:block; margin-top:10px;">*This statement is generated by the system and does not require a signature or stamp*</div>
    
</div>
