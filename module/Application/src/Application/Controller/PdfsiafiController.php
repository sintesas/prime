<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Pdf\PdfsiafiForm;
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
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Prueba;
use Application\Modelo\Entity\Pruebamares;
use Application\Modelo\Entity\Pruebamaresdet;
use Application\Modelo\Entity\Tablafinp;
use Application\Modelo\Entity\Convocatoria;

class PdfsiafiController extends AbstractActionController
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
            
            $PdfsiafiForm = new PdfsiafiForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db4');
            $pr = new Prueba($this->dbAdapter);
            $data = $this->request->getPost();
            $filter = new StringTrim();
            
            $this->dbAdapter1 = $this->getServiceLocator()->get('db1');
            $proy = new Proyectos($this->dbAdapter1);
            $codigo_p = null;
            $codigo_p = $proy->getCodigo($data->convocatoria, $data->programa, $data->unidad, $data->dependencia);
            
            // Instantiate new PDF Model
            $pdf = new PdfModel();
            
            // set filename
            $pdf->setOption('filename', 'Siafi.pdf');
            
            // Defaults to "8x11"
            $pdf->setOption('paperSize', 'a4');
            
            // paper orientation
            $pdf->setOption('paperOrientation', 'landscape');
            
            $pdf->setVariables(array(
                'titulo' => "Gestión Presupuestal",
                'p' => $pr->getPrueba($data->codigo, $codigo_p)
            ));
            
            $pdf->setVariables(array(
                'titulo' => "Gestión Presupuestal",
                'p' => $pr->getPrueba($data->codigo),
                'url' => $this->getRequest()
                    ->getBaseUrl(),
                'info' => $data,
                'consulta' => 1,
                'pdf' => 'S'
            ));
            
            return $pdf;
        } else {
            $PdfsiafiForm = new PdfsiafiForm();
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
            
            // define el campo programa academico
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
            $PdfsiafiForm->add(array(
                'name' => 'unidad',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Unidad Académica : ',
                    'value_options' => $opciones
                )
            ));
            
            // define el campo ciudad
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(33) as $conv) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $conv["id_valor_flexible"] => $conv["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $PdfsiafiForm->add(array(
                'name' => 'dependencia',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Dependencia Académica : ',
                    'value_options' => $opciones
                )
            ));
            
            // define el campo ciudad
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(34) as $conv) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $conv["id_valor_flexible"] => $conv["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $PdfsiafiForm->add(array(
                'name' => 'programa',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Programa Académico : ',
                    'value_options' => $opciones
                )
            ));
            
            
            $Convocatorias = new Convocatoria($this->dbAdapter);
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($Convocatorias->getConvocatoria() as $conv) {
                $op = array(
                    '' => ''
                );
                
                $op = $op + array(
                    $conv["id_convocatoria"] => $conv["titulo"]
                );
                $opciones = $opciones + $op;
            }
            $PdfsiafiForm->add(array(
                'name' => 'convocatoria',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Convocatoria : ',
                    'value_options' => $opciones
                )
            ));
            
            $PdfsiafiForm->add(array(
                'name' => 'vigencia',
                'attributes' => array(
                    'type' => 'text'
                ),
                'options' => array(
                    'label' => 'Vigencia :'
                )
            ));
            
            $PdfsiafiForm->add(array(
                'name' => 'codigo',
                'attributes' => array(
                    'type' => 'text',
                    'placeholder' => 'Ingrese el código del proyecto'
                )
                ,
                'options' => array(
                    'label' => 'Código Proyecto :'
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
                
                $view = new ViewModel(array(
                    'form' => $PdfsiafiForm,
                    'titulo' => "Consulta Presupuesto SIAFI",
                    'titulo2' => "Tipos Valores Existentes",
                    'url' => $this->getRequest()->getBaseUrl(),
                    'datos' => $u->getGrupoinvestigacion(),
                    
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
