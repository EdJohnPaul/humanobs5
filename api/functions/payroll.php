<?php
date_default_timezone_set("Asia/Manila");

function db_connect() {
	$host = 'localhost'; 
	//$host = '54.202.119.45';
	$user = 'root';
	$pass = 'root';
	//$pass = 'root@$avasia';
	$db = 'aisi_humano';
	$con = mysqli_connect($host,$user,$pass,$db);

	// Check connection
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	else {
		return $con;
	}
}

/* Payroll */
/* CRUD - Create, Read, Update, Delete */

function create_payroll_registry($uid, $costcenterid, $year, $month, $period, $payroll_type, $employee_type, $description, $transaction_date) {
	# id uid year month period payroll_type employee_type from_date to_date description transaction_date action date_created date_modified status
    $query = ORM::forTable("payroll_registry")->create();
	$query->uid = $uid;
	$query->cost_center_uid = $costcenterid;
	$query->year = $year;
	$query->month = $month;
	$query->period = $period;
	$query->payroll_type = $payroll_type;
	$query->employee_type = $employee_type;
	$query->from_date = "";
	$query->to_date = "";
	$query->description = $description;
	$query->transaction_date = $transaction_date;
	$query->action = "PENDING";        
	$query->date_created = date("Y-m-d H:i:s");
	$query->date_modified = date("Y-m-d H:i:s");
	$query->status = 1;
    $query->save();
}

function read_payroll_registry() {
	$data = ORM::forTable("payroll_registry")->where("status", 1)->orderByDesc("id")->findMany();
	return $data;
}

function read_payroll_registry_distinct() {
	$sql = "SELECT DISTINCT month, year FROM payroll_registry WHERE year = :year AND action = :action";
	$data = ORM::forTable("payroll_registry")->rawQuery($sql, array("year" => date("Y"), "action" => "POSTED"))->findMany();
	return $data;
}

function read_payroll_registry_monthly() {
	$data = ORM::forTable("payroll_registry")
		->table_alias("t1")
		->distinct()->select("t1.month")
		->select("t1.year")
		->select("t1.payroll_type")
		->select("t1.employee_type")
		->join("timekeeping_log_file", array("t1.uid", "=", "t2.uid"), "t2")
		->select("t2.cost_center_uid")
		->where("year", date("Y"))
		->where("t1.status", 1)
		->orderByDesc("t1.id")
		->findMany();
	return $data;
}

function read_payroll_registry_monthly_by_year_month($year, $month) {
	$data = ORM::forTable("payroll_registry")
		->table_alias("t1")
		->select("t1.uid")
		->select("t1.year")
		->select("t1.payroll_type")
		->select("t1.employee_type")
		->select("t2.cost_center_uid")
		->join("timekeeping_log_file", array("t1.uid", "=", "t2.uid"), "t2")
		->where("t1.year", $year)
		->where("t1.month", $month)
		->where("t1.status", 1)
		->orderByAsc("t1.id")
		->findMany();
	return $data;
}

function read_payroll_registry_monthly_by_year_month_type_costcenter($year, $month, $type, $cost_uid) {
	$data = ORM::forTable("payroll_registry")
		->table_alias("t1")
		->select("t1.uid")
		->select("t1.year")
		->select("t1.month")
		->select("t1.period")
		->select("t1.payroll_type")
		->select("t1.employee_type")
		->select("t2.cost_center_uid")
		->join("timekeeping_log_file", array("t1.uid", "=", "t2.uid"), "t2")
		->where("t1.year", $year)
		->where("t1.month", $month)
		->where("t1.employee_type", $type)
		->where("t2.cost_center_uid", $cost_uid)
		->where("t1.status", 1)
		->orderByAsc("t1.id")
		->findMany();
	return $data;
}

function read_payroll_registry_monthly_by_year_type_costcenter($year, $type, $cost_uid) {
	$data = ORM::forTable("payroll_registry")
		->table_alias("t1")
		->select("t1.uid")
		->select("t1.year")
		->select("t1.month")
		->select("t1.period")
		->select("t1.payroll_type")
		->select("t1.employee_type")
		->select("t2.cost_center_uid")
		->join("timekeeping_log_file", array("t1.uid", "=", "t2.uid"), "t2")
		->where("t1.year", $year)
		->where("t1.employee_type", $type)
		->where("t2.cost_center_uid", $cost_uid)
		->where("t1.status", 1)
		->orderByAsc("t1.id")
		->findMany();
	return $data;
}

