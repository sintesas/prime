<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Objetivosmetas extends TableGateway
{
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgc_objetivometas', $adapter, $databaseSchema, $selectResultPrototype);
    }
    
    public function addObjetivosmetas($data, $id)
    {
        $array = array(
            'id_objetivo' => $id,
            'meta' => $data["meta"]
        );
        $this->insert($array);   
        return 1;
    }

    public function getObjetivosmetas($id)
    {
        $resultSet = $this->select(array(
            'id_objetivo' => $id
        ));
        return $resultSet->toArray();
    }

    public function getObjetivosmetast()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }

    public function eliminarObjetivosmetas($id)
    {
        $this->delete(array(
            'id' => $id
        ));
    }

    public function getObjetivometaById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateObjetivometa($id, $data = array())
    {   
        $array = array(
            'meta' => $data->meta
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }
}
?>