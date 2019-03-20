<?php 

class Htmlhelper {
	
	/**
	 * 
	 * $usergroups = $this->db->get('usergroups');
     * echo $this->htmlhelper->select_object('groupID', $usergroups, 'groupID', 'groupName', 200,'','getUsers()');
     *   
	 * @param unknown_type $id
	 * @param unknown_type $data
	 * @param unknown_type $value
	 * @param unknown_type $display
	 * @param unknown_type $width
	 * @param unknown_type $current_value
	 * @param unknown_type $function_call
	 * @param unknown_type $extra
	 */
	public function select_object($id, $data, $value, $display, $width="", $current_value="", $function_call="", $header=false, $extra="") 
	{
		$is_multi_display = is_array($display);
		
		$html = '<select class="form-control" style="height: 32px;" id="'.$id.'" name="'.$id.'" ';
		if ($width)
			$html .= 'style="width: '.$width.'px;" ';
		if ($function_call)
			$html .= 'onchange="'.$function_call.';" ';
		if ($extra)
			$html .= $extra;
		$html .= '>'."\n";
		
		if(is_array($header)) { 
			foreach($header as $val=>$str) {
				$html .= '<option value="'.$val.'">'.$str.'</option>';
			} 
		} else {
			$html .= '<option value="">&nbsp;</option>';
		}
		
		if ($data->num_rows()) {
			foreach($data->result() as $row) {
				if (trim($current_value)==$row->$value) {
					$html .= '<option value="'.$row->$value.'" selected>';
						if ($is_multi_display) {
							foreach($display as $key=>$separator) {
								$html .= $row->$key.$separator;
							} 					
						} else {
							$html .= $row->$display;
						}
					$html .= "</option>\n";
				} else {
					$html .= '<option value="'.$row->$value.'">';
						if ($is_multi_display) {
							foreach($display as $key=>$separator) {
								$html .= $row->$key.$separator;
							} 					
						} else {
							$html .= $row->$display;
						}
					$html .= "</option>\n";
				}
			}
		} 
		$html .= '</select>'."\n";
			
		return $html;
		
	}
	
	public function select_object_encrypt($id, $data, $value, $display, $width="", $current_value="", $function_call="", $header=false, $extra="")
	{
		$encrypt = new Encrypter();
		
		$is_multi_display = is_array($display);
	
		$html = '<select class="form-control" style="height: 32px;" id="'.$id.'" name="'.$id.'" ';
		if ($width)
			$html .= 'style="width: '.$width.'px;" ';
		if ($function_call)
			$html .= 'onchange="'.$function_call.';" ';
		if ($extra)
			$html .= $extra;
		$html .= '>'."\n";
		if(is_array($header)) {
			foreach($header as $val=>$str) {
				$html .= '<option value="'.$val.'">'.$str.'</option>';
			}
		} else {
			$html .= '<option value="">&nbsp;</option>';
		}
	
		if ($data->num_rows()) {
			foreach($data->result() as $row) {
				if (trim($current_value)==$row->$value) {
					$html .= '<option value="'.$encrypt->encode($row->$value).'" selected>';
					if ($is_multi_display) {
						foreach($display as $key=>$separator) {
							$html .= $row->$key.$separator;
						}
					} else {
						$html .= $row->$display;
					}
					$html .= "</option>\n";
				} else {
					$html .= '<option value="'.$encrypt->encode($row->$value).'">';
					if ($is_multi_display) {
						foreach($display as $key=>$separator) {
							$html .= $row->$key.$separator;
						}
					} else {
						$html .= $row->$display;
					}
					$html .= "</option>\n";
				}
			}
		}
		$html .= '</select>'."\n";
			
		return $html;
	
	}
	
	public function select_status_object($id, $values, $width=100, $current_value="") 
	{
		$object = '';
		
		$object .= '<select name="'.$id.'" id="'.$id.'" width="'.$width.'">';
			$object .= '<option value=""></option>';
			
			if (!empty($values)) {
				foreach($values as $val) {
					if ($val['value'] == $current_value) {
						$object .= '<option value="'.$val['value'].'" selected>'.$val['display'].'</option>';
					} else {
						$object .= '<option value="'.$val['value'].'">'.$val['display'].'</option>';
					}		
				}			
			}
		$object .= '</select>';
		
		return $object;
	}
	
