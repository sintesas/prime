<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Cronograma extends TableGateway{

	//variables de la tabla
	private $nombre_actividad;
	private $descripcion;
	private $objetivo;
	private $fecha_inicio;
	private $fecha_cierre;
	private $prioridad;
		
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_cronograma', 
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
		$this->prioridad=$datos["prioridad"];
		$this->fecha_cierre=$datos["fecha_cierre"];

	}

	public function addCronograma($data=array(), $id){
		self::cargaAtributos($data);


        		$array=array(
			'nombre_actividad'=>$this->nombre_actividad,
			'descripcion'=>$this->descripcion,
			'objetivo'=>$this->objetivo,
			'fecha_inicio'=>$this->fecha_inicio,
			'fecha_cierre'=>$this->fecha_cierre,
			'prioridad'=>$this->prioridad,
			'id_convocatoria'=>$id,
            		 );

      		$this->insert($array);
		return 1;
	}


	public function addCronogramacopiar($nombre,$descripcion,$objetivo,$fecha_ini,$fecha_cie,$prioridad,$conv){


        		$array=array(
			'nombre_actividad'=>$nombre,
			'descripcion'=>$descripcion,
			'objetivo'=>$objetivo,
			'fecha_inicio'=>$fecha_ini,
			'fecha_cierre'=>$fecha_cie,
			'prioridad'=>$prioridad,
			'id_convocatoria'=>$conv,
            		 );

      		$this->insert($array);
		return 1;
	}

    public function getCronogramah($id){

			$rowset = $this->select(function (Where $select) use ($id){
			$select->where(array('id_convocatoria'=>$id));
			$select->order(array('prioridad ASC'));
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
		$resultSet = $this->select(array('id_convocatoria'=>$id));
		return $resultSet->toArray();
   	}


    	public function getCronogramas(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarCronograma($id) {
        		$this->delete(array('id_cronograma' => $id));
    	}
}
?>