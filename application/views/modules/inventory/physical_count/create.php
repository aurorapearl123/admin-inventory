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
		<div class="col-12">
			<div class="card-box">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="head-text"><i class="icon left la <?php echo $current_module['icon'] ?>"></i>Add <?php echo strtoupper($current_module['title']) ?></h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body" id="main-div">
					<div class="table-row">
						<table class="table-form column-3">
							<tr>
								<td class="form-label" width="100px" nowrap>Section</td>
						        <td class="form-group form-input" width="300px">
						        	<select id="ancillaryID" name="ancillaryID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Ancillary" required>
										<option value="" selected>&nbsp;</option>
										<?php 
										$this->db->where('status',1);
										$this->db->order_by('division','asc');
										$records = $this->db->get('ancillaries')->result();
										foreach($records as $rec){
										?>
										<option value="<?php echo $rec->ancillaryID?>" <?php if ($ancillaryID == $rec->ancillaryID){ echo "selected"; }?>><?php echo $rec->division?></option>
										<?php }?>
									</select>
								 </td>
								<td class="form-label" width="100px" nowrap>Category</td>
						        <td class="form-group form-input" width="300px">
						        	<select id="catID" name="catID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Category">
										<option value="" selected>&nbsp;</option>
										<?php 
										$this->db->where('status', 1);
							    		$this->db->order_by('category','asc');
							    		$records = $this->db->get('category')->result();
										foreach($records as $rec){
										?>
										<option value="<?php echo $rec->catID?>" <?php if ($catID == $rec->catID){ echo "selected"; }?>><?php echo $rec->category?></option>
										<?php }?>
									</select>
								</td>
								<td class="form-group form-input" width="100px">
									<button class="btn btn-primary btn-raised pill btn-xs" style="width:100px" type="button" name="cmdAdd" id="cmdAdd" onclick="getItemsBySection();">Filter</button>
								</td>
								<td class="form-group form-input" width="200px"></td>
						    </tr>
						    <tr>
						    	<td class="form-label" nowrap>Date</td>
						        <td class="form-group form-input" width="300px">
						        	<input type="text" class="form-control datepicker" id="pcDate" name="pcDate" data-toggle="datetimepicker" data-target="#pcDate" value="<?php echo date('M d, Y');?>" title="Date" required>
								 </td>
								 <td class="form-label" nowrap>Remarks</td>
						        <td class="form-group form-input" width="300px">
						        	<input type="text" class="form-control" name="remarks" id="remarks" value="" title="Remarks"/>
								</td>
								<td class="form-label" nowrap>Performed By</td>
								<td class="form-group form-input" width="200px">
									<input type="text" class="form-control" style="width: 200px;" name="performedBy" id="performedBy" value="" title="Performed By" required/>
								</td>
						    </tr>
						</table>
						<div class="card-head" style="border-top:1px solid #e9eae9">
							<div class="head-caption">
								<div class="head-title">
									<h4 class="form-label"><i class="icon left la <?php echo $current_module['icon'] ?>"></i>ITEM LIST</h4>
								</div>
							</div>
							<div class="card-head-tools"></div>
						</div>
						<table align="left" class="table table-striped hover">
							<thead>
								<tr>
									<th>&nbsp;</th>
									<?php if (!$catID) { ?>
									  <th>Category</th>
									<?php } ?>	
									<th>Item Name</th>
									<th>Description</th>
									<th>Expiry</th>
									<th>Qty</th>
									<th>Qty on Hand</th>
									<th>Variance</td>
									<th>Unit Cost</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$ctr = 1;
							if ($ancillaryID) {
								foreach($items as $row) {
									$this->db->select('xstockcards.xstockcardID');
									$this->db->select('xstockcards.ancillaryID');
									$this->db->select('xstockcards.itemID');
									$this->db->select('xstockcards.expiry');
									$this->db->select('xstockcards.endBal');
									$this->db->select('xstockcards.price');
									$this->db->select('items.name as itemName');
									$this->db->select('items.description as itemDescription');
									$this->db->select('items.umsr as itemUmsr');
									$this->db->from('xstockcards');	
									$this->db->join('items', 'xstockcards.itemID=items.itemID', 'left');	
									$this->db->where('xstockcards.ancillaryID', $ancillaryID);
									$this->db->where('xstockcards.itemID', $row->itemID);
									$this->db->where('xstockcards.endBal >', 0);
									$this->db->order_by('items.name');
									$this->db->group_by('xstockcards.expiry');
									$xscrecords = $this->db->get()->result();
									if($xscrecords) {
										foreach ($xscrecords as $xsc) { $ctr++;
											$this->db->select('xstockcards.xstockcardID');
											$this->db->select('xstockcards.ancillaryID');
											$this->db->select('xstockcards.itemID');
											$this->db->select('xstockcards.expiry');
											$this->db->select('xstockcards.endBal');
											$this->db->select('xstockcards.price');
											$this->db->select('items.name');
											$this->db->select('items.description');
											$this->db->select('items.umsr');
											$this->db->from('xstockcards');	
											$this->db->join('items', 'xstockcards.itemID=items.itemID', 'left');	
											$this->db->where('xstockcards.itemID', $xsc->itemID);
											$this->db->where('xstockcards.ancillaryID', $xsc->ancillaryID);
											$this->db->where('xstockcards.expiry', $xsc->expiry);
											$this->db->where('xstockcards.endBal >', 0);
											//$this->db->limit(1);
											$this->db->order_by('xstockcards.xstockcardID','desc');
											$a = $this->db->get()->row();
											?>
											<tr>
												<td>
									    			<input type="checkbox" name="update_items[]" id="update_item_<?php echo $a->xstockcardID ?>" value="<?php echo $a->xstockcardID ?>" checked onclick="return false;"/>
												</td>
												<?php if (!$catID) { ?>
												<td><?php echo $row->category ?></td>
												<?php } ?>	
												<td><?php echo $a->name ?></td>
									    		<td><?php echo $a->description ?></td>
									    		<td>
												<?php 
													if ($a->expiry != '1970-01-01' && $a->expiry != '0000-00-00'){
														 echo date('M d, Y',strtotime($a->expiry));
													}
												?>	
												</td>
									    	    <td><?php echo $a->endBal ?></td>
									    		<td>
									    			<input type="text" class="form-control txtnumber" style="width: 100px;" name="onCount_<?php echo $a->xstockcardID ?>" id="onCount_<?php echo $a->xstockcardID ?>" value="<?php echo $a->endBal ?>" onkeypress="return isNumber(event);" onkeyup="get_variance(<?php echo $a->xstockcardID?>)" onfocus="$(this).select();" />
									    			<input type="hidden" name="onHand_<?php echo $a->xstockcardID ?>" id="onHand_<?php echo $a->xstockcardID ?>" value="<?php echo $a->endBal ?>"/>
									    			<input type="hidden" name="xstockcardID" id="xstockcardID"  value="<?php echo $a->xstockcardID ?>" />
									    		</td>
									    		<td><input type="text" class="form-control" style="width:100px" name="variance_<?php echo $a->xstockcardID ?>" id="variance_<?php echo $a->xstockcardID ?>" value="0" readonly/></td>
									    		<td>
									    			<?php echo number_format($a->price,2) ?>
									    			<input type="hidden" class="" style="width: 50px;" name="price_<?php echo $a->xstockcardID ?>" id="price_<?php echo $a->xstockcardID ?>" value="<?php echo number_format($a->price,2,".","") ?>" />
												</td>
											</tr>
										<?php
										}
									} else { 
									?>
										<tr>
											<td>
								    			<input type="checkbox" name="update_xitems[]" id="update_xitem_<?php echo $row->id ?>_main" value="<?php echo $row->id ?>" checked onclick="return false;"/>
											</td>
											<?php if (!$catID) { ?>
											<td><?php echo $row->category ?></td>
											<?php } ?>	
											<td><?php echo $row->name ?></td>
								    		<td><?php echo $row->description ?></td>
								    		<td></td>
								    	    <td><?php echo $row->endBal ?></td>
								    		<td>
								    			<input type="text" class="form-control txtnumber" style="width: 100px;" name="onCount_<?php echo $row->id ?>_main" id="onCount_<?php echo $row->id ?>_main" value="<?php echo $row->endBal?>" onkeypress="return isNumber(event);" onkeyup="get_xvariance(<?php echo $row->id?>)" onfocus="$(this).select();"/>
								    			<input type="hidden" name="onHand_<?php echo $row->id ?>_main" id="onHand_<?php echo $row->id ?>_main" value="<?php echo $row->endBal ?>" />
								    			<input type="hidden" name="id" id="id"  value="<?php echo $row->id ?>" />
								    		</td>
								    		<td><input type="text" class="form-control" style="width:100px" name="variance_<?php echo $row->id ?>_main" id="variance_<?php echo $row->id ?>_main" value="0" readonly/></td>
								    		<td>
								    			<?php echo number_format($row->lastcost,2) ?>
								    			<input type="hidden" class="" style="width: 50px;" name="price_<?php echo $row->id ?>_main" id="price" value="<?php echo number_format($row->price,2,".","") ?>" />
											</td>
										</tr>	
								<?php }?>
							    </tbody>
								<?php 
								}
								for($i=$ctr; $i<=5; $i++) {
									?>
										<tr><td valign="top" colspan="9">&nbsp;</td></tr>
									<?php 
								}
							} else {
								for($i=0; $i<5; $i++) {
								?>
								<tr><td valign="top" colspan="9">&nbsp;</td></tr>
								<?php 
								}
							}
							 ?>
						</table>
					</div>
				</div>
				<div class="card-body" id="loading" style="display:none;">
				<div class="form-group mb-0">
				<center>
				<img src="<?php echo base_url() ?>assets/img/main/loading_big.gif" alt="Loading..... Please Wait!" title="Loading..... Please Wait!" />
				</center>
				</div>
				</div>
				<div class="card-body" id="main-div2">
					<div class="form-group mb-0">
						<button class="btn btn-primary btn-raised pill" type="button" name="cmdSave" id="cmdSave">
						Save
						</button>
						<input type="button" id="cmdCancel" class="btn btn-outline-danger btn-raised pill" value="Cancel"/>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>
