<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Consultamonitores\ConsultamonitoresForm;
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
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Proyectosinv;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Evaluarcriterio;

class ConsultamonitoresController extends AbstractActionController
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
        $this->dbAdapter=$this->getServiceLocator()->get('db1');
        $auth = $this->auth;
        $permisos = new Permisos($this->dbAdapter);
        $identi = print_r($auth->getStorage()->read()->id_usuario,true);
        $resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,get_class());
        if($resultadoPermiso==0){
            $this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
        }
        
        $datosSemilleros = array();
      
      
        $ConsultamonitoresForm = new ConsultamonitoresForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');

        $pt = new Agregarvalflex($this->dbAdapter);
        $auth = $this->auth;
        
        $identi = $auth->getStorage()->read();
        if ($identi == false && $identi == null) {
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/login/index');
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
            $pantalla = "Consultamonitores";
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
        $u = new Aplicarm($this->dbAdapter);
        $buscar = "No";
        $dataBuscar = array();
        if ($this->getRequest()->isPost()) {
            $dataBuscar = $this->request->getPost();
            $buscar = "Si"; 
        }
        $datosAplicarm = $u->getAplicarhMonitores();        
        $pi = new Proyectosinv($this->dbAdapter);
        $prT = new Proyectos ($this->dbAdapter);
        $usu = new Usuarios ($this->dbAdapter);
        $evalCri = new Evaluarcriterio ($this->dbAdapter);
    
        $view = new ViewModel(array(
            'form' => $ConsultamonitoresForm,
            'titulo' => "Consulta de monitores",
            'url' => $this->getRequest()->getBaseUrl(),
            'datosAplicarm' => $datosAplicarm,
            'd_user' => $usua->getUsuarios($d),
            'd_val' => $valflex->getValoresf(),
            'id_user' => $identi->id_usuario,
            'proyinvest' => $pi->getProyectosinvs(),
            'prT' =>  $prT->getProyectoh(),
            'usu' => $usu->filtroUsuario(),
            'menu' => $dat["id_rol"],
            'evalCri' => $evalCri->getEvaluarcriterios(),
            'buscar' =>  $buscar,
            'dataBuscar' => $dataBuscar
        ));
        return $view;
    }

}