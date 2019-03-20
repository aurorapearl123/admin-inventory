<?php


require APPPATH.'libraries/REST_Controller.php';

class Deposit extends REST_Controller
{
    protected $table = "bank_accounts";
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

   
    /**
     *  Get sales
     * @method : GET
     * @url :
     *
     **/

    public function deposits_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if(!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            $ownerID = $is_valid_token['data']->id;
            $date = $this->uri->segment(3);
            if($date) {
                
                if(strlen($date) == 10) {
                    $this->db->select($this->table.'.bankAccountID');
                    $this->db->select($this->table.'.bankID');
                    $this->db->select($this->table.'.accountName');
                    $this->db->select($this->table.'.accountType');
                    $this->db->select($this->table.'.accountNo');
                    $this->db->select($this->table.'.bankAccountType');
                    $this->db->select($this->table.'.email');
                    $this->db->select('bank_deposits.depositDate');
                    $this->db->select('bank_deposits.amount');
                    $this->db->from($this->table);
                    $this->db->join('bank_deposits',$this->table.'.bankAccountID=bank_deposits.bankAccountID', 'left');
                    $this->db->where($this->table.'.ownerID', $ownerID);
                    $this->db->like('bank_deposits.depositDate', date('Y-m-d', strtotime($date)));
                    $deposits = $this->db->get()->result();
                    $this->response([
                        'status' => true,
                        'data' => $deposits,
                        'ownerID' => $ownerID,
                        'date' => $date,
                        'strlength' => strlen($date)
                    ]);
                }
                else {

                    $this->db->select($this->table.'.bankAccountID');
                    $this->db->select($this->table.'.bankID');
                    $this->db->select($this->table.'.accountName');
                    $this->db->select($this->table.'.accountType');
                    $this->db->select($this->table.'.accountNo');
                    $this->db->select($this->table.'.bankAccountType');
                    $this->db->select($this->table.'.email');
                    $this->db->select('bank_deposits.depositDate');
                    $this->db->select('bank_deposits.amount');
                    $this->db->from($this->table);
                    $this->db->join('bank_deposits',$this->table.'.bankAccountID=bank_deposits.bankAccountID', 'left');
                    $this->db->where($this->table.'.ownerID', $ownerID);
                    $this->db->like('bank_deposits.depositDate', date('Y-m', strtotime($date)));
                    $deposits = $this->db->get()->result();
                    $this->response([
                        'status' => true,
                        'data' => $deposits,
                        'ownerID' => $ownerID,
                        'date' => $date,
                        'strlength' => strlen($date)
                    ]);

                }
              
    
            }
            //empty date
            else  {

                $this->response([
                    'status' => true,
                    'empty' => true,
                    'date' => $date,
                    'date' => "empty string is empty"
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