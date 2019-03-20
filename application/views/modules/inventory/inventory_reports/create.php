<div class="subheader">
	<div class="d-flex align-items-center">
		<div class="title mr-auto">
			<h3><i class="icon left la <?php echo $current_module['icon'] ?>"></i> Employees</h3>
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
							<h4 class="head-text">Add Employee</h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo $controller_page ?>/save" enctype="multipart/form-data">
						<input type="hidden" id="sameAddress" name="sameAddress" value="0">
						<div class="subtitle">
							<h5 class="title"><i class="icon left la la-user-secret"></i> Personal Information</h5>
						</div>
						<div class="table-row">
							<table class="table-form column-3">
								<tbody>
									<tr>
										<td class="form-label" width="120px">Last Name<span class="asterisk">*</span></td>
										<td class="form-group form-input" width="200px">
											<input type="text" class="form-control" id="lname" name="lname" title="Last name" required>
										</td>
										<td class="form-label" width="120px">First Name<span class="asterisk">*</span></td>
										<td class="form-group form-input" width="200px">
											<input type="text" class="form-control" id="fname" name="fname" title="First Name" required>
										</td>
										<td class="form-label" width="120px">Middle Name</td>
										<td class="form-group form-input" width="200px">
											<input type="text" class="form-control" id="mname" name="mname">
										</td>
									</tr>
									<tr>
										<td class="form-label">Nickname</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="nickname" name="nickname">
										</td>
										<td class="form-label">Suffix</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="suffix" name="suffix">
										</td>
										<td class="form-label">Title</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="title" name="title">
										</td>
									</tr>
									<tr>
										<td class="form-label">
											Sex<span class="asterisk">*</span>
										</td>
										<td class="form-group form-input">
											<select class="form-control" id="sex" name="sex" title="Sex" required>
												<option value="" selected>&nbsp;</option>
												<option value="M">Male</option>
												<option value="F">Female</option>
											</select>
										</td>
										<td class="form-label">Birth Date<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control datepicker" id="birthDate" name="birthDate" data-toggle="datetimepicker" data-target="#birthDate" title="Birth Date" required>
										</td>
										<td class="form-label">Birth Place</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="birthPlace" name="birthPlace">
										</td>
									</tr>
									<tr>
										<td class="form-label">Civil Status<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<select id="civilStatus" name="civilStatus" class="form-control" title="Civil Status" required>
												<option value="" selected>&nbsp;</option>
												<option value="Single">Single</option>
												<option value="Married">Married</option>
												<option value="Widowed">Widowed</option>
												<option value="Divorced">Divorced</option>
											</select>
										</td>
										<td class="form-label">Nationality<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<select id="nationality" name="nationality" class="form-control" data-live-search="true" liveSearchNormalize="true" title="nationality" required>
												<option value="">&nbsp;</option>
												<?php 
													$countries = $this->db->get('countries')->result();
													foreach($countries as $country){
													?>
												<option value="<?php echo $country->nationality?>" <?php if($country->nationality == 'Filipino'){echo "selected";}?>><?php echo $country->nationality?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label">Languages</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="languages" name="languages" value="Visayan, Tagalog, English">
										</td>
									</tr>
									<tr>
										<td class="form-label">Telephone No.</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="telephone" name="telephone">
										</td>
										<td class="form-label">Mobile No.</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="mobile" name="mobile">
										</td>
										<td class="form-label">Biometric ID</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="biometricID" name="biometricID">
										</td>
									</tr>
									<tr>
										<td class="form-label">Work Email</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="workEmail" name="workEmail" onclick="generateEmail();">
										</td>
										<td class="form-label">Personal Email</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="personalEmail" name="personalEmail">
										</td>
										<td class="form-label">Religion</td>
										<td class="form-group form-input">
												
											<select class="form-control" id="religionID" name="religionID" data-live-search="true" liveSearchNormalize="true">
												<option value="" selected>&nbsp;</option>
												<?php 
												$religions = $this->db->get('religions')->result();
												foreach ($religions as $religion) {
												?>
												<option value="<?php echo $religion->religionID?>"><?php echo $religion->religion?></option>
												<?php } ?>
											</select>	
										</td>
									</tr>
									<tr>
										<th class="form-subtle" colspan="4"><i class="icon left la la-street-view"></i> Current Address</th>
									</tr>
									<tr>
										<td class="form-label">Country<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<select id="currentCountryID" name="currentCountryID" class="form-control" data-live-search="true" liveSearchNormalize="true" required>
												<option value="">&nbsp;</option>
												<?php 
													$countries = $this->db->get('countries')->result();
													foreach($countries as $country){
													?>
												<option value="<?php echo $country->countryID?>" <?php if($country->countryID == $countryID){echo "selected";}?>><?php echo $country->country?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label">Province<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<select id="currentProvinceID" name="currentProvinceID" class="form-control" data-live-search="true" liveSearchNormalize="true" required>
												<option value="">&nbsp;</option>
												<?php 
													$this->db->where('countryID', $countryID);
													$provinces = $this->db->get('provinces')->result();
													foreach($provinces as $province){
													?>
												<option value="<?php echo $province->provinceID?>" <?php if($province->provinceID == $provinceID){echo "selected";}?>><?php echo $province->province?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label">City/Town<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<select id="currentCityID" name="currentCityID" class="form-control" data-live-search="true" liveSearchNormalize="true" required>
												<option value="">&nbsp;</option>
												<?php 
													$this->db->where('provinceID', $provinceID);
													$cities = $this->db->get('cities')->result();
													foreach($cities as $city){
													?>
												<option value="<?php echo $city->cityID?>"><?php echo $city->city?></option>
												<?php }?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="form-label">Barangay<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<select id="currentBarangayID" name="currentBarangayID" class="form-control" data-live-search="true" liveSearchNormalize="true" required>
												<option value="">&nbsp;</option>
											</select>
										</td>
										<td class="form-label">Street</td>
										<td class="form-group form-input" colspan="3">
											<input type="text" class="form-control" id="currentStreet" name="currentStreet">
										</td>
									</tr>
									<tr class="permAddress">
										<th class="form-subtle" colspan="4"><i class="icon left la la-street-view"></i> Permanent Address</th>
									</tr>
									<tr class="permAddress">
										<td class="form-label">Country</td>
										<td class="form-group form-input">
											<select id="provinceCountryID" name="provinceCountryID" class="form-control" data-live-search="true" liveSearchNormalize="true">
												<option value="">&nbsp;</option>
												<?php 
													$countries = $this->db->get('countries')->result();
													foreach($countries as $country){
													?>
												<option value="<?php echo $country->countryID?>" <?php if($country->countryID == 58){echo "selected";}?>><?php echo $country->country?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label">Province</td>
										<td class="form-group form-input">
											<select id="provinceProvinceID" name="provinceProvinceID" class="form-control" data-live-search="true" liveSearchNormalize="true">
												<option value="">&nbsp;</option>
												<?php 
													$this->db->where('countryID', $countryID);
													$provinces = $this->db->get('provinces')->result();
													foreach($provinces as $province){
													?>
												<option value="<?php echo $province->provinceID?>"><?php echo $province->province?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label">City/Town</td>
										<td class="form-group form-input">
											<select id="provinceCityID" name="provinceCityID" class="form-control" data-live-search="true" liveSearchNormalize="true">
												<option value="">&nbsp;</option>
											</select>
										</td>
									</tr>
									<tr class="permAddress">
										<td class="form-label">Barangay</td>
										<td class="form-group form-input">
											<select id="provinceBarangayID" name="provinceBarangayID" class="form-control" data-live-search="true" liveSearchNormalize="true">
												<option value="">&nbsp;</option>
											</select>
										</td>
										<td class="form-label">Street</td>
										<td class="form-group form-input" colspan="3">
											<input type="text" class="form-control" id="provinceStreet" name="provinceStreet">
										</td>
									</tr>
									<tr>
										<td class="form-label">Same with current address</td>
										<td class="form-group form-input">
											<input type="checkbox" onclick="sameAddressFunc();">
										</td>
										<td class="form-label">
										</td>
										<td class="form-group form-input" colspan="3">
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="form-sepator solid"></div>
						<div class="subtitle">
							<h5 class="title"><i class="icon left ti-id-badge"></i> Identification Numbers</h5>
						</div>
						<div class="table-row">
							<table class="table-form">
								<tbody>
									<tr>
										<td class="form-label" width="12%">
											<label for="tinno">TIN</label>
										</td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control" id="tin" name="tin" maxlength="15">
										</td>
										<td class="form-label" width="12%">
											<label for="sssno">SSS No.</label>
										</td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control" id="sssNo" name="sssNo" maxlength="12">
										</td>
										<td class="d-xxl-none"></td>
									</tr>
									<tr>
										<td class="form-label">
											<label for="philno">PhilHealth No.</label>
										</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="philhealthNo" name="philhealthNo" maxlength="14">
										</td>
										<td class="form-label">
											<label for="pagibigno">Pagibig No.</label>
										</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="pagibigNo" name="pagibigNo" maxlength="14">
										</td>
										<td class="d-xxl-none"></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="form-sepator solid"></div>
						<div class="form-group mb-0">
							<button type="button" class="btn btn-primary btn-raised pill" name="cmdSave" id="cmdSave">Save</button>
							<input class="btn btn-outline-danger btn-raised pill" name="cmdCancel" type="button" id="cmdCancel" value=" Cancel " />
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
	        $.post("<?php echo $controller_page ?>/check_duplicate", { empNo: $('#empNo').val() },
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
		    	  window.location = '<?php echo site_url('employee/show') ?>';
		      }
		    });
	});
	
	$('#currentCountryID').change(function() {
		get_current_provinces();
	});
	
	$('#currentProvinceID').change(function() {
		get_current_cities();
	});
	
	$('#currentCityID').change(function() {
		get_current_barangays();
	});
	
	$('#provinceCountryID').change(function() {
		get_permanent_provinces();
	});
	
	$('#provinceProvinceID').change(function() {
		get_permanent_cities();
	});
	
	$('#provinceCityID').change(function() {
		get_permanent_barangays();
	});
	
	$('#branchID').change(function() {
		get_departments();
		get_plantilla();
	});
	
	$('#deptID').change(function() {
		get_sections();
		get_plantilla();
	});
	
	$('#divisionID').change(function() {
		get_plantilla();
	});
	
	$('#cost_branchID').change(function() {
	  get_cost_departments();
	});
	
	$('#cost_deptID').change(function() {
	  get_cost_sections();
	});
	
	<?php 
		echo "\n";
		$parameters = array('currentCountryID');
		echo $this->htmlhelper->get_json_select('get_current_provinces', $parameters, site_url('generic_ajax/get_provinces'), 'currentProvinceID', '');
		
		echo "\n";
		$parameters = array('currentProvinceID');
		echo $this->htmlhelper->get_json_select('get_current_cities', $parameters, site_url('generic_ajax/get_cities'), 'currentCityID', '');
		
		echo "\n";
		$parameters = array('currentCityID');
		echo $this->htmlhelper->get_json_select('get_current_barangays', $parameters, site_url('generic_ajax/get_barangays'), 'currentBarangayID', '');
		
		echo "\n";
		$parameters = array('provinceCountryID');
		echo $this->htmlhelper->get_json_select('get_permanent_provinces', $parameters, site_url('generic_ajax/get_provinces'), 'provinceProvinceID', '');
		
		echo "\n";
		$parameters = array('provinceProvinceID');
		echo $this->htmlhelper->get_json_select('get_permanent_cities', $parameters, site_url('generic_ajax/get_cities'), 'provinceCityID', '');
		
		echo "\n";
		$parameters = array('provinceCityID');
		echo $this->htmlhelper->get_json_select('get_permanent_barangays', $parameters, site_url('generic_ajax/get_barangays'), 'provinceBarangayID', '');
		
		echo "\n";
		$parameters = array('companyID');
		echo $this->htmlhelper->get_json_select('get_branches', $parameters, site_url('generic_ajax/get_code_branches'), 'branchID', '');
		
		echo "\n";
		$parameters = array('branchID');
		echo $this->htmlhelper->get_json_select('get_departments', $parameters, site_url('generic_ajax/get_departments'), 'deptID', '');
		
		echo "\n";
		$parameters = array('deptID');
		echo $this->htmlhelper->get_json_select('get_sections', $parameters, site_url('generic_ajax/get_sections'), 'divisionID', '');
		
		echo "\n";
		$parameters = array('branchID','deptID','divisionID');
		echo $this->htmlhelper->get_json_select('get_plantilla', $parameters, site_url('generic_ajax/get_plantilla'), 'jobPositionID', '');
		
		echo "\n";
		$parameters = array('branchID');
		echo $this->htmlhelper->get_json_select('get_cost_departments', $parameters, site_url('generic_ajax/get_departments'), 'cost_deptID', '');
		
		echo "\n";
		$parameters = array('deptID');
		echo $this->htmlhelper->get_json_select('get_cost_sections', $parameters, site_url('generic_ajax/get_sections'), 'cost_divisionID', '');
		?>
	
	
	
	
	function sameAddressFunc() {
	var address_switch = $('#sameAddress').val();
	console.log(address_switch);
	if (address_switch == 0) {
	  address_switch = 1
	
	  $('.permAddress').hide();
	  $('#sameAddress').val(1);
	} else {
	  address_switch = 0
	  $('.permAddress').show();
	  $('#sameAddress').val(0);
	
	}
	
	
	}
	
	
	function generateEmail() {
	
	
	  var fname = $('#fname').val();
	
	  var a = fname.split(" ");
	  var b = "";
	  $.each(a, function(i, val) {
	    var c = val.charAt(0);
	    b += c;
	  });
	
	  var name = b + $('#lname').val();
	  var email = name.replace(/\s+/g, '_').toLowerCase() + '@lhprime.com';
	
	  $('#workEmail').val(email);
	
	}
	
</script>