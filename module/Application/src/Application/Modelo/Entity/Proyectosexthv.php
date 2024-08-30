<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Proyectosexthv extends TableGateway{

	//variables de la tabla
 private $codigo_proyecto;

    private $tipo_proyecto;

    private $nombre_proyecto;

    private $fecha_inicio;

    private $fecha_fin;

    private $resumen_ejecutivo;

    private $objetivo_general;

    private $equipo_trabajo;

    private $productos_derivados;
    private $rol;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('aps_hv_proyectos_ext', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->codigo_proyecto=$datos["codigo_proyecto"];
		$this->tipo_proyecto=$datos["tipo_proyecto"];
		$this->fecha_inicio=$datos["fecha_inicio"];
		$this->nombre_proyecto=$datos["nombre_proyecto"];
		$this->fecha_fin=$datos["fecha_fin"];
		$this->resumen_ejecutivo=$datos["resumen_ejecutivo"];
		$this->objetivo_general=$datos["objetivo_general"];
		$this->equipo_trabajo=$datos["equipo_trabajo"];
		$this->productos_derivados=$datos["productos_derivados"];
		$this->rol=$datos["rol"];
	}

	public function addProyectosexthv($data=array(), $id, $archi){
		self::cargaAtributos($data);

        		$array=array(
			'id_usuario'=>$id,
			'codigo_proyecto'=>$this->codigo_proyecto,
			'tipo_proyecto'=>$this->tipo_proyecto,
			'fecha_inicio'=>$this->fecha_inicio,
			'fecha_fin'=>$this->fecha_fin,
			'nombre_proyecto'=>$this->nombre_proyecto,
			'resumen_ejecutivo'=>$this->resumen_ejecutivo,
			'objetivo_general'=>$this->objetivo_general,
			'equipo_trabajo'=>$this->equipo_trabajo,
			'productos_derivados'=>$this->productos_derivados,
			'id_rol'=>$this->rol,
            'archivo' => $archi
            		 );
      		  $this->insert($array);
		return 1;
	}

    	public function getProyectosexthv($id){
		$resultSet = $this->select(array('id_usuario'=>$id));
		return $resultSet->toArray();
   	}

    	public function getProyectosexthvt(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarProyectosexthv($id) {
        		$this->delete(array('id_proyecto_ext' => $id));
    	}

    public function getProyectosexthvById($id)
    {
        $resultSet = $this->select(array(
            'id_proyecto_ext' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateProyectosexthv($id, $data = array())
    {   
        $array = array(
            'codigo_proyecto' => $data->codigo_proyecto,
            'fecha_fin' => $data->fecha_fin,
            'fecha_inicio' => $data->fecha_inicio,
            'resumen_ejecutivo' => $data->resumen_ejecutivo,
            'objetivo_general' => $data->objetivo_general,
            'productos_derivados' => $data->productos_derivados,
            'nombre_proyecto' => $data->nombre_proyecto
        );

        $this->update($array, array(
            'id_proyecto_ext' => $id
        ));
        return 1;
    }

    public function updatearchivoProyectosexthv($id, $archi)
    {   
        $array = array(
            'archivo' => $archi
        );

        $this->update($array, array(
            'id_proyecto_ext' => $id
        ));
        return 1;
    }

	public function getReportesbyProyectosexthv($id) {
		$sql = "select u.id_usuario, fn_get_nombres(u.id_usuario) nombre_completo, u.documento, p.id_proyecto_ext, p.codigo_proyecto, p.tipo_proyecto, p.fecha_inicio, p.fecha_fin, p.resumen_ejecutivo, p.objetivo_general, p.equipo_trabajo, p.productos_derivados, p.nombre_proyecto, p.id_rol, p.archivo from aps_hv_proyectos_ext p left join aps_usuarios u on p.id_usuario = u.id_usuario where u.id_usuario is not null and p.id_usuario = " . $id . ";";
		$statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
	}
}
?>