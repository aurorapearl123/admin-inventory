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
						<div class="table-row">
							<table class="table-form column-3">
								<tbody class="">
									<tr>
										<td class="form-label" width="12%">Payment No:<span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control" id="paymentNo" name="paymentNo" title="Payment No" value="<?php echo $rec->paymentNo ?>" required>
										</td>
										<td class="form-label" width="12%">Payment Date: <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control datepicker" id="paymentDate" name="paymentDate" data-toggle="datetimepicker" value="<?php echo ($rec->paymentDate)? date('M d, Y', strtotime($rec->paymentDate)) : ""; ?>" data-target="#paymentDate" title="Payment Date" required>
										</td>
										<td class="form-label" width="12%"></td>
										<td class="form-group form-input" width="21.33%">
											
										</td>
									</tr>
									<tr>
										<td class="form-label">OR No:<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="orNo" name="orNo" title="OR No" value="<?php echo $rec->orNo ?>" required>
										</td>
										<td class="form-label">OR Date: <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control datepicker" id="orDate" name="orDate" data-toggle="datetimepicker" value="<?php echo ($rec->orDate)? date('M d, Y', strtotime($rec->orDate)) : ""; ?>" data-target="#orDate" title="OR Date" required>
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


	<!-- E insert ang same IAR No sa iarheaders table, together sa iya new poID, para multiple ang poID sa usa ka iarNo. -->
	<script>

		$(document).ready(function(){
			get_po_select_products();


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
page_type = 'edit';
//change end












if (page_type == 'view') {
	tm_display_session_products('rrdetails', 'div_products');
} else if (page_type == 'edit') {
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
echo $this->htmlhelper->get_json_select('get_po_select_products', $parameters, site_url('generic_ajax/get_po_select_products'), 'productID', '', '') ;
?>





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
			poID: poID
		},
		function(data){ 
			console.log('set_products',data)

			var ttl = parseFloat(data.rec.qty) - parseFloat(data.rec.delQty);
			
			ttl = (ttl > 0)? ttl : 0; 

			//change start
			$('#productName').val(data.rec.name);
			$('#productDescription').val(data.rec.description);
			$('#productQty').val(ttl);
			$('#productUmsr').val(data.rec.umsr); 
			$('#productPrice').val(parseFloat(data.rec.price)); 
			$('#productAmount').val(parseFloat(data.rec.amount).toFixed(2));
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
	$(display_area).html(table_products_template());
	clear_products();
}

function clear_products() {
	var sessionSet = $('#sessionSet').val();
	$.post(site_url+'generic_ajax/clear_session_js', { sessionSet: sessionSet },
		function(data){
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
		}, "text");

}





//Reuse this functions end=============================================================
</script>