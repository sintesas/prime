<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;

class Solicitudes extends TableGateway{
	private $justificacion;
	private $nueva_fecha;
	private $fecha_inicio;
	private $fecha_fin;
	private $lugar;
	private $fecha;
	private $observacion;
	private $id_tipo_sol;
	private $valor;
	private $id_estado;
	private $id_estado2;
	private $codigo_proy;
	private $codigo;
	private $filtroestado;
	private $filtrosolicitud;
	private $fecha_sol;
    public function __construct(Adapter $adapter = null, 
							   $databaseSchema =null,
							   ResultSet $selectResultPrototype =null){
      return parent::__construct('aps_solicitudes', $adapter, $databaseSchema,$selectResultPrototype);
   }
   
	private function cargaAtributos($datos=array())
	{
		$this->justificacion=$datos["justificacion"];
		$this->nueva_fecha=$datos["nueva_fecha"];
		$this->fecha_inicio=$datos["fecha_inicio"];
		$this->fecha_fin=$datos["fecha_fin"];
		$this->lugar=$datos["lugar"];
		$this->fecha=$datos["fecha"];
		$this->valor=$datos["valor"];
		$this->id_estado2=$datos["id_estado2"];
		$this->id_estado=$datos["id_estado"];
		$this->observacion=$datos["observacion"];
		$this->id_tipo_sol=$datos["id_tipo_sol"];
		$this->codigo_proy=$datos["codigo_proy"];
		$this->codigo=$datos["codigo"];
		$this->filtroestado=$datos["filtroestado"];
		$this->filtrosolicitud=$datos["filtrosolicitud"];
		$this->fecha_sol=$datos["fecha_sol"];
	}
	
    public function getSolicitudes(){
      $resultSet = $this->select(array());
	  return $resultSet->toArray();
    }
	



    public function getSolicitudesmias($id){
      $resultSet = $this->select(array('usuario_crea'=>$id));
	  return $resultSet->toArray();
    }

    public function getSolicitudesid($id){
      $resultSet = $this->select(array('id_sol'=>$id));
	  return $resultSet->toArray();
    }
	
	//funcion enviar mensaje a usuario
	public function mensajeSol($id,$mail,$observa,$esta,$tipo)
	{

	$resultSet = $this->select(array('id_sol'=>$id));
	$vf=$resultSet->toArray();
	



foreach($vf as $mensa){
 $mensa["id_tipo_sol"];
}



if($esta==1){
$estado='Enviado';
}elseif($esta==2){
$estado='En Gestion';
}elseif($esta==3){
$estado='Tramitado';
}

	$filter = new StringTrim();
	echo $filter->filter($mail);

	$message = new \Zend\Mail\Message();
	$message->setBody('Su solicitud #'.$mensa["id_tipo_sol"].' '.trim($tipo).' se encuentra con el siguiente estado: '.$estado.' y tiene la siguiente justificacion  :'.$observa);
	$message->setFrom('ciupPRIME@pedagogica.edu.co');
	$message->setSubject('Respuesta solicitud #'.$mensa["id_tipo_sol"]);
	$message->addTo($filter->filter($mail));
	
	$smtOptions = new \Zend\Mail\Transport\SmtpOptions();
	$smtOptions->setHost('relay01.upn.edu.co')
			   ->setPort(25);
	
	$transport = new \Zend\Mail\Transport\Smtp($smtOptions);
	$transport->send($message);
	}
	
