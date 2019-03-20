<div class="subheader">
	<div class="d-flex align-items-center">
		<div class="title mr-auto">
			<h3><i class="icon left la <?php echo $current_module['icon'] ?>"></i> PHYSICAL COUNT </h3>
		</div>
		<div class="subheader-tools">
			<a href="<?php echo $controller_page?>/show" class="btn btn-primary btn-raised btn-sm pill"><i class="icon ti-angle-left"></i> Back to List</a>
		</div>
	</div>
</div>
<div class="content">
	<div class="row">
		<div class="col-12">
			<div class="card-box">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="head-text"><i class="icon left la <?php echo $current_module['icon'] ?>"></i>VIEW <?php echo strtoupper($current_module['title']) ?> &nbsp;| &nbsp;
								<?php 
								if($rec->status==1){ 
									echo "<font color='green'>Pending</font>"; 
								} else if($rec->status==2){ 
									echo "<font color='green'>Confirmed</font>"; 
								} else { 
									echo "<font color='red'>Cancelled</font>"; 
								}?>
							</h4>
						</div>
					</div>
					<div class="card-head-tools">
						<ul class="tools-list">
							<?php if ($roles['edit'] && $rec->status==1) {?>
							<button type="button" name="cmdConfirm" id="cmdConfirm" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="Confirm"><i class="la la-check-circle"></i></button>
							<button type="button" name="cmdVoid" id="cmdVoid" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="Void"><i class="la la-times-circle"></i></button>
							<li>
								<button type="button" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="Print" onclick="popUp('<?php echo $controller_page ?>/print_record/<?php echo $this->encrypter->encode($rec->$pfield)?>', 800, 500)"><i class="la la-print"></i></button>
							</li>
							<?php } ?>
							<li>
								<button type="button" id="recordlog" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Record Logs" onclick="popUp('<?php echo site_url('logs/record_log/pcheaders/'.$pfield.'/'.$this->encrypter->encode($rec->$pfield).'/Physical Count') ?>', 1000, 500)"><i class="la la-server"></i></button>
							</li>
						</ul>
					</div>
				</div>
				<div class="card-body">
					<div class="data-view">
						<table class="view-table">
							<tbody>
								<tr>
									<td class="data-title" nowrap>PC NO</td>
									<td class="data-input" colspan="3"><?php echo $rec->pcNo?></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="data-title" width="120px" nowrap>Section</td>
									<td class="data-input" width="300px"><?php echo $rec->division?></td>
									<td class="data-title" width="120px" nowrap>Performed By</td>
									<td class="data-input" width="300px"><?php echo $rec->performedBy?></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="data-title" nowrap>Date</td>
									<td class="data-input"><?php echo date('M d, Y',strtotime($rec->pcDate))?></td>
									<td class="data-title" width="120px" nowrap>Remarks</td>
									<td class="data-input"><?php echo $rec->remarks?></td>
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
							<h4 class="form-label"><i class="icon left la <?php echo $current_module['icon'] ?>"></i> ITEM LIST</h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<div class="datatables_wrapper">
						<div class="table-responsive">
							<table align="left" class="table table-striped hover">
								<thead>
									<tr>
										<th rowspan="2" style="border-left:1px solid #ccc;border-top:1px solid #ccc;border-right:1px solid #ccc;border-bottom:1px solid #ccc"><center>#</center></th>
										<th rowspan="2" style="border-left:1px solid #ccc;border-top:1px solid #ccc;border-right:1px solid #ccc;border-bottom:1px solid #ccc"><center>Category</center></th>
										<th rowspan="2" style="border-left:1px solid #ccc;border-top:1px solid #ccc;border-right:1px solid #ccc;border-bottom:1px solid #ccc"><center>Item Name</center></th>
										<th rowspan="2" style="border-left:1px solid #ccc;border-top:1px solid #ccc;border-right:1px solid #ccc;border-bottom:1px solid #ccc"><center>Description</center></th>
										<th rowspan="2" style="border-left:1px solid #ccc;border-top:1px solid #ccc;border-right:1px solid #ccc;border-bottom:1px solid #ccc"><center>Expiry</center></th>
										<th align="right" style="border-left:1px solid #ccc;border-top:1px solid #ccc;border-right:1px solid #ccc;border-bottom:2px solid #ccc" colspan="2"><div align="center">Quantity</div></th>
										<th rowspan="2" style="border-left:1px solid #ccc;border-top:1px solid #ccc;border-right:1px solid #ccc;border-bottom:1px solid #ccc"><div align="right">Variance</div></th>
										<th rowspan="2" style="border-left:1px solid #ccc;border-top:1px solid #ccc;border-right:1px solid #ccc;border-bottom:1px solid #ccc"><center>Umsr</center></td>
										<th rowspan="2" style="border-left:1px solid #ccc;border-top:1px solid #ccc;border-right:1px solid #ccc;border-bottom:1px solid #ccc"><div align="right">Unit Cost</div></th>
									</tr>
									<tr>
										<th align="right" style="border-left:1px solid #ccc;border-top:1px solid #ccc;border-right:1px solid #ccc;border-bottom:1px solid #ccc"><div align="center">System</div></th>
										<th align="right" style="border-left:1px solid #ccc;border-top:1px solid #ccc;border-right:1px solid #ccc;border-bottom:1px solid #ccc"><div align="center">ONHand</div></th>
									</tr>
								</thead>
								<tbody>
								<?php
								foreach($pcdetails as $row) { $ctr++;
								?>
									<tr>
										<td style="border-left:1px solid #ccc;border-right:1px solid #ccc;">
											<?php echo $ctr;  ?>.
										</td>
										<td style="border-right:1px solid #ccc;"><?php echo $row->category ?></td>
										<td style="border-right:1px solid #ccc;"><?php echo $row->name ?></td>
							    		<td style="border-right:1px solid #ccc;"><?php echo $row->description ?></td>
							    		<td style="border-right:1px solid #ccc;"><?php echo $row->expiry ? date('M d, Y',strtotime($row->expiry)) : '' ?></td>
							    	    <td style="border-right:1px solid #ccc;" align="right"><?php echo $row->systemQty ?></td>
							    		<td style="border-right:1px solid #ccc;" align="right"><?php echo $row->actualQty ?></td>
							    		<td style="border-right:1px solid #ccc;" align="right">
							    			<?php 
							    				if($row->variance > 0) {
							    					echo '<font color="green">+'.$row->variance.'</font>';
							    				} else if($row->variance == 0) {
							    					echo '<font color="green">'.$row->variance.'</font>';
							    				} else {
							    					echo '<font color="red">'.$row->variance.'</font>';
							    				} 
							    			?>
							    		</td>
							    		<td style="border-right:1px solid #ccc;" align="center"><?php echo $row->umsr ?></td>
							    		<td style="border-right:1px solid #ccc;" align="right"><?php echo number_format($row->lastcost,2) ?></td>
									</tr>
							    </tbody>
								<?php 
								}
								for($i=$ctr; $i<=5; $i++) {
									?>
										<tr><td valign="top" colspan="10">&nbsp;</td></tr>
									<?php 
								}
								 ?>
									 <tr height="1px">
									 	<td valign="top" colspan="10" style="border-left:1px solid #ffffff;border-right:1px solid #ffffff;border-bottom:1px solid #ffffff;height:1px;background-color:#ffffff">&nbsp;</td>
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
$('#cmdVoid').click(function(){
	swal({
	      title: "Please Confirm!",
	      text: "Are you sure you want to void this record?",
	      icon: "warning",
	      showCancelButton: true,
	      confirmButtonColor: '#3085d6',
	      cancelButtonColor: '#d33',
	      confirmButtonText: 'Yes',
	      cancelButtonText: 'No'
	    })
	    .then((willDelete) => {
	      if (willDelete.value) {
	    	  window.location = '<?php echo site_url('physical_count/void/').$this->encrypter->encode($rec->$pfield) ?>';
	      }
	    });
});
$('#cmdConfirm').click(function(){
	swal({
	      title: "Please Confirm!",
	      text: "Are you sure you want to confirm this record?",
	      icon: "warning",
	      showCancelButton: true,
	      confirmButtonColor: '#3085d6',
	      cancelButtonColor: '#d33',
	      confirmButtonText: 'Yes',
	      cancelButtonText: 'No'
	    })
	    .then((willDelete) => {
	      if (willDelete.value) {
	    	  window.location = '<?php echo site_url('physical_count/confirm/').$this->encrypter->encode($rec->$pfield) ?>';
	      }
	    });
});
</script>