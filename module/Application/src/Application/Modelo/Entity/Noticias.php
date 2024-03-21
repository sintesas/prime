<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Noticias extends TableGateway{
	//variables de la tabla
	private $titulo;
	private $noticia;
	private $fecha_inicio;
	private $fecha_fin;
	private $estado;
	private $url;
	//funcion constructor
    public function __construct(Adapter $adapter = null, 
								$databaseSchema =null,
								ResultSet $selectResultPrototype =null){
		return parent::__construct('hsi_noticias', 
									$adapter, 
									$databaseSchema,
									$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->titulo=$datos["titulo"];
		$this->noticia=$datos["noticia"];
		$this->fecha_inicio=$datos["fecha_inicio"];
		$this->estado=$datos["estado"];
		$this->fecha_fin=$datos["fecha_fin"];
		$this->url=$datos["url"];
	}

	public function addNoticia($data=array(),$archi, $rand){
		self::cargaAtributos($data);
        $array=array
            (
            'titulo'=>$this->titulo,
			'noticia'=>$this->noticia,
            'estado'=>$this->estado,
			'fecha_inicio'=>$this->fecha_inicio,
			'url'=>$this->url,
			'fecha_fin'=>$this->fecha_fin,
			'fecha_crea'=>now,
			'new_archivo'=> $rand,
'archivo'=>$archi
             );



        $this->insert($array);
		$id = $this->getAdapter()->getDriver()->getLastGeneratedValue("hsi_noticias_id_noticia_seq");

		$s= 'ID='.$id.    'titulo'.'='.$this->titulo.','.
			'noticia'.'='.$this->noticia.','.
            		'estado'.'='.$this->estado.','.
			'fecha_inicio'.'='.$this->fecha_inicio.','.
			'url'.'='.$this->url.','.
			'fecha_fin'.'='.$this->fecha_fin.','.
			'fecha_crea'.'='.now.','.
			'archivo'.'='.$archi;


return $s;
	}
	
    public function getNoticiash(){
		$tit='A';
			$rowset = $this->select(function (Where $select) use ($tit){
			$select->where(array('upper(estado) LIKE ?'=>$tit));
			$select->order(array('fecha_inicio DESC'));
			});
			return $rowset->toArray();
    }

    public function getNoticias(){
		$resultSet = $this->select();
		return $resultSet->toArray();
    }

    public function getNoticiasid($id){
		$resultSet = $this->select(array('id_noticia'=>$id));
		return $resultSet->toArray();
    }

    public function eliminarNoticias($id)
    {
        $this->delete(array('id_noticia' => $id));
    }

	//funcion actualizar tabla hsi_noticias
    public function updateNoticias($id, $data=array())
    {
        self::cargaAtributos($data);
		$arrayestado= array();
		

		if($this->estado!=''){
        $arrayestado=array
            (
			  'estado'=>$this->estado,
             );
		}

		$arrayurl= array();
		

		if($this->url!=''){
        $arrayurl=array
            (
			  'url'=>$this->url,
             );
		}
		
        $array=array
            (
			  'titulo'=>$this->titulo,
			  'noticia'=>$this->noticia,
			  'usuario_mod'=>$user,
			  'fecha_mod'=> now
             );
			 

		$array = $array+$arrayestado+$arrayurl;

		$s=     'ID='.$id.',titulo'.'='.$this->titulo.','.
			'noticia'.'='.$this->noticia.','.
            		'estado'.'='.$this->estado.','.
			'url'.'='.$this->url;
		
		$this->update($array, array('id_noticia' => $id));
return $s;
    }

	//funcion para filtrar noticias
	public function filtroNoticias($datos=array()){
		self::cargaAtributos($datos);
		if($this->titulo!=''){
			$tit='%'.$this->titulo.'%';
			$tit=strtoupper($tit);
			$rowset = $this->select(function (Where $select) use ($tit){
			$select->where(array('upper(titulo) LIKE ?'=>$tit));
			});
			return $rowset->toArray();
		}
		if($this->noticia!=''){
			$not='%'.$this->noticia.'%';
			$not=strtoupper($not);
			$rowset = $this->select(function (Where $select) use ($not){
			$select->where(array('upper(noticia) LIKE ?'=>'%'.$not.'%'));
			});
			return $rowset->toArray();
		}
		
		if($this->titulo!='' && $this->noticia!=''){
			$not='%'.$this->noticia.'%';
			$tit='%'.$this->titulo.'%';
			$not=strtoupper($not);
			$tit=strtoupper($tit);
			$rowset = $this->select(function (Where $select) use ($not,$tit){
			$select->where(array('upper(noticia) LIKE ?'=>'%'.$not.'%',
					 'upper(titulo) LIKE ?'=>$tit));
			});
			return $rowset->toArray();
		}
		if($this->titulo=='' && $this->noticia==''){
		$rowset = $this->select();
		return $rowset->toArray();
		}

	}

	public function updateNoticiasImage($id, $imagen)
    {
        $array=array(
		  'imagen'=> $imagen
        );
		$this->update($array, array('id_noticia' => $id));
		return $s;
    }
}

?>