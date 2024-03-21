<?php

namespace Application\Controller;
 
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarsemilleroinv\EditarsemilleroinvForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Semillero;
use Application\Modelo\Entity\Librosemillero;
use Application\Modelo\Entity\Agregarautorsemillero;
use Application\Modelo\Entity\Articulossemillero;
use Application\Modelo\Entity\Capitulossemillero;
use Application\Modelo\Entity\Produccionesinvsemillero;
use Application\Modelo\Entity\Documentosbibliograficossemillero;
use Application\Modelo\Entity\Archivossemillero;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Grupossemillero;
use Application\Modelo\Entity\Integrantesemillero;
use Application\Modelo\Entity\Areasemillero;
use Application\Modelo\Entity\Reconocimientossemillero;
use Application\Modelo\Entity\Gruposrel; //Investigar
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Proyectosintsemi;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Participacioneventos;
use Application\Modelo\Entity\Eventossemi;
use Application\Modelo\Entity\Trabajogradosemi;
use Application\Modelo\Entity\Identificadoressemi;

use Application\Modelo\Entity\Documentosvinculados;
use Application\Modelo\Entity\Articuloshv;
use Application\Modelo\Entity\Libroshv;
use Application\Modelo\Entity\Capitulosusuario;
use Application\Modelo\Entity\Agregarautorusuario;
use Application\Modelo\Entity\Otrasproduccioneshv;
use Application\Modelo\Entity\Bibliograficoshv;
use Application\Modelo\Entity\Proyectosintusua;

