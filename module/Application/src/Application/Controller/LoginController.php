<?php

namespace Application\Controller;
require_once("public/rutas.php"); 

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Validator;
use Zend\I18n\Validator as I18nValidator;
use Zend\Db\Adapter\Adapter;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;
use Application\Modelo\Entity\Multimedia;
// Componentes de autenticaci
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Application\Modelo\Entity\Usuarios;
use Application\Login\LoginForm;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Noticias;
use Application\Modelo\Entity\Eventos;

class LoginController extends AbstractActionController {
	private $dbAdapter;
	private $auth;
	public function __construct() {
		
		// Cargamos el servicio de autenticacien el constructor
		$this->auth = new AuthenticationService ();
	}
	public function indexAction() {
		$auth = $this->auth;
		$identi = $auth->getStorage ()->read ();
		if ($identi != false && $identi != null) {
			return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/login/index' );
		}
		
		$this->dbAdapter = $this->getServiceLocator ()->get ( 'db1' );
		$login = new LoginForm (null, $GLOBALS["ruta_servidor"].'images/captcha');
		
		if ($this->getRequest()->isPost ()) {
            $login->setData($this->getRequest()->getPost());
 
            if ($login->isValid() == false) {
            	$this->flashMessenger ()->addMessage ( "Captcha incorrecto. Por favor ingrese los datos nuevamente.");
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/login/index' );
            }
			
			

			/*
			 * Creamos la autenticacia la que le pasamos:
			 * 1. La conexia la base de datos
			 * 2. La tabla de la base de datos
			 * 3. El campo de la bd que hare username
			 * 4. El campo de la bd que hare contrase
			 */
			
			$authAdapter = new AuthAdapter ($this->dbAdapter, 'aps_usuarios', 'usuario', 'contrasena');
			
			
			$securePass = $this->request->getPost ( "contrasena" );
			$authAdapter->setIdentity ( $this->getRequest ()->getPost ( "usuario" ) )->setCredential ( $securePass );
			$auth->setAdapter ( $authAdapter );
			$result = $auth->authenticate ();
			
			if ($authAdapter->getResultRowObject () == false) {
				$u = new Usuarios ( $this->dbAdapter );
				$id = 0;
				$filter = new StringTrim ();
				$id_usuario = "";
				$intentosrestantes = 5;
				foreach ( $u->getUsuarios ( $id ) as $usu ) {
					if ($this->getRequest ()->getPost ( "usuario" ) == $filter->filter ( $usu ["usuario"] )) {
						if ($usu ["intentos"] == 4) {
							$u->updateEstado ( $usu ["id_usuario"] );
							$au = new Auditoria ( $this->dbAdapter );
							function get_real_ip() {
								if (isset ( $_SERVER ["HTTP_CLIENT_IP"] )) {
									return $_SERVER ["HTTP_CLIENT_IP"];
								} elseif (isset ( $_SERVER ["HTTP_X_FORWARDED_FOR"] )) {
									return $_SERVER ["HTTP_X_FORWARDED_FOR"];
								} elseif (isset ( $_SERVER ["HTTP_X_FORWARDED"] )) {
									return $_SERVER ["HTTP_X_FORWARDED"];
								} elseif (isset ( $_SERVER ["HTTP_FORWARDED_FOR"] )) {
									return $_SERVER ["HTTP_FORWARDED_FOR"];
								} elseif (isset ( $_SERVER ["HTTP_FORWARDED"] )) {
									return $_SERVER ["HTTP_FORWARDED"];
								} else {
									return $_SERVER ["REMOTE_ADDR"];
								}
							}
							
							$data = array (
									'id_usuario' => $identi->id_usuario,
									'ip_terminal' => get_real_ip (),
									'evento' => 'intento' 
							);
							$au->addAuditoriasalir ( $data );
							$this->flashMessenger ()->addMessage ( "Usuario bloqueado por maximo de intentos en contrasena y/o nombre de usuario" );
						} else {
							if($usu ["id_usuario"]!="1"){
								$cont = $usu ["intentos"] + 1;
								$intentosrestantes = 5 - $cont;
								$u->updateUintentos ( $usu ["id_usuario"], $cont );	
							}else{
								$intentosrestantes = 5;
							}
							$id_usuario=$usu ["id_usuario"];
						}
					}
				}
				if ($intentosrestantes != 0) {
					if($id_usuario!="1"){
						$this->flashMessenger ()->addMessage ( "Credenciales incorrectas, intentalo de nuevo. Tiene " . $intentosrestantes . " intentos" );
					}else{
						$this->flashMessenger ()->addMessage ( "Credenciales incorrectas, intentalo de nuevo.");
					}
				}
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/login/index' );
			} else {
				$auth->getStorage ()->write ( $authAdapter->getResultRowObject () );
				$identi = $auth->getStorage ()->read ();
				$au = new Auditoria ( $this->dbAdapter );
				$ad = new Auditoriadet ( $this->dbAdapter );
				
				$rolusuario = new Rolesusuario($this->dbAdapter);
				if(count($rolusuario->verificarRolusuario($identi->id_usuario)) == 0){
					$this->auth->clearIdentity ();
					$this->flashMessenger()->addMessage("El usuario aún no tiene rol asignado. Por favor comuniquese con el Administrador del sistema.");
					return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/login/index' );
				}

				if ($identi->id_estado == 'B') {
					$this->auth->clearIdentity ();
					$this->flashMessenger ()->addMessage ( "Usuario Bloqueado, comuniquese con el administrador." );
					return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/login/index' );
				}
				$u = new Usuarios ( $this->dbAdapter );
				
				$u->updateUintentos ( $identi->id_usuario, 0 );
				function get_real_ip() {
					if (isset ( $_SERVER ["HTTP_CLIENT_IP"] )) {
						return $_SERVER ["HTTP_CLIENT_IP"];
					} elseif (isset ( $_SERVER ["HTTP_X_FORWARDED_FOR"] )) {
						return $_SERVER ["HTTP_X_FORWARDED_FOR"];
					} elseif (isset ( $_SERVER ["HTTP_X_FORWARDED"] )) {
						return $_SERVER ["HTTP_X_FORWARDED"];
					} elseif (isset ( $_SERVER ["HTTP_FORWARDED_FOR"] )) {
						return $_SERVER ["HTTP_FORWARDED_FOR"];
					} elseif (isset ( $_SERVER ["HTTP_FORWARDED"] )) {
						return $_SERVER ["HTTP_FORWARDED"];
					} else {
						return $_SERVER ["REMOTE_ADDR"];
					}
				}			

				$data = array (
						'id_usuario' => $identi->id_usuario,
						'fecha_ingreso' => now,
						'ip_terminal' => get_real_ip (),

				);
				$au->addAuditoria ( $data, session_id () );
				$resultado = $au->getAuditoriaid ( session_id (), get_real_ip (), $identi->id_usuario );
				$evento = 'Ingreso al sistema';
				$ad->addAuditoriadet ( $evento, $resultado );

				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/index/index');
			}
		}
		$u = new Noticias($this->dbAdapter);
		$eve = new Eventos($this->dbAdapter);
		$multimedia = new Multimedia($this->dbAdapter);
		return new ViewModel( 
			array (
				'form' => $login,
				'titulo' => "bienvenido",
				'noticias'=>$u->getNoticiash(),
				'eventos'=> $eve->getEventosh(),
				'multimedia' => $multimedia->getMultimedia()
			)
		);
	}

