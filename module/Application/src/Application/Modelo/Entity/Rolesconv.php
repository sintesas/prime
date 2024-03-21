<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Rolesconv extends TableGateway{

	//variables de la tabla
	private $id_rol;
		
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_roles', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->id_rol=$datos["id_rol"];
	}

	public function addRolesconv($data=array(), $id){
		self::cargaAtributos($data);

		$arrayid_rol= array();
		if($this->id_rol!='0'){
        			$arrayid_rol=array
            			(
			  		'id_rol'=>$this->id_rol,
             			);
		}
		
		    		$array=array(
					'id_convocatoria'=>$id,
            		 );

		$array = $array+$arrayid_rol;

      		$this->insert($array);
		return 1;
	}


    public function updateRolesconv($id, $data=array(),$tipo)
    {
        self::cargaAtributos($data);


		$arrayid_tipo_aspecto= array();
		if($this->id_tipo_requisito!='0'){
        			$arrayid_entidad=array
            			(
			  		'id_tipo_requisito'=>$this->id_tipo_requisito,
             			);
		}

  		$this->update($arrayid_tipo_aspecto, array('id_requisito'=>$id));
   	}

    public function getRolesconv($id){
		$resultSet = $this->select(array('id_convocatoria'=>$id));
		return $resultSet->toArray();
   	}

   	public function getAllRolesconv(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarRolesconv($id) {
        		$this->delete(array('id_rol_conv' => $id));
    	}
}
?>