class EditarsemilleroinvController extends AbstractActionController
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
        
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($this->getRequest()->isPost()) {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $EditarsemilleroinvForm = new EditarsemilleroinvForm();
            $id = (int) $this->params()->fromRoute('id', 0);
            $u = new Semillero($this->dbAdapter);
            $auth = $this->auth;
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $data = $this->request->getPost();
            $dataAnt = $u->getSemilleroinvid($id);
            $resultado = $u->updateSemilleroinv($id, $data, $dataAnt[0]["estado"]);
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
            
            $urlId = "/application/editarsemilleroinv/index/" . $id;
            if ($resultado != '') {
                $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                $evento = 'Edicion semillero de investigación.  Datos antes: ' . print_r($dataAnt[0],true) . " /-/ Datos ahora: ". print_r($data, true);
                $ad->addAuditoriadet($evento, $resul);
                $this->flashMessenger()->addMessage("Semillero de investigación actualizado con éxito.");
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . $urlId);
                //return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarsemilleroinv/index/'.$id);
            } else {
                $resultado = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                $evento = 'Intento fallido editar semillero de investigación.  Datos a cargar: '. print_r($data, true);
                $ad->addAuditoriadet($evento, $resultado);
                $this->flashMessenger()->addMessage("El semillero de investigación no se actualizó con éxito.");
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlId);
            }
        } else {      
            $EditarsemilleroinvForm = new EditarsemilleroinvForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $semi = new Semillero($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $filter = new StringTrim();
            $id = (int) $this->params()->fromRoute('id', 0);
            $semillero = array();
            $semillero = $semi->getSemilleroinvid($id);

            //'value' => 'A'
            $vf = new Agregarvalflex($this->dbAdapter);
             $opciones = array(
                '' => ''
            );
            
            $vf = new Agregarvalflex($this->dbAdapter);
            foreach ($vf->getArrayvalores(33) as $dat) {
                $op = array(
                    $dat["id_valor_flexible"] . '-' . $dat["valor_flexible_padre_id"]  => $dat["descripcion_valor"]
                );
            
                $opciones = $opciones  + $op;
            }

            $EditarsemilleroinvForm->add(array(
                'name' => 'id_dependencia_academica',
                'type' => 'Zend\Form\Element\Select',
            
                'options' => array(
                    'label' => 'Dependencia académica: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'id' => 'id_dependencia_academica',
                    'onchange' => 'myFunction3();',
                    'value' => $semillero[0]["id_dependencia_academica"]."-".$semillero[0]["id_unidad_academica"],
                    'required' => 'required'
                ) // set selected to '1'
            
            ));

            $opciones = array(
                '' => ''
            );
            foreach ($vf->getArrayvalores(34) as $dat) {
                $op = array(
                    $dat["id_valor_flexible"] . '-' . $dat["valor_flexible_padre_id"]=> $dat["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            
            $EditarsemilleroinvForm->add(array(
                'name' => 'id_programa_academico',
                'type' => 'Zend\Form\Element\Select',
                
                'options' => array(
                    'label' => 'Programa académico: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'id' => 'id_programa_academico',
                    'required' => 'required',
                    'value' => $semillero[0]["id_programa_academico"]."-".$semillero[0]["id_dependencia_academica"]
                ) // set selected to '1'

            ));

            $opciones = array(
                '' => ''
            );
            
            foreach ($vf->getArrayvalores(23) as $dat) {                
                $op = array(
                    $dat["id_valor_flexible"] => $dat["descripcion_valor"]
                );  
                $opciones = $opciones  + $op;
            }

            $EditarsemilleroinvForm->add(array(
                'name' => 'id_unidad_academica',
                'type' => 'Zend\Form\Element\Select',
                
                'options' => array(
                    'label' => 'Unidad académica: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'id' => 'id_unidad_academica',
                    'onchange' => 'myFunction2();',
                    'value' => $semillero[0]["id_unidad_academica"], 
                    'required' => 'required'
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
                $pantalla = "editarsemilleroinv";
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

            $us = new Usuarios($this->dbAdapter);
            $a='';
            $opciones=array(0 => "Sin asignar");
            $opcionesHide=array(0 => "Sin asignar");
            foreach ($us->getUsuarios($a) as $datl) {
                $op=array($datl["id_usuario"]=>$datl["primer_nombre"].' '.$datl["segundo_nombre"].' '.$datl["primer_apellido"].' '.$datl["segundo_apellido"]);
                $opciones=$opciones+$op;

                $op=array($datl["id_usuario"]=>$datl["primer_nombre"].' '.$datl["segundo_nombre"].' '.$datl["primer_apellido"].' '.$datl["segundo_apellido"]."-".$datl["email"]);
                $opcionesHide=$opcionesHide+$op;
            }

            $EditarsemilleroinvForm->add(array(
                'name' => 'fecha_creacion',
                'attributes' => array(
                    'type'  => 'date',
                    'required' => 'required',
                    'value' => $semillero[0]["fecha_creacion"]
                ),
                'options' => array(
                    'label' => 'Fecha de creación:',
                ),
            ));


            if($dat["id_rol"] == 1){
                $EditarsemilleroinvForm->add(array(
                    'name' => 'nombre',
                    'attributes' => array(
                        'type'  => 'text',
                        'value' => $semillero[0]["nombre"]
                    ),
                    'options' => array(
                        'label' => 'Nombre del Semillero / Otros procesos de formación:',
                    ),
                ));

                $EditarsemilleroinvForm->add(array(
                    'name' => 'codigo',
                    'attributes' => array(
                        'type'  => 'text',
                        'value' => $semillero[0]["codigo"]
                    ),
                    'options' => array(
                        'label' => 'Código:',
                    ),
                ));

                $EditarsemilleroinvForm->add(array(
                    'type' => 'Zend\Form\Element\Select',
                    'name' => 'estado',
                    'options' => array(
                            'label' => 'Estado :',
                            'empty_option' => 'Seleccione un estado',
                            'value_options' => array(
                              'A' => 'Activo',
                              'I' => 'Inactivo'
                            ),
                    ),
                    'attributes' => array(
                        'value' =>  $semillero[0]["estado"],
                        'required'=>'required'
                    )
                ));

                $EditarsemilleroinvForm->add(array(
                    'name' => 'coordinador_1',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'coordinador_1',
                         'value' => $semillero[0]["coordinador_uno"]
                    ),
                    'options' => array('label' => 'Coordinador 1: ',
                        'value_options' => $opciones,
                    ),
                ));

                $EditarsemilleroinvForm->add(array(
                    'name' => 'coordinador_hide',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'coordinador_hide',
                        'hidden' => true
                    ),
                    'options' => array(
                        'value_options' => $opcionesHide,
                    ),
                ));

                $EditarsemilleroinvForm->add(array(
                    'name' => 'coordinador_2',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'coordinador_2',
                         'value' => $semillero[0]["coordinador_dos"]
                    ),
                    'size' => 25,
                    'options' => array('label' => 'Coordinador 2: ',
                        'value_options' => $opciones,
                    ),
                ));

                $EditarsemilleroinvForm->add(array(
                    'name' => 'id_coordinador_1',
                    'attributes' => array(
                        'type'  => 'text',
                        'id' => 'id_coordinador_1',
                        'placeholder' => 'Filtro'
                    ),
                    'options' => array(
                        'label' => 'Coordinador 1:'
                    ),
                ));

                $EditarsemilleroinvForm->add(array(
                    'name' => 'id_coordinador_2',
                    'attributes' => array(
                        'type'  => 'text',
                        'placeholder' => 'Filtro',
                        'id' => 'id_coordinador_2',
                    ),
                    'options' => array(
                        'label' => 'Coordinador 2:'
                    ),
                ));

            }else{
                $EditarsemilleroinvForm->add(array(
                    'name' => 'nombre',
                    'attributes' => array(
                        'type'  => 'text',
                        'value' => $semillero[0]["nombre"],
                         "disabled" => "disabled"
                    ),
                    'options' => array(
                        'label' => 'Nombre del Semillero / Otros procesos de formación:',
                    ),
                ));
                    
                $EditarsemilleroinvForm->add(array(
                    'name' => 'codigo',
                    'attributes' => array(
                        'type'  => 'text',
                        'value' => $semillero[0]["codigo"],
                         "disabled" => "disabled"
                    ),
                    'options' => array(
                        'label' => 'Código del semillero:',
                    ),
                ));

                $EditarsemilleroinvForm->add(array(
                    'type' => 'Zend\Form\Element\Select',
                    'name' => 'estado',
                    'required'=>'required',
                    'options' => array(
                            'label' => 'Estado :',
                            'empty_option' => 'Seleccione un estado',
                            'value_options' => array(
                              'A' => 'Activo',
                              'I' => 'Inactivo'
                            ),
                    ),
                    'attributes' => array(
                        'value' =>  $semillero[0]["estado"],
                        "disabled" => "disabled"
                    )
                ));

                $EditarsemilleroinvForm->add(array(
                    'name' => 'coordinador_1',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'coordinador_1',
                        'value' => $semillero[0]["coordinador_uno"],
                        "disabled" => "disabled"
                    ),
                    'options' => array('label' => 'Coordinador 1: ',
                        'empty_option' => 'Seleccione el coordinador',
                        'value_options' => $opciones,
                    ),
                ));

                $EditarsemilleroinvForm->add(array(
                    'name' => 'coordinador_hide',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'coordinador_hide',
                        'hidden' => true
                    ),
                    'options' => array(
                        'value_options' => $opcionesHide,
                    ),
                ));

                $EditarsemilleroinvForm->add(array(
                    'name' => 'coordinador_2',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'coordinador_2',
                         'value' => $semillero[0]["coordinador_dos"],
                         "disabled" => "disabled"
                    ),
                    'size' => 25,
                    'options' => array('label' => 'Coordinador 2: ',
                        'empty_option' => 'Seleccione el coordinador',
                        'value_options' => $opciones,
                    ),
                ));

                $EditarsemilleroinvForm->add(array(
                    'name' => 'id_coordinador_1',
                    'attributes' => array(
                        'type'  => 'text',
                         "disabled" => "disabled"
                    ),
                    'options' => array(
                        'label' => 'Coordinador 1:'
                    ),
                ));

                $EditarsemilleroinvForm->add(array(
                    'name' => 'id_coordinador_2',
                    'attributes' => array(
                        'type'  => 'text',
                         "disabled" => "disabled"
                    ),
                    'options' => array(
                        'label' => 'Coordinador 2:'
                    ),
                ));
            }

            $EditarsemilleroinvForm->add(array(
                'name' => 'estudiantes',
                'attributes' => array(
                    'type'  => 'number',
                    'value' => $semillero[0]["estudiantes"]
                ),
                'options' => array(
                    'label' => 'Número de estudiantes:',
                ),
            ));

            $EditarsemilleroinvForm->add(array(
                'name' => 'objetivo_general',
                'attributes' => array(
                    'type'  => 'textarea',
                    'value' => $semillero[0]["objetivo_general"],
                    'maxlength' => 7000,
                    'required'=>'required'
                ),
                'options' => array(
                    'label' => 'Objetivo general:',
                ),
            ));

            $EditarsemilleroinvForm->add(array(
                'name' => 'objetivo_especifico',
                'attributes' => array(
                    'type'  => 'textarea',
                    'value' => $semillero[0]["objetivo_especifico"],
                    'maxlength' => 7000,
                    'required'=>'required'
                ),
                'options' => array(
                    'label' => 'Objetivos especificos:',
                ),
            ));

            $EditarsemilleroinvForm->add(array(
                'name' => 'actividades',
                'attributes' => array(
                    'type'  => 'textarea',
                    'value' => $semillero[0]["actividades"],
                    'maxlength' => 7000,
                    'required'=>'required'
                ),
                'options' => array(
                    'label' => 'Temática general (Obligatorio) y línea de investigación (si aplica)',
                ),
            ));


            $libros = new Librosemillero($this->dbAdapter);
            $autoressemillero = new Agregarautorsemillero($this->dbAdapter);
            $articulos = new Articulossemillero($this->dbAdapter);
            $capitulos = new Capitulossemillero($this->dbAdapter);
            $produccionesinv = new Produccionesinvsemillero($this->dbAdapter);
            $documentosbliograficos = new Documentosbibliograficossemillero($this->dbAdapter);     
            $Archivossemillero = new Archivossemillero($this->dbAdapter);
            $grupostotal = new Grupoinvestigacion($this->dbAdapter);
            $grupossemillero = new Grupossemillero($this->dbAdapter);
            $integrantessemillero = new Integrantesemillero($this->dbAdapter);
            $areas = new Areasemillero($this->dbAdapter);
            $reconocimientos = new Reconocimientossemillero($this->dbAdapter);
            $usuario = new Usuarios($this->dbAdapter);
            $vf = new Agregarvalflex($this->dbAdapter);
            $proyectos = new Proyectos($this->dbAdapter);
            $tablaequipo = new Tablaequipop($this->dbAdapter);
            $proyectosint = new Proyectosintsemi($this->dbAdapter); 
            $participacioneventos = new Participacioneventos($this->dbAdapter); 
            $identificadores = new Identificadoressemi($this->dbAdapter); 
            $eventos = new Eventossemi($this->dbAdapter); 
            $formacion = new Trabajogradosemi($this->dbAdapter);
            $semillero = new Semillero($this->dbAdapter); 

            $Documentosvinculados = new Documentosvinculados($this->dbAdapter);  
            $Articuloshv = new Articuloshv($this->dbAdapter);   
            $libroshv = new Libroshv($this->dbAdapter);
            $Capitulosusuario = new Capitulosusuario($this->dbAdapter);
            $autoresusuario = new Agregarautorusuario($this->dbAdapter);
            $Otrasproduccioneshv = new Otrasproduccioneshv($this->dbAdapter);
            $Bibliograficoshv = new Bibliograficoshv($this->dbAdapter);
            $Proyectosintusua = new Proyectosintusua($this->dbAdapter);     

            $view = new ViewModel(array(
                'form' => $EditarsemilleroinvForm,
                'titulo' => "Editar Semillero / Otros procesos de formación",
                'id' => $id,
                'url' => $this->getRequest()->getBaseUrl(),
                'integrantessemillero' => $integrantessemillero->getIntegrantesemilleroid($id),
                'usuarios' => $usuario->getUsuarios(""),
                'documentosbliograficos' => $documentosbliograficos->getDocumentosbibliograficossemilleroid($id),
                'areas' => $areas->getAreasemillero($id),
                'reconocimientos' => $reconocimientos->getReconocimientossemillero($id),
                'libros' => $libros->getLibrosemillero($id),
                'capitulos' => $capitulos->getCapitulosemillero($id),
                'articulos' => $articulos->getArticulossemillero($id),
                'produccionesinv' => $produccionesinv->getProduccioninvsemillero($id),
                'grupostotal' => $grupostotal->filtroRedes(""),
                'grupossemillero' => $grupossemillero->getGrupossemillero($id),
                'archivossemillero' => $Archivossemillero->getArchivossemillero($id),
                'vf' => $vf->getArrayvalores(24),
                'vf2' => $vf->getValoresf(),
                'autoressemillero' => $autoressemillero->getAgregarautorsemillero($id),
                'proyectosint' =>  $proyectosint->getProyectos($id),
                'proyectos' =>  $proyectos->getProyectoh(),
                'participacioneventos' =>  $participacioneventos->getParticipacioneventos($id), 
                'tablaequipo' =>  $tablaequipo->getTablaequipot(),
                'menu' => $dat["id_rol"],
                'identificadores' => $identificadores->getIdentificadoressemi($id),
                'eventos' => $eventos->getEventossemi($id),
                'formacion' => $formacion->getTrabajogradosemi($id),
                'semilleros' => $semillero->getSemilleroinv(),

                'dataDocumentos' => $Documentosvinculados->getDocumentosvinculadosByEstado($id, "3"),
                'Articuloshv' => $Articuloshv->getArticuloshvt(),
                'libroshv' => $libroshv->getLibroshvt(),
                'capituloshv' => $Capitulosusuario->getCapitulot(),
                'autoresusuario' => $autoresusuario->getAgregarautorusuariot(),
                'Otrasproduccioneshv' => $Otrasproduccioneshv->getOtrasproduccioneshvt(),
                'Bibliograficoshv' => $Bibliograficoshv->getBibliograficost(),
                'Proyectosintusua' =>  $Proyectosintusua->getProyectosi()
            ));
            return $view;
        }
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Semillero($this->dbAdapter);
        $id = (int) $this->params()->fromRoute('id', 0);
        $u->eliminarSemillero($id);
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/semillerosinv/index');
    }

    public function delAction()
    {
        $EditarsemilleroinvForm = new EditarsemilleroinvForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Semillero($this->dbAdapter);
        $id = (int) $this->params()->fromRoute('id', 0);      
        $view = new ViewModel(array(
            'form' => $EditarsemilleroinvForm,
            'titulo' => "Eliminar red de investigación",
            'url' => $this->getRequest()->getBaseUrl(),
            'datos' => $id
        ));
        return $view;
    }
}
