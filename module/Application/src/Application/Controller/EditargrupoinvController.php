<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editargrupoinv\EditargrupoinvForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Lineas;
use Application\Modelo\Entity\Gruposrel;
use Application\Modelo\Entity\Asociaciones;
use Application\Modelo\Entity\Reconocimientos;
use Application\Modelo\Entity\Redes;
use Application\Modelo\Entity\Articulos;
use Application\Modelo\Entity\Autores;
use Application\Modelo\Entity\Libros;
use Application\Modelo\Entity\Proyectosext;
use Application\Modelo\Entity\Integrantes;
use Application\Modelo\Entity\Instituciones;
use Application\Modelo\Entity\Semilleros;
use Application\Modelo\Entity\Otrasproducciones;
use Application\Modelo\Entity\Archivos;
use Application\Modelo\Entity\Bibliograficos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;
use Application\Modelo\Entity\Capitulosgrupo;
use Application\Modelo\Entity\Agregarautorgrupo;
use Application\Modelo\Entity\Redinvestigacion;
use Application\Modelo\Entity\Semillero;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Proyectosint;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Identificadoresgru;
use Application\Modelo\Entity\Eventosgru;
use Application\Modelo\Entity\Trabajogradogru;

use Application\Modelo\Entity\Documentosvinculados;
use Application\Modelo\Entity\Articuloshv;
use Application\Modelo\Entity\Libroshv;
use Application\Modelo\Entity\Capitulosusuario;
use Application\Modelo\Entity\Agregarautorusuario;
use Application\Modelo\Entity\Otrasproduccioneshv;
use Application\Modelo\Entity\Bibliograficoshv;
use Application\Modelo\Entity\Proyectosexthv;
use Application\Modelo\Entity\Proyectosintusua;
use Application\Modelo\Entity\Lineashv;

