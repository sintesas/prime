<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Excelsemillerostodo\ExcelsemillerostodoForm;
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
use Application\Modelo\Entity\Areasemillero;
use Application\Modelo\Entity\Participacioneventos;
use Application\Modelo\Entity\Articulossemillero;
use Application\Modelo\Entity\Librosemillero;
use Application\Modelo\Entity\Capitulossemillero;
use Application\Modelo\Entity\Produccionesinvsemillero;
use Application\Modelo\Entity\Documentosbibliograficossemillero;
use Application\Modelo\Entity\Proyectosintsemi;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Reconocimientossemillero;
use Application\Modelo\Entity\Archivossemillero;
use Application\Modelo\Entity\Identificadoressemi;
use Application\Modelo\Entity\Eventossemi;
use Application\Modelo\Entity\Trabajogradosemi;

class ExcelsemillerostodoController extends AbstractActionController
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
            //$this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
            //return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
        }

        $usuario = new Usuarios($this->dbAdapter);
        $datos = new Semillero($this->dbAdapter);
        $pt = new Agregarvalflex($this->dbAdapter);
        
        if($this->getRequest()->isPost()){
            $data = $this->request->getPost();
            $ExcelsemillerostodoForm = new ExcelsemillerostodoForm();
            $dat = $datos->getSemilleroFiltro($data);
            $id = $dat[0]["id"];
            $intSemi = new Integrantesemillero($this->dbAdapter);   
            $gruposSemi = new Grupossemillero($this->dbAdapter);
            $grupoinv = new Grupoinvestigacion($this->dbAdapter);
            $areas = new Areasemillero($this->dbAdapter);
            $participacioneventos = new Participacioneventos($this->dbAdapter); 
            $articulos = new Articulossemillero($this->dbAdapter);
            $libros = new Librosemillero($this->dbAdapter);
            $capitulos = new Capitulossemillero($this->dbAdapter);
            $produccionesinv = new Produccionesinvsemillero($this->dbAdapter);
            $documentosbliograficos = new Documentosbibliograficossemillero($this->dbAdapter); 
            $proyectosint = new Proyectosintsemi($this->dbAdapter); 
            $proyectos = new Proyectos($this->dbAdapter);
            $reconocimientos = new Reconocimientossemillero($this->dbAdapter);
            $archivossemillero = new Archivossemillero($this->dbAdapter);
            $identificadores = new Identificadoressemi($this->dbAdapter);
            $divulgaciones = new Eventossemi($this->dbAdapter);
            $formaciones = new Trabajogradosemi($this->dbAdapter);
            
            $view = new ViewModel(array(
                'form' => $ExcelsemillerostodoForm,
                'datos' => $datos->getSemilleroFiltro($data),
                'url' => $this->getRequest()->getBaseUrl(),
                'usuarios' => $usuario->getArrayusuarios(),
                'valflex' => $pt->getValoresf(),
                'intSemi' => $intSemi->getIntegrantesemillerot(),
                'gruposSemi' => $gruposSemi->getGrupossemillerot(),
                'grupoinv' => $grupoinv->getGrupoinvestigacion(),
                'areas' => $areas->getAreasemillerot(),
                'participacioneventos' =>  $participacioneventos->getParticipacioneventost(), 
                'articulos' => $articulos->getArticulost(),
                'libros' => $libros->getLibrost(),
                'capitulos' => $capitulos->getCapitulot(),
                'produccionesinv' => $produccionesinv->getProduccioninvsemillerot(),
                'documentosbliograficos' => $documentosbliograficos->getDocumentosbibliograficossemillerot(),
                'proyectosint' =>  $proyectosint->getProyectosi(),
                'proyectos' =>  $proyectos->getProyectoh(),
                'reconocimientos' => $reconocimientos->getReconocimientossemillerot(),
                'archivossemillero' => $archivossemillero->getArchivossemillerot(),
                'identificadores' => $identificadores->getReportesbyIdentificadores($id),
                'divulgaciones' => $divulgaciones->getReportesbyDivulgacion($id),
                'formaciones' => $formaciones->getReportesbyFormacion($id),
                'consulta' => true,
            ));
            $view->setTerminal(true);
            return $view;
        }else{
            $ExcelsemillerostodoForm = new ExcelsemillerostodoForm();
            
            $opciones = array("" => "");
            foreach ($usuario->getArrayusuarios() as $pa) {
                $nombre_completo = $pa["primer_nombre"] . ' ' . $pa["segundo_nombre"] . ' ' . $pa["primer_apellido"] . ' ' . $pa["segundo_apellido"];
                $op = array(
                    $pa["id_usuario"] => $nombre_completo
                );
                $opciones = $opciones + $op;
            }
            $ExcelsemillerostodoForm->get('coordinador_1')->setValueOptions($opciones);
            $ExcelsemillerostodoForm->get('coordinador_2')->setValueOptions($opciones);

            $opciones = array("" => "");
            $opciones_aux = array("" => "");
            foreach ($datos->getSemilleroinv() as $pa) {
                $opciones = $opciones + array($pa["codigo"] => $pa["codigo"]);
                $opciones_aux = $opciones_aux + array($pa["nombre"] => $pa["nombre"]);
            }
            $ExcelsemillerostodoForm->get('codigo')->setValueOptions($opciones);
            $ExcelsemillerostodoForm->get('nombre')->setValueOptions($opciones_aux);

            $opciones = array("" => "");
            foreach ($pt->getArrayvalores(23) as $datFlex) {                
                $opciones = $opciones  + array($datFlex["id_valor_flexible"] => $datFlex["descripcion_valor"]);
            }
            $ExcelsemillerostodoForm->get('unidad')->setValueOptions($opciones);

            $opciones = array("" => "");
            foreach ($pt->getArrayvalores(33) as $datFlex) {                
                $opciones = $opciones  + array($datFlex["id_valor_flexible"] => $datFlex["descripcion_valor"]);
            }
            $ExcelsemillerostodoForm->get('dependencia')->setValueOptions($opciones);

            $opciones = array("" => "");
            foreach ($pt->getArrayvalores(34) as $datFlex) {                
                $opciones = $opciones  + array($datFlex["id_valor_flexible"] => $datFlex["descripcion_valor"]);
            }
            $ExcelsemillerostodoForm->get('programa')->setValueOptions($opciones);

            $per=array('id_rol'=>'');
            $dat=array('id_rol'=>'');
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $permiso = new Permisos($this->dbAdapter);
        
            //me verifica el tipo de rol asignado al usuario
            $rolusuario->verificarRolusuario($identi);
            foreach ($rolusuario->verificarRolusuario($identi) as $dat) {
                $dat["id_rol"];
            }
        
            if ($dat["id_rol"]!= ''){
            //me verifica el permiso sobre la pantalla
            $pantalla="editarsemilleroinv";
            $panta=0;
            $pt = new Agregarvalflex($this->dbAdapter);
            foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
                $panta["id_valor_flexible"];
            }

            $permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]);
                foreach ($permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]) as $per) {
                $per["id_rol"];
                }
            }


            $view = new ViewModel(
                array(
                    'form'=>$ExcelsemillerostodoForm,
                    'titulo'=>"Reporte Semilleros / Otros procesos de formación",
                    'url'=>$this->getRequest()->getBaseUrl(),
                    'menu'=>$dat["id_rol"],
                    'consulta' => false,
                )
            );
            return $view;
        }
    }
}
