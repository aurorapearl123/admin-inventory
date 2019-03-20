<?php
class Logs extends CI_Controller 
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
		$this->module       = 'Administrator';
		// $this->data['current_module']   = $this->modules['Administrator'];
		$this->data['controller_page']  = $this->controller_page = site_url('logs');
		$this->table        = 'config';
		$this->module_path 	= 'modules/'.strtolower($this->module).'/logs';
		
		// check for maintenance period
		if ($this->config_model->getConfig('Maintenance Mode')=='1') {
		    header('location: '.site_url('maintenance_mode'));
		}
		
		// check user session
		if (!$this->session->userdata('current_user')->sessionID) {
		    header('location: '.site_url('login'));
		}
	}
	
	public function index()
	{
		$this->user_log();
	}
	
	public function submenu()
	{
	    //submenu setup
	    require_once('modules.php');
	    
	    foreach($modules as $mod) {
	        //modules/<module>/
	        // - <menu>
	        // - <metadata>
	        $this->load->view('modules/'.str_replace(" ","_",strtolower($mod)).'/metadata');
	    }
	    
        $this->data['modules']               = $this->modules;
        $this->data['current_main_module']   = $this->modules[$this->module]['main'];              // defines the current main module
        $this->data['current_module']        = $this->modules[$this->module]['sub']['Logs'];   
	    // check roles
	    $this->check_roles();
	    $this->data['roles']   = $this->roles;
	}	
	
	public function check_roles()
	{
		// check roles
		$this->roles['view'] 	= $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View '.$this->module);
	}
	
	
	public function record_log($table, $pkey, $pid, $module)
	{
		$pid = $this->encrypter->decode($pid);
		// record logs
		$this->db->select('field_logs.*');
		$this->db->select('users.lastName');
		$this->db->select('users.firstName');
		// $this->db->select('employees.lname');
		// $this->db->select('employees.fname');
		$this->db->from('field_logs');
		$this->db->join('users','field_logs.userID=users.userID','left');
		// $this->db->join('user_ess','field_logs.userID=user_ess.userID','left');
		// $this->db->join('employees','user_ess.empID=employees.empID','left');
		$this->db->where('field_logs.pkey', $pkey);
		$this->db->where('field_logs.pid', $pid);
		$this->db->where('field_logs.table', $table);
		$this->db->order_by("field_logs.lid", "DESC");
		$record_logs = $this->db->get();
		$records = array();
		if ($record_logs->num_rows()) {
			foreach($record_logs->result() as $row) {				
				$d = array();
				$d['date'] 		= date('m/d/Y h:i a', strtotime($row->date));
				$d['host'] 		= $row->hostname." (".$row->host.")";
				$d['user'] 		= ($row->lastName) ? $row->lastName.' , '.$row->firstName : $row->lname.' , '.$row->fname;
				$d['operation'] = $row->operation;
				$d['field'] 	= $row->field;
				$d['old'] 		= $row->oldvalue;
				$d['new'] 		= $row->newvalue;
				
				$records[] 		= $d;
			}
		}
		
		$data['records'] 	= $records;
		
		// table logs
	    $this->db->select('table_logs.*');
		$this->db->select('users.lastName');
		$this->db->select('users.firstName');
		// $this->db->select('employees.lname');
		// $this->db->select('employees.fname');
		$this->db->from('table_logs');
		$this->db->join('users','table_logs.userID=users.userID','left');
		// $this->db->join('user_ess','table_logs.userID=user_ess.userID','left');
		// $this->db->join('employees','user_ess.empID=employees.empID','left');
		$this->db->where('table_logs.module', str_replace('&', ' ', $module));
		$this->db->where('table_logs.pid', $pid);				
		$this->db->order_by("table_logs.lid", "DESC");
		$table_logs = $this->db->get();
		
		$records_table = array();
		
		if ($table_logs->num_rows()) {
			foreach($table_logs->result() as $row) {
				$d = array();
				$d['date'] 		= date('F d, Y h:i a', strtotime($row->date));
				$d['host'] 		= $row->hostname." (".$row->host.")";
				$d['user'] 		= ($row->lastName) ? $row->lastName.' , '.$row->firstName : $row->lname.' , '.$row->fname;
				$d['module'] 	= $row->module;
				$d['operation'] = $row->operation;
				$d['logs'] 		= $row->logs;
				
				$records_table[] = $d;
			}
		}
		
		$data['records_table'] 	= $records_table;
		$data['module'] = str_replace('&', ' ', $module);
		
		// load views
		$this->load->view('header_popup', $data);
		$this->load->view('modules/administrator/logs/record_log', $data);
		$this->load->view('footer_popup');
	}
	
	public function print_user_log($userID=0, $startDate=0, $endDate=0)
	{
		// get the user
		$this->user_model->userID = $userID;
		$this->user_model->getRecord();
		$data['rec'] = $this->user_model;
		
		$records = array();
		$startDate 	= date("Y-m-d", $startDate);
		$endDate  	= date("Y-m-d 23:59:00", $endDate);
		
		// login logs
		$this->db->where('userID', $userID);
		$this->db->where("(date >= '$startDate' and date <= '$endDate' )");
		$login_logs = $this->db->get('login_logs');
		
		// record logs
		$this->db->where('userID', $userID);
		$this->db->where("(date >= '$startDate' and date <= '$endDate' )");
		$record_logs = $this->db->get('table_logs');
		
		if ($login_logs->num_rows()) {
			foreach($login_logs->result() as $row) {
				$d = array();
				$d['date'] 		=  date('m/d/Y h:i a', strtotime($row->date));
				$d['host'] 		= $row->hostname." (".$row->host.")";
				$d['module'] 	= "Authentication";
				$d['operation'] = $row->operation;
				$d['logs'] 		= $row->logs;
				
				$records[] 		= $d;
			}
		}
		
		if ($record_logs->num_rows()) {
			foreach($record_logs->result() as $row) {
				$d = array();
				$d['date'] 		=  date('m/d/Y h:i a', strtotime($row->date));
				$d['host'] 		= $row->hostname." (".$row->host.")";
				$d['module'] 	= $row->module;
				$d['operation'] = $row->operation;
				$d['logs'] 		= $row->logs;
				
				$records[] 		= $d;
			}
		}
		
		// sort ascending
		for($r=0; $r<count($records); $r++) {
			for($c=$r+1; $c<count($records); $c++) {
				if (strtotime($records[$r]['date']) > strtotime($records[$c]['date'])) {
					$temp = $records[$r];
					$records[$r] = $records[$c];
					$records[$c] = $temp;
				}
			}
		}
		
		$data['startDate'] 	= $startDate;
		$data['endDate'] 	= $endDate;
		$data['records'] 	= $records;
		
		// load views
		$this->load->view('header_print', $data);
// 		$this->load->view('print_page_header', $data);
		$this->load->view($this->module_path.'/print_user_log', $data);
		$this->load->view('footer_print');
	}
	public function user_log()
	{
		//************** general settings *******************
		// load submenu
		$this->submenu();
		$data = $this->data;

 		//$data['activetab'] = 2;	// no active tab
		// **************************************************
		$this->db->where('status',1);
		$this->db->order_by('lastName','asc');
		$data['users'] = $this->db->get('users')->result();
		// check roles
		if ($this->roles['view']) {
			// get all modules
			if($this->input->post('startDate') != ""){
				$data['startDate'] 	= date('Y-m-d',strtotime($this->input->post('startDate')));
	
				$startDate 	= $data['startDate'].' 00:00:00';
			}else{
				$data['startDate'] 	= date('Y-m-d');
	
				$startDate 	= $data['startDate'].' 00:00:00';
			}
			
			if($this->input->post('endDate') != ""){
				$data['endDate'] 	= date('Y-m-d',strtotime($this->input->post('endDate')));
	
				$endDate  	= $data['endDate'].' 23:59:00';
			}else{
				$data['endDate'] 	= date('Y-m-31');
	
				$endDate  	= $data['endDate'].' 23:59:00';
			}
			if ($this->input->post('userID') != "") {
				$data['user'] = $this->input->post('userID');
			} else {
				$data['user'] = 0;
			}
			
			// record logs
			$this->db->select('table_logs.*');
			$this->db->select('users.lastName');
			$this->db->select('users.firstName');
			$this->db->from('table_logs');
			$this->db->join('users','table_logs.userID=users.userID','left');
			if($data['module'] != ""){
				$this->db->where('table_logs.module', $module);
			}
			if($data['startDate'] != "" && $data['endDate'] != ""){
				$this->db->where("(table_logs.date >= '$startDate' and table_logs.date <= '$endDate' )");
			}
			$this->db->where('table_logs.userID',$data['user']);
			$this->db->order_by('table_logs.date','desc');
			$data['dataRec'] = $this->db->get()->result();
			
			// load views	
			$this->load->view('header', $data);
			$this->load->view($this->module_path.'/user_log', $data);
			$this->load->view('footer');
		} else {
			// error
			$data["display"] = "block";
			$data["class"] 	 = "errorbox";
			$data["msg"] 	 = "Sorry, you don't have access to this page!";
			$data["urlredirect"] = "";
			$this->load->view("header",$data);
			$this->load->view("message",$data);
			$this->load->view("footer");
		}
	}
	
	public function module_log()
	{
		//************** general settings *******************
		// load submenu
		$this->submenu();
		$data = $this->data;

 		//$data['activetab'] = 2;	// no active tab
		// **************************************************
        
		// check roles
		if ($this->roles['view']) {
			// get all modules
			$data['module_list'] = $this->db->query('select distinct module from table_logs')->result();
			
			if($this->input->post('startDate') != "" && $this->input->post('endDate') != ""){
				$data['startDate'] 	= date('Y-m-d',strtotime($this->input->post('startDate')));
				$data['endDate'] 	= date('Y-m-d',strtotime($this->input->post('endDate')));
	
				$startDate 	= $data['startDate'].' 00:00:00';
				$endDate  	= $data['endDate'].' 23:59:00';
			}else{
				$data['startDate'] 	= date('Y-m-d');
				$data['endDate'] 	= date('Y-m-31');
	
				$startDate 	= $data['startDate'].' 00:00:00';
				$endDate  	= $data['endDate'].' 23:59:00';
			}
			if ($this->input->post('module') != "") {
				$data['module'] = $this->input->post('module');
				$module = $data['module'];
			} else {
				$data['module'] = 0;
				$module = $data['module'];
			}
			
			// record logs
			$this->db->select('table_logs.*');
			$this->db->select('users.lastName');
			$this->db->select('users.firstName');
			$this->db->from('table_logs');
			$this->db->join('users','table_logs.userID=users.userID','left');
			if($data['module'] != ""){
				$this->db->where('table_logs.module', $module);
			}
			if($data['startDate'] != "" && $data['endDate'] != ""){
				$this->db->where("(table_logs.date >= '$startDate' and table_logs.date <= '$endDate' )");
			}
			
			$this->db->order_by('table_logs.date','asc');
			$data['record_logs'] = $this->db->get()->result();
			//echo $this->db->last_query();
			
			// load views
			$this->load->view('header', $data);
			$this->load->view($this->module_path.'/module_log', $data);
			$this->load->view('footer');
		} else {
			// error
			$data["display"] = "block";
			$data["class"] 	 = "errorbox";
			$data["msg"] 	 = "Sorry, you don't have access to this page!";
			$data["urlredirect"] = "";
			$this->load->view("header",$data);
			$this->load->view("message",$data);
			$this->load->view("footer");
		}
	}
	public function printlist($a=0,$b=0,$c=0)
	{
		//************** general settings *******************
		// load submenu
		$this->submenu();
		$data = $this->data;

 		//$data['activetab'] = 2;	// no active tab
		// **************************************************
        
		// check roles
		if ($this->roles['view']) {
			// get all modules
			$data['module_list'] = $this->db->query('select distinct module from table_logs')->result();
			
			if($b != '0' && $c != '0'){
				$data['startDate'] 	= date('Y-m-d',strtotime($b));
				$data['endDate'] 	= date('Y-m-d',strtotime($c));
	
				$startDate 	= $data['startDate'].' 00:00:00';
				$endDate  	= $data['endDate'].' 23:59:00';
			}else{
				$data['startDate'] 	= date('Y-m-d');
				$data['endDate'] 	= date('Y-m-31');
	
				$startDate 	= $data['startDate'].' 00:00:00';
				$endDate  	= $data['endDate'].' 23:59:00';
			}
			if ($a != '0') {
				$data['module'] = str_replace("%20"," ",$a);
				$module = $data['module'];
			} else {
				$data['module'] = 0;
				$module = $data['module'];
			}
			
			// record logs
			$this->db->select('table_logs.*');
			$this->db->select('users.lastName');
			$this->db->select('users.firstName');
			$this->db->from('table_logs');
			$this->db->join('users','table_logs.userID=users.userID','left');
			if($data['module'] != ""){
				$this->db->where('table_logs.module', $module);
			}
			if($data['startDate'] != "" && $data['endDate'] != ""){
				$this->db->where("(table_logs.date >= '$startDate' and table_logs.date <= '$endDate' )");
			}
			
			$this->db->order_by('table_logs.date','asc');
			$data['record_logs'] = $this->db->get()->result();
			//echo $this->db->last_query();
			
			// load views
			$this->load->view('header_print', $data);
			$this->load->view($this->module_path.'/printlist', $data);
			$this->load->view('footer_print');
		} else {
			// error
			$data["display"] = "block";
			$data["class"] 	 = "errorbox";
			$data["msg"] 	 = "Sorry, you don't have access to this page!";
			$data["urlredirect"] = "";
			$this->load->view("header",$data);
			$this->load->view("message",$data);
			$this->load->view("footer");
		}
	}
	public function printlist2($a=0,$b=0,$c=0)
	{
		//************** general settings *******************
		// load submenu
		$this->submenu();
		$data = $this->data;

 		//$data['activetab'] = 2;	// no active tab
		// **************************************************
		// check roles
		if ($this->roles['view']) {
			// get all modules
			
			if($b != 0 && $c != 0){
				$data['startDate'] 	= date('Y-m-d',strtotime($b));
				$data['endDate'] 	= date('Y-m-d',strtotime($c));
	
				$startDate 	= $data['startDate'].' 00:00:00';
				$endDate  	= $data['endDate'].' 23:59:00';
			}else{
				$data['startDate'] 	= date('Y-m-d');
				$data['endDate'] 	= date('Y-m-31');
	
				$startDate 	= $data['startDate'].' 00:00:00';
				$endDate  	= $data['endDate'].' 23:59:00';
			}
			// record logs
			$this->db->select('table_logs.*');
			$this->db->select('users.lastName');
			$this->db->select('users.firstName');
			$this->db->from('table_logs');
			$this->db->join('users','table_logs.userID=users.userID','left');
			if($a != '0'){
				$this->db->where('table_logs.userID', $a);
			}
			if($data['startDate'] != "" && $data['endDate'] != ""){
				$this->db->where("(table_logs.date >= '$startDate' and table_logs.date <= '$endDate' )");
			}
			
			$this->db->order_by('table_logs.date','asc');
			$data['record_logs'] = $this->db->get()->result();
			// load views
			$this->load->view('header_print', $data);
			$this->load->view($this->module_path.'/printlist', $data);
			$this->load->view('footer_print');
		} else {
			// error
			$data["display"] = "block";
			$data["class"] 	 = "errorbox";
			$data["msg"] 	 = "Sorry, you don't have access to this page!";
			$data["urlredirect"] = "";
			$this->load->view("header",$data);
			$this->load->view("message",$data);
			$this->load->view("footer");
		}
	}
	
	public function print_module_log($module='', $startDate=0, $endDate=0)
	{
		$records = array();
		$startDate 	= date("Y-m-d", $startDate);
		$endDate  	= date("Y-m-d 23:59:00", $endDate);
		
		// record logs
		$this->db->select('table_logs.*');
		$this->db->select('users.lastName');
		$this->db->select('users.firstName');
		$this->db->from('table_logs');
		$this->db->join('users','table_logs.userID=users.userID','left');
		$this->db->where('table_logs.module', $module);
		$this->db->where("(table_logs.date >= '$startDate' and table_logs.date <= '$endDate' )");
		$this->db->order_by('table_logs.date','asc');
		$record_logs = $this->db->get();
		
		if ($record_logs->num_rows()) {
			foreach($record_logs->result() as $row) {
				$d = array();
				$d['date'] 		= date('m/d/Y h:i a', strtotime($row->date));
				$d['host'] 		= $row->hostname." (".$row->host.")";
				$d['user'] 		= $row->lastName.' , '.$row->firstName;
				$d['operation'] = $row->operation;
				$d['logs'] 		= $row->logs;
				
				$records[] 		= $d;
			}
		}
		
		$data['module'] 	= $module;
		$data['startDate'] 	= $startDate;
		$data['endDate'] 	= $endDate;
		$data['records'] 	= $records;
			
		// load views
		$this->load->view('header_print', $data);
		// $this->load->view('print_page_header', $data);
		$this->load->view($this->module_path.'/print_module_log', $data);
		$this->load->view('footer_print');
	}
	function exportlist($a=0,$b=0,$c=0) {
		// load submenu
		$this->submenu ();
		$data = $this->data;
		
		if($b != 0 && $c != 0){
			$data['startDate'] 	= date('Y-m-d',strtotime($b));
			$data['endDate'] 	= date('Y-m-d',strtotime($c));

			$startDate 	= $data['startDate'].' 00:00:00';
			$endDate  	= $data['endDate'].' 23:59:00';
		}else{
			$data['startDate'] 	= date('Y-m-d');
			$data['endDate'] 	= date('Y-m-31');

			$startDate 	= $data['startDate'].' 00:00:00';
			$endDate  	= $data['endDate'].' 23:59:00';
		}
		if ($a != 0) {
			$data['module'] = $a;
		} else {
			$data['module'] = 0;
		}
		
		// record logs
		$this->db->select('table_logs.*');
		$this->db->select('users.lastName');
		$this->db->select('users.firstName');
		$this->db->from('table_logs');
		$this->db->join('users','table_logs.userID=users.userID','left');
		if($data['module'] != ""){
			$this->db->where('table_logs.module', $module);
		}
		if($data['startDate'] != "" && $data['endDate'] != ""){
			$this->db->where("(table_logs.date >= '$startDate' and table_logs.date <= '$endDate' )");
		}
		
		$this->db->order_by('table_logs.date','asc');
		$records = $this->db->get ()->result ();
		
		$title = "Audit Activity Log List";
		$companyName = $this->config_model->getConfig ( 'Company' );
		$address = $this->config_model->getConfig ( 'Address' );
		
		//XML Blurb
		$data = "<?xml version='1.0'?>
  
    		<?mso-application progid='Excel.Sheet'?>
  
    		<Workbook xmlns='urn:schemas-microsoft-com:office:spreadsheet' xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:excel' xmlns:ss='urn:schemas-microsoft-com:office:spreadsheet' xmlns:html='http://www.w3.org/TR/REC-html40'>
    		<Styles>
            <Style ss:ID='s20'>
    	        <Alignment ss:Horizontal='Center' ss:Vertical='Bottom'/>
    		  <Font ss:Bold='1' ss:Size='14'/>
    		</Style>
    
    		<Style ss:ID='s23'>
    		  <Font ss:Bold='1'/>
	        <Borders>
                    <Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/>
                   </Borders>
    		</Style>
  
    		<Style ss:ID='s24'>
                <Alignment ss:Horizontal='Center' ss:Vertical='Bottom'/>
                <Font ss:Bold='1'/>
            </Style>
	    
	        <Style ss:ID='s24A'>
                <Alignment ss:Horizontal='Center' ss:Vertical='Bottom'/>
            </Style>
	    
	        <Style ss:ID='s24B'>
                <Alignment ss:Horizontal='Center' ss:Vertical='Bottom'/>
	           <Borders>
                    <Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/>
                   </Borders>
            </Style>
	    
            <Style ss:ID='s25'>
                <Alignment ss:Horizontal='Right' ss:Vertical='Bottom'/>
                <NumberFormat ss:Format='#,##0.00_ ;[Red]\-#,##0.00\ '/>
                <Font ss:Bold='1'/>
            </Style>
            <Style ss:ID='s26'>
                <Alignment ss:Horizontal='Right' ss:Vertical='Bottom'/>
                <NumberFormat ss:Format='#,##0.00_ ;[Red]\-#,##0.00\ '/>
	           <Borders>
                    <Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/>
                   </Borders>
            </Style>
            <Style ss:ID='s27'>
    		      <Borders>
                    <Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/>
                   </Borders>
            </Style>
    		</Styles>
    
    		<Worksheet ss:Name='" . $title . "'>
  
    		<Table>
    		<Column ss:Index='1' ss:AutoFitWidth=\"1\" ss:Width='25.00'/>
    		<Column ss:Index='2' ss:AutoFitWidth=\"1\" ss:Width='80.00'/>
    		<Column ss:Index='3' ss:AutoFitWidth=\"1\" ss:Width='80.00'/>
    		<Column ss:Index='4' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
    		<Column ss:Index='5' ss:AutoFitWidth=\"1\" ss:Width='150.00'/>
    		<Column ss:Index='6' ss:AutoFitWidth=\"1\" ss:Width='150.00'/>
    		    ";
		
		// header
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'></Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s20'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'>" . $companyName . "</Data></Cell>";
		$data .= "</Row>";
		$data .= "<Row ss:StyleID='s24A'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'>" . $address . "</Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'></Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'>" . strtoupper ( $title ) . "</Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'></Data></Cell>";
		$data .= "</Row>";
		
		$fields [] = "  ";
		$fields [] = "DATE/TIME";
		$fields [] = "WORKSTATION";
		$fields [] = "USER";
		$fields [] = "OPERATION";
		$fields [] = "LOGS";
		
		$data .= "<Row ss:StyleID='s24'>";
		//Field Name Data
		foreach ( $fields as $fld ) {
			$data .= "<Cell ss:StyleID='s23'><Data ss:Type='String'>$fld</Data></Cell>";
		}
		$data .= "</Row>";
		
		if (count ( $records )) {
			$ctr = 1;
			foreach ( $records as $row ) {
				$data .= "<Row>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $ctr . ".</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->date . "</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->host . "</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->firstName.' '.$row->lastName . "</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->operation . "</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->logs . "</Data></Cell>";
				$data .= "</Row>";
				
				$ctr ++;
			}
		}
		$data .= "</Table></Worksheet>";
		$data .= "</Workbook>";
		
		//Final XML Blurb
		$filename = "activity_log_list";
		
		header ( "Content-type: application/octet-stream" );
		header ( "Content-Disposition: attachment; filename=$filename.xls;" );
		header ( "Content-Type: application/ms-excel" );
		header ( "Pragma: no-cache" );
		header ( "Expires: 0" );
		
		echo $data;
	
	}
	function exportlist2($a=0,$b=0,$c=0) {
		// load submenu
		$this->submenu ();
		$data = $this->data;
		
		if($b != 0 && $c != 0){
			$data['startDate'] 	= date('Y-m-d',strtotime($b));
			$data['endDate'] 	= date('Y-m-d',strtotime($c));

			$startDate 	= $data['startDate'].' 00:00:00';
			$endDate  	= $data['endDate'].' 23:59:00';
		}else{
			$data['startDate'] 	= date('Y-m-d');
			$data['endDate'] 	= date('Y-m-31');

			$startDate 	= $data['startDate'].' 00:00:00';
			$endDate  	= $data['endDate'].' 23:59:00';
		}
		// record logs
		$this->db->select('table_logs.*');
		$this->db->select('users.lastName');
		$this->db->select('users.firstName');
		$this->db->from('table_logs');
		$this->db->join('users','table_logs.userID=users.userID','left');
		if($a != '0'){
			$this->db->where('table_logs.userID', $a);
		}
		if($data['startDate'] != "" && $data['endDate'] != ""){
			$this->db->where("(table_logs.date >= '$startDate' and table_logs.date <= '$endDate' )");
		}
		
		$this->db->order_by('table_logs.date','asc');
		$records = $this->db->get ()->result ();
		
		$title = "Audit User Log List";
		$companyName = $this->config_model->getConfig ( 'Company' );
		$address = $this->config_model->getConfig ( 'Address' );
		
		//XML Blurb
		$data = "<?xml version='1.0'?>
  
    		<?mso-application progid='Excel.Sheet'?>
  
    		<Workbook xmlns='urn:schemas-microsoft-com:office:spreadsheet' xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:excel' xmlns:ss='urn:schemas-microsoft-com:office:spreadsheet' xmlns:html='http://www.w3.org/TR/REC-html40'>
    		<Styles>
            <Style ss:ID='s20'>
    	        <Alignment ss:Horizontal='Center' ss:Vertical='Bottom'/>
    		  <Font ss:Bold='1' ss:Size='14'/>
    		</Style>
    
    		<Style ss:ID='s23'>
    		  <Font ss:Bold='1'/>
	        <Borders>
                    <Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/>
                   </Borders>
    		</Style>
  
    		<Style ss:ID='s24'>
                <Alignment ss:Horizontal='Center' ss:Vertical='Bottom'/>
                <Font ss:Bold='1'/>
            </Style>
	    
	        <Style ss:ID='s24A'>
                <Alignment ss:Horizontal='Center' ss:Vertical='Bottom'/>
            </Style>
	    
	        <Style ss:ID='s24B'>
                <Alignment ss:Horizontal='Center' ss:Vertical='Bottom'/>
	           <Borders>
                    <Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/>
                   </Borders>
            </Style>
	    
            <Style ss:ID='s25'>
                <Alignment ss:Horizontal='Right' ss:Vertical='Bottom'/>
                <NumberFormat ss:Format='#,##0.00_ ;[Red]\-#,##0.00\ '/>
                <Font ss:Bold='1'/>
            </Style>
            <Style ss:ID='s26'>
                <Alignment ss:Horizontal='Right' ss:Vertical='Bottom'/>
                <NumberFormat ss:Format='#,##0.00_ ;[Red]\-#,##0.00\ '/>
	           <Borders>
                    <Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/>
                   </Borders>
            </Style>
            <Style ss:ID='s27'>
    		      <Borders>
                    <Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/>
                    <Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/>
                   </Borders>
            </Style>
    		</Styles>
    
    		<Worksheet ss:Name='" . $title . "'>
  
    		<Table>
    		<Column ss:Index='1' ss:AutoFitWidth=\"1\" ss:Width='25.00'/>
    		<Column ss:Index='2' ss:AutoFitWidth=\"1\" ss:Width='80.00'/>
    		<Column ss:Index='3' ss:AutoFitWidth=\"1\" ss:Width='80.00'/>
    		<Column ss:Index='4' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
    		<Column ss:Index='5' ss:AutoFitWidth=\"1\" ss:Width='150.00'/>
    		<Column ss:Index='6' ss:AutoFitWidth=\"1\" ss:Width='150.00'/>
    		    ";
		
		// header
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'></Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s20'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'>" . $companyName . "</Data></Cell>";
		$data .= "</Row>";
		$data .= "<Row ss:StyleID='s24A'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'>" . $address . "</Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'></Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'>" . strtoupper ( $title ) . "</Data></Cell>";
		$data .= "</Row>";
		
		$data .= "<Row ss:StyleID='s24'>";
		$data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'></Data></Cell>";
		$data .= "</Row>";
		
		$fields [] = "  ";
		$fields [] = "DATE/TIME";
		$fields [] = "WORKSTATION";
		$fields [] = "USER";
		$fields [] = "OPERATION";
		$fields [] = "LOGS";
		
		$data .= "<Row ss:StyleID='s24'>";
		//Field Name Data
		foreach ( $fields as $fld ) {
			$data .= "<Cell ss:StyleID='s23'><Data ss:Type='String'>$fld</Data></Cell>";
		}
		$data .= "</Row>";
		
		if (count ( $records )) {
			$ctr = 1;
			foreach ( $records as $row ) {
				$data .= "<Row>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $ctr . ".</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->date . "</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->host . "</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->firstName.' '.$row->lastName . "</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->operation . "</Data></Cell>";
				$data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->logs . "</Data></Cell>";
				$data .= "</Row>";
				
				$ctr ++;
			}
		}
		$data .= "</Table></Worksheet>";
		$data .= "</Workbook>";
		
		//Final XML Blurb
		$filename = "user_log_list";
		
		header ( "Content-type: application/octet-stream" );
		header ( "Content-Disposition: attachment; filename=$filename.xls;" );
		header ( "Content-Type: application/ms-excel" );
		header ( "Pragma: no-cache" );
		header ( "Expires: 0" );
		
		echo $data;
	
	}
	
}	
// end