<?php class Project_model extends CI_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();				
    }
	
	function project_detail_list($slug_url)
	{
		$this->db->select('*, projects.created_on as project_create_date');
		$this->db->from('projects');
		$this->db->where('projects.project_id', $slug_url);
		
		$query = $this->db->get();
		return $query->row();
	}
	
	function milestone_plan_list($slug_url)
	{
		$this->db->select('project_details.*, projects.milestone_status');
		$this->db->from('project_details');
		$this->db->join('projects', 'projects.project_id = project_details.project_id', 'left outer');
		$this->db->where('project_details.project_id', $slug_url);
		$this->db->order_by('sort_order', 'ASC');
		
		$query = $this->db->get();
		return $query->result();
	}
	
	function inventory_milestone_plan_list($slug_url)
	{
		$this->db->select('project_details.*, projects.milestone_status');
		$this->db->from('project_details');
		$this->db->join('projects', 'projects.project_id = project_details.project_id', 'left outer');
		$this->db->where('project_details.project_id', $slug_url);
		$this->db->where('projects.milestone_status', 'Posted');
		$this->db->order_by('sort_order', 'ASC');
		
		$query = $this->db->get();
		return $query->result();
	}
	
	function project_details_delete($selected_delete_ids)
	{
		$this->db->where_in('project_detail_id', $selected_delete_ids);
		$this->db->delete('project_details');
	}

}
?>