<script>
	$('#cmdSave').click(function(){
		if (check_fields()) {
	    	$('#cmdSave').attr('disabled','disabled');
	    	$('#cmdSave').addClass('loader');
	    	$('#main-div').hide();
	    	$('#main-div2').hide();
			$('#loading').show();
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
		    	  window.location = '<?php echo site_url('physical_count/show') ?>';
		      }
		    });
	});

	$(document).ready(function(){
	    	$('.txtnumber').change(function() {
	        	the_id = $(this).attr('id').split("_");
	        	$('#update_item_'+the_id[1]).attr("checked","checked");
	        	$('#update_item_'+the_id[1]).attr("checked","checked");
	        });
		});
	
	function getItemsBySection()
	{
		window.location = '<?php echo site_url('physical_count/create/') ?>'+$('#ancillaryID').val()+'/'+$('#catID').val();
	}

	function get_variance(id) 
	{
		onhand = $('#onHand_'+id).val();
		oncount = $('#onCount_'+id).val();
		
		total = oncount - onhand;
		
		$('#variance_'+id).val(total);
	}
	function get_xvariance(id) 
	{
		onhand = $('#onHand_'+id+'_main').val();
		oncount = $('#onCount_'+id+'_main').val();

		total = oncount - onhand;
		
		$('#variance_'+id+'_main').val(total);
	}
</script>