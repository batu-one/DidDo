<?php

include("connect.php");


if($_REQUEST['itemorder']) {
  $itemorder = explode("&", str_replace("itemcontainer[]=", "", $_POST['itemorder']));

  $itemamountsql = mysqli_query($conn, "SELECT * FROM items WHERE userid = 0;");
  $itemamount = mysqli_num_rows($itemamountsql);

  $sql = "";
  $i = 0;

for ($i = 0; $i < $itemamount; $i++) {
  $sql .= "UPDATE items SET position = $i WHERE id = $itemorder[$i];";
}

  mysqli_multi_query($conn,$sql);

}

$conn->close();

?>
