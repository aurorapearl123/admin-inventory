<?php 
class Frameworkhelper {
	
	/**
	 * 
     *  $display = array('lastName'=>' , ','firstName'=>' ','middleName'=>'');
	 *	echo $this->frameworkhelper->get_json_data($records, 'userID', $display);
	 *
	 * @param unknown_type $records
	 * @param unknown_type $value
	 * @param unknown_type $display
	 */
	public function get_json_data($records, $value, $display)  
	{
		//require 'application/libraries/Services_JSON.php';
		
		$is_multi_display = is_array($display);
		
		$data = array();
		if ($records->num_rows()) {
			$ctr  = 0;
			foreach($records->result() as $row) {
				$data[$ctr]['id'] = $row->$value;
				if ($is_multi_display) {
					$data[$ctr]['display'] = "";
					foreach($display as $key=>$separator) {
						$data[$ctr]['display'] .= $row->$key.$separator;
					} 					
				} else {
					$data[$ctr]['display'] = $row->$display;
				}
				$ctr++;
			}
		}
		
		$res = json_encode($data);
		return $res;
	}
	
	/**
	 *
	 *  $display = array('lastName'=>' , ','firstName'=>' ','middleName'=>'');
	 *	echo $this->frameworkhelper->get_json_data($records, 'userID', $display);
	 *
	 * @param unknown_type $records
	 * @param unknown_type $value
	 * @param unknown_type $display
	 */
	public function get_json_data_encrypt($records, $value, $display)
	{
		//require 'application/libraries/Services_JSON.php';
 		//require 'application/libraries/encrypter.php';
		$encrypt = new Encrypter();
	
		$is_multi_display = is_array($display);
	
		$data = array();
		if ($records->num_rows()) {
			$ctr  = 0;
			foreach($records->result() as $row) {
				$data[$ctr]['id'] = $encrypt->encode($row->$value);
				if ($is_multi_display) {
					$data[$ctr]['display'] = "";
					foreach($display as $key=>$separator) {
						$data[$ctr]['display'] .= $row->$key.$separator;
					}
				} else {
					$data[$ctr]['display'] = $row->$display;
				}
				$ctr++;
			}
		}
	
		$j = new Services_JSON();
		$res = $j->encode($data);
		return $res;
	}
	
	
	public function add_session_item($sessionSet, $new_item)
	{
		// retrieve all data
		$session_items = isset($_SESSION[$sessionSet])? $_SESSION[$sessionSet]:array();
		if (!is_array($session_items)) {
			$session_items = array();
		}
		$ctr = count($session_items);
		
		// insert new data
		$session_items[] = $new_item;
		$_SESSION[$sessionSet] = $session_items;
		
		
		if ($ctr < count($session_items))
			return 1; // successful insert
		else
			return 0; // not successful insert
	}
	
	public function del_session_item($sessionSet, $targetID)
	{
		// retrieve all data
		$session_items = isset($_SESSION[$sessionSet])? $_SESSION[$sessionSet]:array();
		if (!is_array($session_items)) {
			$session_items = array();
		}
		
		$success = 0;
		if (!empty($session_items)) {
			foreach($session_items as $key=>$item) {
				if ($key == $targetID) {
					// found and delete item
					$success = 1;
					unset($session_items[$key]);
				}
			}
		}
		
		$_SESSION[$sessionSet] = $session_items;
		
		return $success;
	}
	
