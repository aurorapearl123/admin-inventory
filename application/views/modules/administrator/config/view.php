<div class="subheader">
   <div class="d-flex align-items-center">
      <div class="title mr-auto">
         <h3><i class="icon left la <?php echo $current_module['icon'] ?>"></i> <?php echo $current_module['title'] ?></h3>
      </div>
      <div class="subheader-tools">
         <a href="<?php echo site_url('config/show') ?>" class="btn btn-xs btn-primary btn-raised btn-xs pill"><i class="icon ti-angle-left"></i> Back to List</a>
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
                     <h4 class="head-text">View <?php echo $current_module['module_label'] ?></h4>
                  </div>
               </div>
               <div class="card-head-tools">
                  <ul class="tools-list">
                     <?php if ($roles['edit']) {?>
                     <li>
                        <a href="<?php echo site_url('config/edit/'.$this->encrypter->encode($rec->configID)) ?>" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit"><i class="la la-edit"></i></a>
                     </li>
                     <?php } ?>
                     <?php if ($roles['delete'] && !$in_used) {?>
                     <li>
                        <button name="cmddelete" id="cmddelete" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete" onclick="deleteRecord('<?php echo $this->encrypter->encode($rec->configID); ?>');"><i class="la la-trash-o"></i></button>
                     </li>
                     <?php } ?>
                     <?php if ($this->session->userdata('current_user')->isAdmin) {?>
                     <li>
                        <button type="button" id="recordlog" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Record Logs" onclick="popUp('<?php echo site_url('logs/record_log/config/configID/'.$this->encrypter->encode($rec->configID).'/Configuration') ?>', 1000, 500)"><i class="la la-server"></i></button>
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
                           <td class="data-title" width="12%">Config:</td>
                           <td class="data-input"><?php echo $rec->name; ?></td>
                        </tr>
                        <tr>
                           <td class="data-title">Value:</td>
                           <td class="data-input"><?php echo $rec->value; ?></td>
                        </tr>
                        <tr>
                           <td class="data-title">Description:</td>
                           <td class="data-input"><?php echo $rec->description; ?></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>