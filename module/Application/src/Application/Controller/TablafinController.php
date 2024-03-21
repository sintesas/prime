<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\TablafinForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Tablafin;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Agregarvalflex;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class TablafinController extends AbstractActionController
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
            // Creacion adaptador de conexion, objeto de datos, auditoria
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $not = new Tablafin($this->dbAdapter);
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
            
            $TablafinForm = new TablafinForm();
            // adiciona la noticia
            $id = (int) $this->params()->fromRoute('id', 0);
            $resul = $not->getTablafinexiste($data, $id);
            
            if ($resul == 0) {
                $resultado = $not->addTablafin($data, $id);
                
                $resul = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                $evento = 'Creacion de tabla de financiiación : ' . $resultado . ' (mgc_financiacion)';
                $ad->addAuditoriadet($evento, $resul);
            } else {
                $this->flashMessenger()->addMessage("La creacion del registro fallo, no se pueden duplicar rubros");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/editarconvocatoriai/index/' . $id);
            }
            
            // redirige a la pantalla de inicio del controlador
            if ($resultado == 1) {
                $this->flashMessenger()->addMessage("Registro creado con exito en la tabla de financiacion");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/tablafin/index/' . $id);
            } else {
                $this->flashMessenger()->addMessage("La creacion del Requisito fallo");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/tablafin/index');
            }
        } else {
            
            $TablafinForm = new TablafinForm();
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Tablafin($this->dbAdapter);
            $pt = new Agregarvalflex($this->dbAdapter);
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            $filter = new StringTrim();
            
            //RUBRO
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(32) as $fuente) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $fuente["id_valor_flexible"] => $fuente["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $TablafinForm->add(array(
                'name' => 'id_rubro',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Rubro: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'required' => 'required'
                )
            ));
            
            //FUENTE
            
            $vf = new Agregarvalflex($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getArrayvalores(31) as $fuente) {
                $op = array(
                    '' => ''
                );
                $op = $op + array(
                    $fuente["id_valor_flexible"] => $fuente["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $TablafinForm->add(array(
                'name' => 'id_fuente',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Fuente: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'required' => 'required'
                )
            ));
            
            //Estado
            
            $TablafinForm->add(array(
                'name' => 'id_estado',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'Estado: ',
                    'value_options' => $opciones
                ),
                'attributes' => array(
                    'required' => 'required'
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
            
            if (true) {
                $id = (int) $this->params()->fromRoute('id', 0);
                $valflex = new Agregarvalflex($this->dbAdapter);
                
                $view = new ViewModel(array(
                    'form' => $TablafinForm,
                    'titulo' => "Tabla Financiación de Convocatoria",
                    'url' => $this->getRequest()->getBaseUrl(),
                    'Tablafin' => $u->getTablafin($id),
                    'Tablafinm' => $u->getTablafinorder($id),
                    'valflex' => $valflex->getValoresf(),
                    'id' => $id,
                    'menu' => $dat["id_rol"]
                ));
                return $view;
            } else {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/mensajeadministrador/index');
            }
        }
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Tablafin($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarTablafin($id);

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
        $evento = 'Eliminación de tabla de financiación : ' . $id . ' (mgc_financiacion)';
        $ad->addAuditoriadet($evento, $resul);

        $this->flashMessenger()->addMessage("Registro eliminado con exito");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editarconvocatoriai/index/' . $id2);
    }

    public function delAction()
    {
        $TablafinForm = new TablafinForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Tablafin($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $TablafinForm,
            'titulo' => "Eliminar registro",
            'url' => $this->getRequest()->getBaseUrl(),
            'datos' => $id,
            'id2' => $id2
        ));
        return $view;
    }
}