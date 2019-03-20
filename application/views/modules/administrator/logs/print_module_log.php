<h4 align="center"><u>Module Activity Logs</u></h4>
<br>

<table class="tabDetailView" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td class="tabDetailViewDL" width="80">Module</td>
        <td class="tabDetailViewDF"><?php echo ucwords(strtolower($module)); ?></td>
        <td class="tabDetailViewDL" width="80" nowrap>Date Range</td>
        <td class="tabDetailViewDF"><?php echo date('m/d/Y',strtotime($startDate)).' - To - '.date('m/d/Y',strtotime($endDate)); ?> </td>
    </tr>
</table>

<br>

<table class="listView" border="0" cellpadding="0" cellspacing="0" width="100%">
<tbody>
	<tr height="20">
		<td scope="col" class="listViewThS1" nowrap>Date/Time</td>
		<td scope="col" class="listViewThS1" nowrap>Workstation</td>
		<td scope="col" class="listViewThS1" nowrap>User</td>
		<td scope="col" class="listViewThS1" nowrap>Operation</td>
		<td scope="col" class="listViewThS1" nowrap>Logs</td>
	</tr>
	<tr>
		<td colspan="20" height="1" class="listViewHRS1"></td>
	</tr>
	
	<?php
	if (!empty($records)) {
		foreach($records as $row) {
	?>
    	<tr height="20">
    		<td scope="row" class="oddListRowS1" bgcolor="#ffffff" align="left" valign="top"><span sugar="sugar0b"><?php echo $row['date'] ?></span></td>
    		<td scope="row" class="oddListRowS1" bgcolor="#ffffff" align="left" valign="top"><span sugar="sugar0b"><?php echo $row['host'] ?></span></td>
    		<td scope="row" class="oddListRowS1" bgcolor="#ffffff" align="left" valign="top"><span sugar="sugar0b"><?php echo $row['user'] ?></span></td>
    		<td scope="row" class="oddListRowS1" bgcolor="#ffffff" align="left" valign="top"><span sugar="sugar0b"><?php echo $row['operation'] ?></span></td>
    		<td scope="row" class="oddListRowS1" bgcolor="#ffffff" align="left" valign="top"><span sugar="sugar0b"><?php echo $row['logs'] ?></span></td>
    	</tr>
    	<tr>
    		<td colspan="20" height="1" class="listViewHRS1"></td>
    	</tr>
	<?php
		}
	} else {
	?>
    	<tr>
    		<td colspan="20" class="oddListRowS1">
            	<table border="0" cellpadding="0" cellspacing="0" width="100%">
            	<tbody>
            	<tr>
            		<td nowrap="nowrap" align="center"><b><i>No results found.</i></b></td>
            	</tr>
            	</tbody>
            	</table>
    		</td>
    	</tr>
	<?php
	}
	?>
</tbody>
</table>


<script language="javascript">
addToValidate('frmLogs','userID', '', true, 'User');
addToValidate('frmLogs','startDate', '', true, 'Start Date');
addToValidate('frmLogs','endDate', '', true, 'End Date');

$(function(){
	// Datepicker
	$('#startDate').datepicker({
		inline: true
	});
	
	$('#endDate').datepicker({
		inline: true
	});
});
</script>