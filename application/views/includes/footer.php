
        <footer class="footer text-right">
            <p><?php echo copyrights(); ?></p>
        </footer>

    </div>


    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->

</div>
<!-- END wrapper -->

<!-- Custom modal to be used in javascript BuildModalDialog function -->
<div id="jquery_custom_model" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">&nbsp;</h4>
			</div>
			<div class="modal-body text-center">Loading...</div>
			<div class="modal-footer text-center">
				<button id="save_button"   type="button" data-dismiss="modal" class="btn btn-danger">{% blocktrans %}Save{% endblocktrans %}</button> &nbsp; &nbsp;
				<button id="cancel_button" type="button" data-dismiss="modal" class="btn btn-primary">{% blocktrans %}Close{% endblocktrans %}</button>
			</div>
		</div>
	</div>
</div>

<!--Log History-->
<div class="modal fade" id="logHistory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        	<div class="modal-header">
                <h4 class="modal-title">Log History</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
            	<div id="log-history">

                </div>
            </div>
        </div>
    </div>
</div>

<!--Delete-->
<div class="modal fade" id="deleteModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Records</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Are you sure? Do you want to delete the selected records?</p>
            </div>
            <div class="modal-footer text-right">
                <button type="button" class="btn btn-danger waves-effect waves-light" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Scroll To Top -->
<div class="scroll-to-top"><span class="fa fa-arrow-up"></span></div>

<!--jQuery-->
<script src="<?php echo site_url('assets/js/moment.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/popper.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/metisMenu.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/slimscroll.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/datepicker.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/validation.js'); ?>"></script>
<script src="<?php echo site_url("assets/plugins/notify/notify.js"); ?>"></script>
<script src="<?php echo site_url('assets/js/app.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/fancybox.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/daterangepicker.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/tooltipster.bundle.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/scripts.js'); ?>"></script>
<script>
//Installment Form
function installmentSubmitForm() {
    var form = '#installment-form';
    $(form).validate({
        onsubmit: false
    });

    if (!$(form).valid()) {
        return false;
    }
	
    var formData = new FormData($(form)[0]);
    
    $.ajax({
        url: site_url + 'booking/booking_installment_setup',
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            if (result.msg == "SUCCESS") {
                $('#installmentModal').modal('hide');
                $(form)[0].reset();
				location.reload();
            } else {
                alert(result.data);
            }
        },
    });
}

//Reference Change
$(document).ready(function() {
    // Bind change event to the select element
    $('#payment_method').change(function() {
        // Get the selected option value
        var payment_method = $(this).val();

        // Check if payment method is 'Cash' (value 2) or empty
        if (payment_method == 2 || payment_method == '') {
            $('#reference').hide();
            $('#reference input').prop('disabled', true);
            $('form [name="proof_image"]').addClass('required');
        }
		else if (payment_method == 3) {
            $('#reference').show();
            $('#reference input').prop('disabled', false);
            $('#reference input').removeClass('required');
            $('form [name="proof_image"]').removeClass('required');
        } else {
            $('#reference').show();
            $('#reference input').prop('disabled', false);
            $('form [name="proof_image"]').addClass('required');
        }
    });
});
</script>

</body>
</html>
