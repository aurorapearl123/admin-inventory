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
						<input type="hidden" class="form-control" name="poID2" id="poID2"  >
						<input type="hidden" class="form-control" name="suppID2" id="suppID2"  >
						<div class="table-row">
							<table class="table-form column-3">
								<tbody class="">
									<tr>
										<td class="form-label" width="12%">RR No:<span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control" id="rrNo" name="rrNo" title="RR No" value="<?php echo $series ?>" required>
										</td>
										<td class="form-label" width="12%">RR Date: <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control datepicker" id="rrDate" name="rrDate" data-toggle="datetimepicker" value="<?php echo date('M d, Y')?>" data-target="#rrDate" title="RR Date" required>
										</td>
										<td class="form-label" width="12%"></td>
										<td class="form-group form-input" width="21.33%">
											
										</td>
									</tr>
									<tr>
										<td class="form-label">DR No:<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="drNo" name="drNo" title="DR No" value="<?php echo $series ?>" required>
										</td>
										<td class="form-label">DR Date: <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control datepicker" id="drDate" name="drDate" data-toggle="datetimepicker" value="<?php echo date('M d, Y')?>" data-target="#drDate" title="DR Date" required>
										</td>
										<td class="form-label"></td>
										<td class="form-group form-input">
											
										</td>
									</tr>
									
									<tr>
										<td class="form-label">Driver Name: <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="driverName" name="driverName" title="Driver Name" required>
										</td>
										<td class="form-label">Plate No: <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="plateNo" name="plateNo" title="Plate No" required>
										</td>
										<td class="form-label"></td>
										<td class="form-group form-input">
											
										</td>
									</tr>

									<tr>
										<td class="form-label">Driver Assistant: </td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="driverAssistant" name="driverAssistant" title="Driver Assistant">
										</td>
										<td class="form-label">Time Delivered: <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control datetimepicker" id="timeDelivered" name="timeDelivered" data-toggle="datetimepicker" value="" data-target="#timeDelivered" title="Time Delivered" required>
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
								<input type="hidden" name="sessionSet" id="sessionSet" value="rrdetails" />						
								<table id="table_items" class="table">
									<thead class="thead-light" align="center">
										<tr>
											<th class="dataField w-15" nowrap>Supplier</th>
											<th class="dataField w-15" nowrap>PO No</th>
											<th class="dataField w-40" nowrap>Product</th>
											<th class="dataField w-10" nowrap>Qty</th>
											<th class="dataField w-10" nowrap>Unit Cost</th>
											<th class="dataField w-10" nowrap>Amount</th>
											<th colspan="4"></th>
										</tr>
										<tr>
											<th>
												<select id="suppID" name="suppID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Suppliers" onchange="get_po_suppliers();" required>
													<option value="">&nbsp;</option>
													<?php 
													$this->db->where('status >', 0);
													$results = $this->db->get('suppliers')->result();
													foreach($results as $res){
														?>
														<option value="<?php echo $res->suppID?>" ><?php echo $res->suppName; ?></option>
													<?php }?>
												</select>
											</th>
											<th>
												<select id="poID" name="poID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Purchase Order" onchange="get_po_select_products();" required>
													<option value="">&nbsp;</option>
													
												</select>
											</th>
											<th class="form-group form-input w-10" align="left" >


												<select class="form-control country" id="productID" name="productID" data-live-search="true" livesearchnormalize="true" title="product"  onchange="set_podetails_product();" required>
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

//change start
$parameters = array('poID');
//change end
echo $this->htmlhelper->get_json_select('get_po_select_products', $parameters, site_url('generic_ajax/get_po_select_products'), 'productID', '', 'confirm_change();') ;
?>

function confirm_change()
{
	if ($('#poID2').val() != '' && $('#poID2').val() != $('#poID').val()) {
		swal({
			title: 'You are about to change and reset all entries.',
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

				var elementID = '#div_products';
				reset_list(elementID);
				
			} else {
				//change start
				$('#poID').selectpicker('val', $('#poID2').val());
				$('#productID').selectpicker('render');
				$('#productID').selectpicker('refresh');
				//change end
				get_po_select_products();
			}
		});
	}

	if ($('#suppID2').val() != '' && $('#suppID2').val() != $('#suppID').val()) {
		swal({
			title: 'You are about to change and reset all entries.',
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

				var elementID = '#div_products';
				reset_list(elementID);
				
			} else {
				//change start
				$('#poID').selectpicker('val', $('#poID2').val());
				$('#productID').selectpicker('render');
				$('#productID').selectpicker('refresh');
				//change end
				get_po_select_products();
			}
		});
	}
}

function set_podetails_product()
{
	//change start
	//details
	var productID = $('#productID').val();
	var catID = $('#catID').val();
	var suppID = $('#suppID').val();
	var poID = $('#poID').val();
	//path
	var path = "generic_ajax/set_podetails_product/";
	//change end

	console.log(productID);

	if (catID !="") {
		$.post(site_url+path, { 
			poID: poID, productID: productID
		},
		function(data){ 
			console.log('set_products',data)

			var ttl = parseFloat(data.rec.qty) - parseFloat(data.rec.delQty);
			
			ttl = (ttl > 0)? ttl : 0; 

			var ttlAmount = ttl * data.rec.price;
			//change start
			$('#productName').val(data.rec.name);
			$('#productDescription').val(data.rec.description);
			$('#productQty').val(ttl);
			$('#productUmsr').val(data.rec.umsr); 
			$('#productPrice').val(parseFloat(data.rec.price)); 
			$('#productAmount').val(parseFloat(ttlAmount).toFixed(2));
			$('#productCode').val(data.rec.productCode);
			$('#productCategory').val(data.rec.category);
			$('#suppID2').val(suppID);
			$('#poID2').val(poID);
			//change end

		}, "json");
	} else {
		//change start
		
		$('#productName').val("");
		$('#productDescription').val("");
		$('#productQty').val("");
		$('#productUmsr').val("");
		$('#productPrice').val("");
		$('#productAmount').val("");
		$('#productCode').val("");
		$('#productCategory').val("");
		$('#suppID').val('');
		$('#poID').val('');
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
	$(display_area).html(table_items_template());
	clear_items();
}

function clear_items() {
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
			$('#productExpiry').val("");
			//change end
	}, "text");

}


//template
function table_items_template() {
	var list = '';
	list += '<table id="table_items" class="table hover">';
	//change start
	var headers = ['', 'Category', 'Product', 'Qty', 'Unit', 'Unit Cost', 'Amount'];
	var widths = ['w-5', 'w-10', 'w-35'];
	var aligns = ['', '', '', 'left', '', 'right', 'right'];
	var default_width = 'w-10';
	var default_align = 'center';
	var default_rows = 6;
	var default_columns = 7;
	//change end

	//header start
	list += '<thead class="thead-light">';
	list += '<tr>';
	for (var i = 0; i < headers.length; i++) {
		list += '<th class="'+((widths[i])? widths[i]:default_width) +'"><div align="'+((aligns[i])? aligns[i]:default_align) +'">'+headers[i]+'</div></th>';
	}
	list += '</tr>';
	list += '</thead>';
	//header end

	//tbody start
	list += '<tbody  id="tbody_items">';
	for (var i = 0; i < default_rows; i++) {
		list += '<tr>';
		list += '<td colspan="'+default_columns+'" style="height: 40px;"></td>';
		list += '</tr>';
	}
	list += '</tbody>';
	//tbody end
	list += '</table>';
	
	
	return list;
}


//Reuse this functions end=============================================================
</script>