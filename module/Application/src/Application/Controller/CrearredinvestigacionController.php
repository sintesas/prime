<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Crearredinvestigacion\CrearredinvestigacionForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Redinvestigacion;
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

class CrearredinvestigacionController extends AbstractActionController
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
		if($this->getRequest()->isPost()){

			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$redin = new Redinvestigacion($this->dbAdapter);
			$auth = $this->auth;

			//verifica si esta conectado a la aplicacion de lo contrario lo redirige a la pagina login
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}
			
			//variables para verificar roles y permisos
			$rol = new Roles($this->dbAdapter);
			$rolusuario = new Rolesusuario($this->dbAdapter);
		 	$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$dat["id_rol"];
			}
			
			//obtiene la informacion de la pantalla en la variable data
			$data = $this->request->getPost();

			//crear el objeto forma para llamar a la pantalla
			$CrearredinvestigacionForm = new CrearredinvestigacionForm();
			
			//adiciona la informacion en la tabla correspondiente
			if($data["coordinador_1"]==null){
				$this->flashMessenger()->addMessage("Debe seleccionar el Coordinador 1.");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/crearredinvestigacion/index');
			}else{
				if($data["estado_red"]==null){
					$this->flashMessenger()->addMessage("Debe seleccionar un estado.");
					return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/crearredinvestigacion/index');
				}else{
					$resultado=$redin->addRedinvestigacion($data);
				}
			}



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

			//redirige a la pantalla de inicio del controlador dependiendo si guarda o no la info
			if ($resultado!=''){

	$resul=$au->getAuditoriaid(session_id(),get_real_ip(),$identi->id_usuario);
				$evento='Creación de red de investigación. Nombre:' . $data["nombre_red"];
				$ad->addAuditoriadet( $evento,$resul);   

				$this->flashMessenger()->addMessage("Red de investigación creada con exito");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/redesinv/index');
			}else
			{
			$resultado=$au->getAuditoriaid(session_id(),get_real_ip(),$identi->id_usuario);
			
			$evento='Intento fallido de creacion de red de investigacion:'. $data["nombre_red"];
			$ad->addAuditoriadet( $evento,$resultado);  

				$this->flashMessenger()->addMessage("La creacion de la red de investigación falló.");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/redesinv/index');
			}
		}
		else
		{
			//crear el objeto forma para llamar a la pantalla
			$CrearredinvestigacionForm = new CrearredinvestigacionForm();

			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$auth = $this->auth;

			//variables acceso a BD
			$redinv = new Redinvestigacion($this->dbAdapter);
			$vflex = new Agregarvalflex($this->dbAdapter);
			
			//verifica si esta conectado a la aplicacion de lo contrario lo redirige a la pagina login
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}


			//define campos lista que apuntan a valores flexibles
			$us = new Usuarios($this->dbAdapter);
			$a='';
			$opciones=array('0' => 'Sin asignar');
			foreach ($us->getUsuarios($a) as $dat) {
				$op=array($dat["id_usuario"] => $dat["primer_nombre"]." ".$dat["segundo_nombre"]." ".$dat["primer_apellido"].' '.$dat["segundo_apellido"]);
				$opciones=$opciones+$op;
			}
			$CrearredinvestigacionForm->add(
				array(
					'name' => 'coordinador_1',
					'type' => 'Zend\Form\Element\Select',
                   	'attributes' => array(
                    'id' => 'coordinador_1'
                ),
				'size' => 25,
				'options' => array(
					'label' => 'Coordinador 1: ',
					'required' => true,
					'value_options' => $opciones,
				),
			));

			$CrearredinvestigacionForm->add(
				array('name' => 'coordinador_2',
					'type' => 'Zend\Form\Element\Select',
                  	'attributes' => array(
                    'id' => 'coordinador_2'
                ),
				'size' => 25,
				'options' => array(
					'label' => 'Coordinador 2: ',
					'value_options' => $opciones,
				),
			));

			//Variable para aplicar filtro de espacios vacios
			$filter = new StringTrim();

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
				$pantalla="crearredinvestigacion";
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
		
			//si tiene acceso a la pantalla crea la variable view que hae el llamado a la pantalla, si no lo redigire a mensaje admin.
			if(true){
				$view = new ViewModel(array('form'=>$CrearredinvestigacionForm,
							'titulo'=>"Crear red",
							'titulo2'=>"Tipos Valores Existentes", 
							'url'=>$this->getRequest()->getBaseUrl(),
							'datos'=>$redinv->getRedinv(),
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