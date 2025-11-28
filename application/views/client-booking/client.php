<div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
    <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
        <div class="col-xl-4 col-lg-5 col-md-6">
            <div class="card overflow-hidden h-100 p-xxl-4 p-3 mb-0">
                <div class="auth-brand mb-4 text-center">
                    <img src="assets/images/logo.png" alt="logo light" height="26" class="logo-light">
                </div>

                <p class="text-muted mb-4 text-center">Enter info and get your statement.</p>

                <form class="text-start mb-3" action="<?php echo base_url('client/generate_statement'); ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Registration #</label>
                        <input type="text" name="registration" class="form-control required">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">CNIC #</label>
                        <input type="text" name="cnic" class="form-control client-masking required" placeholder="_____-______-_" maxlength="15" value="<?php echo @$record->cnic; ?>">
                    </div>

                    <div class="d-grid text-center">
                        <button class="btn btn-primary fw-semibold" type="submit">Generate Statement</button>
                    </div>
                </form>

                <p class="mt-auto mb-0 text-center">
                    <?php echo copyrights(); ?>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
if($('.client-masking').length){
    $('.client-masking').mask("99999-9999999-9");
}
</script>
