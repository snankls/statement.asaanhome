<?php 
function is_logged_in()
{
	$CI =& get_instance();
    $return = 'no';
	
	if ( $CI->session->userdata('user_id') )
	$return = 'yes';
    return $return;
}

function check_login( $ajax = 'no' )
{
	$CI =& get_instance();
	
	if ( is_logged_in () == 'no' )
	{
		if ( $ajax == 'yes' )
		{
			out ('AJAX_LOGIN', 'Please login');
			exit();
		}
		else
		{
			$CI->session->set_userdata('redirect_back', current_url() );
			redirect( site_url().'login', 'location' );
			exit();
		}
		
	}
}

function is_admin_logged_in( $admin_roles = array(1) )
{
	$CI =& get_instance();
    $return = 'no';
	
	if ( $CI->session->userdata('email') and in_array($CI->session->userdata('role_id'), $admin_roles) )
		$return = 'yes';

    return $return;
}

function check_admin_login ( $ajax = 'no', $admin_roles = array(1) )
{
	if ( is_admin_logged_in ( $admin_roles ) == 'no' )
	{
		if ( $ajax == 'yes' )
		{
			redirect( site_url().'dashboard', 'location' );
			exit();
		}
		else
		{
			redirect( site_url().'dashboard', 'location' );
			exit();
		}
	}
}

function check_viewer_login( $viewer_roles = array(6) )
{
	$CI =& get_instance();
    
	$current_role_id = $CI->session->userdata('role_id');
	
	if ($current_role_id == 6) {
		redirect('dashboard');
	}
}

function restrict_role($roles_ids = array())
{
    $CI =& get_instance();
    
    $current_role_id = $CI->session->userdata('role_id');
	if (is_array($roles_ids[0])) {
        $roles_ids = array_merge(...$roles_ids);
    }
	
    if (in_array($current_role_id, $roles_ids)) {
        redirect('dashboard');
    }
}

?>