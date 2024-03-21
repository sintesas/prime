<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarnoticias\EditarnoticiasForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Noticias;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class EditarnoticiasController extends AbstractActionController
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
			$EditarnoticiasForm = new EditarnoticiasForm();
			$id = (int) $this->params()->fromRoute('id',0);
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Noticias($this->dbAdapter);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
			$data = $this->request->getPost();
			//print_r($data);
			$resultado=$u->updateNoticias($id,$data);

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
			$urlId = "/application/noticias/index";
if ($resultado!=''){
				$resul=$au->getAuditoriaid(session_id(),get_real_ip(),$identi->id_usuario);
				$evento='Edicion de noticia : '.$resultado;
				$ad->addAuditoriadet( $evento,$resul);     

			$this->flashMessenger()->addMessage("Noticia actualizada con exito");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlId);
}else{
			$this->flashMessenger()->addMessage("La noticia no actualizo con exito");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlId);
}
		}else
		{
        $EditarnoticiasForm = new EditarnoticiasForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$us = new Noticias($this->dbAdapter);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
		
		$filter = new StringTrim();

			$us = new Noticias($this->dbAdapter);
			$id = (int) $this->params()->fromRoute('id',0);
			$dat=$us->getNoticiasid($id)[0];
			
			$EditarnoticiasForm->add(array(
            'name' => 'titulo',
            'attributes' => array(
                'type'  => 'text',
				'value'=>$filter->filter($dat["titulo"]),
            ),
            'options' => array(
                'label' => 'Título :',
            ),
			));
			
			$EditarnoticiasForm->add(array(
            'name' => 'noticia',
            'attributes' => array(
                'type'  => 'textarea',
				'value'=>$filter->filter($dat["noticia"]),
'lenght' => 1500,
            ),
            'options' => array(
                'label' => 'Noticia :',
            ),
			));
		
			$EditarnoticiasForm->add(array(
            'name' => 'url',
            'attributes' => array(
                'type'  => 'text',
				'value'=>$filter->filter($dat["url"]),
            ),
            'options' => array(
                'label' => 'Url :',
            ),
			));
			//define el campo activo
			$estado="";
			if ($dat["estado"]=='A' || $dat["estado"]=='a'){
			$estado= "Activo";
			}
			else {
			$estado= "Inactivo";
			}
			
			$EditarnoticiasForm->add(array(
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
			$pantalla="editarnoticias";
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
		$view = new ViewModel(array('form'=>$EditarnoticiasForm,
									'titulo'=>"Editar Noticias",
									'id'=>$id,
									'url'=>$this->getRequest()->getBaseUrl(),
									'datos'=>$us->getNoticiasid($id),
									'menu'=>$dat["id_rol"]));
		return $view;
		}
		else
		{
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/noticias/index');
		}
		}
    }
}
