<!-- Start Page content -->
<input type="hidden" class="current-status" value="<?php //$quotation_status?>">
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">

                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="tab-1" data-toggle="pill" href="#tab-section-1" role="tab" aria-selected="true"><i class="fa fa-home"></i>&nbsp; Details</a></li>
						<?php if($record_list->plan_type == 'Installment') { ?>
						<li class="nav-item"><a class="nav-link" id="tab-2" data-toggle="pill" href="#tab-section-2" role="tab" aria-selected="true"><i class="fa fa-dollar"></i>&nbsp; Installment</a></li>
						<?php } else { ?>
						<li class="nav-item"><a class="nav-link" id="tab-3" data-toggle="pill" href="#tab-section-3" role="tab" aria-selected="true"><i class="fa fa-dollar"></i>&nbsp; Milestone</a></li>
						<?php } ?>
                    </ul>
                    
                    <div class="tab-content" id="pills-tabContent">
                    	
                        <?php if ($current_role_id != 6) { ?>
                        <div class="form-row clearfix">
                            <div class="form-group col-12">
                                <span class="crud_operations text-right">
                                    <div class="view-actions-container"><i class="fa fa-spinner fa-spin"></i></div>
                                    <script type="text/javascript">CF.GetViewActions("", "<?=$record_list->inventory_id?>", "inventory");</script>
                                </span>
                            </div>
                        </div>
                        <?php } ?>
                        
                        <div class="tab-pane fade show active" id="tab-section-1">
							<?php if (!empty($record_list)){ ?>
                        	<div class="form-row clearfix">
								<div class="form-group col-12">
									<table class="table table-hover mb-0">
										<thead>

										</thead>
										<tbody>
											<tr>
												<td width="40%"><strong>Project Name</strong></td>
												<td><?php echo $record_list->project_name; ?></td>
											</tr>
											<tr>
												<td><strong>Property Type</strong></td>
												<td><?php echo property_types($record_list->property_type); ?></td>
											</tr>
											<tr>
												<td><strong>Unit Number</strong></td>
												<td><?php echo $record_list->unit_number; ?></td>
											</tr>
											<tr>
												<td><strong>Plan Type</strong></td>
												<td><?php echo $record_list->plan_type; ?></td>
											</tr>
											<?php if($record_list->plan_type == 'Installment') { ?>
											<tr>
												<td><strong>Payment Plan (in month)</strong></td>
												<td><?php echo $record_list->payment_plan.' Months'; ?></td>
											</tr>
											<?php } ?>
											<tr>
												<td><strong>Unit Size</strong></td>
												<td><?php echo $record_list->unit_size; ?></td>
											</tr>
											<tr>
												<td><strong>Unit Category</strong></td>
												<td><?php echo $record_list->unit_category; ?></td>
											</tr>
											<tr>
												<td><strong>Total Price</strong></td>
												<td><?php echo number_format($record_list->total_price); ?></td>
											</tr>
											<tr>
												<td><strong>Status</strong></td>
												<td><?php echo inventory_status($record_list->status); ?></td>
											</tr>
										</tbody>
									</table>
								</div>
                            </div>
                            
                            <?php } else { ?>
                            	<p>No Record Available.</p>
                            <?php } ?>
                        </div>
                        
						<?php if($record_list->plan_type == 'Installment') { ?>
							<div class="tab-pane" id="tab-section-2">
								<div class="form-row clearfix">
									<div class="form-group col-12">
										<table class="table table-hover mb-0">
											<thead class="thead-dark">
												<tr>
													<th>Sr #</th>
													<th>Date</th>
													<th>Amount</th>
												</tr>
											</thead>
											<tbody>
												<?php $total_amount = 0;
												$i=1;
												foreach($installment_list as $data) :
												$total_amount += $data->amount; ?>
												<tr>
													<td width="50"><?php echo $i; ?></td>
													<td><?php echo get_date_string_sql($data->inventory_date); ?></td>
													<td><?php echo number_format($data->amount); ?></td>
												</tr>
												<?php $i++; endforeach; ?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="2"><strong>Total</strong></td>
													<td><strong><?php echo number_format($total_amount); ?></strong></td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
								
							</div>
						<?php } else { ?>
							<div class="tab-pane" id="tab-section-3">
								<div class="form-row clearfix">
									<div class="form-group col-12">
										<table class="table table-hover mb-0">
											<thead class="thead-dark">
												<tr>
													<th width="50">Sr #</th>
													<th width="40%">Project Milestone</th>
													<th>Amount</th>
												</tr>
											</thead>
											<tbody>
												<?php $total_amount = 0;
												$i=1;
												foreach($milestone_list as $data) :
												$total_amount += $data->amount; ?>
												<tr>
													<td width="50"><?php echo $i; ?></td>
													<td><?php echo $data->milestone_name; ?></td>
													<td><?php echo number_format($data->amount); ?></td>
												</tr>
												<?php $i++; endforeach; ?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="2"><strong>Total</strong></td>
													<td><strong><?php echo number_format($total_amount); ?></strong></td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
								
							</div>
						<?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div> <!-- container -->
</div> <!-- content -->
