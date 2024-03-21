<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Requisitosdoc extends TableGateway{

	//variables de la tabla
	private $id_tipo_doc;
	private $id_tipo_ponderacion;
	private $descripcion;
	private $observaciones;
	private $fecha_inicio;
	private $fecha_cierre;
	private $fecha_limite;
	private $hora_limite;
	private $responsable;

		
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_requisitos_doc', 
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
		$this->fecha_limite=$datos["fecha_limite"];
		$this->hora_limite=$datos["hora_limite"];
		$this->responsable=$datos["responsable"];

	}


	public function addRequisitodoccopiar($id_tipo_doc,$id_documento,$id_ponderacion1,$id_ponderacion2,$id_estado,$descripcion,$observaciones,$id_tipo_ponderacion,$fecha,$conv,$hora = null,$responsable = null){

        		$array=array(
			'id_tipo_doc'=>$id_tipo_doc,
			'id_documento'=>$id_documento,
			'id_ponderacion1'=>$id_ponderacion1,
			'id_ponderacion2'=>$id_ponderacion2,
			'id_estado'=>$id_estado,
			'descripcion'=>$descripcion,
			'hora_limite'=>$hora,
			'responsable'=>$responsable,
			'descripcion'=>$descripcion,
			'observaciones'=>$observaciones,
			'id_tipo_ponderacion'=>$id_tipo_ponderacion,
			'fecha_limite'=>$fecha,
			'id_convocatoria'=>$conv,
            		 );

      		$this->insert($array);
		return 1;
	}

	public function addRequisitosdoc($data=array(), $id){
		self::cargaAtributos($data);

		$arrayid_tipo_doc= array();
		if($this->id_tipo_doc!='0'){
        			$arrayid_tipo_doc=array
            			(
			  		'id_tipo_doc'=>$this->id_tipo_doc,
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
			'descripcion'=>$this->descripcion,
			'observaciones'=>$this->observaciones,
			'fecha_limite'=>$this->fecha_limite,
			'hora_limite'=>$this->hora_limite,
			'responsable'=>$this->responsable,
            		 );

		$array = $array+$arrayid_tipo_doc+$arrayid_tipo_ponderacion;

      		$this->insert($array);
		return 1;
	}


    public function updateRequisitosdoc($id, $data=array(),$tipo)
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


   	public function updateRequisitosdocAll($id, $data = array())
    {   
        $array = array(
        	'id_tipo_doc'=>$data["id_tipo_doc"],
			'descripcion'=>$data["descripcion"],
			'hora_limite'=>$data["hora_limite"],
			'responsable'=>$data["responsable"],
			'fecha_limite'=>$data["fecha_limite"]
        );

        $this->update($array, array(
            'id_requisito_doc' => $id
        ));
        return 1;
    }

    public function getRequisitosdoc($id){
		$resultSet = $this->select(array('id_convocatoria'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarRequisitosdoc($id) {
        		$this->delete(array('id_requisito_doc' => $id));
    	}

    public function getRequisitosdocById($id){
		$resultSet = $this->select(array('id_requisito_doc'=>$id));
		return $resultSet->toArray();
   	}
}
?>