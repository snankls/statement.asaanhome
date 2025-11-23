<?php
//Enable Disable
function enable_disable($return_index = false)
{
	$data = array(
		1 => 'Enable',
		0 => 'Disable',
	);

	if($return_index !== false)
		return isset($data[$return_index]) ? $data[$return_index] : '';

	return $data;
}

//Status
function status($return_index = false)
{
	$data = array(
		'1' => 'Pending',
		'2' => 'Completed',
		'3' => 'Replied',
		'4' => 'Verified',
	);

	if($return_index !== false)
		return isset($data[$return_index]) ? $data[$return_index] : '';

	return $data;
}

//Inventory Status
function inventory_status($return_index = false)
{
	$data = array(
		'1' => 'Available',
		'2' => 'Booked',
	);

	if($return_index !== false)
		return isset($data[$return_index]) ? $data[$return_index] : '';

	return $data;
}

//⁠Property Types
function property_types($return_index = false)
{
	$data = array(
		'1' => 'House',
		'2' => 'Apartment',
		'3' => 'Residential Plot',
		'4' => 'Commercial Plot',
		'5' => 'Shop',
		'6' => 'Office',
		'7' => 'Farmhouse',
		'8' => 'Kiosk',
		'9' => 'Townhouse',
        '10' => 'Hotel Room',
	);

	if($return_index !== false)
		return isset($data[$return_index]) ? $data[$return_index] : '';

	return $data;
}

//⁠Area Unit
function area_unit($return_index = false)
{
	$data = array(
		'1' => 'Marla',
		'2' => 'Sq. Ft',
		'3' => 'Sq. Yd',
		'4' => 'Kanal',
		'5' => 'Acre',
	);

	if($return_index !== false)
		return isset($data[$return_index]) ? $data[$return_index] : '';

	return $data;
}

//⁠Relation
function relation($return_index = false)
{
	$data = array(
		'1' => 'Brother',
		'2' => 'Sister',
		'3' => 'Father',
		'4' => 'Mother',
		'5' => 'Uncle',
		'6' => 'Friend',
		'7' => 'Spouse',
		'8' => 'Son',
		'9' => 'Daughter',
	);

	if($return_index !== false)
		return isset($data[$return_index]) ? $data[$return_index] : '';

	return $data;
}

//Payment Method
function payment_method($return_index = false)
{
	$data = array(
		'1' => 'Bank',
		'2' => 'Cash',
		'3' => 'Adjustment',
	);

	if($return_index !== false)
		return isset($data[$return_index]) ? $data[$return_index] : '';

	return $data;
}

//Transaction Type
function transaction_type($return_index = false)
{
	$data = array(
		'1' => 'General',
		'2' => 'Opening',
		'3' => 'Closing',
	);

	if($return_index !== false)
		return isset($data[$return_index]) ? $data[$return_index] : '';

	return $data;
}

//Voucher Book
function voucher_book($return_index = false)
{
	$data = array(
		'1' => 'Bank Book',
		'2' => 'Cash Book',
		'3' => 'Journal Book',
	);

	if($return_index !== false)
		return isset($data[$return_index]) ? $data[$return_index] : '';

	return $data;
}

//User Module
function user_module($return_index = false)
{
	$data = array(
		'1' => 'Projects & Finance',
		'2' => 'CRM',
	);

	if($return_index !== false)
		return isset($data[$return_index]) ? $data[$return_index] : '';

	return $data;
}

//Leave Type
function leave_type($return_index = false)
{
	$data = array(
		'1' => 'Sick Leave',
		'2' => 'Casual Leave',
		'3' => 'Emergency Leave',
	);

	if($return_index !== false)
		return isset($data[$return_index]) ? $data[$return_index] : '';

	return $data;
}

//Application Status
function application_status($return_index = false)
{
	$data = array(
		'1' => 'Pending',
		'2' => 'Approved',
		'3' => 'Cancel',
	);

	if($return_index !== false)
		return isset($data[$return_index]) ? $data[$return_index] : '';

	return $data;
}

