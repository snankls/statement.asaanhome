<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">

                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="tab-1" data-toggle="pill" href="#tab-section-1" role="tab" aria-selected="true"><i class="fa fa-home"></i>&nbsp; Personal Information</a></li>
                        <li class="nav-item"><a class="nav-link" id="tab-2" data-toggle="pill" href="#tab-section-2" role="tab" aria-selected="true"><i class="fa fa-home"></i>&nbsp; Other Information</a></li>
                    </ul>
                    
                    <div class="tab-content" id="pills-tabContent">
						<?php if ($current_role_id != 6) { ?>
                        <div class="form-group col-12">
                            <span class="crud_operations text-right">
                                <div class="view-actions-container"><i class="fa fa-spinner fa-spin"></i></div>
                                <script type="text/javascript">CF.GetViewActions("", "<?=$record_list->lead_id?>", "leads");</script>
                            </span><br><br>
                        </div>
                        <?php } ?>
                        
                    	<!--Vendor Details-->
                        <div class="tab-pane fade show active" id="tab-section-1">
							<?php if (!empty($record_list)){ ?>
                        	<div class="form-row clearfix">
                                
								<div class="form-group col-12">
									<table class="table table-hover mb-0">
										<tbody>
											<tr>
												<td><strong>Name</strong></td>
												<td><?php echo $record_list->name; ?></td>
											</tr>
											<tr>
												<td><strong>Phone Number</strong></td>
												<td><?php echo $record_list->phone_number; ?></td>
											</tr>
											<tr>
												<td><strong>Email Address</strong></td>
												<td><?php echo $record_list->email_address; ?></td>
											</tr>
											<tr>
												<td><strong>City</strong></td>
												<td><?php echo $record_list->city; ?></td>
											</tr>
										</tbody>
									</table>
								</div>
                            </div>
                            
                            <?php } else { ?>
                            	<p>No Record Available.</p>
                            <?php } ?>
                        </div>
                        
                        <div class="tab-pane" id="tab-section-2">
                        	<div class="form-row clearfix">
								<div class="form-group col-12">
									<table class="table table-hover mb-0">
										<thead>

										</thead>
										<tbody>
											<tr>
												<td><strong>Allocation</strong></td>
												<td><?php echo $record_list->fullname; ?></td>
											</tr>
											<tr>
												<td><strong>Project Name</strong></td>
												<td><?php echo $record_list->project_name; ?></td>
											</tr>
											<tr>
												<td><strong>Status</strong></td>
												<td><?php echo lead_status($record_list->lead_status); ?></td>
											</tr>
											<tr>
												<td><strong>Task Performed</strong></td>
												<td><?php echo task_performed($record_list->task_performed); ?></td>
											</tr>
											<tr>
												<td><strong>Next Followup Date</strong></td>
												<td><?php echo $record_list->followup_date; ?></td>
											</tr>
											<tr>
												<td><strong>Next Task</strong></td>
												<td><?php echo next_task($record_list->next_task); ?></td>
											</tr>
											<tr>
												<td><strong>Remarks</strong></td>
												<td><?php echo $record_list->remarks; ?></td>
											</tr>
										</tbody>
									</table>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div> <!-- container -->
</div> <!-- content -->
