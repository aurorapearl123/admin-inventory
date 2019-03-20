 <table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
 <tr style="font-weight: bold;font-size: 11px;">
    <td class="top left" align="center">DATE</td>
    <td class="top left" align="center">PC NO</td>
    <td class="top left" align="center">SECTION</td>
    <td class="top left" align="center">PERFORMED BY</td>
    <td class="top left right" align="center">STATUS</td>
 </tr>
<?php 
        foreach($records as $row) {
?>
        <tr>
            <td class="top left"><?php echo date('M d, Y',strtotime($row->pcDate)) ?></td>
            <td class="top left"><?php echo $row->pcNo ?></td>
            <td class="top left"><?php echo $row->division ?></td>
            <td class="top left"><?php echo $row->performedBy ?></td>
            <td class="top left right" align="center">
                <?php 
          			if ($row->status == 1) {
          			    echo "<font color='green'>Pending</font>";
          			} else if($row->status == 2) {
          				echo "<font color='red'>Confirmed</font>";
          			}  else if($row->status == 0) {
          				echo "<font color='red'>Cancelled</font>";
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