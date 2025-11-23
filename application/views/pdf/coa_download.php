<div class="coa-download">
    <figure style="text-align:center; margin:0;"><?php echo get_image($record_list[0]['image'], 'projects', 100); ?></figure>
    
    <table cellpadding="0" cellspacing="0" style="width:100%; font-size:10px; background-color:#CCC; margin:20px 0;">
        <thead>
            <tr>
                <td width="200"></td>
                <td align="center" style="text-align:center; padding:5px 20px; font-size:15px; font-weight:800;">Chart of Accounts</td>
                <td align="right" width="200" style="font-size:10px; float:right; padding:5px 20px;">Print Date: <?php echo date('d-F-Y'); ?></td>
            </tr>
        </thead>
    </table>
    
    <table cellpadding="0" cellspacing="0" style="width:100%; margin-top:30px; font-size:12px;">
        <thead>
            <tr>
                <th style="font-size:10px; border-bottom:1px solid #000; text-align:left; padding: 8px;">CODE</th>
                <th style="font-size:10px; border-bottom:1px solid #000; text-align:left; padding: 8px;">DESCRIPTION</th>
                <th style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">DEBIT</th>
                <th style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">CREDIT</th>
                <th style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;">BALANCE</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($record_list as $row) {
                $indent = str_repeat('&nbsp;', ($row['account_level'] - 1) * 6);
                $isBold = ($row['account_level'] == 1 || $row['account_level'] == 2);
            ?>
            <tr>
                <td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;">
                    <?php if ($isBold) { ?>
                        <strong><?php echo $indent . $row['sort_order']; ?></strong>
                    <?php } else { ?>
                        <?php echo $indent . $row['sort_order']; ?>
                    <?php } ?>
                </td>
                <td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;">
                    <?php if ($isBold) { ?>
                        <strong><?php echo $indent . $row['account_title']; ?></strong>
                    <?php } else { ?>
                        <?php echo $indent . $row['account_title']; ?>
                    <?php } ?>
                </td>
                <td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;" align="center">
                    <?php if ($isBold) { ?>
                        <strong><?php echo number_format($row['debit_total']); ?></strong>
                    <?php } else { ?>
                        <?php echo number_format($row['debit_total']); ?>
                    <?php } ?>
                </td>
                <td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;" align="center">
                    <?php if ($isBold) { ?>
                        <strong><?php echo number_format($row['credit_total']); ?></strong>
                    <?php } else { ?>
                        <?php echo number_format($row['credit_total']); ?>
                    <?php } ?>
                </td>
                <td style="font-size:10px; border-bottom: 1px solid #000; padding: 8px;" align="center">
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
                <td colspan="2" style="font-size:10px; border-bottom:1px solid #000; text-align:left; padding: 8px;"><strong>Grand Total</strong></td>
                <td align="center" style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;"><strong><?php echo number_format($grand_totals['debit']); ?></strong></td>
                <td align="center" style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;"><strong><?php echo number_format($grand_totals['credit']); ?></strong></td>
                <td align="center" style="font-size:10px; border-bottom:1px solid #000; text-align:center; padding: 8px;"><strong><?php echo number_format($grand_totals['balance']); ?></strong></td>
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
