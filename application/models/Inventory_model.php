<?php class Inventory_model extends CI_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();				
    }
	
	function inventory_detail_list($slug_url)
	{
		$this->db->select('*, inventories.project_id as inventory_project_id, inventories.created_on as inventory_create_date');
		$this->db->from('inventories');
		$this->db->join('projects', 'projects.project_id = inventories.project_id', 'left outer');
		$this->db->where('inventories.inventory_id', $slug_url);
		$this->db->where('inventories.post_status', 1);
		
		$query = $this->db->get();
		return $query->row();
	}
	
    function inventory_installment_list($slug_url)
	{
		$this->db->select('*, inventory_installments.date as inventory_date');
		$this->db->from('inventory_installments');
		$this->db->where('inventory_installments.inventory_id', $slug_url);
		$this->db->where('inventory_installments.post_status', 1);
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}
	
    function inventory_milestone_list($slug_url)
	{
		$this->db->select('inventory_milestones.*, milestone_name');
        $this->db->join('project_details', 'project_details.project_detail_id = inventory_milestones.project_milestone_id', 'left outer');
		$this->db->from('inventory_milestones');
		$this->db->where('inventory_milestones.inventory_id', $slug_url);
		$this->db->where('inventory_milestones.post_status', 1);
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}
	
    function total_unit_price($inventory_id)
	{
		$this->db->select('total_price');
		$this->db->from('inventories');
		$this->db->where('inventories.inventory_id', $inventory_id);
		
		$query = $this->db->get();
        $row = $query->row();

        if ($row && !empty($row->total_price)) {
            return $row->total_price;
        }
        
        return '';
	}

	public function get_where($table, $where = array(), $return_type = 'object', $limit = null, $offset = null, $order_by = null, $order = 'ASC') {
        // Select all columns by default
        $this->db->select('*');
        $this->db->from($table);
        
        // Apply where conditions if provided
        if (!empty($where)) {
            $this->db->where($where);
        }
        
        // Apply order by if provided
        if ($order_by !== null) {
            $this->db->order_by($order_by, $order);
        }
        
        // Apply limit/offset if provided
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        // Execute query and return results
        $query = $this->db->get();
        
        if ($return_type == 'array') {
            return $query->result_array();
        } else {
            return $query->result();
        }
    }
}
?>