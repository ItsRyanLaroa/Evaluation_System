<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

// admin_class.php
function login(){
    extract($_POST);

    // Query for admin (using email as username)
    $qry_admin = $this->db->query("SELECT *, concat(firstname,' ',lastname) as name FROM users WHERE email = '".$email."' AND password = '".md5($password)."'");

    // Query for faculty (using school_id as username)
    $qry_faculty = $this->db->query("SELECT *, concat(firstname,' ',lastname) as name FROM faculty_list WHERE email = '".$email."' AND password = '".md5($password)."'");

    // Query for student (using school_id as username) and active status
    $qry_student = $this->db->query("SELECT *, concat(firstname,' ',lastname) as name FROM student_list WHERE school_id = '".$email."' AND password = '".md5($password)."' AND status = 'active'");

    // Check for admin login (still using email)
    if($qry_admin->num_rows > 0){
        $qry = $qry_admin;
        $login_type = 1; // Admin
        $view_folder = 'admin/';
    }
    // Check for faculty login (using school_id instead of email)
    elseif($qry_faculty->num_rows > 0){
        $qry = $qry_faculty;
        $login_type = 2; // Faculty
        $view_folder = 'faculty/';
    }
    // Check for student login (using school_id and active status)
    elseif($qry_student->num_rows > 0){
        $qry = $qry_student;
        $login_type = 3; // Student
        $view_folder = 'student/';
    }
    // If no match, return login failed
    else {
        return 2; // Login failed
    }

    // Set session variables
    foreach ($qry->fetch_array() as $key => $value) {
        if($key != 'password' && !is_numeric($key)) {
            $_SESSION['login_'.$key] = $value;
        }
    }
    $_SESSION['login_type'] = $login_type;
    $_SESSION['login_view_folder'] = $view_folder;

    // Set academic session if default academic year is set
    $academic = $this->db->query("SELECT * FROM academic_list WHERE is_default = 1");
    if($academic->num_rows > 0){
        foreach($academic->fetch_array() as $k => $v){
            if(!is_numeric($k))
                $_SESSION['academic'][$k] = $v;
        }
    }
    return 1; // Login successful
}

	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function login2(){
		extract($_POST);
			$qry = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM students where student_code = '".$student_code."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['rs_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function save_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!empty($password)){
					$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			return 1;
		}
	}
	function signup(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass')) && !is_numeric($k)){
				if($k =='password'){
					if(empty($v))
						continue;
					$v = md5($v);

				}
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}

		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");

		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			if(empty($id))
				$id = $this->db->insert_id;
			foreach ($_POST as $key => $value) {
				if(!in_array($key, array('id','cpass','password')) && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
					$_SESSION['login_id'] = $id;
				if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
					$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}

	function update_user() {
		extract($_POST);
		$data = "";
		$type = array("", "users", "faculty_list", "student_list");
	
		// Determine the unique field based on login type
		$uniqueField = ($_SESSION['login_type'] == 3) ? 'school_id' : 'email';
	
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'table', 'password')) && !is_numeric($k)) {
				$data .= empty($data) ? " $k='$v' " : ", $k='$v' ";
			}
		}
	
		// Handle image upload
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";
		}
	
		// Encrypt password if provided
		if (!empty($password)) {
			$data .= " ,password=md5('$password') ";
		}
	
		// Execute INSERT or UPDATE query
		if (empty($id)) {
			// Insert new record
			$save = $this->db->query("INSERT INTO {$type[$_SESSION['login_type']]} SET $data");
		} else {
			// Update existing record
			$save = $this->db->query("UPDATE {$type[$_SESSION['login_type']]} SET $data WHERE id = $id");
		}
	
		// Update session data on successful save
		if ($save) {
			foreach ($_POST as $key => $value) {
				if ($key != 'password' && !is_numeric($key)) {
					$_SESSION['login_' . $key] = $value;
				}
			}
			if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name'])) {
				$_SESSION['login_avatar'] = $fname;
			}
			return 1;
		}
	}
	
	
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function save_system_settings(){
		extract($_POST);
		$data = '';
		foreach($_POST as $k => $v){
			if(!is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if($_FILES['cover']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'],'../assets/uploads/'. $fname);
			$data .= ", cover_img = '$fname' ";

		}
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set $data where id =".$chk->fetch_array()['id']);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set $data");
		}
		if($save){
			foreach($_POST as $k => $v){
				if(!is_numeric($k)){
					$_SESSION['system'][$k] = $v;
				}
			}
			if($_FILES['cover']['tmp_name'] != ''){
				$_SESSION['system']['cover_img'] = $fname;
			}
			return 1;
		}
	}
	function save_image(){
		extract($_FILES['file']);
		if(!empty($tmp_name)){
			$fname = strtotime(date("Y-m-d H:i"))."_".(str_replace(" ","-",$name));
			$move = move_uploaded_file($tmp_name,'assets/uploads/'. $fname);
			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
			$hostName = $_SERVER['HTTP_HOST'];
			$path =explode('/',$_SERVER['PHP_SELF']);
			$currentPath = '/'.$path[1]; 
			if($move){
				return $protocol.'://'.$hostName.$currentPath.'/assets/uploads/'.$fname;
			}
		}
	}
	function save_subject(){
		extract($_POST);
		$data = "";
	
		// Loop through $_POST data to build query string
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','user_ids')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
	
		// Check if code already exists (excluding the current record if updating)
		$chk = $this->db->query("SELECT * FROM subject_list WHERE code = '$code' AND id != '{$id}'")->num_rows;
		if($chk > 0){
			return 2; // Code already exists
		}
	
		// Determine the next id if inserting a new record
		if(empty($id)){
			$result = $this->db->query("SELECT IFNULL(MAX(id), 0) + 1 AS next_id FROM subject_list");
			$next_id = $result->fetch_assoc()['next_id'];
			
			// Include the new `id` in the data string for insertion
			$data = "id='$next_id', " . $data;
			
			// Insert new record with specified `next_id`
			$save = $this->db->query("INSERT INTO subject_list SET $data");
		} else {
			// Update existing record
			$save = $this->db->query("UPDATE subject_list SET $data WHERE id = $id");
		}
	
		if($save){
			return 1; // Success
		}
	}
	
		function delete_subject(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM subject_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_class(){
		extract($_POST);
		$data = "";
	
		// Build the SQL data string, excluding certain keys and non-numeric values
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id', 'user_ids', 'faculty_id', 'subject_id', 'class_code')) && !is_numeric($k)){
				$data .= empty($data) ? " $k='$v' " : ", $k='$v' ";
			}
		}
	
		// Handle multiple faculty IDs by converting them to a comma-separated string
		if(isset($faculty_id) && is_array($faculty_id)){
			$faculty_ids = implode(',', $faculty_id);
			$data .= ", faculty_id='$faculty_ids' ";
		}
	
		// Automatically retrieve subjects linked to the provided faculty IDs
		if(isset($faculty_id) && is_array($faculty_id)) {
			$subject_ids = [];
			foreach($faculty_id as $fid) {
				// Fetch subjects for each faculty from the subject_teacher table
				$subjects_query = $this->db->query("SELECT subject_id FROM subject_teacher WHERE faculty_id = $fid");
				while ($row = $subjects_query->fetch_assoc()) {
					$subject_ids[] = $row['subject_id'];
				}
			}
			// Remove duplicate subject IDs and convert to comma-separated string
			$subject_ids = array_unique($subject_ids);
			$subject_ids_str = implode(',', $subject_ids);
			$data .= ", subject_id='$subject_ids_str' ";
		}
	
		// Generate a random class code for student enrollment if adding a new class
		if(empty($id)){
			$class_code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
			$data .= ", class_code='$class_code' ";
		}
	
		// Check if class already exists, excluding the current record if updating
		$existing_class_check = $this->db->query("SELECT * FROM class_list WHERE (".str_replace(",", ' AND ', $data).") AND id != '{$id}'")->num_rows;
		if($existing_class_check > 0){
			return 2; // Class already exists
		}
	
		// If there are user IDs, convert to comma-separated string
		if(isset($user_ids) && is_array($user_ids)){
			$user_ids_str = implode(',', $user_ids);
			$data .= ", user_ids='$user_ids_str' ";
		}
	
		// Insert new class if no ID is provided, otherwise update existing class
		if(empty($id)){
			$save = $this->db->query("INSERT INTO class_list SET $data");
		} else {
			$save = $this->db->query("UPDATE class_list SET $data WHERE id = $id");
		}
	
		// Return success or failure
		if($save){
			return 1; // Data saved successfully
		}
		return 0; // Error saving data
	}
	
	function save_subject_teacher() {
		extract($_POST);
		$subject_id = intval($subject_id);
		$academic_year = intval($academic_year);
		$existing_teacher_found = false;
	
		// Validate that subject_id and academic_year are valid
		if ($subject_id <= 0 || $academic_year <= 0) {
			return 0; // Invalid data
		}
	
		// Process each selected faculty (teacher) ID
		if (isset($faculty_id) && is_array($faculty_id)) {
			foreach ($faculty_id as $teacher_id) {
				$teacher_id = intval($teacher_id);
	
				// Check if this teacher is already associated with this subject for the selected academic year
				$chk = $this->db->query("SELECT * FROM subject_teacher WHERE subject_id = $subject_id AND faculty_id = $teacher_id AND academic_year = $academic_year")->num_rows;
	
				if ($chk > 0) {
					// If the teacher is already assigned for the selected academic year, skip the insertion
					$existing_teacher_found = true;
					continue;
				}
	
				// Check if the teacher is associated with the subject in a different academic year
				$existing_different_year = $this->db->query("SELECT * FROM subject_teacher WHERE subject_id = $subject_id AND faculty_id = $teacher_id")->num_rows;
				if ($existing_different_year > 0) {
					// If teacher is already assigned to this subject but with a different academic year, allow insertion
					// Insert new association with subject, teacher, and academic year
					$save = $this->db->query("INSERT INTO subject_teacher (subject_id, faculty_id, academic_year) VALUES ($subject_id, $teacher_id, $academic_year)");
					if (!$save) {
						return 0; // Return error if the insertion fails
					}
				} else {
					// If teacher is not associated with the subject at all, insert the new record
					$save = $this->db->query("INSERT INTO subject_teacher (subject_id, faculty_id, academic_year) VALUES ($subject_id, $teacher_id, $academic_year)");
					if (!$save) {
						return 0; // Return error if the insertion fails
					}
				}
			}
		}
	
		// Return 2 if a teacher already exists for the selected academic year, otherwise return 1 for success
		return $existing_teacher_found ? 2 : 1;
	}
	
	function delete_subject_teacher() { 
		if (!isset($_POST['id'])) {
			return 0;
		}
	
		$id = $_POST['id'];
		$stmt = $this->db->prepare("DELETE FROM subject_teacher WHERE id = ?");
		
		if ($stmt === false) {
			return 0;
		}
	
		$stmt->bind_param("i", $id);
	
		if ($stmt->execute()) {
			return 1;  
		} else {
			return 0;
		}
	}
	
	function delete_class(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM class_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_academic(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','user_ids')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM academic_list where (".str_replace(",",'and',$data).") and id != '{$id}' ")->num_rows;
		if($chk > 0){
			return 2;
		}
		$hasDefault = $this->db->query("SELECT * FROM academic_list where is_default = 1")->num_rows;
		if($hasDefault == 0){
			$data .= " , is_default = 1 ";
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO academic_list set $data");
		}else{
			$save = $this->db->query("UPDATE academic_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_academic(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM academic_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function make_default(){
		extract($_POST);
		$update= $this->db->query("UPDATE academic_list set is_default = 0");
		$update1= $this->db->query("UPDATE academic_list set is_default = 1 where id = $id");
		$qry = $this->db->query("SELECT * FROM academic_list where id = $id")->fetch_array();
		if($update && $update1){
			foreach($qry as $k =>$v){
				if(!is_numeric($k))
					$_SESSION['academic'][$k] = $v;
			}

			return 1;
		}
	}
	function save_criteria(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','user_ids')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM criteria_list where (".str_replace(",",'and',$data).") and id != '{$id}' ")->num_rows;
		if($chk > 0){
			return 2;
		}
		
		if(empty($id)){
			$lastOrder= $this->db->query("SELECT * FROM criteria_list order by abs(order_by) desc limit 1");
		$lastOrder = $lastOrder->num_rows > 0 ? $lastOrder->fetch_array()['order_by'] + 1 : 0;
		$data .= ", order_by='$lastOrder' ";
			$save = $this->db->query("INSERT INTO criteria_list set $data");
		}else{
			$save = $this->db->query("UPDATE criteria_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_criteria(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM criteria_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_criteria_order(){
		extract($_POST);
		$data = "";
		foreach($criteria_id as $k => $v){
			$update[] = $this->db->query("UPDATE criteria_list set order_by = $k where id = $v");
		}
		if(isset($update) && count($update)){
			return 1;
		}
	}

	function save_question(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','user_ids')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
	
		// Determine the next id if inserting a new record
		if(empty($id)){
			$result = $this->db->query("SELECT IFNULL(MAX(id), 0) + 1 AS next_id FROM question_list");
			$next_id = $result->fetch_assoc()['next_id'];
	
			$lastOrder = $this->db->query("SELECT * FROM question_list WHERE academic_id = $academic_id ORDER BY ABS(order_by) DESC LIMIT 1");
			$lastOrder = $lastOrder->num_rows > 0 ? $lastOrder->fetch_array()['order_by'] + 1 : 0;
	
			// Set next_id and order_by
			$data .= ", id='$next_id', order_by='$lastOrder' ";
			
			// Insert a new record
			$save = $this->db->query("INSERT INTO question_list SET $data");
		} else {
			// Update an existing record
			$save = $this->db->query("UPDATE question_list SET $data WHERE id = $id");
		}
	
		if($save){
			return 1;
		}
	}
	
	function delete_question(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM question_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_question_order(){
		extract($_POST);
		$data = "";
		foreach($qid as $k => $v){
			$update[] = $this->db->query("UPDATE question_list set order_by = $k where id = $v");
		}
		if(isset($update) && count($update)){
			return 1;
		}
	}
	function save_faculty(){
		extract($_POST);
		$data = "";
		
		// Handle form data except for 'id', 'cpass', and 'password'
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
	
		// Handle password update
		if(!empty($password)){
			$data .= ", password=md5('$password') ";
		}
	
		// Check for existing email
		$check = $this->db->query("SELECT * FROM faculty_list WHERE email ='$email' ".(!empty($id) ? " AND id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
	
		// Check for existing school ID
		$check = $this->db->query("SELECT * FROM faculty_list WHERE school_id ='$school_id' ".(!empty($id) ? " AND id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 3;
			exit;
		}
	
		// Handle file upload
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";
		}
	
		// Insert or update database
		if(empty($id)){
			$save = $this->db->query("INSERT INTO faculty_list SET $data");
		}else{
			$save = $this->db->query("UPDATE faculty_list SET $data WHERE id = $id");
		}
	
		if($save){
			return 1;
		}
	}
	
	function delete_faculty(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM faculty_list where id = ".$id);
		if($delete)
			return 1;
	}
	function save_student(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!empty($password)){
					$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM student_list where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO student_list set $data");
		}else{
			$save = $this->db->query("UPDATE student_list set $data where id = $id");
		}

		if($save){
			return 1;
		}
	}
	// admin_class.php
	
	
		function delete_student(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM student_list where id = ".$id);
		if($delete)
			return 1;
	}
	function save_task(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if($k == 'description')
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO task_list set $data");
		}else{
			$save = $this->db->query("UPDATE task_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_task(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM task_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_progress(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if($k == 'progress')
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!isset($is_complete))
			$data .= ", is_complete=0 ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO task_progress set $data");
		}else{
			$save = $this->db->query("UPDATE task_progress set $data where id = $id");
		}
		if($save){
		if(!isset($is_complete))
			$this->db->query("UPDATE task_list set status = 1 where id = $task_id ");
		else
			$this->db->query("UPDATE task_list set status = 2 where id = $task_id ");
			return 1;
		}
	}
	function delete_progress(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM task_progress where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_restriction() {
		extract($_POST);
		$data = "";
	
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
	
		// Check for duplicates for the same faculty ID
		$check = $this->db->query("SELECT * FROM restriction_list WHERE class_id = '$class_id' AND subject_id = '$subject_id' AND faculty_id = '{$faculty_id}'" . (!empty($id) ? " AND id != {$id}" : ''))->num_rows;
	
		if ($check > 0) {
			return 3; // Duplicate class_id and subject_id combination found for the same faculty ID
		}
	
		if (empty($id)) {
			// Fetch the next ID based on the maximum ID in the restriction_list table
			$result = $this->db->query("SELECT IFNULL(MAX(id), 0) + 1 AS next_id FROM restriction_list");
			$next_id = $result->fetch_assoc()['next_id'];
	
			// Insert a new restriction with the calculated next ID
			$save = $this->db->query("INSERT INTO restriction_list SET id = $next_id, $data");
		} else {
			// Update an existing restriction
			$save = $this->db->query("UPDATE restriction_list SET $data WHERE id = $id");
		}
	
		if ($save) {
			return 1; // Successfully saved or updated
		}
		return 0; // Error occurred
	}
	
// In admin_class.php
function delete_subject_restriction() {
    // Ensure 'id' is present in the POST data
    if (!isset($_POST['id'])) {
        return 0; // Error if id is not provided
    }
    $id = $_POST['id'];
    
    // Use prepared statements for secure deletion
    $stmt = $this->db->prepare("DELETE FROM restriction_list WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    // Execute and check if deletion was successful
    if ($stmt->execute()) {
        return 1; // Successfully deleted
    } else {
        return 0; // Error occurred
    }
}



function save_evaluation() {
    extract($_POST);

    // Check for duplicate evaluation based on student_id, subject_id, and faculty_id
    $duplicate_check = $this->db->query("SELECT * FROM evaluation_list WHERE student_id = '{$_SESSION['login_id']}' AND subject_id = '$subject_id' AND faculty_id = '$faculty_id'");
    
    // If a duplicate exists, return 0 to indicate failure
    if ($duplicate_check->num_rows > 0) {
        return 0; // Duplicate entry, restrict insertion
    }

    // Fetch the next evaluation_id based on the maximum evaluation_id in the evaluation_list table
    $result = $this->db->query("SELECT IFNULL(MAX(evaluation_id), 0) + 1 AS next_id FROM evaluation_list");
    $next_id = $result->fetch_assoc()['next_id'];

    // Prepare data to insert into evaluation_list without feedback
    $data = "evaluation_id = $next_id, "; // Use the next evaluation_id
    $data .= "student_id = {$_SESSION['login_id']}, ";
    $data .= "academic_id = $academic_id, ";
    $data .= "subject_id = $subject_id, ";
    $data .= "class_id = $class_id, ";
    $data .= "restriction_id = $restriction_id, ";
    $data .= "faculty_id = $faculty_id, ";
    $data .= "status = 'pending'"; // No feedback here

    // Insert evaluation into evaluation_list
    $save = $this->db->query("INSERT INTO evaluation_list SET $data");

    // Check if save was successful
    if ($save) {
        $eid = $next_id; // Use the next evaluation_id as the inserted evaluation ID

        // Insert answers into evaluation_answers table, including feedback for each answer
        foreach ($qid as $k => $v) {
            $answer_data = "evaluation_id = $eid, ";
            $answer_data .= "question_id = $v, ";
            $answer_data .= "rate = {$rate[$v]}, ";
            $answer_data .= "feedback = '" . $this->db->real_escape_string($feedback) . "'"; // Add feedback here

            $ins[] = $this->db->query("INSERT INTO evaluation_answers SET $answer_data");
        }

        // Check if answers were inserted successfully
        if (isset($ins)) {
            return 1; // Success
        } else {
            return 0; // Failed to insert answers
        }
    } else {
        return 0; // Failed to insert evaluation
    }
}

	
	
	function get_class(){
		extract($_POST);
		$data = array();
		$get = $this->db->query("SELECT c.id,concat(c.curriculum,' ',c.level,' - ',c.section) as class,s.id as sid,concat(s.code,' - ',s.subject) as subj FROM restriction_list r inner join class_list c on c.id = r.class_id inner join subject_list s on s.id = r.subject_id where r.faculty_id = {$fid} and academic_id = {$_SESSION['academic']['id']} ");
		while($row= $get->fetch_assoc()){
			$data[]=$row;
		}
		return json_encode($data);

	}
	

	function view_report() {
		extract($_POST);
		$data = array();
		
		// Fetch total number of questions from question_list
		$totalQuestions = $this->db->query("SELECT COUNT(id) as total FROM question_list")->fetch_assoc()['total'];
		
		// Fetch evaluation answers with question text from question_list
		$get = $this->db->query("
			SELECT ea.*, q.question 
			FROM evaluation_answers ea
			JOIN question_list q ON ea.question_id = q.id
			WHERE evaluation_id IN (
				SELECT evaluation_id 
				FROM evaluation_list 
				WHERE academic_id = {$_SESSION['academic']['id']} 
				AND faculty_id = $faculty_id 
				AND subject_id = $subject_id 
				AND class_id = $class_id
			)
		");
	
		// Fetch total evaluations
		$answered = $this->db->query("
			SELECT * 
			FROM evaluation_list 
			WHERE academic_id = {$_SESSION['academic']['id']} 
			AND faculty_id = $faculty_id 
			AND subject_id = $subject_id 
			AND class_id = $class_id
		");
		$totalQuestions=20;
		$totalRating = 0;
		while ($row = $get->fetch_assoc()) {
			// Sum all ratings across the questions
			$totalRating += $row['rate'];
		}
	
		$ta = $answered->num_rows; // Total answered evaluations
	
		// Calculate overall average rating by dividing total rating by (totalQuestions * totalEvaluations)
		$averageRating = ($ta > 0 && $totalQuestions > 0) ? number_format($totalRating / ($totalQuestions * $ta), 2) : 0;
	
		$data['tse'] = $ta; // Total students evaluated
		$data['averageRating'] = $averageRating; 
		return json_encode($data);
	}
	
	
    public function get_detailed_report() {
        extract($_POST);
        
        // Prepare the SQL query to fetch the detailed report using the provided report ID.
        $qry = $this->db->query("SELECT r.*, s.firstname, s.lastname 
                                  FROM evaluation_list r 
                                  JOIN student_list s ON r.student_id = s.id 
                                  WHERE r.id = $id");

        // Check if the query returns any result.
        if ($qry->num_rows > 0) {
            $report = $qry->fetch_assoc();
            
            // Format the report output as HTML for display in the modal.
            $html = "<h4>Report for: " . $report['firstname'] . " " . $report['lastname'] . "</h4>";
            $html .= "<p><strong>Date:</strong> " . $report['date_taken'] . "</p>";
            $html .= "<p><strong>Evaluation:</strong> " . $report['evaluation'] . "</p>";
            $html .= "<p><strong>Comments:</strong> " . $report['comments'] . "</p>";
            // Add more fields if necessary.

            return $html;
        }
	}
	function get_evaluated_students() {
		extract($_POST);
		
		// Prepare SQL query to fetch evaluated students
		$qry = $this->db->query("SELECT es.student_id, s.firstname, s.lastname, es.date_taken 
								 FROM evaluation_list es 
								 JOIN student_list s ON es.student_id = s.id 
								 WHERE es.faculty_id = $faculty_id 
								 AND es.subject_id = $subject_id 
								 AND es.class_id = $class_id");
	
		$data = [];
		$tse = 0;
	
		// Check if there are results
		if ($qry->num_rows > 0) {
			$data['data'] = [];
			while ($row = $qry->fetch_assoc()) {
				$data['data'][] = [
					'student_id' => $row['student_id'],
					'name' => $row['firstname'] . ' ' . $row['lastname'],
					'date_taken' => $row['date_taken']
				];
				$tse++;
			}
		}
	
		// Total Students Evaluated
		$data['tse'] = $tse;
	
		return json_encode($data);
	}
	
	function get_report(){
		extract($_POST);
		$data = array();
	
		// Fetch evaluations with status 'active'
		$get = $this->db->query("SELECT * FROM evaluation_answers 
			WHERE evaluation_id IN 
				(SELECT evaluation_id FROM evaluation_list 
					WHERE academic_id = {$_SESSION['academic']['id']} 
					AND faculty_id = $faculty_id 
					AND subject_id = $subject_id 
					AND class_id = $class_id 
					AND status = 'active')");
	
		// Count active evaluations
		$answered = $this->db->query("SELECT * FROM evaluation_list 
			WHERE academic_id = {$_SESSION['academic']['id']} 
			AND faculty_id = $faculty_id 
			AND subject_id = $subject_id 
			AND class_id = $class_id 
			AND status = 'active'");
	
		$rate = array();
		$totalRating = 0;
		while ($row = $get->fetch_assoc()) {
			$totalRating += $row['rate']; // Sum all ratings across questions
			if (!isset($rate[$row['question_id']][$row['rate']])) {
				$rate[$row['question_id']][$row['rate']] = 0;
			}
			$rate[$row['question_id']][$row['rate']] += 5;
		}
	
		$ta = $answered->num_rows; // Total answered evaluations
		$r = array();
	
		foreach ($rate as $qk => $qv) {
			foreach ($qv as $rk => $rv) {
				$r[$qk][$rk] = ($rate[$qk][$rk] / $ta) * 1;
			}
		}
	
		// Calculate overall average rating
		$totalQuestions = 20; // Ensure this is accurate or dynamically fetched
		$averageRating = ($ta > 0 && $totalQuestions > 0) ? number_format($totalRating / ($totalQuestions * $ta), 2) : 0;
	
		$data['tse'] = $ta;
		$data['totalRating'] = $totalRating;
		$data['averageRating'] = $averageRating;
		$data['data'] = $r;
	
		return json_encode($data);
	}
	
	
	
	
	public function toggle_status($status) {
		include 'db_connect.php';
		$update = $conn->query("UPDATE evaluation_list SET status = '$status'");
		if ($update) {
			return json_encode(['status' => 'success']);
		} else {
			return json_encode(['status' => 'error', 'message' => $conn->error]);
		}
	}
	
	function get_staff_class(){
		extract($_POST);
		$data = array();
		$get = $this->db->query("SELECT c.id,concat(c.curriculum,' ',c.level,' - ',c.section) as class,s.id as sid,concat(s.code,' - ',s.subject) as subj FROM staff_restriction_list r inner join class_list c on c.id = r.class_id inner join subject_list s on s.id = r.subject_id where r.staff_id = {$sid} and academic_id = {$_SESSION['academic']['id']} ");
		while($row= $get->fetch_assoc()){
			$data[]=$row;
		}
		return json_encode($data);

	}
	function get_staff_report(){
		extract($_POST);
		$data = array();
		$get = $this->db->query("SELECT * FROM staff_evaluation_answers where evaluation_id in (SELECT evaluation_id FROM staff_evaluation_list where academic_id = {$_SESSION['academic']['id']} and staff_id = $staff_id and subject_id = $subject_id and class_id = $class_id ) ");
		$answered = $this->db->query("SELECT * FROM staff_evaluation_list where academic_id = {$_SESSION['academic']['id']} and staff_id = $staff_id and subject_id = $subject_id and class_id = $class_id");
			$rate = array();
		while($row = $get->fetch_assoc()){
			if(!isset($rate[$row['question_id']][$row['rate']]))
			$rate[$row['question_id']][$row['rate']] = 0;
			$rate[$row['question_id']][$row['rate']] += 1;

		}
		// $data[]= $row;
		$ta = $answered->num_rows;
		$r = array();
		foreach($rate as $qk => $qv){
			foreach($qv as $rk => $rv){
			$r[$qk][$rk] =($rate[$qk][$rk] / $ta) *100;
		}
	}
	$data['tse'] = $ta;
	$data['data'] = $r;
		
		return json_encode($data);

	}
	function save_staff() {
		extract($_POST);
		$data = "";
	
		foreach($_POST as $k => $v) {
			if(!in_array($k, array('id')) && !is_numeric($k)) {
				if(empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
	
		$check = $this->db->query("SELECT * FROM staff_list WHERE email ='$email' " . (!empty($id) ? " AND id != {$id} " : ''))->num_rows;
		if($check > 0) {
			return 2;
			exit;
		}
	
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";
		}
	
		if(empty($id)) {
			$save = $this->db->query("INSERT INTO staff_list SET $data");
		} else {
			$save = $this->db->query("UPDATE staff_list SET $data WHERE id = $id");
		}
	
		if($save) {
			return 1;
		}
	}
	
	public function save_staff_question() {
        global $conn;
        $staff_id = $_POST['staff_id'];
        $class_id = $_POST['class_id'];
        // Add your logic for saving staff questions
        // For example:
        $query = "INSERT INTO staff_questionnaire (staff_id, class_id) VALUES ('$staff_id', '$class_id')";
        if ($conn->query($query)) {
            echo 1; // Success
        } else {
            echo 2; // Failure
        }
    }
// Inside admin_class.php


	
}