function get_payroll_computation_by_uid($uid) {
	$sql = "SELECT SUM(sss) as sss_total, SUM(philhealth) as total_phic, SUM(pagibig) as total_hdmf, SUM(tax) as total_tax, SUM(net_amount) as total_netpay FROM payroll_summary WHERE uid = :uid and status = 1";	
	$query = ORM::forTable("payroll_summary")->rawQuery($sql, array("uid" => $uid))->findOne();
    return $query;
}

function read_payroll_registry_monthly_by_year_month_costcenter_uid($year, $month, $uid) {
	$data = ORM::forTable("payroll_registry")
		->table_alias("t1")
		//->distinct()->select("t1.month")
		->select("t1.year")
		->select("t1.payroll_type")
		->select("t1.employee_type")
		->select("t2.cost_center_uid")
		->join("timekeeping_log_file", array("t1.uid", "=", "t2.uid"), "t2")
		->where("t1.year", $year)
		->where("t1.month", $month)
		->where("t2.cost_center_uid", $uid)
		->where("t1.status", 1)
		->orderByAsc("t1.id")
		->findMany();
	return $data;
}

function read_payroll_registry_by_year($year) {
	$data = ORM::forTable("payroll_registry")->where("year", $year)->where("status", 1)->orderByDesc("id")->findMany();
	return $data;
}

function read_payroll_registry_by_uid($uid) {
	$data = ORM::forTable("payroll_registry")->where("uid", $uid)->where("status", 1)->findOne();
	return $data;
}

function read_payroll_registry_posted() {
	$data = ORM::forTable("payroll_registry")->where("action", "POSTED")->where("status", 1)->orderByDesc("id")->findMany();
	return $data;
}

function update_payroll_registry($uid, $costcenterid, $year, $month, $period, $payroll_type, $employee_type, $description, $transaction_date, $action, $status) {
	$query = ORM::forTable("payroll_registry")->where("uid", $uid)->findOne();
	$query->set("cost_center_uid", $costcenterid);	
	$query->set("year", $year);
	$query->set("month", $month);
	$query->set("period", $period);
	$query->set("payroll_type", $payroll_type);
	$query->set("employee_type", $employee_type);
	$query->set("description", $description);
	$query->set("transaction_date", $transaction_date);
	$query->set("action", $action);
	$query->set("date_modified", date("Y-m-d H:i:s"));
	$query->set("status", $status);
	$query->save();
}

function update_payroll_registry_action($uid, $action) {
	$query = ORM::forTable("payroll_registry")->where("uid", $uid)->findOne();
	$query->set("action", $action);
	$query->set("date_modified", date("Y-m-d H:i:s"));
	$query->save();
}
 
function count_payroll_registry_by_year_month($year, $month, $costcenterid) {
	$data = ORM::forTable("payroll_registry")->where("year", $year)->where("month", $month)->where("cost_center_uid", $costcenterid)->count();
	return $data;
}

function validate_timekeeping_log($uid) {
	$data = ORM::forTable("timekeeping_log_file")->where("uid", $uid)->where("remarks", "Processing")->where("status", 1)->findOne();
	$token = false;
	if($data) {
		$token = true;
	}
	return $token;
}

function validate_payroll_registry_by_uid($uid) {
	$data = ORM::forTable("payroll_registry")->where("uid", $uid)->where("status", 1)->findOne();
	$token = false;
	if($data) {
		$token = true;
	}
	return $token;
}

function count_payroll_registry_by_uid($uid) {
	$data = ORM::forTable("payroll_registry")->where("uid", $uid)->where("status", 1)->count();
	return $data;
}

function read_payroll_timekeeping_summary_by_uid($uid) {
	# days_present, hours_work, days_absent, days_absent_min, late, undertime, total_tardy, total_tardy_min, total_tardiness, ot, rdot, shot, shrdot, rhot, rhrdot, rd, sh, shrd, rh, rhrd
	$sql = "SELECT t1.uid, t1.emp_uid, t1.days_present, t1.hours_work, t1.days_absent, t1.days_absent_min, t1.late, t1.undertime, t1.total_tardy, t1.total_tardy_min, t1.total_tardiness, t2.ot, t2.rdot, t2.shot, t2.shrdot, t2.rhot, t2.rhrdot, t2.rd, t2.sh, t2.shrd, t2.rh, t2.rhrd FROM temp_timekeeping_summary AS t1 LEFT JOIN temp_overtime_holiday_summary AS t2 ON t1.emp_uid = t2.emp_uid AND t1.uid = t2.uid WHERE t1.uid = :uid AND t1.status = 1";	
	$query = ORM::forTable("temp_timekeeping_summary")->rawQuery($sql, array("uid" => $uid))->findMany();
    return $query;
}

