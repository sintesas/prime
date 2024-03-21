<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Agregarvalflex extends TableGateway
{

    private $descripcion_valor;

    private $sigla_valor;

    private $activo;

    private $valor_flexible_padre_id;

    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('aps_valores_flexibles', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array())
    {
        $this->descripcion_valor = $datos["descripcion_valor"];
        $this->sigla_valor = $datos["sigla_valor"];
        $this->activo = $datos["activo"];
        $this->valor_flexible_padre_id = $datos["valor_flexible_padre_id"];
    }

    public function getValoresflex($id)
    {
        $resultSet = $this->select(array(
            'id_tipo_valor' => $id
        ));
        return $resultSet->toArray();
    }

    public function getValoresf()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }

    public function getArrayvalores($id_padre)
    {
        $resultSet = $this->select(array(
            'id_tipo_valor' => $id_padre
        ));
        
        
        return $resultSet->toArray();
    }

    public function getArrayValoresOrdenados($id_padre)
    {
        $rowset = $this->select(function (Where $select) use ($id_padre){
          $select->where(array('id_tipo_valor'=>$id_padre));
          $select->order(array('id_valor_flexible ASC'));
        });
        return $rowset->toArray();
    }

    public function getArrayvalorespadre($id_padre)
    {
        if ($id_padre == null) {
            
            return $array = array();
        } else {
            $resultSet = $this->select(array(
                'valor_flexible_padre_id' => $id_padre
            ));
            return $resultSet->toArray();
        }
    }

    public function getValoresflexdesc($desc)
    {
        $resultSet = $this->select(array(
            'descripcion_valor' => $desc
        ));
        return $resultSet->toArray();
    }

    public function getvalflexseditar($id)
    {
        $resultSet = $this->select(array(
            'id_valor_flexible' => $id
        ));
        return $resultSet->toArray();
    }
    
    public function getvalflexseditarpadre($id_padre)
    {
        if ($id_padre == null) {
    
            return $array = array();
        } else {
            $resultSet = $this->select(array(
                'valor_flexible_padre_id' => $id_padre
            ));
            return $resultSet->toArray();
        }
    }

    public function getArrayvalflexid($id)
    {
        $filter = new StringTrim();
        $resultSet = $this->select(function ($select) use($id)
        {
            $select->columns(array(
                'descripcion_valor'
            ));
            $select->where(array(
                'id_valor_flexible = ?' => $id
            ));
        });
        $row = $resultSet->current();
        
        return $row;
    }

    public function getValoresflexid($id)
    {        
        $id = (int) $id;
        
        if ($id == null) {
            //cambiado por scano 
            //$id = 3;
            $row = array(
                "id_valor_flexible" => '',
                "descripcion_valor" => ''
            );
        }else{
            
            $rowset = $this->select(array(
                'id_valor_flexible' => $id
            ));
            $row = $rowset->current();
        }
        
        return $row;
    }

    public function addValoresflex($data = array(), $id, $valf = array())
    {
        
        // $this->insert($data);
        $id = (int) $id;
        $c = 0;
        $resultado = 1;
        self::cargaAtributos($data);
        $filter = new StringTrim();
        
        foreach ($valf as $r) {
            if ($filter->filter($r['descripcion_valor']) == $this->descripcion_valor) {
                $c = 1;
            }
        }
        if ($c == 0) {
            $array = array(
                'descripcion_valor' => $this->descripcion_valor,
                'activo' => $this->activo,
                'valor_flexible_padre_id' => $this->valor_flexible_padre_id,
                'id_tipo_valor' => $id
            );
            $this->insert($array);
            return 1;
        } else {
            return 0;
        }
    }
    // funcion actualizar tabla aps_usuarios
    public function updateValflex($id, $data = array())
    {
        self::cargaAtributos($data);
        $arrayactivo = array();
        
        if ($this->activo != '0') {
            $arrayactivo = array(
                'activo' => $this->activo
            );
        }
        
        $array = array(
            'descripcion_valor' => $this->descripcion_valor,
            'sigla_valor' => $this->sigla_valor,
            //'valor_flexible_padre_id' => $this->valor_flexible_padre_id,
            'usuario_mod' => $user,
            'fecha_mod' => now
        );
        
        $array = $array + $arrayactivo;
        
        $this->update($array, array(
            'id_valor_flexible' => $id
        ));
    }

    public function eliminarValoresflex($id)
    {
        $this->delete(array(
            'id_valor_flexible' => $id
        ));
    }
    
    
}

?>