<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\EvaluarForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Evaluar;
use Application\Modelo\Entity\Aspectoeval;
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

class EvaluarController extends AbstractActionController
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

$this->auth = new AuthenticationService();
			$auth = $this->auth;
$identi= $auth->getStorage()->read();

			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Evaluar($this->dbAdapter);

			//verifica si esta conectado
			

		$identi->id_usuario; 
		$rol = new Roles($this->dbAdapter);
		$rolusuario = new Rolesusuario($this->dbAdapter);
		 //$Usuarioform = new UsuarioForm();
		$rolusuario->verificarRolusuario($identi->id_usuario);
		foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
			$dat["id_rol"];
		}

			//obtiene la informacion de las pantallas
			//$data = $this->request->getPost();
			$GrupoinvForm = new GrupoinvForm();
			//adiciona la noticia
			$view = new ViewModel(array('form'=>$EvaluarForm,
						'titulo'=>"Grupos de Investigación", 
						'datos'=>$not->filtroGrupos($data),
						'url'=>$this->getRequest()->getBaseUrl(),
						'menu'=>$dat["id_rol"]));
			return $view;
		}
		else
		{

$this->auth = new AuthenticationService();
			$auth = $this->auth;
$identi= $auth->getStorage()->read();
			$EvaluarForm = new EvaluarForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Evaluar($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);



$id = (int) $this->params()->fromRoute('id',0);

$ex=null;
$tf = new Aspectoeval($this->dbAdapter);
$pf = new Evaluar($this->dbAdapter);
$ap = new Aplicar($this->dbAdapter);


foreach ($pf->getEvaluara($id) as $existe)
{
$ex=$existe["id_aplicar"];	
}

if($ex==null){


$a=$ap->getAplicarid($id);

			
			foreach ($tf->getAspectoeval($a["id_convocatoria"]) as $tabla) {

								
				$resul=$pf->addEvaluar($id,$tabla["ponderacion1"],$tabla["descripcion"]);
			
			}
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
			$pantalla="evaluar";
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


			$view = new ViewModel(array('form'=>$EvaluarForm,
										'titulo'=>"Evaluar aspecto de la propuesta",
										'titulo2'=>"Tipos Valores Existentes", 
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$u->getEvaluados($id),
										'id'=>$id,
										'd_user'=>$usua->getUsuarios($d),
										'd_val'=>$valflex->getValoresf(),
										'id_user'=>$identi->id_usuario,
										'menu'=>$dat["id_rol"]));
			return $view;
			
		}
    }


}