function create_payroll_summary($uid, $emp_uid, $period, $basic, $daysPresent, $daysAbsent, $grossPay, $deminimis, $ecola, $ot, $tardiness, $sss, $philhealth, $pagibig, $sss_loan_amount, $hdmf_loan_amount, $company_loan_amount, $tax, $adj_add, $adj_less, $net_amount) {
	// id, uid, emp_id, period, basic, deminimis, ecola, ot, tardiness, sss, philhealth, pagibig, tax, adj_add, adj_less, net_amount, date_created, date_modified, status
	$query = ORM::forTable("payroll_summary")->create();
	$query->uid = $uid;
	$query->emp_uid = $emp_uid;	
	$query->period = $period;		
	$query->basic = $basic;	
	$query->days_present = $daysPresent;
	$query->days_absent = $daysAbsent;
	$query->gross_regular_pay = $grossPay;	
	$query->deminimis = $deminimis;
	$query->ecola = $ecola;
	$query->ot = $ot;
	$query->tardiness = $tardiness;
	$query->sss = $sss;
	$query->philhealth = $philhealth;
	$query->pagibig = $pagibig;
	$query->sss_loan = $sss_loan_amount;
	$query->pagibig_loan = $hdmf_loan_amount;
	$query->company_loan = $company_loan_amount;
	$query->tax = $tax; 
	$query->adj_add = $adj_add; 
	$query->adj_less = $adj_less; 
	$query->net_amount = $net_amount; 	
	$query->date_created = date("Y-m-d H:i:s");
	$query->date_modified = date("Y-m-d H:i:s");
	$query->status = 1;
    $query->save();
}

function read_payroll_summary_by_uid($uid) {
	$data = ORM::forTable("payroll_summary")->where("uid", $uid)->where("status", 1)->findMany();
	return $data;
}

function read_employee_payroll_summary_by_uid($uid, $emp_uid) {
	$data = ORM::forTable("payroll_summary")->where("uid", $uid)->where("emp_uid", $emp_uid)->where("status", 1)->findOne();
	return $data;
}

function merge_payroll_timekeeping_overtime_holiday($uid) {	
	$sql = "SELECT t1.uid, t1.emp_uid, t1.days_present, t1.hours_work, t1.days_absent, t1.days_absent_min, t1.late, t1.undertime, t1.total_tardy, t1.total_tardy_min, t1.total_tardiness, t2.ot, t2.rdot, t2.shot, t2.shrdot, t2.rhot, t2.rhrdot, t2.rd, t2.sh, t2.shrd, t2.rh, t2.rhrd FROM payroll_timekeeping_summary as t1 LEFT JOIN payroll_overtime_holiday_summary as t2 ON t1.emp_uid = t2.emp_uid AND t1.uid = t2.uid WHERE t1.uid = '$uid'";
	$query = ORM::forTable("payroll_timekeeping_summary")->rawQuery($sql, array("uid" => $uid))->findMany();
    return $query;
}

function validate_payroll_summary_by_uid($uid) {
	$data = ORM::forTable("payroll_summary")->where("uid", $uid)->where("status", 1)->findOne();
	$token = false;
	if($data) {
		$token = true;
	}
	return $token;
}

/* Payroll Adjustment */
function create_payroll_adjustments($uid, $emp_uid, $new_amount, $new_remark, $new_date, $new_type, $period) {
	$query = ORM::forTable("payroll_adjustment")->create();
	$query->uid = $uid;
	$query->emp_uid = $emp_uid;
	$query->amount = $new_amount;
	$query->type = $new_type;
	$query->payroll_date = $new_date;
	$query->period = $period;
	$query->remarks = $new_remark;
	$query->date_created = date("Y-m-d H:i:s");
	$query->date_modified = date("Y-m-d H:i:s");
	$query->status = 1;
    $query->save();
}

