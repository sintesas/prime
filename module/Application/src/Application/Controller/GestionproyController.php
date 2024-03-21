<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\GestionproyForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Aplicar;
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Asignareval;
use Application\Modelo\Entity\Evaluar;
use Application\Modelo\Entity\Usuarios;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Lineas;
use Application\Modelo\Entity\Libros;
use Application\Modelo\Entity\Proyectosext;
use Application\Modelo\Entity\Cronogramaap;
use Application\Modelo\Entity\Requisitosap;
use Application\Modelo\Entity\Aspectoeval;
use Application\Modelo\Entity\Requisitosdoc;
use Application\Modelo\Entity\Requisitosapdoc;
use Application\Modelo\Entity\Propuestainv;
use Application\Modelo\Entity\Rolesconv;
use Application\Modelo\Entity\Archivosconv;
use Application\Modelo\Entity\Url;
use Application\Modelo\Entity\Tablafin;
use Application\Modelo\Entity\Camposadd;
use Application\Modelo\Entity\Tablaequipo;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Gruposparticipantes;
use Application\Modelo\Entity\Tablafinproy;
use Application\Modelo\Entity\Camposaddproy;
use Application\Modelo\Entity\Talentohumano;
use Application\Modelo\Entity\Evaluarproy;

class GestionproyController extends AbstractActionController
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
        $this->dbAdapter=$this->getServiceLocator()->get('db1');
        $auth = $this->auth;
        $permisos = new Permisos($this->dbAdapter);
        $identi = print_r($auth->getStorage()->read()->id_usuario,true);
        $resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,get_class());
        if($resultadoPermiso==0){
            $this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
        }
        
        if ($this->getRequest()->isPost()) {
            // Creacion adaptador de conexion, objeto de datos, auditoria
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $not = new Aplicar($this->dbAdapter);
            $this->auth = new AuthenticationService();
            $auth = $this->auth;
            
            $result = $auth->hasIdentity();
            
            // verifica si esta conectado
            $identi = $auth->getStorage()->read();
            
            $rol = new Roles($this->dbAdapter);
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
            }
            
            // obtiene la informacion de las pantallas
            $data = $this->request->getPost();
            $GestionproyForm = new GestionproyForm();
            
            // define el campo ciudad
            
            // adiciona la noticia
            $evaluador = new Asignareval($this->dbAdapter);
            
            $usuario = new Usuarios($this->dbAdapter);
            $proyext = new Proyectosext($this->dbAdapter);
            $eval = new Evaluar($this->dbAdapter);
            // redirige a la pantalla de inicio del controlador
            $view = new ViewModel(array(
                'form' => $GestionproyForm,
                'titulo' => "Gestión de requisitos y evaluaciones proyecto",
                'datos' => $not->filtroAplicar($data),
                'evaluados' => $eval->getEvaluar(),
                'evaluador' => $evaluador->getAsignarevalt(),
                'usuario' => $usuario->getArrayusuarios(),
                'url' => $this->getRequest()->getBaseUrl(),
                'consulta' => 1,
                'menu' => $dat["id_rol"]
            ));
            return $view;
        } else {   
            $GestionproyForm = new GestionproyForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Aplicar($this->dbAdapter);
            $us = new Aplicarm($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            
            $this->auth = new AuthenticationService();
            $auth = $this->auth;
            
            $result = $auth->hasIdentity();
            
            $identi = $auth->getStorage()->read();
            
            $filter = new StringTrim();
            
            // define el campo ciudad
            
            // verificar roles
            $per = array(
                'id_rol' => ''
            );
            $dat = array(
                'id_rol' => ''
            );
            $permiso_1 = '';
            $roles_1 = '';
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $permiso = new Permisos($this->dbAdapter);
            
            // me verifica el tipo de rol asignado al usuario
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $roles_1 = $dat["id_rol"];
            }
            
            if ($dat["id_rol"] != '') {
                // me verifica el permiso sobre la pantalla
                $pantalla = "grupoinv";
                $panta = 0;
                $pt = new Agregarvalflex($this->dbAdapter);
                
                foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
                    $panta["id_valor_flexible"];
                }
                
                $permiso->verificarPermiso($dat["id_rol"], $panta["id_valor_flexible"]);
                foreach ($permiso->verificarPermiso($roles_1, $panta["id_valor_flexible"]) as $per) {
                    $permiso_1 = $per["id_rol"];
                }
            }
            $id = (int) $this->params()->fromRoute('id', 0);
            $Cronograma = new Cronogramaap($this->dbAdapter);
            $Requisitos = new Requisitosap($this->dbAdapter);
            $Aspectoeval = new Aspectoeval($this->dbAdapter);
            $Requisitosdoc = new Requisitosdoc($this->dbAdapter);
            $Rolesconv = new Rolesconv($this->dbAdapter);
            $Archivosconv = new Archivosconv($this->dbAdapter);
            $Propuesta_inv = new Propuestainv($this->dbAdapter);
            $Url = new Url($this->dbAdapter);
            
            $valflex = new Agregarvalflex($this->dbAdapter);
            $campos = new Camposadd($this->dbAdapter);
            $camposaddproy = new Camposaddproy($this->dbAdapter);
            $rolesuser = new Roles($this->dbAdapter);
            $grupar = new Gruposparticipantes($this->dbAdapter);
            $tablae = new Tablaequipo($this->dbAdapter);
            $gruinv = new Grupoinvestigacion($this->dbAdapter);
            $us = new Usuarios($this->dbAdapter);
            $docreq = new Requisitosapdoc($this->dbAdapter);
            $conv = new Convocatoria($this->dbAdapter);
            $evaluar = new Evaluar($this->dbAdapter);
            
            ;
            foreach ($u->getAplicar($id) as $convo) {
                $res = $convo["id_convocatoria"];
            }
            
            if (true) {
                
                $view = new ViewModel(array(
                    'form' => $GestionproyForm,
                    'titulo' => "Gestión de requisitos y evaluaciones proyecto",
                    'titulo2' => "Tipos Valores Existentes",
                    'Requisitos' => $Requisitos->getRequisitosap($id),
                    'Requisitosdoc' => $Requisitosdoc->getRequisitosdoc($id),
                    'Aspectoeval' => $Aspectoeval->getAspectoeval($id),
                    'Camposaddproy' => $camposaddproy->getCamposaddproy($id),
                    'tablae' => $tablae->getTablaequipo($id),
                    'grupar' => $grupar->getGruposparticipantes($id),
                    'docreq' => $docreq->getRequisitosapdoc($id),
                    'campos' => $campos->getCamposadd($id),
                    'valflex' => $valflex->getValoresf(),
                    'datos' => $evaluar->getEvaluados($id),
                    'tipo' => $conv->getTipoconv($res),
                    'id_convocatoria' => $res,
                    'url' => $this->getRequest()->getBaseUrl(),
                    'proyecto' => $u->getAplicar($id),
                    'usua' => $us->getArrayusuarios(),
                    'consulta' => 0,
                    'menu' => $dat["id_rol"]
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/mensajeadministrador/index');
            }
        }
    }

    public function cumplimientoApAction(){
        $this->dbAdapter=$this->getServiceLocator()->get('db1');
        $u = new Requisitosapdoc($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id',0);
        $id2 = (int) $this->params()->fromRoute('id2',0);
        $id3 = (int) $this->params()->fromRoute('id3',0);
        $Requisitos = new Requisitosap($this->dbAdapter);
        if($id3==1){
            $Requisitos->updateEstadoRequisitos($id, 'C');
        }else{
            $Requisitos->updateEstadoRequisitos($id, 'N');    
        }
        $this->flashMessenger()->addMessage("Estado modificado con éxito.");
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/gestionproy/index/'.$id2);
    }

    public function cumplimientoApDocAction(){
        $this->dbAdapter=$this->getServiceLocator()->get('db1');
        $u = new Requisitosapdoc($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id',0);
        $id2 = (int) $this->params()->fromRoute('id2',0);
        $id3 = (int) $this->params()->fromRoute('id3',0);
        $docreq = new Requisitosapdoc($this->dbAdapter);
        if($id3==1){
            $docreq->updateEstadoRequisitos($id, 'C');
        }else{
            $docreq->updateEstadoRequisitos($id, 'N');    
        }
        $this->flashMessenger()->addMessage("Estado modificado con éxito.");
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/gestionproy/index/'.$id2);
    }
}
