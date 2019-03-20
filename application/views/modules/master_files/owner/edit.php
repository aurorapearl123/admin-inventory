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
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo $controller_page.'/update'; ?>" >
						<input type="hidden" id="<?php echo $pfield ?>" name="<?php echo $pfield ?>" value="<?php echo $this->encrypter->encode($rec->$pfield); ?>"/>
						<div class="subtitle">
							<h5 class="title"><i class="icon left la la-user"></i> Owner Profile</h5>
						</div>
						<div class="table-row">
							<table class="table-form column-3">
								<tbody>
									<tr>
										<td class="form-label" width="12%">Last Name 
										</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" name="lastName" id="lastName" title="Last Name" value="<?php echo $rec->lastName ?>" >
										</td>
										<td class="form-label" width="12%">First Name </td>
										<td class="form-group form-input">
											<input type="text" class="form-control" name="firstName" id="firstName" title="First Name" value="<?php echo $rec->firstName ?>" >
										</td>
										<td class="form-label" width="12%">Middle Name</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" name="middleName" id="middleName" value="<?php echo $rec->middleName ?>">
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="form-sepator solid"></div>
						<div class="subtitle">
							<h5 class="title"><i class="icon left la la-user-plus"></i> Owner Account</h5>
						</div>
						<div class="table-row">
							<table class="table-form column-3">
								<tbody>
									<tr>
										<td class="form-label" width="12%">Username</td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control" name="userName" id="userName" title="Username" value="<?php echo $rec->userName ?>" >
										</td>									
										<td class="form-label" width="12%">PIN</td>
										<td class="form-group form-input" width="21.33%">
											<?php if ($this->session->userdata('current_user')->isAdmin) { ?>
												<button class="btn btn-xs btn-primary btn-raised pill" type="button" name="resetPinBtn" id="resetPinBtn" onclick="resetPin();">
													Reset PIN
													<div class="ripple-container"></div>
												</button>
											<?php } ?>
										</td>
									
											<td class="form-label"></td>
									</tr>
									<tr>
										<td class="form-label" width="12%">Status</td>
										<td class="form-group form-input" width="21.33%">
											<select class="form-control" id="status" name="status" >
												<option value="">&nbsp;</option>
												<option value="1" <?php echo ($rec->status == "1")? 'selected':'';  ?>>Active</option>
												<option value="0" <?php echo ($rec->status == "0")? 'selected':'';  ?>>Inactive</option>
											</select>
										</td>
										<td class="form-label"></td>
										<td class="form-label"></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="form-sepator solid"></div>
						<div class="form-group mb-0">
							<button class="btn btn-xs btn-primary btn-raised pill" type="button" name="cmdSave" id="cmdSave">
								Save
							</button>
							<input type="button" id="cmdCancel" class="btn btn-xs btn-outline-danger btn-raised pill" value="Cancel"/>
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
	

	function resetPin() {
		$.ajax({
			url: '<?php echo site_url('owner/reset_pin') ?>',
			method: 'POST',
			data: { ownerID: '<?php echo $rec->ownerID ?>' },
			dataType: 'json',
			success: function(response) {
				swal('Reset PIN successful.');
			},
			error: function(xhr) {
				console.log(xhr);
			}
		});
	}
	
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
				window.location = '<?php echo $controller_page.'/show'; ?>';
			}
		});

	});

</script>