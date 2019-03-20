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
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo $controller_page.'/update'; ?>" >
						<input type="hidden" id="<?php echo $pfield ?>" name="<?php echo $pfield ?>" value="<?php echo $this->encrypter->encode($rec->$pfield); ?>"/>
						<div class="table-form column-3">
							<table class="table-form">
								
								<tbody>
									<tr>
										<td class="form-label" width="120px" nowrap>Name <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="400px" nowrap>
											<input type="text" class="form-control" name="suppName" id="suppName" title="Name" value="<?php echo $rec->suppName;?>" required>
										</td>
										<td class="form-label" width="120px" nowrap>Contact No <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="400px" nowrap>
											<input type="text" class="form-control" name="contactNo" id="contactNo" title="Contact No" value="<?php echo $rec->contactNo;?>" required>
										</td>

									</tr>

									<tr>
										<td class="form-label" width="120px" nowrap>Contact Person <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="400px" nowrap>
											<input type="text" class="form-control" name="contactperson" id="contactperson" title="Contact Person" value="<?php echo $rec->contactperson;?>" required>
										</td>

										<td class="form-label" width="120px" nowrap>Email Address</td>
										<td class="form-group form-input" width="400px" nowrap>
											<input type="email" class="form-control" name="email" id="email" title="Email Address" value="<?php echo $rec->email;?>" >
										</td>
										
									</tr>

		                              <tr>

		                              	<td class="form-label">Province</td>
		                              	<td class="form-group form-input">
		                                	<select id="currentProvinceID" name="provinceID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Current Province" >
		                                  		<option value="">&nbsp;</option>
				                                  <?php 
				                                    $this->db->where('countryID', 58);
				                                  	$provinces = $this->db->get('provinces')->result();
				                                  	foreach($provinces as $province){
				                                  ?>
		                                  		<option value="<?php echo $province->provinceID?>" <?php if($province->provinceID == $rec->provinceID){echo "selected";}?>><?php echo $province->province?></option>
			                                  <?php }?>
			                                </select>
		                              	</td>

		                              	<td class="form-label">City/Town</td>
		                              	<td class="form-group form-input">
		                                 	<select id="currentCityID" name="cityID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Current City" >
		                                  		<option value="">&nbsp;</option>
				                                  <?php 
				                                    $this->db->where('provinceID', $rec->provinceID);
				                                  	$cities = $this->db->get('cities')->result();
				                                  	foreach($cities as $city){
				                                  ?>
			                                  	<option value="<?php echo $city->cityID?>" <?php if($city->cityID == $rec->cityID){echo "selected";}?>><?php echo $city->city?></option>
			                                  <?php }?>
			                                </select>
		                              	</td>


									</tr>

									<tr>

		                              	<td class="form-label">Barangay </td>
		                              	<td class="form-group form-input">
		                                	<select id="currentBarangayID" name="barangayID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Current Barangay" >
			                                  <?php 
			                                    $this->db->where('cityID', $rec->cityID);
			                                  	$barangays = $this->db->get('barangays')->result();
			                                  	foreach($barangays as $barangay){
			                                  ?>
			                                  	<option value="<?php echo $barangay->barangayID?>" <?php if($barangay->barangayID == $rec->barangayID){echo "selected";}?>><?php echo $barangay->barangay?></option>
			                                  <?php }?>
		                                	</select>
		                              	</td>
		                              	
										<td class="form-label" width="120px" nowrap>Street No </td>
										<td class="form-group form-input" width="400px" nowrap><input type="text" class="form-control" name="streetNo" id="streetNo" title="Street No" value="<?php echo $rec->streetNo;?>" ></td>
	                              	</tr>

									<tr>
										<td class="form-label" nowrap>Status <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<select id="status" name="status" class="form-control" title="Status" >
												<option value="" selected>&nbsp;</option>
												<option value="1" <?php if ($rec->status==1) { echo "selected"; } ?>>Active</option>
												<option value="0" <?php if ($rec->status==0) { echo "selected"; } ?>>Inactive</option>
											</select>
										</td>
										<td class="d-xxl-none" colspan="3"></td>
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
				window.location = '<?php echo $controller_page.'/show' ?>';
			}
		});

	});

	
	$('#currentProvinceID').change(function() {
		get_current_cities();
	});

	$('#currentCityID').change(function() {
		get_current_barangays();
	});

	<?php 
		

	    echo "\n";
	    $parameters = array('currentProvinceID');
	    echo $this->htmlhelper->get_json_select('get_current_cities', $parameters, site_url('generic_ajax/get_cities'), 'currentCityID', '');

	    echo "\n";
	    $parameters = array('currentCityID');
	    echo $this->htmlhelper->get_json_select('get_current_barangays', $parameters, site_url('generic_ajax/get_barangays'), 'currentBarangayID', '');


	?>

</script>