<?php

namespace Application\Modelo\Entity;
use Zend\Db\Sql\Select as Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select as Where;
use Zend\Filter\StringTrim;
use Zend\Db\Sql\Ddl\Column;
use Zend\I18n\Validator;

class Auditoriadet extends TableGateway{
	private $id_auditoria_det;
	private $id_auditoria;
	private $evento;
    public function __construct(Adapter $adapter = null, 
							   $databaseSchema =null,
							   ResultSet $selectResultPrototype =null,Sql $sql = null){
      return parent::__construct('aps_auditoria_det', $adapter, $databaseSchema,$selectResultPrototype, $sql);
   }

	
    public function getAuditoriadet(){
      $resultSet = $this->select();
	  return $resultSet->toArray();
    }

    public function getAuditoriadetids($id=array()){
		$filter = new StringTrim();

		$rowset = $this->select(function (Where $select) use ( $id){
			$select->where(array('id_auditoria' => $id));
		});

		// $sql = "select * from aps_auditoria_det where id_auditoria in in (" . implode(",", $ids) . ") and evento not like 'Ingreso al sistema' and evento not like 'salir del sistema'";
		// $rowset = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		
		return $rowset->toArray();
    }

    public function getAuditoriadetid($id){
    	$resultSet = $this->select(array('id_auditoria'=>$id));

		// $sql = "select * from aps_auditoria_det where id_auditoria = " . $id . " and evento not like 'Ingreso al sistema' and evento not like 'salir del sistema'";
		// $resultSet = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);

	  	return $resultSet->toArray();
    }

	private function cargaAtributos($datos=array())
	{
		if($datos["id_auditoria"]!=''){
		$this->id_auditoria=$datos["id_auditoria"];
		}
		if($datos["id_auditoria_det"]!=''){
		$this->id_auditoria_det=$datos["id_auditoria_det"];
		}
		if($datos["evento"]!=''){
		$this->evento=$datos["evento"];
		}
	}
	
	public function addAuditoriadet($evento,$datos=array())
	{
		$filter = new StringTrim();
		foreach($datos as $d){
			$id_auditoria = $d["id_auditoria"];
		}

		// self::cargaAtributos($datos);

		$array=array
			(
				'id_auditoria'=>$id_auditoria,
				'evento'=>$filter->filter($evento),
			);
		$this->insert($array);

	}

	public function getAuditoriaDetReportes($ids) {
		$sql = "SELECT CASE WHEN u.segundo_nombre IS NOT NULL THEN upper(concat(u.primer_nombre, ' ', u.segundo_nombre)) ELSE upper(u.primer_nombre) END nombres, CASE WHEN u.segundo_apellido IS NOT NULL THEN upper(concat(u.primer_apellido, ' ', u.segundo_apellido)) ELSE upper(u.primer_apellido) END apellidos, au.fecha_ingreso, au.fecha_salida, ad.evento FROM aps_auditoria_det ad INNER JOIN aps_auditoria au ON ad.id_auditoria = au.id_auditoria INNER JOIN aps_usuarios u ON au.id_usuario = u.id_usuario WHERE au.id_auditoria in (" . implode(",", $ids) . ")";
		$db = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		return $db->toArray();
	}

}

?>