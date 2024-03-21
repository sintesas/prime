<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarredinv\EquipodirectivoForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Equipodirectivo;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class EquipodirectivoController extends AbstractActionController
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
        $identi=$auth->getStorage()->read();
        if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
        }

        $filter = new StringTrim();
        //verificar roles
        $per=array('id_rol'=>'');
        $dat=array('id_rol'=>'');
        $rolusuario = new Rolesusuario($this->dbAdapter);
        $permiso = new Permisos($this->dbAdapter);
    
        //me verifica el tipo de rol asignado al usuario
        $rolusuario->verificarRolusuario($identi->id_usuario);
        foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
            $dat["id_rol"];
        }
    
        if ($dat["id_rol"]!= ''){
        //me verifica el permiso sobre la pantalla
        $pantalla="editarredinv";
        $panta=0;
        $pt = new Agregarvalflex($this->dbAdapter);
        foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
            $panta["id_valor_flexible"];
        }

        $permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]);
        foreach ($permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]) as $per) {
            $per["id_rol"];
        }
        }
        //termina de verifcar los permisos
        if(true){
            if ($this->getRequest()->isPost()) {
                $this->dbAdapter = $this->getServiceLocator()->get('db1');
                $u = new Equipodirectivo($this->dbAdapter);
                $auth = $this->auth;
                
                $identi = $auth->getStorage()->read();
                if ($identi == false && $identi == null) {
                    return $this->redirect()->toUrl($this->getRequest()
                        ->getBaseUrl() . '/application/login/index');
                }
                
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
                            $registro = $u->getEquipoById($id2);
                            if($registro != null){
                                unlink("public/images/uploads/ri/equipod/ri_eq__".$id."_".$registro[0]["archivo"]);
                            }
                        }
                        $upload->setDestination('./public/images/uploads/ri/equipod/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "ri_eq__".$id."_".$file_name,'overwrite' => true));
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                    }else{
                        $error_file = true;
                    }
                }

                if($id2!=0){
                    $resul=$u->updateEquipo($id2, $data, $file_name);
                }else{
                    $resul = $u->addEquipodirectivo($data, $id, $file_name);
                }
                
                if ($resul == 1) {
                    if($error_file){
                        $this->flashMessenger()->addMessage("Equipo directivo creado sin archivo adjunto. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }else{
                        $this->flashMessenger()->addMessage("Equipo directivo agregado con éxito.");
                    }
                } else {
                    $this->flashMessenger()->addMessage("El equipo directivo no pudo ser agregado.");
                }
                return $this->redirect()->toUrl($this->getRequest()
                        ->getBaseUrl() . '/application/editarredinv/index/' . $id);
            } else {
                $this->dbAdapter = $this->getServiceLocator()->get('db1');
                $u = new Rolesusuario($this->dbAdapter);
                $EquipodirectivoForm = new EquipodirectivoForm();
                $auth = $this->auth;
                
                $identi = $auth->getStorage()->read();
                if ($identi == false && $identi == null) {
                    return $this->redirect()->toUrl($this->getRequest()
                        ->getBaseUrl() . '/application/login/index');
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
      
                $us = new Usuarios($this->dbAdapter);
                $id = (int) $this->params()->fromRoute('id', 0);
                $id2 = (int) $this->params()->fromRoute('id2', 0);
                if($id2!=0){
                    $Equipodirectivo = new Equipodirectivo($this->dbAdapter);
                    $datBase = $Equipodirectivo->getEquipoById($id2);
                    if($datBase!=null){
                        $EquipodirectivoForm->get('cargo')->setValue($datBase[0]["cargo"]);
                        $EquipodirectivoForm->get('institucion')->setValue($datBase[0]["institucion"]);
                        $EquipodirectivoForm->get('nombre')->setValue($datBase[0]["nombre"]);
                        $EquipodirectivoForm->get('pais')->setValue($datBase[0]["pais"]);
                        $EquipodirectivoForm->get('submit')->setValue("Actualizar");
                    }
                }
                    
                $view = new ViewModel(array(
                    'form' => $EquipodirectivoForm,
                    'titulo' => "Asignar Equipo directivo o Estructura organizacional",
                    'red' => $id,
                    'id2' => $id2
                ));
                return $view;   
            }
        }
        else{
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
        }
    }

    public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Equipodirectivo($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getEquipoById($id2);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/ri/equipod/ri_eq__".$id."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/ri/equipod/ri_eq__".$id."_".$filter->filter($fileName)));
        $response->setHeaders($headers);
        return $response;
    }

    public function asignarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Integrantes($this->dbAdapter);
        $data = $this->request->getPost();
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $id = (int) $this->params()->fromRoute('id', 0);
        $resul = $u->addIntegrantes($id, $id2);
        if ($resul == 1) {
            $this->flashMessenger()->addMessage("Asigno el integrante al grupo");
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/editargrupoinv/index/' . $id2);
        } else {
            $this->flashMessenger()->addMessage("El Usuario ya tiene un rol asignado");
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/editargrupoinv/index/' . $id2);
        }
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Equipodirectivo($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarEquipodirectivo($id);
        $this->flashMessenger()->addMessage("Integrante del Equipo directivo eliminado con éxito");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editarredinv/index/' . $id2);
    }

    public function delAction()
    {
        $EquipodirectivoForm = new EquipodirectivoForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Equipodirectivo($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $EquipodirectivoForm,
            'titulo' => "Eliminar integrante del Equipo directivo",
            'url' => $this->getRequest()->getBaseUrl(),
            'id' => $id,
            'id2' => $id2
        ));
        return $view;
    }
}