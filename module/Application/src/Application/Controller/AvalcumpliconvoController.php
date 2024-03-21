<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Avalcumpliconvo\AvalcumpliconvoForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Avalcumpliconvo;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;

class AvalcumpliconvoController extends AbstractActionController
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
			$not = new Avalcumpliconvo($this->dbAdapter);
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

			//obtiene la informacion de las pantallass
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$data = $this->request->getPost();
			$AvalcumpliconvoForm= new AvalcumpliconvoForm();
			//adiciona la noticia
			if($id2==0){
				$resultado=$not->addInforme($data, $id);
			}else{
				$resultado=$not->updateInformeByFecha($data, $id2);
			}

			//redirige a la pantalla de inicio del controlador
			if ($resultado==1){
				$this->flashMessenger()->addMessage("Informe creado con éxito.");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarconvocatoriai/index/'.$id);
			}else
			{
				$this->flashMessenger()->addMessage("La creación del informe falló.");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarconvocatoriai/index/'.$id);
			}
		}
		else
		{
			$AvalcumpliconvoForm= new AvalcumpliconvoForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Avalcumpliconvo($this->dbAdapter);
			$auth = $this->auth;

			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);

			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			$filter = new StringTrim();
			$dataInforme = array();

			if($id2!=0){
				$not = new Avalcumpliconvo($this->dbAdapter);
				$data = $not->getInformeById($id2);
				$dataInforme["informe"] = trim($data[0]["informe"]);
				$dataInforme["fecha_limite"] = trim($data[0]["fecha_limite"]);
				$dataInforme["estado"] = trim($data[0]["estado"]);
			}else{
				$dataInforme["informe"] = "";
				$dataInforme["fecha_limite"] = "";
				$dataInforme["estado"] = trim("Activo");
			}

			$vf = new Agregarvalflex($this->dbAdapter);

	        $AvalcumpliconvoForm->add(array(
	            'name' => 'informe',
	            'attributes' => array(
	                'type'  => 'text',
					'placeholder'=>'Ingrese el nombre del informe',
					'value' => $dataInforme["informe"]
	            ),
	            'options' => array(
	                'label' => 'Nombre del Informe:',
	            ),
	        ));

			$AvalcumpliconvoForm->add(array(
				'name' => 'fecha_limite',
				'attributes' => array(
					'type' => 'Date',
					'required'=>'required',
					'placeholder'=>'YYYY-MM-DD',
					'value' => $dataInforme["fecha_limite"]
				),
				'options' => array(
					'label' => 'Fecha límite:'
				)
	        ));
			$AvalcumpliconvoForm->get('estado')->setValue($dataInforme["estado"]);
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
			$pantalla="cronograma";
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

			$view = new ViewModel(array('form'=>$AvalcumpliconvoForm,
										'titulo'=>"Informes del monitor",
										'url'=>$this->getRequest()->getBaseUrl(),
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
			$u = new Avalcumpliconvo($this->dbAdapter);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$u->eliminarInforme($id);
			$this->flashMessenger()->addMessage("Informe eliminado con éxito.");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/aprobacionm/index/'.$id2);
			
	}

	public function delAction(){
			$AvalcumpliconvoForm= new AvalcumpliconvoForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Avalcumpliconvo($this->dbAdapter);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$view = new ViewModel(
				array(
					'form'=>$AvalcumpliconvoForm,
					'titulo'=>"Eliminar registro",
					'url'=>$this->getRequest()->getBaseUrl(),
					'datos'=>$id,
					'id2'=>$id2
				)
			);
			return $view;

	}

}