<?php for ($i = 0; $i < 3; $i++) {
	if (!empty($record_list)) {
?>
	<div id="search-export" style="width:33.33%; float:left;">
		<table style="width:100%; margin:0 3%;" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td colspan="2" align="center" style="padding-bottom:10px;"><figure><img src="<?php echo site_url('uploads/projects/'.$record_list->image); ?>" alt="Logo" width="70" /></figure></td>
				</tr>
				<tr>
					<td colspan="2" align="center" style="font-size:11px; padding: 8px 10px;">RECEIPT CHALLAN</td>
				</tr>
				<tr>
					<td style="font-size:10px; border-bottom: 1px solid #000; border-top: 1px solid #000; padding: 8px 10px;">Date</td>
					<td style="font-size:10px; border-bottom: 1px solid #000; border-top: 1px solid #000; padding: 8px 10px;"><?php echo get_date_string_sql($record_list->challan_date); ?></td>
				</tr>
				<tr>
					<td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;">Name</td>
					<td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;"><?php echo $record_list->customer_name; ?></td>
				</tr>
				<tr>
					<td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;">Receipt Number</td>
					<td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;"><?php echo document_number(array('db_table' => 'booking_amounts', 'document_number' => $record_list->serial, 'prefix' => '&nbsp;')); ?></td>
				</tr>
				<tr>
					<td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;">Project</td>
					<td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;"><?php echo $record_list->project_name; ?></td>
				</tr>
				<tr>
					<td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;">Unit Number</td>
					<td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;"><?php echo $record_list->booking_unit; ?></td>
				</tr>
				<tr>
					<td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;">Registration #</td>
					<td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;"><?php echo $record_list->registration; ?></td>
				</tr>
				<tr>
					<td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;">Amount</td>
					<td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;"><?php echo 'PKR '.number_format($record_list->challan_amount).'/-'; ?></td>
				</tr>
				<tr>
					<td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;">Payment Mode</td>
					<td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;"><?php echo payment_method($record_list->challan_payment_method); ?></td>
				</tr>
				<?php if($record_list->challan_payment_method == 1 or $record_list->challan_payment_method == 3){ ?>
				<tr>
					<td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;">Reference</td>
					<td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;"><?php echo $record_list->reference; ?></td>
				</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2" style="padding:30px 0;">&nbsp;</td>
				</tr>
				<tr>
					<td align="center" style="font-size:10px; border-top: 1px solid #000; padding:5px 15px 0 0; border-right:10px solid #fff;">STAMP</td>
					<td align="center" style="font-size:10px; border-top: 1px solid #000; padding:5px 0 0 15px; border-left:10px solid #fff;">VERIFIED BY</td>
				</tr>

				<tr>
					<td colspan="2" align="center" style="font-size:9px; padding-top:10px;"><?php if($i==0) echo 'CUSTOMER COPY'; if($i==1) echo 'FINANCE COPY'; if($i==2) echo 'RECORD COPY'; ?></td>
				</tr>
			</tfoot>
		</table>
	</div>
<?php 
} else { 
?>
	<p>No Record found.</p>
<?php 
} 
}
?>
