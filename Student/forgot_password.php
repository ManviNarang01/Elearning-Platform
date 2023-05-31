<?php


$email = $_POST['email'];
$token = generateRandomToken(); // Generate a random token
echo saveTokenToDatabase($email, $token); // Save the token to the student table
include('smtp/PHPMailerAutoload.php');

$baseURL = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$baseURL .= $_SERVER['HTTP_HOST'];

$message = 'Click the link below to reset your password: <a href="' . $baseURL . '/ELearning/index.php?email=' . $email . '&token=' . urlencode($token) . '">Reset Password</a>';


smtp_mailer($email, 'Forgot Password', $message);

function generateRandomToken() {
    $length = 10; // Set the length of the token
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $token = '';
    for ($i = 0; $i < $length; $i++) {
        $token .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $token;
}

function saveTokenToDatabase($email, $token) {
   
include_once('../dbConnection.php');


   $sql = "UPDATE student SET stu_token = '$token' WHERE stu_email = '$email'";

    if ($conn->query($sql) === TRUE) {
      // echo "Record updated successfully";
    }else{
      echo "Failed";
      die;
    } 
}

function smtp_mailer($to, $subject, $msg) {
    $mail = new PHPMailer();
    $mail->SMTPDebug  = 0; // Set SMTPDebug to 0 to disable debug output
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587;
    $mail->Username = "manvinarang01@gmail.com";
    $mail->Password = "udweuwrpgwwbyxgz";
    $mail->setFrom("manvinarang01@gmail.com");
    $mail->addAddress($to);
    $mail->Subject = $subject;
    $mail->msgHTML($msg);
    $mail->CharSet = 'UTF-8';
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => false
        )
    );

    if ($mail->send()) {
    echo 'success'; // Return lowercase 'success' on success
    } else {
        echo 'failed'; // Return lowercase 'failed' on failure
    }
}

?>
