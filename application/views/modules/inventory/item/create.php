<div class="subheader">
	<div class="d-flex align-items-center">
		<div class="title mr-auto">
			<h3><i class="icon left la <?php echo $current_module['icon'] ?>"><?php echo strtoupper($current_module['title']) ?></i> </h3>
		</div>
		<div class="subheader-tools">
			<a href="<?php echo $controller_page?>/show" class="btn btn-primary btn-raised btn-sm pill"><i class="icon ti-angle-left"></i> Back to List</a>
		</div>
	</div>
</div>
<form method="post" name="frmEntry" id="frmEntry" action="<?php echo $controller_page ?>/save" enctype="multipart/form-data">
<div class="content">
	<div class="row">
		<div class="col-8">
			<div class="card-box" style="height:700px">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="head-text"><i class="icon left la <?php echo $current_module['icon'] ?>"></i>Add <?php echo strtoupper($current_module['title']) ?></h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<div class="table-row">
						<table class="table-form column-3" border="0">
							<tbody>
								<tr>
									<td class="form-label" width="120px" nowrap>Item Code <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">
										<input class="form-control"type="text" class="form-control" id="itemCode" name="itemCode" title="Item Code" required>
									</td>
									<td class="form-label" width="120px" nowrap>Category <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">
										<div class="row">
											<div class="col-10 pr-0">
												<select id="catID" name="catID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Category" onchange="getSubCategory()" required>
													<option value="" selected>&nbsp;</option>
													<?php 
													$this->db->where('status',1);
													$records = $this->db->get('category')->result();
													foreach($records as $rec){
													?>
													<option value="<?php echo $rec->catID?>"><?php echo $rec->category?></option>
													<?php }?>
												</select>
											</div>
											<div class="col-2 pl-0">
												<div class="tooltip2">
													<button type="button" class="button-add" id="addcategory"><span>+ </span></button>
												  <span class="tooltiptext">Add Category</span>
												</div>
											</div>
										</div>
									</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="form-label" width="120px" nowrap>Item Name <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">
										<input class="form-control"type="text" class="form-control" id="name" name="name" title="Item Name" required>
									</td>
									<td class="form-label" width="120px" nowrap>Sub Category</td>
									<td class="form-group form-input" width="400px">
										<div class="row">
											<div class="col-10 pr-0">
												<select id="subcatID" name="subcatID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Sub Category" required>
													<option value="" selected>&nbsp;</option>
												</select>
											</div>
											<div class="col-2 pl-0">
												<div class="tooltip2">
													<button type="button" class="button-add" id="addsubcategory"><span>+ </span></button>
												  <span class="tooltiptext">Add Sub Category</span>
												</div>
											</div>
										</div>
									</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="form-label" nowrap>Description <span class="asterisk">*</span></td>
									<td class="form-group form-input">
										<input class="form-control"type="text" class="form-control" id="description" name="description" title="Item Description" required>
									</td>
									<td class="form-label" width="120px" nowrap>Brand</td>
									<td class="form-group form-input">
										<div class="row">
											<div class="col-10 pr-0">
												<select id="brandID" name="brandID" class="form-control" data-live-search="true" liveSearchNormalize="true">
													<option value="" selected>&nbsp;</option>
													<?php 
													$this->db->where('status',1);
													$records = $this->db->get('brands')->result();
													foreach($records as $rec){
													?>
													<option value="<?php echo $rec->brandID?>"><?php echo $rec->brand?></option>
													<?php }?>
												</select>
											</div>
											<div class="col-2 pl-0">
												<div class="tooltip2">
													<button type="button" class="button-add" id="addbrand"><span>+ </span></button>
												  <span class="tooltiptext">Add Brand</span>
												</div>
											</div>
										</div>
									</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="form-label" nowrap>UMSR</td>
									<td class="form-group form-input">
										<div class="row">
											<div class="col-10 pr-0">
												<select id="umsr" name="umsr" class="form-control" data-live-search="true" liveSearchNormalize="true">
													<option value="">&nbsp;</option>
													<?php 
													$this->db->where('status',1);
													$records = $this->db->get('unit_measurements')->result();
													foreach($records as $rec){
													?>
													<option value="<?php echo $rec->umsr?>"><?php echo $rec->umsr?></option>
													<?php }?>
												</select>
											</div>
											<div class="col-2 pl-0">
												<div class="tooltip2">
													<button type="button" class="button-add" id="addumsr"><span>+ </span></button>
												  <span class="tooltiptext">Add Unit of Measurement</span>
												</div>
											</div>
										</div>
									</td>
									<td class="form-label" nowrap>Barcode</td>
									<td class="form-group form-input">
										<input class="form-control"type="text" class="form-control" id="barcode" name="barcode">
									</td>
									<td>&nbsp;</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card-head" style="border-top:1px solid #e9eae9">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="form-label"><i class="icon left la <?php echo $current_module['icon'] ?>"></i>ITEM DETAILS</h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<div class="table-row">
						<table class="table-form column-3">
						    <tr>   
						        <td class="form-label" nowrap width="110">Inventory</td>
						        <td class="form-group form-input" nowrap>
						        <table width="100%">
							        	<tr>
							        		<td width="5%"><input name="inventoryType" id="inventoryType" type="radio" value="1" checked></td>
								        	<td width="20%">Inventory</td>
								        	<td width="5%"><input name="inventoryType" id="inventoryType" type="radio" value="0"></td>
								        	<td width="70%" nowrap>Non-Inventory</td>
							        	</tr>
						       </table>
						       </td>
						    </tr>
						    <tr>   
						        <td class="form-label" nowrap>Dangerous Drug</td>
						        <td class="form-group form-input" nowrap>
						        <table>
							        	<tr>
							        		<td width="25"><input name="dangerousDrug" id="dangerousDrug" type="checkbox" value="1" ></td>
								        	<td width="25" style="color:red; font-size:10px">Check the box if this item is dangerous drug</td>
							        	</tr>
						       </table>
						       </td>
						    </tr>
						    <tr>   
						        <td class="form-label" nowrap>Vat Type</td>
						        <td class="form-group form-input" nowrap>
						        <table width="100%">
							        <tr>
						        		<td width="5%"><input name="vatType" id="vatType" type="radio" value="1" checked></td>
							        	<td width="20%">Vatable&nbsp;&nbsp;&nbsp;</td>
							        	<td width="5%"><input name="vatType" id="vatType" type="radio" value="0"></td>
							        	<td width="20%">Non-Vatable&nbsp;&nbsp;&nbsp;</td>
					      				<td width="5%"><input name="vatType" id="vatType" type="radio" value="2" ></td>
							        	<td width="20%"align="center" nowrap>Zero-Rated&nbsp;&nbsp;&nbsp;</td>
							        	<td width="5%"><input name="vatType" id="vatType" type="radio" value="3" ></td>
							        	<td width="20%"align="center" nowrap>Exempted&nbsp;&nbsp;&nbsp;</td>
						       		</tr>
						       	</table>
						       </td>
						    </tr>
						    <tr>   
						        <td class="form-label" nowrap>Discountable</td>
						        <td class="form-group form-input" nowrap>
						        <table width="100%">
							        <tr>
						        		<td width="5%"><input name="discountable" id="discountable" type="radio" value="1" checked></td>
							        	<td width="20%">Yes&nbsp;&nbsp;&nbsp;</td>
					      				<td width="5%"><input name="discountable" id="discountable" type="radio" value="0"></td>
							        	<td width="70%" nowrap>No&nbsp;&nbsp;&nbsp;</td>
						       		</tr>
						       	</table>
						       </td>
						    </tr>
						    <tr>   
						        <td class="form-label" nowrap>Markup-Type</td>
						        <td class="form-group form-input" nowrap>
						        <table width="100%">
							        <tr>
						        		<td width="5%"><input name="markupType" id="markupType" type="radio" value="Percentage" ></td>
							        	<td width="20%">Percentage&nbsp;&nbsp;&nbsp;</td>
					      				<td width="5%"><input name="markupType" id="markupType" type="radio" value="Peso" checked></td>
							        	<td width="70%" nowrap>Peso&nbsp;&nbsp;&nbsp;</td>
						       		</tr>
						       	</table>
						       </td>
						    </tr>
						    <tr>   
						        <td class="form-label" nowrap>Markup Value</td>
						        <td class="form-group form-input" nowrap>
						        <table>
							        <tr>
						        		<td width="200px">
						        			<input class="form-control" type="text" name="markup" id="markup" width="200" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();"/>
						        		</td>
						       		</tr>
						       	</table>
						       </td>
						    </tr>
						</table>
					</div>
					<div class="form-sepator solid"></div>
					<div class="form-group mb-0">
						<button class="btn btn-primary btn-raised pill" type="button" name="cmdSave" id="cmdSave">
						Save
						</button>
						<input type="button" id="cmdCancel" class="btn btn-outline-danger btn-raised pill" value="Cancel"/>
					</div>
				</div>
			</div>
		</div>
		<div class="col-4">
			<div class="card-box" style="height:700px">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="form-label"><i class="icon left la <?php echo $current_module['icon'] ?>"></i> Costing</h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<div class="table-row">
						<table class="table-form column-3">
							<tr>
								<td valign="top" width="30%">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
									    <tr>
									        <td class="form-label" valign="top" nowrap>Last Cost <span class="asterisk">*</span></td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="lastcost" id="lastcost" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Current Cost" required/></td>
									    </tr>
									    <tr>
									        <td class="form-label" valign="top" nowrap width="100">Lowest Cost <span class="asterisk">*</span></td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="lowestcost" id="lowestcost" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Previous Cost" required/></td>
									    </tr>
									    <tr>   
									        <td class="form-label" valign="top" nowrap>Highest Cost <span class="asterisk">*</span></td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="highcost" id="highcost" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Highest Cost" required/></td>
									    </tr>
									    <tr>   
									        <td class="form-label" valign="top" nowrap>Average Cost <span class="asterisk">*</span></td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="avecost" id="avecost" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Average Cost" required/></td>
									    </tr>
									    <tr>
									        <td class="form-label" valign="top" nowrap width="100">Lowest Price <span class="asterisk">*</span></td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="lowestprice" id="lowestprice" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Previous Cost" required/></td>
									    </tr>
									    <tr>   
									        <td class="form-label" valign="top" nowrap>Highest Price <span class="asterisk">*</span></td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="highprice" id="highprice" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Highest Cost" required/></td>
									    </tr>
									    <tr>   
									        <td class="form-label" valign="top" nowrap>Average Price <span class="asterisk">*</span></td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="aveprice" id="aveprice" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Average Cost" required/></td>
									    </tr>
									    <tr>
										     <td class="form-label" valign="top" nowrap>MDRP <span class="asterisk">*</span></td>
										     <td class="form-group form-input"><input class="form-control" type="text" name="mdrPrice" id="mdrPrice" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="MDRP" required/></td>
										</tr>
								    </table>
							    </td>
							</tr>
						</table>
					</div>
				</div>
				<div class="card-head" style="border-top:1px solid #e9eae9">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="form-label"><i class="icon left la <?php echo $current_module['icon'] ?>"></i> REORDER LEVELS</h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<div class="table-row">
						<table class="table-form column-3">
							<tr>
								<td valign="top" width="30%">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
									    <tr>
										     <td class="form-label" valign="top" nowrap>Reorder Level <span class="asterisk">*</span></td>
										     <td class="form-group form-input"><input class="form-control" type="text" name="reorderLvl" id="reorderLvl" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Reorder Level" required/></td>
										</tr>
									    <tr>
										     <td class="form-label" valign="top" nowrap>Critical Level <span class="asterisk">*</span></td>
										     <td class="form-group form-input"><input class="form-control" type="text" name="criticalLvl" id="criticalLvl" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Critical Level" required/></td>
										</tr>
										<tr>
										     <td class="form-label" valign="top" nowrap>&nbsp;</td>
										     <td class="form-group form-input">&nbsp;</td>
										</tr>
									    <tr>
										     <td class="form-label" valign="top" nowrap>Lead Time <span class="asterisk">*</span></td>
										     <td class="form-group form-input">
										     	<input class="form-control" type="text" name="leadtime" id="leadtime" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Critical Level" required/>
										     	<i style="font-size:10px">Order to delivery in days</i>
										     </td>
										</tr>
								    </table>
							    </td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>
