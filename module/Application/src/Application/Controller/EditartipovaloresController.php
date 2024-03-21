<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editartipovalores\EditartipovaloresForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Tipovalores;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;

class EditartipovaloresController extends AbstractActionController
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
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Tipovalores($this->dbAdapter);
			$auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
			$data = $this->request->getPost();
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$u->updateTipovalores($id,$data);
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/tipovalores/index');
		}else
		{
        $EditartipovaloresForm = new EditartipovaloresForm;
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Tipovalores($this->dbAdapter);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
		$filter = new StringTrim();

			$us = new Tipovalores($this->dbAdapter);
			$id = (int) $this->params()->fromRoute('id',0);
			foreach ($us->getTipovaloreseditar($id) as $dat) {
			$dat["id_tipo_valor"];
			}
$idvalorpadre=$dat["id_tipo_valor_padre"];
		
			$EditartipovaloresForm->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type'  => 'text',
				'value'=>$filter->filter($dat["descripcion"]),
            ),
            'options' => array(
                'label' => 'Descripción :',
            ),
			));
		
			//define el campo activo
			if ($dat["activo"]='s' || $dat["activo"]='S'){
			$estado= 'Si';
			}
			else {
			$estado='No';
			}
			$EditartipovaloresForm->add(array(
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
		
			//define el campo valor padre
			$opz='';
			$vf = new Tipovalores($this->dbAdapter);
			$opciones=array('0'=>'Seleccione el tipo valor padre');
			foreach ($vf->getArraytipos() as $dat) {
			$op=array($dat["id_tipo_valor"]=>$dat["descripcion"]);
			$opciones=$opciones+$op;
			}
			foreach ($vf->getTipdefeditar($idvalorpadre) as $datz) {
			$opz=$datz["descripcion"];
			}
			$EditartipovaloresForm->add(array(
            'name' => 'valor_padre',
            'attributes' => array(
                'type'  => 'text',
				'value'=>$filter->filter($opz),
				'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Valor Padre:',
            ),
			));
			
			$EditartipovaloresForm->add(array(
			'name' => 'id_tipo_valor_padre',
			'type' => 'Zend\Form\Element\Select',

			'options' => array(
            'label' => 'Valor Padre: ',
            'value_options' => 
				$opciones
            ,
			),
			'attributes' => array(
                'value' => '0' //set selected to '1'
            ),
			));
		
			//define el campo valorpadre
			
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
			$pantalla="editartipovalores";
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
		$view = new ViewModel(array('form'=>$EditartipovaloresForm,
									'titulo'=>"Editar Tipos Valores",
									'titulo2'=>"Tipos Valores Existentes", 
									'url'=>$this->getRequest()->getBaseUrl(),
									'datos'=>$u->getTipovaloreid($id),
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
