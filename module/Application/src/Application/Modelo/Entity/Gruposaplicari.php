<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Gruposaplicari extends TableGateway{
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('mgc_gruposaplicari', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addGrupored($id, $id2){
		print_r($data);
		$array=array(
			'id_aplicari'=>$id2,
			'id_grupo'=>$id,
        );
  		$this->insert($array);
		return 1;
	}

    public function getGruposaplicari($id){
		$resultSet = $this->select(array('id_aplicari'=>$id));
		return $resultSet->toArray();
   	}

    public function getGruposaplicarit(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarGruposaplicari($id) {
        		$this->delete(array('id' => $id));
    }

    public function updateGruposaplicari($id, $id_grupo) {
        $array = array(
            'id_grupo' => $id_grupo,          
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }
}
?>