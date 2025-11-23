<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->model('voucher_model', 'voucher');
		$this->load->model('Chart_of_account_model', 'coa');
	}
	
	public function voucher()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$data['title'] = "Voucher";
		$data['page'] = "finance/voucher/voucher";
		$this->load->library('Layout', $data);
	}

	public function voucher_list($db_table='vouchers', $primary_id='voucher_id', $conditions=array(), $return_table_data=False)
	{
		check_login();
		$is_admin = is_admin_logged_in ( array(1) );
		restrict_role(CRM_ROLE);
		
		$limit = $this->input->post('length');
    	$start = $this->input->post('start');
		
		$current_role_id = $this->session->userdata('role_id');
		$current_user_id = $this->session->userdata('user_id');
		$session_project_id = $this->session->userdata('project_id');

		$joins = array(
			0 => array(
				'table' => 'projects',
				'columns' => "projects.project_id = $db_table.project_id",
				'type' => 'left outer'
			),
			1 => array(
				'table' => 'users',
				'columns' => "users.user_id = $db_table.created_by_id",
				'type' => 'left outer'
			),
		);
		$table_columns = "$db_table.*, projects.project_name, $db_table.created_on as create_date, users.fullname as user_name";
		$table_count = "COUNT(DISTINCT $db_table.$primary_id) as total_count";
		
		//Where Condition
		if($current_role_id == 2) {
			$project_ids = explode(',', $session_project_id);
			$conditions[] = array('operator' => 'WHERE_IN', 'column' => "$db_table.project_id", 'value' => $project_ids);
		}
		
		//Order By
		$conditions[] = array('operator' => 'ORDER_BY', 'column' => "$db_table.voucher_id", 'value' => 'desc');

		// Fetch records from the model (pass search_value)
		$table_data = $this->crud->dt_list($db_table, $table_columns, $joins, $conditions, $limit, $start);
		$total_records = $this->crud->dt_total_count($db_table, $table_count, $joins, $conditions, '', $limit, $start);
		
		$counter = $table_data['start'];
		foreach($table_data['data'] as $index => $rec)
		{
			$counter++;
			$table_data['data'][$index]->counter = $counter;
			$table_data['data'][$index]->DT_RowId = $rec->voucher_id;
			$table_data['data'][$index]->checkbox = '<i class="fa fa-square-o"></i>';
			$table_data['data'][$index]->transaction_type = transaction_type($rec->transaction_type).'<div class="table-action"><a href="'.site_url('voucher/edit/'.$rec->voucher_id).'">Edit</a> | <a href="'.site_url('voucher/view/'.$rec->voucher_id).'">View</a> | <a href="javascript:;" onClick="delete_record('.$rec->voucher_id.');">Delete</a></div>';
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
	
	public function voucher_delete()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$update_id = request_var('update_id', '');
		$voucher_id = request_var('voucher_id', '');
		
		$this->crud->delete($update_id, 'vouchers', 'voucher_id');
		$this->crud->delete($update_id, 'voucher_details', 'voucher_id');
		out ('SUCCESS', 'Removed.');
	}
	
	public function voucher_details_delete()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$delete_ids = request_var('delete_ids', array());

		if (empty($delete_ids))
		{
			out('ERROR', 'Please select the checkbox.');
			return false;
		}

		$selected_delete_ids = array();
		foreach ($delete_ids as $data)
		{
			if ($data['name'] == 'check[]')
				$selected_delete_ids[] = $data['value'];
		}
		
		$this->voucher->voucher_details_delete($selected_delete_ids);

		out ('SUCCESS', 'Removed.');
	}

	public function voucher_setup($record_id=0, $copy=0)
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$session_project_id = $this->session->userdata('project_id');
		$data['coa_level4_list'] = $this->coa->coa_level4_list();
		$data['project_list'] = $this->crud->all_list_sort('projects', 'project_name', $session_project_id);
		$data['record'] = $this->voucher->voucher_detail_list($record_id);
		$data['voucher_images'] = $this->voucher->voucher_images_list($record_id);
		$data['voucher_details'] = $this->voucher->voucher_details_list($record_id);
		$data['page'] = "finance/voucher/voucher-setup";
		$data['title'] = ($record_id != 0) ? ($copy == 1 ? "Voucher Copy" : "Voucher Edit") : "Voucher Add";
		$this->load->library('Layout', $data);
	}
	
	public function voucher_setup_post($slug_url = 0, $db_table = 'vouchers', $primary_id = 'voucher_id')
	{
		check_login();
		restrict_role(CRM_ROLE);

		$message = '';
		$form_data = get_posted_data();
		$created_by_id = $this->session->userdata('user_id');
		$images = [];

		if (!empty($_FILES['voucher_image']['name'][0])) {
			$upload_dir = FCPATH . 'uploads/vouchers/';
			if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

			$this->load->library('upload');
			$this->load->library('image_lib');

			$files = $_FILES['voucher_image'];
			$count = count($files['name']);

			for ($i = 0; $i < $count; $i++) {
				$original_name = $files['name'][$i];
				$clean_name = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', pathinfo($original_name, PATHINFO_FILENAME));
				$extension = pathinfo($original_name, PATHINFO_EXTENSION);
				$new_name =  $clean_name . '_' . time() . '.' . $extension;

				$_FILES['file']['name'] = $new_name;
				$_FILES['file']['type'] = $files['type'][$i];
				$_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
				$_FILES['file']['error'] = $files['error'][$i];
				$_FILES['file']['size'] = $files['size'][$i];

				$config['upload_path'] = $upload_dir;
				$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
				$config['max_size'] = 5000;
				$config['file_name'] = $new_name;

				$this->upload->initialize($config);

				if ($this->upload->do_upload('file')) {
					$data = $this->upload->data();

					if ($data['image_width'] > 800 || $data['image_height'] > 800) {
						$resize_config = [
							'image_library' => 'gd2',
							'source_image' => $data['full_path'],
							'maintain_ratio' => TRUE,
							'width' => 800,
							'height' => 800,
						];
						$this->image_lib->initialize($resize_config);
						$this->image_lib->resize();
						$this->image_lib->clear();
					}

					$images[] = $data['file_name'];
				} else {
					out('ERROR', $this->upload->display_errors());
					return;
				}
			}
		}

		$data = [
			'project_id' => $form_data["project_id"],
			'transaction_type' => $form_data["transaction_type"],
			'voucher_date' => $form_data["voucher_date"],
			'total_debit' => $form_data["total_debit"],
			'total_credit' => $form_data["total_credit"],
		];

		if ($slug_url == 0) {
			$data = array_merge($data, [
				'created_by_id' => $created_by_id,
				'created_on' => time(),
			]);
			$id = $this->crud->add($data, $db_table);

			if (!empty($id) && $images) {
				foreach ($images as $img_name) {
					$this->db->insert('images', [
						'post_id' => $id,
						'post_name' => 'vouchers',
						'image_name' => $img_name,
					]);
				}
			}

			// Voucher details
			if (!empty($id) && isset($form_data["account_number"])) {
				foreach ($form_data["account_number"] as $i => $acc) {
					$data2 = [
						'voucher_id' => $id,
						'account_number' => $acc,
						'narration' => $form_data['narration'][$i],
						'debit' => $form_data['debit'][$i] ?? 0,
						'credit' => $form_data['credit'][$i] ?? 0,
						'book' => $form_data['book'][$i] ?? '',
					];
					$this->crud->add($data2, 'voucher_details');
				}
			}
			$message = 'Record added successfully.';
		} else {
			$data = array_merge($data, [
				'updated_by_id' => $created_by_id,
				'updated_on' => time(),
			]);
			$this->crud->update($data, $slug_url, $db_table, $primary_id);

			if ($images) {
				// Delete old image files if needed (optional: you can fetch & delete old files here)
				$this->db->delete('images', ['post_id' => $slug_url, 'post_name' => 'vouchers']);

				foreach ($images as $img_name) {
					$this->db->insert('images', [
						'post_id' => $slug_url,
						'post_name' => 'vouchers',
						'image_name' => $img_name,
					]);
				}
			}

			// Update voucher details
			if (isset($form_data["account_number"])) {
				foreach ($form_data["account_number"] as $i => $acc) {
					$data2 = [
						'voucher_id' => $slug_url,
						'account_number' => $acc,
						'narration' => $form_data['narration'][$i],
						'debit' => $form_data['debit'][$i] ?? 0,
						'credit' => $form_data['credit'][$i] ?? 0,
						'book' => $form_data['book'][$i] ?? '',
					];
					if (empty($form_data['voucher_detail_id'][$i])) {
						$this->crud->add($data2, 'voucher_details');
					} else {
						$this->crud->update($data2, $form_data['voucher_detail_id'][$i], 'voucher_details', 'voucher_detail_id');
					}
				}
			}

			log_history($db_table, $slug_url);
			$message = 'Record updated successfully.';
		}

		out_json([
			'success' => 1,
			'message' => $message,
			'RedirectTo' => site_url('voucher'),
		]);
	}

	public function voucher_view($slug_url=0, $return_data=0)
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$data['record_list'] = $this->voucher->voucher_detail_list($slug_url);
		$data['voucher_images'] = $this->voucher->voucher_images_list($slug_url);
		$data['voucher_details'] = $this->voucher->voucher_details_list($slug_url);
		
		$data['title'] = "Voucher View";
		$data['page'] = "finance/voucher/voucher-view";

		if($return_data)
			return $data;

		$this->load->library('Layout', $data);
	}
	
	public function get_view_actions($permission_for = 'vouchers')
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$response = array(
			'view_actions' => ''
		);

		$page = request_var('page', '');
		$response['page'] = $page;
		$response['record_id'] = request_var('record_id', 0);
		$response['tpl_data'] = array(
			'log_id' => $response['record_id'],
			'add_url' => check_permission('Add', $permission_for, false) ? site_url("voucher/add"):false,
			'edit_url' => check_permission('Edit', $permission_for, false) ? site_url("voucher/edit/$response[record_id]"):false,
			'view_url' => check_permission('View', $permission_for, false) ? site_url("voucher/view/$response[record_id]"):false,
			'print_url' => check_permission('Print', $permission_for, false) ? site_url("voucher/print/$response[record_id]"):false,
			'list_url' => check_permission('List', $permission_for, false) ? site_url("vouchers"):false,
			'pdf_id' => $response['record_id'],
			'log_table' => check_permission('Log', $permission_for, false) ? "vouchers":false
		);
		$response['view_actions'] = $this->parser->parse('ajax/voucher-view-actions', $response['tpl_data'], TRUE);

		out_json($response);
	}
	
	public function get_accounts_by_project()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$project_id = $this->input->post('project_id');
		$accounts = $this->voucher->get_accounts_by_project($project_id);
		echo json_encode($accounts);
	}

}
