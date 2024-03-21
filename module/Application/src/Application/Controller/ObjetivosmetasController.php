<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\ObjetivosmetasForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Objetivosmetas;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class ObjetivosmetasController extends AbstractActionController
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
      
        $filter = new StringTrim();
        //verificar roles
        $per=array('id_rol'=>'');
        $dat=array('id_rol'=>'');
        $rolusuario = new Rolesusuario($this->dbAdapter);
        $permiso = new Permisos($this->dbAdapter);

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
        
        if ($this->getRequest()->isPost()) {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Objetivosmetas($this->dbAdapter);
            $auth = $this->auth;
    
            $identi = $auth->getStorage()->read();
            $data = $this->request->getPost();
            $id = (int) $this->params()->fromRoute('id', 0);
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            $id3 = (int) $this->params()->fromRoute('id3', 0);
            if($id3!=0){
                $resul = $u->updateObjetivometa($id3, $data);
                $evento = 'Edición de metas : ' . $id3 . ' (mgc_objetivometas)';
            }else{
                $resul = $u->addObjetivosmetas($data, $id2);
                $evento = 'Creación de metas : ' . $resul . ' (mgc_objetivometas)';
            }
            
            if ($resul == 1) {
                $result = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                $ad->addAuditoriadet($evento, $result);

                $this->flashMessenger()->addMessage("Meta agregada con éxito.");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/editaraplicari/index/' . $id);
            } else {
                $this->flashMessenger()->addMessage("La meta no pudo ser agregada.");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/editaraplicari/index/' . $id);
            }
            return $view;
        } else {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Rolesusuario($this->dbAdapter);
            $ObjetivosmetasForm = new ObjetivosmetasForm();
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            $id = (int) $this->params()->fromRoute('id', 0);
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            $id3 = (int) $this->params()->fromRoute('id3', 0);

            if($id3!=0){
                   $u = new Objetivosmetas($this->dbAdapter);
                   $datBase = $u->getObjetivometaById($id3);
                    if($datBase!=null){
                        $ObjetivosmetasForm->get('meta')->setValue($datBase[0]["meta"]);    
                        $ObjetivosmetasForm->get('submit')->setValue("Actualizar");
                    }
                }
            
            $view = new ViewModel(array(
                'form' => $ObjetivosmetasForm,
                'titulo' => "Metas",
                'url' => $this->getRequest ()->getBaseUrl (),
                'id' => $id,
                'id2' => $id2,
                'id3' => $id3
            ));
            return $view; 
        }  
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Objetivosmetas($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $id3 = (int) $this->params()->fromRoute('id3', 0);
        $u->eliminarObjetivosmetas($id3);

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
		$evento = 'Eliminación de metas : ' . $id . ' (mgc_objetivometas)';
		$ad->addAuditoriadet($evento, $resul);

        $this->flashMessenger()->addMessage("Objetivo eliminado con éxito.");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editaraplicari/index/' . $id);
    }

    public function delAction()
    {
        $ObjetivosmetasForm = new ObjetivosmetasForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Objetivosmetas($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $id3 = (int) $this->params()->fromRoute('id3', 0);
        
        $view = new ViewModel(array(
            'form' => $ObjetivosmetasForm,
            'titulo' => "Objetivo eliminado con éxito.",
            'url' => $this->getRequest()->getBaseUrl(),
            'id' => $id,
            'id2' => $id2,
            'id3' => $id3
        ));
        return $view;
    }
}