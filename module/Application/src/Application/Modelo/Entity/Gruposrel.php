<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Gruposrel extends TableGateway{

	//variables de la tabla
	private $id_grupo;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgi_gi_grupos_rel', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->id_grupo=$datos["id_grupo"];
	}

	public function addGruposrel($id_gru, $id){
		self::cargaAtributos($data);

        		$array=array(
			'id_grupo_inv'=>$id,
			'id_grupo'=>$id_gru
            		 );
      		  $this->insert($array);
		return 1;
	}

    	public function getGruposrel($id){
		$resultSet = $this->select(array('id_grupo_inv'=>$id));
		return $resultSet->toArray();
   	}

   	public function getGruposrelt(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarGruposrel($id) {
        		$this->delete(array('id_grupo_rel' => $id));
    	}

    public function getGruposById($id)
    {
        $resultSet = $this->select(array(
            'id_grupo_rel' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateGruposrel($id, $id_usuario, $file_name)
    {   
        $array = array(
            'id_grupo' => $id_usuario,
            'archivo' => $file_name
        );

        $this->update($array, array(
            'id_grupo_rel' => $id
        ));
        return 1;
    }

    public function updateArchivo($id, $file_name)
    {   
        $array = array(
            'archivo' => $file_name
        );

        $this->update($array, array(
            'id_grupo_rel' => $id
        ));
        return 1;
    }
}
?>