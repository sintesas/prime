<?php

namespace Application\Modelo\Entity;
use Zend\Db\Sql\Select as Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
class Roles extends TableGateway{
	private $descripcion;
		private $descripcion2;
		private $observaciones;
	private $activo;
	private $tipovalorpadre;
    public function __construct(Adapter $adapter = null, 
							   $databaseSchema =null,
							   ResultSet $selectResultPrototype =null){
      return parent::__construct('aps_roles', $adapter, $databaseSchema,$selectResultPrototype);
   }
   
	private function cargaAtributos($datos=array())
	{
		$this->descripcion=$datos["descripcion"];
		$this->observaciones=$datos["observaciones"];

	}
	
	
    public function getRoles(){
      $resultSet = $this->select();
	  return $resultSet->toArray();
    }

    public function getRolesid($id){
      $resultSet = $this->select(array('id_rol'=>$id));
	  return $resultSet->toArray();
    }
	
	public function addRoles($data=array(),$rol=array())
	{
		//$this->insert($data);
		$c=0;
		$resultado=1;
		self::cargaAtributos($data);
		$filter = new StringTrim();
		$upper = new StringToUpper();
		foreach($rol as $r){
			if($upper->filter($filter->filter($r['descripcion']))==$upper->filter($this->descripcion)){
			 $c=1;
			}
		}
				if($c==0){
				$array=array
				(
					'descripcion'=>$this->descripcion,
					'observaciones'=>$this->observaciones,
					'opcion_pantalla'=>$data["opcion_pantalla"]
				);
				$this->insert($array);
				return $resultado;
				}else{
				return 0;
				}
		
		
	}
	
	public function verificarRol()
	{
      $resultSet = $this->select();
	  return $resultSet->toArray();
	}
   
    public function eliminarRoles($id)
    {
        $this->delete(array('id_rol' => $id));
    }

    public function updateRol($id, $data = array())
    {   
        $array=array
		(
			'descripcion'=>$data->descripcion,
			'observaciones'=>$data->observaciones,
			'opcion_pantalla'=>$data["opcion_pantalla"]
		);
        $this->update($array, array(
            'id_rol' => $id
        ));
        return 1;
    }

}

?>