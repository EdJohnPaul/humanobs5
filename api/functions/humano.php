<?php



// START ADDED MARCH 28, 2017
$vendorDir = dirname(dirname(__FILE__)) . "/vendor/"; //print $vendorDir;
$baseDir = dirname($vendorDir);
require $vendorDir . 'PHPMailer/PHPMailerAutoload.php';

//END ADDED MARCH 28, 2017
/* Humano */
/* CRUD - Create, Read, Update, Delete */

/* News Feed */
function create_news_feed($uid, $user_uid, $content, $date_created, $date_modified) {
	$data = ORM::forTable("news_feed")->create();
		$data->uid = $uid;
		$data->user_uid = $user_uid;
		$data->content = $content;
		$data->date_created = $date_created;
		$data->date_modified = $date_modified;
		$data->status = 1;
	$data->save();
}

function read_news_feed() {
	$data = ORM::forTable("news_feed")->where("status", 1)->orderByDesc("date_created")->findMany();
	return $data;
}

function read_news_feed_all() {
	$data = ORM::forTable("news_feed")->findMany();
	return $data;
}

function read_news_feed_by_uid($uid) {
	$data = ORM::forTable("news_feed")->where("uid", $uid)->findMany();
	return $data;
}

function update_news_feed($uid, $content, $date_modified) {
	$data = ORM::forTable("news_feed")->where("uid", $uid)->findResultSet();
	$data->set("content", $content);
    $data->set("date_modified", $date_modified);
    $data->save();
}

function delete_news_feed($uid) {
	$data = ORM::forTable("news_feed")->where("uid", $uid)->findOne();
	$data->set("status", 0);
    $data->save();
}

/* New Applicant */
function create_applicants($uid, $name, $course, $school, $address, $contact_no, $email_address, $type, $files, $application_status, $date_created, $date_modified) {
	$data = ORM::forTable("applicants")->create();
		$data->uid = $uid;
		$data->name = $name;
		$data->course = $course;		
		$data->school = $school;
		$data->address = $address;
		$data->contact_no = $contact_no;
		$data->email_address = $email_address;		
		$data->type = $type;
		$data->files = $files;
		$data->application_status = $application_status;		
		$data->date_created = $date_created;
		$data->date_modified = $date_modified;
		$data->status = 1;
	$data->save();
}

function read_applicants() {
	$data = ORM::forTable("applicants")->where("status", 1)->findMany();
	return $data;
}

function read_applicants_by_desc() {
	$data = ORM::forTable("applicants")->order_by_desc("date_created")->where("status", 1)->findMany();
	return $data;
}

function read_applicants_by_uid($uid) {
	$data = ORM::forTable("applicants")->where("uid", $uid)->findMany();
	return $data;
}

function update_applicants($uid, $name, $course, $school, $address, $contact_no, $email_address, $type, $application_status, $status) {
	$data = ORM::forTable("applicants")->where("uid", $uid)->findResultSet();
	$data->set("name", $name);
	$data->set("course", $course);
	$data->set("school", $school);
	$data->set("address", $address);
	$data->set("contact_no", $contact_no);
	$data->set("email_address", $email_address);
	$data->set("type", $type);
	$data->set("application_status", $application_status);	
    $data->set("date_modified", date("Y-m-d H:i:s"));
	$data->set("status", $status);
    $data->save();
}

function delete_applicants($uid) {
	$data = ORM::forTable("applicants")->where("uid", $uid)->findResultSet();
	$data->set("status", 0);
    $data->save();
}

/* Loans */
function create_loans($name) {
	# id, uid, name, date_created, date_modified, status
	$data = ORM::forTable("loans")->create();
		$data->uid = xguid();
		$data->name = $name;		
		$data->date_created = date("Y-m-d H:i:s");
		$data->date_modified = date("Y-m-d H:i:s");
		$data->status = 1;
	$data->save();
}

function read_loans_by_name_desc() {
	$data = ORM::forTable("loans")->order_by_desc("name")->where("status", 1)->findMany();
	return $data;
}

function update_loans($uid, $name, $status) {
	$data = ORM::forTable("loans")->where("uid", $uid)->findResultSet();
	$data->set("name", $name);	
    $data->set("date_modified", date("Y-m-d H:i:s"));
	$data->set("status", $status);
    $data->save();
}

/* Employee Loans */
function create_emp_loans($emp_uid, $loan_uid, $amount, $interest_rate, $no_of_payment) {
	# id, uid, emp_uid, loan_uid, amount, interest_rate, no_of_payment, date_created, date_modified, status
	$data = ORM::forTable("emp_loans")->create();
		$data->uid = xguid();
		$data->emp_uid = $emp_uid;
		$data->loan_uid = $loan_uid;
		$data->amount = $amount;
		$data->interest_rate = $interest_rate;
		$data->no_of_payment = $no_of_payment;
		$data->date_created = date("Y-m-d H:i:s");
		$data->date_modified = date("Y-m-d H:i:s");
		$data->status = 1;
	$data->save();
}

function read_emp_loans_by_name_desc() {
	$data = ORM::forTable("emp_loans")->order_by_desc("date_created")->where("status", 1)->findMany();
	return $data;
}

function update_emp_loans($uid, $amount, $interest_rate, $no_of_payment, $status) {
	$data = ORM::forTable("emp_loans")->where("uid", $uid)->findResultSet();
	$data->set("amount", $amount);	
	$data->set("interest_rate", $interest_rate);
	$data->set("no_of_payment", $no_of_payment);
    $data->set("date_modified", date("Y-m-d H:i:s"));
	$data->set("status", $status);
    $data->save();
}

/* Employee Loans Payment */
// function create_emp_loans_payments($emp_loan_uid, $reference, $amount_paid, $remaining_no_of_payment) {
// 	# id, uid, emp_loan_uid, reference, amount_paid, remaining_no_of_payment, date_created, date_modified, status
// 	$data = ORM::forTable("emp_loans_payments")->create();
// 		$data->uid = xguid();
// 		$data->emp_loan_uid = $emp_loan_uid;
// 		$data->reference = $reference;
// 		$data->amount_paid = $amount_paid;
// 		$data->remaining_no_of_payment = $remaining_no_of_payment;
// 		$data->date_created = date("Y-m-d H:i:s");
// 		$data->date_modified = date("Y-m-d H:i:s");
// 		$data->status = 1;
// 	$data->save();
// }

function read_emp_loans_payment_by_date_desc() {
	$data = ORM::forTable("emp_loans_payments")->order_by_desc("date_created")->where("status", 1)->findMany();
	return $data;
}

function update_emp_loans_payments($uid, $emp_loan_uid, $reference, $amount_paid, $remaining_no_of_payment, $status) {
	$data = ORM::forTable("emp_loans_payments")->where("uid", $uid)->findResultSet();
	$data->set("emp_loan_uid", $emp_loan_uid);
	$data->set("reference", $reference);
	$data->set("amount_paid", $amount_paid);
	$data->set("remaining_no_of_payment", $remaining_no_of_payment);
    $data->set("date_modified", date("Y-m-d H:i:s"));
	$data->set("status", $status);
    $data->save();
}

