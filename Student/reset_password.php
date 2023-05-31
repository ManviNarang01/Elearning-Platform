<?php

include_once('../dbConnection.php');

$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];
$email = $_POST['email'];
$token = $_POST['token'];

if($new_password == $confirm_password){

    $encryptedPassword = password_hash($new_password, PASSWORD_DEFAULT);

$sql = "UPDATE student SET stu_pass = '$encryptedPassword' WHERE stu_email = '$email' AND stu_token = '$token'";

	if ($conn->query($sql) === TRUE) {
        echo "success";
    }else{
        echo "failed";
    } 
}else{

}
    
?>