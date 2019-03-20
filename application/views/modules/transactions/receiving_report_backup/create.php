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
							<h4 class="head-text"> New <?php echo $current_module['module_label'] ?></h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo $controller_page.'/save'; ?>" >
							<input type="hidden" class="form-control" name="poID2" id="poID2" title="Purchase Order ID">
							<input type="hidden" class="form-control" name="poDate" id="poDate" title="PO Date">
							<input type="hidden" class="form-control" name="clusterFund" id="clusterFund" title="Cluster Fund" >
						
						
						<div class="table-row">
							<table class="table-form">
								<tbody>
									<tr>
										<td class="form-label" width="12%">RR No.<span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%">
											
											<input type="text" name="iarNo" id="iarNo" class="form-control" value="" title="RR No." required/>
											
										</td>
										<td class="form-label" width="12%">RR Date<span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control datepicker" id="iarDate" name="iarDate" data-toggle="datetimepicker" data-target="#iarDate" title="IAR Date" value="<?php echo date('M d, Y') ?>" required>
										</td>
										<td class="form-label" width="12%"></td>
										<td class="form-group form-input" width="21.33%">
											
										</td>
										
									</tr>

								</tbody>
							</table>
						</div>

						<!-- <div class="subtitle">
							<h5 class="title"><i class="icon left la la-user"></i> Delivery Report Details</h5>
						</div> -->
						<div class="table-row">
							<table class="table-form">
								<tbody>
									<tr>
										<td class="form-label" width="12%">DR No.</td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control" name="drNo" id="drNo" title="DR No.">
										</td>
										<td class="form-label" width="12%">DR Date</td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control datepicker" id="drDate" name="drDate" data-toggle="datetimepicker" data-target="#drDate" title="DR Date" value="<?php echo date('M d, Y') ?>" >
										</td>
										<td class="form-label" width="12%"></td>
										<td class="form-group form-input" width="21.33%">
											
										</td>
										
									</tr>

								</tbody>
							</table>
						</div>
						
						<div class="table-row">
							<table class="table-form">
								<tbody>
									<tr>
										<td class="form-label" width="12%" valign="">Remarks</td>
										<td class="form-group form-input" width="21.33%">
											<textarea class="form-control" name="remarks" id="remarks" title="Remarks" rows="3"></textarea>
										</td>
										
										<td class="form-label" ></td>
										<td >
											
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
									
									<input type="hidden" class="form-control" name="itemUmsr" id="itemUmsr"  >
									<input type="hidden" class="form-control" name="itemName" id="itemName"  >
									<input type="hidden" class="form-control" name="itemDescription" id="itemDescription"  >
									<input type="hidden" class="form-control" name="itemCode" id="itemCode"  >
									<!-- <input type="hidden" class="form-control" name="itemExpiry" id="itemExpiry"  > -->
									<input type="hidden" name="sessionSet" id="sessionSet" value="iardetails" />						
									<table id="" class="table">
										<thead class="thead-light" align="center">
											<tr>
												<th class="dataField w-10" nowrap>PO No.</th>
												<th class="dataField w-25" nowrap>Product</th>
												<!-- <th class="dataField w-25" nowrap>Expiry</th> -->
												
												<th class="dataField w-10" nowrap>Unit Price</th>
												<th class="dataField w-10" nowrap>Amount</th>
												<th colspan="4"></th>
											</tr>
											<tr>
												<th>
													<select id="poID" name="poID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="PO No." onchange="get_select_items();" required>
														<option value="">&nbsp;</option>
														<?php 
														$this->db->where_in('status', array(2,3));
														$results = $this->db->get('poheaders')->result();
														foreach($results as $res){
															?>
															<option value="<?php echo $res->poID?>"><?php echo $res->poNo; ?></option>
														<?php }?>
													</select>
												</th>
												<th class="form-group form-input w-10" align="left" >
													

													<select class="form-control country" id="itemID" name="itemID" data-live-search="true" livesearchnormalize="true" title="Item"  onchange="set_default_items();" required>
														<option value="" selected>&nbsp;</option>
														
													</select>
												</th>
												<th class="form-group form-input" align="left" nowrap>			
													
													<table>
														<tr>
															<td style="width: 10%">
																<select id="month" name="month" class="form-control">
																	<?php $months = array(1=>'Jan', 'Feb', 'Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'); ?>
																	<option value="" selected>&nbsp;</option>
																	<?php foreach ($months as $i=>$mon) { ?>
																			<option value="<?php echo str_pad($i, 2, "0", STR_PAD_LEFT) ?>"><?php echo $mon ?></option>
																		
																	<?php } ?>

																</select>
															</td>
															<td style="width: 10%">
																<select class="form-control" name="year" id="year" onchange="">
																	<option value="" selected>&nbsp;</option>
																	<!-- Let's just set static for now -->
																	<?php $current_date = date('Y') + 10; ?>
																	<?php for ($i = date('Y'); $i <= $current_date; $i++) { ?>
																		<option value="<?php echo $i ?>"><?php echo $i ?></option>
																	<?php } ?>

																</select>
															</td>
														</tr>
													</table>
												
												</th>
												<th class="form-group form-input " align="center" >
													<input type="text" class="form-control " name="itemQty" id="itemQty" required title="Item Quantity" onkeypress="return isNumber(event)" onfocus="$(this).select();">
												</th>
												<th class="dataField" align="left" nowrap>			
													<input type="text" class="form-control " name="itemPrice" id="itemPrice" required title="Item Price" onkeypress="return isNumber(event)" onfocus="$(this).select();">
												</th>

												<th class="dataField" align="left" nowrap>			
													<input type="text" class="form-control " name="itemAmount" id="itemAmount" required title="Item Amount" readonly>
												</th>
												
												<th class="" align="center">
													<!-- form_source, fields, display_area, do_reset=1, returnField="", success_msg="", error_msg="", checkDuplicate=0, duplicateField="", callback="" -->
													<input type="button" class="btn btn-xs btn-primary pill btn-block" id="addItemsBtn" value=" Add " onclick="tm_add_session_item('frmAdd', 'itemID,itemUmsr,itemName,itemQty,itemPrice,itemAmount,itemDescription,itemCode', 'div_items',0,'','','',0,'itemID','');" /></th>
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
								<div id="div_items" class="table-responsive-xl">
									<table id="table_items" class="table hover">
										<thead class="thead-light" align="center">
											<tr>
												<th class="w-5"><div align="center"></div></th>
												<th class="w-10"><div align="center">Stock No.</div></th>
												<th class="w-10"><div align="center">Unit</div></th>
												<th class="w-35"><div align="left">Description</div></th>
												
												<th class="w-10"><div align="center">Quantity</div></th>
												<th class="w-10"><div align="right">Unit Price</div></th>
												<th class="w-10"><div align="right">Amount</div></th>

											</tr>
										</thead>
										<tbody  id="tbody_items">
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


