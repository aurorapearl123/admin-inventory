<br>
<div class="content">
<div class="message alert-<?php echo $class; ?> alert-icon alert" role="alert">
                  <div class="alert-left">
                    <i class="icon flaticon-exclamation"></i>
                  </div>
                  <div class="alert-text"><?php echo $msg; ?></div>
                </div>
            </div>

<script type='text/javascript'>
function redirect(url) 
{
    window.location=url;
}

function clearError()
{
	$("#bigcontainer").fadeOut("slow");
	setTimeout("redirect('"+redirect_url+"')",500);
}

function redirect_delay() 
{
	redirect_url="<?php echo $urlredirect ?>";
	setTimeout("clearError()",1000);		
}

function reloadIt() 
{ 
  window.opener.location.reload(); 
  
  return true;
} 

// function reload_select(thefunction) 
// { 
// 	if (thefunction!="") {
// 		the_func = "window.opener."+thefunction+"('<?php //echo $activeID ?>');";
// 		eval(the_func);
// 	} 
	
// 	window.close();
// }

function closeThis()
{ 
  window.close() 
}

function doIt() 
{ 
  reloadIt();
  closeThis();
} 


<?php
if ($urlredirect=="refresh") {
?>
	setTimeout("doIt()",1000);		
<?php
} else if ($urlredirect=="reload_select") {
?>
	reload_select('<?php echo $theFunction ?>');
<?php
} else if ($urlredirect) {
?>
	console.log('lets redirect');
	redirect_delay();
<?php
} 

?>
</script>	
