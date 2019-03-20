
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
								<a href="<?php echo $controller_page.'/edit/'.$this->encrypter->encode($rec->$pfield) ?>" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit"><i class="la la-edit"></i></a>
							</li>
							<?php } ?>
							<?php if ($roles['delete'] && !$in_used) {?>
							<li>
								<button name="cmddelete" id="cmddelete" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete" onclick="deleteRecord('<?php echo $this->encrypter->encode($rec->$pfield); ?>');"><i class="la la-trash-o"></i></button>
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
									<td class="data-title" style="width:170px" nowrap>Expiration Date:</td>
									<td class="data-input" style="width:420px" nowrap><?php echo date("m/d/Y",strtotime($rec->expiration)); ?></td>
									
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="data-title" style="width:170px" nowrap>Message Type:</td>
									<td class="data-input" style="width:420px" nowrap>
										<?php 
										switch ($rec->type) {
											case 1:
											echo 'Information';
											break;
											case 2:
											echo 'Notification';
											break;
											case 3:
											echo 'Warning';
											break;

										}
										?>
											
									</td>
									
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="data-title" valign="top" style="width:120px" nowrap>Message:</td>
									<td class="data-input" colspan="6" style="width:420px" nowrap><?php echo $rec->message; ?></td>
									
									
									
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
									
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
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
