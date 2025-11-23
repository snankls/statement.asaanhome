<div id="search-export">
    <figure style="text-align:center; margin:0;"><?php echo get_image($record_list[0]->image, 'projects', 100); ?></figure>
    
	<table cellpadding="0" cellspacing="0" style="width:100%; font-size:10px; background-color:#CCC; margin:20px 0;">
    	<thead>
        	<tr>
            	<td width="200"></td>
            	<td align="center" style="text-align:center; padding:5px 20px; font-size:15px; font-weight:800;">Finance Ledger Report</td>
            	<td align="right" width="200" style="font-size:10px; float:right; padding:5px 20px;">Print Date: <?php echo date('d-F-Y'); ?></td>
            </tr>
        </thead>
    </table>
    
    <table cellpadding="0" cellspacing="0" style="width:100%; font-size:10px; margin-bottom:10px;">
        <tbody>
            <tr>
                <td style="font-size:10px; padding: 8px 0 10px;"><strong>Query:</strong> <?php echo $query_name; ?></td>
            </tr>
            <tr>
                <td style="font-size:10px; padding: 8px 0 10px;"><strong>From Date:</strong> <?php echo get_date_string_sql($from_date); ?></td>
            </tr>
            <tr>
                <td style="font-size:10px; padding: 8px 0 10px;"><strong>To Date:</strong> <?php echo get_date_string_sql($to_date); ?></td>
            </tr>
        </tbody>
    </table>
    
    <table cellpadding="0" cellspacing="0" style="width:100%; font-size:10px; margin:20px 0; border:1px solid #000; border-collapse:collapse;">
        <thead>
            <tr>
                <th width="70" style="border:1px solid #000; padding:4px 10px;"><strong>Date</strong></th>
                <th width="50" style="border:1px solid #000; padding:4px 10px;"><strong>V #</strong></th>
                <th width="90" style="border:1px solid #000; padding:4px 10px; text-align:center;"><strong>Voucher Type</strong></th>
                <th style="border:1px solid #000;"><strong>Narration</strong></th>
                <th width="80" style="border:1px solid #000; padding:4px 10px;"><strong>Debit</strong></th>
                <th width="80" style="border:1px solid #000; padding:4px 10px;"><strong>Credit</strong></th>
                <th width="80" style="border:1px solid #000; padding:4px 10px;"><strong>Balance</strong></th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_debit = 0;
            $total_credit = 0;
            $opening_balance = $record_list[0]->opening_balance;
            $balance = $opening_balance;
            foreach ($record_list as $data){
                $total_debit += $data->debit;
                $total_credit += $data->credit;
                $balance += $data->debit - $data->credit; ?>
            <tr>
                <td style="border:1px solid #000; padding:4px 5px;"><?php echo get_date_string_sql($data->voucher_date); ?></td>
                <td style="border:1px solid #000; padding:4px 5px;" align="center"><?php echo $data->voucher_id; ?></td>
                <td style="border:1px solid #000; padding:4px 5px;"><?php echo voucher_book($data->book); ?></td>
                <td style="border:1px solid #000; padding:4px 5px;"><?php echo $data->narration; ?></td>
                <td style="border:1px solid #000; padding:4px 5px;" align="center"><?php echo number_format($data->debit); ?></td>
                <td style="border:1px solid #000; padding:4px 5px;" align="center"><?php echo number_format($data->credit); ?></td>
                <td style="border:1px solid #000; padding:4px 5px;" align="center"><?php echo number_format($balance); ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" align="center" style="border:1px solid #000;"><strong>Grand Total:</strong></td>
                <td style="border:1px solid #000; padding:4px 10px;" align="center"><strong><?php echo number_format($total_debit); ?></strong></td>
                <td style="border:1px solid #000; padding:4px 10px;" align="center"><strong><?php echo number_format($total_credit); ?></strong></td>
                <td style="border:1px solid #000; padding:4px 10px;" align="center"><strong><?php echo number_format($balance); ?></strong></td>
            </tr>
        </tfoot>
    </table>
    
    <br /><br /><br />
    <div>
        <div style="border-top:1px solid #000; padding-top: 5px; width: 200px; text-align: center; float:left;">Approved By</div>
        <div style="border-top:1px solid #000; padding-top: 5px; width: 200px; text-align: center; float:right;">Check By</div>
        <div style="clear:both;"></div>
    </div>
</div>
