<div class="row">
    <div class="col-5"><a href="javascript:;" class="btn btn-info btn-small waves-effect" id="toggle-filter">Filter Show/Hide</a></div>
    <div class="col-7 text-right"><a href="<?php echo site_url('leads/add'); ?>" class="btn btn-info waves-effect">Create New Leads</a></div>
</div><br>

<div id="filter-form" style="display: none;">
    <form id="leads-form">
        <div class="form-row clearfix">
        	
        	<?php if(isset($data[0]) && $data[0] == 'activity-report') { ?>
            
            <div class="form-group col-lg-3 col-xs-12">
                <label class="col-form-label">Task Performed</label>
                <select name="task_performed" id="task-performed" class="form-control required">
                    <option value="">Select One</option>
                    <?php foreach(task_performed() as $k => $v){ ?>
                    <option value="<?=$k?>" <?php if (isset($_GET['task_performed']) && $k == $_GET['task_performed']) echo 'selected'; ?>><?=$v?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-lg-3 col-xs-12">
                <label class="col-form-label">Allocation</label>
                <select class="form-control select2" name="allocation" id="allocation-id">
                    <option value="">Select One</option>
                    <?php foreach($crm_user_list as $data): ?>
                    <option value="<?php echo $data->user_id; ?>" <?php if (isset($_GET['user_id']) && $data->user_id == $_GET['user_id']) echo 'selected'; ?>><?php echo $data->fullname; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <label class="col-form-label">Last Followup Date</label>
                <input type="text" id="last-followup-date" class="form-control">
            </div>
        	
        	<?php } else if(isset($data[0]) && $data[0] == 'kpi-report') { ?>
            
            <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <label class="col-form-label">Last Followup Date</label>
                <input type="text" id="last-followup-today-date" class="form-control">
            </div>
            
			<?php } else { ?>
            
            <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <label class="col-form-label">Lead ID</label>
                <input type="text" id="lead-id" class="form-control">
            </div>
            <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <label class="col-form-label">Last Followup Date cc</label>
                <input type="text" id="last-followup-date" class="form-control" value="<?php echo isset($_GET['last_followup_date']) ? $_GET['last_followup_date'] : ''; ?>">
            </div>
            <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <?php $next_followup_date_value = '';
                    if (isset($_GET['next_followup_date']) && !empty($_GET['next_followup_date'])) {
                        $next_followup_date_value = $_GET['next_followup_date'];
                    } else {
                        // Default to today's date
                        $today_follow_up_date = date('M d, Y') . ' - ' . date('M d, Y');
                        $next_followup_date_value = $today_follow_up_date;
                    }
                ?>
                <label class="col-form-label">Next Followup Date</label>
                <input type="text" id="next-followup-date" class="form-control"  value="<?php echo isset($_GET['next_followup_date']) && !empty($_GET['next_followup_date']) ? $_GET['next_followup_date'] : date('M d, Y') . ' - ' . date('M d, Y'); ?>">
            </div>
            <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <label class="col-form-label">Lead Added</label>
                <input type="text" id="lead-added-date" class="form-control">
            </div>
            <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <label class="col-form-label">Full Name</label>
                <input type="text" id="name" class="form-control">
            </div>
            <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <label class="col-form-label">Mobile No</label>
                <input type="text" id="phone-number" class="form-control">
            </div>
            <div class="form-group col-lg-3 col-xs-12">
                <label class="col-form-label">Task Performed</label>
                <select name="task_performed" id="task-performed" class="form-control required">
                    <option value="">Select One</option>
                    <?php foreach(task_performed() as $k => $v){ ?>
                    <option value="<?=$k?>" <?php if (isset($_GET['task_performed']) && $k == $_GET['task_performed']) echo 'selected'; ?>><?=$v?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-lg-3 col-xs-12">
                <label class="col-form-label">Next Task</label>
                <select name="next_task" id="next-task" class="form-control required">
                    <option value="">Select One</option>
                    <?php foreach(next_task() as $k => $v){ ?>
                    <option value="<?=$k?>" <?php if (isset($_GET['next_task']) && $k == $_GET['next_task']) echo 'selected'; ?>><?=$v?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-lg-3 col-xs-12">
                <label class="col-form-label">Lead Source</label>
                <select name="lead_source" id="lead-source" class="form-control required">
                    <option value="">Select One</option>
                    <?php foreach(lead_source() as $k => $v){ ?>
                    <option value="<?=$k?>" <?php if(@$record->lead_source == $k and $k !== "") echo 'selected="selected"'; ?>><?=$v?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-lg-3 col-xs-12">
                <label class="col-form-label">Project</label>
                <select class="form-control" name="project_id" id="project-id">
                    <option value="">Select One</option>
                    <?php foreach($project_list as $data): ?>
                    <option value="<?php echo $data->project_id; ?>"><?php echo $data->project_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-lg-3 col-xs-12">
                <label class="col-form-label">Allocation</label>
                <select class="form-control select2" name="allocation" id="allocation-id">
                    <option value="">Select One</option>
                    <?php foreach($crm_user_list as $data): ?>
                    <option value="<?php echo $data->user_id; ?>" <?php if (isset($_GET['user_id']) && $data->user_id == $_GET['user_id']) echo 'selected'; ?>><?php echo $data->fullname; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-lg-3 col-xs-12">
                <label class="col-form-label">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">Select One</option>
                    <?php foreach(lead_status() as $k => $v){ ?>
                    <option value="<?=$k?>" <?php if (isset($_GET['lead_status']) && $k == $_GET['lead_status']) echo 'selected'; ?>><?=$v?></option>
                    <?php } ?>
                </select>
            </div>
            
            <?php } ?>
        </div>
        
        <div class="form-row clearfix">
            <div class="form-group col-lg-3 col-xs-12">
            	<button type="button" class="btn btn-info waves-effect" id="search-leads">Search</button> &ensp;
                <button type="reset" class="btn btn-info waves-effect" id="reset-filters">Reset</button>
            </div>
        </div>
    </form><br />
</div>

<script>
$(document).ready(function(e) {
    // Toggle form visibility
    $('#toggle-filter').on('click', function() {
        $('#filter-form').toggle();
    });
});
</script>