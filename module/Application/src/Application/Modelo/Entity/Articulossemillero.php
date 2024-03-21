<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Articulossemillero extends TableGateway{
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('msi_articulosemillero', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addArticulossemillero($data=array(), $id, $archi){
		$array=array(
			'id_semillero'=>$id,
			'nombre_revista'=>$data->nombre_revista,
			'nombre_articulo'=>$data->nombre_articulo,
			'ano'=>$data->ano,
			'mes'=>$data->mes,
			'pais'=>$data->pais,
			'ciudad'=>$data->ciudad,
			'issn'=>$data->issn,
			'paginas'=>$data->paginas,
			'pagina_inicio'=>$data->pagina_inicio,
			'pagina_fin'=>$data->pagina_fin,
			'fasciculo'=>$data->fasciculo,
			'volumen'=>$data->volumen,
			'serie'=>$data->serie,
			'categoria' => $data->categoria,
            'id_autor' => $data->id_autor,
            'archivo' => $archi
        );
        $this->insert($array);
        return 1;
	}

    public function getArticulossemillero($id){
		$resultSet = $this->select(array('id_semillero'=>$id));
		return $resultSet->toArray();
   	}

    public function getArticulost(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarArticulossemillero($id) {
        		$this->delete(array('id' => $id));
    }

    public function getArticulosemilleroById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updatArticulossemillero($id, $data = array())
    {   
        $array = array(
            'nombre_revista' => $data->nombre_revista,
            'nombre_articulo' => $data->nombre_articulo,
            'pais' => $data->pais,
            'issn' => $data->issn,
            'paginas' => $data->paginas,
            'volumen' => $data->volumen,
            'serie' => $data->serie,
            'pagina_inicio' => $data->pagina_inicio,
            'pagina_fin' => $data->pagina_fin,
            'fasciculo' => $data->fasciculo,
            'ciudad' => $data->ciudad,
            'ano' => $data->ano,
            'mes' => $data->mes,
            'categoria' => $data->categoria,
            'id_autor' => $data->id_autor
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }

    public function updatearchivoArticulossemillero($id, $archi)
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