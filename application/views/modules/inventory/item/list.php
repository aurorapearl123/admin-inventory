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
				<a href="<?php echo $controller_page?>/create" class="btn btn-primary btn-raised btn-sm pill"><i class="icon left la la-plus lg"></i>Add New</a>
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
												    array('column_header'=>'Category','column_field'=>'catID','width'=>'w-10','align'=>''),
												    array('column_header'=>'Item Code','column_field'=>'itemCode','width'=>'w-5','align'=>''),
												    array('column_header'=>'Item','column_field'=>'name','width'=>'w-10','align'=>''),
												    array('column_header'=>'Description','column_field'=>'description','width'=>'w-10','align'=>''),
												    array('column_header'=>'Price','column_field'=>'avecost','width'=>'w-10','align'=>''),
												    array('column_header'=>'Brand','column_field'=>'brandID','width'=>'w-10','align'=>''),
												    array('column_header'=>'Reorder Lvl','column_field'=>'reorderLvl','width'=>'w-10','align'=>''),
												    array('column_header'=>'Status','column_field'=>'status','width'=>'w-5','align'=>'center'),
												);
												
												echo $this->htmlhelper->tabular_header($headers, $sortby, $sortorder);
												?>
										</tr>
										<tr id="filter-group" class="collapse multi-collapse table-filter show">
											<th class="form-group form-input">&nbsp;</th>
											<th class="form-group form-input">
												<select id="catID" name="catID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Category" required>
													<option value="" selected>&nbsp;</option>
													<?php 
													$this->db->where('status',1);
													$recs = $this->db->get('category')->result();
													foreach($recs as $rec){
													?>
													<option value="<?php echo $rec->catID?>" <?php if($rec->catID == $catID){ echo "selected"; }?>><?php echo $rec->category?></option>
													<?php }?>
												</select>
											</th>
											<th class="form-group form-input">
												<input type="text" class="form-control" id="itemCode" name="itemCode" style="width:100px" value="<?php echo $itemCode ?>">
											</th>
											<th class="form-group form-input">
												<input type="text" class="form-control" id="name" name="name" style="width:150px" value="<?php echo $name ?>">
											</th>
											<th class="form-group form-input">
												<input type="text" class="form-control" id="description" name="description" style="width:150px" value="<?php echo $description ?>">
											</th>
											<th class="form-group form-input">&nbsp;</th>
											<th class="form-group form-input">
												<select id="brandID" name="brandID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Brand" required>
													<option value="" selected>&nbsp;</option>
													<?php 
													$this->db->where('status',1);
													$recs = $this->db->get('brands')->result();
													foreach($recs as $rec){
													?>
													<option value="<?php echo $rec->brandID?>" <?php if($rec->brandID == $brandID){ echo "selected"; }?>><?php echo $rec->brand?></option>
													<?php }?>
												</select>
											</th>
											<th class="w-10 form-group form-input">&nbsp;</th>
											<th style="width: 7%;">
												<select class="form-control" id="status" name="status">
													<option value="" selected>&nbsp;</option>
													<option value="1" <?php echo ($status == '1') ? 'selected' : ''?>>Active</option>
													<option value="0" <?php echo ($status == '0') ? 'selected' : ''?>>Inactive</option>
												</select>
											</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											$ctr = 0;
											if (count($records)) {
											    foreach($records as $row) {
											    $ctr++;
											    ?>
													<tr onclick="location.href='<?php echo $controller_page."/view/".$this->encrypter->encode($row->$pfield); ?>'">
														<td><span><?php echo $ctr ?></span></td>
														<td><span><?php echo $row->category ?></span></td>
														<td><span><?php echo $row->itemCode ?></span></td>
														<td><span><?php echo $row->name ?></span></td>
														<td><span><?php echo $row->description ?></span></td>
														<td><span><?php echo $row->avecost ?></span></td>
														<td><span><?php echo $row->brand ?></span></td>
														<td><span><?php echo $row->reorderLvl ?></span></td>
														<td align="center">
															<?php 
															if ($row->status == 1) {
																echo "<span class='badge badge-pill badge-success'>Active</span>";
															} else if($row->status == 0) {
																echo "<span class='badge badge-pill badge-light'>Inactive</span>";
															}
															?>
														</td>
													</tr>
												<?php 
											    }
											} else {	
											?>
													<tr>
														<td colspan="9" align="center"> <i>No records found!</i></td>
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