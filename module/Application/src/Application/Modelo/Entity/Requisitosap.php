<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Requisitosap extends TableGateway{

	//variables de la tabla
	private $id_tipo_doc;
	private $id_tipo_ponderacion;
	private $descripcion;
	private $observaciones;
	private $fecha_inicio;
	private $fecha_cierre;
	private $estado;
	private $archivo;
		
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_requisitosap', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->id_tipo_doc=$datos["id_tipo_doc"];
		$this->id_tipo_ponderacion=$datos["id_tipo_ponderacion"];
		$this->descripcion=$datos["descripcion"];
		$this->observaciones=$datos["observaciones"];
		$this->archivo=$datos["archivo"];
		$this->estado=$datos["estado"];

	}

	public function addRequisitosdoc($descr,$tip, $obser, $id_padre, $id){
        		$array=array(
					'id_aplicar'=>$id,
					'id_convocatoria'=>$id_padre,
					'id_tipo_requisito'=>$tip,
			'descripcion'=>$descr,
			'observaciones'=>$obser,
            		 );
      		$this->insert($array);
		return 1;
	}


    public function updateRequisitos($id, $data=array())
    {
		self::cargaAtributos($data);
        $array=array(
		'id_ponderacion2'=>$this->estado,
		);
  		$this->update($array, array('id_requisitoap'=>$id));
		return 1;
   	}


    	public function getRequisitos(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

    	public function getRequisitosap($id){
		$resultSet = $this->select(array('id_aplicar'=>$id));
		return $resultSet->toArray();
   	}

    	public function getRequisitosapid($id){
		$resultSet = $this->select(array('id_requisitoap'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarRequisitosdoc($id) {
        $this->delete(array('id_requisito_doc' => $id));
    }

    public function updateEstadoRequisitos($id, $estado)
    {
		$array=array(
			'id_ponderacion2'=>$estado,
		);
  		$this->update($array, array('id_requisitoap'=>$id));
		return 1;
   	}
}
?>