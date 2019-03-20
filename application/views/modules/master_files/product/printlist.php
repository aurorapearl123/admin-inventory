<table width="100%" cellspacing="0" cellpadding="6" style="font-size: 12px;">
	<tr style="font-weight: bold;font-size: 11px;">
		<td class="top left">CATEGORY</td>
		<td class="top left">PRODUCT CODE</td>
		<td class="top left">PRODUCT</td>
		<td class="top left">PRICE</td>
		<td class="top left">REORDER LVL</td>
		<td class="top left right" align="center">STATUS</td>
	</tr>
	<?php 
		foreach($records as $row) {
		?>
	<tr>
		<td class="top left"><?php echo $row->category ?></td>		
		<td class="top left"><?php echo $row->productCode ?></td>
		<td class="top left"><?php echo $row->name ?></td>
		<td class="top left" align="right"><?php echo number_format($row->avecost,2) ?></td>
		<td class="top left" align="right"><?php echo number_format($row->reorderLvl) ?></td>
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
