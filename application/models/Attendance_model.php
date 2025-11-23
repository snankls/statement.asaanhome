<?php class Attendance_model extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }

	function check_today_restrict($current_user_id, $today_date)
	{
		$today_date = date('Y-m-d');
        $this->db->select('*');
        $this->db->from('attendances');
        $this->db->where('user_id', $current_user_id);
        $this->db->where('DATE(check_in_time)', $today_date);

        $query = $this->db->get();
        return $query->row();
	}
	
	function today_attendance($current_user_id, $today_date)
	{
		$today_date = date('Y-m-d');
        $this->db->select('*');
        $this->db->from('attendances');
        $this->db->where('user_id', $current_user_id);
        $this->db->where('DATE(check_in_time)', $today_date);
        $this->db->where('check_out_time', '0000-00-00 00:00:00');

        $query = $this->db->get();
        return $query->row();
	}

    public function attendance_individual_list($limit, $start, $search_params)
	{
		$this->db->select('attendances.*, cib.name as cib_location, cob.name as cob_location, fullname');
		$this->db->from('attendances');
		$this->db->join('branches as cib', 'cib.branch_id = attendances.check_in_branch', 'left');
		$this->db->join('branches as cob', 'cob.branch_id = attendances.check_out_branch', 'left');
		$this->db->join('users', 'users.user_id = attendances.user_id', 'left');
		
		//Search Filter
		if(!empty($search_params['user_id'])) {
			$this->db->where('attendances.user_id', $search_params['user_id']);
		}
		
		if (!empty($search_params['start_date_range']) && !empty($search_params['end_date_range'])) {
			$this->db->where('attendances.check_in_time >=', $search_params['start_date_range']);
			$this->db->where('attendances.check_in_time <=', $search_params['end_date_range']);
		}
		
		//Individual Role
		if ($search_params['current_role_id'] != 1 && $search_params['current_role_id'] == 8) {
			$this->db->where('attendances.user_id', $search_params['current_user_id']);
		}

		//Manager Role
		if ($search_params['current_role_id'] != 1 && $search_params['current_role_id'] == 7) {
			$team_ids = explode(',', $search_params['current_team_id']);
			
			$this->db->group_start();
			$this->db->where('attendances.user_id', $search_params['current_user_id']);
			foreach ($team_ids as $team_id) {
				$this->db->or_where("FIND_IN_SET('$team_id', users.team_id)");
			}
			$this->db->group_end();
			
			$this->db->group_start();
			$this->db->where('users.role_id !=', 7);
			$this->db->or_where('users.user_id', $search_params['current_user_id']);
			$this->db->group_end();
		}
		else if ($search_params['current_role_id'] == 2)
		{
			$this->db->where('attendances.user_id', $search_params['current_user_id']);
		}

		$this->db->order_by('attendances.attendance_id', 'desc');
		$this->db->limit($limit, $start);
		
		$query = $this->db->get();
		//print_r($this->db->last_query());
		return $query->result();
	}

    public function get_total_records($search_params)
	{
        $this->db->select('COUNT(DISTINCT attendances.attendance_id) as total_leads');
		$this->db->from('attendances');
		$this->db->join('branches as cib', 'cib.branch_id = attendances.check_in_branch', 'left');
		$this->db->join('branches as cob', 'cob.branch_id = attendances.check_out_branch', 'left');
		
		//Search Filter
		if(!empty($search_params['user_id'])) {
			$this->db->where('attendances.user_id', $search_params['user_id']);
		}
		
		if (!empty($search_params['start_date_range']) && !empty($search_params['end_date_range'])) {
			$this->db->where('attendances.check_in_time >=', $search_params['start_date_range']);
			$this->db->where('attendances.check_in_time <=', $search_params['end_date_range']);
		}
		
		$query = $this->db->get();
    	return $query->row()->total_leads;
	}

	function user_list($current_user_id = '', $current_role_id = '', $current_team_id = '')
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
		else if($current_role_id != 1 and $current_role_id != 7)
		{
			$this->db->where('users.user_id', $current_user_id);
		}
		
		$this->db->where('users.status', 1);
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}

	/*************** Leave Application ***************/
	function leave_application_list($slug_url)
	{
		$this->db->select('*');
		$this->db->from('leave_applications');
		$this->db->where('leave_applications.application_id', $slug_url);
		
		$query = $this->db->get();
		return $query->row();
	}

}
?>
