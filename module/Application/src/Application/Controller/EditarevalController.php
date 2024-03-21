<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\EditarevalForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Asignareval;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Aplicar;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Zend\Filter\StringTrim;


class EditarevalController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     //Cargamos el servicio de autenticación en el constructor
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
			$EditarevalForm= new EditarevalForm();
			$id = (int) $this->params()->fromRoute('id',0);
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Asignareval($this->dbAdapter);
	        $auth = $this->auth;

    	    $identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
        		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}
			$data = $this->request->getPost();
			$u->updateasignareval($id,$data);

			if($data["descripcion"]!=null){
				$urlId = "/application/evaluarproy/index";
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlId);
				$this->flashMessenger()->addMessage("Concepto aplicado con exito");
			}
			$usuario = new Usuarios($this->dbAdapter);
			$aplicar = new Aplicar($this->dbAdapter);
			$correo=$usuario->getUsuariosid($id2);
			$ap=$aplicar->getAplicarid($id3);
			//Se debe habilitar nuevamente
			//$u->enviarcorreoEval($correo["email"],$ap["nombre_proy"], $data);
			$urlId = "/application/gestionrequi/index/".$ap["id_convocatoria"];
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlId);
			$this->flashMessenger()->addMessage("Evaluador editado con Exito");
		}else{
        	$EditarevalForm= new EditarevalForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Agregarvalflex($this->dbAdapter);
        	$auth = $this->auth;
	        $identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
        		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}
			$filter = new StringTrim();
			$us = new Asignareval($this->dbAdapter);
			$id2 = $this->params()->fromRoute('id2');
			$id3 = (int) $this->params()->fromRoute('id3',0);
			$id = (int) $this->params()->fromRoute('id',0);
			$EditarevalForm->add(array(
            			'name' => 'fecha_maxima',
		            	'attributes' => array(
                		'type'  => 'Date',
						'size'=> 15,        			),
            			'options' => array(
                			'label' => 'Fecha maxima de evaluación:',
            			),
			));
			$concepto = "";
			foreach ($us->getAsignarevalt() as $evalT) {
				if($evalT["id_evaluador"] == $id){
					$concepto = $evalT["descripcion"];
				}
			}

	        $EditarevalForm->add(array(
	            'name' => 'descripcion',
	            'attributes' => array(
	                'type'  => 'textarea',
					'placeholder'=>'Ingrese el concepto de evaluación',
					'value' => trim($concepto),
	            ),
	            'options' => array(
	                'label' => 'Concepto global:',
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
			$pantalla="tablaequipo";
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
		$view = new ViewModel(array('form'=>$EditarevalForm,
									'titulo'=>"Editar evaluador del proyecto",
									'id'=>$id,
									'id2'=>$id2,
									'id3'=>$id3,
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
