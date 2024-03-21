<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Requisitosapdoc extends TableGateway
{
    
    // variables de la tabla
    private $id_tipo_doc;

    private $id_tipo_ponderacion;

    private $descripcion;

    private $observaciones;

    private $fecha_inicio;

    private $fecha_cierre;

    private $estado;

    private $archivo;
    
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgc_requisitosap_doc', $adapter, $databaseSchema, $selectResultPrototype);
    }

    private function cargaAtributos($datos = array())
    {
        $this->id_tipo_doc = $datos["id_tipo_doc"];
        $this->id_tipo_ponderacion = $datos["id_tipo_ponderacion"];
        $this->descripcion = $datos["descripcion"];
        $this->observaciones = $datos["observaciones"];
        $this->archivo = $datos["archivo"];
        $this->estado = $datos["estado"];
    }

    public function addRequisitosdoc($descr, $lim, $obser, $id_padre, $id)
    {
        $array = array(
            'id_aplicar' => $id,
            'id_requisito_doc' => $id_padre,
            'fecha_limite' => $lim,
            'descripcion' => $descr,
            'observaciones' => $obser
        );
        
        $this->insert($array);
        return 1;
    }

    public function updateRequisitosdoc($id, $archivo, $data = array())
    {
        self::cargaAtributos($data);
        
        $arrayestado = array();
        if ($this->estado != null) {
            $arrayestado = array(
                'id_ponderacion2' => $this->estado
            );
        }
        
        $arrayid_entidad = array();
        $arraynuevo_archivo = array();
        if ($archivo != null) {
            
            $arrayid_entidad = array(
                'archivo' => $archivo
            );

            $arraynuevo_archivo = array(
                'new_archivo' => "Si"
            );
        }
                
        $array = array();
        $array = $arrayid_entidad + $arrayestado + $arraynuevo_archivo;
        
        $this->update($array, array(
            'id_requisitoap_doc' => $id
        ));
        return 1;
    }

    public function getRequisitosapdoc($id)
    {
        $resultSet = $this->select(array(
            'id_aplicar' => $id
        ));
        return $resultSet->toArray();
    }

    public function getRequisitosapdocid($id)
    {
        $resultSet = $this->select(array(
            'id_requisitoap_doc' => $id
        ));
        return $resultSet->toArray();
    }

    public function getRequisitos()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }

    public function eliminarRequisitosdoc($id)
    {
        $this->delete(array(
            'id_requisito_doc' => $id
        ));
    }

    public function updateEstadoRequisitos($id, $estado)
    {
        $array=array(
            'id_ponderacion2'=>$estado,
        );
        $this->update($array, array('id_requisitoap_doc'=>$id));
        return 1;
    }

    public function updateEstadoRequisitosPorAplicacion($id, $estado)
    {
        $array=array(
            'id_ponderacion2'=>$estado,
        );
        $this->update($array, array('id_aplicar'=>$id));
        return 1;
    }

    public function updateEstadoChecked($id, $estado)
    {
        $array=array(
            'checked'=>$estado,
        );
        $this->update($array, array('id_requisitoap_doc'=>$id));
        return 1;
    }

}
?>