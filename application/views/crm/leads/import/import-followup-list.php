<?php $lastRecord = end($record_list); ?>
<div class="modal-header">
    <h4 class="modal-title">
    	Followup for: <?php echo $lastRecord->name; ?>. Mobile No. <?php echo $lastRecord->country_code.$lastRecord->phone_number; ?> &nbsp;&nbsp;&nbsp;
        <a href="tel:<?php echo $lastRecord->country_code.$lastRecord->phone_number; ?>"><i class="fa fa-phone"></i></a>&nbsp;&nbsp;&nbsp;
        <a href="https://wa.me/<?php echo $lastRecord->country_code.$lastRecord->phone_number; ?>" target="_blank"><i class="fa fa-whatsapp"></i></a>
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

<div class="modal-body">
    <div id="record-export" class="record-table">
       <?php  if (!empty($record_list)){ ?>
        <!-- Scrollable Table Wrapper -->
        <h4>Followup Detail</h4>
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
function follow_up() {
    var form = '#followup-form';
    $(form).validate({
        onsubmit: false
    });

    if (!$(form).valid()) {
        return false;
    }
    
    var formData = new FormData($(form)[0]);
    
    $.ajax({
        url: site_url + 'leads/leads_followup_setup_post',
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json', // specify that the response will be JSON
        success: function(result) {
            if (result.message === "SUCCESS") {
				$('#followupModal').modal('hide');
				
				setTimeout(function() {
					list_record();
				}, 500);
            } else {
                // Handle error message from server
                console.log(result.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX error:", status, error);
            console.error("Response Text:", xhr.responseText);
        }
    });
}

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
