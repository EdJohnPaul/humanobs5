<?php

function create_cash_advance_loan_setup()
{
    $keys = array("loan_amount","term","amortization","interest","allowable","payment","status");
    $values = array(array("3000","5000","10000"),array("1.5","2.5","6"),array("1000"),array("0"),array("1","2","3"),array("15","30","15-30"),1);
    // $uid = xguid();
    $loanType = "Cash Advance Loan";
    for($i=0;$i<count($keys);$i++)
    {
        $sql = ORM::forTable("cash_advance_loan_settings")->create();
        $sql->uid = xguid();
        $sql->loan_key = $keys[$i];
        $sql->loan_value = json_encode($values[$i]);
        $sql->date_created = date("Y-m-d H:i:s");
        $sql->date_modified = date("Y-m-d H:i:s");
        $sql->save();
    }
    // create_loan_settings($uid,$loanType);
    echo "Cash Advance Loan created";
}

function create_loan_settings($uid,$loanType)
{
    $sql = ORM::forTable("all_loan_settings")->create();
    $sql->uid = $uid;
    $sql->loan_type = $loanType;
    $sql->date_created = date("Y-m-d H:i:s");
    $sql->date_modified = date("Y-m-d H:i:s");
    $sql->save();
}

function create_cash_advance_loan_entry($value)
{
    $keys = read_loan_keys();
    $uid = xguid();
    for($i=0;$i<count($keys);$i++)
    {
        $sql = ORM::forTable("cash_advance_loan_entry")->create();
        $sql->uid = $uid;
        $sql->loan_key = $keys[$i];
        $sql->loan_key_value = $value[$i];
        $sql->save();
    }
    create_cash_advance_loan($uid,$value[0]);
}

function create_cash_advance_loan($uid,$value)
{
    $sql = ORM::forTable("cash_advance_loans")->create();
    $sql->loan_uid = $uid;
    $sql->status = 1;
    $sql->date_created = date("Y-m-d H:i:s");
    $sql->date_modified = date("Y-m-d H:i:s");
    $sql->save();
}

function read_all_cash_advance_loans()
{
    $results = read_record_cash_advance_loans();
    $info = null;
    $data = array();
    foreach($results as $result)
    {
        $uid = get_cash_advance_entry_uid($result->loan_uid);
        $info = read_cash_advance_loan_entry($uid);
        $data[] = array("uid"=>$uid,"status"=>$result->status,"loan_details" => $info);
    }
    return $data;
    // echo jsonify($data);
}

function read_enabled_cash_advance_loans()
{
    $results = read_record_cash_advance_loans_enabled();
    $info = null;
    $data = array();
    foreach($results as $result)
    {
        $uid = get_cash_advance_entry_uid($result->loan_uid);
        $info = read_cash_advance_loan_entry($uid);
        $data[] = array("uid"=>$uid,"status"=>$result->status,"loan_details" => $info);
    }
    return $data;
    // echo jsonify($data);
}

function read_loan_name($uid)
{
    $sql = ORM::forTable("cash_advance_loans")->where("loan_uid",$uid)->findOne();
    return $sql->loan;
}

function get_cash_advance_entry_uid($uid)
{
    $data = ORM::forTable("cash_advance_loan_entry")->where("uid",$uid)->findOne();
    return $data->uid;
}

function read_record_cash_advance_loans()
{
    $sql = ORM::forTable("cash_advance_loans")->findMany();
    return $sql;
}

function read_record_cash_advance_loans_enabled()
{
    $sql = ORM::forTable("cash_advance_loans")->where("status",1)->findMany();
    return $sql;
}

function read_cash_advance_loan_entry($uid)
{
    $data = array();
    $keys = read_loan_keys();
    foreach($keys as $key)
    {
        $result = read_cash_advance_loan_by_keys($uid,$key);
        if($result)
        {
            $data[] = array($key => $result->loan_key_value);
        }
        
    }
    return $data;
}

function read_cash_advance_loan_by_keys($uid,$key)
{
    $sql = ORM::for_table("cash_advance_loan_entry")->where("uid",$uid)->where("loan_key",$key)->findOne();
    return $sql;
}

