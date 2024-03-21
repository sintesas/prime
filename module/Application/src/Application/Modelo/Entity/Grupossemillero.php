<?php
namespace Application\Modelo\Entity;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Grupossemillero extends TableGateway{
	//funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema =null, ResultSet $selectResultPrototype =null){
		return parent::__construct('msi_grupossemillero', $adapter, $databaseSchema, $selectResultPrototype);
	}
   
	public function addGruposemillero($id, $id2){
		print_r($data);
		$array=array(
			'id_semillero'=>$id2,
			'id_grupo'=>$id,
        );
  		$this->insert($array);
		return 1;
	}

    public function getGrupossemillero($id){
		$resultSet = $this->select(array('id_semillero'=>$id));
		return $resultSet->toArray();
   	}

    public function getGrupossemillerot(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarGrupossemillero($id) {
        		$this->delete(array('id' => $id));
    }
    
    public function getGruposById($id)
    {
        $resultSet = $this->select(array(
            'id' => $id
        ));
        return $resultSet->toArray();
    }
    
    public function updateGrupossemillero($id, $id_usuario)
    {   
        $array = array(
            'id_grupo' => $id_grupo,
            'archivo' => $file_name      
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }

    public function updateArchivo($id, $file_name)
    {   
        $array = array(
            'archivo' => $file_name
        );

        $this->update($array, array(
            'id' => $id
        ));
        return 1;
    }
}
?>