<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editargrupoinv\GruposrelForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Integrantes;
use Application\Modelo\Entity\Gruposrel;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class GruposrelController extends AbstractActionController
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
		$id2 = (int) $this->params()->fromRoute('id2',0);
    	$id3 = (int) $this->params()->fromRoute('id3',0);
    	$GruposrelForm = new GruposrelForm();

    	$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Grupoinvestigacion($this->dbAdapter);
		$auth = $this->auth;

		$identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}

		$rol = new Roles($this->dbAdapter);
		$rolusuario = new Rolesusuario($this->dbAdapter);
		$rolusuario->verificarRolusuario($identi->id_usuario);
		foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
			$dat["id_rol"];
		}

		if($id3 != 0){
			if($this->getRequest()->isPost()){
				$u = new Gruposrel($this->dbAdapter);
				$upload = new \Zend\File\Transfer\Adapter\Http();
                $file_name = "";
                $error_file = false;
                print_r($id3); 
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
                            $registro = $u->getGruposById($id2);
                            if($registro != null){
                                unlink("public/images/uploads/gi/gruposr/gi_gr__".$id."_".$registro[0]["archivo"]);
                            }
                        }
                        $upload->setDestination('./public/images/uploads/gi/gruposr/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "gi_gr__".$id."_".$file_name,'overwrite' => true));
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
	                        ->getBaseUrl() . '/application/editargrupoinv/index/' . $id);
               	}
			}else{
				$view = new ViewModel(
					array(
						'form'=>$GruposrelForm,
						'titulo'=>"Grupos relacionados o colaboración entre grupos - Cargar archivo",
						'url'=>$this->getRequest()->getBaseUrl(),
						'id'=>$id,
						'id2'=>$id2,
						'id3'=>$id3,
						'menu'=>$dat["id_rol"]
					)
				);	
			}
			return $view;
		}else{
			if($this->getRequest()->isPost()){
				$data = $this->request->getPost();
				//print_r($data);
				$id = (int) $this->params()->fromRoute('id',0);
				$id2 = (int) $this->params()->fromRoute('id2',0);
				
				$r='';
				$view = new ViewModel(
					array(
						'form'=>$GruposrelForm,
						'titulo'=>"Asignar Grupo Relacionado",
						'grupo'=>$id,
						'datos'=>$u->filtroGrupos($data),
						'menu'=>$dat["id_rol"]
					)
				);
				return $view;
			}else
			{
		    $this->dbAdapter=$this->getServiceLocator()->get('db1');
		    $u = new Rolesusuario($this->dbAdapter);
	        $GruposrelForm = new GruposrelForm();
	        $auth = $this->auth;

	        $identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
	        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

				
				//define el campo ciudad
				$vf = new Roles($this->dbAdapter);
				$opciones=array();
				foreach ($vf->getRoles() as $dat) {
				$op=array($dat["id_rol"]=>$dat["descripcion"]);
				$opciones=$opciones+$op;
				}
				$GruposrelForm->add(array(
				'name' => 'roles',
				'type' => 'Zend\Form\Element\Select',
				'options' => array(
	            'label' => 'Roles: ',
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
				$pantalla="integranteshv";
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
			$us = new Grupoinvestigacion($this->dbAdapter);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);

			$a=null;
			if(true){
			$view = new ViewModel(array('form'=> $GruposrelForm,
										'titulo'=>"Grupos relacionados o colaboración entre grupos", 
										'datos'=>$us->getGrupoinvestigacion(),
										'grupo'=>$id,
										'id_rol'=>$id,
										'id2'=>$id2,
										'roles2'=>$vf->getRoles(),
										'rolesusuario'=>$rolusuario->getRolesusuario(),
										'roles'=>$vf->getRolesid($id),
										'menu'=>$dat["id_rol"]));
			
			}
			else
			{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
			}
			return $view;	
			}
		}
    	
		
    }

	public function  asignarAction(){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Gruposrel($this->dbAdapter);
$data = $this->request->getPost();
$id = (int) $this->params()->fromRoute('id',0);
$id2 = (int) $this->params()->fromRoute('id2',0);
			$id3 = (int) $this->params()->fromRoute('id3',0);
			if($id3==0){
	            $resul = $u->addGruposrel($id,$id2);	
	        }else{
	            $resul = $u->updateGruposrel($id3, $id, "");
	        }

if($resul==1){
$this->flashMessenger()->addMessage("Asigno el Grupo");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id2);
}else{
$this->flashMessenger()->addMessage("El Usuario ya tiene un rol asignado");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id2);
}
	}

	public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Gruposrel($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getGruposById($id2);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/gi/gruposr/gi_gr__".$id."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/gi/gruposr/gi_gr__".$id."_".$filter->filter($fileName)));
    	$response->setHeaders($headers);
        return $response;
    }


	public function eliminarAction(){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Gruposrel($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
$id2 = (int) $this->params()->fromRoute('id2',0);
			$u->eliminarGruposrel($id);
$this->flashMessenger()->addMessage("Grupo relacionado eliminado con exito");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id2);
			
	}

	public function delAction(){
$GruposrelForm = new GruposrelForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Gruposrel($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);


			$view = new ViewModel(array('form'=>$GruposrelForm,
										'titulo'=>"Eliminar registro",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$id,'id2'=>$id2));
			return $view;

	}


}