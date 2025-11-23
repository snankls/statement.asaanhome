<div class="content">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <!-- meta -->
                <div class="profile-user-box card bg-custom">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <span class="float-left mr-2"><?php echo get_image($record_list->image, 'users', 60, 'avatar-xl rounded-circle') ?></span>
                                <div class="media-body text-white">
                                    <h4 class="mt-1 mb-1 text-white font-18"><?php echo $record_list->fullname; ?></h4>
                                    <p class="font-15 mb-0"> <?php if($current_role_id == 1){ ?>(Password: <?php echo $record_list->password; ?>)<?php } ?></p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                            	<?php if ($is_admin == 'yes') { ?>
                                <div class="crud_operations text-right">
                                    <div class="view-actions-container"><i class="fa fa-spinner fa-spin"></i></div>
                                    <script type="text/javascript">CF.GetViewActions("", "<?=$record_list->user_id?>", "user");</script>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ meta -->
            </div>
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-xl-12">
                <!-- Personal-Information -->
				<div class="card-box">
					<h4 class="header-title">Personal Information</h4>
					<div class="panel-body">
                    	<?php $project_name = array();
						foreach($project_list as $data) {
							$project_name[] = $data->project_name;
						} ?>
                        <table class="table table-hover mb-0">
                            <tbody>
                                <tr>
                                    <td><strong>Full Name</strong></td>
                                    <td><?php echo $record_list->fullname; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Username</strong></td>
                                    <td><?php echo $record_list->username; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Phone Number</strong></td>
                                    <td><?php echo $record_list->mobile; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Email Address</strong></td>
                                    <td><?php echo $record_list->email; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Role</strong></td>
                                    <td><?php echo $record_list->role; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Projects</strong></td>
                                    <td><?php echo implode(', ', $project_name); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td><?php echo enable_disable($record_list->status); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Address</strong></td>
                                    <td><?php echo $record_list->address; ?></td>
                                </tr>
                            </tbody>
                        </table>
					</div>
				</div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div> <!-- end container-fluid -->
</div> <!-- end content -->
