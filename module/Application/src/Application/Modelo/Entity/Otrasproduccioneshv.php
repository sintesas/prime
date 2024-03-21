<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Otrasproduccioneshv extends TableGateway{

	//variables de la tabla
	private $nombre_producto;
	private $descripcion_producto;
	private $tipo_producto;
	private $mes;
	private $ano;
	private $id_autor;
	private $id_pais;
	private $id_ciudad;
	private $id_departamento;
	private $instituciones;
	private $registro;
	private $otra_info;
	
	//funcion constructor
	public function __construct(Adapter $adapter = null, 
				$databaseSchema =null,
				ResultSet $selectResultPrototype =null){
					return parent::__construct('aps_hv_otra_prod', 
								$adapter, 
								$databaseSchema,
								$selectResultPrototype);
	}
   
	private function cargaAtributos($datos=array())
	{
		$this->nombre_producto=$datos["nombre_producto"];
		$this->descripcion_producto=$datos["descripcion_producto"];
		$this->tipo_producto=$datos["tipo_producto"];
		$this->id_pais=$datos["id_pais"];
		$this->id_ciudad=$datos["id_ciudad"];
		$this->instituciones=$datos["instituciones"];
		$this->registro=$datos["registro"];
		$this->otra_info=$datos["otra_info"];
		$this->mes=$datos["mes"];
		$this->ano=$datos["ano"];
		$this->id_autor=$datos["id_autor"];
	}

	public function addOtrasproduccioneshv($data=array(), $id, $archi){
		self::cargaAtributos($data);

        		$array=array(
			'id_usuario'=>$id,
			'nombre_producto'=>$this->nombre_producto,
			'descripcion_producto'=>$this->descripcion_producto,
			'tipo_producto'=>$this->tipo_producto,
			'id_pais'=>$this->id_pais,
			'id_ciudad'=>$this->id_ciudad,
			'instituciones'=>$this->instituciones,
			'registro'=>$this->registro,
			'otra_info'=>$this->otra_info,
			'mes'=>$this->mes,
			'ano'=>$this->ano,
			'id_autor'=>$this->id_autor,
            'archivo' => $archi
            		 );
      		  $this->insert($array);
		return 1;
	}

    	public function getOtrasproduccioneshv($id){
		$resultSet = $this->select(array('id_usuario'=>$id));
		return $resultSet->toArray();
   	}

    	public function getOtrasproduccioneshvt(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarOtrasproduccioneshv($id) {
        		$this->delete(array('id_otra_prod' => $id));
    	}

    public function getOtrasproduccioneshvById($id)
    {
        $resultSet = $this->select(array(
            'id_otra_prod' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateOtrasproduccioneshv($id, $data = array())
    {   
        $array = array(
            'nombre_producto' => $data->nombre_producto,
            'descripcion_producto' => $data->descripcion_producto,
            'tipo_producto' => $data->tipo_producto,
            'id_pais' => $data->id_pais,
            'id_ciudad' => $data->id_ciudad,
            'instituciones' => $data->instituciones,
            'registro' => $data->registro,
            'autores' => $data->autores,
            'otra_info' => $data->otra_info,
            'mes' => $data->mes,
            'ano' => $data->ano,
            'id_autor' => $data->id_autor
        );

        $this->update($array, array(
            'id_otra_prod' => $id
        ));
        return 1;
    }

    public function updatearchivoOtrasproduccioneshv($id, $archi)
    {   
        $array = array(
            'archivo' => $archi
        );

        $this->update($array, array(
            'id_otra_prod' => $id
        ));
        return 1;
    }
}
?>