//Country List
function country_list($return_index = false)
{
    $data = array(
        '+93' => '🇦🇫 Afghanistan (+93)',
        '+355' => '🇦🇱 Albania (+355)',
        '+213' => '🇩🇿 Algeria (+213)',
        '+376' => '🇦🇩 Andorra (+376)',
        '+244' => '🇦🇴 Angola (+244)',
        '+54' => '🇦🇷 Argentina (+54)',
        '+61' => '🇦🇺 Australia (+61)',
        '+43' => '🇦🇹 Austria (+43)',
        '+994' => '🇦🇿 Azerbaijan (+994)',
        '+973' => '🇧🇭 Bahrain (+973)',
        '+880' => '🇧🇩 Bangladesh (+880)',
        '+375' => '🇧🇾 Belarus (+375)',
        '+32' => '🇧🇪 Belgium (+32)',
        '+501' => '🇧🇿 Belize (+501)',
        '+229' => '🇧🇯 Benin (+229)',
        '+975' => '🇧🇹 Bhutan (+975)',
        '+591' => '🇧🇴 Bolivia (+591)',
        '+387' => '🇧🇦 Bosnia and Herzegovina (+387)',
        '+267' => '🇧🇼 Botswana (+267)',
        '+55' => '🇧🇷 Brazil (+55)',
        '+673' => '🇧🇳 Brunei (+673)',
        '+359' => '🇧🇬 Bulgaria (+359)',
        '+226' => '🇧🇫 Burkina Faso (+226)',
        '+95' => '🇲🇲 Myanmar (Burma) (+95)',
        '+257' => '🇧🇮 Burundi (+257)',
        '+855' => '🇰🇭 Cambodia (+855)',
        '+237' => '🇨🇲 Cameroon (+237)',
        '+1' => '🇨🇦 Canada (+1)',
        '+238' => '🇨🇻 Cape Verde (+238)',
        '+236' => '🇨🇫 Central African Republic (+236)',
        '+235' => '🇹🇩 Chad (+235)',
        '+56' => '🇨🇱 Chile (+56)',
        '+86' => '🇨🇳 China (+86)',
        '+57' => '🇨🇴 Colombia (+57)',
        '+269' => '🇰🇲 Comoros (+269)',
        '+242' => '🇨🇬 Congo (+242)',
        '+243' => '🇨🇩 Congo (DRC) (+243)',
        '+682' => '🇨🇰 Cook Islands (+682)',
        '+506' => '🇨🇷 Costa Rica (+506)',
        '+385' => '🇭🇷 Croatia (+385)',
        '+53' => '🇨🇺 Cuba (+53)',
        '+357' => '🇨🇾 Cyprus (+357)',
        '+420' => '🇨🇿 Czech Republic (+420)',
        '+45' => '🇩🇰 Denmark (+45)',
        '+253' => '🇩🇯 Djibouti (+253)',
        '+593' => '🇪🇨 Ecuador (+593)',
        '+20' => '🇪🇬 Egypt (+20)',
        '+503' => '🇸🇻 El Salvador (+503)',
        '+240' => '🇬🇶 Equatorial Guinea (+240)',
        '+291' => '🇪🇷 Eritrea (+291)',
        '+372' => '🇪🇪 Estonia (+372)',
        '+251' => '🇪🇹 Ethiopia (+251)',
        '+358' => '🇫🇮 Finland (+358)',
        '+33' => '🇫🇷 France (+33)',
        '+241' => '🇬🇦 Gabon (+241)',
        '+995' => '🇬🇪 Georgia (+995)',
        '+49' => '🇩🇪 Germany (+49)',
        '+233' => '🇬🇭 Ghana (+233)',
        '+30' => '🇬🇷 Greece (+30)',
        '+299' => '🇬🇱 Greenland (+299)',
        '+502' => '🇬🇹 Guatemala (+502)',
        '+224' => '🇬🇳 Guinea (+224)',
        '+245' => '🇬🇼 Guinea-Bissau (+245)',
        '+592' => '🇬🇾 Guyana (+592)',
        '+509' => '🇭🇹 Haiti (+509)',
        '+504' => '🇭🇳 Honduras (+504)',
        '+36' => '🇭🇺 Hungary (+36)',
        '+91' => '🇮🇳 India (+91)',
        '+62' => '🇮🇩 Indonesia (+62)',
        '+98' => '🇮🇷 Iran (+98)',
        '+964' => '🇮🇶 Iraq (+964)',
        '+353' => '🇮🇪 Ireland (+353)',
        '+972' => '🇮🇱 Israel (+972)',
        '+39' => '🇮🇹 Italy (+39)',
        '+81' => '🇯🇵 Japan (+81)',
        '+962' => '🇯🇴 Jordan (+962)',
        '+7' => '🇷🇺 Kazakhstan (+7)',
        '+254' => '🇰🇪 Kenya (+254)',
        '+686' => '🇰🇮 Kiribati (+686)',
        '+965' => '🇰🇼 Kuwait (+965)',
        '+996' => '🇰🇬 Kyrgyzstan (+996)',
        '+856' => '🇱🇦 Laos (+856)',
        '+371' => '🇱🇻 Latvia (+371)',
        '+961' => '🇱🇧 Lebanon (+961)',
        '+266' => '🇱🇸 Lesotho (+266)',
        '+231' => '🇱🇷 Liberia (+231)',
        '+218' => '🇱🇾 Libya (+218)',
        '+423' => '🇱🇮 Liechtenstein (+423)',
        '+370' => '🇱🇹 Lithuania (+370)',
        '+352' => '🇱🇺 Luxembourg (+352)',
        '+389' => '🇲🇰 Macedonia (+389)',
        '+261' => '🇲🇬 Madagascar (+261)',
        '+265' => '🇲🇼 Malawi (+265)',
        '+60' => '🇲🇾 Malaysia (+60)',
        '+960' => '🇲🇻 Maldives (+960)',
        '+223' => '🇲🇱 Mali (+223)',
        '+356' => '🇲🇹 Malta (+356)',
        '+692' => '🇲🇭 Marshall Islands (+692)',
        '+222' => '🇲🇷 Mauritania (+222)',
        '+230' => '🇲🇺 Mauritius (+230)',
        '+52' => '🇲🇽 Mexico (+52)',
        '+373' => '🇲🇩 Moldova (+373)',
        '+377' => '🇲🇨 Monaco (+377)',
        '+976' => '🇲🇳 Mongolia (+976)',
        '+382' => '🇲🇪 Montenegro (+382)',
        '+212' => '🇲🇦 Morocco (+212)',
        '+258' => '🇲🇿 Mozambique (+258)',
        '+264' => '🇳🇦 Namibia (+264)',
        '+977' => '🇳🇵 Nepal (+977)',
        '+31' => '🇳🇱 Netherlands (+31)',
        '+64' => '🇳🇿 New Zealand (+64)',
        '+505' => '🇳🇮 Nicaragua (+505)',
        '+227' => '🇳🇳 Niger (+227)',
        '+234' => '🇳🇬 Nigeria (+234)',
        '+683' => '🇳🇺 Niue (+683)',
        '+47' => '🇳🇸 Norway (+47)',
        '+968' => '🇤🇲 Oman (+968)',
        '+92' => '🇵🇰 Pakistan (+92)',
        '+680' => '🇵🇱 Palau (+680)',
        '+970' => '🇵🇹 Palestinian Territory (+970)',
        '+507' => '🇵🇪 Panama (+507)',
        '+675' => '🇵🇹 Papua New Guinea (+675)',
        '+595' => '🇵🇾 Paraguay (+595)',
        '+51' => '🇵🇪 Peru (+51)',
        '+63' => '🇵🇵 Philippines (+63)',
        '+48' => '🇵🇱 Poland (+48)',
        '+351' => '🇵🇹 Portugal (+351)',
        '+974' => '🇦🇴 Qatar (+974)',
        '+242' => '🇱🇴 Republic of the Congo (+242)',
        '+40' => '🇮🇹 Romania (+40)',
        '+7' => '🇷🇹 Russia (+7)',
        '+250' => '🇷🇼 Rwanda (+250)',
        '+590' => '🇬🇲 Saint Barthelemy (+590)',
        '+290' => '🇷🇱 Saint Helena (+290)',
        '+1869' => '🇰🇰 Saint Kitts and Nevis (+1869)',
        '+1758' => '🇹🇸 Saint Lucia (+1758)',
        '+508' => '🇭🇰 Saint Pierre and Miquelon (+508)',
        '+1784' => '🇻🇴 Saint Vincent and the Grenadines (+1784)',
        '+685' => '🇬🇺 Samoa (+685)',
        '+378' => '🇬🇳 San Marino (+378)',
        '+239' => '🇻🇰 Sao Tome and Principe (+239)',
        '+966' => '🇸🇦 Saudi Arabia (+966)',
        '+221' => '🇷🇲 Senegal (+221)',
        '+381' => '🇺🇱 Serbia (+381)',
        '+248' => '🇷🇧 Seychelles (+248)',
        '+232' => '🇹🇱 Sierra Leone (+232)',
        '+65' => '🇮🇭 Singapore (+65)',
        '+421' => '🇮🇰 Slovakia (+421)',
        '+386' => '🇺🇸 Slovenia (+386)',
        '+677' => '🇨🇱 Solomon Islands (+677)',
        '+252' => '🇨🇹 Somalia (+252)',
        '+27' => '🇦🇺 South Africa (+27)',
        '+82' => '🇱🇸 South Korea (+82)',
        '+211' => '🇱🇧 South Sudan (+211)',
        '+34' => '🇩🇪 Spain (+34)',
        '+94' => '🇱🇮 Sri Lanka (+94)',
        '+249' => '🇨🇺 Sudan (+249)',
        '+597' => '🇷🇴 Suriname (+597)',
        '+268' => '🇸🇱 Eswatini (+268)',
        '+46' => '🇮🇸 Sweden (+46)',
        '+41' => '🇨🇳 Switzerland (+41)',
        '+963' => '🇸🇴 Syria (+963)',
        '+886' => '🇨🇾 Taiwan (+886)',
        '+992' => '🇨🇿 Tajikistan (+992)',
        '+255' => '🇹🇾 Tanzania (+255)',
        '+66' => '🇹🇰 Thailand (+66)',
        '+228' => '🇱🇲 Togo (+228)',
        '+690' => '🇲🇰 Tokelau (+690)',
        '+676' => '🇹🇺 Tonga (+676)',
        '+1868' => '🇹🇺 Trinidad and Tobago (+1868)',
        '+216' => '🇷🇰 Tunisia (+216)',
        '+90' => '🇹🇽 Turkey (+90)',
        '+993' => '🇲🇮 Turkmenistan (+993)',
        '+688' => '🇹🇺 Tuvalu (+688)',
        '+256' => '🇺🇴 Uganda (+256)',
        '+380' => '🇺🇬 Ukraine (+380)',
        '+971' => '🇦🇧 United Arab Emirates (+971)',
        '+44' => '🇬🇧 United Kingdom (+44)',
        '+1' => '🇺🇴 United States (+1)',
        '+598' => '🇲🇴 Uruguay (+598)',
        '+998' => '🇮🇼 Uzbekistan (+998)',
        '+678' => '🇳🇬 Vanuatu (+678)',
        '+58' => '🇻🇪 Venezuela (+58)',
        '+84' => '🇻🇳 Vietnam (+84)',
        '+681' => '🇺🇬 Wallis and Futuna (+681)',
        '+967' => '🇪🇨 Yemen (+967)',
        '+260' => '🇿🇲 Zambia (+260)',
        '+263' => '🇿🇨 Zimbabwe (+263)'
    );

	if($return_index !== false)
		return isset($data[$return_index]) ? $data[$return_index] : '';

	return $data;
}

