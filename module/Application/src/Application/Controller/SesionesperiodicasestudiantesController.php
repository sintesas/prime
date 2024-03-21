<?php

namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\SesionesperiodicasestudiantesForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Sesionesperiodicasestudiantes;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Semillero;

class SesionesperiodicasestudiantesController extends AbstractActionController
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
    
    	$SesionesperiodicasestudiantesForm = new SesionesperiodicasestudiantesForm();
		$vf = new Agregarvalflex($this->dbAdapter);
		$rolusuario = new Rolesusuario($this->dbAdapter);
		$Sesionesperiodicasestudiantes = new Sesionesperiodicasestudiantes($this->dbAdapter);

		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2', 0);

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

		$identi=$auth->getStorage()->read();

		$au = new Auditoria($this->dbAdapter);
		$ad = new Auditoriadet($this->dbAdapter);
		
    	if($this->getRequest()->isPost()){
    		$data = $this->request->getPost();
    		if($id2 != 0){
    			$resultado = $Sesionesperiodicasestudiantes->updateSesionesperiodicasestudiantes($id2,$data);
				$evento = 'Edición de programación de sesiones periódicas de avances de investigación: ' . $resultado . ' (mgc_sesionesestudiantes)';
    		}else{
				$resultado = $Sesionesperiodicasestudiantes->addSesionesperiodicasestudiantes($data,$id);
				$evento = 'Creación de programación de sesiones periódicas de avances de investigación: ' . $resultado . ' (mgc_sesionesestudiantes)';
			}

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
		$SesionesperiodicasestudiantesForm->get('id_rol')->setValueOptions($opciones);
		
		if($id2 != 0){
			$datBase = $Sesionesperiodicasestudiantes->getSesionesperiodicasestudiantesById($id2);
			if($datBase!=null){
				$SesionesperiodicasestudiantesForm->get('sesion')->setValue($datBase[0]["sesion"]);
				$SesionesperiodicasestudiantesForm->get('fecha')->setValue($datBase[0]["fecha"]);
				$SesionesperiodicasestudiantesForm->get('fecha_fin')->setValue($datBase[0]["fecha_fin"]);
				$SesionesperiodicasestudiantesForm->get('id_rol')->setValue($datBase[0]["id_rol"]);
				$SesionesperiodicasestudiantesForm->get('submit')->setValue("Actualizar");
			}
		}
    	
    	$view = new ViewModel(
			array(
				'form'=>$SesionesperiodicasestudiantesForm,
				'titulo'=>"Programación de sesiones periódicas de avances de investigación",
				'url'=>$this->getRequest()->getBaseUrl(),		
				'id'=>$id,
				'menu'=> $rolusuario->verificarRolusuario($identi)[0]
			)
		);
		return $view;
    }

	public function eliminarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Sesionesperiodicasestudiantes($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$u->eliminarSesionesperiodicasestudiantes($id);

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
		$evento = 'Eliminación de programación de sesiones periódicas de avances de investigación : ' . $id . ' (mgc_sesionesestudiantes)';
		$ad->addAuditoriadet($evento, $resul);

		$this->flashMessenger()->addMessage("Sesión eliminada con éxito.");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicari/index/'.$id2);

	}

	public function delAction(){
		$SesionesperiodicasestudiantesForm = new SesionesperiodicasestudiantesForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$view = new ViewModel(
			array(
				'form'=>$SesionesperiodicasestudiantesForm,
				'titulo'=>"Eliminar registro",
				'url'=>$this->getRequest()->getBaseUrl(),
				'datos'=>$id,
				'id2'=>$id2
			)
		);
		return $view;
	}
}