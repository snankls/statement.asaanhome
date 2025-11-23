<?php /*if (!empty($record_list)){ ?>
<div id="record-export" class="table-box record-table table-responsive w-100">
    <table id="record-list-dt" class="table table-striped table-hover w-100">
        <thead>
            <tr>
            	<th><input type="checkbox" name="checked_all" id="checked_all" /></th>
                <th>Action</th>
                <th>Create Date</th>
                <th>ID</th>
                <th>Full Name</th>
                <th>Project Name</th>
                <th>Allocation</th>
                <th>Lead Source</th>
                <th>Status</th>
                <th>Last Followup Date</th>
                <th>Task Performed</th>
                <th>Next Followup Date</th>
                <th>Next Task</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($record_list as $data){ ?>
            <tr>
            	<td>
                    <input type="hidden" name="lead_id" value="<?php echo $data->main_lead_id; ?>" />
                	<input type="checkbox" name="check" class="checkbox" />
                    <input type="hidden" name="phone_number" value="<?php echo $data->phone_number; ?>" />
                    <input type="hidden" name="email_address" value="<?php echo $data->email_address; ?>" />
                    <input type="hidden" name="city" value="<?php echo $data->city; ?>" />
                    <input type="hidden" name="country_code" value="<?php echo $data->country_code; ?>" />
                </td>
                <td>
                    <div class="dropdown">
                        <button class="btn  btn-small btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Action <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:;" data-toggle="modal" data-target="#followupModal" class="dropdown-item" onClick="add_followup('<?php echo $data->main_lead_id; ?>');">Add Followup</a></li>
                            <li><a href="javascript:;" class="dropdown-item" onClick="edit_record(this);">Edit Leads</a></li>
                        </ul>
                    </div>
                </td>
                <td><span data-toggle="tooltip" title="<?php echo time_only($data->lead_create_date); ?>"><?php echo date_only($data->lead_create_date); ?></span></td>
                <td><a href="tel:<?php echo $data->country_code.$data->phone_number; ?>" data-toggle="tooltip" title="<?php echo $data->country_code.$data->phone_number; ?>"><?php echo $data->main_lead_id; ?></a></td>
                <td><?php echo $data->name; ?></td>
                <td><?php echo $data->project_name; ?></td>
                <td><?php echo $data->fullname; ?></td>
                <td><?php echo lead_source($data->lead_source); ?></td>
                <td><?php echo lead_status($data->lead_status); ?></td>
                <td><?php echo date_only($data->last_followup_date); ?></td>
                <td><?php echo task_performed($data->task_performed); ?></td>
                <td><?php echo date_only($data->next_followup_date); ?></td>
                <td><?php echo next_task($data->next_task); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php } else{ ?>
	<p>No Record Available.</p>
<?php }*/ ?>

<script>
/*$(document).ready(function(e) {
	//Datatable
	$('#record-list-dt').DataTable({
		lengthMenu: [20, 50, 100, 200, 500, 1000, 2000, 5000],
	});
	
	//Checkbox Checked
	// When the main checkbox is clicked
	$('#checked_all').on('click', function() {
		// Set the checked property for each checkbox with class 'checkbox'
		$('.checkbox').prop('checked', this.checked);
	});
	
	// Optionally, if you want to uncheck the "checked_all" box when any individual checkbox is unchecked
	$('.checkbox').on('click', function() {
		if ($('.checkbox:checked').length === $('.checkbox').length) {
			$('#checked_all').prop('checked', true);
		} else {
			$('#checked_all').prop('checked', false);
		}
	});
});*/
</script>