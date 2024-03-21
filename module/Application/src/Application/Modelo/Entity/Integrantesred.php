<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Integrantesred extends TableGateway
{
    
    // variables de la tabla
    private $id_red;
    private $id_integrantered;
    
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mri_integrantesred', $adapter, $databaseSchema, $selectResultPrototype);
    }

    public function getIntegrantesredi()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }

  public function addIntegrantered($data, $id, $file_name)
    {
        $array = array(
            'id_red' => $id,
            'id_integrantered' => $data->id_autor,
            'tipo_vinculacion' => $data->tipo_vinculacion,
            'archivo' => $file_name
        );

        if($data->fecha_vinculacion!=""){
             $array = $array + array(
                'fecha_vinculacion' => $data->fecha_vinculacion
            );
        }

        if($data->fin_vinculacion!=""){
             $array = $array + array(
                'fin_vinculacion' => $data->fin_vinculacion
            );
        }
        $this->insert($array);
        return 1;
    }

    public function getIntegrantesred($id)
    {
        $resultSet = $this->select(array(
            'id_red' => $id
        ));
        return $resultSet->toArray();
    }

    public function getIntegrantesredById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function eliminarIntegrantered($id)
    {
        $this->delete(array(
            'id' => $id
        ));
    }

    //Eliminiar 


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

    public function updateIntegranteRed($id, $data = array(), $file_name)
    {
        $arrayT = array();
        
        if($data->fin_vinculacion!=""){
            $arrayT['fin_vinculacion'] = $data->fin_vinculacion;
        }

        if($data->tipo_vinculacion!=""){
            $arrayT['tipo_vinculacion'] = $data->tipo_vinculacion;
        }

        $arrayT['archivo'] = $file_name;


        $this->update($arrayT, array(
            'id' => $id
        ));
        return 1;
    } 
}
?>