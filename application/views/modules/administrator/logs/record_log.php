        <div class="subheader">
            <div class="d-flex align-items-center">
              <div class="title mr-auto">
                <h4><i class="icon left la la-tasks"></i> Record Logs</span></h4>
              </div>
            </div>
          </div>
          <div class="content">
            <div class="row">
              <div class="col-12">
                <div class="card-box full-body">
                  <!--  sorting_asc -->
                  <div class="card-body">
                    <div class="datatables_wrapper">
                      <div class="table-responsive">
                        <table class="table table-striped has-img hover">
                          <thead>
                            <tr style="font-weight: bold;">
                                <td nowrap>Date/Time</td>
                				<td nowrap>Workstation</td>
                				<td nowrap>User</td>
                				<td nowrap>Module</td>
                				<td nowrap>Operation</td>
                				<td nowrap>Log</td>
                            </tr>
                          </thead>
                          <tbody>
                          <?php 
                            if (count($records_table)) {
                              foreach($records_table as $row) {
                              ?>
                                    <tr>
                                      <td><?php echo $row['date'] ?></td>
                                      <td><?php echo $row['host'] ?></td>
                                      <td><?php echo $row['user'] ?></td>
                                      <td><?php echo $row['module'] ?></td>
                                      <td><?php echo $row['operation'] ?></td>
                                      <td><?php echo $row['logs'] ?></td>
                                    </tr>
                            <?php }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="7" align="center"> <i>No records found!</i></td>
                                </tr>
                          <?php       
                            }
                      	  ?>
                          </tbody>
                        </table>
                      </div>
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="subheader">
            <div class="d-flex align-items-center">
              <div class="title mr-auto">
                <h4><i class="icon left la la-tasks"></i> Field Logs</span></h4>
              </div>
            </div>
          </div>
          <div class="content">
            <div class="row">
              <div class="col-12">
                <div class="card-box full-body">
                  <!--  sorting_asc -->
                  <div class="card-body">
                    <div class="datatables_wrapper">
                      <div class="table-responsive">
                        <table class="table table-striped has-img hover">
                          <thead>
                            <tr style="font-weight: bold;">
                                <td nowrap>Date/Time</td>
                				<td nowrap>Workstation</td>
                				<td nowrap>User</td>
                				<td nowrap>Operation</td>
                				<td nowrap>Field</td>
                				<td nowrap>Old Value</td>
                				<td nowrap>New Value</td>
                            </tr>
                          </thead>
                          <tbody>
                          <?php 
                            if (count($records)) {
                              foreach($records as $row) {
                              ?>
                                    <tr>
                                      <td><?php echo $row['date'] ?></td>
                                      <td><?php echo $row['host'] ?></td>
                                      <td><?php echo $row['user'] ?></td>
                                      <td><?php echo $row['operation'] ?></td>
                                      <td><?php echo $row['field'] ?></td>
                                      <td><?php echo $row['old'] ?></td>
                                      <td><?php echo $row['new'] ?></td>
                                    </tr>
                            <?php }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="7" align="center"> <i>No records found!</i></td>
                                </tr>
                          <?php       
                            }
                      	  ?>
                          </tbody>
                        </table>
                      </div>
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>


