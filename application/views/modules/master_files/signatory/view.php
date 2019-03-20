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
<div class="content">
	<div class="row">
		<div class="col-12">
			<div class="card-box">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="head-text"><i class="icon left la <?php echo $current_module['icon'] ?>"></i>VIEW <?php echo strtoupper($current_module['title']) ?> &nbsp;| &nbsp;
								<?php 
									if($rec->status==0){ 
										echo "<font color='red'>Inactive</font>"; 
									} else if($rec->status==1) { 
										echo "<font color='green'>Active</font>"; 
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
								<a href="<?php echo $controller_page.'/edit/'.$this->encrypter->encode($rec->signatoryID) ?>" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit"><i class="la la-edit"></i></a>
							</li>
							<?php } ?>
							<?php if ($roles['delete'] && !$in_used) {?>
							<li>
								<button name="cmddelete" id="cmddelete" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete" onclick="deleteRecord('<?php echo $this->encrypter->encode($rec->signatoryID); ?>');"><i class="la la-trash-o"></i></button>
							</li>
							<?php } ?>
							<?php if ($this->session->userdata('current_user')->isAdmin) {?>
							<li>
								<button type="button" id="recordlog" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Record Logs" onclick="popUp('<?php echo site_url('logs/record_log/signatories/'.$pfield.'/'.$this->encrypter->encode($rec->$pfield).'/'.ucfirst(str_replace('_', '&', $controller_name))) ?>', 1000, 500)"><i class="la la-server"></i></button>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<div class="card-body">
					<div class="data-view">
						<table class="view-table">
							<tbody>
								<tr>
									<td class="data-title" style="width:120px" nowrap>Code :</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $rec->code; ?></td>
									<td class="data-input"></td>
								</tr>
								<tr>
									<td class="data-title" style="width:120px" nowrap>Name :</td>
									<td class="data-input" style="width:420px" nowrap><?php echo $rec->fullname; ?></td>
									<td class="data-input"></td>
								</tr>
								<tr>
									<td class="data-title" nowrap>Designation 1:</td>
									<td class="data-input" nowrap><?php echo $rec->designation1; ?></td>
									<td class="data-input"></td>
								</tr>
								<tr>
									<td class="data-title" nowrap>Designation 2:</td>
									<td class="data-input" nowrap><?php echo $rec->designation2; ?></td>
									<td class="data-input"></td>
								</tr>
								<tr>
									<td class="data-title" nowrap>Designation 3:</td>
									<td class="data-input" nowrap><?php echo $rec->designation3; ?></td>
									<td class="data-input"></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>