<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editargrupoinv\hgrupoinvestigacionForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Grupoinvestigacion;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Lineas;
use Application\Modelo\Entity\Libros;
use Application\Modelo\Entity\Proyectosext;
use Application\Modelo\Entity\Asociaciones;
use Application\Modelo\Entity\Semilleros;
use Application\Modelo\Entity\Instituciones;
use Application\Modelo\Entity\Capitulosgrupo;
use Application\Modelo\Entity\Agregarautorgrupo;
use Application\Modelo\Entity\Otrasproducciones;
use Application\Modelo\Entity\Archivos;
use Application\Modelo\Entity\Redinvestigacion;
use Application\Modelo\Entity\Semillero;
use Application\Modelo\Entity\Integrantes;
use Application\Modelo\Entity\Reconocimientos;
use Application\Modelo\Entity\Redes;
use Application\Modelo\Entity\Articulos;
use Application\Modelo\Entity\Bibliograficos;
use Application\Modelo\Entity\Autores;
use Application\Modelo\Entity\Gruposrel;
use Application\Modelo\Entity\Proyectosint;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Identificadoresgru;
use Application\Modelo\Entity\Eventosgru;
use Application\Modelo\Entity\Trabajogradogru;

use Application\Modelo\Entity\Documentosvinculados;
use Application\Modelo\Entity\Articuloshv;
use Application\Modelo\Entity\Libroshv;
use Application\Modelo\Entity\Capitulosusuario;
use Application\Modelo\Entity\Agregarautorusuario;
use Application\Modelo\Entity\Otrasproduccioneshv;
use Application\Modelo\Entity\Bibliograficoshv;
use Application\Modelo\Entity\Proyectosexthv;
use Application\Modelo\Entity\Proyectosintusua;
use Application\Modelo\Entity\Lineashv;

