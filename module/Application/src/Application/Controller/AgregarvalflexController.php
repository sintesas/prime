<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Agregarvalflex\AgregarvalflexForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Tipovalores;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class AgregarvalflexController extends AbstractActionController
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
        
	    $id = (int) $this->params()->fromRoute('id',0);
		if($this->getRequest()->isPost()){
			$AgregarvalflexForm = new AgregarvalflexForm();
			$id = (int) $this->params()->fromRoute('id',0);
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Agregarvalflex($this->dbAdapter);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
			$data = $this->request->getPost();

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

			$resultado=$u->addValoresflex($data,$id,$u->getValoresf());
			if($resultado==0){
			$resultado=$au->getAuditoriaid(session_id(),get_real_ip(),$identi->id_usuario);
			$p=implode(',',  (array)$data);
			$evento='Intento fallido de ingreso valor flexible : '.$p;
			$ad->addAuditoriadet( $evento,$resultado);     

			$this->flashMessenger()->addMessage("El valor ingresado en la descripcion esta duplicado");
			$urlId = "/application/Agregarvalflex/index/".$id;
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlId);
			}else{

			$resultado=$au->getAuditoriaid(session_id(),get_real_ip(),$identi->id_usuario);
			$p=implode(',',  (array)$data);
			$evento='Creacion de valor flexible : '.$p;
			$ad->addAuditoriadet( $evento,$resultado);     

			$this->flashMessenger()->addMessage("Registro Ingresado");
			$urlId = "/application/Agregarvalflex/index/".$id;
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlId);
			}

		}else
		{
        $AgregarvalflexForm = new AgregarvalflexForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Agregarvalflex($this->dbAdapter);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
			$tp = new Tipovalores($this->dbAdapter);
			foreach ($tp->getTipdefeditar($id) as $tip) {
				$tip["id_tipo_valor_padre"];
			}
			
		
			//define el campo valorpadre
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array('0'=>'Seleccione el padre');
			foreach ($vf->getArrayvalores($tip["id_tipo_valor_padre"]) as $dat) {
			$op=array($dat["id_valor_flexible"]=>$dat["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$AgregarvalflexForm->add(array(
			'name' => 'valor_flexible_padre_id',
			'type' => 'Zend\Form\Element\Select',

			'options' => array(
            'label' => 'Valor Padre: ',
            'value_options' => 
				$opciones
            ,
			),
			'attributes' => array(
                'value' => '0' //set selected to '1'
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
			$pantalla="agregarvalflex";
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
		$view = new ViewModel(array('form'=>$AgregarvalflexForm,
									'titulo'=>"Valores Flexibles",
									'titulo2'=>"Valores Flexibles Existentes", 
									'id'=>$id,
									'url'=>$this->getRequest()->getBaseUrl(),
									'datos'=>$u->getValoresflex($id),
									'datos2'=>$u->getValoresf(),
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
			$u = new Agregarvalflex($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$u->eliminarValoresflex($id);	
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/tipovalores/index');
	}
}
