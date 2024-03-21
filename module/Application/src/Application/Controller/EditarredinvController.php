<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarredinv\EditarredinvForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Redinvestigacion;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Contactored;
use Application\Modelo\Entity\Integrantesred;
use Application\Modelo\Entity\Serviciosred;
use Application\Modelo\Entity\Proyectored;
use Application\Modelo\Entity\Equipodirectivo;
use Application\Modelo\Entity\Documentosbibliograficosred;
use Application\Modelo\Entity\Librored;
use Application\Modelo\Entity\Capitulosred;
use Application\Modelo\Entity\Articulosred;
use Application\Modelo\Entity\Produccionesinvred;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Gruposred;
use Application\Modelo\Entity\Archivosred;
use Application\Modelo\Entity\Agregarautorred;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Proyectosintred;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Participacioneventosred;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Identificadoresred;
use Application\Modelo\Entity\Eventosred;
use Application\Modelo\Entity\Trabajogradored;
use Application\Modelo\Entity\Semillero;

use Application\Modelo\Entity\Documentosvinculados;
use Application\Modelo\Entity\Articuloshv;
use Application\Modelo\Entity\Libroshv;
use Application\Modelo\Entity\Capitulosusuario;
use Application\Modelo\Entity\Agregarautorusuario;
use Application\Modelo\Entity\Otrasproduccioneshv;
use Application\Modelo\Entity\Bibliograficoshv;
use Application\Modelo\Entity\Proyectosintusua;
use Application\Modelo\Entity\Proyectosexthv;

