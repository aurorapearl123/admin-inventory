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
							<h4 class="head-text">Edit <?php echo $current_module['module_label'] ?></h4>
						</div>
					</div>
					<div class="card-head-tools"></div>
				</div>
				<div class="card-body">
					<form method="post" name="frmEntry" id="frmEntry" action="<?php echo site_url('inventory_adjustment/update') ?>">
						<input type="hidden" name="adjID" id="adjID" value="<?php echo $this->encrypter->encode($rec->adjID) ?>" />
                        <div class="table-row">
                            <table class="table-form column-3">
                                <tbody>
                                    <tr>
                                        <td class="form-label" width="13%" nowrap>Branch : <span class="asterisk">*</span></td>
                                        <td class="form-group form-input" width="22%">
                                            <?php 
                                                $this->db->select('branches.*');
                                                $this->db->from('branches');
                                                $this->db->where('status',1);
                                                $recs = $this->db->get()->result();                                             
                                            ?>
                                            <select class="form-control" id="branchID" name="branchID" data-live-search="true" livesearchnormalize="true" style="" title="-Select Branch-" >
                                                <?php foreach($recs as $res) {?>
                                                <option value="<?php echo $res->branchID ?>" <?php if ($res->branchID==$rec->branchID ) echo "selected"; ?>  ><?php echo $res->branchName ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td class="form-label" width="13%" nowrap>Date :</td>
                                        <td class="form-group form-input" width="22%">
                                            <input type="text" class="form-control datepicker" id="date" name="date" data-toggle="datetimepicker" value="<?php echo date('M d, Y',strtotime($rec->date))?>" data-target="#date" title="Date" required>
                                        </td>
                                        <td class="d-xxl-none"></td>
                                    </tr>
                                    <tr> 
                                        <td class="form-label" nowrap>Item : <span class="asterisk">*</span></td>
                                        <td class="form-group form-input">
                                            <?php 
                                                $this->db->select('items.*');
                                                $this->db->from('items');
                                                $this->db->where('status',1);
                                                $recs = $this->db->get()->result();                                             
                                            ?>
                                            <select class="form-control" id="itemID" name="itemID" data-live-search="true" livesearchnormalize="true" style="" title="-Select Items-" >
                                                <?php foreach($recs as $res) {?>
                                                <option value="<?php echo $res->itemID ?>" <?php if ($res->itemID==$rec->itemID ) echo "selected"; ?>  ><?php echo $res->brand .' '.$res->item.' '.$res->description.' '.$res->umsr ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>                               
                                        <td class="form-label" nowrap>QTY : <span class="asterisk">*</span></td>
                                        <td class="form-group form-input">
                                            <input type="text" class="form-control" name="qty" id="qty"  title="QTY" value="<?php echo $rec->qty?>" required>
                                        </td>
                                        <td class="d-xxl-none"></td>
                                    </tr>
                                    <tr>
                                        <td class="form-label" nowrap>Adjustment Type (+/-) : <span class="asterisk">*</span></td>
                                        <td class="form-group form-input">
                                            <select id="adjType" name="adjType" class="form-control" title="-Select Adjustment Type-" required>
                                                <option value="DR" <?php if($rec->adjType=='DR'){ echo "selected"; } ?>>Debit</option>
                                                <option value="CR" <?php if($rec->adjType=='CR'){ echo "selected"; } ?>>Credit</option>
                                            </select>
                                        </td>
                                        <td class="d-xxl-none" colspan="2"></td>
                                    </tr>
                                    <tr>                                        
                                        <td class="form-label align-text-top pt-5" nowrap>Remarks : <span class="asterisk">*</span></td>
                                        <td class="form-group form-input">
                                            <textarea rows="2" type="text" class="form-control" name="remarks" id="remarks" title="Remarks" required ><?php echo $rec->remarks?></textarea>
                                        </td>
                                        <td class="d-xxl-none" colspan="2"></td>
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
    $('#cmdSave').click(function(){
        if (check_fields()) {
            $('#cmdSave').attr('disabled','disabled');
            $('#cmdSave').addClass('loader');
            $('#frmEntry').submit();
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
    
    $('#cmdCancel').click(function(){
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
</script>
