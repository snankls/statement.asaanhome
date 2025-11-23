<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Teams extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->model('teams_model', 'teams');
	}
	
	public function teams()
	{
		check_login();
		restrict_role(EXCEPT_ADMIN);
		
		$data['title'] = "Teams";
		$data['page'] = "crm/teams/teams";
		$this->load->library('Layout', $data);
	}

	public function teams_list($db_table = 'teams', $conditions = array(), $return_table_data = False)
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$current_role_id = $this->session->userdata('role_id');
		$current_user_id = $this->session->userdata('user_id');
		$session_project_id = $this->session->userdata('project_id');
		
		$joins = array(
			0 => array(
				'table' => 'users',
				'columns' => "users.user_id = $db_table.created_by_id",
				'type' => 'left outer'
			),
		);
		
		$table_columns = "
			$db_table.*,
			$db_table.created_on as create_date,
			(
				SELECT GROUP_CONCAT(utm.fullname SEPARATOR ', ')
				FROM users utm
				WHERE utm.role_id = 7 AND FIND_IN_SET($db_table.team_id, utm.team_id)
			) as team_manager,
			(
				SELECT GROUP_CONCAT(uti.fullname SEPARATOR ', ')
				FROM users uti
				WHERE uti.role_id = 8 AND FIND_IN_SET($db_table.team_id, uti.team_id)
			) as team_individual,
			users.fullname as user_name
		";
		
		$conditions[] = array('operator' => 'GROUP_BY', 'column' => 'team_id', 'value' => true);
		$conditions[] = array('operator' => 'ORDER_BY', 'column' => "$db_table.team_id", 'value' => 'desc');
		$table_data = $this->crud->datatable_data($db_table, $table_columns, $joins, $conditions);
		
		$counter = $table_data['start'];
		foreach($table_data['data'] as $index => $rec)
		{
			$counter++;
			$table_data['data'][$index]->counter = $counter;
			$table_data['data'][$index]->DT_RowId = $rec->team_id;
			$table_data['data'][$index]->checkbox = '<i class="fa fa-square-o"></i>';
			$table_data['data'][$index]->team_manager = $rec->team_manager;
			$table_data['data'][$index]->team_individual = $rec->team_individual;
			$table_data['data'][$index]->team_status = "<span class='waves-light btn-small ".($rec->status == 1 ? 'btn-success' : 'btn-danger') . "'>" . enable_disable($rec->status) . "</span>";
			$table_data['data'][$index]->created_date = $rec->user_name." <br /> ".date_only($rec->create_date);
			$rec->log_table = $db_table;
			$rec->log_id = $rec->team_id;
			$rec->update_id = $rec->team_id;
			$table_data['data'][$index]->actions = $this->parser->parse('ajax/table-list-action-popup', $rec, TRUE);
		}
	
		if($return_table_data)
			return $table_data;
	
		echo json_encode($table_data);
	}
	
	public function teams_setup($record_id=0, $copy=0)
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$data['record'] = $this->project->project_detail_list($record_id);
		$data['page'] = "crm/teams/teams-setup";
		$data['title'] = ($record_id != 0) ? ($copy == 1 ? "Teams Copy" : "Teams Edit") : "Teams Add";
		$this->load->library('Layout', $data);
	}
	
	public function teams_setup_post($db_table='teams', $primary_id='team_id')
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$message = '';
		$team_name = request_var('team_name', '');
		$status = request_var('status', '');
		$update_id = request_var('update_id', '');
		$created_by_id = $this->session->userdata('user_id');
		
		$data = array(
			'team_name' => $team_name,
			'status' => $status,
		);
		
		if($update_id == '')
		{
			$check_validation = $this->teams->check_teams_validation($team_name);
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
				'created_by_id' => $created_by_id,
				'created_on' => time(),
			);
			$data = array_merge($data, $d);
			$id = $this->crud->add($data, $db_table);
			
			if($id)
			{
				$data = array(
					'team_name' => $team_name,
					'status' => $status,
				);
				$this->crud->update($data, $id, $db_table, $primary_id);
			}

			$message = 'Record added successfully.';
		}
		else
		{
			$check_validation = $this->teams->check_teams_validation($team_name, $update_id);
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

	public function project_view($slug_url=0, $return_data=0)
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$data['record_list'] = $this->project->project_detail_list($slug_url);
		$data['title'] = "Project View";
		$data['page'] = "project/project-view";

		if($return_data)
			return $data;

		$this->load->library('Layout', $data);
	}

	public function get_view_actions($permission_for = 'project')
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$response = array(
			'view_actions' => ''
		);

		$page = request_var('page', '');
		$response['page'] = $page;
		$response['record_id'] = request_var('record_id', 0);
		$response['tpl_data'] = array(
			'log_id' => $response['record_id'],
			'add_url' => check_permission('Add', $permission_for, false) ? site_url("project/add"):false,
			'edit_url' => check_permission('Edit', $permission_for, false) ? site_url("project/edit/$response[record_id]"):false,
			'view_url' => check_permission('View', $permission_for, false) ? site_url("project/view/$response[record_id]"):false,
			'print_url' => check_permission('Print', $permission_for, false) ? site_url("project/print/$response[record_id]"):false,
			'list_url' => check_permission('List', $permission_for, false) ? site_url("project"):false,
			'log_table' => check_permission('Log', $permission_for, false) ? "projects":false
		);
		$response['view_actions'] = $this->parser->parse('ajax/view-actions', $response['tpl_data'], TRUE);

		out_json($response);
	}
		
}
