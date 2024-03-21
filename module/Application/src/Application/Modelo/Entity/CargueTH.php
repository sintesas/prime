<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;
use Zend\Db\Sql\Select as Limit;

class CargueTH extends TableGateway{

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
					return parent::__construct('VUPN_PLAN_TRAB_INVEST_TERCERO', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}



	public function getUsuariosid($id)
	{
			$tit='A';
			$rowset = $this->select(function (Where $select) use ($tit){
			$select->limit(1500);
			});
			return $rowset->toArray();
	}
	
}
?>