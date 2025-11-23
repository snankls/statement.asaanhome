<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Collection extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->model('collection_model', 'collection');
	}
	
	public function collection()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$data['title'] = "Collection";
		$data['page'] = "projects/collection/collection";
		$this->load->library('Layout', $data);
	}
	
	public function collection_list($db_table='challans', $primary_id='challan_id', $conditions=array(), $return_table_data=False)
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$limit = $this->input->post('length');
    	$start = $this->input->post('start');
		
		$current_role_id = $this->session->userdata('role_id');
		$session_project_id = $this->session->userdata('project_id');
		$joins = array(
			0 => array(
				'table' => 'bookings',
				'columns' => "bookings.booking_id = $db_table.booking_id",
				'type' => 'left outer'
			),
			1 => array(
				'table' => 'projects',
				'columns' => "projects.project_id = bookings.project_id",
				'type' => 'left outer'
			),
			2 => array(
				'table' => 'users',
				'columns' => "users.user_id = $db_table.created_by_id",
				'type' => 'left outer'
			),
		);
		$table_columns = "$db_table.*, customer_name, cnic, booking_unit, projects.project_id, $db_table.created_on as create_date, users.fullname as user_name";
		$table_count = "COUNT(DISTINCT $db_table.$primary_id) as total_count";

		//Where Condition
		if($current_role_id == 2 or $current_role_id == 6) {
			$project_ids = explode(',', $session_project_id);
			$conditions[] = array('operator' => 'WHERE_IN', 'column' => "projects.project_id", 'value' => $project_ids);
		}

		//Order By
		$conditions[] = array('operator' => 'ORDER_BY', 'column' => "$db_table.$primary_id", 'value' => 'desc');

		// Fetch records from the model (pass search_value)
		$table_data = $this->crud->dt_list($db_table, $table_columns, $joins, $conditions, $limit, $start);
		$total_records = $this->crud->dt_total_count($db_table, $table_count, $joins, $conditions, '', $limit, $start);
		
		// Prepare response (ensure keys match your column names)
		$counter = $table_data['start'];
		foreach($table_data['data'] as $index => $rec)
		{
			$counter++;
			$table_data['data'][$index]->counter = $counter;
			$table_data['data'][$index]->DT_RowId = $rec->challan_id;
			$table_data['data'][$index]->challan_date = get_date_string_sql($rec->challan_date);
			$table_data['data'][$index]->challan_amount = number_format($rec->challan_amount);
			$table_data['data'][$index]->serial = document_number(array('db_table' => 'booking_amounts', 'document_number' => $rec->serial, 'prefix' => '&nbsp;'));
			$table_data['data'][$index]->payment_method = payment_method($rec->challan_payment_method);
			$table_data['data'][$index]->challan_download = '<button type="button" class="btn btn-dark btn-small" title="Challan" onClick="challan_receipt('.$rec->challan_id.');" download><i class="fa fa-file-pdf"></i></button> <button type="button" class="btn btn-primary btn-small" title="Challan View" onClick="challan_view('.$rec->challan_id.');"><i class="fa fa-file"></i></button> <a href="'.get_image_url($rec->proof_image, 'booking_receipt').'" class="btn btn-success btn-small lightbox-image" target="_blank"><i class="fa fa-photo"></i></a>';
			$table_data['data'][$index]->create_date = $rec->user_name." <br /> ".date_only($rec->create_date);
			$rec->log_table = $db_table;
		}
		
		// JSON response to DataTables
		$response = [
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_records,
			"data" => $table_data,
		];

		echo json_encode($response);
	}
	
	public function challan_view()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$challan_id = request_var('challan_id', '');
		$data['record_list'] = $this->collection->challan_receipt($challan_id);
		$this->load->view('projects/collection/snippet/challan-view', $data);
	}
}
