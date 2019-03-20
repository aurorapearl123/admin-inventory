 <table width="100%" cellspacing="0" cellpadding="5" style="font-size: 12px;">
 <tr style="font-weight: bold;font-size: 11px;">
    <td class="top left" align="center">ID NUMBER</td>
    <td class="top left" align="center">LAST NAME</td>
    <td class="top left" align="center">FIRST NAME</td>
    <td class="top left" align="center">MIDDLE NAME</td>
    <td class="top left" align="center">BIRTH DATE</td>
    <td class="top left" align="center">AGE</td>
    <td class="top left" align="center">SEX</td>
    <td class="top left" align="center">CIVIL STATUS</td>
    <td class="top left right" align="center">STATUS</td>
 </tr>
<?php 
        foreach($records as $row) {
?>
        <tr>
            <td class="top left"><?php echo $row->empNo ?></td>
            <td class="top left"><?php echo $row->lname ?></td>
            <td class="top left"><?php echo $row->fname ?></td>
            <td class="top left"><?php echo $row->mname ?></td>
            <td class="top left">
                <?php 
                  if($row->birthDate!="0000-00-00") {
						echo date("M d, Y",strtotime($row->birthDate)); 
					} else {
						echo " -- "; 
					}
			    ?>
            </td>
            <td class="top left">
                <?php 
                  if($row->birthDate!="0000-00-00") {
						echo (date('Y') - date('Y',strtotime($row->birthDate))); 
					} else {
						echo " -- "; 
					}
			    ?>
            </td>
            <td class="top left">
                <?php if($row->sex == 'M'){echo "Male";}else{echo "Female";} ?>
            </td>
            <td class="top left">
                <?php echo $row->civilStatus ?>
            </td>
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