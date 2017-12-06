<?php

include("connect.php");


if($_REQUEST['id']) {
  $id = $_POST['id'];

  $sql = "INSERT INTO trash SELECT * FROM items WHERE id = $id;";
  $sql .= "DELETE FROM items WHERE id = $id;";

  $query = mysqli_multi_query($conn,$sql);
  if ($query) {
    echo "Moved item to trash";
  }
  else {
    echo $sql . mysqli_error($conn);
  }

}

$conn->close();

?>
