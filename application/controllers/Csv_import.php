<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Csv_import extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('csvimport');
		$this->load->model('leads_model', 'leads');
	}
	
	function import($db_table = 'temp_leads', $db_table_detail = 'temp_lead_details')
	{
		check_login();
		restrict_role(EXCEPT_ADMIN);
		
		$current_user_id = $this->session->userdata('user_id');
		
		// Load the CSV data
		$file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
		
		// Check if file_data is empty or not an array
		if (empty($file_data) || !is_array($file_data)) {
			// Prepare an error response
			echo json_encode(['status' => 'ERROR', 'message' => 'CSV file is empty or invalid. Please upload a valid CSV file.']);
			return;
		}
		
		// Clean up each row to remove any empty keys
		$file_data = array_map(function($row) {
			return array_filter($row, function($key) {
				return $key !== "";  // Keep only non-empty keys
			}, ARRAY_FILTER_USE_KEY);
		}, $file_data);
		
		//Project List
		$project_mapping = [];
		$project_list = $this->crud->all_list_sort('projects', 'project_name');
		foreach ($project_list as $project) {
			$project_mapping[$project->project_name] = $project->project_id;
		}
		
		//Users List
		$users_mapping = [];
		$users_list = $this->crud->all_list_sort('users', 'fullname');
		foreach ($users_list as $users) {
			$users_mapping[$users->fullname] = $users->user_id;
		}
		
		//Created By
		$created_by_mapping = [];
		$created_by_list = $this->crud->all_list_sort('users', 'fullname');
		foreach ($created_by_list as $created) {
			$created_by_mapping[$created->fullname] = $created->user_id;
		}
		
		//Lead Source Helper Function
		$lead_sources = array_flip(lead_source());
		
		// Get all phone numbers from the database to avoid duplicates
		$existing_phone_numbers = $this->leads->leads_import_list();
		
		// Loop through the result set and extract each phone number
		$processed_phone_numbers = [];
		foreach ($existing_phone_numbers as $lead) {
			$processed_phone_numbers[] = $lead->phone_number;
		}
		
		foreach ($file_data as $row)
		{
			// Phone Number
			$country_code = '+'.$row["Country Code"];
			$phone = $row['Client Phone'];
			$phone_number = $country_code.$phone;
			
			// Check if the phone number is already in the database or was processed in the CSV file
			if (in_array($phone_number, $existing_phone_numbers) || in_array($phone_number, $processed_phone_numbers)) {
				continue;
			}
			
			// Add phone number to processed list to prevent duplicate entries
			$processed_phone_numbers[] = $phone_number;
			
			// Map additional fields
			$project_id = isset($project_mapping[$row["Project Name"]]) ? $project_mapping[$row["Project Name"]] : null;
			$user_id = isset($users_mapping[$row["Allocation Name"]]) ? $users_mapping[$row["Allocation Name"]] : null;
			$source_index = isset($lead_sources[$row["Lead Source"]]) ? $lead_sources[$row["Lead Source"]] : null;
			$created_date = strtotime(str_replace(' - ', ' ', $row["Created Date"]));
			$created_by_id = isset($users_mapping[$row["Created By"]]) ? $users_mapping[$row["Created By"]] : null;
			
			// Construct the data array for insertion
			$data = array(
				'name'          => isset($row["Client Name"]) ? $row["Client Name"] : '',
				'country_code'  => $country_code,
				'phone'			=> $phone,
				'phone_number'  => $phone_number,
				'email_address' => isset($row["Client Email"]) ? $row["Client Email"] : '',
				'city'          => isset($row["City"]) ? $row["City"] : '',
				'project_id'    => isset($project_id) ? $project_id : '',
				'allocation_id' => isset($user_id) ? $user_id : '',
				'lead_source'   => isset($source_index) ? $source_index : '',
				'created_by_id' => isset($created_by_id) ? $created_by_id : '',
				'created_on'    => isset($created_date) ? $created_date : '',
			);
			$id = $this->crud->add($data, $db_table);
			
			if (!empty($id)) {
				// Additional data processing
				$lead_status = array_flip(lead_status());
				$status_index = isset($lead_status[$row["Lead Status"]]) ? $lead_status[$row["Lead Status"]] : null;
				
				$task_performed = array_flip(task_performed());
				$task_performed_index = isset($task_performed[$row["Task Performed"]]) ? $task_performed[$row["Task Performed"]] : null;
				
				$next_task = array_flip(next_task());
				$next_task_index = isset($next_task[$row["Next Task"]]) ? $next_task[$row["Next Task"]] : null;
				
				$last_followup_date = strtotime(str_replace(' - ', ' ', $row["Last Followup Date"]));
				$next_followup_date = strtotime(str_replace(' - ', ' ', $row["Next Followup Date"]));
				
				$data2 = array(
					'lead_id'           => $id,
					'lead_status'       => $status_index,
					'task_performed'    => $task_performed_index,
					'last_followup_date'=> $last_followup_date,
					'next_followup_date'=> $next_followup_date,
					'next_task'         => $next_task_index,
					'remarks'           => isset($row["Remarks"]) ? $row["Remarks"] : '',
					'created_by_id'     => $current_user_id,
				);
				$this->crud->add($data2, 'temp_lead_details');
			}
		}
		
		out('SUCCESS', 'Bulk Import Successfully.');
	}
		
}
