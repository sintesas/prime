<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Grupoinv\GrupoinvForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Grupoinvestigacion;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Agregarvalfelx;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;

class GrupoinvController extends AbstractActionController
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
        //session_start();
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
            $not = new Grupoinvestigacion($this->dbAdapter);
            $auth = $this->auth;
            $usua = new Usuarios($this->dbAdapter);
            $valflex = new Agregarvalflex($this->dbAdapter);
            $d = '';
            
            // verifica si esta conectado
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $identi->id_usuario;
            $rol = new Roles($this->dbAdapter);
            $rolusuario = new Rolesusuario($this->dbAdapter);
            // $Usuarioform = new UsuarioForm();
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
            }
            
            // obtiene la informacion de las pantallas
            $data = $this->request->getPost();
            $GrupoinvForm = new GrupoinvForm();
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(23) as $da) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $da["id_valor_flexible"] => $da["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $GrupoinvForm->add(array(
                'name' => 'id_unidad_academica',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Unidad Académica : ',
                    
                    'value_options' => $opciones
                )
            ));
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(33) as $da) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $da["id_valor_flexible"] => $da["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $GrupoinvForm->add(array(
                'name' => 'id_dependencia_academica',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Dependencia Académica : ',
                    
                    'value_options' => $opciones
                )
            ));
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(34) as $da) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $da["id_valor_flexible"] => $da["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $GrupoinvForm->add(array(
                'name' => 'id_programa_academico',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Programa Académico : ',
                    
                    'value_options' => $opciones
                )
            ));
            
            // adiciona la noticia
            $view = new ViewModel(array(
                'form' => $GrupoinvForm,
                'titulo' => "Grupos de Investigación",
                'datos' => $not->filtroGrupos($data),
                'url' => $this->getRequest()->getBaseUrl(),
                'd_user' => $usua->getUsuarios($d),
                'd_val' => $valflex->getValoresf(),
                'menu' => $dat["id_rol"],
                'data'=> $data
            ));
            return $view;
        } else {
            $GrupoinvForm = new GrupoinvForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Grupoinvestigacion($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $filter = new StringTrim();
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(23) as $da) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $da["id_valor_flexible"] => $da["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $GrupoinvForm->add(array(
                'name' => 'id_unidad_academica',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Unidad Académica : ',
                    
                    'value_options' => $opciones
                )
            ));
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(33) as $da) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $da["id_valor_flexible"] => $da["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $GrupoinvForm->add(array(
                'name' => 'id_dependencia_academica',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Dependencia Académica : ',
                    
                    'value_options' => $opciones
                )
            ));
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(34) as $da) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $da["id_valor_flexible"] => $da["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $GrupoinvForm->add(array(
                'name' => 'id_programa_academico',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Programa Académico : ',
                    
                    'value_options' => $opciones
                )
            ));
            
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
                $pantalla = "grupoinv";
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
            
            
            $view = new ViewModel(array(
                'form' => $GrupoinvForm,
                'titulo' => "Grupos de Investigaci&#243;n",
                'titulo2' => "Tipos Valores Existentes",
                'url' => $this->getRequest()->getBaseUrl(),
                'datos' => $u->getGrupoinvestigacion(),
                'd_user' => $usua->getUsuarios($d),
                'd_val' => $valflex->getValoresf(),
                'id_user' => $identi->id_usuario,
                'menu' => $dat["id_rol"]
            ));
            return $view;
        }
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Grupoinvestigacion($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $u->eliminarGrupoinv($id);
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/grupoinv/index');
    }
}