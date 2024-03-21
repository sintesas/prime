<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Informes extends TableGateway{

	//variables de la tabla
	private $informe;
	private $archivo;
	private $fecha_limite;
	private $id_estado;
	private $observaciones;
		
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgp_informes', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->informe=$datos["informe"];
		$this->fecha_limite=$datos["fecha_limite"];
		$this->observaciones=$datos["observaciones"];
		$this->id_estado=$datos["id_estado"];
		$this->archivo=$datos["archivo"];
	}

	public function addInforme($data=array(), $id, $archi = null){
		self::cargaAtributos($data);
			$array=array(
				'informe'=>$this->informe,
				'fecha_limite'=>$this->fecha_limite,
				'id_proyecto'=>$id,
				'id_estado'=>3,
				'archivo2' => $archi
	        );

      		$this->insert($array);
		return 1;
	}

    public function getCronogramah($id){

			$rowset = $this->select(function (Where $select) use ($id){
			$select->where(array('id_proyecto'=>$id));
			$select->order(array('fecha_limite ASC'));
			});
			return $rowset->toArray();
    }

    public function getInfo(){
		$resultSet = $this->select();
		return $resultSet->toArray();
    }


	public function updateInformeDatosBasicos($data=array(),$id, $archi=null)
    {
		$informe=array();
		if($data->informe!=null){
			$informe=array('informe'=>$data->informe);
		}

		$fecha_limite= array();
		if($data->fecha_limite!=null){
        	$fecha_limite=array('fecha_limite'=>$data->fecha_limite);
		}

		$archiarray= array();
		if($archi!=null){
        	$archiarray=array('archivo2'=>$archi);
		}

		$array = $informe+$fecha_limite+$archiarray;
		$this->update($array, array('id_informe' => $id));
		return 1;
    }


    public function updateInforme($data=array(),$id)
    {

        self::cargaAtributos($data);
		$arrayid_estado= array();
		if($this->id_estado!=null){

        			$arrayid_estado=array
            			(
			  'id_estado'=>$this->id_estado,
             			);
		}

		$arrayobservaciones= array();
		if($this->observaciones!=null){
        			$arrayobservaciones=array
            			(
			  'observaciones'=>$this->observaciones,
             			);
		}


$array= array();

$array = $array+$arrayid_estado+$arrayobservaciones;
		$this->update($array, array('id_informe' => $id));

return 1;
    }
    

 public function updateInformearch($data=array(),$id, $arch)
    {

        self::cargaAtributos($data);




		$arrayarch= array();
		if($arch!=null){

        			$arrayarch=array
            			(
			  'archivo'=>$arch,
             			);
		}


$array= array();

$array = $array+$arrayarch;
		$this->update($array, array('id_informe' => $id));

return 1;
    }

    public function getArchivos($id){
		$resultSet = $this->select(array('id_informe'=>$id));
		return $resultSet->toArray();
   	}
    public function getArchivosid($id){
		$resultSet = $this->select(array('id_informe'=>$id));
		return $resultSet->toArray();
   	}


    	public function getCronogramas(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarCronograma($id) {
        		$this->delete(array('id_informe' => $id));
    	}
}
?>