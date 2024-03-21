<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editargrupoinv\CapitulosgrupoForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Capitulosgrupo;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Agregarautorgrupo;

class CapitulosgrupoController extends AbstractActionController
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
			$not = new Capitulosgrupo($this->dbAdapter);
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
			$CapitulosgrupoForm = new CapitulosgrupoForm();
			//adiciona la noticia
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2', 0);
			$upload = new \Zend\File\Transfer\Adapter\Http();

			if($id2!=0){
				$resultado=$not->updateCapitulo($id2, $data);
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
                        $resultado = $not->getCapituloById($id2);
                        if($resultado[0]["archivo"] != ""){
                            unlink("public/images/uploads/capitulos/gi_cap__".$id."_".$resultado[0]["archivo"]);
                        }
                        $upload->setDestination('./public/images/uploads/capitulos/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "gi_cap__".$id."_".$archi,'overwrite' => true));
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resultado = $not->updatearchivoCapitulo($id2,$archi);
                        $this->flashMessenger()->addMessage("Capítulo actualizado con éxito.");
                    }else{
                        $this->flashMessenger()->addMessage("Capítulo actualizado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                }else{
                    $this->flashMessenger()->addMessage("Capítulo actualizado con éxito.");
                }
			}else{
				/* */
	                $upload->setDestination('./public/images/uploads/capitulos/');
	                $files = $upload->getFileInfo();
	                foreach($files as $f){
	                    $archi=$f["name"];
	                }

	                $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "gi_cap__".$id."_".$archi,'overwrite' => true));
	                $upload->setValidators(array(
	                    'Size'  => array('min' => 1, 'max' => 50000000),
	                ));
	                
	                if($upload->isValid()){
	                    $rtn = array('success' => null);
	                    $rtn['success'] = $upload->receive();
	                    $resultado=$not->addCapitulogrupo($data ,$id, $archi);
	                    $this->flashMessenger()->addMessage("Capítulo agregado con éxito.");
	                }else{
	                    $resultado=$not->addCapitulogrupo($data ,$id, "");
	                    $this->flashMessenger()->addMessage("Capítulo creado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
	                }
	            /* */
			}
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id);
		}
		else
		{
			$CapitulosgrupoForm = new CapitulosgrupoForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Capitulosgrupo($this->dbAdapter);
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
            $CapitulosgrupoForm->get('id_autor')->setValueOptions($opciones);
           
            
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
			//termina de verifcar los permisos
			if(true){
				$id = (int) $this->params()->fromRoute('id',0);

				$id2 = (int) $this->params()->fromRoute('id2', 0);
				if($id2!=0){
					$linea = new Capitulosgrupo($this->dbAdapter);
					$datBase = $linea->getCapituloById($id2);
					if($datBase!=null){
						$CapitulosgrupoForm->get('titulo')->setValue($datBase[0]["titulo"]);
						$CapitulosgrupoForm->get('paginas')->setValue($datBase[0]["paginas"]);
						$CapitulosgrupoForm->get('ano')->setValue($datBase[0]["ano"]);
						$CapitulosgrupoForm->get('mes')->setValue($datBase[0]["mes"]);
						$CapitulosgrupoForm->get('pais')->setValue($datBase[0]["pais"]);
						$CapitulosgrupoForm->get('ciudad')->setValue($datBase[0]["ciudad"]);
						$CapitulosgrupoForm->get('serie')->setValue($datBase[0]["serie"]);
						$CapitulosgrupoForm->get('editorial')->setValue($datBase[0]["editorial"]);
						$CapitulosgrupoForm->get('edicion')->setValue($datBase[0]["edicion"]);
						$CapitulosgrupoForm->get('isbn')->setValue($datBase[0]["isbn"]);
						$CapitulosgrupoForm->get('medio_divulgacion')->setValue($datBase[0]["medio_divulgacion"]);
						$CapitulosgrupoForm->get('titulo_capitulo')->setValue($datBase[0]["titulo_capitulo"]);
						$CapitulosgrupoForm->get('numero_capitulo')->setValue($datBase[0]["numero_capitulo"]);
						$CapitulosgrupoForm->get('paginas_capitulo')->setValue($datBase[0]["paginas_capitulo"]);
						$CapitulosgrupoForm->get('pagina_inicio')->setValue($datBase[0]["pagina_inicio"]);
						$CapitulosgrupoForm->get('pagina_fin')->setValue($datBase[0]["pagina_fin"]);
						$CapitulosgrupoForm->get('id_autor')->setValue($datBase[0]["id_autor"]);
						$CapitulosgrupoForm->get('submit')->setValue("Actualizar");
					}
				}

				$view = new ViewModel(
					array(
						'form'=>$CapitulosgrupoForm,
						'titulo'=>"Agregar Capítulo de Libro",
						'url'=>$this->getRequest()->getBaseUrl(),
						'id'=>$id,
						'id2'=>$id2,
						'menu'=>$dat["id_rol"]
					)
				);
				return $view;
			}
			else
			{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
			}
		}
    }

	public function eliminarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Capitulosgrupo($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$u->eliminarCapitulogrupo($id);
		$this->flashMessenger()->addMessage("Capitulo eliminado con éxito.");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id2);	
	}

	public function delAction(){
		$CapitulosgrupoForm = new CapitulosgrupoForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Capitulosgrupo($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$view = new ViewModel(
			array(
				'form'=>$CapitulosgrupoForm,
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
        $u = new Capitulosgrupo($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getCapituloById($id);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/capitulos/gi_cap__".$id2."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/capitulos/gi_cap__".$id2."_".$filter->filter($fileName)));
    	$response->setHeaders($headers);
        return $response;
    }
	

}