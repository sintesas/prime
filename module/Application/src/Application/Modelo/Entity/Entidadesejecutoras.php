<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Entidadesejecutoras extends TableGateway{

	//variables de la tabla
	private $archivo;
	private $id_tipo_archivo;
	private $nombre;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgp_entidadesejecutoras', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   

	public function addEntidad($data=array(), $id){
		$array=array(
			'id_entidad'=>$data->id_entidad,
			'id_rol'=>$data->id_rol,
			'id_proyecto'=>$id
        );
      	$this->insert($array);
		return 1;
	}

    public function getEntidadesConvocatoria($id){
		$resultSet = $this->select(array('id_proyecto'=>$id));
		return $resultSet->toArray();
   	}

	public function eliminarEntidad($id) {
    	$this->delete(array('id_entidadejecutora' => $id));
    }


    	public function getArchivosid($id){
		$resultSet = $this->select(array('id_archivo'=>$id));
		return $resultSet->toArray();
   	}
}
?>