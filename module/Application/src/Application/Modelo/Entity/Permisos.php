<?php

namespace Application\Modelo\Entity;
use Zend\Db\Sql\Select as Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;

class Permisos extends TableGateway
{
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null, Sql $sql = null)
    {
        return parent::__construct('aps_permisos', $adapter, $databaseSchema, $selectResultPrototype, $sql);
    }

    public function getPermisos()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }

    public function verificarPermiso($id_pantalla, $id_rol)
    {
        $resultSet = $this->select(array(
            'id_rol' => $id_rol,
            'id_pantalla' => $id_pantalla
        ));
        return $resultSet->toArray();
    }

    public function addPermisos($data = array(), $id_rol)
    {
        list($id_pantalla, $id_submodulo) = explode('-', $data->id_formulario);      
        if(count($this->verificarPermiso($id_pantalla,$id_rol))>0){
            //ya existe el permiso para esta pantalla
            $resultado = 0;
        }else{
            //no existe toca crearlo
            $array = array(
                'id_rol' => $id_rol,
                'id_pantalla' => $id_pantalla
            );
             $resultado = $this->insert($array);
            
        }
        return $resultado;
    }

    public function getPermisorol($id)
    {
        $resultSet = $this->select(array(
            'id_rol' => $id
        ));
        return $resultSet->toArray();
    }

    public function eliminarPermisos($id)
    {
        $this->delete(array(
            'id_permiso' => $id
        ));
    }

    public function getValidarPermisoPantalla($id_usuario, $pantalla, $recortar = true)
    {
        if($recortar){
            $ini = strrpos($pantalla, "\\")+1;
            $fin = strrpos($pantalla, "Controller");
            $pantalla = substr($pantalla, $ini, $fin-$ini);
        }
        $pantalla = strtoupper($pantalla); 
        $sql = sprintf("SELECT id_pantalla FROM aps_permisos WHERE id_rol IN (SELECT id_rol FROM aps_roles_usuario WHERE id_usuario=%d) AND id_pantalla IN (SELECT id FROM adm_pantalla WHERE UPPER(nombre) = '%s');",
        $id_usuario, $pantalla);
    
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        if(count($statement->toArray())>0){
            $resultado = 1;
        }else{
            $resultado = 0;
        }
        return $resultado;
    }
}

?>