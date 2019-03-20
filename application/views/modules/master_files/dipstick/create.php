<div class="subheader">
	<div class="d-flex align-items-center">
		<div class="title mr-auto">
			<h3><i class="icon left la <?php echo $current_module['icon'] ?>"><?php echo strtoupper($current_module['title']) ?></i> </h3>
		</div>
		<div class="subheader-tools">
			<a href="<?php echo $controller_page?>/show" class="btn btn-primary btn-raised btn-sm pill"><i class="icon ti-angle-left"></i> Back to List</a>
		</div>
	</div>
</div>
<form method="post" name="frmEntry" id="frmEntry" action="<?php echo $controller_page ?>/save" enctype="multipart/form-data">
<div class="content">
	<div class="row">
		<div class="col-8">
			<div class="card-box" style="height:480px">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="head-text"><i class="icon left la <?php echo $current_module['icon'] ?>"></i>Add <?php echo strtoupper($current_module['title']) ?></h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<div class="table-row">
						<table class="table-form column-3" border="0">
							<tbody>
								<tr>
									<td class="form-label" width="120px" nowrap>Shift <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">
									<select class="form-control" id="shiftID" name="shiftID" data-live-search="true" liveSearchNormalize="true" title="Account" required>
												<option value="" selected>&nbsp;</option>
												<?php 
													
													foreach($shifts as $rec){
													?>
													<option value="<?php echo $rec->shiftID?>" <?php if($rec->shiftID == $shiftID){ echo "selected"; }?>><?php echo date('h:i A', strtotime($rec->startTime)).' - '.date('h:i A', strtotime($rec->endTime))?></option>
													<?php } ?>
											</select>
									</td>
									<td class="form-label" width="120px" nowrap>Dipper: <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">
										<input type="text" class="form-control " id="dipper" name="dipper"  >

									</td>
									<td>&nbsp;</td>
								</tr>
								<tr>

									<td class="form-label" width="120px" nowrap>Time in: <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">
									<input type="time" id="inTime" class="form-control" name="inTime" min="9:00" max="18:00" title="Time in" required>
										

									</td>
									<td class="form-label" width="120px" nowrap>Time out: <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">
										<input type="time" class="form-control" id="outTime" name="outTime" min="9:00" max="18:00" title="Time out" required>
										

									</td>
									
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="form-label" width="180px" >Opening Qty <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">									
										<input type="text" class="form-control" name="openingQty" id="openingQty" title="Opening Quantity" required onkeypress="return isNumber(event)" onfocus="$(this).select();">
									</td>
									<td class="form-label" width="180px" >Closing Qty <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">									
										<input type="text" class="form-control" name="closingQty" id="closingQty" title="Closing Quantity" required onkeypress="return isNumber(event)" onfocus="$(this).select();">
									</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="form-label" width="180px" >Variance Qty <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">									
										<input type="text" class="form-control" name="varianceQty" id="varianceQty" title="Variance Quantity" required readonly onkeypress="return isNumber(event)" onfocus="$(this).select();">
									</td>
									
									<td class="form-label" nowrap></td>
									<td class="form-group form-input">
										
									</td>
									<td>&nbsp;</td>
								</tr>

								<tr>
									<td class="form-label" width="180px" >Opening Msr <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">									
										<input type="text" class="form-control" name="openingMsr" id="openingMsr" title="Opening Measurement" required onkeypress="return isNumber(event)" onfocus="$(this).select();">
									</td>
									<td class="form-label" width="180px" >Closing Msr <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">									
										<input type="text" class="form-control" name="closingMsr" id="closingMsr" title="Closing Measurement" required onkeypress="return isNumber(event)" onfocus="$(this).select();">
									</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="form-label" width="180px" >Variance Msr <span class="asterisk">*</span></td>
									<td class="form-group form-input" width="400px">									
										<input type="text" class="form-control" name="varianceMsr" id="varianceMsr" title="Variance Measurement" required onkeypress="return isNumber(event)" onfocus="$(this).select();">
									</td>
									
									<td class="form-label" nowrap></td>
									<td class="form-group form-input">
										
									</td>
									<td>&nbsp;</td>
								</tr>

							</tbody>
						</table>
					</div>
				</div>
				
				<div class="card-body">
					
					<div class="form-sepator solid"></div>
					<div class="form-group mb-0">
						<button class="btn btn-primary btn-raised pill" type="button" name="cmdSave" id="cmdSave">
						Save
						</button>
						<input type="button" id="cmdCancel" class="btn btn-outline-danger btn-raised pill" value="Cancel"/>
					</div>
				</div>
			</div>
		</div>
		<div class="col-4">
			<div class="card-box" style="height:200px">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="form-label"><i class="icon left la <?php echo $current_module['icon'] ?>"></i> Shift  <span style="color:darkolivegreen;font-weight:bold" id="shift-name"></span></h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<div class="table-row">
						<table class="table-form column-3">
							<tr>
								<td valign="top" width="30%">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										
									    <tr>
										
									        <td class="form-label" valign="top" nowrap>Start Time <span style="color:darkolivegreen;font-weight:bold"></span></td>
									        
											<td class="form-group form-input">
											<span id="start-time" style="color:darkolivegreen;font-weight:bold">------</span>
											</td>
									    </tr>
									    <tr>
									        <td class="form-label" valign="top" nowrap width="100">End Time <span style="color:darkolivegreen;font-weight:bold"></span></td>
											<td class="form-group form-input">
											<span id="end-time" style="color:darkolivegreen;font-weight:bold">------</span>
											</td>
									    </tr>
									   
								    </table>
							    </td>
							</tr>
						</table>
					</div>
				</div>
				

				
			</div>
		</div>
	</div>
