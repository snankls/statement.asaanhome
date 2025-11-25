<ul class="metismenu" id="side-menu">
    <li><a href="<?php echo site_url('dashboard'); ?>"><i class="fe-airplay"></i> <span> Dashboard </span></a></li>
    
    <?php if (!in_array($current_role_id, CRM_ROLE)) { ?>
    <li><a href="javascript:;"><i class="fe-command"></i> <span> Projects </span> <span class="menu-arrow"></span></a>
        <ul class="nav-second-level" aria-expanded="false">
            <li><a href="<?php echo site_url('project'); ?>"><span> Project </span></a></li>
            <li><a href="<?php echo site_url('inventory'); ?>"><span> Inventory </span></a></li>
            <li><a href="<?php echo site_url('booking'); ?>"><span> Booking </span></a></li>
            <!-- <li><a href="javascript:;"><span> Booking </span> <span class="menu-arrow"></span></a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li><a href="<?php //echo site_url('booking'); ?>">Booking</a></li>
                    <li><a href="<?php //echo site_url('booking/search'); ?>">Booking Search</a></li>
                </ul>
            </li> -->
            <li><a href="<?php echo site_url('collection'); ?>"><span> Collection </span></a></li>
        </ul>
    </li>
    
    <li><a href="javascript:;"><i class="fe-bar-chart-line"></i> <span> Finance </span> <span class="menu-arrow"></span></a>
        <ul class="nav-second-level" aria-expanded="false">
            <li><a href="<?php echo site_url('voucher'); ?>">Voucher</a></li>
            <?php if ($current_role_id != 6) { ?>
            <li><a href="javascript:;"><span> Chart of Accounts </span> <span class="menu-arrow"></span></a>
                <ul class="nav-third-level">
                    <li><a href="<?php echo site_url('chart-of-accounts/level-1'); ?>">COA Level 1</a></li>
                    <li><a href="<?php echo site_url('chart-of-accounts/level-2'); ?>">COA Level 2</a></li>
                    <li><a href="<?php echo site_url('chart-of-accounts/level-3'); ?>">COA Level 3</a></li>
                    <li><a href="<?php echo site_url('chart-of-accounts/level-4'); ?>">COA Level 4</a></li>
                </ul>
            </li>
            <?php } ?>
            <li><a href="javascript:;"><span> Reports </span> <span class="menu-arrow"></span></a>
                <ul class="nav-third-level">
                    <li><a href="<?php echo site_url('reports/chart-of-accounts'); ?>">Chart of Accounts</a></li>
                    <li><a href="<?php echo site_url('reports/finance-ledger'); ?>">Finance Ledger</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <?php } ?>
    
    <?php if (!in_array($current_role_id, PROJECT_FINANCE_ROLE)) { ?>
    <li><a href="javascript:;"><i class="fe-file-text"></i> <span> CRM </span> <span class="menu-arrow"></span></a>
        <ul class="nav-second-level" aria-expanded="false">
            <li><a href="<?php echo site_url('leads'); ?>"><span> Leads </span></a></li>
            <li><a href="<?php echo site_url('leads/todo-list'); ?>"><span> To-do List </span></a></li>
            <li><a href="<?php echo site_url('leads/receipt'); ?>"><span> Receipt </span></a></li>
            <li>
            	<a href="javascript:;"><span> Reports </span> <span class="menu-arrow"></span></a>
            	<ul class="nav-third-level">
                    <li><a href="<?php echo site_url('reports/activity-report'); ?>">Activity Report</a></li>
                    <li><a href="<?php echo site_url('reports/kpi-report'); ?>">KPI Report</a></li>
                </ul>
            </li>
            <?php if ( $is_admin == "yes" ){ ?>
            <li><a href="<?php echo site_url('leads/import'); ?>"><span> Import Leads </span></a></li>
            <li><a href="<?php echo site_url('teams'); ?>"><span> Teams </span></a></li>
			<?php } ?>
        </ul>
    </li>
    <?php } ?>
    
    <?php if ($current_role_id != 6) { ?>
    <li><a href="javascript:;"><i class="fe-book-open"></i> <span> Attendance </span> <span class="menu-arrow"></span></a>
    	<ul class="nav-second-level" aria-expanded="false">
    		<li><a href="<?php echo site_url('attendance'); ?>">Attendance</a></li>
            <li>
            	<a href="javascript:;"><span> Reports </span> <span class="menu-arrow"></span></a>
            	<ul class="nav-third-level">
                    <li><a href="<?php echo site_url('attendance/individual'); ?>">Individual</a></li>
                    <li><a href="<?php echo site_url('attendance/group'); ?>">Group</a></li>
                </ul>
            </li>
            <?php if ( $is_admin == "yes" ){ ?>
    		<li><a href="<?php echo site_url('branches'); ?>">Branches</a></li>
            <?php } ?>
        </ul>
    </li>
    <?php } ?>
    
    <?php if ( $is_admin == "yes" ){ ?>
    <li><a href="javascript:;"><i class="fe-users"></i> <span> Users </span> <span class="menu-arrow"></span></a>
    	<ul class="nav-second-level" aria-expanded="false">
    		<li><a href="<?php echo site_url('user'); ?>">Users</a></li>
    		<li><a href="<?php echo site_url('user/change-password'); ?>">Change Password</a></li>
        </ul>
    </li>
    <?php } ?>
</ul>
