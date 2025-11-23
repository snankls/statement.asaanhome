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

								<a href="#" class="btn btn-danger DeleteDataTableRows hidden btn-small show_with_selection send_table_data" data-href="<?=site_url() . "api/list_delete"?>">
									<input type="hidden" class="data_table_field" name="table" value="teams">
									<input type="hidden" class="data_table_field" name="column" value="team_id">
									<i class="fa fa-times"></i> Remove Selected
								</a>

								<div class="show_hide_table_columns btn btn-success btn-small">
									<i class=""
									   style="font-style: normal;"
									   data-toggle="dropdown"
									   aria-haspopup="true"
									   aria-expanded="false"
									   title="Show/Hide Columns">Show/Hide Columns</i>
									<div class="dropdown-menu"></div>
								</div>

								<a class="btn btn-info table_field_filters btn-small hidden"
								   href="#">
									<i class="fa fa-filter"></i> Filters
								</a>

							</div>
						</div>
                        
                        <div class="table-responsive">
                            <table id="quotation-data-list" class="table table-striped table-hover page_data_table">
                                <thead>
                                    <tr class="header_columns">
                                        <th class="select-checkbox no-sort no-search" style="min-width: 13px; max-width: 13px;" data-data="checkbox">
                                            <i class="fa fa-square-o"></i>
                                        </th>
                                        <th class="no-sort no-search" style="min-width: 20px; max-width: 20px; text-align:center;" data-data="counter">#</th>
                                        <th class="no-sort" data-data="team_name">Team Name</th>
                                        <th class="no-sort" data-data="team_manager">Team Managers</th>
                                        <th class="hide_able" data-data="team_individual">Team Members</th>
                                        <th class="hide_able no-search" data-data="team_status">Status</th>
                                        <th class="fixed_column no-search" data-data="created_date">Created By</th>
                                        <th class="no-sort no-search text-center" data-data="actions" style="min-width: 100px; max-width: 100px; position: relative;">Actions</th>
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
				<form id="add-form" class="form-horizontal Form" role="form" action="<?=site_url('teams/teams_setup_post')?>">
					<input type="hidden" name="update_id">
					<div class="form-group">
						<label class="col-form-label">Team Name <span class="error-message">*</span></label>
						<input type="text" name="team_name" class="form-control required">
					</div>
					<div class="form-group">
						<label class="col-form-label">Status <span class="error-message">*</span></label>
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
				<button type="button" class="btn btn-custom waves-effect waves-light" onclick="submitForm();">Save</button>
			</div>
		</div>
	</div>
</div>

<script>
function submitForm() {
    let form = $('#add-form');
    let url = form.attr('action');
    let formData = form.serialize();

    $.ajax({
        type: 'POST',
        url: url,
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // If successful, hide the modal and optionally reload the table
                $('#addModal').modal('hide');
                if (response.reload_table) {
                    location.reload();
                }
            } else {
                // Show error message and keep the modal open
                $('#form-error-message').text(response.message).show();
            }
        },
        error: function(xhr, status, error) {
            // Handle any errors here
            console.log(error);
        }
    });
}

//Edit Record
function edit_record(obj) {
	let row = $(obj).closest('tr');
	let table_instance = DTO.GetInstance(2);
	let row_data = table_instance.rows(row.index()).data()[0];
	console.log(row_data);
	$('#addModal [name="update_id"]').val(row_data.team_id);
	$('#addModal [name="team_name"]').val(row_data.team_name);
	$('#addModal [name="status"]').val(row_data.status);
	$('#addModal').modal('show');
}

function form_empty() {
	$('#add-form')[0].reset();
	$('#add-form [name="update_id"]').val('');
}

/*$(window).load(function() {
	window.history.replaceState({}, document.title, site_url + "teams/teams" + "");
});*/

$(document).ready(function(e) {
	//List Record
	load_data_table.init_obj({
		DataUrl: site_url + "teams/teams_list",
		order_by: [[ 2, 'desc' ]],
	});
});
</script>
