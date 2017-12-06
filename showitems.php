<?php

include("connect.php");

require_once 'inc/prettydatetime.php';
use PrettyDateTime\PrettyDateTime;

$datenow = date("Y-m-d H:i:s");
$objdatenow = new DateTime();

$oldestsql = mysqli_query($conn, "SELECT datestart FROM items ORDER BY datestart ASC LIMIT 1;");
$dateoldest = mysqli_fetch_row($oldestsql)[0];
$oldestdec = (strtotime($datenow) - strtotime($dateoldest)) / 86400;

$objdateoldest = new DateTime($dateoldest);
$datedifference = $objdateoldest->diff($objdatenow);
$gridamount = $datedifference->format('%d')/2;

$youngestsql = mysqli_query($conn, "SELECT dateend FROM items ORDER BY dateend DESC LIMIT 1;");
$dateyoungest = mysqli_fetch_row($youngestsql)[0];
$youngestdec = (strtotime($datenow) - strtotime($dateyoungest)) / 86400;

$objdateyoungest = new DateTime($dateyoungest);
$rdatedifference = $objdateyoungest->diff($objdatenow);
$rgridamount = $datedifference->format('%d')/2;

$gridamount = 10;
$rgridamount = $gridamount;

$multiplier = 8;//100 / $oldestdec;
$rmultiplier = -$multiplier;

$gridstepsize = 7 / 1;

// grid left side
echo "<div id=\"gridlines\" style=\"position:absolute;top:0;right:350px;height:100%;width:" . $multiplier . "vw;margin-right:9px;z-index:-1;\">";


