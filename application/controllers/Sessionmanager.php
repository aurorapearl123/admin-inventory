<?php
class Sessionmanager extends CI_Controller 
{
    var $common_menu;
    var $roles;
    var $data;
    var $table;
    var $module;

    var $modules;

    var $module_label;
    var $module_path;
    var $controller_page;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('generic_model', 'cf');
        $this->module = 'Administrator';
        $this->data['controller_page'] = $this->controller_page = site_url('tm_sessionmanager'); // defines contoller link
        $this->table = 'config'; // defines the default table
        $this->module_path = 'modules/' . strtolower(str_replace(" ", "_", $this->module)) . '/config'; // defines module path
                                                                                                  
        // check for maintenance period
        if ($this->config_model->getConfig('Maintenance Mode') == '1') {
            header('location: ' . site_url('maintenance_mode'));
        }
        
        // check user session
        if (! $this->session->userdata('current_user')->sessionID) {
            header('location: ' . site_url('login'));
        }


    }
	
	// AJAX HANDLER FUNCTIONS
	public function push_session_item($fields, $checkDuplicate=0, $duplicateField='')
	{
		$sessionSet = trim($this->input->post('sessionSet'));
		
		$fields = explode('_', $fields);

		$new_item = array();
		if (!empty($fields)) {
			foreach($fields as $f) {
				$new_item[trim($f)] = trim($this->input->post(trim($f)));
			}
		}
		
		$duplicate = 0;
		if ($checkDuplicate) {
			// check for duplicates
			if (!empty($_SESSION[$sessionSet])) {
				foreach($_SESSION[$sessionSet] as $item) {
					if ($new_item[$duplicateField] == $item[$duplicateField]) {
						$duplicate = 1;
						break;
					}
				}
			}
		}
		
		if (!$duplicate)
			echo $this->frameworkhelper->add_session_item($sessionSet, $new_item);
		else
			echo '2'; // duplicate
	}
	
	public function delete_session_item()
	{
		$sessionSet = $this->input->post('sessionSet');
		$targetID   = $this->input->post('targetID');
		
		echo $this->frameworkhelper->del_session_item($sessionSet, $targetID);
	}
	
	public function is_session_empty($sessionSet)
	{
		if (empty($_SESSION[$sessionSet]))
			echo "1";
			//echo "Sample";
		else
			echo "0";
			//echo "aaaaa";
	}
	
	// get record by json
	public function getJSON($table, $fields)
	{
		$this->load->model('generic_model','record');
		
		$fields = explode('_',$fields);
		// set table
		$this->record->table = $table;
		// set where
		if (!empty($fields)) {
			foreach($fields as $f) {
				$this->record->where[trim($f)] = trim($this->input->post(trim($f)));
			}
		}
		// execute retrieve
		$this->record->retrieve();
		$data = (array) $this->record->field;

	    echo json_encode($data);
	}
	
	public function get_session_total()
	{
		$sessionSet = trim($this->input->post('sessionSet'));
		$fld = trim($this->input->post('fld'));
		
		$response = new stdClass();
		$response->total  = 0;
	
		if (!empty($_SESSION[$sessionSet])) {
			foreach($_SESSION[$sessionSet] as $item) {
				$response->total += $item[$fld];
			}
		}
	
		echo json_encode($response);
	}
	
	public function clear_session()
	{
		$sessionSet = trim($this->input->post('sessionSet'));
		$this->frameworkhelper->clear_session_item($sessionSet);
	}
	
	public function formatNumber($decimal_place=2) 
	{
		$amount = $this->input->post('number');
		echo number_format($amount,$decimal_place);
	}
}	
// end