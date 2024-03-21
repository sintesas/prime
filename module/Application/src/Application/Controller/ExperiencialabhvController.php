<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarusuario\ExperiencialabhvForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Experiencialabhv;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class ExperiencialabhvController extends AbstractActionController
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
			$not = new Experiencialabhv($this->dbAdapter);
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
			$ExperiencialabhvForm = new ExperiencialabhvForm();
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
						$registro = $not->getExperiencialabhvById($id2);
	                    if($registro != null){
	                        unlink("public/images/uploads/hv/experienciap/hv_ep__".$id."_".$registro[0]["archivo"]);
	                    }
                    }
                    $upload->setDestination('./public/images/uploads/hv/experienciap/');
                    $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "hv_ep__".$id."_".$file_name,'overwrite' => true));
                    $rtn = array('success' => null);
                    $rtn['success'] = $upload->receive();
                }else{
                	$error_file = true;
                }
			}

			if($id2!=0){
				$resultado=$not->updateExperiencialabhv($id2, $data, $file_name);
				$evento = 'Edición de experiencia laboral : ' . $resultado . ' (aps_experiencia_prof)';
			}else{
				$resultado=$not->addExperiencialabhv($data, $id, $file_name);
				$evento = 'Creación de experiencia laboral : ' . $resultado . ' (aps_experiencia_prof)';
			}
		
			//redirige a la pantalla de inicio del controlador
			if ($resultado==1){
				if($error_file){
					$this->flashMessenger()->addMessage("Experiencia creada sin archivo adjunto. Recuerde que el tamaño máximo del archivo es de 25MB.");
				}else{
					$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);					
					$ad->addAuditoriadet($evento, $resul);
					
					$this->flashMessenger()->addMessage("Experiencia laboral creada con exito");
				}
			}else
			{
				$this->flashMessenger()->addMessage("La creacion de la experiencia laboral fallo");
			}
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarusuario/index/'.$id);

		}
		else
		{
			$ExperiencialabhvForm = new ExperiencialabhvForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Experiencialabhv($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			$filter = new StringTrim();


			//define el campo pais
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(20) as $pa) {
			$op=array($pa["id_valor_flexible"]=>$pa["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$ExperiencialabhvForm->add(array(
			'name' => 'id_pais',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'País : ',
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
			$pantalla="editarusuario";
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
			$id2 = (int) $this->params()->fromRoute('id2', 0);
				if($id2!=0){
					$datBase = $u->getExperiencialabhvById($id2);
					if($datBase!=null){
						$ExperiencialabhvForm->get('empresa')->setValue($datBase[0]["empresa"]);
						$ExperiencialabhvForm->get('tipo_vinculacion')->setValue($datBase[0]["tipo_vinculacion"]);
						$ExperiencialabhvForm->get('dedicacion_horaria')->setValue($datBase[0]["dedicacion_horaria"]);
						$ExperiencialabhvForm->get('fecha_inicio')->setValue($datBase[0]["fecha_inicio"]);
						$ExperiencialabhvForm->get('fecha_fin')->setValue($datBase[0]["fecha_fin"]);
						$ExperiencialabhvForm->get('periodo_vinculacion')->setValue($datBase[0]["periodo_vinculacion"]);
						$ExperiencialabhvForm->get('cargo')->setValue($datBase[0]["cargo"]);
						$ExperiencialabhvForm->get('descripcion_actividades')->setValue($datBase[0]["descripcion_actividades"]);
						$ExperiencialabhvForm->get('otra_info')->setValue($datBase[0]["otra_info"]);
						$ExperiencialabhvForm->get('submit')->setValue("Actualizar");
					}
				}
			$view = new ViewModel(array('form'=>$ExperiencialabhvForm,
										'titulo'=>"Experiencia Laboral",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$u->getExperiencialabhv($id),
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
        $u = new Experiencialabhv($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getExperiencialabhvById($id2);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/hv/experienciap/hv_ep__".$id."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/hv/experienciap/hv_ep__".$id."_".$filter->filter($fileName)));
    	$response->setHeaders($headers);
        return $response;
    }
	
	public function eliminarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Experiencialabhv($this->dbAdapter);
		//print_r($data);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$u->eliminarExperiencialabhv($id);

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
		$evento = 'Eliminación de experiencia laboral : ' . $id . ' (aps_experiencia_prof)';
		$ad->addAuditoriadet($evento, $resul);

		$this->flashMessenger()->addMessage("Experiencia laboral eliminada con exito");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarusuario/index/'.$id2);
		
	}

	public function delAction(){
$ExperiencialabhvForm= new ExperiencialabhvForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Experiencialabhv($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);


			$view = new ViewModel(array('form'=>$ExperiencialabhvForm,
										'titulo'=>"Eliminar registro",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$id,'id2'=>$id2));
			return $view;

	}
}