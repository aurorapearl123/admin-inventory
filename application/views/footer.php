<!-- Modal -->
<?php include 'modal.php'; ?>
</div> <!-- <div class="content-wrapper"> END OF BODY -->

 
</div> <!-- <div class="page-body"> -->

  <footer class="page-footer">
    <div class="container">
      <div class="d-flex">
        <div class="footer-copyright">
          <p class="mb-0"><?php echo date('Y') ?> &copy; <a href="http://www.lhprime.com" class="link" target="_new">Kitrol Performance Fuel</a>.</p>
        </div>
      </div>
    </div>
  </footer>

</div>



<script src="<?php echo base_url('assets/js/popper.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/plugins.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/modernizr.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/custom.js') ?>" type="text/javascript"></script>




<!-- Change Password -->
<div class="modal fade" id="changePassModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form method="post" name="frmChangePassword" id="frmChangePassword" action="<?php echo site_url("user/save_password") ?>">
        <input type="hidden" name="userID" id="userID" value="<?php echo $this->session->userdata('current_user')->userID ?>" />
        <input type="hidden" name="pageName" id="pageName" value="userProfile" />
        <div class="modal-header">
          <h4 class="modal-title">Change Password</h4>
        </div>
        <div class="modal-body">
          <div class="table-row">
            <table class="table-form">
              <tbody>
                <tr>
                  <td class="form-label">
                    <label for="employee">New Password • <span class="asterisk">*</span></label>
                  </td>
                  <td class="form-group form-input">
                    <input type="password" class="form-control" name="userPswd" id="userPswd" required>
                  </td>
                </tr>
                <tr>
                  <td class="form-label">
                    <label for="fmname">Re-Password • <span class="asterisk">*</span></label>
                  </td>
                  <td class="form-group form-input">
                    <input type="password" class="form-control" name="rePswd" id="rePswd" required>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" id="saveNewPasswordBtn" class="btn btn-primary btn-raised pill">Save</button>
          <button type="button" class="btn btn-outline-danger btn-raised pill" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>


<?php 
if (!empty($js)) {
  foreach ($js as $script) {
    ?>
    <script src="<?php echo base_url('assets/js/'.$script) ?>" type="text/javascript"></script>
    <?php 
  }
}

if (!empty($plugin_js)) {
  foreach ($plugin_js as $script) {
    echo $script.'sample';
    ?>
    <script src="<?php echo base_url('assets/js/plugins/'.$script) ?>" type="text/javascript"></script>
    <?php 
  }
}
?>






