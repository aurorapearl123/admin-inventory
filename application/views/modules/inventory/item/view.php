<div class="subheader">
	<div class="d-flex align-items-center">
		<div class="title mr-auto">
			<h3><i class="icon left la <?php echo $current_module['icon'] ?>"></i> <?php echo $current_module['title'] ?> </h3>
		</div>
		<div class="subheader-tools">
			<a href="<?php echo $controller_page?>/show" class="btn btn-primary btn-raised btn-sm pill"><i class="icon ti-angle-left"></i> Back to List</a>
		</div>
	</div>
</div>
<div class="content">
	<div class="row">
		<div class="col-8">
			<div class="card-box" style="height:700px">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="head-text"><i class="icon left la <?php echo $current_module['icon'] ?>"></i>VIEW <?php echo strtoupper($current_module['title']) ?> &nbsp;| &nbsp;<?php if($rec->status==1){ echo "<font color='green'>Active</font>"; } else { echo "<font color='red'>Inactive</font>"; }?></h4>
						</div>
					</div>
					<div class="card-head-tools">
						<ul class="tools-list">
							<li>
								<a id="viewStockcardBtn" class="btn btn-outline-primary nav-link" onclick="viewStockcardModal();"><i class="icon left la la-info"></i><span class="nav-text">View Stockcards</span></a>
							</li>
							<?php if ($roles['edit']) {?>
							<li>
								<a href="<?php echo $controller_page ?>/edit/<?php echo $this->encrypter->encode($rec->$pfield)?>" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit"><i class="la la-edit"></i></a>
							</li>
							<?php } ?>
							<li>
								<button type="button" id="recordlog" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Record Logs" onclick="popUp('<?php echo site_url('logs/record_log/items/'.$pfield.'/'.$this->encrypter->encode($rec->$pfield).'/Item') ?>', 1000, 500)"><i class="la la-server"></i></button>
							</li>
						</ul>
					</div>
				</div>
				<div class="card-body">
					<div class="data-view">
						<table class="view-table">
							<tbody>
								<tr>
									<td class="data-title" width="120px" nowrap>Item Code</td>
									<td class="data-input" width="400px"><?php echo $rec->itemCode?></td>
									<td class="data-title" width="120px" nowrap>Category</td>
									<td class="data-input" width="400px"><?php echo $rec->category?></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="data-title" width="120px" nowrap>Item Name</td>
									<td class="data-input" width="400px"><?php echo $rec->name?></td>
									<td class="data-title" width="120px" nowrap>Sub Category</td>
									<td class="data-input" width="400px"><?php echo $rec->subcatdesc?></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="data-title" nowrap>Description</td>
									<td class="data-input"><?php echo $rec->description?></td>
									<td class="data-title" width="120px" nowrap>Brand</td>
									<td class="data-input"><?php echo $rec->brand?></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="data-title" nowrap>UMSR</td>
									<td class="data-input"><?php echo $rec->umsr?></td>
									<td class="data-title" nowrap>Barcode</td>
									<td class="data-input">
										<svg id="barcode" style="height:35px;"></svg>
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
					<div class="data-view">
						<table class="view-table">
						    <tr>   
						        <td class="data-title" nowrap width="110">Inventory</td>
						        <td class="data-input" nowrap>
						        <table>
						        	<tr>
						        		<td width="25"><input name="inventoryType" id="inventoryType" type="radio" value="1" <?php if ($rec->inventoryType == 1) { echo "checked"; }?> disabled></td>
							        	<td width="25">Inventory</td>
							        	<td width="25"><input name="inventoryType" id="inventoryType" type="radio" value="0" <?php if ($rec->inventoryType == 0) { echo "checked"; }?> disabled></td>
							        	<td width="25"align="center" nowrap>Non-Inventory</td>
						        	</tr>
						       </table>
						       </td>
						    </tr>
						    <tr>   
						        <td class="data-title" nowrap>Dangerous Drug</td>
						        <td class="data-input" nowrap>
						        <table>
						        	<tr>
						        		<td width="25"><input name="dangerousDrug" id="dangerousDrug" type="checkbox" value="1" <?php if ($rec->dangerousDrug == 1) { echo "checked"; }?> disabled></td>
							        	<td width="25" style="color:red; font-size:10px">Check the box if this item is dangerous drug</td>
						        	</tr>
						       </table>
						       </td>
						    </tr>
						    <tr>   
						        <td class="data-title" nowrap>Vat Type</td>
						        <td class="data-input" nowrap>
						        <table>
							        <tr>
						        		<td width="10"><input name="vatType" id="vatType" type="radio" value="1" <?php if ($rec->vatType == 1) { echo "checked"; }?> disabled></td>
							        	<td width="25">Vatable&nbsp;&nbsp;&nbsp;</td>
							        	<td width="10"><input name="vatType" id="vatType" type="radio" value="0" <?php if ($rec->vatType == 0) { echo "checked"; }?> disabled></td>
							        	<td width="25">Non-Vatable&nbsp;&nbsp;&nbsp;</td>
					      				<td width="10"><input name="vatType" id="vatType" type="radio" value="2" <?php if ($rec->vatType == 2) { echo "checked"; }?> disabled></td>
							        	<td width="25"align="center" nowrap>Zero-Rated&nbsp;&nbsp;&nbsp;</td>
							        	<td width="10"><input name="vatType" id="vatType" type="radio" value="3" <?php if ($rec->vatType == 3) { echo "checked"; }?> disabled></td>
							        	<td width="25"align="center" nowrap>Exempted&nbsp;&nbsp;&nbsp;</td>
						       		</tr>
						       	</table>
						       </td>
						    </tr>
						    <tr>   
						        <td class="data-title" nowrap>Discountable</td>
						        <td class="data-input" nowrap>
						        <table>
							        <tr>
						        		<td width="25"><input name="discountable" id="discountable" type="radio" value="1" <?php if ($rec->discountable == 1) { echo "checked"; }?> disabled></td>
							        	<td width="25">Yes&nbsp;&nbsp;&nbsp;</td>
					      				<td width="25"><input name="discountable" id="discountable" type="radio" value="0" <?php if ($rec->discountable == 0) { echo "checked"; }?> disabled></td>
							        	<td width="25"align="center" nowrap>No&nbsp;&nbsp;&nbsp;</td>
						       		</tr>
						       	</table>
						       </td>
						    </tr>
						    <tr>   
						        <td class="data-title" nowrap>Markup-Type</td>
						        <td class="data-input" nowrap>
						        <table>
							        <tr>
						        		<td width="25"><input name="markupType" id="markupType" type="radio" value="Percentage" <?php if ($rec->markupType == "Percentage") { echo "checked"; }?> disabled></td>
							        	<td width="25">Percentage&nbsp;&nbsp;&nbsp;</td>
					      				<td width="25"><input name="markupType" id="markupType" type="radio" value="Peso" <?php if ($rec->markupType == "Peso") { echo "checked"; }?> disabled></td>
							        	<td width="25"align="center" nowrap>Peso&nbsp;&nbsp;&nbsp;</td>
						       		</tr>
						       	</table>
						       </td>
						    </tr>
						    <tr>   
						        <td class="data-title" nowrap>Markup Value</td>
						        <td class="data-input" nowrap>
						        <table>
							        <tr>
						        		<td width="200px"><?php echo number_format($rec->markup,2)?></td>
						       		</tr>
						       	</table>
						       </td>
						    </tr>
						</table>
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
					<div class="data-view">
						<table class="view-table">
							<tr>
								<td valign="top" width="30%">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
									    <tr>
									        <td class="data-title" valign="top" nowrap>Last Cost</td>
									        <td class="data-input">&#8369; <?php echo number_format($rec->lastcost,2)?></td>
									    </tr>
									    <tr>
									        <td class="data-title" valign="top" nowrap width="100">Lowest Cost</td>
									        <td class="data-input">&#8369; <?php echo number_format($rec->lowestcost,2)?></td>
									    </tr>
									    <tr>   
									        <td class="data-title" valign="top" nowrap>Highest Cost</td>
									        <td class="data-input">&#8369; <?php echo number_format($rec->highcost,2)?></td>
									    </tr>
									    <tr>   
									        <td class="data-title" valign="top" nowrap>Average Cost</td>
									        <td class="data-input">&#8369; <?php echo number_format($rec->avecost,2)?></td>
									    </tr>
									    <tr>
									        <td class="data-title" valign="top" nowrap width="100">Lowest Price</td>
									        <td class="data-input">&#8369; <?php echo number_format($rec->lowestprice,2)?></td>
									    </tr>
									    <tr>   
									        <td class="data-title" valign="top" nowrap>Highest Price</td>
									        <td class="data-input">&#8369; <?php echo number_format($rec->highprice,2)?></td>
									    </tr>
									    <tr>   
									        <td class="data-title" valign="top" nowrap>Average Price</td>
									        <td class="data-input">&#8369; <?php echo number_format($rec->aveprice,2)?></td>
									    </tr>
									    <tr>
										     <td class="data-title" valign="top" nowrap>MDRP</td>
										     <td class="data-input">&#8369; <?php echo number_format($rec->mdrPrice,2)?></td>
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
					<div class="data-view">
						<table class="view-table">
							<tr>
								<td valign="top" width="30%">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
									    <tr>
										     <td class="data-title" valign="top" nowrap>Reorder Level</td>
										     <td class="data-input"><?php echo number_format($rec->reorderLvl,2)?></td>
										</tr>
									    <tr>
										     <td class="data-title" valign="top" nowrap>Critical Level</td>
										     <td class="data-input"><?php echo number_format($rec->criticalLvl,2)?></td>
										</tr>
										<tr>
										     <td class="data-title" valign="top" nowrap>&nbsp;</td>
										     <td class="data-input">&nbsp;</td>
										</tr>
									    <tr>
										     <td class="data-title" valign="top" nowrap>Lead Time</td>
										     <td class="data-input">
										     	<?php echo $rec->leadtime?>
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
		<div class="col-12">
			<div class="card-box">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="form-label"><i class="icon left la <?php echo $current_module['icon'] ?>"></i> ITEM PRICE CANVAS</h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<div class="datatables_wrapper">
						<div class="table-responsive">
							<table class="table table-striped hover" style="border:1px solid #e9eae9">
								<thead class="thead-light">
									<tr>
								        <th valign="top" nowrap>Supplier</th>
								        <th valign="top" style="border-right:1px solid #e9eae9" nowrap>Price</th>
								    </tr>
								</thead>
								<tbody>
								    <?php foreach ($records as $record) {?>
								    <tr>
								        <td valign="top" nowrap><?php echo $record->suppName?></td>
								        <td>&#8369;<?php echo number_format($record->price,'2','.','')?></td>
								    </tr>
								    <?php }?>
							    </tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