function read_payroll_adjustments() {
	$query = ORM::forTable("payroll_adjustment")->where("status", 1)->findMany();
    return $query;
}

function read_payroll_adjustment_by_emp_uid_payroll_date($emp_uid, $trans_date, $period) {
	$query = ORM::forTable("payroll_adjustment")->where("emp_uid", $emp_uid)->where("payroll_date", $trans_date)->where("status", 1)->where("period", $period)->findMany();
    return $query;
}

function update_payroll_adjustments_by_uid($uid, $amount, $remark, $date, $type, $period) {
	$query = ORM::forTable("payroll_adjustment")->where("uid", $uid)->findOne();
	$query->set("amount", $amount);	
	$query->set("type", $type);
	$query->set("payroll_date", $date);	
	$query->set("period", $period);
	$query->set("remarks", $remark);
	$query->set("date_modified", date("Y-m-d H:i:s"));
	$query->save();
}

/* Process Payroll */
function compute_overtime_holiday($rate, $ot, $rdot, $shot, $shrdot, $rhot, $rhrdot, $rd, $sh, $shrd, $rh, $rhrd) {
	$rateOT = "1.25";
	$rateRDOT = "1.69";
	$rateSHOT = "1.69";
	$rateSHRDOT = "1.95";
	$rateRHOT = "2.60";
	$rateRHRDOT = "3.38";
	
	$rateRD = "0.30";
	$rateSH = "0.30";
	$rateSHRD = "0.50";
	$rateRH = "1.0";
	$rateRHRD = "1.6";
	
	$a = $rate * $ot * $rateOT;
	$b = $rate * $rdot * $rateRDOT;
	$c = $rate * $shot * $rateSHOT;
	$d = $rate * $shrdot * $rateSHRDOT;
	$e = $rate * $rhot * $rateRHOT;
	$f = $rate * $rhrdot * $rateRHRDOT;	
	$g = $rate * $rd * $rateRD;
	$h = $rate * $sh * $rateSH;
	$i = $rate * $shrd * $rateSHRD;
	$j = $rate * $rh * $rateRH;
	$k = $rate * $rhrd * $rateRHRD;
	
	$overtime = $a + $b + $c + $d + $e + $f + $g + $h + $i + $j + $k;
	
	return $overtime;
}

/* Tax Status */
function create_tax_type() {
	# id uid type date_created date_modified status
	$query = ORM::forTable("tax_type")->create();
	$query->uid = xguid();
	$query->type = $type;
	$query->date_created = date("Y-m-d H:i:s");
	$query->date_modified = date("Y-m-d H:i:s");
	$query->status = 1;
    $query->save();
}

function read_tax_type() {
	$query = ORM::forTable("tax_type")->where("status", 1)->findMany();
    return $query;
}

function read_tax_type_by_uid($uid) {
	$query = ORM::forTable("tax_type")->where("uid", $uid)->where("status", 1)->findOne();
    //return $query;
	if($query) {
		return $query;
	}
	else {
		return "N/A";
	}
}

function get_employee_tax($uid, $basic) {
	$tax = null;
	$emp = getEmployeeDetailsByUid($uid);
	if($emp) {
		$tax_uid = $emp->tax_status;	
		$tax = get_tax_table_amount_by_uid($tax_uid, $basic);
	}
	return $tax;
}

function read_tax_exemption($row) {
	$query = ORM::forTable("tax_exemption")->where("row", $row)->where("status", 1)->findOne();
    return $query;
}

function read_tax_exemption_horizontal() {
	$query = ORM::forTable("tax_exemption")->where("status", 1)->findMany();	
	return $query;
}

function get_employee_last_payroll_netpay($uid, $emp_uid) {
	$query = ORM::forTable("payroll_summary")->select("net_amount")->where("uid", $uid)->where("emp_uid", $emp_uid)->order_by_asc("id")->findOne();
    
	if($query) {
		return $query->net_amount;
	}
	else {
		return 0;
	}
	
}

