<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\CopiarconvocatoriaForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Convocatoria;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Cronograma;
use Application\Modelo\Entity\Requisitos;
use Application\Modelo\Entity\Requisitosdoc;
use Application\Modelo\Entity\Aspectoeval;

class CopiarconvocatoriaController extends AbstractActionController
{
	private $auth;
	public $dbAdapter;
  
	public function __construct() {
     //Cargamos el servicio de autenticacien el constructor
     $this->auth = new AuthenticationService();
	}
	
    public function indexAction()
    {
		if($this->getRequest()->isPost()){
			//Creacion adaptador de conexion, objeto de datos, auditoria
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$not = new Convocatoria($this->dbAdapter);
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
			$CopiarconvocatoriaForm = new CopiarconvocatoriaForm ();
			//adiciona la noticia
			$id = (int) $this->params()->fromRoute('id',0);
			$arr=$not->getConcop($id);
			$resultado=$not->addCopiar($id,$data, $arr);
			
			$crono = new Cronograma($this->dbAdapter);

			$requi = new Requisitos($this->dbAdapter);

			$aspec = new Aspectoeval($this->dbAdapter);

			$requidoc = new Requisitosdoc($this->dbAdapter);



foreach($crono->getCronogramah($id) as $cron){
$crono->addCronogramacopiar($cron["nombre_actividad"],$cron["descripcion"],$cron["objetivo"],$cron["fecha_inicio"],$cron["fecha_cierre"],$cron["prioridad"],$resultado);
}

foreach($requi->getRequisitos($id) as $req){
$requi->addRequisitoscopiar($req["id_tipo_requisito"],$req["id_ponderacion1"],$req["id_ponderacion2"],$req["id_estado"],$req["descripcion"],$req["observaciones"],$req["id_tipo_ponderacion"],$resultado);
}


foreach($aspec->getAspectoeval($id) as $as){
$aspec->addAspectoevalcopiar($as["id_tipo_aspecto"],$as["ponderacion1"],$as["id_ponderacion2"],$as["id_estado"],$as["descripcion"],$as["observaciones"],$as["id_tipo_ponderacion"],$resultado);
}


foreach($requidoc->getRequisitosdoc($id) as $requid){
$requidoc->addRequisitodoccopiar($requid["id_tipo_doc"],$requid["id_documento"],$requid["id_ponderacion1"],$requid["id_ponderacion2"],$requid["id_estado"],$requid["descripcion"],$requid["observaciones"],$requid["id_tipo_ponderacion"],$requid["fecha_limite"],$resultado);
}





			//redirige a la pantalla de inicio del controlador
			if ($resultado!=null){
				$this->flashMessenger()->addMessage("convocatoria duplicada con exito");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/consulconvocatoria/index/'.$id);
			}else
			{
				$this->flashMessenger()->addMessage("La creacion de la asociacion fallo");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/editargrupoinv/index/'.$id);
			}
		}
		else
		{
			$CopiarconvocatoriaForm = new CopiarconvocatoriaForm ();
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Convocatoria($this->dbAdapter);
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
			$pantalla="copiarconvocatoria";
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
		
			if($per["id_rol"]!='' || $dat["id_rol"]==1){
			$id = (int) $this->params()->fromRoute('id',0);
			$view = new ViewModel(array('form'=>$CopiarconvocatoriaForm ,
										'titulo'=>"Copiar Convocatoria",
										'url'=>$this->getRequest()->getBaseUrl(),
										'id'=>$id,
										'menu'=>$dat["id_rol"]));
			return $view;
			}
			else
			{
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
			}
		}
    }
	

}