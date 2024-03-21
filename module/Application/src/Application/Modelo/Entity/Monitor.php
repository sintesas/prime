<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Monitor extends TableGateway{

	//variables de la tabla
	private $num_codigo;
        private $id_facultad;	
        private $id_monitor;	
	private $id_programa_curricular;
	private $creditos_aprobados;
	private $horas_monitoria;
	private $id_usuario;
	private $id_aplicar;
		private $observaciones;
	private $id_estado;
	
	private $facultad;
	private $programa;
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgp_monitores', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->num_codigo = $datos["num_codigo"];
        $this->id_facultad = $datos["id_facultad"];
		$this->id_programa_curricular = $datos["id_programa_curricular"];
		$this->creditos_aprobados = $datos["creditos_aprobados"];
		$this->horas_monitoria = $datos["horas_monitoria"];
		$this->id_usuario = $datos["id_usuario"];
		$this->id_aplicar = $datos["id_aplicar"];
		$this->id_monitor= $datos["id_monitor"];
		$this->facultad = $datos["facultad"];
		$this->programa = $datos["programa"];
		$this->id_estado = $datos["id_estado"];
		$this->observaciones = $datos["observaciones"];
	}

	public function addAplicarm($data=array(), $id){
		self::cargaAtributos($data);

        		$array=array(
			'id_convocatoria'=>$id,
		'num_codigo'=>$this->num_codigo,
        	'id_facultad'=>$this->id_facultad,
        	'id_proyecto'=>$this->id_proyecto,
		'id_programa_curricular'=>$this->id_programa_curricular,
		'id_usuario'=>$this->id_usuario,
            		 );
      		  $this->insert($array);
		return 1;
	}

    

public function updateMonitor($id, $data=array())
    {
        self::cargaAtributos($data);

	
		$arrayestado= array();
		if($this->id_estado!=null){
        			$arrayestado=array
            			(
			  'id_estado'=>$this->id_estado,
             			);
		}

		$arrayobservacion= array();
		if($this->observaciones!=''){
        			$arrayobservacion=array
            			(
			  'observaciones'=>$this->observaciones,
             			);
		}
        $array=array
            (
	);
$array = $array+$arrayestado+$arrayobservacion;

		$this->update($array, array('id_monitor' => $id));

return 1;
    }

