<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\AplicariForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Aplicar;
use Application\Modelo\Entity\Convocatoria;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Tablafin;
use Application\Modelo\Entity\Tablafinproy;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Proyectosinv;
use Application\Modelo\Entity\Proyectos;
use Application\Modelo\Entity\Mares;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Formacionacahv;
use Application\Modelo\Entity\Idiomashv;
use Application\Modelo\Entity\Formacioncomhv;
use Application\Modelo\Entity\Experiencialabhv;
use Application\Modelo\Entity\Requisitosdoc;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class AplicariController extends AbstractActionController {
	private $auth;
	public $dbAdapter;
	public function __construct() {
		// Cargamos el servicio de autenticacien el constructor
		$this->auth = new AuthenticationService ();
	}
	public function indexAction() {
        $this->dbAdapter=$this->getServiceLocator()->get('db1');
        $auth = $this->auth;
        $permisos = new Permisos($this->dbAdapter);
        $identi = print_r($auth->getStorage()->read()->id_usuario,true);
        $resultadoPermiso = $permisos->getValidarPermisoPantalla($identi,get_class());
        if($resultadoPermiso==0){
            $this->flashMessenger()->addMessage("Su rol no tiene permiso para ver el formulario. Si desea puede solicitarlo al administrador.");
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
        }
		
		$AplicariForm = new AplicariForm ();
		$this->dbAdapter = $this->getServiceLocator ()->get ( 'db1' );
		$us = new Usuarios ( $this->dbAdapter );
		$identi = $auth->getStorage ()->read ();
		$filter = new StringTrim ();
		$usuario = $us->getUsuarios($identi->id_usuario);
        $id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
		$vf = new Agregarvalflex ( $this->dbAdapter );
        $convocatoria = new Convocatoria( $this->dbAdapter );

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

		if ($this->getRequest ()->isPost ()) {
            $aplicar = new Aplicar ( $this->dbAdapter );
            $data = $this->request->getPost();

            $id_modalidad = explode("-",$data->id_categoria)[0];
            $nombre_modalidad = "";
  
            foreach ($vf->getArrayValoresOrdenados(70) as $dat) {
                if($dat["id_valor_flexible"] == $id_modalidad){
                    $nombre_modalidad=$dat["descripcion_valor"];
                }
            }

			$id_aplicar = $aplicar->addAplicari($data, $id, $identi->id_usuario, $nombre_modalidad);
		
            if($convocatoria->getConvocatoriaid($id)[0]["id_estado"]=="B"){
                $convocatoria->updateEstado($id, "P");
            }

            $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
			$evento = 'Creación de aplicar : ' . $id_aplicar . ' (mgc_aplicari)';
			$ad->addAuditoriadet($evento, $resul);
            
            $this->flashMessenger()->addMessage("Aplicación creada con éxito.");
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/editaraplicari/index/' . $id_aplicar);
		} 
		
        $opciones = array('' => '');
        foreach ($vf->getArrayValoresOrdenados(70) as $dat) {
            $opciones = $opciones + array(
                $dat["id_valor_flexible"] . '-' . $dat["valor_flexible_padre_id"]  => $dat["descripcion_valor"]
            );
        }
        $AplicariForm->get('id_categoria')->setValueOptions($opciones);

		$opciones = array('' => '');
		foreach ($vf->getArrayvalores(40) as $dat) {
            $opciones = $opciones + array(
                $dat["id_valor_flexible"] . '-' . $dat["valor_flexible_padre_id"]  => $dat["descripcion_valor"]
            );
        }
        $AplicariForm->get('id_linea_inv')->setValueOptions($opciones);

        $opciones = array('' => '');
		foreach ($vf->getArrayvalores(37) as $dat) {
            $opciones = $opciones + array(
                $dat["id_valor_flexible"] => $dat["descripcion_valor"]
            );
        }
        $AplicariForm->get('id_campo')->setValueOptions($opciones);

        $opciones = array('' => '');
		foreach ($vf->getArrayvalores(42) as $dat) {
            $opciones = $opciones + array(
                $dat["id_valor_flexible"] => $dat["descripcion_valor"]
            );
        }
        $AplicariForm->get('id_area_tematica')->setValueOptions($opciones);
		
		$AplicariForm->get('investigador_principal')->setValue($usuario[0]["primer_nombre"]." ".$usuario[0]["segundo_nombre"]." ".$usuario[0]["primer_apellido"]." ".$usuario[0]["segundo_apellido"]);
		$AplicariForm->get('numero_documento')->setValue($usuario[0]["documento"]);
		foreach ($vf->getArrayvalores(25) as $dat) {
            if($dat["id_valor_flexible"]==$usuario[0]["id_tipo_documento"]){
            	$AplicariForm->get('tipo_documento')->setValue($dat["descripcion_valor"]);
            }
        }
        foreach ($vf->getArrayvalores(36) as $dat) {
            if($dat["id_valor_flexible"]==$usuario[0]["id_tipo_vinculacion"]){
            	$AplicariForm->get('tipo_vinculacion')->setValue($dat["descripcion_valor"]);
            }
        }
        
        $opciones = array('' => '');
		foreach ($vf->getArrayvalores(23) as $dat) {
            $opciones = $opciones + array(
                $dat["id_valor_flexible"] => $dat["descripcion_valor"]
            );
        }
        $AplicariForm->get('id_unidad_academica')->setValueOptions($opciones);

        $opciones = array('' => '');
		foreach ($vf->getArrayvalores(33) as $dat) {
            $opciones = $opciones + array(
                $dat["id_valor_flexible"] . '-' . $dat["valor_flexible_padre_id"]  => $dat["descripcion_valor"]
            );
        }
        $AplicariForm->get('id_dependencia_academica')->setValueOptions($opciones);

        $opciones = array('' => '');
		foreach ($vf->getArrayvalores(34) as $dat) {
            $opciones = $opciones + array(
                $dat["id_valor_flexible"] . '-' . $dat["valor_flexible_padre_id"]  => $dat["descripcion_valor"]
            );
        }
        $AplicariForm->get('id_programa_academico')->setValueOptions($opciones);

        $opciones = array('' => '');
		foreach ($vf->getArrayvalores(38) as $dat) {
            $opciones = $opciones + array(
                $dat["id_valor_flexible"] => $dat["descripcion_valor"]
            );
        }
        $AplicariForm->get('instituciones_coofinanciacion')->setValueOptions($opciones);
		$rol = new Roles($this->dbAdapter);
        $rolusuario = new Rolesusuario($this->dbAdapter);
        $rolusuario->verificarRolusuario($identi->id_usuario);
        foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
            $dat["id_rol"];
        }
		$view = new ViewModel ( array (
				'form' => $AplicariForm,
				'titulo' => "Aplicar a la convocatoria interna",
				'url' => $this->getRequest ()->getBaseUrl (),
                'id'=> $id,
                'menu' => $dat["id_rol"]
				
		) );
		return $view;
	}
}