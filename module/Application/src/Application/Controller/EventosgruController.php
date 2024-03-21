<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Editargrupoinv\EventosgruForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Eventosgru;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Agregarautorred;

class EventosgruController extends AbstractActionController
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
			$not = new Eventosgru($this->dbAdapter);
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
			$EventosgruForm = new EventosgruForm();
			//adiciona la noticia
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2', 0);
			$upload = new \Zend\File\Transfer\Adapter\Http();
	                
			if($id2!=0){
				$resultado=$not->updateEventosgru($id2, $data);
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
                        $resultado = $not->getEventosgruById($id2);
                        if($resultado[0]["archivo"] != ""){
                            unlink("public/images/uploads/gi/eventos/gi_eve__".$id."_".$id2);
                        }
                        $upload->setDestination('./public/images/uploads/gi/eventos/');
                        $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "gi_eve__".$id."_".$id2,'overwrite' => true));
                        $rtn = array('success' => null);
                        $rtn['success'] = $upload->receive();
                        $resultado = $not->updatearchivoEventosgru($id2,$archi);
                        $this->flashMessenger()->addMessage("Divulgación actualizada con éxito.");
                    }else{
                        $this->flashMessenger()->addMessage("Divulgación actualizada sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
                    }
                }else{
                    $this->flashMessenger()->addMessage("Producción actualizado con éxito.");
                }
			}else{
				$resultado=$not->addEventosgru($data,$id,"");
				//Subir el nuevo archivo
	            /* */
	                $upload->setDestination('./public/images/uploads/gi/eventos/');
	                $files = $upload->getFileInfo();
	                foreach($files as $f){
	                    $archi=$f["name"];
	                }

	                $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR . "gi_eve__".$id."_".$resultado,'overwrite' => true));
	                $upload->setValidators(array(
	                    'Size'  => array('min' => 1, 'max' => 50000000),
	                ));
	                
	                if($upload->isValid()){
	                    $rtn = array('success' => null);
	                    $rtn['success'] = $upload->receive();
	                    $not->updatearchivoEventosgru($resultado,$archi);
	                    $this->flashMessenger()->addMessage(" agregado con éxito.");
	                }else{
	                    $this->flashMessenger()->addMessage("Divulgación creado sin archivo. Recuerde que el tamaño máximo del archivo es de 25MB.");
	                }
	            /* */
			}			
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id);
		}
		else
		{
			$EventosgruForm = new EventosgruForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Eventosgru($this->dbAdapter);
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
			$id2 = (int) $this->params()->fromRoute('id2', 0);
			if($id2!=0){
				$datBase = $u->getEventosgruById($id2);
				if($datBase!=null){
					$EventosgruForm->get('id_tipoevento')->setValue($datBase[0]["id_tipoevento"]);
					$EventosgruForm->get('id_tipoparticipacion')->setValue($datBase[0]["id_tipoparticipacion"]);
					$EventosgruForm->get('nombre_evento')->setValue($datBase[0]["nombre_evento"]);
					$EventosgruForm->get('nombre_trabajo')->setValue($datBase[0]["nombre_trabajo"]);
					$EventosgruForm->get('id_institucion')->setValue($datBase[0]["id_institucion"]);
					$EventosgruForm->get('ciudad_trabajo')->setValue($datBase[0]["ciudad_trabajo"]);
					$EventosgruForm->get('fecha_inicio')->setValue($datBase[0]["fecha_inicio"]);
					$EventosgruForm->get('fecha_fin')->setValue($datBase[0]["fecha_fin"]);
					$EventosgruForm->get('otra_informacion')->setValue($datBase[0]["otra_informacion"]);
					$EventosgruForm->get('id_tipomedio')->setValue($datBase[0]["id_tipomedio"]);
					$EventosgruForm->get('nombre_trabajo_medio')->setValue($datBase[0]["nombre_trabajo_medio"]);
					$EventosgruForm->get('id_autor')->setValue($datBase[0]["id_autor"]);
					$EventosgruForm->get('id_institucion_medio')->setValue($datBase[0]["id_institucion_medio"]);
					$EventosgruForm->get('ciudad_medio')->setValue($datBase[0]["ciudad_medio"]);
					$EventosgruForm->get('medio_divulgacion')->setValue($datBase[0]["medio_divulgacion"]);
					$EventosgruForm->get('fecha_medio')->setValue($datBase[0]["fecha_medio"]);
					$EventosgruForm->get('descripcion_medio')->setValue($datBase[0]["descripcion_medio"]);
					$EventosgruForm->get('otra_informacion_medio')->setValue($datBase[0]["otra_informacion_medio"]);
					$EventosgruForm->get('submit')->setValue("Actualizar");
				}
			}

			$opciones=array();
			foreach ($pt->getArrayvalores(38) as $dept) {
				$op=array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
            $EventosgruForm->get('id_institucion')->setValueOptions($opciones);
            $EventosgruForm->get('id_institucion_medio')->setValueOptions($opciones);

			$opciones=array();
			foreach ($pt->getArrayvalores(56) as $dept) {
				$op=array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
            $EventosgruForm->get('id_tipoparticipacion')->setValueOptions($opciones);

			$opciones=array();
			foreach ($pt->getArrayvalores(73) as $dept) {
				$op=array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
            $EventosgruForm->get('id_tipoevento')->setValueOptions($opciones);

			$opciones=array();
			foreach ($pt->getArrayvalores(74) as $dept) {
				$op=array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
				$opciones=$opciones+$op;
			}
            $EventosgruForm->get('id_tipomedio')->setValueOptions($opciones);         

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
            $EventosgruForm->get('id_autor')->setValueOptions($opciones);

			$view = new ViewModel(
				array(
					'form'=>$EventosgruForm,
					'titulo'=>"Divulgación",
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
		$u = new Eventosgru($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$u->eliminarEventosgru($id);
		$this->flashMessenger()->addMessage("Divulgación eliminada con éxito.");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id2);	
	}

	public function delAction(){
		$EventosgruForm = new EventosgruForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Eventosgru($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$view = new ViewModel(
			array(
				'form'=>$EventosgruForm,
				'titulo'=>"Eliminar Divulgación",
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
        $u = new Eventosgru($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id');
        $id2 = (int) $this->params()->fromRoute('id2');        
        $data = $u->getEventosgruById($id);        
        foreach ($data as $d) {
            $fileName = $d["archivo"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/gi/eventos/gi_eve__".$id2."_".$id, 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/gi/eventos/gi_eve__".$id2."_".$id));
    	$response->setHeaders($headers);
        return $response;
    }
}