<!-- E insert ang same RR No sa iarheaders table, together sa iya new poID, para multiple ang poID sa usa ka iarNo. -->
<script>

$(document).ready(function(){

	$('#provinceID').change(function() {
		get_cities();
	});

	$('#cityID').change(function() {
		get_barangays();
	});

	$('#month,#year').on('change',function(){
	
	});

	$('#itemPrice,#itemQty').keyup(function(){
		$('#itemAmount').val(parseFloat($('#itemQty').val() * $('#itemPrice').val()).toFixed(2));

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


<?php 
echo "\n";
$parameters = array();
echo $this->htmlhelper->get_json_select('get_provinces', $parameters, site_url('generic_ajax/get_provinces'), 'provinceID', '');

echo "\n";
$parameters = array('provinceID');
echo $this->htmlhelper->get_json_select('get_cities', $parameters, site_url('generic_ajax/get_cities'), 'cityID', '');

echo "\n";
$parameters = array('cityID');
echo $this->htmlhelper->get_json_select('get_barangays', $parameters, site_url('generic_ajax/get_barangays'), 'barangayID', '');

echo "\n";
$parameters = array('poID');

?>





//Reuse this functions start=============================================================
var site_url = '<?php echo site_url(); ?>';
//change start
page_type = 'create';
//change end












if (page_type == 'view') {
	tm_display_session_items('iardetails', 'div_items');
}

<?php 
//change start
$path = 'get_po_select_items';
//change end
echo $this->htmlhelper->get_json_select('get_select_items', $parameters, site_url('generic_ajax/'.$path), 'itemID', '', 'confirm_change();') ;
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

				var elementID = '#div_items';
				reset_list(elementID);
				
			} else {
				//change start
				$('#poID').selectpicker('val', $('#poID2').val());
				$('#itemID').selectpicker('render');
				$('#itemID').selectpicker('refresh');
				//change end
				get_select_items();
			}
		});
	}
}

