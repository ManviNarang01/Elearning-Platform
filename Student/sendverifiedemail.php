<?php

include('smtp/PHPMailerAutoload.php');

$email = $_POST['email'];

$baseURL = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$baseURL .= $_SERVER['HTTP_HOST'];

$message = 'Click the link below to verify your email: <a href="' . $baseURL . '/ELearning/index.php?method=emailverify&email=' . base64_encode($email) . '">Verified Email</a>';

smtp_mailer($email, 'Verified Email', $message);
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