<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\ContratacionpersonalForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Contratacionpersonal;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class ContratacionpersonalController extends AbstractActionController
{

    private $auth;
    public $dbAdapter;

    public function __construct()
    {
        // Cargamos el servicio de autenticacien el constructor
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
        $pantalla="editarsemilleroinv";
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

        $identi=$auth->getStorage()->read();

        $au = new Auditoria($this->dbAdapter);
		$ad = new Auditoriadet($this->dbAdapter);

        //termina de verifcar los permisos
        if(true){
            if ($this->getRequest()->isPost()) {
                $this->dbAdapter = $this->getServiceLocator()->get('db1');
                $u = new Contratacionpersonal($this->dbAdapter);
                $data = $this->request->getPost();
                $id = (int) $this->params()->fromRoute('id', 0);
                $id2 = (int) $this->params()->fromRoute('id2', 0);
                if($id2!=0){
                    $resul=$u->updateContratacionpersonal($id2, $data);
                    $evento = 'Edición de contratración de servicios profesionales : ' . $id2 . ' (mgc_contratacionpersonal)';
                }else{
                    $resul = $u->addContratacionpersonal($data, $id);
                    $evento = 'Creación de contratración de servicios profesionales : ' . $resul . ' (mgc_contratacionpersonal)';
                }
                
                if ($resul == 1) {
                    $result = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                    $ad->addAuditoriadet($evento, $result);
            
                    $this->flashMessenger()->addMessage("Contrato agregado correctamente.");
                    return $this->redirect()->toUrl($this->getRequest()
                        ->getBaseUrl() . '/application/editaraplicari/index/' . $id);
                } else {
                    $this->flashMessenger()->addMessage("El contrato no se pudo agregar.");
                    return $this->redirect()->toUrl($this->getRequest()
                        ->getBaseUrl() . '/application/editaraplicari/index/' . $id2);
                }
            } else {
                $this->dbAdapter = $this->getServiceLocator()->get('db1');
                $u = new Rolesusuario($this->dbAdapter);
                $ContratacionpersonalForm = new ContratacionpersonalForm();
                $auth = $this->auth;
                
                $identi = $auth->getStorage()->read();
                if ($identi == false && $identi == null) {
                    return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/login/index');
                }
                $id = (int) $this->params()->fromRoute('id', 0);
                $id2 = (int) $this->params()->fromRoute('id2', 0);
                $opciones = array();
                foreach ($pt->getArrayvalores(67) as $da) {
                    $op = array(
                        $da["id_valor_flexible"] => $da["descripcion_valor"]
                    );
                    $opciones = $opciones + $op;
                }
                $ContratacionpersonalForm->get('tipo_vinculacion')->setValueOptions($opciones);

                $Contratacionpersonal = new Contratacionpersonal($this->dbAdapter);
                if($id2!=0){
                    $datBase = $Contratacionpersonal->getContratacionpersonalById($id2);
                    if($datBase!=null){
                        $ContratacionpersonalForm->get('tipo_vinculacion')->setValue($datBase[0]["tipo_vinculacion"]);
                        $ContratacionpersonalForm->get('personas')->setValue($datBase[0]["personas"]);
                        $ContratacionpersonalForm->get('objeto')->setValue($datBase[0]["objeto"]);
                        $ContratacionpersonalForm->get('justificacion')->setValue($datBase[0]["justificacion"]);
                        $ContratacionpersonalForm->get('valor')->setValue($datBase[0]["valor"]);
                        $ContratacionpersonalForm->get('submit')->setValue("Actualizar");
                    }
                }
                $view = new ViewModel(array(
                    'form' => $ContratacionpersonalForm,
                    'titulo' => "Contratación de servicios profesionales o personal técnico de apoyo",
                    'id' => $id,
                    'id2' => $id2
                ));
                return $view;
            }
        }
        else{
            return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/mensajeadministrador/index');
        }
    }


    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Contratacionpersonal($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarContratacionpersonal($id);

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
		$evento = 'Eliminación de contratación de servicios profesionales : ' . $id . ' (mgc_contratacionpersonal)';
		$ad->addAuditoriadet($evento, $resul);

        $this->flashMessenger()->addMessage("Servicio eliminado con éxito.");
        return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . '/application/editaraplicari/index/' . $id2);
    }

    public function delAction()
    {
        $ContratacionpersonalForm = new ContratacionpersonalForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Contratacionpersonal($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $ContratacionpersonalForm,
            'titulo' => "Eliminar servicio",
            'url' => $this->getRequest()->getBaseUrl(),
            'id' => $id,
            'id2' => $id2
        ));
        return $view;
    }
}