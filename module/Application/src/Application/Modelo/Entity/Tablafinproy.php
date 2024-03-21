<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;
use Zend\Db\Sql;

class Tablafinproy extends TableGateway
{
    
    // variables de la tabla
    private $valor;

    private $id_rubro;

    private $id_fuente;

    private $descripcion;

    private $observaciones;
    
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgc_financiacion_proy', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array())
    {
        $this->valor = $datos["valor"];
        $this->id_rubro = $datos["id_rubro"];
        $this->id_fuente = $datos["id_fuente"];
        $this->descripcion = strip_tags(htmlspecialchars_decode($datos["descripcion"]));
        $this->observaciones = strip_tags(htmlspecialchars_decode($datos["observaciones"]));
    }

    public function addTablafin($id_rubro, $id_fuente, $id, $per)
    {
        $arrayid_rubro = array();
        if ($id_rubro != '0') {
            $arrayid_rubro = array(
                'id_rubro' => $id_rubro
            );
        }
        
        $arrayid_fuente = array();
        if ($id_fuente != '0') {
            $arrayid_fuente = array(
                'id_fuente' => $id_fuente
            );
        }
        
        $array = array(
            'id_aplicar' => $id,
            'descripcion' => $this->descripcion,
            'observaciones' => $this->observaciones,
            'periodo' => $per
        );
        
        $array = $array + $arrayid_rubro + $arrayid_fuente;
        
        $this->insert($array);
        return 1;
    }

    public function updateTablafin($id, $data = array())
    {
        self::cargaAtributos($data);
        
        $array = array(
            'valor' => $this->valor
        );
        
        $this->update($array, array(
            'id_financiacion' => $id
        ));
        return 1;
    }

    public function getTablafin($id)
    {
        $resultSet = $this->select(function ($select) use($id) {
            $select->columns(array(
                'id_financiacion',
                'id_aplicar',
                'id_rubro',
                'id_fuente',
                'id_estado',
                'valor',
                'descripcion',
                'observaciones',
                'periodo'
            ));
            $select->where(array(
                'id_aplicar' => $id
            ));
            $select->order(array(
                'id_fuente ASC'
            ));
        });
        return $resultSet->toArray();
    }

    public function getTablafinid($id)
    {
        $resultSet = $this->select(array(
            'id_financiacion' => $id
        ));
        return $resultSet->toArray();
    }

    public function getArrayfinanciaper($id)
    {
        $filter = new StringTrim();
        $resultSet = $this->select(function ($select) use($id) {
            $select->quantifier(\Zend\Db\Sql\Select::QUANTIFIER_DISTINCT);
            $select->columns(array(
                'periodo'
            ));
            $select->where(array(
                'id_aplicar = ?' => $id
            ));
            $select->order(array(
                'periodo ASC'
            ));
        });
        return $resultSet->toArray();
    }

    public function getArrayfinancia($id)
    {
        $filter = new StringTrim();
        $resultSet = $this->select(function ($select) use($id) {
            $select->quantifier(\Zend\Db\Sql\Select::QUANTIFIER_DISTINCT);
            $select->columns(array(
                'id_rubro'
            ));
            $select->where(array(
                'id_aplicar = ?' => $id
            ));
        });
        return $resultSet->toArray();
    }

    public function caseSql($id)
    {
        $resultSet = $this->select(function ($select) use($id) {
            $select->columns(array(
                'id_rubro',
                'recursos_inversion' => new Sql\Expression('sum(case when id_fuente=78 then valor else 0 end)'),
                'recursos_funcionamiento' => new Sql\Expression('sum(case when id_fuente=79 then valor else 0 end)'),
                'recursos_cofinanciacion' => new Sql\Expression('sum(case when id_fuente=80 then valor else 0 end)'),
                
                'id_inversion' => new Sql\Expression('sum(case when id_fuente=78 then id_financiacion else 0 end)'),
                'id_funcionamiento' => new Sql\Expression('sum(case when id_fuente=79 then id_financiacion else 0 end)'),
                'id_cofinanciacion' => new Sql\Expression('sum(case when id_fuente=80 then id_financiacion else 0 end)'),
                'periodo',
                'id_aplicar',
                'total' => new Sql\Expression('sum(valor)')
            ));
            $select->where(array(
                'id_aplicar' => $id
            ));
            $select->group(array(
                'id_rubro',
                'periodo',
                'id_aplicar'
            ));
            $select->order(array(
                'id_rubro ASC'
            ));
        });
        return $resultSet->toArray();
    }

    public function caseSql2($id)
    {
        $resultSet = $this->select(function ($select) use($id) {
            $select->columns(array(
                'id_rubro',
                'recursos_inversion' => new Sql\Expression('sum(case when id_fuente=78 then valor else 0 end)'),
                'recursos_funcionamiento' => new Sql\Expression('sum(case when id_fuente=79 then valor else 0 end)'),
                'recursos_cofinanciacion' => new Sql\Expression('sum(case when id_fuente=80 then valor else 0 end)')
            ));
            $select->where(array(
                'id_aplicar' => $id
            ));
            $select->group(array(
                'id_rubro'
            ));
            $select->order(array(
                'id_rubro ASC'
            ));
        });
        return $resultSet->toArray();
    }

    public function sumFuente($id)
    {
        $resultSet = $this->select(function ($select) use($id) {
            $select->columns(array(
                'id_fuente',
                'total' => new Sql\Expression('sum(valor)')
            ));
            $select->where(array(
                'id_aplicar' => $id
            ));
            $select->group(array(
                'id_fuente'
            ));
            $select->order(array(
                'id_fuente ASC'
            ));
        });
        return $resultSet->toArray();
    }

    public function sumTotal($id)
    {
        $resultSet = $this->select(function ($select) use($id) {
            $select->columns(array(
                'total' => new Sql\Expression('sum(valor)')
            ));
            $select->where(array(
                'id_aplicar' => $id
            ));
        });
        return $resultSet->toArray();
    }

    public function sumRubro($id)
    {
        $resultSet = $this->select(function ($select) use($id) {
            $select->columns(array(
                'id_rubro',
                'total' => new Sql\Expression('sum(valor)')
            ));
            $select->where(array(
                'id_aplicar' => $id
            ));
            $select->group(array(
                'id_rubro'
            ));
            $select->order(array(
                'id_rubro ASC'
            ));
        });
        return $resultSet->toArray();
    }

    public function eliminarTablafin($id)
    {
        $this->delete(array(
            'id_financiacion' => $id
        ));
    }
}
?>