/* Transfer temp_timekeeping_summary to payroll_timekeeping_summary */
function transfer_timekeeping_summary($uid) {
	$con = db_connect();
	$sql = "INSERT INTO `payroll_timekeeping_summary` (`uid`, `emp_uid`, `days_present`, `hours_work`, `days_absent`, `days_absent_min`, `late`, `undertime`, `total_tardy`, `total_tardy_min`, `total_tardiness`, `date_created`, `date_modified`, `status`) SELECT `uid`, `emp_uid`, `days_present`, `hours_work`, `days_absent`, `days_absent_min`, `late`, `undertime`, `total_tardy`, `total_tardy_min`, `total_tardiness`, `date_created`, `date_modified`, `status` FROM `temp_timekeeping_summary` WHERE uid = '$uid'";	
	mysqli_query($con, $sql);
}

/* Transfer temp_overtime_holiday_summary to payroll_overtime_holiday_summary */
function transfer_overtime_holiday_summary($uid) {
	$con = db_connect();
	$sql = "INSERT INTO `payroll_overtime_holiday_summary` (`uid`, `emp_uid`, `ot`, `rdot`, `shot`, `shrdot`, `rhot`, `rhrdot`, `rd`, `sh`, `shrd`, `rh`, `rhrd`, `date_created`, `date_modified`, `status`) SELECT `uid`, `emp_uid`, `ot`, `rdot`, `shot`, `shrdot`, `rhot`, `rhrdot`, `rd`, `sh`, `shrd`, `rh`, `rhrd`, `date_created`, `date_modified`, `status` FROM `temp_overtime_holiday_summary` WHERE uid = '$uid'";	
	mysqli_query($con, $sql);
}

function delete_table($uid, $table) {
	$query = ORM::forTable($table)->where("uid", $uid)->deleteMany();
    return $query;
}

# File Summary Upload - Uploading of Manual Timekeeping, Overtime and Adjustment
function create_file_summary_upload($record, $files) {
	if($files=="timekeeping") {
		$data = ORM::forTable("temp_timekeeping_summary")->create();
		$data->uid = $record['uid'];
		$data->emp_uid = $record['emp_uid'];		
		$data->days_present = $record['days_present'];	
		$data->days_absent = $record['days_absent'];
		$data->days_absent_min = $record['days_absent_min'];
		$data->total_tardy_min = $record['total_tardy_min'];
		$data->total_tardiness = $record['total_tardiness'];
		$data->date_created = date("Y-m-d H:i:s");
		$data->date_modified = date("Y-m-d H:i:s");
		$data->status = 1;
		$data->save();
	}
	else if($files=="overtime") {
		$data = ORM::forTable("temp_overtime_holiday_summary")->create();
		$data->uid = $record['uid'];
		$data->emp_uid = $record['emp_uid'];			
		$data->ot = $record['ot'];
		$data->rdot = $record['rdot'];
		$data->shot = $record['shot'];
		$data->shrdot = $record['shrdot'];
		$data->rhot = $record['rhot'];
		$data->rhrdot = $record['rhrdot'];
		$data->rd = $record['rd'];
		$data->sh = $record['sh'];
		$data->shrd = $record['shrd'];
		$data->rh = $record['rh'];
		$data->rhrd = $record['rhrd'];
		$data->nd = $record['nd'];			
		$data->date_created = date("Y-m-d H:i:s");
		$data->date_modified = date("Y-m-d H:i:s");
		$data->status = 1;
		$data->save();
	}
	else if($files=="adjustment") {
		$data = ORM::forTable("temp_adjustment_summary")->create();
		$data->uid = $record['uid'];
		$data->emp_uid = $record['emp_uid'];		
		$data->type = $record['type'];	
		$data->amount = $record['amount'];	
		$data->remarks = $record['remarks'];		
		$data->date_created = date("Y-m-d H:i:s");
		$data->date_modified = date("Y-m-d H:i:s");
		$data->status = 1;
		$data->save();
	}
}

/* SSS Contribution Table */
function create_sss_table($rangeOfComp, $rangeOfCompEnd, $basic_salary, $sssEr, $sssEe, $sssTotal) {
	$query = ORM::forTable("hris_sss")->create();
	$query->rangeOfComp = $rangeOfComp;	
	$query->rangeOfCompEnd = $rangeOfCompEnd;
	$query->basic_salary = $basic_salary;
	$query->sssEr = $sssEr;
	$query->sssEe = $sssEe;
	$query->sssTotal = $sssTotal;
    $query->save();
}

function read_sss_by_id($id) {
    $query = ORM::forTable("hris_sss")->where("id", $id)->findOne();
    return $query;
}

