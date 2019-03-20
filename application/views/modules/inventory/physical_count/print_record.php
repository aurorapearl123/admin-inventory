<style>
table{
    border-collapse: collapse;
	font-size:8pt;
}
.checkBox { width:18px;height:18px;vertical-align:middle; }
.bright { border-right: 0.5px solid #cccccc;}
.bleft { border-left: 0.5px solid #cccccc;}
.btop { border-top: 0.5px solid #cccccc;}
.bbottom { border-bottom: 0.5px solid #cccccc;}
.bbottom-black { border-bottom: 0.5px solid #cccccc; height:30px;}
.ball { border: 1px solid black;}
.label { height: 2px; font-size:8pt;  }
.field { height: 2px; font-size:8pt; }
.table_header { height: 30px; font-size:8pt; background-color:#eeeeee; padding-left:5px; padding-right:5px;}
.table_row { height: 30px; font-size:8pt; padding-left:5px; padding-right:5px;}
.signatory { background-color: #eee; height: 20px; font-size:8pt; }
.signatory_table { position: absolute; bottom: 0;}
.table-box {border: 0.5px solid #000000;}
</style>
<table width="100%">
	<thead>
		<tr class="table_header" style="height:50px">
			<td align="left" colspan="7" class="btop bbottom bleft bright" style="height:30px; padding-left:5px; padding-right:5px;"><strong>PHYSICAL COUNT</strong></td>
		</tr>
	</thead>
	<tr>
		<td class="btop bbottom" style="height:30px; padding-left:5px; padding-right:5px; width:50px">SECTION</td>
		<td class="btop bbottom" style="height:30px; padding-left:5px; padding-right:5px; width:300px">: &nbsp;&nbsp;&nbsp;<?php echo strtoupper($rec->division)?></td>
		<td class="btop bbottom" style="height:30px; padding-left:5px; padding-right:5px; width:10px">&nbsp;</td>
		<td class="btop bbottom" style="height:30px; padding-left:5px; padding-right:5px; width:100px">PERFORMED BY</td>
		<td class="btop bbottom" style="height:30px; padding-left:5px; padding-right:5px;" colspan="3">: &nbsp;&nbsp;&nbsp;<?php echo strtoupper($rec->performedBy)?></td>
	</tr>
	<tr>
		<td class="btop bbottom" style="height:30px; padding-left:5px; padding-right:5px; width:50px">DATE</td>
		<td class="btop bbottom" style="height:30px; padding-left:5px; padding-right:5px; width:300px">: &nbsp;&nbsp;&nbsp;<?php echo strtoupper(date('M d, Y',strtotime($rec->pcDate)))?></td>
		<td class="btop bbottom" style="height:30px; padding-left:5px; padding-right:5px; width:10px">&nbsp;</td>
		<td class="btop bbottom" style="height:30px; padding-left:5px; padding-right:5px; width:50px">REMARKS</td>
		<td class="btop bbottom" style="height:30px; padding-left:5px; padding-right:5px;">: &nbsp;&nbsp;&nbsp;<?php echo strtoupper($rec->remarks)?></td>
		<td class="btop bbottom" style="height:30px; padding-left:5px; padding-right:5px; width:50px">STATUS</td>
		<td class="btop bbottom" style="height:30px; padding-left:5px; padding-right:5px;">: &nbsp;&nbsp;&nbsp;
			<?php 
			if($rec->status==1){ 
				echo "<font color='green'>Pending</font>"; 
			} else if($rec->status==2){ 
				echo "<font color='green'>Confirmed</font>"; 
			} else { 
				echo "<font color='red'>Cancelled</font>"; 
			}?>
		</td>
	</tr>
	<tr>
		<td colspan="5" style="height:5px; padding-left:5px; padding-right:5px;">&nbsp;</td>
	</tr>
</table>

<table width="100%">
	<thead>
		<tr class="table_header" style="height:50px">
			<th rowspan="2" class="bleft bright btop bbottom" style="height:30px; width:5%"><center>#</center></th>
			<th rowspan="2" class="bright btop bbottom" style="height:30px; width:15%"><center>Category</center></th>
			<th rowspan="2" class="bright btop bbottom" style="height:30px; width:14%"><center>Item Name</center></th>
			<th rowspan="2" class="bright btop bbottom" style="height:30px; width:11%"><center>Description</center></th>
			<th rowspan="2" class="bright btop bbottom" style="height:30px; width:15"><center>Expiry</center></th>
			<th class="bright btop bbottom" colspan="2" style="height:30px; width:16%"><div align="center">Quantity</div></th>
			<th rowspan="2" class="bright btop bbottom" style="height:30px; width:7%"><div align="right">Variance</div></th>
			<th rowspan="2" class="bright btop bbottom" style="height:30px; width:10%"><center>Umsr</center></td>
			<th rowspan="2" class="bright btop bbottom" style="height:30px; width:7%"><div align="right">Unit Cost</div></th>
		</tr>
		<tr class="table_header" style="height:50px">
			<th align="center" class="bright bbottom" style="height:30px; width:8%"><div align="center">System</div></th>
			<th align="center" class="bright bbottom" style="height:30px; width:8%"><div align="center">ONHand</div></th>
		</tr>
	</thead>
	<?php
	foreach($pcdetails as $row) { $ctr++;
	?>
	<tbody>
		<tr>
			<td align="left" class="bbottom bleft bright" style="height:30px; padding-left:5px; padding-right:5px;">
				<?php echo $ctr;  ?>.
			</td>
			<td align="left" class="bbottom bright" style="height:30px; padding-left:5px; padding-right:5px;"><?php echo $row->category ?></td>
			<td align="left" class="bbottom bright" style="height:30px; padding-left:5px; padding-right:5px;"><?php echo $row->name ?></td>
	    	<td align="left" class="bbottom bright" style="height:30px; padding-left:5px; padding-right:5px;"><?php echo $row->description ?></td>
	    	<td align="left" class="bbottom bright" style="height:30px; padding-left:5px; padding-right:5px;"><?php echo $row->expiry ? date('M d, Y',strtotime($row->expiry)) : '' ?></td>
		    <td align="center" class="bbottom bright"><?php echo $row->systemQty ?></td>
		    <td align="center" class="bbottom bright"><?php echo $row->actualQty ?></td>
		    <td align="center" class="bbottom bright">
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
		    <td class="bbottom bright"><?php echo $row->umsr ?></td>
		    <td align="right" class="bbottom bright"><?php echo number_format($row->lastcost,2) ?></td>
		</tr>
    </tbody>
	<?php 
}
for($i=$ctr; $i<=5; $i++) {
	?>
		<tr><td valign="top" colspan="9">&nbsp;</td></tr>
	<?php 
}
 ?>
</table>

