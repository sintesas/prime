<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Multimedia extends TableGateway{
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('multimedia', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	public function addMultimedia($data=array()){
    	$array=array(
			'url'=>$data["url"]
        );
  		$this->insert($array);
		return 1;
	}

    public function getMultimedia(){
		$resultSet = $this->select();
		$data = $resultSet->toArray(); 
		$videoId = "";
		if($data != null){
			foreach ($data as $url) {
				$videoId = $url["url"];
			}
		}
		return $videoId;
   	}
}
?>