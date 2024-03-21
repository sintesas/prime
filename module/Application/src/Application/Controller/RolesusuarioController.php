<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Rolesusuario\RolesusuarioForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;
use Zend\Paginator\Paginator;

class RolesusuarioController extends AbstractActionController
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

    	$id = (int) $this->params()->fromRoute('id',0);
    	$RolesusuarioForm = new RolesusuarioForm();
    	$roles = new Roles($this->dbAdapter);
    	$rolusuario = new Rolesusuario($this->dbAdapter);
    	$usuarios = new Usuarios($this->dbAdapter);
    	
    	if($this->getRequest()->isPost()){
    		$data = $this->request->getPost();
    		$paginator = $usuarios->fetchFilter($data);
    		$paginator->setCurrentPageNumber(1);
    		$dataPost="&documento=".urlencode($data->documento)."&nombre=".urlencode($data->nombre)."&apellido=".urlencode($data->apellido)."&usuario=".urlencode($data->usuario);
    	}else{
    		$data= (object)[];
    		$data->documento = urldecode($this->params()->fromQuery('documento', ""));
    		$data->nombre = urldecode($this->params()->fromQuery('nombre', ""));
    		$data->apellido = urldecode($this->params()->fromQuery('apellido', ""));
    		$data->usuario = urldecode($this->params()->fromQuery('usuario', ""));
    		if($data->documento == "" && $data->nombre == "" && $data->apellido =="" && $data->usuario ==""){
    			$paginator = $usuarios->fetchAll();
    		}else{
    			$paginator = $usuarios->fetchFilter($data);
    		}
    		$paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
    		$dataPost="&documento=".urlencode($data->documento)."&nombre=".urlencode($data->nombre)."&apellido=".urlencode($data->apellido)."&usuario=".urlencode($data->usuario);
    	}
		
     	$paginator->setItemCountPerPage(200);
     	$paginator->setPageRange(28);
     	$datUsuario = $rolusuario->verificarRolusuario($identi)[0];
     	
		$view = new ViewModel(array(
			'form'=>$RolesusuarioForm,
			'titulo'=>"Asignar rol a usuario", 
			'id'=>$id,
			'roles'=>$roles->getRoles(),
			'rolesusuario'=>$rolusuario->getRolesusuario(),
			'menu'=>$datUsuario["id_rol"],
			'paginator'=> $paginator,
			'dataPost' => $dataPost
		));
		return $view;
    }

	public function  asignarAction(){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Rolesusuario($this->dbAdapter);
$data = $this->request->getPost();
$id = (int) $this->params()->fromRoute('id',0);
$id2 = (int) $this->params()->fromRoute('id2',0);
			$id = (int) $this->params()->fromRoute('id',0);
			$resul=$u->agregarRolusuario($id2,$id);	
if($resul==1){
$this->flashMessenger()->addMessage("Asigno el rol al usuario");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/roles/index');
}else{
$this->flashMessenger()->addMessage("El Usuario ya tiene un rol asignado");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/roles/index');
}
	}

	public function eliminarAction(){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Rolesusuario($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$u->eliminarRolesusuario($id);	
$this->flashMessenger()->addMessage("Quito el usuario del rol");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/roles/index');
	}
}