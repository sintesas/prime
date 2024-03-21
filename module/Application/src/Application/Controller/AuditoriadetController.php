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
use Application\Auditoriadet\AuditoriadetForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;

class AuditoriadetController extends AbstractActionController
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
	    	$u = new Usuarios($this->dbAdapter);
        		$auth = $this->auth;
		$au= new Auditoria($this->dbAdapter);
			$data = $this->request->getPost();

$identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
		$identi->id_usuario; 
		$rol = new Roles($this->dbAdapter);
		$rolusuario = new Rolesusuario($this->dbAdapter);
		 $AuditoriadetForm = new AuditoriadetForm();
		$rolusuario->verificarRolusuario($identi->id_usuario);
		foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
			$dat["id_rol"];
		}
		//exit;
		$view = new ViewModel(array('form'=>$AuditoriadetForm,'titulo'=>"Auditoría detalle", 'datos'=>$au-> filtroAuditoria($data),'datos2'=>$u->getArrayusuarios(),'datos3'=>$rol->getRoles(),'menu'=>$dat["id_rol"]));
		return $view;
		}else{
       $AuditoriadetForm = new AuditoriadetForm();
	    $this->dbAdapter=$this->getServiceLocator()->get('db1');
	    $u = new Usuarios($this->dbAdapter);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
				$au= new Auditoria($this->dbAdapter);
 $ad= new Auditoriadet($this->dbAdapter);
		
		//verificar roles
		$per=array('id_rol'=>'');
		$dat=array('id_rol'=>'');
		$rol = new Roles($this->dbAdapter);
		$rolusuario = new Rolesusuario($this->dbAdapter);
		$permiso = new Permisos($this->dbAdapter);
		
		//me verifica el tipo de rol asignado al usuario
		$rolusuario->verificarRolusuario($identi->id_usuario);
		foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
			$dat["id_rol"];
		}
		
		if ($dat["id_rol"]!= ''){
			//me verifica el permiso sobre la pantalla
			$pantalla="auditoria";
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

		$id2='';
		$id = (int) $this->params()->fromRoute('id',0);
		if(true){
		$view = new ViewModel(array('form'=>$AuditoriadetForm,'id_p'=>$id,'titulo'=>"Auditoría Detalle", 'datos'=>$au->getAuditoria(), 'datos2'=>$u->getArrayusuarios($id2), 'datos3'=>$ad->getAuditoriadetid($id),'menu'=>$dat["id_rol"]));
		return $view;
		}
		else
		{
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
		}
		}
    }
}
