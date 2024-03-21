<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editargrupoinv\ConsultaeForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Grupoinvestigacion;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Formacionacahv;
use Application\Modelo\Entity\Formacioncomhv;
use Application\Modelo\Entity\Experiencialabhv;
use Application\Modelo\Entity\Integrantes;
use Application\Modelo\Entity\Areashv;
use Application\Modelo\Entity\Foro;
use Application\Modelo\Entity\Idiomashv;
use Application\Modelo\Entity\Articuloshv;
use Application\Modelo\Entity\Lineashv;
use Application\Modelo\Entity\Libroshv;
use Application\Modelo\Entity\Proyectosexthv;
use Application\Modelo\Entity\Otrasproduccioneshv;
use Application\Modelo\Entity\Autores;
use Application\Modelo\Entity\Capitulosusuario;
use Application\Modelo\Entity\Agregarautorusuario;
use Application\Modelo\Entity\Actividadeshv;
use Application\Modelo\Entity\Bibliograficoshv;
use Application\Modelo\Entity\Proyectosintusua;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Tablaequipop;

class ConsultaeController extends AbstractActionController
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
        
		if($this->getRequest()->isPost()){
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Grupoinvestigacion($this->dbAdapter);
			$us = new Usuarios($this->dbAdapter);
			$auth = $this->auth;

			//verifica si esta conectado
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
				$identi = new \stdClass();
            	$identi->id_usuario = '0';
				//return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}
			

			$rol = new Roles($this->dbAdapter);
			$rolusuario = new Rolesusuario($this->dbAdapter);
		 	$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$dat["id_rol"];
			}

			//obtiene la informacion de las pantallas
			$data = $this->request->getPost();
			$ConsultaeForm = new ConsultaeForm();

			//adiciona la noticia
			$inte = new Integrantes($this->dbAdapter);
			$usua  = new Usuarios($this->dbAdapter);
			
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones = array('' => '');
            foreach ($vf->getArrayvalores(66) as $datFlex) {                
                $op = array(
                    $datFlex["id_valor_flexible"] => $datFlex["descripcion_valor"]
                );  
                $opciones = $opciones  + $op;
            }
            $ConsultaeForm->get('tema')->setValueOptions($opciones);

            $actividadeseva = new Actividadeshv($this->dbAdapter);
			$view = new ViewModel(array('form'=>$ConsultaeForm,
						'titulo'=>"Consulta evaluadores", 
						'url'=>$this->getRequest()->getBaseUrl(),
						'campos'=>$data,
						'useractividades'=>$actividadeseva->getActividadeshvByTema($data->tema),
						'datos'=>$us->filtroUsuarioEvaluadores($data),
						'consulta'=>1,			
						'menu'=>$dat["id_rol"]));
			return $view;
		}
		else
		{
			$ConsultaeForm = new ConsultaeForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Grupoinvestigacion($this->dbAdapter);
			$us = new Usuarios($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
				$identi = new \stdClass();
            	$identi->id_usuario = '0';
				//return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
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
			$pantalla="Consultagi";
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
			
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones = array('' => '');
            foreach ($vf->getArrayvalores(66) as $datFlex) {                
                $op = array(
                    $datFlex["id_valor_flexible"] => $datFlex["descripcion_valor"]
                );  
                $opciones = $opciones  + $op;
            }

            $ConsultaeForm->get('tema')->setValueOptions($opciones);
			$view = new ViewModel(array('form'=>$ConsultaeForm,
										'titulo'=>"Consulta evaluadores",
										'titulo2'=>"Tipos valores existentes", 
										'url'=>$this->getRequest()->getBaseUrl(),
										'menu'=>$dat["id_rol"]));
			return $view;
			
		}
    }


}