    public function updateSolicitud($id, $data=array(), $user, $mail,$archi,$val=array())
    {
        self::cargaAtributos($data);

foreach($val as $v){
if($this->id_tipo_sol==$v["id_valor_flexible"]){
$tipo =$v["descripcion_valor"];
}
}


		self::mensajeSol($id, $mail, $this->observacion,$this->id_estado,$tipo);
		


        $array=array
            (
			  'observaciones'=>$this->observacion,
			  'id_estado'=>$this->id_estado,
			  'usuario_mod'=>$user,
			  'fecha_mod'=> now
,'archivo_res'=>$archi
             );
		
		$this->update($array, array('id_sol' => $id));
	}
	

public function filtroSolicitudes($datos=array()){
		self::cargaAtributos($datos);
		if($this->filtroestado!=0  && $this->filtrosolicitud=='' && $this->codigo=='' && $this->fecha_sol==''){
			$tit=$this->filtroestado;
			$rowset = $this->select(function (Where $select) use ($tit){
			$select->where(array('id_estado = ?'=>$tit));
			});
			return $rowset->toArray();
		}
		if($this->filtroestado==0  && $this->filtrosolicitud!='' && $this->codigo=='' && $this->fecha_sol==''){
			$not=$this->filtrosolicitud;
			$rowset = $this->select(function (Where $select) use ($not){
			$select->where(array('id_tipo_sol = ?'=>$not));
			});
			return $rowset->toArray();
		}
		if($this->filtroestado==0  && $this->filtrosolicitud=='' && $this->codigo!='' && $this->fecha_sol==''){
			$not='%'.$this->codigo.'%';
			$not=strtoupper($not);
			$rowset = $this->select(function (Where $select) use ($not){
			$select->where(array('upper(codigo_proy) LIKE  ?'=>$not));
			});
			return $rowset->toArray();
		}
		if($this->filtroestado==0  && $this->filtrosolicitud=='' && $this->codigo=='' && $this->fecha_sol!=''){

			$not=$this->fecha_sol;
			$rowset = $this->select(function (Where $select) use ($not){
			$select->where(array('nueva_fecha = ?'=>$not));
			});
			return $rowset->toArray();
		}
		
		if($this->filtroestado!=0  && $this->filtrosolicitud!='' && $this->codigo=='' && $this->fecha_sol==''){
			$not=$this->filtroestado;
			$tit=$this->filtrosolicitud;

			$rowset = $this->select(function (Where $select) use ($not,$tit){
			$select->where(array('id_estado = ?'=>$not,
					 'id_tipo_sol = ?'=>$tit));
			});
			return $rowset->toArray();
		}
		if($this->filtroestado==0  && $this->filtrosolicitud=='' && $this->codigo=='' && $this->fecha_sol==''){
		$rowset = $this->select();
		return $rowset->toArray();
		}

	}

	
	public function addSolconvocatoria($data=array(),$user,$archi,$mail,$val=array())
	{

		//$this->insert($data);
		self::cargaAtributos($data);
foreach($val as $v){
if($this->id_tipo_sol==$v["id_valor_flexible"]){
$tipo =$v["descripcion_valor"];
}
}


        $array=array
            (
            'justificacion'=>$this->justificacion,
            'nueva_fecha'=>now,
            'codigo_proy'=>$this->codigo_proy,
			'usuario_crea'=>$user,
			'fecha_crea'=>now,
			'archivo'=>$archi,
			'id_estado'=>1,
			'id_tipo_sol'=>$this->id_tipo_sol
             );
        $this->insert($array);

	$id = $this->getAdapter()->getDriver()->getLastGeneratedValue("aps_solicitudes_id_sol_seq");
	self::mensajeSol($id, $mail, $this->justificacion,1,$tipo);

		$s= 'ID='.$id.    'justificacion'.'='.$this->justificacion.','.
			'codigo_proy'.'='.$this->codigo_proy.','.
            		'usuario_crea'.'='.$user.','.
			'id_estado'.'= Enviado,'.
		'archivo'.'='.$archi;

return $s;
	}
	
	public function addSolcambioequipo($data=array())
	{
		//$this->insert($data);

		self::cargaAtributos($data);
        $array=array
            (
            'justificacion'=>$this->justificacion,
			'id_tipo_sol'=>2
             );
        $this->insert($array);
	}
   
