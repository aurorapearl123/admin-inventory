<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration extends CI_Controller
{
    protected $oldDatabase = "cloudmd";
    protected $newDatabase = "cloudmdv2";

    public function __construct()
    {
       parent::__construct();
       if ($this->config_model->getConfig('Maintenance Mode')=='1') {
           header('location: '.site_url('maintenance_mode'));
       }
    }

    public function practices()
    {
        $table = "practice";
        $the_data = [];
        $old_data = $this->db->get($this->oldDatabase.'.'.$table)->result();
        foreach($old_data as $data) {
            $new_data =  [
                'name' => $data->practice_name,
                'createdBy' => 1,
                'dateInserted' => date('Y-m-d'),
                'dateLastEdit' => date('Y-m-d'),
                'status' => 1
            ];
            $the_data[] = $new_data;
        }
        $this->db->insert_batch($this->newDatabase.'.practices', $the_data);
        echo "Practice successfully migrated.";
    }

    public function users()
    {
        $table = 'doctor';
        $the_data = [];
        $old_data = $this->db->get($this->oldDatabase.'.'.$table)->result();
        foreach($old_data as $data) {
            $create_date = $data->createddate == '0000-00-00 00:00:00' ? '0000-00-00 00:00:00' :  date('Y-m-d', strtotime($data->createddate));
            $new_data =  [
                'userID' => $data->doctorid,
                'firstName' => $data->firstname,
                'middleName' => $data->middlename,
                'lastName' => $data->lastname,
                'preferences' => 'Dashboard,Transactions,Inventory,Master Files,Reports',
                'userName' => $data->username,
                'userPswd' => $data->password,
                'gender' => $data->gender,
                'dateEntered' => date('Y-m-d h:i:s'),
                'practiceID' => $data->practice,
                'email' => $data->emailadd,
                'ptrNo' => $data->ptr_no,
                's2No' => $data->s2_no,
                'licenceNo' => $data->license_no,
                'role' => $data->role,
                'primaryclinic' => $data->primaryclinic,
                'image_path' => $data->image_path,
                'mobile' => $data->mobileno,
                'phone' => $data->phoneno,
                'birthdate' => $data->birthdate,
                'createdBy' => 1,
                'dateInserted' => $create_date,
                'dateLastEdit' => date('Y-m-d', strtotime($data->updated_at)),
                'status' => ($data->delete_status == 'A')  ? 1 : 0
            ];
            $the_data[] = $new_data;
        }
        $this->db->insert_batch($this->newDatabase.'.users', $the_data);
        echo "Users successfully migrated.";
    }

    
    public function patients($count)
    {
        $oldDatabase = "cloudmd";
        $newDatabase = "cloudmdv2";
        $table = 'person';
        $old_data = [];
        if($count == 1) {
            $old_data = $this->db->limit(100000)->get($oldDatabase.'.'.$table)->result();
        }
        else if($count == 2){
            $old_data = $this->db->limit(100000,100000)->get($oldDatabase.'.'.$table)->result();
        }
        else if($count == 3){
            $old_data = $this->db->limit(100000,200000)->get($oldDatabase.'.'.$table)->result();
        }
        else {
            echo "No parameter";
            die();
        }

        $the_data = [];
        foreach($old_data as $data) {
            $create_date = $data->createddate == '0000-00-00 00:00:00' ? '0000-00-00 00:00:00' :  date('Y-m-d', strtotime($data->createddate));
            $updated_at = $data->updated_at == '0000-00-00 00:00:00' ? '0000-00-00 00:00:00' :  date('Y-m-d', strtotime($data->updated_at));
            $new_data =  [
                'patientID' => $data->personid,
                'fname' => $data->firstname,
                'mname' => $data->middlename,
                'lname' => $data->lastname,
                'mname' => $data->middlename,
                'bday' => $data->birthdate,
                'birthplace' => $data->place_of_birth,
                'civilStatus' => $data->civil_status,
                'gender' => $data->gender,
                'mobileNo' => $data->mobileno,
                'telNo' => $data->phoneno,
                'email' => $data->emailadd,
                'streetNo' => $data->address,
                'religion' => $data->religion,
                'occupation' => $data->patients_occupation,
                'bloodType' => $data->bloodtype,               
                'fathersName' => $data->father,
                'fathersOccupation' => $data->fathers_occupation,
                'mothersName' => $data->mother,
                'mothersOccupation' => $data->mothers_occupation,
                'spouse' => $data->gardian,
                'spouseOccupation' => $data->spouse_occupation,               
                'refferedBy' => $data->referring_physician,
                'remarks' => $data->summary,
                'createdBy' => 1,
                'dateInserted' => $create_date,
                'dateLastEdit' => $updated_at,
                'status' => ($data->delete_status == 'A')  ? 1 : 0,
                
            ];
           $the_data[] = $new_data;
        }

        $this->db->insert_batch($newDatabase.'.patients', $the_data);      
    }

    public function records()
    {
        //headers
        $this->db->select('*');
        $this->db->from($this->oldDatabase.'.my_records');
        $old_data = $this->db->get()->result();

        $the_data = [];
        foreach($old_data as $data) {
            $create_date = $data->createddate == '0000-00-00 00:00:00' ? '0000-00-00 00:00:00' :  date('Y-m-d', strtotime($data->created_at));
            $updated_at = $data->updated_at == '0000-00-00 00:00:00' ? '0000-00-00 00:00:00' :  date('Y-m-d', strtotime($data->updated_at));
            $new_data =  [
                'recordID' => $data->record_id,
                'patientID' => $data->patient_id,
                'userID' => $data->doctor_id,
                'type' => $data->type,
                'description' => $data->description,
                'recipient' => $data->recipient,
                'subject' => $data->subject,
                'public' => $data->public,
                'dateRequested' => date('Y-m-d',$data->date_requested),
                'serviceTime' => $data->service_time,
                'filePath' => $data->file_path,
                'createdBy' => 1,
                'dateInserted' => $create_date,
                'dateLastEdit' => $updated_at,
                'status' => ($data->delete_status == 'A')  ? 1 : 0,
                
            ];
           $the_data[] = $new_data;
        }

        $this->db->insert_batch($this->newDatabase.'.recordheaders', $the_data);

        //add details
        //set a proccess
        $this->db->select('*');
        $this->db->from($this->oldDatabase.'.record_upload');
        $record_uploads = $this->db->get()->result();
        
        $records_details = [];
        foreach($record_uploads as $record)
        {
            $headerID = $record->table_record_id;
            $file_upload_id = $record->file_upload_id;
            //file_upload
            $this->db->select('*');
            $this->db->from($this->oldDatabase.'.file_upload');
            $this->db->where($this->oldDatabase.'.file_upload.id', $file_upload_id);
            $file_uploads = $this->db->get()->row();
            if($file_uploads->file_path != "") {
                $temp = [
                    'recordID' => $headerID,
                    'filePath' => $file_uploads->file_path,
                    'fileName' => $file_uploads->file_name
                ];
            
                $this->db->insert($this->newDatabase.'. recorddetails', $temp);
            }
               
        }
        echo "Record successfully added.";
        die();
    }

    public function schedules($count)
    {
        
        $table = 'my_schedule';
        if($count == 1) {
            $limit = str_pad($count,6,0,STR_PAD_RIGHT);
            $old_data = $this->db->limit($limit)->get($this->oldDatabase.'.'.$table)->result();
        
        }
        else {
            $count = $count - 1;
            $limit = str_pad($count,6,0,STR_PAD_RIGHT);
            $old_data = $this->db->limit(100000,$limit)->get($this->oldDatabase.'.'.$table)->result();
        }

        $the_data = [];
        foreach($old_data as $data) {
            $create_date = $data->created_at == '0000-00-00 00:00:00' ? '0000-00-00 00:00:00' :  date('Y-m-d', strtotime($data->created_at));
            $updated_at = $data->updated_at == '0000-00-00 00:00:00' ? '0000-00-00 00:00:00' :  date('Y-m-d', strtotime($data->updated_at));
            $new_data =  [
                'scheduleID' => $data->sched_id,
                'patientID' => $data->patient_id,
                'userID' => $data->doctor_id,
                'location' => $data->lcoation,
                'duration' => $data->duration,
                'reason' => $data->reason,
                'date' => $data->date,
                'startTime' => $data->start_time,
                'priority' => $data->priority,
                'smsSent' => $data->sms_sent,
                'reminderSent' => $data->reminder_sent,
                'reccuring' => $data->reccuring,
                'followUpNumber' => $data->fullow_up_number,
                'followUpTimeMeasure' => $data->follow_up_time_measure,
                'followUpPlanDate' => $data->follow_up_plan_date,
                'followUpInstructions' => $data->follow_up_instructions,
                'followUpStatus' => $data->follow_up_status,
                'followUpby' => $data->follow_up_by,
                'followUpDate' => $data->follow_up_date,
                'edcPlan' => $data->edc_plan,
                'paymentMethod' => $data->payment_method,
                'hmoID' => $data->hmo_id,
                'remarks' => $data->remarks,
                'createdBy' => 1,
                'dateInserted' => $create_date,
                'dateLastEdit' => $updated_at,
                'status' => ($data->delete_status == 'A')  ? 1 : 0
            ];
            $the_data[] = $new_data;
        }

        
        $this->db->insert_batch($this->newDatabase.'.schedules', $the_data);
        echo "Schedule successfully migrated.";   
    }

    public function problems($count)
    {
        $table = 'problem_list';
        if($count == 1) {
            $limit = str_pad($count,6,0,STR_PAD_RIGHT);
            $old_data = $this->db->limit($limit)->get($this->oldDatabase.'.'.$table)->result();
        
        }
        else {
            $count = $count - 1;
            $limit = str_pad($count,6,0,STR_PAD_RIGHT);
            $old_data = $this->db->limit(100000,$limit)->get($this->oldDatabase.'.'.$table)->result();
        }

        $the_data = [];
        foreach($old_data as $data) {
            $create_date = $data->created_at == '0000-00-00 00:00:00' ? '0000-00-00 00:00:00' :  date('Y-m-d', strtotime($data->created_at));
            $updated_at = $data->updated_at == '0000-00-00 00:00:00' ? '0000-00-00 00:00:00' :  date('Y-m-d', strtotime($data->updated_at));
            $new_data =  [
                'problemID' => $data->id,
                'name' => $data->problem_name,
                'createdBy' => 1,
                'dateInserted' => $create_date,
                'dateLastEdit' => $updated_at,
                'status' => ($data->delete_status == 'A')  ? 1 : 0
            ];
            $the_data[] = $new_data;
        }

        
        $this->db->insert_batch($this->newDatabase.'.problems', $the_data);
        echo "Problem successfully migrated.";   

    }

    public function common_complaints()
    {
        $table = 'common_complaints';
        $old_data = $this->db->get($this->oldDatabase.'.'.$table)->result();
        $the_data = [];
        foreach($old_data as $data) {
            $create_date = $data->created_at == '0000-00-00 00:00:00' ? '0000-00-00 00:00:00' :  date('Y-m-d h:i:s', strtotime($data->created_at));
            $updated_at = $data->updated_at == '0000-00-00 00:00:00' ? '0000-00-00 00:00:00' :  date('Y-m-d h:i:s', strtotime($data->updated_at));
            $new_data =  [
                'ccID' => $data->id,
                'userID' => $data->doc_id,
                'name' => $data->name,
                'favorite' => $data->favorite,
                'createdBy' => 1,
                'dateInserted' => $create_date,
                'dateLastEdit' => $updated_at,
                'status' => ($data->delete_status == 'A')  ? 1 : 0
            ];
            $the_data[] = $new_data;
        }

        if(!empty($the_data)) 
        {
            $this->db->insert_batch($this->newDatabase.'.common_complaints', $the_data);
            echo "Common Complaints successfully migrated.";  
        }
        else {
            echo "Empyt table.";  
        }
         
       
    }

    public function assessments($count)
    {
        $table = 'assessment';
        //$old_data = $this->db->where('id', 234058)->get($this->oldDatabase.'.'.$table)->result();
        if($count == 1) {
            $limit = str_pad($count,6,0,STR_PAD_RIGHT);
            $old_data = $this->db->limit($limit)->get($this->oldDatabase.'.'.$table)->result();
        
        }
        else {
            $count = $count - 1;
            $limit = str_pad($count,6,0,STR_PAD_RIGHT);
            $old_data = $this->db->limit(100000,$limit)->get($this->oldDatabase.'.'.$table)->result();
        }
        $details = [];
        foreach($old_data as $data) {

            //assesments
            $create_date = $data->created_at == '0000-00-00 00:00:00' ? '0000-00-00 00:00:00' :  date('Y-m-d', strtotime($data->created_at));
            $updated_at = $data->updated_at == '0000-00-00 00:00:00' ? '0000-00-00 00:00:00' :  date('Y-m-d', strtotime($data->updated_at));
            $new_data =  [
                'assessmentID' => $data->id,
                'scheduleID' => $data->sched_id,
                'comment' => $data->comment,
                'createdBy' => 1,
                'dateInserted' => $create_date,
                'dateLastEdit' => $updated_at,
                'status' => ($data->delete_status == 'A')  ? 1 : 0
            ];
            $the_data[] = $new_data;

                            $this->db->where($this->oldDatabase.'.problem_list.assessment_id', $data->id);
                            $this->db->from($this->oldDatabase.'.problem_list');
            $problem_list = $this->db->get()->result();
            foreach($problem_list as $problem)
            {
               
                $temp['assessmentID'] = $problem->assessment_id;
                $temp['icd10ID'] = $problem->icd_id;
                $temp['problemID'] = $problem->id;
                $details[] = $temp;

            }

           
        }

        $this->db->insert_batch($this->newDatabase.'.assessments', $the_data);

        $this->db->insert_batch($this->newDatabase.'.assessmentdetails', $details);


        echo "Assessment successfully migrated.";   
        
    }

   
	
	
}
