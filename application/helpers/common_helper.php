<?php $date_time_formats = array(
	"M d, Y", "d M Y", "Y/M/d", "M d Y", "m/d/Y", "Y-m-d", "d-M-Y",
	"M d, Y g:i a", "d M Y g:i a", "Y/M/d g:i a", "M d Y g:i a", "m/d/Y g:i a", "Y-m-d g:i a",
	"M d, Y g:i:s a", "d M Y g:i:s a", "Y/M/d g:i:s a", "M d Y g:i:s a", "m/d/Y g:i:s a", "Y-m-d g:i:s a",
	"M d, Y H:i:s", "d M Y H:i:s", "Y/M/d H:i:s", "M d Y H:i:s", "m/d/Y H:i:s", "Y-m-d H:i:s",
	"M d, YTH:i:s", "d M YTH:i:s", "Y/M/dTH:i:s", "M d YTH:i:s", "m/d/YTH:i:s", "Y-m-dTH:i:s",
	"M d, YTH:i:sZ", "d M YTH:i:sZ", "Y/M/dTH:i:sZ", "M d YTH:i:sZ", "m/d/YTH:i:sZ", "Y-m-dTH:i:sZ", "Y-m-dTH:i",
	"M d, YTH:i:s.uZ", "d M YTH:i:s.uZ", "Y/M/dTH:i:s.uZ", "M d YTH:i:s.uZ", "m/d/YTH:i:s.uZ", "Y-m-d H:i:s.uZ",
	"Y-m-d H:i:s.000000",
	// Sql Formats
	"Y-m-d H:i", "Y-m-dTH:i", "d-M-Y g:i a",
);

define('DateTimeFormats', $date_time_formats);

function out( $msg, $data = null, $params=array() )
{
	$m = array
	(
		'msg' 	=> $msg,
		'data'	=> $data
	);

	header('Content-Type: application/json');
	echo json_encode( array_merge($m, $params) );
	die();
}

function out_json($params=array() )
{
	header('Content-Type: application/json');
	echo json_encode($params);
	die();
}

function request_var( $var_name, $default_value = '' )
{
	if( isset( $_REQUEST[ $var_name ] ) )
	{
		if(!isset($_REQUEST[ $var_name ]))
			return $default_value;
		else
			return $_REQUEST[ $var_name ];
	}
	else
		return $default_value;
}

function string_to_boolean($string){
	return in_array(strtolower($string), array("yes", "true", "t", "1"));
}

function boolean_to_yes_no($string){
	return string_to_boolean($string) ? 'Yes':'No';
}

function search_array_key(&$val, $key)
{
	global $num_keys;
	if (strpos($key,'CLASS_') !== false) {
		$num_keys++;
	}
	//echo $num_keys;
}

function ttruncat($text,$numb)
{
	if (strlen($text) > $numb)
	{
		$text = substr($text, 0, $numb);
		$text = substr($text,0,strrpos($text," "));
		$etc = " ...";
		$text = $text.$etc;
	}
	return $text;
}

if ( ! function_exists('build_sorter'))
{
	function build_sorter($key, $sort = 'desc')
	{
		if ($sort == 'asc')
		{
			return function ($a, $b) use ($key)
			{
				if (isset($a->$key, $b->$key))
					return cmp_asc($a->$key, $b->$key);
			};
		}
		else
		{
			return function ($a, $b) use ($key)
			{
				if (isset($a->$key, $b->$key))
					return cmp_desc($a->$key, $b->$key);
			};
		}
	}
}

if ( ! function_exists('build_sorter_array'))
{
	function build_sorter_array($key, $sort = 'desc')
	{
		if ($sort == 'asc')
		{
			return function ($a, $b) use ($key)
			{
				if (isset($a[$key], $b[$key]))
					return cmp_asc($a[$key], $b[$key]);
			};
		}
		else
		{
			return function ($a, $b) use ($key)
			{
				if (isset($a[$key], $b[$key]))
					return cmp_desc($a[$key], $b[$key]);
			};
		}
	}
}

