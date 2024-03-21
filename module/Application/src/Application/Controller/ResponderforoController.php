<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Responderforo\ResponderforoForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Foro;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;

class ResponderforoController extends AbstractActionController
{

    private $auth;

    public $dbAdapter;

    public function __construct()
    {
        // Cargamos el servicio de autenticacien el constructor
        $this->auth = new AuthenticationService();
    }

    public function IndexAction()
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
            // Creacion adaptador de conexion, objeto de datos, auditoria
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $not = new Foro($this->dbAdapter);
            $auth = $this->auth;
            $id = (int) $this->params()->fromRoute('id', 0);
            // verifica si esta conectado
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
            
            $upload = new \Zend\File\Transfer\Transfer();
            $upload->setDestination('./public/images/uploads/');
            $upload->setValidators(array(
                'Size' => array(
                    'min' => 1,
                    'max' => 50000000
                )
            ));
            
            /*if (! $upload->isValid()) {
                $this->flashMessenger()->addMessage("El archivo cargado supera lo esperado");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/responderforo/index/' . $id);
            }
            $rtn = array(
                'success' => null
            );*/
            
            if ($this->getRequest()->isPost()) {
                
                $files = $upload->getFileInfo();
                
                $rtn['success'] = $upload->receive();
            }
            
            $files = $upload->getFileInfo();
            foreach ($files as $f) {
                $archi = $f["name"];
            }
            
            // obtiene la informacion de las pantallas
            $data = $this->request->getPost();
            $ResponderforoForm = new ResponderforoForm();
            // adiciona la noticia
            
            $not->actualizaforo($id);
            $resultado = $not->addRespuesta($data, $archi, $identi->id_usuario, $id);
            
            // redirige a la pantalla de inicio del controlador
            if ($resultado == 1) {
                $this->flashMessenger()->addMessage("Respuesta creada con exito");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/foro/ver/' . $id);
            } else {
                $this->flashMessenger()->addMessage("La creacion de la respuesta fallo");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/foro/ver/' . $ic);
            }
        } else {
            $id = (int) $this->params()->fromRoute('id', 0);
            
            $ResponderforoForm = new ResponderforoForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Foro($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $filter = new StringTrim();
            
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
                $pantalla = "responderforo";
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
                    'form' => $ResponderforoForm,
                    'titulo' => "Responder Foro",
                    'titulo2' => "Tipos Valores Existentes",
                    'url' => $this->getRequest()->getBaseUrl(),
                    'datos' => $u->getForo(),
                    'id' => $id,
                    'menu' => $dat["id_rol"]
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/mensajeadministrador/index');
            }
        }
    }
}