<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <form id="change-password-form" class="form-horizontal">
                        <div class="form-group row m-b-20">
                            <div class="col-5">
                                <label>Current Password</label>
                                <input type="password" class="form-control" id="current-password" required>
                            </div>
                        </div>
                        
                        <div class="form-group row m-b-20">
                            <div class="col-5">
                                <label>New Password</label>
                                <input type="password" class="form-control" id="new-password" required>
                                <small class="form-text text-muted">
                                    <i class="fa fa-info-circle"></i> Password requirements:
                                    <ul class="pl-3 mb-0">
                                        <li>At least 6 characters long</li>
                                        <li>Contains at least one letter (a-z, A-Z)</li>
                                        <li>Contains at least one number (0-9)</li>
                                        <li>Special characters are allowed but not required</li>
                                    </ul>
                                </small>
                            </div>
                        </div>
                        
                        <div class="form-group row m-b-20">
                            <div class="col-5">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control" id="confirm-password" required>
                            </div>
                        </div>
                        
                        <div class="form-group row text-center m-t-10">
                            <div class="col-5">
                                <button type="button" class="btn btn-block btn-custom waves-effect waves-light" onClick="add_record();">Reset Password</button>
                            </div>
                        </div>
                        
                        <div class="form-group row m-t-10">
                            <div class="col-12">
                                <div id="response"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- container -->
</div>
<!-- content -->

<script>
function validatePassword(password) {
    // Check if password meets requirements: at least 6 chars, at least one letter and one number
    const hasMinimumLength = password.length >= 6;
    const hasLetter = /[a-zA-Z]/.test(password);
    const hasNumber = /\d/.test(password);
    
    return hasMinimumLength && hasLetter && hasNumber;
}

function getPasswordRequirements() {
    return '<div class="alert alert-info small">' +
           '<strong>Password Requirements:</strong><br>' +
           '✓ At least 6 characters long<br>' +
           '✓ Contains at least one letter (a-z, A-Z)<br>' +
           '✓ Contains at least one number (0-9)<br>' +
           '✓ Special characters are allowed' +
           '</div>';
}

function add_record(){
    var o = new Object();
    o.current_password = $('#current-password').val();
    o.new_password = $('#new-password').val();
    o.confirm_password = $('#confirm-password').val();
    
    // Clear previous responses
    $('#response').html('');
    
    // Client-side validation
    if (!o.current_password) {
        $('#response').html('<div class="alert alert-danger">Please enter your current password.</div>');
        return;
    }
    
    if (!o.new_password) {
        $('#response').html('<div class="alert alert-danger">Please enter a new password.</div>');
        return;
    }
    
    if (!validatePassword(o.new_password)) {
        $('#response').html('<div class="alert alert-danger">' +
                           '<strong>Invalid Password Format:</strong><br>' +
                           'Please ensure your new password meets the following requirements:' +
                           '<ul>' +
                           '<li>At least 6 characters long</li>' +
                           '<li>Contains at least one letter (a-z, A-Z)</li>' +
                           '<li>Contains at least one number (0-9)</li>' +
                           '</ul>' +
                           '</div>');
        return;
    }
    
    if (o.new_password !== o.confirm_password) {
        $('#response').html('<div class="alert alert-danger">New password and confirm password do not match. Please enter the same password in both fields.</div>');
        return;
    }
    
    $('#response').html(loader_small());
    
    $.post(site_url + 'user/save_change_password/', o, function(result) {
        if(result.msg == "SUCCESS") {
            $('#response').html('<div class="alert alert-success">' + result.data + '</div>');
            $("#change-password-form")[0].reset();
        } else {
            $('#response').html('<div class="alert alert-danger">' + result.data + '</div>');
        }
    }, "json").fail(function() {
        $('#response').html('<div class="alert alert-danger">An error occurred while processing your request. Please try again.</div>');
    });
}

// Real-time password validation
$(document).ready(function() {
    $('#new-password').on('blur', function() {
        var password = $(this).val();
        if (password && !validatePassword(password)) {
            $('#response').html(getPasswordRequirements());
        }
    });
});
</script>