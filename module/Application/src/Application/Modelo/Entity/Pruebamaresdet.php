<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Pruebamaresdet extends TableGateway{

	//variables de la tabla
	private $tipo;
	private $descripcion;
	private $fecha;
	private $tema;
	private $instituciones;
	private $id_pais;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('CIUP_REGIST_NOTAS', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
       	public function getPrueba($id){
		$resultSet = $this->select(array('CEDULA'=>$id));
		return $resultSet->toArray();
   	}
	
}
?>