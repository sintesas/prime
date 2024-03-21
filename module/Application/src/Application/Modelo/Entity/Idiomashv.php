<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Idiomashv extends TableGateway{

	//variables de la tabla
	private $nombre;
	private $oir;
	private $leer;
	private $hablar;
	private $escribir;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('aps_hv_idiomas', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->nombre=$datos["nombre"];
		$this->oir=$datos["oir"];
		$this->leer=$datos["leer"];
		$this->hablar=$datos["hablar"];
		$this->escribir=$datos["escribir"];
	}

	public function addIdiomashv($data=array(), $id, $file_name){
		self::cargaAtributos($data);

        		$array=array(
			'id_usuario'=>$id,
			'nombre'=>$this->nombre,
			'oir'=>$this->oir,
			'leer'=>$this->leer,
			'hablar'=>$this->hablar,
			'escribir'=>$this->escribir,
			'modalidad'=>$data->modalidad,
			'archivo' => $file_name
            		 );
      		  $this->insert($array);
		return 1;
	}

    	public function getIdiomashv($id){
		$resultSet = $this->select(array('id_usuario'=>$id));
		return $resultSet->toArray();
   	}

    	public function getIdiomashvt(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarIdiomashv($id) {
        		$this->delete(array('id_idioma' => $id));
    	}
    	
    public function getIdiomashvById($id)
    {
        $resultSet = $this->select(array(
            'id_idioma' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateIdiomashv($id, $data = array(), $file_name)
    {   
        $array=array(
			'nombre'=>$data->nombre,
			'oir'=>$data->oir,
			'leer'=>$data->leer,
			'hablar'=>$data->hablar,
			'escribir'=>$data->escribir,
			'modalidad'=>$data->modalidad,
			'archivo' => $file_name
        );

        $this->update($array, array(
            'id_idioma' => $id
        ));
    }
}
?>