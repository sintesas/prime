<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Agregarautorred extends TableGateway{
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('mri_autorred', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addAgregarautorred($id, $id2, $id3, $id4){
		$array=array(
			'id_red'=>$id3,
			'id_usuario'=>$id,
			'seccion'=>$id4,
			'id_objeto'=>$id2
        );
  		$this->insert($array);
		return 1;
	}

	public function addAgregarautorrol($id, $id2, $id3, $id4, $id5){
		$array=array(
			'id_red'=>$id3,
			'id_usuario'=>$id,
			'seccion'=>$id4,
			'id_objeto'=>$id2,
			'id_rol'=>$id5
        );
  		$this->insert($array);
		return 1;
	}
    public function getAgregarautorred($id){
		$resultSet = $this->select(array('id_red'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarAgregarautorred($id_usuario, $id_red, $id_objeto, $seccion) {
        $this->delete(array(
			'id_red'=>$id_red,
			'id_usuario'=>$id_usuario,
			'seccion'=>$seccion,
			'id_objeto'=>$id_objeto,
        ));
    }

    public function getAgregarautorredt(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarGruposred($id) {
        $this->delete(array('id' => $id));
    }
}
?>