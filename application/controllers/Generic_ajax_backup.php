<?php
class Generic_ajax_backup extends CI_Controller 
{
	
	public function __construct()
	{
		parent::__construct();	
	    // check for maintenance period
        if ($this->config_model->getConfig('Maintenance Mode')=='1') {
            header('location: '.site_url('maintenance_mode'));
        }
        
        // check user session
        if (!$this->session->userdata('current_user')->sessionID) {
            header('location: '.site_url('login'));
        }
	}
	
	public function get_branches()
	{
	    $companyID = trim($this->input->post('companyID'));
	
	    $this->db->where('companyID',$companyID);
	    $this->db->order_by('branchName','asc');
	    $records = $this->db->get('branches');
	    echo $this->frameworkhelper->get_json_data($records, 'branchID', 'branchName');
	}
	
	public function get_emps()
	{
	    $empID = trim($this->input->post('empID'));
	
	    $this->db->where('empID',$empID);
	    $this->db->where('status',1);
	    $this->db->order_by('employmentNo','asc');
	    $records = $this->db->get('employments');
	    echo $this->frameworkhelper->get_json_data($records, 'employmentID', 'employmentNo');
	}
	
	public function get_departments()
	{
	    $branchID = trim($this->input->post('branchID'));
	
	    $this->db->select('departments.*');
	    $this->db->select('branches.branchCode');
	    $this->db->from('departments');
	    $this->db->join('branches','departments.branchID=branches.branchID','left');
	    $this->db->where('departments.branchID',$branchID);
	    $this->db->order_by('departments.deptName','asc');
	    $records = $this->db->get();
	    
	    echo $this->frameworkhelper->get_json_data($records, 'deptID', 'deptName');
	}
	
	public function get_sections()
	{
	    $deptID = trim($this->input->post('deptID'));
	
	    $this->db->select('divisions.*');
	    $this->db->select('departments.deptCode');
	    $this->db->from('divisions');
	    $this->db->join('departments','divisions.deptID=departments.deptID','left');
	    $this->db->where('divisions.deptID',$deptID);
	    $this->db->order_by('divisions.divisionName','asc');
	    $records = $this->db->get();
	    
	    echo $this->frameworkhelper->get_json_data($records, 'divisionID', 'divisionName');
	}
	
	public function get_code_branches()
	{
	    $companyID = trim($this->input->post('companyID'));
	
	    $this->db->where('companyID',$companyID);
	    $this->db->order_by('branchCode','asc');
	    $records = $this->db->get('branches');
	    echo $this->frameworkhelper->get_json_data($records, 'branchID', 'branchCode');
	}
	
	public function get_divisions()
	{
	    $deptID = trim($this->input->post('deptID'));
	
	    $this->db->where('deptID',$deptID);
	    $this->db->order_by('deptName','asc');
	    $records = $this->db->get('departments');
	    echo $this->frameworkhelper->get_json_data($records, 'deptID', 'deptName');
	}
	
	
	public function get_code_departments()
	{
	    $branchID = trim($this->input->post('branchID'));
	
	    $this->db->select('departments.*');
	    $this->db->select('branches.branchCode');
	    $this->db->from('departments');
	    $this->db->join('branches','departments.branchID=branches.branchID','left');
	    $this->db->where('departments.branchID',$branchID);
	    $this->db->order_by('departments.deptCode','asc');
	    $records = $this->db->get();
	    echo $this->frameworkhelper->get_json_data($records, 'deptID', 'deptCode');
	}
	
	public function get_code_sections()
	{
	    $deptID = trim($this->input->post('deptID'));
	
	    $this->db->select('divisions.*');
	    $this->db->select('departments.deptCode');
	    $this->db->from('divisions');
	    $this->db->join('departments','divisions.deptID=departments.deptID','left');
	    $this->db->where('divisions.deptID',$deptID);
	    $this->db->order_by('divisions.divisionCode','asc');
	    $records = $this->db->get();
	    echo $this->frameworkhelper->get_json_data($records, 'divisionID', 'divisionCode');
	}
	
