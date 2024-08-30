<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Experiencialabhv extends TableGateway{

	//variables de la tabla
	private $empresa;
	private $tipo_vinculacion;
	private $dedicacion_horaria;
	private $periodo_vinculacion;
	private $cargo;
	private $descripcion_actividades;
	private $otra_info;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('aps_experiencia_prof', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->empresa=$datos["empresa"];
		$this->tipo_vinculacion=$datos["tipo_vinculacion"];
		$this->dedicacion_horaria=$datos["dedicacion_horaria"];
		$this->cargo=$datos["cargo"];
		$this->descripcion_actividades=$datos["descripcion_actividades"];
		$this->otra_info=$datos["otra_info"];
	}

	public function addExperiencialabhv($data=array(), $id, $file_name){
		self::cargaAtributos($data);
		$array=array(
			'id_usuario'=>$id,
			'empresa'=>$this->empresa,
			'tipo_vinculacion'=>$this->tipo_vinculacion,
			'dedicacion_horaria'=>$this->dedicacion_horaria,
			'cargo'=>$this->cargo,
			'descripcion_actividades'=>$this->descripcion_actividades,
			'otra_info'=>$this->otra_info,
			'archivo' => $file_name
        );
        if ($data->fecha_fin!="") {
        	$array['fecha_fin'] = $data->fecha_fin;
        }
  		if($data->fecha_inicio !=""){
			$array['fecha_inicio'] = $data->fecha_inicio;
		}
    	
  		$this->insert($array);
		return 1;
	}

    	public function getExperiencialabhv($id){
		$resultSet = $this->select(array('id_usuario'=>$id));
		return $resultSet->toArray();
   	}

    	public function getExperiencialabhvt(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarExperiencialabhv($id) {
        		$this->delete(array('id_experiencia_prof' => $id));
    	}
    	
    public function getExperiencialabhvById($id)
    {
        $resultSet = $this->select(array(
            'id_experiencia_prof' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateExperiencialabhv($id, $data = array(), $file_name)
    {   
        $array=array(
			'empresa'=>$data->empresa,
			'tipo_vinculacion'=>$data->tipo_vinculacion,
			'dedicacion_horaria'=>$data->dedicacion_horaria,
			'fecha_inicio'=>$data->fecha_inicio,
			'cargo'=>$data->cargo,
			'descripcion_actividades'=>$data->descripcion_actividades,
			'otra_info'=>$data->otra_info,
			'archivo' => $file_name
        );

        $this->update($array, array(
            'id_experiencia_prof' => $id
        ));
    }

	public function getReportesbyExperiencialabhv($id) {
		$sql = "select u.id_usuario, fn_get_nombres(e.id_usuario) nombre_completo, u.documento, e.id_experiencia_prof, e.empresa, e.tipo_vinculacion, e.dedicacion_horaria, e.periodo_vinculacion, e.cargo, e.descripcion_actividades, e.otra_info, e.fecha_inicio, e.fecha_fin, e.archivo from aps_experiencia_prof e left join aps_usuarios u on e.id_usuario = u.id_usuario where u.id_usuario is not null and e.id_usuario = " . $id . ";";
		$statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
	}
}
?>