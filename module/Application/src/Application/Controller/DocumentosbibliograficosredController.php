<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarredinv\DocumentosbibliograficosredForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Documentosbibliograficosred;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;
use Application\Modelo\Entity\Agregarautorred;

class DocumentosbibliograficosredController extends AbstractActionController
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
        if ($this->getRequest()->isPost()) {
            $u = new Documentosbibliograficosred($this->dbAdapter);
            $data = $this->request->getPost();
            $id = (int) $this->params()->fromRoute('id', 0);
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            $upload = new \Zend\File\Transfer\Adapter\Http();
            if($id2!=0){
                $resul = $u->updateBibliograficosred($id2, $data);
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
                        $resultado = $u->getBibliograficosredById($id2);
                        if($resultado[0]["archivo"] != ""){
                            unlink("public/images/uploads/documentos/ri_doc__".$id."_".$resultado[0]["archivo"]);
                        }
                        $upload->setDestination('./public/images/uploads/documentos/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "ri_doc__".$id."_".$archi,'overwrite' => true));
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resultado = $u->updatearchivoBibliograficosred($id2,$archi);
                        $this->flashMessenger()->addMessage("Documento bibliográfico actualizado con éxito.");
                    }else{
                        $this->flashMessenger()->addMessage("Documento bibliográfico actualizado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                }else{
                    $this->flashMessenger()->addMessage("Documento bibliográfico actualizado con éxito.");
                }
            }else{
                //Subir el nuevo archivo
                /* */
                    $upload->setDestination('./public/images/uploads/documentos/');
                    $files = $upload->getFileInfo();
                    foreach($files as $f){
                        $archi=$f["name"];
                    }

                    $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "ri_doc__".$id."_".$archi,'overwrite' => true));
                    $upload->setValidators(array(
                        'Size'  => array('min' => 1, 'max' => 50000000),
                    ));
                    
                    if($upload->isValid()){
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resul = $u->addDocumentosbibliograficosred($data, $id, $archi);
                        $this->flashMessenger()->addMessage("Documento bibliográfico agregado con éxito.");
                    }else{
                        $resul = $u->addDocumentosbibliograficosred($data, $id, "");
                        $this->flashMessenger()->addMessage("Documento bibliográfico creado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                /* */
            }
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/editarredinv/index/' . $id);
        } else {
            $u = new Rolesusuario($this->dbAdapter);
            $DocumentosbibliograficosredForm = new DocumentosbibliograficosredForm();
            $auth = $this->auth;
            
            $filter = new StringTrim();
            $usuario = new Usuarios($this->dbAdapter);
            $opciones = array();
            $a = '';
            
            foreach ($usuario->getUsuarios($a) as $pa) {
                $nombre_completo = $pa["primer_nombre"] . ' ' . $pa["segundo_nombre"] . ' ' . $pa["primer_apellido"] . ' ' . $pa["segundo_apellido"];
                $op = array(
                    $pa["id_usuario"] => $nombre_completo
                );
                $opciones = $opciones + $op;
            }
            $DocumentosbibliograficosredForm->get('id_autor')->setValueOptions($opciones);
            
                        
            $id = (int) $this->params()->fromRoute('id', 0);
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            if($id2!=0){
                $documento = new Documentosbibliograficosred($this->dbAdapter);
                $datBase = $documento->getBibliograficosredById($id2);
                if($datBase!=null){
                    $DocumentosbibliograficosredForm->get('nombre')->setValue($datBase[0]["nombre"]);
                    $DocumentosbibliograficosredForm->get('numero_paginas')->setValue($datBase[0]["numero_paginas"]);
                    $DocumentosbibliograficosredForm->get('instituciones')->setValue($datBase[0]["instituciones"]);
                    $DocumentosbibliograficosredForm->get('ano')->setValue($datBase[0]["ano"]);
                    $DocumentosbibliograficosredForm->get('mes')->setValue($datBase[0]["mes"]);
                    $DocumentosbibliograficosredForm->get('numero_indexacion')->setValue($datBase[0]["numero_indexacion"]);
                    $DocumentosbibliograficosredForm->get('url')->setValue($datBase[0]["url"]);
                    $DocumentosbibliograficosredForm->get('medio_divulgacion')->setValue($datBase[0]["medio_divulgacion"]);
                    $DocumentosbibliograficosredForm->get('descripcion')->setValue($datBase[0]["descripcion"]);
                    $DocumentosbibliograficosredForm->get('pais')->setValue($datBase[0]["pais"]);
                    $DocumentosbibliograficosredForm->get('ciudad')->setValue($datBase[0]["ciudad"]);
                    $DocumentosbibliograficosredForm->get('id_autor')->setValue($datBase[0]["id_autor"]);
                    $DocumentosbibliograficosredForm->get('submit')->setValue("Actualizar");
                }
            }
            $view = new ViewModel(array(
                'form' => $DocumentosbibliograficosredForm,
                'titulo' => "Agregar Otros documentos bibliográficos",
                'red' => $id,
                'id2' => $id2
            ));
            return $view;           
        }
    }


    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Documentosbibliograficosred($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarDocumentosbibliograficosred($id);
        $this->flashMessenger()->addMessage("Documento bibliográfico eliminado con éxito.");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editarredinv/index/' . $id2);
    }

    public function delAction()
    {
        $DocumentosbibliograficosredForm = new DocumentosbibliograficosredForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Documentosbibliograficosred($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $DocumentosbibliograficosredForm,
            'titulo' => "Eliminar documento bibliográfico",
            'url' => $this->getRequest()->getBaseUrl(),
            'id' => $id,
            'id2' => $id2
        ));
        return $view;
    }

    public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Documentosbibliograficosred($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getBibliograficosredById($id);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/documentos/ri_doc__".$id2."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/documentos/ri_doc__".$id2."_".$filter->filter($fileName)));
        $response->setHeaders($headers);
        return $response;
    }
}