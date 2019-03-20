<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
 * Controller Template ver 0.2 
 * To use just search change start change end comments
 * Add your code inside these tags
 */
class Receiving_report extends CI_Controller
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
        $this->_init_setup('Transactions', 'receiving_report', 'rrheaders', 'rrID', 'rrNo');
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
        $this->data['current_module'] = $this->modules[$this->module]['sub']['Receiving Report']; // defines the current sub module
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
            'rrheaders' => 'rrID'
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

            $this->frameworkhelper->clear_session_item('rrdetails');

            $series = $this->db->count_all_results($this->table);
            $data['series'] = date('y').str_pad($series, 6 , "0", STR_PAD_LEFT);








            //change start
            $this->db->select('suppliers.suppID');
            $this->db->select('suppliers.suppName');
            $this->db->select('provinces.province as province_name');
            $this->db->select('cities.city as city_name');
            $this->db->select('barangays.barangay as barangay_name');
            $this->db->from('suppliers');
            $this->db->join('provinces', 'suppliers.provinceID=provinces.provinceID', 'left');
            $this->db->join('cities', 'suppliers.cityID=cities.cityID', 'left');
            $this->db->join('barangays', 'suppliers.barangayID=barangays.barangayID', 'left');
            $data['suppliers'] = $this->db->get()->result();


            $this->db->select('category.catID');
            $this->db->select('category.category');
            $this->db->from('category');            
            $data['category'] = $this->db->get()->result();
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

























    public function save()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;

        //change start
        $table_fields = array('rrNo', 'drNo', 'plateNo', 'driverName', 'driverAssistant');
        //change end
        
        

        // rrID    rrNo    poDate  suppID  grossAmount discount    netAmount   paymentTerm    dueDate amountPaid  balance datePaid    createdBy   dateCreated confirmedBy dateConfirmed   cancelledBy dateCancelled   dateInserted    dateLastEdit    dateDeleted deletedBy   remarks status 1: pending; 2: confirm; 3: partial delivery; 4: full
        

        // check role
        if ($this->roles['create']) {
            $this->record->table = $this->table;
            $this->record->fields = array();
            
            foreach ($table_fields as $fld) {

                $this->record->fields[$fld] = trim($this->input->post($fld));               
            }
     
            //change start
            $this->record->fields['poID']           = trim($this->input->post('poID2'));
            $this->record->fields['suppID']           = trim($this->input->post('suppID2'));
            $this->record->fields['rrDate']        = date('Y-m-d', strtotime(trim($this->input->post('rrDate'))));
            $this->record->fields['drDate']         = date('Y-m-d', strtotime(trim($this->input->post('drDate'))));
            $this->record->fields['timeDelivered']    = date('Y-m-d', strtotime(trim($this->input->post('timeDelivered'))));
            $this->record->fields['createdBy']          = $this->session->userdata('current_user')->userID; 

            $this->record->fields['dateInserted']       = date('Y-m-d H:i:s');   
            $this->record->fields['dateLastEdit']       = date('Y-m-d H:i:s'); 
            //change end
            
            if ($this->record->save()) {
                $this->record->fields = array();
                //change start
                // $this->record->where['postNo']  = $genNo;
                //change end
                $id = $this->record->where[$this->pfield] = $this->db->insert_id();
                $this->record->retrieve();

                //change start
                // save dates

                // id  rrID    productID   qty price   amount  delQty  delStatus   dateInserted    dateLastEdit    dateDeleted deletedBy   status 1: pending; 2: confirm; 3: partial delivery; 4: full
                $total = 0;
                if (!empty($_SESSION['rrdetails'])) {
                    $batch = array();
                    foreach($_SESSION['rrdetails'] as $product){
                        $info = array();
                        $info['rrID']           = $this->record->field->rrID;
                        $info['productID']         = $product['productID'];                       
                        $info['qty']            = $product['productQty'];
                        $info['price']         = $product['productPrice'];
                        $info['amount']         = $product['productAmount'];
                        $info['dateInserted']   = date('Y-m-d H:i:s');
                        $info['status'] = 1;
                        $batch[] = $info;

                    }
                    $this->db->insert_batch('rrdetails', $batch);
                }
                //change end

                $message = 'New Receiving Report Created - '.$this->record->field->rrNo;
                $this->record->send_notification($message, $this->record->field->rrID);
                
                // record logs
                $pfield = $this->pfield;

                $logs = "Record - " .$id;
                $this->log_model->table_logs($data['current_module']['module_label'], $this->table, $this->pfield, $this->record->field->$pfield, 'Insert', $logs);
                
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

    public function edit($id)
    {   
        // load submenu
        $this->submenu();
        $data = $this->data;
        $id = $this->encrypter->decode($id);
        
        if ($this->roles['edit']) {
            //change start
            // for retrieve with joining tables -------------------------------------------------
            
            $this->frameworkhelper->clear_session_item('rrdetails');// 
            // set table
            $this->record->table = $this->table;
            // set fields for the current table
            $this->record->setFields();
            // extend fields - join tables      

            $this->record->fields[] = 'suppliers.suppName';
            $this->record->fields[] = 'suppliers.streetNo';

            // set joins                   
            $this->record->joins[]  = array('suppliers',$this->table.'.suppID=suppliers.suppID','left');
                                
            // set where
            $this->record->where[$this->table.'.'.$this->pfield] = $id;
            $this->record->where[$this->table.'.status > 0'];

            // execute retrieve
            $this->record->retrieve();            
            $data['rec'] = $this->record->field;
            // ----------------------------------------------------------------------------------
            $this->db->select('rrdetails.rrID');
            $this->db->select('rrdetails.qty as productQty');
            $this->db->select('rrdetails.price as productPrice');
            $this->db->select('rrdetails.amount as productAmount');  
            $this->db->select('rrdetails.productID');        
            $this->db->select('products.name as productName');
            $this->db->select('products.description as productDescription');
            $this->db->select('category.category as productCategory');
            $this->db->select('unit_measurements.umsr as productUmsr');
            $this->db->select('products.productCode');
            $this->db->from('rrdetails');
            $this->db->join('products', 'rrdetails.productID=products.productID','left');
            $this->db->join('unit_measurements', 'products.umsr=unit_measurements.umsrID','left');
            $this->db->join('category', 'products.catID=category.catID','left');
            $this->db->where('rrdetails.rrID', $data['rec']->rrID);
            $this->db->where('rrdetails.status >', 0);
            $rrdetails = $this->db->get()->result_array();
            // productID,productUmsr,productName,productQty,productPrice,productAmount,productBrand
            

            //Details
            //change start
            $sessionSet = 'rrdetails';
            $this->frameworkhelper->clear_session_item($sessionSet);


            if (!empty($rrdetails)) {
                foreach ($rrdetails as $new_item) {
                    $this->frameworkhelper->add_session_item($sessionSet, $new_item);
                }
            }



            $this->db->select('suppliers.suppID');
            $this->db->select('suppliers.suppName');
            $this->db->select('provinces.province as province_name');
            $this->db->select('cities.city as city_name');
            $this->db->select('barangays.barangay as barangay_name');
            $this->db->from('suppliers');
            $this->db->join('provinces', 'suppliers.provinceID=provinces.provinceID', 'left');
            $this->db->join('cities', 'suppliers.cityID=cities.cityID', 'left');
            $this->db->join('barangays', 'suppliers.barangayID=barangays.barangayID', 'left');
            $data['suppliers'] = $this->db->get()->result();
            


            $this->db->select('products.productID');
            $this->db->select('products.productCode');
            $this->db->select('products.name as product_name');
            // $this->db->select('products.description as product_description');
            $this->db->select('products.lastcost as product_lastcost');
            $this->db->from('products');
            $data['products'] = $this->db->get()->result();
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
        $id = $this->encrypter->decode($this->input->post('rrID'));
        //change start
        $table_fields = array('rrNo', 'drNo', 'plateNo', 'driverName', 'driverAssistant');
        //change end
        
        // check roles
        if ($this->roles['edit']) {
            $this->record->table = $this->table;
            $this->record->fields = array();
            
            foreach ($table_fields as $fld) {

                $this->record->fields[$fld] = trim($this->input->post($fld));
                          
            }

            //change start
            $this->record->fields['rrDate']        = date('Y-m-d', strtotime(trim($this->input->post('rrDate'))));
            $this->record->fields['drDate']         = date('Y-m-d', strtotime(trim($this->input->post('drDate'))));
            $this->record->fields['timeDelivered']    = date('Y-m-d', strtotime(trim($this->input->post('timeDelivered'))));
            

            // $this->record->fields['dateInserted']       = date('Y-m-d H:i:s');   
            $this->record->fields['dateLastEdit']       = date('Y-m-d H:i:s'); 
            //change end
            
            $this->record->pfield = $this->pfield;
            $this->record->pk = $id;

            
            // field logs here
            $wasChange = $this->log_model->field_logs($data['current_module']['module_label'], $this->table, $this->pfield, $id, 'Update', $this->record->fields);
            
            if ($this->record->update()) {
                //change start

                $this->db->set('status',-100);
                $this->db->where('rrID',$id);
                $this->db->update('rrdetails');

                $total = 0;
                if (!empty($_SESSION['rrdetails'])) {
                    // $batch = array();
                    foreach($_SESSION['rrdetails'] as $product){

                        $this->db->set('rrID',$id);
                        $this->db->set('productID',$product['productID']);
                        $this->db->set('qty',$product['productQty']);
                        $this->db->set('price',$product['productPrice']);
                        $this->db->set('amount',$product['productAmount']);
                        $this->db->set('dateInserted',date('Y-m-d H:i:s'));
                        $this->db->set('status',1);
                        $this->db->insert('rrdetails');
                    }                    
                }                
                //change end
                // record logs
                if ($wasChange) {
                    $logs = "Record - " . $this->encrypter->decode(trim($this->input->post($this->logfield)));
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

    public function delete($id = 0) {
        // load submenu
        $this->submenu ();
        $data = $this->data;
        $id = $this->encrypter->decode ( $id );
        
        // check roles
        if ($this->roles ['delete']) {
            // set fields
            $this->record->fields = array ();
            // set table
            $this->record->table = $this->table;
            // set where
            $this->record->where [$this->pfield] = $id;
            // execute retrieve
            $this->record->retrieve ();
            
            if (! empty ( $this->record->field )) {
                $this->record->pfield = $this->pfield;
                
                $this->record->pk = $id;

                $this->record->pfield = $this->pfield;
                $this->record->field->dateDeleted = date('Y-m-d H:i:s');
                $this->record->field->deletedBy = $this->session->userdata('current_user')->userID;
                
                // record logs
                $rec_value = $this->record->field->name;
                
                // check if in used
                if (! $this->_in_used ( $id )) {
                    if ($this->record->delete ()) {
                        // record logs
                        $logfield = $this->logfield;
                        
                        $logs = "Record - " . $this->encrypter->decode($this->record->field->$logfield);
                        $this->log_model->table_logs ( $data ['current_module'] ['module_label'], $this->table, $this->pfield, $this->record->pk, 'Delete', $logs );
                        
                        // successful
                        $data ["class"] = "success";
                        $data ["msg"] = $this->data ['current_module'] ['module_label'] . " successfully deleted.";
                        $data ["urlredirect"] = $this->controller_page . "/";
                        $this->load->view ( "header", $data );
                        $this->load->view ( "message" );
                        $this->load->view ( "footer" );
                    } else {
                        // error
                        $data ["class"] = "danger";
                        $data ["msg"] = "Error in deleting the " . $this->data ['current_module'] ['module_label'] . "!";
                        $data ["urlredirect"] = "";
                        $this->load->view ( "header", $data );
                        $this->load->view ( "message" );
                        $this->load->view ( "footer" );
                    }
                } else {
                    // error
                    $data ["class"] = "danger";
                    $data ["msg"] = "Data integrity constraints.";
                    $data ["urlredirect"] = "";
                    $this->load->view ( "header", $data );
                    $this->load->view ( "message" );
                    $this->load->view ( "footer" );
                }
            
            } else {
                // error
                $data ["class"] = "danger";
                $data ["msg"] = $this->module . " record not found!";
                $data ["urlredirect"] = "";
                $this->load->view ( "header", $data );
                $this->load->view ( "message" );
                $this->load->view ( "footer" );
            }
        } else {
            echo "no roles";
            // error
            $data ["class"] = "danger";
            $data ["msg"] = "Sorry, you don't have access to this page!";
            $data ["urlredirect"] = "";
            $this->load->view ( "header", $data );
            $this->load->view ( "message" );
            $this->load->view ( "footer" );
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
            // extend fields - join tables          
            //change start
            // $this->record->fields[]  = 'rrdetails.*';
            // $this->record->fields[] = 'products.*';
            $this->record->fields[] = 'suppliers.suppName';
            $this->record->fields[] = 'suppliers.streetNo';
            $this->record->fields[] = 'suppliers.suppID';
            $this->record->fields[] = 'poheaders.poNo';
            $this->record->fields[] = 'poheaders.poID';

            
            // set joins                   
            $this->record->joins[]  = array('suppliers',$this->table.'.suppID=suppliers.suppID','left');  
            $this->record->joins[]  = array('poheaders',$this->table.'.poID=poheaders.poID','left');  
                                
            // set where
            $this->record->where[$this->table.'.'.$this->pfield] = $id;
            $this->record->where[$this->table.'.status != -100'];
            
            // execute retrieve
            $this->record->retrieve();

            // ----------------------------------------------------------------------------------
            $data['rec'] = $this->record->field;
            // var_dump($data['rec']);
            // var_dump($data['headers']); 
            // echo $this->db->last_query();die();
            //change end
            $this->db->select('rrdetails.rrID');
            $this->db->select('rrdetails.qty');
            // $this->db->select('rrdetails.delQty');
            $this->db->select('rrdetails.price');
            $this->db->select('rrdetails.amount');  
            $this->db->select('rrdetails.productID');        
            $this->db->select('products.name');
            // $this->db->select('products.description');
            $this->db->select('unit_measurements.umsr');
            $this->db->select('category.category');
            $this->db->select('products.productCode');
            
            
            $this->db->from('rrdetails');
            $this->db->join('products', 'rrdetails.productID=products.productID','left');
            $this->db->join('unit_measurements', 'products.umsr=unit_measurements.umsrID','left');
            $this->db->join('category', 'products.catID=category.catID','left');

            
            $this->db->where('rrdetails.rrID', $data['rec']->rrID);
            $this->db->where('rrdetails.status >', 0);
            // $this->db->where('products.status != -100');
            $data['details'] = $this->db->get()->result();

            // echo '<br/><br/>'
           // var_dump($data['details']); die();
       
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


    public function view_decoded($id, $notificationID)
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
            // extend fields - join tables          
            //change start
            // $this->record->fields[]  = 'rrdetails.*';
            // $this->record->fields[] = 'products.*';
            $this->record->fields[] = 'suppliers.suppName';
            $this->record->fields[] = 'suppliers.streetNo';
            $this->record->fields[] = 'poheaders.poNo';
            $this->record->fields[] = 'poheaders.poID';

            
            // set joins                   
            $this->record->joins[]  = array('suppliers',$this->table.'.suppID=suppliers.suppID','left');  
            $this->record->joins[]  = array('poheaders',$this->table.'.poID=poheaders.poID','left');  
                                
            // set where
            $this->record->where[$this->table.'.'.$this->pfield] = $id;
            $this->record->where[$this->table.'.status != -100'];
            
            // execute retrieve
            $this->record->retrieve();

            // ----------------------------------------------------------------------------------
            $data['rec'] = $this->record->field;
            // var_dump($data['rec']);
            // var_dump($data['headers']); 
            // echo $this->db->last_query();die();
            //change end
            $this->db->select('rrdetails.rrID');
            $this->db->select('rrdetails.qty');
            // $this->db->select('rrdetails.delQty');
            $this->db->select('rrdetails.price');
            $this->db->select('rrdetails.amount');  
            $this->db->select('rrdetails.productID');        
            $this->db->select('products.name');
            // $this->db->select('products.description');
            $this->db->select('unit_measurements.umsr');
            $this->db->select('category.category');
            $this->db->select('products.productCode');
            
            
            $this->db->from('rrdetails');
            $this->db->join('products', 'rrdetails.productID=products.productID','left');
            $this->db->join('unit_measurements', 'products.umsr=unit_measurements.umsrID','left');
            $this->db->join('category', 'products.catID=category.catID','left');

            
            $this->db->where('rrdetails.rrID', $data['rec']->rrID);
            $this->db->where('rrdetails.status >', 0);
            // $this->db->where('products.status != -100');
            $data['details'] = $this->db->get()->result();

            
            //notification
            $this->db->set('isRead', 1);
            $this->db->where('id', $notificationID);
            $this->db->update('message_board');
       
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

    public function show()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;

        //change start
        $condition_fields = array(
            array('variable'=>'rrNo', 'field'=>$this->table.'.rrNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'rrDate', 'field'=>$this->table.'.rrDate', 'default_value'=>'', 'operator'=>'where'),
            array('variable'=>'drNo', 'field'=>$this->table.'.drNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'suppName', 'field'=>'suppliers.suppName', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'plateNo', 'field'=>$this->table.'.plateNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'driverName', 'field'=>$this->table.'.driverName', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'timeDelivered', 'field'=>$this->table.'.timeDelivered', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
        );
        
        // sorting fields
        $sorting_fields = array('rrNo'=>'desc','rrDate'=>'desc');
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
        // $this->session->set_userdata($controller.'_datePosted', $datePosted);
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
        // $data['datePosted'] = $datePosted;
        
        // select
        $this->db->select($this->table.'.*');
        $this->db->select('suppliers.suppName');
        $this->db->select('poheaders.poNo');
    

        // from
        $this->db->from($this->table);
        
        
        // join     
        $this->db->join('suppliers',$this->table.'.suppID=suppliers.suppID','left');
        $this->db->join('poheaders',$this->table.'.poID=poheaders.poID','left');
        
        
        // where
        $this->db->where($this->table.'.status != -100');
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
        //change start
        // $data['datePosted'] = $datePosted;
        
        // select
        $this->db->select($this->table.'.*');
        $this->db->select('suppliers.suppName');
        $this->db->select('poheaders.poNo');
    

        // from
        $this->db->from($this->table);
        
        
        // join     
        $this->db->join('suppliers',$this->table.'.suppID=suppliers.suppID','left');
        $this->db->join('poheaders',$this->table.'.poID=poheaders.poID','left');
        
        
        // where
        $this->db->where($this->table.'.status != -100');
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
            array ('variable' => 'rrNo', 'field' => $this->table . '.rrNo', 'default_value' => '', 'operator' => 'where' ), 
            array ('variable' => 'poDate', 'field' => $this->table . '.poDate', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'suppName', 'field' =>  'suppliers.suppName', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'netAmount', 'field' => $this->table . '.netAmount', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'balance', 'field' => $this->table . '.balance', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'dueDate', 'field' => $this->table . '.dueDate', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'deliveryDate', 'field' => $this->table . '.deliveryDate', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'paymentTerm', 'field' => $this->table . '.paymentTerm', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'status', 'field' => $this->table . '.status', 'default_value' => '', 'operator' => 'where' ) 
        );
        
        // sorting fields
        $sorting_fields = array('rrNo'=>'asc','poDate'=>'asc');
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
        // $datePosted = $this->session->userdata($controller.'_datePosted');
        //change end
        $limit = $this->session->userdata($controller . '_limit');
        $offset = $this->session->userdata($controller . '_offset');
        $sortby = $this->session->userdata($controller . '_sortby');
        $sortorder = $this->session->userdata($controller . '_sortorder');
        

        //===================================
        //change start
        // $data['datePosted'] = $datePosted;
        
        // select
        $this->db->select($this->table.'.*');
        $this->db->select('suppliers.suppName');
        $this->db->select('poheaders.poNo');
    

        // from
        $this->db->from($this->table);
        
        
        // join     
        $this->db->join('suppliers',$this->table.'.suppID=suppliers.suppID','left');
        $this->db->join('poheaders',$this->table.'.poID=poheaders.poID','left');
        
        
        // where
        $this->db->where($this->table.'.status != -100');
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
            array ('variable' => 'rrNo', 'field' => $this->table . '.rrNo', 'default_value' => '', 'operator' => 'where' ), 
            array ('variable' => 'poDate', 'field' => $this->table . '.poDate', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'suppName', 'field' =>  'suppliers.suppName', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'netAmount', 'field' => $this->table . '.netAmount', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'balance', 'field' => $this->table . '.balance', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'dueDate', 'field' => $this->table . '.dueDate', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'deliveryDate', 'field' => $this->table . '.deliveryDate', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'paymentTerm', 'field' => $this->table . '.paymentTerm', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'status', 'field' => $this->table . '.status', 'default_value' => '', 'operator' => 'where' ) 
        );
        
        // sorting fields
        $sorting_fields = array('rrNo'=>'asc','poDate'=>'asc');
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
        // $datePosted = $this->session->userdata($controller.'_datePosted');
        //change end
        $limit = $this->session->userdata($controller . '_limit');
        $offset = $this->session->userdata($controller . '_offset');
        $sortby = $this->session->userdata($controller . '_sortby');
        $sortorder = $this->session->userdata($controller . '_sortorder');
        

        //===================================
        //change start
        // $data['datePosted'] = $datePosted;
        
        // select
        $this->db->select($this->table.'.*');
        $this->db->select('suppliers.suppName');
        $this->db->select('poheaders.poNo');
    

        // from
        $this->db->from($this->table);
        
        
        // join     
        $this->db->join('suppliers',$this->table.'.suppID=suppliers.suppID','left');
        $this->db->join('poheaders',$this->table.'.poID=poheaders.poID','left');
        
        
        // where
        $this->db->where($this->table.'.status != -100');
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
            <Column ss:Index='2' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
            <Column ss:Index='3' ss:AutoFitWidth=\"1\" ss:Width='120.00'/>
            <Column ss:Index='4' ss:AutoFitWidth=\"1\" ss:Width='120.00'/>
            <Column ss:Index='5' ss:AutoFitWidth=\"1\" ss:Width='120.00'/>
            <Column ss:Index='6' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
            <Column ss:Index='7' ss:AutoFitWidth=\"1\" ss:Width='80.00'/>
                ";
        
        // header
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='9'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s20'>";
        $data .= "<Cell ss:MergeAcross='9'><Data ss:Type='String'>" . $companyName . "</Data></Cell>";
        $data .= "</Row>";
        $data .= "<Row ss:StyleID='s24A'>";
        $data .= "<Cell ss:MergeAcross='9'><Data ss:Type='String'>" . $address . "</Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='9'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='9'><Data ss:Type='String'>" . strtoupper($title) . "</Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='9'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        
        $fields[] = "  ";
        $fields[] = "RR NO";
        $fields[] = "RR DATE";
        $fields[] = "SUPPLIER NAME";
        $fields[] = "PO NO";      
        $fields[] = "DR NO";
        $fields[] = "PLATE NO";      
        $fields[] = "DRIVER NAME";
        $fields[] = "DELIVERY DATE";   
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
                $rrDate = $row->rrDate == '0000-00-00' ? '' : date("M d, Y",strtotime($row->rrDate));
                $timeDelivered = $row->timeDelivered == '0000-00-00' ? '' : date("M d, Y",strtotime($row->timeDelivered));
                // $deliveryTerm = $row->deliveryTerm == '' ? '' : $row->deliveryTerm.'-Working Days';

                $data .= "<Row>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $ctr . ".</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->rrNo . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $rrDate . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->suppName . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->poNo . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->drNo . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->plateNo. "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->driverName . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $timeDelivered . "</Data></Cell>";
                if ($row->status == 1) {
                    $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>Pending</Data></Cell>";  
                } else if ($row->staus = 2){
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
    public function check_duplicate()
    {
        //change start
        $this->db->where('rrNo', trim($this->input->post('rrNo')));
        //change end
        
        if ($this->db->count_all_results($this->table))
            echo "1"; // duplicate
        else
            echo "0";
    }

    //============================================
    // Module Specific Functions
    //============================================
    public function display_session_products($display_area='')
    {
       
        $sessionSet = $this->input->post('sessionSet');
        $records = isset($_SESSION[$sessionSet])? $_SESSION[$sessionSet]:array();

        // $headers = array('Stock No'=>'left', 'Price' => 'left', 'Amount' => 'left');
        $headers = array(
            array('column_header'=>'','column_field'=>'','width'=>'w-5','text-align'=>'text-left','align'=>'left'),
            array('column_header'=>'Category','column_field'=>'','width'=>'w-5','text-align'=>'text-left','align'=>'left'),
            array('column_header'=>'Product','column_field'=>'','width'=>'w-10','text-align'=>'text-left','align'=>'left'),
            array('column_header'=>'Quantity','column_field'=>'','width'=>'w-10','text-align'=>'text-center','align'=>'center'),
            array('column_header'=>'Unit','column_field'=>'','width'=>'w-10','text-align'=>'text-center','align'=>'left'),
            array('column_header'=>'Unit Cost','column_field'=>'','width'=>'w-10','text-align'=>'text-right','align'=>'right'),
            array('column_header'=>'Amount','column_field'=>'','width'=>'w-10','text-align'=>'text-right','align'=>'right'),
          
        );
        //display the value of session key
        $display = array(
            array('align'=>'left','field'=>'productCategory'),
            array('align'=>'left','field'=>'productName'),
            array('align'=>'center','field'=>'productQty'),
            array('align'=>'center','field'=>'productUmsr'),
            array('align'=>'right','field'=>'productPrice'),
            array('align'=>'right','field'=>'productAmount'),
          
        );
        echo $this->_tm_session_tabular_view($records,$headers,$display,$sessionSet,'950',$display_area);
    }

     private function _tm_session_tabular_view($records, $headers, $display, $sessionID, $width="100%",$display_area='',$default_rows=9,$callback="")
    {
        $total_amount = 0;
        $colspan = count($headers)+1;
        $view = '<table class="table hover" id="po-details-table">'."\n";

        //thead
        $view .= '<thead class="thead-light">'."\n";
        if (!empty($headers)) {
            foreach($headers as $col) {
                // var_dump($col); 
                if ($col['column_field'] == $sortby) {
                    if ($sortorder=="DESC") {
                        $view .= "\n".'<th class="'.$col['width'].' '.$col['text-align']. '" align="'.$col['align'].'" nowrap>';
                    } else {
                        $view .= "\n".'<th class="'.$col['width'].' '.$col['text-align']. '" align="'.$col['align'].'" nowrap>';
                    }
                } else {
                    $view .= "\n".'<th class="'.$col['width'].' '.$col['text-align']. '" align="'.$col['align'].'" nowrap>';
                }
                
                $view .= $col['column_header'];
                $view .= '</th>';
            }
        }
        $view .= '</thead>'."\n";


        //tbody
        $view .= '<tbody>'."\n";

 
        if (!empty($_SESSION[$sessionID])) {
            foreach($_SESSION[$sessionID] as $id=>$product) {
            $view .= '<tr colspan="'.$colspan.'">'."\n";
            $view .= '<td>
                    <i style="font-size: 24px; color: #14699e!important;"class="la la-trash-o product" alt="Delete" title="Delete Row" style="cursor: pointer;" onclick="tm_delete_session_product(\''.$sessionID.'\',\''.$id.'\',\''.$display_area.'\',\''.$callback.'\');"></i>
                    </td>'."\n";
                    
            if(!empty($display)) {
                foreach($display as $td) {
                    $text = $td['field'];
                    if($text == "productAmount") {
                        $total_amount += $product[$text];
                    }
                    
                    $view .= '<td align="'.$td['align'].'" nowrap>'.$product[$text].'</td>'."\n";  
                }
            }
            $view .= '</tr>';
            }
        }           
        
        
        $view .= '</tbody>'."\n";
        $view .= '<tfoot>'."\n";
        $view .= '<tr class="bg-light">'."\n";
        $view .= '<td>'."\n";
        $view .= '</td>'."\n";
        $view .= '<td>'."\n";
        $view .= '</td>'."\n";
        $view .= '<td>'."\n";
        $view .= '</td>'."\n";
        $view .= '<td>'."\n";
        $view .= '</td>'."\n";        
        $view .= '<td>'."\n";
        $view .= '</td>'."\n";
        $view .= '</td><input type="hidden" value="'.$total_amount.'" name="totalAmount" id="totalAmount">'."\n";
        $view .= '<td class="font-weight-bold text-right"><span>TOTAL: </span>'."\n";
        $view .= '<td class="font-weight-bold" align="right" style="white-space: nowrap;"><span>&#8369; '.number_format($total_amount,2).'</span>'."\n";
        $view .= '</td>'."\n";
        $view .= '</tr>'."\n";
        $view .= '</tfoot>'."\n";
        $view .= '</table>'."\n";       
        
        return $view;
    }

    public function close_record()
    {
        $status = $this->input->post('status');
        $rrID = $this->input->post('rrID');
        $closeReason = trim($this->input->post('closeReason'));
        // echo $closeReason; die();

        if ($status == 4) {

            $this->db->set('status', 4);
            $this->db->set('closedBy', $this->session->userdata('current_user')->userID);
            $this->db->set('dateClosed', date('Y-m-d H:i:s'));
            $this->db->set('closeReason', $closeReason);

        } 
                        
        $this->db->where('rrID', $rrID );
        $this->db->update($this->table);

        echo true;
    }
























    //================================================================================= CONFIRM INCREASE INVENTORY

    public function confirm_record()
    {
        $status = $this->input->post('status');
        $id = $this->input->post('id');
        $poID = $this->input->post('poID');
        $suppID = $this->input->post('suppID');
        $message = '';
        
        if ($status == 2) {

            $this->db->set('status', 2);
            $this->db->set('confirmedBy', $this->session->userdata('current_user')->userID);
            $this->db->set('dateConfirmed', date('Y-m-d H:i:s'));

        } else if ($status == 0) {
            $this->db->set('status', 0);
            $this->db->set('cancelledBy', $this->session->userdata('current_user')->userID);
            $this->db->set('dateCancelled', date('Y-m-d H:i:s'));
        }
                        
        $this->db->where($this->pfield, $id);
        $this->db->update($this->table);

        if ($status == 2) {

            $records = $this->confirm_rr($id, $poID);
            
            if ($records) {
                foreach ($records as $row) {

                    $message = 'PO No. '.$row->poNo.' - '.'Fully Delivered.';

                    $qtyValue = $row->newQty;
                    $refNo = 'PO No.'.$row->poNo.'/RR No.'.$row->rrNo;
                    $this->update_stockcards('DR', $row->productID, $qtyValue, $refNo, $row->currentPrice, $row->lastcost, $row->markupType, $row->markup);

                    $this->update_supplier_ledger('DR', $row->productID, $qtyValue, $refNo, $suppID);
                }
            }


            $this->db->select('rrdetails.qty');
            $this->db->select('rrdetails.productID');
            $this->db->select('rrheaders.poID');
            $this->db->from('rrdetails');
            $this->db->join('rrheaders', 'rrdetails.rrID=rrheaders.rrID', 'left');
            $this->db->join('poheaders', 'rrheaders.poID=poheaders.poID', 'left');
            $this->db->where('poheaders.poID', $poID);
            $this->db->where('rrheaders.rrID', $id);
            // $this->db->where('poheaders.status >', 0);
            $records = $this->db->get()->result();

            if (!empty($records)) {
                foreach ($records as $row) {
                    $this->db->set('podetails.delQty', 'delQty+'.$row->qty, false);
                    $this->db->where('podetails.productID', $row->productID);
                    $this->db->where('podetails.poID', $row->poID);
                    $this->db->update('podetails');
                }
            }



            //UPDATE PO DETAILS
            $this->db->select('podetails.id');
            $this->db->select('podetails.delQty');
            $this->db->select('podetails.qty');
            $this->db->from('podetails');
            $this->db->where('podetails.poID', $poID);
            $this->db->where('podetails.status >', 0);
            $records = $this->db->get()->result();

            if (!empty($records)) {
                foreach ($records as $row) {
                    if ($row->delQty >= $row->qty) {
                        $this->db->set('podetails.status', 2);
                        $this->db->where('podetails.id', $row->id);
                        $this->db->update('podetails');
                    }
                }
            }


            $this->db->select('podetails.id');
            $this->db->from('podetails');
            $this->db->where('podetails.poID', $poID);
            $this->db->where('podetails.status', 1);
            $count = $this->db->count_all_results();

            if ($count == 0) {
                //Closed if all are fully delivered
                $this->db->set('status', 4);
                $this->db->where('poID', $poID);
                $this->db->update('poheaders');

                $this->record->send_notification($message, $poID);
            } else {
                //Partial if naa pay na bilin nga dili completed
                $this->db->set('status', 3);
                $this->db->where('poID', $poID);
                $this->db->update('poheaders');
            }

            echo 1;
        }

    }



    private function update_supplier_ledger($type='', $productID=0, $qtyValue=0, $refNo='', $suppID)
    {
        $this->db->select('supplier_ledger.id');
        $this->db->select('supplier_ledger.productID');
        $this->db->select('supplier_ledger.endBal');
        $this->db->select('products.name as productName');
        $this->db->select('products.description as productDescription');
        $this->db->select('products.umsr as productUmsr');
        $this->db->from('supplier_ledger');  
        $this->db->join('products', 'supplier_ledger.productID=products.productID', 'left');
        $this->db->where('supplier_ledger.productID', $productID);
        $this->db->where('supplier_ledger.suppID', $suppID);
        $product = $this->db->get()->last_row();

        $begBal = 0;
        $debit  = 0;
        $credit = 0;
        $endBal = 0;

        if (!empty($product)) {

            if ($type == 'DR') {

                $begBal = $product->endBal;
                $debit  = $qtyValue;
                $credit = 0;
                $endBal = ($product->endBal + $qtyValue);

            } else {

                if ($this->qtyValue > $product->endBal) {
                    // return '-1';
                    // echo 'Insufficiend Funds';
                    return true; //return lang ta since loop man ta

                } else {
                    $begBal = $product->endBal;
                    $debit  = 0;
                    $credit = $qtyValue;
                    $endBal = ($product->endBal - $qtyValue);
                }
            }


            $this->db->set('suppID', $suppID);
            $this->db->set('productID', $productID);
            $this->db->set('begBal', $begBal);
            $this->db->set('increase', $debit);
            $this->db->set('decrease', $credit);
            $this->db->set('endBal', $product->endBal);
            $this->db->set('refNo', $refNo);
            $this->db->set('dateInserted', date('Y-m-d H:i:s'));
            $this->db->insert('supplier_ledger');

            return true;

        } else {
            //Initial Balance for product
            // post to stockcards
            if ($type == 'DR') {


                $this->db->set('suppID', $suppID);
                $this->db->set('productID', $productID);
                $this->db->set('begBal', 0);
                $this->db->set('increase', $qtyValue);
                $this->db->set('decrease', 0);
                $this->db->set('endBal', $qtyValue);
                $this->db->set('refNo', $refNo);
                $this->db->set('dateInserted', date('Y-m-d H:i:s'));
                $this->db->insert('supplier_ledger');

                return true;

            } else {
                //This is credit
                //return invalid request since this is a new product/expiry
                //does not exist
                return true; //return lang ta since loop man ta
            }
        }

        return true;
    }


    private function update_stockcards($type='', $productID=0, $qtyValue=0, $refNo='', $currentPrice=0, $lastcost=0, $markupType='', $markup='')
    {
        $this->db->select('stockcards.id');
        $this->db->select('stockcards.productID');
        $this->db->select('stockcards.endBal');
        $this->db->select('products.name as productName');
        $this->db->select('products.description as productDescription');
        $this->db->select('products.umsr as productUmsr');
        $this->db->from('stockcards');  
        $this->db->join('products', 'stockcards.productID=products.productID', 'left');
        $this->db->where('stockcards.productID', $productID);
        $product = $this->db->get()->last_row();

        $begBal = 0;
        $debit  = 0;
        $credit = 0;
        $endBal = 0;

        if (!empty($product)) {

            if ($type == 'DR') {

                $begBal = $product->endBal;
                $debit  = $qtyValue;
                $credit = 0;
                $endBal = ($product->endBal + $qtyValue);

            } else {

                if ($this->qtyValue > $product->endBal) {
                    // return '-1';
                    // echo 'Insufficiend Funds';
                    return true; //return lang ta since loop man ta

                } else {
                    $begBal = $product->endBal;
                    $debit  = 0;
                    $credit = $qtyValue;
                    $endBal = ($product->endBal - $qtyValue);
                }
            }

            $this->db->set('productID', $productID);
            $this->db->set('begBal', $begBal);
            $this->db->set('increase', $debit);
            $this->db->set('decrease', $credit);
            $this->db->set('endBal', $endBal);
            $this->db->set('refNo', $refNo);
            $this->db->set('dateInserted', date('Y-m-d H:i:s'));
            $this->db->insert('stockcards');

            return true;

        } else {
            //Initial Balance for product
            // post to stockcards
            if ($type == 'DR') {

                $this->db->set('productID', $productID);
                $this->db->set('begBal', 0);
                $this->db->set('increase', $qtyValue);
                $this->db->set('decrease', 0);
                $this->db->set('endBal', $qtyValue);
                $this->db->set('refNo', $refNo);
                $this->db->set('dateInserted', date('Y-m-d H:i:s'));
                $this->db->insert('stockcards');

                return true;

            } else {
                //This is credit
                //return invalid request since this is a new product/expiry
                //does not exist
                return true; //return lang ta since loop man ta
            }
        }

        return true;
    }



    private function confirm_rr($rrID, $poID)
    {
        
        $this->db->set('status', 2);        
        $this->db->where('rrID', $rrID);        
        $this->db->update('rrheaders');

        $this->db->set('status', 2);        
        $this->db->where('rrID', $rrID);        
        $this->db->update('rrdetails');
    
        $records = $this->get_rr_details($rrID);


        $totalAmount = 0;
        foreach ($records as $row) {
            $avecost = (($row->oldQty * $row->lastcost) + ($row->newQty * $row->currentPrice)) / ($row->oldQty + $row->newQty);

            $this->db->select('products.*');
            $this->db->where('productID', $row->productID);
            $old_product = $this->db->get('products', 1)->row();

            //Price 
            //======================================    
            $lowestprice = $old_product->lowestprice;
            $highprice = $old_product->highprice;

            if ($row->currentPrice < $old_product->lowestprice) {
                $lowestprice = $row->currentPrice;
            }

            if ($row->currentPrice > $old_product->highprice) {
                $highprice = $row->currentPrice;
            }
            //======================================


            //Cost
            //======================================
            $lowestcost = $old_product->lowestcost;
            $highcost = $old_product->highcost;

            $oldAmount = ($row->oldQty * $row->lastcost);
            $newAmount = ($row->newQty * $row->currentPrice);

            $totalAmount += $newAmount;

            $value = $oldAmount + $newAmount;
            $stockInventory = $row->oldQty + $row->newQty;

            $costEachQty = $value / $stockInventory;


            if ($costEachQty < $old_product->lowestcost) {
                $lowestcost = $costEachQty;
            }

            if ($costEachQty > $old_product->highcost) {
                $highcost = $costEachQty;
            }
            //======================================


            $this->db->set('lastcost', $costEachQty);
            $this->db->set('lowestcost', $lowestcost);
            $this->db->set('highcost', $highcost);
            $this->db->set('avecost', $avecost);
            //=================
            // $this->db->set('markup', $markup); ??
            // $this->db->set('lowestprice', $lowestprice);
            // $this->db->set('highprice', $highprice); //tanang price manualon lang daw di na hilabtan
            // $this->db->set('aveprice', $aveprice); ?? same formula sa avecost
            // $this->db->set('mdrPrice', $mdrPrice); ??
            //================ di na hilabtan ang price
            $this->db->where('productID', $row->productID);
            $this->db->update('products');


            $this->db->select('qty');
            $this->db->where('productID', $row->productID);
            $oldInventory = $this->db->get('products', 1)->row();

            $newInventoryQty = $oldInventory->qty + $row->newQty;

            $this->db->set('qty', $newInventoryQty);
            $this->db->where('productID', $row->productID);
            $this->db->update('products');


        }

        $this->db->set('totalAmount', $totalAmount);
        $this->db->where('rrID', $rrID);
        $this->db->update('rrheaders');

        $this->db->set('status', 2);
        $this->db->where('poID', $poID);
        $this->db->update('poheaders');

        return $records;

    }

    private function get_rr_details($rrID)
    {
        $this->db->select('rrdetails.id as rrDetailID');
        $this->db->select('rrdetails.rrID');
        $this->db->select('rrdetails.productID');
        $this->db->select('rrdetails.price as currentPrice');
        $this->db->select('rrdetails.qty as newQty');
        $this->db->select('products.catID');
        $this->db->select('products.name');
        $this->db->select('products.description as productDescription');
        $this->db->select('products.lastcost');
        $this->db->select('products.lowestcost');
        $this->db->select('products.highcost');
        $this->db->select('products.avecost');
        $this->db->select('products.markupType');
        $this->db->select('products.markup');
        $this->db->select('products.lowestprice');
        $this->db->select('products.highprice');
        $this->db->select('products.aveprice');
        $this->db->select('products.qty as oldQty');
        $this->db->select('category.category');
        $this->db->select('category.description as categoryDescription');
        $this->db->select('rrheaders.rrNo');
        $this->db->select('poheaders.poNo');
        $this->db->select('unit_measurements.umsr');
        $this->db->from('rrdetails');   
        $this->db->join('products', 'rrdetails.productID=products.productID', 'left');      
        $this->db->join('category', 'products.catID=category.catID', 'left');
        $this->db->join('unit_measurements', 'products.umsr=unit_measurements.umsrID', 'left');
        $this->db->join('rrheaders', 'rrdetails.rrID=rrheaders.rrID', 'left');
        $this->db->join('poheaders', 'rrheaders.poID=poheaders.poID', 'left');
        $this->db->where('rrdetails.rrID', $rrID);    
        $this->db->where('rrheaders.rrID', $rrID);        
        $this->db->where('rrheaders.status >', 0);      
        $this->db->where('rrdetails.status >', 0);
        $records = $this->db->get()->result();

        return $records;
    }

    //================================================================================= CONFIRM INCREASE INVENTORY





















































    public function get_cat_products()
    {
        $catID = $this->input->post('catID');

        $this->db->select('products.productID');
        $this->db->select('products.productCode');
        $this->db->select('products.name');
        $this->db->select('products.lastcost as product_lastcost');
        $this->db->from('products');
        
        $this->db->where ('products.catID',$catID);
        $records = $this->db->get();

        echo $this->frameworkhelper->get_json_data($records, 'productID', 'name');

    }   

    public function get_products()
    {
        $response = new stdClass();
        $response->status  = 0;
        $response->message = '';

        $data = array();

        $productID = $this->input->post('productID');

        $this->db->select('products.name as productName');
        // $this->db->select('products.description');
        $this->db->select('products.umsr');
        $this->db->select('products.lastcost');
        $this->db->select('products.productCode');
        
        $this->db->from('products');
        
        $this->db->where('products.productID', $productID);
        $this->db->where('products.status !=', -100);
        $rec = $this->db->get()->row();
        // var_dump($rec); 

        $response->status  = 1;
        $response->rec  = $rec;
        $response->message = 'success';
        echo json_encode($response);
    }   
    
    public function print_record($id=0)
    {
        $id = $this->encrypter->decode($id);
        
        // load submenu
        $this->submenu();
        $data = $this->data;
        // $data['title'] = "PURCHASE ORDER";
        
        // for retrieve with joining tables -------------------------------------------------
            $this->db->select($this->table.".*");
            $this->db->select('suppliers.suppName');
            $this->db->select('suppliers.streetNo');
            $this->db->select('barangays.barangay');
            $this->db->select('cities.city');
            $this->db->select('provinces.province');

            $this->db->select('createdByUser.firstName as createdByFirst');
            $this->db->select('createdByUser.middleName as createdByMiddle');
            $this->db->select('createdByUser.lastName as createdByLast');

            $this->db->select('confirmedByUser.firstName as confirmedByFirst');
            $this->db->select('confirmedByUser.middleName as confirmedByMiddle');
            $this->db->select('confirmedByUser.lastName as confirmedByLast');

            $this->db->select('cancelledByUser.firstName as cancelledByFirst');
            $this->db->select('cancelledByUser.middleName as cancelledByMiddle');
            $this->db->select('cancelledByUser.lastName as cancelledByLast'); 

            $this->db->from($this->table); 

            $this->db->join('suppliers',$this->table.'.suppID=suppliers.suppID','left');
            $this->db->join('users as createdByUser',$this->table.'.createdBy=createdByUser.userID','left');
            $this->db->join('users as confirmedByUser',$this->table.'.confirmedBy=confirmedByUser.userID','left');
            $this->db->join('users as cancelledByUser',$this->table.'.cancelledBy=cancelledByUser.userID','left');

            $this->db->join('barangays','suppliers.barangayID=barangays.barangayID','left');
            $this->db->join('cities','suppliers.cityID=cities.cityID','left');
            $this->db->join('provinces','suppliers.provinceID=provinces.provinceID','left');
                        
            $this->db->where($this->pfield, $id);
            // ----------------------------------------------------------------------------------
            $data['rrheaders'] = $this->db->get()->row();
            
            // get details
            $this->db->select('rrdetails.rrID');
            $this->db->select('rrdetails.qty');
            $this->db->select('rrdetails.price');
            $this->db->select('rrdetails.amount');  
            $this->db->select('rrdetails.productID');        
            $this->db->select('products.name');
            // $this->db->select('products.description');
            $this->db->select('products.umsr');
            $this->db->select('products.productCode');

            $this->db->from('rrdetails');
            $this->db->join('products', 'rrdetails.productID=products.productID','left');

            $this->db->where('rrdetails.rrID', $data['rrheaders']->rrID);
            $this->db->where('rrdetails.status !=',-100);

            $this->db->order_by('products.productID','ASC');
            $data['rrdetails'] = $this->db->get()->result();
        
            // check roles
            if ($this->roles['view']) {
    
            $data['pdf_paging'] = TRUE;
            // $data['title']      = "PURCHASE ORDER";
                
            
            //-------------------------------
            // load pdf class
            $this->load->library('mpdf');            
            // load pdf class
            $this->mpdf->mpdf('en-GB',array(215.9,330.2),10,'Gotham Narrow',10,10,35,10,10,10,'P');
            $this->mpdf->setTitle($data['title']);
            $this->mpdf->SetDisplayMode('fullpage');
            $this->mpdf->shrink_tables_to_fit = 1;
            $this->mpdf->SetWatermarkImage(base_url().'images/logo/watermark.png');
            $this->mpdf->watermark_font = 'DejaVuSansCondensed';
            $this->mpdf->watermarkImageAlpha = 0.1;
            $this->mpdf->watermarkImgBehind = TRUE;
            $this->mpdf->showWatermarkImage = TRUE;
    
            // content
            // $header = $this->load->view('print_pdf_header', $data, TRUE);
            $header = $this->load->view('print_pdf_po_header', $data, TRUE);
            $this->mpdf->SetHTMLHeader($header);
    
            $footer = $this->load->view('print_pdf_footer', $data, TRUE);
            $this->mpdf->SetHTMLFooter($footer);
            
            $this->mpdf->SetJS('this.print();');
            //Load number to words
            $this->load->library('Numbertowords');
            // echo $this->numbertowords->convert_number(123456789);  die();
           
            // /
    
            $html   = $this->load->view($this->module_path.'/print_record', $data, TRUE);
            $this->mpdf->WriteHTML($html);
            
            //$this->pdf->AutoPrint(false);
            $this->mpdf->Output("PURCHASE_ORDER.pdf","I");
            //$this->Output('filename.pdf', '\Mpdf\Output\Destination::FILE');
        } else {
            // no access this page
            $data['class']  = "danger";
            $data['msg']    = "Sorry, you don't have access to this page!";
            $data['urlredirect']    = "";
            $this->load->view('header', $data);
            $this->load->view('message');
            $this->load->view('footer');
        }
    }


    public function display_session()
    {               
        echo var_dump($_SESSION);
    }


}