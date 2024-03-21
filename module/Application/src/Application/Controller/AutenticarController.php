<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Autenticar\AutenticarForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Usuarios;


class AutenticarController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     //Cargamos el servicio de autenticación en el constructor
     $this->auth = new AuthenticationService();
	}
	
    public function indexAction()
    {

			
		if($this->getRequest()->isPost()){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Usuarios($this->dbAdapter);
			$data = $this->request->getPost();
			//print_r($data);
			$u->autenticar($data);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
			$urlI="/application/index/index/";
			$urlI =$urlI.$data["usuario"];
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlI);
		}else{
			$AutenticarForm = new AutenticarForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$id = (int) $this->params()->fromRoute('id',0);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
			$u = new Usuarios($this->dbAdapter);
			$view = new ViewModel(array('form'=>$AutenticarForm,
										'titulo'=>"Datos Usuarios", 
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$u->getUsuarios()));
			return $view;
		}

    }
}
