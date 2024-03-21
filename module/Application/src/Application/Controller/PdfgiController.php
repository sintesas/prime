<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Pdf\PdfgiForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Grupoinvestigacion;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Lineas;
use Application\Modelo\Entity\Libros;
use DOMPDFModule\View\Model\PdfModel;
use Application\Modelo\Entity\Proyectosext;
use Application\Modelo\Entity\Redes;
use Application\Modelo\Entity\Reconocimientos;
use Application\Modelo\Entity\Articulos;
use Application\Modelo\Entity\Bibliograficos;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Integrantes;
use Application\Modelo\Entity\Autores;

class PdfgiController extends AbstractActionController
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
            $not = new Grupoinvestigacion($this->dbAdapter);
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
            $libros = new Libros($this->dbAdapter);
            $lineas = new Lineas($this->dbAdapter);
            
            $Redes = new Redes($this->dbAdapter);
            $Reconocimientos = new Reconocimientos($this->dbAdapter);
            $Articulos = new Articulos($this->dbAdapter);
            $Bibliograficos = new Bibliograficos($this->dbAdapter);
            $Integrantes = new Integrantes($this->dbAdapter);
            
            $proyext = new Proyectosext($this->dbAdapter);
            $valflex = new Agregarvalflex($this->dbAdapter);
            $usuario = new Usuarios($this->dbAdapter);
            $autores = new Autores($this->dbAdapter);
            
            // Instantiate new PDF Model
            $pdf = new PdfModel();
            
            // set filename
            $pdf->setOption('filename', 'grupos_investigacion.pdf');
            
            // Defaults to "8x11"
            $pdf->setOption('paperSize', 'a4');
            
            // paper orientation
            $pdf->setOption('paperOrientation', 'landscape');
            
            $pdf->setVariables(array(
                'titulo' => "Consulta grupos de investigación PDF",
                'datos' => $not->filtroGrupos($data, $lineas->getLinea($data)),
                'url' => $this->getRequest()
                    ->getBaseUrl(),
                'info' => $data,
                'datos2' => $lineas->getLineast(),
                'datos3' => $libros->getLibrost(),
                'valflex' => $valflex->getValoresf(),
                'datos4' => $proyext->getProyectosextt(),
                'datos5' => $Redes->getRedesi(),
                'datos6' => $Reconocimientos->getReconocimientosi(),
                'datos7' => $Articulos->getArticulosi(),
                'datos8' => $Bibliograficos->getBibliograficost(),
                'datos9' => $Integrantes->getIntegrantesi(),
                'datos10' => $autores->getAutoresi(),
                'usuarios' => $usuario->getArrayusuarios(),
                'consulta' => 1,
                'menu' => $dat["id_rol"],
                'pdf' => 'S'
            ));
            
            return $pdf;
        } else {
            $PdfgiForm = new PdfgiForm();
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
            
            // define el campo ciudad
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(23) as $uni) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $uni["id_valor_flexible"] => $uni["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $PdfgiForm->add(array(
                'name' => 'id_unidad_academica',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Unidad Académica : ',
                    'value_options' => $opciones
                )
            ));
            
            // define el campo ciudad
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(33) as $dept) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $dept["id_valor_flexible"] => $dept["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $PdfgiForm->add(array(
                'name' => 'id_dependencia_academica',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Dependencia Académica : ',
                    'value_options' => $opciones
                )
            ));
            
            // define el campo ciudad
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(34) as $dept) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $dept["id_valor_flexible"] => $dept["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $PdfgiForm->add(array(
                'name' => 'id_programa_academico',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Programa Académico : ',
                    'value_options' => $opciones
                )
            ));
            
            // define el campo ciudad
            $vf = new Lineas($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getLineast() as $dept) {
                $op = array(
                    0 => ''
                );
                $op = $op + array(
                    $dept["id_linea_inv"] => $dept["nombre_linea"]
                );
                $opciones = $opciones + $op;
            }
            $PdfgiForm->add(array(
                'name' => 'id_lineas',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Líneas de Investigación : ',
                    'value_options' => $opciones
                )
            ));
            
            $PdfgiForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'libros',
                'options' => array(
                    'label' => 'Libros',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $PdfgiForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'integrantes',
                'options' => array(
                    'label' => 'Integrantes',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $PdfgiForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'lineas',
                'options' => array(
                    'label' => 'Líneas',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $PdfgiForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'redes',
                'options' => array(
                    'label' => 'Redes',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $PdfgiForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'reconocimientos',
                'options' => array(
                    'label' => 'Reconocimientos',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $PdfgiForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'articulos',
                'options' => array(
                    'label' => 'Artículos',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $PdfgiForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'proyectosext',
                'options' => array(
                    'label' => 'Proyectos Externos',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $PdfgiForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'bibliograficos',
                'options' => array(
                    'label' => 'Documentos Bibliográficos',
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
                $libros = new Libros($this->dbAdapter);
                $lineas = new Lineas($this->dbAdapter);
                $proyext = new Proyectosext($this->dbAdapter);
                $valflex = new Agregarvalflex($this->dbAdapter);
                $Redes = new Redes($this->dbAdapter);
                $Reconocimientos = new Reconocimientos($this->dbAdapter);
                $Articulos = new Articulos($this->dbAdapter);
                $Bibliograficos = new Bibliograficos($this->dbAdapter);
                $Integrantes = new Integrantes($this->dbAdapter);
                $autores = new Autores($this->dbAdapter);
                
                $proyext = new Proyectosext($this->dbAdapter);
                $valflex = new Agregarvalflex($this->dbAdapter);
                $usuario = new Usuarios($this->dbAdapter);
                $view = new ViewModel(array(
                    'form' => $PdfgiForm,
                    'titulo' => "Consulta grupos de investigación PDF",
                    'titulo2' => "Tipos Valores Existentes",
                    'url' => $this->getRequest()->getBaseUrl(),
                    'datos' => $u->getGrupoinvestigacion(),
                    'info' => $u->getGrupoinvestigacion(),
                    'datos2' => $lineas->getLineast(),
                    'datos3' => $libros->getLibrost(),
                    'valflex' => $valflex->getValoresf(),
                    'consulta' => 0,
                    'datos4' => $proyext->getProyectosextt(),
                    'datos5' => $Redes->getRedesi(),
                    'datos6' => $Reconocimientos->getReconocimientosi(),
                    'datos7' => $Articulos->getArticulosi(),
                    'datos8' => $Bibliograficos->getBibliograficost(),
                    'datos9' => $Integrantes->getIntegrantesi(),
                    'datos10' => $autores->getAutoresi(),
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
