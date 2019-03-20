<style>
table{
    border-collapse: collapse;
    font-size:8pt;
    }/*.position { position:fixed;top:0; }*/
.checkBox { width:18px;height:18px;vertical-align:middle; }
.bright { border-right: 0.5px solid #ccc; }
.bleft { border-left: 0.5px solid #ccc; }
.btop { border-top: 0.5px solid #ccc; }
.bbottom { border-bottom: 0.5px solid #ccc; }
.ball { border: 0.5px solid #ccc; }
.label { height: 2px; font-size:10pt;  }
.field { height: 2px; font-size:10pt; }
.table_header { font-size:10pt; }
.table_row { height: 20px; font-size:8pt; vertical-align:middle; }
.signatory { background-color: #eee; height: 20px; font-size:7pt; }
.signatory_table { position: absolute; bottom: 0;}
.tr { height:30px;}
</style>
        <div class="btop bright bbottom bleft">            
            <table width="100%">
            	<thead>
            	<tr>
                    <td width="20%"></td>
                    <td class="" align="center" valign="top" height="40px"><h3> PURCHASE ORDER</h3></td>
                    <td width="20%"></td>
                </tr>
            	<tr>
                    <td></td>
                    <td class="bbottom" align="center"><h3> Dr. Jose Rizal Memorial Hospital</h3></td>
                    <td></td>
                </tr>
                <tr class="table_header">
                    <td></td>
                    <td align="center" valign="top" height="40px">Agency</td>
                    <td></td>
                </tr>
               </thead>
			</table>
            <table width="100%">
                <thead>
                    <tr class="table_header">   
                        <td width="10%" class="btop">Supplier:</td>  
                        <td width="30%" class="bbottom btop"><strong><?php echo strtoupper($soheaders->companyName) ?></strong></td>
                        <td width="10%" class="btop bright"></td>
                        <td width="10%" class="btop"></td>  
                        <td width="15%" class="btop">P.O. No:</td>  
                        <td width="25%" class="bbottom btop"><strong><?php echo $soheaders->soNo ?></strong></td>
                    </tr>  
                    <tr class="table_header">
                        <td>Address:</td>  
                        <td class="bbottom" style="text-decoration: underline;"><strong ><?php echo $soheaders->streetNo. ' '.$soheaders->barangay. ' '.$soheaders->city. ' '.$soheaders->province ?></strong></span></td>
                        <td class="bright"></td>
                        <td></td>
                        <td>Date:</td>  
                        <td class="bbottom"><strong><?php echo date('M d, Y',strtotime($soheaders->poDate)) ?></strong></td>
                    </tr>   
                    <tr class="table_header">   
                        <td></td>  
                        <td class="bbottom"></td>
                        <td class="bright"></td>
                        <td></td>
                        <td>Mode of Procurement:</td>  
                        <td class="bbottom"><strong><?php echo $soheaders->modeProcurement ?></strong></td>
                    </tr>  
                    <tr class="table_header">
                        <td colspan="3" class="bbottom bright">&nbsp;</td>
                        <td colspan="3" class="bbottom">&nbsp;</td>
                    </tr>
                    <tr class="table_header">
                        <td colspan="6" valign="top" style="height:30px">Gentlemen:</td>
                    </tr>
                    <tr>
                        <td colspan="6" valign="top" class="bbottom" style="padding-left:20px;height:30px"><strong><?php echo $this->config_model->getConfig('Purchase Order Note') ?></strong></td>
                    </tr>   
				</table>
				<table width="100%">  
					<thead>  
                    <tr>   
                        <td width="20%">Place of Delivery:</td>  
                        <td width="30%" class="bbottom"><strong><?php echo $soheaders->deliveryPlace ?></strong></td>
                        <td width="10%"></td>
                        <td width="15%" class="bleft">Delivery Term:</td>  
                        <td width="25%" class="bbottom"><strong><?php echo $soheaders->deliveryTerm ?></strong></td>
                    </tr>                      
                    <tr>   
                        <td>Date of Delivery: </td>  
                        <td class="bbottom"><strong><?php echo date('M d, Y', strtotime($soheaders->deliveryDate)) ?></strong></td>
                        <td></td>
                        <td class="bleft">Payment Term:</td>  
                        <td class="bbottom"><strong><?php echo $soheaders->paymentTerm ?></strong></td>
                    </tr> 
                    <tr>   
                        <td class="bbottomm">&nbsp;</td>  
                        <td class="bbottomm">&nbsp;</td>
                        <td class="bbottomm">&nbsp;</td>
                        <td class="bbottomm bleft">&nbsp;</td>  
                        <td class="bbottomm">&nbsp;</td>
                    </tr>                      
                </thead>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" style="width:100%;" class="table_header"> 
                <thead>
                    <tr class="table_row">                        
                        <td align="center" width="10%" class=" bright btop bbottom">&nbsp;<strong>Stock No.</strong>&nbsp;</td>
                        <td align="center" width="10%" class="bbottom bright btop ">&nbsp;<strong>Unit </strong>&nbsp;</td>
                        <td align="center" width="30%" colspan="2" class="bbottom bright btop ">&nbsp;<strong>Description</strong>&nbsp;</td>
                        <td align="center" width="10%" colspan="" class="bbottom bright btop ">&nbsp;<strong>Quantity </strong>&nbsp;</td>
                        <td align="center" width="15%" colspan="" class="bbottom bright btop ">&nbsp;<strong>Unit Cost </strong>&nbsp;</td>
                        <td align="center" colspan="" class="bbottom  btop ">&nbsp;<strong>Amount</strong>&nbsp;</td>
                    </tr>
                </thead>
                <?php
                    $default_rows = 28;
                    $totalAmount = 0;
                    $totalQty = 0;
                    
                    $ctr = 0;
                    if (!empty($sodetails)) {
                        foreach ($sodetails as $row) { $ctr++; 
                         $totalAmount += $row->amount;
                          
                        ?>
                            <tr class="">
                                <td colspan=""  nowrap align="center" width="" class="table_row bbottom  bright">&nbsp;<?php echo $row->itemCode; ?>&nbsp;</td>  
                                <td colspan=""  nowrap align="center" width="" class="table_row bbottom bright">&nbsp;<?php echo $row->umsr; ?>&nbsp;</td>
                                <td colspan="2" nowrap align="left"   width="" class="table_row bbottom bright">&nbsp;<?php echo $row->name.' '.$row->description.' '; ?>&nbsp;</td>
                                <td colspan=""  nowrap align="center" width="" class="table_row bbottom bright">&nbsp;<?php echo number_format($row->qty); ?>&nbsp;</td>    
                                <td colspan=""  nowrap align="right" width="" class="table_row bbottom bright">&nbsp;<?php echo number_format($row->uprice,2); ?>&nbsp;</td>    
                                <td colspan=""  nowrap align="right" width="" class="table_row bbottom ">&nbsp;<?php echo number_format($row->amount,2); ?>&nbsp;</td>
                            </tr>
 
                  <?php }  } ?>

                    <tr class="">
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom  bright"></td>  
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>
                        <td colspan="2" nowrap align="left" width="" class="table_row bbottom bright"></td>
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>    
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>    
                        <td colspan="" nowrap align="right" width="" class="table_row bbottom " ><strong class="bbottom"><?php echo '&#8369; '. number_format($totalAmount, 2); ?></strong></td>    
                    </tr>
                    <tr class="">
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom  bright"></td>  
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>
                        <td colspan="2" nowrap align="left" width="" class="table_row bbottom bright"><strong><?php echo $soheaders->remarksdetails?></strong></td>
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>    
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>    
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom "></td>    
                    </tr>
                    <tr class="">
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom  bright"></td>  
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>
                        <td colspan="2" nowrap align="center" width="" class="table_row bbottom bright"><strong>----NOTHING FOLLOWS----</strong></td>
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>    
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>    
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom "></td>    
                    </tr>
                    <?php
                        $default_rows  = 23 - $ctr;
                        for ($i = 1; $i <= $default_rows; $i++) { ?>

                        <tr class="">
                            <td colspan="" nowrap align="center" width="" class="table_row bbottom  bright"></td>  
                            <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>
                            <td colspan="2" nowrap align="center" width="" class="table_row bbottom bright"></td>
                            <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>    
                            <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>    
                            <td colspan="" nowrap align="center" width="" class="table_row bbottom "></td>    
                        </tr>
                  <?php  } ?>   
                <tr class="">
                    <td colspan="4" nowrap align="left" width="" class="table_row bbottom ">
                        <span class="">&nbsp;(Total Amount in Words) <?php echo $numbertowords = $this->numbertowords->convert_number($totalAmount) .' Pesos'; ?></span>
                    </td>   
                    <td colspan="3" nowrap align="right" width="" class="table_row bbottom ">
                        <span class="">&nbsp;<strong class="bbottom"><?php echo '&#8369; '. number_format($totalAmount, 2); ?></strong>&nbsp;</span>
                    </td>  
                </tr>
			</table>
			<table border="0">
                <tr class="">
                    <td colspan="5" class=" table_row " style="">
                        <span class="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->config_model->getConfig('Purchase Order Guidelines'); ?>&nbsp;</span>
                    </td>
                    <td colspan="2" class="table_row">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                </tr> 
                <tr class="">
                    <td colspan="7" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>                   
                </tr>   
                <tr class="">
                    <td colspan="4" class="table_row">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td valign="top" class="table_row " align="left" style="height:30px" colspan="3">
                        <span class="">&nbsp; Very Truly Yours, &nbsp;</span>
                    </td>
                </tr>   
                <tr class="">                    
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left" style="white-space:nowrap">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left" style="white-space:nowrap">
                        <span class="">&nbsp;  
                                        <?php    
                                            $this->db->where('code','1003');
                                            $row = $this->db->get('signatories')->row();
                                            echo $row->fullname; 
                                        ?> 
                            &nbsp;
                        </span>
                    </td>
                    <td colspan="" class=" table_row " align="left" >
                        <span class="">&nbsp;  &nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                </tr>   

                <tr class="">                    
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left" style="white-space:nowrap">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="center" style="white-space:nowrap">
                        <span class="">&nbsp; 
                                        <?php    
                                            $this->db->where('code','1003');
                                            $row = $this->db->get('signatories')->row();
                                            echo $row->designation1; 
                                        ?> 
                        &nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left" >
                        <span class="">&nbsp;  &nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                </tr>                   
                <tr>                    
                    <td  style="height:30px" valign="top" colspan="3">Conforme: </td>
                    <td colspan="" class=" table_row " align="left" style="white-space:nowrap">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left" style="white-space:nowrap">
                        <span class="">&nbsp;  &nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left" >
                        <span class="">&nbsp;  &nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                </tr>   
                <tr class="">                    
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row btop" align="center" style="white-space:nowrap">
                        <span class="">&nbsp;Signature over Printed Name of Supplier&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left" >
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left" >
                        <span class="">&nbsp;  &nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left" >
                        <span class="">&nbsp;  &nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                </tr>                   
                <tr class="">                    
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left" >
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left" >
                        <span class="">&nbsp;  &nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left" >
                        <span class="">&nbsp;  &nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                </tr>   

                <tr class="">                    
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row btop " align="center">
                        <span class="">&nbsp;Date&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left" >
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left" >
                        <span class="">&nbsp;  &nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left" >
                        <span class="">&nbsp;  &nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row " align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                </tr>   
                <tr class="">                    
                    <td colspan="" class="table_row bbottom" align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row bbottom" align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row bbottom" align="center">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row bbottom" align="left" >
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row bbottom" align="left" >
                        <span class="">&nbsp;  &nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row bbottom" align="left" >
                        <span class="">&nbsp;  &nbsp;</span>
                    </td>
                    <td colspan="" class=" table_row bbottom" align="left">
                        <span class="">&nbsp;&nbsp;</span>
                    </td>
                </tr>
			</table>
			<table width="1000px">                
                <tr>                    
                    <td width="15%" class="table_row">Funds Available: </td>
                    <td width="25%" class="bbottom table_row" align="left"></td>
                    <td width="10%" class="table_row bright" align="center">&nbsp;</td>
                    <td width="10%" class="table_row" align="center">&nbsp;</td>
                    <td class="table_row" align="left">ALOBS NO: </td>
                    <td width="25%" class="table_row bbottom" align="left"><?php echo $soheaders->alobsno ?></td>
                    <td class="bright table_row" align="center">&nbsp;</td>
                </tr>   
                <tr>                    
                    <td class="table_row"></td>
                    <td class="table_row" align="center"></td>
                    <td class="bright table_row" align="center">&nbsp;</td>
                    <td class="table_row" align="center">&nbsp;</td>
                    <td class="table_row">Amount:</td>
                    <td class="table_row" align="left"><?php echo '&#8369; '. number_format($totalAmount, 2); ?></td>
                    <td class="bright table_row" align="center"></td>             
                </tr>                  
            </table>
        </div>
        <div>            
            <table width="1000px">                
                <tr>                    
                    <td class="table_row">APRIL 12, 2016_REV.0</td>
                    <td class="table_row" align="center"></td>
                    <td class=" table_row" align="center">&nbsp;</td>
                    <td class="table_row" align="center">&nbsp;</td>
                    <td class="table_row"></td>
                    <td class="table_row" align="right"></td>
                    <td class=" table_row" align="right">DJRMH-HP-PROC-F-011</td>             
                </tr>                  
               
            </table>
        </div>
