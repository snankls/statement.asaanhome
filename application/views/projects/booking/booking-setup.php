<?php $last = $this->uri->total_segments();
$slug_url = $this->uri->segment($last); ?>

<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                	<div id="purchase-list">
                    	<form id="update-form" class="form-horizontal disabled-field Form" enctype="multipart/form-data" role="form" action="<?php echo site_url('booking/booking_setup_post/'.@$record->booking_id); ?>">
                        	<input type="hidden" name="last_uri" value="booking" />
                        	<input type="hidden" name="inventory_id" id="inventory_id" value="<?php echo @$record->booking_id; ?>" />
                        	<div class="form-row">
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Select Project <span class="error-message">*</span></label>
                                    <select class="form-control project-dropdown required" name="project_id" data-property="<?php echo @$record->property_type; ?>" onchange="CF.BookingPropertyTypesChanged(this);">
                                        <option value="">Select One</option>
                                        <?php foreach($project_list as $data): ?>
                                        <option value="<?php echo $data->project_id; ?>" data-inventory="<?php echo $data->inventory_id; ?>" <?php if(@$record->booking_project_id == $data->project_id) echo 'selected="selected"'; ?>>
                                            <?php echo $data->project_name; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            	<div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Property Type <span class="error-message">*</span></label>
                                    <select class="form-control property-type-dropdown required" name="property_type" data-booking="<?php echo @$record->booking_id; ?>" data-inventory="<?php echo @$record->inventory_inventory_id; ?>" data-selected="<?php echo @$record->property_type; ?>" onchange="CF.BookingUnitChanged(this);">
                            			<option value="">Select One</option>
										
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Select Unit # <span class="error-message">*</span></label>
                                    <select class="form-control unit-dropdown required" name="unit_number" data-selected="<?php echo @$record->booking_unit; ?>" onchange="generate_inventory_id();">
                            			<option value="">Select One</option>
                                        
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Registration # <span class="error-message">*</span></label>
                                    <input type="text" name="registration" class="form-control required" value="<?php echo @$record->registration; ?>">
                                </div>
                                
                                <div class="col-12"><h4 class="border-bottom"><span class="fa fa-cog"></span> Customer Information</h4></div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Full Name <span class="error-message">*</span></label>
                                    <input type="text" name="customer_name" class="form-control required" value="<?php echo @$record->customer_name; ?>">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">CNIC <span class="error-message">*</span></label>
                                    <input type="text" name="cnic" class="form-control masking required" placeholder="_____-______-_" maxlength="15" value="<?php echo @$record->cnic; ?>">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">⁠Father/Husband Name: <span class="error-message">*</span></label>
                                    <input type="text" name="father_husband_name" class="form-control required" value="<?php echo @$record->father_husband_name; ?>">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">⁠City <span class="error-message">*</span></label>
                                    <input type="text" name="customer_city" class="form-control required" value="<?php echo @$record->customer_city; ?>">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">⁠Mobile <span class="error-message">*</span></label>
                                    <input type="text" name="mobile" class="form-control required" value="<?php echo @$record->mobile; ?>">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">⁠⁠Landline</label>
                                    <input type="text" name="landline" class="form-control" value="<?php echo @$record->landline; ?>">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">⁠⁠Email Address (Optional)</label>
                                    <input type="text" name="email_address" class="form-control email" value="<?php echo @$record->email_address; ?>">
                                </div>
                                <div class="form-group col-lg-6 col-xs-12">
                                	<label class="col-form-label">⁠⁠⁠Mailing Address <span class="error-message">*</span></label>
                                    <textarea name="mailing_address" class="form-control required"><?php echo @$record->mailing_address; ?></textarea>
                                </div>
                                <div class="form-group col-lg-6 col-xs-12">
                                	<label class="col-form-label">⁠⁠⁠Permanent Address</label>
                                    <textarea name="permanent_address" class="form-control"><?php echo @$record->permanent_address; ?></textarea>
                                </div>
                                <div class="form-group col-lg-4 col-xs-12">
                                	<label class="col-form-label">⁠⁠⁠Upload CNIC Front <span class="error-message">*</span></label><br>
                                    <input type="file" name="cnic_front" <?php if ($slug_url == 'add') echo 'class="required"'; ?>>
                                    
                                    <?php if(!empty(@$record->cnic_front)) { ?>
                                    <input type="hidden" name="update_cnic_front" class="required" value="<?php echo @$record->cnic_front; ?>">
                                    <?php } ?>
                                    
                                    <?php if ($slug_url != 'add') {
									if(!empty(@$record->cnic_front)) { ?>
                                    <div class="activity-img"><a href="<?php echo site_url('uploads/bookings/'.@$record->cnic_front); ?>" class="lightbox-image"><img src="<?php echo site_url('uploads/bookings/'.@$record->cnic_front); ?>" alt="Image"></a></div>
                                    <?php }
									} ?>
                                </div>
                                <div class="form-group col-lg-4 col-xs-12">
                                	<label class="col-form-label">⁠⁠⁠Upload CNIC Back <span class="error-message">*</span></label><br />
                                    <input type="file" name="cnic_back" <?php if ($slug_url == 'add') echo 'class="required"'; ?>>
                                    
                                    <?php if(!empty(@$record->cnic_back)) { ?>
                                    <input type="hidden" name="update_cnic_back" class="required" value="<?php echo @$record->cnic_back; ?>">
                                    <?php } ?>
                                    
                                    <?php if ($slug_url != 'add') {
									if(!empty(@$record->cnic_back)) { ?>
                                    <div class="activity-img"><a href="<?php echo site_url('uploads/bookings/'.@$record->cnic_back); ?>" class="lightbox-image"><img src="<?php echo site_url('uploads/bookings/'.@$record->cnic_back); ?>" alt="Image"></a></div>
                                    <?php }
									} ?>
                                </div>
                                <div class="form-group col-lg-4 col-xs-12">
                                	<label class="col-form-label">⁠⁠⁠Upload Image <span class="error-message">*</span></label><br />
                                    <input type="file" name="image">
                                    
                                    <?php if(!empty(@$record->booking_image)) { ?>
                                    <input type="hidden" name="update_image" value="<?php echo @$record->booking_image; ?>">
                                    <?php } ?>
                                    
                                    <?php if ($slug_url != 'add') {
									if(!empty(@$record->booking_image)) { ?>
                                    <div class="activity-img"><a href="<?php echo site_url('uploads/bookings/'.@$record->booking_image); ?>" class="lightbox-image"><img src="<?php echo site_url('uploads/bookings/'.@$record->booking_image); ?>" alt="Image"></a></div>
                                    <?php }
									} ?>
                                </div>
                                
                                <div class="col-12"><h4 class="border-bottom"><span class="fa fa-cog"></span> Nominee</h4></div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Nominee Name <span class="error-message">*</span></label>
                                    <input type="text" name="nominee_name" class="form-control" value="<?php echo @$record->nominee_name; ?>">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">⁠Father/Husband Name: <span class="error-message">*</span></label>
                                    <input type="text" name="nominee_father_husband_name" class="form-control" value="<?php echo @$record->nominee_father_husband_name; ?>">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">CNIC <span class="error-message">*</span></label>
                                    <input type="text" name="nominee_cnic" class="form-control masking" placeholder="_____-______-_" value="<?php echo @$record->nominee_cnic; ?>">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">⁠Relationship with Customer <span class="error-message">*</span></label>
                                    <select name="relation" class="form-control">
                                        <option value="">Select One</option>
                                        <?php foreach(relation() as $k => $v){ ?>
                                        <option value="<?=$k?>" <?php if(@$record->relation == $k and $k !== "") echo 'selected="selected"'; ?>><?=$v?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                
                                <div class="col-12"><h4 class="border-bottom"><span class="fa fa-cog"></span> Agency</h4></div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Agency Name <span class="error-message">*</span></label>
                                    <input type="text" name="agency_name" class="form-control" value="<?php echo @$record->agency_name; ?>">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Agency Commission <span class="error-message">*</span></label>
                                    <input type="number" name="agency_commission" class="form-control" value="<?php echo @$record->agency_commission; ?>">
                                </div>
                                
                                <?php if ($slug_url == 'add') { ?>
                                <div class="col-12"><h4 class="border-bottom"><span class="fa fa-cog"></span> Booking Amount</h4></div>
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label class="col-form-label">Date</label>
                                    <input type="text" class="form-control datepicker" name="amount_date">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label class="col-form-label">Booking Amount <span class="error-message">*</span></label>
                                    <input type="number" class="form-control required" name="amount">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label class="col-form-label">Payment Method</label>
                                    <select name="payment_method" id="payment_method" class="form-control" onchange="referenceChange();">
                                        <option value="">Select One</option>
                                        <?php foreach(payment_method() as $k => $v){ ?>
                                        <option value="<?=$k?>"><?=$v?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-xs-12" id="reference" style="display: none;">
                                    <label class="col-form-label">Reference</label>
                                    <input type="text" class="form-control" name="reference">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label class="col-form-label">Image ⁠⁠⁠Proof <span class="error-message">*</span></label><br />
                                    <input type="file" name="proof_image" class="required">
                                    
                                    <?php if(!empty(@$record->proof_image)) { ?>
                                    <input type="hidden" name="update_image" value="<?php echo @$record->proof_image; ?>">
                                    <?php } ?>
                                    
                                    <?php if ($slug_url != 'add') {
									if(!empty(@$record->proof_image)) { ?>
                                    <div class="activity-img"><a href="<?php echo site_url('uploads/booking_receipt/'.@$record->proof_image); ?>" class="lightbox-image"><img src="<?php echo site_url('uploads/booking_receipt/'.@$record->proof_image); ?>" alt="Image"></a></div>
                                    <?php }
									} ?>
                                </div>
                                <?php } ?>
                                <div class="clearfix"></div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <button type="submit" class="btn btn-custom waves-effect waves-light form-submit-button">Save & Exit</button>
                                </div>
                            </div>
                        </form>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/*//Reference Change
function referenceChange() {
    var paymentMethod = $('#payment_method').val();
    var reference = $('#reference');

    if (paymentMethod === '2') { // Check if payment method is 'Cash'
        reference.hide();
        reference.find('input[name="reference"]').prop('required', false);
        reference.find('input[name="reference"]').removeClass('error');
        reference.find('label.error').hide();
    } else {
        reference.show();
        reference.find('input[name="reference"]').prop('required', true);
    }
}*/

function generate_inventory_id() {
	let inventoryId = $('.unit-dropdown option:selected').data('inventory');
	$('#inventory_id').val(inventoryId);
}

$('.project-dropdown').trigger('change');
$('.property-type-dropdown').trigger('change');
$('.unit-dropdown').trigger('change');
</script>
