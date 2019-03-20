<table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
	<tr style="font-weight: bold;font-size: 11px;">
		<td class="top left"></td>
		<td class="top left">NAME</td>
		<td class="top left">USERNAME</td>
		<td class="top left">GROUP</td>
		<td class="top left">ADMINISTRATOR</td>
		<td class="top left">STATUS</td>
	</tr>
	<?php 
		foreach($records as $row) {
		    ?>
	<tr>
		<td class="top left">
			<?php if ($row->imageExtension != '') {?>
			<img src="<?php echo base_url('assets/img/users/'.$row->userID.'_thumb'.$row->imageExtension)?>" class="text-center rounded" style="border-radius:50px" alt="" width="40">
			<?php } else {?>
			<span class="user-text"><?php echo $row->fname[0].$row->lname[0]?></span>
			<?php }?>
		</td>
		<td class="top left"><?php echo $row->lastName.', '.$row->firstName ?></td>
		<td class="top left"><?php echo $row->userName ?></td>
		<td class="top left"><?php echo $row->groupName ?></td>
		<td class="top left"><?php if ($row->isAdmin){ echo 'Yes';} else { echo 'No';}  ?></td>
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
