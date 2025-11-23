<?php $lastRecord = end($record_list); ?>
<div class="modal-header">
    <h4 class="modal-title">
    	Add Followup for: <?php echo $lastRecord->name; ?>. Mobile No. <?php echo $lastRecord->phone_number; ?> &nbsp;&nbsp;&nbsp;
        <a href="tel:<?php echo $lastRecord->phone_number; ?>"><i class="fa fa-phone"></i></a>&nbsp;&nbsp;&nbsp;
        <a href="https://wa.me/<?php echo $lastRecord->phone_number; ?>" target="_blank"><i class="fa fa-whatsapp"></i></a>
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

<div class="modal-body">
    <div id="record-export" class="record-table">
        <form id="followup-form" class="form-horizontal disabled-field Form">
            <input type="hidden" name="lead_id" value="<?php echo $lead_id; ?>" />
            <div class="form-row">
                <div class="form-group col-md-3 col-12">
                    <label class="col-form-label">Task Performed</label>
                    <select name="task_performed" class="form-control required">
                        <option value="">Select One</option>
                        <?php foreach(task_performed() as $k => $v){ ?>
                        <option value="<?=$k?>"><?=$v?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3 col-12">
                    <label class="col-form-label">Next Followup Date</label>
                    <input type="text" class="form-control datepicker required" name="followup_date">
                </div>
                <div class="form-group col-md-3 col-12">
                    <label class="col-form-label">Next Task</label>
                    <select name="next_task" class="form-control required">
                        <option value="">Select One</option>
                        <?php foreach(next_task() as $k => $v){ ?>
                        <option value="<?=$k?>"><?=$v?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3 col-12">
                    <label class="col-form-label">Status</label>
                    <select name="status" class="form-control required">
                        <option value="">Select One</option>
                        <?php foreach(lead_status() as $k => $v){ ?>
                        <option value="<?=$k?>"><?=$v?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-12">
                    <label class="control-label">⁠Remarks</label>
                    <textarea name="remarks" class="form-control"></textarea>
                </div>
                
                <div class="form-group col-12">
                    <button type="button" class="btn btn-info waves-effect" onclick="follow_up()">Save</button>
                </div>
            </div>
        </form>
        
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
