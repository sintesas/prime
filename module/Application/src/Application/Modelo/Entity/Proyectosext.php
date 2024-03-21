<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Proyectosext extends TableGateway
{
    
    // variables de la tabla
    private $codigo_proyecto;

    private $tipo_proyecto;

    private $nombre_proyecto;

    private $fecha_inicio;

    private $fecha_fin;

    private $resumen_ejecutivo;

    private $objetivo_general;

    private $equipo_trabajo;

    private $productos_derivados;
    
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgi_gi_proyectos_ext', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array())
    {
        $this->codigo_proyecto = $datos["codigo_proyecto"];
        $this->tipo_proyecto = $datos["tipo_proyecto"];
        $this->nombre_proyecto = $datos["nombre_proyecto"];
        $this->fecha_inicio = $datos["fecha_inicio"];
        $this->fecha_fin = $datos["fecha_fin"];
        $this->resumen_ejecutivo = $datos["resumen_ejecutivo"];
        $this->objetivo_general = $datos["objetivo_general"];
        $this->equipo_trabajo = $datos["equipo_trabajo"];
        $this->productos_derivados = $datos["productos_derivados"];
    }

    public function addProyectosext($data = array(), $id, $archi)
    {
        self::cargaAtributos($data);
        
        $array = array(
            'id_grupo_inv' => $id,
            'codigo_proyecto' => $this->codigo_proyecto,
            'tipo_proyecto' => $this->tipo_proyecto,
            'nombre_proyecto' => $this->nombre_proyecto,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'resumen_ejecutivo' => $this->resumen_ejecutivo,
            'objetivo_general' => $this->objetivo_general,
            'equipo_trabajo' => $this->equipo_trabajo,
            'productos_derivados' => $this->productos_derivados,
            'archivo' => $archi
        );
        $this->insert($array);
        return 1;
    }

    public function getProyectosext($id)
    {
        $resultSet = $this->select(array(
            'id_grupo_inv' => $id
        ));
        return $resultSet->toArray();
    }

    public function getProyectosextt()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }

    public function eliminarProyectosext($id)
    {
        $this->delete(array(
            'id_proyecto_ext' => $id
        ));
    }

    public function getProyectosextById($id)
    {
        $resultSet = $this->select(array(
            'id_proyecto_ext' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateProyectosext($id, $data = array())
    {   
        $array = array(
            'codigo_proyecto' => $data->codigo_proyecto,
            'fecha_fin' => $data->fecha_fin,
            'fecha_inicio' => $data->fecha_inicio,
            'resumen_ejecutivo' => $data->resumen_ejecutivo,
            'objetivo_general' => $data->objetivo_general,
            'productos_derivados' => $data->productos_derivados,
            'nombre_proyecto' => $data->nombre_proyecto
        );

        $this->update($array, array(
            'id_proyecto_ext' => $id
        ));
        return 1;
    }

    public function updatearchivoProyectosext($id, $archi)
    {   
        $array = array(
            'archivo' => $archi
        );

        $this->update($array, array(
            'id_proyecto_ext' => $id
        ));
        return 1;
    }
}
?>