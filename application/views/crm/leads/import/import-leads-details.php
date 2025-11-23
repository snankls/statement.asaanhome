<!--Followup List-->
<div class="modal fade" id="followupModal" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
            <div id="followup-list"></div>
		</div>
	</div>
</div>

<!--Edit Leads-->
<div class="modal fade" id="editLeadModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Edit Temp Leads</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form id="update-form" class="form-horizontal Form" role="form">
                	<input type="hidden" name="update_id" value="" />
                    <div class="form-group">
                        <label class="col-form-label">Name <span class="error-message">*</span></label>
                        <input type="text" name="name" class="form-control" value="" />
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Country <span class="error-message">*</span></label>
                        <select name="country" class="form-control select2 required">
                            <option value="">Select One</option>
                            <?php foreach(country_list() as $k => $v){ ?>
                            <option value="<?=$k?>"><?=$v?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Phone Number <span class="error-message">*</span></label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control phone-masking" placeholder="3001234567" maxlength="10" />
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Email Address <span class="error-message">*</span></label>
                        <input type="email" name="email_address" id="email_address" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">City <span class="error-message">*</span></label>
                        <input type="text" name="city" id="city" class="form-control" />
                    </div>
                    <div id="form-error-message" class="error-message"></div>
				</form>
			</div>
			<div class="modal-footer text-right">
				<button type="button" class="btn btn-custom waves-effect waves-light" onclick="submitForm();">Save</button>
			</div>
		</div>
	</div>
</div>

<script>
//Edit Record
function edit_record(obj) {
	let row = $(obj).closest('tr');
	
	var lead_id = row.find('td:eq(0) [name="lead_id"]').val();
	var phone = row.find('td:eq(0) [name="phone_number"]').val();
	var email = row.find('td:eq(0) [name="email_address"]').val();
	var city = row.find('td:eq(0) [name="city"]').val();
	var country_code = row.find('td:eq(0) [name="country_code"]').val();
	var name = row.find('td:eq(4)').text();
	
	$('#editLeadModal [name="update_id"]').val(lead_id);
	$('#editLeadModal [name="name"]').val(name);
	$('#editLeadModal [name="phone_number"]').val(phone);
	$('#editLeadModal [name="email_address"]').val(email);
	$('#editLeadModal [name="city"]').val(city);
    $('#editLeadModal [name="country"]').val(country_code).trigger('change');
	$('#editLeadModal').modal('show');
}

//Add Popup Followup
function add_followup(id) {
    var o = new Object();
	o.lead_id = id;
	
	$('#followup-list').html(loader_big());
	$("#followup-list").load(site_url + "import/import_leads_followup_list/", o);
}
</script>