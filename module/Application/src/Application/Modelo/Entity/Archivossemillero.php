<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Archivossemillero extends TableGateway{
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('msi_archivossemillero', $adapter, $databaseSchema, $selectResultPrototype);
	}
	public function addArchivossemillero($data=array(), $id, $archi){
        $array=array(
			'id_semillero'=>$id,
			'archivo'=>$archi,
			'id_tipo_archivo'=>$data->id_tipo_archivo,
			'new_archivo' => "Si"
        );
      	$this->insert($array);
		return 1;
	}

    public function getArchivossemillero($id){
		$resultSet = $this->select(array('id_semillero'=>$id));
		return $resultSet->toArray();
   	}

   	public function getArchivossemillerot(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function getArchivossemilleroid($id){
		$resultSet = $this->select(array('id_archivo'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarArchivossemillero($id) {
        		$this->delete(array('id_archivo' => $id));
    }

    public function updateArchivossemillero($id, $data) {
        $array = array(
            'id_tipo_archivo' => $data->id_tipo_archivo      
        );

        $this->update($array, array(
            'id_archivo' => $id
        ));
        return 1;
    }
}
?>