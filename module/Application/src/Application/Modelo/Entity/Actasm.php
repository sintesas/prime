<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Actasm extends TableGateway{

	//variables de la tabla
	private $archivo;
	private $id_tipo_archivo;
	private $nombre;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgp_actas_monitores', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->archivo=$datos["archivo"];
		$this->id_tipo_archivo=$datos["id_tipo_archivo"];
		$this->nombre=$datos["nombre"];
	}

	public function addArchivos($data=array(), $id, $archi){
		self::cargaAtributos($data);

        		$array=array(
			'id_monitor'=>$id,
			'archivo'=>$archi,
			'id_tipo_archivo'=>$this->id_tipo_archivo,
			'nombre'=>$this->nombre
            		 );
      		  $this->insert($array);
		return 1;
	}

    	public function getArchivos($id){
		$resultSet = $this->select(array('id_monitor'=>$id));
		return $resultSet->toArray();
   	}

    	public function getArchivosid($id){
		$resultSet = $this->select(array('id_archivo'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarArchivos($id) {
        		$this->delete(array('id_archivo' => $id));
    	}
}
?>