function get_cash_advance_loan_uid()
{
    $sql = ORM :: forTable("all_loan_settings")->where("loan_type","Cash Advance Loan")->findOne();
    return $sql->uid;
}

function read_loan_keys()
{

    $uid = get_cash_advance_loan_uid();
    $sql = ORM :: forTable("cash_advance_loan_settings")->findMany();
    foreach($sql as $result)
    {
        $data[] = $result->loan_key;
    }
    return $data;
}

function read_cash_advance_loan_by_uid($uid)
{
    $keys = read_loan_keys();
    foreach($keys as $key)
    {
        $sql = read_cash_advance_loan_by_keys($uid,$key);
        $data[] = array($key=>$sql->loan_key_value);
    }
    // echo jsonify($data);
    return $data;
}

//=================== Request Compnay Loan ====================//

function  create_cash_advance_loan_request_entry($req_uid,$emp_uid,$loan_uid,$application_no,$empEmail,$loanAmount,$loanPeriod,$amortization,$interest,$payment)
{
    $request_keys = array("emp_uid","loan_uid","application_no","emp_email","loan_amount","loan_period","amortization","interest","payment","date_requested","date_granted","date_created","date_modified","status","active");
    $values = array($emp_uid,$loan_uid,$application_no,$empEmail,$loanAmount,$loanPeriod,$amortization,$interest,$payment,date('Y/m/d h:i:s')," ",date('Y/m/d h:i:s'),date('Y/m/d h:i:s'),"Pending",0);

    for($i=0; $i<count($request_keys); $i++)
    {
        $sql = ORM::forTable("cash_advance_loan_request_entry")->create();
        $sql->request_uid = $req_uid;
        $sql->request_key = $request_keys[$i];
        $sql->request_value = $values[$i];
        $sql->save();
    }

}
function create_emp_loans_new_trial($empUid,$loanUid,$amortization,$terms,$amount,$remarks)
{
    $dateGranted = date("Y-m-d");
    $firstMonthly =  first_monthly_amortization_date($dateGranted);
    $period = $terms*30;
    $sql = ORM::forTable("emp_loans_new")->create();//emp_loans_new_trial - the table used for testing the insertion of data
    $sql->uid = xguid();
    $sql->emp_uid = $empUid;
    $sql->loan_uid = $loanUid;
    $sql->application_no = date("Ymd").eid();
    $sql->amortization = $amortization;
    $sql->terms = $terms;
    $sql->loan_granted = $amount;
    $sql->date_granted = date('Y-m-d h:i:s');
    $sql->first_monthly_amortization = $firstMonthly;
    $sql->amortization_period = date("Y-m-t",strtotime($firstMonthly." + ".$period."days"));
    $sql->remarks = $remarks;
    $sql->date_created = date('Y-m-d h:i:s');
    $sql->date_modified = date('Y-m-d h:i:s');
    $sql->status = 1;
    $sql->save();
}


function first_monthly_amortization_date($dateGranted)
{
    $dateGrant = date("Y-m-d",strtotime($dateGranted));
    $dueDate = date("Y-m",strtotime($dateGranted));
    $firstAmort = date_create();
    $datelastMonth = date("Y-m",strtotime('last month'));
    $dateNextMonth = date('Y-m',strtotime('next month'));
    $startDate1 = date("Y-m-1");
    $endDate1 = date('Y-m-15');
    $startDate2 = date('Y-m-16');
    $endDate2 = date('Y-m-31');
    
    if($dateGrant >= $startDate1 && $dateGrant <= $endDate1)
    {
        $firstAmort = date("Y-m-t",strtotime($dueDate));
        return $firstAmort;
    }
    else if($dateGrant >= $startDate2 && $dateGrant <= $endDate2)
    {
        $firstAmort = date("Y-m-15",strtotime($dateNextMonth));
        return $firstAmort;
    }
}

