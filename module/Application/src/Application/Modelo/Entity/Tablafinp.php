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

class Tablafinp extends TableGateway
{
    
    // variables de la tabla
    
    private $id_financiacion;
    
    private $valor;

    private $id_rubro;

    private $id_fuente;

    private $descripcion;

    private $observaciones;

    private $periodo;
    
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgp_financiacion', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array())
    {
        $this->id_financiacion = $datos["id_financiacion"];
        $this->valor = $datos["valor"];
        $this->id_rubro = $datos["id_rubro"];
        $this->id_fuente = $datos["id_fuente"];
        $this->periodo = $datos["periodo"];
        $this->descripcion = strip_tags(htmlspecialchars_decode($datos["descripcion"]));
        $this->observaciones = strip_tags(htmlspecialchars_decode($datos["observaciones"]));
    }
    
    private function cargaAtributosValor($datos = array())
    {
        echo 'Valor: ' + $datos["valor"];
        $this->id_financiacion = $datos["id_financiacion"];
        $this->valor = $datos["valor"];
    }

    public function addTablafin($data = array(), $id)
    {
        self::cargaAtributos($data);
        
        $arrayid_rubro = array();
        if ($this->id_rubro != '0') {
            $arrayid_rubro = array(
                'id_rubro' => $this->id_rubro
            );
        }
        
        $arrayid_fuente = array();
        if ($this->id_fuente != '0') {
            $arrayid_fuente = array(
                'id_fuente' => $this->id_fuente
            );
        }
        
        $array = array(
            'id_proyecto' => $id,
            'descripcion' => $this->descripcion,
            'observaciones' => $this->observaciones,
            'valor' => $this->valor,
            'periodo' => $this->periodo
        );
        
        $array = $array + $arrayid_rubro + $arrayid_fuente;
        
        $this->insert($array);
        return 1;
    }

    public function addTablafin2($proy, $rubro, $fuente, $valor, $per)
    {
        $array = array(
            'id_proyecto' => $proy,
            'id_rubro' => $rubro,
            'id_fuente' => $fuente,
            'valor' => $valor,
            'periodo' => $per
        );
        
        $this->insert($array);
        return 1;
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
                'id_proyecto',
                'total' => new Sql\Expression('sum(valor)')
            ));
            $select->where(array(
                'id_proyecto' => $id
            ));
            $select->group(array(
                'id_rubro',
                'periodo',
                'id_proyecto'
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
                'id_proyecto' => $id
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
                'id_proyecto' => $id
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
                'id_proyecto' => $id
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

    public function getTablafinexiste($data, $id)
    {
        self::cargaAtributos($data);
        
        if ($this->id_rubro != null) {
            $rubro = $this->id_rubro;
        }
        
        if ($this->id_fuente != null) {
            $fuente = $this->id_fuente;
        }
        
        $filter = new StringTrim();
        $resultSet = $this->select(function ($select) use($id, $rubro, $fuente) {
            $select->columns(array(
                'id_proyecto'
            ));
            $select->where(array(
                'id_proyecto = ?' => $id,
                'id_rubro = ?' => $rubro,
                'id_fuente = ?' => $fuente
            ));
        });
        $row = $resultSet->current();
        
        if ($row != null) {
            return 1;
        } else {
            return 0;
        }
    }

    public function updateTablafin($id, $data = array())
    {
        self::cargaAtributosValor($data);
        
        $array_valor = array(
            'valor' => $this->valor
        );
        
        $this->update($array_valor, array(
            'id_financiacion' => $id
        ));
    }

    public function getTablafin($id)
    {
        $resultSet = $this->select(function ($select) use($id) {
            $select->columns(array(
                'id_financiacion',
                'id_proyecto',
                'id_rubro',
                'id_fuente',
                'id_estado',
                'valor',
                'descripcion',
                'observaciones',
                'periodo'
            ));
            $select->where(array(
                'id_proyecto' => $id
            ));
            $select->order(array(
                'id_fuente ASC'
            ));
        });
        return $resultSet->toArray();
    }
    
    public function getTablaFinId($id)
    {
        $resultSet = $this->select(function ($select) use($id) {
            $select->columns(array(
                'id_financiacion',
                'id_proyecto',
                'id_rubro',
                'id_fuente',
                'id_estado',
                'valor',
                'descripcion',
                'observaciones',
                'periodo'
            ));
            $select->where(array(
                'id_financiacion' => $id
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
                'id_proyecto = ?' => $id
            ));
        });
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
                'id_proyecto = ?' => $id
            ));
            $select->order(array(
                'periodo ASC'
            ));
        });
        return $resultSet->toArray();
    }

    public function getTablafinorder($id)
    {
        $filter = new StringTrim();
        $resultSet = $this->select(function ($select) use($id) {
            $select->columns(array(
                'id_fuente'
            ));
            $select->where(array(
                'id_proyecto = ?' => $id
            ));
            $select->group(array(
                'id_fuente'
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