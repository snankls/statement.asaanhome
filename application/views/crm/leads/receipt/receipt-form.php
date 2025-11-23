<?php $last = $this->uri->total_segments();
$slug_url = $this->uri->segment($last); ?>
<?php $is_admin = is_admin_logged_in(array(1)); ?>
<form id="receipt-form" class="form-horizontal Form" enctype="multipart/form-data" role="form" action="<?php echo site_url('leads/leads_receipt_setup_post/'.@$lead_id); ?>">
    <input type="hidden" name="update_id">
    <div class="form-row">
        <div class="form-group col-lg-3 col-xs-12">
            <label class="col-form-label">Name <span class="error-message">*</span></label>
            <input type="text" name="name" class="form-control required">
        </div>
        <div class="form-group col-lg-3 col-xs-12">
            <label class="col-form-label">Mobile <span class="error-message">*</span></label>
            <input type="text" name="mobile" class="form-control required" value="<?php echo @$phone_number; ?>" readonly>
        </div>
        <div class="form-group col-lg-3 col-xs-12">
            <label class="col-form-label">CNIC <span class="error-message">*</span></label>
            <input type="text" name="cnic" class="form-control masking required" placeholder="_____-______-_" maxlength="15">
        </div>
        <div class="form-group col-lg-3 col-xs-12">
            <label class="col-form-label">Select Project <span class="error-message">*</span></label>
            <select class="form-control project-dropdown required" name="project_id" onchange="CF.BookingPropertyTypesChanged(this);">
                <option value="">Select One</option>
                <?php foreach($project_list as $data): ?>
                <option value="<?php echo $data->project_id; ?>">
                    <?php echo $data->project_name; ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group col-lg-3 col-xs-12">
            <label class="col-form-label">Property Type <span class="error-message">*</span></label>
            <select class="form-control property-type-dropdown required" name="property_type">
                <option value="">Select One</option>
                
            </select>
        </div>
        <div class="form-group col-lg-3 col-xs-12">
            <label class="col-form-label">Select Unit # <span class="error-message">*</span></label>
            <input type="text" name="unit_number" class="form-control required">
        </div>
        <div class="form-group col-lg-3 col-xs-12">
            <label class="col-form-label">Payment Type <span class="error-message">*</span></label>
            <select name="payment_type" class="form-control required">
                <option value="">Select One</option>
                <?php foreach(payment_type() as $k => $v){ ?>
                <option value="<?=$k?>" <?php if(@$record->area_unit == $k and $k !== "") echo 'selected="selected"'; ?>><?=$v?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group col-lg-3 col-xs-12">
            <label class="col-form-label">Unit Price <span class="error-message">*</span></label>
            <input type="text" name="unit_price" class="form-control required">
        </div>
        <div class="form-group col-lg-3 col-xs-12">
            <label class="col-form-label">Discount Amount</label>
            <input type="text" name="discount_amount" class="form-control required">
        </div>
        <div class="form-group col-lg-3 col-xs-12">
            <label class="col-form-label">Settled Price</label>
            <input type="text" name="settled_price" class="form-control required">
        </div>
        <div class="form-group col-lg-3 col-xs-12">
            <label class="col-form-label">Received Amount</label>
            <input type="text" name="received_amount" class="form-control required">
        </div>
        <div class="form-group col-lg-3 col-xs-12">
            <label class="col-form-label">Balance Amount</label>
            <input type="text" name="balance_amount" class="form-control required">
        </div>
        <div class="form-group col-lg-3 col-xs-12">
            <label class="col-form-label">Balance Payment Deadline</label>
            <input type="text" name="balance_payment_deadline" class="form-control datepicker required">
        </div>
        
        <?php if($is_admin == 'yes') { ?>
        <div class="form-group col-lg-3 col-xs-12">
            <label class="col-form-label">Status</label>
            <select name="status" class="form-control required">
                <option value="">Select One</option>
                <?php foreach(receipt_status() as $k => $v){ ?>
                <option value="<?=$k?>" <?php if(1 == $k and $k !== "") echo 'selected="selected"'; ?>><?=$v?></option>
                <?php } ?>
            </select>
        </div>
        <?php } ?>

        <div class="form-group col-lg-12 col-xs-12">
            <label class="col-form-label">Other Conditions</label>
            <textarea class="form-control" name="other_conditions"></textarea>
        </div>
        <div class="form-group col-lg-12 col-xs-12">
            <label class="col-form-label">Remarks</label>
            <textarea class="form-control" name="remarks"></textarea>
        </div>
        
        <div class="form-group col-lg-12 col-xs-12">
            <label class="col-form-label">Receipt Image <span class="error-message">*</span></label><br>
            <input type="file" name="receipt_image" value="<?php echo @$record->image; ?>">
            <input type="hidden" name="update_receipt_image">
            
            <?php if ($slug_url == 'receipt') { ?>
            <div class="activity-img"><a href="<?php echo site_url('uploads/receipt/'.@$receipt_image); ?>" name="receipt_image_link" class="lightbox-image"><img src="<?php echo site_url('uploads/receipt/'.@$receipt_image); ?>" name="receipt_image_preview" alt="Image"></a></div>
            <?php } ?>
        </div>

        <div class="clearfix"></div>
    </div>
    <div class="form-row">
        <div class="form-group col-12">
            <button type="submit" class="btn btn-custom waves-effect waves-light form-submit-button">Save</button>
        </div>
    </div>
</form>

<script>
if($('.masking').length){
    $('.masking').mask("99999-9999999-9");
}

//Datepicker
jQuery(".datepicker").datepicker({
    "setDate": new Date(),
    autoclose: true,
    format: 'yyyy-mm-dd',
    todayHighlight: true,
    orientation: "bottom left",
});
</script>