/* PhilHealth Contribution Table */
function read_philhealth_by_id($id) {
    $query = ORM::forTable("hris_philhealth")->where("id", $id)->findOne();
    return $query;
}

function read_philhealth_tables() {
    $query = ORM::forTable("hris_philhealth_new")->where("status", 1)->findMany();
    return $query;
}

function read_philhealth_by_year($year) {
    $query = ORM::forTable("hris_philhealth_new")->where("year", $year)->findOne();
    return $query;
}

function read_philhealth_by_uid($uid) {
    $query = ORM::forTable("hris_philhealth_new")->where("uid", $uid)->findOne();
    return $query;
}

function update_philhealth_tables($uid, $year, $percent) {
	$query = ORM::forTable("hris_philhealth_new")->where("uid", $uid)->findOne();
	$query->set("year", $year);
	$query->set("percent", $percent);
	$query->save();
}

/* BIR Tax Table */
function read_tax_table($uid) {
	$sql = "select t1.type,(select t2.amount from tax_table as t2 where t2.uid = '".$uid."' and t2.row = '1') as r1, (select t2.amount from tax_table as t2 where t2.uid = '".$uid."' and t2.row = '2') as r2, (select t2.amount from tax_table as t2 where t2.uid = '".$uid."' and t2.row = '3') as r3, (select t2.amount from tax_table as t2 where t2.uid = '".$uid."' and t2.row = '4') as r4, (select t2.amount from tax_table as t2 where t2.uid = '".$uid."' and t2.row = '5') as r5, (select t2.amount from tax_table as t2 where t2.uid = '".$uid."' and t2.row = '6') as r6, (select t2.amount from tax_table as t2 where t2.uid = '".$uid."' and t2.row = '7') as r7, (select t2.amount from tax_table as t2 where t2.uid = '".$uid."' and t2.row = '8') as r8 from tax_type as t1 where t1.uid = '".$uid."'";
	$query = ORM::forTable("tax_type")->rawQuery($sql, array("uid" => $uid))->findMany();
    return $query;
}

function read_tax_exempt($field, $row) {
	$sql = "SELECT `" . $field . "` FROM tax_exemption WHERE row = '" . $row . "' AND status = '1'";
	$query = ORM::forTable("tax_exemption")->rawQuery($sql)->findMany();
    return $query;
}

/* Bank Setup */
function create_bank($bank, $code, $account_number, $company_code, $presenting_office, $remarks) {
	# id uid bank code account_number company_code presenting_office remarks date_created date_modified status
	$query = ORM::forTable("banks")->create();
	$query->uid = xguid();
	$query->bank = $bank;	
	$query->code = $code;	
	$query->account_number = $account_number;	
	$query->company_code = $company_code;	
	$query->presenting_office = $presenting_office;
	$query->remarks = $remarks;
	$data->date_created = date("Y-m-d H:i:s");
	$data->date_modified = date("Y-m-d H:i:s");
	$data->status = 1;
    $query->save();
}

function read_bank() {
    $query = ORM::forTable("banks")->where("status", 1)->findMany();
    return $query;
}

function read_bank_by_uid($uid) {
	$query = ORM::forTable("banks")->where("uid", $uid)->findOne();
    return $query;
}

function get_employee_salary_to_date($emp_uid) {
	$sql = "SELECT SUM( net_amount ) AS netpay FROM  `payroll_summary` WHERE  `emp_uid` LIKE '" . $emp_uid . "' AND `period` LIKE '" . date('Y') . "%'";
	$query = ORM::forTable("payroll_summary")->rawQuery($sql)->findOne();
    return $query->netpay;
}

function get_employee_tax_to_date($emp_uid) {
	$sql = "SELECT SUM( tax ) AS tax FROM  `payroll_summary` WHERE  `emp_uid` LIKE '" . $emp_uid . "' AND `period` LIKE '" . date('Y') . "%'";
	$query = ORM::forTable("payroll_summary")->rawQuery($sql)->findOne();
    return $query->tax;
}

function get_cost_center($uid){
    $query = ORM::forTable("cost_center")->where("cost_center_uid", $uid)->where("status", 1)->findOne();
    return $query;
}

# Allowance Table
function read_employee_allowances() {
	$query = ORM::forTable("allowance")->where("status", 1)->findMany();
    return $query;
}

