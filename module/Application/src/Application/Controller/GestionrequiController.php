<?php

namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\GestionrequiForm;
use Application\Proyectos\AsignarForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Aplicar;
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Asignareval;
use Application\Modelo\Entity\Evaluar;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Monitor;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Proyectosinv;
use Application\Modelo\Entity\Usuarios;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Requisitosapdoc;
use Application\Modelo\Entity\Requisitosap;
use Application\Modelo\Entity\Lineas;
use Application\Modelo\Entity\Libros;
use Application\Modelo\Entity\Proyectosext;
use Application\Modelo\Entity\Tablaequipo;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Tablafinproy;
use Application\Modelo\Entity\Tablafinp;
use Application\Modelo\Entity\Talentohumano;
use Application\Modelo\Entity\Gruposparticipantes;
use Application\Modelo\Entity\Gruposproy;


class GestionrequiController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     	//Cargamos el servicio de autenticacien el constructor
    	 $this->auth = new AuthenticationService();
	}
	
    public function indexAction()
    {
    	$this->dbAdapter=$this->getServiceLocator()->get('db1');
        $auth = $this->auth;
        $permisos = new Permisos($this->dbAdapter);
        $identi = print_r($auth->getStorage()->read()->id_usuario,true);
        $resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,get_class());
        if($resultadoPermiso==0){
            $this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
        }
        
		if($this->getRequest()->isPost()){
			
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Aplicar($this->dbAdapter);
		    $this->auth = new AuthenticationService();
			$auth = $this->auth;
			$result = $auth->hasIdentity();

			//verifica si esta conectado
			$identi=$auth->getStorage()->read();
			$rol = new Roles($this->dbAdapter);
			$rolusuario = new Rolesusuario($this->dbAdapter);
		 	$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$dat["id_rol"];
			}

			//obtiene la informacion de las pantallas
			$data = $this->request->getPost();

			$GestionrequiForm = new GestionrequiForm();

			$us = new Aplicarm($this->dbAdapter);
			//define el campo ciudad
	
			//adiciona la noticia
			$evaluador = new Asignareval($this->dbAdapter);
			$id = (int) $this->params()->fromRoute('id',0);
			$docreq = new Requisitosapdoc($this->dbAdapter);
			$req = new Requisitosap($this->dbAdapter);
			$eval = new Evaluar($this->dbAdapter);
			$usuario = new Usuarios($this->dbAdapter);
			$proyext = new Proyectosext($this->dbAdapter);
			$eval= new Evaluar($this->dbAdapter);
			$pi = new Proyectosinv ($this->dbAdapter);
			$cm = new Monitor ($this->dbAdapter);
			$u = new Aplicar($this->dbAdapter);
			$proyectosN = new Proyectos ( $this->dbAdapter );
			$c = new Convocatoria($this->dbAdapter);
			$tipo = $c->getTipo($id);
			if(trim($tipo["tipo_conv"]) == "m"){
		        $GestionrequiForm->add(array(
		            'name' => 'id_aplicar',
		            'attributes' => array(
		                'type'  => 'number',
						'placeholder'=>'Ingrese el Id de la inscripción',
		            ),
		            'options' => array(
		                'label' => 'Id de la inscripción:',
		            ),
		        ));
		        $GestionrequiForm->add(array(
		            'name' => 'nombre_proy',
		            'attributes' => array(
		                'type'  => 'text',
						'placeholder'=>'Ingrese el nombre del proyecto',
		            ),
		            'options' => array(
		                'label' => 'Nombre del proyecto:',
		            ),
		        ));
			}else{
		        $GestionrequiForm->add(array(
		            'name' => 'id_aplicar',
		            'attributes' => array(
		                'type'  => 'number',
						'placeholder'=>'Ingrese el ID de la propuesta',
		            ),
		            'options' => array(
		                'label' => 'ID de la propuesta :',
		            ),
		        ));
		        $GestionrequiForm->add(array(
		            'name' => 'nombre_proy',
		            'attributes' => array(
		                'type'  => 'text',
						'placeholder'=>'Ingrese el nombre de la propuesta',
		            ),
		            'options' => array(
		                'label' => 'Nombre de la propuesta :',
		            ),
		        ));
			}
			$view = new ViewModel(array('form'=>$GestionrequiForm,
				'titulo'=>"Gestión de requisitos y evaluaciones", 
				'datos2'=>$us->getAplicarmconv($id),
				'datos3'=>$u->getAplicarestado($id),
				'datos'=>$not->filtroAplicarpr($data,$id),
				'evaluados'=>$eval->getEvaluar(),
				'evaluador'=>$evaluador->getAsignarevalt(),
				'usuario'=>$usuario->getArrayusuarios(),
				'docreq'=>$docreq->getRequisitos(),
				'req'=>$req->getRequisitos(),
				'evaluar'=>$eval->getEvaluar(),
				'id'=>$id,
				'pi'=>$pi->getProyectosinvs(),
				'contm'=>$cm->getCont(),
				'id'=>$id,
				'url'=>$this->getRequest()->getBaseUrl(),
				'consulta'=>1,
				'proyectosN' => $proyectosN->monProyectos(),
				'menu'=>$dat["id_rol"],
				"tipoConvo" => trim($tipo["tipo_conv"]),
				"dataBus" => $data
			));
			return $view;
		}
		else
		{
			$GestionrequiForm = new GestionrequiForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Aplicar($this->dbAdapter);
			$us = new Aplicarm($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$this->auth = new AuthenticationService();
			$auth = $this->auth;
			$result = $auth->hasIdentity();
			$identi=$auth->getStorage()->read();
			$filter = new StringTrim();

			//verificar roles
			$per=array('id_rol'=>'');
			$dat=array('id_rol'=>'');
			$permiso_1='';
			$roles_1='';
			$rolusuario = new Rolesusuario($this->dbAdapter);
			$permiso = new Permisos($this->dbAdapter);
		
			//me verifica el tipo de rol asignado al usuario
			$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$roles_1=$dat["id_rol"];
			}
		
			if ($dat["id_rol"]!= ''){
			//me verifica el permiso sobre la pantalla
				$pantalla="grupoinv";
				$panta=0;
				$pt = new Agregarvalflex($this->dbAdapter);

				foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
					$panta["id_valor_flexible"];
				}

				$permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]);
				foreach ($permiso->verificarPermiso($roles_1,$panta["id_valor_flexible"]) as $per) {
					$permiso_1=$per["id_rol"];
				}
			}
			$id = (int) $this->params()->fromRoute('id',0);
			$docreq = new Requisitosapdoc($this->dbAdapter);
			$req = new Requisitosap($this->dbAdapter);
			$eval = new Evaluar($this->dbAdapter);
			$use = new Usuarios($this->dbAdapter);
			$pi = new Proyectosinv ($this->dbAdapter);
			$cm = new Monitor ($this->dbAdapter);
			$evaluador = new Asignareval($this->dbAdapter);
			$proyectosN = new Proyectos ( $this->dbAdapter );
			$c = new Convocatoria($this->dbAdapter);
			$tipo = $c->getTipo($id);

			if(trim($tipo["tipo_conv"]) == "m"){
		        $GestionrequiForm->add(array(
		            'name' => 'id_aplicar',
		            'attributes' => array(
		                'type'  => 'number',
						'placeholder'=>'Ingrese el Id de la inscripción',
		            ),
		            'options' => array(
		                'label' => 'Id de la inscripción:',
		            ),
		        ));
		        $GestionrequiForm->add(array(
		            'name' => 'nombre_proy',
		            'attributes' => array(
		                'type'  => 'text',
						'placeholder'=>'Ingrese el nombre del proyecto',
		            ),
		            'options' => array(
		                'label' => 'Nombre del proyecto:',
		            ),
		        ));
			}else{
		        $GestionrequiForm->add(array(
		            'name' => 'id_aplicar',
		            'attributes' => array(
		                'type'  => 'number',
						'placeholder'=>'Ingrese el ID de la propuesta',
		            ),
		            'options' => array(
		                'label' => 'ID de la propuesta :',
		            ),
		        ));
		        $GestionrequiForm->add(array(
		            'name' => 'nombre_proy',
		            'attributes' => array(
		                'type'  => 'text',
						'placeholder'=>'Ingrese el nombre de la propuesta',
		            ),
		            'options' => array(
		                'label' => 'Nombre de la propuesta :',
		            ),
		        ));
			}
			if(true){
				$view = new ViewModel(array('form'=>$GestionrequiForm,
					'titulo'=>"Gestión de requisitos y evaluaciones",
					'titulo2'=>"Tipos Valores Existentes", 
					'url'=>$this->getRequest()->getBaseUrl(),
					'datos'=>$u->getAplicarconv($id),
					'datos3'=>$u->getAplicarestado($id),
					'datos2'=>$us->getAplicarmconv($id),
					'pi'=>$pi->getProyectosinvs(),
					'docreq'=>$docreq->getRequisitos(),
					'req'=>$req->getRequisitos(),
					'evaluar'=>$eval->getEvaluar(),
					'usuario'=>$use->getArrayusuarios(),
					'contm'=>$cm->getCont(),
					'id'=>$id,
					'evaluador'=>$evaluador->getAsignarevalt(),
					'consulta'=>0,
					'proyectosN' => $proyectosN->monProyectos(),
					'menu'=>$dat["id_rol"],
					"tipoConvo" => trim($tipo["tipo_conv"])
				));
				return $view;
			}
			else{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
			}
		}
    }

	public function aprobarproyAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$id3 = $this->params()->fromRoute('id3');
		$apli=null;
		$aplim=null;
		$a = new Aplicar($this->dbAdapter);
		$m = new Aplicarm($this->dbAdapter);
		$c = new Convocatoria($this->dbAdapter);
		$p = new Proyectos($this->dbAdapter);
		$te = new Tablaequipo($this->dbAdapter);
		$tf = new Tablafinproy($this->dbAdapter);
		$tep = new Tablaequipop($this->dbAdapter);
		$tfp = new Tablafinp($this->dbAdapter);
		$usuario = new Usuarios($this->dbAdapter);
		$grupos = new Gruposparticipantes ($this->dbAdapter);
		$gruposp = new Gruposproy ($this->dbAdapter);
		$vf=new Agregarvalflex($this->dbAdapter);

		$this->dbAdapters=$this->getServiceLocator()->get('db2');
        $th = new Talentohumano($this->dbAdapters);
		$apli=$a->getAplicar($id);
		$a->updateAplicarestado($id);
		$aplim=$m->getAplicar($id);

		if($apli!=null){
			$conv=$c->getConvocatoriaid($id2);
			foreach($conv as $cv){
				$cv["id_convocatoria"];
			}

			foreach($apli as $ap){
				$ap["nombre_proy"];
			}

			$res=$p->addProyectosconv($ap["nombre_proy"],$id3,$ap["id_investigador"], $ap["duracion"],$ap["id_campo"],$ap["id_linea_inv"],$ap["id_unidad_academica"], $ap["id_dependencia_academica"],$ap["id_programa_academico"],$ap["resumen_ejecutivo"],$ap["objetivo_general"],$ap["periodo"],$cv["id_convocatoria"],$cv["tipo_conv"],$id);

			$tequipo=$te->getTablaequipo($id);
			$tfinan=$tf->getTablafin($id);
			$grup=$grupos->getGruposparticipantes($id);

			$h = $p->getProyectoparam($ap["nombre_proy"],$ap["id_investigador"], $ap["duracion"],$ap["id_campo"],$ap["id_linea_inv"],$ap["id_unidad_academica"], $ap["id_dependencia_academica"],$ap["id_programa_academico"],$ap["resumen_ejecutivo"],$ap["objetivo_general"],$ap["periodo"],$cv["id_convocatoria"],$cv["tipo_conv"],$id);

			foreach($h as $proyid){
				$proyid["id_proyecto"];
			}

			foreach($tfinan as $tfinancia){
				$tfp->addTablafin2($proyid["id_proyecto"],$tfinancia["id_rubro"],$tfinancia["id_fuente"],$tfinancia["valor"],$tfinancia["periodo"]);
				$per=$tfinancia["periodo"];
			}

			foreach($grup as $gr){
				$gruposp->addGruposparticipantes($gr["id_grupo"],$proyid["id_proyecto"]);
			}

			foreach($tequipo as $tablaequipo){
				$usdoc=$usuario->getArrayusuariosid($tablaequipo["id_integrante"]);
				$pers=$th->getUsuarioproyTH(trim($usdoc["documento"]),trim($id3));
				if($pers==null){
					$tep->addTablaequipo2($proyid["id_proyecto"],$tablaequipo["id_integrante"],$tablaequipo["id_rol"],$tablaequipo["id_tipo_dedicacion"],$tablaequipo["horas_sol"],0,1,1);
				}else{
					foreach($pers as $persona){
						$tep->addTablaequipo2($proyid["id_proyecto"],$tablaequipo["id_integrante"],$tablaequipo["id_rol"],$tablaequipo["id_tipo_dedicacion"],$tablaequipo["horas_sol"],$persona["HORAS_SEMANA_PLANTRAB"],$persona["ANO"],$persona["PERIODO"]);
					}
				}
			}
			
			foreach($vf->getvalflexseditar(186) as $vlf){
				$vlf["descripcion_valor"];
			}
			$email=$usuario->getArrayusuariosid($ap["id_investigador"]);
			//cuando se arreglen los problemas con el servidor de correos, volver a enviar los mensajes
			//Se envia mail al dueño del proyecto nnotificando de la aprobación de la propuesta
			//$p->mensajeProyectos($vlf["descripcion_valor"],$ap["nombre_proy"],$proyid["id_proyecto"],$cv["titulo"],$email["email"]);
			$this->flashMessenger()->addMessage("Proyecto Aprobado con exito");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarproyecto/index/'.$res);
		}else{
			$conv=$c->getConvocatoriaid($id2);
			foreach($conv as $cv){
				$cv["id_convocatoria"];
			}

			foreach($aplim as $ap){
				$ap["num_codigo"];
			}

			$resul=$p->addProyectosconvm($ap["id_proyecto"],$id3,$ap["id_usuario"], $ap["id_facultad"],$ap["id_programa_curricular"],$ap["id_convocatoria"],$cv["tipo_conv"],$id);

			foreach($vf->getValoresflexid(186) as $vlf){
				$vlf["descripcion_valor"];
			}

			$email=$usuario->getArrayusuariosid($ap["id_usuario"]);
			$p->mensajeProyectos($vlf["descripcion_valor"],$ap["nombre_proy"],$cv["titulo"],$email["email"]);
			$this->flashMessenger()->addMessage("Proyecto Aprobado con exito");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarproyecto/index/'.$resul);
		}
	}

	public function aprobarAction(){
		$GestionrequiForm= new GestionrequiForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');

		//print_r($data);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$id3 = $this->params()->fromRoute('id3');

		$view = new ViewModel(array('form'=>$GestionrequiForm,
									'titulo'=>"Aprobar Propuesta de Investigación",
									'url'=>$this->getRequest()->getBaseUrl(),
									'datos'=>$id,'id2'=>$id2, 'id3'=>$id3));
		return $view;
	}


    public function asignarAction()
    {
		if($this->getRequest()->isPost()){
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Aplicar($this->dbAdapter);
		    $this->auth = new AuthenticationService();
			$auth = $this->auth;
			$result = $auth->hasIdentity();

			//verifica si esta conectado
			$identi=$auth->getStorage()->read();
			$rol = new Roles($this->dbAdapter);
			$rolusuario = new Rolesusuario($this->dbAdapter);
		 	$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$dat["id_rol"];
			}

			//obtiene la informacion de las pantallas
			$data = $this->request->getPost();
			$AsignarForm= new AsignarForm();

			//define el campo ciudad
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$id3 = $this->params()->fromRoute('id3');

			$id3 = trim($data["codigo"]);

			//adiciona la noticia
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/gestionrequi/aprobar/'.$id.'/'.$id2.'/'.$id3);
		}
		else{
			$AsignarForm= new AsignarForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Aplicar($this->dbAdapter);
			$us = new Aplicarm($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$this->auth = new AuthenticationService();
			$auth = $this->auth;
			$result = $auth->hasIdentity();
			$identi=$auth->getStorage()->read();
			$filter = new StringTrim();

			//verificar roles
			$per=array('id_rol'=>'');
			$dat=array('id_rol'=>'');
			$permiso_1='';
			$roles_1='';
			$rolusuario = new Rolesusuario($this->dbAdapter);
			$permiso = new Permisos($this->dbAdapter);
		
			//me verifica el tipo de rol asignado al usuario
			$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$roles_1=$dat["id_rol"];
			}
		
			if ($dat["id_rol"]!= ''){
				//me verifica el permiso sobre la pantalla
				$pantalla="grupoinv";
				$panta=0;
				$pt = new Agregarvalflex($this->dbAdapter);


				foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
				$panta["id_valor_flexible"];
				}

				$permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]);
				foreach ($permiso->verificarPermiso($roles_1,$panta["id_valor_flexible"]) as $per) {
					$permiso_1=$per["id_rol"];
				}
			}
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$id3 = $this->params()->fromRoute('id3');
			$docreq = new Requisitosapdoc($this->dbAdapter);
			$req = new Requisitosap($this->dbAdapter);
			$eval = new Evaluar($this->dbAdapter);
			if(true){
				$view = new ViewModel(array('form'=>$AsignarForm,
											'titulo'=>"Asignación del código del proyecto",
											'titulo2'=>"Tipos Valores Existentes", 
											'url'=>$this->getRequest()->getBaseUrl(),
											'datos'=>$u->getAplicarconv($id),
											'datos2'=>$us->getAplicarmconv($id),
											'docreq'=>$docreq->getRequisitos(),
											'req'=>$req->getRequisitos(),
											'evaluar'=>$eval->getEvaluar(),
											'id'=>$id,'id2'=>$id2,
											'consulta'=>0,
											'menu'=>$dat["id_rol"]));
				return $view;
			}
			else
			{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
			}
		}
    }



	public function aprobarmAction(){
$GestionrequiForm= new GestionrequiForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');

			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$id3 = $this->params()->fromRoute('id3');


			$view = new ViewModel(array('form'=>$GestionrequiForm,
										'titulo'=>"Aprobar Monitor",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$id,'id2'=>$id2, 'id3'=>$id3));
			return $view;

	}

	public function aprobarproymAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$id3 = (int) $this->params()->fromRoute('id3',0);
		//$id3 = $this->params()->fromRoute('id3');
		$apli=null;
		$aplim=null;
		$a = new Aplicar($this->dbAdapter);
		$m = new Aplicarm($this->dbAdapter);
		$c = new Convocatoria($this->dbAdapter);
		$p = new Proyectos($this->dbAdapter);
		$mon = new Monitor($this->dbAdapter);
		$te = new Tablaequipo($this->dbAdapter);
		$tf = new Tablafinproy($this->dbAdapter);
		$tep = new Tablaequipop($this->dbAdapter);
		$tfp = new Tablafinp($this->dbAdapter);
		
		$vf=new Agregarvalflex($this->dbAdapter);
		$usuario=new Usuarios($this->dbAdapter);
		$apli=$a->getAplicar($id);
		$aplim=$m->getAplicar($id);

		if($apli!=null){
			/*
			$conv=$c->getConvocatoriaid($id2);
			foreach($conv as $cv){
				$cv["id_convocatoria"];
			}

			foreach($apli as $ap){
				$ap["nombre_proy"];
			}

			$p->addProyectosconv($ap["nombre_proy"],$id3,$ap["id_investigador"], $ap["duracion"],$ap["id_campo"],$ap["id_linea_inv"],$ap["id_unidad_academica"], $ap["id_dependencia_academica"],$ap["id_programa_academico"],$ap["resumen_ejecutivo"],$ap["objetivo_general"],$ap["periodo"],$cv["id_convocatoria"],$cv["tipo_conv"],$id);

			$tequipo=$te->getTablaequipo($id);
			$tfinan=$tf->getTablafin($id);

			$h=$p->getProyectoparam($ap["nombre_proy"],$ap["id_investigador"], $ap["duracion"],$ap["id_campo"],$ap["id_linea_inv"],$ap["id_unidad_academica"], $ap["id_dependencia_academica"],$ap["id_programa_academico"],$ap["resumen_ejecutivo"],$ap["objetivo_general"],$ap["periodo"],$cv["id_convocatoria"],$cv["tipo_conv"],$id);

			foreach($h as $proyid){
				$proyid["id_proyecto"];
			}

			foreach($tfinan as $tfinancia){
				$tfp->addTablafin2($proyid["id_proyecto"],$tfinancia["id_rubro"],$tfinancia["id_fuente"],$tfinancia["valor"],$tfinancia["periodo"]);
				$per=$tfinancia["periodo"];
			}

			$this->flashMessenger()->addMessage("Proyecto Aprobado con exito");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/gestionrequi/index/'.$id2);
			*/
		}
		else{
			/*
			$conv=$c->getConvocatoriaid($id2);
			foreach($conv as $cv){
				$cv["id_convocatoria"];
			}

			foreach($aplim as $ap){
				$ap["num_codigo"];
			}

			foreach($vf->getvalflexseditar(186) as $vlf){
				$vlf["descripcion_valor"];
			}

			$email=$usuario->getArrayusuariosid($ap["id_usuario"]);

			$mon->mensajeProyectos($vlf["descripcion_valor"],$ap["num_codigo"],$email["email"]);

			$resul=$mon->addProyectosconvm($ap["num_codigo"],$ap["id_usuario"], $ap["id_facultad"],$ap["id_programa_curricular"],$id,$ap["id_proyecto"]);
			*/
			if($id3 == 1){
				$apli=$m->updateAplicarAprobar($id, "Aprobado");
				$this->flashMessenger()->addMessage("Monitor aprobado con éxito.");
			}else{
				$apli=$m->updateAplicarAprobar($id, "No aprobado");
				$this->flashMessenger()->addMessage("El monitor no fue aprobado.");
			}
			
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/gestionrequi/index/'.$id2);
		}
	}
}