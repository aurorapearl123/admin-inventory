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
				<a href="<?php echo $controller_page?>/create" class="btn btn-primary btn-raised btn-xs pill"><i class="icon left la la-plus lg"></i>Add New</a>
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
								<table class="table table-striped has-img hover">
									<thead>
										<tr>
											<?php 
												$headers = array(
												    array('column_header'=>'PIN','column_field'=>'inspectorpin','width'=>'w-10','align'=>''),
												    array('column_header'=>'Last Name','column_field'=>'lname','width'=>'w-10','align'=>''),
												    array('column_header'=>'First Name','column_field'=>'fname','width'=>'w-10','align'=>''),
												    array('column_header'=>'Middle Name','column_field'=>'mname','width'=>'w-10','align'=>''),
												    array('column_header'=>'Status','column_field'=>'status','width'=>'w-5','align'=>'center'),
												);
												
												echo $this->htmlhelper->tabular_header($headers, $sortby, $sortorder);
												?>
										</tr>
										<tr id="filter-group" class="collapse multi-collapse table-filter show">
											<th class="form-group form-input">
												<input type="text" class="form-control" id="inspectorpin" name="inspectorpin"  value="<?php echo $inspectorpin ?>" style="width:200px">
											</th>
											<th class="form-group form-input">
												<input type="text" class="form-control" id="lname" name="lname"  value="<?php echo $lname ?>" style="width:200px">
											</th>
											<th class="form-group form-input">
												<input type="text" class="form-control" id="fname" name="fname"  value="<?php echo $fname ?>" style="width:200px">
											</th>
											<th class="form-group form-input">
												<input type="text" class="form-control" id="mname" name="mname"  value="<?php echo $mname ?>" style="width:200px">
											</th>
											<th>
												<select class="form-control" id="status" name="status" style="width:100px">
													<option value="">&nbsp;</option>
													<option value="1" <?php if($status == "1") echo "selected"; ?>>Active</option>
													<option value="0" <?php if($status == "0") echo "selected"; ?>>Inactive</option>
												</select>
											</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											if (count($records)) {
											    foreach($records as $row) {
											    ?>
										<tr onclick="location.href='<?php echo $controller_page."/view/".$this->encrypter->encode($row->inspectorID); ?>'">
											<td><?php echo $row->inspectorpin ?></td>
											<td><?php echo $row->lname ?></td>
											<td><?php echo $row->fname ?></td>
											<td><?php echo $row->mname ?></td>
											<td align="center">
												<?php 
												if ($row->status == 1) {
													echo "<span class='badge badge-pill badge-success'>Active</span>";
												} else {
													echo "<span class='badge badge-pill badge-danger'>Inactive</span>";
												}
												?>
											</td>
										</tr>
										<?php }
											} else {	?>
										<tr>
											<td colspan="5" align="center"> <i>No records found!</i></td>
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
