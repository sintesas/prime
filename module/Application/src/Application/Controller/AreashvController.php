<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarusuario\AreashvForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Areashv;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class AreashvController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     //Cargamos el servicio de autenticacien el constructor
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

		function get_real_ip()
		{
			if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				return $_SERVER["HTTP_CLIENT_IP"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_X_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
				return $_SERVER["HTTP_X_FORWARDED"];
			} elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_FORWARDED"])) {
				return $_SERVER["HTTP_FORWARDED"];
			} else {
				return $_SERVER["REMOTE_ADDR"];
			}
		}

		$au = new Auditoria($this->dbAdapter);
		$ad = new Auditoriadet($this->dbAdapter);
        
		if($this->getRequest()->isPost()){
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Areashv($this->dbAdapter);
			$auth = $this->auth;

			//verifica si esta conectado
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}			

			$rol = new Roles($this->dbAdapter);
			$rolusuario = new Rolesusuario($this->dbAdapter);
		 	$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$dat["id_rol"];
			}

			//obtiene la informacion de las pantallas
			$data = $this->request->getPost();
			$AreashvForm = new AreashvForm();
			//adiciona la noticia
			$id = (int) $this->params()->fromRoute('id',0);
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
						$registro = $not->getAreashvById($id2);
	                    if($registro != null){
	                        unlink("public/images/uploads/hv/areasa/hv_aa__".$id."_".$registro[0]["archivo"]);
	                    }
                    }
                    $upload->setDestination('./public/images/uploads/hv/areasa/');
                    $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "hv_aa__".$id."_".$file_name,'overwrite' => true));
                    $rtn = array('success' => null);
                    $rtn['success'] = $upload->receive();
                }else{
                	$error_file = true;
                }
			}

			if($id2!=0){
				$resultado=$not->updateAreashv($id2, $data, $file_name);
				$evento = 'Edición de área o campo de actuación de conocimiento : ' . $resultado . ' (aps_hv_areas)';
			}else{
				$resultado=$not->addAreashv($data,$id, $file_name);
				$evento = 'Creación de área o campo de actuación de conocimiento : ' . $resultado . ' (aps_hv_areas)';
			}			

			//redirige a la pantalla de inicio del controlador
			if ($resultado==1){
				if($error_file){
					$this->flashMessenger()->addMessage("Area creada sin archivo adjunto. Recuerde que el tamaño máximo del archivo es de 25MB.");
				}else{
					$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
					$ad->addAuditoriadet($evento, $resul);
					$this->flashMessenger()->addMessage("Area creada con exito");
				}
			}else
			{
				$this->flashMessenger()->addMessage("La creacion del area fallo");
			}
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarusuario/index/'.$id);
		}
		else
		{
			$AreashvForm = new AreashvForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Areashv($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
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
			$pantalla="editargrupoinv";
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

			$opciones = array('' => '');
            foreach ($pt->getArrayvalores(66) as $datFlex) {                
                $op = array(
                    $datFlex["id_valor_flexible"] => $datFlex["descripcion_valor"]
                );  
                $opciones = $opciones  + $op;
            }
            $AreashvForm->get('nombre_area')->setValueOptions($opciones);
		
			if(true){
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2', 0);
				if($id2!=0){
					$datBase = $u->getAreashvById($id2);
					if($datBase!=null){
						$AreashvForm->get('nombre_area')->setValue($datBase[0]["nombre_area"]);
						$AreashvForm->get('objeto')->setValue($datBase[0]["objeto"]);
						$AreashvForm->get('submit')->setValue("Actualizar");
					}
				}
			$view = new ViewModel(array('form'=>$AreashvForm,
										'titulo'=>"Área o campo de actuación de conocimiento",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$u->getAreashv($id),
										'id'=>$id,
										'id2'=>$id2,
										'menu'=>$dat["id_rol"]));
			return $view;
			}
			else
			{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
			}
		}
    }

    public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Areashv($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getAreashvById($id2);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/hv/areasa/hv_aa__".$id."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/hv/areasa/hv_aa__".$id."_".$filter->filter($fileName)));
    	$response->setHeaders($headers);
        return $response;
    }
	
	public function eliminarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Areashv($this->dbAdapter);
		//print_r($data);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$u->eliminarAreashv($id);

		function get_real_ip()
		{
			if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				return $_SERVER["HTTP_CLIENT_IP"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_X_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
				return $_SERVER["HTTP_X_FORWARDED"];
			} elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_FORWARDED"])) {
				return $_SERVER["HTTP_FORWARDED"];
			} else {
				return $_SERVER["REMOTE_ADDR"];
			}
		}

		$auth = $this->auth;
		$identi=$auth->getStorage()->read();

		$au = new Auditoria($this->dbAdapter);
		$ad = new Auditoriadet($this->dbAdapter);

		$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
		$evento = 'Eliminación de área o campo de actuación de conocimiento : ' . $id . ' (aps_hv_areas)';
		$ad->addAuditoriadet($evento, $resul);

		$this->flashMessenger()->addMessage("Area eliminada con exito");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarusuario/index/'.$id2);
		
	}

	public function delAction(){
$AreashvForm= new AreashvForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Areashv($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);


			$view = new ViewModel(array('form'=>$AreashvForm,
										'titulo'=>"Eliminar registro",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$id,'id2'=>$id2));
			return $view;

	}
}