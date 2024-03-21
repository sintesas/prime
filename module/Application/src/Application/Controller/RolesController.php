<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Roles\RolesForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class RolesController extends AbstractActionController
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
			$u = new Roles($this->dbAdapter);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
			$data = $this->request->getPost();
			$au = new Auditoria($this->dbAdapter);
			$ad = new Auditoriadet($this->dbAdapter);		  
			function get_real_ip()
    		{
				if (isset($_SERVER["HTTP_CLIENT_IP"]))
				{
					return $_SERVER["HTTP_CLIENT_IP"];
				}
				elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
				{
					return $_SERVER["HTTP_X_FORWARDED_FOR"];
				}
				elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
				{
					return $_SERVER["HTTP_X_FORWARDED"];
				}
				elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
				{
					return $_SERVER["HTTP_FORWARDED_FOR"];
				}
				elseif (isset($_SERVER["HTTP_FORWARDED"]))
				{
					return $_SERVER["HTTP_FORWARDED"];
				}
				else
				{
					return $_SERVER["REMOTE_ADDR"];
				}
			}
			
			$resultado=$u->addRoles($data,$u->getRoles());
			if($resultado==0){
			
			$resultado=$au->getAuditoriaid(session_id(),get_real_ip(),$identi->id_usuario);
			$p=implode(',',  (array)$data);
			$evento='Intento fallido de rol :'.$p;
			$ad->addAuditoriadet( $evento,$resultado);     

			$this->flashMessenger()->addMessage("El valor ingresado en la descripcion esta duplicado");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/roles/index');
			}else{
			$resultado=$au->getAuditoriaid(session_id(),get_real_ip(),$identi->id_usuario);
			$p=implode(',',  (array)$data);
			$evento='Creacion de rol :'. $p . ('aps_roles');
			$ad->addAuditoriadet( $evento,$resultado);     

			$this->flashMessenger()->addMessage("El valor fue ingresado");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/roles/index');
			}
		}else
		{
	    $this->dbAdapter=$this->getServiceLocator()->get('db1');
	    $u = new Roles($this->dbAdapter);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
        $RolesForm = new RolesForm();
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
			$pantalla="roles";
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
		$view = new ViewModel(array('form'=>$RolesForm,
									'titulo'=>"Roles y Permisos", 
									'datos'=>$u->getRoles(),
									'menu'=>$dat["id_rol"]));
		return $view;
		}
		else
		{
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
		}
		}
    }

	public function eliminarAction(){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Roles($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			if($id==1){
	            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/roles/index');
	        }
			$u->eliminarRoles($id);

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
			$evento = 'Eliminación de roles : ' . $id . ' (aps_roles)';
			$ad->addAuditoriadet($evento, $resul);

			$this->flashMessenger()->addMessage("Rol eliminado");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/roles/index');
	}
}
