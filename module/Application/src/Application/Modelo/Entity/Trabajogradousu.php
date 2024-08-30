<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Trabajogradousu extends TableGateway{
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('aps_trabajogradousu', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addTrabajogradousu($data=array(), $id, $archi){
        $idDependenciaAcademica = "";
        $idProgramaAcademico = "";
        if ($data["id_dependencia"] != '') {
            $idDependenciaAcademica = explode("-", $data["id_dependencia"]);
        }
        if ($data["id_programa"] != '') {
            $idProgramaAcademico = explode("-", $data["id_programa"]);
        }
		$array=array(
			'id_red'=>$id,
            'nombre_trabajo'=>$data->nombre_trabajo,
            'id_tipotrabajo'=>$data->id_tipotrabajo,
            'id_estadotipotrabajo'=>$data->id_estadotipotrabajo,
            'id_tipoparticipacion'=>$data->id_tipoparticipacion,
            'id_autor'=>$data->id_autor,
            'id_institucion'=>$data->id_institucion,
            'ciudad_trabajo'=>$data->ciudad_trabajo,
            'id_unidad'=>$data->id_unidad,
            'id_dependencia'=>$idDependenciaAcademica[0],
            'id_programa'=>$idProgramaAcademico[0],
            'fecha_inicio'=>$data->fecha_inicio,
            'fecha_fin'=>$data->fecha_fin,
            'descripcion'=>$data->descripcion,
            'otra_informacion'=>$data->otra_informacion,
            'id_formacioninvestigador'=>$data->id_formacioninvestigador,
            'codigo_proyecto'=>$data->codigo_proyecto,
            'nombre_proyecto'=>$data->nombre_proyecto,
            'id_institucion_proyecto'=>$data->id_institucion_proyecto,
            'ciudad_proyecto'=>$data->ciudad_proyecto,
            'personas_formadas'=>$data->personas_formadas,
            'fecha_inicio_proyecto'=>$data->fecha_inicio_proyecto,
            'fecha_fin_proyecto'=>$data->fecha_fin_proyecto,
            'descripcion_proyecto'=>$data->descripcion_proyecto,
            'descripcion_formacion'=>$data->descripcion_formacion,
            'otra_informacion_proyecto'=>$data->otra_informacion_proyecto,
            'id_semillero'=>$data->id_semillero,
            'id_institucion_semillero'=>$data->id_institucion_semillero,
            'id_rolparticipacion'=>$data->id_rolparticipacion,
            'fecha_inicio_semillero'=>$data->fecha_inicio_semillero,
            'fecha_fin_semillero'=>$data->fecha_fin_semillero,
            'tematica'=>$data->tematica,
            'descripcion_semillero'=>$data->descripcion_semillero,
            'id_investigador'=>$data->id_investigador,    
            'archivo' => $archi
        );
  		$this->insert($array);
		return $this->getAdapter()->getDriver()->getConnection()->getLastGeneratedValue("aps_trabajogradousu_id_trabajogrado_seq");
	}

    public function getTrabajogradousu($id){
		$resultSet = $this->select(array('id_red'=>$id));
		return $resultSet->toArray();
   	}

    public function getTrabajogradousut(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarTrabajogradousu($id) {
        $this->delete(array('id_trabajogrado' => $id));
    }

    public function getTrabajogradousuById($id)
    {
        $resultSet = $this->select(array(
            'id_trabajogrado' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateTrabajogradousu($id, $data = array())
    {   
        $idDependenciaAcademica = "";
        $idProgramaAcademico = "";
        if ($data["id_dependencia"] != '') {
            $idDependenciaAcademica = explode("-", $data["id_dependencia"]);
        }
        if ($data["id_programa"] != '') {
            $idProgramaAcademico = explode("-", $data["id_programa"]);
        }
        $array = array(
            'nombre_trabajo'=>$data->nombre_trabajo,
            'id_tipotrabajo'=>$data->id_tipotrabajo,
            'id_estadotipotrabajo'=>$data->id_estadotipotrabajo,
            'id_tipoparticipacion'=>$data->id_tipoparticipacion,
            'id_autor'=>$data->id_autor,
            'id_institucion'=>$data->id_institucion,
            'ciudad_trabajo'=>$data->ciudad_trabajo,
            'id_unidad'=>$data->id_unidad,
            'id_dependencia'=>$idDependenciaAcademica[0],
            'id_programa'=>$idProgramaAcademico[0],
            'fecha_inicio'=>$data->fecha_inicio,
            'fecha_fin'=>$data->fecha_fin,
            'descripcion'=>$data->descripcion,
            'otra_informacion'=>$data->otra_informacion,
            'id_formacioninvestigador'=>$data->id_formacioninvestigador,
            'codigo_proyecto'=>$data->codigo_proyecto,
            'nombre_proyecto'=>$data->nombre_proyecto,
            'id_institucion_proyecto'=>$data->id_institucion_proyecto,
            'ciudad_proyecto'=>$data->ciudad_proyecto,
            'personas_formadas'=>$data->personas_formadas,
            'fecha_inicio_proyecto'=>$data->fecha_inicio_proyecto,
            'fecha_fin_proyecto'=>$data->fecha_fin_proyecto,
            'descripcion_proyecto'=>$data->descripcion_proyecto,
            'descripcion_formacion'=>$data->descripcion_formacion,
            'otra_informacion_proyecto'=>$data->otra_informacion_proyecto,
            'id_semillero'=>$data->id_semillero,
            'id_institucion_semillero'=>$data->id_institucion_semillero,
            'id_rolparticipacion'=>$data->id_rolparticipacion,
            'fecha_inicio_semillero'=>$data->fecha_inicio_semillero,
            'fecha_fin_semillero'=>$data->fecha_fin_semillero,
            'tematica'=>$data->tematica,
            'descripcion_semillero'=>$data->descripcion_semillero,
            'id_investigador'=>$data->id_investigador
        );
        $this->update($array, array(
            'id_trabajogrado' => $id
        ));
        return 1;
    }

    public function updatearchivoTrabajogradousu($id, $archi)
    {   
        $array = array(
            'archivo' => $archi
        );

        $this->update($array, array(
            'id_trabajogrado' => $id
        ));
        return 1;
    }

    public function getReportesbyFormacion($id) {
        $sql = "select u.id_usuario, fn_get_nombres(u.id_usuario) nombre_completo, u.documento, t.id_trabajogrado, t.nombre_trabajo, (select v.descripcion_valor from aps_valores_flexibles v where v.id_valor_flexible = t.id_tipotrabajo) tipo_trabajo, (select v.descripcion_valor from aps_valores_flexibles v where v.id_valor_flexible = t.id_estadotipotrabajo) estado_tipo_trabajo, (select v.descripcion_valor from aps_valores_flexibles v where v.id_valor_flexible = t.id_tipoparticipacion) tipo_participacion, (select concat(u.nombres, ' ', u.apellidos) from vw_usuarios_personal u where u.id_usuario = t.id_autor) autor, (select v.descripcion_valor from aps_valores_flexibles v where v.id_valor_flexible = t.id_institucion) institucion, t.ciudad_trabajo, (select v.descripcion_valor from aps_valores_flexibles v where v.id_valor_flexible = t.id_unidad) unidad, (select v.descripcion_valor from aps_valores_flexibles v where v.id_valor_flexible = t.id_dependencia) dependencia, (select v.descripcion_valor from aps_valores_flexibles v where v.id_valor_flexible = t.id_programa) programa, t.fecha_inicio, t.fecha_fin, t.descripcion, t.otra_informacion, (select v.descripcion_valor from aps_valores_flexibles v where v.id_valor_flexible = t.id_formacioninvestigador) tipo_formacion, t.ciudad_proyecto, t.nombre_proyecto, (select v.descripcion_valor from aps_valores_flexibles v where v.id_valor_flexible = t.id_institucion_proyecto) institucion_proyecto, t.personas_formadas, t.fecha_inicio_proyecto, t.fecha_fin_proyecto, t.descripcion_proyecto, t.descripcion_formacion, t.otra_informacion_proyecto, (select concat(si.codigo, ' ', si.nombre) from msi_semillerosinv si where si.id = t.id_semillero) semillero, (select v.descripcion_valor from aps_valores_flexibles v where v.id_valor_flexible = t.id_rolparticipacion) rol_participacion, t.fecha_inicio_semillero, t.fecha_fin_semillero, t.tematica, t.descripcion_semillero, t.archivo from aps_trabajogradousu t left join aps_usuarios u on t.id_red = u.id_usuario where u.id_usuario is not null and t.id_red = " . $id . ";";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
    }
}
?>