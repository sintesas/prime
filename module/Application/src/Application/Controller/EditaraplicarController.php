<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\EditaraplicarForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Aplicar;
use Application\Modelo\Entity\Tablafinproy;
use Application\Modelo\Entity\Camposaddproy;
use Application\Modelo\Entity\Talentohumano;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Cronogramaap;
use Application\Modelo\Entity\Requisitos;
use Application\Modelo\Entity\Aspectoeval;
use Application\Modelo\Entity\Requisitosdoc;
use Application\Modelo\Entity\Requisitosap;
use Application\Modelo\Entity\Requisitosapdoc;
use Application\Modelo\Entity\Propuestainv;
use Application\Modelo\Entity\Rolesconv;
use Application\Modelo\Entity\Archivosconv;
use Application\Modelo\Entity\Url;
use Application\Modelo\Entity\Tablafin;
use Application\Modelo\Entity\Camposadd;
use Application\Modelo\Entity\Tablaequipo;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Gruposparticipantes;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Auditoria;
use Application\Pdf\PdfgiForm;
use DOMPDFModule\View\Model\PdfModel;
use Application\Modelo\Entity\Auditoriadet;

class EditaraplicarController extends AbstractActionController
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
            $this->auth = new AuthenticationService();
            $auth = $this->auth;
            
            // verifica si esta conectado
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            // Creacion adaptador de conexion, objeto de datos, auditoria
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $not = new Aplicar($this->dbAdapter);
            
            $rol = new Roles($this->dbAdapter);
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
            }
            
            // obtiene la informacion de las pantallas
            $data = $this->request->getPost();
            //print_r($data);
            $EditaraplicarForm = new EditaraplicarForm();
            // adiciona la noticia
            $id = (int) $this->params()->fromRoute('id', 0);
            $consultaProy = (String) $this->params()->fromRoute('id2', 0);
            //echo 'ver: ' . $consultaProy;
            $ap = $not->getAplicar($id);
            
            foreach ($ap as $p) {
                $id_conv = $p["id_convocatoria"];
            }
            $conv = new Convocatoria($this->dbAdapter);
            $conestado = $conv->getConcop($id_conv);

            
            if ($conestado->id_estado == 'R') {
                $resultado == 0;
            } else {
                $resultado = $not->updateAplicar($id, $data);
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
            
            // redirige a la pantalla de inicio del controlador
            if ($resultado != '') {
                $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                $evento = 'Edicion de aplicacion : ' . $resultado;
                $ad->addAuditoriadet($evento, $resul);
                $this->flashMessenger()->addMessage("Aplicar actualizado con exito");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/editaraplicar/index/' . $id);
            } else {
                $this->flashMessenger()->addMessage("La creacion de la convocatoria fallo o esta fuera del limite de tiempo para aplicar");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/editaraplicar/index/' . $id);
            }
        } else {
            $idConvocatoriaRoles = "";
            $this->auth = new AuthenticationService();
            $auth = $this->auth;
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $EditaraplicarForm = new EditaraplicarForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Aplicar($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            
            $filter = new StringTrim();
            
            $id = (int) $this->params()->fromRoute('id', 0);
            $consultaProy = (String) $this->params()->fromRoute('id2', 0);
            $id3 = (String) $this->params()->fromRoute('id3', 0);
            $ap = $u->getAplicar($id);
            
            foreach ($ap as $p) {
                $id_conv = $p["id_convocatoria"];
            }
            $idConvocatoriaRoles = $id_conv;
            $conv = new Convocatoria($this->dbAdapter);
            $conestado = $conv->getConcop($id_conv);
            
            $estado = "";
            
            //echo '$conestado->id_estado: ' . $conestado->id_estado;
            
            if ($conestado->id_estado == 'R') {
                $estado = 0;
                $ds = 0; 
            }else{
                $estado = $conestado->id_estado;
            }
            
            if ($id != 0) {
                foreach ($u->getAplicar($id, 'i') as $dat) {
                    $id_conv = $dat["id_aplicar"];
                }
            } else {
                foreach ($u->getAplicarh() as $dat) {
                    $id_conv = $dat["id_aplicar"];
                }
            }
            
            $h = date("d") . '-' . date("m") . '-' . date("Y");
            $hoy = date_create($h);
            
            $year = date("Y");
            
            $val1 = '05-05-' . $year;
            $val2 = '01-07-' . $year;
            $val3 = '31-12-' . $year;
            $hoy1 = date_create($val1);
            $corte06 = date_create($val2);
            $corte31 = date_create($val3);
            
            $multi = 0;
            
            // semestre
            if ($dat["periodo"] == 'S') {
                // 1 semestre
                if ($dat["duracion"] < 2) {
                    $multi = 1;
                }                // 2 semestres al inicio a?o
                elseif ($dat["duracion"] == 2) {
                    if ($hoy < $corte06) {
                        $multi = 1;
                    } else {
                        $multi = 2;
                    }
                }                // mas de 2 semestres
                elseif ($dat["duracion"] == 3) {
                    $multi = 2;
                } elseif ($dat["duracion"] == 4) {
                    if ($hoy < $corte06) {
                        $multi = 2;
                    } else {
                        $multi = 3;
                    }
                } elseif ($dat["duracion"] == 5) {
                    $multi = 3;
                } elseif ($dat["duracion"] == 6) {
                    if ($hoy < $corte06) {
                        $multi = 3;
                    } else {
                        $multi = 4;
                    }
                } elseif ($dat["duracion"] == 7) {
                    $multi = 4;
                } elseif ($dat["duracion"] == 8) {
                    if ($hoy < $corte06) {
                        $multi = 4;
                    } else {
                        $multi = 5;
                    }
                }
            } else {
                if ($dat["duracion"] < 6) {
                    $multi = 1;
                } elseif ($dat["duracion"] > 6 && $dat["duracion"] <= 12) {
                    if ($hoy < $corte06) {
                        $multi = 1;
                    } else {
                        $multi = 2;
                    }
                } elseif ($dat["duracion"] > 12 && $dat["duracion"] <= 24) {
                    $multi = 2;
                }
            }
            
            $ex = null;
            $tf = new Tablafin($this->dbAdapter);
            $pf = new Tablafinproy($this->dbAdapter);
            
            foreach ($pf->getTablafin($id_conv) as $existe) {
                $ex = $existe["id_aplicar"];
            }
            if ($ex == null) {
                
                foreach ($tf->getTablafin($dat["id_convocatoria"]) as $tabla) {
                    for ($x = 1; $x <= $multi; $x ++) {
                        $resul = $pf->addTablafin($tabla["id_rubro"], $tabla["id_fuente"], $id_conv, $x);
                    }
                }
            }
            
            $exc = null;
            $ff = new Camposadd($this->dbAdapter);
            $cc = new Camposaddproy($this->dbAdapter);
            
            foreach ($cc->getcamposaddproy($id_conv) as $existec) {
                $exc = $existec["id_aplicar"];
            }
            if ($exc == null) {
                
                foreach ($ff->getCamposadd($dat["id_convocatoria"]) as $tablac) {
                    
                    if ($tablac["obligatorio"] == 'S') {
                        $resul = $cc->addCamposaddproy(trim($tablac["titulo"]) . ' (Campo obligatorio)', $id_conv);
                    } else {
                        $resul = $cc->addCamposaddproy(trim($tablac["titulo"]), $id_conv);
                    }
                }
            }
            
            $excs1 = null;
            $ff = new Requisitos($this->dbAdapter);
            $cc = new Requisitosap($this->dbAdapter);
            foreach ($cc->getRequisitosap($id_conv) as $existec) {
                $excs1 = $existec["id_aplicar"];
            }
            
            if ($excs1 == null) {
                
                foreach ($ff->getRequisitos($dat["id_convocatoria"]) as $tablac) {
                    
                    $resul = $cc->addRequisitosdoc($tablac["descripcion"], $tablac["id_tipo_requisito"], $tablac["observaciones"], $tablac["id_requisito"], $id_conv);
                }
            }
            
            $excs = null;
            $ff = new Requisitosdoc($this->dbAdapter);
            $cc = new Requisitosapdoc($this->dbAdapter);
            
            foreach ($cc->getRequisitosapdoc($id_conv) as $existec) {
                $excs = $existec["id_aplicar"];
            }
            if ($excs == null) {
                foreach ($ff->getRequisitosdoc($dat["id_convocatoria"]) as $tablac) {
                    $resul = $cc->addRequisitosdoc($tablac["descripcion"], $tablac["fecha_limite"], $tablac["observaciones"], $tablac["id_requisito_doc"], $id_conv);
                }
            }
            $idarea = $dat["id_area_tematica"];
            $idunidad = $dat["id_unidad_academica"];
            $iddependencia= $dat["id_dependencia_academica"];
            $idlinea = $dat["id_linea_inv"];
            $idcampo = $dat["id_campo"];
            $idprograma = $dat["id_programa_academico"];
            $idcategoria = $dat["id_categoria"];
            $idinvestigador = $dat["id_investigador"];
            
            $EditaraplicarForm->add(array(
                'name' => 'resumen_ejecutivo',
                'attributes' => array(
                    'type' => 'textarea',
                    'required' => 'required',
                    'value' => $filter->filter($dat["resumen_ejecutivo"]),
                    'placeholder' => 'Ingrese el resumen ejecutivo',
                    'lenght' => 500
                ),
                'options' => array(
                    'label' => 'Resumen Ejecutivo :'
                )
            ));
            
            $EditaraplicarForm->add(array(
                'name' => 'resumen_ejecutivo_desc',
                'attributes' => array(
                    'type' => 'textarea',
                    'disabled' => 'disabled',
                    'value' => $filter->filter($dat["resumen_ejecutivo"]),
                    'placeholder' => 'Ingrese el resumen ejecutivo',
                    'lenght' => 500
                ),
                'options' => array(
                    'label' => 'Resumen Ejecutivo :'
                )
            ));
            
            $EditaraplicarForm->add(array(
                'name' => 'objetivo_general',
                'attributes' => array(
                    'type' => 'textarea',
                    'required' => 'required',
                    'value' => $filter->filter($dat["objetivo_general"]),
                    'placeholder' => 'Ingrese el objetivo general',
                    'lenght' => 500
                ),
                'options' => array( 
                    'label' => 'Objetivo General:'
                )
            ));
            
            $EditaraplicarForm->add(array(
                'name' => 'objetivo_general_desc',
                'attributes' => array(
                    'type' => 'textarea',
                    'disabled' => 'disabled',
                    'value' => $filter->filter($dat["objetivo_general"]),
                    'placeholder' => 'Ingrese el objetivo general',
                    'lenght' => 500
                ),
                'options' => array(
                    'label' => 'Objetivo General:'
                )
            ));
            
            setlocale(LC_MONETARY, 'en_US');
            
            $EditaraplicarForm->add(array(
                'name' => 'total_proy_desc',
                'attributes' => array(
                    'type' => 'text',
                    // ORIGINAL//'value'=>$filter->filter(money_format('%.0n',floor($dat["total_proy"]))),
                    'value' => $filter->filter(floor($dat["total_proy"])), // JLOPEZ
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Total financiación del proyecto:'
                )
            ));

            $EditaraplicarForm->add(array(
                'name' => 'total_financia_desc',
                'attributes' => array(
                    'type' => 'text',
                    // ORIGINAL//'value'=>$filter->filter(money_format('%.0n',floor($dat["total_financia"]))),
                    'value' => $filter->filter(floor($dat["total_financia"])), // JLOPEZ
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Otro tipo de recurso:'
                )
            ));
            
            $EditaraplicarForm->add(array(
                'name' => 'recursos_funcion_desc',
                'attributes' => array(
                    'type' => 'text',
                    // ORIGINAL//'value'=>$filter->filter(money_format('%.0n',floor($dat["recursos_funcion"]))),
                    'value' => $filter->filter(floor($dat["recursos_funcion"])), // JLOPEZ
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Recursos funcionamiento y/o contrapartida UPN :'
                )
            ));
            
            $EditaraplicarForm->add(array(
                'name' => 'recursos_inversion_desc',
                'attributes' => array(
                    'type' => 'text',
                    // ORIGINAL//'value'=>$filter->filter(money_format('%.0n',floor($dat["recursos_inversion"]))),
                    'value' => $filter->filter(floor($dat["recursos_inversion"])), // JLOPEZ
                    'disabled' => 'disabled'
                )
                ,
                'options' => array(
                    'label' => 'Recursos de inversión u otro UPN:'
                )
                
            ));
            
            $EditaraplicarForm->add(array(
                'name' => 'total_financia',
                'attributes' => array(
                    'type' => 'text',
                    'required' => 'required',
                    'value' => $filter->filter($dat["total_financia"]),
                    'placeholder' => 'Ingrese el total de cofinanciación del proyecto  ',                    
                    'onkeyup' => 'sumValores()'
                ),
                'options' => array(
                    'label' => 'Recursos de entidades(es) externas(s) y/o cofinanciación:'//Jarevalo
                )
            ));
            
            $EditaraplicarForm->add(array(
                'name' => 'recursos_inversion',
                'attributes' => array(
                    'type' => 'text',
                    'required' => 'required', 
                    'value' => $filter->filter($dat["recursos_inversion"]),
                    'placeholder' => 'Ingrese los recursos de inversión ', 
                    'onkeyup' => 'sumValores()'
                ),
                'options' => array(
                    'label' => 'Recursos de inversión u otro UPN:'//Jarevalo
                )
                
            ));
            
            $EditaraplicarForm->add(array(
                'name' => 'recursos_funcion',
                'attributes' => array(
                    'type' => 'text',
                    'required' => 'required',
                    'value' => $filter->filter($dat["recursos_funcion"]),
                    'placeholder' => 'Ingrese los recursos de funcionamiento y/o contrapartida ', 
                    'onkeyup' => 'sumValores()'
                ),
                'options' => array(
                    'label' => 'Recursos funcionamiento y/o contrapartida UPN :'
                )
            ));

            $EditaraplicarForm->add(array(
                'name' => 'total_proy',
                'attributes' => array(
                    'type' => 'number',
                    'min' => 0,
                    'value' => $filter->filter(floor($dat["total_proy"])),
                    'placeholder' => 'Ingrese el total de financiación del proyecto',
                    'required' => 'required',
                    'readonly' => 'true'
                ),
                'options' => array(
                    'label' => 'Valor Total del Proyecto :'
                )
            ));
            

            $EditaraplicarForm->add(array(
                'name' => 'nombre_proy',
                'attributes' => array(
                    'type' => 'text',
                    'required' => 'required',
                    'value' => $filter->filter($dat["nombre_proy"]),
                    'placeholder' => 'Ingrese el nombre del proyecto'
                ),
                'options' => array(
                    'label' => 'Nombre Proyecto :'
                )
            
                
            ));
            
            $EditaraplicarForm->add(array(
                'name' => 'nombre_proy_desc',
                'attributes' => array(
                    'type' => 'text',
                    'disabled' => 'disabled',
                    'value' => $filter->filter($dat["nombre_proy"]),
                    'placeholder' => 'Ingrese el nombre del proyecto'
                ),
                'options' => array(
                    'label' => 'Nombre Proyecto :'
                )
            
            
            ));
            
            if ($filter->filter($dat["periodo"]) == 'M') {
                $per = 'MESES';
            } else {
                $per = 'SEMESTRE';
            }
            $EditaraplicarForm->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'periodo',
                'required' => 'required',
                'options' => array(
                    'label' => 'PerÃ­odo',
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
            $us = new Usuarios($this->dbAdapter);
            foreach ($us->getUsuarios($idinvestigador) as $usid) {
                $nombre = $usid["primer_nombre"] . ' ' . $usid["primer_apellido"];
                $opciones = array(
                    $usid["id_usuario"] => $nombre
                );
            }
            
            $EditaraplicarForm->add(array(
                'name' => 'id_investigador',
                'type' => 'Zend\Form\Element\Select',
                'disabled' => 'True',
                'options' => array(
                    'label' => 'Investigador Principal: ',
                    'value_options' => $opciones
                )
            ));
            
            $EditaraplicarForm->add(array(
                'name' => 'codigo_proy',
                'attributes' => array(
                    'type' => 'text',
                    
                    'value' => $filter->filter($dat["codigo_proy"])
                ),
                'options' => array(
                    'label' => 'Código Proyecto :'
                )
            ));
            
            // ------------------------------------------------------------//
            
            // Duraci�n
            
            $EditaraplicarForm->add(array(
                'name' => 'duracion',
                'attributes' => array(
                    'type' => 'text',
                    'placeholder' => 'Ingrese la duración',
                    'required' => 'required',
                    'value' => $filter->filter($dat["duracion"])
                ),
                'options' => array(
                    'label' => 'Duración:'
                )
            ));
            
            // ------------------------------------------------------------//
            
            // �rea Tematica
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array(
                '' => ''
            );
            foreach ($vf->getArrayvalores(42) as $dat) {
                $op = array(
                    $dat["id_valor_flexible"] => $dat["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $opz="";
            
            foreach ($vf->getvalflexseditar($idarea) as $datz) {
                $opz = $datz["descripcion_valor"];
            }
            $EditaraplicarForm->add(array(
                'name' => 'area_tematica',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($opz),
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Area Temática:'
                )
            ));
            $EditaraplicarForm->add(array(
                'name' => 'id_area_tematica',
                'type' => 'Zend\Form\Element\Select',
                
                'options' => array(
                    'label' => 'Area Temática:',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'value' => $idarea,
                    'required' => 'required' 
                ) // set selected to '1'

            ));
            
            // ------------------------------------------------------------//
            
            // Unidad Academica
            
            $opciones = array(
                '' => ''
            );
            
            foreach ($vf->getvalflexseditar($idunidad) as $datz) {
                $opz = $datz["descripcion_valor"];
            }
            
            foreach ($vf->getArrayvalores(23) as $dat) {                
                $op = array(
                    $dat["id_valor_flexible"] => $dat["descripcion_valor"]
                );
                
                $opciones = $opciones  + $op;
            }
            
            $EditaraplicarForm->add(array(
                'name' => 'unidad_academica',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($opz),
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Unidad Académica:'
                )
            ));
            
            $EditaraplicarForm->add(array(
                'name' => 'id_unidad_academica',
                'type' => 'Zend\Form\Element\Select',
                
                'options' => array(
                    'label' => 'Unidad Académica: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'id' => 'id_unidad_academica',
                    'onchange' => 'myFunction2();',
                    'value' => $idunidad,
                    'required' => 'required'
                ) // set selected to '1'

            ));
            
            // ------------------------------------------------------------//
            
            // Dependencia Academica
            
            $opciones = array(
                '' => ''
            );
            
            foreach ($vf->getvalflexseditar($iddependencia) as $datz) {
                $opz = $datz["descripcion_valor"];
            }
            
            foreach ($vf->getArrayvalores(33) as $dat) {
                $op = array(
                    $dat["id_valor_flexible"] . '-' . $dat["valor_flexible_padre_id"]  => $dat["descripcion_valor"]
                );
            
                $opciones = $opciones  + $op;
            }
            
            $EditaraplicarForm->add(array(
                'name' => 'dependencia_academica',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($opz),
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Dependencia Académica:'
                )
            ));
            
            $EditaraplicarForm->add(array(
                'name' => 'id_dependencia_academica',
                'type' => 'Zend\Form\Element\Select',
            
                'options' => array(
                    'label' => 'Dependencia Académica: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'id' => 'id_dependencia_academica',
                    'onchange' => 'myFunction3();',
                    'value' => $iddependencia.'-'.$idunidad,
                    'required' => 'required'
                ) // set selected to '1'
            
            ));
            
            // ------------------------------------------------------------//
            
            // Campos de Investigaci�n
            
            $opciones = array(
                '' => ''
            );
            foreach ($vf->getArrayvalores(37) as $dat) {
                $op = array(
                    $dat["id_valor_flexible"] => $dat["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            
            foreach ($vf->getvalflexseditar($idcampo) as $datz) {
                $opz = $datz["descripcion_valor"];
            }
            $EditaraplicarForm->add(array(
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
            $EditaraplicarForm->add(array(
                'name' => 'id_campo',
                'type' => 'Zend\Form\Element\Select',
                
                'options' => array(
                    'label' => 'Campo de investigación: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'id' => 'id_campo',
                    'onchange' => 'myFunction(this.id);',
                    'value' => $idcampo 
                )
            ));
            
            // ------------------------------------------------------------// 
            // Lineas de Investigaci�n
            
            $opciones = array(
                '' => ''
            );
            
            foreach ($vf->getArrayvalores(40) as $dat) {
                
                $op = array(
                    $dat["id_valor_flexible"] . '-' . $dat["valor_flexible_padre_id"] => $dat["descripcion_valor"]
                );
                    $opciones = $opciones + $op;
            }
            
            foreach ($vf->getvalflexseditar($idlinea) as $datz) {
                $opz = $datz["descripcion_valor"];
                $idPad = $datz["valor_flexible_padre_id"];
            }
            
            $EditaraplicarForm->add(array(
                'name' => 'linea_inv',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($opz),
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Línea investigación:'
                )
            ));

            $EditaraplicarForm->add(array(
                'name' => 'id_linea_inv',
                'type' => 'Zend\Form\Element\Select',
                
                'options' => array(
                    'label' => 'Línea investigación: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'id' => 'id_linea_inv',
                    'required' => 'required',
                    'value' => $idlinea."-".$idcampo
                )
            ));
            
            // ------------------------------------------------------------//
            
           
            
            // Categoria
            
            $opciones = array(
                '' => ''
            );
            foreach ($vf->getArrayvalores(41) as $dat) {
                $op = array(
                    $dat["id_valor_flexible"] => $dat["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            
            foreach ($vf->getvalflexseditar($idcategoria) as $datz) {
                $opz = $datz["descripcion_valor"];
            }
            $EditaraplicarForm->add(array(
                'name' => 'categoria',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($opz),
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Categoría:'
                )
            ));
            $EditaraplicarForm->add(array(
                'name' => 'id_categoria',
                'type' => 'Zend\Form\Element\Select',
                
                'options' => array(
                    'label' => 'Categoría: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'required' => 'required',
                    'value' => $idcategoria
                )
            ));
            
            // ------------------------------------------------------------//
            
            // Progama
            $opciones = array(
                '' => ''
            );
            foreach ($vf->getArrayvalores(34) as $dat) {
                $op = array(
                    $dat["id_valor_flexible"] . '-' . $dat["valor_flexible_padre_id"]=> $dat["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            
            foreach ($vf->getvalflexseditar($idprograma) as $datz) {
                $opz = $datz["descripcion_valor"];
            }
            $EditaraplicarForm->add(array(
                'name' => 'programa_inv',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($opz),
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Programa Académico:'
                )
            ));
            $EditaraplicarForm->add(array(
                'name' => 'id_programa_inv',
                'type' => 'Zend\Form\Element\Select',
                
                'options' => array(
                    'label' => 'Programa Académico: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'id' => 'id_programa_inv',
                    'value' => $idprograma."-".$iddependencia,
                    'required' => 'required',
                    'readolny' => 'true'
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
            
            $ds = 0;
            
            // me verifica el tipo de rol asignado al usuario
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
            }
            
            if ($dat["id_rol"] != '') {
                // me verifica el permiso sobre la pantalla
                $pantalla = "editaraplicar";
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
                $id = (int) $this->params()->fromRoute('id', 0);
                if ($id == 0) {
                    $id_conv = $id_conv;
                } else {
                    $id_conv = $id;
                }

                $Cronograma = new Cronogramaap($this->dbAdapter);
                $Requisitos = new Requisitos($this->dbAdapter);
                $Aspectoeval = new Aspectoeval($this->dbAdapter);
                $Requisitosdoc = new Requisitosdoc($this->dbAdapter);
                $Rolesconv = new Rolesconv($this->dbAdapter);
                $Archivosconv = new Archivosconv($this->dbAdapter);
                $Propuesta_inv = new Propuestainv($this->dbAdapter);
                $Url = new Url($this->dbAdapter);
                $Tablafinproy = new Tablafinproy($this->dbAdapter);
                $valflex = new Agregarvalflex($this->dbAdapter);
                $campos = new Camposadd($this->dbAdapter);
                $camposaddproy = new Camposaddproy($this->dbAdapter);
                $rolesuser = new Roles($this->dbAdapter);
                $grupar = new Gruposparticipantes($this->dbAdapter);
                $tablae = new Tablaequipo($this->dbAdapter);
                $gruinv = new Grupoinvestigacion($this->dbAdapter);
                $us = new Usuarios($this->dbAdapter);
                $docreq = new Requisitosapdoc($this->dbAdapter);
                $conv = new Convocatoria($this->dbAdapter);
                $a = '';
                $titulo="";
                if($this->params()->fromRoute('id2')=="aplicar"){
                    $titulo="Aplicar a la convocatoria";
                }else{
                    $titulo="Editar propuesta de investigación";
                }

                if($id3 != 0){
                    $pdf = new PdfModel();        
                    $pdf->setOption('filename', 'certificado_aplicacion.pdf');
                    $pdf->setOption('paperSize', 'letter');
                    $pdf->setOption('paperOrientation', 'portrait');
                    $valpdf="Si";

                    $pdf->setVariables(array(
                        'datos' => $u->getAplicar($id_conv),
                        'usua' => $us->getUsuarios($a),
                        'valpdf' => "Si",
                        'convo' => $conv->getConvocatoria(),

                        'Requisitos' => $Requisitos->getRequisitos($id_conv),
                        'Aspectoeval' => $Aspectoeval->getAspectoeval($id_conv),
                        'Requisitosdoc' => $Requisitosdoc->getRequisitosdoc($id_conv),
                        'Rolesconv' => $Rolesconv->getRolesconv($idConvocatoriaRoles),
                        'Archivosconv' => $Archivosconv->getArchivosconv($id_conv),
                        'arch' => $Propuesta_inv->getArchivos($id_conv),
                        'Tablafinproy' => $Tablafinproy->getTablafin($id_conv),
                        'pr' => $Tablafinproy->caseSql($id_conv),
                        'sumfuente' => $Tablafinproy->sumFuente($id_conv),
                        'sumrubro' => $Tablafinproy->sumRubro($id_conv),
                        'sumtotal' => $Tablafinproy->sumTotal($id_conv),
                        'des' => $ds,
                        'estado' => $estado,
                        'Tablafinper' => $Tablafinproy->getArrayfinanciaper($id_conv),
                        'Tablafinrubro' => $Tablafinproy->getArrayfinancia($id_conv),
                        'Camposaddproy' => $camposaddproy->getCamposaddproy($id_conv),
                        'tablae' => $tablae->getTablaequipo($id_conv),
                        'grupar' => $grupar->getGruposparticipantes($id_conv),
                        'docreq' => $docreq->getRequisitosapdoc($id_conv),
                        'gruinv' => $gruinv->getGrupoinvestigacion(),
                        'campos' => $campos->getCamposadd($id_conv),
                        'Urls' => $Url->getUrl($id_conv),
                        'valflex' => $valflex->getValoresf(),
                    ));
                    return $pdf;
                }else{
                    $view = new ViewModel(array(
                        'form' => $EditaraplicarForm,
                        'titulo' => $titulo,
                        'datos' => $u->getAplicar($id_conv),
                        'ver' => $this->params()->fromRoute('id2'),
                        'url' => $this->getRequest()->getBaseUrl(),
                        'Cronograma' => $Cronograma->getCronogramah($id_conv),
                        'Requisitos' => $Requisitos->getRequisitos($id_conv),
                        'usua' => $us->getUsuarios($a),
                        'Aspectoeval' => $Aspectoeval->getAspectoeval($id_conv),
                        'Requisitosdoc' => $Requisitosdoc->getRequisitosdoc($id_conv),
                        'Rolesconv' => $Rolesconv->getRolesconv($idConvocatoriaRoles),
                        'Archivosconv' => $Archivosconv->getArchivosconv($id_conv),
                        'arch' => $Propuesta_inv->getArchivos($id_conv),
                        'Tablafinproy' => $Tablafinproy->getTablafin($id_conv),
                        'pr' => $Tablafinproy->caseSql($id_conv),
                        'sumfuente' => $Tablafinproy->sumFuente($id_conv),
                        'sumrubro' => $Tablafinproy->sumRubro($id_conv),
                        'sumtotal' => $Tablafinproy->sumTotal($id_conv),
                        'des' => $ds,
                        'estado' => $estado,
                        'Tablafinper' => $Tablafinproy->getArrayfinanciaper($id_conv),
                        'Tablafinrubro' => $Tablafinproy->getArrayfinancia($id_conv),
                        'Camposaddproy' => $camposaddproy->getCamposaddproy($id_conv),
                        'tablae' => $tablae->getTablaequipo($id_conv),
                        'grupar' => $grupar->getGruposparticipantes($id_conv),
                        'docreq' => $docreq->getRequisitosapdoc($id_conv),
                        'gruinv' => $gruinv->getGrupoinvestigacion(),
                        'campos' => $campos->getCamposadd($id_conv),
                        'Urls' => $Url->getUrl($id_conv),
                        'valflex' => $valflex->getValoresf(),
                        'rolesuser' => $rolesuser->getRoles(),
                        'id' => $id_conv,
                        'menu' => $dat["id_rol"],
                        'consultaProy' => $consultaProy,
                        'valpdf' => "No"
                    ));
                    return $view;
                }
            } else {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/mensajeadministrador/index');
            }
        }
    }
}