<?php
date_default_timezone_set("Asia/Manila");
/* Employee */
/* CRUD - Create, Read, Update, Delete */

/* Get Employee Name */
function read_employee_name_by_uid($emp_uid) {
	$data = ORM::forTable("emp")
	->raw_query("SELECT concat(firstname, ' ', SUBSTRING(middlename, 1, 1), '. ', lastname) as name FROM emp WHERE emp_uid = '$emp_uid'")
	->findOne();
	return utf8_decode($data->name);
}

function read_employee_lastname_by_uid($emp_uid) {
	$data = ORM::forTable("emp")
	->raw_query("SELECT concat(lastname, ', ', firstname, ' ', SUBSTRING(middlename, 1, 1), '.') as name FROM emp WHERE emp_uid = '$emp_uid'")
	->findOne();
	return utf8_decode($data->name);
}

function read_employee_name_one_by_uid($emp_uid) {
	$data = ORM::forTable("emp")
	->raw_query("SELECT concat(lastname, ', ', firstname, ' ', middlename) as name FROM emp WHERE emp_uid = '$emp_uid'")
	->findOne();
	return utf8_decode($data->name);
}

function read_employee_lname_by_uid($emp_uid) {
	$data = ORM::forTable("emp")
	->raw_query("SELECT concat(LEFT(firstname, 1), '. ', lastname) as name FROM emp WHERE emp_uid = '$emp_uid'")
	->findOne();
	return utf8_decode($data->name);
}

function get_employee_number_by_emp_uid($emp_uid) {
	$query = ORM::forTable("users")->where("emp_uid", $emp_uid)->findOne();
    return $query->username;
}

/* Get Employee Username */
function read_employee_uid_by_username($username) {
	$data = ORM::forTable("users")
	->raw_query("SELECT t1.emp_uid as uid FROM users as t1 INNER JOIN emp as t2 ON t1.emp_uid = t2.emp_uid WHERE t1.username = '$username' AND t2.status = '1'")
	->findOne();
	
	if($data) {
		return $data->uid;
	}
	else {
		return false;
	}
}

/* Employee Department */
function create_emp_department($department_uid, $position, $emp_uid) {
	# id uid department_uid emp_uid date_created date_modified status
	$data = ORM::forTable("emp_department")->create();
		$data->uid = xguid();
		$data->department_uid = $department_uid;
		$data->designation = $position;
		$data->emp_uid = $emp_uid;
		$data->date_created = date("Y-m-d H:i:s");
		$data->date_modified = date("Y-m-d H:i:s");
		$data->status = 1;
	$data->save();
}

function read_emp_department() {
	$sql = "SELECT * FROM emp as e INNER JOIN emp_department as d ON e.emp_uid = d.emp_uid WHERE e.status = '1' ORDER BY e.lastname ASC";	
	$data = ORM::forTable("emp")->raw_query($sql)->findMany();
	return $data;
}

function read_emp_department_by_uid($emp_uid) {
	$data = ORM::forTable("emp_department")->where("emp_uid", $emp_uid)->findMany();
	return $data;
}

function read_emp_department_by_department_uid($uid) {
	$data = ORM::forTable("emp_department")->where("department_uid", $uid)->findMany();
	return $data;
}

function read_employee_department_by_uid($uid) {
	$query = ORM::forTable("emp_department")->tableAlias("t1")->join("department", array("t1.department_uid", "=", "t2.uid") ,"t2")->where("t1.emp_uid", $uid)->findOne();
    return $query;
}

function update_emp_department($uid, $department_uid, $position, $status) {
	$data = ORM::forTable("emp_department")
		->where("uid", $uid)
		->findOne();
    $data->set("department_uid", $department_uid);
	$data->set("designation", $position);
    $data->set("date_modified", date("Y-m-d H:i:s"));
	$data->set("status", $status);
    $data->save();
}

function delete_emp_department($uid) {
	$data = ORM::forTable("emp_department")->where("uid", $uid)->findOne();
	$data->set("status", 0);
    $data->save();
}

function get_emp_name_uid($uid)
{
	$data = ORM::forTable("emp")->where("emp_uid",$uid)->findOne();
	return $data;
}

/* Get Employee Salary - Top */
function get_employee_salary($emp_uid, $type) {
	$get_salary = getSalaryByUid($emp_uid);	
	if($get_salary) {
		switch($type) {
			case "base_salary":
				return $get_salary->base_salary;
				break;
			case "pay_period":
				return $get_salary->pay_period_name;
				break;
		}
	}
}
/* Get Employee Salary - Bottom */
