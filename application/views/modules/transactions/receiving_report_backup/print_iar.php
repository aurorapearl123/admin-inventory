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
        <td style="width: 70%" valign="">
            <table style="width: 100%"  border="0">
                <tr>
                    <td valign="" style="" align="left" width="80px"> </td>
                    <td valign="" align="center" class="" width="80px"></td>
                </tr>
            </table>
        </td>
        <td style="width: 30%" valign="">
            <table style="width: 100%"  border="0" class="table_header">
                <tr>
                    <td valign="" style="" align="right" width="10%">Fund Cluster: </td>
                    <td valign="" align="center" class="bbottom" width="20%"><slot><b><?php echo $rec->clusterFund ?></b></slot></td>
                </tr>
            </table>
        </td>
    </tr>
</table>







<table border="0" cellpadding="0" cellspacing="0" style="width:100%;margin-top:5px;" class="table_header">

<tr class="table_header">
    
    <td colspan="3" rowspan="5" nowrap align="" class="table_row bleft btop bbottom bright">
        <table style="width: 100%;" border="0">
            <tr>
                <td colspan="2" valign="" style="" align="left" width="150px">
                    <table>
                        <tr>
                            <td valign="" style="" align="left" width="50px">Supplier: </td>
                            <td valign="" align="" class="bbottom" width="200px"><slot><b><?php echo $rec->suppName ?></b></slot></td>
                            <td valign="" align="right" class="" width="50px">Date:</td>
                            <td valign="" align="right" class="bbottom" width="50px"><strong><?php echo date('M d, Y', strtotime($rec->poDate))?></strong></td>
                        </tr>
                        <tr>
                            <td valign="" style="" align="left" width="">PO No: </td>
                            <td valign="" align="" class="bbottom" width=""><slot><b><?php echo $rec->poNo ?></b></slot></td>
                            <td valign="" align="" class="" width=""></td>
                            <td valign="" align="" class="" width=""></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign="" style="" align="left" width="100">&nbsp;Requisition Office / Dept: </td>
                <td valign="" align="" class="bbottom" width="200px"><slot><b><?php echo $rec->requisitioningOffice ?></b></slot></td>
            </tr>
            <tr>
                <td valign="" style="" align="left" width="80px">&nbsp;Responsibility Center Code: </td>
                <td valign="" align="" class="bbottom" width="200px"><slot><b><?php echo $rec->responsibilityCenterCode ?></b></slot></td>
            </tr>
        </table>
    </td>   
    <td colspan="" rowspan="" nowrap align="" width="50px" class="table_row btop ">
        <span class="label">&nbsp;RR No.</span></td>   
    <td colspan="" rowspan="" nowrap align="center" width="50px" class="table_row btop  bright">
        <span class="label"><?php echo $rec->iarNo ?></span></td>
</tr>
<tr class="table_header">   
    <td colspan="" rowspan="" nowrap align="" width="50px" class="table_row  ">
        <span class="label">&nbsp;Date</span></td>  
    <td colspan="" rowspan="" nowrap align="center" width="50px" class="table_row  bright">
        <span class="label"><?php echo date('M d, Y', strtotime($rec->iarDate)) ?></span></td>
</tr>

<tr class="table_header">   
    <td colspan="" rowspan="" nowrap align="" width="" class="table_row  ">
        <span class="label">&nbsp;INVOICE No.</span></td>   
    <td colspan="" rowspan="" nowrap align="center" width="" class="table_row  bright">
        <span class="label"><?php echo ($rec->invoiceNo)? $rec->invoiceNo : "NONE"; ?></span></td>
</tr>
<tr class="table_header">   
    <td colspan="" rowspan="" nowrap align="" width="" class="table_row  ">
        <span class="label">&nbsp;DR No.</span></td>    
    <td colspan="" rowspan="" nowrap align="center" width="" class="table_row  bright">
        <span class="label"><?php echo ($rec->drNo)? $rec->drNo : "NONE"; ?></span></td>
</tr>
<tr class="table_header">   
    <td colspan="" rowspan="" nowrap align="" width="" class="table_row bbottom ">
        <span class="label">&nbsp;Date</span></td>  
    <td colspan="" rowspan="" nowrap align="center" width="" class="table_row bbottom bright">
        <span class="label"><?php echo date('M d, Y', strtotime($rec->drDate)) ?></span></td>
</tr>
<tr class="table_row">
    
    <td align="center" width="30px" class="bleft bright bbottom"><strong>Stock No.</strong></td>
    <td align="center" colspan="" width="200px" class="bbottom"><strong>Description </strong></td>
    <td align="center" width="0px" colspan="" class="bbottom bright"></td>
    <td align="center" width="50px" colspan="" class="bbottom bright"><strong>Unit </strong></td>
    <td align="center" width="50px" colspan="" class="bbottom bright"><strong>Quantity </strong></td>

</tr>


