<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Usuario\UsuarioForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Aplicar;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Integrantes;
use Zend\Paginator\Paginator;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class UsuarioController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     //Cargamos el servicio de autenticacien el constructor
     $this->auth = new AuthenticationService();
	}
 
    public function indexAction()
    {
    	# Para pantallas accesadas por el menu, debo reiniciar el navegador
    	session_start();
    	if(isset($_SESSION['navegador'])){unset($_SESSION['navegador']);}
    	
    	$this->dbAdapter=$this->getServiceLocator()->get('db1');
        $auth = $this->auth;
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

		$auth = $this->auth;
		$identi=$auth->getStorage()->read();

		$au = new Auditoria($this->dbAdapter);
		$ad = new Auditoriadet($this->dbAdapter);

		if($this->getRequest()->isPost()){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Usuarios($this->dbAdapter);
			$gu = new Grupoinvestigacion($this->dbAdapter);
			$data = $this->request->getPost();

			$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
			$evento = 'Consulta de usuarios (aps_usuarios)';
			$ad->addAuditoriadet($evento, $resul);

		
		$rol = new Roles($this->dbAdapter);
		$rolusuario = new Rolesusuario($this->dbAdapter);
		 $Usuarioform = new UsuarioForm();

		 //define el campo ciudad
		$vf = new Grupoinvestigacion($this->dbAdapter);
		$opciones=array();
		foreach ($vf->getGrupoinvestigacion() as $dept) {
		$op=array(''=>'');
		$op=$op+array($dept["id_grupo_inv"]=>$dept["nombre_grupo"]);
		$opciones=$opciones+$op;
		}
		$Usuarioform->add(array(
		'name' => 'id_grupo_inv',
		'type' => 'Zend\Form\Element\Select',
               'attributes' => array(
                   'id' => 'id_grupo_inv',
               ),
		'options' => array(
        'label' => 'Grupo de investigación:',
        'value_options' => 
			$opciones
        ,
		),
		)); 


			$vf = new Aplicar($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getAplicarh() as $da) {
			$op=array(''=>'');
			$op=$op+array($da["id_aplicar"]=>$da["nombre_proy"]);
			$opciones=$opciones+$op;
			}
			$Usuarioform->add(array(
			'name' => 'id_proyecto_investigacion',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
           			 'label' => 'Proyecto de Investigacion : ',
				
           			 'value_options' => 
				$opciones
            ,
			),
			)); 

			$rf = new Roles($this->dbAdapter);
			$opciones=array();
			foreach ($rf->getRoles() as $da) {
			$op=array(''=>'');
			$op=$op+array($da["id_rol"]=>$da["descripcion"]);
			$opciones=$opciones+$op;
			}
			$Usuarioform->add(array(
			'name' => 'rol_usuario',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
           			 'label' => 'Filtro Roles : ',
				
           			 'value_options' => 
				$opciones
            ,
			),
			)); 

			$Usuarioform->add(array(
			'name' => 'id_estado',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
           			'label' => 'Filtro Estado : ',				
           			'value_options' => array(
                        '' => '',
                        'S' => 'Activo',
                        'N' => 'Inactivo',
                        'B' => 'Bloqueado',
                        'F' => 'Bloqueado Foros'
                    )
			),
			)); 

		$identi=$auth->getStorage()->read();
		$rolusuario->verificarRolusuario($identi->id_usuario);
		foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
			$dat["id_rol"];
		}
		
		$gu = new Grupoinvestigacion($this->dbAdapter);
		$inte = new Integrantes($this->dbAdapter);
		$rolusuarioc = new Rolesusuario($this->dbAdapter);
		
		$dataPost="&id_usu=".urlencode($data->id_usu)."&documento=".urlencode($data->documento)."&nombre=".urlencode($data->nombre)."&apellido=".urlencode($data->apellido)."&usuario=".urlencode($data->usuario)."&rol_usuario=".urlencode($data->rol_usuario)."&id_grupo_inv=".urlencode($data->id_grupo_inv)."&id_estado=".urlencode($data->id_estado);
		
		if($data->id_usu == "" && $data->documento == "" && $data->nombre == "" && $data->apellido =="" && $data->usuario =="" && $data->rol_usuario =="" && $data->id_grupo_inv =="" && $data->id_estado ==""){
			$paginator = $u->fetchAll();
		}else{
			$aux = array();
			if($data->rol_usuario != ""){
				$usuarios = $rolusuarioc->getRolesusuarioid($data->rol_usuario);
	            foreach ($usuarios as $key) {
	                array_push($aux, $key["id_usuario"]);
	            }
			}
			if($data->id_grupo_inv != ""){
				$grupos = $gu->getGrupoinvid($data->id_grupo_inv);
				foreach ($grupos as $key) {
	                array_push($aux, $key["id_lider"]);
	            }

	            $integrantes = $inte->getIntegrantes($data->id_grupo_inv);
				foreach ($integrantes as $key) {
	                array_push($aux, $key["id_integrante"]);
	            }

			}
			$paginator = $u->fetchFilterUsuarios($data, $aux);
		}

		$paginator->setItemCountPerPage(200);
     	$paginator->setPageRange(24);

		$view = new ViewModel(
			array(
				'form'=>$Usuarioform,
				'mensaje'=>"la busqueda no retorno datos.",
				'traer'=>1,
				'usua'=>$u->getArrayusuarios(),
				'campos'=>$data,
				'titulo'=>"Usuarios",
				'datosgu'=>$gu->filtroGrupos($data), 
				'datos'=>$u->filtroUsuario($data),
				'datos2'=>$rolusuario->getRolesusuarioid($data["rol_usuario"]),
				'datos3'=>$rol->getRoles(),
				'menu'=>$dat["id_rol"],
				'grupostotal' => $gu->getGrupoinvestigacion(),
				'integrantes' => $inte->getIntegrantesi(),
				'paginator'=> $paginator,
				'dataPost' => $dataPost
			)
		);
		return $view;




		}else{
	    $this->dbAdapter=$this->getServiceLocator()->get('db1');
	    $u = new Usuarios($this->dbAdapter);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
		
        $Usuarioform = new UsuarioForm();

        	$gu = new Grupoinvestigacion($this->dbAdapter);
		
		$rolusuario = new Rolesusuario($this->dbAdapter);
		$inte = new Integrantes($this->dbAdapter);

        $data= (object)[];
		$data->id_usu = urldecode($this->params()->fromQuery('id_usu', ""));
		$data->documento = urldecode($this->params()->fromQuery('documento', ""));
		$data->nombre = urldecode($this->params()->fromQuery('nombre', ""));
		$data->apellido = urldecode($this->params()->fromQuery('apellido', ""));
		$data->usuario = urldecode($this->params()->fromQuery('usuario', ""));
		$data->rol_usuario = urldecode($this->params()->fromQuery('rol_usuario', ""));
		$data->id_grupo_inv = urldecode($this->params()->fromQuery('id_grupo_inv', ""));
		$data->id_estado = urldecode($this->params()->fromQuery('id_estado', ""));


		if($data->id_usu == "" && $data->documento = "" && $data->nombre == "" && $data->apellido =="" && $data->usuario =="" && $data->rol_usuario =="" && $data->id_grupo_inv =="" && $data->id_estado ==""){
			$paginator = $u->fetchAll();
		}else{
			$aux = array();
			if($data->rol_usuario != ""){
				$usuarios = $rolusuario->getRolesusuarioid($data->rol_usuario);
	            foreach ($usuarios as $key) {
	                array_push($aux, $key["id_usuario"]);
	            }
			}
			if($data->id_grupo_inv != ""){
				$grupos = $gu->getGrupoinvid($data->id_grupo_inv);
				foreach ($grupos as $key) {
	                array_push($aux, $key["id_lider"]);
	            }

	            $integrantes = $inte->getIntegrantes($data->id_grupo_inv);
				foreach ($integrantes as $key) {
	                array_push($aux, $key["id_integrante"]);
	            }

			}
			$paginator = $u->fetchFilterUsuarios($data, $aux);
		}

		$paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
		$dataPost="&id_usu=".urlencode($data->id_usu)."&documento=".urlencode($data->documento)."&nombre=".urlencode($data->nombre)."&apellido=".urlencode($data->apellido)."&usuario=".urlencode($data->usuario)."&rol_usuario=".urlencode($data->rol_usuario)."&id_grupo_inv=".urlencode($data->id_grupo_inv)."&id_estado=".urlencode($data->id_estado);
		$paginator->setItemCountPerPage(200);
     	$paginator->setPageRange(24);







    //define el campo ciudad
		$vf = new Grupoinvestigacion($this->dbAdapter);
		$opciones=array();
		foreach ($vf->getGrupoinvestigacion() as $dept) {
		$op=array(''=>'');
		$op=$op+array($dept["id_grupo_inv"]=>$dept["nombre_grupo"]);
		$opciones=$opciones+$op;
		}
		$Usuarioform->add(array(
		'name' => 'id_grupo_inv',
		'type' => 'Zend\Form\Element\Select',
               'attributes' => array(
                   'id' => 'id_grupo_inv',
               ),
		'options' => array(
        'label' => 'Grupo de investigación:',
        'value_options' => 
			$opciones
        ,
		),
		)); 

		
		//verificar roles
		$per=array('id_rol'=>'');
		$dat=array('id_rol'=>'');
		$rol = new Roles($this->dbAdapter);
		$rolusuario = new Rolesusuario($this->dbAdapter);
		$permiso = new Permisos($this->dbAdapter);
		

			$vf = new Aplicar($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getAplicarh() as $da) {
			$op=array(''=>'');
			$op=$op+array($da["id_aplicar"]=>$da["nombre_proy"]);
			$opciones=$opciones+$op;
			}
			$Usuarioform->add(array(
			'name' => 'id_proyecto_investigacion',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
           			 'label' => 'Proyecto de Investigacion : ',
				
           			 'value_options' => 
				$opciones
            ,
			),
			)); 

	$rf = new Roles($this->dbAdapter);
			$opciones=array();
			foreach ($rf->getRoles() as $da) {
			$op=array(''=>'');
			$op=$op+array($da["id_rol"]=>$da["descripcion"]);
			$opciones=$opciones+$op;
			}
			$Usuarioform->add(array(
			'name' => 'rol_usuario',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
           			 'label' => 'Filtro Roles : ',
				
           			 'value_options' => 
				$opciones
            ,
			),
			)); 

			$Usuarioform->add(array(
			'name' => 'id_estado',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
           			'label' => 'Filtro Estado : ',				
           			'value_options' => array(
                        '' => '',
                        'S' => 'Activo',
                        'N' => 'Inactivo',
                        'B' => 'Bloqueado',
                        'F' => 'Bloqueado Foros'
                    )
			),
			));



		//me verifica el tipo de rol asignado al usuario
		$rolusuario->verificarRolusuario($identi->id_usuario);
		foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
			$dat["id_rol"];
		}
		
		if ($dat["id_rol"]!= ''){
			//me verifica el permiso sobre la pantalla
			$pantalla="usuario";
			$panta=0;
			$pt = new Agregarvalflex($this->dbAdapter);


		foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
			$panta["id_valor_flexible"];
		}

			$permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]);
			foreach ($permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]) as $per) {
				$per["id_rol"];
			}
		}
		$gu = new Grupoinvestigacion($this->dbAdapter);
		$inte = new Integrantes($this->dbAdapter);

		$id = (int) $this->params()->fromRoute('id',0);
		$view = new ViewModel(
			array(
				'form'=>$Usuarioform,
				'titulo'=>"Usuarios",
				'datos'=>$u->getUsuarios($id), 
				'datos2'=>$rolusuario->getRolesusuario(),
				'datos3'=>$rol->getRoles(),
				'grupostotal' => $gu->getGrupoinvestigacion(),
				'integrantes' => $inte->getIntegrantesi(),
				'menu'=>$dat["id_rol"],
				'paginator'=> $paginator,
				'dataPost' => $dataPost,
				'campos'=>$data,
			)
		);
		return $view;
		}
    }
	
}
