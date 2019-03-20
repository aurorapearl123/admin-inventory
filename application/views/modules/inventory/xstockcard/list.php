
<form name="frmFilter" id="frmFilter" method="POST" action="<?php echo $controller_page ?>/show">
    <input type="hidden" id="sortby" name="sortby" value="<?php echo $sortby ?>" />
    <input type="hidden" id="sortorder" name="sortorder" value="<?php echo $sortorder ?>" />
    <div class="subheader">
        <div class="d-flex align-items-center">
            <div class="title mr-auto">
                <h3><i class="icon left la <?php echo $current_module['icon'] ?>"></i> <?php echo $current_module['title'] ?></span></h3>
            </div>
            <?php if ($roles['create']) {?>
            <div class="subheader-tools">
                
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-12">
                <div class="card-box full-body">
                    <div class="card-head">
                        <div class="head-caption">
                            <div class="head-title">
                                <h4 class="head-text"><?php echo $current_module['module_label'] ?> List</h4>
                            </div>
                        </div>
                        <div class="card-head-tools">                           
                            <ul class="tools-list">
                                <li>
                                    <button id="btn-apply" type="submit" class="btn btn-primary btn-xs pill collapse multi-collapse show">Apply Filter</button>
                                </li>
                                <li>
                                    <button type="button" id="btn-filter" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="Filters" onclick="#"><i class="la la-sort-amount-asc"></i></button>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="Print" onclick="popUp('<?php echo $controller_page.'/printlist' ?>', 800, 500)"><i class="la la-print"></i></button>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-outline-light bmd-btn-icon" data-toggle="tooltip" data-placement="bottom" title="Export to Excel File" onclick="window.location='<?php echo $controller_page.'/exportlist' ?>';"><i class="la la-file-excel-o"></i></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="datatables_wrapper">
                            <div class="table-responsive">
                                <table class="table table-striped hover">
                                    <thead align="center">
                                        <tr>
                                            <?php 
        $headers = array(
            array('column_header'=>'STOCK NO.','column_field'=>'itemCode','width'=>'w-10','align'=>''),
            array('column_header'=>'ITEM DESCRIPTION','column_field'=>'name','width'=>'w-20','align'=>''),
            array('column_header'=>'OFFICE / DEPARTMENT','column_field'=>'ancillaryID','width'=>'w-15','align'=>''),
            array('column_header'=>'CATEGORY','column_field'=>'catID','width'=>'w-15','align'=>''),
            array('column_header'=>'EXPIRY','column_field'=>'expiry','width'=>'w-10','align'=>''),
            array('column_header'=>'END BAL','column_field'=>'expiry','width'=>'w-10','align'=>''),
            array('column_header'=>'PRICE','column_field'=>'price','width'=>'w-5','align'=>''),
            array('column_header'=>'STATUS','column_field'=>'status','width'=>'w-10','align'=>''),
        );
        
        echo $this->htmlhelper->tabular_header($headers, $sortby, $sortorder);
        ?>
        
                                        </tr>
                                        <tr id="filter-group" class="collapse multi-collapse table-filter show">
                                            

                                            <th class="form-group form-input">
                                                <input type="text" class="form-control" id="itemCode" name="itemCode" value="<?php echo $itemCode ?>">
                                            </th>
                                            <th class="form-group form-input">
                                                
                                                <select class="form-control" id="name" name="name" style="width: 100px !important;">
                                                    <option value="">&nbsp;</option>
                                                    <?php
                                                    $this->db->select('items.itemID');
                                                    $this->db->select('items.name');
                                                    $this->db->from('items');
                                                    $this->db->join('xstockcards', 'items.itemID=xstockcards.itemID', 'left');
                                                    $this->db->where('xstockcards.expiry !=', '0000-00-00');
                                                    $this->db->where('items.status !=', -100);
                                                    $this->db->group_by('items.itemID');
                                                    $results = $this->db->get()->result();

                                                    foreach ($results as $res) { ?>
                                                        <option value="<?php echo $res->itemID ?>" <?php echo ($res->itemID == $itemID)? "selected" : ""; ?>><?php echo $res->name ?></option>
                                                    <?php } ?>
                                                </select>
                                            </th>
                                            <th class="form-group form-input">
                                                <select class="form-control" id="ancillaryID" name="ancillaryID">
                                                    <option value="">&nbsp;</option>
                                                    <?php
                                                    $this->db->select('ancillaries.ancillaryID');
                                                    $this->db->select('ancillaries.officeName');
                                                    $this->db->from('ancillaries');
                                                    $this->db->join('xstockcards', 'ancillaries.ancillaryID=xstockcards.ancillaryID', 'left');

                                                    if (!$this->session->userdata('current_user')->isAdmin) {
                                                        $ancillaryID = $this->session->userdata('current_user')->ancillaryID;
                                                        $this->db->where('xstockcards.ancillaryID', $ancillaryID);
                                                    }
                                                    $this->db->where('xstockcards.expiry !=', '0000-00-00');
                                                    $this->db->where('ancillaries.status !=', -100);
                                                    $this->db->group_by('ancillaries.ancillaryID');
                                                    $results = $this->db->get()->result();

                                                    foreach ($results as $res) { ?>
                                                        <option value="<?php echo $res->ancillaryID ?>" <?php echo ($res->ancillaryID == $ancillaryID)? "selected" : ""; ?>><?php echo $res->officeName ?></option>
                                                    <?php } ?>
                                                </select>
                                            </th>
                                            <th class="form-group form-input">
                                                <select class="form-control" id="catID" name="catID">
                                                    <option value="">&nbsp;</option>
                                                    <?php
                                                    $this->db->select('category.catID');
                                                    $this->db->select('category.category');
                                                    $this->db->from('items');
                                                    $this->db->join('category', 'items.catID=category.catID', 'left');
                                                    $this->db->join('xstockcards', 'items.itemID=xstockcards.itemID', 'left');
                                                    $this->db->where('xstockcards.expiry !=', '0000-00-00');
                                                    $this->db->where('category.status !=', -100);
                                                    $this->db->where('items.status !=', -100);
                                                    $this->db->group_by('category.catID');
                                                    $results = $this->db->get()->result();

                                                    foreach ($results as $res) { ?>
                                                        <option value="<?php echo $res->catID ?>" <?php echo ($res->catID == $catID)? "selected" : ""; ?>><?php echo $res->category ?></option>
                                                    <?php } ?>
                                                </select>
                                            </th>
                                            <th class="form-group">
                                               <input type="text" class="form-control datepicker" data-toggle="datetimepicker" data-target="#expiry" id="expiry" name="expiry" value="<?php echo ($expiry)? date('M d, Y', strtotime($expiry)) : ""; ?>">

                                            </th>
                                            
                                            <th></th>
                                            <th></th>
                                            
                                            <th class="form-group form-input">
                                                <select class="form-control" id="status" name="status">
                                                    <option value="">&nbsp;</option>
                                                    <option value="1" <?php echo ($status == '1') ? 'selected' : ''?>>Active</option>
                                                    <option value="0" <?php echo ($status == '0') ? 'selected' : ''?>>Inactive</option>
                                                </select>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody align="center">
                                        <?php
                                            if (count($records)) {
                                              foreach($records as $row) {
                                        ?>
                                        <tr onclick="location.href='<?php echo $controller_page.'/view/'.$this->encrypter->encode($row->itemID).'/'.$this->encrypter->encode($row->ancillaryID).'/'.$row->expiry.'/'.trim($row->price); ?>'">
                                             
                                            <td><?php echo $row->itemCode ?></td>
                                            <td align="left"><?php echo $row->name.' '.$row->description ?></td>
                                            <td align="left"><?php echo $row->officeName.'/'.$row->division ?></td>
                                            <td align="left"><?php echo $row->category ?></td>
                                            <td><?php echo date('M Y', strtotime($row->expiry)) ?></td>
                                            <td><?php echo $row->endBal ?></td>
                                            <td align="right"><?php echo number_format($row->price, 2) ?></td>
                                            
                                            <td align="center">
                                                <?php 
                                                    if ($row->status == 1) {
                                                        echo "<span class='badge badge-pill badge-success'>Active</span>";
                                                    } else {
                                                        echo "<span class='badge badge-pill badge-danger'>Inactive</span>";
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php     } //End foreach ?>
                                        <?php } else { ?>
                                        <tr>
                                            <td nowrap="nowrap" colspan="7" align="center"><b><i>No result found.</i></b></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="datatable-footer d-flex">
                                <div class="datatable-pagination">
                                    Pages: &nbsp;&nbsp; 
                                </div>
                                <div class="datatable-pagination">
                                    <?php 
                                        $pagination = $this->pagination->create_links(); 
                                        
                                        if ($pagination) {
                                             echo $pagination;      
                                        } else {
                                            echo "1";
                                        }
                                    ?>
                                </div>
                                <div class="datatable-pager-info float-right ml-auto">
                                    <div class="d-flex">
                                        <div class="datatable-pager-size">
                                            <div class="dataTables_length">
                                                <select aria-controls="table1" class="form-control select-sm pill" tabindex="-98" id="limit" name="limit" onchange="$('#frmFilter').submit();">
                                                    <option value="25" <?php if ($limit==25) echo "selected"; ?>>25</option>
                                                    <option value="50" <?php if ($limit==50) echo "selected"; ?>>50</option>
                                                    <option value="75" <?php if ($limit==75) echo "selected"; ?>>75</option>
                                                    <option value="100" <?php if ($limit==100) echo "selected"; ?>>100</option>
                                                    <option value="125" <?php if ($limit==125) echo "selected"; ?>>125</option>
                                                    <option value="150" <?php if ($limit==150) echo "selected"; ?>>150</option>
                                                    <option value="175" <?php if ($limit==175) echo "selected"; ?>>175</option>
                                                    <option value="200" <?php if ($limit==200) echo "selected"; ?>>200</option>
                                                    <option value="all" <?php if ($limit=='All') echo "selected"; ?>>All</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="datatable-pager-detail">
                                            <div class="dataTables_info">Displaying <?php echo ($offset+1) ?> - <?php if ($offset+$limit < $ttl_rows) { echo ($offset+$limit); } else  { echo $ttl_rows; } ?> of <?php echo number_format($ttl_rows,0)?> records</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

