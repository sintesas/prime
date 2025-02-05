<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\ConvocatoriamForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Convocatoria;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class ConvocatoriamController extends AbstractActionController
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
			$not = new Convocatoria($this->dbAdapter);
			$auth = $this->auth;

			//verifica si esta conectado
			$identi=$auth->getStorage()->read();

			

			$rol = new Roles($this->dbAdapter);
			$rolusuario = new Rolesusuario($this->dbAdapter);
		 	$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$dat["id_rol"];
			}


			//obtiene la informacion de las pantallas
			$data = $this->request->getPost();
			$ConvocatoriamForm = new ConvocatoriamForm();
			//adiciona la noticia
			$id = (int) $this->params()->fromRoute('id',0);
			$resultado=$not->addConvocatoria($data,'m');


			$au = new Auditoria($this->dbAdapter);
			$ad = new Auditoriadet($this->dbAdapter);

			//redirige a la pantalla de inicio del controlador
			//redirige a la pantalla de inicio del controlador
			if ($resultado!=null){
				$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                $evento = 'Creación de convocatoria monitores : ' . $resultado;
                $ad->addAuditoriadet($evento, $resul);

				$this->flashMessenger()->addMessage("Convocatoria creada con exito");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarconvocatoriai/index/'.$resultado);
			}else
			{
				$this->flashMessenger()->addMessage("La creacion de la convocatoria fallo");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarconvocastoriai/index');
			}
		}
		else
		{

			$ConvocatoriamForm = new ConvocatoriamForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Convocatoria($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			

			$filter = new StringTrim();

			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(30) as $uni) {
			$op=array(''=>'');
			$op=$op+array($uni["id_valor_flexible"]=>$uni["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$ConvocatoriamForm->add(array(
			'name' => 'id_proyectos',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Proyectos de Investigacion : ',
            'value_options' => 
				$opciones
            ,
			),
			)); 

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
			$pantalla="convocatoriam";
			$panta=0;
			$pt = new Agregarvalflex($this->dbAdapter);


			foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
			$panta["id_valor_flexible"];
			}

			$permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]);
			foreach ($permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]) as $per) {
				$permiso_1==$per["id_rol"];
			}
			}
		


			if(true){
			$id = (int) $this->params()->fromRoute('id',0);
			$view = new ViewModel(array('form'=>$ConvocatoriamForm,
										'titulo'=>"Convocatoria Monitores",
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



}