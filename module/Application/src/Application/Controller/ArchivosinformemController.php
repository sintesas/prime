<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\ArchivosinformemForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Informesm;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;

class ArchivosinformemController extends AbstractActionController
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
        if ($this->getRequest()->isPost()) {
            // Creacion adaptador de conexion, objeto de datos, auditoria
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $not = new Informesm($this->dbAdapter);
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
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            $id_informe = $not->getIdInforme($id, $id2);
            if(count($id_informe)==0){
                $not->addInforme($id, $id2);
                $id_informe = $not->getIdInforme($id, $id2);
            }
            

            $upload = new \Zend\File\Transfer\Adapter\Http();
            $upload->setDestination('./public/images/uploads/');
            $files = $upload->getFileInfo();
            foreach($files as $f){
                $archi=$f["name"];
            }

            $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "ipm_".$id."_".$id2."_".$archi,'overwrite' => true));
            $upload->setValidators(array(
                'Size'  => array('min' => 1, 'max' => 26214400),
            ));

            if($upload->isValid()){
                $rtn = array(
                    'success' => null
                );
                $rtn['success'] = $upload->receive();
                
                // obtiene la informacion de las pantallas
                $data = $this->request->getPost();
                $ArchivosinformemForm = new ArchivosinformemForm();
                // adiciona la noticia
                $resultado = $not->updateInformeArchivo($id_informe[0]["id_informe"], $archi);
                
                // redirige a la pantalla de inicio del controlador
                if ($resultado == 1) {
                    $this->flashMessenger()->addMessage("Archivo cargado con éxito.");
                    return $this->redirect()->toUrl($this->getRequest()
                        ->getBaseUrl() . '/application/aprobacionm/index/' . $id2);
                } else {
                    $this->flashMessenger()->addMessage("El archivo no se pudo cargar.");
                    return $this->redirect()->toUrl($this->getRequest()
                        ->getBaseUrl() . '/application/aprobacionm/index/' . $id2);
                }
            }else{
                 $this->flashMessenger()->addMessage("Falla en la carga del archivo. Supera las 25MB de tamaño.");
                    return $this->redirect()->toUrl($this->getRequest()
                        ->getBaseUrl() . '/application/aprobacionm/index/' . $id2);
            }
        } else {
            $ArchivosinformemForm = new ArchivosinformemForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
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
                $pantalla = "aprobacionm";
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
                $view = new ViewModel(array(
                    'form' => $ArchivosinformemForm,
                    'titulo' => "Adjuntar informes y/o productos de la monitoria",
                    'url' => $this->getRequest()->getBaseUrl(),
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
        $u = new Informesm($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id',0);
        $id2 = (int) $this->params()->fromRoute('id2',0);
        
        $data = $u->getIdInforme($id, $id2);
        
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
            $response->setStream(fopen("public/images/uploads/ipm_".$id."_".$id2."_".$filter->filter($fileName), 'r'));
            $response->setStatusCode(200);
            $headers = new \Zend\Http\Headers();
            $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                    ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                    ->addHeaderLine('Content-Length', filesize("public/images/uploads/ipm_".$id."_".$id2."_".$filter->filter($fileName)));
        }       
        
        
        $response->setHeaders($headers);
        return $response;
    }
}