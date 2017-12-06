function refreshitems() {
  $.ajax({
    type    : "POST",
    url     : "showitems.php",
    success : function(response){
      $("#items-refresh").html(response);
      $('#alert-text').html("Synchronised");
            }
    });
}


// Add item
$(document).ready(function(e) {
  $('#submit').unbind();
  $('#submit').click(function() {
    var content = $('#content').val();
    var style = $('#style').val();
    $.ajax({
      type    : "POST",
      data    : {content:content,style:style},
      url     : "additem.php",
      success : function(response){
                if (response) {
                $("#content").val("");
                $.ajax({
                  type    : "POST",
                  url     : "showitems.php",
                  success : function(response){
                    $("#items-refresh").html(response);
                          }
                  });
                }
              }
      });
    });
});

// Mark item done
function checkitem(id, checked, content) {
   $.post("checkitem.php", {id:id, checked:checked, content:content},
      function(response) {
        $.ajax({
          type    : "POST",
          url     : "showitems.php",
          success : function(response){
            $("#items-refresh").html(response);
            $.ajax({
              type    : "POST",
              url     : "showdone.php",
              success : function(response){
                $("#items-done-refresh").html(response);
                      }
              });
                  }
          });
      }
   );
}

// Delete item
function deleteitem(id) {
   $.post("deleteitem.php", {id:id},
      function(response) {
        $.ajax({
          type    : "POST",
          url     : "showitems.php",
          success : function(response){
            $("#items-refresh").html(response);
            $.ajax({
              type    : "POST",
              url     : "showdone.php",
              success : function(response){
                $("#items-done-refresh").html(response);
                      }
              });
                  }
          });
      }
   );
}

// Sort items
$(function() {
  $('#items-refresh').sortable({
    update: function (event, ui) {
        var itemorder = $(this).sortable('serialize');
        $.ajax({
            data: {itemorder:itemorder},
            type: 'POST',
            url: 'sortitems.php',
            success : function(response){
              $.ajax({
                type    : "POST",
                url     : "showitems.php",
                success : function(response){
                  $("#items-refresh").html(response);
                        }
                });
            }
        });
    }
});
});

// Datetimepicker
$(".datetimepicker").datetimepicker({
  format: "yyyy-mm-dd hh:ii",
  autoclose: true
}).on('changeDate', function(dateset){
  var deadlinedate = dateset.date;
  var deadlineid = $(this).attr("id");
  console.log(deadlinedate);
  console.log(deadlineid);
  $.post("setdeadline.php", {id:deadlineid, date:deadlinedate},
     function(response) {
        $.ajax({
          type    : "POST",
          url     : "showitems.php",
          success : function(response){
            $("#items-refresh").html(response);
                  }
          });
  });
});

// tooltipster
$(document).ready(function() {
    $('.tooltip').tooltipster({
      delay: 50,
      theme: 'tooltipster-batu',
    });
});
