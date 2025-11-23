<?php //pre_print($record); ?>
<?php $last = $this->uri->total_segments();
$slug_url = $this->uri->segment($last); ?>
<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                	<div id="voucher-list">
                    	<form id="update-form" class="form-horizontal disabled-field Form" enctype="multipart/form-data" role="form" action="<?php echo site_url('voucher/voucher_setup_post/'.@$record->voucher_id); ?>">
                        	<input type="hidden" name="last_uri" value="project" />
                        	<div class="form-row">
                                <div class="form-group col-lg-4 col-xs-12">
                                    <label class="col-form-label">Select Project</label>
                                    <select name="project_id" id="project-id" class="form-control project-id required" onchange="CF.Coa4Changed(this);">
                                    	<option value="">Select One</option>
                                        <?php foreach($project_list as $data): ?>
										<option value="<?php echo $data->project_id; ?>" <?php if(@$record->project_id == $data->project_id) echo 'selected="selected"'; ?>><?php echo $data->project_name; ?></option>
										<?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-lg-4 col-xs-12">
                                    <label class="col-form-label">Transaction Type</label>
                                    <select name="transaction_type" id="transaction-type" class="form-control required">
                                    	<option value="">Select One</option>
                                        <?php foreach(transaction_type() as $k => $v){ ?>
                                        <option value="<?=$k?>" <?php if(@$record->transaction_type == $k and $k !== "") echo 'selected="selected"'; ?>><?=$v?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-lg-4 col-xs-12">
                                	<label class="col-form-label">Voucher Date <span class="error-message">*</span></label>
                                    <input type="text" name="voucher_date" class="form-control datepicker required" value="<?php echo @$record->voucher_date; ?>">
                                </div>
                                <div class="form-group col-lg-12 col-xs-12">
                                    <label class="col-form-label">Images</label><br>
                                    <input type="file" name="voucher_image[]" multiple>

                                    <?php 
                                        if (!empty($voucher_images)) {
                                            foreach ($voucher_images as $img_obj) {
                                                $img = $img_obj->image_name;
                                        ?>
                                                <input type="hidden" name="update_voucher_image[]" value="<?= $img ?>">
                                                <div class="activity-img">
                                                    <a href="<?= site_url('uploads/vouchers/' . $img) ?>" class="lightbox-image">
                                                        <img src="<?= site_url('uploads/vouchers/' . $img) ?>" alt="Image">
                                                    </a>
                                                </div>
                                        <?php
                                            }
                                        }
                                    ?>
                                </div>

                                <!-- <div class="form-group col-lg-12 col-xs-12">
                                    <label class="col-form-label">Image</label><br>
                                    <input type="file" name="voucher_image[]" multiple="multiple">
                                    
                                    <?php /*if(!empty(@$record->voucher_image)) { ?>
                                    <input type="hidden" name="update_voucher_image" value="<?php echo @$record->voucher_image; ?>">
                                    <?php } ?>
                                    
                                    <?php if ($slug_url != 'add') {
									if(!empty(@$record->voucher_image)) { ?>
                                    <div class="activity-img"><a href="<?php echo site_url('uploads/vouchers/'.@$record->voucher_image); ?>" class="lightbox-image"><img src="<?php echo site_url('uploads/vouchers/'.@$record->voucher_image); ?>" alt="Image"></a></div>
                                    <?php }
									}*/ ?>
                                </div> -->
                                <div class="clearfix"></div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group col-lg-12 col-xs-12">
                                    <table id="voucher-table" class="table table-bordered">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th width="40"><input class='check_all' type='checkbox' onclick="select_all()"/></th>
                                                <th width="250">Account Number</th>
                                                <th>Narration</th>
                                                <th>Debit</th>
                                                <th>Credit</th>
                                                <th>Voucher Type</th>
                                            </tr>
                                        </thead>
                                        <tbody id="voucher-details">
    
                                        <?php foreach($voucher_details as $voucher): ?>
                                            <?php $this->load->view("finance/voucher/snippets/voucher-details.php", array('data' => $voucher)); ?>
                                        <?php endforeach; ?>
    
                                        </tbody>
                                    </table>
                                    <a href="javascript:;" class="btn btn-success add-row">Add</a> &nbsp;
                                    <a href="javascript:;" class="btn btn-danger" onclick="delete_row();">Delete</a>
                                </div>
                            </div>
                            <br />
							<div class="form-row product-table-container">
								<div class="col-7"></div>
								<div class="col-5 text-right section-style">
                                	<div class="row">
										<div class="form-group col-6">
											<span class="section-total">Total Debit</span>
											<input type="text" name="total_debit" class="form-control total-debit" value="<?php echo @$record->total_debit; ?>" readonly="readonly">
										</div>
										<div class="form-group col-6">
											<span class="section-total">Total Credit</span>
											<input type="text" name="total_credit" class="form-control total-credit" value="<?php echo @$record->total_credit; ?>" readonly="readonly">
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
                            <br />
                            
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <button type="submit" class="btn btn-custom waves-effect waves-light form-submit-button" onclick="match_sum();">Save & Exit</button>
                                </div>
                            </div>
                        </form>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Voucher Detail-->
<table class="sample-data hidden">
	<?php $this->load->view("finance/voucher/snippets/voucher-details", array("data" => array())); ?>
</table>

<script>
//Record Detail
$(".add-row").on('click', function() {
    var project_id = $('#project-id').val();
    if (project_id == '') {
        alert('Please select project first.');
        return false;
    }

    let new_row = $('.sample-data .voucher-details-row').clone()[0];
    let count = $('#voucher-details tr').length + 1;
    $('.voucher-case', new_row).val(count);

    $('#voucher-details').append(new_row);
});

$(document).ready(function() {
    // Trigger on Project selection change
    $('#project-id').on('change', function() {
        var projectId = $(this).val();
        if (projectId == '') {
            alert('Please select a project first.');
            return false;
        }

        // Iterate over each voucher-details-row to update the account dropdown
        $('.voucher-details-row').each(function() {
            var $row = $(this);
            var $accountDropdown = $row.find('select[name="account_number[]"]');
            
            // Clear the existing options
            $accountDropdown.html('<option value="">Select Account</option>');
            
            // Make AJAX call to fetch the account options based on the selected project
            $.ajax({
                url: site_url + 'api/get_coa_4_name_json',  // Ensure this URL is correct
                type: 'POST',
                data: { project_id: projectId },
                dataType: 'json',
                success: function(response) {
                    console.log('Server Response:', response);
                    
                    // Check if 'dropdown_options' exists in the response
                    if (response && response.dropdown_options) {
                        // Directly use the returned dropdown_options
                        $accountDropdown.html(response.dropdown_options);

                        // Set the selected option (if editing existing record)
                        var selectedAccountId = $accountDropdown.data('selected');
                        if (selectedAccountId) {
                            $accountDropdown.val(selectedAccountId);
                        }
                    } else {
                        console.error('Unexpected response format:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        });
    });

    // Code for initial loading on edit page
    if (window.location.href.includes('/edit/')) {
        $('#project-id').trigger('change');
    }
});

//Delete More Row
function delete_row(){
	if (confirm("do you want to permanent delete?")) {
		var o = new Object();
		o.delete_ids = $('#update-form').serializeArray();

		if(o.delete_ids == '')
		{
			$('.voucher-case:checkbox:checked').parents("tr").remove();
			calculation();
		}
		else
		{
			$.post( site_url + '/voucher/voucher_details_delete/', o, function(result)
			{
				if( result.msg == "SUCCESS" )
				{
					$('.voucher-case:checkbox:checked').parents("tr").remove();
					calculation();
				}
				else
					alert(result.data);
			},"json");
		}
    }
	return false;
}

function select_all() {
    $('input.case:checkbox').each(function(){
		if($('input[class=check_all]:checkbox:checked').length == 0){
			$(this).prop("checked", false);
		} else {
			$(this).prop("checked", true);
		}
	});
}

// Voucher Calculation
function calculation() {
    var total_debit = 0;
    var total_credit = 0;

    // Calculate total debit
    $("input[name='debit[]']").each(function() {
        var debit_value = parseFloat($(this).val());
        if (!isNaN(debit_value)) {
            total_debit += debit_value;
        }
    });

    // Calculate total credit
    $("input[name='credit[]']").each(function() {
        var credit_value = parseFloat($(this).val());
        if (!isNaN(credit_value)) {
            total_credit += credit_value;
        }
    });

    // Update total debit and total credit fields
    $('.total-debit').val(total_debit);
    $('.total-credit').val(total_credit);
}

// Function to check if total debit and total credit match
function match_sum() {
    var type = $('#transaction-type').val();
    
    // Check if type is 1 (General)
    if(type == 1) {
        var sum_debit = $('.total-debit').val();
        var sum_credit = $('.total-credit').val();
        
        if (sum_debit != sum_credit) {
            alert('Debit and Credit values do not match.');
            return false;
        }
    }
    // No return value needed here as we only prevent action for type 1
    return true;
}

// Form submission logic
$('#update-form').submit(function() {
    // Check if total debit and total credit match before submitting the form
    return match_sum();
});

$('input[name="debit[]"], input[name="credit[]"]').on('change', function() {
    calculation();
});

//Debit or Credit Value 0 if empty
$(document).ready(function() {
    function updateFields() {
        $('#voucher-table').find('.voucher-details-row').each(function() {
            var $row = $(this);
            var $debit = $row.find('input[name="debit[]"]');
            var $credit = $row.find('input[name="credit[]"]');
            
            if ($debit.val() === '' && $credit.val() !== '') {
                $debit.val('0');
            }
            
            if ($credit.val() === '' && $debit.val() !== '') {
                $credit.val('0');
            }
        });
    }
	
    $('#voucher-table').on('keyup change', 'input[name="debit[]"], input[name="credit[]"]', function() {
        updateFields();
    });
});
</script>
