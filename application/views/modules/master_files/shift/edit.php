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
<div class="content">
	
				

<form method="post" name="frmEntry" id="frmEntry" action="<?php echo $controller_page ?>/update" enctype="multipart/form-data">
<input type="hidden" name="shiftID" id="shiftID" value="<?php echo $this->encrypter->encode($records->shiftID) ?>" />
	<div class="content">
		<div class="row">
			<div class="col">
				<div class="card-box" style="height:480px">
					<div class="card-head">
						<div class="head-caption">
							<div class="head-title">
								<h4 class="head-text"><i class="icon left la <?php echo $current_module['icon'] ?>"></i>Edit <?php echo strtoupper($current_module['title']) ?></h4>
							</div>
						</div>
						<div class="card-head-tools"></div>
					</div>
					<div class="card-body">
						<div class="table-row">
							<table class="table-form column-3" border="0">
								<tbody>
									
									<tr>

										<td class="form-label" width="120px" nowrap>Start Time: <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="400px">
										
											<input type="time" id="startTime" class="form-control" name="startTime" min="9:00"  required>
											

										</td>
										<td class="form-label" width="120px" nowrap>End Time: <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="400px">
											<input type="time" id="endTime" class="form-control" name="endTime"  required>
											

										</td>
										
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td class="form-label" nowrap>Status <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<select id="status" name="status" class="form-control" title="Status" required>
												<option value="" selected>&nbsp;</option>
												<option value="1" <?php if ($records->status==1) { echo "selected"; } ?>>Active</option>
												<option value="0" <?php if ($records->status==0) { echo "selected"; } ?>>Inactive</option>
											</select>
										</td>
										<td class="d-xxl-none"></td>
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
</div>


<script>
	$(document).ready(function(){
	

	var startTime = '<?php echo date('G:i', strtotime($records->startTime))?>';
	var endTime = '<?php echo date('G:i', strtotime($records->endTime))?>';

	console.log(startTime);
	console.log(endTime);
	
	var length = startTime.length;
	if(length == 4) {
		startTime = '0'+startTime;
	}

	var outTimeLength = endTime.length;
	if(outTimeLength == 4) {
		endTime = '0'+endTime;
	}
	

	document.getElementById('startTime').defaultValue = startTime;
	document.getElementById('endTime').defaultValue = endTime;

	


	

});


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
				window.location = '<?php echo $controller_page.'/show' ?>';
			}
		});

	});

</script>