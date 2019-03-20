<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
 * Controller Template ver 0.2 
 * To use just search change start change end comments
 * Add your code inside these tags
 */
class Stockcard extends CI_Controller
{

    var $data;

    var $roles;

    var $table;

    var $pfield;

    var $module;

    var $modules;

    var $logfield;

    var $module_path;

    var $module_label;

    var $controller_page;

    public function __construct()
    {
        parent::__construct();
        //check if you are using $record or $records, just replace all
        $this->load->model('generic_model', 'record');

        //change start
        //parent module, controller, table, pfield, logfield
        $this->_init_setup('Inventory', 'stockcard', 'stockcards', 'id', 'refNo');
        //change end
    }

    private function _init_setup($module='',  $controller='', $table='', $pfield='', $logfield='')
    {
        $this->module = $module;
        $this->data['controller_page'] = $this->controller_page = site_url($controller);
        $this->table = $table;
        $this->pfield = $this->data['pfield'] = $pfield; //defines primary key
        $this->logfield = $logfield; // for record logs
        $this->module_path = 'modules/' . strtolower(str_replace(" ", "_", $this->module)) . '/' . $controller; // defines module path
        $this->module_label = ucfirst(str_replace("_", " ", $controller));
        $this->data['controller_name'] = $controller;
        $this->data['table_name'] = $table;
                                                                                                  
        // check for maintenance period
        if ($this->config_model->getConfig('Maintenance Mode') == '1') {
            header('location: ' . site_url('maintenance_mode'));
        }
        
        // check user session
        if (! $this->session->userdata('current_user')->sessionID) {
            header('location: ' . site_url('login'));
        }
    }

    private function submenu()
    {
        // submenu setup
        require_once ('modules.php');
        
        //load each module metadata
        foreach ($modules as $mod) {
            $this->load->view('modules/' . str_replace(" ", "_", strtolower($mod)) . '/metadata');
        }
        
        $this->data['modules'] = $this->modules;
        $this->data['current_main_module'] = $this->modules[$this->module]['main']; // defines the current main module
        //change start
        $this->data['current_module'] = $this->modules[$this->module]['sub']['Stockcard']; // defines the current sub module
        //change end
                                                                                                  
        // check roles
        $this->check_roles();
        $this->data['roles'] = $this->roles;
    }

    private function check_roles()
    {
        // check roles
        $this->roles['create']  = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Add '.$this->data['current_module']['module_label']);
        $this->roles['view']    = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View '.$this->data['current_module']['module_label']);
        $this->roles['edit']    = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Edit '.$this->data['current_module']['module_label']);
        $this->roles['delete']  = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Delete '.$this->data['current_module']['module_label']);
    }

