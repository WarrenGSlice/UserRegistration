<?php 
include "config.php";
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Create Registration form with MySQL and PHP</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Bootstrap JS -->
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <?php
    $error_message = "";$success_message = "";

    if(isset($_POST['btnsignup'])){
      $username         =trim($_POST['username']);
      $userrole         =trim($_POST['userrole']);
      $fname            =trim($_POST['fname']);
      $lname            =trim($_POST['lname']);
      $email            =trim($_POST['email']);
      $password         =trim($_POST['password']);
      $confirmpassword  =trim($_POST['confirmpassword']);

      $isValid = true;

      //Check Fields are empty or not
      if($username == '' || $userrole == ' ' || $fname == '' || $lname == '' || $email == '' || $password == '' || $confirmpassword == ''){
        $isValid = false;
        $error_message = "Please fill all fields.";
      }

      //Check if confirm password matching or not
      if($isValid && ($password != $confirmpassword)){
        $isValid = false;
        $error_message = "Confirm password not matching.";
      }

      //Check if Email-ID is valid or not
      if($isValid && !(filter_var($email,FILTER_VALIDATE_EMAIL))){
        $isValid = false;
        $error_message = "Invalid Email-ID";
      }

      //Check if Email-ID already exist
      if($isValid){

        $stmt = $con->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param('s',$email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if($result->num_rows > 0){
          $isValid = false;
          $error_message = "Email-ID already exists";
        }
      }

      //Check if Username already exist
      if($isValid){

        $stmt = $con->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param('s',$username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if($result->num_rows > 0){
          $isValid = false;
          $error_message = "UserName already exists";
        }
      }


      //Insert record
      if($isValid){
        $insertSQL = "INSERT INTO users(username,userrole,fname,lname,email,password) VALUES(?,?,?,?,?,?)";
        $stmt = $con->prepare($insertSQL);
        $stmt->bind_param('ssss', $username, $userrole, $fname, $lname, $email, $password);
        $stmt->execute();
        $stmt->close();

        $success_message = "Account created successfully.";
      }

    }

    ?>
  </head>
  <body>
    <div class='container'>
      <div class='row'>

        <div class='col-md-6' >

          <form method="POST" action=''>
            <h1>User Registration</h1>
            <!-- Display Error Message-->
            <?php
            if(!empty($error_message)){
              ?>
              <div class="alert alert-danger">
                <strong>Error!</strong> <?= $error_message ?>
              </div>
              <?php
            }
            ?>

            <!-- Display Success Message-->
            <?php
            if(!empty($success_message)){
              ?>
              <div class="alert alert-success">
                <strong>Success!</strong> <?= $success_message ?>
              </div>
              <?php
            }
            ?>

            <div class="form-group">
              <label for="username">UserName: </label>
              <input type="text" class="form-control" name="username" id="username" required maxlength="80">
            </div>

            <div class="form-group">
              <label for="userrole">User Role: </label>
              <input type="text" class="form-control" name="userrole" id="userrole" required maxlength="80">
            </div>
            
            <div class="form-group">
              <label for="fname">First Name: </label>
              <input type="text" class="form-control" name="fname" id="fname" required maxlength="80">
            </div>

            <div class="form-group">
              <label for="lname">Last Name: </label>
              <input type="text" class="form-control" name="lname" id="lname" required maxlength="80">
            </div>

            <div class="form-group">
              <label for="email">Email address: </label>
              <input type="email" class="form-control" name="email" id="email" required maxlength="80">
            </div>

            <div class="form-group">
              <label for="password">Password: </label>
              <input type="password" class="form-control" name="password" id="password" required maxlength="80">
            </div>

            <div class="form-group">
              <label for="confirmpassword">Confirm Password: </label>
              <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" required maxlength="80">
            </div>

            <button type="submit" name="btnsignup" class="bnt btn-default">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>