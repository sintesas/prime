<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Camposadd extends TableGateway{

	//variables de la tabla
	private $titulo;
	private $descripcion;
	private $objetivo;
	private $obligatorio;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_campos_add', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->titulo=$datos["titulo"];
		$this->descripcion=$datos["descripcion"];
		$this->objetivo=$datos["objetivo"];
		$this->obligatorio=$datos["obligatorio"];
	}

	public function addCamposadd($data=array(), $id){
		self::cargaAtributos($data);

        		$array=array(
			'id_convocatoria'=>$id,
			'titulo'=>$this->titulo,
			'descripcion'=>$this->descripcion,
			'objetivo'=>$this->objetivo,
			'obligatorio'=>$this->obligatorio,
            		 );
      		  $this->insert($array);
		return 1;
	}

    	public function getCamposadd($id){
		$resultSet = $this->select(array('id_convocatoria'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarCamposadd($id) {
        		$this->delete(array('id_campo_add' => $id));
    	}
}
?>