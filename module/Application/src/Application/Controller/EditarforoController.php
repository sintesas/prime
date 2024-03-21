<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarforo\EditarforoForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Foro;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class EditarforoController extends AbstractActionController
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
    	
	    $id = (int) $this->params()->fromRoute('id',0);
		if($this->getRequest()->isPost()){
			$EditarforoForm = new EditarforoForm();
			$id = (int) $this->params()->fromRoute('id',0);
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Foro($this->dbAdapter);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
			$data = $this->request->getPost();
			//print_r($data);
			$resultado=$u->updateForo($id,$data);
			$urlId = "/application/foro/index";


			$au = new Auditoria($this->dbAdapter);
			$ad = new Auditoriadet($this->dbAdapter);		  
function get_real_ip()
    {
        if (isset($_SERVER["HTTP_CLIENT_IP"]))
        {
            return $_SERVER["HTTP_CLIENT_IP"];
        }
        elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
        {
            return $_SERVER["HTTP_X_FORWARDED"];
        }
        elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
        {
            return $_SERVER["HTTP_FORWARDED_FOR"];
        }
        elseif (isset($_SERVER["HTTP_FORWARDED"]))
        {
            return $_SERVER["HTTP_FORWARDED"];
        }
        else
        {
            return $_SERVER["REMOTE_ADDR"];
        }
    }

if ($resultado!=''){
	$resul=$au->getAuditoriaid(session_id(),get_real_ip(),$identi->id_usuario);
				$evento='Edicion de foro : '.$resultado;
				$ad->addAuditoriadet( $evento,$resul);   

			$this->flashMessenger()->addMessage("Tema actualizado con exito");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlId);
}else{
			$this->flashMessenger()->addMessage("El tema no actualizo con exito");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlId);
}
		}else
		{
        $EditarforoForm = new EditarforoForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$us = new Foro($this->dbAdapter);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
		
		$filter = new StringTrim();

			$us = new Foro($this->dbAdapter);
			$id = (int) $this->params()->fromRoute('id',0);
			foreach ($us->getForoid($id) as $dat) {
			$dat["id_foro"];
			}
		
			$EditarforoForm->add(array(
            'name' => 'titulo',
            'attributes' => array(
                'type'  => 'text',
				'value'=>$filter->filter($dat["titulo"]),
            ),
            'options' => array(
                'label' => 'Título :',
            ),
			));
			
			$EditarforoForm->add(array(
            'name' => 'mensaje',
            'attributes' => array(
                'type'  => 'text',
				'value'=>$filter->filter($dat["mensaje"]),
            ),
            'options' => array(
                'label' => 'Mensaje :',
            ),
			));


		
			//define el campo activo
			if ($dat["estado"]=='H' || $dat["estado"]=='h'){
			$estado= 'Habilitado';
			}
			elseif ($dat["estado"]=='D' || $dat["estado"]=='d'){
			$estado='Desabilitado';
			}
			
			$EditarforoForm->add(array(
            'name' => 'estadoOp',
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
			$pantalla="editarforo";
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
		$view = new ViewModel(array('form'=>$EditarforoForm,
									'titulo'=>"Editar Tema",
									'id'=>$id,
									'url'=>$this->getRequest()->getBaseUrl(),
									'datos'=>$us->getForoid($id),
									'menu'=>$dat["id_rol"]));
		return $view;
		}
		else
		{
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/foro/index');
		}
		}
    }
}
