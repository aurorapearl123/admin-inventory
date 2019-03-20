
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
							<h4 class="head-text">View <?php echo $current_module['module_label'] ?>
								| 						
									<?php 
										if ($rec->status == 1) {
											echo "<font color='green'>Active</font>";
										} else {
											echo "<font color='red'>Inactive</font>";
										}
									?>
							</h4>
						</div>
					</div>
					<div class="card-head-tools">
						<ul class="tools-list">
							<?php $id = $this->encrypter->encode($rec->$pfield); ?>
							<?php if ($roles['edit']) {?>
							<li>
								<a href="<?php echo $controller_page.'/edit/'.$id ?>" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit"><i class="la la-edit"></i></a>
							</li>
							<?php } ?>
							
							<li>
								<button name="cmddelete" id="cmddelete" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete" onclick="deleteRecord('<?php echo $id; ?>');"><i class="la la-trash-o"></i></button>
							</li>
							
							<?php if ($this->session->userdata('current_user')->isAdmin) {?>
							<li>
								<button type="button" id="recordlog" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Record Logs" onclick="popUp('<?php echo site_url('logs/record_log/'.$table_name.'/'.$pfield.'/'.$id.'/'.ucfirst(str_replace('_', '&', $controller_name))) ?>', 1000, 500)"><i class="la la-server"></i></button>
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
									<td class="data-title" style="width:120px" nowrap>Customer Name:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $rec->lname == ''? '' : $rec->lname.', '.$rec->fname.' '.$rec->mname; ?></td>
									<td class="data-title" style="width:120px" nowrap>Company:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $rec->companyName; ?> </td>
									<td class="data-title"></td>
								</tr>								
								<tr>
									<td class="data-title">Date of Birth:</td>
									<td class="data-input"><?php echo date('M d, Y',strtotime($rec->bday))?></td>									
									<td class="data-title" style="width:120px" nowrap>Credit Limit:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo number_format($rec->creditLimit,2); ?> </td>
									<td class="data-title"></td>
								</tr>								
								<tr>
									<td class="data-title">Age</td>
									<td class="data-input">
										<?php 
										if($rec->bday!="0000-00-00") {
											echo (date('Y') - date('Y',strtotime($rec->bday))); 
										} else {
											echo " -- "; 
										}
										?>
									</td>

									<td class="data-title" style="width:120px" nowrap>Gender</td>
									<td class="data-input" nowrap>
										<?php 
										if($rec->gender =="M") {
											echo "Male"; 
										} else {
											echo "Female"; 
										}
										?>
									</td>									
								</tr>

								<tr>
									<td class="data-title" style="width:120px" nowrap>Contact No:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $rec->contactNo; ?> </td>
									<td class="data-title"></td>
									<td class="data-title"></td>
								</tr>								
								<tr>
																
									<td class="data-title" nowrap>Province:</td>
									<td class="data-input" nowrap><?php echo $rec->province; ?></td>
									<td class="data-title" nowrap>City:</td>
									<td class="data-input" nowrap><?php echo $rec->city; ?></td>
								</tr>
								<tr>
									<td class="data-title" nowrap>Barangay:</td>
									<td class="data-input" nowrap><?php echo $rec->barangay; ?></td>
									<td class="data-title" nowrap>Street:</td>
									<td class="data-input"><?php echo $rec->streetNo; ?></td>									
								</tr>
								<!-- Table Rows End -->
								
								
							</tbody>
						</table>
					</div>



					
				</div><!-- Card Body End -->
			</div>
		</div>
	</div>
</div><!-- Content End -->
