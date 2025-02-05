<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editarredinv\CapitulosredForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Capitulosred;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Agregarautorred;

class CapitulosredController extends AbstractActionController
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
			$not = new Capitulosred($this->dbAdapter);
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
			$CapitulosredForm = new CapitulosredForm();
			//adiciona la noticia
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2', 0);
			$upload = new \Zend\File\Transfer\Adapter\Http();
			
			if($id2!=0){
				$resultado=$not->updateCapitulored($id2, $data);
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
                        $resultado = $not->getCapituloredById($id2);
                        if($resultado[0]["archivo"] != ""){
                            unlink("public/images/uploads/capitulos/ri_cap__".$id."_".$resultado[0]["archivo"]);
                        }
                        $upload->setDestination('./public/images/uploads/capitulos/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "ri_cap__".$id."_".$archi,'overwrite' => true));
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resultado = $not->updatearchivoCapitulored($id2,$archi);
                        $this->flashMessenger()->addMessage("Capítulo actualizado con éxito.");
                    }else{
                        $this->flashMessenger()->addMessage("Capítulo actualizado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                }else{
                    $this->flashMessenger()->addMessage("Capítulo actualizado con éxito.");
                }
			}else{
				//Subir el nuevo archivo
	            /* */
	                $upload->setDestination('./public/images/uploads/capitulos/');
	                $files = $upload->getFileInfo();
	                foreach($files as $f){
	                    $archi=$f["name"];
	                }

	                $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "ri_cap__".$id."_".$archi,'overwrite' => true));
	                $upload->setValidators(array(
	                    'Size'  => array('min' => 1, 'max' => 50000000),
	                ));
	                
	                if($upload->isValid()){
	                    $rtn = array('success' => null);
	                    $rtn['success'] = $upload->receive();
	                    $resultado=$not->addCapitulored($data, $id, $archi);
	                    $this->flashMessenger()->addMessage("Capítulo agregado con éxito.");
	                }else{
	                	$resultado=$not->addCapitulored($data, $id, "");
	                    $this->flashMessenger()->addMessage("Capítulo creado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
	                }
	            /* */
			}
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarredinv/index/'.$id);
		}
		else
		{
			$CapitulosredForm = new CapitulosredForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Capitulosred($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			$filter = new StringTrim();
 			
 			// define el campo autor
            $usuario = new Usuarios($this->dbAdapter);
            $opciones = array();
            $a = '';
            
            foreach ($usuario->getUsuarios($a) as $pa) {
                $nombre_completo = $pa["primer_nombre"] . ' ' . $pa["segundo_nombre"] . ' ' . $pa["primer_apellido"] . ' ' . $pa["segundo_apellido"];
                $op = array(
                    $pa["id_usuario"] => $nombre_completo
                );
                $opciones = $opciones + $op;
            }
            $CapitulosredForm->get('id_autor')->setValueOptions($opciones);
            
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
				$datBase = $u->getCapituloredById($id2);
				if($datBase!=null){
					$CapitulosredForm->get('titulo')->setValue($datBase[0]["titulo"]);
					$CapitulosredForm->get('paginas')->setValue($datBase[0]["paginas"]);
					$CapitulosredForm->get('ano')->setValue($datBase[0]["ano"]);
					$CapitulosredForm->get('mes')->setValue($datBase[0]["mes"]);
					$CapitulosredForm->get('pais')->setValue($datBase[0]["pais"]);
					$CapitulosredForm->get('ciudad')->setValue($datBase[0]["ciudad"]);
					$CapitulosredForm->get('serie')->setValue($datBase[0]["serie"]);
					$CapitulosredForm->get('editorial')->setValue($datBase[0]["editorial"]);
					$CapitulosredForm->get('edicion')->setValue($datBase[0]["edicion"]);
					$CapitulosredForm->get('isbn')->setValue($datBase[0]["isbn"]);
					$CapitulosredForm->get('medio_divulgacion')->setValue($datBase[0]["medio_divulgacion"]);
					$CapitulosredForm->get('titulo_capitulo')->setValue($datBase[0]["titulo_capitulo"]);
					$CapitulosredForm->get('numero_capitulo')->setValue($datBase[0]["numero_capitulo"]);
					$CapitulosredForm->get('paginas_capitulo')->setValue($datBase[0]["paginas_capitulo"]);
					$CapitulosredForm->get('pagina_inicio')->setValue($datBase[0]["pagina_inicio"]);
					$CapitulosredForm->get('pagina_fin')->setValue($datBase[0]["pagina_fin"]);
					$CapitulosredForm->get('id_autor')->setValue($datBase[0]["id_autor"]);
					$CapitulosredForm->get('submit')->setValue("Actualizar");
				}
			}
			$view = new ViewModel(
				array(
					'form'=>$CapitulosredForm,
					'titulo'=>"Agregar Capítulo de libro",
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
		$u = new Capitulosred($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$u->eliminarCapitulored($id);
		$this->flashMessenger()->addMessage("Capitulo eliminado con éxito.");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarredinv/index/'.$id2);	
	}

	public function delAction(){
		$CapitulosredForm = new CapitulosredForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Capitulosred($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$view = new ViewModel(
			array(
				'form'=>$CapitulosredForm,
				'titulo'=>"Eliminar libro",
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
        $u = new Capitulosred($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getCapituloredById($id);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/capitulos/ri_cap__".$id2."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/capitulos/ri_cap__".$id2."_".$filter->filter($fileName)));
    	$response->setHeaders($headers);
        return $response;
    }
}