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
               <form method="post" name="frmEntry" id="frmEntry" action="<?php echo site_url('config/update') ?>">
                  <input type="hidden" name="configID" id="configID" value="<?php echo $this->encrypter->encode($rec->configID) ?>" />
                  <div class="table-row">
                     <table class="table-form">
                        <tbody>
                           <tr>
                              <td class="form-label align-text-top pt-5" style="width:100px">
                                 <label>Config<span class="asterisk">*</span></label>
                              </td>
                              <td class="form-group form-input align-text-top">
                                 <input type="text" class="form-control" name="name" id="name" value="<?php echo $rec->name ?>" style="width:250px" title="Config" readonly required>
                              </td>
                              <td class="d-xxl2-none"></td>
                           </tr>
                           <tr>
                              <td class="form-label align-text-top pt-5">
                                 <label>Value<span class="asterisk">*</span></label>
                              </td>
                              <td class="form-group form-input">
                                 <textarea name="value" id="value" class="form-control" style="width:500px" title="Value" required><?php echo $rec->value ?></textarea>
                              </td>
                              <td class="d-xxl2-none"></td>
                           </tr>
                           <tr>
                              <td class="form-label align-text-top pt-5">
                                 <label>Remark</label>
                              </td>
                              <td class="form-group form-input">
                                 <textarea name="description" id="description" class="form-control" style="width:500px"><?php echo $rec->description ?></textarea>
                              </td>
                              <td class="d-xxl2-none"></td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
                  <div class="form-sepator solid"></div>
                  <div class="form-group mb-0">
                     <button class="btn btn-xs btn-primary btn-raised pill" type="button" name="cmdSave" id="cmdSave">
                     Save
                     </button>
                     <input type="button" id="cmdCancel" class="btn btn-xs btn-outline-danger btn-raised pill" value="Cancel"/>
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
   	    	  window.location = '<?php echo site_url('config/show') ?>';
   	      }
   	    });
       
   });
</script>