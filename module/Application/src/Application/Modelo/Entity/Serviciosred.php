<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Serviciosred extends TableGateway
{
    
    // variables de la tabla
    private $id_red;
    private $servicios;
    private $noticias;
    private $eventos;

    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mri_serviciosred', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array(), $id)
    {
        $this->servicios = $datos["servicios"];
        $this->noticias = $datos["noticias"];
        $this->eventos = $datos["eventos"];
        $this->id_red = $id;
    }

    public function addServiciored($data, $id, $file_name)
    {
        self::cargaAtributos($data, $id);
        $array = array(
            'id_red' => $this->id_red,
            'servicios' => $this->servicios,
            'noticias' => $this->noticias,
            'archivo' => $file_name
        );
        $this->insert($array);
        return 1;
    }

    public function getServiciosredid($id)
    {
        $resultSet = $this->select(array(
            'id_red' => $id
        ));
        return $resultSet->toArray();
    }

    public function getServiciosredt()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }


    public function eliminarServiciored($id)
    {
        $this->delete(array(
            'id' => $id
        ));
    }

    public function getServicioById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateServicio($id, $data = array(), $file_name)
    {   
        $array = array(
            'servicios' => $data->servicios,
            'noticias' => $data->noticias,
            'archivo' => $file_name
        );
        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }
}
?>