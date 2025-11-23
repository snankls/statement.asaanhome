<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->helper('function');
		
		$this->load->model('reports_model', 'reports');
		$this->load->model('voucher_model', 'voucher');
		$this->load->model('Chart_of_account_model', 'coa');
		$this->load->model('leads_model', 'leads');
	}
	
	//Chart of Accounts
	public function chart_of_accounts()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$session_project_id = $this->session->userdata('project_id');
		$data['project_list'] = $this->crud->all_list_sort('projects', 'project_name', $session_project_id);
		$data['title'] = "Chart of Accounts";
		$data['page'] = "finance/reports/chart-of-accounts/chart-of-accounts";
		$this->load->library('Layout', $data);
	}

	public function chart_of_account_search()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$project_id = request_var('project_id', '');
		$coa_level = request_var('coa_level', '');
		
		$records = $this->reports->chart_of_account_search($project_id);
		$result = aggregate_coa_totals($records);
		
		// Filter by level if specified
		if (!empty($coa_level)) {
			$result['records'] = filter_by_level($result['records'], $coa_level);
			$result['totals'] = calculate_filtered_totals($result['records'], $coa_level);
		}
		
		$data['record_list'] = $result['records'];
		$data['grand_totals'] = $result['totals'];
		$this->load->view('finance/reports/chart-of-accounts/chart-of-accounts-list', $data);
	}
	
	//Finance Ladger
	public function finance_ledger()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$session_project_id = $this->session->userdata('project_id');
		$data['project_list'] = $this->crud->all_list_sort('projects', 'project_name', $session_project_id);
		$data['title'] = "Finance Ledger";
		$data['page'] = "finance/reports/finance-ledger/finance-ledger";
		$this->load->library('Layout', $data);
	}
	
	public function finance_ledger_search()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$project_id = request_var('project_id', '');
		$query		= request_var('query', '');
		$from_date	= request_var('from_date', '');
		$to_date	= request_var('to_date', '');
		
		$data['record_list'] = $this->reports->finance_ledger_search($project_id, $query, $from_date, $to_date);
		$this->load->view('finance/reports/finance-ledger/finance-ledger-list', $data);
	}
	
	//Leads Activity Report
	public function leads_activity_report()
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$current_user_id = $this->session->userdata('user_id');
		$current_role_id = $this->session->userdata('role_id');
		$current_team_id = $this->session->userdata('team_id');
		
		$data['crm_user_list'] = $this->leads->crm_user_list($current_user_id, $current_role_id, $current_team_id);
		$data['title'] = "Activity Report";
		$data['page'] = "crm/reports/activity-report";
		$this->load->library('Layout', $data);
	}
	
	public function leads_activity_report_list($db_table='lead_details', $conditions=array(), $return_table_data=False)
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$current_user_id = $this->session->userdata('user_id');
		$current_role_id = $this->session->userdata('role_id');
		$current_team_id = $this->session->userdata('team_id');
		
		$limit = $this->input->post('length');
    	$start = $this->input->post('start');
		
		//Last Followup Date
		$last_followup_date = $this->input->post('last_followup_date');
		if (!empty($last_followup_date)) {
			$last_followup_date = explode(' - ', $last_followup_date);
			$start_last_followup_date = strtotime($last_followup_date[0] . ' 00:00:00');
			$end_last_followup_date = strtotime($last_followup_date[1] . ' 23:59:59');
		}
		
		// Fetch search values from POST request
		$search_filters = [
			'current_user_id' => $current_user_id,
			'current_role_id' => $current_role_id,
			'current_team_id' => $current_team_id,
			'task_performed' => $this->input->post('task_performed'),
			'allocation_id' => $this->input->post('allocation_id'),
			'start_last_followup_date' => isset($start_last_followup_date) ? $start_last_followup_date : '',
			'end_last_followup_date' => isset($end_last_followup_date) ? $end_last_followup_date : '',
		];
		
		// Fetch records from the model (pass search_value)
		$records = $this->reports->leads_activity_list($limit, $start, $search_filters);
		$total_records = $this->crud->leads_activity_total_records($search_filters);
		
		// Prepare response (ensure keys match your column names)
		$data = [];
		foreach ($records as $rec) {
			$data[] = [
				"lead_id" => $rec->lead_id,
				"action" => '<a href="javascript:;" class="btn btn-primary btn-small" data-toggle="modal" data-target="#viewActivityModal" onClick="view_activity(\'' . $rec->lead_id . '\');">View Activity</a>',
				"lead_id" => '<a href="tel:' . $rec->phone_number . '" data-toggle="tooltip" title="' . $rec->phone_number . '">' . $rec->lead_id . '</a>',
				"name" => $rec->name,
				"create_date" => date_only($rec->ld_create_date),
				"create_time" => time_only($rec->ld_create_date),
				"task_performed" => task_performed($rec->task_performed),
				"task_performed_by" => $rec->fullname,
			];
		}
		
		$response = [
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_records,
			"data" => $data,
		];
	
		echo json_encode($response);
	}
	
	public function leads_activity_report_details_list()
	{
		check_login('yes');
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$lead_id = request_var('lead_id', '');
		$current_user_id = $this->session->userdata('user_id');
		
		$data['record_list'] = $this->leads->leads_followup_list($lead_id, $current_user_id);
		$this->load->view('crm/reports/activity-report-list', $data);
	}
	
	//KPI Reports
	public function leads_kpi_report()
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
		
		$data['title'] = "KPI Report";
		$data['page'] = "crm/reports/kpi-report";
		$this->load->library('Layout', $data);
	}
	
	public function leads_kpi_report_list()
	{
		check_login();
		restrict_role(PROJECT_FINANCE_ROLE);
	
		$current_user_id = $this->session->userdata('user_id');
		$current_role_id = $this->session->userdata('role_id');
		$current_team_id = $this->session->userdata('team_id');
		
		$limit = $this->input->post('length');
    	$start = $this->input->post('start');
		
		//Last Followup Date
		$last_followup_date = $this->input->post('last_followup_date');
		$last_followup_date_string = '';
		
		if (!empty($last_followup_date)) {
			$last_followup_date = explode(' - ', $last_followup_date);
			$start_last_followup_date = strtotime($last_followup_date[0] . ' 00:00:00');
			$end_last_followup_date = strtotime($last_followup_date[1] . ' 23:59:59');
			
			// Format dates to "F j, Y" (e.g., October 22, 2024)
			$formatted_start_date = date('F j, Y', $start_last_followup_date);
			$formatted_end_date = date('F j, Y', $end_last_followup_date);
			
			// Combine formatted dates
			$last_followup_date_string = $formatted_start_date . ' - ' . $formatted_end_date;
		}
		else
		$last_followup_date_string = "''";
		
		// Fetch search filters from the request
		$search_filters = [
			'current_user_id' => $current_user_id,
			'current_role_id' => $current_role_id,
			'current_team_id' => $current_team_id,
			'start_last_followup_date' => isset($start_last_followup_date) ? $start_last_followup_date : '',
			'end_last_followup_date' => isset($end_last_followup_date) ? $end_last_followup_date : '',
		];
		
		$records = $this->reports->leads_kpi_report_list($search_filters);
		
		// Prepare response (ensure keys match your column names)
		$data = [];
		foreach ($records as $rec) {
			// Prepare the base URL for leads and reports
			$base_url_leads = site_url('leads?user_id=' . $rec->user_id);
			$base_url_activity_report = site_url('reports/activity-report?user_id=' . $rec->user_id);
			
			// Initialize an empty string for the last followup date part
			$last_followup_date_param = '';
	
			// Check if $last_followup_date_string is not empty
			if (!empty($last_followup_date_string)) {
				$last_followup_date_param = '&last_followup_date=' . urlencode($last_followup_date_string);
			}
			
			$data[] = [
				"team_name" => $rec->team_name,
				"team_member" => $rec->fullname,
				'total_leads' => '<a href="' . $base_url_leads . '" target="_blank">' . $rec->total_leads . '</a>',
				'potential_leads' => '<a href="' . $base_url_leads . '&lead_status=3" target="_blank">' . $rec->potential_leads . '</a>',
				'closing_leads' => '<a href="' . $base_url_leads . '&lead_status=4" target="_blank">' . $rec->closing_leads . '</a>',
				
				'productive_calls' => '<a href="' . $base_url_activity_report . '&task_performed=2' . $last_followup_date_param . '" target="_blank">' . $rec->productive_calls . '</a>',
				'non_productive_calls' => '<a href="' . $base_url_activity_report . '&task_performed=3' . $last_followup_date_param . '" target="_blank">' . $rec->non_productive_calls . '</a>',
				'attempted_calls' => '<a href="' . $base_url_activity_report . '&task_performed=1' . $last_followup_date_param . '" target="_blank">' . $rec->attempted_calls . '</a>',
				'meetings_arranged' => '<a href="' . $base_url_activity_report . '&task_performed=5' . $last_followup_date_param . '" target="_blank">' . $rec->meetings_arranged . '</a>',
				'meetings_done' => '<a href="' . $base_url_activity_report . '&task_performed=6' . $last_followup_date_param . '" target="_blank">' . $rec->meetings_done . '</a>',
			];
		}
		
		$response = [
			"draw" => intval($this->input->post('draw')),
			"data" => $data,
		];
	
		echo json_encode($response);
	}
	
}
