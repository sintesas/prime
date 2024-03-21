<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Crearusuario\CrearusuarioForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class CrearusuarioController extends AbstractActionController
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
            
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Usuarios($this->dbAdapter);
            $data = $this->request->getPost();
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $correovalido = $u->comprobarCorreo($data);
            
            if ($correovalido == 0) {
                $this->flashMessenger()->addMessage("El correo ingresado ya se encuentra en uso.");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/crearusuario/index');
            } else {
                $resultado = $u->addUsuario($data, $identi->usuario);
            }
            
            $au = new Auditoria($this->dbAdapter);
            $ad = new Auditoriadet($this->dbAdapter);

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
            
            if ($resultado == 1) {
                $resultado = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                $p = implode(',', (array) $data);
                $evento = 'Creación de usuario. Primer nombre: '.$data["primer_nombre"]. " Segundo nombre: ".$data["segundo_nombre"]. " Primer apellido: ". $data["primer_apellido"]." Segundo apellido: ". $data["segundo_apellido"] . ' (aps_usuario)';
                $ad->addAuditoriadet($evento, $resultado);
                
                $this->flashMessenger()->addMessage("Usuario creado con éxito.");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/usuario/index');
            } else {
                $resultado = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                $p = implode(',', (array) $data);
                $evento = 'Intento fallido de creación de usuario :' . $p . ' (aps_usuario)';
                $ad->addAuditoriadet($evento, $resultado);
                
                $this->flashMessenger()->addMessage("La creacion del usuario falló.");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/usuario/index');
            }
            
        } else {
            $CrearusuarioForm = new CrearusuarioForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $id = (int) $this->params()->fromRoute('id', 0);
            $u = new Usuarios($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            // define el campo ciudad
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(1) as $dat) {
                $op = array(
                    $dat["id_valor_flexible"] => $dat["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $CrearusuarioForm->add(array(
                'name' => 'id_ciudad',
                'type' => 'Zend\Form\Element\Select',
                'required' => 'required',
                'size' => 25,
                'options' => array(
                    'label' => 'Ciudad: ',
                    'value_options' => $opciones
                )
            ));
            
            // define el campo ciudad
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(1) as $dat) {
                $op = array(
                    $dat["id_valor_flexible"] => $dat["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $CrearusuarioForm->add(array(
                'name' => 'id_lugar_nacimiento',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Lugar de Nacimiento: ',
                    'value_options' => $opciones
                )
            ));
            
            // define el campo genero
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(2) as $dat) {
                $op = array(
                    $dat["id_valor_flexible"] => $dat["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $CrearusuarioForm->add(array(
                'name' => 'id_sexo',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Género : ',
                    'value_options' => $opciones
                )
            ));
            
            // define el campo nacionalidad
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(3) as $dat) {
                $op = array(
                    $dat["id_valor_flexible"] => $dat["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $CrearusuarioForm->add(array(
                'name' => 'id_nacionalidad',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Nacionalidad : ',
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
                $pantalla = "crearusuario";
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
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $resvf = $vf->getValoresflexid(" ");
            $opciones = array();
            foreach ($vf->getArrayvalores(25) as $da) {
                $op = array(
                    $resvf["id_valor_flexible"] => $resvf["descripcion_valor"]
                );
                $op = $op + array(
                    $da["id_valor_flexible"] => $da["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $CrearusuarioForm->add(array(
                'name' => 'id_tipo_documento',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Tipo Documento : ',
                    
                    'value_options' => $opciones
                )
            ));
            
            if (true) {
                $view = new ViewModel(array(
                    'form' => $CrearusuarioForm,
                    'titulo' => "Crear datos usuarios",
                    'url' => $this->getRequest()->getBaseUrl(),
                    'datos' => $u->getUsuarios($id),
                    'id' => $identi->id_usuario,
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