	public function get_plantilla()
	{
	    $branchID = trim($this->input->post('branchID'));
	    $deptID = trim($this->input->post('deptID'));
	    $divisionID = trim($this->input->post('divisionID'));
	
	    $this->db->select('job_positions.*');
	    $this->db->select('job_titles.jobTitle');
	    $this->db->from('job_positions');
	    $this->db->join('job_titles','job_positions.jobTitleID=job_titles.jobTitleID','left');
	    $this->db->where('job_positions.branchID',$branchID);
	    $this->db->where('job_positions.deptID',$deptID);
	    $this->db->where('job_positions.divisionID',$divisionID);
	    $this->db->where('job_positions.status',1);
	    $this->db->order_by('job_titles.jobTitle','asc');
	    $records = $this->db->get();
	    
	    $display = array('jobTitle'=>' - ','positionCode'=>'');
	    
	    echo $this->frameworkhelper->get_json_data($records, 'jobPositionID', $display);
	}
	
	public function get_countries()
	{
	    $this->db->order_by('country','asc');
	    $records = $this->db->get('countries');
	    echo $this->frameworkhelper->get_json_data($records, 'countryID', 'country');
	}
	
	public function get_provinces()
	{
	    $countryID = '';
	    if (trim($this->input->post('countryID'))!="") {
			$countryID = trim($this->input->post('countryID'));
		} elseif (trim($this->input->post('currentCountryID'))!="") {
			$countryID = trim($this->input->post('currentCountryID'));
		} elseif (trim($this->input->post('provinceCountryID'))!="") {
			$countryID = trim($this->input->post('provinceCountryID'));
		}
	
	    $this->db->where('countryID',$countryID);
	    $this->db->order_by('province','asc');
	    $records = $this->db->get('provinces');
	    echo $this->frameworkhelper->get_json_data($records, 'provinceID', 'province');
	}
	
	public function get_cities()
	{
	    $provinceID = '';
	    if (trim($this->input->post('provinceID'))!="") {
	        $provinceID = trim($this->input->post('provinceID'));
	    } elseif (trim($this->input->post('currentProvinceID'))!="") {
	        $provinceID = trim($this->input->post('currentProvinceID'));
	    } elseif (trim($this->input->post('provinceProvinceID'))!="") {
	        $provinceID = trim($this->input->post('provinceProvinceID'));
	    }
	
	    $this->db->where('provinceID',$provinceID);
	    $this->db->order_by('city','asc');
	    $records = $this->db->get('cities'); 
	    echo $this->frameworkhelper->get_json_data($records, 'cityID', 'city');
	}
	
	public function get_barangays()
	{
	    $cityID = '';
	    if (trim($this->input->post('cityID'))!="") {
	        $cityID = trim($this->input->post('cityID'));
	    } elseif (trim($this->input->post('currentCityID'))!="") {
	        $cityID = trim($this->input->post('currentCityID'));
	    } elseif (trim($this->input->post('provinceCityID'))!="") {
	        $cityID = trim($this->input->post('provinceCityID'));
	    }
	
	    $this->db->where('cityID',$cityID);
	    $this->db->order_by('barangay','asc');
	    $records = $this->db->get('barangays');
	    echo $this->frameworkhelper->get_json_data($records, 'barangayID', 'barangay');
	}
	
	public function get_employments()
	{
	   $empID = $this->input->post('empID');

	   $this->db->select('employments.employmentID');
	   $this->db->select('employments.employmentNo');
	   $this->db->select('companies.companyName');
	   $this->db->select('companies.companyAbbr');
	   $this->db->select('branches.branchAbbr');
	   $this->db->select('divisions.divisionAbbr');
	   $this->db->select('employee_types.employeeType');
	   $this->db->select('job_positions.positionCode');
	   $this->db->select('job_titles.jobTitle');
	   $this->db->from('employments');
	   $this->db->join('companies','employments.companyID=companies.companyID','left');
	   $this->db->join('branches','employments.branchID=branches.branchID','left');
	   $this->db->join('divisions','employments.divisionID=divisions.divisionID','left');
	   $this->db->join('employee_types','employments.employeeTypeID=employee_types.employeeTypeID','left');
	   $this->db->join('job_positions','employments.jobPositionID=job_positions.jobPositionID','left');
	   $this->db->join('job_titles','job_positions.jobTitleID=job_titles.jobTitleID','left');
	   
	   
	    $this->db->where('employments.empID', $empID);
	    $this->db->where('employments.status', 1);
	    $records = $this->db->get();
	    //echo $this->db->last_query();
	    
	    echo $this->frameworkhelper->get_json_data($records, 'employmentID', array('employmentNo'=>' - ','companyAbbr'=>' / ','branchAbbr'=>' / ','employeeType'=>' / ','jobTitle'=>''));
	}
	