<div class="modal fade" id="addcategoryfrm" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        	<form method="post" name="frmcategory" id="frmCategory" action="<?php echo $controller_page ?>/save_popup" enctype="multipart/form-data">
	            <div class="modal-header">
	              <h4 class="modal-title">Add Category</h4>
	            </div>
	            <div class="modal-body">
	              <div class="table-row">
	                <table class="table-form">
	                  <tbody>
	                    <tr>
	                      <td class="form-label" style="width:130px">Category <span class="asterisk">*</span></td>
	                      <td class="form-group form-input">
	                        <input type="text" class="form-control" name="category" id="category" title="Category" required>
	                      </td>
	                    </tr>
	                    <tr>
	                      <td class="form-label">Descritpion <span class="asterisk">*</span></td>
	                      <td class="form-group form-input">
	                        <input type="text" class="form-control" name="description1" id="description1" title="Description" required>
	                      </td>
	                    </tr>
	                  </tbody>
	                </table>
	              </div>
	            </div>
	            <div class="modal-footer">
	              <button type="button" id="saveCategory" class="btn btn-primary btn-raised pill">Save</button>
	              <button type="button" class="btn btn-outline-danger btn-raised pill" data-dismiss="modal">Close</button>
	            </div>
            </form>
        </div>
      </div>