	public function date_object($id, $label, $value='', $viewMonthYear=0)
	{
		if (!$value) {
			// current date
			$value = date('m/d/Y');
		}
		
		$object  = '';
		
		$object .= '<input type="text" name="'.$id.'" id="'.$id.'" size="10" maxlength="10" value="'.$value.'" />';
		$object .= '<img src="'.base_url().'images/Calendar.gif" title="'.$label.'" align="top" style="cursor: pointer;" onclick="$(\'#'.$id.'\').focus();" />';
		
		$object .= '<script language="javascript">'."\n";
		$object .= '	$(function(){'."\n";
		$object .= '		// Datepicker'."\n";
		$object .= '		$(\'#'.$id.'\').datepicker({'."\n";
		
		if ($viewMonthYear) {
			$object .= '			changeMonth: true,'."\n";	
			$object .= '			changeYear: true'."\n";
		} else {
			$object .= '			inline: true'."\n";
		}
		
		$object .= '		});'."\n";
		$object .= '	});'."\n";
		$object .= '</script>'."\n";	

		return $object;
	}
	
	public function text_input($id, $current_value, $size, $max, $align, $fontsize, $class="", $keyRestrict) 
	{
		echo $id;
	}

	/**
	 * 
	 * $parameters = array('groupID');
	 * echo $this->htmlhelper->get_json_select('getUsers', $parameters, site_url()."config/getUsers", 'userID') ;
	 * 
	 * @param unknown_type $function
	 * @param unknown_type $parameters
	 * @param unknown_type $url
	 * @param unknown_type $id
	 */
	public function get_json_select($function, $parameters, $url, $id, $func_parameter='', $callback='') 
	{
		if ($func_parameter) {
			$create_function  = 'function '.$function.'('.$func_parameter.')'."\n";
		} else {
			$create_function  = 'function '.$function.'()'."\n";
		}
		 
		$create_function .= '{'."\n"; 
		$create_function .= '$.post("'.$url.'", {'."\n";
		if (!empty($parameters)) {
			$c = 0;
			foreach($parameters as $param) {
				if ($c) $create_function .= ',';	
				$create_function .= $param.': $(\'#'.$param.'\').val()'."\n";
				$c++;
			}
		}
	
		$create_function .= '},'."\n";
		 
		$create_function .= '  function(data){'."\n";
		$create_function .= '    $(\'#'.$id.'\').empty();'."\n"; 
		$create_function .= '    $(\'#'.$id.'\').val("");'."\n"; 
		$create_function .= '    $(\'#'.$id.'\').selectpicker("refresh");'."\n"; 
		$create_function .= '    $(\'#'.$id.'\').append(\'<option value="" selected>&nbsp;</option>\');'."\n";
		$create_function .= '    $(\'#'.$id.'\').selectpicker("refresh");'."\n";
		$create_function .= '		for(c = 0; c < data.length; c++){'."\n"; 
		$create_function .= '             $(\'#'.$id.'\').append(\'<option value="\'+data[c].id+\'">\'+data[c].display+\'</option>\');'."\n";
		$create_function .= '             $(\'#'.$id.'\').selectpicker("refresh");'."\n";
		$create_function .= '		}'."\n"; 		 
		
		if ($func_parameter) {
			$create_function .= "\n\n";
			$create_function .= "		$('#$id').val($func_parameter);";
			$create_function .= "\n\n";
		}
		
		if ($callback) {
		    $create_function .= "\n\n".$callback.";\n\n";
		}
		$create_function .= '  }, "json");'."\n"; 
		$create_function .= '}';
		
		return $create_function;
	}
	