# Employee Loans
function create_new_employee_loans($emp_uid, $loan_uid, $application_no, $amortization, $terms, $loan_granted, $date_granted,$first_amortization, $amortization_period) {
	$query = ORM::forTable("emp_loans_new")->create();
	$query->uid = xguid();
	$query->emp_uid = $emp_uid;	
	$query->loan_uid = $loan_uid;	
	$query->application_no = $application_no;	
	$query->amortization = $amortization;	
	$query->terms = $terms;
	$query->loan_granted = $loan_granted;
	$query->date_granted = $date_granted;
	$query->first_monthly_amortization = $first_amortization;
	$query->amortization_period = $amortization_period;
	$query->remarks = "N/A";
	$query->date_created = date("Y-m-d H:i:s");
	$query->date_modified = date("Y-m-d H:i:s");
	$query->status = 1;
    $query->save();
}

function read_new_employee_loans() {
	$query = ORM::forTable("emp_loans_new")->where("status", 1)->findMany();
    return $query;
}

function read_new_employee_loans_by_uid($uid) {
	$query = ORM::forTable("emp_loans_new")->where("uid", $uid)->where("status", 1)->findMany();
    return $query;
}

function validate_new_employee_loans_by_loan_uid($emp_uid, $loan_uid) {
	$query = ORM::forTable("emp_loans_new")
	->where("emp_uid", $emp_uid)
	->where("loan_uid", $loan_uid)
	->where("status", 1)
	->count();
	$value = True;
	if($query>=1) {
		$value = False;
	}
    return $value;
}

function get_employee_loans_and_loans_by_loan_type_and_emp_uid($type, $emp_uid) {
	$data = ORM::forTable("emp_loans_new")
		->table_alias("t1")
		->select("t1.uid")
		->select("t1.emp_uid")
		->select("t1.loan_uid")
		->select("t1.application_no")
		->select("t1.amortization")
		->select("t1.terms")
		->select("t1.loan_granted")
		->select("t1.date_granted")
		->select("t1.first_monthly_amortization")
		->select("t1.amortization_period")
		->select("t2.name")
		->select("t2.type")
		->join("loans", array("t1.loan_uid", "=", "t2.uid"), "t2")
		->where("t2.type", $type)
		->where("t1.emp_uid", $emp_uid)
		->where("t1.status", 1)
		->orderByAsc("t2.type")
		->findMany();
	return $data;
}

function get_employee_loans_and_loans_by_loan_type($type) {
	$data = ORM::forTable("emp_loans_new")
		->table_alias("t1")
		->select("t1.uid")
		->select("t1.emp_uid")
		->select("t1.loan_uid")
		->select("t1.application_no")
		->select("t1.amortization")
		->select("t1.terms")
		->select("t1.loan_granted")
		->select("t1.date_granted")
		->select("t1.first_monthly_amortization")
		->select("t1.amortization_period")
		->select("t2.name")
		->select("t2.type")
		->join("loans", array("t1.loan_uid", "=", "t2.uid"), "t2")
		->where("t2.type", $type)
		->where("t1.status", 1)
		->orderByAsc("t2.type")
		->findMany();
	return $data;
}

function get_employee_loans_and_loans_by_loan_uid($uid) {
	$data = ORM::forTable("emp_loans_new")
		->table_alias("t1")
		->select("t1.uid")
		->select("t1.emp_uid")
		->select("t1.loan_uid")
		->select("t1.application_no")
		->select("t1.amortization")
		->select("t1.terms")
		->select("t1.loan_granted")
		->select("t1.date_granted")
		->select("t1.first_monthly_amortization")
		->select("t1.amortization_period")
		->select("t2.name")
		->select("t2.type")
		->join("loans", array("t1.loan_uid", "=", "t2.uid"), "t2")
		->where("t1.loan_uid", $uid)
		->where("t1.status", 1)
		->orderByAsc("t2.type")
		->findMany();
	return $data;
}

function get_employee_loans_and_loans_all() {
	$data = ORM::forTable("emp_loans_new")
		->table_alias("t1")
		->select("t1.uid")
		->select("t1.emp_uid")
		->select("t1.loan_uid")
		->select("t1.application_no")
		->select("t1.amortization")
		->select("t1.terms")
		->select("t1.loan_granted")
		->select("t1.date_granted")
		->select("t1.first_monthly_amortization")
		->select("t1.amortization_period")
		->select("t2.name")
		->select("t2.type")
		->join("loans", array("t1.loan_uid", "=", "t2.uid"), "t2")
		->where("t1.status", 1)
		->orderByAsc("t2.type")
		->findMany();
	return $data;
}

