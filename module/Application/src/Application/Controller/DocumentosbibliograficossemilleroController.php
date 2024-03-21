<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarsemilleroinv\DocumentosbibliograficossemilleroForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Documentosbibliograficossemillero;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;
use Application\Modelo\Entity\Agregarautorsemillero;

class DocumentosbibliograficossemilleroController extends AbstractActionController
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
            $u = new Documentosbibliograficossemillero($this->dbAdapter);
            $data = $this->request->getPost();
            $id = (int) $this->params()->fromRoute('id', 0);
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            $upload = new \Zend\File\Transfer\Adapter\Http();
                    
            if($id2!=0){
                $resul = $u->updateBibliograficossemillero($id2, $data);
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
                        $resultado = $u->getBibliograficossemilleroById($id2);
                        if($resultado[0]["archivo"] != ""){
                            unlink("public/images/uploads/documentos/si_doc__".$id."_".$resultado[0]["archivo"]);
                        }
                        $upload->setDestination('./public/images/uploads/documentos/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "si_doc__".$id."_".$archi,'overwrite' => true));
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resultado = $u->updatearchivoBibliograficossemillero($id2,$archi);
                        $this->flashMessenger()->addMessage("Documento actualizado con éxito.");
                    }else{
                        $this->flashMessenger()->addMessage("Documento actualizado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                }else{
                    $this->flashMessenger()->addMessage("Documento actualizado con éxito.");
                }
            }else{
                //Subir el nuevo archivo
                /* */
                    $upload->setDestination('./public/images/uploads/documentos/');
                    $files = $upload->getFileInfo();
                    foreach($files as $f){
                        $archi=$f["name"];
                    }

                    $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "si_doc__".$id."_".$archi,'overwrite' => true));
                    $upload->setValidators(array(
                        'Size'  => array('min' => 1, 'max' => 50000000),
                    ));
                    
                    if($upload->isValid()){
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resul = $u->addDocumentosbibliograficossemillero($data, $id, $archi);
                        $this->flashMessenger()->addMessage("Documento agregado con éxito.");
                    }else{
                        $resul = $u->addDocumentosbibliograficossemillero($data, $id, "");
                        $this->flashMessenger()->addMessage("Documento creado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                /* */
            }
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/editarsemilleroinv/index/' . $id);
        } else {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Rolesusuario($this->dbAdapter);
            $DocumentosbibliograficossemilleroForm = new DocumentosbibliograficossemilleroForm();
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }

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
            $DocumentosbibliograficossemilleroForm->get('id_autor')->setValueOptions($opciones);

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
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            if($id2!=0){
                $documento = new Documentosbibliograficossemillero($this->dbAdapter);
                $datBase = $documento->getBibliograficossemilleroById($id2);
                if($datBase!=null){
                    $DocumentosbibliograficossemilleroForm->get('nombre')->setValue($datBase[0]["nombre"]);
                    $DocumentosbibliograficossemilleroForm->get('numero_paginas')->setValue($datBase[0]["numero_paginas"]);
                    $DocumentosbibliograficossemilleroForm->get('instituciones')->setValue($datBase[0]["instituciones"]);
                    $DocumentosbibliograficossemilleroForm->get('ano')->setValue($datBase[0]["ano"]);
                    $DocumentosbibliograficossemilleroForm->get('mes')->setValue($datBase[0]["mes"]);
                    $DocumentosbibliograficossemilleroForm->get('numero_indexacion')->setValue($datBase[0]["numero_indexacion"]);
                    $DocumentosbibliograficossemilleroForm->get('url')->setValue($datBase[0]["url"]);
                    $DocumentosbibliograficossemilleroForm->get('medio_divulgacion')->setValue($datBase[0]["medio_divulgacion"]);
                    $DocumentosbibliograficossemilleroForm->get('descripcion')->setValue($datBase[0]["descripcion"]);
                    $DocumentosbibliograficossemilleroForm->get('pais')->setValue($datBase[0]["pais"]);
                    $DocumentosbibliograficossemilleroForm->get('ciudad')->setValue($datBase[0]["ciudad"]);
                    $DocumentosbibliograficossemilleroForm->get('id_autor')->setValue($datBase[0]["id_autor"]);
                    $DocumentosbibliograficossemilleroForm->get('submit')->setValue("Actualizar");
                }
            }
            $view = new ViewModel(array(
                'form' => $DocumentosbibliograficossemilleroForm,
                'titulo' => "Agregar Otros documentos",
                'semillero' => $id,
                'id2' => $id2,
                'menu' => $dat["id_rol"]
            ));
            return $view;           
        }
    }


    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Documentosbibliograficossemillero($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarDocumentosbibliograficossemillero($id);
        $this->flashMessenger()->addMessage("Documento bibliográfico eliminado con éxito.");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editarsemilleroinv/index/' . $id2);
    }

    public function delAction()
    {
        $DocumentosbibliograficossemilleroForm = new DocumentosbibliograficossemilleroForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Documentosbibliograficossemillero($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $DocumentosbibliograficossemilleroForm,
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
        $u = new Documentosbibliograficossemillero($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getBibliograficossemilleroById($id);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/documentos/si_doc__".$id2."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/documentos/si_doc__".$id2."_".$filter->filter($fileName)));
        $response->setHeaders($headers);
        return $response;
    }
}