<?php

namespace Application\Controller;
// librerias
use Zend\Authentication\AuthenticationService;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;

// Forma
use Application\Convocatoria\EditarconvocatoriaiForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

// Entidad principal
use Application\Modelo\Entity\Convocatoria;

// Entidades
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Cronograma;
use Application\Modelo\Entity\Requisitos;
use Application\Modelo\Entity\Aspectoeval;
use Application\Modelo\Entity\Avalcumpliconvo;
use Application\Modelo\Entity\Requisitosdoc;
use Application\Modelo\Entity\Rolesconv;
use Application\Modelo\Entity\Archivosconv;
use Application\Modelo\Entity\Url;
use Application\Modelo\Entity\Tablafin;
use Application\Modelo\Entity\Camposadd;
use Application\Modelo\Entity\Proyectosinv;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Criterioevaluacion;

class EditarconvocatoriaiController extends AbstractActionController
{
    // Variables
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
        
        $not = new Convocatoria($this->dbAdapter);

        if ($this->getRequest()->isPost()) {
            
            // Creacion adaptador de conexion, objeto de datos, auditoria
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            
            $auth = $this->auth;
            
            // verifica si esta conectado al sistema
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            // verificacion del rol del usuario conectado
            $rol = new Roles($this->dbAdapter);
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
            }
            
            // obtiene la informacion de las pantallas
            $data = $this->request->getPost();
            
            // Creacion del objeto forma
            $EditarconvocatoriaiForm = new EditarconvocatoriaiForm();
            
            // Parametros de la pantalla
            $id = (int) $this->params()->fromRoute('id', 0);
            $id2 = $this->params()->fromRoute('id2');
            
            // Actualizacion de los registros por medio de un objeto de la entidad
            $resultado = $not->UpdateConvocatoria($id, $data);
            
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
                $evento = 'Edición de convocatoria interna : ' . $resultado . '(mgc_convocatoria)';
                $ad->addAuditoriadet($evento, $resul);
                
