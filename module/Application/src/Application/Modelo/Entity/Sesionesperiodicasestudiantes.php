<?php
namespace Application\Modelo\Entity;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Sesionesperiodicasestudiantes extends TableGateway{
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('mgc_sesionesestudiantes', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addSesionesperiodicasestudiantes($data=array(), $id){
		$array=array(
			'id_aplicari'=>$id,
			'sesion'=>$data->sesion,
            'id_rol'=>$data->id_rol,
            'fecha'=>$data->fecha,
            'fecha_fin'=>$data->fecha_fin
		);
		$this->insert($array);
		return 1;
	}

    public function getSesionesperiodicasestudiantesByaplicar($id){
		$resultSet = $this->select(array('id_aplicari'=>$id));
		return $resultSet->toArray();
   	}

    public function getSesionesperiodicasestudiantesById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateSesionesperiodicasestudiantes($id, $data = array())
    {   
        $array = array(
            'sesion'=>$data->sesion,
            'id_rol'=>$data->id_rol,
            'fecha'=>$data->fecha,
            'fecha_fin'=>$data->fecha_fin
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }

   	public function getSesionesperiodicasestudiantest(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarSesionesperiodicasestudiantes($id) {
    	$this->delete(array('id' => $id));
    }

    
}
?>