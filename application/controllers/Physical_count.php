<?php
defined ('BASEPATH') or exit ('No direct script access allowed');

class Physical_count extends CI_Controller {
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
	
	public function __construct() {
		parent::__construct ();
		$this->load->model('generic_model', 'records');
		$this->module = 'Inventory';
		$this->data['controller_page'] = $this->controller_page = site_url('physical_count'); // defines contoller link
		$this->table = 'pcheaders'; // defines the default table
		$this->pfield = $this->data['pfield'] = 'pcID'; // defines primary key
		$this->logfield = 'pcNo';
		$this->module_path = 'modules/'.strtolower(str_replace(" ", "_", $this->module)) . '/Physical_Count'; // defines module path
		

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
		$this->data['current_module'] = $this->modules[$this->module]['sub']['Physical Count']; // defines the current sub module
		// check roles
		$this->check_roles ();
		$this->data ['roles'] = $this->roles;
	}
	
	private function check_roles() {
		// check roles
		$this->roles['create'] 	= $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'Add '.$this->data['current_module']['module_label']);
		$this->roles['view'] 	= $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'View '.$this->data['current_module']['module_label']);
		$this->roles['edit'] 	= $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'Edit Existing '.$this->data['current_module']['module_label']);
		$this->roles['delete'] 	= $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'Delete '.$this->data['current_module']['module_label']);
		$this->roles['approve'] = $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'Approve '.$this->data['current_module']['module_label']);
	}
	
	private function _in_used($id = 0) {
		$tables = array('itempricecanvas' => 'itemID');
		
		if (! empty($tables)) {
			foreach($tables as $table => $fld) {
				$this->db->where($fld, $id);
				if ($this->db->count_all_results($table)) {
					return true;
				}
			}
		}
		return false;
	}
	
	private function _generateID()
	{
		$seriesNo = $this->config_model->getConfig('Physical Count Series Number');
		if ($seriesNo < 10) {
			$series = "00000".$seriesNo;
		}else if ($seriesNo < 100) {
			$series = "0000".$seriesNo;
		}else if ($seriesNo < 1000) {
			$series = "000".$seriesNo;
		}else if ($seriesNo < 10000) {
			$series = "00".$seriesNo;
		}else if ($seriesNo < 100000) {
			$series = "0".$seriesNo;
		}
		
		return "PC".date('d-Y-').$series;
	}
	
	public function index() {
		$this->show ();
	}
	
	public function getItem()
	{
	    $this->db->order_by('name','asc');
	    $records = $this->db->get('items');
	    echo $this->frameworkhelper->get_json_data($records, 'itemID', 'name');
	}
	
	public function create($ancillaryID=0, $catID=0) {
		$this->submenu ();
		$data = $this->data;
		
		// check roles
		if ($this->roles ['create']) {
			
			if ($ancillaryID) {
				$this->db->select('stockcards.*');
		    	$this->db->select('ancillaries.ancillaryID');
		    	$this->db->select('items.catID');
	    		$this->db->select('items.name');
	    		$this->db->select('items.description');
	    		$this->db->select('items.umsr');
	    		$this->db->select('items.lastcost');
	    		$this->db->select('category.category');
	    		$this->db->from('stockcards');
	    		$this->db->join('ancillaries','stockcards.ancillaryID=ancillaries.ancillaryID', 'left');
	    		$this->db->join('items','stockcards.itemID=items.itemID', 'left');
	    		$this->db->join('category','items.catID=category.catID', 'left');
				if ($ancillaryID) {
					$this->db->where('stockcards.ancillaryID', $ancillaryID);
				}
				if ($catID) {
					$this->db->where('items.catID', $catID);
				}
				$this->db->where('stockcards.endBal >', 0);
				$this->db->order_by('items.name', 'asc');
				$this->db->order_by('stockcards.dateInserted', 'desc');
				$this->db->group_by('stockcards.itemID');
				$data['items'] = $this->db->get()->result();
	    	}
	    	
	    	$data['ancillaryID']  	= $ancillaryID;
	    	$data['catID'] 			= $catID;
			
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
	
	public function save() {
		//load submenu
		$this->submenu();
		$data = $this->data;
		$table_fields = array ('ancillaryID','remarks','performedBy');
		
		$ancillaryID  	= $this->input->post('ancillaryID');
		$catID 			= $this->input->post('catID');
		
		// check role
		if ($this->roles['create']) {
			$this->records->table = $this->table;
			$this->records->fields = array ();
			
			foreach ($table_fields as $fld) {
				$this->records->fields[$fld] = trim($this->input->post($fld));
			}
			$this->records->fields['pcDate'] 		= date('Y-m-d',strtotime($this->input->post('pcDate')));
			$this->records->fields['createdBy'] 	= $this->session->userdata('current_user')->userID;
			$this->records->fields['dateInserted'] 	= date('Y-m-d H:i:s');
			$this->records->fields['dateLastEdit'] 	= date('Y-m-d H:i:s');
			$this->records->fields['pcNo'] 			= $genID = $this->_generateID();
			
			if ($this->records->save()) {
				$this->records->fields = array();
				$id = $this->records->where['pcID'] = $this->db->insert_id();
				$this->records->retrieve();
				
				$update_items = $this->input->post('update_items'); 	//xstockcard items
				$update_xitems = $this->input->post('update_xitems'); 	//stockcard items
				
				// insert physical-count details - xstockcard items
				$this->db->select('xstockcards.*');
	    		$this->db->from('xstockcards');
				$this->db->where_in('xstockcards.xstockcardID', $update_items);
				$this->db->where('xstockcards.endBal >',0);
				$items = $this->db->get()->result();
				foreach ($items as $row) {
					$this->db->set('pcID',$id);
					$this->db->set('xstockcardID',$row->xstockcardID);
					$this->db->set('itemID',$row->itemID);
					$this->db->set('systemQty',$this->input->post('onHand_'.$row->xstockcardID));
					$this->db->set('actualQty',$this->input->post('onCount_'.$row->xstockcardID));
					$this->db->set('variance',$this->input->post('variance_'.$row->xstockcardID));
					$this->db->set('dateInserted',$row->dateInserted);
					$this->db->insert('pcdetails');
				}
				
				// insert physical-count details - stockcard items
				$this->db->select('stockcards.*');
	    		$this->db->from('stockcards');
				$this->db->where_in('stockcards.id', $update_xitems);
				$this->db->where('stockcards.endBal >',0);
				$items = $this->db->get()->result();
				foreach ($items as $row) {
					$this->db->set('pcID',$id);
					$this->db->set('xstockcardID',0);
					$this->db->set('itemID',$row->itemID);
					$this->db->set('systemQty',$this->input->post('onHand_'.$row->id.'_main'));
					$this->db->set('actualQty',$this->input->post('onCount_'.$row->id.'_main'));
					$this->db->set('variance',$this->input->post('variance_'.$row->id.'_main'));
					$this->db->set('dateInserted',$row->dateInserted);
					$this->db->insert('pcdetails');
				}
				
				//update config value - physical count series number
				$this->db->where('config.name',$this->config_model->getConfig('Physical Count Series Number'));
				$configval = $this->db->get('config')->row();
				$val = $configval->value+1;
				
				$this->db->set('value',$val);
				$this->db->where('name',$configval->name);
				$this->db->update('config');
				
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
	
	public function edit($id) {
		$this->submenu();
		$data = $this->data;
		$id = $this->encrypter->decode($id);
		
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
			$data['rec'] = $this->records->field;
			
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
		$table_fields = array ('catID', 'barcode', 'brandID','name', 'description', 'umsr','lastcost', 'lowestcost', 'highcost','avecost', 'markupType', 'markup','lowestprice', 'highprice', 'aveprice','reorderLvl', 'criticalLvl', 'leadtime','inventoryType','vatType','discountable','dangerousDrug','mdrPrice','status');
		
		// check roles
		if ($this->roles['edit']) {
			$this->records->table = $this->table;
			$this->records->fields = array();
			
			foreach ($table_fields as $fld) {
				$this->records->fields[$fld] = trim($this->input->post($fld));
			}
			
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
	
	public function view($id) {
		$id = $this->encrypter->decode($id);
		
		// load submenu
		$this->submenu ();
		$data = $this->data;
		// $this->roles['view'] = 1;
		if ($this->roles['view']) {
			// for retrieve with joining tables -------------------------------------------------
			$this->db->select($this->table.".*");
			$this->db->select('ancillaries.division' );
			//$this->db->select('category.category' );
			
			$this->db->from($this->table);
		
			$this->db->join('ancillaries', $this->table.'.ancillaryID=ancillaries.ancillaryID', 'left');
			//$this->db->join('category', $this->table.'.catID=category.catID', 'left');
			
			$this->db->where($this->pfield, $id);
			// ----------------------------------------------------------------------------------
			$data['rec'] = $this->db->get()->row();
			//$data['in_used'] = $this->_in_used($id);
			
			// get details
			$this->db->select('pcdetails.*');
			$this->db->select('xstockcards.expiry');
			$this->db->select('items.catID');
    		$this->db->select('items.name');
    		$this->db->select('items.description');
    		$this->db->select('items.umsr');
    		$this->db->select('items.lastcost');
    		$this->db->select('category.category');
    		$this->db->select('category.category');
    		$this->db->from('pcdetails');
    		$this->db->join('xstockcards','pcdetails.xstockcardID=xstockcards.xstockcardID', 'left');
    		$this->db->join('items','pcdetails.itemID=items.itemID', 'left');
    		$this->db->join('category','items.catID=category.catID', 'left');
			$this->db->where('pcdetails.pcID', $id);
			$this->db->order_by('items.name','ASC');
			$data['pcdetails'] = $this->db->get()->result();
			
//			$items = $this->db->get('items')->result();
//			foreach ($items as $i) {
//				$ancillaries = $this->db->get('ancillaries')->result();
//				foreach ($ancillaries as $a) {
//					$this->db->set('ancillaryID',$a->ancillaryID);
//					$this->db->set('itemID',$i->itemID);
//					$this->db->insert('inventory');
//				}
//			}
			
			
			// view logs ------------------------------------------------------------------------
			$logname = $this->logfield;
			$logs = "Record - ".$data['rec']->$logname;
			$this->log_model->view_logs($data['current_module']['title'], $this->table, $this->pfield, $id, $logs);
		    // ----------------------------------------------------------------------------------
			
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
		
		$condition_fields = array(
			array('variable'=>'pcNo', 'field'=>$this->table.'.pcNo', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'ancillaryID', 'field'=>$this->table.'.ancillaryID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
		);
		
		// sorting fields
		$sorting_fields = array('pcNo'=>'asc');
		
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
		
		switch($filter_source) 
	    {
	    	case 1:
	    		foreach($condition_fields as $key) {
	    			$$key['variable'] = trim($this->input->post($key['variable']));
	    		}
	    		
	    		// dates
	    		$startDate  = trim($this->input->post('startDate'));
	    		$endDate	= trim($this->input->post('endDate'));

	    		$sortby 	= trim($this->input->post('sortby'));
		    	$sortorder 	= trim($this->input->post('sortorder'));
		    	
	    		break;
	    	case 2:
	    		foreach($condition_fields as $key) {
	    			$$key['variable'] = $this->session->userdata($controller.'_'.$key['variable']);
	    		}
	    		
	    		// dates
	    		$startDate  = $this->session->userdata($controller.'_startDate');
	    		$endDate	= $this->session->userdata($controller.'_endDate');
		    	
		    	$sortby 	= $this->session->userdata($controller.'_sortby');
		    	$sortorder 	= $this->session->userdata($controller.'_sortorder');
	    		break;
	    	default:
	    		foreach($condition_fields as $key) {
	    			$$key['variable'] = $key['default_value'];
	    		}
	    		
	    		// dates
	    		$startDate  = "";
	    		$endDate	= "";
	    		
	    		$sortby 	= "";
		    	$sortorder 	= "";
	    }
		
		if (!$startDate) {
	    	$startDate = date('M d, Y');
	    	$endDate   = date('M d, Y');
	    } else if (strtotime($startDate) > strtotime($endDate)) {
	    	$endDate   = $startDate;
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
		
		$this->session->set_userdata($controller.'_startDate', $startDate);
    	$this->session->set_userdata($controller.'_endDate', $endDate);
		
		$this->session->set_userdata ( $controller . '_sortby', $sortby );
		$this->session->set_userdata ( $controller . '_sortorder', $sortorder );
		$this->session->set_userdata ( $controller . '_limit', $limit );
		
		// assign data variables for views
		foreach ( $condition_fields as $key ) {
			$data [$key ['variable']] = $$key ['variable'];
		}
		
		// select
    	$this->db->select($this->table.'.*');
    	//join db
    	$this->db->select('ancillaries.division');
    	//$this->db->select('users.lastName');
    	//$this->db->select('users.firstName');

    	// from
    	$this->db->from($this->table);
    	
    	// join
		$this->db->join('ancillaries',$this->table.'.ancillaryID=ancillaries.ancillaryID','left');
		//$this->db->join('users',$this->table.'.encodedBy=users.userID','left');
		
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
		
		// date range
    	$startDate 	= date('Y-m-d', strtotime($startDate));
    	$endDate 	= date('Y-m-d', strtotime($endDate));
		if($startDate != '' && $startDate != '1970-01-01'){
    		$this->db->where("(pcDate >= '$startDate' and pcDate <= '$endDate')");
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
    	$this->db->select($this->table.'.*');
    	//join db
    	$this->db->select('ancillaries.division');
    	//$this->db->select('users.lastName');
    	//$this->db->select('users.firstName');

    	// from
    	$this->db->from($this->table);
    	
    	// join
		$this->db->join('ancillaries',$this->table.'.ancillaryID=ancillaries.ancillaryID','left');
		//$this->db->join('users',$this->table.'.encodedBy=users.userID','left');
		
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
		
		// date range
    	$startDate 	= date('Y-m-d', strtotime($startDate));
    	$endDate 	= date('Y-m-d', strtotime($endDate));
    	if($startDate != '' && $startDate != '1970-01-01'){
    		$this->db->where("(pcDate >= '$startDate' and pcDate <= '$endDate')");
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
		
		// get
		$data ['records'] = $this->db->get ()->result ();
		
		$data['startDate'] = $startDate;
		$data['endDate'] = $endDate;
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
		$condition_fields = array(
			array('variable'=>'pcNo', 'field'=>$this->table.'.pcNo', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'ancillaryID', 'field'=>$this->table.'.ancillaryID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
		);
		
		// sorting fields
		$sorting_fields = array('pcNo'=>'asc');
		
		$controller = $this->uri->segment ( 1 );
		
		foreach ( $condition_fields as $key ) {
			$$key ['variable'] = $this->session->userdata ( $controller . '_' . $key ['variable'] );
		}
		
		$limit = $this->session->userdata ( $controller . '_limit' );
		$offset = $this->session->userdata ( $controller . '_offset' );
		$sortby = $this->session->userdata ( $controller . '_sortby' );
		$sortorder = $this->session->userdata ( $controller . '_sortorder' );
		
		// select
    	$this->db->select($this->table.'.*');
    	//join db
    	$this->db->select('ancillaries.division');
    	//$this->db->select('users.lastName');
    	//$this->db->select('users.firstName');

    	// from
    	$this->db->from($this->table);
    	
    	// join
		$this->db->join('ancillaries',$this->table.'.ancillaryID=ancillaries.ancillaryID','left');
		//$this->db->join('users',$this->table.'.encodedBy=users.userID','left');
		
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
		
		// get
		$data ['records'] = $this->db->get ()->result ();
		
		$data ['title'] = "Physical Count List";
		
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
		

		// variable:field:default_value:operator
		// note: dont include the special query field filter
		$condition_fields = array(
			array('variable'=>'pcNo', 'field'=>$this->table.'.pcNo', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'ancillaryID', 'field'=>$this->table.'.ancillaryID', 'default_value'=>'', 'operator'=>'where'),
			array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
		);
		
		// sorting fields
		$sorting_fields = array('pcNo'=>'asc');
		
		$controller = $this->uri->segment ( 1 );
		
		foreach ( $condition_fields as $key ) {
			$$key ['variable'] = $this->session->userdata ( $controller . '_' . $key ['variable'] );
		}
		
		$limit = $this->session->userdata ( $controller . '_limit' );
		$offset = $this->session->userdata ( $controller . '_offset' );
		$sortby = $this->session->userdata ( $controller . '_sortby' );
		$sortorder = $this->session->userdata ( $controller . '_sortorder' );
		
		// select
    	$this->db->select($this->table.'.*');
    	//join db
    	$this->db->select('ancillaries.division');
    	//$this->db->select('users.lastName');
    	//$this->db->select('users.firstName');

    	// from
    	$this->db->from($this->table);
    	
    	// join
		$this->db->join('ancillaries',$this->table.'.ancillaryID=ancillaries.ancillaryID','left');
		//$this->db->join('users',$this->table.'.encodedBy=users.userID','left');
		
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
		
		// get
		$records = $this->db->get ()->result ();
		
		$title = "Physical Count List";
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
    		<Column ss:Index='4' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
    		<Column ss:Index='5' ss:AutoFitWidth=\"1\" ss:Width='150.00'/>
    		<Column ss:Index='6' ss:AutoFitWidth=\"1\" ss:Width='150.00'/>
    		    ";
		
		// header
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'></Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s20'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'>".$companyName."</Data></Cell>";
		$data .= "</Row>";
		$data .= "<Row ss:StyleID='s24A'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'>".$address."</Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'></Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'>".strtoupper($title)."</Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'></Data></Cell>";
		$data .= "</Row>";
		
		$fields[] = "  ";
		$fields[] = "DATE";
		$fields[] = "PC NO";
		$fields[] = "SECTION";
		$fields[] = "PERFORMED BY";
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
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".date('M d Y',strtotime($row->pcDate))."</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$row->pcNo."</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$row->division."</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>".$row->performedBy."</Data></Cell>";
				if ($row->status == 1) {
					$data .= "<Cell ss:StyleID='s24B'><Data ss:Type='String'>Pending</Data></Cell>";
				} else if($row->status == 2) {
					$data .= "<Cell ss:StyleID='s24B'><Data ss:Type='String'>Confirmed</Data></Cell>";
				} else {
					$data .= "<Cell ss:StyleID='s24B'><Data ss:Type='String'>Cancelled</Data></Cell>";
				}
				$data .= "</Row>";
				
				$ctr ++;
			}
		}
		$data .= "</Table></Worksheet>";
		$data .= "</Workbook>";
		
		//Final XML Blurb
		$filename = "Physical_Count_List";
		
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=$filename.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		echo $data;
	
	}
	
	public function print_record($id=0)
    {
    	$id = $this->encrypter->decode($id);
    	
        // load submenu
        $this->submenu();
        $data = $this->data;
        $data['title'] = "Physical Count";
        
        // for retrieve with joining tables -------------------------------------------------
			$this->db->select($this->table.".*");
			$this->db->select('ancillaries.division' );
			//$this->db->select('category.category' );
			
			$this->db->from($this->table);
		
			$this->db->join('ancillaries', $this->table.'.ancillaryID=ancillaries.ancillaryID', 'left');
			//$this->db->join('category', $this->table.'.catID=category.catID', 'left');
			
			$this->db->where($this->pfield, $id);
			// ----------------------------------------------------------------------------------
			$data['rec'] = $this->db->get()->row();
			//$data['in_used'] = $this->_in_used($id);
			
			// get details
			$this->db->select('pcdetails.*');
			$this->db->select('xstockcards.expiry');
			$this->db->select('items.catID');
    		$this->db->select('items.name');
    		$this->db->select('items.description');
    		$this->db->select('items.umsr');
    		$this->db->select('items.lastcost');
    		$this->db->select('category.category');
    		$this->db->from('pcdetails');
    		$this->db->join('xstockcards','pcdetails.xstockcardID=xstockcards.xstockcardID', 'left');
    		$this->db->join('items','pcdetails.itemID=items.itemID', 'left');
    		$this->db->join('category','items.catID=category.catID', 'left');
			$this->db->where('pcdetails.pcID', $id);
			$this->db->order_by('items.name','ASC');
			$data['pcdetails'] = $this->db->get()->result();
		
        // check roles
        if ($this->roles['view']) {
    
            $data['pdf_paging'] = TRUE;
            $data['title']      = "Physical Count";
            $data['modulename'] = "Physical Count";            
            
            //-------------------------------
            // load pdf class
            $this->load->library('mpdf');
            // load pdf class
            $this->mpdf->mpdf('en-GB',array(215.9,330.2),10,'Gotham Narrow',10,10,30,10,10,10,'P');
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
            
            $this->mpdf->SetJS('this.print();');
    
            $html   = $this->load->view($this->module_path.'/print_record', $data, TRUE);
            $this->mpdf->WriteHTML($html);
    		
            //$this->pdf->AutoPrint(false);
            $this->mpdf->Output("PHYSICAL_COUNT.pdf","I");
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
    
	public function void($id=0) 
	{
		// load submenu
		$this->submenu ();
		$data = $this->data;
		
		$id = $this->encrypter->decode($id);
		
		// check roles
		if ($this->roles['edit']) {
			$this->records->table = $this->table;
			$this->records->fields = array();
			
			$this->records->fields['status'] = 0;
			$this->records->fields['cancelledBy'] = $this->session->userdata('current_user')->userID;
			$this->records->fields['dateCancelled'] = date('Y-m-d H:i:s');
			
			$this->records->pfield = $this->pfield;
			$this->records->pk = $id;
			
			// field logs here
			$wasChange = $this->log_model->field_logs($data['current_module']['module_label'], $this->table, $this->pfield, $id, 'Cancel', $this->records->fields);
			$this->db->where('status !=',-100);
			if ($this->records->update ()) {
				
				//update pc details table - set status to cancelled/void
				$this->db->set('status',0);
				$this->db->where('pcID',$id);
				$this->db->update('pcdetails');
				
				// successful
				$data["class"] = "success";
				$data["msg"] = $this->data['current_module']['module_label']." successfully updated.";
				$data["urlredirect"] = $this->controller_page . "/view/".$this->encrypter->encode($id);
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
	
	public function confirm($id=0) 
	{
		// load submenu
		$this->submenu ();
		$data = $this->data;
		
		$id = $this->encrypter->decode($id);
		
		// check roles
		if ($this->roles['edit']) {
			$this->records->table = $this->table;
			$this->records->fields = array();
			
			$this->records->fields['status'] = 2;
			$this->records->fields['confirmedBy'] = $this->session->userdata('current_user')->userID;
			$this->records->fields['dateConfirmed'] = date('Y-m-d H:i:s');
			
			$this->records->pfield = $this->pfield;
			$this->records->pk = $id;
			
			// field logs here
			$wasChange = $this->log_model->field_logs($data['current_module']['module_label'], $this->table, $this->pfield, $id, 'Confirm', $this->records->fields);
			$this->db->where('status !=',-100);
			if ($this->records->update ()) {
				
				$this->db->where('pcID',$id);
				$pcheader = $this->db->get('pcheaders')->row();
				
				
				//for inventory update record -----------------
				$this->db->select('pcdetails.*');
				$this->db->select_sum('actualQty');
				$this->db->from('pcdetails');
				$this->db->where('pcID',$id);
				$this->db->group_by('itemID');
				$results = $this->db->get()->result(); 
				foreach ($results as $result) {
					//update inventory
					$this->db->set('inventory.qty',$result->actualQty);
					$this->db->where('inventory.itemID',$result->itemID);
					$this->db->where('inventory.ancillaryID',$pcheader->ancillaryID);
					$this->db->update('inventory');
				}
				//---------------------------------------------
				
				$this->db->where('pcID',$id);
				$this->db->where('status >',0);
				$pcdetails = $this->db->get('pcdetails')->result();
				
				foreach ($pcdetails as $pcdetail) {
					
					//-----------------------------------------stock cards insert here--------------------------------------------
					//xstockcard items here
					if ($pcdetail->xstockcardID != 0) {
						//insert xstockcards
						$totalAmount=0;
						$this->db->select('xstockcards.*');
			    		$this->db->from('xstockcards');
						$this->db->where('xstockcards.xstockcardID', $pcdetail->xstockcardID);
						$this->db->where('xstockcards.endBal >',0);
						$items = $this->db->get()->result();
						foreach ($items as $row) {
							$this->db->set('ancillaryID',$row->ancillaryID);
							$this->db->set('itemID',$row->itemID);
							$this->db->set('expiry',$row->expiry);
							$this->db->set('cost',$row->cost);
							$this->db->set('markupType',$row->markupType);
							$this->db->set('price',$row->price);
							$this->db->set('begBal',$row->begBal);
							
	    					if($pcdetail->variance > 0) {
	    						$this->db->set('increase',$pcdetail->variance);
	    						$this->db->set('decrease',0);
	    						$endBal = $row->endBal + $pcdetail->variance;
	    					} else if($pcdetail->variance == 0) {
	    						$this->db->set('increase',0);
	    						$this->db->set('decrease',0);
	    						$endBal = $row->endBal;
	    					} else {
	    						$this->db->set('increase',0);
	    						$this->db->set('decrease',$pcdetail->variance);
	    						$endBal = $row->endBal - $pcdetail->variance;
	    					}
	    					$this->db->set('endBal',$endBal);
	    					$this->db->set('refNo',$pcheader->pcNo);
	    					$this->db->set('dateInserted',date('Y-m-d H:i:s'));
	    					$this->db->set('dateLastEdit',date('Y-m-d H:i:s'));
	    					$this->db->insert('xstockcards');
	    					
	    					//insert stockcards - based on the xstockcard items -----------------------------
	    					$this->db->select('stockcards.*');
				    		$this->db->from('stockcards');
							$this->db->where('stockcards.ancillaryID', $row->ancillaryID);
							$this->db->where('stockcards.itemID', $row->itemID);
							$this->db->where('stockcards.endBal >',0);
							$this->db->order_by('stockcards.dateInserted','desc');
							$this->db->limit(1);
							$stockcards = $this->db->get()->row();
							
							$this->db->set('ancillaryID',$stockcards->ancillaryID);
							$this->db->set('itemID',$stockcards->itemID);
							$this->db->set('begBal',$stockcards->endBal);
							if($pcdetail->variance > 0) {
								$this->db->set('increase',$pcdetail->variance);
								$this->db->set('decrease',0);
	    						$endBal = $stockcards->endBal + $pcdetail->variance;
	    					} else if($pcdetail->variance == 0) {
	    						$this->db->set('increase',0);
								$this->db->set('decrease',0);
	    						$endBal = $stockcards->endBal;
	    					} else {
	    						$this->db->set('increase',0);
								$this->db->set('decrease',$pcdetail->variance);
								
	    						$endBal = $stockcards->endBal + $pcdetail->variance;
	    					}
							$this->db->set('endBal',$endBal);
							$this->db->set('refNo',$pcheader->pcNo);
							$this->db->set('dateInserted',date('Y-m-d H:i:s'));
							$this->db->set('status',1);
							$this->db->set('withxstockcard','Yes');
							$this->db->insert('stockcards');
							//--------------------------------------------------------------------------------
						}
					} else {
						//stockcard items here
						//insert stockcards items
						$totalAmount=0;
						$this->db->select('stockcards.*');
			    		$this->db->from('stockcards');
						$this->db->where('stockcards.ancillaryID', $pcheader->ancillaryID);
						$this->db->where('stockcards.itemID', $pcdetail->itemID);
						$this->db->where('stockcards.endBal >',0);
						$this->db->order_by('stockcards.dateInserted','desc');
						$this->db->limit(1);
						$items = $this->db->get()->result();
						foreach ($items as $row) {
							
							$this->db->set('ancillaryID',$row->ancillaryID);
							$this->db->set('itemID',$row->itemID);
							$this->db->set('begBal',$row->endBal);
							if($pcdetail->variance > 0) {
								$this->db->set('increase',$pcdetail->variance);
								$this->db->set('decrease',0);
	    						$endBal = $row->endBal + $pcdetail->variance;
	    					} else if($pcdetail->variance == 0) {
	    						$this->db->set('increase',0);
								$this->db->set('decrease',0);
	    						$endBal = $row->endBal;
	    					} else {
	    						$this->db->set('increase',0);
								$this->db->set('decrease',$pcdetail->variance);
								
	    						$endBal = $row->endBal + $pcdetail->variance;
	    					}
							$this->db->set('endBal',$endBal);
							$this->db->set('refNo',$pcheader->pcNo);
							$this->db->set('dateInserted',date('Y-m-d H:i:s'));
							$this->db->set('status',1);
							$this->db->set('withxstockcard','No');
							$this->db->insert('stockcards');
							
						}
					}
					
					//-----------------------------------------end stock cards insert here--------------------------------------------
				}
				
				
				//update pcdetails status to confirm
				$this->db->set('status',2);
				$this->db->where('pcID',$id);
				$this->db->where('status >',0);
				$this->db->update('pcdetails');
				
				
				// successful
				$data["class"] = "success";
				$data["msg"] = $this->data['current_module']['module_label']." successfully updated.";
				$data["urlredirect"] = $this->controller_page . "/view/".$this->encrypter->encode($id);
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
	
	//Conditions and fields changes
	public function check_duplicate() {
		$this->db->where('name', trim($this->input->post('name')));
		
		if ($this->db->count_all_results($this->table))
			echo "1"; // duplicate
		else
			echo "0";
	}
	

}
