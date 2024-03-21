<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarredinv\ContactoredForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Contactored;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class ContactoredController extends AbstractActionController
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
            $u = new Contactored($this->dbAdapter);
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
                        $registro = $u->getContactoredById($id2);
                        if($registro != null){
                            unlink("public/images/uploads/ri/contacto/ri_c__".$id."_".$registro[0]["archivo"]);
                        }
                    }
                    $upload->setDestination('./public/images/uploads/ri/contacto/');
                    $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "ri_c__".$id."_".$file_name,'overwrite' => true));
                    $rtn = array('success' => null);
                    $rtn['success'] = $upload->receive();
                }else{
                    $error_file = true;
                }
            }

            if($id2!=0){
                $resul=$u->updateContactored($id2, $data, $file_name);
            }else{
                $resul = $u->addContactored($data, $id, $file_name);
            }
            
            if ($resul == 1) {
                if($error_file){
                    $this->flashMessenger()->addMessage("Contacto creado sin archivo adjunto. Recuerde que el tamaño máximo del archivo es de 25MB.");
                }else{
                    $this->flashMessenger()->addMessage("Contacto agregado correctamente.");
                }
            } else {
                $this->flashMessenger()->addMessage("El contacto no se pudo agregar.");
            }
            return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/editarredinv/index/' . $id);
        } else {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Rolesusuario($this->dbAdapter);
            $ContactoredForm = new ContactoredForm();
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
            
            $per = array(
                'id_rol' => ''
            );
            $datusu = array(
                'id_rol' => ''
            );
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $permiso = new Permisos($this->dbAdapter);
            
            // me verifica el tipo de rol asignado al usuario
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $datusu) {
                $datusu["id_rol"];
            }
            
            if ($datusu["id_rol"] != '') {
                // me verifica el permiso sobre la pantalla
                $pantalla = "editarredinv";
                $panta = 0;
                $pt = new Agregarvalflex($this->dbAdapter);
                
                foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
                    $panta["id_valor_flexible"];
                }
                
                $permiso->verificarPermiso($datusu["id_rol"], $panta["id_valor_flexible"]);
                foreach ($permiso->verificarPermiso($datusu["id_rol"], $panta["id_valor_flexible"]) as $per) {
                    $per["id_rol"];
                }
            }

            $id2 = (int) $this->params()->fromRoute('id2', 0);
            if($id2!=0){
                $u = new Contactored($this->dbAdapter);
                $datBase = $u->getContactoredById($id2);
                if($datBase!=null){
                    $ContactoredForm->get('telefono_1')->setValue($datBase[0]["telefono_1"]);
                    $ContactoredForm->get('telefono_2')->setValue($datBase[0]["telefono_2"]);
                    $ContactoredForm->get('redsocial_3')->setValue($datBase[0]["redsocial_3"]);
                    $ContactoredForm->get('redsocial_2')->setValue($datBase[0]["redsocial_2"]);
                    $ContactoredForm->get('redsocial_1')->setValue($datBase[0]["redsocial_1"]);
                    $ContactoredForm->get('otro_contacto')->setValue($datBase[0]["otro_contacto"]);
                    $ContactoredForm->get('correo')->setValue($datBase[0]["correo_electronico"]);
                    $ContactoredForm->get('paginaweb')->setValue($datBase[0]["pagina_web"]);
                    $ContactoredForm->get('submit')->setValue("Actualizar");
                }
            }

            $view = new ViewModel(array(
                'form' => $ContactoredForm,
                'titulo' => "Agregar Contacto",
                'red' => $id,
                'id2' => $id2,
                'menu' => $datusu["id_rol"]
            ));
            return $view;
            
        }
    }

    public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Contactored($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getContactoredById($id2);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/ri/contacto/ri_c__".$id."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/ri/contacto/ri_c__".$id."_".$filter->filter($fileName)));
        $response->setHeaders($headers);
        return $response;
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Contactored($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarContactored($id);
        $this->flashMessenger()->addMessage("Datos de contacto eliminados con éxito.");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editarredinv/index/' . $id2);
    }

    public function delAction()
    {
        $ContactoredForm = new ContactoredForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Contactored($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $ContactoredForm,
            'titulo' => "Eliminar datos de contacto",
            'url' => $this->getRequest()->getBaseUrl(),
            'id' => $id,
            'id2' => $id2
        ));
        return $view;
    }
}