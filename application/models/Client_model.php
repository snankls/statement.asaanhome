<?php class Client_model extends CI_Model {
	
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
}
?>