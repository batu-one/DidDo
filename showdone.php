<?php

include("connect.php");

require_once 'inc/prettydatetime.php';
use PrettyDateTime\PrettyDateTime;

$datenow = date("Y-m-d H:i:s");
$objdatenow = new DateTime();

$oldestsql = mysqli_query($conn, "SELECT datestart FROM items ORDER BY datestart ASC LIMIT 1;");
$dateoldest = mysqli_fetch_row($oldestsql)[0];
$oldestdec = (strtotime($datenow) - strtotime($dateoldest)) / 86400;
$multiplier = 100 / $oldestdec;

$objdateoldest = new DateTime($dateoldest);
$datedifference = $objdateoldest->diff($objdatenow);
$gridamount = $datedifference->format('%d')/2;

$youngestsql = mysqli_query($conn, "SELECT dateend FROM items ORDER BY dateend DESC LIMIT 1;");
$dateyoungest = mysqli_fetch_row($youngestsql)[0];
$youngestdec = (strtotime($datenow) - strtotime($dateyoungest)) / 86400;
$rmultiplier = -$multiplier;

$objdateyoungest = new DateTime($dateyoungest);
$rdatedifference = $objdateyoungest->diff($objdatenow);
$rgridamount = $datedifference->format('%d')/2;

$gridstepsize = 1;

// grid left side
echo "<div id=\"gridlines\" style=\"position:absolute;top:0;right:350px;height:100%;width:350px;margin-right:9px;z-index:-1;\">";

for ($i = 0; $i < $gridamount; $i++) {
  echo "
    <div class=\"gridline" . $i . " rounded\" style=\"height:100%;background:#f9f9f9;position:absolute;right:0;font-size:0.7em;color:#d8d8d8;\">
    <div style=\"float:left;position:relative;top:-15px;left:-5px;\">-" . (2 * $i + 2) . "</div>
    <div style=\"float:right;position:relative;top:-15px;left:5px;\">-" . (2 * $i + 1) . "</div>
    </div>
  ";
}
echo "</div>";

echo "<script>";
for ($i = 0; $i < $gridamount; $i++) {
    echo "
      $(\".gridline" . $i . "\").css({
          \"margin-right\": \"" . ($multiplier * $gridstepsize) * (2 * $i + 1) . "%\",
          \"width\": \"" . $multiplier * $gridstepsize . "%\"
      });
    ";
}
echo "</script>";

// grid right side
echo "<div id=\"rgridlines\" style=\"position:absolute;top:0;left:360px;height:100%;width:350px;z-index:-1;\">";

for ($i = 0; $i < $rgridamount; $i++) {
  echo "
    <div class=\"rgridline" . $i . " rounded\" style=\"height:100%;background:#f9f9f9;position:absolute;left:0;font-size:0.7em;color:#d8d8d8;\">
    <div style=\"float:left;position:relative;top:-15px;left:-5px;\">+" . (2 * $i + 1) . "</div>
    <div style=\"float:right;position:relative;top:-15px;left:5px;\">+" . (2 * $i + 2) . "</div>
    </div>
  ";
}
echo "</div>";

echo "<script>";
for ($i = 0; $i < $rgridamount; $i++) {
    echo "
      $(\".rgridline" . $i . "\").css({
          \"margin-left\": \"" . -($rmultiplier * $gridstepsize) * (2 * $i + 1) . "%\",
          \"width\": \"" . -$rmultiplier * $gridstepsize . "%\"
      });
    ";
}
echo "</script>";

$sqllist = "SELECT * FROM items WHERE done = 1 ORDER BY datedone DESC LIMIT 5";
$result = $conn->query($sqllist);

