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
			<div class="card-box" style="height:600px">
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
									<td class="form-label" width="120px" nowrap>Product Code <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">
										<input class="form-control"type="text" class="form-control" id="productCode" name="productCode" title="Product Code" required>
									</td>
									<td class="form-label" width="120px" nowrap>Category <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">
										<div class="row">
											<div class="col-10 pr-0">
												<select id="catID" name="catID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Category"  required>
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
									<td class="form-label" width="120px" nowrap>Product Name <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">
										<input class="form-control"type="text" class="form-control" id="name" name="name" title="Product Name" required>
									</td>

									<td class="form-label" nowrap>Description <span class="asterisk">*</span></td>
									<td class="form-group form-input">
										<input class="form-control"type="text" class="form-control" id="description" name="description" title="Description" required>
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
									<td>&nbsp;</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card-head" style="border-top:1px solid #e9eae9">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="head-text"><i class="icon left la <?php echo $current_module['icon'] ?>"></i>PRODUCT DETAILS</h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<div class="table-row">
						<table class="table-form column-3">
						  
						    <tr>   
						        <td class="form-label" nowrap>Vat Type</td>
						        <td class="form-group form-input" nowrap>
						        <table width="100%">
							        <tr>
						        		<td width="5%"><input name="vatType" id="vatType" type="radio" value="1" checked></td>
							        	<td width="20%">Vatable&nbsp;&nbsp;&nbsp;</td>
							        	<td width="5%"><input name="vatType" id="vatType" type="radio" value="0"></td>
							        	<td width="70%">Non-Vatable&nbsp;&nbsp;&nbsp;</td>
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
			<div class="card-box" style="height:600px">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="head-text"><i class="icon left la <?php echo $current_module['icon'] ?>"></i> Costing</h4>
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
									        <td class="form-label" valign="top" nowrap>Last Cost </td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="lastcost" id="lastcost" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Current Cost" /></td>
									    </tr>
									    <tr>
									        <td class="form-label" valign="top" nowrap width="100">Lowest Cost </td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="lowestcost" id="lowestcost" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Previous Cost" /></td>
									    </tr>
									    <tr>   
									        <td class="form-label" valign="top" nowrap>Highest Cost </td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="highcost" id="highcost" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Highest Cost" /></td>
									    </tr>
									    <tr>   
									        <td class="form-label" valign="top" nowrap>Average Cost </td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="avecost" id="avecost" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Average Cost" /></td>
									    </tr>
									    <tr>
									        <td class="form-label" valign="top" nowrap width="100">Lowest Price </td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="lowestprice" id="lowestprice" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Previous Cost" /></td>
									    </tr>
									    <tr>   
									        <td class="form-label" valign="top" nowrap>Highest Price </td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="highprice" id="highprice" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Highest Cost" /></td>
									    </tr>
									    <tr>   
									        <td class="form-label" valign="top" nowrap>Average Price</td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="aveprice" id="aveprice" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Average Cost" /></td>
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
							<h4 class="head-text"><i class="icon left la <?php echo $current_module['icon'] ?>"></i> REORDER LEVELS</h4>
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
										     <td class="form-label" valign="top" nowrap>Reorder Level </td>
										     <td class="form-group form-input"><input class="form-control" type="text" name="reorderLvl" id="reorderLvl" width="100" value="0" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Reorder Level" /></td>
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
 
	$('#cmdSave').click(function(){
		if (check_fields()) {
	    	$('#cmdSave').attr('disabled','disabled');
	    	$('#cmdSave').addClass('loader');
	    	$.post("<?php echo $controller_page ?>/check_duplicate", { productCode : $('#productCode').val(), name : $('#name').val() },
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
		});
		 
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
		    	  	window.location = '<?php echo site_url('product/show') ?>';
		      	}
		    });
	});
	
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
 

</script>