</div>
<div class="modal fade" id="addsubcategoryfrm" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        	<form method="post" name="frmsubcategory" id="frmsubCategory" action="<?php echo $controller_page ?>/save_popup" enctype="multipart/form-data">
	            <input type="hidden" name="areaID" id="areaID" value="<?php echo $rec->areaID?>" />
	            <div class="modal-header">
	              <h4 class="modal-title">Add Sub Category</h4>
	            </div>
	            <div class="modal-body">
	              <div class="table-row">
	                <table class="table-form">
	                  <tbody>
	                    <tr>
	                      <td class="form-label" style="width:140px">Category <span class="asterisk">*</span></td>
	                      <td class="form-group form-input">
	                        <select id="catID1" name="catID1" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Category" onchange="getSubCategory()" required>
								<option value="" selected>&nbsp;</option>
								<?php 
								$this->db->where('status',1);
								$records = $this->db->get('category')->result();
								foreach($records as $rec){
								?>
								<option value="<?php echo $rec->catID?>"><?php echo $rec->category?></option>
								<?php }?>
							</select>
	                      </td>
	                    </tr>
	                    <tr>
	                      <td class="form-label">Sub Category <span class="asterisk">*</span></td>
	                      <td class="form-group form-input">
	                        <input type="text" class="form-control" name="subcatdesc" id="subcatdesc" title="Sub Category" required>
	                      </td>
	                    </tr>
	                  </tbody>
	                </table>
	              </div>
	            </div>
	            <div class="modal-footer">
	              <button type="button" id="saveSubCategory" class="btn btn-primary btn-raised pill">Save</button>
	              <button type="button" class="btn btn-outline-danger btn-raised pill" data-dismiss="modal">Close</button>
	            </div>
          	</form>
        </div>
      </div>
