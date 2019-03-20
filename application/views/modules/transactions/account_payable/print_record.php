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
.table_row { height: 20px; font-size:8pt; vertical-align:middle; font-size:8pt !important;}
.signatory { background-color: #fff; font-size:10pt; }
.signatory_table { position: absolute; bottom: 0;}
</style>
        <div class="btop bright bbottom bleft">            
            <table width="100%">
                <thead>
                <tr>
                    <td width="20%"></td>
                    <td class="" align="center" valign="top" height="40px"><h2> PURCHASE ORDER</h2></td>
                    <td width="20%"></td>
                </tr>

               </thead>
            </table>
            <table style="width: 100%;" class="signatory" cellspacing="0" cellpadding="1">
                <thead class="table_header">
                    <tr>
                        <td colspan="3" class="btop ">&nbsp;</td>
                        <td colspan="3" class="btop">&nbsp;</td>
                    </tr>
                    <tr class="table_header">   
                        <td width="15%" >&nbsp;Supplier:</td>  
                        <td width="25%" class="bbottom "><strong><?php echo strtoupper($apheaders->suppName) ?></strong></td>
                        <td width="10%" class=" "></td>
                        <td width="10%" class=""></td>  
                        <td width="15%" class="">P.O. No:</td>  
                        <td width="25%" class="bbottom "><strong><?php echo $apheaders->invoiceNo ?></strong>&nbsp;</td>
                    </tr>  
                    <tr class="table_header">
                        <td>&nbsp;Address:</td>  
                        <td class="bbottom" style="text-decoration: underline;"><strong ><?php echo $apheaders->streetNo. ' '.$apheaders->barangay. ' '.$apheaders->city. ' '.$apheaders->province ?></strong></span></td>
                        <td class=""></td>
                        <td></td>
                        <td>Date:</td>  
                        <td class="bbottom"><strong><?php echo date('M d, Y',strtotime($apheaders->invoiceDate)) ?></strong>&nbsp;</td>
                    </tr>   
                    <tr class="table_header">   
                        <td></td>  
                        <td class="bbottom"></td>
                        <td class=""></td>
                        <td></td>
                        <td>Mode of Procurement:</td>  
                        <td class="bbottom"><strong><?php echo $apheaders->modeProcurement ?></strong>&nbsp;</td>
                    </tr>  
                    <tr class="table_header">
                        <td colspan="3" class=" ">&nbsp;</td>
                        <td colspan="3" class="">&nbsp;</td>
                    </tr>
                     
 
                    <tr>   
                        <td>&nbsp;Place of Delivery:</td>  
                        <td class="bbottom"><strong>Kitrol Agusan</strong></td>
                        <td class=""></td>
                        <td></td>
                        <td class="">Delivery Term:</td>  
                        <td class="bbottom"><strong><?php echo $apheaders->deliveryTerm ?></strong>&nbsp;</td>
                    </tr>                      
                    <tr>   
                        <td>&nbsp;Date of Delivery: </td>  
                        <td class="bbottom"><strong><?php echo date('M d, Y', strtotime($apheaders->deliveryDate)) ?></strong></td>
                        <td class=""></td>
                        <td></td>
                        <td class="">Payment Term:</td>  
                        <td class="bbottom"><strong>
                            <?php 
                            if ($rec->paymentTerm == 1) {
                                echo "Credit Card";
                            } else if ($rec->paymentTerm == 2){
                                echo "EOM - End of month";
                            } else if ($rec->paymentTerm == 3){
                                echo "COD - Cash on delivery";
                            } else if ($rec->paymentTerm == 4){
                                echo "Bank Deposit";
                            }
                            ?>
                        </strong>&nbsp;</td>
                    </tr> 
                    <tr class="table_header">
                        <td colspan="6" valign="top" style="height:30px">&nbsp;Gentlemen:</td>
                    </tr>
                    <tr>
                        <td colspan="6" valign="top" class="" style="padding-left:20px;height:30px"><strong>Remarks&nbsp;</strong></td>
                    </tr>  
                </thead>
            </table>
            <table style="width: 100%;" class="signatory" cellspacing="0" cellpadding="1">
                <thead class="table_header">
                                        
               
                    <tr class="table_row">                        
                        <th height="30px" align="center" width="15%" class=" bright btop bbottom">&nbsp;<strong>Category</strong>&nbsp;</th>
                        <th align="center" width="10%" class="bbottom bright btop ">&nbsp;<strong>Unit </strong>&nbsp;</th>
                        <th align="center" width="35%" class="bbottom bright btop ">&nbsp;<strong>Description</strong>&nbsp;</th>
                        <th align="center" width="10%" colspan="" class="bbottom bright btop ">&nbsp;<strong>Quantity </strong>&nbsp;</th>
                        <th align="center" width="10%" colspan="" class="bbottom bright btop ">&nbsp;<strong>Unit Cost </strong>&nbsp;</th>
                        <th align="center" width="10%" class="bbottom  btop ">&nbsp;<strong>Amount</strong>&nbsp;</th>
                    </tr>
                </thead>
                <?php
                    $default_rows = 28;
                    $totalAmount = 0;
                    $totalQty = 0;
                    
                    $ctr = 0;
                    if (!empty($apdetails)) {
                        foreach ($apdetails as $row) { $ctr++; 
                         $totalAmount += $row->amount;
                          
                        ?>
                            <tr class="">
                                <td colspan=""  nowrap align="center" width="" class="table_row bbottom  bright">&nbsp;<?php echo $row->category; ?>&nbsp;</td>  
                                <td colspan=""  nowrap align="center" width="" class="table_row bbottom bright">&nbsp;<?php echo $row->umsr; ?>&nbsp;</td>
                                <td nowrap align="left"   width="" class="table_row bbottom bright">&nbsp;<?php echo $row->name.' '.$row->description.' '; ?>&nbsp;</td>
                                <td colspan=""  nowrap align="center" width="" class="table_row bbottom bright">&nbsp;<?php echo number_format($row->qty); ?>&nbsp;</td>    
                                <td colspan=""  nowrap align="right" width="" class="table_row bbottom bright">&nbsp;<?php echo number_format($row->price,2); ?>&nbsp;</td>    
                                <td colspan=""  nowrap align="right" width="" class="table_row bbottom ">&nbsp;<?php echo number_format($row->amount,2); ?>&nbsp;</td>
                            </tr>
 
                  <?php }  } ?>

                    <tr class="">
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom  bright"></td>  
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>
                        <td nowrap align="center" width="" class="table_row bbottom bright"><strong>-------------------------------------------------</strong></td>
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>    
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>    
                        <td colspan="" nowrap align="right" width="" class="table_row bbottom " ><strong class="bbottom"><?php echo '&#8369; '. number_format($totalAmount, 2); ?></strong>&nbsp;</td>    
                    </tr>
                    
                    <tr class="">
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom  bright"></td>  
                        <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>
                        <td nowrap align="center" width="" class="table_row bbottom bright"><strong>----NOTHING FOLLOWS----</strong></td>
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
                            <td nowrap align="center" width="" class="table_row bbottom bright"></td>
                            <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>    
                            <td colspan="" nowrap align="center" width="" class="table_row bbottom bright"></td>    
                            <td colspan="" nowrap align="center" width="" class="table_row bbottom "></td>    
                        </tr>
                  <?php  } ?>   
                <tr class="">
                    <td colspan="5" nowrap align="left" width="" class="table_row bbottom ">
                        <span class="">&nbsp;(Total Amount in Words) <?php echo $numbertowords = $this->numbertowords->convert_number($totalAmount) .' Pesos'; ?></span>
                    </td>   
                    <td align="right" width="" class="table_row bbottom ">
                        <span class="">&nbsp;<strong class="bbottom"><?php echo '&#8369; '. number_format($totalAmount, 2); ?></strong>&nbsp;</span>
                    </td>  
                </tr>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" style="padding: 10px;">
                                                            <tr>
                                                                <td colspan="7" style="height: 20px !important;">
                                                                    <b>NOTE:</b>
                                                                    <pre><p style="font-size: 8pt; font-family: Gotham Narrow;">
<i>By signing, We acknowledge that we are liable for whatever shortages that were incured during our shift.  We also agree to immediately provide cash payment for the said shortages. Should we lack the capacity to immediately pay in cash, we agree to have the appropriate amount deducted from our salary.


                                                            </i></p></pre>
                                                                    
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 5.55%;"></td>
                                                                <td style="width: 16.66%;">Prepared By:</td>
                                                                <td style="width: 16.66%;"></td>
                                                                <td style="width: 16.66%;"></td>
                                                                <td colspan="2" style="width: 38.885%;">Witness:</td>

                                                                <td style="width: 5.55%;"></td>

                                                            </tr>
                                                            <tr>
                                                                <td colspan="7" height="30px"></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td colspan="2" style="border-bottom: 1px solid #000;" align="center"></td>
                                                                <td ></td>
                                                                <td colspan="2" style="border-bottom: 1px solid #000;" align="center"></td>
                                                                <td ></td>


                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td colspan="2" align="center" style="font-size: 8pt;">Cashier Signature over Printed Name</td>
                                                                <td></td>
                                                                <td colspan="2" align="center" style="font-size: 8pt;">OIC Signature over Printed Name</td>
                                                                <td></td>

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
