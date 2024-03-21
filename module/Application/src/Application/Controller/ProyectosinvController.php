<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\ProyectosinvForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Proyectosinv;
use Application\Modelo\Entity\Proyectos;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Agregarvalflex;

class ProyectosinvController extends AbstractActionController {
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
			$not = new Proyectosinv ( $this->dbAdapter );
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
			$ProyectosinvForm = new ProyectosinvForm ();
			// adiciona la noticia
			$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
			
			$exist = $not->getExistProyect($id, $data["proyect"]);
			if($exist == null){
				$resultado = $not->addProyectosinv ( $data, $id );
				// redirige a la pantalla de inicio del 11ontrolador
				if ($resultado == 1) {
					$this->flashMessenger ()->addMessage ( "Proyectos investigacion creado con exito" );
					return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/editarconvocatoriai/index/' . $id );
				} else {
					$this->flashMessenger ()->addMessage ( "La creacion del Cronograma fallo" );
					return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/cronograma/index' );
				}
			}else{
				$this->flashMessenger ()->addMessage ( "El proyecto ya existe." );
					return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/proyectosinv/index/' . $id );
			}
		} else {
			
			$ProyectosinvForm = new ProyectosinvForm ();
			$this->dbAdapter = $this->getServiceLocator ()->get ( 'db1' );
			$u = new Proyectosinv ( $this->dbAdapter );
			$pt = new Agregarvalflex ( $this->dbAdapter );
			$auth = $this->auth;
			
			$identi = $auth->getStorage ()->read ();
			if ($identi == false && $identi == null) {
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/login/index' );
			}
			
			$filter = new StringTrim ();
					
			$pr = new Proyectos ( $this->dbAdapter );
			$a = '';
			$opciones = array ();
			foreach ( $pr->getProyectoh () as $dat ) {
				$nom = $filter->filter ( $dat ["codigo_proy"] ) . '-/-' . $filter->filter ( $dat ["nombre_proy"] );
				$op = array (
						$nom => $dat ["codigo_proy"]
				);
				$opciones = $opciones + $op;
			}
			$ProyectosinvForm->add ( array (
					'name' => 'proyect',
					'type' => 'Zend\Form\Element\Select',
					'attributes' => array (
							'id' => 'proyect',
							'required' => 'required'
					)
					,
					'required' => 'required',
					'size' => 25,
					'options' => array (
							'label' => 'Código del proyecto / Semillero de investigación / Grupo de estudio:',
							'empty_option' => 'Seleccione el proyecto',
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
				$pantalla = "proyectosinv";
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
						'form' => $ProyectosinvForm,
						'titulo' => "Proyectos de investigación Convocatoria de Monitores",
						'url' => $this->getRequest ()->getBaseUrl (),
						'id' => $id,
						'menu' => $dat ["id_rol"] 
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
		// print_r($data);
		$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
		$id2 = ( int ) $this->params ()->fromRoute ( 'id2', 0 );
		$u->eliminarProyectosinv ( $id );
		$this->flashMessenger ()->addMessage ( "Proyecto de Investigacion eliminado con exito" );
		return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/editarconvocatoriai/index/' . $id2 );
	}
	public function delAction() {
		$ProyectosinvForm = new ProyectosinvForm ();
		$this->dbAdapter = $this->getServiceLocator ()->get ( 'db1' );
		$u = new Proyectosinv ( $this->dbAdapter );
		// print_r($data);
		$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
		$id2 = ( int ) $this->params ()->fromRoute ( 'id2', 0 );
		
		$view = new ViewModel ( array (
				'form' => $ProyectosinvForm,
				'titulo' => "Eliminar registro",
				'url' => $this->getRequest ()->getBaseUrl (),
				'datos' => $id,
				'id2' => $id2 
		) );
		return $view;
	}
}