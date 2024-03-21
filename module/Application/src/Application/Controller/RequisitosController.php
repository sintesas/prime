<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\RequisitosForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Requisitos;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class RequisitosController extends AbstractActionController
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

		if($this->getRequest()->isPost()){
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Requisitos($this->dbAdapter);
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
			$RequisitosForm = new RequisitosForm();
			//adiciona la noticia
			$id = (int) $this->params()->fromRoute('id',0);
			$resultado=$not->addRequisitos($data, $id);

			$au = new Auditoria($this->dbAdapter);
            $ad = new Auditoriadet($this->dbAdapter);

			//redirige a la pantalla de inicio del controlador
			if ($resultado==1){
				$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                $evento = 'Creacion de requisito : ' . $resultado . ' (mgc_requisitos)';
                $ad->addAuditoriadet($evento, $resul);
				$this->flashMessenger()->addMessage("Requisito creado con exito");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarconvocatoriai/index/'.$id);
			}else
			{
				$this->flashMessenger()->addMessage("La creacion del Requisito fallo");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/requisitos/index');
			}
		}
		else
		{

			$RequisitosForm = new RequisitosForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Requisitos($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			$filter = new StringTrim();

			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(26) as $uni) {
			$op=array(''=>'');
			$op=$op+array($uni["id_valor_flexible"]=>$uni["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$RequisitosForm->add(array(
			'name' => 'id_tipo_requisito',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Seleccione el tipo de requisito : ',
            'value_options' => 
				$opciones
            ,
			),
			)); 

			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(29) as $uni) {
			$op=array(''=>'');
			$op=$op+array($uni["id_valor_flexible"]=>$uni["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$RequisitosForm->add(array(
			'name' => 'id_tipo_ponderacion',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Seleccione el tipo de ponderacion : ',
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
			$pantalla="Requisitos";
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
			$view = new ViewModel(array('form'=>$RequisitosForm,
										'titulo'=>"Requisitos de la Convocatoria",
										'url'=>$this->getRequest()->getBaseUrl(),
										'id'=>$id,
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
		$u = new Requisitos($this->dbAdapter);

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

		//print_r($data);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$u->eliminarRequisitos($id);
		$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
        $evento = 'Eliminación de requisito : ' . $id . ' (mgc_requisitos)';
        $ad->addAuditoriadet($evento, $resul);
		
		$this->flashMessenger()->addMessage("Requisito eliminado con exito");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarconvocatoriai/index/'.$id2);			
	}

	public function delAction(){
		$RequisitosForm= new RequisitosForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Requisitos($this->dbAdapter);
		//print_r($data);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);


		$view = new ViewModel(array('form'=>$RequisitosForm,
									'titulo'=>"Eliminar registro",
									'url'=>$this->getRequest()->getBaseUrl(),
									'datos'=>$id,'id2'=>$id2));
		return $view;

	}
}