<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Autores extends TableGateway{

	//variables de la tabla
	private $id_usuario;
	private $id_rol;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('mgi_gi_autores', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->id_usuario=$datos["id_usuario"];
		$this->id_rol=$datos["id_rol"];
	}

	public function addAutores($id_usuario, $id_grupo, $id_padre, $nombre){
		self::cargaAtributos($data);
$filter = new StringTrim();
$nm_pd=$filter->filter($nombre);
        		$array=array(
			'id_usuario'=>$id_usuario,
			'id_padre'=>$id_padre,
			'nombre_padre'=>trim($nm_pd),
			'id_grupo_inv'=>$id_grupo,
            		 );
      		  $this->insert($array);
		return 1;
	}

    public function updateAutores($data=array(),$id,$id2,$id3)
    {

        self::cargaAtributos($data);


		if($this->id_rol!=null){

     			$array=array
            			(
			  		'id_rol'=>$this->id_rol,
             			);
		}

		$this->update($array, array('id_usuario'=>$id2, 'id_grupo_inv'=>$id));
return 1;
   	}

    	public function getAutores($id){
		$resultSet = $this->select(array('id_grupo_inv'=>$id));
		return $resultSet->toArray();
   	}

    	public function getAutoresi(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarAutores($id_usuario, $id_grupo, $id_padre, $nombre) {
$filter = new StringTrim();
$nm_pd=$filter->filter($nombre);
        		$this->delete(array('id_usuario'=>$id_usuario,'id_padre'=>$id_padre,'nombre_padre'=>$nm_pd,'id_grupo_inv'=>$id_grupo));
    	}
}
?>