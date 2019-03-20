<div class="subheader">
    <div class="d-flex align-items-center">
        <div class="title mr-auto">
            <h3><i class="icon left la <?php echo $current_module['icon'] ?>"></i> <?php echo $current_module['title'] ?></h3>
        </div>
        <div class="subheader-tools">
            <a href="<?php echo site_url('inventory_adjustment/show') ?>" class="btn btn-primary btn-raised btn-sm pill"><i class="icon left ti-angle-left md"></i> Back to List</a>
        </div>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <div class="card-head">
                    <div class="head-caption">
                        <div class="head-title">
                            <h4 class="head-text">View <?php echo $current_module['module_label'] ?>
                             &nbsp;| &nbsp;
                                <?php 
                                    if($rec->status==1){ 
                                        echo "<font color='blue'>Pending</font>"; 
                                    } else if($rec->status==2) { 
                                        echo "<font color='green'>Confirmed</font>"; 
                                    } else {
                                        echo "<font color='red'>Cancelled</font>"; 
                                    }
                                ?>
                            </h4>
                        </div>
                    </div>
                    <div class="card-head-tools">
                        <ul class="tools-list">
                            <?php if ($roles['confirm'] && $rec->status == 1) {?>
                            <li>
                                
                                <input type="button" id="confirmBtn" onclick="confirm_record(2);" class="btn btn-xs btn-primary btn-raised pill" value="Confirm"/>
                            </li>
                            <?php } ?>
                            <?php if ($roles['cancel'] && $rec->status == 1) {?>
                            <li>
                                <input type="button" onclick="confirm_record(0);" class="btn btn-xs btn-danger btn-raised pill" value="Cancel"/>
                            </li>
                            <?php } ?>
                            
                            <?php if ($roles['delete'] && !$in_used && $rec->status == 1)  {?>
                                <li>
                                    <button name="cmddelete" id="cmddelete" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete" onclick="deleteRecord('<?php echo $this->encrypter->encode($rec->adjID); ?>');"><i class="la la-trash-o"></i></button>
                                </li>
                            <?php } ?>
                            <?php if ($this->session->userdata('current_user')->isAdmin) {?>
                                <li>
                                    <button type="button" id="recordlog" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Record Logs" onclick="popUp('<?php echo site_url('logs/record_log/inventory_adjustment/adjID/'.$this->encrypter->encode($rec->adjID).'/Inventory Adjustment') ?>', 1000, 500)"><i class="la la-server"></i></button>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="data-view">
                        <table class="view-table">
                            <tbody>
                                <tr>
                                    <td class="data-title">Adjustment No :</td>
                                    <td class="data-input"><?php echo $rec->adjNo; ?></td>
                                </tr>
                                <tr>

                                    <td class="data-title w-10">Office / Dept :</td>
                                    <td class="data-input w-20"><?php echo $rec->officeName; ?></td>
                                    <td class="data-title w-10">Date :</td>
                                    <td class="data-input w-20"><?php echo date('F d, Y',strtotime($rec->date))?></td>
                                    <td class="d-xxl-none"></td>
                                </tr>
                                <tr>
                                    <td class="data-title">Brand :</td>
                                    <td class="data-input"><?php echo $rec->brand; ?></td>
                                    <td class="data-title">Item</td>
                                    <td class="data-input"><?php echo $rec->name; ?></td>
                                    <td class="data-input d-none" style="visibility: hidden" id="itemID"><?php echo $rec->itemID; ?></td>
                                </tr>
                                <tr>
                                    <td class="data-title">Description :</td>
                                    <td class="data-input"><?php echo $rec->description; ?></td>
                                    <td class="data-title">Remarks :</td>
                                    <td class="data-input"><?php echo $rec->remarks; ?></td>
                                    <td class="d-xxl-none"></td>
                                </tr>
                                <tr>
                                    <td class="data-title">UMSR :</td>
                                    <td class="data-input"><?php echo $rec->umsr; ?></td>
                                    <td class="data-title">QTY :</td>
                                    <td class="data-input"><?php echo $rec->qty; ?></td>
                                    <td class="d-xxl-none"></td>
                                </tr>
                                <tr>
                                    <td class="data-title">Adjustment Type:</td>
                                    <td>                                
                                        <?php 
                                            if ($rec->adjType == 'DR') {
                                                echo "<span class='badge badge-pill badge-info'>Increase</span>";
                                            } else if ($rec->adjType == 'CR') {
                                                echo "<span class='badge badge-pill badge-info'>Decrease</span>";
                                            }
                                        ?>
                                    </td>
                                    <td class="d-xxl-none" colspan="3"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="datatable-footer d-flex">

                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    var adjID = "<?php echo $rec->adjID; ?>";
    var itemID = "<?php echo $rec->itemID; ?>";
    var qty = "<?php echo $rec->qty; ?>";
    var adjType = '<?php echo $rec->adjType?>';
    var debit = qty;
    var credit = 0;
    var begBal = qty;
    var endBal = qty;

    function confirm(){
        
            console.log('adjID',adjID)
            console.log('itemID',itemID)
            console.log('qty',qty)
            console.log('debit',debit)
            console.log('adjID',adjID)
            console.log('credit',credit)
            console.log('begBal',begBal)
            console.log('endBal',endBal)
            console.log('endBal',endBal)
            console.log('adjType',adjType)

       swal({
          title: "You are performing 'CONFIRM' action.",
          text: "Do you still want to continue?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
        })
        .then((willConfirm) => {
          if (willConfirm.value) {

                $.post("<?php echo $controller_page ?>/updateApprovedBy", { adjID: adjID },
                    function(data, status){
                        if(status == "success") {   
                            $.post("<?php echo $controller_page ?>/updateQTY", { itemID: itemID, qty:qty, adjType:adjType },
                                function(data, status){
                                    if(status == "success") {                                                                                   
                                        window.location.reload(); 
                                    }
                                }
                            );    
                        }
                    }
                );         

          }
        });     
    }//end of function confirm

    function cancel(){

       swal({
          title: "You are performing 'CANCEL' action.",
          text: "Do you still want to continue?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
        })
        .then((willCancel) => {
          if (willCancel.value) {
            var adjID = "<?php echo $rec->adjID; ?>";
                $.post("<?php echo $controller_page ?>/updateCancelledBy", { adjID: adjID },
                    function(data, status){                     
                        if(status == "success") {
                           window.location.reload();                            
                        }
                });
          }
        });     
    }//end of function cancel


    // function confirm_record(status='') {
        
        

    //     var title = '';
    //     if (status == 2) {
    //         title = 'confirm this record';
    //     } else if (status == 0) {
    //         title = 'cancel this record';
    //     }
    //     var id = "<?php //echo $rec->adjID; ?>";
    //     swal({
    //         title: 'You are about to '+title+'.',
    //         text: "Do you still want to continue?",
    //         icon: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Yes',
    //         cancelButtonText: 'No'
    //     })
    //     .then((willDelete) => {
    //         if (willDelete.value) {
    //             if (status == 2) {
    //                $('#confirmBtn').hide();   
    //             }
                
    //                 $.post("<?php //echo site_url()?>inventory_adjustment/confirm_record", { id: id, status: status},
    //                     function(response){ 
    //                         console.log('ssssss');

    //                         window.location='';

    //                     }, "json");
    //             }
    //         });

    // }


function confirm_record(status='') {
        
        

        var title = '';
        if (status == 2) {
            title = 'confirm this record';
        } else if (status == 0) {
            title = 'cancel this record';
        }
        var id = "<?php echo $rec->adjID; ?>";
        swal({
            title: 'You are about to '+title+'.',
            text: "Do you still want to continue?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        })
        .then((willDelete) => {
            if (willDelete.value) {
                
                
                window.location='<?php echo site_url()?>inventory_adjustment/confirm_record/'+id+'/'+status;
                
                }
            });

    }
    
</script>