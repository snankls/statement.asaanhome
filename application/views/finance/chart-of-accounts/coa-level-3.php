<!-- Start Page content -->
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card-box">
                	<div class="row">
						<div class="col-12 text-right"><button type="button" class="btn btn-info waves-effect" data-toggle="modal" data-target="#addModal" onclick="form_empty();">Create New <?php echo $title; ?></button></div>
					</div><br>
                    
					<div id="list">
						<div class="table_operations_container">
							<div class="data_table_operations text-right">

                                <a href="javascript:;" data-href="<?=site_url() . "api/delete_list"?>" class="btn btn-danger btn-small hidden show_with_selection" id="deleteSelectedBtn" data-toggle="modal" data-target="#deleteModal">
									<input type="hidden" class="data_table_field" name="table" value="chart_of_accounts">
									<input type="hidden" class="data_table_field" name="column" value="chart_of_account_id">
									<i class="fa fa-times"></i> Remove Selected
								</a>

							</div>
						</div>
                        
                        <div class="table-responsive">
                            <table id="dt-table" class="table table-striped table-hover page_data_table">
                                <thead>
                                    <tr class="header_columns">
                                        <th data-data="checkbox" class="checkbox-select" style="min-width: 13px; max-width: 13px;">
                                            <i class="fa fa-square-o"></i>
                                        </th>
                                        <th data-data="counter" style="min-width: 20px; max-width: 20px; text-align:center;">#</th>
                                        <th data-data="sort_order" width="80">Account #</th>
                                        <th data-data="account_title">Account Title</th>
                                        <th data-data="parent_account">Parent Account</th>
                                        <th data-data="project_name">Project Name</th>
                                        <th data-data="account_level">Account Level</th>
                                        <th data-data="account_status">Account Status</th>
                                        <th data-data="create_date">Created By</th>
                                        <th data-data="actions" style="min-width: 100px; max-width: 100px; position: relative;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
			</div>
		</div>
		<!-- end row -->
	</div> <!-- container -->
</div> <!-- content -->

<!--Add Modal-->
<div class="modal fade" id="addModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Create <?php echo $title; ?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form id="add-form" class="form-horizontal Form" role="form" action="<?=site_url('chart_of_accounts/coa_level_3_setup_post')?>">
					<input type="hidden" name="update_id">
					<div class="form-group">
						<label class="col-form-label">Select Project <span class="error-message">*</span></label>
						<select name="project_id" id="project-dropdown" class="form-control project-dropdown required" onchange="CF.Coa1Changed(this);">
                            <option value="">Select One</option>
                            <?php foreach($project_list as $data): ?>
                            <option value="<?php echo $data->project_id; ?>"><?php echo $data->project_name; ?></option>
                            <?php endforeach; ?>
                        </select>
					</div>
					<div class="form-group">
						<label class="col-form-label">COA Level 1 <span class="error-message">*</span></label>
                        <select name="coa_1" id="coa-1" class="form-control coa-1-dropdown required" onchange="CF.Coa2Changed(this);">
                            <option value="">Select One</option>
                            
                        </select>
					</div>
                    <div class="form-group">
                        <label class="col-form-label">COA Level 2 <span class="error-message">*</span></label>
                        <select name="coa_2" id="coa-2" class="form-control coa-2-dropdown required">
                            <option value="">Select One</option>
                            
                        </select>
                    </div>
					<div class="form-group">
						<label class="col-form-label">COA Level 3 Title <span class="error-message">*</span></label>
						<input type="text" name="coa_3" class="form-control required">
					</div>
					<div class="form-group">
						<label class="col-form-label">Account Status <span class="error-message">*</span></label>
						<select name="status" class="form-control required">
                            <?php foreach(enable_disable() as $k => $v){ ?>
                            <option value="<?=$k?>"><?=$v?></option>
                            <?php } ?>
                        </select>
					</div>
                    <div id="form-error-message" class="error-message"></div>
				</form>
			</div>
			<div class="modal-footer text-right">
				<button type="button" class="btn btn-custom waves-effect waves-light" onclick="submitForm('#addModal');">Save</button>
			</div>
		</div>
	</div>
</div>

<!--Edit Modal-->
<div class="modal fade" id="editModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Edit <?php echo $title; ?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form id="add-form" class="form-horizontal Form" role="form" action="<?=site_url('chart_of_accounts/coa_level_3_setup_post')?>">
					<input type="hidden" name="update_id">
					<div class="form-group">
						<label class="col-form-label">Project Name</label>
						<input type="text" name="project_name" class="form-control" readonly>
					</div>
                    <div class="form-group">
                        <label class="col-form-label">Parent Account</label>
                        <input type="text" name="parent_account" class="form-control" readonly>
                    </div>
					<div class="form-group">
						<label class="col-form-label">COA Level 3 Title <span class="error-message">*</span></label>
						<input type="text" name="coa_3" class="form-control required">
					</div>
					<div class="form-group">
						<label class="col-form-label">Account Status <span class="error-message">*</span></label>
						<select name="status" class="form-control required">
                            <?php foreach(enable_disable() as $k => $v){ ?>
                            <option value="<?=$k?>"><?=$v?></option>
                            <?php } ?>
                        </select>
					</div>
                    <div id="form-error-message" class="error-message"></div>
				</form>
			</div>
			<div class="modal-footer text-right">
				<button type="button" class="btn btn-custom waves-effect waves-light" onclick="submitForm('#editModal');">Save</button>
			</div>
		</div>
	</div>
</div>

<script>
function submitForm(modalId) {
    let form = $(modalId).find('form');
    let url = form.attr('action');
    let formData = form.serializeArray();
    let isValid = true;

    // Reset previous error messages
    $(modalId).find('#form-error-message').text('').hide();

    // Client-side validation: Check required fields
    form.find('.required').each(function() {
        if ($(this).val().trim() === '') {
            isValid = false;
        }
    });

    if (!isValid) {
        $(modalId).find('#form-error-message').text('Please fill all required fields.').show();
        return;
    }

    // Get COA Level 1 Order if applicable
    let coa1Order = $('#coa-1 option:selected').data('order');
    if (coa1Order) {
        formData.push({ name: 'level_1_code', value: coa1Order });
    }

    // Get COA Level 2 Order if applicable
    let coa2Order = $('#coa-2 option:selected').data('order');
    if (coa2Order) {
        formData.push({ name: 'level_2_code', value: coa2Order });
    }

    let serializedData = $.param(formData);

    // AJAX request
    $.ajax({
        type: 'POST',
        url: url,
        data: serializedData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $(modalId).modal('hide');
                location.reload();
            } else {
                $(modalId).find('#form-error-message').text(response.message).show();
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}

//Edit Record
function edit_record(obj) {
    let row = $(obj).closest('tr');
    let table_instance = $('#dt-table').DataTable();
    let row_data = table_instance.row(row).data();
    
    // Populate the common fields
    $('#editModal [name="update_id"]').val(row_data.chart_of_account_id);
    $('#editModal [name="project_name"]').val(row_data.project_name);
    $('#editModal [name="parent_account"]').val(row_data.parent_account);
    $('#editModal [name="coa_3"]').val(row_data.account_title);
    $('#editModal [name="status"]').val(row_data.status);

    $('#editModal').modal('show');
}

function form_empty() {
    $('#add-form')[0].reset();
    $('#add-form [name="update_id"]').val('');
}

$(document).ready(function() {
    initializeDataTable(site_url + "chart_of_accounts/chart_of_accounts_level_3_list");
});
</script>
