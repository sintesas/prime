<?php
namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Reconocimientos extends TableGateway
{
    // funcion constructor
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        return parent::__construct('mgi_gi_reconocimientos', $adapter, $databaseSchema, $selectResultPrototype);
    }

	public function addReconocimientos($data=array(), $id, $file_name){
		$array=array(
			'id_grupo_inv'=>$id,
			'descripcion'=>$data["descripcion"],
			'valor'=>$data["valor"],
			'num_acto'=>$data["num_acto"],
			'semestre'=>$data["semestre"],
			'nombre' => $data->nombre,
            'archivo' => $file_name
    	);
        $this->insert($array);
		return 1;
	}

    public function getReconocimientos($id){
		$resultSet = $this->select(array('id_grupo_inv'=>$id));
		return $resultSet->toArray();
   	}

    public function getReconocimientosi(){
		$resultSet = $this->select();
		return $resultSet->toArray();
   	}

   	public function eliminarReconocimientos($id) {
        		$this->delete(array('id_reconocimiento' => $id));
    }

    public function getReconoimientoById($id)
    {
        $resultSet = $this->select(array(
            'id_reconocimiento' => $id
        ));
        return $resultSet->toArray();
    }

    public function updateReconocimiento($id, $data = array(), $file_name)
    {   
        $array = array(
            'descripcion' => $data->descripcion,
            'valor' => $data->valor,
            'num_acto' => $data->num_acto,
            'semestre' => $data->semestre,
            'nombre' => $data->nombre,
            'archivo' => $file_name

        );

        $this->update($array, array(
            'id_reconocimiento' => $id
        ));
    }
}
?>
