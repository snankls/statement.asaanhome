<tr class="voucher-details-row">
	<td>
		<input type="checkbox" class="case voucher-case" name="check[]" value="<?php echo @$data->voucher_detail_id; ?>">
		<input type="hidden" name="voucher_detail_id[]" value="<?php echo @$data->voucher_detail_id; ?>">
	</td>
	<td class="account-id">
    	<select class="form-control coa-4-dropdown required" name="account_number[]" data-selected="<?php echo @$data->account_number; ?>">
			<option value="">Select Account</option>
			
		</select>
    </td>
	<td><textarea class="form-control required" name="narration[]"><?php echo @$data->narration; ?></textarea></td>
	<td><input type="number" class="form-control required" name="debit[]" value="<?php echo isset($data->debit) ? @$data->debit : 0; ?>" onchange="calculation();" onkeyup="calculation();"></td>
	<td><input type="number" class="form-control required" name="credit[]" value="<?php echo isset($data->credit) ? @$data->credit : 0; ?>" onchange="calculation();" onkeyup="calculation();"></td>
	<td>
    	<select name="book[]" class="form-control required">
            <option value="">Select One</option>
            <?php foreach(voucher_book() as $k => $v){ ?>
            <option value="<?=$k?>" <?php if(@$data->book == $k and $k !== "") echo 'selected="selected"'; ?>><?=$v?></option>
            <?php } ?>
        </select>
    </td>
</tr>