class EditargrupoinvController extends AbstractActionController
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
            $EditargrupoinvForm = new EditargrupoinvForm();
            $id = (int) $this->params()->fromRoute('id', 0);
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Grupoinvestigacion($this->dbAdapter);
            
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            $data = $this->request->getPost();
            // print_r($data);
            $res = array();
            $res = $u->getGrupoinvid2($id);
            $resultado = $u->updateGrupoinv($id, $data);
            
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
            
            $urlId = "/application/editargrupoinv/index/" . $id;
            if ($resultado != '') {
                $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                $evento = 'Edición grupo de investigación. Datos antes: ' . print_r($res,true) . " /-/ Datos ahora: ". print_r($data, true);
                $ad->addAuditoriadet($evento, $resul);
                
                $this->flashMessenger()->addMessage("Grupo actualizado con éxito.");
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . $urlId);
                //return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/grupoinv/index');
            } else {
                $resultado = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                
                $evento = 'Intento fallido de edición de grupo de investigación. Datos a cargar: '. print_r($data, true);
                $ad->addAuditoriadet($evento, $resultado);
                
                $this->flashMessenger()->addMessage("El grupo no se actualizó con éxito.");
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlId);
            }
        } else {
            
            $EditargrupoinvForm = new EditargrupoinvForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $us = new Grupoinvestigacion($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $filter = new StringTrim();
            
            $us = new Grupoinvestigacion($this->dbAdapter);
            $id = (int) $this->params()->fromRoute('id', 0);
            
            foreach ($us->getGrupoinvid($id) as $dat) {
                $dat["id_grupo_inv"];
            }
            
            $idunidad = $dat["id_unidad_academica"];
            $iddependencia = $dat["id_dependencia_academica"];    
            $idprograma    = $dat["id_programa_academico"];
            $idpais        = $dat["id_pais"];
            $iddepartamento = $dat["id_departamento"];
            $idciudad = $dat["id_ciudad"];
            
            $uni_v = $idunidad;
            $dep_v = $iddependencia;
            $v_pro = $idprograma;
            $pais_v = $idpais;
            $depar_v = $iddepartamento;
            $ciu_v  =  $idciudad;
            
            ?>
                        <script>
                        if (document.cookie.indexOf="uni_u" >= 0) {
                          // They've been here before.
                          alert("hello again uni_u");
                        }
                        else {
                          // set a new cookie
                        document.cookie="uni_u=<?php echo $uni_v; ?>";
                        }

                        if (document.cookie.indexOf="pais_v" >= 0) {
                            // They've been here before.
                            alert("hello again pais_v");
                          }
                          else {
                            // set a new cookie
                          document.cookie="pais_v=<?php echo $pais_v; ?>";
                          }
                        </script>
            <?php
            $nombreGrupoF = $filter->filter($dat["nombre_grupo"]);
            
            $EditargrupoinvForm->add(array(
                'name' => 'url',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($dat["url"])
                ),
                'options' => array(
                    'label' => 'Url :'
                )
            ));
            if ($dat["id_campo_investigacion"] == null) {
                $v_cam = 1;
            } else {
                $v_cam = $dat["id_campo_investigacion"];
            }
            // Area de Investigacion
            $vf = new Agregarvalflex($this->dbAdapter);
            $resvf = $vf->getValoresflexid($v_cam);
            $opciones = array();
            foreach ($vf->getArrayvalores(37) as $da) {
                $op = array(
                    $resvf["id_valor_flexible"] => $resvf["descripcion_valor"]
                );
                $op = $op + array(
                    $da["id_valor_flexible"] => $da["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $EditargrupoinvForm->add(array(
                'name' => 'id_campo_investigacion',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Campo/área de investigación: ',
                    
                    'value_options' => $opciones
                )
            ));
                        
            // define el campo pais
            $vfPais = new Agregarvalflex($this->dbAdapter);
            $resvfPais = $vf->getValoresflexid($pais_v);
            $opcionesPais = array();
            foreach ($vfPais->getArrayvalores(20) as $xx) {
               if($xx["id_valor_flexible"]==$dat["id_pais"]){
                     $dat["id_pais"] = trim($xx["descripcion_valor"]);
               }
            }
            $EditargrupoinvForm->add(array(
                'name' => 'id_pais',
                'type' => 'text',
                'attributes' => array(
                    'id' => 'id_pais',
                    'value' => $dat["id_pais"]
                ),
                'options' => array(
                    'label' => 'País:'                   
                )
            ));
            
            //$u = new Grupoinvestigacion($this->dbAdapter);
                        
            // define el campo departamento
            $vfDepartamento = new Agregarvalflex($this->dbAdapter);
            $resvfDepartamento = $vf->getValoresflexid($depar_v);
            $opcionesDepartamento = array();
            
            foreach ($vfDepartamento->getArrayvalores(22) as $dept) {
                $opDepartamento = array(
                    $resvfDepartamento["id_valor_flexible"] => $resvfDepartamento["descripcion_valor"]
                );
                $opDepartamento = $opDepartamento + array(
                    $dept["id_valor_flexible"] . '-' . $dept["valor_flexible_padre_id"]  => $dept["descripcion_valor"]
                );
                $opcionesDepartamento = $opcionesDepartamento + $opDepartamento;
            }
            $EditargrupoinvForm->add(array(
                'name' => 'id_departamento',
                'attributes' => array(
                    'id' => 'id_departamento',
                    'onchange' => 'fdepartamento();',
                    'disabled' => 'disabled'
                )
                ,
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Departamento:',
                    'value_options' => $opcionesDepartamento
                )
                
            ));
            
            // define el campo Ciudad
           $vfCiu = new Agregarvalflex($this->dbAdapter);
            $resvfCiu = $vf->getValoresflexid($ciu_v);
            $opcionesCiudad = array();
            foreach ($vfCiu->getArrayvalores(1) as $ciud) {
                if($ciud["id_valor_flexible"]==$dat["id_ciudad"]){
                     $dat["id_ciudad"] = trim($ciud["descripcion_valor"]);
                }
            }
            $EditargrupoinvForm->add(array(
                'name' => 'id_ciudad',
                'attributes' => array(
                    'id' => 'id_ciudad',
                    'value' => $dat["id_ciudad"]
                ),
                'type' => 'text',
                'options' => array(
                    'label' => 'Ciudad:',
                    
                )
            ));     
            
            //****************************************************************
            //****************************************************************            
            
            // Unidad Academica
            
            
            $vfUni = new Agregarvalflex($this->dbAdapter);
            $resvfUni = $vf->getValoresflexid($uni_v);
            $opcionesUni = array();
            
            foreach ($vfUni->getArrayvalores(23) as $uni) {
            
                $opUni = array(
                    $resvfUni["id_valor_flexible"] => $resvfUni["descripcion_valor"]
                );
            
                $opUni = $opUni + array(
                    $uni["id_valor_flexible"] => $uni["descripcion_valor"]
                );
            
                $opcionesUni = $opcionesUni + $opUni;
            }
            
            $EditargrupoinvForm->add(array(
                'name' => 'id_unidad_academica',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Unidad académica:',
                    'value_options' => $opcionesUni
                ),
                'attributes' => array(
                    'id' => 'id_unidad_academica',
                    'onchange' => 'fdependencia();',
                    'required' => 'required'
                )
            
            ));
            
            //****************************************************************
            //****************************************************************            
            
            // Dependencia Academica
        $vfDep = new Agregarvalflex($this->dbAdapter);
            $resvfDep = $vf->getValoresflexid($dep_v);
            $opcionesDep = array();
            foreach ($vfDep->getArrayvalores(33) as $dept) {                
                $opDep = array(
                    $resvfDep["id_valor_flexible"] => $resvfDep["descripcion_valor"]
                );
                
                $opDep = $opDep + array(
                    $dept["id_valor_flexible"] . '-' . $dept["valor_flexible_padre_id"]  => $dept["descripcion_valor"]
                );
                $opcionesDep = $opcionesDep + $opDep;
            }
            $EditargrupoinvForm->add(array(
                'name' => 'id_dependencia_academica',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Dependencia académica:',
                    'value_options' => $opcionesDep
                ),
                'attributes' => array(
                    'id' => 'id_dependencia_academica',
                    'onchange' => 'fprograma();',
                    'required' => 'required',
                    'disabled' => 'disabled'
                )
                
            ));
            
           
            
            //****************************************************************
            //****************************************************************            
            // Programa Academico
            
            $vfPro = new Agregarvalflex($this->dbAdapter);
            $resvfPro = $vf->getValoresflexid($v_pro);
            $opcionesPro = array();
        foreach ($vfPro->getArrayvalores(34) as $pro) {
                $opPro = array(
                    $resvfPro["id_valor_flexible"] => $resvfPro["descripcion_valor"]
                );
                $opPro = $opPro + array(
                    $pro["id_valor_flexible"] . '-' . $pro["valor_flexible_padre_id"] => $pro["descripcion_valor"]
                );
                $opcionesPro = $opcionesPro + $opPro;
            }
            $EditargrupoinvForm->add(array(
                'name' => 'id_programa_academico',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'id_programa_academico',
                    'disabled' => 'disabled'//,
                    //'required' => 'required',
                ),
                'options' => array(
                    'label' => 'Programa académico:',
                    'value_options' => $opcionesPro
                )
            ));
            
            
            
            $EditargrupoinvForm->add(array(
                'name' => 'plan_accion',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["plan_accion"]),
                    'placeholder' => 'Ingrese el plan de acción del grupo',
                    'lenght' => 7500,
                    'maxlength' => 7500,
                    'id' => 'plan'
                ),
                'options' => array(
                    'label' => 'Plan de acción:'
                )
            ));

            $id_lideC = $dat["id_lider"];
            $id_codigoC = $dat["cod_grupo"];

            $EditargrupoinvForm->add(array(
                'name' => 'retos',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["retos"]),
                    'placeholder' => 'Ingrese los retos del grupo',
                    'lenght' => 7500,
                    'maxlength' => 7500
                ),
                'options' => array(
                    'label' => 'Retos:'
                )
            ));
            
            $EditargrupoinvForm->add(array(
                'name' => 'estado_arte',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["estado_arte"]),
                    'placeholder' => 'Ingrese el estado del arte del grupo',
                    'lenght' => 7500,
                    'maxlength' => 7500
                ),
                'options' => array(
                    'label' => 'Estado del arte:'
                )
            ));
            
            $EditargrupoinvForm->add(array(
                'name' => 'mision',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["mision"]),
                    'placeholder' => 'Ingrese la misión del grupo',
                    'lenght' => 7500,
                    'maxlength' => 7500
                ),
                'options' => array(
                    'label' => 'Misión:'
                )
            ));


            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones_cla=array();
            foreach ($vf->getArrayvalores(21) as $daet) {
                $op=$op+array($daet["id_valor_flexible"]=>$daet["descripcion_valor"]);
                $opciones_cla=$opciones_cla+$op;
            }
            $cla_actual = $filter->filter($dat["id_clasificacion"]);

            $EditargrupoinvForm->add(array(
                'name' => 'vision',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["vision"]),
                    'placeholder' => 'Ingrese la visión del grupo',
                    'lenght' => 7500,
                    'maxlength' => 7500
                ),
                'options' => array(
                    'label' => 'Visión:'
                )
            ));
            
            $EditargrupoinvForm->add(array(
                'name' => 'email',
                'attributes' => array(
                    'type' => 'Email',
                    'placeholder' => 'Ingrese el email',
                    'value' => $filter->filter($dat["email"])
                ),
                'options' => array(
                    'label' => 'Email:'
                )
            ));
            
            $EditargrupoinvForm->add(array(
                'name' => 'telefono',
                'attributes' => array(
                    'type' => 'number',
                    'placeholder' => 'Ingrese el teléfono ',
                    'min' => 1,
                    'value' => $filter->filter($dat["telefono"])
                ),
                'options' => array(
                    'label' => 'Teléfono:'
                )
            ));

            $EditargrupoinvForm->add(array(
                'name' => 'dir_postal',
                'attributes' => array(
                    'type' => 'text',
                    'placeholder' => 'Ingrese la dirección postal/física',
                    
                    'value' => $filter->filter($dat["dir_postal"])
                ),
                'options' => array(
                    'label' => 'Dirección postal/física :'
                )
            ));
            
            $EditargrupoinvForm->add(array(
                'name' => 'plan_estrategico',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["plan_estrategico"]),
                    'placeholder' => 'Ingrese el plan estratégico del grupo',
                    'lenght' => 7500,
                    'maxlength' => 7500
                ),
                'options' => array(
                    'label' => 'Plan estratégico:'
                )
            ));
            
            $EditargrupoinvForm->add(array(
                'name' => 'descriptores',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["descriptores"]),
                    'placeholder' => 'Ingrese los descriptores del grupo',
                    'lenght' => 7500,
                    'maxlength' => 7500
                ),
                'options' => array(
                    'label' => 'Descriptores del grupo:'
                )
            ));
            
            $EditargrupoinvForm->add(array(
                'name' => 'descripcion',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["descripcion"]),
                    'placeholder' => 'Ingrese la descripción del grupo',
                    'lenght' => 7500,
                    'maxlength' => 7500
                ),
                'options' => array(
                    'label' => 'Descripción del grupo:'
                )
            ));
            
            $EditargrupoinvForm->add(array(
                'name' => 'sectores_aplicacion',
                'attributes' => array(
                    'type' => 'textarea',
                    'value' => $filter->filter($dat["sectores_aplicacion"]),
                    'placeholder' => 'Ingrese los sectores de aplicación del grupo',
                    'lenght' => 7500,
                    'maxlength' => 7500
                ),
                'options' => array(
                    'label' => 'Sectores de aplicación:'
                )
            ));
            
            $EditargrupoinvForm->add(array(
                'name' => 'fecha_creacion_grupo',
                'attributes' => array(
                    'type' => 'Date',
                    'value' => $filter->filter($dat["fecha_creacion_grupo"]),
                    'placeholder' => 'Ingrese la fecha de creacion'
                ),
                'options' => array(
                    'label' => 'Fecha de creación:'
                )
            ));
            
           
            $vf = new Agregarvalflex($this->dbAdapter);
            $opz = '';
            foreach ($vf->getvalflexseditar($dat["id_campo_investigacion"]) as $datz) {
                if ($dat["id_campo_investigacion"] != '') {
                    $opz = $datz["descripcion_valor"];
                }
            }
            
            $EditargrupoinvForm->add(array(
                'name' => 'campo_op',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($opz),
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'País : '
                )
            ));
            
            // define el campo activo
            if ($dat["estado"] == 'a' || $dat["estado"] == 'A') {
                $estado = 'Activo';
            } elseif ($dat["estado"] == 'i' || $dat["estado"] == 'I') {
                $estado = 'Inactivo';
            } else {
                $estado = '';
            }
            
            $EditargrupoinvForm->add(array(
                'name' => 'estadoOp',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $estado,
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Estado:'
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
                $pantalla = "editargrupoinv";
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

            $usua = new Usuarios($this->dbAdapter);
            $a='';
            $opciones=array("0" => "Sin asignar");
            foreach ($usua->getUsuarios($a) as $datl) {
                $op=array($datl["id_usuario"]=>$datl["primer_nombre"].' '.$datl["segundo_nombre"].' '.$datl["primer_apellido"].' '.$datl["segundo_apellido"]);
                $opciones=$opciones+$op;
            }

            if($dat["id_rol"] == 1){  
                $EditargrupoinvForm->add(array(
                    'name' => 'id_clasificacion',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'id_clasificacion',
                        'required' => 'required',
                        'value' => $cla_actual 
                    ),
                    'options' => array(
                        'label' => 'Clasificación:',
                        'value_options' => $opciones_cla
                    )
                ));

                $EditargrupoinvForm->add(array(
                    'name' => 'nombre_grupo',
                    'attributes' => array(
                        'type' => 'text',
                        'value' => $nombreGrupoF,
                        'lenght' => 120,
                        'maxlength' => 120
                    ),
                    'options' => array(
                        'label' => 'Nombre del grupo:'
                    )
                    
                ));
                
                $EditargrupoinvForm->add(array(
                    'name' => 'coordinador_2',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'coordinador_2',
                         'value' => $id_lideC
                    ),
                    'size' => 25,
                    'options' => array('label' => 'Coordinador: ',
                        'value_options' => $opciones,
                    ),
                ));

                $EditargrupoinvForm->add(array(
                    'name' => 'id_coordinador_2',
                    'attributes' => array(
                        'id' => 'id_coordinador_2',
                        'type'  => 'text'
                    ),
                    'options' => array(
                        'label' =>  'Líder del grupo:'
                    ),
                ));

                $EditargrupoinvForm->add(array(
                    'name' => 'cod_grupo',
                    'attributes' => array(
                        'type' => 'text',
                        'value' => $id_codigoC
                    ),
                    'options' => array(
                        'label' => 'Código del grupo:'
                    )
                ));
            }else{
                $EditargrupoinvForm->add(array(
                    'name' => 'id_clasificacion',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'id_clasificacion',
                        'required' => 'required',
                        'value' => $cla_actual ,
                        'disabled' => 'disabled'
                    ),
                    'options' => array(
                        'label' => 'Clasificación:',
                        'value_options' => $opciones_cla
                    )
                ));
                
                $EditargrupoinvForm->add(array(
                    'name' => 'nombre_grupo',
                    'attributes' => array(
                        'type' => 'text',
                        'value' => $nombreGrupoF,
                        'lenght' => 120,
                        'maxlength' => 120,
                        'disabled' => 'disabled'
                    ),
                    'options' => array(
                        'label' => 'Nombre del grupo:'
                    )
                    
                ));

                $EditargrupoinvForm->add(array(
                    'name' => 'coordinador_2',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'coordinador_2',
                         'value' => $id_lideC,
                         'disabled' => 'disabled'
                    ),
                    'size' => 25,
                    'options' => array('label' => 'Coordinador 2: ',
                        'value_options' => $opciones,
                    ),
                ));

                $EditargrupoinvForm->add(array(
                    'name' => 'id_coordinador_2',
                    'attributes' => array(
                        'type'  => 'text',
                        'id' => 'id_coordinador_2',
                        'disabled' => 'disabled'
                    ),
                    'options' => array(
                        'label' =>  'Líder del grupo:'
                    ),
                ));

                $EditargrupoinvForm->add(array(
                    'name' => 'cod_grupo',
                    'attributes' => array(
                        'type' => 'text',
                        'value' => $id_codigoC,
                        'disabled' => 'disabled'
                    ),
                    'options' => array(
                        'label' => 'Código del grupo:'
                    )
                ));
            }

            if (true) {
                $li = new Lineas($this->dbAdapter);
                $gr = new Gruposrel($this->dbAdapter);
                $as = new Asociaciones($this->dbAdapter);
                $rec = new Reconocimientos($this->dbAdapter);
                $red = new Redes($this->dbAdapter);
                $int = new Integrantes($this->dbAdapter);
                $art = new Articulos($this->dbAdapter);
                $lib = new Libros($this->dbAdapter);
                $proyext = new Proyectosext($this->dbAdapter);
                $otras = new Otrasproducciones($this->dbAdapter);
                $sem = new Semilleros($this->dbAdapter);
                $inst = new Instituciones($this->dbAdapter);
                $arch = new Archivos($this->dbAdapter);
                $usuario = new Usuarios($this->dbAdapter);
                $valflex = new Agregarvalflex($this->dbAdapter);
                $biblio = new Bibliograficos($this->dbAdapter);
                $auto = new Autores($this->dbAdapter);
                $autoresgrupo = new Agregarautorgrupo($this->dbAdapter);
                $capitulosgrupo = new Capitulosgrupo($this->dbAdapter);
                $redinvestigacion = new Redinvestigacion($this->dbAdapter);
                $semillerosinvestigacion = new Semillero($this->dbAdapter);
                $proyectos = new Proyectos($this->dbAdapter);
                $tablaequipo = new Tablaequipop($this->dbAdapter);
                $proyectosint = new Proyectosint($this->dbAdapter);
                $Documentosvinculados = new Documentosvinculados($this->dbAdapter);  
                $Articuloshv = new Articuloshv($this->dbAdapter);   
                $libroshv = new Libroshv($this->dbAdapter);
                $Capitulosusuario = new Capitulosusuario($this->dbAdapter);
                $autoresusuario = new Agregarautorusuario($this->dbAdapter);
                $Otrasproduccioneshv = new Otrasproduccioneshv($this->dbAdapter);
                $Bibliograficoshv = new Bibliograficoshv($this->dbAdapter);
                $Proyectosexthv = new Proyectosexthv($this->dbAdapter);
                $Proyectosintusua = new Proyectosintusua($this->dbAdapter);     
                $LineasHv = new Lineashv($this->dbAdapter);
                $identificadores = new Identificadoresgru($this->dbAdapter); 
                $eventos = new Eventosgru($this->dbAdapter); 
                $formacion = new Trabajogradogru($this->dbAdapter);
                $semillero = new Semillero($this->dbAdapter); 

                $view = new ViewModel(array(
                    'form' => $EditargrupoinvForm,
                    'titulo' => "Editar Grupo de Investigaci&oacute;n",
                    'id' => $id,
                    'url' => $this->getRequest()->getBaseUrl(),
                    'datos' => $us->getGrupoinvid($id),
                    'datos2' => $us->getGrupoinvestigacion(),
                    'lineas' => $li->getLineas($id),
                    'grupos' => $gr->getGruposrel($id),
                    'asociaciones' => $as->getAsociaciones($id),
                    'reconocimientos' => $rec->getReconocimientos($id),
                    'redes' => $red->getRedes($id),
                    'usuarios' => $usuario->getArrayusuarios(),
                    'articulos' => $art->getArticulos($id),
                    'auto' => $auto->getAutoresi(),
                    'integrantes' => $int->getIntegrantes($id),
                    'libros' => $lib->getLibros($id),
                    'proyext' => $proyext->getProyectosext($id),
                    'semilleros' => $sem->getSemilleros($id),
                    'instituciones' => $inst->getInstituciones($id),
                    'otras' => $otras->getOtrasproducciones($id),
                    'biblio' => $biblio->getBibliograficos($id),
                    'valflex' => $valflex->getValoresf(),
                    'arch' => $arch->getArchivos($id),
                    'menu' => $dat["id_rol"],
                    'autoresgrupo' => $autoresgrupo->getAgregarautorgrupo($id),
                    'capitulos' => $capitulosgrupo->getCapitulogrupo($id),
                    'redinvestigacion' => $redinvestigacion->getRedinv(),
                    'proyectosint' =>  $proyectosint->getProyectos($id),
                    'proyectos' =>  $proyectos->getProyectoh(),
                    'tablaequipo' =>  $tablaequipo->getTablaequipot(),
                    'semillerosinvestigacion' => $semillerosinvestigacion->getSemilleroinv(),
                    'dataDocumentos' => $Documentosvinculados->getDocumentosvinculadosByEstado($id, "1"),
                    'Articuloshv' => $Articuloshv->getArticuloshvt(),
                    'libroshv' => $libroshv->getLibroshvt(),
                    'capituloshv' => $Capitulosusuario->getCapitulot(),
                    'autoresusuario' => $autoresusuario->getAgregarautorusuariot(),
                    'Otrasproduccioneshv' => $Otrasproduccioneshv->getOtrasproduccioneshvt(),
                    'Bibliograficoshv' => $Bibliograficoshv->getBibliograficost(),
                    'Proyectosexthv' => $Proyectosexthv->getProyectosexthvt(),
                    'Proyectosintusua' =>  $Proyectosintusua->getProyectosi(),
                    'LineasHv' => $LineasHv->getLineashvt(),

                    'identificadores' => $identificadores->getIdentificadoresgru($id),
                    'eventos' => $eventos->getEventosgru($id),
                    'formacion' => $formacion->getTrabajogradogru($id)
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/grupoinv/index');
            }
        }
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Grupoinvestigacion($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarGrupoinv($id);
        $this->flashMessenger()->addMessage("Registro eliminado con exito");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/grupoinv/index');
    }

    public function delAction()
    {
        $EditargrupoinvForm = new EditargrupoinvForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Grupoinvestigacion($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $EditargrupoinvForm,
            'titulo' => "Eliminar registro",
            'url' => $this->getRequest()->getBaseUrl(),
            'datos' => $id,
            'id2' => $id2
        ));
        return $view;
    }
}
