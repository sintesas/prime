<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\TablaequipoForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Tablaequipo;
use Application\Modelo\Entity\Talentohumano;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class TablaequipoController extends AbstractActionController
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
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Tablaequipo($this->dbAdapter);
			$us = new Usuarios($this->dbAdapter);
			$vf = new Roles($this->dbAdapter);
			$auth = $this->auth;

			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			$rol = new Roles($this->dbAdapter);
			$rolusuario = new Rolesusuario($this->dbAdapter);
			$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$dat["id_rol"];
			}

			$data = $this->request->getPost();
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$id3 = (int) $this->params()->fromRoute('id3',0);
			$r='';
			$TablaequipoForm = new TablaequipoForm();
			$view = new ViewModel(array(
				'form'=>$TablaequipoForm,
				'titulo'=>"Asignar Integrantes Equipo",
				'grupo'=>$id,
				'datos'=>$us->filtroUsuario($data),
				'menu'=>$dat["id_rol"],
				'id3'=>$id3,
									'id_rol'=>$id,
									'roles2'=>$vf->getRoles(),
									'rolesusuario'=>$rolusuario->getRolesusuario(),
									'roles'=>$vf->getRolesid($id)



			)



									

		);
			return $view;
		}else
		{
	    $this->dbAdapter=$this->getServiceLocator()->get('db1');

	    $u = new Rolesusuario($this->dbAdapter);

        $TablaequipoForm = new TablaequipoForm();
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}

			
			//define el campo ciudad
			$vf = new Roles($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getRoles() as $dat) {
			$op=array($dat["id_rol"]=>$dat["descripcion"]);
			$opciones=$opciones+$op;
			}
			$TablaequipoForm->add(array(
			'name' => 'roles',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Roles: ',
            'value_options' => 
				$opciones
            ,
			),
			));

			$id = (int) $this->params()->fromRoute('id',0);
			
		//verificar roles
		$per=array('id_rol'=>'');
		$dat=array('id_rol'=>'');
		$rolusuario = new Rolesusuario($this->dbAdapter);
		$permiso = new Permisos($this->dbAdapter);
		
		//me verifica el tipo de rol asignado al usuario
		$rolusuario->verificarRolusuario($identi->id_usuario);
		foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
			$dat["id_rol"];
		}
		
		if ($dat["id_rol"]!= ''){
			//me verifica el permiso sobre la pantalla
			$pantalla="tablaequipo";
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
		$us = new Usuarios($this->dbAdapter);
$id = (int) $this->params()->fromRoute('id',0);
$id2 = (int) $this->params()->fromRoute('id2',0);
$id3 = (int) $this->params()->fromRoute('id3',0);
		$a=null;
		if(true){
		$view = new ViewModel(array('form'=> $TablaequipoForm,
									'titulo'=>"Asignar Integrantes Equipo", 
									'datos'=>$us->getUsuarios($a),

									'grupo'=>$id,
									'id3'=>$id3,
									'id_rol'=>$id,
									'roles2'=>$vf->getRoles(),
									'rolesusuario'=>$rolusuario->getRolesusuario(),
									'roles'=>$vf->getRolesid($id),
									'menu'=>$dat["id_rol"]));
		return $view;
		}
		else
		{
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
		}
		}
    }

	public function  asignarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Tablaequipo($this->dbAdapter);

		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$id3 = (int) $this->params()->fromRoute('id3',0);

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
		
		$resul=$u->addTablaequipo($id,$id2);	
		if($id3!=0){
			$urlr="/application/editaraplicari/index/";
		}else{
			$urlr="/application/editaraplicar/index/";
		}
		if($resul==1){
			$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
			$evento = 'Creación de tabla equipo de trabajo : ' . $resultado . ' (mgc_tabla_equipos)';
			$ad->addAuditoriadet($evento, $resul);

			$this->flashMessenger()->addMessage("Asignó el integrante al grupo");

		}else{
			$this->flashMessenger()->addMessage("El Usuario ya tiene un rol asignado");

}
return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlr.$id2);
	}

	public function eliminarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Tablaequipo($this->dbAdapter);
		//print_r($data);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$id3 = (int) $this->params()->fromRoute('id3',0);
		$u->eliminarTablaequipo($id);

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

		$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
		$evento = 'Eliminación de tabla equipo de trabajo : ' . $id . ' (mgc_tabla_equipos)';
		$ad->addAuditoriadet($evento, $resul);

		$this->flashMessenger()->addMessage("Integrante eliminado con eéito");
		if($id3!=0){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicari/index/'.$id2);
		}else{
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicar/index/'.$id2);
		}
	}

	public function delAction(){
$TablaequipoForm = new TablaequipoForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Tablaequipo($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$id3 = (int) $this->params()->fromRoute('id3',0);


			$view = new ViewModel(array('form'=>$TablaequipoForm,
										'titulo'=>"Eliminar registro",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$id,'id2'=>$id2,'id3'=>$id3));
			return $view;

	}


}