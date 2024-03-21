<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\EditartablafinForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Tablafinproy;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Aplicarm;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class EditartablafinController extends AbstractActionController
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

	    $id = (int) $this->params()->fromRoute('id',0);
	    $id2 = (int) $this->params()->fromRoute('id2',0);

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
			$EditartablafinForm= new EditartablafinForm();
			$id = (int) $this->params()->fromRoute('id',0);
			$this->dbAdapter=$this->getServiceLocator()->get('db1');
			$u = new Tablafinproy($this->dbAdapter);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
			$data = $this->request->getPost();
			//print_r($data);
			$resultado=$u->updateTablafin($id,$data);
			$urlId = "/application/editaraplicari/index/".$id2;

			$conv = new Convocatoria($this->dbAdapter);
			$apli = new Aplicarm($this->dbAdapter);

			$id_convocatoria=$apli->getID($id);
			$tipo=$conv->getConvocatoriaid($id_convocatoria["id_convocatoria"]);

			if ($resultado==1){

				$resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
				$evento = 'Edición de tabla de financiación : ' . $resultado . ' (mgc_financiacion_proy)';
				$ad->addAuditoriadet($evento, $resul);

				$urlId = "/application/editaraplicari/index/".$id2;
				$this->flashMessenger()->addMessage("Valor actualizado con exito");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlId);

			}else{
				$this->flashMessenger()->addMessage("La noticia no actualizo con exito");
				return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().$urlId);
			}
		}else
		{
        $EditartablafinForm= new EditartablafinForm();
		$this->dbAdapter=$this->getServiceLocator()->get('db1');
		$us = new Tablafinproy($this->dbAdapter);
        $auth = $this->auth;

        $identi=$auth->getStorage()->read();
		if($identi==false && $identi==null){
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/login/index');
		}
		
		$filter = new StringTrim();

$vf = new Agregarvalflex($this->dbAdapter);

			$us = new Tablafinproy($this->dbAdapter);
			$id = (int) $this->params()->fromRoute('id',0);
			foreach ($us->getTablafinid($id) as $dat) {
				$dat["id_aplicar"];
			}
		
$r=$dat["id_rubro"];


			foreach ($vf->getArrayvalflexid($r) as $datz) {
			$opz=$datz;
			}

			$EditartablafinForm->add(array(
            		'name' => 'id_rubro_o',
            		'attributes' => array(
                	'type'  => 'text',
				'value'=>$opz,
				'disabled' => 'disabled'
            		),
            		'options' => array(
                	'label' => 'Rubro:',
            		),
			));

$f=$dat["id_fuente"];

			foreach ($vf->getArrayvalflexid($f) as $datz) {
			$opz=$datz;
			}
			$EditartablafinForm->add(array(
            		'name' => 'id_fuente_o',
            		'attributes' => array(
                	'type'  => 'text',
				'value'=>$opz,
				'disabled' => 'disabled'
            		),
            		'options' => array(
                	'label' => 'Fuente:',
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
			$pantalla="editartablafin";
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
					$id2 = (int) $this->params()->fromRoute('id2',0);
		
		$view = new ViewModel(array('form'=>$EditartablafinForm,
									'titulo'=>"Editar Tabla Financiación",
									'id'=>$id,
									'id2'=>$id2,
									'url'=>$this->getRequest()->getBaseUrl(),
									'datos'=>$us->getTablafin($id),
									'menu'=>$dat["id_rol"]));
		return $view;
		
		}
    }
}
