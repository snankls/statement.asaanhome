<?php $last = $this->uri->total_segments();
$slug_url = $this->uri->segment($last); ?>
<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                	<div id="purchase-list">
                    	<form id="update-form" class="form-horizontal disabled-field Form" role="form" action="<?php echo site_url('inventory/inventory_setup_post/'.@$record->inventory_id); ?>">
                        	<input type="hidden" name="last_uri" value="inventory" />
                        	<input type="text" name="inventory_main_id" class="inventory_id" value="<?php echo @$record->inventory_id; ?>" />
                        	<div class="form-row">
                            	<div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Select Project <span class="error-message">*</span></label>
                                    <select class="form-control project-dropdown required" name="project_id" onchange="CF.PropertyTypesChanged(this);" <?php if(isset($record->status) && $record->status == 2 && $current_role_id == 2) echo 'readonly="readonly"'; ?>>
                            			<option value="">Select One</option>
										<?php foreach($project_list as $data): ?>
										<option value="<?php echo $data->project_id; ?>" <?php if(@$record->inventory_project_id == $data->project_id) echo 'selected="selected"'; ?>><?php echo $data->project_name; ?></option>
										<?php endforeach; ?>
                                    </select>
                                </div>
                            	<div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Property Type <span class="error-message">*</span></label>
                                    <select class="form-control property-type-dropdown required" name="property_type" data-selected="<?php echo @$record->property_type; ?>" <?php if(isset($record->status) && $record->status == 2 && $current_role_id == 2) echo 'readonly="readonly"'; ?>>
                            			<option value="">Select One</option>
										
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Floor/Block <span class="error-message">*</span></label>
                                    <input type="text" name="floor_block" class="form-control required" value="<?php echo @$record->floor_block; ?>" <?php if(isset($record->status) && $record->status == 2 && $current_role_id == 2) echo 'readonly="readonly"'; ?>>
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Unit # <span class="error-message">*</span></label>
                                    <input type="text" name="unit_number" class="form-control required" value="<?php echo @$record->unit_number; ?>" <?php if(isset($record->status) && $record->status == 2 && $current_role_id == 2) echo 'readonly="readonly"'; ?>>
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Plan Type <span class="error-message">*</span></label>
                                    <select class="form-control required" name="plan_type" onChange="togglePlanFields();">
                            			<option value="">Select One</option>
										<option value="Installment" <?php if(@$record->plan_type == 'Installment') echo 'selected'; ?>>Installment</option>
										<option value="Milestone" <?php if(@$record->plan_type == 'Milestone') echo 'selected'; ?>>Milestone</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-xs-12 show-installment" style="display:none;">
                                	<label class="col-form-label">Payment Plan (in month) <span class="error-message">*</span></label>
                                    <input type="number" name="payment_plan" class="form-control payment_plan required" value="<?php echo @$record->payment_plan; ?>">
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Unit Size <span class="error-message">*</span></label>
                                    <input type="text" name="unit_size" class="form-control" value="<?php echo @$record->unit_size; ?>" <?php if(isset($record->status) && $record->status == 2 && $current_role_id == 2) echo 'readonly="readonly"'; ?>>
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Unit Category <span class="error-message">*</span></label>
                                    <input type="text" name="unit_category" class="form-control" value="<?php echo @$record->unit_category; ?>" <?php if(isset($record->status) && $record->status == 2 && $current_role_id == 2) echo 'readonly="readonly"'; ?>>
                                </div>
                                <div class="form-group col-lg-3 col-xs-12">
                                	<label class="col-form-label">Total Price <span class="error-message">*</span></label>
                                    <input type="number" name="total_price" class="form-control required" value="<?php echo @$record->total_price; ?>" <?php if(isset($record->status) && $record->status == 2 && $current_role_id == 2) echo 'readonly="readonly"'; ?> onkeyup="calculateTotalEdit();">
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            
                            <div class="form-row">
                            	<div class="form-group col-12">
                                	<?php //if(@$record->status == 1 or @$record->status == '') { ?>
                                    <div class="show-installment" style="display:none;">
                                	    <button type="button" class="btn btn-custom waves-effect waves-light form-submit-button" onclick="generateRows();">Generate Installment</button><br /><br />
                                    </div>
                                    <?php //} ?>

                                    <?php if(@$record->plan_type == 'Installment') { ?>
                                        <!-- Installment -->
                                        <div id="installment-list">
                                            <?php if (!empty($installment_list)) { ?>
                                            <table id="installment-table-edit" class="table table-bordered">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th width="50">Sr #</th>
                                                        <th>Date</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i=1; $total_amount = 0;
                                                    foreach ($installment_list as $data) {
                                                        $total_amount += $data->amount; ?>
                                                    <tr class="additional-row">
                                                        <td><?php echo $i; ?><input type="hidden" name="installment_id[]" value="<?php echo $data->installment_id ?>" />
                                                        <td><input type="text" name="date[]" class="form-control datepicker-edit" tabIndex="-1" value="<?php echo get_date_string_sql($data->inventory_date); ?>" <?php if($i != 1) echo 'readonly'; ?> /></td>
                                                        <td><input type="text" name="amount[]" id="installment-amount-edit<?php echo $i; ?>" class="form-control installment-amount" value="<?php echo $data->amount; ?>" onkeyup="calculateTotalEdit();" onfocus="calculateTotalEdit();" /></td>
                                                    </tr>
                                                    <?php $i++; } ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="2" style="text-align: right;"><strong>Total:</strong></td>
                                                        <td id="total-amount-edit" style="font-weight:bold;"><?php echo number_format($total_amount); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="text-align: right;"><strong>Difference:</strong></td>
                                                        <td id="difference-amount-edit" class="difference-amount" style="font-weight:bold;">0</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <?php } ?>
                                        </div>
                                    <?php } else { ?>
                                        <!-- Milestone -->
                                        <div id="installment-list">
                                            <?php if (!empty($milestone_list)) { ?>
                                            <table id="installment-table-edit" class="table table-bordered">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th width="50">Sr #</th>
                                                        <th>Milestone Name</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i=1; $total_amount = 0;
                                                    foreach ($milestone_list as $data) {
                                                        $total_amount += $data->amount; ?>
                                                    <tr class="additional-row">
                                                        <td><?php echo $i; ?><input type="text" name="milestone_id[]" value="<?php echo $data->milestone_id ?>" />
                                                        <td><input type="text" name="milestone_name[]" class="form-control" tabIndex="-1" value="<?php echo $data->milestone_name; ?>" readonly /></td>
                                                        <td><input type="text" name="milestone_amount[]" id="milestone-amount-edit<?php echo $i; ?>" class="form-control installment-amount" value="<?php echo $data->amount; ?>" onkeyup="calculateTotalEdit();" onfocus="calculateTotalEdit();" /></td>
                                                    </tr>
                                                    <?php $i++; } ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="2" style="text-align: right;"><strong>Total:</strong></td>
                                                        <td id="total-amount-edit" style="font-weight:bold;"><?php echo number_format($total_amount); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="text-align: right;"><strong>Difference:</strong></td>
                                                        <td id="difference-amount-edit" class="difference-amount" style="font-weight:bold;">0</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        <?php } ?>
                                    </div>
                                    <?php } ?>
                                </div>
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
function togglePlanFields() {
    var projectId = $('[name=project_id]').val();
    var planType = $('[name=plan_type]').val();

    if (planType === 'Installment') {
        $('.show-installment').show();
        $('[name=payment_plan]').prop('disabled', false);
        $('#installment-list').html('');
    } else if (planType === 'Milestone') {
        $('.show-installment').hide();
        $('[name=payment_plan]').prop('disabled', true);

        if(projectId == '')
        {
            alert('Please select Project first.');
            $('[name=plan_type]').val('');
            return false;
        }

        // Fetch milestone data via AJAX
        $.ajax({
            url: site_url + "inventory/get_milestones/" + projectId,
            type: "GET",
            dataType: "json",
            success: function (res) {
                if (res && res.length > 0) {
                    var html = `
                        <table id="milestone-table-edit" class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="50">Sr #</th>
                                    <th>Milestone</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    res.forEach(function (row, index) {
                        var milestoneAmount = row.amount ? row.amount : 0;
                        html += `
                            <tr class="additional-row">
                                <td>${index + 1}</td>
                                <td>
                                    <input type="hidden" name="project_milestone_id[]" value="${row.project_detail_id}">
                                    <input type="text" name="milestone_name[]" class="form-control" tabIndex="-1" value="${row.milestone_name}" readonly>
                                </td>
                                <td>
                                    <input type="text" name="milestone_amount[]" class="form-control installment-amount" 
                                        value="${milestoneAmount}" 
                                        onkeyup="calculateTotalEdit();" 
                                        onfocus="calculateTotalEdit();" />
                                </td>
                            </tr>
                        `;
                    });

                    html += `
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" style="text-align: right;"><strong>Total:</strong></td>
                                    <td id="total-amount-edit" style="font-weight:bold;">0</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: right;"><strong>Difference:</strong></td>
                                    <td id="difference-amount-edit" style="font-weight:bold;">0</td>
                                </tr>
                            </tfoot>
                        </table>
                    `;

                    $('#installment-list').html(html);

                    // Recalculate totals immediately
                    calculateTotalEdit();
                } else {
                    $('#installment-list').html('<p>No milestones found for this project.</p>');
                }
            },

            error: function () {
                $('#installment-list').html('<p class="text-danger">Error loading milestones.</p>');
            }
        });
    } else {
        $('.show-installment').hide();
        $('#installment-list').html('');
    }
}

// Function to generate rows for both add and edit forms
function generateRows() {
    var paymentPlan = parseInt($('.payment_plan').val());

    if (!paymentPlan || paymentPlan <= 0) {
        alert('Please enter a valid number of months.');
        return false;
    }

    // Check if we're in edit mode
    var isEditMode = $('#installment-table-edit').length > 0;
    
    if (isEditMode) {
        // EDIT MODE - Update existing table
        var $tbody = $('#installment-table-edit tbody');
        var existingRows = $tbody.find('tr').length;
        
        // Add missing rows
        if (existingRows < paymentPlan) {
            for (var i = existingRows; i < paymentPlan; i++) {
                var newRowNum = i + 1;
                var newRow = '<tr class="additional-row">' +
                    '<td>' + newRowNum + 
                    '<input type="hidden" name="installment_id[]" value="" />' +
                    '<td><input type="text" name="date[]" class="form-control datepicker-edit"' + 
                    (i === 0 ? '' : ' readonly tabIndex="-1"') + ' /></td>' +
                    '<td><input type="text" name="amount[]" id="installment-amount-edit' + newRowNum + 
                    '" class="form-control installment-amount" value="" ' +
                    'onkeyup="calculateTotalEdit();" onfocus="calculateTotalEdit();" /></td>' +
                    '</tr>';
                $tbody.append(newRow);
            }
            
            // Initialize datepickers for new rows
            $('.datepicker-edit').datepicker({
                autoclose: true,
                format: 'dd-M-yyyy',
                todayHighlight: true,
                orientation: "bottom left"
            });
            
            // Set up date change handler for new rows
            $('.datepicker-edit').off('change').on('change', function() {
                var index = $(this).closest('tr.additional-row').index();
                var selectedDate = $(this).val() ? new Date($(this).datepicker('getDate')) : null;
                updateDatesEdit(selectedDate, index);
            });
            
            // Update dates based on first row if it has a value
            var firstDate = $('.datepicker-edit:first').val();
            if (firstDate) {
                $('.datepicker-edit:first').trigger('change');
            }
        } 
        // Remove extra rows
        else if (existingRows > paymentPlan) {
            $tbody.find('tr:gt(' + (paymentPlan - 1) + ')').remove();
        }
        
        calculateTotalEdit();
    } else {
        // ADD MODE - Create new table or update existing
        var $table = $('#installment-table');
        
        if ($table.length === 0) {
            // Create new table
            var html = '<table id="installment-table" class="table table-bordered">' +
                '<thead class="thead-dark">' +
                '<tr><th width="50">Sr #</th><th>Date</th><th>Amount</th></tr>' +
                '</thead><tbody>';
            
            for (var i = 0; i < paymentPlan; i++) {
                html += '<tr class="additional-row">' +
                    '<td>' + (i + 1) + 
                    '<input type="hidden" name="installment_id[]" value="" />' +
                    '<td><input type="text" name="date[]" class="form-control datepicker"' + 
                    (i === 0 ? '' : ' readonly tabIndex="-1"') + ' /></td>' +
                    '<td><input type="text" name="amount[]" value="0" id="installment-amount' + i + 
                    '" class="form-control" onkeyup="calculateTotal();" onfocus="calculateTotal();" /></td>' +
                    '</tr>';
            }
            
            html += '</tbody><tfoot>' +
                '<tr><td colspan="2" style="text-align: right;"><strong>Total:</strong></td>' +
                '<td id="total-amount" style="font-weight:bold;">0</td></tr>' +
                '<tr><td colspan="2" style="text-align: right;"><strong>Difference:</strong></td>' +
                '<td id="difference-amount" style="font-weight:bold;">0</td></tr>' +
                '</tfoot></table>';
            
            $('#installment-list').html(html);
            
            // Initialize datepickers
            $('.datepicker').datepicker({
                autoclose: true,
                format: 'dd-M-yyyy',
                todayHighlight: true,
                orientation: "bottom left"
            });
            
            // Set up date change handler
            $('.datepicker').off('change').on('change', function() {
                var index = $(this).closest('tr.additional-row').index();
                var selectedDate = $(this).val() ? new Date($(this).datepicker('getDate')) : null;
                updateDates(selectedDate, index);
            });
        } else {
            // Update existing table
            var $tbody = $table.find('tbody');
            var existingRows = $tbody.find('tr').length;
            
            // Add missing rows
            if (existingRows < paymentPlan) {
                for (var i = existingRows; i < paymentPlan; i++) {
                    $tbody.append('<tr class="additional-row">' +
                        '<td>' + (i + 1) + 
                        '<input type="hidden" name="installment_id[]" value="" />' +
                        '<td><input type="text" name="date[]" class="form-control datepicker" readonly tabIndex="-1" /></td>' +
                        '<td><input type="text" name="amount[]" id="installment-amount' + i + 
                        '" class="form-control" onkeyup="calculateTotal();" onfocus="calculateTotal();" /></td>' +
                        '</tr>');
                }
                
                // Reinitialize datepickers
                $('.datepicker').datepicker({
                    autoclose: true,
                    format: 'dd-M-yyyy',
                    todayHighlight: true,
                    orientation: "bottom left"
                });
                
                // Update dates based on first row if it has a value
                var firstDate = $('.datepicker:first').val();
                if (firstDate) {
                    $('.datepicker:first').trigger('change');
                }
            } 
            // Remove extra rows
            else if (existingRows > paymentPlan) {
                $tbody.find('tr:gt(' + (paymentPlan - 1) + ')').remove();
            }
        }
        
        calculateTotal();
    }
}

// Date update function for edit form
function updateDatesEdit(selectedDate, startIndex) {
    if (!selectedDate) return;
    
    var inputs = $('.datepicker-edit');
    
    for (var i = startIndex + 1; i < inputs.length; i++) {
        var newDate = new Date(selectedDate);
        newDate.setMonth(selectedDate.getMonth() + (i - startIndex));
        
        // Format the date correctly
        var day = ("0" + newDate.getDate()).slice(-2);
        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        var month = monthNames[newDate.getMonth()];
        var year = newDate.getFullYear();
        
        var formattedDate = day + '-' + month + '-' + year;
        $(inputs[i]).val(formattedDate);
    }
}

// Similarly update the add form date function
function updateDates(selectedDate, startIndex) {
    if (!selectedDate) return;
    
    var inputs = $('.datepicker');
    
    for (var i = startIndex + 1; i < inputs.length; i++) {
        var newDate = new Date(selectedDate);
        newDate.setMonth(selectedDate.getMonth() + (i - startIndex));
        
        // Format the date correctly
        var day = ("0" + newDate.getDate()).slice(-2);
        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        var month = monthNames[newDate.getMonth()];
        var year = newDate.getFullYear();
        
        var formattedDate = day + '-' + month + '-' + year;
        $(inputs[i]).val(formattedDate);
    }
}

// Calculate total sum of amounts
function calculateTotal() {
    var total_amount = 0;
    $("#installment-table tbody tr").each(function() {
        var amount = parseFloat($(this).find('[id^=installment-amount]').val()) || 0;
        total_amount += amount;
    });
    
    $('#total-amount').text(total_amount.toLocaleString('en-US'));
    get_difference();
}

function initializeEditDatepickers() {
    $('.datepicker-edit').datepicker({
        autoclose: true,
        format: 'dd-M-yyyy',
        todayHighlight: true,
        orientation: "bottom left"
    });
    
    $('.datepicker-edit').off('change').on('change', function() {
        var index = $(this).closest('tr.additional-row').index();
        var selectedDate = $(this).val() ? new Date($(this).val()) : null;
        updateDatesEdit(selectedDate, index);
    });
    
    // Trigger date update if first row has a date
    var firstDate = $('.datepicker-edit:first').val();
    if (firstDate) {
        $('.datepicker-edit:first').trigger('change');
    }
}

//////////Inventory Edit//////////
// Datepicker initialization
function updateDatesEdit(selectedDate, startIndex) {
    var inputs = document.querySelectorAll('.datepicker-edit');
    
    // Start filling dates from the startIndex row
    for (var i = startIndex + 1; i < inputs.length; i++) {
        if (selectedDate) {
            var newDate = new Date(selectedDate);
            newDate.setMonth(selectedDate.getMonth() + (i - startIndex));
            inputs[i].value = newDate.toLocaleDateString('en-GB', {day: '2-digit', month: 'short', year: 'numeric'}).replace(/ /g, '-');
        } else {
            inputs[i].value = ''; // Set date field to empty if no date is selected
        }
    }
}

function calculateTotalEdit() {
    var total_amount = 0;

    // Select visible milestone or installment tables
    var $activeTable = $('#installment-table-edit, #milestone-table-edit, #installment-list table:visible');

    $activeTable.each(function () {
        var subtotal = 0;
        $(this).find("tbody tr").each(function () {
            var amount = parseFloat($(this).find('.installment-amount').val()) || 0;
            subtotal += amount;
        });

        $(this).find('#total-amount-edit').text(subtotal.toLocaleString('en-US'));
        total_amount += subtotal;
    });

    get_difference();
}

function get_difference() {
    var total_price = parseFloat($('[name=total_price]').val()) || 0;
    var total_amount = 0;
    var $table = $('#installment-table-edit, #milestone-table-edit');

    if ($table.length) {
        var total_text = $table.find('#total-amount-edit').text().replace(/,/g, '');
        total_amount = parseFloat(total_text) || 0;
        $table.find('#difference-amount-edit').text((total_price - total_amount).toLocaleString('en-US'));
    } else {
        var $newTable = $('#installment-table, #milestone-table');
        var total_text = $newTable.find('#total-amount').text().replace(/,/g, '');
        total_amount = parseFloat(total_text) || 0;
        $newTable.find('#difference-amount').text((total_price - total_amount).toLocaleString('en-US'));
    }
}

// Initialize on document ready
$(document).ready(function(e) {
    // If we have an edit table, initialize it
    if ($('#installment-table-edit').length) {
        // Initialize datepickers for edit table
        $('.datepicker-edit').datepicker({
            autoclose: true,
            format: 'dd-M-yyyy',
            todayHighlight: true,
            orientation: "bottom left"
        });
        
        // Set up date change handler for edit table
        $('.datepicker-edit').off('change').on('change', function() {
            var index = $(this).closest('tr.additional-row').index();
            var selectedDate = $(this).val() ? new Date($(this).datepicker('getDate')) : null;
            updateDatesEdit(selectedDate, index);
        });
        
        // Trigger first datepicker if it has a value
        if ($('.datepicker-edit:first').val()) {
            $('.datepicker-edit:first').trigger('change');
        }
    }

    calculateTotalEdit();
    
    $('.project-dropdown').trigger('change');
});
</script>
