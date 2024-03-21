<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Coinvestigadores extends TableGateway
{
    
    // variables de la tabla
    private $id_red;
    private $telefono_1;
    private $telefono_2;
    private $redsocial_1;
    private $redsocial_2;
    private $redsocial_3;
    private $otro_contacto;
    private $correo_electronico;
    private $pagina_web;

    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgc_coinvestigadores', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array(), $id)
    {
        $this->id_red = $id;
        $this->telefono_1 = $datos["telefono_1"];
        $this->telefono_2 = $datos["telefono_2"];
        $this->redsocial_1 = $datos["redsocial_1"];
        $this->redsocial_2 = $datos["redsocial_2"];
        $this->redsocial_3 = $datos["redsocial_3"];
        $this->otro_contacto = $datos["otro_contacto"];
        $this->correo_electronico = $datos["correo"];
        $this->pagina_web = $datos["paginaweb"];
    }

    public function addCoinvestigadores($data, $id)
    {
        $array = array(
            'id_aplicari' => $id,
            'id_tipodocumento' => $data->tipo_documento,
            'documento' => $data->documento,
            'apellidos' => $data->apellidos,
            'nombres' => $data->nombres,
            'profesion' => $data->profesion,
            'intitucion' => $data->intitucion,
            'telefono' => $data->telefono,
            'email' => $data->email,
            'horas' => $data->horas
        );
        $this->insert($array);
        return 1;
    }

    public function getCoinvestigadoresid($id)
    {
        $resultSet = $this->select(array(
            'id_aplicari' => $id
        ));
        return $resultSet->toArray();
    }

    public function getCoinvestigadorest()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }


    public function eliminarCoinvestigadores($id)
    {
        $this->delete(array(
            'id' => $id
        ));
    }

    public function getCoinvestigadoresById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateCoinvestigadores($id, $data = array())
    {   
        $array = array(
            'id_tipodocumento' => $data->tipo_documento,
            'documento' => $data->documento,
            'apellidos' => $data->apellidos,
            'nombres' => $data->nombres,
            'profesion' => $data->profesion,
            'intitucion' => $data->intitucion,
            'telefono' => $data->telefono,
            'email' => $data->email,
            'horas' => $data->horas
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }
}
?>