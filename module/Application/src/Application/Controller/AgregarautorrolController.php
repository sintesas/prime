<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarredinv\AgregarautorrolForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Agregarautorred;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;

class AgregarautorrolController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     //Cargamos el servicio de autenticacien el constructor
     $this->auth = new AuthenticationService();
	}
	
    public function indexAction()
    {
		if($this->getRequest()->isPost()){
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Agregarautorred($this->dbAdapter);
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
			$AgregarautorrolForm = new AgregarautorrolForm();
			//adiciona la noticia
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$id3 = (int) $this->params()->fromRoute('id3',0);
			$id4 = (int) $this->params()->fromRoute('id4',0);

			$resultado=$not->addAgregarautorrol($id,$id2,$id3,$id4,$data["id_rol"]);
			
			//redirige a la pantalla de inicio del controlador
			if ($resultado==1){
				$this->flashMessenger()->addMessage("Se agregó el autor con éxito.");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarredinv/index/'.$id3);
			}else{
				$this->flashMessenger()->addMessage("No se agregó el autor con éxito.");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarredinv/index/'.$id3);
			}
		}
		else
		{
			$AgregarautorrolForm= new AgregarautorrolForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
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
			foreach ($vf->getArrayvalores(39) as $pa) {
			$op=array($pa["id_valor_flexible"]=>$pa["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$AgregarautorrolForm->add(array(
			'name' => 'id_rol',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Rol : ',
            'value_options' => 
				$opciones,
			),
			)); 

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
			$pantalla="Editarredinv";
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
			$id3 = (int) $this->params()->fromRoute('id3');
			$id4 = (int) $this->params()->fromRoute('id4');
			$view = new ViewModel(
				array(
					'form'=>$AgregarautorrolForm,
					'titulo'=>"Asignar rol al usuario",
					'url'=>$this->getRequest()->getBaseUrl(),
					'id'=>$id,
					'id2'=>$id2,
					'id3'=>$id3,
					'id4'=>$id4,
					'menu'=>$dat["id_rol"]
				));
			return $view;
			}
			else
			{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
			}
		}
    }



}