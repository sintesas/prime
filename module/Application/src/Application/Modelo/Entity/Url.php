<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Url extends TableGateway{

	//variables de la tabla
	private $url;
	private $nom_url;
	private $descripcion;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_url', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->url=$datos["url"];
		$this->nom_url=$datos["nom_url"];
		$this->descripcion=$datos["descripcion"];
	}

	public function addUrl($data=array(), $id){
		self::cargaAtributos($data);

        		$array=array(
			'id_convocatoria'=>$id,
			'url'=>$this->url,
			'nom_url'=>$this->nom_url,
			'descripcion'=>$this->descripcion,
            		 );
      		  $this->insert($array);
		return 1;
	}

    	public function getUrl($id){
		$resultSet = $this->select(array('id_convocatoria'=>$id));
		return $resultSet->toArray();
   	}

    	public function getUrls(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarUrl($id) {
        		$this->delete(array('id_url' => $id));
    	}
}
?>