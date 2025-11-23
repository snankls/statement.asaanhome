<div class="card">
    <div class="card-block">
        <div class="account-box">
            <div class="card-box p-5">
                <h2 class="text-uppercase text-center pb-4">
                    <img src="<?php echo site_url('assets/images/login-logo.png'); ?>" alt="Logo" height="30">
                </h2>

                <form method="post" action="<?php echo site_url('process-login'); ?>">
                    <div class="form-group m-b-20 row">
                        <div class="col-12">
                            <label>Username / Email Address</label>
                            <input type="text" class="form-control required" name="email">
                        </div>
                    </div>
                    <div class="form-group row m-b-20">
                        <div class="col-12">
                            <label>Password</label>
                            <input type="password" class="form-control required" name="password">
                        </div>
                    </div>
                    <div class="row text-center m-t-10">
                        <div class="col-12">
                            <button type="submit" class="btn btn-block btn-custom waves-effect waves-light">Sign In</button>
                        </div>
                    </div><br/>
                    <div id="login-error"><?php echo $this->session->flashdata("error"); ?></div>
                </form>
            </div>
        </div>
    </div>
</div>
