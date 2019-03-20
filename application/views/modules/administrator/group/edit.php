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
							<h4 class="head-text">Edit <?php echo $current_module['module_label'] ?></h4>
						</div> 
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo site_url('group/update') ?>">
						<input type="hidden" name="groupID" id="groupID" value="<?php echo $this->encrypter->encode($rec->groupID) ?>" />
						<div class="table-row">
							<table class="table-form">
								<tbody>
									<tr>
										<td class="form-label" width="150">
											Group<span class="asterisk">*</span>
										</td>
										<td class="form-group" width="400">
											<input type="text" class="form-control" name="groupName" id="groupName" value="<?php echo $rec->groupName ?>" required>
										</td>
										<td class="d-xxl2-none"></td>
									</tr>
									<tr>
										<td class="form-label" valign="top">
											Description
										</td>
										<td class="form-group form-input">
											<textarea class="form-control" name="groupDesc" id="groupDesc" required><?php echo $rec->groupDesc ?></textarea>
										</td>
										<td class="d-xxl2-none"></td>
									</tr>
									<tr>
										<td class="form-label align-text-top pt-5">
											Status<span class="asterisk">*</span>
										</td>
										<td class="form-group form-input">
											<select name="rstatus" class="form-control" required>
												<option value="1" <?php if ($rec->rstatus==1) echo "selected"; ?> >Active</option>
												<option value="0" <?php if ($rec->rstatus==0) echo "selected"; ?> >Inactive</option>
											</select>
										</td>
										<td class="d-xxl2-none"></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="form-sepator solid"></div>
						<div class="form-group mb-0">
							<button type="button" class="btn btn-xs btn-primary btn-raised pill" name="cmdSave" id="cmdSave">Save</button>
							<button type="button" class="btn btn-xs btn-outline-danger btn-raised pill" name="cmdCancel" id="cmdCancel" >Cancel</button>
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
	       	$('#frmEntry').submit();
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
		    	  window.location = '<?php echo site_url('group/show') ?>';
		      }
		    });
	    
	});
</script>