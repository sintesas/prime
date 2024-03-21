<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Gruposred extends TableGateway{
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('mri_gruposred', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addGrupored($id, $id2){
		print_r($data);
		$array=array(
			'id_red'=>$id2,
			'id_grupo'=>$id,
        );
  		$this->insert($array);
		return 1;
	}

    public function getGruposred($id){
		$resultSet = $this->select(array('id_red'=>$id));
		return $resultSet->toArray();
   	}

    public function getGruposredt(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarGruposred($id) {
        		$this->delete(array('id' => $id));
    }

    public function getGruposById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateGruposred($id, $id_grupo, $file_name) {
        $array = array(
            'id_grupo' => $id_grupo,
            'archivo' => $file_name      
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }

    public function updateArchivo($id, $file_name)
    {   
        $array = array(
            'archivo' => $file_name
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }
}
?>