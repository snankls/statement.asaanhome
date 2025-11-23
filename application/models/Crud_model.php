<?php class Crud_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();				
    }
	
	function add($data, $db_table)
    {
        $this->db->insert($db_table, $data);
		return $this->db->insert_id();
    }
	
	function add_batch($data, $db_table)
    {
	    $this->db->insert_batch($db_table, $data);
	    return true;
    }

	function data_list($db_table, $primary_id)
	{
		$this->db->select('*, '.$db_table.'.created_on as create_date');
		$this->db->from($db_table);
		$this->db->join('users', 'users.user_id = '.$db_table.'.created_by_id', 'left outer');
		$this->db->order_by($primary_id, 'asc');
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}

	function detail_list($id, $db_table, $primary_id, $return_array=false)
	{
		$this->db->select('*');
		$this->db->from($db_table);
		$this->db->where($primary_id, $id);

		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			if($return_array)
				return $query->result_array();
			return $query->result();
		}
		return array();
	}

	function all_list($db_table, $status = '')
	{
		$this->db->select('*');
		$this->db->from($db_table);
		
		if(!empty($status))
		$this->db->where('status', $status);

		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}

	function all_list_sort($db_table, $order, $session_project_id='')
	{
		$this->db->select('*');
		$this->db->from($db_table);
		
		if (!empty($session_project_id)) {
			$session_project_id_array = explode(',', $session_project_id);
			$session_project_id_array = array_map('intval', $session_project_id_array);
			$this->db->where_in($db_table . '.project_id', $session_project_id_array);
		}

		$this->db->where('post_status', '1');
		$this->db->order_by($order, 'asc');
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}
	
	function single($id, $db_table, $primary_id, $return_array=false)
	{
		$this->db->select('*');
		$this->db->from($db_table);
		$this->db->where($primary_id, $id);
		
		$query = $this->db->get();
		if($return_array)
			return $query->row_array();

		return $query->row();
	}
	
	function single_in($id, $db_table, $primary_id, $return_array=false)
	{
		$this->db->select('*');
		$this->db->from($db_table);
		$this->db->where("$primary_id IN (".$id.")",NULL, false);
		
		$query = $this->db->get();
		if($return_array)
			return $query->row_array();

		return $query->result();
	}
	
	function update($data, $update_id, $db_table, $primary_id)
    {
		$this->db->where($primary_id, $update_id);
		$this->db->update($db_table, $data);
		
		$updated_status = $this->db->affected_rows();
		if($updated_status):
			return $update_id;
		else:
			return false;
		endif;
	}
	
	function user_update($data, $update_id, $db_table, $primary_id)
    {
		$this->db->where($primary_id, $update_id);
		$this->db->update($db_table, $data);
		
		$updated_status = $this->db->affected_rows();
		if($updated_status):
			return $update_id;
		else:
			return false;
		endif;
	}

    function update_where($db_table, $conditions=array(), $data=array())
    {
    	foreach($conditions as $c){
			$this->db->where($c);
		}
		$this->db->update($db_table, $data);
    }
	
	function delete($delete_id, $db_table, $primary_id)
	{
		$this->db->where($primary_id, $delete_id);
		$this->db->delete($db_table);
	}
	
	function delete_section($section_id, $slug_id, $db_table, $primary_id)
	{
		$this->db->where($primary_id, $slug_id);
		$this->db->where('section_id', $section_id);
		$this->db->delete($db_table);
	}
	
	function delete_batch($delete_id, $db_table, $primary_id)
	{
		$this->db->where_in($primary_id, $delete_id);
		$this->db->delete($db_table);
	}

	function delete_multi($db_table, $conditions_array)
	{
		foreach($conditions_array as $k => $v)
		{
			if(!is_array($v))
				$v = array($v);

			$this->db->where_in($k, $v);
		}
		$this->db->delete($db_table);
	}
	
	function delete_rows($data, $delete_id, $db_table, $primary_id)
	{
		$this->db->where($primary_id, $delete_id);
		$this->db->update($db_table, $data);
	}
	
	function get_total_records_count($db_table, $conditions)
	{
		$this->db->select('COUNT(*) AS numrows');
		$this->db->from($db_table);
		
		$this->db->join('lead_details', 'lead_details.lead_id = leads.lead_id', 'left');
	
		$this->db->join('projects', 'projects.project_id = leads.project_id', 'left outer');
		$this->db->join('users', 'users.user_id = leads.allocation_id', 'left outer');
	
		// Apply conditions
		foreach ($conditions as $condition) {
			if (isset($condition['column'])) {
				if (isset($condition['operator']) && $condition['operator'] === 'WHERE') {
					$this->db->where($condition['column'], $condition['value']);
				}
			}
		}
	
		$query = $this->db->get();
		return $query->row()->numrows;
	}
	
	function get_records($args=array()){

		$table = $args['table'];
		$select_columns = $args['columns'];
		$conditions_array = isset($args['conditions']) ? $args['conditions']:array();
		$joins = isset($args['joins']) ? $args['joins']:array();
		$order_by = isset($args['order_by']) ? $args['order_by']:array();
		$results_in_array = isset($args['results_in_array']) ? $args['results_in_array']:false;

		$this->db->select($select_columns);
		$this->db->from($table);
		foreach ($joins as $join){
			$this->db->join($join['table'], $join['columns'], $join['type']);
		}

		foreach($conditions_array as $k => $v)
		{
			if(!is_array($v))
				$v = array($v);

			$this->db->where_in($k, $v);
		}

		foreach ($order_by as $c){
			$this->db->order_by($c['column'], $c['dir']);
		}

		$query = $this->db->get();

		if($results_in_array)
			return $query->result_array();
		else
			return $query->result();
	}

	function table_total($table)
	{
		$this->db->select('*');
		$this->db->from($table);
		return $this->db->count_all_results();
	}
	
	function table_query($table, $select_columns, $joins=array(), $conditions=array(), $custom_condition='')
	{
		$this->db->select($select_columns);
		$this->db->from($table);
		foreach ($joins as $join){
			$this->db->join($join['table'], $join['columns'], $join['type']);
		}

		if(isset($_POST["search"]["value"]))
		{
			$count=0;
			foreach ($_POST['columns'] as $c)
			{
				if($c['searchable'] !== 'false' && $_POST["search"]["value"] != '')
				{
					if($count == 0)
						$this->db->like($c['data'], $_POST["search"]["value"]);
					else
						$this->db->or_like($c['data'], $_POST["search"]["value"]);

					$count++;
				}

				$filter_key = $c['data']."_filter";
				if(isset($_POST[$filter_key]) && $_POST[$filter_key] != ''){
					$this->db->like($c['data'], $_POST[$filter_key]);
				}

			}
		}

		foreach ($conditions as $c){
			$this->db->{$c['operator']}($c['column'], $c['value']);
		}

		if(!empty($custom_condition)){
			$this->db->where($custom_condition);
		}
	}
	
	function datatable_data($db_table, $table_columns='*', $joins='', $conditions=array(), $custom_condition='', $limit='', $offset='')
	{
		// Get pagination parameters
		$start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
		$length = isset($_POST['length']) ? (int)$_POST['length'] : -1;
		
		$table_data = array(
			"start" => isset($_POST['start']) ? $_POST['start']:0,
			"length" => isset($_POST['length']) ? $_POST['length']: -1,
			"draw" => isset($_POST['draw']) ? $_POST['draw']:1,
			"recordsTotal" => $this->table_total($db_table),
			"recordsFiltered" => 0,
			"data" => array(
				/*0 => array(
					"DT_RowId" => 1,
					"checkbox" => "",
					"brand_name" => "Angelica",
					"industries_name" => "Angelica",
					"countryName" => "Angelica",
					"mobile_number" => "Angelica",
					"email_address" => "Angelica",
					"website" => "Angelica",
					"create_date" => "Angelica",
					"actions" => ""
				)*/
			)
		);
		
		$this->table_query($db_table, $table_columns, $joins, $conditions, $custom_condition);
		$query = $this->db->get();
		$table_data['recordsFiltered'] = $query->num_rows();

		$this->table_query($db_table, $table_columns, $joins, $conditions, $custom_condition);
		if($table_data['length'] !== -1)
			$this->db->limit($table_data['length'], $table_data['start']);
		
		$query = $this->db->get();
		$table_data['data'] = $query->result();
		return $table_data;
	}
	
	function complaint_json_list($db_table, $table_columns='*', $joins='', $conditions=array())
	{
		$table_data = array(
			"data" => array(
				/*0 => array(
					"DT_RowId" => 1,
					"checkbox" => "",
					"brand_name" => "Angelica",
					"industries_name" => "Angelica",
					"countryName" => "Angelica",
					"mobile_number" => "Angelica",
					"email_address" => "Angelica",
					"website" => "Angelica",
					"create_date" => "Angelica",
					"actions" => ""
				)*/
			)
		);
		
		$this->table_query($db_table, $table_columns, $joins, $conditions);

		$query = $this->db->get();
		return $query->result_array();
	}

	//Latest List
	function latest_list($db_table, $primary_id, $limit)
	{
		$this->db->select('*, '.$db_table.'.created_on as create_date');
		$this->db->from($db_table);
		$this->db->join('ci_user', 'ci_user.user_id = '.$db_table.'.current_user_id', 'left outer');
		$this->db->order_by($primary_id, 'desc');
		
		if(!empty($limit))
		$this->db->limit($limit);
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}
		return array();
	}

	public function all_records_comma_separated($table, $column)
	{
		$this->db->select("GROUP_CONCAT($column) as records");
		$this->db->from($table);

		$query = $this->db->get();
		$row = $query->row();
		return $row->records;
	}
	
	public function single_value($value)
	{
		$this->db->select("detail_value");
		$this->db->from("system_setting");
		$this->db->where("detail_key", $value);
		
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$row = $query->row();
			return $row->detail_value;
		} else {
			return null;
		}
	}

	public function check_unit_validation($project_id, $unit_number, $db_table, $inventory_main_id = '') 
	{
		$this->db->select('inventory_id');
		$this->db->from($db_table);
		$this->db->where('project_id', $project_id);
		$this->db->where('unit_number', $unit_number);
		$this->db->where('post_status', 1);
		
		// Ignore the current record during validation
		if (!empty($inventory_main_id)) {
			$this->db->where('inventory_id !=', $inventory_main_id);
		}
		
		$query = $this->db->get();
		return $query->num_rows() > 0;
	}

	public function check_floor_validation($project_id, $floor_block, $unit_number, $db_table, $inventory_main_id = '') 
	{
		$this->db->select('inventory_id');
		$this->db->from($db_table);
		$this->db->where('project_id', $project_id);
		$this->db->where('floor_block', $floor_block);
		$this->db->where('unit_number', $unit_number);
		$this->db->where('post_status', 1);
		
		// Ignore the current record during validation
		if (!empty($inventory_main_id)) {
			$this->db->where('inventory_id !=', $inventory_main_id);
		}
		
		$query = $this->db->get();
		//print_r($this->db->last_query());
		return $query->num_rows() > 0;
	}
	
	public function check_payment_plan($project_id, $db_table, $inventory_main_id = '')
	{
		$this->db->select('*');
		$this->db->from($db_table);
		$this->db->where('inventory_id', $project_id);
		
		$query = $this->db->get();
		return $query->row()->payment_plan;
	}
	
	public function get_total_leads($search_params)
	{
		$this->db->select('COUNT(DISTINCT leads.lead_id) as total_leads');
		$this->db->from('leads');
		$this->db->join('(SELECT ld.* 
						  FROM lead_details ld 
						  INNER JOIN (SELECT lead_id, MAX(lead_detail_id) as max_id 
									  FROM lead_details 
									  GROUP BY lead_id) last_ld 
						  ON ld.lead_detail_id = last_ld.max_id) as latest_lead_detail', 
						  'latest_lead_detail.lead_id = leads.lead_id', 
						  'right');
		$this->db->join('projects', 'projects.project_id = leads.project_id', 'left');
		$this->db->join('users', 'users.user_id = leads.allocation_id', 'left');
		
		//Search Filter
		if(!empty($search_params['lead_id'])) {
			$this->db->where('leads.lead_id', $search_params['lead_id']);
		}
		
		if(!empty($search_params['name'])) {
			$this->db->or_like('leads.name', $search_params['name']);
		}
		
		if(!empty($search_params['phone_number'])) {
			$this->db->or_like('leads.phone_number', $search_params['phone_number']);
		}
		
		if(!empty($search_params['task_performed'])) {
			$this->db->where('latest_lead_detail.task_performed', $search_params['task_performed']);
		}
		
		if(!empty($search_params['next_task'])) {
			$this->db->where('latest_lead_detail.next_task', $search_params['next_task']);
		}
		
		if(!empty($search_params['lead_source'])) {
			$this->db->where('leads.lead_source', $search_params['lead_source']);
		}
		
		if(!empty($search_params['status'])) {
			$this->db->where('latest_lead_detail.lead_status', $search_params['status']);
		}
		
		if(!empty($search_params['project_id'])) {
			$this->db->where('leads.project_id', $search_params['project_id']);
		}
		
		if(!empty($search_params['allocation_id'])) {
			$this->db->where('leads.allocation_id', $search_params['allocation_id']);
		}
		
		if (!empty($search_params['start_last_followup_date']) && !empty($search_params['end_last_followup_date'])) {
			$this->db->where('latest_lead_detail.last_followup_date >=', $search_params['start_last_followup_date']);
			$this->db->where('latest_lead_detail.last_followup_date <=', $search_params['end_last_followup_date']);
		}
		
		if (!empty($search_params['start_next_followup_date']) && !empty($search_params['end_next_followup_date'])) {
			$this->db->where('latest_lead_detail.next_followup_date >=', $search_params['start_next_followup_date']);
			$this->db->where('latest_lead_detail.next_followup_date <=', $search_params['end_next_followup_date']);
		}
		
		if (!empty($search_params['start_lead_added_date']) && !empty($search_params['end_lead_added_date'])) {
			$this->db->where('leads.created_on >=', $search_params['start_lead_added_date']);
			$this->db->where('leads.created_on <=', $search_params['end_lead_added_date']);
		}
		
		if($search_params['page_view'] == 'todo_list') {
			$endOfToday = strtotime('today 23:59:59');
			$this->db->where('latest_lead_detail.next_followup_date <', $endOfToday);
		}
		
		//Manager Role
		if ($search_params['current_role_id'] != 1 && $search_params['current_role_id'] == 7) {
			$team_ids = explode(',', $search_params['current_team_id']);
			
			$this->db->group_start();
			$this->db->where('leads.allocation_id', $search_params['current_user_id']);
			foreach ($team_ids as $team_id) {
				$this->db->or_where("FIND_IN_SET('$team_id', users.team_id)");
			}
			$this->db->group_end();
			
			$this->db->group_start();
			$this->db->where('users.role_id !=', 7);
			$this->db->or_where('users.user_id', $search_params['current_user_id']);
			$this->db->group_end();
		}
		
		//Individual Role
		if ($search_params['current_role_id'] != 1 && $search_params['current_role_id'] == 8) {
			$this->db->where('leads.allocation_id', $search_params['current_user_id']);
		}
		
		$query = $this->db->get();
    	return $query->row()->total_leads;
	}
	
	public function leads_activity_total_records($search_params)
	{
		$this->db->from('lead_details');
		$this->db->join('leads', 'leads.lead_id = lead_details.lead_id', 'left');
		$this->db->join('users', 'users.user_id = lead_details.created_by_id', 'left');
		
		//Search Filter
		if(!empty($search_params['task_performed'])) {
			$this->db->where('lead_details.task_performed', $search_params['task_performed']);
		}
		
		if(!empty($search_params['allocation_id'])) {
			$this->db->where('lead_details.created_by_id', $search_params['allocation_id']);
		}
		
		if (!empty($search_params['start_last_followup_date']) && !empty($search_params['end_last_followup_date'])) {
			$this->db->where('lead_details.last_followup_date >=', $search_params['start_last_followup_date']);
			$this->db->where('lead_details.last_followup_date <=', $search_params['end_last_followup_date']);
		}
		
		//Manager Role
		if ($search_params['current_role_id'] != 1 && $search_params['current_role_id'] == 7) {
			$team_ids = explode(',', $search_params['current_team_id']);
			
			$this->db->group_start();
			$this->db->where('leads.allocation_id', $search_params['current_user_id']);
			foreach ($team_ids as $team_id) {
				$this->db->or_where("FIND_IN_SET('$team_id', users.team_id)");
			}
			$this->db->group_end();
			
			$this->db->group_start();
			$this->db->where('users.role_id !=', 7);
			$this->db->or_where('users.user_id', $search_params['current_user_id']);
			$this->db->group_end();
		}
		
		//Individual Role
		if ($search_params['current_role_id'] != 1 && $search_params['current_role_id'] == 8) {
			$this->db->where('leads.allocation_id', $search_params['current_user_id']);
		}
		
		return $this->db->count_all_results();
	}

	public function dt_list($db_table, $table_columns='*', $joins='', $conditions=array(), $manager_condition='', $limit='', $offset='')
	{
		// Get pagination parameters
		$start = isset($start) ? (int)$start : 0;
		$length = isset($length) ? (int)$length : -1;
		
		$table_data = array(
			"start" => isset($start) ? $start:0,
		// 	//"length" => isset($length) ? $length: -1,
		// 	//"draw" => isset($_POST['draw']) ? $_POST['draw']:1,
		// 	//"recordsTotal" => $this->table_total($db_table),
		// 	//"recordsFiltered" => 0,
			"data" => array(
				/*0 => array(
					"DT_RowId" => 1,
					"checkbox" => "",
				)*/
			)
		);

		$this->dt_list_query($db_table, $table_columns, $joins, $conditions, $manager_condition, $limit, $start);
		
		$query = $this->db->get();
		$table_data['data'] = $query->result();
		return $table_data;
	}

	public function dt_total_count($db_table, $table_columns, $joins, $conditions, $manager_condition='', $limit, $start)
	{
		$start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
		$length = isset($_POST['length']) ? (int)$_POST['length'] : -1;
		$this->dt_list_query($db_table, $table_columns, $joins, $conditions, $manager_condition, $limit, $start);
		$query = $this->db->get();
    	return $query->row()->total_count;
	}

	function dt_list_query($table, $select_columns, $joins=array(), $conditions=array(), $manager_condition='', $limit, $start)
	{
		$this->db->select($select_columns);
		$this->db->from($table);

		//Joins
		foreach ($joins as $join){
			$this->db->join($join['table'], $join['columns'], $join['type']);
		}

		if(!empty($manager_condition['page_view']) == 'receipt')
		{
			//Manager Role
			if ($manager_condition['current_role_id'] != 1 && $manager_condition['current_role_id'] == 7) {
				$team_ids = explode(',', $manager_condition['current_team_id']);
				
				$this->db->group_start();
				$this->db->where('leads.allocation_id', $manager_condition['current_user_id']);
				foreach ($team_ids as $team_id) {
					$this->db->or_where("FIND_IN_SET('$team_id', users.team_id)");
				}
				$this->db->group_end();
				
				$this->db->group_start();
				$this->db->where('users.role_id !=', 7);
				$this->db->or_where('users.user_id', $manager_condition['current_user_id']);
				$this->db->group_end();
			}
		}

		//Conditions
		foreach ($conditions as $c){
			$this->db->{$c['operator']}($c['column'], $c['value']);
		}

		//Limit
		$this->db->limit($limit, $start);
	}
	
}
?>
