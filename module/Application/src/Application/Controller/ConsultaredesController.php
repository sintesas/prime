<?php

namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Consultaredes\ConsultaredesForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Redinvestigacion;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Agregarvalfelx;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Equipodirectivo;
use Application\Modelo\Entity\Contactored;
use Application\Modelo\Entity\Integrantesred;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Gruposred;
use Application\Modelo\Entity\Serviciosred;
use Application\Modelo\Entity\Articulosred;
use Application\Modelo\Entity\Agregarautorred;
use Application\Modelo\Entity\Librored;
use Application\Modelo\Entity\Capitulosred;
use Application\Modelo\Entity\Documentosbibliograficosred;
use Application\Modelo\Entity\Produccionesinvred;
use Application\Modelo\Entity\Archivosred;
use Application\Modelo\Entity\Proyectored;
use Application\Modelo\Entity\Proyectosintred;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Participacioneventosred;
use Application\Modelo\Entity\Identificadoresred;
use Application\Modelo\Entity\Eventosred;
use Application\Modelo\Entity\Trabajogradored;
use Application\Modelo\Entity\Semillero;

use Application\Modelo\Entity\Documentosvinculados;
use Application\Modelo\Entity\Articuloshv;
use Application\Modelo\Entity\Libroshv;
use Application\Modelo\Entity\Capitulosusuario;
use Application\Modelo\Entity\Agregarautorusuario;
use Application\Modelo\Entity\Otrasproduccioneshv;
use Application\Modelo\Entity\Bibliograficoshv;
use Application\Modelo\Entity\Proyectosexthv;
use Application\Modelo\Entity\Proyectosintusua;
use Application\Modelo\Entity\Autores;

class ConsultaredesController extends AbstractActionController
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
        
        $ConsultaredesForm = new ConsultaredesForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Redinvestigacion($this->dbAdapter);
        $pt = new Agregarvalflex($this->dbAdapter);
        $auth = $this->auth;
        
        $identi = $auth->getStorage()->read();
        if ($identi == false && $identi == null) {
            $identi = new \stdClass();
            $identi->id_usuario = '0';
            //return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/login/index');
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
            $pantalla = "consultaredes";
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
        
        $datosRedes = array();
        $data = array();
        if ($this->getRequest()->isPost()) {
             $data = $this->request->getPost();
            if($dat["id_rol"]==1){
                //$datosRedes = $u->getSemilleroinv();   
                $datosRedes = $u->getRedinvById($data, $identi->id_usuario);  
            }else{
                $datosRedes = $u->getRedinvById($data, $identi->id_usuario);  
            }
        }

        $usua = new Usuarios($this->dbAdapter);
        $valflex = new Agregarvalflex($this->dbAdapter);
        $d = '';

        $equipodirectivo = new Equipodirectivo($this->dbAdapter);
        $contactored = new Contactored($this->dbAdapter);
        $integrantesred = new Integrantesred($this->dbAdapter);
        $grupostotal = new Grupoinvestigacion($this->dbAdapter);
        $gruposred = new Gruposred($this->dbAdapter);
        $serviciosred = new Serviciosred($this->dbAdapter);
        $articulos = new Articulosred($this->dbAdapter);
        $autoresred = new Agregarautorred($this->dbAdapter);
        $libros = new Librored($this->dbAdapter);
        $capitulos = new Capitulosred($this->dbAdapter);
        $documentosbliograficos = new Documentosbibliograficosred($this->dbAdapter);
        $produccionesinv = new Produccionesinvred($this->dbAdapter);
        $archivosred = new Archivosred($this->dbAdapter);
        $proyectored = new Proyectored($this->dbAdapter);
        $proyectos = new Proyectos($this->dbAdapter);
        $tablaequipo = new Tablaequipop($this->dbAdapter);
        $proyectosint = new Proyectosintred($this->dbAdapter); 
        $participacioneventos = new Participacioneventosred($this->dbAdapter); 
        $identificadores = new Identificadoresred($this->dbAdapter); 
        $eventos = new Eventosred($this->dbAdapter); 
        $formacion = new Trabajogradored($this->dbAdapter);
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
        $auto = new Autores($this->dbAdapter);

        $view = new ViewModel(array(
            'form' => $ConsultaredesForm,
            'titulo' => "Consulta Redes de investigación",
            'url' => $this->getRequest()->getBaseUrl(),
            'datosredes' => $datosRedes,
            'd_user' => $usua->getUsuarios($d),
            'd_val' => $valflex->getValoresf(),
            'id_user' => $identi->id_usuario,
            'menu' => $dat["id_rol"],
            'dataConsulta' => $data,
            'equipodirectivo' => $equipodirectivo->getEquipodirectivot(),
            'contactored' => $contactored->getContactoredt(),
            'integrantesred' => $integrantesred->getIntegrantesredi(),
            'usuarios' => $usua->getUsuarios(""),
            'grupostotal' => $grupostotal->filtroRedes(""),
            'gruposred' => $gruposred->getGruposredt(),
            'serviciosred' => $serviciosred->getServiciosredt(),
            'articulos' => $articulos->getArticulost(),
            'autoresred' => $autoresred->getAgregarautorredt(),
            'libros' => $libros->getLibrost(),
            'capitulos' => $capitulos->getCapitulot(),
            'produccionesinv' => $produccionesinv->getProduccioninvredt(),
            'documentosbliograficos' => $documentosbliograficos->getDocumentosbibliograficosredt(),
            'archivosred' => $archivosred->getArchivosredt(),
            'proyectored' => $proyectored->getProyectoredt(),
            'proyectosint' =>  $proyectosint->getProyectosi(),
            'proyectos' =>  $proyectos->getProyectoh(),
            'tablaequipo' =>  $tablaequipo->getTablaequipot(),
            'vf' => $valflex->getArrayvalores(24),
            'participacioneventos' =>  $participacioneventos->getParticipacioneventosredt(),
            'identificadores' => $identificadores->getIdentificadoresredt(),
            'eventos' => $eventos->getEventosredt(),
            'formacion' => $formacion->getTrabajogradoredt(),
            'semilleros' => $semillero->getSemilleroinv(),

            'dataDocumentos' => $Documentosvinculados->getDocumentosvinculadosByActivo("2"),
            'Articuloshv' => $Articuloshv->getArticuloshvt(),
            'libroshv' => $libroshv->getLibroshvt(),
            'capituloshv' => $Capitulosusuario->getCapitulot(),
            'autoresusuario' => $autoresusuario->getAgregarautorusuariot(),
            'Otrasproduccioneshv' => $Otrasproduccioneshv->getOtrasproduccioneshvt(),
            'Bibliograficoshv' => $Bibliograficoshv->getBibliograficost(),
            'Proyectosexthv' => $Proyectosexthv->getProyectosexthvt(),
            'Proyectosintusua' =>  $Proyectosintusua->getProyectosi(),
            'auto' => $auto->getAutoresi()
        ));
        return $view;
       
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Redinvestigacion($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $u->eliminarGrupoinv($id);
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/grupoinv/index');
    }
}