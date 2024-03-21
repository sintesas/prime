<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Produccionesinvsemillero extends TableGateway{
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('msi_produccionesinvsemillero', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addProduccioninvsemillero($data=array(), $id, $archi){
		print_r($data);
		$array=array(
			'id_semillero'=>$id,
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

    public function getProduccioninvsemillero($id){
		$resultSet = $this->select(array('id_semillero'=>$id));
		return $resultSet->toArray();
   	}

    public function getProduccioninvsemillerot(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarProduccioninvsemillero($id) {
        		$this->delete(array('id' => $id));
    }

    public function getOtrasproduccionessemilleroById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateOtrasproduccionessemillero($id, $data = array())
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

    public function updatearchivoOtrasproduccionessemillero($id, $archi)
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