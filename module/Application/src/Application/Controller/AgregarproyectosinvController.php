<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\AgregarproyectosinvForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Proyectos;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Proyectosinv;
use Application\Modelo\Entity\Evaluarcriterio;

class AgregarproyectosinvController extends AbstractActionController {
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
		if ($this->getRequest ()->isPost ()) {
			// Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter = $this->getServiceLocator ()->get ( 'db1' );
			$not = new Aplicarm ( $this->dbAdapter );
			$auth = $this->auth;
			
			// verifica si esta conectado
			$identi = $auth->getStorage ()->read ();
			if ($identi == false && $identi == null) {
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/login/index' );
			}
			
			$rol = new Roles ( $this->dbAdapter );
			$rolusuario = new Rolesusuario ( $this->dbAdapter );
			$rolusuario->verificarRolusuario ( $identi->id_usuario );
			foreach ( $rolusuario->verificarRolusuario ( $identi->id_usuario ) as $dat ) {
				$dat ["id_rol"];
			}
			
			// obtiene la informacion de las pantallas
			$data = $this->request->getPost ();
			$AgregarproyectosinvForm = new AgregarproyectosinvForm ();
			// adiciona la noticia
			$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
			$resultado = $not->updateAplicarProy($data, $id);
			
			// redirige a la pantalla de inicio del controlador
			if ($resultado == 1) {
				$this->flashMessenger ()->addMessage ( "Proyectos investigacion creado con exito" );
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/editaraplicarm/index/' . $id);
			} else {
				$this->flashMessenger ()->addMessage ( "La creacion del Cronograma fallo" );
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/consultaconvocatorias/index' );
			}
		} else {
			$AgregarproyectosinvForm = new AgregarproyectosinvForm();
			$this->dbAdapter = $this->getServiceLocator ()->get ( 'db1' );
			$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
			$id2 = ( int ) $this->params ()->fromRoute ( 'id2', 0 );
			$u = new Proyectosinv ( $this->dbAdapter );
			$pt = new Agregarvalflex ( $this->dbAdapter );
			$auth = $this->auth;
			
			$identi = $auth->getStorage ()->read ();
			if ($identi == false && $identi == null) {
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/login/index' );
			}
			
			$filter = new StringTrim ();
			$opciones = array ();
			foreach ($u->getProyectosinv($id2) as $uni) {
				$op = array();
				$op = $op + array (
						$uni ["id_proyecto_inv"] => strstr($uni ["nombre_proyecto"], '-/-', true) 
				);
				$opciones = $opciones + $op;
			}
			
			$AgregarproyectosinvForm->add ( array (
					'name' => 'id_proyecto',
					'type' => 'Zend\Form\Element\Select',
					'options' => array (
							'label' => 'Proyecto al que desea aplicar:',
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
				$pantalla = "editaraplicarm";
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
				$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
				$view = new ViewModel ( array (
						'form' => $AgregarproyectosinvForm,
						'titulo' => "Agregar proyecto de investigación",
						'url' => $this->getRequest ()->getBaseUrl (),
						'id' => $id,
						'menu' => $dat ["id_rol"],
						'id2' => $id2
				) );
				return $view;
			} else {
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/mensajeadministrador/index' );
			}
		}
	}
	public function eliminarAction() {
		$this->dbAdapter = $this->getServiceLocator ()->get ( 'db1' );
		$u = new Proyectosinv ( $this->dbAdapter );
		$criterio = new Evaluarcriterio ( $this->dbAdapter );
		$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
		$not = new Aplicarm ( $this->dbAdapter );
		$not->delAplicarProy($id);
		$criterio->updateEvaluarcriterioT($id);
		$this->flashMessenger ()->addMessage ("Proyecto de Investigacion eliminado con éxito.");
		return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/editaraplicarm/index/' . $id);
	}
	public function delAction() {
		$AgregarproyectosinvForm = new AgregarproyectosinvForm ();
		$this->dbAdapter = $this->getServiceLocator ()->get ( 'db1' );
		$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
		$view = new ViewModel ( array (
				'form' => $AgregarproyectosinvForm,
				'titulo' => "Eliminar registro",
				'url' => $this->getRequest ()->getBaseUrl (),
				'id' => $id
		) );
		return $view;
	}
}