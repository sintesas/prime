<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Foro\ForoForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Foro;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;

class ForoController extends AbstractActionController
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
			$not = new Foro($this->dbAdapter);
			$auth = $this->auth;

			//verifica si esta conectado
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
				$identi = new \stdClass();
            	$identi->id_usuario = '0';
				//return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}
			

			$usuarios = new Usuarios($this->dbAdapter);

			$usuarios->getArrayusuariosid($identi->id_usuario);



			$rol = new Roles($this->dbAdapter);
			$rolusuario = new Rolesusuario($this->dbAdapter);
		 	$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$dat["id_rol"];
			}

			//obtiene la informacion de las pantallas
			$data = $this->request->getPost();
			$ForoForm = new ForoForm();
			//adiciona la noticia
			//redirige a la pantalla de inicio del controlador
			$view = new ViewModel(array('form'=>$ForoForm,
						'titulo'=>"Foro", 
						'datos'=>$not->filtroForo($data),
						'url'=>$this->getRequest()->getBaseUrl(),
						'id_user' => $identi->id_usuario,
						'menu'=>$dat["id_rol"],
						'data'=> $data));
			return $view;
		}
		else
		{
			$ForoForm = new ForoForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Foro($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	

			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
				$identi = new \stdClass();
            	$identi->id_usuario = '0';
				//return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			$usuarios = new Usuarios($this->dbAdapter);

			$us=$usuarios->getArrayusuariosid($identi->id_usuario);


if(trim($us["id_estado"])=='F')
{
			$this->flashMessenger()->addMessage("Usuario bloqueado para acceder a los foros.");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index/'.$identi->id_usuario);

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
			$pantalla="foro";
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
		
			
			$view = new ViewModel(array('form'=>$ForoForm,
										'titulo'=>"Foro",
										'titulo2'=>"Tipos Valores Existentes", 
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$u->getForo(),
										'id_user' => $identi->id_usuario,
										'menu'=>$dat["id_rol"]));
			return $view;
			
		}
    }
	

public function verAction(){

$ForoForm = new ForoForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Foro($this->dbAdapter);
			$usua = new Usuarios($this->dbAdapter);
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
			$pantalla="foro";
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
		$id = (int) $this->params()->fromRoute('id',0);
			
			$view = new ViewModel(array('form'=>$ForoForm,
										'titulo'=>"Foro",
										'datos'=>$u->getForoid($id),
										'respuestas'=>$u->getRespuestas($id),
										'usuario'=>$usua->getArrayusuarios(),'id'=>$id,
										'id_user' => $identi->id_usuario,
										'menu'=>$dat["id_rol"]));
			return $view;
			

}



	public function eliminarAction(){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Foro($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$u->eliminarForo($id);
$this->flashMessenger()->addMessage("Tema eliminado con exito");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/foro/ver/'.$id2);
	}


	public function delAction(){
$ForoForm= new ForoForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Foro($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);


			$view = new ViewModel(array('form'=>$ForoForm,
										'titulo'=>"Eliminar registro",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$id,'id2'=>$id2));
			return $view;
	}

	public function bajarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Foro($this->dbAdapter);
		$filter = new StringTrim();
		$id = (int) $this->params()->fromRoute('id');

		$data=$u->getForoid($id);

		foreach($data as $d){
			$d["archivo"];
		}

		$fileName = $d["archivo"];
				
	    $response = new \Zend\Http\Response\Stream();
	    $response->setStream(fopen("public/images/uploads/".$filter->filter($fileName), 'r'));
	    $response->setStatusCode(200);

	    $headers = new \Zend\Http\Headers();
	    $headers->addHeaderLine('Content-Type', 'whatever your content type is')
	            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
	            ->addHeaderLine('Content-Length', filesize("public/images/uploads/".$filter->filter($fileName)));

	    $response->setHeaders($headers);
	    return $response;
	}
}