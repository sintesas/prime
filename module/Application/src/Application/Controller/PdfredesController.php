<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Pdf\PdfredesForm;
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
use Application\Modelo\Entity\Redinvestigacion;
use Application\Modelo\Entity\Integrantesred;
use Application\Modelo\Entity\Gruposred;

class PdfredesController extends AbstractActionController
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
        
        $pdf = new PdfModel();
        $pdf->setOption('filename', 'redes.pdf');
        $pdf->setOption('paperSize', 'a4');
        $pdf->setOption('paperOrientation', 'landscape');
        $datos = new Redinvestigacion($this->dbAdapter);
        $pt = new Agregarvalflex($this->dbAdapter);
        $intSemi = new Integrantesred($this->dbAdapter);   
        $gruposSemi = new Gruposred($this->dbAdapter);
        $grupoinv = new Grupoinvestigacion($this->dbAdapter);

        $pdf->setVariables(array(
            'titulo' => "Consulta Grupos Investigación",
            'datos' => $datos->getRedinv(),
            'url' => $this->getRequest()->getBaseUrl(),
            'usuarios' => $usuario->getArrayusuarios(),
            'valflex' => $pt->getValoresf(),
            'intSemi' => $intSemi->getIntegrantesredi(),
            'gruposSemi' => $gruposSemi->getGruposredt(),
            'grupoinv' => $grupoinv->getGrupoinvestigacion(),
            'menu' => $dat["id_rol"]
        ));        
        return $pdf;
    }
}
