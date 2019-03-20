<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	
	public function index()
	{

// 		$this->load->view('welcome_message');

		//Database Migration
        //Source : cloudmd_cloudmddb
        //Target : cloudmdv2
	

        //Table: rvs_procedures  
        // $rvs = $this->db->get('cloudmd_cloudmddb.rvs_procedures')->result();
        // foreach($rvs as $data) 
        // {
        // 	$new = [
        //         'code' => $data->code,
        //         'description' => $data->description,
        //         'caseRate' => $data->caseRate,
        //         'professionalFee' => $data->professionalFee,
        //         'hicFee' => $data->HICFee,
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];

        // 	$this->db->insert('cloudmdv2.rvs_procedures', $new);
        // }
        // echo 'rvs_procedures last inserted id: '.$this->db->insert_id() .'<br/>';

		//------------------------------------------------------------------------------//

        //Table: icd_10_assesment          
        // $icd = $this->db->get('cloudmd_cloudmddb.icd_10_assesment')->result();
        // foreach($icd as $data) 
        // {
        // 	$new = [
        //         'code' => $data->code,
        //         'description' => $data->description,
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];

        // 	$this->db->insert('cloudmdv2.icd10', $new);
        // }
        // echo 'icd10 last inserted id: '.$this->db->insert_id() .'<br/>';
               
		//------------------------------------------------------------------------------//

        //Table: practice                  
        // $practice = $this->db->get('cloudmd_cloudmddb.specialization')->result();
        // foreach($practice as $data) 
        // {
        // 	$new = [
        //         'practice' => $data->specialty,
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];

        // 	$this->db->insert('cloudmdv2.practice', $new);
        // }
        // echo 'Practice last inserted id: '.$this->db->insert_id() .'<br/>';
   	     
		//------------------------------------------------------------------------------//

        //Table: vaccines              
        // $vaccine = $this->db->get('cloudmd_cloudmddb.vaccine_list')->result();
        // foreach($vaccine as $data) 
        // {
        // 	$new = [
        //         'name' => $data->name,
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];

        // 	$this->db->insert('cloudmdv2.vaccines', $new);
        // }
          
        // echo 'Vaccine last inserted id: '.$this->db->insert_id() .'<br/>';
               
		//------------------------------------------------------------------------------//

        //Table: vaccine_shot          
        // $vaccine_shot = $this->db->get('cloudmd_cloudmddb.vaccine_shot')->result();
        // foreach($vaccine_shot as $data) 
        // {
        // 	$new = [
        //         'name' => $data->name,
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];

        // 	$this->db->insert('cloudmdv2.vaccine_shot', $new);
        // }
          
        // echo 'vaccine_shot last inserted id: '.$this->db->insert_id() .'<br/>';

		//------------------------------------------------------------------------------//

        //Table: vaccine_shot            
        // $vaccine_shot = $this->db->get('cloudmd_cloudmddb.vaccine_shot')->result();
        // foreach($vaccine_shot as $data) 
        // {
        // 	$new = [
        //         'name' => $data->name,
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];

        // 	$this->db->insert('cloudmdv2.vaccine_shot', $new);
        // }
          
        // echo 'vaccine_shot last inserted id: '.$this->db->insert_id() .'<br/>';

		//------------------------------------------------------------------------------//

        //Table: patients          
        //Where clause : {doctor_id: 196} 
        //doctor_id depends if account is solo or firm	        
        // $this->db->where_in('cloudmd_cloudmddb.person.doctorid','196');
        // $patients = $this->db->get('cloudmd_cloudmddb.person')->result();
        // // echo $this->db->last_query();
        
        // foreach($patients as $data) 
        // {
        // 	$new = [
        //         'lname' => $data->lastname,
        //         'fname' => $data->firstname,
        //         'mname' => $data->middlename,
        //         'bday' => $data->birthdate,
        //         'birthplace' => $data->place_of_birth,
        //         'civilStatus' => $data->civil_status,
        //         'gender' => $data->gender,
        //         'mobileNo' => $data->mobileno,
        //         'telNo' => $data->phoneno,
        //         'email' => $data->emailadd,
        //         'streetNo' => $data->address,
        //         'religion' => $data->religion,
        //         'occupation' => $data->patients_occupation,
        //         'bloodType' => $data->bloodtype,               
        //         'fathersName' => $data->father,
        //         'fathersOccupation' => $data->fathers_occupation,
        //         'mothersName' => $data->mother,
        //         'mothersOccupation' => $data->mothers_occupation,
        //         'spouse' => $data->gardian,
        //         'spouseOccupation' => $data->spouse_occupation,               
        //         'refferedBy' => $data->referring_physician,
        //         'remarks' => $data->summary,
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];

        // 	$this->db->insert('cloudmdv2.patients', $new);
        // }
          
        // echo 'patients last inserted id: '.$this->db->insert_id() .'<br/>';

        //------------------------------------------------------------------------------//

        //Table: hmo          
        //Where clause : {doctor_id: 196} 
        //doctor_id depends if account is solo or firm	        
        // $this->db->where_in('cloudmd_cloudmddb.hmo.doctor_id','196');
        // $this->db->where('cloudmd_cloudmddb.hmo.delete_status','A');
        // $hmo = $this->db->get('cloudmd_cloudmddb.hmo')->result();
        // // echo $this->db->last_query();
        
        // foreach($hmo as $data) 
        // {
        // 	$new = [
        //         'name' => $data->name,
        //         'address' => $data->address,
        //         'claimsOfficer' => $data->claims_officer,
        //         'contactNo' => $data->phone_no,
        //         'tin' => $data->tin_no,
        //         'vat' => $data->vat,
        //         'billingTerm' => $data->billing_terms,               
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];

        // 	$this->db->insert('cloudmdv2.hmo', $new);
        // }
          
        // echo 'hmo last inserted id: '.$this->db->insert_id() .'<br/>';

        //------------------------------------------------------------------------------//

        //Table: clinics          
        //Where clause : {doctor_id: 196} 
        //doctor_id depends if account is solo or firm	        
        // $this->db->where_in('cloudmd_cloudmddb.clinic.account_id','83');
        // $this->db->where('cloudmd_cloudmddb.clinic.delete_status','A');
        // $clinic = $this->db->get('cloudmd_cloudmddb.clinic')->result();
        // // echo $this->db->last_query();
        
        // foreach($clinic as $data) 
        // {
        // 	$new = [
        //         'name' => $data->clinic_name,
        //         'contactNo' => $data->clinic_phone,                
        //         'streetNo' => $data->clinic_address,                                            
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];

        // 	$this->db->insert('cloudmdv2.clinics', $new);
        // }
          
        // echo 'clinics last inserted id: '.$this->db->insert_id() .'<br/>';

        //------------------------------------------------------------------------------//
        
        //Table: lab_results_category         
        //Where clause : {doctor_id: 196} 
        //doctor_id depends if account is solo or firm	        
        // $this->db->where_in('cloudmd_cloudmddb.lab_category.doctor_id','196');
        // $this->db->where('cloudmd_cloudmddb.lab_category.delete_status','A');
        // $lab_category = $this->db->get('cloudmd_cloudmddb.lab_category')->result();
        // // echo $this->db->last_query();
        
        // foreach($lab_category as $data) 
        // {
        // 	$new = [
        //         'category' => $data->name,                                                        
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];

        // 	$this->db->insert('cloudmdv2.lab_results_category', $new);
        // }
          
        // echo 'lab_results_category last inserted id: '.$this->db->insert_id() .'<br/>';

        //------------------------------------------------------------------------------//
       
        //Table: lab_results          
        //Where clause : {doctor_id: 196} 
        //doctor_id depends if account is solo or firm	 
        //get data that has lab_category_id
        // $this->db->select('cloudmdv2.lab_results_category.labCatID');       
        // $this->db->select('cloudmd_cloudmddb.lab_results.name');       
        // $this->db->select('cloudmd_cloudmddb.lab_results.unit');       
        // $this->db->from('cloudmd_cloudmddb.lab_results');       
        // $this->db->join('cloudmdv2.lab_results_category','cloudmd_cloudmddb.lab_results.lab_category_id=cloudmdv2.lab_results_category.labCatID','left');
        // $this->db->where('cloudmd_cloudmddb.lab_results.doctor_id','196');        
        // // $this->db->where('cloudmd_cloudmddb.lab_category.delete_status','A');
        // $this->db->where('cloudmd_cloudmddb.lab_results.delete_status','A');
        // $lab_results = $this->db->get()->result();
        // echo $this->db->last_query();
     
        // foreach($lab_results as $data) 
        // {
        // 	$new = [
        //         'labCatID' => $data->lab_category_id,                                                        
        //         'name' => $data->name,                                                        
        //         'umsr' => $data->unit,                                                        
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];

        // 	$this->db->insert('cloudmdv2.lab_results', $new);
        // }

        //get data that has no lab_category_id
        // $this->db->from('cloudmd_cloudmddb.lab_results');               
        // $this->db->where('cloudmd_cloudmddb.lab_results.lab_category_id','0');        
        // $this->db->where('cloudmd_cloudmddb.lab_results.doctor_id','196');                
        // $this->db->where('cloudmd_cloudmddb.lab_results.delete_status','A');
        // $lab_results = $this->db->get()->result();
        // // echo $this->db->last_query();

        // foreach($lab_results as $data) 
        // {
        // 	$new = [
        //         'labCatID' => $data->lab_category_id,                                                        
        //         'name' => $data->name,                                                        
        //         'umsr' => $data->unit,                                                        
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];
        // 	$this->db->insert('cloudmdv2.lab_results', $new);
        // }
          
        // echo 'lab_results last inserted id: '.$this->db->insert_id() .'<br/>';

        //------------------------------------------------------------------------------//
    
        //Table: billing_codes         
        //Where clause : {account_id: 83}         
        // $this->db->where('cloudmd_cloudmddb.billing_codes.account_id','83');
        // $this->db->where('cloudmd_cloudmddb.billing_codes.delete_status','A');
        // $billing_codes = $this->db->get('cloudmd_cloudmddb.billing_codes')->result();
        // // echo $this->db->last_query();
        
        // foreach($billing_codes as $data) 
        // {
        // 	$new = [
        //         'particular' => $data->billing_code,                                                        
        //         'amount' => $data->billing_amount,                                                        
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];

        // 	$this->db->insert('cloudmdv2.billing_codes', $new);
        // }
          
        // echo 'billing_codes last inserted id: '.$this->db->insert_id() .'<br/>';

        //------------------------------------------------------------------------------//

        //Table: vital_signs         
        //Where clause : {doctor_id: 83}         
        // $this->db->where('cloudmd_cloudmddb.vital_signs.doctor_id','196');
        // $this->db->where('cloudmd_cloudmddb.vital_signs.delete_status','A');
        // $vital_signs = $this->db->get('cloudmd_cloudmddb.vital_signs')->result();
        // // echo $this->db->last_query();
        
    

        //   foreach($vital_signs as $data) 
        // {
        // 	$new = [
        //         'name' => $data->name,                                                        
        //         'umsr' => $data->unit,                                                        
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];

        // 	$this->db->insert('cloudmdv2.vital_signs', $new);
        // }
          
        // echo 'vital_signs last inserted id: '.$this->db->insert_id() .'<br/>';

        //------------------------------------------------------------------------------//

        //Table: labs_order			         
        //Where clause : {doctor_id: 83}         
        // $this->db->where('cloudmd_cloudmddb.labs.doc_id','196');
        // $this->db->where('cloudmd_cloudmddb.labs.delete_status','A');
        // $labs = $this->db->get('cloudmd_cloudmddb.labs')->result();
        // // echo $this->db->last_query();           

        // foreach($labs as $data) 
        // {
        // 	$new = [
        //         'name' => $data->name,                
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];
        // 	$this->db->insert('cloudmdv2.labs_order', $new);
        // }
          
        // echo 'labs_order last inserted id: '.$this->db->insert_id() .'<br/>';

        //------------------------------------------------------------------------------//

        //Table: imaging_order			         
        //Where clause : {doctor_id: 83}         
        // $this->db->where('cloudmd_cloudmddb.radiology.doc_id','196');
        // $this->db->where('cloudmd_cloudmddb.radiology.delete_status','A');
        // $radiology = $this->db->get('cloudmd_cloudmddb.radiology')->result();
        // // echo $this->db->last_query();           

        // foreach($radiology as $data) 
        // {
        // 	$new = [
        //         'name' => $data->name,                
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];
        // 	$this->db->insert('cloudmdv2.imaging_order', $new);
        // }
          
        // echo 'imaging_order last inserted id: '.$this->db->insert_id() .'<br/>';

        //------------------------------------------------------------------------------//


        //Table: procedures_order			         
        //Where clause : {doctor_id: 83}         
        // $this->db->where('cloudmd_cloudmddb.procedures.doc_id','196');
        // $this->db->where('cloudmd_cloudmddb.procedures.delete_status','A');
        // $procedures = $this->db->get('cloudmd_cloudmddb.procedures')->result();
        // // echo $this->db->last_query();           

        // foreach($procedures as $data) 
        // {
        // 	$new = [
        //         'name' => $data->name,                
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];
        // 	$this->db->insert('cloudmdv2.procedures_order', $new);
        // }
          
        // echo 'procedures_order last inserted id: '.$this->db->insert_id() .'<br/>';

        //------------------------------------------------------------------------------//

        //Table: item_categories			         
        //Where clause : {doctor_id: 83}         
        // $this->db->where('cloudmd_cloudmddb.pos_item_category.doctor_id','196');
        // $this->db->where('cloudmd_cloudmddb.pos_item_category.delete_status','A');
        // $pos_item_category = $this->db->get('cloudmd_cloudmddb.pos_item_category')->result();
        // // echo $this->db->last_query();           

        // foreach($pos_item_category as $data) 
        // {
        // 	$new = [
        //         'category' => $data->item_category,                
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];
        // 	$this->db->insert('cloudmdv2.item_categories', $new);
        // }
          
        // echo 'item_categories last inserted id: '.$this->db->insert_id() .'<br/>';

        //------------------------------------------------------------------------------//

        //Table: items			         
        //Where clause : {doctor_id: 196}         
        // $this->db->where('cloudmd_cloudmddb.pos_items.pos_item_category','0');
        // $this->db->where('cloudmd_cloudmddb.pos_items.doctor_id','196');
        // $this->db->where('cloudmd_cloudmddb.pos_items.delete_status','A');
        // $pos_items = $this->db->get('cloudmd_cloudmddb.pos_items')->result();
        // // echo $this->db->last_query();           

        // foreach($pos_items as $data) 
        // {
        // 	$new = [
        //         'catID' => $data->pos_item_category,                
        //         'name' => $data->name,                
        //         'umsr' => $data->item_unit,                
        //         'reorderLvl' => $data->reorder_level,                                
        //         'createdBy' => 1,
        //         'dateInserted' => date('Y-m-d H:i:s'),
        //         'status' => 1
        // 	];
        // 	$this->db->insert('cloudmdv2.items', $new);
        // }
          
        // echo 'items last inserted id: '.$this->db->insert_id() .'<br/>';

        //------------------------------------------------------------------------------//





       echo "<br/>Super Done!";

	}//closing of index public function

    
}
