<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\PropuestainvForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Propuestainv;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class PropuestainvController extends AbstractActionController
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
			$not = new Propuestainv($this->dbAdapter);
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
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$id3 = (int) $this->params()->fromRoute('id3',0);
			//obtiene la informacion de las pantallas
			$data = $this->request->getPost();

			if($id3!=0){
				$resultado=$not->updatePropuestainv($id3, $data);
				$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
				$evento = 'Edición de anexos : ' . $resultado . ' (mgc_propuesta_inv)';
				$ad->addAuditoriadet($evento, $resul);
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicari/index/'.$id);
			}

			/* */
                $upload = new \Zend\File\Transfer\Adapter\Http();
                $upload->setDestination('./public/images/uploads/');
                $files = $upload->getFileInfo();
                foreach($files as $f){
                    $archi=$f["name"];
                }

                $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "pi_api_".$id."_".$archi,'overwrite' => true));
                $upload->setValidators(array(
                    'Size'  => array('min' => 1, 'max' => 50000000),
                ));
                
                if($upload->isValid()){
                    $rtn = array('success' => null);
                    $rtn['success'] = $upload->receive();
                         
                    $conv = new Convocatoria($this->dbAdapter);
					$apli = new Aplicarm($this->dbAdapter);
					$id_convocatoria=$apli->getID($id);
					$tipo=$conv->getConvocatoriaid($id_convocatoria["id_convocatoria"]);
					$resultado=$not->addArchivos($data,$id,$archi);

					$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
					$evento = 'Creación de anexos : ' . $resultado . ' (mgc_propuesta_inv)';
					$ad->addAuditoriadet($evento, $resul);

                    $this->flashMessenger()->addMessage("Archivo creado con éxito.");
                    if($tipo["tipo_conv"]=='m'){
						return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicarm/index/'.$id);
					}elseif($tipo["tipo_conv"]=='i'){
						return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicari/index/'.$id);
					}else{
						if($id2!=0){
							return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicari/index/'.$id);
						}else{
							return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicar/index/'.$id);
						}
					}
                }else{
                    $this->flashMessenger()->addMessage("Falla en la carga del archivo. Supera las 25MB de tamaño.");
                    if($tipo["tipo_conv"]=='m'){
						return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicarm/index/'.$id);
					}else{
						return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicar/index/'.$id);
					}
                }
            /* */
		}
		else
		{
			$PropuestainvForm= new PropuestainvForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Propuestainv($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			$filter = new StringTrim();


			//define el campo ciudad
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(24) as $dept) {
			$op=array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$PropuestainvForm->add(array(
			'name' => 'id_tipo_archivo',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Tipo de Archivo : ',
            'value_options' => 
				$opciones
            ,
			),
			)); 



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
		
			if(true){
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$id3 = (int) $this->params()->fromRoute('id3',0);

			if($id3!=0){
				$linea = new Propuestainv($this->dbAdapter);
				$datBase = $linea->getArchivosid($id3);
				if($datBase!=null){
					$PropuestainvForm->get('nombre')->setValue($datBase[0]["nombre"]);
					$PropuestainvForm->get('descripcion')->setValue($datBase[0]["descripcion"]);
					$PropuestainvForm->get('submit')->setValue("Actualizar");
				}
			}
			$view = new ViewModel(array('form'=>$PropuestainvForm,
										'titulo'=>"Archivos de Investigación Propuesta",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$u->getArchivos($id),
										'id'=>$id,
										'id2'=>$id2,
										'id3'=>$id3,
										'menu'=>$dat["id_rol"]));
			return $view;
			}
			else
			{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
			}
		}
    }
	

	public function eliminarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Propuestainv($this->dbAdapter);
		//print_r($data);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$id3 = (int) $this->params()->fromRoute('id3',0);
		$u->eliminarArchivos($id);

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
		$evento = 'Eliminación de anexo : ' . $id . ' (mgc_propuesta_inv)';
		$ad->addAuditoriadet($evento, $resul);

		$this->flashMessenger()->addMessage("Archivo eliminado con exito");
		if($id3!=0){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicari/index/'.$id2);
		}else{
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicar/index/'.$id2);
		}
			
	}

	public function delAction(){
$PropuestainvForm = new PropuestainvForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Propuestainv($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$id3 = (int) $this->params()->fromRoute('id3',0);


			$view = new ViewModel(array('form'=>$PropuestainvForm,
										'titulo'=>"Eliminar registro",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$id,'id2'=>$id2,'id3'=>$id3));
			return $view;

	}

	public function bajarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Propuestainv($this->dbAdapter);
		$filter = new StringTrim();
		$id = (int) $this->params()->fromRoute('id');
		$id2 = (int) $this->params()->fromRoute('id2', 0);

		$data=$u->getArchivosid($id);

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
            $response->setStream(fopen("public/images/uploads/pi_api_".$id2."_".$filter->filter($fileName), 'r'));
            $response->setStatusCode(200);
            $headers = new \Zend\Http\Headers();
            $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                    ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                    ->addHeaderLine('Content-Length', filesize("public/images/uploads/pi_api_".$id2."_".$filter->filter($fileName)));
        }
        $response->setHeaders($headers);
        return $response;
	}
}