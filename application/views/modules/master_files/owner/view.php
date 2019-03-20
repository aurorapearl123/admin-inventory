
<!-- Sub Header End -->
<div class="subheader">
	<div class="d-flex align-items-center">
		<div class="title mr-auto">
			<h3><i class="icon left la <?php echo $current_module['icon'] ?>"></i> <?php echo $current_module['title'] ?></h3>
		</div>
		<div class="subheader-tools">
			<a href="<?php echo $controller_page.'/show' ?>" class="btn btn-primary btn-raised btn-xs pill"><i class="icon ti-angle-left"></i> Back to List</a>
		</div>
	</div>
</div>
<!-- Sub Header Start -->

<!-- Content Start -->
<div class="content">
	<div class="row">
		<div class="col-12">
		<!-- Card Box Start -->
			<div class="card-box">
			<!-- Card Header Start -->
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="head-text">View <?php echo $current_module['module_label'] ?>
								| 						
									<?php 
										if ($rec->status == 1) {
											echo "<font color='green'>Active</font>";
										} else {
											echo "<font color='red'>Inactive</font>";
										}
									?>
							</h4>
						</div>
					</div>
					<div class="card-head-tools">
						<ul class="tools-list">
							<?php $id = $this->encrypter->encode($rec->$pfield); ?>
							<li>
								<a class="btn btn-outline-light bmd-btn-icon" id="changepass" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Change Owner Password" onclick="changeOwnerPassBtn();"><i class="icon la la-unlock"></i></a>
							</li>
							<?php if ($roles['edit']) {?>
							<li>
								<a href="<?php echo $controller_page.'/edit/'.$id ?>" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit"><i class="la la-edit"></i></a>
							</li>
							<?php }  if ($roles['delete']) {?>
							
							<li>
								<button name="cmddelete" id="cmddelete" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete" onclick="deleteRecord('<?php echo $id; ?>');"><i class="la la-trash-o"></i></button>
							</li>
							
							<?php } if ($this->session->userdata('current_user')->isAdmin) {?>
							<li>
								<button type="button" id="recordlog" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Record Logs" onclick="popUp('<?php echo site_url('logs/record_log/'.$table_name.'/'.$pfield.'/'.$id.'/'.ucfirst(str_replace('_', '&', $controller_name))) ?>', 1000, 500)"><i class="la la-server"></i></button>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			<!-- Card Header End -->
				
				<!-- Card Body Start -->
				<div class="card-body">
					<div class="data-view">
						<table class="view-table">
							<tbody>
							
							<!-- Table Rows Start -->

								<tr>
									<td class="data-title" style="width:120px" nowrap>Owner Name:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $rec->lastName.', '.$rec->firstName.' '.$rec->middleName; ?></td>
									<td class="data-title"></td>
									<td class="data-title"></td>
								</tr>		

								<tr>
									<td class="data-title" style="width:120px" nowrap>Username:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $rec->userName; ?> </td>
									<td class="data-title"></td>
									<td class="data-title"></td>
								</tr>

								<tr>
									<td class="data-title" style="width:120px" nowrap>PIN:</td>
									<td class="" style="width:100px" nowrap>****
										
										<?php if ($this->session->userdata('current_user')->isAdmin) { ?>
											<button class="ml-10 input-pin btn btn-primary btn-raised bmd-btn-fab bmd-btn-fab-sm" data-toggle="modal" data-target="#modal-viewpin" data-value="" title="View PIN" onclick="viewPin();"> <i class="icon center la la-eye"></i></button>
										<?php } ?>
									</td>
									
									<td class="data-title"></td>
									<td class="data-title"></td>
								</tr>								
																
								<!-- Table Rows End -->
							</tbody>
						</table>
					</div>
				</div><!-- Card Body End -->
			</div>
		</div>
	</div>
</div><!-- Content End -->

<script type="text/javascript">

	function viewPin()
	{
		swal("<?php echo $rec->pin; ?>");		   
	}
	
	function changeOwnerPassBtn()
	{
		swal({
		  	title: '<div class="modal-header" style="width: 401px;padding: 20px;margin: -32px 20px 5px -32px;"><h4 class="modal-title">Change User Password</h4></div>',
		  	html:'<div class="modal-body">'+
							'<div class="table-row">'+
								'<table class="table-form">'+
									'<tbody>'+
									'	<tr>'+
											'<td class="form-label">'+
												'<label for="employee" style="white-space:nowrap">New Password • <span class="asterisk">*</span></label>'+
											'</td>'+
											'<td class="form-group form-input bmd-form-group">'+
												'<input type="password" class="form-control" id="swal-input1" class="swal2-input" required=>'+
												'<input type="hidden" class="form-control" id="ownerID" value="<?php echo $rec->ownerID ?>">'+
											'</td>'+
										'</tr>'+
										'<tr>'+
											'<td class="form-label">'+
												'<label for="fmname">Re-Password • <span class="asterisk">*</span></label>'+
											'</td>'+
											'<td class="form-group form-input bmd-form-group">'+
												'<input type="password" class="form-control" id="swal-input2" class="swal2-input"  required>'+
											'</td>'+
										'</tr>'+
									'</tbody>'+
								'</table>'+
							'</div>'+
						'</div>',	
			cancelButtonColor: '#d33',
			showCancelButton: true,		 
		  preConfirm: function () {
		    return new Promise(function (resolve) {
		      resolve([
		        $('#swal-input1').val(),
		        $('#swal-input2').val()
		      ])
		    })
		  },
		  onOpen: function () {
		    $('#swal-input1').focus()
		  }
		}).then(function (result) {			
			if(result.value) {			
				if (result.value[0] == result.value[1]) {							
					$.post("<?php echo $controller_page.'/change_password'; ?>", {  ownerID: $('#ownerID').val(), userPswd: result.value[1]},					
						function(response){ 
							if (response ) {
								swal("Password changed.",'',"success");												
							} 
					}, "json");
				} else {					
					swal("Password does not match.","Please try again.","warning");											
				}
			}					  
		}).catch(swal.noop);
	}
</script>