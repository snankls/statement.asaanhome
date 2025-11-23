<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->model('import_model', 'import');
	}
	
	public function leads_import()
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$data['title'] = "Buk Import";
		$data['page'] = "crm/leads/import/import";
		$this->load->library('Layout', $data);
	}
	
	public function leads_import_list($db_table='temp_leads', $conditions=array(), $return_table_data=False, $group_by=true)
	{
		check_login();
		
		// Get current user and role info
		$current_user_id = $this->session->userdata('user_id');
		$current_role_id = $this->session->userdata('role_id');
		$current_team_id = $this->session->userdata('team_id');
		
		// Joins setup
		$joins = array(
			0 => array(
				'table' => '(SELECT lead_id, MAX(lead_detail_id) AS max_lead_detail_id FROM temp_lead_details GROUP BY lead_id) AS latest_details',
				'columns' => 'latest_details.lead_id = temp_leads.lead_id',
				'type' => 'right'
			),
			1 => array(
				'table' => 'temp_lead_details',
				'columns' => 'temp_lead_details.lead_id = latest_details.lead_id AND temp_lead_details.lead_detail_id = latest_details.max_lead_detail_id',
				'type' => 'right'
			),
			2 => array(
				'table' => 'projects',
				'columns' => "projects.project_id = $db_table.project_id",
				'type' => 'left outer'
			),
			3 => array(
				'table' => 'users',
				'columns' => "users.user_id = $db_table.allocation_id",
				'type' => 'left outer'
			),
		);
		
		// Columns to select
		$table_columns = "
			$db_table.*,
			temp_lead_details.*,
			$db_table.lead_id as main_lead_id,
			$db_table.created_on as lead_create_date,
			projects.project_name,
			users.fullname
		";
		
		$conditions[] = array('operator' => 'GROUP_BY', 'column' => "$db_table.lead_id", 'value' => true);
		$conditions[] = array('operator' => 'ORDER_BY', 'column' => "$db_table.lead_id", 'value' => 'desc');
		
		// Fetching Data
		$table_data = $this->crud->datatable_data($db_table, $table_columns, $joins, $conditions);
		
		// Process the fetched data
		$counter = $table_data['start'];
		foreach($table_data['data'] as $index => $rec)
		{
			$counter++;
			$table_data['data'][$index]->counter = $counter;
			$table_data['data'][$index]->DT_RowId = $rec->lead_id;
			$table_data['data'][$index]->checkbox = '<i class="fa fa-square-o"></i>';
			
			$table_data['data'][$index]->action = '
			<input type="hidden" name="lead_id" value="'.$rec->main_lead_id.'">
			<input type="hidden" name="country_code" value="'.$rec->country_code.'" />
			<input type="hidden" name="phone_number" value="'.$rec->phone.'" />
			<input type="hidden" name="email_address" value="'.$rec->email_address.'" />
			<input type="hidden" name="city" value="'.$rec->city.'" />
			
			<div class="dropdown">
				<button class="btn btn-small btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Action <span class="caret"></span></button>
				<ul class="dropdown-menu">
					<li><a href="javascript:;" data-toggle="modal" data-target="#followupModal" class="dropdown-item" onClick="add_followup(\'' . $rec->main_lead_id . '\');">Add Followup</a></li>
					<li><a href="javascript:;" class="dropdown-item" onClick="edit_record(this);">Edit Leads</a></li>
				</ul>
			</div>';
			
			$table_data['data'][$index]->create_date = '<span data-toggle="tooltip" title="' . time_only($rec->lead_create_date) . '">' . date_only($rec->lead_create_date) . '</span>';
			$table_data['data'][$index]->main_lead_id = '<a href="tel:' . $rec->phone_number . '" data-toggle="tooltip" title="' . $rec->phone_number . '">' . $rec->main_lead_id . '</a>';
			$table_data['data'][$index]->allocation_name = $rec->fullname;
			$table_data['data'][$index]->lead_source = lead_source($rec->lead_source);
			$table_data['data'][$index]->lead_status = lead_status($rec->lead_status);
			$table_data['data'][$index]->last_followup_date = date_only($rec->last_followup_date);
			$table_data['data'][$index]->task_performed = task_performed($rec->task_performed);
			$table_data['data'][$index]->next_followup_date = date_only($rec->next_followup_date);
			$table_data['data'][$index]->next_task = next_task($rec->next_task);
			
			$rec->log_table = $db_table;
		}
		
		echo json_encode($table_data);
	}
	
	/*public function leads_import_list($db_table='temp_leads', $conditions=array(), $return_table_data=False)
	{
		check_login();
		
		$data['record_list'] = $this->import->leads_import_list();
		$this->load->view('crm/leads/import/import-list', $data);
	}*/
	
	public function import_leads_followup_list()
	{
		check_login('yes');
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$lead_id = request_var('lead_id', '');
		$current_user_id = $this->session->userdata('user_id');
		$data['record_list'] = $this->import->import_followup_list($lead_id);
		$data['lead_id'] = $lead_id;
		
		$this->load->view('crm/leads/import/import-followup-list', $data);
	}
	
	public function import_leads_step1_setup_post($db_table='temp_leads', $primary_id='lead_id')
	{
		check_login();
		check_viewer_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$created_by_id = $this->session->userdata('user_id');
		$update_id = $_POST['update_id'];
		$name = $_POST['name'];
		$phone_number = $_POST['phone_number'];
		$email_address = $_POST['email_address'];
		$city = $_POST['city'];
		$country = $_POST['country'];
		
		$check_phone = $this->import->check_phone_number($country, $phone_number, $update_id);
		
		// Perform validation or any other checks
		if (!empty($check_phone)) {
			out('ERROR', 'Phone number already exists.');
			
			return false;
		}
		
		//Update Challan
		$data = array(
			'name' => $name,
			'phone_number' => $phone_number,
			'email_address' => $email_address,
			'city' => $city,
			'country_code' => $country,
			'updated_by_id' => $created_by_id,
			'updated_on' => time(),
		);
		$this->crud->update($data, $update_id, $db_table, $primary_id);
		
		out ('SUCCESS', 'Record updated successfully.');
	}
	
	public function leads_query_shift()
	{
		check_login();
		
		$temp_leads = $this->crud->all_list('temp_leads');
		
		$leads = $this->crud->all_list('leads');
		
		foreach ($temp_leads as $temp)
		{
			$phone_number = $temp->phone_number;
			$lead_phone_number = $this->import->check_single_phone_number($phone_number, 'leads', 'phone_number');
			
			if($phone_number != $lead_phone_number)
			{
				$data = array(
					'name'          => $temp->name,
					'country_code'  => $temp->country_code,
					'phone_number'  => $temp->phone_number,
					'email_address' => $temp->email_address,
					'city'          => $temp->city,
					'project_id'    => $temp->project_id,
					'allocation_id' => $temp->allocation_id,
					'lead_source'   => $temp->lead_source,
					'created_by_id' => $temp->created_by_id,
					'created_on'    => $temp->created_on,
				);
				$id = $this->crud->add($data, 'leads');
				
				$temp_lead_details = $this->crud->detail_list($temp->lead_id, 'temp_lead_details', 'lead_id');
				foreach ($temp_lead_details as $temp2)
				{
					if (!empty($id)) {
						$data2 = array(
							'lead_id'           => $id,
							'lead_status'       => $temp2->lead_status,
							'task_performed'    => $temp2->task_performed,
							'last_followup_date'=> $temp2->last_followup_date,
							'next_followup_date'=> $temp2->next_followup_date,
							'next_task'         => $temp2->next_task,
							'remarks'           => $temp2->remarks,
							'created_by_id'     => $temp2->created_by_id,
						);
						$this->crud->add($data2, 'lead_details');
					}
				}
				$this->crud->delete($temp->lead_id, 'temp_leads', 'lead_id');
				$this->crud->delete($temp->lead_id, 'temp_lead_details', 'lead_id');
			}
		}
		
		out('SUCCESS', 'Record added successfully.');
	}
}
