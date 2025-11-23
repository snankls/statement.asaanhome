<?php if (!empty($record_list)){ ?>
<div id="advance-search" class="table-box complaints-table">
	<!-- <div class="text-right">
        <form id="pdf-form" method="post" action="<?php //echo base_url('pdf/trail_balance_download'); ?>">
            <input type="hidden" name="from_date" />
            <input type="hidden" name="to_date" />
            <button type="button" class="btn btn-dark waves-effect" onclick="download_pdf();"><span class="fa fa-edit"></span> Print</button>
        </form><br />
    </div> -->
    
    <table id="advance-search-dt" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th width="100">Date</th>
                <th width="100">Voucher No.</th>
                <th width="500">Narration</th>
                <!-- <th>Opening</th> -->
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_debit = 0;
            $total_credit = 0;
            $previous_balance = 0;
    
            foreach ($record_list as $data) {
                $total_debit += $data->debit;
                $total_credit += $data->credit;
    
                // Calculate current balance
                $current_balance = ($data->debit - $data->credit) + $previous_balance;
                $previous_balance = $current_balance;
            ?>
            <tr>
                <td><?php echo $data->voucher_date; ?></td>
                <td align="center"><a href="<?php echo site_url('voucher/view/'.$data->voucher_id); ?>" target="_blank"><?php echo $data->voucher_id; ?></a></td>
                <td><?php echo $data->narration; ?></td>
                <!-- <td><?php //echo number_format($data->opening_balance); ?></td> -->
                <td><?php echo number_format($data->debit); ?></td>
                <td><?php echo number_format($data->credit); ?></td>
                <td><?php echo number_format($current_balance); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
//Download PDF
function download_pdf(){
	var o = new Object();
	o.from_date	 = $('#from-date').val();
	o.to_date	 = $('#to-date').val();
	
	$('#pdf-form [name="from_date"]').val(o.from_date);
	$('#pdf-form [name="to_date"]').val(o.to_date);
	
	$('#pdf-form').submit();
}
</script>
<?php } else{ ?>
	<p>No Record Available.</p>
<?php } ?>