if ( ! function_exists('cmp_asc'))
{
	function cmp_asc($a, $b)
	{
		if ( $a < $b ) return -1;
		if ( $a > $b ) return 1;
		return 0; // equality
	}
}

if ( ! function_exists('cmp_desc'))
{
	function cmp_desc($a, $b)
	{
		if ( $a < $b ) return 1;
		if ( $a > $b ) return -1;
		return 0; // equality
	}
}

function clean($string)
{
	$string = str_replace(' ', '-', $string);
	return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
}

function date_time($date_time, $format='Y-m-d')
{
	date_default_timezone_set('Asia/Karachi');
	return date($format, $date_time);
}

function date_with_time($date_time, $format='d-m-Y - g:i a')
{
	date_default_timezone_set('Asia/Karachi');
	return date($format, $date_time);
}

function date_only($date_time, $format='d-M-Y')
{
	date_default_timezone_set('Asia/Karachi');
	return @date($format, $date_time);
}

function time_only($date_time, $format='g:i a')
{
	date_default_timezone_set('Asia/Karachi');
	return date($format, $date_time);
}

function get_date_object($date, $format='m/d/Y')
{
	date_default_timezone_set('Asia/Karachi');
	$date_obj = DateTime::createFromFormat($format, $date);

	if($date_obj === false)
	{
		foreach(DateTimeFormats as $date_format){
			$date_obj = DateTime::createFromFormat($date_format, $date);
			if($date_obj !== false)
				break;
		}
	}

	return $date_obj;
}

function get_date_time_object($date, $format='d-M-Y H:i:s')
{
	date_default_timezone_set('Asia/Karachi');
	$date_time_obj = DateTime::createFromFormat($format, $date);

	if($date_time_obj === false)
	{
		foreach(DateTimeFormats as $date_format){
			$date_time_obj = DateTime::createFromFormat($date_format, $date);
			if($date_time_obj !== false)
				break;
		}
	}

	return $date_time_obj;
}

// Insert into DB - Format: YYYY-MM-DD
function get_date_string_sql($str_date='')
{
	date_default_timezone_set('Asia/Karachi');
	if($str_date == '')
		$str_date = date('d-M-Y');

	$dateObj = get_date_object($str_date);
	if($dateObj === false)
	{
		foreach(DateTimeFormats as $date_format){
			$dateObj = DateTime::createFromFormat($date_format, $str_date);
			if($dateObj !== false)
				break;
		}
	}

	return $dateObj->format('d-M-Y');
}

// Insert into DB - Format: YYYY-MM-DD H:i:s
function get_date_time_string_sql($str_date='', $for_html_input=false)
{
	if(is_null($str_date))
		return "";

	$str_date = str_replace("T", " ", $str_date);

	date_default_timezone_set('Asia/Karachi');
	if($str_date == '')
		$str_date = date('d-M-Y H:i:s');

	$dateObj = get_date_time_object($str_date);
	if($dateObj === false)
	{
		foreach(DateTimeFormats as $date_format){
			$dateObj = DateTime::createFromFormat($date_format, $str_date);
			if($dateObj !== false)
				break;
		}
	}

	if($dateObj === false)
		return "";

	if($for_html_input)
		return $dateObj->format('d-M-Y\TH:i');
	else
		return $dateObj->format('d-M-Y g:i A');
}

// Display on frontend - Format: d-M-Y
function get_date_string($date_string, $format='d-M-Y', $from_format='Y-m-d')
{
	date_default_timezone_set('Asia/Karachi');
	$date = get_date_object($date_string, $from_format);
	if($date === false){
		foreach(DateTimeFormats as $f){
			$date = get_date_object($date_string, $f);
			if($date !== false)
				break;
		}

	}
	if($date === false)
		return '';
	return $date->format($format);
}

