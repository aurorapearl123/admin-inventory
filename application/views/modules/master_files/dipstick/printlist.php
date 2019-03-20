<table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
	<tr style="font-weight: bold;font-size: 11px;">
		<td class="top left">TIME IN</td>
		<td class="top left">TIME OUT</td>
		<td class="top left">OPENING QTY</td>
		<td class="top left">CLOSING QTY</td>
		<td class="top left">VARIANCE QTY</td>
		<td class="top left">OPENING MSR</td>
		<td class="top left">CLOSING MSR</td>
		<td class="top left">VARIANCE MSR</td>
		<td class="top left right" align="center">STATUS</td>
	</tr>
	<?php 
		foreach ($records as $row) {
		?>
	<tr>
		<td class="top left"><?php echo date('h:i A', strtotime($row->inTime)) ?></td>
		<td class="top left"><?php echo date('h:i A', strtotime($row->outTime)) ?></td>
		<td class="top left"><?php echo $row->openingQty ?></td>
		<td class="top left"><?php echo $row->closingQty ?></td>
		<td class="top left"><?php echo $row->varianceQty ?></td>
		<td class="top left"><?php echo $row->openingMsr ?></td>
		<td class="top left"><?php echo $row->closingMsr ?></td>
		<td class="top left"><?php echo $row->varianceMsr ?></td>
		<td class="top left right" align="center">
			<?php 
				if ($row->status == 1) {
					echo "<font color='green'>Active</font>";
				} else {
					echo "<font color='red'>Inactive</font>";
				}
				?>
		</td>
	</tr>
	<?php 
		}
		?>
	<tr style="background-color: #ffffff;">
		<td class="top" colspan="9">&nbsp;</td>
	</tr>
</table>
