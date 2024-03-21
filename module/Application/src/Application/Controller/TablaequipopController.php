<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Proyectos\TablaequipopForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Talentohumano;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class TablaequipopController extends AbstractActionController
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
        
        if ($this->getRequest()->isPost()) {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Tablaequipop($this->dbAdapter);
            $us = new Usuarios($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $rol = new Roles($this->dbAdapter);
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
            }
            
            $data = $this->request->getPost();
            // print_r($data);
            $id = (int) $this->params()->fromRoute('id', 0);
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            $id3 = (int) $this->params()->fromRoute('id3', 0);
            $id4 = $this->params()->fromRoute('id4');
            $id5 = $this->params()->fromRoute('id5');
            $r = '';
            $TablaequipopForm = new TablaequipopForm();
            $view = new ViewModel(array(
                'form' => $TablaequipopForm,
                'titulo' => "Asignar Integrantes Equipo Proyecto",
                'grupo' => $id,
                'id2' => $id2,
                'id3' => $id3,
                'id4' => $id4,
                'id5' => $id5,
                'datos' => $us->filtroUsuarioIntegrantes($data),
                'menu' => $dat["id_rol"]
            ));
            return $view;
        } else {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            
            $u = new Rolesusuario($this->dbAdapter);
            
            $TablaequipopForm = new TablaequipopForm();
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            // define el campo ciudad
            $vf = new Roles($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getRoles() as $dat) {
                $op = array(
                    $dat["id_rol"] => $dat["descripcion"]
                );
                $opciones = $opciones + $op;
            }
            $TablaequipopForm->add(array(
                'name' => 'roles',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Roles: ',
                    'value_options' => $opciones
                )
            ));
            
            $id = (int) $this->params()->fromRoute('id', 0);
            
            // verificar roles
            $per = array(
                'id_rol' => ''
            );
            $dat = array(
                'id_rol' => ''
            );
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $permiso = new Permisos($this->dbAdapter);
            
            // me verifica el tipo de rol asignado al usuario
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
            }
            
            if ($dat["id_rol"] != '') {
                // me verifica el permiso sobre la pantalla
                $pantalla = "tablaequipo";
                $panta = 0;
                $pt = new Agregarvalflex($this->dbAdapter);
                
                foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
                    $panta["id_valor_flexible"];
                }
                
                $permiso->verificarPermiso($dat["id_rol"], $panta["id_valor_flexible"]);
                foreach ($permiso->verificarPermiso($dat["id_rol"], $panta["id_valor_flexible"]) as $per) {
                    $per["id_rol"];
                }
            }
            $us = new Usuarios($this->dbAdapter);
            $id = (int) $this->params()->fromRoute('id', 0);
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            $id3 = (int) $this->params()->fromRoute('id3', 0);
            $id4 = $this->params()->fromRoute('id4');
            $id5 = $this->params()->fromRoute('id5');
            $a = null;
            if (true) {
                $view = new ViewModel(array(
                    'form' => $TablaequipopForm,
                    'titulo' => "Asignar Integrantes Equipo Proyecto",
                    'datos' => $us->getUsuarios($a),
                    
                    'grupo' => $id,
                    'id_rol' => $id,
                    'id2' => $id2,
                    'id3' => $id3,
                    'id4' => $id4,
                    'id5' => $id5,
                    'roles2' => $vf->getRoles(),
                    'rolesusuario' => $rolusuario->getRolesusuario(),
                    'roles' => $vf->getRolesid($id),
                    'menu' => $dat["id_rol"]
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/mensajeadministrador/index');
            }
        }
    }

    public function asignarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Tablaequipop($this->dbAdapter);
        $us = new Usuarios($this->dbAdapter);
        
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $id3 = (int) $this->params()->fromRoute('id3', 0);
        $id4 = (int) $this->params()->fromRoute('id4', 0);
        $id5 = $this->params()->fromRoute('id5');
        $id6 = $this->params()->fromRoute('id6');
        $this->dbAdapters = $this->getServiceLocator()->get('db2');
        $th = new Talentohumano($this->dbAdapters);
        
        $resus = $us->getArrayusuariosid($id);
        
        $resth = $th->getUsuarioproyTH2($resus["documento"], $id5, $id4, $id3);
        
        if ($resth != null) {
            
            $resul = $u->addTablaequipo3($id, $id2, $id3, $id4, $resth["HORAS_SEMANA_PLANTRAB"]);
        } else {
            
            $resul = $u->addTablaequipo3($id, $id2, $id3, $id4, 0);
        }
        
        if ($resul != null) {
            if ($id6 == 1) {
                $this->flashMessenger()->addMessage("Asigno el integrante al grupo");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/editartablaep/index/' . $resul . '/' . $id2);
            } else {
                $this->flashMessenger()->addMessage("Asigno el integrante al grupo");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/editarproyecto/index/' . $id2);
            }
        } else {
            $this->flashMessenger()->addMessage("El Usuario ya tiene un rol asignado");
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/editarproyecto/index/' . $id2);
        }
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Tablaequipop($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarTablaequipo($id);
        $this->flashMessenger()->addMessage("Integrante eliminado con exito");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editarproyecto/index/' . $id2);
    }

    public function delAction()
    {
        $TablaequipopForm = new TablaequipopForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Tablaequipop($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $TablaequipopForm,
            'titulo' => "Eliminar registro",
            'url' => $this->getRequest()->getBaseUrl(),
            'datos' => $id,
            'id2' => $id2
        ));
        return $view;
    }
}