<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Areashv extends TableGateway{

	//variables de la tabla
	private $nombre_area;
	private $objeto;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('aps_hv_areas', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->nombre_area=$datos["nombre_area"];
		$this->objeto=$datos["objeto"];
	}

	public function addAreashv($data=array(), $id, $file_name){
		self::cargaAtributos($data);

        		$array=array(
			'id_usuario'=>$id,
			'nombre_area'=>$this->nombre_area,
			'objeto'=>$this->objeto,
			'archivo' => $file_name
            		 );
      		  $this->insert($array);
		return 1;
	}

    	public function getAreashv($id){
		$resultSet = $this->select(array('id_usuario'=>$id));
		return $resultSet->toArray();
   	}

    	public function getAreashvt(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarAreashv($id) {
        		$this->delete(array('id_area' => $id));
    	}
    	
     public function getAreashvById($id)
    {
        $resultSet = $this->select(array(
            'id_area' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateAreashv($id, $data = array(), $file_name)
    {   
        $array=array(
			'nombre_area'=>$data->nombre_area,
			'objeto'=>$data->objeto,
			'archivo' => $file_name
        );

        $this->update($array, array(
            'id_area' => $id
        ));
    }
}
?>