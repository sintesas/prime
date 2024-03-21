<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Where;

class Repositorio extends TableGateway
{
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('repositorio', $adapter, $databaseSchema, $selectResultPrototype);
    }

    public function addRepositorio($data=array(), $archi, $id_usuario){
        $array=array(
            'archivo'=>$archi,
            'nombre'=>$data->nombre,
            'url'=>$data->url,
            'id_tipo'=>$data->id_tipo,
            'descripcion'=>$data->descripcion,
            'otra_informacion'=>$data->otra_informacion,
            'fecha_creacion' => now,
            'id_usuario_creador' => $id_usuario
        );
        $this->insert($array);
        return 1;
    }

    public function getRepositorioid($id){
        $resultSet = $this->select(array('id_repositorio'=>$id));
        return $resultSet->toArray();
    }

    public function getRepositoriot(){
        $resultSet = $this->select();
        return $resultSet->toArray();
    }

    public function eliminarParticipacioneventosred($id) {
                $this->delete(array('id_repositorio' => $id));
    }

    public function getRepositorioFiltro($data = array()){
        $condicion = array();
        if($data["id_tipo"]!=""){
             $condicion["id_tipo"]=$data["id_tipo"];
        }
        if($data["nombre"]!=""){
             $condicion["nombre"]=$data["nombre"];
        }

        $resultSet = $this->select($condicion);
        return $resultSet->toArray();
    }



}

?>