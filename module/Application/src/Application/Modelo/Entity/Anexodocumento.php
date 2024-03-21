<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;

class Anexodocumento extends TableGateway{
	private $descripcion;
	private $activo;
	private $tipovalorpadre;
    public function __construct(Adapter $adapter = null, 
							   $databaseSchema =null,
							   ResultSet $selectResultPrototype =null){
      return parent::__construct('aps_documentos', $adapter, $databaseSchema,$selectResultPrototype);
   }
   
	private function cargaAtributos($datos=array())
	{
		$this->descripcion=$datos["descripcion"];
	}
	
    public function getDocumentos(){
      $resultSet = $this->select();
	  return $resultSet->toArray();
    }
	
	public function addRoles($data=array())
	{
		//$this->insert($data);
		self::cargaAtributos($data);
        $array=array
            (
            'descripcion'=>$this->descripcion
             );
        $this->insert($array);
	}
	

   
}

?>