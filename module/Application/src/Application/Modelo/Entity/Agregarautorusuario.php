<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Agregarautorusuario extends TableGateway{
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('aps_hv_autorusuario', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addAgregarautorusuario($id, $id2, $id3, $id4){
		$array=array(
			'id_grupo'=>$id3,
			'id_usuario'=>$id,
			'seccion'=>$id4,
			'id_objeto'=>$id2,
        );
  		$this->insert($array);
		return 1;
	}
    public function getAgregarautorusuario($id){
		$resultSet = $this->select(array('id_grupo'=>$id));
		return $resultSet->toArray();
   	}

    public function getAgregarautorusuariot(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarAgregarautorusuario($id, $id2, $id3, $id4) {
        $this->delete(
        	array(
        		'id_grupo' => $id3,
        		'id_usuario'=>$id,
				'seccion'=>$id4,
				'id_objeto'=>$id2,
        	)
        );
    }

    public function updateautorusuario($id, $data = array(), $seccion)
    {   
        $array = array(
            'id_usuario' => $data->id_autor
        );

        $this->update($array, array(
            'id_objeto' => $id,
            'seccion' => $seccion
        ));
    }
}
?>