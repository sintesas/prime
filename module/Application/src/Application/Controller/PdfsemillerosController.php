<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Pdf\PdfsemillerosForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use DOMPDFModule\View\Model\PdfModel;
use Application\Modelo\Entity\Autores;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Semillero;
use Application\Modelo\Entity\Integrantesemillero;
use Application\Modelo\Entity\Grupossemillero;

class PdfsemillerosController extends AbstractActionController
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
        // Creacion adaptador de conexion, objeto de datos, auditoria
        $this->dbAdapter=$this->getServiceLocator()->get('db1');
        $auth = $this->auth;
        $permisos = new Permisos($this->dbAdapter);
        $identi = print_r($auth->getStorage()->read()->id_usuario,true);
        $resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,get_class());
        if($resultadoPermiso==0){
            $this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
        }
        
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
        
        $data = $this->request->getPost();
        $usuario = new Usuarios($this->dbAdapter);
        ini_set('default_charset', 'UTF-8');
         ini_set('display_errors', 0);
         ini_set('max_execution_time', 0);
     //
        $pdf = new PdfModel();
        $pdf->setOption('filename', 'semilleros.pdf');
        $pdf->setOption('paperSize', 'a4');
        $pdf->setOption('paperOrientation', 'landscape');
        $datos = new Semillero($this->dbAdapter);
        $pt = new Agregarvalflex($this->dbAdapter);
        $intSemi = new Integrantesemillero($this->dbAdapter);   
        $gruposSemi = new Grupossemillero($this->dbAdapter);
        $grupoinv = new Grupoinvestigacion($this->dbAdapter);

        $pdf->setVariables(array(
            'titulo' => "Consulta Grupos Investigación",
            'datos' => $datos->getSemilleroinv(),
            'url' => $this->getRequest()->getBaseUrl(),
            'usuarios' => $usuario->getArrayusuarios(),
            'valflex' => $pt->getValoresf(),
            'intSemi' => $intSemi->getIntegrantesemillerot(),
            'gruposSemi' => $gruposSemi->getGrupossemillerot(),
            'grupoinv' => $grupoinv->getGrupoinvestigacion(),
            'menu' => $dat["id_rol"]
        ));        
        return $pdf;
    }
}
