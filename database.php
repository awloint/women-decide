<?php
$first name = $_POST['first name'];
$last name = $_POST['last name'];
$email = $_POST['email'];
$phone number= $_POST['phone number'];
$gender = $_POST['gender'];
$how did you hear about the walk = $_POST['how did you hear about the walk'];
$from = 'Demo Contact Form'; 
$to = 'example@domain.com'; 
$subject = 'Thanks for registering ';

$body = "From: $name\n E-Mail: $email\n Message:\n $message";

 // Check if first name has been entered
if (!$_POST['first name']) {
	$errName = 'Please enter your first name';
}
 // Check if last name has been entered
 if (!$_POST['last name']) {
	$errName = 'Please enter your last name';
}

// Check if email has been entered and is valid
if (!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errEmail = 'Please enter a valid email address';
}
 // Check if phone number has been entered
 if (!$_POST['phone number']) {
	$errName = 'Please enter your valid phone number';
}

//Check if gender has been entered
if (!$_POST['gender']) {
	$errMessage = 'Please enter your gender';
}
 // Check if how did you hear about the walk has been entered
 if (!$_POST['how did you hear about the walk has been entered']) {
	$errName = 'Please enter how did you hear about the walk has been entered';
}
// If there are no errors, send the email
if (!$errFirstname && !$errLastname && !$errEmail && !$errPhonenumber && !$errGender && !$errHowdidyouhearaboutthewalk) {
	if (mail ($to, $subject, $body, $from)) {
		$result='<div class="alert alert-success">Thank You! I will be in touch</div>';
	} else {
		$result='<div class="alert alert-danger">Sorry there was an error sending your message. Please try again later</div>';
	}
}
  $host ="localhost";
  $dbUsername ="root";
  $dbPassword ="";
  $dbname ="walk database";

  \\create connection
  $conn = new mysqli($host, $dbFirstname, $dbLastname, $dbEmail, $dbPhonenumber, $dbGender, $dbHowdidyouhearaboutthewalk);

  if (mysqli_connect_error()) {
      .die( 'Connect Error'('. mysqli_connect_error(). ')'. mysqli_conect_error()); 
} else {
    $SELECT = "SELECT email" From walk database Where email = ? Limit 1";
    $SELECT = "SELECT phonenumber" From walk database Where  phonenumber =? Limit 1";
    $INSERT = INSERT Into walk database (first name, last name, email, phone number,gender, how did you hear about the walk)
    values(?,?,?,?,?,?)";

    //Prepare statement
    $stmt = $conn- >prepare($SELECT);
    $stmt- >bind_param("sssiss", $firtsname, $lastname, $email, $phonenumber, $gender, $how did you hear about the walk);
    $stmt- >execute();
    $stmt- >bind_result($firtsname, $lastname, $email, $phonenumber, $gender, $how did you hear about the walk);
    $stmt- >store_result();
    $rnum = $stmt- >num_rows;

    if ($rnum==0) {
        $stmt- >close();

        $stmt = $conn->prepare($INSERT);
        $stmt- >bind_param("sssiss", $firtsname, $lastname, $email, $phonenumber, $gender, $how did you hear about the walk);
        $stmt- >execute();
        echo "New record inserted sucessfully ";
    } else {
        echo "Someone already registered using this email";
        echo "Someone already registered using this phone number";
    }
    $stmt->close();
    $conn->close();
}
  }else {
      echo "All field are required";
    die();
  }
  
  }
<?php echo $result;
 ?> 