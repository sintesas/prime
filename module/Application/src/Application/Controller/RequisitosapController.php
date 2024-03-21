<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\RequisitosapForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Requisitosap;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;

class RequisitosapController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     //Cargamos el servicio de autenticacien el constructor
     $this->auth = new AuthenticationService();
	}
	
    public function indexAction()
    {
		if($this->getRequest()->isPost()){
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Requisitosap($this->dbAdapter);
			$auth = $this->auth;

			//verifica si esta conectado
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

			//obtiene la informacion de las pantallas
			$data = $this->request->getPost();
			$RequisitosapForm= new RequisitosapForm();
			//adiciona la noticia
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$id3 = (int) $this->params()->fromRoute('id3',0);
			$conv = new Convocatoria($this->dbAdapter);
			$apli = new Aplicarm($this->dbAdapter);

			$id_convocatoria=$apli->getID($id2);
			$tipo=$conv->getConvocatoriaid($id_convocatoria["id_convocatoria"]);

			$resultado=$not->updateRequisitos($id,$data);

foreach($tipo as $t){
trim($t["tipo_conv"]);
}
			//redirige a la pantalla de inicio del controlador
			if ($resultado==1){
if(trim($t["tipo_conv"])=='m'){
				$this->flashMessenger()->addMessage("Estado Modificado con exito");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/gestionproym/index/'.$id2.'/'.$id3);
}else{
				$this->flashMessenger()->addMessage("Estado Modificado con exito");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/gestionproy/index/'.$id2);
}

				

			}else
			{
				$this->flashMessenger()->addMessage("La creacion del archivo fallo");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id);
			}
		}
		else
		{
			$RequisitosapForm= new RequisitosapForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Requisitosap($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			$filter = new StringTrim();


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
			$pantalla="editargrupoinv";
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
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);			
			$id3 = (int) $this->params()->fromRoute('id3',0);

			$view = new ViewModel(array('form'=>$RequisitosapForm,
										'titulo'=>"Requisitos para la Monitoría/Convocatoria",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$u->getRequisitosapid($id),
										'id'=>$id,
										'id3'=>$id3,
										'id2'=>$id2,										'id2'=>$id2,
										'menu'=>$dat["id_rol"]));
			return $view;
			}
			else
			{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
			}
		}
    }
	

	
	
}