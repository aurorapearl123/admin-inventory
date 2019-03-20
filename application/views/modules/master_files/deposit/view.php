<div class="subheader">
	<div class="d-flex align-items-center">
		<div class="title mr-auto">
			<h3><i class="icon left la <?php echo $current_module['icon'] ?>"></i> <?php echo $current_module['title'] ?></h3>
		</div>
		<div class="subheader-tools">
			<a href="<?php echo site_url('deposit/show') ?>" class="btn btn-primary btn-raised btn-xs pill"><i class="icon ti-angle-left"></i> Back to List</a>
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
							<?php if ($roles['edit']) { ?>
							<li>
								<a href="<?php echo site_url('deposit/edit/'.$this->encrypter->encode($rec->depositID)) ?>" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit"><i class="la la-edit"></i></a>
							</li>
							<?php } ?>
							<?php if ($roles['delete'] && !$in_used) { ?>
							<li>
								<button name="cmddelete" id="cmddelete" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete" onclick="deleteRecord('<?php echo $this->encrypter->encode($rec->depositID); ?>');"><i class="la la-trash-o"></i></button>
							</li>
							<?php } ?>
							<?php if ($this->session->userdata('current_user')->isAdmin) { ?>
							<li>
								<button type="button" id="recordlog" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Record Logs" onclick="popUp('<?php echo site_url('logs/record_log/deposit/depositID/'.$this->encrypter->encode($rec->depositID).'/Deposit') ?>', 1000, 500)"><i class="la la-server"></i></button>
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
									<td class="data-title" style="width:120px" nowrap>Account:</td>
									<td class="data-input" style="width:400px" nowrap><?php echo $rec->accountName; ?></td>
									<td></td>
								</tr>
								<tr>
									<td class="data-title" nowrap>Amount:</td>
									<td class="data-input" nowrap><?php echo $rec->amount ?></td>
									<td></td>
								</tr>
								<tr>
									<td class="data-title" nowrap>Email:</td>
									<td class="data-input" nowrap><?php echo $rec->email ?></td>
									<td></td>
								</tr>
								<tr>
									<td class="data-title" nowrap>Remarks</td>
									<td class="data-input" nowrap><?php echo $rec->remarks; ?></td>
									<td></td>
								</tr>
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
