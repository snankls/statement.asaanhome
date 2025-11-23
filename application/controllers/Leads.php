<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Leads extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->model('leads_model', 'leads');
	}
	
	public function leads()
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$current_user_id = $this->session->userdata('user_id');
		$current_role_id = $this->session->userdata('role_id');
		$current_team_id = $this->session->userdata('team_id');
		
		$data['crm_user_list'] = $this->leads->crm_user_list($current_user_id, $current_role_id, $current_team_id);
		$data['shift_user_list'] = $this->leads->crm_shift_user_list();
		$data['project_list'] = $this->crud->all_list_sort('projects', 'project_name');
		$data['title'] = "Leads";
		$data['page'] = "crm/leads/leads";
		$this->load->library('Layout', $data);
	}
	
	public function leads_list()
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$limit = $this->input->post('length');
    	$start = $this->input->post('start');
		
		//Last Followup Date
		$last_followup_date = $this->input->post('last_followup_date');
		if (!empty($last_followup_date)) {
			$last_followup_date = explode(' - ', $last_followup_date);
			$start_last_followup_date = strtotime($last_followup_date[0] . ' 00:00:00');
			$end_last_followup_date = strtotime($last_followup_date[1] . ' 23:59:59');
		}
		
		//Next Followup Date
		$next_followup_date = $this->input->post('next_followup_date');
		if (!empty($next_followup_date)) {
			$next_followup_date = explode(' - ', $next_followup_date);
			$start_next_followup_date = strtotime($next_followup_date[0] . ' 00:00:00');
			$end_next_followup_date = strtotime($next_followup_date[1] . ' 23:59:59');
		}
		
		//Create Date
		$lead_added_date = $this->input->post('lead_added_date');
		if (!empty($lead_added_date)) {
			$lead_added_date = explode(' - ', $lead_added_date);
			$start_lead_added_date = strtotime($lead_added_date[0] . ' 00:00:00');
			$end_lead_added_date = strtotime($lead_added_date[1] . ' 23:59:59');
		}
		
		// Fetch search values from POST request
		$search_filters = [
			'current_user_id' => $this->session->userdata('user_id'),
			'current_role_id' => $this->session->userdata('role_id'),
			'current_team_id' => $this->session->userdata('team_id'),
			'lead_id' => $this->input->post('lead_id'),
			'name' => $this->input->post('name'),
			'start_last_followup_date' => isset($start_last_followup_date) ? $start_last_followup_date : '',
			'end_last_followup_date' => isset($end_last_followup_date) ? $end_last_followup_date : '',
			'start_next_followup_date' => isset($start_next_followup_date) ? $start_next_followup_date : '',
			'end_next_followup_date' => isset($end_next_followup_date) ? $end_next_followup_date : '',
			'start_lead_added_date' => isset($start_lead_added_date) ? $start_lead_added_date : '',
			'end_lead_added_date' => isset($end_lead_added_date) ? $end_lead_added_date : '',
			'fullname' => $this->input->post('fullname'),
			'phone_number' => $this->input->post('phone_number'),
			'task_performed' => $this->input->post('task_performed'),
			'next_task' => $this->input->post('next_task'),
			'lead_source' => $this->input->post('lead_source'),
			'project_id' => $this->input->post('project_id'),
			'allocation_id' => $this->input->post('allocation_id'),
			'status' => $this->input->post('status'),
			'page_view' => $this->input->post('page_view'),
		];
		
		// Fetch records from the model (pass search_value)
		$records = $this->leads->leads_list($limit, $start, $search_filters);
		$total_records = $this->crud->get_total_leads($search_filters);
		
		// Prepare response (ensure keys match your column names)
		$data = [];
		foreach ($records as $rec) {
			$action = '
				<div class="action-details">
					<input type="hidden" name="name" value="' . $rec->name . '">
					<input type="hidden" name="lead_id" value="' . $rec->main_lead_id . '">
					<input type="hidden" name="country_code" value="' . $rec->country_code . '" />
					<input type="hidden" name="phone_number" value="' . $rec->phone . '" />
					<input type="hidden" name="email_address" value="' . $rec->email_address . '" />
					<input type="hidden" name="city" value="' . $rec->city . '" />
				</div>';
		
			if ($this->input->post('page_view') == 'todo_list') {
				$action .= '<a href="javascript:;" class="dropdown-item btn btn-small btn-primary" data-toggle="modal" data-target="#followupModal" onclick="add_followup(\'' . $rec->main_lead_id . '\');">Add Followup</a>';
			} else {
				$action .= '
				<div class="dropdown">
					<button type="button" class="btn btn-small btn-primary dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
					<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						<li><a href="javascript:;" class="dropdown-item" data-toggle="modal" data-target="#followupModal" onclick="add_followup(\'' . $rec->main_lead_id . '\');">Add Followup</a></li>
						<li><a href="javascript:;" class="dropdown-item" onclick="edit_record(this);">Edit Leads</a></li>
						<li><a href="javascript:;" class="dropdown-item" data-toggle="modal" data-target="#receiptModal" onclick="add_receipt(\'' . $rec->main_lead_id . '\', \'' . $rec->phone_number . '\');">Add Receipt</a></li>
					</ul>
				</div>';
			}
		
			$data[] = [
				"main_lead_id" => $rec->main_lead_id,
				"checkbox" => '<i class="fa fa-square-o"></i>',
				"action" => $action,
				"create_date" => '<span data-toggle="tooltip" title="' . time_only($rec->lead_create_date) . '">' . date_only($rec->lead_create_date) . '</span>',
				"lead_id" => '<a href="tel:' . $rec->phone_number . '" data-toggle="tooltip" title="' . $rec->phone_number . '">' . $rec->main_lead_id . '</a>',
				"name" => $rec->name,
				"project_name" => $rec->project_name,
				"allocation_name" => $rec->fullname,
				"lead_source" => lead_source($rec->lead_source),
				"lead_status" => lead_status($rec->lead_status),
				"last_followup_date" => date_only($rec->last_followup_date),
				"task_performed" => task_performed($rec->task_performed),
				"next_followup_date" => date_only($rec->next_followup_date),
				"next_task" => next_task($rec->next_task),
			];
			//pre_print($data);
		}
		
		// JSON response to DataTables
		$response = [
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_records,
			"data" => $data,
		];
	
		echo json_encode($response);
	}
	
	public function leads_setup($record_id=0, $copy=0)
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$current_user_id = $this->session->userdata('user_id');
		$current_role_id = $this->session->userdata('role_id');
		$current_team_id = $this->session->userdata('team_id');
		
		$data['crm_user_list'] = $this->leads->crm_user_list($current_user_id, $current_role_id, $current_team_id);
		$data['project_list'] = $this->crud->all_list_sort('projects', 'project_name');
		$data['record'] = $this->leads->leads_detail_list($record_id);
		$data['page'] = "crm/leads/leads-setup";
		$data['title'] = ($record_id != 0) ? ($copy == 1 ? "Leads Copy" : "Leads Edit") : "Create Leads";
		$this->load->library('Layout', $data);
	}
	
	public function leads_setup_post($slug_url=0, $db_table='leads', $primary_id='lead_id')
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$message = '';
		$form_data = get_posted_data();
		$last_uri = $form_data["last_uri"];
		$created_by_id = $this->session->userdata('user_id');
		
		$data = array(
			'name' => $form_data["name"],
			'country_code' => $form_data["country"],
			'phone' => $form_data["phone_number"],
			'phone_number' => $form_data["country"].$form_data["phone_number"],
			'email_address' => $form_data["email_address"],
			'city' => $form_data["city"],
			'project_id' => $form_data["project"],
			'allocation_id' => $form_data["allocation"],
			'lead_source' => $form_data["lead_source"],
		);
		
		if($slug_url == 0)
		{
			$d = array(
				'created_by_id' => $created_by_id,
				'created_on' => time(),
			);
			$data = array_merge($data, $d);
			$id = $this->crud->add($data, $db_table);
			
			if(!empty($id))
			{
				$data = array(
					'lead_id' => $id,
					'lead_status' => $form_data["status"],
					'task_performed' => $form_data["task_performed"],
					'last_followup_date' => time(),
					'next_followup_date' => strtotime($form_data["followup_date"]),
					'next_task' => $form_data["next_task"],
					'remarks' => $form_data["remarks"],
					'created_by_id' => $created_by_id,
					'created_on' => time(),
				);
				$this->crud->add($data, 'lead_details');
			}
			
			$message = 'Record added successfully.';
		}
		else
		{
			$d = array(
				'updated_by_id' => $created_by_id,
				'updated_on' => time(),
			);
			$data = array_merge($data, $d);
			$this->crud->update($data, $slug_url, $db_table, $primary_id);
			
			//Add Log History
			log_history($db_table, $slug_url);

			$message = 'Record updated successfully.';
		}
		
		out_json( array(
			'success' => 1,
			'message' => $message,
			'RedirectTo' => site_url('leads'),
		));
	}
	
	public function leads_check_step1($slug_url = 0, $return_data = 0)
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$response = [];
		$country_code = request_var('country', '');
		$phone_number = request_var('phone_number', '');
		
		$check_validation = $this->leads->check_phone_number($country_code, $phone_number, '');
		
		// Perform validation or any other checks
		if (empty($check_validation)) {
			$response['status'] = 'SUCCESS';
			$response['message'] = 'Phone number verified successfully.';
		} else {
			$response['status'] = 'ERROR';
			$response['message'] = 'This lead already exists with '.$check_validation->fullname.'.';
		}
	
		// Return JSON response
		echo json_encode($response);
	}
	
	public function leads_step1_setup_post($db_table='leads', $primary_id='lead_id')
	{
		check_login();
		check_viewer_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$created_by_id = $this->session->userdata('user_id');
		$update_id = $_POST['update_id'];
		$name = $_POST['name'];
		$country = $_POST['country'];
		$phone = $_POST['phone_number'];
		$phone_number = $country.$phone;
		$email_address = $_POST['email_address'];
		$city = $_POST['city'];
		
		$check_phone = $this->leads->check_phone_number($country, $phone_number, $update_id);
		
		// Perform validation or any other checks
		if (!empty($check_phone)) {
			out('ERROR', 'Phone number already existsss.');
			
			return false;
		}
		
		//Update Challan
		$data = array(
			'name' => $name,
			'country_code' => $country,
			'phone' => $phone,
			'phone_number' => $phone_number,
			'email_address' => $email_address,
			'city' => $city,
			'updated_by_id' => $created_by_id,
			'updated_on' => time(),
		);
		$this->crud->update($data, $update_id, $db_table, $primary_id);
		
		out ('SUCCESS', 'Record updated successfully.');
	}
	
	public function leads_view($slug_url=0, $return_data=0)
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$data['record_list'] = $this->leads->leads_detail_list($slug_url);
		$data['title'] = "Leads View";
		$data['page'] = "crm/leads/leads-view";

		if($return_data)
			return $data;

		$this->load->library('Layout', $data);
	}

	public function get_view_actions($permission_for = 'leads')
	{
		check_login();
		check_viewer_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$response = array(
			'view_actions' => ''
		);

		$page = request_var('page', '');
		$response['page'] = $page;
		$response['record_id'] = request_var('record_id', 0);
		$response['tpl_data'] = array(
			'log_id' => $response['record_id'],
			'add_url' => check_permission('Add', $permission_for, false) ? site_url("leads/add"):false,
			'edit_url' => check_permission('Edit', $permission_for, false) ? site_url("leads/edit/$response[record_id]"):false,
			'view_url' => check_permission('View', $permission_for, false) ? site_url("leads/view/$response[record_id]"):false,
			'print_url' => check_permission('Print', $permission_for, false) ? site_url("leads/print/$response[record_id]"):false,
			'list_url' => check_permission('List', $permission_for, false) ? site_url("leads"):false,
			'log_table' => check_permission('Log', $permission_for, false) ? "leads":false
		);
		$response['view_actions'] = $this->parser->parse('ajax/view-actions', $response['tpl_data'], TRUE);

		out_json($response);
	}
	
	public function leads_followup_list()
	{
		check_login('yes');
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$current_user_id = $this->session->userdata('user_id');
		$current_role_id = $this->session->userdata('role_id');
		$current_team_id = $this->session->userdata('team_id');
		$lead_id = request_var('lead_id', '');
		
		$data['crm_user_list'] = $this->leads->crm_user_list($current_user_id, $current_role_id, $current_team_id);
		$data['project_list'] = $this->crud->all_list_sort('projects', 'project_name');
		$data['record_list'] = $this->leads->leads_followup_list($lead_id, $current_user_id);
		$data['lead_id'] = $lead_id;
		
		$this->load->view('crm/leads/add-followup/leads-followup-list', $data);
	}
	
	public function leads_followup_setup_post($db_table='lead_details', $primary_id='lead_detail_id')
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$lead_id = $_POST['lead_id'];
		$task_performed = $_POST['task_performed'];
		$next_followup_date = $_POST['followup_date'];
		$next_task = $_POST['next_task'];
		$status = $_POST['status'];
		$remarks = $_POST['remarks'];
		$created_by_id = $this->session->userdata('user_id');
		
		$data = array(
			'lead_id' => $lead_id,
			'lead_status' => $status,
			'task_performed' => $task_performed,
			'last_followup_date' => time(),
			'next_followup_date' => strtotime($next_followup_date),
			'next_task' => $next_task,
			'remarks' => $remarks,
			'created_by_id' => $created_by_id,
			'created_on' => time(),
		);
		$this->crud->add($data, $db_table);
		
		echo json_encode( array(
			'success' => 1,
			'message' => 'SUCCESS',
			'reload_table' => 0,
			'close_modal' => 0,
		));
	}
	
	//Shift Leads
	public function shift_leads($db_table='leads')
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$leadIds = request_var('lead_ids');
		$allocation_id = request_var('allocation_id');
		
		foreach($leadIds as $id) {
			$data = array(
				'allocation_id' => $allocation_id,
			);
			$this->crud->update($data, $id, $db_table, 'lead_id');
		}
		
		echo json_encode(array(
			'success' => 0,
			'message' => 'Record update successfully.',
		));
    }

	//Add Receipt
	public function leads_add_receipt($record_id=0, $copy=0)
	{
		check_login('yes');
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$data['lead_id'] = request_var('lead_id', '');
		$data['phone_number'] = request_var('phone', '');
		$data['project_list'] = $this->leads->projects_list();
		$data['page'] = "crm/leads/add-receipt-setup";
		$this->load->view('crm/leads/receipt/receipt-form', $data);
	}

	public function leads_receipt_setup_post($slug_url=0, $db_table='lead_receipts', $primary_id='receipt_id')
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);

		$form_data = get_posted_data();
		$update_id = $form_data['update_id'];
		$created_by_id = $this->session->userdata('user_id');

		if (!empty($form_data["receipt_image"])) {
			$upload_dir = FCPATH . 'uploads/receipt/';
			$upload_url = site_url() . 'uploads/receipt/';
		
			if (!is_dir($upload_dir)) {
				if (!mkdir($upload_dir, 0755, true)) {
					out('ERROR', 'Failed to create folders...');
					return;
				}
			}
		
			$config['upload_path'] = $upload_dir;
			$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
			$config['max_size'] = 5000;
		
			$this->upload->initialize($config);
		
			if (!$this->upload->do_upload('receipt_image')) {
				$error = $this->upload->display_errors();
				out('ERROR', $error);
				return;
			} else {
				$data = $this->upload->data();
		
				// Check if the image needs to be resized
				if ($data['image_width'] > 800 || $data['image_height'] > 800) {
					$resize_config['image_library'] = 'gd2';
					$resize_config['source_image'] = $data['full_path'];
					$resize_config['maintain_ratio'] = TRUE;
					$resize_config['width'] = 800;
					$resize_config['height'] = 800;
		
					$this->load->library('image_lib', $resize_config);
		
					if (!$this->image_lib->resize()) {
						$error = $this->image_lib->display_errors();
						out('ERROR', $error);
						return;
					}
		
					// Clear image_lib settings to avoid conflicts
					$this->image_lib->clear();
				}
		
				$image = $data['file_name'];
			}
		 } else {
		 	$image = isset($form_data["update_receipt_image"]) ? $form_data["update_receipt_image"] : '';
		}
		
		$data = array(
			'name' => $form_data["name"],
			'mobile' => $form_data["mobile"],
			'cnic' => $form_data["cnic"],
			'project_id' => $form_data["project_id"],
			'property_type' => $form_data["property_type"],
			'unit_number' => $form_data["unit_number"],
			'payment_type' => $form_data["payment_type"],
			'unit_price' => $form_data["unit_price"],
			'discount_amount' => $form_data["discount_amount"],
			'settled_price' => $form_data["settled_price"],
			'received_amount' => $form_data["received_amount"],
			'balance_amount' => $form_data["balance_amount"],
			'balance_payment_deadline' => $form_data["balance_payment_deadline"],
			'other_conditions' => $form_data["other_conditions"],
			'remarks' => $form_data["remarks"],
			'receipt_image' => $image,
			'status' => isset($form_data["status"]) ? $form_data["status"] : 1,
		);

		if($update_id == '')
		{
			$d = array(
				'lead_id' => $slug_url,
				'created_by_id' => $created_by_id,
				'created_on' => time(),
			);
			$data = array_merge($data, $d);
			$id = $this->crud->add($data, $db_table);
			
			$message = 'Record added successfully.';
		}
		else
		{
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
			'message' => 'SUCCESS',
			'reload_table' => 0,
			'close_modal' => 0,
		));
	}

	public function leads_receipt()
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$data['project_list'] = $this->crud->all_list_sort('projects', 'project_name');
		$data['title'] = "Leads Receipt";
		$data['page'] = "crm/leads/receipt/receipt";
		$this->load->library('Layout', $data);
	}

	public function leads_receipt_list($db_table='lead_receipts', $primary_id='receipt_id', $conditions=array(), $return_table_data=False)
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$limit = $this->input->post('length');
    	$start = $this->input->post('start');
		
		$current_user_id = $this->session->userdata('user_id');
		$current_role_id = $this->session->userdata('role_id');
		$current_team_id = $this->session->userdata('team_id');

		$joins = array(
			0 => array(
				'table' => 'leads',
				'columns' => "leads.lead_id = $db_table.lead_id",
				'type' => 'left outer'
			),
			1 => array(
				'table' => 'projects',
				'columns' => "projects.project_id = $db_table.project_id",
				'type' => 'left outer'
			),
			2 => array(
				'table' => 'users',
				'columns' => "users.user_id = $db_table.created_by_id",
				'type' => 'left outer'
			),
		);
		$table_columns = "$db_table.*, project_name, $db_table.created_on as create_date, users.fullname as user_name";
		$table_count = "COUNT(DISTINCT $db_table.$primary_id) as total_count";
		
		//Conditions
		$manager_condition = [
			'current_user_id' => $this->session->userdata('user_id'),
			'current_role_id' => $this->session->userdata('role_id'),
			'current_team_id' => $this->session->userdata('team_id'),
			'page_view' => 'receipt',
		];

		//Individual Role
		if ($current_role_id != 1 && $current_role_id == 8) {
			$conditions[] = array('operator' => 'WHERE', 'column' => "$db_table.created_by_id", 'value' => $current_user_id);
		}

		//Order By
		$conditions[] = array('operator' => 'ORDER_BY', 'column' => "$db_table.receipt_id", 'value' => 'desc');
		$conditions[] = array('operator' => 'ORDER_BY', 'column' => "$db_table.status", 'value' => 1);

		// Fetch records from the model (pass search_value)
		$table_data = $this->crud->dt_list($db_table, $table_columns, $joins, $conditions, $manager_condition, $limit, $start);
		$total_records = $this->crud->dt_total_count($db_table, $table_count, $joins, $conditions, $manager_condition, $limit, $start);
		
		// Prepare response (ensure keys match your column names)
		$counter = $table_data['start'];
		foreach($table_data['data'] as $index => $rec)
		{
			$counter++;
			$table_data['data'][$index]->counter = $counter;
			$table_data['data'][$index]->DT_RowId = $rec->receipt_id;
			$table_data['data'][$index]->action = ($rec->status == 1 && $current_role_id == 1) ? '<button type="button" class="btn btn-small btn-primary" onclick="receipt_status(this, \'' . $rec->receipt_id . '\')">Pending</button>' : (($rec->status == 2) ? '<div class="btn btn-small btn-success" style="margin-bottom:5px;">Approved</div><br><a href="javascript:;" class="btn btn-small btn-dark" onclick="receipt_download('.$rec->receipt_id.')"><i class="fa fa-file-text-o"></i> PDF</a>' : (($rec->status == 3) ? '<div class="btn btn-small btn-danger">Cancel</div>' : '<div class="btn btn-small btn-primary">Pending</div>'));
			$table_data['data'][$index]->property_types = property_types($rec->property_type);
			$table_data['data'][$index]->payment_type = payment_type($rec->payment_type);
			$table_data['data'][$index]->receipt_image = '<a href="'. get_image_url($rec->receipt_image, 'receipt') .'" class="lightbox-image list-img" target="_blank">'.get_image($rec->receipt_image, 'receipt').'</a>';
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
	
	//To-do List
	public function todo()
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$current_user_id = $this->session->userdata('user_id');
		$current_role_id = $this->session->userdata('role_id');
		$current_team_id = $this->session->userdata('team_id');
		
		$data['crm_user_list'] = $this->leads->crm_user_list($current_user_id, $current_role_id, $current_team_id);
		$data['project_list'] = $this->crud->all_list_sort('projects', 'project_name');
		$data['title'] = "To-do List";
		$data['page'] = "crm/leads/todo-list";
		$this->load->library('Layout', $data);
	}

	//Shift Todo
	public function shift_todo_to_leads($db_table='leads')
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$leadIds = request_var('lead_ids');
		
		foreach($leadIds as $id) {
			$data = array(
				'todo_status' => 1,
			);
			$this->crud->update($data, $id, $db_table, 'lead_id');
		}
		
		echo json_encode(array(
			'success' => 0,
			'message' => 'Record update successfully.',
		));
    }
	
}
