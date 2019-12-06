<?php


// initializing variables
$fname = "";
$lname    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'signupdb');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $fname = mysqli_real_escape_string($db, $_POST['fname']);
  $lname = mysqli_real_escape_string($db, $_POST['lname']);
  /*$tel = mysqli_real_escape_string($db, $_POST['tel']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password = mysqli_real_escape_string($db, $_POST['password']);
*/
  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($fname)) { array_push($errors, "FNAME is required"); }
  if (empty($lname)) { array_push($errors, "LNAME is required"); }
  /*if (empty($tel)) { array_push($errors, "TEL is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }*/

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM logintest WHERE FNAME='$fname' OR LNAME='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['FNAME'] === $fname) {
      array_push($errors, "Username already exists");
    }

    if ($user['LNAME'] === $lname) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (FNAME,LNAME) 
  			  VALUES('$fname', '$lname')";
  	mysqli_query($db, $query);
  	$_SESSION['FNAME'] = $fname;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: logintest.php');
  }
}
