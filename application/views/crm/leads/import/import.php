<!-- Start Page content -->
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
            	<div class="card-box">
                	<div class="row">
                        <div class="col-6">
                            <form method="post" id="import_csv" enctype="multipart/form-data">
                                <label>Select CSV File</label>
                                <input type="file" name="csv_file" id="csv_file" required accept=".csv" />
                                <button type="submit" name="import_csv" class="btn btn-custom waves-effect waves-light" id="import_csv_btn">Import CSV</button>&nbsp;&nbsp;
                                <a href="<?php echo site_url('csvformat/lead-import-data.csv'); ?>" class="btn btn-custom waves-light waves-effect" download><i class="fa fa-download"></i> Download CSV Format</a>
                            </form>
                            <br />
                            <div id="imported_csv_data"></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                
				<div class="card-box">
                	<div id="list">
						<div class="table_operations_container">
							<div class="data_table_operations text-right">

								<a href="#" class="btn btn-danger DeleteDataTableRows hidden btn-small show_with_selection send_table_data" data-href="<?=site_url() . "api/list_delete"?>">
									<input type="hidden" class="data_table_field" name="table" value="temp_leads">
									<input type="hidden" class="data_table_field" name="column" value="lead_id">
									<i class="fa fa-times"></i> Remove Selected
								</a>
                                
                                <a class="btn btn-dark waves-light waves-effect btn-small" href="javascript:;" onclick="shift_query();"><i class="fa fa-download"></i> Run Query</a>

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
                                        <?php if ( $is_admin == "yes" ){ ?>
                                        <th class="select-checkbox no-sort no-search" style="min-width: 13px; max-width: 13px;" data-data="checkbox">
                                            <i class="fa fa-square-o"></i>
                                        </th>
                                        <?php } ?>
                                        <th class="no-sort no-search" style="min-width: 50px; max-width: 50px; text-align:center;" data-data="action">Action</th>
                                        <th class="no-sort no-search" width="50" data-data="create_date">Cerate Date</th>
                                        <th class="hide_able no-search" data-data="main_lead_id">ID</th>
                                        <th class="hide_able" data-data="name">Full Name</th>
                                        <th class="hide_able" data-data="project_name">Project Name</th>
                                        <th class="hide_able no-search" data-data="fullname">Allocation Name</th>
                                        <th class="hide_able" data-data="lead_source">Lead Source</th>
                                        <th class="hide_able" data-data="lead_status">Lead Status</th>
                                        <th class="hide_able" data-data="last_followup_date">Last Followup Date</th>
                                        <th class="hide_able" data-data="task_performed">Task Performed</th>
                                        <th class="hide_able" data-data="next_followup_date">Next Followup Date</th>
                                        <th class="hide_able" data-data="next_task">Next Task</th>
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

<!--Followup List-->
<div class="modal fade" id="followupModal" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
            <div id="followup-list"></div>
		</div>
	</div>
</div>

<!--Edit Leads-->
<div class="modal fade" id="editLeadModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Edit Leads</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form id="update-form" class="form-horizontal Form" role="form">
                	<input type="hidden" name="update_id" value="" />
                    <div class="form-group">
                        <label class="col-form-label">Name <span class="error-message">*</span></label>
                        <input type="text" name="name" class="form-control" value="" />
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Country <span class="error-message">*</span></label>
                        <select name="country" class="form-control select2 required">
                            <option value="">Select One</option>
                            <?php foreach(country_list() as $k => $v){ ?>
                            <option value="<?=$k?>"><?=$v?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Phone Number <span class="error-message">*</span></label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control phone-masking" placeholder="3001234567" maxlength="10" />
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Email Address <span class="error-message">*</span></label>
                        <input type="email" name="email_address" id="email_address" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">City <span class="error-message">*</span></label>
                        <input type="text" name="city" id="city" class="form-control" />
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
</script>

<?php $this->load->view("crm/leads/import/import-leads-details", array("data" => array())); ?>

<script type="text/javascript">
//Import CSV
$(document).ready(function(){
	//CSV Import Script
	$('#import_csv').on('submit', function(event){
		event.preventDefault();
		$.ajax({
			url:site_url + "csv_import/import",
			method:"POST",
			data:new FormData(this),
			contentType:false,
			cache:false,
			processData:false,
			beforeSend:function(){
				$('#import_csv_btn').html('Importing...');
			},
			success:function(data)
			{
				$('#import_csv')[0].reset();
				$('#import_csv_btn').attr('disabled', false);
				$('#import_csv_btn').html('Import Done');
				load_data_table.reload();
			}
		});
	});
});

//Shift Record
function shift_query(){
	if (confirm("Are you sure you want to shift selected records? This cannot be undone.")) {
		var o = new Object;
		
		$.post( site_url + 'import/leads_query_shift/', o, function(result)
		{
			if( result.msg == "SUCCESS" ){
				setInterval(function () {
					location.reload();
				}, 500);
			} else {
				alert(result.data);
			}
		},"json");
    }
	return false;
}

$(window).load(function() {
	window.history.replaceState({}, document.title, site_url + "leads/import" + "");
});

$(document).ready(function(e) {
	//List Record
	load_data_table.init_obj({
		DataUrl: site_url + "import/leads_import_list",
		order_by: [[ 3, 'desc' ]],
	});
});
</script>
