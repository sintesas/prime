<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\EditarevaluarForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Evaluar;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Zend\Filter\StringTrim;

class EditarevaluarController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     	//Cargamos el servicio de autenticaci�n en el constructor
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

	    $id = (int) $this->params()->fromRoute('id',0);
	    $id2 = (int) $this->params()->fromRoute('id2',0);
	    $id3 = (int) $this->params()->fromRoute('id3',0);
		if($this->getRequest()->isPost()){
session_start();
			$EditarevaluarForm = new EditarevaluarForm();
			$id = (int) $this->params()->fromRoute('id',0);
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Evaluar($this->dbAdapter);

$this->auth = new AuthenticationService();
        $auth = $this->auth;

$identi= $auth->getStorage()->read();


			$data = $this->request->getPost();
			//print_r($data);
			$u->updateEvaluar($id,$data);
if($id3==1){
			$urlId = "/application/gestionproy/index/".$id2;
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlId);
			$this->flashMessenger()->addMessage("Estado Actualizado con Exito");
}else{
			$urlId = "/application/evaluar/index/".$id2;
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlId);
			$this->flashMessenger()->addMessage("Integrante editado con Exito");
}
		}else
		{

        $EditarevaluarForm = new EditarevaluarForm();

		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Evaluar($this->dbAdapter);
$this->auth = new AuthenticationService();
        	$auth = $this->auth;

		$identi= $auth->getStorage()->read();

		$filter = new StringTrim();
		$r=$u->getEvaluarid($id);


        $EditarevaluarForm->add(array(
            'name' => 'id_ponderacion2',
            'attributes' => array(
                'type'  => 'Number',
		'min'=>1,
		'max'=>$r["id_ponderacion1"],
				'placeholder'=>'Ingrese el valor de la ponderación',
            ),
            'options' => array(
                'label' => 'Valor Ponderación:',
            ),
        ));

        $EditarevaluarForm->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'estado',
			'required'=>'required',
            'options' => array(
                'label' => 'Estado :',
                'value_options' => array(
                    '' => 'Seleccione',
                    '1' => 'Cumple',
                    '0' => 'No Cumple',
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
			$pantalla="editarevaluar";
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
		

		$view = new ViewModel(array('form'=>$EditarevaluarForm,
									'titulo'=>"Evaluar Aspecto del Proyecto",
									'id'=>$id,
									'id2'=>$id2,
									'id3'=>$id3,
									'url'=>$this->getRequest()->getBaseUrl(),
									'menu'=>$dat["id_rol"]));
		return $view;

		}
    }
}
