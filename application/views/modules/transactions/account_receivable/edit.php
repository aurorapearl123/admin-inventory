<div class="subheader">
	<div class="d-flex align-products-center">
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
							<h4 class="head-text"> Edit <?php echo $current_module['module_label'] ?></h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo $controller_page ?>/update" enctype="multipart/form-data">
						<input type="hidden" id="<?php echo $pfield ?>" name="<?php echo $pfield ?>" value="<?php echo $this->encrypter->encode($rec->$pfield); ?>"/>
						<input type="hidden" name="grossAmount" id="grossAmount" value="">

						<div class="table-row">
							<table class="table-form column-3">
								<tbody class="">
									<tr>
										<td class="form-label" width="12%">Customer:<span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%" nowrap>

											<select class="form-control " id="custID" name="custID" data-live-search="true" livesearchnormalize="true" title="Customer"  required>
												<option value="" selected>&nbsp;</option>
												<?php foreach($customers as $row) { ?>
													<option value="<?php echo $row->custID ?>" <?php if ($custID == $res->custID) { echo 'selected'; } ?>><?php echo $row->companyName ?></option>
												<?php } ?>
											</select>
										</td>



										<td class="form-label" width="12%">Invoice No: <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control" id="invoiceNo" name="invoiceNo" value="<?php echo $rec->invoiceNo ?>" title="P.O No" required>
										</td>
										<td class="form-label" width="12%"></td>
										<td class="form-group form-input" width="21.33%">
											
										</td>
									</tr>
									<tr>
										<td class="form-label">Payment Terms:<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<select id="paymentTerm" name="paymentTerm" class="form-control">
												<option value="">&nbsp;</option>
												<option value="1" <?php echo ($rec->paymentTerm == 1)? 'selected': ''; ?>>Credit Card</option>
												<option value="2" <?php echo ($rec->paymentTerm == 2)? 'selected': ''; ?>>EOM - End of month</option>
												<option value="3" <?php echo ($rec->paymentTerm == 3)? 'selected': ''; ?>>COD - Cash on delivery</option>
												<option value="4" <?php echo ($rec->paymentTerm == 4)? 'selected': ''; ?>>Bank Deposit</option>
											</select>
										</td>
										<td class="form-label">Date: <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control datepicker" id="invoiceDate" name="invoiceDate" data-toggle="datetimepicker" value="<?php echo ($rec->invoiceDate)? date('M d, Y', strtotime($rec->invoiceDate)) : ""; ?>" data-target="#invoiceDate" title="Date" required>
										</td>
										<td class="form-label"></td>
										<td class="form-group form-input">
											
										</td>
									</tr>

									<!-- <tr>
										<td class="form-label">Delivery Date: <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control datepicker" id="deliveryDate" name="deliveryDate" data-toggle="datetimepicker" value="<?php echo ($rec->deliveryDate != '0000-00-00 00:00:00')? date('M d, Y', strtotime($rec->deliveryDate)) : ""; ?>" data-target="#deliveryDate" title="Delivery Date" required>
										</td>
										<td class="form-label">Delivery Term</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="deliveryTerm" name="deliveryTerm" title="Balance" value="<?php echo $rec->deliveryTerm ?>">
										</td>
										<td class="form-label"></td>
										<td class="form-group form-input">
											
										</td>
									</tr> -->

									<tr>
										<td class="form-label">Balance: </td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="balance" name="balance" title="Balance" value="<?php echo $rec->balance; ?>">
										</td>
										<td class="form-label">Due Date: </td>
										<td class="form-group form-input">
											<input type="text" class="form-control datepicker" id="dueDate" name="dueDate" data-toggle="datetimepicker" value="<?php echo ($rec->dueDate != '0000-00-00 00:00:00')? date('M d, Y', strtotime($rec->dueDate)) : ""; ?>" data-target="#dueDate" title="Due Date">
											
										</td>
										<td class="form-label"></td>
										<td class="form-group form-input">
											
										</td>
									</tr>
									<tr>
										<td class="form-label">Amount Paid: </td>
										<td class="form-group form-input">
											
											<input type="text" class="form-control" id="amountPaid" name="amountPaid" title="Amount Paid" value="<?php echo $rec->amountPaid; ?>">
										</td>
										<td class="form-label">Date Paid: </td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="datePaid" name="datePaid" title="Date Paid" value="<?php echo ($rec->datePaid != '0000-00-00 00:00:00')? date('M d, Y', strtotime($rec->datePaid)) : ""; ?>">
										</td>
										<td class="form-label"></td>
										<td class="form-group form-input">
											
										</td>
									</tr>
									
								</tbody>

							</table>
						</div>
					</form>














					<hr>
					<div class="sepator mb-20"></div>




					







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


	<!-- E insert ang same IAR No sa iarheaders table, together sa iya new arID, para multiple ang arID sa usa ka iarNo. -->
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
					window.location = '<?php echo $controller_page.'/show'; ?>';
				}
			});

		});



</script>