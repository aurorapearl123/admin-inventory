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
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo site_url("user/save") ?>" >
						<div class="subtitle">
							<h5 class="title"><i class="icon left la la-user"></i> User Profile</h5>
						</div>
						<div class="table-row">
							<table class="table-form column-3">
								<tbody>
									<tr>
										<td class="form-label" width="12%">Last Name <span class="asterisk">*</span>
										</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" name="lastName" id="lastName" title="Last Name" required>
										</td>
										<td class="form-label" width="12%">First Name <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control" name="firstName" id="firstName" title="First Name" required>
											<div class="validation-text d-none">
												<span class="text-danger">Please choose correct email.</span>
											</div>
										</td>
										<td class="form-label" width="12%">Middle Name</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" name="middleName" id="middleName" >
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="form-sepator solid"></div>
						<div class="subtitle">
							<h5 class="title"><i class="icon left la la-user-plus"></i> User Account</h5>
						</div>
						<div class="table-row">
							<table class="table-form">
								<tbody>
									<tr>
										<td class="form-label" width="12%">ID Number<span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control" name="empNo" id="empNo" title="ID Number" required>
										</td>
										<td class="form-label" width="12%">Username<span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control" name="userName" id="userName" title="Username" required>
										</td>
										<td class="d-xxl-none"></td>
									</tr>
									<tr>
										<td class="form-label">Password<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="password" class="form-control" name="userPswd" id="userPswd" title="Password" required>
										</td>
										<td class="form-label">Re-Password <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="password" class="form-control" name="rePswd" id="rePswd" title="Re-Password" required>
										</td>
										<td class="d-xxl-none"></td>
									</tr>
									<tr>
										<td class="form-label">User Group<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<select id="groupID" name="groupID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="User Group" required>
												<option value="" selected>&nbsp;</option>
												<?php 
				                                $this->db->order_by('groupName', 'asc');
				                                $results = $this->db->get('usergroups')->result();
				                                foreach($results as $res){
				                                  ?>
												<option value="<?php echo $res->groupID?>"><?php echo $res->groupName ?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label">Company</td>
										<td class="form-group form-input">
											<select id="companyID" name="companyID" class="form-control" data-live-search="true" liveSearchNormalize="true" onchange="get_branches();">
												<option value="" selected>&nbsp;</option>
												<?php 
					                                $this->db->order_by('companyName', 'asc');
					                                $results = $this->db->get('companies')->result();
					                                foreach($results as $res){
				                                  ?>
												<option value="<?php echo $res->companyID?>"><?php echo $res->companyName ?></option>
												<?php }?>
											</select>
										</td>
										<td class="d-xxl-none"></td>
									</tr>
									<tr>
										<td class="form-label">Branch</td>
										<td class="form-group form-input">
											<select id="branchID" name="branchID" class="form-control" data-live-search="true" liveSearchNormalize="true" onchange="get_departments();">
												<option value="" selected>&nbsp;</option>
												<?php 
					                                $this->db->order_by('branchName', 'asc');
					                                $results = $this->db->get('branches')->result();
					                                foreach($results as $res){
					                                  ?>
												<option value="<?php echo $res->branchID?>"><?php echo $res->branchName ?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label">Department</td>
										<td class="form-group form-input">
											<select id="deptID" name="deptID" class="form-control" onchange=""  title="Department" >
												<option value="" selected>&nbsp;</option>
												<?php           
													$this->db->order_by('deptName','asc');
													$results = $this->db->get('departments')->result();
													foreach($results as $res){
													  ?>
												<option value="<?php echo $res->deptID ?>"><?php echo $res->deptCode ?></option>
												<?php }?>
											</select>
										</td>
										<td class="d-xxl-none"></td>
									</tr>
									<tr>
										<td class="form-label">Status</td>
										<td class="form-group form-input">
											<select name="status" id="status" class="form-control" required>
												<option value="1">Active</option>
												<option value="0">Inactive</option>
											</select>
										</td>
										<td class="form-label">User Admin</td>
										<td class="form-group form-input">
											<div class="checkbox">
												<label>
												<input type="checkbox" name="isAdmin" id="isAdmin" value="1"> &nbsp;
												</label>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="form-sepator solid"></div>
						<div class="form-group mb-0">
							<button class="btn btn-xs btn-primary btn-raised pill" type="button" name="cmdSave" id="cmdSave">
							Save
							</button>
							<input type="button" id="cmdCancel" class="btn btn-xs btn-outline-danger btn-raised pill" value="Cancel"/>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#createNewUserBtn').click(function(){
	  // check password
	  if ($('#userPswd').val() == $('#rePswd').val()) {
	    $.post(site_url+"/user/check_duplicate", { userName: $('#userName').val() },
	      function(data){
	        if (parseInt(data)) {
	        // duplicate
	        alert("Error: Username is already exist!");
	      } else { 
	        // no duplicate
	        document.getElementById('frmEntry').submit();
	      }
	    }, "text");
	  } else {
	    alert("Passwords does not matched!");
	  }
	})
</script>
<script>
	$('#cmdSave').click(function(){
		if (check_fields()) {
			if ($('#userPswd').val() == $('#rePswd').val()) {
	        	$('#cmdSave').attr('disabled','disabled');
	        	$('#cmdSave').addClass('loader');
	            $.post("<?php echo $controller_page ?>/check_duplicate", { userName: $('#userName').val() },
	              function(data){
	                if (parseInt(data)) {
	                	$('#cmdSave').removeClass("loader");
	                	$('#cmdSave').removeAttr('disabled');
	                  	// duplicate
	                  	swal("Duplicate","Record is already exist!","warning");
	                } else {
	                	// submit
	                   	$('#frmEntry').submit();
	                }
	              }, "text");
			} else {
		      swal("Invalid","Passwords does not matched!","warning");
		    }
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
		    	  window.location = '<?php echo site_url('user') ?>';
		      }
		    });
	    
	});
	
	function clear_here()
	{
		$('#branchID').empty();
		$('#branchID').val('');
		$('#branchID').selectpicker('refresh');
	}
	
	
	<?php 
		echo "\n";
		$parameters = array('companyID');
		echo $this->htmlhelper->get_json_select('get_branches', $parameters, site_url('generic_ajax/get_code_branches'), 'branchID', '');
		
		echo "\n";
		$parameters = array('branchID');
		echo $this->htmlhelper->get_json_select('get_departments', $parameters, site_url('generic_ajax/get_code_departments'), 'deptID', '');
		
		?>
</script>
