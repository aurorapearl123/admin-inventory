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
			<div class="card-box" style="height:600px">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="head-text"><i class="icon left la <?php echo $current_module['icon'] ?>"></i>VIEW <?php echo strtoupper($current_module['title']) ?> &nbsp;| &nbsp;<?php if($rec->status==1){ echo "<font color='green'>Active</font>"; } else { echo "<font color='red'>Inactive</font>"; }?></h4>
						</div>
					</div>
					<div class="card-head-tools">
						<ul class="tools-list">
							
							<?php if ($roles['edit']) {?>
							<li>
								<a href="<?php echo $controller_page ?>/edit/<?php echo $this->encrypter->encode($rec->$pfield)?>" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit"><i class="la la-edit"></i></a>
							</li>
							<?php } ?>
							<li>
								<button type="button" id="recordlog" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Record Logs" onclick="popUp('<?php echo site_url('logs/record_log/products/'.$pfield.'/'.$this->encrypter->encode($rec->$pfield).'/Products') ?>', 1000, 500)"><i class="la la-server"></i></button>
							</li>
						</ul>
					</div>
				</div>
				<div class="card-body">
					<div class="data-view">
						<table class="view-table">
							<tbody>
								<tr>
									<td class="data-title" width="120px" nowrap>Product Code</td>
									<td class="data-input" width="400px"><?php echo $rec->productCode?></td>
									<td class="data-title" width="120px" nowrap>Category</td>
									<td class="data-input" width="400px"><?php echo $rec->category?></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="data-title" width="120px" nowrap>Product Name</td>
									<td class="data-input" width="400px"><?php echo $rec->name?></td>									
									<td class="data-title" nowrap>Description</td>
									<td class="data-input"><?php echo $rec->description?></td>
									<td>&nbsp;</td>
								</tr>							
								<tr>
									<td class="data-title" nowrap>UMSR</td>
									<td class="data-input"><?php echo $rec->umsr?></td>									
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
					<div class="data-view">
						<table class="view-table">
						    
						    <tr>   
						        <td class="data-title" nowrap>Vat Type</td>
						        <td class="data-input" nowrap>
						        <table>
							        <tr>
						        		<td width="10"><input name="vatType" id="vatType" type="radio" value="1" <?php if ($rec->vatType == 1) { echo "checked"; }?> disabled></td>
							        	<td width="25">Vatable&nbsp;&nbsp;&nbsp;</td>
							        	<td width="10"><input name="vatType" id="vatType" type="radio" value="0" <?php if ($rec->vatType == 0) { echo "checked"; }?> disabled></td>
							        	<td width="25">Non-Vatable&nbsp;&nbsp;&nbsp;</td>
					      				
							        	
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
					<div class="data-view">
						<table class="view-table">
							<tr>
								<td valign="top" width="30%">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
									    <tr>
										     <td class="data-title" valign="top" nowrap>Reorder Level</td>
										     <td class="data-input"><?php echo number_format($rec->reorderLvl,2)?></td>
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
<script>
	function viewStockcardModal() {		
		$('#stockcard_modal').modal('show');
	}
</script>