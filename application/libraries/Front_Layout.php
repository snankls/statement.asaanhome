<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Front_Layout {

	public function __construct($params)
	{
		//parent::__construct();
		$CI =& get_instance();
		
		// $CI->load->model('crud_model','crud');
		// $current_user_id = $CI->session->userdata('user_id');
		// $current_user_id = isset($current_user_id) ? $current_user_id : '';
		//$params['current_user_id'] = $current_user_id;
		
		// $source_cd = $CI->crud->single($current_user_id, 'users', 'user_id');
		
		// $params['current_role_id'] = $CI->session->userdata('role_id');
		
		// $params['is_admin'] = is_admin_logged_in ( array(1) );
		
		//Page Setting
		if (!isset($params['page_name']))
		$params['page_name'] = '';
		
		//Page Header
		$CI->load->view('includes/header-front', $params);

		//Page Content
		$CI->load->view($params['page'], $params);
		
		//Page Footer
		$CI->load->view('includes/footer-front', $params);
	}
}
