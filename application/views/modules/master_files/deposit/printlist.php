<table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
	<tr style="font-weight: bold;font-size: 11px;">
		<td class="top left">ACCOUNT NAME</td>
		<td class="top left">EMAIL</td>
		<td class="top left">AMOUNT</td>
		<td class="top left">REMARKS</td>
		<td class="top left right" align="center">STATUS</td>
	</tr>
	<?php 
		foreach ($records as $row) {
		?>
	<tr>
		<td class="top left"><?php echo $row->accountName ?></td>
		<td class="top left"><?php echo $row->email ?></td>
		<td class="top left" align="right"><?php echo $row->amount ?></td>
		<td class="top left"><?php echo $row->remarks ?></td>
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
