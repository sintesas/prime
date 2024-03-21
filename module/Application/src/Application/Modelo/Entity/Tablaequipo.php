<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Tablaequipo extends TableGateway{

	//variables de la tabla
	private $id_integrante;
	private $id_aplicar;
	private $id_rol;
	private $id_tipo_dedicacion;
	private $horas_sol;
	private $horas_apro;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_tabla_equipos', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->id_integrante=$datos["id_integrante"];
		$this->id_aplicar=$datos["id_aplicar"];
		$this->id_rol=$datos["id_rol"];
		$this->id_tipo_dedicacion=$datos["id_tipo_dedicacion"];
		$this->horas_sol=$datos["horas_sol"];
		$this->horas_apro=$datos["horas_apro"];
	}

	public function addTablaequipo($id_inte, $id){

        		$array=array(
			'id_aplicar'=>$id,
			'id_integrante'=>$id_inte
            		 );
      		  $this->insert($array);
		return 1;
	}

    public function updateTablaequipo($id, $data=array())
    {
        self::cargaAtributos($data);
		
		if($this->id_rol!=''){
        $arrayrol=array
            (
			  'id_rol'=>$this->id_rol,
             );
		}

		if($this->id_tipo_dedicacion!=''){
        $arraytipod=array
            (
			  'id_tipo_dedicacion'=>$this->id_tipo_dedicacion,
             );
		}
		else {
			$arraytipo=0;
		}
		
        $array=array
            (
			  'horas_sol'=>$this->horas_sol,

             );
			 
		$array = $array+$arrayrol+$arraytipod;
		
		$this->update($array, array('id_integrantes' => $id));
    }

    	public function getTablaequipo($id){
		$resultSet = $this->select(array('id_aplicar'=>$id));
		return $resultSet->toArray();
   	}

    	public function getTablaequipoid($id){
		$resultSet = $this->select(array('id_integrantes'=>$id));
		return $resultSet->toArray();
   	}


   	public function eliminarTablaequipo($id) {
        		$this->delete(array('id_integrantes' => $id));
    	}


public function filtroGrupos($data=array()){
		self::cargaAtributos($data);

		if($this->id_aplicar!=''){
			$nom=$this->id_aplicar;

		$rowset = $this->select(function (Where $select) use ($nom){
			$select->where(array('id_aplicar = ?'=>$nom));
			});
			return $rowset->toArray();
		}else
		{
		$rowset = $this->select();
		return $rowset->toArray();
		}
	

}
}
?>