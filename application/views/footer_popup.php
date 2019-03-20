

<script src="<?php echo base_url('assets/js/popper.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/plugins.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/modernizr.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/custom.js') ?>" type="text/javascript"></script>
<script>

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

	function cancelAction(controller,func,isPopup)
	{
			window.close();
	}

	function add_session_item(form_source, fields, display_area, do_reset=1, returnField="", success_msg="", error_msg="", checkDuplicate=0, duplicateField="", callback="") 
	{
	// if (check_form(form_source)) {
		if (success_msg=="") {
			success_msg = "Successfully added!";
		}

		if (error_msg=="") {
			error_msg = "Adding failed!";
		}

		fields = fields.replace(/,/gi,"_"); 
		
		$.post(site_url+"/sessionmanager/push_session_item/"+fields+"/"+checkDuplicate+"/"+duplicateField, $('#'+form_source).serialize(),
			function(data){

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
					$('#'+returnField).focus();
				}
			}

			if (display_area != "") {
				display_session_items($('#sessionSet').val(), display_area);
			}

			if (callback != "") {
				eval(callback);
			}
			
		}, "text");
	// }
}

function delete_session_item(item_name, item_id, display_area,callback="") 
{
	swal({
		title: "Confirm delete row?",
		text: "",
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, delete it!'
	})
	.then((willDelete) => {
		if (willDelete.value) {
			$.post(site_url+"/sessionmanager/delete_session_item", { sessionSet: item_name, targetID: item_id },
				function(data){
					if (parseInt(data)==1) {
				//alert("Successfully deleted!");
			} else {
				swal("Delete failed!","Unable to delete record.","warning");
			}

			if (display_area != "") {
				display_session_items(item_name, display_area);
			}

			fn = window[callback];
			if (typeof fn === "function") fn();

			if (callback != "") {
				eval(callback);
			}
		}, "text");
		}
	});
}

function display_session_items(sessionSet, display_area) 
{  	
	$.post('<?php echo $controller_page ?>/display_session_items/'+display_area, { sessionSet: sessionSet },
		function(data){
			$('#'+display_area).html(data);
		}, "text");
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

function resetForm(id) {
	$('#'+id).each(function(){
		this.reset();
	});
}
</script>

</body>
</html>