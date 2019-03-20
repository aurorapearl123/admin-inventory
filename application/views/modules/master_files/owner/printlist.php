<table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
	<tr style="font-weight: bold;font-size: 11px;">		
		<td class="top left" align="center">LAST NAME</td>
		<td class="top left" align="center">FIRST NAME</td>
		<td class="top left" align="center">MIDDLE NAME</td>
		<td class="top left" align="center">USER NAME</td>
		<td class="top left right" align="center">STATUS</td>
	</tr>
	<?php 
		foreach($records as $row) {
		?>
	<tr>
		<td class="top left"><?php echo $row->lastName  ?></td>
		<td class="top left"><?php echo $row->firstName ?></td>
		<td class="top left"><?php echo $row->middleName  ?></td>
		<td class="top left"><?php echo $row->userName  ?></td>
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
		<td class="top" colspan="7">&nbsp;</td>
	</tr>
</table>