function get_employee_loans_payroll($type, $emp_uid) {
	$response = array();
	$results = get_employee_loans_and_loans_by_loan_type_and_emp_uid($type, $emp_uid);
    foreach($results as $result) {
        $emp_name = read_employee_lastname_by_uid($emp_uid);
        $response[] = array(
            "employee_name" => $emp_name,
            "emp_uid" => $result->emp_uid,
            "application_no" => $result->application_no,
            "amortization" => $result->amortization,
            "terms" => $result->terms,
            "loan_granted" => $result->loan_granted,
            "date_granted" => $result->date_granted,
            "first_amortization" => $result->first_monthly_amortization,
            "amortization_period" => $result->amortization_period,
            "loan_name" => $result->name,
            "loan_type" => $result->type
        );
    }

    echo jsonify($response);
}

function get_employee_loans_payroll_amounts($type, $emp_uid, $payroll_period) {
	//$emp_uid = "0B5B0808-4CFB-323F-E289-F51A6CF66577";
    //$type = "sss";    
    //$payroll_period = "2021/06/30";
    $ptime = strtotime($payroll_period);
    $monthly_amortization = 0;

    $results = get_employee_loans_and_loans_by_loan_type_and_emp_uid($type, $emp_uid);
    foreach($results as $result) {
        $first_payment_date = $result->first_monthly_amortization . "/15"; //echo $first_payment_date;
        $last_payment_date = $result->amortization_period;
        $stime = strtotime($first_payment_date); 
        $etime = strtotime($last_payment_date . "+15 days"); //echo " " . date("Y-m-d",$etime);

        if($stime<=$ptime && $ptime<=$etime) {
            $monthly_amortization = $monthly_amortization + $result->amortization;
		}
    }

    $response = array(
        "amortization" => $monthly_amortization
    );

    return $monthly_amortization;
}

function validate_new_employee_loans_by_uid($uid) {
	$query = ORM::forTable("emp_loans_new")
	->where("uid", $uid)
	->where("status", 1)
	->count();
	$value = false;
	if($query>=1) {
		$value = true;
	}
    return $value;
}

function update_new_employee_loans_by_uid($uid, $application_no, $amortization, $terms, $loan_granted, $date_granted,$first_amortization, $amortization_period) {
	$query = ORM::forTable("emp_loans_new")->where("uid", $uid)->findOne();
	$query->set("application_no", $application_no);
	$query->set("amortization", $amortization);
	$query->set("terms", $terms);
	$query->set("loan_granted", $loan_granted);
	$query->set("date_granted", $date_granted);
	$query->set("first_monthly_amortization", $first_amortization);
	$query->set("amortization_period", $amortization_period);
	$query->set("date_modified", date("Y-m-d H:i:s"));
	$query->save();
}

function get_pay_period_uid($uid) {
	$query = ORM::forTable("pay_period")->where("pay_period_uid", $uid)->where("status", 1)->findOne();
	return $query;
}

function get_pay_period_name($name) {
	$query = ORM::forTable("pay_period")->where("pay_period_name", $name)->where("status", 1)->findOne();
	return $query;
}

function get_payroll_tax($uid) {
	$query = ORM::forTable("payroll_tax")->where("uid", $uid)->where("status", 1)->orderByAsc("columns")->findMany();
	return $query;
}

function get_employee_payroll_tax($uid, $taxable_amount) {

	$tax_table = get_payroll_tax($uid);
	foreach($tax_table as $value) {
		//$taxable_amount = $taxable_amount;
		$begin = $value->begin;
		$end = $value->end;

		if($taxable_amount>$begin && $taxable_amount<=$end) {
			$columns = $value->columns;
			$compensation = $value->compensation_range;
			$percent = $value->percent;
			$fixed = $value->fixed;
		}
	}

	$excess = $taxable_amount - $compensation;
	//$excess = number_format($excess, 2, ".", "");
	
	$percent = $excess * $percent;               
	$percent = number_format($percent, 2, ".", "");        

	$tax = $fixed + $percent;

	return $tax;
}

