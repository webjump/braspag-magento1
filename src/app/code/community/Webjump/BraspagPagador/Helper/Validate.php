<?php
class Webjump_BraspagPagador_Helper_Validate extends Mage_Core_Helper_Abstract
{
	const CPF = 'CPF';
	const CNPJ = 'CNPJ';
		
	
	function validateCpfCnpj($str)
	{
		if ($this->validateCpf($str) || $this->validateCnpj($str)) {
			return true;
		}else{
			return false;
		}
	}

	function isCpfOrCnpj($str)
	{
		if ($this->isCpf($str)) {
			return self::CPF;
		}
		elseif ($this->isCnpj($str)) {
			return self::CNPJ;
		}
		else {
			return false;
		}
	}

	function isCpf($str)
	{
		return $this->validateCpf($str);
	}

	function validateCpf($str)
	{
		$str = preg_replace('/[^0-9]+/', '', $str);

		if (strlen($str) != 11) {
			return false;
		}

		$acum=0;
		for($i=0; $i<9; $i++) {
			$acum+= $str[$i]*(10-$i);
		}
		
		$x=$acum % 11;
		$acum = ($x>1) ? (11 - $x) : 0;

		if ($acum != $str[9]) {
			return false;
		}

		$acum=0;
		for ($i=0; $i<10; $i++){
			$acum+= $str[$i]*(11-$i);
		}
		
		$x=$acum % 11;
		$acum = ($x > 1) ? (11-$x) : 0;

		if ( $acum != $str[10]){
			return false;
		}

		return true;
	}

	function isCnpj($str)
	{
		return $this->validateCnpj($str);
	}

	function validateCnpj($str)
	{
		$str = preg_replace('/[^0-9]+/', '', $str);

		if (!preg_match('/^(\d{2,3})(\d{3})(\d{3})(\d{4})(\d{2})$/', $str, $matches)) {
			return false;
		}

		array_shift($matches);
		$str = implode('', $matches);

		if (strlen($str) > 14)
			$str = substr($str, 1);

		$sum1 = 0;
		$sum2 = 0;
		$sum3 = 0;
		$calc1 = 5;
		$calc2 = 6;

		for ($i=0; $i <= 12; $i++) {
			$calc1 = ($calc1 < 2) ? 9 : $calc1;
			$calc2 = ($calc2 < 2) ? 9 : $calc2;

			if ($i <= 11)
				$sum1 += $str[$i] * $calc1;

			$sum2 += $str[$i] * $calc2;
			$sum3 += $str[$i];
			$calc1--;
			$calc2--;
		}

		$sum1 %= 11;
		$sum2 %= 11;

		return ($sum3 && $str[12] == ($sum1 < 2 ? 0 : 11 - $sum1) && $str[13] == ($sum2 < 2 ? 0 : 11 - $sum2)) ? /*$str*/ true : false;
	}
}