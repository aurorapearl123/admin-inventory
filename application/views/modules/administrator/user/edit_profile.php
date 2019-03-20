          <div class="subheader">
            <div class="d-flex align-items-center">
              <div class="title mr-auto">
                <h3><i class="icon left flaticon-profile-1"></i> My Profile</h3>
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
                        <h4 class="head-text">Edit Profile</h4>
                      </div>
                    </div>
                    <div class="card-head-tools"></div>
                  </div>
                  <div class="card-body">
                                   <div >
                  </div>
                    <form method="post" name="frmEntry" id="frmEntry" action="<?php echo site_url("user/update_profile") ?>">
                      <div class="table-row">
                        <table class="table-form">
                          <tbody>
                            <tr>
                              <td class="form-label" width="12%">
                                <label for="lastName">Last Name<span class="asterisk">*</span></label>
                              </td>
                              <td class="form-group form-input" width="21.33%">
                                <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $rec->lastName ?>" required>
                              </td>
                              <td class="form-label" width="12%">
                                <label for="firstName">First Name<span class="asterisk">*</span></label>
                              </td>
                              <td class="form-group form-input" width="21.33%">
                                <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo $rec->firstName ?>" required>
                              </td>
                              <td class="form-label" width="12%">
                                <label for="middleName">Middle Name<span class="asterisk">*</span></label>
                              </td>
                              <td class="form-group form-input" width="21.33%">
                                <input type="text" class="form-control" id="middleName" name="middleName" value="<?php echo $rec->middleName ?>" required>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>

                    <!-- Disabled fields -->
                    <div class="form-sepator solid"></div>
                    <div class="subtitle">
                      <h5 class="title"><i class="icon left la la-user"></i> User Account</h5>
                    </div>
                    <div class="data-view">
                      <table class="view-table">
                        <tbody>
                          <tr>
                            <td class="data-title w-15">Username:</td>
                            <td class="data-input w-35"><?php echo $rec->userName ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <!-- For second column put here -->
                            <!-- Module Preferences Module tabs here -->
                          </tr>
                          <tr>
                            <td class="data-title w-15">User Group:</td>
                            <td class="data-input w-35"><?php echo $rec->groupName ?></td>

                          </tr>
                          <tr>
                            <td class="data-title w-15">User Admin:</td>
                            <td class="data-input w-35">
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox" <?php if ($rec->isAdmin) echo "checked" ?> disabled> &nbsp;
                                </label>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td class="data-title w-15">Status:</td>
                            <td class="data-input w-35"><span class="badge pill badge-success">Active</span></td>
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
	    	  window.location = '<?php echo site_url('user/profile') ?>';
	      }
	    });
    
});
</script>
