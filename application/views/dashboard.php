<?php if ($current_role_id == 1) { ?>
<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product-dashboard product-dashboard-stats">
                            <div class="product-dashboard-header product-dashboard-header-icon">
                                <div class="product-dashboard-icon color-orange">
                                    <i class="fa fa-th"></i>
                                </div>
                                <p class="product-dashboard-category">Total Projects</p>
                                <h3 class="product-dashboard-title"><small data-speed="2000" data-stop="<?php echo $total_projects; ?>">0</small></h3>
                            </div>
                            <div class="product-dashboard-footer">
                                <div class="stats">
                                    <a href="<?php echo site_url('project'); ?>">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product-dashboard product-dashboard-stats">
                            <div class="product-dashboard-header product-dashboard-header-icon">
                                <div class="product-dashboard-icon color-purple">
                                    <i class="fa fa-th"></i>
                                </div>
                                <p class="product-dashboard-category">Total Inventory</p>
                                <h3 class="product-dashboard-title"><small data-speed="2000" data-stop="<?php echo $total_inventory; ?>">0</small></h3>
                            </div>
                            <div class="product-dashboard-footer">
                                <div class="stats">
                                    <a href="<?php echo site_url('inventory'); ?>">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product-dashboard product-dashboard-stats">
                            <div class="product-dashboard-header product-dashboard-header-icon">
                                <div class="product-dashboard-icon color-green">
                                    <i class="fa fa-th"></i>
                                </div>
                                <p class="product-dashboard-category">Total Bookings</p>
                                <h3 class="product-dashboard-title"><small data-speed="2000" data-stop="<?php echo $total_booking; ?>">0</small></h3>
                            </div>
                            <div class="product-dashboard-footer">
                                <div class="stats">
                                    <a href="<?php echo site_url('booking'); ?>">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product-dashboard product-dashboard-stats">
                            <div class="product-dashboard-header product-dashboard-header-icon">
                                <div class="product-dashboard-icon color-red">
                                    <i class="fa fa-th"></i>
                                </div>
                                <p class="product-dashboard-category">Total Collection</p>
                                <h3 class="product-dashboard-title"><small data-speed="2000" data-stop="<?php echo $total_collection; ?>">0</small></h3>
                            </div>
                            <div class="product-dashboard-footer">
                                <div class="stats">
                                    <a href="<?php echo site_url('collection'); ?>">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product-dashboard product-dashboard-stats">
                            <div class="product-dashboard-header product-dashboard-header-icon">
                                <div class="product-dashboard-icon color-teal">
                                    <i class="fa fa-th"></i>
                                </div>
                                <p class="product-dashboard-category">Total Voucher</p>
                                <h3 class="product-dashboard-title"><small data-speed="2000" data-stop="<?php echo $total_voucher; ?>">0</small></h3>
                            </div>
                            <div class="product-dashboard-footer">
                                <div class="stats">
                                    <a href="<?php echo site_url('voucher'); ?>">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product-dashboard product-dashboard-stats">
                            <div class="product-dashboard-header product-dashboard-header-icon">
                                <div class="product-dashboard-icon color-pink">
                                    <i class="fa fa-th"></i>
                                </div>
                                <p class="product-dashboard-category">Total Admin</p>
                                <h3 class="product-dashboard-title"><small data-speed="2000" data-stop="<?php echo $total_admin; ?>">0</small></h3>
                            </div>
                            <div class="product-dashboard-footer">
                                <div class="stats">
                                    <a href="<?php echo site_url('user'); ?>">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product-dashboard product-dashboard-stats">
                            <div class="product-dashboard-header product-dashboard-header-icon">
                                <div class="product-dashboard-icon color-amber">
                                    <i class="fa fa-th"></i>
                                </div>
                                <p class="product-dashboard-category">Total Users</p>
                                <h3 class="product-dashboard-title"><small data-speed="2000" data-stop="<?php echo $total_users; ?>">0</small></h3>
                            </div>
                            <div class="product-dashboard-footer">
                                <div class="stats">
                                    <a href="<?php echo site_url('user'); ?>">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product-dashboard product-dashboard-stats">
                            <div class="product-dashboard-header product-dashboard-header-icon">
                                <div class="product-dashboard-icon color-grey">
                                    <i class="fa fa-th"></i>
                                </div>
                                <p class="product-dashboard-category">Total Viewer</p>
                                <h3 class="product-dashboard-title"><small data-speed="2000" data-stop="<?php echo $total_viewer; ?>">0</small></h3>
                            </div>
                            <div class="product-dashboard-footer">
                                <div class="stats">
                                    <a href="<?php echo site_url('user'); ?>">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product-dashboard product-dashboard-stats">
                            <div class="product-dashboard-header product-dashboard-header-icon">
                                <div class="product-dashboard-icon color-indigo">
                                    <i class="fa fa-th"></i>
                                </div>
                                <p class="product-dashboard-category">Total Manager</p>
                                <h3 class="product-dashboard-title"><small data-speed="2000" data-stop="<?php echo $total_manager; ?>">0</small></h3>
                            </div>
                            <div class="product-dashboard-footer">
                                <div class="stats">
                                    <a href="<?php echo site_url('user'); ?>">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product-dashboard product-dashboard-stats">
                            <div class="product-dashboard-header product-dashboard-header-icon">
                                <div class="product-dashboard-icon color-blue">
                                    <i class="fa fa-th"></i>
                                </div>
                                <p class="product-dashboard-category">Total Individual</p>
                                <h3 class="product-dashboard-title"><small data-speed="2000" data-stop="<?php echo $total_individual; ?>">0</small></h3>
                            </div>
                            <div class="product-dashboard-footer">
                                <div class="stats">
                                    <a href="<?php echo site_url('user'); ?>">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
        <!-- end row -->
    </div> <!-- container -->
</div> <!-- content -->
<?php } ?>

