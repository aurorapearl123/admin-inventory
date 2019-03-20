<?php
class Config_model extends CI_Model 
{
	var $configID;
	var $name;
	var $value;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function saveRecord()
	{
		$data['name']  = $this->name;
		$data['value'] = $this->value;

		$this->db->insert('config', $data); 
		
		if ($this->db->_error_message())
			return false;
		else 
			return true;
	}
	
	public function getRecord()
	{
		$this->db->where('configID', $this->configID);
		$query = $this->db->get('config'); 
	 	
		if ($query->num_rows()) {
			$record = $query->result();
			$this->name	   = $record[0]->name;
			$this->value   = $record[0]->value;
		} else {
			$this->name     = "";
			$this->value    = "";
		}
	}
	
	public function updateRecord()
	{
		$data['name']  = $this->name;
		$data['value'] = $this->value;
		
	    $this->db->where('configID', $this->configID);
		$this->db->update('config', $data); 
		
		if ($this->db->_error_message())
			return false;
		else
			return true;
	}
	
	public function deleteRecord()
	{
		$this->db->where('configID', $this->configID);
		$this->db->delete('config'); 
	 	
		if ($this->db->_error_message())
			return false;
		else 
			return true;   		
	}
	
	public function getConfig($title) 
	{
		$this->db->where('name',$title);
		$queryResult = $this->db->get('config',1);
		$value = $queryResult->result();
		if ($queryResult->num_rows() > 0)	{
	        if ($value[0]->value) {
	            return $value[0]->value;
	        } else {
	            return "";
	        }
		} else {
			return "";
		}
	}
	
	public function setConfig($title) 
	{
		$this->db->where('name',$title);
		$queryResult = $this->db->get('config',1);
		$value = $queryResult->result();
		if ($queryResult->num_rows() > 0)	{
        	$this->configID = $value[0]->configID;
        	$this->name     = $value[0]->name;
        	$this->value    = $value[0]->value;
		} else {
			$this->name     = "";
			$this->value    = "";
		}
	}
	
	public function getAccountID($accountCode)
	{
	    $this->db->where('accountCode', $accountCode);
	    $accounts = $this->db->get('chartofaccounts');
	    
	    if ($accounts->num_rows()) {
	        return $accounts->row()->accID;
	    } 
	    
	    return 0;
	}
}
?>