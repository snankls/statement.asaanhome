<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->helper('function');

		$this->load->model('inventory_model', 'inventory');
		$this->load->model('booking_model', 'booking');
	}
	
	public function booking()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$data['title'] = "Booking";
		$data['page'] = "projects/booking/booking";
		$this->load->library('Layout', $data);
	}

	public function booking_list($db_table='bookings', $primary_id='booking_id', $conditions=array(), $return_table_data=False)
	{
		check_login();
		$is_admin = is_admin_logged_in(array(1));
		restrict_role(CRM_ROLE);
	
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
				'table' => 'inventories',
				'columns' => "inventories.project_id = bookings.project_id",
				'type' => 'left outer'
			),
			2 => array(
				'table' => 'users',
				'columns' => "users.user_id = $db_table.created_by_id",
				'type' => 'left outer'
			),
		);
	
		$table_columns = "
			$db_table.*,
			(
				SELECT bii.amount
				FROM inventory_installments bii
				WHERE bii.inventory_id = bookings.inventory_id
				ORDER BY bii.inventory_id ASC
				LIMIT 1
			) AS first_booking_amount,
			(
				SELECT SUM(ii.amount)
				FROM inventory_installments ii
				WHERE ii.inventory_id = bookings.inventory_id
				AND ii.date <= CURDATE()
			) as due_amount,
			(
				SELECT SUM(ba.amount)
				FROM booking_amounts ba
				WHERE ba.booking_id = bookings.booking_id
			) as paid_amount,
			(
				SELECT i.total_price
				FROM inventories i
				WHERE i.inventory_id = bookings.inventory_id
			) as total_price,
			inventories.inventory_id as inventory_inventory_id,
			$db_table.inventory_id as booking_inventory_id,
			projects.project_name,
			$db_table.created_on as create_date,
			users.fullname as user_name
		";
		
		$conditions[] = array('operator' => 'GROUP_BY', 'column' => "$db_table.booking_id", 'value' => true);
		
		if($current_role_id == 2 or $current_role_id == 6) {
			$project_ids = explode(',', $session_project_id);
			$conditions[] = array('operator' => 'WHERE_IN', 'column' => "$db_table.project_id", 'value' => $project_ids);
		}
		
		$conditions[] = array('operator' => 'ORDER_BY', 'column' => "$db_table.booking_id", 'value' => 'desc');
		$table_data = $this->crud->datatable_data($db_table, $table_columns, $joins, $conditions);
		
		//pre_print($table_data['data']); exit;
		$counter = $table_data['start'];
		foreach($table_data['data'] as $index => $rec)
		{
			$counter++;
			
			$overdue_amount = $rec->due_amount - $rec->paid_amount;
			$future_amount = $rec->total_price - $rec->due_amount;
			
			$table_data['data'][$index]->counter = $counter;
			$table_data['data'][$index]->DT_RowId = $rec->booking_id;
			$table_data['data'][$index]->checkbox = '<i class="fa fa-square-o"></i>';
			$table_data['data'][$index]->project_name = $rec->project_name.'<div class="table-action">';
	
			if ($current_role_id != 6) {
				$table_data['data'][$index]->project_name .= '<a href="'.site_url('booking/edit/'.$rec->booking_id).'">Edit</a> | ';
			}
			$table_data['data'][$index]->project_name .= '<a href="'.site_url('booking/view/'.$rec->booking_id).'">View</a>';
	
			$table_data['data'][$index]->project_name .= '</div>';
			$table_data['data'][$index]->total_price = number_format($rec->total_price);
			$table_data['data'][$index]->book_amount = number_format($rec->first_booking_amount);
			$table_data['data'][$index]->due_amount = number_format($rec->due_amount);
			$table_data['data'][$index]->paid_amount = number_format($rec->paid_amount);
			$table_data['data'][$index]->overdue_amount = number_format($overdue_amount);
			$table_data['data'][$index]->future_amount = number_format($future_amount);
			$table_data['data'][$index]->agency_commission = number_format($rec->agency_commission);
			$table_data['data'][$index]->property_type = property_types($rec->property_type);
			$table_data['data'][$index]->create_date = $rec->user_name." <br /> ".date_only($rec->create_date);
	
			$rec->log_id = $rec->booking_id;
			$rec->update_id = $rec->booking_id;
			$rec->inventory_id = $rec->inventory_inventory_id;
			$rec->booking_inventory_id = $rec->booking_inventory_id;
			$rec->is_admin = $is_admin;
			$rec->current_role_id = $current_role_id;
			$table_data['data'][$index]->actions = $this->parser->parse('ajax/booking-action', $rec, TRUE);
			$rec->log_table = $db_table;
		}
	
		if($return_table_data)
			return $table_data;
	
		echo json_encode($table_data);
	}
	
	public function booking_delete()
	{
		check_login();
		check_viewer_login();
		restrict_role(CRM_ROLE);
		
		$update_id = request_var('update_id', '');
		$inventory_id = request_var('inventory_id', '');
		
		$this->crud->delete($update_id, 'bookings', 'booking_id');
		$this->crud->delete($update_id, 'booking_amounts', 'booking_id');
		$this->crud->delete($update_id, 'challans', 'booking_id');
		
		//Inventory Status Change
		$data = array(
			'status' => 1,
		);
		$this->crud->update($data, $inventory_id, 'inventories', 'inventory_id');
		
		out ('SUCCESS', 'Removed.');
	}

	public function booking_setup($record_id=0, $copy=0)
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$session_project_id = $this->session->userdata('project_id');
		$data['project_list'] = $this->booking->get_inventory_project_lists($record_id, $session_project_id);
		$data['record'] = $this->booking->booking_detail_list($record_id);
		$data['page'] = "projects/booking/booking-setup";
		$data['title'] = ($record_id != 0) ? ($copy == 1 ? "Booking Copy" : "Booking Edit") : "Booking Add";
		$this->load->library('Layout', $data);
	}
	
	public function booking_installment_list()
	{
		check_login('yes');
		$project_id = request_var('project_id');
		restrict_role(CRM_ROLE);
		
		$data['installment_list'] = $this->booking->booking_installment_list($project_id);
		$this->load->view('projects/booking/installment-list', $data);
	}

	public function booking_setup_post($slug_url=0, $db_table='bookings', $primary_id='booking_id')
	{
		check_login();
		check_viewer_login();
		restrict_role(CRM_ROLE);
		
		$message = '';
		$form_data = get_posted_data();
		$last_uri = $form_data["last_uri"];
		$created_by_id = $this->session->userdata('user_id');
		
		$upload_dir = FCPATH . 'uploads/bookings/';
		$upload_url = site_url().'uploads/bookings/';
		if (!is_dir($upload_dir))
		{
			if (!mkdir($upload_dir, 0755, true))
			{
				out ('ERROR', 'Failed to create folders...');
				return;
			}
		}
		
		$config['upload_path'] 		= $upload_dir;
		$config['allowed_types']	= 'gif|jpg|jpeg|png|webp';
		$config['max_size']         = 5000;
		$config['max_width']        = 1900;
		$config['max_height']       = 1600;
		
		$this->upload->initialize($config);
		
		//CNIC Front
		if(!empty($form_data["cnic_front"]))
		{
			if (!$this->upload->do_upload('cnic_front'))
				$error1 = array('error' => $this->upload->display_errors());
			else
				$data1 = array('upload_data' => $this->upload->data());
			
			$cnic_front = isset($data1['upload_data']['file_name']) ? $data1['upload_data']['file_name'] : '';
		}
		else
			$cnic_front = isset($form_data["update_cnic_front"]) ? $form_data["update_cnic_front"] : '';
		
		//CNIC Back
		if(!empty($form_data["cnic_back"]))
		{
			if (!$this->upload->do_upload('cnic_back'))
				$error2 = array('error' => $this->upload->display_errors());
			else
				$data2 = array('upload_data' => $this->upload->data());
			
			$cnic_back = isset($data2['upload_data']['file_name']) ? $data2['upload_data']['file_name'] : '';
		}
		else
			$cnic_back = isset($form_data["update_cnic_back"]) ? $form_data["update_cnic_back"] : '';
		
		//Image
		if(!empty($form_data["image"]))
		{
			if (!$this->upload->do_upload('image'))
				$error3 = array('error' => $this->upload->display_errors());
			else
				$data3 = array('upload_data' => $this->upload->data());
			
			$image = isset($data3['upload_data']['file_name']) ? $data3['upload_data']['file_name'] : '';
		}
		else
			$image = isset($form_data["update_image"]) ? $form_data["update_image"] : '';
		
		//Proof Image
		if(!empty($form_data["proof_image"]))
		{
			$upload_dir = FCPATH . 'uploads/booking_receipt/';
			$upload_url = site_url().'uploads/booking_receipt/';
			if (!is_dir($upload_dir))
			{
				if (!mkdir($upload_dir, 0755, true))
				{
					out ('ERROR', 'Failed to create folders...');
					return;
				}
			}
			
			$config['upload_path'] 		= $upload_dir;
			$config['allowed_types']	= 'gif|jpg|jpeg|png|webp';
			$config['max_size']         = 5000;
			$config['max_width']        = 1900;
			$config['max_height']       = 1600;
			
			$this->upload->initialize($config);
			
			if (!$this->upload->do_upload('proof_image'))
				$error4 = array('error' => $this->upload->display_errors());
			else
				$data4 = array('upload_data' => $this->upload->data());
			
			$proof_image = isset($data4['upload_data']['file_name']) ? $data4['upload_data']['file_name'] : '';
		}
		
		$serial = document_number(array('db_table' => 'booking_amounts'));
		$property_type = isset($form_data["property_type"]) ? $form_data["property_type"] : '';
		$unit_number = isset($form_data["unit_number"]) ? $form_data["unit_number"] : '';
		$father_husband_name = isset($form_data["father_husband_name"]) ? $form_data["father_husband_name"] : '';
		$customer_city = isset($form_data["customer_city"]) ? $form_data["customer_city"] : '';
		$mobile = isset($form_data["mobile"]) ? $form_data["mobile"] : '';
		$landline = isset($form_data["landline"]) ? $form_data["landline"] : '';
		$email_address = isset($form_data["email_address"]) ? $form_data["email_address"] : '';
		$mailing_address = isset($form_data["mailing_address"]) ? $form_data["mailing_address"] : '';
		$permanent_address = isset($form_data["permanent_address"]) ? $form_data["permanent_address"] : '';
		$nominee_name = isset($form_data["nominee_name"]) ? $form_data["nominee_name"] : '';
		$nominee_father_husband_name = isset($form_data["nominee_father_husband_name"]) ? $form_data["nominee_father_husband_name"] : '';
		$nominee_cnic = isset($form_data["nominee_cnic"]) ? $form_data["nominee_cnic"] : '';
		$relation = isset($form_data["relation"]) ? $form_data["relation"] : '';
		$agency_name = isset($form_data["agency_name"]) ? $form_data["agency_name"] : '';
		$agency_commission = isset($form_data["agency_commission"]) ? $form_data["agency_commission"] : '';
		$reference = isset($form_data["reference"]) ? $form_data["reference"] : '';
		$booking_amount = isset($form_data["amount"]) ? $form_data["amount"] : '';
		
		$data = array(
			'project_id' => $form_data["project_id"],
			'inventory_id' => $form_data["inventory_id"],
			'property_type' => $property_type,
			'booking_unit' => $unit_number,
			'registration' => isset($form_data["registration"]) ? $form_data["registration"] : '',
			'customer_name' => $form_data["customer_name"],
			'cnic' => isset($form_data["cnic"]) ? $form_data["cnic"] : '',
			'father_husband_name' => $father_husband_name,
			'customer_city' => $customer_city,
			'mobile' => $mobile,
			'landline' => $landline,
			'email_address' => $email_address,
			'mailing_address' => $mailing_address,
			'permanent_address' => $permanent_address,
			'cnic_front' => $cnic_front,
			'cnic_back' => $cnic_back,
			'image' => $image,
			'nominee_name' => $nominee_name,
			'nominee_father_husband_name' => $nominee_father_husband_name,
			'nominee_cnic' => $nominee_cnic,
			'relation' => $relation,
			'agency_name' => $agency_name,
			'agency_commission' => $agency_commission,
			'booking_amount' => $booking_amount,
		);
		if($slug_url == 0)
		{
			$d = array(
				'created_by_id' => $created_by_id,
				'created_on' => time(),
			);
			$data = array_merge($data, $d);
			$id = $this->crud->add($data, $db_table);
			
			// Email Send
			// if(!empty($id) && !empty($email_address))
			// {
			// 	$subject = $comp_receive_form.' COMPLAINT #. '.$RecNo;
			// 	$message = 'Name of Complainant: '.$form_data["Name"].'<br/><br/> Address: '.$Address.'<br/><br/> Cell No.: '.$CellNo.'<br/><br/> Ref No.: '.$RefNo.'<br/><br/> Nature of Complaint: '.$nature_complaint.'<br/><br/> Complaint Detail: '.$form_data["ComplaintDetail"].'<br/><br/> It is therefore requested to please investigate the matter immediately and submit detail report online on <a href="http://rcc-mepco.com">www.rcc-mepco.com</a> for redesal of grivances of the complainant and onword submission report to Worthy Cheif Executive Officer MEPCO Mutlan.<br/><br/>Dy: Manager<br/>RCC MEPCO Multan.<br/><br/><br/> <strong>Note:</strong> in case of any problem regarding submission reply online on <a href="http://rcc-mepco.com">www.rcc-mepco.com</a> please contact Regional Compalint Center, MEPCO Multan.';
				
			// 	email_send($subject, $email, $message);
			// }

			if(!empty($id))
			{
				//Booking Amount
				$data2 = array(
					'booking_id' => $id,
					'inventory_id' => $form_data["inventory_id"],
					'amount_date' => $form_data["amount_date"],
					'payment_method' => $form_data["payment_method"],
					'reference' => isset($form_data["reference"]) ? $form_data["reference"] : '',
					'serial' => $serial,
					'created_by_id' => $created_by_id,
					'created_on' => time(),
				);
				
				$total_payable_amount = $form_data["amount"];
				$total_paid_amount = $this->booking->total_paid_amount($id);
				$total_paid_amount = $total_paid_amount->paid_amount ? $total_paid_amount->paid_amount : 0;
				
				$plan_type = $this->inventory->get_inventory_plan_type($form_data['inventory_id']);
				if($plan_type == 'Installment')
				{
					$total_installment = $this->booking->paid_inventory_installment_list($form_data['inventory_id']);
				}
				else
				{
					$total_installment = $this->booking->paid_inventory_milestone_installment_list($form_data['inventory_id']);
				}
				
				$total_unit_price = $this->inventory->total_unit_price($form_data['inventory_id']);
				
				$total_paid_installment_till = $total_paid_amount + $total_payable_amount;
				if($total_paid_installment_till > $total_unit_price)
				{
					out ('error', 'Installment amount exceed total price.');
					return false;
				}
				
				//Installment
				$rowNumber = 1;
				foreach ($total_installment as $installment) {
					if ($total_payable_amount > 0) {
						if ($total_paid_amount >= $installment->amount) {
							$total_paid_amount -= $installment->amount;
						} else {
							$remaining = $installment->amount - $total_paid_amount;
							if ($remaining > 0) {
								if ($total_payable_amount >= $remaining) {
									$data2['amount'] = $remaining;
									$return_id = $this->crud->add($data2, 'booking_amounts');
									$total_payable_amount -= $remaining;
									$total_paid_amount = 0;
								} else {
									$data2['amount'] = $total_payable_amount;
									$return_id = $this->crud->add($data2, 'booking_amounts');
									$total_payable_amount = 0;
								}
							}
						}
					}
					$rowNumber++;
				}
				
				if(!empty($return_id))
				{
					//Challans Add
					$data3 = array(
						'booking_id' => $id,
						'inventory_id' => $form_data["inventory_id"],
						'serial' => $serial,
						'challan_date' => $form_data["amount_date"],
						'challan_amount' => $form_data["amount"],
						'challan_payment_method' => $form_data["payment_method"],
						'reference' => isset($form_data["reference"]) ? $form_data["reference"] : '',
						'proof_image' => isset($proof_image) ? $proof_image : '',
						'created_by_id' => $created_by_id,
						'created_on' => time(),
					);
					$this->crud->add($data3, 'challans');
					
					//Inventory Status Update
					$data3 = array(
						'status' => 2,
					);
					$this->crud->update($data3, $form_data["inventory_id"], 'inventories', 'inventory_id');
				}
			}
			
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
		exit;
		out_json( array(
			'success' => 1,
			'message' => $message,
			'RedirectTo' => site_url('booking'),
		));
	}
	
	// public function booking_view($slug_url=0, $return_data=0)
	// {
	// 	check_login();
	// 	restrict_role(CRM_ROLE);
		
	// 	$record_list = $this->booking->booking_detail_list($slug_url);
	// 	$data['challan_list'] = $this->booking->booking_challan_list($slug_url);
	// 	$data['paid_amount_list'] = $this->booking->booking_paid_installment_list($slug_url);

	// 	$installment_list = isset($record_list->inventory_inventory_id) ? $record_list->inventory_inventory_id : null;
	// 	$data['installment_list'] = $this->booking->inventory_installment_list($installment_list);
		
	// 	$data['duesurcharge_list'] = $this->booking->booking_duesurcharge_list($slug_url);
	// 	$data['duesurcharge_waive_off'] = $this->booking->booking_duesurcharge_waive_off($slug_url);
		
	// 	$data['installment_count'] = count($this->booking->inventory_installment_list($installment_list));
		
	// 	$data['record_list'] = $record_list;
	// 	$data['title'] = "Booking View";
	// 	$data['page'] = "projects/booking/booking-view";

	// 	if($return_data)
	// 		return $data;

	// 	$this->load->library('Layout', $data);
	// }

	public function booking_view($slug_url = 0, $return_data = 0)
	{
		check_login();
		restrict_role(CRM_ROLE);

		$record_list = $this->booking->booking_detail_list($slug_url);
		$inventory_id = isset($record_list->inventory_inventory_id) ? $record_list->inventory_inventory_id : null;
		$challan_list = $this->booking->booking_challan_list($slug_url);
		$total_unit_price = $this->inventory->total_unit_price($inventory_id);

		if($record_list->plan_type == 'Installment')
		{
			$installment_list = $this->booking->inventory_installment_list($inventory_id);
			$statement_data = generate_statement_data($installment_list, $challan_list, $total_unit_price);
		}
		else
		{
			$installment_list = $this->booking->inventory_milestone_installment_list($inventory_id);
			$statement_data = generate_milestone_statement_data($installment_list, $challan_list, $total_unit_price);
		}

		$data = [
			'record_list' => $record_list,
			'challan_list' => $challan_list,
			'installment_list' => $installment_list,
			'duesurcharge_list' => $this->booking->booking_duesurcharge_list($slug_url),
			'duesurcharge_waive_off' => $this->booking->booking_duesurcharge_waive_off($slug_url),
			'installment_count' => count($installment_list),

			// ✅ Statement data for view
			'statement_rows' => $statement_data['statement_rows'],
			'total_due' => $statement_data['total_due'] ? $statement_data['total_due'] : 0,
			'total_paid' => $statement_data['total_paid'] ? $statement_data['total_paid'] : 0,
			'total_balance' => $statement_data['total_balance'] ? $statement_data['total_balance'] : 0,
			'total_surcharge' => $statement_data['total_surcharge'] ? $statement_data['total_surcharge'] : 0,

			'title' => "Booking View",
			'page' => "projects/booking/booking-view"
		];

		if ($return_data)
			return $data;

		$this->load->library('Layout', $data);
	}

	public function get_view_actions($permission_for = 'booking')
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
			'add_url' => check_permission('Add', $permission_for, false) ? site_url("booking/add"):false,
			'edit_url' => check_permission('Edit', $permission_for, false) ? site_url("booking/edit/$response[record_id]"):false,
			'view_url' => check_permission('View', $permission_for, false) ? site_url("booking/view/$response[record_id]"):false,
			'print_url' => check_permission('Print', $permission_for, false) ? site_url("booking/print/$response[record_id]"):false,
			'list_url' => check_permission('List', $permission_for, false) ? site_url("booking"):false,
			'log_table' => check_permission('Log', $permission_for, false) ? "bookings":false
		);
		$response['view_actions'] = $this->parser->parse('ajax/booking-view-actions', $response['tpl_data'], TRUE);

		out_json($response);
	}

	public function booking_installment_setup($slug_url=0, $db_table='booking_amounts', $primary_id='booking_amount_id')
	{
		check_login();
		check_viewer_login();
		restrict_role(CRM_ROLE);
		
		$created_by_id = $this->session->userdata('user_id');
		$update_id = $_POST['update_id'];
		$plan_type = $_POST['plan_type'];
		$inventory_id = $_POST['inventory_id'];
		$amount_date = $_POST['amount_date'];
		$amount = $_POST['amount'];
		$payment_method = $_POST['payment_method'];
		$reference = isset($_POST['reference']) ? $_POST['reference'] : '';
		
		// Accessing uploaded file
		$file_name = $_FILES['proof_image']['name'];
		$file_temp = $_FILES['proof_image']['tmp_name'];
		$file_size = $_FILES['proof_image']['size'];
		$file_type = $_FILES['proof_image']['type'];
		
		$upload_dir = FCPATH . 'uploads/booking_receipt/';
		$upload_url = site_url().'uploads/booking_receipt/';
		if (!is_dir($upload_dir))
		{
			if (!mkdir($upload_dir, 0755, true))
			{
				out ('ERROR', 'Failed to create folders...');
				return;
			}
		}
		
		$config['upload_path']      = $upload_dir;
		$config['allowed_types']	= 'gif|jpg|jpeg|png|webp';
		$config['max_size']         = 5000;
		$config['max_width']        = 1900;
		$config['max_height']       = 1600;
		
		$this->upload->initialize($config);
		
		$proof_image = '';
		
		//Proof Image
		if (!$this->upload->do_upload('proof_image'))
			$error = array('error' => $this->upload->display_errors());
		else
			$data = array('upload_data' => $this->upload->data());
		
		$proof_image = isset($data['upload_data']['file_name']) ? $data['upload_data']['file_name'] : '';
		
		$serial = document_number(array('db_table' => 'booking_amounts'));
		$data = array(
			'booking_id' => $update_id,
			'inventory_id' => $inventory_id,
			'serial' => $serial,
			'amount_date' => $amount_date,
			'amount' => $amount,
			'payment_method' => $payment_method,
			'reference' => $reference,
			'created_by_id' => $created_by_id,
			'created_on' => time(),
		);
		$total_payable_amount = $_POST['amount'];
		$total_paid_amount = $this->booking->total_paid_amount($_POST["update_id"]);
		$total_paid_amount = $total_paid_amount->paid_amount ? $total_paid_amount->paid_amount : 0;
		
		
		if($plan_type == 'Installment')
		{
			$total_installment = $this->booking->paid_inventory_installment_list($_POST['inventory_id']);
		}
		else
		{
			$total_installment = $this->booking->paid_inventory_milestone_installment_list($_POST['inventory_id']);
		}
		
		$total_unit_price = $this->inventory->total_unit_price($_POST['inventory_id']);
		$total_paid_installment_till = $total_paid_amount + $total_payable_amount;
		if($total_paid_installment_till > $total_unit_price)
		{
			out ('error', 'Installment amount exceed total price.');
			return false;
		}
		
		//Installment
		$rowNumber = 1;
		foreach ($total_installment as $installment) {
			if ($total_payable_amount > 0) {
				if ($total_paid_amount >= $installment->amount) {
					$total_paid_amount -= $installment->amount;
				} else {
					$remaining = $installment->amount - $total_paid_amount;
					if ($remaining > 0) {
						if ($total_payable_amount >= $remaining) {
							$data['amount'] = $remaining;
							$return_id = $this->crud->add($data, $db_table);
							$total_payable_amount -= $remaining;
							$total_paid_amount = 0;
						} else {
							$data['amount'] = $total_payable_amount;
							$return_id = $this->crud->add($data, $db_table);
							$total_payable_amount = 0;
						}
					}
				}
			}
			$rowNumber++;
		}
		
		if(!empty($return_id))
		{
			//Challans Add
			$data2 = array(
				'booking_id' => $update_id,
				'inventory_id' => $inventory_id,
				'serial' => $serial,
				'challan_date' => $amount_date,
				'challan_amount' => $amount,
				'challan_payment_method' => $payment_method,
				'reference' => $reference,
				'proof_image' => $proof_image,
				'created_by_id' => $created_by_id,
				'created_on' => time(),
			);
			$this->crud->add($data2, 'challans');
			
			out ('SUCCESS', 'Record added successfully.');
		}
	}

	public function challan_update_setup_post($slug_url=0, $db_table='challans', $primary_id='serial')
	{
		check_login();
		check_viewer_login();
		restrict_role(CRM_ROLE);

		$created_by_id = $this->session->userdata('user_id');
		$update_id = $_POST['update_id'];
		$amount_date = $_POST['amount_date'];
		$payment_method = $_POST['payment_method'];
		$reference = isset($_POST['reference']) ? $_POST['reference'] : '';

		// Check if file is uploaded
		if (isset($_FILES["proof_image2"]) && $_FILES["proof_image2"]["error"] === UPLOAD_ERR_OK) {
			// Accessing uploaded file
			$file_name = $_FILES['proof_image2']['name'];
			$file_temp = $_FILES['proof_image2']['tmp_name'];
			$file_size = $_FILES['proof_image2']['size'];
			$file_type = $_FILES['proof_image2']['type'];
			
			$upload_dir = FCPATH . 'uploads/booking_receipt/';
			$upload_url = site_url().'uploads/booking_receipt/';
			if (!is_dir($upload_dir)) {
				mkdir($upload_dir, 0755, true);
			}

			$config['upload_path']      = $upload_dir;
			$config['allowed_types']    = 'gif|jpg|jpeg|png|webp';
			$config['max_size']         = 5000;
			$config['max_width']        = 1900;
			$config['max_height']       = 1600;

			$this->upload->initialize($config);

			$proof_image = '';
			
			// Proof Image Upload
			if (!$this->upload->do_upload('proof_image2')) {
				$error = array('error' => $this->upload->display_errors());
				echo "File upload failed: " . $error['error'];
				return;
			} else {
				$data = array('upload_data' => $this->upload->data());
				$proof_image = $data['upload_data']['file_name'];
			}
		} else {
			// Default image if no new file uploaded
			$proof_image = isset($_POST["update_proof_image"]) ? $_POST["update_proof_image"] : 'no-image.png';
		}

		// Prepare data for updating
		$data = array(
			'challan_date' => $amount_date,
			'challan_payment_method' => $payment_method,
			'reference' => $reference,
			'proof_image' => $proof_image,
			'updated_by_id' => $created_by_id,
			'updated_on' => time(),
		);
		$this->crud->update($data, $update_id, $db_table, $primary_id);

		// Additional data for other table if needed
		$data2 = array(
			'amount_date' => $amount_date,
			'payment_method' => $payment_method,
			'reference' => $reference,
			'proof_image' => $proof_image,
			'updated_by_id' => $created_by_id,
			'updated_on' => time(),
		);
		$this->crud->update($data2, $update_id, 'booking_amounts', $primary_id);
		
		out('SUCCESS', 'Record updated successfully.');
	}
	
	public function challan_receipt_delete()
	{
		check_login();
		check_viewer_login();
		restrict_role(CRM_ROLE);
		
		$delete_id = request_var('delete_id', '');
		$this->crud->delete($delete_id, 'booking_amounts', 'serial');
		$this->crud->delete($delete_id, 'challans', 'serial');
		
		out ('SUCCESS', 'Record deleted successfully.');
	}
	
	//Due Surcharge
	public function booking_duesurcharge_setup($slug_url=0, $db_table='due_surcharges', $primary_id='due_surcharge_id')
	{
		check_login();
		check_viewer_login();
		restrict_role(CRM_ROLE);
		
		$created_by_id = $this->session->userdata('user_id');
		$update_id = $_POST['update_id'];
		$due_surcharge_id = $_POST['due_surcharge_id'];
		$date = $_POST['date'];
		$amount = $_POST['amount'];
		
		$data = array(
			'booking_id' => $update_id,
			'surcharge_date' => $date,
			'amount' => $amount,
		);
		
		if($due_surcharge_id == '')
		{
			$d = array(
				'created_by_id' => $created_by_id,
				'created_on' => time(),
			);
			$data = array_merge($data, $d);
			$id = $this->crud->add($data, $db_table);
		}
		else
		{
			$d = array(
				'updated_by_id' => $created_by_id,
				'updated_on' => time(),
			);
			$data = array_merge($data, $d);
			$this->crud->update($data, $due_surcharge_id, $db_table, $primary_id);
		}
		
		out_json( array(
			'success' => 1,
			'message' => 'SUCCESS',
			'RedirectTo' => site_url('booking/view/'.$slug_url),
		));
	}
	
	public function duesurcharge_delete()
	{
		check_login();
		check_viewer_login();
		restrict_role(CRM_ROLE);
		
		$delete_id = request_var('delete_id', '');
		$this->crud->delete($delete_id, 'due_surcharges', 'due_surcharge_id');
		
		out ('SUCCESS', 'Record deleted successfully.');
	}
	
}
