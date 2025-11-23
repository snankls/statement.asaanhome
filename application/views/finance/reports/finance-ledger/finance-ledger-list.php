<?php if (!empty($record_list)){ ?>
<div id="advance-search" class="table-box complaints-table">
	<div class="text-right">
        <form id="pdf-form" method="post" action="<?php echo base_url('pdf/finance_ledger_download'); ?>">
            <input type="hidden" name="project_id" />
            <input type="hidden" name="query" />
            <input type="hidden" name="query_name" />
            <input type="hidden" name="from_date" />
            <input type="hidden" name="to_date" />
            <button type="button" class="btn btn-small btn-dark waves-effect" onclick="download_pdf();"><span class="fa fa-edit"></span> Print</button>
        </form><br />
    </div>
    
    <div class="text-right"><strong>Opening Balance</strong> = <?php echo number_format($record_list[0]->opening_balance); ?></div><br />
    <table id="advance-search-dt" class="table table-striped table-hover finance-ledger-list">
        <thead class="thead-dark">
            <tr>
                <th width="100">Date</th>
                <th width="100">Voucher No.</th>
                <th>Voucher Type</th>
                <th>Narration</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
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
                $balance += $data->debit - $data->credit;
			?>
            <tr>
                <td><?php echo get_date_string_sql($data->voucher_date); ?></td>
                <td align="center"><a href="<?php echo site_url('voucher/view/'.$data->voucher_id); ?>" target="_blank"><?php echo $data->voucher_id; ?></a></td>
                <td><?php echo voucher_book($data->book); ?></td>
                <td><?php echo $data->narration; ?></td>
                <td><?php echo number_format($data->debit); ?></td>
                <td><?php echo number_format($data->credit); ?></td>
                <td><?php echo number_format($balance); ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" align="center"><strong>Grand Total:</strong></td>
                <td><strong><?php echo number_format($total_debit); ?></strong></td>
                <td><strong><?php echo number_format($total_credit); ?></strong></td>
                <td><strong><?php echo number_format($balance); ?></strong></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
//Download PDF
function download_pdf(){
	var o = new Object();
	o.project_id = $('#project_id option:selected').val();
	o.query		= $('#query option:selected').val();
	o.query_name = $('#query option:selected').text();
	o.from_date	= $('#from-date').val();
	o.to_date	= $('#to-date').val();
	
	$('#pdf-form [name="project_id"]').val(o.project_id);
	$('#pdf-form [name="query"]').val(o.query);
	$('#pdf-form [name="query_name"]').val(o.query_name);
	$('#pdf-form [name="from_date"]').val(o.from_date);
	$('#pdf-form [name="to_date"]').val(o.to_date);
	
	$('#pdf-form').submit();
}
</script>
<?php } else{ ?>
    <p>No Record Available.</p>
<?php } ?>
