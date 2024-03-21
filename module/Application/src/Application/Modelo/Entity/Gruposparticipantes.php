<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Gruposparticipantes extends TableGateway
{
    
    // variables de la tabla
    private $id_grupo;
    
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgc_grupos_participantes', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array())
    {
        $this->id_grupo = $datos["id_grupo"];
    }

    public function addGruposparticipantes($id_gru, $id)
    {
        self::cargaAtributos($data);
        
        $array = array(
            'id_aplicar' => $id,
            'id_grupo' => $id_gru
        );
        $this->insert($array);
        return 1;
    }

    public function getGruposparticipantes($id)
    {
        $resultSet = $this->select(array(
            'id_aplicar' => $id
        ));
        return $resultSet->toArray();
    }

    public function eliminarGruposparticipantes($id)
    {
        $this->delete(array(
            'id_grupo_rel' => $id
        ));
    }
}
?>