if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {

      $checked = "";
      $style = "";

      if ($row["done"]) {
        $checked = " checked";
      }

      if ($row["style"] == "Red") {
        $style = " list-group-item-danger";
        $barstyle = " list-group-item-danger";
      } else if ($row["style"] == "Yellow") {
        $style = " list-group-item-warning";
        $barstyle = " list-group-item-warning";
      } else if ($row["style"] == "Green") {
        $style = " list-group-item-success";
        $barstyle = " list-group-item-success";
      } else if ($row["style"] == "Blue") {
        $style = " list-group-item-info";
        $barstyle = " list-group-item-info";
      } else if ($row["style"] == "Regular") {
        $barstyle = " bg-lightgrey";
      }

      $datestart = strtotime($row["datestart"]);
      $interval = strtotime($datenow) - $datestart;
      $daysspent = $interval / 86400;
      $barwidth = $multiplier * $daysspent;

      $objdatestart = new DateTime($row["datestart"]);
      $prettydatetime = PrettyDateTime::parse($objdatestart, $objdatenow);

      $datedone = strtotime($row["datedone"]);
      $baroffset = $multiplier * ((strtotime($datenow) - $datedone) / 86400);

      $dateend = strtotime($row["dateend"]);
      $interval = strtotime($datenow) - $dateend;
      $daystogo = $interval / 86400;
      $rbarwidth = $rmultiplier * $daystogo;

      $objdateend = new DateTime($row["dateend"]);
      $rprettydatetime = PrettyDateTime::parse($objdateend, $objdatenow);

      echo "<div class=\"list-group-item" . $style . "\" id=\"item_" . $row["id"] . "\" style=\"height:50px;position:relative;opacity:0.5;\">

              <div class=\"checkbox\" id=\"checkbox" . $row["id"] . "\" style=\"float:left;\">
                <label onclick=\"checkitem(" . $row["id"] . ", " . $row["done"] . ", '" . $row["content"] . "');\">
                    <input type=\"checkbox\"  value=\"\"" . $checked . " style=\"margin-right:10px;\">
                    <span class=\"cr\"><i class=\"cr-icon fa fa-check\"></i></span>
                    <span";
                    if (($row["done"])) { echo " style=\"text-decoration:line-through;\""; }
                    echo ">" . $row["content"] . "</span>
                </label>
              </div>

              <div class=\"item-options\" style=\"width:120px;float:right;margin-top:3px;position:absolute;right:20px;\">

                <i class=\"item-option fa fa-trash\" class=\"\" onclick=\"deleteitem(" . $row["id"] . ");\" style=\"float:right;margin-left:10px;\"></i>

                <i class=\"item-option fa fa-paint-brush\" onclick=\"setcolor(" . $row["id"] . ");\" style=\"float:right;margin-left:10px;\"></i>

                <i class=\"item-option fa fa-i-cursor\" onclick=\"renameitem(" . $row["id"] . ");\" style=\"float:right;margin-left:10px;\"></i>

                <i class=\"item-option fa fa-hourglass-half\" onclick=\"setdeadline(" . $row["id"] . ");\" style=\"float:right;margin-left:10px;\"></i>
              </div>


              <div class=\"floatleft\" style=\"width:350px;position:absolute;top:0;left:-10px;\">
                <div class=\"progress-bar" . $barstyle . " progress-bar-striped rounded tooltip\" title=\"" . $prettydatetime . "\" style=\"position:absolute;top:9px;left:-" . ($barwidth - 0) . "%;width:" . ($barwidth - $baroffset) . "%;height:30px;font-size:0.7em;white-space: nowrap;overflow: hidden;text-overflow: clip;\">

                </div>
              </div>
              ";

          if ($row["dateend"] > 0) {
          echo "
          <div class=\"floatright\" style=\"width:350px;position:absolute;top:0;left:360px;\">
            <div class=\"rprogress-bar" . $barstyle . " progress-bar-striped rounded tooltip\" title=\"" . $rprettydatetime . "\" style=\"position:absolute;top:9px;left:0;width:" . $rbarwidth . "%;height:30px;font-size:0.7em;white-space: nowrap;overflow: hidden;text-overflow: clip;\">

            </div>
          </div>
              ";
          }

          echo "</div>";
    }
} else {
  echo "<div class=\"list-group-item\">Nothing done yet.</div>";
}

$conn->close();

?>
