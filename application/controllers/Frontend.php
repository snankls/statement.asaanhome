<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Frontend extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('users_model', 'users');
		$this->load->model('Frontend_model', 'frontend');
	}
	 
	public function index()
	{
		check_login();
		
		$current_user_id = $this->session->userdata('user_id');
		$current_role_id = $this->session->userdata('role_id');
		$current_team_id = $this->session->userdata('team_id');

		// Admin
		//Count function(db_name, where, value, group_by)
		$data['total_projects'] = count($this->frontend->dashboard_count('projects', 'post_status', '1'));
		$data['total_inventory'] = count($this->frontend->dashboard_count('inventories', 'post_status', '1'));
		$data['total_booking'] = count($this->frontend->dashboard_count('bookings'));
		$data['total_voucher'] = count($this->frontend->dashboard_count('voucher_details'));
		
		//Count function(db_name, sum column)
		$result = $this->frontend->dashboard_sum('challans', 'challan_amount');
		$data['total_collection'] = $result->total_sum;

		//Users
		$data['total_admin'] = count($this->frontend->dashboard_count('users', 'role_id', '1'));
		$data['total_users'] = count($this->frontend->dashboard_count('users', 'role_id', '2'));
		$data['total_viewer'] = count($this->frontend->dashboard_count('users', 'role_id', '6'));
		$data['total_manager'] = count($this->frontend->dashboard_count('users', 'role_id', '7'));
		$data['total_individual'] = count($this->frontend->dashboard_count('users', 'role_id', '8'));

		// CRM Users
		$search_filters = [
			'current_user_id' => $current_user_id,
			'current_role_id' => $current_role_id,
			'current_team_id' => $current_team_id,
			'start_next_followup_date' => strtotime(date('Y-m-d') . ' 00:00:00'),
			'end_next_followup_date' => strtotime(date('Y-m-d') . ' 23:59:59'),
			'end_of_today' => strtotime('today 23:59:59'),
		];
		$data['total_leads'] = $this->frontend->dashboard_leads_count('total_leads', $search_filters);
		$data['potential_leeds'] = $this->frontend->dashboard_leads_count('potential_leeds', $search_filters);
		$data['closing_leeds'] = $this->frontend->dashboard_leads_count('closing_leeds', $search_filters);
		$data['due_overdue_meeting'] = $this->frontend->dashboard_leads_count('due_overdue_meeting', $search_filters);
		$data['upcoming_meeting'] = $this->frontend->dashboard_leads_count('upcoming_meeting', $search_filters);
		$data['today_follow_ups'] = $this->frontend->dashboard_leads_count('today_follow_ups', $search_filters);
		$data['todo_list'] = $this->frontend->dashboard_leads_count('todo_list', $search_filters);

		$data['title'] = "Dashboard";
		$data['page'] = "dashboard";
		$this->load->library('Layout', $data);
	}
	 
	//Login
	public function login()
	{
		$data['title'] = "Login";
		$data['page'] = "login";
		$this->load->library('Layout', $data);
	}

	public function process_login()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		
		$config = array(
			array(
				'field'   => 'email',
				'label'   => 'Email',
				'rules'   => 'trim|required'
			),
			array(
				'field'   => 'password',
				'label'   => 'Password',
				'rules'   => 'trim|required'
			),
		);

		$this->form_validation->set_rules($config);
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error', 'Please fill the required fields.');
			redirect(base_url() . 'login');
		}
		else
		{
			$check_status = $this->users->check_status($email, $password);
			if(empty($check_status->email) or empty($check_status->password))
			{
				$this->session->set_flashdata('error', 'Invalid Username or Password.');
				redirect(base_url() . 'login');
			}

			$data = $this->users->check_login($email, $password);
			if(empty($data))
			{
				$this->session->set_flashdata('error', 'Invalid Username or Password.');
				redirect(base_url() . 'login');
			}
			$redirect_back = site_url('dashboard');
			$this->session->set_userdata((array) $data);

			redirect($redirect_back);
		}
	}
	
	public function logout()
	{
		$this->session->unset_userdata('lead_form_data');
		$this->session->sess_destroy();
		redirect( site_url().'login', 'location' );
		exit();
	}
	
}