	/**
	 * 
	 * $records = $this->db->get('users');
		$headers = array('Username'=>'left','Name'=>'left');
		$display = array(array('align'=>'left','fields'=>array('userName'=>'')),
						 array('align'=>'left','fields'=>array('lastName'=>' , ','firstName'=>' ','middleName'=>'')),
						);
		echo $this->htmlhelper->recordset_tabular_view($records,$headers,$display,'500');
	 * @param unknown_type $records
	 * @param unknown_type $headers
	 * @param unknown_type $display
	 * @param unknown_type $width
	 */
	public function recordset_tabular_view($records, $headers, $display, $width="100%",$default_rows=5)
	{
		$view = '<table class="listView" border="0" cellpadding="0" cellspacing="0" width="'.$width.'">'."\n";
		$view .= '<tbody>'."\n";
		$view .= '	<tr height="20">'."\n";
		
		if (!empty($headers)) {
			foreach($headers as $title=>$align) {
				$view .= '		<td scope="col" class="listViewThS1" nowrap>';				
				$view .= '<div align="'.$align.'">'.$title.'</div>';
				$view .= '</td>'."\n";
			}
		}
		$view .= '	</tr>'."\n";
		
		$view .= '	<tr>'."\n";
		$view .= '		<td colspan="20" height="1" class="listViewHRS1"></td>'."\n";
		$view .= '	</tr>'."\n";
		
		// this variables for totals
		$totals = array();
		$i = 0;
		$withTotals = 0;
		foreach($display as $val) {
			$totals[$i] = 0;
			$i++;
		}
			
		$colspan = count($headers);
		$row_ctr = 0;
		if ($records->num_rows()) {
			foreach($records->result() as $row) {
				
		    	$view .= '<tr height="20">'."\n";
		    	$i = 0;
		    	foreach($display as $val) {
			    	$view .= '	<td scope="row" class="oddListRowS1" bgcolor="#ffffff" align="'.$val['align'].'" valign="top"><span sugar="sugar0b">';
			    	$value = "";
			    	foreach($val['fields'] as $fld=>$separator) {
			    		
			    		if ($fld == "counter") {
							$value .= ($row_ctr+1)."."; 			    			
			    		} else {
			    			$value .= $row->$fld.$separator;
			    		}
			    	}
			    	
			    	if (isset($val['display_total'])) {
			    		if ($val['display_total']) {
			    			$totals[$i] += $value;
			    			$withTotals = 1;
			    		}
			    	}
			    	
			    	if (isset($val['format'])) {
			    		switch($val['format'])
			    		{
		    			case 'number':
		    				if (isset($val['decimal']) && is_int($val['decimal'])) {
		    					$value = number_format($value, $val['decimal']);
		    				} else {
		    					$value = number_format($value, 2);
		    				}
		    				break;
		    			case 'date':
			    			if (isset($val['dateformat'])) {
		    					$value = date($val['dateformat'], strtotime($value));
		    				} else {
		    					$value = date('m/d/Y', strtotime($value));
		    				}
		    				break;
		    			case 'datetime':
			    			if (isset($val['dateformat'])) {
		    					$value = date($val['dateformat']." h:i A", strtotime($value));
		    				} else {
		    					$value = date('m/d/Y h:i A', strtotime($value));
		    				}
		    				break;
			    		}
			    	}
			    	$view .= $value;
			    	$view .= '	</span></td>'."\n";
			    	
			    	$i++;
		    	}
		    	$view .= '</tr>'."\n";
		    	$view .= '<tr>'."\n";
		    	$view .= '	<td colspan="'.$colspan.'" height="1" class="listViewHRS1"></td>'."\n";
		    	$view .= '</tr>'."\n";
		    	
		    	$row_ctr++;
			}
			
			for($i=$row_ctr; $i<$default_rows; $i++) {
				$view .= '<tr height="20">'."\n";
		    	foreach($display as $val) {
			    	$view .= '	<td scope="row" class="oddListRowS1" bgcolor="#ffffff" align="'.$val['align'].'" valign="top"><span sugar="sugar0b">&nbsp;</span></td>'."\n";
		    	}
		    	$view .= '</tr>'."\n";
		    	$view .= '<tr>'."\n";
		    	$view .= '	<td colspan="'.$colspan.'" height="1" class="listViewHRS1"></td>'."\n";
		    	$view .= '</tr>'."\n";
			}
			
			// display totals
			if ($withTotals) {
				$view .= '<tr height="20">'."\n";
		    	$i = 0;
		    	foreach($display as $val) {
			    	$view .= '	<td scope="row" class="oddListRowS1" bgcolor="#ffffff" align="'.$val['align'].'" valign="top"><span sugar="sugar0b"><b>';
			    	
		    		if (isset($val['display_total'])) {
			    		if ($val['display_total']) {
			    			$value = $totals[$i];
			    		} else {
			    			$value = "&nbsp;";
			    		}
			    	} else {
			    		$value = "&nbsp;";
			    	}
			    	
			    	if ($value!="&nbsp;") {
				    	if (isset($val['format'])) {
		    				if (isset($val['decimal']) && is_int($val['decimal'])) {
		    					$value = number_format($value, $val['decimal']);
		    				} else {
		    					$value = number_format($value, 2);
		    				}
				    	} else {
				    		$value = number_format($value, 2);
				    	}
			    	}
			    	$view .= $value;
			    	$view .= '</b></span></td>'."\n";
			    	
			    	$i++;
		    	}
		    	$view .= '</tr>'."\n";
			}
		} else {
	    	$view .= '<tr>'."\n";
	    	$view .= '	<td colspan="'.$colspan.'" class="oddListRowS1">'."\n";
	        $view .= '    	<table border="0" cellpadding="0" cellspacing="0" width="100%">'."\n";
	        $view .= '    	<tbody>'."\n";
	        $view .= '    	<tr>'."\n";
	        $view .= '    		<td nowrap="nowrap" align="center"><b><i>No results found.</i></b></td>'."\n";
	        $view .= '    	</tr>'."\n";
	        $view .= '    	</tbody>'."\n";
	        $view .= '     	</table>'."\n";
	    	$view .= '	</td>'."\n";
	    	$view .= '</tr>'."\n";
		}
		
		$view .= '</tbody>'."\n";
		$view .= '</table>'."\n";
		
		return $view;
	}
	
