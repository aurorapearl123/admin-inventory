<?php


require APPPATH.'libraries/REST_Controller.php';

class Inventory extends REST_Controller
{
    protected $table = "products";
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

   
    /**
     *  Get inventory deisel, premium and unleaded
     * @method : GET
     * @url :
     *
     **/

    public function inventory_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if(!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
                $ownerID = $is_valid_token['data']->id;
              
                //$data = [];
                $this->db->select_sum($this->table.'.qty');
                $this->db->select_sum($this->table.'.avecost');
                $this->db->select('unit_measurements.umsr');
                $this->db->from($this->table);
                $this->db->join('unit_measurements',$this->table.'.umsr=unit_measurements.umsrID', 'left');
                $this->db->where($this->table.'.name', 'DIESEL');
               
                $deposits = $this->db->get();

                $data['DIESEL'] = [
                    'total' => $deposits->row()->qty,
                    'umsr' => $deposits->row()->umsr,
                    'price' => $deposits->row()->avecost,
                ];

                $this->db->select_sum($this->table.'.avecost');
                $this->db->select_sum($this->table.'.qty');
                $this->db->select('unit_measurements.umsr');
                $this->db->from($this->table);
                $this->db->join('unit_measurements',$this->table.'.umsr=unit_measurements.umsrID', 'left');
                $this->db->where($this->table.'.name', 'PREMIUM');
               
                $deposits = $this->db->get();

                $data['PREMIUM'] = [
                    'total' => $deposits->row()->qty,
                    'umsr' => $deposits->row()->umsr,
                    'price' => $deposits->row()->avecost,
                ];

                $this->db->select_sum($this->table.'.avecost');
                $this->db->select_sum($this->table.'.qty');
                $this->db->select('unit_measurements.umsr');
                $this->db->from($this->table);
                $this->db->join('unit_measurements',$this->table.'.umsr=unit_measurements.umsrID', 'left');
                $this->db->where($this->table.'.name', 'UNLEADED');
               
                $deposits = $this->db->get();

                $data['UNLEADED'] = [
                    'total' => $deposits->row()->qty,
                    'umsr' => $deposits->row()->umsr,
                    'price' => $deposits->row()->avecost,
                ];



                $this->response([
                    'status' => true,
                    'data' => $data
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
     *  Get inventory by date
     * @method : GET
     * @url :
     *
     **/

    public function inventorydate_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if(!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            $ownerID = $is_valid_token['data']->id;
            $date = $this->uri->segment(3);
            if($date) {
                
                if(strlen($date) == 10) {
                    $this->db->select_sum($this->table.'.qty');
                    $this->db->select('unit_measurements.umsr');
                    $this->db->from($this->table);
                    $this->db->join('unit_measurements',$this->table.'.umsr=unit_measurements.umsrID', 'left');
                    $this->db->like($this->table.'.dateInserted', date('Y-m-d', strtotime($date)));
                    $deposits = $this->db->get();
                    $this->response([
                        'status' => true,
                        'total' => $deposits->row()->qty,
                        'umsr' => $deposits->row()->umsr
                        
                    ]);
                }
                else {

                    $this->db->select_sum($this->table.'.qty');
                    $this->db->select('unit_measurements.umsr');
                    $this->db->from($this->table);
                    $this->db->join('unit_measurements',$this->table.'.umsr=unit_measurements.umsrID', 'left');
                    $this->db->like($this->table.'.dateInserted', date('Y-m', strtotime($date)));
                    $deposits = $this->db->get();
                    $this->response([
                        'status' => true,
                        'total' => $deposits->row()->qty,
                        'umsr' => $deposits->row()->umsr
                        
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

     /**
     *  Get inventory details by date
     * @method : GET
     * @url :
     *
     **/

    public function inventorydetails_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if(!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            $ownerID = $is_valid_token['data']->id;
            $date = $this->uri->segment(3);
            if($date) {

                $this->db->select($this->table.'.dateInserted');
                $this->db->select($this->table.'.name');
                $this->db->select($this->table.'.qty');
                $this->db->select($this->table.'.avecost');
                $this->db->select('unit_measurements.umsr');
                $this->db->from($this->table);
                $this->db->join('unit_measurements',$this->table.'.umsr=unit_measurements.umsrID', 'left');
                //$this->db->where($this->table.'.name', 'DIESEL');
                $this->db->like($this->table.'.dateInserted', date('Y-m', strtotime($date)));
                
                 $deposits = $this->db->get()->result();

                 $this->response([
                         'status' => true,
                         'data' => $deposits
                     ]);
    
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