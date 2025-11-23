<?php $last = $this->uri->total_segments();
$slug_url = $this->uri->segment($last); ?>
<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">
                	<div id="purchase-list">
                    	<form id="update-form" class="form-horizontal disabled-field Form" enctype="multipart/form-data" role="form" action="<?php echo site_url('teams/teams_setup_post/'.@$record->project_id); ?>">
                        	<input type="hidden" name="last_uri" value="project" />
                        	<div class="form-row">
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Team Name <span class="error-message">*</span></label>
                                    <input type="text" name="project_name" class="form-control required" value="<?php echo @$record->project_name; ?>">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label class="col-form-label">Team Leader</label>
                                    <select name="property_types" id="property_types" class="form-control select2 required">
                                        <?php foreach($manager_list as $data){ ?>
                                        <option value="<?php echo $data->user_id; ?>" <?php //if($data->user_id == @$record0->user_id) echo 'selected="selected"'; ?>><?php echo $data->fullname; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label class="col-form-label">Team Members</label>
                                    <select name="property_types" id="property_types" class="form-control select2 required" multiple="multiple">
                                        <?php foreach($member_list as $data){ ?>
                                        <option value="<?php echo $data->user_id; ?>" <?php if($data->user_id == @$record0->user_id) echo 'selected="selected"'; ?>><?php echo $data->fullname; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                
                                
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Area Unit <span class="error-message">*</span></label>
                                    <select name="area_unit" class="form-control required">
                                        <option value="">Select One</option>
                                        <?php foreach(area_unit() as $k => $v){ ?>
                                        <option value="<?=$k?>" <?php if(@$record->area_unit == $k and $k !== "") echo 'selected="selected"'; ?>><?=$v?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">City <span class="error-message">*</span></label>
                                    <input type="text" name="city" class="form-control required" value="<?php echo @$record->project_city; ?>">
                                </div>
                                <div class="form-group col-lg-12 col-xs-12">
                                	<label class="col-form-label">Description</label>
                                    <textarea class="form-control" name="description"><?php echo @$record->description; ?></textarea>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <button type="submit" class="btn btn-custom waves-effect waves-light form-submit-button">Save & Exit</button>
                                </div>
                            </div>
                        </form>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
<?php $property_type_names = array();
$property_types = explode(',', @$record->property_types);
foreach($property_types as $data) {
	$property_type_names[] = $data;
} ?>
$("#property_types").val(["<?php echo implode('","', $property_type_names); ?>"]).trigger("change");
</script>
