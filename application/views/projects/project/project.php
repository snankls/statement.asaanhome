<!-- Start Page content -->
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card-box">
                	<?php if ( $is_admin == "yes" ){ ?>
					<div class="row">
						<div class="col-12 text-right"><a href="<?php echo site_url('project/add'); ?>" class="btn btn-info waves-effect">Create New <?php echo $title; ?></a></div>
					</div><br>
                    <?php } ?>
                    
					<div id="list">
						<div class="table_operations_container">
							<div class="data_table_operations text-right">
								<?php if($is_admin == 'yes') { ?>
									<a href="javascript:;" data-href="<?=site_url() . "api/delete_list"?>" class="btn btn-danger btn-small hidden show_with_selection" id="deleteSelectedBtn" data-toggle="modal" data-target="#deleteModal">
										<input type="hidden" class="data_table_field" name="table" value="projects">
										<input type="hidden" class="data_table_field" name="column" value="project_id">
										<i class="fa fa-times"></i> Remove Selected
									</a>
                                <?php } ?>
							</div>
						</div>
                        
                        <div class="table-responsive">
                            <table id="dt-table" class="table table-striped table-hover page_data_table">
                                <thead>
                                    <tr class="header_columns">
                                        <?php if ( $is_admin == "yes" ){ ?>
										<th class="checkbox-select" style="min-width: 13px; max-width: 13px;" data-data="checkbox">
                                            <i class="fa fa-square-o"></i>
                                        </th>
                                        <?php } ?>
                                        <th data-data="counter" style="min-width: 20px; max-width: 20px; text-align:center;">#</th>
                                        <th data-data="project_image" width="50">Image</th>
                                        <th data-data="project_name">Project Name</th>
                                        <th data-data="area_unit">Area Unit</th>
                                        <th data-data="project_city">City</th>
                                        <th data-data="create_date">Created By</th>
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
    initializeDataTable(site_url + "project/project_list");
});
</script>
