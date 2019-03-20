
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
								<a href="<?php echo $controller_page.'/edit/'.$this->encrypter->encode($rec->rankID) ?>" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit"><i class="la la-edit"></i></a>
							</li>
							<?php } ?>
							<?php if ($roles['delete'] && !$in_used) {?>
							<li>
								<button name="cmddelete" id="cmddelete" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete" onclick="deleteRecord('<?php echo $this->encrypter->encode($rec->rankID); ?>');"><i class="la la-trash-o"></i></button>
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
									<td class="data-title" style="width:120px" nowrap>Rank Name:</td>
									<td class="data-input" nowrap><?php echo $rec->rankName; ?></td>
									<td class="data-title"></td>
									<td class="data-title"></td>
								</tr>
								<tr>
									<td class="data-title" style="width:120px" nowrap>Points Required:</td>
									<td class="data-input" nowrap><?php echo number_format($rec->pointsRequired,2); ?></td>
									<td class="data-title"></td>
									<td class="data-title"></td>
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