<?php

class Encrypter 
{
	public function __construct()
	{
		
	}
	
	function encode($data)
	{
	    $data = $data."";
	    
		$alpha_keys = array(0=>'a','b','c','d','e','f','g','h','i','j','k','l',
							'm','n','o','p','q','r','s','t','u','v','w','x','y','z',
							'A','B','C','D','E','F','G','H','I','J','K','L',
							'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
						);
						
		$num_keys = array();
		$i = 0;
		foreach($alpha_keys as $val) {
			$num_keys[$val] = $i;
			$i++;
		}
		
		$keys = array_merge($alpha_keys,$num_keys);
		$keys['-'] = '-';
		$keys['_'] = '_';
		$keys['|'] = '|';
		$keys['&'] = 'z';
		
		$dummy_keys = array(0=>'a','b','-','|','c','d','e','f','-','g','h','i','j','|',
								'k','l','m','n','|','o','p','q','r','|','s','t','u','v','w','x','y','z'
								,'-','|','|','0','1','2','|','3','|','4','|','5','6','|','7','|','8','|','9');
		
		$len = strlen($data);
		
		// add dummy as prefix
		$en_str = "";
		for($i=0; $i < 10; $i++) {
			$en_str .= $dummy_keys[rand(0,count($dummy_keys)-1)];
		}
		
		for($i=0; $i < $len; $i++) {
			if (in_array($data[$i], $keys)) {
				if ($en_str)
					$en_str .= '&'.$keys[$data[$i]];
				else 
					$en_str .= $keys[$data[$i]];
			} else {
				$en_str .= '&'.$data[$i];
			}
		}
		
		// add dummy as suffix
		$en_str .= '&';
		$r = 10 + (20 - strlen($en_str));
		for($i=0; $i < $r; $i++) {
			$en_str .= $dummy_keys[rand(0,count($dummy_keys)-1)];
		}
		
		return $en_str;
	}
	
	function decode($data)
	{
	    $data = $data."";
	    
		$alpha_keys = array(0=>'a','b','c','d','e','f','g','h','i','j','k','l',
							'm','n','o','p','q','r','s','t','u','v','w','x','y','z',
							'A','B','C','D','E','F','G','H','I','J','K','L',
							'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
						);
		$num_keys = array();
		$i = 0;
		foreach($alpha_keys as $val) {
			$num_keys[$val] = $i;
			$i++;
		}
		
		$keys = array_merge($alpha_keys,$num_keys);
		$keys['-'] = '-';
		$keys['_'] = '_';
		$keys['|'] = '|';
		$keys['&'] = 'z';
		
		$data = explode('&',$data);
		$plain_str = "";
		foreach($data as $ctr=>$d) {
			if ($ctr > 0 && ($ctr+1 != count($data))) {
				if ($d != 'z') {
					foreach($keys as $k=>$v) {
						if (is_numeric($d)) {
							if ($v == $d) {
								$plain_str .= $k;
							}
						} else {
							if ($v === $d) {
								$plain_str .= $k;
							}
						}
					}
				} else {
					$plain_str .= '&';		
				}
			}
		}
		
		return $plain_str;
	}	
}	