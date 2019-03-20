<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
       parent::__construct();
       if ($this->config_model->getConfig('Maintenance Mode')=='1') {
           header('location: '.site_url('maintenance_mode'));
       }
    }
    
    private function _create_session($userID)
    {
        $sessionID = md5($userID.time());
         
        $this->db->set('sessionID', $sessionID);
        $this->db->where('userID', $userID);
        $this->db->update('users');
         
        return $sessionID;
    }
    
    private function _inc_login_attempt($username)
    {
        $this->db->query("update users set loginAttempt = loginAttempt + 1 where userName='$username'");
        $this->db->query("update users set status = 0 where userName='$username' and loginAttempt=10");
    }
    
    private function _clear_login_attempt($username)
    {
        $this->db->query("update users set loginAttempt = 0 where userName='$username'");
    }
    
    public function index($isError='')
	{
		switch($isError)
		{
			case 'unauthorized':
				$data['duplicate_login'] = 0;
				$data["error"] = "Error: Invalid username and password!";
				break;
			case 'inactive':
				$data['duplicate_login'] = 0;
				$data["error"] = "Error: User account is inactive!";
				break;
			default:
				$data['duplicate_login'] = 0;
				$data['error'] = "";
		}

		$this->load->view('login',$data);
	}
	
	public function error($isError="")
	{
		switch($isError)
		{
			case 'unauthorized':
				$data['duplicate_login'] = 0;
				$data["error"] = "Error: Invalid username and password!";
				break;
			case 'duplicate':
				$data['duplicate_login'] = 1;
				$data["error"] = "Warning: Duplicate session/Improper Logout!";
				break;
			case 'inactive':
				$data['duplicate_login'] = 0;
				$data["error"] = "Error: User account is inactive!";
				break;
			default:
				$data['duplicate_login'] = 0;
				$data['error'] = "&nbsp;";
		}

		$this->load->view('login',$data);
	}
	
    public function authenticate()
	{
		$username = trim($this->input->post("username"));
		$password = trim($this->input->post("password"));
		
		if ( $username && $password ) {
		    $password = md5($password);
		    
		    // authenticate here
		    $this->db->where('userName', $username);
		    $this->db->where('userPswd', $password);
		    $users = $this->db->get('users');
		    
		    $current_user = new stdClass();
		    $current_user->userID = 0;
		    if ($users->num_rows()) {
		        $current_user = $users->row();
		    }
			
			if ($current_user->userID) {
				
				$results = explode(",", $current_user->preferences);
				
				// check if the user status is active
				if ($current_user->status) {
				    
				    // create new session
				    $current_user->sessionID  = $this->_create_session($current_user->userID);
				    $cur_user['current_user'] = $current_user;
				    $this->session->set_userdata($cur_user);
				    	
				    $available_modules = explode(",", $current_user->preferences);
				    	
				    // clear the wrong login attempts
				    $this->_clear_login_attempt($username);
				    
				    // login logs
				    $this->log_model->login_logs('Login');
				    
				    if (!$current_user->isAdmin) {
    				    if (count($available_modules)) {
    				        if (in_array('dashboard',$available_modules))
    				            header("location: ".site_url('dashboard'));
    				        else
    				            header("location: ".site_url(str_replace(" ","_",$available_modules[0])));
    				    } else {
    				        header("location: ".site_url('dashboard'));
    				    }
				    } else {
				        header("location: ".site_url('dashboard'));
				    }
				    
				} else {
					$this->error('inactive');	
				}
			} else {
				// update user account - login attempt (wrong login authentication)
				$this->_inc_login_attempt($username);
				$this->error('unauthorized');	
			}
		} else {
			$this->error('unauthorized');	
		}
	}
	
	
}
