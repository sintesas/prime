<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Contratacionpersonal extends TableGateway
{
    
    // variables de la tabla
    private $id_red;
    private $servicios;
    private $noticias;
    private $eventos;

    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgc_contratacionpersonal', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array(), $id)
    {
        $this->servicios = $datos["servicios"];
        $this->noticias = $datos["noticias"];
        $this->eventos = $datos["eventos"];
        $this->id_red = $id;
    }

    public function addContratacionpersonal($data, $id)
    {
        $array = array(
            'id_aplicari' => $id,
            'tipo_vinculacion' => $data->tipo_vinculacion,
            'personas' => $data->personas,
            'objeto' => $data->objeto,
            'justificacion' => $data->justificacion,
            'valor' => $data->valor
        );
        $this->insert($array);
        return 1;
    }

    public function getContratacionpersonalid($id)
    {
        $resultSet = $this->select(array(
            'id_aplicari' => $id
        ));
        return $resultSet->toArray();
    }

    public function getContratacionpersonalt()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }


    public function eliminarContratacionpersonal($id)
    {
        $this->delete(array(
            'id' => $id
        ));
    }

    public function getContratacionpersonalById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateContratacionpersonal($id, $data = array())
    {   
        $array = array(
            'tipo_vinculacion' => $data->tipo_vinculacion,
            'personas' => $data->personas,
            'objeto' => $data->objeto,
            'justificacion' => $data->justificacion,
            'valor' => $data->valor
        );
        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }
}
?>