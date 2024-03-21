<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\EditaraplicarmForm;
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
use Application\Pdf\PdfgiForm;
use DOMPDFModule\View\Model\PdfModel;
use Application\Modelo\Entity\Requisitos;
use Application\Modelo\Entity\Requisitosap;

class EditaraplicarmController extends AbstractActionController {
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
        $formacahv = new Formacionacahv($this->dbAdapter);
		$idioma = new Idiomashv($this->dbAdapter);
		$formcom = new Formacioncomhv($this->dbAdapter);
		$explab = new Experiencialabhv($this->dbAdapter);
		$horarios = new Agregarhorarios ( $this->dbAdapter );
		$u = new Aplicarm ( $this->dbAdapter );

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
			$EditaraplicarmForm = new EditaraplicarmForm ();
			
			$id = (int) $this->params ()->fromRoute ('id', 0);
			$id2 = (int) $this->params ()->fromRoute ('id2', 0);
			$id3 = (int) $this->params ()->fromRoute ('id3', 0);
			$id4 = (int) $this->params ()->fromRoute ('id4', 0);

			foreach ($u->getAplicarm($id) as $datAplicar) {
				$id_conv = $datAplicar["id_aplicar"];
			}

			$horariosAplicacion = $horarios->getHorarioByAplicacion($id);
			$formaca = $formacahv->getFormacionacahv($datAplicar["id_usuario"]);
			$idioma = $idioma->getIdiomashv($datAplicar["id_usuario"]);
			$formcom = $formcom->getFormacioncomhv($datAplicar["id_usuario"]);
			$explab = $explab->getExperiencialabhv($datAplicar["id_usuario"]);

