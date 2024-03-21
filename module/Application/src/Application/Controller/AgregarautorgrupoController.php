<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editargrupoinv\AgregarautorgrupoForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Agregarautorgrupo;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Grupoinvestigacion;


class AgregarautorgrupoController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     //Cargamos el servicio de autenticacien el constructor
     $this->auth = new AuthenticationService();
	}
	
    public function indexAction()
    {
		$usuariosArray = array();
		
		$AgregarautorgrupoForm = new AgregarautorgrupoForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		
		$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$id3= (int) $this->params()->fromRoute('id3',0);
		if($this->getRequest()->isPost()){
			$data = $this->request->getPost();
			$usuariosb = new Usuarios($this->dbAdapter);
			$usuariosArray = $usuariosb->filtroRedes($data);
		}

		$u = new Agregarautorgrupo($this->dbAdapter);
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
		//termina de verifcar los permisos
		if(true){
			$id = (int) $this->params()->fromRoute('id',0);
			$view = new ViewModel(
				array(
					'form'=>$AgregarautorgrupoForm,
					'titulo'=>"Agregar/Quitar autor",
					'url'=>$this->getRequest()->getBaseUrl(),
					'id'=>$id,
					'id2'=>$id2,
					'id3'=>$id3,
					'menu'=>$dat["id_rol"],
					'usuariosArray' => $usuariosArray
				)
			);
			return $view;
		}
		else
		{
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
		}
		
    }

	public function eliminarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Agregarautorgrupo($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);

		$u->eliminarAgregarautorgrupo($id);
		$this->flashMessenger()->addMessage("Producción de investigación eliminada con éxito.");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id3);	
	}

	public function delAction(){
		$AgregarautorgrupoForm = new AgregarautorgrupoForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Agregarautorgrupo($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$view = new ViewModel(
			array(
				'form'=>$AgregarautorgrupoForm,
				'titulo'=>"Eliminar Producción de investigación",
				'url'=>$this->getRequest()->getBaseUrl(),
				'id'=>$id,
				'id2'=>$id2
			)
		);
		return $view;
	}

	public function addAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Agregarautorgrupo($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$id3 = (int) $this->params()->fromRoute('id3',0);
		$id4 = (int) $this->params()->fromRoute('id4',0);
		$u->addAgregarautorgrupo($id,$id2,$id3,$id4);
		$this->flashMessenger()->addMessage("Autor agregado con éxito.");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id3);	
	}
	

	public function dropAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Agregarautorgrupo($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$id3 = (int) $this->params()->fromRoute('id3',0);
		$id4 = (int) $this->params()->fromRoute('id4',0);
		$u->eliminarAgregarautorgrupo($id,$id3,$id2,$id4);
		$this->flashMessenger()->addMessage("Autor eliminado con éxito.");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id3);	
	}
	

}