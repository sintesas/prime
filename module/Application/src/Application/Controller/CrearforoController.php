<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Crearforo\CrearforoForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Foro;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class CrearforoController extends AbstractActionController
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

    	function get_real_ip(){
		    if (isset($_SERVER["HTTP_CLIENT_IP"])){
		        return $_SERVER["HTTP_CLIENT_IP"];
		    }
		    elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
		        return $_SERVER["HTTP_X_FORWARDED_FOR"];
		    }
		    elseif (isset($_SERVER["HTTP_X_FORWARDED"])){
		        return $_SERVER["HTTP_X_FORWARDED"];
		    }
		    elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])){
		        return $_SERVER["HTTP_FORWARDED_FOR"];
		    }
		    elseif (isset($_SERVER["HTTP_FORWARDED"])){
		        return $_SERVER["HTTP_FORWARDED"];
		    }
		    else{
		        return $_SERVER["REMOTE_ADDR"];
		    }
		}

		if($this->getRequest()->isPost()){
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Foro($this->dbAdapter);
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

	       	$upload = new \Zend\File\Transfer\Transfer();
			$upload->setDestination('./public/images/uploads/');
			$upload->setValidators(array(
			    'Size'  => array('min' => 1, 'max' => 50000000),
			));
		   	$rtn = array('success' => null);
		   	$rtn['success'] = $upload->receive();

			$files = $upload->getFileInfo();
			//echo $files;
			foreach($files as $f){
				$archi=$f["name"];
			}

			$au = new Auditoria($this->dbAdapter);
			$ad = new Auditoriadet($this->dbAdapter);	

			//obtiene la informacion de las pantallas
			$data = $this->request->getPost();
			$CrearforoForm = new CrearforoForm();
			//adiciona la noticia
			$resultado=$not->addForo($data, $archi, $identi->id_usuario);

			//redirige a la pantalla de inicio del controlador
			if ($resultado!=''){
				$resul=$au->getAuditoriaid(session_id(),get_real_ip(),$identi->id_usuario);
				$evento='Creacion de foro : '.$resultado;
				$ad->addAuditoriadet( $evento,$resul);  
				$this->flashMessenger()->addMessage("Tema Creado con exito ".date("d") . "/" . date("m") . "/" . date("Y")." ".date("h").":".date("i"));
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/foro/index');
			}else
			{
				$this->flashMessenger()->addMessage("La creacion del tema fallo");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/foro/index');
			}
		}
		else
		{
			$CrearforoForm = new CrearforoForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Foro($this->dbAdapter);
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
				$pantalla="crearforo";
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
				$view = new ViewModel(array('form'=>$CrearforoForm,
											'titulo'=>"Crear Foro",
											'titulo2'=>"Tipos Valores Existentes", 
											'url'=>$this->getRequest()->getBaseUrl(),
											'datos'=>$u->getForo(),
											'menu'=>$dat["id_rol"]));
				return $view;
			}
			else			{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
			}
		}
    }
	
    public function ResponderAction()
    {
		if($this->getRequest()->isPost()){
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Foro($this->dbAdapter);
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

        $upload = new \Zend\File\Transfer\Transfer();
        $upload->setDestination('./data/uploads/');

     $rtn = array('success' => null);

        if ($this->getRequest()->isPost()) {
            $files = $upload->getFileInfo();
            foreach ($files as $file => $info) {
                if (!$upload->isUploaded($file)) {
                    print "<h3>Not Uploaded</h3>";
                    Debug::dump($file);
                    continue;
                }
                if (!$upload->isValid($file)) {
                    print "<h4>Not Valid</h4>";
                    Debug::dump($file);
                    continue;
                }
            }

            $rtn['success'] = $upload->receive();
        }

$files = $upload->getFileInfo();
foreach($files as $f){
$archi=$f["name"];
}




			//obtiene la informacion de las pantallas
			$data = $this->request->getPost();
			$CreaforoForm = new CrearforoForm();
			//adiciona la noticia
			$id = (int) $this->params()->fromRoute('id',0);
			$resultado=$not->addRespuesta($data, $archi, $identi->id_usuario,$id);



			//redirige a la pantalla de inicio del controlador
			if ($resultado==1){
				$this->flashMessenger()->addMessage("Tema Creado con exito");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/foro/index');
			}else
			{
				$this->flashMessenger()->addMessage("La creacion del tema fallo");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/foro/index');
			}
		}
		else
		{
			$CrearforoForm = new CrearforoForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Foro($this->dbAdapter);
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
			$pantalla="foro";
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
			$view = new ViewModel(array('form'=>$CrearforoForm,
										'titulo'=>"Crear Foro",
										'titulo2'=>"Tipos Valores Existentes", 
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$u->getForo(),
										'menu'=>$dat["id_rol"]));
			return $view;
			}
			else
			{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
			}
		}
    }
	public function bajarAction(){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Noticias($this->dbAdapter);
$filter = new StringTrim();
$id = (int) $this->params()->fromRoute('id');

$data=$u->getNoticiasid($id);

foreach($data as $d){
$d["archivo"];
}



			$fileName = $d["archivo"];


    $response = new \Zend\Http\Response\Stream();
    $response->setStream(fopen("public/images/uploads/".$filter->filter($fileName), 'r'));
    $response->setStatusCode(200);


    $headers = new \Zend\Http\Headers();
    $headers->addHeaderLine('Content-Type', 'whatever your content type is')
            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
            ->addHeaderLine('Content-Length', filesize("public/images/uploads/".$filter->filter($fileName)));



    $response->setHeaders($headers);
    return $response;
	}

}