	public function array_tabular_view($records, $headers, $display, $width="100%")
	{
		$view = '<table class="listView" border="0" cellpadding="0" cellspacing="0" width="'.$width.'">'."\n";
		$view .= '<tbody>'."\n";
		$view .= '	<tr height="20">'."\n";
		
		if (!empty($headers)) {
			foreach($headers as $title=>$align) {
				$view .= '		<td scope="col" class="listViewThS1" nowrap>';				
				$view .= '<div align="'.$align.'">'.$title.'</div>';
				$view .= '</td>'."\n";
			}
		}
		$view .= '	</tr>'."\n";
		
		$view .= '	<tr>'."\n";
		$view .= '		<td colspan="20" height="1" class="listViewHRS1"></td>'."\n";
		$view .= '	</tr>'."\n";
			
		$colspan = count($headers);
		if (!empty($records)) {
			foreach($records as $row) {
				
		    	$view .= '<tr height="20">'."\n";
		    	foreach($display as $val) {
			    	$view .= '	<td scope="row" class="oddListRowS1" bgcolor="#ffffff" align="'.$val['align'].'" valign="top"><span sugar="sugar0b">';
			    	foreach($val['fields'] as $fld=>$separator) {
			    		$view .= $row[$fld].$separator;
			    	}
			    	$view .= '	</span></td>'."\n";
		    	}
		    	$view .= '</tr>'."\n";
		    	$view .= '<tr>'."\n";
		    	$view .= '	<td colspan="'.$colspan.'" height="1" class="listViewHRS1"></td>'."\n";
		    	$view .= '</tr>'."\n";
		    	
			}
		} else {
	    	$view .= '<tr>'."\n";
	    	$view .= '	<td colspan="'.$colspan.'" class="oddListRowS1">'."\n";
	        $view .= '    	<table border="0" cellpadding="0" cellspacing="0" width="100%">'."\n";
	        $view .= '    	<tbody>'."\n";
	        $view .= '    	<tr>'."\n";
	        $view .= '    		<td nowrap="nowrap" align="center"><b><i>No results found.</i></b></td>'."\n";
	        $view .= '    	</tr>'."\n";
	        $view .= '    	</tbody>'."\n";
	        $view .= '     	</table>'."\n";
	    	$view .= '	</td>'."\n";
	    	$view .= '</tr>'."\n";
		}
		
		$view .= '</tbody>'."\n";
		$view .= '</table>'."\n";
		
		return $view;
	}
	
