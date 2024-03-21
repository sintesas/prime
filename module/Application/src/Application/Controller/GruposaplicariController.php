<?php
namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\GruposaplicariForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Gruposaplicari;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Grupoinvestigacion;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class GruposaplicariController extends AbstractActionController
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
   
		$grupos = array();
		
		$GruposaplicariForm = new GruposaplicariForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		
		if($this->getRequest()->isPost()){
			$data = $this->request->getPost();
			$id = (int) $this->params()->fromRoute('id',0);
			$grupoinvestigacion = new Grupoinvestigacion($this->dbAdapter);
			$grupos = $grupoinvestigacion->filtroRedes($data);
		}

		$u = new Gruposaplicari($this->dbAdapter);
		$pt = new Agregarvalflex($this->dbAdapter);
		$auth = $this->auth;

		$identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}

		$filter = new StringTrim();
			
			// define el campo autor
        $usuario = new Usuarios($this->dbAdapter);
        $opciones = array(""=>"");
        $a = '';
        
        foreach ($usuario->getUsuarios($a) as $pa) {
            $nombre_completo = $pa["primer_nombre"] . ' ' . $pa["segundo_nombre"] . ' ' . $pa["primer_apellido"] . ' ' . $pa["segundo_apellido"];
            $op = array(
                $pa["id_usuario"] => $nombre_completo
            );
            $opciones = $opciones + $op;
        }
        
        $GruposaplicariForm->add(array(
            'name' => 'id_autor',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_autor'
            ),
            'options' => array(
            	'label' => ' ',
                'value_options' => $opciones,
            )
        ));

		$vf = new Agregarvalflex($this->dbAdapter);
		$opciones=array();
		foreach ($vf->getArrayvalores(23) as $uni) {
			$op=array(''=>'');
			$op=$op+array($uni["id_valor_flexible"]=>$uni["descripcion_valor"]);
			$opciones=$opciones+$op;
		}
		$GruposaplicariForm->add(array(
			'name' => 'unidad_academica',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
	            'label' => 'Filtro unidad académica:',
	            'value_options' => $opciones,
			),
		));

		//define el campo ciudad
		$vf = new Agregarvalflex($this->dbAdapter);
		$opciones=array();
		foreach ($vf->getArrayvalores(33) as $dept) {
			$op=array(''=>'');
			$op=$op+array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
			$opciones=$opciones+$op;
		}
		$GruposaplicariForm->add(array(
			'name' => 'dependencia_academica',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
	            'label' => 'Filtro dependencia académica:',
	            'value_options' => $opciones,
			),
		)); 

		//define el campo ciudad
		$vf = new Agregarvalflex($this->dbAdapter);
		$opciones=array();
		foreach ($vf->getArrayvalores(34) as $dept) {
			$op=array(''=>'');
			$op=$op+array($dept["id_valor_flexible"]=>$dept["descripcion_valor"]);
			$opciones=$opciones+$op;
		}
		$GruposaplicariForm->add(array(
			'name' => 'programa_academico',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
	            'label' => 'Filtro programa académico:',
	            'value_options' => $opciones,
			),
		)); 
        
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
		//termina de verifcar los permisos
		if(true){
			$id = (int) $this->params()->fromRoute('id',0);
			$id2 = (int) $this->params()->fromRoute('id2',0);
			$view = new ViewModel(
				array(
					'form'=>$GruposaplicariForm,
					'titulo'=>"Agregar grupos de investigación participantes",
					'url'=>$this->getRequest()->getBaseUrl(),
					'id'=>$id,
					'id2'=>$id2,
					'menu'=>$dat["id_rol"],
					'grupos' => $grupos
				)
			);
			return $view;
		}
		else
		{
			return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
		}
		
    }

	public function eliminarAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Gruposaplicari($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$u->eliminarGruposaplicari($id);

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
		$evento = 'Eliminación de grupos de investigaciión participantes : ' . $id . ' (mgc_grupos_participantes)';
		$ad->addAuditoriadet($evento, $resul);

		$this->flashMessenger()->addMessage("Producción de investigación eliminada con éxito.");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicari/index/'.$id2);	
	}

	public function delAction(){
		$GruposaplicariForm = new GruposaplicariForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Gruposaplicari($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$view = new ViewModel(
			array(
				'form'=>$GruposaplicariForm,
				'titulo'=>"Eliminar Producción de investigación",
				'url'=>$this->getRequest()->getBaseUrl(),
				'id'=>$id,
				'id2'=>$id2
			)
		);
		return $view;
	}

	public function addAction(){
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$u = new Gruposaplicari($this->dbAdapter);
		$id = (int) $this->params()->fromRoute('id',0);
		$id2 = (int) $this->params()->fromRoute('id2',0);
		$id3 = (int) $this->params()->fromRoute('id3',0);
		if ($id3 == 0) {
			$resultado = $u->addGrupored($id,$id2);
			$evento = 'Creación de grupos de investigación participantes : ' . $resultado . ' (mgc_grupos_participantes)';
		}
		else {
			$resultado = $u->updateGruposaplicari($id3,$id);
			$evento = 'Edición de grupos de investigación participantes : ' . $id3 . ' (mgc_grupos_participantes)';
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

		$auth = $this->auth;
		$identi=$auth->getStorage()->read();

		$au = new Auditoria($this->dbAdapter);
		$ad = new Auditoriadet($this->dbAdapter);

		$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
		$ad->addAuditoriadet($evento, $resul);

		$this->flashMessenger()->addMessage("Grupo de investigación agregado con éxito.");
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editaraplicari/index/'.$id2);	
	}
	
	

}