//Leads Status
function lead_status($return_index = false)
{
    $data = array(
        '1' => 'New',
        '2' => 'Prospect',
        '3' => 'Potential',
        '4' => 'Closing',
        '5' => 'Closed (Won)',
        '6' => 'Closed (Lost)',
    );

    if ($return_index !== false) {
        return isset($data[$return_index]) ? $data[$return_index] : '';
    }

    return $data;
}

//Task Performed
function task_performed($return_index = false)
{
    $data = array(
        '1' => 'Call Attempted',
        '2' => 'Productive Call',
        '3' => 'Non-Productive Call',
        '4' => 'WhatsApp Chat',
        '5' => 'Meeting Arranged',
        '6' => 'Meeting Done (Office)',
        '7' => 'Meeting Done (Outdoor)',
        '8' => 'Meeting Done (Site Office)',
        '9' => 'Token Received',
        '10' => 'Payment Completed',
        '11' => 'Documents Prepared',
        '12' => 'Documents Dispatched',
    );

    if ($return_index !== false) {
        return isset($data[$return_index]) ? $data[$return_index] : '';
    }

    return $data;
}

//Next Task
function next_task($return_index = false)
{
    $data = array(
        '1' => 'Followup Client',
        '2' => 'Arrange Meeting',
        '3' => 'Meet Client',
        '4' => 'Receive Token',
        '5' => 'Receive Balance Amount',
        '6' => 'Prepare Documents',
        '7' => 'Do Nothing',
    );

    if ($return_index !== false) {
        return isset($data[$return_index]) ? $data[$return_index] : '';
    }

    return $data;
}

