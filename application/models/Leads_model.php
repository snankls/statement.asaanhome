<?php class Leads_model extends CI_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();				
    }
	
	public function leads_list($limit, $start, $search_params)
	{
		$this->db->select('leads.*, latest_lead_detail.*, leads.lead_id as main_lead_id, leads.created_on as lead_create_date, projects.project_name, users.fullname');
		$this->db->from('leads');
		$this->db->join('(SELECT ld.* 
						  FROM lead_details ld 
						  INNER JOIN (SELECT lead_id, MAX(lead_detail_id) as max_id 
									  FROM lead_details 
									  GROUP BY lead_id) last_ld 
						  ON ld.lead_detail_id = last_ld.max_id) as latest_lead_detail', 
						  'latest_lead_detail.lead_id = leads.lead_id', 
						  'right');
		$this->db->join('projects', 'projects.project_id = leads.project_id', 'left');
		$this->db->join('users', 'users.user_id = leads.allocation_id', 'left');
		
		//Search Filter
		if(!empty($search_params['lead_id'])) {
			$this->db->where('leads.lead_id', $search_params['lead_id']);
		}
		
		if(!empty($search_params['name'])) {
			$this->db->or_like('leads.name', $search_params['name']);
		}
		
		if(!empty($search_params['phone_number'])) {
			$this->db->or_like('leads.phone_number', $search_params['phone_number']);
		}
		
		if(!empty($search_params['task_performed'])) {
			$this->db->where('latest_lead_detail.task_performed', $search_params['task_performed']);
		}
		
		if(!empty($search_params['next_task'])) {
			$this->db->where('latest_lead_detail.next_task', $search_params['next_task']);
		}
		
		if(!empty($search_params['lead_source'])) {
			$this->db->where('leads.lead_source', $search_params['lead_source']);
		}
		
		if(!empty($search_params['status'])) {
			$this->db->where('latest_lead_detail.lead_status', $search_params['status']);
		}
		
		if(!empty($search_params['project_id'])) {
			$this->db->where('leads.project_id', $search_params['project_id']);
		}
		
		if(!empty($search_params['allocation_id'])) {
			$this->db->where('leads.allocation_id', $search_params['allocation_id']);
		}
		
		if (!empty($search_params['start_last_followup_date']) && !empty($search_params['end_last_followup_date'])) {
			$this->db->where('latest_lead_detail.last_followup_date >=', $search_params['start_last_followup_date']);
			$this->db->where('latest_lead_detail.last_followup_date <=', $search_params['end_last_followup_date']);
		}
		
		if (!empty($search_params['start_next_followup_date']) && !empty($search_params['end_next_followup_date'])) {
			$this->db->where('latest_lead_detail.next_followup_date >=', $search_params['start_next_followup_date']);
			$this->db->where('latest_lead_detail.next_followup_date <=', $search_params['end_next_followup_date']);
		}
		
		if (!empty($search_params['start_lead_added_date']) && !empty($search_params['end_lead_added_date'])) {
			$this->db->where('leads.created_on >=', $search_params['start_lead_added_date']);
			$this->db->where('leads.created_on <=', $search_params['end_lead_added_date']);
		}
		
		if($search_params['page_view'] == 'todo_list') {
			$endOfToday = strtotime('today 23:59:59');
			$this->db->where('latest_lead_detail.next_followup_date <', $endOfToday);
			$this->db->where('leads.todo_status', 0);
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

		$this->db->where('leads.status', 1);
		$this->db->group_by('leads.lead_id');
		$this->db->order_by('leads.lead_id', 'desc');
		$this->db->limit($limit, $start);
		
		$query = $this->db->get();
		return $query->result();
	}
	
	function leads_followup_list($lead_id, $current_user_id)
    {
		$this->db->select('leads.*, lead_details.*, leads.lead_id as main_lead_id, leads.created_on as lead_create_date, lead_details.created_on as lead_details_create_date, projects.project_name, users.fullname, u.fullname as lead_performed_by');
		$this->db->from('leads');
		$this->db->join('lead_details', 'lead_details.lead_id = leads.lead_id', 'left outer');
		$this->db->join('projects', 'projects.project_id = leads.project_id', 'left outer');
		$this->db->join('users', 'users.user_id = leads.allocation_id', 'left outer');
		$this->db->join('users u', 'u.user_id = lead_details.created_by_id', 'left outer');
		$this->db->where('leads.lead_id', $lead_id);
		$this->db->order_by('lead_details.lead_detail_id', 'desc');
		
		$query = $this->db->get();
		if( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		return array();
    }
	
	function leads_detail_list($slug_url)
	{
		$this->db->select('leads.*, lead_details.*, projects.project_name, users.fullname');
		$this->db->from('leads');
		$this->db->join('lead_details', 'lead_details.lead_id = leads.lead_id', 'left outer');
		$this->db->join('projects', 'projects.project_id = leads.project_id', 'left outer');
		$this->db->join('users', 'users.user_id = leads.allocation_id', 'left outer');
		$this->db->where('leads.lead_id', $slug_url);
		
		$query = $this->db->get();
		return $query->row();
	}
	
	function check_phone_number($country_code, $phone_number, $update_id = '')
	{
		$this->db->select('*');
		$this->db->from('leads');
		$this->db->join('users', 'users.user_id = leads.allocation_id', 'left outer');
		$this->db->where('country_code', $country_code);
		$this->db->where('phone', $phone_number);
		
		if(!empty($update_id))
			$this->db->where('lead_id !=', $update_id);
		
		$query = $this->db->get();
		return $query->row();
	}
	
	function crm_user_list($current_user_id = '', $current_role_id = '', $current_team_id = '')
	{
		$this->db->select('*');
		$this->db->from('users');
		
		if($current_role_id == 7)
		{
			$team_ids = explode(',', $current_team_id);
			
			$this->db->group_start();
	
			foreach ($team_ids as $team_id) {
				$this->db->or_where("FIND_IN_SET('$team_id', users.team_id)");
			}
			$this->db->group_end();
			
			$this->db->group_start();
			$this->db->where('users.role_id !=', 7);
			$this->db->or_where('users.user_id', $current_user_id);
			$this->db->group_end();
		}
		
		else if($current_role_id == 8)
		{
			$this->db->where('users.user_id', $current_user_id);
		}
		else
		{
			$this->db->where_in('role_id', array(1,7,8));
		}
		
		$this->db->where('users.status', 1);
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}
	
	function crm_shift_user_list()
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('users.status', 1);
		$this->db->where_in('role_id', array(1,7,8));
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}
	
	//Leads Import
	function leads_import_list()
	{
		$this->db->select('temp_leads.*, temp_lead_details.*, temp_leads.lead_id as main_lead_id, temp_leads.created_on as lead_create_date, projects.project_name, users.fullname');
		$this->db->from('temp_leads');
		$this->db->join('temp_lead_details', 'temp_lead_details.lead_id = temp_leads.lead_id AND temp_lead_details.lead_detail_id = (SELECT MAX(ld.lead_detail_id) FROM temp_lead_details AS ld WHERE ld.lead_id = temp_leads.lead_id)', 'left');
		$this->db->join('projects', 'projects.project_id = temp_leads.project_id', 'left outer');
		$this->db->join('users', 'users.user_id = temp_leads.allocation_id', 'left outer');
		
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return array();
	}

	//Receipt Details
	function receipt_detail_list($receipt_id)
	{
		$this->db->select('lead_receipts.*, email_address, city, project_name');
		$this->db->from('lead_receipts');
		$this->db->where('lead_receipts.receipt_id', $receipt_id);
		$this->db->join('leads', 'leads.lead_id = lead_receipts.lead_id', 'left');
		$this->db->join('projects', 'projects.project_id = lead_receipts.project_id', 'left outer');
		//$this->db->join('users', 'users.user_id = temp_leads.allocation_id', 'left outer');
		
		$query = $this->db->get();
		return $query->row();
	}
	
	function projects_list()
	{
		$this->db->select('projects.*');
		$this->db->from('projects');
		$this->db->join('inventories', 'inventories.project_id = projects.project_id', 'left');
		$this->db->group_by('project_id');

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return array();
	}
}
?>