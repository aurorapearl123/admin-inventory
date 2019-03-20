<table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
	<tr style="font-weight: bold;font-size: 11px;">
		<td class="top left">CODE</td>
		<td class="top left">NAME</td>
		<td class="top left">DESIGNATION 1</td>
		<td class="top left">DESIGNATION 2</td>
		<td class="top left">DESIGNATION 3</td>
		<td class="top left right" align="center">STATUS</td>
	</tr>
	<?php 
		foreach ($records as $row) {
		?>
	<tr>
		<td class="top left"><?php echo $row->code ?></td>
		<td class="top left"><?php echo $row->fullname ?></td>
		<td class="top left"><?php echo $row->designation1 ?></td>
		<td class="top left"><?php echo $row->designation2 ?></td>
		<td class="top left"><?php echo $row->designation3 ?></td>
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
		<td class="top" colspan="6">&nbsp;</td>
	</tr>
</table>
