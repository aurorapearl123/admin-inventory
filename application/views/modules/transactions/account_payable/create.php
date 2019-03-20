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
							<h4 class="head-text"> New <?php echo $current_module['module_label'] ?></h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo $controller_page ?>/save" enctype="multipart/form-data">
						<input type="hidden" name="grossAmount" id="grossAmount" value="">
						
						<div class="table-row">
							<table class="table-form column-3">
								<tbody class="">
									<tr>
										<td class="form-label" width="12%">PO No:<span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%" nowrap>

											<select class="form-control " id="poID" name="poID" data-live-search="true" livesearchnormalize="true" title="Supplier"  onchange="get_rr_payables();" required>
												<option value="" selected>&nbsp;</option>
												<?php 
												$this->db->where('status', 4);
												$results = $this->db->get('poheaders')->result();
												foreach($results as $res) { ?>
													<option value="<?php echo $res->poID ?>"><?php echo $res->poNo ?></option>
												<?php } ?>
											</select>
										</td>



										<td class="form-label" width="12%"></td>
										<td class="form-group form-input" width="21.33%">
											
										</td>
										<td class="form-label" width="12%"></td>
										<td class="form-group form-input" width="21.33%">
											
										</td>
									</tr>
									<tr>
										<td class="form-label" width="12%">Supplier:<span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%" nowrap>

											<select class="form-control " id="suppID" name="suppID" data-live-search="true" livesearchnormalize="true" title="Supplier"  required>
												<option value="" selected>&nbsp;</option>
												<?php foreach($suppliers as $row) { ?>
													<option value="<?php echo $row->suppID ?>"><?php echo $row->suppName ?></option>
												<?php } ?>
											</select>
											
										</td>



										<td class="form-label" width="12%">Invoice No: <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control" id="invoiceNo" name="invoiceNo" title="P.O No" value="<?php echo $series ?>" required>
										</td>
										<td class="form-label" width="12%"></td>
										<td class="form-group form-input" width="21.33%">
											
										</td>
									</tr>
									<tr>
										<td class="form-label">Payment Terms:<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<select id="paymentTerm" name="paymentTerm" class="form-control" title="Payment Term">
												<option value="">&nbsp;</option>
												<option value="0">None</option>
												<option value="1">Credit Card</option>
												<option value="2">EOM - End of month</option>
												<option value="3">COD - Cash on delivery</option>
												<option value="4">Bank Deposit</option>
											</select>
										</td>
										<td class="form-label">Invoice Date: <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control datepicker" id="invoiceDate" name="invoiceDate" data-toggle="datetimepicker" value="<?php echo date('M d, Y')?>" data-target="#invoiceDate" title="Date" required>
										</td>
										<td class="form-label"></td>
										<td class="form-group form-input">
											
										</td>
									</tr>

									

									<tr>
										<td class="form-label">Balance: </td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="balance" name="balance" title="Balance" value="" onkeypress="return isNumber(event);" readonly>
										</td>
										<td class="form-label">Due Date: </td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="dueDate" name="dueDate" title="Due Date" >

											
										</td>
										<td class="form-label"></td>
										<td class="form-group form-input">
											
										</td>
									</tr>
									<tr>
										<td class="form-label">Amount Paid: </td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="amountPaid" name="amountPaid" title="Amount Paid" value="" onkeypress="return isNumber(event);" readonly>
										</td>
										<td class="form-label">Date Paid: </td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="datePaid" name="datePaid" title="Due Date" readonly>
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




					<!-- Details start -->

					<div class="datatables_wrapper">
						<div class="table-responsive-md">
							<form method="post" name="frmAdd" id="frmAdd" ><!-- Form Start -->

													
								<table id="" class="table">
									
										<tbody></tbody>
									</table>

								</form><!-- Form End -->
							</div>
						</div>


						<div class="subtitle mt-20">
							<h5 class="title mr-auto"></h5>
							<div class="subtitle-tools">
							</div>
						</div>
						<div class="datatables_wrapper">
							<div id="div_products" class="table-responsive-xl">
								<table id="table_products" class="table hover">
									<thead class="thead-light" align="center">
										<tr>
											
											<th class="w-10"><div align="center">PO No</div></th>
											<th class="w-10"><div align="center">RR No</div></th>
											<th class="w-10"><div align="center">Delivery Date</div></th>
											<th class="w-10"><div align="right">Amount</div></th>

										</tr>
									</thead>
									<tbody  id="tbody_products">
										<tr>
											<td colspan="4">&nbsp;</td>

										</tr>
										<tfoot>
											<tr class="bg-light">
												<td>
												</td>
												<td>
												</td>
												<input type="hidden" value="1849815" name="totalAmount" id="totalAmount">
												<td class="font-weight-bold text-right">
													<span>TOTAL: </span>
												</td>
												<td class="font-weight-bold" align="right" style="white-space: nowrap;"><span>₱ 0.00</span>
												</td>
											</tr>
										</tfoot>
									</tbody>
								</table>
							</div>
						</div>
						<!-- Details end -->







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


	<!-- E insert ang same IAR No sa iarheaders table, together sa iya new apID, para multiple ang apID sa usa ka iarNo. -->
	<script>

		$(document).ready(function(){



			$('#productPrice,#productQty').keyup(function(){
				$('#productAmount').val(parseFloat($('#productQty').val() * $('#productPrice').val()).toFixed(2));

			});
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
					window.location = '<?php echo $controller_page.'/show'; ?>';
				}
			});

		});













//Reuse this functions start=============================================================
var site_url = '<?php echo site_url(); ?>';
//change start
page_type = 'create';
//change end









function check_fields2()
{
	var valid = true;
	var req_fields = "";

	$('#frmAdd [required]').each(function(){
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



function tm_display_session_products(sessionSet, display_area) 
{  	
	var grossAmount = $('#grossAmount');
	$.post('<?php echo $controller_page ?>/display_session_products/'+display_area, { sessionSet: sessionSet },
		function(data){
			$('#'+display_area).html(data);
			if (page_type == 'view') {
				$('.la-trash-o.product').hide();
			}

			grossAmount.val($('#totalAmount').val());
		}, "text");
}

//Session functions end









//Reuse this functions end=============================================================

function get_rr_payables()
{
	var poID = $('#poID').val();
	// console.log(rrID);

	$.post(site_url+'generic_ajax/get_rr_payables/', { poID: poID },
		function(data){
			console.log(data);

			tm_display_session_products('apdetails', 'div_products');


			$('#suppID').val(data.rec.suppID);

			//Balance
			$('#balance').val(parseFloat(data.rec.totalAmount).toFixed(2));
			
			// $('#dueDate').val(new Date(data.rec.dueDate).toLocaleTimeString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }));
			$('#dueDate').val(new Date(data.rec.dueDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }));
			$('#datePaid').val(new Date(data.rec.datePaid).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }));

			//other values
			$('#paymentTerm').selectpicker('val', data.rec.paymentTerm);
			$('#suppID').selectpicker('val', data.rec.suppID);
			$('#paymentTerm,#suppID').selectpicker('render');
			$('#paymentTerm,#suppID').selectpicker('refresh');
			
		}, "json");
}
</script>