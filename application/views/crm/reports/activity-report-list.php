<div class="modal-body">
    <div id="record-export" class="record-table">
    	<?php  if (!empty($record_list)){ ?>
        <div class="table-responsive follow-table">
            <table id="followup-list-dt" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Followup Date</th>
                        <th>Followup Time</th>
                        <th>Task Performed</th>
                        <th>Next Followup Date</th>
                        <th>Next Task</th>
                        <th>Status</th>
                        <th>Task Performed By</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($record_list as $data){ ?>
                    <tr>
                        <td><?php echo date_only($data->last_followup_date); ?></td>
                        <td><?php echo time_only($data->last_followup_date); ?></td>
                        <td><?php echo task_performed($data->task_performed); ?></td>
                        <td><?php echo date_only($data->next_followup_date); ?></td>
                        <td><?php echo next_task($data->next_task); ?></td>
                        <td><?php echo lead_status($data->lead_status); ?></td>
                        <td><?php echo $data->lead_performed_by; ?></td>
                        <td><?php echo $data->remarks; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } else{ ?>
            <p>No Record Available.</p>
        <?php } ?>
    </div>
</div>

<script>
$(document).ready(function(e) {
	//Datatable
	$('#followup-list-dt').DataTable();
	
    //Datepicker
	jQuery(".datepicker").datepicker({
		"setDate": new Date(),
		autoclose: true,
		format: 'yyyy-mm-dd',
		todayHighlight: true,
		orientation: "bottom left",
	});
});
</script>
