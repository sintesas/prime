<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Application\Convocatoria\AplicarForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Aplicar;
use Application\Modelo\Entity\Usuarios;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class AplicarController extends AbstractActionController
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
            $AplicarForm = new AplicarForm();
            // adiciona la noticia
            $id = (int) $this->params()->fromRoute('id', 0);
            $resultado = $not->addAplicar($data, $id);
            
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
            
            // redirige a la pantalla de inicio del controlador
            if ($resultado != null) {
                $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                $evento = 'Creacion de aplicacion : ' . $resultado;
                $ad->addAuditoriadet($evento, $resul);
                
                $convo = new Convocatoria($this->dbAdapter);
                $convo->updateEstado($id, 'P');
                
                $this->flashMessenger()->addMessage("Usted aplicó con exito a la convocatoria, llene los datos faltantes.");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/editaraplicar/index/' . $resultado.'/aplicar');
            } else {
                $this->flashMessenger()->addMessage("La creacion de la asociacion fallo");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/aplicar/index');
            }
        } else {
            $AplicarForm = new AplicarForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Aplicar($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            $us = new Usuarios($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            
            $result = $auth->hasIdentity();
            $filter = new StringTrim();
            
            foreach ($us->getUsuarios($identi->id_usuario) as $usid) {
                $nombre = $usid["primer_nombre"] . ' ' . $usid["primer_apellido"];
                $opciones = array(
                    $usid["id_usuario"] => $nombre
                );
            }
            
            
            $AplicarForm->add(array(
                'name' => 'id_investigador',
                'type' => 'Zend\Form\Element\Select',
                'disabled' => 'True',
                'placeholder' => 'Ingrese el nombre del investigador principal',
                'options' => array(
                    'label' => 'Investigador Principal: ',
                    'value_options' => $opciones
                )
            ));
            
            // ------------------------------------------------------------//
            
            //Categoria
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(41) as $uni) {
                $op = array(
                    "" => ""
                );
                $op = $op + array(
                    $uni["id_valor_flexible"] => $uni["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $AplicarForm->add(array(
                'name' => 'id_categoria',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Categoría dentro de la convocatoria: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'required' => 'required'
                )
            ));
            
            // ------------------------------------------------------------//
            
            //Programa de Investigación
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(30) as $uni) {
                $op = array(
                    "" => ""
                );
                
                $op = $op + array(
                    $uni["id_valor_flexible"] => $uni["descripcion_valor"]
                );
                
                $opciones = $opciones + $op;
            }
            $AplicarForm->add(array(
                'name' => 'id_programa_inv',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Programa de Investigación : ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'required' => 'required'
                )
            ));
            
            // ------------------------------------------------------------//
            
            //Campos de Investigación
            
            $id2 = (int) $this->params()->fromRoute('id2', 1);
                        
            $vf = new Agregarvalflex($this->dbAdapter);
            $resvf = $vf->getValoresflexid($id2);
            $opciones = array();
            
            foreach ($vf->getArrayvalores(37) as $xx) {
                
                $opRes = array(
                    "" => ""
                );
                
                $opciones = $opciones + $opRes;
                
                $op = array(
                    $xx["id_valor_flexible"] => $xx["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            
            $AplicarForm->add(array(
                'id' => 'campo',
                'name' => 'id_campo',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'id_campo',
                    'onchange' => 'myFunction(this.id);',
                    'required' => 'required'
                ),
                'options' => array(
                    'label' => 'Campo de Investigación : ',
                    'value_options' => $opciones
                )
            ));
            
            // ------------------------------------------------------------//
            
            //Lineas de Investigación
            
            $id3 = (int) $this->params()->fromRoute('id3', 1);
            $resvf = $vf->getValoresflexid($id3);
            
            $vf = new Agregarvalflex($this->dbAdapter);
            
            $opciones = array();
            
            foreach ($vf->getArrayvalores(40) as $xx) {
                
                $opRes = array(
                    "" => ""
                );
                
                $opciones = $opciones + $opRes;
                
                $op = array(
                    $xx["id_valor_flexible"] . '-' . $xx["valor_flexible_padre_id"] => $xx["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            
            $AplicarForm->add(array(
                'name' => 'id_linea_inv',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'linea_inv',
                    'disabled' => 'disabled',
                    'required' => 'required'                   
                ),
                'options' => array(
                    'label' => 'Línea de Investigación : ',
                    'value_options' => $opciones
                )
            ));
            
            // ------------------------------------------------------------//
            
            // Unidad Academica
            
            $id4 = (int) $this->params()->fromRoute('id4', 1);  
            $v_uni = $id3;
            
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
            
            $AplicarForm->add(array(
                'name' => 'id_unidad_academica',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'id_unidad_academica',
                    'onchange' => 'myFunction2();',
                    'required' => 'required'
                ),
                'options' => array(
                    'label' => 'Unidad Académica: ',
                    'value_options' => $opciones
                )
            ));
            
            // ------------------------------------------------------------//
            
            // Dependencia Academica
            
            $id4 = (int) $this->params()->fromRoute('id4', 1);
            $v_dep = $id4;
            
            $vf = new Agregarvalflex($this->dbAdapter);
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
            $AplicarForm->add(array(
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
            $AplicarForm->add(array(
                'name' => 'id_programa_academico',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'id_programa_academico',
                    'disabled' => 'disabled',
                    'required' => 'required'
                ),
                'options' => array(
                    'label' => 'Programa Académica : ',
                    'value_options' => $opciones
                )
            ));
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(42) as $uni) {
                $op = array(
                    "" => ""
                );
                $op = $op + array(
                    $uni["id_valor_flexible"] => $uni["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            
            $AplicarForm->add(array(
                'name' => 'id_area_tematica',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Area Tematica : ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'required' => 'required'
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
                    'form' => $AplicarForm,
                    'titulo' => "Aplicar a la convocatoria",
                    'url' => $this->getRequest()->getBaseUrl(),
                    'terminos' => "Una vez aplique a la convocatoria acepta los terminos y condiciones estipulados.",
                    'datos' => $u->getAplicar($id),
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
    
    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Aplicar($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarAplicar($id);
        $this->flashMessenger()->addMessage("Archivo eliminado con exito");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editargrupoinv/index/' . $id2);
    }

    public function delAction()
    {
        $AplicarForm = new AplicarForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Aplicar($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $AplicarForm,
            'titulo' => "Eliminar registro",
            'url' => $this->getRequest()->getBaseUrl(),
            'datos' => $id,
            'id2' => $id2
        ));
        return $view;
    }
}