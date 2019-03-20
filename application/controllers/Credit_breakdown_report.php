<?php
defined ('BASEPATH') or exit ('No direct script access allowed');

class Credit_breakdown_report extends CI_Controller {
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
		$this->module = 'Credit Breakdown Report';
		$this->data['controller_page'] = $this->controller_page = site_url('credit_breakdown_report'); // defines contoller link
		$this->table = 'countries'; // defines the default table
		$this->pfield = $this->data['pfield'] = 'countryID'; // defines primary key
		$this->logfield = 'country';
		$this->module_path = 'modules/'.strtolower(str_replace(" ", "_", $this->module)); // defines module path
		

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
		$this->data['current_module'] = $this->modules[$this->module]['sub']['Credit Breakdown Report']; // defines the current sub module
		// check roles
		$this->check_roles ();
		$this->data ['roles'] = $this->roles;
	}
	
	private function check_roles() {
		// check roles
		$this->roles['create'] 	= $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'Add '.$this->module);
		$this->roles['view'] 	= $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'View '.$this->module);
		$this->roles['edit'] 	= $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'Edit '.$this->module);
		$this->roles['delete'] 	= $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'Delete '.$this->module);
		$this->roles['approve'] = $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'Approve '.$this->module);
	}
	
	private function _in_used($id = 0) {
		$tables = array('provinces' => 'countryID');
		
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
	
	public function index() {
		$this->create();
	}
	
	public function create() {
		$this->submenu ();
		$data = $this->data;
		
		// check roles
		if ($this->roles ['create']) {
			
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

}