    private function _in_used($id = 0)
    {
        //change start
        $tables = array(
            'customers' => 'custID'
        );
        //change end
        
        if (! empty($tables)) {
            foreach ($tables as $table => $fld) {
                $this->db->where($fld, $id);
                if ($this->db->count_all_results($table)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function index()
    {
        $this->show();
    }

    public function create()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        // check roles
        if ($this->roles['create']) {

            //change start
            //change end

            // load views
            $this->load->view('header', $data);
            $this->load->view($this->module_path . '/create');
            $this->load->view('footer');
        } else {
            // no access this page
            $data['class'] = "danger";
            $data['msg'] = "Sorry, you don't have access to this page!";
            $data['urlredirect'] = "";
            $this->load->view('header', $data);
            $this->load->view('message');
            $this->load->view('footer');
        }
    }



    public function view($id)
    {
        
        // load submenu
        $this->submenu();
        $data = $this->data;
        $id = $this->encrypter->decode($id);

        // echo json_encode($id);
        // die();

       

        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $day = $this->input->post('day');
        $yearMonth = $year.'-'.$month.'-'.'01';
        $yearMonth2 = $year.'-'.$month.'-'.$day;
        $data['year'] = $year;
        $data['month'] = $month;
        $data['day2'] = $day;

       
        
        if ($this->roles['view']) {
            //change start
            $this->db->select($this->table.'.*');
            $this->db->select('products.name');
            $this->db->select('products.productCode');
            $this->db->select('products.umsr');
            $this->db->select('products.reorderLvl');
            $this->db->select('category.category');
            $this->db->where('stockcards.productID', $id);

            // from
            $this->db->from($this->table);
            
            // join     
            $this->db->join('products', 'products.productID=stockcards.productID', 'left');
            $this->db->join('category', 'products.catID=category.catID', 'left');
            
            $data['rec'] = $this->db->get()->row();

            $data['productID'] = $id;
            
        
            //Get all item stockard by expiry if ang method kay daretso ra nimo tan awon ang stockard inig view sa page
            $this->db->select('stockcards.id');
            $this->db->select('stockcards.begBal');
            $this->db->select('stockcards.increase');
            $this->db->select('stockcards.decrease');
            $this->db->select('stockcards.endBal');
            $this->db->select('stockcards.refNo');
            $this->db->select('stockcards.dateInserted');
            $this->db->select('products.name');
            $this->db->select('products.productCode');
            $this->db->select('category.category');
            $this->db->from('stockcards'); 
            $this->db->join('products', 'products.productID=stockcards.productID', 'left');
            $this->db->join('category', 'products.catID=category.catID', 'left');   
            $this->db->where('stockcards.productID', $id);

           
            if ($month && $year && $day == '00') {
               
                $this->db->like('stockcards.dateInserted', date('Y-m', strtotime($yearMonth)));
            } elseif ($month && $year && $day) {
               
                $this->db->like('stockcards.dateInserted', date('Y-m-d', strtotime($yearMonth2)));
            }
            
            $data['records'] = $this->db->get()->result();

        
            // load views
            $this->load->view('header', $data);
            $this->load->view($this->module_path . '/view');
            $this->load->view('footer');
        } else {
            // no access this page
            $data['class'] = "danger";
            $data['msg'] = "Sorry, you don't have access to this page!";
            $data['urlredirect'] = "";
            $this->load->view('header', $data);
            $this->load->view('message');
            $this->load->view('footer');
        }
    }

    public function view_popup($id, $ancillaryID)
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        echo $id = $this->encrypter->decode($id);
        echo $ancillaryID = $this->encrypter->decode($ancillaryID);

        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $yearMonth = $year.'-'.$month.'-'.'01';
        $data['year'] = $year;
        $data['month'] = $month;
        
        if ($this->roles['view']) {
            //change start
            
            $this->db->select('items.name as itemName');
            $this->db->select('items.itemCode');
            $this->db->select('items.description as itemDescription');
            $this->db->select('items.umsr as itemUmsr');
            $this->db->select('items.aveprice');
            $this->db->select('items.lastcost');
            $this->db->select('items.reorderLvl');
            $this->db->select('stockcards.itemID');
            $this->db->select('stockcards.endBal');
            $this->db->select('category.category');
            $this->db->select('category.description as categoryDescription');
            $this->db->select('brands.brand');
            $this->db->select('brands.description as brandDescription');
            $this->db->select('inventory.qty as inventoryQty');
            $this->db->from('items');
            //sa view mag gamit lang ta sa items para ma pull nato tanan iyang xstockcard
            //sa list atong e list tanang xstockcard e group by ancillaryID, itemID and expiry  
            $this->db->join('stockcards', 'items.itemID=stockcards.itemID', 'left');
            $this->db->join('category', 'items.catID=category.catID', 'left');
            $this->db->join('brands', 'items.brandID=brands.brandID', 'left');
            $this->db->join('inventory', 'stockcards.itemID=inventory.itemID AND stockcards.ancillaryID=inventory.ancillaryID', 'left');
            $this->db->where('stockcards.itemID', $id);
            $this->db->where('stockcards.ancillaryID', $ancillaryID);
            
            
            $data['rec'] = $this->db->get()->row();
            $data['ancillaryID'] = $ancillaryID;
            $data['itemID'] = $id;
            var_dump($data['rec']);


            //Get all item stockard by expiry if ang method kay daretso ra nimo tan awon ang stockard inig view sa page
            $this->db->select('stockcards.id');
            $this->db->select('stockcards.ancillaryID');
            $this->db->select('ancillaries.officeName');
            $this->db->select('ancillaries.division');
            $this->db->select('stockcards.itemID');
            
            $this->db->select('stockcards.begBal');
            $this->db->select('stockcards.increase');
            $this->db->select('stockcards.decrease');
            $this->db->select('stockcards.endBal');
            $this->db->select('stockcards.refNo');
            $this->db->select('stockcards.dateInserted');
            $this->db->select('items.name as itemName');
            $this->db->select('items.itemCode');
            $this->db->select('items.description as itemDescription');
            $this->db->select('items.umsr as itemUmsr');
            $this->db->from('stockcards'); 
            $this->db->join('items', 'stockcards.itemID=items.itemID', 'left');    
            $this->db->join('ancillaries', 'stockcards.ancillaryID=ancillaries.ancillaryID', 'left');     
            $this->db->where('stockcards.itemID', $id);
            $this->db->where('stockcards.ancillaryID', $ancillaryID);
            if ($month && $year) {
                $this->db->like('stockcards.dateInserted', date('Y-m', strtotime($yearMonth)));
            }
            
            // $this->db->group_by('expiry');
            $data['records'] = $this->db->get()->result();
            
            $data['officeName'] = $data['records'][0]->officeName;
            $data['division'] = $data['records'][0]->division;
            
            
            
            // load views
            $this->load->view('header_popup', $data);
            $this->load->view($this->module_path . '/view');
            $this->load->view('footer_popup');
        } else {
            // no access this page
            $data['class'] = "danger";
            $data['msg'] = "Sorry, you don't have access to this page!";
            $data['urlredirect'] = "";
            $this->load->view('header_popup', $data);
            $this->load->view('message');
            $this->load->view('footer_popup');
        }
    }

    public function show()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;

        //=========================
        //change start
        $condition_fields = array(
            array('variable'=>'productID', 'field'=>$this->table.'.productID', 'default_value'=>'', 'operator'=>'where'),
            array('variable'=>'name', 'field'=>'products.name', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'catID', 'field'=>'category.catID', 'default_value'=>'', 'operator'=>'where'),
            array('variable'=>'productCode', 'field'=>'products.productCode', 'default_value'=>'', 'operator'=>'where'),
            
            array('variable'=>'endBal', 'field'=>$this->table.'.endBal', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
        );

    

    
        
        // sorting fields
        $sorting_fields = array('id'=>'desc');
        //change end
        //=========================


        
        $controller = $this->uri->segment(1);
        //don't use segment(4) since you are not using pageType
        if ($this->uri->segment(3))
            $offset = $this->uri->segment(3);
        else
            $offset = 0;
        
        // source of filtering
        $filter_source = 0; // default/blank
        if ($this->input->post('filterflag') || $this->input->post('sortby')) {
            $filter_source = 1;
        } else {
            foreach ($condition_fields as $key) {
                if ($this->input->post($key['variable'])) {
                    $filter_source = 1; // form filters
                    break;
                }
            }
        }
        
        if (! $filter_source) {
            foreach ($condition_fields as $key) {
                if ($this->session->userdata($controller . '_' . $key['variable']) || $this->session->userdata($controller . '_sortby') || $this->session->userdata($controller . '_sortorder')) {
                    $filter_source = 2; // session
                    break;
                }
            }
        }
        
        switch ($filter_source) {
            case 1:
                foreach ($condition_fields as $key) {
                    
                        $$key['variable'] = trim($this->input->post($key['variable']));
                    
                }
                
                $sortby = trim($this->input->post('sortby'));
                $sortorder = trim($this->input->post('sortorder'));
                
                break;
            case 2:
                foreach ($condition_fields as $key) {
                    $$key['variable'] = $this->session->userdata($controller . '_' . $key['variable']);
                }
                
                $sortby = $this->session->userdata($controller . '_sortby');
                $sortorder = $this->session->userdata($controller . '_sortorder');
                break;
            default:
                foreach ($condition_fields as $key) {
                    $$key['variable'] = $key['default_value'];
                }
                $sortby = "";
                $sortorder = "";
        }
        
        if ($this->input->post('limit')) {
            if ($this->input->post('limit') == "All")
                $limit = "";
            else
                $limit = $this->input->post('limit');
        } else if ($this->session->userdata($controller . '_limit')) {
            $limit = $this->session->userdata($controller . '_limit');
        } else {
            $limit = 25; // default limit
        }
        
        // set session variables
        foreach ($condition_fields as $key) {
            $this->session->set_userdata($controller . '_' . $key['variable'], $$key['variable']);
        }

        //change start
        // $this->session->set_userdata($controller.'_datePosted', $datePosted);
        //change end
        $this->session->set_userdata($controller . '_sortby', $sortby);
        $this->session->set_userdata($controller . '_sortorder', $sortorder);
        $this->session->set_userdata($controller . '_limit', $limit);
        
        // assign data variables for views
        foreach ($condition_fields as $key) {
            //use value as variable name
            $data[$key['variable']] = $$key['variable'];
        }




         // First Query for Pagination config
        //===================================
        //change start
        // select
        
        $this->db->select($this->table.'.*');
        
        $this->db->select('products.name');
        $this->db->select('products.productCode');
        $this->db->select('category.category');
        $this->db->select('category.catID');

        // from
        $this->db->from($this->table);
        
        // join     
        $this->db->join('products', 'products.productID=stockcards.productID', 'left');
        $this->db->join('category', 'products.catID=category.catID', 'left');
        $this->db->group_by($this->table.'.productID');


        

       
        //change end
        //===================================



        // set conditions here
        foreach ($condition_fields as $key) {
            $operators = explode('_', $key['operator']);
            $operator = $operators[0];
            // check if the operator is like
            if (count($operators) > 1) {
                // like operator
                if (trim($$key['variable']) != '' && $key['field'])
                    $this->db->$operator($key['field'], $$key['variable'], $operators[1]);
            } else {
                if (trim($$key['variable']) != '' && $key['field'])
                    $this->db->$operator($key['field'], $$key['variable']);
            }
        }
        
        // get
        $data['ttl_rows'] = $config['total_rows'] = $this->db->count_all_results();
        
        // set pagination
        $config['base_url'] = $this->controller_page . '/show/';
        $config['per_page'] = $limit;
        $this->pagination->initialize($config);





        $this->db->select($this->table.'.*');
        
        $this->db->select('products.name');
        $this->db->select('products.productCode');
        $this->db->select('category.category');
        $this->db->select('category.catID');

        // from
        $this->db->from($this->table);
        
        // join     
        $this->db->join('products', 'products.productID=stockcards.productID', 'left');
        $this->db->join('category', 'products.catID=category.catID', 'left');
        $this->db->group_by($this->table.'.productID');


        
        
        
        //change end
        //===================================
        




        // set conditions here
        foreach ($condition_fields as $key) {
            $operators = explode('_', $key['operator']);
            $operator = $operators[0];
            // check if the operator is like
            if (count($operators) > 1) {
                // like operator
                if (trim($$key['variable']) != '' && $key['field'])
                    $this->db->$operator($key['field'], $$key['variable'], $operators[1]);
            } else {
                if (trim($$key['variable']) != '' && $key['field'])
                    $this->db->$operator($key['field'], $$key['variable']);
            }
        }
        
        if ($sortby && $sortorder) {
            $this->db->order_by($sortby, $sortorder);
            
            if (! empty($sorting_fields)) {
                foreach ($sorting_fields as $fld => $s_order) {
                    if ($fld != $sortby) {
                        $this->db->order_by($fld, $s_order);
                    }
                }
            }
        } else {
            $ctr = 1;
            if (! empty($sorting_fields)) {
                foreach ($sorting_fields as $fld => $s_order) {
                    if ($ctr == 1) {
                        $sortby = $fld;
                        $sortorder = $s_order;
                    }
                    $this->db->order_by($fld, $s_order);
                    
                    $ctr ++;
                }
            }
        }
        
        if ($limit) {
            if ($offset) {
                $this->db->limit($limit, $offset);
            } else {
                $this->db->limit($limit);
            }
        }
        
        // assigning variables
        $data['sortby'] = $sortby;
        $data['sortorder'] = $sortorder;
        $data['limit'] = $limit;
        $data['offset'] = $offset;
        
        // get
        $arr = $this->db->get()->result();
       

        $data['records'] = $arr;
        

        // echo json_encode($data['records']);
        // die();

    

        // foreach ($arr as $row) {
            

        //     $this->db->select($this->table.'.itemID');
        //     $this->db->select($this->table.'.endBal');

        //     $this->db->select($this->table.'.ancillaryID');
        //     $this->db->select($this->table.'.status');
        //     $this->db->select('items.name');
        //     $this->db->select('items.description');
        //     $this->db->select('items.catID');
        //     $this->db->select('ancillaries.officeName');
        //     $this->db->select('ancillaries.division');
        //     $this->db->select('category.category');

        // // from
        //     $this->db->from($this->table);

        // // join     
        //     $this->db->join('items', 'stockcards.itemID=items.itemID', 'left');
        //     $this->db->join('category', 'items.catID=category.catID', 'left');
        //     $this->db->join('ancillaries', 'stockcards.ancillaryID=ancillaries.ancillaryID', 'left');
        //     $this->db->where($this->table.'.ancillaryID', $row->ancillaryID);
        //     $this->db->where($this->table.'.itemID', $row->itemID);
        //     $this->db->order_by($this->table.'.id', 'desc');
        //     $arr2 = $this->db->get()->row();
        //     // var_dump($arr2);
        //     $data['records'][] = $arr2;
            

        // }
        
        // load views
        $this->load->view('header', $data);
        $this->load->view($this->module_path . '/list');
        $this->load->view('footer');
    }

    public function printlist()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;

        //=========================
        //change start
        $condition_fields = array(
            array('variable'=>'productID', 'field'=>$this->table.'.productID', 'default_value'=>'', 'operator'=>'where'),
            array('variable'=>'name', 'field'=>'products.name', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'catID', 'field'=>'category.catID', 'default_value'=>'', 'operator'=>'where'),
            array('variable'=>'productCode', 'field'=>'products.productCode', 'default_value'=>'', 'operator'=>'where'),
            
            array('variable'=>'endBal', 'field'=>$this->table.'.endBal', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
        );

    

    
        
        // sorting fields
        $sorting_fields = array('id'=>'desc');
        //change end
        //=========================
        //=========================
        
        $controller = $this->uri->segment(1); // if print per page

        // uncomment if want to print with no offset
        // if ($this->uri->segment(3))
        //  $offset = $this->uri->segment(3);
        // else
        //  $offset = 0;
        
        foreach ($condition_fields as $key) {
            $$key['variable'] = $this->session->userdata($controller . '_' . $key['variable']);
        }
        
        //change start
        $datePosted = $this->session->userdata($controller.'_datePosted');
        //change end
        $limit = $this->session->userdata($controller . '_limit');
        $offset = $this->session->userdata($controller . '_offset');
        $sortby = $this->session->userdata($controller . '_sortby');
        $sortorder = $this->session->userdata($controller . '_sortorder');
        


        //===================================
        //change start
        // select
        $this->db->select($this->table.'.*');
        
        $this->db->select('products.name');
        $this->db->select('products.productCode');
        $this->db->select('category.category');
        $this->db->select('category.catID');

        // from
        $this->db->from($this->table);
        
        // join     
        $this->db->join('products', 'products.productID=stockcards.productID', 'left');
        $this->db->join('category', 'products.catID=category.catID', 'left');
        $this->db->group_by($this->table.'.productID');
        //change end
        //===================================


        
        // set conditions here
        foreach ($condition_fields as $key) {
            $operators = explode('_', $key['operator']);
            $operator = $operators[0];
            // check if the operator is like
            if (count($operators) > 1) {
                // like operator
                if (trim($$key['variable']) != '' && $key['field'])
                    $this->db->$operator($key['field'], $$key['variable'], $operators[1]);
            } else {
                if (trim($$key['variable']) != '' && $key['field'])
                    $this->db->$operator($key['field'], $$key['variable']);
            }
        }
        
        if ($sortby && $sortorder) {
            $this->db->order_by($sortby, $sortorder);
            
            if (! empty($sorting_fields)) {
                foreach ($sorting_fields as $fld => $s_order) {
                    if ($fld != $sortby) {
                        $this->db->order_by($fld, $s_order);
                    }
                }
            }
        } else {
            $ctr = 1;
            if (! empty($sorting_fields)) {
                foreach ($sorting_fields as $fld => $s_order) {
                    if ($ctr == 1) {
                        $sortby = $fld;
                        $sortorder = $s_order;
                    }
                    $this->db->order_by($fld, $s_order);
                    
                    $ctr ++;
                }
            }
        }
        
        if ($limit) {
            if ($offset) {
                $this->db->limit($limit, $offset);
            } else {
                $this->db->limit($limit);
            }
        }
        
        // assigning variables
        $data['sortby'] = $sortby;
        $data['sortorder'] = $sortorder;
        $data['limit'] = $limit;
        $data['offset'] = $offset;
        
        // get
        $data['records'] = $this->db->get()->result();

       

        $data['title'] = $this->module_label." List";
        
        // load views
        $this->load->view('header_print', $data);
        $this->load->view($this->module_path . '/printlist');
        $this->load->view('footer_print');
    }

    public function exportlist()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;

        //=========================
        //change start
         //change start
         $condition_fields = array(
            array('variable'=>'productID', 'field'=>$this->table.'.productID', 'default_value'=>'', 'operator'=>'where'),
            array('variable'=>'name', 'field'=>'products.name', 'default_value'=>'', 'operator'=>'like_both'),
           
            array('variable'=>'productCode', 'field'=>'products.productCode', 'default_value'=>'', 'operator'=>'where'),
            
            array('variable'=>'endBal', 'field'=>$this->table.'.endBal', 'default_value'=>'', 'operator'=>'like_both'),
            array('variable'=>'status', 'field'=>$this->table.'.status', 'default_value'=>'', 'operator'=>'where'),
        );


        // sorting fields
        $sorting_fields = array('id'=>'desc');
        //change end
        //=========================
        
        $controller = $this->uri->segment(1); // if print per page

        // uncomment if want to print with no offset
        // if ($this->uri->segment(3))
        //  $offset = $this->uri->segment(3);
        // else
        //  $offset = 0;
        
        foreach ($condition_fields as $key) {
            $$key['variable'] = $this->session->userdata($controller . '_' . $key['variable']);
        }
        
        //change start
        $datePosted = $this->session->userdata($controller.'_datePosted');
        //change end
        $limit = $this->session->userdata($controller . '_limit');
        $offset = $this->session->userdata($controller . '_offset');
        $sortby = $this->session->userdata($controller . '_sortby');
        $sortorder = $this->session->userdata($controller . '_sortorder');
        

        $this->db->select($this->table.'.*');
        
        $this->db->select('products.name');
        $this->db->select('products.productCode');
        $this->db->select('category.category');
        $this->db->select('category.catID');

        // from
        $this->db->from($this->table);
        
        // join     
        $this->db->join('products', 'products.productID=stockcards.productID', 'left');
        $this->db->join('category', 'products.catID=category.catID', 'left');
        $this->db->group_by($this->table.'.productID');
        //===================================


        
        // set conditions here
        foreach ($condition_fields as $key) {
            $operators = explode('_', $key['operator']);
            $operator = $operators[0];
            // check if the operator is like
            if (count($operators) > 1) {
                // like operator
                if (trim($$key['variable']) != '' && $key['field'])
                    $this->db->$operator($key['field'], $$key['variable'], $operators[1]);
            } else {
                if (trim($$key['variable']) != '' && $key['field'])
                    $this->db->$operator($key['field'], $$key['variable']);
            }
        }
        
        if ($sortby && $sortorder) {
            $this->db->order_by($sortby, $sortorder);
            
            if (! empty($sorting_fields)) {
                foreach ($sorting_fields as $fld => $s_order) {
                    if ($fld != $sortby) {
                        $this->db->order_by($fld, $s_order);
                    }
                }
            }
        } else {
            $ctr = 1;
            if (! empty($sorting_fields)) {
                foreach ($sorting_fields as $fld => $s_order) {
                    if ($ctr == 1) {
                        $sortby = $fld;
                        $sortorder = $s_order;
                    }
                    $this->db->order_by($fld, $s_order);
                    
                    $ctr ++;
                }
            }
        }
        
        if ($limit) {
            if ($offset) {
                $this->db->limit($limit, $offset);
            } else {
                $this->db->limit($limit);
            }
        }
        
        // assigning variables
        $data['sortby'] = $sortby;
        $data['sortorder'] = $sortorder;
        $data['limit'] = $limit;
        $data['offset'] = $offset;
        
        // get
        $records = $this->db->get()->result();
        $title = $this->module_label." List";
        $companyName = $this->config_model->getConfig('Company');
        $address = $this->config_model->getConfig('Address');
        
        // XML Blurb
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
            <Column ss:Index='2' ss:AutoFitWidth=\"1\" ss:Width='120.00'/>
            <Column ss:Index='3' ss:AutoFitWidth=\"1\" ss:Width='120.00'/>
            <Column ss:Index='4' ss:AutoFitWidth=\"1\" ss:Width='120.00'/>
            <Column ss:Index='5' ss:AutoFitWidth=\"1\" ss:Width='80.00'/>
            <Column ss:Index='6' ss:AutoFitWidth=\"1\" ss:Width='80.00'/>
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
        $data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'>" . strtoupper($title) . "</Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='5'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        
        $fields[] = "  ";
        $fields[] = "STOCK NO.";
        $fields[] = "ITEM DESCRIPTION";
        $fields[] = "CATEGORY";
        $fields[] = "ENDING BAL";
        $fields[] = "STATUS";

        
        $data .= "<Row ss:StyleID='s24'>";
        // Field Name Data
        foreach ($fields as $fld) {
            $data .= "<Cell ss:StyleID='s23'><Data ss:Type='String'>$fld</Data></Cell>";
        }
        $data .= "</Row>";
        
        if (count($records)) {
            $ctr = 1;
            foreach ($records as $row) {
                $data .= "<Row>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $ctr . ".</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->productCode . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->name.' '.$row->description . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->category . "</Data></Cell>";
                $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->endBal . "</Data></Cell>";


                if ($row->status == 1) {
                    $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>Active</Data></Cell>";  
                } else {
                    $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>Inactive</Data></Cell>";
                }
                
                $data .= "</Row>";
                
                $ctr ++;
            }
        }
        $data .= "</Table></Worksheet>";
        $data .= "</Workbook>";
        
        // Final XML Blurb
        $filename = $this->data['controller_name'].'_list';
        
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$filename.xls;");
        header("Content-Type: application/ms-excel");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        echo $data;
    }

    // Conditions and fields changes
    // public function check_duplicate()
    // {
    //     //change start
    //     $this->db->where('groupName', trim($this->input->post('groupName')));
    //     //change end
        
    //     if ($this->db->count_all_results($this->table))
    //         echo "1"; // duplicate
    //     else
    //         echo "0";
    // }




    //============================================
    // Module Specific Functions
    //============================================
    private function _generateID()
    {
        $idSeries   = $this->config_model->getConfig('Job Post Series');
        $idLength   = $this->config_model->getConfig('Job Post Series Length');
    
        $id  = str_pad($idSeries, $idLength, "0", STR_PAD_LEFT);
        return "JP".date('y').$id;
    }
    
    public function display_session()
    {               
        echo var_dump($_SESSION);
    }

    public function print_stockcard($id, $ancillaryID)
    {

       
        // load submenu
        $this->submenu();
        $data = $this->data;
        $id = $this->encrypter->decode($id);

    

        if ($this->roles['view']) {
            

           //change start
           $this->db->select($this->table.'.*');
           $this->db->select('products.name');
           $this->db->select('products.productCode');
           $this->db->select('products.umsr');
           $this->db->select('products.reorderLvl');
           $this->db->select('category.category');

           // from
           $this->db->from($this->table);
           
           // join     
           $this->db->join('products', 'products.productID=stockcards.productID', 'left');
           $this->db->join('category', 'products.catID=category.catID', 'left');
           
           $data['rec'] = $this->db->get()->row();


       
           //Get all item stockard by expiry if ang method kay daretso ra nimo tan awon ang stockard inig view sa page
           $this->db->select('stockcards.id');
           $this->db->select('stockcards.begBal');
           $this->db->select('stockcards.increase');
           $this->db->select('stockcards.decrease');
           $this->db->select('stockcards.endBal');
           $this->db->select('stockcards.refNo');
           $this->db->select('stockcards.dateInserted');
           $this->db->select('products.name');
           $this->db->select('products.productCode');
           $this->db->select('category.category');
           $this->db->from('stockcards'); 
           $this->db->join('products', 'products.productID=stockcards.productID', 'left');
           $this->db->join('category', 'products.catID=category.catID', 'left');   
           $this->db->where('stockcards.productID', $id);

          
          
           if ($month && $year) {
               $this->db->like('stockcards.dateInserted', date('Y-m', strtotime($yearMonth)));
           }
           
           // $this->db->group_by('expiry');
           $data['records'] = $this->db->get()->result();

           

            
            $data['pdf_paging'] = TRUE;                     
            $data['title']      = "STOCK CARD";
            $data['footer_left']      = "APRIL 12,2016_REV.0";
            $data['footer_right']      = "DJRMH-HPS-MM-F-015";
            

                // load pdf class
            $this->load->library('mpdf');
                // load pdf class
            $this->mpdf->mpdf('en-GB',array(215.9,330.2),10,'Calibri, sans-serif',10,10,25,10,10,0,'P'); //default
            // $this->mpdf->mpdf('en-GB',array(215.9,330.2),10,'Calibri, sans-serif',20,20,30,20,10,0,'P'); //Left,Right,Body and header margin,?,Top,?
            $this->mpdf->setTitle($data['title']);
            $this->mpdf->SetDisplayMode('fullpage');
            $this->mpdf->shrink_tables_to_fit = 1;
            // $this->mpdf->SetWatermarkImage(base_url().'assets/img/main/logo.png');
            $this->mpdf->watermark_font = 'DejaVuSansCondensed';
            $this->mpdf->watermarkImageAlpha = 0.1;
            $this->mpdf->watermarkImgBehind = TRUE;
            $this->mpdf->showWatermarkImage = TRUE;

            // content
            $header = $this->load->view('print_pdf_header', $data, TRUE);
            $this->mpdf->SetHTMLHeader($header);

            $footer = $this->load->view('print_pdf_footer', $data, TRUE);
            $this->mpdf->SetHTMLFooter($footer);

            $html   = $this->load->view($this->module_path . '/print_stockcard', $data, TRUE);
            $this->mpdf->WriteHTML($html);

            $this->mpdf->Output("STOCKCARD_ITEM_".$data['rec']->itemID.".pdf","I");

        
        } else {
            // no access this page
            $data['class'] = "danger";
            $data['msg'] = "Sorry, you don't have access to this page!";
            $data['urlredirect'] = "";
            $this->load->view('header', $data);
            $this->load->view('message');
            $this->load->view('footer');
        }
    }

    public function migrations()
    {
        
        $oldDatabase = "cloudmd";
        $newDatabase = "cloudmdv2";
        //Total 4339
       // $this->rvs_procedures($oldDatabase, $newDatabase);
        //Total 71580 
        //$this->icd_10_assesment($oldDatabase, $newDatabase);
        //Total 36741
        //$this->companies($oldDatabase, $newDatabase);
        //Total 33
        //$this->vaccines($oldDatabase, $newDatabase);
        //$this->practices($oldDatabase, $newDatabase);
        //$this->vaccine_shot($oldDatabase, $newDatabase);
        //$this->patients($oldDatabase, $newDatabase, 'person');
        //$this->users($oldDatabase, $newDatabase, 'doctor');
        //$this->patients($oldDatabase, $newDatabase, 'person');
        echo "done";
        
    }

    public function patients($count)
    {
        if($count == 1) {
            echo json_encode($count);
            die();
        }
        else {
            echo json_encode(0);
            die();
        }
      
    }

    public function users($oldDatabase, $newDatabase, $table)
    {
        $old_data = $this->db->get($oldDatabase.'.'.$table)->result();
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
            $this->db->insert($newDatabase.'.users', $new_data);
        }
       
    }