for ($i = 0; $i < $gridamount; $i++) {
  echo "
    <div class=\"gridline" . $i . " rounded\" style=\"height:100%;background:#383838;position:absolute;right:0;font-size:0.7em;color:#555;\">
    <div style=\"width:100%;height:15px;position:absolute;top:-20px;left:-15%;\">" . (2 * $i + 2) * ($gridstepsize * 24) . "</div>
    <div style=\"width:100%;height:15px;position:absolute;top:-20px;left:85%;\">" . (2 * $i + 1) * ($gridstepsize * 24) . "</div>
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
echo "<div id=\"rgridlines\" style=\"position:absolute;top:0;left:360px;height:100%;width:" . $multiplier . "vw;z-index:-1;\">";

for ($i = 0; $i < $rgridamount; $i++) {
  echo "
    <div class=\"rgridline" . $i . " rounded\" style=\"height:100%;background:#383838;position:absolute;left:0;font-size:0.7em;color:#555;\">
    <div style=\"width:100%;height:15px;position:absolute;top:-20px;left:-15%;\">" . (2 * $i + 1) * ($gridstepsize * 24) . "</div>
    <div style=\"width:100%;height:15px;position:absolute;top:-20px;left:85%;\">" . (2 * $i + 2) * ($gridstepsize * 24) . "</div>
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

$sql = "SELECT * FROM items ORDER BY done, datestart DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {

      if ($row["done"]) {

        $checked = " checked";
        $datestart = strtotime($row["datestart"]);
        $interval = strtotime($row["datedone"]) - $datestart;
        $daysspent = $interval / 86400;
        $barwidth = $multiplier * $daysspent;
        $baroffset = $barwidth + ((strtotime($datenow) - strtotime($row["datedone"])) / 86400) * $multiplier;

      } else {

        $checked = null;
        $datestart = strtotime($row["datestart"]);
        $interval = strtotime($datenow) - $datestart;
        $daysspent = $interval / 86400;
        $barwidth = $multiplier * $daysspent;
        $baroffset = $barwidth;
      }

      $objdatestart = new DateTime($row["datestart"]);
      $prettydatetime = PrettyDateTime::parse($objdatestart, $objdatenow);

      $dateend = strtotime($row["dateend"]);
      $interval = strtotime($datenow) - $dateend;
      $daystogo = $interval / 86400;
      $rbarwidth = $rmultiplier * $daystogo;

      $objdateend = new DateTime($row["dateend"]);
      $rprettydatetime = PrettyDateTime::parse($objdateend, $objdatenow);

      echo "<div id=\"itemcontainer_" . $row["id"] . "\" style=\"position:relative;\">

            <div class=\"floatleft\" style=\"width:" . $multiplier . "vw;position:absolute;top:0;left:-10px;\">
              <div class=\"progress-bar " . $row["style"] . " progress-bar-striped rounded tooltip\" title=\"" . $prettydatetime . " (" . $row['datestart'] . ")\" style=\"position:absolute;top:9px;left:-" . $baroffset . "%;width:" . $barwidth . "%;height:30px;font-size:0.7em;white-space: nowrap;overflow: hidden;text-overflow: clip;\">
              </div>
            </div>
          ";

      if ($row["dateend"] > 0) {
      echo "
            <div class=\"floatright\" style=\"width:" . $multiplier . "vw;position:absolute;top:0;left:360px;\">
              <div class=\"rprogress-bar " . $row["style"] . " progress-bar-striped rounded tooltip\" title=\"" . $rprettydatetime . " (" . $row['dateend'] . ")\" style=\"position:absolute;top:9px;left:0;width:" . $rbarwidth . "%;height:30px;font-size:0.7em;white-space: nowrap;overflow: hidden;text-overflow: clip;\">
              </div>
            </div>
          ";
      }

      echo "
            <div class=\"list-group-item rounded " . $row["style"] . "\" id=\"item_" . $row["id"] . "\" style=\"height:50px;position:relative;margin-bottom:5px;\">
              <div class=\"checkbox\" id=\"checkbox" . $row["id"] . "\" style=\"float:left;\">
                <label onclick=\"checkitem(" . $row["id"] . ", " . $row["done"] . ", '" . $row["content"] . "');\">
                    <input type=\"checkbox\"  value=\"\"" . $checked . ">
                    <span class=\"cr\"><i class=\"cr-icon fa fa-check\"></i></span>
                </label>
                <span style=\"";
                if (($row["done"])) { echo "text-decoration:line-through;"; }
                echo "margin-right:10px;float:right;\">" . $row["content"] . "</span>
              </div>

              <div class=\"item-options\" style=\"width:120px;float:right;margin-top:3px;position:absolute;right:20px;\">

                <i class=\"item-option fa fa-trash\" class=\"\" onclick=\"deleteitem(" . $row["id"] . ");\" style=\"float:right;margin-left:10px;\"></i>

                <i class=\"item-option fa fa-paint-brush\" onclick=\"setcolor(" . $row["id"] . ");\" style=\"float:right;margin-left:10px;\"></i>

                <i class=\"item-option fa fa-i-cursor\" onclick=\"renameitem(" . $row["id"] . ");\" style=\"float:right;margin-left:10px;\"></i>

                <i class=\"item-option fa fa-hourglass-half\" onclick=\"$('#deadline-input" . $row["id"] . "').toggle();\" style=\"float:right;margin-left:10px;\"></i>

              </div>

              <div class=\"datetimepicker\" id=\"deadline-input" . $row["id"] . "\" style=\"position:absolute;right:0px;top:30px;z-index:1000;display:none;font-size:0.5em;\"></div>

            </div>

            </div>
        ";

    }
} else {
  echo "<div class=\"list-group-item\">Nothing to do yet.</div>";
}

$conn->close();

echo "
  <script>
  var now = new Date(Date.now());
  var synctime = ('0' + now.getHours()).slice(-2) + \":\" + ('0' + now.getMinutes()).slice(-2) + \":\" + ('0' + now.getSeconds()).slice(-2);
    $(\"#refreshitems\").prop('title', 'Synced ' + synctime);
  </script>
<script src=\"js.js\"></script>
";

?>
