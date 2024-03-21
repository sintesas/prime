<?php

namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Gestionpantallas\GestionpantallasForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Modulo;
use Application\Modelo\Entity\Permisos;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;


class GestionpantallasController extends AbstractActionController
{
    private $auth;
    public $dbAdapter;
    public function __construct(){
        $this->auth = new AuthenticationService();
    }


    public function indexAction()
    {
        # Para pantallas accesadas por el menu, debo reiniciar el navegador
        $this->dbAdapter=$this->getServiceLocator()->get('db1');
        $auth = $this->auth;

        $permisos = new Permisos($this->dbAdapter);
        $identi = print_r($auth->getStorage()->read()->id_usuario,true);
        $resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,get_class());
        if($resultadoPermiso==0){
            $this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
        }

        $GestionpantallasForm = new GestionpantallasForm();
        $view = new ViewModel(array(
            'form' => $GestionpantallasForm,
            'titulo' => "Gestión de módulos, submódulos y formularios",
            'url' => $this->getRequest()->getBaseUrl(),
            'menu' => '1'
        ));
        return $view;
    }
}