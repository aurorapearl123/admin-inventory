<?php

class Usergroup_model extends CI_Model 
{
	var $groupID;
	var $groupName;
	var $groupDesc;
	var $rstatus;
	var $rank;
	
	var $roles;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function saveRecord()
	{
		$data['groupName'] 	= $this->groupName;
		$data['groupDesc'] 	= $this->groupDesc;
		$data['rank'] 		= $this->rank;
		
		$this->db->insert('usergroups', $data); 
		
		// if ($this->db->_error_message())
		// 	return false;
		// else 
			return true;
	}
	
	public function getRecord()
	{
		$this->db->where('groupID', $this->groupID);
		$query = $this->db->get('usergroups'); 
	 	
		if ($query->num_rows()) {
			$record = $query->result();
			$this->groupName	= $record[0]->groupName;
			$this->groupDesc	= $record[0]->groupDesc;
			$this->rstatus		= $record[0]->rstatus;
			$this->rank			= $record[0]->rank;
			
			// get all user group roles
			$this->db->select("usergrouproles.*");
			$this->db->select("roles.roleName");
			$this->db->select("roles.roleDesc");
			$this->db->select("roles.rstatus");
			$this->db->from("usergrouproles");
			$this->db->join('roles',"usergrouproles.roleID=roles.roleID",'left');
			$this->db->where("usergrouproles.groupID",$this->groupID);
			$this->db->order_by("roles.roleName","ASC");
			$this->roles = $this->db->get();
			
		} else {
			$this->groupName	= "";
			$this->groupDesc	= "";
			$this->rstatus		= "";
			$this->rank			= "";
			$this->roles		= "";
		}
	}
	
	public function getAllRecord()
	{
		$this->db->order_by('groupName');
		return  $this->db->get('usergroups'); 
	}
	
	public function get_name($id)
	{
		$this->db->select('groupName');
		$this->db->where('groupID',$id);
		return $this->db->get('usergroups'); 
	}
	
	public function updateRecord()
	{
		$data['groupName'] 	= $this->groupName;
		$data['groupDesc'] 	= $this->groupDesc;
		$data['rstatus'] 	= $this->rstatus;
		$data['rank'] 		= $this->rank;
		
	    $this->db->where('groupID', $this->groupID);
		$this->db->update('usergroups', $data); 
		
		// if ($this->db->_error_message())
		// 	return false;
		// else
			return true;
	}
	
	public function deleteRecord()
	{
		$this->db->where('groupID', $this->groupID);
		$this->db->delete('usergroups'); 
	 	
		// if ($this->db->_error_message())
		// 	return false;
		// else 
			return true;   		
	}
	
	public function isDuplicate()
	{
		$this->db->where('groupName', $this->groupName);
		$this->db->limit(1);
		$query = $this->db->get('usergroups'); 
	 	
		if ($query->num_rows()) {
			return true;
		} else {
			return false;
		}
	}
	
	public function setGroup($name) 
	{
		$this->db->where('groupName',$name);
		$queryResult = $this->db->get('usergroups',1);
		$value = $queryResult->result();
		if ($queryResult->num_rows() > 0)	{
        	$this->groupID 	  	= $value[0]->groupID;
        	$this->groupName  	= $value[0]->groupName;
        	$this->groupDesc 	= $value[0]->groupDesc;
        	$this->rstatus    	= $value[0]->rstatus;
        	$this->rank    		= $value[0]->rank;
		} else {
			$this->groupID     	= "";
			$this->groupName    = "";
			$this->groupDesc    = "";
			$this->rstatus  	= "";
			$this->rank  		= "";
		}
	}
	
	public function isUsed($groupID) 
	{
		if ($groupID) {
			$this->db->where('groupID',$groupID);
			return $this->db->count_all_results('users');
		} 
		return 0;
	}
}