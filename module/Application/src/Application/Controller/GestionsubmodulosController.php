<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Gestionsubmodulos\GestionsubmodulosForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Modulo;
use Application\Modelo\Entity\Submodulo;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Agregarvalfelx;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;

class GestionsubmodulosController extends AbstractActionController
{

    private $auth;
    public $dbAdapter;
    public function __construct(){
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
        
        $GestionsubmodulosForm = new GestionsubmodulosForm();
        $modulo = new Modulo($this->dbAdapter);
        $submodulo = new Submodulo($this->dbAdapter);
        if ($this->getRequest()->isPost()){
             $data = $this->request->getPost();
             $submodulo->addSubmodulo($data);
        }    
        $modulos=$modulo->geModulosi();
        $opciones=array();
        foreach ($modulos as $mod) {
            $opciones=$opciones+array($mod["id"]=>$mod["nombre"]);
        }
        $GestionsubmodulosForm->get('id_modulo')->setValueOptions($opciones);

        $view = new ViewModel(array(
            'form' => $GestionsubmodulosForm,
            'titulo' => "Gestión de submódulos",
            'url' => $this->getRequest()->getBaseUrl(),
            'modulos' => $modulos,
            'submodulos' => $submodulo->getSubmodulosi(),
            'menu' => '1'
        ));
        return $view;
    }
}