function delete_emp_loans_payments($uid) {
	$data = ORM::forTable("emp_loans_payments")->where("uid", $uid)->findResultSet();
	$data->set("date_modified", date("Y-m-d H:i:s"));
	$data->set("status", 0);
    $data->save();
}

/* Department */
# id, uid, group, date_created, date_modified, status
function create_department($group) {
	$data = ORM::forTable("department")->create();
		$data->uid = xguid();
		$data->group = $group;
		$data->date_created = date("Y-m-d H:i:s");
		$data->date_modified = date("Y-m-d H:i:s");
		$data->status = 1;
	$data->save();
}

function read_department() {
	$data = ORM::forTable("department")->where("status", 1)->order_by_asc("group")->findMany();
	return $data;
}

function read_department_all() {
	$data = ORM::forTable("department")->order_by_asc("group")->findMany();
	return $data;
}

function read_department_by_uid_one($uid) {
	$data = ORM::forTable("department")->where("uid", $uid)->findOne();
	return $data;
}

function read_department_by_uid($uid) {
	$data = ORM::forTable("department")->where("uid", $uid)->where("status", 1)->findMany();
	return $data;
}

function update_department($uid, $group, $status) {
	$data = ORM::forTable("department")
		->where("uid", $uid)
		->findOne();
    $data->set("group", $group);
    $data->set("date_modified", date("Y-m-d H:i:s"));
	$data->set("status", $status);
    $data->save();
}

function delete_department($uid) {
	$data = ORM::forTable("department")->where("uid", $uid)->findOne();
	$data->set("status", 0);
    $data->save();
}

/* VALIDATION - Top */
function validateApplicants($uid) {	
	$valid = false;
	$data = ORM::forTable("applicants")->where("uid", $uid)->count();
	if($data == 1) {
		$valid = true;
	}
	return $valid;
}

function validateApplicantsName($name) {	
	$valid = false;
	$data = ORM::forTable("applicants")->where("name", $name)->count();
	if($data == 1) {
		$valid = true;
	}
	return $data;
}

# Validate Department
function validateDepartmentName($name) {	
	$valid = false;
	$data = ORM::forTable("department")->where("group", $name)->count();
	if($data == 1) {
		$valid = true;
	}
	return $data;
}

function validateDepartment($uid) {	
	$valid = false;
	$data = ORM::forTable("department")->where("uid", $uid)->count();
	if($data == 1) {
		$valid = true;
	}
	return $valid;
}
/* VALIDATION - Bottom */

