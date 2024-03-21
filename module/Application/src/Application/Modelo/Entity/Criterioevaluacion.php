<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Criterioevaluacion extends TableGateway{
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_criterioevaluacion', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	public function addCriterioevaluacion($data=array(), $id){
   		$array=array(
			'id_convocatoria'=>$id,
			'criterio'=>$data->criterio,
     	);
     	$this->insert($array);
		return 1;
	}

    public function getCriterioevaluacionByConvocatoria($id_convocatoria){
		$resultSet = $this->select(array('id_convocatoria'=>$id_convocatoria));
		return $resultSet->toArray();
   	}

    public function getCriterioevaluacion($id){
		$resultSet = $this->select(array('id'=>$id));
		return $resultSet->toArray();
   	}


    	public function getCriterioevaluacions(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarCriterioevaluacion($id) {
        		$this->delete(array('id' => $id));
    	}
}
?>