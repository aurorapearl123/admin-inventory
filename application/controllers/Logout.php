<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller
{
    
    private function _clearSession()
	{
	    $userID = $this->session->userdata('current_user')->userID;
	     
	    $this->db->set('sessionID', '');
	    $this->db->where('userID', $userID);
	    $this->db->update('users');
	}
	
	public function index()
	{
		// login logs
		$this->log_model->login_logs('Logout');
		
		// clear prev session
		$this->_clearSession();
		$this->session->sess_destroy();
		
		header("location: ".site_url("login"));	
	}
	
}