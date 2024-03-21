<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\RequisitosdocForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Requisitosdoc;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class RequisitosdocController extends AbstractActionController
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

        $not = new Requisitosdoc($this->dbAdapter);

        //define el campo ciudad
        $id = (int) $this->params()->fromRoute('id',0);
        $id2 = $this->params()->fromRoute('id2',0);
        $id3 = $this->params()->fromRoute('id3',0);

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
            
            $auth = $this->auth;
            
            // verifica si esta conectado
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $rol = new Roles($this->dbAdapter);
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
            }
            
            // obtiene la informacion de las pantallas
            $data = $this->request->getPost();
            $RequisitosdocForm = new RequisitosdocForm();
            // adiciona la noticia
           
            if($id3 != 0){
                $resultado = $not->updateRequisitosdocAll($id3, $data);
                // redirige a la pantalla de inicio del controlador
                if ($resultado == 1) {
                    $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                    $evento = 'Modificación de documentos requeridos de la convocatoria : ' . $resultado . ' (mgc_requisitos_doc)';

                    $this->flashMessenger()->addMessage("Requisito modificado con exito");
                    return $this->redirect()->toUrl($this->getRequest()
                        ->getBaseUrl() . '/application/editarconvocatoriai/index/'.$id."/".$id2);
                } else {
                    $this->flashMessenger()->addMessage("La modificación del requisito falló");
                    return $this->redirect()->toUrl($this->getRequest()
                        ->getBaseUrl() . '/application/requisitos/index');
                }
            }else{
                $resultado = $not->addRequisitosdoc($data, $id);

                // redirige a la pantalla de inicio del controlador
                if ($resultado == 1) {
                    $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                    $evento = 'Creacion de documentos requeridos de la convocatoria : ' . $resultado . ' (mgc_requisitos_doc)';
                    $ad->addAuditoriadet($evento, $resul);

                    $this->flashMessenger()->addMessage("Requisito creado con exito");
                    return $this->redirect()->toUrl($this->getRequest()
                        ->getBaseUrl() . '/application/editarconvocatoriai/index/' . $id);
                } else {
                    $this->flashMessenger()->addMessage("La creacion del Requisito fallo");
                    return $this->redirect()->toUrl($this->getRequest()
                        ->getBaseUrl() . '/application/requisitos/index');
                }
            }
        } else {
            
            $RequisitosdocForm = new RequisitosdocForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Requisitosdoc($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $filter = new StringTrim();
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(28) as $uni) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $uni["id_valor_flexible"] => $uni["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $RequisitosdocForm->add(array(
                'name' => 'id_tipo_doc',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Seleccione documento requerido : ',
                    'value_options' => $opciones
                )
            ));
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(29) as $uni) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $uni["id_valor_flexible"] => $uni["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $RequisitosdocForm->add(array(
                'name' => 'id_tipo_ponderacion',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Seleccione el tipo de ponderacion : ',
                    'value_options' => $opciones
                )
            ));
            
            // verificar roles
            $per = array(
                'id_rol' => ''
            );
            $dat = array(
                'id_rol' => ''
            );
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $permiso = new Permisos($this->dbAdapter);
            
            // me verifica el tipo de rol asignado al usuario
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $dat) {
                $dat["id_rol"];
            }
            
            if ($dat["id_rol"] != '') {
                // me verifica el permiso sobre la pantalla
                $pantalla = "Requisitos";
                $panta = 0;
                $pt = new Agregarvalflex($this->dbAdapter);
                
                foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
                    $panta["id_valor_flexible"];
                }
                
                $permiso->verificarPermiso($dat["id_rol"], $panta["id_valor_flexible"]);
                foreach ($permiso->verificarPermiso($dat["id_rol"], $panta["id_valor_flexible"]) as $per) {
                    $per["id_rol"];
                }
            }
            
            if($id3 != 0){
                $resultado = $not->getRequisitosdocById($id3);
                if($resultado != null){
                    $RequisitosdocForm->get('id_tipo_doc')->setValue($resultado[0]["id_tipo_doc"]);
                    $RequisitosdocForm->get('descripcion')->setValue($resultado[0]["descripcion"]);
                    $RequisitosdocForm->get('fecha_limite')->setValue($resultado[0]["fecha_limite"]);
                    $RequisitosdocForm->get('hora_limite')->setValue(trim($resultado[0]["hora_limite"]));
                    $RequisitosdocForm->get('responsable')->setValue($resultado[0]["responsable"]);
                }        
            }
            $view = new ViewModel(array(
                'form' => $RequisitosdocForm,
                'titulo' => "Documentos requeridos (requisitos)",
                'url' => $this->getRequest()->getBaseUrl(),
                'id' => $id,
                'id2' => $id2,
                'id3' => $id3,
                'menu' => $dat["id_rol"]
            ));
            return $view;
        }
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Requisitosdoc($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarRequisitosdoc($id);

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
        $evento = 'Eliminación de documentos requeridos de la convocatoria : ' . $id . ' (mgc_requisitos_doc)';
        $ad->addAuditoriadet($evento, $resul);

        $this->flashMessenger()->addMessage("Requisito eliminado con exito");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editarconvocatoriai/index/' . $id2);
    }

    public function delAction()
    {
        $RequisitosdocForm = new RequisitosdocForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Requisitosdoc($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $RequisitosdocForm,
            'titulo' => "Eliminar registro",
            'url' => $this->getRequest()->getBaseUrl(),
            'datos' => $id,
            'id2' => $id2
        ));
        return $view;
    }
}