<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Foro extends TableGateway{
	//variables de la tabla
	
	private $id_autor;
	private $titulo;
	private $mensaje;
	private $fecha;
	private $respuestas;
	private $identificador;
	private $ult_respuesta;
	private $estado;
	private $archivo;
	//funcion constructor
    public function __construct(Adapter $adapter = null, 
								$databaseSchema =null,
								ResultSet $selectResultPrototype =null){
		return parent::__construct('hsi_foro', 
									$adapter, 
									$databaseSchema,
									$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->titulo=$datos["titulo"];
		$this->id_autor=$datos["id_autor"];
		$this->mensaje=$datos["mensaje"];
		$this->estado=$datos["estado"];
		$this->fecha=$datos["fecha"];
		$this->respuestas=$datos["respuestas"];
		$this->identificador=$datos["identificador"];
		$this->ult_respuesta=$datos["ult_respuesta"];
		$this->archivo=$datos["archivo"];
	}

	public function addForo($data=array(), $archi,$id_usuario){
		self::cargaAtributos($data);

        $array=array
            (
			'id_autor'=>$id_usuario,
           			'titulo'=>$this->titulo,
			'mensaje'=>$this->mensaje,
			'fecha'=> now,
  			'identificador'=> null,
  			'estado'=> 'H',
			'archivo'=>$archi
             );
        $this->insert($array);
		$id = $this->getAdapter()->getDriver()->getLastGeneratedValue("hsi_foro_id_foro_seq");

		$s= 'ID='.$id.    'titulo'.'='.$this->titulo.','.
			'mensaje'.'='.$this->mensaje.','.
            		'estado'.'= H,'.
			'fecha'.'='.now.','.
			'id_autor'.'='.$id_usuario.','.
			'archivo'.'='.$archi;


return $s;
	}

	public function addRespuesta($data=array(), $archi,$id_usuario,$id){
		self::cargaAtributos($data);
if($archi==''){
        $array=array
            (
			'id_autor'=>$id_usuario,
			'mensaje'=>$this->mensaje,
			'fecha'=> now,
  			'identificador'=> $id,
  			'estado'=> 'H'
             );
}else{
        $array=array
            (
			'id_autor'=>$id_usuario,
			'mensaje'=>$this->mensaje,
			'fecha'=> now,
  			'identificador'=> $id,
  			'estado'=> 'H',
			'archivo'=> $archi
             );
}
        $this->insert($array);
return 1;
	}
	
    public function getForo(){
		$resultSet = $this->select(array('identificador'=>null));
		return $resultSet->toArray();
    }

    public function getRespuestas($id){
		$resultSet = $this->select(array('identificador'=>$id));
		return $resultSet->toArray();
    }

    public function getForoid($id){
		$resultSet = $this->select(array('id_foro'=>$id));
		return $resultSet->toArray();
    }

    public function eliminarForo($id)
    {
        $this->delete(array('id_foro' => $id));
    }

    public function contRespuestas($id){
		$resultSet = $this->select(array('id_foro'=>$id));
		return $resultSet->toArray();
    }

    public function actualizaforo($id)
    {

        $cont=self::contRespuestas($id);
foreach($cont as $c){
print_r($c['respuestas']);
}

$conteo=$c['respuestas']+1;

echo $conteo;
        $array=array
            (
			  'ult_respuesta'=>now,
			  'respuestas'=>$conteo,
			  'usuario_mod'=>$user,
			  'fecha_mod'=> now
             );
		
		$this->update($array, array('id_foro' => $id));
    }

	//funcion actualizar tabla hsi_noticias
    public function updateForo($id, $data=array())
    {
        self::cargaAtributos($data);
		$arrayestado= array();
		

		if($this->estado!=''){
        $arrayestado=array
            (
			  'estado'=>$this->estado,
             );
		}
		
        $array=array
            (
			  'titulo'=>$this->titulo,
			  'mensaje'=>$this->mensaje,
			  'usuario_mod'=>$user,
			  'fecha_mod'=> now
             );
			 
		$array = $array+$arrayestado;
		
		$this->update($array, array('id_foro' => $id));

		$s=     'ID='.$id.',titulo'.'='.$this->titulo.',mensaje'.'='.$this->mensaje;

return $s;
    }

	//funcion para filtrar noticias
	public function filtroForo($datos=array()){
		self::cargaAtributos($datos);
		if($this->titulo!=''  && $this->mensaje==''){
			$tit='%'.$this->titulo.'%';
			$tit=strtoupper($tit);
			$rowset = $this->select(function (Where $select) use ($tit){
			$select->where(array('upper(titulo) LIKE ?'=>$tit));
			});
			return $rowset->toArray();
		}
		if($this->mensaje!=''  && $this->titulo==''){
			$not='%'.$this->mensaje.'%';
			$not=strtoupper($not);
			$rowset = $this->select(function (Where $select) use ($not){
			$select->where(array('upper(mensaje) LIKE ?'=>'%'.$not.'%'));
			});
			return $rowset->toArray();
		}
		
		if($this->titulo!='' && $this->mensaje!=''){
			$not='%'.$this->mensaje.'%';
			$tit='%'.$this->titulo.'%';
			$not=strtoupper($not);
			$tit=strtoupper($tit);
			$rowset = $this->select(function (Where $select) use ($not,$tit){
			$select->where(array('upper(mensaje) LIKE ?'=>'%'.$not.'%',
					 'upper(titulo) LIKE ?'=>$tit));
			});
			return $rowset->toArray();
		}
		if($this->titulo=='' && $this->mensaje==''){
		$rowset = $this->select();
		return $rowset->toArray();
		}

	}
}

?>