	public function session_tabular_view($records, $headers, $display, $sessionID, $width="100%",$display_area='',$default_rows=5,$callback="")
	{
		$view = '<table class="table table-striped" width="'.$width.'">'."\n";
		$view .= '<thead class="thead-light">'."\n";
		$view .= '	<tr>'."\n";
		$view .= '		<th class="" nowrap>&nbsp;</td>'."\n";	
		if (!empty($headers)) {
			foreach($headers as $title=>$align) {
				$view .= '		<th class="" nowrap>';				
				$view .= '<div align="'.$align.'">'.$title.'</div>';
				$view .= '</td>'."\n";
			}
		}
		$view .= '	</tr>'."\n";
		$view .= '	</thead>'."\n";
		$view .= '	<tbody>'."\n";
		
		// this variables for totals
		$totals = array();
		$i = 0;
		$withTotals = 0;
		foreach($display as $val) {
			$totals[$i] = 0;
			$i++;
		}
					
		$colspan = count($headers)+1;
		$row_ctr = 0;
		
		if (!empty($records)) {
			foreach($records as $id=>$row) {
				
		    	$view .= '<tr class="row-family">'."\n";
		    	$view .= '	<td>
		    				<i class="la la-trash-o" id="cmdDelete2" style="cursor: pointer;font-size: 24px; color: #14699e!important;"class="la la-trash-o" onclick="delete_session_item(\''.$sessionID.'\',\''.$id.'\',\''.$display_area.'\',\''.$callback.'\');"></i>
		    				</td>'."\n";
		    	$i = 0;
		    	foreach($display as $val) {
			    	$view .= '	<td align="'.$val['align'].'" valign="top">';
			    	$value = "";
			    	foreach($val['fields'] as $fld=>$separator) {
			    		if ($fld == "counter") {
							$value .= ($row_ctr+1)."."; 			    			
			    		} else {
			    			$value .= $row[$fld].$separator;
			    		}
			    	}
			    	
			    	if (isset($val['display_total'])) {
			    		if ($val['display_total']) {
			    			$totals[$i] += str_replace(',', '', $value);
			    			$withTotals = 1;
			    		}
			    	}
			    	
			    	if (isset($val['format'])) {
			    		switch($val['format'])
			    		{
		    			case 'number':
		    				if (isset($val['decimal']) && is_int($val['decimal'])) {
		    					$value = number_format($value, $val['decimal']);
		    				} else {
		    					$value = number_format($value, 2);
		    				}
		    				break;
		    			case 'date':
			    			if (isset($val['dateformat'])) {
		    					$value = date($val['dateformat'], strtotime($value));
		    				} else {
		    					$value = date('m/d/Y', strtotime($value));
		    				}
		    				break;
		    			case 'datetime':
			    			if (isset($val['dateformat'])) {
		    					$value = date($val['dateformat']." h:i A", strtotime($value));
		    				} else {
		    					$value = date('m/d/Y h:i A', strtotime($value));
		    				}
		    				break;
			    		}
			    	}
			    	$view .= $value;
			    	$view .= '	</td>'."\n";

			    	$i++;
		    	}
		    	$row_ctr++;
			}
			
			for($i=$row_ctr; $i<$default_rows; $i++) {
				$view .= '<tr>'."\n";
		    	$view .= '	<td align="center" valign="top">&nbsp;</td>'."\n";
		    	foreach($display as $val) {
			    	$view .= '	<td align="'.$val['align'].'" valign="top">&nbsp;</td>'."\n";
		    	}
		    	$view .= '</tr>'."\n";
			}
			
			// display totals
			if ($withTotals) {
				$view .= '<tr>'."\n";
		    	$view .= '	<td align="center" valign="top">&nbsp;</td>'."\n";
		    	$i = 0;
		    	foreach($display as $val) {
			    	$view .= '	<td align="'.$val['align'].'" valign="top"><b>';
			    	
		    		if (isset($val['display_total'])) {
			    		if ($val['display_total']) {
			    			$value = $totals[$i];
			    		} else {
			    			$value = "&nbsp;";
			    		}
			    	} else {
			    		$value = "&nbsp;";
			    	}
			    	
			    	if ($value!="&nbsp;") {
				    	if (isset($val['format'])) {
		    				if (isset($val['decimal']) && is_int($val['decimal'])) {
		    					$value = number_format($value, $val['decimal']);
		    				} else {
		    					$value = number_format($value, 2);
		    				}
				    	} else {
				    		$value = number_format($value, 2);
				    	}
			    	}
			    	$view .= $value;
			    	$view .= '</b></td>'."\n";
			    	
			    	$i++;
		    	}
		    	$view .= '</tr>'."\n";
			}
		} else {
			for($i=$row_ctr; $i<$default_rows; $i++) {
				$view .= '<tr>'."\n";
		    	$view .= '	<td align="center" valign="top">&nbsp;</td>'."\n";
		    	foreach($display as $val) {
			    	$view .= '	<td align="'.$val['align'].'" valign="top">&nbsp;</td>'."\n";
		    	}
		    	$view .= '</tr>'."\n";
			}
		}
		
		$view .= '</tbody>'."\n";
		$view .= '</table>'."\n";
		
		return $view;
	}
	
