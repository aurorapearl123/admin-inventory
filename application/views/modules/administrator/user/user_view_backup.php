
          <div class="subheader">
            <div class="d-flex align-items-center">
              <div class="title mr-auto">
                <h3><i class="icon left flaticon-profile-1"></i> System Users</h3>
              </div>
              <div class="subheader-tools">
                <a href="<?php echo site_url('user/index') ?>" class="btn btn-xs btn-primary btn-raised pill"><i class="icon ti-angle-left"></i> Back to List</a>
              </div>
            </div>
          </div>
          <div class="content">
            <div class="card-box">
              <div class="card-head">
                <div class="head-caption">
                  <div class="head-title">
                    <h4 class="head-text">User Profile</h4>
                  </div>
                </div>
                <div class="card-head-tools">


                                        <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item ml-auto">
                      <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-selected="true">Profile</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="setting-tab" data-toggle="tab" href="#setting" role="tab" aria-selected="false">Settings</a>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="card-body">

                <div class="tab-content">
                  <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="subtitle">
                                      <ul class="tools-list">
                        <li>
                          <a href="<?php echo site_url("user/edit/").$rec->userID ?>" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Edit"><i class="icon la la-edit"></i></a>
                        </li>
                          <li>
                          <a class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Change Password" id="changeOtherUserPassPopupBtn"><i class="icon la la-unlock"></i></a>
                        </li>
                      </ul>

                      
                    </div>
                    <div class="data-view">
                      <table class="view-table">
                        <tbody>
                          <tr>
                            <td class="data-title" width="100">Name:</td>
                            <td class="data-input w-35"><?php echo $rec->lastName.', '.$rec->firstName ?></td>
                            <td></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
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
                            <td class="data-title w-15">User Group:</td>
                            <td class="data-input w-35"><?php echo $rec->groupName ?></td>
                          </tr>
                          <tr>
                            <td class="data-title w-15">Company:</td>
                            <td class="data-input w-35"><?php echo $rec->companyName ?></td>
                            <td class="data-title w-15">Office:</td>
                            <td class="data-input w-35"><?php echo $rec->branchName ?></td>
                          </tr>
                          <tr>
                            <td class="data-title w-15">Division:</td>
                            <td class="data-input w-35"><?php echo $rec->divisionName ?></td>
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
                            <td class="data-title w-15">Access Time (24-hour format):</td>
                            <td class="data-input w-35">0 - 0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>


                    <!-- Module preferences start here -->
                    <div class="form-sepator solid"></div>
                    <div class="subtitle">
                      <h5 class="title"><i class="icon left la la-file-code-o"></i> Module Preferences</h5>
                    </div>
                    <div class="data-view">
                      <table class="view-table">
                        <tbody>
                          <tr>
                          <?php 
                          $ctr = 0;
                          foreach($modules as $mod=>$details) {
                              $ctr++;
                                if ($ctr % 5 == 0) {
                                    echo '</tr>';
                                    echo '<tr>';
                                }
                              ?>
                            <td class="data-input border-0 w-25">
                              <div class="switch">
                                <label>
                                  <input type="checkbox" name="modules[]" value="<?php echo $mod ?>" disabled checked/>
                                  <?php echo $mod ?>
                                </label>
                              </div>
                            </td>
                           <?php } ?>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!-- Module preferences end here -->

                  </div>
                  <div class="tab-pane fade" id="setting" role="tabpanel" aria-labelledby="setting-tab">
                    <div class="data-view" style="padding: 20px;">


<?php 
$this->db->select('roleName');
$this->db->where('userID', $rec->userID);
$this->db->where('rstatus', 1);
$userroles = $this->db->get('userroles')->result();
$roles = array();
foreach($userroles as $r) {
    $roles[] = $r->roleName;
}

?>

