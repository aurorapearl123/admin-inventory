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
        <div class="col-12">
        
        
        
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
                            <?php $id = $this->encrypter->encode($rec->productID); ?>
                            
                            
                            <li>
                                <button class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="Print" onclick="popUp('<?php echo $controller_page ?>/print_stockcard/<?php echo $id?>/<?php echo $ancillaryID ?>', 900, 500)"><i class="la la-file-pdf-o"></i></button>
                            </li>
                            
                            <?php if ($this->session->userdata('current_user')->isAdmin) {?>
                            <li>
                                <button type="button" id="recordlog" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Record Logs" onclick="popUp('<?php echo site_url('logs/record_log/'.$table_name.'/'.$pfield.'/'.$id.'/'.ucfirst(str_replace('_', '&', $controller_name))) ?>', 1000, 500)"><i class="la la-server"></i></button>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
           
                
                
                <!-- Card Body Start -->
                <div class="card-body">
                    <div class="data-view">
                        <!-- <div class="subtitle">
                            <h5 class="title"><i class="icon left la la-user"></i> ITEM GENERAL STOCKCARD</h5>
                        </div> -->
                        <table class="table view-table">
               

                    <tr class="">
                        <td class="data-title" align="left" width="12%">
                            <strong>Customer: </strong>
                        </td>
                        <td class="data-input" align="left" colspan="3" width="65%">
                            <strong><?php echo $rec->companyName ?></strong>
                        </td>

                        <td class="data-title" align="left">
                            <strong>Rank: </strong>

                        </td>
                        <td class="data-input   " align="left">
                            <strong><i class="icon la la-star" style="color: #FFD700;"></i><i class="icon la la-star-half-full" style="color: #FFD700;"></i></strong>
                        </td>
                    </tr>
                    <tr class="">
                        <td class="data-title" align="left" width="12%">
                            <strong>Product Name: </strong>
                        </td>
                        <td class="data-input" align="left" colspan="3" width="65%">
                            <strong><?php echo $rec->name ?></strong>
                        </td>

                        <td class="data-title" align="left">
                            <strong>Stock No: </strong>

                        </td>
                        <td class="data-input   " align="left">
                            <strong><?php echo $rec->productCode ?></strong>
                        </td>
                    </tr>

                    <tr class="">
                        <td class="data-title" align="left">
                            <strong>Description: </strong>
                        </td>
                        <td class="data-input   " align="left" colspan="3">
                            <strong><?php echo $rec->category ?></strong>
                        </td>

                        <td class="data-title" align="left">
                            <strong>Reorder Level: </strong>

                        </td>
                        <td class="data-input   " align="left" width="15%">
                            <strong><?php echo $rec->reorderLvl ?></strong>
                        </td>
                    </tr>
                    <tr class="">
                        <td class="data-title" align="left">
                            <strong>Unit of Measurement: </strong>
                        </td>
                        <td class="data-input   " align="left" colspan="3">
                            <strong><?php echo $rec->umsr ?></strong>
                        </td>

                        <td class="data-title" align="left">
                            <strong></strong>

                        </td>
                        <td class="data-input   " align="left">
                            <strong></strong>
                        </td>
                    </tr>
                   
                    
                    

                    
                </table>
                <div class="form-sepator mb-20"></div>
                <form method="post" name="frmEntry" id="frmEntry" action="<?php echo $controller_page.'/view/'.$this->encrypter->encode($productID) ?>">
                <div class="datatables_wrapper">
                        <div class="table-responsive-md">
                                              
                        <table id="family-members" class="table">
                                <thead class="thead-light" align="center">

                                    <tr>
                                        <th class="w-15">
                                            <select id="month" name="month" class="form-control" title="Month" required>
                                                <?php $months = array(1=>'January', 'Febuary', 'March','April','May','June','July','August','September','October','November','December'); ?>
                                                <?php foreach ($months as $i=>$mon) { ?>
                                                    <?php if ($month) { ?>

                                                        <option value="<?php echo $i ?>" <?php if ($i == $month) { echo "selected"; } ?>> <?php echo $mon ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $i ?>" <?php if ($i == date('n')) { echo "selected"; } ?>> <?php echo $mon ?></option>
                                                    <?php } ?>
                                                <?php } ?>

                                            </select>
                                        </th>
                                        <th class="w-15">
                                            <select class="form-control" name="year" id="year">
                                                <!-- Let's just set static for now -->
                                                <?php $current_date = date('Y') + 10; ?>
                                                <?php for ($i = date('Y'); $i <= $current_date; $i++) { ?>
                                                    <option value="<?php echo $i ?>" <?php if ($year == '') { if($i == date('Y')) { echo "selected"; } } else if ($year == $i) { echo "selected"; }?>><?php echo $i ?></option>
                                                <?php } ?>

                                            </select>
                                        </th>
                                        <th class="w-15">
                                            <select class="form-control" name="day" id="day">
                                                <!-- Let's just set static for now -->
                                                <?php 
                                                $current_date = date('d');
                                                for ($i = 0; $i <= 31; $i++) { 
                                                	if($i<=9) {
                                                		$day = '0'.$i;
                                                	} else {
                                                		$day = $i;
                                                	}
                                                ?>
                                                    <option value="<?php echo $day ?>" <?php if ($day == $day2) { echo "selected"; } ?>><?php echo $day ?></option>
                                                <?php } ?>

                                            </select>
                                        </th>
                                        <th colspan="4">
                                            <div align="left">
                                                <button id="btn-apply" type="submit" class="btn btn-primary btn-xs pill collapse multi-collapse show" >Apply Filter</button>
                                            </div>
                                        </th>
                                    </tr>

                                </thead>
                            </table>

<br>



                            <table class="table">
                                <thead class="thead-light" align="center">

                                    <tr class="">
                                        <th class="  " colspan="" align="center" style=""><strong>TRANSACTION DATE</strong></th>
                                        <th class=" " align="center" style=""><strong>BEG. BAL</strong></th>
                                        <th class=" " align="center" style=""><strong>RECEIPT</strong></th>
                                        <th class=" " align="center" style=""><strong>ISSUE</strong></th>
                                        <th class=" " align="center" style=""><strong>ENDING BAL</strong></th>
                                        <th class=" " align="center" style=""><strong>REFERENCE NO.</strong></th>

                                    </tr>

                                </thead>

                                <tbody align="center" class="">



                                    <?php

                                    if (!empty($records)) { 
                                        foreach ($records as $row) { ?>
                                            <!-- foreach start -->
                                            <tr>
                                                <td class="  w-10" align="center"><?php echo date('m/d/Y H:i A', strtotime($row->dateInserted)) ?></td>
                                                <td class="  w-10" align="" ><?php echo $row->begBal ?></td>
                                                <td class="  w-10" align="" ><?php echo $row->increase ?></td>
                                                <td class="  w-10" align="" ><?php echo $row->decrease ?></td>
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
            </form>
        </div>
                </div>
                <div class="datatable-footer d-flex">

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

</script>