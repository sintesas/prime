<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Proyectos\InformesForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Informes;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class InformesController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     //Cargamos el servicio de autenticacien el constructor
     $this->auth = new AuthenticationService();
	}
	
    public function indexAction()
    {
		// function get_real_ip()
        // {
        //     if (isset($_SERVER["HTTP_CLIENT_IP"])) {
        //         return $_SERVER["HTTP_CLIENT_IP"];
        //     } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        //         return $_SERVER["HTTP_X_FORWARDED_FOR"];
        //     } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
        //         return $_SERVER["HTTP_X_FORWARDED"];
        //     } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
        //         return $_SERVER["HTTP_FORWARDED_FOR"];
        //     } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
        //         return $_SERVER["HTTP_FORWARDED"];
        //     } else {
        //         return $_SERVER["REMOTE_ADDR"];
        //     }
        // }

        // $au = new Auditoria($this->dbAdapter);
        // $ad = new Auditoriadet($this->dbAdapter);

		if($this->getRequest()->isPost()){
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Informes($this->dbAdapter);
			$auth = $this->auth;

			//verifica si esta conectado
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}
			
			$upload = new \Zend\File\Transfer\Adapter\Http();
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

                    //obtiene la informacion de las pantallas
					$data = $this->request->getPost();
					$InformesForm= new InformesForm();
					//adiciona la noticia
					$id = (int) $this->params()->fromRoute('id',0);

					$resultado=$not->addInforme($data, $id, $archi);

					$upload->setDestination('./public/images/uploads/informesp/');
                    $upload->addFilter('File\Rename', array('target' => $upload->getDestination().DIRECTORY_SEPARATOR. $id."_".$archi,'overwrite' => true));
                    $rtn = array('success' => null);
                    $rtn['success'] = $upload->receive();

					//redirige a la pantalla de inicio del controlador
					if ($resultado==1){
						// $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
						// $evento = 'Creación de informes : ' . $resultado . ' (mgp_informes)';			
						// $ad->addAuditoriadet($evento, $resul);

						$this->flashMessenger()->addMessage("Actividad creada con exito");
						return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarproyecto/index/'.$id);
					}else
					{
						$this->flashMessenger()->addMessage("La creacion del Cronograma fallo");
						return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/cronograma/index');
					}
                }else{
                	$id = (int) $this->params()->fromRoute('id',0);
                	$this->flashMessenger()->addMessage("Actividad no creada. Archivo no valido.");
					return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarproyecto/index/'.$id);
                }
	        }else{
	        	$data = $this->request->getPost();
				$InformesForm= new InformesForm();
				//adiciona la noticia
				$id = (int) $this->params()->fromRoute('id',0);

				$resultado=$not->addInforme($data, $id, "");
				if ($resultado==1){
						// $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
						// $evento = 'Creación de informes : ' . $resultado . ' (mgp_informes)';			
						// $ad->addAuditoriadet($evento, $resul);
						$this->flashMessenger()->addMessage("Actividad creada con exito");
						return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarproyecto/index/'.$id);
				}else
				{
					$this->flashMessenger()->addMessage("La creacion del Cronograma fallo");
					return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/cronograma/index');
				}
	        }
		}
		else
		{

			$InformesForm= new InformesForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Informes($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			$filter = new StringTrim();

			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones=array();
			foreach ($vf->getArrayvalores(23) as $uni) {
			$op=array(''=>'');
			$op=$op+array($uni["id_valor_flexible"]=>$uni["descripcion_valor"]);
			$opciones=$opciones+$op;
			}
			$InformesForm->add(array(
			'name' => 'id_entidad',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
            'label' => 'Entidad : ',
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
			$pantalla="cronograma";
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
			$view = new ViewModel(array('form'=>$InformesForm,
										'titulo'=>"Informes y/o Productos del Proyecto",
										'url'=>$this->getRequest()->getBaseUrl(),
										'id'=>$id,
										'menu'=>$dat["id_rol"]));
			return $view;
		}
    }

	public function eliminarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Informes($this->dbAdapter);
		//print_r($data);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$u->eliminarCronograma($id);

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
		$evento = 'Eliminación de idioma : ' . $id . ' (mgp_informes)';
		$ad->addAuditoriadet($evento, $resul);

		$this->flashMessenger()->addMessage("Actividad eliminada con exito");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editarproyecto/index/'.$id2);
			
	}

	public function delAction(){
			$InformesForm= new InformesForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Informes($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);


			$view = new ViewModel(array('form'=>$InformesForm,
										'titulo'=>"Eliminar registro",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$id,
										'id2'=>$id2));
			return $view;

	}

	public function bajarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Informes($this->dbAdapter);
        $filter = new StringTrim();
        $id = (int) $this->params()->fromRoute('id'); 
        $id2 = (int) $this->params()->fromRoute('id2');      
        $data = $u->getArchivosid($id);        
        foreach ($data as $d) {
            $fileName = $d["archivo2"];
        }
        $response = new \Zend\Http\Response\Stream();   
        $response->setStream(fopen("public/images/uploads/informesp/".$id2."_".$filter->filter($fileName), 'r'));
        $response->setStatusCode(200);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'whatever your content type is')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $id2."_".$filter->filter($fileName) . '"')
                ->addHeaderLine('Content-Length', filesize("public/images/uploads/informesp/".$id2."_".$filter->filter($fileName)));
        $response->setHeaders($headers);
        return $response;
    }

}