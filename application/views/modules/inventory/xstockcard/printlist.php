<table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
    <tr style="font-weight: bold;font-size: 11px;">
        <td class="top left">STOCK NO.</td>
        <td class="top left">ITEM DESCRIPTION</td>
        <td class="top left">OFFICE / DEPT.</td>
        <td class="top left">CATEGORY</td>
        <td class="top left">EXPIRY</td>
        <td class="top left">ENDING BAL</td>
        <td class="top left right" align="center">STATUS</td>
    </tr>
    <?php 
    foreach($records as $row) {
        ?>
        <tr>
            <td class="top left"><?php echo $row->itemCode ?></td>
            <td class="top left"><?php echo $row->name.' '.$row->description ?></td>
            <td class="top left"><?php echo $row->officeName.'/'.$row->division ?></td>
            <td class="top left"><?php echo $row->category ?></td>
            <td class="top left"><?php echo $row->expiry ?></td>
            <td class="top left"><?php echo $row->endBal ?></td>
            <td class="top left right" align="center">
                <?php 
                if ($row->status == 1) {
                    echo "<span class='badge badge-pill badge-success'>Active</span>";
                } else {
                    echo "<span class='badge badge-pill badge-danger'>Inactive</span>";
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


