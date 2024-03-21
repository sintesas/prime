<?php

namespace Application\Modelo\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Db\Sql\Select as Select;
use Zend\Db\Sql\Select as Where;
use Zend\Db\Sql\Select as Order;

class Prueba extends TableGateway {
	
	// variables de la tabla
	private $tipo;
	private $descripcion;
	private $fecha;
	private $tema;
	private $instituciones;
	private $id_pais;
	
	// funcion constructor
	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		return parent::__construct ( 'PR_PROYECTOS_INVESTIGACION', $adapter, $databaseSchema, $selectResultPrototype );
	}
	public function getPrueba1($id) {
		$id = '%' . $id . '%';
		$rowset = $this->select ( function (Where $select) use ($id) {
			$select->where ( array (
					'EJECUTOR LIKE ?' => $id 
			) );
		} );
		return $rowset->toArray ();
	}
	public function getVigencias($id) {
		$filter = new StringTrim ();
		$nom = '%' . $id . '%';
		
		$resultSet = $this->select ( function ($select) use ($nom) {
			$select->quantifier ( \Zend\Db\Sql\Select::QUANTIFIER_DISTINCT );
			$select->columns ( array (
					'VIGENCIA' 
			) );
			$select->where ( array (
					'EJECUTOR LIKE ?' => $nom 
			) );
			$select->order ( array (
					'VIGENCIA ASC' 
			) );
		} );
		return $resultSet->toArray ();
	}
	public function getPrueba($id, $id2 = array()) {
		if ($id2 != null) {
			foreach ( $id2 as $cod ) {
				$nom = '%' . $cod ["codigo_proy"] . '%';
				$rowset = $this->select ( function (Where $select) use ($nom) {
					$select->where ( array (
							'EJECUTOR LIKE ?' => $nom 
					) );
					$select->order ( array (
							'VIGENCIA ASC' 
					) );
				} );
			}
			return $rowset->toArray ();
		} else {
			
			$nom = '%' . $id . '%';
			$rowset = $this->select ( function (Where $select) use ($nom) {
				$select->where ( array (
						'EJECUTOR LIKE ?' => $nom 
				) );
				$select->order ( array (
						'VIGENCIA ASC' 
				) );
			} );
			return $rowset->toArray ();
		}
	}
}
?>