<?php class Client_model extends CI_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();				
    }
	
	function get_user_statement($registration, $cnic)
	{
		$this->db->select('booking_id, registration, cnic');
		$this->db->from('bookings');
		$this->db->where('registration', $registration);
		$this->db->where('cnic', $cnic);
		
		$query = $this->db->get();
		return $query->row();
	}

}
?>