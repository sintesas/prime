<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Integrantes extends TableGateway
{
    
    // variables de la tabla
    private $id_integrante;

    private $id_grupo_inv;
    
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgi_gi_integrantes', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array())
    {
        $this->id_integrante = $datos["id_integrante"];
        $this->id_grupo_inv = $datos["id_grupo_inv"];
    }

    public function addIntegrantes($id_inte, $id)
    {
        $array = array(
            'id_grupo_inv' => $id,
            'id_integrante' => $id_inte
        );
        $this->insert($array);
        return 1;
    }

    public function getIntegrantes($id)
    {
        $resultSet = $this->select(array(
            'id_grupo_inv' => $id
        ));
        return $resultSet->toArray();
    }

    public function getIntegrantesi()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }

    public function eliminarIntegrantes($id)
    {
        $this->delete(array(
            'id_integrantes' => $id
        ));
    }

    public function filtroGrupos($data = array())
    {
        self::cargaAtributos($data);
        
        if ($this->id_grupo_inv != '') {
            $nom = $this->id_grupo_inv;
            $rowset = $this->select(function (Where $select) use($nom) {
                $select->where(array(
                    'id_grupo_inv = ?' => $nom
                ));
            });
            return $rowset->toArray();
        } else {
            $rowset = $this->select();
            return $rowset->toArray();
        }
    }

    public function updateIntegrantes($id, $id_usuario)
    {   
        $array = array(
            'id_integrante' => $id_usuario
        );

        $this->update($array, array(
            'id_integrantes' => $id
        ));
        return 1;
    }
}
?>