<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Evaluar extends TableGateway{

	//variables de la tabla
	private $id_grupo_inv;
	private $nombre_grupo;
	private $fecha_creacion;
	private $observaciones;
	private $id_ponderacion2;
	private $estado;

	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgc_aspectos_eval_proy', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->observaciones=$datos["observaciones"];
		$this->id_ponderacion2=$datos["id_ponderacion2"];
		$this->estado=$datos["estado"];
	}

	public function addEvaluar($id_aplicar,$id_ponderacion1,$descripcion){

        		$array=array(
			'id_aplicar'=>$id_aplicar,
			'id_ponderacion1'=>$id_ponderacion1,
			'descripcion'=>$descripcion,

            		 );
      		  $this->insert($array);
		return 1;
	}

    	public function getEvaluar(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

    	public function getEvaluarid($id){
		$id = (int) $id;
		$rowset = $this->select(array('id_aspecto'=>$id));
		$row = $rowset->current();
		if(!$row){
			throw new \Exception("no hay id asociado");
		}
	  return $row;
   	}

    	public function getEvaluados($id){
		$resultSet = $this->select(array('id_aplicar'=>$id));
		return $resultSet->toArray();
    	}

    	public function getEventosh(){
		$resultSet = $this->select(array('estado'=>'A'));
		return $resultSet->toArray();
    	}

    	public function getEvaluara($id){
		$resultSet = $this->select(array('id_aplicar'=>$id));
		return $resultSet->toArray();
    	}

   	public function eliminarGrupoinv($id) {
        		$this->delete(array('id_grupo_inv' => $id));
    	}

	//funcion actualizar tabla hsi_noticias
    	public function updateEvaluar($id, $data=array()){
		self::cargaAtributos($data);
$arrayestado =array();
$arraypon =array();
$arrayobs =array();
		if($this->estado!=null){
        			$arrayestado=array
            			(
			  		'id_estado'=>$this->estado,
             			);
		}
		if($this->id_ponderacion2!=null){
        			$arraypon=array
            			(
			  		'id_ponderacion2'=>$this->id_ponderacion2,
             			);
		}
		if($this->observaciones!=null){
        			$arrayobs=array
            			(
			  		'observaciones'=>$this->observaciones,
             			);
		}
		
        		$array=array(
'fecha_evaluacion'=>now,
);
			 
$array=$arrayestado+$arraypon+$arrayobs;

		$this->update($array, array('id_aspecto' => $id));
		return 1;
	}

	//funcion para filtrar noticias
	public function filtroEventos($datos=array()){
		self::cargaAtributos($datos);
		if($this->titulo!=''  && $this->evento==''){
			$tit='%'.$this->titulo.'%';
			$tit=strtoupper($tit);
			$rowset = $this->select(function (Where $select) use ($tit){
			$select->where(array('upper(titulo) LIKE ?'=>$tit));
			});
			return $rowset->toArray();
		}
		if($this->evento!=''  && $this->titulo==''){
			$not='%'.$this->evento.'%';
			$not=strtoupper($not);
			$rowset = $this->select(function (Where $select) use ($not){
			$select->where(array('upper(evento) LIKE ?'=>'%'.$not.'%'));
			});
			return $rowset->toArray();
		}
		
		if($this->titulo!='' && $this->evento!=''){
			$not='%'.$this->evento.'%';
			$tit='%'.$this->titulo.'%';
			$tit=strtoupper($tit);
			$not=strtoupper($not);
			$rowset = $this->select(function (Where $select) use ($not,$tit){
			$select->where(array('upper(evento) LIKE ?'=>'%'.$not.'%',
					 'upper(titulo) LIKE ?'=>$tit));
			});
			return $rowset->toArray();
		}
		if($this->titulo=='' && $this->evento==''){
		$rowset = $this->select();
		return $rowset->toArray();
		}

	}

	//funcion para filtrar noticias
	public function filtroGrupos($datos=array()){
		self::cargaAtributos($datos);
		if($this->nombre_grupo!=''  && $this->id_unidad_academica==''  && $this->id_dependencia_academica==''  && $this->id_programa_academico=='' && $this->cod_grupo==''){
			$nom='%'.$this->nombre_grupo.'%';
			$nom=strtoupper($nom);
			$rowset = $this->select(function (Where $select) use ($nom){
			$select->where(array('upper(nombre_grupo) LIKE ?'=>$nom));
			});
			return $rowset->toArray();
		}
		if($this->nombre_grupo==''  && $this->id_unidad_academica!=''  && $this->id_dependencia_academica==''  && $this->id_programa_academico=='' && $this->cod_grupo==''){
			$uni=$this->id_unidad_academica;
			$uni=strtoupper($uni);
			$rowset = $this->select(function (Where $select) use ($uni){
			$select->where(array('(id_unidad_academica) = ?'=>$uni));
			});
			return $rowset->toArray();
		}
		if($this->nombre_grupo==''  && $this->id_unidad_academica==''  && $this->id_dependencia_academica!=''  && $this->id_programa_academico=='' && $this->cod_grupo==''){
			$dep=$this->id_dependencia_academica;
			$dep=strtoupper($dep);
			$rowset = $this->select(function (Where $select) use ($dep){
			$select->where(array('id_dependencia_academica = ?'=>$dep));
			});
			return $rowset->toArray();
		}
		if($this->nombre_grupo==''  && $this->id_unidad_academica==''  && $this->id_dependencia_academica==''  && $this->id_programa_academico!='' && $this->cod_grupo==''){
			$pro=$this->id_programa_academico;
			$pro=strtoupper($pro);
			$rowset = $this->select(function (Where $select) use ($pro){
			$select->where(array('id_programa_academico = ?'=>$pro));
			});
			return $rowset->toArray();
		}
		if($this->nombre_grupo==''  && $this->id_unidad_academica==''  && $this->id_dependencia_academica==''  && $this->id_programa_academico=='' && $this->cod_grupo!=''){
			$cod='%'.$this->codigo_grupo.'%';
			$cod=strtoupper($cod);
			$rowset = $this->select(function (Where $select) use ($cod){
			$select->where(array('upper(cod_grupo) LIKE ?'=>'%'.$cod.'%'));
			});
			return $rowset->toArray();
		}
		if($this->nombre_grupo!=''  && $this->id_unidad_academica!=''  && $this->id_dependencia_academica!=''  && $this->id_programa_academico!='' && $this->cod_grupo!=''){
			$nom='%'.$this->nombre_grupo.'%';
			$uni=$this->id_unidad_academica;
			$dep=$this->id_dependencia_academica;
			$pro=$this->id_programa_academico;
			$cod='%'.$this->cod_grupo.'%';
			$cod=strtoupper($cod);
			$nom=strtoupper($nom);
			$uni=strtoupper($uni);
			$dep=strtoupper($dep);
			$pro=strtoupper($pro);
			$rowset = $this->select(function (Where $select) use ($nom,$uni,$dep,$pro,$cod){
			$select->where(array('upper(nombre_grupo) LIKE ?'=>$nom,
					'(id_unidad_academica) = ?'=>$uni,
					'(id_dependencia_academica) = ?'=>$dep,
					'(id_programa_academico) = ?'=>$pro,
					'upper(cod_grupo) LIKE ?'=>'%'.$cod.'%'));
			});
			return $rowset->toArray();
		}
		if($this->nombre_grupo!=''  && $this->id_unidad_academica!=''  && $this->id_dependencia_academica==''  && $this->id_programa_academico=='' && $this->cod_grupo==''){
			$nom='%'.$this->nombre_grupo.'%';
			$uni=$this->id_unidad_academica;
			$nom=strtoupper($nom);
			$uni=$uni;
			$rowset = $this->select(function (Where $select) use ($nom,$uni){
			$select->where(array('upper(nombre_grupo) LIKE ?'=>'%'.$nom.'%',
					'id_unidad_academica = ?'=>$uni));
			});
			return $rowset->toArray();
		}
		if($this->nombre_grupo==''  && $this->id_unidad_academica==''  && $this->id_dependencia_academica==''  && $this->id_programa_academico=='' && $this->cod_grupo==''){
		$rowset = $this->select();
		return $rowset->toArray();
		}

	}

	//funcion para filtrar noticias
	public function filtroInvestigadores($datos=array()){
		self::cargaAtributos($datos);
		if($this->nombre!=''  && $this->apellido==''  && $this->documento==''  && $this->grupo=='' && $this->proyecto==''){
			$nom='%'.$this->nombre.'%';
			$nom=strtoupper($nom);
			$rowset = $this->select(function (Where $select) use ($nom){
			$select->where(array('upper(nombre_grupo) LIKE ?'=>$nom));
			});
			return $rowset->toArray();
		}
		if($this->nombre==''  && $this->apellido!=''  && $this->documento==''  && $this->grupo=='' && $this->proyecto==''){
			$uni=$this->id_unidad_academica;
			$uni=strtoupper($uni);
			$rowset = $this->select(function (Where $select) use ($uni){
			$select->where(array('(id_unidad_academica) = ?'=>$uni));
			});
			return $rowset->toArray();
		}
		if($this->nombre==''  && $this->apellido==''  && $this->documento!=''  && $this->grupo=='' && $this->proyecto==''){
			$dep=$this->id_dependencia_academica;
			$dep=strtoupper($dep);
			$rowset = $this->select(function (Where $select) use ($dep){
			$select->where(array('id_dependencia_academica = ?'=>$dep));
			});
			return $rowset->toArray();
		}
		if($this->nombre==''  && $this->apellido==''  && $this->documento==''  && $this->grupo!='' && $this->proyecto==''){
			$pro=$this->id_programa_academico;
			$pro=strtoupper($pro);
			$rowset = $this->select(function (Where $select) use ($pro){
			$select->where(array('id_programa_academico = ?'=>$pro));
			});
			return $rowset->toArray();
		}
		if($this->nombre==''  && $this->apellido==''  && $this->documento==''  && $this->grupo=='' && $this->proyecto!=''){
			$cod='%'.$this->codigo_grupo.'%';
			$cod=strtoupper($cod);
			$rowset = $this->select(function (Where $select) use ($cod){
			$select->where(array('upper(cod_grupo) LIKE ?'=>'%'.$cod.'%'));
			});
			return $rowset->toArray();
		}
		if($this->nombre!=''  && $this->apellido!=''  && $this->documento!=''  && $this->grupo!='' && $this->proyecto!=''){
			$nom='%'.$this->nombre_grupo.'%';
			$uni=$this->id_unidad_academica;
			$dep=$this->id_dependencia_academica;
			$pro=$this->id_programa_academico;
			$cod='%'.$this->cod_grupo.'%';
			$cod=strtoupper($cod);
			$nom=strtoupper($nom);
			$uni=strtoupper($uni);
			$dep=strtoupper($dep);
			$pro=strtoupper($pro);
			$rowset = $this->select(function (Where $select) use ($nom,$uni,$dep,$pro,$cod){
			$select->where(array('upper(nombre_grupo) LIKE ?'=>$nom,
					'(id_unidad_academica) = ?'=>$uni,
					'(id_dependencia_academica) = ?'=>$dep,
					'(id_programa_academico) = ?'=>$pro,
					'upper(cod_grupo) LIKE ?'=>'%'.$cod.'%'));
			});
			return $rowset->toArray();
		}
		if($this->nombre!=''  && $this->apellido!=''  && $this->documento==''  && $this->grupo=='' && $this->proyecto==''){
			$nom='%'.$this->nombre_grupo.'%';
			$uni=$this->id_unidad_academica;
			$nom=strtoupper($nom);
			$uni=$uni;
			$rowset = $this->select(function (Where $select) use ($nom,$uni){
			$select->where(array('upper(nombre_grupo) LIKE ?'=>'%'.$nom.'%',
					'id_unidad_academica = ?'=>$uni));
			});
			return $rowset->toArray();
		}
		if($this->nombre==''  && $this->apellido==''  && $this->documento==''  && $this->grupo=='' && $this->proyecto==''){
		$rowset = $this->select();
		return $rowset->toArray();
		}

	}

}

?>