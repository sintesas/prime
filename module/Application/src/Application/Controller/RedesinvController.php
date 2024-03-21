<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Redesinv\RedesinvForm;
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

class RedesinvController extends AbstractActionController
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
        if(isset($_SESSION['navegador'])){unset($_SESSION['navegador']);}
        
    
        $RedesinvForm = new RedesinvForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Redinvestigacion($this->dbAdapter);
        $pt = new Agregarvalflex($this->dbAdapter);
        $auth = $this->auth;
        
        $identi = $auth->getStorage()->read();
        if ($identi == false && $identi == null) {
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/login/index');
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
            $pantalla = "redesinv";
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
        $data=array();
        if ($this->getRequest()->isPost()) {
             $data = $this->request->getPost();
            if($dat["id_rol"]==1){
                //$datosRedes = $u->getSemilleroinv();   
                $datosRedes = $u->getRedinvById($data, $identi->id_usuario);  
            }else{
                $datosRedes = $u->getRedinvById($data, $identi->id_usuario);  
            }
        }else{
            $datosRedes = $u->getRedinv();
        }

        $usua = new Usuarios($this->dbAdapter);
        $valflex = new Agregarvalflex($this->dbAdapter);
        $d = '';

        
        if (true) {
            $view = new ViewModel(array(
                'form' => $RedesinvForm,
                'titulo' => "Redes de Investigación",
                'url' => $this->getRequest()->getBaseUrl(),
                'datos' => $datosRedes,
                'd_user' => $usua->getUsuarios($d),
                'd_val' => $valflex->getValoresf(),
                'id_user' => $identi->id_usuario,
                'menu' => $dat["id_rol"],
                'data'=>$data
            ));
            return $view;
        } else {
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/mensajeadministrador/index');
        }
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