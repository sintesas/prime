<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Proyectos\EditarinformeForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Informes;
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

class EditarinformeController extends AbstractActionController
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
			$not = new Informes($this->dbAdapter);
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
		$id2 = $this->params()->fromRoute('id2');
		$id3 = $this->params()->fromRoute('id3');

       	$upload = new \Zend\File\Transfer\Transfer();
       	$files = $upload->getFileInfo();
		foreach($files as $f){
			$archi=$f["name"];
		}
       	if($id3=='t'){
       		$upload->setDestination('./public/images/uploads/informesp/');
       		$upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR. $id2."_".$archi,'overwrite' => true));
       	}else{
       		$upload->setDestination('./public/images/uploads/');
       	}
       	
		$upload->setValidators(array(
		    'Size'  => array('min' => 1, 'max' => 50000000),
		));
       	$rtn = array('success' => null);
       	$rtn['success'] = $upload->receive();
       

			$conv = new Convocatoria($this->dbAdapter);
			$apli = new Aplicarm($this->dbAdapter);


			$data = $this->request->getPost();

			//adiciona la noticia

if($id3=='t'){
	$resultado=$not->updateInformeDatosBasicos($data,$id, $archi); 
}else if($id3=='s'){
	$resultado=$not->updateInformearch($data,$id, $archi);
}else{
			$resultado=$not->updateInforme($data,$id);
}

			//redirige a la pantalla de inicio del controlador
			if ($resultado==1){

				$this->flashMessenger()->addMessage("Informe Editado con exito");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarproyecto/index/'.$id2);

			}else
			{
				$this->flashMessenger()->addMessage("La creacion del archivo fallo");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id);
			}
		}
		else
		{
			$EditarinformeForm= new EditarinformeForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Informes($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			$filter = new StringTrim();


			//define el campo ciudad
	        $EditarinformeForm->add(array(
	            'name' => 'observaciones',
	            'attributes' => array(
	                'type'  => 'textarea',
					'placeholder'=>'Ingrese la observación del documento',
					'lenght' => 500,

	            ),
	            'options' => array(
	                'label' => 'Observación de la aprobación:',
	            ),
	        ));

	        $EditarinformeForm->add(array(
	            'type' => 'Zend\Form\Element\Select',
	            'name' => 'id_estado',
				'required'=>'required',
	            'options' => array(
	                'label' => 'Estado',
	                'value_options' => array(
	                    '' => 'Seleccione',
	                    '1' => 'Cumple',
	                    '2' => 'No cumple',
	                    '3' => 'Pendiente',
	                ),
	            ),
	            'attributes' => array(
	                'value' => '1' //set selected to '1'
	            )
	        ));

		$EditarinformeForm->add(array(
            'name' => 'informe',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el nombre del informe y/o Producto',
            ),
            'options' => array(
                'label' => 'Nombre del Informe y/o Producto:',
            ),
        ));

		$EditarinformeForm->add(array(
			'name' => 'fecha_limite',
			'attributes' => array(
				'type' => 'Date',
				'required'=>'required',
				'placeholder'=>'YYYY-MM-DD'
			),
			'options' => array(
				'label' => 'Fecha límite:'
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
$id3 = $this->params()->fromRoute('id3');
			$view = new ViewModel(array('form'=>$EditarinformeForm,
										'titulo'=>"Informes de Investigación",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$u->getArchivos($id),
										'id'=>$id,
										'id2'=>$id2,
										'id3'=>$id3,
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
			$u = new Propuestainv($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
$id2 = (int) $this->params()->fromRoute('id2',0);
			$u->eliminarArchivos($id);
$this->flashMessenger()->addMessage("Archivo eliminado con exito");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicar/index/'.$id2);
			
	}

	public function delAction(){
$PropuestainvForm = new PropuestainvForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Propuestainv($this->dbAdapter);
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
			$u = new Informes($this->dbAdapter);
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