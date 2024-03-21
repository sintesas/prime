<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Rolesusuario\RolesusuarioForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;

class RolesusuarioController extends AbstractActionController
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
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Rolesusuario($this->dbAdapter);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
			$data = $this->request->getPost();
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);

			$resultado=$u->addRolesusuario($id,$data,$u->getRolesusuario());
			if($resultado==0){

			$this->flashMessenger()->addMessage("El valor ingresado en la descripcion esta duplicado, en este o en otro rol");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/rolesusuario/index/'.$id);
			}else{
			$this->flashMessenger()->addMessage("El valor fue ingresado");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/rolesusuario/index/'.$id);
			}
			
		}else
		{
	    $this->dbAdapter=$this->getServiceLocator()->get('db1');
	    $u = new Rolesusuario($this->dbAdapter);
        $RolesusuarioForm = new RolesusuarioForm();
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}

			//define el campo usuario
			$vf = new Usuarios($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayusuarios() as $dat) {
			$op=array($dat["id_usuario"]=>$dat["primer_nombre"].$dat["primer_apellido"]);
			$opciones=$opciones+$op;
			}
			$RolesusuarioForm->add(array(
			'name' => 'id_usuario',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Usuarios: ',
            'value_options' => 
				$opciones
            ,
			),
			));
			
			$id = (int) $this->params()->fromRoute('id',0);
			
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
			$pantalla="rolesusuario";
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
		$us = new Usuarios($this->dbAdapter);
		$a=null;
		if(true){
		$view = new ViewModel(array('form'=>$RolesusuarioForm,
									'titulo'=>"Roles por Usuario", 
									'datos'=>$u->getRolesusuarioid($id),
									'datos2'=>$us->getUsuarios($a),
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
			$u = new Rolesusuario($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$u->eliminarRolesusuario($id);	
$this->flashMessenger()->addMessage("Quito el usuario del rol");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/roles/index');
	}
}