</div>
<div class="modal fade" id="addumsrfrm" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        	<form method="post" name="frmumsr" id="frmumsr" action="<?php echo $controller_page ?>/save_popup" enctype="multipart/form-data">
	            <div class="modal-header">
	              <h4 class="modal-title">Add Measurement</h4>
	            </div>
	            <div class="modal-body">
	              <div class="table-row">
	                <table class="table-form">
	                  <tbody>
	                    <tr>
	                      <td class="form-label" style="width:140px">Measurement <span class="asterisk">*</span></td>
	                      <td class="form-group form-input">
	                        <input type="text" class="form-control" name="umsr1" id="umsr1" title="Unit of Measurement" required>
	                      </td>
	                    </tr>
	                  </tbody>
	                </table>
	              </div>
	            </div>
	            <div class="modal-footer">
	              <button type="button" id="saveMeasurement" class="btn btn-primary btn-raised pill">Save</button>
	              <button type="button" class="btn btn-outline-danger btn-raised pill" data-dismiss="modal">Close</button>
	            </div>
            </form>
        </div>
      </div>
</div>
<div class="modal fade" id="addbrandfrm" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        	<form method="post" name="frmbrand" id="frmbrand" action="<?php echo $controller_page ?>/save_popup" enctype="multipart/form-data">
	            <div class="modal-header">
	              <h4 class="modal-title">Add Brand</h4>
	            </div>
	            <div class="modal-body">
	              <div class="table-row">
	                <table class="table-form">
	                  <tbody>
	                    <tr>
	                      <td class="form-label" style="width:130px">Brand<span class="asterisk">*</span></td>
	                      <td class="form-group form-input">
	                        <input type="text" class="form-control" name="brand" id="brand" title="Brand" required>
	                      </td>
	                    </tr>
	                    <tr>
	                      <td class="form-label">Description <span class="asterisk">*</span></td>
	                      <td class="form-group form-input">
	                        <input type="text" class="form-control" name="description2" id="description2" title="Description" required>
	                      </td>
	                    </tr>
	                  </tbody>
	                </table>
	              </div>
	            </div>
	            <div class="modal-footer">
	              <button type="button" id="saveBrand" class="btn btn-primary btn-raised pill">Save</button>
	              <button type="button" class="btn btn-outline-danger btn-raised pill" data-dismiss="modal">Close</button>
	            </div>
            </form>
        </div>
      </div>
