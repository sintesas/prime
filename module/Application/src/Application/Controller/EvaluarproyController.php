<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\EvaluarproyForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Asignareval;
use Application\Modelo\Entity\Evaluar;
use Application\Modelo\Entity\Aplicar;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Agregarvalfelx;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Convocatoria;

class EvaluarproyController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     		//Cargamos el servicio de autenticacien el constructor
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

					$EvaluarproyForm = new EvaluarproyForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
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
			$pantalla="evaluarproy";
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

			$usua = new Usuarios($this->dbAdapter);
			$valflex = new Agregarvalflex($this->dbAdapter);
			$d='';
$asignar = new Asignareval($this->dbAdapter);
$eval = new Evaluar($this->dbAdapter);
$aplicar = new Aplicar($this->dbAdapter);
$convo = new Convocatoria($this->dbAdapter);
			$view = new ViewModel(array('form'=>$EvaluarproyForm,
										'titulo'=>"Evaluar propuesta de investigación",
										'titulo2'=>"Tipos Valores Existentes", 
										'url'=>$this->getRequest()->getBaseUrl(),
										'asignar'=>$asignar->getAsignarevalt(),
										'aplicar'=>$aplicar->getAplicarh(),
										'eval'=>$eval->getEvaluar(),
										'd_user'=>$usua->getUsuarios($d),
										'd_val'=>$valflex->getValoresf(),
										'id_user'=>$identi->id_usuario,
										'convo' => $convo->getConvocatoria(),
										'menu'=>$dat["id_rol"]));
			return $view;
			
		
    }


}