<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		
		$this->load->model('attendance_model', 'attendance');
		$this->load->model('users_model', 'users');
	}
	
	public function attendance()
	{
		check_login();

		$current_user_id = $this->session->userdata('user_id');
		$today_date = date('Y-m-d');
		$data['attendance_list'] = $this->attendance->today_attendance($current_user_id, $today_date);
		
		$data['title'] = "Attendance";
		$data['page'] = "attendance/attendance";
		$this->load->library('Layout', $data);
	}
	
	public function check_in($db_table = 'attendances')
	{
		check_login();

		$json_input = file_get_contents('php://input');
		$data = json_decode($json_input, true);

		$latitude = (float)$data['latitude'];
		$longitude = (float)$data['longitude'];
		$current_user_id = $this->session->userdata('user_id');

		$today_date = date('Y-m-d');
		$existing_attendance = $this->attendance->check_today_restrict($current_user_id, $today_date);

		if ($existing_attendance) {
			echo json_encode(['status' => 'error', 'message' => 'Attendance already marked for today. Please check out before marking attendance again.']);
			return;
		}

		// Optional: Check for branch proximity but proceed regardless
		$branches = $this->db->get('branches')->result();
		$branch_found = null;

		foreach ($branches as $branch) {
			$branch_latitude = (float)$branch->latitude;
			$branch_longitude = (float)$branch->longitude;
			$radius_km = (float)$branch->radius / 1000;

			$distance = $this->calculate_distance($latitude, $longitude, $branch_latitude, $branch_longitude);

			if ($distance <= $radius_km) {
				$branch_found = $branch;
				break;
			}
		}

		// Set branch ID if within range, or use default (0 for untitled)
		$attendance_data = [
			'user_id' => $current_user_id,
			'check_in_branch' => $branch_found ? $branch_found->branch_id : 0,
			'check_in_time' => date('Y-m-d H:i:s'),
			'check_in_lat' => $latitude,
			'check_in_long' => $longitude,
		];
		$this->crud->add($attendance_data, $db_table);

		echo json_encode([
			'status' => 'success',
			'message' => 'Attendance marked successfully' . ($branch_found ? ' for ' . $branch_found->name : ' at an unspecified location.') 
		]);
	}

	private function calculate_distance($lat1, $lon1, $lat2, $lon2)
	{
		$earth_radius = 6371; // Earth radius in kilometers

		$dLat = deg2rad($lat2 - $lat1);
		$dLon = deg2rad($lon2 - $lon1);

		$a = sin($dLat / 2) * sin($dLat / 2) +
			cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
			sin($dLon / 2) * sin($dLon / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

		return $earth_radius * $c; // Distance in kilometers
	}

	public function check_out($db_table = 'attendances')
	{
		check_login();

		$json_input = file_get_contents('php://input');
		$data = json_decode($json_input, true);

		$latitude = (float)$data['latitude'];
		$longitude = (float)$data['longitude'];
		$current_user_id = $this->session->userdata('user_id');

		$today = date('Y-m-d');
		$existing_attendance = $this->db->get_where($db_table, [
			'user_id' => $current_user_id,
			'DATE(check_in_time)' => $today,
			'check_out_time' => '0000-00-00 00:00:00'
		])->row();

		if (!$existing_attendance) {
			echo json_encode(['status' => 'error', 'message' => 'You have not checked in today or have already checked out.']);
			return;
		}

		// Optional branch proximity check, allowing checkout regardless
		$branches = $this->db->get('branches')->result();
		$branch_found = null;

		foreach ($branches as $branch) {
			$branch_latitude = (float)$branch->latitude;
			$branch_longitude = (float)$branch->longitude;
			$radius_km = (float)$branch->radius / 1000;

			$distance = $this->calculate_distance($latitude, $longitude, $branch_latitude, $branch_longitude);

			if ($distance <= $radius_km) {
				$branch_found = $branch;
				break;
			}
		}

		// Set branch ID if within range, or use default (0 for untitled)
		$checkout_data = [
			'check_out_branch' => $branch_found ? $branch_found->branch_id : 0,
			'check_out_time' => date('Y-m-d H:i:s'),
			'check_out_lat' => $latitude,
			'check_out_long' => $longitude,
		];

		$this->db->where('attendance_id', $existing_attendance->attendance_id);
		$this->db->update($db_table, $checkout_data);

		echo json_encode([
			'status' => 'success',
			'message' => 'Checked out successfully' . ($branch_found ? ' from ' . $branch_found->name : ' at an unspecified location.')
		]);
	}

	public function attendance_individual()
	{
		check_login();
		
		$current_user_id = $this->session->userdata('user_id');
		$current_role_id = $this->session->userdata('role_id');
		$current_team_id = $this->session->userdata('team_id');
		
		$data['users_list'] = $this->attendance->user_list($current_user_id, $current_role_id, $current_team_id);
		$data['record_list'] = $this->users->branches_list();
		$data['title'] = "Attendance Individual";
		$data['page'] = "attendance/reports/individual";
		$this->load->library('Layout', $data);
	}

	public function attendance_individual_list()
	{
		check_login();
		
		$limit = $this->input->post('length');
    	$start = $this->input->post('start');
		
		//Date Range
		$date_range = $this->input->post('date_range');
		if (!empty($date_range)) {
			$date_range = explode(' - ', $date_range);
		
			// Parse and format the start date
			$start_date = DateTime::createFromFormat('F j, Y', trim($date_range[0]));
			$start_date_range = $start_date->format('Y-m-d') . ' 00:00:00';
		
			// Parse and format the end date
			$end_date = DateTime::createFromFormat('F j, Y', trim($date_range[1]));
			$end_date_range = $end_date->format('Y-m-d') . ' 23:59:59';
		}
		
		// Fetch search values from POST request
		$search_filters = [
			'current_user_id' => $this->session->userdata('user_id'),
			'current_role_id' => $this->session->userdata('role_id'),
			'current_team_id' => $this->session->userdata('team_id'),
			'start_date_range' => isset($start_date_range) ? $start_date_range : '',
			'end_date_range' => isset($end_date_range) ? $end_date_range : '',
			'user_id' => $this->input->post('user_id'),
		];
		
		// Fetch records from the model (pass search_value)
		$records = $this->attendance->attendance_individual_list($limit, $start, $search_filters);
		$total_records = $this->attendance->get_total_records($search_filters);
		
		//pre_print($records);
		// Prepare response (ensure keys match your column names)
		$data = [];
		foreach ($records as $rec) {
			$data[] = [
				"fullname" => $rec->fullname,
				"check_in_date" => date('Y-m-d', strtotime($rec->check_in_time)),
				"check_in_day" => date('l', strtotime($rec->check_in_time)),
				"check_in_time" => date('H:i:s', strtotime($rec->check_in_time)),
				"cib_location" => '<a href="https://maps.google.com/maps?q='.$rec->check_in_lat.','.$rec->check_in_long.'" target="_blank">'.(!empty($rec->cib_location) ? $rec->cib_location : 'untitled').'</a>',
				"cob_location" => '<a href="https://maps.google.com/maps?q='.$rec->check_out_lat.','.$rec->check_out_long.'" target="_blank">'.(!empty($rec->cob_location) ? $rec->cob_location : 'untitled').'</a>',
				"check_out_time" => date('H:i:s', strtotime($rec->check_out_time)),
			];
		}		

		// JSON response to DataTables
		$response = [
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_records,
			"data" => $data,
		];
	
		echo json_encode($response);
	}

	public function attendance_group()
	{
		check_login();
		
		$current_user_id = $this->session->userdata('user_id');
		$current_role_id = $this->session->userdata('role_id');
		$current_team_id = $this->session->userdata('team_id');
		
		$data['users_list'] = $this->attendance->user_list($current_user_id, $current_role_id, $current_team_id);
		$data['record_list'] = $this->users->branches_list();
		$data['title'] = "Attendance Group";
		$data['page'] = "attendance/reports/group";
		$this->load->library('Layout', $data);
	}

	public function attendance_group_list()
	{
		check_login();
		
		$limit = $this->input->post('length');
    	$start = $this->input->post('start');
		
		//Date Range
		$date_range = $this->input->post('date_range');
		if (!empty($date_range)) {
			$date_range = explode(' - ', $date_range);
		
			// Parse and format the start date
			$start_date = DateTime::createFromFormat('F j, Y', trim($date_range[0]));
			$start_date_range = $start_date->format('Y-m-d') . ' 00:00:00';
		
			// Parse and format the end date
			$end_date = DateTime::createFromFormat('F j, Y', trim($date_range[1]));
			$end_date_range = $end_date->format('Y-m-d') . ' 23:59:59';
		}
		
		// Fetch search values from POST request
		$search_filters = [
			'current_user_id' => $this->session->userdata('user_id'),
			'current_role_id' => $this->session->userdata('role_id'),
			'current_team_id' => $this->session->userdata('team_id'),
			'start_date_range' => isset($start_date_range) ? $start_date_range : '',
			'end_date_range' => isset($end_date_range) ? $end_date_range : '',
			'user_id' => $this->input->post('user_id'),
		];
		
		// Fetch records from the model (pass search_value)
		$records = $this->attendance->attendance_individual_list($limit, $start, $search_filters);
		$total_records = $this->attendance->get_total_records($search_filters);
		
		// Prepare response (ensure keys match your column names)
		$data = [];
		foreach ($records as $rec) {
			$data[] = [
				"fullname" => $rec->fullname,
				"check_in_date" => date('Y-m-d', strtotime($rec->check_in_time)),
				"check_in_day" => date('l', strtotime($rec->check_in_time)),
				"check_in_time" => date('H:i:s', strtotime($rec->check_in_time)),
				"cib_location" => '<a href="https://maps.google.com/maps?q='.$rec->check_in_lat.','.$rec->check_in_long.'" target="_blank">'.$rec->cib_location.'</a>',
				"cob_location" => '<a href="https://maps.google.com/maps?q='.$rec->check_out_lat.','.$rec->check_out_long.'" target="_blank">'.$rec->cob_location.'</a>',
				"check_out_time" => date('H:i:s', strtotime($rec->check_out_time)),
			];
		}

		// JSON response to DataTables
		$response = [
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_records,
			"data" => $data,
		];
	
		echo json_encode($response);
	}

	//Branches
	public function branches()
	{
		check_login();
		
		$data['record_list'] = $this->users->branches_list();
		$data['title'] = "Branches";
		$data['page'] = "attendance/branches";
		$this->load->library('Layout', $data);
	}
	
	public function office_branches($db_table = 'branches', $primary_id='branch_id')
	{
		check_login(); 
		
		$created_by_id = $this->session->userdata('user_id');
		$form_data = $this->input->post('branches');
		
		foreach ($form_data as $row) {
			$update_id = $row['update_id'];
			
			$data = array(
				'name' => $row['branch_name'],
				'radius' => $row['branch_radius'],
				'address' => $row['branch_address'],
				'latitude' => $row['latitude'],
				'longitude' => $row['longitude'],
			);
			
			if ($update_id == 0) {
				$d = array(
					'created_by_id' => $created_by_id,
					'created_on' => time(),
				);
				$data = array_merge($data, $d);
				$this->crud->add($data, $db_table);
			} else {
				$d = array(
					'updated_by_id' => $created_by_id,
					'updated_on' => time(),
				);
				$data = array_merge($data, $d);
				$this->crud->update($data, $update_id, $db_table, $primary_id);
			}
		}
		
		echo json_encode(['status' => 'Record added Successfully.']);
	}

	/*************** Leave Application ***************/
	public function leave_application($record_id=0, $copy=0)
	{
		check_login();
		
		$data['title'] = "Leave Application";
		$data['page'] = "attendance/leave-application/leave-application";
		$this->load->library('Layout', $data);
	}
	
	public function leave_application_list($db_table='leave_applications', $primary_id='application_id', $conditions=array(), $return_table_data=False)
	{
		check_login();
		
		$limit = $this->input->post('length');
    	$start = $this->input->post('start');
		
		$current_user_id = $this->session->userdata('user_id');
		$current_role_id = $this->session->userdata('role_id');
		$current_team_id = $this->session->userdata('team_id');
		
		$joins = array(
			0 => array(
				'table' => 'users',
				'columns' => "users.user_id = $db_table.created_by_id",
				'type' => 'left outer'
			),
		);
		$table_columns = "$db_table.*, $db_table.created_on as create_date, users.fullname as user_name";
		$table_count = "COUNT(DISTINCT $db_table.$primary_id) as total_count";
		
		//Where Condition
		if($current_role_id != 1) {
			$conditions[] = array('operator' => 'WHERE', 'column' => "$db_table.created_by_id", 'value' => $current_user_id);
		}
		
		//Order By
		$conditions[] = array('operator' => 'ORDER_BY', 'column' => "$db_table.$primary_id", 'value' => 'desc');

		// Fetch records from the model (pass search_value)
		$table_data = $this->crud->dt_list($db_table, $table_columns, $joins, $conditions, $limit, $start);
		$total_records = $this->crud->dt_total_count($db_table, $table_count, $joins, $conditions, '', $limit, $start);
		
		$counter = $table_data['start'];
		foreach($table_data['data'] as $index => $rec)
		{
			$counter++;
			$table_data['data'][$index]->counter = $counter;
			$table_data['data'][$index]->DT_RowId = $rec->application_id;
			$table_data['data'][$index]->checkbox = '<i class="fa fa-square-o"></i>';
			$table_data['data'][$index]->leave_type = leave_type($rec->leave_type);
			$table_data['data'][$index]->date_range = get_date_string_sql($rec->date_from). ' - ' .get_date_string_sql($rec->date_to);
			
			$table_data['data'][$index]->leave_status = '<button type="button" class="waves-light btn-small ' . 
			($rec->status == 1 ? 'btn-primary' : ($rec->status == 2 ? 'btn-success' : 'btn-danger')) . '" ' .
			($rec->status != 2 && $current_role_id == 1 ? ' data-toggle="modal" data-target="#leaveModal" onclick="edit_record(this);"' : '') . 
			' data-application-id="' . $rec->application_id . '">' . 
			application_status($rec->status) . 
			'</button>';

			$table_data['data'][$index]->create_date = $rec->user_name." <br /> ".date_only($rec->create_date);
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

	public function leave_application_setup($record_id=0, $copy=0)
	{
		check_login();
		
		$data['record'] = $this->attendance->leave_application_list($record_id);
		$data['page'] = "attendance/leave-application/leave-application-setup";
		$data['title'] = ($record_id != 0) ? ($copy == 1 ? "Leave Application Copy" : "Leave Application Edit") : "Leave Application Add";
		$this->load->library('Layout', $data);
	}

	public function leave_application_setup_post($slug_url=0, $db_table='leave_applications', $primary_id='application_id')
	{
		check_login();
		
		$message = '';
		$form_data = get_posted_data();
		$last_uri = $form_data["last_uri"];
		$created_by_id = $this->session->userdata('user_id');
		
		if (!empty($form_data["proof_image"])) {
			$upload_dir = FCPATH . 'uploads/leave-application/';
			$upload_url = site_url() . 'uploads/leave-application/';
		
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
		
			if (!$this->upload->do_upload('proof_image')) {
				$error = $this->upload->display_errors();
				out('ERROR', $error);
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
						out('ERROR', $error);
						return;
					}
		
					// Clear image_lib settings to avoid conflicts
					$this->image_lib->clear();
				}
		
				$image = $data['file_name'];
			}
		} else {
			$image = isset($form_data["update_proof_image"]) ? $form_data["update_proof_image"] : '';
		}
		
		$leave_type	= $form_data["leave_type"];
		$from_date	= isset($form_data["from_date"]) ? $form_data["from_date"] : '';
		$to_date	= isset($form_data["to_date"]) ? $form_data["to_date"] : '';
		$reason		= isset($form_data["reason"]) ? $form_data["reason"] : '';
		$status		= isset($form_data["status"]) ? $form_data["status"] : '';
		
		$data = array(
			'leave_type' => $leave_type,
			'date_from' => $from_date,
			'date_to' => $to_date,
			'reason' => $reason,
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
				'status' => $status,
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
			'RedirectTo' => site_url('leave-application'),
		));
	}

	public function leave_application_change_status($db_table='leave_applications', $primary_id='application_id')
	{
		check_login();
		
		$update_id = request_var('update_id', '');
		$status = request_var('status', '');
		$created_by_id = $this->session->userdata('user_id');
		
		$data = array(
			'status' => $status,
			'updated_by_id' => $created_by_id,
			'updated_on' => time(),
		);
		
		$this->crud->update($data, $update_id, $db_table, $primary_id);
		
		//Add Log History
		log_history($db_table, $update_id);
		
		out_json( array(
			'success' => 1,
			'message' => 'Record updated successfully.',
			'reload_table' => 1,
			'close_modal' => 1,


			// 'success' => 1,
			// 'message' => 'Record updated successfully.',
			// 'RedirectTo' => site_url('leave-application'),
		));
	}

}
