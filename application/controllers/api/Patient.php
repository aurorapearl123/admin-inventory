<?php

require APPPATH.'libraries/REST_Controller.php';

class Patient extends REST_Controller
{
    protected $table = "patients";
    public function __construct($config = 'rest')
    {
       
        parent::__construct($config);

        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }

        $this->load->library('form_validation');
    }

   
    public function add_post()
    {
        header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);

        //validation 
        $this->form_validation->set_rules('firstName', 'firstName', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('middleName', 'middleName', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('lastName', 'lastName', 'trim|required|max_length[100]');
        $this->load->library('form_validation');
        if ($this->form_validation->run() == FALSE)
        {
            //validation message
            $message = array(
                'status' => false,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors()
            );

            $this->response($message, REST_Controller::HTTP_NOT_FOUND);

        }else {
            $data = [
                'firstName' => $this->input->post('firstName', TRUE),
                'lastName' => $this->input->post('lastName', TRUE),
                'middleName' => $this->input->post('middleName', TRUE),
                'comment' => $this->input->post('comment', TRUE)
            ];
            
            $this->db->insert('patients', $data);

            $data['patientID'] = $this->db->insert_id();
    

            $return_data = [
                'status' => TRUE,
                'data' => $data,
                'message' => 'Patient user Successfully Added.'
            ];
            $this->response($return_data);
        }
    }

    public function add_edit_post()
    {
        header("Access-Control-Allow-Origin: *");
       // $_POST = $this->security->xss_clean($_POST);

        //validation 
        $this->form_validation->set_rules('firstName', 'firstName', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('middleName', 'middleName', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('lastName', 'lastName', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('patientID', 'patientID', 'trim|required|max_length[100]');
        $this->load->library('form_validation');
        if ($this->form_validation->run() == FALSE)
        {
            //validation message
            $message = array(
                'status' => false,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors()
            );

            $this->response($message, REST_Controller::HTTP_NOT_FOUND);

        }else {
            $data = [
                'firstName' => $this->input->post('firstName', TRUE),
                'lastName' => $this->input->post('lastName', TRUE),
                'middleName' => $this->input->post('middleName', TRUE),
                'comment' => $this->input->post('comment', TRUE)
            ];
            
            $id = $this->input->post('patientID', TRUE);
            $this->db->where('patientID', $id);
            $this->db->update('patients', $data);
    

            $return_data = [
                'status' => TRUE,
                'data' => $data,
                'message' => 'Patient user Successfully Updated.'
            ];
            $this->response($return_data);
        }
    }

    public function remove_delete()
    {
        header("Access-Control-Allow-Origin: *");
        $patientID = $this->uri->segment(3);
        //$patientID = $this->delete('id');

        $this->db->where('patientID', $patientID);
        $this->db->delete($this->table);


        $return_data = [
            'status' => TRUE,
            'data' => $patientID,
            'message' => 'Patient user Successfully Updated.'
        ];
        $this->response($return_data);
        
    }

    public function list_get()
    {
    
        $patientID = $this->uri->segment(3);
       
        $this->db->where('patientID', $patientID);
        $this->db->from($this->table);
        $patients = $this->db->get()->result();


        $this->response([
                'status' => true,
                'data' => $patients,
            ]);
    
       
    }

    public function all_get()
    {
    
        $patientID = $this->uri->segment(3);
       
        
        $this->db->from($this->table);
        $patients = $this->db->get()->result();


        $this->response([
                'status' => true,
                'data' => $patients,
            ]);
    
       
    }

    public function upload_image_post()
    {
       
        $config['upload_path'] = 'assets/img/uploads';
		$config['allowed_types'] = 'gif|jpg|png';
		//$config['max_width'] = "1024";
		//$config['max_height'] = '786';
		
		$this->load->library('upload',$config);
		
		if( !$this->upload->do_upload('userFile') ){
            $error = array( 'error' => $this->upload->display_errors() );
            $this->response([
                'status' => false,
                'data' => $error,
            ]);
            
		}
		else{
            $data = array( 'upload_data' => $this->upload->data() );
            $this->response([
                'status' => true,
                'data' => $data['upload_data']['full_path'],
            ]);
		}
    }
}