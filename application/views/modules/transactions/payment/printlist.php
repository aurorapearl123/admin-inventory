 <table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
 <tr style="font-weight: bold;font-size: 11px;">
    <td class="top left bottom" align="center">PAYMENT NO</td>
    <td class="top left bottom" align="center">PAYMENT DATE</td>
    <td class="top left bottom" align="center">SUPPLIER </td>
    <td class="top left bottom" align="center">INVOICE NO</td>
    <td class="top left bottom" align="center">OR NO</td>   
    <td class="top left bottom right" align="center">STATUS</td>
 </tr>     
           
<?php 
        foreach($records as $row) {
?>
        <tr>
            <td class="right"><span><?php echo $row->paymentNo ?></span></td>
            <td class="right"><span><?php echo $row->paymentDate == '0000-00-00' ? '' : date('M d,Y',strtotime($row->paymentDate)); ?></span></td>
            <td class="right"><span><?php echo $row->suppName ?></span></td>
            <td class="right"><span><?php echo $row->invoiceNo ?></span></td>
            <td class="right"><span><?php echo $row->orNo ?></span></td>
           
            <td class="top left right" align="center">
                <?php           			
            			if($row->status == 1) {
            				echo "<font color='orange'>Pending</font>";
            			} else if($row->status == 2) {
            				echo "<font color='#17a2b8'>Confirmed</font>";            			
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