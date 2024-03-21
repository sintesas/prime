<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Mares extends TableGateway{

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
					return parent::__construct('CIUP_REGISTRADOS', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}

	public function getUsuarios($id)
	{
		$id = (int) $id;
		$rowset = $this->select(array('CEDULA'=>$id));
		$row = $rowset->current();
		if(!$row){
			throw new \Exception("no hay id asociado");
		}
	  return $row;
	}


	public function getUsuariosid($id)
	{
			$tit='A';
			$rowset = $this->select(function (Where $select) use ($tit){
			$select->limit(10);
			});
			return $rowset->toArray();
	}


       	public function getInfoaplicar($id){
		$filter = new StringTrim();
$id=$filter->filter($id);
		$resultSet = $this->select(function ($select) use ($id){
		$select->columns(array('PROGRAMA',
							   'FACULTAD',
							   'PROMEDIO',
							   'CRED_VALIDOS',
							   'CRED_PROGRAMA',
							   'TOP_25'));
		$select->where(array('CEDULA = ?'=>$id));
		}); 
$row = $resultSet->current();

return $row;


   	}
	
}
?>