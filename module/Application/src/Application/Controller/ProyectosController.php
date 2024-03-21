<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Proyectos\ProyectosForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Tablafin;
use Application\Modelo\Entity\Tablafinproy;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class ProyectosController extends AbstractActionController
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

        function get_real_ip()
        {
            if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                return $_SERVER["HTTP_CLIENT_IP"];
            } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                return $_SERVER["HTTP_X_FORWARDED_FOR"];
            } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
                return $_SERVER["HTTP_X_FORWARDED"];
            } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
                return $_SERVER["HTTP_FORWARDED_FOR"];
            } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
                return $_SERVER["HTTP_FORWARDED"];
            } else {
                return $_SERVER["REMOTE_ADDR"];
            }
        }

        $au = new Auditoria($this->dbAdapter);
        $ad = new Auditoriadet($this->dbAdapter);
        
        if ($this->getRequest()->isPost()) {
            // Creacion adaptador de conexion, objeto de datos, auditoria
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $not = new Proyectos($this->dbAdapter);
            $auth = $this->auth;
            
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
            $ProyectosForm = new ProyectosForm();
            // adiciona la noticia
            $id = (int) $this->params()->fromRoute('id', 0);
            
            $resultado = $not->addProyectos($data);
            
            // redirige a la pantalla de inicio del controlador
            if ($resultado != '') {
                $res = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                $p = implode(',', (array) $data);
                $evento = 'Creacion de proyecto:' . $p;
                $ad->addAuditoriadet($evento, $res);
                
                $this->flashMessenger()->addMessage("Usted creo con exito el proyecto, llene los datos faltantes");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/editarproyecto/index/' . $resultado);
            } else {
                $this->flashMessenger()->addMessage("La creacion de la asociacion fallo");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/aplicar/index');
            }
        } else {
            $ProyectosForm = new ProyectosForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Proyectos($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            $us = new Usuarios($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            
            $result = $auth->hasIdentity();
            $filter = new StringTrim();
            if (isset($_COOKIE["campo_o"])) {
                echo '';
            } else {
                $_COOKIE["campo_o"] = '';
            }
            if (isset($_COOKIE["uni_o"])) {
                echo '';
            } else {
                $_COOKIE["uni_o"] = '';
            }
            if (isset($_COOKIE["dep_o"])) {
                echo '';
            } else {
                $_COOKIE["dep_o"] = '';
            }
            $d = '';
            $opciones = array();
            foreach ($us->getUsuarios($d) as $dat) {
                $op = array(
                    $filter->filter($dat["id_usuario"]) => trim($dat["primer_nombre"]).' '.trim($dat["segundo_nombre"]).' '.trim($dat["primer_apellido"]).' '.trim($dat["segundo_apellido"])
                );
                $opciones = $opciones + $op;
            }
            
            $ProyectosForm->add(array(
                'name' => 'id_investigador',
                'type' => 'Zend\Form\Element\Select',
                'disabled' => 'True',
                'required' => 'required',
                'attributes' => array(
                    'id' => 'id_investigador'
                )
                ,
                'options' => array(
                    'empty_option' => '',
                    'label' => 'Líder del proyecto o proceso de investigación: ',
                    'value_options' => $opciones
                )
            ));
            
            $id2 = (int) $this->params()->fromRoute('id2', 1);
            $v_campo = $id2;
            if ($_COOKIE["campo_o"] != 1 && $v_campo != $id2) {
                $v_campo = $_COOKIE["campo_o"];
            }
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $resvf = $vf->getValoresflexid($v_campo);
            $opciones = array('' => '');
            foreach ($vf->getArrayvalores(37) as $xx) {
                $op = array(
                    $xx["id_valor_flexible"] => $xx["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $ProyectosForm->add(array(
                'name' => 'id_campo',
                'required' => 'required',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'id_campo',
                    'onchange' => 'myFunction(this.id);'
                )
                ,
                'options' => array(
                    'label' => 'Campo de Investigacion : ',
                    'value_options' => $opciones
                )
            ));
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array('' => '');
            foreach ($vf->getArrayvalores(40) as $dat) {
                $opciones = $opciones + array(
                    $dat["id_valor_flexible"] . '-' . $dat["valor_flexible_padre_id"]  => $dat["descripcion_valor"]
                );
            }
            $ProyectosForm->add(array(
                'name' => 'id_linea_inv',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'required' => 'required', 
                    'id' => 'id_linea_inv'
                ),
                
                'options' => array(
                    'label' => 'Linea de Investigacion : ',
                    'value_options' => $opciones
                )
            ));
            
            // ------------------------------------------------------------//
            
            // Unidad Academica
            
            $id3 = (int) $this->params()->fromRoute('id3', 1);
            $v_uni = $id3;
            if ($_COOKIE["uni_o"] != 1 && $v_uni != $id3) {
                $v_uni = $_COOKIE["uni_o"];
            }
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $resvf = $vf->getValoresflexid($v_uni);
            $opciones = array();
            foreach ($vf->getArrayvalores(23) as $xx) {
                $op = array(
                    "" => ""
                );
                
                $op = $op + array(
                    $xx["id_valor_flexible"] => $xx["descripcion_valor"]
                );
                
                $opciones = $opciones + $op;
            }
            
            $ProyectosForm->add(array(
                'name' => 'id_unidad_academica',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'id_unidad_academica',
                    'onchange' => 'myFunction2();',
                    'required' => 'required'
                ),
                'options' => array(
                    'label' => 'Unidad Académica : ',
                    'value_options' => $opciones
                )
            ));
            
            // ------------------------------------------------------------//
            
            // Dependencia Academica
            
            $id4 = (int) $this->params()->fromRoute('id4', 1);
            $v_dep = $id4;
            if ($_COOKIE["dep_o"] != 1 && $v_dep != $id4) {
                $v_uni = $_COOKIE["dep_o"];
            }
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $resvf = $vf->getValoresflexid($v_dep);
            $opciones = array();
            foreach ($vf->getArrayvalores(33) as $uni) {
                $op = array(
                    "" => ""
                );
                $op = $op + array(
                    $uni["id_valor_flexible"] . '-' . $uni["valor_flexible_padre_id"] => $uni["descripcion_valor"]
                );
                
                $opciones = $opciones + $op;
            }
            $ProyectosForm->add(array(
                'name' => 'id_dependencia_academica',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'id_dependencia_academica',
                    'disabled' => 'disabled',
                    'onchange' => 'myFunction3();',
                    'required' => 'required'
                ),
                'options' => array(
                    'label' => 'Dependencia Académica : ',
                    'value_options' => $opciones
                )
            ));
            
            // ------------------------------------------------------------//
            
            // Programa Academico
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(34) as $uni) {
                $op = array(
                    "" => ""
                );
                $op = $op + array(
                    $uni["id_valor_flexible"] . '-' . $uni["valor_flexible_padre_id"] => $uni["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $ProyectosForm->add(array(
                'name' => 'id_programa_academico',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'id_programa_academico',
                    'disabled' => 'disabled',
                    'required' => 'required'
                ),
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
                $pantalla = "editargrupoinv";
                $panta = 0;
                $pt = new Agregarvalflex($this->dbAdapter);
                
                foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
                    $panta["id_valor_flexible"];
                }
                
                $permiso->verificarPermiso($roles_1, $panta["id_valor_flexible"]);
                foreach ($permiso->verificarPermiso($roles_1, $panta["id_valor_flexible"]) as $per) {
                    $permiso_1 = $per["id_rol"];
                }
            }
            
            if (true) {
                $id = (int) $this->params()->fromRoute('id', 0);
                $view = new ViewModel(array(
                    'form' => $ProyectosForm,
                    'titulo' => "Crear proyecto o proceso de investigación",
                    'url' => $this->getRequest()->getBaseUrl(),
                    
                    'id' => $id,
                    'id2' => $id2,
                    'id3' => $id3,
                    'id4' => $id4,
                    'menu' => $dat["id_rol"]
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/mensajeadministrador/index');
            }
        }
    }
}