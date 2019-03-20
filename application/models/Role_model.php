<?php
class Role_model extends CI_Model 
{
	var $roleID;
	var $roleName;
	var $module;
	var $roleDesc;
	var $rstatus;
	
	public function __construct()
	{
		parent::__construct();
	}

	public function saveRecord()
	{
		$data['roleID']    = $this->roleID;
		$data['module']    = $this->module;
		$data['roleName']  = $this->roleName;
		$data['roleDesc']  = $this->roleDesc;
		
		$this->db->insert('roles', $data); 
		
		if ($this->db->_error_message())
			return false;
		else 
			return true;
	}
	
	/**
	 * Method to retrieve a record.
	 *
	 */
	public function getRecord()
	{
		$this->db->where('roleID', $this->roleID);
		$query = $this->db->get('roles'); 
	 	
		if ($query->num_rows()) {
			$record = $query->result();
			$this->roleName	= $record[0]->roleName;
			$this->roleDesc = $record[0]->roleDesc;
			$this->rstatus 	= $record[0]->rstatus;
		} else {
			$this->roleName = "";
			$this->roleDesc = "";
			$this->rstatus  = "";
		}
	}
	
	/**
	 * Method to update role record
	 * Returns true if successful otherwise false
	 *
	 * @return boolean
	 */
	public function updateRecord()
	{
		$data['roleName']  = $this->roleName;
		$data['roleDesc']  = $this->roleDesc;
		
	    $this->db->where('roleID', $this->roleID);
		$this->db->update('roles', $data); 
		
		if ($this->db->_error_message())
			return false;
		else
			return true;
	}
	
	/**
	 * Method to delete role record
	 * Returns true if successful otherwise false
	 *
	 * @return boolean
	 */
	public function deleteRecord()
	{
		$this->db->where('roleID', $this->roleID);
		$this->db->delete('roles'); 
	 	
		if ($this->db->_error_message())
			return false;
		else 
			return true;   		
	}
	
	/**
	 * Method to retrieve all Role records (optional - with filters)
	 */
	public function getAll()
	{
		$this->db->order_by("roleName","ASC");
		$query = $this->db->get('roles');
		return $query;
	}
	
	
	/**
	 * Method that will set active record
	 *
	 * @param string $title
	 */
	public function setRole($name) 
	{
		$this->db->where('roleName',$name);
		$queryResult = $this->db->get('roles',1);
		$value = $queryResult->result();
		if ($queryResult->num_rows() > 0)	{
        	$this->roleID 	= $value[0]->roleID;
        	$this->roleName = $value[0]->roleName;
        	$this->roleDesc = $value[0]->roleDesc;
        	$this->rstatus  = $value[0]->rstatus;
		} else {
			$this->roleID 	= "";
        	$this->roleName = "";
        	$this->roleDesc = "";
        	$this->rstatus  = "";
		}
	}
}