<?php
    $default_rows = 28;
    $totalAmount = 0;
    $totalQty = 0;
    if (!empty($records)) {
        foreach ($records as $row) { ?>
            <tr class="">
                <td colspan="" nowrap align="center" width="" class="table_row bbottom bleft bright <?php echo (end($records))? 'btop':''; ?>"><?php echo $row['itemCode']; ?></td>  
                <td colspan="2" align="left" width="" class="table_row bbottom bright <?php echo (end($records))? 'btop':''; ?>">&nbsp;<?php echo $row['itemName'].' '.$row['itemDescription'].' '; ?><?php echo ($row['itemExpiry'])? '(Expiry: '.date('Y-m-d').')': ''; ?></td>
                <td colspan="" nowrap align="center" width="" class="table_row bbottom bright <?php echo (end($records))? 'btop':''; ?>"><?php echo $row['itemUmsr']; ?></td>
                <td colspan="" nowrap align="center" width="" class="table_row bbottom bright <?php echo (end($records))? 'btop':''; ?>"><?php echo number_format($row['itemQty'], 2); ?>&nbsp;</td>    
            </tr>
            <?php
            $default_rows -= 1;
            if ($row['itemAmount'] > 0) {
                $totalAmount += $row['itemAmount'];
                $totalQty += $row['itemQty'];
            }
            ?>

        <!-- foreach end -->
       <?php } 
    }

    for ($i = 0; $i < $default_rows; $i++) { ?>

        <tr class="">
            <td colspan="" nowrap align="center" width="" class="table_row bbottom bleft bright"></td>  
            <td colspan="2" align="center" width="" class="table_row bbottom bright"></td>
            <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>
            <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>    
        </tr>
    <?php
    }
?>

       
<tr class="">
    <td colspan="4" nowrap align="right" width="" class="table_row bbottom bleft bright">
        &nbsp;<b>TOTAL QTY:</b>&nbsp;</td>                            
    <td colspan="" nowrap align="center" width="" class="table_row bbottom bright">
        &nbsp;<b><?php echo number_format($totalQty, 2); ?></b>&nbsp;</td>       
    
</tr>



<!-- Table Footer Start -->
<tr>
    <td colspan="5" class="bleft bbottom bright">
        <table>
            <tr>
                <td align="center" class="bright bbottom"><em><b>INSPECTION</b></em></td>
                <td align="center" class="bbottom"><em><b>ACCEPTANCE</b></em></td>
            </tr>
            <tr>
                <!-- First Col -->
                <td width="50%" class="bright" valign="top">
                    <table class="table_header">
                        <tr>
                            <td width="80px">Date Inspected:</td>
                            <td width="80px" align="center" class="bbottom"></td>
                            
                        </tr>
                    </table>
                    <table class="table_header" border="0" cellpadding="0" cellspacing="0" style="">
                        
                        <tr>
                            <td width="80px"></td>
                            <td colspan="2" height="30px"></td>
                            <td width="80px"></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="" align="left">
                                
                                <img src="http://localhost/project/lhprime/assets/img/main/ballot_box.png" style="width: 10px;height: 10px;";/>
                                
                                <span>Inspected verifiied and found in order as to quantity and specifications.</span>
                            </td>

                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2" height="30px" class="bbottom" align="center">
                                
                            </td>
                            <td></td>
                            

                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2" align="center">Inspection Officer Inspection Committee</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2" height="30px" class="bbottom" align="center" width="80px"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" align="center">End User</td>
                        </tr>
                    </table>
                </td>

                <!-- 2nd Col -->
                <td width="50%" valign="top">
                    <table class="table_header">
                        <tr>
                            <td width="80px">Date Received:</td>
                            <td width="80px" align="center" class="bbottom"></td>
                            
                        </tr>
                    </table>
                    <table class="table_header" border="0" cellpadding="0" cellspacing="0" style="">
                        
                        <tr>
                            <td width="80px"></td>
                            <td colspan="2" height="30px"></td>
                            <td width="80px"></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="" align="left">
                                
                                <img src="http://localhost/project/lhprime/assets/img/main/ballot_box.png" style="width: 10px;height: 10px;";/>
                                
                                <span>Complete.</span>
                            </td>

                        </tr>
                        <tr>
                            <td colspan="4" style="" align="left" height="30px" valign="">
                                
                                <img src="http://localhost/project/lhprime/assets/img/main/ballot_box.png" style="width: 10px;height: 10px;";/>
                                
                                <span>Partial (pls specify quantity).</span>
                            </td>

                        </tr>
                        <tr>
                            <td colspan="4" align="center"></td>
                        </tr>
                        <tr>
                            <td width=""></td>
                            <td colspan="2" height="30px" class="bbottom" align="center" width="80px"></td>
                            <td width=""></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2" align="center">Supply and/or Property Custodian</td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
<!-- Table Footer End -->
</table>



<!-- <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 5pt;margin-top: 5px;">
    <tr>
        <td valign="top"><slot><em>WD = Weekday</em></slot></td>
        <td valign="top"><slot><em>HDA = Half Day AM</em></slot></td>
        <td valign="top"><slot><em>HDP = Half Day PM</em></slot></td>
        <td valign="top"><slot><em>WE = Weekend</em></slot></td>
        <td valign="top"><slot><em>RH = Regular Holiday</em></slot></td>
        <td valign="top"><slot><em>SH = Special Holiday</em></slot></td>
        <td valign="top"><slot><em>NW = No Work</em></slot></td>
    </tr>
</table> -->

<br>
<table border="0" cellpadding="2" cellspacing="0" width="100%" style="font-size: 8px;">
<tr>
    <td ><font size="8"><?php echo $this->config_model->getConfig('Footer Left - Version')?></font></td>
    <td align="right"><font size="8"><?php echo $this->config_model->getConfig('Footer Right - Version')?></font></td>
</tr>
</table>