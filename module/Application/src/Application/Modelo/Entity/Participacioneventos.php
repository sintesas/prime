<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Participacioneventos extends TableGateway{
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('msi_participacioneventos', $adapter, $databaseSchema, $selectResultPrototype);
	}
	public function addParticipacioneventos($data=array(), $id, $archi){
        $array=array(
			'id_semillero'=>$id,
			'archivo'=>$archi,
			'nombre'=>$data->nombre,
			'lugar'=>$data->lugar,
			'tipo_participacion'=>$data->tipo_participacion,
			'fecha'=>$data->fecha,
			'new_archivo' => 'Si'
        );
      	$this->insert($array);
		return 1;
	}

    public function getParticipacioneventos($id){
		$resultSet = $this->select(array('id_semillero'=>$id));
		return $resultSet->toArray();
   	}

   	public function getParticipacioneventost(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function getParticipacioneventosid($id){
		$resultSet = $this->select(array('id_archivo'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarParticipacioneventos($id) {
        		$this->delete(array('id_archivo' => $id));
    }
}
?>