function get_cash_advance_amortization_amount($emp_uid)
{
    //could put a condition that will check if the employee have outsatanding balnce
    $sql = ORM::forTable("emp_loans_new_trial")->where("emp_uid",$emp_uid)->findOne();
    $amortization_amount = $sql->amortization;
    return $amortization_amount;
}

function create_cash_advance_loan_request($req_uid,$emp_uid,$loan_uid)
{
    $sql = ORM::forTable("cash_advance_loan_request")->create();
    $sql->request_uid = $req_uid;
    $sql->emp_uid = $emp_uid;
    $sql->loan_uid = $loan_uid;
    $sql->date_created = date('Y/m/d h:i:s');
    $sql->date_modified = date('Y/m/d h:i:s');
    $sql->active = 0;
    $sql->request_status = 1;
    $sql->save();
}
function update_cash_advance_loan_request_active($req_uid)
{
    $sql = ORM::forTable("cash_advance_loan_request")->where("request_uid",$req_uid)->findOne();
    $sql->active = 1;
    $sql->request_status = 0;
    $sql->save();
}
function update_cash_advance_loan_request_inactive($req_uid)
{
    $sql = ORM::forTable("cash_advance_loan_request")->where("request_uid",$req_uid)->findOne();
    $sql->active = 0;
    $sql->request_status = 0;
    $sql->denied = 1;
    $sql->save();
}
function update_cash_advance_loan_request_entry_declined($req_uid)
{
    $sql = ORM::forTable("cash_advance_loan_request_entry")->where("request_uid",$req_uid)->where("request_key","status")->findOne();
    $sql->request_value = 'Denied';
    $sql->save();
}
function update_cash_advance_loan_request_entry_status($req_uid)
{
    $sql = ORM::forTable("cash_advance_loan_request_entry")->where("request_uid",$req_uid)->where("request_key","status")->findOne();
    $sql->request_value = "Active";
    $sql->save();
}
function update_cash_advance_loan_request_entry_active($req_uid)
{
    $sql = ORM::forTable("cash_advance_loan_request_entry")->where("request_uid",$req_uid)->where("request_key","active")->findOne();
    $sql->request_value = 1;
    $sql->save();
}
function update_cash_advance_loan_request_entry_inactive($req_uid)
{
    $sql = ORM::forTable("cash_advance_loan_request_entry")->where("request_uid",$req_uid)->where("request_key","active")->findOne();
    $sql->request_value = 0;
    $sql->save();
}
function update_cash_advance_loan_entry_date_granted($req_uid)
{
    $sql = ORM::forTable("cash_advance_loan_request_entry")->where("request_uid",$req_uid)->where("request_key","date_granted")->findOne();
    $sql->request_value = date('Y/m/d h:i:s');
    $sql->save();
}
function update_cash_advance_loan_request_entry_status_to_paid($req_uid)
{
    $sql = ORM::forTable("cash_advance_loan_request_entry")->where("request_uid",$req_uid)->where("request_key","status")->findOne();
    $sql->request_value = "Paid";
    $sql->save();
}
function update_cash_advance_loan_request_archived($emp_uid)
{
    $sql = ORM::forTable("cash_advance_loan_request")->where("emp_uid",$emp_uid)->where("active",0)->where("request_status",0)->findMany();
    foreach($sql as $result)
    {
        $result->archived = 1;
        $result->save();
    }

}

function update_cash_advance_loan_entry_all_by_uid($loanuid,$values)
{
    $keys = read_loan_keys();
    for($i=0; $i<count($keys); $i++)
    {
        $sql = read_cash_advance_loan_by_keys($loanuid,$keys[$i]);
        $sql->loan_key_value = $values[$i];
        $sql->save();
    }
}

function update_cash_advance_loan_status($uid,$status)
{
    $sql = ORM::forTable("cash_advance_loans")->where("loan_uid",$uid)->findOne();
    $sql->status = $status;
    $sql->date_modified = date('Y/m/d h:i:s');
    $sql->save();
}

function validate_request_entry($emp_uid)
{
    $valid = true;
    $sql = ORM::forTable("cash_advance_loan_request")->where("emp_uid",$emp_uid)->where("request_status",1)->count();
    if($sql>=1)
    {
        $valid = false;
    }
    return $valid;
}

