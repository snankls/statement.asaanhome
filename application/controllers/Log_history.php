<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Log_history extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
	}
	
	//Log History List
	public function log_history()
	{
		check_login('yes');
		$db_table = request_var('name', '');
		$id = request_var('id', '');
		
		$data['record_list'] = $this->log_history->log_list($db_table, $id);
		$this->load->view('log-history/log-history', $data);
	}
}
?>
