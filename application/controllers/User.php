<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class User extends CI_Controller {
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
		
		$this->load->model ( 'generic_model', 'user' );
		$this->module = 'Administrator';
		$this->data ['controller_page'] = $this->controller_page = site_url ( 'user' ); // defines contoller link
		$this->table = 'users'; // defines the default table
		$this->pfield = 'userID'; // defines primary key
		$this->logfield = 'userName'; // defines field for record log
		$this->module_path = 'modules/' . strtolower ( str_replace ( " ", "_", $this->module ) ) . '/user'; // defines module path
		

		// check for maintenance period
		if ($this->config_model->getConfig ( 'Maintenance Mode' ) == '1') {
			header ( 'location: ' . site_url ( 'maintenance_mode' ) );
		}
		
		// check user session
		if (! $this->session->userdata ( 'current_user' )->sessionID) {
			header ( 'location: ' . site_url ( 'login' ) );
		}
	}
	
	private function submenu() {
		require_once ('modules.php');
		
		foreach ( $modules as $mod ) {
			//modules/<module>/
			// - <menu>
			// - <metadata>
			$this->load->view ( 'modules/' . str_replace ( " ", "_", strtolower ( $mod ) ) . '/metadata' );
		}
		
		$this->data ['modules'] = $this->modules;
		$this->data ['current_main_module'] = $this->modules [$this->module] ['main']; // defines the current main module
		$this->data ['current_module'] = $this->modules [$this->module] ['sub'] ['User']; // defines the current sub module
		// check roles
		$this->check_roles ();
		$this->data ['roles'] = $this->roles;
	}
	
	private function check_roles() {
		$this->roles ['create'] = $this->session->userdata ( 'current_user' )->isAdmin;
		$this->roles ['view'] = $this->session->userdata ( 'current_user' )->isAdmin;
		$this->roles ['edit'] = $this->session->userdata ( 'current_user' )->isAdmin;
		$this->roles ['delete'] = $this->session->userdata ( 'current_user' )->isAdmin;
	}
	
	public function index() {
		$this->show ();
	}
	
	public function create() {
		$this->submenu ();
		$data = $this->data;
		// check roles
		if ($this->roles ['create']) {
			// load views
			$this->load->view ( 'header', $data );
			$this->load->view ( $this->module_path . '/create' );
			$this->load->view ( 'footer' );
		} else {
			// no access this page
			$data ['class'] = "danger";
			$data ['msg'] = "Sorry, you don't have access to this page!";
			$data ['urlredirect'] = "";
			$this->load->view ( 'header', $data );
			$this->load->view ( 'message' );
			$this->load->view ( 'footer' );
		}
	}
	
	public function save() {
		//load submenu
		$this->submenu ();
		$data = $this->data;
		$table_fields = array ('empNo', 'lastName', 'firstName', 'middleName', 'userName', 'companyID', 'branchID', 'deptID', 'groupID', 'accessTimeStart', 'accessTimeEnd' );
		
		// check role
		if ($this->roles ['create']) {
			$this->user->table = $this->table;
			$this->user->fields = array ();
			
			foreach ( $table_fields as $fld ) {
				$this->user->fields [$fld] = trim ( $this->input->post ( $fld ) );
			}
			
			$this->user->fields ['userID'] = md5 ( trim ( $this->input->post ( 'userName' ) ) );
			$this->user->fields ['userPswd'] = md5 ( trim ( $this->input->post ( 'userPswd' ) ) );
			
			if ($this->input->post ( 'isAdmin' ) != "" && $this->input->post ( 'isAdmin' ) == "1")
				$this->user->fields ['isAdmin'] = 1;
			else
				$this->user->fields ['isAdmin'] = 0;
			
			$this->user->fields ['preferences'] = "";
			if (count ( $this->input->post ( 'modules' ) )) {
				$ctr = 0;
				foreach ( $this->input->post ( 'modules' ) as $tab ) {
					if ($ctr)
						$this->user->fields ['preferences'] .= "," . $tab;
					else
						$this->user->fields ['preferences'] .= $tab;
					
					$ctr ++;
				}
			}
			
			$this->user->fields ['dateEntered'] = date ( 'Y-m-d m:i:s' );
			
			if ($this->user->save ()) {
				$this->user->fields = array ();
				$this->user->where ['userName'] = trim ( $this->input->post ( 'userName' ) );
				
				$this->user->retrieve ();
				$this->user->field->userID;
				
				// inherit roles from the user groups
				$this->db->where ( 'groupID', $this->user->field->groupID );
				$grouproles = $this->db->get ( 'usergrouproles' );
				
				if ($grouproles->num_rows ()) {
					foreach ( $grouproles->result () as $role ) {
						// insert role to the user roles
						$d = array ();
						$d ['userID'] = $this->user->field->userID;
						$d ['roleName'] = $role->roleName;
						$this->db->insert ( 'userroles', $d );
					}
				}
				
				// record logs
				$logs = "Record - " . trim ( $this->input->post ( 'lastName' ) ) . " , " . trim ( $this->input->post ( 'firstName' ) );
				$this->log_model->table_logs ( $this->module, $this->table, 'userID', $this->user->field->userID, 'Insert', $logs );
				
				// success msg
				$data ['class'] = "success";
				$data ['msg'] = $data ['current_module'] ['module_label'] . " successfully saved.";
				$data ['urlredirect'] = $this->controller_page . '/view/' . $this->user->field->userID;
				$this->load->view ( 'header', $data );
				$this->load->view ( 'message' );
				$this->load->view ( 'footer' );
			} else {
				// Unable to save
				$data ['class'] = "danger";
				$data ['msg'] = "Error in saving the " . strtolower ( $this->module ) . "!";
				$data ['urlredirect'] = "";
				$this->load->view ( 'header', $data );
				$this->load->view ( 'message' );
				$this->load->view ( 'footer' );
			}
		
		} else {
			// no access this page
			$data ['class'] = "danger";
			$data ['msg'] = "Sorry, you don't have access to this page!";
			$data ['urlredirect'] = "";
			$this->load->view ( 'header', $data );
			$this->load->view ( 'message' );
			$this->load->view ( 'footer' );
		}
	}
	
	public function edit($id) {
		$this->submenu ();
		$data = $this->data;
		//         $id = $this->encrypter->decode($id);
		

		if ($this->roles ['edit']) {
			// for retrieve with joining tables -------------------------------------------------
			// set db
			$this->user->cdb = $data ['current_db'];
			// set table
			$this->user->table = $this->table;
			
			// set fields for the current table
			$this->user->setFields ();
			
			// extend fields - join tables
			$this->user->fields [] = 'usergroups.groupName';
			$this->user->fields [] = 'branches.branchName';
			
			// set joins
			$this->user->joins [] = array ('usergroups', 'users.groupID=usergroups.groupID', 'left' );
			$this->user->joins [] = array ('branches', 'users.branchID=branches.branchID', 'left' );
			
			// set where
			$this->user->where ['userID'] = $id;
			
			// execute retrieve
			$this->user->retrieve ();
			// ----------------------------------------------------------------------------------
			$data ['rec'] = $this->user->field;
			
			// user groups
			$this->db->order_by ( 'groupName', 'asc' );
			$data ['groups'] = $this->db->get ( 'usergroups' );
			
			// load views
			$this->load->view ( 'header', $data );
			$this->load->view ( $this->module_path . '/edit' );
			$this->load->view ( 'footer' );
		} else {
			// no access this page
			$data ['class'] = "danger";
			$data ['msg'] = "Sorry, you don't have access to this page!";
			$data ['urlredirect'] = "";
			$this->load->view ( 'header', $data );
			$this->load->view ( 'message' );
			$this->load->view ( 'footer' );
		}
	}
	
	public function update() {
		
		// // load submenu
		$this->submenu ();
		$data = $this->data;
		$table_fields = array ('empNo', 'lastName', 'firstName', 'middleName', 'companyID', 'branchID', 'deptID', 'groupID','status');
		
		if ($this->roles ['edit']) {
			$this->user->table = $this->table;
			$this->user->fields = array ();
			
			foreach ( $table_fields as $fld ) {
				$this->user->fields [$fld] = trim ( $this->input->post ( $fld ) );
			}
			
			if ($this->input->post ( 'isAdmin' ) != "" && $this->input->post ( 'isAdmin' ) == "1")
				$this->user->fields ['isAdmin'] = 1;
			else
				$this->user->fields ['isAdmin'] = 0;
			
			$this->user->fields ['preferences'] = "";
			if (count ( $this->input->post ( 'modules' ) )) {
				$this->user->fields ['preferences'] = implode ( ",", $this->input->post ( 'modules[]' ) );
			}
			
			$this->user->pfield = 'userID';
			$this->user->pk = trim ( $this->input->post ( 'userID' ) );
			
			// field logs here
			$wasChange = $this->log_model->field_logs ( $this->module, $this->table, 'userID', trim ( $this->input->post ( 'userID' ) ), 'Update', $this->user->fields );
			
			if ($this->user->update ()) {
				// record logs
				if ($wasChange) {
					$logs = "Record - " . trim ( $this->input->post ( 'lastName' ) ) . " , " . trim ( $this->input->post ( 'firstName' ) );
					$this->log_model->table_logs ( $this->module, $this->table, 'userID', $this->user->pk, 'Update', $logs );
				}
				
				// Successfully updated
				$data ['class'] = "success";
				$data ['msg'] = $this->module . " successfully updated.";
				$data ['urlredirect'] = $this->controller_page . "/view/" . $this->input->post ( 'userID' );
				$this->load->view ( 'header', $data );
				$this->load->view ( 'message' );
				$this->load->view ( 'footer' );
			} else {
				// Error updating
				$data ['class'] = "danger";
				$data ['msg'] = "Error in updating the " . strtolower ( $this->module ) . "!";
				$data ['urlredirect'] = $this->controller_page . "/view/" . $this->user->field->userID;
				$this->load->view ( 'header', $data );
				$this->load->view ( 'message' );
				$this->load->view ( 'footer' );
			}
		} else {
			// no access this page
			$data ['class'] = "danger";
			$data ['msg'] = "Sorry, you don't have access to this page!";
			$data ['urlredirect'] = "";
			$this->load->view ( 'header', $data );
			$this->load->view ( 'message' );
			$this->load->view ( 'footer' );
		}
	}
	
	public function delete($id = 0) {
		// load submenu
		$this->submenu ();
		$data = $this->data;
		$id = $this->encrypter->decode ( $id );
		
		if ($this->roles ['delete']) {
			// set fields
			$this->user->fields = array ();
			// set db
			$this->user->cdb = $data ['current_db'];
			// set table
			$this->user->table = $this->table;
			// set where
			$this->user->where ['userID'] = $id;
			// execute retrieve
			$this->user->retrieve ();
			if (! empty ( $this->user->field )) {
				$this->user->pfield = 'userID';
				$this->user->pk = $id;
				
				if ($this->user->delete ()) {
					
					//Success msg
					$data ['class'] = "success";
					$data ['msg'] = $this->module . " successfully deleted.";
					$data ['urlredirect'] = $this->controller_page . "/show";
					$this->load->view ( 'header', $data );
					$this->load->view ( 'message' );
					$this->load->view ( 'footer' );
				} else {
					//Error Deleting
					$data ['class'] = "danger";
					$data ['msg'] = "Error in deleting the " . strtolower ( $this->module ) . "!";
					$data ['urlredirect'] = "";
					$this->load->view ( 'header', $data );
					$this->load->view ( 'message' );
					$this->load->view ( 'footer' );
				}
			
			} else {
				//Record not found
				$data ['class'] = "danger";
				$data ['msg'] = $this->module . " record not found!";
				$data ['urlredirect'] = "";
				$this->load->view ( 'header', $data );
				$this->load->view ( 'message' );
				$this->load->view ( 'footer' );
			}
		} else {
			//No access this page
			$data ['class'] = "danger";
			$data ['msg'] = "Sorry, you don't have access to this page!";
			$data ['urlredirect'] = "";
			$this->load->view ( 'header', $data );
			$this->load->view ( 'message' );
			$this->load->view ( 'footer' );
		}
	}
	
	public function view($id) {
		// load submenu
		$this->submenu ();
		$data = $this->data;
		//$id = $this->encrypter->decode($id);
		

		if ($this->roles ['view']) {
			// for retrieve with joining tables -------------------------------------------------
			// set table
			$this->user->table = $this->table;
			
			// set fields for the current table
			$this->user->setFields ();
			
			// extend fields - join tables
			$this->user->fields [] = 'usergroups.groupName';
			$this->user->fields [] = 'companies.companyName';
			$this->user->fields [] = 'branches.branchName';
			$this->user->fields [] = 'departments.deptName';
			
			// set joins
			$this->user->joins [] = array ('usergroups', 'users.groupID=usergroups.groupID', 'left' );
			$this->user->joins [] = array ('companies', 'users.companyID=companies.companyID', 'left' );
			$this->user->joins [] = array ('branches', 'users.branchID=branches.branchID', 'left' );
			$this->user->joins [] = array ('departments', 'users.deptID=departments.deptID', 'left' );
			
			// set where
			$this->user->where ['userID'] = $id;
			
			// execute retrieve
			$this->user->retrieve ();
			// ----------------------------------------------------------------------------------
			$data ['rec'] = $this->user->field;
			
			// user roles
			$this->db->where ( 'userID', $id );
			$data ['userroles'] = $this->db->get ( 'userroles' );
			
			$taken_roles = array ();
			if ($data ['userroles']->num_rows ()) {
				$taken_roles = array ();
				foreach ( $data ['userroles']->result () as $role ) {
					$taken_roles [] = $role->roleID;
				}
			}
			
			// get all roles
			$this->load->model ( 'role_model' );
			if (! empty ( $taken_roles ))
				$this->db->where_not_in ( 'roleID', $taken_roles );
			
			$data ['roles'] = $this->db->get ( 'roles' );
			
			// get all terminals
			$this->db->where ( 'userID', $id );
			$data ['terminals'] = $this->db->get ( 'terminals' );
			
			//load views
			$this->load->view ( 'header', $data );
			$this->load->view ( $this->module_path . '/view' );
			$this->load->view ( 'footer' );
		} else {
			// no access this page
			$data ['class'] = "danger";
			$data ['msg'] = "Sorry, you don't have access to this page!";
			$data ['urlredirect'] = "";
			$this->load->view ( 'header', $data );
			$this->load->view ( 'message' );
			$this->load->view ( 'footer' );
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
		$condition_fields = array (array ('variable' => 'lastName', 'field' => $this->table . '.lastName', 'default_value' => '', 'operator' => 'like_both' ), array ('variable' => 'userName', 'field' => $this->table . '.userName', 'default_value' => '', 'operator' => 'like_both' ), array ('variable' => 'groupID', 'field' => $this->table . '.groupID', 'default_value' => '', 'operator' => 'where' ), array ('variable' => 'isAdmin', 'field' => $this->table . '.isAdmin', 'default_value' => '', 'operator' => 'where' ), array ('variable' => 'status', 'field' => $this->table . '.status', 'default_value' => '', 'operator' => 'where' ) );
		
		// sorting fields
		$sorting_fields = array ('lastName' => 'asc' );
		
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
		$this->db->select ( 'usergroups.groupName' );
		
		// from
		$this->db->from ( $this->table );
		
		// join
		$this->db->join ( 'usergroups', $this->table . '.groupID=usergroups.groupID', 'left' );
		
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
		$config ['base_url'] = $this->controller_page . '/show/';
		$config ['per_page'] = $limit;
		$this->pagination->initialize($config);
		
		// select
		$this->db->select ( $this->table . '.*' );
		$this->db->select ( 'usergroups.groupName' );
		
		// from
		$this->db->from ( $this->table );
		
		// join
		$this->db->join ( 'usergroups', $this->table . '.groupID=usergroups.groupID', 'left' );
		
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
		$condition_fields = array (array ('variable' => 'lastName', 'field' => $this->table . '.lastName', 'default_value' => '', 'operator' => 'like_both' ), array ('variable' => 'userName', 'field' => $this->table . '.userName', 'default_value' => '', 'operator' => 'like_both' ), array ('variable' => 'groupID', 'field' => $this->table . '.groupID', 'default_value' => '', 'operator' => 'where' ), array ('variable' => 'isAdmin', 'field' => $this->table . 'isAdmin', 'default_value' => '', 'operator' => 'where' ), array ('variable' => 'status', 'field' => $this->table . '.status', 'default_value' => '', 'operator' => 'where' ) );
		
		// sorting fields
		$sorting_fields = array ('lastName' => 'asc' );
		
		$controller = $this->uri->segment ( 1 );
		
		foreach ( $condition_fields as $key ) {
			$$key ['variable'] = $this->session->userdata ( $controller . '_' . $key ['variable'] );
		}
		
		$limit = $this->session->userdata ( $controller . '_limit' );
		$offset = $this->session->userdata ( $controller . '_offset' );
		$sortby = $this->session->userdata ( $controller . '_sortby' );
		$sortorder = $this->session->userdata ( $controller . '_sortorder' );
		
		$this->db->select ( 'usergroups.groupName' );
		
		// select
		$this->db->select ( $this->table . '.*' );
		
		// from
		$this->db->from ( $this->table );
		
		// // join
		$this->db->join ( 'usergroups', $this->table . '.groupID=usergroups.groupID', 'left' );
		
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
		
		$data ['title'] = "User List";
		
		//load views
		$this->load->view ( 'header_print', $data );
		$this->load->view ( $this->module_path . '/printlist' );
		$this->load->view ( 'footer_print' );
	}
	
	public function exportlist() {
		
		// load submenu
		$this->submenu ();
		$data = $this->data;
		
		$condition_fields = array (array ('variable' => 'lastName', 'field' => $this->table . '.lastName', 'default_value' => '', 'operator' => 'like_both' ), array ('variable' => 'userName', 'field' => $this->table . '.userName', 'default_value' => '', 'operator' => 'like_both' ), array ('variable' => 'groupID', 'field' => $this->table . '.groupID', 'default_value' => '', 'operator' => 'where' ), array ('variable' => 'isAdmin', 'field' => $this->table . 'isAdmin', 'default_value' => '', 'operator' => 'where' ), array ('variable' => 'status', 'field' => $this->table . '.status', 'default_value' => '', 'operator' => 'where' ) );
		
		// sorting fields
		$sorting_fields = array ('lastName' => 'asc' );
		
		$controller = $this->uri->segment ( 1 );
		
		foreach ( $condition_fields as $key ) {
			$$key ['variable'] = $this->session->userdata ( $controller . '_' . $key ['variable'] );
		}
		
		$limit = $this->session->userdata ( $controller . '_limit' );
		$offset = $this->session->userdata ( $controller . '_offset' );
		$sortby = $this->session->userdata ( $controller . '_sortby' );
		$sortorder = $this->session->userdata ( $controller . '_sortorder' );
		
		$this->db->select ( 'usergroups.groupName' );
		
		// select
		$this->db->select ( $this->table . '.*' );
		
		// from
		$this->db->from ( $this->table );
		
		// // join
		$this->db->join ( 'usergroups', $this->table . '.groupID=usergroups.groupID', 'left' );
		
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
		
		$title = "User List";
		$companyName = $this->config_model->getConfig ( 'Company' );
		$address = $this->config_model->getConfig ( 'Address' );
		
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
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'>" . $companyName . "</Data></Cell>";
		$data .= "</Row>";
		$data .= "<Row ss:StyleID='s24A'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'>" . $address . "</Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'></Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'>" . strtoupper ( $title ) . "</Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'></Data></Cell>";
		$data .= "</Row>";
		
		$fields [] = "  ";
		$fields [] = "NAME";
		$fields [] = "USERNAME";
		$fields [] = "GROUP";
		$fields [] = "ADMINISTRATOR";
		$fields [] = "STATUS";
		
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
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $ctr . ".</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->lastName . ", " . $firstName . "</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->userName . "</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->groupName . "</Data></Cell>";
				if ($row->isAdmin == 1) {
					$data .= "<Cell ss:StyleID='s24B'><Data ss:Type='String'>Yes</Data></Cell>";
				} else {
					$data .= "<Cell ss:StyleID='s24B'><Data ss:Type='String'>No</Data></Cell>";
				}
				
				if ($row->status == 1) {
					$data .= "<Cell ss:StyleID='s24B'><Data ss:Type='String'>Active</Data></Cell>";
				} else {
					$data .= "<Cell ss:StyleID='s24B'><Data ss:Type='String'>Inactive</Data></Cell>";
				}
				$data .= "</Row>";
				
				$ctr ++;
			}
		}
		$data .= "</Table></Worksheet>";
		$data .= "</Workbook>";
		
		//Final XML Blurb
		$filename = "user_list";
		
		header ( "Content-type: application/octet-stream" );
		header ( "Content-Disposition: attachment; filename=$filename.xls;" );
		header ( "Content-Type: application/ms-excel" );
		header ( "Pragma: no-cache" );
		header ( "Expires: 0" );
		
		echo $data;
	}
	
	//Conditions and fields changes
	public function check_duplicate() {
		$this->db->where ( 'userName', trim ( $this->input->post ( 'userName' ) ) );
		$this->db->where ( 'status >= 0' );
		
		if ($this->db->count_all_results ( $this->table ))
			echo "1"; // duplicate
		else
			echo "0";
	}
	
	//More pages
	public function profile() {
		// load submenu
		// current module
		$this->submenu ();
		$data = $this->data;
		
		$userID = $this->session->userdata ( 'current_user' )->userID;
		
		$this->db->select ( 'users.*' );
		$this->db->select ( 'usergroups.groupName' );
		$this->db->select ( 'companies.companyName' );
		$this->db->select ( 'branches.branchName' );
		$this->db->select ( 'departments.deptName' );
		$this->db->from ( 'users' );
		$this->db->join ( 'usergroups', 'users.groupID=usergroups.groupID', 'left' );
		$this->db->join ( 'companies', 'users.companyID=companies.companyID', 'left' );
		$this->db->join ( 'branches', 'users.branchID=branches.branchID', 'left' );
		$this->db->join ( 'departments', 'users.deptID=departments.deptID', 'left' );
		$this->db->where ( 'userID', $this->session->userdata ( 'current_user' )->userID );
		
		$data ['rec'] = $this->db->get ()->row ();
		;
		
		// load views
		$this->load->view ( 'header', $data );
		$this->load->view ( $this->module_path . '/profile' );
		$this->load->view ( 'footer' );
	}
	
	public function edit_profile() {
		// load submenu
		$this->submenu ();
		$data = $this->data;
		
		$data ['title'] = "My Profile";
		
		//get all user groups
		$data ['groups'] = $this->db->get ( 'usergroups' );
		
		$this->user_model->userID = $this->session->userdata ( 'current_user' )->userID;
		$this->user_model->getRecord();
		$data ['rec'] = $this->user_model;
		
		// load views
		$this->load->view ( 'header', $data );
		$this->load->view ( $this->module_path . '/edit_profile' );
		$this->load->view ( 'footer' );
	}
	
	public function update_profile() {
		// load submenu
		$this->submenu ();
		$data = $this->data;
		
		$this->user_model->userID = $this->session->userdata ( 'current_user' )->userID;
		$this->user_model->lastName = trim ( $this->input->post ( 'lastName' ) );
		$this->user_model->firstName = trim ( $this->input->post ( 'firstName' ) );
		$this->user_model->middleName = trim ( $this->input->post ( 'middleName' ) );
		
		if ($this->user_model->updateRecordProfile ()) {
			// successful
			$data ["class"] = "success";
			$data ["msg"] = "User successfully updated.";
			$data ["urlredirect"] = site_url ( "user/profile" );
			$this->load->view ( "header", $data );
			$this->load->view ( "message" );
			$this->load->view ( "footer" );
		} else {
			// error
			$data ["class"] = "danger";
			$data ["msg"] = "Error in updating the user!";
			$data ["urlredirect"] = site_url ( "user/profile" );
			$this->load->view ( "header", $data );
			$this->load->view ( "message" );
			$this->load->view ( "footer" );
		}
	}
	
	public function edit_password() {
		$this->check_roles ();
		
		$this->user_model->userID = $userID;
		$this->user_model->getRecord ();
		$data ['rec'] = $this->user_model;
		
		// load views
		$this->load->view ( 'header_popup', $data );
		$this->load->view ( $this->module_path . '/edit_password' );
		$this->load->view ( 'footer_popup' );
	}
	
	public function save_password() {
		// load submenu
		$this->submenu ();
		$data = $this->data;
		
		if ($this->input->post ( 'userID' )) {
			$this->user_model->userID = trim ( $this->input->post ( 'userID' ) );
			$this->user_model->userPswd = md5 ( trim ( $this->input->post ( 'userPswd' ) ) );
			
			if ($this->user_model->update_password ()) {
				// $this->user_model->getRecord();
				

				// record logs
				// $logs = "Record - ".$this->user_model->userName;
				// $this->log_model->table_logs($this->module, $this->table, 'userID', $this->user_model->userID, 'Change Password', $logs);
				// // successful
				if ($this->input->post ( 'pageName' ) == 'userView') {
					$data ["display"] = "block";
					$data ["class"] = "success";
					$data ["msg"] = "Password successfully updated.";
					$data ["urlredirect"] = site_url ( 'user/view/' ) . $this->input->post ( 'userID' );
					$this->load->view ( "header", $data );
					$this->load->view ( "message" );
					$this->load->view ( "footer" );
				} else {
					$data ["display"] = "block";
					$data ["class"] = "success";
					$data ["msg"] = "Password successfully updated.";
					$data ["urlredirect"] = site_url ( 'user/profile' );
					$this->load->view ( "header", $data );
					$this->load->view ( "message" );
					$this->load->view ( "footer" );
				}
			} else {
				
				// error
				$data ["display"] = "block";
				$data ["class"] = "danger";
				$data ["msg"] = "Error in updating the password!";
				$data ["urlredirect"] = site_url ( 'user/profile' );
				$this->load->view ( "header", $data );
				$this->load->view ( "message" );
				$this->load->view ( "footer" );
			}
		} else {
			
			// error
			$data ["display"] = "block";
			$data ["class"] = "danger";
			$data ["msg"] = "Error in updating the password!";
			$data ["urlredirect"] = site_url ( 'user/profile' );
			$this->load->view ( "header", $data );
			$this->load->view ( "message" );
			$this->load->view ( "footer" );
		}
	}
	
	public function delete_terminal() {
		// load submenu
		$this->submenu ();
		$data = $this->data;
		
		// check roles
		if ($this->roles ['edit']) {
			$this->db->where ( 'id', $id );
			$this->db->delete ( 'terminals' );
			
			// successful
			$data ["class"] = "success";
			$data ["msg"] = $this->module . " successfully deleted.";
			$data ["urlredirect"] = $this->controller_page . "/view/$userID";
			$this->load->view ( "header", $data );
			$this->load->view ( "message" );
			$this->load->view ( "footer" );
		
		} else {
			// error
			$data ["class"] = "danger";
			$data ["msg"] = "Sorry, you don't have access to this page!";
			$data ["urlredirect"] = "";
			$this->load->view ( "header", $data );
			$this->load->view ( "message" );
			$this->load->view ( "footer" );
		}
	}
	
	public function add_terminal() {
		// load submenu
		$this->submenu ();
		$data = $this->data;
		
		// check roles
		if ($this->roles ['edit']) {
			// check if IP exist to this user
			$this->db->where ( 'ip', $_SERVER ['REMOTE_ADDR'] );
			$this->db->where ( 'userID', $this->session->userdata ( 'current_user' )->userID );
			$ip_exist = $this->db->count_all_results ( 'terminals' );
			
			if (! $ip_exist) {
				$d = array ();
				$d ['userID'] = $this->session->userdata ( 'current_user' )->userID;
				$d ['ip'] = $_SERVER ['REMOTE_ADDR'];
				$d ['terminal'] = gethostbyaddr ( $_SERVER ['REMOTE_ADDR'] );
				
				$this->db->insert ( 'terminals', $d );
				
				// success msg
				$data ['class'] = "success";
				$data ['msg'] = "Workstation successfully added.";
				$data ['urlredirect'] = $this->controller_page . "/view/" . $this->session->userdata ( 'current_user' )->userID;
				$this->load->view ( 'header', $data );
				$this->load->view ( 'message' );
				$this->load->view ( 'footer' );
			
			} else {
				// already exist
				$data ["class"] = "danger";
				$data ["msg"] = "Workstation already exist!";
				$data ["urlredirect"] = $this->controller_page . "/view/" . $this->session->userdata ( 'current_user' )->userID;
				$this->load->view ( "header", $data );
				$this->load->view ( "message" );
				$this->load->view ( "footer" );
			}
		
		} else {
			// no access this page
			$data ['class'] = "danger";
			$data ['msg'] = "Sorry, you don't have access to this page!";
			$data ['urlredirect'] = "";
			$this->load->view ( 'header', $data );
			$this->load->view ( 'message' );
			$this->load->view ( 'footer' );
		}
	}
	
	public function conversation() {
		// load submenu
		$this->submenu ();
		$data = $this->data;
		
		// check roles
		if ($id == $this->session->userdata ( 'current_user' )->userID) {
			if ($this->input->post ( 'cmdSubmit' )) {
				$data ['userID'] = $this->session->userdata ( 'current_user' )->userID;
				$data ['user'] = $this->session->userdata ( 'current_userName' );
				$data ['chatWith'] = $this->input->post ( 'chatWith' );
				$data ['startDate'] = date ( 'Y-m-d 00:00:00', strtotime ( $this->input->post ( 'startDate' ) ) );
				$data ['endDate'] = date ( 'Y-m-d 23:59:59', strtotime ( $this->input->post ( 'endDate' ) ) );
				
				$query = "SELECT * FROM (`chat`) 
                WHERE ((`from` = '" . $data ['user'] . "' AND `to` = '" . $data ['chatWith'] . "') 
                OR (`from` = '" . $data ['chatWith'] . "' AND `to` = '" . $data ['user'] . "'))
                AND `sent` >= '" . $data ['startDate'] . "'
                AND `sent` <= '" . $data ['endDate'] . "'
                ORDER BY `sent` ASC";
				
				$data ['records'] = $this->db->query ( $query );
			
			} else {
				$data ['userID'] = $this->session->userdata ( 'current_user' )->userID;
				$data ['user'] = $this->session->userdata ( 'current_userName' );
				$data ['chatWith'] = "";
				$data ['startDate'] = date ( 'M d, Y' );
				$data ['endDate'] = date ( 'M d, Y' );
				$this->db->where ( 'id', 0 );
				$data ['records'] = $this->db->get ( 'chat' );
			}
			
			// load views
			$this->load->view ( 'header', $data );
			$this->load->view ( $this->module_path . '/conversation' );
			$this->load->view ( 'footer' );
		} else {
			// no access this page
			$data ['class'] = "danger";
			$data ['msg'] = "Sorry, you don't have access to this page!";
			$data ['urlredirect'] = "";
			$this->load->view ( 'header', $data );
			$this->load->view ( 'message' );
			$this->load->view ( 'footer' );
		}
	}
	
	public function update_roles() {
		// load submenu
		$this->submenu ();
		$data = $this->data;
		
		// check roles
		if ($this->roles ['edit']) {
			$userID = $this->input->post ( 'userID' );
			$roles_array = $this->input->post ( 'roles[]' );
			
			// delete existing set of roles
			$this->db->where ( 'userID', $userID );
			$this->db->delete ( 'userroles' );
			
			foreach ( $roles_array as $r ) {
				$this->db->set ( 'roleName', $r );
				$this->db->set ( 'userID', $userID );
				$this->db->insert ( 'userroles' );
			}
			
			// successful
			$data ["class"] = "success";
			$data ["msg"] = "User Roles successfully updated.";
			$data ["urlredirect"] = site_url ( "user/view/" . $userID );
			$this->load->view ( "header", $data );
			$this->load->view ( "message" );
			$this->load->view ( "footer" );
		} else {
			// error
			$data ["class"] = "danger";
			$data ["msg"] = "Sorry, you don't have access to this page!";
			$data ["urlredirect"] = "";
			$this->load->view ( "header", $data );
			$this->load->view ( "message" );
			$this->load->view ( "footer" );
		}
	}
	
	public function upload() {
		//************** general settings *******************
		// load submenu
		$this->submenu ();
		$data = $this->data;
		//************** end general settings *******************
		

		$this->db->where ('userID', $this->input->post ( 'imgID' ) );
		$data ['rec'] = $this->db->get ( 'users' )->row ();
		// upload picture
		$config ['upload_path'] = 'assets/img/users/';
		$config ['allowed_types'] = 'jpg|png|bmp|JPG|JPEG|PNG|BMP';
		$config ['max_size'] = '5000';
		$config ['max_width'] = '2000';
		$config ['max_height'] = '2000';
		$this->load->library ( 'upload', $config );
		
		if (is_file ( $_FILES ['userfile'] ['tmp_name'] )) {
			
			// delete first the existing image
			@unlink ( $config ['upload_path'] . $this->input->post ( 'imgID' ) . $data ['rec']->imageExtension );
			@unlink ( $config ['upload_path'] . $this->input->post ( 'imgID' ) . '_thumb' . $data ['rec']->imageExtension );
			
			if (! $this->upload->do_upload ()) {
				$error = array ('error' => $this->upload->display_errors () );
			} else {
				// rename uploaded image
				$data ['upload_data'] = $this->upload->data ();
				$fn =  $this->input->post ( 'imgID' );
				rename ( $config ['upload_path'] . $data ['upload_data'] ['file_name'], $config ['upload_path'] . $fn . $data ['upload_data'] ['file_ext'] );
				
				// save image extension to database
				$this->db->where ( 'userID', $data ['rec']->userID );
				$this->db->update ( 'users', array ('imageExtension' => $data ['upload_data'] ['file_ext'] ) );
				
				// generate thumbnail
				$this->_create_thumbnail ( $config ['upload_path'] . $fn . $data ['upload_data'] ['file_ext'] );
				
				$data ['class'] = "success";
				$data ['msg'] = $this->module . " image successfully saved.";
				$data ['urlredirect'] = $this->controller_page . "/view/".$this->input->post ( 'imgID' );
				$this->load->view ( 'header', $data );
				$this->load->view ( 'message' );
				$this->load->view ( 'footer' );
			}
		} else {
			// error
			$data ["display"] = "block";
			$data ["class"] = "errorbox";
			$data ["msg"] = "Error in uploading image!<br>See manual for reference.";
			$data ["urlredirect"] = "refresh";
			$this->load->view ( "header_popup", $data );
			$this->load->view ( "message", $data );
			$this->load->view ( "footer_popup" );
		}
	}
	
	private function _create_thumbnail($source = "") {
		$this->load->library ( 'image_lib' );
		
		$config ['image_library'] = 'gd2';
		$config ['source_image'] = $source;
		$config ['create_thumb'] = TRUE;
		$config ['maintain_ratio'] = FALSE;
		$config ['width'] = 50;
		$config ['height'] = 50;
		
		$this->image_lib->clear ();
		$this->image_lib->initialize ( $config );
		$this->image_lib->resize ();
	}
	
}
