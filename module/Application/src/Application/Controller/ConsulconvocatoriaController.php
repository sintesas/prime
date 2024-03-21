<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\ConsulconvocatoriaForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Asignareval;
use Application\Modelo\Entity\Usuarios;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Lineas;
use Application\Modelo\Entity\Libros;
use Application\Modelo\Entity\Proyectosext;
use Application\Modelo\Entity\Cronograma;
use Application\Modelo\Entity\Archivosconv;
use Application\Modelo\Entity\Aplicar;
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Rolesconv;
use Application\Modelo\Entity\Url;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class ConsulconvocatoriaController extends AbstractActionController
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
            $ConsulconvocatoriaForm = new ConsulconvocatoriaForm();
            // Creacion adaptador de conexion, objeto de datos, auditoria
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $not = new Convocatoria($this->dbAdapter);
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
            
            date_default_timezone_set('America/Bogota');
            $fecha_hoy = date("Y-m-d H:i");
            //echo $fecha_hoy;//."&&".$r["fecha_cierre"];
            $res = $not->filtroConvocatoria($data);

            if (count($res)>0){
                foreach ($res as $r) {
                    $r["fecha_cierre"] = $r["fecha_cierre"]." ".$r["hora_cierre"];
                    if ($r["fecha_cierre"] <= $fecha_hoy && ($r["id_estado"] == 'P' || $r["id_estado"] == 'B')) {
                        $not->updateestado($r["id_convocatoria"], 'R');
                    }
                    $id_convocatoria = $r["id_convocatoria"];
                }
            }

            $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
            $evento = 'Consulta de convocatoria interna: ' . $id_convocatoria . ' (mgc_convocatoria)';
            $ad->addAuditoriadet($evento, $resul);

            // define el campo ciudad
            $ConsulconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'id_estado',
                'required' => 'required',
                'options' => array(
                    'label' => 'Estado:',
                    'value_options' => array(
                        '' => 'Seleccione',
                        'A' => 'En construcción',
                        'B' => 'Abierta',
                        'P' => 'Con aplicaciones',
                        'R' => 'Cerrada',
                        'H' => 'Archivada',
                        'N' => 'Anulada'
                    )
                ),
                'attributes' => array(
                    'value' => ''
                ) // set selected to '1'

            ));
            
            $ConsulconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'id_tipo_conv',
                'required' => 'required',
                'options' => array(
                    'label' => 'Tipo de Convocatoria:',
                    'value_options' => array(
                        '' => 'Seleccione',
                        's' => 'Especial',
                        'm' => 'De monitores',
                        'i' => 'Interna',
                        'e' => 'Externa'
                    )
                ),
                'attributes' => array(
                    'value' => ''
                ) // set selected to '1'

            ));
            
            // adiciona la noticia
            $evaluador = new Asignareval($this->dbAdapter);
            
            $usuario = new Usuarios($this->dbAdapter);
            $proyext = new Proyectosext($this->dbAdapter);
            $valflex = new Agregarvalflex($this->dbAdapter);
            $Cronograma = new Cronograma($this->dbAdapter);
            $Archivosconv = new Archivosconv($this->dbAdapter);
            $Url = new Url($this->dbAdapter);
            $ap = new Aplicar($this->dbAdapter);
            $apm = new Aplicarm($this->dbAdapter);
            $rolesConv = new Rolesconv($this->dbAdapter);
            // redirige a la pantalla de inicio del controlador
            $view = new ViewModel(array(
                'form' => $ConsulconvocatoriaForm,
                'titulo' => "Consulta de convocatorias",
                'datos' => $not->filtroConvocatoria($data),
                'evaluador' => $evaluador->getAsignarevalt(),
                'usuario' => $usuario->getArrayusuarios(),
                'url' => $this->getRequest()->getBaseUrl(),
                'ap' => $ap->getAplicarh(),
                'apm' => $apm->getAplicarh(),
                'valflex' => $valflex->getValoresf(),
                'Cronograma' => $Cronograma->getCronogramas(),
                'Urls' => $Url->getUrls(),
                'Archivosconv' => $Archivosconv->getArchivosconvs(),
                'consulta' => 1,
                'menu' => $dat["id_rol"],
                'rolesConv' => $rolesConv->getAllRolesconv(),
                'idUsuario' => $identi->id_usuario,
                'rolUsuario' => $rolusuario->getRolUsuario($identi->id_usuario)[0]["id_rol"]
            ));
            return $view;
        } else {
            
            $ConsulconvocatoriaForm = new ConsulconvocatoriaForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Convocatoria($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            $auth = $this->auth;
            
            $result = $auth->hasIdentity();
            
            $identi = $auth->getStorage()->read();
            
            $filter = new StringTrim();
            
            // define el campo ciudad
            $ConsulconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'id_estado',
                'required' => 'required',
                'options' => array(
                    'label' => 'Estado:',
                    'value_options' => array(
                        '' => 'Seleccione',
                        'A' => 'En construcción',
                        'B' => 'Abierta',
                        'P' => 'Con aplicaciones',
                        'R' => 'Cerrada',
                        'H' => 'Archivada',
                        'N' => 'Anulada'
                    )
                ),
                'attributes' => array(
                    'value' => ''
                ) // set selected to '1'
            ));
            
            $ConsulconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'id_tipo_conv',
                'required' => 'required',
                'options' => array(
                    'label' => 'Tipo de Convocatoria:',
                    'value_options' => array(
                        '' => 'Seleccione',
                        's' => 'Especial',
                        'm' => 'De monitores',
                        'i' => 'Interna',
                        'e' => 'Externa'
                    )
                ),
                'attributes' => array(
                    'value' => ''
                ) // set selected to '1'

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
            
            if ($roles_1 != '') {
                // me verifica el permiso sobre la pantalla
                $pantalla = "consultaconvocatoria";
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
                $libros = new Libros($this->dbAdapter);
                $lineas = new Lineas($this->dbAdapter);
                $valflex = new Agregarvalflex($this->dbAdapter);
                $Cronograma = new Cronograma($this->dbAdapter);
                $Archivosconv = new Archivosconv($this->dbAdapter);
                $Url = new Url($this->dbAdapter);
                $ap = new Aplicar($this->dbAdapter);
                $apm = new Aplicarm($this->dbAdapter);
                $view = new ViewModel(array(
                    'form' => $ConsulconvocatoriaForm,
                    'titulo' => "Consulta de convocatorias",
                    'titulo2' => "Tipos Valores Existentes",
                    'url' => $this->getRequest()->getBaseUrl(),
                    'Cronograma' => $Cronograma->getCronogramas(),
                    'Urls' => $Url->getUrls(),
                    'Archivosconv' => $Archivosconv->getArchivosconvs(),
                    'datos' => $u->getConvocatoria(),
                    'ap' => $ap->getAplicarh(),
                    'apm' => $apm->getAplicarh(),
                    'valflex' => $valflex->getValoresf(),
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
}
