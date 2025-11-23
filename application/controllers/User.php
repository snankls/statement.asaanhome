<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('api_model', 'api');
		$this->load->model('users_model', 'users');
	}
	
	public function user()
	{
		check_login();
		$data['is_admin'] = check_admin_login('yes');
		$data['title'] = "User";
		$data['page'] = "users/user";
		$this->load->library('Layout', $data);
	}
	
	public function user_list($db_table='users', $conditions=array(), $return_table_data=False)
	{
		check_login();
		
		$joins = array(
			0 => array(
				'table' => 'roles',
				'columns' => "roles.role_id = $db_table.role_id",
				'type' => 'left outer'
			),
		);
		$table_columns = "*, $db_table.created_on as create_date";
		
		$conditions[] = array('operator' => 'ORDER_BY', 'column' => "$db_table.user_id", 'value' => 'desc');
		$table_data = $this->crud->datatable_data($db_table, $table_columns, $joins, $conditions);
		
		$counter = $table_data['start'];
		foreach($table_data['data'] as $index => $rec)
		{
			$counter++;
			$table_data['data'][$index]->counter = $counter;
			$table_data['data'][$index]->DT_RowId = $rec->user_id;
			$table_data['data'][$index]->checkbox = '<i class="fa fa-square-o"></i>';
			$table_data['data'][$index]->user_image = '<a href="'.site_url('user/view/'.$rec->user_id).'" class="list-img">'.get_image($rec->image, 'users').'</a>';
			$table_data['data'][$index]->fullname = $rec->fullname.'<div class="table-action"><a href="'.site_url('user/edit/'.$rec->user_id).'">Edit</a> | <a href="'.site_url('user/view/'.$rec->user_id).'">View</a></div>';
			$table_data['data'][$index]->user_status = "<span class='waves-light btn-small ".($rec->status == 1 ? 'btn-success' : 'btn-danger') . "'>" . enable_disable($rec->status) . "</span>";
			
			$rec->log_table = $db_table;
		}

		if($return_table_data)
			return $table_data;

		echo json_encode($table_data);
	}
	
	public function user_setup($record_id=0, $copy=0)
	{
		check_login();
		
		$data['project_list'] = $this->crud->all_list_sort('projects', 'project_name');
		$data['record'] = $this->users->user_detail_list($record_id);
		$data['user_role'] = $this->crud->all_list('roles');
		$data['team_list'] = $this->crud->all_list('teams', 1);
		
		$data['page'] = "users/user-setup";
		$data['title'] = ($record_id != 0) ? ($copy == 1 ? "User Copy" : "User Edit") : "User Add";
		$this->load->library('Layout', $data);
	}
	
	public function user_setup_post($slug_url=0, $db_table='users', $primary_id='user_id')
	{
		check_login();
		
		$message = '';
		$form_data = get_posted_data();
		$created_by_id = $this->session->userdata('user_id');
		
		$username = $form_data["username"];
		$email_address = $form_data["email_address"];
		
		if (!empty($form_data["user_image"])) {
			$upload_dir = FCPATH . 'uploads/users/';
			$upload_url = site_url() . 'uploads/users/';
		
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
		
			if (!$this->upload->do_upload('user_image')) {
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
					
					$this->image_lib->clear();
				}
		
				$image = $data['file_name'];
			}
		} else {
			$image = isset($form_data["update_image"]) ? $form_data["update_image"] : '';
		}
		
		$config = [
			[
				'field' => 'fullname',
				'label' => 'Full Name',
				'rules' => 'required'
			],
			[
				'field' => 'status',
				'label' => 'User Status',
				'rules' => 'required'
			],
			[
				'field' => 'role_id',
				'label' => 'User Role',
				'rules' => 'required'
			]
		];
		
		if (empty($slug_url)) {
			$this->form_validation->set_message('regex_match', 'The password must be at least 6 characters long and contain both letters and numbers.');
			$config = array_merge($config, [
				[
					'field' => 'username',
					'label' => 'Username',
					'rules' => 'required|is_unique[users.username]'
				],
				[
					'field' => 'email_address',
					'label' => 'Email Address',
					'rules' => 'required|is_unique[users.email]'
				],
				[
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|required|matches[confirm_password]|min_length[6]|regex_match[/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/]'
				],
				[
					'field' => 'confirm_password',
					'label' => 'Confirm Password',
					'rules' => 'trim|required'
				]
			]);
		}
	
		$this->form_validation->set_rules($config);
		
		if ($this->form_validation->run() == FALSE)
		{
			out('ERROR', validation_errors());
			return false;
		}
		else
		{
			if($form_data["user_module"] == 1) {
				$project_id = isset($form_data["project_id"]) ? sprintf('%s', implode(",", $form_data["project_id"])) : '';
				$team_id = '';
			}
			else if($form_data["user_module"] == 2) {
				$project_id = '';
				$team_id = isset($form_data["team_name"]) ? sprintf('%s', implode(",", $form_data["team_name"])) : '';
			}
			
			$data = array(
				'fullname' => $form_data["fullname"],
				'username' => $username,
				'email' => $email_address,
				'user_module' => isset($form_data["user_module"]) ? $form_data["user_module"] : '',
				'project_id' => $project_id,
				'team_id' => $team_id,
				'mobile' => $form_data["mobile"],
				'status' => isset($form_data["status"]) ? $form_data["status"] : '',
				'role_id' => isset($form_data["role_id"]) ? $form_data["role_id"] : '',
				'address' => isset($form_data["address"]) ? $form_data["address"] : '',
				'description' => isset($form_data["description"]) ? $form_data["description"] : '',
				'image' => $image,
			);
	
			if (empty($slug_url))
			{
				$password = $form_data["password"];
				$confirm_password = $form_data["confirm_password"];
				
				$check_unit_validation = $this->users->check_user_validation($db_table, $username, $email_address, $slug_url);
				if (!empty($check_unit_validation))
				{
					out('ERROR', '<p>Username or email already exists.</p>');
					return false;
				}
	
				$d = array(
					'password' => $password,
					'created_by_id' => $created_by_id,
				);
				$data = array_merge($data, $d);
				$this->crud->add($data, $db_table);
	
				$message = 'Record added successfully.';
			}
			else
			{
				$d = array(
					'updated_by_id' => $created_by_id,
					'updated_on' => time(),
				);
				$data = array_merge($data, $d);
				$this->crud->user_update($data, $slug_url, $db_table, $primary_id);
	
				// Add Log History
				log_history($db_table, $slug_url);
	
				$message = 'Record updated successfully.';
			}
	
			out_json([
				'success' => 1,
				'message' => $message,
				'RedirectTo' => site_url('user'),
			]);
		}
	}
	
	public function user_view($slug_url=0, $return_data=0)
	{
		check_login();
		
		$data['record_list'] = $this->users->user_detail_list($slug_url);
		$data['project_list'] = $this->users->project_list($data['record_list']->user_project_id);
		$data['title'] = "User View";
		$data['page'] = "users/user-view";

		if($return_data)
			return $data;

		$this->load->library('Layout', $data);
	}
	
	public function get_view_actions($permission_for = 'user')
	{
		check_login();
		
		$response = array(
			'view_actions' => ''
		);

		$page = request_var('page', '');
		$response['page'] = $page;
		$response['record_id'] = request_var('record_id', 0);
		$response['tpl_data'] = array(
			'log_id' => $response['record_id'],
			'add_url' => check_permission('Add', $permission_for, false) ? site_url("user/add"):false,
			'edit_url' => check_permission('Edit', $permission_for, false) ? site_url("user/edit/$response[record_id]"):false,
			'view_url' => check_permission('View', $permission_for, false) ? site_url("user/view/$response[record_id]"):false,
			'list_url' => check_permission('List', $permission_for, false) ? site_url("user"):false,
			'log_table' => check_permission('Log', $permission_for, false) ? "users":false
		);
		$response['view_actions'] = $this->parser->parse('ajax/view-actions', $response['tpl_data'], TRUE);

		out_json($response);
	}
	
	public function user_permission_setup()
	{
		check_login();
		$user_id = request_var('user_id', '');
		$project_permission = request_var('project_permission', '');

		$message = '';
		if($project_permission != '')
		{
			//Delete Before Update User Permission
			$this->crud->delete($user_id, 'user_permissions', 'user_id');

			foreach($project_permission as $k => $v)
			{
				$data = array(
					'user_id' => $user_id,
					'permission_id' => $project_permission[$k],
				);
				$this->crud->add($data, 'user_permissions');
			}

			$message = 'Record added successfully.';
		}

		echo json_encode( array(
			'success' => 1,
			'message' => $message,
			'reload_table' => 0,
			'close_modal' => 1,
		));
	}
	
	public function resend()
	{
		$email = request_var('email', '');
		
		$hash = md5(uniqid().time());
		$site_url = site_url().'recover?verify='.$hash;
		$data = array (
			'hash' => $hash
		);
		$this->users->update_hash( $email, $data );
		
		$subject = 'Registration';
		$message = 'Dear User,<br/>your have been recover password on MEPCO Complaints.<br/>Please verify your account click <a href="'.$site_url.'" title="Verify">Verify</a>.<br/><br/>Thank you.<br/>MEPCO Complaints';
		
		site_email($email, $subject, $message);
		
		out('SUCCESS', 'User E-mail resend successfully.');
	}
	
	public function change_password()
	{
		$data['title'] = "Change Password";
		$data['page'] = "users/change-password";
		$this->load->library('Layout', $data);
	}
	
	public function save_change_password()
	{
		check_login();

		$current_password = request_var('current_password', '');
		$new_password = request_var('new_password', '');
		$confirm_password = request_var('confirm_password', '');

		$config = array(
			array(
				'field' => 'current_password',
				'label' => 'Current Password',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'new_password',
				'label' => 'New Password',
				'rules' => 'trim|required|min_length[6]|matches[confirm_password]|regex_match[/^(?=.*[A-Za-z])(?=.*\d).{6,}$/]'
			),
			array(
				'field' => 'confirm_password',
				'label' => 'Confirm Password',
				'rules' => 'trim|required'
			)
		);

		$this->form_validation->set_rules($config);
		$this->form_validation->set_message('regex_match', 'The {field} must contain at least one letter and one number.');
		$this->form_validation->set_message('matches', 'The new password and confirm password do not match.');

		if ($this->form_validation->run() == FALSE) {
			$error_message = validation_errors();
			// Format the error message to be more user-friendly
			$error_message = str_replace(['<p>', '</p>'], '', $error_message);
			out('ERROR', $error_message);
			return false;
		}

		$user_id = $this->session->userdata('user_id');
		$user_data = $this->crud->single($user_id, 'users', 'user_id');
		
		// IMPORTANT: If you're storing hashed passwords, use password_verify()
		// If storing plain text (not recommended), use direct comparison
		if($current_password == $user_data->password) {
			$data = array(
				'password' => $new_password // Consider hashing this: password_hash($new_password, PASSWORD_DEFAULT)
			);
			$this->crud->update($data, $user_id, 'users', 'user_id');
			out('SUCCESS','Password successfully changed.');
		} else {
			out('ERROR','Your current password is incorrect. Please try again.');
			return false;
		}
	}
	
	public function forget_password()
	{
		$data['title'] = "Forget Password";
		$data['page'] = "users/forget-password";
		$this->load->library('Layout', $data);
	}
	
	public function forget_password_recover()
	{
		$email = request_var('email', '');
		$data = $this->users->check_status($email);
		
		$config = array(
			array(
				'field'   => 'email', 
				'label'   => 'Email', 
				'rules'   => 'trim|required|valid_email'
			)
		);
		$this->form_validation->set_rules($config);
		
		if ($this->form_validation->run() == FALSE)
		{
			out('ERROR', validation_errors());
			return false;
		}
		
		$hash = md5(uniqid().time());
		$site_url = site_url().'recover?verify='.$hash;
		$data = array (
			'hash' => $hash
		);
		$this->users->update_hash( $email, $data );
		
		$subject = 'Forgot Password';
		$message = 'Dear User,<br/>your have been recover password on MEPCO Complaints.<br/>Please verify your account click <a href="'.$site_url.'" title="Verify">Verify</a>.<br/><br/>Thank you.<br/>MEPCO Complaints';
		
		site_email($email, $subject, $message);
		
		out('SUCCESS','<p>Email verified successfully. Please go to your email and verify your account.</p>');
	}
	
	public function recover_password()
	{
		$data['title'] = "Recover Password";
		$data['page'] = "users/recover-password";
		$this->load->library('Layout', $data);
	}
	
	public function recover_forget_password()
	{
		$new_password		= request_var('new_password', '');
		$confirm_password	= request_var('confirm_password', '');
		$hash				= request_var('hash', '');
		
		$config = array(
			array(
				'field'   => 'new_password', 
				'label'   => 'New Password', 
				'rules'   => 'trim|matches[confirm_password]'
			),
			array(
				'field'   => 'confirm_password', 
				'label'   => 'Confirm Password', 
				'rules'   => 'trim'
			)
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE)
		{
			out('ERROR',validation_errors());
			return false;
		}
		else
		{
			if ( empty($new_password) or empty($confirm_password) )
			{
				out('ERROR','<p>Your Password is empty.</p>');
				return false;
			}
			
			$data = $this->users->user_hash($hash);
			
			if($hash == $data->hash)
			{
				$data = array(
					'password' => $new_password,
					'status' => 1,
				);
				$this->users->change_password($data, $hash);
				
				out('SUCCESS', '<p>Password successfully changed.</p>');
			}
			else
			{
				out('ERROR', '<p>Your password is not match.</p>');
				return false;
			}
		}
	}

}
