<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Cambiarcontrasena\CambiarcontrasenaForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;

class CambiarcontrasenaController extends AbstractActionController
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
			$u = new Usuarios($this->dbAdapter);
			$data = $this->request->getPost();
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
		$identi->id_usuario; 
if(strlen($data["ContrasenaNueva"])>=6 && strlen($data["ContrasenaNueva"])<=20){
		$resultado=$u->cambiarContrasena($identi->id_usuario,$data,$identi->usuario);
		//exit;
			if ($resultado==2){
			$this->flashMessenger()->addMessage("La contrasena cambio con exito");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/cambiarcontrasena/index');
			}
			if($resultado==1){
			$this->flashMessenger()->addMessage("las contrasenas no coinciden");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/cambiarcontrasena/index');
			}
			if($resultado==''){
			$this->flashMessenger()->addMessage("La contrasena actual no coincide");
   			 $this->auth->clearIdentity();

    			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}
}else{

			$this->flashMessenger()->addMessage("la contrasena debe contener entre 6 y 20 caracteres");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/cambiarcontrasena/index');

}
		
		
		}else{

	    $this->dbAdapter=$this->getServiceLocator()->get('db1');
	    $u = new Usuarios($this->dbAdapter);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
		$identi->id_usuario;
		
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
			$pantalla="cambiarcontrasena";
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
		
		if(true){
			$CambiarcontrasenaForm = new CambiarcontrasenaForm();
			$view = new ViewModel(array('form'=>$CambiarcontrasenaForm,
									'titulo'=>"Cambiar Contrase&ntilde;a",
									'menu'=>$dat["id_rol"],
									'idusuario'=>$identi->id_usuario));
			return $view;
		}
		else
		{
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
		}
		}
    }
}
