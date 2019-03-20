<?php
class Userrole_model extends CI_Model 
{
	var $id;
	var $roleID;
	var $userID;
	var $rstatus;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('role_model');
	}
	
	public function saveRecord()
	{
		$data['userID']	= $this->userID;
		$data['roleID']	= $this->roleID;
		
		$this->db->insert('userroles', $data); 
		
		if ($this->db->_error_message())
			return false;
		else 
			return true;
	}
	
	public function deleteRecord()
	{
		$this->db->where('id', $this->id);
		$this->db->delete('userroles'); 
	 	
		if ($this->db->_error_message())
			return false;
		else 
			return true;   		
	}
	
	public function has_access($userID, $roleName) 
	{
		if ($this->session->userdata('current_user')->isAdmin) {
			// admin can always access to all pages
			return 1;
		} else {
		    $this->db->where('userID', $userID);
		    $this->db->where('roleName', $roleName);
			
			return $this->db->count_all_results('userroles');	
		}
	}
}