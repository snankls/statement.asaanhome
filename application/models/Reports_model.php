<?php class Reports_model extends CI_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();				
    }

	function chart_of_account_search($project_id)
	{
		$this->db->select('chart_of_accounts.*, projects.image, 
			COALESCE(SUM(voucher_details.debit), 0) AS debit_total, 
			COALESCE(SUM(voucher_details.credit), 0) AS credit_total');
		$this->db->from('chart_of_accounts');
		$this->db->join('projects', 'projects.project_id = chart_of_accounts.project_id', 'left');
		$this->db->join('voucher_details', 'voucher_details.account_number = chart_of_accounts.chart_of_account_id', 'left');
		$this->db->where('chart_of_accounts.project_id', $project_id);
		$this->db->where('chart_of_accounts.post_status', 1);
		
		if (!empty($coa_level)) {
			$this->db->where('chart_of_accounts.account_level <=', $coa_level);
		}
	
		$this->db->group_by('chart_of_accounts.chart_of_account_id');
		$this->db->order_by('chart_of_accounts.sort_order', 'asc');
	
		$query = $this->db->get();
		return ($query->num_rows() > 0) ? $query->result() : [];
	}
	
	function finance_ledger_search($project_id='', $query='', $from_date='', $to_date='')
	{
		$subquery = $this->db->select('voucher_details.account_number, 
					IFNULL(SUM(voucher_details.debit), 0) - IFNULL(SUM(voucher_details.credit), 0) AS opening_balance')
			->from('voucher_details')
			->join('vouchers', 'vouchers.voucher_id = voucher_details.voucher_id', 'left outer')
			->where('voucher_details.account_number', $query)
			->where('vouchers.voucher_date <', $from_date)
			->group_by('voucher_details.account_number')
			->get_compiled_select();
		
		$this->db->select('
			voucher_details.*,
			vouchers.voucher_date,
			vouchers.transaction_type,
			opening_balance_subquery.opening_balance,
			projects.image,
			projects.project_name
		');
		$this->db->from('voucher_details');
		$this->db->join('vouchers', 'vouchers.voucher_id = voucher_details.voucher_id', 'left outer');
		$this->db->join('projects', 'projects.project_id = vouchers.project_id', 'left outer');
		$this->db->join("($subquery) AS opening_balance_subquery", 'opening_balance_subquery.account_number = voucher_details.account_number', 'left outer');
		$this->db->where('vouchers.project_id', $project_id);
		$this->db->where('voucher_details.account_number', $query);
		
		if(!empty($from_date) AND !empty($to_date))
		$this->db->where('(vouchers.voucher_date >= "'.$from_date.'" AND vouchers.voucher_date <= "'.$to_date.'")');
		
		$this->db->order_by('vouchers.voucher_date', 'asc');
	
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return array();
	}
	
	public function leads_activity_list($limit, $start, $search_params)
	{
		$this->db->select('lead_details.*, lead_details.lead_id as detail_lead_id, lead_details.created_on as ld_create_date, leads.name, leads.phone_number, users.fullname');
		$this->db->from('lead_details');
		$this->db->join('leads', 'leads.lead_id = lead_details.lead_id', 'left');
		$this->db->join('users', 'users.user_id = lead_details.created_by_id', 'left');
		
		//Search Filter
		if(!empty($search_params['task_performed'])) {
			$this->db->where('lead_details.task_performed', $search_params['task_performed']);
		}
		
		if(!empty($search_params['allocation_id'])) {
			$this->db->where('lead_details.created_by_id', $search_params['allocation_id']);
		}
		
		if (!empty($search_params['start_last_followup_date']) && !empty($search_params['end_last_followup_date'])) {
			$this->db->where('lead_details.last_followup_date >=', $search_params['start_last_followup_date']);
			$this->db->where('lead_details.last_followup_date <=', $search_params['end_last_followup_date']);
		}
		
		//Manager Role
		if ($search_params['current_role_id'] != 1 && $search_params['current_role_id'] == 7) {
			$team_ids = explode(',', $search_params['current_team_id']);
			
			$this->db->group_start();
			$this->db->where('leads.allocation_id', $search_params['current_user_id']);
			foreach ($team_ids as $team_id) {
				$this->db->or_where("FIND_IN_SET('$team_id', users.team_id)");
			}
			$this->db->group_end();
			
			$this->db->group_start();
			$this->db->where('users.role_id !=', 7);
			$this->db->or_where('users.user_id', $search_params['current_user_id']);
			$this->db->group_end();
		}
		
		//Individual Role
		if ($search_params['current_role_id'] != 1 && $search_params['current_role_id'] == 8) {
			$this->db->where('leads.allocation_id', $search_params['current_user_id']);
		}
		
		$this->db->order_by('lead_details.created_on', 'desc');
		$this->db->order_by('lead_details.lead_id', 'desc');
		$this->db->limit($limit, $start);
		
		$query = $this->db->get();
		return $query->result();
	}
	
	public function leads_kpi_report_list($search_params = '')
	{
		$this->db->select('
			(SELECT team_name FROM teams WHERE teams.team_id = users.team_id) as team_name,
			users.fullname,
			users.user_id,
			latest_lead_detail.lead_status,
			latest_lead_detail.task_performed,
			
			COUNT(leads.lead_id) as total_leads,
			SUM(CASE WHEN latest_lead_detail.lead_status = 3 THEN 1 ELSE 0 END) as potential_leads,
			SUM(CASE WHEN latest_lead_detail.lead_status = 4 THEN 1 ELSE 0 END) as closing_leads,
			
			(SELECT COUNT(*) FROM lead_details ld 
				WHERE ld.task_performed = 2 
				AND ld.created_by_id = users.user_id
				' . (!empty($search_params['start_last_followup_date']) && !empty($search_params['end_last_followup_date']) ? 
					' AND ld.last_followup_date BETWEEN "' . $search_params['start_last_followup_date'] . '" AND "' . $search_params['end_last_followup_date'] . '"' : '') . '
			) as productive_calls,
	
			(SELECT COUNT(*) FROM lead_details ld 
				WHERE ld.task_performed = 3 
				AND ld.created_by_id = users.user_id
				' . (!empty($search_params['start_last_followup_date']) && !empty($search_params['end_last_followup_date']) ? 
					' AND ld.last_followup_date BETWEEN "' . $search_params['start_last_followup_date'] . '" AND "' . $search_params['end_last_followup_date'] . '"' : '') . '
			) as non_productive_calls,
	
			(SELECT COUNT(*) FROM lead_details ld 
				WHERE ld.task_performed = 1 
				AND ld.created_by_id = users.user_id
				' . (!empty($search_params['start_last_followup_date']) && !empty($search_params['end_last_followup_date']) ? 
					' AND ld.last_followup_date BETWEEN "' . $search_params['start_last_followup_date'] . '" AND "' . $search_params['end_last_followup_date'] . '"' : '') . '
			) as attempted_calls,
	
			(SELECT COUNT(*) FROM lead_details ld 
				WHERE ld.task_performed = 5 
				AND ld.created_by_id = users.user_id
				' . (!empty($search_params['start_last_followup_date']) && !empty($search_params['end_last_followup_date']) ? 
					' AND ld.last_followup_date BETWEEN "' . $search_params['start_last_followup_date'] . '" AND "' . $search_params['end_last_followup_date'] . '"' : '') . '
			) as meetings_arranged,
	
			(SELECT COUNT(*) FROM lead_details ld 
				WHERE ld.task_performed = 6 
				AND ld.created_by_id = users.user_id
				' . (!empty($search_params['start_last_followup_date']) && !empty($search_params['end_last_followup_date']) ? 
					' AND ld.last_followup_date BETWEEN "' . $search_params['start_last_followup_date'] . '" AND "' . $search_params['end_last_followup_date'] . '"' : '') . '
			) as meetings_done
		');
	
		$this->db->from('users');
		$this->db->join('leads', 'leads.allocation_id = users.user_id', 'left');
		$this->db->join('(SELECT ld.* 
					  FROM lead_details ld 
					  INNER JOIN (SELECT lead_id, MAX(lead_detail_id) as max_id 
								  FROM lead_details 
								  GROUP BY lead_id) last_ld 
					  ON ld.lead_detail_id = last_ld.max_id) as latest_lead_detail', 
					'latest_lead_detail.lead_id = leads.lead_id', 
					'right');
		
		// Manager Role
		if ($search_params['current_role_id'] != 1 && $search_params['current_role_id'] == 7) {
			$team_ids = explode(',', $search_params['current_team_id']);
			
			$this->db->group_start();
			$this->db->where('leads.allocation_id', $search_params['current_user_id']);
			foreach ($team_ids as $team_id) {
				$this->db->or_where("FIND_IN_SET('$team_id', users.team_id)");
			}
			$this->db->group_end();
			
			$this->db->group_start();
			$this->db->where('users.role_id !=', 7);
			$this->db->or_where('users.user_id', $search_params['current_user_id']);
			$this->db->group_end();
		}
		
		// Individual Role
		if ($search_params['current_role_id'] != 1 && $search_params['current_role_id'] == 8) {
			$this->db->where('leads.allocation_id', $search_params['current_user_id']);
		}
		
		$this->db->group_by('users.user_id');
		
		$query = $this->db->get();
		return $query->result();
	}
	
	public function leads_kpi_total_records($search_params)
	{
		$this->db->from('leads');
		$this->db->join('lead_details', 'lead_details.lead_id = leads.lead_id', 'left');
		$this->db->join('users', 'leads.allocation_id = users.user_id', 'left');
		return $this->db->count_all_results();
	}
	
}
?>