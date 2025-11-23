<?php if (!empty($record_list)) { ?>
	<table class="table table-hover mb-0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td colspan="2" align="center" style="padding-bottom:10px;"><figure><img src="<?php echo site_url('uploads/projects/'.$record_list->image); ?>" alt="Logo" width="70" /></figure></td>
			</tr>
			<tr>
				<td colspan="2" align="center">RECEIPT CHALLAN</td>
			</tr>
			<tr>
				<td><strong>Date</strong></td>
				<td><?php echo get_date_string_sql($record_list->challan_date); ?></td>
			</tr>
			<tr>
				<td><strong>Name</strong></td>
				<td><?php echo $record_list->customer_name; ?></td>
			</tr>
			<tr>
				<td><strong>Receipt Number</strong></td>
				<td><?php echo document_number(array('db_table' => 'booking_amounts', 'document_number' => $record_list->serial, 'prefix' => '&nbsp;')); ?></td>
			</tr>
			<tr>
				<td><strong>Project</strong></td>
				<td><?php echo $record_list->project_name; ?></td>
			</tr>
			<tr>
				<td><strong>Unit Number</strong></td>
				<td><?php echo $record_list->unit_number; ?></td>
			</tr>
			<tr>
				<td><strong>Registration #</strong></td>
				<td><?php echo $record_list->registration; ?></td>
			</tr>
			<tr>
				<td><strong>Amount</strong></td>
				<td><?php echo 'PKR '.number_format($record_list->challan_amount).'/-'; ?></td>
			</tr>
			<tr>
				<td><strong>Payment Mode</strong></td>
				<td><?php echo payment_method($record_list->challan_payment_method); ?></td>
			</tr>
			<?php if($record_list->challan_payment_method == 1 or $record_list->challan_payment_method == 3){ ?>
			<tr>
				<td><strong>Reference</strong></td>
				<td><?php echo $record_list->reference; ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
<?php 
} else { 
?>
<p>No Record found.</p>
<?php 
}
?>
