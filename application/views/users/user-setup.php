<?php $last = $this->uri->total_segments();
$slug_url = $this->uri->segment($last); ?>
<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <div id="user-list">
                    	<form id="update-form" class="form-horizontal disabled-field Form" enctype="multipart/form-data" role="form" action="<?php echo site_url('user/user_setup_post/'.@$record->user_id); ?>">
							<input type="hidden" name="update_id" value="<?php echo @$record->user_id; ?>">
                        	<div class="form-row">
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label class="col-form-label">Full Name <span class="error-message">*</span></label>
                                    <input type="text" name="fullname" class="form-control required" value="<?php echo @$record->fullname; ?>">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label class="col-form-label">Username <span class="error-message">*</span></label>
                                    <input type="text" name="username" class="form-control required" value="<?php echo @$record->username; ?>" <?php if (!empty($record->username)) echo 'readonly="readonly"'; ?>>
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label class="col-form-label">Email Address <span class="error-message">*</span></label>
                                    <input type="text" class="form-control required" name="email_address" value="<?php echo @$record->email; ?>" <?php if (!empty($record->email)) echo 'readonly="readonly"'; ?>>
                                </div>
                                
                                <?php if ($slug_url == 'add'){ ?>
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label class="col-form-label">Password <span class="error-message">*</span></label>
                                    <input type="password" name="password" class="form-control required">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label class="col-form-label">Confirm Password <span class="error-message">*</span></label>
                                    <input type="password" name="confirm_password" class="form-control required">
                                </div>
                                <?php } ?>
                                
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label class="col-form-label">Mobile Number</label>
                                    <input type="text" name="mobile" class="form-control" value="<?php echo @$record->mobile; ?>">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label class="col-form-label">User Module <span class="error-message">*</span></label>
                                    <select name="user_module" id="user_module" class="form-control required" onchange="userModule();">
                                    	<option value="">Select One</option>
										<?php foreach(user_module() as $k => $v){ ?>
										<option value="<?=$k?>" <?php if(@$record->user_module == $k and $k !== "") echo 'selected="selected"'; ?>><?=$v?></option>
										<?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-xs-12" id="projects_group" <?php if ( @$record->user_module == 2 or $slug_url == 'add' ){ ?>style="display:none;"<?php } ?>>
                                    <label class="col-form-label">Select Projects</label>
                                    <select name="project_id" id="project_id" class="form-control select2" multiple="multiple">
                                        <?php foreach($project_list as $data){ ?>
                                        <option value="<?php echo $data->project_id; ?>"><?php echo $data->project_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-xs-12" id="team_name_group" <?php if ( @$record->user_module == 1 or $slug_url == 'add' ){ ?>style="display:none;"<?php } ?>>
                                    <label class="col-form-label">Team Name <span class="error-message">*</span></label>
                                    <select name="team_name" id="team_name" class="form-control select2" multiple="multiple">
										<?php foreach($team_list as $data){ ?>
                                        <option value="<?php echo $data->team_id; ?>"><?php echo $data->team_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label class="col-form-label">User Status <span class="error-message">*</span></label>
                                    <select name="status" class="form-control required">
										<?php foreach(enable_disable() as $k => $v){ ?>
										<option value="<?=$k?>" <?php if(@$record->status == $k and $k !== "") echo 'selected="selected"'; ?>><?=$v?></option>
										<?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label class="col-form-label">User Role <span class="error-message">*</span></label>
                                    <select name="role_id" id="role_id" class="form-control required">
                                    	<option value="">Select One</option>
                                        <?php foreach($user_role as $role): ?>
										<option value="<?php echo $role->role_id; ?>" data-module="<?php echo $role->role_module; ?>" <?php if($role->role_id == @$record->role_id) echo 'selected="selected"'; ?>><?php echo $role->role; ?></option>
										<?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-lg-6 col-xs-12">
                                    <label class="col-form-label">Address</label>
                                    <textarea class="form-control" name="address"><?php echo @$record->address; ?></textarea>
                                </div>
                                <div class="form-group col-lg-6 col-xs-12">
                                    <label class="col-form-label">Description</label>
                                    <textarea class="form-control" name="description"><?php echo @$record->description; ?></textarea>
                                </div>
                                <div class="form-group col-lg-12 col-xs-12">
                                    <label class="col-form-label">Image</label><br>
                                    <input type="file" name="user_image" value="<?php echo @$record->image; ?>">
                                    
                                    <?php if(!empty(@$record->image)) { ?>
                                    <input type="hidden" name="update_image" value="<?php echo @$record->image; ?>">
                                    <?php } ?>
                                    
                                    <?php if ($slug_url != 'add') {
                                    if(!empty(@$record->image)) { ?>
                                    <div class="activity-img"><a href="<?php echo site_url('uploads/users/'.@$record->image); ?>" class="lightbox-image"><img src="<?php echo site_url('uploads/users/'.@$record->image); ?>" alt="Image"></a></div>
                                    <?php }
                                    } ?>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-custom waves-effect waves-light form-submit-button">Save & Exit</button>
                                </div>
                            </div>
                        </form>
                	</div>
                    <!-- end row -->
                </div> <!-- end card-box -->
            </div><!-- end col -->
        </div>
        <!-- end row -->
	</div>
</div>

<script>
function userModule() {
    var selectedModule = $('#user_module').val();
//alert(selectedModule);
    // Show/hide options in role_id based on selected user_module
    $('#role_id option').each(function() {
        var roleModule = $(this).data('module');
        var roleValue = $(this).val();

        // Show the option based on the selected user module and role module
        if (selectedModule == '1') {
            // Show "Admin" and "Projects & Finance" roles
            if (roleModule === 'All' || roleModule === 'Projects & Finance') {
                $(this).show();
            } else {
                $(this).hide();
            }
        } else if (selectedModule == '2') {
            // Show "Admin", "Manager", and "Individual" roles
            if (roleModule === 'All' || roleModule === 'CRM' || roleValue === '8' || roleValue === '7') {
                $(this).show();
            } else {
                $(this).hide();
            }
        } else {
            // If no module is selected, show all options
            $(this).show();
        }
    });

    // Reset role selection if no valid role is visible
    if ($("#role_id option:visible:selected").length === 0) {
        $('#role_id').val('');
    }

    // Additional logic for showing/hiding fields based on the selected user module
    if (selectedModule == '1') { 
        $('#projects_group').show();
        $('#team_name_group').hide();
        $('#project_id').prop('required', true);
        $('#team_name').prop('required', false);
        $('#team_name').val(null).trigger('change');
    } else if (selectedModule == '2') { 
        $('#projects_group').hide();
        $('#team_name_group').show();
        $('#project_id').prop('required', false);
        $('#team_name').prop('required', true);
        $('#project_id').val(null).trigger('change');
    } else {
        $('#projects_group').hide();
        $('#team_name_group').hide();
        $('#project_id').prop('required', false);
        $('#team_name').prop('required', false);
    }
}


// Trigger the function on page load to set the initial visibility
$(document).ready(function() {
	$('#role_id option').hide();
	userModule();
	
	$('#role_id').val('<?php echo @$record->role_id; ?>');
});

<?php
//Teams Active
$team_name = array();
$team_id = explode(',', @$record->team_id);
foreach($team_id as $data) {
	$team_name[] = $data;
} ?>
$("#team_name").val(["<?php echo implode('","', $team_name); ?>"]).trigger("change");

//Porjects Active
<?php
$project_name = array();
$project_id = explode(',', @$record->project_id);
foreach($project_id as $data) {
	$project_name[] = $data;
} ?>
$("#project_id").val(["<?php echo implode('","', $project_name); ?>"]).trigger("change");
</script>
