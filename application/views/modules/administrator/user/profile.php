
<div class="subheader">
  <div class="d-flex align-items-center">
    <div class="title mr-auto">
      <h3><i class="icon left flaticon-profile-1"></i> My Profile</h3>
    </div>
  </div>
</div>
<div class="content">
  <div class="row">
    <div class="col-xl-3">
      <div class="card-box">
        <div class="card-user py-110">
          <div class="card-user-pic">
            <?php if ($this->session->userdata('current_user')->imageExtension) { ?>
              <img src="<?php echo base_url().'assets/img/users/'.$this->session->userdata('current_user')->userID.$this->session->userdata('current_user')->imageExtension; ?>" class="img-rounded" alt="">
            <?php } else { ?>
             <img src="<?php echo base_url('assets/img/users/noimage.gif') ?>" class="img-rounded" alt="">
           <?php } ?>

           <a type="button" class="btn bmd-btn-icon" data-toggle="modal" data-target="#modal1"><i class="la la-camera-retro"></i></a>
         </div>
         <div class="card-user-details mt-30">
          <span class="card-user-name"><?php echo $rec->firstName.' '.$rec->lastName?></span>
          <span class="card-user-position d-block mt-10">Software Implementation</span>
          <span class="card-user-position d-block">ID: <?php echo $rec->empNo?></span>
          <span class="card-user-position d-block">Status: 
            <?php 
            if($rec->status == 1){
              echo "<span class='badge badge-pill badge-success'>Active</span>";
            }elseif($rec->status == 0){
              echo "<span class='badge badge-pill badge-light'>Inactive</span>";
            }
            ?>
          </span>
        </div>
      </div>

    </div>
  </div> <!-- col-xl-3 end -->

  <div class="col-xl-9">
   
    <div class="card-box">
      <div class="card-head">
        <div class="head-caption">
          <div class="head-title">
            <h4 class="head-text">Personal Information</h4>
          </div>
        </div>
        <div class="card-head-tools">
          <ul class="tools-list">
            <li>
              <a href="<?php echo site_url("user/edit_profile") ?>" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Edit"><i class="icon la la-edit"></i></a>
            </li>
            <li>
              <a class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Change Password" id="saveNewPassPopupBtn"><i class="icon la la-unlock"></i></a>
            </li>
          </ul>
        </div>
      </div>
      <div class="card-body">
        <div class="tab-content">
          <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="subtitle">

            </div>

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
                    <td class="data-title w-15">Branch:</td>
                    <td class="data-input w-35"><?php echo $rec->branchName ?></td>
                  </tr>
                  <tr>
                    <td class="data-title w-15">Department:</td>
                    <td class="data-input w-35"><?php echo $rec->deptName ?></td>
                    <td class="data-title w-15">User Admin:</td>
                    <td class="data-input w-35"><div class="checkbox">
                      <label>
                        <input type="checkbox" <?php if ($rec->isAdmin) echo "checked" ?> disabled> &nbsp;
                      </label>
                    </div></td>
                  </tr>
                </tbody>
              </table>
            </div>

            <?php 
            $preferences = explode(',', $rec->preferences);
            
            ?>
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
                      if ($mod != "Administrator") {
                        $ctr++;
                        if ($ctr % 5 == 0) {
                          echo '</tr>';
                          echo '<tr>';
                        }
                        ?>
                        <td class="data-input border-0 w-25">
                          <div class="switch">
                            <label>
                              <input type="checkbox" name="modules[]" value="<?php echo $mod ?>" <?php if (in_array($mod, $preferences)) echo "checked"  ?> />
                              <?php echo $mod ?>
                            </label>
                          </div>
                        </td>
                      <?php }
                    }
                    ?>
                  </tr>

                </tbody>
              </table>
            </div>
            <!-- Module preferences end here -->

          </div>
        </div>
      </div>
    </div>
  </div> <!-- col-xl-9 end -->
</div>



  <div class="card-box">
    <div class="card-head">
      <div class="head-caption">
        <div class="head-title">
          <h4 class="head-text">MODULE PRIVILEGES </h4>
        </div>
      </div>
      <div class="card-head-tools"></div>
    </div>
    <div class="card-body">
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
      <input type="hidden" name="userID" id="userID" value="<?php echo $rec->userID ?>" />
      <?php 
        foreach($modules as $mod=>$submod) { 
          if ($mod != "Administrator") {
            ?>
            <div class="row">
              <div class="col-12">
                <div class="subtitle mb-15">
                  <h5 class="title"><i class="icon left la <?php echo $submod['main']['icon'] ?>"></i> <?php echo strtoupper($submod['main']['title']) ?> MODULE</span></h5>
                </div>
                <div class="row">
                <?php 
                  if (!empty($submod['sub'])) {
                    foreach($submod['sub'] as $sub=>$subcon) {
                      ?>
                    <div class="col-6 col-xl-3 mb-25">
                      <div class="subtitle">
                        <h5 class="title"><?php echo strtoupper($subcon['title']) ?></h5>
                      </div>
                      <div class="checkbox mb-15">
                        <label>
                          <input type="checkbox" id="<?php echo strtolower(str_replace(" ", "_", $sub)) ?>" name="<?php echo str_replace(" ", "_", $sub)?>" value="1" onclick="check_all('<?php echo strtolower(str_replace(" ", "_", $sub)) ?>')" disabled/>  <?php echo $subcon['description'] ?>
                        </label>
                      </div>
                      <?php 
                        if (!empty($subcon['roles'])) {
                          foreach($subcon['roles'] as $role) {
                            $all_sub[] = $sub;
                            ?>
                          <div class="checkbox pb-10">
                            <label>
                              <input type="checkbox" class="<?php echo strtolower(str_replace(" ", "_", $sub)) ?>" name="roles[]" value="<?php echo $role ?>" <?php if (in_array($role, $roles)) echo "checked"  ?> disabled/> <?php echo $role ?>
                            </label>
                          </div>
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
            </div>
          </div>
          <div class="form-sepator mt-0 solid mx-0"></div>
        <?php }
        }
        ?>
    </div>
  </div>
</div>


<!-- Change Profile Pic modal -->
<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <form method="post" name="frmEntry2" id="frmEntry2" action="<?php echo $controller_page ?>/upload" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="card-user mb-30">
            <div class="card-user-pic">
              <input type="hidden" id="imgID" name="imgID" value="<?php echo $rec->userID?>"/>
              <img src="<?php echo base_url().'assets/img/users/'.$this->session->userdata('current_user')->userID.$this->session->userdata('current_user')->imageExtension; ?>" class="img-rounded" alt="">
            </div>
          </div>
          <div class="form-group">
            <input type="file" class="form-control filestyle" id="userfile" name="userfile">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" id="cmdSave2" class="btn btn-primary btn-raised pill">Save</button>
          <button type="button" id="close" class="btn btn-outline-danger btn-raised pill" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $('#cmdSave2').click(function(){
    $('#cmdSave2').attr('disabled','disabled');
    $('#cmdSave2').addClass('loader');
    $('#frmEntry2').submit();
  });
</script>


