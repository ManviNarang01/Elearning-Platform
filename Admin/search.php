<?php
// search.php

include('../dbConnection.php');

if (isset($_POST['searchTerm'])) {
  $searchTerm = $_POST['searchTerm'];

 $sql = "SELECT * FROM course WHERE LOWER(course_name) LIKE LOWER('%$searchTerm%')";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    echo '<table class="table">
            <thead>
              <tr>
                <th scope="col">Course ID</th>
                <th scope="col">Name</th>
                <th scope="col">Author</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>';

    while ($row = $result->fetch_assoc()) {
  echo '<tr>';
  echo '<th scope="row">' . $row["course_id"] . '</th>';
  echo '<td>' . $row["course_name"] . '</td>';
  echo '<td>' . $row["course_author"] . '</td>';
  echo '<td>
          <form action="editcourse.php" method="POST" class="d-inline">
            <input type="hidden" name="id" value="' . $row["course_id"] . '">
            <button type="submit" class="btn btn-info mr-3" name="view" value="View">
              <i class="fas fa-pen"></i> Edit
            </button>
          </form>
          <form action="" method="POST" class="d-inline">
            <input type="hidden" name="id" value="' . $row["course_id"] . '">
            <button type="submit" class="btn btn-secondary" name="delete" value="Delete">
              <i class="far fa-trash-alt"></i> Delete
            </button>
          </form>
        </td>';
  echo '</tr>';
}


    echo '</tbody>
          </table>';
  } else {
    echo '<p>No results found.</p>';
  }
}
?>
