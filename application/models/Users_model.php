<?php class Users_model extends CI_Model {
	protected $_all_cols = 'user_id';
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();				
    }
	
	function user_detail_list($slug_id)
	{
		$this->db->select('*, users.project_id as user_project_id, users.created_on as create_date');
		$this->db->from('users');
		$this->db->join('roles', 'roles.role_id = users.role_id', 'left outer');
		$this->db->where('users.user_id', $slug_id);

		$query = $this->db->get();
		return $query->row();
	}
	
	function check_status($email)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where("(email = '$email' OR username = '$email')");
		
		$query = $this->db->get();
		return $query->row();
	}
	
	function check_login ( $email, $password )
	{
		$this->db->select('users.*, role');
		$this->db->from('users');
		$this->db->join('roles', 'roles.role_id = users.role_id', 'left outer');
		$this->db->where("(email = '$email' OR username = '$email')");
		$this->db->where('password', $password);
		$this->db->where('status', 1);
		
		$query = $this->db->get();
		if( $query->num_rows() > 0 )
		{
			return $query->row();
		}
		return null;
	}
	
	function update_hash ( $email, $data )
    {
		$this->db->where('email', $email);
		$this->db->update('users', $data);
    }

	function has_permission($permission_name, $permission_type)
	{
		return True;
		/*if(is_admin() or is_super_user())
			return True;*/
		return $this->check_permission($permission_name, $permission_type) > 0;
	}
	
	function user_hash($hash)
	{
		$this->db->select('hash');
		$this->db->from('users');
		$this->db->where('hash', $hash);
		$query = $this->db->get();
		return $query->row();
	}
	
	function change_password($data, $hash)
	{
		$this->db->where('hash', $hash);
		$this->db->update('users', $data);
	}
	
	function project_list($project_id)
	{
		$this->db->select('*');
		$this->db->from('projects');
		
		if(!empty($project_id)) {
			$this->db->where("project_id IN($project_id)");
		}
		$this->db->order_by('project_name', 'ASC');

		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}
	
	function check_user_validation($db_table, $username = '', $email_address = '', $slug_url = '')
	{
		$this->db->select('*');
		$this->db->from($db_table);
		$this->db->where('username', $username);
		$this->db->where('email', $email_address);
		
		if(!empty($slug_url))
			$this->db->where('user_id !=', $slug_url);
		
		$query = $this->db->get();
		return $query->row();
	}
	
	function branches_list()
	{
		$this->db->select('*');
		$this->db->from('branches');
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}
	
}
?>
