<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
 * Controller Template ver 0.2 
 * To use just search change start change end comments
 * Add your code inside these tags
 */
class Inventory_reports extends CI_Controller
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
    
    
	public function __construct() {
		parent::__construct ();
		$this->load->model('generic_model', 'records');
		$this->module = 'Inventory';
		$this->data['controller_page'] = $this->controller_page = site_url('inventory_reports'); // defines contoller link
		$this->table = 'inventory'; // defines the default table
		$this->pfield = $this->data['pfield'] = 'inventoryID'; // defines primary key
		$this->logfield = 'inventoryID';
		$this->module_path = 'modules/'.strtolower(str_replace(" ", "_", $this->module)) . '/inventory_reports'; // defines module path
		

		// check for maintenance period
		if ($this->config_model->getConfig('Maintenance Mode') == '1') {
			header('location: '.site_url('maintenance_mode'));
		}
		
		// check user session
		if (! $this->session->userdata ('current_user')->sessionID) {
			header('location: '.site_url('login'));
		}
	}
	
	private function submenu() {
		//submenu setup
		require_once ('modules.php');
		
		foreach($modules as $mod) {
			//modules/<module>/
			// - <menu>
			// - <metadata>
			$this->load->view('modules/'.str_replace(" ", "_", strtolower($mod)).'/metadata');
		}
		
		$this->data['modules'] = $this->modules;
		$this->data['current_main_module'] = $this->modules[$this->module]['main']; // defines the current main module
		$this->data['current_module'] = $this->modules[$this->module]['sub']['Item Inventory']; // defines the current sub module
		$data['current_module'] = 'Item Inventory';
		// check roles
		$this->check_roles ();
		$this->data ['roles'] = $this->roles;
	}
    
	private function check_roles() {
		// check roles
		$this->roles['create'] 	= $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'Add '.$this->data['current_module']['module_label']);
		$this->roles['view'] 	= $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'View '.$this->data['current_module']['module_label']);
		$this->roles['edit'] 	= $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'Edit Existing '.$this->data['current_module']['module_label']);
		$this->roles['delete'] 	= $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'Delete Existing '.$this->data['current_module']['module_label']);
		$this->roles['approve'] = $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'Approve '.$this->data['current_module']['module_label']);
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

    public function view($id)
    {
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

			// $this->record->fields[]	= 'podetails.*';
            // $this->record->fields[] = 'items.*';
        
            $this->db->select($this->table.'.*');
            $this->db->select('items.*');
            $this->db->select('ancillaries.*');

            $this->db->from($this->table);
            $this->db->join('items', $this->table.'.itemID=items.itemID', 'left');
            $this->db->join('ancillaries', $this->table.'.ancillaryID=ancillaries.ancillaryID', 'left');
            $this->db->where('inventoryID',$id);
          

            $data['records']  = $this->db->get()->result();

        

           

        
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

        //=========================
        //change start
        $condition_fields = array (
            array ('variable' => 'ancillaryID', 'field' => 'ancillaries' . '.ancillaryID', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'name', 'field' => 'items' . '.name', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'description', 'field' => 'items' . '.description', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'qty', 'field' => $this->table . '.qty', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'brandID', 'field' => $this->table . '.brandID', 'default_value' => '', 'operator' => 'brandID' ), 
			array ('variable' => 'status', 'field' => $this->table . '.status', 'default_value' => '', 'operator' => 'where' ) );
        
        // sorting fields
        $sorting_fields = array('inventoryID'=>'asc');
        //change end
        //=========================

        //echo json_encode($_POST);
        //die();

        
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
        $this->db->select($this->table.'.reorderLvl as inventory_reorderLvl');
        $this->db->select($this->table.'.criticalLvl as inventory_criticalLvl');
        $this->db->select('items.*');
        $this->db->select('items.description as item_description');
        $this->db->select('ancillaries.*');
        $this->db->select('brands.*');
    
        // from
        $this->db->from($this->table);
        $this->db->join ( 'items', $this->table . '.itemID=items.itemID', 'left' );
        $this->db->join ( 'ancillaries', $this->table . '.ancillaryID=ancillaries.ancillaryID', 'left' );
        
 
        $this->db->join ( 'brands', 'items.brandID=brands.brandID', 'left' );
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
       $this->db->select($this->table.'.reorderLvl as inventory_reorderLvl');
       $this->db->select($this->table.'.criticalLvl as inventory_criticalLvl');
       $this->db->select($this->table.'.status as inventory_status');
       $this->db->select('items.*');
       $this->db->select('items.description as item_description');
       $this->db->select('ancillaries.*');
       $this->db->select('brands.*');
   
       // from
       $this->db->from($this->table);
       $this->db->join ( 'items', $this->table . '.itemID=items.itemID', 'left' );
       $this->db->join ( 'ancillaries', $this->table . '.ancillaryID=ancillaries.ancillaryID', 'left' );
       

       $this->db->join ( 'brands', 'items.brandID=brands.brandID', 'left' );
    	// join  
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

       

        $this->db->select('ancillaries.*');
        $this->db->from('ancillaries');
        $this->db->where_not_in('ancillaries.status',$this->deleted_status);
        $data['ancillaries'] = $this->db->get()->result();
        
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

    public function printlist()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;

        $condition_fields = array (
            array ('variable' => 'ancillaryID', 'field' => 'ancillaries' . '.ancillaryID', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'name', 'field' => 'items' . '.name', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'description', 'field' => 'items' . '.description', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'qty', 'field' => $this->table . '.qty', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'brandID', 'field' => $this->table . '.brandID', 'default_value' => '', 'operator' => 'brandID' ), 
			array ('variable' => 'status', 'field' => $this->table . '.status', 'default_value' => '', 'operator' => 'where' ) );
        
        // sorting fields
        $sorting_fields = array('inventoryID'=>'asc');


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
       // select
       $this->db->select($this->table.'.*');
        $this->db->select($this->table.'.reorderLvl as inventory_reorderLvl');
        $this->db->select($this->table.'.criticalLvl as inventory_criticalLvl');
        $this->db->select('items.*');
        $this->db->select('items.description as item_description');
        $this->db->select('ancillaries.*');
        $this->db->select('brands.*');
    
        // from
        $this->db->from($this->table);
        $this->db->join ( 'items', $this->table . '.itemID=items.itemID', 'left' );
        $this->db->join ( 'ancillaries', $this->table . '.ancillaryID=ancillaries.ancillaryID', 'left' );
        
 
        $this->db->join ( 'brands', 'items.brandID=brands.brandID', 'left' );
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

        $condition_fields = array (
            array ('variable' => 'ancillaryID', 'field' => 'ancillaries' . '.ancillaryID', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'name', 'field' => 'items' . '.name', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'description', 'field' => 'items' . '.description', 'default_value' => '', 'operator' => 'like_both' ), 
            array ('variable' => 'qty', 'field' => $this->table . '.qty', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'brandID', 'field' => $this->table . '.brandID', 'default_value' => '', 'operator' => 'brandID' ), 
			array ('variable' => 'status', 'field' => $this->table . '.status', 'default_value' => '', 'operator' => 'where' ) );
        
        // sorting fields
        $sorting_fields = array('inventoryID'=>'asc');
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
        


     	
       
        $this->db->select($this->table.'.*');
        $this->db->select($this->table.'.reorderLvl as inventory_reorderLvl');
        $this->db->select($this->table.'.criticalLvl as inventory_criticalLvl');
        $this->db->select('items.*');
        $this->db->select('items.description as item_description');
        $this->db->select('ancillaries.*');
        $this->db->select('brands.*');
    
        // from
        $this->db->from($this->table);
        $this->db->join ( 'items', $this->table . '.itemID=items.itemID', 'left' );
        $this->db->join ( 'ancillaries', $this->table . '.ancillaryID=ancillaries.ancillaryID', 'left' );
        
 
        $this->db->join ( 'brands', 'items.brandID=brands.brandID', 'left' );

        
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
        $title = 'Item Inventory'." List";
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
        $data .= "<Cell ss:MergeAcross='8'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s20'>";
        $data .= "<Cell ss:MergeAcross='8'><Data ss:Type='String'>" . $companyName . "</Data></Cell>";
        $data .= "</Row>";
        $data .= "<Row ss:StyleID='s24A'>";
        $data .= "<Cell ss:MergeAcross='8'><Data ss:Type='String'>" . $address . "</Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='8'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='8'><Data ss:Type='String'>" . strtoupper($title) . "</Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='8'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        
        $fields[] = "  ";
        $fields[] = "ANCILLARY";
        $fields[] = "ITEM";
        $fields[] = "DESCRIPTION";
        $fields[] = "QUANTITY";
        $fields[] = "PRICE";
        $fields[] = "REORDER LVL";      
        $fields[] = "CRITICAL LVL";            
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
                $item_name = $row->brand ? $row->name.'( '.$row->brand.' ) ' : $row->name;
                $data .= "<Row>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $ctr . ".</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->division . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $item_name . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->item_description . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->qty . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->avecost . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->inventory_criticalLvl . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->inventory_reorderLvl . "</Data></Cell>";
                if ($row->status == 1) {
                    $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>Active</Data></Cell>";  
                } else {
                    $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>Inactive</Data></Cell>";
                }
                
                $data .= "</Row>";
                
                $ctr ++;
            }
        }
        $data .= "</Table></Worksheet>";
        $data .= "</Workbook>";
        
        // Final XML Blurb
        $filename = 'Item Inventory'.'_list';
        
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
	
	
	public function display_session()
	{				
		echo var_dump($_SESSION);
    }
    
    public function updateCriticalReorder()
    {
        $id = $this->input->post("id");
        $reorderlvl = $this->input->post('reorderlvl');
        $criticallvl = $this->input->post('criticallvl');   
        
        $data = [
            'reorderLvl' => $reorderlvl,
            'criticalLvl' => $criticallvl
        ];

        $this->db->where('inventoryID', $id);
        $result = $this->db->update('inventory', $data);

        echo json_encode(['updated' => $result]);
        
        
    }


}