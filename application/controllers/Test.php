<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {



	var $qtyValue;
	var $refNo;

	public function index()
	{
		$this->load->view('header');
		$this->load->view('modules/reports/daily_sales_report/print_daily_sales_report');
		$this->load->view('footer');

	}

	public function update_single_xstockcard($type, $itemID, $ancillaryID, $expiry='')
	{
		$isExpiry = 1; //tag that this transaction came from xstockcard

		$this->db->select('xstockcards.xstockcardID');
		$this->db->select('xstockcards.ancillaryID');
		$this->db->select('xstockcards.itemID');
		$this->db->select('xstockcards.expiry');
		$this->db->select('xstockcards.endBal');
		$this->db->select('xstockcards.price');
		$this->db->select('items.name as itemName');
		$this->db->select('items.description as itemDescription');
		$this->db->select('items.umsr as itemUmsr');
		$this->db->from('xstockcards');	
		$this->db->join('items', 'xstockcards.itemID=items.itemID', 'left');
		$this->db->where('xstockcards.itemID', $itemID);
		$this->db->where('xstockcards.ancillaryID', $ancillaryID);
		$this->db->where('xstockcards.expiry', date('Y-m-d', strtotime($expiry)));
		$item = $this->db->get()->last_row();

		$begBal = 0;
		$debit 	= 0;
		$credit = 0;
		$endBal = 0;

		if (!empty($item)) {

			if ($type == 'DR') {

				$begBal = $item->endBal;
				$debit  = $this->qtyValue;
				$credit = 0;
				$endBal = ($item->endBal + $this->qtyValue);

			} else {

				if ($this->qtyValue > $item->endBal) {
					return 2;
					//Insufficiend Funds;
					
				} else {
					$begBal = $item->endBal;
					$debit  = 0;
					$credit = $this->qtyValue;
					$endBal = ($item->endBal - $this->qtyValue);
				}
			}

			$this->db->set('itemID', $itemID);
			$this->db->set('ancillaryID', $ancillaryID);
			$this->db->set('expiry', date('Y-m-d', strtotime($expiry)));
			$this->db->set('begBal', $begBal);
			$this->db->set('increase', $debit);
			$this->db->set('decrease', $credit);
			$this->db->set('endBal', $endBal);
			$this->db->set('price', trim(($item->price)? $item->price : 0));
			$this->db->set('refNo', $this->refNo);
			$this->db->set('dateInserted', date('Y-m-d H:i:s'));
			$this->db->insert('xstockcards');

			//also update main stockcard
			$this->update_main_stockcard($type, $itemID, $ancillaryID, $isExpiry);

		} else {
			//Initial Balance for new expiry
			// post to xstockcards
			if ($type == 'DR') {

				$this->db->set('itemID', $itemID);
				$this->db->set('ancillaryID', $ancillaryID);
				$this->db->set('expiry', date('Y-m-d', strtotime($expiry)));
				$this->db->set('begBal', 0);
				$this->db->set('increase', $this->qtyValue);
				$this->db->set('decrease', 0);
				$this->db->set('endBal', $this->qtyValue);
				$this->db->set('price', trim(($item->price)? $item->price : 0));
				$this->db->set('refNo', $this->refNo);
				$this->db->set('dateInserted', date('Y-m-d H:i:s'));
				$this->db->insert('xstockcards');

				//also update main stockcard
				$this->update_main_stockcard($type, $itemID, $ancillaryID, $isExpiry);

			} else {

				return 3;
				//insufficient or item not found
			}
		}
	}



	public function update_single_main_stockcard($type, $itemID, $ancillaryID, $isExpiry=0)
	{
		$this->db->select('stockcards.id');
		$this->db->select('stockcards.ancillaryID');
		$this->db->select('stockcards.itemID');
		$this->db->select('stockcards.endBal');
		$this->db->select('items.name as itemName');
		$this->db->select('items.description as itemDescription');
		$this->db->select('items.umsr as itemUmsr');
		$this->db->from('stockcards');	
		$this->db->join('items', 'stockcards.itemID=items.itemID', 'left');
		$this->db->where('stockcards.itemID', $itemID);
		$this->db->where('stockcards.ancillaryID', $ancillaryID);
		$item = $this->db->get()->last_row();

		$begBal = 0;
		$debit 	= 0;
		$credit = 0;
		$endBal = 0;

		if (!empty($item)) {

			if ($type == 'DR') {

				$begBal = $item->endBal;
				$debit  = $this->qtyValue;
				$credit = 0;
				$endBal = ($item->endBal + $this->qtyValue);

			} else {
				//check if nonExpiry ba ni kay if expiry type ni then dili ni mo matter
				//if nonExpiry ni then check nato if naa batay enough items nga withdrawhon
				if ($isExpiry == 0) {
					if ($this->qtyValue > $item->endBal) {

						return 2;
						//insufficient funds
						

					} else {
						$begBal = $item->endBal;
						$debit  = 0;
						$credit = $this->qtyValue;
						$endBal = ($item->endBal - $this->qtyValue);
					}
				} else {
					//if expiry type ni then ayaw na pag check, kay ge check nani ngadto sa xstockcard
					$begBal = $item->endBal;
					$debit  = 0;
					$credit = $this->qtyValue;
					$endBal = ($item->endBal - $this->qtyValue);
				}
				
			}

			$this->db->set('itemID', $itemID);
			$this->db->set('ancillaryID', $ancillaryID);
			$this->db->set('begBal', $begBal);
			$this->db->set('increase', $debit);
			$this->db->set('decrease', $credit);
			$this->db->set('endBal', $endBal);
			$this->db->set('refNo', $this->refNo);
			$this->db->set('dateInserted', date('Y-m-d H:i:s'));
			if ($isExpiry) {
				$this->db->set('withxstockcard', 'Yes');
			} else {
				$this->db->set('withxstockcard', 'No');
			}
			$this->db->insert('stockcards');

		} else {
			//Initial Balance for item
			// post to stockcards
			if ($type == 'DR') {

				$this->db->set('itemID', $itemID);
				$this->db->set('ancillaryID', $ancillaryID);
				$this->db->set('begBal', 0);
				$this->db->set('increase', $this->qtyValue);
				$this->db->set('decrease', 0);
				$this->db->set('endBal', $this->qtyValue);
				$this->db->set('refNo', $this->refNo);
				$this->db->set('dateInserted', date('Y-m-d H:i:s'));
				if ($isExpiry) {
					$this->db->set('withxstockcard', 'Yes');
				} else {
					$this->db->set('withxstockcard', 'No');
				}
				$this->db->insert('stockcards');

			} else {
				//This is credit
				//return invalid request since this is a new item/expiry
				return 3;
				//insufficient or item not found
				
				
			}
		}

		return 1;
	}

	public function index6()
	{
		// $pin = $this->generatePIN();
		// echo $pin;

		// $this->db->select('podetails.id');
  //           $this->db->select('podetails.delQty');
  //           $this->db->select('podetails.qty');
  //           $this->db->from('podetails');
  //           $this->db->where('podetails.poID', 11);
  //           $this->db->where('podetails.status >', 0);
  //           $records = $this->db->get()->result();

  //           var_dump($records);
  //           // if (!empty($records)) {
  //           //     foreach ($records as $row) {
  //           //         $this->db->set('podetails.delQty', 'delQty+'.$row->qty, false);
  //           //         $this->db->where('podetails.id', $row->id);
  //           //         $this->db->update('podetails');
  //           //     }
  //           // }



// $this->db->select('iardetails.qty');
//             $this->db->select('iardetails.itemID');
//             $this->db->select('iarheaders.poID');
//             $this->db->from('iardetails');
//             $this->db->join('iarheaders', 'iardetails.iarID=iarheaders.iarID', 'left');
//             $this->db->join('poheaders', 'iarheaders.poID=poheaders.poID', 'left');
//             $this->db->where('poheaders.poID', 12);
//             $this->db->where('iarheaders.iarID', 48);
//             // $this->db->where('poheaders.status >', 0);
//             $records = $this->db->get()->result();

//             var_dump($records);


    	// $expiryDate = date('Y-m-d');
    	// $this->table = 'xstockcards';
     //  	$this->db->select($this->table.'.itemID');
     //    $this->db->select($this->table.'.endBal');
     //    $this->db->select($this->table.'.expiry');
     //    $this->db->select($this->table.'.ancillaryID');
     //    $this->db->select($this->table.'.status');
     //    $this->db->select('items.name');
     //    $this->db->select('items.description');
     //    $this->db->select('items.catID');
     //    $this->db->select('ancillaries.officeName');
     //    $this->db->select('ancillaries.division');
     //    $this->db->select('category.category');

     //    // from
     //    $this->db->from($this->table);
        
     //    // join     
     //    $this->db->join('items', 'xstockcards.itemID=items.itemID', 'left');
     //    $this->db->join('category', 'items.catID=category.catID', 'left');
     //    $this->db->join('ancillaries', 'xstockcards.ancillaryID=ancillaries.ancillaryID', 'left');

        
     //    // where
     //    if (!$this->session->userdata('current_user')->isAdmin) {
     //        $ancillaryID = $this->session->userdata('current_user')->ancillaryID;
     //        $this->db->where('xstockcards.ancillaryID', $ancillaryID);
     //    }
     //    $this->db->where($this->table.'.status !=', -100);
     //    $this->db->where('items.status !=', -100);
     //    $this->db->where('xstockcards.expiry <=', date('Y-m-d'));
     //    $this->db->group_by('xstockcards.itemID');
     //    $this->db->group_by('xstockcards.expiry');
     //    $arr = $this->db->get()->result();

     //    $expiredItems = count($arr);

     //    foreach ($arr as $row) {
            

     //        $this->db->select($this->table.'.itemID');
     //        $this->db->select($this->table.'.endBal');
     //        $this->db->select($this->table.'.expiry');
     //        $this->db->select($this->table.'.ancillaryID');
     //        $this->db->select($this->table.'.status');
     //        $this->db->select('items.name');
     //        $this->db->select('items.description');
     //        $this->db->select('items.catID');
     //        $this->db->select('ancillaries.officeName');
     //        $this->db->select('ancillaries.division');
     //        $this->db->select('category.category');

     //        $this->db->from($this->table);

     //        $this->db->join('items', 'xstockcards.itemID=items.itemID', 'left');
     //        $this->db->join('category', 'items.catID=category.catID', 'left');
     //        $this->db->join('ancillaries', 'xstockcards.ancillaryID=ancillaries.ancillaryID', 'left');
     //        $this->db->where('xstockcards.expiry <=', date('Y-m-d'));
     //        $this->db->where('xstockcards.ancillaryID', $row->ancillaryID);
     //        $this->db->where('xstockcards.itemID', $row->itemID);
     //        $this->db->order_by('xstockcards.xstockcardID', 'desc');
     //        $arr2 = $this->db->get()->row();
     //        // var_dump($arr2);
     //        // $expiredItems++;

     //    }

     //    echo $expiredItems;



		// $ancillaryID = $this->session->userdata('current_user')->ancillaryID;

  //   	$this->table = 'inventory';
  //   	$this->db->select($this->table.'.reorderLvl');
  //       $this->db->select($this->table.'.inventoryID');
  //       $this->db->select($this->table.'.ancillaryID');
  //       $this->db->select($this->table.'.itemID');
  //       $this->db->select($this->table.'.qty');
  //       $this->db->select($this->table.'.status');
  //       $this->db->select('items.name');
  //       $this->db->select('items.itemCode');
  //       $this->db->select('items.umsr');
  //       $this->db->select('brands.brand');
   
  //       // from
  //       $this->db->from($this->table);

  //       // join   
  //       $this->db->join('items',$this->table.'.itemID=items.itemID','left');
  //       $this->db->join('brands','items.brandID=brands.brandID','left');
        
  //       // where

  //       $this->db->where($this->table.'.qty <='.$this->table.'.reorderLvl');
  //       // $this->db->where('items.catID',$categoryID);
  //       $this->db->where($this->table.'.ancillaryID',$ancillaryID);   

  //       $this->db->where($this->table.'.status != -100');
   
  //       $records = $this->db->get()->result();

  //       var_dump($records);



















		// $this->db->set('podetails.delQty', 'delQty+'.'10', false);
		// $this->db->where('podetails.poID', 7);
		// $this->db->update('podetails');

		// $this->db->select('podetails.id');
		// $this->db->select('podetails.delQty');
		// $this->db->select('podetails.qty');
		// $this->db->from('podetails');
		// $this->db->where('podetails.poID', 7);
		// $this->db->where('podetails.status >', 0);
		// $records = $this->db->get()->result();

		// if (!empty($records)) {
		// 	foreach ($records as $row) {
		// 		if ($row->delQty >= $row->qty) {
		// 			echo 1;
		// 			// $this->db->set('status', 2);
		// 			// $this->db->update('podetails');
		// 		}
		// 	}
		// }


		// $this->db->select('podetails.id');
		// $this->db->from('podetails');
		// $this->db->where('podetails.poID', 7);
		// $this->db->where('podetails.status', 1);
		// $count = $this->db->count_all_results();

		// if ($count == 0) {
  //                   //Closed if all are fully delivered
		// 	// $this->db->set('status', 4);
		// 	// $this->db->where('poID', trim($this->input->post('poID')));
		// 	// $this->db->update('poheaders');
		// } else {
  //                   //Partial if naa pay na bilin nga dili completed
		// 	// $this->db->set('status', 3);
		// 	// $this->db->where('poID', trim($this->input->post('poID')));
		// 	// $this->db->update('poheaders');
		// }

		
	}
	public function index5()
	{
		$this->db->select('xstockcards.xstockcardID');
		$this->db->select('xstockcards.ancillaryID');
		$this->db->select('xstockcards.itemID');
		$this->db->select('xstockcards.expiry');
		$this->db->select('xstockcards.endBal');
		$this->db->select('items.name as itemName');
		$this->db->select('items.description as itemDescription');
		$this->db->select('items.umsr as itemUmsr');
		$this->db->from('xstockcards');	
		$this->db->join('items', 'xstockcards.itemID=items.itemID', 'left');	
		$this->db->where('xstockcards.itemID', 2);
		$this->db->where('xstockcards.ancillaryID', 1);
		$this->db->where('expiry !=', '0000-00-00');
		$this->db->group_by('expiry');
		$items = $this->db->get()->result();
		// var_dump($items);

		if (!empty($items)) {
			$arr = array();
			foreach ($items as $item) {
				$this->db->select('xstockcards.xstockcardID');
				$this->db->select('xstockcards.ancillaryID');
				$this->db->select('xstockcards.itemID');
				$this->db->select('xstockcards.expiry');
				$this->db->select('xstockcards.endBal');
				$this->db->select('items.name as itemName');
				$this->db->select('items.description as itemDescription');
				$this->db->select('items.umsr as itemUmsr');
				$this->db->from('xstockcards');	
				$this->db->join('items', 'xstockcards.itemID=items.itemID', 'left');	
				$this->db->where('xstockcards.itemID', $item->itemID);
				$this->db->where('xstockcards.ancillaryID', $item->ancillaryID);
				$this->db->where('expiry', $item->expiry);
				$this->db->limit(1);
				$this->db->order_by('xstockcardID','desc');
				$a = $this->db->get()->row();
				array_push($arr, $a);
			}

			var_dump($arr);
		} else {
			//get main stockcards
		}

	}

	public function index3()
	{
		$data = array();
		$data['pdf_paging'] = TRUE;						
		$data['title']      = "STOCK CARD";
		$data['footer_left']      = "APRIL 12,2016_REV.0";
		$data['footer_right']      = "DJRMH-HPS-MM-F-015";
		// $data['modulename'] = "NOTICE TO CREDIT";
		// $data['subnote'] = "AAA";
		// $data['subnote2']   = $payroll->startDate.' - '.$payroll->endDate;
		// $data['subnote3'] = $payroll->payrollPeriod;

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

		$html 	= $this->load->view('modules/test/test_view', $data, TRUE);
		$this->mpdf->WriteHTML($html);

		$this->mpdf->Output("test_print.pdf","I");


	}

	public function index2()
	{
		


		// //QR Code function
		// https://github.com/dwisetiyadi/CodeIgniter-PHP-QR-Code
		$this->load->library('ciqrcode');

		header("Content-Type: image/png");
		
		$params['data'] = "Hello World";
		$params['data2'] = "Hello World2";

		
		$this->ciqrcode->generate($params);


		// //Bar Code function
		// //I'm just using rand() function for data example
		// $temp = rand(10000, 99999);
		// $this->set_barcode($temp);
	}
	
	private function set_barcode($code)
	{
		//load library
		$this->load->library('ci_zend');
		//load in folder Zend
		$this->ci_zend->load('Zend/Barcode');
		//generate barcode
		Zend_Barcode::render('code128', 'image', array('text'=>$code), array());
	}

	private function generatePIN($digits = 4)
	{
	    $i = 0; //counter
	    $pin = ""; //our default pin is blank.

	    while($i < $digits){
	        //generate a random number between 0 and 9.
	    	$pin .= mt_rand(0, 9);
	    	$i++;
	    }

	    $this->db->where('pin', $pin);
	    $res = $this->db->count_all_results('users');
	    if ($res > 0) {
	    	$this->generatePIN();
	    } else {
	    	return $pin;
	    }
    	
	}
	
}