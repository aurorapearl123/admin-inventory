<div class="content">
	<div class="row">
		<div class="col-12">
			<div class="card-box">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="head-text"><i class="icon left la la-money"></i><?php echo strtoupper($page_title) ?></h4>
						</div>
					</div>
					<div class="card-head-tools">
						<ul class="tools-list">
							<button type="button" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="Print" onclick="popUp('<?php echo $controller_page.'/print_form/'.$this->encrypter->encode($rec->$pfield)?>', 800, 500)"><i class="la la-print"></i></button>
							<!-- 
							<li>
								<button type="button" id="recordlog" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Record Logs" onclick="popUp('<?php echo site_url('logs/record_log/risheaders/'.$pfield.'/'.$this->encrypter->encode($rec->$pfield).'/Requisition Issue Slip') ?>', 1000, 500)"><i class="la la-server"></i></button>
							</li>
							 -->
						</ul>
					</div>
				</div>
				<div class="card-body">
					<div class="data-view">
						<table class="view-table">
							<tbody>
								<tr>
									<td class="data-title" width="120px" nowrap>Period</td>
									<td class="data-title" width="150px">
										<input type="text" class="form-control datepicker" id="startDate" name="startDate" data-toggle="datetimepicker" value="<?php echo date('M 01, Y')?>" data-target="#startDate" title="Start Date" required>
									</td>
									<td class="data-title" width="150px" nowrap>
										<input type="text" class="form-control datepicker" id="endDate" name="endDate" data-toggle="datetimepicker" value="<?php echo date('M d, Y')?>" data-target="#endDate" title="End Date" required>
									</td>
									<td class="data-title" width="150px" align="left" nowrap>
										<button class="button-light-blue" type="button" id="cmdAdd" value=" Add Item " onclick="checkQty()"><span>Apply Filter </span></button>
									</td>
									<td>&nbsp;</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="card-box">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="form-label"><i class="icon left la la-qrcode"></i> TRANSACTION DETAILS</h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<div class="datatables_wrapper">
						<div class="table-responsive">
							<table class="table table-striped hover" style="border:1px solid #e9eae9">
								<thead>
									<tr style="background-color:#004080;">
								        <td valign="top" colspan="3" nowrap><font color='#ffffff'>Non-Cash Breakdown Report</font></td>
								        <td valign="top" colspan="6" align="center" nowrap><font color='#ffffff'>Kitrol Agusan</font></td>
								        <td valign="top" colspan="4" align="right" nowrap><font color='#ffffff'>From Sep 01 to Sep 05, 2018</font></td>
								    </tr>
									<tr>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="center" colspan="2" nowrap>&nbsp;</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="center" colspan="5" nowrap>Bank Name</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="center" colspan="4" nowrap>Debit Card</td>
								        <td style="border-bottom:1px solid #ccc;" valign="top" colspan="2" align="center" nowrap>P.O.</td>
								    </tr>
									<tr style="background-color:#004080;">
								        <td style="border-right:1px solid #ffffff;border-bottom:1px solid #ccc;color:#ffffff;text-align:center" valign="top" width="200px" nowrap>Date</td>
								        <td style="border-right:1px solid #ffffff;border-bottom:1px solid #ccc;color:#ffffff;text-align:center" valign="top" width="300px" nowrap>Cashier</td>
								        <td style="border-right:1px solid #ffffff;border-bottom:1px solid #ccc;color:#ffffff;text-align:center" valign="top" width="100px" nowrap>Security Bank</td>
								        <td style="border-right:1px solid #ffffff;border-bottom:1px solid #ccc;color:#ffffff;text-align:center" valign="top" width="100px" nowrap>Metro Bank</td>
								        <td style="border-right:1px solid #ffffff;border-bottom:1px solid #ccc;color:#ffffff;text-align:center" valign="top" width="100px" nowrap>BDO</td>
								        <td style="border-right:1px solid #ffffff;border-bottom:1px solid #ccc;color:#ffffff;text-align:center" valign="top" width="100px" nowrap>BPI</td>
								        <td style="border-right:1px solid #ffffff;border-bottom:1px solid #ccc;color:#ffffff;text-align:center" valign="top" width="100px" nowrap>&nbsp;</td>
								        <td style="border-right:1px solid #ffffff;border-bottom:1px solid #ccc;color:#ffffff;text-align:center" valign="top" width="100px" nowrap>EPS</td>
								        <td style="border-right:1px solid #ffffff;border-bottom:1px solid #ccc;color:#ffffff;text-align:center" valign="top" width="100px" nowrap>Star Cash</td>
								        <td style="border-right:1px solid #ffffff;border-bottom:1px solid #ccc;color:#ffffff;text-align:center" valign="top" width="100px" nowrap>Star Card</td>
								        <td style="border-right:1px solid #ffffff;border-bottom:1px solid #ccc;color:#ffffff;text-align:center" valign="top" width="100px" nowrap>Rob. Card</td>
								        <td style="border-right:1px solid #ffffff;border-bottom:1px solid #ccc;color:#ffffff;text-align:center" valign="top" width="100px" nowrap>GOCC</td>
								        <td style="border-right:1px solid #ffffff;border-bottom:1px solid #ccc;color:#ffffff;text-align:center" valign="top" width="100px" nowrap>L. Account</td>
								    </tr>
								</thead>
								<tbody>
								    <tr>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="center">2018-09-01</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="center">RAMEL</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right">0.00</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right">0.00</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right">0.00</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right">0.00</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right">0.00</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right">0.00</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right">0.00</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right">0.00</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right">0.00</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right">0.00</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right">7,905.59</td>
								    </tr>
								    <tr style="background-color:#b8c4db;">
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="left" colspan="2"><i>TOTAL</i></td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right"><i>0.00</i></td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right"><i>0.00</i></td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right"><i>0.00</i></td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right"><i>0.00</i></td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right"><i>0.00</i></td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right"><i>0.00</i></td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right"><i>0.00</i></td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right"><i>0.00</i></td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right"><i>0.00</i></td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right"><i>0.00</i></td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;" align="right"><i>7,905.59</i></td>
								    </tr>
								    <tr>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;color:#0f2551" colspan="2">TOTAL</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;">&nbsp;</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;">&nbsp;</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;">&nbsp;</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;">&nbsp;</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;">&nbsp;</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;">&nbsp;</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;">&nbsp;</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;">&nbsp;</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;">&nbsp;</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;">&nbsp;</td>
								        <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;">&nbsp;</td>
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
<script>
var site_url = '<?php echo site_url(); ?>';

