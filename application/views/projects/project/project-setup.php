<?php $last = $this->uri->total_segments();
$slug_url = $this->uri->segment($last); ?>
<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                	<div id="purchase-list">
                    	<form id="update-form" class="form-horizontal disabled-field Form" enctype="multipart/form-data" role="form" action="<?php echo site_url('project/project_setup_post/'.@$record->project_id); ?>">
                        	<input type="hidden" name="last_uri" value="project" />
                        	<div class="form-row">
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Project Name <span class="error-message">*</span></label>
                                    <input type="text" name="project_name" class="form-control required" value="<?php echo @$record->project_name; ?>">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label class="col-form-label">Property Types</label>
                                    <select name="property_types" id="property_types" class="form-control select2 required" multiple="multiple">
                                        <?php foreach(property_types() as $k => $v){ ?>
                                        <option value="<?=$k?>" <?php if(@$record->property_type == $k and $k !== "") echo 'selected="selected"'; ?>><?=$v?></option>
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
                                <div class="form-group col-lg-12 col-xs-12">
                                    <label class="col-form-label">Project Image <span class="error-message">*</span></label><br>
                                    <input type="file" name="project_image" value="<?php echo @$record->image; ?>">
                                    
                                    <?php if(!empty(@$record->image)) { ?>
                                    <input type="hidden" name="update_project_image" value="<?php echo @$record->image; ?>">
                                    <?php } ?>
                                    
                                    <?php if ($slug_url != 'add') {
									if(!empty(@$record->image)) { ?>
                                    <div class="activity-img"><a href="<?php echo site_url('uploads/projects/'.@$record->image); ?>" class="lightbox-image"><img src="<?php echo site_url('uploads/projects/'.@$record->image); ?>" alt="Image"></a></div>
                                    <?php }
									} ?>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-lg-12 col-xs-12">
                                    <table id="project-table" class="table table-bordered sortable">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th width="40">&nbsp;</th>
                                                <?php if (@$record->milestone_status != "Posted"): ?><th width="40"><input class='check_all' type='checkbox' onclick="select_all()"/></th><?php endif; ?>
                                                <th>Milestone Name</th>
                                                <th width="120" class="text-center">Achievement</th>
                                            </tr>
                                        </thead>
                                        <tbody id="project-details">
    
                                            <?php foreach($project_details as $project): ?>
                                                <?php $this->load->view("projects/project/snippets/project-details.php", array('data' => $project, 'projects' => $record)); ?>
                                            <?php endforeach; ?>
    
                                        </tbody>
                                    </table>

                                    <?php if(@$record->milestone_status != 'Posted') { ?>
                                    <a href="javascript:;" class="btn btn-success add-row">Add</a> &nbsp;
                                    <a href="javascript:;" class="btn btn-danger" onclick="delete_row();">Delete</a><br><br>
                                    <?php } ?>
                                </div>
                            </div>
                                
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <button type="submit" class="btn btn-custom waves-effect waves-light form-submit-button">Save & Exit</button> &nbsp;

                                    <?php if($current_role_id == 1 && @$record->milestone_status != 'Posted') { ?>
                                    <button type="button" class="btn btn-dark waves-effect waves-light form-submit-button" onclick="milestone_posted('<?php echo @$record->project_id; ?>');">Milestone Posted</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--project Detail-->
<table class="sample-data hidden">
	<?php $this->load->view("projects/project/snippets/project-details", array("data" => array())); ?>
</table>

<script>
// Handle achievement checkboxes - update hidden input based on checkbox state
$(document).on('change', '.achievement-checkbox', function() {
    var hiddenInput = $(this).siblings('.achievement-value');
    if($(this).is(':checked')) {
        hiddenInput.val('1');
    } else {
        hiddenInput.val('0');
    }
});

// Initialize existing checkboxes on page load
$(document).ready(function() {
    $('.achievement-checkbox').each(function() {
        var hiddenInput = $(this).siblings('.achievement-value');
        if($(this).is(':checked')) {
            hiddenInput.val('1');
        } else {
            hiddenInput.val('0');
        }
    });
});

// Add new row
$(".add-row").on('click', function() {
    let new_row = $('.sample-data .project-details-row').clone()[0];
    
    // Get next row number for sort order
    let count = $('#project-details tr').length + 1;
    
    // Update values for new row
    $(new_row).find('input[name="sort_order[]"]').val(count);
    $(new_row).find('input[name="row_index[]"]').val(''); // Empty for new rows
    $(new_row).find('input[name="project_detail_id[]"]').val('');
    $(new_row).find('input[name="milestone[]"]').val('');
    $(new_row).find('input[name="achievement[]"]').val('0');
    
    // Reset checkboxes
    $(new_row).find('.achievement-checkbox').prop('checked', false);
    $(new_row).find('.project-case').val('');
    
    // Append to table body
    $('#project-details').append(new_row);
});
// $(".add-row").on('click', function() {
//     // Clone hidden sample row
//     let new_row = $('.sample-data .project-details-row').clone()[0];

//     // Get next row number
//     let count = $('#project-details tr').length + 1;

//     // Set the sort_order value
//     $(new_row).find('input[name="sort_order[]"]').val(count);

//     // Optionally set the checkbox value to match the new sort order (if needed)
//     $(new_row).find('.project-case').val('');

//     // Append to table body
//     $('#project-details').append(new_row);
// });

// Milestone Posted
function milestone_posted(id) {
    var o = {};
    o.posted_id = id;

    if (confirm("Do you want to posted it permanently. it cannot be undo?")) {
        $.post(site_url + '/project/milestone_posted/', o, function(res) {
            if (res.RedirectTo) {
                window.location.href = res.RedirectTo;
            }
        }, 'json');
    }
}

//Delete More Row
function delete_row() {
    if (confirm("Do you want to permanently delete?")) {
        var selectedIds = [];

        $('.project-case:checkbox:checked').each(function () {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            alert("No rows selected!");
            return false;
        }

        $.post(site_url + '/project/project_details_delete/', 
            { delete_ids: selectedIds }, 
            function(result) {
                if (result.msg === "SUCCESS") {
                    $('.project-case:checkbox:checked').parents("tr").remove();
                } else {
                    alert(result.details);
                }
            }, 
        "json");
    }
    return false;
}

<?php $property_type_names = array();
$property_types = explode(',', @$record->property_types);
foreach($property_types as $data) {
	$property_type_names[] = $data;
} ?>
$("#property_types").val(["<?php echo implode('","', $property_type_names); ?>"]).trigger("change");
</script>
