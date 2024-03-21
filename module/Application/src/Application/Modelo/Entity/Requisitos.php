<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Requisitos extends TableGateway{

	//variables de la tabla
	private $id_tipo_requisito;
	private $descripcion;
	private $observaciones;
	private $fecha_inicio;
	private $fecha_cierre;
	private $id_tipo_ponderacion;
		
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_requisitos', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->id_tipo_requisito=$datos["id_tipo_requisito"];
		$this->id_tipo_ponderacion=$datos["id_tipo_ponderacion"];
		$this->descripcion=$datos["descripcion"];
		$this->observaciones=$datos["observaciones"];

	}

	public function addRequisitoscopiar($id_tipo_requisito,$id_ponderacion1,$id_ponderacion2,$id_estado,$descripcion,$observaciones,$id_tipo_ponderacion,$conv){

        		$array=array(
			'id_tipo_requisito'=>$id_tipo_requisito,
			'id_ponderacion1'=>$id_ponderacion1,
			'id_ponderacion2'=>$id_ponderacion2,
			'id_estado'=>$id_estado,
			'descripcion'=>$descripcion,
			'observaciones'=>$observaciones,
			'id_tipo_ponderacion'=>$id_tipo_ponderacion,
			'id_convocatoria'=>$conv,
            		 );

      		$this->insert($array);
		return 1;
	}

	public function addRequisitos($data=array(), $id){
		self::cargaAtributos($data);

		$arrayid_tipo_requisito= array();
		if($this->id_tipo_requisito!='0'){
        			$arrayid_tipo_requisito=array
            			(
			  		'id_tipo_requisito'=>$this->id_tipo_requisito,
             			);
		}
		
		$arrayid_tipo_ponderacion= array();
		if($this->id_tipo_ponderacion!='0'){
        			$arrayid_tipo_ponderacion=array
            			(
			  		'id_tipo_ponderacion'=>$this->id_tipo_ponderacion,
             			);
		}

        		$array=array(
					'id_convocatoria'=>$id,
			'descripcion'=>trim($this->descripcion),
			'observaciones'=>$this->observaciones,
            		 );

		$array = $array+$arrayid_tipo_requisito+$arrayid_tipo_ponderacion;

      		$this->insert($array);
		return 1;
	}


    public function updateRequisitos($id, $data=array(),$tipo)
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

    	public function getRequisitos($id){
		$resultSet = $this->select(array('id_convocatoria'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarRequisitos($id) {
        		$this->delete(array('id_requisito' => $id));
    	}
}
?>