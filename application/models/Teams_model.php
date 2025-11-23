<?php class Teams_model extends CI_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();				
    }
	
	function check_teams_validation($team_name = '', $update_id = '')
	{
		$this->db->select('*');
		$this->db->from('teams');
		$this->db->where('team_name', $team_name);
		
		if (!empty($update_id)) {
			$this->db->where('team_id !=', $update_id);
		}
		
		$query = $this->db->get();
		return $query->row();
	}
	
	function chart_of_account_parent_list($account_level)
	{
		$this->db->select('*');
		$this->db->from('teams');
		//$this->db->where('status', 1);
		
		$query = $this->db->get();
		if( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		return array();
	}
}
?>