<div class="subheader">
	<div class="d-flex align-items-center">
		<div class="title mr-auto">
			<h3><i class="icon left la <?php echo $current_module['icon'] ?>"></i> <?php echo $current_module['title'] ?></h3>
		</div>
		<div class="subheader-tools">
			<a href="<?php echo $controller_page ?>/view/<?php echo $this->encrypter->encode($rec->$pfield)?>" class="btn btn-primary btn-raised btn-sm pill"><i class="icon ti-angle-left"></i> Back to View</a>
		</div>
	</div>
</div>
<form method="post" name="frmEntry" id="frmEntry" action="<?php echo $controller_page ?>/update">
<input type="hidden" name="<?php echo $pfield?>" id="<?php echo $pfield?>" value="<?php echo $this->encrypter->encode($rec->$pfield) ?>" />
<div class="content">
	<div class="row">
		<div class="col-8">
			<div class="card-box" style="height:600px">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="head-text"><i class="icon left la <?php echo $current_module['icon'] ?>"></i>EDIT <?php echo strtoupper($current_module['title']) ?></h4> 
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<div class="table-row">
						<table class="table-form column-3">
							<tbody>
								<tr>
									<td class="form-label" width="120px" nowrap>Product Code <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">
										<input class="form-control"type="text" class="form-control" id="productCode" name="productCode" value="<?php echo $rec->productCode?>" title="Product Code" required>
									</td>
									<td class="form-label" width="120px" nowrap>Category <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">
										<select id="catID" name="catID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Category" required>
											<option value="" selected>&nbsp;</option>
											<?php 
											$this->db->where('status',1);
											$records = $this->db->get('category')->result();
											foreach($records as $row){
											?>
											<option value="<?php echo $row->catID?>" <?php if($row->catID == $rec->catID){echo "selected";}?>><?php echo $row->category?></option>
											<?php }?>
										</select>
									</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="form-label" width="120px" nowrap>Product Name <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">
										<input class="form-control"type="text" class="form-control" id="name" name="name" title="Product Name" value="<?php echo $rec->name?>" required>
									</td>

									<td class="form-label" nowrap>Description <span class="asterisk">*</span></td>
									<td class="form-group form-input">
										<input class="form-control"type="text" class="form-control" id="description" name="description" value="<?php echo $rec->description?>" title="Item Description" required>
									</td>									
									<td>&nbsp;</td>
								</tr>								
								<tr>
									<td class="form-label" nowrap>UMSR</td>
									<td class="form-group form-input">
										<select id="umsr" name="umsr" class="form-control" data-live-search="true" liveSearchNormalize="true">
											<option value="">&nbsp;</option>
											<?php 
											$this->db->where('status',1);
											$records = $this->db->get('unit_measurements')->result();
											foreach($records as $row){
											?>
											<option value="<?php echo $row->umsr?>" <?php if($row->umsr == $rec->umsr){echo "selected";}?>><?php echo $row->umsr?></option>
											<?php }?>
										</select>
									</td>
									<td class="form-label" nowrap>Status</td>
									<td class="form-group form-input">
										<select id="status" name="status" class="form-control">
											<option value="">&nbsp;</option>
											<option value="1" <?php if($rec->status == 1){echo "selected";}?>>Active</option>
											<option value="0" <?php if($rec->status == 0){echo "selected";}?>>Inactive</option>
										</select>
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
						        <table>
							        <tr>
						        		<td width="10"><input name="vatType" id="vatType" type="radio" value="1" <?php if($rec->vatType == 1){echo "checked";}?>></td>
							        	<td width="25">Vatable&nbsp;&nbsp;&nbsp;</td>
							        	<td width="10"><input name="vatType" id="vatType" type="radio" value="0" <?php if($rec->vatType == 0){echo "checked";}?>></td>
							        	<td width="25">Non-Vatable&nbsp;&nbsp;&nbsp;</td>
					      			
						       		</tr>
						       	</table>
						       </td>
						    </tr>
						  
						    <tr>   
						        <td class="form-label" nowrap>Markup-Type</td>
						        <td class="form-group form-input" nowrap>
						        <table>
							        <tr>
						        		<td width="25"><input name="markupType" id="markupType" type="radio" value="Percentage" <?php if($rec->markupType == "Percentage"){echo "checked";}?>></td>
							        	<td width="25">Percentage&nbsp;&nbsp;&nbsp;</td>
					      				<td width="25"><input name="markupType" id="markupType" type="radio" value="Peso" <?php if($rec->markupType == "Peso"){echo "checked";}?>></td>
							        	<td width="25"align="center" nowrap>Peso&nbsp;&nbsp;&nbsp;</td>
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
						        			<input class="form-control" type="text" name="markup" id="markup" width="200" value="<?php echo number_format($rec->markup,'2','.','')?>" onkeypress="return isNumber(event)" onfocus="$(this).select();"/>
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
									        <td class="form-group form-input"><input class="form-control" type="text" name="lastcost" id="lastcost" width="100"  value="<?php echo number_format($rec->lastcost,'2','.','')?>" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Current Cost" /></td>
									    </tr>
									    <tr>
									        <td class="form-label" valign="top" nowrap width="100">Lowest Cost </td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="lowestcost" id="lowestcost" width="100" value="<?php echo number_format($rec->lowestcost,'2','.','')?>" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Previous Cost" /></td>
									    </tr>
									    <tr>   
									        <td class="form-label" valign="top" nowrap>Highest Cost </td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="highcost" id="highcost" width="100" value="<?php echo number_format($rec->highcost,'2','.','')?>" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Highest Cost" /></td>
									    </tr>
									    <tr>   
									        <td class="form-label" valign="top" nowrap>Average Cost </td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="avecost" id="avecost" width="100" value="<?php echo number_format($rec->avecost,'2','.','')?>" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Average Cost" /></td>
									    </tr>
									    <tr>
									        <td class="form-label" valign="top" nowrap width="100">Lowest Price </td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="lowestprice" id="lowestprice" width="100" value="<?php echo number_format($rec->lowestprice,'2','.','')?>" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Previous Cost" /></td>
									    </tr>
									    <tr>   
									        <td class="form-label" valign="top" nowrap>Highest Price </td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="highprice" id="highprice" width="100" value="<?php echo number_format($rec->highprice,'2','.','')?>" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Highest Cost" /></td>
									    </tr>
									    <tr>   
									        <td class="form-label" valign="top" nowrap>Average Price</td>
									        <td class="form-group form-input"><input class="form-control" type="text" name="aveprice" id="aveprice" width="100" value="<?php echo number_format($rec->aveprice,'2','.','')?>" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Average Cost" /></td>
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
										     <td class="form-group form-input"><input class="form-control" type="text" name="reorderLvl" id="reorderLvl" width="100" value="<?php echo number_format($rec->reorderLvl,'2','.','')?>" onkeypress="return isNumber(event)" onfocus="$(this).select();" title="Reorder Level" /></td>
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
	
	    $('#frmEntry').submit();
	  }
	});
	
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
	   	  window.location = '<?php echo $controller_page ?>/view/<?php echo $this->encrypter->encode($rec->$pfield)?>';
	     }
	   });
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