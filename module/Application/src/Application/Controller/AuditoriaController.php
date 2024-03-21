<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Auditoria\AuditoriaForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use ZendPdf\Font as Font;
use ZendPdf\Page as Page;
use Zend\Filter\StringTrim;
use ZendPdf\PdfDocument;


class AuditoriaController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     //Cargamos el servicio de autenticacien el constructor
     $this->auth = new AuthenticationService();


	}
	
    public function indexAction()
    {
    	# Para pantallas accesadas por el menu, debo reiniciar el navegador
        session_start();
        if(isset($_SESSION['navegador'])){unset($_SESSION['navegador']);}
        
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
	    	$u = new Usuarios($this->dbAdapter);
        	$auth = $this->auth;
			$au= new Auditoria($this->dbAdapter);
			$data = $this->request->getPost();

			$filter = new StringTrim();

			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
        		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}
			$identi->id_usuario; 
			$rol = new Roles($this->dbAdapter);
			$rolusuario = new Rolesusuario($this->dbAdapter);
		 	$AuditoriaForm = new AuditoriaForm();

			$us = new Usuarios($this->dbAdapter);
			$a='';
			$opciones=array();
			foreach ($us->getUsuarios($a) as $dat) {
				$op=array($filter->filter($dat["id_usuario"]) => strtoupper($dat["primer_nombre"]).' '.strtoupper($dat["segundo_nombre"]).' '.strtoupper($dat["primer_apellido"]).' '.strtoupper($dat["segundo_apellido"]));
				$opciones=$opciones+$op;
			}
			$AuditoriaForm ->add(array('name' => 'usuario',
				'type' => 'Zend\Form\Element\Select',
				'attributes' => array('id' => 'usuario'),
				'required'=>'required',
				'size' => 25,
				'options' => array('label' => 'Filtro Usuario : ',
					'empty_option' => 'Seleccione el usuario',
					'value_options' => $opciones,
				),
			));

			$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$dat["id_rol"];
			}
			
			$id_usuariof=0;
			$idusuario=$u->getUsuarionombre($data);

			if ($idusuario!=1) {
				foreach($idusuario as $usuario){
					$id_usuariof=$usuario["id_usuario"];
				}
			}

			$view = new ViewModel(array('form'=>$AuditoriaForm,'titulo'=>"Auditoría", 'traer'=>1,'datos'=>$au->filtroAuditoria($data,$id_usuariof),'datos2'=>$u->getArrayusuarios(),'datos3'=>$rol->getRoles(),'menu'=>$dat["id_rol"]));
			return $view;
		}
		else{
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Usuarios($this->dbAdapter);
			$auth = $this->auth;

			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}
			$au= new Auditoria($this->dbAdapter);
			$AuditoriaForm = new AuditoriaForm();
	
			//verificar roles
			$per=array('id_rol'=>'');
			$dat=array('id_rol'=>'');
			$rol = new Roles($this->dbAdapter);
			$rolusuario = new Rolesusuario($this->dbAdapter);
			$permiso = new Permisos($this->dbAdapter);
			$filter = new StringTrim();


			$us = new Usuarios($this->dbAdapter);
			$a='';
			$opciones=array();
			foreach ($us->getUsuarios($a) as $dat) {
				$op=array($filter->filter($dat["id_usuario"]) => strtoupper($dat["primer_nombre"]).' '.strtoupper($dat["segundo_nombre"]).' '.strtoupper($dat["primer_apellido"]).' '.strtoupper($dat["segundo_apellido"]));
				$opciones=$opciones+$op;
			}
			$AuditoriaForm ->add(array('name' => 'usuario',
						'type' => 'Zend\Form\Element\Select',
				'attributes' => array(
					'id' => 'usuario',

				),
						'required'=>'required',
						'size' => 25,
						'options' => array('label' => 'Filtro Usuario : ',
						'empty_option' => 'Seleccione el usuario',
								'value_options' => $opciones,
						),
			));


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


			$id = '';
			if(true){
				$view = new ViewModel(array('form'=>$AuditoriaForm,'titulo'=>"Auditoría", 'datos'=>$au->getAuditoria(), 'datos2'=>$u->getUsuarios($id),'menu'=>$dat["id_rol"]));
				return $view;
			}
			else
			{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
			}
		}
    }

 	public function reportAction()
    {
		if ($this->getRequest()->isPost()){
			echo "post";
		}
		else{
			$id = $this->params()->fromRoute('id');
				
			$iparr = explode(",", $id); 

			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			// $au = new Auditoria($this->dbAdapter);
			// $u = new Usuarios($this->dbAdapter);
			// $ad = new Auditoriadet($this->dbAdapter);

			// $r='';
			// $view = new ViewModel(array('datos'=>$au->getAuditoriaids($iparr),'datos2'=>$ad->getAuditoriadetids($iparr),'datos3'=>$u->getArrayusuarios()));

			$ad = new Auditoriadet($this->dbAdapter);
			$view = new ViewModel(array('datos' => $ad->getAuditoriaDetReportes($iparr)));
			$view->setTerminal(true);
			return $view;
		}
    }


	public function pdfAction(){ 
		$pdf = Zend_Pdf::load($pdfPath);
	}

}
