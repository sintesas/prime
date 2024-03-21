<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Identificadoressemi extends TableGateway{
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('msi_identificadoressemi', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addIdentificadoressemi($data=array(), $id, $archi){
		$array=array(
			'id_red'=>$id,
            'id_tipoidentificador'=>$data->id_tipoidentificador,
            'id_tipocategoria'=>$data->id_tipocategoria,
            'id_field'=>$data->id_field,
            'fecha_registro'=>$data->fecha_registro,
            'nombre'=>$data->nombre,
            'web'=>$data->web,
			'ciudad'=>$data->ciudad,
            'descripcion'=>$data->descripcion,
            'otra_informacion'=>$data->otra_informacion,
            'archivo' => $archi
        );
  		$this->insert($array);
		return $this->getAdapter()->getDriver()->getConnection()->getLastGeneratedValue("msi_identificadoressemi_id_identificador_seq");
	}

    public function getIdentificadoressemi($id){
		$resultSet = $this->select(array('id_red'=>$id));
		return $resultSet->toArray();
   	}

    public function getIdentificadoressemit(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarIdentificadoressemi($id) {
        $this->delete(array('id_identificador' => $id));
    }

    public function getIdentificadoressemiById($id)
    {
        $resultSet = $this->select(array(
            'id_identificador' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateIdentificadoressemi($id, $data = array())
    {   
        $array = array(
            'id_tipoidentificador'=>$data->id_tipoidentificador,
            'id_tipocategoria'=>$data->id_tipocategoria,
            'id_field'=>$data->id_field,
            'fecha_registro'=>$data->fecha_registro,
            'nombre'=>$data->nombre,
            'web'=>$data->web,
            'ciudad'=>$data->ciudad,
            'descripcion'=>$data->descripcion,
            'otra_informacion'=>$data->otra_informacion
        );
        $this->update($array, array(
            'id_identificador' => $id
        ));
        return 1;
    }

    public function updatearchivoIdentificadoressemi($id, $archi)
    {   
        $array = array(
            'archivo' => $archi
        );

        $this->update($array, array(
            'id_identificador' => $id
        ));
        return 1;
    }
}
?>