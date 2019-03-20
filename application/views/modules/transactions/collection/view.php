 
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
												echo "<span style='color:#f7bd2d'>PENDING</span>";
											} else if ($rec->status == 2){
												echo "<span style='color:#4cbad1'>CONFIRMED</span>";
											} else if ($rec->status == 3){
												echo "<span style='color:#007bff'>PARTIALLY DELIVERED</span>";
											} else if ($rec->status == 4){
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
							<?php $id = $this->encrypter->encode($rec->$pfield); ?>

							<?php if ($this->session->userdata('current_user')->isAdmin && $rec->status > 2 && $rec->status < 4)  {?>								
							<li>
								<input type="button" onclick="confirm_record(4);" class="btn btn-xs btn-success btn-raised pill" value="Close (Override)"/>
							</li>
							
							<?php } ?>
							<?php if ($roles['confirm'] && $rec->status == 1  ) {?> 	
							<li>								
								<input type="button" onclick="confirm_record(2);" class="btn btn-xs btn-info btn-raised pill" value="Confirm"/>
							</li>
							<?php } ?>
							<?php if ($roles['cancel'] && $rec->status == 1  ) {?> 
							<li>
								<input type="button" onclick="confirm_record(0);" class="btn btn-xs btn-danger btn-raised pill" value="Cancel"/>
							</li>
							<?php } ?>
							<li>
								<button class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="Print" onclick="popUp('<?php echo $controller_page ?>/print_record/<?php echo $id?>', 900, 500)"><i class="la la-print"></i></button>
							</li>	
							<?php if ($roles['edit'] && $rec->status == 1) {?> 	
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
								<td class="data-title" style="width:120px" nowrap>Collection No:</td>
								<td class="data-input" style="width:420px" nowrap><?php echo $rec->collectionNo; ?></td>
								<td class="data-title" style="width:120px" nowrap>Collection Date:</td>
								<td class="data-input" style="width:420px" nowrap><?php echo $rec->collectionDate == '0000-00-00' ? '' :date('M d,Y',strtotime($rec->collectionDate)); ?></td>
								<td class="data-title"></td>
							</tr>
							<tr>
								<td class="data-title" style="width:120px" nowrap>OR No:</td>
								<td class="data-input" nowrap><?php echo $rec->orNo; ?></td>
								<td class="data-title" style="width:120px" nowrap>OR Date:</td>
								<td class="data-input" nowrap><?php echo $rec->orDate == '0000-00-00' ? '' :date('M d,Y',strtotime($rec->orDate)); ?></td>
								<td class="data-title"></td>
							</tr>
								<tr>
									<td class="data-title" style="width:120px" nowrap>Customer:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $rec->companyName; ?></td>
									<td class="data-title" style="width:120px" nowrap>Invoice NO:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $rec->invoiceNo; ?></td> <!-- arheaders invoiceNo -->
									<td class="data-title"></td>
								</tr>


								
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
								<th class="text-center w-10" align="center">SO No</th>
								<th class="text-center w-10" align="center">DR No</th>
								<th class="text-center w-10" align="center">Delivery Date</th>
								<th class="text-right w-10" align="right">Collection Amount</th>
								<th class="text-right w-10" align="right">Total Amount Collected</th>
								<th class="text-right w-10" align="right">DR Balance</th>
                            </tr>
                          </thead>
                          <tbody>
                        	<?php 
 							// PO No
 							// RR No
 							// Delivery Date
 							// Amount
                        	if (!(empty($rec)) && !(empty($details))) {                        	
                        		foreach($details as $row) { ?>
                        		<tr class="rowdetails">
	                        		<td align="center"><?php echo $row->drNo;?></td>	                        		
	                        		<td align="center"><?php echo $row->soNo;?></td>	<!-- Sales Order soNo -->                        		
	                        		<td align="center"><?php echo date('M d, Y', strtotime($row->deliveryDate));?></td> 
	                        		<td align="right"><?php echo number_format($row->amount, 2) ?></td>
	                        		<td align="right"><?php echo number_format($row->amountPaid, 2) ?></td>
	                        		<td align="right"><?php echo number_format($row->drTotalAmount - $row->amountPaid, 2) ?></td>
                        			
                        		</tr>
                        		<?php 
                        			$totalamount += $row->amount;
                        			$totalPaid += $row->amountPaid;
                        			$totalBalance += ($row->drTotalAmount - $row->amountPaid);
                        		} //end of foreach ?>
								<tr class="bg-light">
									
                        			<td colspan="3" class="text-right font-weight-bold" align="right"></td>
									<td align="right" colspan="" class="font-weight-bold" align="right">&#8369; <?php echo number_format($totalamount,2);?></td>
									<td align="right" colspan="" class="font-weight-bold" align="right">&#8369; <?php echo number_format($totalPaid,2);?></td>
									<td align="right" colspan="" class="font-weight-bold" align="right">&#8369; <?php echo number_format($totalBalance,2);?></td>
								</tr>								
								<tr class="">									
									<td colspan="4" class="font-weight-bold">&nbsp;  &nbsp;</td>
								</tr>

                        	<?php } else { ?>
                        		<tr>
	                        		<td colspan="4" class="text-center">No records found.</td>
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
								<?php if ($rec->createdBy != '') { ?>
								<tr>
									<td class="data-title" style="width:120px" nowrap>Created By:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $rec->createdByFirst.' '.$rec->createdByMiddle.' '.$rec->createdByLast; ?></td>
									<td class="data-title" style="width:120px" nowrap>Date Created:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo date('M d,Y g:i A',strtotime($rec->dateInserted)); ?></td>
									<td class="data-title"></td>
								</tr>
								<?php } ?>
								<?php if ($rec->confirmedBy != '') { ?>
								<tr>
									<td class="data-title" style="width:120px" nowrap>Confirmed By:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $rec->confirmedByFirst.' '.$rec->confirmedByMiddle.' '.$rec->confirmedByLast; ?></td>
									<td class="data-title" style="width:120px" nowrap>Date Confirmed:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo date('M d,Y H:i A',strtotime($rec->dateConfirmed)); ?></td>
									<td class="data-title"></td>
								</tr>	
								<?php } ?>									
								<?php if ($rec->closedBy != '') { ?>
								<tr>
									<td class="data-title" style="width:120px" nowrap>Closed By:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $rec->closedByFirst.' '.$rec->closedByMiddle.' '.$rec->closedByLast; ?></td>
									<td class="data-title" style="width:120px" nowrap>Date Closed:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo date('M d,Y g:i A',strtotime($rec->dateClosed)); ?></td>
									<td class="data-title"></td>
								</tr>	
								<?php } ?>	
								<?php if ($rec->cancelledBy != '' ) { ?>						
								<tr>
									<td class="data-title" style="width:120px" nowrap>Cancelled By:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $rec->cancelledByFirst.' '.$rec->cancelledByMiddle.' '.$rec->cancelledByLast; ?></td>
									<td class="data-title" style="width:120px" nowrap>Date Cancelled:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo date('M d,Y g:i A',strtotime($rec->dateCancelled)); ?></td>
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
	
	function confirm_record(status='') {

		var title = '';
		if (status == 2) {
			title = 'confirm this record';
		} else if (status == 0) {
			title = 'cancel this form';
		}
		var id = "<?php echo $rec->collectionID; ?>";
		var arID = "<?php echo $rec->arID; ?>";
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

				$.post("<?php echo $controller_page; ?>/confirm_record", { id: id, status: status, arID: arID },
					function(response){ 
						console.log(response);
						window.location='';
					}, "json");
			}
		});

	}

	function deleteRecord(id){
		console.log('deleteRecord',id);
	}


</script>