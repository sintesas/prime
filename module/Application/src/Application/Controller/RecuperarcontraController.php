<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Recuperarcontra\RecuperarcontraForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Usuarios;


class RecuperarcontraController extends AbstractActionController
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
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Usuarios($this->dbAdapter);
	        $auth = $this->auth;
	        $identi=$auth->getStorage()->read();
			$data = $this->request->getPost();
			$resultado=$u->recuperarContra($data);
			if($resultado == 1){
				$this->flashMessenger()->addMessage("Recuperación de contraseña éxitosa, la contraseña fue enviada al correo registrado en el sistema. <".$resultado.">");
			}else{
				$this->flashMessenger()->addMessage("Los datos ingresados no pertenecen a ningún usuario registrado en el sistema. Por favor intente nuevamente.");
			}
			echo $data["usuario"];
			$urlI="/application/recuperarcontra/index";
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlI);
		}else{
			$RecuperarcontraForm = new RecuperarcontraForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$id = (int) $this->params()->fromRoute('id',0);
			$u = new Usuarios($this->dbAdapter);
	        $auth = $this->auth;
	        $identi=$auth->getStorage()->read();
			$view = new ViewModel(
				array(
					'form'=>$RecuperarcontraForm,
					'titulo'=>"Recuperar Contrase&ntilde;a", 
					'url'=>$this->getRequest()->getBaseUrl()
				)
			);
			return $view;
		}
    }
}
