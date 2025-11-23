<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'third_party/vendor/autoload.php';

class Pdf extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->helper('function');
		
		$this->load->model('booking_model', 'booking');
		$this->load->model('inventory_model', 'inventory');
		$this->load->model('collection_model', 'collection');
		$this->load->model('voucher_model', 'voucher');
		$this->load->model('reports_model', 'reports');
		$this->load->model('leads_model', 'leads');
	}
	
	function account_statement()
	{
		check_login();
		restrict_role(CRM_ROLE);

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

			//'title' => "Booking View",
			//'page' => "projects/booking/booking-view"
		];
		$html = $this->load->view('pdf/account_statement', $data, true);

		//pre_print($html); exit;
		
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
	
	function challan_receipt()
	{
		check_login();
		restrict_role(CRM_ROLE);

		$challan_id = $this->input->get('challan_id');
		$data['record_list'] = $this->collection->challan_receipt($challan_id);

		$filename = $data['record_list']->customer_name."-".$data['record_list']->unit_number."-challan-".date('d-M-Y-g-i');
		$html = $this->load->view('pdf/challan_receipt', $data, true);

		$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'orientation' => 'L',
			'default_font' => '',
			'margin_left' => 5,
			'margin_right' => 5,
			'margin_top' => 12,
			'margin_bottom' => 3,
			'margin_header' => 5,
			'margin_footer' => 5,
		]);
		
		$mpdf->WriteHTML($html); // Write the HTML code to the PDF
		$mpdf->Output($filename.'.pdf', 'D');
	}
	
	function voucher_download()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$voucher_id = $this->input->get('voucher_id');
		$data['record_list'] = $this->voucher->voucher_detail_list($voucher_id);
		$data['voucher_details'] = $this->voucher->voucher_details_list($voucher_id);
		
		$filename = "voucher-".$data['record_list']->voucher_id."-".date('d-M-Y-g-i');
		$html = $this->load->view('pdf/voucher_download', $data, true);
		
        $mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'default_font' => '',
			'margin_left' => 5,
			'margin_right' => 5,
			'margin_top' => 12,
			'margin_bottom' => 3,
			'margin_header' => 5,
			'margin_footer' => 5,
		]);
		
		$mpdf->WriteHTML($html); // Write the HTML code to the PDF
		$mpdf->Output($filename.'.pdf', 'D');
	}
	
	function coa_download()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$project_id = $this->input->get('project_id');
		$coa_level = $this->input->get('coa_level');
		
		$records = $this->reports->chart_of_account_search($project_id);
		$result = aggregate_coa_totals($records);
		
		// Filter by level if specified
		if (!empty($coa_level)) {
			$result['records'] = filter_by_level($result['records'], $coa_level);
			$result['totals'] = calculate_filtered_totals($result['records'], $coa_level);
		}
		
		$data['record_list'] = $result['records'];
		$data['grand_totals'] = $result['totals'];
		
		$filename = "chart-of-account-".date('d-M-Y-g-i');
		$html = $this->load->view('pdf/coa_download', $data, true);
		
		$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'default_font' => '',
			'margin_left' => 5,
			'margin_right' => 5,
			'margin_top' => 12,
			'margin_bottom' => 3,
			'margin_header' => 5,
			'margin_footer' => 5,
		]);
		
		$mpdf->WriteHTML($html); // Write the HTML code to the PDF
		$mpdf->Output($filename.'.pdf', 'D');
	}
	
	function finance_ledger_download()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$project_id = request_var('project_id', '');
		$query		= request_var('query', '');
		$query_name	= request_var('query_name', '');
		$from_date	= request_var('from_date', '');
		$to_date	= request_var('to_date', '');
		$data['record_list'] = $this->reports->finance_ledger_search($project_id, $query, $from_date, $to_date);
		$data['query_name'] = $query_name;
		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;
		
		$filename = "finance-ledger-".date('d-M-Y-g-i');
		$html = $this->load->view('pdf/finance_leger_download', $data, true);
		
        $mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'default_font' => '',
			'margin_left' => 5,
			'margin_right' => 5,
			'margin_top' => 12,
			'margin_bottom' => 3,
			'margin_header' => 5,
			'margin_footer' => 5,
		]);
		
		$mpdf->WriteHTML($html); // Write the HTML code to the PDF
		$mpdf->Output($filename.'.pdf', 'D');
	}
	
	//Receipt Download
	function receipt_download()
	{
		check_login();
		restrict_role(CRM_ROLE);
		
		$receipt_id = request_var('receipt_id', '');
		$data['record_list'] = $this->leads->receipt_detail_list($receipt_id);
		
		$filename = $data['record_list']->name."-".$data['record_list']->unit_number."-".date('d-M-Y-g-i');
		$html = $this->load->view('crm/pdf/receipt', $data, true);

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
		$mpdf->WriteHTML($html); // Write the HTML code to the PDF
        $mpdf->Output($filename.'.pdf', 'D');
	}
	
}
