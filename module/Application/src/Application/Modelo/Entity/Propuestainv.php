<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Propuestainv extends TableGateway{

	//variables de la tabla
	private $archivo;
	private $id_tipo_archivo;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_propuesta_inv', 
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
			'id_aplicar'=>$id,
			'archivo'=>$archi,
			'id_tipo_archivo'=>$this->id_tipo_archivo,
			'nombre'=>$data->nombre,
			'descripcion'=>$data->descripcion,
			'new_archivo' => "Si"
            		 );
      		  $this->insert($array);
		return 1;
	}

    	public function getArchivos($id){
		$resultSet = $this->select(array('id_aplicar'=>$id));
		return $resultSet->toArray();
   	}

    	public function getArchivosid($id){
		$resultSet = $this->select(array('id_archivo'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarArchivos($id) {
        		$this->delete(array('id_archivo' => $id));
    	}

    public function updatePropuestainv($id, $data = array())
    {   
        $array = array(
            'nombre' => $data->nombre,
            'descripcion' => $data->descripcion
        );

        $this->update($array, array(
            'id_archivo' => $id
        ));
        return 1;
    }
}
?>