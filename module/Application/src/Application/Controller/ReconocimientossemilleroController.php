<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarsemilleroinv\ReconocimientossemilleroForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Reconocimientossemillero;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class ReconocimientossemilleroController extends AbstractActionController
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
        //termina de verifcar los permisos
        if(true){
            if ($this->getRequest()->isPost()) {
                $this->dbAdapter = $this->getServiceLocator()->get('db1');
                $u = new Reconocimientossemillero($this->dbAdapter);
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
                            $registro = $u->getReconoimientosemiById($id2);
                            if($registro != null){
                                unlink("public/images/uploads/si/reconocimientos/si_r__".$id."_".$registro[0]["archivo"]);
                            }
                        }
                        $upload->setDestination('./public/images/uploads/si/reconocimientos/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "si_r__".$id."_".$file_name,'overwrite' => true));
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                    }else{
                        $error_file = true;
                    }
                }

                if($id2!=0){
                    $resul = $u->updateReconocimientosemi($id2, $data, $file_name);
                }else{
                    $resul = $u->addReconocimientossemillero($data, $id, $file_name);
                }
                
                if ($resul == 1) {
                    if($error_file){
                        $this->flashMessenger()->addMessage("Reconocimiento creado sin archivo adjunto. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }else{
                        $this->flashMessenger()->addMessage("Se agregó el reconocimiento al semillero.");
                    }
                } else {
                    $this->flashMessenger()->addMessage("No se agregó el reconocimiento al semillero.");
                }
                return $this->redirect()->toUrl($this->getRequest()
                        ->getBaseUrl() . '/application/editarsemilleroinv/index/' . $id);
            } else {
                $ReconocimientossemilleroForm = new ReconocimientossemilleroForm();               
                $id = (int) $this->params()->fromRoute('id', 0);
                $id2 = (int) $this->params()->fromRoute('id2', 0);
                if($id2!=0){
                    $linea = new Reconocimientossemillero($this->dbAdapter);
                    $datBase = $linea->getReconoimientosemiById($id2);
                    if($datBase!=null){
                        $ReconocimientossemilleroForm->get('descripcion')->setValue($datBase[0]["descripcion"]);
                        $ReconocimientossemilleroForm->get('valor')->setValue($datBase[0]["valor"]);
                        $ReconocimientossemilleroForm->get('numero_acto')->setValue($datBase[0]["numero_acto"]);
                        $ReconocimientossemilleroForm->get('fecha')->setValue($datBase[0]["fecha"]);
                        $ReconocimientossemilleroForm->get('nombre')->setValue($datBase[0]["nombre"]);
                        $ReconocimientossemilleroForm->get('submit')->setValue("Actualizar");
                    }
                }

                $us = new Usuarios($this->dbAdapter);
                $view = new ViewModel(array(
                    'form' => $ReconocimientossemilleroForm,
                    'titulo' => "Agregar Reconocimientos",
                    'semillero' => $id,
                    'id2' => $id2,
                    'menu' =>  $dat["id_rol"]
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
        $u = new Reconocimientossemillero($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getReconoimientosemiById($id2);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/si/reconocimientos/si_r__".$id."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/si/reconocimientos/si_r__".$id."_".$filter->filter($fileName)));
        $response->setHeaders($headers);
        return $response;
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Reconocimientossemillero($this->dbAdapter);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarReconocimientossemillero($id);
        $this->flashMessenger()->addMessage("Área de actuación eliminada con éxito.");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editarsemilleroinv/index/' . $id2);
    }

    public function delAction()
    {
        $ReconocimientossemilleroForm = new ReconocimientossemilleroForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $ReconocimientossemilleroForm,
            'titulo' => "Eliminar área",
            'url' => $this->getRequest()->getBaseUrl(),
            'datos' => $id,
            'id2' => $id2
        ));
        return $view;
    }
}