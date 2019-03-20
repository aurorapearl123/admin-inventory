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
							<h4 class="head-text">Daily Sales Report</h4>
						</div>
					</div>
					<div class="card-head-tools">
						<ul class="tools-list">
							<li>
								<button class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="Print" onclick="popUp('<?php echo $controller_page ?>/print_record/<?php echo $id?>', 900, 500)"><i class="la la-file-pdf-o"></i></button>
							</li>
						</ul>
					</div>
				</div>
				<div class="card-body">
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo site_url('reports/daily_sales"') ?>">
						<div class="table-row">
							<table class="table-form column-3" width="100%">
								<tbody>
									<tr>
										<td class="form-label" width="12%" nowrap>Date <span class="asterisk">*</span></td>
										<td class="form-group form-input" colspan="4" width="21.33%" >
											<select class="form-control" id="date" name="date">
												<option value="">&nbsp;</option>
											</select>
										</td>
										<td class="form-label" width="12%"><input class="btn btn-primary btn-raised pill" type="button" name="cmdSubmit" id="cmdSubmit" value=" Submit Filter " />&nbsp;</td>
										<td class="form-group form-input" colspan="4" width="21.33%" >
										</td>
										<td class="form-label" width="12%"></td>
										<td class="form-group form-input" colspan="4" width="21.33%" >
										</td>

									</tr>
									
								</tbody>
							</table>
						</div>
					</form>



					<div class="form-sepator mb-20"></div>





							<div class="table-responsive-xl" style="border-left: 1px solid #eee; border-right: 1px solid #eee;">
								<table class="table table-form table-bordered" style="width: 100%; margin-top: 20px; padding: 5px;" border="0" cellpadding="1">
									<thead class="thead-light" align="center">
										<tr>
											<th colspan="11" height="30px">
												<div align="center"><h4>DAILY SALES REPORT</h4></div>

											</th>
										</tr>
										<tr>
											<th colspan="11">
												<div align="left"><b><i>Fuel</i></b></div>
											</th>

										</tr>
										
										<tr>
											<th style="" colspan="2">
												<div align="left">Digital Meter Reading</div>
											</th>
											<th style=""><div align="center">Closing</div></th>
											<th style=""><div align="center">Opening</div></th>
											<th style=""><div align="center">Liters</div></th>
											<th style=""><div align="center">Calibration</div></th>
											<th style=""><div align="center">Liters Sold</div></th>
											<th style=""><div align="center">Trans Count</div></th>
											<th style=""><div align="center">Price /Liter</div></th>
											<th style=""><div align="center">Sale</div></th>
											<th style=""><div align="center">Value</div></th>
											<!-- <th style=""><div align="center">Refundable/Payable</div></th> -->

										</tr>
									</thead>

									<tbody align="">

										<?php 

										$productTotal = 0;
										//foreach products as row
										//=======================
										?>
										<tr>
											<td style="width: 20%;" align="left" colspan="2" align="left">period</td>
											<td style="width: 5%;" align="right">99999.00</td>
											<td style="width: 5%;" align="right">99999.00</td>
											<td style="width: 5%;" align="right">99999.00</td>
											<td style="width: 5%;" align="right">99999.00</td>
											<td style="width: 5%;" align="right">99999.00</td>
											<td style="width: 5%;" align="right">99999.00</td>
											<td style="width: 5%;" align="right">99999.00</td>
											<td style="width: 5%;" align="right">99999.00</td>
											<td style="width: 5%;" align="right">99999.00</td>
											
										</tr>

										<!-- <?php //if (end($products)) { ?> -->
										<tr>
											<td colspan="5" align="left" style="">
												<b>Total For Premium</b>
											</td>
											<td style=""><div align="right"><b>4014.514</b></div></td> <!-- calibration -->
											<td style=""><div align="right"><b>4014.514</b></div></td>
											<td style=""><div align="right"><b>4014.514</b></div></td>
											<td style=""><div align="right"></div></td>
											<td style=""><div align="right"><b>4014.514</b></div></td>
											<td style=""><div align="right"><b>4014.514</b></div></td>
											
											
										</tr>
										
										<!-- end foreach product -->

										<tr>
											<td colspan="5" align="left" style="">
												<b>Grand Total</b>
											</td>
											<td style=""><div align="right"><b>4014.514</b></div></td> <!-- calibration -->
											<td style=""><div align="right"><b>4014.514</b></div></td>
											<td style=""><div align="right"><b>4014.514</b></div></td>
											<td style=""><div align="right"></div></td>
											<td style=""><div align="right"><b>4014.514</b></div></td>
											<td style=""><div align="right"><b>4014.514</b></div></td>
											
											
										</tr>
										<tr>
											<td colspan="11">
												&nbsp;
											</td>
										</tr>

										<tr style="font-size: 10pt;">

											<td colspan="2" rowspan="2" align="left">Tank Summary</td>
											<td colspan="2" align="center">Opening Dipstick</td>
											<td align="center">Delivery</td>
											<td align="center">Total Inv</td>
											<td colspan="2" align="center">Closing Dipstick</td>
											<td rowspan="2" align="center">Release</td>
											<td rowspan="2" align="center">Liters Sold</td>
											<td rowspan="2" align="center">Over/Short</td>
											<!-- <td><?php //echo  $employee['totalRefundable']; ?></td> -->
										</tr>
										<tr style="font-size: 10pt;">
											<td></td>
											<td align="center">Liters</td>
											<td align="center">Delivery</td>
											<td align="center">Total Inv</td>
											<td></td>
											<td align="center">Liters</td>
											<!-- <td><?php //echo  $employee['totalRefundable']; ?></td> -->
										</tr>
										<tr>
											<td align="left" colspan="2">DIESEL (Tank1)</td>
											<td>&nbsp;</td>
											<td align="right">406.47</td>
											<td align="right">8,000.00</td>
											<td align="right">8,406.00</td>
											<td>&nbsp;</td>
											<td align="right">7,831</td>
											<td align="right">575.470</td>
											<td align="right">4586.322</td>
											<td align="right">4010.852</td>
										</tr>
										

										<!-- <?php //foreach tanks as row ?>-->
										<!-- ============================================= -->
										<tr>
											<td colspan="11">
												<table class="table" style="border-left: 1px solid #eee; border-right: 1px solid #eee;">
													<tbody align="right">
														
														
													</tbody>
												</table>
											</td>
										</tr>
										<!-- ============================================= -->
										<!-- foreach tanks end -->

										<tr>
											<td align="left" colspan="4">
												<b>Total</b>
											</td>
											<td align="right"><b>4014.514</b></td>
											<td align="right"><b>4014.514</b></td>
											<td align="right"></td>
											<td align="right"><b>4014.514</b></td>
											<td align="right"><b>4014.514</b></td>
											<td align="right"><b>4014.514</b></td>
											<td align="right"><b>4014.514</b></td>
										</tr>


									</tbody>
								</table>










								<div class="subtitle mt-20">
									<h5 class="title mr-auto"></h5>
									<div class="subtitle-tools">
									</div>
								</div>
								<div class="datatables_wrapper">
									<div id="div_items" class="table-responsive">
										<table id="table_items" class="table table-bordered">
											<thead class="thead-light" align="center">
												<tr>
													<th colspan="11">
														<div align="left"><b><i>Safe Drop/Deposit</i></b></div>
													</th>

												</tr>
												<tr>
													<th width="8%"><div align="center">Shift</div></th>
													<th width="10%"><div align="center">Time</div></th>
													<th width="15%"><div align="center">Amount</div></th>
													<th width="8%"><div align="center">Shift</div></th>
													<th width="10%"><div align="center">Time</div></th>
													<th width="15%"><div align="center">Amount</div></th>
													<th width="8%"><div align="center">Shift</div></th>
													<th width="10%"><div align="center">Time</div></th>
													<th width="15%"><div align="center">Amount</div></th>
												</tr>
											</thead>
											<tbody  id="tbody_items">
												<!-- foreach shift -->
												<tr>
													<td colspan="9">&nbsp;</td>

												</tr>
												<tr>
													<td colspan="9">&nbsp;</td>

												</tr>
												<!-- end foreach shift -->
												<tr>
													<td colspan="8" align="left">
														
														<b>Total</b>

													</td>
													<td align="right"><b>9999999.99</b></td>

												</tr>
											</tbody>
										</table>
									</div>
								</div>















										<div class="subtitle mt-20">
									<h5 class="title mr-auto"></h5>
									<div class="subtitle-tools">
									</div>
								</div>
								<div class="datatables_wrapper">
									<div id="div_items" class="table-responsive">
										<table id="table_items" class="table table-bordered">
											<thead class="thead-light" align="center">
												<tr>
													<th colspan="11">
														<div align="left"><b><i>Local Account (P.O.)</i></b></div>
													</th>

												</tr>
												<tr>
													<th width="15%"><div align="center">Code</div></th>
													<th width="40%"><div align="center">Customer</div></th>
													<th width="15%"><div align="center">Lubricants</div></th>
													<th width="15%"><div align="center">Fuel</div></th>
													<th width="15%"><div align="center">Amount</div></th>
												</tr>
											</thead>
											<tbody  id="tbody_items">
												<!-- foreach shift -->
												<tr>
													<td colspan="9">&nbsp;</td>

												</tr>
												<tr>
													<td colspan="9">&nbsp;</td>

												</tr>
												<!-- end foreach shift -->
												<tr>
													<td colspan="4" align="left">
														
														<b>Total</b>

													</td>
													<td align="right"><b>9999999.99</b></td>

												</tr>
											</tbody>
										</table>
									</div>
								</div>





							</div>
							<!-- table-responsive container end -->
							<hr>
							<div class="form-sepator mb-20"></div>




							<div class="row">
								<div class="col-md-8">
								</div>
								<div class="col-md-4">
									<table class="table" style="border-left: 0px solid #eee; border-right: 0px solid #eee;">
								<thead class="thead" align="center">
									<tr>
										<th colspan="3">
											<h4>DAILY SALES REPORT SUMMARY</h4>
										</th>
									</tr>
								</thead>
								<tbody align="left">
									<tr>
										<td style="width: 70%;">Fuel</td>
										<td></td>
										<td>₱ 1,999,999.99</td>
									</tr>
									<tr>
										<td>Add Lubes</td>
										<td></td>
										<td>₱ 1,999,999.99</td>
									</tr>



									<tr>
										<td><b><i>Others</i></b></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;Change Fund</td>
										<td></td>
										<td>₱ 1,999,999.99</td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;Paid In</td>
										<td></td>
										<td>₱ 1,999,999.99</td>
									</tr>
									<tr>
										<td><b>Total Sales</b></td>
										<td></td>
										<td><b>₱ 1,999,999.99</b></td>
									</tr>



									<tr>
										<td><b><i>Less</i></b></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;Local Account (P.O.)</td>
										<td></td>
										<td>₱ 1,999,999.99</td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;Credit Card</td>
										<td></td>
										<td>₱ 1,999,999.99</td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;Star Card / Star Cash</td>
										<td></td>
										<td>₱ 1,999,999.99</td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;Paid Out</td>
										<td></td>
										<td>₱ 1,999,999.99</td>
									</tr>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;Discount</td>
										<td></td>
										<td>₱ 1,999,999.99</td>
									</tr>



									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td><b>Net Sales</b></td>
										<td></td>
										<td><b>₱ 1,999,999.99</b></td>
									</tr>
									<tr>
										<td>Cash On Hand</td>
										<td></td>
										<td>₱ 1,999,999.99</td>
									</tr>
									<tr>
										<td><b>Over (Short)</b></td>
										<td></td>
										<td><b>₱ 1,999,999.99</b></td>
									</tr>


								</tbody>
								<tfoot>
									<tr>
										<td colspan="3"><div align="center"> <button class="btn btn-block btn-primary btn-raised" type="button" name="cmdSubmit" id="cmdSubmit"   onclick="printSummary();"><i class="la la-file-pdf-o"></i>&nbsp;PRINT REPORT SUMMARY</button></div></td>
									</tr>
								</tfoot>
							</table>
							</div>
							<div class="col-md-4">
								
							</div>
							</div>
							

							





					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		$('#cmdSubmit').click(function(){
			if (check_fields()) {
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

		<?php 
		echo "\n";
		$parameters = array('branchID');
		echo $this->htmlhelper->get_json_select('get_departments', $parameters, site_url('generic_ajax/get_code_departments'), 'deptID', '');

		echo "\n";
		$parameters = array('deptID');
		echo $this->htmlhelper->get_json_select('get_sections', $parameters, site_url('generic_ajax/get_code_sections'), 'divisionID', '');
		?>


		function printSummary()
		{	

			popUp('<?php echo $controller_page ?>/print_daily_sales/<?php echo (!empty($date))? date('Y-m-d', strtotime($date)): date('Y-m-d'); ?>/', 910, 500);	
		}


	</script>

