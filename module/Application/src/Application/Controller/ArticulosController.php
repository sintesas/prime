<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editargrupoinv\ArticulosForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Articulos;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;

class ArticulosController extends AbstractActionController
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
            $not = new Articulos($this->dbAdapter);
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
            
            // obtiene la informacion de las pantallas
            $data = $this->request->getPost();
            $ArticulosForm = new ArticulosForm();
            // adiciona la noticia
            $id = (int) $this->params()->fromRoute('id', 0);
            $id2 = (int) $this->params()->fromRoute('id2', 0);

            $upload = new \Zend\File\Transfer\Adapter\Http();

            if($id2!=0){
                $resultado=$not->updatArticulos($id2, $data);
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
                        $resultado = $not->getArticuloid($id2);
                        if($resultado[0]["archivo"] != ""){
                            unlink("public/images/uploads/articulos/gi_art__".$id."_".$resultado[0]["archivo"]);
                        }
                        $upload->setDestination('./public/images/uploads/articulos/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "gi_art__".$id."_".$archi,'overwrite' => true));
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resultado = $not->updatearchivoArticulos($id2,$archi);
                        $this->flashMessenger()->addMessage("Artículo actualizado con éxito.");
                    }else{
                        $this->flashMessenger()->addMessage("Artículo actualizado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                }else{
                    $this->flashMessenger()->addMessage("Artículo actualizado con éxito.");
                }
            }else{
                //Subir el nuevo archivo
                /* */
                    $upload->setDestination('./public/images/uploads/articulos/');
                    $files = $upload->getFileInfo();
                    foreach($files as $f){
                        $archi=$f["name"];
                    }

                    $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "gi_art__".$id."_".$archi,'overwrite' => true));
                    $upload->setValidators(array(
                        'Size'  => array('min' => 1, 'max' => 50000000),
                    ));
                    
                    if($upload->isValid()){
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resultado = $not->addArticulos($data, $id, $archi);
                        $this->flashMessenger()->addMessage("Artículo agregado con éxito.");
                    }else{
                        $resultado = $not->addArticulos($data, $id, "");
                        $this->flashMessenger()->addMessage("Artículo creado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                /* */
            }
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/editargrupoinv/index/' . $id);
        } else {
            $ArticulosForm = new ArticulosForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Articulos($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
                       
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(57) as $dept) {
                $op = array(
                    $dept["id_valor_flexible"] => $dept["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $ArticulosForm->get('categoria')->setValueOptions($opciones);
            
            // define el campo autor
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
            $ArticulosForm->get('id_autor')->setValueOptions($opciones);
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
            
            if (true) {
                $id = (int) $this->params()->fromRoute('id', 0);
                $id2 = (int) $this->params()->fromRoute('id2', 0);
                if($id2!=0){
                    $linea = new Articulos($this->dbAdapter);
                    $datBase = $linea->getArticuloById($id2);
                    if($datBase!=null){
                        $ArticulosForm->get('nombre_revista')->setValue($datBase[0]["nombre_revista"]);
                        $ArticulosForm->get('nombre_articulo')->setValue($datBase[0]["nombre_articulo"]);
                        $ArticulosForm->get('id_pais')->setValue($datBase[0]["id_pais"]);
                        $ArticulosForm->get('issn')->setValue($datBase[0]["issn"]);
                        $ArticulosForm->get('paginas')->setValue($datBase[0]["paginas"]);
                        $ArticulosForm->get('num_paginas')->setValue($datBase[0]["num_paginas"]);
                        $ArticulosForm->get('volumen')->setValue($datBase[0]["volumen"]);
                        $ArticulosForm->get('serie')->setValue($datBase[0]["serie"]);
                        $ArticulosForm->get('id_autor')->setValue($datBase[0]["id_autor"]);
                        $ArticulosForm->get('pagina_inicio')->setValue($datBase[0]["pagina_inicio"]);
                        $ArticulosForm->get('pagina_fin')->setValue($datBase[0]["pagina_fin"]);
                        $ArticulosForm->get('fasciculo')->setValue($datBase[0]["fasciculo"]);
                        $ArticulosForm->get('id_ciudad')->setValue($datBase[0]["id_ciudad"]);
                        $ArticulosForm->get('ano')->setValue($datBase[0]["ano"]);
                        $ArticulosForm->get('mes')->setValue($datBase[0]["mes"]);
                        $ArticulosForm->get('categoria')->setValue($datBase[0]["categoria"]);
                        $ArticulosForm->get('submit')->setValue("Actualizar");
                    }
                }

                $view = new ViewModel(array(
                    'form' => $ArticulosForm,
                    'titulo' => "Artículos de Investigación",
                    'url' => $this->getRequest()->getBaseUrl(),
                    'datos' => $u->getArticulos($id),
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
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Articulos($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarArticulos($id);
        $this->flashMessenger()->addMessage("Articulo eliminado con exito");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editargrupoinv/index/' . $id2);
    }

    public function delAction()
    {
        $ArticulosForm = new ArticulosForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Articulos($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $ArticulosForm,
            'titulo' => "Eliminar registro",
            'url' => $this->getRequest()->getBaseUrl(),
            'datos' => $id,
            'id2' => $id2
        ));
        return $view;
    }

    public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Articulos($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getArticuloid($id);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/articulos/gi_art__".$id2."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/articulos/gi_art__".$id2."_".$filter->filter($fileName)));
        $response->setHeaders($headers);
        return $response;
    }
}