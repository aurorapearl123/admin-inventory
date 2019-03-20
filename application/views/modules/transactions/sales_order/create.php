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
										<td class="form-label" width="12%">Customer:<span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%" nowrap>

											<select class="form-control " id="custID" name="custID" data-live-search="true" livesearchnormalize="true" title="Customer"  required>
												<option value="" selected>&nbsp;</option>
												<?php foreach($customers as $row) { ?>
													<option value="<?php echo $row->custID ?>"><?php echo $row->companyName; ?></option>
													<!-- <option value="<?php echo $row->custID ?>"><?php echo $row->lname.', '.$row->fname.' '.$row->mname; ?></option> -->
												<?php } ?>
											</select>
										</td>



										<td class="form-label" width="12%">S.O No: <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control" id="soNo" name="soNo" title="S.O No" value="<?php echo $series ?>" required>
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
												<option value="1">Credit Card</option>
												<option value="2">EOM - End of month</option>
												<option value="3">COD - Cash on delivery</option>
												<option value="4">Bank Deposit</option>
											</select>
										</td>
										<td class="form-label">SO Date: <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control datepicker" id="soDate" name="soDate" data-toggle="datetimepicker" value="<?php echo date('M d, Y')?>" data-target="#soDate" title="Date" required>
										</td>
										<td class="form-label"></td>
										<td class="form-group form-input">
											
										</td>
									</tr>
									<tr>
										<td class="form-label">Customer P.O. No: <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="poNo" name="poNo" title="P.O. No" value="" required>
										</td>
										<td class="form-label">Customer P.O. Date: <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control datepicker" id="poDate" name="poDate" data-toggle="datetimepicker" value="" data-target="#poDate" title="P.O. Date" required>
										</td>
										
										<td class="form-label"></td>
										<td class="form-group form-input">
											
										</td>
									</tr>

									<tr>
										<td class="form-label">Delivery Term</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="deliveryTerm" name="deliveryTerm" title="Balance" value="">
										</td>
										<td class="form-label">Delivery Date: <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control datepicker" id="deliveryDate" name="deliveryDate" data-toggle="datetimepicker" value="" data-target="#deliveryDate" title="Delivery Date" required>
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
										<td class="form-label">Due Date: <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control datepicker" id="dueDate" name="dueDate" data-toggle="datetimepicker" value="" data-target="#dueDate" title="Due Date" required>

											
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
											<input type="text" class="form-control datepicker" id="datePaid" name="datePaid" data-toggle="datetimepicker" value="" data-target="#datePaid" title="Date Paid" readonly>
										</td>
										<td class="form-label"></td>
										<td class="form-group form-input">
											
										</td>
									</tr>

									<tr>
										<td class="form-label"></td>
										<td class="form-group form-input">
											
											

										</td>
										<td class="form-label">Discount: </td>
										<td class="form-group form-input">
											<table class="table">
												<tr>
													<td style="padding: 0px;" width="150px;">
														<input type="text" id="discount" name="discount" value="" class="form-control">
													</td>
													<td width="50px;" align="right">
														<div class="checkbox">
															<label>
																<input type="checkbox" id="discountType" name="discountType" value="1">&nbsp;<i>Percent</i>
															</label>
														</div>
													</td>
												</tr>
											</table>
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

								<input type="hidden" class="form-control" name="productUmsr" id="productUmsr"  >
								<input type="hidden" class="form-control" name="productName" id="productName"  >
								<input type="hidden" class="form-control" name="productDescription" id="productDescription"  >
								<input type="hidden" class="form-control" name="productCode" id="productCode"  >
								<input type="hidden" class="form-control" name="productCategory" id="productCategory"  >
								<input type="hidden" name="sessionSet" id="sessionSet" value="sodetails" />						
								<table id="" class="table">
									<thead class="thead-light" align="center">
										<tr>
											<th class="dataField w-20" nowrap>Category</th>
											<th class="dataField w-40" nowrap>Product</th>
											<th class="dataField w-10" nowrap>Qty</th>
											<th class="dataField w-10" nowrap>Unit Cost</th>
											<th class="dataField w-10" nowrap>Amount</th>
											<th colspan="4"></th>
										</tr>
										<tr>
											<th>
												<select id="catID" name="catID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Category" onchange="get_category_products();" required>
													<option value="">&nbsp;</option>
													<?php 
													$this->db->where('status >', 0);
													$results = $this->db->get('category')->result();
													foreach($results as $res){
														?>
														<option value="<?php echo $res->catID?>"><?php echo $res->category; ?></option>
													<?php }?>
												</select>
											</th>
											<th class="form-group form-input w-10" align="left" >


												<select class="form-control country" id="productID" name="productID" data-live-search="true" livesearchnormalize="true" title="product"  onchange="set_category_product();" required>
													<option value="" selected>&nbsp;</option>

												</select>
											</th>
											
											<th class="form-group form-input " align="center" >
												<input type="text" class="form-control " name="productQty" id="productQty" required title="product Quantity" onkeypress="return isNumber(event)" onfocus="$(this).select();">
											</th>
											<th class="dataField" align="left" nowrap>			
												<input type="text" class="form-control " name="productPrice" id="productPrice" required title="product Price" onkeypress="return isNumber(event)" onfocus="$(this).select();">
											</th>

											<th class="dataField" align="left" nowrap>			
												<input type="text" class="form-control " name="productAmount" id="productAmount" required title="product Amount" readonly>
											</th>

											<th class="" align="center">
												<!-- form_source, fields, display_area, do_reset=1, returnField="", success_msg="", error_msg="", checkDuplicate=0, duplicateField="", callback="" -->
												<input type="button" class="btn btn-xs btn-primary pill btn-block" id="addproductsBtn" value=" Add " onclick="tm_add_session_product('frmAdd', 'productID,productUmsr,productName,productQty,productPrice,productAmount,productDescription,productCode,productCategory', 'div_products',0,'','','',1,'productID','');" /></th>
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
									<thead class="thead-light" align="center">
										<tr>
											<th class="w-5"><div align="center"></div></th>
											<th class="w-10"><div align="center">Category</div></th>
											<th class="w-35"><div align="left">Product</div></th>
											<th class="w-10"><div align="center">Qty</div></th>
											<th class="w-10"><div align="center">Unit</div></th>
											<th class="w-10"><div align="right">Unit Cost</div></th>
											<th class="w-10"><div align="right">Amount</div></th>

										</tr>
									</thead>
									<tbody  id="tbody_products">
										<tr>
											<td colspan="8">&nbsp;</td>

										</tr>
										<tr>
											<td colspan="8">&nbsp;</td>

										</tr>
										<tr>
											<td colspan="8">&nbsp;</td>

										</tr>
										<tr>
											<td colspan="8">&nbsp;</td>

										</tr>
										<tr>
											<td colspan="8">&nbsp;</td>

										</tr>
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


	<!-- E insert ang same IAR No sa iarheaders table, together sa iya new poID, para multiple ang poID sa usa ka iarNo. -->
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












