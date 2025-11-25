<?php $total_duesurcharge_amount = 0;
foreach ($duesurcharge_list as $data) {
    $total_duesurcharge_amount += $data->amount;
} ?>
<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">

                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="tab-1" data-toggle="pill" href="#tab-section-1" role="tab" aria-selected="true"><i class="fa fa-home"></i>&nbsp; Details</a></li>
                        <li class="nav-item"><a class="nav-link" id="tab-2" data-toggle="pill" href="#tab-section-2" role="tab" aria-selected="true"><i class="fa fa-user"></i>&nbsp; Customer</a></li>
                        <li class="nav-item"><a class="nav-link" id="tab-3" data-toggle="pill" href="#tab-section-3" role="tab" aria-selected="true"><i class="fa fa-bullhorn"></i>&nbsp; Nominee</a></li>
                        <li class="nav-item"><a class="nav-link" id="tab-4" data-toggle="pill" href="#tab-section-4" role="tab" aria-selected="true"><i class="fa fa-users"></i>&nbsp; Agency</a></li>
                        <li class="nav-item"><a class="nav-link" id="tab-5" data-toggle="pill" href="#tab-section-5" role="tab" aria-selected="true"><i class="fa fa-dollar"></i>&nbsp; Installment</a></li>
                        <li class="nav-item"><a class="nav-link" id="tab-6" data-toggle="pill" href="#tab-section-6" role="tab" aria-selected="true"><i class="fa fa-file-pdf"></i>&nbsp; Account Statement</a></li>
                        <li class="nav-item"><a class="nav-link" id="tab-7" data-toggle="pill" href="#tab-section-7" role="tab" aria-selected="true"><i class="fa fa-file"></i>&nbsp; Waive off Due Surcharge</a></li>
                    </ul>
                    
                    <div class="tab-content" id="pills-tabContent">
                    	<?php if ($current_role_id != 6) { ?>
                    	<div class="form-row clearfix">
                            <div class="form-group col-12 text-right">
                                <a href="javascript:;" class="btn btn-pink btn-small default enable-tooltip" title="Due Surcharge" onclick="DueSurchargeAdd();"><i class="fa fa-file"></i> Due Surcharge</a>
                                <a href="javascript:;" class="btn btn-dark btn-small default enable-tooltip" title="Installment" data-toggle="modal" data-target="#installmentAddModal"><i class="fa fa-dollar"></i> Installment</a>
                                <span class="crud_operations" style="display:inline-block;">
                                    <div class="view-actions-container"><i class="fa fa-spinner fa-spin"></i></div>
                                    <script type="text/javascript">CF.GetViewActions("", "<?=$record_list->booking_id?>", "booking");</script>
                                </span>
                            </div>
                        </div>
                        <?php } ?>
						
						<?php if (!empty($record_list)){ ?>
                        <div class="tab-pane fade show active" id="tab-section-1">
                        	<div class="form-row clearfix">
								<div class="form-group col-12">
									<table class="table table-hover mb-0">
										<tbody>
											<tr>
												<td><strong>Project Name</strong></td>
												<td><?php echo $record_list->project_name; ?></td>
											</tr>
											<tr>
												<td><strong>Property Type</strong></td>
												<td><?php echo property_types($record_list->property_type); ?></td>
											</tr>
											<tr>
												<td><strong>Unit</strong></td>
												<td><?php echo $record_list->unit_number; ?></td>
											</tr>
											<tr>
												<td><strong>Registration #</strong></td>
												<td><?php echo $record_list->registration; ?></td>
											</tr>
										</tbody>
									</table>
								</div>
                            </div>
                        </div>
                        
                        <div class="tab-pane" id="tab-section-2">
                        	<div class="form-row clearfix">
								<div class="form-group col-12">
									<table class="table table-hover mb-0">
										<tbody>
											<tr>
												<td><strong>Customer Name</strong></td>
												<td><?php echo $record_list->customer_name; ?></td>
											</tr>
											<tr>
												<td><strong>CNIC</strong></td>
												<td><?php echo $record_list->cnic; ?></td>
											</tr>
											<tr>
												<td><strong>Father/Husband Name</strong></td>
												<td><?php echo $record_list->father_husband_name; ?></td>
											</tr>
											<tr>
												<td><strong>City</strong></td>
												<td><?php echo $record_list->customer_city; ?></td>
											</tr>
											<tr>
												<td><strong>Mobile</strong></td>
												<td><?php echo $record_list->mobile; ?></td>
											</tr>
											<tr>
												<td><strong>Landline</strong></td>
												<td><?php echo $record_list->landline; ?></td>
											</tr>
											<tr>
												<td><strong>Email Address</strong></td>
												<td><?php echo $record_list->email_address; ?></td>
											</tr>
											<tr>
												<td><strong>Mailing Address</strong></td>
												<td><?php echo $record_list->mailing_address; ?></td>
											</tr>
											<tr>
												<td><strong>Permanent Address</strong></td>
												<td><?php echo $record_list->permanent_address; ?></td>
											</tr>
											<tr>
												<td><strong>CNIC Front</strong></td>
												<td><div class="activity-img"><a href="<?php echo get_image_url($record_list->cnic_front, 'bookings'); ?>" class="lightbox-image"><?php echo get_image($record_list->cnic_front, 'bookings'); ?></a></div></td>
											</tr>
											<tr>
												<td><strong>CNIC Back</strong></td>
												<td><div class="activity-img"><a href="<?php echo get_image_url($record_list->cnic_back, 'bookings'); ?>" class="lightbox-image"><?php echo get_image($record_list->cnic_back, 'bookings'); ?></a></div></td>
											</tr>
											<tr>
												<td><strong>Image</strong></td>
												<td><div class="activity-img"><a href="<?php echo get_image_url($record_list->booking_image, 'bookings'); ?>" class="lightbox-image"><?php echo get_image($record_list->booking_image, 'bookings'); ?></a></div></td>
											</tr>
										</tbody>
									</table>
								</div>
                            </div>
                        </div>
                        
                        <div class="tab-pane" id="tab-section-3">
                        	<div class="form-row clearfix">
								<div class="form-group col-12">
									<table class="table table-hover mb-0">
										<tbody>
											<tr>
												<td><strong>Nominee Name</strong></td>
												<td><?php echo $record_list->nominee_name; ?></td>
											</tr>
											<tr>
												<td><strong>⁠Father/Husband Name</strong></td>
												<td><?php echo $record_list->nominee_father_husband_name; ?></td>
											</tr>
											<tr>
												<td><strong>CNIC</strong></td>
												<td><?php echo $record_list->nominee_cnic; ?></td>
											</tr>
											<tr>
												<td><strong>Relation</strong></td>
												<td><?php echo relation($record_list->relation); ?></td>
											</tr>
										</tbody>
									</table>
								</div>
                            </div>
                        </div>
                        
                        <div class="tab-pane" id="tab-section-4">
                        	<div class="form-row clearfix">
								<div class="form-group col-12">
									<table class="table table-hover mb-0">
										<tbody>
											<tr>
												<td><strong>Agency Name</strong></td>
												<td><?php echo $record_list->agency_name; ?></td>
											</tr>

											<tr>
												<td><strong>Agency Commission</strong></td>
												<td><?php echo $record_list->agency_commission; ?></td>
											</tr>
										</tbody>
									</table>
								</div>
                            </div>
                        </div>
                        
                        <div class="tab-pane" id="tab-section-5">
                        	<div class="form-row clearfix">
								<div class="form-group col-12">
                                	<?php if (!empty($challan_list)) { ?>
									<table class="table table-hover mb-0">
										<thead class="thead-dark">
                                            <tr>
                                                <th>Sr #</th>
                                                <th>Serial</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Payment Method</th>
                                                <th>Reference</th>
                                                <th>Proof Image</th>
                                                <?php if ($current_role_id != 6) { ?><th class="text-center">Action</th><?php } ?>
                                            </tr>
                                        </thead>
										<tbody>
                                        	<?php $i=1;
											$total_amount = 0;
											$len = count($challan_list);
											foreach($challan_list as $data) :
												$total_amount += $data['challan_amount']; ?>
											<tr>
                                            	<td><?php echo $i; ?><input type="hidden" name="serial_id" value="<?php echo $data['serial']; ?>" /></td>
												<td><?php echo document_number(array('db_table' => 'booking_amounts', 'document_number' => $data['serial'], 'prefix' => '&nbsp;')); ?></td>
												<td><?php echo get_date_string_sql($data['challan_date']); ?></td>
												<td><?php echo number_format($data['challan_amount']); ?></td>
												<td><?php echo payment_method($data['challan_payment_method']); ?><input type="hidden" name="payment_method" value="<?php echo $data['challan_payment_method']; ?>" /></td>
												<td><?php echo $data['reference']; ?></td>
                                                <td><div class="proof-img"><a href="<?php echo get_image_url($data['proof_image'], 'booking_receipt'); ?>" class="lightbox-image"><?php echo get_image($data['proof_image'], 'booking_receipt'); ?></a></div></td>
												
                                                <?php if ($current_role_id != 6) { ?>
                                                <td align="center">
                                                	<button type="button" class="btn btn-dark btn-small" title="Receipt" onClick="challan_receipt('<?php echo $data['challan_id']; ?>');"><i class="fa fa-file-text-o"></i></button>
                                                    <button type="button" class="btn btn-primary waves-effect waves-light btn-small" data-toggle="modal" onclick="installment_edit_record(this);"><i class="fa fa-edit"></i></button>
                                                    <?php if ($i == $len) { ?>
                                                    <button type="button" class="btn btn-danger btn-small" title="Delete" onClick="challan_receipt_delete('<?php echo $data['serial']; ?>');"><i class="fa fa-times"></i></button>
                                                    <?php } ?>
                                                </td>
                                                <?php } ?>
											</tr>
											<?php $i++;
											endforeach; ?>
										</tbody>
                                        <tfoot>
                                        	<tr>
                                            	<td colspan="3"><strong>Grand Total</strong></td>
                                            	<td><strong><?php echo number_format($total_amount); ?></strong></td>
                                            	<td colspan="4"></td>
                                            </tr>
                                        </tfoot>
									</table>
									<?php } else { ?>
                                        <p>No Record Available.</p>
                                    <?php } ?>
								</div>
                            </div>
                        </div>
                        
                        <div class="tab-pane" id="tab-section-6">
                        	<div class="form-row clearfix">
								<div class="form-group col-12">
                                    <!-- Account Statement Section -->
                                    <table id="installment-table" class="table table-bordered">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Month</th>
                                                <th>Due Date</th>
                                                <th>Due Amount</th>
                                                <th>Paid Date</th>
                                                <th>Paid Amount</th>
                                                <th>Serial</th>
                                                <th>Balance</th>
                                                <th>Payment Mode</th>
                                                <th>Reference</th>
                                                <th>Surcharge</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($statement_rows)): ?>
                                                <?php 
                                                $currentDate = date('Y-m-d');
                                                $total_dueSurcharge = 0;
                                                $total_duesurcharge_amount = 0; 

                                                foreach ($statement_rows as $row): ?>
                                                <tr>
                                                    <td><?= $row['month'] ?></td>
                                                    <td><?= $row['due_date'] ?></td>
                                                    <td>
                                                        <?= is_numeric($row['due_amount']) ? number_format($row['due_amount']) : $row['due_amount'] ?>
                                                    </td>
                                                    <td><?= $row['paid_date'] ?></td>
                                                    <td>
                                                        <?= is_numeric($row['paid_amount']) ? number_format($row['paid_amount']) : $row['paid_amount'] ?>
                                                    </td>
                                                    <td><?= $row['serial'] ?></td>
                                                    <td>
                                                        <?= is_numeric($row['balance']) ? number_format($row['balance']) : $row['balance'] ?>
                                                    </td>
                                                    <td><?= $row['payment_mode'] ?></td>
                                                    <td><?= $row['reference'] ?></td>
                                                    <td>
                                                        <?= is_numeric($row['surcharge']) ? number_format($row['surcharge']) : $row['surcharge'] ?>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="10" class="text-center">No statement data available</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                        <?php 
                                        $total_paid_amount = 0;
                                        $total_dueSurcharge = 0;
                                        $total_duesurcharge_amount = 0;
                                        $currentDate = date('Y-m-d');
                                        // 1) Sum actual surcharges from statement rows
                                        if (!empty($statement_rows)) {
                                            foreach ($statement_rows as $row) {
                                                if (is_numeric($row['surcharge'])) {
                                                    $total_dueSurcharge += (float) $row['surcharge'];
                                                }
                                                if (is_numeric($row['paid_amount'])) {
                                                    $total_paid_amount += (float) $row['paid_amount'];
                                                }
                                            }
                                        }

                                        // 2) Sum waived surcharge from DB list if applicable
                                        if (!empty($duesurcharge_list)) {
                                            foreach ($duesurcharge_list as $data) {
                                                $total_duesurcharge_amount += (float) $data->amount;
                                            }
                                        }
                                        ?>
                                        <tfoot> 
                                            <tr>
                                                <td colspan="2"><strong>Total Amount</strong></td>
                                                <td><strong><?= number_format($total_due) ?></strong></td>
                                                <td>-</td>
                                                <td><strong><?= number_format($total_paid) ?></strong></td>
                                                <td>-</td>
                                                <td><strong><?= number_format($total_balance) ?></strong></td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td><strong><?= number_format($total_dueSurcharge) ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="9"><strong>Total Due Surcharge Waive off</strong></td>
                                                <td><strong><?= number_format($total_duesurcharge_amount) ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="9"><strong>Remaining Due Surcharge</strong></td>
                                                <td><strong><?= number_format($total_dueSurcharge - $total_duesurcharge_amount) ?></strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
								</div>
                            </div>
                        </div>
                        
                        <div class="tab-pane" id="tab-section-7">
                        	<div class="form-row clearfix">
								<div class="form-group col-12">
									<table class="table table-hover mb-0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th width="50">#</th>
                                                <th>Due Date</th>
                                                <th>Amount</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;
                                            foreach($duesurcharge_list as $data) { ?>
                                            <tr id="row-<?php echo $data->due_surcharge_id; ?>">
                                                <td>
                                                    <?php echo $i; ?>
                                                    <input type="hidden" class="due-surcharge-id" value="<?php echo $data->due_surcharge_id; ?>" />
                                                </td>
                                                <td><?php echo $data->surcharge_date; ?></td>
                                                <td class="amount-cell"><?php echo number_format($data->amount); ?></td>
                                                <td align="center">
                                                    <button type="button" class="btn btn-primary waves-effect waves-light btn-small" data-toggle="modal" onclick="duesurcharge_edit_record(this);">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger waves-effect waves-light btn-small" title="Delete" onclick="duesurcharge_delete('<?php echo $data->due_surcharge_id; ?>', this);">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php $i++;
                                            } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2"><strong>Grand Total</strong></td>
                                                <td><strong id="grand-total"><?php echo number_format($total_duesurcharge_amount); ?></strong></td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tfoot>
                                    </table>
								</div>
                            </div>
                        </div>
						<?php } else { ?>
                            <p>No Record Available.</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div> <!-- container -->
</div> <!-- content -->

<!--Installment Modal-->
<div class="modal fade" id="installmentAddModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Add Installment</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form id="installment-form" class="form-horizontal Form" role="form" enctype="multipart/form-data">
					<input type="hidden" name="update_id" value="<?php echo $record_list->booking_id; ?>">
					<input type="hidden" name="inventory_id" value="<?php echo $record_list->inventory_inventory_id; ?>">
					<input type="hidden" name="plan_type" value="<?php echo $record_list->plan_type; ?>">
					<div class="form-group">
						<label class="col-form-label">Date</label>
						<input type="text" class="form-control datepicker required" name="amount_date">
					</div>
                    <div class="form-group">
						<label class="col-form-label">Installment Amount</label>
						<input type="text" class="form-control required" name="amount">
					</div>
					<div class="form-group">
                        <label class="col-form-label">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-control">
                            <option value="">Select One</option>
                            <?php foreach(payment_method() as $k => $v){ ?>
                            <option value="<?=$k?>"><?=$v?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group" id="reference" style="display: none;">
                        <label class="col-form-label">Reference</label>
                        <input type="text" name="reference" class="form-control required">
                    </div>
					<div class="form-group">
						<label class="col-form-label">Image Proof <span class="error-message">*</span></label><br />
                        <input type="file" name="proof_image" class="required">
					</div>
				</form>
			</div>
			<div class="modal-footer text-right">
				<button type="button" class="btn btn-custom waves-effect waves-light" onclick="installmentSubmitForm();">Save</button>
			</div>
		</div>
	</div>
</div>

<!--Challan Edit Modal-->
<div class="modal fade" id="challanModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Update Installment</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form id="challan-form" class="form-horizontal Form" role="form" enctype="multipart/form-data">
					<input type="hidden" name="update_id">
					<div class="form-group">
						<label class="col-form-label">Date <span class="error-message">*</span></label>
						<input type="text" class="form-control datepicker required" name="amount_date">
					</div>
					<div class="form-group">
                        <label class="col-form-label">Payment Method <span class="error-message">*</span></label>
                        <select name="payment_method" id="challan_payment" class="form-control" onchange="challanChange();">
                            <option value="">Select One</option>
                            <?php foreach(payment_method() as $k => $v){ ?>
                            <option value="<?=$k?>"><?=$v?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group" id="challan_reference" style="display: none;">
                        <label class="col-form-label">Reference <span class="error-message">*</span></label>
                        <input type="text" name="reference" class="form-control">
                    </div>
					<div class="form-group proof-imgs">
						<label class="col-form-label">Image Proof <span class="error-message">*</span></label><br />
                        <input type="file" name="proof_image2">
                        <input type="hidden" name="update_proof_image" value="" />
					</div>
				</form>
			</div>
			<div class="modal-footer text-right">
				<button type="button" class="btn btn-custom waves-effect waves-light" onclick="installment_update();">Save</button>
			</div>
		</div>
	</div>
</div>

<!--DueSurcharge Modal-->
<div class="modal fade" id="dueSurchargeModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Add Due Surcharge</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form id="duesurcharge-form" class="form-horizontal Form" role="form">
					<input type="hidden" name="update_id" value="<?php echo $record_list->booking_id; ?>">
					<input type="hidden" name="due_surcharge_id" value="">
                    <div id="total-due-surcharge"><strong>Total Due Surcharge PKR <?php echo number_format($total_dueSurcharge); ?></strong></div>
                    <div id="due-surcharge-waive-off"><strong>Total Due Surcharge Waive off PKR <?php echo number_format($duesurcharge_waive_off->waive_off_amount); ?></strong></div>
					<div class="form-group">
						<label class="col-form-label">Date</label>
						<input type="text" class="form-control datepicker required" name="date">
					</div>
                    <div class="form-group">
						<label class="col-form-label">Amount</label>
						<input type="text" class="form-control required" name="amount">
					</div>
				</form>
			</div>
			<div class="modal-footer text-right">
				<button type="button" class="btn btn-custom waves-effect waves-light" onclick="dueSurchargeSubmitForm();">Save</button>
			</div>
		</div>
	</div>
</div>

<script>
//Customer Receipt
function challan_receipt(challan_id) {
    const url = site_url + "pdf/challan_receipt?challan_id=" + challan_id;
    window.open(url, '_blank');
}

//Edit Record
function installment_edit_record(obj) {
	let row = $(obj).closest('tr');
	let payment_method = row.find('[name="payment_method"]').val();
	
	var dateString = row.find('td:eq(2)').text();
	var parsedDate = new Date(dateString);
	var formattedDate = parsedDate.getFullYear() + '-' +
                    ('0' + (parsedDate.getMonth() + 1)).slice(-2) + '-' +
                    ('0' + parsedDate.getDate()).slice(-2);
	
    if (payment_method == 2 || payment_method == '') {
        $('#challan_reference').hide();
        $('#challan_reference input').prop('disabled', true);
        //$('form [name="proof_image"]').addClass('required');
    }
    else if (payment_method == 3) {
        $('#challan_reference').show();
        $('#challan_reference input').prop('disabled', false);
        $('#challan_reference input').removeClass('required');
        //$('form [name="proof_image"]').removeClass('required');
    } else {
        $('#challan_reference').show();
        $('#challan_reference input').prop('disabled', false);
        //$('form [name="proof_image"]').addClass('required');
    }
		
	$('#challanModal [name="update_id"]').val(row.find('[name="serial_id"]').val());
	$('#challanModal [name="amount_date"]').val(formattedDate);
	$('#challanModal [name="payment_method"]').val(payment_method);
	$('#challanModal [name="reference"]').val(row.find('td:eq(5)').text());
	$('#challanModal [name="update_proof_image"]').val(row.find('.proof-img img').attr('src').split('/').pop());
	$('#challanModal .proof-imgs .proof-img').empty();
	$('#challanModal .proof-imgs').append("<div class='proof-img'><br><img src='" + row.find('.proof-img img').attr('src') + "'></div>");
	$('#challanModal').modal('show');
}

function installment_update() {
    var form = '#challan-form';
    $(form).validate({
        onsubmit: false
    });

    if (!$(form).valid()) {
        return false;
    }
	
    var formData = new FormData($(form)[0]);

    $.ajax({
        url: site_url + 'booking/challan_update_setup_post',
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,  // Important for file upload
        processData: false,  // Important for file upload
        success: function(result) {
            if (result.msg == "SUCCESS") {
                $('#challanModal').modal('hide');
                location.reload();
                $(form)[0].reset();
            } else {
                alert(result.data);
            }
        },
    });
}

//Customer Receipt Delete
function challan_receipt_delete(id) {
	if (confirm("do you want to delete permanent?")) {
		var o = new Object;
		o.delete_id = id;
		$.post( site_url + 'booking/challan_receipt_delete/', o, function(result)
		{
			if( result.msg == "SUCCESS" ){
				location.reload();
			}
		},"json");
    }
	return false;
}

function challanChange() {
    var payment_method = $('#challan_payment option:selected').val();

    if (payment_method == 2 || payment_method == '') {
        $('#challan_reference').hide();
		$('#challan_reference input').prop('disabled', true);
	} else {
		$('#challan_reference').show();
		$('#challan_reference input').prop('disabled', false);
	}
}

//Installment
function dueSurchargeSubmitForm() {
    var form = '#duesurcharge-form';
    $(form).validate({
        onsubmit: false
    });

    if (!$(form).valid()) {
        return false;
    }
    
    var total_due_surcharge = parseFloat($('#total-due-surcharge').text().replace('Total Due Surcharge PKR ', '').replace(/,/g, ''));
	var due_surcharge_waive_off = parseFloat($('#due-surcharge-waive-off').text().replace('Total Due Surcharge Waive off PKR ', '').replace(/,/g, ''));
	var amount = parseFloat($('#duesurcharge-form input[name="amount"]').val()); // Convert amount to float
	
	var total_due_surcharge_with_waive_off = due_surcharge_waive_off + amount;
	
    if (total_due_surcharge_with_waive_off > total_due_surcharge) {
        alert('The entered amount cannot exceed the Total Due Surcharge.');
        return false;
    }

    var formData = new FormData($(form)[0]);

    $.ajax({
        url: site_url + 'booking/booking_duesurcharge_setup',
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            if (result.message == "SUCCESS") {
                $('#dueSurchargeModal').modal('hide');
                $(form)[0].reset();
				location.reload();
            } else {
                alert(result.data);
            }
        },
    });
}

//Add Due Surcharge
function DueSurchargeAdd() {
	$('#duesurcharge-form')[0].reset();
	$('#dueSurchargeModal').modal('show');
}

//Edit Record
function duesurcharge_edit_record(obj) {
	let row = $(obj).closest('tr');
	var ds_id = row.find('td:eq(0) .due-surcharge-id').val();
	var date = row.find('td:eq(1)').text();
	var amount = row.find('td:eq(2)').text();
	
	$('#dueSurchargeModal [name="due_surcharge_id"]').val(ds_id);
	$('#dueSurchargeModal [name="date"]').val(date);
	$('#dueSurchargeModal [name="amount"]').val(amount);
	$('#dueSurchargeModal').modal('show');
}

// Due Surcharge Delete
function duesurcharge_delete(id, button) {
    if (confirm("Do you want to delete this permanently?")) {
        var o = { delete_id: id };
        
        $.post(site_url + 'booking/duesurcharge_delete/', o, function(result) {
            if (result.msg === "SUCCESS") {
                // Hide the row associated with the delete button
                $(button).closest('tr').fadeOut('slow', function() {
                    $(this).remove(); // Remove the row from the DOM
                    updateGrandTotal(); // Update the grand total
                });
            }
        }, "json");
    }
    return false;
}

// Function to recalculate and update the grand total
function updateGrandTotal() {
    let total = 0;
    
    // Sum up the remaining amounts
    $('.amount-cell').each(function() {
        total += parseFloat($(this).text()) || 0;
    });
    
    // Update the grand total display
    $('#grand-total').text(total.toLocaleString());
}
</script>
