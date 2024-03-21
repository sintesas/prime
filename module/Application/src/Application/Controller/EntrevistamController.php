<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\EntrevistamForm;
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
use Application\Modelo\Entity\Requisitosapdoc;
use Application\Modelo\Entity\Agregarhorarios;
use Application\Modelo\Entity\Requisitos;
use Application\Modelo\Entity\Requisitosap;
use Application\Modelo\Entity\Criterioevaluacion;
use Application\Modelo\Entity\Evaluarcriterio;

class EntrevistamController extends AbstractActionController {
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
			
			//obtiene la informacion de las pantallas
			$data = $this->request->getPost ();
			$EntrevistamForm = new EntrevistamForm ();
			
			$id = (int) $this->params ()->fromRoute ('id', 0);
			$resultado = $not->updateAplicarEntrevista($id, $data);
			$entrevista = $not->getAplicarm($id);
			// redirige a la pantalla de inicio del controlador
			if ($resultado == 1) {
				$this->flashMessenger ()->addMessage ("Entrevista creada con éxito.");
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/entrevistam/seleccionarm/'.$id.'/2/'.$entrevista[0]["id_convocatoria"].'/0');
			} else {
				$this->flashMessenger ()->addMessage ( "La creacion de la entrevista falló" );
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/entrevistam/seleccionarm/'.$id.'/2/'.$entrevista[0]["id_convocatoria"].'/0');
			}
			
		} else {
			$EntrevistamForm = new EntrevistamForm ();
			$this->dbAdapter = $this->getServiceLocator ()->get ( 'db1' );
			$u = new Aplicarm ( $this->dbAdapter );
			$pt = new Agregarvalflex ( $this->dbAdapter );
			$us = new Usuarios ( $this->dbAdapter );
			$auth = $this->auth;
			$id = ( int ) $this->params ()->fromRoute ('id', 0);
			$id2 = ( int ) $this->params ()->fromRoute ('id2', 0);
			$id3 = ( int ) $this->params ()->fromRoute ('id3', 0);
			$identi = $auth->getStorage ()->read ();
			if ($identi == false && $identi == null) {
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/login/index' );
			}

			$filter = new StringTrim ();

			if ($id != 0) {
				foreach ( $u->getAplicarm ($id) as $datAplicar ) {
					$id_conv = $datAplicar["id_aplicar"];
				}
			} else {
				foreach ( $u->getAplicarh () as $datAplicar ) {
					$id_conv = $datAplicar["id_aplicar"];
				}
			}

			foreach ( $us->getUsuarios ($datAplicar["id_usuario"]) as $usid ) {
				$nombre = $usid ["primer_nombre"] . ' ' . $usid ["primer_apellido"];
				$opciones = array (
						$usid ["id_usuario"] => $nombre 
				);
			}


			$EntrevistamForm->add ( array (
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

			$EntrevistamForm->add ( array (
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

			$EntrevistamForm->add ( array (
				'name' => 'nombres',
				'attributes' => array (
					'type' => 'text',
					'placeholder' => 'Nombres:',
					'value'=> $filter->filter(trim($usid["primer_nombre"])." ".trim($usid["segundo_nombre"])),
					'disabled' => 'disabled'
				),
				'options' => array (
					'label' => "Nombres:"
				) 
			) );

			$EntrevistamForm->add ( array (
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
			
			$EntrevistamForm->add ( array (
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

			$EntrevistamForm->add ( array (
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

			$EntrevistamForm->add ( array (
					'name' => 'id_facultad',
					'attributes' => array (
						'type' => 'text',							
						'value' => $filter->filter($datAplicar["id_facultad"]),
						'disabled' => 'disabled'
					),
					'options' => array (
							'label' => 'Facultad:' 
					) 
			) );
			
			$EntrevistamForm->add ( array (
					'name' => 'id_programa_curricular',
					'attributes' => array (
						'type' => 'text',
						'value' => $filter->filter ($datAplicar ["id_programa_curricular"] ) ,
						'disabled' => 'disabled'
					),
					'options' => array (
							'label' => 'Programa academico:' 
					) 
			) );
			
			$EntrevistamForm->add ( array (
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
			
			$EntrevistamForm->add ( array (
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

			$EntrevistamForm->add ( array (
					'name' => 'celular',
					'attributes' => array (
						'type' => 'text',
						'value' => $filter->filter ($usid ["celular"] ),
						'disabled' => 'disabled'
					),
					'options' => array (
							'label' => 'Número de teléfono celular:' 
					) 
			) );

			$EntrevistamForm->add ( array (
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

			$EntrevistamForm->add ( array (
				'name' => 'promedio_ponderado',
				'attributes' => array (
						'type' => 'text',
						'value' => $filter->filter ( $datAplicar ["promedio_ponderado"] ),
						'disabled' => 'disabled'
				),
				'options' => array (
						'label' => 'Promedio ponderado acumulado:' 
				) 
			) );

			$EntrevistamForm->add ( array (
				'name' => 'creditos_aprobados',
				'attributes' => array (
						'type' => 'text',
						'value' => $filter->filter($datAplicar ["creditos_aprobados"]),
						'disabled' => 'disabled'
				),
				'options' => array (
						'label' => 'Total créditos aprobados:' 
				) 
			) );
			
			$EntrevistamForm->add ( array (
				'name' => 'cumplimiento_conjunto',
				'attributes' => array (
						'type' => 'text',
						'value' => $filter->filter($datAplicar ["cumplimiento_conjunto"]),
						'disabled' => 'disabled'
				),
				'options' => array (
						'label' => 'Cumplimiento conjunto 25%:' 
				) 
			) );

			$EntrevistamForm->add ( array (
				'name' => 'creditos_programa',
				'attributes' => array (
						'type' => 'text',
						'value' => $filter->filter($datAplicar ["creditos_programa"]),
						'disabled' => 'disabled'
				),
				'options' => array (
						'label' => 'Total créditos del programa acádemico:' 
				) 
			) );

			$EntrevistamForm->add ( array (
				'name' => 'obervaciones_entrevista',
				'attributes' => array (
						'type' => 'textarea',
						'maxlength' => "15000",
						'value' =>  $filter->filter($datAplicar ["obervaciones_entrevista"])
				),
			) );

			$EntrevistamForm->add ( array (
				'name' => 'justificacion',
				'attributes' => array (
						'type' => 'textarea',
						'value' =>  $filter->filter($datAplicar ["justificacion"]),
						'disabled' => 'disabled'

				),
			) );
			
			if($datAplicar ["fecha_entrevista"]==null){
				$datAplicar ["fecha_entrevista"] = date("Y-m-d");
			}
			
			$EntrevistamForm->add(array(
				'name' => 'fecha_entrevista',
				'type' => 'date',
				'options' => array (
					'label' => 'Fecha de entrevista:', 
				),
				'attributes' => array (
					'value' =>  $filter->filter($datAplicar ["fecha_entrevista"]),
					'disabled' => 'disabled'
				)
			));

			$EntrevistamForm->add ( array (
				'name' => 'semestre',
				'type' => 'Zend\Form\Element\Select',
				'attributes' => array (
					'value' =>  $filter->filter($datAplicar ["semestre"]),
					'disabled' => 'disabled'
				),
				'options' => array (
					'label' => 'Semestre que cursa:',
					'value_options' => array(
		                '1' => 'Primero',
		                '2' => 'Segundo',
		                '3' => 'Tercero',
		                '4' => 'Cuarto',
		                '5' => 'Quinto',
		                '6' => 'Sexto',
		                '7' => 'Septimo',
		                '8' => 'Octavo',
		                '9' => 'Noveno',
		                '10' => 'Decimo'
		            )
				) 
			));
			$formacahv = new Formacionacahv($this->dbAdapter);
			$idioma = new Idiomashv($this->dbAdapter);
			$formcom = new Formacioncomhv($this->dbAdapter);
			$explab = new Experiencialabhv($this->dbAdapter);
			$horarios = new Agregarhorarios ( $this->dbAdapter );

			//Selecciona los proyectos de investigación de las convocatorias. 
			$pi = new Proyectosinv($this->dbAdapter);
			
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
				$pantalla = "Entrevistam";
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
			$excs1 = null;
			$ff = new Requisitos ( $this->dbAdapter );
			$cc = new Requisitosap ( $this->dbAdapter );
			foreach ( $cc->getRequisitosap ( $datAplicar["id_aplicar"] ) as $existec ) {
				$excs1 = $existec ["id_aplicar"];
			}
			
			if ($excs1 == null) {
				
				foreach ( $ff->getRequisitos ($datAplicar["id_convocatoria"]) as $tablac ) {
					
					$resul = $cc->addRequisitosdoc ( $tablac ["descripcion"], $tablac ["id_tipo_requisito"], $tablac ["observaciones"], $tablac ["id_requisito"], $datAplicar["id_aplicar"] );
				}
			}
			
			$excs = null;
			$docreq = new Requisitosdoc ( $this->dbAdapter );
			$docreqap = new Requisitosapdoc ( $this->dbAdapter );
			
			foreach ( $docreqap->getRequisitosapdoc ( $datAplicar["id_aplicar"] ) as $existec ) {
				$excs = $existec ["id_aplicar"];
			}
			if ($excs == null) {
				
				foreach ( $docreq->getRequisitosdoc ( $datAplicar["id_convocatoria"] ) as $tablac ) {
					
					$resul = $docreqap->addRequisitosdoc ( $tablac ["descripcion"], $tablac ["fecha_limite"], $tablac ["observaciones"], $tablac ["id_requisito_doc"], $datAplicar["id_aplicar"] );
				}
			}
			
			$usu = new Usuarios($this->dbAdapter);
			$prT = new Proyectos ($this->dbAdapter);
			$vl = new Agregarvalflex ($this->dbAdapter);
			$convo = new Convocatoria($this->dbAdapter);
			$criterios = new Criterioevaluacion($this->dbAdapter);
			$evaluarCr = new Evaluarcriterio($this->dbAdapter);
			$proyinvest = $pi->getProyectosinv($datAplicar["id_convocatoria"]);
			$prT =  $prT->monProyectos();

			$codProyecto="";
	        $nombProyecto="";
	        $NumPlazasProyecto="";

	        foreach ($proyinvest as $proInvesti) {
	        	if($datAplicar["id_proyecto"] == $proInvesti["id_proyecto_inv"]){
	        		$codProyecto = strstr($proInvesti ["nombre_proyecto"], '-/-', true);
	        		foreach ($prT as $proyeT){
	        			if($codProyecto == trim($proyeT["codigo_proy"])){
	        				$nombProyecto=$proyeT["nombre_proy"];
					        $NumPlazasProyecto=$proInvesti["cantidad_plazas"];
	        			}
	        		}
	        	}
	        }

	        $EntrevistamForm->add ( array (
				'name' => 'codProyecto',
				'attributes' => array (
						'type' => 'text',
						'value' => $filter->filter($codProyecto),
						'disabled' => 'disabled'
				),
				'options' => array (
						'label' => 'Código del proyecto de investigación, semillero, revista o proceso de gestión de la investigación:' 
				) 
			) );
	        
	        $EntrevistamForm->add ( array (
				'name' => 'nombProyecto',
				'attributes' => array (
						'type' => 'textarea',
						'value' => $filter->filter($nombProyecto),
						'disabled' => 'disabled'
				),
				'options' => array (
						'label' => 'Nombre del proyecto de investigación, semillero, revista o proceso de gestión de la investigación:' 
				) 
			) );
	        
	        $EntrevistamForm->add ( array (
				'name' => 'NumPlazasProyecto',
				'attributes' => array (
						'type' => 'text',
						'value' => $filter->filter($NumPlazasProyecto),
						'disabled' => 'disabled'
				),
				'options' => array (
						'label' => 'Número de plazas:' 
				) 
			) );
			
			if (true) {					
					$view = new ViewModel ( array (
						'form' => $EntrevistamForm,
						'titulo' => "ENTREVISTA PARA ASPIRANTES A MONITORIAS DE INVESTIGACIÓN",
						'url' => $this->getRequest ()->getBaseUrl (),
						'datos' => $u->getAplicarm($datAplicar["id_aplicar"]),
						'menu' => $dat ["id_rol"] ,
						'id' => $datAplicar["id_aplicar"],
						'vl' =>  $vl->getValoresf(),
						'usu' => $usu->getArrayusuarios(),
						'criterios' => $criterios->getCriterioevaluacionByConvocatoria($datAplicar["id_convocatoria"]),
						'evaluarCr' =>  $evaluarCr->getEvaluarcriterioAplicacion($datAplicar["id_aplicar"]) 
					) );
					return $view;				
			} else {
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/mensajeadministrador/index' );
			}
		}
	}

	public function seleccionarmActAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Aplicarm($this->dbAdapter);
		$prT = new Proyectosinv ($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$id3 = (int) $this->params()->fromRoute('id3',0);
		$id4 = (int) $this->params()->fromRoute('id4',0);
		$id5 = (int) $this->params()->fromRoute('id5',0);
		
		if($id2 == 1){
			$u->updateAplicarEstado($id, "Seleccionado");
			$prT->updateProyplaza($id4,($id3-1));
			$this->flashMessenger()->addMessage("Monitor seleccionado con éxito.");
			
		}else{
			$u->updateAplicarEstado($id, "No seleccionado");
			if($id5 == 1){
				$prT->updateProyplaza($id4,($id3+1));
			}
			$this->flashMessenger()->addMessage("Monitor no seleccionado.");
		}
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/consultaprocesom/index');			
	}

	public function seleccionarmAction(){
		$EntrevistamForm= new EntrevistamForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Criterioevaluacion($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$id3 = (int) $this->params()->fromRoute('id3',0);
		$id4 = (int) $this->params()->fromRoute('id4',0);
		$view = new ViewModel(
			array(
				'form'=>$EntrevistamForm,
				'titulo'=>"Eliminar registro",
				'url'=>$this->getRequest()->getBaseUrl(),
				'id'=>$id,
				'id2'=>$id2,
				'id3'=>$id3,
				'id4'=>$id4
			)
		);
		return $view;
	}
}