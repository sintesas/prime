<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Solicitudes\SolicitudesForm;
use Application\Solicitudes\SolconvocatoriaForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Solicitudes;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Filter\StringTrim;

use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class SolicitudesController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     //Cargamos el servicio de autenticación en el constructor
     $this->auth = new AuthenticationService();
	}
	
    public function indexAction()
    {
    	# Para pantallas accesadas por el menu, debo reiniciar el navegador
        session_start();
        if(isset($_SESSION['navegador'])){unset($_SESSION['navegador']);}
        
        $this->dbAdapter=$this->getServiceLocator()->get('db1');
        $auth = $this->auth;
        $permisos = new Permisos($this->dbAdapter);
        $identi = print_r($auth->getStorage()->read()->id_usuario,true);
        $resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,get_class());
        if($resultadoPermiso==0){
            $this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
        }
        
		if($this->getRequest()->isPost()){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Solicitudes($this->dbAdapter);
$ua = new Usuarios($this->dbAdapter);
$vf = new Agregarvalflex($this->dbAdapter);
			$data = $this->request->getPost();
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
       	$upload = new \Zend\File\Transfer\Transfer();
       	$upload->setDestination('./public/images/uploads/');
$upload->setValidators(array(
    'Size'  => array('min' => 1, 'max' => 50000000),
));
       	$rtn = array('success' => null);
       	$rtn['success'] = $upload->receive();
        

	$files = $upload->getFileInfo();
		foreach($files as $f){
			$archi=$f["name"];
		}

			$au = new Auditoria($this->dbAdapter);
			$ad = new Auditoriadet($this->dbAdapter);		  
function get_real_ip()
    {
        if (isset($_SERVER["HTTP_CLIENT_IP"]))
        {
            return $_SERVER["HTTP_CLIENT_IP"];
        }
        elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
        {
            return $_SERVER["HTTP_X_FORWARDED"];
        }
        elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
        {
            return $_SERVER["HTTP_FORWARDED_FOR"];
        }
        elseif (isset($_SERVER["HTTP_FORWARDED"]))
        {
            return $_SERVER["HTTP_FORWARDED"];
        }
        else
        {
            return $_SERVER["REMOTE_ADDR"];
        }
    }

			//print_r($data);
$usua=$ua->getArrayusuariosid($identi->id_usuario);

			$resultado=$u->addSolconvocatoria($data, $identi->id_usuario, $archi, $usua["email"],$vf->getValoresf());
			//exit;
if($resultado!=''){
	$resul=$au->getAuditoriaid(session_id(),get_real_ip(),$identi->id_usuario);
				$evento='Creacion de solicitud : '.$resultado;
				$ad->addAuditoriadet( $evento,$resul); 
$this->flashMessenger()->addMessage("Solicitud creada con exito");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/solicitudes/index');
}
		}else{

	    $this->dbAdapter=$this->getServiceLocator()->get('db1');
	    $u = new Solicitudes($this->dbAdapter);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
        $SolicitudesForm = new SolicitudesForm();

			$filter = new StringTrim();

			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(43) as $uni) {
			$op=array(''=>'');
			$op=$op+array($uni["id_valor_flexible"]=>$uni["descripcion_valor"]);
			$opciones=$opciones+$op;
			}

			$SolicitudesForm ->add(array(
			'name' => 'id_tipo_sol',
			'type' => 'Zend\Form\Element\Select',
'disabled' => 'disabled',
			'options' => array(
            'label' => 'Tipo de solicitud : ',
            'value_options' => 
				$opciones
            ,
			),
			)); 


		//verificar roles
			//verificar roles
			$per=array('id_rol'=>'');
			$dat=array('id_rol'=>'');
$permiso_1='';
$roles_1='';
			$rolusuario = new Rolesusuario($this->dbAdapter);
			$permiso = new Permisos($this->dbAdapter);
		
			//me verifica el tipo de rol asignado al usuario
			$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$roles_1=$dat["id_rol"];
			}
		
			if ($roles_1!= ''){
			//me verifica el permiso sobre la pantalla
			$pantalla="index";
			$panta=0;
			$pt = new Agregarvalflex($this->dbAdapter);


			foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
			$panta["id_valor_flexible"];
			}

			$permiso->verificarPermiso($roles_1,$panta["id_valor_flexible"]);
			foreach ($permiso->verificarPermiso($roles_1,$panta["id_valor_flexible"]) as $per) {
				$permiso_1=$per["id_rol"];
			}
			}


			if(true){
$valflex = new Agregarvalflex($this->dbAdapter);
$users = new Usuarios($this->dbAdapter);
		$view = new ViewModel(array('form'=>$SolicitudesForm,
									'titulo'=>"Solicitudes",
									'datos'=>$u->getSolicitudesmias($identi->id_usuario),
									'usuarios'=>$users->getArrayusuarios(),
									'valflex'=>$valflex->getValoresf(),
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
			$u = new Solicitudes($this->dbAdapter);
$filter = new StringTrim();
$id = (int) $this->params()->fromRoute('id');

$data=$u->getSolicitudesid($id);

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

	public function bajar2Action(){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Solicitudes($this->dbAdapter);
$filter = new StringTrim();
$id = (int) $this->params()->fromRoute('id');

$data=$u->getSolicitudesid($id);

foreach($data as $d){
$d["archivo_res"];
}



			$fileName = $d["archivo_res"];

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
