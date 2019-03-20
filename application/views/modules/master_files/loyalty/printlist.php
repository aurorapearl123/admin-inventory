<table width="100%" cellspacing="0" cellpadding="6" style="font-size: 12px;">
	<tr style="font-weight: bold;font-size: 11px;">
		<td class="top left">LOYALTY NO</td>
		<td class="top left">CUSTOMER</td>
		<td class="top left">NAME</td>
		<td class="top left">RANK</td>
		<td class="top left">EXPIRY</td>
		<td class="top left right" align="center">STATUS</td>
	</tr>
	<?php 
		foreach ($records as $row) {
		?>
	<tr>
		<td class="top left"><?php echo $row->loyaltyNo ?></td>
		<td class="top left"><?php echo $row->lname.', '.$ow->fname.' '.$row->mname ?></td>
		<td class="top left"><?php echo $row->name ?></td>
		<td class="top left"><?php echo $row->rankName ?></td>
		<td class="top left"><?php echo date('M Y',strtotime($row->expiry)) ?></td>
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