function read_cash_advance_loan_request_active($emp_uid)
{
    $data = array();
    $sql = ORM::forTable('cash_advance_loan_request')->where("emp_uid",$emp_uid)->where("active",1)->findOne();
    if($sql)
    {
        $activeLoan = read_cash_advance_loan_request_by_keys($sql->request_uid);
        // $count = count($activeLoan);
        // echo $count; 
        //$loanTitle = read_loan_name($activeLoan[1]['loan_uid']);
        //$loanDetails = read_cash_advance_loan_entry($activeLoan[1]['loan_uid']);
        // echo jsonify($loanDetails).'<br><br>'; 
        // echo jsonify($activeLoan).'<br><br>';
        // echo $loanTitle;
        $data = array(
            "empUid"=>$activeLoan[0]['emp_uid'],
            "loanAmount"=>$activeLoan[3]['loan_amount'],
            "term"=>$activeLoan[4]['loan_period'],
            "amortization"=>$activeLoan[5]['amortization'],
            "interest"=>$activeLoan[6]['interest'],
            "payment"=>$activeLoan[7]['payment'],
            "dateRequested"=>$activeLoan[8]['date_requested'],
            "dateGranted"=>$activeLoan[9]['date_granted'],
            "status"=>$activeLoan[12]['status'],
        );
        
    }
    else
    {
        $data = array();
    }
    return $data;
    // echo jsonify($activeLoan);
}

function read_all_cash_advance_loan_request_active()
{
    $data = array();
    $sql = ORM::forTable("cash_advance_loan_request")->where('active',1)->order_by_asc('date_created')->findMany();
    if($sql==null)
    {
        $data = array();
    }
    else
    {
        foreach($sql as $result)
        {
            $data[] = array(
                "requestID"=>$result->request_uid,
                "empName"=>read_emp_id_cash_advance_loan($result->request_uid),
                "requestInfo"=>read_cash_advance_loan_request_by_keys($result->request_uid)
            );
        }
    }
    return $data;
    // echo jsonify($data);
}

function read_all_cash_advance_loan_request_pending()
{
    $data = array();
    $sql = ORM::forTable("cash_advance_loan_request")->where('request_status',1)->order_by_desc('date_created')->findMany();
    if($sql == null)
    {
        $data = array();
    }
    else
    {
        foreach($sql as $result)
        {
            $data[] = array(
                "requestID"=>$result->request_uid,
                "empName"=>read_emp_id_cash_advance_loan($result->request_uid),
                "requestInfo"=>read_cash_advance_loan_request_by_keys($result->request_uid)
            );
        }
    }
    return $data;
    // echo jsonify($data);
}

function read_all_cash_advance_loan_request_denied()
{
    $data = array();
    $sql = ORM::forTable("cash_advance_loan_request")->where('denied',1)->order_by_desc('date_created')->findMany();
    if($sql == null)
    {
        $data = array();
    }
    else
    {
        foreach($sql as $result)
        {
            $data[] = array(
                "requestID"=>$result->request_uid,
                "empName"=>read_emp_id_cash_advance_loan($result->request_uid),
                "requestInfo"=>read_cash_advance_loan_request_by_keys($result->request_uid)
            );
        }
    }
    return $data;
    // echo jsonify($data);
}

function read_cash_advance_loan_request()
{
    $data = array();   
    $sql = ORM::forTable("cash_advance_loan_request")->order_by_desc('date_created')->findMany();
    if($sql == null)
    {
        $data = array();
    }
    else
    {
        foreach($sql as $result)
        {
            $data[] = array("requestID"=>$result->request_uid,"empName"=>read_emp_id_cash_advance_loan($result->request_uid),"loanDetails"=>read_loan_details($result->request_uid),
            "info"=>read_cash_advance_loan_request_by_keys($result->request_uid));
        }
    }
    return $data;
    // echo jsonify($data);
}

