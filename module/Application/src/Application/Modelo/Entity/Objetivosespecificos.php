<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Objetivosespecificos extends TableGateway
{
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgc_objetivosespecificos', $adapter, $databaseSchema, $selectResultPrototype);
    }
    
    public function addObjetivosespecificos($data, $id)
    {
        $array = array(
            'id_aplicar' => $id,
            'objetivo' => $data["objetivo"]
        );
        $this->insert($array);   
        return 1;
    }

    public function getObjetivosespecificos($id)
    {
        $resultSet = $this->select(array(
            'id_aplicar' => $id
        ));
        return $resultSet->toArray();
    }

    public function getObjetivosespecificost()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }

    public function eliminarObjetivosespecificos($id)
    {
        $this->delete(array(
            'id' => $id
        ));
    }

    public function getObjetivoById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateObjetivo($id, $data = array())
    {   
        $array = array(
            'objetivo' => $data->objetivo
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }
}
?>