<?php
namespace Application\Controller;
require_once("public/rutas.php"); 

use Zend\Authentication\AuthenticationService;
use Application\Proyectos\EditarproyectoForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Proyectos;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Informes;
use Application\Modelo\Entity\Actas;
use Application\Modelo\Entity\Archivosconv;
use Application\Modelo\Entity\Tablafinp;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Gruposproy;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;
use Application\Modelo\Entity\Talentohumano;
use Application\Modelo\Entity\Entidadesejecutoras;
use Application\Modelo\Entity\Aplicar;
use Application\Modelo\Entity\Objetivosespecificos;
use Application\Modelo\Entity\Objetivosmetas;
use Application\Modelo\Entity\Propuestainv;

class EditarproyectoController extends AbstractActionController
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
        if ($auth->getStorage()->read() == false && $auth->getStorage()->read() == null) {
            $identi = new \stdClass();
                $identi->id_usuario = '0';
        }
        else{
            $identi = print_r($auth->getStorage()->read()->id_usuario,true);
            $resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,get_class());
            if($resultadoPermiso==0){
                $this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
            }
        }

        if ($this->getRequest()->isPost()) {
            $this->auth = new AuthenticationService();
            $auth = $this->auth;
            
            // verifica si esta conectado
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                $identi = new \stdClass();
                $identi->id_usuario = '0';
                //return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/login/index');
            }
           
            // Creacion adaptador de conexion, objeto de datos, auditoria
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $not = new Proyectos($this->dbAdapter);
            
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
            // obtiene la informacion de las pantallas2
            $data = $this->request->getPost();
            $EditarproyectoForm = new EditarproyectoForm();
            // adiciona la noticia
            $id = (int) $this->params()->fromRoute('id', 0);
            
            if ($data["id_estado"] == 3) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/editarproyecto/cerrarp/' . $id);
            }
            echo $data["id_programa_academico"]."Dependencia";
            $resultado = $not->updateProyectos($id, $data);
            
            // redirige a la pantalla de inicio del controlador
            if ($resultado == 1) {
                $res = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                $p = implode(',', (array) $data);
                $evento = 'Actualizacion de proyecto:' . $p;
                $ad->addAuditoriadet($evento, $res);
                
                $this->flashMessenger()->addMessage("Proyecto actualizado con exito");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/editarproyecto/index/' . $id);
            } else {
                $this->flashMessenger()->addMessage("La creacion de la convocatoria fallo");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/convocatoriai/index');
            }
        } else {
            $this->auth = new AuthenticationService();
            $auth = $this->auth;
            $identi = $auth->getStorage()->read();
            
            if (isset($_COOKIE["dep_o"])) {
                echo '';
            } else {
                $_COOKIE["dep_o"] = '';
            }

            if ($identi == false && $identi == null) {
                $identi = new \stdClass();
                $identi->id_usuario = '0';
                //return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/login/index');
            }
            
            $EditarproyectoForm = new EditarproyectoForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Proyectos($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            $proyecto = array();
            $filter = new StringTrim();
            
            $id = (int) $this->params()->fromRoute('id', 0);
            
            if ($id != 0) {
                $proyecto = $u->getProyecto($id, 'i');
                foreach ($proyecto as $dat) {
                    $id_conv = $dat["id_proyecto"];
                }
            } else {
                $proyecto = $u->getproyectoh();
                foreach ($proyecto as $dat) {
                    $id_conv = $dat["id_proyecto"];
                }
            }
            
            $idconvocatoria = $dat["id_convocatoria"];
            $idunidad = $dat["id_unidad_academica"];
            $idlinea = $dat["id_linea_inv"];
            $idcampo = $dat["id_campo"];
            $idinvestigador = $dat["id_investigador"];
            $idestado = $dat["id_estado"];
            $codigo = $dat["codigo_proy"];
            $iddependencia = $dat["id_dependencia_academica"];
            $idprograma = $dat["id_programa_academico"];
            $convocatoria = $dat["convocatoria"];
            $tipo_conv = $dat["tipo_conv"];

            if($GLOBALS["consulta_siafi"]=="Si"){
                $this->updateTablaSiafi($dat["codigo_proy"], $dat["id_proyecto"]);
            }
            $EditarproyectoForm->get('fecha_inicio')->setValue($dat["fecha_inicio"]);
            $EditarproyectoForm->get('fecha_terminacion')->setValue($dat["fecha_terminacion"]);
            $EditarproyectoForm->get('tipo_proyecto')->setValue($dat["tipo_conv"]);
            $EditarproyectoForm->get('prorroga')->setValue($dat["prorroga"]);
            $EditarproyectoForm->get('convocatoria')->setValue($dat["convocatoria"]);
            $EditarproyectoForm->get('documento_formalizacion')->setValue($dat["documento_formalizacion"]);
            $EditarproyectoForm->get('modificaciones_documento')->setValue($dat["modificaciones_documento"]);

            $EditarproyectoForm->add(array(
                'name' => 'objetivo_general',
                'attributes' => array(
                    'type' => 'textarea',
                    'placeholder' => 'Ingrese el objetivo general',
                    'lenght' => 500,
                    'value' => $filter->filter($dat["objetivo_general"])
                ),
                'options' => array(
                    'label' => 'Objetivo General:'
                )
            ));
            
            $EditarproyectoForm->add(array(
                'name' => 'observaciones',
                'attributes' => array(
                    'type' => 'textarea',
                    'placeholder' => 'Ingrese la observación de la aprobación',
                    'lenght' => 500,
                    'value' => $filter->filter($dat["observaciones"])
                ),
                'options' => array(
                    'label' => 'Observación del estado del proyecto:'
                )
            ));
            
            $EditarproyectoForm->add(array(
                'name' => 'resumen_ejecutivo',
                'attributes' => array(
                    'type' => 'textarea',
                    'placeholder' => 'Ingrese el objetivo general',
                    'lenght' => 500,
                    'value' => $filter->filter($dat["resumen_ejecutivo"])
                ),
                'options' => array(
                    'label' => 'Resumen Ejecutivo:'
                )
            ));
            
            $EditarproyectoForm->add(array(
                'name' => 'nombre_proy',
                'attributes' => array(
                    'type' => 'text',
                    
                    'value' => $filter->filter($dat["nombre_proy"])
                ),
                'options' => array(
                    'label' => 'Nombre proyecto o proceso de investigación:'
                )
            ));
            
            
            if ($dat["id_estado"] != 4) {
                $EditarproyectoForm->add(array(
                    'type' => 'Zend\Form\Element\Select',
                    'name' => 'id_estado',
                    'required' => 'required',
                    'options' => array(
                        'label' => 'Estado del proyecto',
                        'value_options' => array(
                            '' => 'Seleccione',
                            '1' => 'Creado',
                            '2' => 'Aprobado',
                            '3' => 'Cerrado',
                            '4' => 'Archivado'
                        )
                    ),
                    'attributes' => array(
                        'value' => $dat["id_estado"]
                    )
                ));
            } else {
                
                $EditarproyectoForm->add(array(
                    'type' => 'Zend\Form\Element\Select',
                    'name' => 'id_estado',
                    'required' => 'required',
                    'options' => array(
                        'label' => 'Estado',
                        'value_options' => array(
                            '4' => 'Archivado'
                        )
                    ),
                    'attributes' => array(
                        'value' => $dat["id_estado"]
                    )
                ));
            }
            
            $us = new Usuarios($this->dbAdapter);
            $opciones = array();
            
            foreach ($us->getUsuarios('') as $usid) {
                $op=array($usid["id_usuario"]=>$usid["primer_nombre"].' '.$usid["segundo_nombre"].' '.$usid["primer_apellido"].' '.$usid["segundo_apellido"]);
                $opciones=$opciones+$op;
            }

            $EditarproyectoForm->add(array(
                'name' => 'id_investigador',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'id_investigador',
                    'value' => $dat["id_investigador"]
                ),
                'size' => 25,
                'options' => array('label' => 'Investigador principal: ',
                    'empty_option' => 'Seleccione el Investigador principal',
                    'value_options' => $opciones,
                ),
            ));

            $EditarproyectoForm->add(array(
                    'name' => 'id_investigador_2',
                    'attributes' => array(
                        'id' => 'id_investigador_2',
                        'type'  => 'text'
                    ),
                    'options' => array(
                        'label' =>  'Líder del grupo:'
                    ),
                ));
            
            $EditarproyectoForm->add(array(
                'name' => 'codigo_proy',
                'attributes' => array(
                    'type' => 'text',
                    
                    'value' => $filter->filter($dat["codigo_proy"])
                ),
                'options' => array(
                    'label' => 'Código proyecto o proceso de investigación:'
                )
            ));
            
            $tipoInvesti = $dat["stipo"];
            
            if($dat["stipo"]=="proyecto"){
                $EditarproyectoForm->add(array(
                    'name' => 'fecha_limite',
                    'attributes' => array(
                        'placeholder' => 'DD-MM-YYYY',
                        'type' => 'Date',
                        'size' => 15,
                        'value' => $filter->filter($dat["fecha_limite"]),
                        'required' => 'required',
                    ),
                    'options' => array(
                        'label' => 'Fecha limite de liquidación:'
                    )
                ));
                
                $EditarproyectoForm->add(array(
                    'name' => 'duracion',
                    'attributes' => array(
                        'type' => 'text',
                        'value' => $filter->filter($dat["duracion"])
                    ),
                    'options' => array(
                        'label' => 'Duración:'
                    )
                ));

                $EditarproyectoForm->add(array(
                    'name' => 'primera_vigencia',
                    'attributes' => array(
                        'type' => 'Number',
                        'placeholder' => 'Ingrese el año de la primera vigencia presupuestal',
                        'required' => 'required',
                        'min' => 1989,
                        'max' => 2099,
                        'value' => $filter->filter($dat["primera_vigencia"])
                    )
                    ,
                    'options' => array(
                        'label' => 'Primera Vigencia :'
                    )
                ));
                if ($filter->filter($dat["periodo"]) == 'M') {
                    $per = 'MESES';
                } else {
                    $per = 'SEMESTRE';
                }
                
                $EditarproyectoForm->add(array(
                    'type' => 'Zend\Form\Element\Select',
                    'name' => 'periodo',
                    'required' => 'required',
                    'options' => array(
                        'label' => 'Período',
                        'value_options' => array(
                            '' => $per,
                            'M' => 'Meses',
                            'S' => 'Semestres'
                        )
                    ),
                    'attributes' => array(
                        'value' => '1'
                    ) // set selected to '1'

                ));
            }else{
                $EditarproyectoForm->add(array(
                    'name' => 'fecha_limite',
                    'attributes' => array(
                        'placeholder' => 'DD-MM-YYYY',
                        'type' => 'Date',
                        'size' => 15,
                        'value' => $filter->filter($dat["fecha_limite"]),
                        'disabled' => 'disabled'
                    ),
                    'options' => array(
                        'label' => 'Fecha limite de liquidación:'
                    )
                ));
                
                $EditarproyectoForm->add(array(
                    'name' => 'duracion',
                    'attributes' => array(
                        'type' => 'text',
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["duracion"])
                    ),
                    'options' => array(
                        'label' => 'Duración:'
                    )
                ));

                $EditarproyectoForm->add(array(
                    'name' => 'primera_vigencia',
                    'attributes' => array(
                        'type' => 'Number',
                        'placeholder' => 'Ingrese el aÃ±o de la primera vigencia presupuestal',
                        'required' => 'required',
                        'min' => 1989,
                        'max' => 2099,
                        'value' => $filter->filter($dat["primera_vigencia"]),
                        'disabled' => 'disabled'
                    )
                    ,
                    'options' => array(
                        'label' => 'Primera Vigencia :'
                    )
                ));
                
                if ($filter->filter($dat["periodo"]) == 'M') {
                    $per = 'MESES';
                } else {
                    $per = 'SEMESTRE';
                }
                
                $EditarproyectoForm->add(array(
                    'type' => 'Zend\Form\Element\Select',
                    'name' => 'periodo',
                    'required' => 'required',
                    'options' => array(
                        'label' => 'Período',
                        'value_options' => array(
                            '' => $per,
                            'M' => 'Meses',
                            'S' => 'Semestres'
                        )
                    ),
                    'attributes' => array(
                        'value' => '1',
                        'disabled' => 'disabled'
                    ) // set selected to '1'

                ));
            }
            
            

            $v_uni = (int) $this->params()->fromRoute('id3', 1);
            $vf = new Agregarvalflex($this->dbAdapter);
            $resvf = $vf->getValoresflexid($v_uni);
            $opciones = array();
            $op = array(
                    "0" => ""
                );
            foreach ($vf->getArrayvalores(23) as $xx) {
                
                
                $op = $op + array(
                    $xx["id_valor_flexible"] => $xx["descripcion_valor"]
                );
                
                $opciones = $opciones + $op;
            }
            $opz="";
            foreach ($vf->getvalflexseditar($idunidad) as $datz) {
                $opz = $datz["descripcion_valor"];
            }

            // UNIDAD ACADEMICA
            $EditarproyectoForm->add(array(
                'name' => 'unidad_academica',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($opz),
                    //'value' => $idunidad,
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Unidad Académica:'
                )
            ));
            
            $EditarproyectoForm->add(array(
                'name' => 'id_unidad_academica',
                'type' => 'Zend\Form\Element\Select',
                
                'options' => array(
                    'label' => 'Unidad Académica: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'value' => '0',
                    'id' => 'id_unidad_academica',
                    //'required' => 'required',
                    'onchange' => 'myFunction2();'
                ) // set selected to '1'
            ));
         
            $id4 = (int) $this->params()->fromRoute('id4', 1);
            $v_dep = $id4;
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $resvf = $vf->getValoresflexid($v_dep);
            $opciones = array();
            $op = array(
                    "0" => ""
                );
            foreach ($vf->getArrayvalores(33) as $uni) {
                
                $op = $op + array(
                    $uni["id_valor_flexible"] . '-' . $uni["valor_flexible_padre_id"] => $uni["descripcion_valor"]
                );
                
                $opciones = $opciones + $op;
            }
            $opz="";
            foreach ($vf->getvalflexseditar($iddependencia) as $datz) {
                $opz = $datz["descripcion_valor"];
            }

            $EditarproyectoForm->add(array(
                'name' => 'dependencia_academica',
                'attributes' => array(
                    'type' => 'text',
                   'value' => $filter->filter($opz),
                    //'value' => $idunidad,
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Dependencia Académica:'
                )
            ));
            
            $EditarproyectoForm->add(array(
                'name' => 'id_dependencia_academica',
                'type' => 'Zend\Form\Element\Select',
                
                'options' => array(
                    'label' => 'Dependencia Académica: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'value' => '0',
                    'id' => 'id_dependencia_academica',
                    //'required' => 'required',
                    'onchange' => 'myFunction3();'
                ) // set selected to '1'
            ));

            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            $op = array(
                    "0" => ""
                );
            foreach ($vf->getArrayvalores(34) as $uni) {
                
                $op = $op + array(
                    $uni["id_valor_flexible"] . '-' . $uni["valor_flexible_padre_id"] => $uni["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $opz="";
            foreach ($vf->getvalflexseditar($idprograma) as $datz) {
                $opz = $datz["descripcion_valor"];
            }

            $EditarproyectoForm->add(array(
                'name' => 'programa_academico',
                'attributes' => array(
                    'type' => 'text',
                   'value' => $filter->filter($opz),
                    //'value' => $idunidad,
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Programa Académico:'
                )
            ));
            
            $EditarproyectoForm->add(array(
                'name' => 'id_programa_academico',
                'type' => 'Zend\Form\Element\Select',
                
                'options' => array(
                    'label' => 'Programa Académico: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'value' => '0',
                    //'required' => 'required',
                    //'disabled' => 'disabled',
                    'id' => 'id_programa_academico'
                ) // set selected to '1'
            ));
           
            // LINEAS DE INVESTIGACIÓN
            $opciones = array(
                '0' => ''
            );
            
            foreach ($vf->getArrayvalores(40) as $dat) {
                $op = array(
                    $dat["id_valor_flexible"] . '-' . $dat["valor_flexible_padre_id"] => $dat["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            
            foreach ($vf->getvalflexseditar($idlinea) as $datz) {
                $opz = $datz["descripcion_valor"];
            }
            
            $EditarproyectoForm->add(array(
                'name' => 'linea_inv',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($opz),
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Linea Investigación:'
                )
            ));
            
            $EditarproyectoForm->add(array(
                'name' => 'id_linea_inv',
                'type' => 'Zend\Form\Element\Select',
                
                'options' => array(
                    'label' => 'Linea Investigación: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'id' => 'id_linea_inv',
                    //'required' => 'required',
                    //'disabled' => 'disabled',
                    'value' => '0'
                ) // set selected to '1'

            ));
            
            //CAMPOS DE INVESTIGACIÓN
            
            $opciones = array();
            
            foreach ($vf->getArrayvalores(37) as $dat) {
                $op = array(
                    $dat["id_valor_flexible"] => $dat["descripcion_valor"]
                );
                $opciones = $opciones + $op;
                
            }
            
            foreach ($vf->getvalflexseditar($idcampo) as $datz) {
                $opz = $datz["descripcion_valor"];
            }
            
            $EditarproyectoForm->add(array(
                'name' => 'campo',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($opz),
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Campo:'
                )
            ));
            $EditarproyectoForm->add(array(
                'name' => 'id_campo',
                'type' => 'Zend\Form\Element\Select',
                
                'options' => array(
                    'label' => 'Campo: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'id' => 'id_campo',
                    //'required' => 'required',
                    'value' => '0',
                    'onchange' => 'myFunction(this.id);'
                ) // set selected to '1'

            ));

            //Categoría dentro de la convocatoria
            $aplicar = new Aplicar ( $this->dbAdapter );
            $nombre_modalidad = "";
            $recursos_funcion = "";
            $recursos_inversion = "";
            $total_financia = "";
            $total_proy = "";
            if($proyecto[0]["id_aplicar"]){
                $info_aplicar = $aplicar->getAplicar($proyecto[0]["id_aplicar"]);
                $nombre_modalidad=$info_aplicar[0]["nombre_modalidad"];
                $recursos_funcion = $info_aplicar[0]["recursos_funcion"];
                $recursos_inversion = $info_aplicar[0]["recursos_inversion"];
                $total_financia = $info_aplicar[0]["total_financia"];
                $total_proy = $info_aplicar[0]["total_proy"];  
            }

            $EditarproyectoForm->add(array(
                'name' => 'nombre_modalidad',
                'attributes' => array(
                    'disabled' => 'disabled',
                    'maxlength' => 5000,
                    'type' => 'text',
                    'value' => $nombre_modalidad
                ),
                'options' => array(
                    'label' => 'Categoría dentro de la convocatoria:'                
                )
            ));

            //Recursos del proyecto
            $EditarproyectoForm->add(array(
                'name' => 'recursos_funcion',
                'attributes' => array(
                    'type' => 'number',
                    'readonly' => 'true',
                    'value' => $recursos_funcion,
                    'pattern'  => '([0-9]{1,3}).([0-9]{1,3})',
                    'min' => 0
                ),
                'options' => array(
                    'label' => 'Recursos de funcionamiento:'
                )
            ));

            $EditarproyectoForm->add(array(
                'name' => 'recursos_inversion',
                'attributes' => array(
                    'type' => 'number',
                    'readonly' => 'true',
                    'value' => $recursos_inversion,
                    'pattern'  => '([0-9]{1,3}).([0-9]{1,3})',
                    'min' => 0
                )
                ,
                'options' => array(
                    'label' => 'Recursos de inversión y/o otro UPN:'
                )
            ));

            $EditarproyectoForm->add(array(
                'name' => 'total_financia',
                'attributes' => array(
                    'type' => 'number',
                    'id' => 'total_financia',
                    'readonly' => 'true',
                    'value' => $total_financia,
                    'pattern'  => '([0-9]{1,3}).([0-9]{1,3})',
                    'min' => 0
                ),
                'options' => array(
                    'label' => 'Recursos de cofinanciación:'
                )
            ));

            $EditarproyectoForm->add(array(
                'name' => 'total_proy',
                'attributes' => array(
                    'type' => 'number',
                    'min' => 0,
                    'readonly' => 'true',
                    'pattern'  => '([0-9]{1,3}).([0-9]{1,3})',
                    'value' => $total_proy
                ),
                'options' => array(
                    'label' => 'Total financiación del proyecto:'
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
                $pantalla = "editarproyecto";
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
            
       
            $id = (int) $this->params()->fromRoute('id', 0);
            if ($id == 0) {
                $id_conv = $id_conv;
            } else {
                $id_conv = $id;
            }
            $Informe = new Informes($this->dbAdapter);
            
            $Tablafinproy = new Tablafinp($this->dbAdapter);
            $valflex = new Agregarvalflex($this->dbAdapter);
            $tablae = new Tablaequipop($this->dbAdapter);
            $Archivosconv = new Archivosconv($this->dbAdapter);
            $Propuesta_inv = new Actas($this->dbAdapter);
            $grupar = new Gruposproy($this->dbAdapter);
            $gruinv = new Grupoinvestigacion($this->dbAdapter);
            $a = '';
            $tittle="";
            if($dat["id_rol"]=="0"){
                $EditarproyectoForm->get("fecha_inicio")->setAttribute('disabled', 'disabled');
                $EditarproyectoForm->get("fecha_terminacion")->setAttribute('disabled', 'disabled');
                $EditarproyectoForm->get("prorroga")->setAttribute('disabled', 'disabled');
                $EditarproyectoForm->get("nombre_proy")->setAttribute('disabled', 'disabled');
                $EditarproyectoForm->get("id_investigador")->setAttribute('disabled', 'disabled');
                $EditarproyectoForm->get("resumen_ejecutivo")->setAttribute('disabled', 'disabled');
                $EditarproyectoForm->get("objetivo_general")->setAttribute('disabled', 'disabled');
                $EditarproyectoForm->get("documento_formalizacion")->setAttribute('disabled', 'disabled');
                $EditarproyectoForm->get("tipo_proyecto")->setAttribute('disabled', 'disabled');                
                $EditarproyectoForm->get("modificaciones_documento")->setAttribute('disabled', 'disabled');
                $tittle="Consulta proyecto o proceso de investigación";
            }else{
                $tittle="Editar proyecto o proceso de investigación";
            }

            if($dat["id_rol"]=="0"){

            }

            if($idestado != 2){
                $EditarproyectoForm->get("prorroga")->setAttribute('disabled', 'disabled');
            }

            if($convocatoria != "E"){
                $EditarproyectoForm->get("convocatoria")->setAttribute('disabled', 'disabled');
            }
            
            $Entidadesejecutoras = new Entidadesejecutoras($this->dbAdapter);
            $Objetivosespecificos = new Objetivosespecificos ( $this->dbAdapter );
            $Objetivosmetas = new Objetivosmetas ( $this->dbAdapter );
            $Propuestainv = new Propuestainv($this->dbAdapter);
            $view = new ViewModel(array(
                'form' => $EditarproyectoForm,
                'titulo' => $tittle,
                'datos' => $idestado,
                'ver' => $id2 = $this->params()->fromRoute('id2'),
                'url' => $this->getRequest()->getBaseUrl(),
                'Informe' => $Informe->getCronogramah($id),
                'Archivosconv' => $Archivosconv->getArchivosconv($id),
                'arch' => $Propuesta_inv->getArchivos($id),
                'grupar' => $grupar->getGruposparticipantes($id_conv),
                'Tablafinper' => $Tablafinproy->getArrayfinanciaper($id_conv),
                'tablaeper' => $tablae->getArrayequiposper($id),
                'gruinv' => $gruinv->getGrupoinvestigacion(),
                'codigo' => $codigo,
                'pr' => $Tablafinproy->caseSql($id_conv),
                'sumfuente' => $Tablafinproy->sumFuente($id_conv),
                'sumrubro' => $Tablafinproy->sumRubro($id_conv),
                'sumtotal' => $Tablafinproy->sumTotal($id_conv),
                'idconvocatoria' => $idconvocatoria,
                'usua' => $us->getUsuarios($a),
                'Tablafinproys' => $Tablafinproy->getTablafin($id),
                'tablae' => $tablae->getTablaequipo($id),
                'Tablafinproy' => $Tablafinproy->getTablafin($id),
                'Tablafinrubro' => $Tablafinproy->getArrayfinancia($id),
                'valflex' => $valflex->getValoresf(),
                'id' => $id_conv,
                'menu' => $dat["id_rol"],
                'tipoProyecto' => $tipoInvesti,
                'tipo_conv' => $tipo_conv,
                'entidadesejecutoras' => $Entidadesejecutoras->getEntidadesConvocatoria($id),
                'idPropuesta'=> $proyecto[0]["id_aplicar"],
                'objetivosespecificos' => $Objetivosespecificos->getObjetivosespecificos($proyecto[0]["id_aplicar"]),
                'objetivosmetas' => $Objetivosmetas->getObjetivosmetast(),
                'Propuestainv' => $Propuestainv->getArchivos($proyecto[0]["id_aplicar"])
            ));
            return $view;
        }
    }

    public function cerrarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $not = new Proyectos($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $resultado = $not->updateProyectosestado($id);
        
        $this->flashMessenger()->addMessage("Proyecto cerrado con exito");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editarproyecto/index/' . $id);
    }

    public function cerrarpAction()
    {
        $EditarproyectoForm = new EditarproyectoForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Proyectos($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        
        $view = new ViewModel(array(
            'form' => $EditarproyectoForm,
            'titulo' => "Cerrar Proyecto",
            'url' => $this->getRequest()->getBaseUrl(),
            'datos' => $id
        ));
        return $view;
    }

    /***********************************************************************
    Funcion que se ejecuta al ver la información de un proyecto.
    Actualiza el valor de las horas aprobadas del equipo del proyecto.
    ************************************************************************/
    public function updateTablaSiafi($codigo_proy, $id_proyecto){
        /*
        $this->dbAdapter = $this->getServiceLocator()->get('db2');
        $th = new Talentohumano($this->dbAdapter);
        

        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $tep = new Tablaequipop ($this->dbAdapter);
        $u = new Usuarios ( $this->dbAdapter);
        
        
        $horasAprobadas = $th->getProyTH(trim($codigo_proy));        
        
        $equipoProyecto = $tep->getTablaequipo($id_proyecto);
        
        //Se recorre la ix traida de la vista del SIAFI respecto al proyecto
        foreach ($horasAprobadas as $Aprobadas) {
            
            //Se recorre la ix de personal solicitado guardado en mgp_tabla_equipos
            foreach ($equipoProyecto as $eProyecto){
                
                //Es necesario traer la cedula de cada persona, ya que en la vista esta por cedula y en mgp_tabla_equipos esta por id 
                $infoUsuario = $u->getArrayusuariosid($eProyecto["id_integrante"]);
                
                //Se compara para buscar las horas aprobadas en SIAFI
                if($Aprobadas["EMP_CODIGO"]==$infoUsuario["documento"] && $Aprobadas["PERIODO"]==$eProyecto["periodo"] && $Aprobadas["ANO"]==$eProyecto["ano"]){
                    
                    //Si se encuentra un registro en SIAFI, entonces se guarda el dato en mgp_tabla_equipos
                    $tep->updateTablaequipoById($eProyecto["id_integrantes"], $Aprobadas["HORAS_AUTORIZADAS"]);
                }
            }
        }
        */
    }
}
