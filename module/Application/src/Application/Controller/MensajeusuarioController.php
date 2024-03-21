<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Mensajeusuario\MensajeusuarioForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Correos;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Mail;
use Zend\Filter\StringTrim;
use Application\Usuario\UsuarioForm;

class MensajeusuarioController extends AbstractActionController
{

    private $auth;

    public $dbAdapter;

    private $usersTable;

    public function __construct()
    {
        // Cargamos el servicio de autenticacien el constructor
        $this->auth = new AuthenticationService();
    }

    public function indexAction()
    {
        # Para pantallas accesadas por el menu, debo reiniciar el navegador
        session_start();
        if(isset($_SESSION['navegador'])){unset($_SESSION['navegador']);}

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
            $c = new Correos($this->dbAdapter);
            // print_r($data);
            $mail = array();
            $arreglo = array();
            $filter = new StringTrim();
            
            $upload = new \Zend\File\Transfer\Transfer();
            $upload->setDestination('./public/images/uploads/');
            $upload->setValidators(array(
                'Size' => array(
                    'min' => 1,
                    'max' => 50000000
                )
            ));
            $rtn = array(
                'success' => null
            );
            
            if ($this->getRequest()->isPost()) {
                $files = $upload->getFileInfo();
                foreach ($files as $file => $info) {
                    if (! $upload->isUploaded($file)) {
                        print "<h3>Not Uploaded</h3>";
                        Debug::dump($file);
                        continue;
                    }
                    if (! $upload->isValid($file)) {
                        print "<h4>Not Valid</h4>";
                        Debug::dump($file);
                        continue;
                    }
                }
                
                $rtn['success'] = $upload->receive();
            }
            
            $files = $upload->getFileInfo();
            foreach ($files as $f) {
                $archi = $f["name"];
            }
            
            $u->mensajeUsuario($data, $c->getCorreos(), $archi);
            
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            // exit;
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/mensajeusuario/index');
        } else {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Usuarios($this->dbAdapter);
            $auth = $this->auth;
            $c = new Correos($this->dbAdapter);
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            $MensajeusuarioForm = new MensajeusuarioForm();
            
            // define el campo ciudad
            $vf = new Usuarios($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayusuarios() as $dat) {
                $op = array(
                    $dat["email"] => $dat["primer_nombre"]
                );
                $opciones = $opciones + $op;
            }
            
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
                $pantalla = "mensajeusuario";
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
                $view = new ViewModel(array(
                    'form' => $MensajeusuarioForm,
                    'titulo' => "Mensaje Usuarios",
                    'datos' => $c->getCorreos(),
                    'menu' => $dat["id_rol"]
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/mensajeadministrador/index');
            }
        }
    }

    public function rolesAction()
    {
        if ($this->getRequest()->isPost()) {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Usuarios($this->dbAdapter);
            $data = $this->request->getPost();
            // print_r($data);
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $rol = new Roles($this->dbAdapter);

            $arreglo = array();
            $filter = new StringTrim();
            $upload = new \Zend\File\Transfer\Transfer();
            $upload->setDestination('./public/images/uploads/');
            $upload->setValidators(array(
                'Size' => array(
                    'min' => 1,
                    'max' => 50000000
                )
            ));
            $rtn = array(
                'success' => null
            );
            
            if ($this->getRequest()->isPost()) {
                $files = $upload->getFileInfo();
                foreach ($files as $file => $info) {
                    if (! $upload->isUploaded($file)) {
                        print "<h3>Not Uploaded</h3>";
                        Debug::dump($file);
                        continue;
                    }
                    if (! $upload->isValid($file)) {
                        print "<h4>Not Valid</h4>";
                        Debug::dump($file);
                        continue;
                    }
                }
                
                $rtn['success'] = $upload->receive();
            }
            
            $files = $upload->getFileInfo();
            foreach ($files as $f) {
                $archi = $f["name"];
            }










            $resultado = $u->mensajeRoles($data, $rol->getRoles(), $rolusuario->getRolesusuario(), $u->getArrayusuarios(), $archi);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            // exit;
            if ($resultado == 1) {
                $this->flashMessenger()->addMessage("Mensaje enviado con exito");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/mensajeusuario/roles');
            } else {
                $this->flashMessenger()->addMessage("No se envio el mensaje");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/mensajeusuario/roles');
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
            $MensajeusuarioForm = new MensajeusuarioForm();
            
            // define el campo ciudad
            $vf = new Roles($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getRoles() as $dat) {
                $op = array(
                    $dat["id_rol"] => $dat["descripcion"]
                );
                $opciones = $opciones + $op;
            }
            $MensajeusuarioForm->add(array(
                'name' => 'usuario',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Roles: ',
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
                $pantalla = "mensajeusuario";
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
                $view = new ViewModel(array(
                    'form' => $MensajeusuarioForm,
                    'titulo' => "Mensaje Roles",
                    'menu' => $dat["id_rol"]
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/mensajeadministrador/index');
            }
        }
    }

    public function buscarAction()
    {
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
            $rol = new Roles($this->dbAdapter);
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $Usuarioform = new Usuarioform();
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
            }
            // exit;
            $view = new ViewModel(array(
                'form' => $Usuarioform,
                'titulo' => "Usuarios",
                'datos' => $u->filtroUsuario($data),
                'datos2' => $rolusuario->getRolesusuario(),
                'datos3' => $rol->getRoles(),
                'menu' => $dat["id_rol"]
            ));
            return $view;
        } else {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Usuarios($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $Usuarioform = new Usuarioform();
            // verificar roles
            $per = array(
                'id_rol' => ''
            );
            $dat = array(
                'id_rol' => ''
            );
            $rol = new Roles($this->dbAdapter);
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $permiso = new Permisos($this->dbAdapter);
            
            // me verifica el tipo de rol asignado al usuario
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
            }
            
            if ($dat["id_rol"] != '') {
                // me verifica el permiso sobre la pantalla
                $pantalla = "mensajeusuario";
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
            $id = (int) $this->params()->fromRoute('id', 0);
            if (true) {
                $view = new ViewModel(array(
                    'form' => $Usuarioform,
                    'titulo' => "Usuarios",
                    'datos' => $u->getUsuarios($id),
                    'datos2' => $rolusuario->getRolesusuario(),
                    'datos3' => $rol->getRoles(),
                    'menu' => $dat["id_rol"]
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/mensajeadministrador/index');
            }
        }
    }

    public function adicionarAction()
    {
        $correo = $this->params()->fromRoute('id');
        
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $c = new Correos($this->dbAdapter);
        
        $resultado = $c->addCorreos($correo);
        if ($resultado != '') {
            $this->flashMessenger()->addMessage("El correo se adiciono correctamente");
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/mensajeusuario/buscar');
        } else {
            $this->flashMessenger()->addMessage("No se adiciono el correo");
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/mensajeusuario/buscar');
        }
    }

    public function eliminarAction()
    {
        $correo = $this->params()->fromRoute('id', '');
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $c = new Correos($this->dbAdapter);
        $resultado = $c->eliminarCorreos($correo);
        if ($resultado != '') {
            $this->flashMessenger()->addMessage("Se elimino el correo");
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/mensajeusuario/index');
        } else {
            $this->flashMessenger()->addMessage("No se elimino el correo");
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/mensajeusuario/index');
        }
    }

    public function getUsersTable()
    {
        if (! $this->usersTable) {
            $this->usersTable = new TableGateway('aps_usuarios', $this->getServiceLocator()->get('db1'));
        }
        return $this->usersTable;
    }
}