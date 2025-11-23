<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->model('project_model', 'project');
		$this->load->model('inventory_model', 'inventory');
	}
	
	public function inventory()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$data['title'] = "Inventory";
		$data['page'] = "projects/inventory/inventory";
		$this->load->library('Layout', $data);
	}

	public function inventory_list($db_table='inventories', $primary_id='inventory_id', $conditions=array(), $return_table_data=False)
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$limit = $this->input->post('length');
    	$start = $this->input->post('start');
		
		$current_role_id = $this->session->userdata('role_id');
		$current_user_id = $this->session->userdata('user_id');
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

		$table_columns = "$db_table.*, projects.project_name as project_name, $db_table.created_on as create_date, users.fullname as user_name";
		$table_count = "COUNT(DISTINCT $db_table.$primary_id) as total_count";

		if($current_role_id == 2 or $current_role_id == 6) {
			$project_ids = explode(',', $session_project_id);
			$conditions[] = array('operator' => 'WHERE_IN', 'column' => "$db_table.project_id", 'value' => $project_ids);
		}
		$conditions[] = array('operator' => 'WHERE', 'column' => "$db_table.post_status", 'value' => 1);
		
		//Group By
		$conditions[] = array('operator' => 'group_by', 'column' => 'inventory_id', 'value' => true);
		
		//Order By
		$conditions[] = array('operator' => 'ORDER_BY', 'column' => "$db_table.inventory_id", 'value' => 'desc');

		// Fetch records from the model (pass search_value)
		$table_data = $this->crud->dt_list($db_table, $table_columns, $joins, $conditions, $limit, $start);
		$total_records = $this->crud->dt_total_count($db_table, $table_count, $joins, $conditions, '', $limit, $start);
		
		// Prepare response (ensure keys match your column names)
		$counter = $table_data['start'];
		foreach($table_data['data'] as $index => $rec)
		{
			$counter++;
			$table_data['data'][$index]->counter = $counter;
			$table_data['data'][$index]->DT_RowId = $rec->inventory_id;
			$table_data['data'][$index]->checkbox = '<i class="fa fa-square-o"></i>';
			$table_data['data'][$index]->project_name = $rec->project_name.'<div class="table-action">';
			
			// Check user role to exclude edit and delete buttons
			if ($current_role_id != 6) {
				$table_data['data'][$index]->project_name .= '<a href="'.site_url('inventory/edit/'.$rec->inventory_id).'">Edit</a> | ';
				$table_data['data'][$index]->project_name .= ($rec->status == 1 ? '<a href="javascript:;" onClick="delete_record(\''.$rec->inventory_id.'\');">Delete</a> | ' : '');
			}
			
			$table_data['data'][$index]->project_name .= '<a href="'.site_url('inventory/view/'.$rec->inventory_id).'">View</a></div>';
			$table_data['data'][$index]->property_type = property_types($rec->property_type);

			if($rec->plan_type == 'Installment')
			{
				$table_data['data'][$index]->payment_plan_inv = $rec->payment_plan;
			}
			else
			{
				$table_data['data'][$index]->payment_plan_inv = 'Milestone';
			}

			$table_data['data'][$index]->total_price = number_format($rec->total_price);
			$table_data['data'][$index]->inventory_status = "<span class='waves-light btn-small ".($rec->status == 1 ? 'btn-success' : 'btn-danger') . "'>" . inventory_status($rec->status) . "</span>";
			$table_data['data'][$index]->create_date = $rec->user_name." <br /> ".date_only($rec->create_date);
			$rec->log_table = $db_table;
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
	
	public function inventory_delete()
	{
		check_login();
		check_viewer_login();
		restrict_role(CRM_ROLE);
		
		$delete_id = request_var('delete_id', '');
		$data = array(
			'post_status' => 0,
		);
		$this->crud->update($data, $delete_id, 'inventories', 'inventory_id');
		$this->crud->update($data, $delete_id, 'inventory_installments', 'inventory_id');
		out ('SUCCESS', 'Removed.');
	}

	public function inventory_setup($record_id=0, $copy=0)
	{
		check_login();
		check_viewer_login();
		restrict_role(CRM_ROLE);
		
		$session_project_id = $this->session->userdata('project_id');
		$data['record'] = $this->inventory->inventory_detail_list($record_id);
		$data['project_list'] = $this->crud->all_list_sort('projects', 'project_name', $session_project_id);
		$data['installment_list'] = $this->inventory->inventory_installment_list($record_id);
		$data['milestone_list'] = $this->inventory->inventory_milestone_list($record_id);
		$data['page'] = "projects/inventory/inventory-setup";
		$data['title'] = ($record_id != 0) ? ($copy == 1 ? "Inventory Copy" : "Inventory Edit") : "Inventory Add";
		$this->load->library('Layout', $data);
	}
	
	public function inventory_setup_post($slug_url=0, $db_table='inventories', $primary_id='inventory_id')
	{
		check_login();
		check_viewer_login();
		restrict_role(CRM_ROLE);
		
		$message = '';
		$form_data = get_posted_data();
		$last_uri = $form_data["last_uri"];
		$created_by_id = $this->session->userdata('user_id');

		// Form validation rules
        $this->form_validation->set_rules('project_id', 'Project ID', 'required');
        $this->form_validation->set_rules('floor_block', 'Floor/Block', 'required');
        $this->form_validation->set_rules('unit_number', 'Unit Number', 'required');
		
		$project_id = $form_data["project_id"];
		$inventory_main_id = $form_data["inventory_main_id"];
		$property_type = isset($form_data["property_type"]) ? $form_data["property_type"] : '';
		$floor_block = isset($form_data["floor_block"]) ? $form_data["floor_block"] : '';
		$unit_number = isset($form_data["unit_number"]) ? $form_data["unit_number"] : '';
		$payment_plan = isset($form_data["payment_plan"]) ? $form_data["payment_plan"] : '';
		$unit_size = isset($form_data["unit_size"]) ? $form_data["unit_size"] : '';
		$unit_category = isset($form_data["unit_category"]) ? $form_data["unit_category"] : '';
		$total_price = isset($form_data["total_price"]) ? $form_data["total_price"] : '';
		$date = isset($form_data["date"]) ? $form_data["date"] : '';
		$plan_type = isset($form_data["plan_type"]) ? $form_data["plan_type"] : '';
		$project_milestone_id = isset($form_data["project_milestone_id"]) ? $form_data["project_milestone_id"] : '';
		$amount = isset($form_data["amount"]) ? $form_data["amount"] : [];
		$milestone_amount = isset($form_data["milestone_amount"]) ? $form_data["milestone_amount"] : [];
		$installment_id = isset($form_data["installment_id"]) ? $form_data["installment_id"] : '';
		$inventory_id = isset($form_data["inventory_id"]) ? $form_data["inventory_id"] : '';
		$milestone_id = isset($form_data["milestone_id"]) ? $form_data["milestone_id"] : '';

		($form_data["plan_type"] == 'Installment') ?
			$total_amount = array_sum($amount) :
			$total_amount = array_sum($milestone_amount);
		if ($total_price != $total_amount) {
			out_json([
				'success' => 0,
				'message' => 'The total of installment amounts must equal the total price.'
			]);
			return;
		}
		
		if($form_data["plan_type"] == 'Installment')
		{
			$check_unit_validation = $this->crud->check_unit_validation($project_id, $unit_number, $db_table, $inventory_main_id);
			$check_floor_unit_validation = $this->crud->check_floor_validation($project_id, $floor_block, $unit_number, $db_table, $inventory_main_id);
			//pre_print($check_floor_unit_validation);
			if ( !empty($check_unit_validation) AND !empty($check_floor_unit_validation) )
			{
				out('ERROR', '<p>Record already exists.</p>');
				return false;
			}
		}
		
		$message = '';
		$data = array(
			'project_id' => $project_id,
			'property_type' => $property_type,
			'floor_block' => $floor_block,
			'unit_number' => $unit_number,
			'plan_type' => $plan_type,
			'payment_plan' => $payment_plan,
			'unit_size' => $unit_size,
			'unit_category' => $unit_category,
			'total_price' => $total_price,
		);
		
		// Add
		if($slug_url == 0)
		{
			$d = array(
				'status' => 1,
				'created_by_id' => $created_by_id,
				'created_on' => time(),
			);
			$data = array_merge($data, $d);
			$id = $this->crud->add($data, $db_table);
			
			if(!empty($id))
			{
				if($plan_type == 'Milestone')
				{
					if(!empty($project_milestone_id))
					{
						foreach($project_milestone_id as $index => $value)
						{
							$data2 = array(
								'inventory_id' => $id,
								'project_milestone_id' => !empty($project_milestone_id[$index]) ? $project_milestone_id[$index] : 0,
								'amount' => !empty($milestone_amount[$index]) ? $milestone_amount[$index] : 0,
							);
							$this->crud->add($data2, 'inventory_milestones');
						}
					}
				}
				else
				{
					//Installment
					if(!empty($date))
					{
						foreach($date as $index => $value)
						{
							$data2 = array(
								'inventory_id' => $id,
								'date' => date('Y-m-d', strtotime($date[$index])),
								'amount' => !empty($amount[$index]) ? $amount[$index] : 0,
							);
							$this->crud->add($data2, 'inventory_installments');
						}
					}
				}
			}
			
			$message = 'Record added successfully.';
		}
		// Edit Update
		else
		{
			$d = array(
				'updated_by_id' => $created_by_id,
				'updated_on' => time(),
			);
			$data = array_merge($data, $d);
			$this->crud->update($data, $slug_url, $db_table, $primary_id);

			// Get all existing installments for this inventory
			$existing_installments = $this->inventory->get_where('inventory_installments', array('inventory_id' => $inventory_main_id));
			$existing_ids = array();
			foreach ($existing_installments as $installment) {
				$existing_ids[] = $installment->installment_id;
			}
			
			// Find installments that exist in DB but not in submitted data (deleted rows)
			//$deleted_ids = array_diff($existing_ids, $installment_id);
			$installment_id = isset($form_data["installment_id"]) ? (array)$form_data["installment_id"] : [];
			$deleted_ids = array_diff($existing_ids, $installment_id);
			
			// Mark deleted installments as inactive (post_status = 0)
			foreach ($deleted_ids as $deleted_id) {
				$this->crud->update(array('post_status' => 0), $deleted_id, 'inventory_installments', 'installment_id');
			}
			
			if($plan_type == 'Milestone')
			{
				foreach($milestone_id as $index => $value)
				{
					//echo $installment_id[$index];
					if(!empty($milestone_id[$index]))
					{
						$data2 = array(
							'amount' => !empty($milestone_amount[$index]) ? $milestone_amount[$index] : 0,
						);
						$this->crud->update($data2, $milestone_id[$index], 'inventory_milestones', 'milestone_id');
					}
					else
					{
						$data2 = array(
							'inventory_id' => $form_data["inventory_main_id"],
							'project_milestone_id' => !empty($project_milestone_id[$index]) ? $project_milestone_id[$index] : 0,
							'amount' => !empty($milestone_amount[$index]) ? $milestone_amount[$index] : 0,
						);
						$this->crud->add($data2, 'inventory_installments');
					}
				}
			}
			else
			{
				//Installment
				foreach($date as $index => $value)
				{
					//echo $installment_id[$index];
					if(!empty($installment_id[$index]))
					{
						$data2 = array(
							//'inventory_id' => isset($inventory_id[$index]) ? $inventory_id[$index] : 0,
							'date' => date('Y-m-d', strtotime($date[$index])),
							'amount' => !empty($amount[$index]) ? $amount[$index] : 0,
						);
						$this->crud->update($data2, $installment_id[$index], 'inventory_installments', 'installment_id');
					}
					else
					{
						$data2 = array(
							'inventory_id' => $form_data["inventory_main_id"],
							'date' => date('Y-m-d', strtotime($date[$index])),
							'amount' => !empty($amount[$index]) ? $amount[$index] : 0,
						);
						$this->crud->add($data2, 'inventory_installments');
					}
				}
			}
			
			//Add Log History
			log_history($db_table, $slug_url);

			$message = 'Record updated successfully.';
		}
		
		out_json( array(
			'success' => 1,
			'message' => $message,
			'RedirectTo' => site_url('inventory'),
		));
	}
	
	public function inventory_installment_list()
	{
		check_login('yes');
		check_viewer_login();
		restrict_role(CRM_ROLE);
		
		$data['installment_list'] = request_var('payment_plan');
		$this->load->view('projects/inventory/installment-list', $data);
	}
	
	public function inventory_view($slug_url=0, $return_data=0)
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$data['record_list'] = $this->inventory->inventory_detail_list($slug_url);
		$data['installment_list'] = $this->inventory->inventory_installment_list($slug_url);
		$data['milestone_list'] = $this->inventory->inventory_milestone_list($slug_url);
		$data['title'] = "Project View";
		$data['page'] = "projects/inventory/inventory-view";

		if($return_data)
			return $data;

		$this->load->library('Layout', $data);
	}

	public function get_view_actions($permission_for = 'inventory')
	{
		check_login();
		check_viewer_login();
		restrict_role(CRM_ROLE);
		
		$response = array(
			'view_actions' => ''
		);

		$page = request_var('page', '');
		$response['page'] = $page;
		$response['record_id'] = request_var('record_id', 0);
		$response['tpl_data'] = array(
			'log_id' => $response['record_id'],
			'add_url' => check_permission('Add', $permission_for, false) ? site_url("inventory/add"):false,
			'edit_url' => check_permission('Edit', $permission_for, false) ? site_url("inventory/edit/$response[record_id]"):false,
			'view_url' => check_permission('View', $permission_for, false) ? site_url("inventory/view/$response[record_id]"):false,
			'print_url' => check_permission('Print', $permission_for, false) ? site_url("inventory/print/$response[record_id]"):false,
			'list_url' => check_permission('List', $permission_for, false) ? site_url("inventory"):false,
			'log_table' => check_permission('Log', $permission_for, false) ? "inventories":false
		);
		$response['view_actions'] = $this->parser->parse('ajax/view-actions', $response['tpl_data'], TRUE);

		out_json($response);
	}

	public function get_milestones($inventory_id = 0)
	{
		$milestones = $this->project->milestone_plan_list($inventory_id);
		echo json_encode($milestones);
	}
	
}
