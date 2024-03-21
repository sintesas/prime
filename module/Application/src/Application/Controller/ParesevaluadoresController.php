<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\ParesevaluadoresForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Grupoinvestigacion;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Formacionacahv;
use Application\Modelo\Entity\Formacioncomhv;
use Application\Modelo\Entity\Experiencialabhv;
use Application\Modelo\Entity\Integrantes;
use Application\Modelo\Entity\Areashv;
use Application\Modelo\Entity\Foro;
use Application\Modelo\Entity\Idiomashv;
use Application\Modelo\Entity\Articuloshv;
use Application\Modelo\Entity\Lineashv;
use Application\Modelo\Entity\Libroshv;
use Application\Modelo\Entity\Proyectosexthv;
use Application\Modelo\Entity\Otrasproduccioneshv;
use Application\Modelo\Entity\Autores;
use Application\Modelo\Entity\Capitulosusuario;
use Application\Modelo\Entity\Agregarautorusuario;
use Application\Modelo\Entity\Actividadeshv;
use Application\Modelo\Entity\Bibliograficoshv;
use Application\Modelo\Entity\Proyectosintusua;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Tablaequipop;
use Application\Modelo\Entity\Paresevaluadores;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class ParesevaluadoresController extends AbstractActionController
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
			$not = new Grupoinvestigacion($this->dbAdapter);
			$us = new Usuarios($this->dbAdapter);
			$auth = $this->auth;

			//verifica si esta conectado
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
				$identi = new \stdClass();
            	$identi->id_usuario = '0';
				//return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
			}
			

			$rol = new Roles($this->dbAdapter);
			$rolusuario = new Rolesusuario($this->dbAdapter);
		 	$rolusuario->verificarRolusuario($identi->id_usuario);
			foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
				$dat["id_rol"];
			}

			//obtiene la informacion de las pantallas
			$data = $this->request->getPost();
			$ParesevaluadoresForm = new ParesevaluadoresForm();

			//adiciona la noticia
			$inte = new Integrantes($this->dbAdapter);
			$usua  = new Usuarios($this->dbAdapter);
			
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones = array('' => '');
            foreach ($vf->getArrayvalores(66) as $datFlex) {                
                $op = array(
                    $datFlex["id_valor_flexible"] => $datFlex["descripcion_valor"]
                );  
                $opciones = $opciones  + $op;
            }
            $ParesevaluadoresForm->get('tema')->setValueOptions($opciones);
            $id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
            $actividadeseva = new Actividadeshv($this->dbAdapter);
			$view = new ViewModel(array('form'=>$ParesevaluadoresForm,
						'titulo'=>"Consulta evaluadores", 
						'url'=>$this->getRequest()->getBaseUrl(),
						'campos'=>$data,
						'useractividades'=>$actividadeseva->getActividadeshvByTema($data->tema),
						'datos'=>$us->filtroUsuarioEvaluadores($data),
						'consulta'=>1,	
						'id' => $id,		
						'menu'=>$dat["id_rol"]));
			return $view;
		}
		else
		{
			$ParesevaluadoresForm = new ParesevaluadoresForm();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Grupoinvestigacion($this->dbAdapter);
			$us = new Usuarios($this->dbAdapter);
			$pt = new Agregarvalflex($this->dbAdapter);
			$auth = $this->auth;
	
			$identi=$auth->getStorage()->read();
			if($identi==false && $identi==null){
				$identi = new \stdClass();
            	$identi->id_usuario = '0';
				//return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
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
			$pantalla="Consultagi";
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
			
			$vf = new Agregarvalflex($this->dbAdapter);
			$opciones = array('' => '');
            foreach ($vf->getArrayvalores(66) as $datFlex) {                
                $op = array(
                    $datFlex["id_valor_flexible"] => $datFlex["descripcion_valor"]
                );  
                $opciones = $opciones  + $op;
            }

            $ParesevaluadoresForm->get('tema')->setValueOptions($opciones);
            $id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
			$view = new ViewModel(array('form'=>$ParesevaluadoresForm,
										'titulo'=>"Consulta evaluadores",
										'titulo2'=>"Tipos valores existentes", 
										'url'=>$this->getRequest()->getBaseUrl(),
										'id' => $id,
										'menu'=>$dat["id_rol"]));
			return $view;
			
		}
    }

    public function addAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Paresevaluadores($this->dbAdapter);
        $data = $this->request->getPost();
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);

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

        $resul = $u->addParesevaluadores($id2, $id);
        if ($resul == 1) {
			$result = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
			$evento = 'Creación de evaluador al proyecto : ' . $resul . ' (mgc_coinvestigadores)';
			$ad->addAuditoriadet($evento, $result);

            $this->flashMessenger()->addMessage("Se agregó el evaluador al proyecto.");
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/editaraplicari/index/' . $id2);
        } else {
            $this->flashMessenger()->addMessage("No se agregó el evaluador al proyecto.");
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/editaraplicari/index/' . $id2);
        }
    }

    public function delAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Paresevaluadores($this->dbAdapter);
        $data = $this->request->getPost();
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);

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

        $resul = $u->eliminarParesevaluadores($id);
        if ($resul == 1) {
			$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
			$evento = 'Eliminación de convocatoria interna : ' . $id . ' (mgc_coinvestigadores)';
			$ad->addAuditoriadet($evento, $resul);

            $this->flashMessenger()->addMessage("Se eliminó correctamente el evaluador.");
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/editaraplicari/index/' . $id2);
        } else {
            $this->flashMessenger()->addMessage("No se eliminó el evaluador.");
            return $this->redirect()->toUrl($this->getRequest()
                ->getBaseUrl() . '/application/editaraplicari/index/' . $id2);
        }
    }


}
