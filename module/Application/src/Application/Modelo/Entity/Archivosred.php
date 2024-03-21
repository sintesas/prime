<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Archivosred extends TableGateway{
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mri_archivosred', $adapter, $databaseSchema, $selectResultPrototype);
	}
	public function addArchivosred($data=array(), $id, $archi){
        $array=array(
			'id_red'=>$id,
			'archivo'=>$archi,
			'id_tipo_archivo'=>$data->id_tipo_archivo,
			'new_archivo' => "Si"
        );
      	$this->insert($array);
		return 1;
	}

    public function getArchivosred($id){
		$resultSet = $this->select(array('id_red'=>$id));
		return $resultSet->toArray();
   	}

   	public function getArchivosredt(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function getArchivosredid($id){
		$resultSet = $this->select(array('id_archivo'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarArchivosred($id) {
        		$this->delete(array('id_archivo' => $id));
    }
}
?>