<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Tablafin extends TableGateway{

	//variables de la tabla
	private $valor;
	private $id_rubro;
	private $id_fuente;
	private $descripcion;
	private $observaciones;
		
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_financiacion', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->valor=$datos["valor"];
		$this->id_rubro=$datos["id_rubro"];
		$this->id_fuente=$datos["id_fuente"];
		$this->descripcion=strip_tags(htmlspecialchars_decode($datos["descripcion"]));
		$this->observaciones=strip_tags(htmlspecialchars_decode($datos["observaciones"]));
	}

	public function addTablafin($data=array(), $id){
		self::cargaAtributos($data);

		$arrayid_rubro= array();
		if($this->id_rubro!='0'){
        			$arrayid_rubro=array
            			(
			  		'id_rubro'=>$this->id_rubro,
             			);
		}
		
		$arrayid_fuente= array();
		if($this->id_fuente!='0'){
        			$arrayid_fuente=array
            			(
			  		'id_fuente'=>$this->id_fuente,
             			);
		}

        		$array=array(
					'id_convocatoria'=>$id,
					'descripcion'=>$this->descripcion,
					'observaciones'=>$this->observaciones,
					'valor'=>$this->valor,
            		 );

		$array = $array+$arrayid_rubro+$arrayid_fuente;

      		$this->insert($array);
		return 1;
	}


    public function updateTablafin($id, $data=array(),$tipo)
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

    	public function getTablafin($id){
		$resultSet = $this->select(array('id_convocatoria'=>$id));

		return $resultSet->toArray();
   	}

    public function getTablafinorder($id){

$filter = new StringTrim();
		$resultSet = $this->select(function ($select) use ($id){
		$select->columns(array('id_fuente',
				 ));
		$select->where(array('id_convocatoria = ?'=>$id));
		$select->group(array('id_fuente'));
		}); 
return $resultSet->toArray();
    
    }

    	public function getTablafinexiste($data , $id){
  self::cargaAtributos($data);

		if($this->id_rubro!=null){
        		$rubro=$this->id_rubro;
		}

		if($this->id_fuente!=null){
        		$fuente=$this->id_fuente;
		}

$filter = new StringTrim();
		$resultSet = $this->select(function ($select) use ($id, $rubro, $fuente){
		$select->columns(array('id_convocatoria',
				 ));
		$select->where(array('id_convocatoria = ?'=>$id,'id_rubro = ?'=>$rubro,'id_fuente = ?'=>$fuente));
		}); 
$row = $resultSet->current();

		if($row!=null){
		return 1;
		}else{
		return 0;
		}

   	}

   	public function eliminarTablafin($id) {
        		$this->delete(array('id_financiacion' => $id));
    	}
}
?>