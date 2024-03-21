<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Banner\BannerForm;
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

class BannerController extends AbstractActionController
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
			
			//obtiene la informacion de la pantalla en la variable data
			$data = $this->request->getPost();

			$upload = new \Zend\File\Transfer\Adapter\Http();
			$upload->setDestination('./public/images/');
			$upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "img_".$data["imagen"]. '.jpg','overwrite' => true));

			 $rtn = array(
                'success' => null
            );

            $rtn['success'] = $upload->receive();
            
            $files = $upload->getFileInfo();
            foreach ($files as $f) {
                $archi = $f["name"];
            }			

			//crear el objeto forma para llamar a la pantalla
			$BannerForm = new BannerForm();
			
			if($rtn['success']!=null){
				$this->flashMessenger()->addMessage("Imagen actualizada con éxito.");
			}else{
				$this->flashMessenger()->addMessage("La actualización de la imagen falló. Por favor intente nuevamente.");
			}
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/banner/index');			
		}
		else
		{
			//crear el objeto forma para llamar a la pantalla
			$BannerForm = new BannerForm();

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
				$pantalla="Banner";
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
					'form'=>$BannerForm,
					'titulo'=>"Administrar banner",
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