class EditarredinvController extends AbstractActionController
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
            $EditarredinvForm = new EditarredinvForm();
            $id = (int) $this->params()->fromRoute('id', 0);
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Redinvestigacion($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            $data = $this->request->getPost();
            $res = array();

            $res = $u->getredinvid($id);

            $resultado = $u->updateRedinv($id, $data,$res[0]["estado"]);
            
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
            $urlId = "/application/editarredinv/index/" . $id;
            if ($resultado != '') {
                $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                $evento = 'Edicion red de investigación. Datos antes: ' . print_r($res[0],true) . " /-/ Datos ahora: ". print_r($data, true);
                $ad->addAuditoriadet($evento, $resul);
                
                $this->flashMessenger()->addMessage("Red actualizada con éxito.");
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . $urlId);
                //return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/grupoinv/index');
            } else {
                $resultado = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                
                $evento = 'Intento fallido de edicion de red de investigación. Datos a cargar: '. print_r($data, true);
                $ad->addAuditoriadet($evento, $resultado);
                
                $this->flashMessenger()->addMessage("La red no se actualizó con éxito.");
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlId);
            }
        } else {
            
            $EditarredinvForm = new EditarredinvForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $us = new Redinvestigacion($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $filter = new StringTrim();
            
            $us = new Redinvestigacion($this->dbAdapter);
            $id = (int) $this->params()->fromRoute('id', 0);
            
            foreach ($us->getRedinvid($id) as $dat) {
                $dat["id"];
            }

            $EditarredinvForm->add(array(
                'name' => 'vision',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["vision"]),
                    'lenght' => 2500,
                    'maxlength' => 2500
                ),
                'options' => array(
                    'label' => 'Visión:'
                )
            ));

            $EditarredinvForm->add(array(
                'name' => 'mision',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["mision"]),
                    'lenght' => 2500,
                    'maxlength' => 2500
                ),
                'options' => array(
                    'label' => 'Misión:'
                )
            ));

            $EditarredinvForm->add(array(
                'name' => 'objetivos',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["objetivos"]),
                    'lenght' => 2500,
                    'maxlength' => 2500
                ),
                'options' => array(
                    'label' => 'Objetivos:'
                )
            ));

            $EditarredinvForm->add(array(
                'name' => 'antecedentes',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["antecedentes"]),
                    'lenght' => 2500,
                    'maxlength' => 2500
                ),
                'options' => array(
                    'label' => 'Antecedentes:'
                )
            ));

            $EditarredinvForm->add(array(
                'name' => 'justificacion',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["justificacion"]),
                    'lenght' => 2500,
                    'maxlength' => 2500
                ),
                'options' => array(
                    'label' => 'Justificación:'
                )
            ));

            $EditarredinvForm->add(array(
                'name' => 'descripcion',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["descripcion"]),
                    'lenght' => 2500,
                    'maxlength' => 2500
                ),
                'options' => array(
                    'label' => 'Descripción:'
                )
            ));
        
            $EditarredinvForm->add(array(
                'name' => 'lineas_investigacion',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["lineas_investigacion"]),
                    'lenght' => 2500,
                    'maxlength' => 2500
                ),
                'options' => array(
                    'label' => 'Lineas de investigación:'
                )
            ));

            $EditarredinvForm->add(array(
                'name' => 'instituciones_aliadas',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["instituciones_aliadas"]),
                    'lenght' => 2500,
                    'maxlength' => 2500
                ),
                'options' => array(
                    'label' => 'Instituciones aliadas a la red:'
                )
            ));

            $EditarredinvForm->add(array(
                'name' => 'socios',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["socios"]),
                    'lenght' => 2500,
                    'maxlength' => 2500
                ),
                'options' => array(
                    'label' => 'Socios:'
                )
            ));

            $EditarredinvForm->add(array(
                'name' => 'aliados',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["aliados"]),
                    'lenght' => 2500,
                    'maxlength' => 2500
                ),
                'options' => array(
                    'label' => 'Aliados:'
                )
            ));

            $per = array(
                'id_rol' => ''
            );
            $datusu = array(
                'id_rol' => ''
            );
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $permiso = new Permisos($this->dbAdapter);
            
            // me verifica el tipo de rol asignado al usuario
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $datusu) {
                $datusu["id_rol"];
            }
            
            if ($datusu["id_rol"] != '') {
                // me verifica el permiso sobre la pantalla
                $pantalla = "editarredinv";
                $panta = 0;
                $pt = new Agregarvalflex($this->dbAdapter);
                
                foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
                    $panta["id_valor_flexible"];
                }
                
                $permiso->verificarPermiso($datusu["id_rol"], $panta["id_valor_flexible"]);
                foreach ($permiso->verificarPermiso($datusu["id_rol"], $panta["id_valor_flexible"]) as $per) {
                    $per["id_rol"];
                }
            }

            $us = new Usuarios($this->dbAdapter);
            $a='';
            $opciones=array("0" => "Sin asignar");
            foreach ($us->getUsuarios($a) as $datl) {
                $op=array($datl["id_usuario"]=>$datl["primer_nombre"].' '.$datl["segundo_nombre"].' '.$datl["primer_apellido"].' '.$datl["segundo_apellido"]);
                $opciones=$opciones+$op;
            }

            if($datusu["id_rol"] == 1){
                $EditarredinvForm->add(array(
                    'name' => 'nombre_red',
                    'required' => 'required',
                    'attributes' => array(
                        'type' => 'text',
                        'value' => $filter->filter($dat["nombre"]),
                        'lenght' => 120,
                        'maxlength' => 120
                    ),
                    'options' => array(
                        'label' => 'Nombre de la red:'
                    )
                    
                ));
               
                if ($dat["estado"]=="A"){
                    $EditarredinvForm->add(array(
                        'type' => 'Zend\Form\Element\Select',
                        'name' => 'estado_red',
                        'required' => 'required',
                        'options' => array(
                            'label' => 'Estado:',
                            'value_options' => array(
                                '' => 'Seleccione el estado',
                                'A' => 'Activo',
                                'I' => 'Inactivo'
                            )
                        ),
                        'attributes' => array(
                            'value' => 'A'
                        ) 
                    ));
                }else{
                    $EditarredinvForm->add(array(
                        'type' => 'Zend\Form\Element\Select',
                        'name' => 'estado_red',
                        'required' => 'required',
                        'options' => array(
                            'label' => 'Estado:',
                            'value_options' => array(
                                '' => 'Seleccione el estado',
                                'A' => 'Activo',
                                'I' => 'Inactivo'
                            )
                        ),
                        'attributes' => array(
                            'value' => 'I'
                        ) 
                    ));
                }

                $EditarredinvForm->add(array(
                    'name' => 'codigo',
                    'attributes' => array(
                        'type' => 'text',
                        'value' => $filter->filter($dat["codigo"]),
                        'lenght' => 120,
                        'maxlength' => 120
                    ),
                    'options' => array(
                        'label' => 'Código de la red:'
                    )
                    
                ));
                
                $EditarredinvForm->add(array(
                    'name' => 'coordinador_1',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'coordinador_1',
                         'value' =>$dat["coordinador_uno"]
                    ),
                    'options' => array('label' => 'Coordinador 1: ',
                        'value_options' => $opciones,
                    ),
                ));

                $EditarredinvForm->add(array(
                    'name' => 'coordinador_2',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'coordinador_2',
                         'value' => $dat["coordinador_dos"]
                    ),
                    'size' => 25,
                    'options' => array('label' => 'Coordinador 2: ',
                        'value_options' => $opciones,
                    ),
                ));

                $EditarredinvForm->add(array(
                    'name' => 'id_coordinador_1',
                    'attributes' => array(
                        'type'  => 'text',
                        'onkeyup' => 'showHint1(this.value)'
                    ),
                    'options' => array(
                        'label' => 'Coordinador 1 UPN:'
                    ),
                ));

                $EditarredinvForm->add(array(
                    'name' => 'id_coordinador_2',
                    'attributes' => array(
                        'type'  => 'text',
                        'onkeyup' => 'showHint2(this.value)'
                    ),
                    'options' => array(
                        'label' => 'Coordinador 2 UPN:'
                    ),
                ));
            }else{
                $EditarredinvForm->add(array(
                    'name' => 'nombre_red',
                    'required' => 'required',
                    'attributes' => array(
                        'type' => 'text',
                        'value' => $filter->filter($dat["nombre"]),
                        'lenght' => 120,
                        'maxlength' => 120,
                        'disabled' => 'disabled'
                    ),
                    'options' => array(
                        'label' => 'Nombre de la red:'
                    )
                    
                ));

                if ($dat["estado"]=="A"){
                    $EditarredinvForm->add(array(
                        'type' => 'Zend\Form\Element\Select',
                        'name' => 'estado_red',
                        'required' => 'required',
                        'options' => array(
                            'label' => 'Estado:',
                            'value_options' => array(
                                '' => 'Seleccione el estado',
                                'A' => 'Activo',
                                'I' => 'Inactivo'
                            )
                        ),
                        'attributes' => array(
                            'value' => 'A',
                            'disabled' => 'disabled'
                        ) 
                    ));
                }else{
                    $EditarredinvForm->add(array(
                        'type' => 'Zend\Form\Element\Select',
                        'name' => 'estado_red',
                        'required' => 'required',
                        'options' => array(
                            'label' => 'Estado:',
                            'value_options' => array(
                                '' => 'Seleccione el estado',
                                'A' => 'Activo',
                                'I' => 'Inactivo'
                            )
                        ),
                        'attributes' => array(
                            'value' => 'I',
                            'disabled' => 'disabled'
                        ) 
                    ));
                }

                $EditarredinvForm->add(array(
                    'name' => 'codigo',
                    'attributes' => array(
                        'type' => 'text',
                        'value' => $filter->filter($dat["codigo"]),
                        'lenght' => 120,
                        'maxlength' => 120,
                        'disabled' => 'disabled'
                    ),
                    'options' => array(
                        'label' => 'Código de la red:'
                    )
                    
                ));
                
                $EditarredinvForm->add(array(
                    'name' => 'coordinador_1',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'coordinador_1',
                         'value' =>$dat["coordinador_uno"],
                        'disabled' => 'disabled'
                    ),
                    'options' => array('label' => 'Coordinador 1: ',
                        'value_options' => $opciones,
                    ),
                ));

                $EditarredinvForm->add(array(
                    'name' => 'coordinador_2',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'coordinador_2',
                         'value' => $dat["coordinador_dos"],
                        'disabled' => 'disabled'
                    ),
                    'size' => 25,
                    'options' => array('label' => 'Coordinador 2: ',
                        'value_options' => $opciones,
                    ),
                ));

                $EditarredinvForm->add(array(
                    'name' => 'id_coordinador_1',
                    'attributes' => array(
                        'type'  => 'text',
                        'onkeyup' => 'showHint1(this.value)',
                        'disabled' => 'disabled'
                    ),
                    'options' => array(
                        'label' => 'Coordinador 1 UPN:'
                    ),
                ));

                $EditarredinvForm->add(array(
                    'name' => 'id_coordinador_2',
                    'attributes' => array(
                        'type'  => 'text',
                        'onkeyup' => 'showHint2(this.value)',
                        'disabled' => 'disabled'
                    ),
                    'options' => array(
                        'label' => 'Coordinador 2 UPN:'
                    ),
                ));
            }

            $contactored = new Contactored($this->dbAdapter);
            $integrantesred = new Integrantesred($this->dbAdapter);
            $usuario = new Usuarios($this->dbAdapter);
            $serviciosred = new Serviciosred($this->dbAdapter);
            $proyectored = new Proyectored($this->dbAdapter);
            $equipodirectivo = new Equipodirectivo($this->dbAdapter);
            $documentosbliograficos = new Documentosbibliograficosred($this->dbAdapter);
            $libros = new Librored($this->dbAdapter);
            $capitulos = new Capitulosred($this->dbAdapter);
            $articulos = new Articulosred($this->dbAdapter);
            $produccionesinv = new Produccionesinvred($this->dbAdapter);
            $grupostotal = new Grupoinvestigacion($this->dbAdapter);
            $gruposred = new Gruposred($this->dbAdapter);
            $archivosred = new Archivosred($this->dbAdapter);
            $vf = new Agregarvalflex($this->dbAdapter);
            $autoresred = new Agregarautorred($this->dbAdapter);
            $proyectos = new Proyectos($this->dbAdapter);
            $tablaequipo = new Tablaequipop($this->dbAdapter);
            $proyectosint = new Proyectosintred($this->dbAdapter); 
            $participacioneventos = new Participacioneventosred($this->dbAdapter); 
            $identificadores = new Identificadoresred($this->dbAdapter); 
            $eventos = new Eventosred($this->dbAdapter); 
            $formacion = new Trabajogradored($this->dbAdapter);
            $semillero = new Semillero($this->dbAdapter); 

            $Documentosvinculados = new Documentosvinculados($this->dbAdapter);  
            $Articuloshv = new Articuloshv($this->dbAdapter);   
            $libroshv = new Libroshv($this->dbAdapter);
            $Capitulosusuario = new Capitulosusuario($this->dbAdapter);
            $autoresusuario = new Agregarautorusuario($this->dbAdapter);
            $Otrasproduccioneshv = new Otrasproduccioneshv($this->dbAdapter);
            $Bibliograficoshv = new Bibliograficoshv($this->dbAdapter);
            $Proyectosintusua = new Proyectosintusua($this->dbAdapter);     
            $Proyectosexthv = new Proyectosexthv($this->dbAdapter);

            $view = new ViewModel(array(
                'form' => $EditarredinvForm,
                'titulo' => "Editar red de investigación",
                'id' => $id,
                'url' => $this->getRequest()->getBaseUrl(),
                'contactored' => $contactored->getContactoredid($id),
                'integrantesred' => $integrantesred->getIntegrantesred($id),
                'usuarios' => $usuario->getUsuarios(""),
                'serviciosred' => $serviciosred->getServiciosredid($id),
                'proyectored' => $proyectored->getProyectoredid($id),
                'equipodirectivo' => $equipodirectivo->getEquipodirectivo($id),
                'documentosbliograficos' => $documentosbliograficos->getDocumentosbibliograficosredid($id),
                'libros' => $libros->getLibrored($id),
                'capitulos' => $capitulos->getCapitulored($id),
                'articulos' => $articulos->getArticulosred($id),
                'produccionesinv' => $produccionesinv->getProduccioninvred($id),
                'grupostotal' => $grupostotal->filtroRedes(""),
                'gruposred' => $gruposred->getGruposred($id),
                'archivosred' => $archivosred->getArchivosred($id),
                'vf' => $vf->getArrayvalores(24),
                'vf4' => $vf->getArrayvalores(39),
                'vf2' => $vf->getValoresf(),
                'autoresred' => $autoresred->getAgregarautorred($id),
                'proyectosint' =>  $proyectosint->getProyectos($id),
                'proyectos' =>  $proyectos->getProyectoh(),
                'participacioneventos' =>  $participacioneventos->getParticipacioneventosred($id), 
                'tablaequipo' =>  $tablaequipo->getTablaequipot(),
                'menu' => $datusu["id_rol"],
                'identificadores' => $identificadores->getIdentificadoresred($id),
                'eventos' => $eventos->getEventosred($id),
                'formacion' => $formacion->getTrabajogradored($id),
                'semilleros' => $semillero->getSemilleroinv(),

                'dataDocumentos' => $Documentosvinculados->getDocumentosvinculadosByEstado($id, "2"),
                'Articuloshv' => $Articuloshv->getArticuloshvt(),
                'libroshv' => $libroshv->getLibroshvt(),
                'capituloshv' => $Capitulosusuario->getCapitulot(),
                'autoresusuario' => $autoresusuario->getAgregarautorusuariot(),
                'Otrasproduccioneshv' => $Otrasproduccioneshv->getOtrasproduccioneshvt(),
                'Bibliograficoshv' => $Bibliograficoshv->getBibliograficost(),
                'Proyectosintusua' =>  $Proyectosintusua->getProyectosi(),
                'Proyectosexthv' => $Proyectosexthv->getProyectosexthvt()
            ));
            return $view;
        }
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Redinvestigacion($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarRedinv($id);
        $this->flashMessenger()->addMessage("Red eliminada con exito.");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/redesinv/index');
    }

    public function delAction()
    {
        $EditarredinvForm = new EditarredinvForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Redinvestigacion($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $EditarredinvForm,
            'titulo' => "Eliminar red de investigación",
            'url' => $this->getRequest()->getBaseUrl(),
            'datos' => $id,
            'id2' => $id2
        ));
        return $view;
    }
}
