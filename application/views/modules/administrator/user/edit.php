<div class="subheader">
	<div class="d-flex align-items-center">
		<div class="title mr-auto">
			<h3><i class="icon left la <?php echo $current_module['icon'] ?>"></i> <?php echo $current_module['title'] ?></h3>
		</div>
		<div class="subheader-tools"></div>
	</div>
</div>
<div class="content">
	<div class="row">
		<div class="col-12">
			<div class="card-box">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="head-text">Edit <?php echo $current_module['module_label'] ?></h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo site_url("user/update") ?>">
						<input type="hidden" name="userID" id="userID" value="<?php echo $rec->userID ?>" />
						<div class="table-row">
							<table class="table-form">
								<tbody>
									<tr>
										<td class="form-label" width="12%">Last Name<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $rec->lastName ?>" required>
										</td>
										<td class="form-label">First Name<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo $rec->firstName ?>" required>
										</td>
										<td class="form-label">Middle Name<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="middleName" name="middleName" value="<?php echo $rec->middleName ?>" required>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="form-sepator solid"></div>
						<div class="subtitle">
							<h5 class="title"><i class="icon left la la-user"></i> User Account</h5>
						</div>
						<div class="table-row">
							<table class="table-form">
								<tbody>
									<tr>
										<td class="form-label" width="12%">ID Number:</td>
										<td class="form-group form-input"><input type="text" name="empNo" id="empNo" value="<?php echo $rec->empNo ?>" class="form-control"/></td>
										<td></td>
									</tr>
									<tr>
										<td class="form-label">Username:</td>
										<td class="form-group form-input" width="21.33%"><input type="text" name="userName" id="userName" value="<?php echo $rec->userName ?>" class="form-control" readonly/></td>
									</tr>
									<!-- User Group -->
									<tr>
										<td class="form-label">User Group: </td>
										<td class="form-group form-input">
											<select name="groupID" id="groupID" title="User Group" class="form-control" required>
												<option value="">&nbsp;</option>
												<?php 
					                                $this->db->order_by('groupName', 'asc');
					                                $results = $this->db->get('usergroups')->result();
					                                foreach($results as $res){
					                                  ?>
												<option value="<?php echo $res->groupID?>" <?php if($res->groupID == $rec->groupID){echo "selected";}?>><?php echo $res->groupName ?></option>
												<?php }?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="form-label" width="12%">Company:</td>
										<td class="form-group form-input" width="21.33%">
											<select id="companyID" name="companyID" class="form-control" data-live-search="true" liveSearchNormalize="true" onchange="get_branches();">
												<option value="">&nbsp;</option>
												<?php 
					                                $this->db->order_by('companyName', 'asc');
					                                $results = $this->db->get('companies')->result();
					                                foreach($results as $res){
					                                  ?>
												<option value="<?php echo $res->companyID?>" <?php if($res->companyID == $rec->companyID){echo "selected";}?>><?php echo $res->companyName ?></option>
												<?php }?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="form-label" width="12%">Branch:</td>
										<td class="form-group form-input" width="21.33%">
											<select id="branchID" name="branchID" class="form-control" data-live-search="true" liveSearchNormalize="true" onchange="get_departments();">
												<option value="">&nbsp;</option>
												<?php 
					                                $this->db->order_by('branchName', 'asc');
					                                $results = $this->db->get('branches')->result();
					                                foreach($results as $res){
					                                  ?>
												<option value="<?php echo $res->branchID?>" <?php if($res->branchID == $rec->branchID){echo "selected";}?>><?php echo $res->branchName ?></option>
												<?php }?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="form-label" width="12%">Department:</td>
										<td class="form-group form-input" width="21.33%">
											<select id="deptID" name="deptID" class="form-control" data-live-search="true" liveSearchNormalize="true">
												<option value="">&nbsp;</option>
												<?php 
					                                $this->db->where('status', 1); 
					                                $this->db->order_by('deptName', 'asc');
					                                $departments = $this->db->get('departments')->result();
					                                foreach($departments as $dept){
					                                  ?>
												<option value="<?php echo $dept->deptID?>" <?php if($dept->deptID == $rec->deptID){echo "selected";}?>><?php echo $dept->deptName ?></option>
												<?php }?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="form-label">User Admin:</td>
										<td class="form-group form-input">
											<div class="checkbox">
												<label>
												<input type="checkbox"  name="isAdmin" id="isAdmin" value="1" <?php if ($rec->isAdmin) echo "checked" ?>> &nbsp;
												</label>
											</div>
										</td>
									</tr>
									<tr>
										<td class="form-label" width="12%">Status:</td>
										<td class="form-group form-input" width="21.33%">
											<select name="status" id="status" class="form-control" required>
												<option value="1" <?php if ($rec->status == "1") echo "selected"; ?> >Active</option>
												<option value="0" <?php if ($rec->status == "0") echo "selected"; ?>>Inactive</option>
											</select>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<?php 
							$preferences = explode(',', $rec->preferences);
							?>
						<!-- Module preferences start here -->
						<div class="form-sepator solid"></div>
						<div class="subtitle">
							<h5 class="title"><i class="icon left la la-file-code-o"></i> Module Preferences</h5>
						</div>
						<div class="data-view">
							<table class="view-table">
								<tbody>
									<tr>
										<?php 
											$ctr = 0;
											
											foreach($modules as $mod=>$details) {
											    if ($mod != "Administrator") {
											    $ctr++;
											      if ($ctr % 5 == 0) {
											          echo '</tr>';
											          echo '<tr>';
											      }
											    ?>
										<td class="data-input border-0 w-25">
											<div class="switch">
												<label>
												<input type="checkbox" name="modules[]" value="<?php echo $mod ?>" <?php if (in_array($mod, $preferences)) echo "checked"  ?> />
												<?php echo $mod ?>
												</label>
											</div>
										</td>
										<?php } 
											}
											 ?>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- Module preferences end here -->
						<div class="form-sepator solid"></div>
						<div class="form-group mb-0">
							<button class="btn btn-xs btn-primary btn-raised pill" type="button" name="cmdSave" id="cmdSave">
							Save
							</button>
							<input type="button" id="cmdCancel" class="btn btn-xs btn-outline-danger btn-raised pill" value="Cancel" onclick=""/>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#cmdSave').click(function(){
		if (check_fields()) {
			$('#cmdSave').attr('disabled','disabled');
	    	$('#cmdSave').addClass('loader');
	       	$('#frmEntry').submit();
		}
	});
	
	
	function check_fields()
	{
		 var valid = true;
		 var req_fields = "";
		 
		 $('#frmEntry [required]').each(function(){
		    if($(this).val()=='' ) {
		    	req_fields += "<br/>" + $(this).attr('title');
			    valid = false;
		    } 
		 })
		 
		 if (!valid) {
		 	swal("Required Fields",req_fields,"warning");
		 }
		 
		 return valid;
	}
	
	$('#cmdCancel').click(function(){
		swal({
		      title: "Are you sure?",
		      text: "",
		      icon: "warning",
		      showCancelButton: true,
		      confirmButtonColor: '#3085d6',
		      cancelButtonColor: '#d33',
		      confirmButtonText: 'Yes',
		      cancelButtonText: 'No'
		    })
		    .then((willDelete) => {
		      if (willDelete.value) {
		    	  window.location = '<?php echo site_url('user/view/').$rec->userID ?>';
		      }
		    });
	    
	});
	
	<?php 
		echo "\n";
		$parameters = array('companyID');
		echo $this->htmlhelper->get_json_select('get_branches', $parameters, site_url('generic_ajax/get_code_branches'), 'branchID', '');
		
		echo "\n";
		$parameters = array('branchID');
		echo $this->htmlhelper->get_json_select('get_departments', $parameters, site_url('generic_ajax/get_code_departments'), 'deptID', '');
		
		?>
	
</script>
