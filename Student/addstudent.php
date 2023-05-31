<?php 
if(!isset($_SESSION)){ 
  session_start(); 
}
include_once('../dbConnection.php');

// setting header type to json, We'll be outputting a Json data
header('Content-type: application/json');

// Checking Email already Registered
if(isset($_POST['stuemail']) && isset($_POST['checkemail'])){
  $stuemail = $_POST['stuemail'];
  $sql = "SELECT stu_email FROM student WHERE stu_email='".$stuemail."'";
  $result = $conn->query($sql);
  $row = $result->num_rows;
  echo json_encode($row);
  }
 
  // Inserting or Adding New Student
  if(isset($_POST['stusignup']) && isset($_POST['stuname']) && isset($_POST['stuemail']) && isset($_POST['stupass'])){
    $stuname = $_POST['stuname'];
    $stuemail = $_POST['stuemail'];
    $stupass = $_POST['stupass'];

    // Encrypt the password
    $encryptedPassword = password_hash($stupass, PASSWORD_DEFAULT);

    $sql = "INSERT INTO student(stu_name, stu_email, stu_pass) VALUES ('$stuname', '$stuemail', '$encryptedPassword')";
    if($conn->query($sql) === TRUE){
        echo json_encode("OK");
    } else {
        echo json_encode("Failed");
    }
}


  // Student Login Verification
  if (!isset($_SESSION['is_login'])) {
    if (isset($_POST['checkLogemail']) && isset($_POST['stuLogEmail']) && isset($_POST['stuLogPass'])) {
        $stuLogEmail = $_POST['stuLogEmail'];
        $stuLogPass = $_POST['stuLogPass'];

        // Retrieve the hashed password from the database based on the email
        $sql = "SELECT stu_email, stu_pass FROM student WHERE stu_email='" . $stuLogEmail . "'";
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $storedHash = $row['stu_pass'];

            // Verify the password
            if (password_verify($stuLogPass, $storedHash)) {
                $_SESSION['is_login'] = true;
                $_SESSION['stuLogEmail'] = $stuLogEmail;

                if (isset($_POST['rememberMe']) && $_POST['rememberMe'] == '1') {
                // Set a long-lasting cookie for "Remember Me" functionality
                    $cookieValue = base64_encode($stuLogEmail . ':' . $stuLogPass);
                    setcookie('rememberMe', $cookieValue, time() + (86400 * 30), '/'); // Cookie valid for 30 days
                }
                echo json_encode($result->num_rows);
            } else {
                echo json_encode(0); // Invalid email or password
            }
        } else {
            echo json_encode(0); // Invalid email or password
        }
    }
}


?>