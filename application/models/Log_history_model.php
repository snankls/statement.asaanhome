<?php class Log_history_model extends CI_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();				
    }
	
	function add( $data )
    {
        $this->db->insert('log_histories', $data);
		return $this->db->insert_id();
    }
	
	function log_list($db_name, $db_value)
    {
        $this->db->select('log_histories.table_name, log_histories.table_value, log_histories.created_by_id, log_histories.created_on, users.fullname, users.user_id');
		$this->db->from('log_histories');
		$this->db->join('users', 'users.user_id = log_histories.created_by_id');
		$this->db->where('table_name', $db_name);
		$this->db->where('table_value', $db_value);
		$this->db->order_by('log_id', 'desc');
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
    }
}
?>