$('#addLeadGroup').click(function(){
	swal({
	      title: "Notification",
	      text: "Are you sure you want to 'CONFIRM' this transaction?",
	      icon: "warning",
	      showCancelButton: true,
	      confirmButtonColor: '#3085d6',
	      cancelButtonColor: '#d33',
	      confirmButtonText: 'Yes',
	      cancelButtonText: 'No'
	    })
	    .then((willDelete) => {
	      if (willDelete.value) {
	    	  window.location = '<?php echo $controller_page."/confirm_transaction/".$this->encrypter->encode($rec->risID) ?>';
	      }
	    });
});

$('#addLeadGroup2').click(function(){
	swal({
	      title: "Notification",
	      text: "Are you sure you want to 'CANCEL' this transaction?",
	      icon: "warning",
	      showCancelButton: true,
	      confirmButtonColor: '#3085d6',
	      cancelButtonColor: '#d33',
	      confirmButtonText: 'Yes',
	      cancelButtonText: 'No'
	    })
	    .then((willDelete) => {
	      if (willDelete.value) {
	    	  window.location = '<?php echo $controller_page."/cancel_transaction/".$this->encrypter->encode($rec->risID) ?>';
	      }
	    });
});


$('#cmdCancel').click(function(){
	swal({
	      title: "Notification",
	      text: "Are you sure you want to 'cancel' this transaction?",
	      icon: "warning",
	      showCancelButton: true,
	      confirmButtonColor: '#3085d6',
	      cancelButtonColor: '#d33',
	      confirmButtonText: 'Yes',
	      cancelButtonText: 'No'
	    })
	    .then((willDelete) => {
	      if (willDelete.value) {
	    	  window.location = '<?php echo $controller_page."/cancel_transaction/".$this->encrypter->encode($rec->risID) ?>';
	      }
	    });
});

</script>