 	public function addSolsocializacion($data=array(),$user)
	{
		//$this->insert($data);
		self::cargaAtributos($data);
        $array=array
            (
            'justificacion'=>$this->justificacion,
			'id_tipo_sol'=>3,
			'usuario_crea'=>$user,
			'fecha_crea'=>now,
			'id_estado'=>1
             );
        $this->insert($array);
	}
	
 	public function addSolcambiorubro($data=array())
	{
		//$this->insert($data);
		self::cargaAtributos($data);
        $array=array
            (
            'justificacion'=>$this->justificacion,
			'id_tipo_sol'=>4
             );
        $this->insert($array);
	}
	
 	public function addSoltrabajocampo($data=array())
	{
		//$this->insert($data);
		self::cargaAtributos($data);
        $array=array
            (
            'justificacion'=>$this->justificacion,
			'fecha_inicio'=>$this->fecha_inicio,
			'fecha_fin'=>$this->fecha_fin,
			'lugar'=>$this->lugar,
			'id_tipo_sol'=>5
             );
        $this->insert($array);

	}
	
	public function addSolfotocopias($data=array())
	{
		//$this->insert($data);
		self::cargaAtributos($data);
        $array=array
            (
            'justificacion'=>$this->justificacion,
            'fecha'=>$this->fecha,
			'valor'=>$this->valor,
			'id_tipo_sol'=>6
             );
        $this->insert($array);
	}
	
 	public function addSolmaterialbiblio($data=array())
	{
		//$this->insert($data);
		self::cargaAtributos($data);
        $array=array
            (
            'justificacion'=>$this->justificacion,
			'id_tipo_sol'=>7
             );
        $this->insert($array);
	}
	
	public function addSolmateriales($data=array())
	{
		//$this->insert($data);
		self::cargaAtributos($data);
        $array=array
            (
            'justificacion'=>$this->justificacion,
            'fecha'=>$this->fecha,
			'id_tipo_sol'=>8
             );
        $this->insert($array);
	}
	
	public function addSolcompraequipos($data=array())
	{
		//$this->insert($data);
		self::cargaAtributos($data);
        $array=array
            (
            'justificacion'=>$this->justificacion,
            'valor'=>$this->valor,
			'id_tipo_sol'=>9
             );
        $this->insert($array);
	}
	
	public function addSoltransporte($data=array())
	{
		//$this->insert($data);
		self::cargaAtributos($data);
        $array=array
            (
            'justificacion'=>$this->justificacion,
            'fecha'=>$this->fecha,
			'lugar'=>$this->lugar,
			'valor'=>$this->valor,
			'id_tipo_sol'=>10
             );
        $this->insert($array);
	}
	
	public function addSolcontratacion($data=array())
	{
		//$this->insert($data);
		self::cargaAtributos($data);
        $array=array
            (
            'justificacion'=>$this->justificacion,
            'fecha'=>$this->fecha,
			'valor'=>$this->valor,
			'id_tipo_sol'=>11
             );
        $this->insert($array);
	}
	
	public function addSolcancelacion($data=array())
	{
		//$this->insert($data);
		self::cargaAtributos($data);
        $array=array
            (
            'justificacion'=>$this->justificacion,
            'fecha'=>$this->fecha,
			'id_tipo_sol'=>12
             );
        $this->insert($array);
	}

	public function addSolprorroga($data=array())
	{
		//$this->insert($data);
		self::cargaAtributos($data);
        $array=array
            (
            'justificacion'=>$this->justificacion,
            'fecha'=>$this->fecha,
			'id_tipo_sol'=>13
             );
        $this->insert($array);
	}	
	
	public function cambiarEstado($id, $data=array()){
		self::cargaAtributos($data);
		echo "entro";
		$esta = array(
			'id_estado'=>$this->id_estado2
		);
		echo $this->id_estado;
		$this->update($esta, array('id_sol' => $id));
	}
}

?>