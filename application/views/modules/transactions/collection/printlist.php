 <table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
 <tr style="font-weight: bold;font-size: 11px;">
    <td class="top left" align="center">COLLECTION NO</td>
    <td class="top left" align="center">OR NO</td>
    <td class="top left" align="center">INVOICE NO </td>
    <td class="top left" align="center">OR NO</td>
    
    <td class="top left right" align="center">STATUS</td>
 </tr>     
           
<?php 
        foreach($records as $row) {
?>
        <tr>
            <td class="top left"><span><?php echo $row->collectionNo ?></span></td>
                                                <td class="top left"><span><?php echo $row->collectionDate == '0000-00-00' ? '' : date('M d,Y',strtotime($row->collectionDate)); ?></span></td>
                                                <td class="top left"><span><?php echo $row->invoiceNo ?></span></td>
                                                <td class="top left"><span><?php echo $row->orNo ?></span></td>
                                                
                                                <td class="top left right" align="center">
                                                    <?php 
                                                    if ($row->status == 1) {
                                                        echo "<span class='badge badge-pill badge-warning'>Pending</span>";
                                                    } else if ($row->status == 2){
                                                        echo "<span class='badge badge-pill badge-info'>Confirmed</span>";
                                                    } else if ($row->status == 3){
                                                        echo "<span class='badge badge-pill badge-primary'>Partially Delivered</span>";
                                                    } else if ($row->status == 4){
                                                        echo "<span class='badge badge-pill badge-success'>Closed</span>";
                                                    } else {
                                                        echo "<span class='badge badge-pill badge-danger'>Cancelled</span>";
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