if (page_type == 'view') {
	tm_display_session_products('sodetails', 'div_products');
}

<?php 
//change start
$parameters = array('catID');
//change end
echo $this->htmlhelper->get_json_select('get_category_products', $parameters, site_url('generic_ajax/get_category_products'), 'productID', '', '') ;
?>



function set_category_product()
{
	//change start
	//details
	var productID = $('#productID').val();
	var catID = $('#catID').val();
	//path
	var path = "generic_ajax/set_category_product/";
	//change end

	console.log(productID);

	if (catID !="") {
		$.post(site_url+path, { 
			productID: productID,
			catID: catID
		},
		function(data){ 
			console.log('set_products',data)

			//change start
			$('#productName').val(data.rec.name);
			$('#productDescription').val(data.rec.description);
			// $('#productQty').val(data.rec.qty);
			$('#productUmsr').val(data.rec.umsr); 
			// $('#productPrice').val(parseFloat(data.rec.price)); 
			// $('#productAmount').val(parseFloat(data.rec.amount).toFixed(2));
			$('#productCode').val(data.rec.productCode);
			$('#productCategory').val(data.rec.category);
			//change end

		}, "json");
	} else {
		//change start
		
		$('#productName').val("");
		$('#productDescription').val("");
		// $('#productQty').val("");
		$('#productUmsr').val("");
		// $('#productPrice').val("");
		// $('#productAmount').val("");
		$('#productCode').val("");
		$('#productCategory').val("");
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
		$('#productQty,#productAmount,#productPrice').val('');
		$('#productID').selectpicker('val', '');
		$('#productID').selectpicker('render');
		$('#productID').selectpicker('refresh');
		//change end
	}

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
			$('#productUmsr').val("");
			$('#productName').val("");
			$('#productQty').val("");
			$('#productPrice').val("");
			$('#productDescription').val("");
			$('#productAmount').val("");
			$('#productCategory').val("");
			//change end
		}, "text");

}





//Reuse this functions end=============================================================
</script>