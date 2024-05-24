<?php
date_default_timezone_set("Asia/Manila");
# --- MySQL Functions --- #

function addTime($dateTime, $addTime) {
	$sql = "SELECT ADDTIME('".$dateTime."','".$addTime."') as time";
	$query = ORM::forTable("time_log")->rawQuery($sql)->findOne();
	return $query["time"];
}

function tardyMinutes($date, $tardy) {
	//$sql = "SELECT MINUTE(TIMEDIFF(  '".$timeOut."',  '".$timeIn."' )) AS work_hour";
	$sql = "SELECT TIMESTAMPDIFF( MINUTE ,  '".$date."', '".$tardy."' ) AS minutes";
	$query = ORM::forTable("time_log")->rawQuery($sql)->findOne();
	return $query["minutes"];
}

function workHours($timeOut, $timeIn) {
	$sql = "SELECT TIMEDIFF(  '".$timeOut."',  '".$timeIn."' ) AS work_hour";
	$query = ORM::forTable("time_log")->rawQuery($sql)->findOne();
	return $query["work_hour"];
}

function workHour($timeOut, $timeIn) {
	$sql = "SELECT HOUR(TIMEDIFF(  '".$timeOut."',  '".$timeIn."' )) AS work_hour";
	$query = ORM::forTable("time_log")->rawQuery($sql)->findOne();
	return $query["work_hour"];
}

function get_tax_table_amount_by_uid($uid, $basic) {
	$sql = "SELECT * FROM `tax_table` WHERE uid = '$uid' ORDER BY ABS( amount - $basic ) LIMIT 1";
	$query = ORM::forTable("tax_table")->rawQuery($sql)->findOne();
	return $query;
}

function get_philhealth_amount_by_salary($basic) {
	$sql = "SELECT * FROM `hris_philhealth` ORDER BY ABS( salaryRange - $basic ) LIMIT 1";
	$query = ORM::forTable("hris_philhealth")->rawQuery($sql)->findOne();
	return $query["employeeShare"];
}