	public function DentroAction() {
		$identi = $this->auth->getStorage ()->read ();
		if ($identi != false && $identi != null) {
			$datos = $identi;
		} else {
			$datos = "No estas identificado";
		}
		
		return new ViewModel(array('datos' => $datos));
	}

	public function cerrarAction() {
		$this->dbAdapter = $this->getServiceLocator ()->get ( 'db1' );
		$identi = $this->auth->getStorage ()->read ();
		$datos = $identi;
		$au = new Auditoria ( $this->dbAdapter );
		$ad = new Auditoriadet ( $this->dbAdapter );
		function get_real_ip() {
			if (isset ( $_SERVER ["HTTP_CLIENT_IP"] )) {
				return $_SERVER ["HTTP_CLIENT_IP"];
			} elseif (isset ( $_SERVER ["HTTP_X_FORWARDED_FOR"] )) {
				return $_SERVER ["HTTP_X_FORWARDED_FOR"];
			} elseif (isset ( $_SERVER ["HTTP_X_FORWARDED"] )) {
				return $_SERVER ["HTTP_X_FORWARDED"];
			} elseif (isset ( $_SERVER ["HTTP_FORWARDED_FOR"] )) {
				return $_SERVER ["HTTP_FORWARDED_FOR"];
			} elseif (isset ( $_SERVER ["HTTP_FORWARDED"] )) {
				return $_SERVER ["HTTP_FORWARDED"];
			} else {
				return $_SERVER ["REMOTE_ADDR"];
			}
		}
		
		$data = array (
				'id_usuario' => $identi->id_usuario,
				'ip_terminal' => get_real_ip () 
		);
		$evento = 'salir del sistema';
		$resultado = $au->getAuditoriaid ( session_id (), get_real_ip (), $identi->id_usuario );
		$ad->addAuditoriadet ( $evento, $resultado );
		$au->addAuditoriasalir ( $data );
		$this->auth->clearIdentity ();
		return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/login/index' );
	}
}