<script>
  $('#saveNewPasswordBtn').click(function() {
    if ($('#userPswd').val() == $('#rePswd').val()) {
      $('#frmChangePassword').submit();
    } else {
      alert("Passwords does not matched!");
    }
  });

  $('#saveNewPassPopupBtn').click(function(){
    $('#changePassModal').modal('show');
  })


  function deleteRecord(id)
  {
    swal({
      title: "Are you sure?",
      text: "Once deleted, you will not be able to recover this record!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    })
    .then((willDelete) => {
      if (willDelete.value) {
        window.location='<?php echo $controller_page."/delete/" ?>'+id;
      }
    });
  }


  // function loadRecords(id, target, source)
  // {
  //   $('#container_'+target).hide();
  //   $('#loading_img_'+target).show(); 
  //   $.ajax({
  //     type  : "POST",
  //     url   : source+'/3',
  //     data  : {id: id},
  //     timeout : <?php echo $this->config_model->getConfig('Request Timeout');?>,
  //     success : function(data){
  //       $('#container_'+target).html(data); 
  //       $('#loading_img_'+target).hide();
  //       $('#container_'+target).show();
  //     },
  //     error : function(xhr,textStatus,errorThrown) {  //alert(errorThrown);
  //       $('#loading_img_'+target).hide();
  //       if(textStatus=="timeout"){
  //         $('#msgstatus_'+target).html(' <div class="errorbox" style="display:block;" id="bigcontainer"><div class="boxcontent" id="msgcontainer"><strong>Sorry, the request has been longer than necessary. Please refresh the page or contact an Administrator.</strong></div></div>').slideDown();
  //       }else{        
  //         $('#msgstatus_'+target).html(' <div class="errorbox" style="display:block;" id="bigcontainer"><div class="boxcontent" id="msgcontainer"><strong>Internal Server Error! Please contact an Administrator.</strong></div></div>').slideDown();
  //       }
  //     }               
  //   });
  // }

  function display_session_items(item_name, display_area) 
  {   
    $.post('<?php echo $controller_page ?>/display_session_items/'+display_area, { sessionSet: item_name },
      function(data){
        $('#'+display_area).html(data);
      }, "text");
  }

  function add_session_item(form_source, fields, display_area, do_reset=1, returnField="", success_msg="", error_msg="", checkDuplicate=0, duplicateField="", callback="") 
  {

    if (form_source) {
      if (success_msg=="") {
        success_msg = "Successfully added!";
      }

      if (error_msg=="") {
        error_msg = "Adding failed!";
      }
      fields = fields.replace(/,/gi,"_"); 
      $.post('<?php echo site_url()?>'+"sessionmanager/push_session_item/"+fields+"/"+checkDuplicate+"/"+duplicateField, $('#'+form_source).serialize(),
        function(data){
        //alert(data);
        if (parseInt(data)==1) {
        //alert(success_msg);
      } else if (parseInt(data)==2) {
        alert("Duplicate data");
      } else {
        alert(error_msg);
      }

      if (do_reset) {
        // reset form
        resetForm(form_source);
        // return field
        if (returnField) {
          $('#'+form_source+' #'+returnField).focus();
        }
      }
      if (display_area != "") {
        display_session_items($('#'+form_source+' #sessionSet').val(), display_area);
      }

      if (callback != "") {
        eval(callback);
      }
      
    }, "text");
    }
  }

  function delete_session_item(item_name, item_id, display_area,callback="") 
  {
    swal({
      title: "Are you sure you want to delete this column?",
      text: "",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes',
      cancelButtonText: 'No'
    })
    .then((willDelete) => {
      if (willDelete.value) {
        $.post('<?php echo site_url()?>'+"/sessionmanager/delete_session_item", { sessionSet: item_name, targetID: item_id },
          function(data){
            if (parseInt(data)==1) {
          //alert("Successfully deleted!");

        } else {
          alert("Delete failed!");
        }
          //        
          if (display_area != "") {
            display_session_items(item_name, display_area);
          }
          //
          fn = window[callback];
          if (typeof fn === "function") fn();
          //
          if (callback != "") {
            eval(callback);
          }
        }, "text");
      }
    });




//  reply=confirm("Confirm delete row?");
//    
//    if (reply==true) {
//    $.post(site_url+"/sessionmanager/delete_session_item", { sessionSet: item_name, targetID: item_id },
//      function(data){
//      if (parseInt(data)==1) {
//        //alert("Successfully deleted!");
//      } else {
//        alert("Delete failed!");
//      }
//        
//      if (display_area != "") {
//        display_session_items(item_name, display_area);
//      }
//
//      fn = window[callback];
//      if (typeof fn === "function") fn();
//
////      if (callback != "") {
////        eval(callback);
////      }
//      }, "text");
//    }
}

function is_session_empty(item_name) 
{
  $.post(site_url+"/sessionmanager/is_session_empty/"+item_name, {},
    function(data){
      if (parseInt(data)==1) {

      } else {

      }
    }, "text");
}

function clear_session(sessionSet)
{
  $.post(site_url+"/sessionmanager/clear_session/", { 
    sessionSet: sessionSet});
}


function resetForm(id) {
  $('#'+id).each(function(){
    this.reset();
  });
}
</script>

<script type="text/javascript">
function isNumber(evt)
{
   var charCode = (evt.which) ? evt.which : event.keyCode
   if (charCode != 46 && charCode > 31 
     && (charCode < 48 || charCode > 57))
      return false;

   return true;
}

$('.check-all').click(function(event) { //on click
    if(this.checked) { // check select status
        $('.chkAdd').each(function() { //loop through each checkbox
            this.checked = true;  //select all checkboxes with class "checkbox1"              
        });
    }else{
        $('.chkAdd').each(function() { //loop through each checkbox
            this.checked = false; //deselect all checkboxes with class "checkbox1"                      
        });        
    }
});

function message_popup(message="Error!") {
  swal(message);
}
//var autoTest = setInterval(for_auto_test,10000);
//    function for_auto_test() {
//        // ajax call
//        $.ajax({
//            url: "<?php //echo site_url('company/bridge') ?>",
//            method: "POST",               
//            dataType: "text",
//            success: function(data) {
//                alert(data);      
////                if (parseInt(data) == '1') {
////                  alert('Data Found');                      
////                } else {
////                	alert('Data Not Found');   
////                }
////                console.log(data);
//            }
//        }); 
//    } 




// $('div.dropdown-menu.open li').on('keydown', function (e) {

//         if (e.keyCode == 38) { // Up
//           var previousEle = $(this).prev();
//           if (previousEle.length == 0) {
//             previousEle = $(this).nextAll().last();
//           }
//           var selVal = $('.form-control option').filter(function () {
//             return $(this).text() == previousEle.text();
//           }).val();
//           $('.form-control').selectpicker('val', selVal);

//           return;
//         }
//         if (e.keyCode == 40) { // Down
//           console.log('ss');
          
//           var nextEle = $(this).next();
//           if (nextEle.length == 0) {
//             nextEle = $(this).prevAll().last();
//           }
//           var selVal = $('.form-control option').filter(function () {
//             return $(this).text() == nextEle.text();
//           }).val();
//           $('.form-control').selectpicker('val', selVal);

//           return;
//         }
//       });

</script>
<script>
//format textfield to auto dash tin
$(function () {
    $('#tin').keydown(function (e) {
     var key = e.charCode || e.keyCode || 0;
     $text = $(this); 
     if (key !== 8 && key !== 9) {
         if ($text.val().length === 3) {
             $text.val($text.val() + '-');
         }
         if ($text.val().length === 7) {
             $text.val($text.val() + '-');
         }
         if ($text.val().length === 11) {
             $text.val($text.val() + '-');
         }

     }
     return (key == 8 || key == 9 || key == 46 || (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
 })
});
//format textfield to auto dash sss
$(function () {
    $('#sssNo').keydown(function (e) {
     var key = e.charCode || e.keyCode || 0;
     $text = $(this); 
     if (key !== 8 && key !== 9) {
         if ($text.val().length === 2) {
             $text.val($text.val() + '-');
         }
         if ($text.val().length === 10) {
             $text.val($text.val() + '-');
         }
     }
     return (key == 8 || key == 9 || key == 46 || (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
 })
});
//format textfield to auto dash philhealth
$(function () {
    $('#philhealthNo').keydown(function (e) {
     var key = e.charCode || e.keyCode || 0;
     $text = $(this); 
     if (key !== 8 && key !== 9) {
         if ($text.val().length === 2) {
             $text.val($text.val() + '-');
         }
         if ($text.val().length === 12) {
             $text.val($text.val() + '-');
         }
     }
     return (key == 8 || key == 9 || key == 46 || (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
 })
});
//format textfield to auto dash hdmf
$(function () {
    $('#pagibigNo').keydown(function (e) {
     var key = e.charCode || e.keyCode || 0;
     $text = $(this); 
     if (key !== 8 && key !== 9) {
         if ($text.val().length === 4) {
             $text.val($text.val() + '-');
         }
         if ($text.val().length === 9) {
             $text.val($text.val() + '-');
         }
     }
     return (key == 8 || key == 9 || key == 46 || (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
 })
});


$(function () {
$('td,th').click(function() {

  // var select = $(this).find('select.form-control');
  // // console.log('sss', select[0].id);

  // var id = '#'+select[0].id;

  //   $(id+'.form-control').selectpicker({
  //   style: 'btn-info',
  //   size: 10
  // });
  $('div.dropdown-menu.open li').on('keydown', function (e) {
    if (e.keyCode == 38) { // Up
      console.log('up');
      var previousEle = $(this).prev();
      if (previousEle.length == 0) {
        previousEle = $(this).nextAll().last();
      }
      var selVal = $(id+'.form-control option').filter(function () {
        return $(this).text() == previousEle.text();
      }).val();
      $(id+'.form-control').selectpicker('val', selVal);

      return;
    }
    if (e.keyCode == 40) { // Down
      console.log('down');
      var nextEle = $(this).next();
      if (nextEle.length == 0) {
        nextEle = $(this).prevAll().last();
      }
      var selVal = $(id+'.form-control option').filter(function () {
        return $(this).text() == nextEle.text();
      }).val();
      $(id+'.form-control').selectpicker('val', selVal);

      return;
    }

    if (e.keyCode == 13) { // Enter
      console.log('enter');

      $(id+'.form-control').trigger('change');
      $('div.dropdown-menu.open').removeClass('show');

      return;
    }
  });
});
});





// $(function () {
  function myArrows(id) {
  console.log('hello',id);


}

// sayHello();
// const sayHello = () => {
//   console.log("Hello !");
// }






$(document).ready(function(){
  get_notifications();

});

function get_notifications() {
  $.ajax({
    method:'GET',
    url: '<?php echo site_url('generic_ajax/get_notifications')?>',
    dataType: 'json',
    success: function(response){
    
      var notifications = response.notifications;
      // var dueDate = response.dueDate;
      // var reorderItems = response.reorderItems;
      
      console.log(notifications);

      var new_notifications = 0;
      $.each(notifications, function(i, row){
        if (row.isRead == 0) {
          new_notifications += 1;
        }
      });
      

      var list = '';
      list += '<a href="#" class="nav-link dropdown-toggle';
      if (new_notifications > 0) {
        list += ' has-notification';
      } else {
        list += ' ';
      }

      list += '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="badge badge-danger notification-badge">&nbsp;</span><i class="icon flaticon-music-2"></i></a>';
      list += '<div class="dropdown-menu wrap dropdown-menu-right">';
      list += '<div class="dropdown-header">';
      list += '<span class="dropdown-title">'+new_notifications+'</span>';
      list += '<span class="dropdown-sub-title">User Notifications</span>';
      list += '</div>';
      list += '<div class="dropdown-body">';
      // list += '<div class="scrollable-wrap">';
      list += '<div class="scroll-wrapper scrollable-wrap" style="position: relative;"><div class="scrollable-wrap scroll-content scroll-scrolly_visible" style="height: auto; margin-bottom: 0px; margin-right: 0px; max-height: 250px;">';
      list += '<ul class="timeline-list">';

      //Notifications from message board
      $.each(notifications, function(i, row){
       
       var time = new Date(row.dateCreated);
       var elapsed = calculate_elapsed_time(time);
       console.log(elapsed);

       list += '<li class="timeline-list-item warning">';
        
       list += '<div class="timeline-list-badge"></div>';
       list += '<div class="timeline-list-text">'+row.message+' <a class="link" href="<?php echo site_url('purchase_order/view_decoded/') ?>'+row.paramID+'/'+row.id+'">Check<div class="ripple-container"></div></a></div>';
       list += '<div class="timeline-list-time">'+elapsed+'</div>';
       list += '</li>';
      });


      list += '</ul>';
      list += '</div>';
      list += '</div>';

      // list += '<div class="dropdown-footer">';
      // list += '<a class="btn btn-sm pill btn-outline-primary btn-raised" href="<?php //echo site_url('messages') ?>">View All</a>';
      // list += '</div>';


      list += '</div>';
      // console.log(list);
      $('#notifications').html(list);
    }

  });
}

function calculate_elapsed_time(startTime) {
  endTime = new Date();

  var timeDiff = endTime - startTime; //in ms
  // strip the ms
  timeDiff /= 1000;

  // get seconds 
  var seconds = Math.round(timeDiff);
  console.log(seconds + " seconds");

  return secondsToDhms(seconds);
}

function secondsToDhms(seconds) {
seconds = Number(seconds);
var d = Math.floor(seconds / (3600*24));
var h = Math.floor(seconds % (3600*24) / 3600);
var m = Math.floor(seconds % 3600 / 60);
var s = Math.floor(seconds % 3600 % 60);

var dDisplay = d > 0 ? d + (d == 1 ? " day, " : " days, ") : "";
var hDisplay = h > 0 ? h + (h == 1 ? " hour, " : " hours, ") : "";
var mDisplay = m > 0 ? m + (m == 1 ? " minute, " : " minutes, ") : "";
var sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";
// return dDisplay + hDisplay + mDisplay + sDisplay;
return hDisplay;
}
</script>





</body>
</html>