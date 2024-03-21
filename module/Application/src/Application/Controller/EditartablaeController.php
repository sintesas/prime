<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\EditartablaeForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Tablaequipo;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class EditartablaeController extends AbstractActionController
{

    private $auth;

    public $dbAdapter;

    public function __construct()
    {
        // Cargamos el servicio de autenticaci�n en el constructor
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
        
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
         $id3 = (int) $this->params()->fromRoute('id3', 0);

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
            $EditartablaeForm = new EditartablaeForm();
            $id = (int) $this->params()->fromRoute('id', 0);
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Tablaequipo($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            $data = $this->request->getPost();
            // print_r($data);
            $u->updateTablaequipo($id, $data);
            if($id3!=0){
                $urlId = "/application/editaraplicari/index/" . $id2;
            }else{
                $urlId = "/application/editaraplicar/index/" . $id2;
            }

            $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
            $evento = 'Creación de rol integrante : (mgc_responsablesap)';
            $ad->addAuditoriadet($evento, $resul);

            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . $urlId);
            $this->flashMessenger()->addMessage("Integrante editado con Exito");
        } else {
            $EditartablaeForm = new EditartablaeForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Agregarvalflex($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $filter = new StringTrim();
            
            $us = new Tablaequipo($this->dbAdapter);
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            $id = (int) $this->params()->fromRoute('id', 0);
            foreach ($us->getTablaequipoid($id) as $dat) {
                $dat["id_integrantes"];
                $idrol = $dat["id_rol"];
                $idtipd = $dat["id_tipo_dedicacion"];
            }
            
            $EditartablaeForm->add(array(
                'name' => 'horas_apro',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($dat["horas_apro"]),
                    'placeholder' => 'Ingrese las horas aprobadas :'
                ),
                'options' => array(
                    'label' => 'Horas Aprobadas :'
                )
            ));
            
            $EditartablaeForm->add(array(
                'name' => 'horas_sol',
                'attributes' => array(
                    'type' => 'number',
                    'value' => $filter->filter($dat["horas_sol"]),
                    'placeholder' => 'Ingrese las horas solicitadas'
                ),
                'options' => array(
                    'label' => 'Horas Solicitadas :'
                )
            ));
            
            // define el campo genero
            if ($idrol == null) {
                $v_rol = 1;
            } else {
                $v_rol = $idrol;
            }
            // define el campo rol en el proyecto
            $vf = new Agregarvalflex($this->dbAdapter);
            $resvf = $vf->getValoresflexid($v_rol);
            $opciones = array();
            foreach ($vf->getArrayvalores(39) as $da) {
                $op = array(
                    $resvf["id_valor_flexible"] => $resvf["descripcion_valor"]
                );
                $op = $op + array(
                    $da["id_valor_flexible"] => $da["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $EditartablaeForm->add(array(
                'name' => 'id_rol',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Rol en el Proyecto : ',
                    
                    'value_options' => $opciones
                )
            ));
            
            if ($idtipd == null) {
                $v_tip = 1;
            } else {
                $v_tip = $idtipd;
            }
            // define el campo tipo dedicaci�n
            $vf = new Agregarvalflex($this->dbAdapter);
            $resvf = $vf->getValoresflexid($v_tip);
            $opciones = array();
            foreach ($vf->getArrayvalores(50) as $da) {
                $op = array(
                    $resvf["id_valor_flexible"] => $resvf["descripcion_valor"]
                );
                $op = $op + array(
                    $da["id_valor_flexible"] => $da["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $EditartablaeForm->add(array(
                'name' => 'id_tipo_dedicacion',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Tipo de Dedicación : ',
                    
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
                $pantalla = "tablaequipo";
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
            
            if (true) {
                $view = new ViewModel(array(
                    'form' => $EditartablaeForm,
                    'titulo' => "Integrante del Proyecto",
                    'id' => $id,
                    'id2' => $id2,
                    'id3' => $id3,
                    'url' => $this->getRequest()->getBaseUrl(),
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
