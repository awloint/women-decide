<?php
//include('dbCon')
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$phonenumber= $_POST['phonenumber'];
$gender = $_POST['gender'];
$howdidyouhearaboutthewalk = $_POST['howDidyouhearaboutthewalk'];
$from = 'Demo Contact Form'; 
$to = 'example@domain.com'; 
$subject = 'Thanks for registering ';
$message = "Application successful";
$body = "From: $firstname\n E-Mail: $email\n Message:\n $message";

 // Check if first name has been entered
if (!$_POST['firstname']) {
	$errName = 'Please enter your first name';
}
 // Check if last name has been entered
 if (!$_POST['lastname']) {
	$errName = 'Please enter your last name';
}

// Check if email has been entered and is valid
if (!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errEmail = 'Please enter a valid email address';
}
 // Check if phone number has been entered
 if (!$_POST['phonenumber']) {
	$errName = 'Please enter your valid phone number';
}

//Check if gender has been entered
if (!$_POST['gender']) {
	$errMessage = 'Please enter your gender';
}
 // Check if how did you hear about the walk has been entered
 if (!$_POST['howDidyouhearaboutthewalk']) {
	$errName = 'Please let us know how you heard about the walk';
}
// If there are no errors, send the email
// if (!$errFirstname && !$errLastname && !$errEmail && !$errPhonenumber && !$errGender && !$errHowdidyouhearaboutthewalk) {
// 	if (mail ($to, $subject, $body, $from)) {
// 		$result='<div class="alert alert-success">Thank You! I will be in touch</div>';
// 	} else {
// 		$result='<div class="alert alert-danger">Sorry there was an error sending your message. Please try again later</div>';
// 	}
// }
  $host ="localhost";
  $dbUsername ="root";
  $dbPassword ="";
  $dbname ='walkdatabase';

  //create connection
  $conn = mysqli_connect($host, $dbUsername, $dbPassword, $dbname);
if(!$conn)
{
    echo 'failed';
}

  if (mysqli_connect_error()) {
     // die( 'Connect Error'('. mysqli_connect_error(). ')'. mysqli_conect_error()'); 
     echo 'not connected to the server';
}
else if(!mysqli_select_db($conn, $dbname))
{
 echo 'Database Not Selected';
}
 else {
  //  $SELECT = "SELECT email From walkdatabase Where email = ? Limit 1";
    //$SELECT = "SELECT phonenumber From walk database Where  phonenumber =? Limit 1";
    // $INSERT = "INSERT INTO walkdatabase (Firstname, Lastname, Email, Phonenumber, gender,  Howyouheard)
    // values(?,?,?,?,?,?)";
    $INSERT = "INSERT INTO users (Firstname, Lastname, Email, Phonenumber, gender,  Howyouheard)
    VALUES ('$firstname', '$lastname', '$email', '$phonenumber', '$gender', '$howdidyouhearaboutthewalk')";

  if(!mysqli_query($conn, $INSERT))
  {
      echo 'Not inserted';
  }
  else
  {
    echo 'inserted';      
  }
  header("refresh:2; url=index.html");
  }
 ?> 