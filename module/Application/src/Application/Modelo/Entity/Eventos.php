<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Eventos extends TableGateway{
	//variables de la tabla
	private $titulo;
	private $evento;
	private $fecha_inicio;
	private $fecha_fin;
	private $estado;
	private $url;
	private $archivo;
	//funcion constructor
    public function __construct(Adapter $adapter = null, 
								$databaseSchema =null,
								ResultSet $selectResultPrototype =null){
		return parent::__construct('hsi_eventos', 
									$adapter, 
									$databaseSchema,
									$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->titulo=$datos["titulo"];
		$this->evento=$datos["evento"];
		$this->fecha_inicio=$datos["fecha_inicio"];
		$this->estado=$datos["estado"];
		$this->fecha_fin=$datos["fecha_fin"];
		$this->url=$datos["url"];
		$this->archivo=$datos["archivo"];
	}

	public function addEvento($data=array(), $archi, $random){
		self::cargaAtributos($data);

        $array=array
            (
            'titulo'=>$this->titulo,
			'evento'=>$this->evento,
            'estado'=>$this->estado,
			'fecha_inicio'=>$this->fecha_inicio,
			'fecha_fin'=>$this->fecha_fin,
			'url'=>$this->url,
			'archivo'=>$archi,
			'new_archivo' => $random
             );
        $this->insert($array);

		$id = $this->getAdapter()->getDriver()->getLastGeneratedValue("hsi_eventos_id_evento_seq");


		$s= 'ID='.$id.    'titulo'.'='.$this->titulo.','.
			'evento'.'='.$this->evento.','.
            		'estado'.'='.$this->estado.','.
			'fecha_inicio'.'='.$this->fecha_inicio.','.
			'url'.'='.$this->url.','.
			'fecha_fin'.'='.$this->fecha_fin.','.
			'archivo'.'='.$archi.','.
			'fecha_crea'.'='.now;

return $s;
	}
	
    public function getEventos(){
		$resultSet = $this->select();
		return $resultSet->toArray();
    }

    public function getEventosh(){

		$tit='A';
			$rowset = $this->select(function (Where $select) use ($tit){
			$select->where(array('upper(estado) LIKE ?'=>$tit));
			$select->order(array('fecha_inicio ASC'));
			});
			return $rowset->toArray();
    }

    public function getEventosid($id){
		$resultSet = $this->select(array('id_evento'=>$id));
		return $resultSet->toArray();
    }

    public function eliminarEventos($id)
    {
        $this->delete(array('id_evento' => $id));
    }

	//funcion actualizar tabla hsi_noticias
    public function updateEventos($id, $data=array())
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
			  'evento'=>$this->evento,
			  'url'=>$this->url,
			  'usuario_mod'=>$user,
			  'fecha_mod'=> now
             );
			 
		$array = $array+$arrayestado;


		
		$this->update($array, array('id_evento' => $id));

		$s=     'ID='.$id.',titulo'.'='.$this->titulo.',evento'.'='.$this->evento.',url'.'='.$this->url;

return $s;
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
}

?>