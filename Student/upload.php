<?php
session_start();
include_once('../dbConnection.php');
define('TITLE', 'upload');
define('PAGE', 'upload');
include('./stuInclude/header.php'); 

if(isset($_SESSION['is_login'])){
  $stuLogEmail = $_SESSION['stuLogEmail'];
} else {
  header("Location: ../index.php");
  exit();
}
?>

<div class="col-sm-6 mt-5">
  <?php
  //delete
  if(isset($_GET['id'])){
  $fileId = $_GET['id'];

  // Retrieve the file details from the database
  $retrieveSql = "SELECT filename FROM files WHERE id = '$fileId' AND uploaded_by = '$stuLogEmail'";
  $result = mysqli_query($conn, $retrieveSql);

  if(mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    $fileName = $row['filename'];

    // Delete the file from the server
    $filePath = "uploads/" . $fileName;
    if(file_exists($filePath)){
      unlink($filePath);
    }

    // Delete the file from the database
    $deleteSql = "DELETE FROM files WHERE id = '$fileId'";
    if(mysqli_query($conn, $deleteSql)){
      echo "File deleted: $fileName";
    } else {
      echo "Failed to delete file.";
    }
  } else {
    echo "File not found.";
  }
} else {
  echo "Invalid request.";
}
  //delete

  if(isset($_FILES['file'])){
    $errors = array();

    // Directory where uploaded files will be stored
    $targetDirectory = "uploads/";

    // Iterate through each uploaded file
    foreach($_FILES['file']['tmp_name'] as $key => $tmp_name){
      $fileName = $_FILES['file']['name'][$key];
      $fileSize = $_FILES['file']['size'][$key];
      $fileType = $_FILES['file']['type'][$key];
      $fileTmp = $_FILES['file']['tmp_name'][$key];
      $fileError = $_FILES['file']['error'][$key];

      // Check for file errors
      if($fileError === 0){
        // Generate a unique name for the file to avoid conflicts
        $newFileName = uniqid('', true) . '_' . $fileName;

        // Move the uploaded file to the target directory
        if(move_uploaded_file($fileTmp, $targetDirectory . $newFileName)){
          // Insert the file details into the database
          $sql = "INSERT INTO files (filename, uploaded_by) VALUES ('$newFileName', '$stuLogEmail')";
          if(mysqli_query($conn, $sql)){
            // File uploaded and stored in the database successfully
            echo "File uploaded: $fileName<br>";
          } else {
            // Error storing file details in the database
            // $errors[] = "Failed to store file: $fileName";
          }
        } else {
          // Error moving the uploaded file
          // $errors[] = "Failed to upload file: $fileName";
        }
      } else {
        // Error with the uploaded file
        // $errors[] = "Error with file: $fileName";
      }
    }

    // Display any errors encountered during file upload
    if(!empty($errors)){
      foreach($errors as $error){
        echo $error . "<br>";
      }
    }
  }
  ?>

  <form action="upload.php" method="post" enctype="multipart/form-data">
    <label for="file">Select Files:</label>
    <input type="file" name="file[]" multiple required><br><br>
    <input type="submit" name="submit" value="Upload">
  </form>

  <?php
  // Retrieve the uploaded files from the database
  $retrieveSql = "SELECT id, filename FROM files WHERE uploaded_by = '$stuLogEmail'";
  $result = mysqli_query($conn, $retrieveSql);

  if(mysqli_num_rows($result) > 0){
    echo "<h4>List of Uploaded Files:</h4>";
    echo "<table class='table'>";
    echo "<tr>";
    echo "<th>File Name</th>";
    echo "<th>Action</th>";
    echo "</tr>";

    while($row = mysqli_fetch_assoc($result)){
      echo "<tr>";
      echo "<td>".$row['filename']."</td>";
      echo "<td><a href='upload.php?id=".$row['id']."'>Delete</a></td>";
      echo "</tr>";
    }

    echo "</table>";
  } else {
    echo "<p>No files uploaded yet.</p>";
  }
  ?>
</div>

</div> <!-- Close Row Div from header file -->
<?php
include('./stuInclude/footer.php'); 
?>
