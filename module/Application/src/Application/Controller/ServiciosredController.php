<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarredinv\ServiciosredForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Serviciosred;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class ServiciosredController extends AbstractActionController
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
                $u = new Serviciosred($this->dbAdapter);
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
                            $registro = $u->getServicioById($id2);
                            if($registro != null){
                                unlink("public/images/uploads/ri/serviciosr/ri_sr__".$id."_".$registro[0]["archivo"]);
                            }
                        }
                        $upload->setDestination('./public/images/uploads/ri/serviciosr/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "ri_sr__".$id."_".$file_name,'overwrite' => true));
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                    }else{
                        $error_file = true;
                    }
                }

                if($id2!=0){
                    $resul=$u->updateServicio($id2, $data, $file_name);
                }else{
                    $resul = $u->addServiciored($data, $id, $file_name);
                }
                
                if ($resul == 1) {
                    if($error_file){
                        $this->flashMessenger()->addMessage("Servicio creado sin archivo adjunto. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }else{
                        $this->flashMessenger()->addMessage("Servicio agregado correctamente.");
                    }
                } else {
                    $this->flashMessenger()->addMessage("El servicio no se pudo agregar.");
                }
                return $this->redirect()->toUrl($this->getRequest()
                        ->getBaseUrl() . '/application/editarredinv/index/' . $id);
            } else {
                $this->dbAdapter = $this->getServiceLocator()->get('db1');
                $u = new Rolesusuario($this->dbAdapter);
                $ServiciosredForm = new ServiciosredForm();
                $auth = $this->auth;
                
                $identi = $auth->getStorage()->read();
                if ($identi == false && $identi == null) {
                    return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/login/index');
                }
                $id = (int) $this->params()->fromRoute('id', 0);
                $id2 = (int) $this->params()->fromRoute('id2', 0);
                $Serviciosred = new Serviciosred($this->dbAdapter);
                if($id2!=0){
                    $datBase = $Serviciosred->getServicioById($id2);
                    if($datBase!=null){
                        $ServiciosredForm->get('servicios')->setValue($datBase[0]["servicios"]);
                        $ServiciosredForm->get('noticias')->setValue($datBase[0]["noticias"]);
                        $ServiciosredForm->get('submit')->setValue("Actualizar");
                    }
                }
                $view = new ViewModel(array(
                    'form' => $ServiciosredForm,
                    'titulo' => "Agregar Servicio",
                    'red' => $id
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
        $u = new Serviciosred($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getServicioById($id2);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/ri/serviciosr/ri_sr__".$id."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/ri/serviciosr/ri_sr__".$id."_".$filter->filter($fileName)));
        $response->setHeaders($headers);
        return $response;
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Serviciosred($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarServiciored($id);
        $this->flashMessenger()->addMessage("Servicio eliminado con éxito.");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editarredinv/index/' . $id2);
    }

    public function delAction()
    {
        $ServiciosredForm = new ServiciosredForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Serviciosred($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $ServiciosredForm,
            'titulo' => "Eliminar servicio",
            'url' => $this->getRequest()->getBaseUrl(),
            'id' => $id,
            'id2' => $id2
        ));
        return $view;
    }
}