<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarredinv\IdentificadoresredForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Identificadoresred;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Agregarautorred;

class IdentificadoresredController extends AbstractActionController
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
			$not = new Identificadoresred($this->dbAdapter);
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
			$IdentificadoresredForm = new IdentificadoresredForm();
			//adiciona la noticia
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2', 0);
			$upload = new \Zend\File\Transfer\Adapter\Http();
	                
			if($id2!=0){
				$resultado=$not->updateIdentificadoresred($id2, $data);
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
                        $resultado = $not->getIdentificadoresredById($id2);
                        if($resultado[0]["archivo"] != ""){
                            unlink("public/images/uploads/ri/identificadores/ri_ide__".$id."_".$id2);
                        }
                        $upload->setDestination('./public/images/uploads/ri/identificadores/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "ri_ide__".$id."_".$id2,'overwrite' => true));
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resultado = $not->updatearchivoIdentificadoresred($id2,$archi);
                        $this->flashMessenger()->addMessage("Identificador actualizado con éxito.");
                    }else{
                        $this->flashMessenger()->addMessage("Identificador actualizado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                }else{
                    $this->flashMessenger()->addMessage("Producción actualizado con éxito.");
                }
			}else{
				$resultado=$not->addIdentificadoresred($data,$id,"");
				//Subir el nuevo archivo
	            /* */
	                $upload->setDestination('./public/images/uploads/ri/identificadores/');
	                $files = $upload->getFileInfo();
	                foreach($files as $f){
	                    $archi=$f["name"];
	                }

	                $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "ri_ide__".$id."_".$resultado,'overwrite' => true));
	                $upload->setValidators(array(
	                    'Size'  => array('min' => 1, 'max' => 50000000),
	                ));
	                
	                if($upload->isValid()){
	                    $rtn = array('success' => null);
	                    $rtn['success'] = $upload->receive();
	                    $not->updatearchivoIdentificadoresred($resultado,$archi);
	                    $this->flashMessenger()->addMessage("Identificador agregado con éxito.");
	                }else{
	                    $this->flashMessenger()->addMessage("Identificador creado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
	                }
	            /* */
			}			
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarredinv/index/'.$id);
		}
		else
		{
			$IdentificadoresredForm = new IdentificadoresredForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Identificadoresred($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

            //Verifica permisos sobre la pantalla
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
			if($id2!=0){
				$datBase = $u->getIdentificadoresredById($id2);
				if($datBase!=null){
					$IdentificadoresredForm->get('id_tipoidentificador')->setValue($datBase[0]["id_tipoidentificador"]);
					$IdentificadoresredForm->get('id_tipocategoria')->setValue($datBase[0]["id_tipocategoria"]);
					$IdentificadoresredForm->get('id_field')->setValue($datBase[0]["id_field"]);
					$IdentificadoresredForm->get('fecha_registro')->setValue($datBase[0]["fecha_registro"]);
					$IdentificadoresredForm->get('nombre')->setValue($datBase[0]["nombre"]);
					$IdentificadoresredForm->get('web')->setValue($datBase[0]["web"]);
					$IdentificadoresredForm->get('ciudad')->setValue($datBase[0]["ciudad"]);
					$IdentificadoresredForm->get('descripcion')->setValue($datBase[0]["descripcion"]);
					$IdentificadoresredForm->get('otra_informacion')->setValue($datBase[0]["otra_informacion"]);
					$IdentificadoresredForm->get('submit')->setValue("Actualizar");
				}
			}

			$opciones=array();
			foreach ($pt->getArrayvalores(71) as $dept) {
				$op=array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
            $IdentificadoresredForm->get('id_tipoidentificador')->setValueOptions($opciones);
            
            $opciones=array();
			foreach ($pt->getArrayvalores(72) as $dept) {
				$op=array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
            $IdentificadoresredForm->get('id_tipocategoria')->setValueOptions($opciones);
            

			$view = new ViewModel(
				array(
					'form'=>$IdentificadoresredForm,
					'titulo'=>"Identificadores de Investigación",
					'url'=>$this->getRequest()->getBaseUrl(),
					'id'=>$id,
					'id2'=>$id2,
					'menu'=>$dat["id_rol"]
				)
			);
			return $view;
		}
    }

	public function eliminarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Identificadoresred($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$u->eliminarIdentificadoresred($id);
		$this->flashMessenger()->addMessage("Identificador de investigación eliminado con éxito.");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarredinv/index/'.$id2);	
	}

	public function delAction(){
		$IdentificadoresredForm = new IdentificadoresredForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Identificadoresred($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$view = new ViewModel(
			array(
				'form'=>$IdentificadoresredForm,
				'titulo'=>"Eliminar Identificador de investigación",
				'url'=>$this->getRequest()->getBaseUrl(),
				'id'=>$id,
				'id2'=>$id2
			)
		);
		return $view;
	}

	public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Identificadoresred($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getIdentificadoresredById($id);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/ri/identificadores/ri_ide__".$id2."_".$id, 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/ri/identificadores/ri_ide__".$id2."_".$id));
    	$response->setHeaders($headers);
        return $response;
    }
}