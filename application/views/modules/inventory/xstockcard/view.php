<style>
.checkBox { width:18px;height:18px;vertical-align:middle; }
.bright { border-right: 1px solid #ccc;}
.bleft { border-left: 1px solid #ccc;}
.btop { border-top: 1px solid #ccc;}
.bbottom { border-bottom: 1px solid #ccc;}
.ball { border: 1px solid <?php echo $this->config_model->getConfig('Report Table Border Color')?>;}
.label { height: 2px; font-size:8pt;  }
.field { height: 2px; font-size:8pt; }
.table_header { background-color: <?php echo $this->config_model->getConfig('Report Table BG Color')?>; font-size:10pt; }
.table_row { height: 20px; font-size:8pt; vertical-align:middle; }
.signatory { background-color: #eee; height: 20px; font-size:7pt; }
.signatory_table { position: absolute; bottom: 0;}
</style>
<!-- Sub Header End -->
<div class="subheader">
    <div class="d-flex align-items-center">
        <div class="title mr-auto">
            <h3><i class="icon left la <?php echo $current_module['icon'] ?>"></i> <?php echo $current_module['title'] ?></h3>
        </div>
        <div class="subheader-tools">
            <a href="<?php echo $controller_page.'/show' ?>" class="btn btn-primary btn-raised btn-xs pill"><i class="icon ti-angle-left"></i> Back to List</a>
        </div>
    </div>
</div>
<!-- Sub Header Start -->



<!-- Content Start -->
<div class="content">
    <div class="row">
        <div class="col-md-9">
        
        
        
        <!-- Card Box Start -->
            <div class="card-box">
            
            
            <!-- Card Header Start -->
                <div class="card-head">
                    <div class="head-caption">
                        <div class="head-title">
                            <h4 class="head-text">View <?php echo $current_module['module_label'] ?></h4>
                        </div>
                    </div>
                    <div class="card-head-tools">
                        <ul class="tools-list">
                            <?php $id = $this->encrypter->encode($itemID); ?>
                            
                            
                            <!-- <li>
                                <button class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="Print" onclick="deleteExpiry();"><i class="la la-trash-o"></i></button>
                            </li> -->
                            <li>
                                <button class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="Print" onclick="popUp('<?php echo $controller_page ?>/print_stockcard/<?php echo $id?>/<?php echo $ancillaryID ?>/<?php echo $expiry ?>', 900, 500)"><i class="la la-file-pdf-o"></i></button>
                            </li>
                            
                            
                            <?php if ($this->session->userdata('current_user')->isAdmin) {?>
                            <li>
                                <button type="button" id="recordlog" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Record Logs" onclick="popUp('<?php echo site_url('logs/record_log/'.$table_name.'/'.$pfield.'/'.$id.'/'.ucfirst(str_replace('_', '&', $controller_name))) ?>', 1000, 500)"><i class="la la-server"></i></button>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            <!-- Card Header End -->
                
                
<!--             $this->db->select('items.name as itemName');
            $this->db->select('items.description as itemDescription');
            $this->db->select('items.umsr as itemUmsr');
            $this->db->select('items.aveprice');
            $this->db->select('items.lastcost');
            $this->db->select('items.reorderLvl');
            $this->db->select('xstockcards.expiry');
            $this->db->select('xstockcards.itemID');
            $this->db->select('xstockcards.endBal');
            $this->db->select('category.category');
            $this->db->select('category.description as categoryDescription');
            $this->db->select('brands.brand');
            $this->db->select('brands.description as brandDescription');
            $this->db->select('inventory.qty as inventoryQty'); -->
                
                
                <!-- Card Body Start -->
                <div class="card-body">
                    <div class="data-view">
                        <!-- <div class="subtitle">
                            <h5 class="title"><i class="icon left la la-user"></i> STOCKCARD BY EXPIRY</h5>
                        </div> -->
                        <table class="table view-table">
               
                    <tr class="">
                        <td class="data-title" align="left" width="12%">
                            <strong>Entity Name: </strong>
                        </td>
                        <td class="data-input" align="left" colspan="3" width="65%">
                            <strong><?php echo $officeName . ' / '.$division ?></strong>
                        </td>

                        <td class="data-title" align="left">
                            <strong>Cluster Fund: </strong>

                        </td>
                        <td class="data-input   " align="left">
                            <strong></strong>
                        </td>
                    </tr>
                    <tr class="">
                        <td class="data-title" align="left" width="12%">
                            <strong>Item Name: </strong>
                        </td>
                        <td class="data-input" align="left" colspan="3" width="65%">
                            <strong><?php echo $itemName ?></strong>
                        </td>

                        <td class="data-title" align="left">
                            <strong>Stock No: </strong>

                        </td>
                        <td class="data-input   " align="left">
                            <strong><?php echo $itemCode ?></strong>
                        </td>
                    </tr>

                    <tr class="">
                        <td class="data-title" align="left">
                            <strong>Description: </strong>
                        </td>
                        <td class="data-input   " align="left" colspan="3">
                            <strong><?php echo $itemDescription ?></strong>
                        </td>

                        <td class="data-title" align="left">
                            <strong>Reorder Level: </strong>

                        </td>
                        <td class="data-input   " align="left">
                            <strong><?php echo $reorderLvl ?></strong>
                        </td>
                    </tr>

                    <tr class="">
                        <td class="data-title" align="left">
                            <strong>Unit of Measurement: </strong>
                        </td>
                        <td class="data-input   " align="left" colspan="3">
                            <strong><?php echo $itemUmsr ?></strong>
                        </td>

                        <td class="data-title" align="left">
                            <strong>Expiry: </strong>

                        </td>
                        <td class="data-input   " align="left" width="15%">
                            <strong><?php echo date('M Y', strtotime($expiry)); ?></strong>
                        </td>
                    </tr>
                    <tr class="">
                        <td class="data-title" align="left">
                            <strong>Price: </strong>
                        </td>
                        <td class="data-input   " align="left" colspan="3">
                            <strong><?php echo $itemPrice ?></strong>
                        </td>

                        <td class="data-title" align="left">
                            <strong></strong>

                        </td>
                        <td class="data-input   " align="left" width="15%">
                            <strong></strong>
                        </td>
                    </tr>
                </table>
                
                <div class="form-sepator mb-20"></div>
                <table class="table">
                    <thead class="thead-light" align="center">
                    
                    <tr class="">
                        <th class="  " colspan="" rowspan="2" align="center" style=""><strong>DATE</strong></th>
                        <th class=" " align="center" style=""><strong>BEG. BAL</strong></th>
                        <th class=" " align="center" style=""><strong>RECEIPT</strong></th>
                        <th class=" " align="center" style=""><strong>ISSUE</strong></th>
                        <th class=" " align="center" style=""><strong>ENDING BAL</strong></th>
                        <th class=" " align="center" rowspan="2" style=""><strong>REFERENCE NO.</strong></th>
                    </tr>
                    
                </thead>


                <tbody align="center">

                    

                    <?php

                    $finalBalance = 0;
                    if (!empty($records)) { 
                        foreach ($records as $row) {
                            if (end($records)) {
                                $finalBalance = $row->endBal;
                            }
                        ?>
                        <!-- foreach start -->
                        <tr>
                            <td class="   w-10" align="left"><?php echo date('m/d/Y H:i A', strtotime($row->dateInserted)) ?></td>
                            <td class="  w-10" align="" ><?php echo $row->begBal ?></td>
                            <td class="  w-10" align="" ><?php echo $row->increase ?></td>
                            <td class="  w-20" align="" ><?php echo $row->decrease ?></td>
                            <td class="  w-10" align="" ><?php echo $row->endBal ?></td>
                            <td class="  w-10" align="left" ><?php echo $row->refNo ?></td>
                            
                        </tr>
                        
                        <!-- foreach end -->
                    <?php }
                    } ?>
                      


                </tbody>
            </table>
        </div>
        
    </div>
    <div class="datatable-footer d-flex">

        </div>
</div>
</div>



        <div class="col-md-3">
            <div class="card-box">
                <div class="card-head">
          <div class="head-caption">
            <div class="head-title">
              <h4 class="head-text"><?php echo $itemName ?> stockcards</h4>
            </div>
          </div>
        </div>
            <div class="data-view">
                <table class="table">
                    
                    <tr>
                        <td class="thead-light" colspan="6" align="center">
                            
                        
                           
                            <table class="table table-striped hover" border="0" >
                                <thead>
                                    <tr>
                                        <th nowrap width="12%">Expiry</th>
                                        
                                        
                                        <th nowrap ><div align="right">Price</div></th>
                                        <th nowrap ><div align="right">Qty</div></th>
                                        
                                    </tr>
                                </thead>
                                <tbody align="left">
                                    
                                    <?php 
                                    $totalQty = 0;
                                    foreach ($xstockcards as $row) {
                                    $totalQty += $row->endBal;
                                    ?>
                                    
                                    <tr onclick="location.href='<?php echo site_url('xstockcard/view/').$this->encrypter->encode($row->itemID).'/'.$this->encrypter->encode($row->ancillaryID).'/'.$row->expiry.'/'.$row->price; ?>'" >
                                        <td nowrap width="12%"><?php echo date('M Y', strtotime($row->expiry)); ?></td>
                                        
                                        
                                        <td nowrap align="right"><?php echo number_format($row->price, 2); ?></td>
                                        <td nowrap align="right"><?php echo $row->endBal; ?></td>
                                        
                                    </tr>
                                        
                                    <?php } ?>
                                    <tr><td colspan="3"></td></tr>
                                    <tr>
                                        <td nowrap>Total Quantity</td>
                                        
                                        <td nowrap></td>
                                        <td nowrap align="right"><?php echo $totalQty; ?></td>
                                        
                                        
                                    </tr>

                                    <!-- <tr>
                                        <td nowrap>Average Price:</td>
                                        <td></td>
                                        <td nowrap><?php echo $aveprice ?></td>
                                    </tr>
                                    <tr>
                                        <td nowrap>Average Cost:</td>
                                        <td></td>
                                        <td nowrap><?php echo $avecost ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td nowrap>Last Cost:</td>
                                        <td></td>
                                        <td nowrap><?php echo $lastcost ?></td>
                                    </tr>
                                    <tr>
                                        <td nowrap>
                                        <strong>Balance 
                                        </strong></td>
                                        <td></td>
                                        <td nowrap>
                                        <strong>
                                        aaa
                                        </strong>
                                        </td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </td>
                        <!-- <td colspan="5">
                            
                        </td> -->
                    </tr>
                </table>

            </div>
<!-- <div class="form-sepator mb-10"></div> -->
<div class="card-body" align="center">
    <a id="btn-apply" href="<?php echo site_url().'stockcard/view/'.$this->encrypter->encode($row->itemID).'/'.$this->encrypter->encode($row->ancillaryID); ?>"  class="btn btn-primary btn-sm pill collapse multi-collapse show">View General Stockcard</a>
    </div>
        </div>

    </div>
</div>
</div>
<script>
    $(document).ready(function(){
        var total = 0;
         $("tr.item").each(function() {

                    var price = $(this).find("input.name").val(),
                        amount = $(this).find("input.id").val();
                    total += parseFloat(amount);
            });

            $('#ttlAmount').val(total);


            
    });


    function deleteExpiry()
    {
        swal({
          title: 'You are about to delete the expiry and expiry by stockcard for this item.',
          text: "Do you want to continue?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
      })
        .then((willDelete) => {
          if (willDelete.value) {
            window.location = '<?php echo $controller_page ?>/delete_xstockcard/<?php echo $itemID ?>/<?php echo $ancillaryID ?>/<?php echo $expiry ?>';
          }
      });


    }
</script>