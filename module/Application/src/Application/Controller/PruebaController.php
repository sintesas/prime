<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Application\Editarusuario\PruebaForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Prueba;
use Application\Modelo\Entity\Pruebamares;
use Application\Modelo\Entity\Tablafinp;
use Application\Modelo\Entity\Talentohumano;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Actas;
use Application\Modelo\Entity\Archivosconv;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Gruposproy;
use Application\Modelo\Entity\Permisos;
use Zend\Form\Form;
use Zend\Filter\StringTrim;

class PruebaController extends AbstractActionController {
	private $auth;
	public $dbAdapter;
	public function __construct() {
		// Cargamos el servicio de autenticacien el constructor
		$this->auth = new AuthenticationService ();
	}
	public function indexAction() {
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
        $auth = $this->auth;
        $permisos = new Permisos($this->dbAdapter);
        $identi = print_r($auth->getStorage()->read()->id_usuario,true);
        $resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,get_class());
        if($resultadoPermiso==0){
            $this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
        }

		$PruebaForm = new PruebaForm ();
		$this->dbAdapter = $this->getServiceLocator ()->get ( 'db4' );
		$pr = new Prueba ( $this->dbAdapter );
		$this->dbAdapter1 = $this->getServiceLocator ()->get ( 'db1' );
		$tb = new Tablafinp ( $this->dbAdapter1 );
		$id = $this->params ()->fromRoute ( 'id' );
		$id2 = $this->params ()->fromRoute ( 'id2' );
		$Tablafinproy = new Tablafinp ( $this->dbAdapter1 );
		$valflex = new Agregarvalflex ( $this->dbAdapter1 );
		$tablae = new Tablaequipop ( $this->dbAdapter1 );
		$Archivosconv = new Archivosconv ( $this->dbAdapter1 );
		$Propuesta_inv = new Actas ( $this->dbAdapter1 );
		$grupar = new Gruposproy ( $this->dbAdapter1 );
		$gruinv = new Grupoinvestigacion ( $this->dbAdapter1 );
		
		$filter = new StringTrim ();
		
		$view = new ViewModel ( array (
				'form' => $PruebaForm,
				
				'Archivosconv' => $Archivosconv->getArchivosconv ( $id ),
				'arch' => $Propuesta_inv->getArchivos ( $id ),
				//'grupar' => $grupar->getGruposparticipantes ( $id_conv ),
				//'Tablafinper' => $Tablafinproy->getArrayfinanciaper ( $id_conv ),
				'tablaeper' => $tablae->getArrayequiposper ( $id ),
				'gruinv' => $gruinv->getGrupoinvestigacion (),
				//'sumfuente' => $Tablafinproy->sumFuente ( $id_conv ),
				//'sumrubro' => $Tablafinproy->sumRubro ( $id_conv ),
				//'sumtotal' => $Tablafinproy->sumTotal ( $id_conv ),
				
				'titulo' => "Gestión presupuestal",
				'p' => $pr->getPrueba1 ( $id2 ),
				'vig' => $pr->getVigencias ( $id2 ),
				'tb' => $tb->getTablafinorder ( $id ) 
		) );
		return $view;
	}
	public function thAction() {
		$PruebaForm = new PruebaForm ();
		$this->dbAdapter = $this->getServiceLocator ()->get ( 'db2' );
		$pr = new Talentohumano ( $this->dbAdapter );
		
		$id = $this->params ()->fromRoute ( 'id' );
		$filter = new StringTrim ();
		
		$view = new ViewModel ( array (
				'form' => $PruebaForm,
				
				'p' => $pr->getUsuarioidTH ( '19097872' ) 
		) );
		return $view;
	}
	public function indexMARESAction() {
		$PruebaForm = new PruebaForm ();
		
		$this->dbAdapter = $this->getServiceLocator ()->get ( 'db3' );
		$pr = new Pruebamares ( $this->dbAdapter );
		
		$id = $this->params ()->fromRoute ( 'id' );
		$filter = new StringTrim ();
		
		$view = new ViewModel ( array (
				'form' => $PruebaForm,
				
				'p' => $pr->getPrueba () 
		) );
		return $view;
	}
}