	public function clear_session_item($sessionSet) 
	{
		if (isset($_SESSION[$sessionSet]))	$_SESSION[$sessionSet] = array();
	}
	
	
	/**
	 * 
	 * 	$records = $this->db->get('main_departments');
		$headers = array(
					array('field'=>'deptCode', 'type'=>'string', 'label'=>'Dept Code', 'column'=>'A', 'width'=>20),
					array('field'=>'deptName', 'type'=>'string', 'label'=>'Dept Name', 'column'=>'B', 'width'=>20),
					array('field'=>'deptChairman', 'type'=>'string', 'label'=>'Chairman', 'column'=>'C', 'width'=>20),
					array('field'=>'date', 'type'=>'date', 'label'=>'Date', 'column'=>'D', 'width'=>20),
					);
		
		$filename = "scad";
		
	 * @param unknown_type $records
	 * @param unknown_type $headers
	 * @param unknown_type $filename
	 */
	public function export_recordset_to_excel2007($records, $headers, $filename)
	{
		require ('application/libraries/PHPExcel.php');
		
		$objPHPExcel = new PHPExcel();
					
		// style template
		$styleArray = array(
		    'font' => array(
		        'bold' => true
		    )
		);
					
		// set headers
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet = $objPHPExcel->getActiveSheet();
		if (!empty($headers)) {
			foreach($headers as $h) {
				$sheet->getColumnDimension($h['column'])->setWidth($h['width']);
				$sheet->setCellValue($h['column'].'1', $h['label']);
				$sheet->getStyle($h['column'].'1')->applyFromArray($styleArray);
			}
		}
		
		// set recordset
		$ctr = 2;
		if ($records->num_rows()) {
			foreach($records->result() as $row) {
				if (!empty($headers)) {
					foreach($headers as $h) {
						$field = $h['field'];
						
						// check type
						switch($h['type']) {
							case 'date':
								$dateTimeNow = strtotime($row->$field);
								$sheet->setCellValue($h['column'].$ctr, PHPExcel_Shared_Date::PHPToExcel( $dateTimeNow ));		
								$sheet->getStyle($h['column'].$ctr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
								break;
							case 'time':
								$dateTimeNow = strtotime($row->$field);
								$sheet->setCellValue($h['column'].$ctr, PHPExcel_Shared_Date::PHPToExcel( $dateTimeNow ));		
								$sheet->getStyle($h['column'].$ctr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);
								break;
							case 'datetime':
								$dateTimeNow = strtotime($row->$field);
								$sheet->setCellValue($h['column'].$ctr, PHPExcel_Shared_Date::PHPToExcel( $dateTimeNow ));		
								$sheet->getStyle($h['column'].$ctr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME);
								break;
							default:
								$sheet->setCellValue($h['column'].$ctr, $row->$field);
						}
						
					}
				}
				
				$ctr++;
			}
		}
		
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		
	}
	
	/**
	 * 
	 *  $records = array of records;
		$headers = array(
					array('field'=>'deptCode', 'type'=>'string', 'label'=>'Dept Code', 'column'=>'A', 'width'=>20),
					array('field'=>'deptName', 'type'=>'string', 'label'=>'Dept Name', 'column'=>'B', 'width'=>20),
					array('field'=>'deptChairman', 'type'=>'string', 'label'=>'Chairman', 'column'=>'C', 'width'=>20),
					array('field'=>'date', 'type'=>'date', 'label'=>'Date', 'column'=>'D', 'width'=>20),
					);
		
		$filename = "scad";
		
	 * @param unknown_type $records
	 * @param unknown_type $headers
	 * @param unknown_type $filename
	 */
	public function export_array_to_excel2007($records, $headers, $filename)
	{
		require ('application/libraries/PHPExcel.php');
		
		$objPHPExcel = new PHPExcel();
					
		// style template
		$styleArray = array(
		    'font' => array(
		        'bold' => true
		    )
		);
					
		// set headers
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet = $objPHPExcel->getActiveSheet();
		if (!empty($headers)) {
			foreach($headers as $h) {
				$sheet->getColumnDimension($h['column'])->setWidth($h['width']);
				$sheet->setCellValue($h['column'].'1', $h['label']);
				$sheet->getStyle($h['column'].'1')->applyFromArray($styleArray);
			}
		}
		
		// set recordset
		$ctr = 2;
		if (!empty($records)) {
			foreach($records as $row) {
				if (!empty($headers)) {
					foreach($headers as $h) {
						$field = $h['field'];
						
						// check type
						switch($h['type']) {
							case 'date':
								$dateTimeNow = strtotime($row[$field]);
								$sheet->setCellValue($h['column'].$ctr, PHPExcel_Shared_Date::PHPToExcel( $dateTimeNow ));		
								$sheet->getStyle($h['column'].$ctr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
								break;
							case 'time':
								$dateTimeNow = strtotime($row[$field]);
								$sheet->setCellValue($h['column'].$ctr, PHPExcel_Shared_Date::PHPToExcel( $dateTimeNow ));		
								$sheet->getStyle($h['column'].$ctr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);
								break;
							case 'datetime':
								$dateTimeNow = strtotime($row[$field]);
								$sheet->setCellValue($h['column'].$ctr, PHPExcel_Shared_Date::PHPToExcel( $dateTimeNow ));		
								$sheet->getStyle($h['column'].$ctr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME);
								break;
							default:
								$sheet->setCellValue($h['column'].$ctr, $row[$field]);
						}
						
					}
				}
				
				$ctr++;
			}
		}
		
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}
	
	/**
	 * 
	 * 	$records = $this->db->get('main_departments');
		$headers = array(
					array('field'=>'deptCode', 'type'=>'string', 'label'=>'Dept Code', 'column'=>'A', 'width'=>20),
					array('field'=>'deptName', 'type'=>'string', 'label'=>'Dept Name', 'column'=>'B', 'width'=>20),
					array('field'=>'deptChairman', 'type'=>'string', 'label'=>'Chairman', 'column'=>'C', 'width'=>20),
					array('field'=>'date', 'type'=>'date', 'label'=>'Date', 'column'=>'D', 'width'=>20),
					);
		
		$filename = "scad";
		
	 * @param unknown_type $records
	 * @param unknown_type $headers
	 * @param unknown_type $filename
	 */
	public function export_recordset_to_excel2003($records, $headers, $filename)
	{
		require ('application/libraries/PHPExcel.php');
		
		$objPHPExcel = new PHPExcel();
					
		// style template
		$styleArray = array(
		    'font' => array(
		        'bold' => true
		    )
		);
					
		// set headers
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet = $objPHPExcel->getActiveSheet();
		if (!empty($headers)) {
			foreach($headers as $h) {
				$sheet->getColumnDimension($h['column'])->setWidth($h['width']);
				$sheet->setCellValue($h['column'].'1', $h['label']);
				$sheet->getStyle($h['column'].'1')->applyFromArray($styleArray);
			}
		}
		
		// set recordset
		$ctr = 2;
		if ($records->num_rows()) {
			foreach($records->result() as $row) {
				if (!empty($headers)) {
					foreach($headers as $h) {
						$field = $h['field'];
						
						// check type
						switch($h['type']) {
							case 'date':
								$dateTimeNow = strtotime($row->$field);
								$sheet->setCellValue($h['column'].$ctr, PHPExcel_Shared_Date::PHPToExcel( $dateTimeNow ));		
								$sheet->getStyle($h['column'].$ctr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
								break;
							case 'time':
								$dateTimeNow = strtotime($row->$field);
								$sheet->setCellValue($h['column'].$ctr, PHPExcel_Shared_Date::PHPToExcel( $dateTimeNow ));		
								$sheet->getStyle($h['column'].$ctr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);
								break;
							case 'datetime':
								$dateTimeNow = strtotime($row->$field);
								$sheet->setCellValue($h['column'].$ctr, PHPExcel_Shared_Date::PHPToExcel( $dateTimeNow ));		
								$sheet->getStyle($h['column'].$ctr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME);
								break;
							default:
								$sheet->setCellValue($h['column'].$ctr, $row->$field);
						}
						
					}
				}
				
				$ctr++;
			}
		}
		
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		
	}
	
	/**
	 * 
	 *  $records = array of records;
		$headers = array(
					array('field'=>'deptCode', 'type'=>'string', 'label'=>'Dept Code', 'column'=>'A', 'width'=>20),
					array('field'=>'deptName', 'type'=>'string', 'label'=>'Dept Name', 'column'=>'B', 'width'=>20),
					array('field'=>'deptChairman', 'type'=>'string', 'label'=>'Chairman', 'column'=>'C', 'width'=>20),
					array('field'=>'date', 'type'=>'date', 'label'=>'Date', 'column'=>'D', 'width'=>20),
					);
		
		$filename = "scad";
		
	 * @param unknown_type $records
	 * @param unknown_type $headers
	 * @param unknown_type $filename
	 */
	public function export_array_to_excel2003($records, $headers, $filename)
	{
		require ('application/libraries/PHPExcel.php');
		
		$objPHPExcel = new PHPExcel();
					
		// style template
		$styleArray = array(
		    'font' => array(
		        'bold' => true
		    )
		);
					
		// set headers
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet = $objPHPExcel->getActiveSheet();
		if (!empty($headers)) {
			foreach($headers as $h) {
				$sheet->getColumnDimension($h['column'])->setWidth($h['width']);
				$sheet->setCellValue($h['column'].'1', $h['label']);
				$sheet->getStyle($h['column'].'1')->applyFromArray($styleArray);
			}
		}
		
		// set recordset
		$ctr = 2;
		if (!empty($records)) {
			foreach($records as $row) {
				if (!empty($headers)) {
					foreach($headers as $h) {
						$field = $h['field'];
						
						// check type
						switch($h['type']) {
							case 'date':
								$dateTimeNow = strtotime($row[$field]);
								$sheet->setCellValue($h['column'].$ctr, PHPExcel_Shared_Date::PHPToExcel( $dateTimeNow ));		
								$sheet->getStyle($h['column'].$ctr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
								break;
							case 'time':
								$dateTimeNow = strtotime($row[$field]);
								$sheet->setCellValue($h['column'].$ctr, PHPExcel_Shared_Date::PHPToExcel( $dateTimeNow ));		
								$sheet->getStyle($h['column'].$ctr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);
								break;
							case 'datetime':
								$dateTimeNow = strtotime($row[$field]);
								$sheet->setCellValue($h['column'].$ctr, PHPExcel_Shared_Date::PHPToExcel( $dateTimeNow ));		
								$sheet->getStyle($h['column'].$ctr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME);
								break;
							default:
								$sheet->setCellValue($h['column'].$ctr, $row[$field]);
						}
						
					}
				}
				
				$ctr++;
			}
		}
		
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
	
	public function increment_series($name)
	{
		$CI =& get_instance();
		
		$query = "UPDATE `config` SET `value` = `value`+ 1 WHERE `name` = '".$name."'";
		$CI->db->query($query);
	}
	
	function delete_folder($path)
	{
		if (is_dir($path) === true)
		{
			$files = array_diff(scandir($path), array('.', '..'));
	
			foreach ($files as $file)
			{
				delete_folder(realpath($path) . '/' . $file);
			}
	
			return rmdir($path);
		}
	
		else if (is_file($path) === true)
		{
			return unlink($path);
		}
	
		return false;
	}
	
	
	public function setDays($onMon=0, $onTue=0, $onWed=0, $onThu=0, $onFri=0, $onSat=0, $onSun=0)
	{
	    $days = "";
	    if ($onMon){
	        $days = "M";
	    }
	    if ($onTue){
	        if ($onThu) {
	            $days .= "T";
	        } else {
	            $days .= "Tue";
	        }
	    }
	    if ($onWed){
	        $days .= "W";
	    }
	    if ($onThu){
	        if ($onTue) {
	            $days .= "Th";
	        } else {
	            $days .= "Thu";
	        }
	    }
	    if ($onFri){
	        $days .= "F";
	    }
	    if ($onSat){
	        if ($onSun) {
	            $days .= "S";
	        } else {
	            $days .= "Sat";
	        }
	    }
	    if ($onSun){
	        if ($onSat) {
	            $days .= "S";
	        } else {
	            $days .= "Sun";
	        }
	    }
	    
	    return $days;
	}
}
?>