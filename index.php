<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
?>
<html>
  <head>
   <title>DidDo</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="inc/bootstrap.min.css">
   <link rel="stylesheet" href="inc/bootstrap-datetimepicker.css">
   <link rel="stylesheet" href="inc/tooltipster.bundle.css" />
   <link rel="stylesheet" href="css.css">
   <script src="inc/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
   <script src="inc/bootstrap.min.js"></script>
   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   <script src="https://use.fontawesome.com/844a541925.js"></script>
   <script src="inc/bootstrap-datetimepicker.js"></script>
   <script src="inc/tooltipster.bundle.min.js"></script>
 </head>
 <body>
   <nav class="navbar" style="height:45px;">
     <div class="container container-small">
       <div class="navbar-header">
         <h1 style="display:inline;font-size:1.8em;">DidDo</h1>
         <span style="position:relative;top:-18px;left:-15px;color:#f73d3d;font-size:0.7em;font-style:italic;"><strong>alpha</strong></span>
       </div>
       <div class="nav navbar-nav">

         <div class="row">
            <div class="col" style="padding:5px;">

              <button onclick="$('#newitemform').slideToggle();" class="btn btn-sm tooltip" title="Add item" type="button" style="float:left;background:#444;color:#e3e3e3;height:27px;display:block;cursor:pointer;">
                <i class="fa fa-plus" aria-hidden="true"></i>
              </button>

            </div>
            <div class="col" style="padding:5px;">

              <button onclick="$('#optionview').slideToggle();" class="btn btn-sm tooltip" title="View" type="button" style="float:left;background:#444;color:#e3e3e3;height:27px;display:block;cursor:pointer;">
                <i class="fa fa-eye" aria-hidden="true"></i>
              </button>

            </div>
            <div class="col" style="padding:5px;">

              <button class="btn btn-sm tooltip" title="Settings" type="button" style="float:left;background:#444;color:#e3e3e3;height:27px;display:block;cursor:pointer;">
                <i class="fa fa-cog" aria-hidden="true"></i>
              </button>

            </div>
            <div class="col" style="padding:5px;">

              <button id="refreshitems" onclick="refreshitems()" class="btn btn-sm tooltip" title="Refresh" type="button" style="float:left;background:#444;color:#e3e3e3;height:27px;display:block;cursor:pointer;">
                <i class="fa fa-refresh" aria-hidden="true"></i>
              </button>

            </div>
        </div>

       </div>

     </div>
   </nav>

<div style="padding-top:70px;width:100%;position:relative;overflow-x:hidden;">

  <div id="newitemform">
    <form role="form">
      <div class="row" style="margin-left:10px;padding-top:-10px;padding-bottom:20px;">
        <div class="col-md-auto" style="padding:10px;">
          <input id="content" class="form-control" type="text" placeholder="New item..." style="width:205px;">
        </div>
        <div class="col-md-auto" style="padding:10px;">
          <select id="style" class="form-control" style="width:100px;">
            <option value="Regular">Color...</option>
            <option value="Red">Red</option>
            <option value="Yellow">Yellow</option>
            <option value="Green">Green</option>
            <option value="Blue">Blue</option>
          </select>
        </div>
        <div class="col-md-auto" style="padding:10px;">
          <button id="submit" class="btn" type="button" style="height:38px;width:55px;">Add</button>
        </div>
      </div>
    </form>
  </div>

  <div id="optionview">
    <form role="form">
      <div class="row" style="margin-left:10px;padding-top:-10px;padding-bottom:20px;">
        <div class="col-md-auto" style="padding:10px;">
          // view options go here
        </div>
      </div>
    </form>
  </div>

<div id="mainlist">

  <div class="list-group" id="items-refresh" style="margin-bottom:10px;position:relative;">

    <?php include("showitems.php"); ?>

  </div>

<!--
  <div class="list-group" id="items-done-refresh" style="margin-bottom:10px;position:relative;">

    <?php include("showdone.php"); ?>

  </div>
-->
</div> <!-- END mainlist -->

</div>



</body>
</html>
