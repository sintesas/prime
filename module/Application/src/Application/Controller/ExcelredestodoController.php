<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Excelredestodo\ExcelredestodoForm;
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
use Application\Modelo\Entity\Redinvestigacion;

use Application\Modelo\Entity\Contactored;
use Application\Modelo\Entity\Integrantesred;
use Application\Modelo\Entity\Serviciosred;
use Application\Modelo\Entity\Proyectored;
use Application\Modelo\Entity\Equipodirectivo;
use Application\Modelo\Entity\Documentosbibliograficosred;
use Application\Modelo\Entity\Librored;
use Application\Modelo\Entity\Capitulosred;
use Application\Modelo\Entity\Articulosred;
use Application\Modelo\Entity\Produccionesinvred;
use Application\Modelo\Entity\Gruposred;
use Application\Modelo\Entity\Archivosred;
use Application\Modelo\Entity\Agregarautorred;
use Application\Modelo\Entity\Proyectosintred;
use Application\Modelo\Entity\Participacioneventosred;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Proyectos;

class ExcelredestodoController extends AbstractActionController
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
        $datos = new Redinvestigacion($this->dbAdapter);
        $pt = new Agregarvalflex($this->dbAdapter);
        
        if($this->getRequest()->isPost()){
            $data = $this->request->getPost();

            $ExcelredestodoForm = new ExcelredestodoForm();
            $equipodirectivo = new Equipodirectivo($this->dbAdapter);
            $grupoinv = new Grupoinvestigacion($this->dbAdapter);
            $proyectos = new Proyectos($this->dbAdapter);
            $contactored = new Contactored($this->dbAdapter);
            $integrantesred = new Integrantesred($this->dbAdapter);
            $participacioneventos = new Participacioneventosred($this->dbAdapter);
            $gruposred = new Gruposred($this->dbAdapter);
            $serviciosred = new Serviciosred($this->dbAdapter);
            $articulos = new Articulosred($this->dbAdapter);
            $libros = new Librored($this->dbAdapter);
            $capitulos = new Capitulosred($this->dbAdapter);
            $produccionesinv = new Produccionesinvred($this->dbAdapter);
            $documentosbliograficos = new Documentosbibliograficosred($this->dbAdapter);
            $proyectored = new Proyectored($this->dbAdapter);
            $proyectosint = new Proyectosintred($this->dbAdapter);
            $archivosred = new Archivosred($this->dbAdapter);

            $view = new ViewModel(array(
                'form' => $ExcelredestodoForm,
                'datos' => $datos->getRedesFiltro($data),
                'url' => $this->getRequest()->getBaseUrl(),
                'usuarios' => $usuario->getArrayusuarios(),
                'valflex' => $pt->getValoresf(),
                'proyectos' =>  $proyectos->getProyectoh(),
                'grupoinv' => $grupoinv->getGrupoinvestigacion(),
                
                'equipodirectivo' => $equipodirectivo->getEquipodirectivot(),
                'contactored' => $contactored->getContactoredt(),
                'integrantesred' => $integrantesred->getIntegrantesredi(),
                'participacioneventos' =>  $participacioneventos->getParticipacioneventosredt(), 
                'gruposred' => $gruposred->getGruposredt(),
                'serviciosred' => $serviciosred->getServiciosredt(),
                'articulos' => $articulos->getArticulost(),
                'libros' => $libros->getLibrost(),
                'capitulos' => $capitulos->getCapitulot(),
                'produccionesinv' => $produccionesinv->getProduccioninvredt(),
                'documentosbliograficos' => $documentosbliograficos->getDocumentosbibliograficosredt(),    
                'proyectored' => $proyectored->getProyectoredt(),
                'proyectosint' =>  $proyectosint->getProyectosi(),  
                'archivosred' => $archivosred->getArchivosredt(),  
                'consulta' => true,
            ));
            $view->setTerminal(true);
            return $view;
        }else{
            $ExcelredestodoForm = new ExcelredestodoForm();
            
            $opciones = array("" => "");
            foreach ($usuario->getArrayusuarios() as $pa) {
                $nombre_completo = $pa["primer_nombre"] . ' ' . $pa["segundo_nombre"] . ' ' . $pa["primer_apellido"] . ' ' . $pa["segundo_apellido"];
                $op = array(
                    $pa["id_usuario"] => $nombre_completo
                );
                $opciones = $opciones + $op;
            }
            $ExcelredestodoForm->get('coordinador_1')->setValueOptions($opciones);
            $ExcelredestodoForm->get('coordinador_2')->setValueOptions($opciones);

            $opciones = array("" => "");
            $opciones_aux = array("" => "");
            foreach ($datos->getRedinv() as $pa) {
                $opciones = $opciones + array($pa["codigo"] => $pa["codigo"]);
                $opciones_aux = $opciones_aux + array($pa["nombre"] => $pa["nombre"]);
            }
            $ExcelredestodoForm->get('codigo')->setValueOptions($opciones);
            $ExcelredestodoForm->get('nombre')->setValueOptions($opciones_aux);

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
                    'form'=>$ExcelredestodoForm,
                    'titulo'=>"Reporte redes de investigación",
                    'url'=>$this->getRequest()->getBaseUrl(),
                    'menu'=>$dat["id_rol"],
                    'consulta' => false,
                )
            );
            return $view;
        }
    }
}
