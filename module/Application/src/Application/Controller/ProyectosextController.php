<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editargrupoinv\ProyectosextForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Proyectosext;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;

class ProyectosextController extends AbstractActionController
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
			$not = new Proyectosext($this->dbAdapter);
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
			$ProyectosextForm = new ProyectosextForm();
			//adiciona la noticia
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2', 0);
			$upload = new \Zend\File\Transfer\Adapter\Http();
			if($id2!=0){
				$resultado=$not->updateProyectosext($id2, $data);
				if($upload->isUploaded()){
                    //Archivo cargado
                    $upload->setValidators(array(
                        'Size'  => array('min' => 1, 'max' => 50000000),
                    ));
                    if($upload->isValid()){
                        $files = $upload->getFileInfo();
                        foreach($files as $f){
                            $archi=$f["name"];
                        }
                        $resultado = $not->getProyectosextById($id2);
                        if($resultado[0]["archivo"] != ""){
                            unlink("public/images/uploads/gi/proyexternos/gi_ext__".$id."_".$resultado[0]["archivo"]);
                        }
                        $upload->setDestination('./public/images/uploads/gi/proyexternos/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "gi_ext__".$id."_".$archi,'overwrite' => true));
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resultado = $not->updatearchivoProyectosext($id2,$archi);
                        $this->flashMessenger()->addMessage("Proyecto externo actualizado con éxito.");
                    }else{
                        $this->flashMessenger()->addMessage("Proyecto externo actualizado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                }else{
                    $this->flashMessenger()->addMessage("Proyecto externo actualizado con éxito.");
                }
			}else{
				//Subir el nuevo archivo
	            /* */
	                $upload->setDestination('./public/images/uploads/gi/proyexternos/');
	                $files = $upload->getFileInfo();
	                foreach($files as $f){
	                    $archi=$f["name"];
	                }

	                $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "gi_ext__".$id."_".$archi,'overwrite' => true));
	                $upload->setValidators(array(
	                    'Size'  => array('min' => 1, 'max' => 50000000),
	                ));
	                
	                if($upload->isValid()){
	                    $rtn = array('success' => null);
	                    $rtn['success'] = $upload->receive();
					$resultado=$not->addProyectosext($data,$id, $archi);
	                    $this->flashMessenger()->addMessage("Proyecto externo agregado con éxito.");
	                }else{
	                	$resultado=$not->addArticulosred($data, $id, $archi);
	                    $this->flashMessenger()->addMessage("Proyecto externo creado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
	                }
	            /* */
			}
			//redirige a la pantalla de inicio del controlador
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id);
		}
		else
		{
			$ProyectosextForm = new ProyectosextForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');

			$id2 = (int) $this->params()->fromRoute('id2', 0);
			if($id2!=0){
				$linea = new Proyectosext($this->dbAdapter);
				$datBase = $linea->getProyectosextById($id2);
				if($datBase!=null){
					$ProyectosextForm->get('codigo_proyecto')->setValue($datBase[0]["codigo_proyecto"]);
					$ProyectosextForm->get('fecha_inicio')->setValue($datBase[0]["fecha_inicio"]);
					$ProyectosextForm->get('fecha_fin')->setValue($datBase[0]["fecha_fin"]);
					$ProyectosextForm->get('resumen_ejecutivo')->setValue($datBase[0]["resumen_ejecutivo"]);
					$ProyectosextForm->get('objetivo_general')->setValue($datBase[0]["objetivo_general"]);
					$ProyectosextForm->get('productos_derivados')->setValue($datBase[0]["productos_derivados"]);
					$ProyectosextForm->get('nombre_proyecto')->setValue($datBase[0]["nombre_proyecto"]);
					$ProyectosextForm->get('submit')->setValue("Actualizar");
				}
			}

			$u = new Proyectosext($this->dbAdapter);
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
		
			$id = (int) $this->params()->fromRoute('id',0);
			$view = new ViewModel(array('form'=>$ProyectosextForm,
										'titulo'=>"Proyectos de investigación",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$u->getProyectosext($id),
										'id'=>$id,
										'id2'=>$id2,
										'menu'=>$dat["id_rol"]));
			return $view;
		}
    }

	public function eliminarAction(){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Proyectosext($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
$id2 = (int) $this->params()->fromRoute('id2',0);
			$u->eliminarProyectosext($id);
$this->flashMessenger()->addMessage("Proyecto externo eliminado con exito");
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id2);
			
	}

	public function delAction(){
$ProyectosextForm = new ProyectosextForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Proyectosext($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);


			$view = new ViewModel(array('form'=>$ProyectosextForm,
										'titulo'=>"Eliminar registro",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$id,'id2'=>$id2));
			return $view;
	}

	public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Proyectosext($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getProyectosextById($id);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/gi/proyexternos/gi_ext__".$id2."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/gi/proyexternos/gi_ext__".$id2."_".$filter->filter($fileName)));
    	$response->setHeaders($headers);
        return $response;
    }
	

}