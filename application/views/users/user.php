<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                	<div class="row">
                        <div class="col-12 text-right"><a href="<?php echo site_url('user/add'); ?>" class="btn btn-info waves-effect">Create New <?php echo $title; ?></a></div>
                    </div><br>

					<div id="list">
						<div class="table_operations_container">
							<div class="data_table_operations text-right">

								<a class="btn btn-danger DeleteDataTableRows hidden btn-small show_with_selection send_table_data"
								   href="#"
								   data-href="<?=site_url() . "api/list_delete"?>">
									<input type="hidden" class="data_table_field" name="table" value="users">
									<input type="hidden" class="data_table_field" name="column" value="user_id">
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
                                        <th class="no-sort no-search" style="min-width: 30px; max-width: 30px;" data-data="counter">#</th>
                                        <th class="no-sort no-search" width="50" data-data="user_image">Image</th>
                                        <th class="fixed_column" data-data="fullname">Full Name</th>
                                        <th class="hide_able" data-data="mobile">Phone Number</th>
                                        <th class="hide_able" data-data="email">Email Address</th>
                                        <th class="hide_able" data-data="role_module">Role Module</th>
                                        <th class="hide_able" data-data="role">Role</th>
                                        <th class="hide_able no-search" data-data="user_status">Status</th>
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

<!--Verify Modal-->
<div class="modal fade" id="verifyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        	<div class="modal-header">
                <h4 class="modal-title">Email Sent Verification</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
            	Eamil sent successfully.
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(window).load(function() {
	window.history.replaceState({}, document.title, site_url + "user/" + "");
});

$(document).ready(function(e) {
	//List Record
	load_data_table.init_obj({
		DataUrl: site_url + "user/user_list",
		order_by: [[ 2, 'desc' ]],
	});
});
</script>
