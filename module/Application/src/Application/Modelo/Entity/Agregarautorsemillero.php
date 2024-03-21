<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Agregarautorsemillero extends TableGateway{
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('msi_autorsemillero', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addAgregarautorsemillero($id, $id2, $id3, $id4){
		$array=array(
			'id_semillero'=>$id3,
			'id_usuario'=>$id,
			'seccion'=>$id4,
			'id_objeto'=>$id2,
        );
  		$this->insert($array);
		return 1;
	}
    public function getAgregarautorsemillero($id){
		$resultSet = $this->select(array('id_semillero'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarAgregarautorsemillero($id_usuario, $id_semillero, $id_objeto, $seccion) {
        $this->delete(array(
			'id_semillero'=>$id_semillero,
			'id_usuario'=>$id_usuario,
			'seccion'=>$seccion,
			'id_objeto'=>$id_objeto,
        ));
    }

    public function getAutoressemillerot(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarGrupossemillero($id) {
        $this->delete(array('id' => $id));
    }
}
?>