//Lead Source
function lead_source($return_index = false)
{
	$data = array(
		"1" => "Personal",
		"2" => "Client Referral",
		"3" => "Facebook",
		"4" => "Instagram",
		"5" => "LinkedIn",
		"6" => "Google",
		"7" => "Portal",
		"8" => "Walk In",
		"9" => "Expo",
		"10" => "Directory",
		"11" => "Inbound Call",
		"12" => "Helpline Enquiry",
		"13" => "SMS Campaign",
		"14" => "TVC",
	);

    if ($return_index !== false) {
        return isset($data[$return_index]) ? $data[$return_index] : '';
    }

    return $data;
}

//Payment Type
function payment_type($return_index = false)
{
	$data = array(
		"1" => "Conditional Token",
		"2" => "Confirmed Token",
		"3" => "Booking Amount",
		"4" => "Allocation Amount",
		"5" => "Confirmation Amount",
		"6" => "Other",
	);

    if ($return_index !== false) {
        return isset($data[$return_index]) ? $data[$return_index] : '';
    }

    return $data;
}

//Receipt Status
function receipt_status($return_index = false)
{
	$data = array(
		"1" => "Pending",
		"2" => "Approved",
		"3" => "Cancel",
	);

    if ($return_index !== false) {
        return isset($data[$return_index]) ? $data[$return_index] : '';
    }

    return $data;
}

