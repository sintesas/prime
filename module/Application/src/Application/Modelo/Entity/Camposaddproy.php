<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Camposaddproy extends TableGateway{

	//variables de la tabla
	private $titulo;
	private $valor;

	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_campos_add_proy', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->titulo=$datos["titulo"];
		$this->valor=$datos["valor"];
	}

	public function addCamposaddproy($titulo, $id){

        		$array=array(
			'id_aplicar'=>$id,
			'titulo'=>$titulo,
            		 );
      		  $this->insert($array);
		return 1;
	}

    public function updateCamposaddproy($data=array(),$id)
    {
        self::cargaAtributos($data);

		$array=array
            		(
		  		'valor'=>$this->valor,
             		);
		
  		$this->update($array, array('id_campo_add'=>$id));
return 1;
   	}

    	public function getCamposaddproy($id){
		$resultSet = $this->select(array('id_aplicar'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarCamposaddproy($id) {
        		$this->delete(array('id_campo_add' => $id));
    	}
}
?>