<table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
	<tr style="font-weight: bold;font-size: 11px;">
		<td class="top left">BANK NAME</td>
		<td class="top left">OWNER NAME</td>
		<td class="top left">ACCOUNT NAME</td>
		<td class="top left">ACCOUNT NO</td>
		<td class="top left">ACCOUNT TYPE</td>
		<td class="top left">BANK ACCOUNT TYPE</td>		
		<td class="top left right" align="center">STATUS</td>
	</tr>
	<?php 
		foreach($records as $row) {
		?>
	<tr>
		<td class="top left"><?php echo $row->bankName ?></td>
		<td class="top left"><?php echo $row->lastName.', '.$row->firstName.' '.$row->middleName ?></td>
		<td class="top left"><?php echo $row->accountName ?></td>
		<td class="top left"><?php echo $row->accountNo ?></td>
		<td class="top left">
			<?php 
				if ($row->accountType == 1) {
					echo "Checking Account (PHP)";
				} else if ($row->accountType == 2) {
					echo "Savings Account (PHP)";
				} else if ($row->accountType == 3) {
					echo "Savings Account (PHP)";
				} else if ($row->accountType == 4) {
					echo "Checking Account (USD)";
				}
			?>
		</td>			
		<td class="top left">
			<?php 
				if ($row->bankAccountType == "Income") {
					echo "Income";
				} else if ($row->bankAccountType == "Expense") {
					echo "Expense";
				} 
			?>
		</td>
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
