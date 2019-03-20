<?php


require APPPATH.'libraries/REST_Controller.php';

class Owner extends REST_Controller
{
    protected $table = "owners";
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

   
    /**
     *  Get Owner Details
     * @method : GET
     * @url :
     *
     **/

    public function owner_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if(!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            $ownerID = $is_valid_token['data']->id;
            
            $this->db->select($this->table.'.userName');
            $this->db->select($this->table.'.firstName');
            $this->db->select($this->table.'.lastName');
            $this->db->select($this->table.'.middleName');
            $this->db->from($this->table);
            $this->db->where($this->table.'.ownerId', $ownerID);
            $owner = $this->db->get()->result();
            $this->response([
                'status' => true,
                'owner' => $ownerID,
                'data' => $owner
            ]);
        }
        else {
            $this->response(
                [
                    'status' => FALSE,
                    'message' => $is_valid_token['message']
                ],
                REST_Controller::HTTP_NOT_FOUND
            );
        }
       
    }

     /**
     *  Edit Owner Details
     * @method : GET
     * @url :
     *
     **/
    public function ownerupdate_post()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if(!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            $ownerID = $is_valid_token['data']->id;
            
            $data = [
                'userName' => $this->post('userName', true),
                'firstName' => $this->post('firstName', true),
                'lastName' => $this->post('lastName', true),
                'middleName' => $this->post('middleName', true),
            ];

            //$this->form_validation->set_data($data);
            $this->form_validation->set_rules('firstName', 'firstName', 'trim|required');
            $this->form_validation->set_rules('middleName', 'middleName', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('lastName', 'lastName', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('userName', 'userName', 'trim|required|max_length[100]');
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

                $data['dateLastEdit'] = date('Y-m-d h:i:s');
                $this->db->where('ownerID', $ownerID);
                $this->db->update($this->table, $data);
    
                $this->response([
                    'status' => true,
                    'ownerID' => $ownerID,
                    'data' => $data
                ]);
            }
        }
        else {
            $this->response(
                [
                    'status' => FALSE,
                    'message' => $is_valid_token['message']
                ],
                REST_Controller::HTTP_NOT_FOUND
            );
        }
       
    }

    
}