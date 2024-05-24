<?php
date_default_timezone_set("Asia/Manila");
/* CRUD - Create, Read, Update, Delete */

/* Schedule Type */
# id, uid, department_uid, type, date_created, date_modified, $status
function create_schedule_type($uid, $type, $date_created, $date_modified) {
	$data = ORM::forTable("schedule_type")->create();
		$data->uid = $uid;
		$data->type = $type;
		$data->date_created = $date_created;
		$data->date_modified = $date_modified;
		$data->status = 1;
	$data->save();
}

function read_schedule_type() {
	$data = ORM::forTable("schedule_type")->where("status", 1)->findMany();
	return $data;
}

function read_schedule_type_all() {
	$data = ORM::forTable("schedule_type")->findMany();
	return $data;
}

function read_schedule_type_limit_one($uid) {
	$data = ORM::forTable("schedule_type")->where("uid", $uid)->findOne();
	return $data;
}

function read_schedule_type_by_uid($uid) {
	$data = ORM::forTable("schedule_type")->where("uid", $uid)->findMany();
	return $data;
}

function update_schedule_type($uid, $type, $date_modified, $status) {
	$data = ORM::forTable("schedule_type")
		->where("uid", $uid)
		->findOne();
	$data->set("type", $type);
    $data->set("date_modified", $date_modified);
	$data->set("status", $status);
    $data->save();
}

function delete_schedule_type($uid) {
	$data = ORM::forTable("schedule_type")->where("uid", $uid)->findOne();
	$data->set("status", 0);
    $data->save();
}

/* Work Schedule */
# id, uid, schedule_type, schedule_date, shift_uid, date_created, date_modified, status
function create_work_schedule($schedule_type, $schedule_date, $shift_uid) {
	$data = ORM::forTable("work_schedule")->create();
		$data->uid = xguid();
		$data->schedule_type = $schedule_type;
		$data->schedule_date = $schedule_date;
		$data->shift_uid = $shift_uid;
		$data->date_created = date("Y-m-d H:i:s");
		$data->date_modified = date("Y-m-d H:i:s");
		$data->status = 1;
	$data->save();
}

function read_work_schedule() {
	$data = ORM::forTable("work_schedule")->where("status", 1)->findMany();
	return $data;
}

function read_work_schedule_desc() {
	$data = ORM::forTable("work_schedule")->where("status", 1)->order_by_desc("schedule_date")->findMany();
	return $data;
}

function read_work_schedule_all() {
	$data = ORM::forTable("work_schedule")->findMany();
	return $data;
}

function read_work_schedule_by_uid($uid) {
	$data = ORM::forTable("work_schedule")->where("uid", $uid)->findMany();
	return $data;
}

function update_work_schedule($uid, $schedule_type, $schedule_date, $shift_uid, $date_modified, $status) {
	$data = ORM::forTable("work_schedule")
		->where("uid", $uid)
		->findOne();
    $data->set("schedule_type", $schedule_type);
	$data->set("schedule_date", $schedule_date);
	$data->set("shift_uid", $shift_uid);
    $data->set("date_modified", $date_modified);
	$data->set("status", $status);
    $data->save();
}

function delete_work_schedule($uid) {
	$data = ORM::forTable("work_schedule")->where("uid", $uid)->findOne();
	$data->set("status", 0);
    $data->save();
}

/* Work Scheduled */
# id, uid, schedule_uid, schedule_type, from_date, to_date, shift_uid, shift_type, date_created, date_modified, status 
function create_work_scheduled($uid, $schedule_uid, $schedule_type, $from_date, $to_date, $shift_uid, $shift_type) {
	$data = ORM::forTable("work_scheduled")->create();
		$data->uid = $uid;
		$data->scheduled_uid = $schedule_uid;
		$data->scheduled_type = $schedule_type;		
		$data->from_date = $from_date;
		$data->to_date = $to_date;
		$data->shift_uid = $shift_uid;
		$data->shift_type = $shift_type;		
		$data->date_created = date("Y-m-d H:i:s");
		$data->date_modified = date("Y-m-d H:i:s");
		$data->status = 1;
	$data->save();
}

function read_work_scheduled() {
	$data = ORM::forTable("work_scheduled")->where("status", 1)->findMany();
	return $data;
}

