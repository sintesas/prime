<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Formulario extends TableGateway{
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('adm_pantalla', $adapter, $databaseSchema, $selectResultPrototype);
	}

	public function getFormularioi(){
		$resultSet = $this->select(function ($select) {
            $select->order(array(
                'nombre ASC'
            ));
        });
        return $resultSet->toArray();
   	}

	public function addFormulario($data=array()){
		$array=array(
			'nombre'=> trim($data->nombre_formulario),
			'id_submodulo'=> $data->id_submodulo,
			'descripcion'=> trim($data->descripcion)
    	);
		$this->insert($array);
		return 1;
	}
}
?>