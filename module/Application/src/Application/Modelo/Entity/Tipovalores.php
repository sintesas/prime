<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;

use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Tipovalores extends TableGateway{
	private $descripcion;
	private $activo;
	private $id_tipo_valor_padre;
    public function __construct(Adapter $adapter = null, 
							   $databaseSchema =null,
							   ResultSet $selectResultPrototype =null){
      return parent::__construct('aps_tipos_valores', $adapter, $databaseSchema,$selectResultPrototype);
   }
   
	private function cargaAtributos($datos=array())
	{
		$this->descripcion=$datos["descripcion"];
		$this->activo=$datos["activo"];
		$this->id_tipo_valor_padre=$datos["id_tipo_valor_padre"];
	}
	
    public function getTipdefeditar($id){
      $resultSet = $this->select(array('id_tipo_valor'=>$id));
	  return $resultSet->toArray();
    }
	
    public function getTipovalores(){
		$tit='';
			$rowset = $this->select(function (Where $select) use ($tit){


			$select->order(array('descripcion ASC'));
			});
			return $rowset->toArray();
    }
	
    public function getArraytipos(){
      $resultSet = $this->select();
	  return $resultSet->toArray();
    }
	
	//funcion actualizar tabla aps_tipos_valores
    public function updateTipovalores($id, $data=array())
    {
        self::cargaAtributos($data);
		$arrayactivo= array();
		$arrayid_tipo_valor_padre= array();
		
		if($this->activo!='0'){
        $arrayactivo=array
            (
			  'activo'=>$this->activo,
             );
		}
		
		if($this->id_tipo_valor_padre!=0){
        $arrayid_tipo_valor_padre=array
            (
			  'id_tipo_valor_padre'=>$this->id_tipo_valor_padre,
             );
		}
		
        $array=array
            (
			  'descripcion'=>$this->descripcion,
			  'usuario_mod'=>$user,
			  'fecha_mod'=> now
             );
			 
		$array = $array+$arrayid_tipo_valor_padre+$arrayactivo;
		
		$this->update($array, array('id_tipo_valor' => $id));
    }
	
    public function getTipovaloreseditar($id){
      $resultSet = $this->select(array('id_tipo_valor'=>$id));
	  return $resultSet->toArray();
    }
	
	public function getTipovaloreid($id)
	{
		$id = (int) $id;
		$rowset = $this->select(array('id_tipo_valor'=>$id));
		$row = $rowset->current();
		if(!$row){
			throw new \Exception("no hay id asociado");
		}
	  return $row;
	}
	
	public function addTipovalores($data=array(),$tipval=array())
	{
		$c=0;
		$resultado=1;
		self::cargaAtributos($data);
		$filter = new StringTrim();
		
		foreach($tipval as $r){
			if($filter->filter($r['descripcion'])==$this->descripcion){
			 $c=1;
			}
		}
		if($c==0){
		if($this->id_tipo_valor_padre!=0){
        $array=array
            (
            'descripcion'=>$this->descripcion,
            'activo'=>$this->activo,
			'id_tipo_valor_padre'=>$this->id_tipo_valor_padre
             );
		}else{
        $array=array
            (
            'descripcion'=>$this->descripcion,
            'activo'=>$this->activo
             );
		}
        $this->insert($array);
		return 1;
				}else{
				return 0;
				}
		
		
	}
	
    public function eliminarTipovalores($id)
    {
        $this->delete(array('id_tipo_valor' => $id));
    }
   
}

?>