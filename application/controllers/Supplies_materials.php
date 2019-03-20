<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
 * Controller Template ver 0.2 
 * To use just search change start change end comments
 * Add your code inside these tags
 */
class Supplies_materials extends CI_Controller
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
		$this->_init_setup('Reports', 'supplies_materials', 'risheaders', 'risID', '');
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
        $this->data['current_module'] = $this->modules[$this->module]['sub']['Supplies and Materials Issued']; // defines the current sub module
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
        $this->roles['edit']    = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Edit  '.$this->data['current_module']['module_label']);
        $this->roles['delete']  = $this->userrole_model->has_access($this->session->userdata('current_user')->userID,'Delete  '.$this->data['current_module']['module_label']);
    }

    private function _in_used($id = 0)
    {
        //change start
        $tables = array(
            'users' => 'groupID'
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
        $this->getSuppliesMaterialsIssued();
    }
 
    public function exportlist()
    {
        // load submenu
        $this->submenu();
        $data = $this->data;

        //=========================
        //change start
		$condition_fields = array(
	       // array ('variable' => 'itemID', 'field' => $this->table . '.itemID', 'default_value' => '', 'operator' => 'where' ), 
        //       array ('variable' => 'endBal', 'field' => $this->table . '.endBal', 'default_value' => '', 'operator' => 'where' ), 
        //       array ('variable' => 'dateInserted', 'field' => $this->table . '.dateInserted', 'default_value' => '', 'operator' => 'like_both' )
		);
        
        // sorting fields
        // $sorting_fields = array('poNo'=>'asc','poDate'=>'asc');
        //change end
        //=========================
        
        $controller = $this->uri->segment(1); // if print per page

        // uncomment if want to print with no offset
     	// if ($this->uri->segment(3))
	    // 	$offset = $this->uri->segment(3);
	    // else
	    // 	$offset = 0;
        
        foreach ($condition_fields as $key) {
            $$key['variable'] = $this->session->userdata($controller . '_' . $key['variable']);
        }
        
        //change start
        // $datePosted = $this->session->userdata($controller.'_datePosted');
        //change end
        $limit = $this->session->userdata($controller . '_limit');
        $offset = $this->session->userdata($controller . '_offset');
        $sortby = $this->session->userdata($controller . '_sortby');
        $sortorder = $this->session->userdata($controller . '_sortorder');
        


        //===================================
        //change start
        // select
    	$this->db->select($this->table.'.*');
	 	// $this->db->select('suppliers.suppName');
  

    	// from
    	$this->db->from($this->table);
    	
    	// join   	
	 	// $this->db->join('suppliers',$this->table.'.suppID=suppliers.suppID','left'); 
    	
    	// where
    	// $this->db->where($this->table.'.status != -100');
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
            <Column ss:Index='2' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
            <Column ss:Index='3' ss:AutoFitWidth=\"1\" ss:Width='120.00'/>
            <Column ss:Index='4' ss:AutoFitWidth=\"1\" ss:Width='120.00'/>
            <Column ss:Index='5' ss:AutoFitWidth=\"1\" ss:Width='120.00'/>
            <Column ss:Index='6' ss:AutoFitWidth=\"1\" ss:Width='100.00'/>
            <Column ss:Index='7' ss:AutoFitWidth=\"1\" ss:Width='80.00'/>
                ";
        
        // header
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='14'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s20'>";
        $data .= "<Cell ss:MergeAcross='14'><Data ss:Type='String'>" . $companyName . "</Data></Cell>";
        $data .= "</Row>";
        $data .= "<Row ss:StyleID='s24A'>";
        $data .= "<Cell ss:MergeAcross='14'><Data ss:Type='String'>" . $address . "</Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='14'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='14'><Data ss:Type='String'>" . strtoupper($title) . "</Data></Cell>";
        $data .= "</Row>";
        
        $data .= "<Row ss:StyleID='s24'>";
        $data .= "<Cell ss:MergeAcross='14'><Data ss:Type='String'></Data></Cell>";
        $data .= "</Row>";
        
        $fields[] = "  ";
        $fields[] = "PO NO";
        $fields[] = "PO DATE";
        $fields[] = "SUPPLIER NAME";
        $fields[] = "MODE PROCUREMENT";      
        $fields[] = "DELIVERY PLACE";      
        $fields[] = "DELIVERY DATE";      
        $fields[] = "DELIVERY TERM";      
        $fields[] = "PAYMENT TERM";      
        $fields[] = "GROSS AMOUNT";      
        $fields[] = "DISCOUNT";      
        $fields[] = "TOTAL AMOUNT";      
        $fields[] = "CLUSTER FUND";      
        $fields[] = "REMARKS";      
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
                // $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->poNo . "</Data></Cell>";
                // $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->poDate . "</Data></Cell>";
                // $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->suppName . "</Data></Cell>";
                // $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->modeProcurement . "</Data></Cell>";
                // $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->deliveryPlace . "</Data></Cell>";
                // $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->deliveryDate . "</Data></Cell>";
                // $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->deliveryTerm . "</Data></Cell>";
                // $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->paymentTerm . "</Data></Cell>";
                // $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->grossAmount . "</Data></Cell>";
                // $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->discount . "</Data></Cell>";
                // $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->totalAmount . "</Data></Cell>";
                // $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->clusterFund . "</Data></Cell>";
                // $data .= "<Cell ss:StyleID='s27'><Data ss:Type='String'>" . $row->remarks . "</Data></Cell>";
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

    //============================================
    // Module Specific Functions
    //============================================
     public function print_record($dateYear, $dateMonth, $ancillaryID, $clusterFund, $serialNo)
    {
       
        // load submenu
        $this->submenu();
        $data = $this->data;
        
        //===================================
        //change start    

        if ($ancillaryID == '') {
            $ancillaryID = $this->session->userdata('current_user')->ancillaryID; //mmo
        }
        if($dateYear == '' ){
          $dateYear = date('Y');  
        }
        if($dateMonth == ''){
          $dateMonth = date('m');      
        }
        $datePeriod = $dateYear.'-'. $dateMonth;

        if($clusterFund == 01){ $cluster =  'Regular Agency'; } 
        if($clusterFund == 05){ $cluster = 'Internally Generated Fund'; }
        if($clusterFund == 06){ $cluster = 'Drugs and Meds'; }
               
        $this->db->select($this->table.'.*');
        // from
        $this->db->from($this->table);
                
        // where
        $this->db->where($this->table.'.toAncillaryID',$ancillaryID);
        $this->db->like($this->table.'.risDate ',$datePeriod);
        $this->db->where($this->table.'.status != -100'); // -100 == deleted data
        $this->db->where($this->table.'.status',2);//confirmed status
        $data['records'] = $this->db->get()->result();

        $data['dateMonth']       = $dateMonth; 
        $data['dateYear']        = $dateYear; 
        $data['datePeriod']      = $datePeriod; 
        $data['ancillaryID']     = $ancillaryID; 
        $data['clusterFund']     = $cluster; 
        $data['serialNo']        = $serialNo; 

        //change end
        //===================================
                
        // check roles
        if ($this->roles['view']) {
    
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
    
            $html   = $this->load->view($this->module_path.'/print_record', $data, TRUE);
            $this->mpdf->WriteHTML($html);
            
            //$this->pdf->AutoPrint(false);
            $this->mpdf->Output("SUPPLIES_MATERIALS.pdf","I");
            //$this->Output('filename.pdf', '\Mpdf\Output\Destination::FILE');
        } else {
            // no access this page
            $data['class']  = "danger";
            $data['msg']    = "Sorry, you don't have access to this page!";
            $data['urlredirect']    = "";
            $this->load->view('header', $data);
            $this->load->view('message');
            $this->load->view('footer');
        }
    }

	public function getSuppliesMaterialsIssued()
    {

        $this->submenu();
        $data = $this->data;

        $ancillaryID = trim($this->input->post('ancillaryID'));        
        $dateYear = $this->input->post('dateYear');   
        $dateMonth = $this->input->post('dateMonth'); 

        $clusterFund = trim($this->input->post('clusterFund'));  
     
        $serialNo = trim($this->input->post('serialNo')); 

        if($dateYear == '' ){
          $dateYear = date('Y');  
        }
        if($dateMonth == '') {
          $dateMonth = date('m');      
        }
        $datePeriod = $dateYear.'-'. $dateMonth;

        if (empty($ancillaryID)) {
            $ancillaryID = $this->session->userdata('current_user')->ancillaryID;
        }

     
        
        $this->db->select($this->table.'.*');
        // from
        $this->db->from($this->table);
                
        // where
        $this->db->where($this->table.'.toAncillaryID',$ancillaryID);
        $this->db->like($this->table.'.risDate ',$datePeriod);
        // $this->db->where($this->table.'.risDate <=',$dateTo);
        $this->db->where($this->table.'.status != -100'); // -100 == deleted data
        $this->db->where($this->table.'.status',2);//confirmed status

        $data['records'] = $this->db->get()->result();    

        $data['dateMonth']        = $dateMonth; 
        $data['dateYear']          = $dateYear; 
        $data['datePeriod']          = $datePeriod; 
        $data['ancillaryID']     = $ancillaryID; 
        $data['clusterFund']     = $clusterFund; 
        $data['serialNo']        = $serialNo; 
       
        // load views
        $this->load->view('header', $data);
        $this->load->view($this->module_path . '/list');
        $this->load->view('footer');

    }
	
	public function display_session()
	{				
		echo var_dump($_SESSION);
	}


}