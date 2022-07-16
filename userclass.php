<?php
class userClass{
	
	public $error;
	public $con;

	public function __construct() 
	{
        try {
        	$this->con = new PDO("mysql:host=localhost;dbname=swsm", 'root', '');
        	// set the PDO error mode to exception
        	$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        	//echo "Connected successfully"; 
        	}
        		catch(PDOException $e)
        	{
        	$this->error = "Connection failed: " . $e->getMessage();
        	}
    }
	
	public function user_login($registration_number, $password)
	{
		if(empty($registration_number) || empty($password))
		{
			$this->error = "All fields are required";
		}
		else if($this->check_user_validation($registration_number,$password)==0)
		{
			$this->error = "Registration number or Password is INVALID.";
		}
		
		
	}
	public function check_user_validation($registration_number,$password)
	{
		$password = hash("sha512",$password);
		
		$result = $this->con->prepare("select id from candidate_details where registration_number = :registration_number && password = :password");
		$result->execute([
			':registration_number' => $registration_number,
			':password' => $password,       
		]);
		
		if($result->rowCount() > 0)
		{
			$row = $result->fetch();
			$_SESSION['registration_number'] = $registration_number;
			$_SESSION['id'] = $row['id'];
			header("Location:register_update.php");
		}
		else
		{
			return FALSE;
		}
	}
	public function fetch_user($user_id)
	{		
		$result = $this->con->prepare("select name,mobile,email,father_name,date_of_birth,gender,is_form_updated from candidate_details where id = :id");
		$result->execute([
			':id' => $user_id      
		]);
		
		if($result->rowCount() > 0)
		{
			$row = $result->fetch();
			//return $row['is_form_updated'];
			return $row;
		}
		else
		{
			return FALSE;
		}
	}
	public function update_form($data,$file_data)
	{
		$id = $_SESSION['id'];
		date_default_timezone_set('Asia/Kolkata');
		$datetime = date('Y-m-d H:i:s');

		// Start uploading marks based on degree and governement marks
		$total_marks = 0;

		// Educational marks
		$percentage_10th = $data['percentage_10th'];
		if($percentage_10th < 60)
		{
			$total_marks = $total_marks + 2;
			$decided_marks_10th = 2;
		}
		else if($percentage_10th >= 60 && $percentage_10th <= 75)
		{
			$total_marks = $total_marks + 3;
			$decided_marks_10th = 3;
		}
		else
		{
			$total_marks = $total_marks + 5; // Above than 75
			$decided_marks_10th = 5;
		}

		$percentage_12th = $data['percentage_12th'];
		if($percentage_12th < 60)
		{
			$total_marks = $total_marks + 2;
			$decided_marks_12th = 2;
		}
		else if($percentage_12th >= 60 && $percentage_12th <= 75)
		{
			$total_marks = $total_marks + 3;
			$decided_marks_12th = 3;
		}
		else
		{
			$total_marks = $total_marks + 5; // Above than 75
			$decided_marks_12th = 5;
		}
		
		$percentage_diploma = $data['percentage_diploma'];
		if($percentage_diploma < 60)
		{
			$total_marks = $total_marks + 40;
			$decided_marks_diploma = 40;
		}
		else if($percentage_diploma >= 60 && $percentage_diploma <= 75)
		{
			$total_marks = $total_marks + 50;
			$decided_marks_diploma = 50;
		}
		else
		{
			$total_marks = $total_marks + 55; // Above than 75
			$decided_marks_diploma = 55;
		}

		// Experiance marks
		
		$drinking_water_exp_year = $data['drinking_water_exp_year'];
		if($drinking_water_exp_year <= 2)
		{
			$total_marks = $total_marks + 0;
			$decided_marks_drinking_water = 0;
		}
		else if($drinking_water_exp_year > 2 && $drinking_water_exp_year <= 3)
		{
			$total_marks = $total_marks + 10;
			$decided_marks_drinking_water = 10;
		}
		else if($drinking_water_exp_year > 3 && $drinking_water_exp_year < 5)
		{
			$total_marks = $total_marks + 20;
			$decided_marks_drinking_water = 20;
		}
		else
		{
			$total_marks = $total_marks + 30; // Above than 5 years
			$decided_marks_drinking_water = 30;
		}
		
		$other_construction_exp_year = $data['other_construction_exp_year'];
		if($other_construction_exp_year <= 2)
		{
			$total_marks = $total_marks + 0;
			$decided_marks_other_construction = 0;
		}
		else if($other_construction_exp_year > 2 && $other_construction_exp_year <= 3)
		{
			$total_marks = $total_marks + 5;
			$decided_marks_other_construction = 5;
		}
		else if($other_construction_exp_year > 3 && $other_construction_exp_year < 5)
		{
			$total_marks = $total_marks + 12;
			$decided_marks_other_construction = 12;
		}
		else
		{
			$total_marks = $total_marks + 20; // Above than 5 years
			$decided_marks_other_construction = 20;
		}

		// End uploading marks based on degree and governement marks

		// Start upload files

		// 10th marksheet
		$marksheet_10th_temp = explode(".", $_FILES["marksheet_10th"]["name"]);
		$marksheet_10th = mt_rand(1000,99999999) . '.' . end($marksheet_10th_temp);
		move_uploaded_file($_FILES["marksheet_10th"]["tmp_name"], "marksheets/marksheet_10th/" . $marksheet_10th);

		// 12th marksheet
		$marksheet_12th_temp = explode(".", $_FILES["marksheet_12th"]["name"]);
		$marksheet_12th = mt_rand(1000,99999999) . '.' . end($marksheet_12th_temp);
		move_uploaded_file($_FILES["marksheet_12th"]["tmp_name"], "marksheets/marksheet_12th/" . $marksheet_12th);

		// diploma or engee marksheet
		$marksheet_diploma_temp = explode(".", $_FILES["marksheet_diploma"]["name"]);
		$marksheet_diploma = mt_rand(1000,99999999) . '.' . end($marksheet_diploma_temp);
		move_uploaded_file($_FILES["marksheet_diploma"]["tmp_name"], "marksheets/marksheet_diploma/" . $marksheet_diploma);

		// End upload files

		$stmt = $this->con->prepare("UPDATE candidate_details SET address = :address, board_10th = :board_10th, college_10th = :college_10th, percentage_10th = :percentage_10th, board_12th = :board_12th, college_12th = :college_12th, percentage_12th = :percentage_12th, university_diploma = :university_diploma, college_diploma = :college_diploma, percentage_diploma = :percentage_diploma, drinking_water_exp_year = :drinking_water_exp_year, other_construction_exp_year = :other_construction_exp_year, is_form_updated = :is_form_updated, ip_address = :ip_address, updated_datetime = :updated_datetime, marksheet_10th = :marksheet_10th, marksheet_12th = :marksheet_12th, marksheet_diploma = :marksheet_diploma, decided_marks_10th = :decided_marks_10th, decided_marks_12th = :decided_marks_12th, decided_marks_diploma = :decided_marks_diploma, total_marks = :total_marks, decided_marks_10th = :decided_marks_10th, decided_marks_12th = :decided_marks_12th, decided_marks_diploma = :decided_marks_diploma, decided_marks_drinking_water = :decided_marks_drinking_water, decided_marks_other_construction = :decided_marks_other_construction WHERE id = :id");
				$stmt->execute([
					':address' => $data['address'],
					':board_10th' => $data['board_10th'],
					':college_10th' => $data['college_10th'],
					':percentage_10th' => $data['percentage_10th'],
					':board_12th' => $data['board_12th'],
					':college_12th' => $data['college_12th'],
					':percentage_12th' => $data['percentage_12th'],
					':university_diploma' => $data['university_diploma'],
					':college_diploma' => $data['college_diploma'],
					':percentage_diploma' => $data['percentage_diploma'],
					':drinking_water_exp_year' => $data['drinking_water_exp_year'],
					':other_construction_exp_year' => $data['other_construction_exp_year'],
					':is_form_updated' => 1,
					':ip_address' => $_SERVER['REMOTE_ADDR'],
					':updated_datetime' => $datetime,
					':marksheet_10th' => $marksheet_10th,
					':marksheet_12th' => $marksheet_12th,
					':marksheet_diploma' => $marksheet_diploma,
					':decided_marks_10th' => $decided_marks_10th,
					':decided_marks_12th' => $decided_marks_12th,
					':decided_marks_diploma' => $decided_marks_diploma,
					':total_marks' => $total_marks,
					':decided_marks_10th' => $decided_marks_10th,
					':decided_marks_12th' => $decided_marks_12th,
					':decided_marks_diploma' => $decided_marks_diploma,
					':decided_marks_drinking_water' => $decided_marks_drinking_water,
					':decided_marks_other_construction' => $decided_marks_other_construction,
					':id' => $id,
				]);
		return $stmt;

		// Uncomment when need to update all fields.

		/*$stmt = $this->con->prepare("UPDATE candidate_details SET name = :name, father_name = :father_name, date_of_birth = :date_of_birth, address = :address, mobile = :mobile, email = :email, board_10th = :board_10th, college_10th = :college_10th, percentage_10th = :percentage_10th, board_12th = :board_12th, college_12th = :college_12th, percentage_12th = :percentage_12th, university_diploma = :university_diploma, college_diploma = :college_diploma, percentage_diploma = :percentage_diploma, drinking_water_exp_year = :drinking_water_exp_year, other_construction_exp_year = :other_construction_exp_year, is_form_updated = :is_form_updated, ip_address = :ip_address, updated_datetime = :updated_datetime, marksheet_10th = :marksheet_10th, marksheet_12th = :marksheet_12th, marksheet_diploma = :marksheet_diploma, decided_marks_10th = :decided_marks_10th, decided_marks_12th = :decided_marks_12th, decided_marks_diploma = :decided_marks_diploma, total_marks = :total_marks, decided_marks_10th = :decided_marks_10th, decided_marks_12th = :decided_marks_12th, decided_marks_diploma = :decided_marks_diploma, decided_marks_drinking_water = :decided_marks_drinking_water, decided_marks_other_construction = :decided_marks_other_construction, gender = :gender WHERE id = :id");
				$stmt->execute([
					':name' => $data['name'],
					':father_name' => $data['father_name'],
					':date_of_birth' => $data['date_of_birth'],
					':address' => $data['address'],
					':mobile' => $_POST['mobile'],
					':email' => $data['email'],
					':board_10th' => $data['board_10th'],
					':college_10th' => $data['college_10th'],
					':percentage_10th' => $data['percentage_10th'],
					':board_12th' => $data['board_12th'],
					':college_12th' => $data['college_12th'],
					':percentage_12th' => $data['percentage_12th'],
					':university_diploma' => $data['university_diploma'],
					':college_diploma' => $data['college_diploma'],
					':percentage_diploma' => $data['percentage_diploma'],
					':drinking_water_exp_year' => $data['drinking_water_exp_year'],
					':other_construction_exp_year' => $data['other_construction_exp_year'],
					':is_form_updated' => 1,
					':ip_address' => $_SERVER['REMOTE_ADDR'],
					':updated_datetime' => $datetime,
					':marksheet_10th' => $marksheet_10th,
					':marksheet_12th' => $marksheet_12th,
					':marksheet_diploma' => $marksheet_diploma,
					':decided_marks_10th' => $decided_marks_10th,
					':decided_marks_12th' => $decided_marks_12th,
					':decided_marks_diploma' => $decided_marks_diploma,
					':total_marks' => $total_marks,
					':decided_marks_10th' => $decided_marks_10th,
					':decided_marks_12th' => $decided_marks_12th,
					':decided_marks_diploma' => $decided_marks_diploma,
					':decided_marks_drinking_water' => $decided_marks_drinking_water,
					':decided_marks_other_construction' => $decided_marks_other_construction,
					':gender' => $data['gender'],
					':id' => $id,
				]);
		return $stmt;*/
	}

}

?>