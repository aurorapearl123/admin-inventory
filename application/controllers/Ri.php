<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
 * Controller Template ver 0.2 
 * To use just search change start change end comments
 * Add your code inside these tags
 */
class Ri extends CI_Controller
{

    var $data;

    var $roles;

    var $table;

    var $pfield;

    var $module;

    var $modules;

    var $logfield;

    var $module_path;

    var $module_label;

    var $controller_page;

    public function __construct()
    {
        parent::__construct();
        //check if you are using $record or $records, just replace all
        $this->load->model('generic_model', 'record');

        //change start
        //parent module, controller, table, pfield, logfield
        $this->_init_setup('Transactions', 'ri', 'riheaders', 'riID', 'riNo');
        //change end
    }

    private function _init_setup($module='',  $controller='', $table='', $pfield='', $logfield='')
    {
        $this->module = $module;
        $this->data['controller_page'] = $this->controller_page = site_url($controller);
        $this->table = $table;
        $this->pfield = $this->data['pfield'] = $pfield; //defines primary key
        $this->logfield = $logfield; // for record logs
        $this->module_path = 'modules/' . strtolower(str_replace(" ", "_", $this->module)) . '/' . $controller; // defines module path
        $this->module_label = ucfirst(str_replace("_", " ", $controller));
        $this->data['controller_name'] = $controller;
        $this->data['table_name'] = $table;
                                                                                                  
        // check for maintenance period
        if ($this->config_model->getConfig('Maintenance Mode') == '1') {
            header('location: ' . site_url('maintenance_mode'));
        }
        
        // check user session
        if (! $this->session->userdata('current_user')->sessionID) {
            header('location: ' . site_url('login'));
        }
    }

    private function submenu()
    {
        // submenu setup
        require_once ('modules.php');
        
        //load each module metadata
        foreach ($modules as $mod) {
            $this->load->view('modules/' . str_replace(" ", "_", strtolower($mod)) . '/metadata');
        }
        
        $this->data['modules'] = $this->modules;
        $this->data['current_main_module'] = $this->modules[$this->module]['main']; // defines the current main module
        //change start
        $this->data['current_module'] = $this->modules[$this->module]['sub']['Ri']; // defines the current sub module
        //change end
                                                                                                  
        // check roles
        $this->check_roles();
        $this->data['roles'] = $this->roles;
    }

    private function check_roles()
    {
        // check roles
        $this->roles['create']  = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Add '.$this->data['current_module']['module_label']);
        $this->roles['view']    = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View '.$this->data['current_module']['module_label']);
        $this->roles['edit']    = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Edit '.$this->data['current_module']['module_label']);
        $this->roles['delete']  = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Delete '.$this->data['current_module']['module_label']);
        $this->roles['confirm']  = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Confirm '.$this->data['current_module']['module_label']);
        $this->roles['cancel']  = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Cancel '.$this->data['current_module']['module_label']);
    }

    private function _in_used($id = 0)
    {
        //change start
        $tables = array(
            'riheaders' => 'riID'
        );
        //change end
        
        if (! empty($tables)) {
            foreach ($tables as $table => $fld) {
                $this->db->where($fld, $id);
                if ($this->db->count_all_results($table)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function index()
    {
        $this->show();
    }

    public function create()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        // check roles
        if ($this->roles['create']) {

            //change start
            $this->frameworkhelper->clear_session_item('ridetails');
            //change end

            // load views
            $this->load->view('header', $data);
            $this->load->view($this->module_path . '/create');
            $this->load->view('footer');
        } else {
            // no access this page
            $data['class'] = "danger";
            $data['msg'] = "Sorry, you don't have access to this page!";
            $data['urlredirect'] = "";
            $this->load->view('header', $data);
            $this->load->view('message');
            $this->load->view('footer');
        }
    }


    // riID    riNo    riDate  riTo    riPlantilla riPosition  riAgency    totalAmount signedBy    signedPosition  purpose createdBy   confirmedBy dateConfirmed   cancelledBy dateCancelled   dateInserted    dateLastEdit    dateDeleted deletedBy   status


    // id  riID    iarID   itemID  qty uprice  amount  dateInserted    dateLastEdit    dateDeleted deletedBy   status
    public function save()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;

        //change start
        $table_fields = array('iarID','riNo', 'riTo', 'riPlantilla', 'riPosition', 'riAgency', 'signedBy', 'signedPosition', 'inspectorID');
        //change end
        
        // check role
        if ($this->roles['create']) {
            $this->record->table = $this->table;
            $this->record->fields = array();
            
            foreach ($table_fields as $fld) {
                $this->record->fields[$fld] = trim($this->input->post($fld));
            }

            //change start
            $this->record->fields['riDate']    = date('Y-m-d', strtotime(trim($this->input->post('riDate'))));
            $this->record->fields['createdBy']    = $this->session->userdata('current_user')->userID;
            $this->record->fields['dateInserted']= date('Y-m-d');
            $this->record->fields['purpose'] = $this->config_model->getConfig('ri_purpose');
            //change end
            
            if ($this->record->save()) {
                $this->record->fields = array();
                //change start
                
                //change end
                $id = $this->record->where[$this->pfield] = $this->db->insert_id();
                $this->record->retrieve();

                //change start
                $sessionSet = 'ridetails';
                //change end
                if (!empty($_SESSION[$sessionSet])) {
                    $batch = array();
                    foreach($_SESSION[$sessionSet] as $item){
                        $info = array();
                        $info[$this->pfield]           = $id;
                        //change start
                        $info['itemID']         = $item['itemID'];                       
                        $info['qty']            = $item['itemQty'];
                        $info['uprice']         = $item['itemPrice'];
                        $info['amount']         = $item['itemAmount'];
                        $info['dateInserted']   = date('Y-m-d H:i:s');
                        $info['status']         = 1;
                        //change end
                        $batch[] = $info;

                    }

                    if (!empty($batch)) {
                        $this->db->insert_batch($sessionSet, $batch);
                    }
                }
                //change end


                
                // record logs
                $pfield = $this->pfield;
                
                $logs = "Record - " .$this->record->field->riNo;
                $this->log_model->table_logs($data['current_module']['module_label'], $this->table, $this->pfield, $this->record->field->$pfield, 'Insert', $logs);
                
                $logfield = $this->pfield;
                
                // success msg
                $data["class"] = "success";
                $data["msg"] = $data['current_module']['module_label'] . " successfully saved.";
                $data["urlredirect"] = $this->controller_page . "/view/" . $this->encrypter->encode($id);
                $this->load->view("header", $data);
                $this->load->view("message");
                $this->load->view("footer");
            } else {
                // error
                $data["class"] = "danger";
                $data["msg"] = "Error in saving the " . $this->data['current_module']['module_label'] . "!";
                ;
                $data["urlredirect"] = "";
                $this->load->view("header", $data);
                $this->load->view("message");
                $this->load->view("footer");
            }
        } else {
            // error
            $data["class"] = "danger";
            $data["msg"] = "Sorry, you don't have access to this page!";
            $data["urlredirect"] = "";
            $this->load->view("header", $data);
            $this->load->view("message");
            $this->load->view("footer");
        }
    }

    public function confirm_record()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        if ($this->roles['confirm']) {
        $status = $this->input->post('status');
        $id = $this->input->post('id');

        if ($status == 2) {
            $this->confirm_ri($id);
        } else if ($status == 0) {
            $this->cancel_ri($id);
        } else {
            echo -1;
        }
        
        } else {
           echo 0;
        }
    }

    private function cancel_ri($id)
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        if ($this->roles['cancel']) {
            $this->db->set('status', 0);
            $this->db->set('cancelledBy', $this->session->userdata('current_user')->userID);        
            $this->db->set('dateCancelled', date('Y-m-d'));
            $this->db->where('riID', $id);   
            $this->db->update('riheaders');
           echo 1;
        } else {
           echo 0;
        }
        

        
    }

