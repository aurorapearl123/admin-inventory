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
						
						<input type="hidden" class="form-control" name="apID2" id="apID2"  >
						<div class="table-row">
							<table class="table-form column-3">
								<tbody class="">
									<tr>
										<td class="form-label" width="12%">Payment No:<span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control" id="paymentNo" name="paymentNo" title="Payment No" value="<?php echo $series ?>" required>
										</td>
										<td class="form-label" width="12%">Payment Date: <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control datepicker" id="paymentDate" name="paymentDate" data-toggle="datetimepicker" value="<?php echo date('M d, Y')?>" data-target="#paymentDate" title="Payment Date" required>
										</td>
										<td class="form-label" width="12%"></td>
										<td class="form-group form-input" width="21.33%">
											
										</td>
									</tr>
									<tr>
										<td class="form-label">OR No:<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="orNo" name="orNo" title="OR No" value="<?php echo $series ?>" required>
										</td>
										<td class="form-label">OR Date: <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control datepicker" id="orDate" name="orDate" data-toggle="datetimepicker" value="<?php echo date('M d, Y')?>" data-target="#orDate" title="OR Date" required>
										</td>
										<td class="form-label"></td>
										<td class="form-group form-input">
											
										</td>
									</tr>

									
									<tr>
										<td class="form-label">Payment Method:<span class="asterisk">*</span>
										</td>
										<td class="form-group form-input">
											<select class="form-control" id="paymentMethod" name="paymentMethod" onchange="select_payment_method();">
												<option value="">&nbsp;</option>
												<option value="cash">Cash</option>
												<option value="check">Check</option>
											</select>
										</td>
										<td class="form-label"> </td>
										<td class="form-group form-input">

										</td>
										<td class="form-label"></td>
										<td class="form-group form-input">
											
										</td>
									</tr>

									<tr id="check_tr">
										<td class="form-label">Check No: <span class="asterisk">*</span>
										</td>
										<td class="form-group form-input">
											<input type="text" name="checkNo" id="checkNo" class="form-control">
										</td>
										<td class="form-label">Check Date: </td>
										<td class="form-group form-input">
											<input type="text" class="form-control datepicker" id="checkDate" name="checkDate" data-toggle="datetimepicker" value="<?php echo date('M d, Y')?>" data-target="#checkDate" title="Check Date">
										</td>
										<td class="form-label"></td>
										<td class="form-group form-input">
											
										</td>
									</tr>


									<tr id="cash_tr" style="display: none;">
										<td class="form-label">Bank Account No: <span class="asterisk">*</span>
										</td>
										<td class="form-group form-input">
											<select class="form-control" id="bankAccountID" name="bankAccountID" onchange="set_bank_details();">
												<option value="">&nbsp;</option>
												<?php 
												$result = $this->db->get('bank_accounts')->result();

												foreach ($result as $res) { ?>
													
													<option value="<?php echo $res->bankAccountID ?>"><?php echo $res->accountNo ?></option>
													
												<?php } ?>
												
											</select>
										</td>
										<td class="form-label">Account Name: </td>
										<td class="form-group form-input">
											<input type="text" name="accountName" id="accountName" class="form-control">
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

								<input type="hidden" class="form-control" name="poID" id="poID"  >
								<input type="hidden" class="form-control" name="rrID" id="rrID"  >
								<input type="hidden" class="form-control" name="poNo" id="poNo"  >
								<input type="hidden" class="form-control" name="rrNo" id="rrNo"  >
								<input type="hidden" class="form-control" name="invoiceDetails" id="invoiceDetails"  >
								<input type="hidden" class="form-control" name="apDetailsID" id="apDetailsID"  >
								
								
								<input type="hidden" name="sessionSet" id="sessionSet" value="payment_details" />						
								<table id="table_products" class="table">
									<thead class="thead-light" align="center">
										<tr>
											<th class="dataField w-15" nowrap>Invoice No</th>
											<th class="dataField w-40" nowrap>Invoice Details</th>
											<th class="dataField w-10" nowrap>Amount Paid</th>
											<th class="dataField w-10" nowrap>Balance</th>
											<th class="dataField w-10" nowrap>Amount To Pay</th>
											<th colspan="4"></th>
										</tr>
										<tr>
											<th>
												<select id="apID" name="apID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Invoice No" onchange="get_invoice_select();" required>
													<option value="" selected>&nbsp;</option>
													<?php 
													$this->db->where_in('status', array(2,3));
													$results = $this->db->get('apheaders')->result();
													foreach($results as $res){
														?>
														<option value="<?php echo $res->apID?>" ><?php echo $res->invoiceNo; ?></option>
													<?php }?>
												</select>
											</th>
											<th>
												<select id="details" name="details" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Invoice Details" onchange="set_invoice_details();" required>
													<option value="">&nbsp;</option>
													
												</select>
											</th>
											
											<th class="form-group form-input w-10" align="left" >

												<input type="text" class="form-control " name="amountPaid" id="amountPaid" required title="Amount Paid" onkeypress="return isNumber(event)" onfocus="$(this).select();" readonly>
												
											</th>
											
											<th class="form-group form-input " align="center" >
												<input type="text" class="form-control " name="balance" id="balance" required title="Balance" onkeypress="return isNumber(event)" onfocus="$(this).select();" readonly>
											</th>
											<th class="dataField" align="left" nowrap>			
												
												<input type="text" class="form-control " name="amount" id="amount" required title="Amount To Pay" onkeypress="return isNumber(event)" onfocus="$(this).select();">
											</th>



											<th class="" align="center">
												<!-- form_source, fields, display_area, do_reset=1, returnField="", success_msg="", error_msg="", checkDuplicate=0, duplicateField="", callback="" -->
												<input type="button" class="btn btn-xs btn-primary pill btn-block" id="addproductsBtn" value=" Add Payment " onclick="tm_add_session_product('frmAdd', 'amountPaid,balance,amount,poNo,rrNo,poID,rrID,apDetailsID,invoiceDetails', 'div_products',0,'','','',0,'rrID','');" /></th>
												<th colspan="5"></th>						
											</tr>
										</thead>
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
									<thead class="thead-light">
										<tr><th class="w-5 text-left" align="left" nowrap=""></th>
											<th class="w-10 text-left" align="left" nowrap="">PO No</th>
											<th class="w-10 text-left" align="left" nowrap="">RR No</th>
											<th class="w-35 text-left" align="left" nowrap="">Details</th>
											<th class="w-10 text-right" align="right" nowrap="">Amount</th></tr></thead>
											<tbody>
												<tr colspan="6">
													<td>
													</td>
													<td align="left" nowrap=""></td>
													<td align="left" nowrap=""></td>
													<td align="left" nowrap=""></td>
													<td align="right" nowrap=""></td>
												</tr></tbody>
												<tfoot>
													<tr class="bg-light">
														<td>
														</td>
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


	<!-- E insert ang same IAR No sa iarheaders table, together sa iya new poID, para multiple ang poID sa usa ka iarNo. -->
	<script>

		$(document).ready(function(){



			$('#amount').keyup(function(){
				


				if (parseFloat($('#amount').val()) <= parseFloat($('#balance').val())) {
					$('#amountPaid').val(parseFloat($('#amount').val()));

				} else {
					swal('Please enter amount lower or equal to the balance.');
					$('#amount').val(0);
				}
				

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










		function select_payment_method()
		{
			var paymentMethod = $('#paymentMethod').val();
			var bankAccountNo = $('#bankAccountNo').val();

			if (paymentMethod == 'cash') {
				$('#check_tr').hide();
				$('#cash_tr').show();

			} else if (paymentMethod == 'check') {
				$('#check_tr').show();
				$('#cash_tr').hide();
			}
		}


		function set_bank_details()
		{

			var bankAccountID = $('#bankAccountID').val();
			var accountName = $('#accountName');

			$.post(site_url+'generic_ajax/set_bank_details', {
				bankAccountID: bankAccountID
			},

			function(data) {

				console.log(data);
				accountName.val(data.accountName);

			}, "json");
		}












//Reuse this functions start=============================================================
var site_url = '<?php echo site_url(); ?>';
//change start
page_type = 'create';
//change end












if (page_type == 'view') {
	tm_display_session_products('rrdetails', 'div_products');
}

<?php 
//change start
$parameters = array('catID');
//change end
echo $this->htmlhelper->get_json_select('get_category_products', $parameters, site_url('generic_ajax/get_category_products'), 'productID', '', '') ;


//change start
$parameters = array('suppID');
//change end
echo $this->htmlhelper->get_json_select('get_po_suppliers', $parameters, site_url('generic_ajax/get_po_suppliers'), 'poID', '', '') ;




?>

function get_invoice_select()
{
	$.post(site_url+"/generic_ajax/get_invoice_select", {
		apID: $('#apID').val()
	},
	function(data){
		$('#details').empty();
		$('#details').val("");
		$('#details').selectpicker("refresh");
		$('#details').append('<option value="" selected>&nbsp;</option>');
		$('#details').selectpicker("refresh");
		for(c = 0; c < data.length; c++){
			$('#details').append('<option value="'+data[c].id+'">'+data[c].display+'</option>');
			$('#details').selectpicker("refresh");
		}

		confirm_change();


	}, "json");
}

function confirm_change()
{
	var elementID = '#div_products';
	reset_list(elementID);
}



// amount: "160000.000"
// amountPaid: "0.000"
// apID: "8"
// balance: "0.000"
// dateDeleted: "0000-00-00 00:00:00"
// dateInserted: "2019-02-20 18:59:00"
// dateLastEdit: "0000-00-00 00:00:00"
// delQty: "0"
// deletedBy: ""
// id: "13"
// invoiceNo: "19000007"
// name: "DIESEL"
// poID: "30"
// poNo: "19000020"
// price: "0.00"
// productID: "0"
// qty: "4000"
// rrID: "22"
// rrNo: "19000007"
// status: "1"
// umsr: "Liter"
function set_invoice_details()
{
	//change start
	//details
	var apDetailsID = $('#details').val();
	var apID = $('#apID').val();

	//path
	var path = "generic_ajax/set_invoice_details/";
	//change end


	if (apID !="") {
		$.post(site_url+path, { 
			id: apDetailsID
		},
		function(data){ 
			console.log('set_products',data)

			var ttl = parseFloat(data.rec.amount) - parseFloat(data.rec.amountPaid);
			
			ttl = (ttl > 0)? ttl : 0; 

			//change start
			$('#amountPaid').val(parseFloat(data.rec.amountPaid));
			$('#balance').val(data.rec.balance);
			$('#amount').val(ttl);
			$('#poID').val(data.rec.poID);
			$('#rrID').val(data.rec.rrID);
			$('#apID2').val(apID);
			$('#apDetailsID').val(apDetailsID);
			$('#invoiceDetails').val(data.rec.poNo + '/ ' + data.rec.rrNo);
			$('#poNo').val(data.rec.poNo);
			$('#rrNo').val(data.rec.rrNo);

			//change end

		}, "json");
	} else {
		//change start
		var elementID = '#div_products';
		reset_list(elementID);
		//change end
	}
}

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




//Session functions start
function tm_add_session_product(form_source, fields, display_area, do_reset=1, returnField="", success_msg="", error_msg="", checkDuplicate=0, duplicateField="", callback="") 
{	
	if (check_fields2()) {
		fields = fields.replace(/,/gi,"_"); 
		
		$.post(site_url+"/sessionmanager/push_session_item/"+fields+"/"+checkDuplicate+"/"+duplicateField, $('#'+form_source).serialize(),
			function(data){

				if (parseInt(data)==1) {
					//alert(success_msg);
				} else if (parseInt(data)==2) {
					swal("Duplicate data");
				} else {
					swal(error_msg);
				}

				if (do_reset) {
					// reset form
					resetForm(form_source);
					// return field
					if (returnField) {
						$('#'+form_source+' #'+returnField).focus();
					}
				}		
				if (display_area != "") {
					tm_display_session_products($('#'+form_source+' #sessionSet').val(), display_area);
				}
				if (callback != "") {
					eval(callback);
				}				
			}, "text");
		
		//change start
		$('#amountPaid').val('');
		$('#balance').val('');
		$('#amount').val('');
		$('#poID').val('');
		$('#rrID').val('');
			// $('#apID2').val(''); //ayaw apila
			$('#apDetailsID').val('');
			$('#invoiceDetails').val('');
		//change end
	}

}

function tm_display_session_products(sessionSet, display_area) 
{  	
	$.post('<?php echo $controller_page ?>/display_session_products/'+display_area, { sessionSet: sessionSet },
		function(data){
			$('#'+display_area).html(data);
			if (page_type == 'view') {
				$('.la-trash-o.product').hide();
			}
		}, "text");
}

function tm_delete_session_product(product_name, product_id, display_area,callback="") 
{

	swal({
		title: "Confirm delete row?",
		text: "",
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, delete it!'
	})
	.then((willDelete) => {
		if (willDelete.value) {
			$.post(site_url+"/sessionmanager/delete_session_item", { sessionSet: product_name, targetID: product_id },
				function(data){
					if (parseInt(data)==1) {
				//alert("Successfully deleted!");
			} else {
				swal("Delete failed!","Unable to delete record.","warning");
			}

			if (display_area != "") {
				tm_display_session_products(product_name, display_area);
			}

			fn = window[callback];
			if (typeof fn === "function") fn();

		}, "text");
		}
	});
}
//Session functions end







//Clear functions
function reset_list(display_area) {
	$(display_area).html(table_products_template());
	clear_products();
}

function clear_products() {
	var sessionSet = $('#sessionSet').val();
	$.post(site_url+'generic_ajax/clear_session_js', { sessionSet: sessionSet },
		function(data){
			//change start
			$('#amountPaid').val('');
			$('#balance').val('');
			$('#amount').val('');
			$('#poID').val('');
			$('#rrID').val('');
			$('#apID2').val('');
			$('#apDetailsID').val('');
			$('#invoiceDetails').val('');
			$('#poNo').val('');
			$('#rrNo').val('');
			//change end
		}, "text");

}


//template
function table_products_template() {
	var list = '';
	list += '<table id="table_products" class="table hover">';
	list +='<thead class="thead-light">';
	list +='<tr><th class="w-5 text-left" align="left" nowrap=""></th>';
	list +='<th class="w-10 text-left" align="left" nowrap="">SO No</th>';
	list +='<th class="w-10 text-left" align="left" nowrap="">DR No</th>';
	list +='<th class="w-35 text-left" align="left" nowrap="">Details</th>';
	list +='<th class="w-10 text-right" align="right" nowrap="">Amount</th></tr></thead>';
	list +='<tbody>';
	list +='<tr colspan="6">';
	list +='<td>';
	list +='</td>';
	list +='<td align="left" nowrap=""></td>';
	list +='<td align="left" nowrap=""></td>';
	list +='<td align="left" nowrap=""></td>';
	list +='<td align="right" nowrap=""></td>';
	list +='</tr></tbody>';
	list +='<tfoot>';
	list +='<tr class="bg-light">';
	list +='<td>';
	list +='</td>';
	list +='<td>';
	list +='</td>';
	list +='<td>';
	list +='</td>';
	list +='<input type="hidden" value="1849815" name="totalAmount" id="totalAmount">';
	list +='<td class="font-weight-bold text-right">';
	list +='<span>TOTAL: </span>';
	list +='</td>';
	list +='<td class="font-weight-bold" align="right" style="white-space: nowrap;"><span>₱ 0.00</span>';
	list +='</td>';
	list +='</tr>';
	list +='</tfoot>';
	list += '</table>';
	
	
	return list;
}


//Reuse this functions end=============================================================
</script>