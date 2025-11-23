<?php class Collection_model extends CI_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();				
    }
	
	function challan_receipt($booking_amount_id)
	{
		$this->db->select('*');
		$this->db->from('challans');
		$this->db->join('bookings', 'bookings.booking_id = challans.booking_id', 'left outer');
		$this->db->join('projects', 'projects.project_id = bookings.project_id', 'left outer');
		$this->db->where('challans.challan_id', $booking_amount_id);
		
		$query = $this->db->get();
		return $query->row();
	}
}
?>