JsBarcode("#barcode", "<?php echo $rec->barcode?>");

// $('#viewStockcardBtn').on(function(){
	// console.log('sss');
	function viewStockcardModal() {
		// alert('sss');
		$('#stockcard_modal').modal('show');
	}
	
    
  // });
</script>










<!-- Change Profile Pic modal -->
<div class="modal fade" id="stockcard_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      
        <div class="modal-body" align="center">
          
        	<?php foreach ($xstockcards as $row) { ?>

        		<div class="form-group">
        			<button onclick="openRecord('<?php echo site_url().'xstockcard/view_popup/'.$this->encrypter->encode($row->itemID).'/'.$this->encrypter->encode($row->ancillaryID).'/'.$row->expiry; ?>', 500, 500);"  class="btn btn-primary btn-raised btn-sm btn-block"><?php echo date('M Y', strtotime($row->expiry)); ?></button>
        		</div>

        	<?php } ?>


        	<div class="form-group">
        		<button onclick="openRecord('<?php echo site_url().'stockcard/view_popup/'.$this->encrypter->encode($rec->itemID).'/'.$this->encrypter->encode($rec->ancillaryID); ?>', 500, 900);" class="btn btn-primary btn-raised btn-sm btn-block">General Stockcard</button>
        	</div>
        </div>
        <div class="modal-footer" align="center">
         
          <button type="button" id="close" class="btn btn-outline-light btn-raised btn-block" data-dismiss="modal">Close</button>
        </div>
     
    </div>
  </div>
</div>




