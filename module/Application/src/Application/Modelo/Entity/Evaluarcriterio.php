<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Evaluarcriterio extends TableGateway{
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_evaluarcriterio', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}


    public function getEvaluarcriterio($id_aplicar, $id_criterio){
		$resultSet = $this->select(
			array(
				'id_aplicar'=>$id_aplicar,
				'id_criterio'=>$id_criterio
			)
		);
		return $resultSet->toArray();
   	}

   	public function getEvaluarcriterioAplicacion($id_aplicar){
		$resultSet = $this->select(
			array(
				'id_aplicar'=>$id_aplicar
			)
		);
		return $resultSet->toArray();
   	}


	public function addEvaluarcriterio($id_aplicar, $id_criterio, $id_usuario){
   		$array=array(
			'id_aplicar'=>$id_aplicar,
			'id_criterio'=>$id_criterio,
			'id_usuario'=>$id_usuario
     	);
     	$this->insert($array);
		return 1;
	}

    public function updateEvaluarcriterio($data = array(), $id, $id2, $id_usuario)
    {
        $array = array(
            'evaluacion_cuantitativa' => $data->evaluacion_cuantitativa,
            'evaluacion_cualitativa' => $data->evaluacion_cualitativa,
            'id_usuario' => $id_usuario
        );
        
        $this->update($array, array(
            'id_aplicar'=>$id,
			'id_criterio'=>$id2
        ));
        return 1;
    }

    public function updateEvaluarcriterioT($id)
    {
        $array = array(
            'evaluacion_cuantitativa' => null,
            'evaluacion_cualitativa' => null,
            'id_usuario' => 0
        );
        
        $this->update($array, array(
            'id_aplicar'=>$id
        ));
        return 1;
    }

   	public function getEvaluarcriterios(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarEvaluarcriterio($id) {
        		$this->delete(array('id' => $id));
    	}
}
?>