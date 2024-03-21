<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\ReporteconvocatoriaForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Proyectos;
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
use Application\Modelo\Entity\Informes;
use Application\Modelo\Entity\Asignareval;
use Application\Modelo\Entity\Evaluar;
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Url;


class ReporteconvocatoriaController extends AbstractActionController
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
        
        if ($this->getRequest()->isPost()){
            $dato = $this->request->getPost();
            if($dato["ano"]==""){
                $dato["ano"]=0;
            }
            if($dato["id_convocatoria"]==""){
                $dato["id_convocatoria"]=0;
            }
            
            if($dato["nombre"]==""){
                $dato["nombre"]=0;
            }

            if($dato["id_categoria"]==""){
                $dato["id_categoria"]=0;
            }

            $url="/".$dato["ano"]."/".$dato["id_convocatoria"]."/".$dato["nombre"]."/".$dato["id_tipo_conv"]."/".$dato["id_estado"]."/".$dato["id_unidad_academica"]."/".$dato["id_dependencia_academica"]."/".$dato["id_programa_academico"]."/".$dato["id_categoria"]."/".$dato["codigo"]."/".$dato["resumen"]."/".$dato["titulo_conv"]."/".$dato["investigador"]."/".$dato["categoria"]."/".$dato["elider"]."/".$dato["evaluador"]."/".$dato["objetivo"]."/".$dato["unideppro"]."/".$dato["solicitado"]."/".$dato["duracion"]."/".$dato["campo"];
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/reporteconvocatoria/report'.$url);

            //return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/mensajeadministrador/index');
        /*  $data = $this->request->getPost();
            echo $data["tipo"]."Tipo reporte";
            $view = new ViewModel(array(
                'toe' => '',
                'titulos' => "Reporte de convocatoria interna y externa",
                'titulo2' => "Tipos Valores Existentes",
                'url' =>"",
                'Cronograma' => "",
                'Urls' => "",
                'Archivosconv' => "",
                'ap' => "",
                'apm' => "",
                'valflex' => "",
                'consulta' => 1,
                'menu' => "1"
            ));
            return $view;
        */
        }else{
            $ReporteconvocatoriaForm = new ReporteconvocatoriaForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Proyectos($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            $auth = $this->auth;
            
            $result = $auth->hasIdentity();
            
            $identi = $auth->getStorage()->read();
            
            $filter = new StringTrim();
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(23) as $uni) {
                $opciones = $opciones + array(
                    '0' => ''
                );
                $opciones = $opciones + array(
                    $uni["id_valor_flexible"] => $uni["descripcion_valor"]
                );
            }
            $ReporteconvocatoriaForm->add(array(
                'name' => 'id_unidad_academica',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Unidad Académica : ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'id' => 'id_unidad_academica'
                )
            ));


            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(33) as $uni) {
                $opciones = $opciones + array(
                    '0' => ''
                );
                $opciones = $opciones + array(
                    $uni["id_valor_flexible"] => $uni["descripcion_valor"]
                );
            }
            $ReporteconvocatoriaForm->add(array(
                'name' => 'id_dependencia_academica',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Dependencia Académica : ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'id' => 'id_unidad_academica'
                )
            ));
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(41) as $uni) {
                $opciones = $opciones + array(
                    '0' => ''
                );
                $opciones = $opciones + array(
                    $uni["id_valor_flexible"] => $uni["descripcion_valor"]
                );
            }
            $ReporteconvocatoriaForm->add(array(
                'name' => 'id_categoria',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Categoría/Modalidad : ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'id' => 'id_unidad_academica'
                )
            ));
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(34) as $uni) {
                $opciones = $opciones + array(
                    '0' => ''
                );
                $opciones = $opciones + array(
                    $uni["id_valor_flexible"] => $uni["descripcion_valor"]
                );
            }
            $ReporteconvocatoriaForm->add(array(
                'name' => 'id_programa_academico',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Programa Académico : ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'id' => 'id_unidad_academica'
                )
            ));
            
            // define el campo ciudad
            $ReporteconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'id_estado',
                'required' => 'required',
                'options' => array(
                    'label' => 'Estado de la Convocatoria:',
                    'value_options' => array(
                        '0' => '',
                        'R' => 'Cerrada',
                        'H' => 'Archivada',
                        'N' => 'Anulada',
                        'P' => 'Con Aplicaciones',
                        'B' => 'Abierta'
                    )
                ),
                'attributes' => array(
                    'value' => '1'
                )
            ) // set selected to '1'

            );
            
            $ReporteconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'id_tipo_conv',
                'required' => 'required',
                'options' => array(
                    'label' => 'Tipo de Convocatoria:',
                    'value_options' => array(
                        '0' => '',
                        'S' => 'Especial',
                        'M' => 'De monitores',
                        'I' => 'Interna',
                        'E' => 'Externa'
                    )
                ),
                'attributes' => array(
                    'value' => ''
                )
            ) // set selected to '1'

            );
            
            $ReporteconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'codigo',
                'options' => array(
                    'label' => 'Código del Proyecto',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $ReporteconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'resumen',
                'options' => array(
                    'label' => 'Resumen Ejecutivo',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $ReporteconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'titulo_conv',
                'options' => array(
                    'label' => 'Título del Proyecto/Propuesta',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $ReporteconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'solicitado',
                'options' => array(
                    'label' => 'Valores de la Propuesta',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $ReporteconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'investigador',
                'options' => array(
                    'label' => 'Investigador Principal Proyecto/Propuesta',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $ReporteconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'evaluador',
                'options' => array(
                    'label' => 'Evaluador Principal y Puntajes',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $ReporteconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'objetivo',
                'options' => array(
                    'label' => 'Objetivo General',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $ReporteconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'unideppro',
                'options' => array(
                    'label' => 'Unidad, Dependencia y Programa',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $ReporteconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'elider',
                'options' => array(
                    'label' => 'Email del Líder',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $ReporteconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'categoria',
                'options' => array(
                    'label' => 'Categoría',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $ReporteconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'duracion',
                'options' => array(
                    'label' => 'Duración',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $ReporteconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'campo',
                'options' => array(
                    'label' => 'Campo y Línea de Investigación',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                )
            ));
            
            $ReporteconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'tipo_reporte',
                'required' => 'required',
                'options' => array(
                    'label' => 'Tipo de Reporte:',
                    'value_options' => array(
                        '0' => 'Seleccione',
                        '2' => 'Presupuestal',
                        '3' => 'Equipos de Trabajo'
                    )
                ),
                'attributes' => array(
                    'value' => ''
                )
            ) // set selected to '1'

            );
            /*
            $ReporteconvocatoriaForm->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'tipo',
                'required' => 'required',
                'options' => array(
                    'label' => 'Tipo de Reporte:',
                    'value_options' => array(
                        '0' => '',
                        'P' => 'Propuesta',
                        'Y' => 'Proyecto'
                    )
                ),
                'attributes' => array(
                    'value' => ''
                )
            ));
            */
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
                    'form' => $ReporteconvocatoriaForm,
                    'toe' => '',
                    'titulos' => "Reporte de convocatoria interna y externa",
                    'titulo2' => "Tipos Valores Existentes",
                    'url' => $this->getRequest()->getBaseUrl(),
                    'Cronograma' => $Cronograma->getCronogramas(),
                    'Urls' => $Url->getUrls(),
                    'Archivosconv' => $Archivosconv->getArchivosconvs(),
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


    public function reportAction()    {
        $ReporteConvocatoriaForm = new ReporteconvocatoriaForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
       
        $dato["ano"] = (int)$this->params()->fromRoute('id', 0);
        $dato["id_convocatoria"] = (int) $this->params()->fromRoute('id2', 1);
        $dato["nombre"] = (string) $this->params()->fromRoute('id3', 2);
        $dato["id_tipo_conv"] = (string) $this->params()->fromRoute('id4', 3);
        $dato["id_estado"] = (int) $this->params()->fromRoute('id5', 4);
        $dato["id_unidad_academica"] = (int) $this->params()->fromRoute('id6', 5);
        $dato["id_dependencia_academica"] = (int) $this->params()->fromRoute('id7', 6);
        $dato["id_programa_academico"] = (int) $this->params()->fromRoute('id8',  7);
        $dato["id_categoria"] = (int) $this->params()->fromRoute('id9', 8);
        $dato["codigo"] = (int) $this->params()->fromRoute('id10', 9);
        $dato["resumen"] = (int) $this->params()->fromRoute('id11',10);
        $dato["titulo_conv"] = (int) $this->params()->fromRoute('id12', 11);
        $dato["investigador"] = (int) $this->params()->fromRoute('id13', 12);
        $dato["categoria"] = (int) $this->params()->fromRoute('id14', 13);
        $dato["elider"] = (int) $this->params()->fromRoute('id15', 14);
        $dato["evaluador"] = (int) $this->params()->fromRoute('id16', 15);
        $dato["objetivo"] = (int) $this->params()->fromRoute('id17', 16);
        $dato["unideppro"] = (int) $this->params()->fromRoute('id18', 17);
        $dato["solicitado"] = (int) $this->params()->fromRoute('id19', 18);
        $dato["duracion"] = (int) $this->params()->fromRoute('id20', 19);
        $dato["campo"] = (int) $this->params()->fromRoute('id21', 20);
        if($dato["ano"]==0){
            $dato["ano"]=null;
        }
        if($dato["id_convocatoria"]==0){
            $dato["id_convocatoria"]=null;
        }
        
        if(strcmp($dato["nombre"], 0)===0){
            $dato["nombre"]=null;
        }

        if($dato["id_tipo_conv"]=="0"){
            $dato["id_tipo_conv"]=null;
        }

        if($dato["id_estado"]==0){
            $dato["id_estado"]=null;
        }

        if($dato["id_unidad_academica"]==0){
            $dato["id_unidad_academica"]=null;
        }

        if($dato["id_dependencia_academica"]==0){
            $dato["id_dependencia_academica"]=null;
        }
        if($dato["id_programa_academico"]==0){
            $dato["id_programa_academico"]=null;
        }
        if($dato["id_categoria"]==0){
            $dato["id_categoria"]=null;
        }

    // obtiene la informacion de las pantallas
        $convocatoria = new Convocatoria($this->dbAdapter);
        $ap = new Aplicar($this->dbAdapter);
        $valflex = new Agregarvalflex($this->dbAdapter);
        $us = new Usuarios($this->dbAdapter);
        $evaluador = new Asignareval($this->dbAdapter);
         $proyectos = new Proyectos($this->dbAdapter);
        $view = new ViewModel(array(
            'datosConvo' =>$convocatoria->filtroConvocatoriaReporte($dato),
            'datosPropuestas' => $ap->filtroreporteConvocatorias($dato),
            'datosBusqueda' => $dato,
            'valflex' => $valflex->getValoresf(),
            'usua' => $us->getArrayusuarios(),
            'evaluador' => $evaluador->getAsignarevalt(),
            'proyectos' => $proyectos->getProyectoh()
        ));
        
        $view->setTerminal(true);
        
        return $view;
    }
}
