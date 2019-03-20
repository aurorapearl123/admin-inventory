<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
 * Controller Template ver 0.2 
 * To use just search change start change end comments
 * Add your code inside these tags
 */
class Collection extends CI_Controller
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
        $this->_init_setup('Transactions', 'collection', 'collection_headers', 'collectionID', 'collectionNo');
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
        $this->data['current_module'] = $this->modules[$this->module]['sub']['Payment']; // defines the current sub module
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
            'collection_headers' => 'collectionID'
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

            $this->frameworkhelper->clear_session_item('collection_details');

            $series = $this->db->count_all_results($this->table);
            $data['series'] = date('y').str_pad($series, 6 , "0", STR_PAD_LEFT);



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
        $table_fields = array('collectionNo', 'orNo');
        //change end
        
        
        // arID
        // totalAmount
        // collectionMethod (cash or check)
        // bankAccountID
        // checkNo
        // checkDate

        // collectionID    collectionNo    soDate  bankAccountID  grossAmount discount    netAmount   paymentTerm    dueDate amountPaid  balance datePaid    createdBy   dateCreated confirmedBy dateConfirmed   cancelledBy dateCancelled   dateInserted    dateLastEdit    dateDeleted deletedBy   remarks status 1: pending; 2: confirm; 3: partial delivery; 4: full
        

        // check role
        if ($this->roles['create']) {
            $this->record->table = $this->table;
            $this->record->fields = array();
            
            foreach ($table_fields as $fld) {

                $this->record->fields[$fld] = trim($this->input->post($fld));               
            }
     
            //change start
            $this->record->fields['arID']           = trim($this->input->post('arID2'));

            $this->record->fields['collectionDate']        = date('Y-m-d', strtotime(trim($this->input->post('collectionDate'))));
            $this->record->fields['orDate']         = date('Y-m-d', strtotime(trim($this->input->post('orDate'))));
            

            // $this->record->fields['createdBy']          = $this->session->userdata('current_user')->userID; 

            //====================
            $this->record->fields['totalAmount']          = trim($this->input->post('totalAmount'));

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

                // id  collectionID    productID   qty price   amount  delQty  delStatus   dateInserted    dateLastEdit    dateDeleted deletedBy   status 1: pending; 2: confirm; 3: partial delivery; 4: full
                $total = 0;
                if (!empty($_SESSION['collection_details'])) {
                    $batch = array();
                    foreach($_SESSION['collection_details'] as $product){
                        $info = array();
                        $info['collectionID']           = $this->record->field->collectionID;
                        $info['drID']         = $product['drID'];                       
                        $info['soID']            = $product['soID'];
                        $info['arDetailsID']         = $product['arDetailsID'];
                        $info['amount']         = $product['amount'];
                        $info['dateInserted']   = date('Y-m-d H:i:s');
                        $info['status'] = 1;
                        $batch[] = $info;

                    }
                    $this->db->insert_batch('collection_details', $batch);
                }
                //change end
                
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
            
            // set table
            $this->record->table = $this->table;
            // set fields for the current table
            $this->record->setFields();
            // extend fields - join tables      

            

            // set joins                   
            
                                
            // set where
            $this->record->where[$this->table.'.'.$this->pfield] = $id;
            $this->record->where[$this->table.'.status > 0'];

            // execute retrieve
            $this->record->retrieve();            
            $data['rec'] = $this->record->field;
            // ----------------------------------------------------------------------------------

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
        $id = $this->encrypter->decode($this->input->post('collectionID'));
        //change start
        $table_fields = array('collectionNo', 'orNo');
        //change end
        
        // check roles
        if ($this->roles['edit']) {
            $this->record->table = $this->table;
            $this->record->fields = array();
            
            foreach ($table_fields as $fld) {

                $this->record->fields[$fld] = trim($this->input->post($fld));
                          
            }

            //change start
            $this->record->fields['collectionDate']        = date('Y-m-d', strtotime(trim($this->input->post('collectionDate'))));
            $this->record->fields['orDate']         = date('Y-m-d', strtotime(trim($this->input->post('orDate'))));
            

            // $this->record->fields['dateInserted']       = date('Y-m-d H:i:s');   
            $this->record->fields['dateLastEdit']       = date('Y-m-d H:i:s'); 
            //change end
            
            $this->record->pfield = $this->pfield;
            $this->record->pk = $id;

            
            // field logs here
            $wasChange = $this->log_model->field_logs($data['current_module']['module_label'], $this->table, $this->pfield, $id, 'Update', $this->record->fields);
            
            if ($this->record->update()) {
                //change start

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
            // $this->record->fields[]  = 'collection_details.*';
            // $this->record->fields[] = 'products.*';
            // $this->record->fields[] = 'bank_accounts.accountName';
            $this->record->fields[] = 'customers.companyName';
            $this->record->fields[] = 'arheaders.invoiceNo';
            $this->record->fields[] = 'arheaders.arID';


            
            // set joins                   
            //   
            $this->record->joins[]  = array('arheaders',$this->table.'.arID=arheaders.arID','left');  
            $this->record->joins[]  = array('customers','arheaders.custID=customers.custID','left');  
                                
            // set where
            $this->record->where[$this->table.'.'.$this->pfield] = $id;
            $this->record->where[$this->table.'.status > 0'];
            
            // execute retrieve
            $this->record->retrieve();

            // ----------------------------------------------------------------------------------
            $data['rec'] = $this->record->field;
            // var_dump($data['rec']);
            // var_dump($data['headers']); 
            // echo $this->db->last_query();die();
            //change end
        $this->db->select('ardetails.*');
        $this->db->select('collection_details.amount as amount');
        $this->db->select('arheaders.invoiceNo');
        $this->db->select('soheaders.soNo');
        $this->db->select('soheaders.deliveryDate');
        $this->db->select('drheaders.drNo');
        $this->db->select('drheaders.totalAmount as drTotalAmount');

         

        
        $this->db->from('ardetails');
        $this->db->join('arheaders', 'ardetails.arID=arheaders.arID', 'left');
        $this->db->join('soheaders', 'ardetails.soID=soheaders.soID', 'left');
        $this->db->join('drheaders', 'ardetails.drID=drheaders.drID', 'left');
        $this->db->join('collection_details', 'ardetails.id=collection_details.arDetailsID', 'left');
            $this->db->where('collection_details.collectionID', $id);
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

    public function show()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;

        //change start
        $condition_fields = array(
            array('variable'=>'collectionNo', 'field'=>$this->table.'.collectionNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'collectionDate', 'field'=>$this->table.'.collectionDate', 'default_value'=>'', 'operator'=>'where'),
            array('variable'=>'orNo', 'field'=>$this->table.'.orNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'soNo', 'field'=>'soheaders.soNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'drNo', 'field'=>'drheaders.drNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
        );
        
        // sorting fields
        $sorting_fields = array('collectionNo'=>'desc','collectionDate'=>'desc');
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
        
        $this->db->select('arheaders.invoiceNo');
    

        // from
        $this->db->from($this->table);
        
        
        // join     
        
        $this->db->join('arheaders',$this->table.'.arID=arheaders.arID','left');
        
        
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
        
        $this->db->select('arheaders.invoiceNo');
    

        // from
        $this->db->from($this->table);
        
        
        // join     
        
        $this->db->join('arheaders',$this->table.'.arID=arheaders.arID','left');
        
        
        // where
        $this->db->where($this->table.'.status >', 0);
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
            array('variable'=>'collectionNo', 'field'=>$this->table.'.collectionNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'collectionDate', 'field'=>$this->table.'.collectionDate', 'default_value'=>'', 'operator'=>'where'),
            array('variable'=>'orNo', 'field'=>$this->table.'.orNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'soNo', 'field'=>'soheaders.soNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'drNo', 'field'=>'drheaders.drNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
        );
        
        // sorting fields
        $sorting_fields = array('collectionNo'=>'desc','collectionDate'=>'desc');
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
        

        // Second Query for retrieving data
        //===================================
        //change start
        // $data['datePosted'] = $datePosted;
        
        // select
        $this->db->select($this->table.'.*');
        
        $this->db->select('arheaders.invoiceNo');
    

        // from
        $this->db->from($this->table);
        
        
        // join     
        
        $this->db->join('arheaders',$this->table.'.arID=arheaders.arID','left');
        
        
        // where
        $this->db->where($this->table.'.status >', 0);
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
        //change start
        $condition_fields = array(
            array('variable'=>'collectionNo', 'field'=>$this->table.'.collectionNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'collectionDate', 'field'=>$this->table.'.collectionDate', 'default_value'=>'', 'operator'=>'where'),
            array('variable'=>'orNo', 'field'=>$this->table.'.orNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'soNo', 'field'=>'soheaders.soNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'drNo', 'field'=>'drheaders.drNo', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
        );
        
        // sorting fields
        $sorting_fields = array('collectionNo'=>'desc','collectionDate'=>'desc');
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
        

        // Second Query for retrieving data
        //===================================
        //change start
        // $data['datePosted'] = $datePosted;
        
        // select
        $this->db->select($this->table.'.*');
        
        $this->db->select('arheaders.invoiceNo');
    

        // from
        $this->db->from($this->table);
        
        
        // join     
        
        $this->db->join('arheaders',$this->table.'.arID=arheaders.arID','left');
        
        
        // where
        $this->db->where($this->table.'.status >', 0);
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
                $collectionDate = $row->collectionDate == '0000-00-00' ? '' : date("M d, Y",strtotime($row->collectionDate));
                $timeDelivered = $row->timeDelivered == '0000-00-00' ? '' : date("M d, Y",strtotime($row->timeDelivered));
                // $deliveryTerm = $row->deliveryTerm == '' ? '' : $row->deliveryTerm.'-Working Days';

                $data .= "<Row>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $ctr . ".</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->collectionNo . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $collectionDate . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->accountName . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->invoiceNo . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->orNo . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->checkNo. "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->bankAccountID . "</Data></Cell>";
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
        $this->db->where('collectionNo', trim($this->input->post('collectionNo')));
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
            array('column_header'=>'SO No','column_field'=>'','width'=>'w-10','text-align'=>'text-left','align'=>'left'),
            array('column_header'=>'DR No','column_field'=>'','width'=>'w-10','text-align'=>'text-left','align'=>'left'),
            array('column_header'=>'Details','column_field'=>'','width'=>'w-35','text-align'=>'text-left','align'=>'left'),
            array('column_header'=>'Amount','column_field'=>'','width'=>'w-10','text-align'=>'text-right','align'=>'right'),
          
        );
        //display the value of session key
        $display = array(
            array('align'=>'left','field'=>'soNo'),
            array('align'=>'left','field'=>'drNo'),
            array('align'=>'left','field'=>'invoiceDetails'),
            array('align'=>'right','field'=>'amount'),
          
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
                    if($text == "amount") {
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
        $collectionID = $this->input->post('collectionID');
        $closeReason = trim($this->input->post('closeReason'));
        // echo $closeReason; die();

        if ($status == 4) {

            $this->db->set('status', 4);
            $this->db->set('closedBy', $this->session->userdata('current_user')->userID);
            $this->db->set('dateClosed', date('Y-m-d H:i:s'));
            $this->db->set('closeReason', $closeReason);

        } 
                        
        $this->db->where('collectionID', $collectionID );
        $this->db->update($this->table);

        echo true;
    }




    public function confirm_record()
    {
        $status = $this->input->post('status');
        $id = $this->input->post('id');


        $message = '';
        
        if ($status == 2) {

            $this->db->set('status', 2);
            // $this->db->set('confirmedBy', $this->session->userdata('current_user')->userID);
            // $this->db->set('dateConfirmed', date('Y-m-d H:i:s'));

        } else if ($status == 0) {
            $this->db->set('status', 0);
            // $this->db->set('cancelledBy', $this->session->userdata('current_user')->userID);
            // $this->db->set('dateCancelled', date('Y-m-d H:i:s'));
        }
                        
        $this->db->where($this->pfield, $id);
        $this->db->update($this->table);

        if ($status == 2) {

            

            $this->db->select('collection_details.*');
            $this->db->from('collection_details');
            
            $this->db->where('collection_details.collectionID', $id);
            // $this->db->where('soheaders.status >', 0);
            $records = $this->db->get()->result();
            
            if (!empty($records)) {
                foreach ($records as $row) {
                    
                    $this->db->set('soheaders.amountPaid', 'amountPaid+'.$row->amount, false);
                    $this->db->set('soheaders.balance', 'balance-'.$row->amount, false);
                    $this->db->where('soheaders.soID', $row->soID);
                    $this->db->update('soheaders');


                    $this->db->where('soheaders.soID', $row->soID);
                    $rec = $this->db->get('soheaders', 1)->row();
                    if ($rec->balance <= 0) {
                        $this->db->set('collectionStatus', 4);
                        $this->db->where('soheaders.soID', $rec->soID);
                        $this->db->update('soheaders');
                    } else {
                        $this->db->set('collectionStatus', 3);
                        $this->db->where('soheaders.soID', $rec->soID);
                        $this->db->update('soheaders');
                    }


                    $this->db->set('ardetails.amountPaid', 'amountPaid+'.$row->amount, false);
                    $this->db->set('ardetails.balance', 'balance-'.$row->amount, false);
                    $this->db->where('ardetails.id', $row->arDetailsID);
                    $this->db->update('ardetails');

                    

                    $this->db->select('ardetails.*');
                    $this->db->where('ardetails.id', $row->arDetailsID);
                    $detail = $this->db->get('ardetails', 1)->row();

                    if ($detail->balance <= 0) {

                        $this->db->set('status', 4);
                        $this->db->where('ardetails.id', $detail->id);
                        $this->db->update('ardetails');

                    } else {

                        $this->db->set('status', 3);
                        $this->db->where('ardetails.id', $detail->id);
                        $this->db->update('ardetails');
                    }


                    //add totalAmount in payment_headers
                    $this->db->set('collection_headers.totalAmount', 'totalAmount+'.$row->amount, false);
                    $this->db->where('collection_headers.collectionID', $row->collectionID);
                    $this->db->update('collection_headers');

                }

                $this->db->select('collection_headers.arID');
                $this->db->select('arheaders.invoiceNo');
                $this->db->from('collection_headers');
                $this->db->join('arheaders', 'collection_headers.arID=arheaders.arID', 'left');
                $this->db->where('collection_headers.collectionID', $id);
                $rec = $this->db->get()->row();

                
                $this->db->from('ardetails');
                $this->db->where('ardetails.arID', $rec->arID);
                $this->db->where_in('status', array(0,1,2,3));
                $res_count = $this->db->count_all_results();


                $this->db->select_sum('ardetails.amountPaid');
                $this->db->from('ardetails');
                $this->db->where('ardetails.arID', $rec->arID);
                $totalPaid = $this->db->get()->row()->amountPaid;

                if ($res_count == 0) {

                    $this->db->set('status', 4);
                    $this->db->set('amountPaid', $totalPaid);
                    $this->db->where('arheaders.arID', $rec->arID);
                    $this->db->update('arheaders');

                    $message = 'Sales Invoice '.$rec->invoiceNo.' Fully Paid.';
                    $this->record->send_notification($message, $rec->arID);

                } else {

                    $this->db->set('status', 3);
                    $this->db->set('amountPaid', $totalPaid);
                    $this->db->where('arheaders.arID', $rec->arID);
                    $this->db->update('arheaders');
                }
            }




            echo 1;
        }

    }





    public function display_session()
    {               
        echo var_dump($_SESSION);
    }


}