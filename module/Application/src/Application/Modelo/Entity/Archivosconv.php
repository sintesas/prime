<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Archivosconv extends TableGateway{

	//variables de la tabla
	private $archivo;
	private $id_tipo_archivo;
	private $visible;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_archivos_conv', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->archivo=$datos["archivo"];
		$this->id_tipo_archivo=$datos["id_tipo_archivo"];
		$this->visible=$datos["visible"];
	}

	public function addArchivosconv($data=array(), $id, $archi){
		self::cargaAtributos($data);
        	$array=array(
				'id_convocatoria'=>$id,
				'archivo' => $archi,
				'id_tipo_archivo'=>$this->id_tipo_archivo,
				'visible'=>$this->visible,
				'new_archivo' => "Si"
            );
      		$this->insert($array);
		return 1;
	}

    	public function getArchivosconv($id){
		$resultSet = $this->select(array('id_convocatoria'=>$id));
		return $resultSet->toArray();
   	}

    	public function getArchivosconvs(){
		$resultSet = $this->select(array('visible'=>'S'));
		return $resultSet->toArray();
   	}

    	public function getArchivosconvid($id){
		$resultSet = $this->select(array('id_archivo'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarArchivosconv($id) {
        		$this->delete(array('id_archivo' => $id));
    	}
}
?>