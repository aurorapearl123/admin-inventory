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
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo site_url('deposit/update') ?>">
						<input type="hidden" name="depositID" id="depositID" value="<?php echo $this->encrypter->encode($rec->depositID) ?>" />
						<div class="table-row">
							<table class="table-form column-3">
								<tbody>
									<tr>
										<td class="form-label" style="width:120px" nowrap>Country <span class="asterisk">*</span></label></td>
										<td class="form-group form-input" style="width:400px" nowrap>
											<select class="form-control" id="bankAccountID" name="bankAccountID" data-live-search="true" liveSearchNormalize="true" title="Countries" required>
												<option value="">&nbsp;</option>
												<?php 
												$recs = $this->db->get('bank_accounts')->result();
												foreach($recs as $row){
												?>
												<option value="<?php echo $row->bankAccountID?>" <?php if ($row->bankAccountID == $rec->bankAccountID) { echo "selected"; } ?>><?php echo $row->accountName?></option>
												<?php } ?>
											</select>
										</td>
										<td class="d-xxl-none"></td>
									</tr>
									<tr>
										<td class="form-label" nowrap>Amount <span class="asterisk">*</span></td>
										<td class="form-group form-input" nowrap><input type="text" class="form-control" name="amount" id="amount" value="<?php echo $rec->amount ?>" title="Amount" required></td>
										<td class="d-xxl-none"></td>
									</tr>
									<tr>
										<td class="form-label">Remarks </td>
										<td class="form-group form-input">
											<textarea type="text" class="form-control" name="remarks" id="remarks"><?php echo $rec->remarks ?></textarea>
										</td>
										<td class="d-xxl-none"></td>
									</tr>
									<tr>
										<td class="form-label" nowrap>Status <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<select id="status" name="status" class="form-control" title="Status" required>
												<option value="" selected>&nbsp;</option>
												<option value="1" <?php if ($rec->status==1) { echo "selected"; } ?>>Active</option>
												<option value="0" <?php if ($rec->status==0) { echo "selected"; } ?>>Inactive</option>
											</select>
										</td>
										<td class="d-xxl-none"></td>
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
	    	  window.location = '<?php echo site_url('deposit/view/'.$this->encrypter->encode($rec->depositID)) ?>';
	      }
	    });
	});
</script>
