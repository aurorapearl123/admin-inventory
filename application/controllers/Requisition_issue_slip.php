<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requisition_issue_slip extends CI_Controller
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
    var $deleted_status;
    var $sesion_id;
    var $today;
    var $default_ancillary;
    var $cluster_funds;
    var $current_ancillary;
    var $current_empno;
    var $current_name;
    var $ris_purpose;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('generic_model','records');
        $this->module       = 'Transactions';
        $this->table        = 'risheaders';                                                 
        $this->pfield       = $this->data['pfield'] = 'risID';                                                 
        $this->logfield     = 'risID';
        $this->module_path  = 'modules/transactions/requisition_issue_slip';           
        $this->data['controller_page']  = $this->controller_page = site_url('requisition_issue_slip');
        $this->deleted_status = -100;
        $this->session_id = $this->session->userdata('current_user')->userID;
        $this->today = date('Y-m-d h:i:s');
        $this->default_ancillary = $this->config_model->getConfig('DEFAULT ANCILLARY');
        $this->cluster_funds = $this->config_model->getConfig('Cluster Funds');
        $this->current_ancillary =  $this->session->userdata('current_user')->ancillaryID;
        $this->current_empno = $this->session->userdata('current_user')->empNo;
        $this->current_name = $this->session->userdata('current_user')->firstName .' '.$this->session->userdata('current_user')->lastName.' '.$this->session->userdata('current_user')->middleName;
        $this->ris_purpose = $this->config_model->getConfig('Ris Purpose');

    
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
        $this->data['current_module']   = $this->modules[$this->module]['sub']['Requisition issue slip'];      // defines the current sub module
        // check roles
        $this->check_roles();
        $this->data['roles']   = $this->roles;
    }
    
    
    private function check_roles()
    {
        $this->roles['create']  = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Add '.$this->data['current_module']['module_label']);
        $this->roles['view']    = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View '.$this->data['current_module']['module_label']);
        $this->roles['edit']    = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Edit '.$this->data['current_module']['module_label']);
        $this->roles['delete']  = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Delete '.$this->data['current_module']['module_label']);
        $this->roles['print']   = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Print '.$this->data['current_module']['module_label']);
        $this->roles['export']  = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Export '.$this->data['current_module']['module_label']);
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
           
            
            $this->db->select('ancillaries.*');
            $this->db->from('ancillaries');
            $this->db->where_not_in('ancillaries.status',$this->deleted_status);
            $this->db->where_not_in('ancillaries.ancillaryID', $this->default_ancillary);
            if($this->current_ancillary == 2) {
                $this->db->where('ancillaries.ancillaryID', $this->current_ancillary);
            }
            if($this->current_ancillary == 3) {
                $this->db->where('ancillaries.ancillaryID', $this->current_ancillary);
            }
            $data['ancillaries'] = $this->db->get()->result();
        
            $this->db->select('items.*');
            $this->db->select('inventory.qty');
            $this->db->from('inventory');
            $this->db->where('inventory.qty >',0);
            $this->db->join('items', 'inventory.itemID=items.itemID', 'left');            
            $this->db->group_by("items.itemID");
            $data['items'] = $this->db->get()->result();



            $this->db->select('items.umsr');
            $this->db->select('items.itemID');
            $this->db->from('inventory');
            $this->db->where('inventory.qty >',0);
            $this->db->join('items', 'inventory.itemID=items.itemID', 'left');            
            $this->db->group_by("items.itemID");
            $data['items_usmr'] = $this->db->get()->result();

           
            
            $fund_clusters = $this->cluster_funds;
            $fund_clusters = explode(',', $fund_clusters);
            $data['fund_clusters'] = $fund_clusters;
            $data['current_ancillary'] = $this->current_ancillary;
            $data['current_name'] = $this->current_name;
            $data['ris_purpose'] = $this->ris_purpose;

            $signatory = $this->getSignatory('1004');

            $data['issuedName'] = $signatory->fullname;
            $data['issuedDesignation'] = $signatory->designation1;

    

            
    
            //check if user is csr and pharma
            if($this->current_ancillary == 2 || $this->current_ancillary == 3) {
                 // load views
                $this->load->view('header', $data);
                $this->load->view($this->module_path.'/users_create');
                $this->load->view('footer');
            }
            else {
                 // load views
                $this->load->view('header', $data);
                $this->load->view($this->module_path.'/create');
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

    public function updateAvailable($data, $originIds)
    {
        //set to no first
        for($a = 0; $a < count($originIds); $a++) {
            //$data['isAvailable'] = 'Y';
            $this->db->where('id', $originIds[$a]);
            $this->db->update('risdetails', ['isAvailable' => 'N']);
        }

        for($i = 0; $i < count($data); $i++) {
            //$data['isAvailable'] = 'Y';
            $this->db->where('id', $data[$i]);
            $this->db->update('risdetails', ['isAvailable' => 'Y']);
        }
        
       
    }

    public function saveToDetails()
    {
       
        $ids = $this->input->post('detailIDs');
        $issuedQtys = $this->input->post('issuedQty');
        $amounts = $this->input->post('amount');
        $prices = $this->input->post("price");
        $isAvailables = $this->input->post('isAvailable');
        $risID = $this->input->post('risID');
        $totalAmount = $this->input->post('totalAmount');
    
        //update available
        $this->updateAvailable($isAvailables, $ids);

        for($i = 0; $i < count($ids); $i++){
            $id = $ids[$i];
            $issuedQty = $issuedQtys[$i];
            $amount = $amounts[$i];
            $data['price'] = $prices[$i];
            $data['issuedQty'] = $issuedQty;
            $data['amount'] = $amount;
            $this->db->where('id', $id);
            $this->db->update('risdetails', $data);
           
        }
        //udpate headers status, totalAmount, date
        $this->updateHeader($risID, $totalAmount);

        $data = $this->data;

        $data["class"] = "success";
        $data["msg"] = $this->data['current_module']['module_label']." successfully saved.";
        $data["urlredirect"] = $this->controller_page."/view/".$this->encrypter->encode($risID);
        $this->load->view("header", $data);
        $this->load->view("message");
        $this->load->view("footer");

      
    }
    public function updateHeader($risID, $totalAmount)
    {
        $this->db->where('risID', $risID);
        $this->db->update('risheaders', ['totalAmount' => $totalAmount]);
    }
    
    public function save() {

       
		//load submenu
		$this->submenu();
		$data = $this->data;
    
        $table_fields = array ('purpose', 'fromAncillaryID', 'responsibilityCenterCode', 'risNo','totalAmount', 'requestedName', 'requestedDesignation', 'approvedName', 'approvedDesignation', 'issuedName', 'issuedDesignation');

        $dateString = $this->input->post('risDate');

       
		// check role
		if ($this->roles['create']) {
			$this->records->table = $this->table;
			$this->records->fields = array ();
			
			foreach ($table_fields as $fld) {
				$this->records->fields[$fld] = trim($this->input->post($fld));
            }
            
            $this->records->fields['createdBy'] = $this->session_id;
            $this->records->fields['dateInserted'] = date('Y-m-d H:i:s');
            //change toAncillaryID to fromAnicillaryID
            $this->records->fields['toAncillaryID'] = $this->config_model->getConfig('DEFAULT ANCILLARY');
            //change to pharmacy for default
            //stock card 
            //only show the pharmacy
        
            $date = date('Y-m-d H:i:s', strtotime($this->input->post('risDate')));
            $this->records->fields['risDate'] = $date;
            //update config risno
           
            $ris_no = $this->config_model->getConfig('Requisition Series Number');
            $no = "RIS".date('Y-m-d').'0000'.$ris_no;
            $this->records->fields['risNo'] = $no;



            $this->updateConfig();


			
			if ($this->records->save()) {
				$this->records->fields = array();
				$id = $this->records->where['risID'] = $this->db->insert_id();
				$this->records->retrieve();
				// record logs
				$logs = "Record - ".trim($this->input->post($this->logfield));
                $this->log_model->table_logs($data['current_module']['module_label'], $this->table, $this->pfield, $id, 'Insert', $logs );
                
				   //add details
                   $session_key = $this->input->post("sessionSet");
                   $session_data = $_SESSION[$session_key];
                   $_details = [];
                   $temp = [];
                   //item-description,itemID,unit,requestedQty,isAvailable,issuedQty,price,amount
                   foreach($session_data as $key=>$value) {
                       foreach($value as $k => $v) {
                           if($k == "itemID" || $k == "requestedQty" || $k == "isAvailable" || $k == "issuedQty" || $k == "amount" || $k == "price" || $k == 'xstockcardID' || $k == 'clusterFund' || $k == 'remarks' || $k == 'gstockcardID') {
                               $temp['risID'] = $id;
                               $temp['dateInserted'] = $this->today;
                               if($k == "isAvailable") {
                                   $temp[$k] = $v ? "Y" : "N";
                               }
                               else {
                                   $temp[$k] = $v;
                               }
                               
                           }
                       }
                       $_details[] = $temp;
                   }
   
                   $this->insertDetails("risdetails", $_details);
           
                
				
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

    public function updateConfig()
    {
        $this->db->where('config.name','Requisition Series Number');
        $configval = $this->db->get('config')->row();
        $val = $configval->value+1;
            
        $this->db->set('value',$val);
        $this->db->where('name',$configval->name);
        $this->db->update('config');
    }
    
    public function edit($id) {
		$this->submenu();
		$data = $this->data;
		$id = $this->encrypter->decode($id);

		//$session_key = $this->input->post('session_key');
		$session_key = "risheaders";
        $this->session->unset_userdata($session_key);
        $session_data = $_SESSION[$session_key];

	
		if ($this->roles['edit']) {
			// for retrieve with joining tables -------------------------------------------------
			// set table
			$this->records->table = $this->table;
			// set fields for the current table
			$this->records->setFields();
			// set where
			$this->records->where[$this->pfield] = $id;
			// execute retrieve
			$this->records->retrieve();
			// ----------------------------------------------------------------------------------
			//$data['rec'] = $this->records->field;

            $risdetails = $this->records->field;

            $_data = [];
			foreach($risdetails as $key => $value) {
				$_data[$key] = $value;
				if($key == "risID") {
					$_data['header_details'] = $this->getDetails('risdetails', 'risID', $value);
				}
				if($key == "fromAncillaryID") {
					$_data['from_ancillary'] = $this->getAncillary('ancillaries', 'ancillaryID', $value);
				}
                if($key == "toAncillaryID") {
					$_data['to_ancillary'] = $this->getAncillary('ancillaries', 'ancillaryID', $value);
				}
				
			}

            $data['rec'] = $_data;
            

            $this->db->select('ancillaries.*');
            $this->db->from('ancillaries');
            $this->db->where_not_in('ancillaries.status',$this->deleted_status);
            $this->db->where_not_in('ancillaries.ancillaryID', $this->default_ancillary);
            if($this->current_ancillary == 2) {
                $this->db->where('ancillaries.ancillaryID', $this->current_ancillary);
            }
            if($this->current_ancillary == 3) {
                $this->db->where('ancillaries.ancillaryID', $this->current_ancillary);
            }
            $data['ancillaries'] = $this->db->get()->result();

            $this->db->select('items.*');
            $this->db->select('inventory.qty');
            $this->db->from('inventory');
            $this->db->where('inventory.qty >',0);
            $this->db->join('items', 'inventory.itemID=items.itemID', 'left');            
            $this->db->group_by("items.itemID");
            $data['items'] = $this->db->get()->result();



            $this->db->select('items.umsr');
            $this->db->select('items.itemID');
            $this->db->from('inventory');
            $this->db->where('inventory.qty >',0);
            $this->db->join('items', 'inventory.itemID=items.itemID', 'left');            
            $this->db->group_by("items.itemID");
            $data['items_usmr'] = $this->db->get()->result();

            
            $fund_clusters = $this->cluster_funds;
            $fund_clusters = explode(',', $fund_clusters);

            $data['fund_clusters'] = $fund_clusters;
            $data['current_name'] = $this->current_name;

            $signatory = $this->getSignatory('1004');

            $data['issuedName'] = $signatory->fullname;
            $data['issuedDesignation'] = $signatory->designation1;

          
            if($this->current_ancillary == 2 || $this->current_ancillary == 3) {
              
                // load views for users
                $this->load->view('header', $data);
                $this->load->view($this->module_path.'/users_edit');
                $this->load->view('footer');
           }
           else {

                // load views
                $this->load->view('header', $data);
                $this->load->view($this->module_path.'/edit');
                $this->load->view('footer');
           }
            
			
			
		} else {
			// no access this page
			$data['class'] = "danger";
			$data['msg'] = "Sorry, you don't have access to this page!";
			$data['urlredirect'] = "";
			$this->load->view ('header',$data);
			$this->load->view ('message');
			$this->load->view ('footer');
		}
	}
	

    
    public function update()
    {

       
        $this->submenu ();
		$data = $this->data;
        $table_fields = array ('purpose', 'fromAncillaryID', 'responsibilityCenterCode', 'totalAmount', 'requestedName', 'requestedDesignation', 'approvedName', 'approvedDesignation', 'issuedName', 'issuedDesignation');
    
		
		// check roles
		if ($this->roles['edit']) {
			$this->records->table = $this->table;
			$this->records->fields = array();
			
			foreach ($table_fields as $fld) {
				$this->records->fields[$fld] = trim($this->input->post($fld));
            }
            $this->records->fields['toAncillaryID'] = $this->config_model->getConfig('DEFAULT ANCILLARY');
            $date = date('Y-m-d H:i:s', strtotime($this->input->post('risDate')));
            $this->records->fields['risDate'] = $date;
			
			$this->records->pfield = $this->pfield;
			$this->records->pk = $this->encrypter->decode($this->input->post( $this->pfield));
			
			// field logs here
			$wasChange = $this->log_model->field_logs($data['current_module']['module_label'], $this->table, $this->pfield, $this->encrypter->decode($this->input->post($this->pfield)), 'Update', $this->records->fields);
			
			if ($this->records->update ()) {
				// record logs
				if ($wasChange) {
					$logs = "Record - ".trim($this->input->post($this->logfield));
					$this->log_model->table_logs($data['current_module']['module_label'], $this->table, $this->pfield, $this->records->pk, 'Update', $logs);
				}

				//clear header details
				$this->delete_details("risdetails", 'risID',$this->records->pk);
				 //add details
                 $session_key = $this->input->post("sessionSet");
                 $session_data = $_SESSION[$session_key];
                 $_details = [];
                 $temp = [];
                 //item-description,itemID,unit,requestedQty,isAvailable,issuedQty,price,amount
                 foreach($session_data as $key=>$value) {
                     foreach($value as $k => $v) {
                         if($k == "itemID" || $k == "requestedQty" || $k == "isAvailable" || $k == "issuedQty" || $k == "amount" || $k == "price" || $k == 'xstockcardID' || $k == 'clusterFund' || $k == 'remarks' || $k == 'gstockcardID') {
                             $temp['risID'] = $this->records->pk;
                             $temp['dateInserted'] = $this->today;
                             if($k == "isAvailable") {
                                 $temp[$k] = $v ? "Y" : "N";
                             }
                             else {
                                 $temp[$k] = $v;
                             }
                             
                         }
                     }
                     $_details[] = $temp;
                 }
 
                 $this->insertDetails("risdetails", $_details);

				
				// successful
				$data["class"] = "success";
				$data["msg"] = $this->data['current_module']['module_label']." successfully updated.";
				$data["urlredirect"] = $this->controller_page . "/view/".trim($this->input->post($this->pfield));
				$this->load->view("header", $data);
				$this->load->view("message");
				$this->load->view("footer");
			} else {
				// error
				$data["class"] = "danger";
				$data["msg"] = "Error in updating the ".$this->data['current_module']['module_label']."!";
				$data["urlredirect"] = $this->controller_page."/view/".$this->records->pk;
				$this->load->view("header", $data);
				$this->load->view("message");
				$this->load->view("footer");
			}
		} else {
			// error
			$data["class"] = "danger";
			$data["msg"] = "Sorry, you don't have access to this page!";
			$data["urlredirect"] = "";
			$this->load->view("header",$data);
			$this->load->view("message");
			$this->load->view("footer");
		}
    }

    private function _in_used($id = 0) {
        // $tables = array('items' => 'itemID');
        
        // if (! empty($tables)) {
        //     foreach($tables as $table => $fld) {
        //         $this->db->where($fld, $id);
        //         if ($this->db->count_all_results($table)) {
        //             return true;
        //         }
        //     }
        // }
        return false;
    }

    public function delete($id=0)
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        $id = $this->encrypter->decode($id);

        // check roles
		if ($this->roles ['delete']) {
			// set fields
			$this->records->fields = array ();
			// set table
			$this->records->table = $this->table;
			// set where
			$this->records->where [$this->pfield] = $id;
			// execute retrieve
			$this->records->retrieve ();
			
			if (! empty ( $this->records->field )) {
				$this->records->pfield = $this->pfield;
				$this->records->pk = $id;
				
				// record logs
				$rec_value = $this->records->field->name;
				
				// check if in used
				if (! $this->_in_used ( $id )) {
					if ($this->records->delete ()) {
						// record logs
						$logfield = $this->logfield;
						
						$logs = "Record - " . $this->records->field->$logfield;
						$this->log_model->table_logs ( $data ['current_module'] ['module_label'], $this->table, $this->pfield, $this->records->pk, 'Delete', $logs );
						
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
        $id = $this->encrypter->decode ( $id );

       
		
		// load submenu
		$this->submenu ();
		$data = $this->data;
        
        
        if ($this->roles['view']) {
            // Setup view
            
            $this->db->select($this->table.".*");
			//approved by
            $this->db->select('confirmUser.firstName as confirmUserFirstName');
            $this->db->select('confirmUser.middleName as confirmUserMiddleName');
			$this->db->select('confirmUser.lastName as confirmUserLastName');
			// Received by
			$this->db->select('receiveUser.firstName as receiveUserFirstName');
            $this->db->select('receiveUser.middleName as receiveUserMiddleName');
			$this->db->select('receiveUser.lastName as receiveUserLastName');
			// //cancel
			$this->db->select('cancelledUser.firstName as cancelledUserFirstName');
            $this->db->select('cancelledUser.middleName as cancelledUserMiddleName');
			$this->db->select('cancelledUser.lastName as cancelledUserLastName');

			
			
			$this->db->from($this->table);
			//join
			$this->db->join ( 'users', $this->table . '.createdBy=users.userID', 'left' );
			// //approved  by or confirm
			$this->db->join ( 'users as confirmUser', $this->table . '.confirmedBy=users.userID', 'left' );
			// //recieve
			$this->db->join ( 'users as receiveUser', $this->table . '.receivedBy=users.userID', 'left' );

			// //cancel
			 $this->db->join ( 'users as cancelledUser', $this->table . '.cancelledBy=users.userID', 'left' );

			
		
			
			$this->db->where($this->pfield, $id);
			// ----------------------------------------------------------------------------------
          
            $risdetails = $this->db->get()->row();

          
			$_data = [];
			foreach($risdetails as $key => $value) {
				$_data[$key] = $value;
				if($key == "risID") {
					$_data['header_details'] = $this->getDetails('risdetails', 'risID', $value, $risdetails->toAncillaryID);
				}
				if($key == "fromAncillaryID") {
					$_data['from_ancillary'] = $this->getAncillary('ancillaries', 'ancillaryID', $value);
				}
                if($key == "toAncillaryID") {
					$_data['to_ancillary'] = $this->getAncillary('ancillaries', 'ancillaryID', $value);
                }
				
			}

            $data['rec'] = $_data;

        

            // record logs
            if ($this->config_model->getConfig('Log all record views') == '1') {
                $logfield = $this->logfield;
                $logs = "Record - ".$this->record->field->$logfield;
                $this->log_model->table_logs($this->data['current_module']['module_label'], $this->table, $this->pfield, $this->record->field->$data['pfield'], 'View', $logs);
            }

            // check if record is used in other tables
            //$data['inUsed'] = $this->_in_used($id);
            $data['current_ancillary'] = $this->current_ancillary;
            $data['current_empno'] = $this->validatePin($this->current_ancillary);
          
             //check if user is csr and pharma
             if($this->current_ancillary == 2 || $this->current_ancillary == 3) {

              
                // load views
                $this->load->view('header', $data);
                $this->load->view($this->module_path.'/users_view');
                $this->load->view('footer');
           }
           else {

                 // load views
                $this->load->view('header', $data);
                $this->load->view($this->module_path.'/view');
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
    
    
    public function show()
    {
        //************** general settings *******************
        // load submenu
        $this->submenu();
        $data = $this->data;
        
        // **************************************************
        // variable:field:default_value:operator
        // note: dont include the special query field filter                
        $condition_fields = array(
            array('variable'=>'ancillaryID', 'field'=>'ancillaries.ancillaryID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'responsibilityCenterCode', 'field'=>$this->table.'.responsibilityCenterCode', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'purpose', 'field'=>$this->table.'.purpose', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
		);
		
		// sorting fields
		$sorting_fields = array('risID'=>'desc');
        
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
       $this->db->select($this->table.'.risID');
        $this->db->select($this->table.'.risNo');
        $this->db->select($this->table.'.responsibilityCenterCode');
        $this->db->select($this->table.'.purpose');
        $this->db->select($this->table.'.status');
       //$this->db->select('from_ancillary.division as from_ancillary_division');
       $this->db->select('ancillaries.division');

       // from
       $this->db->from($this->table);
       
       // join
       $this->db->join ('ancillaries', $this->table . '.fromAncillaryID=ancillaries.ancillaryID', 'left' );
       $this->db->where_not_in($this->table.'.status',$this->deleted_status);
       //check if admin
       $isAdmin = $this->session->userdata('current_user')->isAdmin;
       if(!$isAdmin)
       {
           if($this->current_ancillary != 1) {
                $this->db->where("fromAncillaryID", $this->current_ancillary);
           }
       }
      // 

        
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
       $this->db->select($this->table.'.risID');
        $this->db->select($this->table.'.risNo');
        $this->db->select($this->table.'.responsibilityCenterCode');
        $this->db->select($this->table.'.purpose');
        $this->db->select($this->table.'.status');
       $this->db->select('ancillaries.division');

       // from
       $this->db->from($this->table);
       
       // join
       $this->db->join ('ancillaries', $this->table . '.fromAncillaryID=ancillaries.ancillaryID', 'left' );
       $this->db->where_not_in($this->table.'.status',$this->deleted_status);
         $isAdmin = $this->session->userdata('current_user')->isAdmin;
        if(!$isAdmin)
        {
            if($this->current_ancillary != 1) {
                $this->db->where("fromAncillaryID", $this->current_ancillary);
            }
        }
        
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


        $requisitions = $this->db->get()->result();
        
        $result = [];
        foreach($requisitions as $requisition) {
            $temp = [];
            foreach($requisition as $k => $v) {
                $temp[$k] = $v;
                if($k == "risID") {
                    $temp['receive_count'] = $this->receiveDetails($v,"receive");
                    $temp['unreceive_count'] = $this->receiveDetails($v,"unreceive");
                    $temp['details_count'] = $this->receiveDetails($v,"details");
                }
            }

            $result[] = $temp;
           
        }
        $data['records'] = $result;
        $data['current_ancillary'] = $this->current_ancillary;


        $this->db->select('ancillaries.*');
        $this->db->from('ancillaries');
        $this->db->where_not_in('ancillaries.status',$this->deleted_status);
        $this->db->where_not_in('ancillaries.ancillaryID', $this->default_ancillary);
        if($this->current_ancillary == 2) {
            $this->db->where('ancillaries.ancillaryID', $this->current_ancillary);
        }
        if($this->current_ancillary == 3) {
            $this->db->where('ancillaries.ancillaryID', $this->current_ancillary);
        }
        $data['ancillaries'] = $this->db->get()->result();

        //count all recieve
       

        
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
        

        // variable:field:default_value:operator
        // note: dont include the special query field filter
        $condition_fields = array(
			array('variable'=>'division', 'field'=>'ancillaries.division', 'default_value'=>'', 'operator'=>'like_both'),
			array('variable'=>'responsibilityCenterCode', 'field'=>$this->table.'.responsibilityCenterCode', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'purpose', 'field'=>$this->table.'.purpose', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
		);
		
		// sorting fields
		$sorting_fields = array('risID'=>'desc');
        
        $controller = $this->uri->segment(1);
        
        foreach($condition_fields as $key) {
            $$key['variable'] = $this->session->userdata($controller.'_'.$key['variable']);
        }
        
        $limit      = $this->session->userdata($controller.'_limit');
        $offset     = $this->session->userdata($controller.'_offset');
        $sortby     = $this->session->userdata($controller.'_sortby');
        $sortorder  = $this->session->userdata($controller.'_sortorder');
        
          // select
          $this->db->select($this->table.'.risID');
          $this->db->select($this->table.'.risNo');
          $this->db->select($this->table.'.responsibilityCenterCode');
          $this->db->select($this->table.'.purpose');
          $this->db->select($this->table.'.status');
         $this->db->select('ancillaries.division');
  
         // from
         $this->db->from($this->table);
         
         // join
         $this->db->join ('ancillaries', $this->table . '.fromAncillaryID=ancillaries.ancillaryID', 'left' );
         $this->db->where_not_in($this->table.'.status',$this->deleted_status);
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
    public function generatePdf($id)
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
    
        
        $id = $this->encrypter->decode($id);
        
        $this->db->select($this->table.".*");
        //approved by
        $this->db->select('confirmUser.firstName as confirmUserFirstName');
        $this->db->select('confirmUser.middleName as confirmUserMiddleName');
        $this->db->select('confirmUser.lastName as confirmUserLastName');
        // Received by
        $this->db->select('receiveUser.firstName as receiveUserFirstName');
        $this->db->select('receiveUser.middleName as receiveUserMiddleName');
        $this->db->select('receiveUser.lastName as receiveUserLastName');
        // //cancel
        $this->db->select('cancelledUser.firstName as cancelledUserFirstName');
        $this->db->select('cancelledUser.middleName as cancelledUserMiddleName');
        $this->db->select('cancelledUser.lastName as cancelledUserLastName');

        
        
        $this->db->from($this->table);
        //join
        $this->db->join ( 'users', $this->table . '.createdBy=users.userID', 'left' );
        // //approved  by or confirm
        $this->db->join ( 'users as confirmUser', $this->table . '.confirmedBy=users.userID', 'left' );
        // //recieve
        $this->db->join ( 'users as receiveUser', $this->table . '.receivedBy=users.userID', 'left' );

        // //cancel
         $this->db->join ( 'users as cancelledUser', $this->table . '.cancelledBy=users.userID', 'left' );

        
    
        
        $this->db->where($this->pfield, $id);
        // ----------------------------------------------------------------------------------
    
       
        $risdetails = $this->db->get()->row();
        $_data = [];
        foreach($risdetails as $key => $value) {
            $_data[$key] = $value;
            if($key == "risID") {
                $_data['header_details'] = $this->getDetails('risdetails', 'risID', $value);
            }
            if($key == "fromAncillaryID") {
                $_data['from_ancillary'] = $this->getAncillary('ancillaries', 'ancillaryID', $value);
            }
            if($key == "toAncillaryID") {
                $_data['to_ancillary'] = $this->getAncillary('ancillaries', 'ancillaryID', $value);
            }
            
        }

      
        $this->db->where('code', '1003');
        $this->db->from('signatories');
        $ris_approved_by = $this->db->get()->row()->fullname;


        $this->db->where('code', '1004');
        $this->db->from('signatories');
        $ris_issued_by = $this->db->get()->row()->fullname;

       

        $data['rec'] = $_data;
        $data['ris_approved_by'] = $ris_issued_by;
        $data['ris_issued_by'] = $ris_approved_by;

       
        // check roles
        if ($this->roles['view']) {
    
            $data['pdf_paging'] = TRUE;
            $data['title']      = "REQUISITION AND ISSUE SLIP";
            $data['modulename'] = "REQUISITION AND ISSUE SLIP";            
            //$data['ancillary'] 	= "Central Supply Room";            
            
            //-------------------------------
            // load pdf class
            $this->load->library('mpdf');
            // load pdf class
            $this->mpdf->mpdf('en-GB',"Letter",10,'Gotham Narrow',5,5,50,10,10,25,'P');
            $this->mpdf->setTitle($data['title']);
            $this->mpdf->SetDisplayMode('fullpage');
            $this->mpdf->shrink_tables_to_fit = 1;
            $this->mpdf->SetWatermarkImage(base_url().'images/logo/watermark.png');
            $this->mpdf->watermark_font = 'DejaVuSansCondensed';
            $this->mpdf->watermarkImageAlpha = 0.1;
            $this->mpdf->watermarkImgBehind = TRUE;
            $this->mpdf->showWatermarkImage = TRUE;
    
            // content
            $header = $this->load->view('print_pdf_header_ris', $data, TRUE);
            $this->mpdf->SetHTMLHeader($header);
    
            $footer = $this->load->view('print_pdf_footer', $data, TRUE);
            $this->mpdf->SetHTMLFooter($footer);
    
            $html   = $this->load->view($this->module_path.'/print_record', $data, TRUE);
            $this->mpdf->WriteHTML($html);
           // $this->mpdf->Output();
            $this->mpdf->Output("REQUISITION_ISSUE_SLIP.pdf","I");
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
    
    function exportlist()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        //sorting
         // **************************************************
        // variable:field:default_value:operator
        // note: dont include the special query field filter                
        $condition_fields = array(
            array('variable'=>'ancillaryID', 'field'=>'ancillaries.ancillaryID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'responsibilityCenterCode', 'field'=>$this->table.'.responsibilityCenterCode', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'purpose', 'field'=>$this->table.'.purpose', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
		);
		
		// sorting fields
		$sorting_fields = array('risID'=>'desc');
    
        $controller = $this->uri->segment(1);
    
        foreach($condition_fields as $key) {
            $$key['variable'] = $this->session->userdata($controller.'_'.$key['variable']);
        }
    
        $limit      = $this->session->userdata($controller.'_limit');
        $offset     = $this->session->userdata($controller.'_offset');
        $sortby     = $this->session->userdata($controller.'_sortby');
        $sortorder  = $this->session->userdata($controller.'_sortorder');
        // select
        $this->db->select($this->table.'.risID');
        $this->db->select($this->table.'.risNo');
        $this->db->select($this->table.'.responsibilityCenterCode');
        $this->db->select($this->table.'.purpose');
        $this->db->select($this->table.'.status');
        //$this->db->select('from_ancillary.division as from_ancillary_division');
        $this->db->select('ancillaries.division');

        // from
        $this->db->from($this->table);
        
        // join
        $this->db->join ('ancillaries', $this->table . '.fromAncillaryID=ancillaries.ancillaryID', 'left' );
        $this->db->where_not_in($this->table.'.status',$this->deleted_status);
    
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
    
    
        $title = "Requisition issue slip";
		$companyName = $this->config_model->getConfig('Company');
		$address = $this->config_model->getConfig ('Address');
		
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
    		<Column ss:Index='2' ss:AutoFitWidth=\"1\" ss:Width='80.00'/>
    		<Column ss:Index='3' ss:AutoFitWidth=\"1\" ss:Width='80.00'/>
    		    ";
		
		// header
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='4'><Data ss:Type='String'></Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s20'>";
		$data .= "<Cell ss:MergeAcross='4'><Data ss:Type='String'>".$companyName."</Data></Cell>";
		$data .= "</Row>";
		$data .= "<Row ss:StyleID='s24A'>";
		$data .= "<Cell ss:MergeAcross='4'><Data ss:Type='String'>".$address."</Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='4'><Data ss:Type='String'></Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='4'><Data ss:Type='String'>".strtoupper($title)."</Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='4'><Data ss:Type='String'></Data></Cell>";
		$data .= "</Row>";
		
		$fields[] = "  ";
		$fields[] = "DIVISION";
		$fields[] = "RESPONSIBILITY CENTER CODE";
		$fields[] = "PURPOSE";
		$fields[] = "STATUS";
		
		$data .= "<Row ss:StyleID='s24'>";
		//Field Name Data
		foreach ( $fields as $fld ) {
			$data .= "<Cell ss:StyleID='s23'><Data ss:Type='String'>$fld</Data></Cell>";
		}
		$data .= "</Row>";
		
		if (count ( $records )) {
			$ctr = 1;
			foreach ( $records as $row ) {
				$data .= "<Row>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$ctr.".</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$row->division."</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$row->responsibilityCenterCode."</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$row->purpose."</Data></Cell>";
				if ($row->status == 1) {
					$data .= "<Cell ss:StyleID='s24B'><Data ss:Type='String'>Pending</Data></Cell>";
				}
				else if($row->status == 2){
					$data .= "<Cell ss:StyleID='s24B'><Data ss:Type='String'>Confirmed</Data></Cell>";
				} 
				else {
					$data .= "<Cell ss:StyleID='s24B'><Data ss:Type='String'>Cancelled</Data></Cell>";
				}
				$data .= "</Row>";
				
				$ctr ++;
			}
		}
		$data .= "</Table></Worksheet>";
		$data .= "</Workbook>";
		
		//Final XML Blurb
		$filename = "Requisition_issue_slip";
		
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=$filename.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		echo $data;
        
    
    }

    public function check_duplicate()
    {
        // $this->record->table = $this->table;
        // $this->record->where['empNo']  = trim($this->input->post('empNo'));
        // $this->record->retrieve();
        // if (!empty($this->record->field))
        //     echo "1"; // duplicate
        // else 
        //     echo "0";

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

    public function display_session_items($display_area='')
    {

        $sessionSet = $this->input->post('sessionSet');
        $records = isset($_SESSION[$sessionSet])? $_SESSION[$sessionSet]:array();

        if($this->current_ancillary == 2 || $this->current_ancillary == 3) {
            
            // item-description,item-idrequestedQty,isAvailable,issuedQty,price,amount
                    $headers = array('Stockcard'=>'left','Item'=>'left','Unit'=>'left', 'Requested Qty'=>'left', 'Available'=>'left','Issue Qty'=>'left','Price' => 'left', 'Amount' => 'left', 'Fund Cluster' => 'left', 'Remarks' => 'left');
                    $headers = array(
                array('column_header'=>'','column_field'=>'','width'=>'w-5','align'=>'center'),
               
                array('column_header'=>'Item','column_field'=>'','width'=>'w-10','align'=>''),
                array('column_header'=>'Unit','column_field'=>'','width'=>'w-10','align'=>''),
                array('column_header'=>'Requested Qty','column_field'=>'','width'=>'w-10','align'=>''),
                array('column_header'=>'Fund Cluster','column_field'=>'','width'=>'w-10','align'=>''),
                array('column_header'=>'Remarks','column_field'=>'','width'=>'w-10','align'=>''),
            
            );
            //display the value of session key
            $display = array(
                
                array('align'=>'left','field'=>'item-description'),
                array('align'=>'left','field'=>'unit'),
                array('align'=>'left','field'=>'requestedQty'),
             
                array('align'=>'left','field'=>'clusterFund'),
                array('align'=>'left','field'=>'remarks'),
            
            );
            echo $this->_tm_session_tabular_view_users($records,$headers,$display,$sessionSet,'950',$display_area);
        }
        else {
            // item-description,item-idrequestedQty,isAvailable,issuedQty,price,amount
                    $headers = array('Stockcard'=>'left','Item'=>'left','Unit'=>'left', 'Requested Qty'=>'left', 'Available'=>'left','Issue Qty'=>'left','Price' => 'left', 'Amount' => 'left', 'Fund Cluster' => 'left', 'Remarks' => 'left');
                    $headers = array(
                array('column_header'=>'','column_field'=>'','width'=>'w-5','align'=>'center'),
                array('column_header'=>'Item','column_field'=>'','width'=>'w-10','align'=>''),
                array('column_header'=>'Unit','column_field'=>'','width'=>'w-10','align'=>''),
                array('column_header'=>'Requested Qty','column_field'=>'','width'=>'w-10','align'=>''),
                array('column_header'=>'Available','column_field'=>'','width'=>'w-10','align'=>''),
                array('column_header'=>'Issue Qty','column_field'=>'','width'=>'w-10','align'=>''),
                array('column_header'=>'Price','column_field'=>'','width'=>'w-10','align'=>''),
                array('column_header'=>'Amount','column_field'=>'','width'=>'w-10','align'=>''),
                array('column_header'=>'Fund Cluster','column_field'=>'','width'=>'w-10','align'=>''),
                array('column_header'=>'Remarks','column_field'=>'','width'=>'w-10','align'=>''),
            
            );
            //display the value of session key
            $display = array(
                array('align'=>'left','field'=>'item-description'),
                array('align'=>'left','field'=>'unit'),
                array('align'=>'left','field'=>'requestedQty'),
                array('align'=>'left','field'=>'isAvailable'),
                array('align'=>'left','field'=>'issuedQty'),
                array('align'=>'left','field'=>'price'),
                array('align'=>'left','field'=>'amount'),
                array('align'=>'left','field'=>'clusterFund'),
                array('align'=>'left','field'=>'remarks'),
            
            );
            echo $this->_tm_session_tabular_view($records,$headers,$display,$sessionSet,'950',$display_area);
        }

       
    }

    

    public function add_ris()
    {
        $session_key = $this->input->post('session_key');
        $session_data = $_SESSION[$session_key];
        echo json_encode(['test' => $session_data]);
       // die();
    }

    public function clear_ris_header()
    {
        $session_key = $this->input->post('session_key');
        $this->session->unset_userdata($session_key);
        $session_data = $_SESSION[$session_key];
        echo json_encode(['clear-data' => $session_data]);

    }
    public function checkSession()
	{
		$session_key = $this->input->post('session_key');
		$session_data = $_SESSION[$session_key]; 

		echo json_encode([
			'session_count' => count($session_data)
		]);
	}



    private function _tm_session_tabular_view($records, $headers, $display, $sessionID, $width="100%",$display_area='',$default_rows=9,$callback="")
    {
        $total_amount = 0;
        $colspan = count($headers)+1;
        $view = '<table class="table hover">'."\n";

        //thead
        $view .= '<thead class="thead-light">'."\n";
        if (!empty($headers)) {
            foreach($headers as $col) {
                if ($col['column_field'] == $sortby) {
                    if ($sortorder=="DESC") {
                        $view .= "\n".'<th class="'.$col['width'].'" align="'.$col['align'].'" nowrap>';
                    } else {
                        $view .= "\n".'<th class="'.$col['width'].'" align="'.$col['align'].'" nowrap>';
                    }
                } else {
                    $view .= "\n".'<th class="'.$col['width'].'" align="'.$col['align'].'" nowrap>';
                }
                
                $view .= $col['column_header'];
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
                    <i style="font-size: 24px; color: #14699e!important;"class="la la-trash-o" alt="Delete" title="Delete Row" style="cursor: pointer;" onclick="delete_session_item(\''.$sessionID.'\',\''.$id.'\',\''.$display_area.'\',\''.$callback.'\');"></i>
                    </td>'."\n";
                    
            if(!empty($display)) {
                foreach($display as $td) {
                    $text = $td['field'];
                    
                    if($text == "amount") {
                        $total_amount += $item[$text];
                        //$item[$text] = number_format($item[$text]);
                        $amount = $item[$text];
                        $item[$text] = number_format($amount,2);

                    }
                    if($text == "isAvailable") {
                        $isAvable = ($item[$text] == "Y") ? "Y" : "N";
                        $item[$text] = $isAvable;
                   }
                   if($text == "clusterFund") {
                    $isAvable = ($item[$text] == "0") ? "N/A" : $item[$text];
                    $item[$text] = $isAvable;
                   }
                   if($text == "remarks") {
                    $isAvable = $item[$text] ? $item[$text] : "N/A";
                    $item[$text] = $isAvable;
                   }
                    
                    $view .= '<td align="'.$td['align'].'" nowrap>'.$item[$text].'</td>'."\n";      
                }
            }
            $view .= '</tr>';
            }
        }           
        
        
        $view .= '</tbody>'."\n";
        $view .= '<tfoot>'."\n";
        $view .= '<tr>'."\n";
        $view .= '<td class="bg-light"><strong><span>Total </span></strong>'."\n";
        $view .= '</td>'."\n";
        $view .= '<td class="bg-light">'."\n";
        $view .= '</td>'."\n";
        $view .= '<td class="bg-light">'."\n";
        $view .= '</td>'."\n";
        $view .= '<td class="bg-light">'."\n";
        $view .= '</td>'."\n";
        $view .= '<td class="bg-light">'."\n";
        $view .= '</td>'."\n";
        $view .= '<td class="bg-light">'."\n";
		$view .= '</td>'."\n";
		$view .= '</td><input type="hidden" value="'.$total_amount.'" name="totalAmount" id="totalAmount">'."\n";
        $view .= '<td class="bg-light">'."\n";
        $view .= '<td class="bg-light"><strong><span>'.number_format($total_amount,2).'</span></strong>'."\n";
        $view .= '<td class="bg-light">'."\n";
        $view .= '</td>'."\n";
        $view .= '<td class="bg-light">'."\n";
        $view .= '</td>'."\n";
        $view .= '</td>'."\n";
        $view .= '</tr>'."\n";
        $view .= '</tfoot>'."\n";
        $view .= '</table>'."\n";
    
        
        return $view;
    }


    private function _tm_session_tabular_view_users($records, $headers, $display, $sessionID, $width="100%",$display_area='',$default_rows=9,$callback="")
    {
        $total_amount = 0;
        $colspan = count($headers)+1;
        $view = '<table class="table hover">'."\n";

        //thead
        $view .= '<thead class="thead-light">'."\n";
        if (!empty($headers)) {
            foreach($headers as $col) {
                if ($col['column_field'] == $sortby) {
                    if ($sortorder=="DESC") {
                        $view .= "\n".'<th class="'.$col['width'].'" align="'.$col['align'].'" nowrap>';
                    } else {
                        $view .= "\n".'<th class="'.$col['width'].'" align="'.$col['align'].'" nowrap>';
                    }
                } else {
                    $view .= "\n".'<th class="'.$col['width'].'" align="'.$col['align'].'" nowrap>';
                }
                
                $view .= $col['column_header'];
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
                    <i style="font-size: 24px; color: #14699e!important;"class="la la-trash-o" alt="Delete" title="Delete Row" style="cursor: pointer;" onclick="delete_session_item(\''.$sessionID.'\',\''.$id.'\',\''.$display_area.'\',\''.$callback.'\');"></i>
                    </td>'."\n";
                    
            if(!empty($display)) {
                foreach($display as $td) {
                    $text = $td['field'];
                    
                    if($text == "amount") {
                        $total_amount += $item[$text];
                        //$item[$text] = number_format($item[$text]);
                        $amount = $item[$text];
                        $item[$text] = number_format($amount,2);

                    }
                    if($text == "isAvailable") {
                        $isAvable = ($item[$text] == "Y") ? "Y" : "N";
                        $item[$text] = $isAvable;
                   }
                   if($text == "clusterFund") {
                    $isAvable = ($item[$text] == "0") ? "N/A" : $item[$text];
                    $item[$text] = $isAvable;
                   }
                   if($text == "remarks") {
                    $isAvable = $item[$text] ? $item[$text] : "N/A";
                    $item[$text] = $isAvable;
                   }
                    
                    $view .= '<td align="'.$td['align'].'" nowrap>'.$item[$text].'</td>'."\n";      
                }
            }
            $view .= '</tr>';
            }
        }           
        
        
        $view .= '</tbody>'."\n";
     
        $view .= '</table>'."\n";
    
        
        return $view;
    }

    public function insertDetails($table, $data)
	{
		return $this->db->insert_batch($table, $data);
    }
    

    public function getInventoryQty($itemID, $ancillaryID)
    {
       
        $this->db->select('inventory.qty');
    
		$this->db->from("inventory");
        $this->db->where("itemID", $itemID);
        $this->db->where("ancillaryID", $ancillaryID);
        $items = $this->db->get()->row();
        return $items;
    }

    public function getDetails($table, $compare, $id, $fromAncillaryID = null)
	{
        //check if the details has a xstockcards
        $this->db->select($table.'.*');
        $this->db->select('items.itemCode');
        $this->db->select('items.name as item_name');
        $this->db->select('items.avecost');
        $this->db->select('items.umsr as unit');
        $this->db->select('brands.brand');
    
		$this->db->from($table);
		$this->db->where($compare, $id);
		//join item
        $this->db->join ('items', $table.'.itemID=items.itemID', 'left' );
        $this->db->join ('brands','items.brandID=brands.brandID', 'left' );

        $items = $this->db->get()->result();
        //loop to get the general stockcards or x stockcard
        $result = [];
        foreach($items as $item) {
            foreach($item as $k=>$v) {
                $temp[$k] = $v;
                if($k == "xstockcardID") {
                    $temp['xstockcardID'] = $v;
                    $temp['stock_card_data'] = $this->gextGeneralStockCard($v);
                   
                }
                if($k == "gstockcardID") {
                    $temp['gstockcardID'] = $v;
                    $temp['general_stock_card_data'] = $this->getGeneralStockCard($v);
                   
                }
                if($k == "receivedBy") {
                    $temp['receivedBy'] = $v;
                    $temp['user_data'] = $this->getUserData($v);
                   
                }
                if($k == "itemID") {
                    $temp['inventory'] = $this->getInventoryQty($v, $fromAncillaryID);
                }
                
            }
            $result[] = $temp;
        }
        return $result;

    }



    public function getGeneralStockCard($id)
    {
        $this->db->select('stockcards.id as xstockcardID');
        $this->db->select('stockcards.ancillaryID');
        $this->db->select('stockcards.itemID');
        $this->db->select('stockcards.endBal');
        $this->db->select('stockcards.begBal');
        $this->db->select('stockcards.withxstockcard');
        $this->db->select('items.name as item_name');
        $this->db->select('items.description');
        $this->db->select('items.umsr as unit');
        $this->db->select('items.avecost as price');
        $this->db->select('items.description as description');
        $this->db->from('stockcards');	
        $this->db->join('items', 'stockcards.itemID=items.itemID', 'left');	
        $this->db->where('stockcards.id', $id);
        $stockcard = $this->db->get()->row();
        return $stockcard;

        
        
    }

    public function gextGeneralStockCard($id)
    {
        
        $this->db->select('xstockcards.*');
        $this->db->select('items.name as item_name');
        $this->db->select('items.umsr as unit');
        $this->db->select('items.description as description');
        $this->db->select('xstockcards.expiry');
        $this->db->select('xstockcards.cost');
        $this->db->select('xstockcards.price');
        $this->db->select('xstockcards.begBal');
        $this->db->select('xstockcards.endBal');
        $this->db->select('xstockcards.xstockcardID');
        $this->db->from('xstockcards');
        $this->db->where('xstockcards.xstockcardID', $id);
        $this->db->join ('items', 'xstockcards.itemID=items.itemID', 'left' );
        return  $this->db->get()->row();
        
    }
   
    public function getAncillary($table, $compare, $id)
    {
        $this->db->select($table.'.*');
		$this->db->from($table);
		$this->db->where($compare, $id);
		
		return $this->db->get()->result();
    }

    public function delete_details($table, $primary_key, $id)
    {
        $this->db->where($primary_key, $id);
        $this->db->delete($table);
    }
    public function updateStatus()
	{
		$risID = $this->input->post("risID");
		$status = $this->input->post("status");
		$date_label = $this->input->post("date_label");
        $date_by_label = $this->input->post("date_by_label");
        $risNo = $this->input->post("risNo");
        $fromAncillaryID = $this->input->post("fromAncillaryID");
        $toAncillaryID = $this->input->post("toAncillaryID");
        $header_details = $this->input->post("header_details");
        $totalAmount = $this->input->post("totalAmount");

		$this->data['pfield'] = 'risID'; 
        $this->data['table'] = $this->table;
    
		$data = [
			'status' => $status, // 2 confirm, 3 recieve, 5 cancel
			$date_label => date('Y-m-d h:i:s'),
            $date_by_label => $this->session_id,
            'totalAmount' => $totalAmount
		];
		$result = $this->records->updateDate($this->table, $data, "risID", $risID);
		

		$_first_name = $this->session->userdata('current_user')->firstName;
		$_middle_name = $this->session->userdata('current_user')->middleName;
        $_last_name = $this->session->userdata('current_user')->lastName;
        
    
		echo json_encode(
			[
				
				'status' => $status,
				'first_name' => $_first_name,
			    'middle_name' => $_middle_name,
                'last_name' => $_last_name,
                'data' => $data,
			]
		);
    }

    public function updateInventory($ancillaryID, $itemID, $qty, $type)
    {


        $this->db->where('itemID', $itemID);
        $this->db->where('ancillaryID', $ancillaryID);
        $this->db->from('inventory');
        $row = $this->db->get()->row();
        $inventoryQty = $row->qty;
        $new_qty = ($type == "added") ? $inventoryQty + $qty : $inventoryQty - $qty;
        
        //update inventory
        $this->db->set('qty', $new_qty);
        $this->db->where('itemID', $itemID);
        $this->db->where('ancillaryID', $ancillaryID);
        $result = $this->db->update('inventory');
        return $result;
	}
    
   

    public function checkXstockcard()
    {
        //get stock card by item id
        $ancillaryID = $this->config_model->getConfig('DEFAULT ANCILLARY');
        $itemID = $this->input->post("itemID");
        $stockcards = $this->get_stockcards($itemID, $ancillaryID);

        echo json_encode($stockcards);
      
    }

    public function get_stockcards($itemID, $ancillaryID)
	{


		$this->db->select('xstockcards.*');
		$this->db->select('items.name as item_name');
		$this->db->select('items.description');
		$this->db->select('items.umsr');
		$this->db->from('xstockcards');	
		$this->db->join('items', 'xstockcards.itemID=items.itemID', 'left');	
		$this->db->where('xstockcards.itemID', $itemID);
		$this->db->where('xstockcards.ancillaryID', $ancillaryID);
        $this->db->where('expiry !=', '0000-00-00');
        $this->db->where('xstockcards.endBal >', 0);
        $this->db->where('xstockcards.status !=', -100);
		$this->db->where('items.status !=', -100);
        $this->db->group_by('expiry');
        $this->db->group_by('xstockcards.price');
		$items = $this->db->get()->result();
        // var_dump($items);
        $temp = [];

		if (!empty($items)) {
            
            $arr = [];
			foreach ($items as $item) {
                
				$this->db->select('xstockcards.*');
				$this->db->select('items.name as item_name');
				$this->db->select('items.description');
				$this->db->select('items.umsr');
				$this->db->from('xstockcards');	
				$this->db->join('items', 'xstockcards.itemID=items.itemID', 'left');	
				$this->db->where('xstockcards.itemID', $item->itemID);
				$this->db->where('xstockcards.ancillaryID', $item->ancillaryID);
                $this->db->where('xstockcards.expiry', $item->expiry);
				$this->db->where('xstockcards.price', $item->price);
                //$this->db->where('xstockcards.endBal >', 0);
                $this->db->where('xstockcards.status !=', -100);
		        $this->db->where('items.status !=', -100);
				$this->db->limit(1);
				$this->db->order_by('xstockcardID','desc');
                $a = $this->db->get()->row();
                $arr[] = $a;
                $temp['stock_card'] = $arr;
                
			}

			return $temp;
		} else {

            $this->db->select('stockcards.price');	
			$this->db->from('stockcards');	
			$this->db->join('items', 'stockcards.itemID=items.itemID', 'left');	
			$this->db->where('stockcards.itemID', $itemID);
			$this->db->where('stockcards.ancillaryID', $ancillaryID);
			$this->db->where('stockcards.endBal >', 0);
			$this->db->where('stockcards.status >', 0);
			$this->db->where('items.status >', 0);
			$this->db->group_by('stockcards.price');
			$items = $this->db->get()->result();

			if (!empty($items)) {

				$result = [];
				foreach ($items as $item) {
					//get main stockcards
                    $this->db->select('stockcards.*');
                    $this->db->select('stockcards.id as xstockcardID');
                    $this->db->select('stockcards.ancillaryID');
                    $this->db->select('stockcards.itemID');
                    $this->db->select('stockcards.endBal');
                    $this->db->select('items.name as item_name');
                    $this->db->select('items.description');
                    $this->db->select('items.umsr');
                    $this->db->select('items.avecost as price');
                    $this->db->from('stockcards');	
                    $this->db->join('items', 'stockcards.itemID=items.itemID', 'left');	
                    $this->db->where('stockcards.itemID', $itemID);
                    $this->db->where('stockcards.ancillaryID', $ancillaryID);
                    $this->db->where('stockcards.endBal >', 0);
                    $this->db->where('stockcards.status !=', -100);
                    $this->db->where('items.status !=', -100);
                    $this->db->limit(1);
                    $this->db->order_by('stockcards.id','desc');
                    $stockcards = $this->db->get()->result();
                    $result['stock_card']  = $stockcards;
                   
				}

				return $result;

            }
		}

	}
    
    public function createxStockcard($itemID, $ancillaryID, $type, $qty, $refNo,$price, $xstockcardID, $toAncillaryID)
    {
        if($type == "decrease") 
        {
            
            $endBal = 0;
            $expiry = "";
            $rows = $this->db->select("*")->where('itemID', $itemID)->where('ancillaryID', $ancillaryID)->order_by('expiry',"ASC")->get("xstockcards")->result();
            foreach($rows as $xstockcard) {
                $endBal = $xstockcard->endBal;
                //for decrease
                if($endBal < $qty) {
                    //set to sezo
                    $qty = $qty - $xstockcard->endBal;
                    $increase = 0;
                    $decrease = $xstockcard->endBal;
                    $endBal = $xstockcard->endBal - $decrease;
                    //as if add or update
                    $xstockcard = [
                        'expiry' => $xstockcard->expiry,
                        'cost' => $xstockcard->cost,
                        'markupType' => $xstockcard->markupType,
                        'markup' => $xstockcard->markup,
                        'endBal' => $endBal,
                        'increase' => $increase,
                        'decrease' => $decrease,
                        'ancillaryID' => $ancillaryID,
                        'itemID' => $itemID,
                        'refNo' => $refNo,
                        'dateInserted' => date("Y-m-d h:i:s"),
                        'price' => $price,
                        'begBal' => $xstockcard->endBal,
                        'poNo' => $xstockcard->poNo

                    ];
            
                    $this->db->insert('xstockcards', $xstockcard);

                }
                else 
                {
                    $increase = 0;
                    $decrease = $qty;
                    $endBal = $xstockcard->endBal - $decrease;
                    $qty = $qty - $xstockcard->endBal;
                    
                    if($decrease > 0) 
                    {
                        //as if add or update
                        $xstockcard = [
                            'expiry' => $xstockcard->expiry,
                            'cost' => $xstockcard->cost,
                            'markupType' => $xstockcard->markupType,
                            'markup' => $xstockcard->markup,
                            'endBal' => $endBal,
                            'increase' => $increase,
                            'decrease' => $decrease,
                            'ancillaryID' => $ancillaryID,
                            'itemID' => $itemID,
                            'refNo' => $refNo,
                            'dateInserted' => date("Y-m-d h:i:s"),
                            'price' => $price,
                            'begBal' => $xstockcard->endBal,
                            'poNo' => $xstockcard->poNo

                        ];
                
                        $this->db->insert('xstockcards', $xstockcard);
                    }
            
                }

            }
        }
        if($type == "increase") {

            $row = $this->db->select("*")->where('itemID', $itemID)->where('ancillaryID', $ancillaryID)->limit(1)->order_by('expiry',"ASC")->get("xstockcards")->row();
            $begBal = $this->db->select("*")->where('itemID', $itemID)->where('ancillaryID', $ancillaryID)->limit(1)->order_by('expiry',"ASC")->get("xstockcards")->row()->endBal;
            $increase = 0;
            $decrease = 0;
            $endbal = 0;
    
            if(!empty($row) ) {
    
                $endbal = ($type == "increase") ? $row->endBal + $qty : $row->endBal - $qty;
                $increase = ($type == "increase") ? $qty : 0;
                $decrease = ($type == "decrease") ? $qty : 0;
        
                //as if add or update
                $xstockcard = [
                    'expiry' => $row->expiry,
                    'cost' => $row->cost,
                    'markupType' => $row->markupType,
                    'markup' => $row->markup,
                    'endBal' => $endbal,
                    'increase' => $increase,
                    'decrease' => $decrease,
                    'ancillaryID' => $ancillaryID,
                    'itemID' => $itemID,
                    'refNo' => $refNo,
                    'dateInserted' => date("Y-m-d h:i:s"),
                    //need to confirm the price change price to amount
                    'price' => $price,
                    'begBal' => $begBal,
                    'poNo' => $row->poNo
    
                ];
        
                $this->db->insert('xstockcards', $xstockcard);
         
            }
            else {
                //new x stock card create if pharmacy and csr are not exist in x stockcard
                $endbal = ($type == "increase") ? $qty : $qty;
                $increase = ($type == "increase") ? $qty : 0;
                $decrease = ($type == "decrease") ? $qty : 0;
        
                $item = $this->db->select("*")->where('itemID', $itemID)->where('ancillaryID', $toAncillaryID)->limit(1)->order_by('xstockcardID',"DESC")->get("xstockcards")->row();
    
                //check the main if there a expiry
                if(!empty($item)) {
                    //as if add or update
                    $xstockcard = [
                        'expiry' => $item->expiry,
                        'cost' => $item->cost,
                        'markupType' => $item->markupType,
                        'markup' => $item->markup,
                        'endBal' => $endbal,
                        'increase' => $increase,
                        'decrease' => $decrease,
                        'ancillaryID' => $ancillaryID,
                        'itemID' => $itemID,
                        'refNo' => $refNo,
                        'dateInserted' => date("Y-m-d h:i:s"),
                        'price' => $price,
                        'begBal' => 0,
                        'poNo' => $item->poNo
    
                    ];
            
                    $this->db->insert('xstockcards', $xstockcard);
                }
            }
        }
      

    }

    public function createstockcard($itemID, $ancillaryID, $type, $qty, $refNo,$price, $toAncillaryID)
    {
       

        $row = $this->db->select("*")->where('itemID', $itemID)->where('ancillaryID', $ancillaryID)->limit(1)->order_by('id',"DESC")->get("stockcards")->row();
        $begBal = $this->db->select("*")->where('itemID', $itemID)->where('ancillaryID', $ancillaryID)->limit(1)->order_by('id',"DESC")->get("stockcards")->row()->endBal;
       
        $increase = 0;
        $decrease = 0;
        $endbal = 0;

        if(!empty($row)) {
           
            $endbal = ($type == "increase") ? $row->endBal + $qty : $row->endBal - $qty;
            $increase = ($type == "increase") ? $qty : 0;
            $decrease = ($type == "decrease") ? $qty : 0;

            $xstockcard = [
               
                'endBal' => $endbal,
                'increase' => $increase,
                'decrease' => $decrease,
                'ancillaryID' => $ancillaryID,
                'itemID' => $itemID,
                'refNo' => $refNo,
                'dateInserted' => date("Y-m-d h:i:s"),
                'withxstockcard' => $row->withxstockcard,
                'begBal' => $begBal,
                'poNo' => $row->poNo,
                'price' => $row->price

            ];
    
            $this->db->insert('stockcards', $xstockcard);
    
        }
        else {

            $item = $this->db->select("*")->where('itemID', $itemID)->where('ancillaryID', $toAncillaryID)->limit(1)->order_by('id',"DESC")->get("stockcards")->row();
            //new general stock card create if pharmacy and csr are not exist in general stockcard
            $endbal = ($type == "increase") ? $qty :  $qty;
            $increase = ($type == "increase") ? $qty : 0;
            $decrease = ($type == "decrease") ? $qty : 0;

            $xstockcard = [
                'endBal' => $endbal,
                'increase' => $increase,
                'decrease' => $decrease,
                'ancillaryID' => $ancillaryID,
                'itemID' => $itemID,
                'refNo' => $refNo,
                'dateInserted' => date("Y-m-d h:i:s"),
                'withxstockcard' => $item->withxstockcard,
                'begBal' => 0,
                'poNo' => $item->poNo,
                'price' => $item->price
            ];

            $this->db->insert('stockcards', $xstockcard);
        }

       
       
    }
    public function checkQuantity()
    {

        $itemID = $this->input->post('itemID');
		$issuedQty = $this->input->post('issuedQty');
		
        $ancillaryID = $this->default_ancillary;
        $begBal = $this->db->select("*")->where('itemID', $itemID)->where('ancillaryID', $ancillaryID)->limit(1)->order_by('id',"DESC")->get("stockcards")->row()->endBal;


      
        
        if($begBal < $issuedQty) {
			$data =  [
				'itemID' => $itemID,
				'issuedQty' => $issuedQty,
				'ancillaryID' => $this->default_ancillary,
				'inventory_qty' => $row->qty,
				'status' => true
				
			];
		}
		else {
			$data =  [
				'itemID' => $itemID,
				'issuedQty' => $issuedQty,
				'ancillaryID' => $this->default_ancillary,
				'inventory_qty' => $row->qty,
				'status' => false
				
			];
		}
	     echo json_encode($data);
        
    }

    public function updateDetails()
    {
        $id = $this->input->post("id");
        $toAncillaryID = $this->input->post('toAncillaryID');
        $fromAncillaryID = $this->input->post('fromAncillaryID');
        $risNo = $this->input->post("risNo");
        $userID = $this->input->post("userID");
        $risID = $this->input->post("risID");

        
        
        $updated = [];
        
        $row = $this->db->select("*")->where('id', $id)->limit(1)->get("risdetails")->row();
        //update receivedBy, dateReceived 
        $data = [
            'receivedBy' => $userID,
            'dateReceived' => date('Y-m-d h:i:s'),
        ];
        $result = $this->editDetails($id, $data);
        //$result = true;
        if($result) {

            //check if the receive count is equal to details
            $receive_count = $this->receiveDetails($risID,"receive");
            $unreceive_count = $this->receiveDetails($risID,"unreceive");
            $details_count = $this->receiveDetails($risID,"details");

            //$receive_count_track = $receive_count + 1;
            if($receive_count == $details_count) {
               //update header 
                $data = [
                    'status' => 3, // 2 confirm, 3 recieve, 5 cancel
                    'dateReceived' => date('Y-m-d h:i:s'),
                    //double check the header receiveby is from user or fron pin user
                    'receivedBy' => $this->session_id
                ];
                $this->records->updateDate($this->table, $data, "risID", $risID);
            }
            else {
               //nothing to do
            }

        }
        else {
            //controll error 
            $updated['error_update_details'] = true;
        }
        
    
        // //add
        // //check if xstockcard not exist then create
        $update_inventory = $this->updateInventory($toAncillaryID, $row->itemID, $row->issuedQty, "minus");
        $create_xstockcard = $this->createxStockcard($row->itemID, $toAncillaryID, "decrease", $row->issuedQty, $risNo, $row->price, $row->xstockcardID, $toAncillaryID);
        $create_xstockcard = $this->createxStockcard($row->itemID, $fromAncillaryID, "increase", $row->issuedQty, $risNo, $row->price, $row->xstockcardID, $toAncillaryID);
        
        // // //minus
        $update_inventory = $this->updateInventory($fromAncillaryID, $row->itemID, $row->issuedQty, "added");
        $create_stockcard = $this->createstockcard($row->itemID, $toAncillaryID, "decrease", $row->issuedQty, $risNo, $row->price, $toAncillaryID);
        $create_stockcard = $this->createstockcard($row->itemID, $fromAncillaryID, "increase", $row->issuedQty, $risNo, $row->price, $toAncillaryID);


        echo json_encode($create_xstockcard);
    

        $updated = [
           $update_inventory,
          // $create_xstockcard,
            $data
        ];
        echo json_encode($updated);
    }

    public function editDetails($id, $data)
    {
		
		$this->db->where('id', $id);
		return $this->db->update('risdetails', $data);
		//return $this->db->update('swHeaders', $data);
		
    }
    


    public function validatePin($id)
    {
        $this->db->select('users.pin');
        $this->db->select('users.userID');
        $this->db->select('users.userName');
        $this->db->where('ancillaryID', $id);
        $this->db->from('users');
        return $this->db->get()->result();
    }

    public function getUserData($id)
    {
        $this->db->select('users.pin');
        $this->db->select('users.userName');
        $this->db->where('userID', $id);
        $this->db->from('users');
        return $this->db->get()->row();
    }

    public function receiveDetails($risID, $type)
    {
      
        $this->db->where('risID', $risID);
        if($type == "receive") {
            $this->db->where('receivedBy !=', "");
        }
        else if($type == "unreceive"){
            $this->db->where('receivedBy', "");
        }        
        $this->db->from('risdetails');
        return $this->db->count_all_results();
    }

    public function deleteDetails()
    {
        $id = $this->input->post('id');
        $this->delete_details("risdetails", "id", $id);

        $data = [
            'isDeleted' => true
        ];

        echo json_encode($data);
    }

    public function getSignatory($code)
    {
        $this->db->where('code', $code);
        $this->db->from('signatories');
        return $this->db->get()->row();
        //return $this->db->get()->row()->fullname;
    }


}
