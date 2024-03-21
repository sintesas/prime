<?php

namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\AgregarresponsableForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Agregarresponsable;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Semillero;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class AgregarresponsableController extends AbstractActionController
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
    	
    	$AgregarresponsableForm = new AgregarresponsableForm();
		$vf = new Agregarvalflex($this->dbAdapter);
		$rolusuario = new Rolesusuario($this->dbAdapter);
		$Agregarresponsable = new Agregarresponsable($this->dbAdapter);

		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2', 0);
		$id3 = $this->params()->fromRoute('id3', "");
		$id4 = (int) $this->params()->fromRoute('id4', 0);

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

		$identi = $auth->getStorage()->read();

		$au = new Auditoria($this->dbAdapter);
		$ad = new Auditoriadet($this->dbAdapter);

    	if($this->getRequest()->isPost()){
    		$data = $this->request->getPost();
    		if($id4 != 0){
    			$resultado = $Agregarresponsable->updateAgregarresponsable($id4,$data);
				$evento = 'Edición de responsable : ' . $resultado . ' (mgc_responsablesap)';
    		}else{
				$resultado = $Agregarresponsable->addAgregarresponsable($data,$id,$id2,$id3);
				$evento = 'Creación de responsable : ' . $resultado . ' (mgc_responsablesap)';
			}

			//redirige a la pantalla de inicio del controlador
			if ($resultado==1){
				$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
				$ad->addAuditoriadet($evento, $resul);
				
				$this->flashMessenger()->addMessage("Rol agregado con éxito.");
			}else{
				$this->flashMessenger()->addMessage("El rol no pudo ser agregado.");				
			}
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicari/index/'.$id);
    	}

		$opciones=array();
		foreach ($vf->getArrayvalores(55) as $uni) {
			$opciones+=array($uni["id_valor_flexible"]=>$uni["descripcion_valor"]);
		}
		$AgregarresponsableForm->get('id_rol')->setValueOptions($opciones);
		
		if($id4 != 0){
			$datBase = $Agregarresponsable->getAgregarresponsableById($id4);
			if($datBase!=null){
				$AgregarresponsableForm->get('id_rol')->setValue($datBase[0]["id_rol"]);
				$AgregarresponsableForm->get('submit')->setValue("Actualizar");
			}
		}
    	
    	$view = new ViewModel(
			array(
				'form'=>$AgregarresponsableForm,
				'titulo'=>"Agregar rol responsable",
				'url'=>$this->getRequest()->getBaseUrl(),		
				'id'=>$id,
				'menu'=> $rolusuario->verificarRolusuario($identi)[0]
			)
		);
		return $view;
    }
}