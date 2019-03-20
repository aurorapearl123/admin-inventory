<table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
	<tr style="font-weight: bold;font-size: 11px;">
		<td class="top left">IAR DATE.</td>
		<td class="top left">RR No.</td>
		<td class="top left">PO NO.</td>
		<td class="top left">DR NO.</td>
		<td class="top left">REQUISITIONING OFFICE</td>
		<td class="top left right" align="center">STATUS</td>
	</tr>
	<?php 
		foreach($records as $row) {
		?>


	<tr>
		<td class="top left"><?php echo date('Y-m-d', strtotime($row->iarDate)); ?></td>
		<td class="top left"><?php echo $row->iarNo ?></td>
		<td class="top left"><?php echo $row->poNo ?></td>
		<td class="top left"><?php echo $row->drNo ?></td>
		<td class="top left"><?php echo $row->requisitioningOffice ?></td>

		<td class="top left right" align="center">
			<?php 
			if ($row->status == 1) {
				echo "<span class='badge badge-pill badge-warning'>Pending</span>";
			} else if ($row->status == 0) {
				echo "<span class='badge badge-pill badge-danger'>Cancelled</span>";
			} else if ($row->status == 2) {
				echo "<span class='badge badge-pill badge-info'>Inspected</span>";
			} else if ($row->status == 3) {
				echo "<span class='badge badge-pill badge-success'>Accepted</span>";
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
