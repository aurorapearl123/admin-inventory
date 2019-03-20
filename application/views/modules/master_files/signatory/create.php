<div class="subheader">
	<div class="d-flex align-items-center">
		<div class="title mr-auto">
			<h3><i class="icon left la <?php echo $current_module['icon'] ?>"></i> <?php echo $current_module['title'] ?></h3>
		</div>
		<div class="subheader-tools"></div>
	</div>
</div>
<div class="content">
	<div class="row">
		<div class="col-12">
			<div class="card-box">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="head-text">Add <?php echo $current_module['module_label'] ?></h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo site_url("signatory/save") ?>">
						<div class="table-row">
							<table class="table-form column-3">
								<tbody>
									<tr>
										<td class="form-label" width="120px" nowrap>Code <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="400px" nowrap>
											<input type="text" class="form-control" name="code" id="code" title="Code" maxlength="10" required>
										</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td class="form-label" nowrap>Name <span class="asterisk">*</span></td>
										<td class="form-group form-input" nowrap>
											<input type="text" class="form-control" name="fullname" id="fullname" title="Name" required>
										</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td class="form-label" width="120px" nowrap>Designation 1 <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="400px" nowrap>
											<input type="text" class="form-control" name="designation1" id="designation1" title="Designation" required>
										</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td class="form-label" width="120px" nowrap>Designation 2 </td>
										<td class="form-group form-input" width="400px" nowrap>
											<input type="text" class="form-control" name="designation2" id="designation2">
										</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td class="form-label" width="120px" nowrap>Designation 3 </td>
										<td class="form-group form-input" width="400px" nowrap>
											<input type="text" class="form-control" name="designation3" id="designation3">
										</td>
										<td>&nbsp;</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="form-sepator solid"></div>
						<div class="form-group mb-0">
							<button class="btn btn-primary btn-raised pill" type="button" name="cmdSave" id="cmdSave">
							Save
							</button>
							<input type="button" id="cmdCancel" class="btn btn-outline-danger btn-raised pill" value="Cancel"/>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#cmdSave').click(function(){
		if (check_fields()) {
	    	$('#cmdSave').attr('disabled','disabled');
	    	$('#cmdSave').addClass('loader');
	        $.post("<?php echo $controller_page ?>/check_duplicate", { code: $('#code').val(),fullname: $('#fullname').val() },
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
		    	  window.location = '<?php echo site_url('signatory/show') ?>';
		      }
		    });
	    
	});

</script>
