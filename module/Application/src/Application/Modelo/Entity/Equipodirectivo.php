<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Equipodirectivo extends TableGateway
{
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mri_equipodirectivo', $adapter, $databaseSchema, $selectResultPrototype);
    }
    
    public function addEquipodirectivo($data, $id, $file_name)
    {
       
        $array = array(
            'id_red' => $id,
            'cargo' => $data["cargo"],
            'nombre' => $data["nombre"],
            'institucion' => $data["institucion"],
            'pais' => $data["pais"],
            'archivo' => $file_name
        );
        $this->insert($array);   
        return 1;
    }

    public function getEquipodirectivo($id)
    {
        $resultSet = $this->select(array(
            'id_red' => $id
        ));
        return $resultSet->toArray();
    }

    public function getEquipodirectivot()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }

    public function eliminarEquipodirectivo($id)
    {
        $this->delete(array(
            'id' => $id
        ));
    }

    public function getEquipoById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateEquipo($id, $data = array(), $file_name)
    {   
        $array = array(
            'cargo' => $data->cargo,
            'nombre' => $data->nombre,
            'institucion' => $data->institucion,
            'pais' => $data->pais,
            'archivo' => $file_name
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }
}
?>