<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Application\Convocatoria\CoinvestigadoresForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Modelo\Entity\Rolesusuario;
use Application\Modelo\Entity\Usuarios;
use Application\Modelo\Entity\Coinvestigadores;
use Application\Modelo\Entity\Agregarvalflex;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Application\Modelo\Entity\Roles;
use Application\Modelo\Entity\Permisos;
use Application\Modelo\Entity\Auditoria;
use Application\Modelo\Entity\Auditoriadet;

class CoinvestigadoresController extends AbstractActionController
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
        
        $identi=$auth->getStorage()->read();
        
        $au = new Auditoria($this->dbAdapter);
		$ad = new Auditoriadet($this->dbAdapter);
        
        if ($this->getRequest()->isPost()) {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Coinvestigadores($this->dbAdapter);
            $data = $this->request->getPost();
            $id = (int) $this->params()->fromRoute('id', 0);
            $id2 = (int) $this->params()->fromRoute('id2', 0);
            if($id2!=0){
                $resul=$u->updateCoinvestigadores($id2, $data);
                $evento = 'Edición de coinvestigadores : ' . $id2 . ' (mgc_coinvestigadores)';
            }else{
                $resul = $u->addCoinvestigadores($data, $id);
                $evento = 'Creación de coinvestigadores : ' . $resul . ' (mgc_coinvestigadores)';
            }
            
            if ($resul == 1) {
                $result = $au->getAuditoriaid(session_id(), get_real_ip(), $identi->id_usuario);
                $ad->addAuditoriadet($evento, $result);

                $this->flashMessenger()->addMessage("Coinvestigador agregado correctamente.");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/editaraplicari/index/' . $id);
            } else {
                $this->flashMessenger()->addMessage("El coinvestigador no se pudo agregar.");
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/editaraplicari/index/' . $id2);
            }
        } else {
            $this->dbAdapter = $this->getServiceLocator()->get('db1');
            $u = new Rolesusuario($this->dbAdapter);
            $CoinvestigadoresForm = new CoinvestigadoresForm();
            $auth = $this->auth;
            
            $identi = $auth->getStorage()->read();
            if ($identi == false && $identi == null) {
                return $this->redirect()->toUrl($this->getRequest()
                    ->getBaseUrl() . '/application/login/index');
            }
            
            // define el campo ciudad
            $vf = new Roles($this->dbAdapter);
            $opciones = array();
            foreach ($vf->getRoles() as $dat) {
                $op = array(
                    $dat["id_rol"] => $dat["descripcion"]
                );
                $opciones = $opciones + $op;
            }
  
            $us = new Usuarios($this->dbAdapter);
            $id = (int) $this->params()->fromRoute('id', 0);
            
            $per = array(
                'id_rol' => ''
            );
            $datusu = array(
                'id_rol' => ''
            );
            $rolusuario = new Rolesusuario($this->dbAdapter);
            $permiso = new Permisos($this->dbAdapter);
            
            // me verifica el tipo de rol asignado al usuario
            $rolusuario->verificarRolusuario($identi->id_usuario);
            foreach ($rolusuario->verificarRolusuario($identi->id_usuario) as $datusu) {
                $datusu["id_rol"];
            }
            
            if ($datusu["id_rol"] != '') {
                // me verifica el permiso sobre la pantalla
                $pantalla = "editarredinv";
                $panta = 0;
                $pt = new Agregarvalflex($this->dbAdapter);
                
                foreach ($pt->getValoresflexdesc($pantalla) as $panta) {
                    $panta["id_valor_flexible"];
                }
                
                $permiso->verificarPermiso($datusu["id_rol"], $panta["id_valor_flexible"]);
                foreach ($permiso->verificarPermiso($datusu["id_rol"], $panta["id_valor_flexible"]) as $per) {
                    $per["id_rol"];
                }
            }

            $opciones = array();
            foreach ($pt->getArrayvalores(25) as $da) {
                $op = array(
                    $da["id_valor_flexible"] => $da["descripcion_valor"]
                );
                $opciones = $opciones + $op;
            }
            $CoinvestigadoresForm->get('tipo_documento')->setValueOptions($opciones);

            $id2 = (int) $this->params()->fromRoute('id2', 0);
            if($id2!=0){
                $u = new Coinvestigadores($this->dbAdapter);
                $datBase = $u->getCoinvestigadoresById($id2);
                if($datBase!=null){
                    $CoinvestigadoresForm->get('tipo_documento')->setValue($datBase[0]["id_tipodocumento"]);
                    $CoinvestigadoresForm->get('documento')->setValue($datBase[0]["documento"]);
                    $CoinvestigadoresForm->get('apellidos')->setValue($datBase[0]["apellidos"]);
                    $CoinvestigadoresForm->get('nombres')->setValue($datBase[0]["nombres"]);
                    $CoinvestigadoresForm->get('profesion')->setValue($datBase[0]["profesion"]);
                    $CoinvestigadoresForm->get('intitucion')->setValue($datBase[0]["intitucion"]);
                    $CoinvestigadoresForm->get('telefono')->setValue($datBase[0]["telefono"]);
                    $CoinvestigadoresForm->get('email')->setValue($datBase[0]["email"]);
                    $CoinvestigadoresForm->get('horas')->setValue($datBase[0]["horas"]);
                    $CoinvestigadoresForm->get('submit')->setValue("Actualizar");
                }
            }

            $view = new ViewModel(array(
                'form' => $CoinvestigadoresForm,
                'titulo' => "Agregar coinvestigadores",
                'id' => $id,
                'id2' => $id2,
                'menu' => $datusu["id_rol"]
            ));
            return $view;
            
        }
    }

    public function eliminarAction()
    {
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Coinvestigadores($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        $u->eliminarCoinvestigadores($id);

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
		$evento = 'Eliminación de coinvestigadores : ' . $id . ' (mgc_coinvestigadores)';
		$ad->addAuditoriadet($evento, $resul);

        $this->flashMessenger()->addMessage("Datos de coinvestigador eliminados con éxito.");
        return $this->redirect()->toUrl($this->getRequest()
            ->getBaseUrl() . '/application/editaraplicari/index/' . $id2);
    }

    public function delAction()
    {
        $CoinvestigadoresForm = new CoinvestigadoresForm();
        $this->dbAdapter = $this->getServiceLocator()->get('db1');
        $u = new Coinvestigadores($this->dbAdapter);
        // print_r($data);
        $id = (int) $this->params()->fromRoute('id', 0);
        $id2 = (int) $this->params()->fromRoute('id2', 0);
        
        $view = new ViewModel(array(
            'form' => $CoinvestigadoresForm,
            'titulo' => "Eliminar datos de contacto",
            'url' => $this->getRequest()->getBaseUrl(),
            'id' => $id,
            'id2' => $id2
        ));
        return $view;
    }
}