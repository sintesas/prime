<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\AplicarmForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Tablafin;
use Application\Modelo\Entity\Tablafinproy;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Proyectosinv;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Mares;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Formacionacahv;
use Application\Modelo\Entity\Idiomashv;
use Application\Modelo\Entity\Formacioncomhv;
use Application\Modelo\Entity\Experiencialabhv;
use Application\Modelo\Entity\Requisitosdoc;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class AplicarmController extends AbstractActionController {
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

        $permisos = new Permisos($this->dbAdapter);
        $identi = print_r($auth->getStorage()->read()->id_usuario,true);
        $resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,get_class());
        if($resultadoPermiso==0){
            $this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
        }

		function get_real_ip()
		{
			if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				return $_SERVER["HTTP_CLIENT_IP"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_X_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
				return $_SERVER["HTTP_X_FORWARDED"];
			} elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_FORWARDED"])) {
				return $_SERVER["HTTP_FORWARDED"];
			} else {
				return $_SERVER["REMOTE_ADDR"];
			}
		}

		$au = new Auditoria($this->dbAdapter);
		$ad = new Auditoriadet($this->dbAdapter);

		if ($this->getRequest ()->isPost ()) {
			// Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter = $this->getServiceLocator ()->get ( 'db1' );
			$us = new Usuarios ( $this->dbAdapter );
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
			$AplicarmForm = new AplicarmForm ();
			$usid = $us->getUsuarios($identi->id_usuario);
			/*
			$prinv = new Proyectosinv ( $this->dbAdapter );
			foreach ( $prinv->getPlazasinv ( $data->id_proyecto ) as $plazas ) {
				echo $plazas ["plazas_disponibles"];
			}
			
			$pl = $plazas ["plazas_disponibles"] + 1;
			
			$prinv->updateProyplaza ( $data->id_proyecto, $pl );
			*/
			
			$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
			
			//TODO: ELIMINAR EN PRODUCCION
			
			$this->dbAdapterMares = $this->getServiceLocator ()->get ( 'db3' );
			$mr = new Mares ( $this->dbAdapterMares );
			$resultadoMares = $mr->getInfoaplicar($usid[0]["documento"]);
			
			/*
			$resultadoMares = array();
			$resultadoMares["FACULTAD"] = "TEST";
			$resultadoMares["PROMEDIO"] = "TEST";
			$resultadoMares["PROGRAMA"] = "TEST";
			$resultadoMares["CRED_VALIDOS"] = "TEST";
			$resultadoMares["TOP_25"] = "TEST";
			$resultadoMares["CRED_PROGRAMA"] = "TEST";
			*/
			$resultado = $not->addAplicarm($data, $id, $resultadoMares, $identi->id_usuario);
			
			// redirige a la pantalla de inicio del controlador
			
			if ($resultado != 0) {
				$convo = new Convocatoria ( $this->dbAdapter );
				$convo->updateEstado ( $id, 'P' );

				$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
				$evento = 'Creación de aplicarm : ' . $resultado . ' (mgc_aplicarm)';
				$ad->addAuditoriadet($evento, $resul);
				
				$this->flashMessenger ()->addMessage ("Usted aplico con éxito a la convocatoria de monitores.");
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/editaraplicarm/index/'.$resultado);
			} else {
				$this->flashMessenger ()->addMessage ( "La creacion de la convocatoria fallo" );
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/aplicarm/index' );
			}
			
		} else {
			$AplicarmForm = new AplicarmForm ();
			$this->dbAdapter = $this->getServiceLocator ()->get ( 'db1' );
			$u = new Aplicarm ( $this->dbAdapter );
			$pt = new Agregarvalflex ( $this->dbAdapter );
			$us = new Usuarios ( $this->dbAdapter );
			$auth = $this->auth;
			$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
			$identi = $auth->getStorage ()->read ();
			if ($identi == false && $identi == null) {
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/login/index' );
			}
			
			$filter = new StringTrim ();
			foreach ( $us->getUsuarios ( $identi->id_usuario ) as $usid ) {
				$nombre = $usid ["primer_nombre"] . ' ' . $usid ["primer_apellido"];
				$opciones = array (
						$usid ["id_usuario"] => $nombre 
				);
			}
			
			//TODO: ELIMINAR EN PRODUCCION
			
			$this->dbAdapterMares = $this->getServiceLocator ()->get ( 'db3' );
			$mr = new Mares ( $this->dbAdapterMares );
			$resultado = $mr->getInfoaplicar($usid ["documento"]);
			
			/*
			$resultado = array();
			$resultado["FACULTAD"] = "TEST";
			$resultado["PROMEDIO"] = "TEST";
			$resultado["PROGRAMA"] = "TEST";
			$resultado["CRED_VALIDOS"] = "TEST";
			$resultado["TOP_25"] = "TEST";
			$resultado["CRED_PROGRAMA"] = "TEST";
			*/

			//print_r($mr->getInfoaplicar("80365824"));
			$AplicarmForm->add ( array (
				'name' => 'primer_apellido',
				'attributes' => array (
					'type' => 'text',
					'placeholder' => 'Primer apellido:',
					'value'=> $filter->filter($usid ["primer_apellido"]),
					'disabled' => 'disabled'
				),
				'options' => array (
					'label' => "Primer apellido:"
				) 
			) );

			$AplicarmForm->add ( array (
				'name' => 'segundo_apellido',
				'attributes' => array (
					'type' => 'text',
					'placeholder' => 'Segundo  apellido:',
					'value'=> $filter->filter($usid ["segundo_apellido"]),
					'disabled' => 'disabled'
				),
				'options' => array (
					'label' => "Segundo apellido:"
				) 
			) );

			$AplicarmForm->add ( array (
				'name' => 'nombres',
				'attributes' => array (
					'type' => 'text',
					'placeholder' => 'Nombres:',
					'value'=> $filter->filter(trim($usid ["primer_nombre"])." ".trim($usid["segundo_nombre"])),
					'disabled' => 'disabled'
				),
				'options' => array (
					'label' => "Nombres:"
				) 
			) );

			$AplicarmForm->add ( array (
				'name' => 'fecha_nacimiento',
				'attributes' => array (
					'type' => 'text',
					'value'=> $filter->filter($usid ["fecha_nacimiento"] ),
					'disabled' => 'disabled'
				),
				'options' => array (
					'label' => "Fecha de nacimiento:"
				) 
			) );
			
			$AplicarmForm->add ( array (
				'name' => 'cod_estudiante',
				'attributes' => array (
					'type' => 'text',
					'value'=> $filter->filter($usid ["cod_estudiante"]),
					'disabled' => 'disabled'
				)
				,
				'options' => array (
						'label' => "Código del estudiante:"
				) 
			) );

			$AplicarmForm->add ( array (
				'name' => 'identificacion',
				'attributes' => array (
					'type' => 'text',
					'value'=> $filter->filter($usid ["documento"]),
					'disabled' => 'disabled'
				)
				,
				'options' => array (
						'label' => "Número de identificación:"
				) 
			) );

			$AplicarmForm->add ( array (
					'name' => 'id_facultad',
					'attributes' => array (
						'type' => 'text',							
						'value' => $filter->filter($resultado ["FACULTAD"]),
						'disabled' => 'disabled'
					),
					'options' => array (
							'label' => 'Facultad:' 
					) 
			) );
			
			$AplicarmForm->add ( array (
					'name' => 'id_programa_curricular',
					'attributes' => array (
						'type' => 'text',
						'value' => $filter->filter ( $resultado ["PROGRAMA"] ) ,
						'disabled' => 'disabled'
					),
					'options' => array (
							'label' => 'Programa academico:' 
					) 
			) );
			
			$AplicarmForm->add ( array (
					'name' => 'direccion',
					'attributes' => array (
						'type' => 'text',
						'value'=> $filter->filter($usid ["direccion"]),
						'disabled' => 'disabled'
					),
					'options' => array (
							'label' => 'Dirección de residencia:' 
					) 
			) );
			
			$AplicarmForm->add ( array (
					'name' => 'telefono',
					'attributes' => array (
						'type' => 'text',
						'value' => $filter->filter ( $usid ["telefono"] ),
						'disabled' => 'disabled'
					),
					'options' => array (
							'label' => 'Número de teléfono fijo:' 
					) 
			) );

			$AplicarmForm->add ( array (
					'name' => 'celular',
					'attributes' => array (
						'type' => 'text',
						'value' => $filter->filter ( $usid ["celular"] ),
						'disabled' => 'disabled'
					),
					'options' => array (
							'label' => 'Número de teléfono celular:' 
					) 
			) );

			$AplicarmForm->add ( array (
				'name' => 'email',
				'attributes' => array (
					'type' => 'text',
					'value' => $filter->filter ( $usid ["email"] ),
					'disabled' => 'disabled'
				),
				'options' => array (
						'label' => 'Correo eléctronico institucional:' 
				) 
			) );

			$AplicarmForm->add ( array (
				'name' => 'promedio_ponderado',
				'attributes' => array (
						'type' => 'text',
						'value' => $filter->filter ( $resultado ["PROMEDIO"] ),
						'disabled' => 'disabled'
				),
				'options' => array (
						'label' => 'Promedio ponderado acumulado:' 
				) 
			) );

			$AplicarmForm->add ( array (
				'name' => 'creditos_aprobados',
				'attributes' => array (
						'type' => 'text',
						'value' => $filter->filter($resultado ["CRED_VALIDOS"]),
						'disabled' => 'disabled'
				),
				'options' => array (
						'label' => 'Total créditos aprobados:' 
				) 
			) );
			
			$AplicarmForm->add ( array (
				'name' => 'cumplimiento_conjunto',
				'attributes' => array (
						'type' => 'text',
						'value' => $filter->filter($resultado ["TOP_25"]),
						'disabled' => 'disabled'
				),
				'options' => array (
						'label' => 'Cumplimiento conjunto 25%:' 
				) 
			) );

			$AplicarmForm->add ( array (
				'name' => 'creditos_programa',
				'attributes' => array (
						'type' => 'text',
						'value' => $filter->filter($resultado ["CRED_PROGRAMA"]),
						'disabled' => 'disabled'
				),
				'options' => array (
						'label' => 'Total créditos del programa acádemico:' 
				) 
			) );

			$AplicarmForm->add ( array (
				'name' => 'justificacion',
				'attributes' => array (
	                'required'=>'required',
					'type' => 'textarea',
					'placeholder' => "Escrito en relación al tema del proyecto al que aplica",
					'maxlength' => "15000"
				),
			) );

			$formacahv = new Formacionacahv($this->dbAdapter);
			$idioma = new Idiomashv($this->dbAdapter);
			$formcom = new Formacioncomhv($this->dbAdapter);
			$explab = new Experiencialabhv($this->dbAdapter);
			$docreq = new Requisitosdoc ( $this->dbAdapter );

			//Selecciona los proyectos de investigación de las convocatorias. 
			$pi = new Proyectosinv($this->dbAdapter);
			$opciones = array ();
			foreach ( $pi->getProyectosinv ( $id ) as $uni ) {
				$op = array();
				$op = $op + array (
						$uni ["id_proyecto_inv"] => strstr($uni ["nombre_proyecto"], '-/-', true) 
				);
				$opciones = $opciones + $op;
			}
			
			$AplicarmForm->add ( array (
					'name' => 'id_proyecto',
					'type' => 'Zend\Form\Element\Select',
					'options' => array (
							'label' => 'Proyecto al que desea aplicar:',
							'value_options' => $opciones 
					) 
			) );
			date_default_timezone_set('America/Bogota');
			$AplicarmForm->add(array(
				'name' => 'fecha',
				'type' => 'date',
				'options' => array(
					'label' => 'Fecha:', 
				),
				'attributes' => array(
					'value' => date("Y-m-d"),
					'disabled' => 'disabled'
				)
			));
			
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
				$pantalla = "aplicarm";
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
						'form' => $AplicarmForm,
						'titulo' => "Aplicar a la convocatoria de monitores",
						'url' => $this->getRequest ()->getBaseUrl (),
						'datos' => $u->getAplicarm ( $id ),
						'terminos' => "Una vez aplique a la convocatoria acepta los terminos y condiciones estipulados.",
						'id' => $id,
						'menu' => $dat ["id_rol"] ,
						'formaca' => $formacahv->getFormacionacahv($identi->id_usuario),
						'idioma' => $idioma->getIdiomashv($identi->id_usuario),
						'formcom' => $formcom->getFormacioncomhv($identi->id_usuario),
						'explab' => $explab->getExperiencialabhv($identi->id_usuario),
						'docreq' => $docreq->getRequisitosdoc($id),
						'proyinvest' => $pi->getProyectosinv($id)
				) );
				return $view;
			} else {
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/mensajeadministrador/index' );
			}
		}
	}
}