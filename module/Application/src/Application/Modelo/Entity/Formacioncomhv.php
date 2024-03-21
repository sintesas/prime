<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Formacioncomhv extends TableGateway{

	//variables de la tabla
	private $tipo_formacion;
	private $titulo_formacion;
	private $nombre_formacion;
	private $institucion;
	private $fecha_inicio;
	private $fecha_grado;
	private $horas;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('aps_hv_formacion_com', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->tipo_formacion=$datos["tipo_formacion"];
		$this->titulo_formacion=$datos["titulo_formacion"];
		$this->nombre_formacion=$datos["nombre_formacion"];
		$this->institucion=$datos["institucion"];
		$this->fecha_inicio=$datos["fecha_inicio"];
		$this->fecha_grado=$datos["fecha_grado"];
		$this->horas=$datos["horas"];
	}

	public function addFormacioncomhv($data=array(), $id, $file_name){
		self::cargaAtributos($data);

		if($data->institucion==""){
			$data->institucion=0;
		}

        $array=array(
			'id_usuario'=>$id,
			'tipo_formacion'=>$this->tipo_formacion,
			'titulo_formacion'=>$this->titulo_formacion,
			'nombre_formacion'=>$this->nombre_formacion,
			'institucion'=>$data->institucion,
			'fecha_inicio'=>$this->fecha_inicio,
			'fecha_grado'=>$this->fecha_grado,
			'fecha_fin'=>$data->fecha_fin,
			'pais'=>$data->pais,
			'ciudad'=>$data->ciudad,
			'archivo' => $file_name
        );
        
        if($this->horas != ""){
        	$array["horas"] = $this->horas;
        }
      	$this->insert($array);
		return 1;
	}

    	public function getFormacioncomhv($id){
		$resultSet = $this->select(array('id_usuario'=>$id));
		return $resultSet->toArray();
   	}


    	public function getFormacioncomhvt(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarFormacioncomhv($id) {
        		$this->delete(array('id_formacion_com' => $id));
    	}

    public function getFormacioncomhvById($id)
    {
        $resultSet = $this->select(array(
            'id_formacion_com' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateFormacioncomhv($id, $data = array(), $file_name)
    {   
        $array=array(
			'tipo_formacion'=>$data->tipo_formacion,
			'titulo_formacion'=>$data->titulo_formacion,
			'nombre_formacion'=>$data->nombre_formacion,
			'institucion'=>$data->institucion,
			'fecha_inicio'=>$data->fecha_inicio,
			'fecha_grado'=>$data->fecha_grado,
			'fecha_fin'=>$data->fecha_fin,
			'pais'=>$data->pais,
			'ciudad'=>$data->ciudad,
			'archivo' => $file_name
        );

        $this->update($array, array(
            'id_formacion_com' => $id
        ));
    }
}
?>