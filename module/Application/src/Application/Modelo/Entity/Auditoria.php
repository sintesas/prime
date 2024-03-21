<?php

namespace Application\Modelo\Entity;
use Zend\Db\Sql\Select as Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select as Where;
use Zend\Filter\StringTrim;
use Zend\Db\Sql\Ddl\Column;
use Zend\I18n\Validator;

class Auditoria extends TableGateway{
	private $id_usuario;
	private $usuario;
	private $fecha_ingreso;
	private $fecha_salida;
	private $ip_terminal;
	private $evento;
	private $id_pantalla;
    public function __construct(Adapter $adapter = null, 
							   $databaseSchema =null,
							   ResultSet $selectResultPrototype =null,Sql $sql = null){
      return parent::__construct('aps_auditoria', $adapter, $databaseSchema,$selectResultPrototype, $sql);
   }

	
    public function getAuditoria(){
      $resultSet = $this->select();
	  return $resultSet->toArray();
    }

    public function getAuditoriaid($sid,$ip,$id){
      //   $resultSet = $this->select(array('sid'=>$sid,'ip_terminal'=>$ip,'id_usuario'=>$id,'fecha_salida'=>null));
	  $sql = "select id_auditoria from aps_auditoria where sid = '" . $sid . "' and id_usuario = " . $id . " and ip_terminal = '" . $ip . "' and fecha_salida is null";
	  $resultSet = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
	  return $resultSet->toArray();
    }

    public function getAuditoriaids($id=array()){
			$filter = new StringTrim();

			$rowset = $this->select(function (Where $select) use ( $id){
			$select->where(array('id_auditoria' => $id));
			});
			return $rowset->toArray();

    }

    public function getAuditoriaidss($id){
	$filter = new StringTrim();


	$resultSet = $this->select(array('id_auditoria'=>$filter->filter($id)));


	return $resultSet->toArray();	

    }


	private function cargaAtributos($datos=array())
	{
		$this->id_usuario=$datos["id_usuario"];
		$this->fecha_ingreso=$datos["fecha_ingreso"];
		$this->fecha_salida=$datos["fecha_salida"];
		$this->ip_terminal=$datos["ip_terminal"];
		$this->evento=$datos["evento"];
		$this->usuario=$datos["usuario"];
	}
	
	public function addAuditoria($data=array(),$sid)
	{		
	
		if($this->fecha_salida!='0'){
        $arrayfecha_salida=array
            (
			  'fecha_salida'=>$this->fecha_salida,
			'sid'=>$sid,
            );
		}
		//$this->insert($data);
		self::cargaAtributos($data);
        $array=array
            (
            'id_usuario'=>$this->id_usuario,
			'fecha_ingreso'=>$this->fecha_ingreso,
			'ip_terminal'=>$this->ip_terminal
             );
		$array=$array+$arrayfecha_salida;
        $this->insert($array);
	}

