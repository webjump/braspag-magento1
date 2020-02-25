<?php
class Braspag_Lib_Core_Data_Object
{
	public function convertEncode($str)
	{
	  	try {
			$chkMessage = htmlentities($str, ENT_QUOTES, 'ISO-8859-1');
			iconv('UTF-8', 'ISO-8859-1', $chkMessage);//Try to convert. If fail a E_NOTICE will be catch
		} catch (Exception $e) {
			$str = utf8_decode($str);
		}

		return $str;
	}
	
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function debug()
    {
    	return $this->getDataAsArray();
    }

	public function getDataAsArray()
    {
    	return $this->convertDataToArray($this->getArrayCopy());
    }

	public function convertDataToArray($data)
	{
		if ($data instanceof Braspag_Lib_Mpi_Object) {
			$data = $data->getDataAsArray();
		} elseif (is_object($data)) {
			$data = get_object_vars($data);
		}

		if (is_array($data)) {
			foreach($data AS $key=>$value){
				$data[$key] = $this->convertDataToArray($value);
			}
		}
		
		return $data;
	}
}