</div>
</form>

<script>
$(document).ready(function(){
	

	$('#shiftID').on('change', function(){
		var id = $(this).val();
		var text = $("#shiftID option:selected").text();
		$('#shift-name').text('');
		$('#start-time').text('------');
		$('#end-time').text('------');
		var shifts = '<?php echo json_encode($shifts)?>';
		var shifts = JSON.parse(shifts);
		for(i in shifts) {
			if(shifts[i].shiftID == id) {
				//console.log(shifts[i].bankAcronym);
				$('#shift-name').text(text);
				var start_time = moment(shifts[i].startTime).format('LLLL');
				$('#start-time').text(start_time);
				var end_time = moment(shifts[i].endTime).format('LLLL');
				$('#end-time').text(end_time);
			}
		}
	});

	$('#openingQty').on('keyup', function(){
		var closingQty = $('#closingQty').val();
		var openingQty = $(this).val();
		if(!isEmpty(openingQty) && !isEmpty(closingQty)) {
			//console.log(openingQty);
			var variance = openingQty - closingQty;
			$('#varianceQty').val(variance);
		}
		else {
			$('#varianceQty').val('');
		}
		
	});

	$('#closingQty').on('keyup', function(){
		var openingQty = $('#openingQty').val();
		var closingQty = $(this).val();
		if(!isEmpty(closingQty) && !isEmpty(openingQty)) {
			//console.log(openingQty);
			//console.log(closingQty);
			var variance = openingQty - closingQty;
			$('#varianceQty').val(variance);
		}
		else {
			$('#varianceQty').val('');
		}
		
	});

	$('#openingMsr').on('keyup', function(){
		var closingMsr = $('#closingMsr').val();
		var openingMsr = $(this).val();
		if(!isEmpty(openingMsr) && !isEmpty(closingMsr)) {
			//console.log(openingQty);
			var variance = openingMsr - closingMsr;
			$('#varianceMsr').val(variance);
		}
		else {
			$('#varianceMsr').val('');
		}
		
	});


	$('#closingMsr').on('keyup', function(){
		var openingMsr = $('#openingMsr').val();
		var closingQty = $(this).val();
		if(!isEmpty(closingQty) && !isEmpty(openingMsr)) {
			//console.log(openingQty);
			//console.log(closingQty);
			var variance = openingMsr - closingQty;
			$('#varianceMsr').val(variance);
		}
		else {
			$('#varianceMsr').val('');
		}
		
	});


	function isEmpty(val)
	{
		return (val === undefined || val == null || val.length <= 0) ? true : false;
	}

});



	$('#cmdSave').click(function(){
		if (check_fields()) {
	    	$('#cmdSave').attr('disabled','disabled');
	    	$('#cmdSave').addClass('loader');
	        $.post("<?php echo $controller_page ?>/check_duplicate", { name: $('#name').val() },
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
		    	  window.location = '<?php echo site_url('Dipstick/show') ?>';
		      }
		    });
	});

	
</script>
