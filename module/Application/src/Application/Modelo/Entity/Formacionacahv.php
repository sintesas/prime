<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Formacionacahv extends TableGateway
{
    
    // variables de la tabla
    private $tipo_formacion;

    private $titulo_obtenido;

    private $institucion;

    private $fecha_inicio;

    private $id_ciudad;

    private $id_departamento;

    private $fecha_grado;

    private $id_pais;

    private $horas;
    
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('aps_hv_formacion_aca', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array()){
        $this->tipo_formacion = $datos["tipo_formacion"];
        $this->titulo_obtenido = $datos["titulo_formacion"];
        $this->institucion = $datos["institucion"];
        $this->fecha_inicio = $datos["fecha_inicio"];
        $this->fecha_grado = $datos["fecha_grado"];
        $this->horas = $datos["horas"];
        $this->id_pais = $datos["pais"];
        $this->id_ciudad = $datos["ciudad"];
    }

    public function addFormacionacahv($data = array(), $id, $file_name)
    {
        self::cargaAtributos($data);
        
        $array = array(
            'id_usuario' => $id
        );

        if($this->id_pais!=""){
            $array["id_pais"] = $this->id_pais;
        }

        if($data->fecha_fin!=""){
            $array["fecha_fin"] = $data->fecha_fin;
        }

        if($data->nombre_formacion!=""){
            $array["nombre_formacion"] = $data->nombre_formacion;
        }

        if($this->horas!=""){
            $array["horas"] = $this->horas;
        }

        if($this->id_ciudad!=""){
            $array["id_ciudad"] = $this->id_ciudad;
        }

        if($this->fecha_grado!=""){
            $array["fecha_grado"] = $this->fecha_grado;
        }

        if($this->fecha_inicio!=""){
            $array["fecha_inicio"] = $this->fecha_inicio;
        }

        if($this->institucion!=""){
            $array["institucion"] = $this->institucion;
        }

        if($data->titulo_formacion != ""){
            $array["titulo_obtenido"] = $data->titulo_formacion;
        }

        if($this->tipo_formacion!=""){
            $array["tipo_formacion"] = $this->tipo_formacion;
        }

        $array["archivo"] = $file_name;

        $this->insert($array);
        return 1;
    }

    public function getFormacionacahv($id)
    {
        $resultSet = $this->select(array(
            'id_usuario' => $id
        ));
        return $resultSet->toArray();
    }

    public function getFormacionacahvt()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }

    public function eliminarFormacionacahv($id)
    {
        $this->delete(array(
            'id_formacion_aca' => $id
        ));
    }

    public function getFormacionacahvById($id)
    {
        $resultSet = $this->select(array(
            'id_formacion_aca' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateFormacionacahv($id, $data = array())
    {   
        $array=array(
            'tipo_formacion'=>$data->tipo_formacion,
            'titulo_obtenido'=>$data->titulo_formacion,
            'nombre_formacion'=>$data->nombre_formacion,
            'institucion'=>$data->institucion,
            'fecha_inicio'=>$data->fecha_inicio,
            'fecha_grado'=>$data->fecha_grado,
            'fecha_fin'=>$data->fecha_fin,
            'id_pais'=>$data->pais,
            'id_ciudad'=>$data->ciudad,
            'tipo_formacion'=>$data->tipo_formacion,
            'archivo' => $file_name
        );

        $this->update($array, array(
            'id_formacion_aca' => $id
        ));
    }
}
?>