<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">

                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="tab-1" data-toggle="pill" href="#tab-section-1" role="tab" aria-selected="true"><i class="fa fa-home"></i>&nbsp; Details</a></li>
                    </ul>
                    
                    <div class="tab-content" id="pills-tabContent">
                    	<!--Vendor Details-->
                        <div class="tab-pane fade show active" id="tab-section-1">
							<?php if (!empty($record_list)){ ?>
                        	<div class="form-row clearfix">
								
                                <?php if ($current_role_id != 6) { ?>
                                <div class="form-group col-12">
									<span class="crud_operations pull-right">
										<div class="view-actions-container"><i class="fa fa-spinner fa-spin"></i></div>
										<script type="text/javascript">CF.GetViewActions("", "<?=$record_list->project_id?>", "project");</script>
									</span><br><br>
								</div>
                                <?php } ?>
                                
								<div class="form-group col-12">
									<table class="table table-hover mb-0">
										<tbody>
											<tr>
												<td><strong>Reference Number</strong></td>
												<td><?php echo $record_list->project_name; ?></td>
											</tr>
											<tr>
												<td><strong>Property Types</strong></td>
												<td>
													<?php $property_type_names = array();
													$property_types = explode(',', $record_list->property_types);
													foreach($property_types as $data) {
														$property_type_names[] = property_types($data);
													}
													echo implode(', ', $property_type_names); ?>
                                                </td>
											</tr>
											<tr>
												<td><strong>Area Unit</strong></td>
												<td><?php echo area_unit($record_list->area_unit); ?></td>
											</tr>
											<tr>
												<td><strong>City</strong></td>
												<td><?php echo $record_list->project_city; ?></td>
											</tr>
											<tr>
												<td><strong>Description</strong></td>
												<td><?php echo $record_list->description; ?></td>
											</tr>
											<tr>
                                            	<td><strong>Image</strong></td>
												<td><div class="activity-img"><a href="<?php echo get_image_url($record_list->image, 'projects'); ?>" class="lightbox-image"><?php echo get_image($record_list->image, 'projects'); ?></a></div></td>
											</tr>
										</tbody>
									</table>
								</div>
                            </div>
                            
                            <?php } else { ?>
                            	<p>No Record Available.</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div> <!-- container -->
</div> <!-- content -->
