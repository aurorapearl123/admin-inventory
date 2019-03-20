<style>
.checkBox { width:18px;height:18px;vertical-align:middle; }
.bright { border-right: 0.5px solid <?php echo $this->config_model->getConfig('Report Table Border Color')?>;}
.bleft { border-left: 0.5px solid <?php echo $this->config_model->getConfig('Report Table Border Color')?>;}
.btop { border-top: 0.5px solid <?php echo $this->config_model->getConfig('Report Table Border Color')?>;}
.bbottom { border-bottom: 0.5px solid <?php echo $this->config_model->getConfig('Report Table Border Color')?>;}
.ball { border: 1px solid <?php echo $this->config_model->getConfig('Report Table Border Color')?>;}
.label { height: 2px; font-size:8pt;  }
.field { height: 2px; font-size:8pt; }
.table_header { background-color: <?php echo $this->config_model->getConfig('Report Table BG Color')?>; font-size:10pt; }
.table_row { height: 20px; font-size:8pt; vertical-align:middle; font-size:8pt !important;}
.signatory { background-color: #fff; height: 20px; font-size:8pt; }
.signatory_table { position: absolute; bottom: 0;}
</style>

<table style="width: 100%;" border="0" class="table_header">
    
    <tr>
        <td style="width: 70%" valign="">
            <table style="width: 100%"  border="0">
                <tr>
                    <td valign="" style="" align="left" width="80px"> </td>
                    <td valign="" align="center" class="" width="80px"></td>
                </tr>
            </table>
        </td>
        <td style="width: 30%" valign="">
            <table style="width: 100%"  border="0" class="table_header">
                <tr>
                    <td valign="" style="" align="right" width="10%">Date: </td>
                    <td valign="" align="center" class="bbottom" width="15%"><slot><b><?php echo date('F d, Y') ?></b></slot></td>
                </tr>
            </table>
        </td>
    </tr>
</table>


								<table style="width: 100%;" class="signatory" cellspacing="0" cellpadding="1">
									<thead class="table_header" align="">
										<tr>
											<th colspan="11" height="30px">
												<div align="center"><h2>DAILY SALES REPORT</h2></div>

											</th>
										</tr>
										
										<tr>
											<th colspan="11" align="left">
												<div align="left">&nbsp;<b><i>Fuel</i></b></div>
											</th>

										</tr>
										
										<tr>
											<th style="" colspan="2" width="" class="bleft btop bright">
												<div align="left">Digital Meter Reading</div>
											</th>
											<th style="" class=" btop bright"><div align="center">Closing</div></th>
											<th style="" class=" btop bright"><div align="center">Opening</div></th>
											<th style="" class=" btop bright"><div align="center">Liters</div></th>
											<th style="" class=" btop bright"><div align="center">Calibration</div></th>
											<th style="" class=" btop bright"><div align="center">Liters Sold</div></th>
											<th style="" class=" btop bright"><div align="center">Trans Count</div></th>
											<th style="" class=" btop bright"><div align="center">Price /Liter</div></th>
											<th style="" class=" btop bright"><div align="center">Sale</div></th>
											<th style="" class=" btop bright"><div align="center">Value</div></th>
											<!-- <th style=""><div align="center">Refundable/Payable</div></th> -->

										</tr>
									</thead>

									<tbody align="">

										<?php 

										$productTotal = 0;
										//foreach products as row
										//=======================
										?>
										<tr class="table_row">
											<td style="width: 10%;" align="left" colspan="2" class="bleft btop bright">&nbsp;period</td>
											<td style="width: 9%;" align="right" class=" btop bright">99999.00</td>
											<td style="width: 9%;" align="right" class=" btop bright">99999.00</td>
											<td style="width: 9%;" align="right" class=" btop bright">99999.00</td>
											<td style="width: 9%;" align="right" class=" btop bright">99999.00</td>
											<td style="width: 9%;" align="right" class=" btop bright">99999.00</td>
											<td style="width: 9%;" align="right" class=" btop bright">99999.00</td>
											<td style="width: 9%;" align="right" class=" btop bright">99999.00</td>
											<td style="width: 9%;" align="right" class=" btop bright">99999.00</td>
											<td style="width: 9%;" align="right" class=" btop bright">99999.00&nbsp;</td>
											
										</tr>

										<!-- <?php //if (end($products)) { ?> -->
										<tr class="">
											<td colspan="5" align="left" style="" class="bleft btop bright">
												&nbsp;<b>Total For Premium</b>
											</td>
											<td style="" class=" btop bright" align="right"><div align="right" class=" btop bright"><b>1,999,4014.514</b></div></td> <!-- calibration -->
											<td style="" class=" btop bright" align="right"><div align="right" class=" btop bright"><b>4014.514</b></div></td>
											<td style="" class=" btop bright" align="right"><div align="right" class=" btop bright"><b>4014.514</b></div></td>
											<td style="" class=" btop bright" align="right"><div align="right" class=" btop bright"></div></td>
											<td style="" class=" btop bright" align="right"><div align="right" class=" btop bright"><b>4014.514</b></div></td>
											<td style="" class=" btop bright" align="right"><div align="right" class=" btop bright"><b>4014.514</b></div>&nbsp;</td>
											
											
										</tr>
										
										<!-- end foreach product -->

										<tr>
											<td colspan="5" align="left" style="" class="bleft btop bright bbottom">
												&nbsp;<b>Grand Total</b>
											</td>
											<td style="" class=" btop bright bbottom" align="right"><b>4014.514</b></td> <!-- calibration -->
											<td style="" class=" btop bright bbottom" align="right"><b>4014.514</b></td>
											<td style="" class=" btop bright bbottom" align="right"><b>4014.514</b></td>
											<td style="" class=" btop bright bbottom" align="right"></td>
											<td style="" class=" btop bright bbottom" align="right"><b>4014.514</b></td>
											<td style="" class=" btop bright bbottom" align="right"><b>4014.514</b>&nbsp;</td>
										</tr>
										<tr>
											<td colspan="11">
												&nbsp;
											</td>
										</tr>

										<tr style="font-size: 10pt;">

											<td colspan="2" rowspan="2" align="center" class="bleft btop bright">&nbsp;<b>Tank Summary</b></td>
											<td colspan="2" align="center" class=" btop bright"><b><b>Opening Dipstick</b></b></td>
											<td align="center" class=" btop bright"><b>Delivery</b></td>
											<td align="center" class=" btop bright"><b>Total Inv</b></td>
											<td colspan="2" align="center" class=" btop bright"><b>Closing Dipstick</b></td>
											<td rowspan="2" align="center" class=" btop bright"><b>Release</b></td>
											<td rowspan="2" align="center" class=" btop bright"><b>Liters Sold</b></td>
											<td rowspan="2" align="center" class=" btop bright"><b>Over/Short</b></td>
											<!-- <td><?php //echo  $employee['totalRefundable']; ?></b></td> -->
										</tr>
										<tr style="font-size: 10pt;"><b>
											<td class=" btop bright"><b></b></td>
											<td align="center" class=" btop bright"><b>Liters</b></td>
											<td align="center" class=" btop bright"><b>Delivery</b></td>
											<td align="center" class=" btop bright"><b>Total Inv</b></td>
											<td class=" btop bright"><b></b></td>
											<td align="center" class=" btop bright"><b>Liters</b></td>
											<!-- <td><?php //echo  $employee['totalRefundable']; ?></b></td> -->
										</tr>
										<tr>
											<td align="left" colspan="2"  class="bleft btop bright">&nbsp;DIESEL (Tank1)</td>
											<td class=" btop bright">&nbsp;</td>
											<td align="right" class=" btop bright">406.47</td>
											<td align="right" class=" btop bright">8,000.00</td>
											<td align="right" class=" btop bright">8,406.00</td>
											<td class=" btop bright">&nbsp;</td>
											<td align="right" class=" btop bright">7,831</td>
											<td align="right" class=" btop bright">575.470</td>
											<td align="right" class=" btop bright">4586.322</td>
											<td align="right" class=" btop bright">4010.852&nbsp;</td>
										</tr>
										

										<!-- <?php //foreach tanks as row ?>-->
										<!-- ============================================= -->
										<!-- <tr>
											<td colspan="11" class="bleft btop bright">
												<table class="table" style="border-left: 1px solid #eee; border-right: 1px solid #eee;">
													<tbody align="right" class=" btop bright">
														
														
													</tbody>
												</table>
											</td>
										</tr> -->
										<!-- ============================================= -->
										<!-- foreach tanks end -->

										<tr>
											<td align="left" colspan="4" class="bleft btop bright bbottom">
												&nbsp;<b>Total</b>
											</td>
											<td align="right" class=" btop bright bbottom"><b>4014.514</b></td>
											<td align="right" class=" btop bright bbottom"><b>4014.514</b></td>
											<td align="right" class=" btop bright bbottom"></td>
											<td align="right" class=" btop bright bbottom"><b>4014.514</b></td>
											<td align="right" class=" btop bright bbottom"><b>4014.514</b></td>
											<td align="right" class=" btop bright bbottom"><b>4014.514</b></td>
											<td align="right" class=" btop bright bbottom"><b>4014.514</b>&nbsp;</td>
										</tr>

										<tr>
											<td colspan="11" class="">
												&nbsp;
											</td>
										</tr>
									</tbody>
								</table>




										<table style="width: 100%;" class="signatory" cellspacing="0" cellpadding="1">
											<thead class="table_header">

												<tr>
													<th colspan="9" align="left">
														<div align="left">&nbsp;<b><i>Safe Drop/Deposit</i></b></div>
													</th>

												</tr>
												<tr>
													<th width="8%" class="bleft btop bright"><div align="center">Shift</div></th>
													<th width="10%" class=" btop bright"><div align="center">Time</div></th>
													<th width="15%" class=" btop bright"><div align="center">Amount</div></th>
													<th width="8%" class=" btop bright"><div align="center">Shift</div></th>
													<th width="10%" class=" btop bright"><div align="center">Time</div></th>
													<th width="15%" class=" btop bright"><div align="center">Amount</div></th>
													<th width="8%" class=" btop bright"><div align="center">Shift</div></th>
													<th width="10%" class=" btop bright"><div align="center">Time</div></th>
													<th width="15%" class=" btop bright"><div align="center">Amount</div></th>
												</tr>
											</thead>
											<tbody  id="tbody_items">
												<!-- foreach shift -->
												<tr>
													<td colspan="9" class="bleft btop bright">&nbsp;</td>

												</tr>
												
												<!-- end foreach shift -->
												<tr>
													<td colspan="8" align="left" class="bleft btop bright bbottom">
														
														<b>Total</b>

													</td>
													<td align="right" class="bleft btop bright bbottom"><b>9999999.99</b>&nbsp;</td>

												</tr>
											</tbody>
										</table>
								













										<table style="width: 100%;" class="signatory" cellspacing="0" cellpadding="1">
											<thead class="table_header">
												<tr>
													<th colspan="5" align="left">
														<div align="left"><b><i>Local Account (P.O.)</i></b></div>
													</th>

												</tr>
												<tr>
													<th width="15%" class="bleft btop bright"><div align="center">Code</div></th>
													<th width="40%" class=" btop bright"><div align="center">Customer</div></th>
													<th width="15%" class=" btop bright"><div align="center">Lubricants</div></th>
													<th width="15%" class=" btop bright"><div align="center">Fuel</div></th>
													<th width="15%" class=" btop bright"><div align="center">Amount</div></th>
												</tr>
											</thead>
											<tbody>
												<!-- foreach shift -->
												<tr>
													<td colspan="5"  class="bleft btop bright">&nbsp;</td>

												</tr>
												
												<!-- end foreach shift -->
												<tr>
													<td colspan="4" align="left" class="bleft btop bright bbottom">
														
														<b>Total</b>

													</td>
													<td align="right" class="bleft btop bright bbottom"><b>9999999.99</b>&nbsp;</td>

												</tr>
											</tbody>
										</table>
								





											<br>


											<table>
												<tr>

													<!-- Summary section -->
													<td>
														<table style="width: 35%; padding-bottom: 10px;padding-left: 5px;padding-right: 5px;" class="signatory" cellspacing="0" cellpadding="1" class="bleft btop bright bbottom">
															<thead class="table_header" align="center">
																<tr>
																	<th colspan="3" class="bbottom" style="padding-top: 5px;padding-bottom: 5px;">
																		<h4>SUMMARY</h4>
																	</th>
																</tr>

															</thead>
															<tbody align="left">
																<tr>
																	<td>&nbsp;Fuel</td>
																	<td></td>
																	<td>999,999.00&nbsp;</td>
																</tr>
																<tr>
																	<td>&nbsp;Add Lubes</td>
																	<td></td>
																	<td>999,999.00&nbsp;</td>
																</tr>



																<tr>
																	<td>&nbsp;<b><i>Others</i></b></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr>
																	<td>&nbsp;&nbsp;&nbsp;Change Fund</td>
																	<td></td>
																	<td>999,999.00&nbsp;</td>
																</tr>
																<tr>
																	<td>&nbsp;&nbsp;&nbsp;Paid In</td>
																	<td class="bbottom"></td>
																	<td class="bbottom">999,999.00&nbsp;</td>
																</tr>
																<tr>
																	<td>&nbsp;<b>Total Sales</b></td>
																	<td></td>
																	<td><b>999,999.00</b>&nbsp;</td>
																</tr>



																<tr>
																	<td>&nbsp;<b><i>Less</i></b></td>
																	<td></td>
																	<td></td>
																</tr>
																<tr>
																	<td>&nbsp;&nbsp;&nbsp;Local Account (P.O.)</td>
																	<td></td>
																	<td>999,999.00&nbsp;</td>
																</tr>
																<tr>
																	<td>&nbsp;&nbsp;&nbsp;Credit Card</td>
																	<td></td>
																	<td>999,999.00&nbsp;</td>
																</tr>
																<tr>
																	<td>&nbsp;&nbsp;&nbsp;Star Card / Star Cash</td>
																	<td></td>
																	<td>999,999.00&nbsp;</td>
																</tr>
																<tr>
																	<td>&nbsp;&nbsp;&nbsp;Paid Out</td>
																	<td></td>
																	<td>999,999.00&nbsp;</td>
																</tr>
																<tr>
																	<td>&nbsp;&nbsp;&nbsp;Discount</td>
																	<td class="bbottom"></td>
																	<td class="bbottom">999,999.00&nbsp;</td>
																</tr>



																<tr>
																	<td colspan="3">&nbsp;</td>
																</tr>
																<tr>
																	<td>&nbsp;<b>Net Sales</b></td>
																	<td></td>
																	<td><b>999,999.00</b>&nbsp;</td>
																</tr>
																<tr>
																	<td>&nbsp;Cash On Hand</td>
																	<td class="bbottom"></td>
																	<td class="bbottom">999,999.00&nbsp;</td>
																</tr>
																<tr>
																	<td>&nbsp;<b>Over (Short)</b></td>
																	<td></td>
																	<td><b>999,999.00</b>&nbsp;</td>
																</tr>


															</tbody>

														</table>
													</td>









													<!-- Signatory section -->
													<td style="height: 20px !important;" valign="top">
															
														
															
														
														<table border="0" cellpadding="0" cellspacing="0" style="padding: 10px;">
															<tr>
																<td colspan="7" style="height: 20px !important;">
																	<b>NOTE:</b>
																	<pre><p style="font-size: 8pt; font-family: Gotham Narrow;">
<i>By signing, We acknowledge that we are liable for whatever shortages that were incured during our shift.  We also agree to immediately provide cash payment for the said shortages. Should we lack the capacity to immediately pay in cash, we agree to have the appropriate amount deducted from our salary.


															</i></p></pre>
																	
																</td>
															</tr>
															<tr>
																<td style="width: 5.55%;"></td>
																<td style="width: 16.66%;">Prepared By:</td>
																<td style="width: 16.66%;"></td>
																<td style="width: 16.66%;"></td>
																<td colspan="2" style="width: 38.885%;">Witness:</td>

																<td style="width: 5.55%;"></td>

															</tr>
															<tr>
																<td colspan="7" height="30px"></td>
															</tr>
															<tr>
																<td></td>
																<td colspan="2" style="border-bottom: 1px solid #000;" align="center"></td>
																<td ></td>
																<td colspan="2" style="border-bottom: 1px solid #000;" align="center"></td>
																<td ></td>


															</tr>
															<tr>
																<td></td>
																<td colspan="2" align="center" style="font-size: 8pt;">Cashier Signature over Printed Name</td>
																<td></td>
																<td colspan="2" align="center" style="font-size: 8pt;">OIC Signature over Printed Name</td>
																<td></td>

															</tr>
														</table>
														
													</td>
												</tr>
											</table>

									
							

<!-- Footer start -->

<!-- Footer end -->