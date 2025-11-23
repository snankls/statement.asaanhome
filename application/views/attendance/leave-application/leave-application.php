<!-- Start Page content -->
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card-box">
					<div class="row">
					<div class="col-12 text-right"><a href="<?php echo site_url('leave-application/add'); ?>" class="btn btn-info waves-effect">Create New <?php echo $title; ?></a></div>
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
                                        <th data-data="leave_type" width="80">Leave Type</th>
                                        <th data-data="date_range">Date</th>
                                        <th data-data="reason">Reason</th>
                                        <th data-data="status">Status</th>
                                        <th data-data="create_date">Created By</th>
                                        <!-- <th data-data="actions" style="min-width: 100px; max-width: 100px; position: relative;">Actions</th> -->
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

<script>
$(document).ready(function() {
    initializeDataTable(site_url + "attendance/leave_application_list");
});
</script>
