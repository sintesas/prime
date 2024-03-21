<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarusuario\EditarusuarioForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Usuarios;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Lineashv;
use Application\Modelo\Entity\Articuloshv;
use Application\Modelo\Entity\Autores;
use Application\Modelo\Entity\Libroshv;
use Application\Modelo\Entity\Otrasproduccioneshv;
use Application\Modelo\Entity\Proyectosexthv;
use Application\Modelo\Entity\Experiencialabhv;
use Application\Modelo\Entity\Formacionacahv;
use Application\Modelo\Entity\Bibliograficoshv;
use Application\Modelo\Entity\Formacioncomhv;
use Application\Modelo\Entity\Idiomashv;
use Application\Modelo\Entity\Actividadeshv;
use Application\Modelo\Entity\Capitulosusuario;
use Application\Modelo\Entity\Agregarautorusuario;
use Application\Modelo\Entity\Areashv;
use Application\Modelo\Entity\Proyectosintusua;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Identificadoresusu;
use Application\Modelo\Entity\Eventosusu;
use Application\Modelo\Entity\Trabajogradousu;
use Application\Modelo\Entity\Semillero;

class EditarusuarioController extends AbstractActionController
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
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Usuarios($this->dbAdapter);
            $data = $this->request->getPost();
            $id = (int) $this->params()->fromRoute('id', 0);
            // print_r($data);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $ad = new Auditoriadet($this->dbAdapter);
            $au = new Auditoria($this->dbAdapter);

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

            /* */
                $upload = new \Zend\File\Transfer\Adapter\Http();
                $upload->setDestination('./public/images/uploads/');
                $files = $upload->getFileInfo();
                foreach($files as $f){
                    $archi=$f["name"];
                }
                
                if($archi==""){
                    $resultado = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                    $res = array();
                    $res = $u->getArrayusuariosid($id);
                    $p2 = implode(',', (array) $res);
                    $filter = new StringTrim();
                    $p2 = str_replace(' ', '', $p2);
                    $u->updateUsuario($id, $data, $identi->usuario, $archi);
                    $p = implode(',', (array) $data);
                    $evento = 'Edicion de usuario : Datos antes: ' . print_r($res, true) . ' /-/ Datos ahora: ' . print_r($data, true) . '(aps_usuarios)';
                    $evento = $filter->filter($evento);
                    $ad->addAuditoriadet($evento, $resultado);
                    $this->flashMessenger()->addMessage("Usuario editado con éxito.");
                    return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/editarusuario/index/' . $id);
                }else{
                    $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "usu_".$id."_".$archi,'overwrite' => true));
                    $upload->setValidators(array(
                        'Size'  => array('min' => 1, 'max' => 50000000),
                    ));

                    if($upload->isValid()){
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                
                        $resultado = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                        $res = array();
                        $res = $u->getArrayusuariosid($id);
                        $p2 = implode(',', (array) $res);
                        $filter = new StringTrim();
                        $p2 = str_replace(' ', '', $p2);
                        $u->updateUsuario($id, $data, $identi->usuario, $archi);
                        $p = implode(',', (array) $data);
                        $evento = 'Edicion de usuario : Datos antes: ' . print_r($res, true) . ' /-/ Datos ahora: ' . print_r($data, true) . '(aps_usuarios)';
                        $evento = $filter->filter($evento);
                        $ad->addAuditoriadet($evento, $resultado);
                        $this->flashMessenger()->addMessage("Usuario editado con éxito.");
                        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/editarusuario/index/' . $id);
                    }else{
                        $this->flashMessenger()->addMessage("Falla en la carga del archivo. Supera las 25MB de tamaño.");
                        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/editarusuario/index/' . $id);
                    }
                }
                
            /* */
        } else {
            
            $EditarusuarioForm = new EditarusuarioForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $id = (int) $this->params()->fromRoute('id', 0);
            $u = new Usuarios($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $us = new Usuarios($this->dbAdapter);
            $id = (int) $this->params()->fromRoute('id', 0);
            foreach ($us->getUsuarioeditar($id) as $dat) {
                
                $dat["primer_nombre"];
                $ar = $dat["archivo"];
            }
            $idciudad = $dat["id_ciudad"];
            $idestado = $dat["estado_civil"];
            $idunidad = $dat["id_unidad_academica"];
            $iddependencia = $dat["id_dependencia_academica"];
            $idvinculacion = $dat["id_tipo_vinculacion"];
            $idtipdoc = $dat["id_tipo_documento"];
            $idnacimiento = $dat["id_lugar_nacimiento"];
            $idgenero = $dat["id_sexo"];
            $idnacionalidad = $dat["id_nacionalidad"];
            $filter = new StringTrim();
            
            $EditarusuarioForm->add(array(
                'name' => 'primer_nombre',
                'attributes' => array(
                    'autofocus' => 'autofocus',
                    'id' => 'primer_nombre',
                    'type' => 'text',
                    'value' => $filter->filter($dat["primer_nombre"])
                ),
                'options' => array(
                    'label' => 'Primer Nombre:'
                )
            ));

            $EditarusuarioForm->add(array(
                'name' => 'cod_estudiante',
                'attributes' => array(
                    'id' => 'cod_estudiante',
                    'type' => 'text',
                    'value' => $filter->filter($dat["cod_estudiante"])
                ),
                'options' => array(
                    'label' => 'Código de estudiante (Si aplica):'
                )
            ));
            
            $EditarusuarioForm->add(array(
                'name' => 'segundo_nombre',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($dat["segundo_nombre"])
                ),
                'options' => array(
                    'label' => 'Segundo Nombre:'
                )
            )
            );
            
            $EditarusuarioForm->add(array(
                'name' => 'primer_apellido',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($dat["primer_apellido"])
                ),
                'options' => array(
                    'label' => 'Primer apellido:'
                )
            ));
            
            $EditarusuarioForm->add(array(
                'name' => 'segundo_apellido',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($dat["segundo_apellido"])
                ),
                'options' => array(
                    'label' => 'Segundo apellido:'
                )
            ));
            
            $EditarusuarioForm->add(array(
                'name' => 'documento',
                'attributes' => array(
                    'type' => 'Number',
                    'value' => $filter->filter($dat["documento"])
                ),
                'options' => array(
                    'label' => 'No. Documento:'
                )
            ));
            
            $EditarusuarioForm->add(array(
                'name' => 'direccion',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($dat["direccion"])
                ),
                'options' => array(
                    'label' => 'Dirección de Residencia:'
                )
            ));
            
            $EditarusuarioForm->add(array(
                'name' => 'direccion_trabajo',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($dat["direccion_trabajo"])
                ),
                'options' => array(
                    'label' => 'Dirección de Trabajo:'
                )
            ));
            
            $EditarusuarioForm->add(array(
                'name' => 'telefono',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($dat["telefono"])
                ),
                'options' => array(
                    'label' => 'Teléfono de Residencia:'
                )
            ));
            
            $EditarusuarioForm->add(array(
                'name' => 'telefono_trabajo',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($dat["telefono_trabajo"])
                ),
                'options' => array(
                    'label' => 'Teléfono del Trabajo:'
                )
            ));
            
            $EditarusuarioForm->add(array(
                'name' => 'celular',
                'attributes' => array(
                    'type' => 'Number',
                    'value' => $filter->filter($dat["celular"])
                ),
                'options' => array(
                    'label' => 'Celular:'
                )
            ));
            
            $EditarusuarioForm->add(array(
                'name' => 'email',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($dat["email"])
                ),
                'options' => array(
                    'label' => 'Correo electrónico institucional:'
                )
            ));
            
            $EditarusuarioForm->add(array(
                'name' => 'email2',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $filter->filter($dat["email2"])
                ),
                'options' => array(
                    'label' => 'Email (opcional):'
                )
            ));
            
            $EditarusuarioForm->add(array(
                'name' => 'f_naci',
                'attributes' => array(
                    'type' => 'text',
                    'size' => 15,
                    'disabled' => 'disabled',
                    'value' => $filter->filter($dat["fecha_nacimiento"])
                ),
                'options' => array(
                    'label' => 'Fecha Nacimiento:'
                )
            ));
            $EditarusuarioForm->add(array(
                'type' => 'Date',
                'name' => 'fecha_nacimiento',
                'options' => array(
                    'label' => 'Fecha Nacimiento'
                ),
                'attributes' => array(
                    'value' => $filter->filter($dat["fecha_nacimiento"])
                ),
            ));
            
            $EditarusuarioForm->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'id_estado',
                'required' => 'required',
                'options' => array(
                    'label' => 'Activo',
                    'value_options' => array(
                        '' => 'Seleccione',
                        'S' => 'Activo',
                        'N' => 'Inactivo',
                        'B' => 'Bloqueado',
                        'F' => 'Bloqueado Foros'
                    )
                ),
                'attributes' => array(
                    'value' => ''
                )
            ) // set selected to '1'

            );
            
            // define el campo activo
            if ($dat["id_estado"] == 's' || $dat["id_estado"] == 'S') {
                $estado = 'Activo';
            } elseif ($dat["id_estado"] == 'n' || $dat["id_estado"] == 'N') {
                $estado = 'Inactivo';
            } elseif ($dat["id_estado"] == 'b' || $dat["id_estado"] == 'B') {
                $estado = 'Bloqueado';
            } elseif ($dat["id_estado"] == 'f' || $dat["id_estado"] == 'F') {
                $estado = 'Bloqueado Foros';
            } else {
                $estado = '';
            }
            $EditarusuarioForm->add(array(
                'name' => 'estado',
                'attributes' => array(
                    'type' => 'text',
                    'value' => $estado,
                    'disabled' => 'disabled'
                ),
                'options' => array(
                    'label' => 'Estado:'
                )
            ));
            
            // * define el campo estado civil *//
            if ($dat["estado_civil"] == null) {
                $v_ec = " ";
            } else {
                $v_ec = $dat["estado_civil"];
            }
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $resvf = $vf->getValoresflexid($v_ec);
            $opciones = array();
            foreach ($vf->getArrayvalores(35) as $da) {
                $op = array(
                    $resvf["id_valor_flexible"] => $resvf["descripcion_valor"]
                );
                $op = $op + array(
                    $da["id_valor_flexible"] => $da["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            
            $EditarusuarioForm->add(array(
                'name' => 'estado_civil',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Estado Civil : ',
                    
                    'value_options' => $opciones
                )
            ));
            $opz = 0;
            $uni_v = $idunidad;
            $dep_v = $iddependencia;
            //'value' => 'A'
            $vf = new Agregarvalflex($this->dbAdapter);
             $opciones = array(
                '' => ''
            );
            
            $vf = new Agregarvalflex($this->dbAdapter);
            foreach ($vf->getArrayvalores(33) as $datFlex) {
                $op = array(
                    $datFlex["id_valor_flexible"] . '-' . $datFlex["valor_flexible_padre_id"]  => $datFlex["descripcion_valor"]
                );
            
                $opciones = $opciones  + $op;
            }

            $EditarusuarioForm->add(array(
                'name' => 'id_dependencia_academica',
                'type' => 'Zend\Form\Element\Select',
            
                'options' => array(
                    'label' => 'Dependencia académica: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'id' => 'id_dependencia_academica',
                    'onchange' => 'myFunction3();',
                    'value' => $dat["id_dependencia_academica"]."-".$dat["id_unidad_academica"],
                    'required' => 'required'
                ) // set selected to '1'
            
            ));

            $opciones = array(
                '' => ''
            );
            foreach ($vf->getArrayvalores(34) as $datFlex) {
                $op = array(
                    $datFlex["id_valor_flexible"] . '-' . $datFlex["valor_flexible_padre_id"]=> $datFlex["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            
            $EditarusuarioForm->add(array(
                'name' => 'id_programa_academico',
                'type' => 'Zend\Form\Element\Select',
                
                'options' => array(
                    'label' => 'Programa académico: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'id' => 'id_programa_academico',
                    'required' => 'required',
                    'value' => $dat["id_programa_academico"]."-".$dat["id_dependencia_academica"]
                ) // set selected to '1'

            ));

            $opciones = array(
                '' => ''
            );
            
            foreach ($vf->getArrayvalores(65) as $datFlex) {                
                $op = array(
                    $datFlex["id_valor_flexible"] => $datFlex["descripcion_valor"]
                );  
                $opciones = $opciones  + $op;
            }

            $EditarusuarioForm->get('cargo_actual')->setValueOptions($opciones);
            if($dat["cargo_actual"]!=""){
                $EditarusuarioForm->get('cargo_actual')->setAttribute("value",$dat["cargo_actual"]);
            }
            $EditarusuarioForm->get('evaluador')->setAttribute("value",$dat["evaluador"]);
            if($dat["tipo_evaluador"]!=""){
                $EditarusuarioForm->get('tipo_evaluador')->setAttribute("value",$dat["tipo_evaluador"]);
            }
            if($dat["evaluador"]=="No"){
                $EditarusuarioForm->get('tipo_evaluador')->setAttribute("disabled","disabled");
            }

            $opciones = array(
                '' => ''
            );
            
            foreach ($vf->getArrayvalores(23) as $datFlex) {                
                $op = array(
                    $datFlex["id_valor_flexible"] => $datFlex["descripcion_valor"]
                );  
                $opciones = $opciones  + $op;
            }

            $EditarusuarioForm->add(array(
                'name' => 'id_unidad_academica',
                'type' => 'Zend\Form\Element\Select',
                
                'options' => array(
                    'label' => 'Unidad académica: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'id' => 'id_unidad_academica',
                    'onchange' => 'myFunction2();',
                    'value' => $dat["id_unidad_academica"], 
                    'required' => 'required'
                ) // set selected to '1'

            ));

            $opciones = array('' => '');
            foreach ($vf->getArrayvalores(38) as $datFlex) {                
                $op = array(
                    $datFlex["id_valor_flexible"] => $datFlex["descripcion_valor"]
                );  
                $opciones = $opciones  + $op;
            }

            $EditarusuarioForm->get('institucion')->setValueOptions($opciones);
            if($dat["institucion"]!=""){
                $EditarusuarioForm->get('institucion')->setAttribute("value",$dat["institucion"]);
            }
            
            // * define el campo Tipo Documento *//
            if ($idtipdoc == null) {
                $v_td = " ";
            } else {
                $v_td = $idtipdoc;
            }
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $resvf = $vf->getValoresflexid($v_td);
            $opciones = array();
            foreach ($vf->getArrayvalores(25) as $da) {
                $op = array(
                    $resvf["id_valor_flexible"] => $resvf["descripcion_valor"]
                );
                $op = $op + array(
                    $da["id_valor_flexible"] => $da["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $EditarusuarioForm->add(array(
                'name' => 'id_tipo_documento',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Tipo documento: ',
                    
                    'value_options' => $opciones
                )
            ));
            // *define el campo tipo vinculacion*//
            if ($idvinculacion == null) {
                $v_tv = " ";
            } else {
                $v_tv = $idvinculacion;
            }
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $resvf = $vf->getValoresflexid($v_tv);
            $opciones = array();
            foreach ($vf->getArrayvalores(36) as $da) {
                $op = array(
                    $resvf["id_valor_flexible"] => $resvf["descripcion_valor"]
                );
                $op = $op + array(
                    $da["id_valor_flexible"] => $da["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $EditarusuarioForm->add(array(
                'name' => 'id_tipo_vinculacion',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => ' Tipo Vinculación : ',
                    
                    'value_options' => $opciones
                )
            ));
            
            // *define el campo ciudad*//
            if ($idciudad == null) {
                $v_ciu = " ";
            } else {
                $v_ciu = $idciudad;
            }
            $vf = new Agregarvalflex($this->dbAdapter);
            $resvf = $vf->getValoresflexid($v_ciu);
            $opciones = array();
            foreach ($vf->getArrayvalores(1) as $da) {
                $op = array(
                    $resvf["id_valor_flexible"] => $resvf["descripcion_valor"]
                );
                $op = $op + array(
                    $da["id_valor_flexible"] => $da["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $EditarusuarioForm->add(array(
                'name' => 'id_ciudad',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Ciudad de residencia: ',
                    'value_options' => $opciones
                )
            ));
            
            // define el campo lugar nacimiento
            if ($idnacimiento == null) {
                $v_nac = " ";
            } else {
                $v_nac = $idnacimiento;
            }
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $resvf = $vf->getValoresflexid($v_nac);
            $opciones = array();
            foreach ($vf->getArrayvalores(1) as $da) {
                $op = array(
                    $resvf["id_valor_flexible"] => $resvf["descripcion_valor"]
                );
                $op = $op + array(
                    $da["id_valor_flexible"] => $da["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $EditarusuarioForm->add(array(
                'name' => 'id_lugar_nacimiento',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Lugar Nacimiento: ',
                    
                    'value_options' => $opciones
                )
            ));
            
            // * define el campo genero *//
            if ($idgenero == null) {
                $v_gen = " ";
            } else {
                $v_gen = $idgenero;
            }
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $resvf = $vf->getValoresflexid($v_gen);
            $opciones = array();
            foreach ($vf->getArrayvalores(2) as $da) {
                $op = array(
                    $resvf["id_valor_flexible"] => $resvf["descripcion_valor"]
                );
                $op = $op + array(
                    $da["id_valor_flexible"] => $da["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $EditarusuarioForm->add(array(
                'name' => 'id_sexo',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Género : ',
                    
                    'value_options' => $opciones
                )
            ));
            
            // * define el campo nacionalidad *//
            if ($idnacionalidad == null) {
                $v_nacionalidad = " ";
            } else {
                $v_nacionalidad = $idnacionalidad;
            }
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $resvf = $vf->getValoresflexid($v_nacionalidad);
            $opciones = array();
            foreach ($vf->getArrayvalores(3) as $da) {
                $op = array(
                    $resvf["id_valor_flexible"] => $resvf["descripcion_valor"]
                );
                $op = $op + array(
                    $da["id_valor_flexible"] => $da["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $EditarusuarioForm->add(array(
                'name' => 'id_nacionalidad',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Nacionalidad : ',
                    
                    'value_options' => $opciones
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
                $pantalla = "editarusuario";
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
                $li = new Lineashv($this->dbAdapter);
                $art = new Articuloshv($this->dbAdapter);
                $lib = new Libroshv($this->dbAdapter);
                $proyext = new Proyectosexthv($this->dbAdapter);
                $otras = new Otrasproduccioneshv($this->dbAdapter);
                $explab = new Experiencialabhv($this->dbAdapter);
                $formcom = new Formacioncomhv($this->dbAdapter);
                $formaca = new Formacionacahv($this->dbAdapter);
                $actividad = new Actividadeshv($this->dbAdapter);
                $idioma = new Idiomashv($this->dbAdapter);
                $usuario = new Usuarios($this->dbAdapter);
                $area = new Areashv($this->dbAdapter);
                $auto = new Autores($this->dbAdapter);
                $biblio = new Bibliograficoshv($this->dbAdapter);
                $vflex = new Agregarvalflex($this->dbAdapter);
                $autoresgrupo = new Agregarautorusuario($this->dbAdapter);
                $capitulosgrupo = new Capitulosusuario($this->dbAdapter);
                $proyectos = new Proyectos($this->dbAdapter);
                $tablaequipo = new Tablaequipop($this->dbAdapter);
                $proyectosint = new Proyectosintusua($this->dbAdapter);     
                $identificadores = new Identificadoresusu($this->dbAdapter); 
                $eventos = new Eventosusu($this->dbAdapter); 
                $formacion = new Trabajogradousu($this->dbAdapter);
                $semillero = new Semillero($this->dbAdapter); 

                $view = new ViewModel(array(
                    'form' => $EditarusuarioForm,
                    'titulo' => "Editar Datos Usuarios",
                    'url' => $this->getRequest()->getBaseUrl(),
                    'datos' => $u->getUsuariosid($id),
                    'ar' => $ar,
                    'ver' => $id2 = $this->params()->fromRoute('id2'),
                    'vflex' => $vflex->getValoresf(),
                    'lineas' => $li->getLineashv($id),
                    'articulos' => $art->getArticuloshv($id),
                    'libros' => $lib->getLibroshv($id),
                    'proyext' => $proyext->getProyectosexthv($id),
                    'otras' => $otras->getOtrasproduccioneshv($id),
                    'explab' => $explab->getExperiencialabhv($id),
                    'biblio' => $biblio->getBibliograficos($id),
                    'formaca' => $formaca->getFormacionacahv($id),
                    'formcom' => $formcom->getFormacioncomhv($id),
                    'auto' => $auto->getAutores($id),
                    'idioma' => $idioma->getIdiomashv($id),
                    'actividad' => $actividad->getActividadeshv($id),
                    'usuarios' => $usuario->getArrayusuarios(),
                    'area' => $area->getAreashv($id),
                    'id' => $id,
                    'menu' => $dat["id_rol"],
                    'proyectosint' =>  $proyectosint->getProyectos($id),
                    'proyectos' =>  $proyectos->getProyectoh(),
                    'tablaequipo' =>  $tablaequipo->getTablaequipot(),
                    'autoresgrupo' => $autoresgrupo->getAgregarautorusuario($id),
                    'capitulos' => $capitulosgrupo->getCapitulousuario($id),
                    'identificadores' => $identificadores->getIdentificadoresusu($id),
                    'eventos' => $eventos->getEventosusu($id),
                    'formacion' => $formacion->getTrabajogradousu($id),
                    'semilleros' => $semillero->getSemilleroinv()
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/mensajeadministrador/index');
            }
            ;
        }
    }
}
