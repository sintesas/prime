<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Reconocimientossemillero extends TableGateway
{
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('msi_reconocimientos', $adapter, $databaseSchema, $selectResultPrototype);
    }

    public function addReconocimientossemillero($data, $id, $file_name)
    {
        $array = array(
            'id_semillero' => $id,
            'nombre' => $data->nombre,
            'numero_acto' => $data->numero_acto,
            'descripcion' => $data->descripcion,
            'valor' => $data->valor,
            'fecha' => $data->fecha,
            'archivo' => $file_name
        );
        $this->insert($array);
        return 1;
    }

    public function getReconocimientossemillero($id)
    {
        $resultSet = $this->select(array(
            'id_semillero' => $id
        ));
        return $resultSet->toArray();
    }
    
    public function getReconocimientossemillerot()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }

    public function eliminarReconocimientossemillero($id)
    {
        $this->delete(array(
            'id' => $id
        ));
    }

    public function getReconoimientosemiById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateReconocimientosemi($id, $data = array(), $file_name)
    {   
        $array = array(
            'descripcion' => $data->descripcion,
            'valor' => $data->valor,
            'numero_acto' => $data->numero_acto,
            'fecha' => $data->fecha,
            'archivo' => $file_name

        );

        $this->update($array, array(
            'id' => $id
        ));
    }
}
?>