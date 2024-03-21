<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Excelsemilleros\ExcelsemillerosForm;
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
use DOMPDFModule\View\Model\PdfModel;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Semillero;
use Application\Modelo\Entity\Integrantesemillero;
use Application\Modelo\Entity\Grupossemillero;

class ExcelsemillerosController extends AbstractActionController
{

    private $auth;
    public $dbAdapter;

    public function __construct()
    {
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

        $ExcelsemillerosForm = new ExcelsemillerosForm();
        $pt = new Agregarvalflex($this->dbAdapter);
        $datos = new Semillero($this->dbAdapter);
        $pt = new Agregarvalflex($this->dbAdapter);
        $intSemi = new Integrantesemillero($this->dbAdapter);   
        $gruposSemi = new Grupossemillero($this->dbAdapter);
        $grupoinv = new Grupoinvestigacion($this->dbAdapter);
        $usuario = new Usuarios($this->dbAdapter);

        $view = new ViewModel(array(
            'form' => $ExcelsemillerosForm,
            'datos' => $datos->getSemilleroinv(),
            'url' => $this->getRequest()->getBaseUrl(),
            'usuarios' => $usuario->getArrayusuarios(),
            'valflex' => $pt->getValoresf(),
            'intSemi' => $intSemi->getIntegrantesemillerot(),
            'gruposSemi' => $gruposSemi->getGrupossemillerot(),
            'grupoinv' => $grupoinv->getGrupoinvestigacion()
        ));
        $view->setTerminal(true);
        return $view;
    }
}
