<table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
 <tr style="font-weight: bold;font-size: 11px;">
    <td class="top left" align="center">ANCILLARY</td>
    <td class="top left" align="center">ITEM</td>
    <td class="top left" align="center">DESCRIPTON</td>
    <td class="top left" align="center">QUANTITY</td>
    <td class="top left" align="center">PRICE</td>
    <td class="top left" align="center">REORDER LVL</td>
    <td class="top left" align="center">CRITICAL LVL</td>
    <td class="top left right" align="center">STATUS</td>
 </tr>
<?php 
        foreach($records as $row) {
?>
        <tr>
        <?php $item_name = $row->brand ? $row->name.'( '.$row->brand.' ) ' : $row->name?>
            <td class="top left"><?php echo $row->division ?></td>
            <td class="top left"><?php echo $item_name ?></td>
            <td class="top left"><?php echo $row->item_description ?></td>
            <td class="top left"><?php echo $row->qty ?></td>
            <td class="top left"><?php echo $row->avecost ?></td>
            <td class="top left"><?php echo $row->inventory_reorderLvl ?></td>
            <td class="top left"><?php echo $row->inventory_criticalLvl ?></td>

           
            <td class="top left right" align="center">
                <?php 
          			if ($row->status == 1) {
          			    echo "<font color='green'>Active</font>";
          			} else if($row->status == 0) {
          				echo "<font color='red'>Inactive</font>";
          			} else if($row->status == 2) {
          				echo "<font color='red'>Retired</font>";
          			} else if($row->status == 3) {
          				echo "<font color='red'>Deceased</font>";
          			}
          		?>
            </td>
        </tr>
<?php 
        }
?>
<tr style="background-color: #ffffff;">
    <td class="top" colspan="9">&nbsp;</td>
</tr>
</table>