<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'third_party/vendor/autoload.php';

class Client extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->helper('function');

		$this->load->model('client_model', 'client');
		$this->load->model('inventory_model', 'inventory');
		$this->load->model('booking_model', 'booking');
	}
	
	public function index()
	{
		$data['title'] = "Statement Form | Asaan Home (Pvt) Ltd.";
		$data['page'] = "client-booking/client";
		$this->load->library('Front_Layout', $data);
	}
	
	public function statement()
	{
		$data['title'] = "Statement | Asaan Home (Pvt) Ltd.";
		$data['page'] = "client-booking/statement";
		$this->load->library('Front_Layout', $data);
	}
	
	public function generate_statement($return_data = 0)
	{
		$registration = $_POST['registration'];
		$cnic = $_POST['cnic'];

		$statement = $this->client->get_user_statement($registration, $cnic);

		if(!empty($statement))
		{
			$record_list = $this->booking->booking_detail_list($statement->booking_id);
			$inventory_id = isset($record_list->inventory_inventory_id) ? $record_list->inventory_inventory_id : null;
			$challan_list = $this->booking->booking_challan_list($statement->booking_id);
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
				'booking_id' => $statement->booking_id,
				'booking_inventory_id' => $inventory_id,
				//'record_list' => $record_list,
				//'challan_list' => $challan_list,
				//'installment_list' => $installment_list,
				//'duesurcharge_list' => $this->booking->booking_duesurcharge_list($slug_url),
				//'duesurcharge_waive_off' => $this->booking->booking_duesurcharge_waive_off($slug_url),
				//'installment_count' => count($installment_list),

				// ✅ Statement data for view
				'statement_rows' => $statement_data['statement_rows'],
				'total_due' => $statement_data['total_due'] ? $statement_data['total_due'] : 0,
				'total_paid' => $statement_data['total_paid'] ? $statement_data['total_paid'] : 0,
				'total_balance' => $statement_data['total_balance'] ? $statement_data['total_balance'] : 0,
				'total_surcharge' => $statement_data['total_surcharge'] ? $statement_data['total_surcharge'] : 0,
			];

			if ($return_data)
				return $data;
		}
		else
		{
			$data = [
				'error_message' => "No record found for the provided Registration # and CNIC.",
				'statement_rows' => [],
				'title' => "Account Statement",
				'page'  => "client-booking/statement"
			];

			$this->load->library('Front_Layout', $data);
			return;
		}

		$data['title'] = "Account Statement";
		$data['page'] = "client-booking/statement";
		$this->load->library('Front_Layout', $data);
	}
	
	function account_statement()
	{
		$booking_id = $this->input->get('booking_id');
		$inventory_id = $this->input->get('inventory_id');

		$record_list = $this->booking->booking_detail_list($booking_id);
		$challan_list = $this->booking->booking_challan_list($booking_id);
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

		$first_installment_amount = $installment_list[0]->amount;

		$filename = $record_list->customer_name."-".$record_list->unit_number."-".date('d-M-Y-g-i');
		$data = [
			'record_list' => $record_list,
			'challan_list' => $challan_list,
			'installment_list' => $installment_list,
			'duesurcharge_list' => $this->booking->booking_duesurcharge_list($booking_id),
			'duesurcharge_waive_off' => $this->booking->booking_duesurcharge_waive_off($booking_id),
			'first_installment_amount' => $first_installment_amount,
			'installment_count' => count($installment_list),

			// ✅ Statement data for view
			'statement_rows' => $statement_data['statement_rows'],
			'total_due' => $statement_data['total_due'],
			'total_paid' => $statement_data['total_paid'],
			'total_balance' => $statement_data['total_balance'],
			'total_surcharge' => $statement_data['total_surcharge'],
		];
		$html = $this->load->view('pdf/account_statement', $data, true);

        $mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'default_font' => '',
			'margin_left' => 5,
			'margin_right' => 5,
			'margin_top' => 12,
			'margin_bottom' => 3,
			'margin_header' => 5,
			'margin_footer' => 5,
			'pagenumPrefix' => 'Page ',
			'pagenumSuffix' => ' - ',
			'nbpgPrefix' => ' out of ',
			'nbpgSuffix' => ' pages'
		]);
		
		$mpdf->SetFooter('{PAGENO}{nbpg}');
		$mpdf->WriteHTML($html);
        $mpdf->Output($filename.'.pdf', 'D');
	}
}
