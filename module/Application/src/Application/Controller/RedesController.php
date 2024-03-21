<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editargrupoinv\RedesForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Redinvestigacion;
use Application\Modelo\Entity\Redes;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;

class RedesController extends AbstractActionController
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
			$not = new Redes($this->dbAdapter);
			$auth = $this->auth;

			//verifica si esta conectado
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


			//obtiene la informacion de las pantallas
			$data = $this->request->getPost();
			$RedesForm = new RedesForm();
			//adiciona la noticia
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);

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
						$registro = $not->getRedesById($id2);
	                    if($registro != null){
	                        unlink("public/images/uploads/gi/redes/gi_ri__".$id."_".$registro[0]["archivo"]);
	                    }
                    }
                    $upload->setDestination('./public/images/uploads/gi/redes/');
                    $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "gi_ri__".$id."_".$file_name,'overwrite' => true));
                    $rtn = array('success' => null);
                    $rtn['success'] = $upload->receive();
                }else{
                	$error_file = true;
                }
			}

			if($id2==0){
				$resultado=$not->addRedes($data,$id, $file_name);
			}else{
				$resultado=$not->updateRedes($id2, $data, $file_name);
			}
			
			//redirige a la pantalla de inicio del controlador
			if ($resultado==1){
				if($error_file){
					$this->flashMessenger()->addMessage("Red creada sin archivo adjunto. Recuerde que el tamaño máximo del archivo es de 25MB.");
				}else{
					$this->flashMessenger()->addMessage("Red creada con exito");
				}
			}else
			{
				$this->flashMessenger()->addMessage("La creacion de la red fallo");
			}
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id);
		}
		else
		{
			$RedesForm = new RedesForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Redes($this->dbAdapter);
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
			$redinv = new Redinvestigacion($this->dbAdapter);
			$opciones=array();
			$op=array(''=>'Seleccione una red');
			foreach ($redinv->getRedinv() as $red) {
				$op=$op+array($red["id"]=>$red["nombre"]);
				$opciones=$opciones+$op;
			}

			$RedesForm->get('nombre_red')->setValueOptions($opciones);

			if(true){
				$id = (int) $this->params()->fromRoute('id',0);
				$id2 = (int) $this->params()->fromRoute('id2', 0);
				if($id2!=0){
					$datBase = $u->getRedesById($id2);
					if($datBase!=null){
						$RedesForm->get('nombre_red')->setValue($datBase[0]["id_red"]);
						$RedesForm->get('submit')->setValue("Actualizar");
					}
				}

				$view = new ViewModel(
					array(
						'form'=>$RedesForm,
						'titulo'=>"Redes de Investigación",
						'url'=>$this->getRequest()->getBaseUrl(),
						'datos'=>$u->getRedes($id),
						'id'=>$id,
						'id2'=>$id2,
						'menu'=>$dat["id_rol"]
					)
				);
				return $view;
			}else{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
			}
		}
    }
	
	public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Redes($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getRedesById($id2);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/gi/redes/gi_ri__".$id."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/gi/redes/gi_ri__".$id."_".$filter->filter($fileName)));
    	$response->setHeaders($headers);
        return $response;
    }

	public function eliminarAction(){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Redes($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
$id2 = (int) $this->params()->fromRoute('id2',0);
			$u->eliminarRedes($id);
$this->flashMessenger()->addMessage("Red eliminada con exito");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id2);
			
	}

	public function delAction(){
		$RedesForm = new RedesForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Redes($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$view = new ViewModel(
			array(
				'form'=>$RedesForm,
				'titulo'=>"Eliminar registro",
				'url'=>$this->getRequest()->getBaseUrl(),
				'datos'=>$id,
				'id2'=>$id2
			)
		);
		return $view;

	}


}