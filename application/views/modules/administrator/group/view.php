<div class="subheader">
	<div class="d-flex align-items-center">
		<div class="title mr-auto">
			<h3><i class="icon left la <?php echo $current_module['icon'] ?>"></i> <?php echo $current_module['title'] ?></h3>
		</div>
		<div class="subheader-tools">
			<a href="<?php echo site_url('group') ?>" class="btn btn-xs btn-primary btn-raised btn-sm pill"><i class="icon ti-angle-left"></i> Back to List</a>
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
							<h4 class="head-text">View <?php echo $current_module['module_label'] ?></h4>
						</div>
					</div>
					<div class="card-head-tools">
						<ul class="tools-list">
							<?php if ($roles['edit']) {?>
								<li>
									<a href="<?php echo site_url('group/edit/'.$this->encrypter->encode($rec->groupID)); ?>" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit"><i class="la la-edit"></i></a>
								</li>
							<?php } ?>
							<?php if ($roles['delete'] && !$in_used) {?>
								<li>
									<button name="cmddelete" id="cmddelete" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete" onclick="deleteRecord('<?php echo $this->encrypter->encode($rec->groupID); ?>');"><i class="la la-trash-o"></i></button>
								</li>
							<?php } ?>
							<?php if ($this->session->userdata('current_user')->isAdmin) {?>
								<li>
									<button type="button" id="recordlog" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Record Logs" onclick="popUp('<?php echo site_url('logs/record_log/usergroups/groupID/'.$this->encrypter->encode($rec->groupID).'/User&Group') ?>', 1000, 500)"><i class="la la-server"></i></button>
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
									<td class="data-title" width="150">Group:</td>
									<td class="data-input"><?php echo $rec->groupName; ?></td>
								</tr>
								<tr>
									<td class="data-title">Description:</td>
									<td class="data-input"><?php echo $rec->groupDesc; ?></td>
								</tr>
								<tr>
									<td class="data-title">Status:</td>
									<td class="data-input">
										<?php 
										if ($rec->rstatus == 1) {
											echo "<span class='badge badge-pill badge-success'>Active</span>";
										} else {
											echo "<span class='badge badge-pill badge-danger'>Inactive</span>";
										}
										?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<?php 
					$this->db->where('groupID', $rec->groupID);
						// $this->db->where('rstatus', 1);
					$usergrouproles = $this->db->get('usergrouproles')->result();
					$roles[] = array();
					foreach($usergrouproles as $r) {
						$roles[] = $r->roleName;
					}

					?>
					<div class="form-sepator solid"></div>
					<div class="subtitle">
						<h5 class="title"><i class="icon left la la-user"></i> MODULE PRIVILEGES</h5>
					</div>

					<!-- Added new data here -->
					<form name="frmPrivileges" id="frmPrivileges" method="POST" action="<?php echo site_url("group/update_roles") ?>">
						<input type="hidden" name="groupID" id="groupID" value="<?php echo $this->encrypter->encode($rec->groupID); ?>" />
						<?php 
						$all_sub = array();
						foreach($modules as $mod=>$submod) { 
							if ($mod != "Administrator") {
								?>
								<div class="row">
									<div class="col-12" style="margin-top: 20px; float: left;   margin: 3px; padding: 30px; border: 1px solid #ebebeb; border-radius: 0px;">
										<span style="font-weight: bold; margin: 5px;"><i class="icon left la <?php echo $submod['main']['icon'] ?>"></i> <?php echo strtoupper($submod['main']['title']) ?> MODULE</span><br/>

										<br/>
										<?php 
										if (!empty($submod['sub'])) {
											foreach($submod['sub'] as $sub=>$subcon) {
												// var_dump($subcon);
												?>

												<div class="col-3" style="float: left; width: 300px; height: auto; min-height: 210px;  margin: 3px; padding: 5px; background-color: #ffffff; ">
													<span style="font-size: 15px; font-weight: bold; margin-left: 2px;" title="CHECK ALL" ><i class="icon left la <?php echo $subcon['icon'] ?>"></i>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($subcon['title']) ?></span>

													<div class="checkbox" style="margin-bottom: 3px;">
														<label>
															<input type="checkbox" id="<?php echo strtolower(str_replace(" ", "_", $sub)) ?>" name="<?php echo str_replace(" ", "_", $sub)?>" value="1" onclick="check_all('<?php echo strtolower(str_replace(" ", "_", $sub)) ?>')" />  <?php echo $subcon['description'] ?>
														</label>
													</div>


													<br/>
													<?php 
													if (!empty($subcon['roles'])) {
														foreach($subcon['roles'] as $role) {
															$all_sub[] = $sub;
															?>
															<div class="checkbox" style="padding-bottom: 10px; font-weight: bold;" nowrap>
																<label style="font-weight: bold;"><input type="checkbox" class="<?php echo strtolower(str_replace(" ", "_", $sub)) ?>" name="roles[]" value="<?php echo $role ?>" <?php if (in_array($role, $roles)) echo "checked"  ?> /> <?php echo $role ?></label><br/>
															</div>
															<?php 
														}
													}
													?>
												</div>
												<?php 
											}
										}
										?>
									</div>
								</div>
							<?php }
						}
						?>
						<br />
						<button id="cmdUpdate" class="btn btn-xs btn-primary btn-raised pill" name="cmdUpdate">Update</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>




<script>

function check_all(id)
{
	if ($('#'+id).is(":checked")) {
		$('.'+id).attr('checked', true);
	} else {
		$('.'+id).attr('checked', false);
	}
}
</script>