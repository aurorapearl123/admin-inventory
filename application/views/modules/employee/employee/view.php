<div class="subheader">
	<div class="d-flex align-items-center">
		<div class="title mr-auto">
			<h3><i class="icon left la <?php echo $current_module['icon'] ?>"></i> Employee Profile </h3>
		</div>
		<div class="subheader-tools">
			<a href="<?php echo $controller_page?>/show" class="btn btn-primary btn-raised btn-sm pill"><i class="icon ti-angle-left"></i> Back to List</a>
		</div>
	</div>
</div>
<div class="content">
	<div class="row">
		<!-- --------------- Image Container - Left --------------- -->
		<div class="col-xl-3">
			<div class="card-box full-height">
				<div class="card-body" style="height:400px">
					<div class="card-user py-35">
						<div class="card-user-pic">
							<?php $image = "assets/img/main/profile-img.png";?>
							<img src="<?php echo base_url($image)?>" class="text-center rounded" alt="" width="40">
							<button type="button" class="btn bmd-btn-icon" data-toggle="modal" data-target="#modal1">
								<i class="la la-camera-retro"></i></a>
						</div>
						<div class="card-user-details mt-25">
							<span class="card-user-name"><?php echo $rec->fname.' '.$rec->lname?></span>
							<?php if ($employment->jobTitle) { ?>
							<span class="card-user-position d-block mt-10"><?php echo $employment->jobTitle?></span>
							<?php } else { ?>
							<span class="card-user-position d-block mt-10">&nbsp;</span>
							<?php } ?>
							<span class="card-user-position d-block">ID: <?php echo $rec->empNo?></span>
							<span class="card-user-position d-block">Status: 
							<?php 
								if($rec->status == 1){
									echo "<span class='badge badge-pill badge-success'>Active</span>";
								}elseif($rec->status == 0){
									echo "<span class='badge badge-pill badge-light'>Inactive</span>";
								}elseif($rec->status == 2){
									echo "<span class='badge badge-pill badge-info'>Retired</span>";
								}elseif($rec->status == 3){
									echo "<span class='badge badge-pill badge-dark'>Deceased</span>";
								}
								?>
							</span>
							<span class="card-user-position d-block mt-10">&nbsp;</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- --------------- Data Container - right --------------- -->
		<div class="col-xl-9">
			<div class="card-box" style="height:400px">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="head-text">EMPLOYEE INFORMATION</h4>
						</div>
					</div>
					<div class="card-head-tools">
						<ul class="tools-list">
							<?php if ($roles['edit']) {?>
							<li>
								<a href="<?php echo $controller_page ?>/edit/<?php echo $this->encrypter->encode($rec->$pfield)?>" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit"><i class="la la-edit"></i></a>
							</li>
							<?php } ?>
							<li>
								<button type="button" id="recordlog" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Record Logs" onclick="popUp('<?php echo site_url('logs/record_log/employees/'.$pfield.'/'.$this->encrypter->encode($rec->$pfield).'/Employee') ?>', 1000, 500)"><i class="la la-server"></i></button>
							</li>
						</ul>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<div class="subtitle">
								<h5 class="title"><i class="icon left la la-user-secret"></i> Personal Information</h5>
							</div>
							<div class="data-view">
								<table class="view-table">
									<tbody>
										<tr>
											<td class="data-title w-15">ID Number:</td>
											<td class="data-input w-30"><?php echo $rec->empNo?></td>
											<td class="data-title w-15">Biometric ID:</td>
											<td class="data-input w-30"><?php echo $rec->biometricID?></td>
										</tr>
										<tr>
											<td class="data-title">Full Name:</td>
											<td class="data-input"><?php echo $rec->fname.' '.$rec->lname.' '.$rec->mname.' '.$rec->suffix?></td>
											<td class="data-title">Title:</td>
											<td class="data-input"><?php echo $rec->title?></td>
										</tr>
										<tr>
											<td class="data-title">Nickname:</td>
											<td class="data-input"><?php echo $rec->nickname?></td>
											<td class="data-title">Sex:</td>
											<td class="data-input"><?php if($rec->sex == 'M'){echo "Male";}else{echo "Female";}?></td>
										</tr>
										<tr>
											<td class="data-title">Date of Birth:</td>
											<td class="data-input"><?php echo date('M d, Y',strtotime($rec->birthDate))?></td>
											<td class="data-title">Age</td>
											<td class="data-input">
											<?php 
												if($row->birthDate!="0000-00-00") {
												  echo (date('Y') - date('Y',strtotime($rec->birthDate))); 
												} else {
												  echo " -- "; 
												}
												?>
											</td>
										</tr>
										<tr>
											<td class="data-title">Place of Birth:</td>
											<td class="data-input"><?php echo $rec->birthPlace?></td>
											<td class="data-title">Nationality:</td>
											<td class="data-input"><?php echo $rec->nationality?></td>
										</tr>
										<tr>
											<td class="data-title">Language:</td>
											<td class="data-input"><?php echo $rec->languages?></td>
											<td class="data-title">Civil Status:</td>
											<td class="data-input"><?php echo $rec->civilStatus?></td>
										</tr>
										<tr>
											<td class="data-title">Remarks:</td>
											<td class="data-input" colspan="3"><?php echo $rec->remarks?></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="card-box">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<ul id="inner-tab" class="nav nav-tabs w-icon">
								<li class="nav-item" data-nav="1">
									<a class="nav-link active" href="#/">
									<i class="icon left la la-user"></i>
									<span class="nav-text">Employee Details</span>
									</a>
								</li>
								<li class="nav-item" data-nav="2">
									<a class="nav-link" href="#/">
									<i class="icon left la la-file-text"></i>
									<span class="nav-text">Credentials</span>
									</a>
								</li>
								<li class="nav-item" data-nav="3">
									<a class="nav-link" href="#/">
									<i class="icon left la la-briefcase"></i>
									<span class="nav-text">Employment Details</span>
									</a>
								</li>
								<li class="nav-item" data-nav="6">
									<a class="nav-link" href="#/">
									<i class="icon left la la-money"></i>
									<span class="nav-text">Salary & Wages</span>
									</a>
								</li>
								<li class="nav-item" data-nav="4">
									<a class="nav-link" href="#/">
									<i class="icon left la la-car"></i>
									<span class="nav-text">Leaves & Trip Tickets</span>
									</a>
								</li>
								<li class="nav-item" data-nav="5">
									<a class="nav-link" href="#/">
									<i class="icon left la la-calendar"></i>
									<span class="nav-text">Attendance & Shifts</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body content1 tab-content">
					<div class="subtitle">
						<h5 class="title"><i class="icon left la la-mobile-phone"></i> Contact Information</h5>
					</div>
					<div class="data-view">
						<table class="view-table">
							<tbody>
								<tr>
									<td class="data-title w-15">Telephone No:</td>
									<td class="data-input w-30"><?php echo $rec->telephone?></td>
									<td class="data-title w-15">Mobile No:</td>
									<td class="data-input w-30"><?php echo $rec->mobile?></td>
								</tr>
								<tr>
									<td class="data-title">Work Email:</td>
									<td class="data-input"><?php echo $rec->workEmail?></td>
									<td class="data-title">Personal Email:</td>
									<td class="data-input"><?php echo $rec->personalEmail?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="subtitle mt-20">
						<h5 class="title"><i class="icon left la la-street-view"></i> Current Address</h5>
					</div>
					<div class="data-view">
						<table class="view-table">
							<tbody>
								<tr>
									<td class="data-title w-15">House No. / Street:</td>
									<td class="data-input w-30"><?php echo $rec->currentStreet?></td>
									<td class="data-title w-15">Barangay:</td>
									<td class="data-input w-30"><?php echo $rec->currentBarangay?></td>
								</tr>
								<tr>
									<td class="data-title">City/Town:</td>
									<td class="data-input"><?php echo $rec->currentCity?></td>
									<td class="data-title">Province:</td>
									<td class="data-input"><?php echo $rec->currentProvince?></td>
								</tr>
								<tr>
									<td class="data-title">Country:</td>
									<td class="data-input"><?php echo $rec->currentCountry?></td>
									<td class="data-title">Zipcode:</td>
									<td class="data-input"><?php echo $rec->currentZipcode?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="subtitle mt-20">
						<h5 class="title"><i class="icon left la la-street-view"></i> Permanent Address</h5>
					</div>
					<div class="data-view">
						<table class="view-table">
							<tbody>
								<tr>
									<td class="data-title w-15">House No. / Street:</td>
									<td class="data-input w-30"><?php echo $rec->provinceStreet?></td>
									<td class="data-title w-15">Barangay:</td>
									<td class="data-input w-30"><?php echo $rec->provinceBarangay?></td>
								</tr>
								<tr>
									<td class="data-title">City/Town:</td>
									<td class="data-input"><?php echo $rec->provinceCity?></td>
									<td class="data-title">Province:</td>
									<td class="data-input"><?php echo $rec->provinceProvince?></td>
								</tr>
								<tr>
									<td class="data-title">Country:</td>
									<td class="data-input"><?php echo $rec->provinceCountry?></td>
									<td class="data-title">Zipcode:</td>
									<td class="data-input"><?php echo $rec->provinceZipcode?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="subtitle mt-20">
						<h5 class="title"><i class="icon left ti-id-badge"></i> Identification Numbers</h5>
					</div>
					<div class="data-view">
						<table class="view-table">
							<tbody>
								<tr>
									<td class="data-title w-15">TIN:</td>
									<td class="data-input w-30"><?php echo $rec->tin?></td>
									<td class="data-title w-15">SSS No:</td>
									<td class="data-input w-30"><?php echo $rec->sssNo?></td>
								</tr>
								<tr>
									<td class="data-title">Philhealth No:</td>
									<td class="data-input"><?php echo $rec->philhealthNo?></td>
									<td class="data-title">Pag-ibig No:</td>
									<td class="data-input"><?php echo $rec->pagibigNo?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var site_url = '<?php echo site_url() ?>';
	var empID = '<?php echo $rec->empID ?>';
	
	$(document).ready(function() {
	    showFamilyMembers();
	    showAttachments();
	    showMedical();
	
	    showIncentives();
	    showContributions();
	    showLoans();
	
	    $('#inner-tab .nav-item').click(function(){
	    	$('#inner-tab .nav-item').find('.nav-link').removeClass('active');
	    	$(this).find('.nav-link').addClass('active');
	
	    	contentNo = $(this).attr('data-nav');
	
	    	if (contentNo == 1) {
	    	    showFamilyMembers();
	    	    showAttachments();
	          showMedical();
	    	    
				$('.tab-content').hide();
				$('.content1').show();
	    	} else if (contentNo == 2) {
	    	    showEducation();
	    	    showEligibilities();
	    	    showExperiences();
	    	    showTrainings();
	    	    
	    	    $('.tab-content').hide();
				$('.content2').show();
	    	} else if (contentNo == 3) {
	    	    showEmployments();
	    	    
	    	    $('.tab-content').hide();
				$('.content3').show();
	    	} else if (contentNo == 4) {
	          // showLeavesTravels();
	          tm_load_records(empID, 'container_leave_credit');
	          $('.tab-content').hide();
	          $('.content4').show();
	      } else if (contentNo == 5) {
	
	        $('.tab-content').hide();
	          $('.content5').show();
	      } else if (contentNo == 6) {
	       showIncentives();
	       showContributions();
	       showLoans();
	    	    $('.tab-content').hide();
				$('.content6').show();
	      
	    	} else {
	    	    $('.content1').hide();
	    	}
	    });
	    
	    $(this).on({
	        click: function () {
	            $('#modal-attachment').modal('show');
	        },
	    }, '#btn-add-attachment');
	    
	    $(this).on({
	        click: function () {
	            $('#modal4').modal('show');
	            viewFamily($(this).attr('data-id'));
	        },
	    }, '.row-family');
	
	    $(this).on({
	      click: function () {
	        $('#modal-medical-edit').modal('show');
	        viewMedical($(this).attr('data-id'));
	      },
	    }, '.row-medical');
	    
	    $(this).on({
	        click: function () {
	            $('#modal-education-edit').modal('show');
	            viewEducation($(this).attr('data-id'));
	        },
	    }, '.row-education');
	    
	    $(this).on({
	        click: function () {
	            $('#modal-eligibility-edit').modal('show');
	            viewEligibility($(this).attr('data-id'));
	        },
	    }, '.row-eligibility');
	    
	    $(this).on({
	        click: function () {
	            $('#modal-experience-edit').modal('show');
	            viewExperience($(this).attr('data-id'));
	        },
	    }, '.row-experience');
	    
	    $(this).on({
	        click: function () {
	            $('#modal-training-edit').modal('show');
	            viewTraining($(this).attr('data-id'));
	        },
	    }, '.row-training');
	
	
	    $('.app_tr').on('click', function(){
	      var id = $(this).attr('data-id');
	      var table = $(this).attr('data-table');
	
	      console.log(id);
	      console.log(table);
	      $('#appointmentModal').modal('show');
	      var date2 = new Date($('.apptd1_'+id).text());
	      console.log(date2);
	      $('#migrationID').val(id);
	      $('#appointment3').val($('.apptd1_'+id).text());
	      $('#branchCode3').val($('.apptd2_'+id).text());
	      $('#departmentCode3').val($('.apptd3_'+id).text());
	      $('#sectionCode3').val($('.apptd4_'+id).text());
	      $('#employeeStatus3').val($('.apptd5_'+id).text());
	      $('#positionDescription3').val($('.apptd6_'+id).text());
	      $('#historySalary3').val($('.apptd7_'+id).text());
	      $('#status3').val($('.apptd8_'+id).text());
	      $('#remarks3').val($('.apptd9_'+id).text());
	                
	    });
	
	    $('#cmdSaveApp').click(function(){
	
	    $('#cmdSaveApp').attr('disabled','disabled');
	      $('#cmdSaveApp').addClass('loader');
	        $('#appointmentFrm').submit();
	  
	  });
	
	
	    $('#cmdDeleteApp').click(function(){
	      swal({
	        title: "Are you sure  you want to delete?",
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
	          window.location = '<?php echo site_url('employee/delete_old_app/'); ?>'+$('#migrationID').val()+'/'+$('#empID3').val();
	        }
	      });
	    });
	});
	
	function showEmployments() {
	    $.ajax({
	        url: '<?php echo site_url('employee/show_employments')?>',
	        data: { empID: '<?php echo $this->encrypter->encode($rec->empID)?>' },
	        type: 'POST',
	        dataType:'json',
	        success:function(response) {
	            if (response.status == '1') {
	                
	                list = '';
	                for (i = 0; i < response.records.length; i++) {
	                    record = response.records[i];
	                    if (record.status != 1) {
	                      // var url2 = "<?php// echo site_url('employment') ?>"+"/view/"+"<?php //echo $this->encrypter->encode($record->employmentID) ?>"
	                      // console.log(url2, 'ssssss');
	                      list += '<tr class="app_tr" data-id="'+record.employmentID+'" data-table="employments">';
	                      list += ' <td class="w-15"><span>'+record.dateAppointed+'</span></td>';
	                      list += ' <td class="w-10"><span>'+record.branchName+'</span></td>';
	                      list += ' <td class="w-10"><span>'+record.deptName+'</span></td>';
	                      list += ' <td class="w-10"><span>'+record.divisionName+'</span></td>';
	                      list += ' <td class="w-10"><span>'+record.employeeType+'</span></td>';
	                      list += ' <td class="w-10"><span>'+record.jobTitle+'</span></td>';
	                      list += ' <td class="w-10"><span>'+format_number(record.basicSalary)+'</span></td>';
	                      if (record.status == '1') {
	                        list += ' <td class="w-10"><span>Active</span></td>';
	                      } else if (record.status == '2') {
	                        list += ' <td class="w-10"><span>Transferred</span></td>';
	                      } else if (record.status == '3') {
	                        list += ' <td class="w-10"><span>Promotion</span></td>';
	                      } else if (record.status == '4') {
	                        list += ' <td class="w-10"><span>Demoted</span></td>';
	                      } else if (record.status == '5') {
	                        list += ' <td class="w-10"><span>Separated</span></td>';
	                      } else if (record.status == '6') {
	                        list += ' <td class="w-10"><span>Resigned</span></td>';
	                      } else if (record.status == '7') {
	                        list += ' <td class="w-10"><span>EOC</span></td>';
	                      } else if (record.status == '8') {
	                        list += ' <td class="w-10"><span>Salary Adjustment</span></td>';
	                      } else if (record.status == '9') {
	                        list += ' <td class="w-10"><span>Regularization</span></td>';
	                      }
	                      list += ' <td class="w-15"><span>'+record.remarks+'</span></td>';
	                      list += '</tr>';
	                    }
						
	                }
	
	                $('#appointments tbody').html(list);
	            } else if (response.status == '0') {
	                if (response.message) {
	                    alert(response.message);
	                }
	            } else {
	                alert(response.message, 1);
	            }
	        }, error:function(xhr) {
	        }
	    });
	
	}
	
	
	function clearFields(container) {
	    $(container+' :input').each(function() {
			this.value = "";
	    });
	}
	
	function check_fields(form)
	{
		 var valid = true;
		 var req_fields = "";
		 
		 $(form+' [required]').each(function(){
		    if($(this).val()=='' ) {
		    	req_fields += "<br/>" + $(this).attr('title');
			    valid = false;
		    } 
		 })
		 
		 if (!valid) {
		 	swal("Required Fields", req_fields,"warning");
		 }
		 
		 return valid;
	}
	
	function format_number(num) {
	    var rgx  = /(\d+)(\d{3})/;
	    
	    num += '';
	    x    = num.split('.');
	    x1   = x[0];
	    x2   = (x.length > 1) ? '.00' : '.00'; 
	    
	    while (rgx.test(x1)) {
	        x1 = x1.replace(rgx, '$1' + ',' + '$2');
	    } 
	    return (x1 + x2);
	}
	
	
	function tm_load_records(empID, display_area='') 
	{   
	  $.post(site_url+'leave_credit/emp_leave_credit_ro/'+empID,
	    function(data){
	      console.log(data);
	      $('#'+display_area).html(data);
	    }, "text");
	}
	
	
	function viewAppointment(id, table) {
	    $.ajax({
	        url: '<?php echo site_url('employee/view_appointment')?>',
	        data: { empID: '<?php echo $this->encrypter->encode($rec->empID)?>', table: table },
	        type: 'POST',
	        dataType:'json',
	        success:function(response) {
	            if (response.status == '1') {
	                
	                list = '';
	                if (table == "employments") {
	                  for (i = 0; i < response.records.length; i++) {
	                    record = response.records[i];
	                    if (record.status != 1) {
	                      list += '<tr class="" data-id="'+record.employmentID+'" data-table="employments">';
	                      list += ' <td><span>'+record.dateAppointed+'</span></td>';
	                      list += ' <td><span>'+record.branchName+'</span></td>';
	                      list += ' <td><span>'+record.deptName+'</span></td>';
	                      list += ' <td><span>'+record.divisionName+'</span></td>';
	                      list += ' <td><span>'+record.employeeType+'</span></td>';
	                      list += ' <td><span>'+record.jobTitle+'</span></td>';
	                      list += ' <td><span>'+format_number(record.basicSalary)+'</span></td>';
	                      if (record.status == '1') {
	                        list += ' <td><span>Active</span></td>';
	                      } else if (record.status == '2') {
	                        list += ' <td><span>Transferred</span></td>';
	                      } else if (record.status == '3') {
	                        list += ' <td><span>Promoted</span></td>';
	                      } else if (record.status == '4') {
	                        list += ' <td><span>Demoted</span></td>';
	                      } else if (record.status == '5') {
	                        list += ' <td><span>Separated</span></td>';
	                      } else if (record.status == '6') {
	                        list += ' <td><span>Resigned</span></td>';
	                      } else if (record.status == '7') {
	                        list += ' <td><span>EOC</span></td>';
	                      }
	                      list += ' <td><span>'+record.remarks+'</span></td>';
	                      list += '</tr>';
	                    }
	                  }
	                } else { //employee_appointments
	
	                }
	                $('#appointmentTbody').html(list);
	            } else if (response.status == '0') {
	                if (response.message) {
	                    alert(response.message);
	                }
	            } else {
	                alert(response.message, 1);
	            }
	        }, error:function(xhr) {
	        }
	    });
	}
	
	function showLogs() {
	    var startDate = $('#startDate').val();
	    var endDate = $('#endDate').val();
	    $.ajax({
	      url: "<?php echo site_url('biometric_logs/show_logs'); ?>",
	      method: 'post',
	      data: {startDate: startDate, endDate: endDate},
	      dataType: 'json',
	      success: function(response) {
	        console.log(response);
	        var list = '';
	        $.each(response, function(i, row){
	          console.log(row.logDate, 'tmdate');
	          var options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
	          var logDate = new Date(row.logDate);
	
	          list += '<tr>';
	          list += '<td class="">'+logDate.toLocaleDateString("en-US", options)+'</td>';
	          list += '<td>'+logDate.showTime()+'</td>';
	          list += '</tr>';
	        }); 
	        $('#attendance_shifts tbody').html(list);
	      },
	      error: function(xhr) {
	
	      }
	    });
	  }
	
	
	  function showIncentives() {
	    $.ajax({
	        url: '<?php echo site_url('salary_wage/show_incentives')?>',
	        data: { employmentID: '<?php echo $employment->employmentID; ?>' },
	        type: 'POST',
	        dataType:'json',
	        success:function(response) {
	            if (response.status == '1') {
	                
	                list = '';
	                for (i = 0; i < response.records.length; i++) {
	                    record = response.records[i];
	                    
	          list += '<tr class="row-incentive" data-id="'+record.payID+'">';
	          list += ' <td><span>'+record.name+'</span></td>';
	          list += ' <td><span>'+record.effectivity+'</span></td>';
	          list += ' <td><span>'+format_number(record.amount)+'</span></td>';
	          if (record.status == 1) {
	              list += ' <td><span class="badge badge-pill badge-success">Active</span></td>';
	          } else {
	              list += ' <td><span class="badge badge-pill badge-danger">Inactive</span></td>';
	          }
	          list += '</tr>';
	                }
	
	                $('table#incentives tbody').html(list);
	            } else if (response.status == '0') {
	                if (response.message) {
	                    alert(response.message);
	                }
	            } else {
	                alert(response.message, 1);
	            }
	        }, error:function(xhr) {
	        }
	    });
	}
	
	function showContributions() {
	    $.ajax({
	        url: '<?php echo site_url('salary_wage/show_contributions')?>',
	        data: { employmentID: '<?php echo $employment->employmentID; ?>' },
	        type: 'POST',
	        dataType:'json',
	        success:function(response) {
	            if (response.status == '1') {
	                
	                list = '';
	                for (i = 0; i < response.records.length; i++) {
	                    record = response.records[i];
	                    
	          list += '<tr class="row-incentive" data-id="'+record.payID+'">';
	          list += ' <td><span>'+record.name+'</span></td>';
	          list += ' <td><span>'+record.effectivity+'</span></td>';
	          list += ' <td><span>'+((!record.isAutomatic) ? format_number(record.employeeShare) : '--')+'</span></td>';
	          list += ' <td><span>'+((!record.isAutomatic) ? format_number(record.employerShare) : '--')+'</span></td>';
	          if (record.status == 1) {
	              list += ' <td><span class="badge badge-pill badge-success">Active</span></td>';
	          } else {
	              list += ' <td><span class="badge badge-pill badge-danger">Inactive</span></td>';
	          }
	          list += '</tr>';
	                }
	
	                $('table#contributions tbody').html(list);
	            } else if (response.status == '0') {
	                if (response.message) {
	                    alert(response.message);
	                }
	            } else {
	                alert(response.message, 1);
	            }
	        }, error:function(xhr) {
	        }
	    });
	}
	
	function showLoans() {
	    $.ajax({
	        url: '<?php echo site_url('salary_wage/show_loans')?>',
	        data: { employmentID: '<?php echo $employment->employmentID; ?>' },
	        type: 'POST',
	        dataType:'json',
	        success:function(response) {
	            if (response.status == '1') {
	                
	                list = '';
	                for (i = 0; i < response.records.length; i++) {
	                    record = response.records[i];
	                    console.log(record);
	                    
	          list += '<tr class="row-incentive" data-id="'+record.payID+'">';
	          list += ' <td><span>'+record.name+'</span></td>';
	          list += ' <td><span>'+record.effectivity+'</span></td>';
	          list += ' <td><span>'+record.principal+'</span></td>';
	          list += ' <td><span>'+record.paid+'</span></td>';
	          list += ' <td><span>'+record.balance+'</span></td>';
	          list += ' <td><span>'+record.amortization+'</span></td>';
	          if (record.status == 1) {
	              list += ' <td><span class="badge badge-pill badge-success">Active</span></td>';
	          } else {
	              list += ' <td><span class="badge badge-pill badge-danger">Inactive</span></td>';
	          }
	          list += '</tr>';
	                }
	
	                $('table#loans tbody').html(list);
	            } else if (response.status == '0') {
	                if (response.message) {
	                    alert(response.message);
	                }
	            } else {
	                alert(response.message, 1);
	            }
	        }, error:function(xhr) {
	        }
	    });
	}
</script>