function read_cash_advance_loan_requests($emp_uid)
{
    $reqInfo = array();
    $sql = ORM::forTable("cash_advance_loan_request")->where("emp_uid",$emp_uid)->findMany();
    if($sql==null)
    {
        $reqInfo = array();
    }
    else
    {
        foreach($sql as $result)
        {
            $requestInfo = read_cash_advance_loan_request_by_keys($result->request_uid);
            $reqInfo = array(
                    "requestuid"=>$result->request_uid,
                    "empuid"=>$requestInfo[0]['emp_uid'],
                    "empName"=>$requestInfo[1]['loan_uid'],
                    "applicationNo"=>$requestInfo[2]['application_no'],
                    "empEmail"=>$requestInfo[3]['emp_email'],
                    "loanAmount"=>$requestInfo[4]['loan_amount'],
                    "loanPeriod"=>$requestInfo[5]['loan_period'],
                    "amortization"=>$requestInfo[6]['amortization'],
                    "interest"=>$requestInfo[7]['interest'],
                    "payment"=>$requestInfo[8]['payment'],
                    "dateRequested"=>$requestInfo[9]['date_requested'],
                    "dateGranted"=>$requestInfo[10]['date_granted'],
                    "status"=>$requestInfo[13]['status'],
                    "active"=>$requestInfo[14]['active']
                );
        }

    }
        // echo jsonify($reqInfo);
     return $reqInfo;
}

function read_cash_advance_loan_request_requestuid($requid)
{
    $data = array();
    $sql = ORM::forTable("cash_advance_loan_request")->where("request_uid",$requid)->findOne();
    $requestInfo = read_cash_advance_loan_request_by_keys($sql->request_uid);
    $data = array("requestID"=>$sql->request_uid,"empName"=>read_employee_name_by_uid($sql->emp_uid),"requestInfo"=>$requestInfo);
    // echo jsonify($data);
    return $data;
}

function read_cash_advance_loan_request_by_keys($uid)
{
    $data = array();
    $request_keys = array("emp_uid","loan_uid","application_no","emp_email","loan_amount","loan_period","amortization","interest","payment","date_requested","date_granted","date_created","date_modified","status","active");
    foreach($request_keys as $key)
    {
        $sql = read_cash_advance_loan_entries($uid,$key);
        $data[] = array($key => $sql->request_value);
    }
    // echo jsonify($data);
    return $data;
}
function read_cash_advance_loan_entries($uid,$key)
{
    $sql = ORM::forTable("cash_advance_loan_request_entry")->where("request_uid",$uid)->where("request_key",$key)->findOne();
    return $sql;
}

function read_emp_id_cash_advance_loan($uid)
{
    $sql = ORM::forTable("cash_advance_loan_request_entry")->where("request_uid",$uid)->where("request_key","emp_uid")->findOne();
    return read_employee_name_by_uid($sql->request_value);
}

function read_loan_details($uid)
{
    $sql = ORM::forTable("cash_advance_loan_request_entry")->where("request_uid",$uid)->where("request_key","loan_uid")->findOne();
    return read_cash_advance_loan_entry($sql->request_value);
}

function read_cash_advance_loan_settings_payment()
{
    $data = array();
    $uid = read_all_loan_settings_cash_advance();
    $sql = ORM::forTable("cash_advance_loan_settings")->where("loan_key","payment")->findOne();
    $data = json_decode($sql->loan_value);
    return $data;
}

function read_all_loan_settings_cash_advance()
{
    $sql = ORM::forTable("all_loan_settings")->where("loan_type","Cash Advance Loan")->findOne();
    return $sql->uid;
}

function read_cash_advance_settings()
{
    $ctr = 0;
    $sql = ORM::forTable("cash_advance_loan_settings")->findMany();
    foreach($sql as $result)
    {
        $ctr++;
        $data[] = array(
                    "num" =>  $ctr,
                    "uid" => $result->uid,
                    "property"=>$result->loan_key_display_name,
                    "values" => json_decode($result->loan_value)
                );
    }
    // echo jsonify($data);   
    return $data;
}

function  update_cash_advance_loan_settings($uid,$key,$property,$values)
{
    $sql = ORM::forTable("cash_advance_loan_settings")->where("uid",$uid)->where("loan_key",$key)->findOne();
    $sql->loan_key_display_name = $property;
    $sql->loan_value = json_encode($values);
    $sql->save();
}

