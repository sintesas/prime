<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Proyectos\ConsultamonitorForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Monitor;
use Application\Modelo\Entity\Asignareval;
use Application\Modelo\Entity\Evaluar;
use Application\Modelo\Entity\Usuarios;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Lineas;
use Application\Modelo\Entity\Libros;
use Application\Modelo\Entity\Proyectosext;
use Application\Modelo\Entity\Proyectosinv;

class ConsultamonitorController extends AbstractActionController
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
        
		if($this->getRequest()->isPost()){
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Monitor($this->dbAdapter);
		        $this->auth = new AuthenticationService();
			$auth = $this->auth;

$result = $auth->hasIdentity();

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
			$ConsultamonitorForm= new ConsultamonitorForm();
					$pi = new Proyectosinv($this->dbAdapter);
			$us = new Usuarios($this->dbAdapter);
			//define el campo ciudad
	

			//adiciona la noticia

			$usuario = new Usuarios($this->dbAdapter);
			$us = new Usuarios($this->dbAdapter);
			$proyext = new Proyectosext($this->dbAdapter);
			$eval= new Evaluar($this->dbAdapter);
			//redirige a la pantalla de inicio del controlador
			$view = new ViewModel(array('form'=>$ConsultamonitorForm,
						'titulo'=>"Consulta de monitores", 
						'datos'=>$not->filtroMon($data),
						'usuario'=>$usuario->getArrayusuarios(),
						'pi'=>$pi->getProyectosinvs(),
						'us'=>$us->getArrayusuarios(),
						'url'=>$this->getRequest()->getBaseUrl(),
										'consulta'=>1,
						'menu'=>$dat["id_rol"]));
			return $view;
		}
		else
		{
			$ConsultamonitorForm= new ConsultamonitorForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Monitor($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$us = new Usuarios($this->dbAdapter);

			$this->auth = new AuthenticationService();
			$auth = $this->auth;
	
$result = $auth->hasIdentity();

			$identi=$auth->getStorage()->read();


			$filter = new StringTrim();

			//define el campo ciudad


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
		
			if ($dat["id_rol"]!= ''){
			//me verifica el permiso sobre la pantalla
			$pantalla="consultaconvocatoria";
			$panta=0;
			$pt = new Agregarvalflex($this->dbAdapter);


			foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
			$panta["id_valor_flexible"];
			}

			$permiso->verificarPermiso($roles_1,$panta["id_valor_flexible"]);
			foreach ($permiso->verificarPermiso($roles_1,$panta["id_valor_flexible"]) as $per) {
				$permiso_1=$per["id_rol"];
			}
}
							$pi = new Proyectosinv($this->dbAdapter);
			if(true){


			$view = new ViewModel(array('form'=>$ConsultamonitorForm,
										'titulo'=>"Consulta de monitores",
										'consulta'=>0,
'url'=>$this->getRequest()->getBaseUrl(),
'pi'=>$pi->getProyectosinvs(),
'us'=>$us->getArrayusuarios(),
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
