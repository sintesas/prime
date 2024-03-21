<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editargrupoinv\AgregarcoautorForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Autores;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class AgregarcoautorController extends AbstractActionController
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
			$u = new Autores($this->dbAdapter);
			$us = new Usuarios($this->dbAdapter);
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
			$id3 =  $this->params()->fromRoute('id3');

			$AgregarcoautorForm = new AgregarcoautorForm();
			
			

			$view = new ViewModel(array('form'=>$AgregarcoautorForm,
							'titulo'=>"Asignar/Quitar Coautor",
							'rolesusuario'=>$rolusuario->getRolesusuario(),
							'roles2'=>$rol->getRoles(),
							'roles'=>$rol->getRolesid($id), 
							//'id_rol'=>$id,
							'id_grupo'=>$id,
							'id_padre'=>$id2,
							'nombre_padre'=>$id3,
							'datos'=>$us->filtroUsuario($data),
							'menu'=>$dat["id_rol"])
						);
			return $view;
			
		}else{
		    $this->dbAdapter=$this->getServiceLocator()->get('db1');
		    $u = new Autores($this->dbAdapter);
	        $AgregarcoautorForm = new AgregarcoautorForm();
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
			$AgregarcoautorForm->add(array(
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
			$pantalla="rolesusuario";
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
$id3 =  $this->params()->fromRoute('id3');
		$a=null;
		if(true){
		$view = new ViewModel(array('form'=>$AgregarcoautorForm,
									'titulo'=>"Asignar/Quitar Coautor", 
									'titulo2'=>"Asignar/Quitar Autor", 
									'titulo3'=>"Asignar/Quitar Equipo de Trabajo", 
									'datos'=>$us->getUsuarios($a),
									'id_grupo'=>$id,
									'roles2'=>$vf->getRoles(),
									'id_padre'=>$id2,
									'nombre_padre'=>$id3,
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

	public function asignarAction(){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Autores($this->dbAdapter);
			$data = $this->request->getPost();
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$id3 = (int) $this->params()->fromRoute('id3',0);
			$id4 =  $this->params()->fromRoute('id4');
			
			$resul=$u->addAutores($id,$id2,$id3,$id4);	
			if($resul==1 && ($id4=="proy_ext" || $id4=="proy_exthv")){
				$this->flashMessenger()->addMessage("Asigno el usuario como coautor");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/asignarol/index/'.$id2.'/'.$id.'/'.$id4);
			//}elseif ($id4=="Articuloshv" || $id4=="Libroshv" || $id4 =="bibliografico" || $id4=="otra_prodhv") {
			}elseif ($id4=="Articuloshv" || $id4=="Libroshv" || $id4=="bibliograficohv" || $id4=="otra_prodhv") {
				$this->flashMessenger()->addMessage("Asigno el usuario como coautor");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarusuario/index/'.$id2);
			}else{
				$this->flashMessenger()->addMessage("El Usuario ya fue asignado");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id2);
			}
	}

	public function eliminarAction(){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Autores($this->dbAdapter);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$id3 = (int) $this->params()->fromRoute('id3',0);
			$id4 =  $this->params()->fromRoute('id4');
			$u->eliminarAutores($id,$id2,$id3,$id4);	
			$this->flashMessenger()->addMessage("Quito el usuario de la autoria.");

			if($id4=="Articuloshv" || $id4=="Libroshv" || $id4=="bibliograficohv" || $id4=="otra_prodhv" || $id4=="proy_exthv"){
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarusuario/index/'.$id2);
			}else{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id2);
			}
			
	}
}