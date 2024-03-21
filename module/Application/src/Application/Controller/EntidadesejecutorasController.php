<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Proyectos\EntidadesejecutorasForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Entidadesejecutoras;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;

class EntidadesejecutorasController extends AbstractActionController
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
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Entidadesejecutoras($this->dbAdapter);
			$auth = $this->auth;

			//verifica si esta conectado
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}
			

			$rol = new Roles($this->dbAdapter);
			$rolusuario = new Rolesusuario($this->dbAdapter);
		 	$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$dat["id_rol"];
			}


			$id = (int) $this->params()->fromRoute('id',0);
			$conv = new Convocatoria($this->dbAdapter);
			$apli = new Aplicarm($this->dbAdapter);


			$data = $this->request->getPost();

			//adiciona la noticia

			$resultado=$not->addEntidad($data,$id);



			//redirige a la pantalla de inicio del controlador
			if ($resultado==1){

				$this->flashMessenger()->addMessage("Archivo Creado con exito");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarproyecto/index/'.$id);

			}else
			{
				$this->flashMessenger()->addMessage("La creacion del archivo fallo");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id);
			}
		}
		else
		{
			$EntidadesejecutorasForm = new EntidadesejecutorasForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Entidadesejecutoras($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			$filter = new StringTrim();

			//define el campo ciudad
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(38) as $dept) {
			$op=array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			
			//define el campo ciudad
        $EntidadesejecutorasForm->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'id_entidad',
			'required'=>'required',
            'options' => array(
                'label' => 'Nombre entidad',
                'value_options' => $opciones
            ),
            'attributes' => array(
                'value' => '1' //set selected to '1'
            )
        ));

$opciones=array();
			foreach ($vf->getArrayvalores(80) as $dept) {
			$op=array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
			$opciones=$opciones+$op;
			}

        $EntidadesejecutorasForm->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'id_rol',
			'required'=>'required',
            'options' => array(
                'label' => 'Rol entidad',
                'value_options' => $opciones
            ),
            'attributes' => array(
                'value' => '1' //set selected to '1'
            )
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
			$pantalla="editargrupoinv";
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
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$view = new ViewModel(array('form'=>$EntidadesejecutorasForm,
										'titulo'=>"Entidades ejecutoras",
										'url'=>$this->getRequest()->getBaseUrl(),
										'id'=>$id,
										'id2'=>$id2,
										'menu'=>$dat["id_rol"]));
			return $view;
		}
    }
	

	public function eliminarAction(){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Entidadesejecutoras($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
$id2 = (int) $this->params()->fromRoute('id2',0);
			$u->eliminarEntidad($id);
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarproyecto/index/'.$id2);
			
	}

	public function delAction(){
$EntidadesejecutorasForm= new EntidadesejecutorasForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Entidadesejecutoras($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);


			$view = new ViewModel(array('form'=>$EntidadesejecutorasForm,
										'titulo'=>"Eliminar registro",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$id,'id2'=>$id2));
			return $view;

	}

	public function bajarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Entidadesejecutoras($this->dbAdapter);
		$filter = new StringTrim();
		$id = (int) $this->params()->fromRoute('id');
		$data=$u->getArchivosid($id);
		foreach($data as $d){
			$d["archivo"];
		}
		$fileName = $d["archivo"];
    	$response = new \Zend\Http\Response\Stream();
    	$response->setStream(fopen("public/images/uploads/proyectoinvestigacion/".$filter->filter($fileName), 'r'));
		$response->setStatusCode(200);
	    $headers = new \Zend\Http\Headers();
	    $headers->addHeaderLine('Content-Type', 'whatever your content type is')
	            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
	            ->addHeaderLine('Content-Length', filesize("public/images/uploads/proyectoinvestigacion/".$filter->filter($fileName)));
	    $response->setHeaders($headers);
	    return $response;
	}
}