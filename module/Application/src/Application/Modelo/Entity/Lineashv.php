<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Lineashv extends TableGateway{

	//variables de la tabla
	private $nombre_linea;
	private $objetivo;
	private $logros;
	private $efectos;
	private $id_estado;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('aps_hv_lineas', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->nombre_linea=$datos["nombre_linea"];
		$this->objetivo=$datos["objetivo"];
		$this->logros=$datos["logros"];
		$this->efectos=$datos["efectos"];
		$this->id_estado=$datos["id_estado"];
	}

	public function addLineashv($data=array(), $id, $file_name){
		self::cargaAtributos($data);

        		$array=array(
			'id_usuario'=>$id,
			'nombre_linea'=>$this->nombre_linea,
			'objetivo'=>$this->objetivo,
			'logros'=>$this->logros,
			'id_estado'=>$this->id_estado,
			'efectos'=>$this->efectos,
			'archivo' => $file_name
            		 );
      		  $this->insert($array);
		return 1;
	}

    	public function getLineashv($id){
		$resultSet = $this->select(array('id_usuario'=>$id));
		return $resultSet->toArray();
   	}

    	public function getLineashvt(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarLineashv($id) {
        		$this->delete(array('id_linea_inv' => $id));
    	}

    
     public function getLineaById($id)
    {
        $resultSet = $this->select(array(
            'id_linea_inv' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateLineas($id, $data = array(), $file_name)
    {   
        $array = array(
            'nombre_linea' => $data->nombre_linea,
            'objetivo' => $data->objetivo,
            'logros' => $data->logros,
            'efectos' => $data->efectos,
            'id_estado' => $data->id_estado,
            'archivo' => $file_name
        );

        $this->update($array, array(
            'id_linea_inv' => $id
        ));
    }

	public function getReportesbyLineashv($id) {
		$sql = "select u.id_usuario, concat(u.nombres, ' ', u.apellidos) nombre_completo, u.documento, l.id_linea_inv, l.nombre_linea, l.objetivo, l.efectos, l.logros, case when l.id_estado = 1 then 'Activa' when l.id_estado = 0 then 'Inactiva' else NULL end estado, l.archivo from aps_hv_lineas l left join vw_usuarios_personal u on l.id_usuario = u.id_usuario where u.id_usuario is not null and l.id_usuario = " . $id . ";";
		$statement = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        return  $statement->toArray();
	}
}
?>