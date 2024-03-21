<?php
namespace Application\Modelo\Entity;

use Zend\Db\Sql\Select as Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Modelo\Entity\Usuarios;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;

class Rolesusuario extends TableGateway
{

    private $id_usuario;

    protected $_name = 'aps_roles_usuario';

    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null, Sql $sql = null)
    {
        return parent::__construct('aps_roles_usuario', $adapter, $databaseSchema, $selectResultPrototype, $sql);
    }

    private function cargaAtributos($datos = array())
    {
        $this->id_usuario = $datos["id_usuario"];
    }

    public function getRolesusuario()
    {
        $resultSet = $this->select();
        return $resultSet->toArray();
    }

    public function verificaRolesusuario($id_usuario)
    {
        $rowset = $this->select(array(
            'id_usuario' => $id_usuario
        ));
        $row = $rowset->current();
        
        if (! $row) {
            return 0;
        }
        
        return 1;
    }

    public function agregarRolusuario($id_rol, $id_usuario)
    {
        $re = self::verificaRolesusuario($id_usuario);
        
        if ($re == 0) {
             $array = array(
                'id_usuario' => $id_usuario,
                'id_rol' => $id_rol
            );
            $this->insert($array);
            return 1;
        } else {
            $array = array(
                'id_rol' => $id_rol,
            );

            $this->update($array, array(
                'id_usuario' => $id_usuario
            ));
            return 0;
        }
    }

    public function addRolesusuario($id, $data = array(), $rolu = array())
    {
        // $this->insert($data);
        $c = 0;
        $resultado = 1;
        self::cargaAtributos($data);
        $filter = new StringTrim();
        $upper = new StringToUpper();
        
        foreach ($rolu as $r) {
            if ($filter->filter($r["id_usuario"]) == $this->id_usuario) {
                $c = 1;
            }
        }
        
        if ($c == 0) {
            $array = array(
                'id_usuario' => $this->id_usuario,
                'id_rol' => $id
            );
            $this->insert($array);
            return $resultado;
        } else {
            return 0;
        }
    }
    
    // funcion obtener usuario por id
    public function getRolesusuarioid($id)
    {
        if($id!=""){
            $resultSet = $this->select(array(
                'id_rol' => $id
            ));
            return $resultSet->toArray();
        }else{
            $resultSet = $this->select();
            return $resultSet->toArray();       
        }
    }

    public function getCountriesList()
    {
        $resultSet = $this->select(function ($select)
        {
            $select->columns(array(
                'id_permiso',
                'descripcion'
            ));
        });
        // $select->order('name ASC');
        
        return $resultSet->toArray();
    }

    public function eliminarRolesusuario($id)
    {
        $this->delete(array(
            'id_usuario' => $id
        ));
    }

    public function verificarRolusuario($id)
    {
        $resultSet = $this->select(array(
            'id_usuario' => $id
        ));
        $rol_usu = $resultSet->toArray();
        if(count($rol_usu)>0){
            $sql = sprintf("SELECT opcion_pantalla FROM aps_roles WHERE id_rol=%d;", $rol_usu[0]["id_rol"]);
            $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
            $rol_usu[0]["id_rol"] = $statement->toArray()[0]["opcion_pantalla"];
        }
        
        return $rol_usu;
    }

    public function getRolUsuario($id)
    {
        $resultSet = $this->select(array(
            'id_usuario' => $id
        ));
        return $resultSet->toArray();
    }

    public function getUsuariosByRol()
    {
        $sql = "SELECT id_rol, count(*) as cantidad FROM aps_roles_usuario group by id_rol order by id_rol ASC;";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
    }

    public function getLoginByRol()
    {
        $sql = "SELECT aps_roles_usuario.id_rol, count(*) as cantidad FROM aps_auditoria LEFT OUTER JOIN aps_roles_usuario on aps_auditoria.id_usuario = aps_roles_usuario.id_usuario WHERE aps_roles_usuario.id_rol IS NOT NULL GROUP BY aps_roles_usuario.id_rol ORDER BY aps_roles_usuario.id_rol;";
        $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
    }


}

?>