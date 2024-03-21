<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Roles\EditarrolesForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class EditarrolesController extends AbstractActionController
{

    private $auth;

    public $dbAdapter;

    public function __construct()
    {
        // Cargamos el servicio de autenticacien el constructor
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
        
        $id = (int) $this->params()->fromRoute('id', 0);
        if($id==1){
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/roles/index');
        }
        
        if ($this->getRequest()->isPost()) {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Roles($this->dbAdapter);
            $data = $this->request->getPost();
            $resul=$u->updateRol($id, $data);

            
            if ($resul == 1) {
                $this->flashMessenger()->addMessage("Rol editado correctamente.");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/roles/index');
            } else {
                $this->flashMessenger()->addMessage("El rol no pudo ser editado correctamente.");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/roles/index');
            }
        } else {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Rolesusuario($this->dbAdapter);
            $EditarrolesForm = new EditarrolesForm();
            $auth = $this->auth;
                        
            // define el campo ciudad
            $vf = new Roles($this->dbAdapter);

            $datBase = $vf->getRolesid($id);
            if($datBase!=null){
                $EditarrolesForm->get('descripcion')->setValue($datBase[0]["descripcion"]);
                $EditarrolesForm->get('observaciones')->setValue($datBase[0]["observaciones"]);
                $EditarrolesForm->get('opcion_pantalla')->setValue($datBase[0]["opcion_pantalla"]);
                $EditarrolesForm->get('submit')->setValue("Actualizar");
            }

            $view = new ViewModel(array(
                'form' => $EditarrolesForm,
                'titulo' => "Editar rol",
                'id' => $id,
                'menu' => 1
            ));
            return $view;
            
        }
    }
}