<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Tablaequipop extends TableGateway{

	//variables de la tabla
	private $id_integrante;
	private $id_proyecto;
	private $id_rol;
	private $id_tipo_dedicacion;
	private $horas_sol;
	private $horas_apro;
	private $periodo;
	private $ano;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgp_tabla_equipos', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->id_integrante=$datos["id_integrante"];
		$this->id_proyecto=$datos["id_proyecto"];
		$this->id_rol=$datos["id_rol"];
		$this->id_tipo_dedicacion=$datos["id_tipo_dedicacion"];
		$this->horas_sol=$datos["horas_sol"];
		$this->horas_apro=$datos["horas_apro"];
		$this->periodo=$datos["periodo"];
		$this->ano=$datos["ano"];
	}

	public function addTablaequipo($id_inte, $id){

        		$array=array(
			'id_proyecto'=>$id,
			'id_integrante'=>$id_inte
            		 );
      		  $this->insert($array);
		return 1;
	}


	public function addTablaequipo3($inte,$proy,$ano,$per,$hor){

        		$array=array(
			'id_proyecto'=>$proy,
			'id_integrante'=>$inte,
			'ano'=>$ano,
			'periodo'=>$per,
			'horas_apro'=>$hor,
            		 );
      		  $this->insert($array);
	$id = $this->getAdapter()->getDriver()->getLastGeneratedValue("mgp_tabla_equipos_id_integrantes_seq");
return $id;
	}


	public function addTablaequipo2($proy,$inte,$rol,$tipo,$sol,$apro,$ano,$per){

        		$array=array(
			'id_proyecto'=>$proy,
			'id_integrante'=>$inte,
			'id_rol'=>$rol,
			'id_tipo_dedicacion'=>$tipo,
			'horas_sol'=>$sol,
			'horas_apro'=>$apro,
			'ano'=>$ano,
			'periodo'=>$per,
            		 );
      		  $this->insert($array);
		return 1;
	}

	public function updateTablaequipoById($id, $horas){
		$arrayUpdate=array(
        	'horas_apro' => $horas
        );

        $arrayWhere = array(
			'id_integrantes' => $id
        );

        $this->update($arrayUpdate, $arrayWhere);
        return 1;
	}

    public function updateTablaequipo($id, $data=array(), $horas)
    {
        self::cargaAtributos($data);
        
        $arrayUpdate=array(
        	'horas_sol' => $this->horas_sol,
        	'horas_apro' => $horas,
        	'id_tipo_dedicacion' => $this->id_tipo_dedicacion,
        	'ano' => $this->ano,
        	'periodo' => $this->periodo,
        	'id_rol' => $this->id_rol
        );

        $arrayWhere = array(
			'id_integrantes' => $id,
        );

        $this->update($arrayUpdate, $arrayWhere);
        return 1;
/*
		if($this->ano!=''){
        	$arrayano=array('ano'=>$this->ano);
		}

		if($this->periodo!=''){
        	$arrayperiodo=array('periodo'=>$this->periodo);
		}
		
		if($this->id_rol!=''){
        	$arrayrol=array('id_rol'=>$this->id_rol);
		}

		if($this->id_tipo_dedicacion!=''){
        	$arraytipod=array('id_tipo_dedicacion'=>$this->id_tipo_dedicacion);
		}
		
        $array = array('horas_sol'=>$this->horas_sol,'horas_apro'=>$horas);
		//$array = $array+$arrayrol+$arraytipod+$arrayperiodo+$arrayano;
		$this->update("", array('id_integrantes'=>$id));
  */
    }

   	public function getTablaequipo($id){
		$resultSet = $this->select(array('id_proyecto'=>$id));
		return $resultSet->toArray ();
   	}

    public function getTablaequipoid($id){
		$resultSet = $this->select(array('id_integrantes'=>$id));
		return $resultSet->toArray();
   	}

    public function getArrayequiposper($id){
		$filter = new StringTrim();
		$resultSet = $this->select(function ($select) use ($id){
			$select->quantifier(\Zend\Db\Sql\Select::QUANTIFIER_DISTINCT);
			$select->columns(array('periodo','ano'));
			$select->where(array('id_proyecto = ?'=>$id));
			$select->order(array('periodo ASC','periodo ASC'));
		}); 
		return $resultSet->toArray();
	}

   	public function eliminarTablaequipo($id) {
        		$this->delete(array('id_integrantes' => $id));
    	}

	public function getTablaequipot() {
        	$resultSet = $this->select();
			return $resultSet->toArray ();
    }

}
?>