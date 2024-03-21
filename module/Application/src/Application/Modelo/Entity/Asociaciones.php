<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Asociaciones extends TableGateway{

	//variables de la tabla
	private $nombre_asociacion;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgi_gi_asociaciones', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->nombre_asociacion=$datos["nombre_asociacion"];
	}

	public function addAsociaciones($data=array(), $id, $file_name){
		self::cargaAtributos($data);

        		$array=array(
			'id_grupo_inv'=>$id,
			'nombre_asociacion'=>$this->nombre_asociacion,
            'archivo' => $file_name
            		 );
      		  $this->insert($array);
		return 1;
	}

    	public function getAsociaciones($id){
		$resultSet = $this->select(array('id_grupo_inv'=>$id));
		return $resultSet->toArray();
   	}
   	public function getAsociacionest(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarAsociaciones($id) {
        		$this->delete(array('id_asociaciones' => $id));
    }
    
    public function getAsociacionById($id)
    {
        $resultSet = $this->select(array(
            'id_asociaciones' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateAsociacion($id, $data = array(), $file_name)
    {   
        $array = array(
            'nombre_asociacion' => $data->nombre_asociacion,
            'archivo' => $file_name
        );

        $this->update($array, array(
            'id_asociaciones' => $id
        ));
    }

}
?>