    public function migratePatients($oldDatabase, $newDatabase, $table)
    {
        //$old_data = $this->db->limit(3000)->get($oldDatabase.'.'.$table)->result();
        //$old_data = $this->db->limit(100000)->get($oldDatabase.'.'.$table)->result();
        //2nd
        //$old_data = $this->db->limit(100000,100000)->get($oldDatabase.'.'.$table)->result();
        //3rd
        $old_data = $this->db->limit(100000,200000)->get($oldDatabase.'.'.$table)->result();
        //$old_data = $this->db->get($oldDatabase.'.'.$table)->result();
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

    public function vaccine_shot($oldDatabase, $newDatabase)
    {
        $old_data = $this->getData($oldDatabase, 'vaccine_shot');
        foreach($old_data as $data) {
            $new_data =  [
                'name' => $data->name,
                'createdBy' => 1,
                'dateInserted' => date('Y-m-d', strtotime($data->created_at)),
                'dateLastEdit' => date('Y-m-d', strtotime($data->updated_at)),
                'status' => ($data->delete_status == 'A')  ? 1 : 0
            ];

            $this->inssertData($newDatabase, ' vaccine_shots', $new_data);
        }
    }

    public function practices($oldDatabase, $newDatabase)
    {
        $old_data = $this->getData($oldDatabase, 'practice');
        foreach($old_data as $data) {
            $new_data =  [
                'name' => $data->practice_name,
                'createdBy' => 1,
                'dateInserted' => date('Y-m-d'),
                'dateLastEdit' => date('Y-m-d'),
                'status' => 1
            ];

            $this->inssertData($newDatabase, 'practices', $new_data);
        }
    }

    public function vaccines($oldDatabase, $newDatabase)
    {
        $old_data = $this->getData($oldDatabase, 'vaccine_list');
        foreach($old_data as $data) {
            $new_data =  [
                'name' => $data->name,
                'createdBy' => 1,
                'dateInserted' => date('Y-m-d', strtotime($data->created_at)),
                'dateLastEdit' => date('Y-m-d', strtotime($data->updated_at)),
                'status' => ($data->delete_status == 'A')  ? 1 : 0
            ];

            $this->inssertData($newDatabase, 'vaccines', $new_data);
        }
    }

    public function rvs_procedures()
    {
        $old_data = $this->getData($oldDatabase, 'rvs_procedures');
        foreach($old_data as $data) {
            $new_data =  [
                'code' => $data->code,
                'description' => $data->description,
                'caseRate' => $data->caseRate,
                'professionalFee' => $data->professionalFee,
                'healthCareInstitutionFee' => $data->HICFee,
                'createdBy' => 1,
                'dateInserted' => date('Y-m-d'),
                'status' => 1
            ];

            $this->inssertData($newDatabase, 'rvs_procedures', $new_data);
        }
    }

    public function icd_10_assesment()
    {
        $old_data = $this->getData($oldDatabase, 'icd_10_assesment');
        foreach($old_data as $data) {
            $new_data =  [
                'code' => $data->code,
                'description' => $data->description,
                'createdBy' => 1,
                'dateInserted' => date('Y-m-d'),
                'status' => 1
            ];

            $this->inssertData($newDatabase, 'icd10', $new_data);
        }
    }

    public function companies($oldDatabase, $newDatabase)
    {
        $old_data = $this->getData($oldDatabase, 'company');
        foreach($old_data as $data) {
            $new_data =  [
                'name' => $data->name,
                'address' => $data->address,
                'contactNo' => $data->contact_no,
                'createdBy' => 1,
                'dateInserted' => date('Y-m-d'),
                'status' => ($data->delete_status == 'A')  ? 1 : 0
            ];

            $this->inssertData($newDatabase, 'companies', $new_data);
        }
    }


    public function getData($database, $table) 
    {
        return $this->db->get($database.'.'.$table)->result();
    }
    public function inssertData($database, $table, $data)
    {
        $this->db->insert($database.'.'.$table, $data);
    }


}