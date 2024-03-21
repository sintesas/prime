<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Contactored extends TableGateway
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
        return parent::__construct('mri_contactored', $adapter, $databaseSchema, $selectResultPrototype);
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

    public function addContactored($data, $id, $file_name)
    {
        self::cargaAtributos($data, $id);
        $array = array(
            'id_red' => $this->id_red,
            'telefono_1' => $this->telefono_1,
            'telefono_2' => $this->telefono_2,
            'redsocial_1' => $this->redsocial_1,
            'redsocial_2' => $this->redsocial_2,
            'redsocial_3' => $this->redsocial_3,
            'otro_contacto' => $this->otro_contacto,
            'correo_electronico' => $this->correo_electronico,
            'pagina_web' => $this->pagina_web,
            'archivo' => $file_name
        );
        $this->insert($array);
        return 1;
    }

    public function getContactoredid($id)
    {
        $resultSet = $this->select(array(
            'id_red' => $id
        ));
        return $resultSet->toArray();
    }

    public function getContactoredt()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }


    public function eliminarContactored($id)
    {
        $this->delete(array(
            'id' => $id
        ));
    }

    public function getContactoredById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateContactored($id, $data = array(), $file_name)
    {   
        $array = array(
            'telefono_1' => $data->telefono_1,
            'telefono_2' => $data->telefono_2,
            'redsocial_1' => $data->redsocial_1,
            'redsocial_2' => $data->redsocial_2,
            'redsocial_3' => $data->redsocial_3,
            'otro_contacto' => $data->otro_contacto,
            'correo_electronico' => $data->correo,
            'pagina_web' => $data->paginaweb,
            'archivo' => $file_name
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }
}
?>