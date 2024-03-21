<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Proyectos\EditarinformemForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Informesm;
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

class EditarinformemController extends AbstractActionController
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
			$not = new Informesm($this->dbAdapter);
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
			$id2 = (int) $this->params()->fromRoute('id2');

			$data = $this->request->getPost();

			$inform = $not->getIdInforme($id, $id2);
          
			$resultado = $not->updateInformeById($data, $inform[0]["id_informe"]);

			//redirige a la pantalla de inicio del controlador
			if ($resultado == 1){
				$this->flashMessenger()->addMessage("Informe editado con éxito.");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/aprobacionm/index/'.$id2);

			}else
			{
				$this->flashMessenger()->addMessage("La edición del informe falló.");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/aprobacionm/index/'.$id2);
			}
		}
		else
		{
			$EditarinformemForm= new EditarinformemForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Informesm($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2');

			$filter = new StringTrim();

			$inform = $u->getIdInforme($id, $id2);
            if(count($inform)==0){
                $u->addInforme($id, $id2);
                $inform = $u->getIdInforme($id, $id2);
            }
           // $inform = $inform;
			//$inform = $u->getInformeById($id_informe[0]["id_informe"]);
            //print_r($inform);
			//define el campo ciudad
        $EditarinformemForm->add(array(
            'name' => 'observaciones',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese la observación del documento',
				'maxlength' => 4000,
				'value' => $inform[0]["observaciones"]
            ),
            'options' => array(
                'label' => 'Observación de la aprobación:',
            ),
        ));

        $EditarinformemForm->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'estado',
			'required'=>'required',
            'options' => array(
                'label' => 'Estado',
                'value_options' => array(
                    'Cumple' => 'Cumple',
                    'No cumple' => 'No cumple',
                    'Pendiente' => 'Pendiente',
                ),
            ),
            'attributes' => array(
                'value' => $inform[0]["estado"]
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
			$pantalla="aprobacionm";
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
			$view = new ViewModel(array('form'=>$EditarinformemForm,
										'titulo'=>"Informes de Investigación Monitor",
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
			$u = new Informesm($this->dbAdapter);
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