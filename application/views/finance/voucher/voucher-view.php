<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">

                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="tab-1" data-toggle="pill" href="#tab-section-1" role="tab" aria-selected="true"><i class="fa fa-home"></i>&nbsp; Voucher</a></li>
                        <li class="nav-item"><a class="nav-link" id="tab-2" data-toggle="pill" href="#tab-section-2" role="tab" aria-selected="true"><i class="fa fa-home"></i>&nbsp; Voucher Details</a></li>
                    </ul>
                    
                    <div class="tab-content" id="pills-tabContent">
                        <div class="form-row clearfix">
                            <div class="form-group col-12">
                                <span class="crud_operations text-right">
                                    <div class="view-actions-container"><i class="fa fa-spinner fa-spin"></i></div>
                                    <script type="text/javascript">CF.GetViewActions("", "<?=$record_list->voucher_id?>", "voucher");</script>
                                </span>
                            </div>
                        </div>
                        
                    	<!--Vendor Details-->
                        <div class="tab-pane fade show active" id="tab-section-1">
							<?php if (!empty($record_list)){ ?>
                        	<div class="form-row clearfix">
								<div class="form-group col-12">
									<table class="table table-hover mb-0">
										<tbody>
											<tr>
												<td><strong>Voucher ID</strong></td>
												<td><?php echo $record_list->voucher_id; ?></td>
											</tr>
											<tr>
												<td><strong>Project Name</strong></td>
												<td><?php echo $record_list->project_name; ?></td>
											</tr>
											<tr>
												<td><strong>Transaction Type</strong></td>
												<td><?php echo transaction_type($record_list->transaction_type); ?></td>
											</tr>
											<tr>
												<td><strong>Voucher Date</strong></td>
												<td><?php echo $record_list->voucher_date; ?></td>
											</tr>
                                            <tr>
                                            	<td><strong>Images</strong></td>
												<td>
													<?php foreach($voucher_images as $img) : ?>
													<div class="activity-img">
														<a href="<?php echo get_image_url($img->image_name, 'vouchers'); ?>" class="lightbox-image"><?php echo get_image($img->image_name, 'vouchers'); ?></a>
													</div>
													<?php endforeach; ?>
												</td>
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
									<table class="table table-hover mb-0 finance-ledger-list">
										<thead class="thead-dark">
                                            <tr>
                                                <th width="150">Account #</th>
                                                <th>Narration</th>
                                                <th width="120">Debit</th>
                                                <th width="120">Credit</th>
                                                <th width="120">Voucher Type</th>
                                            </tr>
                                        </thead>
										<tbody>
                                        	<?php $i=1;
											$total_debit = 0;
											$total_credit = 0;
											foreach($voucher_details as $data) :
												$total_debit += $data->debit;
												$total_credit += $data->credit; ?>
											<tr>
												<td><?php echo '<strong>'.$data->sort_order.'</strong><br>'.$data->account_title; ?></td>
												<td><?php echo $data->narration; ?></td>
												<td><?php echo number_format($data->debit); ?></td>
												<td><?php echo number_format($data->credit); ?></td>
												<td><?php echo voucher_book($data->book); ?></td>
											</tr>
											<?php $i++;
											endforeach; ?>
										</tbody>
                                        <tfoot>
                                        	<tr>
                                            	<td colspan="2"><strong>Grand Total</strong></td>
                                            	<td><strong><?php echo number_format($total_debit); ?></strong></td>
                                            	<td><strong><?php echo number_format($total_credit); ?></strong></td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        	<tr>
                                                <td colspan="5"><strong>PKR - <?php echo numberToWords($total_debit); ?></strong></td>
                                            </tr>
                                        </tfoot>
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

<script>
function voucher_download(voucher_id) {
	const url = site_url + "pdf/voucher_download?voucher_id=" + voucher_id;
    window.open(url, '_blank');
}
</script>