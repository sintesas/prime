<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Cambiarcontrausuario\CambiarcontrausuarioForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;

class CambiarcontrausuarioController extends AbstractActionController
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
            $u = new Usuarios($this->dbAdapter);
            $data = $this->request->getPost();
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            $identi->id_usuario;
            $id = (int) $this->params()->fromRoute('id', 0);
            $resultado = $u->Cambiarcontrausuario($data, $identi->usuario, $id);
            if ($resultado == 1) {
                $this->flashMessenger()->addMessage("La contrasena se cambio con exito");
                // exit;
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/Cambiarcontrausuario/index/' . $id);
            } else {
                $this->flashMessenger()->addMessage("La contrasena no se cambio con exito");
                // exit;
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/Cambiarcontrausuario/index/' . $id);
            }
        } else {
            
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Usuarios($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            $identi->id_usuario;
            
            $CambiarcontrausuarioForm = new CambiarcontrausuarioForm();
            
            $vf = new Usuarios($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayusuarios() as $dat) {
                $op = array(
                    $dat["id_usuario"] => $dat["primer_nombre"] . $dat["primer_apellido"]
                );
                $opciones = $opciones + $op;
            }
            $CambiarcontrausuarioForm->add(array(
                'name' => 'id_usuario',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Usuario: ',
                    'value_options' => $opciones
                )
            ));
            
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
                $pantalla = "cambiarcontrausuario";
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
            
            if (true) {
                $id = (int) $this->params()->fromRoute('id', 0);
                $view = new ViewModel(array(
                    'form' => $CambiarcontrausuarioForm,
                    'titulo' => "Cambiar contrase&ntilde;a usuario",
                    'menu' => $dat["id_rol"],
                    'idusuario' => $identi->id_usuario,
                    'nombre' => $u->getUsuarios($id)
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/mensajeadministrador/index');
            }
        }
    }
}
