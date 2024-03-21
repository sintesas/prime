<?php
namespace Application\Modelo\Entity;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Semilleros extends TableGateway{
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('mgi_gi_semilleros_tabla', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addSemilleros($data=array(), $id, $file_name){
		$array=array(
			'id_grupo_inv'=>$id,
			'id_semillero'=>$data->id_semillero,
			'archivo' => $file_name
		);
		$this->insert($array);
		return 1;
	}

    public function getSemilleros($id){
		$resultSet = $this->select(array('id_grupo_inv'=>$id));
		return $resultSet->toArray();
   	}

   	public function getSemillerost(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarSemilleros($id) {
    	$this->delete(array('id' => $id));
    }

    public function getSemilleroById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }

     public function updateSemillero($id, $data = array(), $file_name)
    {   
        $array = array(
            'id_semillero' => $data->id_semillero,
			'archivo' => $file_name
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }
}
?>