class hgrupoinvestigacionController extends AbstractActionController
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
        if ($auth->getStorage()->read() == false && $auth->getStorage()->read() == null) {
            $identi = new \stdClass();
                $identi->id_usuario = '0';
        }
        else{
            $identi = print_r($auth->getStorage()->read()->id_usuario,true);
            $resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,get_class());
            if($resultadoPermiso==0){
                $this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
            }
        }

		//Creacion adaptador de conexion, objeto de datos, auditoria
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$not = new Grupoinvestigacion($this->dbAdapter);
		$auth = $this->auth;

		//verifica si esta conectado
		$identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
			$identi = new \stdClass();
        	$identi->id_usuario = '0';
			//return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
		

		$rol = new Roles($this->dbAdapter);
		$rolusuario = new Rolesusuario($this->dbAdapter);
	 	$rolusuario->verificarRolusuario($identi->id_usuario);
		foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
			$dat["id_rol"];
		}

		//obtiene la informacion de las pantallas
		$id = (int) $this->params()->fromRoute('id', 0);
		$hgrupoinvestigacionForm = new hgrupoinvestigacionForm();   
	    
		$libros = new Libros($this->dbAdapter);
		$lineas = new Lineas($this->dbAdapter);
		$int = new Integrantes($this->dbAdapter);
		$red = new Redes($this->dbAdapter);
		$rec = new Reconocimientos($this->dbAdapter);
		$art = new Articulos($this->dbAdapter);
		$biblio = new Bibliograficos($this->dbAdapter);
		$auto = new Autores($this->dbAdapter);
		$gr = new Gruposrel($this->dbAdapter);
		$usuario = new Usuarios($this->dbAdapter);
		$as = new Asociaciones($this->dbAdapter);
		$sem = new Semilleros($this->dbAdapter);
        $inst = new Instituciones($this->dbAdapter);
		$capitulosgrupo = new Capitulosgrupo($this->dbAdapter);
		$autoresgrupo = new Agregarautorgrupo($this->dbAdapter);
		$otras = new Otrasproducciones($this->dbAdapter);
		$auto = new Autores($this->dbAdapter);
		$grupostotal = new Grupoinvestigacion($this->dbAdapter);
		$arch = new Archivos($this->dbAdapter);
		$redinvestigacion = new Redinvestigacion($this->dbAdapter);
		$semillerosinvestigacion = new Semillero($this->dbAdapter);
		$usua = new Usuarios($this->dbAdapter);
		$proyext = new Proyectosext($this->dbAdapter);
		$valflex = new Agregarvalflex($this->dbAdapter);
		$proyectos = new Proyectos($this->dbAdapter);
        $tablaequipo = new Tablaequipop($this->dbAdapter);
        $proyectosint = new Proyectosint($this->dbAdapter); 
		$identificadores = new Identificadoresgru($this->dbAdapter); 
        $eventos = new Eventosgru($this->dbAdapter); 
        $formacion = new Trabajogradogru($this->dbAdapter);
        $semillero = new Semillero($this->dbAdapter); 

        $Documentosvinculados = new Documentosvinculados($this->dbAdapter);  
        $Articuloshv = new Articuloshv($this->dbAdapter);   
        $libroshv = new Libroshv($this->dbAdapter);
        $Capitulosusuario = new Capitulosusuario($this->dbAdapter);
        $autoresusuario = new Agregarautorusuario($this->dbAdapter);
        $Otrasproduccioneshv = new Otrasproduccioneshv($this->dbAdapter);
        $Bibliograficoshv = new Bibliograficoshv($this->dbAdapter);
        $Proyectosexthv = new Proyectosexthv($this->dbAdapter);
        $Proyectosintusua = new Proyectosintusua($this->dbAdapter);
        $LineasHv = new Lineashv($this->dbAdapter);

		//redirige a la pantalla de inicio del controlador
		$view = new ViewModel(
			array(
				'form'=>$hgrupoinvestigacionForm,
				'titulo'=>"Consulta grupos de investigación", 
				'idgrupo' => $id,
				'datos'=>$not->getGrupoinvid($id),
				'url'=>$this->getRequest()->getBaseUrl(),
				'datos2'=>$lineas->getLineas($id),
				'datos3'=>$libros->getLibros($id),
				'datos4'=>$proyext->getProyectosext($id),
				'datos5'=>$int->getIntegrantes($id),
				'redes'=>$red->getRedes($id),
				'datos7' => $rec->getReconocimientos($id),
				'datos8' => $art->getArticulos($id),
				'datos9' => $biblio->getBibliograficos($id),
				'autores' => $auto->getAutores($id),
				'valflex'=>$valflex->getValoresf(),
				'd_user' => $usua->getUsuarios(''),
				'semilleros' => $sem->getSemilleros($id),
				'instituciones' => $inst->getInstituciones($id),
				'capitulos' => $capitulosgrupo->getCapitulogrupo($id),
				'grupos' => $gr->getGruposrel($id),
				'otras' => $otras->getOtrasproducciones($id),
				'usuarios' => $usuario->getArrayusuarios(),		
				'autoresgrupo' => $autoresgrupo->getAgregarautorgrupo($id),
				'asociaciones' => $as->getAsociaciones($id),
				'auto' => $auto->getAutores($id),
				'grupostotal' => $grupostotal->getGrupoinvestigacion(),	
				'consulta'=>1,
				'arch' => $arch->getArchivos($id),
				'menu'=>$dat["id_rol"],
				'redinvestigacion' => $redinvestigacion->getRedinv(),
				'proyectosint' =>  $proyectosint->getProyectos($id),
                'proyectos' =>  $proyectos->getProyectoh(),
                'tablaequipo' =>  $tablaequipo->getTablaequipot(),
				'semillerosinvestigacion' => $semillerosinvestigacion->getSemilleroinv(),
				'identificadores' => $identificadores->getIdentificadoresgru($id),
            	'eventos' => $eventos->getEventosgru($id),
            	'formacion' => $formacion->getTrabajogradogru($id),

				'dataDocumentos' => $Documentosvinculados->getDocumentosvinculadosByActivo("1"),
                'Articuloshv' => $Articuloshv->getArticuloshvt(),
                'libroshv' => $libroshv->getLibroshvt(),
                'capituloshv' => $Capitulosusuario->getCapitulot(),
                'autoresusuario' => $autoresusuario->getAgregarautorusuariot(),
                'Otrasproduccioneshv' => $Otrasproduccioneshv->getOtrasproduccioneshvt(),
                'Bibliograficoshv' => $Bibliograficoshv->getBibliograficost(),
                'Proyectosexthv' => $Proyectosexthv->getProyectosexthvt(),
                'Proyectosintusua' =>  $Proyectosintusua->getProyectosi(),
                'LineasHv' => $LineasHv->getLineashvt()
			)
		);
		return $view;		
    }
}
