<?php class Chart_of_account_model extends CI_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();				
    }
	
	function chart_of_account_parent_list($account_level)
	{
		$this->db->select('*');
		$this->db->from('chart_of_accounts');
		$this->db->where('chart_of_accounts.account_level', $account_level);
		$this->db->where('chart_of_accounts.status', 1);
		
		$query = $this->db->get();
		if( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		return array();
	}
	
	function coa_level4_list()
	{
		$this->db->select('*');
		$this->db->from('chart_of_accounts');
		$this->db->where('account_level', 4);
		$this->db->where('status', 1);
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}
	
	function check_coa_validation($project_id = '', $coa = '', $level = '', $update_id = '')
	{
		$this->db->select('*');
		$this->db->from('chart_of_accounts');
		$this->db->where('project_id', $project_id);
		$this->db->where('account_title', $coa);
		$this->db->where('account_level', $level);
		$this->db->where('post_status', 1);
		
		if (!empty($update_id)) {
			$this->db->where('chart_of_account_id !=', $update_id);
		}
		
		$query = $this->db->get();
		return $query->row();
	}
	
	function check_coa2_validation($project_id = '', $coa1 = '', $coa2 = '', $level = '', $update_id = '')
	{
		$this->db->select('*');
		$this->db->from('chart_of_accounts');
		$this->db->where('project_id', $project_id);
		$this->db->where('parent_id', $coa1);
		$this->db->where('account_title', $coa2);
		$this->db->where('account_level', $level);
		$this->db->where('post_status', 1);
		
		if (!empty($update_id)) {
			$this->db->where('chart_of_account_id !=', $update_id);
		}
		
		$query = $this->db->get();
		return $query->row();
	}
	
	function check_coa3_validation($project_id = '', $level_1_code = '', $level_2_code = '', $coa3 = '', $level = '', $update_id = '')
	{
		$this->db->select('*');
		$this->db->from('chart_of_accounts');
		$this->db->where('project_id', $project_id);
		$this->db->where('level_1_code', $level_1_code);
		$this->db->where('level_2_code', $level_2_code);
		$this->db->where('account_level', $level);
		$this->db->where('post_status', 1);

		// 🚀 Check for duplicate level_3_code
		$this->db->where('level_3_code', $coa3);

		if (!empty($update_id)) {
			$this->db->where('chart_of_account_id !=', $update_id);
		}

		$query = $this->db->get();
		return $query->row();
	}

	function check_coa4_validation($project_id = '', $level_1_code = '', $level_2_code = '', $level_3_code = '', $coa4 = '', $level = '', $update_id = '')
	{
		$this->db->select('*');
		$this->db->from('chart_of_accounts');
		$this->db->where('project_id', $project_id);
		$this->db->where('level_1_code', $level_1_code);
		$this->db->where('level_2_code', $level_2_code);
		$this->db->where('level_3_code', $level_3_code);
		$this->db->where('account_title', $coa4);
		$this->db->where('account_level', $level);
		$this->db->where('post_status', 1);
		
		if (!empty($update_id)) {
			$this->db->where('chart_of_account_id !=', $update_id);
		}
		
		$query = $this->db->get();
		return $query->row();
	}

	function update_check_validation($update_id)
	{
		$this->db->select('level_1_code, level_2_code, level_3_code');
		$this->db->from('chart_of_accounts');
		$this->db->where('chart_of_account_id', $update_id);
		$this->db->where('post_status', 1);
		
		$query = $this->db->get();
		return $query->row();
	}

}
?>