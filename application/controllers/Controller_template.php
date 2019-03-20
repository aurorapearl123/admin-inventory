<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
 * Controller Template ver 0.2 
 * To use just search change start change end comments
 * Add your code inside these tags
 */
class Controller_template extends CI_Controller
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
		$this->_init_setup('aaa', 'aaa', 'aaa', 'aaa', 'aaa');
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
        $this->data['current_module'] = $this->modules[$this->module]['sub']['Job Post']; // defines the current sub module
        //change end
                                                                                                  
        // check roles
        $this->check_roles();
        $this->data['roles'] = $this->roles;
    }

    private function check_roles()
    {
        // check roles
        $this->roles['create'] = $this->session->userdata('current_user')->isAdmin;
        $this->roles['view'] = $this->session->userdata('current_user')->isAdmin;
        $this->roles['edit'] = $this->session->userdata('current_user')->isAdmin;
        $this->roles['delete'] = $this->session->userdata('current_user')->isAdmin;
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
        $table_fields = array('jobPositionID', 'qualifications', 'remarks');
        //change end
        
        // check role
        if ($this->roles['create']) {
            $this->record->table = $this->table;
            $this->record->fields = array();
            
            foreach ($table_fields as $fld) {
                $this->record->fields[$fld] = trim($this->input->post($fld));
            }

            //change start
            $this->record->fields['postNo']    = $genNo = $this->_generateID();
			$this->record->fields['datePosted']= date('Y-m-d');
            //change end
            
            if ($this->record->save()) {
                $this->record->fields = array();
                //change start
                $this->record->where['postNo']  = $genNo;
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
			$this->record->fields[]	= 'job_titles.jobTitle';
			$this->record->fields[]	= 'job_titles.jobDescription';
			$this->record->fields[]	= 'job_positions.companyID';
			$this->record->fields[]	= 'job_positions.branchID';
			$this->record->fields[]	= 'job_positions.divisionID';
			$this->record->fields[]	= 'job_positions.employeeTypeID';
			$this->record->fields[]	= 'companies.companyName';
			$this->record->fields[]	= 'branches.branchName';
			$this->record->fields[]	= 'divisions.divisionName';
			$this->record->fields[]	= 'employee_types.employeeType';
			// set joins
			$this->record->joins[]	= array('job_positions',$this->table.'.jobPositionID=job_positions.jobPositionID','left');
			$this->record->joins[]	= array('job_titles','job_positions.jobTitleID=job_titles.jobTitleID','left');
			$this->record->joins[]	= array('companies','job_positions.companyID=companies.companyID','left');
			$this->record->joins[]	= array('branches','job_positions.branchID=branches.branchID','left');
			$this->record->joins[]	= array('divisions','job_positions.divisionID=divisions.divisionID','left');
			$this->record->joins[]	= array('employee_types','job_positions.employeeTypeID=employee_types.employeeTypeID','left');			
			// set where
			$this->record->where[$this->table.'.'.$this->pfield] = $id;
			// execute retrieve
			$this->record->retrieve();
			// ----------------------------------------------------------------------------------
			$data['rec'] = $this->record->field;
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
        $table_fields = array('jobPositionID', 'qualifications', 'remarks','status');
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
			$this->record->fields[]	= 'job_titles.jobTitle';
			$this->record->fields[]	= 'job_titles.jobDescription';
			$this->record->fields[]	= 'companies.companyName';
			$this->record->fields[]	= 'branches.branchName';
			$this->record->fields[]	= 'divisions.divisionName';
			$this->record->fields[]	= 'employee_types.employeeType';
			// set joins
			$this->record->joins[]	= array('job_positions',$this->table.'.jobPositionID=job_positions.jobPositionID','left');
			$this->record->joins[]	= array('job_titles','job_positions.jobTitleID=job_titles.jobTitleID','left');
			$this->record->joins[]	= array('companies','job_positions.companyID=companies.companyID','left');
			$this->record->joins[]	= array('branches','job_positions.branchID=branches.branchID','left');
			$this->record->joins[]	= array('divisions','job_positions.divisionID=divisions.divisionID','left');
			$this->record->joins[]	= array('employee_types','job_positions.employeeTypeID=employee_types.employeeTypeID','left');						
			// set where
			$this->record->where[$this->table.'.'.$this->pfield] = $id;
			
			// execute retrieve
			$this->record->retrieve();
			// ----------------------------------------------------------------------------------
			$data['rec'] = $this->record->field;
            //change end

            //change start
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

        //=========================
        //change start
        $condition_fields = array(
            array('variable'=>'jobTitleID', 'field'=>'job_positions.jobTitleID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'companyID', 'field'=>'job_positions.companyID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'branchID', 'field'=>'job_positions.branchID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'divisionID', 'field'=>'job_positions.divisionID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'employeeTypeID', 'field'=>'job_positions.employeeTypeID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'1', 'operator'=>'where'),
        );
        
        // sorting fields
        $sorting_fields = array('datePosted'=>'desc','jobTitle'=>'asc');
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
    	$this->db->select('job_titles.jobTitle');
    	$this->db->select('job_titles.jobDescription');
    	$this->db->select('companies.companyAbbr');
    	$this->db->select('branches.branchAbbr');
    	$this->db->select('divisions.divisionAbbr');
    	$this->db->select('employee_types.employeeType');

    	// from
    	$this->db->from($this->table);
    	
    	// join   	
    	$this->db->join('job_positions',$this->table.'.jobPositionID=job_positions.jobPositionID','left');
    	$this->db->join('job_titles','job_positions.jobTitleID=job_titles.jobTitleID','left');
    	$this->db->join('companies','job_positions.companyID=companies.companyID','left');
    	$this->db->join('branches','job_positions.branchID=branches.branchID','left');
    	$this->db->join('divisions','job_positions.divisionID=divisions.divisionID','left');
    	$this->db->join('employee_types','job_positions.employeeTypeID=employee_types.employeeTypeID','left');
    	
    	// where
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
    	$this->db->select('job_titles.jobTitle');
    	$this->db->select('job_titles.jobDescription');
    	$this->db->select('companies.companyAbbr');
    	$this->db->select('branches.branchAbbr');
    	$this->db->select('divisions.divisionAbbr');
    	$this->db->select('employee_types.employeeType');

    	// from
    	$this->db->from($this->table);
    	
    	// join   	
    	$this->db->join('job_positions',$this->table.'.jobPositionID=job_positions.jobPositionID','left');
    	$this->db->join('job_titles','job_positions.jobTitleID=job_titles.jobTitleID','left');
    	$this->db->join('companies','job_positions.companyID=companies.companyID','left');
    	$this->db->join('branches','job_positions.branchID=branches.branchID','left');
    	$this->db->join('divisions','job_positions.divisionID=divisions.divisionID','left');
    	$this->db->join('employee_types','job_positions.employeeTypeID=employee_types.employeeTypeID','left');
    	
    	// where
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
            array('variable'=>'jobTitleID', 'field'=>'job_positions.jobTitleID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'companyID', 'field'=>'job_positions.companyID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'branchID', 'field'=>'job_positions.branchID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'divisionID', 'field'=>'job_positions.divisionID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'employeeTypeID', 'field'=>'job_positions.employeeTypeID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'1', 'operator'=>'where'),
        );
        
        // sorting fields
        $sorting_fields = array('datePosted'=>'desc','jobTitle'=>'asc');
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
    	$this->db->select('job_titles.jobTitle');
    	$this->db->select('job_titles.jobDescription');
    	$this->db->select('companies.companyAbbr');
    	$this->db->select('branches.branchAbbr');
    	$this->db->select('divisions.divisionAbbr');
    	$this->db->select('employee_types.employeeType');

    	// from
    	$this->db->from($this->table);
    	
    	// join   	
    	$this->db->join('job_positions',$this->table.'.jobPositionID=job_positions.jobPositionID','left');
    	$this->db->join('job_titles','job_positions.jobTitleID=job_titles.jobTitleID','left');
    	$this->db->join('companies','job_positions.companyID=companies.companyID','left');
    	$this->db->join('branches','job_positions.branchID=branches.branchID','left');
    	$this->db->join('divisions','job_positions.divisionID=divisions.divisionID','left');
    	$this->db->join('employee_types','job_positions.employeeTypeID=employee_types.employeeTypeID','left');  	
    	
    	// where
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
            array('variable'=>'jobTitleID', 'field'=>'job_positions.jobTitleID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'companyID', 'field'=>'job_positions.companyID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'branchID', 'field'=>'job_positions.branchID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'divisionID', 'field'=>'job_positions.divisionID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'employeeTypeID', 'field'=>'job_positions.employeeTypeID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'1', 'operator'=>'where'),
        );
        
        // sorting fields
        $sorting_fields = array('datePosted'=>'desc','jobTitle'=>'asc');
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
    	$this->db->select('job_titles.jobTitle');
    	$this->db->select('job_titles.jobDescription');
    	$this->db->select('companies.companyAbbr');
    	$this->db->select('branches.branchAbbr');
    	$this->db->select('divisions.divisionAbbr');
    	$this->db->select('employee_types.employeeType');

    	// from
    	$this->db->from($this->table);
    	
    	// join   	
    	$this->db->join('job_positions',$this->table.'.jobPositionID=job_positions.jobPositionID','left');
    	$this->db->join('job_titles','job_positions.jobTitleID=job_titles.jobTitleID','left');
    	$this->db->join('companies','job_positions.companyID=companies.companyID','left');
    	$this->db->join('branches','job_positions.branchID=branches.branchID','left');
    	$this->db->join('divisions','job_positions.divisionID=divisions.divisionID','left');
    	$this->db->join('employee_types','job_positions.employeeTypeID=employee_types.employeeTypeID','left');  	
    	
    	// where
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
        $data .= "<Cell ss:MergeAcross='7'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s20'>";
        $data .= "<Cell ss:MergeAcross='7'><Data ss:Type='String'>" . $companyName . "</Data></Cell>";
        $data .= "</Row>";
        $data .= "<Row ss:StyleID='s24A'>";
        $data .= "<Cell ss:MergeAcross='7'><Data ss:Type='String'>" . $address . "</Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='7'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='7'><Data ss:Type='String'>" . strtoupper($title) . "</Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='7'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        
        $fields[] = "  ";
        $fields[] = "DATE POSTED";
        $fields[] = "JOB POSITION";
        $fields[] = "COMPANY";
        $fields[] = "BRANCH";
        $fields[] = "DIVISION";
        $fields[] = "EMPLOYMENT";
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
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . date('m/d/Y', strtotime($row->datePosted)) . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->jobTitle . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->companyAbbr . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->branchAbbr . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->divisionAbbr . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->employeeType . "</Data></Cell>";

                if ($row->status == 1) {
                    $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>Open</Data></Cell>";  
                } else {
                    $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>Close</Data></Cell>";
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
		$idSeries 	= $this->config_model->getConfig('Job Post Series');
		$idLength 	= $this->config_model->getConfig('Job Post Series Length');
	
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


}