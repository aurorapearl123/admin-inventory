 <table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
 <tr style="font-weight: bold;font-size: 11px;">
    <td class="top left" align="center">DR NO</td>
    <td class="top left" align="center">DATE</td>
    <td class="top left" align="center">Customer </td>
    <td class="top left" align="center">SO NO</td>
    <td class="top left" align="center">PLATE NO</td>
    <td class="top left" align="center">DRIVER NAME</td>
    <td class="top left" align="center">DEPARTURE</td>  
    <td class="top left right" align="center">STATUS</td>
 </tr>     
           
<?php 
        foreach($records as $row) {
?>
        <tr>
            <td class="top left"><?php echo $row->drNo ?></td>
            <td class="top left"><?php echo date("M d, Y",strtotime($row->drDate));  ?></td>
            <td class="top left"><?php echo $row->companyName ?></td>
            <td class="top left"><?php echo $row->soNo ?></td>
            <td class="top left"><?php echo $row->plateNo; ?></td>
            <td class="top left"><?php echo $row->driverName; ?></td>
            <td class="top left"><?php echo $row->departure; ?></td>
           
            <td class="top left right" align="center">
                <?php           			
            			if($row->status == 1) {
            				echo "<font color='orange'>Pending</font>";
            			} else if($row->status == 2) {
            				echo "<font color='#17a2b8'>Confirmed</font>";            			
                  } else if($row->status == 3) {
                    echo "<font color='#007bff'>Partially Delivered</font>";
                  } else if($row->status == 4) {
                    echo "<font color='green'>Confirmed</font>";
                  } else {
                    echo "<font color='#dc3545'>Cancelled</font>";
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