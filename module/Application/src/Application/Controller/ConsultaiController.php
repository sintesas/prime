<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editargrupoinv\ConsultaiForm;
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

class ConsultaiController extends AbstractActionController
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
        if ($auth->getStorage()->read() == false && $auth->getStorage()->read() == null) {
            $identi = new \stdClass();
                $identi->id_usuario = '0';
        }
        else{
            $identi = print_r($auth->getStorage()->read()->id_usuario,true);
            $resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,get_class());
            if($resultadoPermiso==0){
                $this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
            }
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
			$ConsultaiForm = new ConsultaiForm();

			//adiciona la noticia
			$inte = new Integrantes($this->dbAdapter);
			$usua  = new Usuarios($this->dbAdapter);
			
			//redirige a la pantalla de inicio del controlador
			$vf = new Grupoinvestigacion($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getGrupoinvestigacion() as $dept) {
				$op=array(''=>'');
				$op=$op+array($dept["id_grupo_inv"]=>$dept["nombre_grupo"]);
				$opciones=$opciones+$op;
			}

			$integrantes_grupo = $inte->filtroGrupos($data);
			if($data["id_grupo_inv"] != 0){
				$info_grupo = $vf->getGrupoinvid($data["id_grupo_inv"]);
				$data_lider["id_integrante"] = $info_grupo[0]["id_lider"];	
				$data_lider["id_grupo_inv"] = "";
				$data_lider["id_integrantes"] = "";
				array_push($integrantes_grupo, $data_lider);
			}
			$ConsultaiForm->get('id_grupo_inv')->setValueOptions($opciones);
			$view = new ViewModel(array('form'=>$ConsultaiForm,
						'titulo'=>"Consulta investigadores", 
						'campos'=>$data,
						'datos'=>$us->filtroUsuarioInvestigadores($data),
						'datos2'=>$integrantes_grupo,
						'usua'=>$usua->getArrayusuariosConsultaInvestigadores(),		
						'consulta'=>1,		
						'url'=>$this->getRequest()->getBaseUrl(),	
						'menu'=>$dat["id_rol"]));
			return $view;
		}
		else
		{
			$ConsultaiForm = new ConsultaiForm();
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

			$vf = new Grupoinvestigacion($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getGrupoinvestigacion() as $dept) {
				$op=array(''=>'');
				$op=$op+array($dept["id_grupo_inv"]=>$dept["nombre_grupo"]);
				$opciones=$opciones+$op;
			}
			$ConsultaiForm->get('id_grupo_inv')->setValueOptions($opciones);
		
			
			$Formaaca = new Formacionacahv($this->dbAdapter);
			$Formacom = new Formacioncomhv($this->dbAdapter);
			$Explab = new Experiencialabhv($this->dbAdapter);
			$Areas = new Areashv($this->dbAdapter);
			$Idioma = new Idiomashv($this->dbAdapter);
			$Lineas = new Lineashv($this->dbAdapter);
			$Proyext = new Proyectosexthv($this->dbAdapter);
			$Otras = new Otrasproduccioneshv($this->dbAdapter);
			$lib = new Libroshv($this->dbAdapter);
			$art  = new Articuloshv($this->dbAdapter);
			$view = new ViewModel(array('form'=>$ConsultaiForm,
										'titulo'=>"Consulta investigadores",
										'titulo2'=>"Tipos valores existentes", 
										'url'=>$this->getRequest()->getBaseUrl(),
										'menu'=>$dat["id_rol"]));
			return $view;
			
		}
    }


}