function read_work_scheduled_by_uid($uid) {
	$data = ORM::forTable("work_scheduled")->where("uid", $uid)->findMany();
	return $data;
}

function read_work_scheduled_by_uid_one($uid) {
	$data = ORM::forTable("work_scheduled")->where("uid", $uid)->findOne();
	return $data;
}

function update_work_scheduled($uid, $schedule_uid, $schedule_type, $from_date, $to_date, $shift_uid, $shift_type) {
	$data = ORM::forTable("work_scheduled")
		->where("uid", $uid)
		->findOne();
	$data->set("scheduled_uid", $schedule_uid);
    $data->set("scheduled_type", $schedule_type);
	$data->set("from_date", $from_date);
	$data->set("to_date", $to_date);
	$data->set("shift_uid", $shift_uid);
	$data->set("shift_type", $shift_type);
    $data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

function validateWorkScheduled($uid) {	
	$valid = false;
	$data = ORM::forTable("work_scheduled")->where("uid", $uid)->count();
	if($data == 1) {
		$valid = true;
	}
	return $valid;
}

function delete_work_scheduled($uid) {
	$data = ORM::forTable("work_scheduled")->where("uid", $uid)->deleteMany();
	//$data->set("status", 0);
    //$data->save();
	return $data;
}

/* Settings */
# id, uid, items, value, date_created, date_modified, status
function create_settings($items, $code, $value) {
	$data = ORM::forTable("settings")->create();
		$data->uid = xguid();
		$data->items = $items;
		$data->code = $code;
		$data->value = $value;
		$data->date_created = date("Y-m-d H:i:s");
		$data->date_modified = date("Y-m-d H:i:s");
		$data->status = 1;
	$data->save();
}

function read_settings() {
	$data = ORM::forTable("settings")->where("status", 1)->order_by_asc("items")->findMany();
	return $data;
}

function read_settings_all() {
	$data = ORM::forTable("settings")->order_by_asc("items")->findMany();
	return $data;
}

function read_settings_by_uid($uid) {
	$data = ORM::forTable("settings")->where("uid", $uid)->findMany();
	return $data;
}

function read_settings_by_code($code) {
	$data = ORM::forTable("settings")->where("code", $code)->findOne();
	return $data->value;
}

function update_settings($uid, $items, $code, $value, $status) {
	$data = ORM::forTable("settings")
		->where("uid", $uid)
		->findOne();
    $data->set("items", $items);
	$data->set("code", $code);
	$data->set("value", $value);
    $data->date_modified = date("Y-m-d H:i:s");
	$data->set("status", $status);
    $data->save();
}

function delete_settings($uid) {
	$data = ORM::forTable("settings")->where("uid", $uid)->findOne();
	$data->set("status", 0);
    $data->save();
}

/* Shift */
function read_shift_by_uid($uid) {
	$data = ORM::forTable("shift")->where("shift_uid", $uid)->findOne();
	return $data;
}

/* Employee Schedule */
function create_emp_scheduled($work_schedule_uid, $schedule_date, $emp_uid) {
	# id uid work_schedule_uid schedule_date emp_uid date_created date_modified status
	$data = ORM::forTable("emp_scheduled")->create();
		$data->uid = xguid();
		$data->work_schedule_uid = $work_schedule_uid;		
		$data->schedule_date = $schedule_date;
		$data->emp_uid = $emp_uid;		
		$data->date_created = date("Y-m-d H:i:s");
		$data->date_modified = date("Y-m-d H:i:s");
		$data->status = 1;
	$data->save();
}

function read_emp_scheduled($uid) {
	$data = ORM::forTable("emp_scheduled")->where("uid", $uid)->findMany();
	return $data;
}

function read_emp_scheduled_date_emp_uid($date, $emp_uid) {
	$data = ORM::forTable("emp_scheduled")->where("emp_uid", $emp_uid)->where("schedule_date", $date)->findOne();
	return $data;
}

function read_emp_scheduled_orderby_desc() {
	$data = ORM::forTable("emp_scheduled")->where("status", 1)->order_by_desc("schedule_date")->findMany();
	return $data;
}

function update_emp_scheduled($uid, $emp_uid) {
	$data = ORM::forTable("emp_scheduled")->where(array("uid" => $uid, "emp_uid", $emp_uid))->findOne();		
    $data->set("work_schedule_uid", $work_schedule_uid);
	$data->set("schedule_date", $schedule_date);
    $data->set("date_modified", date("Y-m-d H:i:s"));
	$data->set("status", $status);
    $data->save();
}

function delete_emp_scheduled($work_schedule_uid) {
	$data = ORM::forTable("emp_scheduled")->where("work_schedule_uid", $work_schedule_uid)->deleteMany();
	return $data;
}

/* Timekeeping Summary - Top */
function getEmployeeTimeLog($start, $end, $center) {
	$query = ORM::forTable("time_log")	
	//->rawQuery("SELECT DISTINCT(t1.emp_uid) FROM time_log as t1 INNER JOIN users as t2 ON t1.emp_uid = t2.emp_uid WHERE date(t1.date_created) BETWEEN :start AND :end ORDER BY t2.username", array("start" => $start, "end" => $end))
	->rawQuery("SELECT DISTINCT(t1.emp_uid) FROM time_log as t1 INNER JOIN users as t2 ON t1.emp_uid = t2.emp_uid WHERE date(t1.date_created) BETWEEN :start AND :end AND t1.emp_uid IN (SELECT t3.emp_uid FROM emp_cost_center
 as t3 WHERE t3.cost_center_uid = :center) ORDER BY t2.username", array("start" => $start, "end" => $end, "center" => $center))
    ->findMany();
    return $query;
}

function getLate($time_in, $shift_in, $gperiod) {
	$str_time_in = strtotime($time_in);
	$str_shift_in = strtotime($shift_in);
	$str_gperiod = strtotime($gperiod);
	
	if($str_time_in >= $str_gperiod) {
		$query = ORM::forTable("time_log")->rawQuery("SELECT TIMEDIFF('".$time_in."', '".$shift_in."') as late")->findOne();
		//$query = ORM::forTable("time_log")->rawQuery("SELECT TIMESTAMPDIFF(MINUTE ,'".$shift_in."', '".$time_in."') as late")->findOne();
		return $query["late"];
	}
	else {
		return "00:00:00";
	}
}

function getUndertime($time_out, $shift_out) {
	$str_time_out = strtotime($time_out);
	$str_shift_out = strtotime($shift_out);
	
	if($str_time_out < $str_shift_out) {
		$query = ORM::forTable("time_log")->rawQuery("SELECT TIMEDIFF('".$time_out."', '".$shift_out."') as undertime")->findOne();
		//$query = ORM::forTable("time_log")->rawQuery("SELECT TIMESTAMPDIFF(MINUTE ,'".$shift_out."', '".$time_out."') as undertime")->findOne();
		//return $query["undertime"];
		if( $query["undertime"]) {
			return  $query["undertime"];
		}
		else {
			return "00:00:00";
		}
	}
	else {
		return "00:00:00";
	}
}

function getOvertime($time_out, $shift_out) {
	$str_time_out = strtotime($time_out);
	$str_shift_out = strtotime($shift_out);
	
	if($str_time_out > $str_shift_out) {
		//$query = ORM::forTable("time_log")->rawQuery("SELECT TIMESTAMPDIFF(HOUR ,'".$shift_out."', '".$time_out."') as overtime")->findOne();
		$query = ORM::forTable("time_log")->rawQuery("SELECT TIMEDIFF('".$time_out."', '".$shift_out."') as overtime")->findOne();
		return $query["overtime"];
	}
	else {
		return "00:00:00";
	}
}

# Temporary Timekeeping Summary
function create_timekeeping_summary($uid, $emp_uid, $days_present, $hours_work, $days_absent, $days_absent_min, $late, $undertime, $total_tardy, $total_tardy_min, $total_tardiness) {
	# id, uid, emp_uid, days_present, hours_work, days_absent, days_absent_min, late, undertime, total_tardy, total_tardy_min, total_tardiness
	$data = ORM::forTable("temp_timekeeping_summary")->create();
		$data->uid = $uid;
		$data->emp_uid = $emp_uid;		
		$data->days_present = $days_present;
		$data->hours_work = $hours_work;		
		$data->days_absent = $days_absent;
		$data->days_absent_min = $days_absent_min;
		$data->late = $late;
		$data->undertime = $undertime;
		$data->total_tardy = $total_tardy;
		$data->total_tardy_min = $total_tardy_min;
		$data->total_tardiness = $total_tardiness;
		$data->date_created = date("Y-m-d H:i:s");
		$data->date_modified = date("Y-m-d H:i:s");
		$data->status = 1;
	$data->save();
}

function read_timekeeping_summary() {
	$data = ORM::forTable("temp_timekeeping_summary")->where("status", 1)->findMany();
	return $data;
}

function read_timekeeping_summary_by_uid($uid) {
	$data = ORM::forTable("temp_timekeeping_summary")->where("uid" ,$uid)->findMany();
	return $data;
}

function delete_timekeeping_summary($uid) {
	$data = ORM::forTable("temp_timekeeping_summary")->where("uid", $uid)->deleteMany();
	return $data;
}

# Timekeeping Log File
function create_timekeeping_log_file($uid, $period, $from, $to, $cost_center_uid, $remarks) {
	# id, uid, period, from, to, cost_center_uid, date_created, date_modified, remarks, status 
	$data = ORM::forTable("timekeeping_log_file")->create();
		$data->uid = $uid;
		$data->period = $period;		
		$data->from = $from;
		$data->to = $to;		
		$data->cost_center_uid = $cost_center_uid;
		$data->remarks = $remarks;
		$data->date_created = date("Y-m-d H:i:s");
		$data->date_modified = date("Y-m-d H:i:s");
		$data->status = 1;
	$data->save();
}

function read_timekeeping_log_file() {
	$data = ORM::forTable("timekeeping_log_file")->whereNotEqual("remarks", "Processing")->where("status", 1)->orderByDesc("id")->findMany();
	return $data;
}

function read_timekeeping_log_file_for_process() {
	$data = ORM::forTable("timekeeping_log_file")->where("remarks", "Processing")->where("status", 1)->orderByDesc("id")->findMany();
	return $data;
}

function read_timekeeping_log_file_by_uid($uid) {
	$data = ORM::forTable("timekeeping_log_file")->where("uid", $uid)->where("status", 1)->findOne();
	return $data;
}

function update_timekeeping_log_file_remarks($uid, $remarks) {
	$data = ORM::forTable("timekeeping_log_file")->where("uid", $uid)->findOne();		
    $data->set("remarks", $remarks);
    $data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

function delete_timekeeping_log_file($uid) {
	$data = ORM::forTable("timekeeping_log_file")->where("uid", $uid)->deleteMany();
	return $data;
}

/* Timekeeping Summary - Bottom */

/* Holiday Request - Top */
function checkEmployeeHasHolidayByDateAndEmpUid($emp, $date){
    $query = ORM::forTable("holiday_requests")
        ->rawQuery("SELECT count(emp_uid) as count FROM holiday_requests WHERE emp_uid = :emp AND date(start_date) = :dates AND status = 1", array("emp" => $emp, "dates" => $date))->findOne();
    return $query->count;
}

function create_holiday_requests($uid, $type ,$employee, $startDate, $endDate , $hours, $reason ,$requestStatus) {
	# id uid type emp_uid start_date end_date hours reason holiday_request_status cert_by appr_by date_created date_modified status
    $query = ORM::forTable("holiday_requests")->create();
        $query->uid = $uid;
        $query->type = $type;
        $query->emp_uid = $employee;
        $query->start_date = $startDate;
        $query->end_date = $endDate;
        $query->hours = $hours;
        $query->reason = $reason;
        $query->holiday_request_status = $requestStatus;
		$query->cert_by = "";
		$query->appr_by = "";
        $query->date_created = date("Y-m-d H:i:s");
        $query->date_modified = date("Y-m-d H:i:s");
		$query->status = 1;
    $query->save();
}

function create_holiday_notification($holidayNotifUid, $holidayRequestUid){
    $query = ORM::forTable("holiday_notification")->create();
        $query->uid = $holidayNotifUid;
        $query->holiday_request_uid = $holidayRequestUid;
        $query->date_created = date("Y-m-d H:i:s");
        $query->date_modified = date("Y-m-d H:i:s");
		$query->status = 1;
    $query->save();
}

function getEmployeeHolidayRequestsByDateRange($startDate, $endDate, $emp){
    $sql = "SELECT * FROM holiday_requests WHERE emp_uid = '" . $emp . "' AND date(start_date) BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND status = '1'";
	$query = ORM::forTable("holiday_requests")
        //->rawQuery("SELECT * FROM holiday_requests as t1 INNER JOIN overtime_type as t2 ON t1.type=t2.overtime_type_uid INNER JOIN emp as t3 ON t1.emp_uid = t3.emp_uid INNER JOIN users as t4 ON t1.emp_uid = t4.emp_uid WHERE date(t1.start_date) BETWEEN :starts AND :ends AND date(t1.start_date) BETWEEN :starts AND :ends AND t1.emp_uid = :emp AND t1.status = '1'", array("starts" => $startDate, "ends" => $endDate, "emp" => $emp))
		->rawQuery($sql)
        ->findMany();
    return $query;
}

function getHolidayRequestsByUid($uid){
    $query = ORM::forTable("holiday_requests")->tableAlias("t1")->innerJoin("holiday_types", array("t1.type", "=", "t2.holiday_type_uid"), "t2")->where("t1.uid", $uid)->findOne();
        return $query;
}

function updateHolidayRequest($uid, $type,$startDate, $endDate, $reason, $hours ,$requestStatus, $user1, $user2 ,$dateModified, $status){	
	$query = ORM::forTable("holiday_requests")->where("uid", $uid)->findOne();	
	$query->set("type", $type);
	$query->set("start_date", $startDate);
	$query->set("end_date", $endDate);
	$query->set("reason", $reason);
	$query->set("hours", $hours);
	$query->set("holiday_request_status", $requestStatus);
	$query->set("date_modified", $dateModified);
	$query->set("status", $status);
	
	switch ($requestStatus) {
    case "Certified":
        $query->set("cert_by", $user1);
        break;
    case "Approved":
		$query->set("appr_by", $user2);
        break;
	case "Pending":
        $query->set("cert_by", "");
		$query->set("appr_by", "");
        break;
    default:
        //$query->set("cert_by", $user1);
		//$query->set("appr_by", $user2);
	}
	
	$query->save();
}

function updateHolidayNotification($uid, $reqStatus, $dateModified, $status) {    
    $query = ORM::forTable("holiday_notification")->where("holiday_request_uid", $uid)->findOne();
        $query->set("request_status", $reqStatus);
        $query->set("date_modified", $dateModified);
        $query->set("status", 1);
    $query->save();
}

function countHolidayRequestPendingNotification($emp_uid){
	$sql = "SELECT * FROM holiday_requests as t1 INNER JOIN holiday_notification as t2 ON t1.uid = t2.holiday_request_uid WHERE t1.emp_uid = :emp_uid AND t2.request_status = :request AND t1.status = :status";
	$query = ORM::forTable("holiday_requests")->rawQuery($sql, array( "emp_uid" => $emp_uid, "request" => "Pending", "status" => 1 ))->count();
    return $query;
}

function countOvertimeRequestPendingNotification($emp_uid){
	$sql = "SELECT * FROM overtime_requests as t1 INNER JOIN overtime_notification as t2 ON t1.overtime_request_uid = t2.overtime_request_uid WHERE t1.emp_uid = :emp_uid AND t2.request_status = :request AND t1.status = :status";
	$query = ORM::forTable("overtime_requests")->rawQuery($sql, array( "emp_uid" => $emp_uid, "request" => "Pending", "status" => 1 ))->count();
    return $query;
}

function countAdjustmentRequestPendingNotification($emp_uid){
	$sql = "SELECT * FROM time_request as t1 INNER JOIN time_request_notification as t2 ON t1.time_requests_uid = t2.time_request_uid WHERE t1.emp_uid = :emp_uid AND t2.request_status = :request AND t1.status = :status";
	$query = ORM::forTable("time_request")->rawQuery($sql, array( "emp_uid" => $emp_uid, "request" => "Pending", "status" => 1 ))->count();
    return $query;
}

function countLeaveRequestPendingNotification($emp_uid){
	$sql = "SELECT * FROM leave_requests as t1 INNER JOIN leave_notification as t2 ON t1.leave_uid = t2.leave_request_uid WHERE t1.emp_uid = :emp_uid AND t2.request_status = :request AND t1.status = :status";
	$query = ORM::forTable("leave_requests")->rawQuery($sql, array( "emp_uid" => $emp_uid, "request" => "Pending", "status" => 1 ))->count();
    return $query;
}

function getEmployeeHolidayRequests($uid){
    $query = ORM::forTable("holiday_requests")->where("emp_uid", $uid)->where("status", 1)->findMany();
    return $query;
}

function read_holiday_request_by_date_range_status($startDate, $endDate, $status) {
	$sql = "SELECT * FROM holiday_requests WHERE date(start_date) BETWEEN :start AND :end AND holiday_request_status = :status AND status = '1'";
	$query = ORM::forTable("holiday_requests")->rawQuery($sql, array("start" => $startDate, "end" => $endDate, "status" => $status))->findMany();
    return $query;
}

function delete_holiday_request_by_uid($uid) {
	$query = ORM::forTable("holiday_requests")->where("uid", $uid)->where("status", 1)->deleteMany();
	$query2 = ORM::forTable("holiday_notification")->where("holiday_request_uid", $uid)->where("status", 1)->deleteMany();
	return $query;
	return $query2;
}

function read_holiday_requests_by_emp_uid_date($emp, $date) {
    $query = ORM::forTable("holiday_requests")
	->rawQuery("SELECT * FROM holiday_requests WHERE emp_uid = :emp AND date(start_date) = :dates AND holiday_request_status = 'Approved' AND status = 1", array("emp" => $emp, "dates" => $date))->findOne();
    return $query;
}

function read_overtime_requests_by_emp_uid_date($emp, $date) {
    $query = ORM::forTable("overtime_requests")
	->rawQuery("SELECT * FROM overtime_requests WHERE emp_uid = :emp AND date(start_date) = :dates AND overtime_request_status = 'Approved' AND status = 1", array("emp" => $emp, "dates" => $date))->findOne();
    return $query;
}

/* Holiday Request - Bottom */

/* Temp Overtime Holiday Summary - Top */
//temp_overtime_holiday_summary
//id, uid, emp_uid, ot, rdot, shot, shrdot, rhot, rhrdot, rd, sh, shrd, rh, rhrd, date_created, date_modified, status
function create_temp_overtime_holiday_summary($uid, $emp_uid, $ot, $rdot, $shot, $shrdot, $rhot, $rhrdot, $rd, $sh, $shrd, $rh, $rhrd, $nd) {
	$data = ORM::forTable("temp_overtime_holiday_summary")->create();
		$data->uid = $uid;		
		$data->emp_uid = $emp_uid;
		$data->ot = $ot;
		$data->rdot = $rdot;
		$data->shot = $shot;
		$data->shrdot = $shrdot;
		$data->rhot = $rhot;		
		$data->rhrdot = $rhrdot;
		$data->rd = $rd;
		$data->sh = $sh;
		$data->shrd = $shrd;
		$data->rh = $rh;
		$data->rhrd = $rhrd;
		$data->nd = $nd;
		$data->date_created = date("Y-m-d H:i:s");
		$data->date_modified = date("Y-m-d H:i:s");
		$data->status = 1;
	$data->save();
}

function read_temp_overtime_holiday_summary() {
	$query = ORM::forTable("temp_overtime_holiday_summary")->where("status", 1)->findMany();
    return $query;
}

function read_temp_overtime_holiday_summary_by_uid_emp_uid($uid, $emp_uid) {
	$sql = "SELECT * FROM temp_overtime_holiday_summary WHERE emp_uid = '" . $emp_uid . "' AND uid = '" . $uid . "' AND status = 1";
	$query = ORM::forTable("temp_overtime_holiday_summary")->rawQuery($sql)->findOne();
    return $query;
}

function delete_temp_overtime_holiday_summary($uid) {
	$query = ORM::forTable("temp_overtime_holiday_summary")->where("uid", $uid)->deleteMany();
    return $query;
}
/* Temp Overtime Holiday Summary - Bottom */

/* Work Policy - Top */
# id uid work_days_per_year work_hours_per_day work_hours_start work_hours_end break_hours date_created date_modified status
function create_work_policy($work_days_per_year, $work_hours_per_day, $work_hours_start, $work_hours_end, $break_hours) {
	$data = ORM::forTable("work_policy")->create();
		$data->uid = xguid();		
		$data->work_days_per_year = $work_days_per_year;
		$data->work_hours_per_day = $work_hours_per_day;
		$data->work_hours_start = $work_hours_start;
		$data->work_hours_end = $work_hours_end;
		$data->break_hours = $break_hours;		
		$data->date_created = date("Y-m-d H:i:s");
		$data->date_modified = date("Y-m-d H:i:s");
		$data->status = 1;
	$data->save();
}

function read_work_policy() {
	$query = ORM::forTable("work_policy")->where("status", 1)->findOne();
    return $query;
}

/* Work Policy - Bottom */

/* Get Schedule Type - Top */
function get_schedule_type($date, $emp_uid) {
	$n = read_emp_scheduled_date_emp_uid($date, $emp_uid);
	
	if($n) {
		$work_schedule_uid = $n->work_schedule_uid;
		
		$w = read_work_scheduled_by_uid_one($work_schedule_uid);
		
		if($w) {
			$shift_type_uid = $w->shift_type;
			
			$s = read_schedule_type_limit_one($shift_type_uid);
			
			$shift_type = $s->type;
			
			if($shift_type) {
				return $shift_type;
			}
		}
		else {
			return false;
		}
	}
	else {
		return false;
	}
}
/* Get Schedule Type - Bottom */

/* Get Holiday Type - Top */
function get_holiday_type($date) {
	$sql = "SELECT * FROM holiday as t1 INNER JOIN holiday_types as t2 ON t1.type = t2.holiday_type_uid WHERE t1.date = '".$date."'";
	$query = ORM::forTable("holiday")->rawQuery($sql)->findOne();
    return $query;
}

/* Get Holiday Type - Bottom */

function getHolidayRequestsNotification(){
    $query = ORM::forTable("holiday_notification")->where("request_status", "Pending")->where("status", 1)->count();
    return $query;
}

function  get_leave_request_emp_uid_and_date($id, $date) { //getLeaveRequestsByEmpUidAndDate($id, $date){
    $query = ORM::forTable("leave_requests")
        ->tableAlias("t1")
        ->innerJoin("leaves_types", array("t1.leaves_types_uid", "=", "t2.leaves_types_uid"), "t2")
        ->where("t1.emp_uid", $id)
        ->whereLte("t1.start_date", $date)
        ->whereGte("t1.end_date", $date)
        //->whereNotEqual("t2.leave_code", "AB")
		//->whereNotEqual("t2.leave_code", "W")
		//->whereNotEqual("t2.leave_code", "OT")
        ->where("t1.leave_request_status", "Approved")
        ->where("t1.status", 1)
        ->findOne();
    return $query;
}

function get_absent_emp_uid_and_date($emp_uid, $date) {
	$uid = "75788A87-EAEB-7F75-3C00-7976C72DF742";	
	$query = ORM::forTable("leave_requests")
        ->tableAlias("t1")
        ->innerJoin("leaves_types", array("t1.leaves_types_uid", "=", "t2.leaves_types_uid"), "t2")
        ->where("t1.emp_uid", $emp_uid)
        ->whereLte("t1.start_date", $date)
        ->whereGte("t1.end_date", $date)
        ->where("t1.leave_request_status", "Approved")
		->where("t2.leave_code", "AB")
		->where("t2.leave_code", "W")
        ->where("t1.status", 1)
        ->findOne();
    return $query;
}

function get_nigthdiffstatus() {
	//$myout = array("04:00", "05:00", "04:12", "04:35", "04:12");
	//$myout = array("2017-04-11 04:00", "2017-04-10 17:00", "2017-04-11 05:12", "2017-04-11 04:35", "2017-04-11 03:12");
	
	$mytin = array("2017-05-02 18:34:00", "2017-05-03 18:30:00", "2017-05-04 18:33:00", "2017-05-05 18:54:00", "2017-05-06 09:22:00", "2017-05-07 08:00:00", "2017-05-08 18:35:00", "2017-05-09 18:27:00");
	
	//$mytin = array("2017-04-11 01:00", "2017-04-10 09:00", "2017-04-10 16:00", "2017-04-10 17:00", "2017-04-10 17:00");
	
	$myout = array("2017-05-03 05:05:00", "2017-05-04 08:20:00", "2017-05-05 04:00:00", "2017-05-06 04:00:00", "2017-05-07 16:11:00", "2017-05-07 17:00:00", "2017-05-09 04:06:00", "2017-05-10 07:09:00");
	
	$tin = "";
	$nd = 0;
	/* Night Shift START */
	$nightH = 0;
	$nightDiffStatus = 0;
	
	$i = 0;
	foreach($myout as $result) {	
		//$outHour = $time_out; //$outArray["outHour"];
		$tin = $mytin[$i];
		echo "Start [" . $tin . "] "; // Time In
		
		echo $result . " "; // Time Out
		
		$outHour = date("Y-m-d H:i:s", strtotime($result)); 
		$nightDiffStart = "22:00:00";
		$nightDiffEnd = "06:00:00";
		$nightDiffStarts = date("Y-m-d", strtotime($tin . "-0 day")) . " $nightDiffStart"; 
		
		$afterSix = "18:00:00";
		$nightShifts = date("Y-m-d", strtotime($tin . "-0 day")) . " $afterSix"; 
		
		if( date("Y-m-d", strtotime($outHour)) == date("Y-m-d", strtotime($tin))) {
			$nightDiffEnds = date("Y-m-d", strtotime($tin . "+0 day")) . " $nightDiffEnd";
			echo "+0 ";
		} else {
			$nightDiffEnds = date("Y-m-d", strtotime($tin . "+1 day")) . " $nightDiffEnd";
			echo "+1 ";
		}

		echo $nightDiffStarts . " End [" . $nightDiffEnds . "] ";
		echo $outHour . " ";
		
		if( strtotime($outHour) >= strtotime($nightDiffStarts) && strtotime($outHour) <= strtotime($nightDiffEnds) ) {
			if( strtotime($tin) <= strtotime($nightDiffStarts) ) {
				$nightDiffStatus = workHour($outHour, $nightDiffStarts);
				echo $nightDiffStatus;
				$nd = $nd + $nightDiffStatus;
			}else {
				$nightDiffStatus = workHour($outHour, $tin);
				echo $nightDiffStatus;
				$nd = $nd + $nightDiffStatus;
			}
			echo " ";			
		}else if( strtotime($tin) >= strtotime($nightShifts) ) {
			$nightDiffStatus = workHour($nightDiffEnds, $nightDiffStarts);
			echo $nightDiffStatus;
			$nd = $nd + $nightDiffStatus;
			echo " ";
		}else {
			echo "No! ";
		}
		
		/*if(strtotime($outHour) <= strtotime($nightDiffEnd) && strtotime($outHour) >= strtotime($nightDiffStarts)){
			$nightss = (strtotime($nightDiffEnd) - strtotime($outHour)) / 3600;
			$nightH = 8 - $nightss;
			$nightDiffStatus = 1;
		}else if(strtotime($outHour) >= strtotime($nightDiffEnd) && strtotime($outHour) <= strtotime($nightDiffStarts)){
			$nightss = (strtotime($outHour) - strtotime($nightDiffEnd)) / 3600;
			$nightH = 8 - $nightss;
			$nightDiffStatus = 1;
		}else if(strtotime($outHour) == strtotime($nightDiffEnd)){
			$nightss = (strtotime($outHour) - strtotime($nightDiffEnd)) / 3600;
			$nightH = 8 - $nightss;
			$nightDiffStatus = 1;
		}else if( strtotime($outHour) > strtotime($nightDiffEnd) ) {
			$nightss = (strtotime($outHour) - strtotime($nightDiffEnd)) / 3600;
			$nightH = 8 - $nightss;
			$nightDiffStatus = 1;
		}*/
		
		$nightH = floor($nightH);
		/* Night Shift END */
		
		
		//echo $nd . " " . strtotime($outHour) . " " . strtotime($nightDiffEnd) . " " . $nightDiffStarts . " " . $nightDiffEnds;
		echo $nd;
		echo "</br>";
		
		$i++;
	}
	//echo $nd;
}

function humanTiming($time) {
    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}