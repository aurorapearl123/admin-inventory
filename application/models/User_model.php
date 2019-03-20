<?php
class User_model extends CI_Model 
{
	var $userID;
	var $sessionID;
	var $userName;
	var $userPswd;
	var $empNo;
	var $lastName;
	var $firstName;
	var $middleName;
	var $dateEntered;
	var $companyID;
	var $branchID;
	var $divisionID;
	var $deptID;
	var $groupID;
	var $groupName;
	var $isAdmin;
	var $preferences;
	var $loginAttempt;
	var $theme;
	var $rstatus;
	var $lastDateAuthorized;
	var $accessTimeStart;
	var $accessTimeEnd;
	var $attemptTimestamp;
	var $attemptIP;
	var $roles;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function saveRecord()
	{
		$data['userID']       = $this->userID;
		$data['userName']     = $this->userName;
		$data['userPswd']     = $this->userPswd;
		$data['empNo']	      = $this->empNo;
		$data['lastName']     = $this->lastName;
		$data['firstName']    = $this->firstName;
		$data['middleName']   = $this->middleName;
		$data['dateEntered']  = $this->dateEntered;
		$data['isAdmin']      = $this->isAdmin;
		$data['companyID']    = $this->companyID;
		$data['branchID']  	  = $this->branchID;
		$data['deptID']       = $this->deptID;
		$data['groupID']      = $this->groupID;
		$data['preferences']  = $this->preferences;
		$data['theme']        = $this->theme;
		$data['accessTimeStart']  = $this->accessTimeStart;
		$data['accessTimeEnd']    = $this->accessTimeEnd;
		
		$this->db->insert('users', $data); 
		
		$this->saveRoles();
		
		if ($this->db->_error_message())
			return false;
		else 
			return true;
	}
	
	public function saveRoles()
	{
		// get all user group roles
		$this->db->select("usergrouproles.*");
		$this->db->from("usergrouproles");
		$this->db->join('usergroups',"usergrouproles.groupID = usergroups.groupID",'left');
		$this->db->where("usergroups.groupID",$this->groupID);
		$roles = $this->db->get();
		
		if($roles->num_rows())
		{
			foreach($roles->result() as $role) {
				$this->userrole_model->roleID = $role->roleID;		
				$this->userrole_model->userID = $this->userID;	
				$this->userrole_model->saveRecord();
			}
		}
	}
	
	public function getAllRecord()
	{
		$this->db->from('users');
		$this->db->join('usergroups','users.groupID=usergroups.groupID','left');
		$this->db->order_by('lastName');		
		return $this->db->get(); 
	}
	
	public function get_name($id)
	{
		$this->db->select('lastName','firstName','middleName');
		$this->db->where('userID',$id);
		return $this->db->get('users'); 
	}
	
