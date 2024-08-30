<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Identificadoresusu extends TableGateway{
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('aps_identificadoresusu', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addIdentificadoresusu($data=array(), $id, $archi){
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
		return $this->getAdapter()->getDriver()->getConnection()->getLastGeneratedValue("aps_identificadoresusu_id_identificador_seq");
	}

    public function getIdentificadoresusu($id){
		$resultSet = $this->select(array('id_red'=>$id));
		return $resultSet->toArray();
   	}

    public function getIdentificadoresusut(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarIdentificadoresusu($id) {
        $this->delete(array('id_identificador' => $id));
    }

    public function getIdentificadoresusuById($id)
    {
        $resultSet = $this->select(array(
            'id_identificador' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateIdentificadoresusu($id, $data = array())
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

    public function updatearchivoIdentificadoresusu($id, $archi)
    {   
        $array = array(
            'archivo' => $archi
        );

        $this->update($array, array(
            'id_identificador' => $id
        ));
        return 1;
    }

    public function getReportesbyIdentificadores($id) {
        $sql = "select u.id_usuario, fn_get_nombres(u.id_usuario) nombre_completo, u.documento, i.id_identificador, (select v.descripcion_valor from aps_valores_flexibles v where v.id_valor_flexible = i.id_tipoidentificador) tipo_identificador, (select v.descripcion_valor from aps_valores_flexibles v where v.id_valor_flexible = i.id_tipocategoria) tipo_categoria, i.id_field, i.fecha_registro, i.nombre, i.web, i.ciudad, i.descripcion, i.otra_informacion, i.archivo from aps_identificadoresusu i left join vw_usuarios_personal u on i.id_red = u.id_usuario where u.id_usuario is not null and i.id_red = " . $id . ";";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
    }
}
?>