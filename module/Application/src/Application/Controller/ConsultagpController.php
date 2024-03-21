<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editargrupoinv\ConsultagpForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Grupoinvestigacion;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Gruposproy;
use Application\Modelo\Entity\Lineas;

class ConsultagpController extends AbstractActionController
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
			$ConsultagpForm = new ConsultagpForm();

			//define el campo ciudad
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(23) as $uni) {
			$op=array(''=>'');
			$op=$op+array($uni["id_valor_flexible"]=>$uni["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$ConsultagpForm ->add(array(
			'name' => 'id_unidad_academica',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Unidad Académica : ',
            'value_options' => 
				$opciones
            ,
			),
			)); 

			//define el campo ciudad
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(33) as $dept) {
			$op=array(''=>'');
			$op=$op+array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$ConsultagpForm ->add(array(
			'name' => 'id_dependencia_academica',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Dependencia Académica : ',
            'value_options' => 
				$opciones
            ,
			),
			)); 

			//define el campo ciudad
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(34) as $dept) {
			$op=array(''=>'');
			$op=$op+array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$ConsultagpForm ->add(array(
			'name' => 'id_programa_academico',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Programa Académico : ',
            'value_options' => 
				$opciones
            ,
			),
			)); 

			//adiciona la noticia
			$proy = new Proyectos($this->dbAdapter);
			$gruposproy= new Gruposproy($this->dbAdapter);
			$valflex = new Agregarvalflex($this->dbAdapter);
			$linea= new Lineas($this->dbAdapter);
			$consulta = 0;
			if($data["nombre_grupo"]=="" && $data["cod_grupo"]=="" && $data["codigo_proy"]=="" && $data["nombre_proy"]=="" && $data["tipo_proyecto"]=="" && $data["id_unidad_academica"]=="" && $data["id_dependencia_academica"]=="" && $data["id_programa_academico"]==""){
				$consulta = 2;
			}

			$view = new ViewModel(array('form'=>$ConsultagpForm,
						'titulo'=>"Consulta de Proyectos Grupo", 
						'datos'=>$not->filtroGrupos($data),
						'datos2'=>$proy->getData($data),
						'proy'=>$proy->getProyectoh(),
						'gproy'=>$gruposproy->getGrupos(),
						'vf'=>$valflex->getValoresf(),
						'linea'=>$linea->getLineast(),
						'url'=>$this->getRequest()->getBaseUrl(),
						'consulta'=> $consulta,
						'menu'=>$dat["id_rol"], 
						'data'=>$data
				));
			return $view;
		}
		else
		{
			$ConsultagpForm = new ConsultagpForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Grupoinvestigacion($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
				$identi = new \stdClass();
            	$identi->id_usuario = '0';
            	//return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			$filter = new StringTrim();

			//define el campo ciudad
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(23) as $uni) {
			$op=array(''=>'');
			$op=$op+array($uni["id_valor_flexible"]=>$uni["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$ConsultagpForm ->add(array(
			'name' => 'id_unidad_academica',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Unidad Académica : ',
            'value_options' => 
				$opciones
            ,
			),
			)); 

			//define el campo ciudad
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(33) as $dept) {
			$op=array(''=>'');
			$op=$op+array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$ConsultagpForm ->add(array(
			'name' => 'id_dependencia_academica',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Dependencia Académica : ',
            'value_options' => 
				$opciones
            ,
			),
			)); 

			//define el campo ciudad
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(34) as $dept) {
			$op=array(''=>'');
			$op=$op+array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$ConsultagpForm ->add(array(
			'name' => 'id_programa_academico',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Programa Académico : ',
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
			$pantalla="Consultagp";
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
		
			$view = new ViewModel(array('form'=>$ConsultagpForm,
										'titulo'=>"Consulta de Proyectos Grupo",
										'titulo2'=>"Tipos Valores Existentes", 
										'url'=>$this->getRequest()->getBaseUrl(),
										'consulta'=>1,
										'datos'=>$u->getGrupoinvestigacion(),
										'menu'=>$dat["id_rol"]));
			return $view;
		}
    }


}
