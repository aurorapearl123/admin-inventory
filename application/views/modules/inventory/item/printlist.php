 <table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
 <tr style="font-weight: bold;font-size: 11px;">
    <td class="top left" align="center">CATEGORY</td>
    <td class="top left" align="center">ITEM CODE</td>
    <td class="top left" align="center">ITEM</td>
    <td class="top left" align="center">DESCRIPTION</td>
    <td class="top left" align="center">PRICE</td>
    <td class="top left" align="center">BRAND</td>
    <td class="top left" align="center">REORDER LVL</td>
    <td class="top left right" align="center">STATUS</td>
 </tr>
<?php 
        foreach($records as $row) {
?>
        <tr>
            <td class="top left"><?php echo $row->category ?></td>
            <td class="top left"><?php echo $row->itemCode ?></td>
            <td class="top left"><?php echo $row->name ?></td>
            <td class="top left"><?php echo $row->description ?></td>
            <td class="top left"><?php echo $row->avecost ?></td>
            <td class="top left"><?php echo $row->brand ?></td>
            <td class="top left"><?php echo $row->reorderLvl ?></td>
            <td class="top left right" align="center">
                <?php 
          			if ($row->status == 1) {
          			    echo "<font color='green'>Active</font>";
          			} else if($row->status == 0) {
          				echo "<font color='red'>Inactive</font>";
          			} 
          		?>
            </td>
        </tr>
<?php 
        }
?>
<tr style="background-color: #ffffff;">
    <td class="top" colspan="8">&nbsp;</td>
</tr>
</table>