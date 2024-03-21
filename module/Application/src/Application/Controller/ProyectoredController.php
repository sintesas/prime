<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarredinv\ProyectoredForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Proyectored;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class ProyectoredController extends AbstractActionController
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
        $pantalla="editarsemilleroinv";
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
        
        if ($this->getRequest()->isPost()) {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Proyectored($this->dbAdapter);
            $data = $this->request->getPost();
            $id = (int) $this->params()->fromRoute('id', 0);
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            $upload = new \Zend\File\Transfer\Adapter\Http();
 
            if($id2!=0){
                $resul = $u->updateProyectosextred($id2, $data);
                if($upload->isUploaded()){
                    //Archivo cargado
                    $upload->setValidators(array(
                        'Size'  => array('min' => 1, 'max' => 50000000),
                    ));
                    if($upload->isValid()){
                        $files = $upload->getFileInfo();
                        foreach($files as $f){
                            $archi=$f["name"];
                        }
                        $resultado = $u->getProyectosextredById($id2);
                        if($resultado[0]["archivo"] != ""){
                            unlink("public/images/uploads/proyexternos/ri_ext__".$id."_".$resultado[0]["archivo"]);
                        }
                        $upload->setDestination('./public/images/uploads/proyexternos/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "ri_ext__".$id."_".$archi,'overwrite' => true));
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resultado = $u->updatearchivoProyectosextred($id2,$archi);
                        $this->flashMessenger()->addMessage("Proyecto externo actualizado con éxito.");
                    }else{
                        $this->flashMessenger()->addMessage("Proyecto externo actualizado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                }else{
                    $this->flashMessenger()->addMessage("Proyecto externo actualizado con éxito.");
                }
            }else{
                //Subir el nuevo archivo
                /* */
                    $upload->setDestination('./public/images/uploads/proyexternos/');
                    $files = $upload->getFileInfo();
                    foreach($files as $f){
                        $archi=$f["name"];
                    }

                    $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "ri_ext__".$id."_".$archi,'overwrite' => true));
                    $upload->setValidators(array(
                        'Size'  => array('min' => 1, 'max' => 50000000),
                    ));
                    
                    if($upload->isValid()){
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resul = $u->addProyectored($data, $id, $archi);
                        $this->flashMessenger()->addMessage("Proyecto externo agregado con éxito.");
                    }else{
                        $resul = $u->addProyectored($data, $id, "");
                        $this->flashMessenger()->addMessage("Proyecto externo creado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                /* */
            }
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/editarredinv/index/' . $id);
        } else {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Rolesusuario($this->dbAdapter);
            $ProyectoredForm = new ProyectoredForm();
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $us = new Usuarios($this->dbAdapter);
            $id = (int) $this->params()->fromRoute('id', 0);
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            if($id2!=0){
                $proyecto = new Proyectored($this->dbAdapter);
                $datBase = $proyecto->getProyectosextredById($id2);
                if($datBase!=null){
                    $ProyectoredForm->get('nombre')->setValue($datBase[0]["nombre"]);
                    $ProyectoredForm->get('codigo')->setValue($datBase[0]["codigo"]);
                    $ProyectoredForm->get('tipo')->setValue($datBase[0]["tipo"]);
                    $ProyectoredForm->get('fecha_inicio')->setValue($datBase[0]["fecha_inicio"]);
                    $ProyectoredForm->get('fecha_fin')->setValue($datBase[0]["fecha_fin"]);
                    $ProyectoredForm->get('resumen')->setValue($datBase[0]["resumen"]);
                    $ProyectoredForm->get('objetivo')->setValue($datBase[0]["objetivo"]);
                    $ProyectoredForm->get('productos')->setValue($datBase[0]["productos"]);
                    $ProyectoredForm->get('submit')->setValue("Actualizar");
                }
            }

            $view = new ViewModel(array(
                'form' => $ProyectoredForm,
                'titulo' => "Agregar Proyecto",
                'red' => $id,
                'id2' => $id2,
            ));
            return $view;
        }
    }


    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Proyectored($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarProyectored($id);
        $this->flashMessenger()->addMessage("Proyecto eliminados con éxito.");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editarredinv/index/' . $id2);
    }

    public function delAction()
    {
        $ProyectoredForm = new ProyectoredForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Proyectored($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $ProyectoredForm,
            'titulo' => "Eliminar proyecto",
            'url' => $this->getRequest()->getBaseUrl(),
            'id' => $id,
            'id2' => $id2
        ));
        return $view;
    }

    public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Proyectored($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getProyectosextredById($id);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/proyexternos/ri_ext__".$id2."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/proyexternos/ri_ext__".$id2."_".$filter->filter($fileName)));
        $response->setHeaders($headers);
        return $response;
    }
}