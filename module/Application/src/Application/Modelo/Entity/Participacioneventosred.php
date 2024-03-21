<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Participacioneventosred extends TableGateway{
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mri_participacioneventos', $adapter, $databaseSchema, $selectResultPrototype);
	}
	public function addParticipacioneventosred($data=array(), $id, $archi){
        $array=array(
			'id_red'=>$id,
			'archivo'=>$archi,
			'nombre'=>$data->nombre,
			'lugar'=>$data->lugar,
			'tipo_participacion'=>$data->tipo_participacion,
			'fecha'=>$data->fecha,
			'new_archivo'=>'Si'
        );
      	$this->insert($array);
		return 1;
	}

    public function getParticipacioneventosred($id){
		$resultSet = $this->select(array('id_red'=>$id));
		return $resultSet->toArray();
   	}

   	public function getParticipacioneventosredt(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function getParticipacioneventosredid($id){
		$resultSet = $this->select(array('id_archivo'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarParticipacioneventosred($id) {
        		$this->delete(array('id_archivo' => $id));
    }
}
?>