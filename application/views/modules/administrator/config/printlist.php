<table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
   <tr style="font-weight: bold;font-size: 11px;">
      <td class="top left">CONFIG</td>
      <td class="top left">VALUE</td>
      <td class="top left">DESCRIPTION</td>
   </tr>
   <?php 
      foreach($records as $row) {
      ?>
   <tr>
      <td class="top left"><?php echo $row->name ?></td>
      <td class="top left"><?php echo $row->value ?></td>
      <td class="top left"><?php echo $row->description ?></td>
   </tr>
   <?php 
      }
      ?>
   <tr style="background-color: #ffffff;">
      <td class="top" colspan="3">&nbsp;</td>
   </tr>
</table>