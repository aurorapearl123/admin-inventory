<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends CI_Controller
{
    //Default Variables
    var $menu;
    var $roles;
    var $data;
    var $table;
    var $pfield;
    var $logfield;
    var $module;
    var $modules;
    var $module_path;
    var $controller_page;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('generic_model','record');
        $this->module       = 'Inventory';
        $this->table        = 'employees';                                                 
        $this->pfield       = $this->data['pfield'] = 'empID';                                                 
        $this->logfield     = 'empNo';
        $this->module_path  = 'modules/inventory/inventory';           
        $this->data['controller_page']  = $this->controller_page = site_url('inventory');
        
        // check for maintenance period
        if ($this->config_model->getConfig('Maintenance Mode')=='1') {
            header('location: '.site_url('maintenance_mode'));
        }
        
        // check user session
        if (!$this->session->userdata('current_user')->sessionID) {
            header('location: '.site_url('login'));
        }
    }

    private function submenu()
    {
        //submenu setup
        require_once('modules.php');

        foreach($modules as $mod) {
            //modules/<module>/
            // - <menu>
            // - <metadata>
            $this->load->view('modules/'.str_replace(" ","_",strtolower($mod)).'/metadata');
        }

        $this->data['modules']   = $this->modules;
        $this->data['current_main_module']   = $this->modules[$this->module]['main'];            // defines the current main module
        $this->data['current_module']   = $this->modules[$this->module]['sub']['Inventory'];      // defines the current sub module
        // check roles
        $this->check_roles();
        $this->data['roles']   = $this->roles;
    }
    
    private function check_roles()
    {
        $this->roles['create']  = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Add '.$this->module);
        $this->roles['view']    = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View '.$this->module);
        $this->roles['edit']    = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Edit Existing '.$this->module);
        $this->roles['delete']  = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Delete Existing '.$this->module);
        $this->roles['print']   = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Print '.$this->module);
        $this->roles['export']  = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Export '.$this->module);
    }
    
    public function index()
    {
        $this->show();
    }
    
    public function create()
    {
       $this->submenu();
        $data = $this->data;
        // check roles
        if ($this->roles['create']) {
            $data['countryID']      = ($this->session->userdata('current_countryID')) ? $this->session->userdata('current_countryID') : $this->config_model->getConfig('Default Country Option');
            $data['provinceID']     = ($this->session->userdata('current_provinceID')) ? $this->session->userdata('current_countryID') : $this->config_model->getConfig('Default Province Option');
            $data['nationality']    = ($this->session->userdata('current_nationality')) ? $this->session->userdata('current_nationality') : $this->config_model->getConfig('Default Nationality Option');
            $data['languages']      = ($this->session->userdata('current_languages')) ? $this->session->userdata('current_languages') : $this->config_model->getConfig('Default Languages Option');
            $data['companyID']      = $this->session->userdata('current_companyID');
            $data['officeID']       = $this->session->userdata('current_officeID');
            $data['divisionID']     = $this->session->userdata('current_divisionID');
            $data['employeeTypeID'] = $this->session->userdata('current_employeeTypeID');
            
            // load views
            $this->load->view('header', $data);
            $this->load->view($this->module_path.'/create');
            $this->load->view('footer');
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
    
    public function save()
    {
        $enc_type = $this->config_model->getConfig('Encryption Type').'("';
        $enc_key = '","'.$this->config_model->getConfig('Encryption Key').'")';
        $emp = $this->config_model->getConfig('Employee ID Number Series');
        $empNo = str_pad($emp,6,"0",STR_PAD_LEFT);

            $this->record->table  = $this->table;
            $this->record->fields = array();


        	//unencrypted data here
        	$table_fields = array('sex', 'civilStatus', 'nationality', 'languages', 'religionID', 'currentCountryID', 'currentProvinceID', 'currentCityID', 'currentBarangayID', 'provinceCountryID', 'provinceProvinceID', 'provinceCityID', 'provinceBarangayID');
            foreach($table_fields as $fld) {
                $this->db->set($fld, trim($this->input->post($fld)));
            }
            
            //encrypt data here
            $encrypt_table_fields = array('lname', 'fname', 'mname', 'nickname', 'suffix', 'title', 'birthPlace', 'telephone', 'mobile', 'biometricID', 'workEmail', 'personalEmail', 'currentStreet', 'provinceStreet', 'tin', 'sssNo', 'philhealthNo', 'pagibigNo');
        	foreach($encrypt_table_fields as $encrypt_fld) {
                $this->db->set($encrypt_fld, $enc_type.trim($this->input->post($encrypt_fld)).$enc_key, false);
            }
            
            //format date here
            $this->db->set('birthDate', (trim($this->input->post('birthDate')) != '') ? date('Y-m-d', strtotime(trim($this->input->post('birthDate')))) : '0000-00-00');
            $this->db->set('empNo',$empNo);
            
            if ($this->db->insert($this->table)) {
                $id = $this->record->where['empID'] = $this->db->insert_id();
            	$this->record->retrieve();
            	
            	$this->_incrementSeries();
				// record logs
				//$logs = "Record - ".trim($this->input->post($this->logfield));
				//$this->log_model->table_logs($data['current_module']['module_label'], $this->table, $this->pfield, $this->records->field->$data['pfield'], 'Insert', $logs );
				
				$logfield = $this->pfield;
				// success msg
				$data["class"] 		= "success";
				$data["msg"] 		= $this->data['current_module']['module_label']." successfully saved.";
				$data["urlredirect"] = $this->controller_page."/view/".$this->encrypter->encode($id);
				$this->load->view("header", $data);
				$this->load->view("message");
				$this->load->view("footer");
			
			} else {
				// error
				$data ["class"] = "danger";
				$data ["msg"] = "Error in saving the " . $this->data ['current_module'] ['module_label'] . "!";
				$data ["urlredirect"] = "";
				$this->load->view ( "header", $data );
				$this->load->view ( "message" );
				$this->load->view ( "footer" );
			}
    }
    
    public function edit($id)
    {
        $this->submenu();
        $data = $this->data;
        $id = $this->encrypter->decode($id);
        
        $dec_type = $this->config_model->getConfig('Decrypt Data'); 					//encryption type - decrypt
        $enc_key = $this->config_model->getConfig('Encryption Key'); 					//encryption key
        $enc_fields = explode(',', $this->config_model->getConfig('Encrypted Fields '.$this->module)); //encrypted fields

        if ($this->roles['edit']) {
            // for retrieve with joining tables -------------------------------------------------
            // set table
            
            $this->db->select($this->table.'.*');
            
        	//decrypt all encrypted fields
        	foreach ($enc_fields as $enc_field) {
				$this->db->select($dec_type."(".$enc_field.",'".$enc_key."') as ".$enc_field);
			}
			
			$this->db->from($this->table);
			
            // set where
            $this->db->where($this->table.'.'.$this->pfield,$id);
            // execute retrieve
            
            $records = $this->db->get()->row();
            // ----------------------------------------------------------------------------------
            $data['rec'] = $records;

            // load views
            $this->load->view('header', $data);
            $this->load->view($this->module_path.'/edit');
            $this->load->view('footer');
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
    
    public function update()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;

        $enc_type = $this->config_model->getConfig('Encryption Type').'("';
        $enc_key = '","'.$this->config_model->getConfig('Encryption Key').'")';
        $emp = $this->config_model->getConfig('Employee ID Number Series');
        $empNo = str_pad($emp,6,"0",STR_PAD_LEFT);

        if ($this->roles['edit']) {
            $this->record->table  = $this->table;
            $this->record->fields = array();

        	//unencrypted data here
        	$table_fields = array('sex', 'civilStatus', 'nationality', 'languages', 'religionID', 'currentCountryID', 'currentProvinceID', 'currentCityID', 'currentBarangayID', 'provinceCountryID', 'provinceProvinceID', 'provinceCityID', 'provinceBarangayID');
            foreach($table_fields as $fld) {
                $this->db->set($fld, trim($this->input->post($fld)));
            }
            
            //encrypt data here
            $encrypt_table_fields = array('lname', 'fname', 'mname', 'nickname', 'suffix', 'title', 'birthPlace', 'telephone', 'mobile', 'biometricID', 'workEmail', 'personalEmail', 'currentStreet', 'provinceStreet', 'tin', 'sssNo', 'philhealthNo', 'pagibigNo');
        	foreach($encrypt_table_fields as $encrypt_fld) {
                $this->db->set($encrypt_fld, $enc_type.trim($this->input->post($encrypt_fld)).$enc_key, false);
            }
            
            //format date here
            $this->db->set('birthDate', (trim($this->input->post('birthDate')) != '') ? date('Y-m-d', strtotime(trim($this->input->post('birthDate')))) : '0000-00-00');
            $this->db->set('empNo',$empNo);       

            $this->record->pfield   = $this->pfield;
            $this->record->pk       = $this->encrypter->decode(trim($this->input->post($this->pfield)));
            
            // field logs here
            $wasChange = $this->log_model->field_logs($this->module, $this->table, $this->pfield, $this->record->pk, 'Update', $this->record->fields);
            
            if ($this->record->update()) {  
                // record logs
                if ($wasChange) {
                    $logs = "Record - ".trim($this->input->post($this->logfield));
                    $this->log_model->table_logs($data['current_module']['module_label'], $this->table, $this->pfield, $this->record->pk, 'Update', $logs);
                }
                // Successfully updated
                $data['class']  = "success";
                $data['msg']    = $this->module." successfully updated.";
                $data['urlredirect']    = $this->controller_page."/view/".$this->encrypter->encode($this->record->pk);
                $this->load->view('header', $data);
                $this->load->view('message');
                $this->load->view('footer');
            } else {
                // Error updating
                $data['class']  = "success";
                $data['msg']    = "Error in updating the ".strtolower($this->module)."!";
                $data['urlredirect']    = $this->controller_page."/view/".$this->encrypter->encode($this->record->pk);
                $this->load->view('header', $data);
                $this->load->view('message');
                $this->load->view('footer');
            }

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

    public function delete($id=0)
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        $id = $this->encrypter->decode($id);

        if ($this->roles['delete'] && !$this->_inUsed($id)) {
            $this->record->fields = array();
            $this->record->table  = $this->table;
            $this->record->where[$this->pfield] = $id;
            $this->record->retrieve();
            if (!empty($this->record->field)) {
                $this->record->pfield   = $this->pfield;
                $this->record->pk       = $id;
                // record logs
                $logfield = $this->logfield;
                if ($this->record->delete()) {
                    $logs = "Record - ".$this->record->field->$logfield;
                    $this->log_model->table_logs($this->module, $this->table, $this->pfield, $this->record->pk, 'Delete', $logs);
                    //Successfully deleted
                    $data['class']  = "success";
                    $data['msg']    = $this->module." successfully deleted.";
                    $data['urlredirect']    = $this->controller_page."/show";
                    $this->load->view('header', $data);
                    $this->load->view('message');
                    $this->load->view('footer');
                } else {
                    //Error Deleting
                    $data['class']  = "danger";
                    $data['msg']    = "Error in deleting the ".strtolower($this->module)."!";
                    $data['urlredirect']    = "";
                    $this->load->view('header', $data);
                    $this->load->view('message');
                    $this->load->view('footer');
                }
            } else {
                //Record not found
                $data['class']  = "danger";
                $data['msg']    = $this->module." record not found!";
                $data['urlredirect']    = "";
                $this->load->view('header', $data);
                $this->load->view('message');
                $this->load->view('footer');
            }

        } else {
            //No access this page
            $data['class']  = "danger";
            $data['msg']    = "Sorry, you don't have access to this page!";
            $data['urlredirect']    = "";
            $this->load->view('header', $data);
            $this->load->view('message');
            $this->load->view('footer');
        }
    }
    
    public function view($id)
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        
        $id = $this->encrypter->decode($id);
        
        $dec_type = $this->config_model->getConfig('Decrypt Data'); 					//encryption type - decrypt
        $enc_key = $this->config_model->getConfig('Encryption Key'); 					//encryption key
        $enc_fields = explode(',', $this->config_model->getConfig('Encrypted Fields '.$this->module)); //encrypted fields
        
        
        if ($this->roles['view']) {
            // Setup view
            
            $this->db->select($this->table.'.*');
            
            //decrypt all encrypted fields
        	foreach ($enc_fields as $enc_field) {
				$this->db->select($dec_type."(".$enc_field.",'".$enc_key."') as ".$enc_field);
			}
			
			$this->db->select('religionID');
            $this->db->from($this->table);
            $this->db->where($this->pfield, $id);
            $record = $this->db->get()->row();
            // ----------------------------------------------------------------------------------
            $data['rec'] = $record;
            

            // record logs
            if ($this->config_model->getConfig('Log all record views') == '1') {
                $logfield = $this->logfield;
                $logs = "Record - ".$this->record->field->$logfield;
                $this->log_model->table_logs($this->data['current_module']['module_label'], $this->table, $this->pfield, $this->record->field->$data['pfield'], 'View', $logs);
            }

            // check if record is used in other tables
            //$data['inUsed'] = $this->_in_used($id);

            // load views
            $this->load->view('header', $data);
            $this->load->view($this->module_path.'/view');
            $this->load->view('footer');

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
    
//	public function show()
//    {
//
//        //echo json_encode($_POST);
//        //die();
//
//        $this->submenu();
//        $data = $this->data;
//
//        $controller = $this->uri->segment(1);
//
//        if ($this->uri->segment(3)) {
//            $offset = $this->uri->segment(3);
//        } else {
//            $offset = 0;
//        }
//        
//
//        //get records
//        $this->db->select($this->table . '.*');
//        $this->db->from($this->table);
//
//        // get number of rows
//        $data['ttl_rows'] = $config['total_rows'] = $this->db->count_all_results();
//
//        $sortby     	= trim($this->input->post('sortby'));
//        $sortorder  	= trim($this->input->post('sortorder'));
//        
//        if ($sortby == "" || $sortorder == "") {
//        	$sortby 	= 'lname';
//        	$sortorder 	= 'asc';
//        }
//        
//        if (is_array($_POST) && $_POST){
//        	//echo "if";
//        	//die();
//        	foreach ($_POST as $key => $value) {
//	            if($key !== "sortby" && $key !== "sortorder" && $key !== "limit") {
//	            	
//	            	//date query
//	                if($key == "bday" && $value !== "") {
//	                   $data[$key] = $value;
//	                   $this->db->like($this->table.'.'.$key, date('Y-m-d', strtotime($value)));
//	                } 
//	                
//                    $data[$key] = $value;
//                    $this->db->where($this->table.'.'.$key, $value);
//                    
//	            } else {
//	             	//set order_by
//                    if($key == 'sortby') {
//                         $this->db->order_by($this->table.'.'.$sortby, $sortorder);
//                    }
//                    
//                    //set limit
//                    if($key == 'limit') {
//                        $this->db->limit($value);
//                        $limit = $value;
//                    }
//	            }
//        	}
//        } else {
//			//empty sorting fields
//			//set default sorting fields
//			$limit = 25;
//			$this->db->order_by($this->table.'.lname','asc');
//			$this->db->limit($limit);
//		}
//		
//		// set pagination
//        $config['full_tag_open'] = "<ul class='pagination'>";
//        $config['full_tag_close'] = "</ul>";
//        $config['num_tag_open'] = "<li class='page-item'>";
//        $config['num_tag_close'] = "</li>";
//        $config['cur_tag_open'] = "<li class='page-item active'>";
//        $config['cur_tag_close'] = "</li>";
//        $config['next_tag_open'] = "<li class='page-item'>";
//        $config['next_tagl_close'] = "</li>";
//        $config['prev_tag_open'] = "<li class='page-item'>";
//        $config['prev_tagl_close'] = "</li>";
//        $config['first_tag_open'] = "<li class='page-item'>";
//        $config['first_tagl_close'] = "</li>";
//        $config['last_tag_open'] = "<li class='page-item'>";
//        $config['last_tagl_close'] = "</li>";
//
//        $config['base_url'] = $this->controller_page . '/show/';
//        $config['per_page'] = $limit;
//        $this->pagination->initialize($config);
//		
//        //get records
//        $this->db->select($this->table . '.*');
//        $this->db->from($this->table);
//		
//
//        // assigning variables
//        $data['sortby'] = $sortby;
//        $data['sortorder'] = $sortorder;
//        $data['limit'] = $limit;
//        $data['offset'] = $offset;
//
//        // get
//        $data['records'] = $this->db->get()->result();
//
//        // load views
//        $this->load->view('header', $data);
//        $this->load->view($this->module_path . '/list');
//        $this->load->view('footer');
//    }
    
    
    public function show()
    {
        //************** general settings *******************
        // load submenu
        $this->submenu();
        $data = $this->data;
        
        $dec_type = $this->config_model->getConfig('Decrypt Data'); 					//encryption type - decrypt
        $enc_key = $this->config_model->getConfig('Encryption Key'); 					//encryption key
        $enc_fields = explode(',', $this->config_model->getConfig('Encrypted Fields '.$this->module)); //encrypted fields
        $enc_type = $this->config_model->getConfig('Encryption Type').'("';
        
        
        // **************************************************
        // variable:field:default_value:operator
        // note: dont include the special query field filter                
        $condition_fields = array(
			array('variable'=>'empNo', 'field'=>$this->table.'.empNo', 'default_value'=>'', 'operator'=>'like_both'),
			array('variable'=>'lname', 'field'=>$this->table.'.lname', 'default_value'=>'', 'operator'=>'like_both'),
			array('variable'=>'fname', 'field'=>$this->table.'.fname', 'default_value'=>'', 'operator'=>'like_both'),
			array('variable'=>'mname', 'field'=>$this->table.'.mname', 'default_value'=>'', 'operator'=>'like_both'),
			array('variable'=>'sex', 'field'=>$this->table.'.sex', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'civilStatus', 'field'=>$this->table.'.civilStatus', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
		);
		
		// sorting fields
		$sorting_fields = array('empID'=>'desc');
        
        $controller = $this->uri->segment(1);
        
        if ($this->uri->segment(3))
            $offset = $this->uri->segment(3);
        else
            $offset = 0;

        // source of filtering
        $filter_source = 0; // default/blank
        if ($this->input->post('filterflag') || $this->input->post('sortby')) {
            $filter_source = 1;
        } else {
            foreach($condition_fields as $key) {
                if ($this->input->post($key['variable'])) {
                    $filter_source = 1; // form filters
                    break;
                }
            }
        }

        if (!$filter_source) {
            foreach($condition_fields as $key) {
                if ($this->session->userdata($controller.'_'.$key['variable']) || $this->session->userdata($controller.'_sortby') || $this->session->userdata($controller.'_sortorder')) {
                    $filter_source = 2; // session
                    break;
                }
            }
        }
        
        switch($filter_source) 
        {
            case 1:
                foreach($condition_fields as $key) {
                    $$key['variable'] = trim($this->input->post($key['variable']));
                }

                $sortby     = trim($this->input->post('sortby'));
                $sortorder  = trim($this->input->post('sortorder'));
                
                break;
            case 2:
                foreach($condition_fields as $key) {
                    $$key['variable'] = $this->session->userdata($controller.'_'.$key['variable']);
                }
                
                $sortby     = $this->session->userdata($controller.'_sortby');
                $sortorder  = $this->session->userdata($controller.'_sortorder');
                break;
            default:
                foreach($condition_fields as $key) {
                    $$key['variable'] = $key['default_value'];
                }
                $sortby     = "";
                $sortorder  = "";
        }

        if ($this->input->post('limit')) {
            if ($this->input->post('limit')=="All")
                $limit = "";
            else
                $limit = $this->input->post('limit');
        } else if ($this->session->userdata($controller.'_limit')) {
            $limit = $this->session->userdata($controller.'_limit');
        } else {
            $limit = 25; // default limit
        }
        
        // set session variables
        foreach($condition_fields as $key) {
            $this->session->set_userdata($controller.'_'.$key['variable'], $$key['variable']);
        }
        $this->session->set_userdata($controller.'_sortby', $sortby);
        $this->session->set_userdata($controller.'_sortorder', $sortorder);
        $this->session->set_userdata($controller.'_limit', $limit);
            
        // assign data variables for views
        foreach($condition_fields as $key) {
            $data[$key['variable']] = $$key['variable'];
        }
        
        // select
        $this->db->select($this->table.'.*');
        
    	//decrypt all encrypted fields
        foreach ($enc_fields as $enc_field) {
			$this->db->select($dec_type."(".$enc_field.",'".$enc_key."') as ".$enc_field);
		}

        // from
        $this->db->from($this->table);
        
        // join
        
        // where
        // set conditions here
        foreach($condition_fields as $key) {
            $operators = explode('_',$key['operator']);
            $operator  = $operators[0];
            // check if the operator is like
            
            if (count($operators)>1) {
                // like operator
                if (trim($$key['variable'])!='' && $key['field']) {
                	if (in_array($key['variable'], $enc_fields)) {
                 		$this->db->$operator($dec_type."(".$key['field'].",'".$enc_key."')", $$key['variable'], $operators[1]);
                 	} else {
                 		$this->db->$operator($key['field'], $$key['variable'], $operators[1]);
                 	}
                }
            } else {
                if (trim($$key['variable'])!='' && $key['field']) {
                
            		if (in_array($key['variable'], $enc_fields)) {
                 		$this->db->$operator($dec_type."(".$key['field'].",'".$enc_key."')", $$key['variable']);
                 	} else {
                 		$this->db->$operator($key['field'], $$key['variable']);
                 	}
                }
            }
        }
        
        // get
        $data['ttl_rows'] = $config['total_rows'] = $this->db->count_all_results();


        
        $config['base_url'] = $this->controller_page.'/show/';
        $config['per_page'] = $limit;
        $this->pagination->initialize($config);
        
        // select
        $this->db->select($this->table.'.*');
    	
        //decrypt all encrypted fields
        foreach ($enc_fields as $enc_field) {
			$this->db->select($dec_type."(".$enc_field.",'".$enc_key."') as ".$enc_field);
		}

        // from
        $this->db->from($this->table);
        
        // join
        
        // where
        // set conditions here
    	foreach($condition_fields as $key) {
            $operators = explode('_',$key['operator']);
            $operator  = $operators[0];
            // check if the operator is like
            
            if (count($operators)>1) {
                // like operator
                if (trim($$key['variable'])!='' && $key['field']) {
                	if (in_array($key['variable'], $enc_fields)) {
                 		$this->db->$operator($dec_type."(".$key['field'].",'".$enc_key."')", $$key['variable'], $operators[1]);
                 	} else {
                 		$this->db->$operator($key['field'], $$key['variable'], $operators[1]);
                 	}
                }
            } else {
                if (trim($$key['variable'])!='' && $key['field']) {
                
            		if (in_array($key['variable'], $enc_fields)) {
                 		$this->db->$operator($dec_type."(".$key['field'].",'".$enc_key."')", $$key['variable']);
                 	} else {
                 		$this->db->$operator($key['field'], $$key['variable']);
                 	}
                }
            }
        }
        
        if ($sortby && $sortorder) {
            $this->db->order_by($sortby, $sortorder);

            if (!empty($sorting_fields)) {
                foreach($sorting_fields as $fld=>$s_order) {
                    if ($fld != $sortby) {
                        $this->db->order_by($fld, $s_order);
                    }
                }
            }
        } else {
            $ctr = 1;
            if (!empty($sorting_fields)) {
                foreach($sorting_fields as $fld=>$s_order) {
                    if ($ctr == 1) {
                        $sortby     = $fld;
                        $sortorder  = $s_order;
                    }
                    $this->db->order_by($fld, $s_order);
                    
                    $ctr++;
                }
            }
        }
            
        if ($limit) {
            if ($offset) {
                $this->db->limit($limit,$offset); 
            } else {
                $this->db->limit($limit); 
            }
        }
        
        // assigning variables
        $data['sortby']     = $sortby;
        $data['sortorder']  = $sortorder;
        $data['limit']      = $limit;
        $data['offset']     = $offset;
        
        // get
        $data['records'] = $this->db->get()->result();
        //echo $this->db->last_query();
        
        // load views
        $this->load->view('header', $data);
        $this->load->view($this->module_path.'/list');
        $this->load->view('footer');
    }
    
    public function printlist()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        //sorting
        
        $dec_type = $this->config_model->getConfig('Decrypt Data'); 					//encryption type - decrypt
        $enc_key = $this->config_model->getConfig('Encryption Key'); 					//encryption key
        $enc_fields = explode(',', $this->config_model->getConfig('Encrypted Fields '.$this->module)); //encrypted fields
        $enc_type = $this->config_model->getConfig('Encryption Type').'("';
        
        // variable:field:default_value:operator
        // note: dont include the special query field filter
        $condition_fields = array(
			array('variable'=>'empNo', 'field'=>$this->table.'.empNo', 'default_value'=>'', 'operator'=>'like_both'),
			array('variable'=>'lname', 'field'=>$this->table.'.lname', 'default_value'=>'', 'operator'=>'like_both'),
			array('variable'=>'fname', 'field'=>$this->table.'.fname', 'default_value'=>'', 'operator'=>'like_both'),
			array('variable'=>'mname', 'field'=>$this->table.'.mname', 'default_value'=>'', 'operator'=>'like_both'),
			array('variable'=>'sex', 'field'=>$this->table.'.sex', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'civilStatus', 'field'=>$this->table.'.civilStatus', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
		);
		
		// sorting fields
		$sorting_fields = array('empID'=>'desc');
        
        $controller = $this->uri->segment(1);
        
        foreach($condition_fields as $key) {
            $$key['variable'] = $this->session->userdata($controller.'_'.$key['variable']);
        }
        
        $limit      = $this->session->userdata($controller.'_limit');
        $offset     = $this->session->userdata($controller.'_offset');
        $sortby     = $this->session->userdata($controller.'_sortby');
        $sortorder  = $this->session->userdata($controller.'_sortorder');
        
        // select
        $this->db->select($this->table.'.*');
        
    	//decrypt all encrypted fields
        foreach ($enc_fields as $enc_field) {
			$this->db->select($dec_type."(".$enc_field.",'".$enc_key."') as ".$enc_field);
		}

        // from
        $this->db->from($this->table);
        
        // join
        
        // where
        // set conditions here
	    foreach($condition_fields as $key) {
            $operators = explode('_',$key['operator']);
            $operator  = $operators[0];
            // check if the operator is like
            
            if (count($operators)>1) {
                // like operator
                if (trim($$key['variable'])!='' && $key['field']) {
                	if (in_array($key['variable'], $enc_fields)) {
                 		$this->db->$operator($dec_type."(".$key['field'].",'".$enc_key."')", $$key['variable'], $operators[1]);
                 	} else {
                 		$this->db->$operator($key['field'], $$key['variable'], $operators[1]);
                 	}
                }
            } else {
                if (trim($$key['variable'])!='' && $key['field']) {
                
            		if (in_array($key['variable'], $enc_fields)) {
                 		$this->db->$operator($dec_type."(".$key['field'].",'".$enc_key."')", $$key['variable']);
                 	} else {
                 		$this->db->$operator($key['field'], $$key['variable']);
                 	}
                }
            }
        }
        
        if ($sortby && $sortorder) {
            $this->db->order_by($sortby, $sortorder);
        
            if (!empty($sorting_fields)) {
                foreach($sorting_fields as $fld=>$s_order) {
                    if ($fld != $sortby) {
                        $this->db->order_by($fld, $s_order);
                    }
                }
            }
        } else {
            $ctr = 1;
            if (!empty($sorting_fields)) {
                foreach($sorting_fields as $fld=>$s_order) {
                    if ($ctr == 1) {
                        $sortby     = $fld;
                        $sortorder  = $s_order;
                    }
                    $this->db->order_by($fld, $s_order);
        
                    $ctr++;
                }
            }
        }
        
        if ($limit) {
            if ($offset) {
                $this->db->limit($limit,$offset);
            } else {
                $this->db->limit($limit);
            }
        }
        
        // assigning variables
        $data['sortby']     = $sortby;
        $data['sortorder']  = $sortorder;
        $data['limit']      = $limit;
        $data['offset']     = $offset;
        
        // get
        $data['records'] = $this->db->get()->result();
        
        
        $data['title'] = $data['current_module']['module_label']." List";

        //load views
        $this->load->view('header_print', $data);
        $this->load->view($this->module_path.'/printlist');
        $this->load->view('footer_print');
    }
    
    function exportlist()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        //sorting
        
        $dec_type = $this->config_model->getConfig('Decrypt Data'); 					//encryption type - decrypt
        $enc_key = $this->config_model->getConfig('Encryption Key'); 					//encryption key
        $enc_fields = explode(',', $this->config_model->getConfig('Encrypted Fields '.$this->module)); //encrypted fields
        $enc_type = $this->config_model->getConfig('Encryption Type').'("';
    
        // variable:field:default_value:operator
        // note: dont include the special query field filter
        $condition_fields = array(
			array('variable'=>'empNo', 'field'=>$this->table.'.empNo', 'default_value'=>'', 'operator'=>'like_both'),
			array('variable'=>'lname', 'field'=>$this->table.'.lname', 'default_value'=>'', 'operator'=>'like_both'),
			array('variable'=>'fname', 'field'=>$this->table.'.fname', 'default_value'=>'', 'operator'=>'like_both'),
			array('variable'=>'mname', 'field'=>$this->table.'.mname', 'default_value'=>'', 'operator'=>'like_both'),
			array('variable'=>'sex', 'field'=>$this->table.'.sex', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'civilStatus', 'field'=>$this->table.'.civilStatus', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
		);
		
		// sorting fields
		$sorting_fields = array('empID'=>'desc');
    
        $controller = $this->uri->segment(1);
    
        foreach($condition_fields as $key) {
            $$key['variable'] = $this->session->userdata($controller.'_'.$key['variable']);
        }
    
        $limit      = $this->session->userdata($controller.'_limit');
        $offset     = $this->session->userdata($controller.'_offset');
        $sortby     = $this->session->userdata($controller.'_sortby');
        $sortorder  = $this->session->userdata($controller.'_sortorder');
    
        // select
        $this->db->select($this->table.'.*');
        
    	//decrypt all encrypted fields
        foreach ($enc_fields as $enc_field) {
			$this->db->select($dec_type."(".$enc_field.",'".$enc_key."') as ".$enc_field);
		}
    
        // from
        $this->db->from($this->table);
    
        // join
    
        // where
        // set conditions here
    	foreach($condition_fields as $key) {
            $operators = explode('_',$key['operator']);
            $operator  = $operators[0];
            // check if the operator is like
            
            if (count($operators)>1) {
                // like operator
                if (trim($$key['variable'])!='' && $key['field']) {
                	if (in_array($key['variable'], $enc_fields)) {
                 		$this->db->$operator($dec_type."(".$key['field'].",'".$enc_key."')", $$key['variable'], $operators[1]);
                 	} else {
                 		$this->db->$operator($key['field'], $$key['variable'], $operators[1]);
                 	}
                }
            } else {
                if (trim($$key['variable'])!='' && $key['field']) {
                
            		if (in_array($key['variable'], $enc_fields)) {
                 		$this->db->$operator($dec_type."(".$key['field'].",'".$enc_key."')", $$key['variable']);
                 	} else {
                 		$this->db->$operator($key['field'], $$key['variable']);
                 	}
                }
            }
        }
    
        if ($sortby && $sortorder) {
            $this->db->order_by($sortby, $sortorder);
    
            if (!empty($sorting_fields)) {
                foreach($sorting_fields as $fld=>$s_order) {
                    if ($fld != $sortby) {
                        $this->db->order_by($fld, $s_order);
                    }
                }
            }
        } else {
            $ctr = 1;
            if (!empty($sorting_fields)) {
                foreach($sorting_fields as $fld=>$s_order) {
                    if ($ctr == 1) {
                        $sortby     = $fld;
                        $sortorder  = $s_order;
                    }
                    $this->db->order_by($fld, $s_order);
    
                    $ctr++;
                }
            }
        }
    
        if ($limit) {
            if ($offset) {
                $this->db->limit($limit,$offset);
            } else {
                $this->db->limit($limit);
            }
        }
    
        // assigning variables
        $data['sortby']     = $sortby;
        $data['sortorder']  = $sortorder;
        $data['limit']      = $limit;
        $data['offset']     = $offset;
    
        // get
        $records = $this->db->get()->result();
    
    
        $title          = $data['current_module']['module_label']." List";
        $companyName    = $this->config_model->getConfig('Company');
        $address        = $this->config_model->getConfig('Address');
         
        //XML Blurb
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
                    <NumberFormat ss:Format='#,##0.00_ ;[Red]\-#,##0.00\ '/>
    		      <Borders>
                    <Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/>
                   </Borders>
            </Style>
    		</Styles>
    
    		<Worksheet ss:Name='".$title."'>
    
    		<Table>
    		<Column ss:Index='1' ss:AutoFitWidth=\"1\" ss:Width='25.00'/>
    		<Column ss:Index='2' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
    		<Column ss:Index='3' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
    		<Column ss:Index='4' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
    		<Column ss:Index='5' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
    		<Column ss:Index='6' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
    		<Column ss:Index='7' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
    		<Column ss:Index='8' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
    		<Column ss:Index='9' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
    		<Column ss:Index='10' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
    		    ";
    
        // header
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='9'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
    
        $data .= "<Row ss:StyleID='s20'>";
        $data .= "<Cell ss:MergeAcross='9'><Data ss:Type='String'>".$companyName."</Data></Cell>";
        $data .= "</Row>";
        $data .= "<Row ss:StyleID='s24A'>";
        $data .= "<Cell ss:MergeAcross='9'><Data ss:Type='String'>".$address."</Data></Cell>";
        $data .= "</Row>";
    
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='9'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
         
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='9'><Data ss:Type='String'>".strtoupper($title)."</Data></Cell>";
        $data .= "</Row>";
         
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='9'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
    
        $fields[]="#";
        $fields[]="ID NUMBER";
        $fields[]="LAST NAME";
        $fields[]="FIRST NAME";
        $fields[]="MIDDLE NAME";
        $fields[]="BIRTH DATE";
        $fields[]="AGE";
        $fields[]="SEX";
        $fields[]="CIVIL STATUS";
        $fields[]="STATUS";
    
        $data .= "<Row ss:StyleID='s24'>";
        //Field Name Data
        foreach ($fields as $fld) {
            $data .= "<Cell ss:StyleID='s23'><Data ss:Type='String'>$fld</Data></Cell>";
        }
        $data .= "</Row>";
    
        if (count($records)) {
            $ctr = 1;
            foreach ($records as $row) {
                if($row->birthDate!="0000-00-00") {
                    $bday = date("M d, Y",strtotime($row->birthDate));
                    $age  = (date('Y') - date('Y',strtotime($row->birthDate)));
                } else {
                    $bday = '';
                    $age = '';
                }
                
                if ($row->sex == 'M') {
                    $sex = "Male";
                } else {
                    $sex = "Female";
                }
                
                $data .= "<Row>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$ctr.".</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$row->empNo."</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$row->lname."</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$row->fname."</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$row->mname."</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$bday."</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$age."</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$sex."</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$row->civilStatus."</Data></Cell>";
                if  ($row->status == 1) {
                    $data .= "<Cell ss:StyleID='s24B'><Data ss:Type='String'>Active</Data></Cell>";
                } else {
                    $data .= "<Cell ss:StyleID='s24B'><Data ss:Type='String'>Inactive</Data></Cell>";
                }
                $data .= "</Row>";
    
                $ctr++;
            }
        }
        $data .= "</Table></Worksheet>";
        $data .= "</Workbook>";
         
    
        //Final XML Blurb
        $filename = "Employee_List";
    
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$filename.xls;");
        header("Content-Type: application/ms-excel");
        header("Pragma: no-cache");
        header("Expires: 0");
         
        echo $data;
    
    }

    public function check_duplicate()
    {
        $this->record->table = $this->table;
        $this->record->where['empNo']  = trim($this->input->post('empNo'));
        $this->record->retrieve();
        if (!empty($this->record->field))
            echo "1"; // duplicate
        else 
            echo "0";
    }


    public function print_record($empID)
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        $data['title'] = "Personal Data Sheet";
        // check roles
        if ($this->roles['view']) {
            $empID  = $this->encrypter->decode($empID);
            
            // for retrieve with joining tables -------------------------------------------------
            // set table
            $this->record->table = $this->table;            
            // set fields for the current table
            $this->record->setFields();         
            // extend fields - join tables          
            $this->record->fields[] = 'currentCountry.country as currentCountry';
            $this->record->fields[] = 'currentProvince.province as currentProvince';
            $this->record->fields[] = 'currentCity.city as currentCity';
            $this->record->fields[] = 'currentCity.zipcode as currentZipcode';
            $this->record->fields[] = 'currentBarangay.barangay as currentBarangay';
            $this->record->fields[] = 'provinceCountry.country as provinceCountry';
            $this->record->fields[] = 'provinceProvince.province as provinceProvince';
            $this->record->fields[] = 'provinceCity.city as provinceCity';
            $this->record->fields[] = 'provinceCity.zipcode as provinceZipcode';
            $this->record->fields[] = 'provinceBarangay.barangay as provinceBarangay';
            $this->record->fields[] = 'tax_exemptions.exemption';
            
            // set joins
            $this->record->joins[]  = array('countries currentCountry',$this->table.'.currentCountryID=currentCountry.countryID','left');
            $this->record->joins[]  = array('provinces currentProvince',$this->table.'.currentProvinceID=currentProvince.provinceID','left');
            $this->record->joins[]  = array('cities currentCity',$this->table.'.currentCityID=currentCity.cityID','left');
            $this->record->joins[]  = array('barangays currentBarangay',$this->table.'.currentBarangayID=currentBarangay.barangayID','left');
            $this->record->joins[]  = array('countries provinceCountry',$this->table.'.provinceCountryID=provinceCountry.countryID','left');
            $this->record->joins[]  = array('provinces provinceProvince',$this->table.'.provinceProvinceID=provinceProvince.provinceID','left');
            $this->record->joins[]  = array('cities provinceCity',$this->table.'.provinceCityID=provinceCity.cityID','left');
            $this->record->joins[]  = array('barangays provinceBarangay',$this->table.'.provinceBarangayID=provinceBarangay.barangayID','left');
            $this->record->joins[]  = array('tax_exemptions',$this->table.'.taxID=tax_exemptions.taxID','left');
            
            // set where
            $this->record->where[$this->table.'.'.$this->pfield] = $empID;
            
            // execute retrieve
            $this->record->retrieve();
            // ----------------------------------------------------------------------------------
            $data['rec'] = $this->record->field;
    
            $data['pdf_paging'] = TRUE;
            $data['title']      = "PERSONAL DATA SHEET";
            $data['modulename'] = "PERSONAL DATA SHEET";            
    
            // load pdf class
            $this->load->library('mpdf');
            // load pdf class
            $this->mpdf->mpdf('en-GB',array(215.9,330.2),10,'Garamond',10,10,25,10,0,0,'P');
            $this->mpdf->setTitle($data['title']);
            $this->mpdf->SetDisplayMode('fullpage');
            $this->mpdf->shrink_tables_to_fit = 1;
            $this->mpdf->SetWatermarkImage(base_url().'images/logo/watermark.png');
            $this->mpdf->watermark_font = 'DejaVuSansCondensed';
            $this->mpdf->watermarkImageAlpha = 0.1;
            $this->mpdf->watermarkImgBehind = TRUE;
            $this->mpdf->showWatermarkImage = TRUE;
    
            // content
            $header = $this->load->view('print_pdf_header', $data, TRUE);
            $this->mpdf->SetHTMLHeader($header);
    
            $footer = $this->load->view('print_pdf_footer', $data, TRUE);
            $this->mpdf->SetHTMLFooter($footer);
    
            $html   = $this->load->view($this->module_path.'/print_record', $data, TRUE);
            $this->mpdf->WriteHTML($html);
    
            $this->mpdf->Output("PERSONAL_DATA_SHEET.pdf","I");
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

	private function _incrementSeries()
    {
        $query = "UPDATE `config` SET `value` = `value`+ 1 WHERE `name` = 'Employee ID Number Series'";
        $this->db->query($query);

        $query = "UPDATE `config` SET `value` = `value`+ 1 WHERE `name` = 'Employee Code Series'";
        $this->db->query($query);
    }
    

}
