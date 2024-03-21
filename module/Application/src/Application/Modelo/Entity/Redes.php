<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Redes extends TableGateway{
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgi_gi_redes_tabla', $adapter, $databaseSchema, $selectResultPrototype);
	}

	public function addRedes($data=array(), $id, $file_name){
		$array=array(
			'id_grupo_inv'=>$id,
			'id_red'=>$data->nombre_red,
			'archivo' => $file_name
    	);
		$this->insert($array);
		return 1;
	}

    public function getRedes($id){
		$resultSet = $this->select(array('id_grupo_inv'=>$id));
		return $resultSet->toArray();
   	}
    
    public function getRedesi(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarRedes($id) {
    	$this->delete(array('id' => $id));
    }

    public function getRedesById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateRedes($id, $data = array(), $file_name)
    {   
        $array = array(
            'id_red' => $data->nombre_red,
            'archivo' => $file_name     
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }
}
?>