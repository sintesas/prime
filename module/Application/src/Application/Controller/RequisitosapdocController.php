<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\RequisitosapdocForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Requisitosapdoc;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class RequisitosapdocController extends AbstractActionController
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
			$not = new Requisitosapdoc($this->dbAdapter);
			$auth = $this->auth;

			//verifica si esta conectado
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			$au = new Auditoria($this->dbAdapter);
			$ad = new Auditoriadet($this->dbAdapter);

			$rol = new Roles($this->dbAdapter);
			$rolusuario = new Rolesusuario($this->dbAdapter);
		 	$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$dat["id_rol"];
			}
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$id3 = (int) $this->params()->fromRoute('id3',0);
			$id4 = (int) $this->params()->fromRoute('id4',0);
			$id5 = $this->params()->fromRoute('id5',0);
			$data = $this->request->getPost();
			
			if($id3!=0){
				$resultado=$not->updateRequisitosdoc($id,null,$data);
				
				$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
				$evento = 'Crear/Edición de documentos requeridos del proyecto : (mgc_requisitosap_doc)';
				$ad->addAuditoriadet($evento, $resul);

				$this->flashMessenger()->addMessage("Estado modificado con éxito.");
				if($id5 == "0"){		
					return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/gestionproym/index/'.$id2.'/'.$id4);
				}else{
					return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/gestionproy/index/'.$id2);
				}	
			}else{

				$upload = new \Zend\File\Transfer\Adapter\Http();
				$upload->setDestination('./public/images/uploads/');
				$files = $upload->getFileInfo();
				foreach($files as $f){
					$archi=$f["name"];
				}

				$upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "dr_".$id."_".$id2."_".$archi,'overwrite' => true));
				$upload->setValidators(array(
				    'Size'  => array('min' => 1, 'max' => 26214400),
				));
				
				if($upload->isValid()){
					$rtn = array('success' => null);
			       	$rtn['success'] = $upload->receive();
				         
					$resultado=$not->updateRequisitosdoc($id,$archi,$data);

					$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
					$evento = 'Crear/Edición de documentos requeridos del proyecto : (mgc_requisitosap_doc)';
					$ad->addAuditoriadet($evento, $resul);

					$this->flashMessenger()->addMessage("Archivo cargado con éxito.");
					if($id5 == "0"){
						return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicarm/index/'.$id2.'/0/0');
					}else if($id5 == "5"){
						return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicar/index/'.$id2.'/aplicar');
					}else{
						return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicari/index/'.$id2.'/aplicar');
					}
				}else{
					$this->flashMessenger()->addMessage("Falla en la carga del archivo. Supera las 25MB de tamaño.");
					if($id5 == "0"){
						return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicarm/index/'.$id2.'/0/0');
					}else if($id5 == "5"){
						return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicar/index/'.$id2.'/aplicar');
					}else{
						return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicari/index/'.$id2.'/aplicar');
					}
				}
			}
		}
		else
		{
			$RequisitosapdocForm = new RequisitosapdocForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Requisitosapdoc($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			$filter = new StringTrim();


			//define el campo ciudad
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(24) as $dept) {
			$op=array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$RequisitosapdocForm->add(array(
			'name' => 'id_tipo_archivo',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Tipo de Archivo : ',
            'value_options' => 
				$opciones
            ,
			),
			)); 

			//define el campo pais
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(20) as $pa) {
			$op=array($pa["id_valor_flexible"]=>$pa["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$RequisitosapdocForm->add(array(
			'name' => 'id_pais',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Pais : ',
            'value_options' => 
				$opciones
            ,
			),
			)); 


			//define el campo pais
			$usuario = new Usuarios($this->dbAdapter);
			$opciones=array();
			$a='';
			foreach ($usuario->getUsuarios($a) as $pa) {
			$op=array($pa["id_usuario"]=>$pa["primer_nombre"]);
			$opciones=$opciones+$op;
			}
			$RequisitosapdocForm->add(array(
			'name' => 'id_autor',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Autor : ',
            'value_options' => 
				$opciones
            ,
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
			$pantalla="editargrupoinv";
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
			$id2 = (int) $this->params()->fromRoute('id2',0);			
			$id3 = (int) $this->params()->fromRoute('id3',0);
			$id4 = (int) $this->params()->fromRoute('id4',0);
			$id5 = $this->params()->fromRoute('id5',0);

			if($id5 != ""){
				$titulo = "Documentos requeridos para la convocatoria";
			}else{
				$titulo = "Documentos requeridos para la Monitoría/Convocatoria";
			}

			$view = new ViewModel(array('form'=>$RequisitosapdocForm,
										'titulo'=> $titulo,
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$u->getRequisitosapdocid($id),
										'id'=>$id,
										'id3'=>$id3,
										'id2'=>$id2,										
										'id4'=>$id4,
										'id5'=>$id5,
										'menu'=>$dat["id_rol"]
									));
			return $view;
			}
			else
			{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
			}
		}
    }
	

	
	public function bajarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Requisitosapdoc($this->dbAdapter);
		$filter = new StringTrim();
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);

		$data=$u->getRequisitosapdocid($id);

		foreach($data as $d){
			$fileName = $d["archivo"];
			$new_archivo = $d["new_archivo"]; 
		}

		$response = new \Zend\Http\Response\Stream();
		if($new_archivo == ""){
			$response->setStream(fopen("public/images/uploads/".$filter->filter($fileName), 'r'));	
		    $response->setStatusCode(200);
		    $headers = new \Zend\Http\Headers();
		    $headers->addHeaderLine('Content-Type', 'whatever your content type is')
		            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
		            ->addHeaderLine('Content-Length', filesize("public/images/uploads/".$filter->filter($fileName)));

		}else{
			$response->setStream(fopen("public/images/uploads/dr_".$id."_".$id2."_".$filter->filter($fileName), 'r'));
			$response->setStatusCode(200);
		    $headers = new \Zend\Http\Headers();
		    $headers->addHeaderLine('Content-Type', 'whatever your content type is')
		            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
		            ->addHeaderLine('Content-Length', filesize("public/images/uploads/dr_".$id."_".$id2."_".$filter->filter($fileName)));
		}		
	    
	    $response->setHeaders($headers);
    	return $response;
	}

	public function verArchivoAction(){

		$this->dbAdapter=$this->getServiceLocator()->get('db1');

		$u = new Requisitosapdoc($this->dbAdapter);
		$filter = new StringTrim();
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);

		$data=$u->getRequisitosapdocid($id);

		foreach($data as $d){
			$fileName = $d["archivo"];
			$new_archivo = $d["new_archivo"]; 
		}

		$response = new \Zend\Http\Response\Stream();

		if($new_archivo == ""){
			if(strpos(mb_strtoupper($filter->filter($fileName)), ".PDF")!==false){
				header('Content-type: application/pdf');
			}elseif(strpos(mb_strtoupper($filter->filter($fileName)), ".PNG")!==false){
				header('Content-type: image/png');
			}else{
				header('Content-type: image/jpeg');
			}
			
			header('Content-Disposition: inline; filename="' . $filter->filter($fileName) . '"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . filesize("public/images/uploads/".$filter->filter($fileName)));
			header('Accept-Ranges: bytes');
			@readfile("public/images/uploads/".$filter->filter($fileName));
			exit();
		}else{
			if(strpos(mb_strtoupper($filter->filter($fileName)), ".PDF")!==false){
				header('Content-type: application/pdf');
			}elseif(strpos(mb_strtoupper($filter->filter($fileName)), ".PNG")!==false){
				header('Content-type: image/png');
			}else{
				header('Content-type: image/jpeg');
			}
			header('Content-Disposition: inline; filename="' . $filter->filter($fileName) . '"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . filesize("public/images/uploads/dr_".$id."_".$id2."_".$filter->filter($fileName)));
			header('Accept-Ranges: bytes');
			@readfile("public/images/uploads/dr_".$id."_".$id2."_".$filter->filter($fileName));
			exit();
		}
	}
}