<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
 * Controller Template ver 0.2 
 * To use just search change start change end comments
 * Add your code inside these tags
 */
class Inventory_drugs extends CI_Controller
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
    var $default_category;

	public function __construct()
	{
		parent::__construct();
		//check if you are using $record or $records, just replace all
		$this->load->model('generic_model', 'record');

		//change start
		//parent module, controller, table, pfield, logfield
		$this->_init_setup('Reports', 'inventory_drugs', 'stockcards', 'id', 'id');
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
        $this->default_category = $this->config_model->getConfig('Drugs and Medicines');
                                                                                                  
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
        $this->data['current_module'] = $this->modules[$this->module]['sub']['Inventory drug']; // defines the current sub module
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
        $this->roles['print']   = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Print '.$this->data['current_module']['module_label']);
        $this->roles['export']  = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Export '.$this->data['current_module']['module_label']);
    }

    private function _in_used($id = 0)
    {
        //change start
        $tables = array(
            'users' => 'groupID'
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
        $table_fields = array('suppID', 'modeProcurement', 'deliveryPlace','deliveryTerm','paymentTerm','grossAmount','discount','totalAmount','clusterFund','remarks');
        //change end
        
        // check role
        if ($this->roles['create']) {
            $this->record->table = $this->table;
            $this->record->fields = array();
            
            foreach ($table_fields as $fld) {
                $this->record->fields[$fld] = trim($this->input->post($fld));
            }

            //change start
        	$this->record->fields['poDate']    			= date('Y-m-d H:i:s', strtotime(trim($this->input->post('poDate'))));	 
        	$this->record->fields['deliveryDate']    	= date('Y-m-d H:i:s', strtotime(trim($this->input->post('deliveryDate'))));	 
           	$this->record->fields['createdBy']    		= $this->session->userdata('current_user')->userID;              
			$this->record->fields['dateCreated']		= date('Y-m-d H:i:s');
            //change end
            
            if ($this->record->save()) {
                $this->record->fields = array();
                //change start
                // $this->record->where['postNo']  = $genNo;
                //change end
                $id = $this->record->where[$this->pfield] = $this->db->insert_id();
                $this->record->retrieve();

                //change start
                //change end

                
                // record logs
                $pfield = $this->pfield;
                
                $logs = "Record - " .$genNo;
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

            $this->record->fields[] = 'podetails.*';
            $this->record->fields[] = 'items.*';
            $this->record->fields[] = 'suppliers.suppName';
            $this->record->fields[] = 'suppliers.streetNo';
            $this->record->fields[] = 'barangays.barangay';
            $this->record->fields[] = 'cities.city';
            $this->record->fields[] = 'provinces.province';
					
			// set joins

            $this->record->joins[]  = array('podetails',$this->table.'.poID=podetails.poID','left');     
            $this->record->joins[]  = array('items','podetails.itemID=items.itemID','left');           
            $this->record->joins[]  = array('suppliers',$this->table.'.suppID=suppliers.suppID','left');            
            $this->record->joins[]  = array('barangays','suppliers.barangayID=barangays.barangayID','left');
            $this->record->joins[]  = array('cities','suppliers.cityID=cities.cityID','left');
            $this->record->joins[]  = array('provinces','suppliers.provinceID=provinces.provinceID','left');
					
			// set where
			$this->record->where[$this->table.'.'.$this->pfield] = $id;
			// execute retrieve
			$this->record->retrieve();
			// ----------------------------------------------------------------------------------
			$data['rec'] = $this->record->field;
			// var_dump($data['rec']);
			//change end

            //change start
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
        $table_fields = array('suppID', 'modeProcurement', 'deliveryPlace','deliveryTerm','paymentTerm','grossAmount','discount','totalAmount','clusterFund','remarks','status');
        //change end
        
        // check roles
        if ($this->roles['edit']) {
            $this->record->table = $this->table;
            $this->record->fields = array();
            
            foreach ($table_fields as $fld) {
                $this->record->fields[$fld] = trim($this->input->post($fld));
            }

            //change start
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
                if (! $this->_in_used($id)) {
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
                    } else {
                        // error
                        $data["class"] = "danger";
                        $data["msg"] = "Error in deleting the " . $this->data['current_module']['module_label'] . "!";
                        $data["urlredirect"] = "";
                        $this->load->view("header", $data);
                        $this->load->view("message");
                        $this->load->view("footer");
                    }
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

   

    public function show()
    {

    
        // load submenu
        $this->submenu();
        $data = $this->data;

        //=========================
        //change start
        $condition_fields = array(
			array ('variable' => 'poNo', 'field' => $this->table . '.poNo', 'default_value' => '', 'operator' => 'where' ), 
			array ('variable' => 'poDate', 'field' => $this->table . '.poDate', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'suppName', 'field' =>  'suppliers.suppName', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'modeProcurement', 'field' => $this->table . '.modeProcurement', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'deliveryPlace', 'field' => $this->table . '.deliveryPlace', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'deliveryDate', 'field' => $this->table . '.deliveryDate', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'deliveryTerm', 'field' => $this->table . '.deliveryTerm', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'paymentTerm', 'field' => $this->table . '.paymentTerm', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'status', 'field' => $this->table . '.status', 'default_value' => '', 'operator' => 'where' ) 
        );
        
        // sorting fields
        $sorting_fields = array('id'=>'asc');
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
		
    

    	// from
        $this->db->from($this->table);
        $ancillaryID = $this->input->post('ancillaryID');
        $itemID = $this->input->post('itemID');
        $this->db->where('ancillaryID', $ancillaryID);
        $this->db->where('itemID', $itemID);    
        $catID = $this->input->post('catID');
        
        
        
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
        // select
        $this->db->select($this->table.'.*');
        

    	// from
        $this->db->from($this->table);
        $ancillaryID = $this->input->post('ancillaryID');
        $itemID = $this->input->post('itemID');
        $this->db->where('ancillaryID', $ancillaryID);
        $this->db->where('itemID', $itemID);    
        $catID = $this->input->post('catID');

        // echo json_encode($catID);
        // die();

    
    	
    	// join  
    	//$this->db->join('suppliers',$this->table.'.suppID=suppliers.suppID','left'); 	
    	
    	
    	// where
    	//$this->db->where($this->table.'.status != -100');
        //change end
        //===================================
        

        // set conditions here
     
        
        
        // get
        $data['records'] = $this->db->get()->result();


       
        

    
        $this->db->select('ancillaries.*');
        $this->db->from('ancillaries');
        //$this->db->where_not_in('ancillaries.status',$this->deleted_status);
        $data['ancillaries'] = $this->db->get()->result();

        //$medicine_id = $this->config_model->getConfig('Drugs and Medicines');
        //check the category
        $medicine_id = $catID;
        $this->db->select('items.*');
        $this->db->where('items.catID', $medicine_id);
        $this->db->from('items');
        $data['items'] = $this->db->get()->result();

        //echo json_encode($data['items']);
        //die();


        //months
        $months = [];
        for($m = 1; $m <= 12; $m++) {
            $months[] = date('F', mktime(0,0,0,$m, 1, date('Y')));
        }
        $data['months'] = $months;
        //years
        $default_end_date = $this->config_model->getConfig("Default end date");
        $years = range(date("Y"), $default_end_date);
        $data['years'] = $years;

    
        if(!empty($_POST['month'])) {
            // echo json_encode($_POST);
            // die();
            $ancillaryID = $this->input->post('ancillaryID');
            $itemID = $this->input->post("itemID");
            $month = $this->input->post("month");
            $year = $this->input->post("year");
            $catID = $this->input->post('catID');

            
            $firstDay = $this->formatDateFirstDay($year, $month);

            $lastDay = $this->formatDateLastDay($year, $month);  

            $this->db->select('stockcards.*');
            $this->db->select('ancillaries.division');
            $this->db->select('items.name as item_name');
            $this->db->where('DATE(stockcards.dateInserted) >= ', $firstDay);
            $this->db->where('DATE(stockcards.dateInserted) <= ', $lastDay);
           
           
            $this->db->from('stockcards');
            $this->db->group_by('stockcards.itemID');
            $this->db->join ( 'items', 'stockcards.itemID=items.itemID', 'left' );
            $this->db->join ( 'ancillaries', 'stockcards.ancillaryID=ancillaries.ancillaryID', 'left' );
            //$id = $this->default_category;
            //filter to category drugs and medicines only
            // $this->db->where('items.catID',$this->default_category);
            $this->db->where('items.catID',$catID);
            $this->db->where('ancillaries.ancillaryID', $ancillaryID);

            $stockcards = $this->db->get()->result();

          //echo json_encode($stockcards);
           // die();

    

            $data['records'] = $stockcards;

            $data['from_date'] = $firstDay;
            $data['to_date'] = $lastDay;

            $data['year'] = $year;
            $month = (int) $month;

           
            $data['month'] =  date('F', mktime(0,0,0,$month, 1, date('Y')));

            $data['itemID'] = $itemID;
            $data['ancillaryID'] = $ancillaryID;



            $_details = [];
            $temp = [];
            foreach($stockcards as $key => $value) {
                foreach($value as $k => $v) {
                    if($k == "item_name") {
                        $temp["item_name"] = $v;                    
                    }
                    if($k == "itemID") {
                        $temp['id'] = $v;
                        //getStockcardReport($id, $firstDay, $lastDay)
                        //$temp['data'] = $this->getStockcardReport($v, $firstDay, $lastDay);
                    }
                   
                }
                $_details[] = $temp;
            }
            
            $new_data = [];
            foreach($_details as $key => $value) {            
                $t['item_name'] = $value['item_name'];
                $t['data'] = $this->getStockcardReport($value['id'], $firstDay, $lastDay, $ancillaryID);
                $new_data[] = $t;
            }
            $data['stockcards'] = $new_data;

           
        }

        //get category
        $this->db->select('category.catID');
        $this->db->select('category.category');
        $this->db->select('category.description');
        $this->db->from('category');
        $this->db->where('category.status !=', -100);
        $data['category'] = $this->db->get()->result();
        $data['month_id'] =  $this->input->post("month");;
        $data['catID'] = $catID;
        
        // load views
        $this->load->view('header', $data);
        $this->load->view($this->module_path . '/list');
        $this->load->view('footer');
    }

    

    public function formatDateFirstDay($year, $month)
    {
        $d = new DateTime($year.'-'.$month.'-19');
        $d->modify('first day of this month');
        $firstDay = $d->format('Y-m-d');

        return $firstDay;
    }

    public function formatDateLastDay($year, $month)
    {

        $d = new DateTime($year.'-'.$month.'-19');
        $d->modify('last day of this month');
        $lastDay = $d->format('Y-m-d');

        return $lastDay;
    }

    public function generatePdf($id = null, $month = null, $year = null, $ancillaryID = null, $catID = null)
    {
       
        //date('F', mktime(0,0,0,$m, 1, date('Y')));
      
        // load submenu
        $this->submenu();
        $data = $this->data;

      
        $_number_month = date('m',strtotime($month));
      
        $id = $this->encrypter->decode($id);


        $firstDay = $this->formatDateFirstDay($year, $_number_month);

        $lastDay = $this->formatDateLastDay($year, $_number_month);  

        $firstDay = $this->formatDateFirstDay($year, $_number_month);

        $lastDay = $this->formatDateLastDay($year, $_number_month);  

        $this->db->select('stockcards.*');
        $this->db->select('ancillaries.division');
        $this->db->select('items.name as item_name');
        $this->db->where('DATE(stockcards.dateInserted) >= ', $firstDay);
        $this->db->where('DATE(stockcards.dateInserted) <= ', $lastDay);
        // if($itemID) {
        //     $this->db->where('items.itemID', $itemID);
        // }
        // $this->db->where('ancillaries.ancillaryID', $ancillaryID);
       
        $this->db->from('stockcards');
        $this->db->group_by('stockcards.itemID');
        $this->db->join('items', 'stockcards.itemID=items.itemID', 'left' );
        $this->db->join('ancillaries', 'stockcards.ancillaryID=ancillaries.ancillaryID', 'left' );
       // $this->db->where('items.catID',$this->default_category);
       $this->db->where('items.catID',$catID);
        $this->db->where('ancillaries.ancillaryID', $ancillaryID);

        $stockcards = $this->db->get()->result();

      
       

        $data['from_date'] = $firstDay;
        $data['to_date'] = $lastDay;

        $data['year'] = $year;
        $data['month'] = $month;

    
       
        $_details = [];
        $temp = [];
        foreach($stockcards as $key => $value) {
            foreach($value as $k => $v) {
                if($k == "item_name") {
                    $temp["item_name"] = $v;                    
                }
                if($k == "itemID") {
                    $temp['id'] = $v;
                    //getStockcardReport($id, $firstDay, $lastDay)
                    $temp['data'] = $this->getStockcardReport($v, $firstDay, $lastDay, $ancillaryID);
                }
               
            }
            $_details[] = $temp;
        }

        $data['stockcards'] = $_details;

    
        $data['pharmacist_III'] = $this->getSignatory('1002');
        $data['chief_of_medical'] = $this->getSignatory('1005');
        $data['medical_center_chief_1'] = $this->getSignatory('1006');


        $data['designation_pharmacist_III'] = $this->getSignatoryDesignation('1002');
        $data['designation_chief_of_medical'] = $this->getSignatoryDesignation('1005');
        $data['designation_medical_center_chief_1'] = $this->getSignatoryDesignation('1006');

        

    
        // check roles
        if ($this->roles['view']) {
            
            $month = $months[] = date('F', mktime(0,0,0,$month, 1, date('Y')));
            $data['pdf_paging'] = TRUE;
            $data['title']      = "INVENTORY OF DRUGS AND MEDICINES";
            $data['modulename'] = "INVENTORY OF DRUGS AND MEDICINES";            
            $data['ancillary'] 	= "FOR THE MONTHS OF ".strtoupper($month).' '.$year;            
            
            //-------------------------------
            // load pdf class
            $this->load->library('mpdf');
            // load pdf class
            $this->mpdf->mpdf('en-GB',array(215.9,330.2),10,'Gotham Narrow',10,10,60,10,10,25,'L');
            $this->mpdf->setTitle($data['title']);
            $this->mpdf->SetDisplayMode('fullpage');
            $this->mpdf->shrink_tables_to_fit = 1;
            $this->mpdf->SetWatermarkImage(base_url().'images/logo/watermark.png');
            $this->mpdf->watermark_font = 'DejaVuSansCondensed';
    
            // content
            $header = $this->load->view('print_pdf_header_inventory_drug', $data, TRUE);
            $this->mpdf->SetHTMLHeader($header);
    
            $footer = $this->load->view('print_pdf_footer', $data, TRUE);
            $this->mpdf->SetHTMLFooter($footer);
    
            $html   = $this->load->view($this->module_path.'/print_record', $data, TRUE);
            $this->mpdf->WriteHTML($html);
            //$this->mpdf->Output();
            $this->mpdf->Output("INVENTORY_DRUGS.pdf","I");
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

    public function getStockcardReport($itemID, $firstDay, $lastDay, $ancillaryID)
    {
        //$firstDay = $this->formatDateFirstDay($year, $month);

        //$lastDay = $this->formatDateLastDay($year, $month);  

        $this->db->select('stockcards.*');
        $this->db->select('inventory.qty');
        $this->db->select('ancillaries.division');
        $this->db->select('items.name as item_name');
        $this->db->select('items.avecost');
        $this->db->where('DATE(stockcards.dateInserted) >= ', $firstDay);
        $this->db->where('DATE(stockcards.dateInserted) <= ', $lastDay);
        $this->db->where('stockcards.ancillaryID', $ancillaryID);
        $this->db->from('stockcards');
        $this->db->order_by('stockcards.id', 'ASC');
        $this->db->group_by('stockcards.dateInserted');
        $this->db->join ( 'items', 'stockcards.itemID=items.itemID', 'left' );
        $this->db->join ( 'inventory', 'items.itemID=inventory.itemID', 'left' );
        $this->db->join ( 'ancillaries', 'stockcards.ancillaryID=ancillaries.ancillaryID', 'left' );
        $this->db->where('stockcards.itemID', $itemID);

        $stockcards  = $this->db->get()->result();

       // echo json_encode($stockcards);
        //die();

        $data['stockcard'] = $stockcards;

        // echo json_encode($data['stockcard']);
        // die();
        
        $balanced_beginning = [];
        $receive_total_increase =  0;
        $total_decrease =  0;
        //$receive_total_qty = 0;
        //balance beginning
        $beg_unit_cost = 0;
        $beg_quantity = 0;
        $beg_total_cost = 0;
        $begbal_total = 0 ;
        //receive
        $receive_unit_cost = 0;
        $receive_quantity = 0;
        $receive_total_cost = 0;
        $receive_total = 0;
    
         //stock on hand
        $total_stock_beg_unit_cost = 0;
        $total_stock_beg_quantity = 0;
        $total_stock_total_cost = 0;
        $stock_total = 0; 
        //total stock issued
        $total_stock_issued_beg_unit_cost = 0;
        $total_stock_issued_beg_quantity = 0;
        $total_stock_issued_total_cost = 0;
        $issued_total = 0;

         //balance end
         $balanced_end_unit_cost = 0;
         $balanced_end_beg_quantity = 0;
         $balanced_end_total_cost = 0;
         $balanced_total = 0;
         $total_unit_cost = 0;

         $receive_quantity = 0;
        
        
        for($i = 0; $i < count($stockcards); $i++)
        {
            //balance beginning
            //$beg_unit_cost = $stockcards[0]->begBal;
            //$beg_quantity = $stockcards[0]->qty;
            //$beg_total_cost = $stockcards[0]->begBal * $stockcards[0]->qty;

            
            
            
            $beg_quantity = $stockcards[0]->begBal;
            //check if the quantity is zero unit cost must be sezo
            $beg_unit_cost = $beg_quantity ? $stockcards[0]->avecost : 0;
            //$beg_unit_cost = $stockcards[0]->avecost;
            $beg_total_cost = $stockcards[0]->begBal * $stockcards[0]->avecost;
            $beg_total_cost = $beg_total_cost += $beg_unit_cost;

            
            $balanced_beginning['balance_beginning']['qty'] = $beg_quantity;
            $balanced_beginning['balance_beginning']['unit_cost'] = $beg_unit_cost;
            $balanced_beginning['balance_beginning']['total_cost'] = $beg_total_cost;
            $balanced_beginning['balance_beginning']['total'] = $beg_total_cost;

            //receive
           // $balanced_beginning['receive']['increase'] = $total_increase += $stockcards[$i]->increase;
            $receive_quantity = $receive_quantity += $stockcards[$i]->increase;
            //$total_stock_issued_qty =  $total_stock_issued_qty += $stockcards[$i]->decrease;
            //check if zero quantity display zero
            $receive_unit_cost = $receive_quantity ? $receive_unit_cost += $stockcards[$i]->avecost : 0;
            $receive_total_cost = $receive_quantity * $receive_unit_cost;
            $receive_total = $receive_total += $receive_total_cost;
           
            //total all quantity
            $balanced_beginning['receive']['qty'] = $receive_quantity;
            $balanced_beginning['receive']['unit_cost'] = $receive_unit_cost;
            //multitply unit cost by qty
            $balanced_beginning['receive']['total_cost'] = $receive_total_cost;
            $balanced_beginning['receive']['total'] = $receive_total_cost;



            $total_stock_beg_quantity = $beg_quantity + $receive_quantity;
            $total_stock_beg_unit_cost = $beg_unit_cost + $receive_unit_cost;
            $total_stock_total_cost = $receive_total_cost + $beg_total_cost;
            //stock on hand
            $balanced_beginning['total_stock_on_hand']['qty'] = $total_stock_beg_quantity;
            $balanced_beginning['total_stock_on_hand']['unit_cost'] = $total_stock_beg_unit_cost;
            $balanced_beginning['total_stock_on_hand']['total_cost'] = $total_stock_total_cost;
           // $stock_total = $total_stock_total_cost += $total_stock_total_cost;
            $balanced_beginning['total_stock_on_hand']['total'] = $stock_total += $total_stock_total_cost;


            $total_stock_issued_qty =  $total_stock_issued_qty += $stockcards[$i]->decrease;
           // $total_stock_issued_beg_quantity += $stockcards[$i]->qty;
           //check if the quatity is zero set unit cost to zero
           $total_stock_issued_unit_cost = $total_stock_issued_qty ? $total_stock_issued_unit_cost =+ $stockcards[$i]->avecost : 0;
            //$total_stock_issued_unit_cost = $total_stock_issued_unit_cost =+ $stockcards[$i]->avecost;
            $total_stock_issued_total_cost = $total_stock_issued_unit_cost * $total_stock_issued_qty;

            //total stock issued 
        
            $balanced_beginning['total_stock_issued']['qty'] = $total_stock_issued_qty;
            $balanced_beginning['total_stock_issued']['unit_cost'] = $total_stock_issued_unit_cost;
            $balanced_beginning['total_stock_issued']['total_cost'] = $total_stock_issued_total_cost;
            // $issued_total =  $issued_total += $total_stock_issued_total_cost;
            // $balanced_beginning['total_stock_issued']['total'] = $issued_total;
           // $issued_total =  $issued_total += $total_stock_issued_total_cost;
            $balanced_beginning['total_stock_issued']['total'] = $total_stock_issued_total_cost;


           
            $balanced_end_beg_quantity = $total_stock_beg_quantity - $total_stock_issued_qty;
            $balanced_end_unit_cost = $stockcards[$i]->avecost;
           // $balanced_end_unit_cost = $total_stock_beg_unit_cost - $total_stock_issued_unit_cost;
            $balanced_end_total_cost = $balanced_end_beg_quantity * $balanced_end_unit_cost;
           

            //balanced end
            $balanced_beginning['balanced_end']['qty'] = $balanced_end_beg_quantity;
            $balanced_beginning['balanced_end']['unit_cost'] = $balanced_end_unit_cost;
            $balanced_beginning['balanced_end']['total_cost'] = $balanced_end_total_cost;
            $balanced_total = $balanced_total += $balanced_end_total_cost;
            $balanced_beginning['balanced_end']['total'] = $balanced_end_total_cost;
            
        }

        return $balanced_beginning;

        //echo json_encode($balanced_beginning);
        //die();

    }
    

    public function printlist()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;

        //=========================
        //change start
		$condition_fields = array(
			array ('variable' => 'poNo', 'field' => $this->table . '.poNo', 'default_value' => '', 'operator' => 'where' ), 
			array ('variable' => 'poDate', 'field' => $this->table . '.poDate', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'suppName', 'field' =>  'suppliers.suppName', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'modeProcurement', 'field' => $this->table . '.modeProcurement', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'deliveryPlace', 'field' => $this->table . '.deliveryPlace', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'deliveryDate', 'field' => $this->table . '.deliveryDate', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'deliveryTerm', 'field' => $this->table . '.deliveryTerm', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'paymentTerm', 'field' => $this->table . '.paymentTerm', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'status', 'field' => $this->table . '.status', 'default_value' => '', 'operator' => 'where' ) 
		);
        
        // sorting fields
        $sorting_fields = array('poNo'=>'asc','poDate'=>'asc');
        //change end
        //=========================
        
        $controller = $this->uri->segment(1); // if print per page

        // uncomment if want to print with no offset
     	// if ($this->uri->segment(3))
	    // 	$offset = $this->uri->segment(3);
	    // else
	    // 	$offset = 0;
        
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
        // select
    	$this->db->select($this->table.'.*');
	 	$this->db->select('suppliers.suppName');
    

    	// from
    	$this->db->from($this->table);
    	
    	// join   	
	 	$this->db->join('suppliers',$this->table.'.suppID=suppliers.suppID','left'); 
    	
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

    public function exportlist($id = null, $month = null, $year = null, $ancillaryID = null, $catID = null)
    {
        $id = $this->encrypter->decode($id);
        $_number_month = date('m',strtotime($month));

        $firstDay = $this->formatDateFirstDay($year, $_number_month);

        $lastDay = $this->formatDateLastDay($year, $_number_month);  

        $firstDay = $this->formatDateFirstDay($year, $_number_month);

        $lastDay = $this->formatDateLastDay($year, $_number_month);  

        $this->db->select('stockcards.*');
        $this->db->select('ancillaries.division');
        $this->db->select('items.name as item_name');
        $this->db->where('DATE(stockcards.dateInserted) >= ', $firstDay);
        $this->db->where('DATE(stockcards.dateInserted) <= ', $lastDay);
        // if($itemID) {
        //     $this->db->where('items.itemID', $itemID);
        // }
        // $this->db->where('ancillaries.ancillaryID', $ancillaryID);
       
        $this->db->from('stockcards');
        $this->db->group_by('stockcards.itemID');
        $this->db->join('items', 'stockcards.itemID=items.itemID', 'left' );
        $this->db->join('ancillaries', 'stockcards.ancillaryID=ancillaries.ancillaryID', 'left' );
       // $this->db->where('items.catID',$this->default_category);
        $this->db->where('items.catID',$catID);
        $this->db->where('ancillaries.ancillaryID', $ancillaryID);

        $stockcards = $this->db->get()->result();
   

        $data['from_date'] = $firstDay;
        $data['to_date'] = $lastDay;

        $data['year'] = $year;
        $data['month'] = $month;

    
       
        $_details = [];
        $temp = [];
        foreach($stockcards as $key => $value) {
            foreach($value as $k => $v) {
                if($k == "item_name") {
                    $temp["item_name"] = $v;                    
                }
                if($k == "itemID") {
                    $temp['id'] = $v;
                    //getStockcardReport($id, $firstDay, $lastDay)
                    $temp['data'] = $this->getStockcardReport($v, $firstDay, $lastDay, $ancillaryID);
                }
               
            }
            $_details[] = $temp;
        }

        // $data['stockcards'] = $_details;
        // echo json_encode($data['stockcards']);
        // die();
        $records = $_details;
        // get
       // $records = $this->db->get()->result();
        //get category name

        $this->db->select('category.category');
        $this->db->from('category');
        $this->db->where('catID', $catID);
        $category_name = $this->db->get()->row()->category;


        $title = $this->module_label." List of ".$category_name;
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
        $data .= "<Cell ss:MergeAcross='16'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s20'>";
        $data .= "<Cell ss:MergeAcross='16'><Data ss:Type='String'>" . $companyName . "</Data></Cell>";
        $data .= "</Row>";
        $data .= "<Row ss:StyleID='s24A'>";
        $data .= "<Cell ss:MergeAcross='16'><Data ss:Type='String'>" . $address . "</Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='16'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='16'><Data ss:Type='String'>" . strtoupper($title) . "</Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='16'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        

        $data .= "<Row>";
        $data .= "<Cell ss:StyleID='s24'><Data ss:Type='String'> </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24'><Data ss:Type='String'> DRUGS AND MEDICAL MDS </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24' ss:MergeAcross='2'><Data ss:Type='String'> BALANCED BEGINNING </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24' ss:MergeAcross='2'><Data ss:Type='String'> RECEIVED DURING </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24' ss:MergeAcross='2'><Data ss:Type='String'>  TOTAL STOCK ON HAND </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24' ss:MergeAcross='2'><Data ss:Type='String'> TOTAL STOCK ISSUED </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24' ss:MergeAcross='2'><Data ss:Type='String'>  BALANCED END </Data></Cell>"; 
        $data .= "</Row>";

        $data .= "<Row>";
        $data .= "<Cell ss:StyleID='s24A'><Data ss:Type='String'> </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A'><Data ss:Type='String'> </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'> QTY </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'> UNIT COST </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'>  TOTAL COST </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'> QTY </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'> UNIT COST </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'>  TOTAL COST </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'> QTY </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'> UNIT COST </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'>  TOTAL COST </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'> QTY </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'> UNIT COST </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'>  TOTAL COST </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'> QTY </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'> UNIT COST </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'>  TOTAL COST </Data></Cell>";
        $data .= "</Row>";

        $received = 0;
        $stock_on_hand = 0;
        $stock_issued = 0;
        $balance_end = 0;
        $balance_beginning = 0;
    
        
        if (count($records)) {
            $ctr = 1;
            foreach ($records as $row) {              
                $data .= "<Row>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $ctr . ".</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row['item_name'] . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row['data']['balance_beginning']['qty'] . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row['data']['balance_beginning']['unit_cost'] . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s26'><Data ss:Type='String'>" . $row['data']['balance_beginning']['total_cost'] . "</Data></Cell>";
                $balance_beginning = $balance_beginning += $row['data']['balance_beginning']['total'];
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row['data']['receive']['qty'] . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row['data']['receive']['unit_cost'] . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s26'><Data ss:Type='String'>" . $row['data']['receive']['total_cost'] . "</Data></Cell>";
                $received = $received += $row['data']['receive']['total'];
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row['data']['total_stock_on_hand']['qty'] . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row['data']['total_stock_on_hand']['unit_cost'] . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s26'><Data ss:Type='String'>" . $row['data']['total_stock_on_hand']['total_cost'] . "</Data></Cell>";
                $stock_on_hand = $stock_on_hand += $row['data']['total_stock_on_hand']['total_cost'];
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row['data']['total_stock_issued']['qty'] . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row['data']['total_stock_issued']['unit_cost'] . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s26'><Data ss:Type='String'>" . $row['data']['total_stock_issued']['total_cost'] . "</Data></Cell>";
                $stock_issued = $stock_issued += $row['data']['total_stock_issued']['total'];
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row['data']['balanced_end']['qty'] . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row['data']['balanced_end']['unit_cost'] . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s26'><Data ss:Type='String'>" . $row['data']['balanced_end']['total_cost'] . "</Data></Cell>";
                $balance_end = $balance_end += $row['data']['balanced_end']['total'];
                $data .= "</Row>";
                $ctr ++;
            }
        }

        //the total
        $data .= "<Row>";
        $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'> </Data></Cell>";
        $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>TOTAL </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'> </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'>  </Data></Cell>";
        $data .= "<Cell ss:StyleID='s26' ><Data ss:Type='String'> ".$balance_beginning." </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'>  </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'>  </Data></Cell>";
        $data .= "<Cell ss:StyleID='s26' ><Data ss:Type='String'>".$received." </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'>  </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'>  </Data></Cell>";
        $data .= "<Cell ss:StyleID='s26' ><Data ss:Type='String'> ".$stock_on_hand." </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'> </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'>  </Data></Cell>";
        $data .= "<Cell ss:StyleID='s26' ><Data ss:Type='String'> ".$stock_issued." </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'>  </Data></Cell>";
        $data .= "<Cell ss:StyleID='s24A' ><Data ss:Type='String'>  </Data></Cell>";
        $data .= "<Cell ss:StyleID='s26' ><Data ss:Type='String'> ".$balance_end."</Data></Cell>";
        $data .= "</Row>";

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
        $this->db->where('suppName', trim($this->input->post('suppName')));
        //change end
        
        if ($this->db->count_all_results($this->table))
            echo "1"; // duplicate
        else
            echo "0";
    }




    //============================================
    // Module Specific Functions
    //============================================
	

    
    public function getSignatory($code)
    {
        $this->db->where('code', $code);
        $this->db->from('signatories');
        return $this->db->get()->row()->fullname;
    }

    public function getSignatoryDesignation($code)
    {
        $this->db->where('code', $code);
        $this->db->from('signatories');
        return $this->db->get()->row()->designation1;
    }



}