//Installment
function booking_installment($data, $db_table, $total_installment, $total_payable_amount, $total_paid_amount, $data_amount) {
	$CI =& get_instance();
	
	$rowNumber = 1;
	foreach ($total_installment as $installment) {
		if ($total_payable_amount > 0) {
			if ($total_paid_amount >= $installment->amount) {
				$total_paid_amount -= $installment->amount;
			} else {
				$remaining = $installment->amount - $total_paid_amount;
				if ($remaining > 0) {
					if ($total_payable_amount >= $remaining) {
						$data_amount = $remaining;
						$CI->crud->add($data, $db_table);
						$total_payable_amount -= $remaining;
						$total_paid_amount = 0;
					} else {
						$data_amount = $total_payable_amount;
						$CI->crud->add($data, $db_table);
						$total_payable_amount = 0;
					}
				}
			}
		}
		$rowNumber++;
	}
	return true;
}


function content_loader($loader_class=""){
	// $loader_class = loader-big
	return '<img src="' . site_url("assets/images/loader.gif") . '" class="content_loader '.$loader_class.'" alt="Loading...">';
}

function is_super_user(){
	$CI =& get_instance();
	return $CI->session->userdata('is_superuser');
}

/*function is_admin(){
	$CI =& get_instance();
	return in_array(5, $CI->session->userdata('roles')??array());
}*/
?>