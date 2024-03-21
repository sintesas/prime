<?php
namespace Application\Controller;

use Application\Editargrupoinv\ReconocimientosForm;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Reconocimientos;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Zend\Authentication\AuthenticationService;
use Zend\Filter\StringTrim;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ReconocimientosController extends AbstractActionController
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
            $not = new Reconocimientos($this->dbAdapter);
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
            $ReconocimientosForm = new ReconocimientosForm();
            // adiciona la noticia
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
                        $registro = $not->getReconoimientoById($id2);
                        if($registro != null){
                            unlink("public/images/uploads/gi/reconocimientos/gi_r__".$id."_".$registro[0]["archivo"]);
                        }
                    }
                    $upload->setDestination('./public/images/uploads/gi/reconocimientos/');
                    $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "gi_r__".$id."_".$file_name,'overwrite' => true));
                    $rtn = array('success' => null);
                    $rtn['success'] = $upload->receive();
                }else{
                    $error_file = true;
                }
            }

            if($id2!=0){
                $resultado=$not->updateReconocimiento($id2, $data, $file_name);
            }else{
                 $resultado = $not->addReconocimientos($data, $id, $file_name);
            }
                       
            // redirige a la pantalla de inicio del controlador
            if ($resultado == 1) {
                if($error_file){
                    $this->flashMessenger()->addMessage("Reconocimiento creado sin archivo adjunto. Recuerde que el tamaño máximo del archivo es de 25MB.");
                }else{
                    $this->flashMessenger()->addMessage("Reconocimiento creado con exito");
                }
            } else {
                $this->flashMessenger()->addMessage("La creacion del reconocimiento fallo");
            }
            return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/editargrupoinv/index/' . $id);
        } else {
            $ReconocimientosForm = new ReconocimientosForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Reconocimientos($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $filter = new StringTrim();
            
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
                    $linea = new Reconocimientos($this->dbAdapter);
                    $datBase = $linea->getReconoimientoById($id2);
                    if($datBase!=null){
                        $ReconocimientosForm->get('descripcion')->setValue($datBase[0]["descripcion"]);
                        $ReconocimientosForm->get('valor')->setValue($datBase[0]["valor"]);
                        $ReconocimientosForm->get('num_acto')->setValue($datBase[0]["num_acto"]);
                        $ReconocimientosForm->get('semestre')->setValue($datBase[0]["semestre"]);
                        $ReconocimientosForm->get('nombre')->setValue($datBase[0]["nombre"]);
                        $ReconocimientosForm->get('submit')->setValue("Actualizar");
                    }
                }

                $view = new ViewModel(array(
                    'form' => $ReconocimientosForm,
                    'titulo' => "Reconocimientos",
                    'url' => $this->getRequest()->getBaseUrl(),
                    'datos' => $u->getReconocimientos($id),
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

    public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Reconocimientos($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getReconoimientoById($id2);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/gi/reconocimientos/gi_r__".$id."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/gi/reconocimientos/gi_r__".$id."_".$filter->filter($fileName)));
        $response->setHeaders($headers);
        return $response;
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Reconocimientos($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarReconocimientos($id);
        $this->flashMessenger()->addMessage("Reconocimiento eliminado con exito");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editargrupoinv/index/' . $id2);
    }

    public function delAction()
    {
        $ReconocimientosForm = new ReconocimientosForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Reconocimientos($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $ReconocimientosForm,
            'titulo' => "Eliminar registro",
            'url' => $this->getRequest()->getBaseUrl(),
            'datos' => $id,
            'id2' => $id2
        ));
        return $view;
    }
}