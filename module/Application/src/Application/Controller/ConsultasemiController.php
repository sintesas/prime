<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Consultasemi\ConsultasemiForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Semillero;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Agregarvalfelx;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Areasemillero;
use Application\Modelo\Entity\Integrantesemillero;
use Application\Modelo\Entity\Grupossemillero;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Agregarautorsemillero;
use Application\Modelo\Entity\Articulossemillero;
use Application\Modelo\Entity\Librosemillero;
use Application\Modelo\Entity\Capitulossemillero;
use Application\Modelo\Entity\Produccionesinvsemillero;
use Application\Modelo\Entity\Documentosbibliograficossemillero;
use Application\Modelo\Entity\Reconocimientossemillero;
use Application\Modelo\Entity\Archivossemillero;
use Application\Modelo\Entity\Proyectosintsemi;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Participacioneventos;
use Application\Modelo\Entity\Identificadoressemi;
use Application\Modelo\Entity\Eventossemi;
use Application\Modelo\Entity\Trabajogradosemi;

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

class ConsultasemiController extends AbstractActionController
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
        
        $datosSemilleros = array();
      
      
        $ConsultasemiForm = new ConsultasemiForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');

        $pt = new Agregarvalflex($this->dbAdapter);
        $auth = $this->auth;
        
        $identi = $auth->getStorage()->read();
        if ($identi == false && $identi == null) {
            $identi = new \stdClass();
            $identi->id_usuario = '0';
            //return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/login/index');
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
            $pantalla = "consultasemi";
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
        
        $usua = new Usuarios($this->dbAdapter);
        $valflex = new Agregarvalflex($this->dbAdapter);
        $d = '';
        $u = new Semillero($this->dbAdapter);
        $data = array();
        if ($this->getRequest()->isPost()) {
             $data = $this->request->getPost();
            if($dat["id_rol"]==1){
                //$datosSemilleros = $u->getSemilleroinv();   
                $datosSemilleros = $u->getSemilleroinvById($data, $identi->id_usuario);  
            }else{
                $datosSemilleros = $u->getSemilleroinvById($data, $identi->id_usuario);  
            }
        }
        $areas = new Areasemillero($this->dbAdapter);
        $usuario = new Usuarios($this->dbAdapter);
        $vf = new Agregarvalflex($this->dbAdapter);
        $integrantessemillero = new Integrantesemillero($this->dbAdapter);
        $grupostotal = new Grupoinvestigacion($this->dbAdapter);
        $grupossemillero = new Grupossemillero($this->dbAdapter);
        $articulos = new Articulossemillero($this->dbAdapter);
        $autoressemillero = new Agregarautorsemillero($this->dbAdapter);
        $libros = new Librosemillero($this->dbAdapter);
        $capitulos = new Capitulossemillero($this->dbAdapter);
        $produccionesinv = new Produccionesinvsemillero($this->dbAdapter);
        $documentosbliograficos = new Documentosbibliograficossemillero($this->dbAdapter);    
        $Archivossemillero = new Archivossemillero($this->dbAdapter); 
        $reconocimientos = new Reconocimientossemillero($this->dbAdapter);
        $proyectos = new Proyectos($this->dbAdapter);
        $tablaequipo = new Tablaequipop($this->dbAdapter);
        $proyectosint = new Proyectosintsemi($this->dbAdapter);
        $participacioneventos = new Participacioneventos($this->dbAdapter); 
        $identificadores = new Identificadoressemi($this->dbAdapter); 
        $eventos = new Eventossemi($this->dbAdapter); 
        $formacion = new Trabajogradosemi($this->dbAdapter);
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
            'form' => $ConsultasemiForm,
            'titulo' => "Consulta Semilleros / Otros procesos de formación",
            'url' => $this->getRequest()->getBaseUrl(),
            'datosSemilleros' => $datosSemilleros,
            'd_user' => $usua->getUsuarios($d),
            'd_val' => $valflex->getValoresf(),
            'id_user' => $identi->id_usuario,
            'menu' => $dat["id_rol"],
            'dataConsulta' => $data,
            'areas' => $areas->getAreasemillerot(),
            'vf' => $vf->getArrayvalores(24),
            'vf2' => $vf->getValoresf(),
            'vf3' => $vf->getArrayvalores(55),
            'usuarios' => $usuario->getUsuarios(""),
            'integrantessemillero' => $integrantessemillero->getIntegrantesemillerot(),
            'grupostotal' => $grupostotal->filtroRedes(""),
            'grupossemillero' => $grupossemillero->getGrupossemillerot(),
            'autoressemillero' => $autoressemillero->getAutoressemillerot(),
            'articulos' => $articulos->getArticulost(),
            'libros' => $libros->getLibrost(),
            'capitulos' => $capitulos->getCapitulot(),
            'produccionesinv' => $produccionesinv->getProduccioninvsemillerot(),
            'documentosbliograficos' => $documentosbliograficos->getDocumentosbibliograficossemillerot(),
            'reconocimientos' => $reconocimientos->getReconocimientossemillerot(),
            'archivossemillero' => $Archivossemillero->getArchivossemillerot(),
            'vf' => $valflex->getArrayvalores(24),
            'proyectosint' =>  $proyectosint->getProyectosi(),
            'proyectos' =>  $proyectos->getProyectoh(),
            'tablaequipo' =>  $tablaequipo->getTablaequipot(),
            'participacioneventos' =>  $participacioneventos->getParticipacioneventost(),
            'identificadores' => $identificadores->getIdentificadoressemit(),
            'eventos' => $eventos->getEventossemit(),
            'formacion' => $formacion->getTrabajogradosemit(),
            'semilleros' => $semillero->getSemilleroinv(),

            'dataDocumentos' => $Documentosvinculados->getDocumentosvinculadosByActivo("3"),
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

}