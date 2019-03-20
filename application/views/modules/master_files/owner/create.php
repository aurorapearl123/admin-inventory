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
							<h4 class="head-text"> Add New <?php echo $current_module['module_label'] ?></h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo $controller_page.'/save'; ?>" >
						<div class="subtitle">
							<h5 class="title"><i class="icon left la la-user-plus"></i> Owner Profile</h5>
						</div>
						<div class="table-row">
							<table class="table-form column-3">
								<tbody>
									<tr>
										<td class="form-label" width="12%">Last Name <span class="asterisk">*</span> 
										</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" name="lastName" id="lastName" title="Last Name" >
										</td>
										<td class="form-label" width="12%">First Name <span class="asterisk">*</span> </td>
										<td class="form-group form-input">
											<input type="text" class="form-control" name="firstName" id="firstName" title="First Name" >
										</td>
										<td class="form-label" width="12%">Middle Name </td>
										<td class="form-group form-input">
											<input type="text" class="form-control" name="middleName" id="middleName" title="Middle Name">
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
							<table class="table-form">
								<tbody>
									<tr>
										<td class="form-label" width="12%">Username<span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control" name="userName" id="userName" title="Username" required>
										</td>
										<td class="form-label">Password<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="password" class="form-control" name="userPswd" id="userPswd" title="Password" required>
										</td>
										<td class="form-label">Re-Password <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="password" class="form-control" name="rePswd" id="rePswd" title="Re-Password" required>
										</td>
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

		if ($('#userPswd').val() == $('#rePswd').val()) {				
			if (check_fields()) {
				$('#cmdSave').attr('disabled','disabled');
				$('#cmdSave').addClass('loader');
				$('#frmEntry').submit();
			}
		} else {
			swal("Password does not match.","Please try again.","warning");
			$('#userPswd').focus();
			$('#userPswd,#rePswd').val('');
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
				window.location = '<?php echo $controller_page.'/show'; ?>';
			}
		});

	});

</script>