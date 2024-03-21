<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Aspectoeval extends TableGateway{

	//variables de la tabla
	private $id_tipo_aspecto;
	private $id_convocatoria;
	private $id_aspecto;
	private $id_tipo_ponderacion;
	private $descripcion;
	private $observaciones;
	private $fecha_inicio;
	private $ponderacion1;
	private $fecha_cierre;
		
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_aspectos_eval', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->id_tipo_aspecto=$datos["id_tipo_aspecto"];
		$this->id_aspecto=$datos["id_aspecto"];
		$this->id_convocatoria=$datos["id_convocatoria"];
		$this->id_tipo_ponderacion=$datos["id_tipo_ponderacion"];
		$this->ponderacion1=$datos["ponderacion1"];
		$this->descripcion=$datos["descripcion"];
		$this->observaciones=$datos["observaciones"];

	}

	public function addAspectoevalcopiar($id_tipo_aspecto,$ponderacion1,$id_ponderacion2,$id_estado,$descripcion,$observaciones,$id_tipo_ponderacion,$conv){

        		$array=array(
			'id_tipo_aspecto'=>$id_tipo_aspecto,
			'ponderacion1'=>$ponderacion1,
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

	public function addAspectoeval($data=array(), $id){
		self::cargaAtributos($data);

		$arrayid_tipo_aspecto= array();
		if($this->id_tipo_aspecto!='0'){
        			$arrayid_tipo_aspecto=array
            			(
			  		'id_tipo_aspecto'=>$this->id_tipo_aspecto,
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
			'ponderacion1'=>$this->ponderacion1,
            		 );

		$array = $array+$arrayid_tipo_aspecto+$arrayid_tipo_ponderacion;


      		$this->insert($array);
		return 1;
	}


    public function updateAspectoeval($id, $data=array(),$tipo)
    {
        self::cargaAtributos($data);


		$arrayid_tipo_aspecto= array();
		if($this->id_tipo_aspecto!='0'){
        			$arrayid_entidad=array
            			(
			  		'id_tipo_aspecto'=>$this->id_tipo_aspecto,
             			);
		}

  		$this->update($arrayid_tipo_aspecto, array('id_aspecto'=>$id));
   	}

    	public function getAspectoevalsuma($id_conv){
		$resultSet = $this->select(function ($select) use ($id_conv){
		$select->columns(array('ponderacion1',
				 ));
		$select->where(array('id_convocatoria = ?'=>$id_conv));
		}); 
return $resultSet->toArray();
   	}

    	public function getAspectoeval($id){
		$resultSet = $this->select(array('id_convocatoria'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarAspectoeval($id) {
        		$this->delete(array('id_aspecto' => $id));
    	}
}
?>