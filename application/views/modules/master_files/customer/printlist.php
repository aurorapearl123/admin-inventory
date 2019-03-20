<table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
	<tr style="font-weight: bold;font-size: 11px;">
		<td class="top left" align="center">COMPANY NAME</td>
		<td class="top left" align="center">LAST NAME</td>
		<td class="top left" align="center">FIRST NAME</td>
		<td class="top left" align="center">CONTACT NO</td>
		<td class="top left" align="center">GENDER</td>
		<td class="top left" align="center">CREDIT LIMIT</td>
		<td class="top left right" align="center">STATUS</td>
	</tr>
	<?php 
		foreach($records as $row) {
		?>
	<tr>
		<td class="top left"><?php echo $row->companyName == ''?'-': $row->companyName ?></td>
		<td class="top left"><?php echo $row->lname ==''?'-': $row->lname ?></td>
		<td class="top left"><?php echo $row->fname == ''?'-': $row->fname ?></td>
		<td class="top left"><?php echo $row->mname == ''?'-': $row->contactNo ?></td>

		<td class="top left right" align="center">
			<?php 
				if ($row->gender == "M") {
					echo "Male";
				} else {
					echo "Female";
				}
				?>
		</td>
		<td class="top left" align="right"><?php echo $row->creditLimit?></td>
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
