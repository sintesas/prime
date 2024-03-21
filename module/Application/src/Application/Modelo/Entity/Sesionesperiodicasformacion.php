<?php
namespace Application\Modelo\Entity;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Sesionesperiodicasformacion extends TableGateway{
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('mgc_sesionesformacion', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addSesionesperiodicasformacion($data=array(), $id){
		$array=array(
			'id_aplicari'=>$id,
			'id_tipo'=>$data->id_tipo,
            'fecha'=>$data->fecha,
            'fecha_fin'=>$data->fecha_fin
		);
		$this->insert($array);
		return 1;
	}

    public function getSesionesperiodicasformacionByaplicar($id){
		$resultSet = $this->select(array('id_aplicari'=>$id));
		return $resultSet->toArray();
   	}

    public function getSesionesperiodicasformacionById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateSesionesperiodicasformacion($id, $data = array())
    {   
        $array = array(
            'id_tipo' => $data->id_tipo,
            'fecha' => $data->fecha,
            'fecha_fin'=>$data->fecha_fin
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }

   	public function getSesionesperiodicasformaciont(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarSesionesperiodicasformacion($id) {
    	$this->delete(array('id' => $id));
    }

    
}
?>