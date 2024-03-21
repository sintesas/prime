<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Permisos\PermisosForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

use Application\Modelo\Entity\Modulo;
use Application\Modelo\Entity\Submodulo;
use Application\Modelo\Entity\Formulario;
use Application\Modelo\Entity\Roles;


class PermisosController extends AbstractActionController
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
    	
		$PermisosForm = new PermisosForm();
    	$modulo = new Modulo($this->dbAdapter);
    	$submodulo = new Submodulo($this->dbAdapter);
    	$formulario = new Formulario($this->dbAdapter);
    	
    	$roles = new Roles($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);

        if($id==1){
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/roles/index');
        }
		
		$modulos=$modulo->geModulosi();
        $opciones=array();
        foreach ($modulos as $mod) {
            $opciones=$opciones+array($mod["id"]=>$mod["nombre"]);
        }
        $PermisosForm->get('id_modulo')->setValueOptions($opciones);

        $submodulos=$submodulo->getSubmodulosi();
        $opciones=array();
        foreach ($submodulos as $mod) {
            $opciones=$opciones+array($mod["id"].'-'.$mod["id_modulo"]=>$mod["nombre"]);
        }
        $PermisosForm->get('id_submodulo')->setValueOptions($opciones);

        $formularios=$formulario->getFormularioi();
        $opciones=array();
        foreach ($formularios as $mod) {
            $opciones=$opciones+array($mod["id"].'-'.$mod["id_submodulo"]=>$mod["nombre"]." - ".$mod["descripcion"]);
        }
        $PermisosForm->get('id_formulario')->setValueOptions($opciones);
        if($this->getRequest()->isPost()){
        	$data = $this->request->getPost();
        	$resultado=$permisos->addPermisos($data,$id);
        	if($resultado==0){
	        	$this->flashMessenger()->addMessage("El permiso ya existía.");
			}else{
	        	$this->flashMessenger()->addMessage("El permiso se agregó correctamente.");
			}
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/permisos/index/'+$id);
        }
        //me verifica el tipo de rol asignado al usuario
        $rolusuario = new Rolesusuario($this->dbAdapter);
        $identi=$auth->getStorage()->read();
		$rolusuario->verificarRolusuario($identi->id_usuario);
		foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
			$dat["id_rol"];
		}
		
        $view = new ViewModel(array(
        	'form'=>$PermisosForm,
        	'url' => $this->getRequest()->getBaseUrl(),
			'titulo'=>"Gestionar permisos sobre formularios", 
			'id' => $id,
			'modulos' => $modulos,
			'submodulos' => $submodulos, 
			'formularios' => $formularios, 
			'permisos' => $permisos->getPermisorol($id), 
			'rol' => $roles->getRolesid($id),
			'menu'=>$dat["id_rol"],
			'resulPer' => $resultadoPermiso
		));
		return $view;
    }

	public function eliminarAction(){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$permisos = new Permisos($this->dbAdapter);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$permisos->eliminarPermisos($id);	
			$this->flashMessenger()->addMessage("Permiso eliminado con éxito.");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/permisos/index/'.$id2);
	}
}
