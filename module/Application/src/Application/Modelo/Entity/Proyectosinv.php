<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Proyectosinv extends TableGateway{

	//variables de la tabla
	private $proyect;
	private $cantidad_plazas;
	private $fecha_lim_soporte;
	private $profesor;

		
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_proyectos_investigacion', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->proyect=$datos["proyect"];
		$this->cantidad_plazas=$datos["cantidad_plazas"];
		$this->fecha_lim_soporte=$datos["fecha_lim_soporte"];
		$this->profesor=$datos["profesor"];
	}

	public function addProyectosinv($data=array(), $id){
		self::cargaAtributos($data);
        		$array=array(
			'nombre_proyecto'=>$this->proyect,
			'cantidad_plazas'=>$this->cantidad_plazas,
			'plazas_disponibles'=>$this->cantidad_plazas,
			'fecha_lim_soporte'=>$this->fecha_lim_soporte,
			'id_convocatoria'=>$id,
            		 );

      		$this->insert($array);
		return 1;
	}

    public function getPlazasinv($id){

			$rowset = $this->select(function (Where $select) use ($id){
			$select->where(array('id_proyecto_inv'=>$id));
			});
			return $rowset->toArray();
    }


    public function updateProyplaza($id,$pl){
        $array=array
            (
		'plazas_disponibles'=>$pl,
	);
		$this->update($array, array('id_proyecto_inv' => $id));

return 1;
    }

    public function getProyectosinvh($id){
			$rowset = $this->select(function (Where $select) use ($id){
			$select->where(array('id_convocatoria'=>$id));
			$select->order(array('fecha_cierre DESC'));
			});
			return $rowset->toArray();
    }

   	public function getProyectosinv($id){
		$resultSet = $this->select(array('id_convocatoria'=>$id));
		return $resultSet->toArray();
   	}

   	public function getExistProyect($id, $id2){
		$rowset = $this->select(function (Where $select) use ($id, $id2){
			$select->where(array('id_convocatoria'=>$id));
			$select->where(array('nombre_proyecto'=>$id2));
		});
		return $rowset->toArray();
   	}

    public function getProyectosinvs(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

    public function getProyectosid($id){
		$resultSet = $this->select(array('id_proyecto_inv'=>$id));
		return $resultSet->toArray();
   	}

   	public function eliminarProyectosinv($id) {
        		$this->delete(array('id_proyecto_inv' => $id));
    	}
}
?>