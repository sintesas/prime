<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Excel\ExcelmonitoresForm;
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
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Proyectosext;
use Application\Modelo\Entity\Redes;
use Application\Modelo\Entity\Reconocimientos;
use Application\Modelo\Entity\Articulos;
use Application\Modelo\Entity\Bibliograficos;
use Application\Modelo\Entity\Integrantes;
use Application\Modelo\Entity\Autores;
use Application\Modelo\Entity\Monitor;
use Application\Modelo\Entity\Proyectosinv;
use Application\Modelo\Entity\Convocatoria;

class ExcelmonitoresController extends AbstractActionController
{

    private $auth;

    public $dbAdapter;

    public function __construct()
    {
        $this->auth = new AuthenticationService();
    }

    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            
            // Creacion adaptador de conexion, objeto de datos, auditoria
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $not = new Aplicarm($this->dbAdapter);
            $not2 = new Monitor($this->dbAdapter);
            $p = new Proyectosinv($this->dbAdapter);
            $u = new Aplicarm($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            $auth = $this->auth;
            $ExcelmonitoresForm = new ExcelmonitoresForm();
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
            $filter = new StringTrim();
            // define el campo ciudad
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
            $ExcelmonitoresForm->add(array(
                'name' => 'facultad',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Facultad : ',
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
            $ExcelmonitoresForm->add(array(
                'name' => 'programa',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Programa : ',
                    'value_options' => $opciones
                )
            ));
            
            $ExcelmonitoresForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'aprobados',
                'options' => array(
                    'label' => 'Elegidos',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));

            $ExcelmonitoresForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'inscritos',
                'options' => array(
                    'label' => 'Inscritos que cumplen con los requisitos aptos para entrevista',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));

            $ExcelmonitoresForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'todos',
                'options' => array(
                    'label' => 'Inscritos aplicantes de la convocatoria',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));

            $vf = new Convocatoria($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getConvocatoriaMonitores() as $dept) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $filter->filter($dept["id_convocatoria"]) => $filter->filter($dept["id_convocatoria"])." - ".$filter->filter($dept["titulo"])
                );
                $opciones = $opciones + $op;
            }
            $ExcelmonitoresForm->add(array(
            'name' => 'id_convocatoria',
            'type' => 'Zend\Form\Element\Select',
                    'options' => array(
                        'label' => 'ID Convocatoria:',
                        'value_options' => $opciones
                    ),
                   'attributes' => array(
                       'id' => 'id_convocatoria'
                   ),
            ));

            $ExcelmonitoresForm->add(array(
            'name' => 'ano',
            'type' => 'number',
                    'options' => array(
                        'label' => 'Año de apertura de la convocatoria:',
                        'value_options' => $opciones
                    ),
                   'attributes' => array(
                       'id' => 'ano'
                   ),
            ));
            
            
            $valflex = new Agregarvalflex($this->dbAdapter);
            $usuario = new Usuarios($this->dbAdapter);
            $view = new ViewModel(array(
                'form' => $ExcelmonitoresForm,
                'titulo' => "Excel de Monitores",
                'facultad' => $data->facultad,
                'programa' => $data->programa,
                'aprobados' => $data->aprobados,
                'inscritos' => $data->inscritos,
                'id_convocatoria' => $data->id_convocatoria,
                'ano' => $data->ano,
                'todos' => $data->todos,
                'url' => $this->getRequest()->getBaseUrl(),
                'datos' => $u->getAplicarmExcel($data->facultad, $data->programa, $data->id_convocatoria, $data->todos),
                'info' => $u->getAplicarh(),
                'consulta' => 1,
                'usuarios' => $usuario->getArrayusuarios(),
                'menu' => $dat["id_rol"]
            ));
            return $view;
        } else {
            $ExcelmonitoresForm = new ExcelmonitoresForm();
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
            
            // define el campo ciudad
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
            $ExcelmonitoresForm->add(array(
                'name' => 'facultad',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Facultad : ',
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
            $ExcelmonitoresForm->add(array(
                'name' => 'programa',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Programa : ',
                    'value_options' => $opciones
                )
            ));
            
            $ExcelmonitoresForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'aprobados',
                'options' => array(
                    'label' => 'Elegidos',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));

            $ExcelmonitoresForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'inscritos',
                'options' => array(
                    'label' => 'Inscritos que cumplen con los requisitos aptos para entrevista',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));

            $ExcelmonitoresForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'todos',
                'options' => array(
                    'label' => 'Inscritos aplicantes de la convocatoria',
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
                $pantalla = "excelmonitores";
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

            $vf = new Convocatoria($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getConvocatoriaMonitores() as $dept) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $filter->filter($dept["id_convocatoria"]) => $filter->filter($dept["id_convocatoria"])." - ".$filter->filter($dept["titulo"])
                );
                $opciones = $opciones + $op;
            }
            $ExcelmonitoresForm->add(array(
            'name' => 'id_convocatoria',
            'type' => 'Zend\Form\Element\Select',
                    'options' => array(
                        'label' => 'ID Convocatoria:',
                        'value_options' => $opciones
                    ),
                   'attributes' => array(
                       'id' => 'id_convocatoria'
                   ),
            ));

            $ExcelmonitoresForm->add(array(
            'name' => 'ano',
            'type' => 'number',
                    'options' => array(
                        'label' => 'Año de apertura de la convocatoria:',
                        'value_options' => $opciones
                    ),
                   'attributes' => array(
                        'min'   =>  '2000', 
                        'max'   =>  '2070',
                       'id' => 'ano'
                   ),
            ));


            $valflex = new Agregarvalflex($this->dbAdapter);
            $usuario = new Usuarios($this->dbAdapter);
            $view = new ViewModel(array(
                'form' => $ExcelmonitoresForm,
                'titulo' => "Listado de estudiantes inscritos - Elegibles programación para entrevista / Excel",
                'url' => $this->getRequest()->getBaseUrl(),
                'datos' => $u->getAplicarh(),
                'info' => $u->getAplicarh(),
                'usuarios' => $usuario->getArrayusuarios(),
                'menu' => $dat["id_rol"]
            ));
            return $view;
        }
    }

    public function reportAction()
    {
        if ($this->getRequest()->isPost()) {
            echo "post";
        } else {
            $facultad = $this->params()->fromRoute('id');
            $programa = $this->params()->fromRoute('id2');
            $aprobados = $this->params()->fromRoute('id3');
            $id_convocatoria = (int)$this->params()->fromRoute('id5', 0);
            $ano = (int)$this->params()->fromRoute('id6', 0);
            $todos = $this->params()->fromRoute('id7');
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $mon = new Monitor($this->dbAdapter);
            $apl = new Aplicarm($this->dbAdapter);
            $u = new Usuarios($this->dbAdapter);
            $proyectoInv = new Proyectosinv($this->dbAdapter);        
            $r = '';
            $prT = new Proyectos ( $this->dbAdapter );
            $vf = new Agregarvalflex($this->dbAdapter);

            $view = new ViewModel(array(
                'datos' => $apl->getAplicarmExcel($facultad, $programa, $id_convocatoria, $todos),
                'datos3' => $u->getArrayusuarios(),
                'datos4' => $proyectoInv->getProyectosinvs(),
                'aprobados' => $aprobados,
                'todos' => $todos,
                'ano' => $ano,
                'programas' => $vf->getArrayvalores(34),
                'prT' =>  $prT->getProyectoh(),
            ));
            $view->setTerminal(true);
            return $view;
        }
    }
}
