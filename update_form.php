<?php
session_start();
if(!isset($_SESSION['id']))
{
  echo 'You are not authorized.';
  exit;
}
include('userclass.php');

// Start validation
// Required validation

// uncomment if all fields need to required
/*if(empty($_POST['name']) || empty($_POST['father_name']) || empty($_POST['date_of_birth']) || empty($_POST['gender']) || empty($_POST['address']) || empty($_POST['mobile']) || empty($_POST['email']) || empty($_POST['board_10th']) || empty($_POST['board_12th']) || empty($_POST['university_diploma']) || empty($_POST['college_10th']) || empty($_POST['college_12th']) || empty($_POST['college_diploma']) || empty($_POST['percentage_10th']) || empty($_POST['percentage_12th']) || empty($_POST['percentage_diploma']) || empty($_POST['drinking_water_exp_year']) || empty($_POST['other_construction_exp_year']) || ($_FILES['marksheet_10th']['size'] == 0) || ($_FILES['marksheet_12th']['size'] == 0) || ($_FILES['marksheet_diploma']['size'] == 0))
{
	$arr = array('status' => 0, 'message' => 'All fields are mandatory');
    echo json_encode($arr);
    exit;
}*/

if(empty($_POST['address']) || empty($_POST['board_10th']) || empty($_POST['board_12th']) || empty($_POST['university_diploma']) || empty($_POST['college_10th']) || empty($_POST['college_12th']) || empty($_POST['college_diploma']) || empty($_POST['percentage_10th']) || empty($_POST['percentage_12th']) || empty($_POST['percentage_diploma']) || empty($_POST['drinking_water_exp_year']) || empty($_POST['other_construction_exp_year']) || ($_FILES['marksheet_10th']['size'] == 0) || ($_FILES['marksheet_12th']['size'] == 0) || ($_FILES['marksheet_diploma']['size'] == 0))
{
	$arr = array('status' => 0, 'message' => 'All fields are mandatory');
    echo json_encode($arr);
    exit;
}

//numeric and decimal validation
if((is_numeric($_POST['percentage_10th']) == 0) || (is_numeric($_POST['percentage_12th']) == 0) || (is_numeric($_POST['percentage_diploma']) == 0))
{
	$arr = array('status' => 0, 'message' => 'Allow only numeric and decimal values in Percentage Marks');
    echo json_encode($arr);
    exit;
}

if((is_numeric($_POST['drinking_water_exp_year']) == 0) || (is_numeric($_POST['other_construction_exp_year']) == 0))
{
	$arr = array('status' => 0, 'message' => 'Allow only numeric and decimal values in Experience in years');
    echo json_encode($arr);
    exit;
}

// File validation
$maxsize    = 3145728; // It is in bytes
$acceptable = array(
    'application/pdf',
    'image/jpeg',
    'image/jpg',
    'image/png'
);

if(($_FILES['marksheet_10th']['size'] > $maxsize) || ($_FILES['marksheet_12th']['size'] > $maxsize) || ($_FILES['marksheet_diploma']['size'] > $maxsize)) {
    $arr = array('status' => 0, 'message' => 'File too large. File must be less than 3 megabytes.');
    echo json_encode($arr);
    exit;
}

if((!in_array($_FILES['marksheet_10th']['type'], $acceptable)) || (!in_array($_FILES['marksheet_12th']['type'], $acceptable)) || (!in_array($_FILES['marksheet_diploma']['type'], $acceptable))) {
    $arr = array('status' => 0, 'message' => 'Invalid file type. Only PDF and JPG types are accepted.');
    echo json_encode($arr);
    exit;
}
// End file validation

// End validation


// Update the form data
$id = $_SESSION['id'];
$update_data = new userClass();
$result = $update_data->update_form($_POST,$_FILES);
$arr = array('status' => 1, 'message' => $result);
echo json_encode($arr); 
?>