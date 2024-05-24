<?php
require_once "functions.loader.php";	
date_default_timezone_set("Asia/Manila");

include(__DIR__.'/vendor/Captcha/CaptchaBuilderInterface.php');
include(__DIR__.'/vendor/Captcha/PhraseBuilderInterface.php');
include(__DIR__.'/vendor/Captcha/CaptchaBuilder.php');
include(__DIR__.'/vendor/Captcha/PhraseBuilder.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';

use Gregwar\Captcha\CaptchaBuilder;

use Base32\Base32;

$app = new Slim();

$app->get("/test",function(){
    // $dateGranted = date("Y-m-d");
    // first_monthly_amortization_date($dateGranted);
    // echo $date;
    
    // $loanUid = "8991C8CD-3B43-6B9D-6FAE-6D6A7A1E15C5";
    // $amortization = 1000;
    // $terms = 6;
    // $amount = 1000;
    // $remarks ="N/A";
    // $email = "edjohnpaulgallardo28@gmail.com";
    // $loanAmount = 3000;
    // $empName = "Ed John Paul Gallardo";
    // mail_request_to_admin($email,$empName,$loanAmount);
    // $refNumber = generateRefNum();
    // $uid = xguid();
    // echo $refNumber."<br>";
    // echo $uid."<br>";
    // echo generateApplyNo();
    // create_cash_advance_loan_setup();
    // $data = read_all_cash_advance_loans();
    // echo jsonify($data);
    // $requid = "CF96947A-7503-EDC9-D98D-B3EDF5D1CEB8";
    // read_all_cash_advance_loan_request_active();
    // $req_uid = xguid();
    // $emp_uid = "25E50611-8B11-24E9-F10D-B2CB13F7CE66";
    // $loan_uid = "DAF39A62-3BB6-854D-B7D6-076B39556D79";
    // $empEmail = "edjohnpaulgallardo28@gmail.com";
    // $loanAmount = 5000;
    // $empName = "Jade Almoquera";
    // $loanPeriod = 2.5;
    // $amortization = "1000";
    // $interest = "0.00";
    // $payment = "15th/30th";
    // update_cash_advance_loan_request_archived($emp_uid);
    // create_cash_advance_loan_request($req_uid,$emp_uid,$loan_uid);
    // create_cash_advance_loan_request_entry($req_uid,$emp_uid,$loan_uid,$empEmail,$loanAmount,$loanPeriod,$amortization,$interest,$payment);
    // mail_request_to_admin($empEmail,$empName,$loanAmount);
    // create_emp_loans_payments($emp_uid);
    // read_emp_loans_new_uid_by_emp_uid($emp_uid);
    // read_cash_advance_loan_requests($emp_uid);
    $emp_uid = "25E50611-8B11-24E9-F10D-B2CB13F7CE66";
    $noOfPayment = 3;  
    create_emp_loans_payments($emp_uid,$noOfPayment);
    echo "Payment created";
});

$app->get("/get/old/timein", function() {
    $empuid = "13B08C53-1ABE-064B-4966-3FA9F2E3F60A";
    $sdate = "2022-09-12";
    $results = getOldTimeIn($empuid, $sdate); echo $results;
    //echo $results->date_created;
});

$app->get("/lotto/:var", function($var) {
    echo rand(1, $var);
});

$app->get("/condition", function() {
    //echo "Yeah!";
    $x = 3;
    $y = 12;

    if($x !== 3 || $y > 6) {
        echo "A " . true;
    }else{
        echo " false ";
    }
    if($x=="3" && $y <= 6){
        echo "B " . true;
    }else{
        echo " false ";
    }

    if($x<"3" || $y=="12") {
        echo "C " . true;
    }else {
        echo " false ";
    }
    if($x!==3||$y==6) {
        echo "D " . true;
    }else {
        echo " false";
    }
});

$app->get("/sample", function() {
    //$num = application_number('emp_cash_advances');    
    //echo "Application No.: " . $num;
    $table = 'emp_cash_advances';
    $sdate = date("Y-m-d"); echo $sdate . "</br>";
	$sql = "SELECT date_created FROM $table WHERE date_created LIKE '$sdate%' AND status='1'";
    $date = "$sdate%";
	$query = ORM::forTable($table)->where_like("date_created", $date)->where("status", 1)->count();
	$series = 0;	
	$num = $query + 1;
	$series = str_pad($num, '6', '0', STR_PAD_LEFT);
	echo date("Ymd") . $series;
    //echo var_dump($query);
});

$app->get("/ramdomizer", function() {
    $i = 0;
    
    echo sha1(base64_decode("686".salt())) . "</br>";
    
    while($i!=69) {
        $i++;    
        $random = rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
        $password = sha1(base64_decode($random.salt()));

        echo $random . " " . $password . "</br>";
    }
});

$app->get("/xguid", function() { 
    echo xguid(); 
    //echo "</br>";
    //echo get_employee_salary_to_date('6F5E1ECA-9F14-FB1B-91AC-3E759686ADC2');
    //echo date("Y");
    echo "</br></br>";
    $date = date("Y-m-d", strtotime("2021-06-24"));
    echo date("Y-m-d", strtotime($date . "+24 months"));
    echo "</br></br>";
    $amort = "2021/06";    
    $day = date("d");
    $sdate = date("Y-m-d", strtotime($amort . "/".$day));
    echo "</br></br>";
    echo $sdate;
    echo "</br></br>";
    echo strtotime($sdate);
});

$app->get("/tax", function() {
    $salary = "66667"; echo "Salary: " . $salary . "</br>";
    $daily_rate = number_format($salary / 26, 2, ".", ""); echo "Daily Rate: " . $daily_rate . "</br>";

    $payroll_period = "26";
    $days_in_a_year = $payroll_period * 12; echo "Days in a year: " . $days_in_a_year . "</br>";

    $annual_income = $daily_rate * $days_in_a_year; echo "Annual Income: " . $annual_income . "</br>";

    if($annual_income>250000 && $annual_income<=400000) {
        $excess = $annual_income - 250000; $excess = number_format($excess, 2, ",","");
        echo "excess: " . $excess . "</br>";
        $percent = $excess * 0.20; echo "percent: " . $percent . "</br>";
        $tax = $percent / 12; echo "" . $tax;
    }else if($annual_income>400000.01 && $annual_income<=800000) {
        $excess = $annual_income - 400000; $excess = number_format($excess, 2, ",","");
        echo "excess: " . $excess . "</br>";
        $percent = $excess * 0.25; 
        $percent = number_format($percent,2,".",""); echo "%: " . $percent . "</br>";
        $tax = (30000 + $percent) / 12; echo "Tax: " . $tax;
    }else if($annual_income>800000.01 && $annual_income<=2000000) {
        $excess = $annual_income - 800000; $excess = number_format($excess, 2, ",","");
        echo "excess: " . $excess . "</br>";
        $percent = $excess * 0.30; 
        $percent = number_format($percent,2,".",""); echo "%: " . $percent . "</br>";
        $tax = (130000 + $percent) / 12; echo "Tax: " . $tax;
    }else if($annual_income>2000000.01 && $annual_income<=8000000){
        $excess = $annual_income - 2000000; $excess = number_format($excess, 2, ",","");
        echo "excess: " . $excess . "</br>";
        $percent = $excess * 0.32; 
        $percent = number_format($percent,2,".",""); echo "%: " . $percent . "</br>";
        $tax = (490000 + $percent) / 12; echo "Tax: " . $tax;
    }else if($annual_income>8000000.01 && $annual_income<=10000000) {
        $excess = $annual_income - 8000000; $excess = number_format($excess, 2, ",","");
        echo "excess: " . $excess . "</br>";
        $percent = $excess * 0.35; 
        $percent = number_format($percent,2,".",""); echo "%: " . $percent . "</br>";
        $tax = (2410000 + $percent) / 12; echo "Tax: " . $tax;
    }
});

$app->get("/tax2", function() {
    //$taxable_amount = "98768.7";
    $taxable_amount = "10896.88";
    $pay_period_name = "semi-monthly";

    $pay_period_uid =  get_pay_period_name($pay_period_name)->pay_period_uid; 
    $pay_period_name =  get_pay_period_name($pay_period_name)->pay_period_name; 
    
    $tax = get_employee_payroll_tax($pay_period_uid, $taxable_amount);

    echo "UID: " . $pay_period_uid . "</br>";
    echo "Payout Type: " . $pay_period_name . "</br>";
    echo "Taxable Amount: " . $taxable_amount . "</br>";    
    echo "Tax: " . $tax;

});

$app->get("/tax3/:var", function($var) {
    $param = explode("x", $var);    

    if(count($param)===2) {
        $taxable_amount = $param[0]; //echo "Compensation: " . $taxable_amount . "</br>";
        $uid = $param[1];

        $pay_period_name = get_payroll_period($uid)->pay_period_name; //echo $pay_period_name . "</br>";

        echo get_employee_payroll_tax($uid, $taxable_amount);
    }
    
});

$app->get("/eminem", function() {
    $emp_uid = "0B5B0808-4CFB-323F-E289-F51A6CF66577";
    $response = array(
        "add" => "0.00",
        "less" => "0.00"
    );
    $emp_adjustment = read_payroll_adjustment_by_emp_uid($emp_uid);
    if($emp_adjustment) {
        $add = 0;
        $less = 0;
        foreach($emp_adjustment as $result) {
            if(($result->type)==="Add") {
                $add = $add + $result->amount;
            }else {
                $less = $less + $result->amount;
            }
        }
        $response = array(
            "add" => $add,
            "less" => $less
        );
    }
    echo jsonify($response);
});

$app->get("/test/:var", function($var) {
    $results = read_philhealth_by_year($var);
    if($results) {
        echo $results->percent;
    }else{
        echo "No record found!";
    }
    
});

$app->get("/", function () {
	//echo password_hash("123", PASSWORD_DEFAULT);

    $results = read_emp_department();
   // var_dump($results);

   $emp_adjustment = read_payroll_adjustment_by_emp_uid_payroll_date('0B5B0808-4CFB-323F-E289-F51A6CF66577', '2021-03-15');
    foreach($results as $result) {
        //echo $result->amount . "</br>";
    }

    //echo read_philhealth_by_year("2021")->percent;
    if($emp_adjustment) {
        $adj_add = 0;
        $adj_less = 0;
        foreach($emp_adjustment as $adjustment) {
            echo $adjustment->type . " </br>";
            //$adjust_type = $adjustment->type;
            if(($adjustment->type)==="Add") {
                $adj_add = $adj_add + $adjustment->amount;
            }else {
                $adj_less = $adj_less + $adjustment->amount;
            }
        }
        echo $adj_add . " " . $adj_less;
    }
    
});

# Employee New
$app->post("/employee/new/" , function(){
    //parameter: token
    $empUid       = xguid();
    $firstname    = utf8_encode($_POST['firstname']);
    $middlename   = utf8_encode($_POST['middlename']);
    $lastname     = utf8_encode($_POST['lastname']);
    $marital      = $_POST['marital'];
    $usertype     = $_POST['usertype'];
    $username     = $_POST['username'];
    $password     = $_POST['password'];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");
    
    $userId       = xguid();
    // $username  = $_POST['username'];
    $check        = checkUsername($username);
    if($check >= 1){
        $response = array(
            "error"        => 1,
            "errorMessage" => "USERNAME EXISTING!"
        );
    }else if($check == 0){
        //$password = sha1(Base32::decode($_POST['password']));
		$password = sha1(base64_decode($_POST['password'].salt()));
		//$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $ivSize   = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv       = mcrypt_create_iv($ivSize, MCRYPT_RAND);

        newEmployee($empUid , $firstname , $middlename , $lastname, $marital, $usertype, $dateCreated , $dateModified);
        newUserAccount($userId , $username , $password , $usertype , $empUid , $dateCreated , $dateModified);
        newUserUniqueKey(xguid(), $userId, $iv , $dateCreated , $dateModified);

        $response = array(
            "error"        => 0,
            "errorMessage" => "SUCCESSFULLY CREATED!"
        );
    }
    echo jsonify($response);
});

# Employee Status Update 
$app->post("/employee/status/update/" , function(){
    //parameter: token
    $empUid       = $_POST['empUid'];
    $status       = $_POST['status'];
    $dateModified = date("Y-m-d H:i:s");
    updateEmployeeStatus($empUid , $dateModified , $status);
});

# Employee Update 
$app->post("/employee/update/:var" , function($var){
    $param          = explode(".", $var);
    $token          = $param[1];
    
    $empUid         = $param[0];
    $firstname      = utf8_encode($_POST['firstname']);
    $middlename     = utf8_encode($_POST['middlename']);
    $lastname       = utf8_encode($_POST['lastname']);
    $gender         = $_POST['gender'];
    $marital        = $_POST['marital'];
    $nationality    = $_POST['nationality'];
    $bday           = $_POST['bday'];
    $email          = $_POST['email'];
    $nickname       = $_POST['nickname'];
    $driverLicense  = $_POST['driverLicense'];
    $expiryLicense  = $_POST['expiryLicense'];
    $sssNo          = $_POST['sssNo'];
    $taxNo          = $_POST['taxNo'];
    $philhealthNo   = $_POST['philhealthNo'];
    $pagibigNo      = $_POST['pagibigNo'];
    // $dateCreated = date("Y-m-d H:i:s");
    $dateModified   = date("Y-m-d H:i:s");
    $status         = $_POST['status'];
	$taxStatus         = $_POST['taxStatus'];

    updateEmployee($empUid , $firstname , $middlename , $lastname , $gender , $marital , $nationality , $bday , $email , $nickname , $driverLicense , $expiryLicense , $sssNo , $taxNo , $philhealthNo , $pagibigNo , $dateModified , $status, $taxStatus);   
});

$app->post("/hris/employee/update/:var" , function($var){
    $param          = explode(".", $var);
    $token          = $param[1];
    
    $empUid         = $param[0];
    $firstname      = utf8_encode($_POST['firstname']);
    $middlename     = utf8_encode($_POST['middlename']);
    $lastname       = utf8_encode($_POST['lastname']);
    $gender         = $_POST['gender'];
    $marital        = $_POST['marital'];
    $nationality    = $_POST['nationality'];
    $bday           = $_POST['bday'];
    $email          = $_POST['email'];
    $nickname       = $_POST['nickname'];
    $driverLicense  = $_POST['driverLicense'];
    $expiryLicense  = $_POST['expiryLicense'];
    $sssNo          = $_POST['sssNo'];
    $taxNo          = $_POST['taxNo'];
    $philhealthNo   = $_POST['philhealthNo'];
    $pagibigNo      = $_POST['pagibigNo'];
    // $dateCreated = date("Y-m-d H:i:s");
    $dateModified   = date("Y-m-d H:i:s");
    $status         = $_POST['status'];
	$taxStatus         = $_POST['taxStatus'];

    $housenumber = $_POST['housenumber'];
    $barangay = $_POST['barangay'];
    $city = $_POST['city'];
    $region = $_POST['region'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $bloodtype = $_POST['bloodtype'];

    updateEmployeeHRIS($empUid , $firstname , $middlename , $lastname , $gender , $marital , $nationality , $bday , $email , $nickname , $driverLicense , $expiryLicense , $sssNo , $taxNo , $philhealthNo , $pagibigNo , $dateModified , $status, $taxStatus, $housenumber, $barangay, $city, $region, $height, $weight, $bloodtype);   
});

# Employee Name Search
$app->post("/employee/name/search/" , function(){
    //parameter: token
    $empUid     = xguid();
    $firstname  = utf8_decode($_POST['firstname']);
    $middlename = utf8_decode($_POST['middlename']);
    $lastname   = utf8_decode($_POST['lastname']);
	$status = 0;
	
    $employee = getEmployeeByNameCount($firstname , $middlename , $lastname);
    if($employee) {
        $emp = getEmployeeByName($firstname , $middlename , $lastname);
		$active = $emp->status;
		$empUid = $emp->emp_uid;
		if($active == 1) {
            $status = 1;
        }else {
            $status = 0;
        }
    }else {
        $status = 2;
    }

    $response = array(
        "status" => $status,
        "empUid" => $empUid
    );

    echo jsonify($response);
});

# Attempts Data
$app->get("/attempts/data/", function(){
    $response = array();

    $attempts = getAttemptsData();
    foreach($attempts as $data){
        $response[] = array(
            "empNo"  => $data["username"],
            "emp"    => $data["lastname"] . ", " . $data["firstname"],
            "date"   => date("M d, Y", strtotime($data["sdate"])),
            "hours"  => date("h:i:s A", strtotime($data["stime"])),
            "log"    => $data["log"],
            "code"   => $data["location_code"],
            "device" => $data["device"],
            "ip"     => $data["ip_address"]
        );
    }
    echo jsonify($response);
});

# Employee Pages Get
$app->get("/employees/pages/get/:var" , function($var){
    $param     = explode(".", $var);
    $response  = array();
    $employees = getPaginatedEmployees();

    foreach ($employees as $employee) {
        $uid   = $employee->emp_uid;
        $rules = getRuleByEmpUid($uid);
        $costcenter = getSingleCostCenterDataByEmpUid($uid);
        $response[] = array(
            "empUid"     => $employee->emp_uid,
            "empNo"      => $employee->username,
            "firstname"  => utf8_decode($employee->firstname),
            "middlename" => utf8_decode($employee->middlename),
            "lastname"   => utf8_decode($employee->lastname),
            "nickname"   => $employee->nickname?$employee->nickname:"N/A",
            "gender"     => $employee->gender?$employee->gender:"N/A",
            "rule"       => $rules["rule_name"]?$rules["rule_name"]:"N/A",
            "costcenter"       => $costcenter["cost_center_name"]?$costcenter["cost_center_name"]:"N/A",
            "type"       => $employee->type?$employee->type:"N/A"            
        );
    }
    echo jsonify($response);
});

# Employee Data Get
$app->get("/employee/data/get/:var" , function($var){
    //parameter: term.start.size.token
    $param    = explode(".", $var);
    $token    = $param[1];
    $empUid   = $param[0];
    
    $employee = getEmployeeDetailsByUid($empUid);
    $username = getEmloyeeNumberByEmpUid($empUid);
    $type     = getEmployeeType($empUid);

    if($employee->nationality){
        $nationality = $employee->nationality;
    }else{
        $nationality = null;
    }
	
	$taxType = "";
	$taxStatus = read_tax_type_by_uid($employee->tax_status);
	if($taxStatus) {
		$taxType = isset($taxStatus->type) ? $taxStatus->type:"";
	}
	
    $response = array(
        "empUid"        => $employee->emp_uid,
        "firstname"     => utf8_decode($employee->firstname),
        "middlename"    => utf8_decode($employee->middlename),
        "lastname"      => utf8_decode($employee->lastname),
        "gender"        => $employee->gender,
        "marital"       => $employee->marital,
        //"nationality"   => $employee->nationality,
        "bday"          => $employee->bday,
        "nationality"   => $nationality,
        "bday"          => $employee->bday,
        "email"         => $employee->email,
        "nickname"      => $employee->nickname,
        "driverLicense" => $employee->drivers_license,
        "expiryLicense" => $employee->expiry_license,
        "sssNo"         => $employee->sss_no,
        "taxNo"         => $employee->tax_no,
        "philhealthNo"  => $employee->philhealth_no,
        "pagibigNo"     => $employee->pagibig_no,
        "empNumber"     => $username,
        "status"        => $employee->status,
        "type"          => $employee->type,
		"taxStatus"     => $taxType,
		"taxStatusUid"  => $employee->tax_status
    );
    echo jsonify($response);
});

$app->get("/hris/employee/data/get/:var" , function($var){
    //parameter: term.start.size.token
    $param    = explode(".", $var);
    $token    = $param[1];
    $empUid   = $param[0];
    
    $employee = getEmployeeDetailsByUid($empUid);
    $username = getEmloyeeNumberByEmpUid($empUid);
    $type     = getEmployeeType($empUid);

    if($employee->nationality){
        $nationality = $employee->nationality;
    }else{
        $nationality = null;
    }
	
	$taxType = "";
	$taxStatus = read_tax_type_by_uid($employee->tax_status);
	if($taxStatus) {
		$taxType = isset($taxStatus->type) ? $taxStatus->type:"";
	}
	
    $response = array(
        "empUid"        => $employee->emp_uid,
        "firstname"     => utf8_decode($employee->firstname),
        "middlename"    => utf8_decode($employee->middlename),
        "lastname"      => utf8_decode($employee->lastname),
        "gender"        => $employee->gender,
        "marital"       => $employee->marital,
        //"nationality"   => $employee->nationality,
        "bday"          => $employee->bday,
        "nationality"   => $nationality,
        "bday"          => $employee->bday,
        "email"         => $employee->email,
        "nickname"      => $employee->nickname,
        "driverLicense" => $employee->drivers_license,
        "expiryLicense" => $employee->expiry_license,
        "sssNo"         => $employee->sss_no,
        "taxNo"         => $employee->tax_no,
        "philhealthNo"  => $employee->philhealth_no,
        "pagibigNo"     => $employee->pagibig_no,
        "empNumber"     => $username,
        "status"        => $employee->status,
        "type"          => $employee->type,
		"taxStatus"     => $taxType,
		"taxStatusUid"  => $employee->tax_status,
        "housenumber"   => $employee->house_number,
        "barangay"      => $employee->barangay,
        "city"          => $employee->city,
        "region"        => $employee->region,
        "height"        => $employee->height,
        "weight"        => $employee->weight,
        "bloodtype"     => $employee->blood_type
    );
    echo jsonify($response);
});

# Update Emp Number
$app->post("/update/emp/number/:uid", function($uid){
    $response     = array();
    $username     = $_POST["empNumber"];
    $check        = checkUsername($username);
    $dateModified = date("Y-m-d H:i:s");

    if($check >= 1){
        $response = array(
            "prompt" => 1,
            "num"    => $username
        );
    }else{
        updateEmpNumber($uid, $username, $dateModified);
        $response = array(
            "prompt" => 0,
            "num"    => $username
        );
    }

    echo jsonify($response);
});

# Update Emp Type
$app->post("/update/emp/type/:uid", function($uid){
    $response     = array();
    $empType      = $_POST["empType"];
    $dateModified = date("Y-m-d H:i:s");

    updateEmployeeType($uid, $empType, $dateModified);
});

# Employee Nationality Get
$app->get("/employee/nationality/get/" , function(){
    //parameter: token
    $response      = array();
    $nationalities = getNationalities();
    foreach ($nationalities as $nationality) {
        $response[] = array(
            "nationalityUid"  => $nationality->nationality_uid,
            "nationalityName" => $nationality->name
        );
    }

    echo jsonify($response);
});

# Employee Nationality New
$app->post("/employee/nationality/new/:var" , function($var){
    //parameter: token
    $nationalityUid  = xguid();
    $nationalityName = $_POST['nationality'];
    $dateCreated     = date("Y-m-d H:i:s");
    $dateModified    = date("Y-m-d H:i:s");
    $status          = 0;

    $newNationality = newNationality($nationalityUid , $nationalityName , $dateCreated , $dateModified);
    if($newNationality){
        $status         = 1;
        $nationality    = getNationalityByName($nationalityName);
        $nationalityUid = $nationality->nationality_uid;
    }

    $response = array(
        "nationalityUid"  => $nationalityUid,
        "nationalityName" => $nationalityName,
        "status"          => $status
    );

    echo jsonify($response);
});

# Employee Dependent Pages Get
$app->get("/employee/dependent/pages/get/:var" , function($var){
    //parameter: term.start.size.token
    $param              = explode(".", $var);
    $token              = $param[1];
    $empUid             = $param[0];
    $response           = array();
    
    $employeeDependents = getPaginatedEmployeeDependent($empUid);

    foreach ($employeeDependents as $employeeDependent) {
        $response[] = array(
            "name"                 => $employeeDependent->name,
            "relationship"         => $employeeDependent->relationship,
            "bday"                 => date("M. d, Y", strtotime($employeeDependent->bday)),
            "number"               => $employeeDependent->number,
            "employeeDependentUid" => $employeeDependent->emp_dependent_uid,
        );
    }
    echo jsonify($response);
});

# Employee Dependent New
$app->post("/employee/dependent/new/:var" , function($var){
    //parameter: term.start.size.token
    $param = explode(".", $var);
    $emp_uid = $param[0];
    $token = $param[1];
    $response = array();
    $verified = 0;
    if(count($param)===2) {
        $empUid  = $param[0];
        $empDependentUid = xguid();
        $name = $_POST['dependentName'];
        $relationship = $_POST['dependentRelationship'];
        $bday = $_POST['dependentBday'];
        $number = $_POST['dependentNumber'];

        //$newEmployeeDependent = newEmployeeDependent($empDependentUid,$empUid,$newDependentName,$newDependentRelationship,$newDependentNumber,$newDependentBday,$dateCreated,$dateModified);
        create_employee_dependent($empUid, $name, $relationship, $number, $bday);
        $verified = 1;

    }    
    $response = array(
        "success" => $verified
    );
    echo jsonify($response);
});

# Employee Dependent View
$app->get("/employee/dependent/view/:var" , function($var){
    //parameter: term.start.size.token
    $param     = explode(".", $var);
    
    $empUid    = $param[0];
    $depUid    = $param[1];
    $token     = $param[2];
    $response  = array();
    $dependent = getEmployeeDependentByUid($depUid);
    if($dependent){
        $response = array(
            'uid'          => $dependent->emp_dependent_uid,
            'name'         => $dependent->name, 
            'relationship' => $dependent->relationship, 
            'bday'         => $dependent->bday, 
            'number'       => $dependent->number,
            "status"       => $dependent->status
        );
    }
    echo jsonify($response);
});

# Employee Dependent Update
$app->post("/employee/dependent/update/:var" , function($var){
    $param = explode(".", $var);
    $type = $param[2];
    $depUid = $param[1];
    $empUid = $param[0];
    $response = array();
    $verified = 0;
    if(count($param)===3) {
        $dependentName = $_POST['dependentName'];
        $dependentRelationship = $_POST['dependentRelationship'];
        $dependentBday = $_POST['dependentBday'];
        $dependentNumber = $_POST['dependentNumber'];
        if($type=="edit") {

        }else if($type=="delete") {

        }
    }
    $response = array(
        "success" => $verified
    );

    echo jsonify($response);
});

# Employee Phone Pages Get
$app->get("/employee/phone/pages/get/:var" , function($var){
    //parameter: term.start.size.token
    $param          = explode(".", $var);
    $empUid         = $param[0];
    $response       = array();
    
    $employeePhones = getPaginatedEmployeePhone($empUid);
    if($employeePhones){
        foreach ($employeePhones as $employeePhone) {
            $response[] = array(
                "phoneType" => getPhoneTypeByUid($employeePhone->phonetype_uid),
                "number"    => $employeePhone->number,
                "phoneUid"  => $employeePhone->phone_uid
            );
        }
    }
    echo jsonify($response);
});

# Employee Phone New 
$app->post("/employee/phone/new/:var" , function($var){
    //parameter: term.start.size.token
    $param             = explode(".", $var);
    
    $empUid            = $param[0];
    $phoneUid          = xguid();
    $employeePhoneType = $_POST['employeePhoneType'];
    $employeePhone     = $_POST['employeePhone'];
    $dateCreated       = date("Y-m-d H:i:s");
    $dateModified      = date("Y-m-d H:i:s");
    
    $newEmployeePhone  = newEmployeePhone($phoneUid,$empUid,$employeePhoneType,$employeePhone,$dateCreated,$dateModified);
});

# Employee Phone Details Get
$app->get("/employee/phone/details/get/:var" , function($var){
    //parameter: term.start.size.token
    $param         = explode(".", $var);
    $empUid        = $param[0];
    $phoneUid      = $param[1];
    $response      = array();
    
    $employeePhone = getEmployeeContactDetails($phoneUid);
    if($employeePhone){
        $response = array(
            "phoneType"    => getPhoneTypeByUid($employeePhone->phonetype_uid),
            "phoneTypeUid" => $employeePhone->phonetype_uid,
            "number"       => $employeePhone->number,
            "status"       => $employeePhone->status
        );
    }
    echo jsonify($response);
});

# Employee Phone Status Update
$app->post("/employee/phone/status/update/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[2];
    
    $phoneUid     = $param[1];
    $empUid       = $param[0];
    $phoneType    = $_POST['employeePhoneType'];
    $phoneNumber  = $_POST['employeePhone'];
    $status       = $_POST['status'];
    $dateModified = date("Y-m-d H:i:s");
    $phone        = getEmployeeContactDetails($phoneUid);
    if($phone->phonetype_uid != $phoneType OR $phone->number != $phone OR $phone->status != $status){
        updateEmployeePhoneById($phoneUid , $dateModified , $status , $phoneType , $phoneNumber);
        if(employeePhoneCount($empUid,$phoneNumber) > 1){
            updateEmployeePhoneById($phoneUid , $dateModified , $status, $phone->phonetype_uid , $phone->number);
        }
    }
});

# Employee Job New
$app->post("/employee/job/new/:var" , function($var){
    //parameter: empUid.token
    $param            = explode(".", $var);
    
    $empUid           = $param[0];
    $empJobUid        = xguid();
    $jobTitle         = $_POST['jobTitle'];
    $jobCategory      = $_POST['jobCategory'];
    $subunit          = $_POST['subunit'];
    $location         = $_POST['location'];
    $employmentStatus = $_POST['employmentStatus'];
    $startDate        = $_POST['startDate'];
    $endDate          = $_POST['endDate'];
    $dateCreated      = date("Y-m-d H:i:s");
    $dateModified     = date("Y-m-d H:i:s");
    
    $newEmployeeJob   = newEmployeeJob($empJobUid , $jobTitle , $jobCategory , $subunit , $location , $employmentStatus , $empUid , $startDate , $endDate , $dateCreated , $dateModified);
});

# Employee Job Pages Get
$app->get("/employee/job/pages/get/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $empUid       = $param[0];
    $response     = array();
    
    $employeeJobs = getPaginatedEmployeeJob($empUid);
    
    foreach ($employeeJobs as $employeeJob) {
        $job      = getJobByUid($employeeJob->job_uid);
        $jobTitle = "";
        if($job){
            if($job->status == 1){
                $jobTitle =  $job->title;
            }
        }

        $jobCategory     = getJobCategoryByUid($employeeJob->job_category_uid);
        $jobCategoryName = "";
        if($jobCategory){
            if($jobCategory->status == 1){
                $jobCategoryName =  $jobCategory->name;
            }
        }

        $subunit     = getSubunitByUid($employeeJob->subunit_uid);
        $subunitName = "";
        if($subunit){
            if($subunit->status == 1){
                $subunitName =  $subunit->name;
            }
        }

        $location     = getLocationByUid($employeeJob->location_uid);
        $locationName = "";
        if($location){
            if($location->status == 1){
                $locationName =  $location->name;
            }
        }

        $employmentStatus     = getEmploymentStatusByUid($employeeJob->employment_status_uid);
        $employmentStatusName = "";
        if($employmentStatus){
            if($employmentStatus->status == 1){
                $employmentStatusName =  $employmentStatus->name;
            }
        }

        $response[] = array(
            "jobTitle"         => $jobTitle,
            "jobCategory"      => $jobCategoryName,
            "subunit"          => $subunitName,
            "location"         => $locationName,
            "employmentStatus" => $employmentStatusName,
            "employeeJobUid"   => $employeeJob->employee_job_uid
        );
    }
    echo jsonify($response);
});

# Employee Job Details Get
$app->get("/employee/job/details/get/:var" , function($var){
    //parameter: term.start.size.token
    $param          = explode(".", $var);
    $token          = $param[2];
    $response       = array();
    $employeeJobUid = $param[1];
    $empUid         = $param[0];
    
    $employeeJob    = getEmployeeJobByUid($employeeJobUid);
    if($employeeJob){
        $job            = getJobByUid($employeeJob->job_uid);
        $jobTitle       = "";
        $jobDescription = "";
        if($job){
            if($job->status == 1){
                $jobTitle       =  $job->title;
                $jobDescription = $job->description;
            }
        }

        $jobCategory     = getJobCategoryByUid($employeeJob->job_category_uid);
        $jobCategoryName = "";
        if($jobCategory){
            if($jobCategory->status == 1){
                $jobCategoryName =  $jobCategory->name;
            }
        }

        $subunit     = getSubunitByUid($employeeJob->subunit_uid);
        $subunitName = "";
        if($subunit){
            if($subunit->status == 1){
                $subunitName =  $subunit->name;
            }
        }

        $location     = getLocationByUid($employeeJob->location_uid);
        $locationName = "";
        if($location){
            if($location->status == 1){
                $locationName =  $location->name;
            }
        }

        $employmentStatus     = getEmploymentStatusByUid($employeeJob->employment_status_uid);
        $employmentStatusName = "";
        if($employmentStatus){
            if($employmentStatus->status == 1){
                $employmentStatusName =  $employmentStatus->name;
            }
        }

        $response = array(
            "jobUid"              => $employeeJob->job_uid,
            "jobCategoryUid"      => $employeeJob->job_category_uid,
            "subunitUid"          => $employeeJob->subunit_uid,
            "locationUid"         => $employeeJob->location_uid,
            "employmentStatusUid" => $employeeJob->employment_status_uid,
            "jobTitle"            => $jobTitle,
            "jobDescription"      => $jobDescription,
            "jobCategory"         => $jobCategoryName,
            "subunit"             => $subunitName,
            "location"            => $locationName,
            "employmentStatus"    => $employmentStatusName,
            "startDate"           => $employeeJob->start_date,
            "endDate"             => $employeeJob->end_date,
            "dateExtended"        => $employeeJob->date_extended,
            "status"              => $employeeJob->status
        );
    }
    echo jsonify($response);
});

# Employee Job Update
$app->post("/employee/job/update/:var" , function($var){
    //parameter: term.start.size.token
    $param            = explode(".", $var);
    $token            = $param[2];
    
    $empUid           = $param[0];
    $empJobUid        = $param[1];
    $jobTitle         = $_POST['jobTitle'];
    $jobCategory      = $_POST['jobCategory'];
    $subunit          = $_POST['subunit'];
    $location         = $_POST['location'];
    $employmentStatus = $_POST['employmentStatus'];
    $startDate        = $_POST['startDate'];
    $endDate          = $_POST['endDate'];
    $dateExtended     = $_POST['dateExtend'];
    $dateModified     = date("Y-m-d H:i:s");
    $status           = $_POST['status'];
    $employeeJob      = getEmployeeJobByUid($empJobUid);

    if($jobTitle != $employeeJob->job_uid OR $jobCategory != $employeeJob->job_category_uid OR $subunit != $employeeJob->subunit_uid OR $location != $employeeJob->location_uid OR $employmentStatus != $employeeJob->employment_status_uid OR $startDate != $employeeJob->start_date OR $endDate != $employeeJob->end_date OR $dateExtended != $employeeJob->date_extended OR $status != $employeeJob->status){
        updateEmployeeJobById($empJobUid , $jobTitle , $jobCategory , $subunit , $location , $employmentStatus , $startDate , $endDate , $dateExtended , $dateModified , $status);
    }
});

# Employee Job Status Update
$app->post("/employee/job/status/update/:var" , function($var){
    //parameter: term.start.size.token
    $param          = explode(".", $var);
    $token          = $param[1];
    
    $employeeJobUid = $param[0];
    $dateModified   = date("Y-m-d H:i:s");
    $status         = $_POST['status'];

    updateEmployeeJobStatusByUid($employeeJobUid,$dateModified,$status);
});


/*---------------------------------employee end--------------------------------------*/


/*---------------------------------Phone Type--------------------------------------*/

# Phone Type Get
$app->get("/phone/type/get/:var" , function($var){
    //parameter: token
    $phoneTypes = getPhoneTypes();
    $response   = array();
    if($phoneTypes){
        foreach ($phoneTypes as $phoneType) {
            $response[] = array(
                "phoneTypeUid" => $phoneType->phonetype_uid,
                "phoneType"    => $phoneType->phone_type,
            );
        }
    }
    echo jsonify($response);
});

# Phone Type New
$app->post("/phone/type/new/" , function(){
    //parameter: token
    $phoneTypeUid = xguid();
    $phoneType    = $_POST['newPhoneType'];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");
    $status       = 0;
    
    $newPhoneType = newPhoneType($phoneTypeUid,$phoneType,$dateCreated,$dateModified);
    if($newPhoneType){
        $status       = 1;
        $pt           = getPhoneTypeByType($phoneType);
        $phoneTypeUid = $pt->phonetype_uid;
    }

    $response = array(
        "phoneTypeUid" => $phoneTypeUid,
        "phoneType"    => $phoneType,
        "status"       => $status
    );

    echo jsonify($response);
});

/*---------------------------------Phone Type ENd--------------------------------------*/

/*---------------------------------Nationalities--------------------------------------*/

# Nationalities Pages Get
$app->get("/nationalities/pages/get/:var" , function($var){
    //parameter: term.start.size.token
    $param         = explode(".", $var);
    $response      = array();
    
    $nationalities = getPaginatedNationalities();

    foreach ($nationalities as $nationality) {
        $response[] = array(
            "name"           => $nationality->name,
            "nationalityUid" => $nationality->nationality_uid
        );
    }
    echo jsonify($response);
});

# Nationality Details Get
$app->get("/nationality/details/get/:var" , function($var){
    //parameter: term.start.size.token
    $param       = explode(".", $var);
    $token       = $param[1];
    
    $uid         = $param[0];
    $response    = array();
    $nationality = getNationalityByUid($uid);
    if($nationality){
        $response = array(
            "nationality" => $nationality->name,
            "status"      => $nationality->status
        );
    }

    echo jsonify($response);
});

# Nationality Update
$app->post("/nationality/update/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[1];
    
    $uid          = $param[0];
    
    $name         = $_POST['nationality'];
    $status       = $_POST['status'];
    $dateModified = date("Y-m-d H:i:s");
    $nationality  = getNationalityByUid($uid);

    if($nationality->name != $name OR $nationality->status != $status){
        updateNationality($uid , $name , $dateModified , $status);
        if(nationalityCount($name) > 1){
            updateNationality($uid , $nationality->name , $dateModified , $nationality->status);
        }
    }
});
/*--------------------------------- PAYROLL --------------------------------------*/

# Reports Print
$app->post("/reports/print/:var", function($var){
    // parameters: html.token
    $param   = explode(".", $var);
    $token   = $param[0];
    $title   = $_POST["title"];
    // $html = Base32::decode($_POST["html"]);
    $html    = $_POST["html"];

    // echo $html;
    echo $title;
    $dompdf = new DOMPDF();
    $dompdf->set_paper("a4", "landscape");
    $dompdf->load_html($html);
    $dompdf->render();
    $dompdf->stream($title . ".pdf", array('Attachment' => 1));
});

# Reports Payroll Monthly All
$app->get("/reports/payroll/monthly/all/:var" , function($var){
    //parameter: token
    $param     = explode(".", $var);
    $response  = array();
    $employees = getActiveEmployees();
    foreach ($employees as $employee) {
        $response[] = array(
            "name"   => utf8_decode($employee->firstname) . " " . utf8_decode($employee.lastname),
            "salary" => $employee->base_salary
        );
    }
});

# Reports Payslip
$app->get("/reports/payslip/:var" , function($var){
    //parameter: token
    $param     = explode(".", $var);
    $employee  = $_POST["employeeId"];
    $response  = array();
    $employees = getActiveEmployees();
    foreach ($employees as $employee) {
        $response[] = array(
            "name"   => $employee->firstname . " " . $employee.lastname,
            "salary" => $employee->base_salary
        );
    }
});

# Generate Timesheet
$app->get("/generate/timesheet/:var",function($var){
    $param = explode(".", $var);
    $sheet = getEmpTimesheet($param[0],$param[1],$param[2]);
    echo jsonify($sheet);

});

# Reports Payroll Export Employees
$app->get("/reports/payroll/export/employees/:var", function($var){
    $param      = explode(".", $var);
    $startDate  = $param[0];
    $endDate    = $param[1];
    $costss     = $param[2];
    
    $costcenter = getEmployeeByCostCenterUid($costss);
    foreach($costcenter as $cost){
        $emp = $cost["emp_uid"];
        $x   = getTaxByCostCenter($startDate, $endDate, $emp, $costss);

        if($x){
            $response[] = array(
                "emp"             => $x["id"],
                "name"            => $x["name"],
                "empNo"           => $x["empNo"],
                "daySalary"       => $x["daySalary"],
                "hourlySalary"    => $x["hourlySalary"],
                "minutesSalary"   => $x["minutesSalary"],
                "overtimeSalary"  => number_format($x["overtimeSalary"], 2),
                "allowance"       => $x["allowance"],
                "basicSalary"     => $x["basicSalary"],
                "adjustment"      => $x["adjustment"],
                "days"            => $x["days"],
                "cutoffSalary"    => $x["cutoffSalary"],
                "grossSalary"     => $x["grossSalary"],
                "tardySalary"     => $x["tardySalary"],
                "totalSss"        => $x["totalSss"],
                "sssEmployee"     => $x["sssEmployee"],
                "sssEmployer"     => $x["sssEmployer"],
                "totalPhilhealth" => $x["totalPhilhealth"],
                "philEmployee"    => $x["philEmployee"],
                "philEmployer"    => $x["philEmployer"],
                "pagibig"         => $x["pagibig"],
                "totalContri"     => $x["totalContri"],
                "holidayPay"      => $x["holidayPay"],
                "netPay"          => $x["netPay"],
                "tax"             => $x["tax"],
                "loans"           => 0,
                "pettyCash"       => 0
            );
        }//end of checking if employee has income details
        // echo jsonify($x);

    }//end of getting employess by costcenter

    echo jsonify($response);
});

# Reports Payroll Get
$app->post("/reports/payroll/get/", function(){
    getEmployeeSalary();
    
});

/*---------------------------------PAYROLL END--------------------------------------*/
/*---------------------------------job--------------------------------------*/

# Job New
$app->post("/job/new/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[0];
    
    $jobUid       = xguid();
    $title        = $_POST['title'];
    $description  = $_POST['description'];
    $note         = $_POST['note'];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");
    
    $newJob       = newJob($jobUid , $title , $description , $note , $dateCreated , $dateModified);
    $response = array(
        "status" => $newJob
    );
    echo jsonify($response);
});

# Job Pages Get
$app->get("/job/pages/get/:var" , function($var){
    //parameter: term.start.size.token
    $param    = explode(".", $var);
    $response = array();
    
    $jobs     = getPaginatedJobs();

    foreach ($jobs as $job) {
        $response[] = array(
            "title"       => $job->title,
            "description" => $job->description,
            "jobUid"      => $job->job_uid
        );
    }
    echo jsonify($response);
});

# Job Get
$app->get("/jobs/get/:var" , function($var){
    //parameter: term.start.size.token
    $param    = explode(".", $var);
    $response = array();
    
    $jobs     = getJobs();
    foreach ($jobs as $job) {
        $response[] = array(
            "title"       => $job->title,
            "description" => $job->description,
            "jobUid"      => $job->job_uid
        );
    }
    echo jsonify($response);
});

# Job Details Get
$app->get("/job/details/get/:var" , function($var){
    //parameter: term.start.size.token
    $param  = explode(".", $var);
    $token  = $param[1];
    $jobUid = $param[0];
    
    $job    = getJobByUid($jobUid);
    if($job){
        $response = array(
            "title"       => $job->title,
            "description" => $job->description,
            "note"        => $job->note,
            "status"      => $job->status
        );
    }else{
        $response = array(
            "title"       => "",
            "description" => "",
            "note"        => "",
            "status"      => ""
        );
    }

    echo jsonify($response);
});

# Job Update
$app->post("/job/update/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[1];
    
    $jobUid       = $param[0];
    $title        = $_POST['title'];
    $description  = $_POST['description'];
    $note         = $_POST['note'];
    $dateModified = date("Y-m-d H:i:s");
    $status       = $_POST['status'];
    
    $job          = getJobByUid($jobUid);

    if($title != $job->title OR $description != $job->description OR $note != $job->note OR $status != $job->status){
        updateJobById($jobUid , $title , $description , $note , $dateModified , $status);
        if(jobCount($title)>1){
            updateJobById($jobUid , $job->title , $job->description , $job->note , $dateModified , $status);
        }
    }
});
/*---------------------------------job end--------------------------------------*/


/*---------------------------------employment Status--------------------------------------*/
# Employment Status Get
$app->get("/employment/status/get/:var" , function($var){
    //parameter: term.start.size.token
    $param    = explode(".", $var);
    $response = array();

    $employmentStatus = getEmploymentStatus();
    foreach ($employmentStatus as $es) {
        $response[] = array(
            "name"                => $es->name,
            "employmentStatusUid" => $es->employment_status_uid
        );
    }
    echo jsonify($response);
});

# Employment Status Pages Get
$app->get("/employment/status/pages/get/:var" , function($var){
    //parameter: term.start.size.token
    $param    = explode(".", $var);
    $response = array();

    $employmentStatus = getPaginatedEmploymentStatus();
    
    foreach ($employmentStatus as $es) {
        $response[] = array(
            "employmentStatusUid" => $es->employment_status_uid,
            "name"                => $es->name
        );
    }
    echo jsonify($response);
});

# Employment Status New
$app->post("/employment/status/new/:var" , function($var){
    //parameter: term.start.size.token
    $param               = explode(".", $var);
    $token               = $param[0];
    
    $employmentStatusUid = xguid();
    $name                = $_POST['name'];
    $dateCreated         = date("Y-m-d H:i:s");
    $dateModified        = date("Y-m-d H:i:s");
    
    $newEmploymentStatus = newEmploymentStatus($employmentStatusUid , $name , $dateCreated , $dateModified);
    $response = array(
        "status" => $newEmploymentStatus
    );
    echo jsonify($response);
});

# Employment Status Update
$app->post("/employment/status/update/:var" , function($var){
    //parameter: term.start.size.token
    $param               = explode(".", $var);
    $token               = $param[1];
    
    $employmentStatusUid = $param[0];
    $name                = $_POST['name'];
    $dateModified        = date("Y-m-d H:i:s");
    $status              = $_POST['status'];
    
    $employmentStatus    = getEmploymentStatusByUid($employmentStatusUid);

    if($name != $employmentStatus->name OR $status != $employmentStatus->status){
        updateEmploymentStatusById($employmentStatusUid , $name , $dateModified , $status);
        if(employmentStatusCount($name)>1){
            updateEmploymentStatusById($employmentStatusUid , $employmentStatus->name , $dateModified , $status);
        }
    }
});

# Employment Status Details Get
$app->get("/employment/status/details/get/:var" , function($var){
    //parameter: term.start.size.token
    $param               = explode(".", $var);
    $token               = $param[1];
    
    $employmentStatusUid = $param[0];
    
    $employmentStatus    = getEmploymentStatusByUid($employmentStatusUid);
    if($employmentStatus){
        $response = array(
            "name"   => $employmentStatus->name,
            "status" => $employmentStatus->status
        );
    }else{
        $response = array(
            "name"   => "",
            "status" => ""
        );
    }

    echo jsonify($response);
});

# Get Emp Employment Status
$app->get("/get/emp/employment/status/:uid", function($uid){
    $check = checkEmploymentStatusByEmpUidPages($uid);
    $datas    = getEmploymentStatusByEmpUidPages($uid);
    $response = array();
    if($check >= 1){
        foreach($datas as $data){
            $response[] = array(
                "type"         => $data["name"],
                "empStatusUid" => $data["type_uid"],
                "statusUid"    => $data["employment_status_uid"],
                "dateHired"    => date("M d, Y", strtotime($data["date_hired"])),
                "dateStarted"  => date("M d, Y", strtotime($data["date_started"])), //$data["date_started"],
                "dateResigned" => $data["date_resigned"]
            );
        }
    }	
	echo jsonify($response);
});

# Get Single Emp Employment Status 
$app->get("/get/single/emp/employment/status/:uid", function($uid){
    $response = array();
    $data     = getEmploymentStatusByStatusUid($uid);
    
    if($data){
        $employmentData = getEmploymentStatusByUid($data["employment_status_uid"]);
        $response = array(
            "type"         => $data["name"],
            "empStatusUid" => $data["type_uid"],
            "statusUid"    => $data["employment_status_uid"],
            "dateHired"    => $data["date_hired"],
            "dateStarted"    => $data["date_started"],
            "dateResigned" => $data["date_resigned"],
            "status"       => $data["status"]
        );
    }
    echo jsonify($response);
});

# Check Emp Employment Status
$app->get("/check/emp/employment/status/:id", function($id){
    $response = array();
    $check    = checkUserEmploymentStatus($id);
    if($check){
        $response = array(
            "prompt" => 1
        );
    }else{
        $response = array(
            "prompt" => 0
        );
    }

    echo jsonify($response);
});

# Set Emp Employment Status
$app->post("/set/emp/employment/status/:uid", function($uid){
    $empStatusUid = xguid();
    $empStatus    = $_POST["empStatus"];
    $datehired    = $_POST["datehired"];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    $check = checkUserEmploymentStatus($uid);

    if($check){
        $response = array(
            "prompt" => 1
        );
    }else{
        setEmpEmploymentStatus($empStatusUid, $uid, $empStatus, $datehired, $dateCreated, $dateModified);
        $response = array(
            "prompt" => 0
        );
    }
    echo jsonify($response);
});

# Update Emp Employment Status
$app->post("/update/emp/employment/status/:uid", function($uid){
    $employeeStatus = $_POST["employeeStatus"];
    $dateHired      = $_POST["dateHired"];
    $dateStarted      = $_POST["dateStarted"];
    $dateResigned   = $_POST["dateResigned"];
    $status         = $_POST["status"];
    $dateModified   = date("Y-m-d H:i:s");

    if($dateResigned == ""){
        $dateResigned = "0000-00-00";
    }else{
        $dateResigned = $dateResigned;
    }

    if($dateStarted == ""){
        $dateStarted = "0000-00-00";
    }else{
        $dateStarted = $dateStarted;
    }

    updateEmpEmployeeStatus($uid, $employeeStatus, $dateHired, $dateStarted, $dateResigned, $dateModified, $status);
});
/*---------------------------------employment Status end--------------------------------------*/

/*------------------------------------Job Category----------------------------------------------*/
# Job Categories Get
$app->get("/job/categories/get/:var" , function($var){
    //parameter: term.start.size.token
    $param         = explode(".", $var);
    $response      = array();
    
    $jobCategories = getJobCategories();
    foreach ($jobCategories as $jobCategory) {
        $response[] = array(
            "jobCategoryUid" => $jobCategory->job_category_uid,
            "name"           => $jobCategory->name
        );
    }
    echo jsonify($response);
});

# Job Category Pages Get
$app->get("/job/category/pages/get/:var" , function($var){
    //parameter: term.start.size.token
    $param         = explode(".", $var);
    $response      = array();
    
    $jobCategories = getPaginatedJobCategory();

    foreach ($jobCategories as $jobCategory) {
        $response[] = array(
            "jobCategoryUid" => $jobCategory->job_category_uid,
            "name"           => $jobCategory->name
        );
    }
    echo jsonify($response);
});

# Job Category New
$app->post("/job/category/new/:var" , function($var){
    //parameter: term.start.size.token
    $param          = explode(".", $var);
    $token          = $param[0];
    
    $jobCategoryUid = xguid();
    $name           = $_POST['name'];
    $dateCreated    = date("Y-m-d H:i:s");
    $dateModified   = date("Y-m-d H:i:s");
    
    $newJobCategory = newJobCategory($jobCategoryUid , $name , $dateCreated , $dateModified);
    $response = array(
        "status" => $newJobCategory
    );
    echo jsonify($response);
});

# Job Category Update
$app->post("/job/category/update/:var" , function($var){
    //parameter: term.start.size.token
    $param          = explode(".", $var);
    $token          = $param[1];
    
    $jobCategoryUid = $param[0];
    $name           = $_POST['name'];
    $dateModified   = date("Y-m-d H:i:s");
    $status         = $_POST['status'];
    $jobCategory    = getJobCategoryByUid($jobCategoryUid);

    if($name != $jobCategory->name OR $status != $jobCategory->status){
        updateJobCategoryById($jobCategoryUid , $name , $dateModified , $status);
        if(jobCategoryCount($name)>1){
            updateJobCategoryById($employmentStatusUid , $employmentStatus->name , $dateModified , $status);
        }
    }
});

# Job Category Details Get
$app->get("/job/category/details/get/:var" , function($var){
    //parameter: term.start.size.token
    $param          = explode(".", $var);
    $token          = $param[1];
    
    $jobCategoryUid = $param[0];
    
    $jobCategory    = getJobCategoryByUid($jobCategoryUid);
    if($jobCategory){
        $response = array(
            "name"   => $jobCategory->name,
            "status" => $jobCategory->status
        );
    }else{
        $response = array(
            "name"   => "",
            "status" => ""
        );
    }

    echo jsonify($response);
});

/*-----------------------------------Job Category End------------------------------------------*/


/*------------------------------------Country----------------------------------------------*/
# Countries Pages Get
$app->get("/countries/pages/get/:var" , function($var){
    //parameter: term.start.size.token
    $param     = explode(".", $var);
    $term      = $param[0];
    $response  = array();
    
    $countries = getPaginatedCountries();

    foreach ($countries as $country) {
        $response[] = array(
            "code"       => $country->code,
            "name"       => $country->name,
            "iso"        => $country->iso,
            "numCode"    => $country->num_code,
            "countryUid" => $country->country_uid
        );
    }
    echo jsonify($response);
});

# Countries Get
$app->get("/countries/get/:var" , function($var){
    //parameter: term.start.size.token
    $param     = explode(".", $var);
    $response  = array();
    
    $countries = getCountries();
    // sort($countries);
    foreach ($countries as $country) {
        $response[] = array(
            "code"       => $country->code,
            "name"       => $country->name,
            "iso"        => $country->iso,
            "numCode"    => $country->num_code,
            "countryUid" => $country->country_uid
        );
    }
    echo jsonify($response);
});

# Country New
$app->post("/country/new/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[0];
    
    $countryUid   = xguid();
    $code         = $_POST['code'];
    $name         = $_POST['name'];
    $iso          = $_POST['iso'];
    $numCode      = $_POST['numCode'];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");
    
    $newCountry   = newCountry($countryUid , $code , $name , $iso , $numCode , $dateCreated , $dateModified);
    $response     = array(
        "status" => $newCountry
    );
    echo jsonify($response);
});

# Country Update 
$app->post("/country/update/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[1];
    
    $countryUid   = $param[0];
    $code         = $_POST['code'];
    $name         = $_POST['name'];
    $iso          = $_POST['iso'];
    $numCode      = $_POST['numCode'];
    $dateModified = date("Y-m-d H:i:s");
    $status       = $_POST['status'];
    $country      = getCountryByUid($countryUid);

    if($code != $country->code OR $name != $country->name OR $iso != $country->iso OR $numCode != $country->num_code OR $status != $country->status){
        updateCountryById($countryUid ,  $code , $name , $iso , $numCode , $dateModified , $status);
        if(countriesCount($name)>1){
            updateCountryById($countryUid , $country->code , $country->name , $country->iso , $country->num_code , $dateModified , $status);
        }
    }
});

# Country Details Get
$app->get("/country/details/get/:var" , function($var){
    //parameter: term.start.size.token
    $param      = explode(".", $var);
    $token      = $param[1];
    
    $countryUid = $param[0];
    
    $country    = getCountryByUid($countryUid);
    if($country){
        $response = array(
            "code"    => $country->code,
            "name"    => $country->name,
            "iso"     => $country->iso,
            "numCode" => $country->num_code,
            "status"  => $country->status
        );
    }else{
        $response = array(
            "code"    => "",
            "name"    => "",
            "iso"     => "",
            "numCode" => "",
            "status"  => ""
        );
    }

    echo jsonify($response);
});

/*-----------------------------------Country End------------------------------------------*/

/*-----------------------------------General Information------------------------------------------*/   
# General Information Details
$app->get("/general/information/details/:var" , function($var){
    //parameter: term.start.size.token
    $param              = explode(".", $var);
    $token              = $param[0];
    
    $response           = array();
    $generalInformation = getGeneralInformation();
    if($generalInformation){
        $response = array(
            "organizationName"   => $generalInformation->name,
            "taxId"              => $generalInformation->tax_id,
            "registrationNumber" => $generalInformation->registration_number,
            "phone"              => $generalInformation->phone,
            "fax"                => $generalInformation->fax,
            "email"              => $generalInformation->email,
            "country"            => $generalInformation->country,
            "state"              => $generalInformation->province,
            "city"               => $generalInformation->city,
            "zipCode"            => $generalInformation->zip_code,
            "address1"           => $generalInformation->street_1,
            "address2"           => $generalInformation->street_2,
            "note"               => $generalInformation->note,
            "numberOfEmployees"  => getEmployeesCount()
        );
    }

    echo jsonify($response);
});

# General Information Update
$app->post("/general/information/update/:var" , function($var){
    //parameter: term.start.size.token
    $param              = explode(".", $var);
    $token              = $param[0];
    
    $organizationName   = $_POST['organizationName'];
    $taxId              = $_POST['taxId'];
    $registrationNumber = $_POST['registrationNumber'];
    $phone              = $_POST['phone'];
    $fax                = $_POST['fax'];
    $email              = $_POST['email'];
    $address1           = $_POST['address1'];
    $address2           = $_POST['address2'];
    $city               = $_POST['city'];
    $state              = $_POST['state'];
    $zipCode            = $_POST['zipCode'];
    $country            = $_POST['country'];
    $note               = $_POST['note'];
    $dateCreated        = date("Y-m-d H:i:s");
    $dateModified       = date("Y-m-d H:i:s");
    
    $generalInformation = getGeneralInformation();
    if(!generalInformationIsExisting()){
        $generalInformationUid = xguid();
        $subUnitUid            = xguid();
        newGeneralInformation($generalInformationUid , $organizationName , $taxId , $registrationNumber , $phone , $fax , $email , $address1 , $address2 , $city , $state , $zipCode , $country , $note , $dateCreated , $dateModified);

        insertSubunit($subUnitUid , "" , $organizationName , "" , "" , 0 , $dateCreated , $dateModified);
    }else{
        $generalInformationUid = $generalInformation->gen_info_uid;
        updateGeneralInformation($generalInformationUid , $organizationName , $taxId , $registrationNumber , $phone , $fax , $email , $address1 , $address2 , $city , $state , $zipCode , $country , $note , $dateModified);

        $subunit = getSubunitByName($organizationName);


        updateSubunitById($subunit->subunit_uid , $organizationName , $subunit->unit_id , $subunit->description , $subunit->lft , $subunit->lgt , $subunit->parent , $dateModified , $subunit->status);
    }
});

/*-----------------------------------General Information End------------------------------------------*/

/*-----------------------------------Location------------------------------------------*/ 
# Locations Get
$app->get("/locations/get/:var" , function($var){
    //parameter: term.start.size.token
    $param     = explode(".", $var);
    $token     = $param[0];
    
    $locations = getLocations();
    foreach ($locations as $location) {
        $response[] = array(
            "locationUid" => $location->location_uid,
            "name"        => $location->name
        );
    }
    echo jsonify($response);
});  

# Location Pages Get
$app->get("/location/pages/get/:var" , function($var){
    //parameter: term.start.size.token
    $param     = explode(".", $var);
    $response  = array();
    $locations = getPaginatedLocation();
    
    foreach ($locations as $location) {
        $response[] = array(
            "name"        => $location->name,
            "city"        => $location->city,
            "country"     => getCountryByUid($location->country_uid)->name,
            "phone"       => $location->phone,
            //kulang
            "locationUid" => $location->location_uid
        );
    }
    echo jsonify($response);
});

# Location New
$app->post("/location/new/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[0];
    
    $locationUid  = xguid();
    $name         = $_POST['name'];
    $country      = $_POST['country'];
    $province     = $_POST['province'];
    $city         = $_POST['city'];
    $address      = $_POST['address'];
    $zipCode      = $_POST['zipCode'];
    $phone        = $_POST['phone'];
    $tax          = $_POST['tax'];
    $fax          = $_POST['fax'];
    $notes        = $_POST['notes'];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    newLocation($locationUid , $name , $country , $province , $city , $address , $zipCode , $phone , $tax , $fax , $notes , $dateCreated , $dateModified);
});

# Location Details Get
$app->get("/location/details/get/:var" , function($var){
    //parameter: term.start.size.token
    $param       = explode(".", $var);
    $token       = $param[1];
    $locationUid = $param[0];
    $location    = getLocationByUid($locationUid);
    if($location){
        $country = getCountryByUid($location->country_uid);
        if($country){
            $countryName = $country->name;
        }else{
            $countryName = "";
        }

        $response = array(
            "name"       => $location->name,
            "country"    => $countryName,
            "countryUid" => $location->country_uid,
            "province"   => $location->province,
            "city"       => $location->city,
            "address"    => $location->address,
            "zipCode"    => $location->zip_code,
            "phone"      => $location->phone,
            "tax"        => $location->tax,
            "fax"        => $location->fax,
            "notes"      => $location->notes,
            "status"     => $location->status 
        );
    }else{
        $response = array(
            "name"       => "",
            "country"    => "",
            "countryUid" => "",
            "province"   => "",
            "city"       => "",
            "address"    => "",
            "zipCode"    => "",
            "phone"      => "",
            "tax"        => "",
            "fax"        => "",
            "notes"      => "",
            "status"     => ""
        );
    }

    echo jsonify($response);
});

# Location Update
$app->post("/location/update/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[1];
    
    $locationUid  = $param[0];
    $name         = $_POST['name'];
    $country      = $_POST['country'];
    $province     = $_POST['province'];
    $city         = $_POST['city'];
    $address      = $_POST['address'];
    $zipCode      = $_POST['zipCode'];
    $phone        = $_POST['phone'];
    $tax          = $_POST['tax'];
    $fax          = $_POST['fax'];
    $notes        = $_POST['notes'];
    $dateModified = date("Y-m-d H:i:s");
    $status       = $_POST['status'];
    
    $location     = getLocationByUid($locationUid);

    if($name != $location->name OR $country != $location->country_uid OR $province != $location->province OR $city != $location->city OR $address != $location->address OR $zipCode != $location->zip_code OR $phone != $location->phone OR $tax != $location->tax OR $fax != $location->fax OR $notes != $location->notes OR $status != $location->status){
        updateLocationById($locationUid , $name , $country , $province , $city , $address , $zipCode , $phone , $tax , $fax , $notes , $dateModified , $status);
        if(locationCount($name)>1){
            updateLocationById($locationUid , $location->name , $location->country , $location->province , $location->city , $location->address , $location->zip_code , $location->phone , $location->tax , $location->fax , $location->notes , $dateModified , $status);
        }
    }
});

# Location Status Update
$app->post("/location/status/update/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[1];
    
    $locationUid  = $param[0];
    $dateModified = date("Y-m-d H:i:s");
    $status       = $_POST['status'];

    updateLocationStatusByUid($locationUid,$dateModified,$status);
});

/*-----------------------------------Location End------------------------------------------*/

/*-----------------------------------Structure------------------------------------------*/
# Structure Subunits Get
$app->get("/structure/subunits/get/:var" , function($var){
    //parameter: term.start.size.token
    $param    = explode(".", $var);
    $token    = $param[0];
    
    $subunits = getSubunits();
    foreach ($subunits as $subunit) {
        $response[] = array(
            "subunitUid" => $subunit->subunit_uid,
            "name"       => $subunit->name
        );
    }
    echo jsonify($response);
});

# Subunits Get
$app->get("/subunits/get/:var" , function($var){
    $param    = explode(".", $var);
    $token    = $param[0];
    $response = array();
    $subunits = displayChildren("" , 0);

    usort($subunits, function($a, $b) {
        return $a['lft'] - $b['lft'];
    });
    echo jsonify($subunits);
});

# Subunit Get
$app->get("/subunit/get/:var" , function($var){
    $param   = explode(".", $var);
    $token   = $param[1];
    
    $uid     = $param[0];
    $subunit = getSubunit($uid);

    $response = array(
        "name"        => $subunit->name,
        "unitId"      => $subunit->unit_id,
        "description" => $subunit->description,
        "status"      => $subunit->status,
    );

    echo jsonify($response);
});

# Subunit New
$app->post("/subunit/new/:var" , function($var){
    $name        = $_POST['name'];
    $unitId      = $_POST['unitId'];
    $description = $_POST['description'];
    
    $subunit     = getSubunit($var);
    $subunitUid  = xguid();
    $dateCreated = date("Y-m-d H:i:s");
    $parent      = $subunit->name;
    $getlft      = $subunit->rgt - 1;

    if(!subUnitIsExisting($name)){
        updateRgtSubunit($getlft);
        updateLftSubunit($getlft);
        $status = insertSubunit($subunitUid , $parent , $name , $unitId , $description , $getlft , $dateCreated , $dateCreated);
    }else{
        $su = getSubunitByName($name);
        if($su->status != 1){
            updateRgtSubunit($getlft);
            updateLftSubunit($getlft);
            $lft = $getlft + 1;
            $rgt = $getlft + 2;
            updateSubunitById($su->subunit_uid , $name , $unitId , $description , $lft , $rgt , $parent , $dateCreated , 1);
        }
    }
    
    rebuildTree(getSubunitsMain(), 1);
});

# Subunit Edit
$app->post("/subunit/edit/:var" , function($var){
    $name         = $_POST['name'];
    $unitId       = $_POST['unitId'];
    $description  = $_POST['description'];
    $status       = $_POST['status'];
    
    $subunit      = getSubunit($var);
    
    $subunitUid   = $var;
    $parent       = $subunit->parent;
    $rgt          = $subunit->rgt;
    $lft          = $subunit->lft;
    
    $dateModified = date("Y-m-d H:i:s");
    if($subunit->name != $name OR $subunit->unit_id != $unitId OR $subunit->description != $description OR $subunit->status != $status){
        if($status == 0){
            deleteSubunitBetweenLftAndRgt($lft , $rgt , $dateModified , $status);
            $rgt = "null";
            $lft = "null";
        }else{
            updateSubunitById($subunitUid , $name , $unitId , $description , $lft , $rgt , $parent , $dateModified , $status);
            if(subUnitCount($name)>1){
                updateSubunitById($subunitUid , $subunit->name , $subunit->unit_id , $subunit->description , $lft , $rgt , $parent , $dateModified , $status);
            }
        }
    }

    rebuildTree(getSubunitsMain(), 1);
});
/*-----------------------------------Structure END------------------------------------------*/

/*-----------------------------------working Experience------------------------------------------*/
# Work Experience Update 
$app->post("/work/experience/update/:var" , function($var){

    $param             = explode(".", $var);
    
    $workExperienceUid = $param[0];
    $employer          = $_POST['employerWEx'];
    $jobTitle          = $_POST['jobTitleWEx'];
    $from              = $_POST['fromWEx'];
    $to                = $_POST['toWEx'];
    $dateModified      = date("Y-m-d H:i:s");
    $status            = $_POST['status'];

    updateWorkExperience($employer , $jobTitle , $from , $to , $dateModified , $status , $workExperienceUid);
});

# Work Experience Details Get
$app->get("/work/experience/details/get/:var" , function($var){

    $param             = explode(".", $var);
    $workExperienceUid = $param[0];
    $token             = $param[1];
    $response          = array();
    $workExperience    = getWorkExperienceByWorkExperienceUid($workExperienceUid);
    if($workExperience){
        $response = array(
            "workExperienceUid" => $workExperience->work_experience_uid,
            "employerWE"        => $workExperience->employer,
            "jobTitleWE"        => $workExperience->job_title,
            "fromWE"            => $workExperience->we_from,
            "status"            => $workExperience->status,
            "toWE"              => $workExperience->we_to
        );
    }
    
    echo jsonify($response);
});

# Work Experience New
$app->post("/work/experience/new/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[1];
    $empUid       = $param[0];
    
    $empWEUid     = xguid();
    
    $employer     = $_POST['employerWE'];
    $jobTitle     = $_POST['jobTitleWE'];
    $from         = $_POST['fromWE'];
    $to           = $_POST['toWE'];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    newWorkingExperince($empWEUid , $empUid , $employer , $jobTitle , $from , $to , $dateCreated , $dateModified);
});

/*-----------------------------------working Experience END------------------------------------------*/
# Employee Job Pages Get
$app->get("/employee/job/pages/get/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $empUid       = $param[0];
    $response     = array();
    
    $employeeJobs = getPaginatedEmployeeJob($empUid);
    
    foreach ($employeeJobs as $employeeJob) {
        $job      = getJobByUid($employeeJob->job_uid);
        $jobTitle = "";
        if($job){
            if($job->status == 1){
                $jobTitle =  $job->title;
            }
        }
        $response[] = array(
            "jobTitle"         => $jobTitle,
            "jobCategory"      => $jobCategoryName,
            "subunit"          => $subunitName,
            "location"         => $locationName,
            "employmentStatus" => $employmentStatusName,
            "employeeJobUid"   => $employeeJob->employee_job_uid
        );
    }
    echo jsonify($response);
});

# New Leave Request 
$app->post("/new/leave/request/:var", function($var){
    $param         = explode(".", $var);
    $token         = $param[0];
    
    $leaveUid      = xguid();
    $leaveNotifUid = xguid();
    $employee      = $_POST['employee'];
    $leaveType     = $_POST['leaveType'];
    $leaveBalance  = "";
    $startDate     = $_POST['startDate'];
    $endDate       = $_POST['endDate'];
    $reason        = $_POST['reason'];
    $requestStatus = $_POST['requestStatus'];
    $dateCreated   = date("Y-m-d H:i:s");
    $dateModified  = date("Y-m-d H:i:s");
    $response      = array();
    $countOfLeave  = getDaysOfWorkByDateRange($startDate, $endDate);
	
	$admin = false;
	$userType = $_POST['userType'];
	if($userType === "Administrator") {
		$admin = true;
	}

    if(strtotime($startDate) <= strtotime($endDate)){
        $valid = true;
    }else{
        $valid = false;
    }
    
	$validator = false;
	$code = getLeaveCodeByUid($leaveType);
    // $checkRequest = checkPayrollSchedBeforeRequest($startDate);
    // if($checkRequest["prompt"]){
		// if($valid) {
			// $validator = true;
		// }
		// else {
			// $dataError = 3;
			// $prompt = "";
		// }
    // }else {
		// if($valid) {
			// if($admin) {
				// $validator = true;
			// }
			// $dataError = 1;
			// $prompt = "";
		// }
		// else {
			// $dataError = 3;
			// $prompt = "";
		// }
	// }
	
	if($valid) {
		//if($admin) {
			$validator = true;
		//}
		//$dataError = 1;
		//$prompt = "";
	}
	else {
		$dataError = 3;
		$prompt = "";
	}
	
	if($validator) {
		if($valid){
            if($code === "AB" || $code === "W"){
                newLeaveRequest($leaveUid, $employee, $leaveType, $leaveBalance, $startDate, $endDate, $reason ,$requestStatus, $dateCreated, $dateModified);
                addLeaveNotification($leaveNotifUid, $leaveUid, $requestStatus, $dateCreated, $dateModified);
                
				$dataError = 0;
				$prompt = "Successfully Added!";
            }else{
                $checkLeaveCount = checkEmpLeaveCountByEmpUid($employee);
                if($checkLeaveCount){
                    $empLeave   = getEmpLeaveCountByEmp($employee);
                    $leaveCount = leaveCountsByEmpUid($employee);
                    $SL         = $leaveCount["SL"];
                    $BL         = $leaveCount["BL"];
                    $BV         = $leaveCount["BV"];
                    $VL         = $leaveCount["VL"];
                    $ML         = $leaveCount["ML"];
                    $PL         = $leaveCount["PL"];
                    $P          = $leaveCount["P"];
                    switch($code){
                        case "P":
                            if($SL <= 0) {
								$dataError = 5;
								$prompt = "You used all your Personal Leave!";
                            }else{
                                $P = $P - $countOfLeave;
                                newLeaveRequest($leaveUid, $employee, $leaveType, $leaveBalance, $startDate, $endDate, $reason ,$requestStatus, $dateCreated, $dateModified);
                                addLeaveNotification($leaveNotifUid, $leaveUid, $requestStatus, $dateCreated, $dateModified);
								
								$dataError = 0;
								$prompt = "Successfully Added! You only have " . $P . " Personal Leave. Please be noted that the approval might take a few days.";
                            }
                            break;
                        case "SL":
                            if($SL <= 0) {
								$dataError = 5;
								$prompt = "You used all your Sick Leave!";
                            }else{
                                $SL = $SL - $countOfLeave;
                                newLeaveRequest($leaveUid, $employee, $leaveType, $leaveBalance, $startDate, $endDate, $reason ,$requestStatus, $dateCreated, $dateModified);
                                addLeaveNotification($leaveNotifUid, $leaveUid, $requestStatus, $dateCreated, $dateModified);
								
								$dataError = 0;
								$prompt = "Successfully Added! You only have " . $SL . " Sick Leave. Please be noted that the approval might take a few days.";
                            }
                            break;
                        case "BL":
                            if($BL <= 0) {
								$dataError = 5;
								$prompt = "You used all your Birthday Leave!";
                            }else{
                                $BL = $BL - $countOfLeave;
                                newLeaveRequest($leaveUid, $employee, $leaveType, $leaveBalance, $startDate, $endDate, $reason ,$requestStatus, $dateCreated, $dateModified);
                                addLeaveNotification($leaveNotifUid, $leaveUid, $requestStatus, $dateCreated, $dateModified);
								
								$dataError = 0;
								$prompt = "Successfully Added! You only have " . $BL . " Birthday Leave. Please be noted that the approval might take a few days.";
                            }
                            break;
                        case "BV":
                            if($BV <= 0) {								
								$dataError = 5;
								$prompt = "You used all your Bereavement Leave!";
                            }else{
                                $BV = $BV - $countOfLeave;
                                newLeaveRequest($leaveUid, $employee, $leaveType, $leaveBalance, $startDate, $endDate, $reason ,$requestStatus, $dateCreated, $dateModified);
                                addLeaveNotification($leaveNotifUid, $leaveUid, $requestStatus, $dateCreated, $dateModified);
								
								$dataError = 0;
								$prompt = "Successfully Added! You only have " . $BV . " Bereavement Leave Left. Please be noted that the approval might take a few days.";
                            }
                            break;
                        case "VL":
                            if($VL <= 0) {								
								$dataError = 5;
								$prompt = "You used all your Vacation Leave!";
                            }else{
                                $VL = $VL - $countOfLeave;
                                newLeaveRequest($leaveUid, $employee, $leaveType, $leaveBalance, $startDate, $endDate, $reason ,$requestStatus, $dateCreated, $dateModified);
                                addLeaveNotification($leaveNotifUid, $leaveUid, $requestStatus, $dateCreated, $dateModified);
								
								$dataError = 0;
								$prompt = "Successfully Added! You only have " . $VL . " Vacation Leave Left. Please be noted that the approval might take a few days.";
                            }
                            break;
                        case "ML":
                            if($ML <= 0) {								
								$dataError = 5;
								$prompt = "You used all your Maternity Leave!";
                            }else{
                                $ML = $ML - $countOfLeave;
                                newLeaveRequest($leaveUid, $employee, $leaveType, $leaveBalance, $startDate, $endDate, $reason ,$requestStatus, $dateCreated, $dateModified);
                                addLeaveNotification($leaveNotifUid, $leaveUid, $requestStatus, $dateCreated, $dateModified);
								
								$dataError = 0;
								$prompt = "Successfully Added! You only have " . $ML . " Maternity Leave Left. Please be noted that the approval might take a few days.";
                            }
                            break;
                        case "PL":
                            if($PL <= 0) {								
								$dataError = 5;
								$prompt = "You used all your Paternity Leave!";
                            }else {
                                $PL = $PL - $countOfLeave;
                                newLeaveRequest($leaveUid, $employee, $leaveType, $leaveBalance, $startDate, $endDate, $reason ,$requestStatus, $dateCreated, $dateModified);
                                addLeaveNotification($leaveNotifUid, $leaveUid, $requestStatus, $dateCreated, $dateModified);
								
								$dataError = 0;
								$prompt = "Successfully Added! You only have " . $PL . " Paternity Leave Left. Please be noted that the approval might take a few days.";
                            }
                            break;
                    }
                }else {
					$dataError = 4;
					$prompt = "Employee doesn't have Leave Count!";
                }
            }
            
        }else {
			$dataError = 3;
			$prompt = "";
        }
	}
	else {
		if($valid) {
			$dataError = 1;
			$prompt = "";
        }else{			
			$dataError = 3;
			$prompt = "";
        }
	}	
	
	$response = array(
		"dataError" => $dataError,
		"prompt" => $prompt
	);
	
    echo jsonify($response);
});

# Get Leave Period
$app->get("/get/leave/period/:var", function($var){
    $param        = explode(".", $var);
    $token        = $param[0];
    $leavePeriods = getLeavePeriod();
    foreach ($leavePeriods as $leavePeriod) {
        $response[] = array(
            "uid"   => $leavePeriod->leave_period_uid,
            "month" => $leavePeriod->start_month,
            "day"   => $leavePeriod->start_day,
            "from"  => $leavePeriod->from_period,
            "to"    => $leavePeriod->to_period,
        );
    }
    echo jsonify($response);
});

# Get Employee Name
$app->get("/get/employee/name/:var", function($var){
    $param     = explode(".", $var);
    $token     = $param[0];
    $employees = getActiveEmployees();
    foreach ($employees as $employee) {
        $response[] = array(
            "uid"             => $employee->emp_uid,
            "firstname"       => utf8_decode($employee->firstname),
            "middlename"      => utf8_decode($employee->middlename),
            "lastname"        => utf8_decode($employee->lastname),
            "employee_number" => $employee->username
        );
    }
    echo jsonify($response);
});

# Get Employee Name ID
$app->get("/get/employee/name/id/:var", function($var){
    $param     = explode(".", $var);
	$uid     = $param[0];
    $token     = $param[1];
	$response = array();
    $employees = read_employee_name_by_uid($uid);	
	$response[] = array(
		"name" => $employees
	);
    echo jsonify($response);
});

# Get Employee Name Without Benefits
$app->get("/get/employee/name/without/benefit/:var", function($var){
    $param     = explode(".", $var);
    $token     = $param[0];
    $employees = getEmpWithoutBenefits();
    foreach ($employees as $employee) {
        $response[] = array(
            "empUid"     => $employee->emp_uid,
            "firstname"  => utf8_decode($employee->firstname),
            "middlename" => utf8_decode($employee->middlename),
            "lastname"   => utf8_decode($employee->lastname),
            "empNo"      => $employee->username
        );
    }
    echo jsonify($response);
});

# Get Leave Types
$app->get("/get/leave/types/:var", function($var){
    $param      = explode(".", $var);
    $token      = $param[0];
    $leaveTypes = getPaginatedLeaveTypes();
    foreach ($leaveTypes as $leaveType) {
        $response[] = array(
            "uid"  => $leaveType->leaves_types_uid,
            "name" => $leaveType->leave_name,
        );
    }
    echo jsonify($response);
});

# Get Leave Types Emp
$app->get("/get/leave/types/emp/:var", function($var){
    $param = explode(".", $var);
    $uid   = $param[0];
    $x     = getLeaveTypeDataByUid($uid);
    if($x){
        $response = array(
            "leaveTypesUid" => $x->leaves_types_uid,
            "leaveName"     => $x->leave_name,
            "status"        => $x->status
        );
    }
    echo jsonify($response);
});

/*------------------------------------education---------------------------*/
# Education Get
$app->get("/education/get/:var" , function($var){
    //parameter: token
    $param      = explode(".", $var);
    $empUid     = $param[0];
    $token      = $param[1];
    $response   = array();
    $educations = getEducationByUid($empUid);
    foreach ($educations as $education) {
        $response[] = array(
            "educationLevelUid" => $education->level_name,
            "year"              => $education->year,
            "score"             => $education->score,
            "educationUid"      => $education->education_uid
        );
    }
    echo jsonify($response);
});

# Skill Detail Get
$app->get("/skill/detail/get/:var" , function($var){

    $param    = explode(".", $var);
    $empUid   = $param[0];
    $token    = $param[1];
    
    $response = array();
    $skills   = getSkillByUid($empUid);
    foreach ($skills as $skill) {
        $response[] = array(
            "skillUid"        => $skill->skill_type,
            "yearsExperience" => $skill->years_experience,
            "hrisSkillUid"    => $skill->hris_skill_uid
        );
    }
    echo jsonify($response);
});

# Languages Details Get
$app->get("/languages/detail/get/:var" , function($var){

    $param     = explode(".", $var);
    $empUid    = $param[0];
    $token     = $param[1];
    $response  = array();
    $languages = getLanguagesByUid($empUid);
    foreach ($languages as $language) {
        $response[] = array(
            "languagesUid"    => $language->language_name,
            "fluency"         => $language->fluency,
            "competency"      => $language->competency,
            "empLanguagesUid" => $language->emp_languages_uid
        );
    }
    echo jsonify($response);
});

# License Detail Get
$app->get("/license/detail/get/:var" , function($var){

    $param    = explode(".", $var);
    $empUid   = $param[0];
    $token    = $param[1];
    $response = array();
    $licenses = getLicenseByUid($empUid);
    foreach ($licenses as $license) {
        $response[] = array(
            "licenseUid"     => $license->license_name,
            "issuedDate"     => $license->issued_date,
            "expiryDate"     => $license->expiry_date,
            "hrisLicenseUid" => $license->hris_license_uid
        );
    }
    echo jsonify($response);
});

# Work Experience Get
$app->get("/work/experience/get/:var" , function($var){
    //parameter: token
    $param           = explode(".", $var);
    $empUid          = $param[0];
    $token           = $param[1];
    $response        = array();
    $workExperiences = getWorkExperienceByUid($empUid);
    foreach ($workExperiences as $workExperience) {
        $response[] = array(
            "workExperienceUid" => $workExperience->work_experience_uid,
            "employer"          => $workExperience->employer,
            "jobTitle"          => $workExperience->job_title,
            "from"              => $workExperience->we_from,
            "to"                => $workExperience->we_to
        );
    }
    echo jsonify($response);
});

# Education New
$app->post("/education/new/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[1];
    $empUid       = $param[0];
    $educationUid = xguid();
    
    $levelDegree  = $_POST['levelDegree'];
    $school       = $_POST['school'];
    $year         = $_POST['year'];
    $major        = $_POST['major'];
    $score        = $_POST['score'];
    $startDate    = $_POST['startDate'];
    $endDate      = $_POST['endDate'];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    newEducation($educationUid , $empUid , $levelDegree , $school , $year , $major , $score , $startDate , $endDate , $dateCreated , $dateModified);
});

# Education Level Get
$app->get("/education/level/get/" , function(){
    //parameter: token
    $response        = array();
    $educationLevels = getEducationLevel();
    foreach ($educationLevels as $educationLevel) {
        $response[] = array(
            "educationLevelUid" => $educationLevel->education_level_uid,
            "levelName"         => $educationLevel->level_name
        );
    }

    echo jsonify($response);
});

# Education Details Get
$app->get("/education/details/get/:var" , function($var){

    $param        = explode(".", $var);
    $educationUid = $param[0];
    $token        = $param[1];
    $response     = array();
    $education    = getEducationByEducationUid($educationUid);
    if($education){
        $response = array(
            "educationUid"      => $education->education_uid,
            "educationLevelUid" => $education->level_name,
            "year"              => $education->year,
            "score"             => $education->score,
            "school"            => $education->school,
            "major"             => $education->major,
            "startDate"         => $education->start_date,
            "endDate"           => $education->end_date,
            "status"            => $education->status
        );
    }
    
    echo jsonify($response);
});

# Education Update
$app->post("/education/update/:var" , function($var){

    $param        = explode(".", $var);
    
    $educationUid = $param[0];
    $levelDegree  = $_POST['levelDegree'];
    $school       = $_POST['school'];
    $year         = $_POST['year'];
    $major        = $_POST['major'];
    $score        = $_POST['score'];
    $startDate    = $_POST['startDate'];
    $endDate      = $_POST['endDate'];
    $status       = $_POST['status'];
    $dateModified = date("Y-m-d H:i:s");

    updateEducation($levelDegree , $school , $year , $major , $score , $startDate , $endDate , $status , $dateModified , $educationUid);
});

# Skill Update 
$app->post("/skill/update/:var" , function($var){

    $param           = explode(".", $var);
    $skillUid        = $param[0];
    $skillType       = $_POST['skillType'];
    $yearsExperience = $_POST['yearsExperience'];
    $status          = $_POST['status'];
    $dateModified    = date("Y-m-d H:i:s");

    updateSkill($skillUid , $skillType , $yearsExperience , $status , $dateModified);
});

# Skill Details Get
$app->get("/skill/details/get/:var" , function($var){

    $param    = explode(".", $var);
    $skillUid = $param[0];
    $token    = $param[1];
    $response = array();
    $skill    = getSkillBySkillUid($skillUid);
    if($skill){
        $response = array(
            "skillUid"  => $skill->hris_skill_uid,
            "skillType" => $skill->skill_type,
            "year"      => $skill->years_experience,
            "status"    => $skill->status
        );
    }
    
    echo jsonify($response);
});

# Skill Type
$app->get("/skill/type/get/" , function(){

    $response   = array();
    $skillTypes = getSkillType();
    foreach ($skillTypes as $skillType) {
        $response[] = array(
            "skillUid"  => $skillType->skill_uid,
            "skillType" => $skillType->skill_type
        );
    }

    echo jsonify($response);
});

# Skill New 
$app->post("/skill/new/:var" , function($var){
    $param           = explode(".", $var);
    $token           = $param[1];
    $empUid          = $param[0];
    $hrisSkillUid    = xguid();
    
    $skillType       = $_POST['skillType'];
    $yearsExperience = $_POST['yearsExperience'];
    $dateCreated     = date("Y-m-d H:i:s");
    $dateModified    = date("Y-m-d H:i:s");

    newHrisSkill($hrisSkillUid , $empUid , $skillType , $yearsExperience , $dateCreated , $dateModified);
});

# Languages Spoken New
$app->post("/languages/spoken/new/:var" , function($var){
    $param           = explode(".", $var);
    $token           = $param[1];
    $empUid          = $param[0];
    $empLanguagesUid = xguid();
    
    $languageName    = $_POST['languageName'];
    $fluency         = $_POST['fluency'];
    $competency      = $_POST['competency'];
    $dateCreated     = date("Y-m-d H:i:s");
    $dateModified    = date("Y-m-d H:i:s");

    newLanguagesSpoken($empLanguagesUid , $empUid , $languageName , $fluency , $competency , $dateCreated , $dateModified);
});

# Language Details Get
$app->get("/language/details/get/:var" , function($var){
    
    $param        = explode(".", $var);
    $languagesUid = $param[0];
    $token        = $param[1];
    $response     = array();
    $language     = getLanguageBylanguagesUid($languagesUid);
    if($language){
        $response = array(
            "languagesUid"    => $language->language_name,
            "fluency"         => $language->fluency,
            "competency"      => $language->competency,
            "status"          => $language->status,
            "empLanguagesUid" => $language->emp_languages_uid
        );
    }
    
    echo jsonify($response);
});

# Language Get 
$app->get("/languge/get/" , function(){

    $response  = array();
    $languages = getLanguages();
    foreach ($languages as $language) {
        $response[] = array(
            "languagesUid" => $language->languages_uid,
            "languageName" => $language->language_name
        );
    }

    echo jsonify($response);
});

# Languages Update
$app->post("/languages/update/:var" , function($var){

    $param        = explode(".", $var);
    
    $languagesUid = $param[0];
    $languageName = $_POST['languageName'];
    $fluency      = $_POST['fluency'];
    $competency   = $_POST['competency'];
    $status       = $_POST['status'];
    $dateModified = date("Y-m-d H:i:s");

    updateLanguages($languageName , $fluency , $competency , $status , $dateModified , $languagesUid);
});

# License Type
$app->get("/license/type/get/" , function(){
    //parameter: token
    $response     = array();
    $licenseTypes = getLicenseType();
    foreach ($licenseTypes as $licenseType) {
        $response[] = array(
            "licenseUid"  => $licenseType->license_uid,
            "LicenseName" => $licenseType->license_name
        );
    }

    echo jsonify($response);
});

# License New
$app->post("/license/new/:var" , function($var){
    $param          = explode(".", $var);
    $token          = $param[1];
    $empUid         = $param[0];
    $hrisLicenseUid = xguid();
    
    $licenseType    = $_POST['licenseType'];
    $licenseNo      = $_POST['licenseNo'];
    $licenseIssued  = $_POST['licenseIssued'];
    $licenseExpiry  = $_POST['licenseExpiry'];
    $dateCreated    = date("Y-m-d H:i:s");
    $dateModified   = date("Y-m-d H:i:s");

    newHrisLicense($hrisLicenseUid , $empUid , $licenseType , $licenseNo , $licenseIssued , $licenseExpiry , $dateCreated , $dateModified);
});

# License Details Get
$app->get("/license/details/get/:var" , function($var){
    
    $param      = explode(".", $var);
    $licenseUid = $param[0];
    $token      = $param[1];
    $response   = array();
    $license    = getLicenseByLicenseUid($licenseUid);
    if($license){
        $response = array(
            "licenseUid"     => $license->license_name,
            "licenseNo"      => $license->license_no,
            "issuedDate"     => $license->issued_date,
            "expiryDate"     => $license->expiry_date,
            "status"         => $license->status,
            "hrisLicenseUid" => $license->hris_license_uid
        );
    }
    
    echo jsonify($response);
});

# License Update
$app->post("/license/update/:var" , function($var){

    $param         = explode(".", $var);
    $licenseUid    = $param[0];
    $licenseType   = $_POST['licenseType'];
    $licenseNo     = $_POST['licenseNo'];
    $licenseIssued = $_POST['licenseIssued'];
    $licenseExpiry = $_POST['licenseExpiry'];
    $status        = $_POST['status'];
    $dateModified  = date("Y-m-d H:i:s");

    updateLicense($licenseUid , $licenseType , $licenseNo , $licenseIssued , $licenseExpiry , $status , $dateModified);
});

# Employee Salary Get 
$app->get("/employee/salary/get/:var" , function($var){
    //parameter: token
    $param          = explode(".", $var);
    $empUid         = $param[0];
    $token          = $param[1];
    $response       = array();
    $employeeSalary = getSalaryByUid($empUid);
    if ($employeeSalary) {
        $response[] = array(
            "payGradeUid"  => $employeeSalary->paygrade_name,
            "currencyUid"  => $employeeSalary->name,
            "baseSalary"   => $employeeSalary->base_salary,
            "payPeriodUid" => $employeeSalary->pay_period_name,
            "salaryUid"    => $employeeSalary->salary_uid
        );
    }
    echo jsonify($response);
});

# Employee Salary Paygrade Get
$app->get("/employee/salary/paygrade/get/" , function(){
    //parameter: token
    $response  = array();
    $payGrades = getPayGrade();
    foreach ($payGrades as $payGrade) {
        $response[] = array(
            "payGradeUid"  => $payGrade->paygrade_uid,
            "payGradeName" => $payGrade->paygrade_name
        );
    }

    echo jsonify($response);
});

# Employee Salary Currency Get
$app->get("/employee/salary/currency/get/" , function(){
    //parameter: token
    $response   = array();
    $currencies = getCurrencies();
    foreach ($currencies as $currency) {
        $response[] = array(
            "currencyUid"  => $currency->currency_uid,
            "currencyName" => $currency->name
        );
    }

    echo jsonify($response);
});

# Employee Salary Frequency Get
$app->get("/employee/salary/frequency/get/" , function(){
    //parameter: token
    $response    = array();
    $frequencies = getFrequencies();
    foreach ($frequencies as $frequency) {
        $response[] = array(
            "frequencyUid"  => $frequency->pay_period_uid,
            "frequencyName" => $frequency->pay_period_name
        );
    }

    echo jsonify($response);
});

# Employee Salary New
$app->post("/employee/salary/new/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    
    $empUid       = $param[0];
    $salaryUid    = xguid();
    $baseSalary   = $_POST['baseSalary'];
    $payPeriodUid = $_POST['payPeriodUid'];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    $check = checkUserHasSalary($empUid);
    if($check){
        $response = array(
            "prompt" => 1
        );
    }else{
        $response = array(
            "prompt" => 0
        );
        newSalary($salaryUid, $empUid, $baseSalary, $payPeriodUid, $dateCreated, $dateModified);
        newEmpType(xguid(), "", $payPeriodUid, $empUid, $dateCreated, $dateModified);
    }
    echo jsonify($response);
});

# Salary Details Get
$app->get("/salary/details/get/:var" , function($var){
    //parameter: token
    $param     = explode(".", $var);
    $salaryUid = $param[0];
    $token     = $param[1];
    $response  = array();
    $salary    = getSalaryBySalaryUid($salaryUid);
    if($salary){
        $response = array(
        "frequencyUid" => $salary->pay_period_uid,
        "baseSalary"   => $salary->base_salary,
        "payPeriodUid" => $salary->pay_period_name,
        "status"       => $salary->status,
        "salaryUid"    => $salary->salary_uid
    );
    }
    
    echo jsonify($response);
});

# Employee Salary Update
$app->post("/employee/salary/update/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $empUid       = $_POST["uid"];
    $salaryUid    = $param[0];
    $baseSalary   = $_POST['baseSalary'];
    $payPeriodUid = $_POST['payPeriodUid'];
    $status       = $_POST['status'];
    $dateModified = date("Y-m-d H:i:s");

    updateSalary($salaryUid , $dateModified , $baseSalary , $payPeriodUid , $status);
    updateEmpType($empUid, $payPeriodUid, $dateModified);
});

# Employee Late Get
$app->get("/employee/late/get/:var", function($var){
    $param    = explode(".", $var);
    $uid      = $param[0];
    $token    = $param[1];
    $response = array();
    
    $a        = getLateByEmpUid($uid);
    foreach($a as $late){
        $response[] = array(
            "lateUid"  => $late->late_emp_uid,
            "name"     => $late->name,
            "duration" => $late->duration
        );
    }//end of getLateByEmpUid Function

    echo jsonify($response);
});

# Employee Late New
$app->post("/employee/late/new/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    
    $empUid       = $param[0];
    $lateEmpUid   = xguid();
    $lateUid      = $_POST['name'];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    newEmpLate($lateEmpUid, $empUid, $lateUid, $dateCreated, $dateModified);
});

# Late Details Get Edit
$app->get("/late/details/get/edit/:var" , function($var){
    //parameter: token
    $param    = explode(".", $var);
    $lateUid  = $param[0];
    $token    = $param[1];
    $response = array();
    $late     = getEmpLateByEmpUid($lateUid);
    if($late){
        $response = array(
            "lateUid"  => $late->late_emp_uid,
            "name"     => $late->name,
            "duration" => $late->duration,
            "status"   => $late->status
        );
    }
    
    echo jsonify($response);
});

# Employee Late Update 
$app->post("/employee/late/update/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    
    $uid          = $param[0];
    $lateUid      = $_POST['lateUid'];
    $name         = $_POST['name'];
    $status       = $_POST['status'];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    updateEmpLate($lateUid , $name , $status , $dateModified);
});

# Employee Immigration Get
$app->get("/employee/immigration/get/:var" , function($var){
    //parameter: token
    $param                = explode(".", $var);
    $empUid               = $param[0];
    $token                = $param[1];
    $response             = array();
    $employeeImmigrations = getImmigration($empUid);
    foreach ($employeeImmigrations as $employeeImmigration) {
        $response[] = array(
            "documentType"   => $employeeImmigration->document_type,
            "passportNo"     => $employeeImmigration->passport_no,
            "issuedBy"       => $employeeImmigration->name,
            "issuedDate"     => $employeeImmigration->issued_date,
            "expiryDate"     => $employeeImmigration->expiry_date,
            "immigrationUid" => $employeeImmigration->emp_immigration_uid
        );
    }
    echo jsonify($response);
});

# Employee Pettycash Get
$app->get("/employee/pettycash/get/:var" , function($var){
    //parameter: token
    $param             = explode(".", $var);
    $id                = $param[0];
    $token             = $param[1];
    $response          = array();
    $employeePettycash = getPettyCash($id);
    foreach ($employeePettycash as $pettycash) {
        $response[] = array(
            "amount"   => $pettycash->amount,
            "dueDate"  => date("M d, Y", strtotime($pettycash->due_date)),
            "pettyUid" => $pettycash->pettycash_uid
        );
    }
    echo jsonify($response);
});

# Employee Pettycash New
$app->post("/employee/pettycash/new/:var" , function($var){
    //parameter: term.start.size.token
    $param           = explode(".", $var);
    
    $empUid          = $param[0];
    $empPettycashUid = xguid();
    $amount          = $_POST['amount'];
    $dueDate         = $_POST['dueDate'];
    $dateCreated     = date("Y-m-d H:i:s");
    $dateModified    = date("Y-m-d H:i:s");

    newPettyCash($empPettycashUid, $empUid, $amount, $dueDate, $dateCreated, $dateModified);
});

# Pettycash Details Get Edit
$app->get("/pettycash/details/get/edit/:var" , function($var){
    //parameter: token
    $param        = explode(".", $var);
    $pettycashUid = $param[0];
    $token        = $param[1];
    $response     = array();
    $pettycash    = getPettycashByUid($pettycashUid);
    if($pettycash){
        $response = array(
        "amount"       => $pettycash->amount,
        "dueDate"      => $pettycash->due_date,
        "status"       => $pettycash->status,
        "pettycashUid" => $pettycash->pettycash_uid
    );
    }
    
    echo jsonify($response);
});

# Employee Pettycash Update
$app->post("/employee/pettycash/update/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    
    $pettycashUid = $param[0];
    $amount       = $_POST['amount'];
    $dueDate      = $_POST['dueDate'];
    $status       = $_POST['status'];
    $dateModified = date("Y-m-d H:i:s");

    updatePettyCash($pettycashUid , $amount , $dueDate , $status , $dateModified);
});

# Employee Attendance Get
$app->get("/employee/attendance/get/:var" , function($var){
    //parameter: token
    $param                = explode(".", $var);
    $empUid               = $param[0];
    $token                = $param[1];
    $response             = array();
    $employeeImmigrations = getImmigration($empUid);
    foreach ($employeeImmigrations as $employeeImmigration) {
        $response[] = array(
            "documentType"   => $employeeImmigration->document_type,
            "passportNo"     => $employeeImmigration->passport_no,
            "issuedBy"       => $employeeImmigration->name,
            "issuedDate"     => $employeeImmigration->issued_date,
            "expiryDate"     => $employeeImmigration->expiry_date,
            "immigrationUid" => $employeeImmigration->emp_immigration_uid
        );
    }
    echo jsonify($response);
});

# Employee Immigration New 
$app->post("/employee/immigration/new/:var" , function($var){
    //parameter: term.start.size.token
    $param             = explode(".", $var);
    
    $empUid            = $param[0];
    $empImmigrationUid = xguid();
    $documentType      = $_POST['documentType'];
    $passportNo        = $_POST['passportNo'];
    $issuedDate        = $_POST['issuedDate'];
    $expiryDate        = $_POST['expiryDate'];
    $eligibleStatus    = $_POST['eligibleStatus'];
    $countryUid        = $_POST['countryUid'];
    $reviewDate        = $_POST['reviewDate'];
    $dateCreated       = date("Y-m-d H:i:s");
    $dateModified      = date("Y-m-d H:i:s");

    newImmigration($empImmigrationUid, $documentType, $passportNo, $issuedDate, $expiryDate, $eligibleStatus, $countryUid, $reviewDate, $empUid, $dateCreated, $dateModified);
});

# Immigration Details Get Edit
$app->get("/immigration/details/get/edit/:var" , function($var){
    //parameter: token
    $param          = explode(".", $var);
    $immigrationUid = $param[0];
    $token          = $param[1];
    $response       = array();
    $immigration    = getImmigrationByUid($immigrationUid);
    if($immigration){
        $response = array(
        "documentType"   => $immigration->document_type,
        "passportNo"     => $immigration->passport_no,
        "issuedDate"     => $immigration->issued_date,
        "expiryDate"     => $immigration->expiry_date,
        "eligibleStatus" => $immigration->eligible_status,
        "reviewDate"     => $immigration->review_date,
        "countryName"    => $immigration->name,
        "status"         => $immigration->status,
        "immigrationUid" => $immigration->emp_immigration_uid
    );
    }
    
    echo jsonify($response);
});

# Employee Immigration Update
$app->post("/employee/immigration/update/:var" , function($var){
    //parameter: term.start.size.token
    $param = explode(".", $var);

    $immigrationUid = $param[0];
    $documentType   = $_POST['documentType'];
    $passportNo     = $_POST['passportNo'];
    $issuedDate     = $_POST['issuedDate'];
    $expiryDate     = $_POST['expiryDate'];
    $eligibleStatus = $_POST['eligibleStatus'];
    $countryUid     = $_POST['countryUid'];
    $reviewDate     = $_POST['reviewDate'];
    $status         = $_POST['status'];
    $dateCreated    = date("Y-m-d H:i:s");
    $dateModified   = date("Y-m-d H:i:s");

    updateImmigration($documentType , $passportNo , $issuedDate , $expiryDate , $eligibleStatus , $countryUid , $reviewDate, $status , $dateModified , $immigrationUid);
});

# Employee Allowance New 
$app->post("/employee/allowance/new/:var" , function($var){
    //parameter: term.start.size.token
    $param = explode(".", $var);
    $response = array();
    $verified = 0;
    if(count($param)===1) {
        $empUid          = $_POST['empUid'];
        $meal            = $_POST['meal'];
        $transpo         = $_POST['transpo'];
        $cola            = $_POST['cola'];
        $other           = $_POST['others'];
        $period          = $_POST['period'];

        $verified = verifiyAllowanceByEmpUid($empUid);
        if($verified) {
            $verified = 0;
        }else {
            newAllowance($empUid, $meal, $transpo, $cola, $other, $period);
            $verified = 1;
        }
        
    }   

    $response = array(
        "success" => $verified
    );

    echo jsonify($response); 
});

# Employee Allowance Edit 
$app->post("/employee/allowance/edit/:var" , function($var){
    $param = explode(".", $var);
    $token = $param[0];
    $response = array();
    $verified = 0;
    if(count($param)===1) {
        $uid = $_POST['uid'];
        $meal = $_POST['meal'];
        $transpo = $_POST['transpo'];
        $cola = $_POST['cola'];
        $other = $_POST['others'];
        $period = $_POST['period'];
        
        $verify = verifiyAllowanceByAllowanceUid($uid);
        if($verify) {
            updateAllowanceById($uid, $meal, $transpo, $cola, $other, $period);
            $verified = 1;
        }        
    }

    $response = array(
        "success" => $verified
    );
    echo jsonify($response);
});

# Employee Allowance Get All
$app->get("/employee/allowance/get/all/:var", function($var) {
    $param = explode(".", $var);
    $token = $param[0];
    $response = array();
    $results = read_employee_allowances();
    foreach($results as $result) {
        $total = $result->meal + $result->transportation + $result->cola + $result->other;
        $employee_name = read_employee_lastname_by_uid($result->emp_uid);
        $response[] = array(
            "uid" => $result->allowance_uid,
            "emp_uid" => $result->emp_uid,
            "emp_name" => $employee_name,
            "meal" => $result->meal,
            "transportation" => $result->transportation,
            "cola" => $result->cola,
            "other" => $result->other,
            "period" => $result->period,
            "total" => $total
        );
    }
    echo jsonify($response);
});

# Employee Allowance Get 
$app->get("/employee/allowance/get/:var" , function($var){
    //parameter: token
    $param             = explode(".", $var);
    $empUid            = $param[0];
    $token             = $param[1];
    $response          = array();
    $employeeAllowance = getAllowance($empUid);
    foreach ($employeeAllowance as $allowance) {
        $response[] = array(
            "allowanceUid" => $allowance->allowance_uid,
            "meal"         => $allowance->meal,
            "transpo"      => $allowance->transportation,
            "cola"         => $allowance->COLA,
            "other"        => $allowance->other,
            "date"         => date("M d, Y", strtotime($allowance->date_receive))
        );
    }
    echo jsonify($response);
});

# Disabled Employee Allowance
$app->post("/employee/allowance/delete/:var", function($var) {
    $param = explode(".", $var);
    $uid = $param[0];
    $token = $param[1];
    $response = array();
    $verified = 0;
    if(count($param)===2) {
        deleteAllowanceById($uid);
        $verified = 1;
    }
    $response = array(
        "success" => $verified
    );
    echo jsonify($response);
});

# Allowance Details Get Edit
$app->get("/allowance/details/get/edit/:var" , function($var){
    //parameter: token
    $param = explode(".", $var);
    $allowanceUid = $param[0];
    $token = $param[1];
    $response = array();
    $allowance = getAllowanceByUid($allowanceUid);
    if($allowance){
        $response = array(
            "allowanceUid" => $allowance->allowanceUid,
            "meal" => $allowance->meal,
            "transpo" => $allowance->transportation,
            "cola" => $allowance->cola,
            "other" => $allowance->other,
            "period" => $allowance->period
        );
    }
    echo jsonify($response);
});

# Add Costcenter
$app->post("/add/costcenter/", function(){
    $costUid      = xguid();
    $name         = $_POST["name"];
    $desc         = $_POST["desc"];
    $payperiod    = $_POST["payperiod"];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");
    
    $check        = checkCostCenterIfExists($name);
    if($check){
        $response = array(
            "prompt" => 1
        );
    }else{
        addNewCostCenter($costUid, $name, $desc, $payperiod, $dateCreated, $dateModified);
        $response = array(
            "prompt" => 0
        );
    }
    echo jsonify($response);
});

# Get Costcenter
$app->get("/get/costcenter/", function(){
    $response = array();
    $cost     = getCostcenter();

    foreach($cost as $costcenter){
        $response[] = array(
            "uid"         => $costcenter->cost_center_uid,
            "name"        => $costcenter->cost_center_name,
            "description" => $costcenter->description,
            "payperiod"   => $costcenter->pay_period_name,
            "status"      => $costcenter->status
        );
    }

    echo jsonify($response);
});

# Get Costcenter Data
$app->get("/get/costcenter/data/:uid", function($uid){
    $response = array();
    $data     = getCostcenterDataByUid($uid);

    if($data){
        $response = array(
            "costcenterUid" => $data->cost_center_uid,
            "payperiodUid"  => $data->pay_period_uid,
            "ccName"        => $data->cost_center_name,
            "ccDesc"        => $data->description,
            "status"        => $data->status
        );
    }

    echo jsonify($response);
});

# Update Costcenter 
$app->post("/update/costcenter/:uid", function($uid){
    $name         = $_POST["name"];
    $desc         = $_POST["desc"];
    $payperiod    = $_POST["payperiod"];
    $status       = $_POST["status"];
    $dateModified = date("Y-m-d H:i:s");

    updateCostCenter($uid, $name, $desc, $payperiod, $dateModified, $status);
    // $response = array(
    //     "prompt" => 0
    // );
    var_dump(updateCostCenter($uid, $name, $desc, $payperiod, $dateModified, $status));
    // echo jsonify($response);
});

# Set Emp Costcenter
$app->post("/set/emp/costcenter/", function(){
    $costUid      = xguid();
    $costcenter   = $_POST["costcenter"];
    $empUid       = $_POST["uid"];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");
    
    $check        = countEmpSetCostCenter($empUid);
    if($check){
        $response = array(
            "prompt" => 1
        );
    }else{
        setEmpCostCenter($costUid, $costcenter, $empUid, $dateCreated, $dateModified);
        $response = array(
            "prompt" => 0
        );
    }

    echo jsonify($response);
});

# Employee Update Costcenter
$app->post("/employee/update/costcenter/:uid", function($uid){
    $costcenter   = $_POST["costcenter"];
    $status       = $_POST["status"];
    $dateModified = date("Y-m-d H:i:s");

    updateEmpCostCenter($uid, $costcenter, $dateModified, $status);
});

# Check Costcenter
$app->get("/check/costcenter/:uid", function($uid){
    $check = countEmpSetCostCenter($uid);
    if($check){
        $response = array(
            "prompt" => 1
        );
    }else{
        $response = array(
            "prompt" => 0
        );
    }

    echo jsonify($response);
});

# Check Costcenter Set 
$app->get("/check/costcenter/set/:uid", function($uid){
    $check = countEmpSetCostCenter($uid);
    if($check){
        $response = array(
            "prompt" => 1
        );
    }else{
        setEmpCostCenter($costUid, $costcenter, $empUid, $dateCreated, $dateModified);
        $response = array(
            "prompt" => 0
        );
    }

    echo jsonify($response);
});

# Employee Costcenter Data
$app->get("/employee/costcenter/data/:uid", function($uid){
    $datas    = getEmpCostCenterDataByEmpUid($uid);
    $response = array();

    foreach($datas as $data){
        $response[] = array(
            "empCostUid" => $data->emp_cost_center_uid,
            "costUid"    => $data->cost_center_uid,
            "emp"        => $data->emp_uid,
            "ccName"     => $data->cost_center_name,
            "ccDesc"     => $data->description,
            "status"     => $data->status
        );
    }

    echo jsonify($response);
});

# Employee Costcenter Single Data 
$app->get("/employee/costcenter/single/data/:uid", function($uid){
    $data = getEmpCostCenterDataByUid($uid);

    if($data){
        $response = array(
            "empCostUid" => $data->emp_cost_center_uid,
            "costUid"    => $data->cost_center_uid,
            "emp"        => $data->emp_uid,
            "ccName"     => $data->cost_center_name,
            "ccDesc"     => $data->description,
            "status"     => $data->status
        );
    }

    echo jsonify($response);
});

# Get Employee Costcenter 
$app->get("/get/employee/costcenter/:uid", function($uid){
    $datas    = getEmployeeByCostCenterUid($uid);
    $response = array();

    foreach($datas as $data){
        $response[] = array(
            "empUid"     => $data->emp_uid,
            "firstname"  => utf8_decode($data->firstname),
            "middlename" => utf8_decode($data->middlename),
            "lastname"   => utf8_decode($data->lastname),
            "empNo"      => $data->username
        );
    }

    echo jsonify($response);
});
//------------------------------------------------TIMESHEET------------------------------------------------------//
# Employee Timesheet Get 
$app->post("/employee/timesheet/get/:uid", function($uid){
    $startDate = date("Y-m-d", strtotime($_POST["startDate"]));
    $endDate   = date("Y-m-d", strtotime($_POST["endDate"]));

    getAllTimeSheetByEmpUidAndDateRange($uid, $startDate, $endDate);

});

# Employee Timesheet Details 
$app->get("/employee/timesheet/details/:var", function($var){
    $param     = explode(".", $var);
    $uid       = $param[0];
    $startDate = date("Y-m-d", strtotime($param[1]));
    $endDate   = date("Y-m-d", strtotime($param[2]));
    $response  = array();
    
    $scheds    = getAllTimeSheetByEmpUidAndDateRange($uid, $startDate, $endDate);
    foreach($scheds as $sched) {
        $type = $sched->type;
        if($type == 0){
            $types = "IN";
        }else{
            $types = "OUT";
        }//end of if-else
        $response[] = array(
            "date" => date('F d, Y', strtotime($sched->date_created)),
            "time" => date('g:i A', strtotime($sched->date_created)),
            "type" => $types
        );
    }
    echo jsonify($response);

});

# Get Timein Data 
$app->get("/get/timein/data/:uid", function($uid){
    $check = checkTimeInDataByUid($uid);

    if($check){
        $timeIn = getTimeInDataByUid($uid);

        $response = array(
            "timeIn" => date("Y-m-d h:i A",strtotime($timeIn->date_created)),
            "shift"  => $timeIn->shift_uid,
            "status" => $timeIn->status
        );
    }else{
        $response = array(
            "timeIn" => "",
            "shift"  => "",
            "status" => 1
        );
    }
    

    echo jsonify($response);
});

# Get Timeout Data 
$app->get("/get/timeout/data/:uid", function($uid){
    $timeOut = getTimeInDataByUid($uid);

    $response = array(
        "timeOut" => date("Y-m-d h:i A",strtotime($timeOut->date_created)),
        "shift"   => $timeOut->shift_uid
    );

    echo jsonify($response);
});

# Edit Time In Data 
$app->post("/edit/time/in/data/:in", function($in){
    $inUid   = $in;
    $timeIn  = $_POST["timeIn"];
    $empUids = $_POST["empUid"];
    
    $status  = $_POST["status"];
    // $timeInDate = date("Y-m-d", strtotime($timeIn));
    // $timeInHour = date("H:i:s", strtotime($timeIn));
    $shift        = $_POST["shift"];
    $dateModified = date("Y-m-d H:i:s");
    $day          = date("N", strtotime($timeIn));
    $timeIn       = date("Y-m-d H:i",  strtotime($timeIn));

    if(strlen($inUid) <= 1){
        $session = xguid();
        addTimeIn(xguid(), $empUids, $shift, $session, $timeIn, $dateModified);
    }else{
        updateTimeIn($inUid, $timeIn, $shift ,$dateModified, $status);
    }
});

# Edit Time Out Data 
$app->post("/edit/time/out/data/:var", function($var){
    // $inUid = $in;
    $params        = explode(".", $var);
    $inUid         = $params[0];
    $outUid        = $params[1];
    $timeOut       = $_POST["timeOut"];
    $empUids       = $_POST["empUid"];
    
    $status        = $_POST["status"];
    // $timeInDate = date("Y-m-d", strtotime($timeIn));
    // $timeInHour = date("H:i:s", strtotime($timeIn));
    $shift         = $_POST["shift"];
    $dateModified  = date("Y-m-d H:i:s");
    $day           = date("N", strtotime($timeOut));
    $in            = getTimeInDataByUid($inUid);
    $session       = $in["session"];
    $location      = $in["location_uid"];
    $timeOut       = date("Y-m-d H:i",  strtotime($timeOut));

    if($outUid == "No Time Out!"){
        addTimeOut(xguid(), $empUids, $shift, $session, $timeOut, $location, $dateModified);
    }else{
        updateTimeIn($outUid, $timeOut, $shift ,$dateModified, $status);
    }
});

# Edit Time Data 
$app->post("/edit/time/data/:var", function($var){
    $param        = explode(".", $var);
    $inUid        = $param[0];
    $outUid       = $param[1];
    
    $timeIn       = $_POST["timeIn"];
    $status       = $_POST["status"];
    
    $timeInDate   = date("Y-m-d", strtotime($timeIn));
    $timeInHour   = date("H:i", strtotime($timeIn));
    $timeOut      = $_POST["timeOut"];
    $timeOutDate  = date("Y-m-d", strtotime($timeOut));
    $timeOutHour  = date("H:i", strtotime($timeOut));
    $shift        = $_POST["shift"];
    $dateModified = date("Y-m-d H:i:s");
    $day          = date("N", strtotime($timeIn));
    
    $timeIn       = date("Y-m-d H:i", strtotime($timeIn));
    $timeOut      = date("Y-m-d H:i", strtotime($timeOut));

    // $getRule = getRuleByRuleUid($rule, $day);

    // if($getRule){
    //     $shift = $getRule->shift_uid;
    // }

    $in            = getTimeInDataByUid($inUid);
    $inDateCreated = $in["date_modified"];
    $inDate        = date("Y-m-d", strtotime($inDateCreated));
    $inHour        = date("H:i", strtotime($inDateCreated));
    $empUid        = $in["emp_uid"];

    $out            = getTimeInDataByUid($outUid);
    $outDateCreated = $out->date_modified;
    $outDate        = date("Y-m-d", strtotime($outDateCreated));
    $outHour        = date("H:i", strtotime($outDateCreated));
    
    $empNumber      = getEmloyeeNumberByEmpUid($empUid);
    $checkIn        = getOtherTimeInData($empNumber, $inDate, $inHour);
    $checkOut       = getOtherTimeOutData($empNumber, $outDate, $outHour);
    // editOtherTimeIn($empNumber, $inDate, $inHour, $timeInDate, $timeInHour);
    // editOtherTimeOut($empNumber, $outDate, $outHour, $timeOutDate, $timeOutHour);
    // editEventLogTimeIn($empUid, $inDate, $inHour, $timeInDate, $timeInHour);
    // editEventLogTimeOut($empUid, $outDate, $outHour, $timeOutDate, $timeOutHour);
    updateTimeIn($inUid, $timeIn, $shift ,$dateModified, $status);
    updateTimeOut($outUid, $timeOut, $shift ,$dateModified, $status);
});

# Delete Time Data 
$app->post("/delete/time/data/:var", function($var){
    $param  = explode(".", $var);
    $inUid  = $param[0];
    $outUid = $param[1];

    deleteTimeLogByUid($inUid);
    deleteTimeLogByUid($outUid);
});

# Timesheet View All Emp 
$app->get("/timesheet/view/all/emp/", function(){
    $response = array();
    $emp      = getActiveMonthlyEmployees();
    foreach ($emp as $emps) {
        $response[] = array(
            "uid"       => $emps->emp_uid,
            "lastname"  => utf8_decode($emps->lastname),
            "firstname" => utf8_decode($emps->firstname)
        );
    }
    echo jsonify($response);
});

# Emp Period 
$app->get("/emp/period/:frequencyUid", function($frequencyUid){
    $frequencyUid = $frequencyUid;
    $response     = array();
    $a            = getEmployeesByFrequencyUid($frequencyUid);
    foreach($a as $emp){
        $response[] = array(
            "uid"       => $emp->emp_uid,
            "lastname"  => utf8_decode($emp->lastname),
            "firstname" => utf8_decode($emp->firstname)
        );
    }

    echo jsonify($response);
});

# Timesheet View All 
$app->get("/timesheet/view/all/", function(){
    $response = array();
    $emp      = getActiveMonthlyEmployees();
    foreach ($emp as $emps) {
        $response[] = array(
            "uid"       => $emps->emp_uid,
            "lastname"  => utf8_decode($emps->lastname),
            "firstname" => utf8_decode($emps->firstname)
        );
    }
    echo jsonify($response);
});

# Timesheet All Daily 
$app->post("/timesheet/all/daily/", function(){
    // $emp = $_POST["employee"];
    $startDateStr = $_POST["startDate"];
    $endDateStr   = $_POST["endDate"];

    getEmpTimesheet($emp,$startDateStr,$endDateStr);
});

# Timesheet All Daily Attendance 
$app->get("/timesheet/all/daily/attendance/:var", function($var){
    $param     = explode(".", $var);
    $emp       = $param[0];
    $startDate = date('Y-m-d', strtotime($param[1]));
    $endDate   = date('Y-m-d', strtotime($param[2]));
    
    $x         = getEmpTimesheet($emp,$startDate,$endDate);
    echo jsonify($x);
});

# Timesheet All View Attendance 
$app->get("/timesheet/all/view/attendance/:var", function($var){
    $param     = explode(".", $var);
    // $emp    = $param[0];
    $startDate = date('Y-m-d', strtotime($param[0]));
    $endDate   = date('Y-m-d', strtotime($param[1]));
    $id        = $param[2];
    
    $x         = generateTimesheetByEmpUid($startDate, $endDate, $id);
    echo jsonify($x);

    // echo jsonify($x["totalNumDay"]);
});

# Timesheet View Summary 
$app->get("/timesheet/view/summary/:var", function($var){
    $param     = explode(".", $var);
    $startDate = date('Y-m-d', strtotime($param[0]));//strtotime returns seconds of the string since jan 1 1970
    $endDate   = date('Y-m-d', strtotime($param[1]));//simply returns the date in format Y-m-d
    
    $cost      = $param[2];
    
    $x         = timeOrganizedSummary($startDate, $endDate, $cost);
    echo jsonify($x);
});

//------------------------------------------------LOCATION------------------------------------------------------//
# Get Specs Location Data 
$app->get("/get/spes/location/data/:var", function($var){
    $param     = explode(".", $var);
    $long      = $param[0];
    $lat       = $param[1];
    
    $locations = getLocationByCoords($long, $lat);
    if($location){
        $response = array(
            "locUid"       => $locations->uid,
            "name"         => $locations->name,
            "locLongitude" => $locations->longitude,
            "locLatitude"  => $locations->latitude,
            "status"       => $locations->status
        );
    }

    echo jsonify($response);
});

# Location New 
$app->post("/locations/new/", function(){
    $locUid      = xguid();
    $name        = $_POST["name"];
    $fingerprint = $_POST["fingerprint"];

    // $locLongitude = number_format($locLongitude, 5);
    // $locLatitude = number_format($locLatitude, 5);

    $response = array();
    
    $check    = checkLocationExisting($name, $fingerprint);
    if($check >= 1){
        $response = array(
            "error"        => 1,
            "errorMessage" => "Location Existing!"
        );
    }else{
        addLocations($locUid, $name, $fingerprint);
        $response = array(
            "error"        => 0,
            "errorMessage" => "Successfully Added!"
        );
    }
    echo jsonify($response);
});

# Location Single Data 
$app->get("/location/single/data/:uid", function($uid){
    $response  = array();
    $locations = getLocationsByUid($uid);

    $response = array(
        "locUid"      => $locations->uid,
        "name"        => $locations->name,
        "fingerprint" => $locations->fingerprint,
        "status"      => $locations->status
    );

    echo jsonify($response);
});

# Locations Edit Data 
$app->post("/locations/edit/data/:uid", function($uid){
    $response    = array();
    $name        = $_POST["name"];
    $fingerprint = $_POST["fingerprint"];
    $status      = $_POST["status"];

    // $check = checkLocationExisting($name, $locLongitude, $locLatitude);
    // if($check >= 1){
    //     $response = array(
    //         "error" => 1,
    //         "errorMessage" => "Location Existing!"
    //     );
    // }else{
        editLocations($uid, $name, $fingerprint, $status);
        $response = array(
            "error"        => 0,
            "errorMessage" => "Successfully Edited!"
        );
    // }    
    echo jsonify($response);
});
//------------------------------------------------LOCATION END------------------------------------------------------//

//------------------------------------------------TIMESHEET END------------------------------------------------------//
//------------------------------------------------RULES SETTINGS------------------------------------------------------//
# Get Rules Number 
$app->get("/get/rules/number/", function() {
    $response = array();
    
    $rule     = getRuless();
    foreach($rule as $rules){
        $shift      = $rules->name . ": (" . date("h:i A", strtotime($rules->start)) . " - " . date("h:i A", strtotime($rules->end)) .")";
        $response[] = array(
            "ruleUid"  => $rules->rule_uid,
            "ruleName" => $rules->rule_name,
            "day"      => $rules->day,
            "shift"    => $shift,
            "shiftUid" => $rules->shift_uid
        );
    }

    echo jsonify($response);
});

# Get Shifts
$app->get("/get/shifts/", function(){
    $response = array();
    $shifts   = getPaginatedShift();
    foreach($shifts as $shift){
        $response[] = array(
            "shiftUid"    => $shift->shift_uid,
            "shiftName"   => $shift->name,
            "shiftStart"  => $shift->start,
            "shiftEnd"    => $shift->end,
            "gracePeriod" => $shift->grace_period,
        );
    }

    echo jsonify($response);
});

# Get Rules Id 
$app->get("/get/rules/id/:uid", function($uid){
    $response = array();
    $rule     = getRuleByUid($uid);
    foreach($rule as $rules){
        $shift = $rules->name . ": (" . date("h:i A", strtotime($rules->start)) . " - " . date("h:i A", strtotime($rules->end)) .")";

        $response[] = array(
            "ruleUid"  => $rules->rule_uid,
            "ruleName" => $rules->rule_name,
            "day"      => $rules->day,
            "shift"    => $shift
        );
    }
    echo jsonify($response);
});

# Get Rule Data 
$app->get("/get/rule/data/:var", function($var){
    $param = explode(".", $var);
    $uid   = $param[0];
    $day   = $param[1];

    $rules = getRuleByUidAndDay($uid, $day);
    if($rules){
        $shift    = $rules->name . ": (" . date("h:i A", strtotime($rules->start)) . " - " . date("h:i A", strtotime($rules->end)) .")";
        $response = array(
            "ruleUid"  => $rules->rule_uid,
            "ruleName" => $rules->rule_name,
            "day"      => $rules->day,
            "shiftUid" => $rules->shift_uid,
            "shift"    => $shift,
            "status"   => $rules->status
        );
    }
    echo jsonify($response);
});

# Rule Update 
$app->post("/rule/update/", function(){
    $ruleUid      = $_POST["rule"];
    $day          = $_POST["day"];
    $shift        = $_POST["shift"];
    $status       = $_POST["status"];
    $dateModified = date("Y-m-d H:i:s");

    updateRules($ruleUid, $day, $shift, $dateModified, $status);
});

# Emp Rules Data 
$app->get("/emp/rules/data/:uid", function($uid){
    $response = array();
    $rules    = getRuleByEmpUid($uid);
    if($rules) {
        $response[] = array(
            "ruleUid"  => $rules->rule_assignment_uid,
            "ruleName" => $rules->rule_name,
            "status"   => $rules->status
        );
    }
    echo jsonify($response);
});

# Employee Rules Data 
$app->get("/employee/rules/data/:uid", function($uid){
    $response = array();
    $rules    = getRulesByUid($uid);
    if($rules){
        $response = array(
            "assignRuleUid" => $rules->rule_assignment_uid,
            "ruleUid"       => $rules->rule_uid,
            "status"        => $rules->status
        );
    }

    echo jsonify($response);
});

# Get Time Rule 
$app->get("/get/time/rule/:uid", function($uid){
    $timeLogUid = $uid;
    $response   = array();
    $rule       = getRuleShiftByTimeLogUid($timeLogUid);
    if($rule){
        $response = array(
            "shiftUid" => $rule->shiftUid
        );
    }else{
        $response = array(
            "shiftUid" => "0"
        );
    }
    echo jsonify($response);
});

# Employee Rules New 
$app->post("/employee/rules/new/:uid", function($uid){
    $ruleUid      = xguid();
    $rule         = $_POST["rule"];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");
    $response     = array();
    
    $count        = countRuleByEmpUid($uid);
    if($count >= 1){
        $errorMessage = "1 Rule Only";
        $response     = array(
            "errorStatus"  => 1,
            "errorMessage" => $errorMessage
        );
    }else{
        newEmpRule($ruleUid, $rule, $uid, $dateCreated, $dateModified); 
        $response = array(
            "errorStatus"  => 0,
            "errorMessage" => "asd" 
        );
    }

    echo jsonify($response);
});

$app->get("/check/rules/:id", function($id){
    $response = array();
    $count    = countRuleByEmpUid($id);
    if($count >= 1){
        $errorMessage = "1 Rule Only";
        $response     = array(
            "errorStatus"  => 1,
            "errorMessage" => $errorMessage
        );
    }else{
        $response = array(
            "errorStatus"  => 0,
            "errorMessage" => "asd" 
        );
    }

    echo jsonify($response);
});

$app->post("/employee/rules/update/:uid", function($uid){
    $rule         = $_POST["rule"];
    $status       = $_POST["status"];
    $dateModified = date("Y-m-d H:i:s");

    updateRuleAssignment($uid, $rule, $dateModified, $status);
});
//------------------------------------------------RULES SETTINGS END------------------------------------------------------//

//------------------------------------------------OVERTIME SETTINGS------------------------------------------------------//
$app->get("/rate/get/data/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $rate     = getHolidayType();
    foreach($rate as $rates){
        $response[] = array(
            "rateUid" =>$rates->holiday_type_uid,
            "name"    =>$rates->holiday_name_type,
            "code"    =>$rates->holiday_code,
            "rate"    =>$rates->rate
        );
    }

    echo jsonify($response);
});

$app->post("/rate/new/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[0];
    
    $rateUid      = xguid();
    $code         = $_POST["code"];
    $rate         = $_POST["rate"];
    $name         = $_POST["name"];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    addRate($rateUid, $code , $name , $rate , $dateCreated , $dateModified);
});

$app->get("/rate/details/get/:var" , function($var){
    //parameter: term.start.size.token
    $param   = explode(".", $var);
    $token   = $param[1];
    $rateUid = $param[0];
    $rate    = getRatesByUid($rateUid);
    if($rate){

        $response = array(
            "rateUid" => $rate->holiday_type_uid,
            "code"    => $rate->holiday_code,
            "name"    => $rate->holiday_name_type,
            "rate"    => $rate->rate,
            "status"  => $rate->status 
        );
    }else{
        $response = array(
            "rateUid" => "",
            "code"    => "",
            "name"    => "",
            "rate"    => "",
            "status"  => ""
        );
    }

    echo jsonify($response);
});

$app->post("/rate/update/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[1];
    
    $rateUid      = $param[0];
    $code         = $_POST['code'];
    $name         = $_POST['name'];
    $rate         = $_POST['rate'];
    $dateModified = date("Y-m-d H:i:s");
    $status       = $_POST['status'];
    
    $rates        = getRatesByUid($rateUid);

    if($name != $rates->holiday_name_type OR $code != $rates->holiday_code OR $rates != $rates->rate OR $status != $rates->status){
        updateRateById($rateUid, $code, $name , $rate, $dateModified , $status);
        if(rateCount($name)>1){
            updateRateById($rateUid, $rates->holiday_code ,$rates->holiday_name_type, $rates->rate, $dateModified , $status);
        }
    }
});
//------------------------------------------------OVERTIME SETTINGS END------------------------------------------------------//

//------------------------------------------------LOANS--------------------------------------------------------------//

$app->get("/loans/get/data/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $loan     = getLoansDetails();
    foreach($loan as $loans){
        $response[] = array(
            "loanUid"      => $loans->loan_uid,
            "loanName"     => $loans->name,
            "loanInterest" => $loans->interest
        );
    }

    echo jsonify($response);
});

$app->post("/loans/new/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[0];
    
    $loansUid     = xguid();
    $loanName     = $_POST['loanName'];
    $loanInterest = $_POST['loanInterest'];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    addLoans($loansUid , $loanName, $loanInterest , $dateCreated , $dateModified);
});

$app->get("/loans/details/get/:var" , function($var){
    //parameter: term.start.size.token
    $param   = explode(".", $var);
    $token   = $param[1];
    
    $loanUid = $param[0];
    
    $loan    = getLoanByUid($loanUid);
    if($loan){

        $response = array(
            "loanUid"      => $loan->loan_uid,
            "loanName"     => $loan->name,
            "loanInterest" => $loan->interest,
            "status"       => $loan->status 
        );
    }else{
        $response = array(
            "loanUid"      => "",
            "loanName"     => "",
            "loanInterest" => "",
            "status"       => ""
        );
    }

    echo jsonify($response);
});

$app->post("/loans/update/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[1];
    
    $loansUid     = $param[0];
    $loanName     = $_POST['loanName'];
    $loanInterest = $_POST['loanInterest'];
    $dateModified = date("Y-m-d H:i:s");
    $status       = $_POST['status'];
    
    $loans        = getLoanByUid($loansUid);

    if($loanName != $loans->name OR $loanInterest != $loans->interest OR $status != $loans->status){
        updateLoansById($loansUid , $loanName , $loanInterest , $dateModified , $status);
        if(loanCount($loanName)>1){
            updateLoansById($loansUid , $loans->name , $loans->interest , $dateModified , $status);
        }
    }
});

$app->get("/loan/type/get/", function(){
    $response = array();
    $loan     = getLoansDetails();
    foreach($loan as $loans){
        $response[] = array(
            "loanUid"      => $loans->loan_uid,
            "loanName"     => $loans->name,
            "loanInterest" => $loans->interest
        );
    }

    echo jsonify($response);
});

$app->get("/employee/loans/get/:var" , function($var){
    //parameter: token
    $param    = explode(".", $var);
    $empUid   = $param[0];
    $token    = $param[1];
    $response = array();
    $empLoans = getLoansByEmpUid($empUid);
    foreach ($empLoans as $loans) {
        $response[] = array(
            "loanName"     => $loans->name,
            "loanInterest" => $loans->interest,
            "loanAmount"   => $loans->amount,
            "loanUid"      => $loans->emp_loans_uid
        );
    }
    echo jsonify($response);
});

$app->post("/loans/edit/:uid", function($uid){
    $loanType     = $_POST["loanName"];
    $loanAmount   = $_POST["loanAmount"];
    $status       = $_POST["status"];
    $dateModified = date("Y-m-d H:i:s");

    updateEmpLoan($uid, $loanType, $loanAmount, $dateModified, $status);
});

$app->get("/employee/loans/data/:var" , function($var){
    $param    = explode(".", $var);
    $uid      = $param[0];
    $token    = $param[1];
    $response = array();
    $loans    = getEmpLoanByUid($uid);
    // print_r($loans);
    if($loans) {
        $response = array(
            "status"     => $loans["status"],
            "loanType"   => $loans["loan_uid"],
            "loanAmount" => $loans["amount"],
            "loanUid"    => $loans["emp_loans_uid"]
        );
    }
    echo jsonify($response);
});

$app->post("/loan/type/new/:var" , function($var){
    //parameter: term.start.size.token
    $param             = explode(".", $var);
    
    $loanDeductionsUid = xguid();
    $empUid            = $param[0];
    $loanType          = $_POST['loanType'];
    $loanAmount        = $_POST['loanAmount'];
    $dateCreated       = date("Y-m-d H:i:s");
    $dateModified      = date("Y-m-d H:i:s");

    addEmpLoans($loanDeductionsUid, $empUid , $loanType, $loanAmount , $dateCreated , $dateModified);
});

$app->get("/loans/details/get/edit/:var" , function($var){
    //parameter: token
    $param    = explode(".", $var);
    $loanUid  = $param[0];
    $response = array();
    $loan     = getLoanDeductionByUid($loanUid);
    if($loan){
        $response = array(
        "loanName"          => $loan->name,
        "loanAmount"        => $loan->amount,
        "status"            => $loan->status,
        "loanDeductionsUid" => $loan->loan_deductions_uid
    );
    }
    
    echo jsonify($response);
});
//------------------------------------------------END OF LOANS--------------------------------------------------------------//

//------------------------------------------------USERS AUTH------------------------------------------------------//

$app->post("/users/verified", function() {
	$uid = $_POST["username"];
	if($uid) {
		$userId  = getUserId($uid);
		if($userId) {
			$response = array(
				"verified" => 1
			);
		}
		else {
			$response = array(
				"verified" => 0
			);
		}
	}
	else{
		$response = array(
			"verified" => 0
		);
	}
	echo jsonify($response);
});

$app->post("/users/authenticate", function () {
    $username = $_POST["username"];
    $userId   = getUserId($username);
    
    $secretKey = $_POST['secret'];
    $GResponse = $_POST['gresponse'];
    $g_response_success = false;

    $uxPassword = null;
	if(!$userId){
        $response = array(
            "verified" => 0
        );
    }else{
        $encryption = "AES-256-CBC";
        //$uxPassword = sha1(Base32::decode($_POST["password"]));
		$uxPassword = sha1(base64_decode($_POST["password"].salt()));
		//$uxPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);
		

        // $url = "https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$GResponse; //echo $url;
        // $verify = file_get_contents($url);
        // $result = json_decode($verify); //var_dump($result);
        // if($result->success==true) {            
        //     $g_response_success = true; //echo "Success!";
        // }else {            
        //     $g_response_success = false; //echo "You are a robot!";
        // }        
        $g_response_success = true; ### Edit this

        if($g_response_success) {        
            $secretKey  = sha1($username . $uxPassword);
            $uniqueKey  = getUniqueKey($userId);
            if ($uniqueKey == null) {
                $ivSize    = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
                $uniqueKey = mcrypt_create_iv($ivSize, MCRYPT_RAND);
            }
            $password = openssl_encrypt($uxPassword, $encryption, $secretKey, 0, $uniqueKey);
            $response = array(
                "Uid"        => null,
                "Token"      => null,
                "UserId"     => null,
                "EmployeeId" => null,
                "Type"       => null,
                "Username"   => null,
                "location"   => null,
                "verified"   => 0
            );
            // echo $_POST["password"]." = ". Base32::decode($_POST["password"]) ."<br/>";
               
    		if(validUserAccount($username, $uxPassword)) {
    				//$response["Type"]       = getUserType($username);
                    //$response["UserId"]     = $userId;
                    //$response["EmployeeId"] = getUserByUid($userId)->emp_uid;            
                    //$response["Uid"]        = xguid();
                    //$response["Username"]   = getUserByUid($userId)->emp_uid;
                    //$response["Token"]      = xguid();
                    //$response["verified"]   = 1;
                    //$response["location"]   = getCurrentIpAddress($userId);
                    //logToken(xguid(), $response["Token"], $userId, date("Y-m-d H:i:s"), 1);
                    //logTokenReferrer(xguid(), $response["Token"], $_SERVER['REMOTE_ADDR'], date("Y-m-d H:i:s"));
    				
    				$token = xguid();
    				
    				$response = array(
    					"Type" => getUserType($username),
    					"UserId" => $userId,
    					"EmployeeId" =>  getUserByUid($userId)->emp_uid,
    					"Uid" => xguid(),
    					"Token" => $token,
    					"verified" => 1,
    					"location" => getCurrentIpAddress($userId),
    					"Username" => getUserByUid($userId)->emp_uid //$userId
    				);
    				
    				logToken(xguid(), $token, $userId, date("Y-m-d H:i:s"), 1);
                    logTokenReferrer(xguid(), $token, $_SERVER['REMOTE_ADDR'], date("Y-m-d H:i:s"));
            }
    		else {
                $response["verified"] = 2; //$uxPassword;
            }
        }else{
            $response = array(
                "verified" => 3,
                "response" => $g_response_success,
                "secretKey" => $secretKey,
                "gresponse" => $result
            );
        }
    }
    
    echo jsonify($response);

    // deactivateUserTokens($userId);
});

$app->post("/system/tokens/verify", function() {
    $token    = $_POST["token"];
    $user     = getUserFromToken($token);
    $emp      = getEmpUidByUserId($user);
    $location = getCurrentIpAddress($user);
    $type     = getEmployeeType($emp);
    $verified = 0;
    if (validToken($token)) {
        $verified = 1;
    }
    $response = array(
        "verified" => $verified,
        "location" => $location,
        "type"     => $type,
		"token" => $token
    );
    echo jsonify($response);
});

$app->post("/system/tokens/deactivate/:var", function($var) {
    $param = explode(".", $var);
    if (validToken($token) && count($param) === 1) {
        $token = $_POST["token"];
        $user  = getUserFromToken($token);
        deactivateUserTokens($user);
    }
});

$app->post("/users/logout/:var", function($var) {
    $param = explode(".", $var);
    $token = $param[0];
    if (validToken($token) && count($param) === 1) {
        $userId = $_POST["user"];
        deactivateUserTokens($token);
    }
});

/*---------------------------------Late Level--------------------------------------*/
$app->get("/late/get/:var", function($var){
    $param    = explode(".", $var);
    $token    = $param[0];
    $response = array();

    $a = getLates();
    foreach($a as $late){
        $response[] = array(
            "name"     => $late->name,
            "duration" => $late->duration,
            "lateUid"  => $late->late_uid
        );
    }//end of getLates function

    echo jsonify($response);
});

$app->post("/late/new/:var", function($var){
    $param        = explode(".", $var);
    $token        = $param[0];
    
    $lateUid      = xguid();
    $name         = $_POST["name"];
    $duration     = $_POST["duration"];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    newLate($lateUid, $name, $duration, $dateCreated, $dateModified);
});

$app->get("/late/details/get/:var", function($var){
    $param    = explode(".", $var);
    $token    = $param[1];
    
    $uid      = $param[0];
    $response = array();
    
    $a        = getLateByUid($uid);
    if($a){
        $response = array(
            "name"     => $a->name,
            "duration" => $a->duration,
            "status"   => $a->status
        );
    }//end of getLateByUid Function

    echo jsonify($response);
});

$app->post("/late/update/:var", function($var){
    $param        = explode(".", $var);
    $uid          = $param[0];
    $token        = $param[1];
    
    $name         = $_POST["name"];
    $duration     = $_POST["duration"];
    $status       = $_POST["status"];
    $dateModified = date("Y-m-d H:i:s");

    updateLate($uid, $name, $duration, $dateModified, $status);
});
/*---------------------------------Late Level End--------------------------------------*/

/*---------------------------------Degree Level--------------------------------------*/

$app->get("/degree/level/get/:var" , function($var){
    //parameter: term.start.size.token
    $param    = explode(".", $var);
    $response = array();

    $degreeLevels = getPaginatedEducationLevel();
    
    foreach ($degreeLevels as $degreeLevel) {
        $response[] = array(
            "educationLevelUid" => $degreeLevel->education_level_uid,
            "name"              => $degreeLevel->level_name
        );
    }
    echo jsonify($response);
});

$app->post("/degree/level/new/:var" , function($var){
    //parameter: term.start.size.token
    $param          = explode(".", $var);
    $token          = $param[0];
    
    $degreeLevelUid = xguid();
    $name           = $_POST['name'];
    $dateCreated    = date("Y-m-d H:i:s");
    $dateModified   = date("Y-m-d H:i:s");

    newDegreeLevel($degreeLevelUid , $name , $dateCreated , $dateModified);
});

$app->get("/degree/level/details/get/:var" , function($var){
    //parameter: term.start.size.token
    $param    = explode(".", $var);
    $token    = $param[1];
    
    $uid      = $param[0];
    $response = array();

    $degreeLevel = getDegreeLevelByUid($uid);
    if($degreeLevel){
        $response = array(
            "name"   => $degreeLevel->level_name,
            "status" => $degreeLevel->status
        );
    }

    echo jsonify($response);
});

$app->post("/degree/level/update/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $uid          = $param[0];
    $token        = $param[1];
    
    
    $name         = $_POST['name'];
    $status       = $_POST['status'];
    $dateModified = date("Y-m-d H:i:s");

    $degreeLevel = getDegreeLevelByUid($uid);
    if($degreeLevel->level_name != $name OR $degreeLevel->status != $status){
        updateDegreeLevel($uid , $name , $dateModified , $status);
        if(degreeLevelCount($name) > 1){
            updateDegreeLevel($degreeLevel->education_level_uid , $degreeLevel->level_name , $dateModified , $degreeLevel->status);
        }
    }
});

/*---------------------------------Degree Level End--------------------------------------*/

/*---------------------------------Degree Level--------------------------------------*/

$app->get("/skill/type/get/:var" , function($var){
    //parameter: term.start.size.token
    $param      = explode(".", $var);
    $response   = array();
    
    $skillTypes = getPaginatedSkillType();
    
    foreach ($skillTypes as $skillType) {
        $response[] = array(
            "skillUid" => $skillType->skill_uid,
            "name"     => $skillType->skill_type
        );
    }
    echo jsonify($response);
});

$app->post("/skill/type/new/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[0];
    
    $skillUid     = xguid();
    $name         = $_POST['name'];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    newSkillType($skillUid , $name , $dateCreated , $dateModified);
});

$app->get("/skill/type/details/get/:var" , function($var){
    //parameter: term.start.size.token
    $param    = explode(".", $var);
    $token    = $param[1];
    
    $uid      = $param[0];
    $response = array();
    
    $skill    = getSkillTypeByUid($uid);
    if($skill){
        $response = array(
            "name"   => $skill->skill_type,
            "status" => $skill->status
        );
    }

    echo jsonify($response);
});

$app->post("/skill/type/update/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $uid          = $param[0];
    $token        = $param[1];
    
    
    $name         = $_POST['name'];
    $status       = $_POST['status'];
    $dateModified = date("Y-m-d H:i:s");
    
    $skill        = getSkillTypeByUid($uid);
    if($skill->skill_type != $name OR $skill->status != $status){
        updateSkillType($uid , $name , $dateModified , $status);
        if(skillTypeCount($name) > 1){
            updateSkillType($skill->skill_uid , $skill->skill_type , $dateModified , $skill->status);
        }
    }
});

/*---------------------------------Degree Level End--------------------------------------*/

/*---------------------------------Languages--------------------------------------*/

$app->get("/languages/get/:var" , function($var){
    //parameter: term.start.size.token
    $param     = explode(".", $var);
    $response  = array();
    
    $languages = getPaginatedLanguages();
    
    foreach ($languages as $language) {
        $response[] = array(
            "languageUid" => $language->languages_uid,
            "name"        => $language->language_name
        );
    }
    echo jsonify($response);
});

$app->post("/language/new/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[0];
    
    $uid          = xguid();
    $name         = $_POST['name'];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    newLanguage($uid , $name , $dateCreated , $dateModified);
});

$app->get("/language/get/:var" , function($var){
    //parameter: term.start.size.token
    $param    = explode(".", $var);
    $token    = $param[1];
    
    $uid      = $param[0];
    $response = array();
    
    $language = getLanguageByUid($uid);
    if($language){
        $response = array(
            "name"   => $language->language_name,
            "status" => $language->status
        );
    }

    echo jsonify($response);
});

$app->post("/language/update/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $uid          = $param[0];
    $token        = $param[1];
    
    
    $name         = $_POST['name'];
    $status       = $_POST['status'];
    $dateModified = date("Y-m-d H:i:s");

    $language = getLanguageByUid($uid);
    if($language->language_name != $name OR $language->status != $status){
        updateLanguage($uid , $name , $dateModified , $status);
        if(languagesCount($name) > 1){
            updateLanguage($language->languages_uid , $language->language_name , $dateModified , $language->status);
        }
    }
});

/*---------------------------------Languages End--------------------------------------*/
/*---------------------------------Licenses--------------------------------------*/

$app->get("/license/type/get/:var" , function($var){
    //parameter: term.start.size.token
    $param    = explode(".", $var);
    $response = array();
    
    $licenses = getPaginatedLicensesType();
    
    foreach ($licenses as $license) {
        $response[] = array(
            "licenseUid" => $license->license_uid,
            "name"       => $license->license_name
        );
    }
    echo jsonify($response);
});

$app->post("/license/type/new/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[0];
    
    $uid          = xguid();
    $name         = $_POST['name'];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    newLicenseType($uid , $name , $dateCreated , $dateModified);
});

$app->get("/license/type/details/get/:var" , function($var){
    //parameter: term.start.size.token
    $param    = explode(".", $var);
    $token    = $param[1];
    
    $uid      = $param[0];
    $response = array();
    
    $license  = getLicenseTypeByUid($uid);
    if($license){
        $response = array(
            "name"   => $license->license_name,
            "status" => $license->status
        );
    }

    echo jsonify($response);
});

$app->post("/license/type/update/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $uid          = $param[0];
    $token        = $param[1];
    
    
    $name         = $_POST['name'];
    $status       = $_POST['status'];
    $dateModified = date("Y-m-d H:i:s");
    
    $license      = getLicenseTypeByUid($uid);
    if($license->license_name != $name OR $license->status != $status){
        updateLicenseType($uid , $name , $dateModified , $status);
        if(licenseTypeCount($name) > 1){
            updateLicenseType($license->license_uid , $license->license_name , $dateModified , $license->status);
        }
    }
});

/*---------------------------------Licenses End--------------------------------------*/

/*---------------------------------SSS--------------------------------------*/
$app->get("/sss/get/", function(){
    $response = array();
    $sss      = getSSS();
    foreach ($sss as $getsss) {
        $total = 0;
        $sss_total = 0;
        $wsip = 0;
        $sss_total = $getsss->sssTotal + $getsss->ec;
        $wsip = $getsss->mpfEr + $getsss->mpfEe;
        $total = $sss_total + $wsip;
        $response[] = array(
			"id" 			 => $getsss->id,
            "rangeOfComp"    => $getsss->rangeOfComp,
            "rangeOfCompEnd" => $getsss->rangeOfCompEnd,
            "basicSalary"    => $getsss->basic_salary,
            "sssEr"          => $getsss->sssEr,
            "sssEe"          => $getsss->sssEe,
            "sssTotal"       => $getsss->sssTotal,
            "ec"             => $getsss->ec,
            "mpfEr"          => $getsss->mpfEr,
            "mpfEe"          => $getsss->mpfEe,
            "total"          => $total
        );
    }
    echo jsonify($response);
});

$app->get("/sss/edit/:var", function($var){
    $param = explode(".", $var);
	$id = trim($param[0]);
	$token = $param[1];
	$response = array();
    
	if(count($param)===2) {
		$result = read_sss_by_id($id);
		$response = array(
			"id" 			 => $result->id,
			"rangeOfComp"    => $result->rangeOfComp,
			"rangeOfCompEnd" => $result->rangeOfCompEnd,
			"basicSalary"    => $result->basic_salary,
			"sssEr"          => $result->sssEr,
			"sssEe"          => $result->sssEe,
			"sssTotal"       => $result->sssTotal,
            "ec"             => $result->ec,
            "mpfEr"          => $result->mpfEr,
            "mpfEe"          => $result->mpfEe
		);
	}
    echo jsonify($response);
});

$app->post("/sss/update/:var", function($var) {
	$param = explode(".", $var);
	$id = trim($param[0]);
	$response = array();
    $success = 0;
	
	if(count($param)===1) {
		$sssStart = $_POST["start"];
		$sssEnd = $_POST["end"];
		$sssSalary = $_POST["monthly"];
		$sssEr = $_POST["employer"];
		$sssEe = $_POST["employee"];
		$sssTotal = $sssEr + $sssEe;
		
		updateSSS($id , $sssStart, $sssEnd, $sssSalary, $sssEr, $sssEe, $sssTotal);
		$success = 1;
	}
	
	$response = array(
		"success" => $success 
	);
	
	echo jsonify($response);
});

$app->post("/sss/update/", function(){
    $i         = $_POST['i'];
    $sssStart  = $_POST['sssStart'];
    $sssEnd    = $_POST['sssEnd'];
    $sssSalary = $_POST['sssSalary'];
    $sssEr     = $_POST['sssEr'];
    $sssEe     = $_POST['sssEe'];
    $sssTotal  = $_POST['sssTotal'];
        $i++;
        updateSSS($i , $sssStart, $sssEnd, $sssSalary, $sssEr, $sssEe, $sssTotal);

    echo $i;
});

/*---------------------------------SSS End--------------------------------------*/
/*---------------------------------TAX--------------------------------------*/
$app->get("/tax/get/:var", function($var){
    $param        = explode(".", $var);
    $frequencyUid = $param[0];
    $response     = array();
    $tax          = getAllTax($frequencyUid);
    foreach ($tax as $taxx) {
        $response[] = array(
            "taxStatus" => $taxx->no_dep_status,
            "taxOne"    => number_format($taxx->no_dep_1, 2),
            "taxTwo"    => number_format($taxx->no_dep_2, 2),
            "taxThree"  => number_format($taxx->no_dep_3, 2),
            "taxFour"   => number_format($taxx->no_dep_4, 2),
            "taxFive"   => number_format($taxx->no_dep_5, 2),
            "taxSix"    => number_format($taxx->no_dep_6, 2),
            "taxSeven"  => number_format($taxx->no_dep_7, 2),
            "taxEight"  => number_format($taxx->no_dep_8, 2)
        );
    }

    echo jsonify($response);
});

$app->get("/tax/get/forEdit/:var", function($var){
    $param        = explode(".", $var);
    $frequencyUid = $param[0];
    $response     = array();
    $tax          = getAllTax($frequencyUid);
    foreach ($tax as $taxx) {
        $response[] = array(
            "taxStatus" => $taxx->no_dep_status,
            "taxOne"    => $taxx->no_dep_1,
            "taxTwo"    => $taxx->no_dep_2,
            "taxThree"  => $taxx->no_dep_3,
            "taxFour"   => $taxx->no_dep_4,
            "taxFive"   => $taxx->no_dep_5,
            "taxSix"    => $taxx->no_dep_6,
            "taxSeven"  => $taxx->no_dep_7,
            "taxEight"  => $taxx->no_dep_8
        );
    }

    echo jsonify($response);
});

$app->get("/tax/exemp/:var", function($var){
    $param        = explode(".", $var);
    $frequencyUid = $param[0];
    $response     = array();
    $exemp        = getExemption($frequencyUid);
    foreach ($exemp as $ex) {
        $response[] = array(
            "exemption" => $ex->exemption,
            "exStatus"  => $ex->status,
        );
    }

    echo jsonify($response);
});

// $app->post("/tax/ex/update/", function(){
//     // $param = explode(".", $var);
//     $frequencyUid = $_POST['frequencyUid'];
//     $i = $_POST['i'];
//     $taxExemp = $_POST['taxExemp'];
//     $taxStat = $_POST['taxStat'];

//     $i++;
//     updateExemption($i, $frequencyUid, $taxStat, $taxExemp);
//     echo $i;
// });


$app->post("/tax/ex/update/", function(){
    // $param = explode(".", $var);
    $frequencyUid = $_POST['frequencyUid'];    
    $items        = $_POST['items'];
    $response     = array();
    $exempts      = getExemptionUid($frequencyUid);

    $i;

    for($i=0; $i<8; $i++) {
        //echo json_encode($exempts[$i]["exemptionUid"] ."<br/>". $items[$i]["taxExemp"] ."<br/>". $items[$i]["taxStatus"]);  
        updateExemption($exempts[$i]["exemptionUid"], $items[$i]["taxExemp"], $items[$i]["taxStatus"]);
    }    
});

$app->post("/tax/ex/update/dailies/", function(){
    $i        = $_POST['i'];
    $taxExemp = $_POST['taxExemp'];
    $taxStat  = $_POST['taxStat'];

    $i++;
    updateExemptionDailies($i, $taxStat, $taxExemp);
    echo $i;
});

$app->post("/tax/taxx/update/", function(){
    $frequencyUid = $_POST['frequencyUid'];
    $items        = $_POST['items'];
    $response     = array();
    $taxx         = getTaxByFreqUid($frequencyUid);

    $i;

    for($i=0; $i<6; $i++){
        // updateTax($i, $frequencyUid ,$taxStatt, $taxOne, $taxTwo, $taxThree, $taxFour, $taxFive, $taxSix, $taxSeven, $taxEight);
        updateTax($taxx[$i]["tax_uid"], $items[$i]["taxStatt"], $items[$i]["taxOne"], $items[$i]["taxTwo"], $items[$i]["taxThree"], $items[$i]["taxFour"], $items[$i]["taxFive"], $items[$i]["taxSix"], $items[$i]["taxSeven"], $items[$i]["taxEight"]);
        // echo json_decode($taxx[$i]["tax_uid"]); 
    }
});

/*---------------------------------TAX End--------------------------------------*/

/*---------------------------------philhealth--------------------------------------*/
$app->get("/philhealth/tables/", function(){
    $response   = array();
    $philhealth = read_philhealth_tables();
    foreach ($philhealth as $phil) {
        $response[] = array(
            "id"       => $phil->id,
            "uid"      => $phil->uid,
            "year"     => $phil->year,
            "percent"  => $phil->percent
        );
    }
    echo jsonify($response);
});

$app->get("/philhealth/tables/edit/:var", function($var){
    $param = explode(".", $var);
	$uid = trim($param[0]);
	$token = $param[1];
	$response = array();
    
	if(count($param)===2) {
		$result = read_philhealth_by_uid($uid);
		$response = array(
			"id" => $result->id,
			"year" => $result->year,
            "percent" => $result->percent
		);
	}
    echo jsonify($response);
});

$app->post("/philhealth/tables/update/:var", function($var){
    $param = explode(".", $var);
	$uid = trim($param[0]);
	$token = $param[1];
	$response = array(
        "success" => 0
    );

    if(count($param)===2) {
        $year = $_POST["year"];
        $percent = $_POST["percent"];
        update_philhealth_tables($uid, $year, $percent);
        $response = array(
            "success" => 1
        );
    }

    echo jsonify($response);
});

$app->get("/philhealth/get/", function(){
    $response   = array();
    $philhealth = getPhilhealth();
    foreach ($philhealth as $phil) {
        $response[] = array(
            "salaryBracket"       => $phil->id,
            "salaryRange"         => $phil->salaryRange,
            "salaryRangeEnd"      => $phil->salaryRangeEnd,
            "salaryBase"          => $phil->salaryBase,
            "employeeShare"       => $phil->employeeShare,
            "employerShare"       => $phil->employerShare,
            "totalMonthlyPremium" => $phil->totalMonthlyPremium
        );
    }
    echo jsonify($response);
});

$app->get("/philhealth/edit/:var", function($var){
    $param = explode(".", $var);
	$id = trim($param[0]);
	$token = $param[1];
	$response = array();
    
	if(count($param)===2) {
		$result = read_philhealth_by_id($id);
		$response = array(
			"id" => $result->id,
			"salaryRange" => $result->salaryRange,
            "salaryRangeEnd" => $result->salaryRangeEnd,
            "salaryBase" => $result->salaryBase,
            "employeeShare" => $result->employeeShare,
            "employerShare" => $result->employerShare,
            "totalMonthlyPremium" => $result->totalMonthlyPremium
		);
	}
    echo jsonify($response);
});

$app->post("/philhealth/update/", function(){
    $i                   = $_POST['i'];
    $salaryRange         = $_POST['salaryRange'];
    $salaryRangeEnd      = $_POST['salaryRangeEnd'];
    $salaryBase          = $_POST['salaryBase'];
    $employeeShare       = $_POST['employeeShare'];
    $employerShare       = $_POST['employerShare'];
    $totalMonthlyPremium = $_POST['totalMonthlyPremium'];

        $i++;
        updatePhilhealth($i , $salaryRange, $salaryRangeEnd, $salaryBase, $employeeShare, $employerShare, $totalMonthlyPremium);

    echo $i;
});

/*---------------------------------philhealth End--------------------------------------*/

/*---------------------------------pagibig--------------------------------------*/
$app->get("/pagibig/get/", function(){

    $response = array();
    $pagibig  = getPagibig();
    foreach ($pagibig as $love) {
        $response[] = array(
            "pagibigGrossPayRange"    => $love->pagibigGrossPayRange,
            "pagibigGrossPayRangeEnd" => $love->pagibigGrossPayRangeEnd,
            "pagibigEmployer"         => $love->pagibigEmployer,
            "pagibigEmployee"         => $love->pagibigEmployee,
            "pagibigTotal"            => $love->pagibigTotal
        );
    }

    echo jsonify($response);
});

$app->post("/pagibig/update/new/", function(){
    $i        = $_POST['i'];
    $pgGPR    = $_POST['pagibigGPR'];
    $pgGPREnd = $_POST['pagibigGPREnd'];
    $pgEmpr   = $_POST['pagibigEmpr'];
    $pgEmp    = $_POST['pagibigEmp'];
    $pgTotal  = $_POST['pagibigTotal'];
        $i++;
        updatePagibig($i , $pgGPR, $pgGPREnd, $pgEmpr, $pgEmp, $pgTotal);

    echo $i;
});

/*---------------------------------pagibig End--------------------------------------*/

/*---------------------------------Benefits--------------------------------------*/
$app->get("/benefits/pages/get/:var", function($var){
    $token    = $var;
    
    $benefits = getBenefitsPages();
    $response = array();

    foreach($benefits as $benefit){
        $lastname   = utf8_decode($benefit["lastname"]);
        $middlename = utf8_decode($benefit["middlename"]);
        $firstname  = utf8_decode($benefit["firstname"]);

        $response[] = array(
            "benefitUid" => $benefit->emp_benefit_uid,
            "emp"        => $lastname .", ". $firstname ." ". $middlename,
            "empSss"     => number_format($benefit->emp_sss, 2),
            "empPhil"    => number_format($benefit->emp_philhealth, 2),
            "empHDMF"    => number_format($benefit->emp_pagibig, 2)
        );
    }
    echo jsonify($response);
});

$app->get("/benefits/data/:uid", function($uid){

    $benefit  = getBenefitsByUid($uid);
    $response = array();

    if($benefit){
        $response = array(
            "benefitUid" => $benefit->emp_benefit_uid,
            "empSss"     => $benefit->emp_sss,
            "empPhil"    => $benefit->emp_philhealth,
            "empHDMF"    => $benefit->emp_pagibig,
            "status"     => $benefit->status
        );
    }
    echo jsonify($response);
});

$app->post("/benefits/add/:var", function($var){
    $token        = $var;
    $benefitUid   = xguid();
    $emp          = $_POST["emp"];
    $sss          = $_POST["sss"];
    $phil         = $_POST["phil"];
    $hdmf         = $_POST["hdmf"];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");
    
    $check        = checkUserHasBenefits($emp);
    if($check){
        $response = array(
            "prompt" => 1
        );
    }else{
        setEmpBenefit($benefitUid, $emp, $sss, $phil, $hdmf, $dateCreated, $dateModified);
        $response = array(
            "prompt" => 0
        );
    }
    echo jsonify($response);
});

$app->post("/benefits/update/:uid", function($uid){
    $sss          = $_POST["sss"];
    $phil         = $_POST["phil"];
    $hdmf         = $_POST["hdmf"];
    $status       = $_POST["status"];
    $dateModified = date("Y-m-d H:i:s");

    updateEmpBenefit($uid, $sss, $phil, $hdmf, $dateModified, $status);
});

/*---------------------------------Benefits End--------------------------------------*/

/*---------------------------------paygrade--------------------------------------*/ 
$app->post("/paygrade/add/", function(){
    $paygradeUid  = xguid();
    $paygradeName = $_POST["paygradeName"];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    addPaygrade($paygradeUid, $paygradeName, $dateCreated, $dateModified);
});

$app->get("/paygrade/level/get/", function(){
    $response = array();
    $paygrade = getPayGrade();
    foreach ($paygrade as $pg) {
        $response[] = array(
            "paygradeUid"  => $pg->paygrade_uid,
            "paygradeName" => $pg->paygrade_name
        );
    }

    echo jsonify($response);
});

$app->post("/paygrade/level/add/", function(){
    $paygradeLevelUid = xguid();
    $pgUid            = $_POST["pgUid"];
    $pgLevelName      = $_POST["pgLevelName"];
    $pgLevelMin       = $_POST["pgLevelMin"];
    $pgLevelMid       = $_POST["pgLevelMid"];
    $pgLevelMax       = $_POST["pgLevelMax"];
    $pgDateCreated    = date("Y-m-d H:i:s");
    $pgDateModified   = date("Y-m-d H:i:s");

    addPaygradeLevel($paygradeLevelUid, $pgUid, $pgLevelName, $pgLevelMin, $pgLevelMid, $pgLevelMax, $pgDateCreated, $pgDateModified);
});

$app->get("/paygrade/view/", function(){
    $response     = array();
    $paygradeview = paygradeView();
    foreach ($paygradeview as $pgv) {
        $response[] = array(
            "paygradeName"   => $pgv->paygrade_name,
            "pgLevelName"    => $pgv->pgLevelName,
            "pgLevelMinimum" => $pgv->pgLevelMinimum,
            "pgLevelMid"     => $pgv->pgLevelMid,
            "pgLevelMaximum" => $pgv->pgLevelMaximum
        );
    }

    echo jsonify($response);
});

/*---------------------------------paygrade End--------------------------------------*/ 

$app->get("/get/entitlement/:var" , function($var){
    $param = explode(".", $var);
    $token = $param[0];

    $response          = array();
    $leaveEntitlements = getLeaveEntitlementByUid();
    foreach ($leaveEntitlements as $leaveEntitlement) {
        $response[] = array(
            "leaveEntitlementUid" => $leaveEntitlement->leave_entitlement_uid,
            "firstname"           => utf8_decode($leaveEntitlement->firstname),
            "middlename"          => utf8_decode($leaveEntitlement->middlename),
            "lastname"            => utf8_decode($leaveEntitlement->lastname),
            "leaveName"           => $leaveEntitlement->leave_name,
            "from"                => $leaveEntitlement->from_period,
            "to"                  => $leaveEntitlement->to_period,
            "noDays"              => $leaveEntitlement->totaldays
        );
    }
    echo jsonify($response);
});

$app->get("/get/leave/request/:var" , function($var){
    $param = explode(".", $var);
    $token = $param[0];

    $response      = array();
    $leaveRequests = getPaginatedLeaveRequests();
    foreach ($leaveRequests as $leaveRequest) {
        $response[] = array(
            "leaveCode"     =>$leaveRequest->leave_code,
            "empNo"         =>$leaveRequest->username,
            "leaveUid"      => $leaveRequest->leave_uid,
            "startDate"     => date("M d, Y", strtotime($leaveRequest->start_date)),
            "endDate"       => date("M d, Y", strtotime($leaveRequest->end_date)),
            "firstname"     => utf8_decode($leaveRequest->firstname),
            "middlename"    => utf8_decode($leaveRequest->middlename),
            "lastname"      => utf8_decode($leaveRequest->lastname),
            // "noDays"     => $leaveRequest->no_days,
            "reason"        => $leaveRequest->reason,
            "leaveName"     => $leaveRequest->leave_name,
            "requestStatus" => $leaveRequest->leave_request_status,
            "certBy"        =>  $leaveRequest->cert_by,
            "appBy"         =>  $leaveRequest->appr_by
        );
    }
    echo jsonify($response);
});

$app->get("/get/employee/leave/requests/date/:var", function($var){
    $param     = explode(".", $var);
    $startDate = $param[0];
    $endDate   = $param[1];
    $emp       = $param[2];

    $response      = array();
    $leaveRequests = getEmployeeLeaveRequestsByDateRange($startDate, $endDate, $emp);

    foreach($leaveRequests as $leave){
        $response[] = array(
            "code"           =>$leave->leave_code,
            "employee_no"    =>$leave->username,
            "uid"            => $leave->leave_uid,
            "from"           => date("M d, Y", strtotime($leave->start_date)),
            "to"             => date("M d, Y", strtotime($leave->end_date)),
            "firstname"      => utf8_decode($leave->firstname),
            "middlename"     => utf8_decode($leave->middlename),
            "lastname"       => utf8_decode($leave->lastname),
            // "noDays"      => $leaveRequest->no_days,
            "reason"         => $leave->reason,
            "name"           => $leave->leave_name,
            "request_status" => $leave->leave_request_status,
            "certBy"         =>  $leave->cert_by,
            "date_created"         =>  date("Y-m-d h:i A", strtotime($leave->date_created)),
            "date_modified"         =>  date("Y-m-d h:i A", strtotime($leave->date_modified)),
            "appBy"          =>  $leave->appr_by
        );
    }
    echo jsonify($response);
});

$app->get("/get/leave/request/date/:var", function($var){
    $param         = explode(".", $var);
    $startDate     = $param[0];
    $endDate       = $param[1];
    $reqStatus     = $param[2];
    
    $response      = array();
    $leaveRequests = getLeaveRequestsByStatusAndDateRange($startDate, $endDate, $reqStatus);

    foreach($leaveRequests as $leave){
        $a = getEmployeeDetailsByUid($leave->emp_uid);
        if($a){
            $lastnames = utf8_decode($a->firstname) . "_" . " ";
            $words = explode("_", $lastnames);
            $name = "";

            foreach ($words as $w) {
              $name .= $w[0];
            }

            $lastname = $name . ". " . utf8_decode($a->lastname);
        }//end of getEmployeeDetailsByUid Function
        $response[] = array(
            "leaveCode"     => $leave->leave_code,
            "empNo"         => $leave->username,
            "leaveUid"      => $leave->leave_uid,
            "startDate"     => date("M d, Y", strtotime($leave->start_date)),
            "endDate"       => date("M d, Y", strtotime($leave->end_date)),
            "firstname"     => utf8_decode($leave->firstname),
            "middlename"    => utf8_decode($leave->middlename),
            "lastname"      => utf8_decode($leave->lastname),
            "name" => $lastname,
            // "noDays"     => $leaveRequest->no_days,
            "reason"        => $leave->reason,
            "leaveName"     => $leave->leave_name,
            "leaveType"     => $leave->leave_code,
            "requestStatus" => $leave->leave_request_status,
            "certBy"        =>  $leave->cert_by,
            "date_created"        =>  date("m-d-y h:i A", strtotime($leave->date_created)),
            "date_modified"        =>  date("m-d-y h:i A", strtotime($leave->date_modified)),
            "appBy"         =>  $leave->appr_by
        );
    }
    echo jsonify($response);
});

$app->get("/get/leave/details/:uid", function($uid){
    $response = array();
    $leave    = getLeaveRequestsByUid($uid);
    if($leave){
        $response = array(
            "uid"            => $leave->leave_uid,
            "from"           => $leave->start_date,
            "to"             => $leave->end_date,
            "firstname"      => utf8_decode($leave->firstname),
            "middlename"     => utf8_decode($leave->middlename),
            "lastname"       => utf8_decode($leave->lastname),
            "noDays"         => $leave->no_days,
            "name"           => $leave->leave_name,
            "status"         => $leave->status,
            "request_status" => $leave->leave_request_status
        );
    }

    echo jsonify($response);
});

$app->get("/get/leave/types/:var", function($var){
    $param = explode(".", $var);
    $token = $param[0];
    
    $x     = getPaginatedLeaveTypes();
    foreach($x as $leave){
        $response[] = array(
            "leaveUid"  => $leave->leave_uid,
            "leaveName" => $leave->leave_name
        );
    }

    echo jsonify($response);
});

$app->post("/leave/type/new/:var", function($var){
    $param        = explode(".", $var);
    $token        = $param[0];
    
    $leaveUid     = xguid();
    $name         = $_POST["name"];
    $code         = $_POST["code"];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    addLeaveType($leaveUid, $code ,$name ,$dateCreated, $dateModified);
});

$app->get("/remove/leave/request/:uid", function($uid){
    $status = 0;

    removeLeaveRequestByUid($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->post("/update/leave/request/:uid", function($uid){
    $leaveStart         = $_POST["leaveStart"];
    $leaveEnd           = $_POST["leaveEnd"];
    $status             = $_POST["status"];
    $admin              = $_POST["admin"];
    $leaveStatus        = $_POST["leaveStatus"];
    $dateModified       = date("Y-m-d H:i:s");
    $notifStatus        = 1;
    
    $leaveStart         = date("Y-m-d", strtotime($leaveStart));
    $leaveEnd           = date("Y-m-d", strtotime($leaveEnd));
    
    $leaveData          = getLeaveRequestsDataByUid($uid);
    $leaveEmpUid        = $leaveData["emp_uid"];
    $empNumber          = getEmloyeeNumberByEmpUid($leaveEmpUid);
    $leaveStartDate     = $leaveData["start_date"];
    $leaveEndDate       = $leaveData["end_date"];
    $leaveReason        = $leaveData["reason"];
    $leaveRequestStatus = $leaveData["leave_request_status"];
    
    $employee           = getEmployeeDetailsByUid($admin);
    // var_dump($emp); 
    if($employee){
        $lastname   = $employee->lastname;
        $firstname  = $employee->firstname;
        $middlename = $employee->middlename;
        $fullname   = $firstname . " " . $middlename . " " . $lastname;
    }else{
        $fullname = "";
    }

    function getInitials($fullname){
        $words=explode(" ",$fullname);
        $inits='';
        foreach($words as $word){
            $inits.=strtoupper(substr($word,0,1));
        }
        return $inits;  
    }

    $user = getInitials($fullname);

    if($leaveStatus == "Certified"){
        $user1 = $user;
        $user2 = "";
        updateLeaveByUid($uid, $leaveStart, $leaveEnd, $leaveStatus, $user1, $user2 ,$dateModified, $status);
    }else if($leaveStatus == "Approved"){
        $user2 = $user;
        $user1 = "";
        updateLeaveByUid($uid, $leaveStart, $leaveEnd, $leaveStatus, $user1, $user2 ,$dateModified, $status);
    }else{
        $user1 = "";
        $user2 = $user;
        updateLeaveByUid($uid, $leaveStart, $leaveEnd, $leaveStatus, $user1, $user2 ,$dateModified, $status);
    }
});

$app->post("/set/emp/leave/count/", function(){
    $response      = array();
    $leaveCountUid = xguid();
    $emp           = $_POST["emp"];
    $sL            = $_POST["sL"];
    $bL            = $_POST["bL"];
    $brL           = $_POST["brL"];
    $vL            = $_POST["vL"];
    $mL            = $_POST["mL"];
    $pL            = $_POST["pL"];
    $dateCreated   = date("Y-m-d H:i:s");
    $dateModified  = date("Y-m-d H:i:s");
    
    $check         = checkEmpLeaveCountByEmpUid($emp);
    if($check){
        $response = array(
            "prompt" => 1
        );
    }else{
        setEmpLeaveCounts($leaveCountUid, $emp, $sL, $bL, $brL, $vL, $mL, $pL, $dateCreated, $dateModified);
        $response = array(
            "prompt" => 0
        );
    }
    echo jsonify($response);
});

$app->get("/get/emp/leave/counts/pages/", function(){
    $x = leaveCounts();
    echo jsonify($x);
});

$app->get("/get/emp/leave/counts/employee/:uid", function($uid){
    $response = array();
    
    $data     = getEmpLeaveCountByUid($uid);
    if($data){
        $response = array(
            "leaveCountUid" => $data->emp_leave_count_uid,
            "SL"            => $data->SL,
            "BL"            => $data->BL,
            "BV"            => $data->BV,
            "VL"            => $data->VL,
            "ML"            => $data->ML,
            "PL"            => $data->PL,
            "status"        => $data->status
        );
    }
    echo jsonify($response);
});

$app->post("/update/emp/leave/counts/:uid", function($uid){
    $sL           = $_POST["sL"];
    $bL           = $_POST["bL"];
    $bV           = $_POST["bV"];
    $vL           = $_POST["vL"];
    $mL           = $_POST["mL"];
    $pL           = $_POST["pL"];
    $status       = $_POST["status"];
    $dateModified = date("Y-m-d H:i:s");

    updateEmpLeaveCounts($uid, $sL, $bL, $bV, $vL, $mL, $pL, $dateModified, $status);
});

$app->post("/entitlement/new/:var", function($var){
    $param          = explode(".", $var);
    $token          = $param[0];
    
    $entitlementUid = xguid();
    $employee       = $_POST['employee'];
    $leaveType      = $_POST['leaveType'];
    $leavePeriod    = $_POST['leavePeriod'];
    $entitlement    = $_POST['entitlement'];
    $dateCreated    = date("Y-m-d H:i:s");
    $dateModified   = date("Y-m-d H:i:s");

    newEntitlementNew($entitlementUid, $employee, $leaveType, $leavePeriod, $entitlement, $dateCreated, $dateModified);
});

$app->get("/files/get/folder/location/:uid", function($uid){
    set_time_limit(0);

    $response = array();
    $files    = getFilesByEmpUid($uid);
    $stat     = "assets/files";

    while (true){
        $last_ajax_call = isset($_GET["timestamp"]) ? (int)$_GET["timestamp"] : null;
        clearstatcache();
        
        // $date = new DateTime($dateModified);
        // $last_change_in_data_file = $stat['mtime'];
        $last_change_in_data_file = filemtime($stat);
        if ($last_ajax_call == null || $last_change_in_data_file > $last_ajax_call) {
            // $data = file_get_contents($files);
            $files = getFilesByEmpUid($uid);
            if($files){
                $fileUid      = $files->uid;
                $path         = $files->path;
                $empUid       = $files->emp_uid;
                $dateModified = new DateTime($files->date_modified);
                $dateModified = $dateModified->getTimestamp();
            }else{
                $fileUid      = 0;
                $path         = 0;
                $empUid       = $uid;
                $dateModified = 0;
            }
            $response = array(
                "fileUid"   => $fileUid,
                "path"      => $path,
                "empUid"    => $empUid,
                "timestamp" => $last_change_in_data_file,
                "ajax"      => $last_ajax_call
            );
            echo jsonify($response);
            break;
        }else{
            sleep(1);
            continue;
        }      
    }
});

$app->post("/files/multiple/new/:var", function($var){
    $param = explode(".", $var);
    if(validToken($param[2]) && count($param) == 3 && isset($_FILES["attachment"])){
        $empUid    = $param[0];
        $check     = checkFilesIfUserExisting($empUid);
        $reference = $param[2];
        $unique    = $param[1];
        $directory = "assets" . DIRECTORY_SEPARATOR . "files" . DIRECTORY_SEPARATOR;
        if($check){
            $imagePath = getPathByEmpUid($empUid);
            $path2     = $imagePath->path;
            //unlink($path2);

            $fileUid      = xguid();
            $tempFilename = isset($_FILES["attachment"]["name"]);
            $mimeType     = isset($_FILES["attachment"]["type"]);
            $size         = isset($_FILES["attachment"]["size"]);
            $dateCreated  = date("Y-m-d H:i:s");
            $extension    = pathinfo($tempFilename, PATHINFO_EXTENSION);
            $directory    = "assets" . DIRECTORY_SEPARATOR . "files" . DIRECTORY_SEPARATOR;
            $path         = $directory . sha1($tempFilename) . "_" . xguid() . "." . $extension;

            move_uploaded_file($_FILES["attachment"]["tmp_name"], $path);


            updateProfilePicture($empUid, $path2, $path, $tempFilename);

            $response[$tempFilename] = $path;
        }else{
            if (isset($_FILES["attachment"])) {
                $response = array();
                $error    = $_FILES["attachment"]["error"];
                //DEACTIVATE OTHER REFERENCE FILES
                if($unique === "1"){
                    updateFileStatusByReferenceFilenameEmpUid($reference , $fileName , $empUid , 0);
                }
                if (!is_array($_FILES["attachment"]['name'])){
                    $fileUid      = xguid();
                    $tempFilename = $_FILES["attachment"]["name"];
                    $mimeType     = $_FILES["attachment"]["type"];
                    $size         = $_FILES["attachment"]["size"];
                    $dateCreated  = date("Y-m-d H:i:s");
                    $extension    = pathinfo($tempFilename, PATHINFO_EXTENSION);
                    $path         = $directory . sha1($tempFilename) . "_" . xguid() . "." . $extension;
                    if(!file_exists($path)){
                        move_uploaded_file($_FILES["attachment"]["tmp_name"], $path);
                    }
                    newReferenceFile($fileUid, $reference, $tempFilename, $path, $mimeType, $size, $dateCreated, $empUid);
                    $response[$tempFilename] = $path;
                }
                else {
                    $fileCount = count($_FILES["attachment"]['name']);
                    for ($i = 0; $i < $fileCount; $i++) {
                        $fileUid      = xguid();
                        $tempFilename = $_FILES["attachment"]["name"];
                        $mimeType     = $_FILES["attachment"]["type"];
                        $size         = $_FILES["attachment"]["size"];
                        $dateCreated  = date("Y-m-d H:i:s");
                        $extension    = pathinfo($tempFilename, PATHINFO_EXTENSION);
                        $path         = $directory . sha1($tempFilename) . "." . $extension;
                        if(!file_exists($path)){
                            move_uploaded_file($_FILES["attachment"]["tmp_name"], $path);
                        }
                        newReferenceFile($fileUid, $reference, $tempFilename, $path, $mimeType, $size, $dateCreated);
                        $response[$tempFilename] = $path;
                    }
                }
            }
        }
        

        echo jsonify($response);
    }
});

$app->post("/upload/file/:var", function($var) {
    $param = explode(".", $var);
    $uid = $param[0];
    
    if(count($param)===3) {

        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt'); // valid extensions
        $path = 'uploads/'; // upload directory

        if(!empty($_POST['document']) || !empty($_POST['attachment']))
        {
            $img = $_FILES['attachment']['name'];
            $tmp = $_FILES['attachment']['tmp_name'];

            $tempFilename = isset($_FILES["attachment"]["name"]);
            $mimeType     = isset($_FILES["attachment"]["type"]);
            $size         = isset($_FILES["attachment"]["size"]);

            $docs = $param[1];
            $myDocs = str_replace(" ", "-", $docs);

            // get uploaded file's extension
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

            // can upload same image using rand function
            //$final_image = rand(1000,1000000).$img;
            $final_image = $myDocs.".".rand(1000,1000000).".".$ext;

            // check's valid format
            if(in_array($ext, $valid_extensions)) 
            { 
                $path = $path.strtolower($final_image); 

                if(move_uploaded_file($tmp,$path)) 
                {
                    echo "<img src='$path' />";
                    //$name = $_POST['document'];
                    $document = $_POST['document'];

                    //include database configuration file
                    //include_once 'db.php';

                    //insert form data in the database
                    //$insert = $db->query("INSERT uploading (name,email,file_name) VALUES ('".$name."','".$email."','".$path."')");

                    //echo $insert?'ok':'err';
                    upload_document_file($uid, $document, $final_image, $path, $mimeType, $size);
                }
            } 
            else 
            {
                echo 'invalid';
            }
        
        }
    }else {
        echo "invalid";
    }
});

/* Sample file upload */
$app->post("/krono/file/attachment/:var", function($var) {
    $param = explode(".", $var);
    $response = array();
    $token = $param[0];
    $verified = 0;
    $error = 1;
    $isValid = false;
    $year = date("Y");
    $errorMessage = null;
    if(count($param)===2) {
        
        $folder = $param[1]; //"overtime";
        $location = "uploads/";
        $path = $location . $folder;
        if(is_dir($path)) {
            $yearFolder = $path ."/". $year;
            if(is_dir($yearFolder)) {
                if($_FILES['fileupload']) {
                    $uid = $_POST['employeeName1']; //echo $_FILES['fileupload']['name']; echo "</br>"; //echo $_FILES['fileupload']['tmp_name']; //echo "</br>";
                    $img = $_FILES['fileupload']['name'];
                    $tmp = $_FILES['fileupload']['tmp_name'];

                    $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
                    $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION)); //echo $ext . "</br>";
                    $series = rand(10000,100000); //echo $series; echo "</br>";
                    $final_image = date("Ymd") ."_". $uid . "_" . $series .".". $ext; //echo $final_image . "</br>";
                    $path = $yearFolder."/".$final_image;

                    if(in_array($ext, $valid_extensions)) {
                        if(move_uploaded_file($tmp, $path)) {
                            $verified = 1;
                            $error = 0;
                        }else {
                            $errorMessage = "File upload failed!";
                        }
                    }else {
                        $errorMessage = "File extension error!";
                    }

                }else {
                    $errorMessage = "File error.";
                }
            }else {
                $errorMessage = "Year folder doesn't exist";
                mkdir($path."/".$year);
            }
        }else {
            $errorMessage = "Overtime folder doesn't exist";
        }
    }
    $response = array(
        "verified" => $verified,
        "error" => $error,
        "errorMessage" => $errorMessage
    );
    echo jsonify($response);
});
/* Sample file upload */

$app->get("/get/employee/documents/:var", function($var) {

});

/*FOR ADJUSTMENT*/
$app->get("/adjustment/pages/get/", function(){
    $adj      = getAdjustment();
    $response = array();

    foreach($adj as $adjust){
        $lastname   = utf8_decode($adjust["lastname"]);
        $middlename = utf8_decode($adjust["middlename"]);
        $firstname  = utf8_decode($adjust["firstname"]);

        $response[] = array(
            "adjUid"      => $adjust->adjustment_uid,
            "emp"         => $lastname .", ". $firstname ." ". $middlename,
            "payrollDate" => date("M d, Y", strtotime($adjust->payroll_date)),
            "amount"      => $adjust->amount
        );
    }
    echo jsonify($response);
});

$app->post("/adjustment/add/", function(){
    $adjUid       = xguid();
    $adjEmp       = $_POST["emp"];
    $adjAmount    = $_POST["amount"];
    $adjDate      = $_POST["adjDate"];
    
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    addAdjustment($adjUid, $adjEmp, $adjAmount, $adjDate, $dateCreated, $dateModified);
});

$app->get("/adjustment/view/details/:uid", function($uid){

    $adj = getAdjustmentByUid($uid);
    if($adj){
        $lastname   = utf8_decode($adj->lastname);
        $firstname  = utf8_decode($adj->firstname);
        $middlename = utf8_decode($adj->middlename);
        $response   = array(
            "adjUid"      => $adj->adjustment_uid,
            "emp"         => $lastname . ", " . $firstname . " " . $middlename,
            "payrollDate" => $adj->payroll_date,
            "amount"      => $adj->amount,
            "status"      => $adj->status
        );
    }else{
        $response = array(
            "adjUid"      => "",
            "emp"         => "",
            "payrollDate" => "",
            "amount"      => "",
            "status"      => ""
        );
    }

    echo jsonify($response);
});

$app->post("/adjustment/update/:var", function($var){
    $param        = explode(".", $var);
    
    $adjUid       = $param[0];
    $amount       = $_POST["amount"];
    $date         = $_POST["date"];
    $dateModified = date("Y-m-d H:i:s");
    $status       = $_POST["status"];

    updateAdjustment($adjUid, $amount, $date, $dateModified, $status);
});

/*FOR ADDING CURRENCY*/
$app->get("/currency/pages/get/:var", function($var){
    $param    = explode(".", $var);
    $cur      = getCurrencies();
    $response = array();
    foreach($cur as $currency){
        $response[] = array(
            "currencyUid"  => $currency->currency_uid,
            "currencyName" => $currency->name
        );
    }
    echo jsonify($response);
});

$app->post("/currency/add/", function(){
    $currencyUid  = xguid();
    $currencyName = $_POST['currency'];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    addCurrency($currencyUid, $currencyName, $dateCreated, $dateModified);
});

$app->get("/currency/view/details/:var", function($var){
    $param    = explode(".", $var);
    $uid      = $param[0];
    $token    = $param[1];
    $response = array();
    
    $viewCur  = getCurrencyByUid($uid);
    if($viewCur){
        $response = array(
            "currencyName" => $viewCur->name,
            "status"       => $viewCur->status
        );
    }else{
        $response = array(
            "currencyName" => "",
            "status"       => ""
        );
    }
    echo jsonify($response);
});

$app->post("/currency/update/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[1];
    
    $uid          = $param[0];
    $name         = $_POST['name'];
    $dateModified = date("Y-m-d H:i:s");
    $status       = $_POST['status'];
    $currency     = getCurrencyByUid($uid);

    if($name != $currency->name OR $status != $currency->status){
        updateCurrencyByUid($uid , $name , $dateModified , $status);
        if(currencyCount($name)>1){
            updateCurrencyByUid($uid , $currency->name , $dateModified , $status);
        }
    }
});
/*END OF CURRENCY*/

/*HOLIDAY*/
$app->get("/holiday/pages/get/", function(){
    $response = array();
    $year = date("Y");
    $holiday  = getHolidayByYear($year);
    foreach ($holiday as $holidays) {
        $response[] = array(
            "holidayUid" => $holidays->holiday_uid,
            "name"       => $holidays->name,
            "date"       => date("F d, Y", strtotime($holidays->date))
        );
    }

    echo jsonify($response);
});

$app->get("/holiday/type/get/", function(){
    $response = array();
    $type     = getHolidayType();
    foreach($type as $types){
        $response[] = array(
            "holidayTypeUid" => $types->holiday_type_uid,
            "nameType"       => $types->holiday_name_type
        );
    }
    echo jsonify($response);
});

$app->post("/holiday/new/", function(){
    $holidayUid   = xguid();
    $name         = $_POST["name"];
    $type         = $_POST["type"];
    $date         = $_POST["date"];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    addHoliday($holidayUid, $name, $type, $date, $dateCreated, $dateModified);
});

$app->get("/holiday/details/get/:uid", function($uid){
    $response = array();
    $holiday  = getHolidayByUid($uid);
    if($holiday){
        $response = array(
            "holidayUid" => $holiday->holiday_uid,
            "name"       => $holiday->name,
            "type"       => $holiday->holiday_type_uid,
            "date"       => $holiday->date,
            "status"     => $holiday->status
        );
    }

    echo jsonify($response);
});

$app->post("/holiday/edit/details/:uid", function($uid){
    $type         = $_POST["type"];
    $name         = $_POST["name"];
    $date         = $_POST["date"];
    $status       = $_POST["status"];
    $dateModified = date("Y-m-d H:i:s");

    updateHoliday($uid, $type, $name, $date, $dateModified, $status);
});

/*FOR ADDING FREQUENCY*/
$app->get("/frequency/pages/get/:var" , function($var){
    //parameter: term.start.size.token
    $param       = explode(".", $var);
    $term        = $param[0];
    $response    = array();
    
    $frequencies = getPaginatedFrequency();

    foreach ($frequencies as $frequency) {
        $response[] = array(
            "name"         => $frequency->pay_period_name,
            "frequency"    => $frequency->frequency,
            "frequencyUid" => $frequency->pay_period_uid
        );
    }
    echo jsonify($response);
});

$app->post("/frequency/new/:var", function($var){
    $param = explode(".", $var);
    $token = $param[0];

    $frequencyUid  = xguid();
    $frequencyName = $_POST['name'];
    $frequency     = $_POST['frequency'];
    $dateCreated   = date("Y-m-d H:i:s");
    $dateModified  = date("Y-m-d H:i:s");

    addFrequency($frequencyUid, $frequencyName, $frequency, $dateCreated, $dateModified);
});

$app->get("/frequency/details/get/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[0];
    
    $frequencyUid = $param[1];
    
    $frequency    = getfrequencyByUid($frequencyUid);
    if($frequency){
        $response = array(
            "name"      => $frequency->pay_period_name,
            "frequency" => $frequency->frequency,
            "status"    => $frequency->status
        );
    }else{
        $response = array(
            "name"      => "",
            "frequency" => "",
            "status"    => ""
        );
    }

    echo jsonify($response);
});

$app->post("/frequency/update/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[1];
    
    $frequencyUid = $param[0];
    $name         = $_POST['name'];
    $frequencies  = $_POST['frequency'];
    $dateModified = date("Y-m-d H:i:s");
    $status       = $_POST['status'];
    $frequency    = getfrequencyByUid($frequencyUid);

    if($name != $frequency->name OR $frequencies != $frequency->frequency OR $status != $frequency->status){
        updateFrequencyById($frequencyUid ,  $name , $frequencies , $dateModified , $status);
        if(frequencyCount($name)>1){
            updateFrequencyById($frequencyUid , $frequency->name , $frequency->frequencies , $dateModified , $status);
        }
    }
});
/*END OF FREQUENCY*/

/*FOR ATTENDANCE*/
$app->get("/attendace/view/employee/", function(){
    $response = array();
    $Ae       = getActiveEmployees();
    foreach($Ae as $ActE){
        $response[] = array(
            "empUid"    => utf8_decode($ActE->emp_uid),
            "firstname" => utf8_decode($ActE->firstname),
            "lastname"  => utf8_decode($ActE->lastname)
        );
    }
    echo jsonify($response);
});

$app->get("/timesheet/view/", function(){
    getEmpTimesheet();
});
/*END OF ATTENDANCE*/

/*FOR SETTING SCHEDULE*/
$app->post("/schedule/set/", function(){
    $scheduleUid = xguid();
    $payrollDate = $_POST["payrollDate"];
    $cutoffDate  = $_POST["cutoffDate"];

    setSchedule($scheduleUid, $payrollDate, $cutoffDate);
    echo "success";
});

$app->get("/schedule/view/:var", function($var){
    $param        = explode(".", $var);
    $frequencyUid = $param[0];
    $response     = array();
    $schedule     = getSchedules($frequencyUid);
    if($schedule){
        $response[] = array(
            "uid"         => $schedule->schedule_uid,
            "payrollDate" => date("M d, Y", strtotime($schedule->payroll_date)),
            "cutoffDate"  => date("M d, Y", strtotime($schedule->cutoff_date))
        );
    }
    echo jsonify($response);
});

$app->get("/schedule/data/edit/:id", function($id){
    $response = array();
    $scheds   = getSchedulesByUid($id);
        $response = array(
            "frequencyUid" => $scheds->frequency_uid,
            "payrollDate"  => $scheds->payroll_date,
            "cutoffDate"   => $scheds->cutoff_date
        );
    echo jsonify($response);
});

$app->post("/schedule/edit/data/:id", function($id){
    $status       = $_POST["status"];
    $startDate    = $_POST["startDate"];
    $endDate      = $_POST["endDate"];
    $frequencyUid = $_POST["frequencyUid"];
    $schedUid     = xguid();
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    editScheduleStatus($id, $startDate, $endDate,$status);
    addScheduleData($schedUid, $frequencyUid, $startDate, $endDate, $dateCreated, $dateModified);
});

/*END OF SETTING SCHEDULE*/
$app->get("/system/uid/generate", function(){
    $response = array(
        "uid" => xguid()
    );
    echo jsonify($response);
});

$app->post("/employees/get/username/:var", function(){
    $empUid = xguid();
});

$app->get("/token/generate/", function() {
    echo xguid();
});

/*SHIFT*/
$app->get("/shift/get/data/:var" , function($var){
    $param      = explode(".", $var);
    $response   = array();
    $shiftTypes = getPaginatedShift();
    foreach ($shiftTypes as $shift) {
        $shifts = $shift->name . ": (" . date("h:i A", strtotime($shift->start)) . " - " . date("h:i A", strtotime($shift->end)) .")";

        $response[] = array(
            "shiftUid" => $shift->shift_uid,
            "name"     => $shift->name,
            "start"    => date("h:i A", strtotime($shift->start)),
            "end"      => date("h:i A", strtotime($shift->end)),
            "grace"    => $shift->grace_period,
            "batch"    => $shift->batch,
            "shift"    => $shifts
        );
    }

    echo jsonify($response);
});

$app->post("/emp/shift/set/:uid", function($uid){
    $empShiftUid  = xguid();
    $shift        = $_POST["shift"];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    setEmpShift($empShiftUid, $shift, $uid ,$dateCreated, $dateModified);
});

$app->get("/emp/shift/data/:uid", function($uid){
    $response = array();
    $shift    = getShiftByEmpUid($uid);
    if($shift){
        $response = array(
            "empShift" => $shift->emp_shift_uid,
            "shiftUid" => $shift->shift_uid,
            "name"     => $shift->name,
            "start"    => $shift->start,
            "end"      => $shift->end,
            "batch"    => $shift->batch,
            "status"   => $shift->status
        );
    }

    echo jsonify($response);
});


$app->post("/emp/shift/edit/:uid", function($uid){
    $shift        = $_POST["shift"];
    $status       = $_POST["status"];
    $dateModified = date("Y-m-d H:i:s");

    updateEmpShift($uid, $shift, $dateModified, $status);
});

$app->post("/shift/new/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[0];
    
    $shiftUid     = xguid();
    $name         = $_POST["name"];
    $start        = $_POST["start"];
    $end          = $_POST["end"];
    $grace        = $_POST["grace"];
    $batch        = $_POST["batch"];
    
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    addShift($shiftUid , $name, $start , $end , $grace , $batch ,$dateCreated , $dateModified);
	addRules($shiftUid, $name); // Date Modified: August 24, 2016 - Added this function
});

$app->get("/shift/details/get/:var" , function($var){
    //parameter: term.start.size.token
    $param    = explode(".", $var);
    $token    = $param[1];
    
    $shiftUid = $param[0];
    
    $shift    = getShiftByUid($shiftUid);
    if($shift){
        
        $response = array(
            "shiftUid" => $shift->shift_uid,
            "name"     => $shift->name,
            "start"    => $shift->start,
            "end"      => $shift->end,
            "grace"    => $shift->grace_period,
            "batch"    => $shift->batch,
            "status"   => $shift->status
        );
    }else{
        $response = array(
            "shiftUid" => "",
            "name"     => "",
            "start"    => "",
            "end"      => "",
            "grace"    => "",
            "batch"    => "",
            "status"   => ""
        );
    }

    echo jsonify($response);
});

$app->post("/shift/update/:var" , function($var){
    //parameter: term.start.size.token
    $param        = explode(".", $var);
    $token        = $param[1];
    
    $shiftUid     = $param[0];
    $name         = $_POST["name"];
    $start        = $_POST["start"];
    $end          = $_POST["end"];
    $grace        = $_POST["grace"];
    $batch        = $_POST["batch"];
    $dateModified = date("Y-m-d H:i:s");
    $status       = $_POST['status'];

    updateShiftById($shiftUid , $name , $start , $end , $grace , $batch , $dateModified , $status);
});
/*END OF SHIFT*/

/*PAYSLIP*/
$app->get("/payslip/testing/:var", function($var){
    $params       = explode(".", $var);
    $startDate    = $params[0];
    $endDate      = $params[1];
    $frequencyUid = $params[2];
    $id           = $params[3];
    
    $aa           = getEmpTimeLogByUid($id, $startDate, $endDate, $frequencyUid);
    $b            = getEmpNetPayByEmpUid($id);
    $c            = employeeTax($startDate, $endDate, $frequencyUid);
    $pS           = getSchedules($frequencyUid);

    // echo jsonify($c);

    if($pS){
        $schedStartDate = $pS["payroll_date"];
        $schedEndDate   = $pS["cutoff_date"];
    }//end of getting schedule
    if($startDate == $schedStartDate && $endDate == $schedEndDate){
        // foreach($a as $aa){
        if($aa){
            $sahod     = $aa["sahod"];
            $allowance = $aa["allowance"];
            $overtime  = $aa["oTpay"];
            $grossPay  = $aa["grossPay"];
        }

        foreach($b as $bb){
            $sss         = $bb["sss"];
            $philhealth  = $bb["philhealth"];
            $pagibig     = $bb["pagibig"];
            $totalContri = $bb["totalContri"];
        }

        foreach($c as $cc){
            $taxId         = $cc["id"];
            $deduction     = $cc["deduction"];
            $pettyCash     = $cc["pettyCash"];
            $loan          = $cc["loan"];
            $tax           = $cc["tax"];
            $grossEarnings = $cc["grossEarnings"];
            $tardiness     = $cc["tardiness"];
            $adjustment    = $cc["adjustment"];
            $deduction     = $cc["deduction"];

            // $adjustment = $overtime + $lateSalary;
            $netPay = $grossEarnings - $deduction;

            if($id == $taxId){
                $response[] = array(
                    "sahod"          => $grossPay,
                    "allowance"      => number_format($allowance, 2),
                    "overtime"       => number_format($overtime, 2),
                    "sss"            => number_format($sss, 2),
                    "philhealth"     => number_format($philhealth, 2),
                    "pagibig"        => number_format($pagibig, 2),
                    "netPay"         => number_format($netPay, 2),
                    "tardiness"      => number_format($tardiness, 2),
                    "pettyCash"      => number_format($pettyCash, 2),
                    "loan"           => number_format($loan, 2),
                    "tax"            => number_format($tax, 2),
                    "adjustment"     => number_format($adjustment, 2),
                    "totalDeduction" => number_format($deduction, 2),
                    "grossEarnings"  => number_format($grossEarnings, 2),
                    "error"          => "",
                    "errorStatus"    => 0
                );
            }
        }
    }else{
        $response = array(
            "error"       => "NOT IN PAYROLL SCHEDULE!",
            "errorStatus" => 1
        );
    }
    echo jsonify($response);
});

$app->get("/get/requests/notification/", function(){
    $absentRequestNotificationCount         = getAbsentRequestsNotification();
    $overtimeRequestNotificationCount       = getOvertimeRequestsNotification();
    $leaveRequestNotificationCount          = getLeaveRequestsNotification();
    $offsetRequestNotificationCount         = getOffsetNewRequestNotification();
    $timeAdjustmentRequestNotificationCount = getTimeAdjustmentNewRequestNotification();

    $response = array(
        "absent_request_notification_count"          => $absentRequestNotificationCount,
        "overtime_request_notification_count"        => $overtimeRequestNotificationCount,
        "leave_request_notification_count"           => $leaveRequestNotificationCount,
        "offset_request_notification_count"          => $offsetRequestNotificationCount,
        "time_adjustment_request_notification_count" => $timeAdjustmentRequestNotificationCount
    );

    echo jsonify($response);
});

$app->get("/get/overtime/notification/count/", function(){
    $count     = countOvertimeRequestByStatus("Pending");
    $accept    = countOvertimeRequestByStatus("Accepted");
    $certified = countOvertimeRequestByStatus("Certified");
    $denied    = countOvertimeRequestByStatus("Denied");

    $response = array(
        "pendingCount"   => $count,
        "acceptCount"    => $accept,
        "certifiedCount" => $accept,
        "deniedCount"    => $denied
    );

    echo jsonify($response);
});

$app->get("/get/absent/accepted/count/", function(){
    $count = countAcceptedRequestsOfAbsent();

    $response = array(
        "pendingCount" => $count
    );

    echo jsonify($response);
});

$app->get("/get/overtime/notification/count/date/:var", function($var){
    $params    = explode(".", $var);
    $startDate = $params[0];
    $endDate   = $params[1];
    
    $count     = countOvertimeRequestsByStatusAndDateRange($startDate, $endDate, "Pending");
    $accept    = countOvertimeRequestsByStatusAndDateRange($startDate, $endDate, "Approved");
    $certified = countOvertimeRequestsByStatusAndDateRange($startDate, $endDate, "Certified");
    $denied    = countOvertimeRequestsByStatusAndDateRange($startDate, $endDate, "Denied");

    $response = array(
        "pendingCount"   => $count,
        "acceptedCount"  => $accept,
        "certifiedCount" => $certified,
        "deniedCount"    => $denied
    );

    echo jsonify($response);
});

$app->get("/get/employee/notifications/:uid", function($uid){
    $overtimeAcceptedRequestNotification       = countOvertimeAcceptedRequestsByEmpUid($uid);
    $leaveAcceptedRequestNotification          = countLeaveAcceptedRequestsByEmpUid($uid);
    $offsetAcceptedRequestNotification         = countOffsetAcceptedRequestsByEmpUid($uid);
    $timeAdjustmentAcceptedRequestNotification = countTimeAcceptedRequestsByEmpUid($uid);
	$holidayAcceptedRequestNotification 	   = countHolidayAcceptedRequestsByEmpUid($uid);

    $response = array(
        "overtime_accepted_count"        => $overtimeAcceptedRequestNotification,
        "leave_accepted_count"           => $leaveAcceptedRequestNotification,
        "offset_accepted_count"          => $offsetAcceptedRequestNotification,
        "time_adjustment_accepted_count" => $timeAdjustmentAcceptedRequestNotification,
		"holiday_accepted_count" => $holidayAcceptedRequestNotification
    );

    echo jsonify($response);
});

$app->get("/employee/read/overtime/notification/:uid", function($uid){
    $dateModified = date("Y-m-d H:i:s");
    
    $overtimes        = getEmployeeOvertimeNotification($uid);

    foreach($overtimes as $overtime){
        $overtimeUid = $overtime["overtime_request_uid"];

        updateOvertimeNotificationByUid($overtimeUid, $dateModified);
    }//end of checking
});

$app->get("/employee/pending/overtime/notification/:uid", function($uid){
    $count    = countOvertimeRequestPendingNotificationByEmpUid($uid);
    $response = array();
    if($count){
        $response = array(
            "count" => $count
        );
    }else{
        $response = array(
            "count" => 0
        );
    }

    echo jsonify($response);
});

$app->get("/edit/leave/notif/", function(){
    $notif = editLeaveRequestsNotification();

    $response = array(
        "notifCount" => ""
    );

    echo jsonify($response);
});

$app->get("/employee/read/leave/notification/:uid", function($uid){
    $dateModified = date("Y-m-d H:i:s");
    
    $check        = getEmployeeLeaveNotifications($uid);

    foreach($check as $checks){
        $reqUid = $checks["leave_request_uid"];
        updateLeaveNotificationByLeaveUid($reqUid, $dateModified);
    }//end of checking

        updateEmployeeLeaveNotifications($uid, $dateModified);

});

$app->get("/get/leave/notification/count/", function(){
    $count       = countPendingRequestsOfLeave();
    $countAccept = countAcceptedRequestsOfLeave();
    $certified   = countCertifiedRequestsOfLeave();
    $denied      = countDeniedRequestsOfLeave();

    $response = array(
        "pendingCount"   => $count,
        "acceptedCount"  => $countAccept,
        "certifiedCount" => $certified,
        "deniedCount"    => $denied
    );

    echo jsonify($response);
});

$app->get("/get/notification/leave/date/:var", function($var){
    $param     = explode(".", $var);
    $startDate = $param[0];
    $endDate   = $param[1];
    
    $count     = countPendingRequestsOfLeaveByDate($startDate, $endDate);
    $accept    = countAcceptedRequestsOfLeaveByDate($startDate, $endDate);
    $certified = countCertifiedRequestsOfLeaveByDate($startDate, $endDate);
    $denied    = countDeniedRequestsOfLeaveByDate($startDate, $endDate);
    $response = array(
        "pendingCount"   => $count,
        "acceptedCount"  => $accept,
        "certifiedCount" => $certified,
        "deniedCount"    => $denied
    );

    echo jsonify($response);
});

$app->get("/emp/notif/get/leave/:uid", function($uid){
    $count = countAcceptedLeaveRequestsByEmpUid($uid);

    $response = array(
        "acceptedCount" => $count
    );

    echo jsonify($response);
});

$app->get("/employee/pending/leave/notification/:uid", function($uid){
    $count = countPendingLeaveNotifByEmpUid($uid);

    $response = array();

    if($count){
        $response = array(
            "count" => $count
        );
    }else{
        $response = array(
            "count" => 0
        );
    }

    echo jsonify($response);
});

$app->get("/get/absent/requests/:var", function($var){
    $param    = explode(".", $var);
    $token    = $param[0];
    
    $response = array();
    $a        = getAbsentRequest();

    foreach($a as $overtime){
        $response[] = array(
            "overtimeUid"   => $overtime->overtime_request_uid,
            "lastname"      => utf8_decode($overtime->lastname),
            "firstname"     => utf8_decode($overtime->firstname),
            "empUid"        => $overtime->emp_uid,
            "startDate"     => date("M d, Y", strtotime($overtime->start_date)),
            "startDates"    => $overtime->start_date,
            "endDate"       => $overtime->end_date,
            "hours"         => $overtime->hours,
            "reason"        => $overtime->reason,
            "type"          => $overtime->leave_name,
            "requestStatus" => $overtime->overtime_request_status,
            "status"        => $overtime->status,
            "certBy"        => $overtime->cert_by,
            "appBy"         => $overtime->appr_by
        );
    }
    echo jsonify($response);
});

$app->get("/get/emp/absent/requests/:uid", function($uid){
    // $param = explode(".", $var);
    // $token = $param[0];

    $response = array();
    $a        = getAbsentRequestByEmpUid($uid);

    foreach($a as $overtime){
        $response[] = array(
            "overtimeUid"   => $overtime->overtime_request_uid,
            "lastname"      => utf8_decode($overtime->lastname),
            "firstname"     => utf8_decode($overtime->firstname),
            "empUid"        => $overtime->emp_uid,
            "startDate"     => date("M d, Y", strtotime($overtime->start_date)),
            "startDates"    => $overtime->start_date,
            "endDate"       => $overtime->end_date,
            "hours"         => $overtime->hours,
            "reason"        => $overtime->reason,
            "type"          => $overtime->holiday_name_type,
            "requestStatus" => $overtime->overtime_request_status,
            "status"        => $overtime->status
        );
    }
    echo jsonify($response);
});

$app->post("/absent/requests/new/:var", function($var){
    $param               = explode(".", $var);
    $token               = $param[0];
    
    $leaveUid            = xguid();
    // $overtimeNotifUid = xguid();
    $employee            = $_POST["employee"];
    $code                = $_POST["type"];
    $startDate           = date("Y-m-d", strtotime($_POST["startDate"]));
    $endDate             = $startDate;
    $hours               = "";
    $requestStatus       = $_POST["requestStatus"];
    $dateCreated         = date("Y-m-d H:i:s");
    $dateModified        = date("Y-m-d H:i:s");
    $reason              = $_POST["reason"];
    $type                = getLeaveTypeByCode($code);
    $leaveBalance        = "";

    echo "$type<br/>";

    newLeaveRequest($leaveUid, $employee, $type, $leaveBalance, $startDate, $endDate, $reason ,$requestStatus, $dateCreated, $dateModified);
    // addOvertimeRequestsNotification($overtimeNotifUid, $overtimeRequestUid, $dateCreated, $dateModified);
});

$app->get("/get/overtime/requests/:var", function($var){
    $param    = explode(".", $var);
    $token    = $param[0];
    
    $response = array();
    $a        = getOvertimeRequest();

    foreach($a as $overtime){
        $uid        = $overtime->emp_uid;
        $startDate  = $overtime->start_date;
        $startDates = date("Y-m-d", strtotime($startDate));
        $end        = $overtime->end_date;
        $out        = getTimeLogOutByEmpUidAndDate($uid, $startDates, $end);
        if($out){
            $outs   = $out->date_created;
            $endStr = strtotime($end);
            $outStr = strtotime($outs);
            if($outStr >= $endStr){
                $out1 = "Exact!";
            }else{
                $out1 = "Out: " . date("h:i:s A", strtotime($outs));
            }
        }else{
            $out1 = "No Time Out!";
        }
        $response[] = array(
            "overtimeUid"   => $overtime->overtime_request_uid,
            "empNo"         => $overtime->username,
            "lastname"      => utf8_decode($overtime->lastname),
            "firstname"     => utf8_decode($overtime->firstname),
            "empUid"        => $overtime->emp_uid,
            "startDate"     => date("M d, Y", strtotime($overtime->start_date)),
            "startDates"    => $overtime->start_date,
            "endDate"       => $overtime->end_date,
            "hours"         => $overtime->hours,
            "reason"        => $overtime->reason,
            "type"          => $overtime->overtime_type_name,
            "requestStatus" => $overtime->overtime_request_status,
            "status"        => $overtime->status,
            "certBy"        => $overtime->cert_by,
            "apprBy"        => $overtime->appr_by,
            "prompt"        => $out1
        );
    }
    echo jsonify($response);
});

$app->get("/get/employee/overtime/requests/date/:var", function($var){
    $param     = explode(".", $var);
    $startDate = $param[0];
    $endDate   = $param[1];
    $emp       = $param[2];
    
    $response  = array();
    $overtime  = getEmployeeOvertimeRequestsByDateRange($startDate, $endDate, $emp);

    foreach($overtime as $overtimes){
        $uid        = $overtimes->emp_uid;
        $startDate  = $overtimes->start_date;
        $startDates = date("Y-m-d", strtotime($startDate));
        $end        = $overtimes->end_date;
        $sessionData = getTimeLogByEmpUidAndDate($uid, $startDates);
        if($sessionData){
            $session = $sessionData["session"];
            // echo "$session<br/>";
            $out     = getTimeLogOutByEmpUidAndSession($uid, $session);
            if($out){
                $outs   = $out->date_created;
                $endStr = strtotime($end);
                $outStr = strtotime($outs);
                if($outStr >= $endStr){
                    $out1 = "Exact!";
                }else{
                    $out1 = "Out: " . date("M d, Y h:i:s A", strtotime($outs));
                }
            }else{
                $out1 = "No Time Out!";
            }

            $response[] = array(
                "uid"            => $overtimes->overtime_request_uid,
                "empNo"          => $overtimes->username,
                "lastname"       => utf8_decode($overtimes->lastname),
                "firstname"      => utf8_decode($overtimes->firstname),
                "empUid"         => $overtimes->emp_uid,
                "from"           => date("M d, Y", strtotime($overtimes->start_date)),
                "startDates"     => $overtimes->start_date,
                "to"             => $overtimes->end_date,
                "hours"          => $overtimes->hours,
                "reason"         => $overtimes->reason,
                "type"           => $overtimes->overtime_type_name,
                "request_status" => $overtimes->overtime_request_status,
                "status"         => $overtimes->status,
                "certBy"         => $overtimes->cert_by,
                "apprBy"         => $overtimes->appr_by,
                "date_created"   => date("Y-m-d h:i A", strtotime($overtimes->date_created)),
                "date_modified"  => date("Y-m-d h:i A", strtotime($overtimes->date_modified)),
                "prompt"         => $out1
            );
        }
    }

    echo jsonify($response);
});

$app->get("/get/overtime/request/date/:var", function($var){
    $param     = explode(".", $var);
    $startDate = $param[0];
    $endDate   = $param[1];
    $reqStatus = $param[2];
    
    $response  = array();
    $overtime  = getOvertimeRequestsByDates($startDate, $endDate, $reqStatus);

    foreach($overtime as $overtimes){
        $uid        = $overtimes->emp_uid;
        $startDate  = $overtimes->start_date;
        $startDates = date("Y-m-d", strtotime($startDate));

        $employeeDetail = getEmployeeDetailsByUid($uid);
        $lastname = $employeeDetail->lastname;
        $firstname = $employeeDetail->firstname;
        $middlename = $employeeDetail->middlename;
        $username = getEmployeeUsernameByEmpUid($uid);

        $overtimeTypeDetails = getOvertimeTypeByUid($overtimes->type);
        $overtimeTypeName = $overtimeTypeDetails["overtime_type_name"];

        // echo "$uid = $startDates<br/>";
        $end         = $overtimes->end_date;
        $sessionData = getTimeLogByEmpUidAndDate($uid, $startDates);
        $out1 = "No time log";

        $a = getEmployeeDetailsByUid($uid);
        if($a){
            $lastnames = utf8_decode($a->firstname) . "_" . " ";
            $words = explode("_", $lastnames);
            $name = "";

            foreach ($words as $w) {
              $name .= $w[0];
            }

            $lastname = $name . ". " . utf8_decode($a->lastname);
        }//end of getEmployeeDetailsByUid Function

        if($sessionData){
            $session = $sessionData["session"];
            // echo "$session<br/>";
            $out     = getTimeLogOutByEmpUidAndSession($uid, $session);
            if($out){
                $outs   = $out->date_created;
                $endStr = strtotime($end);
                $outStr = strtotime($outs);
                if($outStr >= $endStr){
                    $out1 = "Exact!";
                }else{
                    $out1 = "Out: " . date("M d, Y h:i:s A", strtotime($outs));
                }
            }else{
                $out1 = "No Time Out!";
            }
        }

        $response[] = array(
            "overtimeUid"   => $overtimes->overtime_request_uid,
            "empNo"         => $username,
            "lastname"      => utf8_decode($lastname),
            "firstname"     => utf8_decode($firstname),
            "name"          => utf8_decode($lastname),
            "empUid"        => $overtimes->emp_uid,
            "startDate"     => date("m-d-y", strtotime($overtimes->start_date)),
            "startDates"    => $overtimes->start_date,
            "endDate"       => $overtimes->end_date,
            "hours"         => $overtimes->hours,
            "reason"        => substr($overtimes->reason, 0, 20) . " ... ",
            "type"          => $overtimeTypeName,
            "requestStatus" => $overtimes->overtime_request_status,
            "status"        => $overtimes->status,
            "certBy"        => $overtimes->cert_by,
            "apprBy"        => $overtimes->appr_by,
            "date_created"  => date("m-d-y h:i A", strtotime($overtimes->date_created)),
            "date_modified" => date("m-d-y h:i A", strtotime($overtimes->date_modified)),
            "prompt"        => $out1
        );
    }

    echo jsonify($response);
});

$app->get("/get/overtime/request/:uid", function($uid){
    $response = array();
    $overtime = getOvertimeRequestsByUid($uid);

    if($overtime){
        $startDate  = $overtime->start_date;
        $startDates = date("Y-m-d", strtotime($startDate));
        $startHour  = date("h:i A", strtotime($startDate));
        
        $endDate    = $overtime->end_date;
        $endDates   = date("Y-m-d", strtotime($endDate));
        $endHour    = date("h:i A", strtotime($endDate));
        $response   = array(
            "overtimeUid"   => $overtime->overtime_request_uid,
            "startDate"     => date("M d, Y", strtotime($overtime->start_date)),
            "startDates"    => $startDates,
            "startHour"     => $startHour,
            "endDate"       => $endDates,
            "endHour"       => $endHour,
            "hours"         => $overtime->hours,
            "reason"        => $overtime->reason,
            "type"          => $overtime->type,
            "requestStatus" => $overtime->overtime_request_status,
            "status"        => $overtime->status,
            "start"         => $startDate,
            "end"           => $endDate,
            "empUid"        => $overtime->emp_uid
        );
    }
    echo jsonify($response);
});

$app->get("/check/out/:var", function($var){
    $var       = rawurldecode($var);
    $param     = explode(".", $var);
    $start     = $param[0];
    $end       = $param[1];
    $uid       = $param[2];
    
    $startDate = date("Y-m-d", strtotime($start));
    $out       = getTimeLogOutByEmpUidAndDate($uid, $startDate);
    if($out){
        $outs   = $out->date_created;
        $endStr = strtotime($end);
        $outStr = strtotime($outs);
        if($outStr >= $endStr){
            $response = array(
                "prompt" => 0,
                "out"    => $outs
            );
        }else{
            $response = array(
                "prompt" => 1,
                "out"    => $outs
            );
        }
    }else{
        $response = array(
            "prompt" => 2,
            "out"    => "No Time Out!"
        );
    }
    

    
    echo jsonify($response);
});

$app->post("/overtime/requests/new/:var", function($var){
    $param = explode(".", $var);
    $token = $param[0];

    $overtimeRequestUid = xguid();
    $overtimeNotifUid   = xguid();
    $employee           = $_POST["employee"];
    $type               = $_POST["type"];
    $reason             = $_POST["reason"];
    $requestStatus      = $_POST["requestStatus"];
    $dateCreated        = date("Y-m-d H:i:s");
    $dateModified       = date("Y-m-d H:i:s");
    $startDate          = date("Y-m-d", strtotime($_POST["startDate"]));
    $startHour          = date("H:i", strtotime($_POST["startHour"]));
    $admin              = $_POST["admin"];
    $startDate          = $startDate . " " . $startHour;
    
    $endDate            = date("Y-m-d", strtotime($_POST["endDate"]));
    $endHour            = date("H:i", strtotime($_POST["endHour"]));
    $endDate            = $endDate . " " . $endHour;
    
    $hours1             = strtotime($startDate);
    $hours2             = strtotime($endDate);
    $hours              = ($hours2 - $hours1) / 3600;
    
    /* EDIT - Waltz on 09-14-15 */
    $hours              = sprintf("%.2f", $hours);
    $ehour              = explode('.', $hours);
    $hour1              = $ehour[0];
    $hour2              = $ehour[1];

    if($hour2 >=50) {
        $hour2 = 5;
    }
    else {
        $hour2 = 0;
    }
    $hours = $hour1 . "." . $hour2;
    /* EDIT - Waltz on 09-14-15 */


    if(strtotime($startDate) <= strtotime($endDate)){
        $valid = true;
    }else{
        $valid = false;
    }

    $checkRequest = checkPayrollSchedBeforeRequest($startDate);
    $atype        = getUserTypeByEmpUid($admin);

    if($atype == "Administrator"){
        if($valid){
            addOvertimeRequest($overtimeRequestUid, $type ,$employee, $startDate, $endDate, $hours,$reason, $requestStatus, $dateCreated, $dateModified);
            addOvertimeRequestsNotification($overtimeNotifUid, $overtimeRequestUid, $dateCreated, $dateModified);
            $response = array(
                "prompt" => 0
            );
        }else{
            $response = array(
                "prompt" => 3
            );
        }
    }else{
        if($checkRequest["prompt"]){
            if($valid){
                addOvertimeRequest($overtimeRequestUid, $type ,$employee, $startDate, $endDate, $hours,$reason, $requestStatus, $dateCreated, $dateModified);
                addOvertimeRequestsNotification($overtimeNotifUid, $overtimeRequestUid, $dateCreated, $dateModified);
                $response = array(
                    "prompt" => 0
                );
            }else{
                $response = array(
                    "prompt" => 3
                );
            }
        }else{
            if($valid){
                $response = array(
                    "prompt" => 1
                );
            }else{
                $response = array(
                    "prompt" => 3
                );
            }
        }
    }
    
    echo jsonify($response);
});

$app->post("/request/overtime/:var", function($var){
    $param              = explode(".", $var);
    $token              = $param[0];
    
    $overtimeRequestUid = xguid();
    $employee           = $_POST["employee"];
    // $type            = "";
    $reason             = $_POST["reason"];
    $overtimeNotifUid   = xguid();
    
    $startDate          = $_POST["startDate"];
	$overtimeDate          = $_POST["startDate"];
    $date               = $startDate;
    $startHour          = $_POST["startHour"];
    $startHour          = date("H:i", strtotime($startHour));
    $startDate          = $startDate . " " . $startHour;
    
    $endDate            = $_POST["endDate"];
    $endHour            = $_POST["endHour"];
    $endHour            = date("H:i", strtotime($endHour));
    $endDate            = $endDate . " " . $endHour;
    
    $requestStatus      = "Pending";
    $dateCreated        = date("Y-m-d H:i:s");
    $dateModified       = date("Y-m-d H:i:s");
    
    $type               = getOvertimeTypeByDate($overtimeDate);
    $hours1             = strtotime($startDate);
    $hours2             = strtotime($endDate);
    $hours              = ($hours2 - $hours1) / 3600;
    
    /* EDIT - Waltz on 09-14-15 */
    $hours              = sprintf("%.2f", $hours);
    $ehour              = explode('.', $hours);
    $hour1              = $ehour[0];
    $hour2              = $ehour[1];

    if($hour2 >=50) {
        $hour2 = 5;
    }
    else {
        $hour2 = 0;
    }
    $hours = $hour1 . "." . $hour2;
    /* EDIT - Waltz on 09-14-15 */

    if(strtotime($startDate) <= strtotime($endDate)){
        $valid = true;
    }else{
        $valid = false;
    }

    $checkDate    = checkEmployeeHasOvertimeByDateAndEmpUid($employee, $date);
    
    $checkRequest = checkPayrollSchedBeforeRequest($date);
    if($checkRequest["prompt"]){
        if($valid){
            if($checkDate >= 2){
                $response = array(
                    "prompt" => 4
                );
            }else if($checkDate <= 2){
                addOvertimeRequest($overtimeRequestUid, $type ,$employee, $startDate, $endDate, $hours,$reason ,$requestStatus, $dateCreated, $dateModified);
                addOvertimeRequestsNotification($overtimeNotifUid, $overtimeRequestUid, $dateCreated, $dateModified);
                
                $response = array(
                    "prompt" => 0
                );
            }
        }else{
            $response = array(
                "prompt" => 3
            );
        }
    }else{
        if($valid){
            $response = array(
                "prompt" => 1
            );
        }else{
            $response = array(
                "prompt" => 3
            );
        }
    }

    echo jsonify($response);
});

$app->post("/overtime/request/edit/:uid", function($uid){
    $startDate     = date("Y-m-d", strtotime($_POST["startDate"]));
    $startHour     = date("H:i", strtotime($_POST["startHour"]));
    $startDate     = $startDate . " " . $startHour;
    
    $endDate       = date("Y-m-d", strtotime($_POST["endDate"]));
    $endHour       = date("H:i", strtotime($_POST["endHour"]));
    $endDate       = $endDate . " " . $endHour;
    
    $reason        = $_POST["reason"];
    $status        = $_POST["status"];
    $hours1        = strtotime($startDate);
    $hours2        = strtotime($endDate);
    $hours         = ($hours2 - $hours1) / 3600;
    $hour          = $_POST["hour"];
    $type          = $_POST["type"];
    $admin         = $_POST["admin"];
    
    $requestStatus = $_POST["requestStatus"];
    $dateModified  = date("Y-m-d H:i:s");
    
    $emp           = getEmployeeDetailsByUid($admin);
    if($emp){
        $lastname   = $emp->lastname;
        $firstname  = $emp->firstname;
        $middlename = $emp->middlename;
    }else{
        $lastname   = "";
        $firstname  = "";
        $middlename = "";
    }
    

    $name = $firstname . " " . $middlename . " " . $lastname;

    function getInitials($name){
        $words = explode(" ",$name);
        $inits = '';
        foreach($words as $word){
            $inits.=strtoupper(substr($word,0,1));
        }
        return $inits;  
    }

    $user = getInitials($name);

    switch($requestStatus){
        case "Certified":
            $user1 = $user;
            $user2 = "";
            updateOvertimeRequest($uid, $type,$startDate, $endDate, $reason, $hour ,$requestStatus, $user1, $user2 ,$dateModified, $status);
            updateOvertimeNotification($uid, $requestStatus, $dateModified, $status);
            break;
        case "Approved": 
            $user2 = $user;
            $user1 = "";
            updateOvertimeRequest($uid, $type,$startDate, $endDate, $reason, $hour ,$requestStatus, $user1, $user2 ,$dateModified, $status);
            updateOvertimeNotification($uid, $requestStatus, $dateModified, $status);
            break;
        default:
            $user1 = "";
            $user2 = $user;
            updateOvertimeRequest($uid, $type,$startDate, $endDate, $reason, $hour ,$requestStatus, $user1, $user2 ,$dateModified, $status);
            updateOvertimeNotification($uid, $requestStatus, $dateModified, $status);
            break;
    }
    
});

$app->post("/overtime/request/edit/batch/", function(){
    $uid = $_POST["overtimeUid"];
    $admin = $_POST["admin"];
    $count = $_POST["count"];

    $overtimeRequestDetails = getOvertimeRequestByUid($uid);
    $startDate     = date("Y-m-d H:i:s", strtotime($overtimeRequestDetails["start_date"]));
    $startHour = date("H:i:s", strtotime($overtimeRequestDetails["start_date"]));
    $endDate       = date("Y-m-d H:i:s", strtotime($overtimeRequestDetails["end_date"]));
    $endHour = date("H:i:s", strtotime($overtimeRequestDetails["end_date"]));
    
    $reason        = $overtimeRequestDetails["reason"];
    $status        = $overtimeRequestDetails["status"];
    $hours1        = strtotime($startDate);
    $hours2        = strtotime($endDate);
    $hours         = ($endHour - $startHour) / 3600;
    $hour          = $overtimeRequestDetails["hours"];
    $type          = $overtimeRequestDetails["type"];
    
    $requestStatus = $_POST["action"];
    $dateModified  = date("Y-m-d H:i:s");
    
    $emp           = getEmployeeDetailsByUid($admin);
    if($emp){
        $lastname   = $emp->lastname;
        $firstname  = $emp->firstname;
        $middlename = $emp->middlename;
    }else{
        $lastname   = "";
        $firstname  = "";
        $middlename = "";
    }
    

    $name = $firstname . " " . $middlename . " " . $lastname;
    function getInitials($name){
        $words = explode(" ",$name);
        $inits = '';
        foreach($words as $word){
            $inits.=strtoupper(substr($word,0,1));
        }
        return $inits;  
    }

    $user = getInitials($name);

    switch($requestStatus){
        case "Certified":
            $user1 = $user;
            $user2 = "";
            updateOvertimeRequest($uid, $type,$startDate, $endDate, $reason, $hour ,$requestStatus, $user1, $user2 ,$dateModified, $status);
            updateOvertimeNotification($uid, $requestStatus, $dateModified, $status);
            break;
        case "Approved": 
            $user2 = $user;
            $user1 = "";
            updateOvertimeRequest($uid, $type,$startDate, $endDate, $reason, $hour ,$requestStatus, $user1, $user2 ,$dateModified, $status);
            updateOvertimeNotification($uid, $requestStatus, $dateModified, $status);
            break;
        default:
            $user1 = "";
            $user2 = $user;
            updateOvertimeRequest($uid, $type,$startDate, $endDate, $reason, $hour ,$requestStatus, $user1, $user2 ,$dateModified, $status);
            updateOvertimeNotification($uid, $requestStatus, $dateModified, $status);
            break;
    }

    echo $count;
    
});

$app->get("/remove/overtime/request/:uid", function($uid){
    removeOvertimeRequestByUid($uid);
    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->post("/update/overtime/request/:uid", function($uid){
    $startDate = $_POST["startDate"];
    $startHour = $_POST["startHour"];
    $startHour = date("H:i", strtotime($startHour));
    $startDate = $startDate . " " . $startHour;
    
    $endDate   = $_POST["endDate"];
    $endHour   = $_POST["endHour"];
    $endHour   = date("H:i", strtotime($endHour));
    $endDate   = $endDate . " " . $endHour;
    
    $reason    = $_POST["reason"];
    $status    = $_POST["status"];
    $hours1    = strtotime($startDate);
    $hours2    = strtotime($endDate);
    $hours     = ($hours2 - $hours1) / 3600;
    
    /* EDIT - Waltz on 09-14-15 */
    $hours     = sprintf("%.2f", $hours);
    $ehour     = explode('.', $hours);
    $hour1     = $ehour[0];
    $hour2     = $ehour[1];

    if($hour2 >=50) {
        $hour2 = 5;
    }
    else {
        $hour2 = 0;
    }
    $hours = $hour1 . "." . $hour2;
    /* EDIT - Waltz on 09-14-15 */
    
    $admin = $_POST["admin"];

    $overtimeData = getOvertimeRequestsByUid($uid);
    $type  = $overtimeData["type"];

    $requestStatus = $_POST["requestStatus"];
    $dateModified  = date("Y-m-d H:i:s");

    $emp = getEmployeeDetailsByUid($admin);
    if($emp){
        $lastname   = $emp->lastname;
        $firstname  = $emp->firstname;
        $middlename = $emp->middlename;
    }else{
        $lastname   = "";
        $firstname  = "";
        $middlename = "";
    }
    

    $name = $firstname . " " . $middlename . " " . $lastname;

    function getInitials($name){
        $words = explode(" ",$name);
        $inits = '';
        foreach($words as $word){
            $inits.=strtoupper(substr($word,0,1));
        }
        return $inits;  
    }

    $user = getInitials($name);

    if(strtotime($startDate) <= strtotime($endDate)){
        $valid = true;
    }else{
        $valid = false;
    }

    $checkRequest = checkPayrollSchedBeforeRequest($startDate);
    if($checkRequest["prompt"]){
        if($valid){
            switch($requestStatus){
            case "Certified":
                $user1 = $user;
                $user2 = "";
                updateOvertimeRequest($uid,$type,$startDate, $endDate, $reason, $hours ,$requestStatus, $user1, $user2 ,$dateModified, $status);
                updateOvertimeNotification($uid, $requestStatus, $dateModified, $status);
                break;
            case "Approved": 
                $user2 = $user;
                $user1 = "";
                updateOvertimeRequest($uid,$type,$startDate, $endDate, $reason, $hours ,$requestStatus, $user1, $user2 ,$dateModified, $status);
                updateOvertimeNotification($uid, $requestStatus, $dateModified, $status);
                break;
            default:
                $user1 = $user;
                $user2 = "";
                updateOvertimeRequest($uid,$type,$startDate, $endDate, $reason, $hours ,$requestStatus, $user1, $user2 ,$dateModified, $status);
                updateOvertimeNotification($uid, $requestStatus, $dateModified, $status);
                break;
            }
            $response = array(
                "prompt" => 0
            );
        }else{
            $response = array(
                "prompt" => 3
            );
        }
    }else{
        if($valid){
            $response = array(
                "prompt" => 1
            );
        }else{
            $response = array(
                "prompt" => 3
            );
        }
    }
    echo jsonify($response);
});

$app->get("/get/overtime/type/", function(){
    $response     = array();
    
    $overtimeType = getOvertimeTypes();
    foreach($overtimeType as $type){
        $response[] = array(
            "uid" => $type->overtime_type_uid,
            "kind"    => ucfirst($type->overtime_kind),
            "name"    => $type->overtime_type_name,
            "code"    => $type->overtime_type_code,
            "rate"    => $type->rate,
            "additionalRate"  => $type->additional_rate
        );
    }
    echo jsonify($response);
});

$app->get("/get/overtime/type/kind/", function(){
    $response     = array();
    
    $overtimeType = getOvertimeTypesByType("premium");
    foreach($overtimeType as $type){
        $response[] = array(
            "uid" => $type->overtime_type_uid,
            "kind"    => ucfirst($type->overtime_kind),
            "name"    => $type->overtime_type_name,
            "code"    => $type->overtime_type_code,
            "rate"    => $type->rate,
            "additionalRate"  => $type->additional_rate
        );
    }
    echo jsonify($response);
});

$app->post("/add/overtime/type/", function(){
    $uid          = xguid();
    $kind         = $_POST["kind"];
    $name         = $_POST["name"];
    $code         = $_POST["code"];
    $rate         = $_POST["rate"];
    $rateAd       = $_POST["rateAd"];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    addOvertimeType($uid, $kind, $name, $code, $rate, $rateAd, $dateCreated, $dateModified);
});

$app->get("/get/overtime/type/data/:uid", function($uid){
    $response = array();
    $x        = getOvertimeTypeByUid($uid);

    $response = array(
        "uid"    => $x->overtime_type_uid,
        "kind"   => $x->overtime_kind,
        "name"   => $x->overtime_type_name,
        "code"   => $x->overtime_type_code,
        "rate"   => $x->rate,
        "rateAd" => $x->additional_rate,
        "status" => $x->status
    );

    echo jsonify($response);
});

$app->post("/edit/overtime/type/:uid", function($uid){
    $kind         = $_POST["kind"];
    $name         = $_POST["name"];
    $code         = $_POST["code"];
    $rate         = $_POST["rate"];
    $rateAd       = $_POST["rateAd"];
    $status       = $_POST["status"];
    $dateModified = date("Y-m-d H:i:s");

    editOvertimeType($uid, $kind, $name, $code, $rate, $rateAd, $dateModified, $status);
});

//FOR EMPLOYEES
$app->get("/get/employee/leave/requests/details/:uid", function($uid){
    $response = array();
    $leave    = getEmpLeaveRequestsByEmpUid($uid);

    foreach($leave as $leaves){
        $response[] = array(
            "code"           => $leaves->leave_code,
            "uid"            => $leaves->leave_uid,
            "from"           => date("M d, Y", strtotime($leaves->start_date)),
            "to"             => date("M d, Y", strtotime($leaves->end_date)),
            "name"           => $leaves->leave_name,
            "reason"         => $leaves->reason,
            "request_status" => $leaves->leave_request_status,
            "date_created" => date("Y-m-d h:i A",  strtotime($leaves->date_created)),
            "date_modified" => date("Y-m-d h:i A",  strtotime($leaves->date_modified))
        );
    }

    echo jsonify($response);
});

$app->get("/get/employee/overtime/requests/:uid", function($uid){
    $response = array();
    $a        = getEmployeeOvertimeRequests($uid);

    foreach($a as $overtime){
        $response[] = array(
            "uid"           => $overtime->overtime_request_uid,
            "empUid"        => $overtime->emp_uid,
            "from"          => date("M d, Y", strtotime($overtime->start_date)),
            "reason"        => $overtime->reason,
            "hours"         => number_format($overtime->hours, 2),
            "certBy"        => $overtime->cert_by,
            "appBy"         => $overtime->appr_by,
            "request_status" => $overtime->overtime_request_status,
            "status"        => $overtime->status,
            "date_created"        => date("Y-m-d h:i A", strtotime($overtime->date_created)),
            "date_modified"        => date("Y-m-d h:i A", strtotime($overtime->date_modified))
        );
    }

    echo jsonify($response);
});

$app->get("/get/employee/overtime/request/details/:uid", function($uid){
    $response = array();
    $overtime = getOvertimeRequestsByUid($uid);

    if($overtime){
        $startDate  = $overtime->start_date;
        $startDates = date("Y-m-d", strtotime($startDate));
        $startHour  = date("h:i A", strtotime($startDate));
        
        $endDate    = $overtime->end_date;
        $endDates   = date("Y-m-d", strtotime($endDate));
        $endHour    = date("h:i A", strtotime($endDate));

        $response = array(
            "uid"           => $overtime->overtime_request_uid,
            "empUid"        => $overtime->emp_uid,
            "startDate"     => $startDates,
            "startHour"     => $startHour,
            "endDate"       => $endDates,
            "endHour"       => $endHour,
            "hours"         => $overtime->end_date,
            "reason"        => $overtime->reason,
            "requestStatus" => $overtime->overtime_request_status,
            "status"        => $overtime->status,
            "type"          => $overtime->type
        );
    }

    echo jsonify($response);
});

$app->get("/get/emp/frequency/:uid", function($uid){
    $response = array();
    $freq     = getFrequencyByEmpUid($uid);

    if($freq){
        $response = array(
            "frequencyName" => $freq->pay_period_name,
            "frequencyUid"  => $freq->pay_period_uid
        );
    }

    echo jsonify($response);
});

$app->post("/empshift/", function(){
    $empShiftUid  = $_POST["empShiftUid"];
    $userId       = $_POST["userId"];
    $shift        = $_POST["shift"];
    $batch        = $_POST["batch"];
    $shiftId      = $_POST["shiftId"];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");
    
    $check        = checkEmpShift($empShiftUid, $userId, $batch, $shiftId);
    if($check == 1){
        updateEmpShifts($empShiftUid, $userId, $batch, $shiftId, $dateModified);
    }else{
        insertEmpShift($empShiftUid, $userId, $batch, $shiftId, $dateCreated, $dateModified);
    }
});

$app->post("/empData/", function(){
    $empId    = xguid();
    $userType = $_POST["userType"];
    $userId   = $_POST["userId"];
    $fname    = $_POST["fname"];
    $lname    = $_POST["lname"];
    $mname    = $_POST["mname"];
    $status   = $_POST["status"];

    if($status == "Active"){
        $status = 1;
    }else if($status == "Inactive"){
        $status = 0;
    }

    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");
    
    $check1       = checkEmpData($userId);
    $check2       = checkEmpUser($userId);

    if($check1 == 1){
        updateEmpData($userId, $fname, $lname, $mname, $dateModified, $status);
        updateEmpUser($userId, $userType, $dateModified, $status);
    }else{
        insertEmpData($userId, $fname, $lname, $mname, $dateCreated, $dateModified, $status);
        insertEmpUser($empId, $userId, $userType, $dateCreated, $dateModified, $status);
    }
    
});

$app->post("/empHolidays/", function(){
    $empId  = xguid();
    $date   = $_POST["date"];
    $name   = $_POST["name"];
    $type   = $_POST["type"];
    $status = $_POST["status"];

    if($status == "Active"){
        $status = 1;
    }else if($status == "Inactive"){
        $status = 0;
    }

    if($type == "Regular"){
        $type = "Regular holiday";
    }else if($type == "Special"){
        $type = "Special Holiday";
    }

    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");
    
    $types        = getHolidayTypes($type);
    $check        = checkHoliday($date, $name);
    if($check == 1){
        updateEmpHoliday($date, $name, $types, $dateModified, $status);
    }else{
        insertHoliday($empId, $date, $name, $types, $dateCreated ,$dateModified, $status);
    }
});

$app->post("/shiftSettings/", function(){
    $shiftId      = $_POST["shiftId"];
    $timein       = $_POST["timein"];
    $timeout      = $_POST["timeout"];
    $shift        = $_POST["shift"];
    $batch        = $_POST["batch"];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    $check = checkShift($shift, $batch);

    if($check == 1){
        updateShifts($shiftId, $timein, $timeout, $shift, $batch, $dateCreated, $dateModified);
    }else{
        insertShift($shiftId, $timein, $timeout, $shift, $batch, $dateCreated, $dateModified);
    }

});

$app->post("/salaryCap/", function(){
    $salaryUid    = xguid();
    $userId       = $_POST["userId"];
    $salary       = $_POST["salary"];
    $type         = $_POST["type"];
    
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");
    
    $type         = getSalaryType($type);
    $check        = checkEmpSalary($userId, $type);
    
    $empUid       = getUserEmpUid($userId);

    if($check >= 1){
        updateSalaries($userId, $salary, $type, $dateModified);
        updateEmpType($userId, $type, $dateModified);
    }else{
        insertSalary($salaryUid, $empUid, $salary, $type, $dateCreated, $dateModified);
        newEmpType(xguid(), $type, $empUid, $dateCreated, $dateModified);
    }

});

$app->post("/usersJoin/", function(){
    $empUid       = xguid();
    $usertype     = $_POST["type"];
    $userId       = $_POST["userId"];
    $password     = $_POST["password"];
    $firstname    = utf8_decode($_POST["firstname"]);
    $lastname     = utf8_decode($_POST["lastname"]);
    $middlename   = utf8_decode($_POST["middlename"]);
    $status       = $_POST["status"];
    $username     = $userId;
    $marital      = "";
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");
    
    $userIds      = xguid();
    $password     = sha1(Base32::decode($password));
    $ivSize       = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv           = mcrypt_create_iv($ivSize, MCRYPT_RAND);
    
    $check        = checkIfUserExisted($username);

    if($check >= 1){
        echo "existing";
    }else{
        newEmployee($empUid , $firstname , $middlename , $lastname, $marital, $usertype, $dateCreated , $dateModified);
        newUserAccount($userIds , $username , $password , $usertype , $empUid , $dateCreated , $dateModified);
        newUserUniqueKey(xguid(), $userIds, $iv , $dateCreated , $dateModified);
    }
    
});

$app->get("/restday/get/data/", function(){
    $rest     = getRestDay();
    $response = array();

    foreach($rest as $restDay){
        $response[] = array(
            "restUid" => $restDay->restday_uid,
            "name"    => $restDay->name
        );
    }

    echo jsonify($response);
});

$app->get("/get/restday/data/:uid", function($uid){
    $response = array();
    $rest     = getRestDayByUid($uid);

    if($rest){
        $response = array(
            "restUid"  => $rest->restday_uid,
            "restName" => $rest->name,
            "status"   => $rest->status,
        );
    }

    echo jsonify($response);
});

$app->post("/edit/resday/:uid", function($uid){
    $restDay      = $_POST["restday"];
    $status       = $_POST["status"];
    $dateModified = date("Y-m-d H:i:s");

    editRestDay($uid, $restDay, $dateModified, $status);
});

$app->post("/rest/new/", function(){
    $restDayUid   = xguid();
    $restDay      = $_POST["restDay"];
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    newRestDay($restDayUid, $restDay, $dateCreated, $dateModified);
});

/*-------------------------------------------START COMPLAINT MODULE (JEMUEL/MICHAEL)------------------ */
$app->post("/add/emp/complaint/request/:uid", function($uid){
    $complaintUid = xguid();
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");
    $subject      = $_POST["subject"];
    $description  = $_POST["description"];
    $imgUrl       = $_POST["imgUrl"];
    
    newComplaintRequest($complaintUid, $uid, $dateCreated, $dateModified, $subject, $description, $imgUrl);
});

$app->post("/edit/emp/complaint/request/:uid", function($uid){
    $subject      = $_POST["subject"];
    $description  = $_POST["description"];
    $imgUrl       = $_POST["imgUrl"];
    $dateModified = date("Y-m-d H:i:s");

    editComplaintsByUid($uid, $dateModified, $subject, $description, $imgUrl);
});

$app->get("/get/emp/complaint/requests/:uid", function($uid){
    $response  = array();
    $complaint = getComplaintsByEmpUid($uid);
    foreach($complaint as $complaints){
         $response[] = array(
            "id"           => $complaints["id"],
            "complaintUid" => $complaints["complaint_uid"],
            "empUid"       =>$complaints["emp_uid"],
            "subject"      => $complaints["subject"],
            "description"  => $complaints["description"],
            "imgUrl"       => $complaints["image_url"],
            "dateCreated"  =>array(
                "specDate" => date("M d, Y", strtotime($complaints["date_created"])),
                "specTime" => date("H:i:s", strtotime($complaints["date_created"]))
                ),
            "dateModified" =>array(
                "specDate" => date("M d, Y", strtotime($complaints["date_modified"])),
                "specTime" => date("H:i:s", strtotime($complaints["date_modified"]))
                ),
            "status"       =>$complaints["status"]
            );
       
    }
    echo jsonify($response);
});

$app->get("/get/emp/complaint/request/:uid",function($uid){
    $response  = array();
    $complaint = getComplaintsByUid($uid);
    if ($complaint) {
        $response = array(
             "id"           => $complaint["id"],
             "complaintUid" => $complaint["complaint_uid"],
             "empUid"       =>$complaint["emp_uid"],
             "subject"      => $complaint["subject"],
             "description"  => $complaint["description"],
             "imgUrl"       => $complaint["image_url"],
             "dateCreated"  =>array(
                "specDate" => date("M d, Y", strtotime($complaint["date_created"])),
                "specTime" => date("H:i:s", strtotime($complaint["date_created"]))
                ),
            "dateModified"  =>array(
                "specDate" => date("M d, Y", strtotime($complaint["date_modified"])),
                "specTime" => date("H:i:s", strtotime($complaint["date_modified"]))
                ),
            "status"        =>$complaint["status"]
            );
    }
    echo jsonify($response);
});

/*--------------------------------------------END COMPLAINT MODULE (JEMUEL/MICHAEL)--------------------*/

/* ---------------------------------------------- OFFSET --------------------------------------------- */

$app->get("/get/notif/offset/", function(){
    $response = array();

    editOffsetNotificationRead();
});

$app->get("/get/notif/offset/count/", function(){
    // $response = array();
    $count     = countRequestsOfOffset("pending");
    $accept    = countRequestsOfOffset("accepted");
    $certified = countRequestsOfOffset("certified");
    $denied    = countRequestsOfOffset("denied");

    $response = array(
        "pendingCount"   => $count,
        "acceptedCount"  => $accept,
        "certifiedCount" => $certified,
        "deniedCount"    => $denied
    );

    echo jsonify($response);
});

$app->get("/get/notif/offset/pending/count/date/:var", function($var){
    $param     = explode(".", $var);
    $startDate = $param[0];
    $endDate   = $param[1];
    $count     = countPendingRequestsOfOffsetByDate($startDate, $endDate);

    $response = array(
        "pendingCount" => $count
    );

    echo jsonify($response);
});

$app->get("/get/notif/offset/accepted/count/date/:var", function($var){
    $param     = explode(".", $var);
    $startDate = $param[0];
    $endDate   = $param[1];
    $count     = countAcceptedRequestsOfOffsetByDate($startDate, $endDate);

    $response = array(
        "pendingCount" => $count
    );

    echo jsonify($response);
});

$app->get("/get/offset/requests/", function(){
    $response = array();
    $offset   = getOffset();

    foreach($offset as $offsets){
        $name = utf8_decode($offsets["lastname"]) . ", " . utf8_decode($offsets["firstname"]) . " " . utf8_decode($offsets["middlename"]);
        $response[] = array(
            "emp"           => $name,
            "empNo"         => $offsets["username"],
            "offsetUid"     => $offsets["offset_uid"],
            "fromDate"      => date("M d, Y", strtotime($offsets["from_date"])),
            "setDate"       => date("M d, Y", strtotime($offsets["set_date"])),
            "requestStatus" => $offsets["request_status"],
            "certBy"        => $offsets["cert_by"],
            "appBy"         => $offsets["app_by"],
            "reason"        => $offsets["reason"]
        );
    }
    echo jsonify($response);
});

$app->get("/get/offset/requests/date/:var", function($var){
    $param     = explode(".", $var);
    $startDate = $param[0];
    $endDate   = $param[1];
    $response  = array();
    $offset    = getOffsetByDate($startDate, $endDate);

    foreach($offset as $offsets){
        $name = utf8_decode($offsets["lastname"]) . ", " . utf8_decode($offsets["firstname"]) . " " . utf8_decode($offsets["middlename"]);
        $response[] = array(
            "emp"           => $name,
            "empNo"         => $offsets["username"],
            "offsetUid"     => $offsets["offset_uid"],
            "fromDate"      => date("M d, Y", strtotime($offsets["from_date"])),
            "setDate"       => date("M d, Y", strtotime($offsets["set_date"])),
            "requestStatus" => $offsets["request_status"],
            "certBy"        => $offsets["cert_by"],
            "appBy"         => $offsets["app_by"],
            "reason"        => $offsets["reason"]
        );
    }
    echo jsonify($response);
});

$app->get("/get/data/offset/request/:uid", function($uid){
    $offset = getOffsetRequestsByUid($uid);

    $response = array(
        "offsetUid"     => $offset["offset_uid"],
        "fromDate"      => $offset["from_date"],
        "setDate"       => $offset["set_date"],
        "reason"        => $offset["reason"],
        "requestStatus" => $offset["request_status"],
        "status"        => $offset["status"]
    );

    echo jsonify($response);
});

$app->post("/edit/offset/data/:uid", function($uid){
    $fromDate      = $_POST["fromDate"];
    $setDate       = $_POST["setDate"];
    $requestStatus = $_POST["requestStatus"];
    $status        = $_POST["status"];
    $admin         = $_POST["admin"];
    $reason        = $_POST["reason"];
    
    $dateModified  = date("Y-m-d H:i:s");
    
    $emp           = getEmployeeDetailsByUid($admin);
    $lastname      = $emp->lastname;
    $firstname     = $emp->firstname;
    $middlename    = $emp->middlename;
    
    $name          = $firstname . " " . $middlename . " " . $lastname;

    function getInitials($name){
        $words = explode(" ",$name);
        $inits = '';
        foreach($words as $word){
            $inits.=strtoupper(substr($word,0,1));
        }
        return $inits;  
    }

    $user = getInitials($name);

    if($requestStatus == "Certified"){
        $user1 = $user;
        $user2 = "";
        editOffset($uid, $fromDate, $setDate, $reason, $requestStatus, $user1, $user2, $dateModified, $status);
        editOffsetNotification($uid, $requestStatus, $dateModified);
    }else if($requestStatus == "Approved"){
        $user2 = $user;
        $user1 = "";
        editOffset($uid, $fromDate, $setDate, $reason, $requestStatus, $user1, $user2, $dateModified, $status);
        editOffsetNotification($uid, $requestStatus, $dateModified);
    }else{
        $user1 = "";
        $user2 = "";
        editOffset($uid, $fromDate, $setDate, $reason, $requestStatus, $user1, $user2, $dateModified, $status);
        editOffsetNotification($uid, $requestStatus, $dateModified);
    }

    
});

$app->post("/add/offset/request/", function(){
    $offsetUid      = xguid();
    $offsetNotifUid = xguid();
    $emp            = $_POST["emp"];
    $reason         = $_POST["reason"];
    $fromDate       = $_POST["fromDate"];
    $setDate        = $_POST["setDate"];
    $dateCreated    = date("Y-m-d H:i:s");
    $dateModified   = date("Y-m-d H:i:s");

    if(strtotime($fromDate) <= strtotime($setDate)){
        $valid = true;
    }else{
        $valid = false;
    }

    $checkRequest = checkPayrollSchedBeforeRequest($fromDate);
    if($checkRequest["prompt"]){
        if($valid){
            newOffsetRequest($offsetUid, $emp, $fromDate, $setDate, $reason, $dateCreated, $dateModified);
            newOffsetNotification($offsetNotifUid, $offsetUid, $dateCreated, $dateModified);
            $response = array(
                "prompt" => 0
            );
        }else{
            $response = array(
                "prompt" => 3
            );
        }
    }else{
        if($valid){
            $response = array(
                "prompt" => 1
            );
        }else{
            $response = array(
                "prompt" => 3
            );
        }
    }
    echo jsonify($response);
    
});

$app->post("/request/offset/:uid", function($uid){
    $offsetUid      = xguid();
    $offsetNotifUid = xguid();
    $fromDate       = $_POST["fromDate"];
    $setDate        = $_POST["setDate"];
    $reason         = $_POST["reason"];
    
    $dateCreated    = date("Y-m-d H:i:s");
    $dateModified   = date("Y-m-d H:i:s");

    newOffsetRequest($offsetUid, $uid, $fromDate, $setDate, $reason,$dateCreated, $dateModified);
    newOffsetNotification($offsetNotifUid, $offsetUid, $dateCreated, $dateModified);
});

$app->get("/get/offset/request/details/:uid", function($uid){
    $response = array();
    
    $offset   = getEmployeeOffsetRequests($uid);
    foreach($offset as $offsets){
        $response[] = array(
            "uid"            => $offsets["offset_uid"],
            "from"           => date("M d, Y", strtotime($offsets["from_date"])),
            "setDate"        => date("M d, Y", strtotime($offsets["set_date"])),
            "request_status" => $offsets["request_status"],
            "reason"         => $offsets["reason"]

        );
    }

    echo jsonify($response);
});

$app->get("/emp/notif/get/offset/:uid", function($uid){
    $count = countAcceptedOffsetRequestByEmpUid($uid);

    $response = array(
        "acceptedCount" => $count
    );

    echo jsonify($response);
});

$app->get("/employee/read/offset/notification/:uid", function($uid){
    $dateModified = date("Y-m-d H:i:s");
    
    $check        = getOffsetNotificationUidByEmpUid($uid);
    foreach($check as $checks){
        $uid = $checks["offset_uid"];
        updateOffsetNotificationByUid($uid, $dateModified);
    }
});

$app->get("/employee/pending/offset/notification/:uid", function($uid){
    $count    = countOffsetPendingNotificationByEmpUid($uid);
    $response = array();

    if($count){
        $response = array(
            "count" => $count
        );
    }else{
        $response = array(
            "count" => 0
        );
    }

    echo jsonify($response);
});

$app->get("/get/time/offset/request/:var", function($var){
    $token    = $var;
    $response = array();
    $times    = getTimeOffset();

    foreach($times as $time){
        $lastname   = utf8_decode($time["lastname"]);
        $firstname  = utf8_decode($time["firstname"]);
        $middlename = utf8_decode($time["middlename"]);
        $name       = $lastname . ", " . $firstname . " " . $middlename;

        $response[] = array(
            "timeUid"       => $time["time_requests_uid"],
            "empNo"         => $time["username"],
            "employee"      => $name,
            "timeIn"        => date("h:i:s A", strtotime($time["time_in"])),
            "timeOut"       => date("h:i:s A", strtotime($time["time_out"])),
            "dateRequest"   => date("F d, Y", strtotime($time["date_request"])),
            "requestStatus" => $time["request_status"],
            "status"        => $time["status"],
            "certBy"        => $time["cert_by"],
            "appBy"         => $time["app_by"],
            "reason"        => $time["reason"]
        );
    }

    echo jsonify($response);
});

$app->get("/get/employee/adjustment/time/request/date/:var", function($var){
    $param     = explode(".", $var);
    $startDate = $param[0];
    $endDate   = $param[1];
    $emp       = $param[2];
    
    $response  = array();
    $times     = getEmployeeTimeRequestsByDateRange($startDate, $endDate, $emp);

    foreach($times as $time){
        $lastname   = utf8_decode($time["lastname"]);
        $firstname  = utf8_decode($time["firstname"]);
        $middlename = utf8_decode($time["middlename"]);
        $name       = $lastname . ", " . $firstname . " " . $middlename;

        $response[] = array(
            "uid"       => $time["time_requests_uid"],
            "empNo"         => $time["username"],
            "employee"      => $name,
            "timeIn"        => date("h:i:s A", strtotime($time["time_in"])),
            "timeOut"       => date("h:i:s A", strtotime($time["time_out"])),
            "dateRequest"   => date("F d, Y", strtotime($time["date_request"])),
            "request_status" => $time["request_status"],
            "status"        => $time["status"],
            "certBy"        => $time["cert_by"],
            "appBy"         => $time["app_by"],
            "date_created"         => date("Y-m-d h:i A", strtotime($time["date_created"])),
            "date_modified"         => date("Y-m-d h:i A", strtotime($time["date_modified"])),
            "reason"        => $time["reason"]
        );
    }

    echo jsonify($response);
});

$app->get("/get/time/offset/request/date/:var", function($var){
    $param     = explode(".", $var);
    $startDate = $param[0];
    $endDate   = $param[1];
    $selStatus = $param[2];
    
    $response  = array();
    $times     = getTimeRequestsByDate($startDate, $endDate, $selStatus);


    foreach($times as $time){
        $lastnames = utf8_decode($time["firstname"]) . "_" . " ";

        $words = explode("_", $lastnames);
        $name = "";

        foreach ($words as $w) {
          $name .= $w[0];
        }

        $name = $name . ". " . utf8_decode($time["lastname"]);
    // echo "$startDate, $endDate, $selStatus";

        $username = getEmployeeUsernameByEmpUid($time["emp_uid"]);

        $response[] = array(
            "timeUid"       => $time["time_requests_uid"],
            "empNo"         => $username,
            "employee"      => $name,
            "timeIn"        => date("h:i A", strtotime($time["time_in"])),
            "timeOut"       => date("h:i A", strtotime($time["time_out"])),
            "dateRequest"   => date("M d, y", strtotime($time["date_request"])),
            "requestStatus" => $time["request_status"],
            "status"        => $time["status"],
            "certBy"        => $time["cert_by"],
            "appBy"         => $time["app_by"],
            "date_created"         => date("m-d-y h:i A", strtotime($time["date_created"])),
            "date_modified"         => date("m-d-y h:i A", strtotime($time["date_modified"])),
            "reason"        => $time["reason"]
        );
    }

    echo jsonify($response);
});

$app->get("/get/time/offset/request/edit/:uid", function($uid){
    $response = array();
    $time     = getOffsetTimeRequestByUid($uid);

    if($time){
        $rules = getRuleByEmpUid($time->emp_uid);
		$rule = $rules["shift_uid"];
		$response = array(
            "timeUid"       => $time->time_requests_uid,
            "timeIn"        => date("Y-m-d h:i A", strtotime($time->time_in)),
            "timeOut"       => date("Y-m-d h:i A", strtotime($time->time_out)),
            "dateReq"       => $time->date_request,
            "requestStatus" => $time->request_status,
            "employee"      => $time->emp_uid,
            "status"        => $time->status,
            "reason"        => $time->reason,
			"shiftUid"		=> $rule
        );
    }

    echo jsonify($response);
});

$app->post("/set/time/:emp", function($emp){
    $session      = xguid();
    $timeInUid    = xguid();
    $timeOutUid   = xguid();
    
    $timeIn       = $_POST["timeIn"];
    $timeOut      = $_POST["timeOut"];
    $timeDate     = $_POST["timeDate"];
    $typeIn       = 0;
    $typeOut      = 1;
    $timeIn       = date("Y-m-d H:i", strtotime($timeIn));
    $timeOut      = date("Y-m-d H:i", strtotime($timeOut));
    
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");
    
    $day          = date("N", strtotime($timeDate));
    $status       = 1;
    
    $rule         = getShiftUidInRules($emp, $day);
    if($rule){
        $shift = $rule["shift"];
    }
    if(strtotime($timeIn) <= strtotime($timeOut)){
        $valid = true;
    }else{
        $valid = false;
    }

    // $check = checkTimeRequest($timeDate, $emp);
    // if($check){
    //     $response = array(
    //         "prompt" => 2
    //     );
    // }else{
        if($valid){
            addTimeSheetIn($timeInUid, $emp, $shift, $session, $typeIn, $timeIn, $status);
            addTimeSheetOut($timeOutUid, $emp, $shift, $session, $typeOut, $timeOut, $status);
            $response = array(
                "prompt" => 0
            );
        }else{
            $response = array(
                "prompt" => 3
            );
        }
    // }

    echo jsonify($response);
});

$app->get("/get/time/log/date/:var", function($var){
    $params  = explode(".", $var);
    $emp     = $params[0];
    $date    = date("Y-m-d", strtotime($params[1]));
    
    $timeIn  = getTimeLogInByEmpAndDate($emp, $date);
    $timeOut = getTimeLogOutByEmpAndDate($emp, $date);
    if($timeIn && $timeOut){
        $response = array(
            "timeIn"           => date("Y-m-d h:i A", strtotime($timeIn->date_created)),
            "timeOut"          => date("Y-m-d h:i A", strtotime($timeOut->date_created)),
            "timeshift"        => $timeIn->name,
            "timeInDisplay"    => date("M d, Y h:i A", strtotime($timeIn->date_created)),
            "timeOutDisplay"   => date("M d, Y h:i A", strtotime($timeOut->date_created)),
            "timeshiftDisplay" => $timeIn->name
        );
    }else if($timeIn && !$timeOut){
        $response = array(
            "timeIn"           => date("Y-m-d h:i:s A", strtotime($timeIn->date_created)),
            "timeOut"          => "",
            "timeshift"        => $timeIn->name,
            "timeInDisplay"    => date("M d, Y h:i:s A", strtotime($timeIn->date_created)),
            "timeOutDisplay"   => "N/A",
            "timeshiftDisplay" => $timeIn->name
        );
    }else{
        $response = array(
            "timeIn"           => "",
            "timeOut"          => "",
            "timeshift"        => "",
            "timeInDisplay"    => "N/A",
            "timeOutDisplay"   => "N/A",
            "timeshiftDisplay" => "N/A"
        );
    }

    echo jsonify($response);
});

$app->post("/request/time/adjustment/", function(){
    $timeUid      = xguid();
    $employee     = $_POST["employee"];
    $timeIn       = $_POST["timeIn"];
    $timeOut      = $_POST["timeOut"];
    $timeDate     = $_POST["timeDate"];
    $reason       = $_POST["reason"];
    $timeIn       = date("Y-m-d H:i", strtotime($timeIn));
    $timeOut      = date("Y-m-d H:i", strtotime($timeOut));
    
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    // $timeOutPlusOneDay = strtotime(date("Y-m-d", $timeIn) . "+1 day");
    if(strtotime($timeIn) <= strtotime($timeOut)){
        $valid = true;
    }else{
        $valid = false;
    }

    $check = checkTimeRequest($timeDate, $employee);
    if($check){
        $response = array(
            "prompt" => 2
        );
    }else{
        $checkRequest = checkPayrollSchedBeforeRequest($timeDate);
        if($checkRequest["prompt"]){
            if($valid){
                $dateIn  = date("Y-m-d", strtotime($timeIn));
                $dateOut = date("Y-m-d", strtotime($timeOut));

                if(strtotime($dateIn) > strtotime($dateOut) ||  strtotime($dateIn) != strtotime($timeDate)){
                    $response = array(
                        "prompt" => 3
                    );
                }else{
                    $checkAbsent = checkAbsentByDateAndEmpUid($employee, $timeDate);
                    if($checkAbsent){
                        $response = array(
                            "prompt" => 5
                        );
                    }else{
                        $myTimeIn = getOldTimeIn($employee, $timeDate);
                        if($myTimeIn) {
                            $timeIn = $myTimeIn;
                        }

                        addTimeRequest($timeUid, $employee, $timeIn, $timeOut, $timeDate, $reason,$dateCreated, $dateModified);
                        addTimeReqNotification(xguid(), $timeUid, $dateCreated, $dateModified);
                        $response = array(
                            "prompt" => 0
                        );
                    }
                }
            }else{
                $response = array(
                    "prompt" => 4
                );
            }
        }else{
            if($valid){
                $response = array(
                    "prompt" => 1,
                    "req" => $checkRequest
                );
            }else{
                $response = array(
                    "prompt" => 4
                );
            }
        }
    }
    echo jsonify($response);
});

$app->get("/read/time/count/", function(){
    $count    = countPendingRequestsOfTimeReq();
    $accepted = countAcceptedRequestsOfTimeReq();
    $notif    = ""; //countTimeNotif();

    // if($count >= 300){
    //     $count = "300+";
    // }

    // if($accepted >= 300){
    //     $count = "300+";
    // }
    $dateModified = date("Y-m-d H:i:s");
    
    editRequestsOfTimeReq($dateModified);
    $response = array(
        "pendingCount" => $count,
        "accepted"     => $accepted,
        "notif"        => $notif
    );

    echo jsonify($response);
});

$app->get("/get/time/req/count/:var", function($var){
    $param     = explode(".", $var);
    $startDate = $param[0];
    $endDate   = $param[1];
    
    $count     = countPendingRequestsOfTimeReqByDate($startDate, $endDate);
    $accept    = countAcceptedRequestsOfTimeReqByDate($startDate, $endDate);
    $response = array(
        "pendingCount" => $count,
        "accepted"     => $accept
    );

    echo jsonify($response);
});

$app->get("/time/delete/:uid", function($uid){
    // $status = 0;
    deleteTimeByUid($uid);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->post("/edit/time/request/:uid", function($uid){
    $response     = array();
    $timeInUid    = xguid();
    $timeOutUid   = xguid();
    $session      = xguid();
    $timeIn       = $_POST["timeIn"];
    $timeIn       = date("Y-m-d H:i", strtotime($timeIn));
    $typeIn       = 0;
    $logIn        = "IN";
    $timeOut      = $_POST["timeOut"];
    $timeOut      = date("Y-m-d H:i", strtotime($timeOut));
    $typeOut      = 1;
    $logOut       = "OUT";
    $timeDate     = $_POST["timeDate"];
    $reason       = $_POST["reason"];
    $admin        = $_POST["admin"];
    
    $reqStatus    = $_POST["reqStatus"];
    $status       = $_POST["status"];
    $employee     = $_POST["employee"];
    $day          = date("N", strtotime($timeDate));
    $dateModified = date("Y-m-d H:i:s");
    $sTimeIn      = date("H:i:s", strtotime($timeIn));
    $sTimeOut     = date("H:i:s", strtotime($timeOut));
    
    $username     = getEmployeeUsernameByEmpUid($employee);
    
    $emp          = getEmployeeDetailsByUid($admin);
    $lastname     = $emp->lastname;
    $firstname    = $emp->firstname;
    $middlename   = $emp->middlename;
    
    $name         = $firstname . " " . $middlename . " " . $lastname;

    function getInitials($name){
        $words = explode(" ",$name);
        $inits = '';
        foreach($words as $word){
            $inits.=strtoupper(substr($word,0,1));
        }
        return $inits;  
    }

    $user = getInitials($name);

    if(strtotime($timeIn) <= strtotime($timeOut)){
        $valid = true;
    }else{
        $valid = false;
    }

    $checkRequest = checkPayrollSchedBeforeRequest($timeDate);
    $type         = getUserTypeByEmpUid($admin);

    if($type == "Administrator"){
        if($valid){
            $rule = getShiftUidInRules($employee, $day);
            if($rule){
                //$shift = $rule["shift"];
				$shift = $_POST["shift"];
                if($reqStatus == "Approved"){
                    $user1 = $user;
                    $user2 = "";
                    $check = checkTimeDateByEmpUid($employee, $timeDate);
                    if($check){
                        $getTimeIn = getOldTimeIn($employee, $timeDate);
                        $timeIn = $getTimeIn;
                        
                        removeDateFromTimeInLogByEmpAndDate($employee, $timeDate);
                        removeDateFromTimeOutLogByEmpAndDate($employee, $timeDate);
                        addTimeSheetIn($timeInUid, $employee, $shift, $session, $typeIn, $timeIn, $status);
                        addTimeSheetOut($timeOutUid, $employee, $shift, $session, $typeOut, $timeOut, $status);
                        editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                        
                        $response = array(
                            "prompt" => 3
                        );
                    }else{
                        removeDateFromTimeInLogByEmpAndDate($employee, $timeDate);
                        removeDateFromTimeOutLogByEmpAndDate($employee, $timeDate);
                        addTimeSheetIn($timeInUid, $employee, $shift, $session, $typeIn, $timeIn, $status);
                        addTimeSheetOut($timeOutUid, $employee, $shift, $session, $typeOut, $timeOut, $status);
                        editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                        $response = array(
                            "prompt" => 0
                        );
                    }
                }else if($reqStatus == "Certified"){
                    $user2 = $user;
                    $user1 = "";
                    $check = checkTimeDateByEmpUid($employee, $timeDate);
                    if($check){
                        $response = array(
                            "prompt" => 3
                        );
                    }else{
                        editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                        $response = array(
                            "prompt" => 0
                        );
                    }
                }
            }else{
                $response = array(
                    "prompt" => 2
                );
            }

            $dateModified = date("Y-m-d H:i:s");

            if($reqStatus == "Approved"){
                $user1 = $user;
                $user2 = "";
                $check = checkTimeDateByEmpUid($employee, $timeDate);
                if($check){
                    $response = array(
                        "prompt" => 3
                    );
                }else{
                    $getTimeIn = getOldTimeIn($employee, $timeDate);
                    $timeIn = $getTimeIn;
                    editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                    $response = array(
                        "prompt" => 1
                    );
                }
            }else if($reqStatus == "Certified"){
                $user2 = $user;
                $user1 = "";
                editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                $response = array(
                    "prompt" => 1
                );
            }else{
                $user2 = "";
                $user1 = "";
                editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                $response = array(
                    "prompt" => 1
                );
            }
        }else{
            $response = array(
                "prompt" => 4
            );
        }
    }else{
        if($checkRequest["prompt"]){
            if($valid){
                $rule = getShiftUidInRules($employee, $day);
                if($rule){
                    $shift = $rule["shift"];
                    if($reqStatus == "Approved"){
                        $user1 = $user;
                        $user2 = "";
                        $getTimeIn = getOldTimeIn($employee, $timeDate);
                        $timeIn = $getTimeIn;
                        editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                        $response = array(
                            "prompt" => 0
                        );
                    }else if($reqStatus == "Certified"){
                        $user2 = $user;
                        $user1 = "";
                        editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                        $response = array(
                            "prompt" => 0
                        );
                    }
                }else{
                    $response = array(
                        "prompt" => 2
                    );
                }

                $dateModified = date("Y-m-d H:i:s");

                if($reqStatus == "Approved"){
                    $user1 = $user;
                    $user2 = "";
                    $check = checkTimeDateByEmpUid($employee, $timeDate);
                    if($check){
                        $response = array(
                            "prompt" => 3
                        );
                    }else{
                        $getTimeIn = getOldTimeIn($employee, $timeDate);
                        $timeIn = $getTimeIn;
                        editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                        $response = array(
                            "prompt" => 1
                        );
                    }
                }else if($reqStatus == "Certified"){
                    $user2 = $user;
                    $user1 = "";
                    editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                    $response = array(
                        "prompt" => 1
                    );
                }else{
                    $user2 = "";
                    $user1 = "";
                    $getTimeIn = getOldTimeIn($employee, $timeDate);
                    if($getTimeIn) {
                        $timeIn = $getTimeIn;
                    }                    
                    editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                    $response = array(
                        "prompt" => 1
                    );
                }
            }else{
                $response = array(
                    "prompt" => 4
                );
            }
        }else{
            if($valid){
                $response = array(
                    "prompt" => 5
                );
            }else{
                $response = array(
                    "prompt" => 4
                );
            }
        }
    }
    
    echo jsonify($response);
});

$app->get("/get/employee/adjustment/time/request/:uid", function($uid){
    $response = array();
    $times = getEmployeeTimeAdjustmentRequests($uid);

    foreach($times as $time){
        $response[] = array(
            "uid"            => $time["time_requests_uid"],
            "timeIn"         => date("h:i:s A", strtotime($time["time_in"])),
            "timeOut"        => date("h:i:s A", strtotime($time["time_out"])),
            "dateRequest"    => date("F d, Y", strtotime($time["date_request"])),
            "request_status" => $time["request_status"],
            "status"         => $time["status"],
            "date_created"         => date("Y-m-d h:i A", strtotime($time["date_created"])),
            "date_modified"         => date("Y-m-d h:i A", strtotime($time["date_modified"])),
            "reason"         => $time["reason"]
        );
    }

    echo jsonify($response);
});

/* ---------------------------------------------- END OF OFFSET --------------------------------------------- */

$app->get("/get/user/data/:uid", function($uid){
    $x = getEmployeeDetailsByUid($uid);
    $response = array();
	if($x){
        //$name = utf8_decode($x->lastname) . ", " . utf8_decode($x->firstname) . " " . utf8_decode($x->middlename);
        $name = read_employee_name_by_uid($uid);
		$response = array(
            //"name" => utf8_decode($name)
            "name" => $name
        );
    }

    echo jsonify($response);
});

/*---------------------------------------------FOR PRINTING---------------------------------------------*/
$app->post("/get/details/", function(){
    $startDate = $_POST["startDate"];
    $endDate   = $_POST["endDate"];
    $employee  = $_POST["employee"];
    $response  = array();
    
    $startDate = date("F d, Y", strtotime($startDate));
    $endDate   = date("F d, Y", strtotime($endDate));
    
    $emp       = getEmployeeDetailsByUid($employee);
    if($emp){
        $firstname  = utf8_decode($emp["firstname"]);
        $middlename = utf8_decode($emp["middlename"]);
        $lastname   = utf8_decode($emp["lastname"]);
        $name       = $lastname . ", " . $firstname . " " . $middlename;


        $response = array(
            "startDate" => $startDate,
            "endDate"   => $endDate,
            "name"      => $name
        );
    }
    echo jsonify($response);
});

$app->post("/get/timesheet/dates/", function(){
    $startDate = $_POST["startDate"];
    $endDate   = $_POST["endDate"];
    $response  = array();
    
    $startDate = date("F d, Y", strtotime($startDate));
    $endDate   = date("F d, Y", strtotime($endDate));

    $response = array(
        "startDate" => $startDate,
        "endDate"   => $endDate
    );
    echo jsonify($response);
});

$app->post("/get/date/details/", function(){
    $startDate = $_POST["startDate"];
    $endDate   = $_POST["endDate"];
    
    $startDate = date("F d, Y", strtotime($startDate));
    $endDate   = date("F d, Y", strtotime($endDate));

    $response = array(
        "startDate" => $startDate,
        "endDate"   => $endDate
    );
    echo jsonify($response);
});

$app->post("/emp/change/password/:uid", function($uid){
    $response     = array();
    $currentPass  = $_POST["currentPass"];
    $newPass      = $_POST["newPass"];
    $reEnterPass  = $_POST["reEnterPass"];
    
    //$uxPassword   = sha1(Base32::decode($_POST["currentPass"]));
    //$password     = sha1(Base32::decode($_POST["newPass"]));
	
	$uxPassword = sha1(base64_decode($_POST["currentPass"].salt()));
	$password = sha1(base64_decode($_POST["newPass"].salt()));
	
    $dateModified = date("Y-m-d H:i:s");
    $check        = checkIfPasswordIsCorrectByEmpUid($uid, $uxPassword);
    if($check){
        updateEmpPassword($uid, $password, $dateModified);
        $response = array(
            "prompt" => 0
        );
    }else{
        $response = array(
            "prompt" => 1
        );
    }

    echo jsonify($response);
});

$app->post("/emp/change/password/admin/:uid", function($uid){
    $response     = array();
    $newPass      = $_POST["newPass"];
    $reEnterPass  = $_POST["reEnterPass"];
    
    //$password     = sha1(Base32::decode($_POST["newPass"]));
    $password = sha1(base64_decode($_POST["newPass"].salt()));
	$dateModified = date("Y-m-d H:i:s");
    updateEmpPassword($uid, $password, $dateModified);
    $response = array(
        "prompt" => 0
    );

    echo jsonify($response);
});

$app->get("/time/data/cost/center/:var", function($var){
    $param     = explode(".", $var);
    $uid       = $param[2];
    $startDate = date('Y-m-d', strtotime($param[0]));
    $endDate   = date('Y-m-d', strtotime($param[1]));
    
    $x         = generateEmployeesTimesheet($startDate, $endDate, $uid);
    echo jsonify($x);
});

$app->get("/melot/testing/", function(){
    $uid = "49F29221-5EFD-0C46-A2B0-768D617F3C2C";
    $startDate = "2015-09-15";
    $endDate = "2015-09-16";
    $employeeDetails = getEmployeeByCostCenterUid($uid);

    $startDateString = strtotime($startDate);
    $endDateString = strtotime($endDate);

    foreach ($employeeDetails as $data) {
        $employeeUid = $data->emp_uid;
        $employeeUsername = $data->username;

        $work = 0;
        $late = 0;
        $overtime = 0;
        $undertime = 0;

        $employeeDetails = getEmployeeDetailsByUid($employeeUid);
        if($employeeDetails){
            $lastnames = utf8_decode($employeeDetails->firstname) . "_" . " ";
            $words = explode("_", $lastnames);
            $name = "";

            foreach ($words as $w) {
              $name .= $w[0];
            }

            $lastname = $name . ". " . utf8_decode($employeeDetails->lastname);
            
        }//end of getEmployeeDetailsByUid Function

        for($i=$startDateString; $i<=$endDateString; $i+=86400){
            $date =  date("Y-m-d", $i);
            $day = date("l", $i);

            //Get Time In
            $timeInDetails = getTimeIn($employeeUid, $date);
            $timeInDate = date("Y-m-d", strtotime($timeInDetails["date_created"]));
            
            //Get Holiday
            $holidayDetails = getHolidayByDate($date);
            $holidayDate = $holidayDetails["date"];

            //Get Absent
            $absentDetails = getAbsentRequestByDateAndEmpUid($employeeUid, $date);
            if($absentDetails){
                $absentDate = date("Y-m-d", strtotime($absentDetails->start_date));
                $prompt = 5; //ABSENT
            }else{
                $absentDate = 0;
            }

            //Get Rest Day
            $restDayName = 0;
            $restDayDetails = getRestDayByDay($day);
            if($restDayDetails){
                $restDayName = $restDayDetails["name"];
            }//end of getting restDay

            if(date("l", $i) === $restDayName){
                $sun = date("Y-m-d", $i);
                $prompt = 2; //Rest Day
                $time = "Rest Day";
            }//end of comparing day

            //Get Leave
            $leaveDetails = getLeaveRequestsByEmpUidAndDate($employeeUid, $date);
            if($leaveDetails){
                $leaveStartDate = $leaveDetails->start_date;
                $leaveEndDate = $leaveDetails->end_date;

                $leaveDay = date("l", strtotime($date));
                $prompt = 4;
                $time = "LEAVED";
            }//end of getting leave

            if($holidayDate == $date){
                if($holidayDate === $timeInDate){
                    $holidayDate = $timeInDate;
                    $prompt = 1; //HAS TIME IN
                    $time = $timeInDetails["date_created"];
                }else{
                    $prompt = 3; //HOLIDAY
                    $time = "HOLIDAY";
                }
            }else if($absentDate === $date){
                $prompt = 5; //ABSENT
            }else if($timeInDate != $date && $holidayDate != $date){
                $prompt = 0; // ABSENT
                $time = "ABSENT";
            }else{
                $holidayDate = 0;
                $prompt = 1;
                $time = $timeInDetails["date_created"];
            }

            switch ($prompt) {
                case 0: //FOR OFFSET OR ABSENT
                    $offsetDetails = getAcceptedOffsetRequestByEmpUid($employeeUid, $date);
                    // print_r($offsetDetails);

                    if($offsetDetails){
                        $offsetId = $offsetDetails->offset_uid;
                        $offsetEmpUid = $offsetDetails->emp_uid;
                        $offsetFromDate = $offsetDetails->from_date;
                        $offsetSetDate = $offsetDetails->set_date;
                        $offsetDay = date("N", strtotime($offsetSetDate));

                        $OffsetTimeInDetails = getOffsetTimeInByEmpUidAndDate($employeeUid, $offsetFromDate);
                        foreach($OffsetTimeInDetails as $offsetTimeIn){
                            //TIME IN
                            $offsetTimeInUid = $offsetTimeIn["time_log_uid"];
                            $offsetTimeInData = $offsetTimeIn["date_created"];
                            $offsetTimeInDate = date("Y-m-d", strtotime($offsetTimeInData));
                            $offsetTimeInDay = date("N", strtotime($offsetTimeInDate));
                            $offsetTimeInSession = $offsetTimeIn["session"];

                            //TIME OUT
                            $timeOutDetail = getTimeOutByEmpUidAndSessionNoLoc($employeeUid, $offsetTimeInSession);
                            $timeOutUid = $timeOutDetail["time_log_uid"];
                            $timeOut = $timeOutDetail["date_created"];
                            $timeOutDate = date("Y-m-d", strtotime($timeOut));

                            $timeOutHour = date("H:i:s", strtotime($timeOut));
                            $timeInHour = date("H:i:s", strtotime($offsetTimeInData));

                            //SHIFT
                            $shift = getShiftByTimeInUid($offsetTimeInUid);
                            $shiftStart = $shift->start;
                            $shiftEnd = $shift->end;

                            $shiftEnds = $shiftEnd;
                            $shiftStarts = $shiftStart;

                            $shiftDuration = getEmployeeShiftDuration($employeeUid, $shiftStart, $shiftEnd, $timeInHour, $offsetTimeInUid);

                            /*---------------------OVERTIME---------------------*/
                            if(strtotime($shiftEnd) <= strtotime($timeOutHour)){
                                if($offsetTimeInDate === $timeOutDate){
                                    $overtime = (strtotime($timeOutHour) - strtotime($shiftEnd)) / 3600;
                                }else{
                                    $shiftEnds = $timeOutDate . $shiftEnds;
                                    $overtime = (strtotime($out) - strtotime($shiftEnds)) / 3600;
                                }
                            }else if(strtotime($shiftEnd) >= strtotime($timeOutHour)){
                                if($offsetTimeInDate === $timeOutDate){
                                    $overtime = 0;
                                }else{
                                    $shiftEnd = date("Y-m-d", strtotime($offsetTimeInData . "- 0 day")) . " $shiftEnd"; 
                                    $overtime = (strtotime($timeOut) - strtotime($shiftEnd)) / 3600;
                                }
                            }

                            if($overtime > 60){
                                $overtime = 0;
                            }else if($overtime <= -1 ){
                                $overtime = 0;
                            }

                            if($overtime <= 0){
                                $response[] = array(
                                    "id" => $id,
                                    "inId" => 0,
                                    "outId" => 0,
                                    "prompt" => $prompt,
                                    "lastname" => strtoupper($lastname),
                                    "dates" => $date,
                                    "date" => date("M d, Y", strtotime($date)),
                                    "day" => $day,
                                    "in" => "NO TIME IN",
                                    "out" => "NO TIME OUT",
                                    "late" => "--",
                                    "tardiness" => "--",
                                    "overtime" => "--",
                                    "undertime" => "--",
                                    "work" => "--",
                                    "totalWorked" => "--",
                                    "totalLate" => "--",
                                    "totalOvertime" => "--",
                                    "totalUndertime" => "--",
                                    "approveOTStatus" => "0",
                                    "location" => "--=--",
                                    "empNo" => $empNo
                                );
                            }else{
                                if($overtime === $shiftDuration){
                                    $totalOvertime = $shiftDuration;
                                }else if($overtime > $shiftDuration){
                                    $totalOvertime = $shiftDuration;
                                    
                                }else if($overtime < $shiftDuration){
                                    $totalOvertime = $overtime - 1;
                                }//end of getting total overtime
                                    
                                $overtimeHour = floor($totalOvertime);
                                $totalOvertimeMin = (60*($totalOvertime-$overtimeHour));
                                $overtimeMin = floor(60*($totalOvertime-$overtimeHour));
                                $overtimeMin1 = floor($totalOvertimeMin);
                                $overtimeSec = floor(60*($totalOvertimeMin-$overtimeMin1));

                                $overtimes = new dateTime("$overtimeHour:$overtimeMin:$overtimeSec");

                                /*FOR SECOND OUT*/
                                $secondTotalOvertime = $totalOvertime;
                                $secondOvertimeHour = floor($secondTotalOvertime);
                                $secondTotalOvertimeMin = (60*($secondTotalOvertime-$secondOvertimeHour));
                                $secondOvertimeMin = floor(60*($secondTotalOvertime-$secondOvertimeHour));
                                $secondOvertimeMin1 = floor($secondTotalOvertimeMin);
                                $secondOvertimeSec = floor(60*($secondTotalOvertimeMin-$secondOvertimeMin1));
                                $secondOvertime = new dateTime("$secondOvertimeHour:$secondOvertimeMin:$secondOvertimeSec");
                                $secondOvertimeTime = date_format($secondOvertime, "H:i:s");
                                /*---------------------END OF OVERTIME---------------------*/

                                /*---------------------UNDERTIME---------------------*/
                                $secs = strtotime($secondOvertimeTime)-strtotime("00:00:00");

                                $offsetDay = date("N", strtotime($offsetSetDate));
                                $shift = getOffsetShiftByUidAndDay($employeeUid, $offsetDay);

                                $shiftStart = $shift->start;
                                $shiftEnd = $shift->end;

                                $overt = 0;
                                    
                                $secondOut = date("H:i:s", strtotime($shiftStart)+$secs);

                                if(strtotime($secondOut) <= strtotime($shiftEnd)){
                                    $undertime = (strtotime($shiftEnd) - strtotime($secondOut)) / 3600;
                                }if(strtotime($secondOut) >= strtotime($shiftEnd)){
                                    $overt = (strtotime($secondOut) - strtotime($shiftEnd) / 3600);
                                }

                                $totalUndertime = $undertime;
                                $undertimeHour = floor($totalUndertime);
                                $totalUndertimeMin = (60*($totalUndertime-$undertimeHour));
                                $undertimeMin = floor(60*($totalUndertime-$undertimeHour));
                                $undertimeMin1 = floor($totalUndertimeMin);
                                $undertimeSec = floor(60*($totalUndertimeMin-$undertimeMin1));

                                if($undertimeMin >= 60){
                                    $undertimeMin = 0;
                                    $undertimeHour = $undertimeHour + 1;
                                }else{
                                    $undertimeMin = $undertimeMin;
                                }
                                $undertimes = "$undertimeHour:$undertimeMin:00";

                                $totalOvert = $overt;
                                $overtHour = floor($totalOvert);
                                $totalOvertMin = (60*($totalOvert-$overtHour));
                                $overtMin = floor(60*($totalOvert-$overtHour));
                                $overtMin1 = floor($totalOvertMin);
                                $overtSec = floor(60*($totalOvertMin-$overtMin1));
                                if($overtMin >= 60){
                                    $overtMin = 0;
                                    $overtHour = $overtHour + 1;
                                }else{
                                    $overtMin = $overtMin;
                                }
                                $overt = "$overtHour:$overtMin:00";
                                $totalWorked = $totalOvertime - $totalUndertime;
                                $totalWorked = abs($totalWorked);
                                /*---------------------END OF UNDERTIME---------------------*/

                                $response[] = array(
                                    "id" => $employeeUid,
                                    "inId" => 0,
                                    "outId" => 0,
                                    "prompt" => 6,
                                    "lastname" => strtoupper($lastname),
                                    "dates" => $date,
                                    "date" => date("M d, Y", strtotime($date)),
                                    "day" => $day,
                                    "in" => date("h:i:s A", strtotime($shiftStart)),
                                    "out" => date("h:i:s A", strtotime($secondOut)),
                                    "late" => "00:00:00",
                                    "tardiness" => "00:00:00",
                                    "overtime" => "00:00:00",
                                    "undertime" => date("H:i:s", strtotime($undertimes)),
                                    "work" => date_format($overtimes, "H:i:s"),
                                    "totalWorked" => $totalWorked,
                                    "totalLate" => "OFFSET",
                                    "totalOvertime" => "OFFSET",
                                    "totalUndertime" => $totalUndertime,
                                    "approveOTStatus" => "0",
                                    "location" => "--=--",
                                    "empNo" => $employeeUsername

                                );
                            }//end of if-else
                            
                        }//end of getting Offset Time IN
                    }else{
                        $response[] = array(
                            "id" => $employeeUid,
                            "inId" => 0,
                            "outId" => 0,
                            "prompt" => $prompt,
                            "lastname" => strtoupper($lastname),
                            "dates" => $date,
                            "date" => date("M d, Y", strtotime($date)),
                            "day" => $day,
                            "in" => "NO TIME IN",
                            "out" => "NO TIME OUT",
                            "late" => "--",
                            "tardiness" => "--",
                            "overtime" => "--",
                            "undertime" => "--",
                            "work" => "--",
                            "totalWorked" => "--",
                            "totalLate" => "--",
                            "totalOvertime" => "--",
                            "totalUndertime" => "--",
                            "approveOTStatus" => "0",
                            "location" => "--=--",
                            "empNo" => $employeeUsername
                        );
                    }// end of checking offset
                    break;

                case 1: //FOR PRESENT
                    $check = checkTimeInByEmpUidAndDate($employeeUid, $date);
                    if($check){
                        $timeInDetails = getTimeInByEmpUidAndDate($employeeUid, $date);
                    }else{
                        $timeInDetails = getTimeInByEmpUidAndDateNoLoc($employeeUid, $date);
                    }// end of checking

                    foreach($timeInDetails as $timeIn){
                        $timeInUid = $timeIn["time_log_uid"];
                        $timeInData = $timeIn["date_created"];
                        $timeInDate = date("Y-m-d", strtotime($timeInData));
                        $timeInDay = date("N", strtotime($timeInDate));
                        $timeInSession = $timeIn["session"];

                        $inLoc = "--";
                        $timeOutDetails = getTimeOutByEmpUidAndSessionNoLoc($employeeUid, $timeInSession);
                        $outLoc = "--";

                        $locations = getTimeInLocationByTimeUid($timeInUid);
                        if($locations){
                            $timeInLocation = $locations["name"];
                            $timeOutDetails = getTimeOutByEmpUidAndSession($employeeUid, $timeInSession);
                            $timeOutLocation = $timeOutDetails["name"];
                        }

                        $timeOutUid = $timeOutDetails["time_log_uid"];
                        $timeOutData = $timeOutDetails["date_created"];
                        $timeOutDate = date("Y-m-d", strtotime($timeOutData));

                        $timeInHour = date("H:i:s", strtotime($timeInData));
                        $timeOutHour = date("H:i:s", strtotime($timeOutData));

                        $shiftDetails = getShiftByTimeInUid($timeOutUid);
                        if(!$timeOutDetails || !$shiftDetails){
                            $response[] = array(
                                "id" => $id,
                                "inId" => $inId,
                                "outId" => "No Time Out!",
                                "prompt" => "",
                                "lastname" => strtoupper($lastname),
                                "dates" => $date,
                                "date" => date("M d, Y", strtotime($date)),
                                "day" => $day,
                                "in" => date("h:i:s A", strtotime($in)),
                                "out" => "No Time Out!",
                                "late" => "00:00:00",
                                "tardiness" => "",
                                "overtime" => "00:00:00",
                                "undertime" => "00:00:00",
                                "work" => "00:00:00",
                                "totalWorked" => "00:00:00",
                                "totalLate" => "00:00:00",
                                "totalOvertime" => "00:00:00",
                                "totalUndertime" => "00:00:00",
                                "approveOTStatus" => "",
                                "location" => $inLoc . "=--",
                                "empNo" => $empNo
                            );
                        }else{
                            $shiftStart = $shiftDetails->start;
                            $shiftEnd = $shiftDetails->end;
                            $grace = $shiftDetails->grace_period;

                            $shiftEnds = $shiftEnd;
                            $shiftStarts = $shiftStart;

                            if($grace != 0){
                                $dapatIn = date("H:i:s", strtotime("+$grace minutes", strtotime($shiftStart)));
                            }else{
                                $dapatIn = date("H:i:s", strtotime($shiftStart));
                            }

                            $dapatInHour = date("H:i:s", strtotime($dapatIn));

                            //pasted worked f //to be editted
                            if(strtotime($timeOutData) < strtotime($timeInData)){
                                $work = (strtotime($timeInData) - strtotime($out)) / 3600;
                            }else if(strtotime($timeOutData) > strtotime($timeInData)){
                                $work = (strtotime($timeOutData) - strtotime($timeInData)) / 3600;
                            }
                            //end pasted worked

                            //Get Shift
                            $shiftDuration = getEmployeeShiftDuration($employeeUid, $shiftStart, $shiftEnd, $timeInHour, $timeInUid);

                            if($work === $shiftDuration){
                                $totalWork = $shiftDuration;
                            }else if($work > $shiftDuration){
                                $totalWork = $shiftDuration;
                            }else if($work < $shiftDuration){
                                $totalWork = $work;
                            }//end of getting total work

                            if(strtotime($inn) >= strtotime($inHour)){
                                if($late === $lateCount){
                                    for($x=0; $x < count($inArray); $x++){
                                        if(in_array($empDate, $inArray[$x])){
                                            $getFirstIn[] = $inArray[$x];
                                        }//end of checking
                                    }//end of forloop
                                    // $inn = ($getFirstIn[0]["inHour"]);
                                    // $empDate = ($getFirstIn[0]["inDate"]);
                                    if($in1 === $out1){
                                        if(strtotime($inn) >= strtotime($afterBreak)){
                                            $lates = ((strtotime($inn) - strtotime($shiftStarts)) / 3600) - 1;
                                        }else{
                                            $lates = (strtotime($inn) - strtotime($shiftStarts)) / 3600;
                                        }
                                        /*==================== BOGZ ====================*/
                                        $dif = strtotime($outss)- strtotime($inn);
                                        if($dif<3600){
                                            $lates = 0;
                                        }
                                        /*==================== END ====================*/
                                    }else{
                                        $shiftStarts = $in1 . " " . $shiftStarts;
                                        $lates = (strtotime($in) - strtotime($shiftStarts)) / 3600; 
                                    }
                                }
                            }//end of comparison for late
                            /*END OF LATE FUNCTION*/
                        }
                    }//end of forloop

                    break;
            }

        }

    }

    echo jsonify($response);
     
});

$app->get("/count/time/data/:var", function($var){
    $param      = explode(".", $var);
    $uid        = $param[2];
    $startDate  = date('Y-m-d', strtotime($param[0]));
    $endDate    = date('Y-m-d', strtotime($param[1]));
    $startDates = strtotime($startDate);
    $endDates   = strtotime($endDate);   
    $count      = 0;
    $response   = array();
    for($i=$startDates; $i<=$endDates; $i+=86400){
        $count++;
    }

    $response = array(
        "count" => $count
    );
    echo jsonify($response);
});

$app->get("/get/leave/emp/counts/:var", function($var){
    $token = $var;
    $x     = leaveCounts();
    echo jsonify($x);
});

$app->get("/get/emp/cost/payslip/:var", function($var){
    $params    = explode(".", $var);
    $startDate = $params[0];
    $endDate   = $params[1];
    $emp       = $params[2];
    $uid       = $params[3];
    $summaries = incomeDetails($startDate, $endDate, $emp ,$uid);
    echo jsonify($summaries);
});

$app->post("/android/add/user/", function(){
    $name     = $_POST["name"];
    $email    = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $query    = ORM::forTable("android_user")->create();
        $query->name          = $name;
        $query->email_address = $email;
        $query->username      = $username;
        $query->password      = $password;
    $query->save();
});

$app->get("/employee/leave/count/:emp", function($emp){
    $response = array();
    $leave    = getEmpLeaveCountPagesByEmpUid($emp);

    $response = array();
    $year = date("Y");
    // foreach($leaves as $leave){
    if($leave){
        $leaveCountUid = $leave["emp_leave_count_uid"];
        $id = $leave["emp_uid"];
        $empNo = $leave["username"];
        $name = $leave["lastname"] . ", " . $leave["firstname"];
        $SL = $leave["SL"];
        $BL = $leave["BL"];
        $BV = $leave["BV"];
        $VL = $leave["VL"];
        $ML = $leave["ML"];
        $PL = $leave["PL"];
        // print_r($leave);

        $sickLeave = 0;
        $birthdayLeave = 0;
        $berLeave = 0;
        $vacLeave = 0;
        $noPay = 0;
        $matLeave = 0;
        $patLeave = 0;

        $leaves = getApprovedLeavesByEmpUidByYear($id, $year);
        foreach($leaves as $leave){
            $leaveCode = $leave["leave_code"];
            $leaveStart = $leave["start_date"];
            $leaveEnd = $leave["end_date"];

            switch($leaveCode){
                case "SL":
                    $leaveCount = getDaysOfWorkByDateRange($leaveStart, $leaveEnd);
                    $leaveCount = $leaveCount + 1;
                    $sickLeave += $leaveCount++;
                    break;
                case "BL":
                    $leaveCount = getDaysOfWorkByDateRange($leaveStart, $leaveEnd);
                    $leaveCount = $leaveCount + 1;
                    $birthdayLeave += $leaveCount++;
                    break;
                case "BV":
                    $leaveCount = getDaysOfWorkByDateRange($leaveStart, $leaveEnd);
                    $leaveCount = $leaveCount + 1;
                    $berLeave += $leaveCount++;
                    break;
                case "VL":
                    $leaveCount = getDaysOfWorkByDateRange($leaveStart, $leaveEnd);
                    $leaveCount = $leaveCount + 1;
                    $vacLeave += $leaveCount++;
                    break;
                case "W":
                    $leaveCount = getDaysOfWorkByDateRange($leaveStart, $leaveEnd);
                    $leaveCount = $leaveCount + 1;
                    $noPay += $leaveCount++;
                    break;
                case "ML":
                    $leaveCount = getDaysOfWorkByDateRange($leaveStart, $leaveEnd);
                    $leaveCount = $leaveCount + 1;
                    $matLeave += $leaveCount++;
                    break;
                case "PL":
                    $leaveCount = getDaysOfWorkByDateRange($leaveStart, $leaveEnd);
                    $leaveCount = $leaveCount + 1;
                    $patLeave += $leaveCount++;
                    break;
            }//end of switch
        }//end of getting leave

        // echo "$id = $SL = $sickLeave<br/>";

        $sLTotal = $SL - $sickLeave;
        if($sLTotal < 0){
            $sLTotal = 0;
        }
        $bLTotal = $BL - $birthdayLeave;
        if($bLTotal < 0){
            $bLTotal = 0;
        }
        $bVTotal = $BV - $berLeave;
        if($bVTotal < 0){
            $bVTotal = 0;
        }
        $vLTotal = $VL - $vacLeave;
        if($vLTotal < 0){
            $vLTotal = 0;
        }
        $mLTotal = $ML - $matLeave;
        if($mLTotal < 0){
            $mLTotal = 0;
        }
        $pLTotal = $PL - $patLeave;
        if($pLTotal < 0){
            $pLTotal = 0;
        }

        $response = array(
            "SL" => $sLTotal,
            "BL" => $bLTotal,
            "BV" => $bVTotal,
            "VL" => $vLTotal,
            "ML" => $mLTotal,
            "PL" => $pLTotal
        );
    }else{
        $response = array(
            "SL" => 0,
            "BL" => 0,
            "BV" => 0,
            "VL" => 0,
            "ML" => 0,
            "PL" => 0
        );
    }//end of getEmpLeaveCountPages function

    echo jsonify($response);
});

$app->post("/import/dtr/", function(){
    $row      = 1;
    $response = array();
    $data     = array();
    if (($handle = fopen($_FILES['attachment']['tmp_name'], "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num     = count($data);
            $empNo   = trim($data[0]);
            $empNo   = str_pad($empNo, 4, "0", STR_PAD_LEFT);
            $date    = trim($data[1]);
            $timeIn  = trim($data[2]);
            $timeOut = trim($data[3]);
            $session = xguid();
            $uid     = xguid();

            $timeIns  = date("H:i", strtotime(trim($data[2])));
            $timeOuts = date("H:i", strtotime(trim($data[3])));

            $date = date("Y-m-d", strtotime($date));

            $timeInComb  = $date . " " . $timeIn;
            $timeOutComb = $date . " " . $timeOut;

            $timeInDateTimeFormat  = date("Y-m-d H:i", strtotime($timeInComb));
            $timeOutDateTimeFormat = date("Y-m-d H:i", strtotime($timeOutComb));

            $checkEmpNo = usernameIsExistingUsingLike($empNo);//checking if empno ay nasa database
            if($checkEmpNo){//start of if-else
                $empUid = getUserEmpUidByLike($empNo); //pagkuha ng uid
                $day    = date("N", strtotime($date)); //pagkuha ng araw
                if($timeIn){//start of if-else pagcheck kung may time in
                    $shiftData = getOffsetShiftByUidAndDay($empUid, $day);
                    if($shiftData){//start of if-else pagcheck kung may shift
                        $shiftUid = $shiftData->shift_uid; //pagkuha ng uid ng shift
                        $check = checkUserHasTimeIn($empUid, $date);
                        if(!$check){//pagchcheck kapag wala pa sya sa database
                            addTimeIn($uid, $empUid, $shiftUid, $session, $timeInDateTimeFormat, $timeInDateTimeFormat); 
                            $getSession = getTimeLogByEmpUidAndDate($empUid, $date);
                            $session    = $getSession->session;//pagkuha ng session sa previous time in

                            if($timeOut){//start of if-else pagcheck kung may time out
                                addTimeOut(xguid(), $empUid, $shiftUid, $session, $timeOutDateTimeFormat, "", $timeOutDateTimeFormat);
                            }//end of if-else pagcheck kung may time out  
                            $prompt = 0;    
                            $row++;
                        }else{
                            removeDateFromTimeInLogByEmpAndDate($empUid, $date);
                            removeDateFromTimeOutLogByEmpAndDate($empUid, $date);
                            addTimeIn($uid, $empUid, $shiftUid, $session, $timeInDateTimeFormat, $timeInDateTimeFormat); 
                            $getSession = getTimeLogByEmpUidAndDate($empUid, $date);
                            $session    = $getSession->session;//pagkuha ng session sa previous time in

                            if($timeOut){//start of if-else pagcheck kung may time out
                                addTimeOut(xguid(), $empUid, $shiftUid, $session, $timeOutDateTimeFormat, "", $timeOutDateTimeFormat);
                            }//end of if-else pagcheck kung may time out  
                            $prompt = 1;    
                            $row++;
                        }
                        
                    }//end of if-else pagcheck kung may shift
                }//end of if-else pagcheck kung may time in
            }//end of if-else
        }
        fclose($handle);
    }
    $response = array(
        "result" => $prompt,
        "count" => $row
    );
    echo jsonify($response);
});

/*FOR DTR*/
$app->get("/get/location/data/", function(){
    // $param = explode(".", $var);
    // $token = $param[0];
    $response = array();

    $location = getLocation();
    foreach($location as $locations){
        $response[] = array(
            "locUid"      => $locations->uid,
            "name"        => $locations->name,
            "fingerprint" => $locations->fingerprint,
            "status"      => $locations->status
        );
    }

    echo jsonify($response);
});

$app->post("/check/user/", function(){
    $username = $_POST["username"];
    $check    = checkIfUserExisted($username);
    // print_r($check);
    $count    = $check->count;
    $emp      = $check->emp_uid;
    $status   = $check->status;
    if($username){
        if($status === "0"){
            $response = array(
                "prompt" => 0,
                "username" => ""
            );
        }else{
            if($count >= 1){
                $response = array(
                    "prompt" => 1,
                    "username" => $emp
                );
            }else{
                $response = array(
                    "prompt" => 0,
                    "username" => ""
                );
            }
        }
    }else{
        $response = array(
            "prompt" => 2,
            "username" => ""
        );
    }
    

    echo jsonify($response);
});

$app->get("/check/time/log/:username", function($username){

    if(checkPreviousClockLog($username)){
        if(checkIfClockIn($username)){
            $type = 1;
            $action = "OUT";
        }

        if(checkIfClockOut($username)){
            $type = 0;
            $action = "IN";
        }
    }else{
        $type = 0;
        $action = "IN";
    }

    $emp1       = getEmployeeDetailsByUid($username);
    $lastname   = $emp1["lastname"];
    $middlename = $emp1["middlename"];
    $firstname  = $emp1["firstname"];

    $name = $firstname . " " . $middlename . " " . $lastname;
    

    $response = array(
        "action" => $action,
        "name"   => $name
    );

    echo jsonify($response);
});

$app->post("/employee/timesheet/new/", function(){
    date_default_timezone_set("Asia/Manila");
    $emp          = $_POST["username"];
    $len          = strlen($emp);
    $password     = $_POST["password"];
    $timeLogUid   = xguid();
    $session      = xguid();
    $uid          = xguid();
    $response     = array();
    // $shift     = $_POST["shift"];
    $type         = "";
    $locUid       = $_POST["locUid"];
    $device       = $_POST["device"];
    
    //ClientJS
    $fprint       = $_POST["clientjs"];
    
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");

    if($len <= 5){
        $userId = getUserId($emp);
        if(!$userId){
            $response = array(
                "verified" => 0
            );
        }

        $encryption = "AES-256-CBC";
        //$uxPassword = sha1(Base32::decode($_POST["password"]));
		$uxPassword = sha1(base64_decode($_POST["password"].salt()));
		
        $secretKey  = sha1($emp . $uxPassword);
        $uniqueKey  = getUniqueKey($userId);
        if ($uniqueKey == null) {
            $ivSize    = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
            $uniqueKey = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        }
        $password = openssl_encrypt($uxPassword, $encryption, $secretKey, 0, $uniqueKey);
        
        if (validUserAccount($emp, $uxPassword)) {
            $checkUser = checkIfUserExisted($emp);
            $username  = $checkUser["emp_uid"];
            $count     = $checkUser["count"];
            if(!$username){
                $userPrompt = 2;
                $response   = array(
                    "errorMessage" => "ACCOUNT DOESNT EXIST!",
                    "errorStatus"  => 2,
                    "hour"         => "",
                    "name"         => "",
                    "action"       => ""
                );
            }else{
                if(checkPreviousClockLog($username)){
                    if(checkIfClockIn($username)){
                        $type    = 1;
                        $logType = "OUT";
                        $action  = "CLOCK OUT ";
                        $session = getPreviousTimeSession($username);
                    }

                    if(checkIfClockOut($username)){
                        $type    = 0;
                        $logType = "IN";
                        $action  = "CLOCK IN ";
                    }
                }else{
                    $type    = 0;
                    $logType = "IN";
                    $action  = "CLOCK IN ";
                }
                $emp1       = getEmployeeDetailsByUid($username);
                $lastname   = $emp1["lastname"];
                $middlename = $emp1["middlename"];
                $firstname  = $emp1["firstname"];

                $name  = $firstname . " " . $middlename . " " . $lastname;
                $sDate = date("Y-m-d");
                $sTime = date("H:i:s");
                $day   = date("N", strtotime($dateCreated));
                
                $shifts = getShiftUidInRules($username, $day);
                $hour   = date("h:i:s A", strtotime($dateCreated));
                
                //ClientJS
                // clientJSlog($emp, date("Y-m-d h:i:s A"), $fprint, $logType);

                if($shifts == "0"){
                    $response = array(
                        "errorMessage" => "LAGYAN NG RULE!",
                        "errorStatus"  => 1,
                        "hour"         => $hour,
                        "name"         => utf8_decode($name),
                        "action"       => $action
                    );
                }else{
                    $shift           = $shifts->shift;
                    $getLocationData = getLocationsByUid($locUid);
                    $locationUid     = $getLocationData["uid"];
                    $locName         = $getLocationData["name"];

                    dummyGenerateTimelog($timeLogUid, $username, $session ,$shift, $type, $locationUid, $dateCreated, $dateModified);
                    addTimeStampData($emp, $sDate, $sTime, $logType);
                    addEventLog($username, $sDate, $sTime, $logType, $locName, $locationUid, $dateCreated, $dateModified);

                    $response = array(
                        "errorMessage" => 0,
                        "errorStatus"  => "",
                        "hour"         => $hour,
                        "name"         => utf8_decode($name),
                        "action"       => $action
                    );
                }
            }
        } else {
            $response = array(
                "errorMessage" => "Invalid username or password!",
                "errorStatus"  => 2,
                "hour"         => "",
                "name"         => "",
                "action"       => ""
            );
        }
    }else{
        $userId = getUserIdByEmpUid($emp);
        if(!$userId){
            $response = array(
                "verified" => 0
            );
        }
        $encryption = "AES-256-CBC";
        //$uxPassword = sha1(Base32::decode($_POST["password"]));
		$uxPassword = sha1(base64_decode($_POST["password"].salt()));
		
        $secretKey  = sha1($emp . $uxPassword);
        $uniqueKey  = getUniqueKey($userId);
        if ($uniqueKey == null) {
            $ivSize    = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
            $uniqueKey = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        }
        $password = openssl_encrypt($uxPassword, $encryption, $secretKey, 0, $uniqueKey);
        
        if (validEmpUserAccount($emp, $uxPassword)) {
            $checkUser = checkEmployeeByUid($emp);
            if(!$checkUser){
                $userPrompt = 2;
                $response = array(
                    "errorMessage" => "ACCOUNT DOESNT EXIST!",
                    "errorStatus"  => 2,
                    "hour"         => "",
                    "name"         => "",
                    "action"       => ""
                );
            }else{
                if(checkPreviousClockLog($emp)){
                    if(checkIfClockIn($emp)){
                        $type    = 1;
                        $logType = "OUT";
                        $action  = "CLOCK OUT ";
                        $session = getPreviousTimeSession($emp);
                    }

                    if(checkIfClockOut($emp)){
                        $type    = 0;
                        $logType = "IN";
                        $action  = "CLOCK IN ";
                    }
                }else{
                    $type    = 0;
                    $logType = "IN";
                    $action  = "CLOCK IN ";
                }
                $emp1       = getEmployeeDetailsByUid($emp);
                $lastname   = $emp1["lastname"];
                $middlename = $emp1["middlename"];
                $firstname  = $emp1["firstname"];
                
                $name       = $firstname . " " . $middlename . " " . $lastname;
                $sDate      = date("Y-m-d");
                $sTime      = date("H:i:s");
                $day        = date("N", strtotime($dateCreated));
                
                $shifts     = getShiftUidInRules($emp, $day);
                $hour       = date("h:i:s A", strtotime($dateCreated));
                if($shifts == "0"){
                    $response = array(
                        "errorMessage" => "LAGYAN NG RULE!",
                        "errorStatus"  => 1,
                        "hour"         => $hour,
                        "name"         => utf8_decode($name),
                        "action"       => $action
                    );
                }else{
                    $shift           = $shifts->shift;
                    $getLocationData = getLocationsByUid($locUid);
                    $locationUid     = $getLocationData["uid"];
                    $locName         = $getLocationData["name"];
                    
                    $uname           = getEmloyeeNumberByEmpUid($emp);

                    dummyGenerateTimelog($timeLogUid, $emp, $session ,$shift, $type, $locationUid, $dateCreated, $dateModified);
                    addTimeStampData($uname, $sDate, $sTime, $logType);
                    addEventLog($emp, $sDate, $sTime, $logType, $locName, $locationUid, $dateCreated, $dateModified);

                    $response = array(
                        "errorMessage" => 0,
                        "errorStatus"  => "",
                        "hour"         => $hour,
                        "name"         => utf8_decode($name),
                        "action"       => $action
                    );
                }
            }
        } else {
            $response = array(
                "errorMessage" => "ACCOUNT DOESNT EXIST!",
                "errorStatus"  => 2,
                "hour"         => "",
                "name"         => "",
                "action"       => ""
            );
        }
    }

    echo jsonify($response);
    // addShift($shiftUid,$emp,$startTime,$duration,$dateCreated, $dateModified);
    //dummyDataSetSchedule($uid,$timeLogUid,$emp,$startTime,$days,$dateCreated, $dateModified);
});

$app->post("/add/attempt/", function(){
    $emp          = $_POST["username"];
    $locUid       = $_POST["locCode"];
    $password     = $_POST["password"];
    $device       = $_POST["device"];
    $ip           = $_POST["ip"];
    $attemptUid   = xguid();
    $dateCreated  = date("Y-m-d H:i:s");
    $dateModified = date("Y-m-d H:i:s");
    
    //ClientJS
    $fprint       = $_POST["clientjs"];
    
    $userId       = getUserId($emp);
    if(!$userId){
        $response = array(
            "verified" => 0
        );
    }

    $encryption = "AES-256-CBC";
    //$uxPassword = sha1(Base32::decode($_POST["password"]));
	$uxPassword = sha1(base64_decode($_POST["password"].salt()));
	
    $secretKey  = sha1($emp . $uxPassword);
    $uniqueKey  = getUniqueKey($userId);
    if ($uniqueKey == null) {
        $ivSize    = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $uniqueKey = mcrypt_create_iv($ivSize, MCRYPT_RAND);
    }
    $password = openssl_encrypt($uxPassword, $encryption, $secretKey, 0, $uniqueKey);
    
    // print_r(validUserAccount($emp, $uxPassword));
    if (validUserAccount($emp, $uxPassword)) {
        $checkUser = checkIfUserExisted($emp);
        $username  = $checkUser["emp_uid"];
        $count     = $checkUser["count"];
        if(!$username){
            $userPrompt = 2;
            $response = array(
                "errorMessage" => "ACCOUNT DOESNT EXIST!",
                "errorStatus"  => 2,
                "hour"         => "",
                "name"         => "",
                "action"       => ""
            );
        }else{
            if(checkPreviousClockLog($username)){
                if(checkIfClockIn($username)){
                    $type    = 1;
                    $logType = "OUT";
                    $action  = "CLOCK OUT ";
                    $session = getPreviousTimeSession($username);
                }

                if(checkIfClockOut($username)){
                    $type    = 0;
                    $logType = "IN";
                    $action  = "CLOCK IN ";
                }
            }else{
                $type    = 0;
                $logType = "IN";
                $action  = "CLOCK IN ";
            }
            $emp1       = getEmployeeDetailsByUid($username);
            $lastname   = $emp1["lastname"];
            $middlename = $emp1["middlename"];
            $firstname  = $emp1["firstname"];
            
            $name       = $firstname . " " . $middlename . " " . $lastname;
            $sDate      = date("Y-m-d");
            $sTime      = date("H:i:s");
            $day        = date("N", strtotime($dateCreated));
            
            $hour       = date("h:i:s A", strtotime($dateCreated));
            $shifts     = getShiftUidInRules($username, $day);
            
            //ClientJS
            // clientJSlog($username, date("Y-m-d h:i:s A"), $fprint, $logType);

            if($shifts == "0"){
                $response = array(
                    "errorMessage" => "LAGYAN NG RULE!",
                    "errorStatus"  => 1,
                    "hour"         => $hour,
                    "name"         => utf8_decode($name),
                    "action"       => $action
                );
            }else{
                addAttemptLog($attemptUid, $username, $sDate, $sTime, $logType, $locUid, $device, $ip, $dateCreated, $dateModified);

                $response = array(
                    "errorMessage" => 0,
                    "errorStatus"  => "",
                    "hour"         => $hour,
                    "name"         => utf8_decode($name),
                    "action"       => $action
                );
            }
        }
    } else {
        $response = array(
            "errorMessage" => "ACCOUNT DOESNT EXIST!",
            "errorStatus"  => 2,
            "hour"         => "",
            "name"         => "",
            "action"       => ""
        );
    }

    echo jsonify($response);
});

$app->get("/timezone/get/", function(){
  $timezoneOffset = getOffsetByTimeZone("Asia/Manila");
  $date           = date("M d, Y");
  $response       = array(
    "offset" => $timezoneOffset,
    "date"   => $date
  );
  echo jsonify($response);
});

$app->post("/backup/database/", function(){
    date_default_timezone_set("Asia/Manila");

    $startDate = $_POST["startDate"];
    $endDate   = $_POST["endDate"];
    EXPORT_TABLES("localhost","root","root","hris", $startDate, $endDate);

    //https://github.com/tazotodua/useful-php-scripts
    function EXPORT_TABLES($host,$user,$pass,$name, $startDate, $endDate, $tables=false, $backup_name=false ){
        $mysqli = new mysqli($host,$user,$pass,$name);
        $mysqli->select_db($name);
        $mysqli->query("SET NAMES 'utf8'");
        $queryTables = $mysqli->query('SHOW TABLES');
        while($row = $queryTables->fetch_row()) {
            // $target_tables[] = $row[0]; 
        }   
        // echo json_encode($target_tables);
        $target_tables = array(
            "leave_requests",
            "overtime_requests",
            "time_request",
            "time_log"
        );

        // echo json_encode($target_table7

        $content = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSET time_zone = \"+00:00\";\r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--Database: `".$name."`\r\n\r\n\r\n";
        foreach($target_tables as $table){
            $result        = $mysqli->query("SELECT * FROM ".$table." WHERE date(date_created) >= '" .$startDate. "' AND date(date_created) <= '" . $endDate . "'");    
            $fields_amount = $result->field_count;  
            $rows_num      = $mysqli->affected_rows;    
            $res           = $mysqli->query('SHOW CREATE TABLE '.$table);   
            $TableMLine    = $res->fetch_row();
            $content       .= "\n\n".$TableMLine[1].";\n\n";
            for ($i = 0, $st_counter = 0; $i < $fields_amount; $i++, $st_counter=0) {
                while($row = $result->fetch_row())  { //when started (and every after 100 command cycle):
                    if ($st_counter%100 == 0 || $st_counter == 0 )  {
                        $content .= "\nINSERT INTO ".$table." VALUES";
                    }
                        $content .= "\n(";
                        for($j=0; $j<$fields_amount; $j++){ 
                            $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); 

                            if (isset($row[$j])){
                                if($j == 0){
                                    $content .= '""' ; 
                                }else{
                                    $content .= '"'.$row[$j].'"' ; 
                                }
                            }else{
                                $content .= '""';
                            }
                            if ($j<($fields_amount-1)){
                                $content.= ',';
                            }       
                        }
                        $content .=")";
                    //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
                    if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) {
                        $content .= ";";
                    }else{
                        $content .= ",";
                    }   
                    $st_counter=$st_counter+1;
                }
            } $content .="\n\n\n";
        }
        $content .= "\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
        $backup_name = "Humano (".date('Y-m-d H:i:s A').").sql";
        header('Content-Type: application/octet-stream');   
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"".$backup_name."\"");  
        echo $content; 
        exit;
    }
});

$app->get("/change/date/format/:date", function($date){
  $date = date("Y-m-d", strtotime($date));

  $response = array($date);
  echo jsonify($response);
});

$app->post("/update/time/request/status/batch/", function(){
    $response     = array();
    $timeInUid    = xguid();
    $timeOutUid   = xguid();
    $session      = xguid();
    $uid = $_POST["uid"];
    $count = $_POST["count"];
    $timeRequestData = getOffsetTimeRequestByUid($uid);
    $timeIn       = $timeRequestData["time_in"];
    $timeIn       = date("Y-m-d H:i", strtotime($timeIn));
    $typeIn       = 0;
    $logIn        = "IN";
    $timeOut      = $timeRequestData["time_out"];
    $timeOut      = date("Y-m-d H:i", strtotime($timeOut));
    $typeOut      = 1;
    $logOut       = "OUT";
    $timeDate     = $timeRequestData["date_request"];
    $reason       = $timeRequestData["reason"];
    $admin        = $_POST["admin"];

    $reqStatus    = $_POST["status"];
    $status       = $timeRequestData["status"];
    $employee     = $timeRequestData["emp_uid"];
    $day          = date("N", strtotime($timeDate));
    $dateModified = date("Y-m-d H:i:s");
    $sTimeIn      = date("H:i:s", strtotime($timeIn));
    $sTimeOut     = date("H:i:s", strtotime($timeOut));

    $username     = getEmployeeUsernameByEmpUid($employee);

    $emp          = getEmployeeDetailsByUid($admin);
    $lastname     = $emp->lastname;
    $firstname    = $emp->firstname;
    $middlename   = $emp->middlename;

    $name         = $firstname . " " . $middlename . " " . $lastname;

    function getInitials($name){
    $words = explode(" ",$name);
    $inits = '';
    foreach($words as $word){
        $inits.=strtoupper(substr($word,0,1));
    }
    return $inits;  
    }

    $user = getInitials($name);

    if(strtotime($timeIn) <= strtotime($timeOut)){
        $valid = true;
    }else{
        $valid = false;
    }

    $checkRequest = checkPayrollSchedBeforeRequest($timeDate);
    $type         = getUserTypeByEmpUid($admin);

    if($type == "Administrator"){
        if($valid){
            $rule = getShiftUidInRules($employee, $day);
            if($rule){
                $shift = $rule["shift"];
                if($reqStatus == "Approved"){
                    $user1 = $user;
                    $user2 = "";
                    $check = checkTimeDateByEmpUid($employee, $timeDate);
                    if($check){
                        removeDateFromTimeInLogByEmpAndDate($employee, $timeDate);
                        removeDateFromTimeOutLogByEmpAndDate($employee, $timeDate);
                        addTimeSheetIn($timeInUid, $employee, $shift, $session, $typeIn, $timeIn, $status);
                        addTimeSheetOut($timeOutUid, $employee, $shift, $session, $typeOut, $timeOut, $status);
                        editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                        $response = array(
                            "prompt" => 3,
                            "count" => $count
                        );
                    }else{
                        removeDateFromTimeInLogByEmpAndDate($employee, $timeDate);
                        removeDateFromTimeOutLogByEmpAndDate($employee, $timeDate);
                        addTimeSheetIn($timeInUid, $employee, $shift, $session, $typeIn, $timeIn, $status);
                        addTimeSheetOut($timeOutUid, $employee, $shift, $session, $typeOut, $timeOut, $status);
                        editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                        $response = array(
                            "prompt" => 0,
                            "count" => $count
                        );
                    }
                }else if($reqStatus == "Certified"){
                    $user2 = $user;
                    $user1 = "";
                    $check = checkTimeDateByEmpUid($employee, $timeDate);
                    if($check){
                        $response = array(
                            "prompt" => 3,
                            "count" => $count
                        );
                    }else{
                        editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                        $response = array(
                            "prompt" => 0,
                            "count" => $count
                        );
                    }
                }
            }else{
                $response = array(
                    "prompt" => 2,
                    "count" => $count
                );
            }

            $dateModified = date("Y-m-d H:i:s");

            if($reqStatus == "Approved"){
                $user1 = $user;
                $user2 = "";
                $check = checkTimeDateByEmpUid($employee, $timeDate);
                if($check){
                    $response = array(
                        "prompt" => 3,
                        "count" => $count
                    );
                }else{
                    editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                    $response = array(
                        "prompt" => 1,
                        "count" => $count
                    );
                }
            }else if($reqStatus == "Certified"){
                $user2 = $user;
                $user1 = "";
                editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                $response = array(
                    "prompt" => 1,
                    "count" => $count
                );
            }else{
                $user2 = "";
                $user1 = "";
                editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                $response = array(
                    "prompt" => 1,
                    "count" => $count
                );
            }
        }else{
            $response = array(
                "prompt" => 4,
                "count" => $count
            );
        }
    }else{
        if($checkRequest["prompt"]){
            if($valid){
                $rule = getShiftUidInRules($employee, $day);
                if($rule){
                    $shift = $rule["shift"];
                    if($reqStatus == "Approved"){
                        $user1 = $user;
                        $user2 = "";
                        editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                        $response = array(
                            "prompt" => 0,
                            "count" => $count
                        );
                    }else if($reqStatus == "Certified"){
                        $user2 = $user;
                        $user1 = "";
                        editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                        $response = array(
                            "prompt" => 0,
                            "count" => $count
                        );
                    }
                }else{
                    $response = array(
                        "prompt" => 2,
                        "count" => $count
                    );
                }

                $dateModified = date("Y-m-d H:i:s");

                if($reqStatus == "Approved"){
                    $user1 = $user;
                    $user2 = "";
                    $check = checkTimeDateByEmpUid($employee, $timeDate);
                    if($check){
                        $response = array(
                            "prompt" => 3,
                            "count" => $count
                        );
                    }else{
                        editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                        $response = array(
                            "prompt" => 1,
                            "count" => $count
                        );
                    }
                }else if($reqStatus == "Certified"){
                    $user2 = $user;
                    $user1 = "";
                    editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                    $response = array(
                        "prompt" => 1,
                        "count" => $count
                    );
                }else{
                    $user2 = "";
                    $user1 = "";
                    editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status);
                    $response = array(
                        "prompt" => 1,
                        "count" => $count
                    );
                }
            }else{
                $response = array(
                    "prompt" => 4,
                    "count" => $count
                );
            }
        }else{
            if($valid){
                $response = array(
                    "prompt" => 5,
                    "count" => $count
                );
            }else{
                $response = array(
                    "prompt" => 4,
                    "count" => $count
                );
            }
        }
    }

    echo jsonify($response);
});

$app->get("/get/resigned/employees/", function(){
    $response = array();
    $year = date("Y");

    $datas = getResignedEmployeesByYear($year);
    foreach ($datas as $data) {
        $employeeDetails = getEmployeeDetailsByUid($data->emp_uid);

        if($employeeDetails){
            $lastnames = utf8_decode($employeeDetails->firstname) . "_" . " ";
            $words = explode("_", $lastnames);
            $name = "";

            foreach ($words as $w) {
              $name .= $w[0];
            }

            $lastname = $name . ". " . utf8_decode($employeeDetails->lastname);
            
        }//end of getEmployeeDetailsByUid Function

        $employmentStatusDetails = getEmploymentStatusByUid($data->employment_status_uid);
        $response[] = array(
            "employmentStatusUid" => $data->type_uid,
            "employeeName" => $lastname,
            "employeeNo" => $employeeDetails->username,
            "dateHired" => date("m-d-y", strtotime($data->date_hired)),
            "dateResigned" => date("m-d-y", strtotime($data->date_resigned)),
            "employmentStatus" => $employmentStatusDetails->name,
            "dateCreated" => date("m-d-y", strtotime($data->date_created)),
            "dateModified" => date("m-d-y", strtotime($data->date_modified))
        );
    }

    echo jsonify($response);
});

$app->get("/get/new/hired/employees/", function(){
    $response = array();	
	$year = date("Y");

    $datas = getNewHiredEmployeesByYear($year);
	foreach ($datas as $data) {
        $employeeDetails = getEmployeeDetailsByUid($data->emp_uid);
		
        if($employeeDetails){
            $lastnames = utf8_decode($employeeDetails->firstname) . "_" . " ";
            $words = explode("_", $lastnames);
            $name = "";

            foreach ($words as $w) {
              $name .= $w[0];
            }

            $lastname = $name . ". " . utf8_decode($employeeDetails->lastname);
        
			$employmentStatusDetails = getEmploymentStatusByUid($data->employment_status_uid);
			$response[] = array(
				"employmentStatusUid" => $data->type_uid,
				"employeeName" => $lastname,
				"employeeNo" => $employeeDetails->username,
				"dateHired" => date("M. d, Y", strtotime($data->date_hired)),
				"dateResigned" => date("m-d-y", strtotime($data->date_resigned)),
				"employmentStatus" => $employmentStatusDetails->name,
				"dateCreated" => date("m-d-y", strtotime($data->date_created)),
				"dateModified" => date("m-d-y", strtotime($data->date_modified))
			);
		}
    }

    echo jsonify($response);
});

$app->get("/get/employee/birthdays/", function(){
    $response = array();
    // $latestDate = "1550-".date("m-d");
    $latestDate = date("m-d");
    $futureDate = date("m-d", strtotime("+3 week"));

    // echo "$latestDate = $futureDate";
    $datas = getUpcomingBirthdays($latestDate, $futureDate);
    
    foreach ($datas as $data) {
        $employeeDetails = getEmployeeDetailsByUid($data->emp_uid);
        $username = "";
        if($employeeDetails){
            $lastnames = utf8_decode($employeeDetails->firstname) . "_" . " ";
            $words = explode("_", $lastnames);
            $name = "";

            foreach ($words as $w) {
              $name .= $w[0];
            }

            $lastname = $name . ". " . utf8_decode($employeeDetails->lastname);
            $username = $employeeDetails->username;
            
        }//end of getEmployeeDetailsByUid Function

        $bday = date("Y-m-d", strtotime($data->bday));
        $today = date("Y-m-d");

        $bday = new DateTime($bday);
        $today = new DateTime($today);

        $age = $today->diff($bday);
        $age = ($age->y) + 1;
		
		$dept = read_employee_department_by_uid($data->emp_uid);
		$department = null;
		if($dept) {
			$department = $dept['group'];
		}

        $response[] = array(
            "empUid" => $data->emp_uid,
            "employeeName" => $lastname,
            "employeeNo" => $username,
			"department" => $department,
            "age" => $age,
            "birthday" => date("M. d, Y", strtotime($data->bday)),
            "dateCreated" => date("m-d-y", strtotime($data->date_created)),
            "dateModified" => date("m-d-y", strtotime($data->date_modified))
        );
    }

    echo jsonify($response);
});
/*---------------------------------------------END FOR PRINTING---------------------------------------------*/

#======================================== Krono Timekeeping - Top ========================================= #

$app->post("/template/create/", function() {
    /*$uid = guid();
    $username = $_POST["username"];
    $plainPassword = $_POST["password"];
    $password = mcEncrypt(sha1($plainPassword), $salt);
    $firstname = ucwords($_POST["firstname"]);
    $middlename = ucwords($_POST["middlename"]);
    $lastname = ucwords($_POST["lastname"]);
    $contactNo = $_POST["contactNo"];*/
	$success = 0;
	
    if($valid){
        /*newUser($uid, $username, $password, $firstname, $middlename, $lastname, $contactNo, $email, $question, $answer, $group, $courier, $active);*/
        $success = 1;
    }

    $response = array(
        "success" => $success
    );

    echo jsonify($response);
});

$app->get("/template/read/:var", function($var) {
	$param = explode(".", $var);
    if (count($param) === 1) {
        $results = "";
        $list = array();

        foreach($results as $result){
            $list[] = array(
                "uid" => $result->uid
            );
        } 

        $response = array(
            "list" => $list
        );

        echo jsonify($response);
    }
});

$app->post("/template/update/:var", function($var) {
	$param = explode(".", $var);
    if (count($param) === 1) {
        /*$uid = $_POST["uid"];
        $firstname = ucwords($_POST["firstname"]);
        $middlename = ucwords($_POST["middlename"]);
        $lastname = ucwords($_POST["lastname"]);
        $contactNo = $_POST["contactNo"];
        $email = $_POST["email"];
        $active = $_POST["active"];
        updateUser($uid, $firstname, $middlename, $lastname, $contactNo, $email, $active);*/
    }
});

$app->get("/read/employees/:var", function($var){
    $param     = explode(".", $var);
    $token     = $param[0];
    $employees = getActiveEmployees();
    foreach ($employees as $employee) {
        $response[] = array(
            "uid"             => $employee->emp_uid,
            "firstname"       => utf8_decode($employee->firstname),
            "middlename"      => utf8_decode($employee->middlename),
            "lastname"        => utf8_decode($employee->lastname),
			"gender"        => utf8_decode($employee->gender),
			"marital"        => utf8_decode($employee->marital),
			"bday"        => date("M. d, Y", strtotime($employee->bday)), //utf8_decode($employee->bday), 
            "empNo" => $employee->username
        );       
    }
    echo jsonify($response);
});

#======================================== Krono Timekeeping - Bottom ====================================== #

$app->get("/read/newsfeed/:var", function($var) {
	$param     = explode(".", $var);
    $token     = $param[0];
	$response = array();
	
	if($token) {
		$newsfeeds = read_news_feed();
		foreach($newsfeeds as $news) {
			//$author = read_employee_name_by_uid($news->user_uid);
			$author = read_employee_lname_by_uid($news->user_uid);
			$response[] = array(
			"uid" => $news->uid,
			"author" => $author,
			"content" => $news->content,
			"pubdate" => date("F d, Y H:i:s", strtotime($news->date_created)),
			//"daysago" => time_elapsed_string(date("Y-m-d H:i:s", strtotime($news->date_created)), true)
			"daysago" => date("l jS \of F Y h:i:s A", strtotime($news->date_created))
			);			
		}
	}
	
	echo jsonify($response);
});

/* Upload Files TOP */
$app->post("/files/new/:var", function($var) use ($app) {
    $param = explode(".", $var);
    if(count($param) == 3){
        $uid = $param[0];
        $reference = $param[1];
        $tempFilename = $_FILES["attachment"]["name"];
        $extension = pathinfo($tempFilename, PATHINFO_EXTENSION);
        $newFilename = preg_replace("/[^a-zA-Z0-9]+/", "", $tempFilename);
        $newFilename = str_replace($extension, "_" . md5(xguid()), $newFilename) . "." . $extension;
        $filename = $_FILES["attachment"]["name"];
        $mimeType = $_FILES["attachment"]["type"];
        $size = $_FILES["attachment"]["size"];
        $dateCreated = date("Y-m-d H:i:s");
		
        $path = "files/" . $newFilename;

        if(!file_exists($path)){
            move_uploaded_file($_FILES["attachment"]["tmp_name"], $path);
            updateFileStatusByReference($reference, 0);
            newFile($uid, $reference, $filename, $newFilename, $path, $mimeType, $size, $dateCreated);
        }
    }
});

$app->get("/files/x/reference/get/:var", function($var){
    $param = explode(".", $var);
    $reference = $param[0];
    $file = getFile($reference);
    if($file){
        if(file_exists($file->path)){
            $path = $file->path;
            header("Content-type: {$file->mime_type}");
            readfile($path);
            exit();
        }else{
            header("Content-type: image/png");
            readfile("files/x-person.png");
            exit();
        }
    }else{
        header("Content-type: image/png");
        readfile("files/x-person.png");
        exit();
    }
});
/* Upload Files BOTTOM */

/* New Applicant  */
$app->get("/applicant/view/:var", function($var) {
	$param = explode(".", $var);
	$response = array();
	if(count($param) === 1) {
		$results = read_applicants_by_desc();
		foreach($results as $result) {
			$response[] = array(
				"uid" => $result->uid,
				"name" => $result->name,
				"course" => $result->course,
				"school" => $result->school,
				"type" => $result->type,
				"status" => $result->application_status,
				"sdate" => date("M. d, Y", strtotime($result->date_created))
			);
		}
		echo jsonify($response);
	}
	else if(count($param) === 2){
		$uid = $param[0];
		$token = $param[1];
		//echo $uid;
		$results = read_applicants_by_uid($uid);
		foreach($results as $result) {
			$fuid = $result->files;
			$file = getFileByUid($fuid);
			$path = $file["path"];
			$response[] = array(
				"uid" => $result->uid,
				"name" => $result->name,
				"course" => $result->course,
				"school" => $result->school,
				"address" => $result->address,
				"contact" => $result->contact_no,
				"email" => $result->email_address,
				"type" => $result->type,
				"status" => $result->application_status,
				"files" => $path
			);
		}
		echo jsonify($response);
	}
});

$app->post("/applicant/new/:var", function($var){
    $param = explode(".", $var);
    if (count($param) === 1) {
        $uid = $_POST["uid"];
        $name = $_POST["name"];		
		$course = $_POST["course"];
		$school = $_POST["school"];
		$address = $_POST["address"];
		$contact_no = $_POST["contact_no"];		
		$email_address = $_POST["email_address"];
		$type = $_POST["applicant_type"];
		$files = $_POST["files"];
		$application_status = $_POST["application_status"];		
		$date_created = date("Y-m-d H:i:s");
		$date_modified = date("Y-m-d H:i:s");		
        $success = 0;
        if(validateApplicantsName($name) === 0) {
            create_applicants($uid, $name, $course, $school, $address, $contact_no, $email_address, $type, $files, $application_status, $date_created, $date_modified);
            if(validateApplicants($uid)){
                $success = 1;
            }
        }
        $response = array(
            "success" => $success
        );
        echo jsonify($response);
    }
});

$app->post("/applicant/edit/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];
	$success = 0;
	if(count($param) === 2) {
		$name = $_POST["name"];		
		$course = $_POST["course"];
		$school = $_POST["school"];
		$address = $_POST["address"];
		$contact_no = $_POST["contact_no"];		
		$email_address = $_POST["email_address"];
		$type = $_POST["applicant_type"];
		$application_status = $_POST["application_status"];
		
		update_applicants($uid, $name, $course, $school, $address, $contact_no, $email_address, $type, $application_status, 1);
		
		$success = 1;
	}
	
	$response = array(
		"success" => $success
	);
	echo jsonify($response);
});

/* Department */
$app->post("/department/new/:var", function($var) {
	$param = explode(".", $var);
    $response = array();
	if(count($param) === 1) {
		$success = 0;
		$department = $_POST["department"];
		if(validateDepartmentName($department) === 0) {
			create_department($department);
			$success = 1;
		}
	}
	$response = array(
		"success" => $success
	);
	echo jsonify($response);
});

$app->post("/department/edit/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];
    $response = array();
	$success = 0;
	$error = 0;
	if(validateDepartment($uid) && count($param) === 2) {
		$department = trim($_POST["department"]);
		$status = $_POST["status"];
		update_department($uid, $department, $status);
		$success = 1;
	}
	$response = array(
		"success" => $success
	);
	echo jsonify($response);
});

$app->get("/departments/view/:var", function($var) {
	$param = explode(".", $var);
    $response = array();
	if(count($param) === 1) {
		$results = read_department();
		foreach($results as $result) {
			$stat = $result->status;
			$status = "Enabled";
			if($stat === "0") {
				$status = "Disabled";
			}
			$response[] = array(
				"uid" => $result->uid,
				"department" => $result->group,
				"status" => $status
			);
		}
		echo jsonify($response);
	}
});

$app->get("/departments/view/edit/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];
    $response = array();
	if(count($param) === 2) {
		$results = read_department_by_uid($uid);
		$success = 1;
		foreach($results as $result) {
			$response[] = array(
				"uid" => $result->uid,
				"department" => $result->group,
				"status" => $result->status
			);
		}
		echo jsonify($response);
	}
});

# Work Schedule 
$app->post("/schedule/new/:var" , function($var){
    $param = explode(".", $var);
    $response = array();
	
	$shed = $_POST["sched"];
	$fromDate = $_POST["fromDate"];
	$toDate = $_POST["toDate"];
	$shift = $_POST["shift"];
	$type = $_POST["type"];
	
    $verified = 0;
	$date_error = 0;
	
    if(count($param) === 1) {		
		$sdate = strtotime($fromDate);
		$edate = strtotime($toDate);
		$oneday = 60 * 60 * 24;
		if($edate < $sdate) {
			$date_error = 1;
		}
		else {
			for($i=$sdate;$i<=$edate;$i = $i + $oneday) {
				$date = date("Y-m-d", $i);
				create_work_schedule($type, $date, $shift);
			}			
			$verified = 1;
		}
    }
	
    $response[] = array(
        "verified" => $verified,
        "date_error" => $date_error,
		"hours" => $oneday
    );

    echo jsonify($response);
});

$app->get("/schedule/get/data/:var" , function($var){
    $response = array();
    $schedules = read_work_schedule_desc();
    foreach ($schedules as $schedule) {
        $uid   = $schedule->shift_uid;
        $shift = read_shift_by_uid($uid);
		$name = $shift['name'];
		$response[] = array(
            "schedUid" => $schedule->uid,
            "type" => $schedule->schedule_type,
            "date" => date("F d, Y", strtotime($schedule->schedule_date)), 
            "shift" => $schedule->shift_uid,
			"name" => $name
        );
    }
    echo jsonify($response);
});

$app->get("/workschedule/:var" , function($var){
    $list = array();
    $response = array();

    $verified = 0;
    $schedules = read_work_schedule_by_uid($var);
    foreach ($schedules as $schedule) {
        $verified = 1;
        $list[] = array(
            "schedUid" => $schedule->uid,
            "type" => $schedule->schedule_type,
            "date" => $schedule->schedule_date, 
            "shift" => $schedule->shift_uid,
            "verified" => $verified          
        );
    }

    // $response[] = array(
    //     "result" => $list,
    //     "verified" => $verified
    // );

    echo jsonify($list);
});

$app->get("/get/schedule/data/:uid", function($uid){
    $response = array();
    $schedules     = read_work_schedule_by_uid($uid);
    if($schedule){
        foreach($schedules as $schedule) {
			$response = array(
				"schedUid" => $schedule->uid,
				"type"     => $schedule->schedule_type,
				"date"    => $schedule->schedule_date,
				"shift"    => $schedule->shift_uid
			);
		}
    }

    echo jsonify($response);
});

$app->post("/update/schedule/:var", function($var){
    $param        = explode(".", $var);
    $schedUid     = $param[0];
    $response     = array();
    if(count($param) === 1) {
        $verified     = 0;
        $type         = $_POST['type'];
        $date         = $_POST['date'];
        $shift        = $_POST['shift'];
        $dateModified = date("Y-m-d H:i:s");

        $validate = validateWorkSchedule($schedUid);
        if ($validate) {
            $verified = 1;
            update_work_schedule($schedUid, $type, $date, $shift, $dateModified);
        };

        echo jsonify($response);
    }

});

/* Work Scheduled - Top */
# id, uid, schedule_uid, schedule_type, from_date, to_date, shift_uid, shift_type, date_created, date_modified, status 
$app->get("/work_schedule/view/:var", function($var) {	
	$param = explode(".", $var);
	$response = array();
	$list = array();
	$verified = 0;	
	if(count($param) === 1) {		
		$results = read_work_scheduled();
		foreach($results as $result) {
			$uid   = $result->shift_uid;
			$shift = read_shift_by_uid($uid);
			$name = $shift['name'];
			$types = read_schedule_type_limit_one($result->shift_type);
			$type = $types["type"];
			
			if($result->scheduled_type === "Individual") {
				$group = read_employee_lastname_by_uid($result->scheduled_uid);
			}
			else {
				$department = read_department_by_uid_one($result->scheduled_uid);
				$group = $department["group"];
			}
			
			$list[] = array(
				"uid" => $result->uid,
				"schedule_uid" => $result->scheduled_uid,
				"schedule_type" => $result->scheduled_type,
				"from_date" => date("F d, Y", strtotime($result->from_date)),
				"to_date" => date("F d, Y", strtotime($result->to_date)),
				"shift_uid" => $name, //$result->shift_uid,
				"type" => $type,
				"status" => $result->status,
				"group" => $group
			);
		}
		$verified = 1;
	}	
	$response = array(
		"list" => $list,
		"success" => $verified
	);	
	echo jsonify($response);	
});

$app->post("/work_schedule/new/:var", function($var) {	
	$param = explode(".", $var);
	$response = array();
	$verified = 0;
	$date_error = 0;
	
	$schedule_uid = $_POST["schedule_uid"];	
	$schedule_type = $_POST["schedule_type"];
	$from_date = $_POST["from_date"];
	$to_date = $_POST["to_date"];
	$shift_uid = $_POST["shift_uid"];
	$shift_type = $_POST["shift_type"];
	
	if(count($param) === 1) {
		$sdate = strtotime($from_date);
		$edate = strtotime($to_date);
		$oneday = 60 * 60 * 24;
		if($edate < $sdate) {
			$date_error = 1;
		}
		else {
			$work_schedule_uid = xguid();
			create_work_scheduled($work_schedule_uid, $schedule_uid, $schedule_type, $from_date, $to_date, $shift_uid, $shift_type);
			if($schedule_type === "Group") {
				$departments = read_emp_department_by_department_uid($schedule_uid);
				foreach($departments as $dept) {
					$emp_uid = $dept->emp_uid;
					for($i=$sdate;$i<=$edate;$i = $i + $oneday) {
						$date = date("Y-m-d", $i);
						create_emp_scheduled($work_schedule_uid, $date, $emp_uid); 
					}
				}
			}
			else {
				for($i=$sdate;$i<=$edate;$i = $i + $oneday) {
					$date = date("Y-m-d", $i);
					$emp_uid = $schedule_uid;
					create_emp_scheduled($work_schedule_uid, $date, $emp_uid); 
				}
			}			
			
			$verified = 1;
		}
	}
	
	$response[] = array(
		"verified" => $verified,
		"date_error" => $date_error
	);
	
	echo jsonify($response);
	
});

$app->get("/work_schedule/view/edit/:var", function($var) {
	# id, uid, schedule_uid, schedule_type, from_date, to_date, shift_uid, shift_type, date_created, date_modified, status
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];
    $response = array();
	if(count($param) === 2) {
		$results = read_work_scheduled_by_uid($uid);
		$success = 1;
		foreach($results as $result) {
			$response[] = array(
				"uid" => $result->uid,
				"schedule_uid" => $result->scheduled_uid,
				"schedule_type" => $result->scheduled_type,
				"from_date" => date("Y-m-d", strtotime($result->from_date)),
				"to_date" => date("Y-m-d", strtotime($result->to_date)),
				"shift_uid" => $result->shift_uid,
				"shift_type" => $result->shift_type,
				"status" => $result->status
			);
		}
		echo jsonify($response);
	}
});

$app->post("/work_schedule/edit/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];
    $response = array();
	$verified = 0;
	$date_error = 0;
	if(validateWorkScheduled($uid) && count($param) === 2) {
		$schedule_uid = $_POST["schedule_uid"];	
		$schedule_type = $_POST["schedule_type"];
		$from_date = date("Y-m-d", strtotime($_POST["from_date"]));
		$to_date = date("Y-m-d", strtotime($_POST["to_date"]));
		$shift_uid = $_POST["shift_uid"];
		$shift_type = $_POST["shift_type"];
		
		$sdate = strtotime($_POST["from_date"]);
		$edate = strtotime($_POST["to_date"]);
		$oneday = 60 * 60 * 24;
		if($edate < $sdate) {
			$date_error = 1;
		}
		else {
			update_work_scheduled($uid, $schedule_uid, $schedule_type, $from_date, $to_date, $shift_uid, $shift_type);
			delete_emp_scheduled($uid);
			if($schedule_type === "Group") {
				$departments = read_emp_department_by_department_uid($schedule_uid);
				foreach($departments as $dept) {
					$emp_uid = $dept->emp_uid;
					for($i=$sdate;$i<=$edate;$i = $i + $oneday) {
						$date = date("Y-m-d", $i);
						create_emp_scheduled($uid, $date, $emp_uid); 
					}
				}
			}
			else {
				for($i=$sdate;$i<=$edate;$i = $i + $oneday) {
					$date = date("Y-m-d", $i);
					$emp_uid = $schedule_uid;
					create_emp_scheduled($uid, $date, $emp_uid); 
				}
			}
			
			$verified = 1;
		}
	}
	
	$response[] = array(
		"verified" => $verified,
		"date_error" => $date_error
	);
	
	echo jsonify($response);
});

$app->get("/work_schedule/employee/view/:var", function($var) {	
	$param = explode(".", $var);
	$response = array();
	$list = array();
	$verified = 0;	
	if(count($param) === 1) {		
		$results = read_work_scheduled();
		foreach($results as $result) {
			if($result->scheduled_type === "Individual") {
				$emp_uid = $result->scheduled_uid;
				$fullname = read_employee_name_by_uid($emp_uid);
				$uid   = $result->shift_uid;
				$shift = read_shift_by_uid($uid);
				$name = $shift['name'];
				$types = read_schedule_type_limit_one($result->shift_type);
				$type = $types["type"];
				$list[] = array(
					"uid" => $result->uid,
					"schedule_uid" => $result->scheduled_uid,
					"schedule_type" => $result->scheduled_type,
					"from_date" => date("F d, Y", strtotime($result->from_date)),
					"to_date" => date("F d, Y", strtotime($result->to_date)),
					"shift_uid" => $name,
					"type" => $type,
					"emp_name" => $fullname,
					"status" => $result->status
				);
			}
		}
		$verified = 1;
	}	
	$response = array(
		"list" => $list,
		"success" => $verified
	);	
	echo jsonify($response);	
});

$app->get("/work_schedule/group/view/:var", function($var) {	
	$param = explode(".", $var);
	$response = array();
	$list = array();
	$verified = 0;	
	if(count($param) === 1) {		
		$results = read_work_scheduled();
		foreach($results as $result) {
			$dept_uid = $result->scheduled_uid;
			$department = read_department_by_uid_one($dept_uid);
			$department = $department["group"];
			$uid   = $result->shift_uid;
			$shift = read_shift_by_uid($uid);
			$name = $shift['name'];
			$types = read_schedule_type_limit_one($result->shift_type);
			$type = $types["type"];
			if($result->scheduled_type === "Group") {
				$list[] = array(
					"uid" => $result->uid,
					"schedule_uid" => $result->scheduled_uid,
					"schedule_type" => $result->scheduled_type,
					"from_date" => date("F d, Y", strtotime($result->from_date)),
					"to_date" => date("F d, Y", strtotime($result->to_date)),
					"shift_uid" => $name,
					"type" => $type,
					"department" => $department,
					"status" => $result->status
				);
			}
		}
		$verified = 1;
	}	
	$response = array(
		"list" => $list,
		"success" => $verified
	);	
	echo jsonify($response);	
});

$app->post("/work_schedule/delete/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];
    $response = array();
	$success = 0;
	
	if(count($param) === 2) {
		$valid = validateWorkScheduled($uid);
		if($valid) {		
			$emp_sched = read_work_scheduled_by_uid_one($uid);
			$work_schedule_uid = $emp_sched->work_schedule_uid;
			delete_emp_scheduled($work_schedule_uid);
			delete_work_scheduled($uid);	
			$success = 1;
		}
		else {
			$success = 0;
		}
	}
	
	$response = array(
		"success" => $success
	);
	
	echo jsonify($response);
});

/* Work Scheduled - Bottom */

/* Shift */
$app->get("/shift/get/:var", function($var) {
	# id shift_uid name start end grace_period batch date_created date_modified status
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];
	$verified = 0;	
	$response = array();
	$list = array();
	if(count($param) === 2) {
		$results = read_shift_by_uid($uid);
		foreach($results as $result) {
			$list[] = array(
				"uid" => $result->shift_uid,
				"name" => $result->name,
				"start" => $result->start,
				"end" => $result->end,
				"gperiod" => $result->grace_period,
				"status" => $result->status
			);
		}
		$verified = 1;
	}	
	$response[] = array(
		"list" => $list,
		"verified" => $verified
	);	
	echo jsonify($response);
});

/* Employee Department - Top */
$app->post("/employee/department/add/:var", function($var){
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];
	$success = 0;	
	$response = array();
	if(count($param) === 2) {
		$dept = $_POST["department"];
		$position = $_POST["position"];
		create_emp_department($dept, $position, $uid);
		$success = 1;
	}
	$response = array(
		"success" => $success
	);	
	echo jsonify($response);
});

$app->get("/employee/departments/view/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];
	$response = array();
	$list = array();
	$verified = 0;	
	if(count($param) === 2) {
		$results = read_emp_department_by_uid($uid);
		foreach($results as $result) {
			$dept = read_department_by_uid_one($result->department_uid);
			$name = $dept["group"];
			$list[] = array(
				"uid" => $result->uid,
				"dept" => $result->department_uid,
				"department" => $name,
				"position" => $result->designation,
				"emp_uid" => $result->emp_uid,
				"status" => $result->status,
				"position" => $result->designation
			);
		}
		$verified = 1;
	}
	$response = array(
		"list" => $list,
		"verified" => $verified
	);	
	echo jsonify($response);
});

$app->post("/employee/department/edit/:var", function($var){
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];
	$success = 0;	
	$response = array();
	if(count($param) === 2) {
		$dept = $_POST["department"];
		$position = $_POST["position"];
		$status = 1;
		update_emp_department($uid, $dept, $position, $status);
		$success = 1;
	}
	$response = array(
		"success" => $success
	);	
	echo jsonify($response);
});

/* Employee Department - Bottom */

/* Schedule Type - Top */
# id uid type date_created date_modified status
$app->get("/schedule_type/view/:var", function($var) {	
	$param = explode(".", $var);
	$response = array();
	$list = array();	
	if(count($param) === 1) {		
		$results = read_schedule_type();
		foreach($results as $result) {
			$response[] = array(
				"uid" => $result->uid,
				"type" => $result->type
			);
		}
	}	
	echo jsonify($response);	
});
/* Schedule Type - Bottom */

/* Timekeeping Summary - Top */
$app->get("/timekeeping/summary/:var", function($var) {
	$param = explode(".", $var);
	$sdate = $param[0];
	$edate = $param[1];
	$costcenter = $param[2];
	$token = $param[3];
	
	$response = array();
	$list = array();
	$success = 0;
	$error = 0;
	if(count($param) === 4) {
		$timein = strtotime($sdate);
		$timeout = strtotime($edate);
		if($timein > $timeout) {
			$error = 1;
		}
		else {
			$timelogs = getEmployeeTimeLog($sdate, $edate, $costcenter);			
			$uid = xguid();
			foreach($timelogs as $timelog) {
				$emp_uid = $timelog->emp_uid;
				$name = read_employee_lastname_by_uid($emp_uid); //echo $name . "</br>";
				$workHour = "00:00:00";
				$workHours = "00:00:00";
				$present = 0;
				$presents = 0;
				$myAbsent = 0;
				$myLate = "00:00:00";
				$myOvertime = "00:00:00";
				$myUndertime = "00:00:00";
				$myTardy = "00:00:00";
				$dayspresent = 0;
				$myTardyMin = 0;
				$myTardyMins = 0;
				$tardyMins = 0;
				$myAbsentMin = 0;
				
				$leaveCount = 0;
				$holidayCount = 0;
				$dayPresent = 0;
				$dayAbsent = 0;
				
				$restDays = 0;
				
				for($i = $timein;$i<=$timeout;$i = $i + 60 * 60 * 24) {
					
					$date = date("Y-m-d", $i); //echo $date . " ";
					$ins = getTimeIn($emp_uid, $date);					
					$tin = $ins["date_created"];
					$session = $ins["session"];
					$shift_uid = $ins["shift_uid"];
					
					$shifts = getShiftByUid($shift_uid);
					$gperiod = $shifts["grace_period"];
					$shift_in = $shifts["start"];
					$shift_out = $shifts["end"];
					
					$late = "";
					$undertime = "";
					$overtime = "";
					
					if($gperiod) {
						$gperiod = $shifts["grace_period"];
						$gp = $date." ".$shift_in;
						$gp = date("Y-m-d H:".$gperiod.":s", strtotime($gp));
					}
					else {
						$gperiod = $shifts["grace_period"];
						$gp = $date." ".$shift_in;
						$gp = date("Y-m-d H:i:s", strtotime($gp));
					}
					
					if($tin) {
						$time_in = date("H:i", strtotime($tin));
						$tin = date("Y-m-d H:i:s", strtotime($tin));
					}
					else {
						$time_in = "00:00";
						//$tin = "00:00:00";
					}
					
					$out = getTimeOut($emp_uid, $session);	
					$tout = $out["date_created"];
					if($tout) {
						$time_out = date("H:i", strtotime($tout));
						$tout = date("Y-m-d H:i:s", strtotime($tout));
					}
					else {
						$time_out = "00:00";
						//$tout = "00:00:00";
					}
					
					$shift_type = get_schedule_type($date, $emp_uid);
					if($shift_type) {
						if($shift_type == "Flexible") {
							$late = "00:00:00";
						}
						else {
							$timeIn = $tin;
							$shiftIn = $date." ".$shift_in;					
							$late = getLate($timeIn, $shiftIn, $gp);
						}
					}
					else {						
						$timeIn = $tin;
						$shiftIn = $date." ".$shift_in;					
						$late = getLate($timeIn, $shiftIn, $gp);
					}
					
					/*$timeIn = $tin;
					$shiftIn = $date." ".$shift_in;					
					$late = getLate($timeIn, $shiftIn, $gp);*/
					
					$base_salary = get_employee_salary($emp_uid, "base_salary");	
					$pay_period = get_employee_salary($emp_uid, "pay_period");
					
					//echo $pay_period . "</br>";
					if($pay_period == "Monthly") {
						$holiday = checkHolidayByDate($date);
						if($holiday) {
							//echo $holiday . "</br>";
							$holidayCount = $holidayCount + 1;
						}
					}
					
					$timeOut = $tout;
					$shiftOut = $date." ".$shift_out;
					$undertime = getUndertime($timeOut, $shiftOut);	
					$overtime = getOvertime($timeOut, $shiftOut);	
					
					$ins =  $tin ?: "00:00:00";
					$outs = $tout ?: "00:00:00";
					$workHour = workHours($outs, $ins);
					$workHour = $workHour ?: "00:00:00";
					$workHours= addTime($workHours, $workHour);	 //echo $workHours . "</br> ";
					
					$dpresent = 0;
					$present = workHour($tout, $tin);
					$present = ( $present * ( (60/9)/60) );
					$present = floatval($present); //echo $present . " ";
					
					if($present >= 1) {
						$dpresent = 1;
					}
					else if ($present > 0.6 && $present <= 0.9) {
						$dpresent = 1;
					}
					else if ($present >= 0.4 && $present <= 0.6) {
						$dpresent = 0.5;
					}
					else {
						//$myAbsent = $myAbsent + 1; 
						$restDay = getRestDay();
						foreach($restDay as $rest) {
							$days = trim($rest->name);
							if(date("l", strtotime($date)) === $days) {
								//$myAbsent = $myAbsent - 1;	
								$restDays = $restDays + 1;
								//echo "Rest Day " . date("l", strtotime($date)) . " " . $restDays . "</br>";
							}
						}
						//echo $myAbsent . " ";
					}
					//echo $present . " ";
					//echo $dpresent . " ";
					//echo $dpresent . " </br>";
					//$dayspresent = floatval($dayspresent) + floatval($dpresent); //echo $dayspresent . " ";
					$dayspresent = $dayspresent + $dpresent; //echo $dayspresent . " ";
					
					$myLate = addTime($myLate, $late); //echo $myLate . " ";
					$myOvertime = addTime($myOvertime, $overtime);
					
					//$undertime = str_replace("-", "", $undertime);
					
					$n = strchr($undertime,"-");
					$m = strlen($n); //echo $m . " ";
					if($m > 0) {
						$undertime = str_replace("-", "", $undertime);
						$myUndertime = addTime($myUndertime, trim($undertime));
						//$myUndertime = addTime($myUndertime, "00:00:00");
					}
					else {
						$undertime = str_replace("-", "", $undertime);
						$myUndertime = addTime($myUndertime, "00:00:00");
					}
					
					//echo $myUndertime . " ";
					
					$myTardy = addTime($myLate, $myUndertime); //echo $myTardy . " ";
					
					$leave = get_leave_request_emp_uid_and_date($emp_uid, $date); //getLeaveRequestsByEmpUidAndDate($emp_uid, $date);
					if($leave) {
						$leave_types = getLeaveTypeDataByUid($leave->leaves_types_uid);
						$leave_code = trim($leave_types->leave_code);
						//echo $leave_code . " ";
						
						switch($leave_code) {
							case "VL":
								$leaveCount = $leaveCount + 1;
								break;
							case "SL":
								$leaveCount = $leaveCount + 1;
								break;
							case "BL":
								$leaveCount = $leaveCount + 1;
								break;
							case "BV":
								$leaveCount = $leaveCount + 1;
								break;
							case "PL":
								$leaveCount = $leaveCount + 1;
								break;
							case "ML":
								$leaveCount = $leaveCount + 1;
								break;
							case "P":
								$leaveCount = $leaveCount + 1;
								break;
							case "W":
								$myAbsent = $myAbsent + 1;
								break;
							case "AB":
								$myAbsent = $myAbsent + 1;
								break;
						}
						
						/*if($leave_code != "W" || $leave_code != "AB" || $leave_code != "OT") {
							$leaveCount = $leaveCount + 1;
							//echo $leave_code . " " . $leaveCount;
						}
						else if($leave_code === "W" || $leave_code === "AB") {
							$myAbsent = $myAbsent + 1;
						}*/
					}
					
					# Absent 
					
					
					$absences = get_absent_emp_uid_and_date($emp_uid, $date);
					
					//echo "</br>";
				}
				
				//echo "Total Leave " . $leaveCount . "</br>";
				//echo "Total Absent " . $myAbsent . "</br>";
				//echo "Total Rest Day " . $restDays . "</br>";
				
				//echo $holidayCount . "</br>";
				$dayAbsent = $myAbsent; //- $holidayCount - $leaveCount;
				
				if($dayAbsent<=0) {
					//$dayAbsent = 0;
				}
				
				//$dayspresent = $dayspresent + $leaveCount; //echo $dayspresent . "</br>";
				//$myAbsent = $myAbsent - $leaveCount;
				$dayPresent = $dayspresent + $holidayCount + $leaveCount;
				$tardyMins = tardyMinutes($date, $date." ".$myTardy);
				$myAbsentMin = $dayAbsent * 60 * 8;
				$tardiness = $tardyMins + $myAbsentMin;
				
				//echo "Present: " . $dayspresent; // . " " . "Absent: " . $myAbsent . "</br>";
				//echo "</br>";
				
				$list[] = array(
					"emp_uid" => $emp_uid,
					"empName" => $name,
					"daysPresent" => $dayPresent ?: "0",
					"hoursWork" => $workHours,
					"daysAbsent" => $dayAbsent,
					"daysAbsentMin" => $myAbsentMin,
					"late" => $myLate,
					"overTime" => $myOvertime,
					"underTime" => $myUndertime,
					"totalTardy" => $myTardy,
					"tardyMin" => $tardyMins,
					"totalTardiness" => $tardiness,
					"holidayCount" => $holidayCount,
					"leaveCount" => $leaveCount
				);
			}
			echo jsonify($list);
			$success = 1;
		}
	}
});

$app->get("/timekeeping/summary/process/:var", function($var) {
	$param = explode(".", $var);
	$sdate = $param[0];
	$edate = $param[1];
	$costcenter = $param[2];
	$token = $param[3];
	
	$response = array();
	$success = 0;
	$error = 0;
	if(count($param) === 4) {
		$timein = strtotime($sdate);
		$timeout = strtotime($edate);
		if($timein > $timeout) {
			$error = 1;
		}
		else {
			$timelogs = getEmployeeTimeLog($sdate, $edate, $costcenter);			
			$uid = xguid();
			foreach($timelogs as $timelog) {
				$emp_uid = $timelog->emp_uid;
				$name = read_employee_lastname_by_uid($emp_uid); //echo $name . "</br>";
				$workHour = "00:00:00";
				$workHours = "00:00:00";
				$present = 0;
				$presents = 0;
				$myAbsent = 0;
				$myLate = "00:00:00";
				$myOvertime = "00:00:00";
				$myUndertime = "00:00:00";
				$myTardy = "00:00:00";
				$dayspresent = 0;
				$myTardyMin = 0;
				$myTardyMins = 0;
				$tardyMins = 0;
				$myAbsentMin = 0;
				
				$holidayCount = 0;
				$leaveCount = 0;
				$dayAbsent = 0;
				$dayPresent = 0;
				
				/* Holiday */
				$rd = 0;
				$rh = 0;
				$sh = 0;
				$shrd = 0;
				$rhrd = 0;
				
				/* Overtime */
				$ot = 0;
				$rdot = 0;
				$shot = 0;				
				$shrdot = 0;
				$rhot = 0;
				$rhrdot = 0;
				
				/* Night shift */
				$nd = 0;
				
				$restDays = 0;
				$leaves = 0;
				
				for($i = $timein;$i<=$timeout;$i = $i + 60 * 60 * 24) {
					
					$date = date("Y-m-d", $i);
					$ins = getTimeIn($emp_uid, $date);					
					$tin = $ins["date_created"];
					$session = $ins["session"];
					$shift_uid = $ins["shift_uid"];
					
					$shifts = getShiftByUid($shift_uid);
					$gperiod = $shifts["grace_period"];
					$shift_in = $shifts["start"];
					$shift_out = $shifts["end"];
					
					$late = "";
					$undertime = "";
					$overtime = "";
					
					if($gperiod) {
						$gperiod = $shifts["grace_period"];
						$gp = $date." ".$shift_in;
						$gp = date("Y-m-d H:".$gperiod.":s", strtotime($gp));
					}
					else {
						$gperiod = $shifts["grace_period"];
						$gp = $date." ".$shift_in;
						$gp = date("Y-m-d H:i:s", strtotime($gp));
					}
					
					if($tin) {
						$time_in = date("H:i", strtotime($tin));
						$tin = date("Y-m-d H:i:s", strtotime($tin));
					}
					else {
						$time_in = "00:00";
					}
					
					$out = getTimeOut($emp_uid, $session);	
					$tout = $out["date_created"];
					if($tout) {
						$time_out = date("H:i", strtotime($tout));
						$tout = date("Y-m-d H:i:s", strtotime($tout));
					}
					else {
						$time_out = "00:00";
					}
					
					$shift_type = get_schedule_type($date, $emp_uid);
					if($shift_type) {
						if($shift_type == "Flexible") {
							$late = "00:00:00";
						}
						else {
							$timeIn = $tin;
							$shiftIn = $date." ".$shift_in;					
							$late = getLate($timeIn, $shiftIn, $gp);
						}
					}
					else {						
						$timeIn = $tin;
						$shiftIn = $date." ".$shift_in;					
						$late = getLate($timeIn, $shiftIn, $gp);
					}
					
					/*$timeIn = $tin;
					$shiftIn = $date." ".$shift_in;					
					$late = getLate($timeIn, $shiftIn, $gp);*/
					
					$pay_period = get_employee_salary($emp_uid, "pay_period"); //echo $pay_period . "</br>";
					if($pay_period == "Monthly") {
						$holiday = checkHolidayByDate($date);
						if($holiday) {
							$holidayCount = $holidayCount + 1; //echo $holiday . "</br>";
						}
					}
					
					$timeOut = $tout;
					$shiftOut = $date." ".$shift_out;
					$undertime = getUndertime($timeOut, $shiftOut);	
					$overtime = getOvertime($timeOut, $shiftOut);	
					
					$ins =  $tin ?: "00:00:00";
					$outs = $tout ?: "00:00:00";
					$workHour = workHours($outs, $ins);
					$workHour = $workHour ?: "00:00:00";
					$workHours= addTime($workHours, $workHour);	
					
					$dpresent = 0;
					$present = workHour($tout, $tin);
					$present = ( $present * ( (60/9)/60) );
					$present = floatval($present);
					
					if($present >= 1) {
						$dpresent = 1;
					}
					else if ($present > 0.6 && $present <= 0.9) {
						$dpresent = 1;
					}
					else if ($present >= 0.4 && $present <= 0.6) {
						$dpresent = 0.5;
					}
					else {						
						$restDay = getRestDay();
						foreach($restDay as $rest) {
							$days = trim($rest->name);
							if(date("l", strtotime($date)) === $days) {	
								$restDays = $restDays + 1;
							}
						}
					}
					$dayspresent = floatval($dayspresent) + floatval($dpresent);
					
					$myLate = addTime($myLate, $late);
					$myOvertime = addTime($myOvertime, $overtime);
					
					//echo $undertime . " ";
					
					$n = strchr($undertime,"-");
					$m = strlen($n);
					if($m > 0) {
						$undertime = str_replace("-", "", $undertime);
						$myUndertime = addTime($myUndertime, $undertime);
					}
					else {						
						$myUndertime = addTime($myUndertime, "00:00:00");
					}
					
					//echo $myLate . " " . $myUndertime . " ";
					$myTardy = addTime($myLate, $myUndertime); //echo $myTardy . "</br> ";
					
					//$leave = getLeaveRequestsByEmpUidAndDate($emp_uid, $date);
					//if($leave) {
					//	$leave_types = getLeaveTypeDataByUid($leave->leaves_types_uid);
					//	$leave_code = $leave_types->leave_code;
					//	if($leave_code != "W" || $leave_code != "AB" || $leave_code != "OT") {
					//		$leaves = $leaves - 1;
					//	}
					//}				
					
					$holiday_type = read_holiday_requests_by_emp_uid_date($emp_uid, $date);
					if($holiday_type) {
						$holiday_rate = getRatesByUid($holiday_type->type);
						switch($holiday_rate->holiday_code) {
							case "RD":
								$rd = $rd + $holiday_type->hours;
								break;
							case "RH":
								$rh = $rh + $holiday_type->hours;
								break;
							case "SH":
								$sh = $sh + $holiday_type->hours;
								break;
							case "SHRD":
								$shrd = $shrd + $holiday_type->hours;
								break;
							case "RHRD":
								$rhrd = $rhrd + $holiday_type->hours;
								break;
						}
					}
					
					$overtime_type = read_overtime_requests_by_emp_uid_date($emp_uid, $date);
					if($overtime_type) {
						$overtime_rate = getOvertimeTypeByUid($overtime_type->type);
						switch($overtime_rate->overtime_type_code) {
							case "OT":
								$ot = $ot + $overtime_type->hours;
								break;
							case "RDOT":
								$rdot = $rdot + $overtime_type->hours;
								break;
							case "SHOT":
								$shot = $shot + $overtime_type->hours;
								break;
							case "SHRDOT":
								$shrdot = $shrdot + $overtime_type->hours;
								break;
							case "RHOT":
								$rhot = $rhot + $overtime_type->hours;
								break;
							case "RHRDOT":
								$rhrdot = $rhrdot + $overtime_type->hours;
								break;
						}
					}
					
					/* Night Shift START */
					$nightH = 0;
                    $nightDiffStatus = 0;
					
					//$outHour = $time_out; //$outArray["outHour"];
					//$outHour = date("H:i:s", strtotime($time_out)); 
					
					// if( date("H", strtotime( $tin ) ) === "12" ) {
						
					// }
					
					$outHour = date("Y-m-d H:i:s", strtotime($tout));
					$nightDiffStart = "22:00:00";
					$nightDiffEnd = "06:00:00";
					$nightDiffStarts = date("Y-m-d", strtotime($tin . "-0 day")) . " $nightDiffStart"; 
					//$nightDiffEnds = date("Y-m-d", strtotime($tin . "+1 day")) . " $nightDiffEnd"; 

					$afterSix = "18:00:00";
					$nightShifts = date("Y-m-d", strtotime($tin . "-0 day")) . " $afterSix"; 
					
					if( date("Y-m-d", strtotime($outHour)) == date("Y-m-d", strtotime($tin))) {
						if( strtotime($outHour) < strtotime($nightDiffStarts) ) {
							$nightDiffEnds = date("Y-m-d", strtotime($tin . "+0 day")) . " $nightDiffEnd";
						}else {
							$nightDiffEnds = date("Y-m-d", strtotime($tin . "+1 day")) . " $nightDiffEnd";
						}	
					} else {
						$nightOut = date("Y-m-d", strtotime($tin . "+1 day"));
						// if( strtotime($nightOut) == strtotime(date("Y-m-d", strtotime($outHour))) ) {
							// $nightDiffEnds = $outHour;
						// }
						// else {
							// $nightDiffEnds = date("Y-m-d", strtotime($tin . "+1 day")) . " $nightDiffEnd";
						// }	

						if( strtotime($nightOut) == strtotime(date("Y-m-d", strtotime($outHour))) ) {
							$nightShiftOutEnds = date("Y-m-d H:i:s", strtotime($nightOut . " " . $nightDiffEnd) ); 
							if( strtotime( $outHour ) <= strtotime( $nightShiftOutEnds ) ) {
								$nightDiffEnds = $outHour; 
							}
							else {
								$nightDiffEnds = $nightShiftOutEnds; 
							}
						}
						else {			
							$nightDiffEnds = date("Y-m-d", strtotime($tin . "+1 day")) . " $nightDiffEnd"; 
						}
					}
					
					if( strtotime($outHour) >= strtotime($nightDiffStarts) && strtotime($outHour) <= strtotime($nightDiffEnds) ) {
						if( strtotime($tin) <= strtotime($nightDiffStarts) ) {
							$nightDiffStatus = workHour($outHour, $nightDiffStarts);
							$nd = $nd + $nightDiffStatus;
						}
						else {
							$nightDiffStatus = workHour($outHour, $tin);
							$nd = $nd + $nightDiffStatus;
						}	
					}else if( strtotime($tin) >= strtotime($nightShifts) ) {
						$nightDiffStatus = workHour($nightDiffEnds, $nightDiffStarts);
						$nd = $nd + $nightDiffStatus;
					}else {
						
					}
					
					// if( strtotime($outHour) >= strtotime($nightDiffStarts) && strtotime($outHour) <= strtotime($nightDiffEnds) ) {
						// if( strtotime($tin) <= strtotime($nightDiffStarts) ) {
							// $nightDiffStatus = workHour($outHour, $nightDiffStarts);
							// $nd = $nd + $nightDiffStatus; 
						// }
						// else {
							// $nightDiffStatus = workHour($outHour, $tin);
							// $nd = $nd + $nightDiffStatus; 
						// }		
					// }else if( strtotime($tin) >= strtotime($nightShifts) ) {
						// $nightDiffStatus = workHour($nightDiffEnds, $nightDiffStarts);		
						// $nd = $nd + $nightDiffStatus; 	
					// }
					// else if( strtotime( $tin ) < strtotime( $nightDiffEnds ) ) {
						// $nightDiffStatus = workHour($nightDiffEnds, $nightDiffStarts);
						// $nd = $nd + $nightDiffStatus; 
					// }else {
						// if( strtotime($outHour) < strtotime($nightDiffEnds) ) {
							// $nightDiffStatus = workHour($nightDiffEnds, $nightDiffStarts);
							// $nd = $nd + $nightDiffStatus;
						// }
						// else {
							
						// }
					// }
					
					/* Night Shift END */
					
					/*$leave = getLeaveRequestsByEmpUidAndDate($emp_uid, $date);
					if($leave) {
						$leave_types = getLeaveTypeDataByUid($leave->leaves_types_uid);
						$leave_code = trim($leave_types->leave_code);
						if($leave_code != "W" || $leave_code != "AB" || $leave_code != "OT") {
							$leaveCount = $leaveCount + 1;
						}
						else if($leave_code === "W" || $leave_code === "AB") {
							$myAbsent = $myAbsent + 1;
						}
					}*/
					
					$leave = get_leave_request_emp_uid_and_date($emp_uid, $date); //getLeaveRequestsByEmpUidAndDate($emp_uid, $date);
					if($leave) {
						$leave_types = getLeaveTypeDataByUid($leave->leaves_types_uid);
						$leave_code = trim($leave_types->leave_code);
						//echo $leave_code . " ";
						
						switch($leave_code) {
							case "VL":
								$leaveCount = $leaveCount + 1;
								break;
							case "SL":
								$leaveCount = $leaveCount + 1;
								break;
							case "BL":
								$leaveCount = $leaveCount + 1;
								break;
							case "BV":
								$leaveCount = $leaveCount + 1;
								break;
							case "PL":
								$leaveCount = $leaveCount + 1;
								break;
							case "ML":
								$leaveCount = $leaveCount + 1;
								break;
							case "P":
								$leaveCount = $leaveCount + 1;
								break;
							case "W":
								$myAbsent = $myAbsent + 1;
								break;
							case "AB":
								$myAbsent = $myAbsent + 1;
								break;
						}
						
						/*if($leave_code != "W" || $leave_code != "AB" || $leave_code != "OT") {
							$leaveCount = $leaveCount + 1;
							//echo $leave_code . " " . $leaveCount;
						}
						else if($leave_code === "W" || $leave_code === "AB") {
							$myAbsent = $myAbsent + 1;
						}*/
					}
					
					//$nd = $nd + $nightDiffStatus;
				}
				
				$dayAbsent = $myAbsent; //- $holidayCount - $leaveCount;
				
				if($dayAbsent<=0) {
					//$dayAbsent = 0;
				}
				
				$dayPresent = $dayspresent + $holidayCount + $leaveCount;
				
				$tardyMins = tardyMinutes($date, $date." ".$myTardy); //echo $tardyMins . "</br>";
				$myAbsentMin = $dayAbsent * 60 * 8;
				$tardiness = $tardyMins + $myAbsentMin;
				
				create_timekeeping_summary($uid, $emp_uid, $dayPresent, $workHours, $dayAbsent, $myAbsentMin, $myLate, $myUndertime, $myTardy, $tardyMins, $tardiness);
				
				create_temp_overtime_holiday_summary($uid, $emp_uid, $ot, $rdot, $shot, $shrdot, $rhot, $rhrdot, $rd, $sh, $shrd, $rh, $rhrd, $nd);
				
			}			
			
			create_timekeeping_log_file($uid, "from " . date("M. d, Y", strtotime($sdate)) . " to " . date("M. d, Y", strtotime($edate)) . " period.", $sdate, $edate, $costcenter, "For Process");
			
			$success = 1;
		}
	}	
	
	$response = array(
		"success" => $success,
		"error" => $error
	);
	
	echo jsonify($response);
});

$app->get("/timekeeping/log/file/:var", function($var) {
	$param = explode(".", $var);
	$token = $param[0];
	$response = array();
	if(count($param) === 1) {
		# id, uid, period, from, to, cost_center_uid, date_created, date_modified, remarks, status 
		$results = read_timekeeping_log_file();
		foreach($results as $result) {
			$costCenter = getCostcenterDataByUid($result->cost_center_uid);
			$costCenterName = $costCenter["cost_center_name"] . " " . $costCenter["description"];
			$response[] = array(
				"uid" => $result->uid,
				"period" => $result->period,
				"fromDate" => $result->from,
				"toDate" => $result->to,
				"costCenter" => $costCenterName,
				"dateCreated" => $result->date_created,
				"dateModify" => $result->date_modified,
				"remarks" => $result->remarks
			); 
		}
	}	
	echo jsonify($response);
});

$app->get("/timekeeping/log/processing/:var", function($var) {
	$param = explode(".", $var);
	$token = $param[0];
	$response = array();
	if(count($param) === 1) {
		# id, uid, period, from, to, cost_center_uid, date_created, date_modified, remarks, status 
		$results = read_timekeeping_log_file_for_process();
		foreach($results as $result) {
			$costCenter = getCostcenterDataByUid($result->cost_center_uid);
			$costCenterName = $costCenter["cost_center_name"] . " " . $costCenter["description"];
            $costCenterID = $result->cost_center_uid;
			$response[] = array(
				"uid" => $result->uid,
				"period" => $result->period,
				"fromDate" => $result->from,
				"toDate" => $result->to,
				"costCenter" => $costCenterName,
                "costCenterID" => $costCenterID,
				"dateCreated" => $result->date_created,
				"dateModify" => $result->date_modified,
				"remarks" => $result->remarks
			); 
		}
	}	
	echo jsonify($response);
});

$app->get("/timekeeping/log/file/view/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$response = array();
	if(count($param) === 1) {
		# id, uid, emp_uid, days_present, hours_work, days_absent, days_absent_min, late, undertime, total_tardy, total_tardy_min, total_tardiness
		$results = read_timekeeping_summary_by_uid($uid);
		foreach($results as $result) {
			//$name = read_employee_name_by_uid($result->emp_uid);
			$name = read_employee_lastname_by_uid($result->emp_uid);
			$response[] = array(
				"uid" => $result->uid,
				"empName" => $name,
				"daysPresent" => $result->days_present,
				"hoursWork" => $result->hours_work,
				"daysAbsent" => $result->days_absent,
				"late" => $result->late,
				"undertime" => $result->undertime,
				"totalTardy" => $result->total_tardy
			);
		}
	}
	echo jsonify($response);
});

$app->get("/timekeeping/log/file/delete/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];
	
	$response = array();
	$success = 0;
	
	if(count($param) === 2) {
		delete_timekeeping_summary($uid);
		delete_timekeeping_log_file($uid);
		$success = 1;
	}
	
	$response = array(
		"success" => $success
	);
	
	echo jsonify($response);
});

//Process Timelog
$app->get("/timekeeping/log/file/process/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];
	
	$response = array();
	$success = 0;
	
	if(count($param) === 2) {
        update_timekeeping_log_file_remarks($uid, "Processing");
		$success = 1;
	}
	
	$response = array(
		"success" => $success
	);
	
	echo jsonify($response);
});

$app->get("/timekeeping/summary/export/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];
	
	$response = array();
	$success = 0;
	
	if(count($param) === 2) {
		
		$success = 1;
	}
	
	$response = array(
		"success" => $success
	);
	
	echo jsonify($response);
});

/* Timekeeping Summary - Bottom */

/* Holiday Request - Top */
$app->post("/request/holiday/:var", function($var){
    $param              = explode(".", $var);
    $token              = $param[0];
    $response = array();
	
	if(count($param) === 1) {
		$holidayRequestUid = xguid();
		$employee           = $_POST["employee"];
		$reason             = $_POST["reason"];
		$holidayNotifUid   = xguid();
		
		$startDate          = $_POST["startDate"];
		$date               = $startDate;
		$startHour          = $_POST["startHour"];
		$startHour          = date("H:i", strtotime($startHour));
		$startDate          = $startDate . " " . $startHour;
		
		$endDate            = $_POST["endDate"];
		$endHour            = $_POST["endHour"];
		$endHour            = date("H:i", strtotime($endHour));
		$endDate            = $endDate . " " . $endHour;
		
		$requestStatus      = "Pending";
		$dateCreated        = date("Y-m-d H:i:s");
		$dateModified       = date("Y-m-d H:i:s");
		
		$type               = $_POST["type"]; //getHolidayTypeByDate($startDate);
		$typeText           = trim($_POST["typeText"]);
		$hours1             = strtotime($startDate);
		$hours2             = strtotime($endDate);
		$hours              = ($hours2 - $hours1) / 3600;
		
		$admin = $_POST["admin"];
		
		/* EDIT - Waltz on 09-14-15 */
		$hours              = sprintf("%.2f", $hours);
		$ehour              = explode('.', $hours);
		$hour1              = $ehour[0];
		$hour2              = $ehour[1];

		if($hour1 >= 9) {
			$hour1 = $hour1 - 1;
		}
		
		if($hour2 >=50) {
			$hour2 = 5;
		}
		else {
			$hour2 = 0;
		}
		$hours = $hour1 . "." . $hour2;
		/* EDIT - Waltz on 09-14-15 */

		if(strtotime($startDate) <= strtotime($endDate)){
			$valid = true;
		}else{
			$valid = false;
		}

		$holiday = checkHolidayByDate($date);
		if($holiday) {
			$checkDate = checkEmployeeHasHolidayByDateAndEmpUid($employee, $date);		
			$checkRequest = checkPayrollSchedBeforeRequest($date);
			if($checkRequest["prompt"]){
				if($valid){
					if($checkDate >= 2){
						$response = array(
							"prompt" => 4
						);
					}else if($checkDate <= 2){
						create_holiday_requests($holidayRequestUid, $type ,$employee, $startDate, $endDate, $hours,$reason ,$requestStatus);
						create_holiday_notification($holidayNotifUid, $holidayRequestUid);					
						$response = array(
							"prompt" => 0
						);
					}
				}else{
					$response = array(
						"prompt" => 3
					);
				}
			}else{
				if($valid){
					if($admin) {
						create_holiday_requests($holidayRequestUid, $type ,$employee, $startDate, $endDate, $hours,$reason ,$requestStatus);
						create_holiday_notification($holidayNotifUid, $holidayRequestUid);
						$prompt = 0;
					}
					else {
						$prompt = 1;
					}
					$response = array(
						"prompt" => $prompt
					);
				}else{
					$response = array(
						"prompt" => 3
					);
				}
			}
		}
		else {
			if($typeText === "Rest Day") {
				$checkDate = checkEmployeeHasHolidayByDateAndEmpUid($employee, $date);	
				$checkRequest = checkPayrollSchedBeforeRequest($date);
				if($checkRequest["prompt"]){
					if($valid){
						if($checkDate >= 2){
							$response = array(
								"prompt" => 4
							);
						}else if($checkDate <= 2){
							create_holiday_requests($holidayRequestUid, $type ,$employee, $startDate, $endDate, $hours,$reason ,$requestStatus);
							create_holiday_notification($holidayNotifUid, $holidayRequestUid);					
							$response = array(
								"prompt" => 0
							);
						}
					}else{
						$response = array(
							"prompt" => 3
						);
					}
				}else{
					if($valid){
						if($admin) {
							create_holiday_requests($holidayRequestUid, $type ,$employee, $startDate, $endDate, $hours,$reason ,$requestStatus);
							create_holiday_notification($holidayNotifUid, $holidayRequestUid);
							$prompt = 0;
						}
						else {
							$prompt = 1;
						}
						$response = array(
							"prompt" => $prompt
						);
					}else{
						$response = array(
							"prompt" => 3
						);
					}
				}
			}
			else {
				$response = array(
					"prompt" => 5,
					"text" => $typeText
				);
			}
		}
	}
	
    echo jsonify($response);
});

$app->get("/get/employee/holiday/requests/date/:var", function($var){
    $param     = explode(".", $var);
    $startDate = $param[0];
    $endDate   = $param[1];
    $emp       = $param[2];    
    $response  = array();
	if(count($param) === 3) {
		$holidays = getEmployeeHolidayRequestsByDateRange($startDate, $endDate, $emp);
		foreach($holidays as $holiday){
			$response[] = array(
				"uid" => $holiday->uid,
				"empNo" => $holiday->username,
				"lastname" => utf8_decode($holiday->lastname),
				"firstname" => utf8_decode($holiday->firstname),
				"empUid" => $holiday->emp_uid,
				"from" => date("M d, Y", strtotime($holiday->start_date)),
				"startDates" => $holiday->start_date,
				"to" => $holiday->end_date,
				"hours" => number_format($holiday->hours, 2),
				"reason" => $holiday->reason,
				"type" => $holiday->overtime_type_name,
				"request_status" => $holiday->holiday_request_status,
				"status" => $holiday->status,
				"certBy" => $holiday->cert_by,
				"apprBy" => $holiday->appr_by,
				"date_created" => date("Y-m-d h:i A", strtotime($holiday->date_created)),
				"date_modified" => date("Y-m-d h:i A", strtotime($holiday->date_modified)),
				"prompt" => ""//$out1
			);
		}
		/*foreach($holidays as $holiday){
			$uid        = $holiday->emp_uid;
			$startDate  = $holiday->start_date;
			$startDates = date("Y-m-d", strtotime($startDate));
			$end        = $holiday->end_date;
			$sessionData = getTimeLogByEmpUidAndDate($uid, $startDates);
			if($sessionData){
				$session = $sessionData["session"];
				$out     = getTimeLogOutByEmpUidAndSession($uid, $session);
				if($out){
					$outs   = $out->date_created;
					$endStr = strtotime($end);
					$outStr = strtotime($outs);
					if($outStr >= $endStr){
						$out1 = "Exact!";
					}else{
						$out1 = "Out: " . date("M d, Y h:i:s A", strtotime($outs));
					}
				}else{
					$out1 = "No Time Out!";
				}
				$response[] = array(
					"uid" => $holiday->uid,
					"empNo" => $holiday->username,
					"lastname" => utf8_decode($holiday->lastname),
					"firstname" => utf8_decode($holiday->firstname),
					"empUid" => $holiday->emp_uid,
					"from" => date("M d, Y", strtotime($holiday->start_date)),
					"startDates" => $holiday->start_date,
					"to" => $holiday->end_date,
					"hours" => $holiday->hours,
					"reason" => $holiday->reason,
					"type" => $holiday->overtime_type_name,
					"request_status" => $holiday->holiday_request_status,
					"status" => $holiday->status,
					"certBy" => $holiday->cert_by,
					"apprBy" => $holiday->appr_by,
					"date_created" => date("Y-m-d h:i A", strtotime($holiday->date_created)),
					"date_modified" => date("Y-m-d h:i A", strtotime($holiday->date_modified)),
					"prompt" => $out1
				);
			}
		}*/
	}
    echo jsonify($response);
});

$app->get("/get/employee/holiday/request/details/:uid", function($uid){
    $response = array();
    $overtime = getHolidayRequestsByUid($uid);

    if($overtime){
        $startDate  = $overtime->start_date;
        $startDates = date("Y-m-d", strtotime($startDate));
        $startHour  = date("H:i", strtotime($startDate));
        
        $endDate    = $overtime->end_date;
        $endDates   = date("Y-m-d", strtotime($endDate));
        $endHour    = date("H:i", strtotime($endDate));

        $response = array(
            "uid"           => $overtime->uid,
            "empUid"        => $overtime->emp_uid,
            "startDate"     => $startDates,
            "startHour"     => $startHour,
            "endDate"       => $endDates,
            "endHour"       => $endHour,
            "hours"         => $overtime->hours,
            "reason"        => $overtime->reason,
            "requestStatus" => $overtime->holiday_request_status,
            "status"        => $overtime->status,
            "type"          => $overtime->type
        );
    }

    echo jsonify($response);
});

$app->post("/update/holiday/request/:uid", function($uid){
    $startDate = $_POST["startDate"];
    $startHour = $_POST["startHour"];
    $startHour = date("H:i", strtotime($startHour));
    $startDate = $startDate . " " . $startHour;
    
    $endDate   = $_POST["endDate"];
    $endHour   = $_POST["endHour"];
    $endHour   = date("H:i", strtotime($endHour));
    $endDate   = $endDate . " " . $endHour;
    
    $reason    = $_POST["reason"];
    $status    = $_POST["status"];
    $hours1    = strtotime($startDate);
    $hours2    = strtotime($endDate);
    $hours     = ($hours2 - $hours1) / 3600;
    
    /* EDIT - Waltz on 09-14-15 */
    $hours     = sprintf("%.2f", $hours);
    $ehour     = explode('.', $hours);
    $hour1     = $ehour[0];
    $hour2     = $ehour[1];

    if($hour1 >= 8) {
		$hour1 = $hour1 - 1;
	}
	
	if($hour2 >=50) {
        $hour2 = 5;
    }
    else {
        $hour2 = 0;
    }
    $hours = $hour1 . "." . $hour2;
    /* EDIT - Waltz on 09-14-15 */
    
    $admin = $_POST["admin"];

    $overtimeData = getHolidayRequestsByUid($uid);
    $type  = $overtimeData["type"];

    $requestStatus = $_POST["requestStatus"];
    $dateModified  = date("Y-m-d H:i:s");

    $emp = getEmployeeDetailsByUid($admin);
    if($emp){
        $lastname   = $emp->lastname;
        $firstname  = $emp->firstname;
        $middlename = $emp->middlename;
    }else{
        $lastname   = "";
        $firstname  = "";
        $middlename = "";
    }    

    $name = $firstname . " " . $middlename . " " . $lastname;

    function getInitials($name){
        $words = explode(" ",$name);
        $inits = '';
        foreach($words as $word){
            $inits.=strtoupper(substr($word,0,1));
        }
        return $inits;  
    }

    $user = getInitials($name);

    if(strtotime($startDate) <= strtotime($endDate)){
        $valid = true;
    }else{
        $valid = false;
    }

    $checkRequest = checkPayrollSchedBeforeRequest($startDate);
    if($checkRequest["prompt"]){
        if($valid){
            switch($requestStatus){
            case "Certified":
                $user1 = $user;
                $user2 = "";
                updateHolidayRequest($uid,$type,$startDate, $endDate, $reason, $hours ,$requestStatus, $user1, $user2 ,$dateModified, $status);
                updateHolidayNotification($uid, $requestStatus, $dateModified, $status);
                break;
            case "Approved": 
                $user2 = $user;
                $user1 = "";
                updateHolidayRequest($uid,$type,$startDate, $endDate, $reason, $hours ,$requestStatus, $user1, $user2 ,$dateModified, $status);
                updateHolidayNotification($uid, $requestStatus, $dateModified, $status);
                break;
			case "Pending": 
                $user2 = "";
                $user1 = "";
                updateHolidayRequest($uid,$type,$startDate, $endDate, $reason, $hours ,$requestStatus, $user1, $user2 ,$dateModified, $status);
                updateHolidayNotification($uid, $requestStatus, $dateModified, $status);
                break;
            default:
                $user1 = $user;
                $user2 = "";
                updateHolidayRequest($uid,$type,$startDate, $endDate, $reason, $hours ,$requestStatus, $user1, $user2 ,$dateModified, $status);
                updateHolidayNotification($uid, $requestStatus, $dateModified, $status);
                break;
            }
            $response = array(
                "prompt" => 0
            );
        }else{
            $response = array(
                "prompt" => 3
            );
        }
    }else{
        if($valid){
            if($admin) {
				switch($requestStatus){
				case "Certified":
					$user1 = $user;
					$user2 = "";
					updateHolidayRequest($uid,$type,$startDate, $endDate, $reason, $hours ,$requestStatus, $user1, $user2 ,$dateModified, $status);
					updateHolidayNotification($uid, $requestStatus, $dateModified, $status);
					break;
				case "Approved": 
					$user2 = $user;
					$user1 = "";
					updateHolidayRequest($uid,$type,$startDate, $endDate, $reason, $hours ,$requestStatus, $user1, $user2 ,$dateModified, $status);
					updateHolidayNotification($uid, $requestStatus, $dateModified, $status);
					break;
				case "Pending": 
					$user2 = "";
					$user1 = "";
					updateHolidayRequest($uid,$type,$startDate, $endDate, $reason, $hours ,$requestStatus, $user1, $user2 ,$dateModified, $status);
					updateHolidayNotification($uid, $requestStatus, $dateModified, $status);
					break;
				default:
					$user1 = $user;
					$user2 = "";
					updateHolidayRequest($uid,$type,$startDate, $endDate, $reason, $hours ,$requestStatus, $user1, $user2 ,$dateModified, $status);
					updateHolidayNotification($uid, $requestStatus, $dateModified, $status);
					break;
				}
				$prompt = 0;
			}
			else {
				$prompt = 1;
			}
			
			$response = array(
                "prompt" => $prompt
            );
        }else{
            $response = array(
                "prompt" => 3
            );
        }
    }
    echo jsonify($response);
});

$app->get("/get/employee/holiday/requests/:uid", function($uid){
    $response = array();
    $results = getEmployeeHolidayRequests($uid);
    foreach($results as $overtime){
        $response[] = array(
            "uid" => $overtime->uid,
            "empUid" => $overtime->emp_uid,
            "from" => date("M d, Y", strtotime($overtime->start_date)),
            "reason" => $overtime->reason,
            "hours"  => number_format($overtime->hours, 2),
            "certBy" => $overtime->cert_by,
            "appBy" => $overtime->appr_by,
            "request_status" => $overtime->holiday_request_status,
            "status" => $overtime->status,
            "date_created" => date("Y-m-d h:i A", strtotime($overtime->date_created)),
            "date_modified" => date("Y-m-d h:i A", strtotime($overtime->date_modified))
        );
    }

    echo jsonify($response);
});

$app->get("/get/holiday/data/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $rate     = getHolidayAndType();
    foreach($rate as $rates){
        $response[] = array(
            "holiday_type" => $rates->type,
			"holiday_name" => $rates->name,
			"holiday_date" => date("F d, Y", strtotime($rates->date)),			
			"type_uid" => $rates->holiday_type_uid,
            "type_name" => $rates->holiday_name_type,
            "type_code" => $rates->holiday_code,
            "type_rate" => $rates->rate
        );
    }
    echo jsonify($response);
});

$app->get("/get/holiday/requests/date/:var", function($var) {
	$param    = explode(".", $var);
    $startDate = $param[0];
	$endDate = $param[1];
	$status = $param[2];
	$token = $param[3];
	$response = array();	
	if(count($param) === 4) {
		$results = read_holiday_request_by_date_range_status($startDate, $endDate, $status);
		foreach($results as $result) {
			$name = read_employee_name_by_uid($result->emp_uid);
			$response[] = array(
				"uid" => $result->uid,					
				"name" => $name,
				"start_date" => date("M d, Y", strtotime($result->start_date)),				
				"hours"  => number_format($result->hours, 2),
				"reason" => $result->reason,
				"request_status" => $result->holiday_request_status,
				"certBy" => $result->cert_by,
				"apprBy" => $result->appr_by,
				"date_created" => date("Y-m-d h:i A", strtotime($result->date_created)),
				"date_modified" => date("Y-m-d h:i A", strtotime($result->date_modified))
			);
		}
	}	
	echo jsonify($response);
});

$app->post("/delete/holiday/request/:var", function($var) {
	$param    = explode(".", $var);
    $uid = $param[0];
	$success = 0;
	$response = array();
	if(count($param) === 1) {
		delete_holiday_request_by_uid($uid);
		$success = 1;
	}
	$response = array(
		"success" => $success
	);	
	echo jsonify($response);
});

/* Holiday Request - Bottom */

/* Company Setup */
$app->get("/company/setup/:var", function($var) {
	$param = explode(".", $var);
    $token = $param[0];
	$response = array();
	
	if(count($param) === 1) {
		//$results = read_settings();
		$results = read_settings_all();
		foreach($results as $result) {
			$response[] = array(
				"uid" => $result->uid,
				"items" => $result->items,
				"code" => $result->code,
				"content" => $result->value,
				"status" => $result->status
			);
		}
	}
	echo jsonify($response);
});

$app->post("/company/setup/add/:var", function($var) {
	$param = explode(".", $var);
    $token = $param[0];
	$response = array();
	$success = 0;
	
	if(count($param) === 1) {
		$items = $_POST["items"];
		$content = $_POST["content"];
		
		$code = str_replace(" ", "_", $items);
		$code = strtolower($code);
		
		create_settings($items, $code, $content);
		
		$success = 1;
	}
	
	$response = array(
		"success" => $success
	);
	
	echo jsonify($response);
});

$app->get("/company/setup/view/:var", function($var) {
	$param = explode(".", $var);
    $uid = $param[0];
	$token = $param[1];
	$response = array();
	
	if(count($param) === 2) {
		$results = read_settings_by_uid($uid);
		foreach($results as $result) {
			$response = array(
				"uid" => $result->uid,
				"items" => $result->items,
				"code" => $result->code,
				"content" => $result->value,
				"status" => $result->status
			);
		}
	}	
	echo jsonify($response);
});

$app->post("/company/setup/edit/:var", function($var) {
	$param = explode(".", $var);
    $uid = $param[0];
	$token = $param[1];
	$response = array();
	$success = 0;
	
	if(count($param) === 2) {
		$items = $_POST["items"];
		$content = $_POST["content"];
		$status = $_POST["status"];
		
		$code = str_replace(" ", "_", $items);
		$code = strtolower($code);
		
		update_settings($uid, $items, $code, $content, $status);
		
		$success = 1;
	}
	
	$response = array(
		"success" => $success
	);
	
	echo jsonify($response);
});

$app->get("/company/setup/get/:var", function($var) {
	$param = explode(".", $var);
    $code = $param[0];
	$response = array();
	if(count($param)===1) {
		$result = read_settings_by_code($code);
		$response = array(
			"code" => $code,
			"value" => $result
		);
	}	
	echo jsonify($response);
});

/* PAYROLL - Top */
$app->get("/payroll/:var", function($var) {
	$param = explode(".", $var);
	$taon = $param[0];
	$token = $param[1];
	$response = array();
	
	if(count($param) === 2) {
		$results = read_payroll_registry_by_year($taon);
		foreach($results as $result) {	
			$response[] = array(
				"uid" => $result->uid,
				"year" => $result->year,
				"month" => $result->month,
				"period" => $result->period,
				"payrollType" => $result->payroll_type,
				"employeeType" => $result->employee_type,
				"description" => $result->description,
				"transDate" => date("M. d, Y", strtotime($result->transaction_date)),
				"action" => $result->action
			);
		}
	}
	
	echo jsonify($response);
});

$app->get("/payroll/registry/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];
	$response = array();
	
	if(count($param) === 2) {
		$results = read_payroll_registry_by_uid($uid);
		$actionStatus = $results['action'];
		$response = array(
			"action" => $actionStatus
		);
	}
	
	echo jsonify($response);
});

$app->get("/get/payroll/period/:var", function($var) {
	$param = explode(".", $var);
	$year = $param[0];
	$month = $param[1];
	$costcenterid = $param[2];
    $token = $param[3];
	$response = array();
	$period = null;
	$count = null;
	
	if(count($param) === 4) {
		$count = count_payroll_registry_by_year_month($year, $month, $costcenterid);
		
		if($count === 1) {
			$period = 2;
		}
        else if ($count === 2) {
            $period = 3;
        }
		else {
			$period = 1;
		}
		
		$response = array(
			"period" => $period
		);
	}
	
	echo jsonify($response);
});

$app->post("/create/new/payroll/:var", function($var) {
	$param = explode(".", $var);
	$token = $param[0];	
	$response = array();
	$success = 0;
	
	$year = $_POST['year'];
	$month = $_POST['month'];
	$employeeType = $_POST['employeeType'];
	$payrollPeriod = $_POST['payrollPeriod'];
	$transPeriod = $_POST['transPeriod'];
	$transDate = $_POST['transDate'];
	$period = $_POST['period'];
	$description = $_POST['description'];
    $costcenterid = $_POST['costcenterid'];
	
	$uid = $transPeriod;
	
	if(count($param) === 1) {
		if(validate_timekeeping_log($uid)) {
			if(validate_payroll_registry_by_uid($uid)) {
				update_payroll_registry($uid, $costcenterid, $year, $month, $period, $payrollPeriod, $employeeType, $description, $transDate, "PENDING", 1);
				$success = 1;
			}
			else {
				create_payroll_registry($uid, $costcenterid, $year, $month, $period, $payrollPeriod, $employeeType, $description, $transDate, "PENDING", 1);
				$success = 1;
			}
		}
	}
	
	$response = array(
		"success" => $success
	);
	
	echo jsonify($response);
});

$app->get("/payroll/timekeeping/view/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];
	$response = array();
	$success = 0;
	
	if(count($param) === 2) {
		$results = read_payroll_timekeeping_summary_by_uid($uid);
		foreach($results as $result) {
			$name = read_employee_name_by_uid($result->emp_uid);
			$response[] = array(
				"uid" => $result->uid,
				"empName" => $name,
				"daysPresent" => $result->days_present,
				"daysAbsent" => $result->days_absent,
				"daysAbsentMin" => $result->days_absent_min,
				"totalTardyMin" => $result->total_tardy_min,
				"totalTardiness" => $result->total_tardiness,
				"ot" => $result->ot,
				"rdot" => $result->rdot,
				"shot" => $result->shot,
				"shrdot" => $result->shrdot,
				"rhot" => $result->rhot,
				"rhrdot" => $result->rhrdot,
				"rd" => $result->rd,
				"sh" => $result->sh,
				"shrd"=> $result->shrd,
				"rh" => $result->rh,
				"rhrd" => $result->rhrd
			);			
		}
	}
	
	echo jsonify($response);
});

$app->post("/process/payroll/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];
	$response = array();
	$list = array();
	$success = 0;
	
	if(count($param) === 2) {
		$emp_uid = "";
		$period = "";
		$basic = "0.00";
		$deminimis = "0.00";
		$ecola = "0.00";
		$overTime = "0.00";
		$tardiness = "0.00";
		$sss = "0.00";
		$philhealth = "0.00";
		$pagibig = "0.00";
		$tax = "0.00";
		$adj_add = "0.00";
		$adj_less = "0.00";
		$net_amount = "0.00";
		
		$daysPresent = 0;
		$daysAbsent = 0;
		$regularPay = 0;
		
		$reg = read_payroll_registry_by_uid($uid);
		$period = $reg['year'] . "/" . $reg['month'] . "-" . $reg['period'];
		$actionStatus = trim($reg['action']);
		
		$year = $reg['year'];
		$month = $reg['month'];
        $costcenterid = $reg['cost_center_uid'];
		
		$ctr = 0;
		$count = 0;
		$computeTax = false;        
		
		if(validate_payroll_summary_by_uid($uid)) {
			delete_table($uid, "payroll_summary");
		}
		
		if($actionStatus=="PENDING") {
			transfer_timekeeping_summary($uid);
			transfer_overtime_holiday_summary($uid);
		}
		
		$results = merge_payroll_timekeeping_overtime_holiday($uid);
		foreach($results as $result) {
			$ctr++;
			$emp_uid = $result->emp_uid;
			
			$daysPresent = $result->days_present;
			$daysAbsent = $result->days_absent;
			
			# Get Employee Salary #
			$get_salary = getSalaryByUid($emp_uid);
			$base_salary = $get_salary->base_salary;
			$pay_period = $get_salary->pay_period_name;
			
			$work_policy = read_work_policy();
			$work_days_per_year = $work_policy->work_days_per_year;
			$work_hours_per_day = $work_policy->work_hours_per_day;
			
			switch($pay_period) {
				case "Monthly":
					$basic = $base_salary;
					$daily = ($base_salary * 12) / $work_days_per_year;					
					break;
                case "Semi-Monthly":
                    $basic = $base_salary;
                    $daily = ($base_salary * 12) / $work_days_per_year;					
                    break;
				case "Daily":
					$basic = ($base_salary * $work_days_per_year) / 12;
					$daily = $base_salary;					
					break;				
			}
			
			$hourly = $daily / $work_hours_per_day;
			$minute = $hourly / 60;
			
			$basic_salary = $basic; # Basic Salary
			
			# Periods per monthly
			$payroll_period = read_settings_by_code('periods_per_month');
			if($payroll_period = "Semi-Monthly") {
				$basic = $basic / 2;
			}
			
			# Get Employee Salary #
			
			# Absent #
			$absent = $result->days_absent;
			$absentMin = $result->days_absent_min;
			$absentDeduction = $absentMin * $minute;
			
			# Overtime #
			# ot rdot shot shrdot rhot rhrdot rd sh shrd rh rhrd
			$ot = $result->ot;
			$rdot = $result->rdot;
			$shot = $result->shot;
			$shrdot = $result->shrdot;
			$rhot = $result->rhot;
			$rhrdot = $result->rhrdot;
			$rd = $result->rd;
			$sh = $result->sh;
			$shrd = $result->shrd;
			$rh = $result->rh;
			$rhrd = $result->rhrd;
			
			$overtime = compute_overtime_holiday($hourly, $ot, $rdot, $shot, $shrdot, $rhot, $rhrdot, $rd, $sh, $shrd, $rh, $rhrd);
			# Overtime #
			
			# Tardiness #
			//$tardy = $result->total_tardiness;
			if($pay_period=="Monthly") {
				$tardy = $result->total_tardiness;
				$regularPay = $basic;
			} else {
				$tardy = $result->total_tardy_min;
				$regularPay = $daysPresent * $daily;
			}
			
			$tardyDeduction = $tardy * $minute;
			
			//$totalTardiness = $absentDeduction + $tardyDeduction;			
			$totalTardiness = $tardyDeduction;	

			$sssEmployee = "0.00";
			$philEmployee = "0.00";
			$pagibig = "0.00";
			$tax = "0.00";

            $taxValue = 0;

            $sss_loan_amount = 0;
            $hdmf_loan_amount = 0;
            $company_loan_amount = 0;
            $trans_date = $reg['transaction_date'];

			//$count = count_payroll_registry_by_uid($uid);
			$count = count_payroll_registry_by_year_month($year, $month, $costcenterid);
			if($count>1) {
				# Contribution #
				$sss = getSSSBySalary($basic_salary);
				$sssEmployee = $sss["sssEe"];
				
				//$philhealth = getPhilhealthBySalary($basic);
				//$philEmployee = $philhealth["employeeShare"];
				//$philEmployee = get_philhealth_amount_by_salary($basic_salary); // Edit Sept. 01, 2021
                $my_year = date("Y", strtotime($trans_date));
                $philPercent = read_philhealth_by_year($my_year)->percent;
                $philEmployee = $basic_salary * $philPercent / 100;
                $philEmployee = $philEmployee / 2;
				
				$pagibig = 100;
				
				$last_netpay = get_employee_last_payroll_netpay($uid, $emp_uid);
				
				$computeTax = true; // disabled tax deductions - May 19, 2021 4:56pm
			
                $sss_loan_amount = get_employee_loans_payroll_amounts("sss", $emp_uid, $trans_date);
                
                $hdmf_loan_amount = get_employee_loans_payroll_amounts("hdmf", $emp_uid, $trans_date);
                
                $company_loan_amount = get_employee_loans_payroll_amounts("company", $emp_uid, $trans_date);  
                
                $cash_advance_loan_amount = get_employee_loans_payroll_amounts("cash advance",$emp_uid, $trans_date); // added 5-5-2023 * gets the amortization amount of the cash advance loan
            }
			$totalDeduction = $sssEmployee + $philEmployee + $pagibig;

            # Taxable Income
			$taxableIncome = $regularPay + $overtime - $totalTardiness - $totalDeduction;

            if($taxableIncome) {
                $pay_period_name = "Semi-Monthly";

                $pay_period_uid =  get_pay_period_name($pay_period_name)->pay_period_uid; 
                
                $taxValue = get_employee_payroll_tax($pay_period_uid, $taxableIncome);
            }

			if($computeTax) {
				# Tax #
				// $tax = get_employee_tax($emp_uid, $basic_salary);
				// $tax_amount = $tax["amount"];
				// $tax_row = $tax["row"];
				
				// $tax_ex = read_tax_exemption($tax_row);
				// $amount = $tax_ex["amount"];
				// $percent = $tax_ex["percent"];
				
				// $taxExcess = $basic_salary - $tax_amount;
				// $taxEx = $taxExcess * ($percent / 100);
				
				// $taxValue = $taxEx + $amount;/

                // $taxable_amount = $taxableIncome;
                // $pay_period_name = "Semi-Monthly";

                // $pay_period_uid =  get_pay_period_name($pay_period_name)->pay_period_uid; 
                
                // $taxValue = get_employee_payroll_tax($pay_period_uid, $taxable_amount);
			}	
			
			
			#Net Income
			$netIncome = $regularPay + $overtime - $totalTardiness - $totalDeduction;
			
			//$netPay = $netIncome;
			$netPay = $netIncome - $taxValue;			

            // De Minimis - Allowances > Meal, Transportation, Cash Allowances. etc...
            $cut_off = $reg['period'];
            if($cut_off===1) {
                $period_type = "15";
            }else if($cut_off===2) {
                $period_type = "30";
            }else {
                $period_type = "both";
            }

            $meal = 0;
            $transpo = 0;
            $cola = 0;
            $other = 0;
            $deminimis = 0;

            $emp_allowance = getAllowanceByEmpUidPeriod($emp_uid, $period_type);
            if($emp_allowance) {
                $meal = $emp_allowance->meal;
                $transpo = $emp_allowance->transportation;
                $cola = $emp_allowance->cola;
                $other = $emp_allowance->other;
                $deminimis = $meal + $transpo + $cola + $other;
            }

            $trans_date = $reg['transaction_date'];
            $emp_adjustment = read_payroll_adjustment_by_emp_uid_payroll_date($emp_uid, $trans_date, $cut_off);
            if($emp_adjustment) {
                $adj_add = 0;
                $adj_less = 0;
                foreach($emp_adjustment as $adjustment) {
                    if(($adjustment->type)==="Add") {
                        $adj_add = $adj_add + $adjustment->amount;
                    }else {
                        $adj_less = $adj_less + $adjustment->amount;
                    }
                }
            }
			
			if($actionStatus!="POSTED") {
				$salary = sprintf("%.2f", $basic_salary);
				$grossPay = sprintf("%.2f", $regularPay);
				create_payroll_summary($uid, $emp_uid, $period, $salary, $daysPresent, $daysAbsent, $grossPay, $deminimis, $ecola, $overtime, $totalTardiness, $sssEmployee, $philEmployee, $pagibig, $sss_loan_amount,$hdmf_loan_amount, $company_loan_amount, $taxValue, $adj_add, $adj_less, $netPay);
			}
		}
		
		if($actionStatus!="POSTED") {
			update_timekeeping_log_file_remarks($uid, "For Review");
			update_payroll_registry_action($uid, "POSTED");
		}
		$success = 1;
	}
	
	$response = array(
		//"payroll" => $list,
		"success" => $success,
		"count" => $count,
		"computeTax" => $computeTax,
		"action" => $actionStatus,
		"counter" => $ctr
	);
	
	echo jsonify($response);
});

$app->get("/get/tax/status/:var", function($var) {
	$param = explode(".", $var);
	$token = $param[0];
	$response = array();
	
	if(count($param) === 1) {
		$results = read_tax_type();
		if($results) {
			foreach($results as $result) {
				$response[] = array(
					"uid" => $result->uid,
					"type" => $result->type
				);
			}
		}
	}
	
	echo jsonify($response);
});

$app->get("/payroll/employee/list/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$response = array();
	
	if(count($param) === 1) {
		$results = read_payroll_summary_by_uid($uid);
		foreach($results as $result) {            
			$employee = getEmployeeDetailsByUid($result->emp_uid); 
			$name = read_employee_name_one_by_uid($result->emp_uid); 
            //$name = utf8_decode($name);
            
            // echo $uid . " ";
            // echo $employee->username . " ";
            // echo $result->emp_uid . " ";
            // echo utf8_decode($name) . " ";
            // echo $employee->status . " ";
            // echo "</br>";

			$response[] = array(
				"uid" => $uid,
				"empID" => $employee->username,
				"empUid" => $result->emp_uid,
				"empName" => $name,
				"status" => $employee->status					
			);          
		}
	}
	echo jsonify($response);
});

$app->get("/payroll/employee/payslip/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$emp_uid = $param[1];
	$token = $param[2];
	$response = array();
	
	if(count($param) === 3) {
		$results = read_employee_payroll_summary_by_uid($uid, $emp_uid);
		$company = read_settings_by_code("company_name");
		$name = read_employee_name_one_by_uid($emp_uid);
		
		$basic_salary = $results->gross_regular_pay;
		$deminimis = $results->deminimis;
		$absences = $results->days_absent;
		$total_absences = $absences * 480;
		
		$leaveCount = leaveCountsByEmpUid($emp_uid);
		$SL = $leaveCount["SL"];
		$BL = $leaveCount["BL"];
		$VL = $leaveCount["VL"];
		
		if($SL === "N/A") {
			$SL = 0;
		} 
		elseif($BL === "N/A") {
			$BL = 0;
		}
		elseif($VL === "N/A") {
			$VL = 0;
		}
		
		$total_leave = $SL + $VL + $BL;
		
		$overtime = $results->ot;
		//$tardiness = $results->tardiness - $total_absences;
		$tardiness = $results->tardiness;
        $adjAdd = $results->adj_add;
		$adjLess = $results->adj_less;
		
		$total_tardiness = $results->tardiness;
		
		$sss_contri = $results->sss;
		$phic_contri = $results->philhealth;
		$hdmf_contri = $results->pagibig;
		$withholding_tax = $results->tax;
		
		$deminimisDeduction = "0.00";
		
		$emp = getEmployeeDetailsByUid($emp_uid);
		$tin = $emp->tax_no;
		$sss_no = $emp->sss_no;
		$philhealth_no = $emp->philhealth_no;
		$pagibig_no = $emp->pagibig_no;
		$tax_type_uid = $emp->tax_status;
		
		$dtr_log = read_timekeeping_log_file_by_uid($uid);
		$date_covered = ucfirst($dtr_log->period);
		
		$registry = read_payroll_registry_by_uid($uid);
		$payroll_date = $registry->transaction_date;
		
		$tax_type = read_tax_type_by_uid($tax_type_uid);
		$tax_status = isset($tax_type->type) ? $tax_type->type:"N/A"; //$tax_status:"N/A";
		
		$department = read_employee_department_by_uid($emp_uid);
		$emp_department = $department->group;
        $emp_position = $department->designation;
		
		$totalCompensation = $basic_salary + $deminimis + $overtime + $adjAdd;
		$totalDeduction = $total_tardiness + $adjLess + $sss_contri + $phic_contri + $hdmf_contri + $withholding_tax;	
        
        $taxable_income = $basic_salary + $overtime + $adjAdd - $total_tardiness;
        $non_taxable_income = $sss_contri + $phic_contri + $hdmf_contri + $deminimis;
		
		$netpay = $totalCompensation - $totalDeduction;
		
		$assumed_13th_month = $netpay / 24;
		
		$salary_to_date = get_employee_salary_to_date($emp_uid);
		$tax_to_date = get_employee_tax_to_date($emp_uid);
		
		$response = array(
			"company_name" => $company,
			"name" => $name,
			"tin" => $tin,
			"payroll_date" => date("F d, Y", strtotime($payroll_date)),
			"date_covered" => $date_covered,
			"sss" => $sss_no,
			"tax_status" => $tax_status,
			"philhealth" => $philhealth_no,
			"pagibig" => $pagibig_no,
			"department" => $emp_department,
            "position" => $emp_position,
			"basic_salary" => $basic_salary,
			"deminimis" => $deminimis,
			"deminimisDeduction" => $deminimisDeduction,
			"absentCount" => $absences,
			"absences" => $total_absences,
			"overtime" => $overtime,
			"tardiness" => $tardiness,
			"adjAdd" => $adjAdd,
			"adjLess" => $adjLess,
			"sss_contri" => $sss_contri,
			"phic_contri" => $phic_contri,
			"hdmf_contri" => $hdmf_contri,
			"withholding_tax" => $withholding_tax,
			"totalCompensation" => $totalCompensation,
			"totalDeduction" => $totalDeduction,
            "taxableIncome" => $taxable_income,
            "nonTaxableIncome" => $non_taxable_income,
			"netpay" => $netpay,
			"assumed_13th_month" => $assumed_13th_month,
			"salary_to_date" => $salary_to_date,
			"tax_to_date" => $tax_to_date,
			"SL" => $SL,
			"VL" => $VL,
			"BL" => $BL,
			"total_leave" => $total_leave
		);
	}
	
	echo jsonify($response);
});

$app->get("/payroll/summary/view/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$response = array();
	
	if(count($param) === 1) {
		$results = read_payroll_summary_by_uid($uid);
		foreach($results as $result) {
			//$empName = read_employee_lastname_by_uid($result->emp_uid);
			$empName = read_employee_lname_by_uid($result->emp_uid);
			$empNum = getEmloyeeNumberByEmpUid($result->emp_uid);
			$response[] = array(
				"id" => $result->id,
				"uid" => $result->uid,
				"emp_uid" => $result->emp_uid,
				"empNum" => $empNum,
				"empName" => $empName,
				"period" => $result->period,
				"basic" => $result->basic, 
				"daysPresent" => $result->days_present,
				"daysAbsent" => $result->days_absent,
				"grossPay" => $result->gross_regular_pay,
				"deminimis" => $result->deminimis,
				"ecola" => $result->ecola,
				"ot" => $result->ot,
				"tardiness" => $result->tardiness,
				"sss" => $result->sss,
				"philhealth" => $result->philhealth,
				"pagibig" => $result->pagibig,
				"tax" => $result->tax, 
				"adjAdd" => $result->adj_add,
				"adjLess" => $result->adj_less, 
				"netAmount" => $result->net_amount, 
			);
		}
	}
	
	echo jsonify($response);
});

$app->get("/payroll/summary/list/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$response = array();
	
	if(count($param) === 1) {
		$results = read_payroll_registry();
		foreach($results as $result) {			
			$response[] = array(
				"uid" => $result->uid,
				"year" => $result->year,
				"month" => $result->month,
				"period" => $result->period,
				"type" => $result->payroll_type, 
				"empType" => $result->employee_type,
				"desc" => $result->description,
				"transDate" => date("F d, Y", strtotime($result->transaction_date)),
				"action" => $result->action
			);
		}
	}	
	echo jsonify($response);
});

$app->get("/monthly/payroll/summary/list/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$response = array();
	
	if(count($param) === 1) {
		$results = read_payroll_registry_monthly();
		foreach($results as $result) {			
			$costCenter = get_cost_center($result->cost_center_uid);
			$response[] = array(
				"year" => $result->year,
				"month" => $result->month,
				"type" => $result->payroll_type, 
				"empType" => $result->employee_type,
				"costCenterUid" => $result->cost_center_uid,
				"costCenter" => $costCenter['cost_center_name'] . " " . $costCenter['description']
			);
		}
	}	
	echo jsonify($response);
});

$app->get("/monthly/payroll/summary/:var", function($var) {
	$param = explode(".", $var);
	$year = $param[0];
	$month = $param[1];
	$uid = $param[2];
	$response = array();
	$month = array();
	$lists = array();
	
	if( count($param) === 3 ) {
		$results = read_payroll_registry_distinct();
		foreach($results as $result) {
			$month[] = array(
				"month" => $result->month,
				"year" => $result->year
			);
		}
		
		$ctr = count($month);
		for($i=0;$i<$ctr;$i++) {
			$taon = $month[$i]['year'];
			$buwan = $month[$i]['month'];
			
			$codes = read_payroll_registry_monthly_by_year_month($taon, $buwan);
			foreach($codes as $code) {
				$lists[] = array(
					"year" => $code->uid
				);
			}
			
			// $lists[] = array(
				// "year" => $month[$i]['year'],
				// "month" => $month[$i]['month']
			// );
		}
		
		//foreach($month as $mon) {
			//$code = read_payroll_registry_monthly_by_year_month($mon->year, $mon->month);
			// $lists[] = array(
				// "year" => $mon->year
			// );
		//}
		
		$response = array(
			"success" => 1,
			"month" => $month,
			"list" => $lists
		);
	}	
	echo jsonify($response);
});

$app->post("/payroll/monthly/summary/query/:var", function($var) {
	$param = explode(".", $var);	
	$response = array();
	$lists = array();
	$success = 0;
	if( count($param) === 1 ) {		
		$costcenter = $_POST['costCenter'];
		$year = $_POST['yeaR'];
		$month = $_POST['monTH']; $month = str_pad($month,2,"0",STR_PAD_LEFT);
		$type = $_POST['tyPE'];
		
		$results = read_payroll_registry_monthly_by_year_month_type_costcenter($year, $month, $type, $costcenter);
		
		$getTotal = null;
		
		if($results) {
			foreach( $results as $result ) {
				$cCenter = getCostcenterDataByUid($result->cost_center_uid);
				$cCenterName = $cCenter['cost_center_name'] . " " . $cCenter->description;
				
				$getTotal = get_payroll_computation_by_uid($result->uid);
			
				$lists[] = array(
					"uid" => $result->uid,
					"costcenter" => $cCenterName,
					"year" => $result->year,
					"month" => $result->month,
					"period" => $result->period,
					"payroll_type" => $result->payroll_type,
					"employee_type" => $result->employee_type,
					"sss" => number_format($getTotal->total_sss, 2, '.', ','),
					"sss" => number_format($getTotal->total_sss, 2, '.', ','),
					"phic" => number_format($getTotal->total_phic, 2, '.', ','),
					"hdmf" => number_format($getTotal->total_hdmf, 2, '.', ','),
					"tax" => number_format($getTotal->total_tax, 2, '.', ','),
					"netpay" => number_format($getTotal->total_netpay, 2, '.', ','),
				);
			}
			
			if($getTotal) {
				$success = 1;
			}
		}
		
		$response = array(
			"list" => $lists,
			"costcenter" => $costcenter,
			"year" => $year,
			"month" => $month,
			"type" => $type,
			"success" => $success
		);
	}
	
	echo jsonify($response);
});

$app->post("/payroll/yeartodate/summary/query/:var", function($var) {
	$param = explode(".", $var);	
	$response = array();
	$lists = array();
	$totals = array();
	$success = 0;
	
	if( count($param) === 1 ) {
		$costcenter = $_POST['costCenter'];
		$year = $_POST['yeaR'];
		$type = $_POST['tyPE'];
		
		$results = read_payroll_registry_monthly_by_year_type_costcenter($year, $type, $costcenter);
		
		$getTotal = null;
		
		if($results) {
			$total_sss = 0;
			$total_phic = 0;
			$total_hdmf = 0;
			$total_tax = 0;
			$total_netpay = 0;
			
			foreach( $results as $result ) {
				$cCenter = getCostcenterDataByUid($result->cost_center_uid);
				$cCenterName = $cCenter['cost_center_name'] . " " . $cCenter->description;
				
				$getTotal = get_payroll_computation_by_uid($result->uid);
			
				$lists[] = array(
					"uid" => $result->uid,
					"costcenter" => $cCenterName,
					"year" => $result->year,
					"month" => $result->month,
					"period" => $result->period,
					"payroll_type" => $result->payroll_type,
					"employee_type" => $result->employee_type,
					"sss" => number_format($getTotal->total_sss, 2, '.', ','),
					"phic" => number_format($getTotal->total_phic, 2, '.', ','),
					"hdmf" => number_format($getTotal->total_hdmf, 2, '.', ','),
					"tax" => number_format($getTotal->total_tax, 2, '.', ','),
					"netpay" => number_format($getTotal->total_netpay, 2, '.', ','),
				);
				
				$total_sss = $total_sss + $getTotal->total_sss;
				$total_phic = $total_phic + $getTotal->total_phic;
				$total_hdmf = $total_hdmf + $getTotal->total_hdmf;
				$total_tax = $total_tax + $getTotal->total_tax;
				$total_netpay = $total_netpay + $getTotal->total_netpay;
			}
			
			if($getTotal) {
				$totals = array(
					"total_sss" => number_format($total_sss, 2, '.', ','),
					"total_phic" => number_format($total_phic, 2, '.', ','),
					"total_hdmf" => number_format($total_hdmf, 2, '.', ','),
					"total_tax" => number_format($total_tax, 2, '.', ','),
					"total_netpay" => number_format($total_netpay, 2, '.', ',')
				);				
				$success = 1;
			}
		}
		
		$response = array(
			"list" => $lists,
			"totals" => $totals,
			"costcenter" => $costcenter,
			"year" => $year,
			"type" => $type,
			"success" => $success
		);
	}
	
	echo jsonify($response);
});

$app->get("/payroll/registry/list/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$response = array();
	
	if(count($param) === 1) {
		$results = read_payroll_registry_posted();
		foreach($results as $result) {			
			$response[] = array(
				"uid" => $result->uid,
				"year" => $result->year,
				"month" => $result->month,
				"period" => $result->period,
				"type" => $result->payroll_type, 
				"empType" => $result->employee_type,
				"desc" => $result->description,
				"transDate" => date("F d, Y", strtotime($result->transaction_date)),
				"action" => $result->action
			);
		}
	}	
	echo jsonify($response);
});

$app->post("/validate/file/:var", function($var) {
	$param = explode(".", $var);
	$token = $param[0];
	$response = array();
	$success = 0;
	
	if(count($param)===1) {
		$filename = $_FILES['field']['name'];
		$sourcePath = $_FILES['field']['tmp_name'];
		$targetPath = "files/".$_FILES['field']['name'];
		move_uploaded_file($sourcePath,$targetPath);
		
		$success = 1;
	}
	
	$response = array(
		"success" => $success
	);
	
	echo jsonify($response);
});

$app->post("/manual/payroll/registry/:var", function($var) {
	// 	id uid period from to cost_center_uid date_created date_modified remarks status
	$param = explode(".", $var);
	$token = $param[0];
	$response = array();
	$success = 0;
	
	if(count($param)===1) {
		$uid = xguid();
		$fromDate = $_POST['fromDate'];
		$toDate = $_POST['toDate'];
		$costCenter = $_POST['costCenter'];
		$remarks = "For Process";
		$period = date("M. d, Y",strtotime($fromDate)) . " to " . date("M. d, Y",strtotime($toDate)) . " period";		
		create_timekeeping_log_file($uid, $period, $fromDate, $toDate, $costCenter, $remarks);
		$success = 1;
	}
	
	$response = array(
		"success" => $success,
		"token" => $uid
	);
	
	echo jsonify($response);
});

$app->post("/manual/payroll/upload/:var", function($var) {
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];
	$files = $param[2];
	$response = array();
	$success = 0;	
	$error = 0;
	$row = 0;
	if(count($param)===3) {
		$filename = $_FILES[$files]['name'];
		$sourcePath = $_FILES[$files]['tmp_name'];
		$targetPath = "files/".$_FILES[$files]['name'];
		$imageFileType = pathinfo($targetPath,PATHINFO_EXTENSION);		
		if($imageFileType!="csv") {
			$error = 1;
		}
		else {
			move_uploaded_file($sourcePath,$targetPath);		
			$handle = fopen($targetPath, "r");   
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				$recordsArray = array();
				if($row>0) {
					$empID = str_pad($data[0], 4, "0", STR_PAD_LEFT);
					if($emp_uid = read_employee_uid_by_username($empID)) {
						if($files=="timekeeping") {						
							$recordsArray = array(
								"uid" => $uid,
								"emp_uid" => $emp_uid,
								"days_present" => $data[1],
								"days_absent" => $data[2],
								"days_absent_min" => $data[3],
								"total_tardy_min" => $data[4],
								"total_tardiness" => $data[5]
							);
							create_file_summary_upload($recordsArray, $files);
							$success = 1;
						}
						else if($files=="overtime") {
							$recordsArray = array(
								"uid" => $uid,
								"emp_uid" => $emp_uid,
								"ot" => $data[1],
								"rdot" => $data[2],
								"shot" => $data[3],
								"shrdot" => $data[4],
								"rhot" => $data[5],
								"rhrdot" => $data[6],
								"rd" => $data[7],
								"sh" => $data[8],
								"shrd" => $data[9],
								"rh" => $data[10],
								"rhrd" => $data[11],
								"nd" => $data[12]
							);
							create_file_summary_upload($recordsArray, $files);
							$success = 1;
						}
						else if($files=="adjustment") {
							$recordsArray = array(
								"uid" => $uid,
								"emp_uid" => $emp_uid,
								"type" => $data[1],
								"amount" => $data[2],
								"remarks" => $data[3]
							);
							create_file_summary_upload($recordsArray, $files);
							$success = 1;
						}
						else {
							$error = 2;
						}
					}
				}
				$row++;
			}
			fclose($handle);
		}
	}
	
	$response = array(
		"success" => $success,
		"file_type" => $imageFileType,
		"error" => $error
	);
	
	echo jsonify($response);
});

/* Payroll Adjustment Added on March 22, 2021 04:48pm */
$app->post("/payroll/adjustments/:var", function ($var) {
    $param = explode(".", $var);
    $response = array();
    $verified = 0;

    if(count($param) === 1) {
        $uid = xguid();
        $emp_uid = $_POST['empUid'];
        $new_amount = $_POST['newAmount'];
        $new_remark = $_POST['newRemark'];
        $new_date = $_POST['newDate'];
        $new_type = $_POST['newType'];
        $period = $_POST['newPeriod'];

        create_payroll_adjustments($uid, $emp_uid, $new_amount, $new_remark, $new_date, $new_type, $period);

        $verified = 1;
    }

    $response = array(
        "success" => $verified
    );
    echo jsonify($response);
});

$app->get("/get/payroll/adjustment/:var", function ($var) {
    $param = explode(".", $var);
    $response = array();
    $verified = 0;

    if(count($param) === 1) {
        $results = read_payroll_adjustments();
        foreach($results as $result) {
            $name = read_employee_name_one_by_uid($result->emp_uid);
            
            $period = date("d", strtotime($result->payroll_date));
            $month = date("m", strtotime($result->payroll_date));
            $year = date("Y", strtotime($result->payroll_date));

            $response[] = array(
                "uid" => $result->uid,
                "emp_uid" => $result->emp_uid,
                "empName" => $name,
                "amount" => $result->amount,
                "remark" => $result->remarks,
                "type" => $result->type,
                "payroll_date" => $result->payroll_date,
                "payroll_period" => $result->period,
                "period" => $period,
                "month" => $month,
                "year" => $year,
                "status" => $result->status
            );
        }
        $verified = 1;
    }

    echo jsonify($response);
});

$app->post("/payroll/adjustments/edit/:var", function ($var) {
    $param = explode(".", $var);
    $response = array();
    $verified = 0;

    if(count($param) === 1) {
        $uid = $_POST['editUid'];
        $amount = $_POST['editAmount'];
        $remark = $_POST['editRemark'];
        $date = $_POST['editDate'];
        $type = $_POST['editType'];
        $period = $_POST['editPeriod'];

        update_payroll_adjustments_by_uid($uid, $amount, $remark, $date, $type, $period);

        $verified = 1;
    }

    $response = array(
        "success" => $verified
    );
    echo jsonify($response);
});

/* PAYROLL - Bottom */

/*EVALUATION START*/

$app->post("/employee/evaluation/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;

    if(count($param) === 1) {
        $uid       = xguid();
        $employee    = $_POST['employee'];
        $evaluator   = $_POST['evaluator'];
        $evaluations = $_POST['evaluations'];
        
        $dateCreated   = date("Y-m-d H:i:s");
        $dateModified  = date("Y-m-d H:i:s"); 
        $effectiveDate = date('Y-m-d', strtotime("+12 months", strtotime($dateModified)));

        foreach($evaluations as $evaluation) {
            evaluation_results($uid, $employee, $evaluator, $evaluation["questionUid"], $evaluation["result"], $evaluation["questionType"], $dateCreated, $dateModified, $effectiveDate);
        }

        $verified = 1;
   
        $response = array(
            "error"        => 0,
            "errorMessage" => "SUCCESSFULLY CREATED!"
        );
    }

    echo jsonify($response);
});

$app->post("/knowledge/new/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;
    if(count($param) === 1) {
        $questionUid = xguid();
        $question    = $_POST['question']; 
        $answer      = $_POST['answer'];
        
        $dateCreated   = date("Y-m-d H:i:s");
        $dateModified  = date("Y-m-d H:i:s");

        create_knowledge_based_question($questionUid, $question, $answer, $dateCreated, $dateModified);
        $verified = 1;
    }
});

$app->get("/knowledge/based/data/:var", function($var){
    $response  = array();
    $questions = read_knowledge_based_all();

    foreach($questions as $question){
        $response[] = array(
            "uid"   => $question->uid,
            "question"  => $question->question,
            "answer"  => $question->answer
        );
    }
    echo jsonify($response);
    // var_dump($response);

});

$app->get("/knowledge/based/data/get/:var", function($var){
    $param    = explode(".", $var);
    $token    = $param[1];
    $uid      = $param[0];
    
        $results = read_knowledge_based_by_uid($uid);

            $response = array(
                "uid"   => $results->uid,
                "question"  => $results->question,
                "answer"  => $results->answer
            ); 

    echo jsonify($response);
    // var_dump($response);
}); 

$app->post("/knowledge/update/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response     = array();
        $question     = $_POST['question'];
        $answer       = $_POST['answer'];
        $dateModified = date("Y-m-d H:i:s");

        update_knowledge($uid, $question, $answer, $dateModified);      
        $success = 1;
    }
        $response = array(
            "success" => $success
        );
    echo jsonify($response);

    // var_dump($uid);

});

$app->get("/delete/knowledge/based/:uid", function($uid){
    $status = 0;

    delete_knowledge_based($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->post("/new/request/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;
    if(count($param) === 1) {
        $requestUid = xguid();
        $request    = $_POST['requestFor']; 
        $reason     = $_POST['reason']; 
        $empuid     = $_POST['empuid']; 
        $dateCreated   = date("Y-m-d H:i:s");
        $dateModified  = date("Y-m-d H:i:s");

        create_emp_request($requestUid, $request, $reason, $empuid, $dateCreated, $dateModified);
        $verified = 1;
    }
});

$app->get("/requests/individual/data/:var", function($var){
    $param     = explode(".", $var);
    $userUid     = $param[0];
    $token     = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_requests_by_user($userUid);
        foreach ($results as $result) {  
            $emp_uid    = $result->emp_uid;
            $emp        = read_emp_by_uid($emp_uid);
            $firstname  = $emp["firstname"];
            $middlename = $emp["middlename"];
            $lastname   = $emp["lastname"];
            $empuid     = $emp["emp_uid"];

            $request_uid   = $result->request_uid;
            $request       = read_request_type_by_uid($request_uid);
            $name       = $request["name"];

            $uid = $result->uid;

            $response[] = array(
                "uid"        => $result->uid,
                "firstname"  => $firstname,
                "middlename" => $middlename,
                "lastname"   => $lastname,
                "empuid"     => $empuid,
                "requestName" => $name,
                "request"    => $result->request_uid,
                "reason" => $result->reason,
                "requestStatus"   => $result->request_status,
                "dateRelease"   => $result->dateRelease
            );
        }
    }
    
 echo jsonify($response);

    // var_dump($response);

});

$app->get("/emp/request/data/:var", function($var){
    $param    = explode(".", $var);
    $token    = $param[1];
    $uid      = $param[0];
    $response = array();
    
    if(count($param) === 2) {
        $results = read_request_by_uid($uid);
            foreach($results as $result) {
            $response[] = array(
                    "uid" => $result->uid,
                    "request" => $result->request_uid,
                    "reason" => $result->reason,
                    "requestStatus" => $result->request_status,
                    "empuid" => $result->emp_uid
            );
        }
    }   

    echo jsonify($response);
    // var_dump($response);
});

$app->post("/emp/request/update/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response      = array();
        $request        = $_POST['request'];
        $reason        = $_POST['reason'];
        $dateModified  = date("Y-m-d H:i:s");

        update_emp_request($uid, $request, $reason, $dateModified);      
        $success = 1;
    }
        $response = array(
            "success" => $success
        );
    echo jsonify($response);

    // var_dump($uid);

});

$app->get("/delete/emp/request/:uid", function($uid){
    $status = 0;

    delete_emp_request($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/requests/all/data/:var", function($var){
    $param     = explode(".", $var);
    $token     = $param[0];
    $response = array();

    if(count($param) === 1) {
        $results = read_emp_request_all();
        foreach ($results as $result) {
            $emp_uid    = $result->emp_uid;
            $emp        = read_emp_by_uid($emp_uid);
            $firstname  = $emp["firstname"];
            $middlename = $emp["middlename"];
            $lastname   = $emp["lastname"];
            $empuid     = $emp["emp_uid"];

            $request_uid   = $result->request_uid;
            $request       = read_request_type_by_uid($request_uid);
            $name       = $request["name"];

            $response[] = array(
                "uid"        => $result->uid,
                "firstname"  => $firstname,
                "middlename" => $middlename,
                "lastname"   => $lastname,
                "empuid"     => $empuid,
                "requestName" => $name,
                "request"    => $result->request_uid,
                "reason" => $result->reason,
                "requestStatus"   => $result->request_status
            );
        }
    }
    
    echo jsonify($response);

    // var_dump($response);

});

$app->get("/emp/request/decline/:uid", function($uid){
    $requestStatus = "Declined";

    admin_emp_request($uid, $requestStatus);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->post("/emp/request/approve/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];

    if(count($param) === 2) {
        $dateRelease      = $_POST['dateRelease'];
        $requestStatus = "Approved";
        $dateModified  = date("Y-m-d H:i:s");

        admin_emp_request($uid, $dateRelease, $requestStatus, $dateModified);      
        $success = 1;
    }

    $response = array(
        "prompt" => 1
    );
    echo jsonify($response);
});

$app->get("/loans/individual/data/:var", function($var){
    $param     = explode(".", $var);
    $userUid     = $param[0];
    $token     = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_loans_by_user($userUid);
        foreach ($results as $result) {  
            $loan_uid   = $result->loan_uid;
            $loan       = read_loan_by_uid($loan_uid);
            $name       = $loan["name"];

            $emp_uid    = $result->emp_uid;
            $emp        = read_emp_by_uid($emp_uid);
            $firstname  = $emp["firstname"];
            $middlename = $emp["middlename"];
            $lastname   = $emp["lastname"];
            $empuid     = $emp["emp_uid"];

            $response[] = array(
                "uid"        => $result->uid,
                "firstname"  => $firstname,
                "middlename" => $middlename,
                "lastname"   => $lastname,
                "empuid"     => $empuid,
                "loan"       => $name, //$result->loan_uid, 
                "amount"     => $result->amount, 
                "interest"   => $result->interest_rate,
                "payment"    => $result->no_of_payment, 
                "requestStatus" => $result->request_status,  
                "date"       => $result->date_created      
            );
        }
    }
    
    echo jsonify($response);

    // var_dump($response);

});

$app->post("/instruction/new/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;
    if(count($param) === 1) {

        $instructionUid = xguid();
        $title        = $_POST['title']; 
        $instruction  = $_POST['instruction'];
        $curriculum   = $_POST['curriculum'];
        
        $dateCreated  = date("Y-m-d H:i:s");
        $dateModified = date("Y-m-d H:i:s");

        create_instruction($instructionUid, $title, $instruction, $curriculum, $dateCreated, $dateModified);
        $verified = 1;
    }
});

$app->get("/instruction/get/data/:var", function($var){
    $param     = explode(".", $var);
    $token     = $param[0];
    $response = array();

    if(count($param) === 1) {
        $curriculums = read_curriculum();
        foreach ($curriculums as $curriculum) {
            $uid   = $curriculum->uid;
            
            $department_uid    = $curriculum->department_uid;
            $department        = read_dep_by_uid($department_uid);        
            $group             = $department["group"];

            $topic_uid    = $curriculum->topic_uid;
            $topic        = read_topic_by_uid($topic_uid);        
            $topicname     = $department["name"];

            $response[] = array(
                "uid" => $curriculum->uid,
                "name" => $curriculum->name,
                "department" => $curriculum->department_uid,
                "group" => $group,
                // "categories" => $topic->categories     
            );
        }
    }

    echo jsonify($response);

});

$app->get("/instructions/get/:var", function($var){
    $param     = explode(".", $var);
    $currUid     = $param[0];
    $token     = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_instruction_one($currUid);
        foreach ($results as $result) {
            $response[] = array(
                "uid"        => $result->uid,
                "title"  => $result->title,
                "instruction" => $result->instruction,
                "curriculum"   => $result->curriculum_uid
            );
        }
    }
    
    echo jsonify($response);

    // var_dump($response);

});

$app->get("/instructions/get/:var", function($var){
    $param     = explode(".", $var);
    $currUid     = $param[0];
    $token     = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_instruction_one($currUid);
        foreach ($results as $result) {
            $response[] = array(
                "uid"        => $result->uid,
                "title"  => $result->title,
                "instruction" => $result->instruction,
                "curriculum"   => $result->curriculum_uid
            );
        }
    }
    
    echo jsonify($response);

    // var_dump($response);

});

$app->get("/instruction/view/edit/:var", function($var) {
    $param    = explode(".", $var);
    $uid      = $param[0];
    $token    = $param[1];
    $response = array();
    if(count($param) === 2) {
        $results = read_instruction_uid($uid);
        $success = 1;
        foreach($results as $result) {
            $response[] = array(
                    "uid" => $result->uid,
                    "title" => $result->title,
                    "instruction" => $result->instruction,
                    "curriculum" => $result->curriculum_uid
            );
        }
       echo jsonify($response);
        // var_dump($response);
    }
    
});

$app->post("/instruction/edit/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response      = array();
        $title        = $_POST['title'];
        $instruction  = $_POST['instruction'];
        $dateModified  = date("Y-m-d H:i:s");

        update_instruction($uid, $title, $instruction, $dateModified);      
        $success = 1;
    }
        $response = array(
            "success" => $success
        );
    echo jsonify($response);

});

$app->get("/delete/instruction/:uid", function($uid){
    $status = 0;

    delete_instruction($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/content/examination/get/:var", function($var){
    $param     = explode(".", $var);
    $currUid     = $param[0];
    $token     = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_examination_one($currUid);
        foreach ($results as $result) {
            $response[] = array(
                "uid"        => $result->uid,
                "name"  => $result->name,
                "content" => $result->content,
                "curriculum"   => $result->curriculum_uid,
                "type"        => $result->type,
                "choices"  => $result->choices,
                "answer" => $result->answer,
                "points"   => $result->points
            );
        }
    }
    echo jsonify($response);

    // var_dump($response);

});

$app->get("/get/rating/individual/:var", function($var){
    $param     = explode(".", $var);
    $examineeUid     = $param[0];
    $token     = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_rating_one($examineeUid);
        // $userUid = $ratings['examinee_uid'];

        // $results = get_merged_rating($userUid, $curUid);

        foreach ($results as $result) {

            $curriculum_uid   = $result->curriculum_uid;
            $curriculum       = read_curr_by_uid($curriculum_uid);
            $name       = $curriculum["name"];

            $response[] = array(
                "uid" => $result->uid,
                "rating" => $result->rating,
                "percentage" => $result->percentage,
                "curriculum" => $result->curriculum_uid,
                "curriculumName" => $name,
                "user" => $result->emp_uid   
            );
        }
    }

   echo jsonify($response);
   // var_dump($response);

});

$app->get("/get/data/training/individual/:var", function($var){
    $param     = explode(".", $var);
    $examineeUid     = $param[0];
    $token     = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_examinee_one($examineeUid);

        foreach ($results as $result) {
            $response[] = array(
                "uid" => $result->uid,
                "empUid" => $result->emp_uid,
                "currUid" => $result->curriculum_uid,
            );
        }
    }

    echo jsonify($response);
  // var_dump($response);

});

$app->get("/preview/instruction/:var", function($var){
    $param     = explode(".", $var);
    $uid     = $param[0];
    $token     = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_instruction_preview($uid);

        foreach ($results as $result) {

            $curUid = $result->curriculum_uid;

            $next   = get_next_instruction($uid, $curUid);
            $nextUid  = $next["uid"];
            $nextName = $next["title"];

            $previous   = get_previous_instruction($uid, $curUid);
            $previousUid       = $previous["uid"];
            $previousName = $previous["title"];


            $response[] = array(
                "nextUid" => $nextUid,
                "nextName" => $nextName,
                "previousUid" => $previousUid,
                "previousName" => $previousName,
                "uid"        => $result->uid,
                "title"  => $result->title,
                "instruction" => $result->instruction,
                "curriculum"   => $result->curriculum_uid
            );
        }
    }


    
    echo jsonify($response);

     // var_dump($response);

});

$app->get("/curriculum/exam/:var", function($var){
    $param     = explode(".", $var);
    $uid     = $param[0];
    $token     = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_curriculum_one($uid);
        foreach ($results as $result) {
            $response[] = array(
                "uid"        => $result->uid,
                "name"  => $result->name,
                "department" => $result->department_uid,
                "withExam"   => $result->with_exam
            );
        }
    }
    
    echo jsonify($response);

     // var_dump($response);

});

$app->post("/request/type/new/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;
    if(count($param) === 1) {
        $requestUid   = xguid();
        $request   = $_POST['request']; 
        $dateCreated   = date("Y-m-d H:i:s");
        $dateModified  = date("Y-m-d H:i:s");

       create_request_type($requestUid, $request, $dateCreated, $dateModified);
        $verified = 1;
    }  

});

$app->get("/request/get/data/:var", function($var){
    $param     = explode(".", $var);
    $token     = $param[0];
    $response = array();

    if(count($param) === 1) {
        $results = read_requests_all();
        foreach ($results as $result) {
            $uid    = $result->uid;
            $name   = $result->name;

            $response[] = array(
                "uid"        => $result->uid,
                "name"        => $result->name,
            );
        }
    }
    
    echo jsonify($response);

     // var_dump($response);

});

$app->get("/request/type/get/:var", function($var){
    $param    = explode(".", $var);
    $token    = $param[1];
    $uid      = $param[0];
    $response = array();
    
    if(count($param) === 2) {
        $results = read_request_type_uid($uid);
            foreach($results as $result) {
            $response[] = array(
                    "uid" => $result->uid,
                    "name" => $result->name, 
            );
        }
    }   

    echo jsonify($response);
     // var_dump($response);
}); 

$app->post("/request/type/update/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response      = array();
        $request        = $_POST['request'];
        $dateModified  = date("Y-m-d H:i:s");

        update_request_type($uid, $request, $dateModified);      
        $success = 1;
    }
        $response = array(
            "success" => $success
        );
    echo jsonify($response);

    // var_dump($uid);

});

$app->get("/delete/request/type/:uid", function($uid){
    $status = 0;

   delete_request_type($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/get/type/request/", function(){
    $response  = array();
    $requests = read_type_requests();

    foreach($requests as $request){
        $response[] = array(
            "uid"         => $request->uid,
            "name"        => $request->name
        );
    }
     echo jsonify($response);
    // var_dump($response);
});

$app->get("/emp/loan/decline/:uid", function($uid){
    $requestStatus = "Declined";

    admin_emp_loan($uid, $requestStatus);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/emp/loan/approve/:uid", function($uid){
    $requestStatus = "Approved";

    admin_emp_loan($uid, $requestStatus);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/delete/certificate/:uid", function($uid){
    $status = 0;

    delete_certificate($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/certificate/preview/data/:var", function($var){
    $param     = explode(".", $var);
    $uid     = $param[0];
    $token     = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_certificates_by_uid($uid);
        foreach ($results as $result) {
            $emp_uid    = $result->emp_uid;
            $emp        = read_emp_by_uid($emp_uid);
            $firstname  = $emp["firstname"];
            $middlename = $emp["middlename"];
            $lastname   = $emp["lastname"];
            $empuid     = $emp["emp_uid"];

            $uid   = $result->uid;

            $department_uid   = $result->department_uid;
            $department       = read_dep_by_uid($department_uid);
            $name       = $department["group"];

            $type   = $result->type;
            $types      = read_cert_type_by_uid($type);
            $typename       = $types["name"];


            $response[] = array(
                "uid" => $result->uid,
                "firstname"  => $firstname,
                "middlename" => $middlename,
                "lastname"   => $lastname,
                "empuid"     => $empuid,
                // "curriculum" => $examinee->curriculum_uid,
                "depname" => $name,
                "department" => $result->department_uid,
                "typename" => $typename,
                "type" => $result->type,
                "issued" => $result->date_created     
            );
        }
    }
    
    echo jsonify($response);
    // var_dump($response);

});

$app->get("/get/employee/evaluations/", function(){
    $response = array();
    // $latestDate = "1550-".date("m-d");
    $latestDate = date("m-d-y");
    $futureDate = date("m-d-y", strtotime("+3 week"));
    $evaluationResultsArray = array();
    $response = array();


    // echo "$latestDate = $futureDate";
    $datas = getUpcomingEvaluations($latestDate, $futureDate);
    
    foreach ($datas as $key => $data) {
        $employeeDetails = getEmployeeDetailsByUid($data->emp_uid);

        $employee   = read_emp_by_uid($data->emp_uid);
        $empfirstname  = $employee["firstname"];
        $empmiddlename = $employee["middlename"];
        $emplastname   = $employee["lastname"];
        $empuid     = $employee["emp_uid"];

        $evaluationResults[] = array(
            "nextEval" => $data->next_evaluation
        );
        array_push($evaluationResultsArray, $evaluationResults);

        $emp_uid = $data['emp_uid'];
        $next_evaluation = $data['next_evaluation'];

        if(isset($response[$emp_uid]))
            $index = ((count($response[$emp_uid]) - 1) / 2) + 1;
        else if(isset($response[$next_evaluation]))
            $index = ((count($response[$next_evaluation]) - 1) / 2) + 1;
        else
            $index = 1;


        $response[$emp_uid]['emp_uid'] = $emp_uid;
        $response[$emp_uid]['empfirstname'] = $empfirstname;
        $response[$emp_uid]['empmiddlename'] = $empmiddlename;
        $response[$emp_uid]['emplastname'] = $emplastname;
        $response[$emp_uid]['next_evaluation'] = $next_evaluation;
        $response[$emp_uid]['evaluations'] = $evaluationResults;


    }

    echo jsonify($response);
    //var_dump($response);
});

$app->post("/documents/new/this/:var", function($var) use ($app) {
    $param = explode(".", $var);
    if(count($param) == 3){
        $uid = $param[0];
        $reference = $param[1];
        $tempFilename = $_FILES["attachment"]["name"];
        $extension = pathinfo($tempFilename, PATHINFO_EXTENSION);
        $newFilename = preg_replace("/[^a-zA-Z0-9]+/", "", $tempFilename);
        $newFilename = str_replace($extension, "_" . md5(xguid()), $newFilename) . "." . $extension;
        $filename = $_FILES["attachment"]["name"];
        $mimeType = $_FILES["attachment"]["type"];
        $size = $_FILES["attachment"]["size"];
        $dateCreated = date("Y-m-d H:i:s");
        
        $path = "files/" . $newFilename;

        if(!file_exists($path)){
            move_uploaded_file($_FILES["attachment"]["tmp_name"], $path);
            updateFileStatusByReference($reference, 0);
            newFile($uid, $reference, $filename, $newFilename, $path, $mimeType, $size, $dateCreated);
        }
    }
});

$app->post("/documents/new/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;

    if(count($param) === 1) {
       $uid    = xguid();
        $empUid = $_POST["empUid"];
        $description = $_POST["description"];     
        $files = $_POST["files"];
        $date_created = date("Y-m-d H:i:s");
        $date_modified = date("Y-m-d H:i:s");       

        create_documents($uid, $empUid, $description, $files, $date_created, $date_modified);
        $verified = 1;
   
        $response = array(
            "error"        => 0,
            "errorMessage" => "SUCCESSFULLY CREATED!"
        );
    }
});

$app->get("/documents/individual/data/:var", function($var){
    $param     = explode(".", $var);
    $userUid     = $param[0];
    $token     = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_documents_by_user($userUid);
        foreach ($results as $result) {  
            $emp_uid    = $result->emp_uid;
            $emp        = read_emp_by_uid($emp_uid);
            $firstname  = $emp["firstname"];
            $middlename = $emp["middlename"];
            $lastname   = $emp["lastname"];
            $empuid     = $emp["emp_uid"];

            $response[] = array(
                "uid"        => $result->uid,
                "firstname"  => $firstname,
                "middlename" => $middlename,
                "lastname"   => $lastname,
                "empuid"     => $empuid,
                "description" => $result->description, 
                "files" => $result->files, 
                "date"       => $result->date_created      
            );
        }
    }
    
    echo jsonify($response);

    //var_dump($response);

});

$app->get("/delete/emp/documents/:uid", function($uid){
    $status = 0;

    delete_emp_document($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/loans/individual/approved/:var", function($var){
    $param     = explode(".", $var);
    $userUid     = $param[0];
    $token     = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_approved_loans_user($userUid);
        foreach ($results as $result) {  
            $loan_uid   = $result->loan_uid;
            $loan       = read_loan_by_uid($loan_uid);
            $name       = $loan["name"];

            $emp_uid    = $result->emp_uid;
            $emp        = read_emp_by_uid($emp_uid);
            $firstname  = $emp["firstname"];
            $middlename = $emp["middlename"];
            $lastname   = $emp["lastname"];
            $empuid     = $emp["emp_uid"];

            $response[] = array(
                "uid"        => $result->uid,
                "firstname"  => $firstname,
                "middlename" => $middlename,
                "lastname"   => $lastname,
                "empuid"     => $empuid,
                "loan"       => $name, //$result->loan_uid, 
                "amount"     => $result->amount, 
                "interest"   => $result->interest_rate,
                "payment"    => $result->no_of_payment, 
                "requestStatus" => $result->request_status,  
                "date"       => $result->date_created      
            );
        }
    }
    
   echo jsonify($response);

     //var_dump($response);

});

$app->get("/get/individual/documents/:var", function($var){
    $param     = explode(".", $var);
    $userUid     = $param[0];
    $token     = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_documents_by_user($userUid);
        foreach ($results as $result) {  
            $emp_uid    = $result->emp_uid;
            $emp        = read_emp_by_uid($emp_uid);
            $firstname  = $emp["firstname"];
            $middlename = $emp["middlename"];
            $lastname   = $emp["lastname"];
            $empuid     = $emp["emp_uid"];

            $response[] = array(
                "uid"        => $result->uid,
                "firstname"  => $firstname,
                "middlename" => $middlename,
                "lastname"   => $lastname,
                "empuid"     => $empuid,
                "files"     => $result->files, 
                "description"   => $result->description,     
            );
        }
    }
    
   echo jsonify($response);

    // var_dump($response);

});

$app->post("/emp/documents/edit/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response      = array();
        $empUid        = $_POST['employee'];
        $loanUid       = $_POST['loan'];
        $amount        = $_POST['amount'];
        $interestRate  = $_POST['interest'];
        $numberPayment = $_POST['payment'];
        $dateModified  = date("Y-m-d H:i:s");

        edit_loans($uid, $empUid, $loanUid, $amount, $interestRate, $numberPayment, $dateModified);
        
        $success = 1;
    }
    
        $response = array(
            "success" => $success
        );
    echo jsonify($response);

});

$app->get("/questionnaire/data/get/:var", function($var){
    $param    = explode(".", $var);
    $token    = $param[1];
    $uid      = $param[0];
    $response = array();
    
    if(count($param) === 2) {
        $results = read_questionnaire_by_uid($uid);
            foreach($results as $result) {
            $response[] = array(
                    "uid" => $result->uid,
                    "name" => $result->name, 
            );
        }
    }   

    echo jsonify($response);
    // var_dump($response);
}); 

$app->post("/questionnaire/update/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response      = array();
        $content        = $_POST['content'];
        $dateModified  = date("Y-m-d H:i:s");

        update_questionnaire($uid, $content, $dateModified);      
        $success = 1;
    }
        $response = array(
            "success" => $success
        );
    echo jsonify($response);

    // var_dump($uid);
});

$app->post("/employee/update/individual/:var" , function($var){
    $param          = explode(".", $var);
    $token          = $param[1];
    
    $empUid         = $param[0];
    $firstname      = utf8_decode($_POST['firstname']);
    $middlename     = utf8_decode($_POST['middlename']);
    $lastname       = utf8_decode($_POST['lastname']);
    $gender         = $_POST['gender'];
    $marital        = $_POST['marital'];
    $nationality    = $_POST['nationality'];
    $bday           = $_POST['bday'];
    $email          = $_POST['email'];
    $nickname       = $_POST['nickname'];
    $driverLicense  = $_POST['driverLicense'];
    $expiryLicense  = $_POST['expiryLicense'];
    $sssNo          = $_POST['sssNo'];
    $taxNo          = $_POST['taxNo'];
    $philhealthNo   = $_POST['philhealthNo'];
    $pagibigNo      = $_POST['pagibigNo'];
    // $dateCreated = date("Y-m-d H:i:s");
    $dateModified   = date("Y-m-d H:i:s");
    $status         = $_POST['status'];

    updateEmployeeOne($empUid , $firstname , $middlename , $lastname , $gender , $marital , $nationality , $bday , $email , $nickname , $driverLicense , $expiryLicense , $sssNo , $taxNo , $philhealthNo , $pagibigNo , $dateModified , $status);   
});

$app->post("/hris/employee/update/individual/:var" , function($var){
    $param          = explode(".", $var);
    $token          = $param[1];
    
    $empUid         = $param[0];
    $firstname      = utf8_decode($_POST['firstname']);
    $middlename     = utf8_decode($_POST['middlename']);
    $lastname       = utf8_decode($_POST['lastname']);
    $gender         = $_POST['gender'];
    $marital        = $_POST['marital'];
    $nationality    = $_POST['nationality'];
    $bday           = $_POST['bday'];
    $email          = $_POST['email'];
    $nickname       = $_POST['nickname'];
    $driverLicense  = $_POST['driverLicense'];
    $expiryLicense  = $_POST['expiryLicense'];
    $sssNo          = $_POST['sssNo'];
    $taxNo          = $_POST['taxNo'];
    $philhealthNo   = $_POST['philhealthNo'];
    $pagibigNo      = $_POST['pagibigNo'];
    // $dateCreated = date("Y-m-d H:i:s");
    $dateModified   = date("Y-m-d H:i:s");
    $status         = $_POST['status'];
    $taxStatus         = $_POST['taxStatus'];

    $housenumber = $_POST['housenumber'];
    $barangay = $_POST['barangay'];
    $city = $_POST['city'];
    $region = $_POST['region'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $bloodtype = $_POST['bloodtype'];

    updateEmployeeOneHRIS($empUid , $firstname , $middlename , $lastname , $gender , $marital , $nationality , $bday , $email , $nickname , $driverLicense , $expiryLicense , $sssNo , $taxNo , $philhealthNo , $pagibigNo , $dateModified , $status, $taxStatus, $housenumber, $barangay, $city, $region, $height, $weight, $bloodtype);   
});

$app->get("/get/captcha/", function(){
    header('Content-type: image/jpeg');
    $builder = new CaptchaBuilder;
    $builder->build();
    $output =  "<img src='".$builder->inline()."'/>";
    $answer =  $builder->getPhrase();
    $response = array(
		"answer" => $answer, 
		"output" => $output
    );	
    //echo jsonify($response);
	echo $output . " " . $answer;
}); 

/* Dashboard Notifications */ /*Created March 24, 2017 */ /* Last Modified March 27, 2017 */
$app->get("/get/dashboard/notifications/:var", function($var){
	$param = explode(".", $var);
    $token = $param[0];
	$response = array(
		"leaveCount" => 0,
		"overtimeCount" => 0,
		"holidayCount" => 0,
		"adjustmentCount" => 0
	);
	
	if(count($param) === 1) {
		$leaveCount = getLeaveRequestsNotification();
		$overtimeCount = getOvertimeRequestsNotification();
		$holidayCount = getHolidayRequestsNotification();
		$adjustmentCount = getTimeAdjustmentNewRequestNotification();
		
		$response = array(
			"leaveCount" => $leaveCount,
			"overtimeCount" => $overtimeCount,
			"holidayCount" => $holidayCount,
			"adjustmentCount" => $adjustmentCount
		);
	}
	
	echo jsonify($response);
});

$app->get("/get/employee/dashboard/notifications/:var", function($var){
	$param = explode(".", $var);
    $uid = $param[0];
	$token = $param[1];
	$response = array(
		"leaveCount" => 0,
		"overtimeCount" => 0,
		"holidayCount" => 0,
		"adjustmentCount" => 0
	);
	
	if(count($param) === 2) {
		$leaveCount = countLeaveRequestPendingNotification($uid);
		$overtimeCount = countOvertimeRequestPendingNotification($uid);
		$holidayCount = countHolidayRequestPendingNotification($uid);
		$adjustmentCount = countAdjustmentRequestPendingNotification($uid);
		
		$response = array(
			"leaveCount" => $leaveCount,
			"overtimeCount" => $overtimeCount,
			"holidayCount" => $holidayCount,
			"adjustmentCount" => $adjustmentCount
		);
	}	
	echo jsonify($response);
});

$app->get("/get/tax_table/:var", function($var) {
	$param = explode(".", $var);
    $token = $param[0];
	$response = array();
	if(count($param)===1) {
		$results = read_tax_type();
		foreach($results as $result) {
			$type = $result->uid;
			$tax_table = read_tax_table($type);
			foreach($tax_table as $table) {
				$response[] = array(
					"type" => $table->type,
					"row1" => $table->r1,
					"row2" => $table->r2,
					"row3" => $table->r3,
					"row4" => $table->r4,
					"row5" => $table->r5,
					"row6" => $table->r6,
					"row7" => $table->r7,
					"row8" => $table->r8
				);
			}
		}
	}
	echo jsonify($response);
});

$app->get("/get/tax_exempt/:var", function($var) {
	$param = explode(".", $var);
    $token = $param[0];
	$field = $param[1];
	$response = array();
	$arr = "";
	if(count($param)===2) {
		for($i=1;$i<=8;$i++) {
			$results = read_tax_exempt($field, $i);			
			foreach($results as $result) {				
				if($arr!="") {
					$arr .= "," . $result->$field;
				}	
				else {
					$arr .= $result->$field;
				}
			}
		}
		
		$arr = trim($arr);
		$array = explode(",", $arr);
		$arrCount = count($array) - 1;
		
		$response = array(
			"row1" => $array[0],
			"row2" => $array[1],
			"row3" => $array[2],
			"row4" => $array[3],
			"row5" => $array[4],
			"row6" => $array[5],
			"row7" => $array[6],
			"row8" => $array[7],
		);
	}
	echo jsonify($response);
	//echo $arr . $arrCount;
});

$app->get("/get/bank/:var", function($var) {
	$param = explode(".", $var);
    $token = $param[0];
	$response = array();
	
	if( count($param) === 1 ) {
		$results = read_bank();
		foreach($results as $result) {
			# id uid bank code account_number company_code presenting_office remarks date_created date_modified status
			$response[] = array(
				"uid" => $result->uid,
				"bank" => $result->bank,
				"code" => $result->code,
				"account_number" => $result->account_number,
				"company_code" => $result->company_code,
				"presenting_office" => $result->presenting_office,
				"remarks" => $result->remarks
			);
		}
	}
	
	echo jsonify($response);
});

$app->get("/get/bank/edit/:var", function($var) {
	$param = explode(".", $var);
    $uid = $param[0];
	$token = $param[1];
	$response = array();
	
	if( count($param ) === 2 ) {
		$result = read_bank_by_uid($uid);
		$response = array(
			"uid" => $result->uid,
			"bank" => $result->bank,
			"code" => $result->code,
			"account_number" => $result->account_number,
			"company_code" => $result->company_code,
			"presenting_office" => $result->presenting_office,
			"remarks" => $result->remarks
		);
	}
	
	echo jsonify($response);
});

# =================================================================================================================
# =================================================================================================================
# =================================================================================================================
# ================================================== HUMANO HRIS ==================================================
# =================================================================================================================
# =================================================================================================================
# =================================================================================================================
# =================================================================================================================
# =================================================================================================================

/*START JEN*/

/* START LOANS*/

$app->get("/emp/loans/get/data/:var", function($var){
    $param     = explode(".", $var);
    $token     = $param[0];
    $response = array();

    if(count($param) === 1) {
        $results = read_emp_loans();
        foreach ($results as $result) {
            $loan_uid   = $result->loan_uid;
            $loan       = read_loan_by_uid($loan_uid);
            $name       = $loan["name"];

            $emp_uid    = $result->emp_uid;
            $emp        = read_emp_by_uid($emp_uid);
            $firstname  = $emp["firstname"];
            $middlename = $emp["middlename"];
            $lastname   = $emp["lastname"];
            $empuid     = $emp["emp_uid"];

            $response[] = array(
                "uid"        => $result->uid,
                "firstname"  => $firstname,
                "middlename" => $middlename,
                "lastname"   => $lastname,
                "empuid"     => $empuid,
                "loan"       => $name, //$result->loan_uid, 
                "amount"     => $result->amount, 
                "interest"   => $result->interest_rate,
                "payment"    => $result->no_of_payment,
                "requestStatus" => $result->request_status,   
                "date"       => $result->date_created      
            );
        }
    }
    
    echo jsonify($response);
    
});

$app->post("/emp/loan/new/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;
    if(count($param) === 1) {

        $empLoanUid    = xguid();
        $empUid        = $_POST['employee'];
        $loanUid       = $_POST['loan'];
        $amount        = $_POST['amount'];
        $numberPayment = $_POST['payment'];
        $requestStatus = $_POST['requestStatus'];
        
        $dateCreated   = date("Y-m-d H:i:s");
        $dateModified  = date("Y-m-d H:i:s");

        // $check         = check_emp_loans($empUid);
    
            // if($check >= 1){
            //     $response = array(
            //         "error"        => 1,
            //         "errorMessage" => "EMPLOYEE EXISTING!"
            //     );
            // }else if($check == 0){

                create_employee_loans($empLoanUid, $empUid, $loanUid, $amount, $numberPayment, $requestStatus, $dateCreated, $dateModified);
                $verified = 1;

                $response[] = array(
                    "verified" => $verified,
                );    

                echo jsonify($response);
            // }
    }

});

$app->get("/get/type/loans/", function(){
    $response  = array();
    $loantypes = read_type_loans();

    foreach($loantypes as $loantype){
        $response[] = array(
            "uid"         => $loantype->uid,
            "name"        => $loantype->name
        );
    }
    echo jsonify($response);
});

$app->get("/type/loans/data/:var", function($var){
    $response = array();
    $types    = read_type_loans_all();

    foreach($types as $type){
        $response[] = array(
            "uid"   => $type->uid,
            "name"  => $type->name
        );
    }
    echo jsonify($response);

});

$app->post("/loantype/new/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;
    if(count($param) === 1) {

        $loanTypeUid = xguid();
        $type = $_POST['type'];
        
        $dateCreated   = date("Y-m-d H:i:s");
        $dateModified  = date("Y-m-d H:i:s");

        create_loantype($loanTypeUid, $type, $dateCreated, $dateModified);
        $verified = 1;
    }

});

$app->get("/loans/view/edit/:var", function($var) {
    $param    = explode(".", $var);
    $uid      = $param[0];
    $token    = $param[1];
    $response = array();
    if(count($param) === 2) {
        $results = read_loans_uid($uid);
        $success = 1;
        foreach($results as $result) {
            $response[] = array(
                "uid"      => $result->uid,
                "employee" => $result->emp_uid,
                "loan"     => $result->loan_uid, 
                "amount"   => $result->amount, 
                "interest" => $result->interest_rate,
                "payment"  => $result->no_of_payment,
                "remaining" => $result->remaining_balance,   
                "date"     => $result->date_created      

            );
        }
        echo jsonify($response);
    }
});

$app->post("/emp/loan/edit/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response      = array();
        $empUid        = $_POST['employee'];
        $loanUid       = $_POST['loan'];
        $amount        = $_POST['amount'];
        $interestRate  = $_POST['interest'];
        $numberPayment = $_POST['payment'];
        $dateModified  = date("Y-m-d H:i:s");

        edit_loans($uid, $empUid, $loanUid, $amount, $interestRate, $numberPayment, $dateModified);
        
        $success = 1;
    }
    
        $response = array(
            "success" => $success
        );
    echo jsonify($response);

});

$app->post("/updated/loan/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response         = array();
        $remainingAmount  = $_POST['remainingAmount'];
        $dateModified     = date("Y-m-d H:i:s");

        updated_loans($uid, $remainingAmount, $dateModified);
        
        $success = 1;
    }
    
        $response = array(
            "success" => $success
        );
    echo jsonify($response);

});

$app->post("/type/loan/edit/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response      = array();
        $type        = $_POST['type'];
        $dateModified  = date("Y-m-d H:i:s");

        edit_type_loans($uid, $type, $dateModified);
        
        $success = 1;
    }
    
        $response = array(
            "success" => $success
        );
    echo jsonify($response);

});


## LATEST EMPLOYEE LOANS - START ## DATE: JUNE 23, 2021
$app->post("/new/employee/loans/:var", function($var) {
    $param = explode(".", $var);
    $token = $param["0"];
    $success = 0;
    $response = array();
    if(count($param)===1) {
        $emp_uid = $_POST['employee_name'];
        $loan_uid = $_POST['loan_type'];
        $application_no = $_POST['application_no'];
        $amortization = $_POST['amortization'];
        $terms = $_POST['terms'];
        $loan_granted = $_POST['loan_granted'];
        $date_granted = $_POST['date_granted'];
        $first_amortization = $_POST['first_amortization']; 
        $amortization_date = date("Y-m-d", strtotime($first_amortization."/15"));
        $amortization_period = date("Y-m-d", strtotime($amortization_date ." +" . $terms . " months"));

        $validated = validate_new_employee_loans_by_loan_uid($emp_uid, $loan_uid);
        if($validated===True) {
            create_new_employee_loans($emp_uid, $loan_uid, $application_no, $amortization, $terms, $loan_granted, $date_granted,$first_amortization, $amortization_period);
            $success = 1;
        }else{
            $success = 2;
        }
    }
    $response = array(
        "success" => $success
    );

    echo jsonify($response);
});

$app->post("/edit/employee/loans/:var", function($var) {
    $param = explode(".", $var);
    $token = $param["0"];
    $uid = $param["1"];
    $success = 0;
    $response = array();
    if(count($param)===2) {
        $emp_uid = $_POST['employee_name'];
        $loan_uid = $_POST['loan_type'];
        $application_no = $_POST['application_no'];
        $amortization = $_POST['amortization'];
        $terms = $_POST['terms'];
        $loan_granted = $_POST['loan_granted'];
        $date_granted = $_POST['date_granted'];
        $first_amortization = $_POST['first_amortization']; $amortization_date = date("Y-m-d", strtotime($first_amortization."/15"));
        $amortization_period = date("Y-m-d", strtotime($amortization_date ." +" . $terms . " months"));

        $validated = validate_new_employee_loans_by_uid($uid);
        if($validated===true) {
            update_new_employee_loans_by_uid($uid, $application_no, $amortization, $terms, $loan_granted, $date_granted,$first_amortization, $amortization_period);
            $success = 1;
        }else{
            $success = 2;
        }
        
    }

    $response = array(
        "success" => $success
    );

    echo jsonify($response);
});

$app->get("/get/employee/loans/:var", function($var) {
    $param = explode(".", $var);
    $token = $param["0"];
    $success = 0;
    $response = array();
    if(count($param)===1) {
        $results = read_new_employee_loans();
        $loan_type = "";
        $emp_name = "";
        foreach($results as $result) {
            $emp_name = read_employee_lastname_by_uid($result->emp_uid);
            $loan_type = read_loan_by_uid($result->loan_uid);
            $response[] = array(
                "uid" => $result->uid,
                "emp_uid" => $result->emp_uid,
                "emp_name" => $emp_name,
                "loan_uid" => $result->loan_uid,
                "loan_type" => $loan_type->name,
                "application_no" => $result->application_no,
                "amortization" => $result->amortization,
                "terms" => $result->terms,
                "loan_granted" => $result->loan_granted,
                "date_granted" => $result->date_granted,
                "first_amortization" => $result->first_monthly_amortization,
                "amortization_period" => $result->amortization_period
            );
            
        }
    }
    // echo jsonify($loan_type); 
    echo jsonify($response);
});

$app->get("/get/employee/loans/edit/:var", function($var) {
    $param = explode(".", $var);
    $token = $param["0"];
    $uid = $param["1"];
    $success = 0;
    $response = array();
    if(count($param)===2) {
        $results = read_new_employee_loans_by_uid($uid);
        $loan_type = "";
        $emp_name = "";
        foreach($results as $result) {
            $emp_name = read_employee_lastname_by_uid($result->emp_uid);
            $loan_type = read_loan_by_uid($result->loan_uid);
            $response[] = array(
                "uid" => $result->uid,
                "emp_uid" => $result->emp_uid,
                "emp_name" => $emp_name,
                "loan_uid" => $result->loan_uid,
                "loan_type" => $loan_type->name,
                "application_no" => $result->application_no,
                "amortization" => $result->amortization,
                "terms" => $result->terms,
                "loan_granted" => $result->loan_granted,
                "date_granted" => $result->date_granted,
                "first_amortization" => $result->first_monthly_amortization,
                "amortization_period" => $result->amortization_period
            ); 
        }
    }

    echo jsonify($response);
});

## LATEST EMPLOYEE LOANS - END ## DATE: JUNE 23, 2021

/*END LOANS*/

/* START DEPARTMENT*/
$app->get("/get/data/department/:var", function($var){
    $response = array();
    $deps = read_departments();

    foreach($deps as $dep){
        $response[] = array(
            "uid"    => $dep->uid,
            "group"  => $dep->group
        );
    }
    echo jsonify($response);

});

/*END DEPARTMENT*/

$app->get("/get/data/content/:var", function($var){
    $response = array();
    $contents = read_training_content();

    foreach($contents as $content){
        $response[] = array(
            "uid"    => $content->uid,
            "name"  => $content->name
        );
    }
    echo jsonify($response);

});

$app->post("/training/topic/new/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;
    if(count($param) === 1) {

        $topicUid     = xguid();
        $topic        = $_POST['topic']; 
        // $categories   = $_POST['categories'];     
        $dateCreated  = date("Y-m-d H:i:s");
        $dateModified = date("Y-m-d H:i:s");

        create_topics($topicUid, $topic, $dateCreated, $dateModified);
        $verified = 1;
    }
});

$app->post("/training/topic/edit/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response      = array();
        $topic        = $_POST['topic'];
        $dateModified  = date("Y-m-d H:i:s");

        update_topics($uid, $topic, $dateModified);      
        $success = 1;
    }
        $response = array(
            "success" => $success
        );
    echo jsonify($response);

});

$app->post("/category/new/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;
    if(count($param) === 1) {

        $categoryUid = xguid();
        $name        = $_POST['name']; 
        // $topicUid    = $_POST['topic_uid'];
        
        $dateCreated  = date("Y-m-d H:i:s");
        $dateModified = date("Y-m-d H:i:s");

        create_category($categoryUid, $name, $dateCreated, $dateModified);
        $verified = 1;
    }
});

$app->get("/curriculum/get/data/:var", function($var){
    $param     = explode(".", $var);
    $token     = $param[0];
    $response = array();

    if(count($param) === 1) {
        $curriculums = read_curriculum();
        foreach ($curriculums as $curriculum) {
            $uid   = $curriculum->uid;
            
            $department_uid    = $curriculum->department_uid;
            $department        = read_dep_by_uid($department_uid);        
            $group             = $department["group"];

            $topic_uid    = $curriculum->topic_uid;
            $topic        = read_topic_by_uid($topic_uid);        
            $topicname     = $department["name"];

            $response[] = array(
                "uid" => $curriculum->uid,
                "name" => $curriculum->name,
                "department" => $curriculum->department_uid,
                "group" => $group,
                // "categories" => $topic->categories     
            );
        }
    }

    echo jsonify($response);

});

$app->get("/curriculum/data/get/:var" , function($var){
    $param    = explode(".", $var);
    $token    = $param[1];
    $uid      = $param[0];
    
    $curriculum = curriculum_by_uid($uid);

    $department_uid    = $curriculum->department_uid;
    $department        = read_dep_by_uid($department_uid);        
    $group             = $department["group"];

    $response = array(
            "name" => $curriculum->name,
            "department" => $curriculum->department_uid,
            "group" => $group,
    );
    echo jsonify($response);
});

$app->get("/get/data/curriculum/:var", function($var){
    $response = array();
    $topics = read_curriculum_all();

    foreach($topics as $topic){
        $response[] = array(
            "uid"    => $topic->uid,
            "name"  => $topic->name
        );
    }
    echo jsonify($response);

});

$app->post("/curriculum/new/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;
    if(count($param) === 1) {

        $curriculumUid = xguid();
        $name        = $_POST['name']; 
        $departmentUid    = $_POST['department'];
        
        $dateCreated  = date("Y-m-d H:i:s");
        $dateModified = date("Y-m-d H:i:s");

        create_curriculum($curriculumUid, $name, $departmentUid, $dateCreated, $dateModified);
        $verified = 1;
    }
});

$app->get("/get/data/curriculums/:var", function($var){
    $response = array();
    $curriculums = read_curriculum_all();

    foreach($curriculums as $curriculum){
        $response[] = array(
            "uid"    => $curriculum->uid,
            "name"  => $curriculum->name
        );
    }
    echo jsonify($response);

});

$app->post("/curriculum/update/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response      = array();
        $name        = $_POST['name'];
        $department  = $_POST['department'];
        $dateModified  = date("Y-m-d H:i:s");

        update_curriculum($uid, $name, $department, $dateModified);      
        $success = 1;
    }
        $response = array(
            "success" => $success
        );
    echo jsonify($response);

});

/*END TOPIC*/

$app->get("/examinee/get/data/:var", function($var){
    $param     = explode(".", $var);
    $token     = $param[0];
    $response = array();

    if(count($param) === 1) {
        $examinees = read_examinee();
        foreach ($examinees as $examinee) {
            $emp_uid    = $examinee->emp_uid;
            $emp        = read_emp_by_uid($emp_uid);
            $firstname  = $emp["firstname"];
            $middlename = $emp["middlename"];
            $lastname   = $emp["lastname"];
            $empuid     = $emp["emp_uid"];

            $uid   = $examinee->uid;

            $curriculum_uid   = $examinee->curriculum_uid;
            $curriculum       = read_curr_by_uid($curriculum_uid);
            $name       = $curriculum["name"];


            $response[] = array(
                "uid" => $examinee->uid,
                "firstname"  => $firstname,
                "middlename" => $middlename,
                "lastname"   => $lastname,
                "empuid"     => $empuid,
                // "curriculum" => $examinee->curriculum_uid,
                "currname" => $name,
                "curriculum" => $examinee->curriculum_uid,
                "end" => $examinee->end_date,
                "start" => $examinee->date_created     
            );
        }
    }
    
    echo jsonify($response);
});

$app->get("/examinee/individual/data/:var", function($var){
    $param     = explode(".", $var);
    $traineeUid  = $param[0];
    $token       = $param[1];
    $response = array();

    if(count($param) === 2) {
        $examinees = read_trainee_by_uid($traineeUid);
        foreach ($examinees as $examinee) {
            $emp_uid    = $examinee->emp_uid;
            $emp        = read_emp_by_uid($emp_uid);
            $firstname  = $emp["firstname"];
            $middlename = $emp["middlename"];
            $lastname   = $emp["lastname"];
            $empuid     = $emp["emp_uid"];

            $uid   = $examinee->uid;

            $curriculum_uid   = $examinee->curriculum_uid;
            $curriculum       = read_curr_by_uid($curriculum_uid);
            $name       = $curriculum["name"];


            $response[] = array(
                "uid" => $examinee->uid,
                "firstname"  => $firstname,
                "middlename" => $middlename,
                "lastname"   => $lastname,
                "empuid"     => $empuid,
                // "curriculum" => $examinee->curriculum_uid,
                "currname" => $name,
                "curriculum" => $examinee->curriculum_uid,
                "end" => $examinee->end_date,
                "start" => $examinee->date_created     
            );
        }
    }
    
    echo jsonify($response);

    // var_dump($response);
});

$app->get("/view/examinee/topics/:var", function($var){

    $param          = explode(".", $var);
    $curriculumUid  = $param[0];
    $token          = $param[1];
    $response       = array();

    if(count($param) === 2) {
        $results = read_curriculum_by_user($curriculumUid);   
        foreach($results as $result) {
            $response[] = array(
                "uid"        => $result->uid,
                "name"   => $result->name,
                "curriculum" => $result->curriculum_uid, 
                "dateCreated"    => $result->date_created
            );
        }     
    }
     // var_dump($response);
     echo jsonify($response);

});

$app->get("/view/rating/:var", function($var){

    $param          = explode(".", $var);
    $examUid        = $param[0];
    $token          = $param[1];
    $response       = array();

    if(count($param) === 2) {
        $results = read_rating_by_uid($examUid);   
        foreach($results as $result) {
            $response[] = array(
                "uid"          => $result->uid,
                "rating"       => $result->rating,
                "topic_uid"    => $result->topic_uid, 
                "examinee_uid" => $result->examinee_uid
            );
        }     
    }
     // var_dump($results);
     echo jsonify($response);

});

$app->get("/view/exam/subtopics/:var", function($var){

    $param     = explode(".", $var);
    $topicUid  = $param[0];
    $token     = $param[1];
    $response  = array();

    if(count($param) === 2) {
        $results = read_results_by_subtopic($topicUid);

        foreach($results as $result) {
            $subtopic = read_subtopic_by_uid($result->subtopic_uid);
            $name = $subtopic["name"];

            $response[] = array(
                "uid"        => $result->uid,
                "subtopic"   => $name,
                "topic"   => $result->topic_uid,
                "score" => $result->rating, 
                "rate"    => $result->percentage
            );
        }
       
    }
     // var_dump($response);
     
     echo jsonify($response);

});

$app->get("/view/examinee/subtopics/:var", function($var){

    $param     = explode(".", $var);
    $topicUid  = $param[0];
    $token     = $param[1];
    $response  = array();

    if(count($param) === 2) {
        $results = read_subtopics_by_topic($topicUid);
    //     $success = 1;
        foreach($results as $result) {
            $response[] = array(
                "uid"        => $result->uid,
                "name"   => $result->name,
                "topic" => $result->topic_uid, 
                "dateCreated"    => $result->date_created
            );
        }
       
    }
     // var_dump($response);
     


     echo jsonify($response);

});

$app->get("/examinee/view/:var", function($var){
    $param    = explode(".", $var);
    $token    = $param[1];
    $uid      = $param[0];

    if(count($param) === 2) {
        $examinee = read_examinee_uid($uid);

        $curriculum_uid   = $examinee->curriculum_uid;
        $curriculum       = read_curr_by_uid($curriculum_uid);
        $name             = $curriculum["name"];

        $emp_uid    = $examinee->emp_uid;
        $emp        = read_emp_by_uid($emp_uid);
        $firstname  = $emp["firstname"];
        $middlename = $emp["middlename"];
        $lastname   = $emp["lastname"];
        $empuid     = $emp["emp_uid"];

            // $uid   = $results->uid;

            $response[] = array(
                "uid" => $examinee->uid,
                "firstname"  => $firstname,
                "middlename" => $middlename,
                "lastname"   => $lastname,
                "empuid"     => $empuid,
                // "curriculum" => $results->curriculum_uid,
                "currname" => $name,
                "curriculum" => $examinee->curriculum_uid,
                "end" => $examinee->end_date,
                "start" => $examinee->date_created  
            );
        }        
    
    echo jsonify($response);


});

$app->post("/examinee/new/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;
    if(count($param) === 1) {

        $examineeUid   = xguid();
        $employeeUid   = $_POST['employee']; 
        $curriculumUid = $_POST['curriculum'];
        $endDate       = $_POST['endDate'];
        
        $dateCreated   = date("Y-m-d H:i:s");
        $dateModified  = date("Y-m-d H:i:s");

        create_examinee($examineeUid, $employeeUid, $curriculumUid, $endDate, $dateCreated, $dateModified);
        $verified = 1;
    }
});

$app->post("/examinee/update/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response      = array();
        $employee        = $_POST['employee'];
        $curriculum     = $_POST['curriculum'];
        $endDate        = $_POST['endDate'];
        $dateModified  = date("Y-m-d H:i:s");

        update_examinee($uid, $employee, $curriculum, $endDate, $dateModified);      
        $success = 1;
    }
        $response = array(
            "success" => $success
        );
    echo jsonify($response);

});

$app->get("/examinee/view/edit/:var", function($var) {
    $param    = explode(".", $var);
    $uid      = $param[0];
    $token    = $param[1];
    $response = array();
    if(count($param) === 2) {
        $results = read_examinee_uid($uid);
        $success = 1;
        foreach($results as $result) {
            $response[] = array(
                "uid"        => $result->uid,
                "empuid"   => $result->emp_uid,
                "curriculum" => $result->curriculum_uid, 
                "endDate"    => $result->end_date
            );
        }
        echo jsonify($response);
    }
});

$app->get("/view/examinee/ratings/:var", function($var){

    $param          = explode(".", $var);
    $examineeUid    = $param[0];
    $token          = $param[1];
    $response       = array();

    if(count($param) === 2) {
        $results = read_rating_by_examinee($examineeUid);   
        foreach($results as $result) {
            $response[] = array(
                "uid"        => $result->uid,
                "rating"   => $result->rating,
                "topic" => $result->topic_uid, 
                "examinee" => $result->examinee_uid, 
                "dateCreated"    => $result->date_created
            );
        }     
    }
     // var_dump($response);
     echo jsonify($response);

});

$app->get("/curriculum/view/edit/:var", function($var) {
    $param    = explode(".", $var);
    $uid      = $param[0];
    $token    = $param[1];
    $response = array();
    if(count($param) === 2) {
        $results = read_curriculum_uid($uid);
        $success = 1;
        foreach($results as $result) {
            $response[] = array(
                "uid"        => $result->uid,
                "name"   => $result->name,
                "department" => $result->department_uid, 
                "topic"    => $result->topic
            );
        }
        echo jsonify($response);
    }
    
});

$app->post("/topic/new/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;

    if(count($param) === 1) {
        $topicUid       = xguid();
        $topic    = utf8_decode($_POST['topic']);
        $curriculumUid = ($_POST['curriculum']);
        
        $dateCreated   = date("Y-m-d H:i:s");
        $dateModified  = date("Y-m-d H:i:s");

        create_training_topics($topicUid, $topic, $curriculumUid, $dateCreated, $dateModified);
        $verified = 1;
   
        $response = array(
            "error"        => 0,
            "errorMessage" => "SUCCESSFULLY CREATED!"
        );
    }
    echo jsonify($response);
});

$app->get("/topic/get/data/:var", function($var){
    $param = explode(".", $var);
    $uid = $param[0];
    $token = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_training_topics_by_uid($uid);
        foreach($results as $result) {
            $response[] = array(
                "uid" => $result->uid,
                "name" => $result->name
            );
        }        
    }
    
    echo jsonify($response);

});

$app->get("/topic/data/get/:var" , function($var){
    $param    = explode(".", $var);
    $token    = $param[1];
    $uid      = $param[0];
    
    $topic = read_topic_by_uid($uid);

    $response = array(
            "name" => $topic->name,
            "uid" => $topic->uid
    );
    echo jsonify($response);
});

$app->post("/topic/update/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response      = array();
        $name        = $_POST['name'];
        $dateModified  = date("Y-m-d H:i:s");

        update_topics($uid, $name, $dateModified);      
        $success = 1;
    }
        $response = array(
            "success" => $success
        );
    echo jsonify($response);

});


$app->get("/subtopic/view/:var", function($var){
    $param = explode(".", $var);
    $uid = $param[0];
    $token = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_training_subtopics_by_uid($uid);
        foreach($results as $result) {
            $response[] = array(
                "uid" => $result->uid,
                "name" => $result->name
            );
        }        
    }
    
    echo jsonify($response);

});

$app->get("/subtopic/data/get/:var" , function($var){
    $param    = explode(".", $var);
    $token    = $param[1];
    $uid      = $param[0];
    
    $subtopic = read_subtopic_by_uid($uid);

    $response = array(
            "name" => $subtopic->name,
            "uid" => $subtopic->uid
    );
    echo jsonify($response);

    // var_dump($response);
});

$app->get("/content/view/:var", function($var){
    $param = explode(".", $var);
    $uid = $param[0];
    $token = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_training_contents_by_uid($uid);
        foreach($results as $result) {
            $response[] = array(
                "uid" => $result->uid,
                "name" => $result->name,
                "content" => $result->content,
                "curriculum" => $result->curriculum_uid, 
                "type" => $result->type,
                "answer" => $result->answer
            );
        }        
    }
        // var_dump($response);
    echo jsonify($response);

});

$app->get("/subtopics/get/data/:var", function($var){
    $param     = explode(".", $var);
    $token     = $param[0];
    $response = array();

    if(count($param) === 1) {
        $subtopics = read_training_subtopics();
        foreach($subtopics as $subtopic){
            $uid   = $subtopic->uid;
            $name   = $subtopic->name;
            $topic   = $subtopic->topic_uid;
            $curriculum   = $subtopic->curriculum_uid;


            $response[] = array(
                "uid"         => $subtopic->uid,
                "name"        => $subtopic->name,
                "topic"       => $subtopic->topic_uid,
                "curriculum"  => $subtopic->curriculum_uid
            );
        }
    }
    echo jsonify($response);
});

$app->post("/subtopic/new/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;

    if(count($param) === 1) {
        $subtopicUid       = xguid();
        $subtopic    = utf8_decode($_POST['subtopic']);
        $topic = ($_POST['topic']);
        
        $dateCreated   = date("Y-m-d H:i:s");
        $dateModified  = date("Y-m-d H:i:s");

        create_training_subtopics($subtopicUid, $subtopic, $topic, $dateCreated, $dateModified);
        $verified = 1;
   
        $response = array(
            "error"        => 0,
            "errorMessage" => "SUCCESSFULLY CREATED!"
        );
    }
    echo jsonify($response);
});

$app->post("/subtopic/update/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response      = array();
        $name        = $_POST['name'];
        $dateModified  = date("Y-m-d H:i:s");

        update_subtopic($uid, $name, $dateModified);      
        $success = 1;
    }
        $response = array(
            "success" => $success
        );
    echo jsonify($response);

});

$app->get("/view/curriculum/user/:var", function($var) {
    $param    = explode(".", $var);
    $uid      = $param[0];
    $token    = $param[1];
    $response = array();
    if(count($param) === 2) {
        $results = read_curriculum_by_user($uid);
        $success = 1;
        foreach($results as $result) {
            $response[] = array(
                "uid"        => $result->emp_uid,
                "curriculum" => $result->curriculum_uid, 
                "examineeUid"    => $result->uid
            );
        }
        echo jsonify($response);
    }
    
});

$app->post("/content/new/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;
    $withExam = 1;

    
    if(count($param) === 1) {
        $contentUid       = xguid();
        $name    = $_POST['name'];
        $content    = $_POST['content'];
        $curriculum = $_POST['curriculum'];
        $type = $_POST['type'];
        $choices = $_POST['choices'];
        $answer = $_POST['answer'];
        $points = $_POST['points'];
        
        $dateCreated   = date("Y-m-d H:i:s");
        $dateModified  = date("Y-m-d H:i:s");

        create_training_content($contentUid, $name, $content, $curriculum, $type, $choices, $answer, $points, $dateCreated, $dateModified);
        update_with_exam($curriculum, $withExam);
        $verified = 1;
   
        $response = array(
            "error"        => 0,
            "errorMessage" => "SUCCESSFULLY CREATED!"
        );
    }
    echo jsonify($response);
});

$app->get("/content/data/get/:var", function($var){
    $param    = explode(".", $var);
    $token    = $param[1];
    $uid      = $param[0];
    $response = array();
    
    if(count($param) === 2) {
        $results = read_training_contents_by_uid($uid);
            foreach($results as $result) {
            $response[] = array(
                    "uid" => $result->uid,
                    "name" => $result->name, 
                    "content" => $result->content,
                    "subtopic" => $result->subtopic_uid,
                    "type" => $result->type,
                    "choices" => $result->choices,
                    "answer" => $result->answer,
                    "points" => $result->points

            );
        }
    }

    echo jsonify($response);
    // var_dump($response);
}); 

$app->post("/rating/new/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;

    if(count($param) === 1) {
        $ratingUid       = xguid();

        $rating    = $_POST['rating'];
        $percentage = $_POST['percentage'];
        $examinee = $_POST['examinee'];
        $content = $_POST['content'];
        $insUid = $_POST['insUid'];
        $examineeUid = $_POST['examineeUid'];
        
        $dateCreated   = date("Y-m-d H:i:s");
        $dateModified  = date("Y-m-d H:i:s");

        create_training_rating($ratingUid, $rating, $percentage, $insUid, $examinee, $content, $examineeUid, $dateCreated, $dateModified);
        $verified = 1;
   
        $response = array(
            "error"        => 0,
            "errorMessage" => "SUCCESSFULLY CREATED!"
        );
    }
    echo jsonify($response);
});

$app->post("/certificate/type/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;

    if(count($param) === 1) {
        $certificateUid       = xguid();
        $name    = $_POST['name'];
        
        $dateCreated   = date("Y-m-d H:i:s");
        $dateModified  = date("Y-m-d H:i:s");

        create_certificate_type($certificateUid, $name, $dateCreated, $dateModified);
        $verified = 1;
   
        $response = array(
            "error"        => 0,
            "errorMessage" => "SUCCESSFULLY CREATED!"
        );
    }
    echo jsonify($response);
});

$app->post("/memo/type/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;

    if(count($param) === 1) {
        $memoUid       = xguid();
        $name    = $_POST['name'];
        
        $dateCreated   = date("Y-m-d H:i:s");
        $dateModified  = date("Y-m-d H:i:s");

        create_memo_type($memoUid, $name, $dateCreated, $dateModified);
        $verified = 1;
   
        $response = array(
            "error"        => 0,
            "errorMessage" => "SUCCESSFULLY CREATED!"
        );
    }
    echo jsonify($response);
});

$app->get("/get/certificate/type/:var", function($var){
    $response = array();
    $results = read_certificate_type();

    foreach ($results as $result) {
        $response[] = array(
            "uid" => $result->uid, 
            "name" => $result->name
        );
    }

    // var_dump($results);
    echo jsonify($response);
});

$app->get("/get/memo/type/:var", function($var){
    $response = array();
    $results = read_memo_type();

    foreach ($results as $result) {
        $response[] = array(
            "uid" => $result->uid, 
            "name" => $result->name
        );
    }

    // var_dump($results);
    echo jsonify($response);
});

$app->get("/certificate/data/get/:var", function($var){
    $param    = explode(".", $var);
    $token    = $param[1];
    $uid      = $param[0];
    $response = array();
    
    if(count($param) === 2) {
        $results = read_certificate_type_by_uid($uid);
            foreach($results as $result) {
            $response[] = array(
                    "uid" => $result->uid,
                    "name" => $result->name, 
            );
        }
    }   

    echo jsonify($response);
    // var_dump($response);
}); 

$app->get("/memo/data/get/:var", function($var){
    $param    = explode(".", $var);
    $token    = $param[1];
    $uid      = $param[0];
    $response = array();
    
    if(count($param) === 2) {
        $results = read_memo_type_by_uid($uid);
            foreach($results as $result) {
            $response[] = array(
                    "uid" => $result->uid,
                    "name" => $result->name, 
            );
        }
    }   

    echo jsonify($response);
    // var_dump($response);
}); 

$app->post("/memo/type/update/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    // if(count($param) === 2) {
    //     $response      = array();
    //     $name        = $_POST['name'];
    //     $dateModified  = date("Y-m-d H:i:s");

    //     uupdate_memo_type($uid, $name, $dateModified);      
    //     $success = 1;
    // }
    //     $response = array(
    //         "success" => $success
    //     );
    // echo jsonify($response);

    var_dump($uid);

});

$app->get("/certificate/view/:var", function($var) {
    $param = explode(".", $var);
    $response = array();
    if(count($param) === 1) {
        $results = read_certificate_type();
        foreach($results as $result) {
            $stat = $result->status;
            $status = "Enabled";
            if($stat === "0") {
                $status = "Disabled";
            }
            $response[] = array(
                "uid" => $result->uid,
                "name" => $result->name,
                "status" => $status
            );
        }
        echo jsonify($response);
    }
});

$app->get("/memo/view/:var", function($var) {
    $param = explode(".", $var);
    $response = array();
    if(count($param) === 1) {
        $results = read_memo();
        foreach($results as $result) {
            $stat = $result->status;
            $status = "Enabled";
            if($stat === "0") {
                $status = "Disabled";
            }
            $response[] = array(
                "uid" => $result->uid,
                "name" => $result->name,
                "status" => $status
            );
        }
        echo jsonify($response);
    }
});

$app->post("/certificate/new/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;
    if(count($param) === 1) {

        $certificateUid   = xguid();
        $employeeUid   = $_POST['employee']; 
        $departmentUid = $_POST['department'];
        $type       = $_POST['type'];
        
        $dateCreated   = date("Y-m-d H:i:s");
        $dateModified  = date("Y-m-d H:i:s");

        create_certificate($certificateUid, $employeeUid, $departmentUid, $type, $dateCreated, $dateModified);
        $verified = 1;

        $response = array(
            "error"        => 0,
            "errorMessage" => "SUCCESSFULLY CREATED!"
        );
    }

    echo jsonify($response);
});

$app->post("/memo/new/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;
    if(count($param) === 1) {

        $memoUid   = xguid();
        $employeeUid   = $_POST['employee']; 
        $departmentUid = $_POST['department'];
        $type       = $_POST['type'];
        
        $dateCreated   = date("Y-m-d H:i:s");
        $dateModified  = date("Y-m-d H:i:s");

        create_memo($memoUid, $employeeUid, $departmentUid, $type, $dateCreated, $dateModified);
        $verified = 1;

        $response = array(
            "error"        => 0,
            "errorMessage" => "SUCCESSFULLY CREATED!"
        );
    }

    echo jsonify($response);
});


$app->get("/certificate/get/data/:var", function($var){
    $param     = explode(".", $var);
    $token     = $param[0];
    $response = array();

    if(count($param) === 1) {
        $results = read_certificates();
        foreach ($results as $result) {
            $emp_uid    = $result->emp_uid;
            $emp        = read_emp_by_uid($emp_uid);
            $firstname  = $emp["firstname"];
            $middlename = $emp["middlename"];
            $lastname   = $emp["lastname"];
            $empuid     = $emp["emp_uid"];

            $uid   = $result->uid;

            $department_uid   = $result->department_uid;
            $department       = read_dep_by_uid($department_uid);
            $name       = $department["group"];

            $type   = $result->type;
            $types      = read_cert_type_by_uid($type);
            $typename       = $types["name"];


            $response[] = array(
                "uid" => $result->uid,
                "firstname"  => $firstname,
                "middlename" => $middlename,
                "lastname"   => $lastname,
                "empuid"     => $empuid,
                // "curriculum" => $examinee->curriculum_uid,
                "depname" => $name,
                "department" => $result->department_uid,
                "typename" => $typename,
                "type" => $result->type,
                "issued" => $result->date_created     
            );
        }
    }
    
    echo jsonify($response);

});

$app->get("/certificate/individual/data/:var", function($var){
    $param     = explode(".", $var);
    $userUid     = $param[0];
    $token     = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_certificates_by_user($userUid);
        foreach ($results as $result) {
            $emp_uid    = $result->emp_uid;
            $emp        = read_emp_by_uid($emp_uid);
            $firstname  = $emp["firstname"];
            $middlename = $emp["middlename"];
            $lastname   = $emp["lastname"];
            $empuid     = $emp["emp_uid"];

            $uid   = $result->uid;

            $department_uid   = $result->department_uid;
            $department       = read_dep_by_uid($department_uid);
            $name       = $department["group"];

            $type   = $result->type;
            $types      = read_cert_type_by_uid($type);
            $typename       = $types["name"];


            $response[] = array(
                "uid" => $result->uid,
                "firstname"  => $firstname,
                "middlename" => $middlename,
                "lastname"   => $lastname,
                "empuid"     => $empuid,
                // "curriculum" => $examinee->curriculum_uid,
                "depname" => $name,
                "department" => $result->department_uid,
                "typename" => $typename,
                "type" => $result->type,
                "issued" => $result->date_created     
            );
        }
    }
    
    echo jsonify($response);
    // var_dump($response);

});

$app->get("/memo/individual/data/:var", function($var){
    $param     = explode(".", $var);
    $userUid     = $param[0];
    $token     = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_memos_by_user($userUid);
        foreach ($results as $result) {
            $emp_uid    = $result->emp_uid;
            $emp        = read_emp_by_uid($emp_uid);
            $firstname  = $emp["firstname"];
            $middlename = $emp["middlename"];
            $lastname   = $emp["lastname"];
            $empuid     = $emp["emp_uid"];

            $uid = $result->uid;

            $department_uid = $result->department_uid;
            $department     = read_dep_by_uid($department_uid);
            $name           = $department["group"];

            $type     = $result->type;
            $types    = read_memo_types_by_uid($type);
            $typename = $types["name"];


            $response[] = array(
                "uid"        => $result->uid,
                "firstname"  => $firstname,
                "middlename" => $middlename,
                "lastname"   => $lastname,
                "empuid"     => $empuid,
                // "curriculum" => $examinee->curriculum_uid,
                "depname"    => $name,
                "department" => $result->department_uid,
                "typename"   => $typename,
                "type"       => $result->type,
                "issued"     => $result->date_created     
            );
        }
    }
    
    echo jsonify($response);

    // var_dump($response);

});

$app->get("/memo/get/data/:var", function($var){
    $param     = explode(".", $var);
    $token     = $param[0];
    $response = array();

    if(count($param) === 1) {
        $results = read_memo_all();
        foreach ($results as $result) {
            $emp_uid    = $result->emp_uid;
            $emp        = read_emp_by_uid($emp_uid);
            $firstname  = $emp["firstname"];
            $middlename = $emp["middlename"];
            $lastname   = $emp["lastname"];
            $empuid     = $emp["emp_uid"];

            $uid = $result->uid;

            $department_uid = $result->department_uid;
            $department     = read_dep_by_uid($department_uid);
            $name           = $department["group"];

            $type     = $result->type;
            $types    = read_memo_types_by_uid($type);
            $typename = $types["name"];


            $response[] = array(
                "uid"        => $result->uid,
                "firstname"  => $firstname,
                "middlename" => $middlename,
                "lastname"   => $lastname,
                "empuid"     => $empuid,
                // "curriculum" => $examinee->curriculum_uid,
                "depname"    => $name,
                "department" => $result->department_uid,
                "typename"   => $typename,
                "type"       => $result->type,
                "issued"     => $result->date_created     
            );
        }
    }
    
    echo jsonify($response);

    // var_dump($response);

});

$app->get("/certificate/view/edit/:var", function($var) {
    $param    = explode(".", $var);
    $uid      = $param[0];
    $token    = $param[1];
    $response = array();
    if(count($param) === 2) {
        $results = read_certificate_uid($uid);
        $success = 1;
        foreach($results as $result) {
            $response[] = array(
                "uid"        => $result->uid,
                "empuid"   => $result->emp_uid,
                "department" => $result->department_uid, 
                "type"    => $result->type
            );
        }
        echo jsonify($response);
        // var_dump($response);
    }
});


$app->get("/get/data/certificate/:var", function($var){
    $param     = explode(".", $var);
    $token     = $param[0];
    $certificates = read_certificate_all();

    foreach($certificates as $certificate){
        $response[] = array(
            "uid"    => $certificate->uid,
            "name"  => $certificate->name
        );
    }
    echo jsonify($response);
});

$app->get("/get/data/memo/:var", function($var){
    $param     = explode(".", $var);
    $token     = $param[0];
    $memos = read_memo_type();

    foreach($memos as $memo){
        $response[] = array(
            "uid"    => $memo->uid,
            "name"  => $memo->name
        );
    }
    echo jsonify($response);
});

$app->post("/certificate/update/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response      = array();
        $employee        = $_POST['employee'];
        $department     = $_POST['department'];
        $type        = $_POST['type'];
        $dateModified  = date("Y-m-d H:i:s");

        update_certificates($uid, $employee, $department, $type, $dateModified);      
        $success = 1;
    }
        $response = array(
            "success" => $success
        );
    echo jsonify($response);

    // var_dump($uid);

});

$app->post("/memo/update/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response      = array();
        $employee        = $_POST['employee'];
        $department     = $_POST['department'];
        $type        = $_POST['type'];
        $dateModified  = date("Y-m-d H:i:s");

        update_memo($uid, $employee, $department, $type, $dateModified);      
        $success = 1;
    }
        $response = array(
            "success" => $success
        );
    echo jsonify($response);

    // var_dump($uid);

});

$app->post("/certificate/type/update/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response      = array();
        $name        = $_POST['name'];
        $dateModified  = date("Y-m-d H:i:s");

        update_certificate_type($uid, $name, $dateModified);      
        $success = 1;
    }
        $response = array(
            "success" => $success
        );
    echo jsonify($response);

});

$app->post("/new/questionnaire/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;

    if(count($param) === 1) {
        $questionnaireUid       = xguid();
        $name    = $_POST['name'];
        
        $dateCreated   = date("Y-m-d H:i:s");
        $dateModified  = date("Y-m-d H:i:s");

        create_questionnaire($questionnaireUid, $name, $dateCreated, $dateModified);
        $verified = 1;
   
        $response = array(
            "error"        => 0,
            "errorMessage" => "SUCCESSFULLY CREATED!"
        );
    }
    echo jsonify($response);
});


$app->get("/get/questionnaire/:var", function($var){
    $response = array();
    $results = read_questionnaire_all();

    foreach ($results as $result) {
        $response[] = array(
            "uid" => $result->uid, 
            "name" => $result->name
        );
    }
    // var_dump($response);
    echo jsonify($response);
});


$app->post("/new/question/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;

    if(count($param) === 1) {
        $questionUid       = xguid();
        $title    = $_POST['title'];
        $question    = $_POST['question'];
        $type    = $_POST['type'];
        $typeUid    = $_POST['typeUid'];
        
        $dateCreated   = date("Y-m-d H:i:s");
        $dateModified  = date("Y-m-d H:i:s");

        create_question($questionUid, $title, $question, $type, $typeUid, $dateCreated, $dateModified);
        $verified = 1;
   
        $response = array(
            "error"        => 0,
            "errorMessage" => "SUCCESSFULLY CREATED!"
        );
    }
    echo jsonify($response);
});

$app->get("/questions/view/:var", function($var){
    $param = explode(".", $var);
    $uid = $param[0];
    $token = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_questions_by_uid($uid);
        foreach($results as $result) {
            $response[] = array(
                "uid" => $result->uid,
                "name" => $result->name,
                "question"  => $result->question,
                "type"    => $result->type,
                "typeUid"   => $result->type_uid,
            );
        }        
    }
    
    echo jsonify($response);
    // var_dump($response);

});

// $app->get("/read/evaluation/list/:var", function($var){
//     $param   = explode(".", $var);
//     $empUid     = $param[0];
//     $token   = $param[1];
//     $success = 0;

//     if(count($param) === 2) {
//         $response = array();
//         $date  = 
//         $typeUid = 
//         $evalUid

//         read_evaluation_by_employee_date($empUid, $date, $typeUid, $evalUid);      
//         $success = 1;
//     }
//         $response = array(
//             "success" => $success
//         );
//     echo jsonify($response);

// });


$app->get("/get/evaluation/history/:var", function($var){
    $param = explode(".", $var);
    $uid = $param[0];
    $token = $param[1];
    $evaluationResultsArray = array();
    $response = array();

    if(count($param) === 2) {
        $results = read_history_by_uid($uid);
        foreach($results as $key => $result) {

            $type = read_eval_type_by_uid($result->type_uid);
            $name = $type["name"];

            $questions = read_question_by_uid($result->question_uid);
            $questionname = $questions["name"];
            $question = $questions["question"];

            $employee   = read_emp_by_uid($result->emp_uid);
            $empfirstname  = $employee["firstname"];
            $empmiddlename = $employee["middlename"];
            $emplastname   = $employee["lastname"];
            $empuid     = $employee["emp_uid"];

            $evaluator  = read_emp_by_uid($result->evaluator_uid);
            $evalfirstname  = $evaluator["firstname"];
            $evalmiddlename = $evaluator["middlename"];
            $evallastname   = $evaluator["lastname"];
            $evaluid     = $evaluator["emp_uid"];

            $evaluationResults[] = array(
                "question_uid" => $result->question_uid,
                "result"  => $result->result,
                "question" => $question,
                "questionname"  => $questionname
            );
            array_push($evaluationResultsArray, $evaluationResults);

            $uid = $result['uid'];
            $emp_uid = $result['emp_uid'];
            $type_uid = $result['type_uid'];
            $evaluator_uid = $result['evaluator_uid'];
            $date_created = $result['date_created'];

            if(isset($response[$uid]))
                $index = ((count($response[$uid]) - 1) / 2) + 1;
            else if(isset($response[$emp_uid]))
                $index = ((count($response[$emp_uid]) - 1) / 2) + 1;
            else if(isset($response[$type_uid]))
                $index = ((count($response[$type_uid]) - 1) / 2) + 1;
            else if(isset($response[$evaluator_uid]))
                $index = ((count($response[$evaluator_uid]) - 1) / 2) + 1;
            else if(isset($response[$date_created]))
                $index = ((count($response[$date_created]) - 1) / 2) + 1;
            else
                $index = 1;

            $response[$emp_uid]['uid'] = $uid;
            $response[$emp_uid]['emp_uid'] = $emp_uid;
            $response[$emp_uid]['empfirstname'] = $empfirstname;
            $response[$emp_uid]['empmiddlename'] = $empmiddlename;
            $response[$emp_uid]['emplastname'] = $emplastname;
            $response[$emp_uid]['type_uid'] = $type_uid;
            $response[$emp_uid]['evaluator_uid'] = $evaluator_uid;
            $response[$emp_uid]['evalfirstname'] = $evalfirstname;
            $response[$emp_uid]['evalmiddlename'] = $evalmiddlename;
            $response[$emp_uid]['evallastname'] = $evallastname;
            $response[$emp_uid]['date_created'] = $date_created;
            $response[$emp_uid]['name'] = $name;
            $response[$emp_uid]['evaluations'] = $evaluationResults;

        }
    }
 
    echo jsonify($response);
    // var_dump($response);

});

$app->get("/employee/evaluation/history/:var", function($var){
    $param = explode(".", $var);
    $empUid = $param[0];
    $token = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_history_by_employee($empUid);
        foreach($results as $key => $result) {

            $type = read_eval_type_by_uid($result->type_uid);
            $name = $type["name"];

            $uid = $result['uid'];
            $type_uid = $result['type_uid'];
            $date_created = $result['date_created'];

            if(isset($response[$uid]))
                $index = ((count($response[$uid]) - 1) / 2) + 1;
            else if(isset($response[$type_uid]))
                $index = ((count($response[$type_uid]) - 1) / 2) + 1;
            else if(isset($response[$date_created]))
                $index = ((count($response[$date_created]) - 1) / 2) + 1;
            else
                $index = 1;

            $response[$uid]['uid'] = $uid;
            $response[$uid]['date_created'] = $date_created;
            $response[$uid]['type_uid'] = $type_uid;
            $response[$uid]['name'] = $name;

        }
    }
 
    echo jsonify($response);
    // var_dump($response);

});

$app->get("/get/evaluation/employees/:var", function($var){
    $param = explode(".", $var);
    $token = $param[0];
    $employees = read_evaluation_employee_all();

    foreach($employees as $employee){

        $emp = read_latest_evaluation_by_uid($employee->emp_uid);
        $date = $emp["date_modified"];
        $effectiveDate = $emp["next_evaluation"];

        //$effectiveDate = date('Y-m-d', strtotime("+12 months", strtotime($date)));

        $response[] = array(
            "dateModified" => $date,
            "nextEval" => $effectiveDate,
            "empUid"    => $employee->emp_uid,
            "firstname"  => $employee->firstname,
            "middlename"  => $employee->middlename,
            "lastname"  => $employee->lastname
        );
    }
         echo jsonify($response);
   //var_dump($response);
});

$app->get("/get/annoucement/:var", function($var){
    $response = array();
    $results = read_news_feed();

    foreach ($results as $result) {
        $response[] = array(
            "uid" => $result->uid,
            "user_uid" => $result->user_uid, 
            "content" => $result->content,
            "date" => $result->date_modified
        );
    }
    // var_dump($response);
    echo jsonify($response);
});


$app->get("/announcement/data/get/:var", function($var){
    $param    = explode(".", $var);
    $token    = $param[1];
    $uid      = $param[0];
    $response = array();
    
    if(count($param) === 2) {
        $results = read_news_feed_by_uid($uid);
            foreach($results as $result) {
            $response[] = array(
                    "uid" => $result->uid,
                    "content" => $result->content, 
            );
        }
    }   

    echo jsonify($response);
    // var_dump($response);
}); 

$app->post("/announcement/update/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response      = array();
        $content        = $_POST['content'];
        $dateModified  = date("Y-m-d H:i:s");

        update_news_feed($uid, $content, $dateModified);      
        $success = 1;
    }
        $response = array(
            "success" => $success
        );
    echo jsonify($response);

    // var_dump($uid);

});

$app->get("/delete/announcement/request/:uid", function($uid){
    $status = 0;

    delete_news_feed($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/delete/questionnaire/:uid", function($uid){
    $status = 0;

    delete_questionnaire($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/delete/questions/:uid", function($uid){
    $status = 0;

    delete_questions($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/delete/curriculum/:uid", function($uid){
    $status = 0;

    delete_curriculum($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/delete/topic/:uid", function($uid){
    $status = 0;

    delete_topic($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/delete/subtopic/:uid", function($uid){
    $status = 0;

    delete_subtopic($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/delete/content/:uid", function($uid){
    $status = 0;

    delete_content($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/delete/loans/type/:uid", function($uid){
    $status = 0;

    delete_loan_types($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/delete/company/loans/:uid", function($uid){
    $status = 0;

    delete_company_loans($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/delete/certificate/type/:uid", function($uid){
    $status = 0;

    delete_certificate_setting($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/delete/memo/type/:uid", function($uid){
    $status = 0;

    delete_memo_setting($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/delete/memo/:uid", function($uid){
    $status = 0;

    delete_memo($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->post("/department/head/update/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];
    $success = 0;

    if(count($param) === 2) {
        $response    = array();
        $employee    = $_POST['employee'];
        $department  = $_POST['department'];
        $position    = $_POST['position'];
        $dateModified  = date("Y-m-d H:i:s");

        update_department_head($uid, $employee, $department, $position, $dateModified);      
        $success = 1;
    }
        $response = array(
            "success" => $success
        );
    echo jsonify($response);
});

$app->post("/new/newsfeed/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;

    if(count($param) === 1) {
        $annoucementUid       = xguid();
        $user_uid    = $_POST['user_uid'];
        $content    = $_POST['content'];
        
        $dateCreated   = date("Y-m-d H:i:s");
        $dateModified  = date("Y-m-d H:i:s");

        create_news_feed($annoucementUid, $user_uid, $content, $dateCreated, $dateModified);
        $verified = 1;
   
        $response = array(
            "error"        => 0,
            "errorMessage" => "SUCCESSFULLY CREATED!"
        );
    }
    echo jsonify($response);
});

$app->get("/requests/head/data/:var", function($var){
    $param     = explode(".", $var);
    $userUid     = $param[0];
    $token     = $param[1];
    $response = array();

    if(count($param) === 2) {
        $results = read_employee_department($userUid);
        foreach ($results as $result) {  
            $department  = $result->department_uid;

            $heads = read_department_head($department);
                foreach ($heads as $head){
            
                $department_head = $head->emp_uid;

                $datas = read_department_head_data($department_head);
                    foreach ($datas as $data) {
                        
                        $email = $data->email;
                    }


                    $response[] = array(
                        "department"  => $department,
                        "departmentHead" => $department_head,
                        "email"   => $email
                    );
            }
        }
    }
    
    echo jsonify($response);
});

$app->get("/delete/department/head/:uid", function($uid){
    $status = 0;

   delete_department_head($uid, $status);

    $response = array(
        "prompt" => 1
    );

    echo jsonify($response);
});

$app->get("/heads/view/edit/:var", function($var) {
    $param    = explode(".", $var);
    $uid      = $param[0];
    $token    = $param[1];
    $response = array();
    if(count($param) === 2) {
        $results = read_department_head_uid($uid);
        $success = 1;
        foreach($results as $result) {
            $response[] = array(
                "uid"        => $result->uid,
                "empuid"   => $result->emp_uid,
                "department" => $result->department_uid, 
                "position"    => $result->position
            );
        }
        echo jsonify($response);
    }
});

$app->get("/get/department/head/:var", function($var){
    $param     = explode(".", $var);
    $token     = $param[0];
    $response = array();

    if(count($param) === 1) {
        $results = read_department_head_all();
        foreach ($results as $result) {
            $emp_uid    = $result->emp_uid;
            $emp        = read_emp_by_uid($emp_uid);
            $firstname  = $emp["firstname"];
            $middlename = $emp["middlename"];
            $lastname   = $emp["lastname"];
            $empuid     = $emp["emp_uid"];

            $department_uid   = $result->department_uid;
            $department       = read_dep_by_uid($department_uid);
            $name       = $department["group"];

            $response[] = array(
                "uid" => $result->uid,
                "firstname"  => $firstname,
                "middlename" => $middlename,
                "lastname"   => $lastname,
                "empuid"     => $result->emp_uid,
                "depname" => $name,
                "department" => $result->department_uid,
                "position" => $result->position
            );
        }
    }
    echo jsonify($response); 
});

$app->post("/new/department/head/:var", function($var){
    $param    = explode(".", $var);
    $response = array();
    $verified = 0;

    if(count($param) === 1) {
        $uid    = xguid();
        $empUid = $_POST["empUid"];
        $depUid = $_POST["depUid"];     
        $position = $_POST["position"];
        $dateCreated = date("Y-m-d H:i:s");
        $dateModified = date("Y-m-d H:i:s");       

        create_department_head($uid, $empUid, $depUid, $position, $dateCreated, $dateModified);
        $verified = 1;
   
        $response = array(
            "error"        => 0,
            "errorMessage" => "SUCCESSFULLY CREATED!"
        );   
    }

    echo jsonify($response); 
});

$app->post("/emp/submit/approve/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];
    $token   = $param[1];

    if(count($param) === 2) {
        $dateRelease      = $_POST['dateRelease'];
        $dateModified  = date("Y-m-d H:i:s");

        admin_submit_request($uid, $dateRelease, $dateModified);      
        $success = 1;
    }

    $response = array(
        "prompt" => 1
    );
    echo jsonify($response);
});

$app->post("/email/loan/approve/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];

    if(count($param) === 1) {
        $loanStatus      = $_POST['loanStatus'];
        $dateModified  = date("Y-m-d H:i:s");

        email_loan_approve($uid, $loanStatus, $dateModified);      
        $success = 1;
    }

    $response = array(
        "prompt" => 1
    );
    echo jsonify($response);
});

$app->post("/email/request/approve/:var", function($var){
    $param   = explode(".", $var);
    $uid     = $param[0];

    if(count($param) === 1) {
        $requestStatus      = $_POST['requestStatus'];
        $dateModified  = date("Y-m-d H:i:s");

        email_request_approve($uid, $requestStatus, $dateModified);      
        $success = 1;
    }

    $response = array(
        "prompt" => 1
    );
    echo jsonify($response);
});

$app->post("/educational/level/:var", function($var) {
    $param = explode(".", $var);
    $response = array();
    $verified = 0;

    if(count($param) === 1) {
        $level = $_POST['level'];
        create_educational_level($level);
        $verified = 1;
    }

    $response = array(
        "success" => $verified
    );

    echo jsonify($response);
});

$app->get("/get/education/level/:var", function($var) {
    $param = explode(".", $var);
    $response = array();
    $verified = 0;
    if(count($param)===1) {
        $results = read_educational_level();
        foreach($results as $result) {
            $response[] = array(
                "uid" => $result->education_level_uid,
                "level" => $result->level_name,
                "status" => $result->status
            );
        }
    }

    echo jsonify($response);
});

$app->post("/educational/addnew/:var", function($var) {
    $param = explode(".", $var);
    $response = array();
    $verified = 0;
    if(count($param)===2) {
        $emp_id = $_POST['empid'];
        $level = $_POST['level'];
        $school = $_POST['school'];
        $major = $_POST['major'];
        $year = $_POST['year'];
        $dstart = $_POST['dstart'];
        $dend = $_POST['dend'];
        $score = "";
        $remarks = "";

        create_educational_background($emp_id, $level, $school, $major, $year, $score, $dstart, $dend);
        $verified = 1;
    }

    $response = array(
        "success" => $verified
    );

    echo jsonify($response);
});

$app->post("/educational/update/:var", function($var) {
    $param = explode(".", $var);
    $type = $param[2];
    $response = array();
    $verified = 0;
    if(count($param)===3) {
        if($type==="edit") {
            $uid = $_POST['uid'];
            $emp_id = $_POST['empid'];
            $level = $_POST['level'];
            $school = $_POST['school'];
            $major = $_POST['major'];
            $year = $_POST['year'];
            $dstart = $_POST['dstart'];
            $dend = $_POST['dend'];
            $score = "";
            update_educational_background($uid, $school, $major, $year, $score, $dstart, $dend);
            $verified = 1;
        }else if($type==="delete") {
            $uid = $_POST['uid'];
            delete_educational_background($uid);
            $verified = 1;
        }
    }

    $response = array(
        "success" => $verified
    );

    echo jsonify($response);
});

$app->get("/educational/background/:var", function($var) {
    $param = explode(".", $var);
    $emp_uid = $param[0];
    $response = array();
    
    if(count($param)===2) {
        $results = read_educational_background_by_emp_uid($emp_uid);        
        foreach($results as $result) {
            $level = read_educational_level_by_uid($result->education_level_uid);
            $response[] = array(
                "uid" => $result->education_uid,
                "level_uid" => $result->education_level_uid,
                "emp_uid" => $result->emp_uid,
                "level" => $level->level_name,
                "school" => $result->school,
                "major" => $result->major,
                "year" => $result->year,
                "score" => $result->score,
                "start_date" => date("Y-m-d", strtotime($result->start_date)),
                "end_date" => date("Y-m-d", strtotime($result->end_date))
            );
        }
    }

    echo jsonify($response);
});

$app->post("/workexperience/addnew/:var", function($var) {
    $param = explode(".", $var);
    $emp_uid = $param[0];
    $response = array();
    $verified = 0;

    if(count($param)===2) {
        $employer = $_POST['employer'];
        $position = $_POST['position'];
        $dstart = $_POST['dstart'];
        $dend = $_POST['dend'];
        $status = $_POST['status'];

        create_work_experience($emp_uid, $employer, $position, $dstart, $dend, $status);
        $verified = 1;
    }

    $response = array(
        "success" => $verified
    );

    echo jsonify($response);
});

$app->post("/workexperience/update/:var", function($var) {
    $param = explode(".", $var);
    $uid = $param[0];
    $response = array();
    $verified = 0;
    if(count($param)===2) {
        $employer = $_POST['employer'];
        $position = $_POST['position'];
        $dstart = $_POST['dstart'];
        $dend = $_POST['dend'];
        $status = $_POST['status'];

        update_work_experience_by_uid($uid, $employer, $position, $dstart, $dend, $status);
        $verified = 1;
    }else if(count($param)===3) {
        if($param[2]==="delete") {
            delete_work_experience_by_uid($uid);
            $verified = 2;
        }
    }

    $response = array(
        "success" => $verified
    );

    echo jsonify($response);
});

$app->get("/get/employee/workexperience/:var", function($var) {
    $param = explode(".", $var);
    $emp_uid = $param[0];
    $response = array();

    if(count($param)===2) {
        $results = read_work_experience_by_emp_uid($emp_uid);        
        foreach($results as $result) {
            $currentYear = date("Y");
            $currentDate = date("Y-m-d");
            $endYear = date("Y", strtotime($result->we_to));
            $endDate = date("Y-m-d", strtotime($result->we_to));
            
            if($endYear!="1970") {
                if($endYear === $currentYear) {
                   
                    if((strtotime($currentDate))>(strtotime($endDate))) {
                        $endDate = date("m/d/Y", strtotime($result->we_to));
                    }else{
                        $endDate = "Present";
                    }
                }else {
                    $endDate = date("m/d/Y", strtotime($result->we_to));
                }
            }else {
                $endDate = "Present";
            }

            $response[] = array(
                "uid" => $result->work_experience_uid,
                "emp_uid" => $result->emp_uid,
                "employer" => $result->employer,
                "position" => $result->job_title,
                "status" => $result->comments,
                "start_date" => date("m/d/Y", strtotime($result->we_from)),
                "end_date" => $endDate
            );
        }
    }

    echo jsonify($response);
});

$app->get("/get/employee/workexperience/raw/:var", function($var) {
    $param = explode(".", $var);
    $emp_uid = $param[0];
    $token = $param[1];
    $response = array();
    if(count($param)===2) {
        $results = read_work_experience_by_emp_uid($emp_uid);        
        foreach($results as $result) {
            //$currentYear = date("Y");
            //$endYear = date("Y", strtotime($result->we_to));
            
            // if($endYear!="1970") {
            //     if($endYear === $currentYear) {
            //         $endDate = "Present";
            //     }else {
            //         $endDate = date("m/d/Y", strtotime($result->we_to));
            //     }
            // }else {
            //     $endDate = "Present";
            // }

            $response[] = array(
                "uid" => $result->work_experience_uid,
                "emp_uid" => $result->emp_uid,
                "employer" => $result->employer,
                "position" => $result->job_title,
                "status" => $result->comments,
                "start_date" => date("Y-m-d", strtotime($result->we_from)),
                "end_date" => date("Y-m-d", strtotime($result->we_to))
            );
        }
    }

    echo jsonify($response);
});

$app->get("/get/employee/license/docs/:var", function($var) {
    $param = explode(".", $var);
    $emp_uid = $param[0];
    $response = array();

    if(count($param)===2) {
        $results = read_hris_license_by_emp_uid($emp_uid);
        foreach($results as $result) {
            $license = read_license_by_uid($result->license_uid);
            $response[] = array(
                "emp_uid" => $result->emp_uid,
                "license" => $license->license_name,
                "license_no" => $result->license_no,
                "date_issued" => date("M d, Y", strtotime($result->issued_date)),
                "date_expired" => date("M d, Y", strtotime($result->expiry_date))
            );
        }
    }

    echo jsonify($response);
});

$app->get("/get/license/list/:var", function($var) {
    $param = explode(".", $var);
    $response = array();
    if(count($param)===1) {
        $results = read_license();
        foreach($results as $result) {
            $response[] = array(
                "uid" => $result->license_uid,
                "license" => $result->license_name,
                "status" => $result->status
            );
        }
    }
    echo jsonify($response);
});

$app->post("/license/addnew/:var", function($var) {
    $param = explode(".", $var);
    $emp_uid = $param[0];
    $response = array();
    $verified = 0;
    if(count($param)===2) {
        $license_uid = $_POST['license'];
        $license_no = $_POST['license_no'];
        $issued = $_POST['date_issued'];
        $expired = $_POST['date_expired'];
        
        create_license($emp_uid, $license_uid, $license_no, $issued, $expired);
        $verified = 1;
    }

    $response = array(
        "success" => $verified
    );

    echo jsonify($response);
});

$app->get("/get/document/list/:var", function($var) {
    $param = explode(".", $var);
    $response = array();
    if(count($param)===1) {
        $results = read_document();
        foreach($results as $result) {
            $response[] = array(
                "uid" => $result->uid,
                "document" => $result->document,
                "status" => $result->status
            );
        }
    }
    echo jsonify($response);
});

$app->post("/new/cash/advanced/:var", function($var) {
    $param = explode(".", $var);
    $token = $param[0];
    $response = array();
    $info = array();
    $verified = 0;
    $error = 1;
    if(count($param)===1) {
        // $verified = 1;
        // $error = 0;
        $application_no = application_number('emp_cash_advances');
        $emp_uid = $_POST['employee-name'];
        $loan_amount = $_POST['loan-amount'];
        $terms = $_POST['terms'];
        $interest = $_POST['interest-rate'];
        $monthly_due = $_POST['amount-due'];
        $verify = verify_employee_cash_advances($emp_uid);
        if($verify!=0){
            $error = 2;
        }else {
            create_employee_cash_advances($emp_uid, $application_no, $loan_amount, $terms, $interest, $monthly_due);
            $info = array(
                "Application No." => application_number('emp_cash_advances'),
                "Employee Name" => $_POST['employee-name'],
                "Loan Amount" => $_POST['loan-amount'],
                "Terms" => $_POST['terms'],
                "Interest" => $_POST['interest-rate'],
                "Monthly Due" => $_POST['amount-due']
            );
            $error = 0;
            $verified = 1;
        }
    }
    $response = array(
        "verified" => $verified,
        "error" => $error,
        "info" => $info
    );
    echo jsonify($response);
});

$app->get("/get/employee/cash/advanced/:var", function($var) {
    $param = explode(".", $var);
    $token = $param[0];
    $response = array();
    $info = array();
    $verified = 0;
    $error = 1;
    if(count($param)===1) {
        $results = get_employee_cash_advances_all();
        foreach($results as $result) {
            $emp_name = read_employee_lastname_by_uid($result->emp_uid);
            $info[] = array(
                "uid" => $result->uid,
                "emp_uid" => $result->emp_uid,
                "emp_name" => $emp_name,
                "application_no" => $result->application_no,
                "loan_amount" => $result->loan_amount,
                "terms" => $result->terms,
                "interest" => $result->interest,
                "monthly_due" => $result->monthly_due,
                "status" => $result->status
            );
        }
        $verified = 1;
        $error = 0;
    }else if(count($param)===2) {
        $uid = $param[1];
        $result = get_employee_cash_advances_by_uid($uid);
        $emp_name = read_employee_lastname_by_uid($result->emp_uid);
        $info = array(
            "uid" => $result->uid,
            "emp_uid" => $result->emp_uid,
            "emp_name" => $emp_name,
            "application_no" => $result->application_no,
            "loan_amount" => $result->loan_amount,
            "terms" => $result->terms,
            "interest" => $result->interest,
            "monthly_due" => $result->monthly_due,
            "status" => $result->status
        );
    }
    $response = array(
        "verified" => $verified,
        "error" => $error,
        "info" => $info
    );
    echo jsonify($response);
});

$app->get("/get/employee/cash/advanced/view/:var", function($var) {
    $param = explode(".", $var);
    $token = $param[0];
    $response = array();
    $info = array();
    $verified = 0;
    $error = 1;
    if(count($param)===2) {
        $uid = $param[0];
        $results = get_employee_loan_payment_by_uid($uid);
        foreach($results as $result) {
            $info[] = array(
                "uid" => $result->uid,
                "luid" => $result->emp_loan_uid,
                "reference" => $result->reference,
                "amount_paid" => $result->amount_paid,
                "balance" => $result->remaining_no_of_payment,
                "date_posted" => date("M d, Y", strtotime($result->date_created))	
            );
        }
        $verified = 1;
        $error = 0;
    }
    $response = array(
        "verified" => $verified,
        "error" => $error,
        "info" => $info
    );
    echo jsonify($response);
});

$app->post("/employee/cash/advanced/payment/:var", function($var) {
    $param = explode(".", $var);
    $token = $param[0];
    $response = array();
    $info = array();
    $verified = 0;
    $error = 1;
    if(count($param)===1) {
        $uid = $_POST['payment-uid'];
        $reference = $_POST['reference_no'];
        $payment = $_POST['loan-payment'];
        create_emp_loans_payments($uid, $reference, $payment, 0);
        $verified = 1;
        $error = 0;
    }
    $response = array(
        "verified" => $verified,
        "error" => $error
    );
    echo jsonify($response);
});

$app->post("/employee/cash/advanced/update/:var", function($var) {
    $param = explode(".", $var);
    $token = $param[0];
    $response = array();
    $info = array();
    $verified = 0;
    $error = 1;
    if(count($param)===2) {
        $action = $param[0];
        $uid = $_POST['uid'];
        $xuid = $_POST['xuid'];
        if($action==="delete") {
            delete_emp_loans_payments($xuid);
        }else if($action==="update") {

        }
        $verified = 1;
        $error = 0;
    }
    $response = array(
        "verified" => $verified,
        "error" => $error
    );
    echo jsonify($response);
});

// Start Listing Cash Advance Loan
$app->get("/cash/advance/loans/view/:var", function($var){
    //list view of all cash advance loans
    $param = explode(".",$var);
    $response = array();
    if(count($param)===2)
    {
        $getCashAdvanceLoans = read_all_cash_advance_loans();
        foreach($getCashAdvanceLoans as $result)
        {
            $info = array(
                "loanUID" => $result["uid"],
                "loanStat"=> $result["status"],
                "loanAmount" => $result["loan_details"][0]["loan_amount"],
                "term" => $result["loan_details"][1]["term"],
                "amortization" => $result["loan_details"][2]["amortization"],
                "interest" =>$result["loan_details"][3]["interest"],
                "allowable" => $result["loan_details"][4]["allowable"],
                "payment" => $result["loan_details"][5]["payment"]);
                $response[] = $info;
        }
    }
    echo jsonify($response);
});

$app->get("/cash/advance/loans/view/enabled/:var", function($var){
    //list view for all enabled cash advance
    $param = explode(".",$var);
    $response = array();
    if(count($param)===2)
    {
        $getCashAdvanceLoans = read_enabled_cash_advance_loans();
        foreach($getCashAdvanceLoans as $result)
        {
            $info = array(
                "loanUID" => $result["uid"],
                "loanStat"=> $result["status"],
                "loanAmount" => $result["loan_details"][0]["loan_amount"],
                "term" => $result["loan_details"][1]["term"],
                "amortization" => $result["loan_details"][2]["amortization"],
                "interest" =>$result["loan_details"][3]["interest"],
                "allowable" => $result["loan_details"][4]["allowable"],
                "payment" => $result["loan_details"][5]["payment"]);
                $response[] = $info;
        }
    }
    echo jsonify($response);
});


$app->get("/edit/cash/advance/loan/:var",function($var){
    //reads the data of the cash advance to be changed
    $param = explode(".",$var);
    $uid = $param[1];
    $token = $param[0];
    $response = array();

    if(count($param)===2)
    {
        $cashAdvanceLoans = read_cash_advance_loan_by_uid($uid);
        $response = array(
                        "loanUID" => $uid,
                        "loanAmount" => $cashAdvanceLoans[0]["loan_amount"],
                        "term" => $cashAdvanceLoans[1]["term"],
                        "amortization" => $cashAdvanceLoans[2]["amortization"],
                        "interest" => $cashAdvanceLoans[3]["interest"],
                        "allowable" => $cashAdvanceLoans[4]["allowable"],
                        "payment" => $cashAdvanceLoans[5]["payment"],
                        "status" => $cashAdvanceLoans[6]["status"]
                    );
    }
    
    echo jsonify($response);
});

$app->post("/save/cash/advance/changes/:var",function($var){
    //saves the cash advance changes
    $param = explode(".",$var);
    $verified = 0;
    $error = 1;
    $loanuid = $_POST["loanuid"];
    $loanAmount = $_POST["loanAmount"];
    $term = $_POST["term"];
    $amortization = $_POST["amortization"];
    $interest = $_POST["interest"];
    $allowable = $_POST["allowable"];
    $payment = $_POST["payment"];
    $status = $_POST["status"];
    $values = array($loanAmount,$term,$amortization,$interest,$allowable,$payment,$status);

    if(count($param)===2)
    {
        update_cash_advance_loan_entry_all_by_uid($loanuid,$values);
        update_cash_advance_loan_status($loanuid,$status);
        $verified = 1;
        $error = 0;
    }
    else
    {
        $error = 1;
    }
    $response = array("verified" => $verified,"error" => $error);
    echo jsonify($response);
});

$app->get("/get/cash/advance/loan/one/:var",function($var){
    //reads single cash advance loan
    $param = explode(".",$var);
    $uid = $param[0];
    $response = array();
    if(count($param)===2)
    {
        $response = read_cash_advance_loan_by_uid($uid);
    }
    echo jsonify($response);
});

$app->post("/company/loan/request/:var",function($var){
//Request Cash Advance or Company Loan//
    $param = explode(".",$var);
    $req_uid = xguid();
    $error = 1;
    $verified = 0;
    $emp_uid = $param[0];
    $application_no = generateApplyNo();
    $loan_uid = $_POST['loanUid'];
    $empName = $_POST['empName'];
    $empEmail = $_POST['empEmail'];
    $loanAmount = $_POST['loanAmount'];
    $loanPeriod = $_POST['loanPeriod'];
    $amortization = $_POST['amortization'];
    $interest = $_POST['interest'];
    $payment = $_POST['payment'];

    $loanDetails = read_cash_advance_loan_by_uid($loan_uid);
    $loanAmount = $loanDetails[0]['loan_amount'];
    if(count($param)===2)
    {
        update_cash_advance_loan_request_archived($emp_uid);
        $valid = validate_request_entry($emp_uid,$loan_uid);
        if($valid)
        {            
            create_cash_advance_loan_request($req_uid,$emp_uid,$loan_uid);
            create_cash_advance_loan_request_entry($req_uid,$emp_uid,$loan_uid,$application_no,$empEmail,$loanAmount,$loanPeriod,$amortization,$interest,$payment);
            $mail = mail_request_to_admin($empEmail,$empName,$loanAmount);
            $verified = 1;
            $error = 0;
        }
        else
        {
            $verified = 0;
            $error = 1;
        }
        
    }
    else
    {
        $error = 1;
        $verified = 0;
    }
    $response = array("verified"=>$verified,"error"=>$error);
    echo jsonify($response);
});

$app->get("/company/loan/request/view/:var",function($var){
    //reads the request or application for cash advance
    $param = explode(".",$var);
    $requid = $param[1];
    $response = array();
    if(count($param)===2)
    {
        $getResults = read_cash_advance_loan_request();
        foreach($getResults as $result)
        {
            $info = array(
                "requestUid"=>$result['requestID'],
                "empUid"=>$result['info'][0]['emp_uid'],
                "empName"=>$result['empName'],
                "loanUid"=>$result['info'][1]['loan_uid'],
                "applicationNo"=>$result['info'][2]['application_no'],
                "loanAmount"=>$result['info'][4]['loan_amount'],
                "term"=>$result['info'][5]['loan_period'],
                "amortization"=>$result['info'][6]['amortization'],
                "interest"=>$result['info'][7]['interest'],
                "allowable"=>$result['loanDetails'][4]['allowable'],
                "payment"=>$result['info'][8]['payment'],
                "dateRequested"=>$result['info'][9]['date_requested'],
                "dateGranted"=>$result['info'][10]['date_granted'],
                "status"=>$result['info'][13]['status']
            );
            $response[] = $info;
        }   
    }
    echo jsonify($response);
});

$app->get("/company/loan/request/view/selected/:var",function($var){
    //reads the cash advance request
    $param = explode(".",$var);
    $requid = $param[1];
    $response = array();
    if(count($param)===2)
    {
        $result = read_cash_advance_loan_request_requestuid($requid);
        $info = array(
            "requestUid"=>$result['requestID'],
            "empUid"=>$result['requestInfo'][0]['emp_uid'],
            "empName"=>$result['empName'],
            "loanUid"=>$result['requestInfo'][1]['loan_uid'],
            "applicationNo"=>$result['requestInfo'][2]['application_no'],
            "loanAmount"=>$result['requestInfo'][4]['loan_amount'],
            "loanPeriod"=>$result['requestInfo'][5]['loan_period'],
            "amortization"=>$result['requestInfo'][6]['amortization'],
            "interest"=>$result['requestInfo'][7]['interest'],
            "payment"=>$result['requestInfo'][8]['payment'],
            "dateRequested"=>$result['requestInfo'][9]['date_requested'],
            "dateGranted"=>$result['requestInfo'][10]['date_granted'],
            "status"=>$result['requestInfo'][13]['status']
        );
    }
    $response = array($info);
    echo jsonify($response);
});

$app->get("/company/loan/active/:var",function($var){
    //reads all active cash advance loan 
    $param = explode(".",$var);
    $response = array();
    if(count($param)===2)
    {
        $getCashAdvanceActive = read_all_cash_advance_loan_request_active();
        foreach($getCashAdvanceActive as $result)
        {
            $info = array(
                "requestUid"=>$result['requestID'],
                "empUid"=>$result['requestInfo'][0]['emp_uid'],
                "empName"=>$result['empName'],
                "loanUid"=>$result['requestInfo'][1]['loan_uid'],
                "applicationNo"=>$result['requestInfo'][2]['application_no'],
                "loanAmount"=>$result['requestInfo'][4]['loan_amount'],
                "term"=>$result['requestInfo'][5]['loan_period'],
                "amortization"=>$result['requestInfo'][6]['amortization'],
                "interest"=>$result['requestInfo'][7]['interest'],
                "payment"=>$result['requestInfo'][8]['payment'],
                "dateRequested"=>$result['requestInfo'][9]['date_requested'],
                "dateGranted"=>$result['requestInfo'][10]['date_granted'],
                "status"=>$result['requestInfo'][13]['status']
            );
            $response[]=$info;
        }
    }
    else
    {
        $response = array();
    }
    echo jsonify($response);
});

$app->get("/company/loan/pending/:var",function($var){
    //reads pending cash advance application 
    $param = explode(".",$var);
    $response = array();
    if(count($param)===2)
    {
        $getCashAdvancePending = read_all_cash_advance_loan_request_pending();
        foreach($getCashAdvancePending as $result)
        {
            $info = array(
                "requestUid"=>$result['requestID'],
                "empUid"=>$result['requestInfo'][0]['emp_uid'],
                "empName"=>$result['empName'],
                "loanUid"=>$result['requestInfo'][1]['loan_uid'],
                "applicationNo"=>$result['requestInfo'][2]['application_no'],
                "loanAmount"=>$result['requestInfo'][4]['loan_amount'],
                "term"=>$result['requestInfo'][5]['loan_period'],
                "amortization"=>$result['requestInfo'][6]['amortization'],
                "interest"=>$result['requestInfo'][7]['interest'],
                "payment"=>$result['requestInfo'][8]['payment'],
                "dateRequested"=>$result['requestInfo'][9]['date_requested'],
                "dateGranted"=>$result['requestInfo'][10]['date_granted'],
                "status"=>$result['requestInfo'][13]['status']
            );
            $response[]=$info;
        }
    }
    echo jsonify($response);
});

$app->get("/company/loan/denied/:var",function($var){
    //reads denied cash advance application 
    $param = explode(".",$var);
    $response = array();
    if(count($param)===2)
    {
        $getCashAdvancePending = read_all_cash_advance_loan_request_denied();
        foreach($getCashAdvancePending as $result)
        {
            $info = array(
                "requestUid"=>$result['requestID'],
                "empUid"=>$result['requestInfo'][0]['emp_uid'],
                "empName"=>$result['empName'],
                "loanUid"=>$result['requestInfo'][1]['loan_uid'],
                "applicationNo"=>$result['requestInfo'][2]['application_no'],
                "loanAmount"=>$result['requestInfo'][4]['loan_amount'],
                "term"=>$result['requestInfo'][5]['loan_period'],
                "amortization"=>$result['requestInfo'][6]['amortization'],
                "interest"=>$result['requestInfo'][7]['interest'],
                "payment"=>$result['requestInfo'][8]['payment'],
                "dateRequested"=>$result['requestInfo'][9]['date_requested'],
                "dateGranted"=>$result['requestInfo'][10]['date_granted'],
                "status"=>$result['requestInfo'][13]['status']
            );
            $response[]=$info;
        }
    }
    echo jsonify($response);
});

$app->get("/cash/advance/loan/current/request/:var",function($var){
    //checks the current request
    $param = explode(".",$var);
    $emp_uid = $param[0];
    $response = array();
    if(count($param)===2)
    {
        $getRequest = read_cash_advance_loan_requests($emp_uid);
        if($getRequest!=null)
        {
            $response[] = $getRequest;
        }
        else
        {
            $response = array();
        }
    }
    else
    {
        echo "Invalid Token";
    }
    // echo jsonify($getRequest);
    echo jsonify($response);
});

$app->get("/cash/advance/settings/payment/:var",function($var){
    //reads cash advance settings
    $param  = explode(".",$var);
    if(count($param)==2)
    {
        $data = read_cash_advance_loan_settings_payment();
    }
    echo json_encode($data);
});

$app->get("/active/cash/advance/loan/:var",function($var){
    //reads active cash advance
    $param = explode(".",$var);
    $emp_uid = $param[1];
    // $response = array();
    if(count($param)==2)
    {
        //get current
        $currentLoan = read_cash_advance_loan_request_active($emp_uid);
        echo jsonify($currentLoan);
    }
});

$app->post("/cash/advance/loan/approve/:var",function($var){
    //approvecash advance
    $param = explode(".",$var);
    $verified = 1;
    $error = 0;
    $response = array();
    $requestUid = $_POST['requestUid'];
    $empUid = $_POST['empUid'];
    $loanUid = $_POST['loanUid'];
    $amortization = $_POST['amortization'];
    $terms = $_POST['loanPeriod'];
    $amount = $_POST['loanAmount'];
    $remarks = $_POST['remarks'];
    if(count($param) === 2)
    {
        // create_emp_loans_new_trial($empUid,$loanUid,$amortization,$terms,$amount,$remarks);
        update_cash_advance_loan_request_active($requestUid);
        update_cash_advance_loan_entry_date_granted($requestUid);
        update_cash_advance_loan_request_entry_status($requestUid);
        update_cash_advance_loan_request_entry_active($requestUid);
        $verified = 1;
        $error = 0;
    }
    else
    {
        $verified = 0;
        $error = 1;
    }
    $response = array("verified"=>$verified,"error"=>$error);
    echo jsonify($response);
});

$app->post ("/cash/advance/loan/denied/:var",function($var){
    //denied cash advance
    $param = explode(".",$var);
    $verified = 1;
    $error = 0;
    $response = array();
    $reqUid = $_POST['requestUid'];
    $empUid = $_POST['empUid'];
    $loanUid = $_POST['loanUid'];
    if(count($param)===2)
    {
        update_cash_advance_loan_request_inactive($reqUid);
        update_cash_advance_loan_request_entry_declined($reqUid);
        update_cash_advance_loan_request_entry_inactive($reqUid);
        $verified = 1;
        $error = 0;
    }
    else
    {
        $verified = 0;
        $error = 1;
    }
    $response = array("verified"=>$verified,"error"=>$error);
    echo jsonify($response);
});

$app->get("/cash/advance/loan/setup/list/:var",function($var){
    $param = explode(".",$var);
    if(count($param)===2)
    {
        $cashAdvanceSetup = read_cash_advance_settings();
    }
    echo jsonify($cashAdvanceSetup);
});

$app->post("/save/changes/loan/settings/:var",function($var){
    $param = explode(".",$var);
    $uid = $_POST["uid"];
    $property = $_POST["property"];
    $values = $_POST["values"];
    $verified = 1;
    $error = 0;
    if(count($param)===2)
    {
        $key = read_cash_advance_settings_loan_key_by_uid($uid);
        update_cash_advance_loan_settings($uid,$key,$property,$values);
        $error = 0;
        $verified =  1;
    }
    else
    {
        $error = 1;
    }
    $response = array("verified"=>$verified,"error"=>$error);
    echo jsonify($response);
});

$app->post("/cash/advance/new/loan/offer/:var",function($var){
    $param = explode(".",$var);
    $loanAmount = $_POST["loanAmount"];
    $loanTerm = $_POST["loanTerm"];
    $amortization = $_POST["amortization"];
    $interest = $_POST["interest"];
    $allowed = $_POST["allowed"];
    $payment = $_POST["payment"];
    $status = 1;
    $response = array();
    $values = array($loanAmount,$loanTerm,$amortization,$interest,$allowed,$payment,$status);
    if(count($param === 2))
    {
        $create = create_cash_advance_loan_entry($values);
        $success = 1;
        $error = 0;
    }
    else
    {
        $success = 0;
        $error = 1;
    }
    $response = array("success"=>$success, "error"=>$error);
    echo jsonify($response);
});

$app->get("/generate/reference/number/:var",function($var){
    $param = explode(".",$var);
    $refNumber = generateRefNo();
    echo json_encode($refNumber);
});

$app->post("/loan/payment/:var",function($var){
    $param = explode(".",$var);
    $refNo = $_POST['refNo'];
    $empName = $_POST['empName'];
    $loanType = $_POST['loanType'];
    $amortization = $_POST['amortization'];

    if($param === 2)
    {
        create_emp_loans_payments();
    }
});

$app->get("/cash/advance/payments/:var",function($var){
    $param = explode(".",$var);
    $uid = $param[1];
    $token = $param[0];
    $response = array();
    $totalAmountPaid = 0;
    if(count($param) === 2)
    {
        $paymentData = cash_advance_soa($uid);
        foreach($paymentData["payments"] as $payment)
        {
            $totalAmountPaid += floatval($payment["amountPaid"]); 
        } 
        $response = array(floatval($totalAmountPaid),floatval($paymentData["loanGranted"]));
    }
    else
    {
        $response = array();
    }
    echo jsonify($response);
});

$app->get("/soa/data/:var",function($var){
    $param = explode(".",$var);
    $token = $param[0];
    $uid = $param[1];
    $applyNo = $param[2];
    $soaData =  cash_advance_soa($uid,$applyNo); // cash_advance_soa($uid);
    $ctr = count($soaData["payments"]);
    $response = array();
    if(count($param) === 3)
    {
        // $uid = "F92524FC-3A49-C952-DE63-E04A0148DAD1";
        for($i=0;$i<$ctr;$i++)
        {
            $response[] = array(
                        "emploanUid"=>$soaData["payments"][$i]["emploanUid"],
                        "applicationNo"=>$soaData["payments"][$i]["applicationNo"],
                        "emp_uid"=>$soaData["payments"][$i]["emp_uid"],
                        "referNo"=>$soaData["payments"][$i]["referNo"],
                        "datePosted"=>$soaData["payments"][$i]["datePosted"],
                        "amountPaid"=>$soaData["payments"][$i]["amountPaid"],
                        "balance"=>$soaData["loanGranted"]-=$soaData["amortization"]
                    );
        }
    }
    echo jsonify($response);
});

$app->get("/loan/granted/:var",function($var){
    $param = explode(".",$var);
    $uid = $param[1];
    $token = $param[0];
    $applyno = $param[2];
    if(count($param) === 3)
    {
        $response = read_emp_loans_loan_granted_by_uid_application_no($uid,$applyno);
    }
    echo $response;
});

$app->post("/update/cash/advance/paid/:var",function($var){
    $param = explode(".",$var);
    $uid = $param[1];
    $token = $param[0];
    $reqUid = $_POST['requestID'];
    if(count($param)=== 2)
    {
        update_cash_advance_loan_request_entry_status_to_paid($reqUid);
    }
});

$app->post("/create/loan/payments", function(){
    $emp_uid = "25E50611-8B11-24E9-F10D-B2CB13F7CE66";
    $noOfPayment = 3;  
    create_emp_loans_payments($emp_uid,$noOfPayment);
    echo "Payment created";
});
//End Cash Advance Loan
$app->run();
?>