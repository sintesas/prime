<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Produccionesinvred extends TableGateway{
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('mri_produccionesinvred', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addProduccioninvred($data=array(), $id, $archi){
		print_r($data);
		$array=array(
			'id_red'=>$id,
			'nombre'=>$data->nombre,
			'ano'=>$data->ano,
			'mes'=>$data->mes,
			'pais'=>$data->pais,
			'ciudad'=>$data->ciudad,
			'descripcion'=>$data->descripcion,
			'tipo'=>$data->tipo,
			'instituciones'=>$data->instituciones,
			'registro'=>$data->registro,
			'otra_informacion'=>$data->otra_informacion,
            'id_autor' => $data->id_autor,
            'archivo' => $archi
        );
  		$this->insert($array);
		return 1;
	}

    public function getProduccioninvred($id){
		$resultSet = $this->select(array('id_red'=>$id));
		return $resultSet->toArray();
   	}

    public function getProduccioninvredt(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarProduccioninvred($id) {
        $this->delete(array('id' => $id));
    }

    public function getOtrasproduccionesredById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateOtrasproduccionesred($id, $data = array())
    {   
        $array = array(
            'nombre' => $data->nombre,
            'descripcion' => $data->descripcion,
            'tipo' => $data->tipo,
            'pais' => $data->pais,
            'ciudad' => $data->ciudad,
            'instituciones' => $data->instituciones,
            'registro' => $data->registro,
            'otra_informacion' => $data->otra_informacion,
            'mes' => $data->mes,
            'ano' => $data->ano,
            'id_autor' => $data->id_autor
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }

    public function updatearchivoOtrasproduccionesred($id, $archi)
    {   
        $array = array(
            'archivo' => $archi
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }
}
?>