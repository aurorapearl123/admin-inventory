<table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
	<tr style="font-weight: bold;font-size: 11px;">
		<td class="top left">DATE</td>
		<td class="top left">BRANCH NAME</td>
		<td class="top left">BRAND</td>
		<td class="top left">ITEM</td>
		<td class="top left">DESCRIPTION</td>
		<td class="top left">UMSR</td>
		<td class="top left">ADJUSTMENT TYPE</td>
		<td class="top left">QTY</td>
		<td class="top left right" align="center">STATUS</td>
	</tr>
	<?php 
		foreach ($records as $row) {
		?>

	<tr>
		<td class="top left"><?php echo $row->adjNo ?></td>
		<td class="top left"><?php echo date('F d Y', strtotime($row->dateInserted)) ?></td>
		<td class="top left"><?php echo $row->officeName ?></td>
		<td class="top left"><?php echo $row->brand ?></td>
		<td class="top left"><?php echo $row->name ?></td>
		<td class="top left"><?php echo $row->umsr ?></td>
		<td class="top left">
			
			<?php 
				if ($row->adjType == 'DR') {
					echo "Debit";
				} else if ($row->adjType == 'CR') {
					echo "Credit";
				}
			?>
		</td>
		<td class="top left"><?php echo $row->qty ?></td>
		<td class="top left right" align="center">
			<?php 
				if ($row->status == 1) {
					echo "<font color='orange'>Pending</font>";
				} else if ($row->status == 2) {
					echo "<font color='green'>Confirmed</font>";
				} else if ($row->status == 0) {
					echo "<font color='red'>Cancelled</font>";
				}
			?>
		</td>
	</tr>
	<?php 
		}
		?>
	<tr style="background-color: #ffffff;">
		<td class="top" colspan="10">&nbsp;</td>
	</tr>
</table>
