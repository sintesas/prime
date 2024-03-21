<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editargrupoinv\hvinvestigadorForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Grupoinvestigacion;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Formacionacahv;
use Application\Modelo\Entity\Formacioncomhv;
use Application\Modelo\Entity\Experiencialabhv;
use Application\Modelo\Entity\Integrantes;
use Application\Modelo\Entity\Areashv;
use Application\Modelo\Entity\Foro;
use Application\Modelo\Entity\Idiomashv;
use Application\Modelo\Entity\Articuloshv;
use Application\Modelo\Entity\Lineashv;
use Application\Modelo\Entity\Libroshv;
use Application\Modelo\Entity\Proyectosexthv;
use Application\Modelo\Entity\Otrasproduccioneshv;
use Application\Modelo\Entity\Autores;
use Application\Modelo\Entity\Capitulosusuario;
use Application\Modelo\Entity\Agregarautorusuario;
use Application\Modelo\Entity\Actividadeshv;
use Application\Modelo\Entity\Bibliograficoshv;
use Application\Modelo\Entity\Proyectosintusua;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Documentosvinculados;
use Application\Modelo\Entity\Identificadoresusu;
use Application\Modelo\Entity\Eventosusu;
use Application\Modelo\Entity\Trabajogradousu;
use Application\Modelo\Entity\Semillero;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class hvinvestigadorController extends AbstractActionController
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
		$us = new Usuarios($this->dbAdapter);
		$auth = $this->auth;

		//verifica si esta conectado
		$identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
			$identi = new \stdClass();
        	$identi->id_usuario = '0';
			//return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
		
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$id3 = (int) $this->params()->fromRoute('id3',0);
		$id4 = (int) $this->params()->fromRoute('id4',0);
		$rol = new Roles($this->dbAdapter);
		$rolusuario = new Rolesusuario($this->dbAdapter);
	 	$rolusuario->verificarRolusuario($identi->id_usuario);
		foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
			$dat["id_rol"];
		}

		//obtiene la informacion de las pantallas
		$data = $this->request->getPost();
		$hvinvestigadorForm = new hvinvestigadorForm();

		//adiciona la noticia
		$inte = new Integrantes($this->dbAdapter);
		$Formaaca = new Formacionacahv($this->dbAdapter);
		$Formacom = new Formacioncomhv($this->dbAdapter);
		$Explab = new Experiencialabhv($this->dbAdapter);
		$Areas = new Areashv($this->dbAdapter);
		$Idioma = new Idiomashv($this->dbAdapter);
		$Lineas = new Lineashv($this->dbAdapter);
		$Proyext = new Proyectosexthv($this->dbAdapter);
		$Otras = new Otrasproduccioneshv($this->dbAdapter);
		$usua  = new Usuarios($this->dbAdapter);
		$foro  = new Foro($this->dbAdapter);
		$art  = new Articuloshv($this->dbAdapter);
		$lib = new Libroshv($this->dbAdapter);
		$vf = new Agregarvalflex($this->dbAdapter);
		$auto = new Autores($this->dbAdapter);
        $capitulosgrupo = new Capitulosusuario($this->dbAdapter);
        $autoresgrupo = new Agregarautorusuario($this->dbAdapter);
        $actividad = new Actividadeshv($this->dbAdapter);
        $biblio = new Bibliograficoshv($this->dbAdapter);
        $proyectos = new Proyectos($this->dbAdapter);
        $tablaequipo = new Tablaequipop($this->dbAdapter);
        $proyectosint = new Proyectosintusua($this->dbAdapter);
        $Documentosvinculados = new Documentosvinculados($this->dbAdapter);
        $gu = new Grupoinvestigacion($this->dbAdapter);

        $identificadores = new Identificadoresusu($this->dbAdapter); 
        $eventos = new Eventosusu($this->dbAdapter); 
        $formacion = new Trabajogradousu($this->dbAdapter);
        $semillero = new Semillero($this->dbAdapter); 
		//redirige a la pantalla de inicio del controlador
		$tittle="";
		if($id2=="1"){
			$tittle="Hoja de vida de investigadores";
		}else{
			$tittle="Evaluadores";
		}
		$view = new ViewModel(array('form'=>$hvinvestigadorForm,
			'titulo'=>$tittle, 
			'datos'=>$us->getUsuarioeditar($id),
			'url'=>$this->getRequest()->getBaseUrl(),
			'usua'=>$usua->getArrayusuarios(),
			'art'=>$art->getArticuloshvt(),
			'foro'=>$foro->getForo(),
			'vf'=>$vf->getValoresf(),
			'formaaca'=>$Formaaca->getFormacionacahvt(),
			'formacom'=>$Formacom->getFormacioncomhvt(),
			'explab'=>$Explab-> getExperiencialabhvt(),
			'areas'=>$Areas->getAreashvt(),
			'lib'=>$lib->getLibroshvt(),
			'idioma'=>$Idioma->getIdiomashvt(),
			'linea'=>$Lineas->getLineashvt(),
			'consulta'=>1,
			'proyext'=>$Proyext->getProyectosexthvt(),
			'biblio' => $biblio->getBibliograficost(),
			'actividad' => $actividad->getActividadeshvt(),
			'auto' => $auto->getAutoresi(),
			'otras'=>$Otras->getOtrasproduccioneshvt(),
			'proyectosint' =>  $proyectosint->getProyectosi(),
            'proyectos' =>  $proyectos->getProyectoh(),
            'tablaequipo' =>  $tablaequipo->getTablaequipot(),
        	'autoresgrupo' => $autoresgrupo->getAgregarautorusuariot(),
			'capitulos' => $capitulosgrupo->getCapitulot(),
			'id2' => $id2,
			'id3' => $id3,
			'identificadores' => $identificadores->getIdentificadoresusu($id),
            'eventos' => $eventos->getEventosusu($id),
            'formacion' => $formacion->getTrabajogradousu($id),
            'semilleros' => $semillero->getSemilleroinv(),
			'documentosvinculados' => $Documentosvinculados->getDocumentosvinculadosByGrupo($id,$id3,$id4),
			'id_usuario' => $identi->id_usuario,
			'grupostotal' => $gu->getGrupoinvestigacion(),
			'integrantes' => $inte->getIntegrantesi(),
			'id4' => $id4,
			'menu'=>$dat["id_rol"]));
		return $view;
    }

    public function vincularAction(){
    	$this->dbAdapter=$this->getServiceLocator()->get('db1');
        $auth = $this->auth;
    	$Documentosvinculados = new Documentosvinculados($this->dbAdapter);
    	$tipo_docu = $this->params()->fromRoute('id',0);
		$id_usuario = (int) $this->params()->fromRoute('id2',0);
		$id_grupo = (int) $this->params()->fromRoute('id3',0);
		$id_docu = (int) $this->params()->fromRoute('id4',0);
		$modulo = (int) $this->params()->fromRoute('id5',0);
		$id_usuario_solicitud = print_r($auth->getStorage()->read()->id_usuario,true);

		function get_real_ip()
		{
			if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				return $_SERVER["HTTP_CLIENT_IP"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_X_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
				return $_SERVER["HTTP_X_FORWARDED"];
			} elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_FORWARDED"])) {
				return $_SERVER["HTTP_FORWARDED"];
			} else {
				return $_SERVER["REMOTE_ADDR"];
			}
		}

		$auth = $this->auth;
		$identi=$auth->getStorage()->read();

		$au = new Auditoria($this->dbAdapter);
		$ad = new Auditoriadet($this->dbAdapter);

		$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
		$evento = 'Creación de consulta evaluador : (mgc_coinvestigadores)';
		$ad->addAuditoriadet($evento, $resul);

		$Documentosvinculados->addDocumentosvinculados($tipo_docu, $id_usuario, $id_grupo, $id_docu, $id_usuario_solicitud, $modulo);
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/hvinvestigador/index/'.$id_usuario.'/1/'.$id_grupo.'/'.$modulo);
    }

    public function cancelarvinculacionAction(){
    	$this->dbAdapter=$this->getServiceLocator()->get('db1');
        $auth = $this->auth;
    	$Documentosvinculados = new Documentosvinculados($this->dbAdapter);
    	$id_vincu = (int) $this->params()->fromRoute('id',0);
    	$id2 = (int) $this->params()->fromRoute('id2',0);
    	$id3 = (int) $this->params()->fromRoute('id3',0);
    	$id4 = (int) $this->params()->fromRoute('id4',0);

		function get_real_ip()
		{
			if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				return $_SERVER["HTTP_CLIENT_IP"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_X_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
				return $_SERVER["HTTP_X_FORWARDED"];
			} elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_FORWARDED"])) {
				return $_SERVER["HTTP_FORWARDED"];
			} else {
				return $_SERVER["REMOTE_ADDR"];
			}
		}

		$auth = $this->auth;
		$identi=$auth->getStorage()->read();

		$au = new Auditoria($this->dbAdapter);
		$ad = new Auditoriadet($this->dbAdapter);

		$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
		$evento = 'Eliminación de consulta evaluador : ' . $id_vincu . ' (mgc_coinvestigadores)';
		$ad->addAuditoriadet($evento, $resul);

		$Documentosvinculados->eliminarDocumentosvinculados($id_vincu);
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/hvinvestigador/index/'.$id2.'/1/'.$id3.'/'.$id4);
    }
}
