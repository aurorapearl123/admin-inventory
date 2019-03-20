<div class="subheader">
	<div class="d-flex align-items-center">
		<div class="title mr-auto">
			<h3><i class="icon left la <?php echo $current_module['icon'] ?>"><?php echo strtoupper($current_module['title']) ?></i> </h3>
		</div>
		<div class="subheader-tools">
			<a href="<?php echo $controller_page?>/show" class="btn btn-primary btn-raised btn-sm pill"><i class="icon ti-angle-left"></i> Back to List</a>
		</div>
	</div>
</div>
<form method="post" name="frmEntry" id="frmEntry" action="<?php echo $controller_page ?>/save" enctype="multipart/form-data">
<div class="content">
	<div class="row">
		<div class="col">
			<div class="card-box" style="height:350px">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="head-text"><i class="icon left la <?php echo $current_module['icon'] ?>"></i>Add <?php echo strtoupper($current_module['title']) ?></h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<div class="table-row">
						<table class="table-form column-3" border="0">
							<tbody>
							
								<tr>

									<td class="form-label" width="120px" nowrap>Time in: <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">
									<input type="time" id="startTime" class="form-control" name="startTime" min="9:00" title="Time in" max="18:00" required>
										

									</td>
									<td class="form-label" width="120px" nowrap>Time out: <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">
										<input type="time" class="form-control" id="endTime" name="endTime" title="Time Out" min="9:00" max="18:00" required>
									</td>
									
									<td>&nbsp;</td>
								</tr>
							

							</tbody>
						</table>
					</div>
				</div>
				
				<div class="card-body">
					
					<div class="form-sepator solid"></div>
					<div class="form-group mb-0">
						<button class="btn btn-primary btn-raised pill" type="button" name="cmdSave" id="cmdSave">
						Save
						</button>
						<input type="button" id="cmdCancel" class="btn btn-outline-danger btn-raised pill" value="Cancel"/>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>
</form>

<script>
$(document).ready(function(){
	




});



	$('#cmdSave').click(function(){
		if (check_fields()) {
	    	$('#cmdSave').attr('disabled','disabled');
	    	$('#cmdSave').addClass('loader');
	        $.post("<?php echo $controller_page ?>/check_duplicate", { startTime: $('#startTime').val(), endTime: $('#endTime').val() },
	          function(data){
	            if (parseInt(data)) {
	            	$('#cmdSave').removeClass("loader");
	            	$('#cmdSave').removeAttr('disabled');
	              	// duplicate
	              	swal("Duplicate","Record is already exist!","warning");
	            } else {
							
	            	// submit
	            	$('#frmEntry').submit();
	            }
	          }, "text");
		}
	});
	
	
	function check_fields()
	{
		 var valid = true;
		 var req_fields = "";
		 
		 $('#frmEntry [required]').each(function(){
		    if($(this).val()=='' ) {
		    	req_fields += "<br/>" + $(this).attr('title');
			    valid = false;
		    } 
		 })
		 
		 if (!valid) {
		 	swal("Required Fields",req_fields,"warning");
		 }
		 
		 return valid;
	}
	
	$('#cmdCancel').click(function(){
		swal({
		      title: "Are you sure?",
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
		    	  window.location = '<?php echo site_url('item/show') ?>';
		      }
		    });
	});

	
</script>
