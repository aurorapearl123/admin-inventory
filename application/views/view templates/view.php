
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
							<h4 class="head-text">View <?php echo $current_module['module_label'] ?></h4>
						</div>
					</div>
					<div class="card-head-tools">
						<ul class="tools-list">
							<?php $id = $this->encrypter->encode($rec->$pfield); ?>
							<?php if ($roles['edit']) {?>
							<li>
								<a href="<?php echo $controller_page.'/edit/'.$this->encrypter->encode($rec->branchID) ?>" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit"><i class="la la-edit"></i></a>
							</li>
							<?php } ?>
							<?php if ($roles['delete'] && !$in_used) {?>
							<li>
								<button name="cmddelete" id="cmddelete" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete" onclick="deleteRecord('<?php echo $this->encrypter->encode($rec->branchID); ?>');"><i class="la la-trash-o"></i></button>
							</li>
							<?php } ?>
							<?php if ($this->session->userdata('current_user')->isAdmin) {?>
							<li>
								<button type="button" id="recordlog" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Record Logs" onclick="popUp('<?php echo site_url('logs/record_log/'.$table_name.'/'.$pfield.'/'.$this->encrypter->encode($rec->$pfield).'/'.ucfirst(str_replace('_', '&', $controller_name))) ?>', 1000, 500)"><i class="la la-server"></i></button>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			<!-- Card Header End -->
				
				
				
				
				
				<!-- Card Body Start -->
				<div class="card-body">
					<div class="data-view">
						<table class="view-table">
							<tbody>
							
							<!-- Table Rows Start -->
								<tr>
									<td class="data-title" style="width:120px" nowrap>Branch Code:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $rec->branchCode; ?></td>
									<td class="data-title" style="width:120px" nowrap>Company</td>
									<td class="data-input" nowrap><?php echo $rec->companyCode.' - '.$rec->companyName; ?></td>
									<td class="data-input"></td>
								</tr>
								<tr>
									<td class="data-title" nowrap>Branch Name:</td>
									<td class="data-input" nowrap><?php echo $rec->branchName; ?></td>
									<td class="data-title" nowrap>Branch Abbr:</td>
									<td class="data-input" nowrap><?php echo $rec->branchAbbr; ?></td>
									<td class="data-input"></td>
								</tr>
								<tr>
									<td class="data-title" nowrap>Branch Contact:</td>
									<td class="data-input" nowrap><?php echo $rec->branchContact; ?></td>
									<td class="data-title" nowrap>Branch Email:</td>
									<td class="data-input" nowrap><?php echo $rec->branchEmail; ?></td>
									<td class="data-input"></td>
								</tr>
								<tr>
									<td class="data-title" nowrap>Branch Head:</td>
									<td class="data-input" nowrap><?php echo $rec->fname.' '.$rec->mname.' '.$rec->lname; ?></td>
									<td class="data-title" nowrap>Head Title:</td>
									<td class="data-input"><?php echo $rec->branchHeadTitle; ?></td>
									<td class="data-input"></td>
								</tr>
								<tr>
									<td class="data-title" nowrap>Branch Address:</td>
									<td class="data-input" nowrap><?php echo $rec->branchAddress; ?></td>
									<td class="data-title" nowrap>&nbsp;</td>
									<td class="data-title" nowrap><?php //echo $rec->remarks; ?></td>
									<td class="data-input"></td>
								</tr>
								<tr>
									<td class="data-title" nowrap>Admin Officer:</td>
									<td class="data-input" nowrap><?php echo $rec->adminOfficer; ?></td>
									<td class="data-title" nowrap>Timekeeper:</td>
									<td class="data-input" nowrap><?php echo $rec->timekeeper; ?></td>
									<td class="data-input"></td>
								</tr>
								<tr>
									<td class="data-title" nowrap>Status:</td>
									<td class="data-input" nowrap>
										<?php 
											if ($rec->status == 1) {
												echo "<span class='badge badge-pill badge-success'>Active</span>";
											} else {
												echo "<span class='badge badge-pill badge-danger'>Inactive</span>";
											}
											?>
									</td>
									<td class="data-title" colspan="3"></td>
								</tr>
								<!-- Table Rows End -->
								
								
							</tbody>
						</table>
					</div>



						<!-- <?php 
						$tableData = array();
						array_push($tableData, array('label'=>'Post No. :', 'val'=>$rec->postNo));
						array_push($tableData, array('label'=>'Date Posted :', 'val'=>$rec->datePosted));
						array_push($tableData, array('label'=>'Job Position :', 'val'=>$rec->jobTitle));
						array_push($tableData, array('label'=>'Company :', 'val'=>$rec->companyName));
						array_push($tableData, array('label'=>'Branch :', 'val'=>$rec->branchName));
						array_push($tableData, array('label'=>'Division :', 'val'=>$rec->divisionName));
						array_push($tableData, array('label'=>'Employment :', 'val'=>$rec->employeeType));
						array_push($tableData, array('label'=>'Qualifications :', 'val'=>$rec->qualifications));
						array_push($tableData, array('label'=>'Remarks :', 'val'=>$rec->remarks));
						$statusString = '';
						if ($rec->status == 1) {
							$statusString = "<span class='badge badge-pill badge-success'>Active</span>";
						} else {
							$statusString = "<span class='badge badge-pill badge-danger'>Inactive</span>";
						}
						array_push($tableData, array('label'=>'Status :', 'val'=>$statusString));
						// var_dump($tableData);
						echo $this->tableview->table_view($tableData, 2);
						?> -->
				</div><!-- Card Body End -->
			</div>
		</div>
	</div>
</div><!-- Content End -->
