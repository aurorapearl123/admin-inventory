<style>
.checkBox { width:18px;height:18px;vertical-align:middle; }
.bright { border-right: 0.5px solid <?php echo $this->config_model->getConfig('Report Table Border Color')?>;}
.bleft { border-left: 0.5px solid <?php echo $this->config_model->getConfig('Report Table Border Color')?>;}
.btop { border-top: 0.5px solid <?php echo $this->config_model->getConfig('Report Table Border Color')?>;}
.bbottom { border-bottom: 0.5px solid <?php echo $this->config_model->getConfig('Report Table Border Color')?>;}
.ball { border: 1px solid <?php echo $this->config_model->getConfig('Report Table Border Color')?>;}
.label { height: 2px; font-size:8pt;  }
.field { height: 2px; font-size:8pt; }
.table_header { background-color: <?php echo $this->config_model->getConfig('Report Table BG Color')?>; font-size:10pt; }
.table_row { height: 20px; font-size:8pt; vertical-align:middle; }
.signatory { background-color: #eee; height: 20px; font-size:7pt; }
.signatory_table { position: absolute; bottom: 0;}
</style>
<br>
<br>
<br>






<table style="width: 100%;" border="0" class="table_header">
    <tr>
        <td valign="" style="" align="center" colspan="4">EXPIRY <?php echo date('M Y', strtotime($expiry)) ?></td>
    </tr>
    <tr>
        <td valign="" style="" align="left" width="20%">Entity Name: </td>
        <td valign="" style="" align="left" width="45%" class="bbottom"><b><?php echo $rec->officeName . ' / '.$rec->division ?></b></td>
        <td valign="" style="" align="right" width="20%">Fund Cluster: </td>
        <td valign="" style="" align="left" width="15%" class="bbottom"><b><?php echo $rec->clusterFund ?></b></td>
    </tr>
    <tr>
        <td valign="" style="" align="left" width="">Item: </td>
        <td valign="" style="" align="left" width="" class="bbottom"><b><?php echo $rec->itemName ?></b></td>
        <td valign="" style="" align="right" width="">Stock No.: </td>
        <td valign="" style="" align="left" width="" class="bbottom"><b><?php echo $rec->itemCode ?></b></td>
    </tr>
    <tr>
        <td valign="" style="" align="left" width="">Description: </td>
        <td valign="" style="" align="left" width="" class="bbottom"><b><?php echo $rec->itemDescription ?></b></td>
        <td valign="" style="" align="right" width="">Re-order Level: </td>
        <td valign="" style="" align="left" width="" class="bbottom"><b><?php echo $rec->reorderLvl ?></b></td>
    </tr>
    <tr>
        <td valign="" style="" align="left" width="" nowrap>Unit of Measurement: </td>
        <td valign="" style="" align="left" width="" class="bbottom"><b><?php echo $rec->itemUmsr ?></b></td>
        <td valign="" style="" align="right" width=""></td>
        <td valign="" style="" align="left" width="" class=""></td>
    </tr>

</table>




<table border="0" cellpadding="0" cellspacing="0" style="width:100%;margin-top:5px;" class="table_header">

	<thead align="center">
		<tr>
			<th rowspan="2" class="bleft bright btop bbottom" width="10%">
				Date
			</th>
			<th rowspan="2" width="20%" class="bright btop bbottom">Reference</th>
			<th width="10%" class="bright btop bbottom">Receipt</th>
			<th colspan="2" class="bright btop bbottom">Issue</th>
			<th width="10%" class="bright btop bbottom">Balance</th>
			<th rowspan="2" width="20%" class="bright btop bbottom">No. of Days to Consume</th>
		</tr>
		<tr>
			<th class="bright bbottom">Qty.</th>
			<th width="10%" class="bright bbottom">Qty.</th>
			<th width="30%" class="bright bbottom">Office</th>
			<th class="bright bbottom">Qty.</th>
		</tr>

	</thead>

<?php
    $default_rows = 28;

    if (!empty($records)) {
        foreach ($records as $row) { ?>
            
        	<tr class="">
        		<td colspan="" nowrap align="center" width="" class="table_row bbottom bleft bright <?php echo (end($records))? 'btop':''; ?>"><?php echo date('Y-m-d', strtotime($row->dateInserted)); ?></td>  
        		<td colspan="" nowrap align="center" width="" class="table_row bbottom bright <?php echo (end($records))? 'btop':''; ?>"><?php echo $row->refNo ?></td>
        		<td colspan="" nowrap align="center" width="" class="table_row bbottom bright <?php echo (end($records))? 'btop':''; ?>"><?php echo $row->increase ?></td>
        		<td colspan="" nowrap align="center" width="" class="table_row bbottom bright <?php echo (end($records))? 'btop':''; ?>"><?php echo $row->decrease ?></td>    
        		<td colspan="" nowrap align="center" width="" class="table_row bbottom bright <?php echo (end($records))? 'btop':''; ?>"><?php echo $row->officeName ?></td>    
        		<td colspan="" nowrap align="center" width="" class="table_row bbottom bright <?php echo (end($records))? 'btop':''; ?>"><?php echo $row->endBal ?></td>    
        		<td colspan="" nowrap align="center" width="" class="table_row bbottom bright <?php echo (end($records))? 'btop':''; ?>"></td>
        	</tr>

        <!-- foreach end -->
       <?php } 
    }

    for ($i = 0; $i < $default_rows; $i++) { ?>

        <tr class="">
            <td colspan="" nowrap align="center" width="" class="table_row bbottom bleft bright"></td>  
            <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>
            <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>
            <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>    
            <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>    
            <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>    
            <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>
        </tr>
    <?php
    }
?>




</table>

<br>
<table border="0" cellpadding="2" cellspacing="0" width="100%" style="font-size: 8px;">
<tr>
    <td ><font size="8"><?php echo $this->config_model->getConfig('Footer Left - Version')?></font></td>
    <td align="right"><font size="8"><?php echo $this->config_model->getConfig('Footer Right - Version')?></font></td>
</tr>
</table>