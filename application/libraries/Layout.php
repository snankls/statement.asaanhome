<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Layout {

	public function __construct($params)
	{
		//parent::__construct();
		$CI =& get_instance();
		
		$CI->load->model('crud_model','crud');
		$current_user_id = $CI->session->userdata('user_id');
		$current_user_id = isset($current_user_id) ? $current_user_id : '';
		$params['current_user_id'] = $current_user_id;
		
		$source_cd = $CI->crud->single($current_user_id, 'users', 'user_id');
		$params['show_source_cd'] = isset($source_cd->SourceCD) ? $source_cd->SourceCD : '';
		$source_cd = $params['show_source_cd'];
		
		$params['current_role_id'] = $CI->session->userdata('role_id');
		
		$params['is_admin'] = is_admin_logged_in ( array(1) );
		
		//Page Setting
		if (!isset($params['page_name']))
		$params['page_name'] = '';
		
		$CI->load->model('frontend_model', 'frontend');
		$params['navigation'] = $CI->load->view('includes/navigation', $params, true);
		
		//Page Header
		if ($params['page'] == 'login' or $params['page'] == 'logout' or $params['page'] == 'users/forget-password' or $params['page'] == 'users/recover-password')
			$CI->load->view('includes/header-login', $params);
		else
			$CI->load->view('includes/header', $params);

		//Page Content
		$CI->load->view($params['page'], $params);
		
		//Page Footer
		if ($params['page'] == 'login' or $params['page'] == 'logout' or $params['page'] == 'users/forget-password' or $params['page'] == 'users/recover-password')
			$CI->load->view('includes/footer-login', $params);
		else
			$CI->load->view('includes/footer', $params);
	}
}
