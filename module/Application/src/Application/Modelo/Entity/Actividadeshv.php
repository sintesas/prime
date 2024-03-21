<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Actividadeshv extends TableGateway{

	//variables de la tabla
	private $tipo;
	private $descripcion;
	private $fecha;
	private $tema;
	private $instituciones;
	private $id_pais;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('aps_hv_actividades', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->tipo=$datos["tipo"];
		$this->descripcion=$datos["descripcion"];
		$this->fecha=$datos["fecha"];
		$this->tema=$datos["tema"];
		$this->instituciones=$datos["instituciones"];
		$this->id_pais=$datos["id_pais"];
	}

	public function addActividadeshv($data=array(), $id, $file_name){
		self::cargaAtributos($data);
		if($data->instituciones==""){
			$data->instituciones=0;
		}
		if($data->tema==""){
			$data->tema=0;
		}
		if($data->valor==""){
			$data->valor=0;
		}
        		$array=array(
			'id_usuario'=>$id,
			'tipo'=>$this->tipo,
			'descripcion'=>$this->descripcion,
			'fecha'=>$this->fecha,
			'tema'=>$data->tema,
			'instituciones'=>$data->instituciones,
			'id_pais'=>$this->id_pais,
			'documento_vinculacion'=>$data->documento_vinculacion,
			'valor'=>$data->valor,
			'dedicacion'=>$data->dedicacion,
            'archivo' => $file_name
            		 );
      		  $this->insert($array);
		return 1;
	}

    	public function getActividadeshv($id){
		$resultSet = $this->select(array('id_usuario'=>$id));
		return $resultSet->toArray();
   	}

   	public function getActividadeshvByTema($id_tema){
   		if($id_tema!=""){
   			$sql = "SELECT id_usuario, tema FROM aps_hv_actividades WHERE tema=".$id_tema." GROUP BY  id_usuario, tema;";
            $statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
            return  $statement->toArray();

   			$resultSet = $this->select(array('tema'=>$id_tema));
			return $resultSet->toArray();
   		}else{
   			return "";
   		}
		
   	}

    	public function getActividadeshvt(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarActividadeshv($id) {
        		$this->delete(array('id_actividades' => $id));
    }

     public function getActividadeshvbyId($id)
    {
        $resultSet = $this->select(array(
            'id_actividades' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateActividadeshv($id, $data = array(), $file_name)
    {   
        $array=array(
			'tipo'=>$data->tipo,
			'descripcion'=>$data->descripcion,
			'fecha'=>$data->fecha,
			'id_pais'=>$data->id_pais,
			'documento_vinculacion'=>$data->documento_vinculacion,
			'dedicacion'=>$data->dedicacion,
            'archivo' => $file_name
        );
        
        if($data->instituciones != ""){
			$array["instituciones"] = $data->instituciones;
		}else{
			$array["instituciones"] = null;
		}

		if($data->tema != ""){
			$array["tema"] = $data->tema;
		}else{
			$array["tema"] = null;
		}

		if($data->valor !=""){
			$array["valor"] = $data->valor;
		}else{
			$array["valor"] = null;
		}

        $this->update($array, array(
            'id_actividades' => $id
        ));
    }
}
?>