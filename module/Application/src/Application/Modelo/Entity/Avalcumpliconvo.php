<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Avalcumpliconvo extends TableGateway{

	//variables de la tabla
	private $informe;
	private $fecha_limite;
	
		
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_avalcumplimiento', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->informe=$datos["informe"];
		$this->fecha_limite=$datos["fecha_limite"];
	}

	public function addInforme($data=array(), $id){
		self::cargaAtributos($data);
   		$array=array(
			'informe'=>$this->informe,
			'fecha_limite'=>$this->fecha_limite,
			'id_convocatoria'=>$id,
			'estado' => $data["estado"]
        );
        $this->insert($array);
		return 1;
	}

	public function getInformeById($id){
		$rowset = $this->select(
			function (Where $select) use ($id){
				$select->where(array('id'=>$id));
			}
		);
		return $rowset->toArray();
    }

    public function getInformeByIdConvo($id){
		$rowset = $this->select(
			function (Where $select) use ($id){
				$select->where(array('id_convocatoria'=>$id));
			}
		);
		return $rowset->toArray();
    }
    public function getInformeActivoByIdConvo($id){
		$rowset = $this->select(
			function (Where $select) use ($id){
				$select->where(array('id_convocatoria'=>$id, 'estado'=>'Activo'));
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
		$arrayData["estado"] = trim($data["estado"]);
		$this->update($arrayData, array('id' => $id));
		return 1;
    }
}
?>