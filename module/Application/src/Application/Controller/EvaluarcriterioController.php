<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\EvaluarcriterioForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Evaluarcriterio;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Criterioevaluacion;
use Application\Modelo\Entity\Aplicarm;


class EvaluarcriterioController extends AbstractActionController
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
			$not = new Evaluarcriterio($this->dbAdapter);
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


			//obtiene la informacion de las pantallas
			$data = $this->request->getPost();
			$EvaluarcriterioForm = new EvaluarcriterioForm();
			//adiciona la noticia
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$resultado=$not->updateEvaluarcriterio($data, $id, $id2, $identi->id_usuario);

			$criterios = $not->getEvaluarcriterioAplicacion($id);
			$suma = 0;
			$cont = 0;
			foreach ($criterios as $criterio) {
				$suma = $suma + $criterio["evaluacion_cuantitativa"];
				$cont = $cont + 1;
			}

			if($cont!=0){
				$divSumaCont = $suma / $cont;
			}else{
				$divSumaCont = 0;
			}
			$aplim = new Aplicarm($this->dbAdapter);
			$aplim->updateAplicarCuantitativa($id,round($divSumaCont,2));
			//redirige a la pantalla de inicio del controlador
			if ($resultado==1){
				$this->flashMessenger()->addMessage("Criterio de evaluación credado con éxito.");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/entrevistam/index/'.$id);
			}else
			{
				$this->flashMessenger()->addMessage("La creacion del criterio de evaluacion falló.");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/entrevistam/index/'.$id);
			}
		}
		else
		{
			$EvaluarcriterioForm = new EvaluarcriterioForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$not = new Evaluarcriterio($this->dbAdapter);
			$criterioEvaluacion = new Criterioevaluacion($this->dbAdapter);
			
			$resultado=$not->getEvaluarcriterio($id, $id2);
			if($resultado == null){
				$not->addEvaluarcriterio($id, $id2, $identi->id_usuario);
				$resultado=$not->getEvaluarcriterio($id, $id2);
			}

			$criterio = $criterioEvaluacion->getCriterioevaluacion($id2);
			$EvaluarcriterioForm->add(array(
	            'name' => 'criterio',
	            'attributes' => array(
	                'type'  => 'textarea',
					'value' => $criterio[0]["criterio"],
	                'disabled' => 'disabled'
	            ),
	            'options' => array(
	                'label' => 'Criterio de evaluación:',
	            ),
	        ));


	        $EvaluarcriterioForm->add(array(
	            'name' => 'evaluacion_cualitativa',
	            'attributes' => array(
	                'type'  => 'textarea',
					'value' => $resultado[0]["evaluacion_cualitativa"],
	                'required' => 'required',
	                'max-length' => 10000
	            ),
	            'options' => array(
	                'label' => 'Evaluacion cualitativa:',
	            ),
	        ));

	        $EvaluarcriterioForm->add(array(
	            'name' => 'evaluacion_cuantitativa',
	            'attributes' => array(
	                'type'  => 'number',
					'value' => $resultado[0]["evaluacion_cuantitativa"],
	                'required' => 'required',
	                  'min'  => '0',
        			  'max'  => '5',
        			  'step' => '0.01'
	            ),
	            'options' => array(
	                'label' => 'Evaluacion cuantitativa:',
	            ),
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
			$pantalla="editarconvocatoriai";
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
			$view = new ViewModel(array('form'=>$EvaluarcriterioForm,
										'titulo'=>"Evaluación monitor",
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
}