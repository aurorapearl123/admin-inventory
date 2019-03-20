 <table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
 <tr style="font-weight: bold;font-size: 11px;">
    <td class="top left">Date/Time</td>
    <td class="top left">WORKSTATION</td>
    <td class="top left">MODULE</td>
    <td class="top left">OPERATION</td>
    <td class="top left">LOGS</td>

 </tr>
<?php 
        foreach($records as $row) {
?>
        <tr>
            <td class="top left"><?php echo $row->date ?></td>
            <td class="top left"><?php echo $row->host ?></td>
            <td class="top left"><?php echo $row->module ?></td>
            <td class="top left"><?php echo $row->operation ?></td>
            <td class="top left"><?php echo $row->logs ?></td>
        </tr>
<?php 
        }
?>
<tr style="background-color: #ffffff;">
    <td class="top" colspan="7">&nbsp;</td>
</tr>
</table>