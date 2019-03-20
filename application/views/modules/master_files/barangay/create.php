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
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo site_url("barangay/save") ?>">
						<input type="hidden" name="countryID" id="country" value="92" />
						<div class="table-row">
							<table class="table-form column-3">
								<tbody>
									<tr>
										<td class="form-label" style="width:120px" nowrap>Province <span class="asterisk">*</span></td>
										<td class="form-group form-input" style="width:400px" nowrap>
											<select class="form-control" id="provinceID" name="provinceID" data-live-search="true" liveSearchNormalize="true" onchange="getCities()" title="Province" required>
												<option value="" selected>&nbsp;</option>
												<?php 
												$recs = $this->db->get('provinces')->result();
												foreach($recs as $rec){
												?>
												<option value="<?php echo $rec->provinceID?>"><?php echo $rec->province?></option>
												<?php } ?>
											</select>
											<button  onclick="popUp('<?php echo site_url('province/create_popup') ?>',900,400)">Add</button>
										</td>
										<td class="d-xxl-none"></td>
									</tr>
									<tr>
										<td class="form-label" nowrap>City <span class="asterisk">*</span></td>
										<td class="form-group form-input" nowrap>
											<select class="form-control" id="cityID" name="cityID" data-live-search="true" liveSearchNormalize="true" title="City" required>
												<option value="" selected>&nbsp;</option>
												<?php 
												$recs = $this->db->get('cities')->result();
												foreach($recs as $rec){
												?>
												<option value="<?php echo $rec->cityID?>"><?php echo $rec->city?></option>
												<?php } ?>
											</select>
										</td>
										<td class="d-xxl-none"></td>
									</tr>
									<tr>
										<td class="form-label" nowrap>Barangay <span class="asterisk">*</span></td>
										<td class="form-group form-input">
											<input type="text" class="form-control" name="barangay" id="barangay" title="Barangay" required>
										</td>
										<td class="d-xxl-none"></td>
									</tr>
									<tr>
										<td class="form-label">Remarks </td>
										<td class="form-group form-input">
											<textarea type="text" class="form-control" name="remarks" id="remarks"></textarea>
										</td>
										<td class="d-xxl-none"></td>
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
<?php
echo "\n";
$parameters = array('provinceID');
echo $this->htmlhelper->get_json_select('getCities', $parameters, site_url('generic_ajax/get_cities'), 'cityID', '');

$parameters = array();
echo $this->htmlhelper->get_json_select2('getProvince', $parameters, site_url('province/getProvince'), 'provinceID', 'activeID') ;
?>

	$('#cmdSave').click(function(){
		if (check_fields()) {
	    	$('#cmdSave').attr('disabled','disabled');
	    	$('#cmdSave').addClass('loader');
	        $.post("<?php echo $controller_page ?>/check_duplicate", { barangay: $('#barangay').val() },
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
		    	  window.location = '<?php echo site_url('barangay/show') ?>';
		      }
		    });
	    
	});
</script>
