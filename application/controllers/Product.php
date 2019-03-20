<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
 * Controller Template ver 0.2 
 * To use just search change start change end comments
 * Add your code inside these tags
 */
class Product extends CI_Controller
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
		$this->load->model('generic_model', 'records');
		//change start
		//parent module, controller, table, pfield, logfield
		$this->_init_setup('Master Files', 'product', 'products', 'productID', 'name');
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
        $this->data['current_module'] = $this->modules[$this->module]['sub']['Product']; // defines the current sub module
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
            'loyalty' => 'productID',
            'drdetails' =>'productID',
            'podetails' =>'productID',
            'sodetails' =>'productID',
            'stockcards' =>'productID',
            'rrdetails' =>'productID'
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
       	//load submenu
		$this->submenu();
		$data = $this->data;
		$table_fields = array ('catID','name','productCode', 'description', 'umsr','lastcost', 'lowestcost', 'highcost','avecost', 'markupType', 'markup','lowestprice', 'highprice', 'aveprice','qty', 'reorderLvl', 'isVAT');
		
		// check role
		if ($this->roles['create']) {
			$this->records->table = $this->table;
			$this->records->fields = array ();
			
			foreach ($table_fields as $fld) {
				$this->records->fields[$fld] = trim($this->input->post($fld));
			}

			$this->records->fields['createdBy']      =   $this->session->userdata('current_user')->userID;
			$this->records->fields['dateInserted']   =   date('Y-m-d H:i:s');
	         
			if ($this->records->save()) {
				$this->records->fields = array();
             
				$id = $this->records->where['productID'] = $this->db->insert_id();
				$this->records->retrieve();
											
				// record logs
				$logs = "Record - ".trim($this->input->post($this->logfield));
				$this->log_model->table_logs($data['current_module']['module_label'], $this->table, $this->pfield, $this->records->field->$data['pfield'], 'Insert', $logs );
				
				$logfield = $this->pfield;
				// success msg
				$data["class"] = "success";
				$data["msg"] = $this->data['current_module']['module_label']." successfully saved.";
				$data["urlredirect"] = $this->controller_page."/view/".$this->encrypter->encode($id);
				$this->load->view("header", $data);
				$this->load->view("message");
				$this->load->view("footer");
			
			} else {
				// error
				$data["class"] = "danger";
				$data["msg"] = "Error in saving the ".$this->data['current_module']['module_label']."!";
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
			$this->records->table = $this->table;
			// set fields for the current table
			$this->records->setFields();
			// extend fields - join tables					
			// $this->records->fields[]	= 'job_titles.jobTitle';
			
			// set joins
			// $this->records->joins[]	= array('job_positions',$this->table.'.jobPositionID=job_positions.jobPositionID','left');
				
			// set where
			$this->records->where[$this->table.'.'.$this->pfield] = $id;
			// execute retrieve
			$this->records->retrieve();
			// ----------------------------------------------------------------------------------
			$data['rec'] = $this->records->field;
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
        $table_fields = array ('catID','name','productCode', 'description', 'umsr','lastcost', 'lowestcost', 'highcost','avecost', 'markupType', 'markup','lowestprice', 'highprice', 'aveprice','qty', 'reorderLvl', 'isVAT','status');
        //change end
        
        // check roles
        if ($this->roles['edit']) {
            $this->records->table = $this->table;
            $this->records->fields = array();

            foreach ($table_fields as $fld) {
                $this->records->fields[$fld] = trim($this->input->post($fld));
            }

            //change start
            $this->records->fields['dateLastEdit']   = date('Y-m-d H:i:s');
            //change end
            
            $this->records->pfield = $this->pfield;
   
            $this->records->pk = $this->encrypter->decode($this->input->post( $this->pfield));
            // field logs here
            $wasChange = $this->log_model->field_logs($data['current_module']['module_label'], $this->table, $this->pfield, $id, 'Update', $this->records->fields);
            
      
            if ($this->records->update()) {
                //change start
                //change end

                // records logs
                if ($wasChange) {
                    $logs = "Record - " . trim($this->input->post($this->logfield));
                    $this->log_model->table_logs($data['current_module']['module_label'], $this->table, $this->pfield, $this->records->pk, 'Update', $logs);
                }
                
                // successful
                $data["class"] = "success";
                $data["msg"] = $this->data['current_module']['module_label'] . " successfully updated.";
                $data["urlredirect"] = $this->controller_page . "/view/" . $this->encrypter->encode($this->records->pk);
                $this->load->view("header", $data);
                $this->load->view("message");
                $this->load->view("footer");
            } else {
                // error
                $data["class"] = "danger";
                $data["msg"] = "Error in updating the " . $this->data['current_module']['module_label'] . "!";
                $data["urlredirect"] = $this->controller_page . "/view/" . $this->records->pk;
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
            $this->records->fields = array();
            // set table
            $this->records->table = $this->table;
            // set where
            $this->records->where[$this->pfield] = $id;
            // execute retrieve
            $this->records->retrieve();
            
            if (! empty($this->records->field)) {
                $this->records->pfield = $this->pfield;
                $this->records->pk = $id;
                
                // check if in used
                // if (! $this->_in_used($id)) {
                    if ($this->records->delete()) {
                        $logfield = $this->logfield;

                        //change start
                        //change end
                        
                        // records logs
                        $logs = "Record - " . $this->records->field->$logfield;
                        $this->log_model->table_logs($this->data['current_module']['module_label'], $this->table, $this->pfield, $this->records->pk, 'Delete', $logs);
                        
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
                // } else {
                //     // error
                //     $data["class"] = "danger";
                //     $data["msg"] = "Data integrity constraints.";
                //     $data["urlredirect"] = "";
                //     $this->load->view("header", $data);
                //     $this->load->view("message");
                //     $this->load->view("footer");
                // }
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
			$this->records->table = $this->table;			
			// set fields for the current table
			$this->records->setFields();			
			// extend fields - join tables			
			$this->records->fields[] = 'category.category';

			// set joins
			$this->records->joins[]  = array('category',$this->table.'.catID=category.catID','left');
           				
			// set where
			$this->records->where[$this->table.'.'.$this->pfield] = $id;
			
			// execute retrieve
			$this->records->retrieve();
			// ----------------------------------------------------------------------------------
			$data['rec'] = $this->records->field;
         
            //change end

            //change start
            //change end
            
            $data['in_used'] = $this->_in_used($id);
            // record logs
            $pfield = $this->pfield;
            if ($this->config_model->getConfig('Log all record views') == '1') {
                $logs = "Record - " . $this->records->field->name;
                $this->log_model->table_logs($this->module, $this->table, $this->pfield, $this->records->field->$pfield, 'View', $logs);
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
        $condition_fields = array(
            array('variable'=>'category', 'field'=>'category.category', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'productCode', 'field'=>$this->table.'.productCode', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'name', 'field'=>$this->table.'.name', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'avecost', 'field'=>$this->table.'.avecost', 'default_value'=>'', 'operator'=>'where'),
            array('variable'=>'reorderLvl', 'field'=>$this->table.'.reorderLvl', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'1', 'operator'=>'where'),
        );
        
        // sorting fields
        $sorting_fields = array('name'=>'asc');
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
        $this->db->select('category.category');

    	// from
    	$this->db->from($this->table);
    	
    	// join   
        $this->db->join('category',$this->table.'.catID=category.catID','left');                    
    	
    	// where
        $this->db->where($this->table.'.status !=', -100);
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
        $this->db->select('category.category');

    	// from
    	$this->db->from($this->table);
    	
    	// join   	
        $this->db->join('category',$this->table.'.catID=category.catID','left');
    	
    	// where
        $this->db->where($this->table.'.status !=', -100);
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
        // var_dump($data['records']);die();
        
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
            array('variable'=>'category', 'field'=>'category.category', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'productCode', 'field'=>$this->table.'.productCode', 'default_value'=>'', 'operator'=>'where'),
            array('variable'=>'name', 'field'=>$this->table.'.name', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'avecost', 'field'=>$this->table.'.avecost', 'default_value'=>'', 'operator'=>'where'),
            array('variable'=>'reorderLvl', 'field'=>$this->table.'.reorderLvl', 'default_value'=>'', 'operator'=>'where'),
            array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'1', 'operator'=>'where'),
        );
        
        // sorting fields
        $sorting_fields = array('name'=>'asc');
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
        $datePosted = $this->session->userdata($controller.'_datePosted');
        //change end
        $limit = $this->session->userdata($controller . '_limit');
        $offset = $this->session->userdata($controller . '_offset');
        $sortby = $this->session->userdata($controller . '_sortby');
        $sortorder = $this->session->userdata($controller . '_sortorder');
        
        //===================================
        //change start
        // select
        $this->db->select($this->table.'.*');
        $this->db->select('category.category');

    	// from
    	$this->db->from($this->table);
    	
    	// join   
        $this->db->join('category',$this->table.'.catID=category.catID','left');	
 	    	
    	// where
        $this->db->where($this->table.'.status !=', -100);
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
            array('variable'=>'category', 'field'=>'category.category', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'productCode', 'field'=>$this->table.'.productCode', 'default_value'=>'', 'operator'=>'where'),
            array('variable'=>'name', 'field'=>$this->table.'.name', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'avecost', 'field'=>$this->table.'.avecost', 'default_value'=>'', 'operator'=>'where'),
            array('variable'=>'reorderLvl', 'field'=>$this->table.'.reorderLvl', 'default_value'=>'', 'operator'=>'where'),
            array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'1', 'operator'=>'where'),
        );
        
        // sorting fields
        $sorting_fields = array('name'=>'asc');
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
        $datePosted = $this->session->userdata($controller.'_datePosted');
        //change end
        $limit = $this->session->userdata($controller . '_limit');
        $offset = $this->session->userdata($controller . '_offset');
        $sortby = $this->session->userdata($controller . '_sortby');
        $sortorder = $this->session->userdata($controller . '_sortorder');
        


        //===================================
        //change start
        // select
    	$this->db->select($this->table.'.*');
        $this->db->select('category.category');

    	// from
    	$this->db->from($this->table);
    	
    	// join   	
        $this->db->join('category',$this->table.'.catID=category.catID','left');
	
    	
    	// where
        $this->db->where($this->table.'.status !=', -100);
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
            <Column ss:Index='3' ss:AutoFitWidth=\"1\" ss:Width='120.00'/>
            <Column ss:Index='4' ss:AutoFitWidth=\"1\" ss:Width='120.00'/>
            <Column ss:Index='5' ss:AutoFitWidth=\"1\" ss:Width='80.00'/>
            <Column ss:Index='6' ss:AutoFitWidth=\"1\" ss:Width='80.00'/>
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
        $fields[] = "CATEGORY";
        $fields[] = "PRODUCT CODE";
        $fields[] = "PRODUCT";
        $fields[] = "PRICE";
        $fields[] = "REORDER LEVEL";
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
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->category . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->productCode . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->name . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s26'><Data ss:Type='String'>" . number_format($row->avecost,2) . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s26'><Data ss:Type='String'>" . number_format($row->reorderLvl,2) . "</Data></Cell>";

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
        $filename = $this->data['controller_name'].'_list';
        
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$filename.xls;");
        header("Content-Type: application/ms-excel");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        echo $data;
    }

    // Conditions and fields changes
    public function check_duplicate() {
        $this->db->where('productCode', trim($this->input->post('productCode')));
        $this->db->where('name', trim($this->input->post('name')));
        
        if ($this->db->count_all_results($this->table))
            echo "1"; // duplicate
        else
            echo "0";
    }




    //============================================
    // Module Specific Functions
    //============================================
    //
    public function save_popup() {
        //load submenu
        $this->submenu();
        $data = $this->data;
        $table_fields = array ('catID','subcatID', 'barcode', 'brandID','itemCode','name', 'description', 'umsr','lastcost', 'lowestcost', 'highcost','avecost', 'markupType', 'markup','lowestprice', 'highprice', 'aveprice','reorderLvl', 'criticalLvl', 'leadtime','inventoryType','vatType','discountable','dangerousDrug','mdrPrice');
        
        // check role
        if ($this->roles['create']) {
            $this->records->table = $this->table;
            $this->records->fields = array ();
            
            foreach ($table_fields as $fld) {
                $this->records->fields[$fld] = trim($this->input->post($fld));
            }
            
            if ($this->records->save()) {
                $this->records->fields = array();
                $id = $this->records->where['itemID'] = $this->db->insert_id();
                $this->records->retrieve();
                // record logs
                $logs = "Record - ".trim($this->input->post($this->logfield));
                $this->log_model->table_logs($data['current_module']['module_label'], $this->table, $this->pfield, $this->records->field->$data['pfield'], 'Insert', $logs );
                
                $logfield = $this->pfield;
                // success msg
                $data["class"] = "success";
                $data["msg"] = $this->data['current_module']['module_label']." successfully saved.";
                $data["urlredirect"] = "reload_select";
                $data["theFunction"] = "getProvince";
                $data["activeID"]    = $id;
                $this->load->view("header_popup", $data);
                $this->load->view("message");
                $this->load->view("footer_popup");
            
            } else {
                // error
                $data["class"] = "danger";
                $data["msg"] = "Error in saving the ".$this->data['current_module']['module_label']."!";
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
    
    public function save_popup_category()
    {
        $category = $this->input->post('category');
        
        $this->db->where('category',$category);
        $count = $this->db->count_all_results('category');

        if ($count >= 1) {
            $data['error'] = true;
            $data['message'] = 'Category Already Exists';
        } else {
            $this->db->set('category',$category);
            $this->db->set('description',$this->input->post('description'));
            $this->db->set('createdBy',$this->session->userdata('current_user')->userID);
            $this->db->set('dateInserted',date('Y-m-d H:i:s'));
            $this->db->insert('category');
            
            $id = $this->db->insert_id();
            
            $data['id'] = $id;
            $data['error'] = false;
            $data['message'] = 'Successfully Added';
        }
        echo json_encode($data);
    }

    public function save_popup_umsr()
    {
        $umsr = $this->input->post('umsr');
        
        $this->db->where('umsr',$umsr);
        $count = $this->db->count_all_results('unit_measurements');

        if ($count >= 1) {
            $data['error'] = true;
            $data['message'] = 'UMSR Already Exists';
        } else {
            $this->db->set('umsr',$umsr);
            $this->db->set('createdBy',$this->session->userdata('current_user')->userID);
            $this->db->set('dateCreated',date('Y-m-d H:i:s'));
            $this->db->insert('unit_measurements');
            
            $id = $this->db->insert_id();
            
            $this->db->where('umsrID',$id);
            $umsrrec = $this->db->get('unit_measurements')->row();
            
            $data['id'] = $umsrrec->umsr;
            $data['error'] = false;
            $data['message'] = 'Successfully Added';
        }
        echo json_encode($data);
    }
	
	public function display_session()
	{				
		echo var_dump($_SESSION);
	}


}