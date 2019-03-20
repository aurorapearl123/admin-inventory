
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
								 &nbsp;| &nbsp;
								<?php 
									if($rec->status==1){ 
										echo "<font color='blue'>Pending</font>"; 
									} else if($rec->status==2) { 
										echo "<font color='green'>Inspected</font>"; 
									} else if($rec->status==3) { 
										echo "<font color='green'>Accepted</font>"; 
									} else {
										echo "<font color='red'>Cancelled</font>"; 
									}
								?>
							</h4>
						</div>
					</div>
					<div class="card-head-tools">
						<ul class="tools-list">
							<?php $id = $this->encrypter->encode($rec->$pfield); ?>
							<!-- <?php if ($roles['edit']) {?>
							<li>
								
								<input type="button" onclick="confirm_record(2);" class="btn btn-xs btn-info btn-raised pill" value="Inspect"/>
							</li>
							<?php } ?> -->
							<?php if ($roles['inspect'] && $rec->status == 1) {?>
								<li>

									<input type="button" onclick="confirm_record(2);" class="btn btn-xs btn-success btn-raised pill" value="Confirm Inspection"/>
								</li>
							<?php } ?>
							<?php if ($roles['confirm'] && $rec->status == 2) {?>
							<li>
								
								<input type="button" onclick="confirm_record(3);" class="btn btn-xs btn-primary btn-raised pill" value="Accept"/>
							</li>
							<?php } ?>
							<?php if ($roles['cancel'] && ($rec->status == 1 || $rec->status == 2)) {?>
							<li>
								<input type="button" onclick="confirm_record(0);" class="btn btn-xs btn-danger btn-raised pill" value="Cancel"/>
							</li>
							<?php } ?>
							<li>
								<button class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="Print" onclick="popUp('<?php echo $controller_page ?>/print_iar/<?php echo $id?>', 900, 500)"><i class="la la-file-pdf-o"></i></button>
							</li>
							<?php if ($roles['edit'] && $rec->status == 1) {?>
							<li>
								<a href="<?php echo $controller_page.'/edit/'.$id ?>" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit"><i class="la la-edit"></i></a>
							</li>
							<?php } ?>
							<?php if ($roles['delete'] && $rec->status == 1) {?>
							<li>
								<button name="cmddelete" id="cmddelete" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete" onclick="deleteRecord('<?php echo $id; ?>');"><i class="la la-trash-o"></i></button>
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
						<!-- <div class="subtitle">
							<h5 class="title"><i class="icon left la la-user"></i> IAR Headers</h5>
						</div> -->
						<table class="view-table">
							<tbody>
							
							<!-- Table Rows Start -->
								<tr>
									<td class="data-title" style="width:120px" nowrap>RR No:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $rec->iarNo; ?></td>
									<td class="data-title" style="width:120px" nowrap>IAR Date:</td>
									<td class="data-input" nowrap>
										<?php echo date('M d, Y',strtotime($rec->iarDate))?>
									</td>
									<td class="data-input"></td>
								</tr>
								<tr>
									<td class="data-title" style="width:120px" nowrap>DR No:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $rec->drNo; ?></td>
									<td class="data-title" style="width:120px" nowrap>DR Date:</td>
									<td class="data-input" nowrap>
										<?php echo date('M d, Y',strtotime($rec->drDate))?>
									</td>
									<td class="data-input"></td>
								</tr>
								<tr>
									<td class="data-title" style="width:120px" nowrap>Invoice No:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $rec->invoiceNo; ?></td>
									<td class="data-title" style="width:120px" nowrap>Invoice Date:</td>
									<td class="data-input" nowrap>
										<?php echo date('M d, Y',strtotime($rec->invoiceDate))?>
									</td>
									<td class="data-input"></td>
								</tr>
								
								
								<tr>
									<td class="data-title" nowrap>Requisitioning Office:</td>
									<td class="data-input" nowrap><?php echo $rec->requisitioningOffice; ?></td>
									<td class="data-title" nowrap>Responsibility Center Code:</td>
									<td class="data-input"><?php echo $rec->responsibilityCenterCode; ?></td>
									<td class="data-input"></td>
								</tr>
								
								
								<tr>
									<td class="data-title" nowrap>Remarks:</td>
									<td class="data-input" nowrap><?php echo $rec->remarks; ?></td>
									<td class="data-title" nowrap>Delivery Type</td>
									<td class="data-input" nowrap>
									<?php echo $rec->deliveryType; ?>
									</td>
									<td class="data-input"></td>
								</tr>
								
								<!-- Table Rows End -->
								
								
							</tbody>
						</table>
					</div>


					<!-- View Details Start -->

					<div class="subtitle mt-20">
						<h5 class="title mr-auto"><i class="icon left la la-info"></i> IAR Details</h5>
						<div class="subtitle-tools">
						</div>
					</div>

					<table class="view-table">
						<tbody>
							<tr>
								<td class="data-title" style="width:120px" nowrap>PO No:</td>
								<td class="data-input" style="width:420px" nowrap><?php echo $rec->poNo; ?></td>
								<td class="data-title" style="width:120px" nowrap>PO Date:</td>
								<td class="data-input" nowrap>
									<?php echo date('M d, Y',strtotime($rec->poDate))?>
								</td>
								<td class="data-input"></td>
							</tr>
							<tr>
								<td class="data-title" nowrap>Cluster Fund:</td>
								<td class="data-input" nowrap><?php echo $rec->clusterFund; ?></td>
								<td class="data-title" nowrap></td>
								<td class="data-input" nowrap>
									
								</td>


								<td class="data-input"></td>
							</tr>
						</tbody>
					</table>




					<div class="datatables_wrapper">
						<div id="div_items" class="table-responsive">
							<table id="table_items" class="table hover">
								<thead class="thead-light" align="center">
									<tr>
										<th class="w-5"><div align="center"></div></th>
										<th class="w-10"><div align="center">Stock No.</div></th>
										<th class="w-45"><div align="left">Description</div></th>
										<th class="w-10"><div align="center">Quantity</div></th>
										<th class="w-10"><div align="center">Unit</div></th>
										<th class="w-10"><div align="right">Unit Price</div></th>
										<th class="w-10"><div align="right">Amount</div></th>

									</tr>
								</thead>
								<tbody  id="tbody_items">
									<tr>
										<td colspan="7">&nbsp;</td>

									</tr>
									<tr>
										<td colspan="7">&nbsp;</td>

									</tr>
									<tr>
										<td colspan="7">&nbsp;</td>

									</tr>
									<tr>
										<td colspan="7">&nbsp;</td>

									</tr>
									<tr>
										<td colspan="7">&nbsp;</td>

									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<!-- View Details End -->








					<div class="datatable-footer d-flex">

					</div>

					
				</div><!-- Card Body End -->
			</div>
		</div>
	</div>
</div><!-- Content End -->


<script>
	$(document).ready(function(){
		
	});
	

	function updateQty(id='') {


		swal({
			title: 'You are about to update quantity.',
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

				var val = $('#qty'+id).val();
				var iarDetailID = $('#qty'+id).attr('data-id');
				var icon = $('#icon'+id).show();
				console.log(val);
					$.post("<?php echo site_url()?>iar/update_qty", { 
						qty: val, id: iarDetailID},
						function(response){ 
							
						}, "json");
				}
			});

	}

	function confirm_record(status='') {

		var title = '';
		if (status == 3) {
			title = 'change status to accepted';
		} else if (status == 2) {
			title = 'change status to inspected';
		} else if (status == 0) {
			title = 'cancel this form';
		}
		var id = "<?php echo $rec->iarID; ?>";
		var poID = "<?php echo $rec->poID; ?>";
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

				
					$.post("<?php echo site_url()?>iar/confirm_record", { id: id, status: status, poID: poID},
						function(response){ 
							console.log('ssssss');
							
								window.location='';
							
							
						}, "json");
				}
			});

	}
	







//Reuse this functions start=============================================================
var site_url = '<?php echo site_url(); ?>';
//change start
page_type = 'view';
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
			$('#itemUmsr').val(data.rec.umsr); 
			$('#itemName').val(data.rec.itemName +' '+data.rec.description);
			$('#itemQty').val(data.rec.qty);
			$('#itemPrice').val(parseFloat(data.rec.uprice)); 
			$('#itemDescription').val(data.rec.description);
			$('#itemAmount').val(parseFloat(data.rec.amount));
			$('#itemCode').val(parseFloat(data.rec.itemCode));
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
		$('#itemID').selectpicker('val', '');
		$('#itemID').selectpicker('render');
		$('#itemID').selectpicker('refresh');
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
			//change end
	}, "text");

}


//template
function table_items_template() {
	var list = '';
	list += '<table id="table_items" class="table hover">';
	//change start
	var headers = ['', 'Stock No.', 'Description', 'Quantity', 'Unit', 'Unit Cost', 'Amount'];
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
</script>
