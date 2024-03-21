<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;
use Zend\Db\Sql\Select as Limit;

class Talentohumano extends TableGateway
{
    
    // variables de la tabla
    private $tipo;

    private $descripcion;

    private $fecha;

    private $tema;

    private $instituciones;

    private $id_pais;
    
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('VUPN_PLAN_TRAB_INVESTIG', $adapter, $databaseSchema, $selectResultPrototype);
    }

    public function getUsuarioidTH($id)
    {
        $resultSet = $this->select(array(
            'EMP_CODIGO' => $id
        ));
        return $resultSet->toArray();
    }

    public function getusprueba($id)
    {
        $tit = 'A';
        $rowset = $this->select(function (Where $select) use($tit) {
            $select->limit(2);
        });
        return $rowset->toArray();
    }

    public function getUsuariosid($id)
    {
        $tit = 'A';
        $rowset = $this->select(function (Where $select) use($tit) {
            $select->limit(10);
        });
        return $rowset->toArray();
    }

    public function getUsuarioproyTH($id, $proy)
    {
        $resultSet = $this->select(array(
            'EMP_CODIGO' => $id,
            'CODIGO_PROYECTO' => $proy
        ));
        return $resultSet->toArray();
    }

    public function getProyTH($proy)
    {
        $resultSet = $this->select(array(
            'CODIGO_PROYECTO' => $proy
        ));
        return $resultSet->toArray();
    }


    public function getUsuarioproyTH2($id, $proy, $per, $ano)
    {
        $filter = new StringTrim();
        $resultSet = $this->select(array(
            'EMP_CODIGO' => $filter->filter($id),
            'CODIGO_PROYECTO' => $filter->filter($proy),
            'PERIODO' => $filter->filter($per),
            'ANO' => $filter->filter($ano)
        ));
        return $resultSet->current();
    }

    public function getUsuarioTH()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }
}
?>