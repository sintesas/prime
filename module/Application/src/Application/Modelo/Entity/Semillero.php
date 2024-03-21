<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
//use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Where;

class Semillero extends TableGateway
{
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('msi_semillerosinv', $adapter, $databaseSchema, $selectResultPrototype);
    }

    public function getSemilleroinv()
    {
       $resultSet = $this->select(function ($select) {
            $select->order(array(
                'nombre ASC'
            ));
        });
        return $resultSet->toArray();
    }

    public function getSemilleroinvById($data = array(), $id)
    {
        $array = array();
        if($data["nombre"] != ""){
           $array['upper(nombre) LIKE ?'] = "%".mb_strtoupper($data["nombre"],'utf-8'). "%";
        }
        if($data["codigo"] != ""){
           $array['upper(codigo) LIKE ?'] = "%".mb_strtoupper($data["codigo"],'utf-8'). "%";
        }
        $resultSet = $this->select($array);
        $row = $resultSet->toArray();
        return $row;
    }

    public function addSemilleroinv($data = array())
    {
        $array = array();
        if( $data["nombre"] != ""){
            $array['nombre'] = $data["nombre"];
        }

        if( $data["codigo"] != ""){
            $array['codigo'] = $data["codigo"];
        }

        if( $data["coordinador_1"] != ""){
            $array['coordinador_uno'] = $data["coordinador_1"];
        }

        if( $data["coordinador_2"] != ""){
            $array['coordinador_dos'] = $data["coordinador_2"];
        }

        if( $data["fecha"] != ""){
            $array['fecha_creacion'] = $data["fecha"];
        }

        if( $data["id_unidad_academica"] != ""){
            $array['id_unidad_academica'] = $data["id_unidad_academica"];
        }

        if( $data["id_dependencia_academica"] != ""){
            $arraPos=explode("-", $data["id_dependencia_academica"]);
            $array['id_dependencia_academica'] = $arraPos[0];
        }

        if( $data["id_programa_academico"] != ""){
            $arraPos=explode("-", $data["id_programa_academico"]);
            $array['id_programa_academico'] = $arraPos[0];
        }

        if( $data["estado"] != ""){
            $array['estado'] = $data["estado"];
        }
        $this->insert($array);
        return 1;
    }

    public function eliminarSemillero($id)
    {
        $this->delete(array(
            'id' => $id
        ));
    }

    public function getSemilleroinvid($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateSemilleroinv($id, $data = array(), $estadoAnt)
    {
        $array = array();
        if($data["codigo"] != ""){
            $array['codigo'] = $data["codigo"];
        }
        if($data["nombre"] != ""){
            $array['nombre'] = $data["nombre"];
        }
        if($data->estado != ""){
             $array['estado'] = $data->estado;;
        }
        if($data["coordinador_1"] != ""){
            $array['coordinador_uno'] = $data["coordinador_1"];
        }
        if($data["coordinador_2"] != ""){
            $array['coordinador_dos'] = $data["coordinador_2"];
        }
        $array['estudiantes'] = $data["estudiantes"];
        $array['fecha_creacion'] = $data["fecha_creacion"];
        $array['id_unidad_academica'] = $data["id_unidad_academica"];
        $arraPos=explode("-", $data["id_dependencia_academica"]);
        $array['id_dependencia_academica'] = $arraPos[0];
        $arraPos=explode("-", $data["id_programa_academico"]);
        $array['id_programa_academico'] = $arraPos[0];
        $array['que_entiende'] = $data->que_entiende;
        $array['objetivo_general'] = $data->objetivo_general;
        $array['objetivo_especifico'] = $data->objetivo_especifico;
        $array['actividades'] = $data->actividades;
        $array['relacion_procesos'] = $data->relacion_procesos;
        $array['relacion_instituciones'] = $data->relacion_instituciones;
        if($estadoAnt != $array['estado']){
            $array['fecha_estado'] = now;
        }
        
        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }

    public function getSemilleroByYear()
    {
        $sql = "SELECT date_part('year', fecha_creacion) as ano, count(id) as cantidad FROM msi_semillerosinv group by date_part('year', fecha_creacion) order by date_part('year', fecha_creacion) ASC;";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
    }

    public function getSemilleroByEstadoYear()
    {
        $sql = "SELECT date_part('year', fecha_creacion) as ano, estado, count(*) as cantidad FROM msi_semillerosinv group by date_part('year', fecha_creacion), estado order by date_part('year', fecha_creacion), estado ASC;";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
    }

    public function getSemilleroFiltro($data = array())
    {
        $array = array();
        if($data["codigo"] != ""){
           $array['upper(codigo) LIKE ?'] = "%".mb_strtoupper($data["codigo"],'utf-8'). "%";
        }
        if($data["nombre"] != ""){
           $array['upper(nombre) LIKE ?'] = "%".mb_strtoupper($data["nombre"],'utf-8'). "%";
        }
        if($data["unidad"] != ""){
           $array['id_unidad_academica = ?'] = $data["unidad"];
        }
        if($data["dependencia"] != ""){
           $array['id_dependencia_academica = ?'] = $data["dependencia"];
        }
        if($data["programa"] != ""){
           $array['id_programa_academico = ?'] = $data["programa"];
        }
        if($data["ano"] != ""){
           $array['EXTRACT(YEAR FROM fecha_creacion) = ?'] = $data["ano"];
        }
        if($data["coordinador_1"] != ""){
           $array['coordinador_uno = ?'] = $data["coordinador_1"];
        }
        if($data["coordinador_2"] != ""){
           $array['coordinador_dos = ?'] = $data["coordinador_2"];
        }
        $resultSet = $this->select($array);
        $row = $resultSet->toArray();
        return $row;
    }

}

?>