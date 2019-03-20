<?php
defined ('BASEPATH') or exit ('No direct script access allowed');

class Stock_withdrawal_slip extends CI_Controller {
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
	var $session_id;
	var $today;
	var $default_ancillary;
	var $current_ancillary;
	
	public function __construct() {
		parent::__construct ();
		$this->load->model('generic_model', 'records');
		$this->module = 'Transactions';
		$this->data['controller_page'] = $this->controller_page = site_url('stock_withdrawal_slip'); // defines contoller link
		$this->table = 'swheaders'; // defines the default table
		$this->pfield = $this->data['pfield'] = 'swID'; // defines primary key
		$this->logfield = 'swheaders';
       // $this->module_path = 'modules/'.strtolower(str_replace(" ", "_", $this->module)) . '/stock_withdrawal_slip'; // defines module path
        $this->module_path  = 'modules/transactions/stock_withdrawal_slip';
		$this->session_id = $this->session->userdata('current_user')->userID;
		$this->today = date('Y-m-d H:i:s');
		$this->default_ancillary = $this->config_model->getConfig('DEFAULT ANCILLARY');
		$this->current_ancillary =  $this->session->userdata('current_user')->ancillaryID;

		

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
		$this->data['current_module'] = $this->modules[$this->module]['sub']['Stock withdrawal slip']; // defines the current sub module
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
	
//	private function _in_used($id = 0) {
//		$tables = array('items' => 'itemID');
//		
//		if (! empty($tables)) {
//			foreach($tables as $table => $fld) {
//				$this->db->where($fld, $id);
//				if ($this->db->count_all_results($table)) {
//					return true;
//				}
//			}
//		}
//		return false;
//	}
	
	public function index() {
		$this->show ();
	}
	
	public function create() {
		$this->submenu ();
		$data = $this->data;
		
		// check roles
		if ($this->roles ['create']) {
            
            $this->db->select('suppliers.suppID');
            $this->db->select('suppliers.suppName');
            $this->db->select('provinces.province as province_name');
            $this->db->select('cities.city as city_name');
            $this->db->select('barangays.barangay as barangay_name');
            $this->db->from('suppliers');
           // $this->db->join('provinces');
            $this->db->join ( 'provinces', 'suppliers.provinceID=provinces.provinceID', 'left' );
            $this->db->join ( 'cities', 'suppliers.cityID=cities.cityID', 'left' );
            $this->db->join ( 'barangays', 'suppliers.barangayID=barangays.barangayID', 'left' );
            //$this->db->where_not_in('ancillaries.status',$this->deleted_status);
			$data['suppliers'] = $this->db->get()->result();
			
			$this->db->select('ancillaries.*');
            $this->db->from('ancillaries');
            //$this->db->where_not_in('ancillaries.status',$this->deleted_status);
			$data['ancillaries'] = $this->db->get()->result();
			

            $this->db->select('items.*');
            $data['items'] = $this->db->get('items')->result();
            //echo json_encode($data['items']);
			//die();
			
			$data['current_ancillary'] = $this->current_ancillary;
			// load views
			$this->load->view('header', $data);
			$this->load->view($this->module_path.'/create');
			$this->load->view('footer');
		
		} else {
			// no access this page
			$data['class'] = "danger";
			$data['msg'] = "Sorry, you don't have access to this page!";
			$data['urlredirect'] = "";
			$this->load->view('header',$data);
			$this->load->view('message');
			$this->load->view('footer');
		}
	}
	public function create_popup() {
		$this->submenu ();
		$data = $this->data;
		
		// check roles
		if ($this->roles ['create']) {
			
			// load views
			$this->load->view('header_popup', $data);
			$this->load->view($this->module_path.'/create_popup');
			$this->load->view('footer_popup');
		
		} else {
			// no access this page
			$data['class'] = "danger";
			$data['msg'] = "Sorry, you don't have access to this page!";
			$data['urlredirect'] = "";
			$this->load->view('header',$data);
			$this->load->view('message');
			$this->load->view('footer');
		}
	}
	
	public function getUmsr()
	{
	    $this->db->order_by('umsr','asc');
	    $records = $this->db->get('unit_measurements');
	    echo $this->frameworkhelper->get_json_data($records, 'umsrID', 'umsr');
	}
	
	public function save_popup() {
		//load submenu
		$this->submenu();
		$data = $this->data;
		$table_fields = array ('umsr');
		
		// check role
		if ($this->roles['create']) {
			$this->records->table = $this->table;
			$this->records->fields = array ();
			
			foreach ($table_fields as $fld) {
				$this->records->fields[$fld] = trim($this->input->post($fld));
			}
			
			
			if ($this->records->save()) {
				$this->records->fields = array();
				$id = $this->records->where['umsrID'] = $this->db->insert_id();
				$this->records->retrieve();
				// record logs
				//$logs = "Record - ".trim($this->input->post($this->logfield));
				//$this->log_model->table_logs($data['current_module']['module_label'], $this->table, $this->pfield, $this->records->field->$data['pfield'], 'Insert', $logs );
				
				$logfield = $this->pfield;
				// success msg
				$data["class"] = "success";
				$data["msg"] = $this->data['current_module']['module_label']." successfully saved.";
				$data["urlredirect"] = "reload_select";
				$data["theFunction"] = "getCountry";
	            $data["activeID"] 	 = $id;
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
	public function save() {
		//load submenu
		$this->submenu();
		$data = $this->data;
		$table_fields = array ('suppID', 'swDate', 'totalAmount', 'suppID', 'ancillaryID', 'requestedBy', 'notedBy', 'withdrawnBy','dateWithdrawn');

		$session_key = $this->input->post("sessionSet");
		$session_data = $_SESSION[$session_key];

	
		// check role
		if ($this->roles['create']) {
			$this->records->table = $this->table;
			$this->records->fields = array ();
			
			foreach ($table_fields as $fld) {
				$this->records->fields[$fld] = trim($this->input->post($fld));
            }
            
            $this->records->fields['createdBy'] = $this->session_id;
			$this->records->fields['dateInserted'] = date('Y-m-d H:i:s');
			
            $date = date('Y-m-d H:i:s', strtotime($this->input->post('swDate')));
			$this->records->fields['swDate'] = $date;

			//date withdrawn
			$dateWithdrawn = date('Y-m-d H:i:s', strtotime($this->input->post('dateWithdrawn')));
			$this->records->fields['dateWithdrawn'] = $dateWithdrawn;

			//$this->records->fields['swNo'] = "1111";

			$ris_no = $this->config_model->getConfig('Stock Withdrawal Series Number');
            $no = "SWS".date('Y-m-d').'0000'.$ris_no;
            $this->records->fields['swNo'] = $no;

			$this->updateConfig();
			
			
			if ($this->records->save()) {
				$this->records->fields = array();
				$id = $this->records->where['swID'] = $this->db->insert_id();
				$this->records->retrieve();
				// record logs
				$logs = "Record - ".trim($this->input->post($this->logfield));
                $this->log_model->table_logs($data['current_module']['module_label'], $this->table, $this->pfield, $id, 'Insert', $logs );
                
				//insert details
					//insert details 
				$session_key = $this->input->post("sessionSet");
				$session_data = $_SESSION[$session_key];
				$_details = [];
				$temp = [];
				foreach($session_data as $key=>$value) {
					foreach($value as $k => $v) {
						if($k == "itemID" || $k == "qty" || $k == "invNo" || $k == "batchNo" || $k == "amount" || $k == "remarks" || $k == "xstockcardID" || $k == "price" || $k == "gstockcardID") {
							$temp['swID'] = $id;
							$temp['dateInserted'] = $this->today;
							$temp[$k] = $v;
						}
					}
					$_details[] = $temp;
				}

				$this->insertDetails("swdetails", $_details);
                
				
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
        $this->db->where('config.name','Stock Withdrawal Series Number');
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
		$session_key = "sws";
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
			//$data['rec'] = $this->records->field;
			$withdrawals = $this->records->field;
			$_data = [];
			foreach($withdrawals as $key => $value) {
				$_data[$key] = $value;
				if($key == "swID") {
					$_data['header_details'] = $this->getDetails('swdetails', 'swID', $value);
				}
				if($key == "suppID") {
					//$_data['supplier_detail'] = $this->getDetails('suppliers', 'suppID', $value);
					$_data['supplier_detail'] = $this->getAddress('suppliers', 'suppID', $value);
				}
				
			}


			$data['rec'] = $_data;
		
			$this->db->select('suppliers.suppID');
            $this->db->select('suppliers.suppName');
            $this->db->select('provinces.province as province_name');
            $this->db->select('cities.city as city_name');
            $this->db->select('barangays.barangay as barangay_name');
            $this->db->from('suppliers');
    
            $this->db->join ( 'provinces', 'suppliers.provinceID=provinces.provinceID', 'left' );
            $this->db->join ( 'cities', 'suppliers.cityID=cities.cityID', 'left' );
            $this->db->join ( 'barangays', 'suppliers.barangayID=barangays.barangayID', 'left' );
			$data['suppliers'] = $this->db->get()->result();
			
			$this->db->select('ancillaries.*');
            $this->db->from('ancillaries');
            //$this->db->where_not_in('ancillaries.status',$this->deleted_status);
            $data['ancillaries'] = $this->db->get()->result();


            $this->db->select('items.*');
			$data['items'] = $this->db->get('items')->result();
			
			

			// load views
			$this->load->view('header', $data);
			$this->load->view($this->module_path.'/edit');
			$this->load->view('footer');
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
	
	public function update() {
		// load submenu
		$this->submenu ();
		$data = $this->data;
		$table_fields = array ('suppID', 'swDate', 'totalAmount', 'suppID', 'ancillaryID', 'requestedBy', 'notedBy', 'withdrawnBy','dateWithdrawn');

	
		
		// check roles
		if ($this->roles['edit']) {
			$this->records->table = $this->table;
			$this->records->fields = array();
			
			foreach ($table_fields as $fld) {
				$this->records->fields[$fld] = trim($this->input->post($fld));
			}
			
			$this->records->pfield = $this->pfield;
			$this->records->pk = $this->encrypter->decode($this->input->post( $this->pfield));

			//date withdrawn
			$dateWithdrawn = date('Y-m-d H:i:s', strtotime($this->input->post('dateWithdrawn')));
			$this->records->fields['dateWithdrawn'] = $dateWithdrawn;

			$date = date('Y-m-d H:i:s', strtotime($this->input->post('swDate')));
			$this->records->fields['swDate'] = $date;


			
			// field logs here
			$wasChange = $this->log_model->field_logs($data['current_module']['module_label'], $this->table, $this->pfield, $this->encrypter->decode($this->input->post($this->pfield)), 'Update', $this->records->fields);
			
			if ($this->records->update ()) {
				// record logs
				if ($wasChange) {
					$logs = "Record - ".trim($this->input->post($this->logfield));
					$this->log_model->table_logs($data['current_module']['module_label'], $this->table, $this->pfield, $this->records->pk, 'Update', $logs);
				}

				//clear header details
				$this->delete_details("swdetails", $this->records->pk);
				$session_key = $this->input->post("sessionSet");
				$session_data = $_SESSION[$session_key];
				$_details = [];
				$temp = [];
				foreach($session_data as $key=>$value) {
					foreach($value as $k => $v) {
						if($k == "itemID" || $k == "qty" || $k == "invNo" || $k == "batchNo" || $k == "amount" || $k == "remarks" || $k == "xstockcardID" || $k == "price" || $k == "gstockcardID") {
							$temp['swID'] = $this->records->pk;
							$temp['dateInserted'] = $this->today;
							$temp[$k] = $v;
						}
					}
					$_details[] = $temp;
				}

				$this->insertDetails("swdetails", $_details);

				
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
	
	public function delete($id = 0) {
		// load submenu
		$this->submenu ();
		$data = $this->data;
		$id = $this->encrypter->decode ( $id );
		
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
	public function generatePdf($id)
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
    
        
		$id = $this->encrypter->decode($id);
		

        
        $this->db->select($this->table.".*");
        //approved by
       	// set table
		$this->records->table = $this->table;
		// set fields for the current table
		$this->records->setFields();
		// set where
		$this->records->where[$this->pfield] = $id;

		//approved by
		$this->db->select('confirmUser.firstName as confirmUserFirstName');
		$this->db->select('confirmUser.middleName as confirmUserMiddleName');
		$this->db->select('confirmUser.lastName as confirmUserLastName');
		//withdrawn by
		$this->db->select('withdrawnUser.firstName as withdrawnUserFirstName');
		$this->db->select('withdrawnUser.middleName as withdrawnUserMiddleName');
		$this->db->select('withdrawnUser.lastName as withdrawnUserLastName');
		//cancel
		$this->db->select('cancelledUser.firstName as cancelledUserFirstName');
		$this->db->select('cancelledUser.middleName as cancelledUserMiddleName');
		$this->db->select('cancelledUser.lastName as cancelledUserLastName');

		
		
		//$this->db->from($this->table);
		//join
		$this->db->join ( 'users', $this->table . '.createdBy=users.userID', 'left' );
		//approved  by or confirm
		$this->db->join ( 'users as confirmUser', $this->table . '.confirmedBy=users.userID', 'left' );
		//withdrawn
		$this->db->join ( 'users as withdrawnUser', $this->table . '.withdrawnBy=users.userID', 'left' );

		//cancel
		$this->db->join ( 'users as cancelledUser', $this->table . '.cancelledBy=users.userID', 'left' );

		
		// execute retrieve
		$this->records->retrieve();
		// ----------------------------------------------------------------------------------
		//$data['rec'] = $this->records->field;
		//$data['rec'] = $this->records->field;
		$withdrawals = $this->records->field;
		$_data = [];
		foreach($withdrawals as $key => $value) {
			$_data[$key] = $value;
			if($key == "swID") {
				$_data['header_details'] = $this->getDetails('swdetails', 'swID', $value);
			}
			if($key == "suppID") {
				//$_data['supplier_detail'] = $this->getDetails('suppliers', 'suppID', $value);
				$_data['supplier_detail'] = $this->getAddress('suppliers', 'suppID', $value);
			}
			
		}


		$data['rec'] = $_data;

    
        // check roles
        if ($this->roles['view']) {
			
			$data['pdf_paging'] = TRUE;
            $data['title']      = "STOCK WITHDRAWAL SLIP";
            $data['modulename'] = "STOCK WITHDRAWAL SLIP";            
            $data['ancillary'] 	= "PHARMACY SECTION";
            $this->load->library('mpdf');
            // load pdf class
            
			

			$this->mpdf->mpdf('en-GB',array(215.9,330.2),10,'Gotham Narrow',10,10,40,10,10,25,'P');
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
			

    
            $this->mpdf->Output("STOCK_WITHDRAWAL_SLIP.pdf","I");
            //$this->mpdf->Output("PHARMACY_CHARGE_SLIP.pdf","I");
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
	
	
	public function view($id) {
		$id = $this->encrypter->decode ( $id );
		
		// load submenu
		$this->submenu ();
		$data = $this->data;
		// $this->roles['view'] = 1;
		if ($this->roles['view']) {
			// for retrieve with joining tables -------------------------------------------------
			$this->db->select($this->table.".*");
			//approved by
            $this->db->select('confirmUser.firstName as confirmUserFirstName');
            $this->db->select('confirmUser.middleName as confirmUserMiddleName');
			$this->db->select('confirmUser.lastName as confirmUserLastName');
			//withdrawn by
			$this->db->select('withdrawnUser.firstName as withdrawnUserFirstName');
            $this->db->select('withdrawnUser.middleName as withdrawnUserMiddleName');
			$this->db->select('withdrawnUser.lastName as withdrawnUserLastName');
			//cancel
			$this->db->select('cancelledUser.firstName as cancelledUserFirstName');
            $this->db->select('cancelledUser.middleName as cancelledUserMiddleName');
			$this->db->select('cancelledUser.lastName as cancelledUserLastName');

			
			
			$this->db->from($this->table);
			//join
			$this->db->join ( 'users', $this->table . '.createdBy=users.userID', 'left' );
			//approved  by or confirm
			$this->db->join ( 'users as confirmUser', $this->table . '.confirmedBy=users.userID', 'left' );
			//withdrawn
			$this->db->join ( 'users as withdrawnUser', $this->table . '.withdrawnBy=users.userID', 'left' );

			//cancel
			$this->db->join ( 'users as cancelledUser', $this->table . '.cancelledBy=users.userID', 'left' );

			
		
			
			$this->db->where($this->pfield, $id);
			// ----------------------------------------------------------------------------------
			//$data['rec'] = $this->db->get()->row();

			$withdrawals = $this->db->get()->row();
			$_data = [];
			foreach($withdrawals as $key => $value) {
				$_data[$key] = $value;
				if($key == "swID") {
					$_data['header_details'] = $this->getDetails('swdetails', 'swID', $value);
				}
				if($key == "suppID") {
					$_data['supplier_detail'] = $this->getAddress('suppliers', 'suppID', $value);
				}
				if($key == "ancillaryID") {
					$_data['ancillary_detail'] = $this->getAncillary('ancillaries', 'ancillaryID', $value);
				}
				
				
			}

			$data['rec'] = $_data;
		
			//$data['in_used'] = $this->_in_used($id);
			
			// record logs
			if ($this->config_model->getConfig('Log all record views') == '1') {
				$logs = "Record - ".$this->records->field->name;
				$this->log_model->table_logs($this->module, $this->table, $this->pfield, $this->records->field->$data['pfield'], 'View', $logs);
			}
			
			//load views
			$this->load->view('header', $data);
			$this->load->view($this->module_path.'/view');
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
	
	public function show() {
		//************** general settings *******************
		// load submenu
		$this->submenu ();
		$data = $this->data;
		
	
		// **************************************************
		// variable:field:default_value:operator
		// note: dont include the special query field filter                
		$condition_fields = array (
			array ('variable' => 'address', 'field' => 'provinces.province', 'default_value' => '', 'operator' => 'where' ), 
			array ('variable' => 'totalAmount', 'field' => $this->table . '.totalAmount', 'default_value' => '', 'operator' => 'like_both' ), 
			//array ('variable' => 'address', 'field' => $this->table . '.address', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'status', 'field' => $this->table . '.status', 'default_value' => '', 'operator' => 'where' ) );
		
		// sorting fields
		$sorting_fields = array ('swID' => 'asc' );
		
		$controller = $this->uri->segment ( 1 );
		
		if ($this->uri->segment ( 3 ))
			$offset = $this->uri->segment ( 3 );
		else
			$offset = 0;
		
		// source of filtering
		$filter_source = 0; // default/blank
		if ($this->input->post ( 'filterflag' ) || $this->input->post ( 'sortby' )) {
			$filter_source = 1;
		} else {
			foreach ( $condition_fields as $key ) {
				if ($this->input->post ( $key ['variable'] )) {
					$filter_source = 1; // form filters
					break;
				}
			}
		}
		
		if (! $filter_source) {
			foreach ( $condition_fields as $key ) {
				if ($this->session->userdata ( $controller . '_' . $key ['variable'] ) || $this->session->userdata ( $controller . '_sortby' ) || $this->session->userdata ( $controller . '_sortorder' )) {
					$filter_source = 2; // session
					break;
				}
			}
		}
		
		switch ($filter_source) {
			case 1 :
				foreach ( $condition_fields as $key ) {
					$$key ['variable'] = trim ( $this->input->post ( $key ['variable'] ) );
				}
				
				$sortby = trim ( $this->input->post ( 'sortby' ) );
				$sortorder = trim ( $this->input->post ( 'sortorder' ) );
				
				break;
			case 2 :
				foreach ( $condition_fields as $key ) {
					$$key ['variable'] = $this->session->userdata ( $controller . '_' . $key ['variable'] );
				}
				
				$sortby = $this->session->userdata ( $controller . '_sortby' );
				$sortorder = $this->session->userdata ( $controller . '_sortorder' );
				break;
			default :
				foreach ( $condition_fields as $key ) {
					$$key ['variable'] = $key ['default_value'];
				}
				$sortby = "";
				$sortorder = "";
		}
		
		if ($this->input->post ( 'limit' )) {
			if ($this->input->post ( 'limit' ) == "All")
				$limit = "";
			else
				$limit = $this->input->post ( 'limit' );
		} else if ($this->session->userdata ( $controller . '_limit' )) {
			$limit = $this->session->userdata ( $controller . '_limit' );
		} else {
			$limit = 25; // default limit
		}
		
		// set session variables
		foreach ( $condition_fields as $key ) {
			$this->session->set_userdata ( $controller . '_' . $key ['variable'], $$key ['variable'] );
		}
		$this->session->set_userdata ( $controller . '_sortby', $sortby );
		$this->session->set_userdata ( $controller . '_sortorder', $sortorder );
		$this->session->set_userdata ( $controller . '_limit', $limit );
		
		// assign data variables for views
		foreach ( $condition_fields as $key ) {
			$data [$key ['variable']] = $$key ['variable'];
		}
		
		// select
		$this->db->select ( $this->table . '.*' );
		
		// from
		$this->db->from ( $this->table );
		
		// join

		$this->db->select('suppliers.suppID');
		$this->db->select('suppliers.suppName');
		$this->db->select('provinces.province as province_name');
		$this->db->select('cities.city as city_name');
		$this->db->select('barangays.barangay as barangay_name');
		// $this->db->from('suppliers');
		// $this->db->join('provinces');
		$this->db->join ( 'suppliers', $this->table.'.suppID=suppliers.suppID', 'left' );
		$this->db->join ( 'provinces', 'suppliers.provinceID=provinces.provinceID', 'left' );
		$this->db->join ( 'cities', 'suppliers.cityID=cities.cityID', 'left' );
		$this->db->join ( 'barangays', 'suppliers.barangayID=barangays.barangayID', 'left' );
		
		// where
		// set conditions here
		foreach ( $condition_fields as $key ) {
			$operators = explode ( '_', $key ['operator'] );
			$operator = $operators [0];
			// check if the operator is like
			if (count ( $operators ) > 1) {
				// like operator
				if (trim ( $$key ['variable'] ) != '' && $key ['field'])
					$this->db->$operator ( $key ['field'], $$key ['variable'], $operators [1] );
			} else {
				if (trim ( $$key ['variable'] ) != '' && $key ['field'])
					$this->db->$operator ( $key ['field'], $$key ['variable'] );
			}
		}
		
		// get
		$data ['ttl_rows'] = $config ['total_rows'] = $this->db->count_all_results ();
		
		// set pagination   
		$config ['full_tag_open'] = "<ul class='pagination'>";
		$config ['full_tag_close'] = "</ul>";
		$config ['num_tag_open'] = "<li class='page-item'>";
		$config ['num_tag_close'] = "</li>";
		$config ['cur_tag_open'] = "<li class='page-item active'>";
		$config ['cur_tag_close'] = "</li>";
		$config ['next_tag_open'] = "<li class='page-item'>";
		$config ['next_tagl_close'] = "</li>";
		$config ['prev_tag_open'] = "<li class='page-item'>";
		$config ['prev_tagl_close'] = "</li>";
		$config ['first_tag_open'] = "<li class='page-item'>";
		$config ['first_tagl_close'] = "</li>";
		$config ['last_tag_open'] = "<li class='page-item'>";
		$config ['last_tagl_close'] = "</li>";
		
		$config ['base_url'] = $this->controller_page . '/show/';
		$config ['per_page'] = $limit;
		$this->pagination->initialize ( $config );
		
		// select
		$this->db->select ( $this->table . '.*' );
		
		// from
		$this->db->from ( $this->table );
		
		
		// join
		$this->db->select('suppliers.suppID');
		$this->db->select('suppliers.suppName');
		$this->db->select('provinces.province as province_name');
		$this->db->select('cities.city as city_name');
		$this->db->select('barangays.barangay as barangay_name');
		// $this->db->from('suppliers');
		// $this->db->join('provinces');
		$this->db->join ( 'suppliers', $this->table.'.suppID=suppliers.suppID', 'left' );
		$this->db->join ( 'provinces', 'suppliers.provinceID=provinces.provinceID', 'left' );
		$this->db->join ( 'cities', 'suppliers.cityID=cities.cityID', 'left' );
		$this->db->join ( 'barangays', 'suppliers.barangayID=barangays.barangayID', 'left' );


		
		// where
		// set conditions here
		foreach ( $condition_fields as $key ) {
			$operators = explode ( '_', $key ['operator'] );
			$operator = $operators [0];
			// check if the operator is like
			if (count ( $operators ) > 1) {
				// like operator
				if (trim ( $$key ['variable'] ) != '' && $key ['field'])
					$this->db->$operator ( $key ['field'], $$key ['variable'], $operators [1] );
			} else {
				if (trim ( $$key ['variable'] ) != '' && $key ['field'])
					$this->db->$operator ( $key ['field'], $$key ['variable'] );
			}
		}
		
		if ($sortby && $sortorder) {
			$this->db->order_by ( $sortby, $sortorder );
			
			if (! empty ( $sorting_fields )) {
				foreach ( $sorting_fields as $fld => $s_order ) {
					if ($fld != $sortby) {
						$this->db->order_by ( $fld, $s_order );
					}
				}
			}
		} else {
			$ctr = 1;
			if (! empty ( $sorting_fields )) {
				foreach ( $sorting_fields as $fld => $s_order ) {
					if ($ctr == 1) {
						$sortby = $fld;
						$sortorder = $s_order;
					}
					$this->db->order_by ( $fld, $s_order );
					
					$ctr ++;
				}
			}
		}
		// foreach ($_POST as $key => $value) {
        //     if($key !== "sortby" && $key !== "sortorder" && $key !== "limit") {

        //         //echo "key =".$key."<br/>";
        //         //echo "\nvalue = ".$value."<br/>";
        //         if($key == "supplier") {
		// 			$data[$key] = $value;
		// 			$this->db->like('suppliers.suppName', $value);
		// 		}
		// 		if($key == "address") {
		// 			$data[$key] = $value;
		// 			$this->db->like('provinces.province', $value);
        //         }
        //         if($key == "totalAmount") {
        //             $data[$key] = $value;
        //             $this->db->like($this->table.'.'.$key, $value);
		// 		}
				
        //     }

        // }

		if ($limit) {
			if ($offset) {
				$this->db->limit ( $limit, $offset );
			} else {
				$this->db->limit ( $limit );
			}
		}
		
		// assigning variables
		$data ['sortby'] = $sortby;
		$data ['sortorder'] = $sortorder;
		$data ['limit'] = $limit;
		$data ['offset'] = $offset;
		
		
		// get
		$data ['records'] = $this->db->get ()->result ();

		$this->db->select('suppliers.suppID');
		$this->db->select('suppliers.suppName');
		$this->db->select('provinces.province as province_name');
		$this->db->select('cities.city as city_name');
		$this->db->select('barangays.barangay as barangay_name');
		$this->db->from('suppliers');
		// $this->db->join('provinces');
		$this->db->join ( 'provinces', 'suppliers.provinceID=provinces.provinceID', 'left' );
		$this->db->join ( 'cities', 'suppliers.cityID=cities.cityID', 'left' );
		$this->db->join ( 'barangays', 'suppliers.barangayID=barangays.barangayID', 'left' );
		//$this->db->where_not_in('ancillaries.status',$this->deleted_status);
		$data['suppliers'] = $this->db->get()->result();
		
		$this->db->select('ancillaries.*');
		$this->db->from('ancillaries');
        $this->db->where_not_in('suppliers.status',$this->deleted_status);
		$data['ancillaries'] = $this->db->get()->result();

		//echo json_encode($data['records']);
		//die();



		// load views
		$this->load->view ( 'header', $data );
		$this->load->view ( $this->module_path . '/list' );
		$this->load->view ( 'footer' );
	}
	
	public function printlist() {
		// load submenu
		$this->submenu ();
		$data = $this->data;
		//sorting
		

		// variable:field:default_value:operator
		// note: dont include the special query field filter
		$condition_fields = array (
			array ('variable' => 'address', 'field' => 'provinces.province', 'default_value' => '', 'operator' => 'where' ), 
			array ('variable' => 'totalAmount', 'field' => $this->table . '.totalAmount', 'default_value' => '', 'operator' => 'like_both' ), 
			//array ('variable' => 'address', 'field' => $this->table . '.address', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'status', 'field' => $this->table . '.status', 'default_value' => '', 'operator' => 'where' ) );
		
		// sorting fields
		$sorting_fields = array ('swID' => 'asc' );
		
		$controller = $this->uri->segment ( 1 );
		
		foreach ( $condition_fields as $key ) {
			$$key ['variable'] = $this->session->userdata ( $controller . '_' . $key ['variable'] );
		}
		
		$limit = $this->session->userdata ( $controller . '_limit' );
		$offset = $this->session->userdata ( $controller . '_offset' );
		$sortby = $this->session->userdata ( $controller . '_sortby' );
		$sortorder = $this->session->userdata ( $controller . '_sortorder' );
		
		
		// select
		$this->db->select ( $this->table . '.*' );
	
		// from
		$this->db->from ( $this->table );
		
		// join

		$this->db->select('suppliers.suppID');
		$this->db->select('suppliers.suppName');
		$this->db->select('provinces.province as province_name');
		$this->db->select('cities.city as city_name');
		$this->db->select('barangays.barangay as barangay_name');
		// $this->db->from('suppliers');
		// $this->db->join('provinces');
		$this->db->join ( 'suppliers', $this->table.'.suppID=suppliers.suppID', 'left' );
		$this->db->join ( 'provinces', 'suppliers.provinceID=provinces.provinceID', 'left' );
		$this->db->join ( 'cities', 'suppliers.cityID=cities.cityID', 'left' );
		$this->db->join ( 'barangays', 'suppliers.barangayID=barangays.barangayID', 'left' );
		
		// where
	
		
		// where
		// set conditions here
		foreach ( $condition_fields as $key ) {
			$operators = explode ( '_', $key ['operator'] );
			$operator = $operators [0];
			// check if the operator is like
			if (count ( $operators ) > 1) {
				// like operator
				if (trim ( $$key ['variable'] ) != '' && $key ['field'])
					$this->db->$operator ( $key ['field'], $$key ['variable'], $operators [1] );
			} else {
				if (trim ( $$key ['variable'] ) != '' && $key ['field'])
					$this->db->$operator ( $key ['field'], $$key ['variable'] );
			}
		}
		
		if ($sortby && $sortorder) {
			$this->db->order_by ( $sortby, $sortorder );
			
			if (! empty ( $sorting_fields )) {
				foreach ( $sorting_fields as $fld => $s_order ) {
					if ($fld != $sortby) {
						$this->db->order_by ( $fld, $s_order );
					}
				}
			}
		} else {
			$ctr = 1;
			if (! empty ( $sorting_fields )) {
				foreach ( $sorting_fields as $fld => $s_order ) {
					if ($ctr == 1) {
						$sortby = $fld;
						$sortorder = $s_order;
					}
					$this->db->order_by ( $fld, $s_order );
					
					$ctr ++;
				}
			}
		}
		
		if ($limit) {
			if ($offset) {
				$this->db->limit ( $limit, $offset );
			} else {
				$this->db->limit ( $limit );
			}
		}
		
		// assigning variables
		$data ['sortby'] = $sortby;
		$data ['sortorder'] = $sortorder;
		$data ['limit'] = $limit;
		$data ['offset'] = $offset;

		foreach ($_POST as $key => $value) {
            if($key !== "sortby" && $key !== "sortorder" && $key !== "limit") {

                //echo "key =".$key."<br/>";
                //echo "\nvalue = ".$value."<br/>";
                if($key == "supplier") {
					$data[$key] = $value;
					$this->db->like('suppliers.suppName', $value);
				}
				if($key == "address") {
					$data[$key] = $value;
					$this->db->like('provinces.province', $value);
                }
                if($key == "totalAmount") {
                    $data[$key] = $value;
                    $this->db->like($this->table.'.'.$key, $value);
				}
			
            }

        }


		
		// get
		$data ['records'] = $this->db->get ()->result ();
		
		$data ['title'] = "Stock Withdrawal slip";
		
		//load views
		$this->load->view ( 'header_print', $data );
		$this->load->view ( $this->module_path . '/printlist' );
		$this->load->view ( 'footer_print' );
	}
	
	function exportlist() {
		// load submenu
		$this->submenu ();
		$data = $this->data;
		//sorting
		

		$condition_fields = array (
			array ('variable' => 'address', 'field' => 'provinces.province', 'default_value' => '', 'operator' => 'where' ), 
			array ('variable' => 'totalAmount', 'field' => $this->table . '.totalAmount', 'default_value' => '', 'operator' => 'like_both' ), 
			//array ('variable' => 'address', 'field' => $this->table . '.address', 'default_value' => '', 'operator' => 'like_both' ), 
			array ('variable' => 'status', 'field' => $this->table . '.status', 'default_value' => '', 'operator' => 'where' ) );
		
		// sorting fields
		$sorting_fields = array ('swID' => 'asc' );

		
		$controller = $this->uri->segment ( 1 );
		
		foreach ( $condition_fields as $key ) {
			$$key ['variable'] = $this->session->userdata ( $controller . '_' . $key ['variable'] );
		}
		
		$limit = $this->session->userdata ( $controller . '_limit' );
		$offset = $this->session->userdata ( $controller . '_offset' );
		$sortby = $this->session->userdata ( $controller . '_sortby' );
		$sortorder = $this->session->userdata ( $controller . '_sortorder' );
		
	
		// select
		$this->db->select ( $this->table . '.*' );
	
		// from
		$this->db->from ( $this->table );
		
		// join

		$this->db->select('suppliers.suppID');
		$this->db->select('suppliers.suppName');
		$this->db->select('provinces.province as province_name');
		$this->db->select('cities.city as city_name');
		$this->db->select('barangays.barangay as barangay_name');
		// $this->db->from('suppliers');
		// $this->db->join('provinces');
		$this->db->join ( 'suppliers', $this->table.'.suppID=suppliers.suppID', 'left' );
		$this->db->join ( 'provinces', 'suppliers.provinceID=provinces.provinceID', 'left' );
		$this->db->join ( 'cities', 'suppliers.cityID=cities.cityID', 'left' );
		$this->db->join ( 'barangays', 'suppliers.barangayID=barangays.barangayID', 'left' );
		
		// where
	
		// set conditions here
		foreach ( $condition_fields as $key ) {
			$operators = explode ( '_', $key ['operator'] );
			$operator = $operators [0];
			// check if the operator is like
			if (count ( $operators ) > 1) {
				// like operator
				if (trim ( $$key ['variable'] ) != '' && $key ['field'])
					$this->db->$operator ( $key ['field'], $$key ['variable'], $operators [1] );
			} else {
				if (trim ( $$key ['variable'] ) != '' && $key ['field'])
					$this->db->$operator ( $key ['field'], $$key ['variable'] );
			}
		}
		
		if ($sortby && $sortorder) {
			$this->db->order_by ( $sortby, $sortorder );
			
			if (! empty ( $sorting_fields )) {
				foreach ( $sorting_fields as $fld => $s_order ) {
					if ($fld != $sortby) {
						$this->db->order_by ( $fld, $s_order );
					}
				}
			}
		} else {
			$ctr = 1;
			if (! empty ( $sorting_fields )) {
				foreach ( $sorting_fields as $fld => $s_order ) {
					if ($ctr == 1) {
						$sortby = $fld;
						$sortorder = $s_order;
					}
					$this->db->order_by ( $fld, $s_order );
					
					$ctr ++;
				}
			}
		}
		
		if ($limit) {
			if ($offset) {
				$this->db->limit ( $limit, $offset );
			} else {
				$this->db->limit ( $limit );
			}
		}
		
		// assigning variables
		$data ['sortby'] = $sortby;
		$data ['sortorder'] = $sortorder;
		$data ['limit'] = $limit;
		$data ['offset'] = $offset;

		foreach ($_POST as $key => $value) {
            if($key !== "sortby" && $key !== "sortorder" && $key !== "limit") {

                //echo "key =".$key."<br/>";
                //echo "\nvalue = ".$value."<br/>";
                if($key == "supplier") {
					$data[$key] = $value;
					$this->db->like('suppliers.suppName', $value);
				}
				if($key == "address") {
					$data[$key] = $value;
					$this->db->like('provinces.province', $value);
                }
                if($key == "totalAmount") {
                    $data[$key] = $value;
                    $this->db->like($this->table.'.'.$key, $value);
				}
            }

        }

		
		// get
		$records = $this->db->get ()->result ();
		
		$title = "Stock withdrawal slip";
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
		$fields[] = "SUPPLIER";
		$fields[] = "ADDRESS";
		$fields[] = "TOTAL AMOUNT";
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
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$row->suppName."</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$row->province_name.' '.$row->city_name.' '.$row->barangay_name."</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$row->totalAmount."</Data></Cell>";
				if ($row->status == 1) {
					$data .= "<Cell ss:StyleID='s24B'><Data ss:Type='String'>Active</Data></Cell>";
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
		$filename = "Stock_withdrawal_slip";
		
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=$filename.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		echo $data;
	
	}
	
	//Conditions and fields changes
	public function check_duplicate() {
		$this->db->where('umsr', trim($this->input->post('umsr')));
		
		if ($this->db->count_all_results($this->table))
			echo "1"; // duplicate
		else
			echo "0";
    }
    
    public function display_session_items($display_area='')
    {

       
        $sessionSet = $this->input->post('sessionSet');
        $records = isset($_SESSION[$sessionSet])? $_SESSION[$sessionSet]:array();

        //item-description,item-id,qty,invNo,batchNo,amount,remark

      

        $headers = array('Stockcard'=>'left', 'Product Name'=>'left', 'Quantity' => 'left', 'Price' => 'left', 'Amount' => 'left', 'Remarks' => 'left');
                $headers = array(
			array('column_header'=>'','column_field'=>'','width'=>'w-5','align'=>'center'),
			array('column_header'=>'Stockcard','column_field'=>'','width'=>'w-10','align'=>''),
            array('column_header'=>'Product Name','column_field'=>'','width'=>'w-10','align'=>''),
            array('column_header'=>'Quantity','column_field'=>'','width'=>'w-10','align'=>''),
            array('column_header'=>'BatchNo','column_field'=>'','width'=>'w-10','align'=>''),
            array('column_header'=>'Sales Invoice','column_field'=>'','width'=>'w-10','align'=>''),
			array('column_header'=>'Price','column_field'=>'','width'=>'w-10','align'=>''),
			array('column_header'=>'Amount','column_field'=>'','width'=>'w-10','align'=>''),
			array('column_header'=>'Remarks','column_field'=>'','width'=>'w-10','align'=>''),
			
          
        );
        //display the value of session key
        $display = array(
			array('align'=>'left','field'=>'stockcardText'),
            array('align'=>'left','field'=>'item-description'),
            array('align'=>'left','field'=>'qty'),
            array('align'=>'left','field'=>'batchNo'),
            array('align'=>'left','field'=>'invNo'),
			array('align'=>'left','field'=>'price'),
			array('align'=>'left','field'=>'amount'),
            array('align'=>'left','field'=>'remarks'),
          
        );
        echo $this->_tm_session_tabular_view($records,$headers,$display,$sessionSet,'950',$display_area);
    }

    private function _tm_session_tabular_view($records, $headers, $display, $sessionID, $width="100%",$display_area='',$default_rows=9,$callback="")
    {
		
        $total_amount = 0;
        $colspan = count($headers)+1;
        $view = '<table class="table hover" id="sw-details-table">'."\n";

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
        $view .= '<tbody id="tbodyid">'."\n";

		//echo json_encode($_SESSION[$sessionID]);
		//die();
		
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
        $view .= '<td class="bg-light"><span><strong>Total </strong></span>'."\n";
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
        $view .= '<td class="bg-light"><strong><span id="id-total">'.number_format($total_amount,2).'</span></strong>'."\n";
		$view .= '<td class="bg-light">'."\n";
		$view .= '</td>'."\n";
		
		
        $view .= '</tr>'."\n";
        $view .= '</tfoot>'."\n";
        $view .= '</table>'."\n";
       
        
        return $view;
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

	public function insertDetails($table, $data)
	{
		return $this->db->insert_batch($table, $data);
	}

	
	public function getDetails($table, $compare, $id)
	{
        //check if the details has a xstockcards
		$this->db->select($table.'.*');
    
		$this->db->from($table);
		$this->db->where($compare, $id);
		//join item
        //$this->db->join ('items', $table.'.itemID=items.itemID', 'left' );
        //$this->db->join ('xstockcards', $table.'.xstockcardID=xstockcards.xstockcardID','left');
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
                
            }
            $result[] = $temp;
        }

        //echo json_encode($result);
        //die();
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
        //join item
        $this->db->join ('items', 'xstockcards.itemID=items.itemID', 'left' );
       // $this->db->join ('xstockcards', $table.'.xstockcardID=xstockcards.xstockcardID','left');
        return  $this->db->get()->row();
        
    }
    

	public function getAddress($table, $compare, $id)
	{
		$this->db->select($table.'.suppName');
		$this->db->select('provinces.province as province_name');
		$this->db->select('cities.city as city_name');
		$this->db->select('barangays.barangay as barangay_name');
		$this->db->from($table);
		$this->db->where($compare, $id);
		// $this->db->join('provinces');
		$this->db->join ( 'provinces', $table.'.provinceID=provinces.provinceID', 'left' );
		$this->db->join ( 'cities', $table.'.cityID=cities.cityID', 'left' );
		$this->db->join ( 'barangays', $table.'.barangayID=barangays.barangayID', 'left' );
		return $this->db->get()->row();
	}

	public function delete_details($table, $swID)
    {
        $this->db->where('swID', $swID);
        $this->db->delete($table);
	}
	
	public function updateStatus()
	{
		$swID = $this->input->post("swID");
		$status = $this->input->post("status");
		$date_label = $this->input->post("date_label");
		$date_by_label = $this->input->post("date_by_label");
		$header_details = $this->input->post("header_details");
		$ancillaryID = trim($this->input->post("ancillaryID"));
		$swNo = trim($this->input->post("swNo"));

		//$this->load->model('generic_model', 'records');
		$this->data['pfield'] = 'swID'; 
		$this->data['table'] = $this->table;
		$data = [
			'status' => $status, // 2 confirm, 3 withdraw, 5 cancel
			$date_label => date('Y-m-d h:i:s'),
			$date_by_label => $this->session_id
		];
		$create_xstockcard = "";
		$create_stockcard = "";
		$update_inventory = "";
		$result = $this->records->updateDate($this->table, $data, "swID", $swID);
		//change to 2 
		if($status == 2) {
			
			$header_details = json_decode($header_details);
		
			for($i = 0; $i < count($header_details); $i++) {
				
				$update_inventory = $this->updateInventory($ancillaryID, $header_details[$i]->itemID, $header_details[$i]->qty, "minus");
				$create_xstockcard = $this->createxStockcard($header_details[$i]->itemID, $ancillaryID, "decrease", $header_details[$i]->qty, $swNo, $header_details[$i]->price, $header_details[$i]->xstockcardID);
				$create_stockcard = $this->createstockcard($header_details[$i]->itemID, $ancillaryID, "decrease", $header_details[$i]->qty, $swNo, $header_details[$i]->price);
		
			}
		
			$_first_name = $this->session->userdata('current_user')->firstName;
			$_middle_name = $this->session->userdata('current_user')->middleName;
			$_last_name = $this->session->userdata('current_user')->lastName;
		}
		
		
		echo json_encode(
			[
				
				'create_stockcard' => $create_stockcard,
				'updated_xstockcard' => $create_xstockcard
			]
		);
	}

	public function getLastInsertStockCard()
	{
		$this->db->select('xstockcards.*');
		//$this->db->limit(1);
		$this->db->order_by('xstockcards.xstockcardID','DESC');
		$this->db->from('xstockcards');
		//$this->db->where('serviceID',$serviceID);
		$category = $this->db->get()->row();

		return $category;
	}

	public function getLastInsertStockCardID($id)
	{
		$this->db->select('xstockcards.*');
		//$this->db->limit(1);
		$this->db->order_by('xstockcards.xstockcardID','DESC');
		$this->db->where('xstockcardID', $id);
		$this->db->from('xstockcards');
		//$this->db->where('serviceID',$serviceID);
		$category = $this->db->get()->row();

		return $category;
	}


	public function checkXstockcard()
    {
        //get stock card by item id
        $ancillaryID = $this->input->post("ancillaryID");
        //$ancillaryID = $this->config_model->getConfig('DEFAULT ANCILLARY');
        $itemID = $this->input->post("itemID");

        //$this->load->model('stockcard_model');
        //$stockcards = $this->stockcard_model->get_stockcards($itemID, $ancillaryID);
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
    

   
	public function getAncillary($table, $compare, $id)
    {
        $this->db->select($table.'.*');
		$this->db->from($table);
		$this->db->where($compare, $id);
		
		return $this->db->get()->result();
	}
	
	public function updateInventory($ancillaryID, $itemID, $qty, $type)
    {
		$this->db->where('itemID', $itemID);
        $this->db->where('ancillaryID', $ancillaryID);
        $this->db->from('inventory');
        $row = $this->db->get()->row();
       // $inventoryID = $row->inventoryID;
        $inventoryQty = $row->qty;
        $new_qty = ($type == "added") ? $inventoryQty + $qty : $inventoryQty - $qty;
        
        //update inventory
        $this->db->set('qty', $new_qty);
        $this->db->where('itemID', $itemID);
        $this->db->where('ancillaryID', $ancillaryID);
        $result = $this->db->update('inventory');
        return $result;
	}
	
	  //create stockcard
    //change the amount and price to issud qty and amount
    //public function createxStockcard($itemID, $ancillaryID, $type, $amount, $refNo,$price)
    public function createxStockcard($itemID, $ancillaryID, $type, $qty, $refNo,$price, $xstockcardID)
    {
       
        $row = $this->db->select("*")->where('itemID', $itemID)->where('ancillaryID', $ancillaryID)->limit(1)->order_by('xstockcardID',"DESC")->get("xstockcards")->row();
        $begBal = $this->db->select("*")->where('itemID', $itemID)->where('ancillaryID', $ancillaryID)->limit(1)->order_by('xstockcardID',"DESC")->get("xstockcards")->row()->endBal;

        $increase = 0;
        $decrease = 0;
        $endbal = 0;

        if(!empty($row) ) {

            $endbal = ($type == "increase") ? $row->endBal + $qty : $row->endBal - $qty;
            $increase = ($type == "increase") ? $qty : 0;
            $decrease = ($type == "decrease") ? $qty : 0;
    
            //as if add or update
            $xstockcard = [
                //'ancillaryID' => $ancillaryID,
                //'itemdID' => $itemID,
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

            
            $item = $this->db->select("*")->where('xstockcardID', $xstockcardID)->limit(1)->order_by('xstockcardID',"DESC")->get("xstockcards")->row();
            //check the main if there a expiry
            if(!empty($item)) {
                //as if add or update
                $xstockcard = [
                    //'ancillaryID' => $ancillaryID,
                    //'itemdID' => $itemID,
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
                    //need to confirm the price change price to amount
                    'price' => $price,
					'begBal' => 0,
					'poNo' => $item->poNo

                ];
        
                $this->db->insert('xstockcards', $xstockcard);
            }
        }

       

       
    }

    public function createstockcard($itemID, $ancillaryID, $type, $qty, $refNo,$price)
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
                //'ancillaryID' => $ancillaryID,
                //'itemdID' => $itemID,
                
                'endBal' => $endbal,
                'increase' => $increase,
                'decrease' => $decrease,
                'ancillaryID' => $ancillaryID,
                'itemID' => $itemID,
                'refNo' => $refNo,
                'dateInserted' => date("Y-m-d h:i:s"),
                //need to confirm the price change price to amount
                'withxstockcard' => $row->withxstockcard,
				'begBal' => $begBal,
				'poNo' => $row->poNo,
				'price' => $row->price

            ];
    
            $this->db->insert('stockcards', $xstockcard);
    
        }
        else {
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
                'withxstockcard' => "Yes",
                'begBal' => 0,
            ];

            $this->db->insert('stockcards', $xstockcard);
        }

       
       
    }
	public function checkQuantity()
    {
		//get the ancillary
        $itemID = $this->input->post('itemID');
		$qty = $this->input->post('qty');
		$ancillaryID = $this->input->post('ancillaryID');
		

		$this->db->where('ancillaryID', $ancillaryID);
		$this->db->where('itemID', $itemID);
		$this->db->from('inventory');
        $row = $this->db->get()->row();

      
        
        if($row->qty <= $qty) {
			$data =  [
				'itemID' => $itemID,
				'quantity' => $qty,
				'ancillaryID' => $ancillaryID,
				'inventory_qty' => $row->qty,
				'status' => true
				
			];
		}
		else {
			$data =  [
				'itemID' => $itemID,
				'quantity' => $qty,
				'ancillaryID' => $ancillaryID,
				'inventory_qty' => $row->qty,
				'status' => false
				
			];
		}
	     echo json_encode($data);
        
    }

}