function read_cash_advance_settings_loan_key_by_uid($uid)
{
    $sql = ORM::forTable("cash_advance_loan_settings")->where("uid",$uid)->findOne();
    return $sql->loan_key;
}

function read_cash_advance_loan_settings_properties()
{
    $sql = ORM::forTable("cash_advance_loan_settings")->findMany();
    foreach($sql as $result)
    {
        $data[] = array($result->loan_key => json_decode($result->loan_value));
    }
    // echo json_encode($data);
    return $data;
}

function access_setting_property_value($values,$propertyName)
{
    foreach($values as $value)
    {
        $data[] = array($propertyName=>$value);
    }
    return $data;
}

function cash_advance_soa($uid,$applyNo)
{
    // echo intval($applyNo);
    $sql = ORM::forTable("emp_loans_payments")->raw_query('SELECT t1.emp_loan_uid, t2.emp_uid, t2.application_no, t1.reference, t1.date_created, t1.amount_paid, t1.status FROM `emp_loans_payments` as t1 JOIN `emp_loans_new` as t2 ON t1.emp_loan_uid = t2.uid WHERE t2.emp_uid = :emp_uid AND t2.application_no = :applyNo', array('emp_uid'=>$uid,'applyNo'=>$applyNo))->findMany();
    $data = array();
    foreach($sql as $result)
    {
        $data[] =  array(
            "emploanUid"=>$result->emp_loan_uid,
            "applicationNo"=>$result->application_no,
            "emp_uid"=>$result->emp_uid,
            "referNo"=>$result->reference,
            "datePosted"=>$result->date_created,
            "amountPaid"=>$result->amount_paid
        );
    }
    $loanGranted = read_emp_loans_loan_granted_by_uid_application_no($uid,$applyNo);
    $amortization = read_emp_loans_amortization($uid);
    $info = array("payments"=>$data,"loanGranted"=>$loanGranted,"amortization"=>$amortization);
    return $info;
    // echo jsonify($balance);
}

function read_emp_loans_loan_granted($uid)
{
    $sql = ORM::forTable("emp_loans_new")->where("emp_uid",$uid)->where("status", 1)->findOne();
    if($sql!=null)
    {
        return $sql->loan_granted;
    }
    else
    {
        return null;
    }
}

function read_emp_loans_loan_granted_by_uid_application_no($uid,$applyno)
{
    $sql = ORM::forTable("emp_loans_new")->where("emp_uid",$uid)->where("status", 1)->where('application_no',$applyno)->findOne();
    if($sql!=null)
    {
        return $sql->loan_granted;
    }
    else
    {
        return null;
    }
}

function read_emp_loans_amortization($uid)
{
    $sql = ORM::forTable("emp_loans_new")->where("emp_uid",$uid)->where("status",1)->findOne();
    if($sql!=null)
    {
        return $sql->amortization;
    }
    else
    {
        return null;
    }
}

//----------Cash Advance Payments------------//
function create_emp_loans_payments($emp_uid,$noOfPayment)
{
    $data = read_emp_loans_new_uid_by_emp_uid($emp_uid);
    $sql = ORM::forTable('emp_loans_payments')->create();
    $sql->uid = xguid();
    $sql->emp_loan_uid = $data[0]; 
    $sql->reference = generateRefNum();
    $sql->amount_paid = $data[1];
    $sql->remaining_no_of_payment = $noOfPayment;
    $sql->date_created = date('Y-m-d h:i:s');
    $sql->date_modified = date('Y-m-d h:i:s');
    $sql->status = 1;
    $sql->save();

    // echo jsonify($data);
    // echo $data[0];
}

function read_emp_loans_new_uid_by_emp_uid($emp_uid)
{
    $sql = ORM::forTable('emp_loans_new')->where('emp_uid',$emp_uid)->where('status',1)->findOne();
    $data = array($sql->uid,$sql->amortization);
    return $data;
    // echo jsonify($data);
}