	public function getRecord()
	{
		$this->db->select('users.*');
		$this->db->select('usergroups.groupName');
		$this->db->select('companies.companyName');
		$this->db->select('branches.branchName');
		$this->db->select('divisions.divisionName');
		$this->db->from('users');
		$this->db->join('usergroups','users.groupID=usergroups.groupID','left');
		$this->db->join('companies','users.companyID=companies.companyID','left');
		$this->db->join('branches','users.branchID=branches.branchID','left');
		$this->db->join('divisions','users.divisionID=divisions.divisionID','left');
		$this->db->where('userID', $this->userID);
		$query = $this->db->get();  
	 	
		if ($query->num_rows()) {
			$record = $query->result();
			$this->sessionID	= $record[0]->sessionID;
			$this->userName		= $record[0]->userName;
			$this->userPswd		= $record[0]->userPswd;
			$this->empNo		= $record[0]->empNo;
			$this->lastName		= $record[0]->lastName;
			$this->firstName	= $record[0]->firstName;
			$this->middleName	= $record[0]->middleName;
			$this->dateEntered	= $record[0]->dateEntered;			
			$this->companyID	= $record[0]->companyID;
			$this->companyName	= $record[0]->companyName;
			$this->branchID		= $record[0]->branchID;
			$this->branchName	= $record[0]->branchName;
			$this->divisionID	= $record[0]->divisionID;
			$this->divisionName	= $record[0]->divisionName;
			$this->groupID		= $record[0]->groupID;
			$this->groupName	= $record[0]->groupName;
			$this->isAdmin		= $record[0]->isAdmin;
			$this->preferences	= $record[0]->preferences;
			$this->loginAttempt	= $record[0]->loginAttempt;
			$this->theme		= $record[0]->theme;
			$this->status		= $record[0]->status;
			$this->lastDateAuthorized	= $record[0]->lastDateAuthorized;
			$this->accessTimeStart		= $record[0]->accessTimeStart;
			$this->accessTimeEnd		= $record[0]->accessTimeEnd;
			// $this->attemptTimestamp		= $record[0]->attemptTimestamp;
			// $this->attemptIP			= $record[0]->attemptIP;
				
			// get all user group roles
			$this->db->select('userroles.*');
			$this->db->select('roles.roleName');
			$this->db->select('roles.roleDesc');
			$this->db->from('userroles');
			$this->db->join('roles','userroles.roleID=roles.roleID');
			$this->db->where('userroles.userID',$this->userID);
			$this->db->order_by('roles.roleName','ASC');
			$this->roles = $this->db->get();
			
		} else {
			$this->sessionID	= "";
			$this->userName		= "";
			$this->userPswd		= "";
			$this->empNo		= "";
			$this->lastName		= "";
			$this->firstName	= "";
			$this->middleName	= "";
			$this->dateEntered	= "";
			$this->companyID	= "";
			$this->companyName	= "";
			$this->branchID		= "";
			$this->branchName	= "";
			$this->divisionID	= "";
			$this->divisionName	= "";			
			$this->groupID		= "";
			$this->groupName	= "";
			$this->isAdmin		= "";
			$this->preferences	= "";
			$this->loginAttempt	= "";
			$this->theme		= "";
			$this->lastChatView	= "";
			$this->status		= "";
			$this->accessTimeStart	= "";
			$this->accessTimeEnd	= "";
			// $this->attemptTimestamp	= "";
			// $this->attemptIP		= "";
		}
	}
	
	public function setRecord()
	{
		$this->db->where('userName', $this->userName);
		$query = $this->db->get('users',1); 
	 	
		if ($query->num_rows()) {
			$record = $query->result();
			$this->userID		= $record[0]->userID;
		} else {
			$this->userID		= "";
		}
		
		$this->getRecord();
	}
	
	public function updateRecord()
	{
		$data['empNo']		= $this->empNo;
		$data['lastName']	= $this->lastName;
		$data['firstName'] 	= $this->firstName;
		$data['middleName'] = $this->middleName;
		$data['companyID']  = $this->companyID;
		$data['branchID']   = $this->branchID;
		$data['divisionID'] = $this->divisionID;
		$data['groupID'] 	= $this->groupID;
		$data['isAdmin'] 	= $this->isAdmin;
		$data['preferences']= $this->preferences;
		$data['theme'] 		= $this->theme;
		$data['rstatus']	= $this->rstatus;
		$data['accessTimeStart']  = $this->accessTimeStart;
		$data['accessTimeEnd']    = $this->accessTimeEnd;
		
	    $this->db->where('userID', $this->userID);
		$this->db->update('users', $data); 
		
		if ($this->db->_error_message())
			return false;
		else
			return true;
	}
	
	public function updateRoles()
	{
		// get all user group roles
		$this->db->select("userroles.*");
		$this->db->from("userroles");
		$this->db->where("userroles.userID=",$this->userID);
		$roles = $this->db->get();
		
		if($roles->num_rows())
		{
			foreach($roles->result() as $role) {
				$this->userrole_model->id = $role->id;
				$this->userrole_model->deleteRecord();	
			}
		}
		
		$this->saveRoles();
	}
	
	public function updateRecordProfile()
	{
		$data['lastName'] 	= $this->lastName;
		$data['firstName'] 	= $this->firstName;
		$data['middleName'] = $this->middleName;
		// $data['theme']		= $this->theme;
		
	    $this->db->where('userID', $this->userID);
		$this->db->update('users', $data); 
		
		// update session current user vars
		$this->db->where('userID', $this->userID);
		$user = $this->db->get('users')->row();
		//tomEdit 2	
		// $cur_user = array();
		// $cur_user['current_userID']		= $this->userID;
		// $cur_user['current_userName']	= $this->userName;
		// $cur_user['current_userPswd']	= $this->userPswd;
		// $cur_user['current_lastName']	= $this->lastName;
		// $cur_user['current_firstName']	= $this->firstName;
		// $cur_user['current_middleName']	= $this->middleName;
		// $cur_user['current_dateEntered']= $this->dateEntered;
		// $cur_user['current_groupID']	= $this->groupID;
		// $cur_user['current_isAdmin']	= $this->isAdmin;
		// var_dump($user);
		$this->session->set_userdata('current_user', $user);
		
		
		return true;
	}
	
