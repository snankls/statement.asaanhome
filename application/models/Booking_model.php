<?php class Booking_model extends CI_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();				
    }
	
	function booking_detail_list($slug_url)
	{
		$this->db->select('*, bookings.project_id as booking_project_id, inventories.inventory_id as inventory_inventory_id, bookings.image as booking_image, bookings.image as booking_image');
		$this->db->from('bookings');
		$this->db->join('projects', 'projects.project_id = bookings.project_id', 'left outer');
		$this->db->join('inventories', 'inventories.inventory_id = bookings.inventory_id', 'left outer');
		$this->db->where('bookings.booking_id', $slug_url);
		$this->db->where('inventories.post_status', 1);
		$this->db->group_by('bookings.booking_id');
		
		$query = $this->db->get();
		//print_r($this->db->last_query());
		return $query->row();
	}
	
	function get_inventory_project_lists($record_id = '', $session_project_id = '')
	{
		$this->db->select('*');
		$this->db->from('inventories');
		$this->db->join('projects', 'projects.project_id = inventories.project_id', 'left outer');
		
		if (!empty($session_project_id)) {
			if (is_string($session_project_id)) {
				// Convert comma-separated string to array
				$session_project_id = explode(',', $session_project_id);
			}
			$this->db->where_in('inventories.project_id', $session_project_id);
		}
		
		$this->db->where('inventories.post_status', 1);
		$this->db->group_by('projects.project_id');
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}
	
    function booking_installment_list($project_id)
	{
		$this->db->select('payment_plan');
		$this->db->from('projects');
		$this->db->where('projects.project_id', $project_id);
		
		$query = $this->db->get();
		return $query->row();
	}
	
	function booking_paid_installment_list($slug_url)
	{
		$this->db->select('*');
		$this->db->from('booking_amounts');
		$this->db->where('booking_amounts.booking_id', $slug_url);
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}
	
	function inventory_installment_list($slug_url)
	{
		$this->db->select('inventory_installments.*, date as inventory_date');
		$this->db->from('inventory_installments');
		$this->db->where('inventory_id', $slug_url);
		$this->db->where('post_status', 1);
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}
	
    function inventory_milestone_installment_list($slug_url)
	{
		$this->db->select('
			inventory_milestones.*,
			project_details.milestone_name,
			project_details.achievement,
			project_details.achievement_date
		');
		$this->db->from('inventory_milestones');
		$this->db->join('project_details', 'project_details.project_detail_id = inventory_milestones.project_milestone_id', 'left');
		$this->db->where('inventory_milestones.inventory_id', $slug_url);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return array();
	}
	
	function total_paid_amount($booking_id)
	{
		$this->db->select('inventory_id, SUM(amount) as paid_amount');
		$this->db->from('booking_amounts');
		$this->db->where('booking_amounts.booking_id', $booking_id);
		
		$query = $this->db->get();
		return $query->row();
	}
	
    function paid_inventory_installment_list($slug_url)
	{
		$this->db->select('amount');
		$this->db->from('inventory_installments');
		$this->db->where('inventory_installments.inventory_id', $slug_url);
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}
	
    function paid_inventory_milestone_installment_list($slug_url)
	{
		$this->db->select('amount');
		$this->db->from('inventory_milestones');
		$this->db->where('inventory_milestones.inventory_id', $slug_url);
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}
	
	function booking_challan_list($slug_url)
	{
		$this->db->select('*');
		$this->db->from('challans');
		$this->db->where('challans.booking_id', $slug_url);
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}

	// public function booking_challan_list($slug_url) {
	// 	$this->db->select('*');
	// 	$this->db->from('challans');
	// 	$this->db->join('bookings', 'bookings.booking_id = challans.booking_id');
	// 	$this->db->where('bookings.slug_url', $slug_url);
	// 	$this->db->order_by('challans.challan_date', 'ASC');
	// 	return $this->db->get()->result();
	// }

	// public function inventory_installment_list($inventory_id) {
	// 	$this->db->select('*');
	// 	$this->db->from('inventory_installments');
	// 	$this->db->where('inventory_id', $inventory_id);
	// 	$this->db->order_by('date', 'ASC');
	// 	return $this->db->get()->result();
	// }
	
	function booking_search($project='', $property_type='', $unit_number='', $registration='', $from_date='', $to_date='')
    {
		$this->db->select('
			bookings.*,
			(
				SELECT bii.amount
				FROM inventory_installments bii
				WHERE bii.inventory_id = bookings.inventory_id
				ORDER BY bii.inventory_id ASC
				LIMIT 1
			) AS first_booking_amount,
			(
				SELECT SUM(ii.amount)
				FROM inventory_installments ii
				WHERE ii.inventory_id = bookings.inventory_id
				AND ii.date <= CURDATE()
			) as due_amount,
			(
				SELECT SUM(ba.amount)
				FROM booking_amounts ba
				WHERE ba.booking_id = bookings.booking_id
			) as paid_amount,
			(
				SELECT i.total_price
				FROM inventories i
				WHERE i.inventory_id = bookings.inventory_id
			) as total_price,
			inventories.inventory_id as inventory_inventory_id,
			bookings.inventory_id as booking_inventory_id,
			projects.project_name,
			bookings.created_on as create_date,
		');
		$this->db->from('bookings');
		$this->db->join('projects', 'projects.project_id = bookings.project_id', 'left outer');
		//$this->db->join('booking_amounts', 'booking_amounts.booking_id = bookings.booking_id', 'left outer');
		$this->db->join('inventories', 'inventories.project_id = bookings.project_id', 'left outer');
		
		if(!empty($unit_number))
		$this->db->where('bookings.unit_number', $unit_number);
		
		if(!empty($project))
		$this->db->where('bookings.project_id', $project);
		
		if(!empty($property_type))
		$this->db->where('bookings.property_type', $property_type, 'after');
		
		if(!empty($unit_number))
		$this->db->where('bookings.unit_number', $unit_number, 'after');
		
		if(!empty($registration))
		$this->db->where('bookings.registration', $registration, 'after');
		
		if(!empty($from_date) AND !empty($to_date))
		$this->db->where('( bookings.created_on >= '.$from_date.' AND bookings.created_on <= '.$to_date.')');
		
		$this->db->order_by('bookings.booking_id', 'desc');
		$this->db->group_by('bookings.booking_id');
		
		$query = $this->db->get();
		if( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		return array();
    }
	
    function booking_duesurcharge_list($booking_id)
	{
		$this->db->select('*');
		$this->db->from('due_surcharges');
		$this->db->where('booking_id', $booking_id);
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}
	
    function booking_duesurcharge_waive_off($booking_id)
	{
		$this->db->select('SUM(amount) as waive_off_amount');
		$this->db->from('due_surcharges');
		$this->db->where('booking_id', $booking_id);
		
		$query = $this->db->get();
		return $query->row();
	}

}
?>