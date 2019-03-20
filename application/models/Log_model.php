<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_model extends CI_Model 
{
	var $fields;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function table_logs($module, $table, $pkey, $pid, $operation, $logs)
	{
		$data = array();
		$data['userID'] 	= $this->session->userdata('current_user')->userID;
		$data['host'] 		= $_SERVER['REMOTE_ADDR'];
		$data['hostname'] 	= "";
		$data['date'] 		= date('Y-m-d H:i:s');		
		$data['module'] 	= $module;
		$data['table'] 		= $table;
		$data['pkey'] 		= $pkey;
		$data['pid'] 		= $pid;
		$data['operation'] 	= $operation;
		$data['logs'] 		= $logs;
		
		$this->db->insert('table_logs', $data); 
		
		return true;
	}
	
	public function view_logs($module, $table, $pkey, $pid, $logs)
	{
	    // check if last table log entry is view
	    $this->db->where('userID', $this->session->userdata('current_user')->userID);
	    $this->db->where('host', $_SERVER['REMOTE_ADDR']);
	    $this->db->order_by('lid','desc');
	    $this->db->limit(1);
	    $log = $this->db->get('table_logs')->row();
	    
	    if ($log->operation == 'View' && $log->module == $module && $log->table == $table && $log->pid == $pid) {
    	    
	    } else {
	        $data = array();
	        $data['userID'] 	= $this->session->userdata('current_user')->userID;
	        $data['host'] 		= $_SERVER['REMOTE_ADDR'];
	        $data['hostname'] 	= "";
	        $data['date'] 		= date('Y-m-d H:i:s');
	        $data['module'] 	= $module;
	        $data['table'] 		= $table;
	        $data['pkey'] 		= $pkey;
	        $data['pid'] 		= $pid;
	        $data['operation'] 	= 'View';
	        $data['logs'] 		= $logs;
	        
	        $this->db->insert('table_logs', $data);
	    }
	    
	    return true;
	}
	
	
	public function login_logs($operation, $logs='Success')
	{
		$data = array();
		$data['userID'] 	= $this->session->userdata('current_user')->userID;
		$data['host'] 		= $_SERVER['REMOTE_ADDR'];
		$data['hostname'] 	= "";
		$data['date'] 		= date('Y-m-d H:i:s');
		$data['operation'] 	= $operation;
		$data['logs'] 		= $logs;
		
		$this->db->insert('login_logs', $data); 
		
		return true;
	}
	
	public function field_logs($module, $table, $pkey, $pid, $operation, $new_data)
	{
		$datetime = date('Y-m-d H:i:s');
		
		// get all fields
		$results = $this->db->query('describe '.$table); 
		
		$fds = array();
		if ($results->num_rows()) {
			foreach($results->result() as $f) {
				$fds[] = $f->Field;
			}
		}
		
		// get the target record
		$this->db->where($pkey,$pid);
		$target_rec = $this->db->get($table);
		
		$old_data = array();
		if ($target_rec->num_rows()) {
			$recs = $target_rec->result();
			$rec = $recs[0];
			
			// convert object to array
			if (!empty($new_data)) {
				foreach($new_data as $f=>$v) {
					$old_data[$f] = $rec->$f;
				}
			}
		}
		
		// check for any changes
		$changes_found = 0;
		if (!empty($old_data) && !empty($new_data)) {
			foreach($new_data as $f=>$v) {
				if (trim($old_data[$f]) != trim($v)) {
					$changes_found = 1;
					// there's change of value here
					$data = array();
					$data['userID'] 	= $this->session->userdata('current_user')->userID;
					$data['host'] 		= $_SERVER['REMOTE_ADDR'];
					$data['hostname'] 	= "";
					$data['date'] 		= $datetime;
					$data['table'] 		= $table;
					$data['module'] 	= $module;
					$data['pkey'] 		= $pkey;
					$data['pid'] 		= $pid;
					$data['operation'] 	= $operation;
					$data['field'] 		= $f;
					$data['oldvalue'] 	= trim($old_data[$f]);
					$data['newvalue'] 	= trim($v);
					
					$this->db->insert('field_logs', $data); 
				}
			}
		}
		
		
		return $changes_found;
	}
	
}
// end Log_model