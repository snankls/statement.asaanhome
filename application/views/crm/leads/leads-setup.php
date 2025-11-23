<?php $last = $this->uri->total_segments();
$slug_url = $this->uri->segment($last); ?>
<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <div id="purchase-list">
                    	<form id="update-form" class="form-horizontal disabled-field Form wizard" enctype="multipart/form-data" role="form" action="<?php echo site_url('leads/leads_setup_post/'.@$record->lead_id); ?>">
                            <input type="hidden" name="last_uri" value="leads" />
                            <input type="hidden" name="update_id" value="<?php echo @$record->lead_id; ?>" />
                            <!-- Step Headings -->
                            <div class="step-headings">
                                <span class="step-heading" id="step-heading-1">1. Personal Information</span>
                                <span class="step-heading" id="step-heading-2">2. Other Information</span>
                                <span class="step-heading" id="step-heading-3">3. Completed</span>
                            </div>
                            
                            <div class="wizard-form">
                                <section>
                                    <div class="form-row">
                                    	<div class="form-group col-lg-3 col-xs-12">
                                            <label class="col-form-label">Name <span class="error-message">*</span></label>
                                            <input type="text" name="name" class="form-control" value="<?php echo @$record->name; ?>" />
                                        </div>
                                        <div class="form-group col-lg-3 col-xs-12">
                                            <label class="col-form-label">Country <span class="error-message">*</span></label>
                                            <select name="country" class="form-control select2 required">
                                                <option value="">Select One</option>
                                                <?php foreach(country_list() as $k => $v){ ?>
                                                <option value="<?=$k?>" <?php if(@$record->country_code == $k and $k !== "" or $k == '+92') echo 'selected="selected"'; ?>><?=$v?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3 col-xs-12">
                                            <label class="col-form-label">Phone Number <span class="error-message">*</span></label>
                                            <input type="text" name="phone_number" id="phone_number" class="form-control phone-masking" placeholder="3001234567" maxlength="10" value="<?php echo @$record->phone_number; ?>" />
                                        </div>
                                        <div class="form-group col-lg-3 col-xs-12">
                                            <label class="col-form-label">Email Address <span class="error-message">*</span></label>
                                            <input type="email" name="email_address" id="email_address" class="form-control" value="<?php echo @$record->email_address; ?>" />
                                        </div>
                                        <div class="form-group col-lg-3 col-xs-12">
                                            <label class="col-form-label">City <span class="error-message">*</span></label>
                                            <input type="text" name="city" id="city" class="form-control" value="<?php echo @$record->city; ?>" />
                                        </div>
                                        
                                        <div class="form-group col-lg-12 col-xs-12 error-message">
	                                        <div class="error-step1"></div>
                                        </div>
                                        
                                        <div class="form-group col-lg-12 col-xs-12 text-right">
                                        	<div class="wizard-navigation">
                                                <button type="button" class="btn btn-info waves-effect" onclick="nextStep(1)">Next</button>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                
                                <section>
                                    <div class="form-row">
                                        <div class="form-group col-lg-3 col-xs-12">
                                            <label class="col-form-label">Allocation</label>
                                            <select class="form-control allocation select2" name="allocation">
                                                <option value="">Select One</option>
                                                <?php foreach($crm_user_list as $data): ?>
                                                <option value="<?php echo $data->user_id; ?>" <?php if(@$record->user_id == $data->user_id or ($data->user_id == $current_user_id)) echo 'selected="selected"'; ?>><?php echo $data->fullname; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3 col-xs-12">
                                            <label class="col-form-label">Project</label>
                                            <select class="form-control" name="project">
                                                <option value="">Select One</option>
                                                <?php foreach($project_list as $data): ?>
                                                <option value="<?php echo $data->project_id; ?>" <?php if(@$record->project_id == $data->project_id) echo 'selected="selected"'; ?>><?php echo $data->project_name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3 col-xs-12">
                                            <label class="col-form-label">Status</label>
                                            <select name="status" class="form-control">
                                                <option value="">Select One</option>
                                                <?php foreach(lead_status() as $k => $v){ ?>
                                                <option value="<?=$k?>" <?php if(@$record->lead_status == $k and $k !== "") echo 'selected="selected"'; ?>><?=$v?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3 col-xs-12">
                                            <label class="col-form-label">Lead Source</label>
                                            <select name="lead_source" class="form-control required">
                                                <option value="">Select One</option>
                                                <?php foreach(lead_source() as $k => $v){ ?>
                                                <option value="<?=$k?>" <?php if(@$record->lead_source == $k and $k !== "") echo 'selected="selected"'; ?>><?=$v?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3 col-xs-12">
                                            <label class="col-form-label">Task Performed</label>
                                            <select name="task_performed" class="form-control required">
                                                <option value="">Select One</option>
                                                <?php foreach(task_performed() as $k => $v){ ?>
                                                <option value="<?=$k?>" <?php if(@$record->task_performed == $k and $k !== "") echo 'selected="selected"'; ?>><?=$v?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3 col-xs-12">
                                        	<label class="col-form-label">Next Followup Date</label>
                                            <input type="text" class="form-control datepicker" name="followup_date" value="<?php echo @$record->followup_date; ?>">
                                        </div>
                                        <div class="form-group col-lg-3 col-xs-12">
                                        	<label class="col-form-label">Next Task</label>
                                            <select name="next_task" class="form-control required">
                                                <option value="">Select One</option>
                                                <?php foreach(next_task() as $k => $v){ ?>
                                                <option value="<?=$k?>" <?php if(@$record->next_task == $k and $k !== "") echo 'selected="selected"'; ?>><?=$v?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-12 col-xs-12">
                                        	<label class="control-label">⁠Remarks</label>
                                            <textarea name="remarks" class="form-control"><?php echo @$record->remarks; ?></textarea>
                                    	</div>
                                    </div>
                                    
                                    <div class="form-group col-lg-12 col-xs-12 text-right">
                                        <div class="wizard-navigation">
                                            <button type="button" class="btn btn-success waves-effect" onclick="prevStep(1)">Previous</button> &nbsp;
                                            <button type="button" class="btn btn-info waves-effect" onclick="nextStep(2)">Next</button>
                                        </div>
                                    </div>
                                </section>
                                
                                <section>
                                    <div class="form-group clearfix">
                                        <div class="col-lg-12">
                                            <div class="checkbox checkbox-primary">
                                                "You’re about to submit this lead."
                                            </div>
                                        </div>
                                        
                                        <div class="form-group col-lg-12 col-xs-12 text-right">
                                            <div class="wizard-navigation">
                                                <button type="button" class="btn btn-success waves-effect" onclick="prevStep(2)">Previous</button> &nbsp;
                                                <button type="submit" class="btn btn-custom waves-effect waves-light form-submit-button">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentStep = 0;

function showStep(step) {
    const sections = document.querySelectorAll('section');
    sections.forEach((s, index) => {
        s.style.display = (index === step) ? 'block' : 'none';
    });

    document.querySelectorAll('.step-heading').forEach((heading, index) => {
        heading.classList.toggle('active', index === step);
    });
}

function validateStep(step) {
    const form = document.getElementById('update-form');
    const sections = form.querySelectorAll('section');
    const inputs = sections[step].querySelectorAll('input, select');
    let isValid = true;

    inputs.forEach(input => {
        // Remove any existing error messages
        if (input.nextElementSibling && input.nextElementSibling.classList.contains('error-message')) {
            input.nextElementSibling.remove();
        }

        // Get the associated label text by traversing the DOM
        const label = input.closest('.form-group').querySelector('label');
        const labelText = label ? label.textContent.trim().replace('*', '').trim() : input.name;

        // Create the error message element
        const errorElement = document.createElement('span');
        errorElement.classList.add('error-message');
        errorElement.style.color = 'red';

        // Check validity and append error message if invalid
        if (!input.checkValidity() || input.value.trim() === '') {
            isValid = false;
            errorElement.textContent = `${labelText} is required or invalid.`;
            input.parentElement.appendChild(errorElement);
        }
    });

    return isValid;
}

function nextStep(step) {
    // Validation for specific steps
    if (step === 1 && !validateStep(0)) return;
    if (step === 2 && !validateStep(1)) return;

    const formData = new FormData(document.getElementById('update-form'));

	if (step === 1) {
		fetch(site_url + `leads/leads_check_step${step}`, {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			if (data.status === 'SUCCESS') {
				currentStep++;
				showStep(currentStep);
			} else {
				document.querySelector(`.error-step${step}`).textContent = data.message;
			}
		})
		.catch(error => {
			console.error('Error:', error);
			alert('There was an error processing your request.');
		});
	}
	else
	{
		currentStep++;
        showStep(currentStep);
	}
}

function prevStep(step) {
    currentStep = step - 1;
    showStep(currentStep);
}

showStep(currentStep);

$(document).ready(function(e) {
    $('.allocation').trigger('change');
});
</script>
