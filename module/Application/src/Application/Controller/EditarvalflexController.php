<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarvalflex\EditarvalflexForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Zend\Filter\StringTrim;

class EditarvalflexController extends AbstractActionController
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
		if($this->getRequest()->isPost()){
			$EditarvalflexForm = new EditarvalflexForm();
			$id = (int) $this->params()->fromRoute('id',0);
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Agregarvalflex($this->dbAdapter);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
			$data = $this->request->getPost();
			//print_r($data);
			$u->updateValflex($id,$data);
			$urlId = "/application/editarvalflex/index/".$id;
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlId);
		}else
		{
        $EditarvalflexForm = new EditarvalflexForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Agregarvalflex($this->dbAdapter);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
		
		$filter = new StringTrim();

			$us = new Agregarvalflex($this->dbAdapter);
			$id = (int) $this->params()->fromRoute('id',0);
			foreach ($us->getvalflexseditar($id) as $dat) {
			$dat["id_valor_flexible"];
			}
		
			$EditarvalflexForm->add(array(
            'name' => 'descripcion_valor',
            'attributes' => array(
                'type'  => 'text',
				'value'=>$filter->filter($dat["descripcion_valor"]),
            ),
            'options' => array(
                'label' => 'Descripción:',
            ),
			));
			
			$EditarvalflexForm->add(array(
            'name' => 'sigla_valor',
            'attributes' => array(
                'type'  => 'text',
				'value'=>$filter->filter($dat["sigla_valor"]),
            ),
            'options' => array(
                'label' => 'Sigla :',
            ),
			));
		
			//define el campo activo
			if ($dat["activo"]='s' || $dat["activo"]='S'){
			$estado= 'Si';
			}
			else {
			$estado='No';
			}
			
			$EditarvalflexForm->add(array(
            'name' => 'estado',
            'attributes' => array(
                'type'  => 'text',
				'value'=> $estado,
				'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Estado:',
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
			$pantalla="editarvalflex";
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
		$view = new ViewModel(array('form'=>$EditarvalflexForm,
									'titulo'=>"Editar Valores Flexibles",
									'id'=>$id,
									'url'=>$this->getRequest()->getBaseUrl(),
									'datos'=>$u->getValoresflexid($id),
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
