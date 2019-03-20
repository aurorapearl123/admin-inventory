<form name="frmFilter" id="frmFilter" method="POST" action="<?php echo $controller_page ?>/show">
	<input type="hidden" id="sortby" name="sortby" value="<?php echo $sortby ?>" />
	<input type="hidden" id="sortorder" name="sortorder" value="<?php echo $sortorder ?>" />
	<div class="subheader">
		<div class="d-flex align-items-center">
			<div class="title mr-auto">
				<h3><i class="icon left la <?php echo $current_module['icon'] ?>"></i> <?php echo $current_module['title'] ?></span></h3>
			</div>
			<?php if ($roles['create']) {?>
			<div class="subheader-tools">
			
			</div>
			<?php } ?>
		</div>
	</div>
	<div class="content">
		<div class="row">
			<div class="col-12">
				<div class="card-box full-body">
					<div class="card-head">
						<div class="head-caption">
							<div class="head-title">
								<h4 class="head-text"><?php echo $current_module['module_label'] ?> List</h4>
							</div>
						</div>
						
						<div class="card-head-tools">
							<ul class="tools-list">
								<li>
									<button id="btn-apply" type="submit" class="btn btn-primary btn-sm pill collapse multi-collapse show">Apply Filter</button>
								</li>
								<li>
									<button type="button" id="btn-filter" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="Filters" onclick="#"><i class="la la-sort-amount-asc"></i></button>
								</li>
								<li>
									<button type="button" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="Print" onclick="popUp('<?php echo $controller_page ?>/printlist', 800, 500)"><i class="la la-print"></i></button>
								</li>
								<li>
									<button type="button" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="Export to Excel File" onclick="window.location='<?php echo $controller_page ?>/exportlist'"><i class="la la-file-excel-o"></i></button>
								</li>
							</ul>
						</div>
					</div>

					
					<!--  sorting_asc -->
					<div class="card-body">
						<div class="datatables_wrapper">
							<div class="table-responsive-md">
								<table align="left" class="table table-striped hover">
									<thead>
										<tr>
											<?php 
												$headers = array(
													array('column_header'=>'','column_field'=>'','width'=>'w-5','align'=>''),
													
													array('column_header'=>'Ancillary','column_field'=>'name','width'=>'w-10','align'=>''),
												    array('column_header'=>'Item','column_field'=>'name','width'=>'w-10','align'=>''),
													array('column_header'=>'Description','column_field'=>'description','width'=>'w-10','align'=>''),
													array('column_header'=>'Quantity','column_field'=>'qty','width'=>'w-10','align'=>''),
												    array('column_header'=>'Price','column_field'=>'avecost','width'=>'w-10','align'=>''),
													array('column_header'=>'Reorder Lvl','column_field'=>'reorderLvl','width'=>'w-10','align'=>''),
													array('column_header'=>'Critical Lvl','column_field'=>'reorderLvl','width'=>'w-10','align'=>''),
													array('column_header'=>'Status','column_field'=>'status','width'=>'w-5','align'=>'center'),
													array('column_header'=>'Option','column_field'=>'status','width'=>'w-5','align'=>'center'),
													
												);
												
												echo $this->htmlhelper->tabular_header($headers, $sortby, $sortorder);
												?>
										</tr>
										<tr id="filter-group" class="collapse multi-collapse table-filter show">
											<th class="form-group form-input">&nbsp;</th>
											<th class="form-group form-input">
											<select class="form-control" tabindex="5" name="ancillaryID" id="ancillaryID"  title="Ancilliary" required >
											<option value="" selected>&nbsp;</option>

											<?php 
											foreach($ancillaries as $rec){?>

												<option value="<?php echo $rec->ancillaryID?>" <?php echo ($ancillaryID == $rec->ancillaryID)? "selected" : ""; ?>><?php echo $rec->division?></option> 
												<?php } ?>
											</select>
											</th>

											<th class="form-group form-input">
												<input type="text" class="form-control" id="name" name="name" style="width:150px" value="<?php echo $name ?>">
											</th>
											<th class="form-group form-input">
												<input type="text" class="form-control" id="description" name="description" style="width:150px" value="<?php echo $description ?>">
											</th>
											
											<th class="form-group form-input">&nbsp;</th>
											<th class="form-group form-input">&nbsp;</th>
											<th class="w-10 form-group form-input">&nbsp;</th>
											<th class="w-10 form-group form-input">&nbsp;</th>
											
											<th style="width: 7%;">
												<select class="form-control" id="status" name="status">
													<option value="" selected>&nbsp;</option>
													<option value="1" <?php echo ($status == '1') ? 'selected' : ''?>>Active</option>
													<option value="0" <?php echo ($status == '0') ? 'selected' : ''?>>Inactive</option>
												</select>
											</th>
											<th class="w-10 form-group form-input">&nbsp;</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											$ctr = 0;
											if (count($records)) {
											    foreach($records as $row) {
											    $ctr++;
											    ?>
													<?php $brand_name = $row->brand ? "( $row->brand )": ""?>
													<tr onclick="#">
														<td><span><?php echo $ctr ?></span></td>
														<td><span><?php echo $row->division ?></span></td>
														<td><span><?php echo $row->name.' '.$brand_name?></span></td>
														<td><span><?php echo $row->item_description ?></span></td>
														<td><span><?php echo $row->qty ?></span></td>
														<td><span><?php echo $row->avecost ?></span></td>
														<td><span><input name="reorderlvl" type="number" value="<?php echo $row->inventory_reorderLvl ?>" class="form-control"></span></td>
														<td><span>
														<input name="criticalllv" type="number" value="<?php echo $row->inventory_criticalLvl ?>" class="form-control">
														</span></td>
														<!-- <td align="center"><button type="button" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="Edit" onclick="editRecord('<?php echo $row->inventoryID?>'); return false;"><i class="la la-edit"></i></button></td> -->
														
														
														<td align="center">
															<?php 
															if ($row->inventory_status == 1) {
																echo "<span class='badge badge-pill badge-success'>Active</span>";
															} else if($row->inventory_status == 0) {
																echo "<span class='badge badge-pill badge-light'>Inactive</span>";
															}
															?>
														</td>
														<td align="center"><button type="button" class="btn btn-outline-light bmd-btn-icon edit-row" data-id="<?php echo $row->inventoryID?>" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="la la-edit"></i></button></td>
													</tr>
												<?php 
											    }
											} else {	
											?>
													<tr>
														<td colspan="10" align="center"> <i>No records found!</i></td>
													</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
							<div class="datatable-footer d-flex">
								<div class="datatable-pagination">
									Pages: &nbsp;&nbsp; 
								</div>
								<div class="datatable-pagination">
									<?php 
									$pagination = $this->pagination->create_links(); 
									
									if ($pagination) {
									     echo $pagination;      
									} else {
									    echo "1";
									}
									?>
								</div>
								<div class="datatable-pager-info float-right ml-auto">
									<div class="d-flex">
										<div class="datatable-pager-size">
											<div class="dataTables_length">
												<select aria-controls="table1" class="form-control select-sm pill" tabindex="-98" id="limit" name="limit" onchange="$('#frmFilter').submit();">
													<option value="25" <?php if ($limit==25) echo "selected"; ?>>25</option>
													<option value="50" <?php if ($limit==50) echo "selected"; ?>>50</option>
													<option value="75" <?php if ($limit==75) echo "selected"; ?>>75</option>
													<option value="100" <?php if ($limit==100) echo "selected"; ?>>100</option>
													<option value="125" <?php if ($limit==125) echo "selected"; ?>>125</option>
													<option value="150" <?php if ($limit==150) echo "selected"; ?>>150</option>
													<option value="175" <?php if ($limit==175) echo "selected"; ?>>175</option>
													<option value="200" <?php if ($limit==200) echo "selected"; ?>>200</option>
													<option value="all" <?php if ($limit=='All') echo "selected"; ?>>All</option>
												</select>
											</div>
										</div>
										<div class="datatable-pager-detail">
											<div class="dataTables_info">Displaying <?php echo ($offset+1) ?> - <?php if ($offset+$limit < $ttl_rows) { echo ($offset+$limit); } else  { echo $ttl_rows; } ?> of <?php echo number_format($ttl_rows,0)?> records</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<script>
	$(document).ready(function(){
		console.log("on ready");

		$('.edit-row').on('click', function(){
			var id = $(this).data("id");
			var criticallvl = $(this).closest('td').prev("td").prev("td").find("input").val();
			var reorderlvl =  $(this).closest('td').prev("td").prev("td").prev("td").find("input").val();
			// console.log("this is record"+id);
			// console.log("the critical level");
			// console.log(criticallvl);
			// console.log("reorder");
			// console.log(reorderlvl);
			var title = $(this).closest('td').prev("td").prev("td").prev("td").prev("td").prev("td").prev("td").text();

			swal({
				title: title,
				text: "Do you want to update reorder level "+reorderlvl+" and critical level "+criticallvl,
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes',
				cancelButtonText: 'No'
				})
				.then((willDelete) => {
				if (willDelete.value) {
					// window.location = '<?php echo site_url('unit_measurement/show') ?>';
					
					$.post("<?php echo $controller_page?>/updateCriticalReorder", {id: id, criticallvl: criticallvl, reorderlvl : reorderlvl }, 
					function(data){
						console.log(data);
						// console.log("button result");
					});
					
					
				}
			});

		});

	});

</script>