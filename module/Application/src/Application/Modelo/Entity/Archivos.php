<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Archivos extends TableGateway{

	//variables de la tabla
	private $archivo;
	private $id_tipo_archivo;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgi_gi_archivos', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->archivo=$datos["archivo"];
		$this->id_tipo_archivo=$datos["id_tipo_archivo"];
	}

	public function addArchivos($data=array(), $id, $archi){
		self::cargaAtributos($data);

        		$array=array(
			'id_grupo_inv'=>$id,
			'archivo'=>$archi,
			'id_tipo_archivo'=>$this->id_tipo_archivo,
			'new_archivo' => "Si"
            		 );
      		  $this->insert($array);
		return 1;
	}

    public function getArchivos($id){
		$resultSet = $this->select(array('id_grupo_inv'=>$id));
		return $resultSet->toArray();
   	}

    public function getArchivosid($id){
		$resultSet = $this->select(array('id_archivo'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarArchivos($id){
        $this->delete(array('id_archivo' => $id));
    }

    public function getArchivost(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}
}
?>