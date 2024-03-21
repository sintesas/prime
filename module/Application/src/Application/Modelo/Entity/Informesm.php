<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Informesm extends TableGateway{

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
					return parent::__construct('mgp_informes_monitores', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->informe=$datos["informe"];
		$this->fecha_limite=$datos["fecha_limite"];
		$this->observaciones=$datos["observaciones"];
		$this->id_estado=$datos["id_estado"];
		$this->archivo=$datos["archivo"];
	}

	public function getIdInforme($id,$id2){
		$rowset = $this->select(
			function (Where $select) use ($id, $id2){
				$select->where(array(
					'id_avalcumpliconvo'=>$id,
					'id_aplicar'=>$id2
				));
			}
		);
		return $rowset->toArray();
    }

	public function addInforme($id, $id2){
		$array=array(
			'id_aplicar'=>$id2,
			'id_avalcumpliconvo'=>$id,
			'estado'=>"Pendiente"
        );
        $this->insert($array);
		return 1;
	}

	public function updateInformeArchivo($id, $archivo)
    {
    	$arrayData= array();
		
		if($archivo != null){
			$arrayData["archivo"] = $archivo;	
			$arrayData["new_archivo"] = "Si";
		}
		$this->update($arrayData, array('id_informe' => $id));
		return 1;
    }

    public function getInformeh($id){
		$rowset = $this->select(
			function (Where $select) use ($id){
				$select->where(array('id_aplicar'=>$id));

			}
		);
		return $rowset->toArray();
    }

    public function updateInformeById($data=array(),$id)
    {
    	$arrayData= array();
		
		if($data["estado"] != null){
			$arrayData["estado"] = $data["estado"];	
		}

		if($data["observaciones"] != null){
			$arrayData["observaciones"] = $data["observaciones"];	
		}
		$this->update($arrayData, array('id_informe' => $id));
		return 1;
    }
/*










	

    public function eliminarInforme($id) {
    	$this->delete(array('id_informe' => $id));
    }

    public function getInformeById($id){
		$rowset = $this->select(
			function (Where $select) use ($id){
				$select->where(array('id_informe'=>$id));
			}
		);
		return $rowset->toArray();
    }

    

    public function updateInformeByFecha($data=array(),$id)
    {
    	echo $data["fecha_limite"];
    	$arrayData= array();
		$arrayData["informe"] = trim($data["informe"]);	
		$arrayData["fecha_limite"] = trim($data["fecha_limite"]);	
		$this->update($arrayData, array('id_informe' => $id));
		return 1;
    }

    
    
*/

















    public function eliminarCronograma($id) {
    	$this->delete(array('id_informe' => $id));
    }

    public function getCronogramah($id){
		$rowset = $this->select(
			function (Where $select) use ($id){
				$select->where(array('id_proyecto'=>$id));
				$select->order(array('fecha_limite ASC'));
			}
		);
		return $rowset->toArray();
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

   	
}
?>