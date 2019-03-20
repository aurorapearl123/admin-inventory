<div class="subheader">
    <div class="d-flex align-items-center">
        <div class="title mr-auto">
            <h3><i class="icon left la <?php echo $current_module['icon'] ?>"></i> <?php echo $current_module['title'] ?></h3>
        </div>
        <div class="subheader-tools"></div>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <div class="card-head">
                    <div class="head-caption">
                        <div class="head-title">
                            <h4 class="head-text">Add <?php echo $current_module['module_label'] ?></h4>
                        </div>
                    </div>
                    <div class="card-head-tools"></div>
                </div>
                    <!-- adjID   adjNo   date    branchID    itemID  adjType DR: Debit; CR: Credit   qty remarks createdBy   confirmedBy dateConfirmed   cancelledBy dateCancelled   dateInserted    dateLastEdit    dateDeleted deletedBy   status -->
                <div class="card-body">
                    <form method="post" name="frmEntry" id="frmEntry" action="<?php echo site_url('inventory_adjustment/save') ?>">
                        <div class="table-row">
                            <table class="table-form ">
                                <tbody>
                                    <tr>
                                        <td class="form-label" nowrap>Adjustment No. : <span class="asterisk">*</span></td>
                                        <td class="form-group form-input">
                                            <input type="text" name="adjNo" id="adjNo" class="form-control" value="<?php echo $series; ?>" />
                                        
                                        </td>
                                        <td class="form-label" width="13%" nowrap>Date : </td>
                                        <td class="form-group form-input" width="22%">
                                            <input type="text" class="form-control datepicker" id="date" name="date" data-toggle="datetimepicker" data-target="#date" title="Adjustment Date" value="<?php echo date('M d, Y') ?>" >
                                        </td>
                                        <td class="d-xxl-none"></td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="form-label" width="13%" nowrap>Office Name / Dept. : <span class="asterisk">*</span></td>
                                        <td class="form-group form-input" width="22%">
                                            
                                            <select id="ancillaryID" name="ancillaryID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Branch" onchange="get_items();" required>
                                                <option value="">&nbsp;</option>
                                                <?php 

                                                if (!$this->session->userdata('current_user')->isAdmin) {
                                                    $ancillaryID = $this->session->userdata('current_user')->ancillaryID;
                                                    $this->db->where('ancillaries.ancillaryID', $ancillaryID);
                                                }
                                                $this->db->where('status !=', -100);
                                                $results = $this->db->get('ancillaries')->result();
                                                foreach($results as $res){
                                                    
                                                    ?>
                                                    <option value="<?php echo $res->ancillaryID; ?>"  <?php echo ($ancillaryID == $res->ancillaryID)? 'selected': '' ?>><?php echo $res->division . ' / ' .$res->officeName; ?></option>
                                                <?php }?>
                                            </select>
                                        </td>
                                        <td class="form-label" width="13%" nowrap></td>
                                        <td class="form-group form-input" width="22%">
                                            
                                        </td>
                                        <td class="d-xxl-none"></td>
                                    </tr>
                                    <tr> 
                                        <td class="form-label" nowrap>Item : <span class="asterisk">*</span></td>
                                        <td class="form-group form-input">
                                        
                                        <select id="itemID" name="itemID" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Items" onchange="get_stockcards();" required>
                                                
                                            </select>
                                        </td>
                                        <td class="form-label" nowrap>Stockcard : <span class="asterisk">*</span></td>
                                        <td class="form-group form-input">
                                        
                                        <select id="stockcard" name="stockcard" class="form-control" data-live-search="true" liveSearchNormalize="true" title="Stockcard" required>
                                                
                                            </select>
                                        </td>
                                        <td class="form-label" nowrap></td>
                                        <td class="form-group form-input" nowrap>
                                            
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td class="form-label" nowrap>Adjustment Type : <span class="asterisk">*</span></td>
                                        <td class="form-group form-input">
                                            <select class="form-control" id="adjType" name="adjType" style="" title="Adjustment Type" required>
                                                <option value="">&nbsp;</option>
                                                <option value="DR">Increase</option>
                                                <option value="CR">Decrease</option>
                                            </select>
                                        </td>
                                        <td class="form-label" nowrap>Quantity : <span class="asterisk">*</span></td>
                                        <td class="form-group form-input" nowrap>
                                            <input type="text" class="form-control" name="qty" id="qty" value="" title="Quantity" required>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="form-label" nowrap></td>
                                        <td class="form-group form-input">
                                            
                                        </td>
                                        <td class="form-label" nowrap></td>
                                        <td class="form-group form-input">
                                            
                                        
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="form-label align-text-top pt-5">Remarks : <span class="asterisk">*</span></td>
                                        <td class="form-group form-input">
                                            <textarea rows="2" type="text" class="form-control" name="remarks" id="remarks" title="Remarks" required></textarea>
                                        </td>
                                        <td class="d-xxl-none"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-sepator solid"></div>
                        <div class="form-group mb-0">
                            <button class="btn btn-primary btn-raised pill" type="button" name="cmdSave" id="cmdSave">
                            Save
                            </button>
                            <input type="button" id="cmdCancel" class="btn btn-outline-danger btn-raised pill" value="Cancel"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function(){
        get_items();
    });
    
    var site_url = '<?php echo site_url(); ?>';

    globalEndbal = 0;


    <?php
    echo "\n";
    $parameters = array('ancillaryID');
    echo $this->htmlhelper->get_json_select('get_items', $parameters, site_url('generic_ajax/get_branch_items'), 'itemID', '', '') ;

    ?>



    $('#cmdSave').click(function()
    {
        if (check_fields()) {
            $('#cmdSave').attr('disabled','disabled');
            $('#cmdSave').addClass('loader');
            // $.post("<?php echo $controller_page ?>/check_duplicate", { branchID: $('#branchID').val(), itemID:$('#itemID').val(), adjType:$('#adjType').val(), remarks:$('#remarks').val()},
            //   function(data){
            //     if (parseInt(data)) {
            
            //         // duplicate
            //         swal("Duplicate","Record is already exist!","warning");
            //     } else {
                    // submit

                    if ($('#adjType').val() == 'CR' && globalEndbal < $('#qty').val() ) {
                        swal('Insufficient Quantity');
                        $('#cmdSave').removeClass("loader");
                        $('#cmdSave').removeAttr('disabled');
                    } else {
                        $('#frmEntry').submit();
                    }
                    
                    // alert($('#branchID').val());
              //   }
              // }, "text");
        }
    });
        
    function check_fields()
    {
         var valid = true;
         var req_fields = "";
         
         $('#frmEntry [required]').each(function(){
            if($(this).val()=='' ) {
                req_fields += "<br/>" + $(this).attr('title');
                valid = false;
            } 
         })
         
         if (!valid) {
            swal("Required Fields",req_fields,"warning");
         }
         
         return valid;
    }
    
    $('#cmdCancel').click(function()
    {
        swal({
              title: "Are you sure?",
              text: "",
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes',
              cancelButtonText: 'No'
            })
            .then((willDelete) => {
              if (willDelete.value) {
                  window.location = '<?php echo site_url('inventory_adjustment/show') ?>';
              }
            });
        
    });



    function get_stockcards()
    {

        var stockcard = $('#stockcard');
        stockcard.empty();
        stockcard.val("");
        stockcard.selectpicker("refresh");
        stockcard.append("<option value='' selected>&nbsp;</option>");
        var itemID = $('#itemID').val();
        var ancillaryID = $('#ancillaryID').val();
        
        if ($('#itemID').val() !="" && $('#ancillaryID').val() !="") {
            $.post(site_url+"generic_ajax/get_stockcards", { 
                itemID: itemID,
                ancillaryID
            },
            function(response){ 
                
                
                var select = '';
                var options = { month: 'short', year: 'numeric' };

                //Naay xstockcard if 1, else wala ni xstockcard nga item
                if (response.status == 1) {

                    if (response.records) {
                        //array
                        $.each(response.records, function(i, row){
                            var date = (row.expiry == '0000-00-00' || row.expiry == '1970-01-01')? row.expiry : new Date(row.expiry).toLocaleDateString('en-US', options);
                            console.log(row.itemName);
                            var opt = '';
                            opt += '<option value="'+row.expiry+'" data-endbal="'+row.endBal+'">';
                            opt += date+ ' - ' + '('+row.endBal+')' + ' @ P' + parseFloat(row.price).toFixed(2);
                            opt += '</option>';
                            stockcard.append(opt);

                            globalEndbal = row.endBal;
                        });
                    } else {
                            var opt = '<option value="" selected>No Results</option>';
                            stockcard.append(opt);
                            globalEndbal = 0;
                    }
                    
                    stockcard.selectpicker("refresh");
                } else {
                    if (response.records) {
                        //array
                        $.each(response.records, function(i, row){
                            var opt = '';
                            opt += '<option value="main" selected>';
                            opt += row.endBal + ' @ P' + parseFloat(row.price).toFixed(2);
                            opt += '</option>';
                            stockcard.append(opt);
                            globalEndbal = row.endBal;
                        });
                    } else {
                            var opt = '<option value="" selected>No Results</option>';
                            stockcard.append(opt);
                            globalEndbal = 0;
                    }
                    
                }
                stockcard.selectpicker("refresh");
              
            }, "json");
        }
    }
    
</script>