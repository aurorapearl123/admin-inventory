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
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo site_url("bank_accounts/save") ?>">
						<div class="table-row">
							<table class="table-form column-3">
								<tbody>
									<tr>
										<td class="form-label" width="120px" nowrap>Bank Name <span class="asterisk">*</span> </td>	
										<td class="form-group form-input" width="400px" nowrap>
											<select id="bankID" name="bankID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Bank Name" >
		                                  		<option value="">&nbsp;</option>
					                                <?php 
					                                    $this->db->where('status !=','-100');
														$banks = $this->db->get('banks')->result();			
					                                  	foreach($banks as $bank){             		
					                                  ?>
			                                  			<option value="<?php echo $bank->bankID?>" ><?php echo $bank->bankName ?></option>
			                                 	 <?php } ?>
			                                </select>
		                               </td>	
										<td class="form-label" width="120px" nowrap>Owner Name <span class="asterisk">*</span> </td>	
										<td class="form-group form-input" width="400px" nowrap>
											<select id="ownerID" name="ownerID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Owner Name" >
		                                  		<option value="">&nbsp;</option>
					                                <?php 
					                                    $this->db->where('status !=','-100');
														$owners = $this->db->get('owners')->result();			
					                                  	foreach($owners as $owner){             		
					                                  ?>
			                                  			<option value="<?php echo $owner->ownerID?>" ><?php echo $owner->lastName.', '.$owner->firstName. ' '.$owner->middleName ?></option>
			                                 	 <?php } ?>
			                                </select>
		                               </td>																			
									</tr>
									<tr>
										<td class="form-label" width="120px" nowrap>Account Name <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="400px" nowrap>
											<input type="text" class="form-control" name="accountName" id="accountName" title="Accout Name" required>
										</td>	
										<td class="form-label" width="120px" nowrap>Account No <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="400px" nowrap>
											<input type="text" class="form-control" name="accountNo" id="accountNo" title="Account No" required>
											
										</td>									
										
										<td class="form-label" ></td>
									</tr>
									<tr>
										<td class="form-label" width="120px" nowrap>Account Type <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="400px" nowrap>
											<select id="accountType" name="accountType" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Account Type" required>
		                                  		<option value="1" >Checking Account (PHP)</option>
	                                  			<option value="2" >Savings Account (PHP)</option>			                                 	
	                                  			<option value="3" >Cash Card (PHP)</option>			                                 	
	                                  			<option value="4" >Checking Account (USD)</option>			                                 	
			                                </select>
										</td>	
										<td class="form-label" width="120px" nowrap>Bank Account Type <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="400px" nowrap>
											
											<select id="bankAccountType" name="bankAccountType" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Bank Account Type" >
		                                  		<option value="Income" >Income</option>
	                                  			<option value="Expense" >Expense</option>			                                 	
			                                </select>
										</td>									
										
										<td class="form-label" ></td>
									</tr>
									<tr>
										<td class="form-label" width="120px" nowrap>Email </td>
										<td class="form-group form-input" width="400px" nowrap>
											<input type="email" class="form-control" name="email" id="email" title="Email" >
										</td>	
										<td class="form-label" ></td>
										<td class="form-label" ></td>
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
	        $.post("<?php echo $controller_page ?>/check_duplicate", { accountName: $('#accountName').val(), accountNo: $('#accountNo').val()  },
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
		    	  window.location = '<?php echo site_url('bank_accounts/show') ?>';
		      }
		    });
	    
	});



</script>
