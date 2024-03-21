<?php
namespace Application\Modelo\Entity;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Agregarresponsable extends TableGateway{
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('mgc_responsablesap', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addAgregarresponsable($data=array(), $id, $id2, $tipo){
		$array=array(
			'id_aplicari'=>$id,
			'id_rol'=>$data->id_rol,
            'tipo'=>$tipo,
            'id_padre'=>$id2
		);
		$this->insert($array);
		return 1;
	}

    public function getAgregarresponsableByaplicar($id){
		$resultSet = $this->select(array('id_aplicari'=>$id));
		return $resultSet->toArray();
   	}

    public function getAgregarresponsableById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateAgregarresponsable($id, $data = array())
    {   
        $array = array(
            'id_rol' => $data->id_rol,
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }






   	public function getAgregarresponsablet(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarAgregarresponsable($id) {
    	$this->delete(array('id' => $id));
    }

    
}
?>