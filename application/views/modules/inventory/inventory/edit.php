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
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo $controller_page ?>/update">
						<input type="hidden" name="<?php echo $pfield?>" id="<?php echo $pfield?>" value="<?php echo $this->encrypter->encode($rec->$pfield) ?>" />
						<div class="subtitle">
							<h5 class="title"><i class="icon left la la-user-secret"></i> Personal Information</h5>
						</div>
						<div class="table-row">
							<table class="table-form column-3">
								<tbody>
									<tr>
										<td class="form-label">ID Number<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="empNo" name="empNo" value="<?php echo $rec->empNo?>" title="ID Number" readonly required>
										</td>
										<td colspan="4"></td>
									</tr>
									<tr>
										<td class="form-label" width="120px">Last Name<span class="asterisk">*</span></td>
										<td class="form-group form-input" width="200px">
											<input type="text" class="form-control" id="lname" name="lname" value="<?php echo $rec->lname?>" title="Last name" required>
										</td>
										<td class="form-label" width="120px">First Name<span class="asterisk">*</span></td>
										<td class="form-group form-input" width="200px">
											<input type="text" class="form-control" id="fname" name="fname" value="<?php echo $rec->fname?>" title="First Name" required>
											<div class="validation-text d-none">
												<span class="text-danger">Please choose correct email.</span>
											</div>
										</td>
										<td class="form-label" width="120">Middle Name</td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control" id="mname" name="mname" value="<?php echo $rec->mname?>">
										</td>
									</tr>
									<tr>
										<td class="form-label">Nickname</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="nickname" name="nickname" value="<?php echo $rec->nickname?>">
										</td>
										<td class="form-label">Suffix</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="suffix" name="suffix" value="<?php echo $rec->suffix?>">
										</td>
										<td class="form-label">Title</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="title" name="title" value="<?php echo $rec->title?>">
										</td>
									</tr>
									<tr>
										<td class="form-label">
											Sex<span class="asterisk">*</span>
										</td>
										<td class="form-group form-input">
											<select class="form-control" id="sex" name="sex" title="Sex" required>
												<option value="">&nbsp;</option>
												<option value="M" <?php echo ($rec->sex == 'M') ? 'selected' : ''?>>Male</option>
												<option value="F" <?php echo ($rec->sex == 'F') ? 'selected' : ''?>>Female</option>
											</select>
										</td>
										<td class="form-label">Birth Date<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control datepicker" id="birthDate" name="birthDate" value="<?php echo date('M d, Y', strtotime($rec->birthDate))?>" data-toggle="datetimepicker" data-target="#birthDate" title="Birth Date" required>
										</td>
										<td class="form-label">Birth Place</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="birthPlace" name="birthPlace" value="<?php echo $rec->birthPlace?>">
										</td>
									</tr>
									<tr>
										<td class="form-label">Civil Status<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<select id="civilStatus" name="civilStatus" class="form-control" title="Civil Status" required>
												<option value="">&nbsp;</option>
												<option value="Single" <?php echo ($rec->civilStatus == 'Single') ? 'selected' : ''?>>Single</option>
												<option value="Married" <?php echo ($rec->civilStatus == 'Married') ? 'selected' : ''?>>Married</option>
												<option value="Widowed" <?php echo ($rec->civilStatus == 'Widowed') ? 'selected' : ''?>>Widowed</option>
												<option value="Divorced" <?php echo ($rec->civilStatus == 'Divorced') ? 'selected' : ''?>>Divorced</option>
											</select>
										</td>
										<td class="form-label">Nationality<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<select id="nationality" name="nationality" class="form-control" data-live-search="true" liveSearchNormalize="true" title="nationality">
												<option value="">&nbsp;</option>
												<?php 
													$countries = $this->db->get('countries')->result();
													foreach($countries as $country){
													?>
												<option value="<?php echo $country->nationality?>" <?php if($country->nationality == $rec->nationality){echo "selected";}?>><?php echo $country->nationality?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label">Languages</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="languages" name="languages" value="<?php echo $rec->languages?>">
										</td>
									</tr>
									<tr>
										<td class="form-label">Telephone No.</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="telephone" name="telephone" value="<?php echo $rec->telephone?>">
										</td>
										<td class="form-label">Mobile No.</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $rec->mobile?>">
										</td>
										<td class="form-label">Biometric ID</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="biometricID" name="biometricID" value="<?php echo $rec->biometricID ?>">
										</td>
									</tr>
									<tr>
										<td class="form-label">Work Email</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="workEmail" name="workEmail" value="<?php echo $rec->workEmail?>">
										</td>
										<td class="form-label">Personal Email</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="personalEmail" name="personalEmail" value="<?php echo $rec->personalEmail?>">
										</td>
										<td class="form-label">Religion</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="religion" name="religion" title="Religion">
										</td>
									</tr>
									<tr>
										<th class="form-subtle" colspan="4"><i class="icon left la la-street-view"></i> Current Address</th>
									</tr>
									<tr>
										<td class="form-label">Country<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<select id="currentCountryID" name="currentCountryID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Current Country">
												<option value="">&nbsp;</option>
												<?php 
													$countries = $this->db->get('countries')->result();
													foreach($countries as $country){
													?>
												<option value="<?php echo $country->countryID?>" <?php if($country->countryID == $rec->currentCountryID){echo "selected";}?>><?php echo $country->country?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label">Province<span class="asterisk">*</span></td>
										<td class="form-group form-input" >
											<select id="currentProvinceID" name="currentProvinceID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Current Province">
												<option value="">&nbsp;</option>
												<?php 
													$this->db->where('countryID', $rec->currentCountryID);
													$provinces = $this->db->get('provinces')->result();
													foreach($provinces as $province){
													?>
												<option value="<?php echo $province->provinceID?>" <?php if($province->provinceID == $rec->currentProvinceID){echo "selected";}?>><?php echo $province->province?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label">City/Town<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<select id="currentCityID" name="currentCityID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Current City">
												<option value="">&nbsp;</option>
												<?php 
													$this->db->where('provinceID', $rec->currentProvinceID);
													$cities = $this->db->get('cities')->result();
													foreach($cities as $city){
													?>
												<option value="<?php echo $city->cityID?>" <?php if($city->cityID == $rec->currentCityID){echo "selected";}?>><?php echo $city->city?></option>
												<?php }?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="form-label">Barangay<span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<select id="currentBarangayID" name="currentBarangayID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Current Barangay">
												<option value="">&nbsp;</option>
												<?php 
													$this->db->where('cityID', $rec->currentCityID);
													$barangays = $this->db->get('barangays')->result();
													foreach($barangays as $barangay){
													?>
												<option value="<?php echo $barangay->barangayID?>" <?php if($barangay->barangayID == $rec->currentBarangayID){echo "selected";}?>><?php echo $barangay->barangay?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label">Street</td>
										<td class="form-group form-input" colspan="3">
											<input type="text" class="form-control" id="currentStreet" name="currentStreet" value="<?php echo $rec->currentStreet?>">
										</td>
									</tr>
									<tr>
										<th class="form-subtle" colspan="4"><i class="icon left la la-street-view"></i> Permanent Address</th>
									</tr>
									<tr>
										<td class="form-label">Country</td>
										<td class="form-group form-input">
											<select id="provinceCountryID" name="provinceCountryID" class="form-control" data-live-search="true" liveSearchNormalize="true">
												<option value="">&nbsp;</option>
												<?php 
													$countries = $this->db->get('countries')->result();
													foreach($countries as $country){
													?>
												<option value="<?php echo $country->countryID?>" <?php if($country->countryID == $rec->provinceCountryID){echo "selected";}?>><?php echo $country->country?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label">Province</td>
										<td class="form-group form-input">
											<select id="provinceProvinceID" name="provinceProvinceID" class="form-control" data-live-search="true" liveSearchNormalize="true">
												<option value="">&nbsp;</option>
												<?php 
													$this->db->where('countryID', $rec->provinceCountryID);
													$provinces = $this->db->get('provinces')->result();
													foreach($provinces as $province){
													?>
												<option value="<?php echo $province->provinceID?>" <?php if($province->provinceID == $rec->provinceProvinceID){echo "selected";}?>><?php echo $province->province?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label">City/Town</td>
										<td class="form-group form-input">
											<select id="provinceCityID" name="provinceCityID" class="form-control" data-live-search="true" liveSearchNormalize="true">
												<option value="">&nbsp;</option>
												<?php 
													$this->db->where('provinceID', $rec->provinceProvinceID);
													$cities = $this->db->get('cities')->result();
													foreach($cities as $city){
													?>
												<option value="<?php echo $city->cityID?>" <?php if($city->cityID == $rec->provinceCityID){echo "selected";}?>><?php echo $city->city?></option>
												<?php }?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="form-label">Barangay</td>
										<td class="form-group form-input" >
											<select id="provinceBarangayID" name="provinceBarangayID" class="form-control" data-live-search="true" liveSearchNormalize="true">
												<option value="">&nbsp;</option>
												<?php 
													$this->db->where('cityID', $rec->provinceCityID);
													$barangays = $this->db->get('barangays')->result();
													foreach($barangays as $barangay){
													?>
												<option value="<?php echo $barangay->barangayID?>" <?php if($barangay->barangayID == $rec->provinceBarangayID){echo "selected";}?>><?php echo $barangay->barangay?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label">Street</td>
										<td class="form-group form-input" colspan="3">
											<input type="text" class="form-control" id="provinceStreet" name="provinceStreet" value="<?php echo $rec->provinceStreet?>">
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
										<td class="form-label" width="12%">TIN</td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control" id="tin" name="tin" value="<?php echo $rec->tin?>">
										</td>
										<td class="form-label" width="12%">SSS No.</td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control" id="sssNo" name="sssNo" value="<?php echo $rec->sssNo?>">
										</td>
										<td class="d-xxl-none"></td>
									</tr>
									<tr>
										<td class="form-label">PhilHealth No.</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="philhealthNo" name="philhealthNo" value="<?php echo $rec->philhealthNo?>">
										</td>
										<td class="form-label">Pagibig No.</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" id="pagibigNo" name="pagibigNo" value="<?php echo $rec->pagibigNo?>">
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
	
	    $('#frmEntry').submit();
	  }
	});
	
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
	   	  window.location = '<?php echo $controller_page ?>/view/<?php echo $this->encrypter->encode($rec->$pfield)?>';
	     }
	   });
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
		?>
	
</script>

