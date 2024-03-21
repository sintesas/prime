<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Agregarhorarios extends TableGateway{
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_aplicarm_horario', $adapter, $databaseSchema, $selectResultPrototype);
	}

	public function addHorario($data=array(), $id){
		$array=array(
			'id_aplicar'=>$id,
			'dia'=>$data->dia,
			'hora_inicio'=>$data->hora_inicio,
			'hora_fin'=>$data->hora_fin,
    	);
		$this->insert($array);
		return 1;
	}

    public function getHorarioByAplicacion($id){
		$resultSet = $this->select(array('id_aplicar'=>$id));
		return $resultSet->toArray();
   	}
    
    public function getProyectosi(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarHorario($id) {
    	$this->delete(array('id' => $id));
    }
}
?>