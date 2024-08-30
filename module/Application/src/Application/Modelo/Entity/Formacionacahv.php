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

    public function getReportesbyFormacionAcahv($id) {
		$sql = "select u.id_usuario, fn_get_nombres(a.id_usuario) nombre_completo, u.documento, a.id_formacion_aca, a.tipo_formacion, a.titulo_obtenido, a.fecha_inicio, a.fecha_grado, a.id_pais pais, a.horas, a.id_ciudad ciudad, fn_get_valores_flexibles(a.id_departamento) departamento, a.fecha_fin, a.nombre_formacion, fn_get_valores_flexibles(a.institucion) institucion, a.archivo from aps_hv_formacion_aca a left join aps_usuarios u on a.id_usuario = u.id_usuario where u.id_usuario is not null and a.id_usuario = " . $id . ";";
		$statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
	}
}
?>