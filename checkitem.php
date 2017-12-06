<?php

include("connect.php");


if($_REQUEST['id']) {
  $id = $_POST['id'];
  $checked = $_POST['checked'];
  $content = $_POST['content'];
  $datetime = date('Y-m-d H:i:s');

  if ($checked == "0") {
    $sql = "UPDATE items SET done = 1, datedone = '$datetime' WHERE id = $id";
  } else if ($checked == "1") {
    $sql = "UPDATE items SET done = 0, datedone = '0' WHERE id = $id";
  }

  $query = mysqli_query($conn,$sql);
  if ($query) {
    
  }
  else {
    echo $sql . mysqli_error($conn);
  }

}

$conn->close();

?>
