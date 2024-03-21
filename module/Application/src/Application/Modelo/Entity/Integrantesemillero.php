<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Integrantesemillero extends TableGateway
{
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('msi_integrantes', $adapter, $databaseSchema, $selectResultPrototype);
    }

    public function addIntegrantesemillero($data, $id, $file_name)
    {
        $array = array(
            'id_semillero' => $id,
        );

        if($data->integrante!=""){
            $array["integrante"] = $data->integrante;
        }

        if($data->tipo_vinculacion!=""){
            $array["tipo_vinculacion"] = $data->tipo_vinculacion;
        }

        if($data->fecha_inicio!=""){
            $array["fecha_inicio"] = $data->fecha_inicio;
        }

        if($data->rol_participacion!=""){
            $array["rol_participacion"] = $data->rol_participacion;
        }

        if($data->estado!=""){
            $array["estado"] = $data->estado;
        }
        $array["archivo"] = $file_name;
        $this->insert($array);
        return 1;
    }

    public function getIntegrantesemilleroid($id)
    {
        $resultSet = $this->select(array(
            'id_semillero' => $id
        ));
        return $resultSet->toArray();
    }


    public function getIntegrantesemillerobyid($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function getIntegrantesemillerot()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }


    public function eliminarIntegrantesemillero($id)
    {
        $this->delete(array(
            'id' => $id
        ));
    }

    public function updateIntegrantesemillero($id, $data = array(), $file_name)
    {
        $arrayT = array();
        
        if($data->fecha_fin!=""){
            $arrayT['fecha_fin'] = $data->fecha_fin;
        }

        if($data->rol_participacion!=""){
            $arrayT['rol_participacion'] = $data->rol_participacion;
        }

        if($data->tipo_vinculacion!=""){
            $arrayT['tipo_vinculacion'] = $data->tipo_vinculacion;
        }

        if($data->estado!=""){
            $arrayT["estado"] = $data->estado;
        }

        if($data->integrante!=""){
            $arrayT["integrante"] = $data->integrante;
        }

        $arrayT["fecha_inicio"] = $data->fecha_inicio;
        

        $array["archivo"] = $file_name;
        $this->update($arrayT, array(
            'id' => $id
        ));
        return 1;
    }
}
?>