function set_default_items()
{
	//change start
	//headers
	var poID2 = $('#poID2');
	var poDate = $('#poDate');
	var clusterFund = $('#clusterFund');
	//details
	var itemID = $('#itemID').val();
	var poID = $('#poID').val();
	//path
	var path = "iar/set_default_po_item/";
	//change end

	console.log(itemID);

	if (itemID !="" && poID !="") {
		$.post(site_url+path, { 
			itemID: itemID,
			poID: poID
		},
		function(data){ 
			console.log('set_items',data)

			poID2.val(data.header.poID);
			poDate.val(data.header.poDate);
			clusterFund.val(data.header.clusterFund);
			//change start
			var ttl = parseFloat(data.rec.qty) - parseFloat(data.rec.delQty);
			
			ttl = (ttl > 0)? ttl : 0; 
			$('#itemUmsr').val(data.rec.umsr); 
			$('#itemName').val(data.rec.name);
			$('#itemQty').val(ttl);
			$('#itemPrice').val(parseFloat(data.rec.uprice)); 
			$('#itemDescription').val(data.rec.description);
			$('#itemAmount').val(parseFloat(data.rec.amount).toFixed(2));
			// $('#itemExpiry').val(data.rec.expiry);
			set_expiry();
			$('#itemCode').val(data.rec.itemCode);
			//change end

		}, "json");
	} else {
		//change start
		$('#itemUmsr').val("");
		$('#itemName').val("");
		$('#itemQty').val("");
		$('#itemPrice').val("");
		$('#itemDescription').val("");
		$('#itemAmount').val("");
		// $('#itemExpiry').val("");
		$('#itemCode').val("");
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
function tm_add_session_item(form_source, fields, display_area, do_reset=1, returnField="", success_msg="", error_msg="", checkDuplicate=0, duplicateField="", callback="") 
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
					tm_display_session_items($('#'+form_source+' #sessionSet').val(), display_area);
				}
				if (callback != "") {
					eval(callback);
				}				
			}, "text");
		
		//change start
		$('#itemQty,#itemAmount,#itemPrice').val('');
		$('#itemID,#month,#year').selectpicker('val', '');
		$('#itemID,#month,#year').selectpicker('render');
		$('#itemID,#month,#year').selectpicker('refresh');
		//change end
	}

}

function tm_display_session_items(sessionSet, display_area) 
{  	
	$.post('<?php echo $controller_page ?>/display_session_items/'+display_area, { sessionSet: sessionSet },
		function(data){
			$('#'+display_area).html(data);
			if (page_type == 'view') {
				$('.la-trash-o.item').hide();
			}
		}, "text");
}

function tm_delete_session_item(item_name, item_id, display_area,callback="") 
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
		$.post(site_url+"/sessionmanager/delete_session_item", { sessionSet: item_name, targetID: item_id },
		  function(data){
			if (parseInt(data)==1) {
				//alert("Successfully deleted!");
			} else {
				swal("Delete failed!","Unable to delete record.","warning");
			}
			  
			if (display_area != "") {
				tm_display_session_items(item_name, display_area);
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
			$('#itemUmsr').val("");
			$('#itemName').val("");
			$('#itemQty').val("");
			$('#itemPrice').val("");
			$('#itemDescription').val("");
			$('#itemAmount').val("");
			// $('#itemExpiry').val("");
			//change end
	}, "text");

}


//template
function table_items_template() {
	var list = '';
	list += '<table id="table_items" class="table hover">';
	//change start
	var headers = ['', 'Stock No.', 'Unit', 'Description', 'Quantity', 'Unit Cost', 'Amount'];
	var widths = ['w-5', 'w-10', 'w-10', 'w-45'];
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






//Static functions
function get_po_items_view()
{
	var div_items = $('#tbody_items');
	var poID = $('#poID').val();

	console.log('sss');
	$.ajax({
		method: 'POST',
		url: '<?php echo site_url('iar/get_po_items') ?>',
		data: {poID: poID},
		dataType: 'json',
		success: function(response) {

			var count = 0;
			var list = '';

			var rec = response.rec;
			$('#clusterFund').val(rec.clusterFund);
			var options = { month: 'long', day: 'numeric', year: 'numeric' };
			var poDate = new Date(rec.poDate).toLocaleDateString("en-US", options);
			$('#poDate').val(poDate);

		  // $('#requisitioningCenterCode').val('test');
		  var items = [];
		  $.each(response.records, function(i, row){

		    // item_array.push(row.item);
		    console.log(i, row);

		    var dict = [row.itemID, row.name, row.description, row.umsr, row.qty+'_'];
		    items.push(dict);

		    list += '<tr>';
		    
		    list += '<td style="text-align: center;" class=" att-3">'+ row.itemID + '</td>';
		    list += '<td class=" att-3">'+ row.name + ' - ' + row.description + '</td>';
		    list += '<td style="text-align: center;" class=" att-3">'+row.umsr+ '</td>';
		    list += '<td style="text-align: center;" class=" att-3">'+row.qty+ '</td>';
		    list += '</tr>';
		});
		  list += '<input type="hidden" class="form-control" name="items[]" id="items" value="'+ items +'">';
		  // console.log(items)
		  div_items.html(list);


		},
		complete: function(response) {

		}, error:function(xhr) {
		},
	});
}
</script>