			if(count($horariosAplicacion) == 0){
				$this->flashMessenger ()->addMessage ("Su aplicación no ha sido actualizada. Debe establecer un horario para la monitoria.");
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/editaraplicarm/index/'.$id."/".$id2."/".$id3."/".$id4);
			}else if(count($formaca) == 0 ){
				$this->flashMessenger ()->addMessage ("Su aplicación no ha sido actualizada. Debe incluir información en la sección 'Formación Académica'");
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/editaraplicarm/index/'.$id."/".$id2."/".$id3."/".$id4);
			}else if(count($formcom) == 0){
				$this->flashMessenger ()->addMessage ("Su aplicación no ha sido actualizada. Debe incluir información en la sección 'Formación complementaria'");
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/editaraplicarm/index/'.$id."/".$id2."/".$id3."/".$id4);
			}else if(count($explab) == 0){
				$this->flashMessenger ()->addMessage ("Su aplicación no ha sido actualizada. Debe incluir información en la sección 'Experiencia laboral'");
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/editaraplicarm/index/'.$id."/".$id2."/".$id3."/".$id4);
			}else{
				$resultado = $not->updateAplicar($id, $data);			
				// redirige a la pantalla de inicio del controlador
				if ($resultado == 1) {
					$this->flashMessenger ()->addMessage ("Usted aplico con éxito a la convocatoria de monitores.");
					if($id3 != 0){
						return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/editaraplicarm/index/'.$id."/".$id2."/".$id3."/".$id4);
					}else{
						return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/editaraplicarm/index/'.$id."/".$id2."/".$id3."/".$id4);
					}
				} else {
					$this->flashMessenger ()->addMessage ( "La creacion de la convocatoria falló" );
					return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/editaraplicarm/index' );
				}
			}
		} else {
			$EditaraplicarmForm = new EditaraplicarmForm ();
			$this->dbAdapter = $this->getServiceLocator ()->get ( 'db1' );
			$pt = new Agregarvalflex ( $this->dbAdapter );
			$us = new Usuarios ( $this->dbAdapter );
			$auth = $this->auth;
			$id = ( int ) $this->params ()->fromRoute ('id', 0);
			$id2 = ( int ) $this->params ()->fromRoute ('id2', 0);
			$id3 = ( int ) $this->params ()->fromRoute ('id3', 0);
			$id4 = ( int ) $this->params ()->fromRoute ('id4', 0);
			$identi = $auth->getStorage ()->read ();
			if ($identi == false && $identi == null) {
				return $this->redirect ()->toUrl ( $this->getRequest ()->getBaseUrl () . '/application/login/index' );
			}

			$filter = new StringTrim ();

			foreach ( $u->getAplicarm ($id) as $datAplicar ) {
				$id_conv = $datAplicar["id_aplicar"];
			}

			foreach ( $us->getUsuarios ($datAplicar["id_usuario"]) as $usid ) {
				$nombre = $usid ["primer_nombre"] . ' ' . $usid ["primer_apellido"];
				$opciones = array (
						$usid ["id_usuario"] => $nombre 
				);
			}

			$EditaraplicarmForm->add ( array (
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

			$EditaraplicarmForm->add ( array (
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

			$EditaraplicarmForm->add ( array (
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

			$EditaraplicarmForm->add ( array (
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
			
			$EditaraplicarmForm->add ( array (
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

			$EditaraplicarmForm->add ( array (
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

			$EditaraplicarmForm->add ( array (
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
			
			$EditaraplicarmForm->add ( array (
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
			
			$EditaraplicarmForm->add ( array (
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
			
			$EditaraplicarmForm->add ( array (
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

			$EditaraplicarmForm->add ( array (
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

			$EditaraplicarmForm->add ( array (
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

			$EditaraplicarmForm->add ( array (
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

			$EditaraplicarmForm->add ( array (
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
			
			$EditaraplicarmForm->add ( array (
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

			$EditaraplicarmForm->add ( array (
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

			$EditaraplicarmForm->add ( array (
				'name' => 'justificacion',
				'attributes' => array (
						'type' => 'textarea',
						'placeholder' => "Escrito en relación al tema del proyecto al que aplica",
						'maxlength' => "15000",
						'required'=>'required',
						'value' =>  $filter->filter($datAplicar ["justificacion"])
				),
			) );

			$EditaraplicarmForm->add(array(
				'name' => 'fecha',
				'type' => 'date',
				'options' => array (
					'label' => 'Fecha:', 
				),
				'attributes' => array (
					'value' =>  $filter->filter($datAplicar["fecha"]),
					//'value' => date("Y-m-d"),
					'disabled' => 'disabled'
				)
			));

			$EditaraplicarmForm->add ( array (
				'name' => 'semestre',
				'type' => 'Zend\Form\Element\Select',
				'attributes' => array (
					'value' =>  $filter->filter($datAplicar ["semestre"])
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
								
			if($id2!=0){
				$pdf = new PdfModel();        
                $pdf->setOption('filename', 'certificado_aplicacion.pdf');
            	$pdf->setOption('paperSize', 'a4');
            	$pdf->setOption('paperOrientation', 'landscape');
            	$valpdf="Si";

            	$pdf->setVariables(array(
            		'datos' => $u->getAplicarm($datAplicar["id_aplicar"]),
					'menu' => $dat ["id_rol"] ,
					'formaca' => $formacahv->getFormacionacahv($datAplicar["id_usuario"]),
					'idioma' => $idioma->getIdiomashv($datAplicar["id_usuario"]),
					'formcom' => $formcom->getFormacioncomhv($datAplicar["id_usuario"]),
					'explab' => $explab->getExperiencialabhv($datAplicar["id_usuario"]),
					'docreq' => $docreq->getRequisitosdoc($datAplicar["id_convocatoria"]),
					'docreqap' => $docreqap->getRequisitosapdoc($datAplicar["id_aplicar"]),
					'proyinvest' => $pi->getProyectosinv($datAplicar["id_convocatoria"]),
					'id' => $datAplicar["id_aplicar"],
					'prT' =>  $prT->getProyectoh(),
					'horarios' => $horarios->getHorarioByAplicacion($datAplicar["id_aplicar"]),
					'vl' =>  $vl->getValoresf(),
					'usu' => $usu->getArrayusuarios(),
	                'valpdf' => "Si",
	                "convo" => $convo->getConvocatoriaid($datAplicar["id_convocatoria"]),
	                'id3' => $id3,
					'id2' => $id2,
					'usuario' => $datAplicar["id_usuario"]
	            ));
	            return $pdf;
			}else{
				$view = new ViewModel ( array (
					'form' => $EditaraplicarmForm,
					'titulo' => "Aplicar a la convocatoria de monitores",
					'url' => $this->getRequest ()->getBaseUrl (),
					'datos' => $u->getAplicarm($datAplicar["id_aplicar"]),
					'terminos' => "Una vez aplique a la convocatoria acepta los terminos y condiciones estipulados.",
					'menu' => $dat ["id_rol"] ,
					'formaca' => $formacahv->getFormacionacahv($datAplicar["id_usuario"]),
					'idioma' => $idioma->getIdiomashv($datAplicar["id_usuario"]),
					'formcom' => $formcom->getFormacioncomhv($datAplicar["id_usuario"]),
					'explab' => $explab->getExperiencialabhv($datAplicar["id_usuario"]),
					'docreq' => $docreq->getRequisitosdoc($datAplicar["id_convocatoria"]),
					'docreqap' => $docreqap->getRequisitosapdoc($datAplicar["id_aplicar"]),
					'proyinvest' => $pi->getProyectosinv($datAplicar["id_convocatoria"]),
					'id' => $datAplicar["id_aplicar"],
					'prT' =>  $prT->getProyectoh(),
					'horarios' => $horarios->getHorarioByAplicacion($datAplicar["id_aplicar"]),
					'vl' =>  $vl->getValoresf(),
					'usu' => $usu->getArrayusuarios(),
					'valpdf' => "No",
					'id3' => $id3,
					'id2' => $id2,
					'id4' => $id4,
					'usuario' => $datAplicar["id_usuario"]
				) );
				return $view;
			}
		}
	}
}