// Display on frontend - Format: d-M-Y H:i:s
function get_date_time_string($date_time_string='current', $to_format='d-M-Y h:i:s a', $from_format='Y-m-d H:i:s')
{
	date_default_timezone_set('Asia/Karachi');

	$date = get_date_time_object($date_time_string, $from_format);
	if($date === false){
		foreach(DateTimeFormats as $f){
			$date = get_date_time_object($date_time_string, $f);
			if($date !== false)
				break;
		}

	}
	if($date === false)
		return '';

	if(in_array($to_format, array("html_input", "date_input")))
		$to_format = "Y-m-d\TH:i:s";

	$final_string = $date->format($to_format);
	if(strpos($final_string, "01-Jan-1970") !== FALSE)
		$final_string = "";

	return $final_string;
}

function add_days_to_date($date1,$number_of_days){
	$str =' + '. $number_of_days. ' days';
	$date2= date('Y-m-d H:i:s', strtotime($date1. $str));
	return $date2;
}

function time_ago($date) {
	$timestamp = strtotime($date);

	$strTime = array("second", "minute", "hour", "day", "month", "year");
	$length = array("60","60","24","30","12","10");

	$currentTime = time();
	if($currentTime >= $timestamp) {
		$diff     = time()- $timestamp;
		for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
			$diff = $diff / $length[$i];
		}

		$diff = round($diff);
		return $diff . " " . $strTime[$i] . "(s) ago ";
	}
}

// Log History Add
function log_history($db_table, $row_id="", $primary_key=false, $updated_by=false) {
	$CI =& get_instance();

	if(is_array($db_table)){
		$row_id = $db_table['row_id'] ?? $row_id;
		$primary_key = $db_table['primary_key'] ?? $primary_key;
		$updated_by = $db_table['updated_by'] ?? $updated_by;
		$db_table = $db_table['db_table'];
	}

	$CI->load->model('log_history_model', 'log_history');
	$current_user_id = $CI->session->userdata('user_id');

	// Trying to update table last updated details
	if(!$updated_by){

		if(!$primary_key){
			$query = $CI->db->query("SHOW columns from $db_table ");
			$data = $query->result();
			foreach($data as $k => $v){
				if($v->Key == "PRI"){
					$primary_key = $v->Field;
					break;
				}
			}
		}

		$query = $CI->db->query("SHOW columns from $db_table LIKE 'updated_by'");
		$updated_by = $query->num_rows() > 0;
	}

	if($updated_by){
		$up = array(
			'updated_by' => $current_user_id,
			'updated_on' => get_date_time_string_sql()
		);
		$up_conditions = array($primary_key => $row_id);
		$CI->crud->update_where($db_table, $up_conditions, $up);
	}

	$log_data = array(
		'table_name' => $db_table,
		'table_value' => $row_id,
		'created_by_id' => $current_user_id,
	);
	$CI->log_history->add($log_data);
}

//Log History Delete
function log_history_delete($db_table, $db_value)
{
	$CI =& get_instance();

	$CI->load->model('log_history_model', 'log_history');
	$CI->log_history->delete($db_table, $db_value);
}

// 1st, 2nd, 3rd, 4th so on
function ordinal_number($num)
{
	if (!in_array(($num % 100), array(11,12,13)))
	{
		switch ($num % 10)
		{
			// Handle 1st, 2nd, 3rd
			case 1: return $num.'st';
			case 2: return $num.'nd';
			case 3: return $num.'rd';
		}
	}
	return $num.'th';
}

