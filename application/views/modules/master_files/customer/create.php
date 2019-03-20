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
							<h4 class="head-text"> Add New <?php echo $current_module['module_label'] ?></h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo $controller_page.'/save'; ?>" >
						<div class="subtitle">
							<h5 class="title"><i class="icon left la la-user"></i> Customer Profile</h5>
						</div>
						<div class="table-row">
							<table class="table-form column-3">
								<tbody>
									<tr>
										<td class="form-label" width="12%">Last Name 
										</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" name="lname" id="lname" title="Last Name" >
										</td>
										<td class="form-label" width="12%">First Name </td>
										<td class="form-group form-input">
											<input type="text" class="form-control" name="fname" id="fname" title="First Name" >
											
										</td>
										<td class="form-label" width="12%">Middle Name</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" name="mname" id="mname" >
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						
						
						<div class="table-row">
							<table class="table-form">
								<tbody>
									<tr>
										<td class="form-label" width="12%">Birth Date<span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control datepicker" id="bday" name="bday" data-toggle="datetimepicker" data-target="#bday" title="Birth Date" required>
										</td>
										<td class="form-label" width="12%">Gender<span class="asterisk">*</span></td>
										<td class="form-group form-input" width="21.33%">
											<select class="form-control" id="gender" name="gender" required>
												<option value="">&nbsp;</option>
												<option value="M">Male</option>
												<option value="F">Female</option>
											</select>
										</td>
										<td class="form-label" width="12%">Contact No.</td>
										<td class="form-group form-input" width="21.33%">
											<input type="number" class="form-control" name="contactNo" id="contactNo" title="Contact No.">
										</td>
									</tr>

									<tr>
										<td class="form-label" width="12%">Company Name</td>
										<td class="form-group form-input" width="21.33%">
											<input type="text" class="form-control" name="companyName" id="companyName" >
										</td>
										<td class="form-label">Credit Limit</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" name="creditLimit" id="creditLimit" title="Credit Limit" onkeypress="return isNumber(event);">
										</td>
										<td class="d-xxl-none"></td>
									</tr>
								<!-- 	<tr>
										<td class="form-label">Credit Limit</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" name="creditLimit" id="creditLimit" title="Credit Limit" onkeypress="return isNumber(event);">
										</td>
										<td class="form-label"></td>
										<td class="form-group form-input">
											
										</td>
										<td class="d-xxl-none"></td>
									</tr> -->

									
								</tbody>
							</table>
						</div>
						<div class="subtitle">
							<h5 class="title"><i class="icon left la la-user-plus"></i> Address</h5>
						</div>
						<div class="table-row">
							<table class="table-form">
								<tbody>
									<tr>
										<td class="form-label" width="12%">Province</td>
										<td class="form-group form-input" width="21.33%">
											<select id="provinceID" name="provinceID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Province">
												<option value="">&nbsp;</option>
												<?php 
												$this->db->where('countryID', 58); //Philippines
												$provinces = $this->db->get('provinces')->result();
												foreach($provinces as $province){
													?>
													<option value="<?php echo $province->provinceID?>" <?php if($province->provinceID == $provinceID){echo "selected";}?>><?php echo $province->province?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label" width="12%">City / Town</td>
										<td class="form-group form-input" width="21.33%">
											<select id="cityID" name="cityID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="City">
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
										<td class="form-label" width="12%">Barangay</td>
										<td class="form-group form-input" width="21.33%">
											<select id="barangayID" name="barangayID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Barangay">
												<option value="">&nbsp;</option>
											</select>
										</td>
										
									</tr>
									<tr>
										<td class="form-label">Street</td>
										<td class="form-group form-input">
											<input type="text" class="form-control" name="streetNo" id="streetNo" title="Street">
										</td>
										<td class="form-label"></td>
										<td class="form-group form-input">
											
										</td>
										<td class="d-xxl-none"></td>
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

<!-- Details -->
<div class="modal  fade" id="details_list_modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			
			<div class="modal-header">
				<h4 class="modal-title">Details List</h4>
			</div>
			<div class="modal-body">
				<div class="table-row" id="details_list">
					<table class="table-view">
						<tbody>
							<tr>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-danger btn-raised pill" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script>

	$(document).ready(function(){

		$('#provinceID').change(function() {
			get_cities();
		});

		$('#cityID').change(function() {
			get_barangays();
		});
	});


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
				window.location = '<?php echo $controller_page.'/show'; ?>';
			}
		});

	});


	<?php 
    echo "\n";
    $parameters = array();
    echo $this->htmlhelper->get_json_select('get_provinces', $parameters, site_url('generic_ajax/get_provinces'), 'provinceID', '');
    
    echo "\n";
    $parameters = array('provinceID');
    echo $this->htmlhelper->get_json_select('get_cities', $parameters, site_url('generic_ajax/get_cities'), 'cityID', '');
    
    echo "\n";
    $parameters = array('cityID');
    echo $this->htmlhelper->get_json_select('get_barangays', $parameters, site_url('generic_ajax/get_barangays'), 'barangayID', '');

    ?>



</script>