<!-- Added new data here -->
<h3>MODULE PRIVILEGES</h3>
<form name="frmPrivileges" id="frmPrivileges" method="POST" action="<?php echo site_url("user/update_roles") ?>">
<input type="hidden" name="userID" id="userID" value="<?php echo $rec->userID ?>" />
<?php 
foreach($modules as $mod=>$submod) { ?>
    <div style="float: left; width: 99%;   margin: 3px; padding: 5px;">
        <span style="font-weight: bold; margin: 5px;"><i class="icon left la <?php echo $submod['main']['icon'] ?>"></i> <?php echo strtoupper($submod['main']['title']) ?> MODULE</span><br/>
        <span style="padding: 5px;"><?php echo $submod['main']['description'] ?> </span>
        <br/>
        <?php 
        if (!empty($submod['sub'])) {
            foreach($submod['sub'] as $sub=>$subcon) {
               ?>
               <div style="float: left; width: 300px; height: auto; min-height: 210px;  margin: 3px; padding: 5px; background-color: #ffffff; ">
                    <span style="font-weight: bold; margin: 5px;" title="CHECK ALL" ><?php echo strtoupper($subcon['title']) ?></span><br/>
                    <span style="padding: 5px;"><?php echo $subcon['description'] ?> </span><br/><br/>
                    
                    <?php 
                    if (!empty($subcon['roles'])) {
                        foreach($subcon['roles'] as $role) {
                    ?>
                    <label style="font-weight: bold; margin-top: 5px;"><input type="checkbox" class="<?php echo strtolower(str_replace(" ", "_", $sub)) ?>" name="roles[]" value="<?php echo $role ?>" <?php if (in_array($role, $roles)) echo "checked"  ?> /> <?php echo $role ?></label><br/>
                    <?php 
                        }
                    }
                    ?>
                </div>
               <?php 
            }
        }
        ?>
        
    </div>
<?php } ?>

<button id="cmdUpdate" class="btn btn-xs btn-primary btn-raised pill" name="cmdUpdate">Update</button>
</form>
<!-- Added new data end here -->




                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>




        <!-- Change Password -->
    <div class="modal fade" id="changeOtherUserPassModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <form method="post" name="frmChangeOtherPassword" id="frmChangeOtherPassword" action="<?php echo site_url("user/save_password") ?>">
            <input type="hidden" name="userID" id="userID" value="<?php echo $rec->userID ?>" />
            <input type="hidden" name="pageName" id="pageName" value="userView" />
            <div class="modal-header">
              <h4 class="modal-title">Change User Password</h4>
            </div>
            <div class="modal-body">
              <div class="table-row">
                <table class="table-form">
                  <tbody>
                    <tr>
                      <td class="form-label">
                        <label for="employee">New Password • <span class="asterisk">*</span></label>
                      </td>
                      <td class="form-group form-input">
                        <input type="password" class="form-control" name="userPswd" id="userPswd" required>
                      </td>
                    </tr>
                    <tr>
                      <td class="form-label">
                        <label for="fmname">Re-Password • <span class="asterisk">*</span></label>
                      </td>
                      <td class="form-group form-input">
                        <input type="password" class="form-control" name="rePswd" id="rePswd" required>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="changeOtherUserPassBtn" class="btn btn-primary btn-raised pill">Save</button>
              <button type="button" class="btn btn-outline-danger btn-raised pill" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>


<script>
  $('#changeOtherUserPassBtn').click(function() {
    if ($('#userPswd').val() == $('#rePswd').val()) {
      $('#frmChangeOtherPassword').submit();
    } else {
      alert("Passwords does not matched!");
    }

  });
</script>

<script type="text/javascript">
  $('#changeOtherUserPassPopupBtn').click(function(){
    $('#changeOtherUserPassModal').modal('show');
  });

</script>



<script>

$('#cmdUpdate').click(function(){
		$('#cmdUpdate').attr('disabled','disabled');
    	$('#cmdUpdate').addClass('loader');
       	$('#frmPrivileges').submit();
});
</script>