// Document # Show
function document_number($kwargs=array())
{
	$CI =& get_instance();

//	$kwargs = array(
//		'db_table' => '',
//		'primary_key' => '',
//		'document_number' => 'document_number',
//		'prefix' => '',
//		'city_id' => ''
//	);
	$get_next_number = isset($kwargs['get_next_number']) ? $kwargs['get_next_number'] : true;
	$prefix = isset($kwargs['prefix']) ? $kwargs['prefix'] : false;
	if(!isset($kwargs['primary_key']))
		$kwargs['primary_key'] = 'serial';

	$document_number = isset($kwargs['document_number']) ? $kwargs['document_number'] : '';
	$record = isset($kwargs['record']) ? $kwargs['record'] : FALSE;

	if($prefix === 'SQ-'){
		$dates = explode('-', $record->quotation_date);
		$year = substr( $dates[0], -2);
		$prefix .= "$record->branch_country_code-$record->branch_city_code-$record->branch_number$year";
	}

	$extra_checks = isset($kwargs['extra_checks']) ? $kwargs['extra_checks'] : array();
	if($document_number == '' and $get_next_number){
		$CI->load->model('api_model', 'api');
		$document_number = $CI->api->get_document_number($kwargs['db_table'], $kwargs['primary_key'], $extra_checks);
	}

	if($prefix and $document_number != '')
		$document_number = $prefix.str_pad($document_number, 6, "0", STR_PAD_LEFT);

	return $document_number;

}

// Document # Show
function chart_of_account_number($kwargs=array())
{
	$CI =& get_instance();
	$CI->load->model('api_model', 'api');

	$coa_number = $CI->api->get_coa_number(
        $kwargs['project_id'],
        $kwargs['level_1_code'],
        $kwargs['level_2_code'],
        $kwargs['level_3_code'],
        $kwargs['primary_key'],
        $kwargs['number_size'],
        $kwargs['account_level']
    );

    return $coa_number;
}

// results for array1 (when it is in more, it is in array1 and not in array2. same for less)
function compare_multi_Arrays($array1, $array2){

	$result = array("more"=>array(), "less"=>array(), "diff"=>array());

	foreach($array1 as $k => $v) {

		if(is_object($v))
			$v = (array)$v;

		if(is_array($v) && isset($array2[$k]) && is_array($array2[$k])){
			$sub_result = compare_multi_Arrays($v, $array2[$k]);
			//merge results
			foreach(array_keys($sub_result) as $key){
				if(!empty($sub_result[$key])){
					$result[$key] = array_merge_recursive($result[$key],array($k => $sub_result[$key]));
				}
			}
		}else{

			if(isset($array2[$k])){

				if(is_decimal($v) or is_decimal($array2[$k])){
					$v = no_currency($v);
					$array2[$k] = no_currency($array2[$k]);
				}

				if(md5($v) !== md5($array2[$k])){
					$result["diff"][$k] = array("from"=>$v,"to"=>$array2[$k]);
				}
			}else{
				$result["more"][$k] = $v;
			}
		}
	}
	foreach($array2 as $k => $v) {
		if(!isset($array1[$k])){
			$result["less"][$k] = $v;
		}
	}
	return $result;
}

//Invoice Paid Conditions
function is_proforma_invoice_paid($record_list, $register_payment)
{
	if ((isset($register_payment->payment_type) and $register_payment->payment_type != 0) and (($register_payment->payment_type != 2 and ($record_list->status == 3 or $record_list->status == 4)) or ($register_payment->payment_proceed == 2 and $register_payment->payment_status == 2)))
		return true;
	else
		return false;
}

//Notifications
function send_notifications($notifications=array())
{
	$CI =& get_instance();
	$current_user_id = $CI->session->userdata('user_id');

	if(!isset($notifications[0]) or !is_array($notifications[0]))
		$notifications = array($notifications);

	$data = array();
	foreach($notifications as $notify)
	{
		if(!is_array($notify['to_user']))
			$notify['to_user'] = array($notify['to_user']);

		// Get all users with notify role
		$notify['to_user'] = array_unique(array_merge($notify['to_user'], $CI->users->with_role(6)));

		foreach($notify['to_user'] as $to_user)
		{
			if((int)$to_user == (int)$current_user_id)
				continue;

			$n_data = $notify;
			$n_data['to_user'] = $to_user;
			$n_data['from_user'] = $current_user_id;
			$n_data['created_on'] = get_date_time_string_sql();
			$data[] = $n_data;
		}
	}
	if(count($data) > 0){
		$CI->crud->add_batch($data, 'notifications');
	}
}

