<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('api_model', 'api');
	}
	
	//Delete List Record
	public function list_delete()
	{
		check_login();
		$db_table = request_var('table', '');
		$db_column = request_var('column', '');

		$post_response = array();
		$record_ids = $_POST['delete_records'];

		$conditions = array(
			$db_column => explode(',', $record_ids)
		);
		$this->crud->delete_multi($db_table, $conditions);

		$post_response['success'] = true;
		$post_response['message'] = 'Selected record(s) are deleted.';

		echo json_encode($post_response);
		die();
	}
	
	//Delete Record
	public function delete()
	{
		check_login();
		$delete_id = request_var('delete_id', '');
		$db_table = request_var('db_name', '');
		$primary_key = request_var('primary_key', '');
		$check_db_table = request_var('check_db_table', array());
		$related_table = request_var('related_table', array());
		
		foreach($check_db_table as $label => $table)
		{
			$check = $this->crud->detail_list($delete_id, $table, $primary_key);
			if(!empty($check))
			{
				out ('ERROR', "This id using another table.");
				return false;
			} else {
				$this->crud->delete($delete_id, $db_table, $primary_key);
			}
		}
		out ('SUCCESS', 'Removed.');
	}

	//Delete Single
	public function delete_single()
	{
		check_login();
		
		$id = request_var('id');
		$db_table = request_var('db_table');
		$primary_id = request_var('primary_id');
		$this->crud->delete($id, $db_table, $primary_id);
		
		out ('SUCCESS', 'Removed.');
	}

	//Parent & Details
	public function delete_list()
	{
		check_login();

		$deleteIds = request_var('delete_Ids');
		$db_table = request_var('db_table');
		$primary_id = request_var('primary_id');
		
		// Make sure $deleteIds is an array
		if (is_array($deleteIds)) {
			foreach($deleteIds as $id) {
				$data = array('post_status' => 0);
				$this->crud->delete_rows($data, $id, $db_table, $primary_id);
			}
			out('SUCCESS', 'Removed.');
		} else {
			out('ERROR', 'No records selected.');
		}
	}

	//Parent & Details
	public function delete_and_details()
	{
		check_login();
		
		$deleteIds = request_var('delete_Ids');
		$db_table = request_var('db_table');
		$primary_id = request_var('primary_id');
		$db_details = request_var('db_details');

		foreach($deleteIds as $id) {
			$data = array('status' => 0);

			// Update main table
			$this->crud->delete_rows($data, $id, $db_table, $primary_id);
			// Update details table
			$this->crud->delete_rows($data, $id, $db_details, $primary_id);
		}
		
		out ('SUCCESS', 'Removed.');
	}
	
	//Property Types
	public function get_property_name_json()
	{
		check_login('yes');
		$project_id = request_var('project_id', '');
		
		$response = array(
			'dropdown_options' => $this->api->get_property_name($project_id)
		);
		echo json_encode($response);
	}
	
	//Property Types
	public function get_booking_property_name_json()
	{
		check_login('yes');
		$project_id = request_var('project_id', '');
		$property_type = request_var('property_type', '');
		
		$response = array(
			'dropdown_options' => $this->api->get_booking_property_name($project_id, $property_type)
		);
		echo json_encode($response);
	}
	
	//Booking Search Property Types
	public function get_booking_search_property_name_json()
	{
		check_login('yes');
		$project_id = request_var('project_id', '');
		
		$response = array(
			'dropdown_options' => $this->api->get_booking_search_property_name($project_id)
		);
		echo json_encode($response);
	}
	
	//Unit
	public function get_unit_number_json()
	{
		check_login('yes');
		$booking_id = request_var('booking_id', '');
		$project_id = request_var('project_id', '');
		$property_type_id = request_var('property_type_id', '');
		$inventory_id = request_var('inventory_id', '');
		
		$response = array(
			'dropdown_options' => $this->api->get_unit_number($project_id, $property_type_id, $booking_id, $inventory_id)
		);
		echo json_encode($response);
	}
	
	//Search Unit
	public function get_search_unit_number_json()
	{
		check_login('yes');
		$project_id = request_var('project_id', '');
		$property_type_id = request_var('property_type_id', '');
		
		$response = array(
			'dropdown_options' => $this->api->get_search_unit_number($project_id, $property_type_id)
		);
		echo json_encode($response);
	}
	
	//COA 1
	public function get_coa_1_name_json()
	{
		check_login('yes');
		
		$project_id = request_var('project_id', '');
		$response = array(
			'dropdown_options' => $this->api->get_coa_1_name($project_id)
		);
		echo json_encode($response);
	}
	
	//COA 2
	public function get_coa_2_name_json()
	{
		check_login('yes');
		
		$coa_1_id = request_var('coa_1_id', '');
		$response = array(
			'dropdown_options' => $this->api->get_coa_2_name($coa_1_id)
		);
		echo json_encode($response);
	}
	
	//COA 3
	public function get_coa_3_name_json()
	{
		check_login('yes');
		
		$coa_2_id = request_var('coa_2_id', '');
		$response = array(
			'dropdown_options' => $this->api->get_coa_3_name($coa_2_id)
		);
		echo json_encode($response);
	}
	
	//COA 4
	public function get_coa_4_name_json()
	{
		check_login('yes');
		
		$project_id = request_var('project_id', '');
		$dropdown_options = $this->api->get_coa_4_name($project_id);
		
		$response = array(
			'dropdown_options' => $this->api->get_coa_4_name($project_id)
		);
		echo json_encode($response);
	}
	
	//Finance Ledger Query
	public function get_finance_query_json()
	{
		check_login('yes');
		
		$project_id = request_var('project_id', '');
		
		$response = array(
			'dropdown_options' => $this->api->get_coa_4_name($project_id)
		);
		echo json_encode($response);
	}
}
?>