                $this->flashMessenger()->addMessage("Convocatoria editada con exito");
                if ($id2 == "fechas") {
                    return $this->redirect()->toUrl($this->getRequest()
                        ->getBaseUrl() . '/application/editarconvocatoriai/index/' . $id . '/' . $id2);
                } elseif ($id2 == "ver") {
                    return $this->redirect()->toUrl($this->getRequest()
                        ->getBaseUrl() . '/application/editarconvocatoriai/index/' . $id . '/' . $id2);
                } else {
                    return $this->redirect()->toUrl($this->getRequest()
                        ->getBaseUrl() . '/application/editarconvocatoriai/index/' . $id);
                }
            } else {
                // Si falla muestra el siguiente error
                $this->flashMessenger()->addMessage("La creacion de la convocatoria fallo");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/convocatoriai/index');
            }
        } else {            
            // Creacion de la forma
            $EditarconvocatoriaiForm = new EditarconvocatoriaiForm();
            
            // Creacion adaptador de conexion, objeto de datos, auditoria
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $auth = $this->auth;
            
            // objetos para acceder a la entidad
            $u = new Convocatoria($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            
            // verificacion de conexion del usuario
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            // objeto para quitar los blanco en cadenas
            $filter = new StringTrim();
            
            // creacion de campos
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
            
            $id2 = $this->params()->fromRoute('id2');

            if ($id2 == 'ver') {                
                $id = (int) $this->params()->fromRoute('id', 0);
                
                if ($id != 0) {
                    foreach ($u->getConvocatoriaid($id, 'i') as $dat) {
                        $id_conv = $dat["id_convocatoria"];
                    }
                } else {
                    foreach ($u->getConvocatoria($id, 'i') as $dat) {
                        $id_conv = $dat["id_convocatoria"];
                    }
                }
                $tipo = $dat["tipo_conv"];
                $entidad = $dat["id_entidad"];
                
                $vf = new Agregarvalflex($this->dbAdapter);
                $resvf = $vf->getValoresflexid($entidad);
                
                $opciones = array();
                foreach ($vf->getArrayvalores(23) as $xx) {
                    $op = array(
                        $resvf["id_valor_flexible"] => $resvf["descripcion_valor"]
                    );
                    $op = $op + array(
                        $xx["id_valor_flexible"] => $xx["descripcion_valor"]
                    );
                    $opciones = $opciones + $op;
                }
                
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'id_entidad',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'id_entidad'
                    ),
                    'options' => array(
                        'label' => 'Entidad : ',
                        'value_options' => $opciones
                    )
                ));
                
                $cronograma = "";
                if(trim($dat["cronograma"]) == "S"){
                    $cronograma = "Si";
                    $cronograman = "S";
                }elseif(trim($dat["cronograma"]) == "N"){
                    $cronograma = "No";
                    $cronograman = "N";
                }
                
                $EditarconvocatoriaiForm->add(array(
                    'type' => 'Zend\Form\Element\Select',
                    'name' => 'cronograma',
                    'required' => 'required',
                    'options' => array(
                        'label' => 'Cronograma:',
                        'value_options' => array(
                            $dat["cronograma"] => $cronograma,
                            'S' => 'Si',
                            'N' => 'No'
                        )
                    ),
                    'attributes' => array(
                        'value' => '1'
                    )
                ) // set selected to '1'

                );
                
                $estado = '';
                if (trim($dat["id_estado"]) == "A") {
                    $estado = "En Construccion";
                    $estadon = "A";
                } elseif (trim($dat["id_estado"]) == "B") {
                    $estado = "Abierta";
                    $estadon = "B";
                } elseif (trim($dat["id_estado"]) == "P") {
                    $estado = "Con Aplicaciones";
                    $estadon = "P";
                } elseif (trim($dat["id_estado"]) == "R") {
                    $estado = "Cerrada";
                    $estadon = "R";
                } elseif (trim($dat["id_estado"]) == "H") {
                    $estado = "Archivada";
                    $estadon = "H";
                } elseif (trim($dat["id_estado"]) == "N") {
                    $estado = "Anulada";
                    $estadon = "N";
                }  
                
                $EditarconvocatoriaiForm->add(array(
                    'type' => 'Zend\Form\Element\Select',
                    'name' => 'id_estado',
                    'required' => 'required',
                    'options' => array(
                        'label' => 'Estado de la Convocatoria:',
                        'value_options' => array(
                            $dat["id_estado"] => $estado,
                            'B' => 'Abierta',
                            'P' => 'Con Aplicaciones',
                            'R' => 'Cerrada',
                            'H' => 'Archivada',
                            'N' => 'Anulada'
                            
                        )
                    ),
                    'attributes' => array(
                        'value' => '1'
                    )
                ) // set selected to '1'

                );
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'id_convocatoria',
                    'attributes' => array(
                        'type' => 'text',
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["id_convocatoria"])
                    ),
                    'options' => array(
                        'label' => 'ID :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'titulo',
                    'attributes' => array(
                        'type' => 'text',
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["titulo"])
                    ),
                    'options' => array(
                        'label' => 'Título :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'descripcion',
                    'attributes' => array(
                        'type' => 'textarea',
                        'placeholder' => 'Ingrese la descripcion',
                        'maxlenght' => 5000,
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["descripcion"])
                    ),
                    'options' => array(
                        'label' => 'Descripción :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'observaciones',
                    'attributes' => array(
                        'type' => 'textarea',
                        'placeholder' => 'Ingrese las observaciones',
                        'maxlenght' => 5000,
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["observaciones"])
                    ),
                    'options' => array(
                        'label' => 'Observaciones :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'fecha_apertura1',
                    'attributes' => array(
                        'placeholder' => 'DD-MM-YYYY',
                        'type' => 'text',
                        'size' => 15,
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["fecha_apertura"])
                    ),
                    'options' => array(
                        'label' => 'Fecha Apertura :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'type' => 'Date',
                    'disabled' => 'disabled',
                    'name' => 'fecha_apertura',
                    'options' => array(
                        'label' => 'Fecha Apertura'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'fecha_cierre1',
                    'attributes' => array(
                        'placeholder' => 'DD-MM-YYYY',
                        'type' => 'text',
                        'size' => 15,
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["fecha_cierre"])
                    ),
                    'options' => array(
                        'label' => 'Fecha Fin :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'type' => 'Date',
                    'disabled' => 'disabled',
                    'name' => 'fecha_cierre',
                    'options' => array(
                        'label' => 'Fecha Fin'
                    )
                ));
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'hora_cierre',
                    'attributes' => array(
                        'type' => 'time',
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["hora_cierre"])
                    ),
                    'options' => array(
                        'label' => 'Hora Fin:'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'hora_apertura',
                    'attributes' => array(
                        'type' => 'time',
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["hora_apertura"])
                    ),
                    'options' => array(
                        'label' => 'Hora Apertura :'
                    )
                ));
            } elseif ($id2 == 'fechas') {                
                $id = (int) $this->params()->fromRoute('id', 0);
                
                if ($id != 0) {
                    foreach ($u->getConvocatoriaid($id, 'i') as $dat) {
                        $id_conv = $dat["id_convocatoria"];
                    }
                } else {
                    foreach ($u->getConvocatoria($id, 'i') as $dat) {
                        $id_conv = $dat["id_convocatoria"];
                    }
                }
                
                $entidad = $dat["id_entidad"];
                
                $vf = new Agregarvalflex($this->dbAdapter);
                $resvf = $vf->getValoresflexid($entidad);
                
                $opciones = array();
                foreach ($vf->getArrayvalores(23) as $xx) {
                    $op = array(
                        $resvf["id_valor_flexible"] => $resvf["descripcion_valor"]
                    );
                    $op = $op + array(
                        $xx["id_valor_flexible"] => $xx["descripcion_valor"]
                    );
                    $opciones = $opciones + $op;
                }
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'id_entidad',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'id_entidad'
                    ),
                    'options' => array(
                        'label' => 'Entidad : ',
                        'value_options' => $opciones
                    )
                ));
                
                $cronograma = '';
                if(trim($dat["cronograma"]) == "S"){
                    $cronograma = "Si";
                    $cronograman = "S";
                }elseif(trim($dat["cronograma"]) == "N"){
                    $cronograma = "No";
                    $cronograman = "N";
                }
                
                $EditarconvocatoriaiForm->add(array(
                    'type' => 'Zend\Form\Element\Select',
                    'name' => 'cronograma',
                    'required' => 'required',
                    'options' => array(
                        'label' => 'Cronograma:',
                        'value_options' => array(
                            $dat["cronograma"] => $cronograma,
                            'S' => 'Si',
                            'N' => 'No'
                        )
                    ),
                    'attributes' => array(
                        'value' => '1'
                    )
                ) // set selected to '1'

                );
                
                $estado = '';
                if (trim($dat["id_estado"]) == "A") {
                    $estado = "En Construccion";
                    $estadon = "A";
                } elseif (trim($dat["id_estado"]) == "B") {
                    $estado = "Abierta";
                    $estadon = "B";
                } elseif (trim($dat["id_estado"]) == "P") {
                    $estado = "Con Aplicaciones";
                    $estadon = "P";
                } elseif (trim($dat["id_estado"]) == "R") {
                    $estado = "Cerrada";
                    $estadon = "R";
                } elseif (trim($dat["id_estado"]) == "H") {
                    $estado = "Archivada";
                    $estadon = "H";
                } elseif (trim($dat["id_estado"]) == "N") {
                    $estado = "Anulada";
                    $estadon = "N";
                } 

                $EditarconvocatoriaiForm->add(array(
                    'type' => 'Zend\Form\Element\Select',
                    'name' => 'id_estado',
                    'required' => 'required',
                    'options' => array(
                        'label' => 'Estado de la Convocatoria:',
                        'value_options' => array(
                            $dat["id_estado"] => $estado,
                            'B' => 'Abierta',
                            'P' => 'Con Aplicaciones',
                            'R' => 'Cerrada',
                            'H' => 'Archivada',
                            'N' => 'Anulada'
                        )
                    ),
                    'attributes' => array(
                        'value' => '1'
                    )
                ) // set selected to '1'

                );
                
                $tipo = $dat["tipo_conv"];
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'id_convocatoria',
                    'attributes' => array(
                        'type' => 'text',
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["id_convocatoria"])
                    ),
                    'options' => array(
                        'label' => 'ID :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'titulo',
                    'attributes' => array(
                        'type' => 'text',
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["titulo"])
                    ),
                    'options' => array(
                        'label' => 'Título :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'descripcion',
                    'attributes' => array(
                        'type' => 'textarea',
                        'placeholder' => 'Ingrese la descripcion',
                        'lenght' => 500,
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["descripcion"])
                    ),
                    'options' => array(
                        'label' => 'Descripción :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'observaciones',
                    'attributes' => array(
                        'type' => 'textarea',
                        'placeholder' => 'Ingrese las observaciones',
                        'lenght' => 500,
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["observaciones"])
                    ),
                    'options' => array(
                        'label' => 'Observaciones :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'fecha_apertura1',
                    'attributes' => array(
                        'placeholder' => 'DD-MM-YYYY',
                        'type' => 'text',
                        'size' => 15,
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["fecha_apertura"])
                    ),
                    'options' => array(
                        'label' => 'Fecha Apertura :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'type' => 'date',
                    'placeholder' => 'DD-MM-YYYY',
                    'name' => 'fecha_apertura',
                    'options' => array(
                        'label' => 'Fecha Apertura'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'fecha_cierre1',
                    'attributes' => array(
                        'type' => 'text',
                        'placeholder' => 'DD-MM-YYYY',
                        'size' => 15,
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["fecha_cierre"])
                    ),
                    'options' => array(
                        'label' => 'Fecha Fin :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'type' => 'Date',
                    'placeholder' => 'DD-MM-YYYY',
                    'name' => 'fecha_cierre',
                    'options' => array(
                        'label' => 'Fecha Fin'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'hora_cierre',
                    'attributes' => array(
                        'type' => 'time',
                        'value' => $filter->filter($dat["hora_cierre"])
                    ),
                    'options' => array(
                        'label' => 'Hora Fin :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'hora_apertura',
                    'attributes' => array(
                        'type' => 'time',
                        
                        'value' => $filter->filter($dat["hora_apertura"])
                    ),
                    'options' => array(
                        'label' => 'Hora Apertura :'
                    )
                ));
            } else {                
                $id = (int) $this->params()->fromRoute('id', 0);
                
                if ($id != 0) {
                    foreach ($u->getConvocatoriaid($id, 'i') as $dat) {
                        $id_conv = $dat["id_convocatoria"];
                    }
                } else {
                    foreach ($u->getConvocatoria($id, 'i') as $dat) {
                        $id_conv = $dat["id_convocatoria"];
                    }
                }
                $tipo = $dat["tipo_conv"];
                $entidad = $dat["id_entidad"];
                
                $vf = new Agregarvalflex($this->dbAdapter);
                $resvf = $vf->getValoresflexid($entidad);
                
                $opciones = array();
                foreach ($vf->getArrayvalores(23) as $xx) {
                    $op = array(
                        $resvf["id_valor_flexible"] => $resvf["descripcion_valor"]
                    );
                    $op = $op + array(
                        $xx["id_valor_flexible"] => $xx["descripcion_valor"]
                    );
                    $opciones = $opciones + $op;
                }
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'id_entidad',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'id_entidad'
                    ),
                    'options' => array(
                        'label' => 'Entidad : ',
                        'value_options' => $opciones
                    )
                ));
                
                //--------------------------------------------------------------------//
                // CRONOGRAMA
                
                
                $cronograma = '';
                if(trim($dat["cronograma"]) == "S"){
                    $cronograma = "Si";
                    $cronograman = "S";
                }elseif(trim($dat["cronograma"]) == "N"){
                    $cronograma = "No";
                    $cronograman = "N";
                }
                
                $EditarconvocatoriaiForm->add(array(
                    'type' => 'Zend\Form\Element\Select',
                    'name' => 'cronograma',
                    'required' => 'required',
                    'options' => array(
                        'label' => 'Cronograma:',
                        'value_options' => array(
                            $dat["cronograma"] => $cronograma,
                            'S' => 'Si',
                            'N' => 'No'
                        )
                    ),
                    'attributes' => array(
                        'value' => '1'
                    )
                ) // set selected to '1'

                );
                
                
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'id_convocatoria',
                    'attributes' => array(
                        'type' => 'text',
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["id_convocatoria"])
                    ),
                    'options' => array(
                        'label' => 'ID :'
                    )
                ));
                
                //--------------------------------------------------------------------//
                // ESTADO
                
                $estado = '';
                if (trim($dat["id_estado"]) == "A") {
                    $estado = "En Construccion";
                    $estadon = "A";
                } elseif (trim($dat["id_estado"]) == "R") {
                    $estado = "Cerrada";
                    $estadon = "R";
                } elseif (trim($dat["id_estado"]) == "H") {
                    $estado = "Archivada";
                    $estadon = "H";
                } elseif (trim($dat["id_estado"]) == "N") {
                    $estado = "Anulada";
                    $estadon = "N";
                } elseif (trim($dat["id_estado"]) == "P") {
                    $estado = "Con Aplicaciones";
                    $estadon = "P";
                } elseif (trim($dat["id_estado"]) == "B") {
                    $estado = "Abierta";
                    $estadon = "B";
                }
                $EditarconvocatoriaiForm->add(array(
                    'type' => 'Zend\Form\Element\Select',
                    'name' => 'id_estado',
                    'required' => 'required',
                    'options' => array(
                        'label' => 'Estado de la Convocatoria:',
                        'value_options' => array(
                            $dat["id_estado"] => $estado,
                            'B' => 'Abierta',
                            'P' => 'Con Aplicaciones',
                            'R' => 'Cerrada',
                            'H' => 'Archivada',
                            'N' => 'Anulada'
                        )
                    ),
                    'attributes' => array(
                        'value' => '1'
                    )
                ) // set selected to '1'

                );
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'titulo',
                    'attributes' => array(
                        'type' => 'text',
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["titulo"])
                    ),
                    'options' => array(
                        'label' => 'Título :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'descripcion',
                    'attributes' => array(
                        'type' => 'textarea',
                        'placeholder' => 'Ingrese la descripcion',
                        'lenght' => 500,
                        'value' => $filter->filter($dat["descripcion"])
                    ),
                    'options' => array(
                        'label' => 'Descripción :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'observaciones',
                    'attributes' => array(
                        'type' => 'textarea',
                        'placeholder' => 'Ingrese las observaciones',
                        'lenght' => 500,
                        'value' => $filter->filter($dat["observaciones"])
                    ),
                    'options' => array(
                        'label' => 'Observaciones :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'fecha_apertura1',
                    'attributes' => array(
                        'type' => 'text',
                        'placeholder' => 'DD-MM-YYYY',
                        'size' => 15,
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["fecha_apertura"])
                    ),
                    'options' => array(
                        'label' => 'Fecha Apertura :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'type' => 'Date',
                    'placeholder' => 'DD-MM-YYYY',
                    'name' => 'fecha_apertura',
                    'options' => array(
                        'label' => 'Fecha Apertura'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'fecha_cierre1',
                    'attributes' => array(
                        'type' => 'text',
                        'placeholder' => 'DD-MM-YYYY',
                        'size' => 15,
                        'disabled' => 'disabled',
                        'value' => $filter->filter($dat["fecha_cierre"])
                    ),
                    'options' => array(
                        'label' => 'Fecha Fin :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'type' => 'Date',
                    'name' => 'fecha_cierre',
                    'placeholder' => 'DD-MM-YYYY',
                    'options' => array(
                        'label' => 'Fecha Fin'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'hora_cierre',
                    'attributes' => array(
                        'type' => 'time',
                        'value' => $filter->filter($dat["hora_cierre"])
                    ),
                    'options' => array(
                        'label' => 'Hora Fin :'
                    )
                ));
                
                $EditarconvocatoriaiForm->add(array(
                    'name' => 'hora_apertura',
                    'attributes' => array(
                        'type' => 'time',
                        'value' => $filter->filter($dat["hora_apertura"])
                    ),
                    'options' => array(
                        'label' => 'Hora Apertura :'
                    )
                ));
            }
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
            
              
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if ($id == 0) {
            $id_conv = $id;
        } else {
            $id_conv = $id;
        }

        $Cronograma = new Cronograma($this->dbAdapter);
        $Requisitos = new Requisitos($this->dbAdapter);
        $Aspectoeval = new Aspectoeval($this->dbAdapter);
        $Requisitosdoc = new Requisitosdoc($this->dbAdapter);
        $Rolesconv = new Rolesconv($this->dbAdapter);
        $Archivosconv = new Archivosconv($this->dbAdapter);
        $Url = new Url($this->dbAdapter);
        $Tablafin = new Tablafin($this->dbAdapter);
        $valflex = new Agregarvalflex($this->dbAdapter);
        $campos = new Camposadd($this->dbAdapter);
        $rolesuser = new Roles($this->dbAdapter);
        $Proy = new Proyectosinv($this->dbAdapter);
        $prT = new Proyectos ( $this->dbAdapter );
        $usu = new Usuarios($this->dbAdapter);
        $criterioeval = new Criterioevaluacion($this->dbAdapter);
        $Avalcumpliconvo = new Avalcumpliconvo($this->dbAdapter);
        $id2 = $this->params()->fromRoute('id2',"n");
        $view = new ViewModel(array(
            'form' => $EditarconvocatoriaiForm,
            'titulo' => "Editar convocatoria ",
            'estado' => $estadon,
            'ver' => $id2,
            'datos' => $u->getConvocatoriaid($id_conv),
            'url' => $this->getRequest()->getBaseUrl(),
            'Cronograma' => $Cronograma->getCronogramah($id_conv),
            'tipo' => $tipo,
            'Requisitos' => $Requisitos->getRequisitos($id_conv),
            'Aspectoeval' => $Aspectoeval->getAspectoeval($id_conv),
            'Requisitosdoc' => $Requisitosdoc->getRequisitosdoc($id_conv),
            'Rolesconv' => $Rolesconv->getRolesconv($id_conv),
            'Archivosconv' => $Archivosconv->getArchivosconv($id_conv),
            'Tablafin' => $Tablafin->getTablafin($id_conv),
            'Tablafinm' => $Tablafin->getTablafinorder($id_conv),
            'proy' => $Proy->getProyectosinv($id_conv),
            'campos' => $campos->getCamposadd($id_conv),
            'Urls' => $Url->getUrl($id_conv),
            'valflex' => $valflex->getValoresf(),
            'rolesuser' => $rolesuser->getRoles(),
            'id' => $id_conv,
            'menu' => $dat["id_rol"],
            'prT' =>  $prT->getProyectoh(),
            'usu' => $usu->getArrayusuarios(),
            'informesm' =>$Avalcumpliconvo->getInformeByIdConvo($id),
            'criterioeval' => $criterioeval->getCriterioevaluacionByConvocatoria($id_conv)
        ));
            return $view;
        }
    }
}