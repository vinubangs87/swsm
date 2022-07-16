<?php
session_start();
if(!isset($_SESSION['id']))
{
  header("Location:index.php");
}
include('userclass.php');
$user_data = new userClass();
$result = $user_data->fetch_user($_SESSION['id']);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Transperent selection system of Junior engineer in s.w.s.m.</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <style type="text/css">
   #loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
  text-align: center;
    background: url('https://i.imgur.com/Cn9MC39.gif') 50% 50% no-repeat rgba(0,0,0,0.8);
  vertical-align: middle;
  color: #fff;
 
}
#loader:before{
      content: attr(data-wordLoad);
      color: #fff;
      position: absolute;
      top: calc(50% + 150px); 
      left: calc(50% - 90px); 
      width: 180px;
      display: table-cell;
      text-align: center;
      vertical-align: middle;
      font-size: 1.5rem;
}
  </style>
</head>
<body class="hold-transition sidebar-mini">
  <div id="loader" data-wordLoad="Please Waiting" style="display: none;"></div>
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="margin-left: 0px !important; background-color: #EDF8FF;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <img src="dist/img/logo.png" style="height:79px;width:auto;" alt="Jal Jeevan Mission - Uttar Pradesh ( JJM - UP )" title="Jal Jeevan Mission - Uttar Pradesh ( JJM - UP )" />
      </li>
    </ul>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item ">
        <a class="nav-link" href="logout.php">
          <button type="button" class="btn btn-primary">Logout</button>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin-left: 0px !important;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User panel</h1>
          </div>
          
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->
        <?php if($result['is_form_updated'] == 1) { ?>
        <div class="card card-default"><h1>You have successfully updated your form.</h1></div>
        <?php } ?>
         <?php if($result['is_form_updated'] != 1) { ?>
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Please update below form (<b style="color:red;">*</b> means mandatory field)</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form id="form" action="update_form.php" method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Name<span style="color:red;">*</span></label>
                  <input type="text" name="name" class="form-control" value="<?= $result['name']; ?>" readonly="readonly">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Father name<span style="color:red;">*</span></label>
                  <input type="text" name="father_name" class="form-control" value="<?= $result['father_name']; ?>" readonly="readonly">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Date of birth<span style="color:red;">*</span></label>
                  <input type="date" name="date_of_birth" class="form-control" value="<?= $result['date_of_birth']; ?>" readonly="readonly">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Gender<span style="color:red;">*</span></label>
                  <!-- <select class="form-control select2" name="gender">
                    <option value="">Select gender</option>
                    <option value="Male" <?php //($result['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?php //($result['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                    <option value="Transgender" <?php //($result['gender'] == 'Transgender') ? 'selected' : '' ?>>Transgender</option>
                  </select> -->
                  <input type="text" name="gender" class="form-control" value="<?= $result['gender']; ?>" readonly="readonly">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Address<span style="color:red;">*</span></label>
                  <input type="text" name="address" class="form-control" required="required">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Mobile<span style="color:red;">*</span></label>
                  <input type="number" name="mobile" class="form-control" value="<?= $result['mobile']; ?>" readonly="readonly">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Email<span style="color:red;">*</span></label>
                  <input type="text" name="email" class="form-control" value="<?= $result['email']; ?>" readonly="readonly">
                </div>
              </div>
            </div>

            <b style="color: red">Qualification: </b>
              <div class="row">
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th>Degree Name</th>
                      <th>Name of University/Board<span style="color:red;">*</span></th>
                      <th>Name of College<span style="color:red;">*</span></th>
                      <th>Percentage Marks<span style="color:red;">*</span> <span style="color:red">(Only in number like: 60. Please don't write % sign)</span></th>
                      <th>Upload marksheet<span style="color:red;">*</span> <span style="color:red">(Only jpg or pdf format)</span></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>10th</td>
                      <td><input type="text" name="board_10th" class="form-control" required="required"></td>
                      <td><input type="text" name="college_10th" class="form-control" required="required"></td>
                      <td><input type="text" name="percentage_10th" class="form-control" required="required"></td>
                      <td><input type="file" name="marksheet_10th" class="form-control" required="required"></td>
                    </tr>
                    <tr>
                      <td>12th</td>
                      <td><input type="text" name="board_12th" class="form-control" required="required"></td>
                      <td><input type="text" name="college_12th" class="form-control" required="required"></td>
                      <td><input type="text" name="percentage_12th" class="form-control" required="required"></td>
                      <td><input type="file" name="marksheet_12th" class="form-control" required="required"></td>
                    </tr>
                    <tr>
                      <td>Diploma/B.tech.</td>
                      <td><input type="text" name="university_diploma" class="form-control" required="required"></td>
                      <td><input type="text" name="college_diploma" class="form-control" required="required"></td>
                      <td><input type="text" name="percentage_diploma" class="form-control" required="required"></td>
                      <td><input type="file" name="marksheet_diploma" class="form-control" required="required"></td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <b style="color: red">Experience: </b><br/>
              <span style="color:blue">Note: If you do not have any experiance, please enter 0.</span>
              <div class="row">
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th>Experience field<span style="color:red;">*</span></th>
                      <th>Experience in years<span style="color:red;">*</span> <span style="color:red">(Only in number like: 10. Please don't write alphabet)</span></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Drinking water supply sector (Ground Water/Surface Water/Water treatment</td>
                      <td><input type="text" name="drinking_water_exp_year" class="form-control" placeholder="If you do not have any experiance, please enter 0" required="required"></td>
                    </tr>
                    <tr>
                      <td>Other construction sector</td>
                      <td><input type="text" name="other_construction_exp_year" class="form-control" placeholder="If you do not have any experiance, please enter 0" required="required"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
                <div class="form-group">
                  <button type="submit" id="submitBtn" class="btn btn-primary" name="submit">Submit Form</button>
                </div>
            </form>

          </div>
          <!-- /.card-body -->
        </div>
          <?php } ?>
        <!-- /.card -->
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer" style="background-color: #367397; margin-left: 0px !important;">
    <center style="color:white"> &copy; Copyrights 2021 developed and designed by Jal Jeevan Mission - Uttar Pradesh ( JJM - UP ), Namami Gange & Rural Water Supply Department</center>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- Page script -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

  })
</script>
<script>
$(document).ready(function(){
    $("#form").on('submit',(function(e) {
      e.preventDefault();
      if (confirm("Do you want to update?")) {
      var form_data = new FormData(this);
            $.ajax({
                type: "POST",
                url: 'update_form.php',
                data: form_data,
                dataType: "json",
                contentType: false,
                cache: false,
                processData:false,
                 beforeSend: function() {
                    $('#loader').show();
                },
                success: function(data) {
                    if(data.status == 0)
                    {
                      alert(data.message);
                      $('#loader').hide();
                    }
                    if(data.status == 1)
                    {
                      alert("Form Submited Successfully");
                      $('#loader').hide();
                      location.href = "register_update.php";
                    }
                },
                error: function(data) {
                      
                    // Some error in ajax call
                    alert("some Error");
                }
            });
          }
        }));
});
</script>
</body>
</html>
