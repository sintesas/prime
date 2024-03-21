<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Lineas extends TableGateway
{
    
    // variables de la tabla
    private $nombre_linea;

    private $objetivo;

    private $logros;

    private $efectos;

    private $id_lineas;
    
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgi_gi_lineas', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array())
    {
        $this->nombre_linea = $datos["nombre_linea"];
        $this->objetivo = $datos["objetivo"];
        $this->logros = $datos["logros"];
        $this->efectos = $datos["efectos"];
        $this->id_lineas = (isset($datos["id_lineas"]) && $datos["id_lineas"] !="")? $datos["id_lineas"]: 0;
    }

    public function addLineas($data = array(), $id, $file_name)
    {
        self::cargaAtributos($data);
        
        $array = array(
            'id_grupo_inv' => $id,
            'nombre_linea' => $this->nombre_linea,
            'objetivo' => $this->objetivo,
            'logros' => $this->logros,
            'efectos' => $this->efectos,
            'archivo' => $file_name
        );
        $this->insert($array);
        return 1;
    }

    public function getLineas($id)
    {
        $resultSet = $this->select(array(
            'id_grupo_inv' => $id
        ));
        return $resultSet->toArray();
    }

    public function getLinea($data = array())
    {
        $filter = new StringTrim();
        self::cargaAtributos($data);
        $id = $this->id_lineas;
        
        $resultSet = $this->select(function ($select) use($id)
        {
            $select->columns(array(
                'id_grupo_inv'
            ));
            $select->where(array(
                'id_linea_inv = ?' => $id
            ));
        });
        $row = $resultSet->current();
        
        return $row;
    }

    public function getLineaById($id)
    {
        $resultSet = $this->select(array(
            'id_linea_inv' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateLineas($id, $data = array(), $file_name)
    {   
        $array = array(
            'nombre_linea' => $data->nombre_linea,
            'objetivo' => $data->objetivo,
            'logros' => $data->logros,
            'efectos' => $data->efectos,
            'archivo' => $file_name
        );

        $this->update($array, array(
            'id_linea_inv' => $id
        ));
    }

    public function getLineast()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }

    public function eliminarLineas($id)
    {
        $this->delete(array(
            'id_linea_inv' => $id
        ));
    }
}
?>