<?php if ($current_role_id == 7 or $current_role_id == 8) {
    $today_follow_up_date = date('M d, Y') . ' - ' . date('M d, Y');
    
    if($current_role_id == 8)
    {
        $potential_leads_url = site_url("leads/todo-list?user_id=$current_user_id&lead_status=3");
        $closing_leads_url = site_url("leads/todo-list?user_id=$current_user_id&lead_status=4");
        $due_overdue_url = site_url("leads/todo-list?user_id=$current_user_id&task_performed=5");
        $upcoming_meetings_url = site_url("leads/todo-list?user_id=$current_user_id&next_task=2");
        $next_followup_date_url = site_url("leads/todo-list?user_id=$current_user_id&next_followup_date=$today_follow_up_date");
    }
    else {
        $potential_leads_url = site_url("leads/todo-list?lead_status=3");
        $closing_leads_url = site_url("leads/todo-list?lead_status=4");
        $due_overdue_url = site_url('leads/todo-list?task_performed=5');
        $upcoming_meetings_url = site_url('leads/todo-list?next_task=2');
        $next_followup_date_url = site_url("leads/todo-list?next_followup_date=$today_follow_up_date");
    }
?>
<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product-dashboard product-dashboard-stats">
                            <div class="product-dashboard-header product-dashboard-header-icon">
                                <div class="product-dashboard-icon color-orange">
                                    <i class="fa fa-th"></i>
                                </div>
                                <p class="product-dashboard-category">Total Leads</p>
                                <h3 class="product-dashboard-title"><small data-speed="2000" data-stop="<?php echo $total_leads; ?>">0</small></h3>
                            </div>
                            <div class="product-dashboard-footer">
                                <div class="stats">
                                    <a href="<?php echo site_url('leads'); ?>">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product-dashboard product-dashboard-stats">
                            <div class="product-dashboard-header product-dashboard-header-icon">
                                <div class="product-dashboard-icon color-purple">
                                    <i class="fa fa-th"></i>
                                </div>
                                <p class="product-dashboard-category">Potential Leads</p>
                                <h3 class="product-dashboard-title"><small data-speed="2000" data-stop="<?php echo $potential_leeds; ?>">0</small></h3>
                            </div>
                            <div class="product-dashboard-footer">
                                <div class="stats">
                                    <a href="<?php echo $potential_leads_url; ?>">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product-dashboard product-dashboard-stats">
                            <div class="product-dashboard-header product-dashboard-header-icon">
                                <div class="product-dashboard-icon color-green">
                                    <i class="fa fa-th"></i>
                                </div>
                                <p class="product-dashboard-category">Closing Leads</p>
                                <h3 class="product-dashboard-title"><small data-speed="2000" data-stop="<?php echo $closing_leeds; ?>">0</small></h3>
                            </div>
                            <div class="product-dashboard-footer">
                                <div class="stats">
                                    <a href="<?php echo $closing_leads_url; ?>">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product-dashboard product-dashboard-stats">
                            <div class="product-dashboard-header product-dashboard-header-icon">
                                <div class="product-dashboard-icon color-red">
                                    <i class="fa fa-th"></i>
                                </div>
                                <p class="product-dashboard-category">Due & Overdue Meetings</p>
                                <h3 class="product-dashboard-title"><small data-speed="2000" data-stop="<?php echo $due_overdue_meeting; ?>">0</small></h3>
                            </div>
                            <div class="product-dashboard-footer">
                                <div class="stats">
                                    <a href="<?php echo $due_overdue_url; ?>">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product-dashboard product-dashboard-stats">
                            <div class="product-dashboard-header product-dashboard-header-icon">
                                <div class="product-dashboard-icon color-teal">
                                    <i class="fa fa-th"></i>
                                </div>
                                <p class="product-dashboard-category">Upcoming Meetings</p>
                                <h3 class="product-dashboard-title"><small data-speed="2000" data-stop="<?php echo $upcoming_meeting; ?>">0</small></h3>
                            </div>
                            <div class="product-dashboard-footer">
                                <div class="stats">
                                    <a href="<?php echo $upcoming_meetings_url; ?>">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product-dashboard product-dashboard-stats">
                            <div class="product-dashboard-header product-dashboard-header-icon">
                                <div class="product-dashboard-icon color-pink">
                                    <i class="fa fa-th"></i>
                                </div>
                                <p class="product-dashboard-category">Today's Follow-ups</p>
                                <h3 class="product-dashboard-title"><small data-speed="2000" data-stop="<?php echo $today_follow_ups; ?>">0</small></h3>
                            </div>
                            <div class="product-dashboard-footer">
                                <div class="stats">
                                    <a href="<?php echo $next_followup_date_url; ?>">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product-dashboard product-dashboard-stats">
                            <div class="product-dashboard-header product-dashboard-header-icon">
                                <div class="product-dashboard-icon color-blue">
                                    <i class="fa fa-th"></i>
                                </div>
                                <p class="product-dashboard-category">Todo List</p>
                                <h3 class="product-dashboard-title"><small data-speed="2000" data-stop="<?php echo $todo_list; ?>">0</small></h3>
                            </div>
                            <div class="product-dashboard-footer">
                                <div class="stats">
                                    <a href="<?php echo site_url('leads/todo-list'); ?>">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
        <!-- end row -->
    </div> <!-- container -->
</div> <!-- content -->
<?php } ?>