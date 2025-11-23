<script>
//Add Popup Followup
function add_followup(id) {
    var o = new Object();
	o.lead_id = id;
	
	$('#followup-list').html(loader_big());
	$("#followup-list").load(site_url + "leads/leads_followup_list/", o);
}

//Edit Record
function edit_record(obj) {
	let row = $(obj).closest('tr');
	
	var name = row.find('.action-details [name="name"]').val();
	var lead_id = row.find('.action-details [name="lead_id"]').val();
	var country_code = row.find('.action-details [name="country_code"]').val();
	var phone = row.find('.action-details [name="phone_number"]').val();
	var email = row.find('.action-details [name="email_address"]').val();
	var city = row.find('.action-details [name="city"]').val();
	
	$('#editLeadModal [name="update_id"]').val(lead_id);
	$('#editLeadModal [name="name"]').val(name);
    $('#editLeadModal [name="country"]').val(country_code).trigger('change');
	$('#editLeadModal [name="phone_number"]').val(phone);
	$('#editLeadModal [name="email_address"]').val(email);
	$('#editLeadModal [name="city"]').val(city);
	
	$('#editLeadModal').modal('show');
}

//Add Popup Followup
function add_receipt(id, phone) {
    var o = new Object();
	o.lead_id = id;
	o.phone = phone;
	
	$('#receipt-list').html(loader_big());
	$("#receipt-list").load(site_url + "leads/leads_add_receipt/", o);
}

//Update Lead
function submitForm() {
    var form = '#update-form';
    $(form).validate({
        onsubmit: false
    });

    if (!$(form).valid()) {
        return false;
    }
	
    var formData = new FormData($(form)[0]);
    
    $.ajax({
        url: site_url + 'leads/leads_step1_setup_post',
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            if (result.msg == "SUCCESS") {
                $('#editLeadModal').modal('hide');
				$('#leads-table-list').DataTable().ajax.reload();
            } else {
				$('#form-error-message').text(result.data);
            }
        },
    });
}

//submit followup leads
function follow_up() {
    var form = '#followup-form';
	var button = $(form).find('button[type="button"]');
	
    $(form).validate({
        onsubmit: false
    });

    if (!$(form).valid()) {
        return false;
    }
	
	button.attr('disabled', true);
    
    var formData = new FormData($(form)[0]);
    
    $.ajax({
        url: site_url + 'leads/leads_followup_setup_post',
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(result) {
            if (result.message === "SUCCESS") {
				$('#followupModal').modal('hide');
				$('#leads-table-list').DataTable().ajax.reload();
            } else {
                // Handle error message from server
                console.log(result.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX error:", status, error);
            console.error("Response Text:", xhr.responseText);
        }
    });
}
	
$(document).ready(function(e) {
	//Tooltip
	$('[data-toggle="tooltip"]').tooltipster();
});
</script>