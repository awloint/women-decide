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


        // Send an Email Via PHPMailer
        $mail = new PHPMailer(true); // enable exceptions
        // server settings
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host = $emailHost;
        $mail->SMTPAuth = true;
        $mail->Username = $emailUsername;
        $mail->Password = $emailPassword;
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $emailBody = '<table style="background-color: #d5d5d5;" border="0" width="100%" cellspacing="0">
                        <tbody>
                        <tr>
                        <td>
                        <table style="font-family: Helvetica,Arial,sans-serif; background-color: #fff; margin-top: 40px; margin-bottom: 40px;" border="0" width="600" cellspacing="0" cellpadding="0" align="center">
                        <tbody>
                        <tr>
                        <td style="padding-top: 40px; padding-right: 40px; padding-bottom: 15px;" colspan="2">
                        <p style="text-align: right;"><a href="https://awlo.org"><img src="http://awlo.org/email/awlo_lg.png" alt="African Women in Leadership Organisation" width="20%" border="0" /></a></p>
                        </td>
                        </tr>
                        <tr>
                        <td style="padding-right: 40px; text-align: right;" colspan="2"></td>
                        </tr>
                        <tr>
                        <td style="color: #000; font-size: 12pt; font-family: Helvetica; font-weight: normal; line-height: 15pt; padding: 40px 40px 80px 40px;" colspan="2" valign="top">Dear ' . $firstName . ' ' . $lastName . ',' . '
                        <p>Thank you for choosing to be a part of the #WomenDecide Awareness Walk.</p>
                        <p>Kick of time is 7am prompt, December 15, 2018 at Ikeja City Mall</p>
                        <p>For more enquiries, call 08037594969.</p>
                        <p>Best regards,</p>
                        </td>
                        </tr>
                        <tr>
                        <td style="border-top: 5px solid #940000; height: 10px; font-size: 7pt;" colspan="2" valign="top"><span>&nbsp;</span></td>
                        </tr>
                        <tr style="text-align: center;">
                        <td id="s1" style="padding-left: 20px;" valign="top"><span style="text-align: center; color: #333; font-size: 12pt;"><strong>AWLO Correspondence Team<span style="color: #cccccc; font-size: x-large;">&nbsp;|&nbsp;</span><span style="text-align: left; color: #333; font-size: 11pt; font-weight: normal;">International Headquarters</span></td>
                        </tr>
                        <tr style="text-align: center; padding-left: 40px; padding-right: 40px; padding-bottom: 0;">
                        <td colspan="2" valign="top"><span style="color: #333; font-size: 8pt; font-weight: normal; line-height: 17pt; padding-left: 40px; padding-right: 40px;">African Women in Leadership Organisation<br /><strong>International Headquarters:</strong> 6, Alhaji Bankole Crescent, Ikeja, Lagos - Nigeria<br />tel: +2347066819910 &nbsp;|&nbsp; mobile: +2348066285116 &nbsp;|&nbsp; +2348087719510<br /><strong>USA:</strong> 60 4800 Duval Point Way SW, Snellville, GA 30039, USA.<br />tel: +1 404-518-8194 &nbsp;| <span>+1 505-547-0528</span>&nbsp;&nbsp;<br /><strong>South Africa:</strong>&nbsp;Newlands Shopping Centre CNR. Dely Road/Lois Road, <br />1st Floor, Suite 104, Newlands, Pretoria, South Africa<br />tel: +27-845-105871<br /><strong>email:&nbsp;</strong>info@awlo.org &nbsp;|&nbsp; <strong>www.awlo.org</strong></span>
                        <p><a href="http://twitter.com/awloint"><img src="http://awlo.org/email/social/twitter_circle_color-20.png" width="20px" height="20px" /></a><a href="http://facebook.com/awloint"><img src="http://awlo.org/email/social/facebook_circle_color-20.png" width="20px" height="20px" /></a><a href="https://plus.google.com/103912934440599693779"><img src="http://awlo.org/email/social/google_circle_color-20.png" width="20px" height="20px" /></a><a href="http://linkedin.com/company/awloint"><img src="http://awlo.org/email/social/linkedin_circle_color-20.png" width="20px" height="20px" /></a><a href="http://instagram.com/awloint"><img src="http://awlo.org/email/social/instagram_circle_color-20.png" width="20px" height="20px" /></a><a href="https://www.youtube.com/channel/UCevvBafqeTjY16qd2gbceJw"><img src="http://awlo.org/email/social/youtube_circle_color-20.png" width="20px" height="20px" /></a></p>
                        </td>
                        </tr>
                        <tr>
                        <td id="s3" style="padding-left: 20px; padding-right: 20px;" colspan="2" valign="bottom">
                        <p style="font-family: Helvetica, sans-serif; text-align: center; font-size: 12px; line-height: 21px; color: #333;"><span style="margin-left: 4px;"><span style="opacity: 0.4; color: #333; font-size: 9px;">Disclaimer: This message and any files transmitted with it are confidential and privileged. If you have received it in error, please notify the sender by return e-mail and delete this message from your system. If you are not the intended recipient you are hereby notified that any dissemination, copy or disclosure of this e-mail is strictly prohibited.</span></span></p>
                        </td>
                        </tr>
                        <tr>
                        <td style="border-bottom: 5px solid #940000; height: 5px; font-size: 7pt;" colspan="2" valign="top">&nbsp;</td>
                        </tr>
                        </tbody>
                        </table>
                        </td>
                        </tr>
                        </tbody>
                        </table>';
        //Recipients
        $mail->setFrom('info@awlo.org', 'AWLO x YALI Lagos');
        $mail->addAddress($email, $firstName.' '.$lastName);
        // Content
        $mail->isHTML(true);
        $mail->Subject = '#WomenDecide Awareness Walk';
        $mail->Body = $emailBody;
        
        $mail->send();


    }

    

}