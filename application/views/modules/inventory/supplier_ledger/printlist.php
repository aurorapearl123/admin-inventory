<table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
    <tr style="font-weight: bold;font-size: 11px;">
        <td class="top left">SUPPLIER</td>
        <td class="top left">STOCK NO.</td>
        <td class="top left">ITEM DESCRIPTION</td>
        <td class="top left">CATEGORY</td>
        <td class="top left">ENDING BAL</td>
        <td class="top left right" align="center">STATUS</td>
    </tr>
    <?php 
    foreach($records as $row) {
        ?>
        <tr>
            <td class="top left"><?php echo $row->suppName ?></td>
            <td class="top left"><?php echo $row->productCode ?></td>
            <td class="top left"><?php echo $row->name.' '.$row->description ?></td>
            <td class="top left"><?php echo $row->category ?></td>
            <td class="top left"><?php echo $row->endBal ?></td>
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