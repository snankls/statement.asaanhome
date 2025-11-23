<?php class Api_model extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }
	
	//Get Document Number
	function get_document_number($db_name, $primary_key, $city_id='')
	{
		$this->db->select($primary_key);
		$this->db->from($db_name);
		$this->db->order_by($primary_key, 'desc');
		$this->db->limit(1);
		
		$query = $this->db->get();
		//print_r($this->db->last_query());
		$row = $query->row_array();
		if (isset($row[$primary_key]))
		{
			$row[$primary_key] += 1;
			return $row[$primary_key];
		}
		return 1;
	}
	
	public function get_coa_number($project_id = '', $level_1_code = '', $level_2_code = '', $level_3_code = '', $primary_key, $number_size, $account_level) {
		$this->db->from('chart_of_accounts');
		$this->db->where('project_id', $project_id);
		$this->db->where('account_level', $account_level);
		
		if ($level_1_code) {
			$this->db->where('level_1_code', $level_1_code);
		}
		
		if ($level_2_code) {
			$this->db->where('level_2_code', $level_2_code);
		}
		
		if ($level_3_code) {
			$this->db->where('level_3_code', $level_3_code);
		}

		$next_sort_order = $this->db->count_all_results();
		
		return str_pad($next_sort_order + 1, $number_size, "0", STR_PAD_LEFT);
	}
	
	//Property Types
	function get_property_name($project_id)
	{
		$this->db->select('project_id, property_types');
		$this->db->where('project_id', $project_id);
		
		$query = $this->db->get('projects');
		$output = '<option value="">Select One</option>';
		
		foreach($query->result() as $row)
		{
			$property_type_names = array();
			$property_types = explode(',', $row->property_types);
			foreach($property_types as $data) {
				$output .= '<option value="'.$data.'">'.property_types($data).'</option>';
			}
		}
		return $output;
	}
	
	//Booking Property Types
	function get_booking_property_name($project_id = '', $property_type = '')
	{
		// If editing
		if (!empty($project_id)) {
			// Edit page: include either
			// - status = 1
			// - or property_type = 5
			$this->db->group_start();
				$this->db->where('inventories.status', 1);
				$this->db->or_where('inventories.property_type', $property_type);
			$this->db->group_end();
		} else {
			// Add page: only active
			$this->db->where('inventories.status', 1);
		}

		$this->db->where('inventories.project_id', $project_id);
		//$this->db->where('inventories.status', 1);
		$this->db->join('projects', 'projects.project_id = inventories.project_id', 'left outer');
		$this->db->group_by('inventories.property_type');
		
		$query = $this->db->get('inventories');

		$output = '<option value="">Select One</option>';
		foreach($query->result() as $row)
		{
			$output .= '<option value="'.$row->property_type.'">'.property_types($row->property_type).'</option>';
		}
		return $output;
	}
	
	//Booking Property Types
	function get_booking_search_property_name($project_id)
	{
		$this->db->where('inventories.project_id', $project_id);
		$this->db->join('projects', 'projects.project_id = inventories.project_id', 'left outer');
		$this->db->group_by('inventories.property_type');
		
		$query = $this->db->get('inventories');
		$output = '<option value="">Select One</option>';
		foreach($query->result() as $row)
		{
			$output .= '<option value="'.$row->property_type.'">'.property_types($row->property_type).'</option>';
		}
		return $output;
	}
	
	//Unit Number
	function get_unit_number($project_id, $property_type_id, $booking_id='', $inventory_id='')
	{
		$this->db->select('inventories.inventory_id, inventories.unit_number, inventories.status');
		
		if (!empty($booking_id)) {
			$this->db->join('bookings', 'bookings.inventory_id = inventories.inventory_id', 'left outer');
			$this->db->where('(inventories.status = 1 OR inventories.inventory_id = (SELECT inventory_id FROM bookings WHERE booking_id = '.$booking_id.'))');
			//$this->db->where('(inventories.inventory_id = '.$inventory_id.' AND inventories.status = 2) OR inventories.status = 1 OR bookings.booking_id != '.$inventory_id);
		} else {
			$this->db->where('inventories.status', 1);
		}
		
		$this->db->where('inventories.post_status', 1);
		$this->db->where('inventories.project_id', $project_id);
		$this->db->where('inventories.property_type', $property_type_id);
		
		$query = $this->db->get('inventories');
		$output = '<option value="">Select One</option>';
		foreach ($query->result() as $row) {
			$output .= '<option value="'.$row->unit_number.'" data-inventory="'.$row->inventory_id.'">'.$row->unit_number.'</option>';
		}
		return $output;
	}
	
	//Search Unit Number
	function get_search_unit_number($project_id, $property_type_id)
	{
		$this->db->where('inventories.property_type', $property_type_id);
		$this->db->where('inventories.project_id', $project_id);
		
		$query = $this->db->get('inventories');
		$output = '<option value="">Select One</option>';
		foreach ($query->result() as $row) {
			$output .= '<option value="'.$row->unit_number.'" data-inventory="'.$row->inventory_id.'">'.$row->unit_number.'</option>';
		}
		return $output;
	}
	
	//COA 1
	function get_coa_1_name($project_id)
	{
		$this->db->where('project_id', $project_id);
		$this->db->where('account_level', 1);
		$this->db->where('status', 1);
		$this->db->where('post_status', 1);
		$this->db->order_by('account_title', 'asc');
		
		$query = $this->db->get('chart_of_accounts');
		$output = '<option value="">Select One</option>';
		foreach($query->result() as $row)
		{
			$output .= '<option value="'.$row->chart_of_account_id.'" data-order="'.$row->level_1_code.'">'.$row->account_title.'</option>';
		}
		return $output;
	}
	
	//COA 2
	function get_coa_2_name($coa_1_id)
	{
		$this->db->where('parent_id', $coa_1_id);
		$this->db->where('account_level', 2);
		$this->db->where('status', 1);
		$this->db->where('post_status', 1);
		$this->db->order_by('account_title', 'asc');
		
		$query = $this->db->get('chart_of_accounts');
		$output = '<option value="">Select One</option>';
		foreach($query->result() as $row)
		{
			$output .= '<option value="'.$row->chart_of_account_id.'" data-order="'.$row->level_2_code.'">'.$row->account_title.'</option>';
		}
		return $output;
	}
	
	//COA 3
	function get_coa_3_name($coa_2_id)
	{
		//$this->db->where('level_1_code', $coa_1_id);
		$this->db->where('parent_id', $coa_2_id);
		$this->db->where('account_level', 3);
		$this->db->where('status', 1);
		$this->db->where('post_status', 1);
		$this->db->order_by('account_title', 'asc');
		
		$query = $this->db->get('chart_of_accounts');
		$output = '<option value="">Select One</option>';
		foreach($query->result() as $row)
		{
			$output .= '<option value="'.$row->chart_of_account_id.'" data-order="'.$row->level_3_code.'">'.$row->account_title.'</option>';
		}
		return $output;
	}
	
	//COA 4
	function get_coa_4_name($project_id)
	{
		$this->db->where('project_id', $project_id);
		$this->db->where('account_level', 4);
		$this->db->where('post_status', 1);
		$this->db->order_by('account_title', 'asc');
		
		$query = $this->db->get('chart_of_accounts');
		$output = '<option value="">Select One</option>';
		foreach($query->result() as $row)
		{
			$output .= '<option value="'.$row->chart_of_account_id.'" data-order="'.$row->level_4_code.'">'.$row->account_title.'</option>';
		}
		return $output;
	}

}
?>