</div>
<script>
$('#addumsr').click(function(){
	$('#addumsrfrm').modal('show');
});
$('#addcategory').click(function(){
	$('#addcategoryfrm').modal('show');
});
$('#addsubcategory').click(function(){
	$('#addsubcategoryfrm').modal('show');
});
$('#addbrand').click(function(){
	$('#addbrandfrm').modal('show');
});


var site_url = '<?php echo site_url(); ?>';
<?php
echo "\n";
$parameters = array('catID');
echo $this->htmlhelper->get_json_select('getSubCategory', $parameters, site_url('item/get_sub_cat'), 'subcatID', '');
?>

	$('#cmdSave').click(function(){
		if (check_fields()) {
	    	$('#cmdSave').attr('disabled','disabled');
	    	$('#cmdSave').addClass('loader');
	        $.post("<?php echo $controller_page ?>/check_duplicate", { name: $('#name').val() },
	          function(data){
	            if (parseInt(data)) {
	            	$('#cmdSave').removeClass("loader");
	            	$('#cmdSave').removeAttr('disabled');
	              	// duplicate
	              	swal("Duplicate","Record is already exist!","warning");
	            } else {
	            	// submit
	               	$('#frmEntry').submit();
	            }
	          }, "text");
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
		    	  window.location = '<?php echo site_url('item/show') ?>';
		      }
		    });
	});

	JsBarcode("#barcode", "Hello World!");
</script>
<script>
$('#saveMeasurement').on('click', function()
{
    var umsr = $('#umsr1').val();
	   if(umsr == "") {
		   swal("Warning","Please input umsr!","warning");
	   } else {
        var url = "<?php echo $controller_page?>";
        $.ajax({
            url: url+"/save_popup_umsr",
            dataType: "json",
            type: "POST",
            data: { umsr: umsr},
            success: function(data) {
                if(data.error) {
                     setTimeout(function(){
                        swal("Warning","Record already exist!","warning");
                    }, 0);
                } else {
                        var error = data.message;
                        var id = data.id;

                        $('#addumsrfrm').modal('hide');
                        var x = document.getElementById("umsr");
                        var option = document.createElement("option");
                        option.text = umsr;
                        option.value = id;
                        x.add(option);
                        
                        $('#umsr').selectpicker('val', id);
                        $('#umsr').selectpicker('render');
                        $('#umsr').selectpicker('refresh');
                        $('#umsr1').val('');

                }

            }
        });
	   }
 });
 