    public function confirm_ri($id)
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        if ($this->roles['confirm']) {
        $this->db->set('status', 2);
        $this->db->set('confirmedBy', $this->session->userdata('current_user')->userID);        
        $this->db->set('dateConfirmed', date('Y-m-d'));
        $this->db->where('riID', $id);   
        $this->db->update('riheaders');
        echo 1;
        } else {
           echo 0;
        }
    }

    // public function get_iar_items()
    // {
    //     $response = new stdClass();
    //     $response->status  = 0;
    //     $response->message = '';

    //     $data = array();

    //     $iarID = $this->input->post('iarID');
    //     $itemID = $this->input->post('itemID');

    //     // $this->db->where('iarID', $iarID);
    //     // $this->db->where('status !=', -100);
    //     // $rec = $this->db->get('iarheaders', 1)->row();
        
    //     // $this->db->select('iardetails.itemID');
    //     $this->db->select('iardetails.qty');
    //     $this->db->select('iardetails.uprice');
    //     $this->db->select('items.name as itemName');
    //     // $this->db->select('items.umsr');
    //     $this->db->from('iardetails');
    //     $this->db->join('items', 'iardetails.itemID=items.itemID', 'left');
    //     $this->db->where('iardetails.iarID', $iarID);
    //     $this->db->where('iardetails.itemID', $itemID);
    //     $this->db->where('iardetails.status !=', -100);
    //     $rec = $this->db->get()->row();


    //     $response->status  = 1;
    //     // $response->rec  = $rec;
    //     $response->rec  = $rec;
    //     $response->message = 'success';
    //     echo json_encode($response);
    // }

    
    

    public function edit($id)
    {   
        // load submenu
        $this->submenu();
        $data = $this->data;
        $id = $this->encrypter->decode($id);
        
        if ($this->roles['edit']) {
            //change start
            // for retrieve with joining tables -------------------------------------------------
            // set table
            $this->record->table = $this->table;
            // set fields for the current table
            $this->record->setFields();
            // extend fields - join tables                  
            // $this->record->fields[]  = 'job_titles.jobTitle';
            
            // set joins
            // $this->record->joins[]   = array('job_positions',$this->table.'.jobPositionID=job_positions.jobPositionID','left');
                
            // set where
            $this->record->where[$this->table.'.'.$this->pfield] = $id;
            // execute retrieve
            $this->record->retrieve();
            // ----------------------------------------------------------------------------------
            $data['rec'] = $this->record->field;
            //change end

            //change start 
            $this->db->select('ridetails.itemID');
            $this->db->select('ridetails.qty as itemQty');
            $this->db->select('ridetails.uprice as itemPrice');
            $this->db->select('ridetails.amount as itemAmount');
            $this->db->select('items.name as itemName');
            $this->db->select('items.itemCode');
            $this->db->select('items.description as itemDescription');
            $this->db->select('items.umsr as itemUmsr');
            $this->db->select('iarheaders.drNo');
            $this->db->select('iarheaders.drDate');
            $this->db->select('poheaders.poNo');
            $this->db->select('poheaders.poDate');
            $this->db->select('riheaders.iarID');
            $this->db->from('ridetails');      
            $this->db->join('items', 'ridetails.itemID=items.itemID', 'left');
            $this->db->join('riheaders', 'ridetails.riID=riheaders.riID', 'left');    
            $this->db->join('iarheaders', 'riheaders.iarID=iarheaders.iarID', 'left');     
            $this->db->join('poheaders', 'iarheaders.poID=poheaders.poID', 'left');     
            $this->db->where('ridetails.riID', $id);  
            $this->db->where('ridetails.status !=', -100); 
            $details = $this->db->get()->result_array();


            $this->iar_model->iarID = $data['rec']->iarID;
            $result = $this->iar_model->view_iar();

            $data['iarheaders'] = $result[0];
            //change end

            //Details
            //change start
            $sessionSet = 'ridetails';
            $this->frameworkhelper->clear_session_item($sessionSet);
            if (!empty($details)) {
                foreach ($details as $new_item) {
                    $this->frameworkhelper->add_session_item($sessionSet, $new_item);
                }
            }
            //change end
            
            // load views
            $this->load->view('header', $data);
            $this->load->view($this->module_path . '/edit');
            $this->load->view('footer');
        } else {
            // no access this page
            $data['class'] = "danger";
            $data['msg'] = "Sorry, you don't have access to this page!";
            $data['urlredirect'] = "";
            $this->load->view('header', $data);
            $this->load->view('message');
            $this->load->view('footer');
        }
    }

    public function update()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        $id = $this->encrypter->decode($this->input->post($this->pfield));
        //change start
        $table_fields = array('riNo', 'riTo', 'riPlantilla', 'riPosition', 'riAgency', 'signedBy', 'signedPosition', 'inspectorID');
        //change end
        
        // check roles
        if ($this->roles['edit']) {
            $this->record->table = $this->table;
            $this->record->fields = array();
            
            foreach ($table_fields as $fld) {
                $this->record->fields[$fld] = trim($this->input->post($fld));
            }

            //change start
            $this->record->fields['riDate']    = date('Y-m-d', strtotime(trim($this->input->post('riDate'))));
            $this->record->fields['dateLastEdit']= date('Y-m-d');
            //change end
            
            $this->record->pfield = $this->pfield;
            $this->record->pk = $id;
            
            // field logs here
            $wasChange = $this->log_model->field_logs($data['current_module']['module_label'], $this->table, $this->pfield, $id, 'Update', $this->record->fields);
            
            if ($this->record->update()) {
                //change start
                //change end

                // record logs
                if ($wasChange) {
                    $logs = "Record - " . trim($this->input->post($this->logfield));
                    $this->log_model->table_logs($data['current_module']['module_label'], $this->table, $this->pfield, $this->record->pk, 'Update', $logs);
                }
                
                // successful
                $data["class"] = "success";
                $data["msg"] = $this->data['current_module']['module_label'] . " successfully updated.";
                $data["urlredirect"] = $this->controller_page . "/view/" . $this->encrypter->encode($id);
                $this->load->view("header", $data);
                $this->load->view("message");
                $this->load->view("footer");
            } else {
                // error
                $data["class"] = "danger";
                $data["msg"] = "Error in updating the " . $this->data['current_module']['module_label'] . "!";
                $data["urlredirect"] = $this->controller_page . "/view/" . $this->record->pk;
                $this->load->view("header", $data);
                $this->load->view("message");
                $this->load->view("footer");
            }
        } else {
            // error
            $data["class"] = "danger";
            $data["msg"] = "Sorry, you don't have access to this page!";
            $data["urlredirect"] = "";
            $this->load->view("header", $data);
            $this->load->view("message");
            $this->load->view("footer");
        }
    }

    public function delete($id)
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        $id = $this->encrypter->decode($id);
        
        // check roles
        if ($this->roles['delete']) {
            // set fields
            $this->record->fields = array();
            // set table
            $this->record->table = $this->table;
            // set where
            $this->record->where[$this->pfield] = $id;
            // execute retrieve
            $this->record->retrieve();
            
            if (! empty($this->record->field)) {
                $this->record->pfield = $this->pfield;
                $this->record->pk = $id;
                
                // check if in used
                // if (! $this->_in_used($id)) {
                    if ($this->record->delete()) {
                        $logfield = $this->logfield;

                        //change start
                        //change end
                        
                        // record logs
                        $logs = "Record - " . $this->record->field->$logfield;
                        $this->log_model->table_logs($this->data['current_module']['module_label'], $this->table, $this->pfield, $this->record->pk, 'Delete', $logs);
                        
                        // successful
                        $data["class"] = "success";
                        $data["msg"] = $this->data['current_module']['module_label'] . " successfully deleted.";
                        $data["urlredirect"] = $this->controller_page . "/";
                        $this->load->view("header", $data);
                        $this->load->view("message");
                        $this->load->view("footer");
                    // } else {
                    //     // error
                    //     $data["class"] = "danger";
                    //     $data["msg"] = "Error in deleting the " . $this->data['current_module']['module_label'] . "!";
                    //     $data["urlredirect"] = "";
                    //     $this->load->view("header", $data);
                    //     $this->load->view("message");
                    //     $this->load->view("footer");
                    // }
                } else {
                    // error
                    $data["class"] = "danger";
                    $data["msg"] = "Data integrity constraints.";
                    $data["urlredirect"] = "";
                    $this->load->view("header", $data);
                    $this->load->view("message");
                    $this->load->view("footer");
                }
            } else {
                // error
                $data["class"] = "danger";
                $data["msg"] = $this->data['current_module']['module_label'] . " record not found!";
                $data["urlredirect"] = "";
                $this->load->view("header", $data);
                $this->load->view("message");
                $this->load->view("footer");
            }
        } else {
            // error
            $data["class"] = "danger";
            $data["msg"] = "Sorry, you don't have access to this page!";
            $data["urlredirect"] = "";
            $this->load->view("header", $data);
            $this->load->view("message");
            $this->load->view("footer");
        }
    }

    public function view($id)
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        $id = $this->encrypter->decode($id);
        
        if ($this->roles['view']) {
            //change start
            // for retrieve with joining tables -------------------------------------------------
            // set table
            $this->record->table = $this->table;            
            // set fields for the current table
            $this->record->setFields();         
                               
            // set where
            $this->record->where[$this->table.'.'.$this->pfield] = $id;
            
            // execute retrieve
            $this->record->retrieve();
            // ----------------------------------------------------------------------------------
            $data['rec'] = $this->record->field;
            //change end

            //change start 
            $this->db->select('ridetails.itemID');
            $this->db->select('ridetails.qty as itemQty');
            $this->db->select('ridetails.uprice as itemPrice');
            $this->db->select('ridetails.amount as itemAmount');
            $this->db->select('items.name as itemName');
            $this->db->select('items.itemCode');
            $this->db->select('items.description as itemDescription');
            $this->db->select('items.umsr as itemUmsr');
            $this->db->select('iarheaders.drNo');
            $this->db->select('iarheaders.drDate');
            $this->db->select('poheaders.poNo');
            $this->db->select('poheaders.poDate');
            $this->db->select('riheaders.iarID');
            $this->db->from('ridetails');      
            $this->db->join('items', 'ridetails.itemID=items.itemID', 'left');
            $this->db->join('riheaders', 'ridetails.riID=riheaders.riID', 'left');    
            $this->db->join('iarheaders', 'riheaders.iarID=iarheaders.iarID', 'left');     
            $this->db->join('poheaders', 'iarheaders.poID=poheaders.poID', 'left');     
            $this->db->where('ridetails.riID', $id);  
            $this->db->where('ridetails.status !=', -100); 
            $details = $this->db->get()->result_array();


            $this->iar_model->iarID = $data['rec']->iarID;
            $result = $this->iar_model->view_iar();

            $data['iarheaders'] = $result[0];
            $data['inspector'] = $this->getInspector($data['rec']->inspectorID);
            //change end

            //Details
            //change start
            $sessionSet = 'ridetails';
            $this->frameworkhelper->clear_session_item($sessionSet);
            if (!empty($details)) {
                foreach ($details as $new_item) {
                    $this->frameworkhelper->add_session_item($sessionSet, $new_item);
                }
            }
            //change end

            
            $data['in_used'] = $this->_in_used($id);
            // record logs
            $pfield = $this->pfield;
            if ($this->config_model->getConfig('Log all record views') == '1') {
                $logs = "Record - " . $this->record->field->name;
                $this->log_model->table_logs($this->module, $this->table, $this->pfield, $this->record->field->$pfield, 'View', $logs);
            }
            
            // load views
            $this->load->view('header', $data);
            $this->load->view($this->module_path . '/view');
            $this->load->view('footer');
        } else {
            // no access this page
            $data['class'] = "danger";
            $data['msg'] = "Sorry, you don't have access to this page!";
            $data['urlredirect'] = "";
            $this->load->view('header', $data);
            $this->load->view('message');
            $this->load->view('footer');
        }
    }

    private function view_iar()
    {

        $this->db->select('riheaders.*');
        $this->db->select('poheaders.poNo');
        $this->db->select('poheaders.poDate');
        $this->db->from('iarheaders');  
        $this->db->join('poheaders', 'iarheaders.poID=poheaders.poID', 'left');
        $this->db->where('iarheaders.iarID', $this->iarID);     
        $this->db->where('iarheaders.status !=', -100);     
        $rec = $this->db->get()->row();

        $this->db->select('iardetails.itemID');
        $this->db->select('iardetails.qty as itemQty');
        $this->db->select('iardetails.uprice as itemPrice');
        $this->db->select('iardetails.amount as itemAmount');
        $this->db->select('items.name as itemName');
        $this->db->select('items.itemCode');
        $this->db->select('items.description as itemDescription');
        $this->db->select('items.umsr as itemUmsr');
        $this->db->from('iardetails');  
        $this->db->join('items', 'iardetails.itemID=items.itemID', 'left');     
        $this->db->where('iardetails.iarID', $this->iarID);     
        $this->db->where('iardetails.status !=', -100);     
        $records = $this->db->get()->result_array();

        return array($rec, $records);
    }

    public function show()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;

        //=========================
        //change start
        $condition_fields = array(
            array('variable'=>'riNo', 'field'=>$this->table.'.riNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'riTo', 'field'=>$this->table.'.riTo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'riAgency', 'field'=>$this->table.'.riAgency', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'iarNo', 'field'=>$this->table.'.iarNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'poNo', 'field'=>$this->table.'.poNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'requisitioningOffice', 'field'=>$this->table.'.requisitioningOffice', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
        );
        
        // sorting fields
        $sorting_fields = array('riNo'=>'desc');
        //change end
        //=========================


        
        $controller = $this->uri->segment(1);
        //don't use segment(4) since you are not using pageType
        if ($this->uri->segment(3))
            $offset = $this->uri->segment(3);
        else
            $offset = 0;
        
        // source of filtering
        $filter_source = 0; // default/blank
        if ($this->input->post('filterflag') || $this->input->post('sortby')) {
            $filter_source = 1;
        } else {
            foreach ($condition_fields as $key) {
                if ($this->input->post($key['variable'])) {
                    $filter_source = 1; // form filters
                    break;
                }
            }
        }
        
        if (! $filter_source) {
            foreach ($condition_fields as $key) {
                if ($this->session->userdata($controller . '_' . $key['variable']) || $this->session->userdata($controller . '_sortby') || $this->session->userdata($controller . '_sortorder')) {
                    $filter_source = 2; // session
                    break;
                }
            }
        }
        
        switch ($filter_source) {
            case 1:
                foreach ($condition_fields as $key) {
                    $$key['variable'] = trim($this->input->post($key['variable']));
                }
                
                $sortby = trim($this->input->post('sortby'));
                $sortorder = trim($this->input->post('sortorder'));
                
                break;
            case 2:
                foreach ($condition_fields as $key) {
                    $$key['variable'] = $this->session->userdata($controller . '_' . $key['variable']);
                }
                
                $sortby = $this->session->userdata($controller . '_sortby');
                $sortorder = $this->session->userdata($controller . '_sortorder');
                break;
            default:
                foreach ($condition_fields as $key) {
                    $$key['variable'] = $key['default_value'];
                }
                $sortby = "";
                $sortorder = "";
        }
        
        if ($this->input->post('limit')) {
            if ($this->input->post('limit') == "All")
                $limit = "";
            else
                $limit = $this->input->post('limit');
        } else if ($this->session->userdata($controller . '_limit')) {
            $limit = $this->session->userdata($controller . '_limit');
        } else {
            $limit = 25; // default limit
        }
        
        // set session variables
        foreach ($condition_fields as $key) {
            $this->session->set_userdata($controller . '_' . $key['variable'], $$key['variable']);
        }

        //change start
        $this->session->set_userdata($controller.'_datePosted', $datePosted);
        //change end
        $this->session->set_userdata($controller . '_sortby', $sortby);
        $this->session->set_userdata($controller . '_sortorder', $sortorder);
        $this->session->set_userdata($controller . '_limit', $limit);
        
        // assign data variables for views
        foreach ($condition_fields as $key) {
            //use value as variable name
            $data[$key['variable']] = $$key['variable'];
        }




        // First Query for Pagination config
        //===================================
        //change start
        $data['datePosted'] = $datePosted;
        
        // select
        $this->db->select($this->table.'.*');
        $this->db->select('iarheaders.iarNo');
        $this->db->select('iarheaders.drNo');
        $this->db->select('iarheaders.invoiceNo');
        $this->db->select('iarheaders.requisitioningOffice');
        $this->db->select('poheaders.poNo');
        $this->db->select('poheaders.poDate');

        // from
        $this->db->from($this->table);

        // join     
        $this->db->join('iarheaders', 'riheaders.iarID=iarheaders.iarID', 'left');
        $this->db->join('poheaders', 'iarheaders.poID=poheaders.poID', 'left');
    
        // where
        $this->db->where('riheaders.status !=', -100);
        $this->db->where('iarheaders.status !=', -100);
        //change end
        //===================================
        



        // set conditions here
        foreach ($condition_fields as $key) {
            $operators = explode('_', $key['operator']);
            $operator = $operators[0];
            // check if the operator is like
            if (count($operators) > 1) {
                // like operator
                if (trim($$key['variable']) != '' && $key['field'])
                    $this->db->$operator($key['field'], $$key['variable'], $operators[1]);
            } else {
                if (trim($$key['variable']) != '' && $key['field'])
                    $this->db->$operator($key['field'], $$key['variable']);
            }
        }
        
        // get
        $data['ttl_rows'] = $config['total_rows'] = $this->db->count_all_results();
        
        // set pagination
        $config['base_url'] = $this->controller_page . '/show/';
        $config['per_page'] = $limit;
        $this->pagination->initialize($config);





        // Second Query for retrieving data
        //===================================
        
        // select
        $this->db->select($this->table.'.*');
        $this->db->select('iarheaders.iarNo');
        $this->db->select('iarheaders.drNo');
        $this->db->select('iarheaders.invoiceNo');
        $this->db->select('iarheaders.requisitioningOffice');
        $this->db->select('poheaders.poNo');
        $this->db->select('poheaders.poDate');

        // from
        $this->db->from($this->table);

        // join     
        $this->db->join('iarheaders', 'riheaders.iarID=iarheaders.iarID', 'left');
        $this->db->join('poheaders', 'iarheaders.poID=poheaders.poID', 'left');
    
        // where
        $this->db->where('riheaders.status !=', -100);
        $this->db->where('iarheaders.status !=', -100);
        //change end
        //===================================
        




        // set conditions here
        foreach ($condition_fields as $key) {
            $operators = explode('_', $key['operator']);
            $operator = $operators[0];
            // check if the operator is like
            if (count($operators) > 1) {
                // like operator
                if (trim($$key['variable']) != '' && $key['field'])
                    $this->db->$operator($key['field'], $$key['variable'], $operators[1]);
            } else {
                if (trim($$key['variable']) != '' && $key['field'])
                    $this->db->$operator($key['field'], $$key['variable']);
            }
        }
        
        if ($sortby && $sortorder) {
            $this->db->order_by($sortby, $sortorder);
            
            if (! empty($sorting_fields)) {
                foreach ($sorting_fields as $fld => $s_order) {
                    if ($fld != $sortby) {
                        $this->db->order_by($fld, $s_order);
                    }
                }
            }
        } else {
            $ctr = 1;
            if (! empty($sorting_fields)) {
                foreach ($sorting_fields as $fld => $s_order) {
                    if ($ctr == 1) {
                        $sortby = $fld;
                        $sortorder = $s_order;
                    }
                    $this->db->order_by($fld, $s_order);
                    
                    $ctr ++;
                }
            }
        }
        
        if ($limit) {
            if ($offset) {
                $this->db->limit($limit, $offset);
            } else {
                $this->db->limit($limit);
            }
        }
        
        // assigning variables
        $data['sortby'] = $sortby;
        $data['sortorder'] = $sortorder;
        $data['limit'] = $limit;
        $data['offset'] = $offset;
        
        // get
        $data['records'] = $this->db->get()->result();
        
        // load views
        $this->load->view('header', $data);
        $this->load->view($this->module_path . '/list');
        $this->load->view('footer');
    }

    public function printlist()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;

        //=========================
        //change start
        $condition_fields = array(
            array('variable'=>'riNo', 'field'=>$this->table.'.riNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'riTo', 'field'=>$this->table.'.riTo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'riAgency', 'field'=>$this->table.'.riAgency', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'iarNo', 'field'=>$this->table.'.iarNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'poNo', 'field'=>$this->table.'.poNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'requisitioningOffice', 'field'=>$this->table.'.requisitioningOffice', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
        );
        
        // sorting fields
        $sorting_fields = array('riNo'=>'desc');
        //change end
        //=========================
        
        $controller = $this->uri->segment(1); // if print per page

        // uncomment if want to print with no offset
        // if ($this->uri->segment(3))
        //  $offset = $this->uri->segment(3);
        // else
        //  $offset = 0;
        
        foreach ($condition_fields as $key) {
            $$key['variable'] = $this->session->userdata($controller . '_' . $key['variable']);
        }
        
        //change start
        $datePosted = $this->session->userdata($controller.'_datePosted');
        //change end
        $limit = $this->session->userdata($controller . '_limit');
        $offset = $this->session->userdata($controller . '_offset');
        $sortby = $this->session->userdata($controller . '_sortby');
        $sortorder = $this->session->userdata($controller . '_sortorder');
        


        //===================================
        //change start
        $data['datePosted'] = $datePosted;
        
        // select
        $this->db->select($this->table.'.*');
        $this->db->select('iarheaders.iarNo');
        $this->db->select('iarheaders.drNo');
        $this->db->select('iarheaders.invoiceNo');
        $this->db->select('iarheaders.requisitioningOffice');
        $this->db->select('poheaders.poNo');
        $this->db->select('poheaders.poDate');

        // from
        $this->db->from($this->table);

        // join     
        $this->db->join('iarheaders', 'riheaders.iarID=iarheaders.iarID', 'left');
        $this->db->join('poheaders', 'iarheaders.poID=poheaders.poID', 'left');
    
        // where
        $this->db->where('riheaders.status !=', -100);
        $this->db->where('iarheaders.status !=', -100);
        //change end
        //===================================


        
        // set conditions here
        foreach ($condition_fields as $key) {
            $operators = explode('_', $key['operator']);
            $operator = $operators[0];
            // check if the operator is like
            if (count($operators) > 1) {
                // like operator
                if (trim($$key['variable']) != '' && $key['field'])
                    $this->db->$operator($key['field'], $$key['variable'], $operators[1]);
            } else {
                if (trim($$key['variable']) != '' && $key['field'])
                    $this->db->$operator($key['field'], $$key['variable']);
            }
        }
        
        if ($sortby && $sortorder) {
            $this->db->order_by($sortby, $sortorder);
            
            if (! empty($sorting_fields)) {
                foreach ($sorting_fields as $fld => $s_order) {
                    if ($fld != $sortby) {
                        $this->db->order_by($fld, $s_order);
                    }
                }
            }
        } else {
            $ctr = 1;
            if (! empty($sorting_fields)) {
                foreach ($sorting_fields as $fld => $s_order) {
                    if ($ctr == 1) {
                        $sortby = $fld;
                        $sortorder = $s_order;
                    }
                    $this->db->order_by($fld, $s_order);
                    
                    $ctr ++;
                }
            }
        }
        
        if ($limit) {
            if ($offset) {
                $this->db->limit($limit, $offset);
            } else {
                $this->db->limit($limit);
            }
        }
        
        // assigning variables
        $data['sortby'] = $sortby;
        $data['sortorder'] = $sortorder;
        $data['limit'] = $limit;
        $data['offset'] = $offset;
        
        // get
        $data['records'] = $this->db->get()->result();
        
        $data['title'] = $this->module_label." List";
        
        // load views
        $this->load->view('header_print', $data);
        $this->load->view($this->module_path . '/printlist');
        $this->load->view('footer_print');
    }

    public function exportlist()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;

        //=========================
        //change start
        $condition_fields = array(
            array('variable'=>'riNo', 'field'=>$this->table.'.riNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'riTo', 'field'=>$this->table.'.riTo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'riAgency', 'field'=>$this->table.'.riAgency', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'iarNo', 'field'=>$this->table.'.iarNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'poNo', 'field'=>$this->table.'.poNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'requisitioningOffice', 'field'=>$this->table.'.requisitioningOffice', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
        );
        
        // sorting fields
        $sorting_fields = array('riNo'=>'desc');
        //change end
        //=========================
        
        $controller = $this->uri->segment(1); // if print per page

        // uncomment if want to print with no offset
        // if ($this->uri->segment(3))
        //  $offset = $this->uri->segment(3);
        // else
        //  $offset = 0;
        
        foreach ($condition_fields as $key) {
            $$key['variable'] = $this->session->userdata($controller . '_' . $key['variable']);
        }
        
        //change start
        $datePosted = $this->session->userdata($controller.'_datePosted');
        //change end
        $limit = $this->session->userdata($controller . '_limit');
        $offset = $this->session->userdata($controller . '_offset');
        $sortby = $this->session->userdata($controller . '_sortby');
        $sortorder = $this->session->userdata($controller . '_sortorder');
        


        //===================================
        //change start
        $data['datePosted'] = $datePosted;
        
        // select
        $this->db->select($this->table.'.*');
        $this->db->select('iarheaders.iarNo');
        $this->db->select('iarheaders.drNo');
        $this->db->select('iarheaders.invoiceNo');
        $this->db->select('iarheaders.requisitioningOffice');
        $this->db->select('poheaders.poNo');
        $this->db->select('poheaders.poDate');

        // from
        $this->db->from($this->table);

        // join     
        $this->db->join('iarheaders', 'riheaders.iarID=iarheaders.iarID', 'left');
        $this->db->join('poheaders', 'iarheaders.poID=poheaders.poID', 'left');
    
        // where
        $this->db->where('riheaders.status !=', -100);
        $this->db->where('iarheaders.status !=', -100);
        //change end
        //===================================

        
        // set conditions here
        foreach ($condition_fields as $key) {
            $operators = explode('_', $key['operator']);
            $operator = $operators[0];
            // check if the operator is like
            if (count($operators) > 1) {
                // like operator
                if (trim($$key['variable']) != '' && $key['field'])
                    $this->db->$operator($key['field'], $$key['variable'], $operators[1]);
            } else {
                if (trim($$key['variable']) != '' && $key['field'])
                    $this->db->$operator($key['field'], $$key['variable']);
            }
        }
        
        if ($sortby && $sortorder) {
            $this->db->order_by($sortby, $sortorder);
            
            if (! empty($sorting_fields)) {
                foreach ($sorting_fields as $fld => $s_order) {
                    if ($fld != $sortby) {
                        $this->db->order_by($fld, $s_order);
                    }
                }
            }
        } else {
            $ctr = 1;
            if (! empty($sorting_fields)) {
                foreach ($sorting_fields as $fld => $s_order) {
                    if ($ctr == 1) {
                        $sortby = $fld;
                        $sortorder = $s_order;
                    }
                    $this->db->order_by($fld, $s_order);
                    
                    $ctr ++;
                }
            }
        }
        
        if ($limit) {
            if ($offset) {
                $this->db->limit($limit, $offset);
            } else {
                $this->db->limit($limit);
            }
        }
        
        // assigning variables
        $data['sortby'] = $sortby;
        $data['sortorder'] = $sortorder;
        $data['limit'] = $limit;
        $data['offset'] = $offset;
        
        // get
        $records = $this->db->get()->result();
        $title = $this->module_label." List";
        $companyName = $this->config_model->getConfig('Company');
        $address = $this->config_model->getConfig('Address');
        
        // XML Blurb
        $data = "<?xml version='1.0'?>
            
            <?mso-application progid='Excel.Sheet'?>
            
            <Workbook xmlns='urn:schemas-microsoft-com:office:spreadsheet' xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:excel' xmlns:ss='urn:schemas-microsoft-com:office:spreadsheet' xmlns:html='http://www.w3.org/TR/REC-html40'>
            <Styles>
            <Style ss:ID='s20'>
                <Alignment ss:Horizontal='Center' ss:Vertical='Bottom'/>
              <Font ss:Bold='1' ss:Size='14'/>
            </Style>
            
            <Style ss:ID='s23'>
              <Font ss:Bold='1'/>
            <Borders>
                    <Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/>
                   </Borders>
            </Style>
            
            <Style ss:ID='s24'>
                <Alignment ss:Horizontal='Center' ss:Vertical='Bottom'/>
                <Font ss:Bold='1'/>
            </Style>
            
            <Style ss:ID='s24A'>
                <Alignment ss:Horizontal='Center' ss:Vertical='Bottom'/>
            </Style>
            
            <Style ss:ID='s24B'>
                <Alignment ss:Horizontal='Center' ss:Vertical='Bottom'/>
               <Borders>
                    <Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/>
                   </Borders>
            </Style>
            
            <Style ss:ID='s25'>
                <Alignment ss:Horizontal='Right' ss:Vertical='Bottom'/>
                <NumberFormat ss:Format='#,##0.00_ ;[Red]\-#,##0.00\ '/>
                <Font ss:Bold='1'/>
            </Style>
            <Style ss:ID='s26'>
                <Alignment ss:Horizontal='Right' ss:Vertical='Bottom'/>
                <NumberFormat ss:Format='#,##0.00_ ;[Red]\-#,##0.00\ '/>
               <Borders>
                    <Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/>
                   </Borders>
            </Style>
            <Style ss:ID='s27'>
                  <Borders>
                    <Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/>
                   </Borders>
            </Style>
            </Styles>
            
            <Worksheet ss:Name='" . $title . "'>
                
            <Table>
            <Column ss:Index='1' ss:AutoFitWidth=\"1\" ss:Width='25.00'/>
            <Column ss:Index='2' ss:AutoFitWidth=\"1\" ss:Width='120.00'/>
            <Column ss:Index='3' ss:AutoFitWidth=\"1\" ss:Width='150.00'/>
            <Column ss:Index='4' ss:AutoFitWidth=\"1\" ss:Width='150.00'/>
            <Column ss:Index='5' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
            <Column ss:Index='6' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
            <Column ss:Index='7' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
                ";
        
        // header
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='6'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s20'>";
        $data .= "<Cell ss:MergeAcross='6'><Data ss:Type='String'>" . $companyName . "</Data></Cell>";
        $data .= "</Row>";
        $data .= "<Row ss:StyleID='s24A'>";
        $data .= "<Cell ss:MergeAcross='6'><Data ss:Type='String'>" . $address . "</Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='6'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='6'><Data ss:Type='String'>" . strtoupper($title) . "</Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='6'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        
        $fields[] = "  ";
        $fields[] = "RI NO.";
        $fields[] = "TO";
        $fields[] = "AGENCY";
        $fields[] = "IAR NO.";
        $fields[] = "PO NO.";
        $fields[] = "STATUS";

        
        $data .= "<Row ss:StyleID='s24'>";
        // Field Name Data
        foreach ($fields as $fld) {
            $data .= "<Cell ss:StyleID='s23'><Data ss:Type='String'>$fld</Data></Cell>";
        }
        $data .= "</Row>";
        
        if (count($records)) {
            $ctr = 1;
            foreach ($records as $row) {
                $data .= "<Row>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $ctr . ".</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->riNo . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->riTo . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->riAgency . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->iarNo . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->poNo . "</Data></Cell>";

                if ($row->status == 1) {
                    $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>Pending</Data></Cell>"; 
                } else if ($row->status == 2) {
                    $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>Confirmed</Data></Cell>"; 
                } else {
                    $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>Cancelled</Data></Cell>"; 
                }
                
                
                $data .= "</Row>";
                
                $ctr ++;
            }
        }
        $data .= "</Table></Worksheet>";
        $data .= "</Workbook>";

        // Final XML Blurb
        $filename = $this->data['controller_name'].'_list';
        
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$filename.xls;");
        header("Content-Type: application/ms-excel");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        echo $data;
    }

    // Conditions and fields changes
    // public function check_duplicate()
    // {
    //     //change start
    //     $this->db->where('groupName', trim($this->input->post('groupName')));
    //     //change end
        
    //     if ($this->db->count_all_results($this->table))
    //         echo "1"; // duplicate
    //     else
    //         echo "0";
    // }




    //============================================
    // Module Specific Functions
    //============================================
    private function _generateID()
    {
        $idSeries   = $this->config_model->getConfig('Job Post Series');
        $idLength   = $this->config_model->getConfig('Job Post Series Length');
    
        $id  = str_pad($idSeries, $idLength, "0", STR_PAD_LEFT);
        return "JP".date('y').$id;
    }   
    
    public function getPositionDuties()
    {
        $jobPositionID = trim($this->input->post('jobPositionID'));
        
        if ($jobPositionID) {
            $this->db->where('jobPositionID', $jobPositionID);
        }
        $this->db->order_by('duty','asc');
        $records = $this->db->get('position_duties');
        echo $this->frameworkhelper->get_json_data($records, 'jobPostID', 'duty');
    }   
    
    public function display_session()
    {               
        echo var_dump($_SESSION);
    }








    //Session Item Functions
    public function display_session_items($display_area='')
    {
       
        $sessionSet = $this->input->post('sessionSet');
        $records = isset($_SESSION[$sessionSet])? $_SESSION[$sessionSet]:array();

        
        $headers = array(
            array('column_header'=>'','column_field'=>'','width'=>'w-5','align'=>''),
            array('column_header'=>'Stock No','column_field'=>'','width'=>'w-10','align'=>''),
            array('column_header'=>'Description','column_field'=>'','width'=>'w-45','align'=>'left'),
            array('column_header'=>'Quantity','column_field'=>'','width'=>'w-10','align'=>''),
            array('column_header'=>'Unit','column_field'=>'','width'=>'w-10','align'=>''),
            array('column_header'=>'Unit Price','column_field'=>'','width'=>'w-10','align'=>'right'),
            array('column_header'=>'Amount','column_field'=>'','width'=>'w-10','align'=>'right'),
          
        );
        //display the value of session key
        $display = array(
            array('align'=>'center','field'=>'itemCode'),
            array('align'=>'left','field'=>'itemName'),
            array('align'=>'center','field'=>'itemQty'),
            array('align'=>'center','field'=>'itemUmsr'),
            array('align'=>'right','field'=>'itemPrice'),
            array('align'=>'right','field'=>'itemAmount'),

          
        );
        echo $this->_tm_session_tabular_view($records,$headers,$display,$sessionSet,'950',$display_area);
    }

    private function _tm_session_tabular_view($records, $headers, $display, $sessionID, $width="100%",$display_area='',$default_rows=9,$callback="")
    {
        $total_amount = 0;
        $colspan = count($headers);
        $view = '<table class="table hover" id="po-details-table">'."\n";

        //thead
        $view .= '<thead class="thead-light" align="center">'."\n";
        if (!empty($headers)) {
            foreach($headers as $col) {
                if ($col['column_field'] == $sortby) {
                    if ($sortorder=="DESC") {
                        $view .= "\n".'<th class="'.$col['width'].'" nowrap>';
                    } else {
                        $view .= "\n".'<th class="'.$col['width'].'" nowrap>';
                    }
                } else {
                    $view .= "\n".'<th class="'.$col['width'].'" nowrap>';
                }
                $view .= "\n".'<div align="'.$col['align'].'">';
                
                $view .= $col['column_header'];
                $view .= "\n".'</div>';
                $view .= '</th>';
            }
        }
        $view .= '</thead>'."\n";


        //tbody
        $view .= '<tbody>'."\n";

 
        if (!empty($_SESSION[$sessionID])) {
            foreach($_SESSION[$sessionID] as $id=>$item) {
            $view .= '<tr colspan="'.$colspan.'">'."\n";
            $view .= '<td>
                    <i style="font-size: 24px; color: #14699e!important;"class="la la-trash-o item" alt="Delete" title="Delete Row" style="cursor: pointer;" onclick="delete_session_item(\''.$sessionID.'\',\''.$id.'\',\''.$display_area.'\',\''.$callback.'\');"></i>
                    </td>'."\n";
                    
            if(!empty($display)) {
                foreach($display as $td) {
                    $text = $td['field'];
                    if($text == "itemAmount") {
                        $total_amount += $item[$text];
                        $view .= '<td align="'.$td['align'].'" nowrap>'.$item[$text].'</td>'."\n";  
                    } else if($text == "itemName") {
                        $view .= '<td align="'.$td['align'].'" nowrap>'.$item['itemName'].' - '.$item['itemDescription'].'</td>'."\n";

                    } else {
                        $view .= '<td align="'.$td['align'].'" nowrap>'.$item[$text].'</td>'."\n";  
                    }
                    
                }
            }
            $view .= '</tr>';
            }
        }           
        

        if (!empty($headers)) {
            $view .= '</tbody>'."\n";
            $view .= '<tfoot>'."\n";
            $view .= '<tr class="bg-light">'."\n";
            for ($i = 0; $i < (count($headers) - 3); $i++) {
               $view .= '<td></td>'."\n"; 
            }
            //additional footer td
            $view .= '<td><input type="hidden" value="'.$total_amount.'" name="totalAmount" id="totalAmount"></td>'."\n";
            $view .= '<td class="font-weight-bold text-right"><span>TOTAL: </span></td>'."\n";
            $view .= '<td class="font-weight-bold" align="right"><span>₱ '.number_format($total_amount,2).'</span></td>'."\n";
            //additional footer td
            $view .= '</tr>'."\n";
            $view .= '</tfoot>'."\n";
            $view .= '</table>'."\n";
        }      
        
        return $view;
    }


    public function print_ri($id)
    {

        // load submenu
        $this->submenu();
        $data = $this->data;
        $id = $this->encrypter->decode($id);
        
        if ($this->roles['view']) {
            //change start
            // for retrieve with joining tables -------------------------------------------------
            // set table
            $this->record->table = $this->table;            
            // set fields for the current table
            $this->record->setFields();         
                               
            // set where
            $this->record->where[$this->table.'.'.$this->pfield] = $id;
            
            // execute retrieve
            $this->record->retrieve();
            // ----------------------------------------------------------------------------------
            $data['rec'] = $this->record->field;
            //change end



            //change start 
            $this->db->select('ridetails.itemID');
            $this->db->select('ridetails.qty as itemQty');
            $this->db->select('ridetails.uprice as itemPrice');
            $this->db->select('ridetails.amount as itemAmount');
            $this->db->select('items.name as itemName');
            $this->db->select('items.itemCode');
            $this->db->select('items.description as itemDescription');
            $this->db->select('items.umsr as itemUmsr');
            $this->db->select('iarheaders.drNo');
            $this->db->select('iarheaders.drDate');
            $this->db->select('poheaders.poNo');
            $this->db->select('poheaders.poDate');
            $this->db->select('riheaders.iarID');
            $this->db->from('ridetails');      
            $this->db->join('items', 'ridetails.itemID=items.itemID', 'left');
            $this->db->join('riheaders', 'ridetails.riID=riheaders.riID', 'left');    
            $this->db->join('iarheaders', 'riheaders.iarID=iarheaders.iarID', 'left');     
            $this->db->join('poheaders', 'iarheaders.poID=poheaders.poID', 'left');     
            $this->db->where('ridetails.riID', $id);  
            $this->db->where('ridetails.status !=', -100); 
            $data['records'] = $this->db->get()->result_array();


            $this->iar_model->iarID = $data['rec']->iarID;
            $result = $this->iar_model->view_iar();

            $data['officer'] = $this->getSignatory('1004');
            $data['inspector'] = $this->getInspector($data['rec']->inspectorID);
            

            // $data['rec'] = $result[0];
            //change end

            //Details
            
            
            $data['pdf_paging'] = TRUE;                     
            $data['title']      = "REQUEST FOR INSPECTION";
            $data['footer_left']      = "APRIL 12,2016_REV.0";
            $data['footer_right']      = "DJRMH-HPS-MM-F-015";
            // $data['modulename'] = "NOTICE TO CREDIT";
            // $data['subnote'] = "AAA";
            // $data['subnote2']   = $payroll->startDate.' - '.$payroll->endDate;
            // $data['subnote3'] = $payroll->payrollPeriod;

                // load pdf class
            $this->load->library('mpdf');
                // load pdf class
            $this->mpdf->mpdf('en-GB',array(215.9,330.2),10,'Calibri, sans-serif',10,10,40,10,10,10,'P'); //default
            // $this->mpdf->mpdf('en-GB',array(215.9,330.2),10,'Calibri, sans-serif',20,20,30,20,10,0,'P'); //Left,Right,Body and header margin,?,Top,?
            $this->mpdf->setTitle($data['title']);
            $this->mpdf->SetDisplayMode('fullpage');
            $this->mpdf->shrink_tables_to_fit = 1;
            // $this->mpdf->SetWatermarkImage(base_url().'assets/img/main/logo.png');
            $this->mpdf->watermark_font = 'DejaVuSansCondensed';
            $this->mpdf->watermarkImageAlpha = 0.1;
            $this->mpdf->watermarkImgBehind = TRUE;
            $this->mpdf->showWatermarkImage = TRUE;

            // content
            $header = $this->load->view('print_pdf_header', $data, TRUE);
            $this->mpdf->SetHTMLHeader($header);

            $footer = $this->load->view('print_pdf_footer', $data, TRUE);
            $this->mpdf->SetHTMLFooter($footer);

            $html   = $this->load->view($this->module_path . '/print_ri', $data, TRUE);
            $this->mpdf->WriteHTML($html);

            $this->mpdf->Output("RI_No_".$data['rec']->riNo.".pdf","I");

        
        } else {
            // no access this page
            $data['class'] = "danger";
            $data['msg'] = "Sorry, you don't have access to this page!";
            $data['urlredirect'] = "";
            $this->load->view('header', $data);
            $this->load->view('message');
            $this->load->view('footer');
        }
    }


    public function getSignatory($code)
    {
        $this->db->where('code', $code);
        $this->db->from('signatories');
        return $this->db->get()->row();
    }

    private function getInspector($id)
    {
       $this->db->where('inspectorID', $id);
       $this->db->from('inspectors');
       return $this->db->get()->row();   
    }


}