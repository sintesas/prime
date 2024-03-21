<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Proyectos\EditartablaepForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Talentohumano;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Proyectos;
use Zend\Filter\StringTrim;

class EditartablaepController extends AbstractActionController {
	private $auth;
	public $dbAdapter;
	public function __construct() {
		// Cargamos el servicio de autenticación en el constructor
		$this->auth = new AuthenticationService ();
	}
	public function indexAction() {
		$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
		$id2 = ( int ) $this->params ()->fromRoute ( 'id2', 0 );
		if ($this->getRequest ()->isPost ()) {
			$EditartablaepForm = new EditartablaepForm ();
			$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
			$this->dbAdapter = $this->getServiceLocator ()->get ( 'db1' );
			$u = new Tablaequipop ( $this->dbAdapter );
			$ud = new Usuarios ( $this->dbAdapter);
			$pr = new Proyectos ( $this->dbAdapter );
			$auth = $this->auth;
			$this->dbAdapters = $this->getServiceLocator ()->get ( 'db2' );
			$th = new Talentohumano ( $this->dbAdapters );
			
			$identi = $auth->getStorage ()->read ();
			if ($identi == false && $identi == null) {
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/login/index' );
			}
			$data = $this->request->getPost ();
			
			//$data ["periodo"];
			
			foreach ( $u->getTablaequipoid ( $id ) as $eqid ) {
				$eqid ["id_integrante"];
			}
			
			$resus = $ud->getArrayusuariosid ( $eqid ["id_integrante"] );
			
			foreach ( $pr->getProyecto ( $id2 ) as $proy ) {
				$proy ["codigo_proy"];
			}
			
			$resth = $th->getUsuarioproyTH2 ( $resus ["documento"], $proy ["codigo_proy"], $data ["periodo"], $data ["ano"] );
			
			//echo $resth ["HORAS_SEMANA_PLANTRAB"];
			if ($resth != null) {
				$u->updateTablaequipo ( $id, $data, $resth ["HORAS_SEMANA_PLANTRAB"] );
			} else {
				$u->updateTablaequipo ( $id, $data, 0 );
			}
			
			$urlId = "/application/editarproyecto/index/" . $id2;
			return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . $urlId );
			$this->flashMessenger ()->addMessage ( "Integrante editado con Exito" );
		} else {
			$EditartablaepForm = new EditartablaepForm ();
			$this->dbAdapter = $this->getServiceLocator ()->get ( 'db1' );
			$u = new Agregarvalflex ( $this->dbAdapter );
			$auth = $this->auth;
			
			$identi = $auth->getStorage ()->read ();
			if ($identi == false && $identi == null) {
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/login/index' );
			}
			
			$filter = new StringTrim ();
			
			$us = new Tablaequipop ( $this->dbAdapter );
			$id2 = ( int ) $this->params ()->fromRoute ( 'id2', 0 );
			$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
			foreach ( $us->getTablaequipoid ( $id ) as $dat ) {
				$dat ["id_integrantes"];
				$idrol = $dat ["id_rol"];
				$idtipd = $dat ["id_tipo_dedicacion"];
			}
			
			$EditartablaepForm->add ( array (
					'name' => 'horas_apro',
					'attributes' => array (
							'type' => 'text',
							'value' => $filter->filter ( $dat ["horas_apro"] ),
							'placeholder' => 'Ingrese el número de horas aprobadas' 
					),
					'options' => array (
							'label' => 'Horas Aprobadas :' 
					) 
			) );
			
			$EditartablaepForm->add ( array (
					'name' => 'horas_apro',
					'attributes' => array (
							'type' => 'text',
							'value' => $filter->filter ( $dat ["horas_apro"] ) 
					),
					'options' => array (
							'label' => 'Horas Aprobadas :' 
					) 
			) );
			
			$EditartablaepForm->add ( array (
					'name' => 'ano',
					'attributes' => array (
							'type' => 'number',
							'placeholder' => 'Ingrese el AÃ±o  ' ,
							'min' => 2000,
							'max' => 2099,
					)
					,
					'options' => array (
							'label' => 'AÃ±o :' 
					) 
			) );
			
			// Periodo
			
			$EditartablaepForm->add ( array (
					'name' => 'periodo',
					'type' => 'Zend\Form\Element\Select',
					'options' => array (
							'label' => 'Periodo : ',
                    'value_options' => array(
                        '' => '',
                        '1' => 'Primero',
                        '2' => 'Segundo'
                    )
					) 
			) );
			
			$EditartablaepForm->add ( array (
					'name' => 'horas_sol',
					'attributes' => array (
							'type' => 'text',
							'value' => $filter->filter ( $dat ["horas_sol"] ),
							'placeholder' => 'Ingrese el total de horas solicitadas    ' 
					),
					'options' => array (
							'label' => 'Horas Solicitadas :' 
					) 
			) );
			
			// define el campo genero
			if ($idrol == null) {
				$v_rol = 1;
			} else {
				$v_rol = $idrol;
			}
			// define el campo rol en el proyecto
			$vf = new Agregarvalflex ( $this->dbAdapter );
			$resvf = $vf->getValoresflexid ( $v_rol );
			$opciones = array ();
			foreach ( $vf->getArrayvalores ( 39 ) as $da ) {
				$op = array (
						$resvf ["id_valor_flexible"] => $resvf ["descripcion_valor"] 
				);
				$op = $op + array (
						$da ["id_valor_flexible"] => $da ["descripcion_valor"] 
				);
				$opciones = $opciones + $op;
			}
			$EditartablaepForm->add ( array (
					'name' => 'id_rol',
					'type' => 'Zend\Form\Element\Select',
					'options' => array (
							'label' => 'Rol en el Proyecto : ',
							
							'value_options' => $opciones 
					) 
			) );
			
			if ($idtipd == null) {
				$v_tip = 1;
			} else {
				$v_tip = $idtipd;
			}
			// define el campo tipo dedicación
			$vf = new Agregarvalflex ( $this->dbAdapter );
			$resvf = $vf->getValoresflexid ( $v_tip );
			$opciones = array ();
			foreach ( $vf->getArrayvalores ( 50 ) as $da ) {
				$op = array (
						$resvf ["id_valor_flexible"] => $resvf ["descripcion_valor"] 
				);
				$op = $op + array (
						$da ["id_valor_flexible"] => $da ["descripcion_valor"] 
				);
				$opciones = $opciones + $op;
			}
			$EditartablaepForm->add ( array (
					'name' => 'id_tipo_dedicacion',
					'type' => 'Zend\Form\Element\Select',
					'options' => array (
							'label' => 'Tipo de DedicaciÃ³n : ',
							
							'value_options' => $opciones 
					) 
			) );
			
			// verificar roles
			$per = array (
					'id_rol' => '' 
			);
			$dat = array (
					'id_rol' => '' 
			);
			$rolusuario = new Rolesusuario ( $this->dbAdapter );
			$permiso = new Permisos ( $this->dbAdapter );
			
			// me verifica el tipo de rol asignado al usuario
			$rolusuario->verificarRolusuario ( $identi->id_usuario );
			foreach ( $rolusuario->verificarRolusuario ( $identi->id_usuario ) as $dat ) {
				$dat ["id_rol"];
			}
			
			if ($dat ["id_rol"] != '') {
				// me verifica el permiso sobre la pantalla
				$pantalla = "tablaequipo";
				$panta = 0;
				$pt = new Agregarvalflex ( $this->dbAdapter );
				
				foreach ( $pt->getValoresflexdesc ( $pantalla ) as $panta ) {
					$panta ["id_valor_flexible"];
				}
				
				$permiso->verificarPermiso ( $dat ["id_rol"], $panta ["id_valor_flexible"] );
				foreach ( $permiso->verificarPermiso ( $dat ["id_rol"], $panta ["id_valor_flexible"] ) as $per ) {
					$per ["id_rol"];
				}
			}
			
			if (true) {
				$view = new ViewModel ( array (
						'form' => $EditartablaepForm,
						'titulo' => "Editar Integrante del Proyecto",
						'id' => $id,
						'id2' => $id2,
						'url' => $this->getRequest ()->getBaseUrl (),
						'menu' => $dat ["id_rol"] 
				) );
				return $view;
			} else {
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/mensajeadministrador/index' );
			}
		}
	}
}
