<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Gestionarunitaria\GestionarunitariaForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Solicitudes;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;

class GestionarunitariaController extends AbstractActionController
{

    public $dbAdapter;

    private $auth;

    public function __construct()
    {
        // Cargamos el servicio de autenticación en el constructor
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
            $u = new Solicitudes($this->dbAdapter);
            $ua = new Usuarios($this->dbAdapter);
            $vf = new Agregarvalflex($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
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
            $rtn['success'] = $upload->receive();
            
            $files = $upload->getFileInfo();
            foreach ($files as $f) {
                $archi = $f["name"];
            }
            $data = $this->request->getPost();
            $id = (int) $this->params()->fromRoute('id', 0);
            
            $dat = $u->getSolicitudesid($id);
            foreach ($dat as $d) {
                $id_user = $d["usuario_crea"];
            }
            
            $usua = $ua->getArrayusuariosid($id_user);
            
            $u->updateSolicitud($id, $data, $identi->id_usuario, $usua["email"], $archi, $vf->getValoresf());
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/gestionarsolicitudes/index');
        } else {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Solicitudes($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            $GestionarunitariaForm = new GestionarunitariaForm();
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
                $pantalla = "gestionarunitaria";
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
                    'form' => $GestionarunitariaForm,
                    'titulo' => "Gestionar Solicitud Unitaria",
                    'datos' => $u->getSolicitudesid($id),
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
