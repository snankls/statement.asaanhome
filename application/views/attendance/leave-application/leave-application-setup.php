<?php $last = $this->uri->total_segments();
$slug_url = $this->uri->segment($last); ?>
<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                	<div id="purchase-list">
                    	<form id="update-form" class="form-horizontal disabled-field Form" enctype="multipart/form-data" role="form" action="<?php echo site_url('attendance/leave_application_setup_post/'.@$record->application_id); ?>">
                        	<input type="hidden" name="last_uri" value="leave-application" />
                        	<div class="form-row">
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Leave Type <span class="error-message">*</span></label>
									<select name="leave_type" class="form-control required">
                                        <option value="">Select One</option>
                                        <?php foreach(leave_type() as $k => $v){ ?>
                                        <option value="<?=$k?>" <?php if(@$record->leave_type == $k and $k !== "") echo 'selected="selected"'; ?>><?=$v?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label class="col-form-label">Date From</label>
                                    <input type="text" id="from-date" name="from_date" class="form-control datepicker required" value="<?php echo @$record->date_from; ?>">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label class="col-form-label">Date To</label>
                                    <input type="text" id="to-date" name="to_date" class="form-control datepicker required" value="<?php echo @$record->date_to; ?>">
                                </div>

                                <?php if ( $is_admin == "yes" ){ ?>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Leave Type <span class="error-message">*</span></label>
									<select name="status" class="form-control required">
                                        <option value="">Select One</option>
                                        <?php foreach(application_status() as $k => $v){ ?>
                                        <option value="<?=$k?>" <?php if(@$record->status == $k and $k !== "") echo 'selected="selected"'; ?>><?=$v?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <?php } ?>

                                <div class="form-group col-lg-12 col-xs-12">
                                	<label class="col-form-label">Reason</label>
                                    <textarea class="form-control required" name="reason"><?php echo @$record->reason; ?></textarea>
                                </div>
                                <div class="form-group col-lg-12 col-xs-12">
                                    <label class="col-form-label">Attach Proof</label><br>
                                    <input type="file" name="proof_image" value="<?php echo @$record->image; ?>">
                                    
                                    <?php if(!empty(@$record->image)) { ?>
                                    <input type="hidden" name="update_proof_image" value="<?php echo @$record->image; ?>">
                                    <?php } ?>
                                    
                                    <?php if ($slug_url != 'add') {
									if(!empty(@$record->image)) { ?>
                                    <div class="activity-img"><a href="<?php echo site_url('uploads/leave-application/'.@$record->image); ?>" class="lightbox-image"><img src="<?php echo site_url('uploads/leave-application/'.@$record->image); ?>" alt="Image"></a></div>
                                    <?php }
									} ?>
                                </div>
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
