<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Creareventos\CreareventosForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Eventos;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class CreareventosController extends AbstractActionController
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
    	
		if($this->getRequest()->isPost()){
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Eventos($this->dbAdapter);
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

			$data = $this->request->getPost();
			$upload = new \Zend\File\Transfer\Adapter\Http();
            $upload->setDestination('./public/images/uploads/');
            $files = $upload->getFileInfo();
            $archi="";
            foreach($files as $f){
                $archi=$f["name"];
            }
            $random = mt_rand(); 
            $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "hi_eve_".$random."_".$archi,'overwrite' => true));
            $upload->setValidators(array(
                'Size'  => array('min' => 1, 'max' => 50000000),
            ));

            if($archi==""){
            	$resultado=$not->addEvento($data, "", "");
                $this->flashMessenger()->addMessage("Evento creado con éxito.");
            }else{
		        if($upload->isValid()){
		        	$rtn = array('success' => null);
		        	$rtn['success'] = $upload->receive();
		            $resultado=$not->addEvento($data, $archi, $random);
		            $this->flashMessenger()->addMessage("Evento creada con éxito.");
		        }else{
		            $this->flashMessenger()->addMessage("Falla en la carga del archivo. Supera las 25MB de tamaño.");
		        }
            }
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/eventos/index');
 		}
		else
		{
			$CreareventosForm = new CreareventosForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Eventos($this->dbAdapter);
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
			$pantalla="creareventos";
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
			$view = new ViewModel(array('form'=>$CreareventosForm,
										'titulo'=>"Crear Eventos",
										'titulo2'=>"Tipos Valores Existentes", 
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$u->getEventos(),
										'menu'=>$dat["id_rol"]));
			return $view;
			}
			else
			{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
			}
		}
    }
	
}