<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Solicitudes\SolicitudesForm;
use Application\Gestionarsolicitudes\GestionarsolicitudesForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Solicitudes;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;

class GestionarsolicitudesController extends AbstractActionController
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
        # Para pantallas accesadas por el menu, debo reiniciar el navegador
        session_start();
        if(isset($_SESSION['navegador'])){unset($_SESSION['navegador']);}
        
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
            $u = new Solicitudes($this->dbAdapter);
            $auth = $this->auth;
            $GestionarsolicitudesForm = new GestionarsolicitudesForm();
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $data = $this->request->getPost();
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(43) as $dept) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $dept["id_valor_flexible"] => $dept["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $GestionarsolicitudesForm->add(array(
                'name' => 'filtrosolicitud',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Filtro Solicitud : ',
                    'value_options' => $opciones
                )
            ));
            $GestionarsolicitudesForm->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'filtroestado',
                'options' => array(
                    'label' => 'Filtro Estado',
                    'value_options' => array(
                        '0' => '',
                        '1' => 'Enviado',
                        '2' => 'En gestion',
                        '3' => 'Tramitado'
                    )
                ),
                'attributes' => array(
                    'value' => '0'
                ) // set selected to '1'

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
                $pantalla = "gestionarsolicitudes";
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

            
            $valflex = new Agregarvalflex($this->dbAdapter);
            $users = new Usuarios($this->dbAdapter);
            $view = new ViewModel(array(
                'form' => $GestionarsolicitudesForm,
                'titulo' => "Gestionar Solicitudes",
                'datos' => $u->filtroSolicitudes($data),
                'url' => $this->getRequest()->getBaseUrl(),
                'usuarios' => $users->getArrayusuarios(),
                'valflex' => $valflex->getValoresf(),
                'data' => $data,
                'menu' => $dat["id_rol"]
            ));
            return $view;
        } else {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Solicitudes($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            $GestionarsolicitudesForm = new GestionarsolicitudesForm();
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
                $pantalla = "gestionarsolicitudes";
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
            $opciones = array();
            foreach ($vf->getArrayvalores(43) as $dept) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $dept["id_valor_flexible"] => $dept["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $GestionarsolicitudesForm->add(array(
                'name' => 'filtrosolicitud',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Filtro Solicitud : ',
                    'value_options' => $opciones
                )
            ));
            
            $GestionarsolicitudesForm->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'filtroestado',
                'options' => array(
                    'label' => 'Filtro Estado',
                    'value_options' => array(
                        '0' => '',
                        '1' => 'Enviado',
                        '2' => 'En gestion',
                        '3' => 'Tramitado'
                    )
                ),
                'attributes' => array(
                    'value' => '0'
                ) // set selected to '1'

            ));
            
                $valflex = new Agregarvalflex($this->dbAdapter);
                $users = new Usuarios($this->dbAdapter);
                $view = new ViewModel(array(
                    'form' => $GestionarsolicitudesForm,
                    'titulo' => "Gestionar Solicitudes",
                    'datos' => $u->getSolicitudes(),
                    'url' => $this->getRequest()->getBaseUrl(),
                    'usuarios' => $users->getArrayusuarios(),
                    'valflex' => $valflex->getValoresf(),
                    'menu' => $dat["id_rol"]
                ));
                return $view;
        }
    }
}