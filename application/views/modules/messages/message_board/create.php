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
							<h4 class="head-text">New <?php echo $current_module['module_label'] ?></h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo site_url('message_board/save'); ?>" >
						<div class="table-form column-3">
							<table class="table-form">
								<tbody>
									<tr>
										<td class="form-label" style="width: 10%;">Expiration: </td>
										<td class="form-group form-input" style="width: 20%;"><input type="text" class="form-control datepicker" name="expiration" id="expiration" data-toggle="datetimepicker" data-target="#expiration" value="<?php echo date('M d Y')?>" label="Date Filed"></td>
										<td colspan="3" style="width: 60%;"></td>
									</tr>
									<tr>
										<td class="form-label" style="width: 10%;">Message Type: </td>
										<td class="form-group form-input" style="width: 20%;">
											<select class="form-control" name="type" id="type" style="width:100px">
												<option value="1">Information</option>
												<option value="2">Notification</option>
												<option value="3">Warning</option>
											</select>
										</td>
										<td colspan="3" style="width: 60%;">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="5" class="form-label" width="12%" valign="top" nowrap>
											Message<span class="asterisk">*</span>
										</td>
									</tr>
									<tr>
										
										<td class="form-group form-input" width=""  colspan="5" rowspan="1">
											<textarea class="form-control" name="message" id="message" rows="5" maxlength="50" style="width:300px; height: 200px;"></textarea>
										</td>
										
									</tr>
								</tbody>
							</table>
						</div>
						
					</form>

					
					<!-- End -->



						<div class="form-sepator solid"></div>
						<div class="form-group mb-0">
							<button class="btn btn-xs btn-primary btn-raised pill" type="button" name="cmdSave" id="cmdSave">
								Save
							</button>
							<input type="button" id="cmdCancel" class="btn btn-xs btn-outline-danger btn-raised pill" value="Cancel"/>
						</div>

				</div>
			</div>
		</div>
	</div>
</div>

























<script type="text/javascript">
	CKEDITOR.replace('message');
</script>




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
				window.location = '<?php echo site_url($controller_page.'/show') ?>';
			}
		});

	});

</script>