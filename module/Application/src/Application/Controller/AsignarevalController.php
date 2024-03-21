<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\AsignarevalForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Asignareval;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Aplicar;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class AsignarevalController extends AbstractActionController
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
			$u = new Asignareval($this->dbAdapter);
			$us = new Usuarios($this->dbAdapter);
			$this->auth = new AuthenticationService();
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
			$r='';
			$AsignarevalForm = new AsignarevalForm();
			$view = new ViewModel(array('form'=>$AsignarevalForm,'titulo'=>"Asignar Evaluador",'grupo'=>$id,'datos'=>$us->filtroUsuario($data),'menu'=>$dat["id_rol"]));
			return $view;
		}else
		{
	    $this->dbAdapter=$this->getServiceLocator()->get('db1');
	    $u = new Rolesusuario($this->dbAdapter);
        $AsignarevalForm = new AsignarevalForm();
$this->auth = new AuthenticationService();
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
			$AsignarevalForm->add(array(
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
			$pantalla="Asignarevalhv";
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
		$a=null;
		if(true){
		$view = new ViewModel(array('form'=> $AsignarevalForm,
									'titulo'=>"Asignar Evaluador", 
									'datos'=>$us->getUsuarios($a),
									'grupo'=>$id,
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
			$u = new Asignareval($this->dbAdapter);
			$usuario = new Usuarios($this->dbAdapter);
			$aplicar = new Aplicar($this->dbAdapter);
			$data = $this->request->getPost();
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
	$this->auth = new AuthenticationService();
        $auth = $this->auth;


        $identi=$auth->getStorage()->read();
print_r($identi);

		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}

$resul=$u->addAsignareval($id2,$id);

if($resul==1){
$r=$u->getAsignar($id2,$id);


$this->flashMessenger()->addMessage("Asigno el evaluador al proyecto");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editareval/index/'.$r["id_evaluador"].'/'.$id.'/'.$id2);
}else{
$this->flashMessenger()->addMessage("El Usuario ya tiene un rol asignado");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id2);
}
	}

	public function eliminarAction(){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Asignareval($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
$id2 = (int) $this->params()->fromRoute('id2',0);
			$u->eliminarAsignareval($id);
$this->flashMessenger()->addMessage("Integrante eliminado con exito");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id2);
			
	}

	public function delAction(){
$AsignarevalForm = new AsignarevalForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Asignareval($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);


			$view = new ViewModel(array('form'=>$AsignarevalForm,
										'titulo'=>"Eliminar registro",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$id,'id2'=>$id2));
			return $view;

	}


}