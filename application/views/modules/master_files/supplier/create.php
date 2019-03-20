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
							<h4 class="head-text">Add <?php echo $current_module['module_label'] ?></h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo site_url("supplier/save") ?>">
						<div class="table-row">
							<table class="table-form column-3">
								<tbody>
									<tr>
										<td class="form-label" width="120px" nowrap>Name <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="400px" nowrap><input type="text" class="form-control" name="suppName" id="suppName" title="Name" required></td>
										<td class="form-label" width="120px" nowrap>Contact No <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="400px" nowrap>
											<input type="text" class="form-control" name="contactNo" id="contactNo" title="Contact No" required></td>
										<td class="form-label" ></td>

									</tr>

									<tr>
										<td class="form-label" width="120px" nowrap>Contact Person <span class="asterisk">*</span></td>
										<td class="form-group form-input" width="400px" nowrap>
											<input type="text" class="form-control" name="contactperson" id="contactperson" title="Contact Person" required>
										</td>

										<td class="form-label" width="120px" nowrap>Email Address </td>
										<td class="form-group form-input" width="400px" nowrap>
											<input type="email" class="form-control" name="email" id="email" title="Email Address" >
										</td>
										<td class="form-label" ></td>
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
		                                  		<option value="<?php echo $province->provinceID?>" <?php if($province->provinceID == $provinceID){echo "selected";}?>><?php echo $province->province?></option>
			                                  <?php }?>
			                                </select>
		                              	</td>

		                              	<td class="form-label">City/Town</td>
		                              	<td class="form-group form-input">
		                                 	<select id="currentCityID" name="cityID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Current City" >
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
		                              	<td class="form-label" ></td>


									</tr>

									<tr>

		                              	<td class="form-label">Barangay</td>
		                              	<td class="form-group form-input">
		                                	<select id="currentBarangayID" name="barangayID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Current Barangay" >
		                                  		<option value="">&nbsp;</option>
		                                	</select>
		                              	</td>

										<td class="form-label" width="120px" nowrap>Street No </td>
										<td class="form-group form-input" width="400px" nowrap><input type="text" class="form-control" name="streetNo" id="streetNo" title="Street No" ></td>
										<td class="form-label" ></td>
	                              	</tr>

		                           

								</tbody>
							</table>
						</div>
						<div class="form-sepator solid"></div>
						<div class="form-group mb-0">
							<button class="btn btn-primary btn-raised pill" type="button" name="cmdSave" id="cmdSave">
							Save
							</button>
							<input type="button" id="cmdCancel" class="btn btn-outline-danger btn-raised pill" value="Cancel"/>
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
	        $.post("<?php echo $controller_page ?>/check_duplicate", { suppName: $('#suppName').val() },
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
		    	  window.location = '<?php echo site_url('supplier/show') ?>';
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
