<?php class Import_model extends CI_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();				
    }
	
	//Leads Import
	function leads_import_list()
	{
		$this->db->select('temp_leads.*, temp_lead_details.*, temp_leads.lead_id as main_lead_id, temp_leads.created_on as lead_create_date, projects.project_name, users.fullname');
		$this->db->from('temp_leads');
		$this->db->join('temp_lead_details', 'temp_lead_details.lead_id = temp_leads.lead_id', 'left outer');
		$this->db->join('projects', 'projects.project_id = temp_leads.project_id', 'left outer');
		$this->db->join('users', 'users.user_id = temp_leads.allocation_id', 'left outer');
		
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return array();
	}
	
	function import_followup_list($lead_id)
    {
		$this->db->select('temp_leads.*, temp_lead_details.*, temp_leads.lead_id as main_lead_id, temp_leads.created_on as lead_create_date, temp_lead_details.created_on as temp_lead_details_create_date, projects.project_name, users.fullname, u.fullname as lead_performed_by');
		$this->db->from('temp_leads');
		$this->db->join('temp_lead_details', 'temp_lead_details.lead_id = temp_leads.lead_id', 'left outer');
		$this->db->join('projects', 'projects.project_id = temp_leads.project_id', 'left outer');
		$this->db->join('users', 'users.user_id = temp_leads.allocation_id', 'left outer');
		$this->db->join('users u', 'u.user_id = temp_lead_details.created_by_id', 'left outer');
		$this->db->where('temp_leads.lead_id', $lead_id);
		$this->db->order_by('temp_lead_details.lead_detail_id', 'desc');
		
		$this->db->limit(1);
		
		$query = $this->db->get();
		if( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		return array();
    }
	
	function check_phone_number($country_code, $phone_number, $update_id = '')
	{
		$this->db->select('*');
		$this->db->from('temp_leads');
		$this->db->where('country_code', $country_code);
		$this->db->where('phone_number', $phone_number);
		
		if(!empty($update_id))
			$this->db->where('lead_id !=', $update_id);
		
		$query = $this->db->get();
		return $query->row();
	}
	
	function check_single_phone_number($id, $db_table, $primary_id, $return_array=false)
	{
		$this->db->select('*');
		$this->db->from($db_table);
		$this->db->where($primary_id, $id);
		
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row()->phone_number;
		} else {
			return null;
		}
	}
}
?>