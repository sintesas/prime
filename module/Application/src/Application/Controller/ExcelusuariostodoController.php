<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Excelusuariostodo\ExcelusuariostodoForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;
use Application\Modelo\Entity\Lineashv;
use Application\Modelo\Entity\Articuloshv;
use Application\Modelo\Entity\Libroshv;
use Application\Modelo\Entity\Otrasproduccioneshv;
use Application\Modelo\Entity\Proyectosexthv;
use Application\Modelo\Entity\Experiencialabhv;
use Application\Modelo\Entity\Formacionacahv;
use Application\Modelo\Entity\Bibliograficoshv;
use Application\Modelo\Entity\Formacioncomhv;
use Application\Modelo\Entity\Idiomashv;
use Application\Modelo\Entity\Actividadeshv;
use Application\Modelo\Entity\Capitulosusuario;
use Application\Modelo\Entity\Areashv;
use Application\Modelo\Entity\Proyectosintusua;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Identificadoresusu;
use Application\Modelo\Entity\Eventosusu;
use Application\Modelo\Entity\Trabajogradousu;

class ExcelusuariostodoController extends AbstractActionController
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
        $identi = print_r($auth->getStorage()->read()->id_usuario, true);
        $resultadoPermiso = $permisos->getValidarPermisoPantalla($identi, get_class());
        if ($resultadoPermiso == 0){
        //     $this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
        //     return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
        }

        $usuario = new Usuarios($this->dbAdapter);

        if($this->getRequest()->isPost()) {
            $data = $this->request->getPost();
            $Excelusuariostodoform = new Excelusuariostodoform();
            $usuario = new Usuarios($this->dbAdapter);
            $dat = $usuario->getReportesbyUsuario($data);
            $id = $dat[0]["id_usuario"];
            $li = new Lineashv($this->dbAdapter);
            $art = new Articuloshv($this->dbAdapter);
            $lib = new Libroshv($this->dbAdapter);
            $proyext = new Proyectosexthv($this->dbAdapter);
            $otras = new Otrasproduccioneshv($this->dbAdapter);
            $explab = new Experiencialabhv($this->dbAdapter);
            $formcom = new Formacioncomhv($this->dbAdapter);
            $formaca = new Formacionacahv($this->dbAdapter);
            $actividad = new Actividadeshv($this->dbAdapter);
            $idioma = new Idiomashv($this->dbAdapter);            
            $area = new Areashv($this->dbAdapter);
            $biblio = new Bibliograficoshv($this->dbAdapter);
            $capitulos = new Capitulosusuario($this->dbAdapter);
            $proyectosint = new Proyectosintusua($this->dbAdapter);     
            $identificadores = new Identificadoresusu($this->dbAdapter); 
            $eventos = new Eventosusu($this->dbAdapter); 
            $formacion = new Trabajogradousu($this->dbAdapter);

            $view = new ViewModel(array(
                'form' => $Excelusuariostodoform,
                'datos' => $usuario->getReportesbyUsuario($data),
                'url' => $this->getRequest()->getBaseUrl(),
                'li' => $li->getReportesbyLineashv($id),
                'lib' => $lib->getReportesbyLibroshv($id),
                'art' => $art->getReportesbyArticuloshv($id),
                'proyext' => $proyext->getReportesbyProyectosexthv($id),
                'otras' => $otras->getReportesbyOtrasProduccioneshv($id),
                'explab' => $explab->getReportesbyExperiencialabhv($id),
                'formcom' => $formcom->getReportesbyFormacioncomhv($id),
                'formaca' => $formaca->getReportesbyFormacionacahv($id),
                'actividad' => $actividad->getReportesbyActividadeshv($id),
                'idioma' => $idioma->getReportesbyIdiomashv($id),
                'area' => $area->getReportesbyAreashv($id),
                'biblio' => $biblio->getReportesbyBibliograficoshv($id),
                'capitulos' => $capitulos->getReportesbyCapitulosusu($id),
                'proyectosint' => $proyectosint->getReportesbyProyectosintusua($id),
                'identificadores' => $identificadores->getReportesbyIdentificadores($id),
                'eventos' => $eventos->getReportesbyDivulgacion($id),
                'formacion' => $formacion->getReportesbyFormacion($id),
                'consulta' => true,
            ));

            $view->setTerminal(true);
            return $view;
        }
        else {
            $Excelusuariostodoform = new Excelusuariostodoform();

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
                    'form'=>$Excelusuariostodoform,
                    'titulo'=>"Reporte Usuarios",
                    'url'=>$this->getRequest()->getBaseUrl(),
                    'menu'=>$dat["id_rol"],
                    'consulta' => false,
                )
            );

            return $view;
        }
    }
}