public function getCont(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

	public function filtroAplicarm($datos=array()){
		self::cargaAtributos($datos);
		if($this->id_aplicar!=''){
			$id=$this->id_aplicar;
			$rowset = $this->select(function (Where $select) use ($id){
			$select->where(array('id_aplicar  =?'=>$id));
			});
			return $rowset->toArray();
		}
		if($this->id_aplicar=='' ){
		$rowset = $this->select();
		return $rowset->toArray();
		}

	}

	public function filtroMon($datos=array()){
		self::cargaAtributos($datos);
		if($this->id_monitor!=null){
			$id=$this->id_monitor;
			$rowset = $this->select(function (Where $select) use ($id){
			$select->where(array('id_monitor  =?'=>$id));
			});
			return $rowset->toArray();
		}
		if($this->id_monitor=='' ){
		$rowset = $this->select();
		return $rowset->toArray();
		}

	}


	public function filtroMonitoresexcel($facultad, $programa){


		if($facultad!=0 && $programa==0){

$nom='%'.$facultad.'%';
$nom=strtoupper($nom);
			$rowset = $this->select(function (Where $select) use ($nom){
			$select->where(array('upper(id_facultad) LIKE ?'=>$nom));
			});
			return $rowset->toArray();
		}
		if($facultad==0 && $programa!=0){

$nom='%'.$programa.'%';
$nom=strtoupper($nom);
			$rowset = $this->select(function (Where $select) use ($nom){
			$select->where(array('upper(id_programa_curricular) LIKE ?'=>$nom));
			});
			return $rowset->toArray();
		}

		if($facultad!=0 && $programa!=0){

$nom='%'.$facultad.'%';
$nom=strtoupper($nom);
$nom2='%'.$programa.'%';
$nom2=strtoupper($nom);


			$rowset = $this->select(function (Where $select) use ($nom, $nom2){
			$select->where(array('upper(id_facultad) LIKE ?'=>$nom,'upper(id_programa_curricular) LIKE ?'=>$nom2));
			});
			return $rowset->toArray();
		}
		if($facultad==0 && $programa==0){
		$rowset = $this->select();
		return $rowset->toArray();
		}

	}


	public function filtroMonitores($datos=array()){


		self::cargaAtributos($datos);
		if($this->facultad!='' && $this->programa==''){

$nom='%'.$this->facultad.'%';
$nom=strtoupper($nom);
			$rowset = $this->select(function (Where $select) use ($nom){
			$select->where(array('upper(id_facultad) LIKE ?'=>$nom));
			});
			return $rowset->toArray();
		}
		if($this->facultad=='' && $this->programa!=''){

$nom='%'.$this->programa.'%';
$nom=strtoupper($nom);
			$rowset = $this->select(function (Where $select) use ($nom){
			$select->where(array('upper(id_programa_curricular) LIKE ?'=>$nom));
			});
			return $rowset->toArray();
		}

		if($this->facultad!='' && $this->programa!=''){

$nom='%'.$this->facultad.'%';
$nom=strtoupper($nom);
$nom2='%'.$this->programa.'%';
$nom2=strtoupper($nom);


			$rowset = $this->select(function (Where $select) use ($nom, $nom2){
			$select->where(array('upper(id_facultad) LIKE ?'=>$nom,'upper(id_programa_curricular) LIKE ?'=>$nom2));
			});
			return $rowset->toArray();
		}
		if($this->facultad=='' && $this->programa==''){
		$rowset = $this->select();
		return $rowset->toArray();
		}

	}

	public function addProyectosconvm($num,$inv,$facultad,$procur,$idap,$proyecto){


        	$array=array(
		'num_codigo'=>$num,
		'id_usuario'=>$inv,
		'id_facultad'=>$facultad,
		'id_programa_curricular'=>$procur,
		'id_proyecto'=>$proyecto,
		'id_estado'=>1,
		'id_aplicar'=>$idap,
            		 );
      		  $this->insert($array);

		$id = $this->getAdapter()->getDriver()->getLastGeneratedValue("mgp_monitores_id_monitor_seq");


		return $id;
	}

    	public function getMonitor($id){
		$resultSet = $this->select(array('id_monitor'=>$id));
		return $resultSet->toArray();
   	}


    	public function getMonitorh(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}



    	public function getAplicarusuariom($id){

		$resultSet = $this->select(array('id_usuario'=>$id));
		return $resultSet->toArray();
}


    public function getID($id){
$filter = new StringTrim();
		$resultSet = $this->select(function ($select) use ($id){
		$select->columns(array('id_convocatoria'
				 ));
		$select->where(array('id_aplicar = ?'=>$id));
		}); 
$row = $resultSet->current();

return $row;
    }

    	public function getAplicarmconv($id){
		$resultSet = $this->select(array('id_convocatoria'=>$id));
		return $resultSet->toArray();
   	}

public function getAplicar($id){
		$resultSet = $this->select(array('id_aplicar'=>$id));
		return $resultSet->toArray();
   	}

    	public function getAplicarmid($id){
		$id = (int) $id;
		$rowset = $this->select(array('id_aplicar'=>$id));
		$row = $rowset->current();
		if(!$row){
			throw new \Exception("no hay id asociado");
		}

	  return $row;
}

    	public function getAplicarh(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarAplicar($id) {
        		$this->delete(array('id_aplicar' => $id));
    	}

	public function mensajeProyectos($mensaje,$idproy,$email)
	{
	$filter = new StringTrim();
	$idproy=$filter->filter($idproy);
	$email=$filter->filter($email);

$message=$filter->filter($mensaje);

	$message = new \Zend\Mail\Message();
	$message->setBody($filter->filter($mensaje));
	$message->setFrom('ricardo.sanchez.villabon@gmail.com');
	$message->setSubject('solicitud propuesta de investigacion');
	$message->addTo($email);
	
	$smtOptions = new \Zend\Mail\Transport\SmtpOptions();
	$smtOptions->setHost('correoupn.pedagogica.edu.co')
			   ->setport(25);
	
	$transport = new \Zend\Mail\Transport\Smtp($smtOptions);
	$transport->send($message);



	}
}
?>