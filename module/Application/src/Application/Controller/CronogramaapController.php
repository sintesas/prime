<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\CronogramaapForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Cronogramaap;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Objetivosespecificos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class CronogramaapController extends AbstractActionController
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
        
		if($this->getRequest()->isPost()){
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Cronogramaap($this->dbAdapter);
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

			$id = (int) $this->params()->fromRoute('id',0);
			$conv = new Convocatoria($this->dbAdapter);
			$apli = new Aplicarm($this->dbAdapter);
			$id_convocatoria=$apli->getID($id);
			$tipo=$conv->getConvocatoriaid($id_convocatoria["id_convocatoria"]);

			foreach($tipo as $t){
				echo $t["tipo_conv"];
			}

			//obtiene la informacion de las pantallas
			$data = $this->request->getPost();
			$CronogramaapForm = new CronogramaapForm();
			//adiciona la noticia
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$id3 = (int) $this->params()->fromRoute('id3',0);
			if($id2!=0){
				$resultado=$not->updateCronogramaap($id2, $data, $id3);
				$evento = 'Edición de actividad cronograma : ' . $id2 . ' (mgc_cronograma_ap)';
			}else{
				$resultado=$not->addCronograma($data, $id, $id3);
				$evento = 'Creación de actividad cronograma : ' . $resultado . ' (mgc_cronograma_ap)';
			}
			
			//redirige a la pantalla de inicio del controlador
			if ($resultado==1){
				$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
				$ad->addAuditoriadet($evento, $resul);

				if($t["tipo_conv"]=='i'){
				$this->flashMessenger()->addMessage("Actividad creada con exito");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicari/index/'.$id);
				}else{
					//Cambiar, debe ir a editaraplicar
				$this->flashMessenger()->addMessage("Actividad creada con exito");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicari/index/'.$id);
				}
			}else
			{
				$this->flashMessenger()->addMessage("La creacion del Cronograma fallo");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/cronograma/index');
			}
		}
		else
		{
			$id = (int) $this->params()->fromRoute('id',0);
			$id3 = (int) $this->params()->fromRoute('id3',0);
			$CronogramaapForm = new CronogramaapForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Cronogramaap($this->dbAdapter);
			$Objetivosespecificos = new Objetivosespecificos($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}

			if($id3==1){
				$filter = new StringTrim();
				$vf = new Agregarvalflex($this->dbAdapter);
				$opciones=array();
				foreach ($vf->getArrayvalores(55) as $uni) {
					$opciones+=array($uni["id_valor_flexible"]=>$uni["descripcion_valor"]);
				}
				$CronogramaapForm->get('id_rolresponsable')->setValueOptions($opciones);

				$opciones=array();
				foreach ($Objetivosespecificos->getObjetivosespecificos($id) as $objetivo) {
					$opciones+=array($objetivo["id"]=>$objetivo["objetivo"]);
				}
				$CronogramaapForm->get('id_meta')->setValueOptions($opciones);
			}
			
			//verificar roles
			$dat=array('id_rol'=>'');
			$rolusuario = new Rolesusuario($this->dbAdapter);
			$permiso = new Permisos($this->dbAdapter);
		
			//me verifica el tipo de rol asignado al usuario
			$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$dat["id_rol"];
			}			
			
			$id2 = (int) $this->params()->fromRoute('id2',0);
			
			if($id2!=0){
				$datBase = $u->getCronogramaapById($id2);
				if($datBase!=null){
					$CronogramaapForm->get('descripcion')->setValue($datBase[0]["descripcion"]);
					$CronogramaapForm->get('fecha_inicio')->setValue($datBase[0]["fecha_inicio"]);
					$CronogramaapForm->get('fecha_cierre')->setValue($datBase[0]["fecha_cierre"]);
					$CronogramaapForm->get('nombre_actividad')->setValue($datBase[0]["nombre_actividad"]);
					$CronogramaapForm->get('submit')->setValue("Actualizar");
					if($id3=="1"){
						$CronogramaapForm->get('id_meta')->setValue($datBase[0]["id_meta"]);
						$CronogramaapForm->get('id_rolresponsable')->setValue($datBase[0]["id_rolresponsable"]);
					}else{
						$CronogramaapForm->get('objetivo')->setValue($datBase[0]["objetivo"]);
					}
				}
			}

			$view = new ViewModel(
				array(
					'form'=>$CronogramaapForm,
					'titulo'=>"Cronograma del proyecto",
					'url'=>$this->getRequest()->getBaseUrl(),
					'id'=>$id,
					'id2'=>$id2,
					'id3'=>$id3,
					'menu'=>$dat["id_rol"]
				)
			);
			return $view;			
		}
    }

	public function eliminarAction(){
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Cronogramaap($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$id3 = (int) $this->params()->fromRoute('id3',0);
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
			$evento = 'Eliminación de actividad cronograma : ' . $id . ' (mgc_cronograma_ap)';
			$ad->addAuditoriadet($evento, $resul);

			$this->flashMessenger()->addMessage("Actividad eliminada con exito");
			if($id3=="1"){
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicari/index/'.$id2);
			}else{
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicar/index/'.$id2);
		}	
	}

	public function delAction(){
$CronogramaapForm= new CronogramaapForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Cronogramaap($this->dbAdapter);
			//print_r($data);
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$id3 = (int) $this->params()->fromRoute('id3',0);

			$view = new ViewModel(array('form'=>$CronogramaapForm,
										'titulo'=>"Eliminar registro",
										'url'=>$this->getRequest()->getBaseUrl(),
										'datos'=>$id,'id2'=>$id2,'id3'=>$id3 ));
			return $view;

	}

}