$('#saveBrand').on('click', function()
{
    var brand = $('#brand').val();
    var description = $('#description2').val();
	   if(description == "") {
		   swal("Warning","Please input Description!","warning");
	   }
	   if(brand == "") {
		   swal("Warning","Please input Brand!","warning");
	   } else {
        var url = "<?php echo $controller_page?>";
        $.ajax({
            url: url+"/save_popup_brand",
            dataType: "json",
            type: "POST",
            data: { brand: brand, description: description},
            success: function(data) {
                if(data.error) {
                     setTimeout(function(){
                    	 swal("Warning","Record already exist!","warning");
                    }, 0);
                } else {
                        var error = data.message;
                        var id = data.id;

                        $('#addbrandfrm').modal('hide');
                        var x = document.getElementById("brandID");
                        var option = document.createElement("option");
                        option.text = brand;
                        option.value = id;
                        x.add(option);
                        
                        $('#brandID').selectpicker('val', id);
                        $('#brandID').selectpicker('render');
                        $('#brandID').selectpicker('refresh');

                        $('#brand').val('');
                        $('#description2').val('');
                }

            }
        });
	   }
 });
 
$('#saveCategory').on('click', function()
{
    var category = $('#category').val();
    var description = $('#description1').val();
	   if(description == "") {
		   swal("Warning","Please input Description!","warning");
	   }
	   if(category == "") {
		   swal("Warning","Please input Category!","warning");
	   } else {
        var url = "<?php echo $controller_page?>";
        $.ajax({
            url: url+"/save_popup_category",
            dataType: "json",
            type: "POST",
            data: { category: category, description: description},
            success: function(data) {
                if(data.error) {
                     setTimeout(function(){
                    	 swal("Warning","Record already exist!","warning");
                    }, 0);
                } else {
                        var error = data.message;
                        var id = data.id;

                        $('#addcategoryfrm').modal('hide');
                        var x = document.getElementById("catID");
                        var option = document.createElement("option");
                        option.text = category;
                        option.value = id;
                        x.add(option);
                        
                        $('#catID').selectpicker('val', id);
                        $('#catID').selectpicker('render');
                        $('#catID').selectpicker('refresh');

                        $('#category').val('');
                        $('#description1').val('');
                }

            }
        });
	   }
 });
 
$('#saveSubCategory').on('click', function()
{
    var subcatdesc = $('#subcatdesc').val();
    var catID = $('#catID1').val();
	   if(catID == "") {
		   swal("Warning","Please select category!","warning");
	   }
	   if(subcatdesc == "") {
		   swal("Warning","Please input Sub Category!","warning");
	   } else {
        var url = "<?php echo $controller_page?>";
        $.ajax({
            url: url+"/save_popup_subcategory",
            dataType: "json",
            type: "POST",
            data: { subcatdesc: subcatdesc, catID: catID},
            success: function(data) {
                if(data.error) {
                     setTimeout(function(){
                    	 swal("Warning","Record already exist!","warning");
                    }, 0);
                } else {
                        var error = data.message;
                        var id = data.id;

                        $('#addsubcategoryfrm').modal('hide');
                        var x = document.getElementById("subcatID");
                        var option = document.createElement("option");
                        option.text = subcatdesc;
                        option.value = id;
                        x.add(option);
                        
                        $('#subcatID').selectpicker('val', id);
                        $('#subcatID').selectpicker('render');
                        $('#subcatID').selectpicker('refresh');

                        $('#catID1').val('');
                        $('#subcatdesc').val('');
                }

            }
        });
	   }
 });
</script>