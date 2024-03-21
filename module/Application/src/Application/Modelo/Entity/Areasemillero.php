<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Areasemillero extends TableGateway
{
    

    
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('msi_area', $adapter, $databaseSchema, $selectResultPrototype);
    }

    public function addAreasemillero($data, $id, $file_name)
    {
        $array = array(
            'id_semillero' => $id,
            'tematica' => $data->tematica,
            'archivo' => $file_name           
        );
        $this->insert($array);
        return 1;
    }

    public function getAreasemillero($id)
    {
        $resultSet = $this->select(array(
            'id_semillero' => $id
        ));
        return $resultSet->toArray();
    }
    public function getAreasemillerot()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }

    public function eliminarAreasemillero($id)
    {
        $this->delete(array(
            'id' => $id
        ));
    }

    public function getAreasemilleroById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateAreasemillero($id, $data = array(), $file_name)
    {   
        $array = array(
            'tematica' => $data->tematica,
            'archivo' => $file_name
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }
}
?>