<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Proyectos\EditarmonitorForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Monitor;
use Application\Modelo\Entity\Tablafinproy;
use Application\Modelo\Entity\Camposaddproy;
use Application\Modelo\Entity\Talentohumano;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Informesm;
use Application\Modelo\Entity\Requisitos;
use Application\Modelo\Entity\Aspectoeval;
use Application\Modelo\Entity\Requisitosdoc;
use Application\Modelo\Entity\Requisitosap;
use Application\Modelo\Entity\Requisitosapdoc;
use Application\Modelo\Entity\Actasm;
use Application\Modelo\Entity\Rolesconv;
use Application\Modelo\Entity\Archivosconv;
use Application\Modelo\Entity\Url;
use Application\Modelo\Entity\Tablafinp;
use Application\Modelo\Entity\Camposadd;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Gruposparticipantes;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;
use Application\Modelo\Entity\Proyectosinv;

class EditarmonitorController extends AbstractActionController
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
        if ($this->getRequest()->isPost()) {
            $this->auth = new AuthenticationService();
            $auth = $this->auth;
            
            // verifica si esta conectado
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            // Creacion adaptador de conexion, objeto de datos, auditoria
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $not = new Monitor($this->dbAdapter);
            
            $rol = new Roles($this->dbAdapter);
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
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
            // obtiene la informacion de las pantallas
            $data = $this->request->getPost();
            $EditarmonitorForm = new EditarmonitorForm();
            // adiciona la noticia
            $id = (int) $this->params()->fromRoute('id', 0);
            $resultado = $not->updateMonitor($id, $data);
            
            // redirige a la pantalla de inicio del controlador
            if ($resultado == 1) {
                
                $this->flashMessenger()->addMessage("Proyecto actualizado con éxito.");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/editarmonitor/index/' . $id);
            } else {
                $this->flashMessenger()->addMessage("La creacion de la convocatoria falló.");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/convocatoriai/index');
            }
        } else {
            $this->auth = new AuthenticationService();
            $auth = $this->auth;
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $EditarmonitorForm = new EditarmonitorForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Monitor($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            
            $filter = new StringTrim();
            
            $id = (int) $this->params()->fromRoute('id', 0);
            
            if ($id != 0) {
                foreach ($u->getMonitor($id, 'i') as $dat) {
                    $id_conv = $dat["id_proyecto"];
                }
            } else {
                foreach ($u->getMonitorh() as $dat) {
                    $id_conv = $dat["id_proyecto"];
                }
            }
            
            $usuaid = new Usuarios($this->dbAdapter);
            $pi = new Proyectosinv($this->dbAdapter);
            $res_us = $usuaid->getArrayusuariosid($dat["id_usuario"]);
            
            $opciones = array(
                $dat["id_usuario"] => $res_us["primer_nombre"] . ' ' . $res_us["primer_apellido"]
            );
            $EditarmonitorForm->add(array(
                'name' => 'id_investigador',
                'type' => 'Zend\Form\Element\Select',
                'disabled' => 'True',
                'options' => array(
                    'label' => 'Investigador Principal: ',
                    'value_options' => $opciones
                )
            ));
            
            $nombreProyecto = "";
            
            foreach ($pi->getProyectosid($dat["id_proyecto"]) as $pro) {
                $nombreProyecto = $pro["nombre_proyecto"];
            }
            
            $opciones2 = array(
                $dat["id_proyecto"] => $nombreProyecto
            );
            
            $EditarmonitorForm->add(array(
                'name' => 'id_proyecto',
                'type' => 'Zend\Form\Element\Select',
                'disabled' => 'True',
                'options' => array(
                    'label' => 'Proyecto de Investigación: ',
                    'value_options' => $opciones2
                )
            ));
            
            $EditarmonitorForm->add(array(
                'name' => 'num_codigo',
                'attributes' => array(
                    'type' => 'text',
                    'disabled' => 'True',
                    'value' => $filter->filter($dat["num_codigo"])
                ),
                'options' => array(
                    'label' => 'Código del estudiante:'
                )
            ));
            
            $EditarmonitorForm->add(array(
                'name' => 'id_facultad',
                'attributes' => array(
                    'type' => 'text',
                    'disabled' => 'True',
                    'value' => $filter->filter($dat["id_facultad"])
                ),
                'options' => array(
                    'label' => 'Facultad:'
                )
            ));
            
            $EditarmonitorForm->add(array(
                'name' => 'id_programa_curricular',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($dat["id_programa_curricular"]),
                    'disabled' => 'True'
                ),
                'options' => array(
                    'label' => 'Programa Curricular:'
                )
            ));
            
            $EditarmonitorForm->add(array(
                'name' => 'observaciones',
                'attributes' => array(
                    'type' => 'textarea',
                    'placeholder' => 'Ingrese la observación de la aprobación',
                    'lenght' => 500,
                    'value' => $filter->filter($dat["observaciones"])
                ),
                'options' => array(
                    'label' => 'Observación de la aprobación:'
                )
            ));
            
            $EditarmonitorForm->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'id_estado',
                'required' => 'required',
                'options' => array(
                    'label' => 'Estado',
                    'value_options' => array(
                        '' => 'Seleccione',
                        '1' => 'Creado',
                        '2' => 'Aprobado',
                        '3' => 'Cerrado'
                    )
                ),
                'attributes' => array(
                    'value' => $dat["id_estado"]
                )
            ));
            
            $est = $dat["id_estado"];
            
            $us = new Usuarios($this->dbAdapter);
            foreach ($us->getUsuarios($identi->id_usuario) as $usid) {
                $nombre = $usid["primer_nombre"] . ' ' . $usid["primer_apellido"];
                $opciones = array(
                    $usid["id_usuario"] => $nombre
                );
            }
            
            $EditarmonitorForm->add(array(
                'name' => 'id_usuario',
                'type' => 'Zend\Form\Element\Select',
                'disabled' => 'True',
                'options' => array(
                    'label' => 'Investigador Principal: ',
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
                $pantalla = "editaraplicar";
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
                $id = (int) $this->params()->fromRoute('id', 0);
                if ($id == 0) {
                    $id_conv = $id_conv;
                } else {
                    $id_conv = $id;
                }
                $Informe = new Informesm($this->dbAdapter);
                
                $Tablafinproy = new Tablafinp($this->dbAdapter);
                $valflex = new Agregarvalflex($this->dbAdapter);
                $tablae = new Tablaequipop($this->dbAdapter);
                $Archivosconv = new Archivosconv($this->dbAdapter);
                $Propuesta_inv = new Actasm($this->dbAdapter);
                $a = '';
                $view = new ViewModel(array(
                    'form' => $EditarmonitorForm,
                    'titulo' => "Editar Monitor de Investigación",
                    
                    'ver' => $id2 = $this->params()->fromRoute('id2'),
                    'url' => $this->getRequest()->getBaseUrl(),
                    'Informe' => $Informe->getCronogramah($id),
                    'Archivosconv' => $Archivosconv->getArchivosconv($id),
                    'arch' => $Propuesta_inv->getArchivos($id),
                    
                    'Tablafinper' => $Tablafinproy->getArrayfinanciaper($id_conv),
                    'tablaeper' => $tablae->getArrayequiposper($id),
                    'estado' => $est,
                    
                    'usua' => $us->getUsuarios($a),
                    'Tablafinproys' => $Tablafinproy->getTablafin($id),
                    'tablae' => $tablae->getTablaequipo($id),
                    'Tablafinproy' => $Tablafinproy->getTablafin($id),
                    'Tablafinrubro' => $Tablafinproy->getArrayfinancia($id),
                    'valflex' => $valflex->getValoresf(),
                    'id' => $id_conv,
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