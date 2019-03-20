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
							<h4 class="head-text">New <?php echo $current_module['module_label'] ?></h4>
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
										<td class="form-label"  width="12%" >
											Company<span class="asterisk">*</span>
										</td>
										<td class="form-group form-input" width="21.33%">
											<select id="companyID" name="companyID" class="form-control" onchange="get_branches(); "  title="Company" required readonly>
												
												<?php           
												$this->db->where('companyID', 1);
												$this->db->order_by('companyName','asc');
												$results = $this->db->get('companies')->result();
												foreach($results as $res){
													?>
													<option value="1" selected><?php echo $res->companyName ?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label" valign="top" width="12%" colspan="6" rowspan="6">
											<div valign="top" style="overflow-y: auto; height:250px;"><!-- scroll start -->
												<table width="100%" class="table table-striped hover" align=center" style="margin-top: 0px">
													<thead>
														<tr>
															<td align="left" style=" border-left:1px solid #EAF2F8" nowrap><span style="margin-left: 10px;">JOB TITLES</span></td>
															<td align="center" style=" border-left:1px solid #EAF2F8" nowrap>Occupied</td>
															<td align="center" style=" border-left:1px solid #EAF2F8" nowrap>Vacant</td>

															<td align="center" style=" border-left:1px solid #EAF2F8" nowrap>POSITIONS</td>
															<td align="center" style=" border-left:1px solid #EAF2F8;border-right:1px solid #EAF2F8" nowrap>Applicants</td>
														</tr>
													</thead>
													<?php 
													$this->db->where('status >',0);
													$jobs = $this->db->get('job_titles')->result();

													foreach ($jobs as $job) {
														?>

														<?php 

														$this->db->select('job_positions.companyID');
														$this->db->select('job_positions.branchID');
														$this->db->select('job_positions.deptID');
														$this->db->select('job_positions.divisionID');
														$this->db->select('companies.companyCode');
														$this->db->select('branches.branchName');
														$this->db->select('departments.deptName');
														$this->db->select('divisions.divisionName');
														$this->db->select('employee_types.employeeType');


														$this->db->from('job_positions');

														$this->db->join('companies', 'job_positions.companyID=companies.companyID', 'left');
														$this->db->join('branches', 'job_positions.branchID=branches.branchID', 'left');
														$this->db->join('departments', 'job_positions.deptID=departments.deptID', 'left');
														$this->db->join('divisions', 'job_positions.divisionID=divisions.divisionID', 'left');
														$this->db->join('employee_types', 'job_positions.employeeTypeID=employee_types.employeeTypeID', 'left');
														
														$this->db->where('job_positions.status >', 0);
														$this->db->where('job_positions.jobTitleID', $job->jobTitleID);
														$jobpositions = $this->db->get()->result_array();
                       // var_dump($jobs);

														?>

														<tr class="positions-tr" id="pos_<?php echo $job->jobTitleID ?>" data-id="<?php echo $job->jobTitleID ?>" data-list='<?php echo json_encode($jobpositions) ?>'>
															<!-- vacant 1, occupied 2 -->
															<td align="left" class="positions-td" style="border-left:1px solid #EAF2F8" nowrap>
																<?php echo $job->code.' - '.$job->jobTitle?>
															</td>
															<td align="center" style="border-left:1px solid #EAF2F8" nowrap>
																<?php 
																$this->db->where('jobTitleID',$job->jobTitleID);
																$this->db->where('status',2);
																echo $rows = $this->db->count_all_results('job_positions');
																?>
															</td>
															<td align="center" style="border-left:1px solid #EAF2F8" nowrap>
																<?php 
																$this->db->where('jobTitleID',$job->jobTitleID);
																$this->db->where('status',1);
																echo $rows = $this->db->count_all_results('job_positions');
																?>
															</td>
															<td align="center" style="border-left:1px solid #EAF2F8;border-right:1px solid #EAF2F8" nowrap>
																<?php 
																$this->db->where('jobTitleID',$job->jobTitleID);
																$this->db->where('status >',0);
																echo $rows = $this->db->count_all_results('job_positions');
																$ttl += $rows;
																?>
															</td>
															<td></td>
														</tr>
													<?php } ?>
													<tr>
														<td align="right" style="border-left:1px solid #EAF2F8" nowrap>TOTAL : </td>
														<td align="center" style="border-left:1px solid #EAF2F8;border-right:1px solid #EAF2F8" nowrap>
														</td>
														<td align="center" style="border-left:1px solid #EAF2F8;border-right:1px solid #EAF2F8" nowrap>
														</td>
														<td align="center" style="border-left:1px solid #EAF2F8;border-right:1px solid #EAF2F8" nowrap>
															<?php echo $ttl;?>
														</td>
														<td></td>
													</tr>
												</table>
											</div><!-- scroll end -->
										</td>
										<td valign="top" class="form-group form-input" width="21.33%" >
											
										</td>
										<td class="form-label" width="12%">
										</td>
										<td class="form-group form-input" width="21.33%">
										</td>
									</tr>
									<tr>
										<td class="form-label">
											Branch<span class="asterisk">*</span>
										</td>
										<td class="form-group form-input">
											<select id="branchID" name="branchID" class="form-control" onchange="get_departments(); "  title="Branch" required>
												<option value="" selected>&nbsp;</option>
												<?php           
												$this->db->where('companyID', 1);
												$this->db->order_by('branchName','asc');
												$results = $this->db->get('branches')->result();
												foreach($results as $res){
													?>
													<option value="<?php echo $res->branchID ?>" <?php if ($rec->branchID == $res->branchID) echo "selected" ?>><?php echo $res->branchCode ?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label" width="12%">
										</td>
										<td class="form-group form-input" width="21.33%">
											
										</td>
										<td class="form-label" width="12%">
										</td>
										<td class="form-group form-input" width="21.33%">
										</td>
									</tr>
									<tr>
										<td class="form-label">
											Department
										</td>
										<td class="form-group form-input">
											<select id="deptID" name="deptID" class="form-control" onchange="get_sections()"  title="Department">
												<option value="" selected>&nbsp;</option>
												<?php           
												$this->db->where('status', 1);
												$this->db->order_by('deptName','asc');
												$results = $this->db->get('departments')->result();
												foreach($results as $res){
													?>
													<option value="<?php echo $res->deptID ?>" <?php if ($rec->deptID == $res->deptID) echo "selected" ?>><?php echo $res->deptCode ?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label" width="12%">
										</td>
										<td class="form-group form-input" width="21.33%">
											
										</td>
										<td class="form-label" width="12%">
										</td>
										<td class="form-group form-input" width="21.33%">
										</td>
									</tr>

									<tr>
										<td class="form-label">
											Division
										</td>
										<td class="form-group form-input">
											<select id="divisionID" name="divisionID" class="form-control" data-live-search="true" liveSearchNormalize="true"  title="Section" onchange="get_plantilla();">
												<option value="" selected>&nbsp;</option>
												<?php           
												$this->db->where('status', 1);
												$this->db->order_by('divisionName','asc');
												$results = $this->db->get('divisions')->result();
												foreach($results as $res){
													?>
													<option value="<?php echo $res->divisionID ?>" <?php if ($rec->divisionID == $res->divisionID) echo "selected" ?>><?php echo $res->divisionName ?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label" width="12%">
										</td>
										<td class="form-group form-input" width="21.33%">
											
										</td>
										<td class="form-label" width="12%">
										</td>
										<td class="form-group form-input" width="21.33%">
										</td>
									</tr>

									<tr>
										<td class="form-label">
											Employment Type<span class="asterisk">*</span>
										</td>
										<td class="form-group form-input">

											<select id="employeeTypeID" name="employeeTypeID" class="form-control" data-live-search="true" liveSearchNormalize="true"  title="Employment Type" onchange="getJobPositions();">
												<option value="" selected>&nbsp;</option>
												<?php           
												$this->db->where('status',1); 
												$this->db->order_by('rank','asc');
												$this->db->order_by('employeeType','asc');
												$results = $this->db->get('employee_types')->result();
												foreach($results as $res){
													?>
													<option value="<?php echo $res->employeeTypeID ?>" <?php if ($rec->employeeTypeID == $res->employeeTypeID) echo "selected" ?>><?php echo $res->employeeType ?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label" width="12%">
										</td>
										<td class="form-group form-input" width="21.33%">
											
										</td>
										<td class="form-label" width="12%">
										</td>
										<td class="form-group form-input" width="21.33%">
										</td>
									</tr>
									<tr>
										<td class="form-label">
											Job Position<span class="asterisk">*</span>
										</td>
										<td class="form-group form-input">
											<select id="jobPositionID" name="jobPositionID" class="form-control" data-live-search="true" liveSearchNormalize="true"  title="Employment Type" onchange="">
												<option value="" selected>&nbsp;</option>
												<?php           
												$this->db->select('job_positions.*');
												$this->db->select('job_titles.jobTitle');
												$this->db->from('job_positions');
												$this->db->join('job_titles','job_positions.jobTitleID=job_titles.jobTitleID','left');
												$this->db->where('job_positions.status', 1);
												$this->db->order_by('job_positions.rank','asc');
												$results = $this->db->get()->result();
												foreach($results as $res){
													?>
													<option value="<?php echo $res->jobPositionID ?>" <?php if ($rec->jobPositionID == $res->jobPositionID) echo "selected" ?>><?php echo $res->jobTitle ?></option>
												<?php }?>
											</select>
										</td>
										<td class="form-label" width="12%">
										</td>
										<td class="form-group form-input" width="21.33%">
											
										</td>
										<td class="form-label" width="12%">
										</td>
										<td class="form-group form-input" width="21.33%">
										</td>
									</tr>
									<tr>
										<td class=""><br><br></td>
									</tr>
									<tr>
										<td class="form-label" width="12%" valign="top" nowrap>
											Qualifications<span class="asterisk">*</span>
										</td>
										<td class="form-group form-input" width=""  colspan="5" rowspan="3">
											<textarea class="form-control" name="qualifications" id="qualifications" rows="5" maxlength="50" style="width:500px"><?php echo $rec->qualifications ?></textarea>
										</td>
										
										
									</tr>
									<tr></tr>
									<tr></tr>
									<tr>
										<td class="form-label" valign="top" width="12%">
											Remarks
										</td>
										<td class="form-group form-input" height="200" width="21.33%">
											<textarea style="" class="form-control" name="remarks" id="remarks"/><?php echo $rec->remarks ?></textarea>
										</td>
										<td class="form-label" width="12%">
										</td>
										<td class="form-group form-input" width="21.33%">
											
										</td>
										<td class="form-label" width="12%">
										</td>
										<td class="form-group form-input" width="21.33%">
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

</script>