	public function get_employments2()
	{
	   $branchID = $this->input->post('branchID');
	   $deptID = $this->input->post('depID');

	   $this->db->select('employments.employmentID');
	   $this->db->select('employments.employmentNo');
	   $this->db->select('employees.lname');
	   $this->db->select('employees.fname');
	   $this->db->select('employees.mname');
	   $this->db->select('companies.companyName');
	   $this->db->select('companies.companyAbbr');
	   $this->db->select('branches.branchAbbr');
	   $this->db->select('divisions.divisionAbbr');
	   $this->db->select('employee_types.employeeType');
	   $this->db->select('job_positions.positionCode');
	   $this->db->select('job_titles.jobTitle');
	   $this->db->from('employments');
	   $this->db->join('employees','employments.empID=employees.empID','left');
	   $this->db->join('companies','employments.companyID=companies.companyID','left');
	   $this->db->join('branches','employments.branchID=branches.branchID','left');
	   $this->db->join('divisions','employments.divisionID=divisions.divisionID','left');
	   $this->db->join('employee_types','employments.employeeTypeID=employee_types.employeeTypeID','left');
	   $this->db->join('job_positions','employments.jobPositionID=job_positions.jobPositionID','left');
	   $this->db->join('job_titles','job_positions.jobTitleID=job_titles.jobTitleID','left');
	   
	   if (!empty($branchID)) {
	    $this->db->where('employments.branchID', $branchID);
	   }
	   if (!empty($deptID)) {
	    $this->db->where('employments.deptID', $deptID);
	   }
	    $records = $this->db->get();
	    //echo $this->db->last_query();
	    
	    echo $this->frameworkhelper->get_json_data($records, 'employmentID', array('fname'=>' ','mname'=>' ','lname'=>''));
	}

	public function get_job_positions()
	{
		$jobTitleID 	= trim($this->input->post('jobTitleID'));
		$companyID  	= trim($this->input->post('companyID'));
		$officeID   	= trim($this->input->post('branchID'));
		$divisionID 	= trim($this->input->post('divisionID'));
		$employeeTypeID = trim($this->input->post('employeeTypeID'));
		
		if ($jobTitleID) {
			$this->db->where('job_positions.jobTitleID', $jobTitleID);
		}
		if ($companyID) {
			$this->db->where('job_positions.companyID', $companyID);
		}
		if ($branchID) {
			$this->db->where('job_positions.officeID', $officeID);
		}
		if ($divisionID) {
			$this->db->where('job_positions.divisionID', $divisionID);
		}
		if ($employeeTypeID) {
			$this->db->where('job_positions.employeeTypeID', $employeeTypeID);
		}
		$this->db->select('job_positions.*');
		$this->db->select('job_titles.jobTitle');
		$this->db->from('job_positions');
		$this->db->join('job_titles','job_positions.jobTitleID=job_titles.jobTitleID','left');
		$this->db->where('job_positions.status', 1);
		$this->db->order_by('job_positions.rank','asc');
		$records = $this->db->get();
		echo $this->frameworkhelper->get_json_data($records, 'jobPositionID', array('positionCode'=>' - ','jobTitle'=>''));
	}	


