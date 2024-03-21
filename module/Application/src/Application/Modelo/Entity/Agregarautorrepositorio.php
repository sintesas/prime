<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Agregarautorrepositorio extends TableGateway{
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('rep_autor', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addAgregarautorrepositorio($id, $id2, $id3, $id4){
		$array=array(
			'id_repositorio'=>$id3,
			'id_usuario'=>$id,
			'seccion'=>$id4,
			'id_objeto'=>$id2,
        );
  		$this->insert($array);
		return 1;
	}
    public function getAgregarautorrepositorio($id){
		$resultSet = $this->select(array('id_repositorio'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarAgregarautorrepositorio($id_usuario, $id_repositorio, $id_objeto, $seccion) {
        $this->delete(array(
			'id_repositorio'=>$id_repositorio,
			'id_usuario'=>$id_usuario,
			'seccion'=>$seccion,
			'id_objeto'=>$id_objeto,
        ));
    }

    public function getAutoresrepositoriot(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarAutorrepositorio($id) {
        $this->delete(array('id' => $id));
    }

    public function getAgregarautorrepositorioFiltro($id){
		$resultSet = $this->select(function ($select) use($id)
        {
            $select->columns(array(
                'id_repositorio'
            ));
            $select->where(array(
                'id_usuario' => $id
            ));
            $select->group(array('id_repositorio')); 
        });
        return $resultSet->toArray();
   	}
}
?>