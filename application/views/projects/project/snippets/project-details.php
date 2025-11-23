
<!-- project-details.php snippet file -->
<tr class="project-details-row">
	<td>
        <span class="drag-icon" data-id="<?php echo @$data->project_detail_id; ?>">
            <i class="fa fa-arrows-alt"></i>
        </span>
		<input type="hidden" name="sort_order[]" value="<?php echo isset($data->sort_order) ? $data->sort_order : '0'; ?>">
		<!-- Add a row index as a separate hidden field for mapping -->
		<input type="hidden" name="row_index[]" value="<?php echo isset($data->project_detail_id) ? $data->project_detail_id : ''; ?>" class="row-index">
		<input type="hidden" name="project_detail_id[]" value="<?php echo @$data->project_detail_id; ?>">
    </td>
	<?php if (@$projects->milestone_status != "Posted"): ?>
	<td>
		<input type="checkbox" class="case project-case" name="check[]" value="<?php echo @$data->project_detail_id; ?>">
	</td>
	<?php endif; ?>
	<td>
		<input type="text" class="form-control required" name="milestone[]" value="<?php echo isset($data->milestone_name) ? @$data->milestone_name : ''; ?>">
	</td>
	<?php if ($current_role_id == 1) { ?>
	<td class="text-center">
		<input type="hidden" name="achievement[]" value="<?php echo !empty($data->achievement) ? '1' : '0'; ?>" class="achievement-value">
		<input type="checkbox" class="achievement-checkbox" value="1" <?= (!empty($data->achievement)) ? 'checked' : '' ?>>
	</td>
	<?php } ?>
</tr>