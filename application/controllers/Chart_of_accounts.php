<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Chart_of_accounts extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->model('Chart_of_account_model', 'coa');
	}
	
	public function chart_of_accounts_level_1()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$session_project_id = $this->session->userdata('project_id');
		$data['project_list'] = $this->crud->all_list_sort('projects', 'project_name', $session_project_id);
		$data['title'] = "Chart of Accounts Level 1";
		$data['page'] = "finance/chart-of-accounts/coa-level-1";
		$this->load->library('Layout', $data);
	}
	
	public function chart_of_accounts_level_1_list($db_table='chart_of_accounts', $primary_id='chart_of_account_id', $conditions=array(), $return_table_data=False)
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$limit = $this->input->post('length');
    	$start = $this->input->post('start');
		
		$current_role_id = $this->session->userdata('role_id');
		$session_project_id = $this->session->userdata('project_id');
		$joins = array(
			0 => array(
				'table' => 'projects',
				'columns' => "projects.project_id = $db_table.project_id",
				'type' => 'left outer'
			),
			1 => array(
				'table' => 'users',
				'columns' => "users.user_id = $db_table.created_by_id",
				'type' => 'left outer'
			),
		);
		$table_columns = "$db_table.*, project_name, project_name, $db_table.created_on as create_date, users.fullname as user_name";
		$table_count = "COUNT(DISTINCT $db_table.$primary_id) as total_count";
		
		//Where Condition
		if($current_role_id != 1) {
			$project_ids = explode(',', $session_project_id);
			$conditions[] = array('operator' => 'WHERE_IN', 'column' => "$db_table.project_id", 'value' => $project_ids);
		}
		$conditions[] = array('operator' => 'WHERE', 'column' => "$db_table.account_level", 'value' => 1);
		$conditions[] = array('operator' => 'WHERE', 'column' => "$db_table.post_status", 'value' => 1);
		
		//Order By
		$conditions[] = array('operator' => 'ORDER_BY', 'column' => "$db_table.$primary_id", 'value' => 'desc');

		// Fetch records from the model (pass search_value)
		$table_data = $this->crud->dt_list($db_table, $table_columns, $joins, $conditions, $limit, $start);
		$total_records = $this->crud->dt_total_count($db_table, $table_count, $joins, $conditions, '', $limit, $start);
		
		$counter = $table_data['start'];
		foreach($table_data['data'] as $index => $rec)
		{
			$counter++;
			$table_data['data'][$index]->counter = $counter;
			$table_data['data'][$index]->DT_RowId = $rec->chart_of_account_id;
			$table_data['data'][$index]->checkbox = '<i class="fa fa-square-o"></i>';
			$table_data['data'][$index]->account_status = "<span class='waves-light btn-small ".($rec->status == 1 ? 'btn-success' : 'btn-danger') . "'>" . enable_disable($rec->status) . "</span>";
			$table_data['data'][$index]->create_date = $rec->user_name." <br /> ".date_only($rec->create_date);
			$rec->log_table = $db_table;
			$rec->log_id = $rec->chart_of_account_id;
			$rec->update_id = $rec->chart_of_account_id;
			$table_data['data'][$index]->actions = $this->parser->parse('ajax/table-list-action-popup', $rec, TRUE);
		}
		
		// JSON response to DataTables
		$response = [
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_records,
			"data" => $table_data,
		];

		echo json_encode($response);
	}
	
	public function coa_level_1_setup_post($db_table='chart_of_accounts', $primary_id='chart_of_account_id')
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$message = '';
		$project_id = request_var('project_id', '');
		$coa_1 = request_var('coa_1', '');
		$status = request_var('status', '');
		$update_id = request_var('update_id', '');
		$created_by_id = $this->session->userdata('user_id');
		$sort_order = chart_of_account_number(array('project_id' => $project_id, 'level_1_code' => '', 'level_2_code' => '', 'level_3_code' => '', 'level_1_code' => '', 'primary_key' => 'sort_order', 'number_size' => 2, 'account_level' => 1));

		$data = array(
			'account_title' => $coa_1,
			'status' => $status,
		);
		
		if($update_id == '')
		{
			$check_validation = $this->coa->check_coa_validation($project_id, $coa_1, 1);
			if (!empty($check_validation)) {
				echo json_encode(array(
					'success' => 0,
					'message' => 'Record already exists.',
					'reload_table' => 0,
					'close_modal' => 0,
				));
				return false;
			}
			
			$d = array(
				'project_id' => $project_id,
				'account_level' => 1,
				'created_by_id' => $created_by_id,
				'created_on' => time(),
			);
			$data = array_merge($data, $d);
			$id = $this->crud->add($data, $db_table);
			
			if($id)
			{
				$data = array(
					'level_1_code' => $sort_order,
					'sort_order' => $sort_order,
				);
				$this->crud->update($data, $id, $db_table, $primary_id);
			}

			$message = 'Record added successfully.';
		}
		else
		{
			$check_validation = $this->coa->check_coa_validation($project_id, $coa_1, 1, $update_id);
			if (!empty($check_validation)) {
				echo json_encode(array(
					'success' => 0,
					'message' => 'Record already exists.',
					'reload_table' => 0,
					'close_modal' => 0,
				));
				return false;
			}
			
			$d = array(
				'updated_by_id' => $created_by_id,
				'updated_on' => time(),
			);
			$data = array_merge($data, $d);
			$this->crud->update($data, $update_id, $db_table, $primary_id);

			//Add Log History
			log_history($db_table, $update_id);

			$message = 'Record updated successfully.';
		}
		
		echo json_encode( array(
			'success' => 1,
			'message' => $message,
			'reload_table' => 1,
			'close_modal' => 1,
		));
	}
	
	
	/***************************************************************/
		//Level 2
	/***************************************************************/
	
	public function chart_of_accounts_level_2()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$session_project_id = $this->session->userdata('project_id');
		$data['project_list'] = $this->crud->all_list_sort('projects', 'project_name', $session_project_id);
		$data['title'] = "Chart of Accounts Level 2";
		$data['page'] = "finance/chart-of-accounts/coa-level-2";
		$this->load->library('Layout', $data);
	}
	
	public function chart_of_accounts_level_2_list($db_table='chart_of_accounts', $primary_id='chart_of_account_id', $conditions=array(), $return_table_data=False)
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$limit = $this->input->post('length');
    	$start = $this->input->post('start');
		
		$current_role_id = $this->session->userdata('role_id');
		$session_project_id = $this->session->userdata('project_id');
		$joins = array(
			0 => array(
				'table' => 'projects',
				'columns' => "projects.project_id = $db_table.project_id",
				'type' => 'left outer'
			),
			1 => array(
				'table' => $db_table . ' AS parent',
				'columns' => "parent.chart_of_account_id = $db_table.parent_id",
				'type' => 'left outer'
			),
			2 => array(
				'table' => 'users',
				'columns' => "users.user_id = $db_table.created_by_id",
				'type' => 'left outer'
			),
		);
		$table_columns = "$db_table.*, project_name, $db_table.created_on as create_date, users.fullname as user_name, parent.account_title as parent_account_title";
		$table_count = "COUNT(DISTINCT $db_table.$primary_id) as total_count";
		
		//Where Condition
		if($current_role_id != 1) {
			$project_ids = explode(',', $session_project_id);
			$conditions[] = array('operator' => 'WHERE_IN', 'column' => "$db_table.project_id", 'value' => $project_ids);
		}
		$conditions[] = array('operator' => 'WHERE', 'column' => "$db_table.account_level", 'value' => 2);
		$conditions[] = array('operator' => 'WHERE', 'column' => "$db_table.post_status", 'value' => 1);
		
		//Order By
		$conditions[] = array('operator' => 'ORDER_BY', 'column' => "$db_table.$primary_id", 'value' => 'desc');

		// Fetch records from the model (pass search_value)
		$table_data = $this->crud->dt_list($db_table, $table_columns, $joins, $conditions, $limit, $start);
		$total_records = $this->crud->dt_total_count($db_table, $table_count, $joins, $conditions, '', $limit, $start);
		
		$counter = $table_data['start'];
		foreach($table_data['data'] as $index => $rec)
		{
			$counter++;
			$table_data['data'][$index]->counter = $counter;
			$table_data['data'][$index]->DT_RowId = $rec->chart_of_account_id;
			$table_data['data'][$index]->checkbox = '<i class="fa fa-square-o"></i>';
			$table_data['data'][$index]->parent_account = $rec->parent_account_title;
			$table_data['data'][$index]->account_status = "<span class='waves-light btn-small ".($rec->status == 1 ? 'btn-success' : 'btn-danger') . "'>" . enable_disable($rec->status) . "</span>";
			$table_data['data'][$index]->create_date = $rec->user_name." <br /> ".date_only($rec->create_date);
			$rec->log_table = $db_table;
			$rec->log_id = $rec->chart_of_account_id;
			$rec->update_id = $rec->chart_of_account_id;
			$table_data['data'][$index]->actions = $this->parser->parse('ajax/table-list-action-popup', $rec, TRUE);
		}
		
		// JSON response to DataTables
		$response = [
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_records,
			"data" => $table_data,
		];

		echo json_encode($response);
	}
	
	public function coa_level_2_setup_post($db_table='chart_of_accounts', $primary_id='chart_of_account_id')
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$message = '';
		$created_by_id = $this->session->userdata('user_id');
		
		$project_id = request_var('project_id', '');
		$coa_1 = request_var('coa_1', '');
		$coa_2 = request_var('coa_2', '');
		$level_1_code = request_var('level_1_code', '');
		$status = request_var('status', '');
		$update_id = request_var('update_id', '');
		
		$sort_order = chart_of_account_number(array('project_id' => $project_id, 'level_1_code' => $level_1_code, 'level_2_code' => '', 'level_3_code' => '', 'primary_key' => 'sort_order', 'number_size' => 3, 'account_level' => 2));
		
		$data = array(
			'project_id' => $project_id,
			'account_title' => $coa_2,
			'status' => $status,
		);
		
		if($update_id == '')
		{
			$check_validation = $this->coa->check_coa2_validation($project_id, $coa_1, $coa_2, 2);
			if (!empty($check_validation)) {
				echo json_encode(array(
					'success' => 0,
					'message' => 'Record already exists.',
					'reload_table' => 0,
					'close_modal' => 0,
				));
				return false;
			}
			
			$d = array(
				'parent_id' => $coa_1,
				'level_1_code' => $level_1_code,
				'level_2_code' => $sort_order,
				'sort_order' => $level_1_code.$sort_order,
				'account_level' => 2,
				'created_by_id' => $created_by_id,
				'created_on' => time(),
			);
			$data = array_merge($data, $d);
			$id = $this->crud->add($data, $db_table);
			
			$message = 'Record added successfully.';
		}
		else
		{
			$check_validation = $this->coa->check_coa2_validation($project_id, $coa_1, $coa_2, 2, $update_id);
			if (!empty($check_validation)) {
				echo json_encode(array(
					'success' => 0,
					'message' => 'Record already exists.',
					'reload_table' => 0,
					'close_modal' => 0,
				));
				return false;
			}
			
			$d = array(
				'updated_by_id' => $created_by_id,
				'updated_on' => time(),
			);
			$data = array_merge($data, $d);
			$this->crud->update($data, $update_id, $db_table, $primary_id);

			//Add Log History
			log_history($db_table, $update_id);

			$message = 'Record updated successfully.';
		}

		echo json_encode( array(
			'success' => 1,
			'message' => $message,
			'reload_table' => 1,
			'close_modal' => 1,
		));
	}
	
	/***************************************************************/
		//Level 3
	/***************************************************************/
	
	public function chart_of_accounts_level_3()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$session_project_id = $this->session->userdata('project_id');
		$data['project_list'] = $this->crud->all_list_sort('projects', 'project_name', $session_project_id);
		$data['title'] = "Chart of Accounts Level 3";
		$data['page'] = "finance/chart-of-accounts/coa-level-3";
		$this->load->library('Layout', $data);
	}
	
	public function chart_of_accounts_level_3_list($db_table='chart_of_accounts', $primary_id='chart_of_account_id', $conditions=array(), $return_table_data=False)
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$limit = $this->input->post('length');
    	$start = $this->input->post('start');
		
		$current_role_id = $this->session->userdata('role_id');
		$session_project_id = $this->session->userdata('project_id');
		$joins = array(
			0 => array(
				'table' => 'projects',
				'columns' => "projects.project_id = $db_table.project_id",
				'type' => 'left outer'
			),
			1 => array(
            	'table' => $db_table . ' AS parent',
				'columns' => "parent.chart_of_account_id = $db_table.parent_id",
				'type' => 'left outer'
			),
			2 => array(
				'table' => 'users',
				'columns' => "users.user_id = $db_table.created_by_id",
				'type' => 'left outer'
			),
		);
		$table_columns = "$db_table.*, project_name, $db_table.created_on as create_date, users.fullname as user_name, parent.account_title as parent_account_title";
		$table_count = "COUNT(DISTINCT $db_table.$primary_id) as total_count";
		
		//Where Condition
		if($current_role_id != 1) {
			$project_ids = explode(',', $session_project_id);
			$conditions[] = array('operator' => 'WHERE_IN', 'column' => "$db_table.project_id", 'value' => $project_ids);
		}
		$conditions[] = array('operator' => 'WHERE', 'column' => "$db_table.account_level", 'value' => 3);
		$conditions[] = array('operator' => 'WHERE', 'column' => "$db_table.post_status", 'value' => 1);
		
		//Order By
		$conditions[] = array('operator' => 'ORDER_BY', 'column' => "$db_table.$primary_id", 'value' => 'desc');

		// Fetch records from the model (pass search_value)
		$table_data = $this->crud->dt_list($db_table, $table_columns, $joins, $conditions, $limit, $start);
		$total_records = $this->crud->dt_total_count($db_table, $table_count, $joins, $conditions, '', $limit, $start);
		
		$counter = $table_data['start'];
		foreach($table_data['data'] as $index => $rec)
		{
			$counter++;
			$table_data['data'][$index]->counter = $counter;
			$table_data['data'][$index]->DT_RowId = $rec->chart_of_account_id;
			$table_data['data'][$index]->checkbox = '<i class="fa fa-square-o"></i>';
			$table_data['data'][$index]->parent_account = $rec->parent_account_title;
			$table_data['data'][$index]->account_status = "<span class='waves-light btn-small ".($rec->status == 1 ? 'btn-success' : 'btn-danger') . "'>" . enable_disable($rec->status) . "</span>";
			$table_data['data'][$index]->create_date = $rec->user_name." <br /> ".date_only($rec->create_date);
			$rec->log_table = $db_table;
			$rec->log_id = $rec->chart_of_account_id;
			$rec->update_id = $rec->chart_of_account_id;
			$table_data['data'][$index]->actions = $this->parser->parse('ajax/table-list-action-popup', $rec, TRUE);
		}
		
		// JSON response to DataTables
		$response = [
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_records,
			"data" => $table_data,
		];

		echo json_encode($response);
	}
	
	public function coa_level_3_setup_post($db_table='chart_of_accounts', $primary_id='chart_of_account_id')
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$message = '';
		$created_by_id = $this->session->userdata('user_id');
		
		$project_id = request_var('project_id', '');
		$coa_1 = request_var('coa_1', '');
		$coa_2 = request_var('coa_2', '');
		$coa_3 = request_var('coa_3', '');
		$level_1_code = request_var('level_1_code', '');
		$level_2_code = request_var('level_2_code', '');
		$status = request_var('status', '');
		$update_id = request_var('update_id', '');
		
		$sort_order = chart_of_account_number(array('project_id' => $project_id, 'level_1_code' => $level_1_code, 'level_2_code' => $level_2_code, 'level_3_code' => '', 'primary_key' => 'sort_order', 'number_size' => 3, 'account_level' => 3));
		
		$data = array(
			'account_title' => $coa_3,
			'status' => $status,
		);
		
		if($update_id == '')
		{
			$check_validation = $this->coa->check_coa3_validation($project_id, $level_1_code, $level_2_code, $coa_3, 3);
			if (!empty($check_validation)) {
				echo json_encode(array(
					'success' => 0,
					'message' => 'Record already exists.',
					'reload_table' => 0,
					'close_modal' => 0,
				));
				return false;
			}
			
			$d = array(
				'parent_id' => $coa_2,
				'project_id' => $project_id,
				'level_1_code' => $level_1_code,
				'level_2_code' => $level_2_code,
				'level_3_code' => $sort_order,
				'sort_order' => $level_1_code.$level_2_code.$sort_order,
				'account_level' => 3,
				'created_by_id' => $created_by_id,
				'created_on' => time(),
			);
			$data = array_merge($data, $d);
			$id = $this->crud->add($data, $db_table);

			$message = 'Record added successfully.';
		}
		else
		{
			$check_validation = $this->coa->check_coa3_validation($project_id, $level_1_code, $level_2_code, $coa_3, 3, $update_id);
			if (!empty($check_validation)) {
				echo json_encode(array(
					'success' => 0,
					'message' => 'Record already exists.',
					'reload_table' => 0,
					'close_modal' => 0,
				));
				return false;
			}

			$d = array(
				'updated_by_id' => $created_by_id,
				'updated_on' => time(),
			);
			$data = array_merge($data, $d);
			$this->crud->update($data, $update_id, $db_table, $primary_id);

			//Add Log History
			log_history($db_table, $update_id);

			$message = 'Record updated successfully.';
		}

		echo json_encode( array(
			'success' => 1,
			'message' => $message,
			'reload_table' => 1,
			'close_modal' => 1,
		));
	}
	
	/***************************************************************/
		//Level 4
	/***************************************************************/
	
	public function chart_of_accounts_level_4()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$session_project_id = $this->session->userdata('project_id');
		$data['project_list'] = $this->crud->all_list_sort('projects', 'project_name', $session_project_id);
		$data['title'] = "Chart of Accounts Level 4";
		$data['page'] = "finance/chart-of-accounts/coa-level-4";
		$this->load->library('Layout', $data);
	}
	
	public function chart_of_accounts_level_4_list($db_table='chart_of_accounts', $primary_id='chart_of_account_id', $conditions=array(), $return_table_data=False)
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$limit = $this->input->post('length');
    	$start = $this->input->post('start');
		
		$current_role_id = $this->session->userdata('role_id');
		$session_project_id = $this->session->userdata('project_id');
		$joins = array(
			0 => array(
				'table' => 'projects',
				'columns' => "projects.project_id = $db_table.project_id",
				'type' => 'left outer'
			),
			1 => array(
            	'table' => $db_table . ' AS parent',
				'columns' => "parent.chart_of_account_id = $db_table.parent_id",
				'type' => 'left outer'
			),
			2 => array(
				'table' => 'users',
				'columns' => "users.user_id = $db_table.created_by_id",
				'type' => 'left outer'
			),
		);
		$table_columns = "$db_table.*, project_name, $db_table.created_on as create_date, users.fullname as user_name, parent.account_title as parent_account_title";
		$table_count = "COUNT(DISTINCT $db_table.$primary_id) as total_count";
		
		//Where Condition
		if($current_role_id != 1) {
			$project_ids = explode(',', $session_project_id);
			$conditions[] = array('operator' => 'WHERE_IN', 'column' => "$db_table.project_id", 'value' => $project_ids);
		}
		$conditions[] = array('operator' => 'WHERE', 'column' => "$db_table.account_level", 'value' => 4);
		$conditions[] = array('operator' => 'WHERE', 'column' => "$db_table.post_status", 'value' => 1);
		
		//Order By
		$conditions[] = array('operator' => 'ORDER_BY', 'column' => "$db_table.$primary_id", 'value' => 'desc');

		// Fetch records from the model (pass search_value)
		$table_data = $this->crud->dt_list($db_table, $table_columns, $joins, $conditions, $limit, $start);
		$total_records = $this->crud->dt_total_count($db_table, $table_count, $joins, $conditions, '', $limit, $start);
		
		$counter = $table_data['start'];
		foreach($table_data['data'] as $index => $rec)
		{
			$counter++;
			$table_data['data'][$index]->counter = $counter;
			$table_data['data'][$index]->DT_RowId = $rec->chart_of_account_id;
			$table_data['data'][$index]->checkbox = '<i class="fa fa-square-o"></i>';
			$table_data['data'][$index]->parent_account = $rec->parent_account_title;
			$table_data['data'][$index]->account_status = "<span class='waves-light btn-small ".($rec->status == 1 ? 'btn-success' : 'btn-danger') . "'>" . enable_disable($rec->status) . "</span>";
			$table_data['data'][$index]->create_date = $rec->user_name." <br /> ".date_only($rec->create_date);
			$rec->log_table = $db_table;
			$rec->log_id = $rec->chart_of_account_id;
			$rec->update_id = $rec->chart_of_account_id;
			$table_data['data'][$index]->actions = $this->parser->parse('ajax/table-list-action-popup', $rec, TRUE);
		}
		
		// JSON response to DataTables
		$response = [
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_records,
			"data" => $table_data,
		];

		echo json_encode($response);
	}
	
	public function coa_level_4_setup_post($db_table='chart_of_accounts', $primary_id='chart_of_account_id')
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$message = '';
		$created_by_id = $this->session->userdata('user_id');
		
		$project_id = request_var('project_id', '');
		$coa_1 = request_var('coa_1', '');
		$coa_2 = request_var('coa_2', '');
		$coa_3 = request_var('coa_3', '');
		$coa_4 = request_var('coa_4', '');
		
		$level_1_code = request_var('level_1_code', '');
		$level_2_code = request_var('level_2_code', '');
		$level_3_code = request_var('level_3_code', '');
		
		$status = request_var('status', '');
		$update_id = request_var('update_id', '');
		
		$sort_order = chart_of_account_number(array('project_id' => $project_id, 'level_1_code' => $level_1_code, 'level_2_code' => $level_2_code, 'level_3_code' => $level_3_code, 'primary_key' => 'sort_order', 'number_size' => 3, 'account_level' => 4));
		
		$data = array(
			'account_title' => $coa_4,
			'status' => $status,
		);
		
		if($update_id == '')
		{
			$check_validation = $this->coa->check_coa4_validation($project_id, $level_1_code, $level_2_code, $level_3_code, $coa_4, 4);
			if (!empty($check_validation)) {
				echo json_encode(array(
					'success' => 0,
					'message' => 'Record already exists.',
					'reload_table' => 0,
					'close_modal' => 0,
				));
				return false;
			}
			
			$d = array(
				'parent_id' => $coa_3,
				'project_id' => $project_id,
				'level_1_code' => $level_1_code,
				'level_2_code' => $level_2_code,
				'level_3_code' => $level_3_code,
				'level_4_code' => $sort_order,
				'sort_order' => $level_1_code.$level_2_code.$level_3_code.$sort_order,
				'account_level' => 4,
				'created_by_id' => $created_by_id,
				'created_on' => time(),
			);
			$data = array_merge($data, $d);
			$id = $this->crud->add($data, $db_table);
			
			$message = 'Record added successfully.';
		}
		else
		{
			$check_validation = $this->coa->check_coa4_validation($project_id, $level_1_code, $level_2_code, $level_3_code, $coa_4, 4, $update_id);
			if (!empty($check_validation)) {
				echo json_encode(array(
					'success' => 0,
					'message' => 'Record already exists.',
					'reload_table' => 0,
					'close_modal' => 0,
				));
				return false;
			}
			
			$d = array(
				'updated_by_id' => $created_by_id,
				'updated_on' => time(),
			);
			$data = array_merge($data, $d);
			$this->crud->update($data, $update_id, $db_table, $primary_id);

			//Add Log History
			log_history($db_table, $update_id);

			$message = 'Record updated successfully.';
		}

		echo json_encode( array(
			'success' => 1,
			'message' => $message,
			'reload_table' => 1,
			'close_modal' => 1,
		));
	}
}
