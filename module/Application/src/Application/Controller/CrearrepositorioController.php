<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Crearrepositorio\CrearrepositorioForm;
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

class CrearrepositorioController extends AbstractActionController
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
			$repositorio = new Repositorio($this->dbAdapter);
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

			$upload = new \Zend\File\Transfer\Transfer();
            $upload->setDestination('./public/images/uploads/');
            $upload->setValidators(array(
                'Size' => array(
                    'min' => 1,
                    'max' => 50000000
                )
            ));
            $rtn = array(
                'success' => null
            );

            $rtn['success'] = $upload->receive();
            
            $files = $upload->getFileInfo();
            foreach ($files as $f) {
                $archi = $f["name"];
            }
			
			//obtiene la informacion de la pantalla en la variable data
			$data = $this->request->getPost();

			//crear el objeto forma para llamar a la pantalla
			$CrearrepositorioForm = new CrearrepositorioForm();
			
			//adiciona la informacion en la tabla correspondiente
			$resultado=$repositorio->addRepositorio($data, $archi, $identi->id_usuario);;
			if ($resultado==1){
				$addAutor = new Agregarautorrepositorio($this->dbAdapter);
				$datosUltimo=array_pop($repositorio->getRepositoriot());
				$addAutor->addAgregarautorrepositorio($data["autor"], "1", $datosUltimo["id_repositorio"], "2");
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
				$evento='Creacion de noticia : '.$resultado;
				$ad->addAuditoriadet( $evento,$resul);   

				$this->flashMessenger()->addMessage("Repositorio creado con éxito.");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/repositorio/index');
			}else
			{
				$resultado=$au->getAuditoriaid(session_id(),get_real_ip(),$identi->id_usuario);
				
				$evento='Intento fallido de creacion del repositorio:'.$p;
				$ad->addAuditoriadet( $evento,$resultado);  

					$this->flashMessenger()->addMessage("La creacion del repositorio falló.");
					return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/repositorio/index');
			}
		}
		else
		{
			//crear el objeto forma para llamar a la pantalla
			$CrearrepositorioForm = new CrearrepositorioForm();

			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$auth = $this->auth;

			$vflex = new Agregarvalflex($this->dbAdapter);
			
			//verifica si esta conectado a la aplicacion de lo contrario lo redirige a la pagina login
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			//define campos lista que apuntan a valores flexibles
			$us = new Usuarios($this->dbAdapter);
			$a='';
			$opciones=array();
			foreach ($us->getUsuarios($a) as $dat) {
				$op=array($dat["id_usuario"] => $dat["primer_nombre"]." ".$dat["segundo_nombre"]." ".$dat["primer_apellido"].' '.$dat["segundo_apellido"]);
				$opciones=$opciones+$op;
			}
			$CrearrepositorioForm->add(array(
				'name' => 'autor',
				'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'autor',
                    'required'=>'required'
				),
				'size' => 25,
				'options' => array(
					'label' => 'Autor: ',
					'empty_option' => 'Seleccione el autor',
					'value_options' => $opciones,
				),
			));

            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(24) as $uni) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $uni["id_valor_flexible"] => $uni["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $CrearrepositorioForm->add(array(
                'name' => 'id_tipo',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Tipo de documento: ',
                    'value_options' => $opciones
                )
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
				$pantalla="Crearrepositorio";
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
				$view = new ViewModel(array(
					'form'=>$CrearrepositorioForm,
					'titulo'=>"Crear documento",
					'url'=>$this->getRequest()->getBaseUrl(),
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