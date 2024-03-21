<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarusuario\ProyectosintusuaForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Proyectosintusua;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class ProyectosintusuaController extends AbstractActionController
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
        
        $ProyectosintusuaForm = new ProyectosintusuaForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Proyectosintusua($this->dbAdapter);
		$pt = new Agregarvalflex($this->dbAdapter);
		$auth = $this->auth;

		$identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}

		$filter = new StringTrim();

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
			$pantalla="editarredinv";
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
		
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2', 0);
		$id3 = (int) $this->params()->fromRoute('id3',0);

		if($id3 != 0){
			if($this->getRequest()->isPost()){
				$u = new Proyectosintusua($this->dbAdapter);
				$upload = new \Zend\File\Transfer\Adapter\Http();
                $file_name = "";
                $error_file = false;
                if($upload->isUploaded()){
                    //Archivo cargado
                    $upload->setValidators(array(
                        'Size'  => array('min' => 1, 'max' => 50000000),
                    ));
                    if($upload->isValid()){
                        $files = $upload->getFileInfo();
                        foreach($files as $f){
                            $file_name=$f["name"];
                        }
                        if($id2!=0){
                            $registro = $u->getProyectointusuaById($id2);
                            if($registro != null){
                                unlink("public/images/uploads/hv/proyectosi/hv_pi__".$id."_".$registro[0]["archivo"]);
                            }
                        }
                        $upload->setDestination('./public/images/uploads/hv/proyectosi/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "hv_pi__".$id."_".$file_name,'overwrite' => true));
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resul=$u->updateArchivo($id2, $file_name);
                    }else{
                        $error_file = true;
                    }
                    if($error_file){
						$this->flashMessenger()->addMessage("Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }else{
	                    $this->flashMessenger()->addMessage("Archivo cargado exitosamente");
                    }
	                return $this->redirect()->toUrl($this->getRequest()
	                        ->getBaseUrl() . '/application/editarusuario/index/' . $id);
               	}
			}else{
				$view = new ViewModel(
					array(
						'form'=>$ProyectosintusuaForm,
						'titulo'=>"Proyectos de investigación internos - Cargar archivo",
						'url'=>$this->getRequest()->getBaseUrl(),
						'id'=>$id,
						'id2'=>$id2,
						'id3'=>$id3,
						'menu'=>$dat["id_rol"]
					)
				);	
			}
		}else{
			$data = null; 
		
			if($this->getRequest()->isPost()){
				$data = $this->request->getPost();
			}
			
			$redinv = new Proyectos($this->dbAdapter);

			$view = new ViewModel(
				array(
					'form'=>$ProyectosintusuaForm,
					'titulo'=>"Proyectos de investigación internos",
					'url'=>$this->getRequest()->getBaseUrl(),
					'datos'=>$redinv->filtroProyectos($data),
					'id'=>$id,
					'id2'=>$id2,
					'menu'=>$dat["id_rol"]
				)
			);
		}
		return $view;
    }

    public function  asignarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Proyectosintusua($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$id3 = (int) $this->params()->fromRoute('id3',0);
		if($id3!=0){
			$resultado = $u->updateProyectointusua($id3, $id, "");
			$evento = 'Edición de proyectos de investigación internos : ' . $resultado . ' (aps_hv_proyectosint)';
		}else{
		    $resultado = $u->addProyectos($id2, $id);
			$evento = 'Creación de proyectos de investigación internos : ' . $resultado . ' (aps_hv_proyectosint)';
		}

		function get_real_ip()
		{
			if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				return $_SERVER["HTTP_CLIENT_IP"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_X_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
				return $_SERVER["HTTP_X_FORWARDED"];
			} elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_FORWARDED"])) {
				return $_SERVER["HTTP_FORWARDED"];
			} else {
				return $_SERVER["REMOTE_ADDR"];
			}
		}

		$au = new Auditoria($this->dbAdapter);
		$ad = new Auditoriadet($this->dbAdapter);

		$auth = $this->auth;
		$identi=$auth->getStorage()->read();

		if($resul==1){
			$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
			$ad->addAuditoriadet($evento, $resul);
			$this->flashMessenger()->addMessage("Proyecto asignado con éxito.");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarusuario/index/'.$id2);
		}else{
			$this->flashMessenger()->addMessage("La asignación del proyecto falló.");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarusuario/index/'.$id2);
		}
    }

    public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Proyectosintusua($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getProyectointusuaById($id2);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/hv/proyectosi/hv_pi__".$id."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/hv/proyectosi/hv_pi__".$id."_".$filter->filter($fileName)));
    	$response->setHeaders($headers);
        return $response;
    }
	
	public function eliminarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Proyectosintusua($this->dbAdapter);
		//print_r($data);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$u->eliminarProyecto($id);

		function get_real_ip()
		{
			if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				return $_SERVER["HTTP_CLIENT_IP"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_X_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
				return $_SERVER["HTTP_X_FORWARDED"];
			} elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
				return $_SERVER["HTTP_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_FORWARDED"])) {
				return $_SERVER["HTTP_FORWARDED"];
			} else {
				return $_SERVER["REMOTE_ADDR"];
			}
		}

		$auth = $this->auth;
		$identi=$auth->getStorage()->read();

		$au = new Auditoria($this->dbAdapter);
		$ad = new Auditoriadet($this->dbAdapter);

		$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
		$evento = 'Eliminación de proyectos de investigación internos : ' . $id . ' (aps_hv_proyectosint)';
		$ad->addAuditoriadet($evento, $resul);


		$this->flashMessenger()->addMessage("Red eliminada con exito");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarusuario/index/'.$id2);
			
	}

	public function delAction(){
		$ProyectosintusuaForm = new ProyectosintusuaForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Proyectosintusua($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$view = new ViewModel(
			array(
				'form'=>$ProyectosintusuaForm,
				'titulo'=>"Eliminar registro",
				'url'=>$this->getRequest()->getBaseUrl(),
				'datos'=>$id,
				'id2'=>$id2
			)
		);
		return $view;

	}


}