// $permission_type = SQ, SI etc...
// $permission_type = Approve, Edit, View etc...
function check_permission($permission_name='', $permission_for='', $redirect_to_home=true)
{
	$CI =& get_instance();
	
	$CI->load->model('users_model', 'users');
	if(!$CI->users->has_permission($permission_name, $permission_for))
	{
		if($redirect_to_home)
		{
			redirect('dashboard/?show_message=permission_denied');
			die();
		}
		return false;

	}
	return true;
}

function has_permission($permission_name, $permission_for=''){
	$CI = get_instance();
	if(!isset($CI->users))
		$CI->load->model('users_model', 'users');
	return $CI->users->has_permission($permission_name, $permission_for) == True;
}

function get_active_vat($vat_type="0"){

	$CI = get_instance();

//	if(!isset($CI->setting))
//		$CI->load->model('setting_model', 'setting');

	return $CI->setting->get_active_vat($vat_type);

}

function no_currency($v=0, $decimals=4, $thousand_separator='', $check_empty=0){

	if($check_empty and $v == "")
		return $v;

	if(is_array($v))
		return $v;

	return number_format((float)preg_replace('/[^0-9\.-]/', '', $v), $decimals, '.', $thousand_separator);
}

function calculate_percentage($v1=0, $v2=4){
	$percentage = 0;
	if($v2 > 0)
		$percentage = ($v1*100)/($v2);
	return no_currency($percentage, 2);
}

// + and - operations are performed after converting values to positives.
function calculate_value($a, $operator, $b){

	$result = "";

	if($operator === "+")
		$result = @(abs($a) + abs($b));

	if($operator === "-")
		if(@$a < 0)
			$result = @($a - abs($b));
		else
			$result = @(abs($a) - abs($b));

	if($operator === "/")
		$result = @($a / $b);

	if($operator === "*")
		$result = @($a * $b);

	return $result;

}

if ( ! function_exists('is_decimal'))
{
	function is_decimal ($price){
		$price_ex = explode('.', $price);
		if(isset($price_ex[1]) and strlen($price_ex[1]) === 4)
			return true;

		return false;
	}
}

if ( ! function_exists('get_posted_data'))
{
	function get_posted_data ($posted_key='data'){

		$post_data = array();
		if(isset($_REQUEST[$posted_key])){

			$form_data = request_var($posted_key, '{}');
			$form_data = str_replace('%5B', '[', $form_data);
			$form_data = str_replace('%5D', ']', $form_data);
			$form_data = str_replace('%22"', '"', $form_data);
			$form_data = str_replace('%20', ' ', $form_data);
			$form_data = str_replace('%2C', ',', $form_data);
			$form_data = str_replace('%3A', ':', $form_data);
			$form_data = str_replace('%2F', '/', $form_data);
			$form_data = str_replace('%3D', '=', $form_data);
			$form_data = str_replace('%23', '#', $form_data);
//			$form_data = json_decode(urldecode($form_data), true);
			$form_data = json_decode($form_data, true);
			$post_data["data"] = $form_data;

			foreach ($form_data as $k => $v) {
				$var_name = $k;
				if(is_array($v))
				{
					foreach($v as $i => $j){
						if(!is_array($j) and @strpos($j, 'SAR') !== FALSE)
							$v[$i] = no_currency($j);
					}
				}else
					if(@strpos($v, 'SAR') !== FALSE)
						$v = no_currency($v);

				if(strpos(@$var_name, '[]') !== FALSE) {
					$name_split = explode('[]', $var_name);
					$var_name = $name_split[0];
					if(!is_array($v))
						$v = array($v);
				}
				$post_data[$var_name] = $v;

			}

		}

		return $post_data;

	}
}

if ( ! function_exists('add_remove')) {
	function add_remove($v1, $v2, $type)
	{

		if ($type == 0) {
			return $v1 - abs($v2);
		} else
			return $v1 + abs($v2);

	}
}

