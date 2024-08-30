<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Eventossemi extends TableGateway{
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('msi_eventossemi', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addEventossemi($data=array(), $id, $archi){
		$array=array(
			'id_red'=>$id,
            'id_tipoevento'=>$data->id_tipoevento,
            'id_tipoparticipacion'=>$data->id_tipoparticipacion,
            'nombre_evento'=>$data->nombre_evento,
            'nombre_trabajo'=>$data->nombre_trabajo,
            'id_institucion'=>$data->id_institucion,
            'ciudad_trabajo'=>$data->ciudad_trabajo,
            'fecha_inicio'=>$data->fecha_inicio,
            'fecha_fin'=>$data->fecha_fin,
            'otra_informacion'=>$data->otra_informacion,
            'id_tipomedio'=>$data->id_tipomedio,
            'nombre_trabajo_medio'=>$data->nombre_trabajo_medio,
            'id_autor'=>$data->id_autor,
            'id_institucion_medio'=>$data->id_institucion_medio,
            'ciudad_medio'=>$data->ciudad_medio,
            'medio_divulgacion'=>$data->medio_divulgacion,
            'fecha_medio'=>$data->fecha_medio,
            'descripcion_medio'=>$data->descripcion_medio,
            'otra_informacion_medio'=>$data->otra_informacion_medio,
            'archivo' => $archi
        );
  		$this->insert($array);
		return $this->getAdapter()->getDriver()->getConnection()->getLastGeneratedValue("msi_eventossemi_id_evento_seq");
	}

    public function getEventossemi($id){
		$resultSet = $this->select(array('id_red'=>$id));
		return $resultSet->toArray();
   	}

    public function getEventossemit(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarEventossemi($id) {
        $this->delete(array('id_evento' => $id));
    }

    public function getEventossemiById($id)
    {
        $resultSet = $this->select(array(
            'id_evento' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateEventossemi($id, $data = array())
    {   
        $array = array(
            'id_tipoevento'=>$data->id_tipoevento,
            'id_tipoparticipacion'=>$data->id_tipoparticipacion,
            'nombre_evento'=>$data->nombre_evento,
            'nombre_trabajo'=>$data->nombre_trabajo,
            'id_institucion'=>$data->id_institucion,
            'ciudad_trabajo'=>$data->ciudad_trabajo,
            'fecha_inicio'=>$data->fecha_inicio,
            'fecha_fin'=>$data->fecha_fin,
            'otra_informacion'=>$data->otra_informacion,
            'id_tipomedio'=>$data->id_tipomedio,
            'nombre_trabajo_medio'=>$data->nombre_trabajo_medio,
            'id_autor'=>$data->id_autor,
            'id_institucion_medio'=>$data->id_institucion_medio,
            'ciudad_medio'=>$data->ciudad_medio,
            'medio_divulgacion'=>$data->medio_divulgacion,
            'fecha_medio'=>$data->fecha_medio,
            'descripcion_medio'=>$data->descripcion_medio,
            'otra_informacion_medio'=>$data->otra_informacion_medio,
        );
        $this->update($array, array(
            'id_evento' => $id
        ));
        return 1;
    }

    public function updatearchivoEventossemi($id, $archi)
    {   
        $array = array(
            'archivo' => $archi
        );

        $this->update($array, array(
            'id_evento' => $id
        ));
        return 1;
    }

    public function getReportesbyDivulgacion($id) {
        $sql = "select s.id id_semillero, s.nombre nombre_semillero, s.codigo codigo_semillero, e.id_evento, (select v.descripcion_valor from aps_valores_flexibles v where v.id_valor_flexible = e.id_tipoevento) tipo_evento, (select v.descripcion_valor from aps_valores_flexibles v where v.id_valor_flexible = e.id_tipoparticipacion) tipo_participacion, e.nombre_evento, e.nombre_trabajo, (select v.descripcion_valor from aps_valores_flexibles v where v.id_valor_flexible = e.id_institucion) institucion, e.ciudad_trabajo, e.fecha_inicio, e.fecha_fin, e.otra_informacion, (select v.descripcion_valor from aps_valores_flexibles v where v.id_valor_flexible = e.id_tipomedio) tipo_medio, e.nombre_trabajo_medio, (select concat(u.nombres, ' ', u.apellidos) from vw_usuarios_personal u where u.id_usuario = e.id_autor) autor, (select v.descripcion_valor from aps_valores_flexibles v where v.id_valor_flexible = e.id_institucion_medio) institucion_medio, e.ciudad_medio, e.medio_divulgacion, e.fecha_medio, e.descripcion_medio, e.otra_informacion_medio from msi_eventossemi e left join msi_semillerosinv s on e.id_red = s.id where e.id_red = " . $id . ";";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
    }
}
?>