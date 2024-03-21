<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Proyectored extends TableGateway
{
    
    // variables de la tabla
    private $id_red;
    private $nombre;
    private $codigo;
    private $tipo;
    private $fecha_inicio;
    private $fecha_fin;
    private $objetivo;
    private $productos;
    private $resumen;

    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mri_proyectored', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array(), $id)
    {
        $this->id_red = $id;
        $this->nombre = $datos["nombre"];
        $this->codigo = $datos["codigo"];
        $this->tipo = $datos["tipo"];
        $this->fecha_inicio = $datos["fecha_inicio"];
        $this->fecha_fin = $datos["fecha_fin"];
        $this->objetivo = $datos["objetivo"];
        $this->productos = $datos["productos"];
        $this->resumen = $datos["resumen"];
    }

    public function addProyectored($data, $id, $archi)
    {
        self::cargaAtributos($data, $id);
        $array = array(
            'id_red' => $this->id_red,
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'tipo' => $this->tipo,
            'fecha_inicio' => $this->fecha_inicio,
            'archivo' => $archi
        );

        if($this->fecha_fin != ""){
             $array['fecha_fin'] = $this->fecha_fin;
        }
        
        if($this->objetivo != ""){
             $array['objetivo'] = $this->objetivo;
        }
        
        if($this->productos != ""){
             $array['productos'] = $this->productos;
        }
        
        if($this->resumen != ""){
             $array['resumen'] = $this->resumen;
        }
        
        $this->insert($array);
        return 1;
    }

    public function getProyectoredid($id)
    {
        $resultSet = $this->select(array(
            'id_red' => $id
        ));
        return $resultSet->toArray();
    }

    public function getProyectoredt()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }


    public function eliminarProyectored($id)
    {
        $this->delete(array(
            'id' => $id
        ));
    }

    public function getProyectosextredById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateProyectosextred($id, $data = array())
    {   
        $array = array(
            'nombre' => $data->nombre,
            'codigo' => $data->codigo,
            'fecha_inicio' => $data->fecha_inicio,
            'fecha_fin' => $data->fecha_fin,
            'resumen' => $data->resumen,
            'objetivo' => $data->objetivo,
            'productos' => $data->productos
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }

    public function updatearchivoProyectosextred($id, $archi)
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