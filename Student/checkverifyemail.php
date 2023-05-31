<?php
if(isset($_POST['email'])){

include_once('../dbConnection.php');

$email = base64_decode($_POST['email']);
   $sql = "UPDATE student SET stu_email_verify = '1' WHERE stu_email = '$email'";

    if ($conn->query($sql) === TRUE) {
        echo "success";
    }else{
      echo "failed";
    } 
} 
?>