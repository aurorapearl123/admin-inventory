<table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
	<tr style="font-weight: bold;font-size: 11px;">
		<td class="top left">NAME</td>
		<td class="top left">CONTACT NO</td>
		<td class="top left">CONTACT PERSON</td>
		<td class="top left">EMAIL ADDRESS</td>
		<td class="top left right" align="center">STATUS</td>
	</tr>
	<?php 
		foreach ($records as $row) {
		?>
	<tr>
		<td class="top left"><?php echo $row->suppName ?></td>
		<td class="top left"><?php echo $row->contactNo ?></td>
		<td class="top left"><?php echo $row->contactperson ?></td>
		<td class="top left"><?php echo $row->email ?></td>
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
		<td class="top" colspan="5">&nbsp;</td>
	</tr>
</table>
