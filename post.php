<?php
//Pull in the Database Configuration file
require 'dbconfig.php';

// Pull in Sendupulse Classes
require 'sendpulse-rest-api-php/ApiInterface.php';
require 'sendpulse-rest-api-php/ApiClient.php';
require 'sendpulse-rest-api-php/Storage/TokenStorageInterface.php';
require 'sendpulse-rest-api-php/Storage/FileStorage.php';
require 'sendpulse-rest-api-php/Storage/SessionStorage.php';
require 'sendpulse-rest-api-php/Storage/MemcachedStorage.php';
require 'sendpulse-rest-api-php/Storage/MemcacheStorage.php';

// Pull in PHPMailer Classes
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$SPApiClient = new ApiClient(API_USER_ID, API_SECRET, new FileStorage());


// Capture Post Data that is sent from the form through the main.js file
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$gender = $_POST['gender'];
$referrer = $_POST['referrer'];

// Connect to the Database using PDO

$dsn = "mysql:host=$host;dbname=$db";

//Create PDO Connection with the dbconfig data
$conn = new PDO($dsn, $username, $password);


//display a message if the connection was successful
// if($conn) {
//     echo "Connected to the <strong>$db</strong> database successfully!";
// }

// Check to see if the user is in the database already
$usercheck = "SELECT * FROM women_decide WHERE email=?";

// prepare the Query
$usercheckquery = $conn->prepare($usercheck);

//Execute the Query
$usercheckquery->execute(array("$email"));

//Fetch the Result
$usercheckquery->rowCount();
if($usercheckquery->rowCount() > 0) {
    echo "user_exists";
} else {
    // Insert the user into the database
    $enteruser = "INSERT into women_decide (firstName, lastName, email, phone, gender, referrer) VALUES (:firstName, :lastName, :email, :phone, :gender, :referrer)";
    //Prepare Query
    $enteruserquery = $conn->prepare($enteruser);
    // Execute the Query
    $enteruserquery->execute(
        array(
            "firstName"         =>  $firstName,
            "lastName"          =>  $lastName,
            "email"             =>  $email,
            "phone"             =>  $phone,
            "gender"            =>  $gender,
            "referrer"          =>  $referrer
        )
    );

    //send success response
    echo "success";

    // Fetch Result
    $enteruserquery->rowCount();
    // Check to see if the query executed successfully
    if ($enteruserquery->rowCount() > 0) {

        // send an SMS
        $smstext = "Hi {$firstName}. Thanks for registering for the #WomenDecide Awareness Walk. We look forward to doing this with you. For further enquiries call: 08037594969.";
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://5r4gj.api.infobip.com/sms/2/text/single",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{ \"from\":\"AWLO\", \"to\":\"{$phone}\", \"text\":\"{$smstext}\" }",
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "authorization: {$token}",
            "content-type: application/json"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        // if ($err) {
        //     echo "cURL Error #:" . $err;
        // } else {
        //     echo $response;
        // }

        /**
         * Add User to the SendPule mailing List
         */
        $bookID = 2125883;
        $emails = array(
                array(
                    'email'         =>  $email,
                    'variables'     =>  array(
                    'phone'         =>  $phone,
                    'name'          =>  $firstName,
                    'lastName'      =>  $lastName,
                    'gender'        =>  $gender,
                    'referrer'      =>  $referrer,
                )
            )
        );
        // Without Confirmation
        var_dump($SPApiClient->addEmails($bookID, $emails));




    }

    

}