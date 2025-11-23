<div id="search-export">
    <figure style="text-align:center; margin:0;"><?php echo get_image($record_list->image, 'projects', 100); ?></figure>

    <table cellpadding="0" cellspacing="0" style="width:100%; font-size:10px; background-color:#CCC; margin:20px 0;">
        <thead>
            <tr>
                <td width="200"></td>
                <td align="center" style="text-align:center; padding:5px 20px; font-size:15px; font-weight:800;"><?php echo transaction_type($record_list->transaction_type); ?> Voucher</td>
                <td align="right" width="200" style="font-size:10px; float:right; padding:5px 20px;">Print Date: <?php echo date('d-F-Y'); ?></td>
            </tr>
        </thead>
    </table>

    <table cellpadding="0" cellspacing="0" style="width:100%; font-size:10px; margin-bottom:10px;">
        <tbody>
            <tr>
                <td style="font-size:10px;"><strong>Voucher ID:</strong> <?php echo $record_list->voucher_id; ?></td>
            </tr>
            <tr>
                <td style="font-size:10px; padding: 8px 0 10px;"><strong>Voucher Date:</strong> <?php echo get_date_string_sql($record_list->voucher_date); ?></td>
            </tr>
        </tbody>
    </table>

    <?php if (!empty($record_list)){ ?>
    <table cellpadding="0" cellspacing="0" style="width:100%; font-size:10px; border:1px solid #000; border-collapse:collapse;">
        <thead>
            <tr>
                <th style="border:1px solid #000; letter-spacing:1px; text-align:left; font-size:12px; padding:4px 7px;"><strong>Account Number</strong></th>
                <th style="border:1px solid #000; letter-spacing:1px; text-align:left; font-size:12px; padding:4px 7px;"><strong>Narration</strong></th>
                <th style="border:1px solid #000; letter-spacing:1px; text-align:left; font-size:12px; padding:4px 7px;"><strong>Debit</strong></th>
                <th style="border:1px solid #000; letter-spacing:1px; text-align:left; font-size:12px; padding:4px 7px;"><strong>Credit</strong></th>
                <th style="border:1px solid #000; letter-spacing:1px; text-align:left; font-size:12px; padding:4px 7px;"><strong>Book</strong></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i=1;
            $total_debit = 0;
            $total_credit = 0;
            foreach($voucher_details as $data) :
                $total_debit += $data->debit;
                $total_credit += $data->credit;
            ?>
            <tr>
                <td style="border:1px solid #000; padding:4px 7px;"><?php echo $data->account_title; ?></td>
                <td style="border:1px solid #000; padding:4px 7px; word-wrap: break-word; word-break: break-all;"><?php echo $data->narration; ?></td>
                <td style="border:1px solid #000; padding:4px 7px; width:85px;"><?php echo number_format($data->debit); ?></td>
                <td style="border:1px solid #000; padding:4px 7px; width:85px;"><?php echo number_format($data->credit); ?></td>
                <td style="border:1px solid #000; padding:4px 7px; width:85px;"><?php echo voucher_book($data->book); ?></td>
            </tr>
            <?php $i++; endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="border:1px solid #000; padding:10px 7px;">
                    <strong>Grand Total</strong><br />
                    <strong>PKR - <?php echo numberToWords($total_debit); ?></strong>
                </td>
                <td style="border:1px solid #000; padding:10px 7px;"><strong><?php echo number_format($total_debit); ?></strong></td>
                <td style="border:1px solid #000; padding:10px 7px;"><strong><?php echo number_format($total_credit); ?></strong></td>
                <td style="border:1px solid #000; padding:10px 7px;"></td>
            </tr>
        </tfoot>
    </table>
    <?php } ?>

    <br /><br /><br />
    <table cellpadding="0" cellspacing="0" style="width:100%; font-size:10px;">
        <tr>
            <td style="border-top:1px solid #000; width: 180px; text-align: center;">Approved By</td>
            <td>&nbsp;</td>
            <td style="border-top:1px solid #000; width: 180px; text-align: center;">Received By</td>
            <td>&nbsp;</td>
            <td style="border-top:1px solid #000; width: 180px; text-align: center;">Check By</td>
        </tr>
    </table>
</div>
