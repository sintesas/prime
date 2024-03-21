<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Pdf\PdfmonitoresForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Grupoinvestigacion;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Lineas;
use Application\Modelo\Entity\Libros;
use DOMPDFModule\View\Model\PdfModel;
use Application\Modelo\Entity\Proyectosext;
use Application\Modelo\Entity\Redes;
use Application\Modelo\Entity\Reconocimientos;
use Application\Modelo\Entity\Articulos;
use Application\Modelo\Entity\Bibliograficos;
use Application\Modelo\Entity\Integrantes;
use Application\Modelo\Entity\Autores;
use Application\Modelo\Entity\Monitor;
use Application\Modelo\Entity\Proyectosinv;
use Application\Modelo\Entity\Proyectos;

class PdfmonitoresController extends AbstractActionController
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
            $not = new Aplicarm($this->dbAdapter);
            $not2 = new Monitor($this->dbAdapter);
            $p = new Proyectosinv($this->dbAdapter);
             $prT = new Proyectos ( $this->dbAdapter );
            
            $auth = $this->auth;
            
            // verifica si esta conectado
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            $rol = new Roles($this->dbAdapter);
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
            }
            
            $data = $this->request->getPost();
            $usuario = new Usuarios($this->dbAdapter);
            
            // Instantiate new PDF Model
            $pdf = new PdfModel();
            
            // set filename
            $pdf->setOption('filename', 'monitores.pdf');
            
            // Defaults to "8x11"
            $pdf->setOption('paperSize', 'a4');
            
            // paper orientation
            $pdf->setOption('paperOrientation', 'landscape');

            $pdf->setVariables(array(
                'titulo' => "Consulta Grupos Investigación",
                //'datos' => $not->filtroMonitores($data),
                'datos' => $not->getAplicarmPDF($data),
                'url' => $this->getRequest()->getBaseUrl(),
                'elegibles' => $data->elegibles,
                'seleccionados' => $data->seleccionados,
                'info' => $data,
                'usuarios' => $usuario->getArrayusuarios(),
                'proy' => $p->getProyectosinvs(),
                'consulta' => 1,
                'prT' =>  $prT->getProyectoh(),
                'menu' => $dat["id_rol"],
                'pdf' => 'S'
            ));
            
            return $pdf;
        } else {
            $PdfmonitoresForm = new PdfmonitoresForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Aplicarm($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $filter = new StringTrim();
            
            // define el campo facultad
            $vf = new Aplicarm($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getAplicarmFacultad() as $dept) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $filter->filter($dept["id_facultad"]) => $filter->filter($dept["id_facultad"])
                );
                $opciones = $opciones + $op;
            }
            $PdfmonitoresForm->add(array(
                'name' => 'facultad',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Facultad: ',
                    'value_options' => $opciones
                )
            ));
            
            $vf = new Aplicarm($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getAplicarmPrograma() as $dept) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $filter->filter($dept["id_programa_curricular"]) => $filter->filter($dept["id_programa_curricular"])
                );
                $opciones = $opciones + $op;
            }
            $PdfmonitoresForm->add(array(
                'name' => 'programa',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Programa : ',
                    'value_options' => $opciones
                )
            ));
            
            $PdfmonitoresForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'elegibles',
                'attributes'=> array(
                    'id'  => 'elegibles'
                ),
                'options' => array(
                    'label' => 'Inscritos elegibles',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));

            $PdfmonitoresForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'seleccionados',
                'attributes'=> array(
                    'id'  => 'seleccionados'
                ),
                'options' => array(
                    'label' => 'Seleccionados',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
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
                $pantalla = "Consultagi";
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
                
                $valflex = new Agregarvalflex($this->dbAdapter);
                $usuario = new Usuarios($this->dbAdapter);
                $view = new ViewModel(array(
                    'form' => $PdfmonitoresForm,
                    'titulo' => "Listado de estudiantes inscritos - Elegibles programación para entrevista",
                    'url' => $this->getRequest()->getBaseUrl(),
                    'datos' => $u->getAplicarh(),
                    'info' => $u->getAplicarh(),
                    'usuarios' => $usuario->getArrayusuarios(),
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
