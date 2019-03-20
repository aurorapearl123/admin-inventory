 
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
											if ($headers->status == 1) {
												echo "<span style='color:#f7bd2d'>PENDING</span>";
											} else if ($headers->status == 2){
												echo "<span style='color:#4cbad1'>CONFIRMED</span>";
											} else if ($headers->status == 3){
												echo "<span style='color:#007bff'>PARTIALLY DELIVERED</span>";
											} else if ($headers->status == 4){
												echo "<span style='color:#1c9980'>CLOSED</span>";
											} else {
												echo "<span style='color:#de252d'>CANCELLED</span>";
											}
										?>	
											
							</h4> 				
						</div>
					</div>
					<div class="card-head-tools">
						<ul class="tools-list">
							<?php $id = $this->encrypter->encode($headers->$pfield); ?>

							<?php if ($this->session->userdata('current_user')->isAdmin && $headers->status > 2 && $headers->status < 4)  {?>								
							<li>
								<input type="button" onclick="confirm_record(4);" class="btn btn-xs btn-success btn-raised pill" value="Close (Override)"/>
							</li>
							
							<?php } ?>
							<?php if ($roles['confirm'] && $headers->status == 1  ) {?> 	
							<li>								
								<input type="button" onclick="confirm_record(2);" class="btn btn-xs btn-info btn-raised pill" value="Confirm"/>
							</li>
							<?php } ?>
							<?php if ($roles['cancel'] && $headers->status == 1  ) {?> 
							<li>
								<input type="button" onclick="confirm_record(0);" class="btn btn-xs btn-danger btn-raised pill" value="Cancel"/>
							</li>
							<?php } ?>
							<li>
								<button class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="Print" onclick="popUp('<?php echo $controller_page ?>/print_record/<?php echo $id?>', 900, 500)"><i class="la la-print"></i></button>
							</li>	
							<?php if ($roles['edit'] && $headers->status == 1) {?> 	
							<li>
								<a href="<?php echo $controller_page.'/edit/'.$id ?>" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit"><i class="la la-edit"></i></a>
							</li>
							<li>
								<button onclick="deleteRecord('<?php echo $id; ?>');"class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete" ><i class="la la-trash-o"></i></button>
							</li>
							<?php } ?>
							<?php if ($this->session->userdata('current_user')->isAdmin) {?>								
							
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
									<td class="data-title" style="width:120px" nowrap>Supplier:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $headers->suppName; ?></td>
									<td class="data-title" style="width:120px" nowrap>SO No:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $headers->poNo; ?></td>
									<td class="data-title"></td>
								</tr>
								<tr>
									<td class="data-title" style="width:120px" nowrap>Payment Term:</td>
									<td class="data-input" nowrap><?php echo $headers->paymentTerm; ?></td>
									<td class="data-title" style="width:120px" nowrap>Date:</td>
									<td class="data-input" nowrap><?php echo $headers->poDate == '0000-00-00' ? '' :date('M d,Y',strtotime($headers->poDate)); ?></td>
									<td class="data-title"></td>
								</tr>
								<tr>
									<td class="data-title" style="width:120px" nowrap>Delivery Date:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $headers->deliveryDate == '0000-00-00 00:00:00' ? '' :date('M d,Y',strtotime($headers->poDate)); ?></td>
									<td class="data-title" style="width:120px" nowrap>Delivery Term:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $headers->deliveryTerm; ?></td>
									<td class="data-title"></td>
								</tr>
								<tr>
									<td class="data-title" style="width:120px" nowrap>Balance:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $headers->balance; ?></td>
									<td class="data-title" style="width:120px" nowrap>Amount Paid:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $headers->amountPaid; ?></td>
									<td class="data-title"></td>
								</tr>
								<tr>
									<td class="data-title" style="width:120px" nowrap>Due Date:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $headers->dueDate == '0000-00-00 00:00:00' ? '' :date('M d,Y',strtotime($headers->poDate)); ?></td>
									<td class="data-title" style="width:120px" nowrap>Date Paid:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $headers->datePaid == '0000-00-00 00:00:00' ? '' :date('M d,Y',strtotime($headers->datePaid)); ?></td>
									<td class="data-title"></td>
								</tr>
								<?php if ($headers->status > 2 ) {?>
								<tr>
									<td class="data-title" style="width:120px" nowrap>Close Override Reason:</td>
									<td class="data-input" style="width:420px" nowrap colspan="3"><?php echo $headers->closeReason; ?></td>
									<td class="data-title"></td>
									<td class="data-title"></td>									
								</tr>
							<?php } ?>
								
								<!-- Table Rows End -->
							</tbody>
						</table>
					</div>
					<!-- <div class="subtitle mt-20">
                      <h5 class="title mr-auto"><i class="icon left la la-calendar"></i> Purchase Order Details</h5>
                      <div class="subtitle-tools">
                      </div>
                    </div> -->
                    <div class="form-sepator solid mx-0"></div>
                    <div class="datatables_wrapper">
                      <div class="table-responsive-xl">
                        <table id="" class="table hover">
                          <thead class="thead-light">
                            <tr>
								<th class="text-center" align="center">Stock No</th>
								<th class="text-left" align="center">Description</th>
								<th class="text-center" align="center">Qty</th>										
                    			<?php if ($headers->status > 2) {
                    				echo '<th class="text-center" align="center">Delivered Qty</th>';
                    			 } ?>
								<th class="text-center" align="center">Unit</th>
								<th class="text-right" align="right">Unit Cost</th>
								<th class="text-right" align="right">Amount</th>
                            </tr>
                          </thead>
                          <tbody>
                        	<?php 
                        	if (!(empty($headers)) && !(empty($details))) {                        	
                        		foreach($details as $rec) { ?>
                        		<tr class="rowdetails">
	                        		<td align="center"><?php echo $rec->itemCode;?></td>	                        		
	                        		<td align="left"><?php echo $rec->brand != '' ? $rec->name.' ('.$rec->brand.')' : $rec->name; ?></td> 
	                        		<td align="center"><?php echo $rec->qty;?></td>
                        			<?php if ($headers->status > 2) {
                        				if ($rec->qty != $rec->delQty) {
                        					echo '<td align="center" style="color:#007bff; font-weight:bold">'. $rec->delQty .'</td>';
                        				} else {
                        					echo '<td align="center">'. $rec->delQty .'</td>';
                        				}                        				
                        			 } ?>
                        			 <td align="center"><?php echo $rec->umsr;?></td>
	                        		<td align="right"><?php echo number_format($rec->uprice,2);?></td>
	                        		<td align="right" class="tdamount"><?php echo number_format($rec->amount,2);?></td>
                        		</tr>
                        		<?php 
                        			$totalamount += $rec->amount;
                        		} //end of foreach ?>
								<tr class="bg-light">
									
                        			<?php if ($headers->status > 2) {
                        				echo '<td colspan="6" class="text-right font-weight-bold">TOTAL:</td>';
                        			 } else {
                        			 	echo '<td colspan="5" class="text-right font-weight-bold">TOTAL:</td>';
                        			 } ?>
									<td align="right" colspan="" class="font-weight-bold">&#8369; <?php echo number_format($totalamount,2);?></td>
								</tr>								
								<tr class="">									
									<td colspan="7" class="font-weight-bold">&nbsp;  &nbsp;</td>
								</tr>

                        	<?php } else { ?>
                        		<tr>
	                        		<td colspan="7" class="text-center">No records found.</td>
	                        	</tr>                        		
                    		<?php } ?>      
                           <!-- end foreach -->
                           <!-- end if -->
                          </tbody>
                        </table>

                      </div>
                    </div>
                    <div class="form-sepator solid mx-0"></div>
                    <div class="data-view">
						<table class="view-table">
							<tbody>
							<!-- Table Rows Start -->
								<tr>
									<td class="data-title" style="width:120px" nowrap>Discountable:</td>
									<td class="data-input" style="width:420px" nowrap>
									</td>
									<td class="data-title" style="width:120px" nowrap>Discount:</td>
									<td class="data-input" style="width:420px" nowrap></td>
									<td class="data-title"></td>
								</tr>
								<tr>
									<td class="data-title" style="width:120px" nowrap>Total Gross:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo number_format($headers->grossAmount, 2); ?></td>
									<td class="data-title" style="width:120px" nowrap>Net Amount:</td>
									<td class="data-input" style="width:420px" nowrap>&#8369; <?php echo number_format($headers->netAmount,2);?></td>
									<td class="data-title"></td>
								</tr>
								<!-- Table Rows End -->
							</tbody>
						</table>
                    </div>
                    <div class="form-sepator solid mx-0"></div>
                    <div class="data-view">
						<table class="view-table">
							<tbody>
							<!-- Table Rows Start -->
								<?php if ($headers->createdBy != '') { ?>
								<tr>
									<td class="data-title" style="width:120px" nowrap>Created By:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $headers->createdByFirst.' '.$headers->createdByMiddle.' '.$headers->createdByLast; ?></td>
									<td class="data-title" style="width:120px" nowrap>Date Created:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo date('M d,Y g:i A',strtotime($headers->dateInserted)); ?></td>
									<td class="data-title"></td>
								</tr>
								<?php } ?>
								<?php if ($headers->confirmedBy != '') { ?>
								<tr>
									<td class="data-title" style="width:120px" nowrap>Confirmed By:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $headers->confirmedByFirst.' '.$headers->confirmedByMiddle.' '.$headers->confirmedByLast; ?></td>
									<td class="data-title" style="width:120px" nowrap>Date Confirmed:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo date('M d,Y H:i A',strtotime($headers->dateConfirmed)); ?></td>
									<td class="data-title"></td>
								</tr>	
								<?php } ?>									
								<?php if ($headers->closedBy != '') { ?>
								<tr>
									<td class="data-title" style="width:120px" nowrap>Closed By:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $headers->closedByFirst.' '.$headers->closedByMiddle.' '.$headers->closedByLast; ?></td>
									<td class="data-title" style="width:120px" nowrap>Date Closed:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo date('M d,Y g:i A',strtotime($headers->dateClosed)); ?></td>
									<td class="data-title"></td>
								</tr>	
								<?php } ?>	
								<?php if ($headers->cancelledBy != '' ) { ?>						
								<tr>
									<td class="data-title" style="width:120px" nowrap>Cancelled By:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $headers->cancelledByFirst.' '.$headers->cancelledByMiddle.' '.$headers->cancelledByLast; ?></td>
									<td class="data-title" style="width:120px" nowrap>Date Cancelled:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo date('M d,Y g:i A',strtotime($headers->dateCancelled)); ?></td>
									<td class="data-title"></td>
								</tr>
								<?php } ?>
								<!-- Table Rows End -->
							</tbody>
						</table>
                    </div>
 				</div><!-- Card Body End -->

			</div>
		</div>
	</div>
</div><!-- Content End -->

<script>
	
	tablerow = $('.rowdetails');
	console.log('tablerow',tablerow);
	
	tdamount = $('.tdamount');
	console.log('tdamount',tdamount);

	function confirm_record(status='') {
		console.log('confirm_record',status);
		var title = '';
		if (status == 2) {
			title = 'change status to confirm';
		} else if (status == 0) {
			title = 'cancel this form';
		} else if( status == 4) { //close override
			title = 'close override this form'
		}
		
		var soID = "<?php echo $headers->soID; ?>";

		if (status == 4) { //close override	
		                   					
			swal({
				input: 'text',
				inputPlaceholder: 'Enter here your reason.'	,			
				title: 'You are about to '+title+'.',
				text: "Do you still want to continue?",
				icon: "warning",
				content: "input",
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Submit',
				cancelButtonText: 'No'
			})
			.then((closeReason) => {
				if (closeReason.value) {
					console.log('closeReason',closeReason);
						$.post("<?php echo site_url()?>purchase_order/close_record", { status: status, soID: soID, closeReason: closeReason.value},
							function(response){ 							
								window.location='';
							}, "json");
				}
			});
		} else {

			swal({
				title: 'You are about to '+title+'.',
				text: "Do you still want to continue?",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes',
				cancelButtonText: 'No'
			})
			.then((willDelete) => {
				if (willDelete.value) {
						$.post("<?php echo site_url()?>purchase_order/confirm_record", { status: status, soID: soID},
							function(response){ 							
								window.location='';
							}, "json");
				}
			});

		}

	}

	function deleteRecord(id){
		console.log('deleteRecord',id);
	}




</script>