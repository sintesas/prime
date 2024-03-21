<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Documentosvinculados extends TableGateway{
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('mgi_documentosvinculados', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addDocumentosvinculados($tipo_docu, $id_usuario, $id_grupo, $id_documm, $id_usuario_solicitud, $modulo){
		$array=array(
			'id_grupoinv'=>$id_grupo,
			'id_usuario'=>$id_usuario,
			'id_documento'=>$id_documm,
			'tipo_documento'=>$tipo_docu,
			'id_usuario_solicitud'=>$id_usuario_solicitud,
			'fecha_solcitud'=> now,
			'estado_solicitud'=> "P",
            'modulo' => $modulo 
        );
        $this->insert($array);
        return 1;
	}


    public function getDocumentosvinculadosByGrupo($id_usuario, $id_grupo, $modulo){
        $resultSet = $this->select(
            array(
                'id_usuario' => $id_usuario,
                'id_grupoinv' =>  $id_grupo,
                'modulo' => $modulo
            )
        );
        return $resultSet->toArray();
    }

    public function eliminarDocumentosvinculados($id) {
        $this->delete(array('id' => $id));
    }

    public function getDocumentosvinculadosByUsuario($id_usuario, $modulo){
        $resultSet = $this->select(
            array(
                'id_usuario' => $id_usuario,
                'modulo' => $modulo
            )
        );
        return $resultSet->toArray();
    }

    public function getDocumentosvinculadosPendientesByUsuario($id_usuario){
        $resultSet = $this->select(
            array(
                'id_usuario' => $id_usuario,
                'estado_solicitud' => 'P'
            )
        );
        return $resultSet->toArray();
    }

    public function updateDocumentosvinculados($id, $estado)
    {   
        $array = array(
            'estado_solicitud' => $estado
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }

    public function getDocumentosvinculadosByEstado($id_grupo, $modulo){
        $resultSet = $this->select(
            array(
                'estado_solicitud' => "A",
                'id_grupoinv' =>  $id_grupo,
                'modulo' => $modulo
            )
        );
        return $resultSet->toArray();
    }

    public function getDocumentosvinculadosByActivo($modulo){
        $resultSet = $this->select(
            array(
                'estado_solicitud' => "A",
                'modulo' => $modulo
            )
        );
        return $resultSet->toArray();
    }

}
?>