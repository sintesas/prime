<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Instituciones extends TableGateway{

	//variables de la tabla
	private $descripcion;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgi_gi_instituciones', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->descripcion=$datos["descripcion"];
	}

	public function addInstituciones($data=array(), $id, $file_name){
		self::cargaAtributos($data);

        		$array=array(
			'id_grupo_inv'=>$id,
			'descripcion'=>$this->descripcion,
			'archivo' => $file_name
            		 );
      		  $this->insert($array);
		return 1;
	}

    	public function getInstituciones($id){
		$resultSet = $this->select(array('id_grupo_inv'=>$id));
		return $resultSet->toArray();
   	}

   	public function getInstitucionest(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarInstituciones($id) {
   		$this->delete(array('id_institucion' => $id));
	}

    public function getInstitucionById($id)
    {
        $resultSet = $this->select(array(
            'id_institucion' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateInstitucion($id, $data = array(), $file_name)
    {   
        $array = array(
            'descripcion' => $data->descripcion,
			'archivo' => $file_name
        );

        $this->update($array, array(
            'id_institucion' => $id
        ));
        return 1;
    }
}
?>