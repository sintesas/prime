<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Cronogramaap extends TableGateway{

	//variables de la tabla
	private $nombre_actividad;
	private $descripcion;
	private $objetivo;
	private $fecha_inicio;
	private $fecha_cierre;
		
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_cronograma_ap', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->nombre_actividad=$datos["nombre_actividad"];
		$this->descripcion=$datos["descripcion"];
		$this->objetivo=$datos["objetivo"];
		$this->fecha_inicio=$datos["fecha_inicio"];
		$this->fecha_cierre=$datos["fecha_cierre"];

	}

	public function addCronograma($data=array(), $id, $id3){
		self::cargaAtributos($data);
		if($id3=="1"){
			$array=array(
				'nombre_actividad'=>$this->nombre_actividad,
				'descripcion'=>$this->descripcion,
				'id_meta'=>$data->id_meta,
				'id_rolresponsable'=>$data->id_rolresponsable,
				'fecha_inicio'=>$this->fecha_inicio,
				'fecha_cierre'=>$this->fecha_cierre,
				'id_aplicar'=>$id,
	     	);
		}else{
			$array=array(
				'nombre_actividad'=>$this->nombre_actividad,
				'descripcion'=>$this->descripcion,
				'objetivo'=>$this->objetivo,
				'fecha_inicio'=>$this->fecha_inicio,
				'fecha_cierre'=>$this->fecha_cierre,
				'id_aplicar'=>$id
	     	);
		}
		$this->insert($array);
		return 1;
	}

    public function getCronogramah($id){

			$rowset = $this->select(function (Where $select) use ($id){
				$select->where(array('id_aplicar'=>$id));
				$select->order(array('id_meta ASC'));
				$select->order(array('id_cronograma ASC'));
			});
			return $rowset->toArray();
    }


    public function updateCronograma($id, $data=array(),$tipo)
    {
        self::cargaAtributos($data);


		$arrayid_entidad= array();
		if($this->id_entidad!='0'){
        			$arrayid_entidad=array
            			(
			  		'id_entidad'=>$this->id_entidad,
             			);
		}

		$arrayid_proyectos= array();
		if($this->id_proyectos!='0'){
        			$arrayid_entidad=array
            			(
			  		'id_proyectos'=>$this->id_proyectos,
             			);
		}

		$array_num_monitores= array();
		if($this->numero_monitores!=null){
        			$array_num_monitores=array
            			(
			  		'numero_monitores'=>$this->numero_monitores,
             			);
		}

		$arrayfecha_lim_soporte= array();
		if($this->fecha_lim_soporte!=null){
        			$arrayfecha_lim_soporte=array
            			(
			  		'fecha_lim_soporte'=>$this->fecha_lim_soporte,
             			);
		}


        $array=array
            (
			'titulo'=>$this->titulo,
			'descripcion'=>$this->descripcion,
			'observaciones'=>$this->observaciones,
			'fecha_apertura'=>$this->fecha_apertura,
			'fecha_cierre'=>$this->fecha_cierre,
			'tipo_conv'=>$tipo
	);

		$array = $array+$arrayid_entidad+$arrayid_proyectos+$array_num_monitores+$arrayfecha_lim_soporte;

		$this->update($array, array('id_cronograma'=>$id, 'tipo_conv'=>$tipo));
   	}

    	public function getCronograma($id){
		$resultSet = $this->select(array('id_aplicar'=>$id));
		return $resultSet->toArray();
   	}


    	public function getCronogramas(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarCronograma($id) {
        		$this->delete(array('id_cronograma' => $id));
    	}

    public function getCronogramaapById($id)
    {
        $resultSet = $this->select(array(
            'id_cronograma' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateCronogramaap($id, $data = array(), $id3)
    {   
    	if($id3=="1"){
			$array=array(
				'nombre_actividad' => $data->nombre_actividad,
				'id_meta'=>$data->id_meta,
				'id_rolresponsable'=>$data->id_rolresponsable,
				'descripcion' => $data->descripcion,
		        'fecha_inicio' => $data->fecha_inicio,
				'fecha_cierre'=>$data->fecha_cierre
	     	);
		}else{
			$array = array(
		        'nombre_actividad' => $data->nombre_actividad,
		        'descripcion' => $data->descripcion,
		        'objetivo' => $data->objetivo,
		        'fecha_inicio' => $data->fecha_inicio,
				'fecha_cierre'=>$data->fecha_cierre
		    );
		}

        $this->update($array, array(
            'id_cronograma' => $id
        ));
        return 1;
    }
}
?>