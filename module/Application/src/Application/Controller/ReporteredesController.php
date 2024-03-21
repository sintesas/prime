<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Reporteredes\ReporteredesForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
class ReporteredesController extends AbstractActionController
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

        $ReporteredesForm = new ReporteredesForm();
        $pt = new Agregarvalflex($this->dbAdapter);
        $identi = $auth->getStorage()->read();
        if ($identi == false && $identi == null) {
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/login/index');
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
            $pantalla = "Reporteredes";
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
            
            $valflex = new Agregarvalflex($this->dbAdapter);
            $usuario = new Usuarios($this->dbAdapter);
            $view = new ViewModel(array(
                'form' => $ReporteredesForm,
                'titulo' => "Reporte redes de investigación",
                'url' => $this->getRequest()->getBaseUrl(),
                'menu' => $dat["id_rol"]
            ));
            return $view;
        } else {
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/mensajeadministrador/index');
        }
    }
}
