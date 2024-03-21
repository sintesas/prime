<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Proyectos\ConsultaproyectoForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Asignareval;
use Application\Modelo\Entity\Evaluar;
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
use Application\Modelo\Entity\Convocatoria;

class ConsultaproyectoController extends AbstractActionController
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
        
        if ($this->getRequest()->isPost()) {
            // Creacion adaptador de conexion, objeto de datos, auditoria
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $not = new Proyectos($this->dbAdapter);
            $this->auth = new AuthenticationService();
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
            $ConsultaproyectoForm = new ConsultaproyectoForm();
            
            // define el campo ciudad
            
            // adiciona la noticia
            
            $convo = new Convocatoria($this->dbAdapter);
            $usuario = new Usuarios($this->dbAdapter);
            $proyext = new Proyectosext($this->dbAdapter);
            $eval = new Evaluar($this->dbAdapter);
            // redirige a la pantalla de inicio del controlador
            $view = new ViewModel(array(
                'form' => $ConsultaproyectoForm,
                'titulo' => "Consulta de proyectos",
                'datos' => $not->filtroProyectos($data),
                'id_usuario' => $identi->id_usuario,
                'usuario' => $usuario->getArrayusuarios(),
                'url' => $this->getRequest()->getBaseUrl(),
                'convocatorias' =>  $convo->getConvocatoria(),
                'consulta' => 1,
                'menu' => $dat["id_rol"],
                'data'=>$data
            ));
            return $view;
        } else {
            $ConsultaproyectoForm = new ConsultaproyectoForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Proyectos($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            
            $this->auth = new AuthenticationService();
            $auth = $this->auth;
            
            $result = $auth->hasIdentity();
            
            $identi = $auth->getStorage()->read();
            
            $filter = new StringTrim();
            
            // define el campo ciudad
            
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
                $pantalla = "consultaproyecto";
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
            $view = new ViewModel(array(
                'form' => $ConsultaproyectoForm,
                'titulo' => "Consulta de proyectos",
                'consulta' => 0,
                'url' => $this->getRequest()->getBaseUrl(),
                'id_usuario' => $identi->id_usuario,
                'menu' => $dat["id_rol"]
            ));
            return $view;
        }
    }
}
