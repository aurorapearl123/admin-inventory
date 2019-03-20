<?php

require APPPATH.'libraries/REST_Controller.php';

class Sales extends REST_Controller
{
    protected $table = "collection_headers";
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
    }

   
    /**
     *  Get sales
     * @method : GET
     * @url :
     *
     **/


    // public function sales_options()
    // {
    //     $this->response([
    //         'status' => true,
    //         'date' => $date,
    //         'data' => $collection_data,
    //         'ownerID' => 23
    //     ]);
    //     return $this->set_response(null, 200);
    // }

    public function sales_get()
    {

        header("Access-Control-Allow-Origin: *");
        $date = $this->uri->segment(3);
        if($date) {
            
            if(strlen($date) == 10) {
                $this->db->select($this->table.'.collectionNo');
                $this->db->select($this->table.'.collectionID');
                $this->db->select($this->table.'.collectionDate');
                $this->db->select($this->table.'.orDate');
                $this->db->select($this->table.'.orNo');
                $this->db->select($this->table.'.arID');
                $this->db->select($this->table.'.totalAmount');
                $this->db->select($this->table.'.remarks');
                $this->db->like('collectionDate', date('Y-m-d', strtotime($date)));
                $collection_data = $this->db->get($this->table)->result();
                $this->response([
                    'status' => true,
                    'date' => $date,
                    'data' => $collection_data,
                    'ownerID' => $ownerID
                ]);
            }
            else {
                $this->db->select($this->table.'.collectionNo');
                $this->db->select($this->table.'.collectionID');
                $this->db->select($this->table.'.collectionDate');
                $this->db->select($this->table.'.orDate');
                $this->db->select($this->table.'.orNo');
                $this->db->select($this->table.'.arID');
                $this->db->select($this->table.'.totalAmount');
                $this->db->select($this->table.'.remarks');
                $this->db->like('collectionDate', date('Y-m', strtotime($date)));
                $collection_data = $this->db->get($this->table)->result();
                $this->response([
                    'status' => true,
                    'date' => $date,
                    'data' => $collection_data,
                    'ownerID' => $ownerID
                ]);
            }
        }
            else  {

                $this->response([
                    'status' => true,
                    'empty' => true,
                    'date' => $date,
                    'date' => "empty string is empty"
                ]);
            }
        // $this->load->library('Authorization_Token');
        // $is_valid_token = $this->authorization_token->validateToken();
        // if(!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
        //     $ownerID = $is_valid_token['data']->id;
        //     $date = $this->uri->segment(3);
        //     if($date) {
                
        //         if(strlen($date) == 10) {
        //             $this->db->select($this->table.'.collectionNo');
        //             $this->db->select($this->table.'.collectionID');
        //             $this->db->select($this->table.'.collectionDate');
        //             $this->db->select($this->table.'.orDate');
        //             $this->db->select($this->table.'.orNo');
        //             $this->db->select($this->table.'.arID');
        //             $this->db->select($this->table.'.totalAmount');
        //             $this->db->select($this->table.'.remarks');
        //             $this->db->like('collectionDate', date('Y-m-d', strtotime($date)));
        //             $collection_data = $this->db->get($this->table)->result();
        //             $this->response([
        //                 'status' => true,
        //                 'date' => $date,
        //                 'data' => $collection_data,
        //                 'ownerID' => $ownerID
        //             ]);
        //         }
        //         else {
        //             $this->db->select($this->table.'.collectionNo');
        //             $this->db->select($this->table.'.collectionID');
        //             $this->db->select($this->table.'.collectionDate');
        //             $this->db->select($this->table.'.orDate');
        //             $this->db->select($this->table.'.orNo');
        //             $this->db->select($this->table.'.arID');
        //             $this->db->select($this->table.'.totalAmount');
        //             $this->db->select($this->table.'.remarks');
        //             $this->db->like('collectionDate', date('Y-m', strtotime($date)));
        //             $collection_data = $this->db->get($this->table)->result();
        //             $this->response([
        //                 'status' => true,
        //                 'date' => $date,
        //                 'data' => $collection_data,
        //                 'ownerID' => $ownerID
        //             ]);
        //         }
               
    
        //     }
        //     //empty date
        //     else  {

        //         $this->response([
        //             'status' => true,
        //             'empty' => true,
        //             'date' => $date,
        //             'date' => "empty string is empty"
        //         ]);
        //     }
        // }
        // else {
        //     $this->response(
        //         [
        //             'status' => FALSE,
        //             'message' => $is_valid_token['message']
        //         ],
        //         REST_Controller::HTTP_NOT_FOUND
        //     );
        // }
       
    }

     /**
     *  Get sales
     * @method : GET
     * @url :
     *
     **/

    public function monhtlysales_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if(!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            $ownerID = $is_valid_token['data']->id;
            $date = $this->uri->segment(3);
            if($date) {
                
                if(strlen($date) == 10) {
                    $this->db->select_sum($this->table.'.totalAmount');
                    $this->db->from($this->table);
                    $this->db->like('collectionDate', date('Y-m-d', strtotime($date)));
                    $collection_data = $this->db->get();
                    $this->response([
                            'status' => true,
                            'total' => $collection_data->row()->totalAmount,
                            
                        ]);
                }
                else {

                    $this->db->select_sum($this->table.'.totalAmount');
                    $this->db->from($this->table);
                    $this->db->like('collectionDate', date('Y-m', strtotime($date)));
                    $collection_data = $this->db->get();
                    $this->response([
                            'status' => true,
                            'total' => $collection_data->row()->totalAmount,
                            
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
     *  Get sales
     * @method : GET
     * @url :
     *
     **/

    public function resportsales_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if(!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            $ownerID = $is_valid_token['data']->id;
            $date = $this->uri->segment(3);
            $data = [
                (int) $this->getSalesRepost(date('Y').'-01'),
                (int) $this->getSalesRepost(date('Y').'-02'),
                (int) $this->getSalesRepost(date('Y').'-03'),
                (int) $this->getSalesRepost(date('Y').'-04'),
                (int) $this->getSalesRepost(date('Y').'-05'),
                (int) $this->getSalesRepost(date('Y').'-06'),
                (int) $this->getSalesRepost(date('Y').'-07'),
                (int) $this->getSalesRepost(date('Y').'-08'),
                (int) $this->getSalesRepost(date('Y').'-09'),
                (int) $this->getSalesRepost(date('Y').'-10'),
                (int) $this->getSalesRepost(date('Y').'-11'),
                (int) $this->getSalesRepost(date('Y').'-12'),
            ];

            $this->response([
                'status' => true,
                'data' => $data,
                
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

    public function getSalesRepost($date)
    {
        $this->db->select_sum($this->table.'.totalAmount');
        $this->db->from($this->table);
        $this->db->like('collectionDate', date('Y-m', strtotime($date)));
        $collection_data = $this->db->get();
        if($collection_data->row()->totalAmount == null) {
            return 0;
        }
        else {
            return $collection_data->row()->totalAmount;
        }
        // $this->response([
        //         'status' => true,
        //         'total' => $collection_data->row()->totalAmount,
                
        //     ]);
    }
    
}