<?php

include("connect.php");


if($_REQUEST['id']) {
  $id = substr($_POST['id'], 14);
  $convertdate = DateTime::createFromFormat('D M d Y H:i:s', substr($_POST['date'], 0, 24));
  $deadlinedate = $convertdate->format('Y-m-d H:i');

  $sql = "UPDATE items SET dateend = '$deadlinedate' WHERE id = '$id';";

  $query = mysqli_query($conn,$sql);

}

$conn->close();

?>