/* START LOANS */
function create_employee_loans($empLoanUid, $empUid, $loanUid, $amount, $numberPayment, $requestStatus, $dateCreated, $dateModified) {
	$data = ORM::forTable("emp_loans")->create();
		$data->uid = $empLoanUid;
		$data->emp_uid = $empUid;
		$data->loan_uid = $loanUid;
		$data->amount = $amount;
		$data->no_of_payment = $numberPayment;
		$data->request_status = $requestStatus;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function check_emp_loans($empUid){
    $query = ORM::forTable("emp_loans")->where("emp_uid", $empUid)->where("status", 1)->count();
    return $query;
}

function read_emp_loans() {
	# uid emp_uid loan_uid amount interest_rate no_of_payment date_created date_modified status id
	$data = ORM::forTable("emp_loans")->where("status", 1)->findMany();
	return $data;
}

function read_type_loans(){
	$data = ORM::forTable("loans")->order_by_asc("name")->findMany();
	return $data;
}

function read_type_loans_all(){
	$data = ORM::forTable("loans")->where("status", 1)->findMany();
	return $data;
}

function create_loantype($loanTypeUid, $name, $dateCreated, $dateModified) {
	$data = ORM::forTable("loans")->create();
		$data->uid = $loanTypeUid;
		$data->name = $name;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function read_loans_uid($uid) {
	$data = ORM::forTable("emp_loans")->where("uid", $uid)->findMany();
	return $data;
}

function edit_loans($uid, $empUid, $loanUid, $amount, $interestRate, $numberPayment, $dateModified){
    $data = ORM::forTable("emp_loans")->where("uid", $uid)->findResultSet();
        $data->set("emp_uid", $empUid);
        $data->set("loan_uid", $loanUid);
        $data->set("amount", $amount);
        $data->set("interest_rate", $interestRate);
        $data->set("no_of_payment", $numberPayment);
        $data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

function read_loan_by_uid($uid) {
	$data = ORM::forTable("loans")->where("uid", $uid)->findOne();
	return $data;
}

function read_emp_by_uid($uid) {
	$data = ORM::forTable("emp")->where("emp_uid", $uid)->findOne();
	return $data;
}

function edit_type_loans($uid, $name, $dateModified){
    $data = ORM::forTable("loans")->where("uid", $uid)->findResultSet();
        $data->set("name", $name);
        $data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}
/*END LOANS*/

/*START DEPARTMENT*/
function read_departments(){
	$data = ORM::forTable("department")->order_by_asc("group")->findMany();
	return $data;
}

function read_dep_by_uid($uid) {
	$data = ORM::forTable("department")->where("uid", $uid)->findOne();
	return $data;
}

function read_cert_type_by_uid($uid) {
	$data = ORM::forTable("certificate_types")->where("uid", $uid)->findOne();
	return $data;
}

function read_memo_types_by_uid($uid) {
	$data = ORM::forTable("memo_type")->where("uid", $uid)->findMany();	
	return $data;
}

/*END DEPARTMENT*/


/*START TOPIC*/

function read_topics() {
	$data = ORM::forTable("training_topics")->where("status", 1)->findMany();
	return $data;
}

function read_topic_by_uid($uid) {
	$query = ORM::forTable("training_topics")->where("uid", $uid)->findOne();
	return $query;
}

function read_results_by_subtopic($topicUid) {
	$query = ORM::forTable("training_rating")->where("topic_uid", $topicUid)->findMany();
	return $query;
}


function create_topics($topicUid, $topic, $dateCreated, $dateModified) {
	$data = ORM::forTable("training_topics")->create();
		$data->uid = $topicUid;
		$data->name = $topic;
		// $data->categories = $categories;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function create_category($categoryUid, $name, $dateCreated, $dateModified) {
	$data = ORM::forTable("training_category")->create();
		$data->uid = $categoryUid;
		// $data->topic_uid = $topicUid;
		$data->name = $name;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function read_category(){
	$data = ORM::forTable("training_category")->order_by_asc("name")->findMany();
	return $data;
}

function update_topics($uid, $name, $dateModified){
    $data = ORM::forTable("training_topics")->where("uid", $uid)->findResultSet();
        $data->set("name", $name);
        $data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

function delete_topic($uid, $status) {
    $query = ORM::forTable("training_topics")->where("uid", $uid)->findOne();
    $query->set("status", 0);
    $query->save();
}

/*END TOPIC*/

/*START SUBTOPIC*/
function read_subtopic_by_uid($uid) {
	$query = ORM::forTable("training_subtopics")->where("uid", $uid)->findOne();
	return $query;
}

function read_training_subtopics() {
	$data = ORM::forTable("training_subtopics")->where("status", 1)->findMany();
	return $data;
}

function read_training_subtopics_by_uid($uid) {
	$data = ORM::forTable("training_subtopics")
		->where("topic_uid", $uid)
		->where("status", 1)->findMany();
	return $data;
}

function create_training_subtopics($subtopicUid, $subtopic, $topic, $dateCreated, $dateModified) {
	$data = ORM::forTable("training_subtopics")->create();
		$data->uid = $subtopicUid;
		$data->name = $subtopic;
		$data->topic_uid = $topic;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function update_subtopic($uid, $name, $dateModified){
    $data = ORM::forTable("training_subtopics")->where("uid", $uid)->findResultSet();
        $data->set("name", $name);
        $data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

function read_subtopics_by_topic($topicUid){
	$data = ORM::forTable("training_subtopics")->where("topic_uid", $topicUid)->order_by_asc("date_created")->findMany();
	return $data;
}

function delete_subtopic($uid, $status) {
    $query = ORM::forTable("training_subtopics")->where("uid", $uid)->findOne();
    $query->set("status", 0);
    $query->save();
}
/*END SUBTOPIC*/

/*START CURRICULUM*/
function read_curriculum() {
	$data = ORM::forTable("training_curriculum")->where("status", 1)->findMany();
	return $data;
}

function read_topic_all(){
	$data = ORM::forTable("training_topics")->order_by_asc("name")->findMany();
	return $data;
}

function create_curriculum($curriculumUid, $name, $departmentUid, $dateCreated, $dateModified) {
	$data = ORM::forTable("training_curriculum")->create();
		$data->uid = $curriculumUid;
		$data->name = $name;
		$data->department_uid = $departmentUid;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function read_curriculum_all(){
	$data = ORM::forTable("training_curriculum")->order_by_asc("name")->findMany();
	return $data;
}

function update_curriculum($uid, $name, $department, $dateModified){
    $data = ORM::forTable("training_curriculum")->where("uid", $uid)->findResultSet();
        $data->set("name", $name);
        $data->set("department_uid", $department);
        $data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

function curriculum_by_uid($uid){
    $query = ORM::forTable("training_curriculum")->where("uid", $uid)->findOne();
    return $query;
}


/*END CURRICULUM*/

function read_examinee() {
	$data = ORM::forTable("training_examinee")->where("status", 1)->findMany();
	return $data;
}

function create_examinee($examineeUid, $employeeUid, $curriculumUid, $endDate, $dateCreated, $dateModified) {
	$data = ORM::forTable("training_examinee")->create();
		$data->uid = $examineeUid;
		$data->emp_uid = $employeeUid;
		$data->curriculum_uid = $curriculumUid;
		$data->end_date = $endDate;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function update_examinee($uid, $employee, $curriculum, $endDate, $dateModified){
    $data = ORM::forTable("training_examinee")->where("uid", $uid)->findResultSet();
        $data->set("emp_uid", $employee);
        $data->set("curriculum_uid", $curriculum);
        $data->set("end_date", $endDate);
        $data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

function read_examinee_uid($uid) {
	$data = ORM::forTable("training_examinee")->where("uid", $uid)->findMany();
	return $data;
}

function read_curriculum_uid($uid) {
	$data = ORM::forTable("training_curriculum")->where("uid", $uid)->findMany();
	return $data;
}

function read_curriculum_by_user($examUid){
	$data = ORM::forTable("training_topics")->where("curriculum_uid", $examUid)->order_by_asc("date_created")->findMany();
	return $data;
}

function delete_curriculum($uid, $status) {
    $query = ORM::forTable("training_curriculum")->where("uid", $uid)->findOne();
    $query->set("status", 0);
    $query->save();
}

// function read_examinee_by_uid($uid) {
// 	$query = ORM::forTable("training_examinee")->where("uid", $uid)->findOne();
// 	return $query;
// }

function read_examinee_by_uid($uid) {
	$data = ORM::forTable("training_examinee")
		->where("uid", $uid)
		->where("status", 1)->findOne();
	return $data;
}

function read_rating_by_examinee($examineeUid){
	$data = ORM::forTable("training_rating")->where("examinee_uid", $examineeUid)->order_by_asc("date_created")->findMany();
	return $data;
}

function read_trainee_by_uid($traineeUid){
	$query = ORM::forTable("training_examinee")->where("emp_uid", $traineeUid)->findMany();
	return $query;
}

function read_curr_by_uid($uid) {
	$data = ORM::forTable("training_curriculum")->where("uid", $uid)->findOne();
	return $data;
}

function create_training_topics($topicUid, $topic, $curriculumUid, $dateCreated, $dateModified) {
	$data = ORM::forTable("training_topics")->create();
		$data->uid = $topicUid;
		$data->name = $topic;
		$data->curriculum_uid = $curriculumUid;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function read_training_topics() {
	$data = ORM::forTable("training_topics")->where("status", 1)->findMany();
	return $data;
}

function read_training_topics_by_uid($uid) {
	$data = ORM::forTable("training_topics")
		->where("curriculum_uid", $uid)
		->where("status", 1)->findMany();
	return $data;
}

function examinee_by_uid($uid){
    $query = ORM::forTable("training_examinee")->where("uid", $uid)->findOne();
    return $query;
}

/*START CONTENTS*/

function read_training_contents_by_uid($uid) {
	$data = ORM::forTable("training_content")
		->where("curriculum_uid", $uid)
		->where("status", 1)->findMany();
	return $data;
}

function create_training_content($contentUid, $name, $content, $curriculum, $type, $choices, $answer, $points, $dateCreated, $dateModified) {
	$data = ORM::forTable("training_content")->create();
		$data->uid = $contentUid;
		$data->name = $name;
		$data->content = $content;
		$data->curriculum_uid = $curriculum;
		$data->type = $type;
		$data->choices = $choices;
		$data->answer = $answer;
		$data->points = $points;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function read_content_by_uid($uid) {
	$query = ORM::forTable("training_content")->where("uid", $uid)->findOne();
	return $query;
}

function delete_content($uid, $status) {
    $query = ORM::forTable("training_content")->where("uid", $uid)->findOne();
    $query->set("status", 0);
    $query->save();
}

/*END CONTENT*/

/* START RATING*/
function create_training_rating($ratingUid, $rating, $percentage, $insUid, $examinee, $content, $examineeUid,$dateCreated, $dateModified) {
	$data = ORM::forTable("training_rating")->create();
		$data->uid = $ratingUid;
		$data->percentage = $percentage;
		$data->rating = $rating;
		$data->curriculum_uid = $insUid;
		$data->emp_uid = $examinee;
		$data->content_uid = $content;
		$data->examinee_uid = $examineeUid;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function read_sample_by_uid($uid){
	$query = ORM::forTable("training_examinee")->where("uid", $uid)->findManys();
	return $query;
}

function read_rating_by_uid($examUid){
	$query = ORM::forTable("training_rating")->where("examinee_uid", $uid)->findManys();
	return $query;
}

/*END RATING*/

/*START CERTIFICATE*/
function create_certificate_type($certificateUid, $name, $dateCreated, $dateModified) {
	$data = ORM::forTable("certificate_types")->create();
		$data->uid = $certificateUid;
		$data->name = $name;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function read_certificate_type() {
	$data = ORM::forTable("certificate_types")->where("status", 1)->findMany();
	return $data;
}

function read_certificate_type_by_uid($uid) {
	$data = ORM::forTable("certificate_types")
		->where("uid", $uid)
		->where("status", 1)->findMany();
	return $data;
}


function update_certificate_type($uid, $name, $dateModified){
    $data = ORM::forTable("certificate_types")->where("uid", $uid)->findResultSet();
        $data->set("name", $name);
        $data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

function create_certificate($certificateUid, $employeeUid, $departmentUid, $type, $dateCreated, $dateModified) {
	$data = ORM::forTable("certificates")->create();
		$data->uid = $certificateUid;
		$data->emp_uid = $employeeUid;
		$data->department_uid = $departmentUid;
		$data->type = $type;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function read_certificates() {
	$data = ORM::forTable("certificates")->where("status", 1)->findMany();
	return $data;
}

function read_certificates_by_user($userUid) {
	$data = ORM::forTable("certificates")->where("emp_uid", $userUid)->findMany();
	return $data;
}

function read_certificate_uid($uid) {
	$query = ORM::forTable("certificates")->where("uid", $uid)->findMany();
	return $query;
}

function read_certificate_all(){
	$data = ORM::forTable("certificate_types")->order_by_asc("name")->findMany();
	return $data;
}

function update_certificates($uid, $employee, $department, $type, $dateModified) {
	$data = ORM::forTable("certificates")->where("uid", $uid)->findResultSet();
	$data->set("emp_uid", $employee);	
	$data->set("department_uid", $department);
	$data->set("type", $type);
    $data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

/*END CERTIFICATE*/

/*START MEMO*/
function create_memo_type($memoUid, $name, $dateCreated, $dateModified) {
	$data = ORM::forTable("memo_type")->create();
		$data->uid = $memoUid;
		$data->name = $name;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function read_memo_type() {
	$data = ORM::forTable("memo_type")->where("status", 1)->findMany();
	return $data;
}

function update_memo($uid, $employee, $department, $type, $dateModified) {
	$data = ORM::forTable("memos")->where("uid", $uid)->findResultSet();
	$data->set("emp_uid", $employee);	
	$data->set("department_uid", $department);
	$data->set("type", $type);
    $data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

function update_memo_type($uid, $name, $dateModified){
    $data = ORM::forTable("memo_type")->where("uid", $uid)->findResultSet();
        $data->set("name", $name);
        $data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

function read_memo() {
	$data = ORM::forTable("memo_type")->where("status", 1)->findMany();
	return $data;
}

function create_memo($memoUid, $employeeUid, $departmentUid, $type, $dateCreated, $dateModified) {
	$data = ORM::forTable("memos")->create();
		$data->uid = $memoUid;
		$data->emp_uid = $employeeUid;
		$data->department_uid = $departmentUid;
		$data->type = $type;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function read_memo_all() {
	$data = ORM::forTable("memos")->where("status", 1)->findMany();
	return $data;
}

function read_memos_by_user($userUid) {
	$data = ORM::forTable("memos")->where("emp_uid", $userUid)->findMany();
	return $data;
}

/*END MEMO*/

function create_questionnaire($questionnaireUid, $name, $dateCreated, $dateModified) {
	$data = ORM::forTable("evaluation_type")->create();
		$data->uid = $questionnaireUid;
		$data->name = $name;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function read_questionnaire_all() {
	$data = ORM::forTable("evaluation_type")->where("status", 1)->findMany();
	return $data;
}


function evaluation_results($uid, $employee, $evaluator, $question, $answer, $questionType, $dateCreated, $dateModified, $effectiveDate) {
	$data = ORM::forTable("evaluation_results")->create();
		$data->uid = $uid;
		$data->emp_uid = $employee;
		$data->evaluator_uid = $evaluator;
		$data->question_uid = $question;
		$data->result = $answer;
		$data->type_uid = $questionType;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->next_evaluation = $effectiveDate;
		$data->status = 1;
	$data->save();
}


function create_question($questionUid, $title, $question, $type, $typeUid, $dateCreated, $dateModified) {
	$data = ORM::forTable("evaluation_questions")->create();
		$data->uid = $questionUid;
		$data->name = $title;
		$data->question = $question;
		$data->type = $type;
		$data->type_uid = $typeUid;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function read_questions_by_uid($uid) {
	$data = ORM::forTable("evaluation_questions")
		->where("type_uid", $uid)
		->where("status", 1)->findMany();
	return $data;
}

function read_evaluation_by_employee_date($empUid, $date, $typeUid, $evalUid) {
	$data = ORM::forTable("evaluation_results")
		->where("emp_uid", $empUid)
		->where("date_created", $date)
		->where("type_uid", $typeUid)
		->where("evaluator_uid", $evalUid)->findMany();
	return $data;
}

function read_eval_type_by_uid($uid) {
	$data = ORM::forTable("evaluation_type")->where("uid", $uid)->findOne();
	return $data;
}

function read_question_by_uid($uid) {
	$data = ORM::forTable("evaluation_questions")->where("uid", $uid)->findOne();
	return $data;
}

function read_evaluation_employee_all() {
	$data = ORM::forTable("emp")->where("status", 1)->findMany();
	return $data;
}


function read_history_by_employee($empUid) {
	$data = ORM::forTable("evaluation_results")
		->where("emp_uid", $empUid)
		->where("status", 1)->findMany();
	return $data;
}

function read_history_by_uid($uid) {
	$data = ORM::forTable("evaluation_results")
		->where("uid", $uid)
		->where("status", 1)->findMany();
	return $data;
}

function delete_questionnaire($uid, $status) {
    $query = ORM::forTable("evaluation_type")->where("uid", $uid)->findOne();
    $query->set("status", 0);
    $query->save();
}

function delete_questions($uid, $status) {
    $query = ORM::forTable("evaluation_questions")->where("uid", $uid)->findOne();
    $query->set("status", 0);
    $query->save();
}


function delete_loan_types($uid, $status) {
    $query = ORM::forTable("loans")->where("uid", $uid)->findOne();
    $query->set("status", 0);
    $query->save();
}


function delete_company_loans($uid, $status) {
    $query = ORM::forTable("emp_loans")->where("uid", $uid)->findOne();
    $query->set("status", 0);
    $query->save();
}

function delete_certificate_setting($uid, $status) {
    $query = ORM::forTable("certificate_types")->where("uid", $uid)->findOne();
    $query->set("status", 0);
    $query->save();
}

function delete_memo_setting($uid, $status) {
    $query = ORM::forTable("memo_type")->where("uid", $uid)->findOne();
    $query->set("status", 0);
    $query->save();
}

function delete_memo($uid, $status) {
    $query = ORM::forTable("memos")->where("uid", $uid)->findOne();
    $query->set("status", 0);
    $query->save();
}

function create_knowledge_based_question($questionUid, $question, $answer, $dateCreated, $dateModified) {
	$data = ORM::forTable("knowledge_questions")->create();
		$data->uid = $questionUid;
		$data->question = $question;
		$data->answer = $answer;		
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function read_knowledge_based_all() {
	$data = ORM::forTable("knowledge_questions")->where("status", 1)->findMany();
	return $data;
}

function read_knowledge_based_by_uid($uid) {
	$data = ORM::forTable("knowledge_questions")->where("uid", $uid)->findOne();
	return $data;
}

function update_knowledge($uid, $question, $answer, $dateModified) {
	$data = ORM::forTable("knowledge_questions")->where("uid", $uid)->findResultSet();
	$data->set("question", $question);	
	$data->set("answer", $answer);
    $data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

function delete_knowledge_based($uid, $status) {
    $query = ORM::forTable("knowledge_questions")->where("uid", $uid)->findOne();
    $query->set("status", 0);
    $query->save();
}


function create_emp_request($requestUid, $request, $reason, $empuid, $dateCreated, $dateModified) {
	$data = ORM::forTable("emp_requests")->create();
		$data->uid = $requestUid;
		$data->request_uid = $request;
		$data->reason = $reason;	
		$data->request_status = "Delivered";
		$data->emp_uid = $empuid;	
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function read_requests_by_user($userUid) {
	$data = ORM::forTable("emp_requests")->where("emp_uid", $userUid)->where("status", 1)->findMany();
	return $data;
}

function read_request_by_uid($uid) {
	$data = ORM::forTable("emp_requests")->where("uid", $uid)->findMany();
	return $data;
}

function update_emp_request($uid, $request, $reason, $dateModified) {
	$data = ORM::forTable("emp_requests")->where("uid", $uid)->findResultSet();
	$data->set("request_uid", $request);	
	$data->set("reason", $reason);
    $data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

function delete_emp_request($uid, $status) {
    $query = ORM::forTable("emp_requests")->where("uid", $uid)->findOne();
    $query->set("status", 0);
    $query->save();
}

function read_emp_request_all() {
	$data = ORM::forTable("emp_requests")->where("status", 1)->findMany();
	return $data;
}

//START EDITED MARCH 30, 2017
function admin_emp_request($uid, $requestStatus, $dateModified){
    $query = ORM::forTable("emp_requests")->where("uid", $uid)->findOne();
    $query->set("request_status", $requestStatus);
    $query->set("date_modified", date("Y-m-d H:i:s"));
    $query->save();
}
//END EDITED MARCH 30, 2017

function read_loans_by_user($userUid) {
	$data = ORM::forTable("emp_loans")->where("emp_uid", $userUid)->where("status", 1)->findMany();
	return $data;
}

function create_instruction($instructionUid, $title, $instruction, $curriculum, $dateCreated, $dateModified) {
	$data = ORM::forTable("training_instruction")->create();
		$data->uid = $instructionUid;
		$data->title = $title;
		$data->instruction = $instruction;
		$data->curriculum_uid = $curriculum;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function read_instruction_one($currUid) {
	$data = ORM::forTable("training_instruction")->where("curriculum_uid", $currUid)->where("status", 1)->findMany();
	return $data;
}

function read_instruction_uid($uid) {
	$data = ORM::forTable("training_instruction")->where("uid", $uid)->findMany();
	return $data;
}

function update_instruction($uid, $title, $instruction, $dateModified){
    $data = ORM::forTable("training_instruction")->where("uid", $uid)->findResultSet();
        $data->set("title", $title);
        $data->set("instruction", $instruction);
        $data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

function delete_instruction($uid, $status) {
    $query = ORM::forTable("training_instruction")->where("uid", $uid)->findOne();
    $query->set("status", 0);
    $query->save();
}

function read_examination_one($currUid) {
	$data = ORM::forTable("training_content")->where("curriculum_uid", $currUid)->where("status", 1)->findMany();
	return $data;
}

function read_rating_one($examineeUid) {
	$data = ORM::forTable("training_rating")->where("examinee_uid", $examineeUid)->where("status", 1)->findMany();
	return $data;
}

function read_instruction_preview($uid) {
	$data = ORM::forTable("training_instruction")->where("uid", $uid)->where("status", 1)->findMany();
	return $data;
}

function update_with_exam($curriculum, $withExam) {
    $query = ORM::forTable("training_curriculum")->where("uid", $curriculum)->findOne();
    $query->set("with_exam", 1);
    $query->save();
}

function read_curriculum_one($uid) {
	$data = ORM::forTable("training_curriculum")->where("uid", $uid)->where("status", 1)->findMany();
	return $data;
}

function create_request_type($requestUid, $request, $dateCreated, $dateModified) {
	$data = ORM::forTable("requests")->create();
		$data->uid = $requestUid;                               
		$data->name = $request;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function read_requests_all() {
	$data = ORM::forTable("requests")->where("status", 1)->findMany();
	return $data;
}


function update_request_type($uid, $request, $dateModified) {
	$data = ORM::forTable("requests")->where("uid", $uid)->findResultSet();
	$data->set("name", $request);	
    $data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

function delete_request_type($uid, $status) {
    $query = ORM::forTable("requests")->where("uid", $uid)->findOne();
    $query->set("status", 0);
    $query->save();
}

function read_type_requests(){
	$data = ORM::forTable("requests")->where("status", 1)->findMany();
	return $data;
}

function read_request_type_by_uid($uid) {
	$data = ORM::forTable("requests")->where("uid", $uid)->findOne();
	return $data;
}

function admin_emp_loan($uid, $requestStatus){
    $query = ORM::forTable("emp_loans")->where("uid", $uid)->findOne();
    $query->set("request_status", $requestStatus);
    $query->set("date_modified", date("Y-m-d H:i:s"));
    $query->save();
}

function delete_certificate($uid, $status) {
    $query = ORM::forTable("certificates")->where("uid", $uid)->findOne();
    $query->set("status", 0);
    $query->save();
}

function read_certificates_by_uid($uid) {
	$data = ORM::forTable("certificates")->where("uid", $uid)->findMany();
	return $data;
}

function read_latest_evaluation_by_uid($empUid) {
	$data = ORM::forTable("evaluation_results")->where("emp_uid", $empUid)->order_by_asc("date_created")->findOne();
	return $data;
}

function getUpcomingEvaluations($from, $to){
    $query = ORM::forTable("evaluation_results")
    ->rawQuery("SELECT * FROM evaluation_results WHERE status = '1' AND DATE_FORMAT (next_evaluation, '%m-%dd-%yyyy') BETWEEN :from AND :to ORDER BY DATE_FORMAT (next_evaluation, '%m-%dd-%yyyy') ASC", array("from" => $from, "to" => $to))
    ->findMany();
    return $query;
}


function get_next_instruction($uid, $curUid){
	$query = ORM::forTable("training_instruction")
	->rawQuery("SELECT uid, title FROM training_instruction WHERE status = '1' AND date_created > (SELECT date_created FROM training_instruction WHERE uid = :uid) AND curriculum_uid = :curUid ORDER BY date_created ASC", array("uid" => $uid, "curUid" => $curUid))
	->findOne();
	return $query;
}

function get_previous_instruction($uid, $curUid){
	$query = ORM::forTable("training_instruction")
	->rawQuery("SELECT uid, title FROM training_instruction WHERE status = '1' AND date_created < (SELECT date_created FROM training_instruction WHERE uid = :uid) AND curriculum_uid = :curUid ORDER BY date_created DESC", array("uid" => $uid, "curUid" => $curUid))
	->findOne();
	return $query;
}

function create_documents($uid, $empUid, $description, $files, $date_created, $date_modified) {
	$data = ORM::forTable("documents")->create();
		$data->uid = $uid;
		$data->emp_uid = $empUid;
		$data->description = $description;		
		$data->files = $files;
		$data->date_created = $date_created;
		$data->date_modified = $date_modified;
		$data->status = 1;
	$data->save();
}

function read_documents_by_user($userUid) {
	$data = ORM::forTable("documents")->where("emp_uid", $userUid)->where("status", 1)->findMany();
	return $data;
}

function delete_emp_document($uid, $status) {
    $query = ORM::forTable("documents")->where("uid", $uid)->findOne();
    $query->set("status", 0);
    $query->save();
}

function read_approved_loans_user($userUid) {
    $data = ORM::forTable("emp_loans")->where("emp_uid", $userUid)->where("status", 1)->where("request_status", "Approved")->findMany();
    return $data;
}

function edit_document_by_user($uid, $description, $files, $dateModified){
    $data = ORM::forTable("documents")->where("uid", $uid)->findResultSet();
        $data->set("description", $description);
        $data->set("files", $files);
        $data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

function get_merged_rating($userUid, $curUid){
	$query = ORM::forTable("training_rating")
	->rawQuery("SELECT uid, rating, percentage, curriculum_uid, examinee_uid FROM training_rating WHERE examinee_uid = :userUid AND curriculum_uid = :curUid", array("userUid" => $userUid, "curUid" => $curUid))
	->findMany();
	return $query;
}

function read_examinee_one($examineeUid) {
	$data = ORM::forTable("training_examinee")->where("uid", $examineeUid)->where("status", 1)->findMany();
	return $data;
}

function read_questionnaire_by_uid($uid) {

	$data = ORM::forTable("evaluation_type")->where("uid", $uid)->findMany();
	return $data;
}

function update_questionnaire($uid, $content, $date_modified) {
	$data = ORM::forTable("evaluation_type")
		->where("uid", $uid)
		->findOne();
	$data->set("name", $content);
    $data->set("date_modified", $date_modified);
    $data->save();
}

function read_request_type_uid($uid) {
	$data = ORM::forTable("requests")->where("uid", $uid)->findMany();	
	return $data;
}

function updateEmployeeOne($empUid , $firstname , $middlename , $lastname , $gender , $marital , $nationality , $bday , $email , $nickname , $driverLicense , $expiryLicense , $sssNo , $taxNo , $philhealthNo , $pagibigNo , $dateModified , $status){
    $query = ORM::forTable("emp")->where("emp_uid", $empUid)->findOne();
        $query->set("firstname", $firstname);
        $query->set("middlename", $middlename);
        $query->set("lastname", $lastname);
        $query->set("gender", $gender);
        $query->set("marital", $marital);
        $query->set("nationality", $nationality);
        $query->set("bday", $bday);
        $query->set("email", $email);
        $query->set("nickname", $nickname);
        $query->set("drivers_license", $driverLicense);
        $query->set("expiry_license", $expiryLicense);
        $query->set("sss_no", $sssNo);
        $query->set("tax_no", $taxNo);
        $query->set("philhealth_no", $philhealthNo);
        $query->set("pagibig_no", $pagibigNo);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function updateEmployeeOneHRIS($empUid , $firstname , $middlename , $lastname , $gender , $marital , $nationality , $bday , $email , $nickname , $driverLicense , $expiryLicense , $sssNo , $taxNo , $philhealthNo , $pagibigNo , $dateModified , $status, $taxStatus, $housenumber, $barangay, $city, $region, $height, $weight, $bloodtype){
    $query = ORM::forTable("emp")->where("emp_uid", $empUid)->findOne();
        $query->set("firstname", $firstname);
        $query->set("middlename", $middlename);
        $query->set("lastname", $lastname);
        $query->set("gender", $gender);
        $query->set("marital", $marital);
        $query->set("nationality", $nationality);
        $query->set("bday", $bday);
        $query->set("email", $email);
        $query->set("house_number", $housenumber);
        $query->set("barangay", $barangay);
        $query->set("city", $city);
        $query->set("region", $region);
        $query->set("height", $height);
        $query->set("weight", $weight);
        $query->set("blood_type", $bloodtype);
        $query->set("nickname", $nickname);
        $query->set("drivers_license", $driverLicense);
        $query->set("expiry_license", $expiryLicense);
        $query->set("sss_no", $sssNo);
        $query->set("tax_no", $taxNo);
        $query->set("philhealth_no", $philhealthNo);
        $query->set("pagibig_no", $pagibigNo);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function read_memo_type_uid($uid) {
	$data = ORM::forTable("memo_type")->where("uid", $uid)->findOne();	
	return $data;
}



// START ADDED MARCH 31, 2017
function send_email_for_request($requestUid, $request, $reason, $empuid, $firstname, $middlename, $lastname, $name, $approveUid, $declineUid, $var, $department, $departmentHead, $headEmail, $depName, $dateCreated){

	$mail = new PHPMailer;

	$var = "<table style='width: 90%; font-family: Lato, sans-serif;''> 
				<thead style='width: 90%; height: 30%;'>
					<th colspan='3' style='background-color: #1CCABB; align: left'><h1 style='color: white;'>HUMANO</h1></th>
				</thead>
				<tbody style='background-color: white; height: 50%'>
					<tr>
						<td colspan='3'>
							<p>$firstname $middlename $lastname has requested your approval for the following: </p>
							<br><strong>DEPARTMENT NAME: </strong> $depName <br>
							<br><strong>REQUEST ID: </strong> $requestUid <br>
							<br><strong>REQUEST TYPE: </strong> Document Request, $name <br>
							<br><strong>REASON: </strong> $reason <br>
							<br><strong>DATE AND TIME: </strong> $dateCreated <br>
							
							<br><br><p>Please click the following link to either certify or reject this request. </p>
						</td>
					</tr>
					<tr>
						<td align='center'>
							$approveUid'><h1><strong>Certify</strong</h1></a>
						</td>
						<td align='center'>
							$declineUid'><h1><strong>Reject</strong</h1></a>
						</td>
						<td>
						</td>
					</tr>
				</tbody>
				<tfoot style='background-color: #353957; height: 20%'>
					<tr>
						<td style='padding: 20px; color: white;' colspan='3'>
								--------------------------------------------------------------------------------<br>
								Note: This is a system-generated e-mail. Do not reply. <br>
								--------------------------------------------------------------------------------
						</td>
					</tr>
				</tfoot>
			</table>";

	//$mail->SMTPDebug = 3;                               // Enable verbose debug output

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'myhumanoonline@gmail.com';                 // SMTP username
	$mail->Password = 'p@$$w0rd';                           // SMTP password
	$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 465;                                    // TCP port to connect to

	$mail->setFrom('myhumanoonline@gmail.com', 'Mailer');
	$mail->addAddress($headEmail);     // Add a recipient
	//$mail->addAddress('ellen@example.com');               // Name is optional
	$mail->addReplyTo('info@example.com', 'Information');
	//$mail->addCC('cc@example.com');
	//$mail->addBCC('bcc@example.com');

	$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = 'Humano Request Approval';
	$mail->Body    = $var;
	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	if(!$mail->send()) {
	    echo 'Message could not be sent.';
	    echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
	    echo 'Message has been sent';
	}
}

// function send_email_for_loans($empLoanUid, $empUid, $name, $firstname, $middlename, $lastname, $approveUid, $declineUid, $var, $dateCreated){

// 	$mail = new PHPMailer;

// 	$var = "<table style='width: 90%; font-family: Lato, sans-serif;''> 
// 				<thead style='width: 90%; height: 30%;'>
// 					<th style='background-color: #1CCABB;'><h1 style='align: left'>HUMANO</h1></th>
// 				</thead>
// 				<tbody style='background-color: #D2D6DE; height: 50%'>
// 					<tr>
// 						REQUEST ID: $empLoanUid <br>
// 						REQUEST: $name <br>
// 						EMPLOYEE ID: $empUid <br>
// 						FULL NAME: $firstname $middlename $lastname <br>
// 						TOKEN: $var <br>
// 						APPROVE: $approveUid'> APPROVE </a> <br>
// 						DECLINE: $declineUid'> DECLINE </a> <br>
// 						DATE CREATED $dateCreated <br> 
// 					</tr>
// 				</tbody>
				
// 				<tfoot style='background-color: #353957; height: 20%'>
// 					<tr>
// 						<td style='padding: 40px; color: white;'>
// 								--------------------------------------------------------------------------<br>
// 								This is a system-generated e-mail. Do not reply. <br>
// 								--------------------------------------------------------------------------
// 						</td>
// 					</tr>
// 				</tfoot>
// 			</table>";

// 	//$mail->SMTPDebug = 3;                               // Enable verbose debug output

// 	$mail->isSMTP();                                      // Set mailer to use SMTP
// 	$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
// 	$mail->SMTPAuth = true;                               // Enable SMTP authentication
// 	$mail->Username = 'myhumanoonline@gmail.com';                 // SMTP username
// 	$mail->Password = 'p@$$w0rd';                           // SMTP password
// 	$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
// 	$mail->Port = 465;                                    // TCP port to connect to

// 	$mail->setFrom('myhumanoonline@gmail.com', 'Mailer');
// 	$mail->addAddress('akosianjong15@gmail.com', 'Jen Zapanta');     // Add a recipient
// 	//$mail->addAddress('ellen@example.com');               // Name is optional
// 	$mail->addReplyTo('info@example.com', 'Information');
// 	//$mail->addCC('cc@example.com');
// 	//$mail->addBCC('bcc@example.com');

// 	$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
// 	$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
// 	$mail->isHTML(true);                                  // Set email format to HTML

// 	$mail->Subject = 'Humano Request Approval';
// 	$mail->Body    = $var;
// 	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

// 	if(!$mail->send()) {
// 	    echo 'Message could not be sent.';
// 	    echo 'Mailer Error: ' . $mail->ErrorInfo;
// 	} else {
// 	    echo 'Message has been sent';
// 	}
// }

function email_request_approve($uid, $requestStatus, $dateModified){
    $query = ORM::forTable("emp_requests")->where("uid", $uid)->findOne();
    $query->set("request_status", $requestStatus);
    $query->set("date_modified", date("Y-m-d H:i:s"));
    $query->save();
}

// function email_loan_approve($uid, $loanStatus, $dateModified){
//     $query = ORM::forTable("emp_loans")->where("uid", $uid)->findOne();
//     $query->set("request_status", $loanStatus);
//     $query->set("date_modified", date("Y-m-d H:i:s"));
//     $query->save();
// }

function admin_submit_request($uid, $dateRelease, $dateModified){
    $query = ORM::forTable("emp_requests")->where("uid", $uid)->findOne();
    $query->set("dateRelease", $dateRelease);
    $query->set("date_modified", date("Y-m-d H:i:s"));
    $query->save();
}

function create_department_head($uid, $empUid, $depUid, $position, $dateCreated, $dateModified){
	$data = ORM::forTable("department_heads")->create();
		$data->uid = $uid;
		$data->emp_uid = $empUid;
		$data->department_uid = $depUid;
		$data->position = $position;
		$data->date_created = $dateCreated;
		$data->date_modified = $dateModified;
		$data->status = 1;
	$data->save();
}

function read_department_head_all(){
	$data = ORM::forTable("department_heads")->where("status", 1)->findMany();
	return $data;
}

function read_department_head_uid($uid) {
	$data = ORM::forTable("department_heads")->where("uid", $uid)->findMany();
	return $data;
}


function delete_department_head($uid, $status) {
    $query = ORM::forTable("department_heads")->where("uid", $uid)->findOne();
    $query->set("status", 0);
    $query->save();
}

function read_employee_department($empUid) {
	$data = ORM::forTable("emp_department")->where("emp_uid", $empUid)->findMany();
	return $data;
}

function read_department_head($depUid) {
	$data = ORM::forTable("department_heads")->where("department_uid", $depUid)->findMany();
	return $data;
}

function read_department_head_data($department_head) {
	$data = ORM::forTable("emp")->where("emp_uid", $department_head)->findMany();
	return $data;
}

//END EDITED MARCH 30, 2017

// END ADDED MARCH 28, 2017

function update_department_head($uid, $employee, $department, $position, $dateModified){
    $data = ORM::forTable("department_heads")->where("uid", $uid)->findResultSet();
        $data->set("emp_uid", $employee);
        $data->set("department_uid", $department);
        $data->set("position", $position);
        $data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

/* Educational Level */
function create_educational_level($level) {
	$data = ORM::forTable("education_level")->create();
	$data->education_level_uid = xguid();
	$data->level_name = $level;
	$data->date_created = date("Y-m-d H:i:s");
	$data->date_modified = date("Y-m-d H:i:s");
	$data->status = 1;
	$data->save();
}

function read_educational_level() {
	$data = ORM::forTable("education_level")->where("status", 1)->findMany();
	return $data;
}

function read_educational_level_by_uid($uid) {
	$data = ORM::forTable("education_level")->where("education_level_uid", $uid)->where("status", 1)->findOne();
	return $data;
}

/* Educational Background */
function create_educational_background($emp_uid, $level, $school, $major, $year, $score, $start_date, $end_date) {
	$data = ORM::forTable("education")->create();
	$data->education_uid = xguid();
	$data->emp_uid = $emp_uid;
	$data->education_level_uid = $level;
	$data->school = $school;
	$data->major = $major;
	$data->year = $year;
	$data->score = $score;
	$data->start_date = $start_date;
	$data->end_date = $end_date;
	$data->date_created = date("Y-m-d H:i:s");
	$data->date_modified = date("Y-m-d H:i:s");
	$data->status = 1;
	$data->save();
}

function read_educational_background() {
	$data = ORM::forTable("education")->where("status", 1)->findMany();
	return $data;
}

function read_educational_background_by_uid($uid) {
	$data = ORM::forTable("education")->where("educational_uid", $uid)->where("status", 1)->findOne();
	return $data;
}

function read_educational_background_by_emp_uid($emp_uid) {
	$data = ORM::forTable("education")->where("emp_uid", $emp_uid)->where("status", 1)->findMany();
	return $data;
}

function update_educational_background($uid, $school, $major, $year, $score, $start_date, $end_date) {
	$data = ORM::forTable("education")->where("education_uid", $uid)->findResultSet();
	$data->set("school", $school);
	$data->set("major", $major);
	$data->set("year", $year);
	$data->set("score", $score);
	$data->set("start_date", $start_date);
	$data->set("end_date", $end_date);
	$data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

function delete_educational_background($uid) {
	$data = ORM::forTable("education")->where("education_uid", $uid)->findResultSet();
	$data->set("status", 0);
	$data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

/* Work Experience */
function create_work_experience($emp_uid, $employer, $position, $dstart, $dend, $status) {
	$data = ORM::forTable("hris_work_experience")->create();
	$data->work_experience_uid = xguid();
	$data->emp_uid = $emp_uid;
	$data->employer = $employer;
	$data->job_title = $position;
	$data->we_from = $dstart;
	$data->we_to = $dend;
	$data->comments = $status;
	$data->date_created = date("Y-m-d H:i:s");
	$data->date_modified = date("Y-m-d H:i:s");
	$data->status = 1;
	$data->save();
}

function update_work_experience_by_uid($uid, $employer, $position, $dstart, $dend, $status) {
	$data = ORM::forTable("hris_work_experience")->where("work_experience_uid", $uid)->findResultSet();
	$data->set("employer", $employer);
	$data->set("job_title", $position);
	$data->set("we_from", $dstart);
	$data->set("we_to", $dend);
	$data->set("comments", $status);
	$data->set("date_modified", date("Y-m-d H:i:s"));
    $data->save();
}

function delete_work_experience_by_uid($uid) {
	$data = ORM::forTable("hris_work_experience")->where("work_experience_uid", $uid)->findResultSet();
	$data->set("date_modified", date("Y-m-d H:i:s"));
	$data->set("status", 0);
    $data->save();
}

function read_work_experience_by_emp_uid($emp_uid) {
	$data = ORM::forTable("hris_work_experience")->where("emp_uid", $emp_uid)->where("status", 1)->findMany();
	return $data;
}

function create_license($emp_uid, $license_uid, $license_no, $issued, $expired) {
	$data = ORM::forTable("hris_license")->create();
	$data->hris_license_uid = xguid();
	$data->emp_uid = $emp_uid;
	$data->license_uid = $license_uid;
	$data->license_no = $license_no;
	$data->issued_date = $issued;
	$data->expiry_date = $expired;
	$data->date_created = date("Y-m-d H:i:s");
	$data->date_modified = date("Y-m-d H:i:s");
	$data->status = 1;
	$data->save();
}

function read_hris_license_by_emp_uid($emp_uid) {
	$data = ORM::forTable("hris_license")->where("emp_uid", $emp_uid)->where("status", 1)->findMany();
	return $data;
}

function read_license_by_uid($uid) {
	$data = ORM::forTable("license")->where("license_uid", $uid)->where("status", 1)->findOne();
	return $data;
}

function read_license() {
	$data = ORM::forTable("license")->where("status", 1)->findMany();
	return $data;	
}

function read_document() {
	$data = ORM::forTable("document")->where("status", 1)->findMany();
	return $data;	
}

function upload_document_file($emp_uid, $doc_uid, $filename, $path, $mimeType, $size) {
	$query = ORM::forTable("emp_document")->create();
	$query->uid = xguid();
	$query->emp_uid = $emp_uid;
	$query->doc_uid = $doc_uid;
	$query->filename = $filename;
	$query->path = $path;
	$query->mime_type = $mimeType;
	$query->size = $size;
	$query->date_created = date("Y-m-d H:i:s");
	$query->date_modified = date("Y-m-d H:i:s");
	$query->save();
}

function read_emp_document_by_emp_uid($emp_uid) {
	$data = ORM::forTable("emp_document")->where("emp_uid", $emp_uid)->where("status", 1)->findMany();
	return $data;
}

/* Application Number Generator */
function application_number($table) {
	$sdate = date("Y-m-d"); $date = "$sdate%";
	$sql = "SELECT id FROM $table WHERE date_created LIKE '$sdate%'";
	$query = ORM::forTable($table)->where_like("date_created", $date)->where("status", 1)->count();
	$series = 0;	
	$num = $query + 1;
	$series = str_pad($num, '6', '0', STR_PAD_LEFT);
	return date("Ymd") . $series;
}

function create_employee_cash_advances($emp_uid, $application_no, $loan_amount, $terms, $interest, $monthly_due) {
	$query = ORM::forTable("emp_cash_advances")->create();
	$query->uid = xguid();
	$query->emp_uid = $emp_uid;
	$query->application_no = $application_no;
	$query->loan_amount = $loan_amount;
	$query->terms = $terms;
	$query->interest = $interest;
	$query->date_granted = "N/A";
	$query->monthly_due = $monthly_due;
	$query->remarks = "N/A";
	$query->date_created = date("Y-m-d H:i:s");
	$query->date_modified = date("Y-m-d H:i:s");
	$query->status = 1;
	$query->save();
}

function get_employee_cash_advances_all() {
	$data = ORM::forTable("emp_cash_advances")->where("status", 1)->findMany();
	return $data;
}

function get_employee_cash_advances_by_uid($uid) {
	$data = ORM::forTable("emp_cash_advances")->where("uid", $uid)->where("status", 1)->findOne();
	return $data;
}

function verify_employee_cash_advances($emp_uid) {
	$data = ORM::forTable("emp_cash_advances")->where("emp_uid", $emp_uid)->where("status", 1)->count();
	return $data;
}

function get_employee_loan_payment_by_uid($uid) {
	$data = ORM::forTable("emp_loans_payments")->where("emp_loan_uid", $uid)->where("status", 1)->findMany();
	return $data;
}