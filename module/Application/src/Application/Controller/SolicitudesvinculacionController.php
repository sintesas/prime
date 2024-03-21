<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Index\SolicitudesvinculacionForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Documentosvinculados;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Redinvestigacion;
use Application\Modelo\Entity\Semillero;
use Application\Modelo\Entity\Lineashv;
use Application\Modelo\Entity\Articuloshv;
use Application\Modelo\Entity\Autores;
use Application\Modelo\Entity\Libroshv;
use Application\Modelo\Entity\Capitulosusuario;
use Application\Modelo\Entity\Agregarautorusuario;
use Application\Modelo\Entity\Otrasproduccioneshv;
use Application\Modelo\Entity\Bibliograficoshv;
use Application\Modelo\Entity\Proyectosexthv;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Proyectosintusua;
use Application\Modelo\Entity\Tablaequipop;

class SolicitudesvinculacionController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     		//Cargamos el servicio de autenticacien el constructor
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

        $id = (int) $this->params()->fromRoute('id',0);
        $SolicitudesvinculacionForm = new SolicitudesvinculacionForm();
        $Documentosvinculados = new Documentosvinculados($this->dbAdapter);
        $Grupoinvestigacion = new Grupoinvestigacion($this->dbAdapter);
        $Redinvestigacion = new Redinvestigacion($this->dbAdapter);
        $Semillero = new Semillero($this->dbAdapter);
        $Usuarios = new Usuarios($this->dbAdapter);
        $Articuloshv = new Articuloshv($this->dbAdapter);
        $vflex = new Agregarvalflex($this->dbAdapter);
        $auto = new Autores($this->dbAdapter);
        $libroshv = new Libroshv($this->dbAdapter);
        $Capitulosusuario = new Capitulosusuario($this->dbAdapter);
        $autoresusuario = new Agregarautorusuario($this->dbAdapter);
        $Otrasproduccioneshv = new Otrasproduccioneshv($this->dbAdapter);
        $Bibliograficoshv = new Bibliograficoshv($this->dbAdapter);
        $Proyectosexthv = new Proyectosexthv($this->dbAdapter);
        $Proyectosintusua = new Proyectosintusua($this->dbAdapter);     
        $proyectos = new Proyectos($this->dbAdapter);
        $Tablaequipop = new Tablaequipop($this->dbAdapter);
        $rolusuario = new Rolesusuario($this->dbAdapter);
        $lineashv = new Lineashv($this->dbAdapter);

        $view = new ViewModel(array(
        	'form'=>$SolicitudesvinculacionForm,
			'titulo'=>"Solicitudes de vinculación de productos",
			'url'=>$this->getRequest()->getBaseUrl(),
			'dataDocumentos' => $Documentosvinculados->getDocumentosvinculadosByUsuario($identi, $id),
			'Grupoinvestigacion' => $Grupoinvestigacion->getGrupoinvestigacion(), 
            'Redinvestigacion' => $Redinvestigacion->getRedinv(),
            'Semillero' => $Semillero->getSemilleroinv(),
			'usuarios' => $Usuarios->getArrayusuarios(),
			'Articuloshv' => $Articuloshv->getArticuloshv($identi),
			'vflex' => $vflex->getValoresf(),
			'auto' => $auto->getAutores($identi),
			'libros' => $libroshv->getLibroshv($identi),
			'capitulos' => $Capitulosusuario->getCapitulousuario($identi),
			'autoresusuario' => $autoresusuario->getAgregarautorusuario($identi),
            'lineashv' => $lineashv->getLineashv($identi),
			'Otrasproduccioneshv' => $Otrasproduccioneshv->getOtrasproduccioneshv($identi),
			'Bibliograficoshv' => $Bibliograficoshv->getBibliograficos($identi),
			'Proyectosexthv' => $Proyectosexthv->getProyectosexthv($identi),
			'Proyectosintusua' =>  $Proyectosintusua->getProyectos($identi),
            'proyectos' =>  $proyectos->getProyectoh(),
            'Tablaequipop' =>  $Tablaequipop->getTablaequipot(),
			'menu'=> $rolusuario->verificarRolusuario($identi)[0],
            'id' => $id
		));
		return $view;		
    }

    public function aprobarAction(){
    	$this->dbAdapter=$this->getServiceLocator()->get('db1');
        $auth = $this->auth;
    	$Documentosvinculados = new Documentosvinculados($this->dbAdapter);
    	$id = (int) $this->params()->fromRoute('id',0);
		$id2 = $this->params()->fromRoute('id2',0);
        $id3 = $this->params()->fromRoute('id3',0);
		$Documentosvinculados->updateDocumentosvinculados($id, $id2);
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/solicitudesvinculacion/index/'.$id3);
    }


}