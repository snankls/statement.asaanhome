<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->model('api_model', 'api');
		$this->load->model('project_model', 'project');
	}
	
	public function client()
	{
		$data['title'] = "Client";
		$data['page'] = "client-booking/client";
		$this->load->library('Front_Layout', $data);
	}

	public function project_list($db_table='projects', $primary_id='project_id', $conditions=array(), $return_table_data=False)
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$limit = $this->input->post('length');
    	$start = $this->input->post('start');
		
		$current_role_id = $this->session->userdata('role_id');
		$current_user_id = $this->session->userdata('user_id');
		$session_project_id = $this->session->userdata('project_id');

		$joins = array(
			0 => array(
				'table' => 'users',
				'columns' => "users.user_id = $db_table.created_by_id",
				'type' => 'left outer'
			),
		);
		$table_columns = "$db_table.*, $db_table.image as project_image, $db_table.$primary_id as proj_id, $db_table.created_on as create_date, users.fullname as user_name";
		$table_count = "COUNT(DISTINCT $db_table.$primary_id) as total_count";
		
		//Where Condition
		if($current_role_id == 2 or $current_role_id == 6) {
			$project_ids = explode(',', $session_project_id);
			$conditions[] = array('operator' => 'WHERE_IN', 'column' => "$db_table.project_id", 'value' => $project_ids);
		}
		$conditions[] = array('operator' => 'WHERE', 'column' => "$db_table.post_status", 'value' => 1);

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
			$table_data['data'][$index]->DT_RowId = $rec->proj_id;
			$table_data['data'][$index]->checkbox = '<i class="fa fa-square-o"></i>';
			$table_data['data'][$index]->project_image = '<a href="'.site_url('project/view/'.$rec->proj_id).'" class="list-img">'.get_image($rec->project_image, 'projects').'</a>';
			$table_data['data'][$index]->project_name = $rec->project_name.'<div class="table-action">';
			if ($current_role_id != 6) {
				$table_data['data'][$index]->project_name .= '<a href="'.site_url('project/edit/'.$rec->proj_id).'">Edit</a> | ';
			}
			$table_data['data'][$index]->project_name .= '<a href="'.site_url('project/view/'.$rec->proj_id).'">View</a></div>';
			$table_data['data'][$index]->area_unit = area_unit($rec->area_unit);
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

	public function project_setup($record_id=0, $copy=0)
	{
		check_login();
		check_viewer_login();
		restrict_role(CRM_ROLE);
			
		$data['record'] = $this->project->project_detail_list($record_id);
		$data['page'] = "projects/project/project-setup";
		$data['title'] = ($record_id != 0) ? ($copy == 1 ? "Project Copy" : "Project Edit") : "Project Add";
		$this->load->library('Layout', $data);
	}
	
	public function project_setup_post($slug_url=0, $db_table='projects', $primary_id='project_id')
	{
		check_login();
		check_viewer_login();
		restrict_role(CRM_ROLE);
		
		$message = '';
		$form_data = get_posted_data();
		$last_uri = $form_data["last_uri"];
		$created_by_id = $this->session->userdata('user_id');
		
		if (!empty($form_data["project_image"])) {
			$upload_dir = FCPATH . 'uploads/projects/';
			$upload_url = site_url() . 'uploads/projects/';
		
			if (!is_dir($upload_dir)) {
				if (!mkdir($upload_dir, 0755, true)) {
					out('ERROR', 'Failed to create folders...');
					return;
				}
			}
		
			$config['upload_path'] = $upload_dir;
			$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
			$config['max_size'] = 5000; // in KB
		
			$this->upload->initialize($config);
		
			if (!$this->upload->do_upload('project_image')) {
				$error = $this->upload->display_errors();
				out('ERROR', $error); // Display the specific error message
				return;
			} else {
				$data = $this->upload->data();
		
				// Check if the image needs to be resized
				if ($data['image_width'] > 800 || $data['image_height'] > 800) {
					$resize_config['image_library'] = 'gd2';
					$resize_config['source_image'] = $data['full_path'];
					$resize_config['maintain_ratio'] = TRUE;
					$resize_config['width'] = 800;
					$resize_config['height'] = 800;
		
					$this->load->library('image_lib', $resize_config);
		
					if (!$this->image_lib->resize()) {
						$error = $this->image_lib->display_errors();
						out('ERROR', $error); // Display the specific error message
						return;
					}
		
					// Clear image_lib settings to avoid conflicts
					$this->image_lib->clear();
				}
		
				$image = $data['file_name'];
			}
		} else {
			$image = isset($form_data["update_project_image"]) ? $form_data["update_project_image"] : '';
		}
		
		$property_types	= $form_data["property_types"];
		$property_types	= sprintf('%s', implode(",", $property_types));
		$area_unit		= isset($form_data["area_unit"]) ? $form_data["area_unit"] : '';
		$city			= isset($form_data["city"]) ? $form_data["city"] : '';
		$description	= isset($form_data["description"]) ? $form_data["description"] : '';
		
		$data = array(
			'project_name' => $form_data["project_name"],
			'property_types' => $property_types,
			'area_unit' => $area_unit,
			'project_city' => $city,
			'description' => $description,
			'image' => $image,
		);
		
		if($slug_url == 0)
		{
			$d = array(
				'created_by_id' => $created_by_id,
				'created_on' => time(),
			);
			$data = array_merge($data, $d);
			$id = $this->crud->add($data, $db_table);
			
			$message = 'Record added successfully.';
		}
		else
		{
			$d = array(
				'updated_by_id' => $created_by_id,
				'updated_on' => time(),
			);
			$data = array_merge($data, $d);
			$this->crud->update($data, $slug_url, $db_table, $primary_id);
			
			//Add Log History
			log_history($db_table, $slug_url);

			$message = 'Record updated successfully.';
		}
		
		out_json( array(
			'success' => 1,
			'message' => $message,
			'RedirectTo' => site_url('project'),
		));
	}

	public function project_view($slug_url=0, $return_data=0)
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$data['record_list'] = $this->project->project_detail_list($slug_url);
		$data['title'] = "Project View";
		$data['page'] = "projects/project/project-view";

		if($return_data)
			return $data;

		$this->load->library('Layout', $data);
	}

	public function get_view_actions($permission_for = 'project')
	{
		check_login();
		check_viewer_login();
		restrict_role(CRM_ROLE);
		
		$response = array(
			'view_actions' => ''
		);

		$page = request_var('page', '');
		$response['page'] = $page;
		$response['record_id'] = request_var('record_id', 0);
		$response['tpl_data'] = array(
			'log_id' => $response['record_id'],
			'add_url' => check_permission('Add', $permission_for, false) ? site_url("project/add"):false,
			'edit_url' => check_permission('Edit', $permission_for, false) ? site_url("project/edit/$response[record_id]"):false,
			'view_url' => check_permission('View', $permission_for, false) ? site_url("project/view/$response[record_id]"):false,
			'print_url' => check_permission('Print', $permission_for, false) ? site_url("project/print/$response[record_id]"):false,
			'list_url' => check_permission('List', $permission_for, false) ? site_url("project"):false,
			'log_table' => check_permission('Log', $permission_for, false) ? "projects":false
		);
		$response['view_actions'] = $this->parser->parse('ajax/view-actions', $response['tpl_data'], TRUE);

		out_json($response);
	}
		
}