	public function addAuditoriasalir($data=array())
	{		
		self::cargaAtributos($data);
        $array=array
            (
			  'fecha_salida'=>now,
			   'evento'=>5
             );
			 		
		$this->update($array, array('ip_terminal' => $this->ip_terminal,'id_usuario'=>$this->id_usuario,'fecha_salida'=>null));
	}

	
	//funcion para filtrar usuarios
	public function filtroAuditoria($datos=array(),$id_usuario){
		self::cargaAtributos($datos);

		$sql = "select au.id_auditoria, concat(u.nombres, ' ', u.apellidos) nombre_completo, au.fecha_ingreso, au.fecha_salida, au.ip_terminal from aps_auditoria au inner join vw_usuarios_personal u on au.id_usuario = u.id_usuario where ";

		if ($id_usuario != 0 && $this->fecha_ingreso != null && $this->fecha_salida == null) {
			$sql = $sql . "u.id_usuario = " . $id_usuario . " and au.fecha_ingreso between '" . $this->fecha_ingreso . "' and '" . date('Y-m-d', strtotime($this->fecha_ingreso . ' + 1 days')) . "'";
		}

		if ($id_usuario != 0 && $this->fecha_ingreso == null && $this->fecha_salida != null) {
			$sql = $sql . "u.id_usuario = " . $id_usuario . " and au.fecha_salida between '" . $this->fecha_salida . "' and '" . date('Y-m-d', strtotime($this->fecha_salida . ' + 1 days')) . "'";
		}

		if ($id_usuario != 0 && $this->fecha_ingreso != null && $this->fecha_salida != null) {
			$sql = $sql . "u.id_usuario = " . $id_usuario . " and au.fecha_ingreso >= date '" . $this->fecha_ingreso . "' and au.fecha_salida < date '" . date('Y-m-d', strtotime($this->fecha_salida . ' + 1 days')) . "'";
		}

		if ($id_usuario == 0 && $this->fecha_ingreso != null && $this->fecha_salida == null) {
			$sql = $sql . "au.fecha_ingreso between '" . $this->fecha_ingreso . "' and '" . date('Y-m-d', strtotime($this->fecha_ingreso . ' + 1 days')) . "'";
		}

		if ($id_usuario == 0 && $this->fecha_ingreso == null && $this->fecha_salida != null) {
			$sql = $sql . "au.fecha_salida between '" . $this->fecha_salida . "' and '" . date('Y-m-d', strtotime($this->fecha_salida . ' + 1 days')) . "'";
		}

		if ($id_usuario == 0 && $this->fecha_ingreso != null && $this->fecha_salida != null) {
			$sql = $sql . "au.fecha_ingreso >= date '" . $this->fecha_ingreso . "' and au.fecha_salida < date '" . date('Y-m-d', strtotime($this->fecha_salida . ' + 1 days')) . "'";
		}

		if ($id_usuario != 0 && $this->fecha_ingreso == null && $this->fecha_salida == null) {
			$sql = $sql. "u.id_usuario = " . $id_usuario;
		}

		$db = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		return $db->toArray();

		// $date = $this->fecha_ingreso;
		// $my_date = date('Y/m/d', strtotime($date));

		// $date2 =strtotime ( '+1 day' , strtotime ( $date ) ) ;
		// $my_date2 = date ( 'Y-m-j' , $date2 );

		// $dates = $this->fecha_salida;
		// $my_dates = date('Y/m/d', strtotime($dates));

		// $date2s =strtotime ( '+1 day' , strtotime ( $dates ) ) ;
		// $my_date2s = date ( 'Y-m-j' , $date2s );

		// if($id_usuario!='' && $this->fecha_ingreso=='' &&  $this->fecha_salida==''){
		// 	$idu=$id_usuario;
		// 	$rowset = $this->select(function (Where $select) use ($idu){
		// 		$select->where(array('id_usuario = ?'=>$idu));
		// 	});
		// 	return $rowset->toArray();
		// }

		// if($this->fecha_ingreso!='' &&  $this->fecha_salida=='' && $id_usuario==0){

		// 	$rowset = $this->select(function (Where $select) use ( $my_date,$my_date2){
		// 	$select->where(array('fecha_ingreso >= ?' => $my_date, 'fecha_ingreso < ?' => $my_date2));
		// 	});
		// 	return $rowset->toArray();
		// }

		// if($this->fecha_ingreso!='' &&  $this->fecha_salida=='' && $id_usuario!=0){
		// 	$idu=$id_usuario;
		// 	$rowset = $this->select(function (Where $select) use ( $my_date,$my_date2,$idu){
		// 	$select->where(array('fecha_ingreso >= ?' => $my_date, 'fecha_ingreso < ?' => $my_date2, 'id_usuario = ?'=>$idu));
		// 	});
		// 	return $rowset->toArray();
		// }

		// if($this->fecha_ingreso=='' &&  $this->fecha_salida!='' && $id_usuario!=0){
		// 	$idu=$id_usuario;
		// 	$rowset = $this->select(function (Where $select) use ( $my_dates,$my_date2s,$idu){
		// 	$select->where(array('fecha_salida >= ?' => $my_dates, 'fecha_salida< ?' => $my_date2s, 'id_usuario = ?'=>$idu));
		// 	});
		// 	return $rowset->toArray();
		// }

		// if($this->fecha_salida!='' && $this->fecha_ingreso=='' && $id_usuario==0){

		// 	$rowset = $this->select(function (Where $select) use ($my_dates,$my_date2s){
		// 	$select->where(array('fecha_salida >= ?' => $my_dates, 'fecha_salida < ?' => $my_date2s));
		// 	});
		// 	return $rowset->toArray();
		// }

		// if($this->fecha_salida!='' && $this->fecha_ingreso!='' && $id_usuario==0){

		// 	$rowset = $this->select(function (Where $select) use ($my_date,$my_date2,$my_dates,$my_date2s){
		// 	$select->where(array('fecha_ingreso >= ?' => $my_date, 'fecha_ingreso < ?' => $my_date2,'fecha_salida >= ?' => $my_dates, 'fecha_salida < ?' => $my_date2s));
		// 	});
		// 	return $rowset->toArray();
		// }

		// if($this->fecha_salida!='' && $this->fecha_ingreso!='' && $id_usuario!=0){
		// 	$idu=$id_usuario;

		// 	$rowset = $this->select(function (Where $select) use ($my_date,$my_date2,$idu,$my_dates,$my_date2s){
		// 	$select->where(array('fecha_ingreso >= ?' => $my_date, 'fecha_ingreso < ?' => $my_date2,
		// 			'fecha_salida >= ?' => $my_dates, 'fecha_salida < ?' => $my_date2s,
		// 			 'id_usuario = ?'=>$idu));
		// 	});
		// 	return $rowset->toArray();
		// }

		// if($this->fecha_salida=='' && $this->fecha_ingreso=='' && $id_usuario==0){
      	// 	$resultSet = $this->select();
	  	// 	return $resultSet->toArray();
		// }
	}
}

?>