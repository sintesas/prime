<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Excelgrupostodo\ExcelgrupostodoForm;
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
use Application\Modelo\Entity\Lineas;
use Application\Modelo\Entity\Gruposrel;
use Application\Modelo\Entity\Redinvestigacion;
use Application\Modelo\Entity\Redes;
use Application\Modelo\Entity\Reconocimientos;
use Application\Modelo\Entity\Asociaciones;
use Application\Modelo\Entity\Semilleros;
use Application\Modelo\Entity\Semillero;
use Application\Modelo\Entity\Instituciones;
use Application\Modelo\Entity\Articulos;
use Application\Modelo\Entity\Libros;
use Application\Modelo\Entity\Capitulosgrupo;
use Application\Modelo\Entity\Otrasproducciones;
use Application\Modelo\Entity\Bibliograficos;
use Application\Modelo\Entity\Proyectosext;
use Application\Modelo\Entity\Proyectosint;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Archivos;
use Application\Modelo\Entity\Identificadoresgru;
use Application\Modelo\Entity\Eventosgru;
use Application\Modelo\Entity\Trabajogradogru;

class ExcelgrupostodoController extends AbstractActionController
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
        $datos = new Grupoinvestigacion($this->dbAdapter);
        $pt = new Agregarvalflex($this->dbAdapter);
        
        if($this->getRequest()->isPost()){
            $data = $this->request->getPost();

            $ExcelgrupostodoForm = new ExcelgrupostodoForm();
            $dat = $datos->getGruposFiltro($data);
            $id = $dat[0]["id_grupo_inv"];
            $usuario = new Usuarios($this->dbAdapter);
            $lineas = new Lineas($this->dbAdapter);
            $gruposrelacionados = new Gruposrel($this->dbAdapter);
            $redinvestigacion = new Redinvestigacion($this->dbAdapter);
            $red = new Redes($this->dbAdapter);
            $rec = new Reconocimientos($this->dbAdapter);
            $asociaciones = new Asociaciones($this->dbAdapter);
            $semilleros = new Semilleros($this->dbAdapter);
            $semillerosinvestigacion = new Semillero($this->dbAdapter);
            $instituciones = new Instituciones($this->dbAdapter);
            $articulos = new Articulos($this->dbAdapter);  
            $libros = new Libros($this->dbAdapter);
            $capitulos = new Capitulosgrupo($this->dbAdapter);
            $otrasproducciones = new Otrasproducciones($this->dbAdapter);
            $bibliograficos = new Bibliograficos($this->dbAdapter);
            $proyext = new Proyectosext($this->dbAdapter);
            $proyectosint = new Proyectosint($this->dbAdapter);
            $proyectos = new Proyectos($this->dbAdapter);
            $archivos = new Archivos($this->dbAdapter);
            $identificadores = new Identificadoresgru($this->dbAdapter);
            $divulgaciones = new Eventosgru($this->dbAdapter);
            $formaciones = new Trabajogradogru($this->dbAdapter);

            $view = new ViewModel(array(
                'form' => $ExcelgrupostodoForm,
                'datos' => $datos->getGruposFiltro($data),
                'url' => $this->getRequest()->getBaseUrl(),
                'usuarios' => $usuario->getArrayusuarios(),
                'valflex' => $pt->getValoresf(),
                'lineas' =>  $lineas->getLineast(),
                'gruposrelacionados' =>  $gruposrelacionados->getGruposrelt(),
                'redinvestigacion' => $redinvestigacion->getRedinv(),
                'redes' => $red->getRedesi(),
                'asociaciones' => $asociaciones->getAsociacionest(),
                'reconocimientos' => $rec->getReconocimientosi(),
                'semilleros' => $semilleros->getSemillerost(),
                'semillerosinvestigacion' => $semillerosinvestigacion->getSemilleroinv(),
                'instituciones' => $instituciones->getInstitucionest(),
                'articulos' => $articulos->getArticulosi(),
                'libros' => $libros->getLibrost(),
                'capitulos' => $capitulos->getCapitulot(),
                'otrasproducciones' => $otrasproducciones->getOtrasproduccionest(),
                'bibliograficos' => $bibliograficos->getBibliograficost(),
                'proyext' => $proyext->getProyectosextt(),
                'proyectosint' =>  $proyectosint->getProyectosi(),
                'proyectos' =>  $proyectos->getProyectoh(),
                'archivos' => $archivos->getArchivost(),
                'identificadores' => $identificadores->getReportesbyIdentificadores($id),
                'divulgaciones' => $divulgaciones->getReportesbyDivulgacion($id),
                'formaciones' => $formaciones->getReportesbyFormacion($id),
                'consulta' => true,
            ));
            $view->setTerminal(true);
            return $view;
        }else{
            $ExcelgrupostodoForm = new ExcelgrupostodoForm();
            
            $opciones = array("" => "");
            foreach ($usuario->getArrayusuarios() as $pa) {
                $nombre_completo = $pa["primer_nombre"] . ' ' . $pa["segundo_nombre"] . ' ' . $pa["primer_apellido"] . ' ' . $pa["segundo_apellido"];
                $op = array(
                    $pa["id_usuario"] => $nombre_completo
                );
                $opciones = $opciones + $op;
            }
            $ExcelgrupostodoForm->get('lider')->setValueOptions($opciones);
            //$ExcelgrupostodoForm->get('coordinador_2')->setValueOptions($opciones);

            $opciones = array("" => "");
            $opciones_aux = array("" => "");
            foreach ($datos->getGrupoinvestigacion() as $pa) {
                $opciones_aux = $opciones_aux + array($pa["nombre_grupo"] => $pa["nombre_grupo"]);
            };
            $ExcelgrupostodoForm->get('nombre')->setValueOptions($opciones_aux);

            $opciones_aux = array("" => "");
            foreach ($datos->getGrupoinvestigacionOrderByCodigo() as $pa) {
                $opciones = $opciones + array($pa["cod_grupo"] => $pa["cod_grupo"]);
            }
            $ExcelgrupostodoForm->get('codigo')->setValueOptions($opciones);
            
            // define el campo pais
            $opciones = array('' => '');
            foreach ($pt->getArrayvalores(21) as $xx) {
                $opciones = $opciones + array($xx["id_valor_flexible"] => $xx["descripcion_valor"]);
            }
            $ExcelgrupostodoForm->get('clasificacion')->setValueOptions($opciones);

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
                    'form'=>$ExcelgrupostodoForm,
                    'titulo'=>"Reporte grupos de investigación",
                    'url'=>$this->getRequest()->getBaseUrl(),
                    'menu'=>$dat["id_rol"],
                    'consulta' => false,
                )
            );
            return $view;
        }
    }
}
