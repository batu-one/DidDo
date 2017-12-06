<?php

include("connect.php");

date_default_timezone_set('Europe/Amsterdam');

if($_REQUEST['content']) {
  $content = $_POST['content'];
  $style = $_POST['style'];
  $datetime = date('Y-m-d H:i');

  $itemammountsql = mysqli_query($conn, "SELECT * FROM items WHERE userid = 0;");
  $itemammount = mysqli_num_rows($itemammountsql);

  $sqladd = "INSERT INTO items (userid, content, style, position, done, datestart) VALUES ('0', '$content', '$style', '$itemammount+1', '0', '$datetime')";

  $query = mysqli_query($conn,$sqladd);
  if ($query) {
    $contentshort = strlen($_POST['content']) > 16 ? substr($_POST['content'], 0, 16)."..." : $_POST['content'];
    echo "Added <strong>'" . $contentshort . "'</strong>";
  }
  else {
    echo $sqladd . mysqli_error($conn);
  }

}

$conn->close();

?>
