<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
 * Controller Template ver 0.1 
 * To use just search change start change end comments
 * Add your code inside these tags
 */
class Reports extends CI_Controller
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
        $this->load->model('generic_model', 'records');

        //change start
        //parent module, controller, table, pfield, logfield
        $this->_init_setup('Reports', 'reports', 'items', 'itemID', 'name');
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
        $this->data['current_module'] = $this->modules[$this->module]['sub']['Reports']; // defines the current sub module
        //change end
                                                                                                  
        // check roles
        $this->check_roles();
        $this->data['roles'] = $this->roles;
    }

    private function check_roles()
    {
        // check roles
        $this->roles['create']  = $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'Add '.$this->data['current_module']['module_label']);
        $this->roles['view']    = $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'View '.$this->data['current_module']['module_label']);
        $this->roles['edit']    = $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'Edit '.$this->data['current_module']['module_label']);
        $this->roles['delete']  = $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'Delete '.$this->data['current_module']['module_label']);
        $this->roles['approve'] = $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'Approve '.$this->data['current_module']['module_label']);
        $this->roles['view_credit_breakdown_report'] 	= $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'View Credit Breakdown Report');
        $this->roles['view_sales_breakdown_report'] 	= $this->userrole_model->has_access($this->session->userdata('current_user')->userID, 'View Sales Breakdown Report');
    }

    public function index()
    {
        $this->daily_sales();
    }

    public function daily_sales()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        // check roles
        if ($this->roles['view']) {

            $this->frameworkhelper->clear_session_item('records');
            
            //change end
            $this->load->view('header', $data);
            $this->load->view($this->module_path . '/daily_sales');
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
    
    public function credit_breakdown_report()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        
        $data['page_title'] = 'Credit Breakdown Report';
        
        // check roles
        if ($this->roles['view']) {
        	
            //change end
            $this->load->view('header', $data);
            $this->load->view($this->module_path . '/credit_breakdown_report');
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
    
    public function sales_breakdown_report()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        
        $data['page_title'] = 'Sales Breakdown Report';
        
        // check roles
        if ($this->roles['view']) {

            //change end
            $this->load->view('header', $data);
            $this->load->view($this->module_path . '/sales_breakdown_report');
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


    public function print_daily_sales($date='')
    {
        // load submenu
        $this->submenu();
        $data = $this->data;
        // check roles
        if ($this->roles['view']) {

            $this->frameworkhelper->clear_session_item('records');
            
            $data['pdf_paging'] = TRUE;
            // $data['title']      = "PURCHASE ORDER";
                
            
            //-------------------------------
            // load pdf class
            $this->load->library('mpdf');            
            // load pdf class
            $this->mpdf->mpdf('en-GB',array(215.9,330.2),10,'Gotham Narrow',10,10,40,10,10,10,'P');
            $this->mpdf->setTitle($data['title']);
            $this->mpdf->SetDisplayMode('fullpage');
            $this->mpdf->shrink_tables_to_fit = 1;
            $this->mpdf->SetWatermarkImage(base_url().'images/logo/watermark.png');
            $this->mpdf->watermark_font = 'DejaVuSansCondensed';
            $this->mpdf->watermarkImageAlpha = 0.1;
            $this->mpdf->watermarkImgBehind = TRUE;
            $this->mpdf->showWatermarkImage = TRUE;
    
            // content
            // $header = $this->load->view('print_pdf_header', $data, TRUE);
            $header = $this->load->view('print_pdf_header', $data, TRUE);
            $this->mpdf->SetHTMLHeader($header);
    
            $footer = $this->load->view('print_pdf_footer', $data, TRUE);
            $this->mpdf->SetHTMLFooter($footer);
            
            $this->mpdf->SetJS('this.print();');
            //Load number to words
            $this->load->library('Numbertowords');
            // echo $this->numbertowords->convert_number(123456789);  die();
           
            // /
    
            $html   = $this->load->view($this->module_path.'/print_daily_sales', $data, TRUE);
            $this->mpdf->WriteHTML($html);
            
            //$this->pdf->AutoPrint(false);
            $this->mpdf->Output("DAILY_SALES_REPORT_".$date.".pdf","I");
            //$this->Output('filename.pdf', '\Mpdf\Output\Destination::FILE');
            
            
            
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







    

}
