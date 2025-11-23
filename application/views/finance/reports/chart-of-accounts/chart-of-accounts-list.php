<?php if (!empty($record_list)){ ?>
<div class="text-right">
    <form id="pdf-form" method="post" action="<?php echo base_url('pdf/coa_download'); ?>">
    	<input type="hidden" name="project_id" />
        <button type="button" class="btn btn-small btn-dark waves-effect" onclick="coa_download();"><span class="fa fa-edit"></span> Print</button>
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
        foreach ($record_list as $row) {
            $indent = str_repeat('&nbsp;', ($row['account_level'] - 1) * 6);
            $isBold = ($row['account_level'] == 1 || $row['account_level'] == 2);
        ?>
        <tr>
            <td width="70">
                <?php if ($isBold) { ?>
                    <strong><?php echo $indent . $row['sort_order']; ?></strong>
                <?php } else { ?>
                    <?php echo $indent . $row['sort_order']; ?>
                <?php } ?>
            </td>
            <td width="650">
                <?php if ($isBold) { ?>
                    <strong><?php echo $indent . $row['account_title']; ?></strong>
                <?php } else { ?>
                    <?php echo $indent . $row['account_title']; ?>
                <?php } ?>
            </td>
            <td width="50" align="center">
                <?php if ($isBold) { ?>
                    <strong><?php echo number_format($row['debit_total']); ?></strong>
                <?php } else { ?>
                    <?php echo number_format($row['debit_total']); ?>
                <?php } ?>
            </td>
            <td width="50" align="center">
                <?php if ($isBold) { ?>
                    <strong><?php echo number_format($row['credit_total']); ?></strong>
                <?php } else { ?>
                    <?php echo number_format($row['credit_total']); ?>
                <?php } ?>
            </td>
            <td width="50" align="center">
                <?php if ($isBold) { ?>
                    <strong><?php echo number_format($row['balance']); ?></strong>
                <?php } else { ?>
                    <?php echo number_format($row['balance']); ?>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2"><strong>Grand Total</strong></td>
            <td align="center"><strong><?php echo number_format($grand_totals['debit']); ?></strong></td>
            <td align="center"><strong><?php echo number_format($grand_totals['credit']); ?></strong></td>
            <td align="center"><strong><?php echo number_format($grand_totals['balance']); ?></strong></td>
        </tr>
    </tfoot>
</table>
<?php } else{ ?>
    <p>No Record Available.</p>
<?php } ?>
