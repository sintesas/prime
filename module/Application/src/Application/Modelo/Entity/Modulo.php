<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Modulo extends TableGateway{
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('adm_modulo', $adapter, $databaseSchema, $selectResultPrototype);
	}

	public function geModulosi(){
		$resultSet = $this->select(function ($select) {
            $select->order(array(
                'nombre ASC'
            ));
        });
        return $resultSet->toArray();
   	}

	public function addModulo($data=array()){
		$array=array(
			'nombre' => trim($data->nombre_modulo)
    	);
		$this->insert($array);
		return 1;
	}
}
?>