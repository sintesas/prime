<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Graficas\GraficasForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Repositorio;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;
use Application\Modelo\Entity\Agregarautorrepositorio;
use Application\Modelo\Entity\Redinvestigacion;
use Application\Modelo\Entity\Semillero;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Proyectos;


class GraficasController extends AbstractActionController
{
	//declaracion de variables 
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
    		 //Cargamos el servicio de autenticacion en el constructor
     		$this->auth = new AuthenticationService();
	}
	
	public function indexAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
        $auth = $this->auth;
        $permisos = new Permisos($this->dbAdapter);
        $identi = print_r($auth->getStorage()->read()->id_usuario,true);
        $resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,get_class());
        if($resultadoPermiso==0){
            $this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
        }
        
		//Preguntamos si la forma esta en modo post
		
			//crear el objeto forma para llamar a la pantalla
		$GraficasForm = new GraficasForm();

		//Creacion adaptador de conexion, objeto de datos, auditoria
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$auth = $this->auth;

		$vflex = new Agregarvalflex($this->dbAdapter);
		
		//verifica si esta conectado a la aplicacion de lo contrario lo redirige a la pagina login
		$identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}

		//variables para verificar roles y permisos
		$per=array('id_rol'=>'');
		$dat=array('id_rol'=>'');
		$rolusuario = new Rolesusuario($this->dbAdapter);
		$permiso = new Permisos($this->dbAdapter);
	
		//verifica el tipo de rol asignado al usuario
		$rolusuario->verificarRolusuario($identi->id_usuario);
		foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
			$dat["id_rol"];
		}
	
		//me verifica el permiso sobre la pantalla
		if ($dat["id_rol"]!= ''){
			$pantalla="Graficas";
			$panta=0;
			$pt = new Agregarvalflex($this->dbAdapter);

			foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
				$panta["id_valor_flexible"];
			}

			//verifica el id del valor flexible para el permiso
			$permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]);
			foreach ($permiso->verificarPermiso($dat["id_rol"],$panta["id_valor_flexible"]) as $per) {
				$per["id_rol"];
			}
		}
		$redinv = new Redinvestigacion($this->dbAdapter);
		$semilleroinv = new Semillero($this->dbAdapter);
		$grupoinv = new Grupoinvestigacion($this->dbAdapter);
		$convo = new Convocatoria($this->dbAdapter);
		$usua = new Usuarios($this->dbAdapter);
		$rol = new Roles($this->dbAdapter);
		$rolesusu = new Rolesusuario($this->dbAdapter);
		$aplicacionesCovo = new Aplicarm($this->dbAdapter);
		$proyectos = new Proyectos($this->dbAdapter);
		
		//si tiene acceso a la pantalla crea la variable view que hae el llamado a la pantalla, si no lo redigire a mensaje admin.
		if(true){
			$view = new ViewModel(array(
				'form'=>$GraficasForm,
				'titulo'=>"Reportes estadísticos",
				'url'=>$this->getRequest()->getBaseUrl(),
				'redinv' => $redinv->getRedByYear(),
				'semilleroinv' => $semilleroinv->getSemilleroByYear(),
				'semilleroinvEstado' => $semilleroinv->getSemilleroByEstadoYear(),
				'redinvEstado' => $redinv->getRedByEstadoYear(),
				'grupoinvEstado' => $grupoinv->getGrupoByEstadoYear(),
				'convoTipo' => $convo->getConvoByTipoYear(),
				'grupoinv' => $grupoinv->getGruposByYear(),
				'usuaEstado' => $usua->getUsuariosByEstado(),
				'loginUsua' => $rolesusu->getLoginByRol(),
				'roles' => $rol->getRoles(),
				'rolesusu' => $rolesusu->getUsuariosByRol(),
				'aplicacionesCovo' => $aplicacionesCovo->getAplicacionesByConvocatoria(),
				'proyConvoYear' => $proyectos->getProyectosByYear(),
				'proyConvo' => $proyectos->getProyectosByConvo(),	
				'proySinConvo' => $proyectos->getProyectosByYearSinConvo(),	
				'totalUsua' => $usua->getArrayusuarios(),		
				'menu'=>$dat["id_rol"]));
			return $view;
		}
		else
		{
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
		}
	}
}