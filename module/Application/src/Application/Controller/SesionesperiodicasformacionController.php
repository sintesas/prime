<?php

namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\SesionesperiodicasformacionForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Sesionesperiodicasformacion;
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

class SesionesperiodicasformacionController extends AbstractActionController
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
    	
    	$SesionesperiodicasformacionForm = new SesionesperiodicasformacionForm();
		$vf = new Agregarvalflex($this->dbAdapter);
		$rolusuario = new Rolesusuario($this->dbAdapter);
		$Sesionesperiodicasformacion = new Sesionesperiodicasformacion($this->dbAdapter);

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
    			$resultado = $Sesionesperiodicasformacion->updateSesionesperiodicasformacion($id2,$data);
				$evento = 'Edición de programación de sesiones periódicas de formación en investigación: ' . $resultado . ' (mgc_sesionesformacion)';
    		}else{
				$resultado = $Sesionesperiodicasformacion->addSesionesperiodicasformacion($data,$id);
				$evento = 'Creación de programación de sesiones periódicas de formación en investigación: ' . $resultado . ' (mgc_sesionesformacion)';
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
		foreach ($vf->getArrayvalores(68) as $uni) {
			$opciones+=array($uni["id_valor_flexible"]=>$uni["descripcion_valor"]);
		}
		$SesionesperiodicasformacionForm->get('id_tipo')->setValueOptions($opciones);
		
		if($id2 != 0){
			$datBase = $Sesionesperiodicasformacion->getSesionesperiodicasformacionById($id2);
			if($datBase!=null){
				$SesionesperiodicasformacionForm->get('id_tipo')->setValue($datBase[0]["id_tipo"]);
				$SesionesperiodicasformacionForm->get('fecha')->setValue($datBase[0]["fecha"]);
				$SesionesperiodicasformacionForm->get('fecha_fin')->setValue($datBase[0]["fecha_fin"]);
				$SesionesperiodicasformacionForm->get('submit')->setValue("Actualizar");
			}
		}
    	
    	$view = new ViewModel(
			array(
				'form'=>$SesionesperiodicasformacionForm,
				'titulo'=>"Programación de sesiones periódicas de formación en investigación",
				'url'=>$this->getRequest()->getBaseUrl(),		
				'id'=>$id,
				'menu'=> $rolusuario->verificarRolusuario($identi)[0]
			)
		);
		return $view;
    }

	public function eliminarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Sesionesperiodicasformacion($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$u->eliminarSesionesperiodicasformacion($id);

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
		$evento = 'Eliminación de programación de sesiones periódicas de formación en investigación : ' . $id . ' (mgc_sesionesformacion)';
		$ad->addAuditoriadet($evento, $resul);

		$this->flashMessenger()->addMessage("Sesión eliminada con éxito.");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicari/index/'.$id2);

	}

	public function delAction(){
		$SesionesperiodicasformacionForm = new SesionesperiodicasformacionForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$view = new ViewModel(
			array(
				'form'=>$SesionesperiodicasformacionForm,
				'titulo'=>"Eliminar registro",
				'url'=>$this->getRequest()->getBaseUrl(),
				'datos'=>$id,
				'id2'=>$id2
			)
		);
		return $view;
	}
}