	public function get_leave_credits()
	{
		$this->db->select('leave_credits.*');
		$this->db->select('leave_types.code');
		$this->db->select('leave_types.leaveType');
		$this->db->from('leave_credits');
		$this->db->join('leave_types','leave_credits.leaveTypeID=leave_types.leaveTypeID','left');
		$this->db->where('leave_credits.empID', $empID);
		$this->db->where('leave_credits.credit >', 0);
		$this->db->order_by('leave_credits.credit','desc');
		$this->db->order_by('leave_types.rank','asc');
		$this->db->order_by('leave_types.leaveType','asc');
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
           echo json_encode($query->result_array());            
        }
	}

	public function get_json_row()
	{
	    $id = trim($this->input->post('id'));
	    $field = trim($this->input->post('field'));
	    $table = trim($this->input->post('table'));
	    $select = trim($this->input->post('select'));
	    $join = trim($this->input->post('join'));

	    //Select
	    $this->db->select($table.'.*');

	    if ($select != '') {
	    	$selects = explode(',', $select);
	    	foreach ($selects as $s) {
	    		$this->db->select($s);
	    	}
	    }

	    //From
	    $this->db->from($table);

	    //Join
	    if ($join != '') {
	    	$joins = explode(',', $join);
	    	foreach ($joins as $j) {
	    		$x = explode('|', $j);
	    		$this->db->join($x[0], $x[1], $x[2]);
	    	}
	    }

	    //Where
	    $this->db->where($field,$id);

	    $record = $this->db->get()->row();
	    // var_dump($record);
	    echo json_encode($record);

	}














































	//The basic CRUD Requests
	public function get_table()
	{
		$select[] = $this->input->post('select');
		if ($select) {
			foreach ($select as $value) {
				$this->db->select($value);
			}
		}
		$table = $this->input->post('table');
		$query = $this->db->get($table);
		
		if ($query->num_rows() > 0) {
           echo json_encode($query->result_array());            
        }
	}

	public function insert_table()
	{
		$table = $this->input->post('table');
		$data = $this->input->post('data');
		$query = $this->db->insert($table, $data)->row();
		$id = $this->db->insert_id();

		$msg['success'] = false;
		$msg['type'] = 'insert';
		$msg['insert_id'] = $id;
		if ($this->db->affected_rows() > 0) {
			$msg['success'] = true;
		}
		echo json_encode($msg);
		//If you want to update a UI after this request, you need to do it in the success callback in the js, it will create an error if you make the call here. 
	}

	public function replace_table()
	{
		$field = $this->input->post('field');
		$arg = $this->input->post('param');
		$table = $this->input->post('table');
		$data = $this->input->post('data');
		$this->db->where($field, $arg);
		$this->db->replace($table, $data);
		$msg['success'] = false;
		$msg['type'] = 'replace';
		if ($this->db->affected_rows() > 0) {
			$msg['success'] = true;
		}
		echo json_encode($msg);
	}

	public function update_table()
	{
		$field = $this->input->post('field');
		$arg = $this->input->post('param');
		$table = $this->input->post('table');
		$data = $this->input->post('data');
		$this->db->where($field, $arg);
		$this->db->update($table, $data);
		$msg['success'] = false;
		$msg['type'] = 'update';
		if ($this->db->affected_rows() > 0) {
			$msg['success'] = true;
		}
		echo json_encode($msg);
	}
	
	public function delete_table()
	{
		$field = $this->input->post('field');
		$arg = $this->input->post('param');
		$table = $this->input->post('table');
		$this->db->where($field, $arg);
		$this->db->delete($table);
		$msg['success'] = false;
		$msg['type'] = 'add';
		if ($this->db->affected_rows() > 0) {
			$msg['success'] = true;
		}
		echo json_encode($msg);
	}
















	// Get with options
	public function get_table_where()
	{
		//'key=>value'
		$where[] = $this->input->post('where');
		if ($where) {
			foreach ($where as $value) {
				$this->db->where($value);
			}
		}

		$table = $this->input->get('table');
		$query = $this->db->get($table);
		if ($query->num_rows() > 0) {
			echo json_encode($query->result_array());
		}
	}

	public function select_join_table_where_get()
	{
		$select[] = $this->input->post('select');
		if ($select) {
			foreach ($select as $value) {
				$this->db->select($value);
			}
		}

		$this->db->from($table);

		//'join_table','table.id=join_table.id','left';
		$join[] = $this->input->post('join');
		if ($join) {
			foreach ($select as $value) {
				$this->db->join($value);
			}
		}		

		//'key=>value'
		$where[] = $this->input->post('where');
		if ($where) {
			foreach ($where as $value) {
				$this->db->where($value);
			}
		}

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			echo json_encode($query->result_array());
		}
	}
























	public function get_iar_products()
	{
		$id = trim($this->input->post('rrID'));

		$this->iar_model->rrID = $id;
		$result = $this->iar_model->view_iar();

		$data['rec'] = $result[0];

        //Details
        //change start
		$sessionSet = 'ridetails'; //ri ang nag request ani so ridetails ato gamiton
		$this->frameworkhelper->clear_session_product($sessionSet);
		if (!empty($result[1])) {
			foreach ($result[1] as $new_product) {
				$this->frameworkhelper->add_session_product($sessionSet, $new_product);
			}
		}
        //change end
        
        echo 1;
	}

	public function get_iar_select_products()
	{
	    $rrID = trim($this->input->post('rrID'));
	
	    $this->db->select('rrdetails.*');
	    $this->db->select('products.name');
	    $this->db->from('rrdetails');
	    $this->db->join('products','rrdetails.productID=products.productID','left');
	    $this->db->where('rrdetails.rrID',$rrID);
	    $this->db->order_by('products.name','asc');
	    $this->db->where('rrdetails.status !=', -100);
	    $this->db->where('products.status !=', -100);
	    $records = $this->db->get();
	    echo $this->frameworkhelper->get_json_data($records, 'productID', 'name');
	}

	public function get_category_products()
	{
		
	    $catID = trim($this->input->post('catID'));
	
	    $this->db->select('products.*');
	    // $this->db->select('category.');
	    $this->db->select('category.category');
	    $this->db->from('products');
	    $this->db->join('category','products.catID=category.catID','left');
	    $this->db->where('products.catID',$catID);
	    $this->db->order_by('products.name','asc');
	    $this->db->where('products.status >', 0);
	    $this->db->where('category.status >', 0);
	    $records = $this->db->get();
	    echo $this->frameworkhelper->get_json_data($records, 'productID', 'name');
	}


	public function set_category_product()
	{
	    $catID = trim($this->input->post('catID'));
	    $productID = trim($this->input->post('productID'));

        $response = new stdClass();
        $response->status  = 0;
        $response->message = '';

        $data = array();
        

        $this->db->select('products.*');
	    // $this->db->select('category.');
	    $this->db->select('category.category');
	    $this->db->select('unit_measurements.umsr');
	    $this->db->from('products');
	    $this->db->join('category','products.catID=category.catID','left');
	    $this->db->join('unit_measurements','products.umsr=unit_measurements.umsrID','left');
	    $this->db->where('products.catID',$catID);
	    $this->db->order_by('products.name','asc');
	    $this->db->where('products.status >', 0);
	    $this->db->where('category.status >', 0);
        $rec = $this->db->get()->row();

        $response->status  = 1;
        $response->rec  = $rec;
        $response->message = 'success';
        echo json_encode($response);
    }

    public function set_po_product()
	{
	    $productID = trim($this->input->post('productID'));

        $response = new stdClass();
        $response->status  = 0;
        $response->message = '';

        $data = array();
        

        $this->db->select('products.*');
	    // $this->db->select('category.');
	    $this->db->select('category.category');
	    $this->db->select('unit_measurements.umsr');
	    $this->db->from('products');
	    $this->db->join('category','products.catID=category.catID','left');
	    $this->db->join('unit_measurements','products.umsr=unit_measurements.umsrID','left');
	    $this->db->where('products.productID',$productID);
	    $this->db->order_by('products.name','asc');
	    $this->db->where('products.status >', 0);
	    $this->db->where('category.status >', 0);
        $rec = $this->db->get()->row();

        $response->status  = 1;
        $response->rec  = $rec;
        $response->message = 'success';
        echo json_encode($response);
    }

    public function set_podetail_product()
	{
	    $poID = trim($this->input->post('poID'));

        $response = new stdClass();
        $response->status  = 0;
        $response->message = '';

        $data = array();
        

        $this->db->select('podetails.*');
        $this->db->select('products.name');
        $this->db->select('products.description');
        $this->db->select('products.productCode');
	    $this->db->select('category.category');
	    $this->db->select('unit_measurements.umsr');
	    $this->db->from('podetails');
	    $this->db->join('products','podetails.productID=products.productID','left');
	    $this->db->join('category','products.catID=category.catID','left');
	    $this->db->join('unit_measurements','products.umsr=unit_measurements.umsrID','left');
	    $this->db->where('podetails.poID',$poID);
	    $this->db->order_by('products.name','asc');
	    $this->db->where('podetails.status >', 0);
	    $this->db->where('products.status >', 0);
	    $this->db->where('category.status >', 0);
        $rec = $this->db->get()->row();

        $response->status  = 1;
        $response->rec  = $rec;
        $response->message = 'success';
        echo json_encode($response);
    }

    public function set_sodetail_product()
	{
	    $poID = trim($this->input->post('soID'));

        $response = new stdClass();
        $response->status  = 0;
        $response->message = '';

        $data = array();
        

        $this->db->select('sodetails.*');
        $this->db->select('products.name');
        $this->db->select('products.description');
        $this->db->select('products.productCode');
	    $this->db->select('category.category');
	    $this->db->select('unit_measurements.umsr');
	    $this->db->from('sodetails');
	    $this->db->join('products','sodetails.productID=products.productID','left');
	    $this->db->join('category','products.catID=category.catID','left');
	    $this->db->join('unit_measurements','products.umsr=unit_measurements.umsrID','left');
	    $this->db->where('sodetails.soID',$soID);
	    $this->db->order_by('products.name','asc');
	    $this->db->where('sodetails.status >', 0);
	    $this->db->where('products.status >', 0);
	    $this->db->where('category.status >', 0);
        $rec = $this->db->get()->row();

        $response->status  = 1;
        $response->rec  = $rec;
        $response->message = 'success';
        echo json_encode($response);
    }
	

	public function get_branch_products()
	{
	    $id = trim($this->input->post('ancillaryID'));
	
	    
	    $this->db->select('products.name');
	    $this->db->select('products.productID');
	    $this->db->from('products');
	    $this->db->where('products.status >', 0);
	    $this->db->where('products.qty >', 0);
	    $this->db->order_by('products.name','asc');
	    $records = $this->db->get();
	    echo $this->frameworkhelper->get_json_data($records, 'productID', 'name');
	}


	public function clear_session_js()
    {
        $sessionSet = $this->input->post('sessionSet');
        $this->frameworkhelper->clear_session_product($sessionSet);
        echo 1;
    }

    public function get_po_suppliers()
	{
	    $suppID = trim($this->input->post('suppID'));

	    $this->db->select('poheaders.*');
	    $this->db->from('poheaders');
	    $this->db->where('poheaders.suppID',$suppID);
	    $this->db->order_by('poheaders.poNo','asc');
	    $this->db->where('poheaders.status', 2);
	    $records = $this->db->get();
	    echo $this->frameworkhelper->get_json_data($records, 'poID', 'poNo');
	}

    public function get_po_select_products()
	{
	    $poID = trim($this->input->post('poID'));
	
	    $this->db->select('podetails.*');
	    $this->db->select('products.name');
	    $this->db->from('podetails');
	    $this->db->join('products','podetails.productID=products.productID','left');
	    $this->db->where('podetails.poID',$poID);
	    $this->db->order_by('products.name','asc');
	    $this->db->where('podetails.status >', 0);
	    $this->db->where('products.status >', 0);
	    $records = $this->db->get();
	    echo $this->frameworkhelper->get_json_data($records, 'productID', 'name');
	}

	
}	