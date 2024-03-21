<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Proyectosintusua extends TableGateway{
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('aps_hv_proyectosint', $adapter, $databaseSchema, $selectResultPrototype);
	}

	public function addProyectos($id, $id2){
		print_r($data);
		$array=array(
			'id_grupo_inv'=>$id,
			'id_proyecto'=>$id2
    	);
		$this->insert($array);
		return 1;
	}

    public function getProyectos($id){
		$resultSet = $this->select(array('id_grupo_inv'=>$id));
		return $resultSet->toArray();
   	}
    
    public function getProyectosi(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarProyecto($id) {
    	$this->delete(array('id' => $id));
    }

    public function getProyectointusuaById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateProyectointusua($id, $id2, $file_name)
    {   
        $array = array(
            'id_proyecto' => $id2, 
            'archivo' => $file_name
        );

        $this->update($array, array(
            'id' => $id
        ));
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