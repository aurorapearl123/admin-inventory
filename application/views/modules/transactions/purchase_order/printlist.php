 <table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
 <tr style="font-weight: bold;font-size: 11px;">
    <td class="top left" align="center">PO NO</td>
    <td class="top left" align="center">DATE</td>
    <td class="top left" align="center">SUPPLIER </td>
    <td class="top left" align="center">NET AMOUNT</td>
    <td class="top left" align="center">BALANCE</td>
    <td class="top left" align="center">DUE DATE</td>
    
    <td class="top left" align="center">PAYMENT STATUS</td>     
    <td class="top left right" align="center">STATUS</td>
 </tr>     
           
<?php 
        foreach($records as $row) {
?>
        <tr>
            <td class="top left"><?php echo $row->poNo ?></td>
            <td class="top left"><?php echo date("M d, Y",strtotime($row->poDate));  ?></td>
            <td class="top left"><?php echo $row->suppName ?></td>
            <td class="top left"><?php echo $row->netAmount ?></td>
            <td class="top left"><?php echo $row->balance ?></td>
            <td class="top left"><?php echo date("M d, Y",strtotime($row->dueDate)); ?></td>
            
            <td><span 
                <?php 
                if (date('M d,Y') > date('M d,Y',strtotime($row->dueDate)) && $row->status != 1) {
                    echo " style='color: #de252d; font-weight: bold' ";
                }
                ?>
                >
                <?php echo $row->dueDate == '0000-00-00' ? '' : date('M d,Y',strtotime($row->dueDate)); ?>
            </span>
        </td>
           
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
    <td class="top" colspan="9">&nbsp;</td>
</tr>
</table>