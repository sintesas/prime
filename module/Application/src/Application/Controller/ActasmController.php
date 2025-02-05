<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Proyectos\ActasmForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Actasm;
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

class ActasmController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     //Cargamos el servicio de autenticacien el constructor
     $this->auth = new AuthenticationService();
	}
	
    public function indexAction()
    {
		if($this->getRequest()->isPost()){
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Actasm($this->dbAdapter);
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

       	$upload = new \Zend\File\Transfer\Transfer();
			       	$upload->setDestination('./public/images/uploads/');
			$upload->setValidators(array(
			    'Size'  => array('min' => 1, 'max' => 50000000),
			));
       	$rtn = array('success' => null);
       	$rtn['success'] = $upload->receive();
        

	$files = $upload->getFileInfo();
		foreach($files as $f){
			$archi=$f["name"];
		}


		$id = (int) $this->params()->fromRoute('id',0);
			

			$data = $this->request->getPost();

			//adiciona la noticia

			$resultado=$not->addArchivos($data,$id,$archi);



			//redirige a la pantalla de inicio del controlador
			if ($resultado==1){

				$this->flashMessenger()->addMessage("Documento cargado con éxito.");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/aprobacionm/index/'.$id);

			}else
			{
				$this->flashMessenger()->addMessage("La carga del documento falló.");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/aprobacionm/index/'.$id);
			}
		}
		else
		{
			$ActasmForm= new ActasmForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Actasm($this->dbAdapter);
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
			foreach ($vf->getArrayvalores(24) as $dept) {
			$op=array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$ActasmForm->add(array(
			'name' => 'id_tipo_archivo',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Tipo de Archivo : ',
            'value_options' => 
				$opciones
            ,
			),
			)); 
			//define el campo ciudad
        $ActasmForm->add(array(
            'name' => 'nombre',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el nombre del documento',

            ),
            'options' => array(
                'label' => 'Nombre del documento:',
            ),
        ));

        $ActasmForm->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'id_estado',
			'required'=>'required',
            'options' => array(
                'label' => 'Estado',
                'value_options' => array(
                    '' => 'Seleccione',
                    '1' => 'Creado',
                    '2' => 'Aprobado',
                    '3' => 'Cerrado',
                ),
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
		
			if(true){
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$view = new ViewModel(array('form'=>$ActasmForm,
										'titulo'=>"Adjuntar avales de cumplimiento de la monitoria",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$u->getArchivos($id),
										'id'=>$id,
										'id2'=>$id2,
										'menu'=>$dat["id_rol"]));
			return $view;
			}
			else
			{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
			}
		}
    }
	

	public function eliminarAction(){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Actasm($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$u->eliminarArchivos($id);
			$this->flashMessenger()->addMessage("Archivo eliminado con exito");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/aprobacionm/index/'.$id2);
			
	}

	public function delAction(){
			$PropuestainvForm = new ActasmForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Actasm($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);


			$view = new ViewModel(array('form'=>$PropuestainvForm,
										'titulo'=>"Eliminar registro",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$id,'id2'=>$id2));
			return $view;

	}

	public function bajarAction(){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Actasm($this->dbAdapter);
$filter = new StringTrim();
$id = (int) $this->params()->fromRoute('id');

$data=$u->getArchivosid($id);

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