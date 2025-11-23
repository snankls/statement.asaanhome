<?php class Frontend_model extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();				
    }

    //Dashboard Count
	function dashboard_count($db_name = '', $where = '', $value = '', $group_by = '')
	{
		$this->db->select('*');
		$this->db->from($db_name);
		
		if($group_by == 'yes')
		$this->db->group_by($db_name.'.'.$where);
		
		if($value != '')
		$this->db->where($where, $value);
		
		$query = $this->db->get();
		return $query->result();
	}

    //Dashboard Sum
	function dashboard_sum($db_name = '', $sum_column = '')
	{
		$this->db->select("sum($sum_column) as total_sum");
		$this->db->from($db_name);
		$query = $this->db->get();
		return $query->row();
	}

	// Dashboard CRM
	function dashboard_leads_count($type = '', $search_params = array())
	{
		$alias = !empty($type) ? $type : 'total_count';

		$this->db->select("COUNT(DISTINCT leads.lead_id) as {$alias}");
		$this->db->from('leads');

		$this->db->join('users', 'users.user_id = leads.allocation_id', 'left');
		$this->db->join('(SELECT ld.* 
				FROM lead_details ld 
				INNER JOIN (SELECT lead_id, MAX(lead_detail_id) as max_id 
							FROM lead_details 
							GROUP BY lead_id) last_ld 
				ON ld.lead_detail_id = last_ld.max_id) as latest_lead_detail', 
			'latest_lead_detail.lead_id = leads.lead_id', 
			'right');

		if (isset($search_params['current_role_id']) && $search_params['current_role_id'] == 7) {
			$team_ids = explode(',', $search_params['current_team_id']);

			// Team-based filtering: match allocation_id to self or team
			$this->db->group_start();
			$this->db->where('leads.allocation_id', $search_params['current_user_id']);
			foreach ($team_ids as $team_id) {
				$this->db->or_where("FIND_IN_SET('$team_id', users.team_id)");
			}
			$this->db->group_end();

			// Only count leads handled by users other than Team Lead, or self
			$this->db->group_start();
			$this->db->where('users.role_id !=', 7);
			$this->db->or_where('users.user_id', $search_params['current_user_id']);
			$this->db->group_end();

			// Type-specific status filtering
			if ($type == 'potential_leeds') {
				$this->db->where('latest_lead_detail.lead_status', 3);
				
				if (!empty($search_params['end_next_followup_date'])) {
					$this->db->where('latest_lead_detail.next_followup_date <', $search_params['end_next_followup_date']);
				}
			} elseif ($type == 'closing_leeds') {
				$this->db->where('latest_lead_detail.lead_status', 4);
			} elseif ($type == 'due_overdue_meeting') {
				$this->db->where('latest_lead_detail.task_performed', 5);
			} elseif ($type == 'upcoming_meeting') {
				$this->db->where('latest_lead_detail.next_task', 2);
				
				if (!empty($search_params['end_next_followup_date'])) {
					$this->db->where('latest_lead_detail.next_followup_date <', $search_params['end_next_followup_date']);
				}
			} elseif ($type == 'today_follow_ups') {
				$this->db->where('latest_lead_detail.next_followup_date >=', $search_params['start_next_followup_date']);
				$this->db->where('latest_lead_detail.next_followup_date <=', $search_params['end_next_followup_date']);
			} elseif ($type == 'todo_list') {
				$this->db->where('latest_lead_detail.next_followup_date <', $search_params['end_of_today']);
			}
		}
		else if (isset($search_params['current_role_id']) && $search_params['current_role_id'] == 8)
		{
			// Total leads
			if ($type == 'total_leads') {
				$this->db->where('leads.allocation_id', $search_params['current_user_id']);
			}

			// Potential leads
			if ($type == 'potential_leeds') {
				$this->db->where('leads.allocation_id', $search_params['current_user_id']);
				$this->db->where('latest_lead_detail.lead_status', 3);

				if (!empty($search_params['end_next_followup_date'])) {
					$this->db->where('latest_lead_detail.next_followup_date <', $search_params['end_next_followup_date']);
				}
			}

			// Closing leads
			if ($type == 'closing_leeds') {
				$this->db->where('leads.allocation_id', $search_params['current_user_id']);
				$this->db->where('latest_lead_detail.lead_status', 4);
			}

			// Due & Overdue Meetings
			if ($type == 'due_overdue_meeting') {
				$this->db->where('leads.allocation_id', $search_params['current_user_id']);
				$this->db->where('latest_lead_detail.task_performed', 5);
			}

			// Upcoming Meetings
			if ($type == 'upcoming_meeting') {
				$this->db->where('leads.allocation_id', $search_params['current_user_id']);
				$this->db->where('latest_lead_detail.next_task', 2);
				
				if (!empty($search_params['end_next_followup_date'])) {
					$this->db->where('latest_lead_detail.next_followup_date <', $search_params['end_next_followup_date']);
				}
			}

			// Today's Follow-ups
			if ($type == 'today_follow_ups') {
				$this->db->where('leads.allocation_id', $search_params['current_user_id']);
				$this->db->where('latest_lead_detail.next_followup_date >=', $search_params['start_next_followup_date']);
				$this->db->where('latest_lead_detail.next_followup_date <=', $search_params['end_next_followup_date']);
			}

			// Todo List
			if ($type == 'todo_list') {
				$this->db->where('leads.allocation_id', $search_params['current_user_id']);
				$this->db->where('latest_lead_detail.next_followup_date <', $search_params['end_of_today']);
				$this->db->where('leads.todo_status', 0);
			}
		}

		$this->db->where('leads.status', 1);

		$query = $this->db->get();
		$result = $query->row();

		return $result ? $result->$alias : 0;
	}
}
?>