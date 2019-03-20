<?php

class Numbertowords {
	function convert_number($number) {
		if (($number < 0) || ($number > 999999999)) {
			throw new Exception("Number is out of range");
		}
		$Gn = floor($number / 1000000);
		/* Millions (giga) */
		$number -= $Gn * 1000000;
		$kn = floor($number / 1000);
		/* Thousands (kilo) */
		$number -= $kn * 1000;
		$Hn = floor($number / 100);
		/* Hundreds (hecto) */
		$number -= $Hn * 100;
		$Dn = floor($number / 10);
		/* Tens (deca) */
		$n = $number % 10;
		/* Ones */
		$res = "";
		if ($Gn) {
			$res .= $this->convert_number($Gn) .  " Million";
		}
		if ($kn) {
			$res .= (empty($res) ? "" : " ") .$this->convert_number($kn) . " Thousand";
		}
		if ($Hn) {
			$res .= (empty($res) ? "" : " ") .$this->convert_number($Hn) . " Hundred";
		}
		$ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
		$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
		if ($Dn || $n) {
			if (!empty($res)) {
				$res .= " ";
			}
			if ($Dn < 2) {
				$res .= $ones[$Dn * 10 + $n] . '';
			} else {
				$res .= $tens[$Dn] .'';
				if ($n) {
					$res .= "-" . $ones[$n] .'';
				}
			}
			// $res .= " Pesos";
		}
		if (empty($res)) {
			$res = "Zero";
		}
		//return $res;
		 $points = substr(number_format($number,2),-2,2);
            if($points > 0){
            $Dn = floor($points / 10);
	/* Tens (deca) */
				$n = $points % 10;
			            /* Ones */
			                
			    if ($Dn || $n) {			    	
					if (!empty($res)) {
						$res .= " Pesos and ";
					}
					if($Dn < 2) {
						$res .= $ones[$Dn * 10 + $n];
					} else {
						$res .= $tens[$Dn];
						if ($n) {
							$res .= "-" . $ones[$n];
						}
					}
                    $res .= " Cents";
				}
            
            }
		return $res;

	}
}
?>