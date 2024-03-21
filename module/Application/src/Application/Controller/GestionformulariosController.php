<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Gestionformularios\GestionformulariosForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Modulo;
use Application\Modelo\Entity\Submodulo;
use Application\Modelo\Entity\Formulario;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Agregarvalfelx;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;

class GestionformulariosController extends AbstractActionController
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
        
        $GestionformulariosForm = new GestionformulariosForm();
        $modulo = new Modulo($this->dbAdapter);
        $submodulo = new Submodulo($this->dbAdapter);
        $formulario = new Formulario($this->dbAdapter);
        if ($this->getRequest()->isPost()){
             $data = $this->request->getPost();
             $formulario->addFormulario($data);
        }    
        $submodulos=$submodulo->getSubmodulosi();
        $opciones=array();
        foreach ($submodulos as $mod) {
            $opciones=$opciones+array($mod["id"]=>$mod["nombre"]);
        }
        $GestionformulariosForm->get('id_submodulo')->setValueOptions($opciones);

        $view = new ViewModel(array(
            'form' => $GestionformulariosForm,
            'titulo' => "Gestión de formularios",
            'url' => $this->getRequest()->getBaseUrl(),
            'modulos' => $modulo->geModulosi(),
            'submodulos' => $submodulos,
            'formularios' => $formulario->getFormularioi(),
            'menu' => '1'
        ));
        return $view;
    }
}