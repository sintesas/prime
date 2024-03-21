<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarsemilleroinv\IntegrantessemilleroForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Integrantesemillero;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class IntegrantessemilleroController extends AbstractActionController
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
            $u = new Integrantesemillero($this->dbAdapter);
            $data = $this->request->getPost();
            $id = (int) $this->params()->fromRoute('id', 0);
            $id2 = (int) $this->params()->fromRoute('id2', 0);

            $upload = new \Zend\File\Transfer\Adapter\Http();
            $file_name = "";
            $error_file = false;
            if($upload->isUploaded()){
                //Archivo cargado
                $upload->setValidators(array(
                    'Size'  => array('min' => 1, 'max' => 50000000),
                ));
                if($upload->isValid()){
                    $files = $upload->getFileInfo();
                    foreach($files as $f){
                        $file_name=$f["name"];
                    }
                    if($id2!=0){
                        $registro = $u->getIntegrantesemillerobyid($id2);
                        if($registro != null){
                            unlink("public/images/uploads/si/integrantes/si_i__".$id."_".$registro[0]["archivo"]);
                        }
                    }
                    $upload->setDestination('./public/images/uploads/si/integrantes/');
                    $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "si_i__".$id."_".$file_name,'overwrite' => true));
                    $rtn = array('success' => null);
                    $rtn['success'] = $upload->receive();
                }else{
                    $error_file = true;
                }
            }

            if($id2 == "0"){
                $resul = $u->addIntegrantesemillero($data, $id, $file_name);
            }else{
                $resul = $u->updateIntegrantesemillero($id2, $data, $file_name);
            }
            
            if ($resul == 1) {
                if($error_file){
                    $this->flashMessenger()->addMessage("Integrante creado sin archivo adjunto. Recuerde que el tamaño máximo del archivo es de 25MB.");
                }else{
                    if($id2 == "0"){
                        $this->flashMessenger()->addMessage("Integrate agregado correctamente.");
                    }
                    else{
                        $this->flashMessenger()->addMessage("Integrate actualizado correctamente.");
                    }
                }
            } else {
                $this->flashMessenger()->addMessage("El integrante no se pudo agregar.");
            }
            return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/editarsemilleroinv/index/' . $id);
        } else {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Rolesusuario($this->dbAdapter);
            $IntegrantessemilleroForm = new IntegrantessemilleroForm();
            $auth = $this->auth;
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            if($id2 != "0"){
                $u = new Integrantesemillero($this->dbAdapter);
                $inte = $u->getIntegrantesemillerobyid($id2);
                //print_r($inte[0]);
            }
            $us = new Usuarios($this->dbAdapter);
            $a='';
            $opciones=array();
            foreach ($us->getUsuarios($a) as $dat) {
                $op=array($dat["id_usuario"]=>$dat["primer_nombre"].' '.$dat["segundo_nombre"].' '.$dat["primer_apellido"].' '.$dat["segundo_apellido"]);
                $opciones=$opciones+$op;
            }
            if($id2 == "0"){
                $IntegrantessemilleroForm->add(array(
                    'name' => 'integrante',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'integrante',
                        'required'=>'required'
                    ),
                    'options' => array(
                        'label' => 'Integrante:',
                        'empty_option' => 'Seleccione el integrante',
                        'value_options' => $opciones
                    ),
                ));

                $IntegrantessemilleroForm->add(array(
                    'name' => 'id_integrante',
                    'attributes' => array(
                        'type'  => 'text',
                        'id' => 'id_integrante'
                    ),
                    'options' => array(
                        'label' => 'Integrante:'
                    ),
                ));
            }else{
                $IntegrantessemilleroForm->add(array(
                    'name' => 'integrante',
                    'type' => 'Zend\Form\Element\Select',
                    'attributes' => array(
                        'id' => 'integrante',
                        'value' => $inte[0]["integrante"],
                        'disabled' => true
                    ),
                    'options' => array(
                        'label' => 'Integrante:',
                        'empty_option' => 'Seleccione el integrante',
                        'value_options' => $opciones,
                    ),
                ));
                $IntegrantessemilleroForm->add(array(
                    'name' => 'id_integrante',
                    'attributes' => array(
                        'type'  => 'text',
                        'disabled' => true
                    ),
                    'options' => array(
                        'label' => 'Integrante:'
                    ),
                ));
            }
            

            $vf = new Agregarvalflex($this->dbAdapter);
            
            $opciones = array(
                '' => ''
            );
            
            foreach ($vf->getArrayvalores(4) as $dat) {
                $op = array(
                    $dat["id_valor_flexible"]  => $dat["descripcion_valor"]
                );
                $opciones = $opciones  + $op;
            }

             $IntegrantessemilleroForm->add(array(
                    'name' => 'estado',
                    'type' => 'Zend\Form\Element\Select',
                    'options' => array(
                        'label' => 'Estado:',
                        'value_options' => $opciones,
                        'empty_option' => 'Seleccione el estado',
                    ),
                    'attributes' => array(
                        'id' => 'estado'
                    )
                ));


            $opciones = array(
                '' => ''
            );
            
            foreach ($vf->getArrayvalores(55) as $dat) {
                $op = array(
                    $dat["id_valor_flexible"]  => $dat["descripcion_valor"]
                );
                $opciones = $opciones  + $op;
            }

            $IntegrantessemilleroForm->add(array(
                'name' => 'rol_participacion',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Rol participación:',
                    'value_options' => $opciones,
                    'empty_option' => 'Seleccione el rol',
                ),
                'attributes' => array(
                    'id' => 'rol_participacion'
                ) // set selected to '1'
            ));

            if($id2 != "0"){
                $IntegrantessemilleroForm->get('rol_participacion')->setValue($inte[0]["rol_participacion"]);
                $IntegrantessemilleroForm->get('estado')->setValue($inte[0]["estado"]);
            }
            
            if($id2 == "0"){
                $IntegrantessemilleroForm->add(array(
                    'name' => 'fecha_inicio',
                    'attributes' => array(
                        'type'  => 'date',
                        'max' => date("Y-m-d"),
                        'required'=>'required'
                    ),
                    'options' => array(
                        'label' => 'Fecha inicio de vinculación:'
                    ),
                ));

                $IntegrantessemilleroForm->add(array(
                    'name' => 'fecha_fin',
                    'attributes' => array(
                        'type'  => 'date',
                        'max' => date("Y-m-d")
                    ),
                    'options' => array(
                        'label' => 'Fecha fin de vinculación:'
                    ),
                ));
            }
            else{
                 $IntegrantessemilleroForm->add(array(
                    'name' => 'fecha_inicio',
                    'attributes' => array(
                        'type'  => 'date',
                        'max' => date("Y-m-d"),
                        'value' => $inte[0]["fecha_inicio"],
                    ),
                    'options' => array(
                        'label' => 'Fecha inicio de vinculación:'
                    ),
                ));

                $IntegrantessemilleroForm->add(array(
                    'name' => 'fecha_fin',
                    'attributes' => array(
                        'type'  => 'date',
                        'max' => date("Y-m-d"),
                        'value' => $inte[0]["fecha_fin"],
                    ),
                    'options' => array(
                        'label' => 'Fecha fin de vinculación:'
                    ),
                ));
            }

            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array(
                '' => ''
            );
            
            foreach ($vf->getArrayvalores(36) as $dat) {
                $op = array(
                    $dat["id_valor_flexible"]  => $dat["descripcion_valor"]
                );
                $opciones = $opciones  + $op;
            }

            if($id2 == "0"){
                $IntegrantessemilleroForm->add(array(
                    'name' => 'tipo_vinculacion',
                    'type' => 'Zend\Form\Element\Select',
                    'options' => array(
                        'label' => 'Tipo vinculación:',
                        'value_options' => $opciones,
                        'empty_option' => 'Seleccione el tipo de vinculación',
                    ),
                    'attributes' => array(
                        'id' => 'tipo_vinculacion'
                    ) // set selected to '1'
                ));
            }else{
                $IntegrantessemilleroForm->add(array(
                    'name' => 'tipo_vinculacion',
                    'type' => 'Zend\Form\Element\Select',
                    'options' => array(
                        'label' => 'Tipo vinculación:',
                        'value_options' => $opciones,
                        'empty_option' => 'Seleccione el tipo de vinculación',
                    ),
                    'attributes' => array(
                        'id' => 'tipo_vinculacion',
                        'value' => $inte[0]["tipo_vinculacion"]
                    ) // set selected to '1'
                ));
            }

            // define el campo ciudad
            $vf = new Roles($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getRoles() as $dat) {
                $op = array(
                    $dat["id_rol"] => $dat["descripcion"]
                );
                $opciones = $opciones + $op;
            }
            
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
            $id = (int) $this->params()->fromRoute('id', 0);
  
            $us = new Usuarios($this->dbAdapter);
            $id = (int) $this->params()->fromRoute('id', 0);
            
            $view = new ViewModel(array(
                'form' => $IntegrantessemilleroForm,
                'titulo' => "Agregar Integrantes del semillero / Otros procesos de formación",
                'semillero' => $id,
                'menu' =>  $dat["id_rol"]
            ));
            return $view;
            
        }
    }

    public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Integrantesemillero($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getIntegrantesemillerobyid($id2);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/si/integrantes/si_i__".$id."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/si/integrantes/si_i__".$id."_".$filter->filter($fileName)));
        $response->setHeaders($headers);
        return $response;
    }
    
    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Integrantesemillero($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarIntegrantesemillero($id);
        $this->flashMessenger()->addMessage("Integrante eliminado  con éxito.");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editarsemilleroinv/index/' . $id2);
    }

    public function delAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $auth = $this->auth;
        $permisos = new Permisos($this->dbAdapter);
        $identi = print_r($auth->getStorage()->read()->id_usuario,true);
        $resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,"eliminarintegrantessemillero", false);
        if($resultadoPermiso==0){
            $this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
        }

        $IntegrantessemilleroForm = new IntegrantessemilleroForm();
        $u = new Integrantesemillero($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $IntegrantessemilleroForm,
            'titulo' => "Eliminar integrante semillero",
            'url' => $this->getRequest()->getBaseUrl(),
            'id' => $id,
            'id2' => $id2
        ));
        return $view;
    }
}