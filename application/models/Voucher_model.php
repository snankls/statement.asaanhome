<?php class Voucher_model extends CI_Model {
	
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();				
    }
	
	function voucher_detail_list($slug_url)
	{
		$this->db->select('*');
		$this->db->from('vouchers');
		$this->db->join('projects', 'projects.project_id = vouchers.project_id', 'left outer');
		$this->db->where('voucher_id', $slug_url);
		
		$query = $this->db->get();
		//print_r($this->db->last_query());
		return $query->row();
	}
	
	function voucher_images_list($slug_url)
	{
		$this->db->select('image_name');
		$this->db->from('images');
		$this->db->where('post_id', $slug_url);
		$this->db->where('post_name', 'vouchers');
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}
	
	function voucher_details_list($slug_url)
	{
		$this->db->select('voucher_details.*, account_title, sort_order');
		$this->db->from('voucher_details');
		$this->db->join('chart_of_accounts', 'chart_of_accounts.chart_of_account_id = voucher_details.account_number', 'left outer');
		$this->db->where('voucher_id', $slug_url);
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}
	
	function voucher_details_delete($selected_delete_ids)
	{
		$this->db->where_in('voucher_detail_id', $selected_delete_ids);
		$this->db->delete('voucher_details');
	}
}
?>