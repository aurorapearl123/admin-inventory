<?php


require APPPATH.'libraries/REST_Controller.php';

class ApiLogin extends REST_Controller
{
    protected $table = "owners";
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

   
    /**
     *  User Login
     * @method : POST
     * @url :
     *
     **/
    public function login_post()
    {
        header("Access-Control-Allow-Origin: *");
        $_POST = $this->security->xss_clean($_POST);

        //validation 
        $this->form_validation->set_rules('pin', 'pin', 'trim|required|max_length[10]');
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

            $pin = $this->input->post('pin', TRUE);
            $user = $this->login($pin);

            if(!$user) {
                $response = [
                    'status' => FALSE,
                    'message' => 'Invalid Pin'
                ];
                $this->response($response, 400);
            }
            else {

                //Load Authorization Token Library
                $this->load->library('Authorization_Token');
                //Generate Token
                $token_data['id'] = $user['ownerID'];
                $token_data['userName'] = $user['userName'];
                $token_data['firstName'] = $user['firstName'];
                $token_data['time'] = time();


                $user_token = $this->authorization_token->generateToken($token_data);

                //$this->load->library('session');

                //$this->session->set_userdata('userId', 'myId');

                $response = [
                    'id' => $user['ownerID'],
                    'userName' => $user['userName'],
                    'firstName' => $user['firstName'],
                    'middleName' => $user['middleName'],
                    'lastName' => $user['lastName'],
                    'token' => $user_token
                ];
                //log
               // $this->login_logs('Login', $user['userID']);

                $return_data = [
                    'status' => TRUE,
                    'data' => $response,
                    'message' => 'Login user Successfully.'
                ];
                $this->response($return_data);
            }
        }

    }

    public function login($pin)
    {
        $this->db->select('owners.*');
        $this->db->where('pin', $pin);
        $this->db->from($this->table);
        $q = $this->db->get();
        

        if($q->num_rows()) {
            $owner_pin = $q->row('pin');
            if($owner_pin == $pin) {

                $data['ownerID'] = $q->row('ownerID');
                $data['userName'] = $q->row('userName');
                $data['firstName'] = $q->row('firstName');
                $data['middleName'] = $q->row('middleName');
                $data['lastName'] = $q->row('lastName');
                return $data;
                

            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

}