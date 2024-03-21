<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarredinv\ParticipacioneventosredForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Participacioneventosred;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;

class ParticipacioneventosredController extends AbstractActionController
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
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $not = new Participacioneventosred($this->dbAdapter);
            $auth = $this->auth;
            
            // verifica si esta conectado
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $rol = new Roles($this->dbAdapter);
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
            }            
            
           
            $id = (int) $this->params()->fromRoute('id', 0);
            $ParticipacioneventosredForm = new ParticipacioneventosredForm();
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            $filter = new StringTrim();
            //Eliminar archivo antiguo
            if($id2!=0){
                $data = $not->getParticipacioneventosredid($id2);
                foreach ($data as $d) {
                    $fileName = $d["archivo"];
                    $new_archivo = $d["new_archivo"];
                }
                
                if($new_archivo == ""){
                    unlink("public/images/uploads/".$filter->filter($fileName));
                }else{
                    unlink("public/images/uploads/ri_pe_".$id."_".$filter->filter($fileName));
                }
                $not->eliminarParticipacioneventosred($id2);
            }
            $data = $this->request->getPost();
            //Subir el nuevo archivo
            /* */
                $upload = new \Zend\File\Transfer\Adapter\Http();
                $upload->setDestination('./public/images/uploads/');
                $files = $upload->getFileInfo();
                foreach($files as $f){
                    $archi=$f["name"];
                }

                $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "ri_pe_".$id."_".$archi,'overwrite' => true));
                $upload->setValidators(array(
                    'Size'  => array('min' => 1, 'max' => 50000000),
                ));
                
                if($upload->isValid()){
                    $rtn = array('success' => null);
                    $rtn['success'] = $upload->receive();
                         
                    $resultado = $not->addParticipacioneventosred($data, $id, $archi);
                    $this->flashMessenger()->addMessage("Participación en evento creada con éxito.");
                    return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/editarredinv/index/' . $id);

                }else{
                    $this->flashMessenger()->addMessage("La carga del archivo falló.");
                    return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/editarredinv/index/' . $id);
                }
            /* */
        } else {
            $ParticipacioneventosredForm = new ParticipacioneventosredForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Participacioneventosred($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $filter = new StringTrim();
            
            // define el campo ciudad
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array("" => "Seleccione el tipo de evento");
            foreach ($vf->getArrayvalores(56) as $dept) {
                $op = array(
                    $dept["id_valor_flexible"] => $dept["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $ParticipacioneventosredForm->get('tipo_participacion')->setValueOptions($opciones);
                        
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
                $pantalla = "editarredinv";
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
            
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            if($id2!=0){
                $datBase = $u->getParticipacioneventosredid($id2);
                if($datBase!=null){
                    $ParticipacioneventosredForm->get('nombre')->setValue($datBase[0]["nombre"]);
                    $ParticipacioneventosredForm->get('lugar')->setValue($datBase[0]["lugar"]);
                    $ParticipacioneventosredForm->get('tipo_participacion')->setValue($datBase[0]["tipo_participacion"]);
                    $ParticipacioneventosredForm->get('fecha')->setValue($datBase[0]["fecha"]);
                    $ParticipacioneventosredForm->get('submit')->setValue("Actualizar");
                }
            }

            if (true) {
                $id = (int) $this->params()->fromRoute('id', 0);
                $view = new ViewModel(array(
                    'form' => $ParticipacioneventosredForm,
                    'titulo' => "Agregar Participación en eventos",
                    'url' => $this->getRequest()->getBaseUrl(),
                    'datos' => $u->getParticipacioneventosred($id),
                    'id' => $id,
                    'id2' => $id2,
                    'menu' => $dat["id_rol"]
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/mensajeadministrador/index');
            }
        }
    }

    public function eliminarAction()
    {
        $filter = new StringTrim();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Participacioneventosred($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);

        $data = $u->getParticipacioneventosredid($id);
        foreach ($data as $d) {
            $fileName = $d["archivo"];
            $new_archivo = $d["new_archivo"];
        }
        
        if($new_archivo == ""){
            unlink("public/images/uploads/".$filter->filter($fileName));
        }else{
            unlink("public/images/uploads/ri_pe_".$id2."_".$filter->filter($fileName));
        }
        $u->eliminarParticipacioneventosred($id);
        $this->flashMessenger()->addMessage("Archivo eliminado con éxito.");
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/editarredinv/index/' . $id2);
    }

    public function delAction()
    {
        $ArchivosForm = new ParticipacioneventosredForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Participacioneventosred($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $ArchivosForm,
            'titulo' => "Eliminar archivo",
            'url' => $this->getRequest()->getBaseUrl(),
            'datos' => $id,
            'id2' => $id2
        ));
        return $view;
    }

    public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Participacioneventosred($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $data = $u->getParticipacioneventosredid($id);
        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
            $new_archivo = $d["new_archivo"];
        }

        $response = new \Zend\Http\Response\Stream();
        if($new_archivo == ""){
            $response->setStream(fopen("public/images/uploads/".$filter->filter($fileName), 'r'));  
            $response->setStatusCode(200);
            $headers = new \Zend\Http\Headers();
            $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                    ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                    ->addHeaderLine('Content-Length', filesize("public/images/uploads/".$filter->filter($fileName)));

        }else{
            $response->setStream(fopen("public/images/uploads/ri_pe_".$id2."_".$filter->filter($fileName), 'r'));
            $response->setStatusCode(200);
            $headers = new \Zend\Http\Headers();
            $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                    ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                    ->addHeaderLine('Content-Length', filesize("public/images/uploads/ri_pe_".$id2."_".$filter->filter($fileName)));
        }
        $response->setHeaders($headers);
        return $response;
    }
}