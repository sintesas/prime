<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Application\Proyectos\GruposproyForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Gruposproy;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
class GruposproyController extends AbstractActionController
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
            echo  '1';
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $us = new Grupoinvestigacion($this->dbAdapter);
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
            $id = (int) $this->params()->fromRoute('id', 0);
            $GruposproyForm = new GruposproyForm();
            $view = new ViewModel(array(
                'form' => $GruposproyForm,
                'titulo' => "Asignar Grupos Participantes Proyecto",
                'grupo' => $id,
                'datos' => $us->filtroGruposNombreGrupo($data),
                'menu' => $dat["id_rol"]
            ));
            return $view;
        } else {
            echo '2';
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Rolesusuario($this->dbAdapter);
            $GruposproyForm = new GruposproyForm();
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
            $GruposproyForm->add(array(
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
                $pantalla = "gruposparticipantes";
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
            
            $us = new Grupoinvestigacion($this->dbAdapter);
            $id = (int) $this->params()->fromRoute('id', 0);
            $a = null;
            if (true) {
                $view = new ViewModel(array(
                    'form' => $GruposproyForm,
                    'titulo' => "Asignar Grupos Participantes",
                    'datos' => $us->getGrupoinvestigacion(),
                    'grupo' => $id,
                    'id_rol' => $id,
                    'id' => $id,
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
        $u = new Gruposproy($this->dbAdapter);
        $data = $this->request->getPost();
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $id = (int) $this->params()->fromRoute('id', 0);
        $resul = $u->addGruposparticipantes($id, $id2);
        if ($resul == 1) {
            $this->flashMessenger()->addMessage("Asigno el Grupo");
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/editarproyecto/index/' . $id2);
        } else {
            $this->flashMessenger()->addMessage("El Usuario ya tiene un rol asignado");
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/editarproyecto/index/' . $id2);
        }
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Gruposproy($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarGruposparticipantes($id);
        $this->flashMessenger()->addMessage("Grupo particioante eliminado con exito");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editarproyecto/index/' . $id2);
    }

    public function delAction()
    {
        $GruposproyForm = new GruposproyForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Gruposproy($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $GruposproyForm,
            'titulo' => "Eliminar registro",
            'url' => $this->getRequest()->getBaseUrl(),
            'datos' => $id,
            'id2' => $id2
        ));
        return $view;
    }
}