<?php
    require_once 'idiorm.php';
    /*FOR ONLINE*/
    ORM::configure('mysql:host=127.0.0.1;dbname=hris');
    ORM::configure('username', 'root');
    ORM::configure('password', 'root@$avasia2015');
    ORM::configure('logging', true);
    ORM::configure('return_result_sets', true);

    /*FOR DEVELOPMENT*/
    // ORM::configure('mysql:host=127.0.0.1;dbname=hris');
    // ORM::configure('username', 'root');
    // ORM::configure('password', 'root');
    // ORM::configure('logging', true);
    // ORM::configure('return_result_sets', true);
function spacetime(){
    ORM::configure('mysql:host=127.0.0.1;dbname=spacetime');
    ORM::configure('username', 'root');
    ORM::configure('password', 'root');
    ORM::configure('logging', true);
    ORM::configure('return_result_sets', true);
}
function jsonify($response) {
    $json = json_encode($response);
    $tabcount = 0;
    $result = '';
    $inquote = false;
    $tab = "   ";
    $newline = "\n";

    for ($i = 0; $i < strlen($json); $i++) {
        $char = $json[$i];

        if ($char == '"' && $json[$i - 1] != '\\')
            $inquote = !$inquote;

        if ($inquote) {
            $result.=$char;
            continue;
        }
        switch ($char) {
            case '{':
                if ($i)
                    $result.=$newline;
                $result.=str_repeat($tab, $tabcount) . $char . $newline . str_repeat($tab, ++$tabcount);
                break;
            case '}':
                $result.=$newline . str_repeat($tab, --$tabcount) . $char;
                break;
            case ',':
                $result.=$char;
                if ($json[$i + 1] != '{')
                    $result.=$newline . str_repeat($tab, $tabcount);
                break;
            default:
                $result.=$char;
        }
    }
    return $result;
}

function xguid() {
    mt_srand((double) microtime() * 10000);
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $hyphen = chr(45);
    $uuid = substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12);
    return $uuid;
}
function getOffsetByTimeZone($localTimeZone){
    $time = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone($localTimeZone));
    $timezoneOffset = str_replace(":00", "", $time->format('P'));
    return $timezoneOffset;
}


/*------------------------------User Account -------------------------------------*/
function usernameIsExisting($username){
    $query = ORM::forTable("users")->select_expr("COUNT(*)", "count")->where("username", $username)->findOne();
        $existing = false;
        if ($query->count >= 1) {
            $existing = true;
        }
        return $existing;
}


function newUserAccount($userId , $username , $password , $usertype , $empUid , $dateCreated , $dateModified){
    if(!usernameIsExisting($username)){
        $query = ORM::forTable("users")->create();
            $query->users_uid = $userId;
            $query->username = $username;
            $query->password = $password;
            $query->type = $usertype;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
            $query->emp_uid = $empUid;
        $query->save();
    }
}

function newUserEmpAccount($userId , $username , $password , $usertype , $empUid , $dateCreated , $dateModified){
    $query = ORM::forTable("users")->create();
        $query->users_uid = $userId;
        $query->username = $username;
        $query->password = $password;
        $query->type = $usertype;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
        $query->emp_uid = $empUid;
    $query->save();
}

function userHasExistingUniqueKey($userId){
    $query = ORM::forTable("user_unique_keys")->select_expr("COUNT(*)", "count")->where("user", $userId)->findOne();
        $existing = false;
        if ($query->count >= 1) {
            $existing = true;
        }
        return $existing;
}

function newUserUniqueKey($uid, $userId, $key , $dateCreated , $dateModified){
    if(!userHasExistingUniqueKey($userId)){
        $query = ORM::forTable("user_unique_keys")->create();
            $query->uid = $uid;
            $query->user = $userId;
            $query->unique_key = $key;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
        $query->save();
    }
}

function updateUserUniqueKey($userId, $key ,$dateModified){
    $query = ORM::forTable("user_unique_keys")->where("user", $userId)->findOne();
        $query->set("unique_key", $key);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function checkIfPasswordIsCorrectByEmpUid($uid, $password){
    $query = ORM::forTable("users")->where("emp_uid", $uid)->where("password", $password)->where("status", 1)->findOne();
    $valid = false;
    if($query){
        $valid = true;
    }

    return $valid;
}

function updateEmpPassword($uid, $password, $dateModified){
    $query = ORM::forTable("users")->where("emp_uid", $uid)->where("status", 1)->findOne();
        $query->set("password", $password);
        $query->set("date_modified", $dateModified);
    $query->save();
}

/*------------------------------User Account End -------------------------------------*/

/*------------------------------employees -------------------------------------*/

function getActiveEmployees(){
    $query = ORM::forTable("emp")->tableAlias("t1")->innerJoin("users", array("t1.emp_uid", "=", "t2.emp_uid"), "t2")->whereNotEqual("t2.username", "0001")->where("t1.status", "1")->orderByAsc("t2.username")->findMany();
    return $query;
}

function getEmployeesCount(){
    $query = ORM::forTable("emp")->select_expr("count(id)", "count")->where("status", "1")->findOne();
    return $query->count;
}

function employeeIsExisting($firstname , $middlename , $lastname) {
    $query = ORM::forTable("emp")->select_expr("count(emp_uid)","count")->where("firstname", $firstname)->where("middlename", $middlename)->where("lastname", $lastname)->findOne();
        if($query->count >= 1){
            return true;
        }else{
            return false;
        }
}

function getEmployeesByFrequencyUid($frequencyUid){
    $query = ORM::forTable("emp")
    ->rawQuery("SELECT * from emp as t1 INNER JOIN salary as t2 ON t1.emp_uid = t2.emp_uid INNER JOIN pay_period as t3 ON t2.pay_period_uid = t3.pay_period_uid WHERE t2.pay_period_uid = :frequencyUid AND t1.status=1", array("frequencyUid" => $frequencyUid))
    ->findResultSet();

    return $query;
}

function getActiveMonthlyEmployees(){
    $query = ORM::forTable("emp")
    ->rawQuery("SELECT * FROM emp as t1 INNER JOIN salary as t2 ON t1.emp_uid = t2.emp_uid INNER JOIN pay_period as t3 ON t2.pay_period_uid = t3.pay_period_uid WHERE t3.pay_period_name = 'Semi-Monthly' ORDER BY t1.lastname ASC")
    ->findMany();

    return $query;
}

function getSemiMonthlyUid(){
    $query = ORM::forTable("pay_period")->where("pay_period_name", "Semi-Monthly")->where("status", 1)->findOne();
    return $query->pay_period_uid;
}

function getDailyUid(){
    $query = ORM::forTable("pay_period")->where("pay_period_name", "Daily")->where("status", 1)->findOne();
    return $query->pay_period_uid;
}

function getMonthlyUid(){
    $query = ORM::forTable("pay_period")->where("pay_period_name", "Monthly")->where("status", 1)->findOne();
    return $query->pay_period_uid;
}

function getWeeklyUid(){
    $query = ORM::forTable("pay_period")->where("pay_period_name", "Weekly")->where("status", 1)->findOne();
    return $query->pay_period_uid;
}
function getActiveDailyEmployees(){
    $query = ORM::forTable("emp")
    ->rawQuery("SELECT * FROM emp as t1 INNER JOIN salary as t2 ON t1.emp_uid = t2.emp_uid INNER JOIN pay_period as t3 ON t2.pay_period_uid = t3.pay_period_uid WHERE t3.pay_period_name = 'Daily' ORDER BY t1.lastname ASC")
    ->findMany();

    return $query;
}


function newEmployee($empUid , $firstname , $middlename , $lastname, $marital, $type, $dateCreated , $dateModified) {
    if (!employeeIsExisting($firstname, $middlename , $lastname)) {
        $query = ORM::forTable("emp")->create();
            $query->emp_uid = $empUid;
            $query->firstname = $firstname;
            $query->middlename = $middlename;
            $query->lastname = $lastname;
            $query->marital = $marital;
            $query->type = $type;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
        $query->save();
            return false;
    }else{
        return true;
    }
}

function newEmp($empUid , $firstname , $middlename , $lastname, $marital, $type, $dateCreated , $dateModified){
    $query = ORM::forTable("emp")->create();
        $query->emp_uid = $empUid;
        $query->firstname = $firstname;
        $query->middlename = $middlename;
        $query->lastname = $lastname;
        $query->marital = $marital;
        $query->type = $type;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function updateEmployeeStatus($empUid , $dateModified , $status) {
    $query = ORM::forTable("emp")->where("emp_uid", $empUid)->findOne();
        $query->set("status", $status);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function updateEmployee($empUid , $firstname , $middlename , $lastname , $gender , $marital , $nationality , $bday , $email , $nickname , $driverLicense , $expiryLicense , $sssNo , $taxNo , $philhealthNo , $pagibigNo , $dateModified , $status){
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

function getEmployeeByName($firstname , $middlename , $lastname){
    $query = ORM::forTable("emp")->select("emp_uid")->where("firstname", $firstname)->where("middlename", $middlename)->where("lastname", $lastname)->findOne();
    return $query;
}

function checkIfUserExisted($username){
    $query = ORM::forTable("users")->selectExpr("COUNT(username)", "count")->where("username", $username)->where("status", 1)->findOne();
    return $query->count;
}

function checkIfUser($username){
    $query = ORM::forTable("users")
        ->rawQuery("SELECT COUNT(username) as count, emp_uid FROM users WHERE username = :username OR emp_uid = :username AND status = 1", array("username" => $username))
        ->findOne();
    return $query;
}

function getEmployeeByUid($empUid){
    $query = ORM::forTable("emp")->where("emp_uid", $empUid)->findOne();
    return $query;
}

function getEmployeeType($empUid){
    $query = ORM::forTable("users")->where("emp_uid", $empUid)->findOne();
    return $query->type;
}

function updateEmpTypes($uid, $empType, $dateModified){
    $query = ORM::forTable("users")->where("emp_uid", $uid)->findOne();
        $query->set("type", $empType);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function checkEmployeeByUid($empUid){
    $query = ORM::forTable("emp")->where("emp_uid", $empUid)->count();
    $valid = false;
    if($query >= 1){
        $valid = true;
    }

    return $valid;
}

function getEmloyeeNumberByEmpUid($empUid){
    $query = ORM::forTable("users")->select("username", "uname")->where("emp_uid", $empUid)->where("status", 1)->findOne();
    return $query->uname;
}

function getEmpByUid($id){
    $query = ORM::forTable("emp")->tableAlias("t1")->innerJoin("users", array("t1.emp_uid", "=", "t2.emp_uid"), "t2")->where("t1.emp_uid", $id)->findOne();
    return $query;
}

function getPaginatedEmployees() {
    $query = ORM::forTable("emp")->tableAlias("t1")->innerJoin("users", array("t1.emp_uid", "=", "t2.emp_uid"), "t2")->where("t1.status", 1)->orderByAsc("t2.username")->findMany();
    return $query;
}

/*------------------------------employees end-----------------------------*/

/*------------------------------employee Dependent-----------------------------------*/


function getPaginatedEmployeeDependent($empUid) {
    $query = ORM::forTable("emp_dependent")->where("status", 1)->where("emp_uid", $empUid)->find_result_set();
    return $query;
}

function employeeDependentIsExisting($empUid,$name,$relationship,$bday){
    $query = ORM::forTable("emp_dependent")->select_expr("count(emp_dependent_uid)", "count")->where("emp_uid", $empUid)->where("name", $name)->where("relationship", $relationship)->where("bday", $bday)->findOne();
        $status = false;
        if($query->count >= 1){
            $status = true;
        }
        return $status;
}

function getEmployeeDependentUidAndStatus($empUid,$name,$relationship,$bday){
    $query = ORM::forTable("emp_dependent")->select("emp_dependent_uid")->select("status")->where("emp_uid", $empUid)->where("name", $name)->where("relationship", $relationship)->where("bday", $bday)->find_result_set();
    return $query;
}

function getEmployeeDependentByUid($empDepUid){
    $query = ORM::forTable("emp_dependent")->where("emp_dependent_uid", $empDepUid)->findOne();
    return $query;
}

function newEmployeeDependent($empDependentUid,$empUid,$name,$relationship,$number,$bday,$dateCreated,$dateModified){
    if(!employeeDependentIsExisting($empUid,$name,$relationship,$bday)){
        $query = ORM::forTable("emp_dependent")->create();
            $query->emp_dependent_uid = $empDependentUid;
            $query->emp_uid = $empUid;
            $query->name = $name;
            $query->relationship = $relationship;
            $query->number = $number;
            $query->bday = $bday;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
        $query->save();
    }else{
        $empDependentUid = getEmployeeDependentUidAndStatus($empUid,$name,$relationship,$bday);
        if($empDependentUid->status == 0){
            updateEmployeeDependentStatusById($empDependentUid->emp_dependent_uid , $empDependentUid->name , $empDependentUid->relationship , $empDependentUid->bday , $dateModified , 1);
        }
    }    
}

function updateEmployeeDependentStatusById($empDependentUid , $name , $relationship ,  $bday , $number ,  $dateModified , $status){
    $query = ORM::forTable("emp_dependent")->where("emp_dependent_uid", $empDependentUid)->findOne();
        $query->set("name", $name);
        $query->set("relationship", $relationship);
        $query->set("bday", $bday);
        $query->set("number", $number);
        $query->set("status", $status);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function employeeDependentCount($empUid , $name , $relationship , $bday){
    $query = ORM::forTable("emp_dependent")->where("emp_uid", $empUid)->where("name", $name)->where("relationship", $relationship)->where("bday", $bday)->count();
    return $query;
}

/*------------------------------employee Dependent End-----------------------------------*/

/*------------------------------employee Phone-----------------------------------*/

function getPaginatedEmployeePhone($empUid) {
    $query = ORM::forTable("phone")->where("status", 1)->where("emp_uid", $empUid)->findOne();
    return $query;
}

function getEmployeeContactDetails($phoneUid){
    $query = ORM::forTable("phone")->where("phone_uid", $phoneUid)->findOne();
    return $query;
}

function employeePhoneIsExisting($empUid,$phone){
    $query = ORM::forTable("phone")->select_expr("count(phone_uid)", "count")->where("emp_uid", $empUid)->where("number", $phone)->findOne();
        $status = false;
        if($query->count >= 1){
            $status = true;
        }
        return $status;
}

function employeePhoneCount($empUid,$phone){
    $query = ORM::forTable("phone")->select_expr("count(phone_uid)", "count")->where("emp_uid", $empUid)->where("number", $phone)->findOne();
    return $query;
}

function getEmployeePhoneUidAndStatus($empUid,$phone){
    $query = ORM::forTable("phone")->select("phone_uid")->select("status")->where("emp_uid", $empUid)->where("number", $phone)->findOne();
    return $query;
}

function newEmployeePhone($phoneUid,$empUid,$phoneType,$phone,$dateCreated,$dateModified){
    if(!employeePhoneIsExisting($empUid,$phone)){
        $query = ORM::forTable("phone")->create();
            $query->phone_uid = $phoneUid;
            $query->emp_uid = $empUid;
            $query->phonetype_uid = $phoneType;
            $query->number = $phone;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
        $query->save();
    }else{
        $empPhone = getEmployeePhoneUidAndStatus($empUid,$phone);
        if($empPhone->status == 0){
            updateEmployeePhoneById($empPhone->phone_uid , $dateModified , 1 , $phoneType , $phone );
        }
    }    
}

function updateEmployeePhoneById($empPhoneUid , $dateModified , $status , $phoneType , $phone){
    $query = ORM::forTable("phone")->where("phone_uid", $empPhoneUid)->findOne();
        $query->set("status", $status);
        $query->set("date_modified", $dateModified);
        $query->set("phonetype_uid", $phoneType);
        $query->set("number", $phone);
    $query->save();
}

/*------------------------------employee Phone End-----------------------------------*/

/*------------------------------Phone Type-----------------------------*/
function getPhoneTypes() {
    $query = ORM::forTable("phonetype")->where("status", 1)->find_result_set();
    return $query;
}

function getPhoneTypeByType($phoneType) {
    $query = ORM::forTable("phonetype")->select("phonetype_uid")->where("status", 1)->where("phone_type", $phoneType)->find_result_set();
    return $query;
}

function getPhoneTypeByUid($phoneTypeUid) {
    $query = ORM::forTable("phonetype")->select("phone_type")->where("phonetype_uid", $phoneTypeUid)->find_result_set();
    return $query;
}

function phoneTypeIsExisting($phoneType) {
    $query = ORM::forTable("phonetype")->select_expr("count(phonetype_uid)", "count")->where("phone_type", $phoneType)->find_result_set();
        if($query >= 1){
            return true;
        }else{
            return false;
        }
}

function newPhoneType($phoneTypeUid,$phoneType,$dateCreated,$dateModified) {
    if (!phoneTypeIsExisting($phoneType)) {
        $query = ORM::forTable("phonetype")->create();
            $query->phonetype_uid = $phoneTypeUid;
            $query->phone_type = $phoneType;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
        $query->save();
        return false;
    }else{
        return true;
    }
}

/*------------------------------Phone Type End-----------------------------*/

/*------------------------------nationalities-----------------------------*/

function nationalityIsExisting($nationalityName) {
    $query = ORM::forTable("nationality")->select_expr("count(nationality_uid)", "count")->where("name", $nationalityName)->findOne();
        if($query->count >= 1){
            return true;
        }else{
            return false;
        }
}

function nationalityCount($nationalityName) {
    $query = ORM::forTable("nationality")->select_expr("count(nationality_uid)", "count")->where("name", $nationalityName)->find_result_set();
    return $query->count;
}

function newNationality($nationalityUid , $nationalityName , $dateCreated , $dateModified) {
    if (!nationalityIsExisting($nationalityName)) {
        $query = ORM::forTable("nationality")->create();
            $query->nationality_uid = $nationalityUid;
            $query->name = $nationalityName;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
        $query->save();
            return false;
    }else{
        return true;
    }
}

function getNationalities() {
    $query = ORM::forTable("nationality")->where("status", 1)->orderByAsc("name")->find_result_set();
    return $query;
}

function getNationalityByName($nationalityName){
    $query = ORM::forTable("nationality")->where("name", $nationalityName)->findMany();
    return $query;
}


function updateNationality($nationalityUid , $name , $dateModified , $status) {
    $query = ORM::forTable("nationality")->where("nationality_uid", $nationalityUid)->findOne();
        $query->set("name", $name);
        $query->set("status", $status);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function getPaginatedNationalities() {
    $query = ORM::forTable("nationality")->findMany();
    return $query;
}

function getNationalityByUid($uid) {
    $query = ORM::forTable("nationality")->where("nationality_uid", $uid)->findOne();
    return $query;
}


/*------------------------------nationalities END-----------------------------*/

/*--------------------------------------job-------------------------------------*/

function getJobs(){
    $query = ORM::forTable("hris_job_title")->where("status", 1)->find_result_set();
        return $query;
}

function getJobByUid($jobUid){
    $query = ORM::forTable("hris_job_title")->where("job_uid", $jobUid)->findOne();
        return $query;
}

function jobIsExisting($title){
    $query = ORM::forTable("hris_job_title")->select_expr("count(job_uid)", "count")->where("title", $title)->findOne();
        $status = false;
        if($query->count == 1){
            $status = true;
        }
        return $status;
}

function jobCount($title){
    $query = ORM::forTable("hris_job_title")->select_expr("count(job_uid)", "count")->where("title", $title)->findOne();
        return $query->count;
}

function getJobUidAndStatus($title){
    $query = ORM::forTable("hris_job_title")->select("job_uid")->select("status")->where("title", $title)->find_result_set();
        return $query;
}

function newJob($jobUid , $title , $description , $note , $dateCreated , $dateModified){
    if(!jobIsExisting($title)){
        $query = ORM::forTable("hris_job_title")->create();
            $query->job_uid = $jobUid;
            $query->title = $title;
            $query->description = $description;
            $query->note = $note;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
        $query->save();
        return 0;
    }else{
        $job = getJobUidAndStatus($title);
        if($job->status == 0){
            updateJobById($job->job_uid , $title , $description , $note , $dateModified , 1);
            return 1;
        }else{
            return 2;
        }
    }    
}

function updateJobById($jobUid , $title , $description , $note , $dateModified , $status){
    $query = ORM::forTable("hris_job_title")->where("job_uid", $jobUid)->findOne();
        $query->set("title", $title);
        $query->set("description", $description);
        $query->set("note", $note);
        $query->set("status", $status);
        $query->set("date_modified", $dateModified);
    $query->save();
        return true;
}

function getPaginatedJobs() {
    $query = ORM::forTable("hris_job_title")->findMany();
        return $query;
}

/*-------------------------------------job end------------------------------------*/
/*-------------------------------------Employment status------------------------------------*/
function getEmploymentStatus() {
    $query = ORM::forTable("hris_employment_status")->where("status", 1)->findMany();
        return $query;
}

function getPaginatedEmploymentStatus() {
    $query = ORM::forTable("hris_employment_status")->findMany();
        return $query;
}

function newEmploymentStatus($employmentStatusUid , $name , $dateCreated , $dateModified){
    if(!employmentStatusIsExisting($name)){
        $query = ORM::forTable("hris_employment_status")->create();
            $query->employment_status_uid = $employmentStatusUid;
            $query->name = $name;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
        $query->save();
            return 0;
    }else{
        $employementStatus = getEmploymentStatusUidAndStatus($name);
        if($employementStatus->status == 0){
            updateEmploymentStatusById($employementStatus->employment_status_uid , $name , $dateModified , 1);
            return 1;
        }else{
            return 2;
        }
    }    
}

function updateEmploymentStatusById($employementStatusUid , $name , $dateModified , $status){
    $query = ORM::forTable("hris_employment_status")->where("employment_status_uid", $employementStatusUid)->findOne();
        $query->set("status", $status);
        $query->set("name", $name);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function employmentStatusIsExisting($name){
    $query = ORM::forTable("hris_employment_status")->select_expr("count(employment_status_uid)", "count")->where("name", $name)->findOne();

        $status = false;
        if($query->count == 1){
            $status = true;
        }
        return $status;
}

function employmentStatusCount($name){
    $query = ORM::forTable("hris_employment_status")->select_expr("count(employment_status_uid)", "count")->where("name", $name)->findOne();
        return $query;
}

function getEmploymentStatusUidAndStatus($name){
    $query = ORM::forTable("hris_employment_status")->selectMany("employment_status_uid", "status")->where("name", $name)->findOne();
        return $query;
}

function getEmploymentStatusByUid($employmentStatusUid){
   $query = ORM::forTable("hris_employment_status")->where("employment_status_uid", $employmentStatusUid)->findOne();
        return $query; 
}

function checkUserEmploymentStatus($emp){
    $query = ORM::forTable("emp_employment_type")->where("emp_uid", $emp)->where("status", 1)->count();
    $valid = false;

    if($query >= 1){
        $valid = true;
    }

    return $valid;
}

function setEmpEmploymentStatus($empStatusUid, $uid, $empStatus, $datehired, $dateCreated, $dateModified){
    $query = ORM::forTable("emp_employment_type")->create();
        $query->emp_employment_type_uid = $empStatusUid;
        $query->emp_uid = $uid;
        $query->employment_status_uid = $empStatus;
        $query->date_hired = $datehired;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getEmploymentStatusByEmpUidPages($uid){
    $query = ORM::forTable("emp_employment_type")->tableAlias("t1")->innerJoin("hris_employment_status", array("t1.employment_status_uid", "=", "t2.employment_status_uid"), "t2")->where("t1.emp_uid", $uid)->where("t1.status", 1)->findMany();
    return $query;
}

function getEmploymentStatusByStatusUid($uid){
    $query = ORM::forTable("emp_employment_type")->tableAlias("t1")->innerJoin("hris_employment_status", array("t1.employment_status_uid", "=", "t2.employment_status_uid"), "t2")->where("t1.emp_employment_type_uid", $uid)->where("t1.status", 1)->findOne();
    return $query;
}

function updateEmpEmployeeStatus($uid, $employeeStatus, $dateHired, $dateResigned, $dateModified, $status){
    $query = ORM::forTable("emp_employment_type")->where("emp_employment_type_uid", $uid)->findOne();
        $query->set("employment_status_uid", $employeeStatus);
        $query->set("date_hired", $dateHired);
        $query->set("date_resigned", $dateResigned);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}
/*-------------------------------------Employment status End------------------------------------*/

/*-------------------------------------Job Category------------------------------------*/
function getJobCategories() {
    $query = ORM::forTable("hris_job_category")->where("status", 1)->findMany();
        return $query;
}

function getPaginatedJobCategory() {
    $query = ORM::forTable("hris_job_category")->findMany();
        return $query;
}

function jobCategoryIsExisting($name){
    $query = ORM::forTable("hris_job_category")->select_expr("count(job_category_uid)", "count")->where("name", $name)->findOne();
        $status = false;
        if($query->count == 1){
            $status = true;
        }
        return $status;
}

function newJobCategory($jobCategoryUid , $name , $dateCreated , $dateModified){
    if(!jobCategoryIsExisting($name)){
        $query = ORM::forTable("hris_job_category")->create();
            $query->job_category_uid = $jobCategoryUid;
            $query->name = $name;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
        $query->save();
            return 0;
    }else{
        $jobCategory = getJobCategoryUidAndStatus($name);
        if($jobCategory->status == 0){
            updateJobCategoryById($jobCategory->job_category_uid , $name , $dateModified , 1);
            return 1;
        }else{
            return 2;
        }
    }    
}

function getJobCategoryUidAndStatus($name){
    $query = ORM::forTable("hris_job_category")->select("job_category_uid")->select("status")->where("name", $name)->find_result_set();
        return $query;
}

function updateJobCategoryById($jobCategoryUid , $name , $dateModified , $status){
    $query = ORM::forTable("hris_job_category")->where("job_category_uid", $jobCategoryUid)->findOne();
        $query->set("status", $status);
        $query->set("name", $name);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function getJobCategoryByUid($jobCategoryUid){
   $query = ORM::forTable("hris_job_category")->where("job_category_uid", $jobCategoryUid)->findOne();
        return $query;
}

function jobCategoryCount($name){
    $query = ORM::forTable("hris_job_category")->select_expr("count(job_category_uid)", "count")->where("name", $name)->find_result_set();
        return $query;
}


/*-------------------------------------Job Category ENd------------------------------------*/

/*-------------------------------------Country------------------------------------*/
function getCountries() {
    $query = ORM::forTable("hris_countries")->where("status", "1")->find_result_set();
        return $query;
}

function getPaginatedCountries() {
    $query = ORM::forTable("hris_countries")->findMany();
        return $query;
}

function countryIsExisting($name){
    $query = ORM::forTable("hris_countries")->select_expr("count(country_uid)", "count")->where("name", $name)->find_result_set();
        $status = false;
        if($query == 1){
            $status = true;
        }
        return $status;
}

function newCountry($countryUid , $code , $name , $iso , $numCode , $dateCreated , $dateModified){
    if(!countryIsExisting($name)){
        $query = ORM::forTable("hris_countries")->create();
            $query->country_uid = $countryUid;
            $query->code = $code;
            $query->name = $name;
            $query->iso = $iso;
            $query->num_code = $numCode;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
        $query->save();
            return 0;
    }else{
        $country = getCountryUidAndStatus($name);
        if($country->status == 0){
            updateCountryById($country->country_uid , $code , $name , $iso , $numCode , $dateModified , 1);
            return 1;
        }else{
            return 2;
        }
    }    
}

function getCountryUidAndStatus($name){
    $query = ORM::forTable("hris_countries")->select("country_uid")->select("status")->where("name", $name)->findOne();
        return $query;
}

function updateCountryById($countryUid , $code , $name , $iso , $numCode , $dateModified , $status){
    $query = ORM::forTable("hris_countries")->where("country_uid", $countryUid)->findOne();
        $query->set("status", $status);
        $query->set("code", $code);
        $query->set("name", $name);
        $query->set("iso", $iso);
        $query->set("num_code", $numCode);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function getCountryByUid($countryUid){
   $query = ORM::forTable("hris_countries")->where("country_uid", $countryUid)->findOne();
        return $query;
}

function countriesCount($name){
    $query = ORM::forTable("hris_countries")->select_expr("count(country_uid)", "count")->where("name", $name)->find_result_set();
        return $query;
}

/*-------------------------------------Country ENd------------------------------------*/

/*-------------------------------------Organization------------------------------------*/

function getGeneralInformation() {
    $query = ORM::forTable("hris_gen_info")->where("status", "1")->findOne();
    return $query;
}

function generalInformationIsExisting() {
    $query = ORM::forTable("hris_gen_info")->select_expr("count(gen_info_uid)", "count")->where("status", 1)->findOne();
        if($query->count >=1){
            $status = true;
        }else{
            $status = false;
        }
        return $status;
}

function newGeneralInformation($generalInformationUid , $organizationName , $taxId , $registrationNumber , $phone , $fax , $email , $address1 , $address2 , $city , $state , $zipCode , $country , $note , $dateCreated , $dateModified) {
    $query = ORM::forTable("hris_gen_info")->create();
        $query->gen_info_uid = $generalInformationUid;
        $query->name = $organizationName;
        $query->tax_id = $taxId;
        $query->registration_number = $registrationNumber;
        $query->phone = $phone;
        $query->fax = $fax;
        $query->email = $email;
        $query->country = $country;
        $query->province = $state;
        $query->city = $city;
        $query->zip_code = $zipCode;
        $query->street_1 = $address1;
        $query->street_2 = $address2;
        $query->note = $note;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
        return 0;
}

function updateGeneralInformation($generalInformationUid , $organizationName , $taxId , $registrationNumber , $phone , $fax , $email , $address1 , $address2 , $city , $state , $zipCode , $country , $note , $dateModified) {
    $query = ORM::forTable("hris_gen_info")->where("gen_info_uid", $generalInformationUid)->findOne();
        $query->set("name", $organizationName);
        $query->set("tax_id", $taxId);
        $query->set("registration_number", $registrationNumber);
        $query->set("phone", $phone);
        $query->set("fax", $fax);
        $query->set("email", $email);
        $query->set("country", $country);
        $query->set("province", $state);
        $query->set("city", $city);
        $query->set("zip_code", $zipCode);
        $query->set("street_1", $address1);
        $query->set("street_2", $address2);
        $query->set("note", $note);
        $query->set("date_modified", $dateModified);
    $query->save();
}


/*-------------------------------------Organization ENd------------------------------------*/

/*-------------------------------------Location------------------------------------*/

function getLocations() {
    $query = ORM::forTable("hris_location")->where("status", 1)->findMany();
        return $query;
}

function locationCount($startTime){
    $query = ORM::forTable("hris_location")->select_expr("count(shift_uid)", "count")->where("name", $name)->find_result_set();
        return $query;
}

function getPaginatedLocation() {
    $query = ORM::forTable("hris_location")->findMany();
        return $query;
}

function locationIsExisting($name){
    $query = ORM::forTable("hris_location")->select_expr("count(location_uid)", "count")->where("name", $name)->findOne();
        $status = false;
        if($query->count == 1){
            $status = true;
        }
        return $status;
}

function newLocation($locationUid , $name , $country , $province , $city , $address , $zipCode , $phone , $tax , $fax , $notes , $dateCreated , $dateModified){
    if(!locationIsExisting($name)){
        $query = ORM::forTable("hris_location")->create();
            $query->location_uid = $locationUid;
            $query->name = $name;
            $query->country_uid = $country;
            $query->province = $province;
            $query->city = $city;
            $query->address = $address;
            $query->zip_code = $zipCode;
            $query->phone = $phone;
            $query->tax = $tax;
            $query->fax = $fax;
            $query->notes = $notes;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
        $query->save();
            return 0;
    }else{
        $location = getLocationUidAndStatus($name);
        if($location->status == 0){
            updateLocationById($location->location_uid , $name , $country , $province , $city , $address , $zipCode , $phone , $tax , $fax , $notes , $dateModified , 1);
            return 1;
        }else{
            return 2;
        }
    }    
}

function getLocationUidAndStatus($name){
    $query = ORM::forTable("hris_location")->select("location_uid")->select("status")->where("name", $name)->find_result_set();
        return $query;
}

function updateLocationById($locationUid , $name , $country , $province , $city , $address , $zipCode , $phone , $tax , $fax , $notes , $dateModified , $status){
    $query = ORM::forTable("hris_location")->where("location_uid", $locationUid)->findOne();
        $query->set("status", $status);
        $query->set("name", $name);
        $query->set("country_uid", $country);
        $query->set("province", $province);
        $query->set("city", $city);
        $query->set("address", $address);
        $query->set("zip_code", $zipCode);
        $query->set("phone", $phone);
        $query->set("tax", $tax);
        $query->set("fax", $fax);
        $query->set("notes", $notes);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function getLocationByUid($locationUid){
   $query = ORM::forTable("hris_location")->where("location_uid", $locationUid)->findOne();
        return $query;
}

function updateLocationStatusByUid($locationUid,$dateModified,$status) {
    $query = ORM::forTable("hris_location")->where("location_uid", $locationUid)->findOne();
        $query->set("status", $status);
        $query->set("date_modified", $dateModified);
    $query->save();
}


/*---------------------------------------------Location End---------------------------------*/

/*---------------------------------------------Sub unit---------------------------------*/

function displayChildren($parent , $level) {
    $query = ORM::forTable("hris_subunits")->where("parent", $parent)->where("status", 1)->findMany(); 
        $response = array();
        if($query){
            foreach ($query as $subunit) {
                $response[] = array(
                    "subunitUid" => $subunit->subunit_uid,
                    "parent" => $subunit->parent,
                    "name" => $subunit->name,
                    "unitId" => $subunit->unit_id,
                    "description" => $subunit->description,
                    "name" => $subunit->name,
                    "lft" => $subunit->lft,
                    "rgt" => $subunit->rgt,
                    "level" => $level
                );
                $response = array_merge(displayChildren($subunit->name , $level+1), $response);
            }
        }
        return $response;
}

function getSubunitsMain(){
    $query = ORM::forTable("hris_subunits")->select("name")->where("parent", "")->limit(1)->findOne();
        return $query;
}

function getSubunits(){
    $query = ORM::forTable("hris_subunits")->where("status","1")->findMany();
        return $query;
}


function getSubunit($uid){
    $query = ORM::forTable("hris_subunits")->where("subunit_uid", $uid)->findOne();
        return $query;
}

function updateLftSubunit($lft){
    $query = ORM::forTable("hris_subunits")->whereGt("lft", $lft)->findOne();
        $query->set("lft", $lft+2);
    $query->save();
}

function updateRgtSubunit($rgt){
    $query = ORM::forTable("hris_subunits")->whereGt("rgt", $rgt)->findOne();
        $query->set("rgt", $rgt+2);
    $query->save();
}

function insertSubunit($subunitUid , $parent , $name , $unitId , $description , $lft , $dateCreated , $dateModified){
    $query = ORM::forTable("hris_subunits")->create();
        $query->subunit_uid =$subunitUid;
        $query->parent =$parent;
        $query->name =$name;
        $query->unit_id =$unitId;
        $query->description =$description;
        $query->lft =$lft + 1;
        $query->rgt =$lft + 2;
        $query->date_created =$dateCreated;
        $query->date_modified =$dateModified;
    $query->save();
        return 0;
}

function rebuildTree($parent, $lft) {   
    $rgt = $lft+1;   
    $query = ORM::forTable("hris_subunits")->where("parent", $parent)->where("status", 1)->findMany();   
        if($query){
            foreach ($query as $subunit) {
                $rgt = rebuildTree($subunit->name, $rgt);
            }
        }
        updateLftAndRgtSubunit($lft , $rgt , $parent);
        return $rgt + 1;
}

function updateLftAndRgtSubunit($lft , $rgt , $parent){
    $query = ORM::forTable("hris_subunits")->where("name", $parent)->findOne();
        $query->set("lft", $lft);
        $query->set("rgt", $rgt);
    $query->save();
}

function subUnitIsExisting($name){
    $query = ORM::forTable("hris_subunits")->select_expr("count(subunit_uid)", "count")->where("name", $name)->findOne();
        $status = false;
        if($query->count == 1){
            $status = true;
        }
        return $status;
}

function subUnitCount($name){
    $query = ORM::forTable("hris_subunits")->select_expr("count(subunit_uid)", "count")->where("name", $name)->findOne();
        return $query;
}

function getSubunitByName($name){
    $query = ORM::forTable("hris_subunits")->where("name", $name)->findOne();
        return $query;
}

function deleteSubunitBetweenLftAndRgt($lft , $rgt , $dateModified , $status){
    $query = ORM::forTable("hris_subunits")->whereRaw("('lft' BETWEEN $lft AND $rgt)")->findOne();
        $query->set("status", $status);
        $query->set("lft", "null");
        $query->set("rgt", "null");
        $query->set("date_modified", $dateModified);
    $query->save();
}

function updateSubunitById($subunitUid , $name , $unitId , $description , $lft , $rgt , $parent , $dateModified , $status){
    $query = ORM::forTable("hris_subunits")->where("subunit_uid", $subunitUid)->findOne();
        $query->set("status", $status);
        $query->set("name", $name);
        $query->set("unit_id", $unitId);
        $query->set("description", $description);
        $query->set("lft", $lft);
        $query->set("rgt", $rgt);
        $query->set("parent", $parent);
        $query->set("date_modified", $dateModified);
    $query->save();
}

// function newSubunit($subUnitUid , $name , $unitId , $description , $lft , $rgt , $level , $dateCreated , $dateModified){
//     if(!subUnitIsExisting($name)){
//         $query = "INSERT INTO hris_subunits(subunit_uid,name,unit_id,description,lft,rgt,level,date_created,date_modified) VALUES(:subUnitUid,:name,:unitId,:description,:lft,:rgt,:level,:dateCreated,:dateModified)";
//         try {
//             $db = getConnection();
//             $statement = $db->prepare($query);
//             $statement->execute(array(
//                 ":subUnitUid" => $subUnitUid,
//                 ":name" => $name,
//                 ":unitId" => $unitId,
//                 ":description" => $description,
//                 ":lft" => $lft,
//                 ":rgt" => $rgt,
//                 ":level" => $level,
//                 ":dateCreated" => $dateCreated,
//                 ":dateModified" => $dateModified
//             ));
//             return 0;
//         }catch (PDOException $e) {
//             echo '{"error":{"text":newSubunit ' . $e->getMessage() . '}}';
//         }
//     }else{
//         $subunit = getSubunitByName($name);
//         if($subunit->status == 0){
//             updateSubunitById($subunit->subunit_uid , $name , $unitId , $description , $lft , $rgt , $level , $dateModified , 1);
//             return 1;
//         }else{
//             return 2;
//         }
//     }    
// }





function getSubunitByUid($subunitUid){
    $query = ORM::forTable("hris_subunits")->where("subunit_uid", $subunitUid)->findOne();
        return $query;
}



/*---------------------------------------------sub Unit End---------------------------------*/
/*---------------------------------------------Employee Job End---------------------------------*/

function getPaginatedEmployeeJob($empUid) {
    $query = ORM::forTable("hris_employee_job")->where("status", 1)->where("emp_uid", $empUid)->findMany();
        return $query;
}

function newEmployeeJob($empJobUid , $jobTitle , $jobCategory , $subunit , $location , $employmentStatus , $empUid , $startDate , $endDate , $dateCreated , $dateModified){
    $query = ORM::forTable("hris_employee_job")->create();
        $query->employee_job_uid = $empJobUid;
        $query->job_uid = $jobTitle;
        $query->job_category_uid = $jobCategory;
        $query->subunit_uid = $subunit;
        $query->location_uid = $location;
        $query->employment_status_uid = $employmentStatus;
        $query->emp_uid = $empUid;
        $query->start_date = $startDate;
        $query->end_date = $endDate;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getEmployeeJobByUid($employeeJobUid){
    $query = ORM::forTable("hris_employee_job")->where("employee_job_uid", $employeeJobUid)->findOne();
        return $query;
}

function updateEmployeeJobById($empJobUid , $jobTitle , $jobCategory , $subunit , $location , $employmentStatus , $startDate , $endDate , $dateExtended , $dateModified , $status){
    $query = ORM::forTable("hris_employee_job")->where("employee_job_uid", $empJobUid)->findOne();
        $query->set("job_uid", $jobTitle);
        $query->set("job_category_uid", $jobCategory);
        $query->set("subunit_uid", $subunit);
        $query->set("location_uid", $location);
        $query->set("employment_status_uid", $employmentStatus);
        $query->set("start_date", $startDate);
        $query->set("end_date", $endDate);
        $query->set("date_extended", $dateExtended);
        $query->set("status", $status);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function updateEmployeeJobStatusByUid($empJobUid,$dateModified,$status){
    $query = ORM::forTable("hris_employee_job")->where("employee_job_uid", $empJobUid)->findOne();
        $query->set("status", $status);
        $query->set("date_modified", $dateModified);
    $query->save();
}

// function getExemption(){
//     $query = ORM::forTable("hris_exemption")->findMany();
//     return $query;
// }

function getExemption($frequencyUid){
    $query = ORM::forTable("hris_exemption")->where("frequency_uid", $frequencyUid)->findMany();
    return $query;
}

function getAllTax($frequencyUid){
    $query = ORM::forTable("tax")->where("frequency_uid", $frequencyUid)->findMany();
    return $query;
}

function getTax($frequencyUid){
    $query = ORM::forTable("tax")
    ->rawQuery("SELECT e_id as id1, NULL as id2, exemption, NULL as dep_status ,status, NULL as no_dep_1,NULL as no_dep_2, NULL as no_dep_3, NULL as no_dep_4, NULL as no_dep_5, NULL as no_dep_6, NULL as no_dep_7, NULL as no_dep_8 FROM hris_exemption as t1 WHERE t1.frequency_uid = :frequencyUid UNION ALL SELECT NULL, t_id, NULL ,no_dep_status, NULL ,no_dep_1, no_dep_2, no_dep_3, no_dep_4, no_dep_5, no_dep_6, no_dep_7, no_dep_8 FROM tax as t2 WHERE t2.frequency_uid=:frequencyUid", array("frequencyUid" => $frequencyUid))
    ->findMany();
    return $query;
}


function getAllExemptions($frequencyUid) {
    $query = ORM::forTable("hris_exemption")
        ->where("frequency_uid", $frequencyUid)
        ->findMany();

    return $query;
}

function updateExemption($exemptUid, $taxExemp, $taxStat){

    $query = ORM::forTable("hris_exemption")
        ->where("exemption_uid", $exemptUid)
        ->findOne();
        $query->set("exemption", $taxExemp);
        $query->set("status", $taxStat);
        $query->save();
}

function getExemptionUid($frequencyUid) {
    $response = array();
    $ids = ORM::forTable("hris_exemption")
        ->where("frequency_uid", $frequencyUid)
        ->findMany();

    foreach($ids as $id) {
        $response[] = array(
            "exemptionUid" => $id->exemption_uid
        );
    }
    return $response;
}

function getTaxByFreqUid($frequencyUid){
    $response = array();
    $query = ORM::forTable("tax")->where("frequency_uid", $frequencyUid)->findMany();
    foreach($query as $tax){
        $response[] = array(
            "tax_uid" => $tax->tax_uid
        );
    }
    return $response;
}
function updateTax($taxUid, $taxStatus, $taxOne, $taxTwo, $taxThree, $taxFour, $taxFive, $taxSix, $taxSeven, $taxEight){
    $query = ORM::forTable("tax")->where("tax_uid", $taxUid)->findOne();
        $query->set("no_dep_status", $taxStatus);
        $query->set("no_dep_1", $taxOne);
        $query->set("no_dep_2", $taxTwo);
        $query->set("no_dep_3", $taxThree);
        $query->set("no_dep_4", $taxFour);
        $query->set("no_dep_5", $taxFive);
        $query->set("no_dep_6", $taxSix);
        $query->set("no_dep_7", $taxSeven);
        $query->set("no_dep_8", $taxEight);
    $query->save();
}

/*---------------------------------------------Employee Job End---------------------------------*/
function getPayGradeUidBySalary($salary) {
    $query = ORM::forTable("paygrade")->select("paygrade_uid")->where("status", 1)->whereLt("minimum", $salary)->whereLt("maximum", $salary)->findOne();
        return $query;
}

function getCurrencyUidByValue($currency) {
    $query = ORM::forTable("currency")->select("currency_uid")->where("status", 1)->where("name", $currency)->find_result_set();
        return $query;
}

function getPayPeriodUidByValue($payPeriod) {
    $query = ORM::forTable("pay_period")->select("pay_period_uid")->where("status", 1)-> where("frequency", $payPeriod)->find_result_set();
        return $query;
}

function getWorkDayPerMonth(){
    $query = ORM::forTable("work_day")->where("status", 1)->limit(1)->findOne();
    return $query->work_day_per_month;
}
function countDurationPerDay($id, $in, $out, $ins){
    $query = ORM::forTable("time_log")
        ->rawQuery("SELECT CONCAT(MOD(HOUR(TIMEDIFF(:inss, :outs)), 24), ':',MINUTE(TIMEDIFF(:inss, :outs))) as time FROM time_log WHERE date(date_created) = :ins AND emp_uid = :id AND status = 1 LIMIT 1", array("inss" => $in, "outs" => $out, "ins" => $ins, "id" => $id))->findOne();
    return $query->time;
}

function countInSaShift($id, $in, $shiftStart, $empDate){
    $query = ORM::forTable("time_log")
        ->rawQuery("SELECT CONCAT(MOD(HOUR(TIMEDIFF(:inss, :outs)), 24), ':',MINUTE(TIMEDIFF(:inss, :outs))) as time FROM time_log WHERE date(date_created) = :ins AND emp_uid = :id AND status = 1 LIMIT 1", array("inss" => $in, "outs" => $shiftStart, "ins" => $empDate, "id" => $id))->findOne();
    return $query->time;
}

function countUndertimePerDay($id, $out1, $out2, $date){
    $query = ORM::forTable("time_log")
        ->rawQuery("SELECT CONCAT(MOD(HOUR(TIMEDIFF(:out1, :out2)), 24), ':',MINUTE(TIMEDIFF(:out1, :out2))) as time FROM time_log WHERE date_created LIKE CONCAT(:date, '%') AND emp_uid = :id AND status = 1 LIMIT 1", array("out1" => $out1, "out2" => $out2, "date" => $date, "id" => $id))->findOne();
    return $query->time;
}

function countLatePerDay($id, $in1, $in2, $date){
    $query = ORM::forTable("time_log")
        ->rawQuery("SELECT CONCAT(MOD(HOUR(TIMEDIFF(:in1, :in2)), 24), ':',MINUTE(TIMEDIFF(:in1, :in2))) as time FROM time_log WHERE date_created LIKE CONCAT(:date, '%') AND emp_uid = :id AND status = 1 LIMIT 1", array("in1" => $in1, "in2" => $in2, "date" => $date, "id" => $id))->findOne();
    return $query->time;
}

function countDate($id, $date){
    $query = ORM::forTable("time_log")
        ->whereRaw("date(date_created) = :dates AND emp_uid = :id AND type = 0 AND status = 1", array("dates" => $date, "id" => $id))
        ->limit(1)
        ->count();
    return $query;
}
function countDateOut($id, $date){
    $query = ORM::forTable("time_log")
        ->whereRaw("date(date_created) = :dates AND emp_uid = :id AND type = 1 AND status = 1", array("dates" => $date, "id" => $id))
        ->limit(1)
        ->count();
    return $query;
}


function countDurationOfShifts($id, $date, $day){
    $query = ORM::forTable("shift")
        ->rawQuery("SELECT HOUR(TIMEDIFF(t1.end, t1.start)) as time FROM shift as t1 INNER JOIN time_log as t4 ON t1.shift_uid = t4.shift_uid WHERE t4.emp_uid = :id AND t4.type = 0 AND date(t4.date_created) = :dates AND t4.status = 1 LIMIT 1", array("id" => $id, "dates" => $date))
        ->findOne();
    return $query->time;
}

function countDurationOfShiftsByDayAndEmpUid($id, $date){
    $query = ORM::forTable("shift")
        ->rawQuery("SELECT HOUR(TIMEDIFF(t1.end, t1.start)) as time FROM shift as t1 INNER JOIN time_log as t2 ON t1.shift_uid = t2.shift_uid WHERE t2.emp_uid = :id AND date(t2.date_created) = :dates AND t2.status = 1 LIMIT 1", array("id" => $id, "dates" => $date))
        ->findOne();
    return $query->time;
}

function countDurationOfShiftsReversed($id, $start, $end, $day, $date){
    $query = ORM::forTable("shift")
        ->rawQuery("SELECT HOUR(TIMEDIFF(:end, :start)) as time FROM shift LIMIT 1", array("start" => $start, "end" => $end))
        ->findOne();
    return $query->time;
}

function countDurationOfShiftsReversedOffset($id, $start, $end, $day){
    $query = ORM::forTable("shift")
        ->rawQuery("SELECT HOUR(TIMEDIFF(:end, :start)) as time FROM shift LIMIT 1", array("start" => $start, "end" => $end))
        ->findOne();
    if($query){
        return $query->time;
    }else{
        return false;
    }
}

function getEmpShiftByUid($id){
    $query = ORM::forTable("emp_shift")->tableAlias("t1")->innerJoin("shift", array("t1.shift_uid", "=", "t2.shift_uid"), "t2")->where("t1.emp_uid", $id)->findOne();
    return $query;
}

function getShiftByUidAndDate($id, $date, $day){
    $query = ORM::forTable("shift")
        ->rawQuery("SELECT * FROM shift as t1 INNER JOIN time_log as t4 ON t1.shift_uid = t4.shift_uid WHERE t4.emp_uid = :id AND t4.type = 0 AND date(t4.date_created) = :dates AND t4.status = 1", array("id" => $id, "dates" => $date))
        ->findOne();
    return $query;
}

function getShiftByDayAndEmpUid($id, $date){
    $query = ORM::forTable("shift")
        ->rawQuery("SELECT * FROM shift as t1 INNER JOIN time_log as t2 ON t1.shift_uid = t2.shift_uid WHERE t2.emp_uid = :id AND date(t2.date_created) = :dates AND t2.status = 1", array("id" => $id, "dates" => $date))
        ->findOne();
    return $query;
}

function getShiftByUidAndDay($id, $date){
    $query = ORM::forTable("shift")
        ->rawQuery("SELECT * FROM shift as t1 INNER JOIN time_log as t2 ON t1.shift_uid = t2.shift_uid WHERE t2.emp_uid = :id AND date(t2.date_created) = :dates AND t2.status = 1", array("id" => $id,"dates" => $date))

        ->findOne();
    return $query;
}

function getOffsetShiftByUidAndDay($id, $day){
    $query = ORM::forTable("shift")
        ->rawQuery("SELECT * FROM shift as t1 INNER JOIN rules as t2 ON t1.shift_uid = t2.shift_uid INNER JOIN rule_assignment as t3 ON t2.rule_uid = t3.rule_uid WHERE t3.emp_uid = :id AND t2.day = :day AND t3.status = 1", array("id" => $id,"day" => $day))
        ->findOne();
    return $query;
}

function countShiftByUidAndDay($id, $day){
    $query = ORM::forTable("shift")
        ->rawQuery("SELECT HOUR(TIMEDIFF(t1.end, t1.start)) as time FROM shift as t1 INNER JOIN rules as t2 ON t1.shift_uid = t2.shift_uid INNER JOIN rule_assignment as t3 ON t2.rule_uid = t3.rule_uid WHERE t3.emp_uid = :id AND t2.day = :day AND t3.status = 1", array("id" => $id,"day" => $day))
        ->findOne();
    return $query->time;
}

// function getShiftInRules($id, $date){
//     $query = ORM::forTable("rules")
//         ->rawQuery("SELECT * FROM rules as t1 INNER JOIN rule_assignment as t2 ON t1.rul");
// }

function getTimeInByDateAndUid($date, $uid){
    $query = ORM::forTable("time_log")
        ->rawQuery("SELECT * FROM time_log WHERE emp_uid = :uid AND date_created LIKE CONCAT('%', :dates, '%') AND type = '0' AND status ='1'", array("uid" => $uid, "dates" => $date))
        ->findOne();

    return $query;
}

function getTimeLogOutByEmpUidAndSession($uid, $session){
    $query = ORM::forTable("time_log")
        ->rawQuery("SELECT * FROM time_log WHERE emp_uid = :uid AND session = :session AND type = 1 AND status = 1 ORDER BY id ASC LIMIT 1", array("uid" => $uid, "session" => $session))
        ->findOne();
    return $query;
}

function getTimeSessionEmpUidAndDate($uid, $startDate){
    $query = ORM::forTable("time_log")
        ->rawQuery("SELECT * FROM time_log WHERE emp_uid = :uid AND date(date_created) = :dates AND type = 0 AND status = 1 ORDER BY id ASC LIMIT 1", array("uid" => $uid, "dates" => $startDate))
        ->findOne();
    return $query;
}

function getTimeOutByDateAndUid($date, $uid){
    $query = ORM::forTable("time_log")
        ->rawQuery("SELECT * FROM time_log WHERE emp_uid = :uid AND date_created LIKE CONCAT('%', :dates, '%') AND type = '1' AND status ='1'", array("uid" => $uid, "dates" => $date))
        ->findOne();

    return $query;
}

function getTimeLogInByEmpAndDate($emp, $date){
    $query = ORM::forTable("time_log")
        ->rawQuery("SELECT t1.date_created, t2.name FROM time_log as t1 INNER JOIN shift as t2 ON t1.shift_uid = t2.shift_uid WHERE t1.emp_uid = :emp AND date(t1.date_created) = :dates AND t1.status = 1 AND t1.type = '0'", array("emp" => $emp, "dates" => $date))
        ->findOne();
    return $query;
}

function getTimeLogOutByEmpAndDate($emp, $date){
    $query = ORM::forTable("time_log")
        ->rawQuery("SELECT * FROM time_log WHERE emp_uid = :emp AND date(date_created) = :dates AND status = 1 AND type = '1'", array("emp" => $emp, "dates" => $date))
        ->findOne();
    return $query;
}

function getTotalWork($in, $out, $id){
    $query = ORM::forTable("time_log")
        ->rawQuery("SELECT CONCAT(MOD(HOUR(TIMEDIFF(:ins, :out)),24), ':', MINUTE(TIMEDIFF(:ins, :out)), ':00') as time FROM time_log WHERE emp_uid = :id LIMIT 1", array("ins" => $in, "out" => $out, "id" => $id))
        ->findOne();
    return $query->time;
}

function checkCostCenterIfExists($name){
    $query = ORM::forTable("cost_center")->where("cost_center_name", $name)->where("status", 1)->count();
    $valid = false;

    if($query >= 1){
        $valid = true;
    }

    return $valid;
}

function getCostcenter(){
    $query = ORM::forTable("cost_center")->tableAlias("t1")->innerJoin("pay_period", array("t1.pay_period_uid", "=", "t2.pay_period_uid"), "t2")->where("t1.status", 1)->findMany();
    return $query;
}

function getEmpCostCenterDataByEmpUid($uid){
    $query = ORM::forTable("emp_cost_center")->tableAlias("t1")
        ->innerJoin("cost_center", array("t1.cost_center_uid", "=", "t2.cost_center_uid"), "t2")
        ->where("t1.emp_uid", $uid)
        ->where("t1.status", 1)
        ->findMany();
    return $query;
}

function getSingleCostCenterDataByEmpUid($uid){
    $query = ORM::forTable("emp_cost_center")->tableAlias("t1")
        ->innerJoin("cost_center", array("t1.cost_center_uid", "=", "t2.cost_center_uid"), "t2")
        ->innerJoin("emp", array("t1.emp_uid", "=", "t3.emp_uid"), "t3")
        ->innerJoin("users", array("t3.emp_uid", "=", "t4.emp_uid"), "t4")
        ->where("t1.emp_uid", $uid)
        ->where("t1.status", 1)
        ->findOne();
    return $query;
}

function getEmpCostCenterDataByUid($uid){
    $query = ORM::forTable("emp_cost_center")->tableAlias("t1")
        ->innerJoin("cost_center", array("t1.cost_center_uid", "=", "t2.cost_center_uid"), "t2")
        ->where("t1.emp_cost_center_uid", $uid)
        ->where("t1.status", 1)
        ->findOne();
    return $query;
}

function getEmployeeByCostCenterUid($uid){
    $query = ORM::forTable("emp_cost_center")->tableAlias("t1")
        ->innerJoin("cost_center", array("t1.cost_center_uid", "=", "t2.cost_center_uid"), "t2")
        ->innerJoin("users", array("t1.emp_uid", "=", "t3.emp_uid"), "t3")
        ->innerJoin("emp", array("t3.emp_uid", "=", "t4.emp_uid"), "t4")
        ->where("t1.cost_center_uid", $uid)
        ->where("t1.status", 1)
        ->where("t2.status", 1)
        ->where("t3.status", 1)
        ->where("t4.status", 1)
        ->findMany();
    return $query;
}

function getCostcenterDataByUid($uid){
    $query = ORM::forTable("cost_center")->tableAlias("t1")->innerJoin("pay_period", array("t1.pay_period_uid", "=", "t2.pay_period_uid"), "t2")->where("t1.cost_center_uid", $uid)->where("t1.status", 1)->findOne();
    return $query;
}

function addNewCostCenter($costUid, $name, $desc, $payperiod, $dateCreated, $dateModified){
    $query = ORM::forTable("cost_center")->create();
        $query->cost_center_uid = $costUid;
        $query->cost_center_name = $name;
        $query->description = $desc;
        $query->pay_period_uid = $payperiod;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function updateCostCenter($uid, $name, $desc, $payperiod, $dateModified, $status){
    $query = ORM::forTable("cost_center")->where("cost_center_uid", $uid)->where("status", 1)->findOne();
        $query->set("cost_center_name", $name);
        $query->set("description", $desc);
        $query->set("pay_period_uid", $payperiod);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}
function countEmpSetCostCenter($uid){
    $query = ORM::forTable("emp_cost_center")->where("emp_uid", $uid)->count();
    $valid = false;
    if($query >= 1){
        $valid = true;
    }

    return $valid;
}

function setEmpCostCenter($costUid, $costcenter, $empUid, $dateCreated, $dateModified){
    $query = ORM::forTable("emp_cost_center")->create();
        $query->emp_cost_center_uid = $costUid;
        $query->cost_center_uid = $costcenter;
        $query->emp_uid = $empUid;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function updateEmpCostCenter($uid, $costcenter, $dateModified, $status){
    $query = ORM::forTable("emp_cost_center")->where("emp_cost_center_uid", $uid)->findOne();
        $query->set("cost_center_uid", $costcenter);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}
/*------------------------------ PAYROLL -----------------------------*/

function getAllEmployeeData(){
    $query = ORM::forTable("emp")->tableAlias("t1")->innerJoin("users", array("t1.emp_uid", "=", "t2.emp_uid"), "t2")->whereNotEqual("t2.username", "0001")->where("t1.status", "1")->orderByAsc("t1.lastname")->findMany();
    return $query;
}

function getAllEmployeeSalaryData(){
    $query = ORM::forTable("emp")
    ->rawQuery("SELECT * FROM emp as t2 INNER JOIN salary as t3  ON t2.emp_uid = t3.emp_uid  WHERE t2.status=1  AND t3.status=1")
    ->findMany();
    return $query;
}

function getEmployeeSalaryDataByEmpUid($id){
    $query = ORM::forTable("emp")
    ->rawQuery("SELECT * FROM emp as t2 INNER JOIN salary as t3  ON t2.emp_uid = t3.emp_uid  WHERE t2.status=1 AND t3.status=1 AND t2.emp_uid = :id", array("id" => $id))
    ->findOne();
    return $query;
}

function getAllEmployeeDependentData(){
    $query = ORM::forTable("emp_dependent")
    ->rawQuery("SELECT * FROM emp_dependent as t1 LEFT OUTER JOIN emp as t2 ON t1.emp_uid = t2.emp_uid INNER JOIN salary as t3 ON t2.emp_uid = t3.emp_uid WHERE t1.status=1 AND t2.status=1 AND t3.status=1")
    ->findMany();
    return $query;
}

function getAllEmployeeDependentBday($id){
    $query = ORM::forTable("emp_dependent")
    ->rawQuery("SELECT t2.emp_uid, t1.bday, t1.emp_dependent_uid FROM emp_dependent as t1 INNER JOIN emp as t2 ON t1.emp_uid=t2.emp_uid WHERE t1.emp_uid = :id AND t1.status=1 AND t2.status=1", array("id" => $id))
    ->findMany();

    return $query;
}

function getAllEmployeeDependentDataByEmpUid($id){
    $query = ORM::forTable("emp_dependent")
    ->rawQuery("SELECT * FROM emp_dependent as t1 LEFT OUTER JOIN emp as t2 ON t1.emp_uid = t2.emp_uid INNER JOIN salary as t3 ON t2.emp_uid = t3.emp_uid WHERE t1.status=1 AND t2.status=1 AND t3.status=1 AND t1.emp_uid = :id", array("id" => $id))
    ->findMany();
    return $query;
}

function getTimelogTesting($startDate, $endDate){
    $query = ORM::forTable("time_log")->
    rawQuery("SELECT * FROM time_log as t1 INNER JOIN emp as t2 ON t1.emp_uid=t2.emp_uid WHERE date(t1.date_created) >= :startDate AND date(t1.date_created) <= :endDate AND t1.status='1' GROUP BY t2.lastname ORDER BY t2.lastname ASC", array("startDate" => $startDate, "endDate" => $endDate))->findMany();
    return $query;
}

function getTimelogByEmpUid($id, $startDate, $endDate){
    $query = ORM::forTable("time_log")->
    rawQuery("SELECT * FROM time_log as t1 INNER JOIN emp as t2 ON t1.emp_uid=t2.emp_uid WHERE t1.date_created BETWEEN :startDate AND :endDate AND t1.status='1' AND t1.emp_uid = :id GROUP BY t2.lastname ORDER BY t2.lastname ASC", array("startDate" => $startDate, "endDate" => $endDate, "id" => $id))->findMany();
    return $query;
}

function getFirstEmpDateAll($startDate){
    $first = ORM::forTable("time_log")
    ->rawQuery("SELECT t1.date_created, t1.emp_uid FROM time_log as t1 INNER JOIN emp as t2 ON t1.emp_uid=t2.emp_uid WHERE t1.date_created >= :startDate AND t1.type='0' AND t1.status='1' GROUP BY t2.lastname ORDER BY t2.lastname ASC", array("startDate" => $startDate))
    ->findMany();
    return $first;
}

function getFirstEmpDateByEmpUid($id, $startDate){
    $first = ORM::forTable("time_log")
    ->rawQuery("SELECT t1.date_created, t1.emp_uid FROM time_log as t1 INNER JOIN emp as t2 ON t1.emp_uid=t2.emp_uid WHERE t1.date_created >= :startDate AND t1.type='0' AND t1.status='1' AND t1.emp_uid = :id GROUP BY t2.lastname ORDER BY t2.lastname ASC", array("startDate" => $startDate, "id" => $id))
    ->findOne();
    return $first;
}

function getLastEmpDateAll($endDate){
    $end = ORM::forTable("time_log")
    ->rawQuery("SELECT MAX(t1.date_created) as date_created, t1.emp_uid FROM time_log as t1 INNER JOIN emp as t2 ON t1.emp_uid=t2.emp_uid WHERE t1.date_created <= :endDate AND t1.type='1' AND t1.status='1' GROUP BY t2.lastname ORDER BY t2.lastname ASC", array("endDate" => $endDate))
    ->findMany();
    return $end;
}

function getLastEmpDateByEmpUid($id, $endDate){
    $end = ORM::forTable("time_log")
    ->rawQuery("SELECT MAX(t1.date_created) as date_created, t1.emp_uid FROM time_log as t1 INNER JOIN emp as t2 ON t1.emp_uid=t2.emp_uid WHERE t1.date_created <= :endDate AND t1.type='1' AND t1.status='1' AND t1.emp_uid = :id GROUP BY t2.lastname ORDER BY t2.lastname ASC", array("endDate" => $endDate, "id" => $id))
    ->findOne();
    return $end;
}

function getEmpTimesAll($emp, $startDate){
    $time = ORM::forTable("time_log")
    ->rawQuery("SELECT t1.time, t1.emp_uid FROM time_log as t1 INNER JOIN emp as t2 ON t1.emp_uid=t2.emp_uid WHERE t1.date_created >= :startDate AND t1.status='1' ORDER BY t2.lastname ASC, t1.date_created ASC", array("startDate" => $startDate))
    ->findMany();
    return $time;
}

function getEmpTimesByEmpUid($id, $startDate){
    $time = ORM::forTable("time_log")
    ->rawQuery("SELECT t1.date_created, t1.emp_uid FROM time_log as t1 INNER JOIN emp as t2 ON t1.emp_uid=t2.emp_uid WHERE t1.date_created >= :startDate AND t1.status='1' AND t1.emp_uid = :id ORDER BY t2.lastname ASC, t1.date_created ASC", array("startDate" => $startDate, "id" => $id))
    ->findOne();
    return $time;
}

function getTimeInByEmpUid($id, $startDate, $endDate){
    $query = ORM::forTable("time_log")
    ->rawQuery("SELECT date_created, emp_uid FROM time_log WHERE emp_uid = :id AND type='0'  AND status='1' AND date_created BETWEEN :startDate AND :endDate", array("id" => $id, "startDate" => $startDate, "endDate" => $endDate))
    ->findMany();

    return $query;
}

function getClockIn($id, $time){
    $query = ORM::forTable("time_log")
    ->rawQuery("SELECT date_created, emp_uid, COUNT(date_created) as count FROM time_log WHERE emp_uid = :id AND type='0' AND status='1' AND date_created = :times AND date_created IS NOT NULL", array("id" => $id, "times" => $time))
    ->findOne();

    if($query->count >= 1){
        $query1 = $query;
    }else{
        $query1 = 0;
    }

    return $query1;
}

function getClockOut($id, $time){
    $query = ORM::forTable("time_log")
    ->rawQuery("SELECT date_created, emp_uid, COUNT(date_created) as count FROM time_log WHERE emp_uid = :id AND type='1' AND status='1' AND date_created = :times AND date_created IS NOT NULL", array("id" => $id, "times" => $time))
    ->findOne();

    if($query->count >= 1){
        $query1 = $query;
    }else{
        $query1 = 0;
    }

    return $query1;
}

function checkTimeInByEmpUidAndDate($id, $date){
    $query = ORM::forTable("time_log")
    ->rawQuery("SELECT * FROM time_log as t1 INNER JOIN locations as t2 ON t1.location_uid = t2.uid WHERE t1.emp_uid = :id AND t1.type='0' AND t1.status='1' AND date(t1.date_created) = :dates ORDER BY t1.date_created ASC", array("id" => $id, "dates" => $date))
    ->count();
    $valid = false;
    if($query >= 1){
        $valid = true;
    }
    return $valid;
}
function getTimeInByEmpUidAndDateNoLoc($id, $date){
    $query = ORM::forTable("time_log")
    ->rawQuery("SELECT * FROM time_log as t1 WHERE t1.emp_uid = :id AND t1.type='0' AND t1.status='1' AND date(t1.date_created) = :dates ORDER BY t1.date_created ASC", array("id" => $id, "dates" => $date))
    ->findMany();

    return $query;
}
function getTimeInByEmpUidAndDate($id, $date){
    $query = ORM::forTable("time_log")
    ->rawQuery("SELECT * FROM time_log as t1 INNER JOIN locations as t2 ON t1.location_uid = t2.uid WHERE t1.emp_uid = :id AND t1.type='0' AND t1.status='1' AND date(t1.date_created) = :dates ORDER BY t1.date_created ASC", array("id" => $id, "dates" => $date))
    ->findMany();

    return $query;
}

function getOffsetTimeInByEmpUidAndDate($id, $date){
    $query = ORM::forTable("time_log")
    ->rawQuery("SELECT * FROM time_log WHERE emp_uid = :id AND type='0' AND status='1' AND date(date_created) = :dates ORDER BY date_created ASC", array("id" => $id, "dates" => $date))
    ->findMany();

    return $query;
}

function countInsByEmpUidAndDate($id, $date){
    $query = ORM::forTable("time_log")
    ->rawQuery("SELECT count(type) as count FROM time_log WHERE emp_uid = :id AND type='0' AND status='1' AND date(date_created) = :dates ORDER BY date_created ASC ", array("id" => $id, "dates" => $date))
    //->rawQuery("SELECT t1.date_created, t1.emp_uid FROM time_log as t1 INNER JOIN rule_assignment as t2 ON t1.emp_uid = t2.emp_uid INNER JOIN rules as t3 ON t2.rule_uid = t3.rule_uid WHERE t1.emp_uid = :id AND t1.type='0' AND t1.status='1' AND date(t1.date_created) = :dates ORDER BY t1.date_created ASC LIMIT 1", array("id" => $id, "dates" => $date))
    ->findOne();

    return $query->count;
}

function getTimeOutByEmpUidAndDate($id, $session){
    $query = ORM::forTable("time_log")
        ->rawQuery("SELECT * FROM  time_log as t1 INNER JOIN locations as t2 ON t1.location_uid = t2.uid WHERE t1.emp_uid = :id AND t1.session = :session AND t1.type = '1' AND t1.status=1 ORDER BY t1.date_created ASC LIMIT 1", array("id" => $id, "session" => $session))
        ->findOne();

    return $query;
}

function getTimeOutByEmpUidAndDateNoLoc($id, $session){
    $query = ORM::forTable("time_log")
        ->rawQuery("SELECT * FROM  time_log as t1 WHERE t1.emp_uid = :id AND t1.session = :session AND t1.type = '1' AND t1.status=1 ORDER BY t1.date_created ASC LIMIT 1", array("id" => $id, "session" => $session))
        ->findOne();

    return $query;
}

function getInLocation($id, $session, $date){
    $query = ORM::forTable("time_log")
        ->rawQuery("SELECT * FROM  time_log as t1 INNER JOIN locations as t2 ON t1.location_uid = t2.uid WHERE t1.emp_uid = :id AND t1.session = :session AND date(t1.date_created) = :dates AND t1.type = '0' ORDER BY t1.date_created ASC LIMIT 1", array("id" => $id, "session" => $session, "dates" => $date))
        ->findOne();

    return $query;
}

function editTimeIn($uid, $time, $shift ,$dateModified, $status){
    $query = ORM::forTable("time_log")->where("time_log_uid", $uid)->findOne();
        $query->set("shift_uid", $shift);
        $query->set("date_created", $time);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);

    $query->save();
}

function addTimeIn($uid, $emp, $shift, $session, $dateCreated, $dateModified){
    $query = ORM::forTable("time_log")->create();
        $query->set("time_log_uid", $uid);
        $query->set("emp_uid", $emp);
        $query->set("shift_uid", $shift);
        $query->set("session", $session);
        $query->set("type", "0");
        $query->set("date_created", $dateCreated);
        $query->set("date_modified", $dateModified);

    $query->save();
}

function addTimeOut($uid, $emp, $shift, $session, $dateCreated, $location, $dateModified){
    $query = ORM::forTable("time_log")->create();
        $query->set("time_log_uid", $uid);
        $query->set("emp_uid", $emp);
        $query->set("shift_uid", $shift);
        $query->set("session", $session);
        $query->set("type", "1");
        $query->set("location_uid", $location);
        $query->set("date_created", $dateCreated);
        $query->set("date_modified", $dateModified);

    $query->save();
}

function editTimeOut($uid, $time, $shift ,$dateModified, $status){
    $query = ORM::forTable("time_log")->where("time_log_uid", $uid)->findOne();
        $query->set("shift_uid", $shift);
        $query->set("date_created", $time);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function getClockInPartner($session, $type){
    $query = ORM::forTable("time_log")->where("session", $session)->whereNotEqual("type", $type)->where("status", 1)->findOne();
    return $query;
}

function getSessionInTimeLog($uid){
    $query = ORM::forTable("time_log")->where("time_log_uid", $uid)->findOne();
    return $query;
}

function checkTimeInDataByUid($id){
    $query = ORM::forTable("time_log")->where("time_log_uid", $id)->count();
    $valid = false;
    if($query >= 1){
        $valid = true;
    }

    return $valid;
}

function getTimeInDataByUid($id){
    $query = ORM::forTable("time_log")->where("time_log_uid", $id)->findOne();
    return $query;
}

function getOtherTimeInData($empNumber, $inDate, $inHour){
    $query = ORM::forTable("time_stamp")->where("user_id", $empNumber)->where("sdate", $inDate)->where("stime", $inHour)->findOne();
    return $query;
}

function getOtherTimeOutData($empNumber, $outDate, $outHour){
    $query = ORM::forTable("time_stamp")->where("user_id", $empNumber)->where("sdate", $outDate)->where("stime", $outHour)->findOne();
    return $query;
}

function editOtherTimeIn($empNumber, $inDate, $inHour, $timeInDate, $timeInHour){
    $query = ORM::forTable("time_stamp")->where("user_id", $empNumber)->where("sdate", $inDate)->where("stime", $inHour)->where("log_type", "IN")->orderByDesc("id")->limit(1)->findOne();
        $query->set("sdate", $timeInDate);
        $query->set("stime", $timeInHour);
    $query->save();
}

function editOtherTimeOut($empNumber, $outDate, $outHour, $timeOutDate, $timeOutHour){
    $query = ORM::forTable("time_stamp")->where("user_id", $empNumber)->where("sdate", $outDate)->where("stime", $outHour)->orderByDesc("id")->limit(1)->findOne();
        $query->set("sdate", $timeOutDate);
        $query->set("stime", $timeOutHour);
    $query->save();
}

function getTimeIn($id, $date){
    $query = ORM::forTable("time_log")
    ->rawQuery("SELECT date_created, emp_uid FROM time_log WHERE emp_uid = :id AND type='0' AND status='1' AND date(date_created) = :dates", array("id" => $id, "dates" => $date))
    ->findOne();

    return $query;
}

function getTimeOutByEmpUid($id, $startDate, $endDate){
    $query = ORM::forTable("time_log")
    ->rawQuery("SELECT date_created, emp_uid FROM time_log WHERE emp_uid = :id AND type='1' AND status='1' AND date_created BETWEEN :startDate AND :endDate", array("id" => $id, "startDate" => $startDate, "endDate" => $endDate))
    ->findMany();
    return $query;
}

function getTimeOutByEmpUidUlet($id, $time){
    $query = ORM::forTable("time_log")
    ->rawQuery("SELECT date_created, emp_uid FROM time_log WHERE emp_uid = :uid AND date_created LIKE CONCAT('%', :time, '%') AND type = 1 AND status = 1", array("uid" => $id, "time" => $time))
    ->findOne();
    return $query;
}

function getEmpShiftAll(){
    $shift = ORM::forTable("time_log")
    ->rawQuery("SELECT t2.start, t2.end, t1.emp_uid FROM time_log as t1 INNER JOIN shift as t2 ON t1.shift_uid=t2.shift_uid INNER JOIN emp as t3 ON t1.emp_uid=t3.emp_uid WHERE t1.status=1 GROUP BY t3.lastname ORDER BY t3.lastname")
    ->findMany();
    return $shift;
}


function getEmpShiftInAll($startDate, $endDate){
    $timeNila = ORM::forTable("time_log")
    ->rawQuery("SELECT t1.date_created, t1.emp_uid FROM time_log as t1 INNER JOIN emp as t2 ON t1.emp_uid=t2.emp_uid WHERE t1.date_created  BETWEEN :startDate AND :endDate AND t1.type='0' AND t1.status = 1 ORDER BY t2.lastname", array("startDate" => $startDate, "endDate" => $endDate))
    ->findMany();
    return $timeNila;
}

function getEmpShiftByEmpUid($id, $startDate, $endDate){
    $timeNila = ORM::forTable("time_log")
    ->rawQuery("SELECT t1.date_created, t1.emp_uid FROM time_log as t1 INNER JOIN emp as t2 ON t1.emp_uid=t2.emp_uid WHERE t1.date_created  BETWEEN :startDate AND :endDate AND t1.type='0' AND t1.status = 1 AND t2.emp_uid = :id", array("startDate" => $startDate, "endDate" => $endDate, "id" => $id))
    ->findMany();
    return $timeNila;
}
function getEmpShiftOutAll($startDate, $endDate){
    $timeNila = ORM::forTable("time_log")
    ->rawQuery("SELECT t1.date_created, t1.emp_uid FROM time_log as t1 INNER JOIN emp as t2 ON t1.emp_uid=t2.emp_uid WHERE t1.date_created BETWEEN :startDate AND :endDate AND t1.type='1' AND t1.status = 1 ORDER BY t2.lastname", array("startDate" => $startDate, "endDate" => $endDate))
    ->findMany();
    return $timeNila;
}

function getEmpShiftOutByUid($id, $startDate, $endDate){
    $timeNila = ORM::forTable("time_log")
    ->rawQuery("SELECT t1.date_created, t1.emp_uid FROM time_log as t1 INNER JOIN emp as t2 ON t1.emp_uid=t2.emp_uid WHERE t1.date_created BETWEEN :startDate AND :endDate AND t1.type='1' AND t1.status = 1 AND t1.emp_uid = :id ORDER BY t2.lastname", array("startDate" => $startDate, "endDate" => $endDate, "id" => $id))
    ->findOne();
    return $timeNila;
}

function getTimeLogInAll($startDate, $endDate){
    $query = ORM::forTable("time_log")
    ->rawQuery("SELECT min(date_created) as date_created, emp_uid FROM time_log WHERE date_created BETWEEN :startDate AND :endDate AND type='0' AND status=1", array("startDate" => $startDate, "endDate" => $endDate))
    ->findMany();
    return $query;
}

function getTimeLogOutAll($startDate, $endDate){
    $query = ORM::forTable("time_log")
    ->rawQuery("SELECT max(date_created) as date_created, emp_uid FROM time_log WHERE date_created BETWEEN :startDate AND :endDate AND type='1' AND status=1", array("startDate" => $startDate, "endDate" => $endDate))
    ->findMany();
    return $query;
}

function getAllowanceAll($startDate, $endDate){
    $query = ORM::forTable("allowance")
    ->rawQuery("SELECT * FROM allowance WHERE date_created BETWEEN :startDate AND :endDate AND status = 1 GROUP BY emp_uid", array("startDate" => $startDate, "endDate" => $endDate))
    ->findMany();

    return $query;
}

function getAllowanceByEmpUid($id, $startDate, $endDate){
    $query = ORM::forTable("allowance")
    ->rawQuery("SELECT SUM(meal) AS mealTotal, SUM(transportation) AS transpoTotal, SUM(COLA) AS colaTotal, SUM(other) AS otherTotal FROM  allowance WHERE date_receive BETWEEN :startDate AND :endDate AND emp_uid = :id AND status = 1", array("startDate" => $startDate, "endDate" => $endDate, "id" => $id))
    ->findOne();

    return $query;
}

function getLoansAll($startDate, $endDate){
    $query = ORM::forTable("loan_deductions")
    ->rawQuery("SELECT emp_uid, sum(amount) as amount FROM loan_deductions WHERE date_created BETWEEN :startDate AND :endDate AND status=1 GROUP BY emp_uid", array("startDate" => $startDate, "endDate" => $endDate))
    ->findMany();

    return $query;
}

function getLoanByEmpUid($id, $startDate, $endDate){
    $query = ORM::forTable("loan_deductions")
    ->rawQuery("SELECT * FROM loan_deductions WHERE date_created BETWEEN :startDate AND :endDate AND status=1 AND emp_uid = :id", array("startDate" => $startDate, "endDate" => $endDate, "id" => $id))
    ->findMany();

    return $query;
}

function countEmployeeDependents(){
    $query = ORM::forTable("emp_dependent")
    ->rawQuery("SELECT COUNT(t2.emp_uid) as count, t1.emp_uid as emp_uid FROM emp_dependent as t1 LEFT OUTER JOIN emp as t2 ON t1.emp_uid = t2.emp_uid INNER JOIN salary as t3 ON t2.emp_uid = t3.emp_uid WHERE t1.status=1 AND t2.status=1 AND t3.status=1 GROUP BY t2.emp_uid")
    ->findMany();

    return $query;
}

function countEmployeeDependentsByUid($id){
    $query = ORM::forTable("emp_dependent")
    ->rawQuery("SELECT COUNT(t2.emp_uid) as count, t1.emp_uid as emp_uid FROM emp_dependent as t1 LEFT OUTER JOIN emp as t2 ON t1.emp_uid = t2.emp_uid INNER JOIN salary as t3 ON t2.emp_uid = t3.emp_uid WHERE t1.status=1 AND t2.status=1 AND t3.status=1 AND t1.emp_uid = :id", array("id" => $id))
    ->findOne();

    return $query;
}

function getDependentDataByUid($id){
    $query = ORM::forTable("emp_dependent")
    ->rawQuery("SELECT count(emp_uid) as count, emp_uid, emp_dependent_uid FROM emp_dependent WHERE emp_dependent_uid = :id AND status = 1 GROUP BY emp_uid", array("id" => $id))
    ->findMany();

    return $query;
}
function getSingleEmpData(){
    $query = ORM::forTable("emp")
    ->rawQuery("SELECT * FROM emp WHERE emp_uid NOT IN (SELECT emp_uid FROM emp_dependent WHERE status = 1) AND status = 1")
    ->findMany();

    return $query;
}

function getSinglesDataByEmpUid($id){
    $query = ORM::forTable("emp_dependent")
    ->rawQuery("SELECT * FROM emp_dependent as t1 INNER JOIN emp as t2 ON t1.emp_uid = t2.emp_uid WHERE t1.emp_uid = :id AND t1.status =1 AND t2.status=1", array("id" => $id))
    ->findOne();

    return $query;
}

// function getEmpShift($emp){
//     $shift = ORM::forTable("time_log")->tableAlias("t1")->selectMany("t2.start_time", "t2.duration")->innerJoin("shift", array("t1.shift_uid", "=", "t2.shift_uid") ,"t2")->where("t1.emp_uid", $emp)->where("t1.status", "1")->limit(1)->findMany();
//     return $shift;
// }

function getFirstEmpDate($emp, $startDate){
    $first = ORM::forTable("time_log")->select("date_created")->where("emp_uid", $emp)->whereGte("date_created", $startDate)->where("status", "1")->where("type", "0")->where("status", "1")->orderByAsc("date_created")->limit(1)->findOne();
    return $first;
}
function getLastEmpDate($emp, $endDate){
    $end = ORM::forTable("time_log")->select("date_created")->where("emp_uid", $emp)->whereLte("date_created", $endDate)->where("status", "1")->where("type", "1")->orderByDesc("date_created")->limit(1)->findOne();
    return $end;
}

function getEmpTimes($emp, $startDate){
    $time = ORM::forTable("time_log")->select("date_created")->where("emp_uid", $emp)->where("status", "1")->whereGte("date_created", $startDate)->findOne();
    return $time;
}

function getEmpShift($emp){
    $shift = ORM::forTable("time_log")->tableAlias("t1")->selectMany("t2.start", "t2.end")->innerJoin("shift", array("t1.shift_uid", "=", "t2.shift_uid") ,"t2")->where("t1.emp_uid", $emp)->where("t1.status", "1")->limit(1)->findMany();
    return $shift;
}

function getAllEmpShift($emp, $startDate, $endDate){
    $timeNya = ORM::forTable("time_log")
    ->rawQuery("SELECT date_created FROM time_log WHERE emp_uid = :emp AND date_created BETWEEN :startDate AND :endDate AND status = 1", array("emp" => $emp, "startDate" => $startDate, "endDate" => $endDate))
    ->findMany();
    return $timeNya;
}
function getAllEmpShiftIn($emp, $startDate, $endDate){
    $timeNya = ORM::forTable("time_log")
    ->rawQuery("SELECT date_created FROM time_log WHERE emp_uid = :emp AND date_created BETWEEN :startDate AND :endDate AND type='0' AND status = 1", array("emp" => $emp, "startDate" => $startDate, "endDate" => $endDate))
    ->findMany();
    return $timeNya;
}

function getAllEmpShiftOut($emp, $startDate, $endDate){
    $timeNya = ORM::forTable("time_log")
    ->rawQuery("SELECT date_created FROM time_log WHERE emp_uid = :emp AND date_created BETWEEN :startDate AND :endDate AND type='1' AND status = 1", array("emp" => $emp, "startDate" => $startDate, "endDate" => $endDate))
    ->findOne();
    return $timeNya;
}

function getPayperiodAndSalaryByEmpUid($id){
    $query = ORM::forTable("salary")
    ->rawQuery("SELECT * FROM salary as t1 INNER JOIN pay_period as t2 ON t1.pay_period_uid = t2.pay_period_uid WHERE t1.emp_uid = :id", array("id" => $id))->findOne();

    return $query;
}

function getEmployeeSalary() {
    $query = ORM::forTable("emp")->select("emp.*")->select("salary.base_salary", "baseSalary")->innerJoin("salary" , array("emp.emp_uid" ,"=", "salary.emp_uid"))->orderByAsc("emp.lastname")->findMany();
        return $query;
}

function dummyGenerateTimelog ($timeLogUid, $emp, $session ,$shift, $type, $locationUid, $dateCreated, $dateModified){
     $query = ORM::forTable("time_log")->create();
        $query->time_log_uid = $timeLogUid;
        $query->emp_uid = $emp;
        $query->shift_uid = $shift;
        $query->session = $session;
        $query->type = $type;
        $query->location_uid = $locationUid;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getEmpTimelogs($emp,$startDate,$endDate){
    // $query = ORM::forTable("time_log")->where("emp_uid", $emp)->where("status", 1)->whereGte("date_created", $startDate)->whereLte("date_created",$endDate)->orderByAsc("date_created")->findMany();
    $query = ORM::forTable("time_log")
    ->rawQuery("SELECT * FROM time_log WHERE emp_uid = :emp AND date_created BETWEEN :startDate AND  :endDate AND status = 1", array("emp" => $emp, "startDate" => $startDate, "endDate" => $endDate))
    ->findMany();
    return $query;

}

function addTimeStampData($emp, $date, $time, $type){
    $query = ORM::forTable("time_stamp")->create();
        $query->user_id = $emp;
        $query->sdate = $date;
        $query->stime = $time;
        $query->log_type = $type;
    $query->save();
}


function getScheduleByEmpUid($uid){
    $query = ORM::forTable("schedule")->where("emp_uid", $uid)->findOne();
        return $query;
}

function getAllTimeSheet($uid, $startDate, $endDate){
    $query = ORM::forTable("schedule")
    ->rawQuery("SELECT * FROM time_log WHERE emp_uid= :id AND date_created BETWEEN :startDate AND :endDate AND status=1 ORDER BY date_created DESC", array("id" => $uid, "startDate" => $startDate, "endDate" => $endDate))->findMany();
    return $query;
}
/*SHIFT*/

function getShiftByUid($uid){
    $query = ORM::forTable("shift")->where("shift_uid", $uid)->findOne();
        return $query;
}

function getShift(){
    $query = ORM::forTable("shift")->where("status", "1")->findMany();
        return $query;
}

function dummySetShift($shiftUid , $name, $start , $end , $grace , $batch ,$dateCreated , $dateModified){
    $query = ORM::forTable("shift")->create();
        $query->shift_uid = $shiftUid;
        $query->name = $name;
        $query->start = $start;
        $query->end = $end;
        $query->grace_period = $grace;
        $query->batch = $batch;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function updateShiftById($shiftUid , $name , $start , $end , $grace , $batch , $dateModified , $status){
    $query = ORM::forTable("shift")->where("shift_uid", $shiftUid)->findOne();
        $query->set("name", $name);
        $query->set("start", $start);
        $query->set("end", $end);
        $query->set("grace_period", $grace);
        $query->set("batch", $batch);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function getShiftByEmpUid($uid){
    $query = ORM::forTable("shift")->tableAlias("t1")->innerJoin("emp_shift", array("t1.shift_uid", "=", "t2.shift_uid"), "t2")->where("t2.emp_uid", $uid)->findOne();
    return $query;
}

function updateEmpShift($uid, $shift, $dateModified, $status){
    $query = ORM::forTable("emp_shift")->where("emp_shift_uid", $uid)->findOne();
        $query->set("shift_uid", $shift);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function setEmpShift($empShiftUid, $shift, $uid ,$dateCreated, $dateModified){
    $query = ORM::forTable("emp_shift")->create();
        $query->emp_shift_uid = $empShiftUid;
        $query->shift_uid = $shift;
        $query->emp_uid = $uid;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function shiftCount($name){
    $query = ORM::forTable("shift")->select_expr("count(name)", "count")->where("name", $name)->findOne();
        return $query;
}
/*END OF SHIFT*/
function dummyDataSetSchedule($uid,$timeLogUid,$emp,$shiftUid,$days,$dateCreated, $dateModified){
    $query = ORM::forTable("schedule")->create();
        $query->schedule_uid = $uid;
        $query->time_log_uid = $timeLogUid;
        $query->emp_uid = $emp;
        $query->shift_uid = $shiftUid;
        $query->days = $days;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getAllEmpUid(){
    $query = ORM::forTable("emp")->select("emp_uid")->where(1)->findMany();
        return $query;
}

function getAllEmp(){
    $query = ORM::forTable("emp")->tableAlias("t1")->select("t1.firstname", "firstname")->select("t1.middlename", "middlename")->select("lastname", "lastname")->select("t2.base_salary", "baseSalary")->innerJoin("salary", array("t1.emp_uid", "=", "t2.emp_uid"), "t2")->orderByAsc("t1.lastname")->findMany();
        return $query;
}

function getAllTimeOut($empId, $date){
    $outs = getTimeOutByEmpUidAndDate($empId, $date);
    $response = array();
    foreach($outs as $outss){
        $outId = $outss->emp_uid;
        $out = $outss->date_created;

        $response[] = array(
            "out" => $out
        );
    }
    
    return $response;
}

function checkRestDay($id, $date){
    $query = ORM::forTable("time_log")
        ->rawQuery("SELECT count(id) as count FROM time_log WHERE emp_uid = :id AND date(date_created) = :dates AND status = 1 AND type = 0", array("id" => $id, "dates" => $date))->findOne();
    return $query->count;
}
function getEmpNetPayByEmpUid($id){
    $response = array();

    $dep = getDailySalaryByEmpUid($id);
    $response = array();

    $empSalary = $dep["monthlySalary"];
    $id = $dep["emp"];

    $ss = getSSSBySalary($empSalary);
    // SSS
    if($ss){
        $sssStartRange = $ss["rangeOfComp"];
        $sssEndRange = $ss["rangeOfCompEnd"];
        $ssEmployee = $ss["sssEe"];
        $ssEmployer = $ss["sssEr"];
        $ssTotal = $ss["sssTotal"];
    }
    // PHILHEALTH

    $philhealth = getPhilhealthBySalary($empSalary);
    if($philhealth){
        $pStart = $philhealth["salaryRange"];
        $pEnd = $philhealth["salaryRangeEnd"];
        $salaryBase = $philhealth["salaryBase"];
        $philTotal = $philhealth["totalMonthlyPremium"];
        $philEmployer = $philhealth["employerShare"];
        $philEmployee = $philhealth["employeeShare"];
    }

    //PAGIBIG
    // foreach($pag as $pagibig){
    //     $pagStart = $pagibig["pagibigGrossPayRange"];
    //     $pagEnd = $pagibig["pagibigGrossPayRangeEnd"];
    //     $pagEmp = $pagibig["pagibigEmployer"];
    //     $pagTotal = $pagibig["pagibigTotal"];

    //     // if(!$pagibigNo){
    //     //     $totalPagibig = 0;
    //     // }else{
    //         if($empSalary >= $pagStart && $empSalary <= $pagEnd){
    //             // echo "GROSS PAY RANGE: &nbsp" . $pagStart . " - " . $pagEnd . " : " . $pagTotal . "<br/>";
    //             $totalPagibig = $empSalary * $pagTotal;
    //             // echo "<td>" . $totalPagibig . "</td>";
    //         }else if($empSalary <= $pagStart && $empSalary >= $pagEnd){
    //             // echo "GROSS PAY RANGE: &nbsp" . $pagStart . " - " . $pagEnd . " : " . $pagTotal . "<br/>";
    //             $totalPagibig = $empSalary * $pagTotal;
    //             // echo "<td>" . $totalPagibig . "</td>";
    //         }
    //     // }
    // }
    $totalPagibig = "100";

    //NET PAY
    $totalContri = $ssEmployee + $philEmployee + $totalPagibig;

    $response = array(
        "netId" => $id,
        "basicSalary" => $empSalary,
        "sss" => $ssTotal,
        "sssEmployee" => $ssEmployee,
        "sssEmployer" => $ssEmployer,
        "philhealth" => $philTotal,
        "philEmployee" => $philEmployee,
        "philEmployer" => $philEmployer,
        "pagibig" => $totalPagibig,
        "totalContri" => $totalContri
    );
    // }
    // return $response;
}

function overtime($id, $startDate, $endDate){
    $rate = getRateByEmpUid($id, $startDate, $endDate);
    $response = array();
    // print_r($rate);
    foreach($rate as $rates){
        $oTrate = $rates->rate;
        $reqId = $rates->overtime_request_uid;

        $rateCode = getRateCodeByUid($reqId);

        switch($rateCode){
            case "RegOT":
                $nightRate = 0.10;
                // $nightDiffs = $hourlyPayment * $nightRate;
                $oTrate = 1.25;
                break;
            default:
                $nightRate = $oTrate;
                // $nightDiffs = $hourlyPayment * 0.10 * $nightRate;
                break;
        }//end of switch
        // echo "$oTrate = $reqId = $rateCode = $nightRate<br/>";
        $response[] = array(
            "id" => $id,
            "oTcode" => $rateCode,
            "nightRate" => $nightRate,
            "oTRate" => $oTrate
        );
    }//end of getRateByEmpUid Function
    // echo jsonify($response);
    return $response;
}
function getEmpTimesheetAll($startDate, $endDate){
    $a = getTimelogTesting($startDate, $endDate);

    foreach($a as $aa){
        $id = $aa->emp_uid;
        $lastname = utf8_decode($aa->lastname);
        $firstname = utf8_decode($aa->firstname);
        $middlename = utf8_decode($aa->middlename);

        $workingStartDate = getTimeInByEmpUid($id, $startDate, $endDate);
        $workingEndDate = getTimeOutByEmpUid($id, $startDate ,$endDate);

        $shift = getEmpShiftByUid($id);
        $work = 0;
        $late = 0;
        $overtime = 0;
        $undertime = 0;

        $start = strtotime($startDate);
        $end = strtotime($endDate);
        $total = 0;

        if($shift){
            $shiftEmpUid = $shift->emp_uid;
            $shiftStart = $shift->start;
            $shiftEnd = $shift->end;
        }//end of getEmpShiftByUid Function

        /*WORKS FUNCTION*/
        foreach($workingStartDate as $in){
            $inId = $in->emp_uid;
            $timeInn = $in->date_created;
            $timeInDate = date("Y-m-d", strtotime($timeInn));
            $timeIn = date("H:i:s", strtotime($timeInn));
            $timeIns = strtotime($timeIn);

            $out = getTimeOutByEmpUidUlet($inId, $timeInDate);
            if($out){
                $outId = $out->emp_uid;
                $timeOut = $out->date_created;
                $timeOutt = date("H:i:s", strtotime($timeOut));
                $timeOuts = strtotime($timeOutt);
            }
            $work += ($timeOuts - $timeIns) / 3600;
        }//end of time in function
        /*END OF FUNCTION OF WORKS*/
        $totalWork = $work;
        $workHour = floor($totalWork);
        $workMin = round(60*($totalWork-$workHour));

        $date1 = date_create($endDate);
        $date2 = date_create($startDate);

        $daysOfWorkTotal = date_diff($date2, $date1);
        $daysOfWork = $daysOfWorkTotal->format("%a");

        while (date("Y-m-d", $start) != date("Y-m-d", $end)) {
            if(date("l", $start) == "Sunday"){
                $total++;
            }
            $start = strtotime(date("Y-m-d", $start) . "+1 day");
        }//end of while

        $weekEnd = $total;
        $daysOfWork = $daysOfWork-$weekEnd;

        $latess = getTimeInByEmpUid($id, $startDate, $endDate);
        $overTt = getTimeOutByEmpUid($id, $startDate, $endDate);

        $wHoursToMinutes = $workHour * 60;
        $totalWMinutes = $wHoursToMinutes + $workMin;

        $late = 0;

        $overtime = 0;
        $undertime = 0;
        $nights = 0;
        $night = 0;
        $partialOvertime = 0;
        $overtimeNightStatus = 0;

        $dapatIn = (strtotime($graceMinute) - strtotime($shiftStart)) / 3600;
        $dapatInHour = floor($dapatIn);
        $dapatInMin = round(60*($dapatIn-$dapatInHour));

        if($dapatIn < 0){
            $dapatInHour = str_replace("-", "", $dapatInHour);
            $dapatInMin = str_replace("-", "", $dapatInMin);
            $tempInHour = "$dapatInHour:$dapatInMin";

            $inHour = date("H:i:s", strtotime($tempInHour));
        }else{
            $dapatInHour = floor($dapatIn);
            $dapatInMin = round(60*($dapatIn-$dapatInHour));
            $tempInHour = "$dapatInHour:$dapatInMin";

            $inHour = date("H:i:s", strtotime($tempInHour));
        }

        foreach($latess as $latee){
            $lId = $latee->emp_uid;

            $timeIns = date("Y-m-d", strtotime($latee->date_created));
            $timeIn = date("H:i:s", strtotime($latee->date_created));

            $timeIn1 = strtotime($timeIn);  
   
            $durationDapat = strtotime($inHour);
            if($timeIn1 >= $durationDapat){
                $late += ($timeIn1 - strtotime($shiftStart))/3600;
            }//end of comparison
        }//end of getTimeInByEmpUid Function
        //REGULAR DAY OF OVERTIME
        $overtimeStatus = 0;

        foreach($overTt as $overT){
            $oId = $overT->emp_uid;

            $oTimeOuts = date("Y-m-d", strtotime($overT->date_created));
            $oTimeOut = date("H:i:s", strtotime($overT->date_created));
            $oTimeOut1 = strtotime($oTimeOut);
            $endDurationDapat = strtotime($shiftEnd);
            $nightDiffStart = "22:00:00";
            $nightDiffEnd = "06:00:00";

            $testingOvertime = getOvertimeRequestsByDatesAndEmpUid($id, $startDate, $endDate);
            if($testingOvertime){
                $testOvertimeId = $testingOvertime->emp_uid;
                $testOvertimeStartDate = $testingOvertime->start_date;
                $testOvertimeEndDate = $testingOvertime->end_date;
                if($testOvertimeId == $id){
                    if($testOvertimeEndDate == $oTimeOuts){
                        if(strtotime($shiftEnd) <= $oTimeOut1){
                            // $overtime += (($oTimeOut1 - strtotime($shiftEnd))/3600) - $night;
                            $partialOvertime += ($oTimeOut1 - strtotime($shiftEnd))/3600;
                            $overtimeStatus = 1;
                            // $overtime += $partialOvertime - $night;
                            if($overtimeStatus == 1){
                                if($oTimeOut >= $nightDiffStart || $oTimeOut == $nightDiffEnd){
                                    $nights += ($oTimeOut1 - strtotime($nightDiffStart)) / 3600;
                                    $overtimeNightStatus = 1;
                                }//end of night Differential
                            }else{
                                $overtimeNightStatus = 0;
                            }//end of getting overtime Status

                            $overtime = $partialOvertime - $nights;
                        }//end of getting overtime
                    }//end of comparison of id
                }//end of comparing id
            }//end of getOvertimeRequestByEmpUid Function

            if($oTimeOut1 <= $endDurationDapat){
                $undertime += (strtotime($shiftEnd) - $oTimeOut1)/3600;
            }//end of comparison of hour

            $totalOvertime = $overtime;
            $overHour = floor($totalOvertime);
            $overMin = round(60*($totalOvertime-$overHour));

            $totalUndertime = $undertime;
            $underHour = floor($totalUndertime);
            $underMin = round(60*($totalUndertime-$underHour));

            $totalLate = $late;
            $lateHour = floor($totalLate);
            $lateMin = round(60*($totalLate-$lateHour));
        }//end of getTimeOutByEmpUid Function
        $results[] = array(
            "emp" => $id,
            "lastname" => $lastname,
            "firstname" => $firstname,
            "middlename" => $middlename,
            "workingHours" => $workHour,
            "workingMinutes" => $workMin,
            "wWorkingHour" => $work,
            "lateHour" => $lateHour,
            "lLateHour" => $totalLate,
            "lateMinute" => $lateMin,
            "overtimeHour" => $overHour,
            "oOvertimeHour" => $totalOvertime,
            "overtimeMinute" => $overMin,
            "undertimeHour" => $underHour,
            "undertimeMinute" => $underMin,
            "uUndertime" => $undertime,
            "overtimeStatus" => $overtimeStatus,
            "partialOvertime" => $partialOvertime,
            "nightHour" => $nights,
            "overtimeNightStatus" => $overtimeNightStatus
        );
    }//end of getTimelogTesting Function
    // echo jsonify($results);
    return $results;
}
function getAllTimeIn($id, $date){
    $ins = getTimeInByEmpUidAndDate($id, $date);
    foreach($ins as $inss){
        $in = $inss["date_created"];
        $inn = date("H:i:s", strtotime($in));
        $in1 = date("Y-m-d", strtotime($in));

        $response[] = array(
            "inHour" => $inn, 
            "inDate" => $in1
        );
    }//end of getting ins

    return $response;
}
function times($startDate, $endDate){
    $startDates = strtotime($startDate);
    $endDates = strtotime($endDate);

    $a = getTimelogTesting($startDate, $endDate);
    for($i=$startDates; $i<=$endDates; $i+=86400){
        $date =  date("Y-m-d", $i);
        $day = date("l", $i);

        foreach($a as $aa){
            $id = $aa->emp_uid;
            $lastname = utf8_decode($aa->lastname);
            $start = $aa->date_created;

            $work = 0;
            $late = 0;
            $overtime = 0;
            $undertime = 0;

            $b = getTimeOutByEmpUidUlet($id, $date);
            $out = date("Y-m-d", strtotime($b["date_created"]));

            $holiday = getHolidayByDate($date);
            $hDate = $holiday["date"];
            if($hDate == $date){
                $prompt = 3;
                $time = "HOLIDAY";
            }else if($out != $date && $hDate != $date){
                $prompt = 0;
                $time = "ABSENT";
            }else{
                $prompt = 1;
                $time = $b["date_created"];
            }

            if(date("l", $i) == "Sunday"){
                $sun = date("Y-m-d", $i);
                $prompt = 2;
                $time = "Rest Day";
            }

            switch ($prompt) {
                case 0:
                    $absentEmpId = $id;
                    $absentDate = $date;

                    $absentDay = $day;
                    $in = $time;
                    $out = $time;

                    $response[] = array(
                        "id" => $id,
                        "prompt" => $prompt,
                        "lastname" => strtoupper($lastname),
                        "dates" => $date,
                        "date" => date("M d, Y", strtotime($date)),
                        "day" => $day,
                        "in" => "ABSENT",
                        "out" => "ABSENT",
                        "late" => "ABSENT",
                        "overtime" => "ABSENT",
                        "undertime" => "ABSENT",
                        "work" => "ABSENT",
                        "approveOTStatus" => "0"
                    );
                    break;
                case 1:
                    $empId = $id;
                    $empDate = $date;
                    $empNote = $time;
                    $empDay = $day;

                    $ins = getTimeInByEmpUidAndDate($empId, $empDate);
                    $outs = getTimeOutByEmpUidAndDate($empId, $empDate);

                    if($ins && $outs){
                        $inId = $ins->emp_uid;
                        $outId = $outs->emp_uid;
                        $in = $ins->date_created;
                        $out = $outs->date_created;
                    }
                    $inDate = date("Y-m-d", strtotime($in));

                    $shift = getShiftByUidAndDate($inId, $inDate);
                    $shiftStart = $shift->start;
                    $shiftEnd = $shift->end;

                    $dapatIn = (strtotime($graceMinute) - strtotime($shiftStart)) / 3600;
                    $dapatInHour = floor($dapatIn);
                    $dapatInMin = round(60*($dapatIn-$dapatInHour));

                    if($dapatIn < 0){
                        $dapatInHour = str_replace("-", "", $dapatInHour);
                        $dapatInMin = str_replace("-", "", $dapatInMin);
                        $tempInHour = "$dapatInHour:$dapatInMin";

                        $inHour = date("H:i:s", strtotime($tempInHour));
                    }else{
                        $dapatInHour = floor($dapatIn);
                        $dapatInMin = round(60*($dapatIn-$dapatInHour));
                        $tempInHour = "$dapatInHour:$dapatInMin";

                        $inHour = date("H:i:s", strtotime($tempInHour));
                    }

                    /*WORKED FUNCTION*/
                    if(strtotime($out) < strtotime($in)){
                        $work += (strtotime($in) - strtotime($out)) / 3600;
                    }else if(strtotime($out) > strtotime($in)){
                        $work += (strtotime($out) - strtotime($in)) / 3600;
                    }

                    $inn = date("H:i:s", strtotime($in));
                    $inHour = date("H:i:s", strtotime($inHour));
                    /*END OF WORKED FUNCTION*/

                    /*LATE FUNCTION*/
                    if(strtotime($inn) >= strtotime($inHour)){
                        $late += (strtotime($inn) - strtotime($inHour)) / 3600;
                    }//end of comparison for late
                    /*END OF LATE FUNCTION*/

                    /*OVERTIME FUNCTION*/
                    $outHour = date("H:i:s", strtotime($out));
                    $request = getOvertimeRequestByEmpUidAndDate($empId, $empDate, $hDate);
                    $requestStartDate = $request["start_date"];
                    $requestEmpId = $request["emp_uid"];

                    if($empId == $requestEmpId){
                        if(strtotime($shiftEnd) <= strtotime($outHour)){
                            $overtime += (strtotime($outHour) - strtotime($shiftEnd)) / 3600;
                        }//end of comparison for overtime
                    }//end of comparison for Id
                    /*END OF OVERTIME FUNCTION*/

                    /*UNDERTIME FUNCTION*/ 
                    if(strtotime($outHour) <= strtotime($shiftEnd)){
                        // echo "UNDERTIME = $id = $shiftEnd = $outHour<br/>";
                        $undertime += (strtotime($shiftEnd) - strtotime($outHour)) / 3600;
                    }//end of comparison for undertime
                    /*END OF UNDERTIME FUNCTION*/ 
                    $check = getOvertimeRequestByEmpUidAndDate($empId, $empDate, $hDate);
                    $checkEmpId = $check["emp_uid"];
                    $checkDate = $check["start_date"];
                    $oTstatus = 0;
                    $approvedDate = 0;

                    if(!$checkDate){

                    }else{
                        $approvedDate = $checkDate;
                        $oTstatus = 1;
                    }//end of checking

                    $totalWork = $work;
                    $workHour = floor($totalWork);
                    $workMin = floor(60*($totalWork-$workHour));

                    $totalLate = $late;
                    $lateHour = floor($totalLate);
                    $lateMin = floor(60*($totalLate-$lateHour));

                    $totalOvertime = $overtime;
                    $overtimeHour = floor($totalOvertime);
                    $overtimeMin = floor(60*($totalOvertime-$overtimeHour));

                    $totalUndertime = $undertime;
                    $undertimeHour = floor($totalUndertime);
                    $undertimeMin = floor(60*($totalUndertime-$undertimeHour));

                    $lates = new dateTime("$lateHour:$lateMin:00");
                    $overtimes = new dateTime("$overtimeHour:$overtimeMin:00");
                    $undertimes = "$undertimeHour:$undertimeMin:00";
                    $worked = "$workHour:$workMin:00";

                    $response[] = array(
                        "id" => $id,
                        "prompt" => $prompt,
                        "lastname" => strtoupper($lastname),
                        "dates" => $date,
                        "date" => date("M d, Y", strtotime($date)),
                        "day" => $day,
                        "in" => date("H:i:s A", strtotime($in)),
                        "out" => date("h:i:s A", strtotime($out)),
                        "late" => date_format($lates, "H:i:s"),
                        "overtime" => date_format($overtimes, "H:i:s"),
                        "undertime" => date("H:i:s", strtotime($undertimes)),
                        "work" => date("H:i:s", strtotime($worked)),
                        "approveOTStatus" => $oTstatus
                    );
                    break;
                case 2:
                    $restId = $id;
                    $restDate = $date;
                    // $restNote = $time;
                    $restDay = $day;
                    $in = $time;
                    $out = $time;

                    $response[] = array(
                        "id" => $id,
                        "prompt" => $prompt,
                        "lastname" => strtoupper($lastname),
                        "dates" => $date,
                        "error" => $time,
                        "date" => date("M d, Y", strtotime($date)),
                        "day" => $day,
                        "in" => "REST DAY",
                        "out" => "REST DAY",
                        "late" => "REST DAY",
                        "overtime" => "REST DAY",
                        "undertime" => "REST DAY",
                        "work" => "REST DAY",
                        "approveOTStatus" => "0"
                    );
                    break;
                case 3:
                    $holidayEmpId = $id;
                    $holidayDate = $date;
                    // $absentNote = $time;
                    $holidayDay = $day;
                    $in = $time;
                    $out = $time;

                    $response[] = array(
                        "id" => $id,
                        "prompt" => $prompt,
                        "lastname" => strtoupper($lastname),
                        "dates" => $date,
                        "date" => date("M d, Y", strtotime($date)),
                        "day" => $day,
                        "in" => "HOLIDAY",
                        "out" => "HOLIDAY",
                        "late" => "HOLIDAY",
                        "overtime" => "HOLIDAY",
                        "undertime" => "HOLIDAY",
                        "work" => "HOLIDAY",
                        "approveOTStatus" => "0"
                    );
                    break;
            }//end of switch for prompt
        }//end of getTimelogTesting function
    }//end of for-loop
    foreach ($response as $k => $v) {
        $sort["dates"][$k] = $v["dates"];
    }//end of response

    array_multisort($sort["dates"], SORT_ASC,$response);
    // echo jsonify($response);
    return $response;
}

function getRestDaysCountInAYear($start, $end){
    $startDates = strtotime($start);
    $endDates = strtotime($end);   
    $totalMonday = 0;
    $totalTuesday = 0;
    $totalWednesday = 0;
    $totalThursday = 0;
    $totalFriday = 0;
    $totalSaturday = 0;
    $totalSunday = 0;
    $restday = 0;
    $count = 0;

    for($i=$startDates; $i<=$endDates; $i+=86400){
        $date =  date("Y-m-d", $i);
        $day = date("l", $i);
        $count ++;
    }//end of for-loop

    $restDay = getRestDay();
    foreach($restDay as $restDays){
        $restName = $restDays->name;

        while (date("Y-m-d", $startDates) != date("Y-m-d", $endDates)) {
            if(date("l", $startDates) === $restName && $restName === "Monday"){
                $totalMonday++;
            }else if(date("l", $startDates) === $restName && $restName === "Tuesday"){
                $totalTuesday++;
            }else if(date("l", $startDates) === $restName && $restName === "Wednesday"){
                $totalWednesday++;
            }else if(date("l", $startDates) === $restName && $restName === "Thursday"){
                $totalThursday++;
            }else if(date("l", $startDates) === $restName && $restName === "Friday"){
                $totalFriday++;
            }else if(date("l", $startDates) === $restName && $restName === "Saturday"){
                $totalSaturday++;
            }else if(date("l", $startDates) === $restName && $restName === "Sunday"){
                $totalSunday++;
            }//end of if-else condition
            $startDates = strtotime(date("Y-m-d", $startDates) . "+1 day");
        }//end of while

        $restday += $totalMonday + $totalTuesday + $totalWednesday + $totalThursday + $totalFriday + $totalSaturday + $totalSunday;

    }//end of getting restday

    $countPerYear = $count - $restday;
    
    return $countPerYear;
}

function getRestDayCount($uid){
    $schedule = getSchedules($uid);
    $payrollDate = $schedule->payroll_date;
    $cutoffDate = $schedule->cutoff_date;

    $start = strtotime($payrollDate);
    $end = strtotime($cutoffDate);
    $totalMonday = 0;
    $totalTuesday = 0;
    $totalWednesday = 0;
    $totalThursday = 0;
    $totalFriday = 0;
    $totalSaturday = 0;
    $totalSunday = 0;
    $restday = 0;
    $rest = array();

    $restDay = getRestDay();
    foreach($restDay as $restDays){
        $restName = $restDays->name;
        while (date("Y-m-d", $start) != date("Y-m-d", $end)) {
            if(date("l", $start) === $restName && $restName === "Monday"){
                $totalMonday++;
            }else if(date("l", $start) === $restName && $restName === "Tuesday"){
                $totalTuesday++;
            }else if(date("l", $start) === $restName && $restName === "Wednesday"){
                $totalWednesday++;
            }else if(date("l", $start) === $restName && $restName === "Thursday"){
                $totalThursday++;
            }else if(date("l", $start) === $restName && $restName === "Friday"){
                $totalFriday++;
            }else if(date("l", $start) === $restName && $restName === "Saturday"){
                $totalSaturday++;
            }else if(date("l", $start) === $restName && $restName === "Sunday"){
                $totalSunday++;
            }//end of if-else condition
            $start = strtotime(date("Y-m-d", $start) . "+1 day");
        }//end of while

        $restday += $totalMonday + $totalTuesday + $totalWednesday + $totalThursday + $totalFriday + $totalSaturday + $totalSunday;
    }//end of getting restday

    $rest = array(
        "payrollDate" => $payrollDate,
        "cutofDate" => $cutoffDate,
        "count" => $restday
    );

    return $rest;
    // echo jsonify($rest);
}

function holidayPayByEmpUid($startDate, $endDate, $emp){
    $holidayCount = 0;
    $holidayPayTotal = 0;

    $holidays = getHolidayByEmpUid($startDate, $endDate, $emp);
    $salary = getDailySalaryByEmpUid($emp);

    if($salary){
        $daySalary = $salary["daySalary"];
        $hourlySalary = $salary["hourlySalary"];
    }else{
        $daySalary = 0;
        $hourlySalary = 0;
    }//end of checking if user has salary

    foreach ($holidays as $holiday) {
        $tardiness = $holiday["tardiness"];
        $late = $holiday["late"];
        $undertime = $holiday["undertime"];
        $code = $holiday["code"];
        $work = $holiday["work"];
        $rate = $holiday["rate"];

        $holidayPay = $hourlySalary * $rate * $work;
        $holidayCount++;

        $holidayPayTotal += $holidayPay;
    }//end of getHolidayByEmpUid Function

    $response = array(
        "emp" => $emp,
        "holidayCount" => $holidayCount,
        "holidayPay" => $holidayPayTotal
    );

    // echo jsonify($response);
    return $response;
}

function getDailySalaryByEmpUid($emp){
    $salaries = getSalaryByUid($emp);
    $response = array();
    $workdays = getWorkDay();
    $weeklySalary = 0;
    $monthlySalary = 0;
    $hourlySalary = 0;

    if($salaries){
        $salaryName = $salaries->pay_period_name;
        $baseSalary = $salaries->base_salary;
        switch($salaryName){
            case "Daily":
                // $weeklySalary = ($salaries->base_salary) * 6;
                $monthlySalary = ($salaries->base_salary) * $workdays->work_day_per_month;
                // $monthlySalary = ($salaries->base_salary) * 26;
                $weeklySalary = $monthlySalary / 4;
                $hourlySalary = ($salaries->base_salary) / 8;
                $minSalary = $hourlySalary / 60;
                $daySalary = $salaries->base_salary;
                break;
            case "Weekly":
                $weeklySalary = $salaries->base_salary;
                $monthlySalary = ($salaries->base_salary) * 4;
                $hourlySalary = ($monthlySalary / $workdays->work_day_per_month) / 8;
                $minSalary = $hourlySalary / 60;
                $daySalary = $hourlySalary * 8;

                break;
            case "Semi-Monthly":
                $weeklySalary = ($salaries->base_salary) / 2;
                $monthlySalary = ($salaries->base_salary) * 2;
                $hourlySalary = ($monthlySalary / $workdays->work_day_per_month) / 8;
                $minSalary = $hourlySalary / 60;
                $daySalary = $hourlySalary * 8;

                break;
            case "Monthly":
                $weeklySalary = ($salaries->base_salary) / 4;
                $monthlySalary = $salaries->base_salary;
                $hourlySalary = (($salaries->base_salary) / $workdays->work_day_per_month) / 8;
                $minSalary = $hourlySalary / 60;
                $daySalary = $hourlySalary * 8;

                break;
        }//end of switch

        // else if(($salaries->pay_period_name) == "Quarterly"){
        //     $monthlySalary = ($salaries->base_salary) / 3;
        //     $weeklySalary = $monthlySalary / 4;
        //     $hourlySalary = ($monthlySalary / $workdays->work_day_per_month) / 8;
        // }

        // else if(($salaries->pay_period_name) == "Semi-Annual"){
        //     $monthlySalary = ($salaries->base_salary) / 6;
        //     $weeklySalary = $monthlySalary / 4;
        //     $hourlySalary = ($monthlySalary / $workdays->work_day_per_month) / 8;
        // }

        // else if(($salaries->pay_period_name) == "Annual"){
        //     $monthlySalary = ($salaries->base_salary) / 12;
        //     $weeklySalary = $monthlySalary / 4;
        //     $hourlySalary = ($monthlySalary / $workdays->work_day_per_month) / 8;
        // }

        $response = array(
            "payPeriod" =>$salaries->pay_period_name,
            "emp" => $salaries->emp_uid,
            "name" => $salaries->lastname . ", " . $salaries->firstname,
            "basicSalary" => $salaries->base_salary,
            "weeklySalary" => $weeklySalary,
            "monthlySalary" => $monthlySalary,
            "hourlySalary" => $hourlySalary,
            "minSalary" => $minSalary,
            "daySalary" => $daySalary
        );
    }
    // echo jsonify($response);
    return $response;
}

function getDailySalary(){
    $salary = getSalary();
    $response = array();
    $workdays = getWorkDay();
    $weeklySalary = 0;
    $monthlySalary = 0;
    $hourlySalary = 0;

    foreach($salary as $salaries){
        $salaryName = $salaries->pay_period_name;
        switch($salaryName){
            case "Daily":
                // $weeklySalary = ($salaries->base_salary) * 6;
                $monthlySalary = ($salaries->base_salary) * $workdays->work_day_per_month;
                // $monthlySalary = ($salaries->base_salary) * 26;
                $weeklySalary = $monthlySalary / 4;
                $hourlySalary = ($salaries->base_salary) / 8;
                break;
            case "Weekly":
                $weeklySalary = $salaries->base_salary;
                $monthlySalary = ($salaries->base_salary) * 4;
                $hourlySalary = ($monthlySalary / $workdays->work_day_per_month) / 8;
                break;
            case "Semi-Monthly":
                $weeklySalary = ($salaries->base_salary) / 2;
                $monthlySalary = ($salaries->base_salary) * 2;
                $hourlySalary = ($monthlySalary / $workdays->work_day_per_month) / 8;
                break;
            case "Monthly":
                $weeklySalary = ($salaries->base_salary) / 4;
                $monthlySalary = $salaries->base_salary;
                $hourlySalary = (($salaries->base_salary) / $workdays->work_day_per_month) / 8;
                break;
        }//end of switch

        // else if(($salaries->pay_period_name) == "Quarterly"){
        //     $monthlySalary = ($salaries->base_salary) / 3;
        //     $weeklySalary = $monthlySalary / 4;
        //     $hourlySalary = ($monthlySalary / $workdays->work_day_per_month) / 8;
        // }

        // else if(($salaries->pay_period_name) == "Semi-Annual"){
        //     $monthlySalary = ($salaries->base_salary) / 6;
        //     $weeklySalary = $monthlySalary / 4;
        //     $hourlySalary = ($monthlySalary / $workdays->work_day_per_month) / 8;
        // }

        // else if(($salaries->pay_period_name) == "Annual"){
        //     $monthlySalary = ($salaries->base_salary) / 12;
        //     $weeklySalary = $monthlySalary / 4;
        //     $hourlySalary = ($monthlySalary / $workdays->work_day_per_month) / 8;
        // }

        $response[] = array(
            "payPeriod" =>$salaries->pay_period_name,
            "emp" => $salaries->emp_uid,
            "name" => $salaries->lastname . ", " . $salaries->firstname,
            "basicSalary" => $salaries->base_salary,
            "weeklySalary" => $weeklySalary,
            "monthlySalary" => $monthlySalary,
            "hourlySalary" => $hourlySalary
        );
    }
    // echo jsonify($response);
    return $response;
}

function timeAll($startDate, $endDate, $uid){
    $emps = getEmployeeByCostCenterUid($uid);
    foreach($emps as $emp){
        $id = $emp["emp_uid"];
        $empNo = $emp["username"];

        $startDates = strtotime($startDate);
        $endDates = strtotime($endDate);   

        for($i=$startDates; $i<=$endDates; $i+=86400){
            $date =  date("Y-m-d", $i);
            $day = date("l", $i);

            $a = getEmpByUid($id);
            if($a){
                $lastname = utf8_decode($a->lastname);
            }//end of getEmpByUid Function

            $work = 0;
            $late = 0;
            $overtime = 0;
            $undertime = 0;
            $c = getTimeIn($id, $date);
            $insss = date("Y-m-d", strtotime($c["date_created"]));

            $holiday = getHolidayByDate($date);
            $hDate = $holiday["date"];

            // $abDate = $date . " 00:00:00";

            $absent = getAbsentRequestByDateAndEmpUid($id, $date);
            if($absent){
                $absentDate = date("Y-m-d", strtotime($absent->start_date));
                $prompt = 5;
            }else{
                $absentDate = 0;
            }
            
            if($hDate == $date){
                if($hDate === $insss){
                    $holidayDate = $hDate;
                    $prompt = 1;
                    $time = $c["date_created"];
                }else{
                    $prompt = 3;
                    $time = "HOLIDAY";
                }
            }else if($absentDate === $date){
                $prompt = 5;
            }else if($insss != $date && $hDate != $date){
                $prompt = 0;
                $time = "ABSENT";
            }else{
                $holidayDate = 0;
                $prompt = 1;
                $time = $c["date_created"];
            }
            $restName = 0;
            $rest = getRestDayByDay($day);
            if($rest){
                $restName = $rest["name"];
            }//end of getting restDay

            if(date("l", $i) === $restName){
                $sun = date("Y-m-d", $i);
                $prompt = 2;
                $time = "Rest Day";
            }//end of comparing day

            $leave = getLeaveByEmpUidAndDate($id, $date);
            if($leave){
                $leaveStartDate = $leave->start_date;
                $leaveEndDate = $leave->end_date;

                $leaveDay = date("l", strtotime($date));
                if($leaveDay === $restName){
                    $prompt = 2;
                    $time = "Rest Day";
                }else{
                    $prompt = 4;
                    $time = "LEAVED";
                }
            }//end of getting leave
            

            switch ($prompt) {
                case 0:
                    $absentEmpId = $id;
                    $absentDate = $date;
                    $over = 0;

                    $absentDay = $day;
                    $offset = getAcceptedOffsetRequestByEmpUid($absentEmpId, $absentDate);
                    if($offset){
                        $offsetId = $offset["offset_uid"];
                        $offsetEmpUid = $offset["emp_uid"];
                        $offsetFromDate = $offset["from_date"];
                        $offsetSetDate = $offset["set_date"];
                        $offsetDay = date("N", strtotime($offsetSetDate));
                        // echo "$offsetFromDate = $offsetSetDate<br/>";
                        $ins = getOffsetTimeInByEmpUidAndDate($offsetEmpUid, $offsetFromDate);
                        foreach($ins as $inss){
                            $inId = $inss["time_log_uid"];
                            $in = $inss["date_created"];
                            $in1 = date("Y-m-d", strtotime($in));
                            $inDay = date("N", strtotime($in1));
                            $inSession = $inss["session"];

                            $outss = getTimeOutByEmpUidAndDateNoLoc($empId, $inSession);
                            $outId = $outss["time_log_uid"];
                            $out = $outss["date_created"];
                            $out1 = date("Y-m-d", strtotime($out));
                            $outHour = date("H:i:s", strtotime($out));
                            $inHour = date("H:i:s", strtotime($in));
                            $shift = getShiftByUidAndDate($absentEmpId, $in1, $offsetDay);
                            $shiftStart = $shift->start;
                            $shiftEnd = $shift->end;
                            $shiftEnds = $shiftEnd;
                            $shiftStarts = $shiftStart;
                            if(strtotime($shiftStart) < strtotime($shiftEnd)){
                                $shiftDuration = countDurationOfShifts($absentEmpId, $in1, $offsetDay);
                                $afterBreak = "13:00:00";
                                if(strtotime($inHour) >= strtotime($afterBreak)){
                                    $shiftDuration = $shiftDuration;
                                }else{
                                    $shiftDuration = $shiftDuration - 1;
                                }
                            }else{
                                $shiftStart = "2015-02-01 " . $shiftStart;
                                $shiftEnd = "2015-02-02 " . $shiftEnd;

                                $shiftDuration = countDurationOfShiftsReversed($absentEmpId, $shiftStart, $shiftEnd, $offsetDay, $in1);
                                $shiftDuration = $shiftDuration - 1;
                            }

                            if($out1 == $out1){
                                $over++;
                            }

                            $outArray = array(
                                "outHour" => $outHour, 
                                "out" => $out, 
                                "outDate" => $out1
                            );

                            $undertimeCounts = countDateOut($empId, $out1);
                            
                            $outHour = $outArray["outHour"];
                            $out = $outArray["out"];

                            /*---------------------OVERTIME---------------------*/

                            if(strtotime($shiftEnd) <= strtotime($outArray["outHour"])){
                                if($in1 === $out1){
                                    $overtime = (strtotime($outHour) - strtotime($shiftEnd)) / 3600;
                                }else{
                                    $shiftEnds = $out1 . $shiftEnds;
                                    $overtime = (strtotime($out) - strtotime($shiftEnds)) / 3600;
                                }
                            }else if(strtotime($shiftEnd) >= strtotime($outArray["outHour"])){
                                if($in1 === $out1){
                                    $overtime = 0;
                                }else{
                                    $shiftEnd = date("Y-m-d", strtotime($in . "- 0 day")) . " $shiftEnd"; 
                                    $overtime = (strtotime($out) - strtotime($shiftEnd)) / 3600;
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
                                $totalOvertime1 = $totalOvertime;
                                $overtimeHour1 = floor($totalOvertime1);
                                $totalOvertimeMin1 = (60*($totalOvertime1-$overtimeHour1));
                                $overtimeMin1 = floor(60*($totalOvertime1-$overtimeHour1));
                                $overtimeMin11 = floor($totalOvertimeMin1);
                                $overtimeSec1 = floor(60*($totalOvertimeMin1-$overtimeMin11));
                                $overtimess1 = new dateTime("$overtimeHour1:$overtimeMin1:$overtimeSec1");
                                $secondOut = date_format($overtimess1, "H:i:s");
                                /*---------------------END OF OVERTIME---------------------*/

                                /*---------------------UNDERTIME---------------------*/
                                $secs = strtotime($secondOut)-strtotime("00:00:00");

                                $offsetDay = date("N", strtotime($offsetSetDate));
                                $shift = getOffsetShiftByUidAndDay($absentEmpId, $offsetDay);
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
                                    "id" => $id,
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
                                    "empNo" => $empNo

                                );
                            }
                        }//end of getOffsetTimeInByEmpUidAndDate Function
                    }else{
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
                    }
                    break;
                case 1:
                    $empId = $id;
                    $empDate = $date;
                    $empNote = $time;
                    $empDay = $day;
                    $empHolidayDate = $holidayDate;
                    $check = checkTimeInByEmpUidAndDate($empId, $date);
                    if($check){
                        $ins = getTimeInByEmpUidAndDate($empId, $empDate);
                    }else{
                        $ins = getTimeInByEmpUidAndDateNoLoc($empId, $empDate);
                    }
                    
                    $late = 0;
                    $under = 0;

                    foreach($ins as $inss){
                        $inId = $inss["time_log_uid"];
                        $in = $inss["date_created"];
                        $in1 = date("Y-m-d", strtotime($in));
                        $inDay = date("N", strtotime($in1));
                        $inSession = $inss["session"];
                        
                        $locations = getInLocation($empId, $inSession, $date);
                        if($locations){
                            $inLoc = $locations["name"];
                            $outss = getTimeOutByEmpUidAndDate($empId, $inSession);
                            $outLoc = $outss["name"];
                        }else{
                            $inLoc = "--";
                            $outss = getTimeOutByEmpUidAndDateNoLoc($empId, $inSession);
                            $outLoc = "--";
                        }
                        
                        if(!$outss){
                            $response[] = array(
                                "id" => $id,
                                "inId" => $inId,
                                "outId" => "WALA PANG OUT!",
                                "prompt" => "",
                                "lastname" => strtoupper($lastname),
                                "dates" => $date,
                                "date" => date("M d, Y", strtotime($date)),
                                "day" => $day,
                                "in" => date("h:i:s A", strtotime($in)),
                                "out" => "WALA PANG OUT!",
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
                            $outId = $outss["time_log_uid"];
                            $out = $outss["date_created"];
                            $out1 = date("Y-m-d", strtotime($out));

                            $shift = getShiftByUidAndDate($empId, $in1, $inDay);
                            $shiftStart = $shift->start;
                            $shiftEnd = $shift->end;
                            $grace = $shift->grace_period;
                            $shiftEnds = $shiftEnd;
                            $shiftStarts = $shiftStart;

                            if($grace != 0){
                                $dapatIn = date("H:i:s", strtotime("+$grace minutes", strtotime($shiftStart)));
                            }else{
                                $dapatIn = date("H:i:s", strtotime($shiftStart));
                            }

                            $inss = date("H:i:s", strtotime($in));
                            $outss = date("H:i:s", strtotime($out));

                            /*WORKED FUNCTION*/
                            if(strtotime($out) < strtotime($in)){
                                $work = (strtotime($in) - strtotime($out)) / 3600;
                            }else if(strtotime($out) > strtotime($in)){
                                $work = (strtotime($out) - strtotime($in)) / 3600;
                            }

                            if(strtotime($shiftStart) < strtotime($shiftEnd)){
                                $shiftDuration = countDurationOfShifts($empId, $in1, $inDay);
                                $afterBreak = "13:00:00";

                                if(strtotime($inss) >= strtotime($afterBreak)){
                                    $shiftDuration = $shiftDuration;
                                }else{
                                    $shiftDuration = $shiftDuration - 1;
                                }
                            }else{
                                $shiftStart = "2015-02-01 " . $shiftStart;
                                $shiftEnd = "2015-02-02 " . $shiftEnd;

                                $shiftDuration = countDurationOfShiftsReversed($empId, $shiftStart, $shiftEnd, $inDay, $in1);
                                $shiftDuration = $shiftDuration - 1;
                            }

                            if($work === $shiftDuration){
                                $totalWork = $shiftDuration;
                            }else if($work > $shiftDuration){
                                $totalWork = $shiftDuration;
                            }else if($work < $shiftDuration){
                                $totalWork = $work;
                            }//end of getting total work


                            $inn = date("H:i:s", strtotime($in));
                            $inHour = date("H:i:s", strtotime($dapatIn));
                            /*END OF WORKED FUNCTION*/
                            $empDates = date("Y-m-d", strtotime($empDate . "+1 day"));
                            if($in1 == $empDate){
                                $late++;
                            }
                            if($out1 == $empDate){
                                $under++;
                            }else if($out1 == $empDates){
                                $under++;
                            }
                            $lates = 0;
                            $undertime = 0;
                            $over = 0;
                            $getFirstIn = array();
                            // /*LATE FUNCTION*/
                            $inArray[] = array(
                                "inHour" => $inn, 
                                "inDate" => $empDate
                            );
                            $lateCount = countDate($empId, $empDate);

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
                                    }else{
                                        $shiftStarts = $in1 . " " . $shiftStarts;
                                        $lates = (strtotime($in) - strtotime($shiftStarts)) / 3600;
                                    }
                                }
                            }//end of comparison for late
                            /*END OF LATE FUNCTION*/
                            $outHour = date("H:i:s", strtotime($out));

                            // /*OVERTIME FUNCTION*/
                            $undertimeCounts = countDateOut($empId, $out1);

                            // /*UNDERTIME FUNCTION*/ 
                            $getLastOut = array();
                            if($undertimeCounts === $under){
                                if(strtotime($outHour) <= strtotime($shiftEnds)){
                                    $undertimeCounts = countDateOut($empId, $out1);
                                    $outArray = array(
                                        "outHour" => $outHour, 
                                        "outDate" => $out1
                                    );
                                    
                                    $outHour = $outArray["outHour"];
                                    $empDate = $outArray["outDate"];
                                    $outss = $empDate . " " . $outHour;

                                    if($in1 === $out1){
                                        $undertime = (strtotime($shiftEnds) - strtotime($outHour)) / 3600;
                                    }else{
                                        $shiftEnds = $out1 . " " . $shiftEnds;
                                        $undertime = (strtotime($shiftEnds) - strtotime($outss)) / 3600;
                                    }
                                    
                                    // $undertime = (strtotime($shiftEnds) - strtotime($outHour)) / 3600;
                                }//end of comparison for undertime
                            }
                            $request = getOvertimeRequestByEmpUidAndDate($empId, $empDate, $empHolidayDate);
                            $requestStartDate = $request["start_date"];
                            $requestEmpId = $request["emp_uid"];
                            if($out1 === $out1){
                                $over++;
                            }

                            $outArray = array(
                                "outHour" => $outHour, 
                                "out" => $out, 
                                "outDate" => $out1
                            );

                            // if($undertimeCounts === $over){
                                $outHour = $outArray["outHour"];
                                $out = $outArray["out"];
                                if(strtotime($shiftEnd) <= strtotime($outHour)){
                                    if($in1 === $out1){
                                        $overtime = (strtotime($outHour) - strtotime($shiftEnd)) / 3600;
                                    }else{
                                        $shiftEnds = $out1 . $shiftEnds;
                                        $overtime = (strtotime($out) - strtotime($shiftEnds)) / 3600;
                                    }
                                }else if(strtotime($shiftEnd) >= strtotime($outHour)){
                                    if($in1 === $out1){
                                        $overtime = 0;
                                    }else{
                                        $shiftEnd = date("Y-m-d", strtotime($in . "- 0 day")) . " $shiftEnd"; 
                                        $overtime = (strtotime($out) - strtotime($shiftEnd)) / 3600;
                                    }
                                }//end of comparison for overtime
                                /*END OF UNDERTIME FUNCTION*/ 
                            // }//end of comparing count

                            if($overtime > 60){
                                $overtime = 0;
                            }else if($overtime <= -1 ){
                                $overtime = 0;
                            }
                            
                            $check = getOvertimeRequestByEmpUidAndDate($empId, $empDate, $empHolidayDate);
                            $checkEmpId = $check["emp_uid"];
                            $checkDate = $check["start_date"];
                            $oTstatus = 0;
                            $approvedDate = 0;

                            if(!$checkDate){

                            }else{
                                $approvedDate = $checkDate;
                                $oTstatus = 1;
                            }//end of checking
                            $totalWork = $totalWork - ($lates + $undertime);
                            $totalWork = abs($totalWork);

                            $workHour = floor($totalWork);
                            $totalWorkMin = (60*($totalWork-$workHour));
                            $workMin = floor(60*($totalWork-$workHour));
                            $workMin1 = floor($totalWorkMin);
                            $workSec = round(60*($totalWorkMin-$workMin1));

                            if($lates < 0){
                                $lates = 0;
                            }else{
                                $lates = $lates;
                            }
                            $totalLate = $lates;

                            $lateHour = floor($totalLate);
                            $totalLateMin = (60*($totalLate-$lateHour));
                            $lateMin = floor(60*($totalLate-$lateHour));
                            $lateMin1 = floor($totalLateMin);
                            $lateSec = round(60*($totalLateMin-$lateMin1));
                            if($lateMin >= 60){
                                $lateHour = $lateHour + 1;
                            }else{
                                $lateHour = $lateHour;
                            }

                            if($lateSec >= 60){
                                $lateSec = 0;
                                $lateMin = $lateMin + 1;
                            }else{
                                $lateSec = $lateSec;
                            }
                            $lates = new dateTime("$lateHour:$lateMin:$lateSec");

                            $totalOvertime = $overtime;
                            $overtimeHour = floor($totalOvertime);
                            $totalOvertimeMin = (60*($totalOvertime-$overtimeHour));
                            $overtimeMin = floor(60*($totalOvertime-$overtimeHour));
                            $overtimeMin1 = floor($totalOvertimeMin);
                            $overtimeSec = round(60*($totalOvertimeMin-$overtimeMin1));

                            if($overtimeMin >= 60){
                                $overtimeHour = $overtimeHour + 1;
                            }else{
                                $overtimeHour = $overtimeHour;
                            }
                            if($overtimeSec >= 60){
                                $overtimeSec = 0;
                                $overtimeMin = $overtimeMin + 1;
                            }else{
                                $overtimeSec = $overtimeSec;
                            }

                            $overtimes = "$overtimeHour:$overtimeMin:$overtimeSec";

                            if($totalOvertime >= 1){
                                $totalUndertime = 0;
                            }else{
                                $totalUndertime = $undertime;
                            }

                            $undertimeHour = floor($totalUndertime);
                            $totalUndertimeMin = (60*($totalUndertime-$undertimeHour));
                            $undertimeMin = floor(60*($totalUndertime-$undertimeHour));
                            $undertimeMin1 = floor($totalUndertimeMin);
                            $undertimeSec = round(60*($totalUndertimeMin-$undertimeMin1));

                            if($undertimeMin >= 60){
                                $undertimeMin = 0;
                                $undertimeHour = $undertimeHour + 1;
                            }else{
                                $undertimeMin = $undertimeMin;
                            }//end of checking overtime minute

                            if($lateSec >= 60){
                                $undertimeSec = 0;
                                $undertimeMin = $undertimeMin + 1;
                            }else{
                                $undertimeSec = $undertimeSec;
                            }
                            $undertimes = "$undertimeHour:$undertimeMin:$undertimeSec";
                            $worked = "$workHour:$workMin1:$workSec";

                            $check = checkTimeIsRequested($date, $id);

                            if($check){
                                $response[] = array(
                                    "id" => $id,
                                    "inId" => $inId,    
                                    "outId" => $outId,
                                    "prompt" => 7,
                                    "lastname" => strtoupper($lastname),
                                    "dates" => $date,
                                    "date" => date("M d, Y", strtotime($date)),
                                    "day" => $day,
                                    "in" => date("h:i:s A", strtotime($in)),
                                    "out" => date("h:i:s A", strtotime($out)),
                                    "late" => date_format($lates, "H:i:s"),
                                    "tardiness" => "",
                                    "overtime" => $overtimes,
                                    "undertime" => date("H:i:s", strtotime($undertimes)),
                                    "work" => date("H:i:s", strtotime($worked)),
                                    "totalWorked" => $totalWork,
                                    "totalLate" => $totalLate,
                                    "totalOvertime" => $totalOvertime,
                                    "totalUndertime" => $totalUndertime,
                                    "approveOTStatus" => $oTstatus,
                                    "location" => $inLoc . "=" . $outLoc,
                                    "empNo" => $empNo
                                );
                            }else{
                                $response[] = array(
                                    "id" => $id,
                                    "inId" => $inId,
                                    "outId" => $outId,
                                    "prompt" => $prompt,
                                    "lastname" => strtoupper($lastname),
                                    "dates" => $date,
                                    "date" => date("M d, Y", strtotime($date)),
                                    "day" => $day,
                                    "in" => date("h:i:s A", strtotime($in)),
                                    "out" => date("h:i:s A", strtotime($out)),
                                    "late" => date_format($lates, "H:i:s"),
                                    "tardiness" => "",
                                    "overtime" => $overtimes,
                                    "undertime" => date("H:i:s", strtotime($undertimes)),
                                    "work" => date("H:i:s", strtotime($worked)),
                                    "totalWorked" => $totalWork,
                                    "totalLate" => $totalLate,
                                    "totalOvertime" => $totalOvertime,
                                    "totalUndertime" => $totalUndertime,
                                    "approveOTStatus" => $oTstatus,
                                    "location" => $inLoc . "=" . $outLoc,
                                    "empNo" => $empNo

                                );
                            }
                        }
                    }//end of getTimeInByEmpUidAndDate Function
                    break;
                case 2:
                    $restId = $id;
                    $restDate = $date;
                    // $restNote = $time;
                    $restDay = $day;
                    $in = $time;
                    $out = $time;

                    $checkLoc = checkTimeInByEmpUidAndDate($id, $restDate);
                    if($checkLoc){
                        $ins = getTimeInByEmpUidAndDate($id, $restDate);
                    }else{
                        $ins = getTimeInByEmpUidAndDateNoLoc($id, $restDate);
                    }
                    $check = checkRestDay($restId, $restDate);
                    
                    $late = 0;
                    $under = 0;

                    if($check >= 1){
                        foreach($ins as $inss){
                            $inId = $inss["time_log_uid"];
                            $in = $inss["date_created"];
                            $in1 = date("Y-m-d", strtotime($in));
                            $inDay = date("N", strtotime($in1));
                            $inSession = $inss["session"];

                            $locations = getInLocation($restId, $inSession, $date);
                            if($locations){
                                $inLoc = $locations["name"];
                                $outss = getTimeOutByEmpUidAndDate($restId, $inSession);
                                $outLoc = $outss["name"];
                            }else{
                                $inLoc = "--";
                                $outss = getTimeOutByEmpUidAndDateNoLoc($restId, $inSession);
                                $outLoc = "--";
                            }
                            if(!$outss){
                                $response[] = array(
                                    "id" => $id,
                                    "inId" => $inId,
                                    "outId" => "WALA PANG OUT!",
                                    "prompt" => "",
                                    "lastname" => strtoupper($lastname),
                                    "dates" => $date,
                                    "date" => date("M d, Y", strtotime($date)),
                                    "day" => $day,
                                    "in" => date("h:i:s A", strtotime($in)),
                                    "out" => "WALA PANG OUT!",
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
                                $outId = $outss["time_log_uid"];
                                $out = $outss["date_created"];

                                $out1 = date("Y-m-d", strtotime($out));

                                $shift = getShiftByUidAndDate($restId, $date, $inDay);
                                $shiftStart = $shift->start;
                                $shiftEnd = $shift->end;
                                $grace = $shift->grace_period;
                                $shiftEnds = $shiftEnd;
                                $shiftStarts = $shiftStart;

                                if($grace != 0){
                                    $dapatIn = date("H:i:s", strtotime("+$grace minutes", strtotime($shiftStart)));
                                }else{
                                    $dapatIn = date("H:i:s", strtotime($shiftStart));
                                }
                                $inss = date("H:i:s", strtotime($in));
                                $outss = date("H:i:s", strtotime($out));

                                /*WORKED FUNCTION*/
                                if(strtotime($out) < strtotime($in)){
                                    $work = (strtotime($in) - strtotime($out)) / 3600;
                                }else if(strtotime($out) > strtotime($in)){
                                    $work = (strtotime($out) - strtotime($in)) / 3600;
                                }

                                if(strtotime($shiftStart) < strtotime($shiftEnd)){
                                    $shiftDuration = countDurationOfShifts($restId, $in1, $inDay);
                                    $afterBreak = "13:00:00";

                                    if(strtotime($inss) >= strtotime($afterBreak)){
                                        $shiftDuration = $shiftDuration;
                                    }else{
                                        $shiftDuration = $shiftDuration - 1;
                                    }
                                }else{
                                    $shiftStart = "2015-02-01 " . $shiftStart;
                                    $shiftEnd = "2015-02-02 " . $shiftEnd;

                                    $shiftDuration = countDurationOfShiftsReversed($restId, $shiftStart, $shiftEnd, $inDay, $in1);
                                    $shiftDuration = $shiftDuration - 1;
                                }

                                if($work === $shiftDuration){
                                    $totalWork = $shiftDuration;
                                }else if($work > $shiftDuration){
                                    $totalWork = $shiftDuration;
                                }else if($work < $shiftDuration){
                                    $totalWork = $work;
                                }

                                $inn = date("H:i:s", strtotime($in));
                                $inHour = date("H:i:s", strtotime($dapatIn));
                                /*END OF WORKED FUNCTION*/
                                $empDates = date("Y-m-d", strtotime($restDate . "+1 day"));
                                if($in1 == $restDate){
                                    $late++;
                                }
                                if($out1 == $restDate){
                                    $under++;
                                }else if($out1 == $empDates){
                                    $under++;
                                }
                                $lates = 0;
                                $undertime = 0;
                                $over = 0;
                                $getFirstIn = array();
                                // /*LATE FUNCTION*/
                                $inArray[] = array(
                                    "inHour" => $inn, 
                                    "inDate" => $restDate
                                );
                                $lateCount = countDate($restId, $restDate);

                                if(strtotime($inn) >= strtotime($inHour)){
                                    
                                    if($late === $lateCount){
                                        for($x=0; $x < count($inArray); $x++){
                                            if(in_array($restDate, $inArray[$x])){
                                                $getFirstIn[] = $inArray[$x];
                                            }//end of checking
                                        }//end of forloop
                                        $inn = ($getFirstIn[0]["inHour"]);
                                        $empDate = ($getFirstIn[0]["inDate"]);
                                        if($in1 === $out1){
                                            if(strtotime($inn) >= strtotime($afterBreak)){
                                                $lates = ((strtotime($inn) - strtotime($shiftStarts)) / 3600) - 1;
                                            }else{
                                                $lates = (strtotime($inn) - strtotime($shiftStarts)) / 3600;
                                            }
                                        }else{
                                            $shiftStarts = $in1 . " " . $shiftStarts;
                                            $lates = (strtotime($in) - strtotime($shiftStarts)) / 3600;
                                        }
                                    }
                                }//end of comparison for late
                                /*END OF LATE FUNCTION*/
                                $outHour = date("H:i:s", strtotime($out));

                                // /*OVERTIME FUNCTION*/
                                $undertimeCounts = countDateOut($restId, $out1);
                                // /*UNDERTIME FUNCTION*/ 
                                $getLastOut = array();
                                if($undertimeCounts === $under){
                                    if(strtotime($outHour) <= strtotime($shiftEnds)){
                                        $undertimeCounts = countDateOut($restId, $out1);
                                        $outArray = array(
                                            "outHour" => $outHour, 
                                            "outDate" => $out1
                                        );
                                        $outHour = $outArray["outHour"];
                                        $empDate = $outArray["outDate"];
                                        $outss = $empDate . " " . $outHour;

                                        if($in1 === $out1){
                                            $undertime = (strtotime($shiftEnds) - strtotime($outHour)) / 3600;
                                        }else{
                                            $shiftEnds = $out1 . " " . $shiftEnds;

                                            $undertime = (strtotime($shiftEnds) - strtotime($outss)) / 3600;
                                        }
                                    }//end of comparison for undertime
                                }
                                $request = getOvertimeRequestByEmpUidAndDate($restId, $restDate, $restDate);
                                $requestStartDate = $request["start_date"];
                                $requestEmpId = $request["emp_uid"];
                                if($out1 === $out1){
                                    $over++;
                                }

                                $outArray = array(
                                    "outHour" => $outHour, 
                                    "out" => $out, 
                                    "outDate" => $out1
                                );

                                // if($undertimeCounts === $over){
                                    $outHour = $outArray["outHour"];
                                    $out = $outArray["out"];
                                    if(strtotime($shiftEnd) <= strtotime($outHour)){
                                        if($in1 === $out1){
                                            $overtime = (strtotime($outHour) - strtotime($shiftEnd)) / 3600;
                                        }else{
                                            $shiftEnds = $out1 . $shiftEnds;
                                            $overtime = (strtotime($out) - strtotime($shiftEnds)) / 3600;
                                        }
                                    }else if(strtotime($shiftEnd) >= strtotime($outHour)){
                                        if($in1 === $out1){
                                            $overtime = 0;
                                        }else{
                                            $shiftEnd = date("Y-m-d", strtotime($in . "- 0 day")) . " $shiftEnd"; 
                                            $overtime = (strtotime($out) - strtotime($shiftEnd)) / 3600;
                                        }
                                    }//end of comparison for overtime
                                    /*END OF UNDERTIME FUNCTION*/ 
                                // }//end of comparing count

                                if($overtime > 60){
                                    $overtime = 0;
                                }else if($overtime <= -1 ){
                                    $overtime = 0;
                                }
                                
                                $check = getOvertimeRequestByEmpUidAndDate($restId, $restDate, $restDate);
                                $checkEmpId = $check["emp_uid"];
                                $checkDate = $check["start_date"];
                                $oTstatus = 0;
                                $approvedDate = 0;

                                if(!$checkDate){

                                }else{
                                    $approvedDate = $checkDate;
                                    $oTstatus = 1;
                                }//end of checking
                                $totalWork = $totalWork - ($lates + $undertime);
                                $totalWork = abs($totalWork);
                                $workHour = floor($totalWork);
                                $totalWorkMin = (60*($totalWork-$workHour));
                                $workMin = floor(60*($totalWork-$workHour));
                                $workMin1 = floor($totalWorkMin);
                                $workSec = round(60*($totalWorkMin-$workMin1));

                                if($lates < 0){
                                    $lates = 0;
                                }else{
                                    $lates = $lates;
                                }
                                $totalLate = $lates;

                                $lateHour = floor($totalLate);
                                $totalLateMin = (60*($totalLate-$lateHour));
                                $lateMin = floor(60*($totalLate-$lateHour));
                                $lateMin1 = floor($totalLateMin);
                                $lateSec = round(60*($totalLateMin-$lateMin1));
                                if($lateMin >= 60){
                                    $lateHour = $lateHour + 1;
                                }else{
                                    $lateHour = $lateHour;
                                }

                                if($lateSec >= 60){
                                    $lateSec = 0;
                                    $lateMin = $lateMin + 1;
                                }else{
                                    $lateSec = $lateSec;
                                }
                                $lates = new dateTime("$lateHour:$lateMin:$lateSec");

                                $totalOvertime = $overtime;
                                $overtimeHour = floor($totalOvertime);
                                $totalOvertimeMin = (60*($totalOvertime-$overtimeHour));
                                $overtimeMin = floor(60*($totalOvertime-$overtimeHour));
                                $overtimeMin1 = floor($totalOvertimeMin);
                                $overtimeSec = round(60*($totalOvertimeMin-$overtimeMin1));

                                if($overtimeMin >= 60){
                                    $overtimeHour = $overtimeHour + 1;
                                }else{
                                    $overtimeHour = $overtimeHour;
                                }
                                if($overtimeSec >= 60){
                                    $overtimeSec = 0;
                                    $overtimeMin = $overtimeMin + 1;
                                }else{
                                    $overtimeSec = $overtimeSec;
                                }

                                $overtimes = new dateTime("$overtimeHour:$overtimeMin:$overtimeSec");


                                $totalUndertime = $undertime;
                                $undertimeHour = floor($totalUndertime);
                                $totalUndertimeMin = (60*($totalUndertime-$undertimeHour));
                                $undertimeMin = floor(60*($totalUndertime-$undertimeHour));
                                $undertimeMin1 = floor($totalUndertimeMin);
                                $undertimeSec = round(60*($totalUndertimeMin-$undertimeMin1));

                                if($undertimeMin >= 60){
                                    $undertimeMin = 0;
                                    $undertimeHour = $undertimeHour + 1;
                                }else{
                                    $undertimeMin = $undertimeMin;
                                }//end of checking overtime minute

                                if($lateSec >= 60){
                                    $undertimeSec = 0;
                                    $undertimeMin = $undertimeMin + 1;
                                }else{
                                    $undertimeSec = $undertimeSec;
                                }
                                $undertimes = "$undertimeHour:$undertimeMin:$undertimeSec";
                                $worked = "$workHour:$workMin1:$workSec";

                                $response[] = array(
                                    "id" => $id,
                                    "inId" => $inId,
                                    "outId" => $outId,
                                    "prompt" => 1,
                                    "lastname" => strtoupper($lastname),
                                    "dates" => $date,
                                    "date" => date("M d, Y", strtotime($date)),
                                    "day" => $day,
                                    "in" => date("h:i:s A", strtotime($in)),
                                    "out" => date("h:i:s A", strtotime($out)),
                                    "late" => date_format($lates, "H:i:s"),
                                    "tardiness" => "",
                                    "overtime" => date_format($overtimes, "H:i:s"),
                                    "undertime" => date("H:i:s", strtotime($undertimes)),
                                    "work" => date("H:i:s", strtotime($worked)),
                                    "totalWorked" => $totalWork,
                                    "totalLate" => $totalLate,
                                    "totalOvertime" => $totalOvertime,
                                    "totalUndertime" => $totalUndertime,
                                    "approveOTStatus" => $oTstatus,
                                    "location" => $inLoc . "=" . $outLoc,
                                    "empNo" => $empNo

                                );
                            }
                        }//end of getTimeInByEmpUidAndDate Function
                    }else{
                        $response[] = array(
                            "id" => $id,
                            "inId" => 0,
                            "outId" => 0,
                            "prompt" => $prompt,
                            "lastname" => strtoupper($lastname),
                            "dates" => $date,
                            "error" => $time,
                            "date" => date("M d, Y", strtotime($date)),
                            "day" => $day,
                            "in" => "REST DAY",
                            "out" => "REST DAY",
                            "late" => "REST DAY",
                            "tardiness" => "REST DAY",
                            "overtime" => "REST DAY",
                            "undertime" => "REST DAY",
                            "work" => "REST DAY",
                            "totalWorked" => "REST DAY",
                            "totalLate" => "REST DAY",
                            "totalOvertime" => "REST DAY",
                            "totalUndertime" => "REST DAY",
                            "approveOTStatus" => "0",
                            "location" => "--=--",
                            "empNo" => $empNo

                        );
                    }
                    break;
                case 3:
                    $holidayEmpId = $id;
                    $holidayDate = $date;
                    // $absentNote = $time;
                    $holidayDay = $day;
                    $in = $time;
                    $out = $time;
                    $response[] = array(
                        "id" => $id,
                        "inId" => 0,
                        "outId" => 0,
                        "prompt" => $prompt,
                        "lastname" => strtoupper($lastname),
                        "dates" => $date,
                        "date" => date("M d, Y", strtotime($date)),
                        "day" => $day,
                        "in" => "HOLIDAY",
                        "out" => "HOLIDAY",
                        "late" => "HOLIDAY",
                        "tardiness" => "HOLIDAY",
                        "overtime" => "HOLIDAY",
                        "undertime" => "HOLIDAY",
                        "work" => "HOLIDAY",
                        "totalWorked" => "HOLIDAY",
                        "totalLate" => "HOLIDAY",
                        "totalOvertime" => "HOLIDAY",
                        "totalUndertime" => "HOLIDAY",
                        "approveOTStatus" => "0",
                        "location" => "--=--",
                        "empNo" => $empNo

                    );
                    break;
                case 4:
                    $leaveEmpId = $id;
                    $leaveDate = $date;
                    // $absentNote = $time;
                    $leaveDay = $day;
                    $in = $time;
                    $out = $time;

                    $response[] = array(
                        "id" => $id,
                        "inId" => 0,
                        "outId" => 0,
                        "prompt" => $prompt,
                        "lastname" => strtoupper($lastname),
                        "dates" => $date,
                        "date" => date("M d, Y", strtotime($date)),
                        "day" => $day,
                        "in" => "ON LEAVE",
                        "out" => "ON LEAVE",
                        "late" => "ON LEAVE",
                        "tardiness" => "ON LEAVE",
                        "overtime" => "ON LEAVE",
                        "undertime" => "ON LEAVE",
                        "work" => "ON LEAVE",
                        "totalWorked" => "ON LEAVE",
                        "totalLate" => "ON LEAVE",
                        "totalOvertime" => "ON LEAVE",
                        "totalUndertime" => "ON LEAVE",
                        "approveOTStatus" => "0",
                        "location" => "--=--",
                        "empNo" => $empNo

                    );
                    break;
                case 5:

                    $response[] = array(
                        "id" => $id,
                        "inId" => 0,
                        "outId" => 0,
                        "prompt" => $prompt,
                        "lastname" => strtoupper($lastname),
                        "dates" => $date,
                        "date" => date("M d, Y", strtotime($date)),
                        "day" => $day,
                        "in" => "ABSENT",
                        "out" => "ABSENT",
                        "late" => "ABSENT",
                        "tardiness" => "ABSENT",
                        "overtime" => "ABSENT",
                        "undertime" => "ABSENT",
                        "work" => "ABSENT",
                        "totalWorked" => "ABSENT",
                        "totalLate" => "ABSENT",
                        "totalOvertime" => "ABSENT",
                        "totalUndertime" => "ABSENT",
                        "approveOTStatus" => "0",
                        "location" => "--=--",
                        "empNo" => $empNo

                    );
                    break;
            }//end of switch for prompt
        }//end of for-loop

        foreach ($response as $k => $v) {
            $sort[$k] = $v["dates"];
            $sortLastname[$k] = $v["empNo"];

        }//end of response

        array_multisort($sortLastname, SORT_ASC, $sort, SORT_ASC, $response);
    }
    // echo jsonify($response);       
    return $response;
}

function timeByEmpUid($startDate, $endDate, $id){
    $startDates = strtotime($startDate);
    $endDates = strtotime($endDate);   

    for($i=$startDates; $i<=$endDates; $i+=86400){
        $date =  date("Y-m-d", $i);
        $day = date("l", $i);

        $a = getSingleCostCenterDataByEmpUid($id);
        if($a){
            $lastname = utf8_decode($a->lastname);
            $empNo = $a->username;
        }//end of getEmpByUid Function

        $work = 0;
        $late = 0;
        $overtime = 0;
        $undertime = 0;
        $c = getTimeIn($id, $date);
        $insss = date("Y-m-d", strtotime($c["date_created"]));

        $holiday = getHolidayByDate($date);
        $hDate = $holiday["date"];

        // $abDate = $date . " 00:00:00";

        $absent = getAbsentRequestByDateAndEmpUid($id, $date);
        if($absent){
            $absentDate = date("Y-m-d", strtotime($absent->start_date));
            $prompt = 5;
        }else{
            $absentDate = 0;
        }
        
        if($hDate == $date){
            if($hDate === $insss){
                $holidayDate = $hDate;
                $prompt = 1;
                $time = $c["date_created"];
            }else{
                $prompt = 3;
                $time = "HOLIDAY";
            }
        }else if($absentDate === $date){
            $prompt = 5;
        }else if($insss != $date && $hDate != $date){
            $prompt = 0;
            $time = "ABSENT";
        }else{
            $holidayDate = 0;
            $prompt = 1;
            $time = $c["date_created"];
        }
        $restName = 0;
        $rest = getRestDayByDay($day);
        if($rest){
            $restName = $rest["name"];
        }//end of getting restDay

        if(date("l", $i) === $restName){
            $sun = date("Y-m-d", $i);
            $prompt = 2;
            $time = "Rest Day";
        }//end of comparing day

        $leave = getLeaveByEmpUidAndDate($id, $date);
        if($leave){
            $leaveStartDate = $leave->start_date;
            $leaveEndDate = $leave->end_date;

            $leaveDay = date("l", strtotime($date));
            if($leaveDay === $restName){
                $prompt = 2;
                $time = "Rest Day";
            }else{
                $prompt = 4;
                $time = "LEAVED";
            }
        }//end of getting leave
        

        switch ($prompt) {
            case 0:
                $absentEmpId = $id;
                $absentDate = $date;
                $over = 0;

                $absentDay = $day;
                $offset = getAcceptedOffsetRequestByEmpUid($absentEmpId, $absentDate);
                if($offset){
                    $offsetId = $offset["offset_uid"];
                    $offsetEmpUid = $offset["emp_uid"];
                    $offsetFromDate = $offset["from_date"];
                    $offsetSetDate = $offset["set_date"];
                    $offsetDay = date("N", strtotime($offsetSetDate));
                    // echo "$offsetFromDate = $offsetSetDate<br/>";
                    $ins = getOffsetTimeInByEmpUidAndDate($offsetEmpUid, $offsetFromDate);
                    foreach($ins as $inss){
                        $inId = $inss["time_log_uid"];
                        $in = $inss["date_created"];
                        $in1 = date("Y-m-d", strtotime($in));
                        $inDay = date("N", strtotime($in1));
                        $inSession = $inss["session"];

                        $outss = getTimeOutByEmpUidAndDateNoLoc($empId, $inSession);
                        $outId = $outss["time_log_uid"];
                        $out = $outss["date_created"];
                        $out1 = date("Y-m-d", strtotime($out));
                        $outHour = date("H:i:s", strtotime($out));
                        $inHour = date("H:i:s", strtotime($in));
                        $shift = getShiftByUidAndDate($absentEmpId, $in1, $offsetDay);
                        $shiftStart = $shift->start;
                        $shiftEnd = $shift->end;
                        $shiftEnds = $shiftEnd;
                        $shiftStarts = $shiftStart;
                        if(strtotime($shiftStart) < strtotime($shiftEnd)){
                            $shiftDuration = countDurationOfShifts($absentEmpId, $in1, $offsetDay);
                            $afterBreak = "13:00:00";

                            if(strtotime($inHour) >= strtotime($afterBreak)){
                                $shiftDuration = ($shiftDuration - 1) / 2;
                            }else{
                                $shiftDuration = $shiftDuration - 1;
                            }
                        }else{
                            $shiftStart = "2015-02-01 " . $shiftStart;
                            $shiftEnd = "2015-02-02 " . $shiftEnd;

                            $shiftDuration = countDurationOfShiftsReversed($absentEmpId, $shiftStart, $shiftEnd, $offsetDay, $in1);
                            $shiftDuration = $shiftDuration - 1;
                        }

                        if($out1 == $out1){
                            $over++;
                        }

                        $outArray = array(
                            "outHour" => $outHour, 
                            "out" => $out, 
                            "outDate" => $out1
                        );

                        $undertimeCounts = countDateOut($empId, $out1);
                        
                        $outHour = $outArray["outHour"];
                        $out = $outArray["out"];

                        /*---------------------OVERTIME---------------------*/

                        if(strtotime($shiftEnd) <= strtotime($outArray["outHour"])){
                            if($in1 === $out1){
                                $overtime = (strtotime($outHour) - strtotime($shiftEnd)) / 3600;
                            }else{
                                $shiftEnds = $out1 . $shiftEnds;
                                $overtime = (strtotime($out) - strtotime($shiftEnds)) / 3600;
                            }
                        }else if(strtotime($shiftEnd) >= strtotime($outArray["outHour"])){
                            if($in1 === $out1){
                                $overtime = 0;
                            }else{
                                $shiftEnd = date("Y-m-d", strtotime($in . "- 0 day")) . " $shiftEnd"; 
                                $overtime = (strtotime($out) - strtotime($shiftEnd)) / 3600;
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
                            $totalOvertime1 = $totalOvertime;
                            $overtimeHour1 = floor($totalOvertime1);
                            $totalOvertimeMin1 = (60*($totalOvertime1-$overtimeHour1));
                            $overtimeMin1 = floor(60*($totalOvertime1-$overtimeHour1));
                            $overtimeMin11 = floor($totalOvertimeMin1);
                            $overtimeSec1 = floor(60*($totalOvertimeMin1-$overtimeMin11));
                            $overtimess1 = new dateTime("$overtimeHour1:$overtimeMin1:$overtimeSec1");
                            $secondOut = date_format($overtimess1, "H:i:s");
                            /*---------------------END OF OVERTIME---------------------*/

                            /*---------------------UNDERTIME---------------------*/
                            $secs = strtotime($secondOut)-strtotime("00:00:00");

                            $offsetDay = date("N", strtotime($offsetSetDate));
                            $shift = getOffsetShiftByUidAndDay($absentEmpId, $offsetDay);
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
                                /*---------------------END OF UNDERTIME---------------------*/
                            $response[] = array(
                                "id" => $id,
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
                                "empNo" => $empNo

                            );
                        }
                    }//end of getOffsetTimeInByEmpUidAndDate Function
                }else{
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
                }
                break;
            case 1:
                $empId = $id;
                $empDate = $date;
                $empNote = $time;
                $empDay = $day;
                $empHolidayDate = $holidayDate;
                $check = checkTimeInByEmpUidAndDate($empId, $date);
                if($check){
                    $ins = getTimeInByEmpUidAndDate($empId, $empDate);
                }else{
                    $ins = getTimeInByEmpUidAndDateNoLoc($empId, $empDate);
                }
                
                $late = 0;
                $under = 0;

                foreach($ins as $inss){
                    $inId = $inss["time_log_uid"];
                    $in = $inss["date_created"];
                    $in1 = date("Y-m-d", strtotime($in));
                    $inDay = date("N", strtotime($in1));
                    $inSession = $inss["session"];
                    
                    $locations = getInLocation($empId, $inSession, $date);
                    if($locations){
                        $inLoc = $locations["name"];
                        $outss = getTimeOutByEmpUidAndDate($empId, $inSession);
                        $outLoc = $outss["name"];
                    }else{
                        $inLoc = "--";
                        $outss = getTimeOutByEmpUidAndDateNoLoc($empId, $inSession);
                        $outLoc = "--";
                    }
                    
                    if(!$outss){
                        $response[] = array(
                            "id" => $id,
                            "inId" => $inId,
                            "outId" => "WALA PANG OUT!",
                            "prompt" => "",
                            "lastname" => strtoupper($lastname),
                            "dates" => $date,
                            "date" => date("M d, Y", strtotime($date)),
                            "day" => $day,
                            "in" => date("h:i:s A", strtotime($in)),
                            "out" => "WALA PANG OUT!",
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
                        $outId = $outss["time_log_uid"];
                        $out = $outss["date_created"];
                        $out1 = date("Y-m-d", strtotime($out));

                        $shift = getShiftByUidAndDate($empId, $in1, $inDay);
                        $shiftStart = $shift->start;
                        $shiftEnd = $shift->end;
                        $grace = $shift->grace_period;
                        $shiftEnds = $shiftEnd;
                        $shiftStarts = $shiftStart;

                        if($grace != 0){
                            $dapatIn = date("H:i:s", strtotime("+$grace minutes", strtotime($shiftStart)));
                        }else{
                            $dapatIn = date("H:i:s", strtotime($shiftStart));
                        }

                        $inss = date("H:i:s", strtotime($in));
                        $outss = date("H:i:s", strtotime($out));

                        /*WORKED FUNCTION*/
                        if(strtotime($out) < strtotime($in)){
                            $work = (strtotime($in) - strtotime($out)) / 3600;
                        }else if(strtotime($out) > strtotime($in)){
                            $work = (strtotime($out) - strtotime($in)) / 3600;
                        }

                        if(strtotime($shiftStart) < strtotime($shiftEnd)){
                            $shiftDuration = countDurationOfShifts($empId, $in1, $inDay);
                            $afterBreak = "13:00:00";
                            if(strtotime($inss) >= strtotime($afterBreak)){
                                $shiftDuration = ($shiftDuration - 1) / 2;
                            }else{
                                $shiftDuration = $shiftDuration - 1;
                            }
                        }else{
                            $shiftStart = "2015-02-01 " . $shiftStart;
                            $shiftEnd = "2015-02-02 " . $shiftEnd;

                            $shiftDuration = countDurationOfShiftsReversed($empId, $shiftStart, $shiftEnd, $inDay, $in1);
                            $shiftDuration = $shiftDuration - 1;
                            $afterBreak = "13:00:00";
                        }
                        

                        // echo "$shiftHalf<br/>";
                        if($work === $shiftDuration){
                            $totalWork = $shiftDuration;
                        }else if($work > $shiftDuration){
                            $totalWork = $shiftDuration;
                            // echo "$id = " . $count3 - $excessTime . "<br/>";
                        }else if($work <= $shiftDuration){
                            $totalWork = $work;
                        }//end of getting total work

                        $inn = date("H:i:s", strtotime($in));
                        $inHour = date("H:i:s", strtotime($dapatIn));
                        /*END OF WORKED FUNCTION*/
                        $empDates = date("Y-m-d", strtotime($empDate . "+1 day"));
                        if($in1 == $empDate){
                            $late++;
                        }
                        if($out1 == $empDate){
                            $under++;
                        }else if($out1 == $empDates){
                            $under++;
                        }
                        $lates = 0;
                        $undertime = 0;
                        $over = 0;
                        $getFirstIn = array();
                        // /*LATE FUNCTION*/
                        $inArray[] = array(
                            "inHour" => $inn, 
                            "inDate" => $empDate
                        );
                        $lateCount = countDate($empId, $empDate);

                        if(strtotime($inn) >= strtotime($inHour)){
                            
                            if($late === $lateCount){
                                for($x=0; $x < count($inArray); $x++){
                                    if(in_array($empDate, $inArray[$x])){
                                        $getFirstIn[] = $inArray[$x];
                                    }//end of checking
                                }//end of forloop
                                $inn = ($getFirstIn[0]["inHour"]);
                                $empDate = ($getFirstIn[0]["inDate"]);
                                if($in1 === $out1){
                                    if(strtotime($inn) >= strtotime($afterBreak)){
                                        $lates = ((strtotime($inn) - strtotime($shiftStarts)) / 3600) - 1;
                                    }else{
                                        $lates = (strtotime($inn) - strtotime($shiftStarts)) / 3600;
                                    }
                                }else{
                                    $shiftStarts = $in1 . " " . $shiftStarts;
                                    $lates = (strtotime($in) - strtotime($shiftStarts)) / 3600;
                                }
                            }
                        }//end of comparison for late
                        /*END OF LATE FUNCTION*/
                        $outHour = date("H:i:s", strtotime($out));

                        // /*OVERTIME FUNCTION*/
                        $undertimeCounts = countDateOut($empId, $out1);

                        // /*UNDERTIME FUNCTION*/ 
                        $getLastOut = array();
                        if($undertimeCounts === $under){
                            if(strtotime($outHour) <= strtotime($shiftEnds)){
                                $undertimeCounts = countDateOut($empId, $out1);
                                $outArray = array(
                                    "outHour" => $outHour, 
                                    "outDate" => $out1
                                );
                                
                                $outHour = $outArray["outHour"];
                                $empDate = $outArray["outDate"];
                                $outss = $empDate . " " . $outHour;

                                if($in1 === $out1){
                                    $undertime = (strtotime($shiftEnds) - strtotime($outHour)) / 3600;
                                }else{
                                    $shiftEnds = $out1 . " " . $shiftEnds;
                                    $undertime = (strtotime($shiftEnds) - strtotime($outss)) / 3600;
                                }
                                
                                // $undertime = (strtotime($shiftEnds) - strtotime($outHour)) / 3600;
                            }//end of comparison for undertime
                        }
                        $request = getOvertimeRequestByEmpUidAndDate($empId, $empDate, $empHolidayDate);
                        $requestStartDate = $request["start_date"];
                        $requestEmpId = $request["emp_uid"];
                        if($out1 === $out1){
                            $over++;
                        }

                        $outArray = array(
                            "outHour" => $outHour, 
                            "out" => $out, 
                            "outDate" => $out1
                        );

                        // if($undertimeCounts === $over){
                            $outHour = $outArray["outHour"];
                            $out = $outArray["out"];
                            if(strtotime($shiftEnd) <= strtotime($outHour)){
                                if($in1 === $out1){
                                    $overtime = (strtotime($outHour) - strtotime($shiftEnd)) / 3600;
                                }else{
                                    $shiftEnds = $out1 . $shiftEnds;
                                    $overtime = (strtotime($out) - strtotime($shiftEnds)) / 3600;
                                }
                            }else if(strtotime($shiftEnd) >= strtotime($outHour)){
                                if($in1 === $out1){
                                    $overtime = 0;
                                }else{
                                    $shiftEnd = date("Y-m-d", strtotime($in . "- 0 day")) . " $shiftEnd"; 
                                    $overtime = (strtotime($out) - strtotime($shiftEnd)) / 3600;
                                }
                            }//end of comparison for overtime
                            /*END OF UNDERTIME FUNCTION*/ 
                        // }//end of comparing count

                        if($overtime > 60){
                            $overtime = 0;
                        }else if($overtime <= -1 ){
                            $overtime = 0;
                        }
                        
                        $check = getOvertimeRequestByEmpUidAndDate($empId, $empDate, $empHolidayDate);
                        $checkEmpId = $check["emp_uid"];
                        $checkDate = $check["start_date"];
                        $oTstatus = 0;
                        $approvedDate = 0;

                        if(!$checkDate){

                        }else{
                            $approvedDate = $checkDate;
                            $oTstatus = 1;
                        }//end of checking
                        $totalWork = $totalWork;
                        $totalWork = abs($totalWork);
                        $workHour = floor($totalWork);
                        $totalWorkMin = (60*($totalWork-$workHour));
                        $workMin = floor(60*($totalWork-$workHour));
                        $workMin1 = floor($totalWorkMin);
                        $workSec = round(60*($totalWorkMin-$workMin1));

                        if($lates < 0){
                            $lates = 0;
                        }else{
                            $lates = $lates;
                        }
                        $totalLate = $lates;

                        $lateHour = floor($totalLate);
                        $totalLateMin = (60*($totalLate-$lateHour));
                        $lateMin = floor(60*($totalLate-$lateHour));
                        $lateMin1 = floor($totalLateMin);
                        $lateSec = round(60*($totalLateMin-$lateMin1));
                        if($lateMin >= 60){
                            $lateHour = $lateHour + 1;
                        }else{
                            $lateHour = $lateHour;
                        }

                        if($lateSec >= 60){
                            $lateSec = 0;
                            $lateMin = $lateMin + 1;
                        }else{
                            $lateSec = $lateSec;
                        }
                        $lates = new dateTime("$lateHour:$lateMin:$lateSec");

                        $totalOvertime = $overtime;
                        $overtimeHour = floor($totalOvertime);
                        $totalOvertimeMin = (60*($totalOvertime-$overtimeHour));
                        $overtimeMin = floor(60*($totalOvertime-$overtimeHour));
                        $overtimeMin1 = floor($totalOvertimeMin);
                        $overtimeSec = round(60*($totalOvertimeMin-$overtimeMin1));

                        if($overtimeMin >= 60){
                            $overtimeHour = $overtimeHour + 1;
                        }else{
                            $overtimeHour = $overtimeHour;
                        }
                        if($overtimeSec >= 60){
                            $overtimeSec = 0;
                            $overtimeMin = $overtimeMin + 1;
                        }else{
                            $overtimeSec = $overtimeSec;
                        }

                        $overtimes = "$overtimeHour:$overtimeMin:$overtimeSec";

                        if($totalOvertime >= 1){
                            $totalUndertime = 0;
                        }else{
                            $totalUndertime = $undertime;
                        }

                        $undertimeHour = floor($totalUndertime);
                        $totalUndertimeMin = (60*($totalUndertime-$undertimeHour));
                        $undertimeMin = floor(60*($totalUndertime-$undertimeHour));
                        $undertimeMin1 = floor($totalUndertimeMin);
                        $undertimeSec = round(60*($totalUndertimeMin-$undertimeMin1));

                        if($undertimeMin >= 60){
                            $undertimeMin = 0;
                            $undertimeHour = $undertimeHour + 1;
                        }else{
                            $undertimeMin = $undertimeMin;
                        }//end of checking overtime minute

                        if($lateSec >= 60){
                            $undertimeSec = 0;
                            $undertimeMin = $undertimeMin + 1;
                        }else{
                            $undertimeSec = $undertimeSec;
                        }
                        $undertimes = "$undertimeHour:$undertimeMin:$undertimeSec";
                        $worked = "$workHour:$workMin1:$workSec";
                        // echo "$date = $worked<br/>";
                        
                        $check = checkTimeIsRequested($date, $id);

                        if($check){
                            $response[] = array(
                                "id" => $id,
                                "inId" => $inId,    
                                "outId" => $outId,
                                "prompt" => 7,
                                "lastname" => strtoupper($lastname),
                                "dates" => $date,
                                "date" => date("M d, Y", strtotime($date)),
                                "day" => $day,
                                "in" => date("h:i:s A", strtotime($in)),
                                "out" => date("h:i:s A", strtotime($out)),
                                "late" => date_format($lates, "H:i:s"),
                                "tardiness" => "",
                                "overtime" => $overtimes,
                                "undertime" => date("H:i:s", strtotime($undertimes)),
                                "work" => date("H:i:s", strtotime($worked)),
                                "totalWorked" => $totalWork,
                                "totalLate" => $totalLate,
                                "totalOvertime" => $totalOvertime,
                                "totalUndertime" => $totalUndertime,
                                "approveOTStatus" => $oTstatus,
                                "location" => $inLoc . "=" . $outLoc,
                                "empNo" => $empNo
                            );
                        }else{
                            $response[] = array(
                                "id" => $id,
                                "inId" => $inId,
                                "outId" => $outId,
                                "prompt" => $prompt,
                                "lastname" => strtoupper($lastname),
                                "dates" => $date,
                                "date" => date("M d, Y", strtotime($date)),
                                "day" => $day,
                                "in" => date("h:i:s A", strtotime($in)),
                                "out" => date("h:i:s A", strtotime($out)),
                                "late" => date_format($lates, "H:i:s"),
                                "tardiness" => "",
                                "overtime" => $overtimes,
                                "undertime" => date("H:i:s", strtotime($undertimes)),
                                "work" => date("H:i:s", strtotime($worked)),
                                "totalWorked" => $totalWork,
                                "totalLate" => $totalLate,
                                "totalOvertime" => $totalOvertime,
                                "totalUndertime" => $totalUndertime,
                                "approveOTStatus" => $oTstatus,
                                "location" => $inLoc . "=" . $outLoc,
                                "empNo" => $empNo

                            );
                        }
                    }
                }//end of getTimeInByEmpUidAndDate Function
                break;
            case 2:
                $restId = $id;
                $restDate = $date;
                // $restNote = $time;
                $restDay = $day;
                $in = $time;
                $out = $time;

                $checkLoc = checkTimeInByEmpUidAndDate($id, $restDate);
                if($checkLoc){
                    $ins = getTimeInByEmpUidAndDate($id, $restDate);
                }else{
                    $ins = getTimeInByEmpUidAndDateNoLoc($id, $restDate);
                }
                $check = checkRestDay($restId, $restDate);
                
                $late = 0;
                $under = 0;

                if($check >= 1){
                    foreach($ins as $inss){
                        $inId = $inss["time_log_uid"];
                        $in = $inss["date_created"];
                        $in1 = date("Y-m-d", strtotime($in));
                        $inDay = date("N", strtotime($in1));
                        $inSession = $inss["session"];

                        $locations = getInLocation($restId, $inSession, $date);
                        if($locations){
                            $inLoc = $locations["name"];
                            $outss = getTimeOutByEmpUidAndDate($restId, $inSession);
                            $outLoc = $outss["name"];
                        }else{
                            $inLoc = "--";
                            $outss = getTimeOutByEmpUidAndDateNoLoc($restId, $inSession);
                            $outLoc = "--";
                        }
                        if(!$outss){
                            $response[] = array(
                                "id" => $id,
                                "inId" => $inId,
                                "outId" => "WALA PANG OUT!",
                                "prompt" => "",
                                "lastname" => strtoupper($lastname),
                                "dates" => $date,
                                "date" => date("M d, Y", strtotime($date)),
                                "day" => $day,
                                "in" => date("h:i:s A", strtotime($in)),
                                "out" => "WALA PANG OUT!",
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
                            $outId = $outss["time_log_uid"];
                            $out = $outss["date_created"];

                            $out1 = date("Y-m-d", strtotime($out));

                            $shift = getShiftByUidAndDate($restId, $restDate, $restDay);
                            $shiftStart = $shift->start;
                            $shiftEnd = $shift->end;
                            $grace = $shift->grace_period;
                            $shiftEnds = $shiftEnd;
                            $shiftStarts = $shiftStart;

                            if($grace != 0){
                                $dapatIn = date("H:i:s", strtotime("+$grace minutes", strtotime($shiftStart)));
                            }else{
                                $dapatIn = date("H:i:s", strtotime($shiftStart));
                            }
                            $inss = date("H:i:s", strtotime($in));
                            $outss = date("H:i:s", strtotime($out));

                            /*WORKED FUNCTION*/
                            if(strtotime($out) < strtotime($in)){
                                $work = (strtotime($in) - strtotime($out)) / 3600;
                            }else if(strtotime($out) > strtotime($in)){
                                $work = (strtotime($out) - strtotime($in)) / 3600;
                            }

                            if(strtotime($shiftStart) < strtotime($shiftEnd)){
                                $shiftDuration = countDurationOfShifts($restId, $in1, $inDay);
                                $afterBreak = "13:00:00";

                                if(strtotime($inss) >= strtotime($afterBreak)){
                                    $shiftDuration = ($shiftDuration - 1) / 2;
                                }else{
                                    $shiftDuration = $shiftDuration - 1;
                                }
                            }else{
                                $shiftStart = "2015-02-01 " . $shiftStart;
                                $shiftEnd = "2015-02-02 " . $shiftEnd;

                                $shiftDuration = countDurationOfShiftsReversed($restId, $shiftStart, $shiftEnd, $inDay, $in1);
                                $shiftDuration = $shiftDuration - 1;
                            }

                            // echo "$shiftHalf<br/>";
                            if($work === $shiftDuration){
                                $totalWork = $shiftDuration;
                            }else if($work > $shiftDuration){
                                $totalWork = $shiftDuration;
                                // echo "$id = " . $count3 - $excessTime . "<br/>";
                            }else if($work <= $shiftDuration){
                                $totalWork = $work;
                            }//end of getting total work

                            $inn = date("H:i:s", strtotime($in));
                            $inHour = date("H:i:s", strtotime($dapatIn));
                            /*END OF WORKED FUNCTION*/
                            $empDates = date("Y-m-d", strtotime($restDate . "+1 day"));
                            if($in1 == $restDate){
                                $late++;
                            }
                            if($out1 == $restDate){
                                $under++;
                            }else if($out1 == $empDates){
                                $under++;
                            }
                            $lates = 0;
                            $undertime = 0;
                            $over = 0;
                            $getFirstIn = array();
                            // /*LATE FUNCTION*/
                            $inArray[] = array(
                                "inHour" => $inn, 
                                "inDate" => $restDate
                            );
                            $lateCount = countDate($restId, $restDate);

                            if(strtotime($inn) >= strtotime($inHour)){
                                
                                if($late === $lateCount){
                                    for($x=0; $x < count($inArray); $x++){
                                        if(in_array($restDate, $inArray[$x])){
                                            $getFirstIn[] = $inArray[$x];
                                        }//end of checking
                                    }//end of forloop
                                    $inn = ($getFirstIn[0]["inHour"]);
                                    $empDate = ($getFirstIn[0]["inDate"]);
                                    if($in1 === $out1){
                                        if(strtotime($inn) >= strtotime($afterBreak)){
                                            $lates = ((strtotime($inn) - strtotime($shiftStarts)) / 3600) - 1;
                                        }else{
                                            $lates = (strtotime($inn) - strtotime($shiftStarts)) / 3600;
                                        }
                                    }else{
                                        $shiftStarts = $in1 . " " . $shiftStarts;
                                        $lates = (strtotime($in) - strtotime($shiftStarts)) / 3600;
                                    }
                                }
                            }//end of comparison for late
                            /*END OF LATE FUNCTION*/
                            $outHour = date("H:i:s", strtotime($out));

                            // /*OVERTIME FUNCTION*/
                            $undertimeCounts = countDateOut($restId, $out1);
                            // /*UNDERTIME FUNCTION*/ 
                            $getLastOut = array();
                            if($undertimeCounts === $under){
                                if(strtotime($outHour) <= strtotime($shiftEnds)){
                                    $undertimeCounts = countDateOut($restId, $out1);
                                    $outArray = array(
                                        "outHour" => $outHour, 
                                        "outDate" => $out1
                                    );
                                    $outHour = $outArray["outHour"];
                                    $empDate = $outArray["outDate"];
                                    $outss = $empDate . " " . $outHour;

                                    if($in1 === $out1){
                                        $undertime = (strtotime($shiftEnds) - strtotime($outHour)) / 3600;
                                    }else{
                                        $shiftEnds = $out1 . " " . $shiftEnds;

                                        $undertime = (strtotime($shiftEnds) - strtotime($outss)) / 3600;
                                    }
                                }//end of comparison for undertime
                            }
                            $request = getOvertimeRequestByEmpUidAndDate($restId, $restDate, $restDate);
                            $requestStartDate = $request["start_date"];
                            $requestEmpId = $request["emp_uid"];
                            if($out1 === $out1){
                                $over++;
                            }

                            $outArray = array(
                                "outHour" => $outHour, 
                                "out" => $out, 
                                "outDate" => $out1
                            );

                            // if($undertimeCounts === $over){
                                $outHour = $outArray["outHour"];
                                $out = $outArray["out"];
                                if(strtotime($shiftEnd) <= strtotime($outHour)){
                                    if($in1 === $out1){
                                        $overtime = (strtotime($outHour) - strtotime($shiftEnd)) / 3600;
                                    }else{
                                        $shiftEnds = $out1 . $shiftEnds;
                                        $overtime = (strtotime($out) - strtotime($shiftEnds)) / 3600;
                                    }
                                }else if(strtotime($shiftEnd) >= strtotime($outHour)){
                                    if($in1 === $out1){
                                        $overtime = 0;
                                    }else{
                                        $shiftEnd = date("Y-m-d", strtotime($in . "- 0 day")) . " $shiftEnd"; 
                                        $overtime = (strtotime($out) - strtotime($shiftEnd)) / 3600;
                                    }
                                }//end of comparison for overtime
                                /*END OF UNDERTIME FUNCTION*/ 
                            // }//end of comparing count

                            if($overtime > 60){
                                $overtime = 0;
                            }else if($overtime <= -1 ){
                                $overtime = 0;
                            }
                            
                            $check = getOvertimeRequestByEmpUidAndDate($restId, $restDate, $restDate);
                            $checkEmpId = $check["emp_uid"];
                            $checkDate = $check["start_date"];
                            $oTstatus = 0;
                            $approvedDate = 0;

                            if(!$checkDate){

                            }else{
                                $approvedDate = $checkDate;
                                $oTstatus = 1;
                            }//end of checking
                            $totalWork = $totalWork - ($lates + $undertime);
                            $workHour = floor($totalWork);
                            $totalWorkMin = (60*($totalWork-$workHour));
                            $workMin = floor(60*($totalWork-$workHour));
                            $workMin1 = floor($totalWorkMin);
                            $workSec = round(60*($totalWorkMin-$workMin1));

                            if($lates < 0){
                                $lates = 0;
                            }else{
                                $lates = $lates;
                            }
                            $totalLate = $lates;

                            $lateHour = floor($totalLate);
                            $totalLateMin = (60*($totalLate-$lateHour));
                            $lateMin = floor(60*($totalLate-$lateHour));
                            $lateMin1 = floor($totalLateMin);
                            $lateSec = round(60*($totalLateMin-$lateMin1));
                            if($lateMin >= 60){
                                $lateHour = $lateHour + 1;
                            }else{
                                $lateHour = $lateHour;
                            }

                            if($lateSec >= 60){
                                $lateSec = 0;
                                $lateMin = $lateMin + 1;
                            }else{
                                $lateSec = $lateSec;
                            }
                            $lates = new dateTime("$lateHour:$lateMin:$lateSec");

                            $totalOvertime = $overtime;
                            $overtimeHour = floor($totalOvertime);
                            $totalOvertimeMin = (60*($totalOvertime-$overtimeHour));
                            $overtimeMin = floor(60*($totalOvertime-$overtimeHour));
                            $overtimeMin1 = floor($totalOvertimeMin);
                            $overtimeSec = round(60*($totalOvertimeMin-$overtimeMin1));

                            if($overtimeMin >= 60){
                                $overtimeHour = $overtimeHour + 1;
                            }else{
                                $overtimeHour = $overtimeHour;
                            }
                            if($overtimeSec >= 60){
                                $overtimeSec = 0;
                                $overtimeMin = $overtimeMin + 1;
                            }else{
                                $overtimeSec = $overtimeSec;
                            }

                            $overtimes = new dateTime("$overtimeHour:$overtimeMin:$overtimeSec");


                            $totalUndertime = $undertime;
                            $undertimeHour = floor($totalUndertime);
                            $totalUndertimeMin = (60*($totalUndertime-$undertimeHour));
                            $undertimeMin = floor(60*($totalUndertime-$undertimeHour));
                            $undertimeMin1 = floor($totalUndertimeMin);
                            $undertimeSec = round(60*($totalUndertimeMin-$undertimeMin1));

                            if($undertimeMin >= 60){
                                $undertimeMin = 0;
                                $undertimeHour = $undertimeHour + 1;
                            }else{
                                $undertimeMin = $undertimeMin;
                            }//end of checking overtime minute

                            if($lateSec >= 60){
                                $undertimeSec = 0;
                                $undertimeMin = $undertimeMin + 1;
                            }else{
                                $undertimeSec = $undertimeSec;
                            }
                            $undertimes = "$undertimeHour:$undertimeMin:$undertimeSec";
                            $worked = "$workHour:$workMin1:$workSec";

                            $response[] = array(
                                "id" => $id,
                                "inId" => $inId,
                                "outId" => $outId,
                                "prompt" => 1,
                                "lastname" => strtoupper($lastname),
                                "dates" => $date,
                                "date" => date("M d, Y", strtotime($date)),
                                "day" => $day,
                                "in" => date("h:i:s A", strtotime($in)),
                                "out" => date("h:i:s A", strtotime($out)),
                                "late" => date_format($lates, "H:i:s"),
                                "tardiness" => "",
                                "overtime" => date_format($overtimes, "H:i:s"),
                                "undertime" => date("H:i:s", strtotime($undertimes)),
                                "work" => date("H:i:s", strtotime($worked)),
                                "totalWorked" => $totalWork,
                                "totalLate" => $totalLate,
                                "totalOvertime" => $totalOvertime,
                                "totalUndertime" => $totalUndertime,
                                "approveOTStatus" => $oTstatus,
                                "location" => $inLoc . "=" . $outLoc,
                                "empNo" => $empNo

                            );
                        }
                    }//end of getTimeInByEmpUidAndDate Function
                }else{
                    $response[] = array(
                        "id" => $id,
                        "inId" => 0,
                        "outId" => 0,
                        "prompt" => $prompt,
                        "lastname" => strtoupper($lastname),
                        "dates" => $date,
                        "error" => $time,
                        "date" => date("M d, Y", strtotime($date)),
                        "day" => $day,
                        "in" => "REST DAY",
                        "out" => "REST DAY",
                        "late" => "REST DAY",
                        "tardiness" => "REST DAY",
                        "overtime" => "REST DAY",
                        "undertime" => "REST DAY",
                        "work" => "REST DAY",
                        "totalWorked" => "REST DAY",
                        "totalLate" => "REST DAY",
                        "totalOvertime" => "REST DAY",
                        "totalUndertime" => "REST DAY",
                        "approveOTStatus" => "0",
                        "location" => "--=--",
                        "empNo" => $empNo

                    );
                }
                break;
            case 3:
                $holidayEmpId = $id;
                $holidayDate = $date;
                // $absentNote = $time;
                $holidayDay = $day;
                $in = $time;
                $out = $time;
                $response[] = array(
                    "id" => $id,
                    "inId" => 0,
                    "outId" => 0,
                    "prompt" => $prompt,
                    "lastname" => strtoupper($lastname),
                    "dates" => $date,
                    "date" => date("M d, Y", strtotime($date)),
                    "day" => $day,
                    "in" => "HOLIDAY",
                    "out" => "HOLIDAY",
                    "late" => "HOLIDAY",
                    "tardiness" => "HOLIDAY",
                    "overtime" => "HOLIDAY",
                    "undertime" => "HOLIDAY",
                    "work" => "HOLIDAY",
                    "totalWorked" => "HOLIDAY",
                    "totalLate" => "HOLIDAY",
                    "totalOvertime" => "HOLIDAY",
                    "totalUndertime" => "HOLIDAY",
                    "approveOTStatus" => "0",
                    "location" => "--=--",
                    "empNo" => $empNo

                );
                break;
            case 4:
                $leaveEmpId = $id;
                $leaveDate = $date;
                // $absentNote = $time;
                $leaveDay = $day;
                $in = $time;
                $out = $time;

                $response[] = array(
                    "id" => $id,
                    "inId" => 0,
                    "outId" => 0,
                    "prompt" => $prompt,
                    "lastname" => strtoupper($lastname),
                    "dates" => $date,
                    "date" => date("M d, Y", strtotime($date)),
                    "day" => $day,
                    "in" => "ON LEAVE",
                    "out" => "ON LEAVE",
                    "late" => "ON LEAVE",
                    "tardiness" => "ON LEAVE",
                    "overtime" => "ON LEAVE",
                    "undertime" => "ON LEAVE",
                    "work" => "ON LEAVE",
                    "totalWorked" => "ON LEAVE",
                    "totalLate" => "ON LEAVE",
                    "totalOvertime" => "ON LEAVE",
                    "totalUndertime" => "ON LEAVE",
                    "approveOTStatus" => "0",
                    "location" => "--=--",
                    "empNo" => $empNo

                );
                break;
            case 5:

                $response[] = array(
                    "id" => $id,
                    "inId" => 0,
                    "outId" => 0,
                    "prompt" => $prompt,
                    "lastname" => strtoupper($lastname),
                    "dates" => $date,
                    "date" => date("M d, Y", strtotime($date)),
                    "day" => $day,
                    "in" => "ABSENT",
                    "out" => "ABSENT",
                    "late" => "ABSENT",
                    "tardiness" => "ABSENT",
                    "overtime" => "ABSENT",
                    "undertime" => "ABSENT",
                    "work" => "ABSENT",
                    "totalWorked" => "ABSENT",
                    "totalLate" => "ABSENT",
                    "totalOvertime" => "ABSENT",
                    "totalUndertime" => "ABSENT",
                    "approveOTStatus" => "0",
                    "location" => "--=--",
                    "empNo" => $empNo

                );
                break;
        }//end of switch for prompt
    }//end of for-loop

    foreach ($response as $k => $v) {
        $sort["dates"][$k] = $v["dates"];
    }//end of response

    array_multisort($sort["dates"], SORT_ASC,$response);
    // echo jsonify($response);
    return $response;
}

function timeSummary($startDate, $endDate){
    $freq = getAllEmployeeData();
    $rest = getRestDay();

    $startDates = strtotime($startDate);
    $endDates = strtotime($endDate);    

    for($i=$startDates; $i<=$endDates; $i+=86400){
        $date =  date("Y-m-d", $i);
        $day = date("l", $i);

        foreach($freq as $fr){
            $countDate = 0;
            $id = $fr["emp_uid"];
            $lastname = utf8_decode($fr["lastname"]);
            $c = getTimeIn($id, $date);
            $insss = date("Y-m-d", strtotime($c["date_created"]));

            $holiday = getHolidayByDate($date);
            $hDate = $holiday["date"];
            if($hDate == $date){
                if($hDate === $insss){
                    $holidayDate = $hDate;
                    $prompt = 1;
                    $time = $c["date_created"];
                }else{
                    $prompt = 3;
                    $time = "HOLIDAY";
                }
            }else if($insss != $date && $hDate != $date){
                $prompt = 0;
                $time = "ABSENT";
            }else{
                $holidayDate = 0;
                $prompt = 1;
                $time = $c["date_created"];
            }
            $restName = 0;
            $rest = getRestDayByDay($day);
            if($rest){
                $restName = $rest["name"];
            }//end of getting restDay

            if(date("l", $i) === $restName){
                $sun = date("Y-m-d", $i);
                $prompt = 2;
                $time = "Rest Day";
            }//end of comparing day
            
            switch ($prompt) {
                case 0:
                    $absentEmpId = $id;
                    $absentDate = $date;
                    // $absentNote = $time;
                    $absentDay = $day;
                    $offset = getAcceptedOffsetRequestByEmpUid($absentEmpId, $absentDate);
                    if($offset){
                        $offsetId = $offset["offset_uid"];
                        $offsetEmpUid = $offset["emp_uid"];
                        $offsetFromDate = $offset["from_date"];
                        $offsetSetDate = $offset["set_date"];
                        $ins = getOffsetTimeInByEmpUidAndDate($offsetEmpUid, $offsetFromDate);
                        $inId = $ins["time_log_uid"];
                        $in = $ins["date_created"];
                        $in1 = date("Y-m-d", strtotime($in));
                        $inDay = date("N", strtotime($in1));
                        $inSession = $inss["session"];

                        $outss = getTimeOutByEmpUidAndDateNoLoc($absentEmpId, $inSession);
                        $outId = $outss["time_log_uid"];
                        $out = $outss["date_created"];
                        $out1 = date("Y-m-d", strtotime($out));
                        $outHour = date("H:i:s", strtotime($out));

                        $shift = getShiftByUidAndDate($absentEmpId, $in1, $inDay);
                        $shiftStart = $shift->start;
                        $shiftEnd = $shift->end;
                        /*---------------------OVERTIME---------------------*/
                        if(strtotime($shiftEnd) <= strtotime($outHour)){
                            $overtime = (strtotime($outHour) - strtotime($shiftEnd)) / 3600;
                        }else if(strtotime($shiftEnd) >= strtotime($outHour)){
                            if($in1 === $out1){
                                $overtime = 0;
                            }else{
                                $shiftEnd = date("Y-m-d", strtotime($shiftEnd . "- 0 day")) . " $shiftEnd"; 
                                $overtime = (strtotime($shiftEnd) - strtotime($out)) / 3600;
                            }
                        }

                        if($overtime <= 0){
                            $in = $time;
                            $out = $time;
                            $response[] = array(
                                "id" => $id,
                                "lastname" => strtoupper($lastname),
                                "prompt" => $prompt,
                                "dates" => $absentDate,
                                "date" => date("M d, Y", strtotime($absentDate)),
                                "day" => $absentDay,
                                "tardiness" => "ABSENT",
                                "late" => "ABSENT",
                                "undertime" => "ABSENT",
                                "overtime" => "ABSENT",
                                "work" => "ABSENT",
                                "worked" => "",
                                "oTstatus" => ""
                            );
                        }else{
                            $totalOvertime = $overtime;
                            $overtimeHour = floor($totalOvertime);
                            $totalOvertimeMin = (60*($totalOvertime-$overtimeHour));
                            $overtimeMin = floor(60*($totalOvertime-$overtimeHour));
                            $overtimeMin1 = floor($totalOvertimeMin);
                            $overtimeSec = floor(60*($totalOvertimeMin-$overtimeMin1));

                            $overtimes = new dateTime("$overtimeHour:$overtimeMin:$overtimeSec");
                            $secondOut = date_format($overtimes, "H:i:s");
                            /*---------------------END OF OVERTIME---------------------*/

                            /*---------------------UNDERTIME---------------------*/
                            $secs = strtotime($secondOut)-strtotime("00:00:00");
                            $offsetDay = date("N", strtotime($offsetSetDate));
                            $shift = getOffsetShiftByUidAndDay($absentEmpId, $offsetDay);
                            $shiftStart = $shift->start;
                            $shiftEnd = $shift->end;
                            $secondOut = date("H:i:s", strtotime($shiftStart)+$secs);
                            if(strtotime($secondOut) <= strtotime($shiftEnd)){
                                $undertime = (strtotime($shiftEnd) - strtotime($secondOut)) / 3600;
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

                            /*---------------------END OF UNDERTIME---------------------*/
                            $count4 = 0;
                            if(strtotime($shiftStart) < strtotime($shiftEnd)){
                                $shiftDuration = countDurationOfShifts($absentEmpId, $out1, $offsetDay);
                                $afterBreak = "13:00:00";

                                if(strtotime($inss) >= strtotime($afterBreak)){
                                    $shiftDuration = ($shiftDuration - 1) / 2;
                                }else{
                                    $shiftDuration = $shiftDuration - 1;
                                }
                            }else{
                                $shiftStart = "2015-02-01 " . $shiftStart;
                                $shiftEnd = "2015-02-02 " . $shiftEnd;

                                $shiftDuration = countDurationOfShiftsReversedOffset($empId, $shiftStart, $shiftEnd, $offsetDay);
                                $shiftDuration = $shiftDuration - 1;
                            }

                            if($overtime > $shiftDuration){
                                $excessTime = $overtime - $shiftDuration;
                                $count4 = $overtime - $excessTime;
                            }

                            $response[] = array(
                                "id" => $id,
                                "lastname" => strtoupper($lastname),
                                "prompt" => 1,
                                "dates" => $date,
                                "date" => date("M d, Y", strtotime($date)),
                                "day" => $dat,
                                "tardiness" => $totalUndertime,
                                "late" => "00:00:00",
                                "undertime" => $totalUndertime,
                                "overtime" => "00:00:00",
                                "work" => $overtime,
                                "worked" => $count4,
                                "oTstatus" => ""
                            );
                        }
                        
                    }else{
                        $in = $time;
                        $out = $time;
                        $response[] = array(
                            "id" => $id,
                            "lastname" => strtoupper($lastname),
                            "prompt" => $prompt,
                            "dates" => $absentDate,
                            "date" => date("M d, Y", strtotime($absentDate)),
                            "day" => $absentDay,
                            "tardiness" => "ABSENT",
                            "late" => "ABSENT",
                            "undertime" => "ABSENT",
                            "overtime" => "ABSENT",
                            "work" => "ABSENT",
                            "worked" => "",
                            "oTstatus" => ""
                        );
                    }
                    
                    break;
                case 1:
                    $empId = $id;
                    $empDate = $date;
                    $empNote = $time;
                    $empDay = $day;
                    $empHolidayDate = $holidayDate;


                    $ins = getTimeInByEmpUidAndDate($empId, $empDate);
                    foreach($ins as $inss){
                        $in = $inss["date_created"];
                        $in1 = date("Y-m-d", strtotime($in));
                        $inDay = date("N", strtotime($in1));
                        $inSession = $inss["session"];
                        $totalCount = 0;

                        $outss = getTimeOutByEmpUidAndDate($empId, $inSession);
                        if(!$outss){
                            $response[] = array(
                                "id" => $empId,
                                "lastname" => strtoupper($lastname),
                                "prompt" => $prompt,
                                "dates" => $empDate,
                                "date" => date("M d, Y", strtotime($date)),
                                "day" => $empDay,
                                "tardiness" => 0,
                                "late" => 0,
                                "undertime" => 0,
                                "overtime" => 0,
                                "work" => 0,
                                "worked" => 0,
                                "oTstatus" => ""
                            );
                        }else{
                            $out = $outss["date_created"];
                            $shift = getShiftByUidAndDate($empId, $in1, $inDay);
                            $shiftStart = $shift->start;
                            $shiftEnd = $shift->end; 
                            $grace = $shift->grace_period;
                            $shiftEnds = $shiftEnd;
                            $shiftStarts = $shiftStart;

                            if(strtotime($shiftStart) < strtotime($shiftEnd)){
                                $shiftDuration = countDurationOfShifts($empId, $in1, $inDay);
                                $afterBreak = "13:00:00";

                                if(strtotime($inss) >= strtotime($afterBreak)){
                                    $shiftDuration = ($shiftDuration - 1) / 2;
                                }else{
                                    $shiftDuration = $shiftDuration - 1;
                                }
                            }else{
                                $shiftStart = "2015-02-01 " . $shiftStart;
                                $shiftEnd = "2015-02-02 " . $shiftEnd;

                                $shiftDuration = countDurationOfShiftsReversed($empId, $shiftStart, $shiftEnd, $inDay, $in1);
                                $shiftDuration = $shiftDuration - 1;
                            }

                            if($grace != 0){
                                $dapatIn = date("H:i:s", strtotime("+$grace minutes", strtotime($shiftStart)));
                            }else{
                                $dapatIn = date("H:i:s", strtotime($shiftStart));
                            }
                            $inn = date("H:i:s", strtotime($in));
                            $outHour = date("H:i:s", strtotime($out));
                            $insss = date("Y-m-d", strtotime($in));
                            $inHour = date("H:i:s", strtotime($dapatIn));

                            $shiftStart1 = $shift->start;
                            $shiftEnd1 = $shift->end; 

                            $test = 0;
                            $excessTime = 0;
                            $tardiness = 0;
                            $lateCount3 = 0;
                            $undertimeCount3 = 0;
                            /*WORKED FUNCTION*/
                            if(strtotime($inn) >= strtotime($shiftStart1)){
                                $count = countDurationPerDay($id, $in, $out, $empDate);

                                $count1 = date("H:i:s", strtotime($count));
                                $count2 = explode(":", $count1);
                                $count3 = ($count2[0] + ($count2[1]/60) + ($count2[2]/3600));
                                $check = getOvertimeRequestByEmpUidAndDate($empId, $empDate, $empHolidayDate);
                                $checkEmpId = $check["emp_uid"];
                                $checkDate = $check["start_date"];
                                $oTstatus = 0;
                                $approvedDate = 0;

                                if($checkDate){
                                    $approvedDate = $checkDate;
                                    $oTstatus = 1;
                                }

                                if($count3 > $shiftDuration){
                                    $excessTime = $count3 - $shiftDuration;
                                }//end of comparing in in shift
                                if(strtotime($inn) > strtotime($inHour)){
                                    $inssss = $insss . " " . $inHour;
                                    $lateCount = countLatePerDay($id, $in, $inssss, $date);
                                    $lateCount1 = date("H:i:s", strtotime($lateCount));
                                    $lateCount2 = explode(":", $lateCount1);
                                    $lateCount3 = ($lateCount2[0] + ($lateCount2[1]/60) + ($lateCount2[2]/3600));
                                }//end of comparing number of hours
                            }else if(strtotime($inn) <= strtotime($shiftStart1)){
                                $shiftStart = $insss . " " . $shiftStart1;   
                                $inDates = date("Y-m-d", strtotime($in));
                                
                                $countDapat = countInSaShift($id, $in, $shiftStart, $empDate);
                                $lateCount3 = 0;
                                $count = countDurationPerDay($id, $in, $out, $empDate);
                                $count1 = date("H:i:s", strtotime($count));
                                $count2 = explode(":", $count1);
                                $count3 = ($count2[0] + ($count2[1]/60) + ($count2[2]/3600));
                                $check = getOvertimeRequestByEmpUidAndDate($empId, $empDate, $empHolidayDate);
                                $checkEmpId = $check["emp_uid"];
                                $checkDate = $check["start_date"];
                                $oTstatus = 0;
                                $approvedDate = 0;
                                if($checkDate){
                                    $approvedDate = $checkDate;
                                    $oTstatus = 1;
                                }//end of checking
                                if($count3 > $shiftDuration){
                                    $excessTime = $count3 - $shiftDuration;
                                }//end of getting overtime

                                /*UNDERTIME FUNCTION*/ 
                                if(strtotime($outHour) <= strtotime($shiftEnds)){
                                    $outs = $insss . " " . $shiftEnd;
                                    $undertimeCount = countUndertimePerDay($empId, $out, $outs, $empDate);
                                    $undertimeCount1 = date("H:i:s", strtotime($undertimeCount));
                                    $undertimeCount2 = explode(":", $undertimeCount1);
                                    $undertimeCount3 = ($undertimeCount2[0] + ($undertimeCount2[1]/60) + ($undertimeCount2[2]/3600));
                                }
                            }//end of comparing time in in shift
                            /*END OF WORKED FUNCTION*/
                            $tardiness = $lateCount3 + $undertimeCount3;
                            $count4 = 0;

                            if($count3 > $shiftDuration){
                                $count4 = $count3 - $excessTime;
                            }
                            $count3 = $count3;
                            $response[] = array(
                                "id" => $empId,
                                "lastname" => strtoupper($lastname),
                                "prompt" => $prompt,
                                "dates" => $empDate,
                                "date" => date("M d, Y", strtotime($date)),
                                "day" => $empDay,
                                "tardiness" => $tardiness,
                                "late" => $lateCount3,
                                "undertime" => $undertimeCount3,
                                "overtime" => $excessTime,
                                "work" => $count3,
                                "worked" => $count4,
                                "oTstatus" => $oTstatus
                            );
                        }
                    }//end of getting getTimeInByEmpUidAndDate Function
                    break;
                case 2:
                    $restId = $id;
                    $restDate = $sun;
                    // $restNote = $time;
                    $restDay = $day;
                    $in = $time;
                    $out = $time;

                    $response[] = array(
                        "id" => $restId,
                        "lastname" => strtoupper($lastname),
                        "prompt" => $prompt,
                        "dates" => $restDate,
                        "date" => date("M d, Y", strtotime($restDate)),
                        "day" => $restDay,
                        "tardiness" => "REST DAY",
                        "late" => "REST DAY",
                        "undertime" => "REST DAY",
                        "overtime" => "REST DAY",
                        "work" => "REST DAY",
                        "worked" => "",
                        "oTstatus" => ""
                    );
                    break;
                case 3:
                    $holidayEmpId = $id;
                    $holidayDate = $date;
                    // $absentNote = $time;
                    $holidayDay = $day;
                    $in = $time;
                    $out = $time;
                    
                    $response[] = array(
                        "id" => $holidayEmpId,
                        "lastname" => strtoupper($lastname),
                        "prompt" => $prompt,
                        "dates" => $holidayDate,
                        "date" => date("M d, Y", strtotime($holidayDate)),
                        "day" => $holidayDay,
                        "tardiness" => "HOLIDAY",
                        "late" => "HOLIDAY",
                        "undertime" => "HOLIDAY",
                        "overtime" => "HOLIDAY",
                        "work" => "HOLIDAY",
                        "worked" => "",
                        "oTstatus" => ""
                    );
                    break;
            } //end of switch for prompt
        }//end of getting employee's data
    }//end of forloop
    foreach ($response as $k => $v) {
        $sort["dates"][$k] = $v["dates"];
    }//end of response

    array_multisort($sort["dates"], SORT_ASC,$response);

    // echo jsonify($response);
    return $response;
}

function timeSummaryByUid($startDate, $endDate, $id){
    $startDates = strtotime($startDate);
    $endDates = strtotime($endDate);

    for($i=$startDates; $i<=$endDates; $i+=86400){
        $date =  date("Y-m-d", $i);
        $day = date("l", $i);

        $a = getEmpByUid($id);
        if($a){
            $lastname = utf8_decode($a->lastname);
        }//end of getEmpByUid Function

        $work = 0;
        $late = 0;
        $overtime = 0;
        $undertime = 0;
        $c = getTimeIn($id, $date);
        $insss = date("Y-m-d", strtotime($c["date_created"]));

        $holiday = getHolidayByDate($date);
        $hDate = $holiday["date"];

        // $abDate = $date . " 00:00:00";

        $absent = getAbsentRequestByDateAndEmpUid($id, $date);
        if($absent){
            $absentDate = date("Y-m-d", strtotime($absent->start_date));
            $prompt = 5;
        }else{
            $absentDate = 0;
        }
        
        if($hDate == $date){
            if($hDate === $insss){
                $holidayDate = $hDate;
                $prompt = 1;
                $time = $c["date_created"];
            }else{
                $prompt = 3;
                $time = "HOLIDAY";
            }
        }else if($absentDate === $date){
            $prompt = 5;
        }else if($insss != $date && $hDate != $date){
            $prompt = 0;
            $time = "ABSENT";
        }else{
            $holidayDate = 0;
            $prompt = 1;
            $time = $c["date_created"];
        }
        $restName = 0;
        $rest = getRestDayByDay($day);
        if($rest){
            $restName = $rest["name"];
        }//end of getting restDay

        if(date("l", $i) === $restName){
            $sun = date("Y-m-d", $i);
            $prompt = 2;
            $time = "Rest Day";
        }//end of comparing day

        $leave = getLeaveByEmpUidAndDate($id, $date);
        if($leave){
            $leaveStartDate = $leave->start_date;
            $leaveEndDate = $leave->end_date;

            $leaveDay = date("l", strtotime($date));
            if($leaveDay === $restName){
                $prompt = 2;
                $time = "Rest Day";
            }else{
                $prompt = 4;
                $time = "LEAVED";
            }
        }

        switch ($prompt) {
            case 0:
                $absentEmpId = $id;
                $absentDate = $date;
                $over = 0;

                $absentDay = $day;
                $offset = getAcceptedOffsetRequestByEmpUid($absentEmpId, $absentDate);
                if($offset){
                    $offsetId = $offset["offset_uid"];
                    $offsetEmpUid = $offset["emp_uid"];
                    $offsetFromDate = $offset["from_date"];
                    $offsetSetDate = $offset["set_date"];
                    $offsetDay = date("N", strtotime($offsetSetDate));
                    $ins = getOffsetTimeInByEmpUidAndDate($offsetEmpUid, $offsetFromDate);
                    foreach($ins as $inss){
                        $inId = $inss["time_log_uid"];
                        $in = $inss["date_created"];
                        $in1 = date("Y-m-d", strtotime($in));
                        $inDay = date("N", strtotime($in1));
                        $inSession = $inss["session"];

                        $outss = getTimeOutByEmpUidAndDateNoLoc($empId, $inSession);
                        $outId = $outss["time_log_uid"];
                        $out = $outss["date_created"];
                        $out1 = date("Y-m-d", strtotime($out));
                        $outHour = date("H:i:s", strtotime($out));
                        $inHour = date("H:i:s", strtotime($in));
                        $shift = getShiftByUidAndDate($absentEmpId, $in1, $offsetDay);
                        $shiftStart = $shift->start;
                        $shiftEnd = $shift->end;
                        $shiftEnds = $shiftEnd;
                        if(strtotime($shiftStart) < strtotime($shiftEnd)){
                            $shiftDuration = countDurationOfShifts($absentEmpId, $in1, $offsetDay);
                            $afterBreak = "13:00:00";

                            if(strtotime($inHour) >= strtotime($afterBreak)){
                                $shiftDuration = ($shiftDuration - 1) / 2;
                            }else{
                                $shiftDuration = $shiftDuration - 1;
                            }
                        }else{
                            $shiftStart = "2015-02-01 " . $shiftStart;
                            $shiftEnd = "2015-02-02 " . $shiftEnd;

                            $shiftDuration = countDurationOfShiftsReversed($absentEmpId, $shiftStart, $shiftEnd, $offsetDay, $in1);
                            $shiftDuration = $shiftDuration - 1;
                        }
                        if($out1 == $out1){
                            $over++;
                        }

                        $outArray = array(
                            "outHour" => $outHour, 
                            "out" => $out, 
                            "outDate" => $out1
                        );

                        $undertimeCounts = countDateOut($empId, $out1);
                        $outHour = $outArray["outHour"];
                        $out = $outArray["out"];

                        /*---------------------OVERTIME---------------------*/

                        if(strtotime($shiftEnd) <= strtotime($outArray["outHour"])){
                            if($in1 === $out1){
                                $overtime = (strtotime($outHour) - strtotime($shiftEnd)) / 3600;
                            }else{
                                $shiftEnds = $out1 . $shiftEnds;
                                $overtime = (strtotime($out) - strtotime($shiftEnds)) / 3600;
                            }
                        }else if(strtotime($shiftEnd) >= strtotime($outArray["outHour"])){
                            if($in1 === $out1){
                                $overtime = 0;
                            }else{
                                $shiftEnd = date("Y-m-d", strtotime($in . "- 0 day")) . " $shiftEnd"; 
                                $overtime = (strtotime($out) - strtotime($shiftEnd)) / 3600;
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
                                "lastname" => strtoupper($lastname),
                                "prompt" => $prompt,
                                "dates" => $date,
                                "date" => date("M d, Y", strtotime($absentDate)),
                                "day" => $absentDay,
                                "tardiness" => 0,
                                "late" => 0,
                                "undertime" => 0,
                                "overtime" => 0,
                                "work" => 0,
                                "oTstatus" => "",
                                "nightDiffStatus" => "",
                                "nightHours" => 0
                            ); 
                        }else{
                            if($overtime === $shiftDuration){
                                $totalOvertime = $shiftDuration;
                            }else if($overtime > $shiftDuration){
                                $totalOvertime = $shiftDuration;
                            }else if($overtime < $shiftDuration){
                                $totalOvertime = $overtime - 1;
                            }
                                
                            $overtimeHour = floor($totalOvertime);
                            $totalOvertimeMin = (60*($totalOvertime-$overtimeHour));
                            $overtimeMin = floor(60*($totalOvertime-$overtimeHour));
                            $overtimeMin1 = floor($totalOvertimeMin);
                            $overtimeSec = floor(60*($totalOvertimeMin-$overtimeMin1));

                            $overtimes = new dateTime("$overtimeHour:$overtimeMin:$overtimeSec");
                            /*FOR SECOND OUT*/
                            $totalOvertime1 = $overtime - 1;
                            $overtimeHour1 = floor($totalOvertime1);
                            $totalOvertimeMin1 = (60*($totalOvertime1-$overtimeHour1));
                            $overtimeMin1 = floor(60*($totalOvertime1-$overtimeHour1));
                            $overtimeMin11 = floor($totalOvertimeMin1);
                            $overtimeSec1 = floor(60*($totalOvertimeMin1-$overtimeMin11));
                            $overtimess1 = new dateTime("$overtimeHour1:$overtimeMin1:$overtimeSec1");
                            $secondOut = date_format($overtimess1, "H:i:s");
                            /*---------------------END OF OVERTIME---------------------*/

                            /*---------------------UNDERTIME---------------------*/
                            $secs = strtotime($secondOut)-strtotime("00:00:00");

                            $offsetDay = date("N", strtotime($offsetSetDate));
                            $shift = getOffsetShiftByUidAndDay($absentEmpId, $offsetDay);
                            $shiftStart = $shift->start;
                            $shiftEnd = $shift->end;
                            $overt = 0;
                            // echo "$shiftStart<br/>";
                            $secondOut = date("H:i:s", strtotime($shiftStart)+$secs);
                            // echo "$secondOut = $shiftEnd<br/>";
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
                            /*---------------------END OF UNDERTIME---------------------*/
                            $response[] = array(
                                "id" => $id,
                                "lastname" => strtoupper($lastname),
                                "prompt" => 1,
                                "dates" => $date,
                                "date" => date("M d, Y", strtotime($date)),
                                "day" => $date,
                                "tardiness" => $totalUndertime,
                                "late" => 0,
                                "undertime" => $totalUndertime,
                                "overtime" => 0,
                                "work" => $totalOvertime,
                                "oTstatus" => "",
                                "nightDiffStatus" => "",
                                "nightHours" => 0
                            );
                        }
                    }//end of getOffsetTimeInByEmpUidAndDate Function
                }else{
                    $response[] = array(
                        "id" => $id,
                        "lastname" => strtoupper($lastname),
                        "prompt" => $prompt,
                        "dates" => $date,
                        "date" => date("M d, Y", strtotime($absentDate)),
                        "day" => $absentDay,
                        "tardiness" => 0,
                        "late" => 0,
                        "undertime" => 0,
                        "overtime" => 0,
                        "work" => 0,
                        "oTstatus" => "",
                        "nightDiffStatus" => "",
                        "nightHours" => 0
                    );  
                }
                break;
            case 1:
                $empId = $id;
                $empDate = $date;
                $empNote = $time;
                $empDay = $day;
                $empHolidayDate = $holidayDate;
                $ins = getTimeInByEmpUidAndDateNoLoc($empId, $empDate);
                $late = 0;
                $under = 0;

                foreach($ins as $inss){
                    $inId = $inss["time_log_uid"];
                    $in = $inss["date_created"];
                    $in1 = date("Y-m-d", strtotime($in));
                    $inDay = date("N", strtotime($in1));
                    $inSession = $inss["session"];

                    $outss = getTimeOutByEmpUidAndDateNoLoc($empId, $inSession);
                    if(!$outss){
                        $response[] = array(
                            "id" => $empId,
                            "lastname" => strtoupper($lastname),
                            "prompt" => "",
                            "dates" => $date,
                            "date" => date("M d, Y", strtotime($date)),
                            "day" => $empDay,
                            "tardiness" => 0,
                            "late" => 0,
                            "undertime" => 0,
                            "overtime" => 0,
                            "work" => 0,
                            "oTstatus" => "",
                            "nightDiffStatus" => "",
                            "nightHours" => 0
                        );
                    }else{
                        $outId = $outss["time_log_uid"];
                        $out = $outss["date_created"];
                        $out1 = date("Y-m-d", strtotime($out));

                        $shift = getShiftByUidAndDate($empId, $in1, $inDay);
                        $shiftStart = $shift->start;
                        $shiftEnd = $shift->end;
                        $shiftEnds1 = $shiftEnd;
                        $grace = $shift->grace_period;
                        $shiftEnds = $shiftEnd;
                        $shiftStarts = $shiftStart;

                        if($grace != 0){
                            $dapatIn = date("H:i:s", strtotime("+$grace minutes", strtotime($shiftStart)));
                        }else{
                            $dapatIn = date("H:i:s", strtotime($shiftStart));
                        }
                        $inss = date("H:i:s", strtotime($in));
                        $outss = date("H:i:s", strtotime($out));

                        /*WORKED FUNCTION*/
                        if(strtotime($out) < strtotime($in)){
                            $work = (strtotime($in) - strtotime($out)) / 3600;
                        }else if(strtotime($out) > strtotime($in)){
                            $work = (strtotime($out) - strtotime($in)) / 3600;
                        }//end of worked function

                        if(strtotime($shiftStart) < strtotime($shiftEnd)){
                            $shiftDuration = countDurationOfShifts($empId, $in1, $inDay);
                            $afterBreak = "13:00:00";

                            if(strtotime($inss) >= strtotime($afterBreak)){
                                $shiftDuration = ($shiftDuration - 1) / 2;
                            }else{
                                $shiftDuration = $shiftDuration - 1;
                            }
                        }else{
                            $shiftStart = "2015-02-01 " . $shiftStart;
                            $shiftEnd = "2015-02-02 " . $shiftEnd;

                            $shiftDuration = countDurationOfShiftsReversed($empId, $shiftStart, $shiftEnd, $inDay, $in1);
                            $shiftDuration = $shiftDuration - 1;
                            $afterBreak = "00:00:00";

                        }//end of getting shiftDuration

                        // echo "$shiftHalf<br/>";
                        if($work === $shiftDuration){
                            $totalWork = $shiftDuration;
                        }else if($work > $shiftDuration){
                            $totalWork = $shiftDuration;
                            // echo "$id = " . $count3 - $excessTime . "<br/>";
                        }else if($work <= $shiftDuration){
                            $totalWork = $shiftDuration;
                        }//end of getting total work

                        $inn = date("H:i:s", strtotime($in));
                        $inHour = date("H:i:s", strtotime($dapatIn));
                        $empDates = date("Y-m-d", strtotime($empDate . "+1 day"));
                        if($in1 == $empDate){
                            $late++;
                        }//end of count for late
                        if($out1 == $empDate){
                            $under++;
                        }else if($out1 == $empDates){
                            $under++;
                        }//end of count for undertime
                        $lates = 0;
                        $undertime = 0;
                        $over = 0;
                        $getFirstIn = array();
                        // /*LATE FUNCTION*/
                        $inArray[] = array(
                            "inHour" => $inn, 
                            "inDate" => $empDate
                        );
                        $lateCount = countDate($empId, $empDate);

                        /*LATE FUNCTION*/
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
                                }else{
                                    $shiftStarts = $in1 . " " . $shiftStarts;
                                    $lates = (strtotime($in) - strtotime($shiftStarts)) / 3600;
                                }
                            }//end of comparing counts
                        }//end of late function
                        $outHour = date("H:i:s", strtotime($out));

                        $undertimeCounts = countDateOut($empId, $out1);
                        /*UNDERTIME FUNCTION*/ 
                        $getLastOut = array();
                        if($undertimeCounts === $under){
                            if(strtotime($outHour) <= strtotime($shiftEnds)){
                                $undertimeCounts = countDateOut($empId, $out1);
                                $outArray = array(
                                    "outHour" => $outHour, 
                                    "outDate" => $out1
                                );
                                $outHour = $outArray["outHour"];
                                $empDate = $outArray["outDate"];
                                $outss = $empDate . " " . $outHour;

                                if($in1 === $out1){
                                    $undertime = (strtotime($shiftEnds) - strtotime($outHour)) / 3600;
                                }else{
                                    $shiftEnds = $out1 . " " . $shiftEnds;

                                    $undertime = (strtotime($shiftEnds) - strtotime($outss)) / 3600;
                                }
                            }//end of comparison for undertime
                        }//end of getting count

                        $check = getOvertimeRequestByEmpUidAndDate($empId, $empDate, $empHolidayDate);
                        $checkEmpId = $check["emp_uid"];
                        $checkDate = $check["start_date"];
                        $hours = $check["hours"];
                        $oTstatus = 0;
                        $approvedDate = 0;

                        if(!$checkDate){

                        }else{
                            $approvedDate = $checkDate;
                            $oTstatus = 1;
                        }//end of checking
                        if($out1 === $out1){
                            $over++;
                        }

                        $outArray = array(
                            "outHour" => $outHour, 
                            "out" => $out, 
                            "outDate" => $out1
                        );
                        /*OVERTIME FUNCTION*/
                        $nightH = 0;
                        $nightDiffStatus = 0;
                        // if($undertimeCounts === $over){
                            $outHour = $outArray["outHour"];
                            $nightDiffStart = "22:00:00";
                            $nightDiffEnd = "06:00:00";
                            $nightDiffStarts = date("Y-m-d", strtotime($in . "- 0 day")) . " $nightDiffStart"; 
                            $nightDiffEnds = date("Y-m-d", strtotime($in . "- 0 day")) . " $nightDiffEnd"; 
                            // echo "$nightDiffStarts = $nightDiffEnds<br/>";
                            if(strtotime($outHour) <= strtotime($nightDiffEnd) && strtotime($outHour) >= strtotime($nightDiffStarts)){
                                $nightss = (strtotime($nightDiffEnd) - strtotime($outHour)) / 3600;
                                $nightH = 8 - $nightss;
                                $nightDiffStatus = 1;
                            // echo "$nightDiffEnd = $outHour = $nightss = $nightH<br/>";
                            }else if(strtotime($outHour) >= strtotime($nightDiffEnd) && strtotime($outHour) <= strtotime($nightDiffStarts)){
                                $nightss = (strtotime($outHour) - strtotime($nightDiffEnd)) / 3600;
                                $nightH = 8 - $nightss;
                                $nightDiffStatus = 1;
                            // echo "$outHour = $nightDiffEnd = $nightss = $nightH<br/>";
                            }else if(strtotime($outHour) == strtotime($nightDiffEnd)){
                                $nightss = (strtotime($outHour) - strtotime($nightDiffEnd)) / 3600;
                                $nightH = 8 - $nightss;
                                $nightDiffStatus = 1;
                            // echo "$outHour = $nightDiffEnd = $nightss = $nightH<br/>";
                            }

                            $nightH = floor($nightH);
                            $out = $outArray["out"];
                            if(strtotime($shiftEnd) <= strtotime($outHour)){
                                if($in1 === $out1){
                                    $overtime = (strtotime($outHour) - strtotime($shiftEnd)) / 3600;
                                }else{
                                    $shiftEnds = $out1 . $shiftEnds;
                                    $overtime = (strtotime($out) - strtotime($shiftEnds)) / 3600;
                                }
                            }else if(strtotime($shiftEnd) >= strtotime($outHour)){
                                if($in1 === $out1){
                                    $overtime = 0;
                                }else{
                                    $shiftEnd = date("Y-m-d", strtotime($in . "- 0 day")) . " $shiftEnd"; 
                                    $overtime = (strtotime($out) - strtotime($shiftEnd)) / 3600;
                                }//end of comparing dates
                            }//end of comparison for overtime
                        // }//end of comparing count

                        if($overtime > 60){
                            $overtime = 0;
                        }else if($overtime <= -1 ){
                            $overtime = 0;
                        }
                        $totalWork = $totalWork;
                        $tardiness = $lates + $undertime;
                        $response[] = array(
                            "id" => $empId,
                            "lastname" => strtoupper($lastname),
                            "prompt" => $prompt,
                            "dates" => $date,
                            "date" => date("M d, Y", strtotime($date)),
                            "day" => $empDay,
                            "tardiness" => $tardiness,
                            "late" => $lates,
                            "undertime" => $undertime,
                            "overtime" => $hours,
                            "work" => $totalWork,
                            "oTstatus" => $oTstatus,
                            "nightDiffStatus" => $nightDiffStatus,
                            "nightHours" => $nightH
                        );
                    }//end of out function
                }//end of getTimeInByEmpUidAndDate Function
                break;
            case 2:
                $restId = $id;
                $restDate = $sun;
                // $restNote = $time;
                $restDay = $day;
                $in = $time;
                $out = $time;
                // echo "$restId = $restDate = $restNote<br/>";
                $empHolidayDate = $restDate;
                $ins = getTimeInByEmpUidAndDateNoLoc($restId, $restDate);
                $late = 0;
                $under = 0;

                $check = checkRestDay($restId, $restDate);

                if($check >= 1){
                    foreach($ins as $inss){
                        $inId = $inss["time_log_uid"];
                        $in = $inss["date_created"];
                        $in1 = date("Y-m-d", strtotime($in));
                        $inDay = date("N", strtotime($in1));
                        $inSession = $inss["session"];

                        $outss = getTimeOutByEmpUidAndDateNoLoc($restId, $inSession);
                        if(!$outss){
                            $response[] = array(
                                "id" => $restId,
                                "lastname" => strtoupper($lastname),
                                "prompt" => $prompt,
                                "dates" => $date,
                                "date" => date("M d, Y", strtotime($date)),
                                "day" => $empDay,
                                "tardiness" => 0,
                                "late" => 0,
                                "undertime" => 0,
                                "overtime" => 0,
                                "work" => 0,
                                "oTstatus" => "",
                                "nightDiffStatus" => "",
                                "nightHours" => 0
                            );
                        }else{
                            $outId = $outss["time_log_uid"];
                            $out = $outss["date_created"];
                            $out1 = date("Y-m-d", strtotime($out));

                            $shift = getShiftByUidAndDate($restId, $in1, $inDay);
                            $shiftStart = $shift->start;
                            $shiftEnd = $shift->end;
                            $shiftEnds1 = $shiftEnd;
                            $grace = $shift->grace_period;
                            $shiftEnds = $shiftEnd;
                            $shiftStarts = $shiftStart;

                            if($grace != 0){
                                $dapatIn = date("H:i:s", strtotime("+$grace minutes", strtotime($shiftStart)));
                            }else{
                                $dapatIn = date("H:i:s", strtotime($shiftStart));
                            }
                            $inss = date("H:i:s", strtotime($in));
                            $outss = date("H:i:s", strtotime($out));

                            if(strtotime($shiftStart) < strtotime($shiftEnd)){
                                $shiftDuration = countDurationOfShifts($restId, $in1, $inDay);
                                $afterBreak = "13:00:00";

                                if(strtotime($inss) >= strtotime($afterBreak)){
                                    $shiftDuration = ($shiftDuration - 1) / 2;
                                }else{
                                    $shiftDuration = $shiftDuration - 1;
                                }
                            }else{
                                $shiftStart = "2015-02-01 " . $shiftStart;
                                $shiftEnd = "2015-02-02 " . $shiftEnd;

                                $shiftDuration = countDurationOfShiftsReversed($restId, $shiftStart, $shiftEnd, $inDay, $in1);
                                $shiftDuration = $shiftDuration - 1;
                            }//end of getting shiftDuration
                            

                            /*WORKED FUNCTION*/
                            if(strtotime($out) < strtotime($in)){
                                $work = (strtotime($in) - strtotime($out)) / 3600;
                            }else if(strtotime($out) > strtotime($in)){
                                $work = (strtotime($out) - strtotime($in)) / 3600;
                            }//end of worked function

                            // echo "$shiftHalf<br/>";
                            if($work === $shiftDuration){
                                $totalWork = $shiftDuration;
                            }else if($work > $shiftDuration){
                                $totalWork = $shiftDuration;
                                // echo "$id = " . $count3 - $excessTime . "<br/>";
                            }else if($work <= $shiftDuration){
                                $totalWork = $shiftDuration;
                            }//end of getting total work

                            $inn = date("H:i:s", strtotime($in));
                            $inHour = date("H:i:s", strtotime($dapatIn));
                            $empDates = date("Y-m-d", strtotime($restDate . "+1 day"));
                            if($in1 == $restDate){
                                $late++;
                            }//end of count for late
                            if($out1 == $restDate){
                                $under++;
                            }else if($out1 == $empDates){
                                $under++;
                            }//end of count for undertime
                            $lates = 0;
                            $undertime = 0;
                            $over = 0;
                            $getFirstIn = array();
                            // /*LATE FUNCTION*/
                            $inArray[] = array(
                                "inHour" => $inn, 
                                "inDate" => $restDate
                            );
                            $lateCount = countDate($restId, $restDate);

                            /*LATE FUNCTION*/
                            if(strtotime($inn) >= strtotime($inHour)){
                                if($late === $lateCount){
                                    for($x=0; $x < count($inArray); $x++){
                                        if(in_array($restDate, $inArray[$x])){
                                            $getFirstIn[] = $inArray[$x];
                                        }//end of checking
                                    }//end of forloop
                                    $inn = ($getFirstIn[0]["inHour"]);
                                    $empDate = ($getFirstIn[0]["inDate"]);
                                    if($in1 === $out1){
                                        if(strtotime($inn) >= strtotime($afterBreak)){
                                            $lates = ((strtotime($inn) - strtotime($shiftStarts)) / 3600) - 1;
                                        }else{
                                            $lates = (strtotime($inn) - strtotime($shiftStarts)) / 3600;
                                        }
                                    }else{
                                        $shiftStarts = $in1 . " " . $shiftStarts;
                                        $lates = (strtotime($in) - strtotime($shiftStarts)) / 3600;
                                    }
                                }//end of comparing counts
                            }//end of late function
                            $outHour = date("H:i:s", strtotime($out));

                            $undertimeCounts = countDateOut($restId, $out1);
                            /*UNDERTIME FUNCTION*/ 
                            $getLastOut = array();
                            if($undertimeCounts === $under){
                                if(strtotime($outHour) <= strtotime($shiftEnds)){
                                    $undertimeCounts = countDateOut($restId, $out1);
                                    $outArray = array(
                                        "outHour" => $outHour, 
                                        "outDate" => $out1
                                    );
                                    $outHour = $outArray["outHour"];
                                    $empDate = $outArray["outDate"];
                                    $outss = $empDate . " " . $outHour;

                                    if($in1 === $out1){
                                        $undertime = (strtotime($shiftEnds) - strtotime($outHour)) / 3600;
                                    }else{
                                        $shiftEnds = $out1 . " " . $shiftEnds;

                                        $undertime = (strtotime($shiftEnds) - strtotime($outss)) / 3600;
                                    }
                                }//end of comparison for undertime
                            }//end of getting count

                            $check = getOvertimeRequestByEmpUidAndDate($restId, $restDate, $restDate);
                            $checkEmpId = $check["emp_uid"];
                            $checkDate = $check["start_date"];
                            $hours = $check["hours"];
                            $oTstatus = 0;
                            $approvedDate = 0;

                            if(!$checkDate){

                            }else{
                                $approvedDate = $checkDate;
                                $oTstatus = 1;
                            }//end of checking
                            if($out1 === $out1){
                                $over++;
                            }

                            $outArray = array(
                                "outHour" => $outHour, 
                                "out" => $out, 
                                "outDate" => $out1
                            );
                            $nightH = 0;
                            $nightDiffStatus = 0;
                            /*OVERTIME FUNCTION*/
                            // if($undertimeCounts === $over){
                                $outHour = $outArray["outHour"];
                                $out = $outArray["out"];
                                $nightDiffStart = "22:00:00";
                                $nightDiffEnd = "06:00:00";
                                $nightDiffStarts = date("Y-m-d", strtotime($in . "- 0 day")) . " $nightDiffStart"; 
                                $nightDiffEnds = date("Y-m-d", strtotime($in . "- 0 day")) . " $nightDiffEnd"; 

                                if(strtotime($outHour) <= strtotime($nightDiffEnd) && strtotime($outHour) >= strtotime($nightDiffStarts)){
                                    $nightss = (strtotime($nightDiffEnd) - strtotime($outHour)) / 3600;
                                    $nightH = 8 - $nightss;
                                    $nightDiffStatus = 1;
                                // echo "$nightDiffEnd = $outHour = $nightss = $nightH<br/>";
                                }else if(strtotime($outHour) >= strtotime($nightDiffEnd) && strtotime($outHour) <= strtotime($nightDiffStarts)){
                                    $nightss = (strtotime($outHour) - strtotime($nightDiffEnd)) / 3600;
                                    $nightH = 8 - $nightss;
                                    $nightDiffStatus = 1;
                                // echo "$outHour = $nightDiffEnd = $nightss = $nightH<br/>";
                                }else if(strtotime($outHour) == strtotime($nightDiffEnd)){
                                    $nightss = (strtotime($outHour) - strtotime($nightDiffEnd)) / 3600;
                                    $nightH = 8 - $nightss;
                                    $nightDiffStatus = 1;
                                // echo "$outHour = $nightDiffEnd = $nightss = $nightH<br/>";
                                }

                                $nightH = floor($nightH);

                                
                                if(strtotime($shiftEnd) <= strtotime($outHour)){
                                    if($in1 === $out1){
                                        $overtime = (strtotime($outHour) - strtotime($shiftEnd)) / 3600;
                                    }else{
                                        $shiftEnds = $out1 . $shiftEnds;
                                        $overtime = (strtotime($out) - strtotime($shiftEnds)) / 3600;
                                    }
                                }else if(strtotime($shiftEnd) >= strtotime($outHour)){
                                    if($in1 === $out1){
                                        $overtime = 0;
                                    }else{
                                        $shiftEnd = date("Y-m-d", strtotime($in . "- 0 day")) . " $shiftEnd"; 
                                        $overtime = (strtotime($out) - strtotime($shiftEnd)) / 3600;
                                    }//end of comparing dates
                                }//end of comparison for overtime
                            // }//end of comparing count

                            if($overtime > 60){
                                $overtime = 0;
                            }else if($overtime <= -1 ){
                                $overtime = 0;
                            }
                            $totalWork = $totalWork;
                            $tardiness = $lates + $undertime;
                            $response[] = array(
                                "id" => $restId,
                                "lastname" => strtoupper($lastname),
                                "prompt" => 1,
                                "dates" => $date,
                                "date" => date("M d, Y", strtotime($date)),
                                "day" => $restDay,
                                "tardiness" => $tardiness,
                                "late" => $lates,
                                "undertime" => $undertime,
                                "overtime" => $hours,
                                "work" => $totalWork,
                                "oTstatus" => $oTstatus,
                                "nightDiffStatus" => $nightDiffStatus,
                                "nightHours" => $nightH
                            );
                        }//end of out function
                    }//end of getTimeInByEmpUidAndDate Function
                }else{
                    $response[] = array(
                        "id" => $restId,
                        "lastname" => strtoupper($lastname),
                        "prompt" => 1,
                        "dates" => $date,
                        "date" => date("M d, Y", strtotime($date)),
                        "day" => $restDay,
                        "tardiness" => "REST DAY",
                        "late" => "REST DAY",
                        "undertime" => "REST DAY",
                        "overtime" => "REST DAY",
                        "work" => "REST DAY",
                        "oTstatus" => 0,
                        "nightDiffStatus" => "",
                        "nightHours" => 0
                    );
                }
                break;
            case 3:
                $empId = $id;
                $empDate = $date;
                $empNote = $time;
                $empDay = $day;
                $ins = getTimeInByEmpUidAndDate($empId, $empDate);
                $late = 0;
                $under = 0;

                if($time === "HOLIDAY"){
                    $response[] = array(
                        "id" => $empId,
                        "lastname" => strtoupper($lastname),
                        "prompt" => $prompt,
                        "dates" => $date,
                        "date" => date("M d, Y", strtotime($date)),
                        "day" => $empDay,
                        "tardiness" => "HOLIDAY",
                        "late" =>"HOLIDAY",
                        "undertime" => "HOLIDAY",
                        "overtime" => "HOLIDAY",
                        "work" => "HOLIDAY",
                        "oTstatus" => "",
                        "nightDiffStatus" => "",
                        "nightHours" => "HOLIDAY"
                    );
                }else{
                    foreach($ins as $inss){
                        $inId = $inss["time_log_uid"];
                        $in = $inss["date_created"];
                        $in1 = date("Y-m-d", strtotime($in));
                        $inDay = date("N", strtotime($in1));
                        $inSession = $inss["session"];

                        $outss = getTimeOutByEmpUidAndDate($empId, $inSession);
                        if(!$outss){
                            $response[] = array(
                                "id" => $empId,
                                "lastname" => strtoupper($lastname),
                                "prompt" => $prompt,
                                "dates" => $date,
                                "date" => date("M d, Y", strtotime($date)),
                                "day" => $empDay,
                                "tardiness" => "HOLIDAY",
                                "late" =>"HOLIDAY",
                                "undertime" => "HOLIDAY",
                                "overtime" => "HOLIDAY",
                                "work" => "HOLIDAY",
                                "oTstatus" => "",
                                "nightDiffStatus" => "",
                                "nightHours" => "HOLIDAY"
                            );
                        }else{
                            $outId = $outss["time_log_uid"];
                            $out = $outss["date_created"];
                            $out1 = date("Y-m-d", strtotime($out));


                            $shift = getShiftByUidAndDate($empId, $in1, $inDay);
                            $shiftStart = $shift->start;
                            $shiftEnd = $shift->end;
                            $shiftEnds1 = $shiftEnd;
                            $grace = $shift->grace_period;
                            $shiftEnds = $shiftEnd;
                            $shiftStarts = $shiftStart;

                            if($grace != 0){
                                $dapatIn = date("H:i:s", strtotime("+$grace minutes", strtotime($shiftStart)));
                            }else{
                                $dapatIn = date("H:i:s", strtotime($shiftStart));
                            }
                            $inss = date("H:i:s", strtotime($in));
                            $outss = date("H:i:s", strtotime($out));

                            if(strtotime($shiftStart) < strtotime($shiftEnd)){
                                $shiftDuration = countDurationOfShifts($empId, $in1, $inDay);
                                $afterBreak = "13:00:00";

                                if(strtotime($inss) >= strtotime($afterBreak)){
                                    $shiftDuration = $shiftDuration;
                                }else{
                                    $shiftDuration = $shiftDuration - 1;
                                }
                            }else{
                                $shiftStart = "2015-02-01 " . $shiftStart;
                                $shiftEnd = "2015-02-02 " . $shiftEnd;

                                $shiftDuration = countDurationOfShiftsReversed($empId, $shiftStart, $shiftEnd, $inDay, $in1);
                                $shiftDuration = $shiftDuration - 1;
                            }
                            
                            /*WORKED FUNCTION*/
                            if(strtotime($out) < strtotime($in)){
                                $work = (strtotime($in) - strtotime($out)) / 3600;
                            }else if(strtotime($out) > strtotime($in)){
                                $work = (strtotime($out) - strtotime($in)) / 3600;
                            }//end of worked function

                            if($work === $shiftDuration){
                                $totalWork = $shiftDuration;
                            }else if($work > $shiftDuration){
                                $totalWork = $shiftDuration;
                                // echo "$id = " . $count3 - $excessTime . "<br/>";
                            }else if($work < $shiftDuration){
                                $totalWork = $work;
                            }//end of getting total work

                            $inn = date("H:i:s", strtotime($in));
                            $inHour = date("H:i:s", strtotime($dapatIn));
                            $empDates = date("Y-m-d", strtotime($empDate . "+1 day"));
                            if($in1 == $empDate){
                                $late++;
                            }//end of count for late
                            if($out1 == $empDate){
                                $under++;
                            }else if($out1 == $empDates){
                                $under++;
                            }//end of count for undertime
                            $lates = 0;
                            $undertime = 0;
                            $over = 0;
                            $getFirstIn = array();
                            // /*LATE FUNCTION*/
                            $inArray[] = array(
                                "inHour" => $inn, 
                                "inDate" => $empDate
                            );
                            $lateCount = countDate($empId, $empDate);

                            /*LATE FUNCTION*/
                            if(strtotime($inn) >= strtotime($inHour)){
                                if($late === $lateCount){
                                    for($x=0; $x < count($inArray); $x++){
                                        if(in_array($empDate, $inArray[$x])){
                                            $getFirstIn[] = $inArray[$x];
                                        }//end of checking
                                    }//end of forloop
                                    $inn = ($getFirstIn[0]["inHour"]);
                                    $empDate = ($getFirstIn[0]["inDate"]);
                                    if($in1 === $out1){
                                        if(strtotime($inn) >= strtotime($afterBreak)){
                                            $lates = ((strtotime($inn) - strtotime($shiftStarts)) / 3600) - 1;
                                        }else{
                                            $lates = (strtotime($inn) - strtotime($shiftStarts)) / 3600;
                                        }
                                    }else{
                                        $shiftStarts = $in1 . " " . $shiftStarts;
                                        $lates = (strtotime($in) - strtotime($shiftStarts)) / 3600;
                                    }
                                }//end of comparing counts
                            }//end of late function
                            $outHour = date("H:i:s", strtotime($out));

                            $undertimeCounts = countDateOut($empId, $out1);
                            /*UNDERTIME FUNCTION*/ 
                            $getLastOut = array();
                            if($undertimeCounts === $under){
                                if(strtotime($outHour) <= strtotime($shiftEnds)){
                                    $undertimeCounts = countDateOut($empId, $out1);
                                    $outArray = array(
                                        "outHour" => $outHour, 
                                        "outDate" => $out1
                                    );
                                    $outHour = $outArray["outHour"];
                                    $empDate = $outArray["outDate"];
                                    $outss = $empDate . " " . $outHour;

                                    if($in1 === $out1){
                                        $undertime = (strtotime($shiftEnds) - strtotime($outHour)) / 3600;
                                    }else{
                                        $shiftEnds = $out1 . " " . $shiftEnds;

                                        $undertime = (strtotime($shiftEnds) - strtotime($outss)) / 3600;
                                    }
                                }//end of comparison for undertime
                            }//end of getting count

                            $check = getOvertimeRequestByEmpUidAndDate($empId, $empDate, $empHolidayDate);
                            $checkEmpId = $check["emp_uid"];
                            $checkDate = $check["start_date"];
                            $checkHours = $check["hours"];

                            $oTstatus = 0;
                            $approvedDate = 0;

                            if(!$checkDate){

                            }else{
                                $approvedDate = $checkDate;
                                $oTstatus = 1;
                            }//end of checking
                            if($out1 === $out1){
                                $over++;
                            }

                            $outArray = array(
                                "outHour" => $outHour, 
                                "out" => $out, 
                                "outDate" => $out1
                            );
                            $nightH = 0;
                            $nightDiffStatus = 0;
                            /*OVERTIME FUNCTION*/
                            // if($undertimeCounts === $over){
                                $outHour = $outArray["outHour"];
                                $nightDiffStart = "22:00:00";
                                $nightDiffEnd = "06:00:00";
                                $nightDiffStarts = date("Y-m-d", strtotime($in . "- 0 day")) . " $nightDiffStart"; 
                                $nightDiffEnds = date("Y-m-d", strtotime($in . "- 0 day")) . " $nightDiffEnd"; 

                                if(strtotime($outHour) <= strtotime($nightDiffEnd) && strtotime($outHour) >= strtotime($nightDiffStarts)){
                                    $nightss = (strtotime($nightDiffEnd) - strtotime($outHour)) / 3600;
                                    $nightH = 8 - $nightss;
                                    $nightDiffStatus = 1;
                                // echo "$nightDiffEnd = $outHour = $nightss = $nightH<br/>";
                                }else if(strtotime($outHour) >= strtotime($nightDiffEnd) && strtotime($outHour) <= strtotime($nightDiffStarts)){
                                    $nightss = (strtotime($outHour) - strtotime($nightDiffEnd)) / 3600;
                                    $nightH = 8 - $nightss;
                                    $nightDiffStatus = 1;
                                // echo "$outHour = $nightDiffEnd = $nightss = $nightH<br/>";
                                }else if(strtotime($outHour) == strtotime($nightDiffEnd)){
                                    $nightss = (strtotime($outHour) - strtotime($nightDiffEnd)) / 3600;
                                    $nightH = 8 - $nightss;
                                    $nightDiffStatus = 1;
                                // echo "$outHour = $nightDiffEnd = $nightss = $nightH<br/>";
                                }
                                $nightH = floor($nightH);
                                $out = $outArray["out"];
                                if(strtotime($shiftEnd) <= strtotime($outHour)){
                                    if($in1 === $out1){
                                        $overtime = (strtotime($outHour) - strtotime($shiftEnd)) / 3600;
                                    }else{
                                        $shiftEnds = $out1 . $shiftEnds;
                                        $overtime = (strtotime($out) - strtotime($shiftEnds)) / 3600;
                                    }
                                }else if(strtotime($shiftEnd) >= strtotime($outHour)){
                                    if($in1 === $out1){
                                        $overtime = 0;
                                    }else{
                                        $shiftEnd = date("Y-m-d", strtotime($in . "- 0 day")) . " $shiftEnd"; 
                                        $overtime = (strtotime($out) - strtotime($shiftEnd)) / 3600;
                                    }//end of comparing dates
                                }//end of comparison for overtime
                            // }//end of comparing count

                            if($overtime > 60){
                                $overtime = 0;
                            }else if($overtime <= -1 ){
                                $overtime = 0;
                            }
                            $totalWork = $totalWork;
                            $tardiness = $lates + $undertime;
                            $response[] = array(
                                "id" => $empId,
                                "lastname" => strtoupper($lastname),
                                "prompt" => $prompt,
                                "dates" => $date,
                                "date" => date("M d, Y", strtotime($date)),
                                "day" => $empDay,
                                "tardiness" => $tardiness,
                                "late" => $lates,
                                "undertime" => $undertime,
                                "overtime" => $checkHours,
                                "work" => $totalWork,
                                "oTstatus" => $oTstatus,
                                "nightDiffStatus" => $nightDiffStatus,
                                "nightHours" => $nightH
                            );
                        }//end of out function
                    }//end of getTimeInByEmpUidAndDate Function
                }
                break;
            case 4: 
                $leaveEmpId = $id;
                $leaveDate = $date;
                // $absentNote = $time;
                $leaveDay = $day;
                $in = $time;
                $out = $time;
                
                $response[] = array(
                    "id" => $leaveEmpId,
                    "lastname" => strtoupper($lastname),
                    "prompt" => $prompt,
                    "dates" => $date,
                    "date" => date("M d, Y", strtotime($leaveDate)),
                    "day" => $leaveDay,
                    "tardiness" => "ON LEAVE",
                    "late" => "ON LEAVE",
                    "undertime" => "ON LEAVE",
                    "overtime" => "ON LEAVE",
                    "work" => "ON LEAVE",
                    "worked" => "",
                    "oTstatus" => "",
                    "nightDiffStatus" => 0,
                    "nightHours" => 0
                );
                break;
            case 5:

                $response[] = array(
                    "id" => $id,
                    "lastname" => strtoupper($lastname),
                    "prompt" => $prompt,
                    "dates" => $date,
                    "date" => date("M d, Y", strtotime($date)),
                    "day" => $day,
                    "tardiness" => "ABSENT",
                    "late" => "ABSENT",
                    "undertime" => "ABSENT",
                    "overtime" => "ABSENT",
                    "work" => "ABSENT",
                    "worked" => "",
                    "oTstatus" => "",
                    "nightDiffStatus" => 0,
                    "nightHours" => 0
                );
                break;
        }//end of switch
    }//end of for-loop
    foreach ($response as $k => $v) {
        $sort["dates"][$k] = $v["dates"];
    }//end of response

    array_multisort($sort["dates"], SORT_ASC,$response);

    // echo jsonify($response);
    return $response;
}

function getHolidayByEmpUid($startDate, $endDate, $emp){
    $holidayCount = 0;
    // $cost = "3D18640B-DA3F-5580-3CFC-E657624DC9F6";
    $response = array();
    $a = getEmpByUid($emp);
    if($a){
        $lastname = utf8_decode($a->lastname);
    }//end of getEmpByUid Function
    $holidays = getHolidayAndTypeByDates($startDate, $endDate);
    foreach($holidays as $holiday){
        $holidayDate = $holiday["date"];
        $holidayType = $holiday["type"];
        $holidayCode = $holiday["holiday_code"];
        $holidayRate = $holiday["rate"];

        $check = checkRestDay($emp, $holidayDate);
        if($check){
            $holidayId = $emp;
                // echo "$holiday<br/>";
            $holidayDate = $holidayDate;
            $holidayDay = date("d", strtotime($holidayDate));
            // $in = $time;
            // $out = $time;
            // echo "$restId = $restDate = $restNote<br/>";
            $empHolidayDate = $holidayDate;
            $ins = getTimeInByEmpUidAndDateNoLoc($holidayId, $holidayDate);
            $late = 0;
            $under = 0;

            foreach($ins as $inss){
                $inId = $inss["time_log_uid"];
                $in = $inss["date_created"];
                $in1 = date("Y-m-d", strtotime($in));
                $inDay = date("N", strtotime($in1));
                $inSession = $inss["session"];

                $outss = getTimeOutByEmpUidAndDateNoLoc($holidayId, $inSession);
                if(!$outss){
                    $response[] = array(
                        "id" => $holidayId,
                        "lastname" => strtoupper($lastname),
                        "prompt" => $prompt,
                        "dates" => $holidayDate,
                        "tardiness" => 0,
                        "late" => 0,
                        "undertime" => 0,
                        "overtime" => 0,
                        "work" => 0,
                        "oTstatus" => "",
                        "nightDiffStatus" => "",
                        "nightHours" => 0
                    );
                }else{
                    $outId = $outss["time_log_uid"];
                    $out = $outss["date_created"];
                    $out1 = date("Y-m-d", strtotime($out));

                    $shift = getShiftByUidAndDate($holidayId, $in1, $inDay);
                    $shiftStart = $shift->start;
                    $shiftEnd = $shift->end;
                    $shiftEnds1 = $shiftEnd;
                    $grace = $shift->grace_period;
                    $shiftEnds = $shiftEnd;
                    $shiftStarts = $shiftStart;

                    if($grace != 0){
                        $dapatIn = date("H:i:s", strtotime("+$grace minutes", strtotime($shiftStart)));
                    }else{
                        $dapatIn = date("H:i:s", strtotime($shiftStart));
                    }
                    $inss = date("H:i:s", strtotime($in));
                    $outss = date("H:i:s", strtotime($out));

                    if(strtotime($shiftStart) < strtotime($shiftEnd)){
                        $shiftDuration = countDurationOfShifts($holidayId, $in1, $inDay);
                        $afterBreak = "13:00:00";

                        if(strtotime($inss) >= strtotime($afterBreak)){
                            $shiftDuration = ($shiftDuration - 1) / 2;
                        }else{
                            $shiftDuration = $shiftDuration - 1;
                        }
                    }else{
                        $shiftStart = "2015-02-01 " . $shiftStart;
                        $shiftEnd = "2015-02-02 " . $shiftEnd;

                        $shiftDuration = countDurationOfShiftsReversed($holidayId, $shiftStart, $shiftEnd, $inDay, $in1);
                        $shiftDuration = $shiftDuration - 1;
                    }//end of getting shiftDuration

                    /*WORKED FUNCTION*/
                    if(strtotime($out) < strtotime($in)){
                        $work = (strtotime($in) - strtotime($out)) / 3600;
                    }else if(strtotime($out) > strtotime($in)){
                        $work = (strtotime($out) - strtotime($in)) / 3600;
                    }//end of worked function

                    // echo "$shiftHalf<br/>";
                    if($work === $shiftDuration){
                        $totalWork = $shiftDuration;
                    }else if($work > $shiftDuration){
                        $totalWork = $shiftDuration;
                        // echo "$id = " . $count3 - $excessTime . "<br/>";
                    }else if($work <= $shiftDuration){
                        $totalWork = $shiftDuration;
                    }//end of getting total work

                    $inn = date("H:i:s", strtotime($in));
                    $inHour = date("H:i:s", strtotime($dapatIn));
                    $empDates = date("Y-m-d", strtotime($holidayDate . "+1 day"));
                    if($in1 == $holidayDate){
                        $late++;
                    }//end of count for late
                    if($out1 == $holidayDate){
                        $under++;
                    }else if($out1 == $empDates){
                        $under++;
                    }//end of count for undertime
                    $lates = 0;
                    $undertime = 0;
                    $over = 0;
                    $getFirstIn = array();
                    // /*LATE FUNCTION*/
                    $inArray[] = array(
                        "inHour" => $inn, 
                        "inDate" => $holidayDate
                    );
                    $lateCount = countDate($holidayId, $holidayDate);

                    /*LATE FUNCTION*/
                    if(strtotime($inn) >= strtotime($inHour)){
                        if($late === $lateCount){
                            for($x=0; $x < count($inArray); $x++){
                                if(in_array($holidayDate, $inArray[$x])){
                                    $getFirstIn[] = $inArray[$x];
                                }//end of checking
                            }//end of forloop
                            $inn = ($getFirstIn[0]["inHour"]);
                            $empDate = ($getFirstIn[0]["inDate"]);
                            if($in1 === $out1){
                                if(strtotime($inn) >= strtotime($afterBreak)){
                                    $lates = ((strtotime($inn) - strtotime($shiftStarts)) / 3600) - 1;
                                }else{
                                    $lates = (strtotime($inn) - strtotime($shiftStarts)) / 3600;
                                }
                            }else{
                                $shiftStarts = $in1 . " " . $shiftStarts;
                                $lates = (strtotime($in) - strtotime($shiftStarts)) / 3600;
                            }
                        }//end of comparing counts
                    }//end of late function
                    $outHour = date("H:i:s", strtotime($out));

                    $undertimeCounts = countDateOut($holidayId, $out1);
                    /*UNDERTIME FUNCTION*/ 
                    $getLastOut = array();
                    if($undertimeCounts === $under){
                        if(strtotime($outHour) <= strtotime($shiftEnds)){
                            $undertimeCounts = countDateOut($holidayId, $out1);
                            $outArray = array(
                                "outHour" => $outHour, 
                                "outDate" => $out1
                            );
                            $outHour = $outArray["outHour"];
                            $empDate = $outArray["outDate"];
                            $outss = $empDate . " " . $outHour;

                            if($in1 === $out1){
                                $undertime = (strtotime($shiftEnds) - strtotime($outHour)) / 3600;
                            }else{
                                $shiftEnds = $out1 . " " . $shiftEnds;

                                $undertime = (strtotime($shiftEnds) - strtotime($outss)) / 3600;
                            }
                        }//end of comparison for undertime
                    }//end of getting count

                    $check = getOvertimeRequestByEmpUidAndDate($holidayId, $holidayDate, $holidayDate);
                    $checkEmpId = $check["emp_uid"];
                    $checkDate = $check["start_date"];
                    $oTstatus = 0;
                    $approvedDate = 0;

                    if(!$checkDate){

                    }else{
                        $approvedDate = $checkDate;
                        $oTstatus = 1;
                    }//end of checking
                    if($out1 === $out1){
                        $over++;
                    }

                    $outArray = array(
                        "outHour" => $outHour, 
                        "out" => $out, 
                        "outDate" => $out1
                    );
                    $nightH = 0;
                    $nightDiffStatus = 0;
                    /*OVERTIME FUNCTION*/
                    // if($undertimeCounts === $over){
                        $outHour = $outArray["outHour"];
                        $out = $outArray["out"];
                        $nightDiffStart = "22:00:00";
                        $nightDiffEnd = "06:00:00";
                        $nightDiffStarts = date("Y-m-d", strtotime($in . "- 0 day")) . " $nightDiffStart"; 
                        $nightDiffEnds = date("Y-m-d", strtotime($in . "- 0 day")) . " $nightDiffEnd"; 

                        if(strtotime($outHour) <= strtotime($nightDiffEnd) && strtotime($outHour) >= strtotime($nightDiffStarts)){
                            $nightss = (strtotime($nightDiffEnd) - strtotime($outHour)) / 3600;
                            $nightH = 8 - $nightss;
                            $nightDiffStatus = 1;
                        // echo "$nightDiffEnd = $outHour = $nightss = $nightH<br/>";
                        }else if(strtotime($outHour) >= strtotime($nightDiffEnd) && strtotime($outHour) <= strtotime($nightDiffStarts)){
                            $nightss = (strtotime($outHour) - strtotime($nightDiffEnd)) / 3600;
                            $nightH = 8 - $nightss;
                            $nightDiffStatus = 1;
                        // echo "$outHour = $nightDiffEnd = $nightss = $nightH<br/>";
                        }else if(strtotime($outHour) == strtotime($nightDiffEnd)){
                            $nightss = (strtotime($outHour) - strtotime($nightDiffEnd)) / 3600;
                            $nightH = 8 - $nightss;
                            $nightDiffStatus = 1;
                        // echo "$outHour = $nightDiffEnd = $nightss = $nightH<br/>";
                        }

                        $nightH = floor($nightH);

                        
                        if(strtotime($shiftEnd) <= strtotime($outHour)){
                            if($in1 === $out1){
                                $overtime = (strtotime($outHour) - strtotime($shiftEnd)) / 3600;
                            }else{
                                $shiftEnds = $out1 . $shiftEnds;
                                $overtime = (strtotime($out) - strtotime($shiftEnds)) / 3600;
                            }
                        }else if(strtotime($shiftEnd) >= strtotime($outHour)){
                            if($in1 === $out1){
                                $overtime = 0;
                            }else{
                                $shiftEnd = date("Y-m-d", strtotime($in . "- 0 day")) . " $shiftEnd"; 
                                $overtime = (strtotime($out) - strtotime($shiftEnd)) / 3600;
                            }//end of comparing dates
                        }//end of comparison for overtime
                    // }//end of comparing count

                    if($overtime > 60){
                        $overtime = 0;
                    }else if($overtime <= -1 ){
                        $overtime = 0;
                    }
                    $totalWork = $totalWork - ($lates + $undertime);
                    $tardiness = $lates + $undertime;
                    $response[] = array(
                        // "id" => $holidayId,
                        // "lastname" => strtoupper($lastname),
                        "prompt" => 1,
                        "dates" => $holidayDate,
                        "tardiness" => $tardiness,
                        "late" => $lates,
                        "undertime" => $undertime,
                        "work" => $totalWork,
                        "oTstatus" => $oTstatus,
                        "nightDiffStatus" => $nightDiffStatus,
                        "nightHours" => $nightH,
                        "code" => $holidayCode,
                        "rate" => $holidayRate
                    );
                }//end of out function
            }//end of getTimeInByEmpUidAndDate Function
        }else{
            $response[] = array(
                // "id" => $emp,
                // "lastname" => strtoupper($lastname),
                "prompt" => 1,
                "dates" => $holidayDate,
                "tardiness" => 0,
                "late" => 0,
                "undertime" => 0,
                "work" => 0,
                "oTstatus" => 0,
                "nightDiffStatus" => 0,
                "nightHours" => 0,
                "code" => 0,
                "rate" => 0
            );
        }
    }
    // echo "$holidayCount<br/>";
    // echo jsonify($response);
    return $response;
}

function timeSuperSummary($startDate, $endDate, $uid){
    $startDates = strtotime($startDate);
    $endDates = strtotime($endDate);

    $data = getEmployeeByCostCenterUid($uid);
    foreach($data as $datas){
        $dateCount = 0;
        $id = $datas["emp_uid"];
        $sum = timeSummaryByUid($startDate, $endDate, $id);
        $sums = 0;
        $apprvOT1 = 0;
        $excessTime3 = 0;
        $holiday3 = 0;
        $undertime3 = 0;
        $late3 = 0;
        $tardiness3 = 0;
        $finalAbsentCount = 0;
        $workDays = 0;
        $totalDays = 0;
        $workDaysCount = 0; 
        $leaveCount = 0;

        foreach($sum as $summary){
            $id1 = $summary["id"];
            $prompt = $summary["prompt"];
            $date = $summary["dates"];
            $day = date("N", strtotime($date));
            $tardiness = $summary["tardiness"];
            $late = $summary["late"];
            $undertime = $summary["undertime"];
            $overtime = $summary["overtime"];
            $worked = $summary["work"];
            $oTstatus = $summary["oTstatus"];
            $absentCount = 0;
            $noTimeCount = 0;
            $holidayCount = 0;
            $work = 0;
            $excessTime = 0;
            $approvedOvertime = 0;
            $undertime1 = 0;
            $late1 = 0;
            $tardiness1 = 0;
            $apprvOT = 0;  
            $workDaysCount = 0;

            if($id1 == $id){ 
                if($prompt === 1){
                    $shift = getShiftByDayAndEmpUid($id1, $date);

                    if($shift){
                        $shiftStart = $shift["start"];
                        $shiftEnd = $shift["end"]; 
                        if(strtotime($shiftStart) < strtotime($shiftEnd)){
                            $shiftDuration = countDurationOfShiftsByDayAndEmpUid($id1, $date);
                        }else{
                            $shiftStart = "2015-02-01 " . $shiftStart;
                            $shiftEnd = "2015-02-02 " . $shiftEnd;

                            $shiftDuration = countDurationOfShiftsReversedOffset($id1, $shiftStart, $shiftEnd, $date);
                        }//end of getting shift duration
                        $afterBreak = "13:00:00";

                        // if(strtotime($inss) >= strtotime($afterBreak)){
                        //     $shiftDuration = $shiftDuration;
                        // }else{
                            $shiftDuration = $shiftDuration - 1;
                        // }
                    }else{
                        $shiftDuration = 8;
                    }
                    $workDaysCount += ($worked / $shiftDuration);
                    $work += $worked;
                    $excessTime += $overtime;
                    $undertime1 += $undertime; 
                    $late1 += $late;
                    $tardiness1 += $tardiness;
                }else if($prompt === 0){
                    $noTimeCount++;
                }else if($prompt === 3){
                    $holidayCount++;
                }else if($prompt === 4){
                    $leaveCount++;
                }else if($prompt === 5){
                    $absentCount++;
                }//end of getting prompt

                if($oTstatus == 1){
                    $apprvOT += $overtime;
                }//end of getting ot status
            }//end of comparing id    
            $work3 = $work  + $holidayCount;
            $sums += $work3;
            $apprvOT1 += $apprvOT;
            $excessTime3 += $excessTime;
            $undertime3 += $undertime1;
            $late3 += $late1;
            $tardiness3 += $tardiness1;
            $finalAbsentCount += $absentCount;
            $holiday3 += $holidayCount;
            
            $workDays += $workDaysCount;

            if($late3 <= 0){
                $late3 = 0;
            }
            if($tardiness3 <= 0){
                $tardiness3 = 0;
            }
        }//end of timeSummaryByUid Function
        $workDays = $workDays + $leaveCount + $holiday3;

        $response[] = array(
            "id" => $id,
            "worked" => $sums,
            "overtime" => $excessTime3,
            "late" => $late3,
            "undertime" => $undertime3,
            "tardiness" => $tardiness3,
            "NoOfDays" => $workDays,
            "ApprovedOT" => $apprvOT1,
            "absent" => $finalAbsentCount
        );
    }//end of getting employee's data

    // echo jsonify($response);
    return $response;
}
function timeOrganizedSummary($startDate, $endDate, $uid){
    $response = array();
    $a = timeTotals($startDate, $endDate, $uid);
    foreach($a as $time){
        $id1 = $time["id"];
        $workTotal = $time["workTotal"];
        $absentTotal = $time["absentTotal"];
        $overtimeTotal = $time["overtimeTotal"];
        $lateTotal = $time["lateTotal"];
        $undertimeTotal = $time["undertimeTotal"];
        $ApprovedOTotal = $time["ApprovedOTotal"];
        $noOfDays = $time["noOfDays"];
        $tardiness1 = $time["tardiness1"];
        $emp = getEmpByUid($id1);
        if($emp){
            $id = $emp["emp_uid"];
            $checkSalary = checkGetFrequencyByEmpUid($id);
            if($checkSalary){
                $salaryData = getFrequencyByEmpUid($id);
                $payPeriodName = $salaryData->pay_period_name;
                $salary = $salaryData->base_salary;
            }else{
                $payPeriodName = "Not Set";
                $salary = "Not Set";
            }
            $lastname = utf8_decode($emp["lastname"]);
            $firstname = utf8_decode($emp["firstname"]);
            $middlename = utf8_decode($emp["middlename"]);
            $username = $emp["username"];

            $NoOfDays = $noOfDays;
            // $NoOfDays1 = $NoOfDays * 2;
            // $NoOfDays1 = floor($NoOfDays1);
            // $NoOfDays1 = $NoOfDays1 / 2;
            $NoOfDays2 = floor($NoOfDays);
            $NoOfDays3 = $NoOfDays - $NoOfDays2;

            if($NoOfDays3 == .50){
                $NoOfDays1 = round($NoOfDays * 2) / 2;
            }else if($NoOfDays3 >= .51){
                $NoOfDays1 = round($NoOfDays);
            }else if($NoOfDays3 <= .49){
                $NoOfDays1 = round($NoOfDays * 2) / 2;
                // $NoOfDays1 = $NoOfDays1;
            }
            $absents = $absentTotal;
            //WORKED
            $workHour = floor($workTotal);
            $totalWorkMin = (60*($workTotal-$workHour));
            $workMin = floor(60*($workTotal-$workHour));
            $workMin1 = floor($totalWorkMin);
            $workSec = round(60*($totalWorkMin-$workMin1));

            // $totalWorked = str_replace("-", "", $workHour) . ":" . str_replace("-", "", $workMin1) . ":" . str_replace("-", "", $workSec);
            $totalWorked = str_replace("-", "", str_pad($workHour, 2, "0", STR_PAD_LEFT)) . ":" . str_replace("-", "", str_pad($workMin1, 2, "0", STR_PAD_LEFT));

            //OVERTIME
            $overtimeHour = floor($overtimeTotal);
            $totalOvertimeMin = (60*($overtimeTotal-$overtimeHour));
            $overtimeMin = floor(60*($overtimeTotal-$overtimeHour));
            $overtimeMin1 = floor($totalOvertimeMin);
            $overtimeSec = round(60*($totalOvertimeMin-$overtimeMin1));

            if($overtimeMin >= 60){
                $overtimeMin = 0;
                $overtimeHour = $overtimeHour + 1;
            }else{
                $overtimeMin = $overtimeMin;
            }//end of checking overtime minute

            if($overtimeSec >= 60){
                $overtimeSec = 0;
                $overtimeMin = $overtimeMin + 1;
            }else{
                $overtimeSec = $overtimeSec;
            }
            // $totalOvertime = str_replace("-", "", $overtimeHour) . ":" . str_replace("-", "", $overtimeMin) . ":" . str_replace("-", "", $overtimeSec);
            $totalOvertime = str_replace("-", "", str_pad($overtimeHour, 2, "0", STR_PAD_LEFT)) . ":" . str_replace("-", "", str_pad($overtimeMin, 2, "0", STR_PAD_LEFT));

            //LATE
            $lateHour = floor($lateTotal);
            $totalLateMin = (60*($lateTotal-$lateHour));
            $lateMin = floor(60*($lateTotal-$lateHour));
            $lateMin1 = floor($totalLateMin);
            $lateSec = round(60*($totalLateMin-$lateMin1));

            if($lateMin >= 60){
                $lateMin = 0;
                $lateHour = $lateHour + 1;
            }else{
                $lateMin = $lateMin;
            }//end of checking overtime minute

            if($lateSec >= 60){
                $lateSec = 0;
                $lateMin = $lateMin + 1;
            }else{
                $lateSec = $lateSec;
            }
            // $totalLate = str_replace("-", "", $lateHour) . ":" . str_replace("-", "", $lateMin) . ":" . str_replace("-", "", $lateSec);
            $totalLate = str_replace("-", "", str_pad($lateHour, 2, "0", STR_PAD_LEFT)) . ":" . str_replace("-", "", str_pad($lateMin, 2, "0", STR_PAD_LEFT));

            //UNDERTIME
            $undertimeHour = floor($undertimeTotal);
            $totalUndertimeMin = (60*($undertimeTotal-$undertimeHour));
            $undertimeMin = floor(60*($undertimeTotal-$undertimeHour));
            $undertimeMin1 = floor($totalUndertimeMin);
            $undertimeSec = round(60*($totalUndertimeMin-$undertimeMin1));

            if($undertimeMin >= 60){
                $undertimeMin = 0;
                $undertimeHour = $undertimeHour + 1;
            }else{
                $undertimeMin = $undertimeMin;
            }//end of checking overtime minute

            if($undertimeSec >= 60){
                $undertimeSec = 0;
                $undertimeMin = $undertimeMin + 1;
            }else{
                $undertimeSec = $undertimeSec;
            }
            // $totalUndertime = str_replace("-", "", $undertimeHour) . ":" . str_replace("-", "", $undertimeMin) . ":" . str_replace("-", "", $undertimeSec);
            $totalUndertime = str_replace("-", "", str_pad($undertimeHour, 2, "0", STR_PAD_LEFT)) . ":" . str_replace("-", "", str_pad($undertimeMin, 2, "0", STR_PAD_LEFT));
            //APPROVED OT
            $approvedOTHour = floor($ApprovedOTotal);
            $totalApprovedOTMin = (60*($ApprovedOTotal-$approvedOTHour));
            $approvedOTMin = floor(60*($ApprovedOTotal-$approvedOTHour));
            $approvedOTMin1 = floor($totalApprovedOTMin);
            $approvedOTSec = round(60*($totalApprovedOTMin-$approvedOTMin1));

            $totalApprovedOT = str_replace("-", "", str_pad($approvedOTHour, 2, "0", STR_PAD_LEFT)) . ":" . str_replace("-", "", str_pad($approvedOTMin, 2, "0", STR_PAD_LEFT));

            //TARDINESS
            $totalTardy = floor($tardiness1);
            $totalTardyMin = (60*($tardiness1-$totalTardy));
            $totalTardyMins = floor(60*($tardiness1-$totalTardy));
            $totalTardyMin1 = floor($totalTardyMin);
            $totalTardySec = round(60*($totalTardyMins-$totalTardyMin1));

            // $totalTardiness = str_replace("-", "", $totalTardy) . ":" . str_replace("-", "", $totalTardyMins) . ":" . str_replace("-", "", $totalTardySec);
            $totalTardiness = str_replace("-", "", str_pad($totalTardy, 2, "0", STR_PAD_LEFT)) . ":" . str_replace("-", "", str_pad($totalTardyMins, 2, "0", STR_PAD_LEFT));

            if($payPeriodName != "Monthly"){
                $tardiness = $tardiness1 * 60;
                $tardiness = floor($tardiness);

                if($totalWorked === "0:0"){

                }else{
                    $response[] = array(
                        "id" => $id,
                        "name" => $lastname . ", " . $firstname . " " . $middlename,
                        "username" => $username,
                        "payPeriodName" => $payPeriodName,
                        "salary" => $salary,
                        "worked" => $totalWorked,
                        "overtime" => $totalOvertime,
                        "late" => $totalLate,
                        "undertime" => $totalUndertime,
                        "tardiness" => $tardiness,
                        "approvedOt" => $totalApprovedOT,
                        "days" => number_format($NoOfDays1, 2),
                        "totalTardy" => $totalTardiness,
                        "absent" => $absentTotal
                    );
                }
            }else{
                $absent = $absentTotal * 60 * 8;
                $tardiness = $tardiness1 * 60;
                $tardiness = floor($tardiness);
                $tardiness = $absent + $tardiness;
                if($totalWorked === "0:0"){

                }else{
                    $response[] = array(
                        "id" => $id,
                        "name" => $lastname . ", " . $firstname . " " . $middlename,
                        "username" => $username,
                        "payPeriodName" => $payPeriodName,
                        "salary" => $salary,
                        "worked" => $totalWorked,
                        "overtime" => $totalOvertime,
                        "late" => $totalLate,
                        "undertime" => $totalUndertime,
                        "tardiness" => $tardiness,
                        "approvedOt" => $totalApprovedOT,
                        "days" => number_format($NoOfDays1, 2),
                        "totalTardy" => $totalTardiness,
                        "absent" => $absentTotal
                    );
                }
            }//end of getting period name
        }
        // if($totalWorked === "0:0:0"){
        // }else{
            $response[] = array(
                "id" => $id,
                "name" => $lastname . ", " . $firstname . " " . $middlename,
                "username" => $username,
                "payPeriodName" => $payPeriodName,
                "salary" => $salary,
                "worked" => $totalWorked,
                "overtime" => $totalOvertime,
                "late" => $totalLate,
                "undertime" => $totalUndertime,
                "tardiness" => $tardiness,
                "approvedOt" => $totalApprovedOT,
                "days" => number_format($NoOfDays1, 2),
                "totalTardy" => $totalTardiness,
                "absent" => $absentTotal
            );
        // }
        
    }//end of getting employee's data
    $response = array_map('unserialize', array_unique(array_map('serialize', $response)));
    // echo jsonify($response);
    return $response;
}

function timeTotals($startDate, $endDate, $uid){
    $a = timeSuperSummary($startDate, $endDate, $uid);
    
    $response = array();
    foreach($a as $time){
        $id1 = $time["id"];
        $worked = $time["worked"];
        $overtime = $time["overtime"];
        $late = $time["late"];
        $undertime = $time["undertime"];
        $tardiness = $time["tardiness"];
        $NoOfDays = $time["NoOfDays"];
        $ApprovedOT = $time["ApprovedOT"];
        $absent = $time["absent"];
        
        $workTotal = $worked;
        $absentTotal = $absent;
        $overtimeTotal = $overtime;
        $lateTotal = $late;
        $undertimeTotal = $undertime;
        $ApprovedOTotal = $ApprovedOT;
        $noOfDays = $NoOfDays;
        $tardiness1 = $tardiness;

        $response[] = array(
            "id" => $id1,
            "workTotal" => $workTotal,
            "absentTotal" => $absentTotal,
            "overtimeTotal" => $overtimeTotal,
            "lateTotal" => $lateTotal,
            "undertimeTotal" => $undertimeTotal,
            "ApprovedOTotal" => $ApprovedOTotal,
            "noOfDays" => $noOfDays,
            "tardiness1" => $tardiness1
        );
    }//end of timeSuperSummary  Function

    return $response;
}

function getValidDependentCountByEmpUid($emp){
    $dependents = getAllEmployeeDependentBday($emp);
    $dependentCount = 0;

    foreach ($dependents as $dependents) {
        $empUid = $dependents["emp_uid"];
        $depBday = $dependents["bday"];
        $depUid = $dependents["emp_dependent_uid"];

        //COMPUTATION OF AGE
        $one = new DateTime($depBday);
        $today = new DateTime();

        $diff = $today->diff($one);
        $age = $diff->y;

        $result = 0;

        if($age <= 21){
            $validDependent = $depUid;

            $sample = getDependentDataByUid($validDependent);
            foreach($sample as $sam){
                $sample1 = number_format($sam["count"]);
                $sample2 = $sam["emp_dependent_uid"];
                $sample3 = $sam["emp_uid"];

                $result++;
            }//end of getDependentDataByUid Function
        }else{
            $error = "NOT VALID";
        }//end of checking age
        $dependentCount += $result;

    }//end of getAllEmployeeDependentBday Function
    $response = array(
        "emp" => $emp,
        "dependentValidCount" => $dependentCount
    );

    // echo jsonify($response);
    return $response;
}

function employeeTax($startDate, $endDate, $frequencyUid){
    $emp = getAllEmployeeSalaryData();
    $pS = getSchedules($frequencyUid);
    $tax = getTax($frequencyUid);
    $exemp = getExemption($frequencyUid);

    if($pS){
        $schedStartDate = $pS["payroll_date"];
        $schedEndDate = $pS["cutoff_date"];
    }//end of getting schedule

    if($schedStartDate == $startDate && $schedEndDate == $endDate){
        foreach($emp as $dependent){
            $id = $dependent["emp_uid"];
            $depName = $dependent["name"];
            $empLastname = utf8_decode($dependent["lastname"]);           
            $empFirstname = utf8_decode($dependent["firstname"]);
            $empSalary = $dependent["base_salary"];
            $empStatus = $dependent["marital"];
            $depRelationship = $dependent["relationship"];
            $taxNo = $dependent["tax_no"];

            $a = getPayperiodAndSalaryByEmpUid($id);
            $payPeriod = $a->pay_period_uid;

            if($payPeriod == $frequencyUid){
                $pId = $id;
                $empT = getEmpTimeLogByUid($pId, $startDate, $endDate, $frequencyUid);

                if($empT){
                    $emId = $empT["id"];
                    $days = $empT["days"];
                    $daysOfWork = $empT["daysOfWork"];
                    $salary = $empT["sahod"];
                    $salaryPerDay = $empT["sahodPerDay"];
                    $salaryPerHour = $empT["hourlyPayment"];
                    $oTpay = $empT["oTpay"];
                    $tardiness = $empT["tardiness"];
                    $allowance = $empT["allowance"];
                    $grosspay = $empT["grossPay"];
                    $loan = $empT["loan"];
                    $pettycash = $empT["pettycash"];
                    $leavePay = $empT["leavePay"];
                    $holidayPay = $empT["holidayPay"];
                    $nightDifferentialPay = $empT["nightDifferential"];
                    $grossEarnings = $empT["grossEarnings"];
                    $adjustment = $empT["adjustment"];

                    $y = getEmpNetPayByEmpUid($pId);
                    foreach ($y as $pag) {
                        $netId = $pag["netId"];
                        $sss = $pag["sss"];
                        $philhealth = $pag["philhealth"];
                        $pagibig = $pag["pagibig"];
                        $totalContri = $pag["totalContri"];
                    }//end of getEmpNetPayByEmpUid function

                    $birthday = getAllEmployeeDependentBday($pId);
                    $result = 0;

                    foreach($birthday as $bday){
                        $birth = $bday->bday;

                        $one = new DateTime($birth);
                        $today = new DateTime();

                        $diff = $today->diff($one);
                        $age = $diff->y;

                        if($age <= 21){
                            $validDependent = $bday->emp_dependent_uid;
                            $empDepUid = $bday->emp_uid;

                            $sample = getDependentDataByUid($validDependent);
                            foreach($sample as $sam){
                                $sample1 = number_format($sam["count"]);
                                $sample2 = $sam["emp_dependent_uid"];
                                $sample3 = $sam["emp_uid"];

                                $result++;
                            }//end of getDependentDataByUid Function
                        }else{
                            $error = "NOT VALID";
                        }//end of checking age
                    }//end of getting dependent's birthday
                    $taxIn1 = $salary + $oTpay + $holidayPay + $nightDifferentialPay;
                    if($tardiness > $grosspay){
                        $taxIn2 = $allowance + $sss + $philhealth + $pagibig;
                    }else{
                        $taxIn2 = $tardiness + $allowance + $sss + $philhealth + $pagibig;
                    }
                    $taxableIncome = $taxIn1 - $taxIn2;
                    if(!$taxNo){
                        $tatlo = 0;
                    }else{
                        switch ($empStatus) {
                            case "Single":
                                $totalDepCount = $result;
                                switch($totalDepCount){
                                    case "0":
                                        $singleStatus = "S/ME";
                                        // echo "$id - $singleStatus<br/>";
                                        break;
                                    case "1":
                                        $singleStatus = "ME1/S1";
                                        // echo "$id - $singleStatus<br/>";
                                        break;
                                    case "2":
                                        $singleStatus = "ME2/S2";
                                        // echo "$id - $singleStatus<br/>";
                                        break;
                                    case "3":
                                        $singleStatus = "ME3/S3";
                                        // echo "$id - $singleStatus<br/>";
                                        break;
                                    case "4":
                                        $singleStatus = "ME4/S4";
                                        // echo "$id - $singleStatus<br/>";
                                        break;
                                    case "5":
                                        $singleStatus = "ME4/S4";
                                        // echo "$id - $singleStatus<br/>";
                                        break;
                                }//end of switch for dependent count
                                foreach($exemp as $ex){
                                    $exId = $ex["e_id"];
                                    $exExemption = $ex["exemption"];
                                    $exStatus = $ex["status"];

                                    foreach($tax as $taxx){
                                        $sample1 = $taxx["id1"];
                                        $sample2 = $taxx["id2"];
                                        $exemption = $taxx["exemption"];
                                        $taxStatus = $taxx["status"];
                                        $depMarital = $taxx["dep_status"];
                                        $one = $taxx["no_dep_1"];
                                        $two = $taxx["no_dep_2"];   
                                        $three = $taxx["no_dep_3"];
                                        $four = $taxx["no_dep_4"];
                                        $five = $taxx["no_dep_5"];
                                        $six = $taxx["no_dep_6"];
                                        $seven = $taxx["no_dep_7"];
                                        $eight = $taxx["no_dep_8"];

                                        if($singleStatus == $depMarital){

                                            if($taxableIncome >= $one && $taxableIncome <= $two){
                                                $number = 1;
                                                if($number == $exId){
                                                    // echo "1<br/>";
                                                    $una = $taxableIncome - $one;
                                                    $dalawa = $una * $exStatus;
                                                    $tatlo = $dalawa + $exExemption;
                                                    // echo "<br/>WITHHOLDING TAX: $id - $tatlo <br/>";
                                                }
                                            }else if($taxableIncome >= $two && $taxableIncome <= $three){
                                                $number = 2;
                                                if($number == $exId){
                                                    // echo "2<br/>";
                                                    $una = $taxableIncome - $two;
                                                    $dalawa = $una * $exStatus;
                                                    $tatlo = $dalawa + $exExemption;
                                                    // echo "<br/>WITHHOLDING TAX: $id - $tatlo <br/>";
                                                }
                                            }else if($taxableIncome >= $three && $taxableIncome <= $four){
                                                $number = 3;
                                                if($number == $exId){
                                                    // echo "3<br/>";
                                                    $una = $taxableIncome - $three;
                                                    $dalawa = $una * $exStatus;
                                                    $tatlo = $dalawa + $exExemption;
                                                    // echo "<br/>WITHHOLDING TAX: $id - $tatlo <br/>";
                                                }
                                            }else if($taxableIncome >= $four && $taxableIncome <= $five){
                                                $number = 4;
                                                if($number == $exId){
                                                    // echo "4<br/>";
                                                    $una = $taxableIncome - $four;
                                                    $dalawa = $una * $exStatus;
                                                    $tatlo = $dalawa + $exExemption;
                                                    // echo "<br/>WITHHOLDING TAX: $id - $tatlo <br/>";
                                                }
                                            }else if($taxableIncome >= $five && $taxableIncome <= $six){
                                                $number = 5;
                                                if($number == $exId){
                                                    // echo "5<br/>";
                                                    $una = $taxableIncome - $five;
                                                    $dalawa = $una * $exStatus;
                                                    $tatlo = $dalawa + $exExemption;
                                                    // echo "<br/>WITHHOLDING TAX: $id - $tatlo <br/>";
                                                }
                                            }else if($taxableIncome >= $six && $taxableIncome <= $seven){
                                                $number = 6;
                                                if($number == $exId){
                                                    // echo "6<br/>";
                                                    $una = $taxableIncome - $six;
                                                    $dalawa = $una * $exStatus;
                                                    $tatlo = $dalawa + $exExemption;
                                                    // echo "<br/>WITHHOLDING TAX: $id - $tatlo <br/>";
                                                }
                                            }else if($taxableIncome >= $seven && $taxableIncome <= $eight){
                                                $number = 7;
                                                if($number == $exId){
                                                    // echo "7<br/>";
                                                    $una = $taxableIncome - $seven;
                                                    $dalawa = $una * $exStatus;
                                                    $tatlo = $dalawa + $exExemption;
                                                    // echo "<br/>WITHHOLDING TAX: $id - $tatlo <br/>";
                                                }
                                            }else  if($taxableIncome >= $eight){
                                                $number = 8;
                                                if($number == $exId){
                                                    // echo "8<br/>";
                                                    $una = $taxableIncome - $eight;
                                                    $dalawa = $una * $exStatus;
                                                    $tatlo = $dalawa + $exExemption;
                                                    // echo "<br/>WITHHOLDING TAX: $id - $tatlo <br/>";
                                                }
                                            }
                                        }//end of comparison
                                    }//end of getting tax
                                }//end of getting exemption
                                break;
                            case "Married":
                                $totalDepCount = $result;
                                switch($totalDepCount){
                                    case "0":
                                        $marriedStatus = "S/ME";
                                        // echo "$id - $marriedStatus<br/>";
                                        break;
                                    case "1":
                                        $marriedStatus = "ME1/S1";
                                        // echo "$id - $marriedStatus<br/>";
                                        break;
                                    case "2":
                                        $marriedStatus = "ME2/S2";
                                        // echo "$id - $marriedStatus<br/>";
                                        break;
                                    case "3":
                                        $marriedStatus = "ME3/S3";
                                        // echo "$id - $marriedStatus<br/>";
                                        break;
                                    case "4":
                                        $marriedStatus = "ME4/S4";
                                        // echo "$id - $marriedStatus<br/>";
                                        break;
                                    default:
                                        $marriedStatus = "ME4/S4";
                                        // echo "$id - $marriedStatus<br/>";
                                        break;
                                }//end of getting dependent count
                                foreach($exemp as $ex){
                                    $exId = $ex["e_id"];
                                    $exExemption = $ex["exemption"];
                                    $exStatus = $ex["status"];

                                    foreach($tax as $taxx){
                                        $sample1 = $taxx["id1"];
                                        $sample2 = $taxx["id2"];
                                        $exemption = $taxx["exemption"];
                                        $taxStatus = $taxx["status"];
                                        $depMarital = $taxx["dep_status"];
                                        $one = $taxx["no_dep_1"];
                                        $two = $taxx["no_dep_2"];   
                                        $three = $taxx["no_dep_3"];
                                        $four = $taxx["no_dep_4"];
                                        $five = $taxx["no_dep_5"];
                                        $six = $taxx["no_dep_6"];
                                        $seven = $taxx["no_dep_7"];
                                        $eight = $taxx["no_dep_8"];

                                        if($marriedStatus == $depMarital){
                                            if($taxableIncome >= $one && $taxableIncome <= $two){
                                                $number = 1;
                                                if($number == $exId){
                                                    // echo "1<br/>";
                                                    $una = $taxableIncome - $one;
                                                    $dalawa = $una * $exStatus;
                                                    $tatlo = $dalawa + $exExemption;
                                                    // echo "<br/>WITHHOLDING TAX: $id ==   $tatlo <br/>";
                                                }
                                            }else if($taxableIncome >= $two && $taxableIncome <= $three){
                                                $number = 2;
                                                if($number == $exId){
                                                    // echo "2<br/>";
                                                    $una = $taxableIncome - $two;
                                                    $dalawa = $una * $exStatus;
                                                    $tatlo = $dalawa + $exExemption;
                                                    // echo "<br/>WITHHOLDING TAX: $id ==   $tatlo <br/>";
                                                }
                                            }else if($taxableIncome >= $three && $taxableIncome <= $four){
                                                $number = 3;
                                                if($number == $exId){
                                                    // echo "3<br/>";
                                                    $una = $taxableIncome - $three;
                                                    $dalawa = $una * $exStatus;
                                                    $tatlo = $dalawa + $exExemption;
                                                    // echo "<br/>WITHHOLDING TAX: $id ==   $tatlo <br/>";
                                                }
                                            }else if($taxableIncome >= $four && $taxableIncome <= $five){
                                                $number = 4;
                                                if($number == $exId){
                                                    // echo "4<br/>";
                                                    $una = $taxableIncome - $four;
                                                    $dalawa = $una * $exStatus;
                                                    $tatlo = $dalawa + $exExemption;
                                                    // echo "<br/>WITHHOLDING TAX: $id ==   $tatlo <br/>";
                                                }
                                            }else if($taxableIncome >= $five && $taxableIncome <= $six){
                                                $number = 5;
                                                if($number == $exId){
                                                    // echo "5<br/>";
                                                    $una = $taxableIncome - $five;
                                                    $dalawa = $una * $exStatus;
                                                    $tatlo = $dalawa + $exExemption;
                                                    // echo "<br/>WITHHOLDING TAX: $id ==   $tatlo <br/>";
                                                }
                                            }else if($taxableIncome >= $six && $taxableIncome <= $seven){
                                                $number = 6;
                                                if($number == $exId){
                                                    // echo "6<br/>";
                                                    $una = $taxableIncome - $six;
                                                    $dalawa = $una * $exStatus;
                                                    $tatlo = $dalawa + $exExemption;
                                                    // echo "<br/>WITHHOLDING TAX: $id ==   $tatlo <br/>";
                                                }
                                            }else if($taxableIncome >= $seven && $taxableIncome <= $eight){
                                                $number = 7;
                                                if($number == $exId){
                                                    // echo "7<br/>";
                                                    $una = $taxableIncome - $seven;
                                                    $dalawa = $una * $exStatus;
                                                    $tatlo = $dalawa + $exExemption;
                                                    // echo "<br/>WITHHOLDING TAX: $id ==   $tatlo <br/>";
                                                }
                                            }else  if($taxableIncome >= $eight){
                                                $number = 8;
                                                if($number == $exId){
                                                    // echo "8<br/>";
                                                    $una = $taxableIncome - $eight;
                                                    $dalawa = $una * $exStatus;
                                                    $tatlo = $dalawa + $exExemption;
                                                    // echo "<br/>WITHHOLDING TAX: $id ==   $tatlo <br/>";
                                                }
                                            }
                                        }//end of comparison
                                    }//end of getting tax
                                }//end of getting exemption
                                break;
                        }//end of switch for employee's status
                    }

                    $deduction = $sss + $philhealth + $pagibig + $tatlo + $pettycash + $loan;
                    $response[] = array(
                        "id" => $id,
                        "days" => $days,
                        "daysOfWork" => $daysOfWork,
                        "salary" => $salary,
                        "salaryPerDay" => $salaryPerDay,
                        "salaryPerHour" => $salaryPerHour,
                        "oTpay" => $oTpay,
                        "tardiness" => $tardiness,
                        "allowance" => $allowance,
                        "grosspay" => $grosspay,
                        "loan" => $loan,
                        "pettycash" => $pettycash,
                        "leavePay" => $leavePay,
                        "sss" => $sss,
                        "philhealth" => $philhealth,
                        "pagibig" => $pagibig,
                        "deduction" => $deduction,
                        "pettyCash" => $pettycash,
                        "adjustment" => $adjustment,
                        "grossEarnings" => $grossEarnings,
                        "tax" => $tatlo,
                        "error" => "NO ERROR",
                        "errorStatus" => 0
                    );
                }//end of getEmpTimeLogByUid Function
            }//end of getting pay period
        }//end of getting employee's data
    }else{
        $response[] = array(
            "error" => "NOT IN PAYROLL SCHEDULE!",
            "errorStatus" => 1
        );
    }//end of comparing dates
    // echo jsonify($response);
    return $response;
}

/*------------------------------ PAYROLL end-----------------------------*/
/*------------------------------ Working Experince-----------------------------*/
function updateWorkExperience($employer , $jobTitle , $from , $to , $dateModified , $status , $workExperienceUid) {
    $query = ORM::forTable("hris_work_experience")->where("work_experience_uid", $workExperienceUid)->findOne();
        $query->set("employer", $employer);
        $query->set("job_title", $jobTitle);
        $query->set("we_from", $from);
        $query->set("we_to", $to);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function getWorkExperienceByWorkExperienceUid($workExperienceUid) {
    $query = ORM::forTable("hris_work_experience")->where("work_experience_uid", $workExperienceUid)->where("status", 1)->findOne();
        return $query;
}

function newWorkingExperince($empWEUid , $empUid , $employer , $jobTitle , $from , $to , $dateCreated , $dateModified) {
    $query = ORM::forTable("hris_work_experience")->create();
        $query->work_experience_uid = $empWEUid;
        $query->emp_uid = $empUid;
        $query->employer = $employer;
        $query->job_title = $jobTitle;
        $query->we_from = $from;
        $query->we_to = $to;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getWorkExperienceCount($empUid) {
    $query = ORM::forTable("hris_work_experience")->select_expr("count(work_experience_uid)", "count")->where("status", 1)->where("emp_uid", $empUid)->find_result_set();
        return $query;
}

function getWorkExperienceCountByTerm($term,$empUid) {
    $query = ORM::forTable("hris_work_experience")->select_expr("count(work_experience_uid)", "count")->where("status", 1)->where("emp_uid", $empUid)->whereRaw("('employer' LIKE '"%$term%"') OR ('job_title' LIKE '"%$term%"') OR ('comments' LIKE '"%$term%"')")->findOne();
}

function getPaginatedWorkExperience($start , $size , $empUid) {
    $query = ORM::forTable("hris_work_experience")->where("status", 1)->where("emp_uid", $empUid)->limit($start)->limit($size)->findMany();
        return $query;
}

function getPaginatedWorkExperienceByTerm($start , $size , $term , $empUid) {
    $query = ORM::forTable("hris_work_experience")->select_expr("count(work_experience_uid)", "count")->where("status", 1)->where("emp_uid", $empUid)->whereRaw("('employer' LIKE '"%$term%"') OR ('job_title' LIKE '"%$term%"') OR ('comments' LIKE '"%$term%"')")->limit($start)->limit($size)->findMany();
        return $query;
}

function newLeaveRequest($leaveUid, $employee, $leaveType, $leaveBalance, $startDate, $endDate, $reason ,$requestStatus, $dateCreated, $dateModified){
    $query = ORM::forTable("leave_requests")->create();
        $query->leave_uid = $leaveUid;
        $query->emp_uid = $employee;
        $query->leaves_types_uid = $leaveType;
        $query->leave_entitlement_uid = $leaveBalance;
        $query->start_date = $startDate;
        $query->end_date = $endDate;
        $query->reason = $reason;
        $query->leave_request_status = $requestStatus;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getLeavePeriod(){
    $query = ORM::forTable("leave_period")->where("status", 1)->findMany();
        return $query;
}

function getLeaveTypes(){
    $query = ORM::forTable("leaves_types")->where("status", "1")->findMany();
        return $query;
}

function getLeaveTypeDataByUid($uid){
    $query = ORM::forTable("leaves_types")->where("leaves_types_uid", $uid)->findOne();

    return $query;
}

function getLeaveTypeByCode($code){
    $query = ORM::forTable("leaves_types")->where("leave_code", $code)->where("status", 1)->findOne();
    return $query->leaves_types_uid;
}

function getLeaveCodeByuid($uid){
    $query = ORM::forTable("leaves_types")->where("leaves_types_uid", $uid)->where("status", 1)->findOne();
    return $query->leave_code;
}

function addOtherTableLeave($empNumber, $startDate, $endDate, $reason, $leaveName, $leaveCode, $requestStatus){
    $query = ORM::forTable("overtime_leave")->create();
        $query->user_id = $empNumber;
        $query->sdate = $startDate;
        $query->fromdate = $startDate;
        $query->todate = $endDate;
        $query->reason = $reason;
        $query->type = $leaveName;
        $query->code = $leaveCode;
        $query->status = $requestStatus;
    $query->save();
}

function checkEmpLeaveCountByEmpUid($emp){
    $query = ORM::forTable("emp_leave_count")->where("emp_uid", $emp)->where("status", 1)->count();
    $valid = false;

    if($query >= 1){
        $valid = true;
    }

    return $valid;
}

function setEmpLeaveCounts($leaveCountUid, $emp, $sL, $bL, $brL, $vL, $mL, $pL, $dateCreated, $dateModified){
    $query = ORM::forTable("emp_leave_count")->create();
        $query->emp_leave_count_uid = $leaveCountUid;
        $query->emp_uid = $emp;
        $query->SL = $sL;
        $query->BL = $bL;
        $query->BV = $brL;
        $query->VL = $vL;
        $query->ML = $mL;
        $query->PL = $pL;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getEmpLeaveCountPages(){
    $query = ORM::forTable("emp_leave_count")->tableAlias("t1")->innerJoin("emp", array("t1.emp_uid", "=", "t2.emp_uid"), "t2")->innerJoin("users", array("t1.emp_uid", "=", "t3.emp_uid"), "t3")->where("t1.status", 1)->findMany();
    return $query;
}

function getEmpLeaveCountPagesByEmpUid($uid){
    $query = ORM::forTable("emp_leave_count")->tableAlias("t1")->innerJoin("emp", array("t1.emp_uid", "=", "t2.emp_uid"), "t2")->innerJoin("users", array("t1.emp_uid", "=", "t3.emp_uid"), "t3")->where("t1.emp_uid", $uid)->where("t1.status", 1)->findOne();
    return $query;
}

function getEmpLeaveCountByUid($uid){
    $query = ORM::forTable("emp_leave_count")->where("emp_leave_count_uid", $uid)->findOne();
    return $query;
}

function getEmpLeaveCountByEmp($emp){
    $query = ORM::forTable("emp_leave_count")->where("emp_uid", $emp)->findOne();
    return $query;
}

function checkTimeLog($id, $startDate, $endDate){
    $query = ORM::forTable("time_log")
        ->rawQuery("SELECT * FROM time_log WHERE emp_uid = :uid AND date_created BETWEEN :startDate AND :endDate AND status = 1", array("uid" => $id, "startDate" => $startDate, "endDate" => $endDate))->findOne();
        return $query;
}

function updateEmpLeaveCounts($uid, $sL, $bL, $bV, $vL, $mL, $pL, $dateModified, $status){
    $query = ORM::forTable("emp_leave_count")->where("emp_leave_count_uid", $uid)->findOne();
        $query->set("SL", $sL);
        $query->set("BL", $bL);
        $query->set("BV", $bV);
        $query->set("VL", $vL);
        $query->set("ML", $mL);
        $query->set("PL", $pL);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function updateEmpLeaveCountsByEmpUid($uid, $sL, $bL, $bV, $vL, $mL, $pL, $dateModified){
    $query = ORM::forTable("emp_leave_count")->where("emp_uid", $uid)->findOne();
        $query->set("SL", $sL);
        $query->set("BL", $bL);
        $query->set("BV", $bV);
        $query->set("VL", $vL);
        $query->set("ML", $mL);
        $query->set("PL", $pL);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function checkPreviousClockLog($uid){
    $query = ORM::forTable("time_log")->where("emp_uid", $uid)->where("status", 1)->count();

    $valid = false;
    if($query >= 1){
        $valid = true;
    }

    return $valid;
}

function checkIfClockIn($uid){
    $query = ORM::forTable("time_log")->where("emp_uid", $uid)->where("status", 1)->orderByDesc("date_created")->limit(1)->findOne();
    $valid = false;
    if($query->type === "0"){
        $valid = true;
    }
    return $valid;
}

function checkIfClockOut($uid){
    $query = ORM::forTable("time_log")->where("emp_uid", $uid)->where("status", 1)->orderByDesc("date_created")->limit(1)->findOne();
    $valid = false;
    if($query->type === "1"){
        $valid = true;
    }
    return $valid;
}
function getPreviousTimeSession($uid){
    $query = ORM::forTable("time_log")->where("emp_uid", $uid)->where("type", 0)->where("status", 1)->orderByDesc("date_created")->limit(1)->findOne();
    return $query->session;
}

function checkTimeLogIn($id, $startDate, $endDate, $holiday){
    $query = ORM::forTable("time_log")
        ->rawQuery("SELECT * FROM `time_log` WHERE date_created BETWEEN :startDate AND :endDate AND date_created LIKE CONCAT('%', :holidayDate, '%') AND emp_uid = :uid AND type = 0 AND status = 1", array("uid" => $id, "startDate" => $startDate, "endDate" => $endDate, "holidayDate" => $holiday))->findOne();
        return $query;
}
function checkTimeLogOut($id, $startDate, $endDate, $holiday){
    $query = ORM::forTable("time_log")
        ->rawQuery("SELECT * FROM `time_log` WHERE date_created BETWEEN :startDate AND :endDate AND date_created LIKE CONCAT('%', :holidayDate, '%') AND emp_uid = :uid AND type = 1 AND status = 1", array("uid" => $id, "startDate" => $startDate, "endDate" => $endDate, "holidayDate" => $holiday))->findOne();
        return $query;
}

function addLeaveType($leaveUid, $code ,$name, $dateCreated, $dateModified){
    $query = ORM::forTable("leaves_types")->create();
        $query->leaves_types_uid = $leaveUid;
        $query->leave_code = $code;
        $query->leave_name = $name;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getLeaveByUid($uid){
    $query = ORM::forTable("leave_requests")->where("leave_uid", $uid)->findOne();
    return $query;
}

function deleteLeaveByUid($uid, $status){
    $query = ORM::forTable("leave_requests")->where("leave_uid", $uid)->findOne();
    $query->set("status", $status);
    $query->save();
}

function editLeaveByUid($uid, $leaveStart, $leaveEnd, $leaveStatus, $user1, $user2 ,$dateModified, $status){
    $query = ORM::forTable("leave_requests")->where("leave_uid", $uid)->findOne();

    if($leaveStatus == "Certified"){
        $query->set("start_date", $leaveStart);
        $query->set("end_date", $leaveEnd);
        $query->set("leave_request_status", $leaveStatus);
        $query->set("cert_by", $user1);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    }else if($leaveStatus == "Approved"){
        $query->set("start_date", $leaveStart);
        $query->set("end_date", $leaveEnd);
        $query->set("leave_request_status", $leaveStatus);
        $query->set("appr_by", $user2);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    }else{
        $query->set("start_date", $leaveStart);
        $query->set("end_date", $leaveEnd);
        $query->set("leave_request_status", $leaveStatus);
        $query->set("cert_by", $user1);
        $query->set("appr_by", $user2);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    }
        
    $query->save();
}

function checkAbsentByDateAndEmpUid($uid, $date){
    $query = ORM::forTable("leave_requests")
        ->rawQuery("SELECT count(id) as count FROM leave_requests WHERE start_date = :dates AND emp_uid = :uid AND leave_request_status = 'Approved' AND status=1", array("dates" => $date, "uid" => $uid))->findOne();
    $valid = false;

    if($query->count >= 1){
        $valid = true;
    }

    return $valid;
}

function getLeaveRequestsDataByUid($uid){
    $query = ORM::forTable("leave_requests")->where("leave_uid", $uid)->findOne();
    return $query;
}

function getLeaveRequestsByUid(){
    $query = ORM::forTable("emp")
    ->rawQuery("SELECT * FROM emp as t1 INNER JOIN leave_requests as t2 ON t1.emp_uid=t2.emp_uid INNER JOIN leaves_types as t3 ON t2.leaves_types_uid=t3.leaves_types_uid INNER JOIN users as t4 ON t1.emp_uid = t4.emp_uid WHERE t2.leave_request_status = 'Pending' AND t2.status=1 ORDER BY t1.id DESC")->findMany();
    return $query;
}

function getLeaveRequestsByDate($startDate, $endDate, $reqStatus){
    $query = ORM::forTable("leave_requests")
        ->rawQuery("SELECT * FROM leave_requests as t1 INNER JOIN emp as t2 ON t1.emp_uid=t2.emp_uid INNER JOIN leaves_types as t3 ON t1.leaves_types_uid=t3.leaves_types_uid INNER JOIN users as t4 ON t1.emp_uid = t4.emp_uid WHERE (t1.start_date BETWEEN :start AND :end) AND (t1.end_date BETWEEN :start AND :end) AND leave_request_status = :status AND t1.status=1 ORDER BY t2.id DESC", array("start" => $startDate, "end" => $endDate, "status" => $reqStatus))->findMany();
    return $query;
}

function getLeaveRequestsByEmpUidAndDate($startDate, $endDate, $emp){
    $query = ORM::forTable("leave_requests")
        ->rawQuery("SELECT * FROM emp as t1 INNER JOIN leave_requests as t2 ON t1.emp_uid=t2.emp_uid INNER JOIN leaves_types as t3 ON t2.leaves_types_uid=t3.leaves_types_uid INNER JOIN users as t4 ON t1.emp_uid = t4.emp_uid WHERE (t2.start_date BETWEEN :start AND :end) AND (t2.end_date BETWEEN :start AND :end) AND t2.emp_uid = :emp AND t2.status=1 ORDER BY t2.id DESC", array("start" => $startDate, "end" => $endDate, "emp" => $emp))->findMany();
    return $query;
}

function getLeaveRequestsByLUid($uid){
    $query = ORM::forTable("leave_requests")
        ->rawQuery("SELECT * FROM emp as t1 INNER JOIN leave_requests as t2 ON t1.emp_uid=t2.emp_uid INNER JOIN leaves_types as t3 ON t2.leaves_types_uid=t3.leaves_types_uid WHERE t2.leave_uid = :uid", array("uid" => $uid))
        ->findOne();
    return $query;
}

function getLeavesDataByEmpUid($empUid){
    $query = ORM::forTable("leave_requests")
        ->rawQuery("SELECT * FROM emp as t1 INNER JOIN leave_requests as t2 ON t1.emp_uid=t2.emp_uid INNER JOIN leaves_types as t3 ON t2.leaves_types_uid=t3.leaves_types_uid WHERE t2.emp_uid = :uid", array("uid" => $empUid))
        ->findOne();
    return $query;
}

function getLeaveByEmpUid($id){
    $query = ORM::forTable("leave_requests")->tableAlias("t1")->innerJoin("leaves_types", array("t1.leaves_types_uid", "=", "t2.leaves_types_uid"), "t2")->where("t1.emp_uid", $id)->where("t1.leave_request_status", "Approved")->where("status", 1)->findMany();
    return $query;
}

function getLeaveByEmpUidAndDate($id, $date){
    $query = ORM::forTable("leave_requests")
        ->tableAlias("t1")
        ->innerJoin("leaves_types", array("t1.leaves_types_uid", "=", "t2.leaves_types_uid"), "t2")
        ->where("t1.emp_uid", $id)
        ->whereLte("t1.start_date", $date)
        ->whereGte("t1.end_date", $date)
        ->whereNotEqual("t2.leave_code", "AB")
        ->where("t1.leave_request_status", "Approved")
        ->where("t1.status", 1)
        ->findOne();
    return $query;
}

function editOtherTableLeave($empNumber, $leaveStartDate, $leaveEndDate, $leaveReason, $leaveRequestStatus, $leaveStart, $leaveEnd, $leaveStatus, $user1, $user2){
    $query = ORM::forTable("overtime_leave")
        ->where("user_id", $empNumber)
        ->where("sdate", $leaveStartDate)
        ->where("fromdate", $leaveStartDate)
        ->where("todate", $leaveEndDate)
        ->where("reason", $leaveReason)
        ->where("status", $leaveRequestStatus)
        ->findOne();

    if($leaveRequestStatus == "Certified"){
        $query->set("sdate", $leaveStart);
        $query->set("fromdate", $leaveStart);
        $query->set("todate", $leaveEnd);
        $query->set("cert_by", $user2);
        $query->set("status", $leaveStatus);
    }else if($leaveRequestStatus == "Approved"){
        $query->set("sdate", $leaveStart);
        $query->set("fromdate", $leaveStart);
        $query->set("todate", $leaveEnd);
        $query->set("app_by", $user1);
        $query->set("status", $leaveStatus);
    }else{
        $query->set("sdate", $leaveStart);
        $query->set("fromdate", $leaveStart);
        $query->set("todate", $leaveEnd);
        $query->set("app_by", $user1);
        $query->set("cert_by", $user2);
        $query->set("status", $leaveStatus);
    }
    
    
    $query->save();
}

function newEntitlementNew($entitlementUid, $employee, $leaveType, $leavePeriod, $entitlement, $dateCreated, $dateModified){
    $query = ORM::forTable("leave_entitlement")->create();
        $query->leave_entitlement_uid = $entitlementUid;
        $query->emp_uid = $employee;
        $query->leaves_types_uid = $leaveType;
        $query->leave_period_uid = $leavePeriod;
        $query->no_days = $entitlement;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}
/*------------------------------  Absent-----------------------------*/
function getAbsentRequest(){
    $query = ORM::forTable("overtime_requests")->tableAlias("t1")->innerJoin("emp", array("t1.emp_uid", "=", "t2.emp_uid"), "t2")->innerJoin("leaves_types", array("t1.type", "=", "t3.leaves_types_uid"), "t3")->where("t3.leave_code", "AB")->where("t1.status", 1)->orderByDesc("t1.date_modified")->findMany();
    return $query;
}

function getAbsentRequestByEmpUid($uid){
    $query = ORM::forTable("overtime_requests")->tableAlias("t1")->innerJoin("emp", array("t1.emp_uid", "=", "t2.emp_uid"), "t2")->innerJoin("leaves_types", array("t1.type", "=", "t3.leaves_types_uid"), "t3")->where("t1.emp_uid", $uid)->where("t3.leave_code", "AB")->where("t1.status", 1)->findMany();
    return $query;
}

function getAbsentRequestByDateAndEmpUid($uid, $date){
    $query = ORM::forTable("leave_requests")->tableAlias("t1")->innerJoin("emp", array("t1.emp_uid", "=", "t2.emp_uid"), "t2")->innerJoin("leaves_types", array("t1.leaves_types_uid", "=", "t3.leaves_types_uid"), "t3")->where("t3.leave_code", "AB")->where("t1.emp_uid", $uid)->where("t1.start_date", $date)->where("t1.status", 1)->findOne();
    return $query;
}
/*------------------------------  Absent End-----------------------------*/

/*------------------------------  Overtime Notification-----------------------------*/
function addOvertimeRequestsNotification($overtimeNotifUid, $overtimeRequestUid, $dateCreated, $dateModified){
    $query = ORM::forTable("overtime_notification")->create();
        $query->overtime_notification_uid = $overtimeNotifUid;
        $query->overtime_request_uid = $overtimeRequestUid;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function checkOvertimeDateByEmpUid($emp, $date){
    $query = ORM::forTable("overtime_requests")
        ->rawQuery("SELECT count(emp_uid) as count FROM overtime_requests WHERE emp_uid = :emp AND date(start_date) = :dates AND status = 1", array("emp" => $emp, "dates" => $date))->findOne();
    return $query->count;
}

function getOvertimeRequestsNotification(){
    $query = ORM::forTable("overtime_notification")->where("request_status", "Pending")->where("status", 1)->count();
    return $query;
}

function getAbsentRequestsNotification(){
    $query = ORM::forTable("overtime_notification")->tableAlias("t1")->innerJoin("overtime_requests", array("t1.overtime_request_uid", "=", "t2.overtime_request_uid"), "t2")->innerJoin("leaves_types", array("t2.type", "=", "t3.leaves_types_uid"), "t3")->where("t1.request_status", "Pending")->where("t3.leave_code", "AB")->where("t1.status", 1)->count();
    return $query;
}

function editOvertimeRequestsNotification(){
    $query = ORM::forTable("overtime_notification")->where("status", 1)->where("request_status", "Pending")->findOne();
        $query->set("status", 0);
    $query->save();
}

function countPendingRequestsOfOvertime(){
    $query = ORM::forTable("overtime_requests")->tableAlias("t1")->innerJoin("overtime_type", array("t1.type", "=", "t2.overtime_type_uid"), "t2")->where("t1.overtime_request_status", "Pending")->whereNotEqual("t2.overtime_type_code", "AB")->where("t1.status", 1)->count();
    return $query;
}

function countPendingRequestsOfAbsent(){
    $query = ORM::forTable("overtime_requests")->tableAlias("t1")->innerJoin("leaves_types", array("t1.type", "=", "t2.leaves_types_uid"), "t2")->where("t1.overtime_request_status", "Pending")->where("t2.leave_code", "AB")->where("t1.status", 1)->count();
    return $query;
}

function countAcceptedRequestsOfOvertime(){
    $query = ORM::forTable("overtime_requests")->tableAlias("t1")->innerJoin("overtime_type", array("t1.type", "=", "t2.overtime_type_uid"), "t2")->where("t1.overtime_request_status", "Approved")->whereNotEqual("t2.overtime_type_code", "AB")->where("t1.status", 1)->count();
    return $query;
}

function countAcceptedRequestsOfAbsent(){
    $query = ORM::forTable("overtime_requests")->tableAlias("t1")->innerJoin("leaves_types", array("t1.type", "=", "t2.leaves_types_uid"), "t2")->where("t1.overtime_request_status", "Approved")->where("t2.leave_code", "AB")->where("t1.status", 1)->count();
    return $query;
}
function countPendingRequestsOfOvertimeDate($startDate, $endDate){
    $query = ORM::forTable("overtime_requests")
        ->rawQuery("SELECT COUNT(id) as count FROM overtime_requests WHERE overtime_request_status = 'Pending' AND date(start_date) BETWEEN :start AND :end AND status = 1", array("start" => $startDate, "end" => $endDate))
        ->findOne();
    return $query->count;
}

function countAcceptedRequestsOfOvertimeDate($startDate, $endDate){
    $query = ORM::forTable("overtime_requests")
        ->rawQuery("SELECT COUNT(id) as count FROM overtime_requests WHERE overtime_request_status = 'Approved' AND start_date BETWEEN :start AND :end AND status = 1", array("start" => $startDate, "end" => $endDate))
        ->findOne();
    return $query->count;
}

function countAcceptedRequestsByEmpUid($uid){
    $query = ORM::forTable("overtime_notification")
        ->rawQuery("SELECT COUNT(t1.overtime_request_uid) AS count FROM  overtime_notification AS t1 INNER JOIN overtime_requests AS t2 ON t1.overtime_request_uid = t2.overtime_request_uid WHERE t2.emp_uid =  :uid AND t1.status = 1 AND  request_status =  'Approved'", array("uid" => $uid))
    ->findOne();
    return $query->count;
}
function getOtNotifUidByEmpUid($uid){
    $query = ORM::forTable("overtime_notification")->tableAlias("t1")->innerJoin("overtime_requests", array("t1.overtime_request_uid", "=", "t2.overtime_request_uid"), "t2")->where("t2.emp_uid", $uid)->where("t1.request_status", "Approved")->where("t1.status", 1)->where("t2.status", 1)->findMany();
    return $query;
}
function editOTNotificationByUid($uid, $dateModified){
    $query = ORM::forTable("overtime_notification")->where("overtime_request_uid", $uid)->findOne();
        $query->set("status", 0);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function countPendingNotifByEmpUid($uid){
    $query = ORM::forTable("overtime_requests")->where("emp_uid", $uid)->where("overtime_request_status", "Pending")->where("status", 1)->count();
    return $query;
}

function editOTNotification($uid, $reqStatus, $dateModified, $status){
    
    $query = ORM::forTable("overtime_notification")->where("overtime_request_uid", $uid)->findOne();
        $query->set("request_status", $reqStatus);
        $query->set("date_modified", $dateModified);
        $query->set("status", 1);
    $query->save();
}
/*------------------------------ End of Overtime Notification-----------------------------*/

/*------------------------------ Overtime Types-----------------------------*/
function addOvertimeType($uid, $kind, $name, $code, $rate, $rateAd, $dateCreated, $dateModified){
    $query = ORM::forTable("overtime_type")->create();
        $query->overtime_type_uid = $uid;
        $query->overtime_kind = $kind;
        $query->overtime_type_name = $name;
        $query->overtime_type_code = $code;
        $query->rate = $rate;
        $query->additional_rate = $rateAd;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getOvertimeTypeByUid($uid){
    $query = ORM::forTable("overtime_type")->where("overtime_type_uid", $uid)->findOne();
    return $query;
}

function editOvertimeType($uid, $kind, $name, $code, $rate, $rateAd, $dateModified, $status){
    $query = ORM::forTable("overtime_type")->where("overtime_type_uid", $uid)->findOne();
        $query->set("overtime_kind", $kind);
        $query->set("overtime_type_name", $name);
        $query->set("overtime_type_code", $code);
        $query->set("rate", $rate);
        $query->set("additional_rate", $rateAd);
        $query->set("date_created", $dateModified);
        $query->set("status", $status);
    $query->save();
}

/*------------------------------ END of Overtime Types-----------------------------*/


/*------------------------------ Leave Notification-----------------------------*/
function getLeaveRequestsNotification(){
    $query = ORM::forTable("leave_notification")->where("request_status", "Pending")->where("status", 1)->count();
    return $query;
}

function editLeaveRequestsNotification(){
    $query = ORM::forTable("leave_notification")->where("status", 1)->where("request_status", "Pending")->findOne();
        $query->set("status", "0");
    $query->save();
}

function addLeaveNotification($leaveNotifUid, $leaveUid, $requestStatus, $dateCreated, $dateModified){
    $query = ORM::forTable("leave_notification")->create();
        $query->leave_notification_uid = $leaveNotifUid;
        $query->leave_request_uid = $leaveUid;
        $query->request_status = $requestStatus;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function countPendingRequestsOfLeave(){
    $query = ORM::forTable("leave_requests")->where("leave_request_status", "Pending")->where("status", 1)->count();
    return $query;
}

function countAcceptedRequestsOfLeave(){
    $query = ORM::forTable("leave_requests")->where("leave_request_status", "Approved")->where("status", 1)->count();
    return $query;
}

function countPendingRequestsOfLeaveByDate($startDate, $endDate){
    $query = ORM::forTable("leave_requests")
        ->rawQuery("SELECT COUNT(leave_uid) AS count FROM leave_requests WHERE (start_date BETWEEN :start AND :end) AND (end_date BETWEEN :start AND :end) AND leave_request_status = 'Pending' AND status = 1", array("start" => $startDate, "end" => $endDate))
        ->findOne();
    return $query->count;
}

function countAcceptedRequestsOfLeaveByDate($startDate, $endDate){
    $query = ORM::forTable("leave_requests")
        ->rawQuery("SELECT COUNT(leave_uid) AS count FROM leave_requests WHERE (start_date BETWEEN :start AND :end) AND (end_date BETWEEN :start AND :end) AND leave_request_status = 'Approved' AND status = 1", array("start" => $startDate, "end" => $endDate))
        ->findOne();
    return $query->count;
}

function countAcceptedLeaveRequestsByEmpUid($uid){
    $query = ORM::forTable("leave_notification")
        ->rawQuery("SELECT COUNT(t1.leave_request_uid) AS count FROM leave_notification AS t1 INNER JOIN leave_requests AS t2 ON t1.leave_request_uid = t2.leave_uid WHERE t2.emp_uid = :uid AND t1.request_status =  'Approved' AND t1.status = 1", array("uid" => $uid))
        ->findOne();
    return $query->count;
}

function getApprovedLeavesByEmpUidByYear($uid, $year){
    $query = ORM::forTable("leave_requests")
    ->rawQuery("SELECT * FROM leave_requests as t1 INNER JOIN leaves_types as t2 ON t1.leaves_types_uid = t2.leaves_types_uid WHERE t1.emp_uid = :uid AND t1.leave_request_status = 'Approved' AND t1.date_created LIKE CONCAT('%', :dates, '%') AND t1.status = 1", array("uid" => $uid, "dates" => $year))->findMany();
    return $query;
}

function editLeaveNotificationByUid($uid, $leaveStatus ,$dateModified, $notifStatus){
    $query = ORM::forTable("leave_notification")->where("leave_request_uid", $uid)->findOne();
        $query->set("request_status", $leaveStatus);
        $query->set("date_modified", $dateModified);
        $query->set("status", $notifStatus);
    $query->save();
}

function getLeaveNotifUidByEmpUid($uid){
    $query = ORM::forTable("leave_notification")->tableAlias("t1")->innerJoin("leave_requests", array("t1.leave_request_uid", "=", "t2.leave_uid"), "t2")->where("t2.emp_uid", $uid)->where("t1.request_status", "Approved")->where("t2.status", 1)->where("t1.status", 1)->findMany();
    return $query;
}

function editLeaveNotificationByLeaveUid($uid, $dateModified){
    $query = ORM::forTable("leave_notification")->where("leave_request_uid", $uid)->findOne();
        $query->set("date_modified", $dateModified);
        $query->set("status", 0);
    $query->save();
}

function editLeaveNotificationByEmpUid($uid, $dateModified){
    $query = ORM::forTable("leave_notification")->tableAlias("t1")->innerJoin("leave_requests", array("t1.leave_request_uid", "=", "t2.leave_uid"), "t2")->where("t2.emp_uid", $uid)->where("t1.status", 1)->where("t1.request_status", "Approved")->findOne();
        $query->set("date_modified", $dateModified);
        $query->set("status", 0);
    $query->save();
}

function countPendingLeaveNotifByEmpUid($uid){
    $query = ORM::forTable("leave_requests")->where("emp_uid", $uid)->where("status", 1)->where("leave_request_status", "Pending")->count();
    return $query;
}
/*------------------------------ End of Leave Notification-----------------------------*/
/*------------------------------  Overtime-----------------------------*/
function getOvertimeRequest(){
    $query = ORM::forTable("overtime_requests")->tableAlias("t1")->innerJoin("emp", array("t1.emp_uid", "=", "t2.emp_uid"), "t2")->innerJoin("overtime_type", array("t1.type", "=", "t3.overtime_type_uid"), "t3")->innerJoin("users", array("t1.emp_uid", "=", "t4.emp_uid"), "t4")->where("t1.status", 1)->orderByDesc("t1.date_modified")->findMany();
    return $query;
}


function addOvertimeRequest($overtimeRequestUid, $type ,$employee, $startDate, $endDate , $hours, $reason ,$requestStatus, $dateCreated, $dateModified){
    $query = ORM::forTable("overtime_requests")->create();
        $query->overtime_request_uid = $overtimeRequestUid;
        $query->type = $type;
        $query->emp_uid = $employee;
        $query->start_date = $startDate;
        $query->end_date = $endDate;
        $query->hours = $hours;
        $query->reason = $reason;
        $query->overtime_request_status = $requestStatus;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function deleteOvertimeByUid($uid){
    $query = ORM::forTable("overtime_requests")->where("overtime_request_uid", $uid)->findOne();
        $query->set("status", 0);
    $query->save();
}

function editOvertimeRequest($uid, $type,$startDate, $endDate, $reason, $hours ,$requestStatus, $user1, $user2 ,$dateModified, $status){
    $query = ORM::forTable("overtime_requests")->where("overtime_request_uid", $uid)->findOne();
    
    if($requestStatus == "Certified"){
        $query->set("type", $type);
        $query->set("start_date", $startDate);
        $query->set("end_date", $endDate);
        $query->set("reason", $reason);
        $query->set("hours", $hours);
        $query->set("overtime_request_status", $requestStatus);
        $query->set("cert_by", $user1);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    }else if($requestStatus == "Approved"){
        $query->set("type", $type);
        $query->set("start_date", $startDate);
        $query->set("end_date", $endDate);
        $query->set("reason", $reason);
        $query->set("hours", $hours);
        $query->set("overtime_request_status", $requestStatus);
        $query->set("appr_by", $user2);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    }else{
        $query->set("type", $type);
        $query->set("start_date", $startDate);
        $query->set("end_date", $endDate);
        $query->set("reason", $reason);
        $query->set("hours", $hours);
        $query->set("overtime_request_status", $requestStatus);
        $query->set("cert_by", $user1);
        $query->set("appr_by", $user2);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    }
        
    $query->save();
}

function getOvertimeRequestByEmpUid($empUid){
    $query = ORM::forTable("overtime_requests")->where("emp_uid", $empUid)->where("overtime_request_status", "Approved")->where("status", "1")->findResultSet();
    return $query;
}

function getOvertimeByDate($date){
    $query = ORM::forTable("overtime_requests")
        ->rawQuery("SELECT * FROM overtime_requests WHERE start_date = :dates AND status = 1 AND overtime_request_status = 'Approved'", array("dates" => $date))
        ->findOne();
    return $query;
}

function getOvertimeRequestByDateAndEmpUid($date, $id){
    $query = ORM::forTable("overtime_requests")
        ->rawQuery("SELECT * FROM overtime_requests as t1 INNER JOIN overtime_type as t2 ON t1.type = t2.overtime_type_uid WHERE date(t1.start_date) = :dates AND t1.emp_uid = :emp AND t1.status = 1 AND t1.overtime_request_status = 'Approved'", array("dates" => $date, "emp" => $id))
        ->findOne();
    return $query;
}

function getOvertimeRequestByEmpUidAndDate($id, $date, $holidayDate){
    $query = ORM::forTable("overtime_requests")
        ->rawQuery("SELECT * FROM overtime_requests as t1 INNER JOIN overtime_type as t2 ON t1.type=t2.overtime_type_uid WHERE t1.emp_uid = :id AND date(t1.start_date) = :dates AND t1.overtime_request_status = 'Approved' AND t1.status = 1", array("id" => $id, "dates" => $date))
        ->findOne();
    return $query;
}

function getOvertimeRequestsByDatesAndEmpUid($id, $start, $end){
    $query = ORM::forTable("overtime_requests")
        ->rawQuery("SELECT * FROM overtime_requests as t1 INNER JOIN overtime_types as t2 ON t1.type=t2.overtime_type_uid WHERE t1.emp_uid = :id AND date(t1.start_date) BETWEEN :starts AND :ends AND date(t1.end_date) BETWEEN :starts AND :ends AND t2.overtime_type_code = 'RegOT' AND t1.overtime_request_status = 'Approved' AND t1.status = '1'", array("id" => $id, "starts" => $start, "ends" => $end))
        ->findOne();
    return $query;
}

function getOvertimeRequestsByEmpUidAndDates($startDate, $endDate, $emp){
    $query = ORM::forTable("overtime_requests")
        ->rawQuery("SELECT * FROM overtime_requests as t1 INNER JOIN overtime_type as t2 ON t1.type=t2.overtime_type_uid INNER JOIN emp as t3 ON t1.emp_uid = t3.emp_uid INNER JOIN users as t4 ON t1.emp_uid = t4.emp_uid WHERE date(t1.start_date) BETWEEN :starts AND :ends AND date(t1.start_date) BETWEEN :starts AND :ends AND t1.emp_uid = :emp AND t1.status = '1'", array("starts" => $startDate, "ends" => $endDate, "emp" => $emp))
        ->findMany();
    return $query;
}

function getOvertimeRequestsByDates($startDate, $endDate, $reqStatus){
    $query = ORM::forTable("overtime_requests")
        ->rawQuery("SELECT * FROM overtime_requests as t1 INNER JOIN overtime_type as t2 ON t1.type=t2.overtime_type_uid INNER JOIN emp as t3 ON t1.emp_uid = t3.emp_uid INNER JOIN users as t4 ON t1.emp_uid = t4.emp_uid WHERE date(t1.start_date) BETWEEN :starts AND :ends AND date(t1.start_date) BETWEEN :starts AND :ends AND overtime_request_status = :status AND t1.status = '1'", array("starts" => $startDate, "ends" => $endDate, "status" => $reqStatus))
        ->findMany();
    return $query;
}

function getOvertimeRequestsAndTypeByDatesAndEmpUid($id, $start, $end){
    $query = ORM::forTable("overtime_requests")
        ->rawQuery("SELECT * FROM overtime_requests as t1 INNER JOIN overtime_types as t2 ON t1.type=t2.overtime_type_uid WHERE t1.emp_uid = :id AND t1.start_date BETWEEN :starts AND :ends AND t1.end_date BETWEEN :starts AND :ends AND t2.overtime_type_code != 'RegOT' AND t1.overtime_request_status = 'Approved' AND t1.status = '1'", array("id" => $id, "starts" => $start, "ends" => $end))
        ->findOne();
    return $query;
}

function getOvertimeTypes(){
    $query = ORM::forTable("overtime_type")->where("status", 1)->findMany();
    return $query;
}

function countOvertimeTypes(){
    $query = ORM::forTable("overtime_type")->where("status", 1)->count();
    return $query;
}

function getOvertimeTypesByCode($code){
    $query = ORM::forTable("overtime_type")->where("overtime_type_code", $code)->where("status", 1)->findOne();
    return $query;
}

/*------------------------------  Overtime end-----------------------------*/


/*------------------------------  Working Experince end-----------------------------*/

function getWorkExperienceByUid($empUid){
    $query = ORM::forTable("hris_work_experience")->where("emp_uid", $empUid)->where("status", 1)->orderByAsc("we_to")->findMany();
        return $query;
}

function getEducationByUid($empUid){
    $query = ORM::forTable("education")->tableAlias("e")->innerJoin("education_level", array("e.education_level_uid", "=", "el.education_level_uid"), "el")->where("e.emp_uid", $empUid)->where("e.status", 1)->orderByDesc("e.end_date")->findMany();
        return $query;
}

function getSkillByUid($empUid){
    $query = ORM::forTable("hris_skill")->tableAlias("hs")->innerJoin("skill", array("hs.skill_uid", "=", "s.skill_uid"), "s")->where("hs.emp_uid", $empUid)->where("hs.status", 1)->orderByAsc("s.skill_type")->findMany();
        return $query;
}

function getLanguagesByUid($empUid){
    $query = ORM::forTable("hris_languages")->tableAlias("el")->innerJoin("languages", array("el.languages_uid", "=", "l.languages_uid"), "l")->where("el.emp_uid", $empUid)->where("el.status", 1)->findMany();
        return $query;
}

function getLicenseByUid($empUid){
    $query = ORM::forTable("hris_license")->tableAlias("hl")->innerJoin("license", array("hl.license_uid", "=", "l.license_uid"), "l")->where("hl.emp_uid", $empUid)->where("hl.status", 1)->findMany();
        return $query;
}

function newEducation($educationUid , $empUid , $levelDegree , $school , $year , $major , $score , $startDate , $endDate , $dateCreated , $dateModified){
    $query = ORM::forTable("education")->create();
        $query->education_uid = $educationUid;
        $query->emp_uid = $empUid;
        $query->education_level_uid = $levelDegree;
        $query->school = $school;
        $query->major = $major;
        $query->year = $year;
        $query->score = $score;
        $query->start_date = $startDate;
        $query->end_date = $endDate;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getEducationLevel() {
    $query = ORM::forTable("education_level")->where("status", 1)->orderByAsc("level_name")->findMany();
        return $query;
}

function getEducationByEducationUid($educationUid) {
    $query = ORM::forTable("education")->tableAlias("e")->innerJoin("education_level", array("e.education_level_uid", "=", "el.education_level_uid"), "el")->where("e.education_uid", $educationUid)->findMany();
        return $query;
}

function updateEducation($levelDegree , $school , $year , $major , $score , $startDate , $endDate , $status , $dateModified , $educationUid) {
    $query = ORM::forTable("education")->where("education_uid", $educationUid)->findOne();
        $query->set("education_level_uid", $levelDegree);
        $query->set("school", $school);
        $query->set("year", $year);
        $query->set("major", $major);
        $query->set("score", $score);
        $query->set("start_date", $startDate);
        $query->set("end_date", $endDate);
        $query->set("status", $status);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function getSkillType() {
    $query = ORM::forTable("skill")->where("status", 1)->orderByAsc("skill_type")->findMany();
        return $query;
}

function newHrisSkill($hrisSkillUid , $empUid , $skillType , $yearsExperience , $dateCreated , $dateModified){
    $query = ORM::forTable("hris_skill")->create();
        $query->hris_skill_uid = $hrisSkillUid;
        $query->emp_uid = $empUid;
        $query->skill_uid = $skillType;
        $query->years_experience = $yearsExperience;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getSkillBySkillUid($hrisSkillUid) {
    $query = ORM::forTable("hris_skill")->tableAlias("hs")->innerJoin("skill", array("hs.skill_uid", "=", "s.skill_uid"), "s")->where("hs.hris_skill_uid", $hrisSkillUid)->find_result_set();
        return $query;
}

function getLanguages() {
    $query = ORM::forTable("languages")->where("status", 1)->orderByAsc("language_name")->findMany();
        return $query;
}

function newLanguagesSpoken($empLanguagesUid , $empUid , $languageName , $fluency , $competency , $dateCreated , $dateModified){
    $query = ORM::forTable("hris_languages")->create();
        $query->emp_languages_uid = $empLanguagesUid;
        $query->emp_uid = $empUid;
        $query->languages_uid = $languageName;
        $query->fluency = $fluency;
        $query->competency = $competency;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getLanguageBylanguagesUid($languagesUid){
    $query = ORM::forTable("hris_languages")->tableAlias("el")->innerJoin("languages", array("el.languages_uid", "=", "l.languages_uid"), "l")->where("el.emp_languages_uid", $languagesUid)->where("el.status", 1)->find_result_set();
        return $query;
}

function updateLanguages($languageName , $fluency , $competency , $status , $dateModified , $languagesUid) {
    $query = ORM::forTable("hris_languages")->where("emp_languages_uid", $languagesUid)->findOne();
        $query->set("languages_uid", $languageName);
        $query->set("fluency", $fluency);
        $query->set("competency", $competency);
        $query->set("status", $status);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function getLicenseType() {
    $query = ORM::forTable("license")->where("status", 1)->orderByAsc("license_name")->find_result_set();
        return $query;
}

function newHrisLicense($hrisLicenseUid , $empUid , $licenseType , $licenseNo , $licenseIssued , $licenseExpiry , $dateCreated , $dateModified){
    $query = ORM::forTable("hris_license")->create();
        $query->hris_license_uid = $hrisLicenseUid;
        $query->emp_uid = $empUid;
        $query->license_uid = $licenseType;
        $query->license_no = $licenseNo;
        $query->issued_date = $licenseIssued;
        $query->expiry_date = $licenseExpiry;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getLicenseByLicenseUid($licenseUid){
    $query = ORM::forTable("hris_license")->tableAlias("el")->innerJoin("license", array("el.license_uid", "=", "l.license_uid"), "l")->where("el.hris_license_uid", $licenseUid)->where("el.status", 1)->find_result_set();
        return $query;
}

function updateLicense($licenseUid , $licenseType , $licenseNo , $licenseIssued , $licenseExpiry , $status , $dateModified) {
    $query = ORM::forTable("hris_license")->where("hris_license_uid", $licenseUid)->findOne();
        $query->set("license_uid", $licenseType);
        $query->set("license_no", $licenseNo);
        $query->set("issued_date", $licenseIssued);
        $query->set("expiry_date", $licenseExpiry);
        $query->set("status", $status);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function updateSkill($skillUid , $skillType , $yearsExperience , $status , $dateModified) {
    $query = ORM::forTable("hris_skill")->where("hris_skill_uid", $skillUid)->findOne();
        $query->set("skill_uid", $skillType);
        $query->set("years_experience", $yearsExperience);
        $query->set("status", $status);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function getSalaryByUid($empUid){
    $query = ORM::forTable("salary")->tableAlias("s")
    ->innerJoin("pay_period", array("s.pay_period_uid", "=", "pp.pay_period_uid"), "pp")
    ->where("s.emp_uid", $empUid)->where("s.status", "1")
    ->findOne();
        return $query;
}

function getSalary(){
    $query = ORM::forTable("salary")->tableAlias("t1")->innerJoin("pay_period", array("t1.pay_period_uid", "=", "t2.pay_period_uid"), "t2")->innerJoin("emp", array("t1.emp_uid", "=", "t3.emp_uid"), "t3")->where("t1.status", 1)->findMany();

    return $query;
}

function getWorkDay(){
    $query = ORM::forTable("work_day")->findOne();

    return $query;
}

function getPayGrade() {
    $query = ORM::forTable("paygrade")->where("status", "1")->orderByAsc("paygrade_name")->findMany();
        return $query;
}

function getCurrencies() {
    $query = ORM::forTable("currency")->where("status", "1")->orderByAsc("name")->findMany();
        return $query;
}

function getAdjustment(){
    $query = ORM::forTable("adjustment")->tableAlias("t1")->innerJoin("emp", array("t1.emp_uid", "=", "t2.emp_uid"), "t2")->where("status", 1)->findMany();
    return $query;
}

function getAdjustmentByEmpUid($uid, $start, $end){
    $query = ORM::forTable("adjustment")
        ->where("emp_uid", $uid)
        ->whereGte("payroll_date", $start)
        ->whereLte("payroll_date", $end)
        ->where("status", 1)
        ->findOne();
    return $query;
}

function checkAdjustment($uid){
    $query = ORM::forTable("adjustment")->selectExpr("COUNT(adjustment_uid)", "count")->where("adjustment_uid", $uid)->where("status", 1)->findOne();
    return $query->count;
}

function addAdjustment($adjUid, $adjEmp, $adjAmount, $adjDate, $dateCreated, $dateModified){
    $query = ORM::forTable("adjustment")->create();
        $query->adjustment_uid = $adjUid;
        $query->emp_uid = $adjEmp;
        $query->amount = $adjAmount;
        $query->payroll_date = $adjDate;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getAdjustmentByUid($uid){
    $query = ORM::forTable("adjustment")->tableAlias("t1")->innerJoin("emp", array("t1.emp_uid", "=", "t2.emp_uid"), "t2")->where("t1.adjustment_uid", $uid)->findOne();
    return $query;
}

function updateAdjustment($adjUid, $amount, $date, $dateModified, $status){
    $query = ORM::forTable("adjustment")->where("adjustment_uid", $adjUid)->findOne();
        $query->set("amount", $amount);
        $query->set("payroll_date", $date);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function getCurrencyByUid($uid) {
    $query = ORM::forTable("currency")->where("currency_uid", $uid)->findOne();
        return $query;
}

function updateCurrencyByUid($uid , $name , $dateModified , $status){
    $query = ORM::forTable("currency")->where("currency_uid", $uid)->findOne();
        $query->set("name", $name);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function currencyCount($name){
    $query = ORM::forTable("currency")->where("name", $name)->count();
    return $query;
}
function getFrequencies() {
    $query = ORM::forTable("pay_period")->where("status", "1")->orderByAsc("pay_period_name")->findMany();
        return $query;
}
/*---------------------------------------HOLIDAY------------------------------------------------------*/
function getHoliday(){
    $query = ORM::forTable("holiday")->where("status", "1")->findMany();
        return $query;
}

function getHolidayType(){
    $query = ORM::forTable("holiday_types")->where("status", 1)->orderByAsc("holiday_name_type")->findMany();
        return $query;
}

function getHolidayByUid($uid){
    $query = ORM::forTable("holiday")->tableAlias("t1")->innerJoin("holiday_types", array("t1.type", "=", "t2.holiday_type_uid"), "t2")->where("t1.holiday_uid", $uid)->findOne();
    return $query;
}

function getHolidayByDate($date){
    $query = ORM::forTable("holiday")->where("date", $date)->where("status", 1)->findOne();
        return $query;
}   

function getHolidayTypeUidByCode($code){
    $query = ORM::forTable("holiday_types")->where("holiday_code", $code)->where("status", 1)->findOne();
    return $query->holiday_type_uid;
}

function getHolidayAndTypeByDate($date){
    $query = ORM::forTable("holiday")->tableAlias("t1")->innerJoin("holiday_types", array("t1.type", "=", "t2.holiday_type_uid"), "t2")->where("t1.date", $date)->where("t1.status", 1)->findOne();
    return $query;
}

function getHolidayAndType(){
    $query = ORM::forTable("holiday")->tableAlias("t1")->innerJoin("holiday_types", array("t1.type", "=", "t2.holiday_type_uid"), "t2")->where("t1.status", 1)->findMany();
    return $query;
}

function getHolidayAndTypeByDates($startDate, $endDate){
    $query = ORM::forTable("holiday")
        ->rawQuery("SELECT * FROM holiday as t1 INNER JOIN holiday_types as t2 ON t1.type=t2.holiday_type_uid WHERE t1.date >= :startDate AND t1.date <= :endDate", array("startDate" => $startDate, "endDate" => $endDate))->findMany();
    return $query;
}

function addHoliday($holidayUid, $name, $type, $date, $dateCreated, $dateModified){
    $query = ORM::forTable("holiday")->create();
        $query->holiday_uid = $holidayUid;
        $query->name = $name;
        $query->type = $type;
        $query->date = $date;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function updateHoliday($uid, $type, $name, $date, $dateModified, $status){
    $query = ORM::forTable("holiday")->where("holiday_uid", $uid)->findOne();
        $query->set("name", $name);
        $query->set("type", $type);
        $query->set("date", $date);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function checkHolidayDate($date){
    $query = ORM::forTable("holiday")->where("date", $date)->where("status", 1)->findOne();
    $query2 = ORM::forTable("overtime_type")->where("overtime_type_code", "RegOT")->where("status", 1)->findOne();

    if($query){
        $type = $query->type;
    }else{
        $type = $query2->overtime_type_uid;
    }
    return $type;
}
/*---------------------------------------END OF HOLIDAY------------------------------------------------------*/


function newSalary($salaryUid, $empUid, $baseSalary, $payPeriodUid, $dateCreated, $dateModified) {
    $query = ORM::forTable("salary")->create();
        $query->salary_uid = $salaryUid;
        $query->emp_uid = $empUid;
        $query->base_salary = $baseSalary;
        $query->pay_period_uid = $payPeriodUid;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function newEmpType($id, $payPeriodUid, $empUid, $dateCreated, $dateModified){
    $query = ORM::forTable("emp_type")->create();
        $query->type_uid = $id;
        $query->pay_period_uid = $payPeriodUid;
        $query->emp_uid = $empUid;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getSalaryBySalaryUid($salaryUid){
    $query = ORM::forTable("salary")->tableAlias("s")
        ->selectMany("pp.pay_period_name", "s.base_salary", "s.salary_uid", "s.status", "pp.pay_period_uid")
        ->innerJoin("pay_period", array("s.pay_period_uid", "=", "pp.pay_period_uid"), "pp")
        ->where("s.salary_uid", $salaryUid)->findOne();
    return $query;
}

function updateSalary($salaryUid , $dateModified , $baseSalary , $payPeriodUid , $status) {
    $query = ORM::forTable("salary")->where("salary_uid", $salaryUid)->findOne();
        $query->set("base_salary", $baseSalary);
        $query->set("pay_period_uid", $payPeriodUid);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function updateEmpType($uid, $payPeriodUid, $dateModified){
    $query = ORM::forTable("emp_type")->where("emp_uid", $uid);
        $query->set("pay_period_uid", $payPeriodUid);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function getImmigration($empUid) {
    $query = ORM::forTable("emp_immigration")->tableAlias("ei")->select("ei.document_type")->select("ei.passport_no")->select("c.name")->select("ei.issued_date")->select("ei.expiry_date")->select("ei.emp_immigration_uid")->innerJoin("hris_countries", array("ei.country_uid", "=", "c.country_uid"), "c")->where("ei.emp_uid", $empUid)->where("ei.status", "1")->orderByAsc("ei.issued_date")->findMany();
        return $query;
}

function getLateByEmpUid($uid){
    $query = ORM::forTable("late_emp")->tableAlias("t1")->innerJoin("late", array("t1.late_uid", "=", "t2.late_uid"), "t2")->where("t1.emp_uid", $uid)->findMany();
    return $query;
}

function getLatesByEmpUid($uid){
    $query = ORM::forTable("late_emp")->tableAlias("t1")->innerJoin("late", array("t1.late_uid", "=", "t2.late_uid"), "t2")->where("t1.emp_uid", $uid)->findOne();
    return $query;
}

function getEmpLateByEmpUid($lateUid){
    $query = ORM::forTable("late_emp")->tableAlias("t1")->innerJoin("late", array("t1.late_uid", "=", "t2.late_uid"), "t2")->where("t1.late_emp_uid", $lateUid)->findOne();
    return $query;
}

function newEmpLate($lateEmpUid, $empUid, $lateUid, $dateCreated, $dateModified){
    $query = ORM::forTable("late_emp")->create();
        $query->late_emp_uid = $lateEmpUid;
        $query->emp_uid = $empUid;
        $query->late_uid = $lateUid;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getPettyCash($id) {
    $query = ORM::forTable("pettycash")->where("emp_uid", $id)->where("status", "1")->findMany();
        return $query;
}

function getPettyCashByEmpUid($id) {
    $query = ORM::forTable("pettycash")->where("emp_uid", $id)->where("status", "1")->findMany();
        return $query;
}

function newPettyCash($empPettycashUid, $empUid, $amount, $dueDate, $dateCreated, $dateModified){
    $query = ORM::forTable("pettycash")->create();
        $query->pettycash_uid = $empPettycashUid;
        $query->emp_uid = $empUid;
        $query->amount = $amount;
        $query->due_date = $dueDate;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
        $query->status = "1";
    $query->save();
}

function getPettycashByUid($pettycashUid) {
    $query = ORM::forTable("pettycash")->where("pettycash_uid", $pettycashUid)->findOne();
    return $query;
}

function updatePettyCash($pettycashUid , $amount , $dueDate , $status , $dateModified){
    $query = ORM::forTable("pettycash")->where("pettycash_uid", $pettycashUid)->findOne();
        $query->set("amount", $amount);
        $query->set("due_date", $dueDate);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function newImmigration($empImmigrationUid, $documentType, $passportNo, $issuedDate, $expiryDate, $eligibleStatus, $countryUid, $reviewDate, $empUid, $dateCreated, $dateModified){
    $query = ORM::forTable("emp_immigration")->create();
        $query->emp_immigration_uid = $empImmigrationUid;
        $query->document_type = $documentType;
        $query->passport_no = $passportNo;
        $query->issued_date = $issuedDate;
        $query->expiry_date = $expiryDate;
        $query->eligible_status = $eligibleStatus;
        $query->country_uid = $countryUid;
        $query->review_date = $reviewDate;
        $query->emp_uid = $empUid;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getImmigrationByUid($immigratioUid) {
    $query = ORM::forTable("emp_immigration")->tableAlias("ei")->innerJoin("hris_countries", array("ei.country_uid", "=", "c.country_uid"), "c")->where("ei.emp_immigration_uid", $immigratioUid)->orderByAsc("ei.issued_date")->findOne();
    return $query;
}

function updateImmigration($documentType , $passportNo , $issuedDate , $expiryDate , $eligibleStatus , $countryUid , $reviewDate, $status , $dateModified , $empImmigrationUid) {
    $query = ORM::forTable("emp_immigration")->where("emp_immigration_uid", $empImmigrationUid)->findOne();
        $query->set("document_type", $documentType);
        $query->set("passport_no", $passportNo);
        $query->set("issued_date", $issuedDate);
        $query->set("expiry_date", $expiryDate);
        $query->set("eligible_status", $eligibleStatus);
        $query->set("country_uid", $countryUid);
        $query->set("review_date", $reviewDate);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function getAllowance($empUid){
    $query = ORM::forTable("allowance")->where("emp_uid", $empUid)->where("status", "1")->findMany();
    return $query;
}

function newAllowance($empAllowanceUid, $meal, $transpo, $cola, $other, $date, $dateCreated, $dateModified, $empUid){
    $query = ORM::forTable("allowance")->create();
        $query->allowance_uid = $empAllowanceUid;
        $query->emp_uid = $empUid;
        $query->meal = $meal;
        $query->transportation = $transpo;
        $query->COLA = $cola;
        $query->other = $other;
        $query->date_receive = $date;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
        $query->status = "1";
    $query->save();
}

function getAllowanceByUid($allowanceUid){
    $query = ORM::forTable("allowance")->where("allowance_uid", $allowanceUid)->findOne();
    return $query;
}

function updateAllowanceById($id, $meal, $transpo, $cola, $other, $date, $dateModified, $status){
    $query = ORM::forTable("allowance")->where("allowance_uid", $id)->findOne();
        $query->set("meal", $meal);
        $query->set("transportation", $transpo);
        $query->set("COLA", $cola);
        $query->set("other", $other);
        $query->set("date_receive", $date);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();

}
//-------------------------------------------USERS AUTH------------------------------------------------------------//
function getUserId($username) {
    $query = ORM::forTable("users")->where("username", $username)->findOne();
    if($query){
        return $query->users_uid;
    }else{
        return false;
    }
}

function getUserIdByEmpUid($username) {
    $query = ORM::forTable("users")->where("emp_uid", $username)->findOne();
    if($query){
        return $query->users_uid;
    }else{
        return false;
    }
}

function getUserEmpUid($username) {
    $query = ORM::forTable("users")->where("username", $username)->findOne();
    if($query){
        return $query->emp_uid;
    }else{
        return false;
    }
}

function getUserTypeByEmpUid($emp) {
    $query = ORM::forTable("users")->where("emp_uid", $emp)->findOne();
    if($query){
        return $query->type;
    }else{
        return false;
    }
}

function getUniqueKey($userId) {
    $query = ORM::forTable("user_unique_keys")->where("user", $userId)->findOne();
        return $query->unique_key;
}

function validUserAccount($username, $password) {
    $query = ORM::forTable("users")->where("username", $username)->where("password", $password)->where("status", "1")->count();
        $valid = false;
        if($query == 1){
            $valid = true;
        }
        return $valid;
}

function validEmpUserAccount($username, $password) {
    $query = ORM::forTable("users")->where("emp_uid", $username)->where("password", $password)->where("status", "1")->count();
        $valid = false;
        if($query == 1){
            $valid = true;
        }
        return $valid;
}

function getUserType($username) {
    $query = ORM::forTable("users")->where("username", $username)->findOne();
        return $query->type;
}

function getUserByUid($usersUid){
    $query = ORM::forTable("users")->where("users_uid", $usersUid)->findOne();
        return $query;
}

function deactivateUserTokens($userId) {
    $query = ORM::forTable("access_tokens")->where("user", $userId)->where("status", "1")->findOne();
    $query->set("status", "0");
    $query->save();
}

function getCurrentIpAddress($userId) {
    $query = ORM::forTable("access_token_logs")->tableAlias("atl")->select("atl.ip_address")->innerJoin("access_tokens", array("atl.token", "=", "at.token"), "at")->where("at.user", $userId)->where("at.status", "1")->findMany();
        return $query;
}

function logToken($accessTokenUid, $token, $userId, $dateCreated, $status) {
    $query = ORM::forTable("access_tokens")->create();
        $query->access_tokens_uid = $accessTokenUid;
        $query->token = $token;
        $query->user = $userId;
        $query->date_created = $dateCreated;
        $query->status = $status;
    $query->save();
}

function logTokenReferrer($accessTokenLogsUid, $token, $ipAddress, $date) {
    if (!existingTokenLog($token, $ipAddress)) {
        $query = ORM::forTable("access_token_logs")->create();
            $query->access_token_logs_uid = $accessTokenLogsUid;
            $query->token = $token;
            $query->ip_address = $ipAddress;
            $query->date = $date;
        $query->save();
    }
}

function getUserFromToken($token) {
    $query = ORM::forTable("access_tokens")->select("user")->where("token", $token)->findOne();
        return $query->user;
}

function getEmpUidByUserId($user){
    $query = ORM::forTable("users")->where("users_uid", $user)->where("status", 1)->findOne();
    return $query->emp_uid;
}

function validToken($token) {
    $check = ORM::forTable("access_tokens")->select_expr("COUNT(*)", "count")->where("token", $token)->where("status", "1")->findOne();
        $valid = false;
        if ($check->count >= 1 && validTokenOrigin($token)) {
            $valid = true;
        }
        return $valid;
}

function validTokenOrigin($token) {
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $query = ORM::forTable("access_token_logs")->select_expr("COUNT(*)", "count")->where("token", $token)->where("ip_address", $ipAddress)->findOne();
        $valid = false;
        if ($query->count == 1) {
            $valid = true;
        }
        return $valid;
}

function existingTokenLog($token, $ipAddress) {
    $query = ORM::forTable("access_token_logs")->select_expr("COUNT(*)", "count")->where("token", $token)->where("ip_address", $ipAddress)->findOne();
        $existing = false;
        if ($query->count >= 1) {
            $existing = true;
        }
        return $existing;
}

function checkUsername($username){
    $query = ORM::forTable("users")->where("username", $username)->where("status", 1)->count();
    return $query;
}

function updateEmpNumber($uid, $username, $dateModified){
    $query = ORM::forTable("users")->where("emp_uid", $uid)->where("status", 1)->findOne();
        $query->set("username", $username);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function getEmployeeUsernameByEmpUid($uid){
    $query = ORM::forTable("users")->where("emp_uid", $uid)->where("status", 1)->findOne();
    return $query->username;
}
/*-------------------------------------Late Level------------------------------------*/
function getLates(){
    $query = ORM::forTable("grace")->where("status", 1)->findOne();
    return $query;
}

function getGracePeriod(){
    $query = ORM::forTable("grace")->where("status", 1)->findOne();
    return $query->duration;
}

function lateCount($name){
    $query = ORM::forTable("late")->select_expr("count(name)", "count")->where("name", $name)->findOne();
    return $query->count;
}

function newLate($lateUid, $name, $duration, $dateCreated, $dateModified){
    if(lateCount($name) == 0){
        $query = ORM::forTable("late")->create();
            $query->late_uid = $lateUid;
            $query->name = $name;
            $query->duration = $duration;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
        $query->save();
    }else{
        $late = getLateByName($name);
        if($late->status == 0){
            updateLate($late->late_uid, $name, $duration, $dateModified);
        }
    }//end of if-else
}

function getLateByName($name){
    $query = ORM::forTable("late")->where("name", $name)->findOne();
    return $query;
}
function updateLate($lateUid, $name, $duration, $dateModified, $status){
    $query = ORM::forTable("late")->where("late_uid", $lateUid)->findOne();
        $query->set("status", $status);
        $query->set("name", $name);
        $query->set("duration", $duration);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function getLateByUid($uid){
    $query = ORM::forTable("late")->where("late_uid", $uid)->findOne();
    return $query;
}

function updateEmpLate($lateUid , $name , $status , $dateModified){
    $query = ORM::forTable("late_emp")->where("late_emp_uid", $lateUid)->findOne();
        $query->set("late_uid", $name);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

/*-------------------------------------Late Level End------------------------------------*/


/*-------------------------------------Degree Level------------------------------------*/

function getPaginatedEducationLevel() {
    $query = ORM::forTable("education_level")->findMany();
        return $query;
}

function degreeLevelCount($name){
    $query = ORM::forTable("education_level")->select_expr("count(education_level_uid)", "count")->where("level_name", $name)->findOne();
        return $query->count;
}

function newDegreeLevel($degreeLevelUid , $name , $dateCreated , $dateModified){
    if(degreeLevelCount($name) == 0){
        $query = ORM::forTable("education_level")->create();
            $query->education_level_uid = $degreeLevelUid;
            $query->level_name = $name;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
        $query->save();
    }else{
        $degreeLevel = getDegreeLevelByName($name);
        if($degreeLevel->status == 0){
            updateDegreeLevel($degreeLevel->education_level_uid , $name , $dateModified , 1);
        }
    }
}

function updateDegreeLevel($degreeLevelUid , $name , $dateModified , $status){
    $query = ORM::forTable("education_level")->where("education_level_uid", $degreeLevelUid)->findOne();
        $query->set("status", $status);
        $query->set("level_name", $name);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function getDegreeLevelByName($name){
    $query = ORM::forTable("education_level")->where("level_name", $name)->find_result_set();
        return $query;
}

function getDegreeLevelByUid($uid){
   $query = ORM::forTable("education_level")->where("education_level_uid", $uid)->findOne();
        return $query;
}


/*-------------------------------------Degree Level End------------------------------------*/

/*-------------------------------------Skill Type------------------------------------*/

function getPaginatedSkillType() {
    $query = ORM::forTable("skill")->findMany();
        return $query;
}

function skillTypeCount($name){
    $query = ORM::forTable("skill")->select_expr("count(skill_uid)", "count")->where("skill_type", $name)->findOne();
        return $query->count;
}

function newSkillType($skillUid , $name , $dateCreated , $dateModified){
    if(skillTypeCount($name) == 0){
        $query = ORM::forTable("skill")->create();
            $query->skill_uid = $skillUid;
            $query->skill_type = $name;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
        $query->save();
    }else{
        $skill = getSkillTypeByName($name);
        if($skill->status == 0){
            updateSkillType($skill->skill_uid , $name , $dateModified , 1);
        }
    }
}

function updateSkillType($skillUid , $name , $dateModified , $status){
    $query = ORM::forTable("skill")->where("skill_uid", $skillUid)->findOne();
        $query->set("status", $status);
        $query->set("skill_type", $name);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function getSkillTypeByName($name){
    $query = ORM::forTable("skill")->where("skill_type", $name)->find_result_set();
        return $query;
}

function getSkillTypeByUid($uid){
   $query = ORM::forTable("skill")->where("skill_uid", $uid)->findOne();
        return $query;
}


/*-------------------------------------Skill Type End------------------------------------*/

/*-------------------------------------Languages------------------------------------*/

function getPaginatedLanguages() {
    $query = ORM::forTable("languages")->findMany();
        return $query;
}

function languagesCount($name){
    $query = ORM::forTable("languages")->select_expr("count(languages_uid)", "count")->where("language_name", $name)->findOne();
        return $query->count;
}

function newLanguage($uid , $name , $dateCreated , $dateModified){
    if(languagesCount($name) == 0){
        $query = ORM::forTable("languages")->create();
            $query->languages_uid = $uid;
            $query->language_name = $name;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
        $query->save();
    }else{
        $language = getLanguageByName($name);
        if($language->status == 0){
            updateLanguage($language->languages_uid , $name , $dateModified , 1);
        }
    }
}

function updateLanguage($uid , $name , $dateModified , $status){
    $query = ORM::forTable("languages")->where("languages_uid", $uid)->findOne();
        $query->set("status", $status);
        $query->set("language_name", $name);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function getLanguageByName($name){
    $query = ORM::forTable("languages")->where("language_name", $name)->find_result_set();
        return $query;
}

function getLanguageByUid($uid){
   $query = ORM::forTable("languages")->where("languages_uid", $uid)->findOne();
        return $query;
}


/*-------------------------------------Languages End------------------------------------*/

/*-------------------------------------Licenses------------------------------------*/

function getPaginatedLicensesType() {
    $query = ORM::forTable("license")->findMany();
        return $query;
}

function licenseTypeCount($name){
    $query = ORM::forTable("license")->select_expr("count(license_uid)", "count")->where("license_name", $name)->findOne();
        return $query->count;
}

function newLicenseType($uid , $name , $dateCreated , $dateModified){
    if(licenseTypeCount($name) == 0){
        $query = ORM::forTable("license")->create();
            $query->license_uid = $uid;
            $query->license_name = $name;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
        $query->save();
    }else{
        $license = getLicenseTypeByName($name);
        if($license->status == 0){
            updateLanguage($license->license_uid , $name , $dateModified , 1);
        }
    }
}

function updateLicenseType($uid , $name , $dateModified , $status){
    $query = ORM::forTable("license")->where("license_uid", $uid)->findOne();
        $query->set("status", $status);
        $query->set("license_name", $name);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function getLicenseTypeByName($name){
    $query = ORM::forTable("license")->where("license_name", $name)->find_result_set();
        return $query;
}

function getLicenseTypeByUid($uid){
   $query = ORM::forTable("license")->where("license_uid", $uid)->findOne();
        return $query;
}


/*-------------------------------------Lincense End------------------------------------*/

function getLeaveEntitlementByUid(){
    $query = ORM::forTable("leave_entitlement")->tableAlias("le")->select("*")->SUM("no_days", "totaldays")->innerJoin("emp", array("le.emp_uid", "=", "e.emp_uid"), "e")->innerJoin("leaves_types", array("le.leaves_types_uid", "=", "lt.leaves_types_uid"), "lt")->innerJoin("leave_period", array("le.leave_period_uid", "=", "lp.leave_period_uid"), "lp")->where("le.status", "1")->groupBy("le.emp_uid")->groupBy("le.leaves_types_uid")->orderByDesc("lp.from_period")->findMany();
        return $query;
}

function updateFileStatusByReferenceFilenameEmpUid($reference , $fileName , $empUid , $status) {
    $query = ORM::forTable("files")->where("reference", $reference)->findOne();
        $query->set("status", $status);
    $query->save();
}

function getFilesByEmpUid($uid){
    $query = ORM::forTable("files")->where("emp_uid", $uid)->findOne();
    return $query;
}

function checkFilesIfUserExisting($uid){
    $query = ORM::forTable("files")->where("emp_uid", $uid)->count();
    $valid = false;
    if($query){
        $valid = true;
    }

    return $valid;
}

function getPathByEmpUid($empUid){
    $query = ORM::forTable("files")->select("path")->where("emp_uid", $empUid)->findOne();
    return $query;
}

function updateProfilePicture($empUid, $path2, $path, $tempFilename){
    $query = ORM::forTable("files")->where("emp_uid", $empUid)->findOne();
        $query->set("filename", $tempFilename);
        $query->set("path", $path);
    $query->save();
}
function newReferenceFile($uid, $reference, $filename, $path, $mimeType, $size, $date, $empUid) {
    if (!fileNameIsExisting($filename , $reference, $empUid)) {
        $query = ORM::forTable("files")->create();
            $query->uid = $uid;
            $query->reference = $reference;
            $query->filename = $filename;
            $query->path = $path;
            $query->mime_type = $mimeType;
            $query->size = $size;
            $query->date_created = $date;
            $query->emp_uid = $empUid;
        $query->save();
    }else{
        $file = getFileByPathAndReference($filename , $reference);
        if($file->status == 0){
            updateImageStatus($file->uid , 1);
        }
    }
}

function getFileByPathAndReference($filename , $reference){
    $query = ORM::forTable("files")->where("reference", $reference)->where("filename", $filename)->findOne();
        return $query;
}

function fileNameIsExisting($filename , $reference, $empUid){
    $query = ORM::forTable("files")->select_expr("COUNT(*)", "count")->where("reference", $reference)->where("filename", $filename)->where("emp_uid", $empUid)->findOne();
        $existing = false;
        if ($query->count >= 1) {
            $existing = true;
        }
        return $existing;
}
/*-------------------------------------Vacancies End------------------------------------*/

/*-------------------------------------SSS------------------------------------*/
function getSSS() {
    $query = ORM::forTable("hris_sss")->findMany();
        return $query;
}

function updateSSS($i , $sssStart, $sssEnd, $sssSalary, $sssEr, $sssEe, $sssTotal){
    $query = ORM::forTable("hris_sss")->findOne($i);
        $query->set("rangeOfComp", $sssStart);
        $query->set("rangeOfCompEnd", $sssEnd);
        $query->set("basic_salary", $sssSalary);
        $query->set("sssEr", $sssEr);
        $query->set("sssEe", $sssEe);
        $query->set("sssTotal", $sssTotal);
    $query->save();
}

function getSSSBySalary($salary){
    $query = ORM::forTable("hris_sss")
        ->rawQuery("SELECT * FROM hris_sss ORDER BY ABS(basic_salary - :salary) LIMIT 1", array("salary" => $salary))->findOne();
    return $query;
}

/*-------------------------------------SSS End------------------------------------*/

/*-------------------------------------Philhealth------------------------------------*/
function getPhilhealth() {
    $query = ORM::forTable("hris_philhealth")->findMany();
        return $query;
}

function getPhilhealthBySalary($salary){
    $query = ORM::forTable("hris_philhealth")
    ->rawQuery("SELECT * FROM hris_philhealth WHERE salaryRange <= :salary AND salaryRangeEnd >= :salary ORDER BY id DESC LIMIT 1", array("salary" => $salary))->findOne();
    return $query;
}
function updatePhilhealth($i , $salaryRange, $salaryRangeEnd, $salaryBase, $employeeShare, $employerShare, $totalMonthlyPremium){
    $query = ORM::forTable("hris_philhealth")->findOne($i);
        $query->set("salaryRange", $salaryRange);
        $query->set("salaryRangeEnd", $salaryRangeEnd);
        $query->set("salaryBase", $salaryBase);
        $query->set("employeeShare", $employeeShare);
        $query->set("employerShare", $employerShare);
        $query->set("totalMonthlyPremium", $totalMonthlyPremium);
    $query->save();

}
/*-------------------------------------Philhealth End------------------------------------*/

/*-------------------------------------Pag-ibig------------------------------------*/
function getPagibig() {
    $query = ORM::forTable("hris_pagibig")->findMany();
        return $query;
}

function updatePagibig($i , $pagibigGPR, $pagibigGPREnd, $pagibigEmpr, $pagibigEmp, $pagibigTotal){
    $query = ORM::forTable("hris_pagibig")->findOne($i);
        $query->set("pagibigGrossPayRange", $pagibigGPR);
        $query->set("pagibigGrossPayRangeEnd", $pagibigGPREnd);
        $query->set("pagibigEmployer", $pagibigEmpr);
        $query->set("pagibigEmployee", $pagibigEmp);
        $query->set("pagibigTotal", $pagibigTotal);
    $query->save();
}

/*-------------------------------------pag-ibig End------------------------------------*/

/*-------------------------------------Benefits------------------------------------*/
function getBenefitsPages(){
    $query = ORM::forTable("emp_benefits")->tableAlias("t1")->innerJoin("emp", array("t1.emp_uid", "=", "t2.emp_uid"), "t2")->where("t1.status", 1)->findMany();

    return $query;
}

function getEmpWithoutBenefits(){
    $query = ORM::forTable("emp_benefits")
        ->rawQuery("SELECT * FROM users as t1 INNER JOIN emp as t3 ON t1.emp_uid = t3.emp_uid WHERE t1.emp_uid NOT IN (SELECT emp_uid FROM emp_benefits as t2 WHERE t1.status=1) AND t1.username != 0001")->findMany();

    return $query;
}

function checkUserHasBenefits($emp){
    $query = ORM::forTable("emp_benefits")->where("emp_uid", $emp)->where("status", 1)->count();
    $valid = false;

    if($query >= 1){
        $valid = true;
    }

    return $valid;
}

function getEmpBenefitsByEmpUid($emp){
    $query = ORM::forTable("emp_benefits")->where("emp_uid", $emp)->where("status", 1)->findOne();
    return $query;
}

function setEmpBenefit($benefitUid, $emp, $sss, $phil, $hdmf, $dateCreated, $dateModified){
    $query = ORM::forTable("emp_benefits")->create();
        $query->emp_benefit_uid = $benefitUid;
        $query->emp_uid = $emp;
        $query->emp_sss = $sss;
        $query->emp_philhealth = $phil;
        $query->emp_pagibig = $hdmf;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getBenefitsByUid($uid){
    $query = ORM::forTable("emp_benefits")->where("emp_benefit_uid", $uid)->where("status", 1)->findOne();
    return $query;
}

function updateEmpBenefit($uid, $sss, $phil, $hdmf, $dateModified, $status){
    $query = ORM::forTable("emp_benefits")->where("emp_benefit_uid", $uid)->findOne();
        $query->set("emp_sss", $sss);
        $query->set("emp_philhealth", $phil);
        $query->set("emp_pagibig", $hdmf);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

/*-------------------------------------Benefits End------------------------------------*/

/*-------------------------------------paygrade------------------------------------*/

function paygradeIsExisting($paygradeName){
    $query = ORM::forTable("paygrade")->select_expr("count(paygrade_uid)", "count")->where("paygrade_name" , $paygradeName)->findOne();
    if($query->count >= 1){
        return true;
    }else{
        return false;
    }
}

function addPaygrade($paygradeUid, $paygradeName, $dateCreated, $dateModified) {
    if(!paygradeIsExisting($paygradeName)){
    $query = ORM::forTable("paygrade")->create();
        $query->paygrade_uid = $paygradeUid;
        $query->paygrade_name = $paygradeName;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
        $query->status = "1";
    $query->save();
        return false;
    }else{
        return true;
    }
}

function addPaygradeLevel($paygradeLevelUid, $pgUid, $pgLevelName, $pgLevelMin, $pgLevelMid, $pgLevelMax, $pgDateCreated, $pgDateModified){
    $query = ORM::forTable("paygradeLevel")->create();
        $query->pgLevel_uid = $paygradeLevelUid;
        $query->pg_uid = $pgUid;
        $query->pgLevelName = $pgLevelName;
        $query->pgLevelMinimum = $pgLevelMin;
        $query->pgLevelMid = $pgLevelMid;
        $query->pgLevelMaximum = $pgLevelMax;
        $query->pgLevel_date_created = $pgDateCreated;
        $query->pgLevel_date_modified = $pgDateModified;
        $query->pgLevelStatus = "1";
    $query->save();
    return $query;
}

function paygradeView(){
    $query = ORM::forTable("paygrade")->tableAlias("t1")->innerJoin("paygradeLevel", array("t1.paygrade_uid", "=", "t2.pg_uid"), "t2")->orderByAsc("t1.paygrade_name")->findMany();
    return $query;
}

/*-------------------------------------paygrade End------------------------------------*/
/*-------------------------------------currency------------------------------------*/
function currencyIsExisting($currencyName){
    $query = ORM::forTable("currency")->select_expr("count(currency_uid)", "count")->where("name", $currencyName)->findOne();
    if($query->count >= 1){
        return true;
    }else{
        return false;
    }
}

function addCurrency($currencyUid, $currencyName, $dateCreated, $dateModified){
    if(!currencyIsExisting($currencyName)){
        $query = ORM::forTable("currency")->create();
            $query->currency_uid = $currencyUid;
            $query->name = $currencyName;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
            $query->status = "1";
        $query->save();
        return false;
    }else{
        return true;
    }
}

/*-------------------------------------currency End------------------------------------*/


/*-------------------------------------frequency------------------------------------*/
function frequencyIsExisting($frequencyName){
    $query = ORM::forTable("pay_period")->select_expr("count(pay_period_uid)", "count")->where("pay_period_name", $frequencyName)->findOne();
    if($query->count >= 1){
        return true;
    }else{
        return false;
    }
}

function addFrequency($frequencyUid, $frequencyName, $frequency, $dateCreated, $dateModified){
    // if(!frequencyIsExisting($frequencyName)){
        $query = ORM::forTable("pay_period")->create();
            $query->pay_period_uid = $frequencyUid;
            $query->pay_period_name = $frequencyName;
            $query->frequency = $frequency;
            $query->date_created = $dateCreated;
            $query->date_modified = $dateModified;
            $query->status = "1";
        $query->save();
    //     return false;
    // }else{
    //     return true;
    // }
}

function getPaginatedFrequency(){
    $query = ORM::forTable("pay_period")->where("status", "1")->findMany();
    return $query;
}

function getfrequencyByUid($uid){
    $query = ORM::forTable("pay_period")->where("pay_period_uid", $uid)->findOne();
    return $query;
}

function frequencyCount($name){
    $query = ORM::forTable("pay_period")->where("pay_period_name", $name)->count();
    return $query;
}

function updateFrequencyById($frequencyUid , $name , $frequencies , $dateModified , $status){
    $query = ORM::forTable("pay_period")->where("pay_period_uid", $frequencyUid)->findOne();
        $query->set("pay_period_name", $name);
        $query->set("frequency", $frequencies);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

/*-------------------------------------frequency End------------------------------------*/

/*-------------------------------------set schedule------------------------------------*/
function setSchedule($scheduleUid, $payrollDate, $cutoffDate){
    $query = ORM::forTable("payroll_schedule")->create();
        $query->schedule_uid = $scheduleUid;
        $query->payroll_date = $payrollDate;
        $query->cutoff_date = $cutoffDate;
        $query->status = "1";
    $query->save();
}

// function getSchedules($frequencyUid){
//     $query = ORM::forTable("payroll_schedule")->where("frequency_uid", $frequencyUid)->where("status", "1")->findOne();
//     return $query;
// }

function getSchedules(){
    $query = ORM::forTable("payroll_schedule")->where("status", "1")->findMany();
    return $query;
}

function getSchedulesByUid($id){
    $query = ORM::forTable("payroll_schedule")->where("schedule_uid", $id)->findOne();
    return $query;
}

function editScheduleStatus($id, $startDate, $endDate,$status){
    $query = ORM::forTable("payroll_schedule")->where("schedule_uid", $id)->findOne();
        $query->set("payroll_date", $startDate);
        $query->set("cutoff_date", $endDate);
        $query->set("status", $status);
    $query->save();
}

function addScheduleData($schedUid, $id, $startDate, $endDate, $dateCreated, $dateModified){
    $query = ORM::forTable("payroll_schedule_data")->create();
        $query->schedule_data_uid = $schedUid;
        $query->frequency_uid = $id;
        $query->payroll_date = $startDate;
        $query->cutoff_date = $endDate;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}
/*-------------------------------------set schedule End------------------------------------*/


/*-------------------------------------Loans------------------------------------*/
function getLoansDetails(){
    $query = ORM::forTable("loan")->where("status" , "1")->orderByAsc("name")->findMany();
    return $query;
}

function addLoans($loansUid , $loanName, $loanInterest , $dateCreated , $dateModified){
    $query = ORM::forTable("loan")->create();
        $query->loan_uid = $loansUid;
        $query->name = $loanName;
        $query->interest = $loanInterest;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
        $query->status = "1";
    $query->save();
}

function getLoanByUid($uid){
    $query = ORM::forTable("loan")->where("loan_uid", $uid)->findOne();
        return $query;
}
function getEmpLoanByUid($uid){
    $query = ORM::forTable("emp_loans")->where("emp_loans_uid", $uid)->findOne();
        return $query;
}
function updateEmpLoan($uid, $loanType, $amount, $dateModified, $status){
    $query = ORM::forTable("emp_loans")->where("emp_loans_uid", $uid)->findOne();
        $query->set("loan_uid", $loanType);
        $query->set("amount", $amount);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
        return $query;
}

function updateLoansById($loansUid , $loanName , $loanInterest , $dateModified , $status){
    $query = ORM::forTable("loan")->where("loan_uid", $loansUid)->findOne();
        $query->set("name", $loanName);
        $query->set("interest", $loanInterest);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function loanCount($loanName){
    $query = ORM::forTable("loan")->select_expr("count(loan_uid)", "count")->where("name", $loanName)->findOne();
        return $query->count;
}

function getLoansByEmpUid($empUid){
    $query = ORM::forTable("loans")
    ->rawQuery("SELECT * FROM emp_loans as t1 INNER JOIN loan as t2 ON t1.loan_uid=t2.loan_uid WHERE t1.emp_uid = :empUid", array("empUid" => $empUid))
    ->findMany();
    return $query;
}

function addEmpLoans($loanDeductionsUid, $empUid , $loanType, $loanAmount , $dateCreated , $dateModified){
    $query = ORM::forTable("emp_loans")->create();
        $query->emp_loans_uid = $loanDeductionsUid;
        $query->emp_uid = $empUid;
        $query->loan_uid = $loanType;
        $query->amount = $loanAmount;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
        $query->status = "1";
    $query->save();
}

function getLoanDeductionByUid($loanUid){
    $query = ORM::forTable("loan_deductions")
    ->rawQuery("SELECT * FROM loan_deductions as t1 INNER JOIN loan as t2 ON t1.emp_loans_uid=t2.loan_uid WHERE loan_deductions_uid = :loanUid", array("loanUid" => $loanUid))
    ->findOne();
    return $query;
}
/*-------------------------------------Loans End------------------------------------*/

/*-------------------------------------RATE Settings------------------------------------*/

function addRate($overtimeUid, $code , $name , $rate , $dateCreated , $dateModified){
    $query = ORM::forTable("holiday_types")->create();
        $query->holiday_type_uid=$overtimeUid;
        $query->holiday_code=$code;
        $query->holiday_name_type=$name;
        $query->rate=$rate;
        $query->date_created=$dateCreated;
        $query->date_modified=$dateModified;
    $query->save();
}

function getRatesByUid($rateUid){
    $query = ORM::forTable("holiday_types")->where("holiday_type_uid", $rateUid)->findOne();
    return $query;
}

function updateRateById($rateUid, $code ,$name , $rate, $dateModified , $status){
    $query = ORM::forTable("holiday_types")->where("holiday_type_uid", $rateUid)->findOne();
        $query->set("holiday_code", $code);
        $query->set("holiday_name_type", $name);
        $query->set("rate", $rate);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function rateCount($name){
    $query = ORM::forTable("holiday_types")->select_expr("count(holiday_type_uid)", "count")->where("holiday_name_type", $name)->findOne();
    return $query->count;
}

function getRateByEmpUid($uid, $startDate, $endDate){
    $query = ORM::forTable("overtime_type")->tableAlias("t1")
    ->rawQuery("SELECT t1.rate as rate, t2.overtime_request_uid FROM overtime_type as t1 INNER JOIN overtime_requests as t2 ON t1.overtime_type_uid = t2.type WHERE t2.emp_uid = :uid AND date(t2.start_date) >= :startDate AND date(t2.end_date) <= :endDate AND t2.overtime_request_status = 'Approved' AND t2.status = 1", array("startDate" => $startDate, "endDate" => $endDate, "uid" => $uid))
        ->findMany();
    return $query;
}

function getRateCodeByUid($id){
    $query = ORM::forTable("overtime_type")->tableAlias("t1")
        ->select("t1.overtime_type_code", "code")
        ->innerJoin("overtime_requests", array("t1.overtime_type_uid", "=", "t2.type"), "t2")
        ->where("t2.overtime_request_uid", $id)
        ->where("t2.status", 1)
        ->findOne();
    return $query->code;
}
/*-------------------------------------Overtime Settings End------------------------------------*/
/*FUNCTION FOR CONNECTION LAHAT SA SPACETIME*/

//employee
function getSTEmployee(){
    spacetime();
    $query = ORM::forTable("users")->where("active", 1)->findMany();

    return $query;
}

function getSTTimeLogs(){
    spacetime();
    $query = ORM::forTable("users")
    ->rawQuery("SELECT * FROM users as t1 INNER JOIN clock_logs as t2 ON t1.uid=t2.user")
    ->findMany();

    return $query;
}

//Function for testing
function insertTry($name, $username, $password, $email){
    $query = ORM::forTable("testing")->create();
        $query->name = $name;
        $query->username = $username;
        $query->password = $password;
        $query->email = $password;
    $query->save();
}

function insertTimeFromSpacetime($uid, $user, $type, $shift, $dateTime){
    $query = ORM::forTable("time_log")->create();
        $query->time_log_uid = $uid;
        $query->emp_uid = $user;
        $query->shift_uid = $shift;
        $query->type = $type;
        $query->date_created = $dateTime;
        $query->date_modified = $dateTime;
    $query->save();
}

function insertAgain($name){
    $query = ORM::forTable("testing")->create();
        $query->name = $name;
    $query->save();
}

function getLeaveRequestByEmpUid($id, $start, $end){
    $query = ORM::forTable("leave_requests")
        ->rawQuery("SELECT DATEDIFF(:ends, :starts) AS time FROM leave_requests WHERE emp_uid = :uid AND leave_request_status = 'Approved' AND status = '1'", array("uid" => $id, "starts" => $start, "ends" => $end))
        ->findOne();
    return $query->time;
}

function daysOfWork($start, $end){
    $query = ORM::forTable("leave_requests")
        ->rawQuery("SELECT DATEDIFF(:ends, :starts) AS time FROM time_log WHERE status = '1'", array("starts" => $start, "ends" => $end))
        ->findOne();
    return $query->time;
}

function getTotalWorked($id, $start, $end){
    $query = ORM::forTable("leave_requests")
        ->rawQuery("SELECT DATEDIFF(:ends, :starts) AS time FROM time_log WHERE emp_uid = :uid AND date_created LIKE CONCAT(:starts, '%') AND status = '1'", array("uid" => $id, "starts" => $start, "ends" => $end))
        ->findOne();
    return $query->time;
}

function getScheduleCountDays($id, $start, $end){
    $query = ORM::forTable("payroll_schedule")
        ->rawQuery("SELECT DATEDIFF(:ends, :starts) AS count FROM payroll_schedule WHERE frequency_uid = :uid AND status = 1", array("uid" => $id, "ends" => $end, "starts" => $start))
        ->findOne();
    return $query->count;
}

//FOR EMPLOYEES
function getEmpLeaveRequestsByEmpUid($uid){
    $query = ORM::forTable("leave_requests")->tableAlias("t1")->innerJoin("leaves_types", array("t1.leaves_types_uid", "=", "t2.leaves_types_uid"), "t2")->where("t1.emp_uid" , $uid)->where("t1.status", 1)->findMany();
        return $query;
}

function getOvertimeRequestsByEmpUid($uid){
    $query = ORM::forTable("overtime_requests")->where("emp_uid", $uid)->where("status", 1)->findMany();
        return $query;
}

function getOvertimeRequestsByUid($uid){
    $query = ORM::forTable("overtime_requests")->tableAlias("t1")->innerJoin("overtime_type", array("t1.type", "=", "t2.overtime_type_uid"), "t2")->where("t1.overtime_request_uid", $uid)->findOne();
        return $query;
}

function getFrequencyByEmpUid($uid){
    $query = ORM::forTable("salary")->tableAlias("t1")->innerJoin("pay_period", array("t1.pay_period_uid", "=", "t2.pay_period_uid"), "t2")->where("t1.emp_uid", $uid)->findOne();
        return $query;
}

function checkGetFrequencyByEmpUid($uid){
    $query = ORM::forTable("salary")->tableAlias("t1")->innerJoin("pay_period", array("t1.pay_period_uid", "=", "t2.pay_period_uid"), "t2")->where("t1.emp_uid", $uid)->count();
    $valid = false;
    if($query >= 1){
        $valid = true;
    }

    return $valid;
}

//BASTA
function checkEmpShift($empShiftUid, $userId, $batch, $shiftId){
    $query = ORM::forTable("emp_shift")->selectExpr("COUNT(emp_shift_uid)", "count")
        ->where("emp_shift_uid", $empShiftUid)
        ->where("shift_uid", $shiftId)
        ->where("emp_uid", $userId)
        ->where("batch", $batch)
        ->findOne();
    return $query->count;
}

function insertEmpShift($empShiftUid, $userId, $batch, $shiftId, $dateCreated, $dateModified){
    $query = ORM::forTable("emp_shift")->create();
        $query->emp_shift_uid = $empShiftUid;
        $query->shift_uid = $shiftId;
        $query->emp_uid = $userId;
        $query->batch = $batch;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function updateEmpShifts($empShiftUid, $userId, $batch, $shiftId, $dateModified){
    $query = ORM::forTable("emp_shift")->whereRaw("emp_shift_uid = :empShiftUid OR emp_uid = :userId", array("empShiftUid" => $empShiftUid, "userId" => $userId))->findOne();
        $query->set("batch", $batch);
        $query->set("shift_uid", $shiftId);
        $query->set("date_modified", $dateModified);
    $query->save();
}

function insertEmpData($userId, $fname, $lname, $mname, $dateCreated, $dateModified, $status){
    $query = ORM::forTable("emp")->create();
        $query->emp_uid = $userId;
        $query->firstname = $fname;
        $query->middlename = $mname;
        $query->lastname = $lname;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
        $query->status = $status;
    $query->save();
}

function insertEmpUser($empId, $userId, $userType, $dateCreated, $dateModified, $status){
    $query = ORM::forTable("users")->create();
        $query->users_uid = $empId;
        $query->username = $userId;
        $query->emp_uid = $userId;
        $query->type = $userType;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
        $query->status = $status;
    $query->save();
}

function checkEmpData($userId){
    $query = ORM::forTable("emp")->selectExpr("COUNT(emp_uid)", "count")->where("emp_uid", $userId)->findOne();
    return $query->count;
}

function checkEmpUser($userId){
    $query = ORM::forTable("users")->selectExpr("COUNT(emp_uid)", "count")->where("emp_uid", $userId)->findOne();
    return $query;
}

function updateEmpData($userId, $fname, $lname, $mname, $dateModified, $status){
    $query = ORM::forTable("emp")->where("emp_uid", $userId)->where("status", 1)->findOne();
        $query->set("firstname", $fname);
        $query->set("lastname", $lname);
        $query->set("middlename", $mname);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function updateEmpUser($userId, $userType, $dateModified, $status){
    $query = ORM::forTable("users")->where("emp_uid", $userId)->where("status", 1)->findOne();
        $query->set("username", $userId);
        $query->set("emp_uid", $userId);
        $query->set("type", $userType);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function insertHoliday($empId, $date, $name, $type, $dateCreated ,$dateModified, $status){
    $query = ORM::forTable("holiday")->create();
        $query->holiday_uid = $empId;
        $query->name = $name;
        $query->type = $type;
        $query->date = $date;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
        $query->status = $status;  
    $query->save();
}

function checkHoliday($date, $name){
    $query = ORM::forTable("holiday")->selectExpr("COUNT(name)", "count")
    ->where("date", $date)
    ->where("name", $name)
    ->findOne();

    return $query->count;
}

function updateEmpHoliday($date, $name, $type, $dateModified, $status){
    $query = ORM::forTable("holiday")
    ->where("date", $date)
    ->whereRaw("name", $name)
    ->findOne();
        $query->set("date", $date);
        $query->set("name", $name);
        $query->set("type", $type);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}
function getHolidayTypes($type){
    $query = ORM::forTable("holiday_types")->select("holiday_type_uid")->where("holiday_name_type", $type)->findOne();
    return $query->holiday_type_uid;
}

function insertShift($shiftId, $timein, $timeout, $shift, $batch, $dateCreated, $dateModified){
    $query = ORM::forTable("shift")->create();
        $query->shift_uid = $shiftId;
        $query->name = $shift;
        $query->start = $timein;
        $query->end = $timeout;
        $query->batch = $batch;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function checkShift($shift, $batch){
    $query = ORM::forTable("shift")->selectExpr("COUNT(shift_uid)", "count")
        ->where("name", $shift)
        ->where("batch", $batch)
        ->findOne();
    return $query->count;
}

function updateShifts($shiftId, $timein, $timeout, $shift, $batch, $dateCreated, $dateModified){
    $query = ORM::forTable("shift")->where("name", $shift)->where("batch", $batch)->findOne();
        $query->save("name", $shift);
        $query->save("start", $timein);
        $query->save("end", $timeout);
        $query->save("batch", $batch);
        $query->save("date_modified", $dateModified);
    $query->save();

}

function getSalaryType($type){
    $query = ORM::forTable("pay_period")->select("pay_period_uid", "uid")->where("pay_period_name", $type)->findOne();
    return $query->uid;
}   

function insertSalary($salaryUid, $userId, $salary, $type, $dateCreated, $dateModified){
    $query = ORM::forTable("salary")->create();
        $query->salary_uid = $salaryUid;
        $query->emp_uid = $userId;
        $query->base_salary = $salary;
        $query->pay_period_uid = $type;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function checkEmpSalary($userId, $type){
    $query = ORM::forTable("salary")->selectExpr("COUNT(id)", "count")
        ->where("emp_uid", $userId)
        ->where("pay_period_uid", $type)
        ->findOne();
    return $query->count;
}

function updateSalaries($userId, $salary, $type, $dateModified){
    $query = ORM::forTable("salary")->where("emp_uid", $salaryUid)->findOne();
        $query->set("emp_uid", $userId);
        $query->set("base_salary", $salary);
        $query->set("pay_period_uid", $type);
        $query->set("date_modified", $dateModified);
    $query->save();
}
/*-------------------------------------REST DAY----------------------------------------------*/

function getRestDay(){
    $query = ORM::forTable("restday")->where("status", 1)->findMany();
    return $query;
}

function getRestDayByUid($uid){
    $query = ORM::forTable("restday")->where("restday_uid", $uid)->findOne();
    return $query;
}

function editRestDay($uid, $restDay, $dateModified, $status){
    $query = ORM::forTable("restday")->where("restday_uid", $uid)->findOne();
        $query->set("name", $restDay);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function getRestDayByDay($day){
    $query = ORM::forTable("restday")->where("name", $day)->where("status", 1)->limit(1)->findOne();
    return $query;
}

function newRestDay($restDayUid, $restDay, $dateCreated, $dateModified){
    $query = ORM::forTable("restday")->create();
        $query->restday_uid = $restDayUid;
        $query->name = $restDay;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

/*-----------------------------------END OF REST DAY----------------------------------------------*/

/*-------------------------------------OFFSET----------------------------------------------*/
function getOffset(){
    $query = ORM::forTable("offset_requests")->tableAlias("t1")->innerJoin("emp", array("t1.emp_uid", "=", "t2.emp_uid"), "t2")->innerJoin("users", array("t1.emp_uid", "=", "t3.emp_uid"), "t3")->where("t1.request_status", "pending")->where("t1.status", 1)->orderByDesc("t1.date_modified")->findMany();
    return $query;
}

function getOffsetByDate($startDate, $endDate){
    $query = ORM::forTable("offset_requests")
        ->rawQuery("SELECT * FROM offset_requests as t1 INNER JOIN emp as t2 ON t1.emp_uid = t2.emp_uid INNER JOIN users as t3 ON t1.emp_uid = t3.emp_uid WHERE (t1.from_date BETWEEN :start AND :end) OR (t1.set_date BETWEEN :start AND :end) AND t1.status = 1 ORDER BY t1.date_modified DESC", array("start" => $startDate, "end" => $endDate))
        ->findMany();
    return $query;
}

function newOffsetRequest($offsetUid, $emp, $fromDate, $setDate, $reason,$dateCreated, $dateModified){
    $query = ORM::forTable("offset_requests")->create();
        $query->offset_uid = $offsetUid;
        $query->emp_uid = $emp;
        $query->from_date = $fromDate;
        $query->set_date = $setDate;
        $query->reason = $reason;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function newOffsetNotif($offsetNotifUid, $offsetUid, $dateCreated, $dateModified){
    $query = ORM::forTable("offset_notification")->create();
        $query->offset_notification_uid = $offsetNotifUid;
        $query->offset_uid = $offsetUid;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getOffsetRequestsByEmpUid($uid){
    $query = ORM::forTable("offset_requests")->where("emp_uid", $uid)->where("status", 1)->findMany();
    return $query;
}

function getOffsetRequestsByUid($uid){
    $query = ORM::forTable("offset_requests")->where("offset_uid", $uid)->findOne();
    return $query;
}

function editOffset($uid, $fromDate, $setDate, $reason, $requestStatus, $user1, $user2, $dateModified, $status){
    $query = ORM::forTable("offset_requests")->where("offset_uid", $uid)->findOne();
    if($requestStatus == "Approved"){
        $query->set("from_date", $fromDate);
        $query->set("set_date", $setDate);
        $query->set("reason", $reason);
        $query->set("request_status", $requestStatus);
        $query->set("app_by", $user2);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    }else if($requestStatus == "Certified"){
        $query->set("from_date", $fromDate);
        $query->set("set_date", $setDate);
        $query->set("reason", $reason);
        $query->set("request_status", $requestStatus);
        $query->set("cert_by", $user1);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    }else{
        $query->set("from_date", $fromDate);
        $query->set("set_date", $setDate);
        $query->set("reason", $reason);
        $query->set("request_status", $requestStatus);
        $query->set("cert_by", $user1);
        $query->set("app_by", $user2);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    }

    $query->save();
}

function editOffsetNotification($uid, $requestStatus, $dateModified){
    $query = ORM::forTable("offset_notification")->where("offset_uid", $uid)->findOne();
        $query->set("request_status", $requestStatus);
        $query->set("date_modified", $dateModified);
        $query->set("status", 1);
    $query->save();
}

function getOffsetNewRequestNotification(){
    $query = ORM::forTable("offset_notification")->where("request_status", "pending")->where("status", 1)->count();
    return $query;
}

function editOffsetNotificationRead(){
    $query = ORM::forTable("offset_notification")->where("request_status", "pending")->where("status", 1)->findOne();
        $query->set("status", "0");
    $query->save();
    return $query;
}

function countPendingRequestsOfOffset(){
    $query = ORM::forTable("offset_requests")->where("request_status", "pending")->where("status", 1)->count();
    return $query;
}

function countAcceptedRequestsOfOffset(){
    $query = ORM::forTable("offset_requests")->where("request_status", "approved")->where("status", 1)->count();
    return $query;
}

function countPendingRequestsOfOffsetByDate($startDate, $endDate){
    $query = ORM::forTable("offset_requests")
        ->rawQuery("SELECT COUNT(request_status) as count FROM offset_requests WHERE (from_date BETWEEN :start AND :end OR set_date BETWEEN :start AND :end) AND request_status = 'pending' AND status = 1", array("start" => $startDate, "end" => $endDate))
        ->findOne();
    return $query->count;
}

function countAcceptedRequestsOfOffsetByDate($startDate, $endDate){
    $query = ORM::forTable("offset_requests")
        ->rawQuery("SELECT COUNT(request_status) as count FROM offset_requests WHERE (from_date BETWEEN :start AND :end OR set_date BETWEEN :start AND :end) AND request_status = 'approved' AND status = 1", array("start" => $startDate, "end" => $endDate))
        ->findOne();
    return $query->count;
}

function countAcceptedOffsetRequestByEmpUid($uid){
    $query = ORM::forTable("offset_requests")->
        rawQuery("SELECT COUNT(t1.offset_uid) as count FROM offset_requests as t1 INNER JOIN offset_notification as t2 ON t1.offset_uid = t2.offset_uid WHERE t1.emp_uid = :uid AND t1.request_status = 'approved' AND t1.status = 1 AND t2.status = 1", array("uid" => $uid))
        ->findOne();
    return $query->count;
}

function getOffsetNotifUidByEmpUid($uid){
    $query = ORM::forTable("offset_notification")->tableAlias("t1")->innerJoin("offset_requests", array("t1.offset_uid", "=", "t2.offset_uid"), "t2")->where("t2.emp_uid", $uid)->where("t1.request_status", "approved")->where("t2.status", 1)->where("t1.status", 1)->findMany();
    return $query;
}

function editOffsetNotificationByOffsetUid($uid, $dateModified){
    $query = ORM::forTable("offset_notification")->where("offset_uid", $uid)->findOne();
        $query->set("date_modified", $dateModified);
        $query->set("status", 0);
    $query->save();
}

function editOffsetNotificationByEmpUid($uid, $dateModified){
    $query = ORM::forTable("offset_notification")->tableAlias("t1")->innerJoin("offset_requests", array("t1.offset_uid", "=", "t2.offset_uid"), "t2")->where("t2.emp_uid", $uid)->where("t1.status", 1)->where("t1.request_status", "approved")->findOne();
        $query->set("t1.date_modified", $dateModified);
        $query->set("t1.status", 0);
    $query->save();
}

function countPendingOffserNotifByEmpUid($uid){
    $query = ORM::forTable("offset_requests")->where("emp_uid", $uid)->where("status", 1)->where("request_status", "pending")->count();
    return $query;
}

function getAcceptedOffsetRequestByEmpUid($uid, $date){
    $query = ORM::forTable("offset_requests")->where("emp_uid", $uid)->where("set_date", $date)->where("request_status", "approved")->where("status", 1)->findOne();
    return $query;
}

function addTimeRequest($timeUid, $employee, $timeIn, $timeOut, $timeDate, $reason, $dateCreated, $dateModified){
    $query = ORM::forTable("time_request")->create();
        $query->time_requests_uid = $timeUid;
        $query->emp_uid = $employee;
        $query->time_in = $timeIn;
        $query->time_out = $timeOut;
        $query->date_request = $timeDate;
        $query->reason = $reason;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function checkTimeRequest($date, $emp){
    $query = ORM::forTable("time_request")->where("emp_uid", $emp)->where("date_request", $date)->where("status", 1)->count();
    $valid = false;

    if($query >= 1){
        $valid = true;
    }

    return $valid;
}
function addTimeReqNotification($uid, $timeUid, $dateCreated, $dateModified){
    $query = ORM::forTable("time_request_notification")->create();
        $query->time_request_notification_uid = $uid;
        $query->time_request_uid = $timeUid;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function countPendingRequestsOfTimeReq(){
    $query = ORM::forTable("time_request_notification")->where("request_status", "Pending")->where("status", 1)->count();
    return $query;
}

function editRequestsOfTimeReq($dateModified){
    $query = ORM::forTable("time_request_notification")->where("status", 1)->findMany();
    $query->set("status", "0");
    $query->set("date_modified", $dateModified);
    $query->save();
}

function countTimeNotif(){
    $query = ORM::forTable("time_request_notification")->where("status", 1)->count();
    return $query;
}

function countAcceptedRequestsOfTimeReq(){
    $query = ORM::forTable("time_request")->where("request_status", "Approved")->where("status", 1)->count();
    return $query;
}

function countPendingRequestsOfTimeReqByDate($startDate, $endDate){
    $query = ORM::forTable("time_request")
        ->rawQuery("SELECT COUNT(time_requests_uid) AS count FROM time_request WHERE (date_request BETWEEN :start AND :end) AND request_status = 'Pending' AND status = 1", array("start" => $startDate, "end" => $endDate))
        ->findOne();
    return $query->count;
}

function countAcceptedRequestsOfTimeReqByDate($startDate, $endDate){
    $query = ORM::forTable("time_request")
        ->rawQuery("SELECT COUNT(time_requests_uid) AS count FROM time_request WHERE (date_request BETWEEN :start AND :end) AND request_status = 'Approved' AND status = 1", array("start" => $startDate, "end" => $endDate))
        ->findOne();
    return $query->count;
}

function checkTimeIsRequested($date, $emp){
    $query = ORM::forTable("time_request")->where("emp_uid", $emp)->where("date_request", $date)->where("status", 1)->where("request_status", "Approved")->count();
    $valid = false;
    if($query >= 1){
        $valid = true;
    }

    return $valid;
}

function getTimeOffset(){
    $query = ORM::forTable("time_request")->tableAlias("t1")->innerJoin("emp", array("t1.emp_uid", "=", "t2.emp_uid"), "t2")->innerJoin("users", array("t1.emp_uid", "=", "t3.emp_uid"), "t3")->where("t1.status", 1)->orderByDesc("t1.id")->findMany();

    return $query;
}

function getTimeRequestsByDateAndEmpUid($startDate, $endDate, $emp){
    $query = ORM::forTable("time_request")
        ->rawQuery("SELECT * FROM emp as t1 INNER JOIN time_request as t2 ON t1.emp_uid=t2.emp_uid INNER JOIN users as t3 ON t2.emp_uid = t3.emp_uid WHERE (t2.date_request BETWEEN :start AND :end) AND t2.emp_uid = :emp AND t2.status=1 ORDER BY t2.id DESC", array("start" => $startDate, "end" => $endDate, "emp" => $emp))->findMany();
    return $query;
}

function getTimeRequestsByDate($startDate, $endDate, $reqStatus){
    $query = ORM::forTable("time_request")
        ->rawQuery("SELECT * FROM emp as t1 INNER JOIN time_request as t2 ON t1.emp_uid=t2.emp_uid INNER JOIN users as t3 ON t2.emp_uid = t3.emp_uid WHERE (t2.date_request BETWEEN :start AND :end) AND request_status = :status AND t2.status=1 ORDER BY t2.id DESC", array("start" => $startDate, "end" => $endDate, "status" => $reqStatus))->findMany();
    return $query;
}

function getOffsetTimeRequestByUid($uid){
    $query = ORM::forTable("time_request")->where("time_requests_uid", $uid)->findOne();
    return $query;
}

function getTimeOffsetByEmpUid($uid){
    $query = ORM::forTable("time_request")->where("emp_uid", $uid)->where("status", 1)->findMany();
    return $query;
}

function checkTimeDateByEmpUid($uid, $date){
    $query = ORM::forTable("time_log")
    ->rawQuery("SELECT count(date_created) as count FROM time_log WHERE emp_uid = :uid AND date(date_created) = :dates AND status = 1", array("uid" => $uid, "dates" => $date))->findOne();
    $valid = false;
    if($query->count >= 1){
        $valid = true;
    }

    return $valid;
}

function removeDateFromTimeInLogByEmpAndDate($uid, $date){
    $query = ORM::forTable("time_log")
    ->rawQuery("SELECT * FROM time_log WHERE emp_uid = :uid AND date(date_created) = :dates AND type = '0' AND status = 1", array("uid" => $uid, "dates" => $date))->findResultSet();
    // if($query->count >= 1){
    //     $query2 = ORM::forTable("time_log")
       // ->rawQuery("SELECT * FROM time_log WHERE emp_uid = :uid AND date(date_created) = :dates AND type = '0' AND status = 1", array("uid" => $uid, "dates" => $date))->findOne();
       // ->rawQuery("SELECT * FROM time_log WHERE emp_uid = :uid AND date(date_created) = :dates AND type = '0' AND status = 1", array("uid" => $uid, "dates" => $date))->delete();
        if($query){
            $query->set("status", 0);
            $query->save();
        }
    // }
    
}

function removeDateFromTimeOutLogByEmpAndDate($uid, $date){
    $query = ORM::forTable("time_log")
    ->rawQuery("SELECT * FROM time_log WHERE emp_uid = :uid AND date(date_created) = :dates AND type = '1' AND status = 1", array("uid" => $uid, "dates" => $date))->findResultSet();
    if($query){
        $query->set("status", 0);
        $query->save();
    }
}

function addTimeSheetIn($timeInUid, $employee, $shift, $session, $typeIn, $timeIn, $status){
    $query = ORM::forTable("time_log")->create();
        $query->time_log_uid = $timeInUid;
        $query->emp_uid = $employee;
        $query->shift_uid = $shift;
        $query->session = $session;
        $query->type = $typeIn;
        $query->date_created = $timeIn;
        $query->date_modified = $timeIn;
        $query->status = $status;

    $query->save();
}

function addTimeSheetOut($timeOutUid, $employee, $shift, $session, $typeOut, $timeOut, $status){
    $query = ORM::forTable("time_log")->create();
        $query->time_log_uid = $timeOutUid;
        $query->emp_uid = $employee;
        $query->shift_uid = $shift;
        $query->session = $session;
        $query->type = $typeOut;
        $query->date_created = $timeOut;
        $query->date_modified = $timeOut;
        $query->status = $status;
    $query->save();
}

function deleteTimeByUid($uid){
    $query = ORM::forTable("time_request")->where("time_requests_uid", $uid)->findOne();
        $query->set("status", 0);
    $query->save();
}

function deleteTimeLogByUid($uid){
    $query = ORM::forTable("time_log")->where("time_log_uid", $uid)->findOne();
        $query->set("status", 0);
    $query->save();
}

function editTimeRequest($uid, $timeIn, $timeOut, $timeDate, $reason, $reqStatus, $user1, $user2 ,$dateModified, $status){
    $query = ORM::forTable("time_request")->where("time_requests_uid", $uid)->findOne();
    if($reqStatus == "Certified"){
        $query->set("time_in", $timeIn);
        $query->set("time_out", $timeOut);
        $query->set("date_request", $timeDate);
        $query->set("reason", $reason);
        $query->set("request_status", $reqStatus);
        $query->set("cert_by", $user2);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    }else if($reqStatus == "Approved"){
        $query->set("time_in", $timeIn);
        $query->set("time_out", $timeOut);
        $query->set("date_request", $timeDate);
        $query->set("reason", $reason);
        $query->set("request_status", $reqStatus);
        $query->set("app_by", $user1);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    }else{
        $query->set("time_in", $timeIn);
        $query->set("time_out", $timeOut);
        $query->set("date_request", $timeDate);
        $query->set("reason", $reason);
        $query->set("request_status", $reqStatus);
        $query->set("cert_by", $user2);
        $query->set("app_by", $user1);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    }
        
    $query->save();
}
/*-------------------------------------END OF OFFSET----------------------------------------------*/

/*-------------------------------------RULES----------------------------------------------*/
function getRules(){
    $query = ORM::forTable("rules")->tableAlias("t1")->innerJoin("shift", array("t1.shift_uid", "=", "t2.shift_uid"), "t2")->where("t1.status", 1)->where("t2.status", 1)->groupBy("t1.rule_uid")->orderByAsc("t1.day")->findMany();
    return $query;
}   
function getRuless(){
    $query = ORM::forTable("rules")->tableAlias("t1")->innerJoin("shift", array("t1.shift_uid", "=", "t2.shift_uid"), "t2")->where("t1.status", 1)->where("t2.status", 1)->groupBy("t1.rule_uid")->orderByAsc("t1.rule_name")->findMany();
    return $query;
}

function getRuleByUid($id){
    $query = ORM::forTable("rules")->tableAlias("t1")->innerJoin("shift", array("t1.shift_uid", "=", "t2.shift_uid"), "t2")->where("t1.status", 1)->where("t2.status", 1)->where("rule_uid", $id)->orderByAsc("t1.day")->findMany();
    return $query;
}

function getRuleByUidAndDay($uid, $day){
    $query = ORM::forTable("rules")->tableAlias("t1")->innerJoin("shift", array("t1.shift_uid", "=", "t2.shift_uid"), "t2")->where("t1.status", 1)->where("t2.status", 1)->where("t1.rule_uid", $uid)->where("t1.day", $day)->findOne();
    return $query;
}

function updateRules($ruleUid, $day, $shift, $dateModified, $status){
    $query = ORM::forTable("rules")->where("rule_uid", $ruleUid)->where("day", $day)->findOne();
        $query->set("shift_uid", $shift);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function getRuleByEmpUid($id){
    $query = ORM::forTable("rule_assignment")->tableAlias("t1")->innerJoin("rules", array("t1.rule_uid", "=", "t2.rule_uid"), "t2")->where("t1.status", 1)->where("t1.emp_uid", $id)->findOne();
    return $query;
}

function getShiftUidInRules($id, $day){
    $query = ORM::forTable("rule_assignment")->tableAlias("t1")->select("t2.shift_uid", "shift")->innerJoin("rules", array("t1.rule_uid", "=", "t2.rule_uid"), "t2")->where("t1.emp_uid", $id)->where("t2.day", $day)->where("t1.status", 1)->findOne();

    $valid = 0;
    if($query){
        $valid = $query;
    }
    return $valid;
}

function countRuleByEmpUid($id){
    $query = ORM::forTable("rule_assignment")->where("emp_uid", $id)->where("status", 1)->count();
    return $query;
}

function newEmpRule($ruleUid, $rule, $uid, $dateCreated, $dateModified){
    $query = ORM::forTable("rule_assignment")->create();
        $query->rule_assignment_uid = $ruleUid;
        $query->emp_uid = $uid;
        $query->rule_uid = $rule;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
    $query->save();
}

function getRulesByUid($uid){
    $query = ORM::forTable("rule_assignment")->where("rule_assignment_uid", $uid)->findOne();
    return $query;
}

function getRuleByRuleUid($id, $day){
    $query = ORM::forTable("rules")->where("rule_uid", $id)->where("day", $day)->findOne();
    return $query;
}

function updateRuleAssignment($uid, $rule, $dateModified, $status){
    $query = ORM::forTable("rule_assignment")->where("rule_assignment_uid", $uid)->findOne();
        $query->set("rule_uid", $rule);
        $query->set("date_modified", $dateModified);
        $query->set("status", $status);
    $query->save();
}

function getRuleShiftByTimeLogUid($uid){
    $query = ORM::forTable("rules")
        ->rawQuery("SELECT t1.shift_uid as shiftUid FROM rules as t1 INNER JOIN time_log as t3 ON t1.shift_uid = t3.shift_uid WHERE t3.time_log_uid = :uid LIMIT 1", array("uid" => $uid))
        ->findOne();
    return $query;
}
/*-------------------------------------END OF RULES----------------------------------------------*/

/*-------------------------------------LOCATION----------------------------------------------*/
function getLocation(){
    $query = ORM::forTable("locations")->where("status", "1")->findMany();
    return $query;
}

function getNearestLocation($long, $lat){
    $query = ORM::forTable("locations")
        ->rawQuery("SELECT id, uid, name, longitude, latitude, status, ABS(longitude - :long) AS nearestLong, ABS(latitude - :lat) AS nearestLat FROM  locations ORDER BY ABS(longitude - :long) , ABS(latitude - :lat) LIMIT 1", array("long" => $long, "lat" => $lat))
        ->findOne();

    return $query;
}

function getLocationByCoords($long, $lat){
    $query = ORM::forTable("locations")
        ->rawQuery("SELECT * FROM locations WHERE longitude = :long AND latitude = :lat AND status = 1", array("long" => $long, "lat" => $lat))->findOne();

    return $query;
}
function getLocationsByUid($uid){
    $query = ORM::forTable("locations")->where("uid", $uid)->where("status", "1")->findOne();
    return $query;
}

function checkLocationExisting($name, $fingerprint){
    $query = ORM::forTable("locations")->where("fingerprint", $fingerprint)->where("name", $name)->where("status", 1)->count();
    return $query;
}

function addLocations($uid, $name, $fingerprint){
    $query = ORM::forTable("locations")->create();
        $query->uid = $uid;
        $query->name = $name;
        $query->fingerprint = $fingerprint;
    $query->save();
}

function editLocations($uid, $name, $fingerprint,$status){
    $query = ORM::forTable("locations")->where("uid", $uid)->where("status", "1")->findOne();
        $query->set("name", $name);
        $query->set("fingerprint", $fingerprint);
        $query->set("status", $status);
    $query->save();
}

function addEventLog($emp, $sDate, $sTime, $logType, $locName, $locUid, $dateCreated, $dateModified){
    $query = ORM::forTable("event_log")->create();
        $query->user_id = $emp;
        $query->sdate = $sDate;
        $query->stime = $sTime;
        $query->log = $logType;
        $query->location = $locName;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;
        $query->location_uid = $locUid;

    $query->save();
}

function addAttemptLog($attemptUid, $username, $sDate, $sTime, $logType, $locationUid, $device, $ip, $dateCreated, $dateModified){
    $query = ORM::forTable("attempts_log")->create();
        $query->attempt_uid = $attemptUid;
        $query->user_id = $username;
        $query->sdate = $sDate;
        $query->stime = $sTime;
        $query->log = $logType;
        $query->device = $device;
        $query->ip_address = $ip;
        $query->location_code = $locationUid;
        $query->date_created = $dateCreated;
        $query->date_modified = $dateModified;

    $query->save();
}

function editEventLogTimeIn($empNumber, $inDate, $inHour, $timeInDate, $timeInHour){
    $query = ORM::forTable("event_log")->where("user_id", $empNumber)->where("sdate", $inDate)->where("log", "IN")->orderByDesc("id")->limit(1)->findOne();
        $query->set("sdate", $timeInDate);
        $query->set("stime", $timeInHour);
    $query->save();
}

function editEventLogTimeOut($empNumber, $outDate, $outHour, $timeOutDate, $timeOutHour){
    $query = ORM::forTable("event_log")->where("user_id", $empNumber)->where("sdate", $outDate)->where("log", "OUT")->orderByDesc("id")->limit(1)->findOne();
        $query->set("sdate", $timeOutDate);
        $query->set("stime", $timeOutHour);
    $query->save();
}
/*-------------------------------------LOCATION END----------------------------------------------*/
    // getDailySalaryByEmpUid($id);
function summaryById($startDate, $endDate, $id){
    $response = array();
    $time = timeSummaryByUid($startDate, $endDate, $id);
    foreach($time as $times){
        $date = $times["dates"];
        $prompt = $times["prompt"];
        $tardiness = $times["tardiness"];
        $late = $times["late"];
        $undertime = $times["undertime"];
        $overtime = $times["overtime"];
        $work = $times["work"];
        $oTstatus = $times["oTstatus"];
        $nightDiffStatus = $times["nightDiffStatus"];
        $nightHours = $times["nightHours"];

        //variables
        $absentDate = 0;
        $holidayDate = 0;
        $workedDate = 0;
        $restDate = 0;

        if($prompt === 1){
            $workedDate = $date;
            $prompt = 1;
            // echo "WORKED: $date<br/>";
        }else if($prompt === 0){
            $noDate = $date;
            $prompt = 0;
            // echo "NOTIME: $NOTIME<br/>";
        }else if($prompt === 2){
            $restDate = $date;
            $prompt = 2;
            // echo "REST DAY: $date<br/>";
        }else if($prompt === 3){
            $holidayDate = $date;
            $prompt = 3;
            // echo "HOLIDAY: $date<br/>";
        }else if($prompt === 4){
            $leaveDate = $date;
            $prompt = 4;
            // echo "LEAVE: $date<br/>";
        }else if($prompt === 5){
            $absentDate = $date;
            $prompt = 5;
            // echo "LEAVE: $date<br/>";
        }else if($prompt === 6){
            $workedDate = $date;
            $prompt = 6;
            // echo "OFFSET: $date<br/>";
        }//end of getting prompt

        switch ($prompt){
            case 0:
                $response[] = array(
                    "id" => $id,
                    "date" => $date,
                    "work" => $work,
                    "late" => $late,
                    "overtime" => $overtime,
                    "undertime" => $undertime,
                    "rate" => null,
                    "code" => null,
                    "nightDiffStatus" => null,
                    "nightHours" => null,
                    "oTstatus" => $oTstatus
                );
                break;
            case 1:
            case 6: 
                $overtimes = $overtime;
                $undertimes = $undertime;
                $lates = $late;
                $works = $work;
                $dateName = date("l", strtotime($date));

                $request = getOvertimeRequestByDateAndEmpUid($date, $id);
                $requestName = $request["overtime_type_name"];
                $requestCode = $request["overtime_type_code"];
                $reqStartDate = $request["start_date"];
                $reqRate = $request["rate"];
                $hours = $request["hours"];

                // echo "$requestCode<br/>";
                if($request){
                    $oTstatus = 1;
                    $response[] = array(
                        "id" => $id,
                        "date" => $reqStartDate,
                        "work" => $works,
                        "late" => $lates,
                        "overtime" => $hours,
                        "undertime" => $undertimes,
                        "rate" => $reqRate,
                        "code" => $requestCode,
                        "nightDiffStatus" => $nightDiffStatus,
                        "nightHours" => $nightHours,
                        "oTstatus" => $oTstatus
                    );
                }else{
                    $oTstatus = 0;
                    $reqStartDate = 0;
                    $response[] = array(
                        "id" => $id,
                        "date" => $date,
                        "work" => $works,
                        "late" => $lates,
                        "overtime" => 0,
                        "undertime" => $undertimes,
                        "rate" => null,
                        "code" => null,
                        "nightDiffStatus" => $nightDiffStatus,
                        "nightHours" => $nightHours,
                        "oTstatus" => $oTstatus
                    );
                }
                break;
            case 2:
                $overtime = $overtime;
                $undertime = $undertime;
                $late = $late;
                $work = $work;
                $dateName = date("l", strtotime($date));

                $restday = getRestDay();
                $restArray = array();
                foreach($restday as $restdays){
                    $restName = $restdays["name"];
                    $restArray = array();
                    if($dateName === $restName){
                        $request = getOvertimeRequestByDateAndEmpUid($date, $id);

                        if($request){
                            $oTstatus = 1;
                        }else{
                            $oTstatus = 0;
                        }
                        $restArray = array(
                            "id" => $id,
                            "restdates" => $date,
                            "work" => $work,
                            "late" => $late,
                            "name" => $request["overtime_type_name"],
                            "oTstatus" => $oTstatus,
                            "restPrompt" => true
                        );
                    }else{
                        $restArray = array(
                            "id" => $id,
                            "restdates" => 0,
                            "work" => 0,
                            "late" => 0,
                            "name" => 0,
                            "oTstatus" => 0,
                            "restPrompt" => false
                        );
                    }//end of comparing date name
                }//end of getting rest days
                $restdates = $restArray["restdates"];
                $restPrompt = $restArray["restPrompt"];
                $restWork = $restArray["work"];
                $restLate = $restArray["late"];
                $restOTstatus = $restArray["oTstatus"];
                $restName = $restArray["name"];

                $request = getOvertimeRequestByDateAndEmpUid($restdates, $id);
                $requestName = $request["overtime_type_name"];
                $requestCode = $request["overtime_type_code"];
                $reqStartDate = $request["start_date"];
                $reqRate = $request["rate"];
                $hours = $request["hours"];
                if($request){
                    $oTstatus = 1;
                    $response[] = array(
                        "id" => $id,
                        "date" => $reqStartDate,
                        "work" => $work,
                        "late" => $late,
                        "overtime" => $hours,
                        "undertime" => $undertime,
                        "rate" => $reqRate,
                        "code" => $requestCode,
                        "nightDiffStatus" => $nightDiffStatus,
                        "nightHours" => $nightHours,
                        "oTstatus" => $oTstatus
                    );
                }else{
                    $oTstatus = 0;
                    $reqStartDate = 0;
                    $response[] = array(
                        "id" => $id,
                        "date" => $date,
                        "work" => $work,
                        "late" => $late,
                        "overtime" => 0,
                        "undertime" => $undertime,
                        "rate" => null,
                        "code" => null,
                        "nightDiffStatus" => $nightDiffStatus,
                        "nightHours" => $nightHours,
                        "oTstatus" => $oTstatus
                    );
                }
                break;
            case 3:
                $overtime = $overtime;
                $undertime = $undertime;
                $late = $late;
                $work = $work;
                $dateName = date("l", strtotime($date));

                $holidayDates = array();
                $holiday = getHoliday();

                foreach($holiday as $holidays){
                    $dataHolidayDate = $holidays["date"];

                    if($date === $dataHolidayDate){
                        $request = getOvertimeRequestByDateAndEmpUid($date, $id);

                        if($request){
                            $oTstatus = 1;
                        }else{
                            $oTstatus = 0;
                        }
                        $holidayDates = array(
                            "id" => $id,
                            "holidayDates" => $dataHolidayDate,
                            "work" => $work,
                            "late" => $late,
                            "name" => $request["overtime_type_name"],
                            "oTstatus" => $oTstatus,
                            "holidayPrompt" => true
                        );
                    }else{
                        $holidayDates = array(
                            "id" => $id,
                            "holidayDates" => 0,
                            "work" => 0,
                            "late" => 0,
                            "name" => 0,
                            "oTstatus" => 0,
                            "holidayPrompt" => false
                        );
                    }
                }//end of getting holidays
                $dataHoliday = $holidayDates["holidayDates"];
                $holidayPrompt = $holidayDates["holidayPrompt"];
                $holidayWork = $holidayDates["work"];
                $holidayLate = $holidayDates["late"];
                $holidayOTstatus = $holidayDates["oTstatus"];
                $holidayName = $holidayDates["name"];

                $request = getOvertimeRequestByDateAndEmpUid($dataHoliday, $id);
                $requestName = $request["overtime_type_name"];
                $requestCode = $request["overtime_type_code"];
                $reqStartDate = $request["start_date"];
                $reqRate = $request["rate"];
                $hours = $request["hours"];
                if($request){
                    $oTstatus = 1;
                    $response[] = array(
                        "id" => $id,
                        "date" => $date,
                        "work" => $work,
                        "late" => $late,
                        "overtime" => $hours,
                        "undertime" => $undertime,
                        "rate" => $reqRate,
                        "code" => $requestCode,
                        "nightDiffStatus" => $nightDiffStatus,
                        "nightHours" => $nightHours,
                        "oTstatus" => $oTstatus
                    );
                }else{
                    $oTstatus = 0;
                    $reqStartDate = 0;
                    $response[] = array(
                        "id" => $id,
                        "date" => $date,
                        "work" => $work,
                        "late" => $late,
                        "overtime" => 0,
                        "undertime" => $undertime,
                        "rate" => null,
                        "code" => null,
                        "nightDiffStatus" => $nightDiffStatus,
                        "nightHours" => $nightHours,
                        "oTstatus" => $oTstatus
                    );
                }

                break;
            case 4: 
                $leaves = getLeaveByEmpUid($id);
                $leaveCode = "";
                foreach($leaves as $leave){
                    $leaveCode = $leave["leave_code"];
                    $leaveStartDate = $leave["start_date"];
                    $leaveEndDate = $leave["end_date"];

                    if($date === $leaveStartDate || $date === $leaveEndDate){
                        $leaveCode = $leaveCode;
                    }else{
                        $leaveCode = "";
                    }//end of comparing dates
                }//end of getting employee's accepted leave

                $response[] = array(
                    "id" => $id,
                    "date" => $date,
                    "work" => $work,
                    "late" => $late,
                    "overtime" => $overtime,
                    "undertime" => $undertime,
                    "rate" => null,
                    "code" => $leaveCode,
                    "nightDiffStatus" => null,
                    "nightHours" => null,
                    "oTstatus" => $oTstatus
                );
                break;
            case 5:
                $response[] = array(
                    "id" => $id,
                    "date" => $date,
                    "work" => $work,
                    "late" => $late,
                    "overtime" => $overtime,
                    "undertime" => $undertime,
                    "rate" => null,
                    "code" => null,
                    "nightDiffStatus" => null,
                    "nightHours" => null,
                    "oTstatus" => $oTstatus
                );
                break;
        }//end of switch
    }//end of getting time data
    $response = array_map('unserialize', array_unique(array_map('serialize', $response)));
    // echo jsonify($response);
    return $response;
}

function overtimeWithRate($startDate, $endDate, $id){
    // $emps = getEmployeeByCostCenterUid($uid);
    // foreach($emps as $emp){
    //     $id = $emp["emp_uid"];
        $summaries = summaryById($startDate, $endDate, $id);
        foreach($summaries as $summary){
            $id2 = $summary["id"];
            $rate = $summary["rate"];
            $code = $summary["code"];
            $date = $summary["date"];
            $nightDiffStatus = $summary["nightDiffStatus"];
            $nightHours = $summary["nightHours"];
            $date2 = date("Y-m-d", strtotime($summary["date"]));
            $ratePercent = $rate * 100;
            $ratePercent = $ratePercent . "%";
            $ratePercents = $rate * 100;
            $ratePercents = $ratePercents . "%";
            $overtime = $summary["overtime"];
            $oTstatus = $summary["oTstatus"];


            // $realOt = $overtime - $nightHours;
            if($oTstatus == 1){
                $response[] = array(
                    "id" => $id,
                    "date" => $date,
                    "rate" => $ratePercent,
                    "code" => $code,
                    "ot" => $overtime,
                    "nightD" => $nightHours
                );
            }else if($oTstatus == 0){
                $response[] = array(
                    "id" => $id,
                    "date" => $date,
                    "rate" => null,
                    "code" => null,
                    "ot" => $overtime,
                    "nightD" => $nightHours
                );
            }//end of comparing id

        }//end of summaryById Function
    // }
    return $response;
    // echo jsonify($response);
}

function overtimeSummary($startDate, $endDate, $id){
    $x = overtimeWithRate($startDate, $endDate, $id);
    $ot = getOvertimeTypes();
    $response = array();
    foreach($ot as $ots){
        $rate = $ots["rate"];
        $rate = $rate * 100;
        $rate = $rate . "%";
        $code = $ots["overtime_type_code"];
        $ot = 0;
        $night = 0;

        foreach($x as $xx){
            $oRate = $xx["rate"];
            $oCode = $xx["code"];
            $overtime = $xx["ot"];
            $nightD = $xx["nightD"];
            // $ot = 0;
            if($code === $oCode){
                $ot += $overtime;
            }
            $night += $nightD;

        }
        $response[] = array(
            "id" => $id,
            "rate" => $rate,
            "code" => $code,
            "ot" => $ot,
            "night" => $night
        );
    }//end of overtimeWithRate Function
    // echo jsonify($response);
    return $response;

}
function getAttemptsData(){
    $query = ORM::forTable("attempts_log")->tableAlias("t1")->innerJoin("emp", array("t1.user_id", "=", "t2.emp_uid"), "t2")->innerJoin("users", array("t1.user_id", "=", "t3.emp_uid"), "t3")->where("t1.status",1)->orderByDesc("t1.id")->limit(300)->findMany();
    return $query;
}

function checkPayrollSchedBeforeRequest($requestDate){
    $curDate = date("Y-m-d");
    // $curDate = "2015-08-09";
    $response = array();

    $sched = getSchedules();
    foreach($sched as $scheds){
        // $cutoff = $scheds->cut_off;
        $payrollDate = $scheds->payroll_date;
        $cutoffDate = $scheds->cutoff_date;
        $payrollDate1 = $scheds->payroll_date;
        $cutoffDate1 = $scheds->cutoff_date;
        $month = date("m");
        $year = date("Y");

        if($payrollDate < $cutoffDate){
            $payrollDate = $year . "-" . $month . "-" . $payrollDate;
            $payrollDate = date("Y-m-d", strtotime("+1 day", strtotime($payrollDate)));

            $payrollDate = date("Y-m-d", strtotime($payrollDate));
            $cutoffDate = $year . "-" . $month . "-" . $cutoffDate;
            $cutoffDate = date("Y-m-d", strtotime("+1 day", strtotime($cutoffDate)));
            $cutoffDay = date("l", strtotime($cutoffDate));
            if($cutoffDay == "Sunday"){
                $cutoffDate = date("Y-m-d", strtotime("+1 day", strtotime($cutoffDate)));
            }
        }else{
            $payrollDate = $year . "-" . $month . "-" . $payrollDate;
            $payrollDate = date("Y-m-d", strtotime($payrollDate));
            $payrollDate = date("Y-m-d", strtotime("+1 day", strtotime($payrollDate)));
            $cutoffDate = $year . "-" . $month . "-" . $cutoffDate;
            $cutoffDate = date("Y-m-d", strtotime($cutoffDate));
            $cutoffDate = date("Y-m-d", strtotime("+1 month", strtotime($cutoffDate)));
            $cutoffDate = date("Y-m-d", strtotime("+1 day", strtotime($cutoffDate)));
            $cutoffDay = date("l", strtotime($cutoffDate));
            if($cutoffDay == "Sunday"){
                $cutoffDate = date("Y-m-d", strtotime("+1 day", strtotime($cutoffDate)));
            }
        }
        $payrollDateStr = strtotime($payrollDate);
        $cutoffDateStr = strtotime($cutoffDate);
        $currentDateStr = strtotime($curDate);
        $payrollDate = date("Y-m-d", strtotime("-1 day", strtotime($payrollDate)));

        // echo "$curDate === $payrollDate = $cutoffDate<br/>";
        // echo "$currentDateStr === $payrollDateStr = $cutoffDateStr<br/>";
        if($currentDateStr <= $cutoffDateStr && $currentDateStr >= $payrollDateStr){
            $validPayrollDate = $payrollDate;
            $validCutoffDate = $cutoffDate;
            // echo "$requestDate = $validPayrollDate = $validCutoffDate<br/>";

            if(strtotime($requestDate) <= strtotime($validCutoffDate) && strtotime($requestDate) >= strtotime($validPayrollDate)){
                $response = array(
                    "prompt" => true
                );
            }else{
                $response = array(
                    "prompt" => false
                );
            }//comparing date
        }else{
            $month1 = date("m", strtotime("-1 month"));
            if($payrollDate1 < $cutoffDate1){
                $payrollDate1 = $year . "-" . $month1 . "-" . $payrollDate1;
                $payrollDate1 = date("Y-m-d", strtotime($payrollDate1));
                $payrollDate1 = date("Y-m-d", strtotime("+1 day", strtotime($payrollDate1)));
                $cutoffDate1 = $year . "-" . $month1 . "-" . $cutoffDate1;
                $cutoffDate1 = date("Y-m-d", strtotime("+1 day", strtotime($cutoffDate1)));
                $cutoffDay1 = date("l", strtotime($cutoffDate1));
                if($cutoffDay1 == "Sunday"){
                    $cutoffDate1 = date("Y-m-d", strtotime("+1 day", strtotime($cutoffDate1)));
                }
            }else{
                $payrollDate1 = $year . "-" . $month1 . "-" . $payrollDate1;
                $payrollDate1 = date("Y-m-d", strtotime($payrollDate1));
                $payrollDate1 = date("Y-m-d", strtotime("+1 day", strtotime($payrollDate1)));
                $cutoffDate1 = $year . "-" . $month1 . "-" . $cutoffDate1;
                $cutoffDate1 = date("Y-m-d", strtotime($cutoffDate1));
                $cutoffDate1 = date("Y-m-d", strtotime("+1 month", strtotime($cutoffDate1)));
                $cutoffDate1 = date("Y-m-d", strtotime("+1 day", strtotime($cutoffDate1)));
                $cutoffDay1 = date("l", strtotime($cutoffDate1));
                if($cutoffDay1 == "Sunday"){
                    $cutoffDate1 = date("Y-m-d", strtotime("+1 day", strtotime($cutoffDate1)));
                }
            }

            $payrollDateStr1 = strtotime($payrollDate1);
            $cutoffDateStr1 = strtotime($cutoffDate1);
            $currentDateStr1 = strtotime($curDate);
            $payrollDate1 = date("Y-m-d", strtotime("-1 day", strtotime($payrollDate1)));
            // echo "$curDate === $payrollDate1 = $cutoffDate1<br/>";
            // echo "$currentDateStr === $payrollDateStr = $cutoffDateStr<br/>";
            if($currentDateStr1 <= $cutoffDateStr1 && $currentDateStr1 >= $payrollDateStr1){
                $validPayrollDate1 = $payrollDate1;
                $validCutoffDate1 = $cutoffDate1;

                // echo "$validPayrollDate1 = $validCutoffDate1<br/>";

                if(strtotime($requestDate) <= strtotime($validCutoffDate1) && strtotime($validPayrollDate1) >= strtotime($requestDate)){
                    $response = array(
                        "prompt" => true
                    );
                }else if(strtotime($requestDate) <= strtotime($validCutoffDate1) && strtotime($validPayrollDate1) <= strtotime($requestDate)){
                    $response = array(
                        "prompt" => true
                    );
                }else{
                    $response = array(
                        "prompt" => false
                    );
                }//comparing date
            }//end of getting between
        }
    }//end of getting payroll schedule

    return $response;
    // echo jsonify($response);
}

function leaveCounts(){
    $response = array();

    $year = date("Y");
    $leaves = getEmpLeaveCountPages();

    foreach($leaves as $leave){
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
            switch($leaveCode){
                case "SL":
                    $sickLeave++;
                    break;
                case "BL":
                    $birthdayLeave++;
                    break;
                case "BV":
                    $berLeave++;
                    break;
                case "VL":
                    $vacLeave++;
                    break;
                case "W":
                    $noPay++;
                    break;
                case "ML":
                    $matLeave++;
                    break;
                case "PL":
                    $patLeave++;
                    break;
            }//end of switch
        }//end of getting leave

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

        // echo "$sLTotal";

        $response[] = array(
            "leaveCountUid" => $leaveCountUid,
            "id" => $id,
            "empName" => $name,
            "empNo" => $empNo,
            "SL" => $sLTotal,
            "BL" => $bLTotal,
            "BV" => $bVTotal,
            "VL" => $vLTotal,
            "ML" => $mLTotal,
            "PL" => $pLTotal,
            "w" => $noPay,
            "year" => $year
        );

        $dateModified = date("Y-m-d H:i:s");
        $status = 1;

        // updateEmpLeaveCounts($leaveCountUid, $sLTotal, $bLTotal, $bVTotal, $vLTotal, $mLTotal, $pLTotal, $dateModified, $status);
    }//end of getEmpLeaveCountPages function

    // echo jsonify($response);
    return $response;
}

function leaveCountsByEmpUid($uid){
    $response = array();

    $year = date("Y");
    $leave = getEmpLeaveCountPagesByEmpUid($uid);

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
            switch($leaveCode){
                case "SL":
                    $sickLeave++;
                    break;
                case "BL":
                    $birthdayLeave++;
                    break;
                case "BV":
                    $berLeave++;
                    break;
                case "VL":
                    $vacLeave++;
                    break;
                case "W":
                    $noPay++;
                    break;
                case "ML":
                    $matLeave++;
                    break;
                case "PL":
                    $patLeave++;
                    break;
            }//end of switch
        }//end of getting leave

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

        // echo "$sLTotal";

        $response = array(
            "leaveCountUid" => $leaveCountUid,
            "id" => $id,
            "empName" => $name,
            "empNo" => $empNo,
            "SL" => $sLTotal,
            "BL" => $bLTotal,
            "BV" => $bVTotal,
            "VL" => $vLTotal,
            "ML" => $mLTotal,
            "PL" => $pLTotal,
            "w" => $noPay,
            "year" => $year
        );

        $dateModified = date("Y-m-d H:i:s");
        $status = 1;

        // updateEmpLeaveCounts($leaveCountUid, $sLTotal, $bLTotal, $bVTotal, $vLTotal, $mLTotal, $pLTotal, $dateModified, $status);
    }else{
        $response = array(
            "leaveCountUid" => "N/A",
            "id" => $uid,
            "empName" => "N/A",
            "empNo" => "N/A",
            "SL" => "N/A",
            "BL" => "N/A",
            "BV" => "N/A",
            "VL" => "N/A",
            "ML" => "N/A",
            "PL" => "N/A",
            "w" => "N/A",
            "year" => "N/A"
        );
    }//end of getEmpLeaveCountPages function

    // echo jsonify($response);
    return $response;
}

// function forLeaveCounts($emp){
//     $response = array();

//     $year = date("Y");
//     $leave = getEmpLeaveCountByEmp($emp);

//     if($leave){
//         $leaveCountUid = $leave["emp_leave_count_uid"];
//         $id = $leave["emp_uid"];
//         $SL = $leave["SL"];
//         $BL = $leave["BL"];
//         $BV = $leave["BV"];
//         $VL = $leave["VL"];
//         $ML = $leave["ML"];
//         $PL = $leave["PL"];
//         $sickLeave = 0;
//         $birthdayLeave = 0;
//         $berLeave = 0;
//         $vacLeave = 0;
//         $noPay = 0;
//         $matLeave = 0;
//         $patLeave = 0;

//         $leaves = getApprovedLeavesByEmpUidByYear($id, $year);
//         foreach($leaves as $leave){
//             $leaveCode = $leave["leave_code"];

//             switch($leaveCode){
//                 case "SL":
//                     $sickLeave++;
//                     break;
//                 case "BL":
//                     $birthdayLeave++;
//                     break;
//                 case "BV":
//                     $berLeave++;
//                     break;
//                 case "VL":
//                     $vacLeave++;
//                     break;
//                 case "W":
//                     $noPay++;
//                     break;
//                 case "ML":
//                     $matLeave++;
//                     break;
//                 case "PL":
//                     $patLeave++;
//                     break;
//             }//end of switch
//         }//end of getting leave

//         $sLTotal = $SL - $sickLeave;
//         if($sLTotal < 0){
//             $sLTotal = 0;
//         }
//         $bLTotal = $BL - $birthdayLeave;
//         if($bLTotal < 0){
//             $bLTotal = 0;
//         }
//         $bVTotal = $BV - $berLeave;
//         if($bVTotal < 0){
//             $bVTotal = 0;
//         }
//         $vLTotal = $VL - $vacLeave;
//         if($vLTotal < 0){
//             $vLTotal = 0;
//         }
//         $mLTotal = $ML - $matLeave;
//         if($mLTotal < 0){
//             $mLTotal = 0;
//         }
//         $pLTotal = $PL - $patLeave;
//         if($pLTotal < 0){
//             $pLTotal = 0;
//         }

//         $response = array(
//             "leaveCountUid" => $leaveCountUid,
//             "id" => $id,
//             "SL" => $sLTotal,
//             "BL" => $bLTotal,
//             "BV" => $bVTotal,
//             "VL" => $vLTotal,
//             "ML" => $mLTotal,
//             "PL" => $pLTotal,
//             "w" => $noPay,
//             "year" => $year
//         );

//         $dateModified = date("Y-m-d H:i:s");
//         $status = 1;

//         updateEmpLeaveCounts($leaveCountUid, $sLTotal, $bLTotal, $bVTotal, $vLTotal, $mLTotal, $pLTotal, $dateModified, $status);
//     }//end of getEmpLeaveCountPages function

//     // echo jsonify($response);
//     return $response;
// }

function incomeDetails($startDate, $endDate, $emp, $cost){
    $holiday = holidayPayByEmpUid($startDate, $endDate, $emp);
    $response = array();
    $salaries = getDailySalaryByEmpUid($emp);
    $summaries = timeOrganizedSummary($startDate, $endDate, $cost);
    $emps = getEmployeeSalaryDataByEmpUid($emp);
    $pag = getPagibig();
    $totalWork = 0;
    $totalLate = 0;
    $totalOt= 0;
    $totalUn = 0;
    $totalNight = 0;
    // echo jsonify($summaries);
    foreach($summaries as $summary){
        $id = $summary["id"];
        if($id === $emp){
            $work = $summary["worked"];
            $tardy = $summary["tardiness"];
            $name = $summary["name"];
            $username = $summary["username"];
            $days = $summary["days"];
        }
        // $totalLate += $late;
        // $totalUn += $undertime;
    }//end of summaryById Function
    // echo "$totalOt<br/>";
    // $tardy = $totalLate + $totalUn;
    //COMPUTATION!!!!!!!!!!
    /*-----------------------------SALARY-----------------------------*/
    $payPeriodName = $salaries["payPeriod"];
    $basicSalary = $salaries["basicSalary"];
    $lateSalary = $salaries["hourlySalary"];
    $hourlySalary = $salaries["hourlySalary"];
    $daySalary = $salaries["daySalary"];
    $monthlySalary = $salaries["monthlySalary"];
    $minSalary = $salaries["minSalary"];
    $daySalary = $salaries["daySalary"];
    /*-----------------------------END-----------------------------*/

    /*-----------------------------HOLIDAY-----------------------------*/
    $holidayCountDays = $holiday["holidayCount"];
    $holidayPay = $holiday["holidayPay"];
    /*-----------------------------END-----------------------------*/

    if($holidayPay != 0){
        $days = $days - $holidayCountDays;
    }
    /*------------------getting contribution numbers------------------*/
    $sssNo = $emps["sss_no"];
    $philhealthNo = $emps["philhealth_no"];
    $pagibigNo = $emps["pagibig_no"];
    $taxNo = $emps["tax_no"];
    /*------------------END------------------*/

    switch($payPeriodName){
        case "Daily":
            $cutoffSalary = $days * $daySalary;
            // $cutoffSalary = $monthlySalary / 2;
            break;
        
        default:
            $halfMonthlySalary = $monthlySalary / 2;
            $forHoliday = $daySalary * $holidayCountDays;
            $cutoffSalary = ($halfMonthlySalary - $forHoliday) + $holidayPay;
            break;
    }

    // echo "$hourlySalary<br/>";
    $empTardySalary = $minSalary * $tardy;
    // echo "$days = $cutoffSalary = $forHoliday = $holidayPay<br/>";

    // echo "$empTardySalary = $minSalary = $tardy<br/>";
    //ALLOWANCE
    $allowance = getAllowanceByEmpUid($emp, $startDate, $endDate);
    $totalAllowance = 0;
    $mealTotal = 0;
    $transpoTotal = 0;
    $colaTotal = 0;
    $otherTotal = 0;
    if($allowance){
        $mealTotal = $allowance["mealTotal"];
        $transpoTotal = $allowance["transpoTotal"];
        $colaTotal = $allowance["colaTotal"];
        $otherTotal = $allowance["otherTotal"];
    }
    $totalAllowance = $mealTotal + $transpoTotal + $colaTotal + $otherTotal;
    //ADJUSTMENT
    $adjustments = getAdjustmentByEmpUid($emp, $startDate, $endDate);
    $adjustment = 0;
    if($adjustments){
        $adjustment = $adjustments->amount;
    }
    // //OVERTIME
    $overtimeSummaries = overtimeSummary($startDate, $endDate, $emp);
    // // echo jsonify($overtimeSummaries);
    $totalOtPay = 0;
    $nightPay = 0;
    foreach ($overtimeSummaries as $overtimeSummary) {
        $rate = $overtimeSummary["rate"];
        $code = $overtimeSummary["code"];
        $ot = $overtimeSummary["ot"];
        $night = $overtimeSummary["night"];
        $rateDecimal = floatval($rate) / 100;
        $otPay = $hourlySalary * $rateDecimal;
        $nightD = .10;
        switch ($code) {
            case "RDOT":
                $totalOtPay = $ot * $otPay;
                $nightPay = $hourlySalary * $nightD * $rateDecimal * $night;
                break;
            case "SHOT":
                $totalOtPay = $ot * $otPay;
                $nightPay = $hourlySalary * $nightD * $rateDecimal * $night;
                break;
            case "SHROT":
                $totalOtPay = $ot * $otPay;
                $nightPay = $hourlySalary * $nightD * $rateDecimal * $night;
                break;
            case "RHOT":
                $totalOtPay = $ot * $otPay;
                $nightPay = $hourlySalary * $nightD * $rateDecimal * $night;
                break;
            case "RHROT":
                $totalOtPay = $ot * $otPay;
                $nightPay = $hourlySalary * $nightD * $rateDecimal * $night;
                break;
            case "RegOT":
                $totalOtPay = $ot * $otPay;
                $nightPay = $hourlySalary * $nightD * $night;
                break;
        }
        $totalOtPay += $totalOtPay; 
        $nightPay += $nightPay; 
    }
    // echo "$nightPay<br/>";

    $totalEmpOtPay = $totalOtPay + $nightPay;

    if($totalEmpOtPay < 0){
        $totalEmpOtPay = 0;
    }
    $grossSalary = ($cutoffSalary + $totalEmpOtPay + $totalAllowance + $adjustment) - $empTardySalary;
    // echo "$totalWork = $totalAllowance = $adjustment = $totalOtPay = $nightPay = $empTardySalary<br/>";
    $dep = getDailySalaryByEmpUid($emp);
    $response = array();

    $empSalary = $dep["monthlySalary"];
    $check = checkUserHasBenefits($emp);

    if($check){
        $benefits = getEmpBenefitsByEmpUid($emp);
        // SSS
        if(!$sssNo){
            $sssStartRange = 0;
            $sssEndRange = 0;
            $ssEmployee = 0;
            $ssEmployer = 0;
            $ssTotal = 0;
        }else{
            $ssEmployee = $benefits["emp_sss"];
            $ssEmployer = 0;
            $ssTotal = 0;
        }//end of getting sss number

        //PHILHEALTH
        if(!$philhealthNo){
            $pStart = 0;
            $pEnd = 0;
            $salaryBase = 0;
            $philTotal = 0;
            $philEmployer = 0;
            $philEmployee = 0;
        }else{
            $salaryBase = 0;
            $philTotal = 0;
            $philEmployer = 0;
            $philEmployee = $benefits["emp_philhealth"];
        }

        //HDMF
        if(!$pagibigNo){
            $totalPagibig = 0;
        }else{
            $totalPagibig = $benefits["emp_pagibig"];
        }
    }else{
        $ss = getSSSBySalary($empSalary);
        // SSS
        if($ss){
            if(!$sssNo){
                $sssStartRange = 0;
                $sssEndRange = 0;
                $ssEmployee = 0;
                $ssEmployer = 0;
                $ssTotal = 0;
            }else{
                $sssStartRange = $ss["rangeOfComp"];
                $sssEndRange = $ss["rangeOfCompEnd"];
                $ssEmployee = $ss["sssEe"];
                $ssEmployer = $ss["sssEr"];
                $ssTotal = $ss["sssTotal"];
            }//end of getting sss number
        }//end of getting sss

        // PHILHEALTH
        $philhealth = getPhilhealthBySalary($empSalary);
        if($philhealth){
            if(!$philhealthNo){
                $pStart = 0;
                $pEnd = 0;
                $salaryBase = 0;
                $philTotal = 0;
                $philEmployer = 0;
                $philEmployee = 0;
            }else{
                $pStart = $philhealth["salaryRange"];
                $pEnd = $philhealth["salaryRangeEnd"];
                $salaryBase = $philhealth["salaryBase"];
                $philTotal = $philhealth["totalMonthlyPremium"];
                $philEmployer = $philhealth["employerShare"];
                $philEmployee = $philhealth["employeeShare"];
            }
        }// end of getting philhealth

        //PAG-IBIG
        foreach($pag as $pagibig){
            $pagStart = $pagibig["pagibigGrossPayRange"];
            $pagEnd = $pagibig["pagibigGrossPayRangeEnd"];
            $pagEmp = $pagibig["pagibigEmployer"];
            $pagTotal = $pagibig["pagibigTotal"];

            if(!$pagibigNo){
                $totalPagibig = 0;
            }else{
                $totalPagibig = "100";

                // if($empSalary >= $pagStart && $empSalary <= $pagEnd){
                //     // echo "GROSS PAY RANGE: &nbsp" . $pagStart . " - " . $pagEnd . " : " . $pagTotal . "<br/>";
                //     $totalPagibig = $empSalary * $pagTotal;
                //     // echo "<td>" . $totalPagibig . "</td>";
                // }else if($empSalary <= $pagStart && $empSalary >= $pagEnd){
                //     // echo "GROSS PAY RANGE: &nbsp" . $pagStart . " - " . $pagEnd . " : " . $pagTotal . "<br/>";
                //     // $totalPagibig = "100";
                //     $totalPagibig = $empSalary * $pagTotal;
                //     // echo "<td>" . $totalPagibig . "</td>";
                // }
            }
        }//end of getting pag-ibig
    }//end of checking if user has benefits data

    // $totalPagibig = "100";

    //NET PAY
    $totalContri = $ssEmployee + $philEmployee + $totalPagibig;
    $netPay = $grossSalary - $totalContri;
    // echo "$totalWork = $totalAllowance = $adjustment = $totalOtPay = $nightPay = $empTardySalary<br/>";
    $response = array(
        "emp" => $emp,
        "name" => $name,
        "startDate" => date("F d, Y", strtotime($startDate)),
        "endDate" => date("F d, Y", strtotime($endDate)),
        "username" => $username,
        "daySalary" => $daySalary,
        "hourly" => $hourlySalary,
        "minutes" => $minSalary,
        "overtime" => $totalEmpOtPay,
        "allowance" => $totalAllowance,
        "basicSalary" => $monthlySalary,
        "adjustment" => $adjustment,
        "days" => $days,
        "cutoffSalary" => number_format($cutoffSalary, 2),
        "grossSalary" => number_format($grossSalary, 2),
        "tardy" => $tardy,
        "tardySalary" => number_format($empTardySalary, 2),
        "totalSss" => $ssTotal,
        "sssEmployee" => number_format($ssEmployee, 2),
        "sssEmployer" => number_format($ssEmployer, 2),
        "totalPhilhealth" => number_format($philTotal, 2),
        "philEmployee" => number_format($philEmployee, 2),
        "philEmployer" => number_format($philEmployer, 2),
        "pagibig" => number_format($totalPagibig, 2),
        "totalContri" => number_format($totalContri, 2),
        "netPay" => number_format($netPay, 2),
        "holidayPay" => number_format($holidayPay, 2),
        "taxNo" => $taxNo
    );
    // echo jsonify($response);
    return $response;
}

function getTaxByCostCenter($startDate, $endDate, $emp, $cost){
    $dependents = getValidDependentCountByEmpUid($emp);
    $cashes = incomeDetails($startDate, $endDate, $emp, $cost);
    $empData = getEmployeeSalaryDataByEmpUid($emp);
    $payperiodData = getPayperiodAndSalaryByEmpUid($emp);
    $costcenter = getSingleCostCenterDataByEmpUid($emp);
    $payperiodUid = $costcenter["pay_period_uid"];
    // $basicSalary = $payperiodData["base_salary"];
    // echo "$payperiodUid<br/>";
    $tax = getTax($payperiodUid);
    $exemp = getExemption($payperiodUid);
    $empStatus = $empData["marital"];

    if($cashes){
        $name = $cashes["name"];
        $empNo = $cashes["username"];
        $basicSalary = $cashes["basicSalary"];
        $overtime = $cashes["overtime"];
        $allowance = $cashes["allowance"];
        $overtime = $cashes["overtime"];
        $tardy = $cashes["tardySalary"];
        $totalContri = $cashes["totalContri"];
        $netPay = $cashes["netPay"];
        $days = $cashes["days"];
        $daySalary = $cashes["daySalary"];
        $hourlySalary = $cashes["hourly"];
        $minutesSalary = $cashes["minutes"];
        $grossSalary = $cashes["grossSalary"];
        $cutoffSalary = $cashes["cutoffSalary"];
        $adjustment = $cashes["adjustment"];
        $sssEmployee = $cashes["sssEmployee"];
        $sssEmployer = $cashes["sssEmployer"];
        $totalSss = $cashes["totalSss"];
        $philEmployee = $cashes["philEmployee"];
        $philEmployer = $cashes["philEmployer"];
        $totalPhilhealth = $cashes["totalPhilhealth"];
        $pagibig = $cashes["pagibig"];
        $holidayPay = $cashes["holidayPay"];
        $taxNo = $cashes["taxNo"];

        $taxableIncome = ($basicSalary + $overtime) - ($tardy + $allowance + $totalContri);
    }else{
        $taxableIncome = null;
    }

    if($dependents){
        $dependentCount = $dependents["dependentValidCount"];
    }else{
        $dependentCount = null;
    }

    switch($dependentCount){
        case "0":
            $singleStatus = "S/ME";
            // echo "$id - $singleStatus<br/>";
            break;
        case "1":
            $singleStatus = "ME1/S1";
            // echo "$id - $singleStatus<br/>";
            break;
        case "2":
            $singleStatus = "ME2/S2";
            // echo "$id - $singleStatus<br/>";
            break;
        case "3":
            $singleStatus = "ME3/S3";
            // echo "$id - $singleStatus<br/>";
            break;
        case "4":
            $singleStatus = "ME4/S4";
            // echo "$id - $singleStatus<br/>";
            break;
        default:
            $singleStatus = "ME4/S4";
            // echo "$id - $singleStatus<br/>";
            break;
    }//end of switch for count

    foreach($exemp as $ex){
        $exId = $ex["e_id"];
        $exExemption = $ex["exemption"];
        $exStatus = $ex["status"];

        foreach($tax as $taxx){
            $sample1 = $taxx["id1"];
            $sample2 = $taxx["id2"];
            $exemption = $taxx["exemption"];
            $taxStatus = $taxx["status"];
            $depMarital = $taxx["dep_status"];
            $one = $taxx["no_dep_1"];
            $two = $taxx["no_dep_2"];   
            $three = $taxx["no_dep_3"];
            $four = $taxx["no_dep_4"];
            $five = $taxx["no_dep_5"];
            $six = $taxx["no_dep_6"];
            $seven = $taxx["no_dep_7"];
            $eight = $taxx["no_dep_8"];

            if($singleStatus == $depMarital){
                if($taxableIncome >= $one && $taxableIncome <= $two){
                    $number = 1;
                    if($number == $exId){
                        // echo "1<br/>";
                        $una = $taxableIncome - $one;
                        $dalawa = $una * $exStatus;
                        $tatlo = $dalawa + $exExemption;
                        // echo "<br/>WITHHOLDING TAX: $id - $tatlo <br/>";
                    }
                }else if($taxableIncome >= $two && $taxableIncome <= $three){
                    $number = 2;
                    if($number == $exId){
                        // echo "2<br/>";
                        $una = $taxableIncome - $two;
                        $dalawa = $una * $exStatus;
                        $tatlo = $dalawa + $exExemption;
                        // echo "<br/>WITHHOLDING TAX: $id - $tatlo <br/>";
                    }
                }else if($taxableIncome >= $three && $taxableIncome <= $four){
                    $number = 3;
                    if($number == $exId){
                        // echo "3<br/>";
                        $una = $taxableIncome - $three;
                        $dalawa = $una * $exStatus;
                        $tatlo = $dalawa + $exExemption;
                        // echo "<br/>WITHHOLDING TAX: $id - $tatlo <br/>";
                    }
                }else if($taxableIncome >= $four && $taxableIncome <= $five){
                    $number = 4;
                    if($number == $exId){
                        // echo "4<br/>";
                        $una = $taxableIncome - $four;
                        $dalawa = $una * $exStatus;
                        $tatlo = $dalawa + $exExemption;
                        // echo "<br/>WITHHOLDING TAX: $id - $tatlo <br/>";
                    }
                }else if($taxableIncome >= $five && $taxableIncome <= $six){
                    $number = 5;
                    if($number == $exId){
                        // echo "5<br/>";
                        $una = $taxableIncome - $five;
                        $dalawa = $una * $exStatus;
                        $tatlo = $dalawa + $exExemption;
                        // echo "<br/>WITHHOLDING TAX: $id - $tatlo <br/>";
                    }
                }else if($taxableIncome >= $six && $taxableIncome <= $seven){
                    $number = 6;
                    if($number == $exId){
                        // echo "6<br/>";
                        $una = $taxableIncome - $six;
                        $dalawa = $una * $exStatus;
                        $tatlo = $dalawa + $exExemption;
                        // echo "<br/>WITHHOLDING TAX: $id - $tatlo <br/>";
                    }
                }else if($taxableIncome >= $seven && $taxableIncome <= $eight){
                    $number = 7;
                    if($number == $exId){
                        // echo "7<br/>";
                        $una = $taxableIncome - $seven;
                        $dalawa = $una * $exStatus;
                        $tatlo = $dalawa + $exExemption;
                        // echo "<br/>WITHHOLDING TAX: $id - $tatlo <br/>";
                    }
                }else  if($taxableIncome >= $eight){
                    $number = 8;
                    if($number == $exId){
                        // echo "8<br/>";
                        $una = $taxableIncome - $eight;
                        $dalawa = $una * $exStatus;
                        $tatlo = $dalawa + $exExemption;
                        // echo "<br/>WITHHOLDING TAX: $id - $tatlo <br/>";
                    }
                }
            }//end of comparing marital
        }//end of tax
    }//end of exemption

    if(!$taxNo){
        $tatlo = 0;
    }else{
        $tatlo = $tatlo;
    }

    $response = array(
        "id" => $emp,
        "name" => $name,
        "empNo" => $empNo,
        "daySalary" => $daySalary,
        "hourlySalary" => $hourlySalary,
        "minutesSalary" => $minutesSalary,
        "overtimeSalary" => $overtime,
        "allowance" => $allowance,
        "basicSalary" => $basicSalary,
        "adjustment" => $adjustment,
        "days" => $days,
        "cutoffSalary" => $cutoffSalary,
        "grossSalary" => $grossSalary,
        "tardySalary" => $tardy,
        "totalSss" => $totalSss,
        "sssEmployee" => $sssEmployee,
        "sssEmployer" => $sssEmployer,
        "totalPhilhealth" => $totalPhilhealth,
        "philEmployee" => $philEmployee,
        "philEmployer" => $philEmployer,
        "pagibig" => $pagibig,
        "totalContri" => $totalContri,
        "holidayPay" => $holidayPay,
        "netPay" => $netPay,
        "loans" => 0,
        "pettyCash" => 0,
        "tax" => number_format($tatlo, 2),
    );
    // echo "$taxableIncome = $tatlo<br/>";

    // echo jsonify($response);
    return $response;
}
?>