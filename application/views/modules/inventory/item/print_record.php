<style>
.checkBox { width:18px;height:18px;vertical-align:middle; }
.bright { border-right: 0.5px solid #cccccc;}
.bleft { border-left: 0.5px solid #cccccc;}
.btop { border-top: 0.5px solid #cccccc;}
.bbottom { border-bottom: 0.5px solid #cccccc;}
.bbottom-black { border-bottom: 0.5px solid #000000;}
.ball { border: 1px solid black;}
.label { height: 2px; font-size:8pt;  }
.field { height: 2px; font-size:8pt; }
.table_header {  font-size:8pt; }
.table_row { height: 20px; font-size:8pt; vertical-align:middle; }
.signatory { background-color: #eee; height: 20px; font-size:8pt; }
.signatory_table { position: absolute; bottom: 0;}
.table-box {border: 0.5px solid #000000;}

</style>

<!-- Header Start -->
<table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
<tbody>
	<tr>
		<td width="33%"><img src="<?php echo base_url('assets/img/main/logo.png') ?>" style="width: 200px;"/></td>
		<td width="33%" valign="bottom"></td>
		<td width="33%" valign="bottom" align="right">
			<table class="table-box" style="padding-bottom: 5px;">
				<tbody>
					<tr>
						<td valign="center" align="left">
							<span style="font-size: 7px;">LHPMC-HRD-LF</span>
						</td>
					</tr>
					<tr>
						<td valign="center" align="left">
							<span style="font-size: 7px">REV. 0</span>
						</td>
					</tr>
					<tr>
						<td valign="center" align="left">
							<span style="font-size: 7px">15 FEB 2016</span>
						</td>
					</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" width="100%" height="50px" valign="middle" align="center"><strong style="font-size: 16px">LEAVE FORM</strong></td>
	</tr>
</tbody>
</table>
<!-- Header End -->




<!-- Employee Information -->
<table border="0" cellpadding="0" cellspacing="0" style="width:100%; margin-top: 10px;">
	<tbody>
	<tr class="">
	<td valign="middle" align="left" width="10%">
		<span style="font-size: 12px">Name: </span> 
	</td>

	<td class="bbottom-black" valign="middle" align="left" width="30%">
		<strong style="font-size: 12px">&nbsp;<?php echo strtoupper($rec->lname.', '.$rec->fname.' '.$rec->mname.' '.$rec->suffix)?></strong> 
	</td>

	<td width="30%">&nbsp;</td>

	<td valign="middle" align="left" width="10%">
		<span style="font-size: 12px">Date Filed: </span> 
	</td>

	<td class="bbottom-black" valign="middle" align="left" width="20%">
		<strong style="font-size: 12px">&nbsp;<?php echo strtoupper(date('F d, Y', strtotime($rec->dateFiled)))?></strong> 
	</td>
</tr>

	<tr class="">
	<td valign="middle" align="left">
		<span style="font-size: 12px">Department: </span> 
	</td>

	<td class="bbottom-black" valign="middle" align="left">
		<strong style="font-size: 12px">&nbsp;<?php echo strtoupper($rec->deptName)?></strong> 
	</td>

	<td></td>

	<td valign="middle" align="left">
		<span style="font-size: 12px">Branch: </span> 
	</td>

	<td  class="bbottom-black" valign="middle" align="left">
		<strong style="font-size: 12px">&nbsp;<?php echo strtoupper($rec->branchName)?></strong> 
	</td>
</tr>
</tbody>
</table>





<!-- Leave Type -->
<table border="0" cellpadding="0" cellspacing="0" style="width:100%;  margin-top: 20px;">
	<tbody>
<tr class="">
<?php 
$ctr = 0;
$leave_types = $this->db->get('leave_types')->result();

foreach ($leave_types as $row) {
	$ctr++;
	if ($ctr % 4 == 0) {
    echo '</tr>';
    echo '<tr>';
  	}
	?>
	<td height="30px" colspan="" nowrap valign="middle" align="left">
		<?php if ($row->leaveTypeID == $rec->leaveTypeID) { ?>
			<img src="http://localhost/project/lhprime/assets/img/main/ballot_box_with_check.png" style="width: 10px;height: 10px;";/>
		<?php } else { ?>
			<img src="http://localhost/project/lhprime/assets/img/main/ballot_box.png" style="width: 10px;height: 10px;";/>
		<?php } ?>
		
		<span><?php echo $row->leaveType?></span>
    	</td>
    </tr>
<?php }
?>
</tbody>
</table>





<!-- Period -->
<table class="bright bleft btop bbottom" border="0" cellpadding="0" cellspacing="0" style="width:100%; margin-top: 10px;">
	<tr>
		<td height="25px;" align="center"><span style="font-size: 12px">FROM (Date/Time)</span></td>
		<td height="25px;" align="center"><span style="font-size: 12px">TO (Date/Time)</span></td>
	</tr>
	<?php 
	if ($leave_dates->num_rows()) {
		foreach ($leave_dates->result() as $row) {?>
	<tr class="">
		<td colspan="" nowrap valign="middle" align="center" width="50%" height="25px;">
			<span style="font-size: 12px">&nbsp;<?php echo strtoupper(date('F d, Y', strtotime($row->startDate)));?> <?php echo (date('H:i', strtotime($row->startDate)) != '00:00') ? strtoupper(date('h:i A', strtotime($row->startDate))) : '';?></span> 
		</td>
		<td colspan="" nowrap valign="middle" align="center" width="50%">
			<span style="font-size: 12px">&nbsp;<?php echo strtoupper(date('F d, Y', strtotime($row->endDate)));?> <?php echo (date('H:i', strtotime($row->endDate)) != '00:00') ? strtoupper(date('h:i A', strtotime($row->endDate))) : '';?></span> 
		</td>
	</tr>

		<?php if ($leave_dates->num_rows() < 3) { ?>
			<?php $default = 3 - $leave_dates->num_rows(); ?>
			<?php $i = 0; ?>
			<?php while ($i < $default) { ?>
				<?php $i++; ?>
				<tr class="">
					<td colspan="" nowrap valign="middle" align="center" width="50%" height="25px;"></td>
					<td colspan="" nowrap valign="middle" align="center" width="50%"></td>
				</tr>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<?php } else { ?>
	<tr class="">
		<td colspan="" nowrap valign="middle" align="center" width="50%" height="25px;"></td>
		<td colspan="" nowrap valign="middle" align="center" width="50%"></td>
	</tr>
	<tr class="">
		<td colspan="" nowrap valign="middle" align="center" width="50%" height="25px;"></td>
		<td colspan="" nowrap valign="middle" align="center" width="50%"></td>
	</tr>
<?php  } ?>
</table>


<!-- Reasons -->
<table border="0" cellpadding="0" cellspacing="0" style="width:100%; margin-top: 20px;">
<tr class="">
	<td height="80px" colspan="1" nowrap valign="top" align="left" width="">
		<span style="font-size: 12px">REASON/S:</span> 
		<strong style="font-size: 12px">&nbsp;<?php echo strtoupper($rec->reason)?></strong> 
	</td>
</tr>
</table>









