<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Agregarautorgrupo extends TableGateway{
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('mgi_autorgrupo', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addAgregarautorgrupo($id, $id2, $id3, $id4){
		$array=array(
			'id_grupo'=>$id3,
			'id_usuario'=>$id,
			'seccion'=>$id4,
			'id_objeto'=>$id2,
        );
  		$this->insert($array);
		return 1;
	}
    public function getAgregarautorgrupo($id){
		$resultSet = $this->select(array('id_grupo'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarAgregarautorgrupo($id_usuario, $id_grupo, $id_objeto, $seccion) {
        $this->delete(array(
			'id_grupo'=>$id_grupo,
			'id_usuario'=>$id_usuario,
			'seccion'=>$seccion,
			'id_objeto'=>$id_objeto,
        ));
    }

    public function getAgregarautorgrupot(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarGruposgrupo($id) {
        $this->delete(array('id' => $id));
    }

    public function updateautorgrupo($id, $data = array(), $seccion)
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