	public function tabular_header($headers, $sortby='', $sortorder='', $frm='frmFilter', $callback='')
	{
		$view  = '';

		if (!empty($headers)) {
			foreach($headers as $col) {
			    if ($col['column_field'] == $sortby) {
			        if ($sortorder=="DESC") {
				        $view .= "\n".'<th class="'.$col['width'].' sorting_desc" align="'.$col['align'].'" nowrap>';
			        } else {
				        $view .= "\n".'<th class="'.$col['width'].' sorting_asc" align="'.$col['align'].'" nowrap>';
			        }
			    } else {
			        $view .= "\n".'<th class="'.$col['width'].'" align="'.$col['align'].'" nowrap>';
			    }
				
				$view .= '<span onclick="sorting(\''.$col['column_field'].'\', \''.$frm.'\', \''.$callback.'\')">'.$col['column_header'].'</span>';
				$view .= '</th>';
			}
		}
		
		return $view;
	}
	
	public function select_level($levelID, $default='', $width=100, $isDropsearch=true, $withBlank=false)
	{
	    $select  = '';
	    if ($isDropsearch) {
	        $select .= '<select name="yrLevel" id="yrLevel" style="width:'.$width.'px" class="dropsearch">';
	    } else {
	        $select .= '<select name="yrLevel" id="yrLevel" style="width:'.$width.'px">';
	    }
	    
	    if ($withBlank) {
	       $select .= '<option value="">&nbsp;</option>';
	    }
	    
	    switch($levelID) {
	        case 1:
	            // postgrad
	            for($i=1; $i<=2; $i++) {
	                if ($default == $i) {
	                    $select .= '<option value="'.$i.'" selected>'.$i.'</option>';
	                } else {
	                    $select .= '<option value="'.$i.'">'.$i.'</option>';
	                }
	            }
	            break;
	        case 2:
	            // college
	            for($i=1; $i<=5; $i++) {
	                if ($default == $i) {
	                    $select .= '<option value="'.$i.'" selected>'.$i.'</option>';
	                } else {
	                    $select .= '<option value="'.$i.'">'.$i.'</option>';
	                }
	            }
	            break;
            case 3:
                // seniorhigh
                for($i=11; $i<=12; $i++) {
                    if ($default == $i) {
                        $select .= '<option value="G'.$i.'" selected>Grade '.$i.'</option>';
                    } else {
                        $select .= '<option value="G'.$i.'">Grade '.$i.'</option>';
                    }
                }      
                break;
            case 4:
                // juniorhigh
                for($i=7; $i<=10; $i++) {
                    if ($default == $i) {
                        $select .= '<option value="G'.$i.'" selected>Grade '.$i.'</option>';
                    } else {
                        $select .= '<option value="G'.$i.'">Grade '.$i.'</option>';
                    }
                }  
                break;
            case 5:
                // elementary
                for($i=1; $i<=6; $i++) {
                    if ($default == $i) {
                        $select .= '<option value="G'.$i.'" selected>Grade '.$i.'</option>';
                    } else {
                        $select .= '<option value="G'.$i.'">Grade '.$i.'</option>';
                    }
                }
                break;
            case 6:
                // preschool
                if ($default == 'Nursery') {
                    $select .= '<option value="Nursery" selected>Nursery</option>';
                } else {
                    $select .= '<option value="Nursery">Nursery</option>';
                }
                
                if ($default == 'Kinder 1') {
                    $select .= '<option value="Kinder 1" selected>Kinder 1</option>';
                } else {
                    $select .= '<option value="Kinder 1">Kinder 1</option>';
                }
                
                if ($default == 'Kinder 2') {
                    $select .= '<option value="Kinder 2" selected>Kinder 2</option>';
                } else {
                    $select .= '<option value="Kinder 2">Kinder 2</option>';
                }
                
                break;
            default:
                // sped and vocational
	    }
	    
	    
	    $select .= '</select>';
	    
	    return $select;
	}
	
