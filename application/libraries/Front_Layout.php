<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Front_Layout {

	public function __construct($params)
	{
		//parent::__construct();
		$CI =& get_instance();
		
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