	public function checkSession($userID, $sID)
	{
	    $this->db->where('userID', $userID);
	    $this->db->where('sessionID', $sID);
		$result = $this->db->get('users'); 
		
		if ($result->num_rows())
			return true;
		else
			return false;
	}
	
	public function createSession()
	{
		$data['sessionID'] 	= md5("bis".rand(0,100000000).rand(0,999999999).strtotime(date("d M y h:i:s A")));
	    $this->db->where('userID', $this->userID);
		$this->db->update('users', $data); 
		
		if ($this->db->_error_message())
			return false;
		else
			return $data['sessionID'];
	}
	
	public function clearSession()
	{
		$data['sessionID'] 	= "";
	    $this->db->where('userID', $this->userID);
		$this->db->update('users', $data); 
		
		if ($this->db->_error_message())
			return false;
		else
			return true;
	}
	
	public function update_password()
	{
		$data['userPswd'] 	= $this->userPswd;

		$this->db->where('userID', $this->userID);
		$this->db->update('users', $data); 
		//tomEdit 1
		if (!$this->db->error())
			return false;
		else
			return true;
	}
	
	public function update_password_ess()
	{
		$data['userPswd'] 	= $this->userPswd;

		$this->db->where('userID', $this->userID);
		$this->db->update('user_ess', $data); 
		//tomEdit 1
		if (!$this->db->error())
			return false;
		else
			return true;
	}
	
	public function deleteRecord()
	{
		$this->db->where('userID', $this->userID);
		$this->db->delete('users'); 
	 	
		if ($this->db->_error_message())
			return false;
		else 
			return true;   		
	}
	
	public function isDuplicate()
	{
		$this->db->where('userName', $this->userName);
		$this->db->limit(1);
		$query = $this->db->get('users'); 
	 	
		if ($query->num_rows()) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getAll()
	{
		$this->db->order_by("lastName","ASC");
		$query = $this->db->get('users');
		return $query;
	}
	
	public function authenticate($userName, $userPswd) 
	{
		$this->db->where('userName', $userName);
		$this->db->where('userPswd', md5($userPswd));
		$records = $this->getAll();

		if ($records->num_rows()) {
			$data = $records->result();	
			$this->userID = $data[0]->userID;
			$this->getRecord();
			
			return $this;
		} else {
			return 0;
		}
	}
	
	public function authenticate_admin($userName, $userPswd) 
	{
		$this->db->where('userName', $userName);
		$this->db->where('userPswd', md5($userPswd));
		$this->db->where('rstatus', 1);
		$records = $this->getAll();

		if ($records->num_rows()) {
			$data = $records->result();	
			$this->userID = $data[0]->userID;
			$this->getRecord();
			
			return $this;
		} else {
			return 0;
		}
	}
	
	public function authenticate_user_session()
	{
		if ((!$this->session->userdata('current_user') && !$this->session->userdata('current_user')->username) || !$this->checkSession($this->session->userdata('current_user')->userID,$this->session->userdata('current_user')->sessionID)) {
			header("Location: ".site_url('login/index/unauthorized'));
		} else {
			// not allowed access here
			header("Location: ".base_url()."index.php/login/index/time_limit");
	   }
	}
	
	public function update_login_attempt($userName,$wrong_attempt=0) 
	{
		$this->userName = $userName;
		$this->setRecord();

		if ($this->userID) {
			if ($wrong_attempt) {
				$info['loginAttempt'] = $this->loginAttempt+1;
				
				if ($info['loginAttempt']>=$this->config_model->getConfig('Max Login Attempts')) {
					// lock/inactive the account
					$info['rstatus'] = 0;
				}
			} else {
				$info['loginAttempt'] = 0;
			}
			
			if (!$this->isAdmin) {
				$this->db->where('userID', $this->userID);
				$this->db->update('users', $info);
			} 
			
			if ($this->db->_error_message())
				return false;
			else
				return true;	
		}
	}
	
	public function getTheme($id='') 
	{
		if ($id) {
			$this->db->where('userID', $id);
			$query = $this->db->get('users'); 
			
			if ($query->num_rows()) {
				$record = $query->result();
				
				if ($record[0]->theme!="")
					return $record[0]->theme;
			}
		}
		
		return strtoupper($this->config_model->getConfig('Theme'));
	}
}