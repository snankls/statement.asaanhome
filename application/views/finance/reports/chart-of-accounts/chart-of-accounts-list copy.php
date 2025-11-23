<?php if (!empty($record_list)){ ?>
<div class="text-right">
    <form id="pdf-form" method="post" action="<?php echo base_url('pdf/coa_download'); ?>">
    	<input type="hidden" name="project_id" />
        <button type="button" class="btn btn-dark waves-effect" onclick="coa_download();"><span class="fa fa-edit"></span> Print</button>
    </form><br />
</div>

<table class="table table-striped table-hover">
    <thead class="thead-dark">
        <tr>
            <th>CODE</th>
            <th>DESCRIPTION</th>
            <th align="center">DEBIT</th>
            <th align="center">CREDIT</th>
            <th align="center">BALANCE</th>
        </tr>
    </thead>
    <tbody>
        <?php 
		$total_debit = 0;
		$total_credit = 0;
		$balance = 0;
		foreach ($record_list as $row) {
			$total_debit += $row->debit_total;
			$total_credit += $row->credit_total;
			$balance += $row->debit_total - $row->credit_total;
			
			$indent = str_repeat('&nbsp;', ($row->account_level - 1) * 6); 
			$isBold = $row->account_level < 3 ? true : false;
        ?>
        <tr>
            <td width="70">
                <?php if ($isBold) { ?>
                    <strong><i><font color="black" face="arial"><?php echo $indent . $row->sort_order; ?></font></i></strong>
                <?php } else { ?>
                    <font color="black" face="arial"><?php echo $indent . $row->sort_order; ?></font>
                <?php } ?>
            </td>
            <td width="650">
                <?php if ($isBold) { ?>
                    <strong><i><font face="arial" color="black"><?php echo $indent . $row->account_title; ?></font></i></strong>
                <?php } else { ?>
                    <font face="arial" color="black"><?php echo $indent . $row->account_title; ?></font>
                <?php } ?>
            </td>
            
            <?php if ($isBold) { ?>
                <td width="50" align="center"><strong><font face="arial" color="black"><?php echo $row->debit_total; ?></font></strong></td>
                <td width="50" align="center"><strong><font face="arial" color="black"><?php echo $row->credit_total; ?></font></strong></td>
                <td width="50" align="center"><strong><font face="arial" color="black"><?php echo number_format($row->debit_total - $row->credit_total); ?></font></strong></td>
            <?php } else { ?>
                <td width="50" align="center"><font face="arial" color="black"><?php echo number_format($row->debit_total); ?></font></td>
                <td width="50" align="center"><font face="arial" color="black"><?php echo number_format($row->credit_total); ?></font></td>
                <td width="50" align="center"><font face="arial" color="black"><?php echo number_format($row->debit_total - $row->credit_total); ?></font></td>
            <?php } ?>
        </tr>
        <?php } ?>
    </tbody>
    <tfoot>
    	<tr>
        	<td colspan="2"><strong>Grand Total</strong></td>
        	<td align="center"><strong><?php echo number_format($total_debit); ?></strong></td>
        	<td align="center"><strong><?php echo number_format($total_credit); ?></strong></td>
        	<td align="center"><?php echo number_format($balance); ?></td>
        </tr>
    </tfoot>
</table>
<?php } else{ ?>
    <p>No Record Available.</p>
<?php } ?>