if ( ! function_exists('equalise_multi_array_params')) {
	function equalise_multi_array_params($urls_array=array(), $max_params=array()){

		foreach($urls_array as $index => $url_data){

			foreach($url_data as $k => $v){
				$max_params[$k] = FALSE;
			}
			foreach($max_params as $k1 => $v1){
				if(!isset($urls_array[$index][$k1]))
					$urls_array[$index][$k1] = FALSE;
			}

			foreach($url_data as $k => $v){

				if(is_array($v) and is_array(@$v[0]))
					$urls_array[$index][$k] = equalise_multi_array_params($urls_array[$index][$k], $max_params);
			}
		}
		return $urls_array;

	}
}

function build_menu_urls_html($urls_array = FALSE) {

	$CI = &get_instance();
	$urls_html = "";

	if($urls_array === FALSE) {
		$urls_array = menu_urls();
	}

	foreach($urls_array as $url_data){
		$urls_html .= $CI->load->view("admin/snippets/menu-item", $url_data, true);
	}

	return $urls_html;
}

function get_image($image, $path, $size='', $class='') {
	
	if(!empty($image))
		$image = '<img src="'.site_url('uploads/'.$path.'/'.$image).'" alt="Image" width="'.$size.'" height="'.$size.'" class="'.$class.'">';
	else
		$image = '<img src="'.site_url('assets/images/no-image.png').'" alt="Image" width="'.$size.'" height="'.$size.'" class="'.$class.'">';
	
	return $image;
}

function get_image_url($image, $path) {
	
	if(!empty($image))
		$image_url = site_url('uploads/'.$path.'/'.$image);
	else
		$image_url = site_url('assets/images/no-image.png');
	
	return $image_url;
}

//Numbert convert into Words
function numberToWords($num) {
    $lessThan20 = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten",
                   "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen",
                   "Eighteen", "Nineteen"];
    $tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
    $thousands = ["", "Thousand", "Million", "Billion"];

    if ($num == 0) {
        return "Zero";
    }

    $result = "";

    function helper($num, $lessThan20, $tens) {
        if ($num < 20) {
            return $lessThan20[$num];
        } elseif ($num < 100) {
            return $tens[(int)($num / 10)] . ($num % 10 != 0 ? " " . $lessThan20[$num % 10] : "");
        } else {
            return $lessThan20[(int)($num / 100)] . " Hundred" . ($num % 100 != 0 ? " " . helper($num % 100, $lessThan20, $tens) : "");
        }
    }

    for ($i = 0, $unit = ""; $num > 0; $i++, $num = (int)($num / 1000)) {
        if ($num % 1000 != 0) {
            $result = helper($num % 1000, $lessThan20, $tens) . " " . $thousands[$i] . ($result != "" ? " " . $result : "");
        }
    }

    return $result;
}

//Date convert into string to time
function date_strtotime($from_date, $type) {
	date_default_timezone_set('Asia/Karachi');
    
	if($type == 'from')
	{
		$from_date = DateTime::createFromFormat('Y-m-d H:i:s', $from_date . ' 00:00:00');
		$date = $from_date->getTimestamp();
	}
	else
	{
		$to_date = DateTime::createFromFormat('Y-m-d H:i:s', $from_date . ' 23:59:59');
		$date = $to_date->getTimestamp();
	}

	return $date;
}

//Email send
function email_send($subject, $email) {
	$CI =& get_instance();
	$CI->load->library('email');
	$config['mailtype'] = 'html';
	$CI->email->initialize($config);
	
	$CI->email->from('Notification<noreply@asaanhomes.pk>');
	$CI->email->to($circle_email);
	$CI->email->subject($subject .' | Asaan Homes');
	$CI->email->message($message);
	$CI->email->send();
}

function copyrights() {
	return "Copyright &copy; " . date('Y') . " All rights reserved.";
}

function pre_print($array_data) {
	$data = '<pre>'.print_r($array_data, true).'</pre>';
	echo $data;
}

?>