	public function select_semester($levelID, $default='', $width=100, $isDropsearch=true, $withBlank=false)
	{
	    $select  = '';
	    if ($isDropsearch) {
	        $select .= '<select name="semCode" id="semCode" style="width:'.$width.'px" class="dropsearch">';
	    } else {
	        $select .= '<select name="semCode" id="semCode" style="width:'.$width.'px">';
	    }
	     
	    if ($withBlank) {
	        $select .= '<option value="">&nbsp;</option>';
	    }
	     
	    switch($levelID) {
	        case 1:
	        case 2:
	        case 3:
	            // postgrad, college, seniorhigh
        	    if ($default == '1') {
        	        $select .= '<option value="1" selected>1st Sem</option>';
        	    } else {
        	        $select .= '<option value="1">1st Sem</option>';
        	    }
        	    
        	    if ($default == '2') {
        	        $select .= '<option value="2" selected>2nd Sem</option>';
        	    } else {
        	        $select .= '<option value="2">2nd Sem</option>';
        	    }
        	    
        	    if ($default == '4') {
        	        $select .= '<option value="4" selected>Summer</option>';
        	    } else {
        	        $select .= '<option value="4">Summer</option>';
        	    }
        	    
        	    break;
    	    case 4:
    	        // juniorhigh
    	        if ($default == '1') {
    	            $select .= '<option value="1" selected>Regular</option>';
    	        } else {
    	            $select .= '<option value="1">Regular</option>';
    	        }
    	         
    	        if ($default == '4') {
    	            $select .= '<option value="4" selected>Summer</option>';
    	        } else {
    	            $select .= '<option value="4">Summer</option>';
    	        }
    	         
    	        break;
    	    default:
    	        
	    }
        	     
	    $select .= '</select>';
	     
	    return $select;
	}
	
	
	public function select_schoolyear($start, $end, $default, $width=100, $isDropsearch=true, $withBlank=false)
	{
	    $select  = '';
	    if ($isDropsearch) {
	        $select .= '<select name="schYear" id="schYear" style="width:'.$width.'px" class="dropsearch">';
	    } else {
	        $select .= '<select name="schYear" id="schYear" style="width:'.$width.'px">';
	    }
	    
	    if ($withBlank) {
	        $select .= '<option value="">&nbsp;</option>';
	    }

	    for($i=$start; $i>=$end; $i--) {
	        $schyear = $i."-".($i+1);
    	    if ($default == $schyear) {
    	        $select .= '<option value="'.$schyear.'" selected>'.$schyear.'</option>';
    	    } else {
    	        $select .= '<option value="'.$schyear.'">'.$schyear.'</option>';
    	    }
	    }
	    
	    $select .= '</select>';
	    
	    return $select;
	}
	
	public function select_object_for_popup($id, $data, $value, $display, $width="", $current_value="", $function_call="", $header=false, $extra="") 
	{
		$is_multi_display = is_array($display);
		
		$html = '<select class="custom-select-a" id="'.$id.'" name="'.$id.'" ';
		if ($width) {
			$html .= 'style="width: '.$width.'px; height: 32px;" ';
	    } else {
	        $html .= 'style="height: 32px;" ';
	    }
	    
		if ($function_call)
			$html .= 'onchange="'.$function_call.';" ';
		if ($extra)
			$html .= $extra;
		$html .= '>'."\n";
		
		if(is_array($header)) { 
			foreach($header as $val=>$str) {
				$html .= '<option value="'.$val.'">'.$str.'</option>';
			} 
		} else {
			$html .= '<option value="">&nbsp;</option>';
		}
		
		if ($data->num_rows()) {
			foreach($data->result() as $row) {
				if (trim($current_value)==$row->$value) {
					$html .= '<option value="'.$row->$value.'" selected>';
						if ($is_multi_display) {
							foreach($display as $key=>$separator) {
								$html .= $row->$key.$separator;
							} 					
						} else {
							$html .= $row->$display;
						}
					$html .= "</option>\n";
				} else {
					$html .= '<option value="'.$row->$value.'">';
						if ($is_multi_display) {
							foreach($display as $key=>$separator) {
								$html .= $row->$key.$separator;
							} 					
						} else {
							$html .= $row->$display;
						}
					$html .= "</option>\n";
				}
			}
		} 
		$html .= '</select>'."\n";
			
		return $html;
		
	}
	
}
?>